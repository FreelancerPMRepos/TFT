/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  View,
  ScrollView,
  TouchableOpacity,
  FlatList,
  Switch,
  Dimensions,
} from 'react-native';
import Modal from 'react-native-modal';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {BaseStyle, BaseColor, BaseSetting} from '@config';
import {
  Header,
  SafeAreaView,
  Icon,
  Text,
  Button,
  ProfileDetail,
} from '@components';
import styles from './styles';
import _ from 'lodash';
import {getApiData} from '../../utils/apiHelper';
import PropTypes from 'prop-types';
import FilterActions from '../../redux/reducers/filter/actions';
// Load sample data
import {UserData} from '@data';
import CAlert from '../../components/CAlert';
import {translate} from '../../lang/Translate';
import categoryName from '../../config/category';
import {setStatusbar} from '@config';
import moment from 'moment';
import {codePushVersion} from 'app/config/statusbar';

var selected = {
  countries: '',
};
class Profile extends Component {
  constructor(props) {
    super();
    this.state = {
      reminders: false,
      loading: false,
      countries: [],
      selectedCountry: '',
      userData: UserData[0],
      modalVisible: false,
    };
  }

  /**
   * @description Simple logout with Redux
   * @author Passion UI <passionui.com>
   * @date 2019-08-03
   */
  componentDidMount() {
    setStatusbar('light');
    this.setCountries();
  }

