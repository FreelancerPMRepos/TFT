/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {setStatusbar} from '../../config/statusbar';
import {
  View,
  TextInput,
  FlatList,
  TouchableOpacity,
  ScrollView,
  Dimensions,
  Platform,
  PermissionsAndroid,
  Linking,
} from 'react-native';
import Geolocation from '@react-native-community/geolocation';
import {BaseStyle, BaseColor} from '@config';
import {
  Header,
  SafeAreaView,
  Icon,
  Text,
  Button,
  BookingTime,
  StarRating,
  Image,
} from '@components';
import {BaseSetting} from '../../config/setting';
import Modal from 'react-native-modal';
import RangeSlider from 'rn-range-slider';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import EIcon from 'react-native-vector-icons/Entypo';
import styles from './styles';
import moment from 'moment';
import {connect} from 'react-redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {bindActionCreators} from 'redux';
import {getApiData} from '../../utils/apiHelper';
import _ from 'lodash';
import CAlert from '../../components/CAlert';
import {translate} from '../../lang/Translate';
import FilterActions from '../../redux/reducers/filter/actions';
import categoryName from '../../config/category';
import MCIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import LocationModal from 'app/components/LocationModal';
import {getLatLng, getCurrencySymbol} from 'app/utils/booking';
import {isRTL} from '@utils';

const IOS = Platform.OS === 'ios';
let selected = {
  water: '',
  location: [],
  amenities: [],
};
class Search extends Component {
  constructor(props) {
    super(props);
    this.state = {
      location: [],
      waterType: [
        {
          id: '1',
          type: 'All',
          checked: true,
        },
        {id: '2', type: 'Artesian'},
        {id: '3', type: 'Chlorine'},
      ],
      priceBegin: 20,
      priceEnd: 200,
      markedDates: {},
      checkinTime: '',
      checkoutTime: '',
      modalVisible: false,
      loading: false,
      selectedWaterType: 'All',
      selectedLocation: ['Everywhere'],
      selectedDate: moment().format('YYYY-MM-DD'),
      selectedPeriod: 'Morning',
      lat: 0,
      lng: 0,
      amenities: [],
      selectedAmenities: 'None Selected',
      showLocationModal: false,
      cSymbol: 'BHD',
    };
    this.rangeSliderRef = React.createRef();
  }

  componentDidMount() {
    setStatusbar('light');
    console.log('FILTER ------------', this.props.filter);
    this.getSymbol();
    this.setAllFilters();
    selected = {
      water: '',
      location: [],
      amenities: [],
    };
  }
  getSymbol = () => {
    const {country} = this.props.auth;
    const cSymbol = getCurrencySymbol(country);
    console.log('getCurrencySymbol===', cSymbol);
    this.setState({cSymbol});
  };

