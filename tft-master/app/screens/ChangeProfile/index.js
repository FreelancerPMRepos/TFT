import React, {Component} from 'react';
import {View, ScrollView, TextInput, TouchableOpacity} from 'react-native';
import {
  Image,
  Header,
  SafeAreaView,
  Icon,
  Text,
  Button,
  Tag,
} from '@components';
import styles from './styles';
import DateTimePickerModal from 'react-native-modal-datetime-picker';
import Modal from 'react-native-modal';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {BaseStyle, BaseColor, Images, BaseSetting} from '@config';
import {getApiData} from '../../utils/apiHelper';
import PropTypes from 'prop-types';
import FilterActions from '../../redux/reducers/filter/actions';
import CAlert from '../../components/CAlert';
import {translate} from '../../lang/Translate';
import moment from 'moment';
import {setStatusbar} from '@config';

class ChangeProfile extends Component {
  constructor(props) {
    super(props);
    const {userData} = this.props.auth;
    this.state = {
      firstName: userData.first_name,
      lastName: userData.last_name,
      gender: userData.gender === 'M' || userData.gender === '' ? true : false,
      dob:
        userData.dob === '' || userData.dob == null
          ? 'MM/DD/YYYY'
          : moment(userData.dob).format('MM/DD/YYYY'),
      email: userData.email,
      loading: false,
      datePickerVisible: false,
    };
  }

  componentDidMount() {
    setStatusbar('light');
  }

  handleBirthDate = date => {
    console.log('A date has been picked: ', moment(date).format('MM/DD/YYYY'));
    this.setState({
      dob: moment(date).format('MM/DD/YYYY'),
      datePickerVisible: false,
    });
  };

  onSetGender(gender) {
    console.log('SELECTED GENDER ==>', gender ? 'male' : 'female');
    this.setState({
      gender,
    });
  }

  validate() {
    const {firstName, lastName, dob, email} = this.state;
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (firstName.trim().length <= 0) {
      CAlert(translate('First_Name_Invalid'), translate('alert'));
    } else if (lastName.trim().length <= 0) {
      CAlert(translate('Last_Name_Invalid'), translate('alert'));
    } else if (dob === 'MM/DD/YYYY') {
      CAlert(translate('dob_Invalid'), translate('alert'));
    } else if (!re.test(String(email).toLowerCase())) {
      CAlert(translate('valid_email'), translate('alert'));
    } else {
      return true;
    }
  }

  async onConfirm() {
    const {
      auth,
      navigation,
      AuthActions: {setUserData},
    } = this.props;
    const {firstName, lastName, gender, dob, email} = this.state;
    const isValid = this.validate();
    if (isValid) {
      const data = {
        id: auth.userData.ID,
        firstName: firstName,
        lastName: lastName,
        gender: gender ? 'M' : 'F',
        dob: moment(dob, 'MM/DD/YYYY').format('YYYY-MM-DD'),
        email: email,
        apiVersion: 2,
      };
      if (auth.isConnected) {
        console.log(data);
        await getApiData(BaseSetting.endpoints.changeProfile, 'post', data)
          .then(result => {
            if (result && result.status && result.data) {
              const store = {
                ...result.data,
                isGuest: false,
              };
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
    } else {
      this.setState(
        {
          loading: false,
        },
        () => {
          console.log('Invlaid Input details by user!');
        },
      );
    }
  }

  render() {
    const {navigation, auth} = this.props;
    const {gender} = this.state;
    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <Header
          title={translate('Change_Profile')}
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
          onPressRight={() => {}}
        />
        <ScrollView>
          <View style={styles.contain}>
            {/* <View>
              <Image source={this.state.image} style={styles.thumb} />
            </View> */}
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('First_Name')}
              </Text>
            </View>
            <TextInput
              style={BaseStyle.textInput}
              onChangeText={text => this.setState({firstName: text})}
              autoCorrect={false}
              placeholder={translate('First_Name')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.firstName}
              selectionColor={BaseColor.primaryColor}
            />
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Last_Name')}
              </Text>
            </View>
            <TextInput
              style={BaseStyle.textInput}
              onChangeText={text => this.setState({lastName: text})}
              autoCorrect={false}
              placeholder={translate('Last_Name')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.lastName}
              selectionColor={BaseColor.primaryColor}
            />
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Gender')}
              </Text>
            </View>
            <View style={styles.gender}>
              <Tag
                outline={!gender}
                primary={gender}
                onPress={() => this.onSetGender(true)}
                style={{marginRight: 20}}>
                {translate('Male')}
              </Tag>
              <Tag
                outline={gender}
                primary={!gender}
                onPress={() => this.onSetGender(false)}>
                {translate('Female')}
              </Tag>
            </View>
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Date_Of_Birth')}
              </Text>
            </View>
            <View style={styles.contentQuest}>
              <TouchableOpacity
                style={styles.field}
                onPress={() => this.setState({datePickerVisible: true})}>
                <View style={{flexDirection: 'row'}}>
                  <Text body1 semibold>
                    {this.state.dob}
                  </Text>
                </View>
              </TouchableOpacity>
            </View>
            <DateTimePickerModal
              isVisible={this.state.datePickerVisible}
              mode="date"
              onConfirm={date => this.handleBirthDate(date)}
              onCancel={() => this.setState({datePickerVisible: false})}
              maximumDate={new Date()}
              minimumDate={new Date(1900, 1, 1)}
            />
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Email')}
              </Text>
            </View>
            <TextInput
              style={BaseStyle.textInput}
              onChangeText={text => this.setState({email: text})}
              autoCorrect={false}
              placeholder={translate('Email')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.email}
            />
          </View>
        </ScrollView>
        <View style={{padding: 20}}>
          <Button
            loading={this.state.loading}
            full
            onPress={() => {
              this.setState({loading: true}, () => this.onConfirm());
            }}>
            {translate('Confirm')}
          </Button>
        </View>
      </SafeAreaView>
    );
  }
}

ChangeProfile.defaultProps = {
  auth: {},
};

ChangeProfile.propTypes = {
  auth: PropTypes.objectOf(PropTypes.any),
};

const mapStateToProps = state => {
  return {
    auth: state.auth,
  };
};

const mapDispatchToProps = dispatch => {
  return {
    AuthActions: bindActionCreators(AuthActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(ChangeProfile);