  async setCountries() {
    const {country} = this.props.auth;
    const {
      AuthActions: {setUserCountry},
    } = this.props;
    if (this.props.filter.allFilters.allCountries) {
      const countries = await this.props.filter.allFilters.allCountries;
      console.log('countries ===> ', country, countries, this.props.filter);
      countries[0].checked = true;
      await this.setState({
        selectedCountry: country === '' ? countries[0] : country,
        countries,
      });
      await this.setState({
        countries: this.state.countries.map(item => {
          if (item.name === this.state.selectedCountry.name) {
            selected.countries = item;
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
  }

  onChangeCountries = select => {
    this.setState({
      countries: this.state.countries.map(item => {
        if (item.name === select.name) {
          selected.countries = item;
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
  };

  saveCountry = async () => {
    const {
      AuthActions: {setUserCountry},
      FilterActions: {setFilters},
      filter: {allFilters},
    } = this.props;
    // console.log('FILTER====>', this.props.filter);
    //reseting filter
    this.resetFilter();
    //saving country to Redux
    setUserCountry(selected.countries);
    // Calling API to get new City List and saving in Redux
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
    const data = {
      request: true,
      country_code: selected.countries.phone_code,
    };

    await getApiData(BaseSetting.endpoints.getCountries, 'post', data)
      .then(result => {
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

          const NearBy = {
            id: 0,
            city: 'Near me',
            checked: false,
          };
          //Add new Near Me option in Cities
          fData.poolCities.unshift(NearBy);
          fData.chaletesCities.unshift(NearBy);
          fData.campsCities.unshift(NearBy);
          setFilters(fData);
        } else {
          fData.poolCities = [];
          fData.chaletesCities = [];
          fData.campsCities = [];
          fData.allCountries = [];

          setFilters(fData);
        }
      })
      .catch(err => {
        console.log(`Error: ${err}`);
      });
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
  };

  // resetFilter = () => {
  //   const {navigation, auth} = this.props;
  //   const {
  //     FilterActions: {setFilters},
  //     filter: {filterDataType, allFilters},
  //   } = this.props;
  //   const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
  //   const pData =
  //     allFilters && _.isObject(allFilters) && allFilters.poolFilters
  //       ? allFilters.poolFilters
  //       : {};
  //   console.log('resetFilter -> pData', pData);
  //   const chaletsData =
  //     allFilters && _.isObject(allFilters) && allFilters.chaletFilters
  //       ? allFilters.chaletFilters
  //       : {};
  //   const campsData =
  //     allFilters && _.isObject(allFilters) && allFilters.campFilters
  //       ? allFilters.campFilters
  //       : {};
  //   let ftType = {};
  //   let ftTypeName = 'poolFilters';
  //   if (filterDataType === categoryName.pools) {
  //     ftType = pData;
  //     pData.byPeriod = 'Morning';
  //     pData.waterType = '';
  //     pData.startPeriod = {id: 1, title: 'Morning'};
  //     pData.endPeriod = {id: 1, title: 'Morning'};
  //     pData.minPrice =
  //       fData.poolMinMaxPrice && fData.poolMinMaxPrice.minPrice
  //         ? Number(fData.poolMinMaxPrice.minPrice)
  //         : '';
  //     pData.maxPrice =
  //       fData.poolMinMaxPrice && fData.poolMinMaxPrice.maxPrice
  //         ? Number(fData.poolMinMaxPrice.maxPrice)
  //         : '';
  //   }
  //   if (filterDataType === categoryName.chalets) {
  //     ftType = chaletsData;
  //     ftTypeName = 'chaletFilters';
  //     chaletsData.minPrice =
  //       fData.chaletMinMaxPrice && fData.chaletMinMaxPrice.minPrice
  //         ? Number(fData.chaletMinMaxPrice.minPrice)
  //         : '';
  //     chaletsData.maxPrice =
  //       fData.chaletMinMaxPrice && fData.chaletMinMaxPrice.maxPrice
  //         ? Number(fData.chaletMinMaxPrice.maxPrice)
  //         : '';
  //   }
  //   if (filterDataType === categoryName.camps) {
  //     ftType = campsData;
  //     ftTypeName = 'campFilters';
  //     campsData.minPrice =
  //       fData.campMinMaxPrice && fData.campMinMaxPrice.minPrice
  //         ? Number(fData.campMinMaxPrice.minPrice)
  //         : '';
  //     campsData.maxPrice =
  //       fData.campMinMaxPrice && fData.campMinMaxPrice.maxPrice
  //         ? Number(fData.campMinMaxPrice.maxPrice)
  //         : '';
  //   }
  //   ftType.desiredLocation = ['Everywhere'];
  //   ftType.byDate = moment().format('YYYY-MM-DD');
  //   ftType.startDate = moment().format('YYYY-MM-DD');
  //   ftType.endDate = moment()
  //     .add(1, 'days')
  //     .format('YYYY-MM-DD');
  //   ftType.lat = 0;
  //   ftType.lng = 0;
  //   ftType.amenities = 'None Selected';
  //   fData[ftTypeName] = ftType;

  //   fData.resetFilter = true;
  //   console.log('AccountSettings -> resetFilter -> fData', fData);
  //   setFilters(fData);
  // };

  onLogOut() {
    const {auth} = this.props;
    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : false;
    if (isGuestUser) {
      this.setState(
        {
          loading: true,
        },
        () => {
          setTimeout(() => {
            this.logOutAPICall();
          }, 500);
        },
      );
    } else {
      CAlert(
        translate('Are_You_Sure_logout'),
        translate('Logout'),
        () => {
          this.setState(
            {
              loading: true,
            },
            () => {
              setTimeout(() => {
                this.resetFilter();
                this.logOutAPICall();
              }, 500);
            },
          );
        },
        () => {},
        translate('Logout'),
        translate('Cancel'),
      );
    }
  }

  logOutAPICall = () => {
    const {
      auth,
      navigation,
      AuthActions: {setUserData},
    } = this.props;
    // eslint-disable-next-line no-unused-vars
    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : false;
    setUserData({});
    navigation.navigate('Start');
  };

  /**
   * @description Call when reminder option switch on/off
   */
  toggleSwitch = value => {
    this.setState({reminders: value});
  };

  render() {
    const {
      navigation,
      auth,
      language: {languageName},
    } = this.props;
    const {userData, loading, countries, selectedCountry} = this.state;
    console.log('SHOW COUNTRY', selectedCountry);
    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : true;
    const fName =
      _.isObject(auth.userData) && _.isString(auth.userData.first_name)
        ? auth.userData.first_name
        : '';
    const lName =
      _.isObject(auth.userData) && _.isString(auth.userData.last_name)
        ? auth.userData.last_name
        : '';
    const userName = fName + ' ' + lName;
    const email =
      _.isObject(auth.userData) && _.isString(auth.userData.email)
        ? auth.userData.email
        : '';
    const mobileNo =
      _.isObject(auth.userData) && _.isString(auth.userData.mobile)
        ? auth.userData.mobile
        : '';
    console.log('selectedCountry===>', selectedCountry);
    return (
      <SafeAreaView
        style={[BaseStyle.safeAreaView, {marginBottom: 64}]}
        forceInset={{top: 'always'}}>
        <Header title={translate('profile')} />
        <ScrollView>
          <View style={styles.contain}>
            <View style={{width: '100%'}}>
              <ProfileDetail
                icon={false}
                style={{
                  borderWidth: 1,
                  borderColor: '#ddd',
                  borderRadius: 7,
                  padding: 15,
                  shadowColor: BaseColor.lightPrimaryColor,
                  shadowOffset: {
                    width: 0,
                    height: 3,
                  },
                  shadowOpacity: 0.27,
                  shadowRadius: 4.65,
                  elevation: 6,
                  backgroundColor: '#fff',
                }}
                image={userData.image}
                imageTxt={`${fName.charAt(0)}${lName.charAt(0)}`}
                textFirst={userName}
                textSecond={mobileNo}
                textThird={email}
                // onPress={() => navigation.navigate('ProfileExanple')}
              />

              {isGuestUser ? null : (
                <View style={{marginTop: 20}}>
                  <TouchableOpacity
                    style={styles.profileItem}
                    onPress={() => {
                      navigation.navigate('AccountSettings');
                    }}>
                    <Text body1>{translate('Account_Settings')}</Text>
                    <Icon
                      name="angle-right"
                      size={18}
                      color={BaseColor.primaryColor}
                      style={{marginLeft: 5}}
                    />
                  </TouchableOpacity>
                  <TouchableOpacity
                    style={styles.profileItem}
                    onPress={() => {
                      navigation.navigate('AddPool');
                    }}>
                    <Text body1>{translate('add_pool')}</Text>
                    <View
                      style={{
                        flexDirection: 'row',
                        alignItems: 'center',
                      }}>
                      <Icon
                        name="angle-right"
                        size={18}
                        color={BaseColor.primaryColor}
                        style={{marginLeft: 5}}
                      />
                    </View>
                  </TouchableOpacity>
                </View>
              )}

              <TouchableOpacity
                style={styles.profileItem}
                onPress={() => {
                  this.setState({
                    modalVisible: true,
                  });
                }}>
                <Text body1>{translate('Country')}</Text>
                <View
                  style={{
                    flexDirection: 'row',
                    alignItems: 'center',
                  }}>
                  <Text body1 grayColor>
                    {selectedCountry.name}
                  </Text>
                  <Icon
                    name="angle-right"
                    size={18}
                    color={BaseColor.primaryColor}
                    style={{marginLeft: 5}}
                  />
                </View>
              </TouchableOpacity>
              <TouchableOpacity
                style={styles.profileItem}
                onPress={() => {
                  navigation.navigate('ChangeLanguage');
                }}>
                <Text body1>{translate('Language')}</Text>
                <View
                  style={{
                    flexDirection: 'row',
                    alignItems: 'center',
                  }}>
                  <Text body1 grayColor>
                    {languageName}
                  </Text>
                  <Icon
                    name="angle-right"
                    size={18}
                    color={BaseColor.primaryColor}
                    style={{marginLeft: 5}}
                  />
                </View>
              </TouchableOpacity>

              <View style={styles.profileItem}>
                <Text body1>{translate('App_Version')}</Text>
                <Text body1 grayColor>
                  {BaseSetting.appVersion} ({codePushVersion})
                </Text>
              </View>
            </View>
          </View>
        </ScrollView>
        <View style={{padding: 20}}>
          <Button full loading={loading} onPress={() => this.onLogOut()}>
            {isGuestUser ? translate('Login') : translate('Logout')}
          </Button>
        </View>

        {/* Country selection modal */}
        <Modal
          isVisible={this.state.modalVisible}
          propagateSwipe={true}
          onSwipeComplete={() => {
            this.saveCountry();
            this.setState({
              modalVisible: false,
              selectedCountry: selected.countries,
            });
          }}
          swipeDirection={'down'}
          onBackdropPress={() => {
            this.saveCountry();
            this.setState({
              modalVisible: false,
              selectedCountry: selected.countries,
            });
          }}
          swipeThreshold={200}
          style={[styles.bottomModal, {marginTop: 0}]}>
          <View style={styles.contentFilterBottom}>
            <View style={styles.contentSwipeDown}>
              <View style={styles.lineSwipeDown} />
            </View>
            <View style={[styles.contentActionModalBottom, {height: 50}]}>
              <TouchableOpacity
                onPress={() =>
                  this.setState({
                    modalVisible: false,
                    countries: this.state.countries.map(item => {
                      if (item.name === selectedCountry.name) {
                        selected.countries = item;
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
                  this.saveCountry();
                  this.setState({
                    modalVisible: false,
                    selectedCountry: selected.countries,
                  });
                }}>
                <Text body1 primaryColor>
                  {translate('save')}
                </Text>
              </TouchableOpacity>
            </View>
            <View
              style={{
                maxHeight: Dimensions.get('window').height * 0.65,
                paddingBottom: 20,
              }}>
              <FlatList
                data={countries}
                keyExtractor={(item, index) => item.id}
                renderItem={({item}) => {
                  if (item.show_app == '1') {
                    return (
                      <TouchableOpacity
                        style={styles.item}
                        onPress={() => this.onChangeCountries(item)}>
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
      </SafeAreaView>
    );
  }
}

Profile.defaultProps = {
  auth: {},
  language: {},
  filter: '',
};

Profile.propTypes = {
  auth: PropTypes.objectOf(PropTypes.any),
  language: PropTypes.objectOf(PropTypes.any),
  filter: PropTypes.string,
};

const mapStateToProps = state => {
  return {
    auth: state.auth,
    language: state.language,
    filter: state.filter,
  };
};

const mapDispatchToProps = dispatch => {
  return {
    AuthActions: bindActionCreators(AuthActions, dispatch),
    FilterActions: bindActionCreators(FilterActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(Profile);