  setAllFilters = () => {
    const {selectedLocation} = this.state;
    const {
      FilterActions: {setFilters},
      filter: {allFilters, filterDataType},
    } = this.props;
    console.log(
      'FILTER ===>  Search -> setAllFilters -> allFilters',
      allFilters,
    );

    if (
      filterDataType === categoryName.pools &&
      allFilters &&
      !_.isEmpty(allFilters) &&
      allFilters.poolFilters
    ) {
      if (allFilters.poolFilters.waterType) {
        const {waterType} = this.state;
        const waterTypeObj = _.find(waterType, {
          type: allFilters.poolFilters.waterType,
        });
        this.setState(
          {
            selectedWaterType: allFilters.poolFilters.waterType,
          },
          () => {
            this.onChangeWaterType(waterTypeObj);
          },
        );
      }
    }
    let citiesArray = [];
    let selectedCities = [];
    let minimumPrice = 0;
    let maximumPrice = 0;
    let savedAmenities = [];
    let amenitiesArray = [];
    let selectedDate = this.state.selectedDate;
    let filterType = 'poolFilters';
    let minMaxPrices = {};
    if (filterDataType === categoryName.pools) {
      minMaxPrices =
        allFilters && _.isObject(allFilters) && allFilters.poolMinMaxPrice
          ? allFilters.poolMinMaxPrice
          : {minPrice: 20, maxPrice: 200};
      citiesArray =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters &&
        allFilters.poolCities
          ? allFilters.poolCities
          : [];
      amenitiesArray =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters &&
        allFilters.poolAmenities
          ? allFilters.poolAmenities
          : [];
      savedAmenities =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.poolFilters &&
        !_.isEmpty(allFilters.poolFilters) &&
        allFilters.poolFilters.amenities &&
        allFilters.poolFilters.amenities
          ? allFilters.poolFilters.amenities
          : [];
      selectedCities =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.poolFilters &&
        allFilters.poolFilters.desiredLocation
          ? allFilters.poolFilters.desiredLocation
          : [];
      minimumPrice =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.poolFilters &&
        allFilters.poolFilters.minPrice
          ? allFilters.poolFilters.minPrice
          : Number(minMaxPrices.minPrice);
      maximumPrice =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.poolFilters &&
        allFilters.poolFilters.maxPrice
          ? allFilters.poolFilters.maxPrice
          : Number(minMaxPrices.maxPrice);
    } else if (filterDataType === categoryName.chalets) {
      filterType = 'chaletFilters';
      minMaxPrices =
        allFilters && _.isObject(allFilters) && allFilters.chaletMinMaxPrice
          ? allFilters.chaletMinMaxPrice
          : {minPrice: 20, maxPrice: 200};
      citiesArray =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters &&
        allFilters.chaletesCities
          ? allFilters.chaletesCities
          : [];
      amenitiesArray =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters &&
        allFilters.chaletAmenities
          ? allFilters.chaletAmenities
          : [];
      savedAmenities =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.chaletFilters &&
        !_.isEmpty(allFilters.chaletFilters) &&
        allFilters.chaletFilters.amenities &&
        allFilters.chaletFilters.amenities
          ? allFilters.chaletFilters.amenities
          : [];
      selectedCities =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.chaletFilters &&
        allFilters.chaletFilters.desiredLocation
          ? allFilters.chaletFilters.desiredLocation
          : [];
      minimumPrice =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.chaletFilters &&
        allFilters.chaletFilters.minPrice
          ? allFilters.chaletFilters.minPrice
          : Number(minMaxPrices.minPrice);
      maximumPrice =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.chaletFilters &&
        allFilters.chaletFilters.maxPrice
          ? allFilters.chaletFilters.maxPrice
          : Number(minMaxPrices.maxPrice);
    } else if (filterDataType === categoryName.camps) {
      filterType = 'campFilters';
      minMaxPrices =
        allFilters && _.isObject(allFilters) && allFilters.campMinMaxPrice
          ? allFilters.campMinMaxPrice
          : {minPrice: 20, maxPrice: 200};
      citiesArray =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters &&
        allFilters.campsCities
          ? allFilters.campsCities
          : [];
      amenitiesArray =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters &&
        allFilters.campAmenities
          ? allFilters.campAmenities
          : [];
      savedAmenities =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.campFilters &&
        !_.isEmpty(allFilters.campFilters) &&
        allFilters.campFilters.amenities &&
        allFilters.campFilters.amenities
          ? allFilters.campFilters.amenities
          : [];
      selectedCities =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.campFilters &&
        allFilters.campFilters.desiredLocation
          ? allFilters.campFilters.desiredLocation
          : [];
      minimumPrice =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.campFilters &&
        allFilters.campFilters.minPrice
          ? allFilters.campFilters.minPrice
          : Number(minMaxPrices.minPrice);
      maximumPrice =
        allFilters &&
        !_.isEmpty(allFilters) &&
        allFilters.campFilters &&
        allFilters.campFilters.maxPrice
          ? allFilters.campFilters.maxPrice
          : Number(minMaxPrices.maxPrice);
    }
    const nCities = selectedCities.length > 0 ? selectedCities : ['Everywhere'];
    citiesArray.map((city, i) => {
      if (_.isArray(selectedCities) && !_.isEmpty(selectedCities)) {
        const fIndex = selectedCities.findIndex(scity => scity === city.city);
        if (fIndex > -1) {
          city.checked = true;
        } else {
          city.checked = false;
        }
      } else if (i === 1) {
        city.checked = true;
      }
    });
    amenitiesArray.map((amenity, i) => {
      if (_.isArray(savedAmenities) && !_.isEmpty(savedAmenities)) {
        const fIndex = savedAmenities.findIndex(
          sAmenity => sAmenity === amenity.name,
        );
        if (fIndex > -1) {
          amenity.checked = true;
        } else {
          amenity.checked = false;
        }
      } else {
        amenity.checked = false;
      }
    });
    // console.log('SelectedCities:=====', selectedCities);
    // console.log('All Location:======', citiesArray);
    // console.log('Markded Location:======', nCities);
    // console.log('allFilters:======', allFilters);
    // console.log('FILTER ===> savedAmenities', savedAmenities);
    // console.log('minimumPrice:======', minimumPrice, maximumPrice);
    selected.amenities = savedAmenities;

    console.log('FILTER ===> Selected Amentiies set ===> ', selected.amenities);

    if (
      !_.isEmpty(allFilters[filterType]) &&
      !_.isEmpty(allFilters[filterType].byDate)
    ) {
      selectedDate = allFilters[filterType].byDate;
    }

    const nPeriod =
      allFilters && _.has(allFilters, 'poolFilters.byPeriod')
        ? allFilters.poolFilters.byPeriod
        : 'Morning';
    this.setState(
      {
        location: citiesArray,
        selectedLocation: nCities,
        priceBegin: minimumPrice,
        priceEnd: maximumPrice,
        amenities: amenitiesArray,
        selectedAmenities: savedAmenities,
        selectedDate,
        selectedPeriod: nPeriod,
      },
      () => {
        this.rangeSliderRef.setLowValue(minimumPrice);
        this.rangeSliderRef.setHighValue(maximumPrice);
      },
    );
  };

