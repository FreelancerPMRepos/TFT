/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import LanguageActions from '../../redux/reducers/language/actions';
import {store} from '../../redux/store/configureStore';
import RNRestart from 'react-native-restart';
import {
  View,
  FlatList,
  TextInput,
  ActivityIndicator,
  TouchableOpacity,
} from 'react-native';
import {BaseStyle, BaseColor} from '@config';
import {Header, SafeAreaView, Icon, Text} from '@components';
import styles from './styles';
import {initTranslate, translate} from '../../lang/Translate';
// Load sample language data list
import {LanguageData} from '@data';
import {setStatusbar} from '@config';

class ChangeLanguage extends Component {
  constructor(props) {
    super(props);

    // Temp data define
    this.state = {
      country: '',
      language: LanguageData,
      loading: false,
    };
  }

  /**
   * @description Called when setting language is selected
   * @author Passion UI <passionui.com>
   * @date 2019-08-03
   * @param {object} select
   */
  componentDidMount() {
    setStatusbar('light');
    console.log('Current Language=====>', this.props.language);
    this.setInitialLanguage();
  }

  setInitialLanguage() {
    const {language} = this.props;
    this.setState({
      language: this.state.language.map(item => {
        console.log('Item Language', item.languageCode);
        console.log('Props language', language.languageData);
        if (item.languageCode === language.languageData) {
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

  onChange(select) {
    const {
      LanguageActions: {setLanguage},
    } = this.props;
    this.setState({
      language: this.state.language.map(item => {
        if (item.language === select.language) {
          //Setting Language Redux
          console.log('Language Setting Change to ==>', item);
          setLanguage(item.languageCode, item.language);
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

  render() {
    const {navigation} = this.props;
    let {language} = this.state;
    console.log('language', language);
    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <Header
          title={translate('Change_Language')}
          renderLeft={() => {
            return (
              <Icon
                name="arrow-left"
                size={20}
                color={BaseColor.primaryColor}
              />
            );
          }}
          renderRight={() => {
            if (this.state.loading) {
              return (
                <ActivityIndicator
                  size="small"
                  color={BaseColor.primaryColor}
                />
              );
            } else {
              return (
                <Text headline primaryColor>
                  {translate('save')}
                </Text>
              );
            }
          }}
          onPressLeft={() => {
            navigation.goBack();
          }}
          onPressRight={() => {
            this.setState(
              {
                loading: true,
              },
              () => {
                setTimeout(() => {
                  initTranslate(store);
                  setTimeout(() => {
                    RNRestart.Restart();
                  }, 1000);
                }, 500);
              },
            );
          }}
        />
        <View style={styles.contain}>
          <View style={{width: '100%', height: '100%'}}>
            <FlatList
              data={language}
              keyExtractor={(item, index) => item.id}
              renderItem={({item}) => (
                <TouchableOpacity
                  style={styles.item}
                  onPress={() => this.onChange(item)}>
                  <Text
                    body1
                    style={
                      item.checked
                        ? {
                            color: BaseColor.primaryColor,
                          }
                        : {}
                    }>
                    {item.language}
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
      </SafeAreaView>
    );
  }
}

const mapStateToProps = state => {
  return {
    language: state.language,
  };
};

const mapDispatchToProps = dispatch => {
  return {
    LanguageActions: bindActionCreators(LanguageActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(ChangeLanguage);
