import React, {Component} from 'react';
import {View, ScrollView, TextInput, BackHandler} from 'react-native';
import _ from 'lodash';
import {Header, SafeAreaView, Icon, Text, Button} from '@components';
import styles from './styles';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {BaseStyle, BaseColor, BaseSetting} from '@config';
import {getApiData} from '../../utils/apiHelper';
import PropTypes from 'prop-types';
import CAlert from '../../components/CAlert';
import {translate} from '../../lang/Translate';
import {setStatusbar} from '@config';

class ChangePassword extends Component {
  constructor(props) {
    super(props);
    this.state = {
      currentPassword: '',
      newPassword: '',
      repassword: '',
      loading: false,
    };
  }

  componentDidMount() {
    setStatusbar('light');
    console.log('Props==>', this.props);
    const item = this.props.navigation.getParam('data');
    const fromResetPassword = this.props.navigation.getParam(
      'fromResetPassword',
    );
    console.log(
      'ChangePassword -> componentDidMount -> item',
      item,
      fromResetPassword,
    );
    BackHandler.addEventListener(
      'hardwareBackPress',
      this.handleBackButtonClick,
    );
  }

  componentWillUnmount() {
    BackHandler.removeEventListener(
      'hardwareBackPress',
      this.handleBackButtonClick,
    );
  }

  handleBackButtonClick = () => {
    const fromResetPassword = this.props.navigation.getParam(
      'fromResetPassword',
    );
    fromResetPassword
      ? this.props.navigation.navigate('Start')
      : this.props.navigation.goBack();
    return true;
  };

  validate() {
    const {currentPassword, newPassword, repassword} = this.state;
    const fromResetPassword = this.props.navigation.getParam(
      'fromResetPassword',
    );
    if (currentPassword.trim().length < 8 && !fromResetPassword) {
      this.setState({loading: false}, () =>
        CAlert('Please type current password with at lease 8 characters!'),
      );
    } else if (newPassword.trim().length < 8) {
      this.setState({loading: false}, () =>
        CAlert('Please type new password with at lease 8 characters!'),
      );
    } else if (repassword.trim().length < 8) {
      this.setState({loading: false}, () =>
        CAlert('Please type Re-type password!'),
      );
    } else if (newPassword !== repassword) {
      this.setState({loading: false}, () =>
        CAlert('Confirm Password does not match!'),
      );
    } else {
      if (fromResetPassword) {
        this.onReset();
        return;
      } else {
        this.changePassword();
        return true;
      }
    }
  }

  async changePassword() {
    const {auth, navigation} = this.props;
    const {currentPassword, newPassword, repassword} = this.state;

    const data = {
      id: auth.userData.ID,
      countryCode: auth.userData.country_code,
      mobile: auth.userData.mobile,
      oldPassword: currentPassword,
      password: newPassword,
    };
    console.log('Auth', data);
    if (auth.isConnected) {
      await getApiData(BaseSetting.endpoints.changePassword, 'post', data)
        .then(result => {
          if (result && result.status) {
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
                CAlert(result.message);
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
  }

  onReset(uId) {
    const {code, phone, selectedValue, newPassword} = this.state;
    const {navigation, auth} = this.props;
    let item = this.props.navigation.getParam('data');
    item.password = newPassword;
    console.log('ChangePassword -> onReset -> item', item);
    // return;
    if (auth.isConnected) {
      const url = BaseSetting.endpoints.recovery;

      getApiData(url, 'post', item)
        .then(result => {
          if (_.isObject(result)) {
            if (_.isBoolean(result.status) && result.status === true) {
              this.setState({loading: false}, () => {
                CAlert(
                  result.message,
                  translate('alert'),
                  () => {
                    navigation.navigate('Start');
                  },
                  translate('OK'),
                );
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
                CAlert(translate('went_wrong'), translate('alert'));
              },
            );
          }
        })
        .catch(err => {
          console.log(`Error: ${err}`);
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
  }

  render() {
    const {navigation} = this.props;
    const fromResetPassword = this.props.navigation.getParam(
      'fromResetPassword',
    );
    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <Header
          title={
            fromResetPassword
              ? translate('Reset_Password')
              : translate('Change_Password')
          }
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
            fromResetPassword
              ? navigation.navigate('Start')
              : navigation.goBack();
          }}
        />
        <ScrollView>
          <View style={styles.contain}>
            {!fromResetPassword ? (
              <>
                <View style={styles.contentTitle}>
                  <Text headline2 semibold>
                    {translate('Current_Password')}
                  </Text>
                </View>
                <TextInput
                  contextMenuHidden={true}
                  style={BaseStyle.textInput}
                  onChangeText={text => this.setState({currentPassword: text})}
                  autoCorrect={false}
                  secureTextEntry={true}
                  placeholder={translate('Current_Password')}
                  placeholderTextColor={BaseColor.grayColor}
                  value={this.state.currentPassword}
                  selectionColor={BaseColor.primaryColor}
                />
              </>
            ) : null}
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('New_Password')}
              </Text>
            </View>
            <TextInput
              contextMenuHidden={true}
              style={BaseStyle.textInput}
              onChangeText={text => this.setState({newPassword: text})}
              autoCorrect={false}
              secureTextEntry={true}
              placeholder={translate('New_Password')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.newPassword}
              selectionColor={BaseColor.primaryColor}
            />
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Confirmation_Password')}
              </Text>
            </View>
            <TextInput
              style={BaseStyle.textInput}
              onChangeText={text => this.setState({repassword: text})}
              autoCorrect={false}
              secureTextEntry={true}
              placeholder={translate('Confirmation_Password')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.repassword}
              selectionColor={BaseColor.primaryColor}
            />
          </View>
        </ScrollView>
        <View style={{padding: 20}}>
          <Button
            loading={this.state.loading}
            full
            onPress={() => {
              this.setState(
                {
                  loading: true,
                },
                () => this.validate(),
              );
            }}>
            {fromResetPassword
              ? translate('Reset_Password')
              : translate('Confirm')}
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
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(ChangePassword);
