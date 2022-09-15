/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import _ from 'lodash';
import AuthActions from '../../redux/reducers/auth/actions';
import {
  View,
  TouchableOpacity,
  ScrollView,
  Platform,
  Linking,
  StatusBar,
  NativeModules,
} from 'react-native';
import {bindActionCreators} from 'redux';
import {SafeAreaView, Text, Button, Image, SwiperComponent} from '@components';
import styles from './styles';
import {BaseColor, BaseStyle, Images, setStatusbar} from '@config';
import * as Utils from '@utils';
import {initTranslate, translate} from '../../lang/Translate';
import {store} from '../../redux/store/configureStore';
import languageActions from '../../redux/reducers/language/actions';
import RNRestart from 'react-native-restart';
import CAlert from '../../components/CAlert';
import CodePush from 'react-native-code-push';
import {BaseSetting} from '../../config/setting';
import {getApiData} from '../../utils/apiHelper';
import FilterActions from '../../redux/reducers/filter/actions';
import {NavigationEvents} from 'react-navigation';

class Walkthrough extends Component {
  constructor(props) {
    super(props);

    let initialIndex = 0;

    console.log('Walkthrough ===> ', props);
    if (props.introShown && props.introShown === true) {
      initialIndex = 2;
    }

    this.state = {
      loading: false,
      scrollEnabled: true,
      slide: [
        {
          svg: require('@assets/lottie/1.json'),
          title: translate('Finding a Place?'),
          subTitle: translate(
            'Looking for a place to hangout with friends? or a place for family gathering?',
          ),
        },
        {
          svg: require('@assets/lottie/2.json'),
          title: translate('We got you covered!'),
          subTitle: translate(
            'Pools chalets camps and other places are ready to have you',
          ),
        },
        {
          title: translate('Register now and explore'),
        },
      ],
      currentSlide: 0,
      initialIndex,
    };
  }

  /**
   * @description Simple authentication without call any APIs
   * @author Passion UI <passionui.com>
   * @date 2019-08-03
   */
  authentication() {
    this.setState(
      {
        loading: true,
      },
      () => {
        this.props.actions.authentication(true, response => {
          if (response.success) {
            this.props.navigation.navigate('Loading');
          } else {
            this.setState({
              loading: false,
            });
          }
        });
      },
    );
  }

  onSkip = () => {
    const {
      actions: {setUserData},
      navigation,
    } = this.props;
    setUserData({
      first_name: 'Guest',
      last_name: 'Guest',
      isGuest: true,
      email: 'guest@guest.com',
      mobile: '123456789',
    });
    setTimeout(() => {
      navigation.navigate('Main');
    }, 500);
  };
  // com.googleusercontent.apps
  componentDidMount() {
    this.getCountries();
    if (Platform.OS === 'android') {
      Linking.getInitialURL().then(url => {
        if (!url.includes('com.googleusercontent.apps')) {
          this.navigate(url);
        }
      });
    } else {
      Linking.addEventListener('url', e => {
        if (!e.url.includes('com.googleusercontent.apps')) {
          this.handleOpenURL(e);
        }
      });
    }
    StatusBar.setBackgroundColor(BaseColor.whiteColor, true);
    StatusBar.setBarStyle('dark-content', true);

    /* Set Intro shown */
    const {setIntroShown} = this.props.actions;
    setIntroShown(true);
  }

  componentWillUnmount() {
    Linking.removeEventListener('url', e => {
      this.handleOpenURL(e);
    });
  }
  getCountries = () => {
    const {
      FilterActions: {setFilters},
      filter: {allFilters},
    } = this.props;
    const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
    const data = {
      request: true,
      apiVersion: 2,
    };
    getApiData(BaseSetting.endpoints.getCountries, 'post', data)
      .then(result => {
        console.log('TCL: Walkthrough -> getCountries -> result', result);
        if (result && result.status && result.data) {
          if (result.data.countries) {
            fData.allCountries = result.data.countries
              ? result.data.countries
              : [];
          }
          setFilters(fData);
        } else {
          fData.allCountries = [];
          setFilters(fData);
        }
      })
      .catch(err => {
        console.log(`Error: ${err}`);
      });
  };
  handleOpenURL(event) {
    console.log(event.url);
    try {
      this.navigate(event.url);
    } catch (error) {
      console.log('Error==>', error);
    }
  }
  navigate = url => {
    console.log('On Navigate ===> ', url);
    // CAlert('Please Login First!');
    const {navigate} = this.props.navigation;

    if (url && url != null) {
      const urlSplit = url.split('?');
      let urlParams = urlSplit[1]
        .split('&') // ["a=b454","c=dhjjh","f=g6hksdfjlksd"]
        .map(_.partial(_.split, _, '=', 2)); // [["a","b454"],["c","dhjjh"],["f","g6hksdfjlksd"]]

      urlParams = _.fromPairs(urlParams); // {"a":"b454","c":"dhjjh","f":"g6hksdfjlksd"}

      if (urlParams.id && urlParams.type) {
        navigate('HotelDetail', {
          itemID: urlParams.id,
          selectedCategory: urlParams.type,
        });
      }
    }
  };

