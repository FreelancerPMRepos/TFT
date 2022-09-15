/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from 'react-redux';
import _ from 'lodash';
import {
  View,
  // Animated,
  TouchableOpacity,
  FlatList,
  TextInput,
  Linking,
  Platform,
  RefreshControl,
  findNodeHandle,
  Dimensions,
  Alert,
} from 'react-native';
import Animated, {
  Clock,
  Value,
  timing,
  stopClock,
  interpolate,
  Easing,
  event,
} from 'react-native-reanimated';
import {withInAppNotification} from '@libs/react-native-in-app-notification';
import {BlurView} from '@react-native-community/blur';
import {Image, Text, HotelItem, SafeAreaView, BookingTime} from '@components';
import {getStatusBarHeight} from 'react-native-status-bar-height';
import {
  BaseStyle,
  BaseColor,
  Images,
  GreenColor,
  setStatusbar,
  FontFamily,
} from '@config';
import styles from './styles';
import {getApiData} from '../../utils/apiHelper';
import categoryName, {
  periodTypes,
  getCurrentPeriod,
} from '../../config/category';
import MyLoader, {ListLoader} from '../../components/CContentLoder';
import CLoader from '../../components/CLoader';
import CAlert from '../../components/CAlert';
import {BaseSetting} from '../../config/setting';
import {translate} from '../../lang/Translate';
import CNoDataFound from '../../components/CNoDataFound';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import Feather from 'react-native-vector-icons/Feather';
import {NavigationEvents} from 'react-navigation';
import FilterActions from '../../redux/reducers/filter/actions';
import {bindActionCreators} from 'redux';
import authActions from '../../redux/reducers/auth/actions';
import bookingAction from '../../redux/reducers/booking/actions';
import {FloatingAction} from 'react-native-floating-action';
import MapView, {Marker, Callout, PROVIDER_GOOGLE} from 'react-native-maps';
import {StarRating} from '@components';
import * as Utils from '@utils';
import {SharedElement} from 'react-navigation-shared-element';
import LottieView from 'lottie-react-native';
import moment from 'moment';
import {ScrollView} from 'react-native-gesture-handler';
import LocationModal from 'app/components/LocationModal';
import {
  getLatLng,
  getCurrentFilterType,
  getCurrencySymbol,
} from 'app/utils/booking';
import messaging from '@react-native-firebase/messaging';
import firebase from '@react-native-firebase/app';
import FastImage from 'react-native-fast-image';

const IOS = Platform.OS === 'ios';
const timeout = 500;
let animationTimeout;
const {width: wWidth, height: wHeight} = Dimensions.get('window');
const AnimatedFlatList = Animated.createAnimatedComponent(FlatList);

const ASPECT_RATIO = wWidth / wHeight;
const LATITUDE_DELTA = 0.0922;
const LONGITUDE_DELTA = LATITUDE_DELTA * ASPECT_RATIO;

const selected = {
  location: [],
};

let facilityIcons = [
  {
    category: categoryName.pools,
    image: Images.menu_pools,
    imageBkg: Images.pools,
  },
  {
    category: categoryName.chalets,
    image: Images.menu_chalets,
    imageBkg: Images.chalets,
  },
  {
    category: categoryName.camps,
    image: Images.menu_camps,
    imageBkg: Images.camps,
  },
];

class Home extends Component {
  constructor(props) {
    super(props);
    this.scrollRef = React.createRef();
    this.map = React.createRef();

    /*  */
    const activeFacilities = this.getActiveFacilities();
    console.log('Construction ==> ', activeFacilities);

    // Temp data define
    this.state = {
      viewRef: null,
      isLoading: false,
      searchInput: '',
      filter: props.filter,
      icons: facilityIcons,
      listViewOption: 'listView',
      selectedCategory: activeFacilities[0].category,
      catBkgImage: activeFacilities[0].imageBkg,
      poolsData: {
        itemList: [],
        pageCount: 1,
        loadMore: true,
        isRefreshing: false,
      },
      chaletsData: {
        itemList: [],
        pageCount: 1,
        loadMore: true,
        isRefreshing: false,
      },
      campsData: {
        itemList: [],
        pageCount: 1,
        loadMore: true,
        isRefreshing: false,
      },
      actions: [
        {
          text: '',
          icon: <Icon name="view-list" size={20} color="#fff" />,
          name: 'ListView',
          position: 3,
        },
        {
          text: '',
          icon: <Icon name="view-grid" size={20} color="#fff" />,
          name: 'GridView',
          position: 2,
        },
        {
          text: '',
          icon: <Icon name="google-maps" size={20} color="#fff" />,
          name: 'MapView',
          position: 1,
        },
      ],
      detailVisible: false,
      previewData: {},
      mapCardVisible: new Animated.Value(0),
      showSearch: false,
      locationModalVisible: false,
      cSymbol: this.getSymbol(),
      showAnimation: false,
    };
    this._deltaY = new Animated.Value(0);
    this.clock = new Clock();
    this.scrollY = new Value(0);
    this.transY = new Value(0);

    this.onScroll = event([
      {
        nativeEvent: {contentOffset: {y: this.scrollY}},
      },
    ]);

    this.filterValuesScale = new Value(1);
  }

  componentDidUpdate(prevProps, prevState) {
    const {selectedCategory} = this.state;
    const {
      auth: {disableFacilityData},
    } = this.props;

    console.log(this.props.poolFilters, prevProps.poolFilters);
    if (
      (!_.isEqual(
        this.props.filter.resetFilter,
        prevProps.filter.resetFilter,
      ) &&
        this.props.filter.resetFilter === true) ||
      !_.isEqual(this.props.poolFilters, prevProps.poolFilters) ||
      !_.isEqual(this.props.chaletFilters, prevProps.chaletFilters) ||
      !_.isEqual(this.props.campFilters, prevProps.campFilters) ||
      !_.isEqual(this.props.auth, prevProps.auth)
    ) {
      const searchStr = !_.isEqual(
        this.props.auth.country,
        prevProps.auth.country,
      )
        ? 'COUNTRY_CHANGE'
        : '';
      this.getItemsListAPICall(searchStr);
    }

    if (
      !_.isEqual(
        this.props[getCurrentFilterType(selectedCategory)].desiredLocation,
        prevProps[getCurrentFilterType(selectedCategory)].desiredLocation,
      )
    ) {
      selected.location = this.props[
        getCurrentFilterType(selectedCategory)
      ].desiredLocation;
    }

    if (
      !_.isEqual(
        this.props.filter.filterDataType,
        prevProps.filter.filterDataType,
      )
    ) {
      this.resetBookingData();
    }

    if (
      !_.isEqual(
        this.props.auth.disableFacilityData,
        prevProps.auth.disableFacilityData,
      )
    ) {
      const defaultFacility =
        disableFacilityData && disableFacilityData.default_category
          ? disableFacilityData.default_category
          : 'Pools';
      if (defaultFacility === 'Chalets') {
        facilityIcons = [
          {
            category: categoryName.chalets,
            image: Images.menu_chalets,
            imageBkg: Images.chalets,
          },
          {
            category: categoryName.pools,
            image: Images.menu_pools,
            imageBkg: Images.pools,
          },
          {
            category: categoryName.camps,
            image: Images.menu_camps,
            imageBkg: Images.camps,
          },
        ];
        this.updateSelectedCategory(facilityIcons[0]);
      } else if (defaultFacility === 'Camps') {
        facilityIcons = [
          {
            category: categoryName.camps,
            image: Images.menu_camps,
            imageBkg: Images.camps,
          },
          {
            category: categoryName.chalets,
            image: Images.menu_chalets,
            imageBkg: Images.chalets,
          },
          {
            category: categoryName.pools,
            image: Images.menu_pools,
            imageBkg: Images.pools,
          },
        ];
        this.updateSelectedCategory(facilityIcons[0]);
      } else {
        facilityIcons = [
          {
            category: categoryName.pools,
            image: Images.menu_pools,
            imageBkg: Images.pools,
          },
          {
            category: categoryName.camps,
            image: Images.menu_camps,
            imageBkg: Images.camps,
          },
          {
            category: categoryName.chalets,
            image: Images.menu_chalets,
            imageBkg: Images.chalets,
          },
        ];
        this.updateSelectedCategory(facilityIcons[0]);
      }
    }
  }

  removeDisableFacility = data => {
    const {icons} = this.state;
    console.log('Home -> icons -> Before', icons, data);
    Object.assign(icons[0], {isActive: data.pools});
    Object.assign(icons[1], {isActive: data.chalets});
    Object.assign(icons[2], {isActive: data.camps});
    console.log('Home -> icons -> After', icons);
    const isDisable = _.remove(icons, function(item) {
      return item.isActive === '0';
    });
    this.updateSelectedCategory(icons[0] ? icons[0] : {});
  };

  async componentDidMount() {
    // this.props.navigation.navigate('NoInternet');
    this.checkApplicationPermission();

    const {
      auth: {url},
      authActions: {setInitialUrl},
    } = this.props;
    if (Platform.OS === 'android') {
      try {
        Linking.getInitialURL().then(url => {
          console.log('componentDidMount -> url', url);
          if (url && !url.includes('com.googleusercontent.apps')) {
            this.navigate(url);
          }
        });
      } catch (error) {
        console.log('componentDidMount -> error', error);
      }
    } else {
      if (url && !url.includes('com.googleusercontent.apps')) {
        this.navigate(url);
        setInitialUrl('');
      }
      Linking.addEventListener('url', e => {
        if (!e.url.includes('com.googleusercontent.apps')) {
          this.handleOpenURL(e);
        }
      });
    }
    const {
      FilterActions: {setFilterType},
    } = this.props;
    const activeFacilities = this.getActiveFacilities();
    setFilterType(activeFacilities[0].category);

    this.getItemsListAPICall('', () => {
      this.getCountries();
    });
    this.setStatusbar();
    this.resetBookingData();
  }

  checkApplicationPermission = async () => {
    const hasPermission = await messaging().hasPermission();
    console.log(
      'checkApplicationPermission -> authorizationStatus',
      hasPermission,
    );

    try {
      const enabled =
        hasPermission === messaging.AuthorizationStatus.AUTHORIZED ||
        hasPermission === messaging.AuthorizationStatus.PROVISIONAL;
      if (!enabled) {
        const authorizationStatus = await messaging().requestPermission();
        if (authorizationStatus === messaging.AuthorizationStatus.AUTHORIZED) {
          console.log('User has notification permissions enabled.');
        } else if (
          authorizationStatus === messaging.AuthorizationStatus.PROVISIONAL
        ) {
          console.log('User has provisional notification permissions.');
        } else {
          console.log('User has notification permissions disabled');
        }
      }
      this.getFCMToken();
    } catch (error) {
      console.log('Home -> checkApplicationPermission -> error', error);
    }
  };

