/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  View,
  ScrollView,
  TextInput,
  TouchableOpacity,
  Platform,
} from 'react-native';
import {BaseStyle, BaseColor, Images} from '@config';
import {Image, Header, SafeAreaView, Icon, Text, Button} from '@components';
import styles from './styles';
import {setStatusbar} from '../../config/statusbar';
import {BaseSetting} from '../../config/setting';
import Modal from 'react-native-modal';
import {connect} from 'react-redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {bindActionCreators} from 'redux';
import {getApiData} from '../../utils/apiHelper';
import {translate} from '../../lang/Translate';
import CAlert from '../../components/CAlert';
import CPicker from '../../components/CPicker';
import DropDown from '../../components/DropDown';

// Load sample data
const IOS = Platform.OS === 'ios';
class AddPool extends Component {
  constructor(props) {
    super(props);

    this.state = {
      poolName: '',
      phoneNumber: '',
      area: '',
      notes: '',
      loading: false,
      countries: [],
      code: '',
      selectedValue: {key: 1, label: 'BH +973', value: '+973'},
    };
  }

  componentDidMount() {
    // setStatusbar('light');
    console.log('Props==', this.props.filter);
    const {
      filter: {allFilters},
    } = this.props;
    const allCountries =
      allFilters && allFilters.allCountries ? allFilters.allCountries : [];
    this.setState({countries: allCountries});
  }

  validate() {
    const {poolName, phoneNumber, area, notes} = this.state;
    console.log('STATE', this.state);
    let isValid = true;
    if (poolName.trim().length === 0) {
      this.setState(
        {
          loading: false,
        },
        () => {
          CAlert('Pool name is required!');
          isValid = false;
        },
      );
      return false;
    } else if (phoneNumber.length < 5) {
      this.setState(
        {
          loading: false,
        },
        () => {
          CAlert('A valid phone number is required!');
          isValid = false;
        },
      );
      return false;
    } else if (area.trim().length === 0) {
      this.setState(
        {
          loading: false,
        },
        () => {
          CAlert('Area name is required!');
          isValid = false;
        },
      );
      return false;
    } else if (notes.trim().length === 0) {
      this.setState(
        {
          loading: false,
        },
        () => {
          CAlert('Please write a note!!');
          isValid = false;
        },
      );
      return false;
    }
    console.log('AddPool -> validate -> isValid', isValid);
    if (isValid) {
      console.log('In else');
      this.sendRequestAPI();
    }
  }

  sendRequestAPI() {
    const {poolName, phoneNumber, area, notes, selectedValue} = this.state;
    const {navigation, auth} = this.props;
    //Validate Details
    // if (isValid) {
    console.log('AddPool -> sendRequestAPI -> data', selectedValue);
    // return;
    const pCode = selectedValue ? selectedValue.label : '';
    const val = pCode.split('+').pop();
    const data = {
      userId: auth.userData.ID,
      poolName: poolName,
      phoneNumber: `+${val} ${phoneNumber}`,
      area: area,
      notes: notes,
      apiVersion: 2,
    };
    console.log('AddPool -> sendRequestAPI -> data', data);
    if (auth.isConnected) {
      getApiData(BaseSetting.endpoints.sendPoolRequest, 'post', data)
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
    // }
  }

  render() {
    const {navigation} = this.props;
    const {countries, selectedValue} = this.state;
    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <Header
          title={translate('add_pool')}
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
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Pool_Name')}
              </Text>
            </View>
            <TextInput
              returnKeyType={'next'}
              autoFocus={true}
              style={BaseStyle.textInput}
              blurOnSubmit={false}
              onChangeText={text => this.setState({poolName: text})}
              autoCorrect={false}
              placeholder={translate('Pool_Name')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.poolName}
              selectionColor={BaseColor.primaryColor}
              ref={input => {
                this.poolInput = input;
              }}
              onSubmitEditing={() => {
                this.phoneInput.focus();
              }}
            />
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Phone_Number')}
              </Text>
            </View>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                alignItems: 'center',
                paddingHorizontal: Platform.OS === 'ios' ? 60 : 58,
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
                  this.phoneInput = o;
                }}
                onSubmitEditing={() => {
                  this.areaInput.focus();
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
                onChangeText={text => this.setState({phoneNumber: text})}
                autoCorrect={false}
                keyboardType="phone-pad"
                placeholder={translate('Phone')}
                placeholderTextColor={BaseColor.grayColor}
                value={this.state.phoneNumber}
                selectionColor={BaseColor.primaryColor}
              />
            </View>
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Area')}
              </Text>
            </View>
            <TextInput
              returnKeyType={'next'}
              style={BaseStyle.textInput}
              blurOnSubmit={false}
              onChangeText={text => this.setState({area: text})}
              autoCorrect={false}
              placeholder={translate('Area')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.area}
              selectionColor={BaseColor.primaryColor}
              ref={input => {
                this.areaInput = input;
              }}
              onSubmitEditing={() => {
                this.notesInput.focus();
              }}
            />
            <View style={styles.contentTitle}>
              <Text headline2 semibold>
                {translate('Notes')}
              </Text>
            </View>
            <TextInput
              returnKeyType={'next'}
              multiline={true}
              numberOfLines={10}
              style={[
                BaseStyle.textInput,
                {height: 100, textAlignVertical: 'top'},
              ]}
              onChangeText={text => this.setState({notes: text})}
              autoCorrect={false}
              placeholder={translate('Notes')}
              placeholderTextColor={BaseColor.grayColor}
              value={this.state.notes}
              selectionColor={BaseColor.primaryColor}
              ref={input => {
                this.notesInput = input;
              }}
              onSubmitEditing={() => {
                this.validate();
              }}
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
                () => {
                  this.validate();
                },
              );
            }}>
            {translate('Confirm')}
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

export default connect(mapStateToProps, mapDispatchToProps)(AddPool);
