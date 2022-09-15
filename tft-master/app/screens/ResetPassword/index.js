/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  View,
  ScrollView,
  TouchableOpacity,
  TextInput,
  Platform,
  Modal,
  KeyboardAvoidingView,
  Dimensions,
} from 'react-native';
import {BaseStyle, BaseColor, setStatusbar} from '@config';
import {Header, SafeAreaView, Icon, Text, Button} from '@components';
import CPicker from '../../components/CPicker';
import countryCode from '../../config/county';
import {getApiData} from '../../utils/apiHelper';
import {BaseSetting} from '../../config/setting';
import _ from 'lodash';
import styles from './styles';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import {translate} from '../../lang/Translate';
import CAlert from '../../components/CAlert';
import AuthActions from '../../redux/reducers/auth/actions';
import OTPInputView from '@twotalltotems/react-native-otp-input';
import {connect} from 'react-redux';
import DropDown from '../../components/DropDown';
import {handleSendCode, verifyCode} from '../../utils/CommonFunction';
import {NavigationEvents} from 'react-navigation';
import CLoader from 'app/components/CLoader';
import {bindActionCreators} from 'redux';

const IOS = Platform.OS === 'ios';
class ResetPassword extends Component {
  constructor(props) {
    super(props);
    this.state = {
      phone: __DEV__ ? '9428894094' : '',
      loading: false,
      success: {
        phone: true,
      },
      code: '+966',
      label: 'SA +966',
      otpCode: '',
      country: countryCode,
      modalVisible: false,
      selectedValue: __DEV__
        ? {key: 1, label: 'IN +91', value: '+91'}
        : {key: 1, label: 'BH +973', value: '+973'},
      countries: [],
    };
  }