  getFCMToken = async () => {
    const fcmToken = await messaging().getToken();
    console.log('fcmToken');
    console.log(fcmToken);
    try {
      this.setFCMListeners();
    } catch (error) {
      console.log('getFCMToken -> error', error);
    }
  };

  setFCMListeners = async () => {
    try {
      this.onTokenRefreshListener = messaging().onTokenRefresh(fcmToken => {
        if (fcmToken) {
          console.log('setFCMListeners -> fcmToken', fcmToken);
        }
      });

      /* If App is foreground and receives a notification */
      try {
        messaging().onMessage(async remoteMessage => {
          console.log(
            remoteMessage,
            'A new FCM message arrived!',
            JSON.stringify(remoteMessage),
          );
          this.handlePushMessage(remoteMessage);
        });
      } catch (error) {
        console.log('setFCMListeners -> error', error);
      }

      /* If App openend from a Notification */
      messaging().onNotificationOpenedApp(async remoteMessage => {
        console.log(
          'Notification caused app to open from background state:',
          remoteMessage,
        );
        this.handlePushMessage(remoteMessage, true);
      });

      // Check whether an initial notification is available
      messaging()
        .getInitialNotification()
        .then(async remoteMessage => {
          if (remoteMessage) {
            // clearNotificationsList();
            console.log(
              'Notification caused app to open from quit state:',
              remoteMessage,
            );
            this.handlePushMessage(remoteMessage);
          }
        });
    } catch (error) {
      console.log('setFCMListeners -> error', error);
    }
  };

  handlePushMessage = async (remoteMessage, directAction = false) => {
    if (_.has(remoteMessage, 'data.fcm_options.image')) {
      console.log('Loading FCM Image ===> ');
      await FastImage.preload([
        {
          uri: remoteMessage.data.fcm_options.image,
        },
      ]);
    }

    this.pushTap = () => {
      const {data} = remoteMessage;
      if (data.url) {
        Linking.openURL(data.url);
      } else if (data.facility_type && data.facility_id) {
        const {navigate} = this.props.navigation;
        navigate('HotelDetail', {
          itemID: data.facility_id,
          selectedCategory: data.facility_type,
        });
      }
    };

    if (directAction) {
      this.pushTap();
      return;
    }

    const {
      notification: {title, body},
    } = remoteMessage;
    this.props.showNotification({
      title: title ? title : 'New Notification',
      message: body ? body : 'New Notification',
      icon: _.has(remoteMessage, 'data.fcm_options.image')
        ? {uri: remoteMessage.data.fcm_options.image}
        : null,
      onPress: () => {
        this.pushTap();
      },
    });
  };

  componentWillUnmount() {
    Linking.removeEventListener('url', e => {
      this.handleOpenURL(e);
    });
  }

  setStatusbar() {
    /* Set Statusbar to match */
    setStatusbar('light');
  }

  handleOpenURL(event) {
    try {
      this.navigate(event.url);
    } catch (error) {
      console.log('Error==>', error);
    }
  }

  navigate = url => {
    const {navigate} = this.props.navigation;

    if (url && url !== null) {
      const urlSplit = url.split('?');
      let urlParams = urlSplit[1]
        .split('&') // ["a=b454","c=dhjjh","f=g6hksdfjlksd"]
        .map(_.partial(_.split, _, '=', 2)); // [["a","b454"],["c","dhjjh"],["f","g6hksdfjlksd"]]

      urlParams = _.fromPairs(urlParams); // {"a":"b454","c":"dhjjh","f":"g6hksdfjlksd"}

      if (urlParams.id && urlParams.type) {
        navigate('HotelDetail', {
          itemID: urlParams.id,
          selectedCategory: urlParams.type,
        });
      }
    }
  };

