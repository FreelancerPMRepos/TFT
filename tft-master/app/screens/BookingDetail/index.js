/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {bindActionCreators} from 'redux';
import {
  View,
  Image,
  TouchableOpacity,
  Linking,
  Platform,
  ScrollView,
  Animated,
} from 'react-native';
import {BaseStyle, BaseColor} from '@config';
import {
  Header,
  SafeAreaView,
  Icon,
  Text,
  Button,
  StarRating,
} from '@components';
import CAlert from '@components/CAlert';
import {BaseSetting} from '../../config/setting';
import {getApiData} from '../../utils/apiHelper';
import {translate} from '../../lang/Translate';
import {setStatusbar} from '@config';
import _ from 'lodash';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import MCIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import Moment from 'moment';
import styles from './styles';
import {ActionSheetCustom as ActionSheet} from 'react-native-actionsheet';
import LinearGradient from 'react-native-linear-gradient';
import {getStatusBarHeight} from 'react-native-status-bar-height';
import {isIphoneX} from '../../config/isIphoneX';

const IOS = Platform.OS === 'ios';
const HEADER_MAX_HEIGHT = 200;
const HEADER_MIN_HEIGHT = IOS ? 65 : 55;
const HEADER_SCROLL_DISTANCE = HEADER_MAX_HEIGHT - HEADER_MIN_HEIGHT;
// const imageViewHeight = Math.min(
//   Dimensions.get('window').height * (IOS ? 0.35 : 0.4),
//   350,
// );
const options = [
  <View style={styles.actionList}>
    <MCIcon
      name="google-maps"
      size={25}
      style={{marginHorizontal: 5}}
      color={BaseColor.primaryColor}
    />
    <Text body1>Google Map</Text>
  </View>,
  <View style={styles.actionList}>
    <MCIcon
      name="map-outline"
      size={25}
      style={{marginHorizontal: 5}}
      color={BaseColor.primaryColor}
    />
    <Text body1>Apple Map</Text>
  </View>,
  <View style={styles.actionList}>
    <Text body1 style={{color: 'red'}}>
      Cancel
    </Text>
  </View>,
];
class BookingDetail extends Component {
  constructor(props) {
    super(props);
    this.state = {
      scrollY: new Animated.Value(0),
    };
  }

  componentDidMount() {
    const {item, fromReview} = this.props.navigation.state.params;
    setStatusbar('dark');
    const rType = item && item.reservations_type ? item.reservations_type : '';
    if (rType === '0' && fromReview) {
      this.props.navigation.navigate('Feedback', {item});
    }
    // if (fromReview) {
    //   setTimeout(() => {
    //     this.askForRate();
    //   }, 1000);
    // }
  }

  componentWillUnmount() {
    setStatusbar('light');
  }

  askForRate = () => {
    const {navigation} = this.props;
    let item = navigation.getParam('item');
    CAlert(
      `Rate this ${item.facilty_type}`,
      // translate('recommend'),
      // translate('alert'),
      'Review!',
      () => {
        navigation.navigate('Feedback', {item});
      },
      () => {},
    );
  };