  handleFilterData = () => {
    console.log('Handle Filter Data ====> ', this.state);
    const {
      FilterActions: {setFilters},
      filter: {allFilters, filterDataType, position},
    } = this.props;
    const {
      selectedLocation,
      selectedWaterType,
      selectedDate,
      selectedPeriod,
      lat,
      lng,
      priceBegin,
      priceEnd,
      selectedAmenities,
    } = this.state;
    if (selectedLocation && selectedLocation[0] === 'Near me') {
      this.setState({
        lat: Number(position.coords.latitude),
        lng: Number(position.coords.longitude),
      });
    }
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
    if (filterDataType === categoryName.pools) {
      if (!fData.poolFilters) {
        fData.poolFilters = {};
      }
      fData.poolFilters.desiredLocation = selectedLocation;
      fData.poolFilters.byDate = selectedDate;
      fData.poolFilters.byPeriod = selectedPeriod;
      fData.poolFilters.waterType = selectedWaterType;
      fData.poolFilters.lat = this.state.lat;
      fData.poolFilters.lng = this.state.lng;
      fData.poolFilters.minPrice = priceBegin;
      fData.poolFilters.maxPrice = priceEnd;
      fData.poolFilters.amenities = selectedAmenities;
    } else if (filterDataType === categoryName.chalets) {
      if (!fData.chaletFilters) {
        fData.chaletFilters = {};
      }
      fData.chaletFilters.desiredLocation = selectedLocation;
      fData.chaletFilters.byDate = selectedDate;
      fData.chaletFilters.lat = lat;
      fData.chaletFilters.lng = lng;
      fData.chaletFilters.minPrice = priceBegin;
      fData.chaletFilters.maxPrice = priceEnd;
      fData.chaletFilters.amenities = selectedAmenities;
    } else if (filterDataType === categoryName.camps) {
      if (!fData.campFilters) {
        fData.campFilters = {};
      }
      fData.campFilters.desiredLocation = selectedLocation;
      fData.campFilters.byDate = selectedDate;
      fData.campFilters.lat = lat;
      fData.campFilters.lng = lng;
      fData.campFilters.minPrice = priceBegin;
      fData.campFilters.maxPrice = priceEnd;
      fData.campFilters.amenities = selectedAmenities;
    }
    fData.resetFilter = true;
    setFilters(fData);
  };

