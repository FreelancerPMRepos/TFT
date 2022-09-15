/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  View,
  ScrollView,
  TextInput,
  Dimensions,
  Platform,
  StatusBar,
  Animated,
  KeyboardAvoidingView,
} from 'react-native';
import {BaseStyle, BaseColor} from '@config';
import {
  Image,
  Header,
  SafeAreaView,
  Icon,
  Text,
  StarRating,
  Button,
} from '@components';
import categoryName from '../../config/category';
import {BaseSetting} from '../../config/setting';
import styles from './styles';
import {translate} from '../../lang/Translate';
import {isIphoneX} from '../../config/isIphoneX';
import {getApiData} from '../../utils/apiHelper';
import CAlert from '../../components/CAlert';
import _ from 'lodash';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {getStatusBarHeight} from 'react-native-status-bar-height';
import LinearGradient from 'react-native-linear-gradient';
import LottieView from 'lottie-react-native';

const IOS = Platform.OS === 'ios';
const HEADER_MAX_HEIGHT = 200;
const HEADER_MIN_HEIGHT = IOS ? 65 : 55;
const HEADER_SCROLL_DISTANCE = HEADER_MAX_HEIGHT - HEADER_MIN_HEIGHT;
const imageViewHeight = Math.min(
  Dimensions.get('window').height * (IOS ? 0.35 : 0.3),
  350,
);

class Feedback extends Component {
  constructor(props) {
    super(props);
    this.state = {
      priceRating: '0.5',
      locationRating: '0.5',
      cleanlinessRating: '0.5',
      amenitiesRating: '0.5',
      doRecommend: null,
      review: '',
      loading: false,
      scrollY: new Animated.Value(0),
      showAnimation: false,
    };
  }

  validateReview = () => {
    const {
      priceRating,
      locationRating,
      cleanlinessRating,
      amenitiesRating,
    } = this.state;
    if (
      priceRating > 0 &&
      locationRating > 0 &&
      cleanlinessRating > 0 &&
      amenitiesRating > 0
    ) {
      this.setState(
        {
          loading: true,
        },
        () => {
          this.askRecommend();
        },
      );
    } else {
      this.setState(
        {
          loading: false,
        },
        () => {
          CAlert(translate('Rating_Invalid'), translate('Oops'));
        },
      );
    }
  };

  askRecommend = () => {
    CAlert(
      translate('recommend'),
      translate('alert'),
      () => {
        this.setState({doRecommend: true}, () => {
          this.submitReview(true);
        });
      },
      () => {
        this.setState({doRecommend: false}, () => {
          this.submitReview(false);
        });
      },
      'Yes',
      'No',
    );
  };

