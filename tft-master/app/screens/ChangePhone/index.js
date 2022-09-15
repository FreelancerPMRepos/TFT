/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {bindActionCreators} from 'redux';
import {
  View,
  ScrollView,
  TouchableOpacity,
  TextInput,
  Platform,
  ActionSheetIOS,
  TouchableHighlight,
  Modal,
  Alert,
} from 'react-native';
import {BaseStyle, BaseColor, setStatusbar} from '@config';
import {Header, SafeAreaView, Icon, Text, Button} from '@components';
import OTPInputView from '@twotalltotems/react-native-otp-input';
import styles from './styles';
import {getApiData} from '../../utils/apiHelper';
import CAlert from '../../components/CAlert';
import {BaseSetting} from '../../config/setting';
import _ from 'lodash';
import countryCode from '../../config/county';
import CPicker from '../../components/CPicker';
import {translate} from '../../lang/Translate';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import DropDown from '../../components/DropDown';
import firebase from '@react-native-firebase/app';
import {handleSendCode, verifyCode} from '../../utils/CommonFunction';
import CLoader from 'app/components/CLoader';

const IOS = Platform.OS === 'ios';
class ChangePhone extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isWithEmail: false,
      mobileNo: __DEV__ ? '9428894094' : '',
      email: __DEV__ ? 'dhaval4241@gmail.com' : '',
      password: __DEV__ ? '12345678' : '',
      loading: false,
      code: '+966',
      label: 'SA +966',
      success: {
        id: true,
        password: true,
      },
      country: countryCode,
      modalVisible: false,
      otpCode: '',
      countries: [],
      selectedValue: {key: 1, label: 'BH +973', value: '+973'},
    };
  }

  componentDidMount() {
    // this.onProductView();
    console.log('Props==', this.props.filter);
    setStatusbar('light');
    const {
      filter: {allFilters},
    } = this.props;
    const allCountries =
      allFilters && allFilters.allCountries ? allFilters.allCountries : [];
    this.setState({countries: allCountries});
  }
  // onChangePhone = async user => {
  //   await Promise.all([
  //     analytics().setUserId(user.ID),
  //     analytics().setUserProperty('language', user.language),
  //   ]);
  // };

  // onProductView = async () => {
  //   await analytics().logEvent('product_view', {
  //     id: '123456789',
  //     color: 'red',
  //     via: 'ProductCatalog',
  //   });
  // };

  async checkPhoneNo() {
    const {code, mobileNo, selectedValue} = this.state;
    const {navigation, auth} = this.props;

    if (auth.isConnected) {
      const url = BaseSetting.endpoints.checkPhoneExist;
      const pCode = selectedValue ? selectedValue.label : '';
      const val = pCode.split('+').pop();
      const data = {
        countryCode: `+${val}`,
        mobile: mobileNo,
        register: true,
      };
      this.setState({loading: true}, () => {
        getApiData(url, 'post', data)
          .then(result => {
            if (_.isObject(result) && result.status) {
              console.log('checkPhoneNo -> result', result);
              this.setState({loading: false}, async () => {
                const response = await handleSendCode(selectedValue, mobileNo);
                console.log('SignIn -> onLogin -> response', response);
                this.setState({
                  modalVisible: response.modalVisible,
                  confirmResult: response.confirmResult,
                  otpCode: '',
                });
              });
            } else {
              this.setState(
                {
                  loading: false,
                },
                () => {
                  CAlert(result.message, translate('alert'));
                },
              );
            }
          })
          .catch(err => {
            console.log(`Error: ${err}`);
          });
      });
    } else {
      this.setState(
        {
          loading: false,
        },
        () => {
          CAlert(translate('Internet'), translate('alert'));
        },
      );
    }
  }

  async onLogin() {
    const {mobileNo, code, selectedValue} = this.state;
    if (mobileNo.trim() === '') {
      CAlert(translate('enter_phone'), translate('alert'));
    } else {
      this.checkPhoneNo();
      // const response = await handleSendCode(selectedValue, mobileNo, code);
      // console.log('ChangePhone -> onLogin -> response', response);
      // this.setState({
      //   modalVisible: response.modalVisible,
      //   confirmResult: response.confirmResult,
      //   otpCode: '',
      // });
    }
  }

  ChangePhone = uId => {
    const {
      navigation,
      auth,
      AuthActions: {setUserData},
    } = this.props;
    const {mobileNo, selectedValue} = this.state;

    const {userData} = this.props.auth;

    const pCode = selectedValue ? selectedValue.label : '';
    const val = pCode.split('+').pop();
    let data = {
      countryCode: `+${val}`,
      mobile: mobileNo,
      uuid: uId,
      id: userData && userData.ID ? userData.ID : '',
    };
    console.log('ChangePhone -> data', data);

    if (auth.isConnected) {
      const url = BaseSetting.endpoints.changeNumber;
      this.setState({loading: true}, async () => {
        await getApiData(url, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (result.data && _.isBoolean(result.status) && result.status) {
                const store = {
                  ...result.data,
                  isGuest: false,
                };
                console.log('userdata==', store);
                setUserData(store);
                this.setState(
                  {
                    loading: false,
                  },
                  () => {
                    CAlert(
                      result.message,
                      translate('alert'),
                      () => {
                        navigation.goBack();
                      },
                      translate('OK'),
                    );
                  },
                );
              } else {
                this.setState(
                  {
                    loading: false,
                  },
                  () => {
                    CAlert(
                      _.isString(result.message)
                        ? result.message
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
          });
      });
      // eslint-disable-next-line no-unreachable
    } else {
      this.setState(
        {
          loading: false,
        },
        () => {
          CAlert(translate('Internet'), translate('alert'));
        },
      );
    }
  };
  setModalVisible(visible) {
    this.setState({modalVisible: visible});
    // this.verifyOtp();
  }

  verifyOtpCode = async code => {
    const {confirmResult} = this.state;
    try {
      const response = await verifyCode(code, confirmResult);
      console.log('render -> response', response);
      this.setState(
        {modalVisible: response.modalVisible, loading: false},
        () => {
          const uId =
            response.user && !_.isEmpty(response.user) && response.user.uid
              ? response.user.uid
              : '';
          this.ChangePhone(uId);
        },
      );
    } catch (error) {
      this.setState({loading: false});
    }
  };

  render() {
    const {
      isWithEmail,
      email,
      mobileNo,
      label,
      modalVisible,
      otpCode,
      countries,
      selectedValue,
      confirmResult,
    } = this.state;
    // const response = handleSendCode(selectedValue, mobileNo);
    // console.log('render -> response', response);
    const {navigation} = this.props;

    let fromRegister = navigation.getParam('register', false);

    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <Header
          title={translate('Change_Number')}
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
            fromRegister ? navigation.popToTop() : navigation.goBack();
          }}
        />
        <ScrollView>
          <View style={styles.contain}>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                alignItems: 'center',
                paddingHorizontal: Platform.OS === 'ios' ? 60 : 58,
                marginTop: 65,
              }}>
              <DropDown
                containerStyle={styles.dropdownStyle} // change as requirement
                placeholder="PlaceholderText"
                labelText="" // This is for label in left side
                data={countries}
                rightIcon="menu-down"
                iconSize={30}
                iconStyle={{color: '#000'}}
                value={selectedValue}
                onChange={value => {
                  console.log('TCL: SignIn -> render -> value', value);
                  this.setState({selectedValue: value});
                }}
              />
              <TextInput
                {...this.props}
                ref={o => {
                  this.phone = o;
                }}
                onSubmitEditing={() => {
                  this.onLogin();
                }}
                blurOnSubmit={false}
                returnKeyType="next"
                style={[
                  BaseStyle.textInput,
                  {
                    marginTop: 10,
                    width: '100%',
                    marginRight: 10,
                  },
                ]}
                onChangeText={text => this.setState({mobileNo: text})}
                autoCorrect={false}
                keyboardType="phone-pad"
                placeholder={translate('Phone')}
                placeholderTextColor={BaseColor.grayColor}
                value={this.state.mobileNo}
                selectionColor={BaseColor.primaryColor}
              />
            </View>
            <View style={{width: '100%'}}>
              <Button
                full
                loading={this.state.loading}
                style={{marginTop: 20}}
                onPress={() => {
                  this.onLogin();
                }}>
                {translate('Confirm')}
              </Button>
            </View>
          </View>
        </ScrollView>
        <View
          style={{
            flex: 1,
            justifyContent: 'center',
            alignItems: 'center',
            margin: 20,
          }}>
          <Modal
            transparent
            animationType="slide"
            visible={modalVisible}
            onRequestClose={() => {
              this.setState({modalVisible: false});
            }}>
            <View
              style={{
                flex: 1,
                alignItems: 'center',
                justifyContent: 'center',
                backgroundColor: 'rgba(0,0,0,0.3)',
              }}>
              <View style={styles.MainAlertView}>
                <Text style={styles.AlertTitle}>{translate('Verify')} OTP</Text>
                <View
                  style={{width: '100%', height: 0.5, backgroundColor: '#000'}}
                />

                <OTPInputView
                  style={{width: '80%', height: 100}}
                  pinCount={6}
                  code={otpCode}
                  onCodeChanged={code => {
                    this.setState({otpCode: code});
                  }}
                  autoFocusOnLoad
                  codeInputFieldStyle={styles.underlineStyleBase}
                  codeInputHighlightStyle={styles.underlineStyleHighLighted}
                  onCodeFilled={code => {
                    this.setState({loading: true}, () => {
                      this.verifyOtpCode(code);
                    });
                  }}
                />

                <View
                  style={{width: '100%', height: 0.5, backgroundColor: '#000'}}
                />
                <View
                  style={{
                    flex: 1,
                    width: '100%',
                    flexDirection: 'row',
                    justifyContent: 'space-evenly',
                  }}>
                  {this.state.loading ? (
                    <View style={{width: 100}}>
                      <CLoader />
                    </View>
                  ) : (
                    <TouchableOpacity
                      style={styles.buttonStyle}
                      onPress={() => {
                        if (_.isEmpty(otpCode)) {
                          CAlert('Please enter valid otp');
                        } else {
                          this.setState({loading: true}, () => {
                            this.verifyOtpCode(this.state.otpCode);
                          });
                        }
                      }}
                      activeOpacity={0.7}>
                      <Text style={styles.TextStyle}>
                        {translate('Verify')}
                      </Text>
                    </TouchableOpacity>
                  )}

                  <View
                    style={{
                      width: 0.5,
                      backgroundColor: '#000',
                    }}
                  />

                  <TouchableOpacity
                    style={styles.buttonStyle}
                    onPress={() => {
                      this.setState({
                        modalVisible: false,
                        loading: false,
                        otpCode: '',
                      });
                    }}
                    activeOpacity={0.7}>
                    <Text style={styles.TextStyle}>{translate('Cancel')}</Text>
                  </TouchableOpacity>
                </View>
              </View>
            </View>
          </Modal>
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
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(ChangePhone);