  onApply = () => {
    const {
      FilterActions: {setFilters},
      filter: {filterDataType, allFilters},
      navigation,
    } = this.props;
    console.log('On APPLY ===> ', allFilters);
    this.handleFilterData();
    this.setState({loading: true}, async () => {
      navigation.goBack();
    });
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
        this.setState({modalVisible: false});
      }
    } catch (err) {
      console.log('Got position from GPS ===> ', err);
      this.setState(
        {modalVisible: false, selectedLocation: ['Everywhere']},
        () => {
          Object.assign(this.state.location[0], {
            checked: false,
          });
          Object.assign(this.state.location[1], {
            checked: true,
          });
        },
      );
    }
  };

  openModal(modal) {
    this.setState({
      modalVisible: modal,
    });
  }

  onChangeWaterType(select) {
    this.setState({
      waterType: this.state.waterType.map(item => {
        if (item.type === select.type) {
          selected.water = item.type;
          return {
            ...item,
            checked: true,
          };
        } else {
          return {
            ...item,
            checked: false,
          };
        }
      }),
    });
  }

  onChangeAmenities(item) {
    console.log('FILTER ===>  On change Amenity ===> ', this.state.amenities);

    selected.amenities = [];
    this.state.amenities.map((amenity, i) => {
      if (amenity.name === item.name) {
        amenity.checked = !amenity.checked;
      }
      if (amenity.checked) {
        selected.amenities.push(amenity.name);
      } else {
        selected.amenities.splice(i, 1);
      }
    });

    console.log('FILTER ===>  On change Amenity 1 ===> ', this.state.amenities);
    this.setState(
      {
        amenities: this.state.amenities,
      },
      () => {
        console.log(
          'FILTER ===>  On change Amenity Done ===> ',
          this.state.amenities,
        );
      },
    );
  }

  changeBookingTime = (type, value, eVal) => {
    console.log('TCL: changeBookingTime -> value', type, eVal);
    type === 'date'
      ? this.setState(
          {
            selectedDate: value._i,
            selectedEndDate: eVal._i,
            // selectedDate: value.format('YYYY-MM-DD'),
          },
          () => {
            // this.handleFilterData(value, 'date');
          },
        )
      : this.setState(
          {
            selectedPeriod: value,
          },
          () => {
            // this.handleFilterData(value, 'period');
          },
        );
  };

  onChangeLocation = select => {
    const {location} = this.state;
    console.log('On Change Location ====> ', location, select);
    selected.location = [];
    const selLocs = location.map((item, key) => {
      if (item.city === select.city) {
        selected.location.push(item.city);
        return {
          ...item,
          checked: !item.checked,
        };
      } else if (select.id < 2 || (select.id >= 2 && item.id < 2)) {
        return {
          ...item,
          checked: false,
        };
      }
      if (item.checked) {
        selected.location.push(item.city);
      }
      return {
        ...item,
      };
    });

    console.log(
      'On Change Location ====> ',
      selected.location,
      JSON.stringify(selLocs),
    );

    if (selected.location.length > 0) {
      this.setState(
        {
          selectedLocation: selected.location,
          location: selLocs,
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

  resetFilter = () => {
    const {navigation, auth} = this.props;
    const {
      FilterActions: {setFilters},
      filter: {filterDataType, allFilters},
    } = this.props;
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
    const pData =
      allFilters && _.isObject(allFilters) && allFilters.poolFilters
        ? allFilters.poolFilters
        : {};
    console.log('resetFilter -> pData', fData, pData);
    const chaletsData =
      allFilters && _.isObject(allFilters) && allFilters.chaletFilters
        ? allFilters.chaletFilters
        : {};
    const campsData =
      allFilters && _.isObject(allFilters) && allFilters.campFilters
        ? allFilters.campFilters
        : {};
    let ftType = {};
    let ftTypeName = 'poolFilters';
    if (filterDataType === categoryName.pools) {
      ftType = pData;
      pData.byPeriod = '';
      pData.waterType = '';
      pData.startPeriod = '';
      pData.endPeriod = '';
      pData.minPrice =
        fData.poolMinMaxPrice && fData.poolMinMaxPrice.minPrice
          ? Number(fData.poolMinMaxPrice.minPrice)
          : '';
      pData.maxPrice =
        fData.poolMinMaxPrice && fData.poolMinMaxPrice.maxPrice
          ? Number(fData.poolMinMaxPrice.maxPrice)
          : '';
    }
    if (filterDataType === categoryName.chalets) {
      ftType = chaletsData;
      ftTypeName = 'chaletFilters';
      chaletsData.minPrice =
        fData.chaletMinMaxPrice && fData.chaletMinMaxPrice.minPrice
          ? Number(fData.chaletMinMaxPrice.minPrice)
          : '';
      chaletsData.maxPrice =
        fData.chaletMinMaxPrice && fData.chaletMinMaxPrice.maxPrice
          ? Number(fData.chaletMinMaxPrice.maxPrice)
          : '';
    }
    if (filterDataType === categoryName.camps) {
      ftType = campsData;
      ftTypeName = 'campFilters';
      campsData.minPrice =
        fData.campMinMaxPrice && fData.campMinMaxPrice.minPrice
          ? Number(fData.campMinMaxPrice.minPrice)
          : '';
      campsData.maxPrice =
        fData.campMinMaxPrice && fData.campMinMaxPrice.maxPrice
          ? Number(fData.campMinMaxPrice.maxPrice)
          : '';
    }
    ftType.desiredLocation = ['Everywhere'];
    ftType.byDate = '';
    ftType.startDate = '';
    ftType.endDate = '';
    ftType.lat = 0;
    ftType.lng = 0;
    ftType.amenities = 'None Selected';
    fData[ftTypeName] = ftType;

    fData.resetFilter = true;
    console.log('SET FILTERS =====>  Setting to ', fData);
    setFilters(fData);
    setTimeout(() => {
      navigation.navigate('Home');
    }, 200);
  };

  renderModal() {
    console.log('Search Render Modal ====> 1');
    const {
      adult,
      children,
      waterType,
      location,
      selectedWaterType,
      selectedLocation,
      modalVisible,
      amenities,
      selectedAmenities,
    } = this.state;
    console.log('Location==> 3', JSON.stringify(location));

    return (
      <View>
        <LocationModal
          modalVisible={this.state.modalVisible === 'location'}
          translate={translate}
          location={location}
          onChangeLocation={this.onChangeLocation}
          onModalClose={() =>
            this.setState({
              modalVisible: false,
            })
          }
        />
        <Modal
          propagateSwipe={true}
          isVisible={this.state.modalVisible === 'watertype'}
          onBackdropPress={() => {
            console.log('renderModal -> selected.water', selected.water);
            // if
            this.setState({
              modalVisible: false,
              selectedWaterType:
                selected.water && !_.isEmpty(selected.water)
                  ? selected.water
                  : 'All',
            });
          }}
          onSwipeComplete={() => {
            this.setState({
              modalVisible: false,
              selectedWaterType: selected.water,
            });
          }}
          swipeDirection={'down'}
          style={styles.bottomModal}>
          <View style={styles.contentFilterBottom}>
            <View style={styles.contentSwipeDown}>
              <View style={styles.lineSwipeDown} />
            </View>
            <View
              style={[
                styles.contentActionModalBottom,
                {height: IOS ? 55 : 50},
              ]}>
              <TouchableOpacity
                onPress={() =>
                  this.setState({
                    modalVisible: false,
                    waterType: this.state.waterType.map(item => {
                      if (item.type === selectedWaterType) {
                        selected.water = item.type;
                        return {
                          ...item,
                          checked: true,
                        };
                      } else {
                        return {
                          ...item,
                          checked: false,
                        };
                      }
                    }),
                  })
                }>
                <Text body1>{translate('cancel')}</Text>
              </TouchableOpacity>
              <TouchableOpacity
                onPress={() => {
                  this.setState({
                    modalVisible: false,
                    selectedWaterType: selected.water,
                  });
                }}>
                <Text body1 primaryColor>
                  {translate('save')}
                </Text>
              </TouchableOpacity>
            </View>
            <View
              style={[
                styles.lineRow,
                {
                  paddingBottom: 0,
                  maxHeight: Dimensions.get('window').height * 0.5,
                },
              ]}>
              <FlatList
                bounces={false}
                data={waterType}
                keyExtractor={(item, index) => item.id}
                contentContainerStyle={styles.flatList}
                renderItem={({item}) => (
                  <TouchableOpacity
                    style={styles.item}
                    onPress={() => this.onChangeWaterType(item)}>
                    <Text
                      body1
                      style={
                        item.checked
                          ? {
                              color: BaseColor.primaryColor,
                            }
                          : {}
                      }>
                      {item.type}
                    </Text>
                    {item.checked && (
                      <Icon
                        name="check"
                        size={14}
                        color={BaseColor.primaryColor}
                      />
                    )}
                  </TouchableOpacity>
                )}
              />
            </View>
          </View>
        </Modal>
        <Modal
          propagateSwipe={true}
          isVisible={this.state.modalVisible === 'amenitiesModal'}
          onSwipeComplete={() => {
            console.log('FILTER ===> ==', selected.amenities);
            this.setState({
              modalVisible: false,
              selectedAmenities: selected.amenities,
            });
          }}
          swipeDirection={'down'}
          swipeThreshold={200}
          onBackdropPress={() => {
            console.log('FILTER ===> ==', selected.amenities);
            this.setState({
              modalVisible: false,
              selectedAmenities: selected.amenities,
            });
          }}
          deviceHeight={Dimensions.get('window').height / 2}
          deviceWidth={Dimensions.get('window').width}
          // swipeDirection={['down']}
          style={styles.bottomModal}>
          <View style={[styles.contentFilterBottom]}>
            <View style={styles.contentSwipeDown}>
              <View style={styles.lineSwipeDown} />
            </View>
            <View
              style={[
                styles.contentActionModalBottom,
                {height: IOS ? 55 : 50},
              ]}>
              <TouchableOpacity
                onPress={() =>
                  this.setState({
                    modalVisible: false,
                    // amenities: this.state.amenities.map(item => {
                    //   console.log('item', item);
                    //   if (item.name === selectedAmenities) {
                    //     // selected.water = item.name;
                    //     this.setState({selectedAmenities: item.name});
                    //     return {
                    //       ...item,
                    //       checked: true,
                    //     };
                    //   } else {
                    //     this.setState({selectedAmenities: item.name});
                    //     return {
                    //       ...item,
                    //       checked: false,
                    //     };
                    //   }
                    // }),
                  })
                }>
                <Text body1>{translate('cancel')}</Text>
              </TouchableOpacity>
              <TouchableOpacity
                onPress={() => {
                  console.log('FILTER ===> ==', selected.amenities);
                  this.setState({
                    modalVisible: false,
                    selectedAmenities: selected.amenities,
                  });
                }}>
                <Text body1 primaryColor>
                  {translate('save')}
                </Text>
              </TouchableOpacity>
            </View>
            <View
              style={[
                styles.lineRow,
                {
                  paddingBottom: 0,
                  maxHeight: Dimensions.get('window').height * 0.7,
                },
              ]}>
              <FlatList
                bounces={false}
                data={amenities}
                keyExtractor={(item, index) => `${index}`}
                contentContainerStyle={styles.flatList}
                renderItem={({item}) => {
                  if (item) {
                    return (
                      <TouchableOpacity
                        style={styles.item}
                        onPress={() => {
                          console.log('item==', item);
                          this.onChangeAmenities(item);
                        }}>
                        <View
                          style={{
                            flexDirection: 'row',
                            alignItems: 'center',
                            flex: 1,
                          }}>
                          {item && item.iconType === 'image' ? (
                            <Image
                              tintColor={BaseColor.primaryColor}
                              source={{uri: item.serverImgLink}}
                              style={styles.img}
                            />
                          ) : (
                            <MCIcon
                              name={item.iconClass}
                              color={BaseColor.primaryColor}
                              size={22}
                              style={{paddingRight: 10}}
                            />
                          )}
                          <Text
                            body1
                            style={
                              item.checked
                                ? {
                                    color: BaseColor.primaryColor,
                                  }
                                : {}
                            }>
                            {item.name}
                          </Text>
                        </View>
                        {item.checked && (
                          <Icon
                            name="check"
                            size={14}
                            color={BaseColor.primaryColor}
                          />
                        )}
                      </TouchableOpacity>
                    );
                  }
                }}
              />
            </View>
          </View>
        </Modal>
      </View>
    );
  }

  render() {
    const {navigation} = this.props;
    const {filter} = this.props;
    const filterType =
      _.isObject(filter) && !_.isEmpty(filter) && filter.filterDataType
        ? filter.filterDataType
        : '';
    const {
      filter: {allFilters},
    } = this.props;
    console.log('render -> allFilters', allFilters);
    let selectedCities = [];
    let minMaxPrices = {};
    if (filterType === categoryName.pools) {
      selectedCities =
        allFilters &&
        _.isObject(allFilters) &&
        allFilters.poolFilters &&
        allFilters.poolFilters.desiredLocation
          ? allFilters.poolFilters.desiredLocation
          : ['Everywhere'];
      minMaxPrices =
        allFilters && _.isObject(allFilters) && allFilters.poolMinMaxPrice
          ? allFilters.poolMinMaxPrice
          : {minPrice: '20', maxPrice: '200'};
    } else if (filterType === categoryName.chalets) {
      selectedCities =
        allFilters &&
        _.isObject(allFilters) &&
        allFilters.chaletFilters &&
        allFilters.chaletFilters.desiredLocation
          ? allFilters.chaletFilters.desiredLocation
          : ['Everywhere'];
      minMaxPrices =
        allFilters && _.isObject(allFilters) && allFilters.chaletMinMaxPrice
          ? allFilters.chaletMinMaxPrice
          : {minPrice: '20', maxPrice: '200'};
    } else if (filterType === categoryName.camps) {
      selectedCities =
        allFilters &&
        _.isObject(allFilters) &&
        allFilters.campFilters &&
        allFilters.campFilters.desiredLocation
          ? allFilters.campFilters.desiredLocation
          : ['Everywhere'];
      minMaxPrices =
        allFilters && _.isObject(allFilters) && allFilters.campMinMaxPrice
          ? allFilters.campMinMaxPrice
          : {minPrice: '20', maxPrice: '200'};
    }
    const {
      selectedWaterType,
      selectedLocation,
      selectedPeriod,
      selectedDate,
      priceBegin,
      priceEnd,
      selectedAmenities,
      showLocationModal,
      cSymbol,
    } = this.state;
    console.log('render -> selectedWaterType', selectedWaterType);

    let locations = selectedLocation.length;
    let amenities =
      _.isArray(selectedAmenities) && !_.isEmpty(selectedAmenities)
        ? selectedAmenities.length
        : 0;
    console.log('Price-->', minMaxPrices);
    return (
      <SafeAreaView
        style={[
          BaseStyle.safeAreaView,
          {backgroundColor: BaseColor.whiteColor},
        ]}
        forceInset={{top: 'always'}}>
        {this.renderModal()}
        <View style={{paddingTop: IOS ? 10 : 0}}>
          <Header
            title="Filter"
            renderLeft={() => {
              return (
                <Icon
                  name="times"
                  size={20}
                  color={BaseColor.primaryColor}
                  style={{paddingRight: isRTL ? 25 : 0}}
                />
              );
            }}
            onPressLeft={() => {
              navigation.goBack();
            }}
          />
        </View>
        <ScrollView bounces={false} style={{padding: isRTL ? 10 : 15, flex: 1}}>
          {showLocationModal && (
            <>
              <View style={styles.contentQuest}>
                <TouchableOpacity
                  style={styles.field}
                  onPress={() => this.openModal('location')}>
                  <Text caption1 style={{marginBottom: 5}}>
                    {translate('desired_location')}
                  </Text>
                  <View style={{flexDirection: 'row'}}>
                    <View style={{flex: 1}}>
                      <EIcon
                        name="location"
                        size={20}
                        color={BaseColor.primaryColor}
                      />
                    </View>
                    <View style={{flex: 8}}>
                      {locations > 1 ? (
                        <Text body1 semibold>
                          {locations} selected{' '}
                        </Text>
                      ) : (
                        <Text body1 semibold>
                          {selectedLocation}
                        </Text>
                      )}
                    </View>
                  </View>
                </TouchableOpacity>
              </View>
              <BookingTime
                style={{marginBottom: 15}}
                onChange={this.changeBookingTime}
                showPeriod={filterType === categoryName.pools}
              />
            </>
          )}
          {/* {filterType === categoryName.pools ? ( */}
          <View
            style={[
              styles.contentPickDate,
              {marginBottom: 15, marginTop: 0, marginLeft: isRTL ? 20 : 0},
            ]}>
            {filterType === categoryName.pools ? (
              <TouchableOpacity
                style={styles.field}
                onPress={() => this.openModal('watertype')}>
                <Text caption1 style={{marginBottom: 5}}>
                  {translate('water_type')}
                </Text>
                <View
                  style={{flexDirection: 'row', justifyContent: 'flex-start'}}>
                  <View>
                    <EIcon
                      name="water"
                      size={20}
                      color={BaseColor.primaryColor}
                      style={{paddingRight: 10}}
                    />
                  </View>
                  <View>
                    <Text body1 semibold>
                      {selectedWaterType}
                    </Text>
                  </View>
                </View>
              </TouchableOpacity>
            ) : null}
            {filterType === categoryName.pools ? (
              <View style={styles.linePick} />
            ) : null}
            <TouchableOpacity
              style={styles.field}
              onPress={() => this.openModal('amenitiesModal')}>
              <Text caption1 style={{marginBottom: 5}}>
                {translate('Amenities')}
              </Text>
              <View style={{flexDirection: 'row'}}>
                <View style={{flex: 8}}>
                  {amenities > 1 ? (
                    <Text body1 semibold>
                      {amenities} selected{' '}
                    </Text>
                  ) : (
                    <Text body1 semibold>
                      {_.isArray(selectedAmenities) &&
                      selectedAmenities &&
                      selectedAmenities[0]
                        ? selectedAmenities[0]
                        : 'None Selected'}
                    </Text>
                  )}
                </View>
              </View>
              {/* <View style={{flexDirection: 'row'}}>
                  <View>
                    <EIcon
                      name="water"
                      size={20}
                      color={BaseColor.primaryColor}
                      style={{paddingRight: 10}}
                    />
                  </View>
                  <View>
                    <Text body1 semibold>
                      {selectedWaterType}
                    </Text>
                  </View>
                </View> */}
            </TouchableOpacity>
          </View>
          {/* ) : null} */}

          <View style={[styles.field, {marginLeft: isRTL ? 20 : 0}]}>
            <Text caption1 style={{marginBottom: 5}}>
              {translate('price')}
            </Text>
            <View style={styles.contentRange}>
              <Text caption1 grayColor>
                {minMaxPrices.minPrice} {cSymbol}
              </Text>
              <Text caption1 grayColor>
                {minMaxPrices.maxPrice} {cSymbol}
              </Text>
            </View>
            <RangeSlider
              ref={c => (this.rangeSliderRef = c)}
              style={{
                width: '100%',
                height: 40,
              }}
              thumbRadius={12}
              lineWidth={5}
              // initialLowValue={10}
              // initialHighValue={200}
              gravity={'center'}
              labelStyle="none"
              min={Number(minMaxPrices.minPrice)}
              max={Number(minMaxPrices.maxPrice)}
              step={1}
              selectionColor={BaseColor.primaryColor}
              blankColor={BaseColor.textSecondaryColor}
              onValueChanged={(low, high, fromUser) => {
                console.log(
                  'render -> low, high, fromUser',
                  low,
                  high,
                  fromUser,
                );
                this.setState({
                  priceBegin: low,
                  priceEnd: high,
                });
              }}
              onTouchStart={() => {
                this.setState({
                  scrollEnabled: false,
                });
              }}
              onTouchEnd={() => {
                this.setState({
                  scrollEnabled: true,
                });
              }}
            />
            <View style={styles.contentResultRange}>
              <Text caption1>{translate('avg_price')}</Text>
              <Text caption1>
                {priceBegin} - {priceEnd} {cSymbol}
              </Text>
            </View>
          </View>
        </ScrollView>
        <View style={[styles.btnWrapper]}>
          <Button
            // full
            onPress={() => this.onApply()}
            loading={this.state.loading}
            style={{width: '45%'}}>
            {translate('Apply')}
          </Button>
          <Button
            // full
            onPress={() => this.resetFilter()}
            // loading={this.state.loading}
            styleText={styles.titleStyle}
            style={[styles.resetBtn]}>
            {translate('Reset')}
          </Button>
        </View>
      </SafeAreaView>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
  filter: state.filter,
});

const mapDispatchToProps = dispatch => {
  return {
    AuthActions: bindActionCreators(AuthActions, dispatch),
    FilterActions: bindActionCreators(FilterActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(Search);
