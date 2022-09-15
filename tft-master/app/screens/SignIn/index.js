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
import {NavigationEvents} from 'react-navigation';

const IOS = Platform.OS === 'ios';
class SignIn extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isWithEmail: false,
      mobileNo: __DEV__ ? '9428894094' : '',
      email: __DEV__ ? 'dhaval4241@gmail.com' : '',
      password: '',
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
    const {
      filter: {allFilters},
    } = this.props;
    const allCountries =
      allFilters && allFilters.allCountries ? allFilters.allCountries : [];
    this.setState({countries: allCountries});
  }

  isValidEmail = text => {
    const emailRegExp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    return emailRegExp.test(text);
  };

  // onSignIn = async user => {
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

  async onLogin() {
    const {
      email,
      password,
      mobileNo,
      isWithEmail,
      code,
      selectedValue,
    } = this.state;
    let isValid = false;
    let data = {};
    if (isWithEmail) {
      if (email.trim() === '') {
        this.setState({loading: false}, () => {
          CAlert(translate('Enter_Your_Email'), translate('alert'));
        });
      } else if (!this.isValidEmail(email)) {
        this.setState({loading: false}, () => {
          CAlert(translate('valid_email'), translate('alert'));
        });
      } else if (password.trim() === '') {
        this.setState({loading: false}, () => {
          CAlert(translate('Password_8_Characters'), translate('alert'));
        });
      } else if (password.length < 8) {
        this.setState({loading: false}, () => {
          CAlert(translate('Password_8_Characters'), translate('alert'));
        });
      } else {
        isValid = true;
        data = {email, password};
        this.loginAPICall();
      }
    } else {
      if (mobileNo.trim() === '') {
        this.setState({loading: false}, () => {
          CAlert(translate('enter_phone'), translate('alert'));
        });
      } else if (password.trim() === '') {
        this.setState({loading: false}, () => {
          CAlert(translate('Password_8_Characters'), translate('alert'));
        });
      } else {
        this.loginAPICall();
        // try {
        //   const response = await handleSendCode(selectedValue, mobileNo, code);
        //   console.log('SignIn -> onLogin -> response', response);
        //   this.setState({
        //     modalVisible: response.modalVisible,
        //     confirmResult: response.confirmResult,
        //     otpCode: '',
        //   });
        // } catch (error) {
        //   console.log('SignIn -> onLogin -> error', error);
        //   this.setState({loading: false});
        // }
      }
    }
    if (isValid) {
      this.setState({
        loading: true,
      });
    }
  }

  loginAPICall = uId => {
    const {
      navigation,
      auth,
      AuthActions: {setUserData},
    } = this.props;
    // let isValid = false;
    const {
      isWithEmail,
      mobileNo,
      selectedValue,
      email,
      password,
      code,
    } = this.state;

    let data = {};
    if (isWithEmail) {
      data.email = email;
      data.password = password;
    } else {
      const pCode = selectedValue ? selectedValue.label : '';
      const val = `+${pCode.split('+').pop()}`;
      data.countryCode = val;
      data.mobile = mobileNo;
      data.password = password;
    }


    if (auth.isConnected) {
      const url = isWithEmail
        ? BaseSetting.endpoints.loginEmail
        : BaseSetting.endpoints.loginMobile;
      this.setState({loading: true}, () => {
        getApiData(url, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (_.isBoolean(result.status) && result.status === true) {
                if (_.isObject(result.data)) {
                  this.setState({loading: false}, () => {
                    let userData = result.data;
                    userData.isGuest = false;
                    setUserData(userData);
                    setTimeout(() => {
                      navigation.navigate('Main');
                    }, 500);
                  });
                }
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
      };
      this.setState({loading: true}, () => {
        getApiData(url, 'post', data)
          .then(result => {
            if (_.isObject(result) && result.status) {
              console.log('checkPhoneNo -> result', result);
              this.setState({loading: true}, async () => {
                const response = await handleSendCode(
                  selectedValue,
                  mobileNo,
                  code,
                );
                console.log('SignIn -> onLogin -> response', response);
                this.setState({
                  loading: false,
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

  showActionSheet = () => {
    let options = [];
    countryCode.map(item => {
      options.push(item.label);
    });

    console.log('Array Label', options);

    ActionSheetIOS.showActionSheetWithOptions(
      {
        options: options,
      },
      buttonIndex => {
        this.setState({
          label: options[buttonIndex],
          code: countryCode[buttonIndex].value,
        });
      },
    );
  };

  verifyOtpCode = async code => {
    const {confirmResult} = this.state;
    this.setState({loading: true}, async () => {
      const response = await verifyCode(code, confirmResult);
      this.setState({modalVisible: response.modalVisible}, () => {
        const uId =
          response.user && !_.isEmpty(response.user) && response.user.uid
            ? response.user.uid
            : '';
        this.loginAPICall(uId);
      });
    });
    // console.log('render -> response', response);
  };

  setStatusbar() {
    /* Set Statusbar to match */
    setStatusbar('light');
  }

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
      <SafeAreaView
        style={[BaseStyle.safeAreaView, {backgroundColor: '#fff'}]}
        forceInset={{top: 'always'}}>
        <NavigationEvents
          onWillFocus={payload => {
            /* No need to update item on back - should be handled from CDU */
            // this.getItemsListAPICall();
            this.setStatusbar();
          }}
        />
        <Header
          title={translate('Login')}
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
        <ScrollView keyboardShouldPersistTaps>
          <View style={[styles.contain, {marginTop: 65}]}>
            {isWithEmail ? (
              <>
                <View style={styles.contentTitle}>
                  <Text headline2 semibold>
                    {translate('Email')}
                  </Text>
                </View>
                <TextInput
                  style={[BaseStyle.textInput]}
                  onSubmitEditing={() => {
                    this.pass.focus();
                  }}
                  onChangeText={text => this.setState({email: text})}
                  autoCorrect={false}
                  placeholder={translate('Email')}
                  placeholderTextColor={
                    this.state.success.id
                      ? BaseColor.grayColor
                      : BaseColor.primaryColor
                  }
                  value={email}
                  selectionColor={BaseColor.primaryColor}
                />
              </>
            ) : (
              <>
                <View style={styles.contentTitle}>
                  <Text headline2 semibold>
                    {translate('Phone')}
                  </Text>
                </View>
                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    // paddingHorizontal: Platform.OS === 'ios' ? 60 : 58,
                    width: '100%',
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
                      this.pass.focus();
                    }}
                    // onSubmitEditing={() => {
                    //   this.setState({loading: true}, () => {
                    //     this.onLogin();
                    //   });
                    // }}
                    blurOnSubmit={false}
                    returnKeyType="next"
                    style={[
                      BaseStyle.textInput,
                      {
                        marginTop: 10,
                        marginLeft: 3,
                        flex: 1,
                      },
                    ]}
                    onFocus={() => {
                      this.setState({
                        success: {
                          ...this.state.success,
                          id: true,
                        },
                      });
                    }}
                    onChangeText={text => this.setState({mobileNo: text})}
                    autoCorrect={false}
                    keyboardType="phone-pad"
                    placeholder={translate('Phone')}
                    placeholderTextColor={
                      this.state.success.id
                        ? BaseColor.grayColor
                        : BaseColor.primaryColor
                    }
                    value={mobileNo}
                    selectionColor={BaseColor.primaryColor}
                  />
                </View>
              </>
            )}

            {/* {isWithEmail ? ( */}
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Password')}
              </Text>
            </View>
            <TextInput
              style={[BaseStyle.textInput, {marginTop: 10}]}
              onChangeText={text => this.setState({password: text})}
              ref={o => {
                this.pass = o;
              }}
              onFocus={() => {
                this.setState({
                  success: {
                    ...this.state.success,
                    password: true,
                  },
                });
              }}
              onSubmitEditing={() => {
                this.onLogin();
              }}
              autoCorrect={false}
              placeholder={translate('Password')}
              secureTextEntry={true}
              placeholderTextColor={
                this.state.success.password
                  ? BaseColor.grayColor
                  : BaseColor.primaryColor
              }
              value={this.state.password}
              selectionColor={BaseColor.primaryColor}
            />
            {/* ) : null} */}
            <View style={{width: '100%'}}>
              <Button
                full
                loading={this.state.loading}
                style={{marginTop: 20}}
                onPress={() => {
                  this.setState({loading: true}, () => {
                    this.onLogin();
                  });
                }}>
                {translate('Login')}
              </Button>
            </View>
            <View
              style={{
                width: '100%',
                flexDirection: 'row',
                justifyContent: 'space-between',
                marginVertical: 15,
              }}>
              {/* {isWithEmail ? ( */}
              <TouchableOpacity
                onPress={() => navigation.navigate('ResetPassword')}>
                <Text body2 grayColor>
                  {translate('Forgot_Password')}
                </Text>
              </TouchableOpacity>
              {/* ) : (
                <View />
              )} */}
              <TouchableOpacity
                onPress={() => {
                  this.setState({
                    isWithEmail: !isWithEmail,
                    email: '',
                    password: '',
                  });
                }}>
                <Text body2 grayColor>
                  {isWithEmail
                    ? translate('Login_By_Phone')
                    : translate('Login_By_Email')}
                </Text>
              </TouchableOpacity>
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
                  <TouchableOpacity
                    style={styles.buttonStyle}
                    onPress={() => {
                      this.setState({loading: true}, () => {
                        this.verifyOtpCode(this.state.otpCode);
                      });
                    }}
                    activeOpacity={0.7}>
                    <Text style={styles.TextStyle}>{translate('Verify')}</Text>
                  </TouchableOpacity>

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

export default connect(mapStateToProps, mapDispatchToProps)(SignIn);