  getRatingStatusAPICall = () => {
    return new Promise((resolve, reject) => {
      const {auth, navigation} = this.props;
      let item = navigation.getParam('item');
      let service_id = item.facilty_ID;
      let service_type = item.facilty_type;
      service_type = 'is_' + service_type;

      if (auth.isConnected) {
        const url = BaseSetting.endpoints.checkRating;
        let data = {
          userId: auth.userData.ID,
          serviceType: service_type,
          serviceId: service_id,
        };

        getApiData(url, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (
                _.isBoolean(result.status) &&
                result.status === true &&
                result.data.checkRating === false
              ) {
                console.log('data=>', result.data);
                //Check Rating is true if already rated
                navigation.navigate('Feedback', {item});
              } else {
                resolve(null);
              }
            } else {
              resolve(null);
            }
          })
          .catch(err => {
            console.log(`Error: ${err}`);
            reject(err);
          });
      } else {
        reject(null);
      }
    });
  };

  showActionSheet = () => {
    try {
      this.ActionSheet.show();
    } catch (error) {
      console.log('TCL: BookingDetail -> showActionSheet -> error', error);
    }
  };

  handleLocation = (i, type = 'google') => {
    const {item} = this.props.navigation.state.params;
    let url = `http://maps.google.com/?q=${item.lat},${item.lng}`;
    if (i === 1) {
      url = `http://maps.apple.com/?daddr=${item.lat},${item.lng}`;
    } else {
      url = `http://maps.google.com/?q=${item.lat},${item.lng}`;
    }
    Linking.canOpenURL(url)
      .then(supported => {
        if (supported) {
          return Linking.openURL(url);
        }
      })
      .catch(err => {
        console.error('An error occurred', err);
      });
  };

  handleAction = index => {
    const {item} = this.props.navigation.state.params;
    console.log('BookingDetail -> item', item);
    let phoneNumber = item.customer_service;

    if (index === 0) {
      //handling Call
      if (Platform.OS !== 'android') {
        phoneNumber = `telprompt:${item.country_code}${item.customer_service}`;
      } else {
        phoneNumber = `tel:${item.country_code}${item.customer_service}`;
      }
      Linking.canOpenURL(phoneNumber)
        .then(supported => {
          if (!supported) {
            CAlert('Phone number is not available');
          } else {
            return Linking.openURL(phoneNumber);
          }
        })
        .catch(err => console.log(err));
    } else if (index === 1) {
      const pNum = '+973 66303131';
      //handling Whatsapp
      // let link = `whatsapp://send?text=hello&phone=${phoneNumber}`;
      // `https://api.whatsapp.com/send?phone=${phoneNumber}`
      let link = `https://wa.me/${pNum}?text=Hi!`;
      Linking.canOpenURL(link)
        .then(supported => {
          if (!supported) {
            CAlert(
              'Please install whatsapp to get customer service via whatsapp',
            );
          } else {
            return Linking.openURL(link);
          }
        })
        .catch(err => console.error('An error occurred', err));
    }
  };

  render() {
    const headerBgStyle = this.state.scrollY.interpolate({
      inputRange: [0, HEADER_SCROLL_DISTANCE / 2, HEADER_SCROLL_DISTANCE],
      outputRange: [
        'rgba(77,178,229,0)',
        'rgba(77,178,229,0.5)',
        'rgba(77,178,229,1)',
      ],
      extrapolate: 'clamp',
    });
    const {navigation} = this.props;
    const {item} = this.props.navigation.state.params;
    let periodType, startTime, endTime, periodType1, startTime1, endTime1;
    const payPrice = Number(item.price) - Number(item.down_payment);
    const sDate = Moment(item.start_date, 'YYYY-MM-DD');
    let eDate = Moment(item.start_date, 'YYYY-MM-DD');
    let eWeekDay = Moment().format('ddd');
    if (item.end_date && !_.isNull(item.end_date)) {
      eDate = Moment(item.end_date, 'YYYY-MM-DD');
      eWeekDay = eDate.format('ddd');
    }
    const insurancePrice = Number(item.insurance);
    const sWeekDay = sDate.format('ddd');
    if (item.period === '1') {
      periodType = 'Morning';
      startTime = item.morning_start_time;
      endTime = item.morning_end_time;
    } else if (item.period === '2') {
      periodType = 'Evening';
      startTime = item.evening_start_time;
      endTime = item.evening_end_time;
    } else if (item.period === '3') {
      periodType = 'Full day';
      startTime = item.full_day_start_time;
      endTime = item.full_day_end_time;
    } else {
      periodType = 'Morning';
      periodType1 = 'Evening';
      startTime = item.morning_start_time;
      endTime = item.morning_end_time;
      startTime1 = item.evening_start_time;
      endTime1 = item.evening_end_time;
    }
    console.log(
      'Data==>',
      // eDate,
      // item.period,
      // insurancePrice,
      // item.facilty_type,
      item,
      startTime1,
      endTime1,
    );
    const rType = item && item.reservations_type ? item.reservations_type : '';
    const imgUrlPath = item && item.thumb && item.thumb[3] ? item.thumb[3] : '';
    const sPath = item && item.serverPath ? item.serverPath : '';
    const imgUrl = `${sPath}${imgUrlPath}`;
    let headerHeight;
    if (!IOS) {
      headerHeight = 55;
    } else if (IOS && isIphoneX()) {
      headerHeight = 90;
    } else {
      headerHeight = 70;
    }
    const isEqual = item.period !== '4';
    const currency = item && item.currency ? item.currency : 'BHD';
    const whatsappIcon = (
      <MCIcon
        name="whatsapp"
        size={20}
        style={{marginHorizontal: 5}}
        color={BaseColor.whiteColor}
      />
    );
    return (
      <Animated.View style={BaseStyle.safeAreaView}>
        <ScrollView bounces={false} style={{flex: 1}}>
          <ActionSheet
            ref={o => (this.ActionSheet = o)}
            title="Open Map"
            options={options}
            cancelButtonIndex={2}
            onPress={index => this.handleLocation(index)}
          />
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
            title={translate('booking_detail')}
            titleStyle={{color: BaseColor.whiteColor, fontWeight: '700'}}
            renderLeft={() => {
              return (
                <Icon
                  name="arrow-left"
                  size={20}
                  color={BaseColor.whiteColor}
                />
              );
            }}
            onPressLeft={() => {
              navigation.goBack();
            }}
          />
          <View>
            <Image
              style={{width: '100%', height: 200}}
              source={{uri: imgUrl ? imgUrl : item.url}}
            />
            <View style={styles.buttonRound}>
              <TouchableOpacity
                onPress={() => {
                  this.handleAction(0);
                }}>
                <MIcon name="call" size={30} color={'#FFF'} />
              </TouchableOpacity>
            </View>
          </View>
          <View style={[styles.contentBoxTop, {top: 30}]}>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
              }}>
              <View style={{flexDirection: 'row', alignItems: 'center'}}>
                <MIcon
                  name="location-on"
                  color={BaseColor.primaryColor}
                  size={17}
                />
                <Text title semibold style={{textAlign: 'center'}}>
                  {item.city_EN}
                </Text>
              </View>
              <View>
                <TouchableOpacity
                  onPress={() => {
                    if (IOS) {
                      this.showActionSheet(item);
                    } else {
                      this.handleLocation(0);
                    }
                  }}
                  style={styles.button}>
                  <Text
                    style={{color: '#FFF', fontSize: 13, marginHorizontal: 10}}>
                    {translate('View_Map')}
                  </Text>
                </TouchableOpacity>
              </View>
            </View>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                alignItems: 'center',
                marginVertical: 10,
              }}>
              <Text title semibold style={{textAlign: 'center'}}>
                {translate('Reservation_ID')} : {item.ID}
              </Text>
              {rType === '1' || item.totalRating <= 0 ? null : (
                <View style={{marginRight: 15}}>
                  <StarRating
                    disabled={true}
                    starSize={15}
                    maxStars={5}
                    rating={item.totalRating}
                    selectedStar={rating => {}}
                    fullStarColor={BaseColor.yellowColor}
                  />
                </View>
              )}
            </View>
          </View>
          <View style={{paddingBottom: 15}}>
            <View style={[styles.contentBoxTop, {paddingHorizontal: 20}]}>
              <View
                style={{
                  flexDirection: 'row',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                }}>
                <View
                  style={{
                    flexDirection: 'row',
                    alignItems: 'center',
                    paddingVertical: 8,
                  }}>
                  <MCIcon
                    name="calendar-month"
                    color={BaseColor.primaryColor}
                    size={20}
                  />
                  <Text
                    title
                    bold
                    style={{
                      textAlign: 'center',
                      marginHorizontal: 5,
                    }}>
                    {sWeekDay},
                  </Text>
                  <Text
                    title
                    style={{
                      textAlign: 'center',
                      // marginHorizontal: 5,
                    }}>
                    {sDate.format('DD MMM YYYY')}
                  </Text>
                  {!isEqual ? <Text> -</Text> : null}
                  {!isEqual ? (
                    <>
                      <Text
                        title
                        bold
                        style={{
                          textAlign: 'center',
                          marginHorizontal: 5,
                        }}>
                        {eWeekDay},
                      </Text>
                      <Text
                        title
                        style={{
                          textAlign: 'center',
                          // marginHorizontal: 5,
                        }}>
                        {eDate.format('DD MMM YYYY')}
                      </Text>
                    </>
                  ) : null}
                </View>
              </View>
              <View style={{flexDirection: 'row', alignItems: 'center'}}>
                <MCIcon
                  name="clock-outline"
                  color={BaseColor.primaryColor}
                  size={20}
                />
                <Text
                  title
                  style={{
                    textAlign: 'center',
                    // marginBottom: 10,
                    marginHorizontal: 5,
                  }}>
                  {periodType}: {startTime} to {endTime}
                </Text>
                {!isEqual ? <Text> -</Text> : null}
                {!isEqual ? (
                  <Text
                    title
                    style={{
                      textAlign: 'center',
                      // marginBottom: 10,
                      marginHorizontal: 5,
                    }}>
                    {periodType1}: {startTime1} to {endTime1}
                  </Text>
                ) : null}
              </View>
              <View
                style={{
                  flexDirection: 'row',
                  justifyContent: 'center',
                  marginVertical: 20,
                }}>
                <Text
                  body2
                  bold
                  style={{
                    marginHorizontal: 40,
                  }}>
                  {translate('Price')}: {item.price} {currency}
                </Text>
                <Text
                  body2
                  bold
                  textAlign="right"
                  style={{
                    marginHorizontal: 40,
                  }}>
                  {translate('Paid')}: {item.down_payment} {currency}
                </Text>
              </View>
              <View style={{alignItems: 'center'}}>
                <MCIcon name="cash-multiple" color="#000" size={50} />
                <Text
                  body1
                  bold
                  textAlign="right"
                  style={{
                    marginHorizontal: 40,
                  }}>
                  {payPrice} {currency}
                </Text>
                {insurancePrice > 0 || item.facilty_type ? (
                  <View
                    style={{justifyContent: 'center', alignItems: 'center'}}>
                    <Text body2>
                      + {insurancePrice} {currency} {translate('Insurance')}
                    </Text>
                    <Text body2>Cash (in {item.facilty_type})</Text>
                  </View>
                ) : (
                  <View />
                )}
              </View>
            </View>
          </View>
          <View
            style={{
              flexDirection: 'row',
              justifyContent: 'center',
              alignItem: 'center',
              paddingHorizontal: 8,
            }}>
            {item.rated || rType === '1' ? null : (
              <View style={{padding: 5, paddingTop: 20, flex: 1}}>
                <Button
                  full
                  loading={this.state.loading}
                  styleText={{fontSize: 15}}
                  style={{paddingHorizontal: 10}}
                  onPress={() =>
                    this.props.navigation.navigate('Feedback', {item})
                  }>
                  {translate('Submit Review')}
                </Button>
              </View>
            )}
            <View style={{padding: 5, paddingTop: 20, flex: 1}}>
              <Button
                full
                iconLeft={whatsappIcon}
                styleText={{fontSize: 15}}
                style={{paddingHorizontal: 17}}
                loading={this.state.loading}
                onPress={() => this.handleAction(1)}>
                {translate('whatsapp_help')}
              </Button>
            </View>
          </View>
        </ScrollView>
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

export default connect(mapStateToProps, mapDispatchToProps)(BookingDetail);
