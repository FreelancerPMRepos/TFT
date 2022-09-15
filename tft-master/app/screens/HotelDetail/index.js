/* eslint-disable radix */
/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import AuthActions from '../../redux/reducers/auth/actions';
import BookActions from '../../redux/reducers/booking/actions';
import _ from 'lodash';
import {
  View,
  ScrollView,
  Animated,
  TouchableOpacity,
  Platform,
  Linking,
  StatusBar,
  Share,
  Dimensions,
  PanResponder,
  StyleSheet,
  FlatList,
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import {BaseStyle, BaseColor, Images} from '@config';
import {
  Header,
  SafeAreaView,
  Icon,
  Text,
  StarRating,
  Button,
  Image,
  Tag,
} from '@components';
import * as Utils from '@utils';
import {getPriceofDates} from '@utils/booking';
import styles from './styles';
import {getApiData} from '../../utils/apiHelper';
import categoryName, {periodTypes} from '../../config/category';
import CAlert from '../../components/CAlert';
import {BaseSetting} from '../../config/setting';
import {translate} from '../../lang/Translate';
import {getStatusBarHeight} from 'react-native-status-bar-height';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import IIcon from 'react-native-vector-icons/Ionicons';
import MCIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import moment from 'moment';
import Swiper from 'react-native-swiper';
import MapView, {Marker, PROVIDER_GOOGLE} from 'react-native-maps';
// Load sample data
import {HelpBlockData} from '@data';
import CCalendar from '../../components/CCalendar';
import {isIphoneX} from '../../config/isIphoneX';
import LottieView from 'lottie-react-native';
import {SharedElement} from 'react-navigation-shared-element';
import {NewDetailLoader} from '../../components/CContentLoder';
import CLoader from '../../components/CLoader';
import {
  getDefaultPeriodofType,
  getCurrentFilterType,
  getCurrencySymbol,
} from 'app/utils/booking';
import {setIntervalTime} from 'app/utils/CommonFunction';
import Modal from 'react-native-modal';
import {NavigationEvents} from 'react-navigation';
import {ActionSheetCustom as ActionSheet} from 'react-native-actionsheet';

const {height} = Dimensions.get('window');

const IOS = Platform.OS === 'ios';
const HEADER_MAX_HEIGHT = 200;
const HEADER_MIN_HEIGHT = IOS ? 65 : 55;
const HEADER_SCROLL_DISTANCE = HEADER_MAX_HEIGHT - HEADER_MIN_HEIGHT;
const imageViewHeight = Math.min(
  Dimensions.get('window').height * (IOS ? 0.35 : 0.4),
  350,
);

const options = [
  <View style={styles.actionList}>
    <MCIcon
      name="google-maps"
      size={25}
      style={{marginHorizontal: 5}}
      color={BaseColor.primaryColor}
    />
    <Text body1>Google Map</Text>
  </View>,
  <View style={styles.actionList}>
    <MCIcon
      name="map-outline"
      size={25}
      style={{marginHorizontal: 5}}
      color={BaseColor.primaryColor}
    />
    <Text body1>Apple Map</Text>
  </View>,
  <View style={styles.actionList}>
    <Text body1 style={{color: 'red'}}>
      Cancel
    </Text>
  </View>,
];

class HotelDetail extends Component {
  constructor(props) {
    super(props);
    // Temp data define
    this.state = {
      heightHeader:
        Platform.OS === 'ios'
          ? Utils.heightHeader() - getStatusBarHeight()
          : Utils.heightHeader(),
      renderMapView: false,
      region: {
        latitude: 1.9344,
        longitude: 103.358727,
        latitudeDelta: 0.05,
        longitudeDelta: 0.004,
      },
      roomType: [
        {
          id: '1',
          image: Images.room8,
          name: 'Standard Twin Room',
          price: '$399,99',
          available: 'Hurry Up! This is your last room!',
          services: [
            {icon: 'wifi', name: 'Free Wifi'},
            {icon: 'shower', name: 'Shower'},
            {icon: 'users', name: 'Max 3 aduts'},
            {icon: 'subway', name: 'Nearby Subway'},
          ],
        },
        {
          id: '2',
          image: Images.room5,
          name: 'Delux Room',
          price: '$399,99',
          available: 'Hurry Up! This is your last room!',
          services: [
            {icon: 'wifi', name: 'Free Wifi'},
            {icon: 'shower', name: 'Shower'},
            {icon: 'users', name: 'Max 3 aduts'},
            {icon: 'subway', name: 'Nearby Subway'},
          ],
        },
      ],
      helpBlock: HelpBlockData,
      picsArray: [{initial: true}, {initial: true}],
      itemDetails: {},
      priceDetail: {},
      showPrice: false,
      pageLoad: true,
      shoModal: false,
      selectedDate: '',
      period: '',
      loading: false,
      opacityValue: 0,
      scrollY: new Animated.Value(0),
      showAnimation: false,
      dayPrices: {},
      priceModal: false,
      cSymbol: _.has(props, 'auth.country')
        ? getCurrencySymbol(props.auth.country)
        : 'BHD',

      /* Airbnb mode */
      swipeBottom: new Animated.Value(0),
    };

    this._panResponder = PanResponder.create({
      onStartShouldSetPanResponder: (evt, gestureState) => false,
      onStartShouldSetPanResponderCapture: (evt, gestureState) => false,
      onMoveShouldSetPanResponder: (evt, gestureState) => {
        return this.state.scrollY._value <= 0 && gestureState.dy > 10;
      },
      onPanResponderGrant: (evt, gestureState) => {
        Animated.spring(this.state.swipeBottom, {
          toValue: 0,
        }).start();
      },
      onPanResponderMove: (evt, gestureState) => {
        if (gestureState.dy > height * 0.4) {
          this.goingBack = true;
          if (this.swiper) {
            this.swiper.scrollTo(0);
          }
          setTimeout(() => {
            props.navigation.goBack();
            this.gestureStarted = false;
          }, 100);
        } else {
          if (
            gestureState.dx === 0 &&
            gestureState.dy > 10 &&
            !this.gestureStarted
          ) {
            this.gestureStarted = true;
          }
          if (this.gestureStarted) {
            this.state.swipeBottom.setValue(gestureState.dy);
          }
        }
        // return Animated.event([null, {dy: this.state.swipeBottom}]);
      },
      onPanResponderTerminationRequest: (evt, gestureState) => true,
      onPanResponderRelease: (evt, gestureState) => {
        this.gestureStarted = false;
        if (!this.goingBack) {
          Animated.spring(this.state.swipeBottom, {
            toValue: 0,
          }).start();
        }
        // The user has released all touches while this view is the
        // responder. This typically means a gesture has succeeded
      },
      onPanResponderTerminate: (evt, gestureState) => {
        this.gestureStarted = false;
        if (!this.goingBack) {
          Animated.spring(this.state.swipeBottom, {
            toValue: 0,
          }).start();
        }
        // Another component has become the responder, so this gesture
        // should be cancelled
      },
      onShouldBlockNativeResponder: (evt, gestureState) => {
        return true;
      },
    });
  }

  componentDidMount() {
    console.log('isIphoneX===', isIphoneX);
    StatusBar.setBarStyle('light-content', true);
    this.concurrentAPICalls();
    if (this.animation1) {
      this.animation1.play(0, 55);
    }
  }

  componentDidUpdate(prevProps) {
    const {
      booking: {bookingData},
      filter,
      navigation,
      filter: {filterDataType},
      fromFilter,
    } = this.props;

    let selectedCategory = navigation.getParam('selectedCategory', '');
    const currentFilterType = getCurrentFilterType(selectedCategory);
    const startDate =
      filter &&
      _.has(filter, `${getCurrentFilterType(filterDataType)}.startDate`)
        ? moment(filter[getCurrentFilterType(filterDataType)].startDate)
        : '';
    const endDate =
      filter && _.has(filter, `${getCurrentFilterType(filterDataType)}.endDate`)
        ? moment(filter[getCurrentFilterType()].endDate)
        : '';
    console.log(
      'HotelDetail -> componentDidUpdate -> currentFilterType',
      currentFilterType,
      selectedCategory,
    );

    if (
      bookingData &&
      bookingData.startingDate &&
      (!_.isEqual(
        bookingData.startingDate,
        prevProps.booking.bookingData.startingDate,
      ) ||
        !_.isEqual(
          bookingData.endingDate,
          prevProps.booking.bookingData.endingDate,
        ) ||
        !_.isEqual(
          bookingData.periodType,
          prevProps.booking.bookingData.periodType,
        ))
    ) {
      console.log(
        'Not Equal ===>',
        prevProps.booking.bookingData,
        this.props.booking.bookingData,
      );
      let cSDate = moment(bookingData.startingDate).format('YYYY-MM-DD');
      let cEDate = moment(bookingData.endingDate).format('YYYY-MM-DD');
      cSDate = Platform.select({
        ios: bookingData.startingDate,
        android: moment(bookingData.startingDate).format('YYYY-MM-DD'),
      });
      cEDate = Platform.select({
        ios: bookingData.endingDate,
        android: moment(bookingData.endingDate).format('YYYY-MM-DD'),
      });
      this.setState({
        period: bookingData.periodType,
        startDate: cSDate,
        endDate: cEDate,
      });
    }
  }

  componentWillUnmount() {
    if (IOS) {
      StatusBar.setBarStyle('dark-content', true);
    }
  }

  async concurrentAPICalls() {
    const {
      BookActions: {setBookingData},
      booking: {bookingData},
    } = this.props;
    const {navigation, filter} = this.props;
    const {selectedDate} = this.state;
    let selectedCategory = navigation.getParam('selectedCategory', '');
    console.log(
      'HotelDetail -> concurrentAPICalls -> selectedCategory',
      selectedCategory,
    );
    const currentFilterType = getCurrentFilterType(selectedCategory);
    let sDate =
      filter && _.has(filter, `${currentFilterType}.byDate`)
        ? filter[currentFilterType].byDate
        : '';
    let startDate =
      filter && _.has(filter, `${currentFilterType}.startDate`)
        ? filter[currentFilterType].startDate
        : '';
    let endDate =
      filter && _.has(filter, `${currentFilterType}.endDate`)
        ? filter[currentFilterType].endDate
        : '';
    let periodType =
      filter && _.has(filter, `${currentFilterType}.byPeriod`)
        ? filter[currentFilterType].byPeriod
        : getDefaultPeriodofType(selectedCategory);

    if (sDate < moment(new Date()).format('YYYY-MM-DD')) {
      sDate = moment(new Date()).format('YYYY-MM-DD');
    }
    const detail = await this.getItemDetailAPICall();

    let _tBData = {};

    if (this.isFullDayfromTime(detail)) {
      if (
        _.isEmpty(bookingData.startPeriod) ||
        (_.isObject(bookingData.startPeriod) &&
          bookingData.startPeriod.id !== 3)
      ) {
        _tBData.periodType = [periodTypes[2]];
        _tBData.startPeriod = periodTypes[2];
        _tBData.endPeriod = periodTypes[2];
      }
    } else {
      if (
        _.isEmpty(bookingData.startPeriod) ||
        (_.isObject(bookingData.startPeriod) &&
          bookingData.startPeriod.id === 3)
      ) {
        _tBData.periodType = [periodTypes[0]];
        _tBData.startPeriod = periodTypes[0];
        _tBData.endPeriod = periodTypes[0];
      }
    }
    setBookingData(_tBData);

    console.log('New Dates ===> ', startDate, endDate, sDate);
    let imgArray = detail.poolImages ? detail.poolImages : [];
    if (selectedCategory === 'Chalets') {
      imgArray = detail.chaletImages ? detail.chaletImages : [];
    } else if (selectedCategory === 'Camps') {
      imgArray = detail.campImages ? detail.campImages : [];
    }

    if (_.isObject(detail)) {
      this.setState(
        {
          shoModal: false,
          selectedDate: sDate,
          period: periodType,
          startDate,
          endDate,
          itemDetails: detail,
          picsArray: imgArray,
          pageLoad: false,
        },
        async () => {
          const dayPrices = await this.getDayPrices();
          console.log('Dates-->', sDate, selectedDate);
          console.log('DayType==>', periodType);
          Utils.enableExperimental();
          if (!_.isEmpty(dayPrices)) {
            this.setState({
              dayPrices,
            });
          }
        },
      );
    } else {
      CAlert(
        translate('Loading_Error'),
        translate('Error'),
        () => {
          this.concurrentAPICalls();
        },
        () => {
          navigation.goBack();
        },
        translate('Retry'),
        translate('go_back'),
      );
    }
  }

  getDayPrices = () => {
    return new Promise((resolve, reject) => {
      const {isConnected, navigation, auth} = this.props;
      let itemID = navigation.getParam('itemID', '');
      let selectedCategory = navigation.getParam('selectedCategory', '');
      console.log(
        'HotelDetail -> getDayPrices -> itemID',
        itemID,
        selectedCategory,
        categoryName.pools,
        isConnected,
      );

      if (auth.isConnected) {
        let sType = getCurrentFilterType(selectedCategory, 'sType');

        const data = {
          serviceId: itemID,
          serviceType: sType,
        };
        console.log('data==', data);
        this.setState({loading: true}, () => {
          getApiData(BaseSetting.endpoints.dayPrices, 'post', data)
            .then(result => {
              console.log('HotelDetail -> getDayPrices -> result', result);
              if (result && _.isObject(result)) {
                console.log('CCalendar -> getDayPrices -> result', result);
                if (result.status && result.data) {
                  this.setState({loading: false}, () => {
                    resolve(result.data);
                  });
                } else {
                  this.setState({loading: false}, () => {
                    if (
                      _.isString(result.message) &&
                      result.message === 'No data found'
                    ) {
                      resolve([]);
                    } else {
                      resolve(false);
                    }
                  });
                }
              } else {
                this.setState({loading: false});
                resolve(false);
              }
            })
            .catch(err => {
              this.setState({loading: false});
              console.log(`Error: ${err}`);
              reject(err);
            });
        });
      } else {
        this.setState({loading: false});
        reject(null);
      }
    });
  };

  getItemDetailAPICall = () => {
    return new Promise((resolve, reject) => {
      const {auth, navigation, filter} = this.props;
      let itemID = navigation.getParam('itemID', '');
      let selectedCategory = navigation.getParam('selectedCategory', '');
      console.log(
        'TCL: getItemDetailAPICall -> selectedCategory',
        this.props.filter,
      );

      if (auth.isConnected) {
        const UrlString =
          BaseSetting.endpoints[
            getCurrentFilterType(selectedCategory, 'detail')
          ];
        const currentFilterType = getCurrentFilterType(selectedCategory);

        const sDate =
          filter && _.has(filter, `${currentFilterType}.byDate`)
            ? filter[currentFilterType].byDate
            : moment(new Date()).format('YYYY-MM-DD');
        const periodType =
          selectedCategory === categoryName.pools &&
          filter &&
          _.has(filter, `${currentFilterType}.byPeriod`)
            ? filter[currentFilterType].byPeriod
            : '';

        let data = {
          id: itemID,
          user_id:
            _.isObject(auth.userData) && auth.userData.ID
              ? auth.userData.ID
              : 0,
          date: sDate,
        };

        if (selectedCategory === categoryName.pools) {
          data.morning = periodType === 'Morning' ? true : false;
          data.evening = periodType === 'Evening' ? true : false;
        }

        console.log(
          'HotelDetail -> getItemDetailAPICall -> data',
          data,
          UrlString,
        );

        getApiData(UrlString, 'post', data)
          .then(result => {
            console.log('getItemDetailAPICall -> result', result);
            if (_.isObject(result)) {
              if (
                _.isBoolean(result.status) &&
                result.status === true &&
                _.isObject(result.data)
              ) {
                resolve(result.data);
              } else {
                resolve(null);
              }
            } else {
              resolve(null);
            }
          })
          .catch(err => {
            console.log(`Error: ${err}`);
            reject(err);
          });
      } else {
        reject(null);
      }
    });
  };

  getPicsArrayAPICall = () => {
    return new Promise((resolve, reject) => {
      const {auth, navigation} = this.props;
      let itemID = navigation.getParam('itemID', '');
      let selectedCategory = navigation.getParam('selectedCategory', '');

      if (auth.isConnected) {
        let UrlString =
          BaseSetting.endpoints[getCurrentFilterType(selectedCategory, 'pics')];

        let data = {
          id: itemID,
        };

        getApiData(UrlString, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (
                _.isBoolean(result.status) &&
                result.status === true &&
                _.isArray(result.data)
              ) {
                resolve(result.data);
              } else {
                resolve(null);
              }
            } else {
              resolve(null);
            }
          })
          .catch(err => {
            console.log(`Error: ${err}`);
            reject(err);
          });
      } else {
        reject(null);
      }
    });
  };

  // handleLocation = url => {
  //   Linking.canOpenURL(url)
  //     .then(supported => {
  //       if (!supported) {
  //         console.log("Can't handle url: " + url);
  //       } else {
  //         return Linking.openURL(url);
  //       }
  //     })
  //     .catch(err => console.error('An error occurred', err));
  // };

  openCalendar = () => {
    this.setState({
      shoModal: true,
    });
  };

  showLoginAlert = (message, title = 'alert') => {
    const {
      navigation,
      AuthActions: {setUserData},
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

  showFamilyAlert = () => {
    const {navigation} = this.props;
    let selectedCategory = navigation.getParam('selectedCategory', '');
    let familyMsg = getCurrentFilterType(selectedCategory, 'familyMsg', true);

    CAlert(
      familyMsg,
      translate('Family_Only'),
      () => {
        this.addReservationAPICall();
      },
      () => {},
    );
  };

  removeBookMarkAlert = () => {
    CAlert(
      translate('delete_Bookmark'),
      translate('alert'),
      () => {
        this.setBookMarkAPICall();
      },
      () => {},
      translate('delete'),
    );
  };

  setBookMarkAPICall = () => {
    const {auth, navigation} = this.props;
    const {itemDetails} = this.state;
    let selectedCategory = navigation.getParam('selectedCategory', '');

    if (auth.isConnected) {
      let serviceType = getCurrentFilterType(selectedCategory, 'name');

      let UrlString = itemDetails.isBookmarked
        ? BaseSetting.endpoints.removeBookMark
        : BaseSetting.endpoints.addBookMark;

      let showAnim = itemDetails.isBookmarked ? false : true;

      let data = {
        service_id: itemDetails.ID,
        service_type: serviceType,
        user_id:
          _.isObject(auth.userData) && auth.userData.ID ? auth.userData.ID : 0,
      };
      this.setState({showAnimation: showAnim, bookmarkLoading: true}, () => {
        getApiData(UrlString, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (_.isBoolean(result.status) && result.status === true) {
                let detail = itemDetails;
                detail.isBookmarked = !detail.isBookmarked;
                this.setState({
                  itemDetails: detail,
                  bookmarkLoading: false,
                });
              } else {
                this.setState({bookmarkLoading: false}, () => {
                  CAlert(
                    _.isString(result.message)
                      ? result.message
                      : translate('went_wrong'),
                    translate('alert'),
                  );
                });
              }
            } else {
              this.setState({bookmarkLoading: false}, () => {
                CAlert(translate('went_wrong'), translate('alert'));
              });
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

  addReservationAPICall = () => {
    const {
      auth,
      navigation,
      booking: {bookingData},
    } = this.props;
    console.log('addReservationAPICall -> bookingData', bookingData);

    const {
      picsArray,
      itemDetails,
      priceDetail,
      selectedDate,
      period,
    } = this.state;

    let selectedCategory = navigation.getParam('selectedCategory', '');
    let itemID = navigation.getParam('itemID', '');
    console.log('addReservationAPICall -> selectedCategory', selectedCategory);

    let downPayment = '';
    let selectedPrice = '';
    let periodID = 1;
    let ePeriodID = 1;
    let startTime = '';
    let endTime = '';
    console.log('period===>', period, bookingData);
    if (
      period === 'Full Day' ||
      (period[0] && period[0].id && period[0].id === 3) ||
      (bookingData.periodType && bookingData.periodType[0].id === 3)
    ) {
      periodID = 3;
      startTime =
        selectedCategory === categoryName.pools
          ? priceDetail.full_day_start_time
          : itemDetails.start_time;
      endTime =
        selectedCategory === categoryName.pools
          ? priceDetail.full_day_end_time
          : itemDetails.end_time;
    } else if (
      selectedCategory === 'Pools' &&
      bookingData &&
      bookingData.startPeriod &&
      bookingData.endPeriod
    ) {
      periodID = bookingData.startPeriod.id;
      ePeriodID = bookingData.endPeriod.id;
    } else if (
      bookingData &&
      bookingData.periodType &&
      bookingData.periodType[0].id === 1
    ) {
      periodID = 1;
      startTime = itemDetails.morning_start_time;
      endTime = itemDetails.morning_end_time;
    } else if (
      bookingData &&
      bookingData.periodType &&
      bookingData.periodType[0].id === 2
    ) {
      periodID = 2;
      startTime = itemDetails.evening_start_time;
      endTime = itemDetails.evening_end_time;
    }

    if (auth.isConnected) {
      this.setState({
        loading: true,
      });
      let data = {};
      data[getCurrentFilterType(selectedCategory, 'id')] = itemID;
      data.offerId = _.isEmpty(priceDetail.offer_Id) ? 0 : priceDetail.offer_Id;
      data.userId = auth.userData.ID;
      data.ownerId = itemDetails.owner_ID;
      data.userName = auth.userData.first_name + ' ' + auth.userData.last_name;
      data.mobile = auth.userData.mobile;
      data.countryCode = auth.userData.country_code;
      data.rate = itemDetails.rate;
      data.price =
        bookingData && bookingData.totalPrice
          ? bookingData.totalPrice
          : itemDetails && itemDetails.price
          ? itemDetails.price
          : selectedPrice;
      data.downPayment =
        bookingData && bookingData.totalDownpayment
          ? bookingData.totalDownpayment
          : itemDetails && itemDetails.down_payment
          ? itemDetails.down_payment
          : downPayment;
      // data.startDate =
      //   bookingData && bookingData.startingDate
      //     ? moment(bookingData.startingDate).format('YYYY-MM-DD')
      //     : selectedDate;
      data.startDate =
        bookingData && bookingData.startingDate
          ? Platform.select({
              ios: moment(bookingData.startingDate).format('YYYY-MM-DD'),
              android: this.state.startDate,
            })
          : selectedDate;
      // data.endDate =
      //   bookingData && bookingData.endingDate
      //     ? moment(bookingData.endingDate).format('YYYY-MM-DD')
      //     : selectedDate;
      data.endDate =
        bookingData && bookingData.endingDate
          ? Platform.select({
              ios: moment(bookingData.endingDate).format('YYYY-MM-DD'),
              android: this.state.endDate,
            })
          : selectedDate;
      data.period = periodID;
      data.endPeriod = periodID === 3 ? 3 : ePeriodID;
      data.currency = this.state.cSymbol;

      console.log(
        'addReservationAPICall -> data',
        selectedDate,
        bookingData,
        data,
      );
      // CAlert(JSON.stringify(data));
      // return;

      getApiData(BaseSetting.endpoints.addReservation, 'post', data)
        .then(result => {
          console.log('addReservationAPICall -> result', result);
          if (_.isObject(result)) {
            console.log('addReservationAPICall -> result', result);
            if (
              _.isBoolean(result.status) &&
              result.status === true &&
              _.isObject(result.data)
            ) {
              this.setState(
                {
                  loading: false,
                },
                () => {
                  navigation.navigate('PreviewBooking', {
                    itemDetails: itemDetails,
                    priceDetail: priceDetail,
                    selectedDate: selectedDate,
                    selectedCategory: selectedCategory,
                    period: period,
                    picsArray: picsArray,
                    downPayment: this.getBookingPrice('totalDownpayment'),
                    selectedPrice: this.getBookingPrice(),
                    currency: this.state.cSymbol,
                    reservationID: result.data.last_insert_id,
                    startTime: startTime,
                    endTime: endTime,
                    startPeriod: periodTypes[data.period - 1],
                    endPeriod: periodTypes[data.endPeriod - 1],
                    sDate: this.state.startDate,
                    eDate: this.state.endDate,
                  });
                },
              );
            } else {
              this.setState(
                {
                  loading: false,
                },
                () => {
                  CAlert(
                    _.isString(result.message) && result.message === 'taken'
                      ? translate('Error_Reserved')
                      : translate('went_wrong'),
                    translate('alert'),
                  );
                },
              );
            }
          } else {
            this.setState(
              {
                loading: false,
              },
              () => {
                CAlert(translate('went_wrong'), translate('alert'));
              },
            );
          }
        })
        .catch(err => {
          console.log(`Error: ${err}`);
          this.setState({
            loading: false,
          });
        });
    } else {
      CAlert(translate('Internet'), translate('alert'));
    }
  };

  regionFrom(lat, lon, distance = 5000) {
    //distance :calulates the zoom level of the map
    distance = distance / 2;
    const circumference = 40075;
    const oneDegreeOfLatitudeInMeters = 111.32 * 1000;
    const angularDistance = distance / circumference;

    const latitudeDelta = distance / oneDegreeOfLatitudeInMeters;
    const longitudeDelta = Math.abs(
      Math.atan2(
        Math.sin(angularDistance) * Math.cos(lat),
        Math.cos(angularDistance) - Math.sin(lat) * Math.sin(lat),
      ),
    );

    let result = {
      latitude: parseFloat(lat),
      longitude: parseFloat(lon),
      latitudeDelta: latitudeDelta,
      longitudeDelta: longitudeDelta,
    };

    return result;
  }

  sharePool = () => {
    try {
      const {itemDetails} = this.state;
      const {filterDataType} = this.props.filter;
      const id =
        itemDetails && _.isObject(itemDetails) && itemDetails.ID
          ? itemDetails.ID
          : '';
      const url = `${BaseSetting.baseUrl}/?id=${id}&type=${filterDataType}`;
      console.log('sharePool -> url', url);
      Share.share({
        title: 'Share',
        message: !IOS ? url : '',
        url: IOS ? url : '',
      })
        .then(result => console.log('result=>', result))
        .catch(errorMsg => console.log('errorMsg=>', errorMsg));
    } catch (error) {
      console.log(`Sharing Error ${error}`);
    }
  };

  checkBookBtn = () => {
    const {
      auth,
      booking: {bookingData},
    } = this.props;
    const {itemDetails, startDate, endDate} = this.state;
    const toDate =
      startDate && !_.isEmpty(startDate)
        ? startDate
        : bookingData && bookingData.startingDate
        ? bookingData.startingDate
        : '';
    const fromDate =
      endDate && !_.isEmpty(endDate)
        ? endDate
        : bookingData && bookingData.endingDate
        ? bookingData.endingDate
        : '';
    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : true;

    if (toDate === '' && fromDate === '') {
      this.setState({
        shoModal: true,
      });
    } else if (isGuestUser) {
      this.showLoginAlert('Login_Message');
    } else if (itemDetails.family_tag === '1') {
      this.showFamilyAlert();
    } else {
      this.addReservationAPICall();
    }
  };

  /* Calculate Booking price and Downpayment based on selected Starting and Ending date */
  getBookingPrice(type = 'totalPrice') {
    const {
      booking: {bookingData},
    } = this.props;
    const {itemDetails, dayPrices, startDate, endDate} = this.state;
    console.log('getBookingPrice -> bookingData', bookingData);
    // CAlert(`${startDate} ${bookingData.startingDate}`);
    // const sDate = Platform.select({
    //   ios: bookingData.startingDate,
    //   android: moment(bookingData.startingDate, 'DD MMM YYYY').format(
    //     'YYYY-MM-DD',
    //   ),
    // });
    // const eDate = Platform.select({
    //   ios: bookingData.endingDate,
    //   android: moment(bookingData.endingDate, 'DD MMM YYYY').format(
    //     'YYYY-MM-DD',
    //   ),
    // });
    if (bookingData.startingDate && bookingData.endingDate) {
      const priceDet = getPriceofDates(
        // sDate,
        // eDate,
        startDate,
        endDate,
        // bookingData.startingDate,
        // bookingData.endingDate,
        {
          activeStartPeriod: bookingData.startPeriod,
          activeEndPeriod: bookingData.endPeriod,
        },
        dayPrices,
      );
      console.log('getBookingPrice -> priceDet', priceDet);

      if (type === 'totalPrice') {
        return priceDet.dPrice ? priceDet.dPrice : itemDetails.price;
      } else if (type === 'totalDownpayment') {
        return priceDet.downPayment
          ? priceDet.downPayment
          : itemDetails.down_payment;
      } else {
        console.log('getBookingPrice -> priceDet', priceDet);
        return priceDet.explanation ? priceDet.explanation : 0;
      }
    } else if (bookingData && bookingData[type]) {
      console.log('getBookingPrice -> bookingData[type]', bookingData[type]);
      return bookingData[type];
    } else if (itemDetails) {
      if (type === 'totalPrice') {
        return itemDetails.price;
      } else {
        return itemDetails.down_payment;
      }
    } else {
      return 0;
    }
  }

  renderModal() {
    const explanation = this.getBookingPrice('explanation');
    const tPrice = this.getBookingPrice();
    const tDownPay = this.getBookingPrice('totalDownpayment');
    const {cSymbol} = this.state;
    console.log('data==>', explanation);
    return (
      <View style={[styles.modalWrapper]}>
        <Modal
          propagateSwipe={true}
          swipeDirection={'down'}
          isVisible={this.state.priceModal}
          swipeThreshold={200}
          onBackdropPress={() => {
            this.setState({priceModal: false});
          }}
          onSwipeComplete={() => this.setState({priceModal: false})}
          style={styles.bottomModal}>
          <View style={styles.contentFilterBottom}>
            <View style={styles.contentSwipeDown}>
              <View style={styles.lineSwipeDown} />
            </View>
            <View style={styles.modalHeader}>
              <Text title3 semibold>
                Details
              </Text>
            </View>
            <View
              style={{
                maxHeight: Dimensions.get('window').height * 0.3,
                minHeight: 200,
              }}>
              <View style={styles.subHeader}>
                <Text grayColor semibold>
                  {translate('date')}
                </Text>
                <Text
                  grayColor
                  semibold
                  style={{
                    textAlign: 'right',
                    flex: 1,
                  }}>
                  {translate('Price')}
                </Text>
                <Text
                  grayColor
                  semibold
                  style={{
                    textAlign: 'right',
                    flex: 1,
                  }}>
                  {translate('Down')}
                </Text>
              </View>
              {explanation && (
                <FlatList
                  data={Object.keys(explanation)}
                  bounces={true}
                  keyExtractor={(item, index) => index}
                  renderItem={({item, index}) => {
                    // console.log("renderModal -> item", item); return;
                    const val = explanation[item];
                    const fPrice =
                      val && val.F && val.F.price ? val.F.price : 0;
                    const fDownPay =
                      val && val.F && val.F.downPayment ? val.F.downPayment : 0;
                    if (val.F) {
                      return (
                        <View style={styles.subHeader}>
                          <Text>
                            {moment(item, 'YYYY-MM-DD').format('DD MMMM YYYY')}
                          </Text>
                          {fPrice > 0 && (
                            <Text
                              numberOfLines={1}
                              style={{
                                textAlign: 'right',
                                flex: 1,
                              }}>
                              F: {fPrice} {cSymbol}
                            </Text>
                          )}
                          {fDownPay > 0 && (
                            <Text
                              style={{
                                textAlign: 'right',
                                flex: 1,
                              }}>
                              F: {fDownPay} {cSymbol}
                            </Text>
                          )}
                        </View>
                      );
                    } else if (val.E || val.M) {
                      const mPrice =
                        val && val.M && val.M.price ? val.M.price : 0;
                      const mDownPay =
                        val && val.M && val.M.downPayment
                          ? val.M.downPayment
                          : 0;
                      const ePrice =
                        val && val.E && val.E.price ? val.E.price : 0;
                      const eDownPay =
                        val && val.E && val.E.downPayment
                          ? val.E.downPayment
                          : 0;
                      return (
                        <View style={styles.subHeader}>
                          <Text>
                            {moment(item, 'YYYY-MM-DD').format('DD MMM, YYYY')}
                          </Text>
                          <View style={{flex: 1, alignItems: 'flex-end'}}>
                            {mPrice > 0 && (
                              <Text
                                numberOfLines={1}
                                style={{
                                  textAlign: 'center',
                                }}>
                                M: {mPrice} {cSymbol}
                              </Text>
                            )}
                            {ePrice > 0 && (
                              <Text
                                numberOfLines={1}
                                style={{
                                  textAlign: 'center',
                                  paddingTop: 5,
                                }}>
                                E: {` ${ePrice}`} {cSymbol}
                              </Text>
                            )}
                          </View>
                          <View style={{flex: 1, alignItems: 'flex-end'}}>
                            {mDownPay > 0 && (
                              <Text
                                style={{
                                  textAlign: 'center',
                                }}>
                                M: {mDownPay} {cSymbol}
                              </Text>
                            )}
                            {eDownPay > 0 && (
                              <Text
                                style={{
                                  textAlign: 'center',
                                  paddingTop: 5,
                                }}>
                                E: {` ${eDownPay}`} {cSymbol}
                              </Text>
                            )}
                          </View>
                        </View>
                      );
                    }
                  }}
                />
              )}
            </View>
            <View
              style={[
                styles.footer,
                {justifyContent: 'center', paddingBottom: 10},
              ]}>
              <Text semibold grayColor caption1>
                M : {translate('Morning')}
              </Text>
              <Text
                semibold
                grayColor
                caption1
                style={{
                  textAlign: 'center',
                  paddingHorizontal: 10,
                  // flex: 1,
                }}>
                E : {translate('Evening')}
              </Text>
              <Text semibold grayColor caption1>
                F : {translate('Full_Day')}
              </Text>
            </View>
            <View style={styles.footer}>
              <Text body1 primaryColor semibold>
                {translate('total_price')}
              </Text>
              <Text
                semibold
                body1
                style={{
                  textAlign: 'right',
                  flex: 1,
                  // marginLeft: 40,
                }}>
                {tPrice} {cSymbol}
              </Text>
              <Text
                semibold
                body1
                style={{
                  textAlign: 'right',
                  flex: 1,
                  // marginLeft: 40,
                }}>
                {tDownPay} {cSymbol}
              </Text>
            </View>
          </View>
        </Modal>
      </View>
    );
  }

  getSymbol = () => {
    const {country} = this.props.auth;
    const cSymbol = getCurrencySymbol(country);
    console.log('getCurrencySymbol===', cSymbol);
    this.setState({cSymbol});
  };

  isFullDayfromTime = (detail = null) => {
    if (detail === null) {
      detail = this.state.itemDetails;
    }
    return (
      _.isEmpty(detail.morning_start_time) && !_.isEmpty(detail.start_time)
    );
  };
  showActionSheet = () => {
    try {
      this.ActionSheet.show();
    } catch (error) {
      console.log('TCL: BookingDetail -> showActionSheet -> error', error);
    }
  };

  handleLocation = (i, type = 'google') => {
    const item = this.state.itemDetails;
    let url = `http://maps.google.com/?q=${item.lat},${item.lng}`;
    if (i === 1) {
      url = `http://maps.apple.com/?daddr=${item.lat},${item.lng}`;
    } else {
      url = `http://maps.google.com/?q=${item.lat},${item.lng}`;
    }
    Linking.canOpenURL(url)
      .then(supported => {
        if (supported) {
          return Linking.openURL(url);
        }
      })
      .catch(err => {
        console.error('An error occurred', err);
      });
  };

  render() {
    const {
      auth,
      navigation,
      language: {languageData},
    } = this.props;
    const {
      picsArray,
      pageLoad,
      itemDetails,
      shoModal,
      selectedDate,
      period,
      loading,
      dayPrices,
    } = this.state;

    const calendarWidth = Dimensions.get('window').width - 60;

    const region = this.regionFrom(itemDetails.lat, itemDetails.lng);
    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : true;

    let selectedCategory = navigation.getParam('selectedCategory', '');
    let itemID = navigation.getParam('itemID', '');
    let itemImage = navigation.getParam('itemImage', '');
    let itemCity = navigation.getParam('itemCity', '');
    let itemName = navigation.getParam('itemName', '');

    console.log('Details screen ===> ', itemID, itemImage);
    let seletedWeekDay = moment(selectedDate).format('ddd');

    console.log(
      'Price ===> ',
      period,
      translate('Full_Day'),
      selectedDate,
      seletedWeekDay,
    );

    const headerBgStyle = this.state.scrollY.interpolate({
      inputRange: [0, HEADER_SCROLL_DISTANCE / 2, HEADER_SCROLL_DISTANCE],
      outputRange: [
        'rgba(77,178,229,0)',
        'rgba(77,178,229,0.5)',
        'rgba(77,178,229,1)',
      ],
      extrapolate: 'clamp',
    });

    const titleY = this.state.scrollY.interpolate({
      inputRange: [0, imageViewHeight - 30, imageViewHeight],
      outputRange: [50, 50, 0],
      extrapolate: 'clamp',
    });

    const titleO = this.state.scrollY.interpolate({
      inputRange: [0, imageViewHeight - 30, imageViewHeight],
      outputRange: [0, 0, 1],
      extrapolate: 'clamp',
    });

    let headerHeight;
    if (!IOS) {
      headerHeight = 55;
    } else if (IOS && isIphoneX()) {
      headerHeight = 90;
    } else {
      headerHeight = 70;
    }

    const amenitiesArray =
      itemDetails && itemDetails.amenities ? itemDetails.amenities : [];

    const seperator = (
      <View
        style={{
          height: '100%',
          width: 0.5,
          backgroundColor: BaseColor.textSecondaryColor,
        }}
      />
    );

    const seperatorHor = (
      <View
        style={{
          height: 0.5,
          width: '100%',
          backgroundColor: BaseColor.textSecondaryColor,
        }}
      />
    );

    /* Airbnb mode */
    const screenY = this.state.swipeBottom.interpolate({
      inputRange: [0, height / 2],
      outputRange: [0, height / 2],
      extrapolate: 'clamp',
    });

    const screenScale = this.state.swipeBottom.interpolate({
      inputRange: [0, height / 2],
      outputRange: [1, 0.8],
      extrapolate: 'clamp',
    });

    const shadowOpacity = this.state.swipeBottom.interpolate({
      inputRange: [0, height / 2],
      outputRange: [1, 0],
      extrapolate: 'clamp',
    });
    console.log('pageload===', this.state.pageLoad);
    const {bookmarkLoading} = this.state;
    const {
      booking: {bookingData},
    } = this.props;
    let perType = 'Morning';
    if (period && !_.isEmpty(period)) {
      perType = period;
    } else if (selectedCategory !== categoryName.pools) {
      perType = 'FullDay';
    }
    const {startDate, endDate} = this.state;
    console.log('render -> startDate, endDate', startDate, endDate, perType);
    const MSTime = itemDetails.morning_start_time
      ? itemDetails.morning_start_time
      : '';
    const ESTime = itemDetails.evening_start_time
      ? itemDetails.evening_start_time
      : '';
    const sTime = itemDetails.start_time ? itemDetails.start_time : '';
    const iTime = itemDetails.interval_time
      ? Number(itemDetails.interval_time)
      : 0;
    let hType = 'pool';
    if (selectedCategory === categoryName.chalets) {
      hType = 'chalet';
    } else if (selectedCategory === categoryName.camps) {
      hType = 'camp';
    }
    console.log('render -> iTime', bookingData, itemDetails, iTime);
    // return;
    const toDate =
      startDate && !_.isEmpty(startDate)
        ? startDate
        : bookingData && bookingData.startingDate
        ? bookingData.startingDate
        : '';
    const fromDate =
      endDate && !_.isEmpty(endDate)
        ? endDate
        : bookingData && bookingData.endingDate
        ? bookingData.endingDate
        : '';
    const startPeriod =
      bookingData &&
      bookingData.startPeriod &&
      !_.isEmpty(bookingData.startPeriod)
        ? bookingData.startPeriod
        : periodTypes[this.isFullDayfromTime() ? 2 : 0];
    const endPeriod =
      bookingData && bookingData.endPeriod && !_.isEmpty(bookingData.endPeriod)
        ? bookingData.endPeriod
        : periodTypes[this.isFullDayfromTime() ? 2 : 0];
    console.log('Item ===> Dates', bookingData, fromDate, toDate);
    let fromBookmark = navigation.getParam('fromBookmark', '');
    const intervalDisable = setIntervalTime(
      MSTime,
      ESTime,
      sTime,
      iTime,
      toDate && moment(toDate).format('ddd, DD MMMM YYYY'),
    );
    console.log('CCalendar -> handleBottomButtons -> intervalTime', iTime);
    console.log('render -> intervalDisable', intervalDisable);
    let mDisable = false;
    let eDisable = false;
    let fDisable = false;
    if (intervalDisable) {
      mDisable = intervalDisable.isMorningDisable;
      eDisable = intervalDisable.isEveningDisable;
      fDisable = intervalDisable.isFullDayDisable;
    }
    const {filterDataType, allFilters} = this.props.filter;
    console.log('render -> filterDataType', bookingData, allFilters);
    const isFullDay =
      (allFilters.poolFilters &&
        allFilters.poolFilters.byPeriod === 'Full Day') ||
      itemDetails.price_type === 'single'
        ? true
        : false;

    let morDisable =
      _.isEqual(fromDate, toDate) &&
      bookingData &&
      bookingData.startPeriod &&
      bookingData.startPeriod.id === 2 &&
      bookingData.endPeriod.id === 1;
    console.log('render -> morDisable', morDisable, bookingData);
    const {cSymbol} = this.state;
    const currentDate = moment();
    var futureMonth = moment(currentDate).add(3, 'M');
    const futureMonthEnd = moment(futureMonth).endOf('month');
    if (
      currentDate.date() != futureMonth.date() &&
      futureMonth.isSame(futureMonthEnd.format('YYYY-MM-DD'))
    ) {
      futureMonth = futureMonth.add(1, 'd');
    }

    const isAfterDate = moment(toDate).isAfter(
      futureMonth.format('YYYY-MM-DD'),
    );
    console.log(
      'render -> isAfterDate',
      fromDate,
      toDate,
      futureMonth.format('YYYY-MM-DD'),
      isAfterDate,
      itemDetails,
    );
    return (
      <Animated.View
        // {...this._panResponder.panHandlers}
        style={{
          flex: 1,
        }}>
        <ActionSheet
          ref={o => (this.ActionSheet = o)}
          title="Open Map"
          options={options}
          cancelButtonIndex={2}
          onPress={index => this.handleLocation(index)}
        />
        <NavigationEvents
          onWillFocus={payload => {
            if (!IOS) {
              let fromPayment = this.props.navigation.getParam(
                'fromPayment',
                '',
              );
              if (fromPayment) {
                this.props.navigation.goBack();
              }
            }
          }}
        />
        <Animated.View
          style={[
            StyleSheet.absoluteFill,
            {backgroundColor: '#00000080', opacity: shadowOpacity},
          ]}
        />
        <Animated.View
          style={{
            flex: 1,
            transform: [{translateY: screenY}, {scale: screenScale}],
            backgroundColor: BaseColor.whiteColor,
          }}>
          <LinearGradient
            colors={[BaseColor.primaryColor, '#00000000']}
            style={styles.linearGradient}
          />
          <Header
            style={{
              position: 'absolute',
              top: 0,
              left: 0,
              right: 0,
              width: '100%',
              paddingTop: IOS ? getStatusBarHeight() : 0,
              height: headerHeight,
              zIndex: 999999,
              backgroundColor: headerBgStyle,
            }}
            title=""
            renderLeft={() => {
              return (
                <Icon
                  name="arrow-left"
                  size={20}
                  color={BaseColor.whiteColor}
                />
              );
            }}
            renderCenter={() => {
              return (
                <Animated.View
                  style={{
                    marginTop: 18,
                    transform: [{translateY: titleY}],
                    opacity: titleO,
                  }}>
                  <Text
                    title3
                    numberOfLines={1}
                    semibold
                    style={{
                      color: BaseColor.whiteColor,
                      paddingTop: IOS ? 0 : 5,
                    }}>
                    {'  '}
                    {languageData === 'en'
                      ? itemDetails.name_EN
                      : itemDetails.name_AR}
                    {'  '}
                  </Text>
                </Animated.View>
              );
            }}
            renderRight={() => {
              return (
                <Icon name="share-alt" size={20} color={BaseColor.whiteColor} />
              );
            }}
            // renderRightSecond={() => {
            //   return (
            //     <Icon name="images" size={20} color={BaseColor.whiteColor} />
            //   );
            // }}
            onPressLeft={() => {
              if (this.swiper) {
                this.swiper.scrollTo(0);
              }
              setTimeout(() => {
                navigation.goBack();
              }, 100);
            }}
            onPressRight={() => {
              this.sharePool();
            }}
          />
          <View
            style={[
              BaseStyle.safeAreaView,
              IOS && isIphoneX() ? BaseStyle.iphoneXStyle : {},
            ]}>
            <Animated.ScrollView
              bounces={false}
              showsVerticalScrollIndicator={false}
              style={{position: 'relative'}}
              scrollEventThrottle={16}
              // {...this._panResponder.panHandlers}
              onScroll={Animated.event([
                {nativeEvent: {contentOffset: {y: this.state.scrollY}}},
              ])}>
              {/* Main Container */}
              <View>
                {/* Image View */}
                <View style={{height: imageViewHeight, width: '100%'}}>
                  <Swiper
                    dotStyle={{
                      backgroundColor: BaseColor.textSecondaryColor,
                      flexWrap: 'wrap',
                    }}
                    loop={false}
                    ref={swiper => (this.swiper = swiper)}
                    activeDotColor={BaseColor.primaryColor}>
                    {picsArray.map((item, key) => {
                      const imgMarkup = (
                        <TouchableOpacity
                          key={key}
                          activeOpacity={1}
                          style={{height: imageViewHeight, width: '100%'}}
                          onPress={() => {
                            if (!item.initial) {
                              navigation.navigate('PreviewImage', {
                                picsArray: picsArray,
                                itemDetails: itemDetails,
                                index: key,
                              });
                            }
                          }}>
                          <SharedElement
                            id={`image_${itemID}${key != 0 ? key : ''}`}>
                            <Image
                              key={key}
                              style={{width: '100%', height: '100%'}}
                              resizeMode="cover"
                              source={{
                                uri: item.initial
                                  ? itemImage
                                  : item.serverPath +
                                    (item.thumb && item.thumb[7]
                                      ? item.thumb[7]
                                      : item.name),
                              }}
                            />
                          </SharedElement>
                        </TouchableOpacity>
                      );
                      return imgMarkup;
                    })}
                  </Swiper>
                </View>
                {/* Information */}
                <View style={styles.contentBoxTop}>
                  <View style={styles.contentWrapper}>
                    <View style={styles.titleWrapper}>
                      {/* Item Name */}
                      <SharedElement id={`text_${itemID}`}>
                        <Text body1 semibold>
                          {itemName !== ''
                            ? itemName
                            : languageData === 'en'
                            ? itemDetails.name_EN
                            : itemDetails.name_AR}
                        </Text>
                      </SharedElement>
                      {/* Bookmark Button */}
                      {pageLoad ? null : (
                        <TouchableOpacity
                          onPress={() => {
                            isGuestUser
                              ? this.showLoginAlert('login_feature')
                              : this.setBookMarkAPICall();
                          }}>
                          {bookmarkLoading ? (
                            <CLoader />
                          ) : (
                            <MCIcon
                              name={
                                itemDetails.isBookmarked
                                  ? 'heart'
                                  : 'heart-outline'
                              }
                              size={25}
                              color={BaseColor.primaryColor}
                              // color={'#F42F4C'}
                            />
                          )}
                        </TouchableOpacity>
                      )}
                    </View>
                    <View
                      style={[
                        styles.titleWrapper,
                        {paddingTop: 0, paddingBottom: 10},
                      ]}>
                      <SharedElement id={`location_${itemID}`}>
                        <View
                          style={{
                            flexDirection: 'row',
                            alignItems: 'center',
                            paddingLeft: 0,
                          }}>
                          {/* Item Location */}
                          <Icon
                            name="map-marker-alt"
                            color={BaseColor.lightPrimaryColor}
                            size={12}
                            style={{paddingRight: 4}}
                          />
                          <Text caption1 grayColor semibold>
                            {itemCity != ''
                              ? itemCity
                              : languageData === 'en'
                              ? itemDetails.city_EN
                              : itemDetails.city_AR}
                            {'  '}
                          </Text>
                        </View>
                      </SharedElement>
                      {/* Item Rating */}
                      {itemDetails.avgRating &&
                        Number(itemDetails.avgRating) > 0 && (
                          <TouchableOpacity
                            onPress={() =>
                              this.props.navigation.navigate('Review', {
                                serviceID: itemDetails.ID,
                              })
                            }>
                            <StarRating
                              disabled={true}
                              starSize={14}
                              maxStars={5}
                              rating={itemDetails.avgRating}
                              selectedStar={rating => {}}
                              fullStarColor={BaseColor.yellowColor}
                            />
                          </TouchableOpacity>
                        )}
                    </View>
                  </View>
                  {/* Item Description */}
                  {this.state.pageLoad ? (
                    <View
                      style={{
                        justifyContent: 'center',
                        alignItems: 'center',
                        paddingLeft: 10,
                        flex: 1,
                        // backgroundColor: 'red'
                      }}>
                      <NewDetailLoader />
                    </View>
                  ) : itemDetails.description_EN ||
                    itemDetails.description_AR ? (
                    <Text
                      title
                      style={{textAlign: 'center', marginTop: 5, padding: 20}}>
                      {languageData === 'en'
                        ? itemDetails.description_EN
                        : itemDetails.description_AR}
                    </Text>
                  ) : null}
                  {seperatorHor}
                  {/* Facilities Icon */}
                  {amenitiesArray && (
                    <View style={styles.contentService}>
                      {amenitiesArray.map((item, index) => (
                        <View
                          style={{
                            alignItems: 'center',
                            paddingVertical: 10,
                            paddingHorizontal: 5,
                            width: (Dimensions.get('window').width - 40) * 0.25,
                          }}
                          key={'service' + index}>
                          {item.iconType === 'image' ? (
                            <Image
                              tintColor={BaseColor.primaryColor}
                              source={{uri: item.serverImgLink}}
                              style={styles.img}
                            />
                          ) : (
                            <MCIcon
                              name={item.iconClass}
                              color={BaseColor.primaryColor}
                              size={30}
                            />
                          )}
                          <Text overline grayColor style={{marginTop: 4}}>
                            {item.value} x{' '}
                            {languageData === 'en' ? item.name : item.name_AR}
                          </Text>
                        </View>
                      ))}
                    </View>
                  )}
                  {/* <View /> */}
                  {seperatorHor}
                  {/* Open Time and other details */}
                  {!pageLoad && (
                    <View style={styles.blockView}>
                      <Text
                        headline
                        semibold
                        style={{textAlign: 'center', marginBottom: 10}}>
                        {translate('know')}
                      </Text>
                      <View
                        style={{
                          flexDirection: 'row',
                          marginTop: 5,
                          // backgroundColor: 'red',
                          alignItems: 'center',
                        }}>
                        <View
                          style={{
                            paddingTop: 10,
                            flex: 1,
                            justifyContent: 'center',
                            alignItems: 'center',
                          }}>
                          <View
                            style={{
                              flex: 1,
                              flexDirection: 'row',
                              alignItems: 'flex-end',
                              marginBottom: 13,
                            }}>
                            {selectedCategory === categoryName.pools &&
                            !this.isFullDayfromTime() ? (
                              <>
                                <MIcon
                                  name="wb-sunny"
                                  size={15}
                                  color="#f9d71c"
                                />
                                <Text title semibold>
                                  {' '}
                                  : {itemDetails.morning_start_time} to{' '}
                                  {itemDetails.morning_end_time}
                                </Text>
                              </>
                            ) : (
                              <Text title semibold>
                                {translate('RentalـTimes')}
                              </Text>
                            )}
                          </View>
                          <View
                            style={{
                              flex: 1,
                              flexDirection: 'row',
                              marginBottom: 10,
                            }}>
                            <IIcon
                              name={
                                selectedCategory === categoryName.pools &&
                                !this.isFullDayfromTime()
                                  ? 'ios-moon'
                                  : 'md-clock'
                              }
                              size={15}
                              color={
                                selectedCategory === categoryName.pools &&
                                !this.isFullDayfromTime()
                                  ? '#a9a9a9'
                                  : '#000'
                              }
                            />
                            <Text title semibold>
                              {' '}
                              :{' '}
                              {selectedCategory === categoryName.pools &&
                              !this.isFullDayfromTime()
                                ? itemDetails.evening_start_time
                                : itemDetails.start_time}{' '}
                              to{' '}
                              {selectedCategory === categoryName.pools &&
                              !this.isFullDayfromTime()
                                ? itemDetails.evening_end_time
                                : itemDetails.end_time}
                            </Text>
                          </View>
                        </View>
                        {seperator}
                        <View
                          style={{
                            // paddingTop: 5,
                            flex: 1,
                            justifyContent:
                              selectedCategory === categoryName.pools
                                ? 'flex-start'
                                : 'center',
                            alignItems: 'center',
                          }}>
                          <Text title semibold style={{marginBottom: 13}}>
                            {translate('Size')}: ({itemDetails.size})
                          </Text>
                          {selectedCategory === categoryName.pools ? (
                            <Text title semibold>
                              {translate('Water')}: {itemDetails.water}
                            </Text>
                          ) : null}
                        </View>
                      </View>
                    </View>
                  )}
                  {seperatorHor}
                  {/* Item Address */}
                  {!pageLoad && (
                    <View style={{padding: 20, paddingTop: 0}}>
                      <Text title3 semibold style={{marginVertical: 10}}>
                        {translate('Address')}
                      </Text>
                      <MapView
                        provider={PROVIDER_GOOGLE}
                        style={styles.mapView}
                        region={region}
                        scrollEnabled={false}
                        zoomEnabled={false}>
                        <Marker
                          coordinate={{
                            latitude: region.latitude,
                            longitude: region.longitude,
                          }}
                          title={itemDetails.name_EN}
                          description={itemDetails.location}
                        />
                        <MIcon
                          onPress={() => {
                            if (IOS) {
                              this.showActionSheet(itemDetails);
                            } else {
                              this.handleLocation(0);
                            }
                            // const url = Platform.select({
                            //   ios: `http://maps.apple.com/?daddr=${itemDetails.lat},${itemDetails.lng}`,
                            //   android: `http://maps.google.com/?q=${itemDetails.lat},${itemDetails.lng}`,
                            // });
                            // Linking.canOpenURL(url)
                            //   .then(supported => {
                            //     if (supported) {
                            //       return Linking.openURL(url);
                            //     }
                            //   })
                            //   .catch(err => {
                            //     console.error('An error occurred', err);
                            //   });
                          }}
                          style={styles.iconButton}
                          name="directions"
                          size={30}
                          color={BaseColor.primaryColor}
                        />
                      </MapView>
                    </View>
                  )}
                </View>
                {toDate !== '' && fromDate !== '' && !isAfterDate && (
                  <>
                    {seperatorHor}
                    <View
                      style={{
                        width: '100%',
                        flexDirection: 'row',
                        // margin: 5,
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        paddingHorizontal: 20,
                      }}>
                      <View>
                        <Text title3 semibold style={{marginVertical: 10}}>
                          {translate('price_details')}
                        </Text>
                      </View>
                      <TouchableOpacity
                        disabled={morDisable}
                        activeOpacity={0.7}
                        style={{opacity: morDisable ? 0.5 : 1}}
                        onPress={() => {
                          this.setState({priceModal: true});
                        }}>
                        <Text semiBold primaryColor>
                          {translate('view_details')}
                        </Text>
                      </TouchableOpacity>
                    </View>
                    <TouchableOpacity
                      onPress={() => {
                        this.setState({
                          shoModal: true,
                        });
                      }}
                      activeOpacity={0.7}
                      style={styles.detailCard}>
                      <View>
                        <View
                          onPress={this.openCalendar}
                          style={{paddingLeft: 10, paddingRight: 20}}>
                          {selectedDate === '' ? null : (
                            <View
                              style={{
                                width: '100%',
                                flexDirection: 'row',
                                margin: 5,
                              }}>
                              <View
                                style={{
                                  flex: 1,
                                  justifyContent: 'center',
                                  alignItems: 'flex-start',
                                }}>
                                <Text title style={{marginBottom: 10}}>
                                  {translate('From')}
                                </Text>
                                <Text title>
                                  {toDate &&
                                    moment(toDate).format('ddd, DD MMMM YYYY')}
                                </Text>
                                {filterDataType !== 'Pools' || isFullDay ? (
                                  <View
                                    activeOpacity={0.8}
                                    style={[
                                      styles.singlePeriod,
                                      styles.singleTag,
                                    ]}>
                                    <Text
                                      caption2
                                      medium
                                      style={{color: BaseColor.primaryColor}}>
                                      {translate('Full_Day')}
                                    </Text>
                                  </View>
                                ) : _.isObject(startPeriod) &&
                                  !_.isEmpty(startPeriod) ? (
                                  <View
                                    activeOpacity={0.8}
                                    style={[
                                      styles.singlePeriod,
                                      styles.singleTag,
                                    ]}>
                                    <Text
                                      caption2
                                      medium
                                      style={{color: BaseColor.primaryColor}}>
                                      {startPeriod.title}
                                    </Text>
                                  </View>
                                ) : _.isArray(startPeriod) &&
                                  !_.isEmpty(startPeriod) ? (
                                  <View
                                    activeOpacity={0.8}
                                    style={[
                                      styles.singlePeriod,
                                      styles.singleTag,
                                    ]}>
                                    <Text
                                      caption2
                                      medium
                                      style={{color: BaseColor.primaryColor}}>
                                      {startPeriod[0].title}
                                    </Text>
                                  </View>
                                ) : null}
                              </View>
                              {seperator}
                              <View
                                style={{
                                  flex: 1,
                                  justifyContent: 'center',
                                  alignItems: 'flex-end',
                                }}>
                                <Text title style={{marginBottom: 10}}>
                                  {translate('To')}
                                </Text>
                                <Text title>
                                  {fromDate &&
                                    moment(fromDate).format(
                                      'ddd, DD MMMM YYYY',
                                    )}
                                </Text>
                                {filterDataType !== 'Pools' || isFullDay ? (
                                  <View
                                    activeOpacity={0.8}
                                    style={[
                                      styles.singlePeriod,
                                      styles.singleTag,
                                    ]}>
                                    <Text
                                      caption2
                                      medium
                                      style={{color: BaseColor.primaryColor}}>
                                      Full Day
                                    </Text>
                                  </View>
                                ) : _.isObject(endPeriod) &&
                                  !_.isEmpty(endPeriod) ? (
                                  <View
                                    activeOpacity={0.8}
                                    style={[
                                      styles.singlePeriod,
                                      styles.singleTag,
                                    ]}>
                                    <Text
                                      caption2
                                      medium
                                      style={{color: BaseColor.primaryColor}}>
                                      {endPeriod.title}
                                    </Text>
                                  </View>
                                ) : null}
                              </View>
                            </View>
                          )}
                        </View>
                      </View>
                      {/* Payment Detrail */}
                      {selectedDate !== '' ? (
                        <View style={[{padding: 20, paddingLeft: 10}]}>
                          {/* {seperatorHor} */}
                          <View
                            style={{
                              width: '100%',
                              flexDirection: 'row',
                              margin: 5,
                              justifyContent: 'space-between',
                              alignItems: 'center',
                              paddingBottom: 5,
                            }}>
                            <Text title>{translate('total_price')}:</Text>
                            <Text title>
                              {this.getBookingPrice()} {cSymbol}
                            </Text>
                          </View>
                          {/* {seperatorHor} */}
                          <View
                            style={{
                              width: '100%',
                              flexDirection: 'row',
                              margin: 5,
                              justifyContent: 'space-between',
                              alignItems: 'center',
                              paddingVertical: 6,
                            }}>
                            <Text title>{translate('Down')}:</Text>
                            <Text title>
                              {this.getBookingPrice('totalDownpayment')}{' '}
                              {cSymbol}
                            </Text>
                          </View>
                          {/* {seperatorHor} */}
                          <View
                            style={{
                              width: '100%',
                              flexDirection: 'row',
                              margin: 5,
                              justifyContent: 'space-between',
                              alignItems: 'center',
                              paddingTop: 5,
                            }}>
                            <Text title>{translate('Insurance')}</Text>
                            <Text title>
                              {itemDetails.insurance === '0'
                                ? translate('No_Insurance')
                                : `${itemDetails.insurance} ${cSymbol}`}{' '}
                            </Text>
                          </View>
                        </View>
                      ) : null}
                    </TouchableOpacity>
                  </>
                )}

                {/* Family Detail */}
                {itemDetails.family_tag === '1' ? (
                  <View
                    style={{
                      padding: 20,
                      flexDirection: 'row',
                      backgroundColor: '#ec4c54',
                      borderRadius: 5,
                      alignItems: 'center',
                    }}>
                    <IIcon
                      name="md-alert"
                      color={BaseColor.whiteColor}
                      size={15}
                      style={{paddingRight: 10}}
                    />
                    <Text title bold style={{color: BaseColor.whiteColor}}>
                      {getCurrentFilterType(
                        selectedCategory,
                        'familyMsg',
                        true,
                      )}
                    </Text>
                  </View>
                ) : null}
              </View>
              {this.state.showAnimation ? (
                <View pointerEvents="none" style={[styles.animationWrap]}>
                  <LottieView
                    ref={animation => {
                      this.animation1 = animation;
                    }}
                    onAnimationFinish={() => {
                      this.setState({showAnimation: false});
                    }}
                    autoSize={false}
                    style={[styles.animation]}
                    source={require('@assets/lottie/bAnimate.json')}
                    autoPlay={true}
                    loop={false}
                  />
                </View>
              ) : null}
              {this.renderModal()}
            </Animated.ScrollView>
            {/* Pricing & Booking Process */}
            {pageLoad ? null : (
              <View style={styles.contentButtonBottom}>
                {toDate === '' || fromDate === '' || isAfterDate ? (
                  <View />
                ) : (
                  <View>
                    {selectedDate !== '' ? (
                      <>
                        {loading ? null : (
                          <>
                            <Text title>
                              {translate('Price')} / {translate('Down')}
                            </Text>
                            <Text title primaryColor semibold>
                              {this.getBookingPrice('totalDownpayment')}{' '}
                              {cSymbol}
                            </Text>
                          </>
                        )}
                      </>
                    ) : (
                      <>
                        <Text title>
                          {selectedCategory === categoryName.pools
                            ? translate('Select_Date_Period')
                            : translate('Please_Select_Date')}
                        </Text>
                      </>
                    )}
                  </View>
                )}
                <Button
                  // disable={loading || selectedDate === ''}
                  loading={loading}
                  style={{height: 46, opacity: loading ? 0.7 : 1.0}}
                  onPress={() => {
                    console.log('render -> mDisable', mDisable);
                    if (isAfterDate) {
                      CAlert(
                        `Sorry for inconvenience this ${hType} is currently under maintenance`,
                        'Alert!',
                      );
                      return;
                    }
                    if (morDisable) {
                      CAlert('Please Select valid period', 'Alert!', () => {
                        this.setState({shoModal: true});
                      });
                    } else if (
                      (mDisable &&
                        bookingData.startPeriod &&
                        bookingData.startPeriod.id === 1) ||
                      eDisable ||
                      fDisable
                    ) {
                      CAlert(
                        `Sorry for inconvenience today's booking time is over for this ${hType}. Please select another date or period to proceed!`,
                        'Alert!',
                        () => {
                          this.setState({shoModal: true});
                        },
                      );
                    } else {
                      this.checkBookBtn();
                    }
                  }}>
                  {translate('book_now')}
                </Button>
              </View>
            )}
          </View>
          <Modal
            isVisible={shoModal}
            animationType="fade"
            onBackdropPress={() => {
              console.log('Backdrop===>');
              if (this.calendaeRef) {
                console.log(
                  'render -> this.calendaeRef',
                  this.calendaeRef.state,
                );
              }
              this.setState({shoModal: false});
            }}
            coverScreen={true}
            hasBackdrop={true}
            deviceHeight={Dimensions.get('window').height}
            deviceWidth={Dimensions.get('window').width}
            animationIn="fadeIn"
            onRequestClose={() => {
              this.setState({
                shoModal: false,
              });
            }}>
            <View
              style={[
                styles.contentCalendar,
                {
                  height: Dimensions.get('window').height * 0.7,
                  width: calendarWidth + 20,
                },
              ]}>
              <CCalendar
                childCalRef={tcmp => {
                  console.log('REf ===> ', tcmp);
                  this.calendaeRef = tcmp;
                }}
                fromFilter={false}
                currency={cSymbol}
                isConnected={auth.isConnected}
                itemID={itemID}
                category={selectedCategory}
                periodType={bookingData.periodType || period}
                startDate={startDate}
                endingDay={endDate}
                selectedDate={
                  selectedDate !== ''
                    ? selectedDate
                    : moment().format('YYYY-MM-DD')
                }
                dayPrices={dayPrices}
                onClose={() => {
                  this.setState({
                    shoModal: false,
                  });
                }}
                morningStartTime={MSTime}
                eveningStartTime={ESTime}
                startTime={sTime}
                intervalTime={iTime}
                onDateSelect={async day => {
                  console.log('render -> day', day);
                  this.setState({
                    shoModal: false,
                    selectedDate: day.date,
                    period: day.period,
                  });
                }}
              />
            </View>
          </Modal>
        </Animated.View>
      </Animated.View>
    );
  }
}

HotelDetail.sharedElements = (navigation, otherNavigation, showing) => {
  let itemID = navigation.getParam('itemID', '');
  return [
    {id: `image_${itemID}`},
    // {id: `text_${itemID}`, animation: 'fade'},
    // {id: `location_${itemID}`},
  ];
};

HotelDetail.defaultProps = {
  auth: {},
  language: {},
  filter: {},
  booking: {},
};

HotelDetail.propTypes = {
  auth: PropTypes.objectOf(PropTypes.any),
  language: PropTypes.objectOf(PropTypes.any),
  filter: PropTypes.objectOf(PropTypes.any),
  booking: PropTypes.objectOf(PropTypes.any),
};

const mapStateToProps = state => ({
  auth: state.auth,
  language: state.language,
  filter: state.filter,
  booking: state.booking,
});

const mapDispatchToProps = dispatch => {
  return {
    AuthActions: bindActionCreators(AuthActions, dispatch),
    BookActions: bindActionCreators(BookActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(HotelDetail);