  onSlideChange = index => {
    this.setState({
      currentSlide: index,
    });
  };

  setStatusbar() {
    /* Set Statusbar to match */
    setStatusbar('light');
  }

  render() {
    const {
      navigation,
      languageActions: {setLanguage},
    } = this.props;
    const {currentSlide, slide, initialIndex} = this.state;
    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <NavigationEvents
          onWillFocus={payload => {
            /* No need to update item on back - should be handled from CDU */
            // this.getItemsListAPICall();
            this.setStatusbar();
          }}
        />
        {/* <ScrollView
          style={styles.contain}
          scrollEnabled={this.state.scrollEnabled}
          onContentSizeChange={(contentWidth, contentHeight) =>
            this.setState({
              scrollEnabled: Utils.scrollEnabled(contentWidth, contentHeight),
            })
          }> */}
        <View style={styles.wrapper}>
          <TouchableOpacity onPress={this.onSkip}>
            <Text style={styles.skip}>{translate('Skip')}</Text>
          </TouchableOpacity>
          <SwiperComponent
            navigation={this.props.navigation}
            loop={false}
            index={initialIndex}
            slide={slide}
            dotStyle={{
              backgroundColor: BaseColor.textSecondaryColor,
            }}
            onIndexChanged={this.onSlideChange}
            activeDotColor={BaseColor.primaryColor}
            paginationStyle={styles.contentPage}
            removeClippedSubviews={false}>
            {this.state.slide.map((item, index) => {
              return (
                <View style={styles.slide} key={index}>
                  <View style={styles.contentBox}>
                    {item.title && (
                      <Text title1 style={styles.slideTitle}>
                        {item.title}
                      </Text>
                    )}
                    {item.subTitle && (
                      <Text subhead style={styles.slideSubTitle}>
                        {item.subTitle}
                      </Text>
                    )}
                  </View>
                </View>
              );
            })}
          </SwiperComponent>
        </View>
        {/* <View style={{width: '100%'}}>
            <Button
              full
              style={{
                marginTop: 20,
              }}
              onPress={() => navigation.navigate('SignUp')}>
              {translate('Register')}
            </Button>
            <Button
              full
              style={{marginTop: 20}}
              loading={this.state.loading}
              onPress={() => navigation.navigate('SignIn')}>
              {translate('Login')}
            </Button>
            <View style={styles.contentActionBottom}>
              <TouchableOpacity
                onPress={() => {
                  setLanguage(translate('key'));
                  setTimeout(() => {
                    initTranslate(store);
                    if (Platform.OS === 'ios') {
                      // NativeModules.DevSettings.reload();
                      CodePush.restartApp();
                    } else {
                      RNRestart.Restart();
                    }
                  }, 500);
                }}>
                <Text body1 grayColor>
                  {translate('Xlanguage')}
                </Text>
              </TouchableOpacity>

              <TouchableOpacity onPress={this.onSkip}>
                <Text body1 primaryColor>
                  {translate('Skip')}
                </Text>
              </TouchableOpacity>
            </View>
          </View>*/}
        {/* </ScrollView> */}
      </SafeAreaView>
    );
  }
}

const mapStateToProps = state => {
  return {
    filter: state.filter,
    introShown: state.auth.introShown,
  };
};

const mapDispatchToProps = dispatch => {
  return {
    actions: bindActionCreators(AuthActions, dispatch),
    languageActions: bindActionCreators(languageActions, dispatch),
    FilterActions: bindActionCreators(FilterActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(Walkthrough);