  getItemsListAPICall = (word = '', cb = false) => {
    const {auth} = this.props;
    console.log('Home -> getItemsListAPICall -> auth', auth);
    const {country} = this.props.auth;
    const countryCode =
      country && !_.isEmpty(country) && country.value ? country.value : '+973';
    console.log('Home -> getItemsListAPICall -> countryCode', countryCode);
    const {
      filter: {
        position,
        filterDataType,
        allFilters: {poolFilters, chaletFilters, campFilters},
        allFilters,
      },
    } = this.props;
    let data = {};
    let {
      selectedCategory,
      poolsData,
      chaletsData,
      campsData,
      searchInput,
    } = this.state;
    const {
      language: {languageData},
    } = this.props;

    /* Country change handle */
    if (word === 'COUNTRY_CHANGE') {
      word = '';
      poolsData.itemList = [];
      chaletsData.itemList = [];
      campsData.itemList = [];
      poolsData.loadMore = false;
      chaletsData.loadMore = false;
      campsData.loadMore = false;
      poolsData.pageCount = 1;
      chaletsData.pageCount = 1;
      campsData.pageCount = 1;
    }

    const poolLat =
      allFilters && allFilters.poolFilters && allFilters.poolFilters.lat
        ? allFilters.poolFilters.lat
        : 0;
    const poolLng =
      allFilters && allFilters.poolFilters && allFilters.poolFilters.lng
        ? allFilters.poolFilters.lng
        : 0;
    const chaletLat =
      allFilters && allFilters.chaletFilters && allFilters.chaletFilters.lat
        ? allFilters.chaletFilters.lat
        : 0;
    const chaletLng =
      allFilters && allFilters.chaletFilters && allFilters.chaletFilters.lng
        ? allFilters.chaletFilters.lng
        : 0;
    const campLat =
      allFilters && allFilters.campFilters && allFilters.campFilters.lat
        ? allFilters.campFilters.lat
        : 0;
    const campLng =
      allFilters && allFilters.campFilters && allFilters.campFilters.lng
        ? allFilters.campFilters.lng
        : 0;
    const poolAmenities =
      allFilters && allFilters.poolFilters && allFilters.poolFilters.amenities
        ? allFilters.poolFilters.amenities
        : [];
    const chaletAmenities =
      allFilters &&
      allFilters.chaletFilters &&
      allFilters.chaletFilters.amenities
        ? allFilters.chaletFilters.amenities
        : [];
    const campAmenities =
      allFilters && allFilters.campFilters && allFilters.campFilters.amenities
        ? allFilters.campFilters.amenities
        : [];
    if (auth.isConnected) {
      this.setState(
        {
          isLoading: true,
        },
        () => {
          let pageCount = {};
          let UrlString = '';
          let date = '';
          let endDate = '';

          const resetFlag =
            allFilters && allFilters.resetFilter
              ? allFilters.resetFilter
              : _.isEmpty(
                  this.state[getCurrentFilterType(selectedCategory, 'data')],
                );
          if (
            selectedCategory === categoryName.pools ||
            filterDataType === categoryName.pools
          ) {
            // Apply filter conditions
            pageCount = poolsData.pageCount;
            if (this.checkResetFilter() || resetFlag) {
              pageCount = 1;
              poolsData = Object.assign(poolsData, {
                itemList: [],
                pageCount: 1,
              });
              this.setState({
                poolsData,
              });
            }

            console.log('Home -> getItemsListAPICall -> poolsData', poolsData);
            // pageCount = 1;
            UrlString = BaseSetting.endpoints.getPools;
            // send redux filter data in api call.
            if (poolAmenities === 'None Selected') {
              data.amenities = [];
            } else {
              data.amenities = poolAmenities;
            }
            // data.countryCode = countryCode;
            data.morning = poolFilters.byPeriod === 'Morning' ? true : false;
            data.evening = poolFilters.byPeriod === 'Evening' ? true : false;
            data.fullday = poolFilters.byPeriod === 'Full Day' ? true : false;
            data.isArebic = languageData === 'ar';
            data.period =
              poolFilters && poolFilters.startPeriod
                ? poolFilters.startPeriod.id
                : 1;
            data.endPeriod =
              poolFilters && poolFilters.endPeriod
                ? poolFilters.endPeriod.id
                : 1;
            data.water_type = poolFilters.waterType;
            date = poolFilters.startDate
              ? moment(poolFilters.startDate).format('YYYY-MM-DD')
              : poolFilters.byDate
              ? moment(poolFilters.byDate).format('YYYY-MM-DD')
              : '';
            endDate = poolFilters.endDate
              ? moment(poolFilters.endDate).format('YYYY-MM-DD')
              : '';
            data.minPrice =
              poolFilters.minPrice === 0 ? '' : poolFilters.minPrice;
            data.maxPrice =
              poolFilters.maxPrice === 0 ? '' : poolFilters.maxPrice;
            let locationNew = '';
            console.log(
              'LAtt==>',
              poolFilters.desiredLocation[0],
              poolLat,
              poolLng,
            );
            if (
              poolFilters.desiredLocation[0] === 'Near me' &&
              ((poolLat !== 0 && poolLng !== 0) ||
                (position && position.coords))
            ) {
              data.city = '';
              data.lat =
                position && position.coords
                  ? Number(position.coords.latitude)
                  : poolLat;
              data.lng =
                position && position.coords
                  ? Number(position.coords.longitude)
                  : poolLng;
            } else {
              _.each(poolFilters.desiredLocation, (item, key) => {
                locationNew +=
                  key === poolFilters.desiredLocation.length - 1
                    ? "'" + item + "'"
                    : "'" + item + "',";
              });
            }
            data.city = locationNew === "'Everywhere'" ? "'All'" : locationNew;
            // send redux filter data in api call.
            // }
          } else if (
            selectedCategory === categoryName.chalets ||
            filterDataType === categoryName.chalets
          ) {
            // ========================= For chalet facility  ====================
            UrlString = BaseSetting.endpoints.getChalets;
            pageCount = chaletsData.pageCount;
            if (this.checkResetFilter() || resetFlag) {
              pageCount = 1;
              chaletsData = Object.assign(chaletsData, {
                itemList: [],
                pageCount: 1,
              });
              this.setState({
                chaletsData,
              });
            }
            // send redux filter data in api call.
            if (chaletAmenities === 'None Selected') {
              data.amenities = [];
            } else {
              data.amenities = chaletAmenities;
            }
            data.isArebic = languageData === 'ar';
            date = chaletFilters.startDate
              ? moment(chaletFilters.startDate).format('YYYY-MM-DD')
              : chaletFilters.byDate
              ? moment(chaletFilters.byDate).format('YYYY-MM-DD')
              : '';
            endDate = chaletFilters.endDate
              ? moment(chaletFilters.endDate).format('YYYY-MM-DD')
              : '';
            let locationNew = '';
            if (
              chaletFilters.desiredLocation[0] === 'Near me' &&
              ((chaletLat !== 0 && chaletLng !== 0) ||
                (position && position.coords))
            ) {
              data.city = '';
              data.lat =
                position && position.coords
                  ? Number(position.coords.latitude)
                  : chaletLat;
              data.lng =
                position && position.coords
                  ? Number(position.coords.longitude)
                  : chaletLng;
            } else {
              _.each(chaletFilters.desiredLocation, (item, key) => {
                locationNew +=
                  key === chaletFilters.desiredLocation.length - 1
                    ? "'" + item + "'"
                    : "'" + item + "',";
              });
            }
            data.minPrice =
              chaletFilters.minPrice === 0 ? '' : chaletFilters.minPrice;
            data.maxPrice =
              chaletFilters.maxPrice === 0 ? '' : chaletFilters.maxPrice;
            data.city = locationNew === "'Everywhere'" ? "'All'" : locationNew;
            // send redux filter data in api call.
          } else if (
            selectedCategory === categoryName.camps ||
            filterDataType === categoryName.camps
          ) {
            // ========================= For camp facility  ====================
            pageCount = campsData.pageCount;
            if (this.checkResetFilter() || resetFlag) {
              pageCount = 1;
              campsData = Object.assign(campsData, {
                itemList: [],
                pageCount: 1,
              });
              this.setState({
                campsData,
              });
            }
            // Apply filter conditions
            UrlString = BaseSetting.endpoints.getCamps;
            // send redux filter data in api call.
            if (campAmenities === 'None Selected') {
              data.amenities = [];
            } else {
              data.amenities = campAmenities;
            }
            date = campFilters.startDate
              ? moment(campFilters.startDate).format('YYYY-MM-DD')
              : campFilters.byDate
              ? moment(campFilters.byDate).format('YYYY-MM-DD')
              : '';
            endDate = campFilters.endDate
              ? moment(campFilters.endDate).format('YYYY-MM-DD')
              : '';
            data.isArebic = languageData === 'ar';
            data.minPrice =
              campFilters.minPrice === 0 ? '' : campFilters.minPrice;
            data.maxPrice =
              campFilters.maxPrice === 0 ? '' : campFilters.maxPrice;
            let locationNew = '';
            if (
              // campFilters.desiredLocation[0] === 'Near me' &&
              // campLat !== 0 &&
              // campLng !== 0
              campFilters.desiredLocation[0] === 'Near me' &&
              ((campLat !== 0 && campLng !== 0) ||
                (position && position.coords))
            ) {
              data.city = '';
              data.lat =
                position && position.coords
                  ? Number(position.coords.latitude)
                  : campLat;
              data.lng =
                position && position.coords
                  ? Number(position.coords.longitude)
                  : campLng;
            } else {
              _.each(campFilters.desiredLocation, (item, key) => {
                locationNew +=
                  key === campFilters.desiredLocation.length - 1
                    ? "'" + item + "'"
                    : "'" + item + "',";
              });
            }
            data.city = locationNew === "'Everywhere'" ? "'All'" : locationNew;
            // send redux filter data in api call.
            // }
          }
          data.request = true;
          data.page = pageCount;
          data.count = 10;
          data.countryCode = countryCode;
          data.date = date;
          data.end_date = endDate;
          data.user_id =
            _.isObject(auth.userData) && auth.userData.ID
              ? auth.userData.ID
              : 0;
          data.searchString = word ? word : searchInput;
          if (word !== '') {
            data.page = 1;
          }

          console.log(
            'Home -> getItemsListAPICall -> data',
            data,
            UrlString,
            pageCount,
          );
          // return;
          getApiData(UrlString, 'post', data)
            .then(async result => {
              console.log('Home -> getItemsListAPICall -> result', result);
              if (_.isObject(result)) {
                if (_.isBoolean(result.status) && result.status === true) {
                  let poolsObj = JSON.parse(JSON.stringify(poolsData));
                  let chaletsObj = JSON.parse(JSON.stringify(chaletsData));
                  let campsObj = JSON.parse(JSON.stringify(campsData));
                  if (_.isArray(result.data) && result.data.length > 0) {
                    if (selectedCategory === categoryName.pools) {
                      /* IF Search word is passed */
                      if (word !== '') {
                        poolsObj.itemList = result.data;
                        poolsObj.pageCount = 2;
                      } else {
                        poolsObj.itemList = await _.concat(
                          poolsData.itemList,
                          result.data,
                        );
                        poolsObj.pageCount = result.isLast
                          ? poolsData.pageCount
                          : poolsData.pageCount + 1;
                      }
                      poolsObj.loadMore = !result.isLast;
                      poolsObj.isRefreshing = false;
                    } else if (selectedCategory === categoryName.chalets) {
                      if (word !== '') {
                        chaletsObj.itemList = result.data;
                        chaletsObj.pageCount = 2;
                      } else {
                        chaletsObj.itemList = _.concat(
                          chaletsData.itemList,
                          result.data,
                        );
                        chaletsObj.pageCount = result.isLast
                          ? chaletsObj.pageCount
                          : chaletsObj.pageCount + 1;
                      }
                      chaletsObj.loadMore = !result.isLast;
                      chaletsObj.isRefreshing = false;
                    } else if (selectedCategory === categoryName.camps) {
                      if (word !== '') {
                        campsObj.itemList = result.data;
                        campsObj.pageCount = 2;
                      } else {
                        campsObj.itemList = await _.concat(
                          campsData.itemList,
                          result.data,
                        );
                        campsObj.pageCount = result.isLast
                          ? campsObj.pageCount
                          : campsObj.pageCount + 1;
                      }
                      campsObj.loadMore = !result.isLast;
                      campsObj.isRefreshing = false;
                    }
                    this.setState(
                      {
                        poolsData: poolsObj,
                        chaletsData: chaletsObj,
                        campsData: campsObj,
                        isLoading: false,
                      },
                      () => {
                        if (cb) {
                          cb();
                        }
                      },
                    );
                  } else {
                    if (word) {
                      poolsObj.itemList = [];
                      chaletsObj.itemList = [];
                      campsObj.itemList = [];
                      poolsObj.loadMore = false;
                      chaletsObj.loadMore = false;
                      campsObj.loadMore = false;
                      poolsObj.pageCount = 1;
                      chaletsObj.pageCount = 1;
                      campsObj.pageCount = 1;
                      this.setState({
                        poolsData: poolsObj,
                        chaletsData: chaletsObj,
                        campsData: campsObj,
                        isLoading: false,
                      });
                    } else {
                      console.log('GO in else when word is empty');
                    }
                  }
                } else {
                  let poolsObj = JSON.parse(JSON.stringify(poolsData));
                  let chaletsObj = JSON.parse(JSON.stringify(chaletsData));
                  let campsObj = JSON.parse(JSON.stringify(campsData));
                  poolsObj.itemList = [];
                  poolsObj.pageCount = 1;
                  chaletsObj.itemList = [];
                  chaletsObj.pageCount = 1;
                  campsObj.itemList = [];
                  campsObj.pageCount = 1;
                  poolsObj.loadMore = false;
                  poolsObj.isRefreshing = false;
                  chaletsObj.loadMore = false;
                  chaletsObj.isRefreshing = false;
                  campsObj.loadMore = false;
                  campsObj.isRefreshing = false;

                  this.setState(
                    {
                      poolsData: poolsObj,
                      chaletsData: chaletsObj,
                      campsData: campsObj,
                      isLoading: false,
                    },
                    () => {
                      if (cb) {
                        cb();
                      }
                    },
                  );
                }
              } else {
                let chData = JSON.parse(JSON.stringify(chaletsData));
                chData.itemList = [];
                let poolsObj = poolsData;
                let chaletsObj = chData;
                let campsObj = campsData;

                poolsObj.loadMore = false;
                poolsObj.isRefreshing = false;
                chaletsObj.loadMore = false;
                chaletsData.isRefreshing = false;
                campsObj.loadMore = false;
                campsObj.isRefreshing = false;

                this.setState(
                  {
                    poolsData: poolsObj,
                    chaletsData: chaletsObj,
                    campsData: campsObj,
                    isLoading: false,
                  },
                  () => {
                    if (cb) {
                      cb();
                    }
                    CAlert(translate('went_wrong'), translate('alert'));
                  },
                );
              }
            })
            .catch(err => {
              console.log(`Error: ${err}`);
            });
        },
      );
    } else {
      let poolsObj = poolsData;
      let chaletsObj = chaletsData;
      let campsObj = campsData;

      poolsObj.isRefreshing = false;
      chaletsObj.isRefreshing = false;
      campsObj.isRefreshing = false;

      this.setState(
        {
          poolsData: poolsObj,
          chaletsData: chaletsObj,
          campsData: campsObj,
          isLoading: false,
        },
        () => {
          CAlert(translate('Internet'), translate('alert'));
        },
      );
    }
  };

  getSymbol = (retVal = false) => {
    const {country} = this.props.auth;
    const cSymbol = getCurrencySymbol(country);
    console.log('getCurrencySymbol===', cSymbol);
    if (retVal) {
      return cSymbol;
    }
    this.setState({cSymbol});
  };