  componentDidMount() {
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

  validation = async () => {
    const {email, selectedValue} = this.state;
    let valid = true;
    if (!this.isValidEmail(email)) {
      CAlert(translate('valid_email'), translate('alert'));
      valid = false;
    }
    if (valid) {
      this.checkPhoneNo();
    }
  };

  verifyOtp = otp => {
    this.setState({modalVisible: false});
    const {
      navigation,
      auth,
      AuthActions: {setUserData},
    } = this.props;
    const {mobileNo, email} = this.state;
    if (auth.isConnected) {
      const data = {
        email,
        otp,
      };
      getApiData(BaseSetting.endpoints.verifyEmailOTP, 'post', data)
        .then(result => {
          this.setState({otpCode: ''});
          if (_.isObject(result)) {
            console.log('ResetPassword -> result', result);
            if (_.isBoolean(result.status) && result.status === true) {
              if (result.data) {
                // let userData = result.data;
                // userData.isGuest = false;
                // setUserData(userData);
                setTimeout(() => {
                  this.props.navigation.navigate('ChangePassword', {
                    data,
                    fromResetPassword: true,
                  });
                }, 200);
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

  verifyOtpCode = async code => {
    const {confirmResult, phone, selectedValue} = this.state;
    if (code.length >= 6) {
      try {
        const response = await verifyCode(code, confirmResult);
        console.log('render -> response', response);
        this.setState(
          {modalVisible: response.modalVisible, otpCode: '', loading: false},
          () => {
            const uId =
              response.user && !_.isEmpty(response.user) && response.user.uid
                ? response.user.uid
                : '';
            const pCode = selectedValue ? selectedValue.label : '';
            const val = pCode.split('+').pop();
            const data = {
              countryCode: `+${val}`,
              mobile: phone,
              uuid: uId,
            };

            this.props.navigation.navigate('ChangePassword', {
              data,
              fromResetPassword: true,
            });
          },
        );
      } catch (error) {
        console.log('ResetPassword -> error', error);
        this.setState({loading: false}, () => {
          CAlert('Invalid Otp');
        });
      }
    } else {
      this.setState({loading: false}, () => {
        CAlert('Invalid Otp');
      });
    }
  };

  async checkPhoneNo() {
    const {code, phone, email, selectedValue} = this.state;
    const {navigation, auth} = this.props;

    if (auth.isConnected) {
      const url = BaseSetting.endpoints.verifyEmail;
      const pCode = selectedValue ? selectedValue.label : '';
      const val = pCode.split('+').pop();
      const data = {
        email,
      };
      this.setState({loading: true}, () => {
        getApiData(url, 'post', data)
          .then(result => {
            if (_.isObject(result) && result.status) {
              console.log('checkPhoneNo -> result', result);
              this.setState({loading: false}, async () => {
                CAlert(
                  result.data,
                  translate('alert'),
                  () => {
                    this.setState({modalVisible: true, otpCode: ''});
                  },
                  () => {},
                );
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

  setStatusbar() {
    /* Set Statusbar to match */
    setStatusbar('light');
  }
  renderOtpModal = () => {
    const {otpCode} = this.state;
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
            code={otpCode}
            onCodeChanged={code => {
              this.setState({otpCode: code});
            }}
            autoFocusOnLoad
            codeInputFieldStyle={styles.underlineStyleBase}
            codeInputHighlightStyle={styles.underlineStyleHighLighted}
            onCodeFilled={code => {
              this.setState({loading: true}, () => {
                this.verifyOtp(code);
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
                      this.verifyOtp(this.state.otpCode);
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
    const {
      label,
      phone,
      modalVisible,
      otpCode,
      code,
      selectedValue,
      countries,
      email,
    } = this.state;
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
          title={translate('Reset_Password')}
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
          <ScrollView keyboardShouldPersistTaps={'handled'}>
            <View
              style={{
                alignItems: 'center',
                padding: 20,
                width: '100%',
                marginTop: 65,
              }}>
              <View style={styles.contentTitle}>
                <Text headline2 semibold>
                  {translate('Email')}
                </Text>
              </View>
              <TextInput
                style={[BaseStyle.textInput]}
                onSubmitEditing={() => {
                  this.validation();
                }}
                onChangeText={text => this.setState({email: text})}
                autoCorrect={false}
                placeholder={translate('Email')}
                placeholderTextColor={BaseColor.grayColor}
                value={email}
                selectionColor={BaseColor.primaryColor}
              />
              {/* <View style={styles.contentTitle}>
                <Text headline2 semibold>
                  {translate('Phone')}
                </Text>
              </View>
              <View
                style={{
                  flexDirection: 'row',
                  paddingHorizontal: IOS ? 58 : 55,
                  justifyContent: 'flex-start',
                  alignItems: 'center',
                  marginRight: 10,
                }}>
                <DropDown
                  placeholder="PlaceholderText"
                  labelText="" // This is for label in left side
                  data={countries}
                  value={selectedValue}
                  rightIcon="menu-down"
                  iconSize={30}
                  iconStyle={{color: '#000'}}
                  onChange={value => {
                    this.setState({selectedValue: value});
                  }}
                />
                <TextInput
                  style={[BaseStyle.textInput, {marginTop: 10}]}
                  onChangeText={text => this.setState({phone: text})}
                  onFocus={() => {
                    this.setState({
                      success: {
                        ...this.state.success,
                        id: true,
                      },
                    });
                  }}
                  onSubmitEditing={() => {
                    this.validation();
                  }}
                  autoCorrect={false}
                  placeholder={translate('Phone')}
                  keyboardType="phone-pad"
                  placeholderTextColor={
                    this.state.success.phone
                      ? BaseColor.grayColor
                      : BaseColor.primaryColor
                  }
                  value={phone}
                  selectionColor={BaseColor.primaryColor}
                />
              </View> */}

              <View style={{width: '100%'}}>
                <Button
                  full
                  style={{marginTop: 20}}
                  onPress={() => {
                    this.validation();
                  }}
                  loading={this.state.loading}>
                  {translate('Reset_Password')}
                </Button>
              </View>
            </View>
          </ScrollView>
        </KeyboardAvoidingView>
        {IOS ? (
          <Modal
            transparent
            animationType="slide"
            visible={modalVisible}
            onRequestClose={() => {
              this.setState({modalVisible: false});
            }}>
            {this.renderOtpModal()}
          </Modal>
        ) : modalVisible ? (
          this.renderOtpModal()
        ) : null}
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

export default connect(mapStateToProps, mapDispatchToProps)(ResetPassword);
