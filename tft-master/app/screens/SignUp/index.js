/* eslint-disable radix */
/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  View,
  ScrollView,
  TextInput,
  Platform,
  ActionSheetIOS,
  TouchableOpacity,
  Modal,
  KeyboardAvoidingView,
  Dimensions,
} from 'react-native';
import {connect} from 'react-redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {bindActionCreators} from 'redux';
import {BaseStyle, BaseColor, setStatusbar} from '@config';
import {Header, SafeAreaView, Icon, Button, Text} from '@components';
import styles from './styles';
import {getApiData} from '../../utils/apiHelper';
import CAlert from '../../components/CAlert';
import CPicker from '../../components/CPicker';
import countryCode from '../../config/county';
import {BaseSetting} from '../../config/setting';
import _ from 'lodash';
import {translate} from '../../lang/Translate';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import DropDown from '../../components/DropDown';
// import firebase from '@react-native-firebase/auth';
import firebase from '@react-native-firebase/app';
import '@react-native-firebase/auth';
import OTPInputView from '@twotalltotems/react-native-otp-input';
import {handleSendCode} from 'app/utils/CommonFunction';
import {NavigationEvents} from 'react-navigation';
import CLoader from 'app/components/CLoader';
import Toast from 'app/components/Toast';

const IOS = Platform.OS === 'ios';
class SignUp extends Component {
  constructor(props) {
    super(props);
    this.state = {
      firstname: __DEV__ ? 'Krunal' : '',
      lastname: __DEV__ ? 'Panchal' : '',
      phone: __DEV__ ? '9033578483' : '',
      email: __DEV__ ? 'krunalp1993@gmail.com' : '',
      password: __DEV__ ? '12345678' : '',
      code: '+973',
      label: 'BH +973',
      selectedValue: {key: 1, label: 'BH +973', value: '+973'},
      loading: false,
      success: {
        firstname: true,
        lastname: true,
        phone: true,
        email: true,
        password: true,
      },
      countries: [],
      modalVisible: false,
      otpCode: '',
      confirmResult: null,
    };
  }

  componentDidMount() {
    console.log('Props==', this.props.filter);
    const {
      filter: {allFilters},
    } = this.props;
    const allCountries =
      allFilters && allFilters.allCountries ? allFilters.allCountries : [];
    this.setState({countries: allCountries});
  }

  /* Show message on Toast */
  showToast = message => {
    if (this.refs.notifyToast) {
      this.refs.notifyToast.show(message, 2000);
    }
  };

  onSignUp = () => {
    console.log('SignUp called');
    const {firstname, lastname, email, password, phone, success} = this.state;
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (
      firstname === '' ||
      lastname === '' ||
      phone === '' ||
      email === '' ||
      password === ''
    ) {
      this.setState({
        success: {
          ...success,
          firstname: firstname !== '' ? true : false,
          lastname: lastname !== '' ? true : false,
          phone: phone !== '' ? true : false,
          email: email !== '' ? true : false,
          password: password !== '' ? true : false,
        },
      });
    }
    if (firstname.trim().length <= 0) {
      CAlert(translate('First_Name_Invalid'), translate('alert'));
    } else if (lastname.trim().length <= 0) {
      CAlert(translate('Last_Name_Invalid'), translate('alert'));
    } else if (phone.trim().length <= 0) {
      CAlert(translate('Phone_Invalid'), translate('alert'));
    } else if (phone.trim().length > 10) {
      // CAlert('Mobile number must ', translate('alert'));
      CAlert(translate('Phone_Invalid'), translate('alert'));
    } else if (!re.test(String(email).toLowerCase())) {
      CAlert(translate('valid_email'), translate('alert'));
    } else if (password.trim() === '') {
      CAlert(translate('Password_8_Characters'), translate('alert'));
    } else if (password.trim().length < 8) {
      CAlert(translate('Password_8_Characters'), translate('alert'));
    } else {
      this.setState(
        {
          loading: true,
        },
        () => {
          setTimeout(() => {
            this.setState(
              {
                loading: true,
              },
              () => {
                this.checkPhoneNo();
                // this.handleSendCode();
              },
            );
          }, 500);
        },
      );
    }
  };