  submitReview = doRecommend => {
    const {navigation, auth} = this.props;
    const {
      priceRating,
      locationRating,
      cleanlinessRating,
      amenitiesRating,
      review,
    } = this.state;
    let item = navigation.getParam('item');
    let service_id = item.facilty_ID;
    let service_type = item.facilty_type;
    service_type = 'is_' + service_type;

    if (auth.isConnected) {
      const url = BaseSetting.endpoints.addRating;
      const data = {
        userId: auth.userData.ID,
        serviceId: service_id,
        comment: review,
        serviceType: service_type.toLowerCase(),
        priceRating: priceRating,
        locationRating: locationRating,
        cleanlinessRating: cleanlinessRating,
        amenitiesRating: amenitiesRating,
        recommendFriend: doRecommend,
      };

      console.log('Data==>', data);

      getApiData(url, 'post', data)
        .then(result => {
          if (_.isObject(result)) {
            if (_.isBoolean(result.status) && result.status === true) {
              this.setState(
                {
                  rating: '',
                  review: '',
                  loading: false,
                  showAnimation: true,
                },
                // setTimeout(() => {
                //   () => navigation.navigate('Booking');
                // }, 500),
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

  render() {
    const {
      navigation,
      language: {languageData},
    } = this.props;

    let item = navigation.getParam('item');
    const {
      priceRating,
      locationRating,
      cleanlinessRating,
      amenitiesRating,
    } = this.state;

    const headerBgStyle = this.state.scrollY.interpolate({
      inputRange: [0, HEADER_SCROLL_DISTANCE / 2, HEADER_SCROLL_DISTANCE],
      outputRange: [
        'rgba(77,178,229,0)',
        'rgba(77,178,229,0.5)',
        'rgba(77,178,229,1)',
      ],
      extrapolate: 'clamp',
    });

    let headerHeight;
    if (!IOS) {
      headerHeight = 55;
    } else if (IOS && isIphoneX()) {
      headerHeight = 90;
    } else {
      headerHeight = 70;
    }
    console.log('URL--->', item);
    const imgUrlPath = item && item.thumb && item.thumb[3] ? item.thumb[3] : '';
    const sPath = item && item.serverPath ? item.serverPath : '';
    const imgUrl = `${sPath}${imgUrlPath}`;
    return (
      <Animated.View style={BaseStyle.safeAreaView}>
        <LinearGradient
          colors={[BaseColor.primaryColor, '#00000000']}
          style={styles.linearGradient}
        />
        <Header
          style={{
            position: 'absolute',
            top: 0,
            left: 0,
            right: 0,
            width: '100%',
            paddingTop: IOS ? getStatusBarHeight() : 0,
            height: headerHeight,
            zIndex: 999999,
            backgroundColor: headerBgStyle,
          }}
          title={languageData === 'en' ? item.name_EN : item.name_AR}
          titleStyle={{color: BaseColor.whiteColor, fontWeight: '700'}}
          renderLeft={() => {
            return (
              <Icon name="arrow-left" size={20} color={BaseColor.whiteColor} />
            );
          }}
          // renderCenter={() => {
          //   return (
          //     <Animated.View
          //       style={{
          //         marginTop: 18,
          //         transform: [{translateY: titleY}],
          //         opacity: titleO,
          //       }}>
          //       <Text title2 semibold style={{color: BaseColor.whiteColor}}>
          //         {'  '}
          //         {languageData === 'en'
          //           ? itemDetails.name_EN
          //           : itemDetails.name_AR}
          //         {'  '}
          //       </Text>
          //     </Animated.View>
          //   );
          // }}
          onPressLeft={() => {
            this.state.showAnimation ? null : navigation.goBack();
          }}
        />
        <KeyboardAvoidingView
          enabled={IOS ? true : false}
          behavior="padding"
          style={{flex: 1}}>
          <ScrollView bounces={false} style={{flex: 1}}>
            <Image
              source={{uri: imgUrl ? imgUrl : item.url}}
              style={styles.image}
            />
            <View style={{padding: 10}}>
              <View style={styles.container}>
                <View style={{width: 160}}>
                  <Text
                    title3
                    semibold
                    style={{textAlign: 'center', marginBottom: 12}}>
                    Reviews
                  </Text>
                </View>
              </View>
              <View style={styles.rationgWrapper}>
                <View style={styles.rateView}>
                  <Text body1 semibold>
                    Price:
                  </Text>
                  <StarRating
                    starSize={25}
                    maxStars={5}
                    rating={priceRating}
                    selectedStar={rating => {
                      this.setState({priceRating: rating});
                    }}
                    fullStarColor={BaseColor.yellowColor}
                    containerStyle={{padding: 5}}
                  />
                </View>
                <View style={styles.rateView}>
                  <Text body1 semibold>
                    Location:
                  </Text>
                  <StarRating
                    starSize={25}
                    maxStars={5}
                    rating={locationRating}
                    selectedStar={rating => {
                      this.setState({locationRating: rating});
                    }}
                    fullStarColor={BaseColor.yellowColor}
                    containerStyle={{padding: 5}}
                  />
                </View>
                <View style={styles.rateView}>
                  <Text body1 semibold>
                    Cleanliness:
                  </Text>
                  <StarRating
                    starSize={25}
                    maxStars={5}
                    rating={cleanlinessRating}
                    selectedStar={rating => {
                      this.setState({cleanlinessRating: rating});
                    }}
                    fullStarColor={BaseColor.yellowColor}
                    containerStyle={{padding: 5}}
                  />
                </View>
                <View style={styles.rateView}>
                  <Text body1 semibold>
                    Amenities:
                  </Text>
                  <StarRating
                    starSize={25}
                    maxStars={5}
                    rating={amenitiesRating}
                    selectedStar={rating => {
                      this.setState({amenitiesRating: rating});
                    }}
                    fullStarColor={BaseColor.yellowColor}
                    containerStyle={{padding: 5}}
                  />
                </View>
              </View>
              <TextInput
                style={[BaseStyle.textInput, {marginTop: 30, height: 100}]}
                onChangeText={text => this.setState({review: text})}
                textAlignVertical="top"
                multiline={true}
                numberOfLines={4}
                autoCorrect={false}
                placeholder="Write your review here..."
                placeholderTextColor={BaseColor.grayColor}
                value={this.state.review}
                selectionColor={BaseColor.primaryColor}
              />
            </View>
            <View style={{padding: 10}}>
              <Button
                full
                loading={this.state.loading}
                onPress={() => this.validateReview()}>
                Submit
              </Button>
            </View>
            {this.state.showAnimation ? (
              <View style={[styles.animationWrap]}>
                <LottieView
                  ref={animation => {
                    this.animation1 = animation;
                  }}
                  onAnimationFinish={() => {
                    this.setState({showAnimation: false}, () =>
                      navigation.navigate('Booking'),
                    );
                  }}
                  autoSize={false}
                  style={[styles.animation]}
                  source={require('@assets/lottie/rateSuccess.json')}
                  autoPlay={true}
                  loop={false}
                />
              </View>
            ) : null}
          </ScrollView>
        </KeyboardAvoidingView>
      </Animated.View>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
  language: state.language,
});

const mapDispatchToProps = dispatch => {
  return {
    AuthActions: bindActionCreators(AuthActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(Feedback);