  getCountries = (cb = false) => {
    const {
      FilterActions: {setFilters},
      filter: {allFilters},
    } = this.props;
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
    const {country} = this.props.auth;
    const countryCode =
      country && !_.isEmpty(country) && country.value ? country.value : '+973';
    console.log('Home -> getCountries -> countryCode', countryCode);
    const data = {
      request: true,
      apiVersion: 2,
      country_code: countryCode,
    };
    console.log('Home -> getCountries -> data', data);
    getApiData(BaseSetting.endpoints.getCountries, 'post', data)
      .then(result => {
        console.log('Home -> getCountries -> result', result);
        if (result && result.status && result.data) {
          if (result.data.poolCities) {
            fData.poolCities = result.data.poolCities
              ? result.data.poolCities
              : [];
          }
          if (result.data.chaletesCities) {
            fData.chaletesCities = result.data.chaletesCities
              ? result.data.chaletesCities
              : [];
          }
          if (result.data.campsCities) {
            fData.campsCities = result.data.campsCities
              ? result.data.campsCities
              : [];
          }
          if (result.data.countries) {
            fData.allCountries = result.data.countries
              ? result.data.countries
              : [];
          }
          if (result.data.poolAmenities) {
            fData.poolAmenities = result.data.poolAmenities
              ? result.data.poolAmenities
              : [];
          }
          if (result.data.chaletAmenities) {
            fData.chaletAmenities = result.data.chaletAmenities
              ? result.data.chaletAmenities
              : [];
          }
          if (result.data.campAmenities) {
            fData.campAmenities = result.data.campAmenities
              ? result.data.campAmenities
              : [];
          }
          if (result.data.poolMinMaxPrice) {
            fData.poolMinMaxPrice = result.data.poolMinMaxPrice
              ? result.data.poolMinMaxPrice
              : {};
          }
          if (result.data.chaletMinMaxPrice) {
            fData.chaletMinMaxPrice = result.data.chaletMinMaxPrice
              ? result.data.chaletMinMaxPrice
              : {};
          }
          if (result.data.campMinMaxPrice) {
            fData.campMinMaxPrice = result.data.campMinMaxPrice
              ? result.data.campMinMaxPrice
              : {};
          }
          if (result.data.disable_and_default_facility) {
            const disableData = result.data.disable_and_default_facility;
            const {
              authActions: {disableFacilityData},
              auth,
            } = this.props;

            if (!_.isEqual(disableData, auth.disableFacilityData)) {
              console.log(
                'UPDATE DISABLED FAC: YES',
                disableData,
                auth.disableFacilityData,
              );
              disableFacilityData(disableData);
            }
          }

          const NearBy = {
            id: 0,
            city: 'Near me',
            city_AR: 'بجانبي',
            checked: false,
          };
          //Add new Near Me option in Cities
          fData.poolCities.unshift(NearBy);
          fData.chaletesCities.unshift(NearBy);
          fData.campsCities.unshift(NearBy);
          setFilters(fData);
          if (cb) {
            cb();
          }
        } else {
          fData.poolCities = [];
          fData.chaletesCities = [];
          fData.campsCities = [];
          fData.allCountries = [];

          setFilters(fData);
          if (cb) {
            cb();
          }
        }
      })
      .catch(err => {
        console.log(`Error: ${err}`);
        if (cb) {
          cb();
        }
      });
  };
  showLoginAlert = (message, title = 'alert') => {
    const {
      navigation,
      authActions: {setUserData},
    } = this.props;
    CAlert(
      translate(message),
      translate(title),
      () => {
        setUserData({});
        navigation.navigate('Start');
      },
      () => {},
      translate('Go'),
    );
  };

  disableFacility = () => {
    const {auth} = this.props;

    if (auth.isConnected) {
      let UrlString = BaseSetting.endpoints.disableFacility;

      let data = {
        apiVersion: 2,
      };

      getApiData(UrlString, 'post', data)
        .then(result => {
          console.log('disableFacility -> result', result);
          if (result) {
            if (result.data && result.data.Disables) {
              const disableData = result.data.Disables;
              const {
                authActions: {disableFacilityData},
              } = this.props;

              if (!_.isEqual(disableData, auth.disableFacilityData)) {
                console.log(
                  'UPDATE DISABLED FAC: YES',
                  disableData,
                  auth.disableFacilityData,
                );
                disableFacilityData(disableData);
              } else {
                console.log('UPDATE DISABLED FAC: NO');
              }
            }
          }
        })
        .catch(err => {
          console.log(`Error: ${err}`);
        });
    }
  };