  async checkPhoneNo() {
    const {code, phone, selectedValue} = this.state;
    const {navigation, auth} = this.props;

    if (auth.isConnected) {
      const url = BaseSetting.endpoints.checkPhoneExist;
      const pCode = selectedValue ? selectedValue.label : '';
      const val = pCode.split('+').pop();
      const data = {
        countryCode: `+${val}`,
        mobile: phone,
        register: true,
      };
      this.setState({loading: true}, () => {
        getApiData(url, 'post', data)
          .then(result => {
            if (_.isObject(result) && result.status) {
              console.log('checkPhoneNo -> result', result);
              this.setState({loading: false}, async () => {
                let response;
                try {
                  response = await this.handleSendCode(selectedValue, phone);
                  console.log('SignUp -> checkPhoneNo -> response', response);
                } catch (error) {
                  console.log('SignUp -> checkPhoneNo -> error', error);
                }
                console.log('SignIn -> onLogin -> response', response);
                this.setState({
                  modalVisible: true,
                  confirmResult: response,
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
            this.setState({
              loading: false,
            });
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

  validatePhoneNumber = () => {
    var regexp = /^\+[0-9]?()[0-9](\s|\S)(\d[0-9]{8,16})$/;
    return regexp.test(this.state.phone);
  };

  handleSendCode = async () => {
    const _this = this;
    const {selectedValue, code} = this.state;
    const pCode = selectedValue ? selectedValue.label : '';
    const val = `+${pCode.split('+').pop()} ${this.state.phone}`;
    console.log('handleSendCode -> val', val);
    try {
      return await firebase
        .auth()
        .verifyPhoneNumber(val, 120)
        .on(
          'state_changed',
          async phoneAuthSnapshot => {
            console.log('phoneAuthSnapshot====>?');
            console.log(phoneAuthSnapshot);
            // How you handle these state events is entirely up to your ui flow and whether
            // you need to support both ios and android. In short: not all of them need to
            // be handled - it's entirely up to you, your ui and supported platforms.

            // E.g you could handle android specific events only here, and let the rest fall back
            // to the optionalErrorCb or optionalCompleteCb functions
            switch (phoneAuthSnapshot.state) {
              // ------------------------
              //  IOS AND ANDROID EVENTS
              // ------------------------
              case firebase.auth.PhoneAuthState.CODE_SENT: // or 'sent'
                console.log('code sent');
                firebase
                  .auth()
                  .signInWithPhoneNumber(val.trim())
                  .then(confirmResult => {
                    _this.setState(
                      {
                        modalVisible: true,
                        confirmResult,
                        otpCode: '',
                        loading: false,
                      },
                      () => {
                        console.log(
                          'TCL: SignUp -> handleSendCode -> confirmResult',
                          confirmResult,
                          _this.state.modalVisible,
                        );
                      },
                    );
                  })
                  .catch(error => {
                    CAlert(
                      error.message || translate('went_wrong'),
                      translate('alert'),
                    );
                  });

                // on ios this is the final phone auth state event you'd receive
                // so you'd then ask for user input of the code and build a credential from it
                // as demonstrated in the `signInWithPhoneNumber` example above
                break;
              case firebase.auth.PhoneAuthState.ERROR: // or 'error'
                console.log('verification error=======>?');
                console.log(phoneAuthSnapshot.error);
                const msg =
                  phoneAuthSnapshot.error && phoneAuthSnapshot.error.message
                    ? phoneAuthSnapshot.error.message
                    : translate('went_wrong');
                CAlert(msg, translate('alert'));
                break;

              // ---------------------
              // ANDROID ONLY EVENTS
              // ---------------------
              case firebase.auth.PhoneAuthState.AUTO_VERIFY_TIMEOUT: // or 'timeout'
                console.log('auto verify on android timed out');
                const msg1 =
                  phoneAuthSnapshot.error && phoneAuthSnapshot.error.message
                    ? phoneAuthSnapshot.error.message
                    : translate('went_wrong');
                CAlert(msg1, translate('alert'));
                // proceed with your manual code input flow, same as you would do in
                // CODE_SENT if you were on IOS
                // this.gotoScreen('Verification', {
                //   user: data,
                //   verificationId: phoneAuthSnapshot,
                //   type: 'signup',
                // });
                break;
              case firebase.auth.PhoneAuthState.AUTO_VERIFIED: // or 'verified'
                // auto verified means the code has also been automatically confirmed as correct/received
                // phoneAuthSnapshot.code will contain the auto verified sms code - no need to ask the user for input.
                console.log('auto verified on android');
                console.log(phoneAuthSnapshot);
                const {verificationId, code} = phoneAuthSnapshot;


                let user = firebase.auth().currentUser;
                console.log('Auto verify ===>', user);
                if (!user) {
                  const credential = firebase.auth.PhoneAuthProvider.credential(
                    verificationId,
                    code,
                  );
                  const userCredential = await firebase
                    .auth()
                    .signInWithCredential(credential);
                  console.log('Credentials ==> ', credential, userCredential);
                  user = userCredential.user;
                }

                if (phoneAuthSnapshot.state === 'verified') {
                  if (user) {
                    const uId =
                      user && !_.isEmpty(user) && user.uid ? user.uid : '';
                    console.log('uid==>', uId);
                    console.log('user.uid==>', user.uid);
                    this.setState(
                      {
                        loading: true,
                        otpCode: code ? code : '******',
                        userId: uId,
                      },
                      () => {
                        this.showToast(
                          'Auto verification of Phone successful, Signing up your account now.',
                        );
                        this.RegisterAPICall(uId);
                      },
                    );
                  } else {
                    this.showToast(
                      'Auto verification of Phone successful, but failed to retrieve the user!',
                    );
                  }
                } else {
                  this.autoVerified = true;
                  this.showToast(
                    'Auto verification of Phone successful, but failed to register!',
                  );
                }
                break;
            }
          },
          error => {
            const errMsg =
              error && error.message ? error.message : translate('went_wrong');
            CAlert(errMsg, translate('alert'));
            // optionalErrorCb would be same logic as the ERROR case above,  if you've already handed
            // the ERROR case in the above observer then there's no need to handle it here
            console.log(error);
            // verificationId is attached to error if required
            console.log(error.verificationId);
          },
          phoneAuthSnapshot => {
            // optionalCompleteCb would be same logic as the AUTO_VERIFIED/CODE_SENT switch cases above
            // depending on the platform. If you've already handled those cases in the observer then
            // there's absolutely no need to handle it here.

            // Platform specific logic:
            // - if this is on IOS then phoneAuthSnapshot.code will always be null
            // - if ANDROID auto verified the sms code then phoneAuthSnapshot.code will contain the verified sms code
            //   and there'd be no need to ask for user input of the code - proceed to credential creating logic
            // - if ANDROID auto verify timed out then phoneAuthSnapshot.code would be null, just like ios, you'd
            //   continue with user input logic.
            console.log(phoneAuthSnapshot);
          },
        );
      // const response = await handleSendCode({}, val.trim());
      // if (response.verificationId) {
      //   this.setState({btnLoad: false}, () => {
      //     this.gotoScreen('Verification', {
      //       user: data,
      //       verificationId: response,
      //       type: 'signup',
      //     });
      //   });
      // } else {
      //   console.log('response=======>');
      //   console.log(response);
      //   this.setState({btnLoad: false}, () => {
      //     CAlert('Mobile number already registered', translate('alert'));
      //   });
      // }
    } catch (error) {
      console.log('TCL: SignUp -> handleSendCode -> error', error);
    }
  };

  RegisterAPICall = uId => {
    const {navigation, auth} = this.props;
    const {
      firstname,
      lastname,
      phone,
      email,
      password,
      code,
      selectedValue,
      countries,
    } = this.state;
    const pCode = selectedValue ? selectedValue.label : '';
    const val = pCode.split('+').pop();

    if (auth.isConnected) {
      const data = {
        firstName: firstname,
        lastName: lastname,
        countryCode: `+${val}`,
        mobile: parseInt(phone),
        email: email,
        password: password,
        uuid: uId,
      };
      console.log('SignUp -> data', data);
      getApiData(BaseSetting.endpoints.registration, 'post', data)
        .then(result => {
          if (_.isObject(result)) {
            if (_.isBoolean(result.status) && result.status === true) {
              if (_.isObject(result.data)) {
                CAlert(result.message, translate('Register'), () => {
                  navigation.navigate('SignIn', {register: true});
                });
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
          CAlert(translate('went_wrong'), translate('alert'));
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
  };

  showActionSheet = () => {
    const {countries} = this.state;
    console.log('TCL: SignUp -> showActionSheet -> countries', countries);
    let options = [];
    countries.map(item => {
      console.log('TCL: SignUp -> showActionSheet -> item', item);
      const countryLabel = `${item.country_code} ${item.phone_code}`;
      options.push(countryLabel);
    });
    // const cancelObj = {Id: '1', label: 'Cancel'};
    // options.push(cancelObj);

    console.log('Array Label', options);

    ActionSheetIOS.showActionSheetWithOptions(
      {
        options: options,
      },
      buttonIndex => {
        console.log(
          'TCL: SignUp -> showActionSheet -> buttonIndex',
          buttonIndex,
          countries[buttonIndex],
        );
        this.setState({
          label: options[buttonIndex],
          code: Number(countries[buttonIndex].Id),
        });
      },
    );
  };

  verifyCode = async code => {
    const {
      confirmResult: {verificationId},
    } = this.state;
    try {
      let user = firebase.auth().currentUser;

      if (!user) {
        const credential = firebase.auth.PhoneAuthProvider.credential(
          verificationId,
          code,
        );
        const userCredential = await firebase
          .auth()
          .signInWithCredential(credential);

        user = userCredential.user;
      }

      this.setState({loading: false}, () => {
        const uId = user && !_.isEmpty(user) && user.uid ? user.uid : '';
        console.log('uid==>', uId);
        console.log('user.uid==>', user.uid);
        this.setState(
          {
            loading: true,
            otpCode: code,
            userId: uId,
          },
          () => {
            this.RegisterAPICall(uId);
          },
        );
        console.log('user', user);
      });
    } catch (error) {
      this.setState({loading: false}, () => {
        CAlert('Invalid Otp');
        console.log('error', error);
      });
    }
  };

  setStatusbar() {
    /* Set Statusbar to match */
    setStatusbar('light');
  }

  renderOtpModal = () => {
    const {otpCode} = this.state;
    console.log('renderOtpModal -> otpCode', otpCode);
    return (
      <View
        style={{
          flex: IOS ? 1 : null,
          height: IOS ? 'auto' : Dimensions.get('window').height - 50,
          alignItems: 'center',
          justifyContent: 'center',
          position: 'relative',
          backgroundColor: IOS ? 'rgba(0,0,0,0.3)' : '#FFF',
        }}>
        <View style={styles.MainAlertView}>
          <Text style={styles.AlertTitle}>{translate('Verify')} OTP</Text>
          <View style={{width: '100%', height: 0.5, backgroundColor: '#000'}} />

          <OTPInputView
            style={{width: '80%', height: 100}}
            pinCount={6}
            code={otpCode ? otpCode : ''}
            onCodeChanged={code => {
              this.setState({otpCode: code});
            }}
            autoFocusOnLoad
            codeInputFieldStyle={styles.underlineStyleBase}
            codeInputHighlightStyle={styles.underlineStyleHighLighted}
            onCodeFilled={code => {
              this.setState({loading: true}, () => {
                this.verifyCode(code);
              });
            }}
          />

          <View style={{width: '100%', height: 0.5, backgroundColor: '#000'}} />
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
                      this.verifyCode(this.state.otpCode);
                    });
                  }
                }}
                activeOpacity={0.7}>
                <Text style={styles.TextStyle}>{translate('Verify')}</Text>
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
        {/* <View
            style={{
              alignItems: 'center',
              justifyContent: 'center',
              backgroundColor: 'black',
              height: Dimensions.get('window').height - 50,
              position: 'absolute',
              left: 0,
              top: 0,
              opacity: 0.5,
              width: Dimensions.get('window').width,
            }}
          /> */}
      </View>
    );
  };

  render() {
    const {navigation} = this.props;
    let {
      loading,
      firstname,
      lastname,
      phone,
      email,
      password,
      success,
      label,
      selectedValue,
      countries,
    } = this.state;
    console.log('Render Call');
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
          title={translate('Register')}
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
        <KeyboardAvoidingView
          enabled={IOS ? true : false}
          behavior="padding"
          style={{flex: 1}}>
          <ScrollView style={{flex: 1}} keyboardShouldPersistTaps>
            <View style={[styles.contain]}>
              <View style={styles.contentTitle}>
                <Text headline2 semibold>
                  {translate('First_Name')}
                </Text>
              </View>
              <TextInput
                {...this.props}
                ref={o => {
                  this.fname = o;
                }}
                onSubmitEditing={() => {
                  this.lname.focus();
                }}
                blurOnSubmit={false}
                returnKeyType="next"
                style={[BaseStyle.textInput, {marginTop: 10}]}
                onChangeText={text => this.setState({firstname: text})}
                autoCorrect={false}
                placeholder={translate('First_Name')}
                placeholderTextColor={
                  success.firstname
                    ? BaseColor.grayColor
                    : BaseColor.primaryColor
                }
                value={firstname}
              />
              <View style={styles.contentTitle}>
                <Text headline2 semibold>
                  {translate('Last_Name')}
                </Text>
              </View>
              <TextInput
                {...this.props}
                ref={o => {
                  this.lname = o;
                }}
                onSubmitEditing={() => {
                  this.phone.focus();
                }}
                blurOnSubmit={false}
                returnKeyType="next"
                style={[BaseStyle.textInput, {marginTop: 10}]}
                onChangeText={text => this.setState({lastname: text})}
                autoCorrect={false}
                placeholder={translate('Last_Name')}
                placeholderTextColor={
                  success.lastname
                    ? BaseColor.grayColor
                    : BaseColor.primaryColor
                }
                value={lastname}
              />
              <View style={styles.contentTitle}>
                <Text headline2 semibold>
                  {translate('Phone')}
                </Text>
              </View>
              <View
                style={{
                  width: '100%',
                  flexDirection: 'row',
                  justifyContent: 'space-between',
                  alignItems: 'center',
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
                    console.log('TCL: SignUp -> render -> value', value);
                    this.setState({selectedValue: value});
                  }}
                />
                <TextInput
                  {...this.props}
                  ref={o => {
                    this.phone = o;
                  }}
                  onSubmitEditing={() => {
                    this.email.focus();
                  }}
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
                  onChangeText={text => this.setState({phone: text})}
                  autoCorrect={false}
                  keyboardType="phone-pad"
                  placeholder={translate('Phone')}
                  placeholderTextColor={
                    success.phone ? BaseColor.grayColor : BaseColor.primaryColor
                  }
                  value={phone}
                />
              </View>
              <View style={styles.contentTitle}>
                <Text headline2 semibold>
                  {translate('Email')}
                </Text>
              </View>
              <TextInput
                {...this.props}
                ref={o => {
                  this.email = o;
                }}
                onSubmitEditing={() => {
                  this.pass.focus();
                }}
                blurOnSubmit={false}
                returnKeyType="next"
                style={[BaseStyle.textInput, {marginTop: 10}]}
                onChangeText={text => this.setState({email: text})}
                autoCorrect={false}
                placeholder={translate('Email')}
                keyboardType="email-address"
                placeholderTextColor={
                  success.email ? BaseColor.grayColor : BaseColor.primaryColor
                }
                value={email}
              />
              <View style={styles.contentTitle}>
                <Text headline2 semibold>
                  {translate('Password')}
                </Text>
              </View>
              <TextInput
                {...this.props}
                ref={o => {
                  this.pass = o;
                }}
                onSubmitEditing={() => {
                  this.onSignUp();
                }}
                blurOnSubmit={false}
                returnKeyType="go"
                secureTextEntry
                style={[BaseStyle.textInput, {marginTop: 10}]}
                onChangeText={text => this.setState({password: text})}
                autoCorrect={false}
                placeholder={translate('Password')}
                placeholderTextColor={
                  success.password
                    ? BaseColor.grayColor
                    : BaseColor.primaryColor
                }
                value={password}
              />
              <View style={{width: '100%'}}>
                <Button
                  full
                  style={{marginTop: 20}}
                  loading={loading}
                  onPress={() => this.onSignUp()}>
                  {translate('Register')}
                </Button>
              </View>
            </View>
          </ScrollView>
        </KeyboardAvoidingView>
        {IOS ? (
          <Modal
            transparent
            animationType="slide"
            visible={this.state.modalVisible}
            onRequestClose={() => {
              this.setState({modalVisible: false});
            }}>
            {this.renderOtpModal()}
          </Modal>
        ) : this.state.modalVisible ? (
          this.renderOtpModal()
        ) : null}

        <Toast
          ref="notifyToast"
          position="top"
          positionValue={100}
          fadeInDuration={750}
          fadeOutDuration={2000}
          opacity={0.8}
        />
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

export default connect(mapStateToProps, mapDispatchToProps)(SignUp);
