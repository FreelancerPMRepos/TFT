/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  View,
  ScrollView,
  TouchableOpacity,
  FlatList,
  Switch,
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

var selected = {
  countries: '',
};
class AccountSettings extends Component {
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
    const countries = this.props.filter.allFilters.allCountries;
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

  saveCountry = () => {
    const {
      AuthActions: {setUserCountry},
      FilterActions: {setFilters},
      filter: {allFilters},
    } = this.props;
    console.log('FILTER====>', this.props.filter);
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

    getApiData(BaseSetting.endpoints.getCountries, 'post', data)
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
    const {navigation} = this.props;
    const {
      FilterActions: {setFilters},
      filter: {filterDataType, allFilters},
    } = this.props;
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
    const pData =
      allFilters && _.isObject(allFilters) && allFilters.poolFilters
        ? allFilters.poolFilters
        : {};
    const chaletsData =
      allFilters && _.isObject(allFilters) && allFilters.chaletFilters
        ? allFilters.chaletFilters
        : {};
    const campsData =
      allFilters && _.isObject(allFilters) && allFilters.campFilters
        ? allFilters.campFilters
        : {};
    if (filterDataType === categoryName.pools && fData.resetFilter) {
      pData.desiredLocation = ['Everywhere'];
      pData.byDate = '';
      pData.byPeriod = '';
      pData.waterType = '';
      fData.resetFilter.isResetFilter = true;
      setFilters(pData);
    } else {
      pData.desiredLocation = ['Everywhere'];
      pData.byDate = '';
      pData.byPeriod = '';
      pData.waterType = '';
      fData.resetFilter = {};
      fData.resetFilter.isResetFilter = true;
      setFilters(pData);
    }

    if (filterDataType === categoryName.chalets && fData.resetFilter) {
      chaletsData.desiredLocation = ['Everywhere'];
      chaletsData.byDate = '';
      fData.resetFilter.isResetFilter = true;
      setFilters(chaletsData);
    } else {
      chaletsData.desiredLocation = ['Everywhere'];
      chaletsData.byDate = '';
      fData.resetFilter = {};
      fData.resetFilter.isResetFilter = true;
      setFilters(chaletsData);
    }

    if (filterDataType === categoryName.camps && fData.resetFilter) {
      campsData.desiredLocation = ['Everywhere'];
      campsData.byDate = '';
      fData.resetFilter.isResetFilter = true;
      setFilters(campsData);
    } else {
      campsData.desiredLocation = ['Everywhere'];
      campsData.byDate = '';
      fData.resetFilter = {};
      fData.resetFilter.isResetFilter = true;
      setFilters(campsData);
    }
    setFilters(fData);
  };

  onLogOut() {
    const {auth} = this.props;
    // eslint-disable-next-line no-unused-vars
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

    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <Header
          title={translate('Account_Settings')}
          renderLeft={() => {
            return (
              <Icon
                name="arrow-left"
                size={20}
                color={BaseColor.primaryColor}
              />
            );
          }}
          onPressLeft={() => {
            navigation.goBack();
          }}
        />
        <ScrollView>
          <View style={styles.contain}>
            <View style={{width: '100%'}}>
              {isGuestUser ? null : (
                <View>
                  <TouchableOpacity
                    style={styles.profileItem}
                    onPress={() => {
                      navigation.navigate('ChangeProfile');
                    }}>
                    <Text body1>{translate('Change_Profile')}</Text>
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
                      navigation.navigate('ChangePhone');
                    }}>
                    <Text body1>{translate('Change_Number')}</Text>
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
                      navigation.navigate('ChangePassword');
                    }}>
                    <Text body1>{translate('Change_Password')}</Text>
                    <Icon
                      name="angle-right"
                      size={18}
                      color={BaseColor.primaryColor}
                      style={{marginLeft: 5}}
                    />
                  </TouchableOpacity>
                </View>
              )}
            </View>
          </View>
        </ScrollView>
      </SafeAreaView>
    );
  }
}

AccountSettings.defaultProps = {
  auth: {},
  language: {},
  filter: '',
};

AccountSettings.propTypes = {
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

export default connect(mapStateToProps, mapDispatchToProps)(AccountSettings);