  setBookMarkAPICall = (itemID, isBookMarked) => {
    const {auth} = this.props;
    const {selectedCategory, poolsData, chaletsData, campsData} = this.state;

    if (auth.isConnected) {
      let serviceType = '';

      let UrlString = '';
      let showAnim = false;

      if (isBookMarked) {
        UrlString = BaseSetting.endpoints.removeBookMark;
        showAnim = false;
      } else {
        UrlString = BaseSetting.endpoints.addBookMark;
        showAnim = true;
      }

      if (selectedCategory === categoryName.pools) {
        serviceType = 'pool';
      } else if (selectedCategory === categoryName.chalets) {
        serviceType = 'chalet';
      } else if (selectedCategory === categoryName.camps) {
        serviceType = 'camp';
      }

      let data = {
        service_id: itemID,
        service_type: serviceType,
        user_id:
          _.isObject(auth.userData) && auth.userData.ID ? auth.userData.ID : 0,
      };

      this.setState({showAnimation: showAnim}, () => {
        getApiData(UrlString, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (_.isBoolean(result.status) && result.status === true) {
                let poolsObj = poolsData;
                let chaletsObj = chaletsData;
                let campsObj = campsData;

                if (selectedCategory === categoryName.pools) {
                  let itemList = poolsObj.itemList;
                  let itemIndex = itemList.findIndex(
                    item => item.ID === itemID,
                  );
                  itemList[itemIndex].isBookmarked = !isBookMarked;
                  poolsObj.itemList = itemList;
                } else if (selectedCategory === categoryName.chalets) {
                  let itemList = chaletsObj.itemList;
                  let itemIndex = itemList.findIndex(
                    item => item.ID === itemID,
                  );
                  itemList[itemIndex].isBookmarked = !isBookMarked;
                  chaletsObj.itemList = itemList;
                } else if (selectedCategory === categoryName.camps) {
                  let itemList = campsObj.itemList;
                  let itemIndex = itemList.findIndex(
                    item => item.ID === itemID,
                  );
                  itemList[itemIndex].isBookmarked = !isBookMarked;
                  campsObj.itemList = itemList;
                }

                this.setState({
                  poolsData: poolsObj,
                  chaletsData: chaletsObj,
                  campsData: campsObj,
                });
              } else {
                CAlert(
                  _.isString(result.message)
                    ? result.message
                    : translate('went_wrong'),
                  translate('alert'),
                );
              }
            } else {
              CAlert(translate('went_wrong'), translate('alert'));
            }
          })
          .catch(err => {
            console.log(`Error: ${err}`);
          });
      });
    } else {
      CAlert(translate('Internet'), translate('alert'));
    }
  };
  updateSelectedCategory = item => {
    console.log('item ====>', item);
    const {
      FilterActions: {setFilterType},
    } = this.props;
    let isLoadPage = false;
    const {poolsData, chaletsData, campsData} = this.state;
    if (item) {
      if (item.category === categoryName.pools) {
        setFilterType(item.category);
        poolsData.itemList.length > 0
          ? (isLoadPage = false)
          : (isLoadPage = true);
      } else if (item.category === categoryName.chalets) {
        setFilterType(item.category);
        chaletsData.itemList.length > 0
          ? (isLoadPage = false)
          : (isLoadPage = true);
      } else if (item.category === categoryName.camps) {
        setFilterType(item.category);
        campsData.itemList.length > 0
          ? (isLoadPage = false)
          : (isLoadPage = true);
      }
      Utils.enableExperimental();
      this.setState(
        {
          selectedCategory: item.category,
          catBkgImage: item.imageBkg,
        },
        () => {
          isLoadPage ? this.getItemsListAPICall() : null;
        },
      );
    }
  };

  getActiveFacilities = () => {
    const activeIcons = facilityIcons.filter((item, index) => {
      console.log(
        'Conditions==>',
        item.category.toLowerCase(),
        item,
        _.has(
          this.props,
          `auth.disableFacilityData.${item.category.toLowerCase()}`,
        ),
        Number(
          this.props.auth.disableFacilityData[item.category.toLowerCase()],
        ) === 0,
      );
      if (
        _.has(
          this.props,
          `auth.disableFacilityData.${item.category.toLowerCase()}`,
        ) &&
        Number(
          this.props.auth.disableFacilityData[item.category.toLowerCase()],
        ) === 0
      ) {
        return false;
      }
      return true;
    });
    console.log('getActiveFacilities -> activeIcons', this.props, activeIcons);
    return activeIcons;
  };

  renderIconService() {
    const {selectedCategory, listViewOption} = this.state;
    let textOpacity = interpolate(this.scrollY, {
      inputRange: [180, 190],
      outputRange: [1, 0],
      extrapolate: 'clamp',
    });

    const translateY = interpolate(this.scrollY, {
      inputRange: [170, 200],
      outputRange: [0, 20],
      extrapolate: 'clamp',
    });

    const tCWidth = wWidth - 40;
    const width = interpolate(this.scrollY, {
      inputRange: [0, 200],
      outputRange: [tCWidth * 0.8, tCWidth * 0.5],
      extrapolate: 'clamp',
    });

    const mL = interpolate(this.scrollY, {
      inputRange: [0, 200],
      outputRange: [tCWidth * 0.1 * (Utils.isRTL ? -1 : 1), 0],
      extrapolate: 'clamp',
    });

    const backOpacity = interpolate(this.scrollY, {
      inputRange: [50, 100],
      outputRange: [0, 1],
      extrapolate: 'clamp',
    });

    let nextType = 'ListView';
    let nextTypeIcon = 'square';
    let nextTitle = translate('Card');

    if (listViewOption === 'listView') {
      nextType = 'MapView';
      nextTypeIcon = 'map-pin';
      nextTitle = translate('Map');
    }

    if (listViewOption === 'mapView') {
      nextType = 'GridView';
      nextTypeIcon = 'grid';
      nextTitle = translate('Grid');
    }

    const activeFacilities = this.getActiveFacilities();

    return (
      <View
        style={{
          flexDirection: 'row',
          justifyContent: 'flex-start',
          alignItems: 'flex-start',
          width: '100%',
          position: 'relative',
        }}>
        <View
          style={{
            flexDirection: 'row',
            justifyContent: 'space-between',
            alignItems: 'center',
            position: 'absolute',
            right: 0,
          }}>
          <Animated.View
            style={[
              {
                justifyContent: 'center',
                alignItems: 'center',
                opacity: backOpacity,
                transform: [{translateY}],
                marginRight: 10,
              },
            ]}>
            <TouchableOpacity
              style={[styles.iconContent]}
              onPress={() => {
                this.resetBookingData();
                this.props.navigation.navigate('Search');
              }}>
              <Feather name="sliders" size={18} color={BaseColor.grayColor} />
            </TouchableOpacity>
            <Animated.Text
              footnote
              grayColor
              style={{
                opacity: textOpacity,
                fontSize: 10,
                fontFamily: FontFamily.bold,
                marginTop: 5,
              }}>
              {translate('Filter')}
            </Animated.Text>
          </Animated.View>
          <Animated.View
            style={{
              justifyContent: 'center',
              alignItems: 'center',
              transform: [{translateY}],
              opacity: backOpacity,
            }}>
            <TouchableOpacity
              onPress={() => {
                this.handleGridOption(nextType);
              }}
              style={[
                styles.iconContent,
                {backgroundColor: BaseColor.primaryColor},
              ]}>
              <Feather
                name={nextTypeIcon}
                size={18}
                color={BaseColor.whiteColor}
              />
            </TouchableOpacity>
            <Animated.Text
              footnote
              grayColor
              style={{
                opacity: textOpacity,
                fontSize: 10,
                fontFamily: FontFamily.bold,
                marginTop: 5,
              }}>
              {nextTitle}
            </Animated.Text>
          </Animated.View>
        </View>
        <Animated.View
          style={{
            flexDirection: 'row',
            justifyContent:
              activeFacilities.length === 3 ? 'space-between' : 'space-around',
            alignItems: 'center',
            transform: [{translateY}, {translateX: mL}],
            width,
            position: 'relative',
            zIndex: 1,
          }}>
          {activeFacilities.map((item, index) => {
            return (
              <TouchableOpacity
                key={index}
                style={{
                  alignItems: 'center',
                  justifyContent: 'center',
                  // width: 100,
                }}
                activeOpacity={0.9}
                onPress={() => {
                  this.updateSelectedCategory(item);
                  this.resetBookingData();
                  this.map && !_.isUndefined(this.map)
                    ? this.reCenterMap()
                    : null;
                }}>
                <View
                  style={[
                    styles.iconContent,
                    item.category === selectedCategory
                      ? {backgroundColor: BaseColor.primaryColor}
                      : null,
                  ]}>
                  <Image
                    tintColor={
                      item.category === selectedCategory
                        ? BaseColor.whiteColor
                        : BaseColor.primaryColor
                    }
                    source={item.image}
                    style={styles.img}
                  />
                </View>
                <Animated.Text
                  footnote
                  grayColor
                  bold
                  style={{
                    opacity: textOpacity,
                    fontSize: 10,
                    fontFamily: FontFamily.bold,
                    marginTop: 5,
                  }}>
                  {translate(item.category)}
                </Animated.Text>
              </TouchableOpacity>
            );
          })}
        </Animated.View>
      </View>
    );
  }
  clearSearchInput = () => {
    if (!_.isEmpty(this.state.searchInput)) {
      this.setState({searchInput: ''}, () => {
        this.getItemsListAPICall();
      });
    }
  };

  triggerSearchShow = () => {
    Utils.enableExperimental();
    this.setState({showSearch: !this.state.showSearch}, () => {
      if (this.state.showSearch && this.searchInput) {
        this.searchInput.focus();
      }
    });
  };

  getCityList = () => {
    const {
      filter: {allFilters},
    } = this.props;
    const {selectedCategory} = this.state;
    if (selectedCategory) {
      let citiesArray =
        allFilters &&
        _.has(allFilters, `${getCurrentFilterType(selectedCategory, 'city')}`)
          ? allFilters[getCurrentFilterType(selectedCategory, 'city')]
          : [];

      let selectedCities = selected.location;

      citiesArray &&
        citiesArray.map((city, i) => {
          if (_.isArray(selectedCities) && !_.isEmpty(selectedCities)) {
            const fIndex = selectedCities.findIndex(
              scity => scity === city.city,
            );
            if (fIndex > -1) {
              city.checked = true;
            } else {
              city.checked = false;
            }
          } else if (i === 1) {
            city.checked = true;
          }
        });

      console.log('Location modal City ===> ', citiesArray);
      return citiesArray;
    }
  };

  translateCity = cityName => {
    const {
      allFilters,
      language: {languageData},
    } = this.props;
    const {selectedCategory} = this.state;

    if (languageData === 'ar') {
      let citiesArray =
        allFilters &&
        _.has(allFilters, `${getCurrentFilterType(selectedCategory, 'city')}`)
          ? allFilters[getCurrentFilterType(selectedCategory, 'city')]
          : [];

      console.log('citiesArray ===> ', citiesArray);
      let selectedCity = citiesArray.find(cityObj => {
        return cityObj.city === cityName;
      });
      console.log(selectedCity);
      if (selectedCity && selectedCity.city_AR) {
        return selectedCity.city_AR;
      }
    }
    return cityName;
  };

  onChangeLocation = select => {
    const location = this.getCityList();
    console.log('On Change Location ====> ', location, select);
    selected.location = [];
    location.map((item, key) => {
      if (item.city === select.city) {
        // selected.location.push(item.city);
        item.checked = !item.checked;
        // console.log('item====>', item, select);
      } else if (select.id < 2 || (select.id >= 2 && item.id < 2)) {
        console.log('item====>', item, select);
        item.checked = false;
      }
      console.log('item.checked', item.checked);
      if (item.checked) {
        selected.location.push(item.city);
      }
      console.log('selected location ====>', selected);
    });

    if (selected.location.length > 0) {
      this.setState(
        {
          selectedLocation: selected.location,
        },
        () => {
          if (select.city === 'Near me') {
            try {
              this.getLatLng();
            } catch (error) {
              console.log('onChangeLocation -> error', error);
              CAlert('Permission Denied');
            }
          }
        },
      );
    }
  };

  getLatLng = async () => {
    console.log('getLatLng -> getLatLng');
    const {
      FilterActions: {setPosition},
    } = this.props;

    try {
      const position = await getLatLng();
      console.log('Got position from GPS ===> ', position);
      if (!_.isEmpty(position)) {
        setPosition(position);
        this.setState({
          lat: Number(position.coords.latitude),
          lng: Number(position.coords.longitude),
        });
      } else {
        this.setState({locationModalVisible: false});
      }
    } catch (err) {
      console.log('Got position from GPS ===> ', err);
      this.setState({locationModalVisible: false});
      selected.location = ['Everywhere'];
    }
  };

  saveFilterValue = (type, value) => {
    console.log('saveFilterValue -> type, value', type, value);
    const {
      FilterActions: {setFilters},
      filter: {allFilters},
    } = this.props;
    const {selectedCategory} = this.state;
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};

    if (type === 'period') {
      fData.poolFilters.byPeriod = value;
    }
    if (type === 'location') {
      fData[getCurrentFilterType(selectedCategory)].desiredLocation = value;
    }

    /* Always Reset to page 1 and data = [] on filter change */
    fData.resetFilter = true;
    setFilters(fData);
    console.log('Props===>', this.props);
  };

  renderFilters = () => {
    const {
      allFilters,
      booking: {bookingData},
    } = this.props;
    const {selectedCategory} = this.state;
    /* Display Booking date */
    console.log('allFilters==', allFilters);
    const cFilters = allFilters[getCurrentFilterType(selectedCategory)];
    const cFilterStartDate =
      cFilters.startDate !== '' ? moment(cFilters.startDate) : '';
    const cFilterEndDate =
      cFilters.endDate !== '' ? moment(cFilters.endDate) : '';
    const startingDate =
      cFilterStartDate && cFilterStartDate !== ''
        ? cFilterStartDate.format('MMM DD')
        : '';

    console.log('allFilters ===> ', cFilters, bookingData);
    const endingDate =
      cFilterStartDate &&
      cFilterStartDate !== '' &&
      cFilterEndDate &&
      cFilterEndDate !== '' &&
      cFilterStartDate.format('MMM') !== cFilterEndDate.format('MMM')
        ? cFilterEndDate.format('MMM DD')
        : cFilterEndDate && cFilterEndDate !== ''
        ? cFilterEndDate.format('DD')
        : '';

    return (
      <>
        <ScrollView
          horizontal={true}
          style={[styles.filterScroll]}
          showsHorizontalScrollIndicator={false}>
          <View style={[styles.filterValue]}>
            <TouchableOpacity
              style={styles.filterBtn}
              onPress={() => {
                if (this.bookingTimeCmp) {
                  console.log(
                    'renderFilters -> this.bookingTimeCmp',
                    this.bookingTimeCmp,
                  );
                  this.bookingTimeCmp.openModal(true);
                }
              }}>
              <Text bold footnote style={styles.filterValueLabel}>
                {translate('date')}
              </Text>
              {startingDate && endingDate ? (
                <Text bold title2 style={styles.filterValueText}>
                  {startingDate} - {endingDate}
                </Text>
              ) : (
                <Text bold title2 style={styles.filterValueText}>
                  {translate('choose_date')}
                </Text>
              )}
            </TouchableOpacity>
          </View>
          {cFilters.desiredLocation.map((lc, li) => (
            <View style={[styles.filterValue]} key={li}>
              <TouchableOpacity
                style={styles.filterBtn}
                onPress={() => {
                  console.log('pressed');
                  this.setState({
                    locationModalVisible: true,
                  });
                }}>
                <Text bold footnote style={styles.filterValueLabel}>
                  {console.log('Location===>', lc)}
                  {translate('location')}
                </Text>
                <Text bold title2 style={styles.filterValueText}>
                  {this.translateCity(lc)}
                </Text>
              </TouchableOpacity>
            </View>
          ))}
          {/* {getCurrentFilterType(selectedCategory) === 'poolFilters' && (
            <View style={[styles.filterValue]}>
              <TouchableOpacity
                style={styles.filterBtn}
                onPress={() => {
                  if (this.bookingTimeCmp) {
                    this.bookingTimeCmp.openPeriodModal(true);
                  }
                }}>
                <Text bold footnote style={styles.filterValueLabel}>
                  {translate('period')}
                </Text>
                {cFilters.byPeriod === '' ? (
                  <Text bold title2 style={styles.filterValueText}>
                    {translate('choose_period')}
                  </Text>
                ) : cFilters.byPeriod === translate('Full_Day') ||
                  cFilters.byPeriod === 'Full Day' ? (
                  <Text bold title2 style={styles.filterValueText}>
                    {console.log('cFilters===>', cFilters)}
                    {translate('Full_Day')}
                  </Text>
                ) : (
                  <Text bold title2 style={styles.filterValueText}>
                    {cFilters.byPeriod
                      ? cFilters.byPeriod
                      : _.has(bookingData, 'startPeriod.title')
                      ? bookingData.startPeriod.title
                      : translate('Full_Day')}
                    {_.has(bookingData, 'endPeriod.title')
                      ? `- ${bookingData.endPeriod.title}`
                      : ''}
                  </Text>
                )}
              </TouchableOpacity>
            </View>
          )} */}
        </ScrollView>
      </>
    );
  };

  renderHeader = () => {
    const {navigation} = this.props;

    const translateY = interpolate(this.scrollY, {
      inputRange: [0, 200],
      outputRange: [0, -150],
      extrapolate: 'clamp',
    });

    const opacity = interpolate(this.scrollY, {
      inputRange: [0, 80],
      outputRange: [1, 0],
      extrapolate: 'clamp',
    });

    const opacity1 = interpolate(this.scrollY, {
      inputRange: [80, 160],
      outputRange: [1, 0],
      extrapolate: 'clamp',
    });

    // const resetFilter = this.checkResetFilter();
    const {searchInput, showSearch, listViewOption} = this.state;

    let nextType = 'ListView';
    let nextTypeIcon = 'square';

    if (listViewOption === 'listView') {
      nextType = 'MapView';
      nextTypeIcon = 'map-pin';
    }

    if (listViewOption === 'mapView') {
      nextType = 'GridView';
      nextTypeIcon = 'grid';
    }

    return (
      <Animated.View style={[styles.absHeader, {transform: [{translateY}]}]}>
        {IOS ? (
          <BlurView style={styles.absHeaderBlur} blurType="prominent" />
        ) : (
          <View
            style={[
              styles.absHeaderBlur,
              {
                backgroundColor: '#FFF',
                borderBottomWidth: 1.3,
                borderColor: '#ccc',
              },
            ]}
          />
        )}
        <Animated.View
          style={[
            {
              width: '100%',
              paddingTop: (IOS ? getStatusBarHeight() : 0) + 20,
              opacity,
              flexDirection: 'row',
              justifyContent: 'space-between',
              alignItems: 'center',
              paddingHorizontal: 26,
            },
          ]}>
          <Image
            source={Images.blue_logo}
            resizeMode={'cover'}
            style={styles.imageBackground}
          />
          <View style={{flexDirection: 'row'}}>
            <Animated.View
              style={[
                styles.iconContent,
                styles.rightBigIcon,
                {
                  opacity,
                  marginRight: 10,
                },
              ]}>
              <TouchableOpacity
                style={styles.filterIcon}
                onPress={() => navigation.navigate('Search')}>
                <Feather name="sliders" size={18} color={BaseColor.grayColor} />
              </TouchableOpacity>
            </Animated.View>
            <Animated.View
              style={[
                styles.iconContent,
                styles.rightBigIcon,
                {
                  backgroundColor: BaseColor.primaryColor,
                },
              ]}>
              <TouchableOpacity
                onPress={() => {
                  this.handleGridOption(nextType);
                }}
                style={[styles.filterIcon]}>
                <Feather
                  name={nextTypeIcon}
                  size={18}
                  color={BaseColor.whiteColor}
                />
              </TouchableOpacity>
            </Animated.View>
          </View>
        </Animated.View>
        {/* Search Bar */}
        <View style={styles.searchForm}>
          <View style={styles.searchBoxContainer}>
            <Animated.View
              style={[
                {
                  flex: 1,
                  opacity: opacity1,
                },
                styles.inputWrapper,
              ]}>
              <View style={styles.filtersWrap}>
                {showSearch ? (
                  <TextInput
                    ref={cmp => (this.searchInput = cmp)}
                    style={[BaseStyle.textInput, styles.searchInput]}
                    onChangeText={newSearchInput =>
                      this.setState({searchInput: newSearchInput}, () => {
                        if (newSearchInput.trim().length >= 2) {
                          this.getItemsListAPICall(newSearchInput);
                        }
                      })
                    }
                    autoCorrect={false}
                    placeholder={translate('looking_for')}
                    placeholderTextColor={BaseColor.grayColor}
                    value={this.state.searchInput}
                    selectionColor={BaseColor.primaryColor}
                    onSubmitEditing={() => {
                      this.getItemsListAPICall(this.state.searchInput);
                    }}
                  />
                ) : (
                  this.renderFilters()
                )}
                <View>
                  <TouchableOpacity
                    style={[styles.filterIcon, styles.searchIcon]}
                    onPress={() => {
                      if (showSearch) {
                        this.clearSearchInput();
                        if (searchInput.trim().length > 0) {
                          return;
                        }
                      }
                      this.triggerSearchShow();
                    }}>
                    <Feather
                      name={showSearch ? 'x' : 'search'}
                      size={18}
                      color={BaseColor.whiteColor}
                    />
                  </TouchableOpacity>
                </View>
              </View>
            </Animated.View>
          </View>
          {this.renderIconService()}
        </View>
      </Animated.View>
    );
  };

  renderFooter = () => {
    const {auth} = this.props;
    const {selectedCategory, poolsData, chaletsData, campsData} = this.state;
    let msg = '';
    let loadMore = true;
    let itemList = [];

    if (selectedCategory === categoryName.pools) {
      loadMore = poolsData.loadMore;
      itemList = poolsData.itemList;
      msg = auth.isConnected
        ? translate('load_msg') + ' ' + translate('Pools')
        : translate('load_error') + ' ' + translate('Camps');
    } else if (selectedCategory === categoryName.chalets) {
      loadMore = chaletsData.loadMore;
      itemList = chaletsData.itemList;
      msg = auth.isConnected
        ? translate('load_msg') + ' ' + translate('Chalets')
        : translate('load_error') + ' ' + translate('Camps');
    } else if (selectedCategory === categoryName.camps) {
      loadMore = campsData.loadMore;
      itemList = campsData.itemList;
      msg = auth.isConnected
        ? translate('load_msg') + ' ' + translate('Camps')
        : translate('load_error') + ' ' + translate('Camps');
    }
    return loadMore && itemList.length > 0 ? (
      <View
        style={{
          flex: 1,
          justifyContent: 'center',
          alignItems: 'center',
          padding: 10,
        }}>
        <Text grayColor>{msg}</Text>
        {auth.isConnected ? (
          <CLoader />
        ) : (
          <TouchableOpacity onPress={this.getItemsListAPICall}>
            <Text style={{color: BaseColor.primaryColor}}>
              {translate('Retry')}
            </Text>
          </TouchableOpacity>
        )}
      </View>
    ) : null;
  };

  renderEmpty = () => {
    const {selectedCategory, isLoading, listViewOption} = this.state;

    if (selectedCategory === categoryName.pools && !isLoading) {
      return (
        <View style={[styles.animationWrap]}>
          <CNoDataFound
            msgNoData={translate('No_Pools')}
            imageSource={Images.pools_nodata}
          />
        </View>
      );
    } else if (selectedCategory === categoryName.chalets && !isLoading) {
      return (
        <CNoDataFound
          msgNoData={translate('No_Chalets')}
          imageSource={Images.pools_nodata}
        />
      );
    } else if (selectedCategory === categoryName.camps && !isLoading) {
      return (
        <CNoDataFound
          msgNoData={translate('No_Camps')}
          imageSource={Images.pools_nodata}
        />
      );
    }

    const renderMap = [1, 2, 3];
    return renderMap.map(() => {
      if (listViewOption === 'listView') {
        return (
          <View style={{justifyContent: 'center', alignItems: 'center'}}>
            <ListLoader />
          </View>
        );
      } else {
        return <MyLoader />;
      }
    });
  };

  onEndReached = () => {
    const {
      isLoading,
      selectedCategory,
      poolsData,
      chaletsData,
      campsData,
    } = this.state;
    let loadMore = false;

    if (selectedCategory === categoryName.pools) {
      loadMore = poolsData.loadMore;
    } else if (selectedCategory === categoryName.chalets) {
      loadMore = chaletsData.loadMore;
    } else if (selectedCategory === categoryName.camps) {
      loadMore = campsData.loadMore;
    }
    if (!isLoading && loadMore) {
      this.getItemsListAPICall();
    }
  };

  resetScroll = value => {
    this._config = {
      duration: 500,
      toValue: value,
      easing: Easing.inOut(Easing.ease),
    };
    this._anim = timing(this.scrollY, this._config);
    this._anim.start();
  };

  handleGridOption = type => {
    Utils.enableExperimental();

    if (type === 'GridView') {
      this.resetScroll(0);
      this.setState({listViewOption: 'grid'});
    } else if (type === 'ListView') {
      this.resetScroll(0);
      this.setState({listViewOption: 'listView'});
    } else if (type === 'MapView') {
      this.resetScroll(180);
      this.setState({listViewOption: 'mapView'});
    }
  };

  handlePullToReferesh = () => {
    const {selectedCategory} = this.state;
    const {auth} = this.props;

    if (auth.isConnected) {
      this.setState(
        {
          [getCurrentFilterType(selectedCategory, 'data')]: {
            itemList: [],
            pageCount: 1,
            loadMore: true,
            isRefreshing: true,
          },
        },
        () => {
          this.getItemsListAPICall();
        },
      );
    } else {
      CAlert(translate('Internet'), translate('alert'));
    }
  };

  checkResetFilter = () => {
    let isResetFilter = false;
    const {
      filter: {allFilters, resetFilter},
      FilterActions: {setFilters},
    } = this.props;
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};

    if (resetFilter) {
      isResetFilter = true;
      fData.resetFilter = false;
      setFilters(fData);
      return isResetFilter;
    }
    console.log('checkResetFilter -> isResetFilter', isResetFilter);
    return isResetFilter;
  };

  scrollToTop = () => {
    console.log(
      'scrollToTop -> this.scrollRef',
      this.scrollRef,
      this.scrollRef.current,
    );
    try {
      // this.scrollRef.getNode().scrollToOffset({offset: 0, animated: true});
      // this.scrollRef.scrollTo({x: 0, y: 150, animated: true});
      // this.scrollRef.getNode().scrollTo({
      //   y: 150,
      //   animated: true,
      // });
    } catch (error) {
      console.log('TCL: scrollToTop -> error', error);
    }
  };
  scrollTo = () => {
    // this.scrollRef.scrollTo({x: 0, y: 0, animated: true});
    if (this.scrollRef) {
      // this.scrollRef.getNode().scrollToOffset({offset: 0, animated: true});
      // this.scrollRef.scrollTo({x: 0, y: 150, animated: true});
      this.scrollRef.current.scrollTo({animated: true}, 0);
    }
  };

  markerClick = (event, item) => {
    this.setState({detailVisible: true, previewData: item}, () => {
      this._config = {
        duration: 400,
        toValue: 1,
        easing: Easing.inOut(Easing.ease),
      };
      timing(this.state.mapCardVisible, this._config).start();
    });
  };
  markerListPreview = () => {
    const {
      navigation,
      language: {languageData},
    } = this.props;
    const {detailVisible, previewData, selectedCategory, cSymbol} = this.state;
    let thumbArray = _.isArray(previewData.thumb) ? previewData.thumb : [];
    let imgUrl =
      thumbArray.length > 0 ? previewData.serverPath + thumbArray[7] : '';
    console.log('Rate--->', previewData);

    const cardTranslateY = this.state.mapCardVisible.interpolate({
      inputRange: [0, 1],
      outputRange: [-300, 0],
      extrapolate: 'clamp',
    });

    const cardOpacity = this.state.mapCardVisible.interpolate({
      inputRange: [0, 1],
      outputRange: [0, 1],
      extrapolate: 'clamp',
    });

    const cardScale = this.state.mapCardVisible.interpolate({
      inputRange: [0, 1],
      outputRange: [0.8, 1],
      extrapolate: 'clamp',
    });

    if (!detailVisible) {
      return null;
    }

    return (
      <Animated.View
        style={{
          opacity: cardOpacity,
          left: 0,
          transform: [{translateY: cardTranslateY}, {scale: cardScale}],
          position: 'absolute',
          top: 0,
          width: '100%',
          elevation: 5,
          shadowColor: '#000',
          shadowOffset: {
            width: 0,
            height: 5,
          },
          shadowOpacity: 0.36,
          shadowRadius: 6.68,
          // backgroundColor: '#FFF',
          zIndex: 9,
        }}>
        <TouchableOpacity
          activeOpacity={0.7}
          onPress={() => {
            this.resetBookingData();
            navigation.navigate('HotelDetail', {
              itemID: previewData.ID,
              selectedCategory,
              itemImage: imgUrl,
              itemName:
                languageData === 'en'
                  ? previewData.name_EN
                  : previewData.name_AR,
              itemCity:
                languageData === 'en'
                  ? previewData.city_EN
                  : previewData.city_AR,
            });
          }}
          style={{
            borderRadius: 5,
            margin: 10,
            backgroundColor: '#FFF',
          }}>
          <Animated.View style={{position: 'relative'}}>
            <SharedElement id={`image_${previewData.ID}`}>
              <Image
                source={{uri: imgUrl}}
                style={styles.previewImgStyle}
                resizeMode={'cover'}
              />
            </SharedElement>
            {previewData.avgRating && Number(previewData.totalRating) > 0 ? (
              <View style={styles.rateWrapper}>
                <Text caption1 style={[styles.sizeText, {fontSize: 13}]}>
                  {Number(previewData.avgRating).toFixed(1)}
                  <Text caption1 style={{color: '#222'}}>
                    {' '}
                    ({previewData.totalRating} reviews)
                  </Text>
                </Text>
              </View>
            ) : null}
            <View style={styles.sizeWrapper}>
              <Text style={styles.sizeText}>{previewData.size}</Text>
            </View>
            {previewData.offer_tag === '1' ? (
              <Image source={Images.offer} style={styles.offerImg} />
            ) : null}
            <TouchableOpacity
              style={styles.closeWrapper}
              activeOpacity={0.7}
              onPress={() => {
                this._config = {
                  duration: 400,
                  toValue: 0,
                  easing: Easing.inOut(Easing.ease),
                };
                timing(this.state.mapCardVisible, this._config).start();
              }}>
              <Icon name="close" size={15} color={BaseColor.primaryColor} />
            </TouchableOpacity>
          </Animated.View>
          <View style={[styles.previewNameWrapper, {padding: 10}]}>
            <View>
              <Text body1>{previewData.name_EN}</Text>
              <Text grayColor caption1 style={{paddingTop: 5}}>
                {previewData.city_EN}
              </Text>
            </View>
            <View style={{alignItems: 'flex-end'}}>
              <Text
                body2
                style={{
                  color: BaseColor.primaryColor,
                }}>
                <Text caption1 primaryColor>
                  {translate('Start_From')}
                </Text>
                {'  '}
                {`${previewData.price} ${cSymbol}`}
              </Text>
              {previewData.avgRating && Number(previewData.totalRating) > 0 ? (
                <View style={{width: 60, paddingTop: 5}}>
                  <StarRating
                    disabled={true}
                    starSize={10}
                    maxStars={5}
                    rating={previewData.avgRating}
                    selectedStar={rating => {}}
                    fullStarColor={BaseColor.yellowColor}
                  />
                </View>
              ) : null}
            </View>
          </View>
        </TouchableOpacity>
      </Animated.View>
    );
  };
  focusMap(markers) {
    console.log(`Markers received to populate map: ${markers}`);
    console.log('focusMap');
    if (this.map && this.map.fitToSuppliedMarkers) {
      this.map.fitToSuppliedMarkers(markers, true);
    } else {
      console.log('Clear');
    }
  }

  focus1() {
    const itemData = this.getItemList();
    console.log('reCenterMap -> itemData', itemData);
    const iList = itemData.itemList;
    const identifierArr = [];
    iList &&
      iList.map(item => {
        identifierArr.push(item.ID);
      });
    console.log('reCenterMap -> identifierArr', identifierArr);
    animationTimeout = setTimeout(() => {
      console.log('focus1');
      this.focusMap(identifierArr);
    }, timeout);
  }
  reCenterMap = () => {
    animationTimeout = setTimeout(() => {
      console.log('reCenter');
      this.focus1();
    }, timeout);
  };

  /* Lets reset Booking start end time and Period on back to home */
  resetBookingData(data = {}) {
    const {
      allFilters,
      bookingAction: {setBookingData},
    } = this.props;
    const {selectedCategory} = this.state;

    /* Display Booking date */
    const cFilters = allFilters[getCurrentFilterType(selectedCategory)];
    const cFilterStartDate =
      cFilters.startDate !== '' ? moment(cFilters.startDate) : '';
    console.log(
      'renderFilters -> cFilterStartDate',
      cFilterStartDate,
      cFilterStartDate && cFilterStartDate !== '',
    );
    const cFilterEndDate = moment(cFilters.endDate);
    // const cFilters = allFilters[getCurrentFilterType(selectedCategory)];
    // const cFilterStartDate = moment(cFilters.startDate);
    // const cFilterEndDate = moment(cFilters.endDate);
    const cFilterPeriod =
      getCurrentFilterType(selectedCategory) === 'poolFilters'
        ? cFilters.byPeriod
        : 'Full Day';
    console.log(
      'Reset Booking Data again ===> ',
      cFilterStartDate,
      cFilterEndDate,
      cFilterEndDate.isValid(),
    );

    const bData = {
      startingDate:
        cFilterStartDate && cFilterStartDate !== ''
          ? cFilterStartDate.format('DD MMMM YYYY')
          : '',
      endingDate:
        cFilterEndDate && cFilterEndDate !== '' && cFilterEndDate.isValid()
          ? cFilterEndDate.format('DD MMMM YYYY')
          : '',
      // endingDate: cFilterEndDate.format('DD MMMM YYYY'),
      periodType: [getCurrentPeriod(cFilterPeriod)],
    };

    if (data.startPeriod && data.endPeriod) {
      bData.startPeriod = data.startPeriod;
      bData.endPeriod = data.endPeriod;
    }

    console.log(
      'Reset Booking data to ===> ',
      bData,
      getCurrentPeriod(cFilterPeriod),
    );

    setBookingData(bData);

    /* Reset Locations */
    selected.location = this.props[
      getCurrentFilterType(selectedCategory)
    ].desiredLocation;
    console.log(
      'Update Locations ===> ',
      getCurrentFilterType(selectedCategory),
      selected.location,
    );
  }

  getItemList = () => {
    const {selectedCategory, poolsData, chaletsData, campsData} = this.state;
    console.log('getItemList -> poolsData', poolsData);
    let itemList = [];
    let placeHolder = null;
    let isRefresh = false;
    let baseColor = '';

    if (selectedCategory === categoryName.pools) {
      itemList = _.uniqBy(poolsData.itemList, 'ID');
      placeHolder = Images.pools_placeholder;
      isRefresh = poolsData.isRefreshing;
      baseColor = BaseColor.lightPrimaryColor;
    } else if (selectedCategory === categoryName.chalets) {
      itemList = _.uniqBy(chaletsData.itemList, 'ID');
      placeHolder = Images.chalets_placeholder;
      isRefresh = chaletsData.isRefreshing;
      baseColor = BaseColor.accentColor;
    } else if (selectedCategory === categoryName.camps) {
      itemList = _.uniqBy(campsData.itemList, 'ID');
      placeHolder = Images.camps_placeholder;
      isRefresh = campsData.isRefreshing;
      baseColor = GreenColor.lightPrimaryColor;
    }
    console.log('getItemList -> itemList', itemList);
    return {itemList, placeHolder, isRefresh, baseColor};
  };

  renderListView = () => {
    const {
      navigation,
      language: {languageData},
      auth,
    } = this.props;
    const {
      selectedCategory,
      poolsData,
      chaletsData,
      campsData,
      listViewOption,
      detailVisible,
      cSymbol,
    } = this.state;

    let itemList = [];
    let placeHolder = null;
    let isRefresh = false;
    let baseColor = '';

    if (selectedCategory === categoryName.pools) {
      itemList = _.uniqBy(poolsData.itemList, 'ID');
      placeHolder = Images.pools_placeholder;
      isRefresh = poolsData.isRefreshing;
      baseColor = BaseColor.lightPrimaryColor;
    } else if (selectedCategory === categoryName.chalets) {
      itemList = _.uniqBy(chaletsData.itemList, 'ID');
      placeHolder = Images.chalets_placeholder;
      isRefresh = chaletsData.isRefreshing;
      baseColor = BaseColor.accentColor;
    } else if (selectedCategory === categoryName.camps) {
      itemList = _.uniqBy(campsData.itemList, 'ID');
      placeHolder = Images.camps_placeholder;
      isRefresh = campsData.isRefreshing;
      baseColor = GreenColor.lightPrimaryColor;
    }
    const mapTranslateY = interpolate(this.state.mapCardVisible, {
      inputRange: [0, 1],
      outputRange: [0, 100],
      extrapolate: 'clamp',
    });

    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : true;

    if (listViewOption === 'mapView') {
      return (
        <Animated.View
          ref={c => (this.scrollRef = c)}
          style={{flex: 1, marginTop: 80}}>
          {this.markerListPreview()}
          <Animated.View
            style={{
              transform: [{translateY: mapTranslateY}],
              height: Dimensions.get('window').height - 200,
              width: Dimensions.get('window').width,
            }}>
            <MapView
              provider={PROVIDER_GOOGLE}
              ref={c => (this.map = c)}
              style={styles.mapView}
              onMapReady={() => {
                this.reCenterMap();
              }}
              // region={region}
              // onRegionChange={this.onRegionChange}
              initialRegion={{
                latitude: 26.0667,
                longitude: 50.5577,
                // latitudeDelta: LATITUDE_DELTA,
                // longitudeDelta: LONGITUDE_DELTA,
                latitudeDelta: 0.2822,
                longitudeDelta: 0.3521,
              }}
              scrollEnabled
              zoomEnabled>
              {itemList &&
                itemList.map((marker, i) => {
                  return (
                    <Marker
                      calloutVisible={true}
                      coordinate={{
                        latitude: marker.lat ? parseFloat(marker.lat) : 26.0667,
                        longitude: marker.lng
                          ? parseFloat(marker.lng)
                          : 50.5577,
                      }}
                      identifier={marker.ID}
                      onPress={e => {
                        this.markerClick(e, marker);
                      }}
                      title={marker.name_EN}
                      description={`${marker.price} ${cSymbol}`}
                    />
                  );
                })}
            </MapView>
          </Animated.View>
        </Animated.View>
      );
    } else if (listViewOption === 'listView') {
      return (
        <AnimatedFlatList
          ref={ref => {
            this.scrollRef = ref;
          }}
          isGuestUser={isGuestUser}
          scrollEventThrottle={16}
          keyboardShouldPersistTaps={'handled'}
          onScroll={this.onScroll}
          contentContainerStyle={{
            paddingTop: 220,
            paddingBottom: 70,
          }}
          data={itemList}
          keyExtractor={item => item.ID}
          renderItem={({item, index}) => {
            let serverPath = _.isString(item.serverPath) ? item.serverPath : '';
            let thumbArray = _.isArray(item.thumb) ? item.thumb : [];
            let imagePath =
              thumbArray.length > 0 ? serverPath + thumbArray[7] : '';
            return (
              <HotelItem
                block
                currency={cSymbol}
                baseColor={baseColor}
                onOffer={item.offer_tag}
                image={imagePath}
                placeHolder={placeHolder}
                languageData={languageData}
                name={languageData === 'en' ? item.name_EN : item.name_AR}
                location={languageData === 'en' ? item.city_EN : item.city_AR}
                price={item.price}
                amenities={item.amenities}
                onPressBookMark={() => {
                  isGuestUser
                    ? this.showLoginAlert('login_feature')
                    : this.setBookMarkAPICall(item.ID, item.isBookmarked);
                }}
                isBookmarked={item.isBookmarked}
                id={item.ID}
                rate={Number(item.avgRating)}
                numReviews={Number(item.totalRating)}
                poolSize={item.size}
                style={{
                  marginBottom: 10,
                }}
                onPress={() => {
                  this.resetBookingData();
                  navigation.navigate('HotelDetail', {
                    itemID: item.ID,
                    selectedCategory,
                    itemImage: imagePath,
                    itemName:
                      languageData === 'en' ? item.name_EN : item.name_AR,
                    itemCity:
                      languageData === 'en' ? item.city_EN : item.city_AR,
                  });
                }}
              />
            );
          }}
          ListEmptyComponent={this.renderEmpty}
          ListFooterComponent={this.renderFooter}
          onEndReached={distance => {
            this.onEndReached();
          }}
          onEndReachedThreshold={0.5}
          refreshControl={
            <RefreshControl
              refreshing={isRefresh}
              onRefresh={() => {
                this.handlePullToReferesh();
              }}
              progressViewOffset={220}
            />
          }
        />
      );
    } else {
      return (
        <AnimatedFlatList
          isGuestUser={isGuestUser}
          ref={cmp => {
            if (this.state.viewRef == null) {
              this.setState({
                viewRef: findNodeHandle(cmp),
              });
            }
          }}
          // bounces={false}
          scrollEventThrottle={16}
          keyboardShouldPersistTaps={'handled'}
          onScroll={this.onScroll}
          contentContainerStyle={{
            paddingTop: 220,
            paddingBottom: 70,
          }}
          numColumns={2}
          data={itemList}
          keyExtractor={item => item.ID}
          key={'single'}
          columnWrapperStyle={{
            marginHorizontal: 20,
          }}
          renderItem={({item, index}) => {
            let serverPath = _.isString(item.serverPath) ? item.serverPath : '';
            let thumbArray = _.isArray(item.thumb) ? item.thumb : [];
            let imagePath =
              thumbArray.length > 0 ? serverPath + thumbArray[4] : '';
            return (
              <HotelItem
                grid
                currency={cSymbol}
                allData={itemList}
                baseColor={baseColor}
                onOffer={item.offer_tag}
                image={imagePath}
                placeHolder={placeHolder}
                name={languageData === 'en' ? item.name_EN : item.name_AR}
                location={languageData === 'en' ? item.city_EN : item.city_AR}
                price={item.price}
                isBookMarked={item.isBookmarked}
                onPressBookMark={() => {
                  isGuestUser
                    ? this.showLoginAlert('login_feature')
                    : this.setBookMarkAPICall(item.ID, item.isBookmarked);
                }}
                id={item.ID}
                rate={Number(item.avgRating)}
                numReviews={Number(item.totalRating)}
                poolSize={item.size}
                style={{
                  marginBottom: 10,
                  marginLeft: index % 2 ? 15 : 0,
                }}
                onPress={() => {
                  this.resetBookingData();
                  navigation.navigate('HotelDetail', {
                    itemID: item.ID,
                    selectedCategory,
                    itemImage: imagePath,
                    itemName:
                      languageData === 'en' ? item.name_EN : item.name_AR,
                    itemCity:
                      languageData === 'en' ? item.city_EN : item.city_AR,
                  });
                }}
              />
            );
          }}
          ListEmptyComponent={this.renderEmpty}
          ListFooterComponent={this.renderFooter}
          onEndReached={distance => {
            this.onEndReached();
          }}
          onEndReachedThreshold={0.5}
          refreshControl={
            <RefreshControl
              refreshing={isRefresh}
              onRefresh={() => {
                this.handlePullToReferesh();
              }}
              progressViewOffset={220}
            />
          }
        />
      );
    }
  };

  render() {
    const {selectedCategory} = this.state;
    const refScale = interpolate(this.scrollY, {
      inputRange: [-150, -1],
      outputRange: [1, 0],
      extrapolate: 'clamp',
    });

    const refRotate = interpolate(this.scrollY, {
      inputRange: [-150, -1],
      outputRange: [360, 350],
      extrapolate: 'clamp',
    });

    const renderCustomPopup = ({
      appIconSource,
      appTitle,
      timeText,
      title,
      body,
    }) => (
      <View>
        <Text>{'title'}</Text>
        <Text>{'body'}</Text>
      </View>
    );
    // const {
    //   filter: {
    //     allFilters: {poolFilters},
    //   },
    // } = this.props;
    // console.log('render -> poolFilters', poolFilters, this.locationRef);
    // const prevSelectedCity =
    //   poolFilters && poolFilters.desiredLocation
    //     ? poolFilters.desiredLocation
    //     : [];
    // const nextSelectedCity = this.locationRef

    return (
      <SafeAreaView style={{flex: 1}}>
        <NavigationEvents
          onWillFocus={payload => {
            /* No need to update item on back - should be handled from CDU */
            let fromPayment = this.props.navigation.getParam('fromPayment', '');
            if (fromPayment) {
              this.getItemsListAPICall();
            }
            this.map && !_.isUndefined(this.map) ? this.reCenterMap() : null;
            // await this.disableFacility();
            this.setStatusbar();
            this.resetBookingData();
            this.getSymbol();
            this.getCountries();
          }}
        />
        {this.renderListView()}
        {this.renderHeader()}

        {Platform.OS === 'ios' && (
          <Animated.View
            style={[
              styles.customRefreshControl,
              {transform: [{scale: refScale}, {rotate: refRotate}]},
            ]}>
            <Icon name={'refresh'} size={40} color={BaseColor.grayColor} />
          </Animated.View>
        )}
        <BookingTime
          childRef={tcmp => {
            console.log('REf ===> ', tcmp);
            this.bookingTimeCmp = tcmp;
          }}
          onChange={(type, value) => {
            if (type === 'period') {
              this.saveFilterValue('period', value);
            }
            if (type === 'date') {
              this.resetBookingData(value);
            }
            console.log('On time change ===> ', type, value);
          }}
          layoutHidden={true}
          showPeriod={getCurrentFilterType(selectedCategory) === 'poolFilters'}
        />
        <LocationModal
          childLocRef={tcmp => {
            console.log('REf ===> ', tcmp);
            this.locationRef = tcmp;
          }}
          modalVisible={this.state.locationModalVisible}
          translate={translate}
          locRef={this.locationRef}
          location={this.getCityList()}
          onChangeLocation={this.onChangeLocation}
          sLocations={selected.location}
          onModalClose={locations => {
            if (!_.isEmpty(locations)) {
              this.setState({
                locationModalVisible: false,
              });

              this.saveFilterValue('location', selected.location);
              console.log('Location change modal close ==> ', locations);
            } else {
              this.setState({
                locationModalVisible: false,
              });
            }
          }}
        />
        {this.state.showAnimation ? (
          <View pointerEvents="none" style={[styles.animationWrap1]}>
            <LottieView
              ref={animation => {
                this.animation1 = animation;
              }}
              onAnimationFinish={() => {
                this.setState({showAnimation: false});
              }}
              autoSize={false}
              style={[styles.animation1]}
              source={require('@assets/lottie/bAnimate.json')}
              autoPlay={true}
              loop={false}
            />
          </View>
        ) : null}
        {/* <TouchableOpacity
          onPress={() => {}}
          activeOpacity={0.7}
          style={styles.scrollTopWrapper}>
          <BlurView
            style={[
              styles.absHeaderBlur,
              {height: 50, width: 50, borderRadius: 25},
            ]}
            blurType="light"
          />
          <Icon name="chevron-double-up" size={25} color="#FFF" />
        </TouchableOpacity> */}
      </SafeAreaView>
    );
  }
}

Home.defaultProps = {
  auth: {},
  language: {},
  filter: '',
};

Home.propTypes = {
  auth: PropTypes.objectOf(PropTypes.any),
  language: PropTypes.objectOf(PropTypes.any),
  filter: PropTypes.any,
  booking: PropTypes.any,
};

const mapStateToProps = state => {
  return {
    auth: state.auth,
    language: state.language,
    filter: state.filter,
    booking: state.booking,
    allFilters: state.filter.allFilters,
    poolFilters: state.filter.poolFilters,
    chaletFilters: state.filter.chaletFilters,
    campFilters: state.filter.campFilters,
  };
};

const mapDispatchToProps = dispatch => {
  return {
    FilterActions: bindActionCreators(FilterActions, dispatch),
    authActions: bindActionCreators(authActions, dispatch),
    bookingAction: bindActionCreators(bookingAction, dispatch),
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(withInAppNotification(Home));
