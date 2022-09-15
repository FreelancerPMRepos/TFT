/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from 'react-redux';
import _ from 'lodash';
import {
  View,
  ScrollView,
  Platform,
  Animated,
  Modal,
  TouchableOpacity,
  Image,
  TextInput,
  StatusBar,
  Dimensions,
  ActivityIndicator,
  KeyboardAvoidingView,
  BackHandler,
} from 'react-native';
import {BaseStyle, BaseColor, Images} from '@config';
import {Header, SafeAreaView, Icon, Text, Button} from '@components';
import * as Utils from '@utils';
import styles from './styles';
import {getStatusBarHeight} from 'react-native-status-bar-height';
import {BaseSetting} from '../../config/setting';
import {translate} from '../../lang/Translate';
import {getApiData} from '../../utils/apiHelper';
import categoryName from '../../config/category';
import CAlert from '../../components/CAlert';
import moment from 'moment';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import McIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import {WebView} from 'react-native-webview';
import Swiper from 'react-native-swiper';
import {isIphoneX} from '../../config/isIphoneX';
import LinearGradient from 'react-native-linear-gradient';

const IOS = Platform.OS === 'ios';
const HEADER_MAX_HEIGHT = 200;
const HEADER_MIN_HEIGHT = IOS ? 65 : 55;
const HEADER_SCROLL_DISTANCE = HEADER_MAX_HEIGHT - HEADER_MIN_HEIGHT;
const imageViewHeight = Math.min(
  Dimensions.get('window').height * (IOS ? 0.35 : 0.3),
  350,
);

class PreviewBooking extends Component {
  constructor(props) {
    super(props);

    this.state = {
      loading: false,
      refNumber: '',
      invoiceID: '',
      paymentURL: '',
      selectedPayment: 1,
      showModal: false,
      termModal: true,
      heightHeader:
        Platform.OS === 'ios'
          ? Utils.heightHeader() - getStatusBarHeight()
          : Utils.heightHeader(),
      opacityValue: 0,
      scrollY: new Animated.Value(0),
      specialReq: '',
      visible: false,
      picsArray: [],
      isAgreeTerms: false,
      isAgreePolicy: false,
    };
    this._deltaY = new Animated.Value(0);
  }

  componentDidMount() {
    if (IOS) {
      StatusBar.setBarStyle('light-content', true);
    }
    BackHandler.addEventListener(
      'hardwareBackPress',
      this.handleBackButtonClick,
    );
  }

  componentWillUnmount() {
    if (IOS) {
      StatusBar.setBarStyle('dark-content', true);
    }
    BackHandler.removeEventListener(
      'hardwareBackPress',
      this.handleBackButtonClick,
    );
  }

  handleBackButtonClick = () => {
    console.log(
      'PreviewBooking -> handleBackButtonClick -> handleBackButtonClick',
    );
    this.handleBackPress();
    return true;
  };

  cancelReservationAPICall = () => {
    const {auth, navigation} = this.props;

    let reservationID = navigation.getParam('reservationID', '');

    if (auth.isConnected) {
      let data = {id: reservationID};

      getApiData(BaseSetting.endpoints.cancelReservation, 'post', data)
        .then(result => {
          if (_.isObject(result)) {
            if (_.isBoolean(result.status) && result.status === true) {
              navigation.popToTop();
            } else {
              CAlert(
                _.isString(result.message)
                  ? result.message
                  : translate('went_wrong'),
                translate('alert'),
              );
            }
          } else {
            CAlert(translate('went_wrong'), translate('alert'));
          }
        })
        .catch(err => {
          console.log(`Error: ${err}`);
        });
    } else {
      CAlert(translate('Internet'), translate('alert'));
    }
  };

  handleBackPress = () => {
    CAlert(
      translate('Cancel_Reservation_Message'),
      translate('Cancel_Reservation'),
      () => {
        this.cancelReservationAPICall();
      },
      () => {},
    );
  };

  sendSpecialReq = () => {
    const {specialReq} = this.state;
    console.log('TCL: sendSpecialReq -> specialReq', specialReq);
    if (specialReq) {
      let reservationID = this.props.navigation.getParam('reservationID', '');
      const data = {
        note: specialReq,
        id: reservationID,
      };
      console.log('TCL: sendSpecialReq -> data', data);
      this.setState({loading: true}, () => {
        getApiData(BaseSetting.endpoints.addNote, 'post', data)
          .then(result => {
            console.log('TCL: sendSpecialReq -> result', result);
            if (result && result.status) {
              this.setState({
                loading: false,
              });
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
            this.setState({
              loading: false,
            });
          });
      });
    }
    this.createPaymentAPICall();
  };

  createPaymentAPICall = () => {
    const {
      auth,
      language: {languageData},
      booking: {bookingData},
      navigation,
    } = this.props;
    const toDate =
      bookingData && bookingData.startingDate
        ? bookingData.startingDate
        : selectedDate;

    let periodID = 0;
    let reservationID = navigation.getParam('reservationID', '');
    let downPayment = navigation.getParam('downPayment', '');
    let itemDetails = navigation.getParam('itemDetails', {});
    let selectedDate = navigation.getParam('selectedDate', '');
    let period = navigation.getParam('period', '');
    const currency = navigation.getParam('currency', '');

    if (period && period[0] && period[0].id) {
      if (period[0].id === 3) {
        periodID = 3;
      } else if (period[0].id === 1) {
        periodID = 1;
      } else if (period[0].id === 2) {
        periodID = 2;
      } else {
        periodID = 4;
      }
    }

    if (auth.isConnected) {
      this.setState({
        loading: true,
      });
      let data = {};

      data.userId = auth.userData.ID;
      data.userFirstName = auth.userData.first_name;
      data.userLastName = auth.userData.last_name;
      data.userMobile = auth.userData.mobile;
      data.userEmail = auth.userData.email;
      data.reservationId = reservationID;
      data.gateway =
        this.state.selectedPayment === 2 ? 'Benefit' : 'VISA/MASTER';
      data.currency = currency;
      data.faciltyName =
        languageData === 'en' ? itemDetails.name_EN : itemDetails.name_AR;
      data.selectedDate = toDate;
      data.period = periodID;
      data.language = languageData;
      data.downPayment = downPayment;
      data.userCountryCode = auth.userData.country_code;
      console.log('createPaymentAPICall -> data', data);

      getApiData(BaseSetting.endpoints.createPayment, 'post', data)
        .then(result => {
          console.log('createPaymentAPICall -> result', result);
          if (_.isObject(result)) {
            if (
              _.isBoolean(result.status) &&
              result.status === true &&
              _.isObject(result.data)
            ) {
              this.setState({
                refNumber: result.data.ref_number,
                invoiceID: result.data.invoiceId,
                paymentURL: result.data.URL,
                showModal: true,
                loading: false,
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
          this.setState({
            loading: false,
          });
        });
    } else {
      CAlert(translate('Internet'), translate('alert'));
    }
  };

  onNavigationStateChange = navState => {
    console.log('navState ============================================>');
    console.log(navState);
    console.log('URL: ', navState.url);
    if (
      navState.url === 'http://api.kashtahapp.com/paytabs/response' ||
      navState.url === 'http://api.kashtahapp.com/benefit/response' ||
      navState.url === 'http://api.kashtahapp.com/MyFatoorah/response' ||
      navState.url === 'http://api.kashtahapp.com/credimax/response'
    ) {
      this.setState(
        {
          showModal: false,
          loading: true,
        },
        () => {
          this.verifyAPICall();
        },
      );
    }
  };

  verifyAPICall = () => {
    const {
      auth,
      language: {languageData},
      navigation,
    } = this.props;
    const {refNumber, invoiceID} = this.state;
    let reservationID = navigation.getParam('reservationID', '');
    let startTime = navigation.getParam('startTime', '');
    let endTime = navigation.getParam('endTime', '');
    let selectedCategory = navigation.getParam('selectedCategory', '');
    let priceDetail = navigation.getParam('priceDetail', {});
    let selectedDate = navigation.getParam('selectedDate', '');
    let period = navigation.getParam('period', '');
    let picsArray = navigation.getParam('picsArray', []);
    let itemDetails = navigation.getParam('itemDetails', {});
    let downPayment = navigation.getParam('downPayment', '');
    let selectedPrice = navigation.getParam('selectedPrice', '');
    const currency = navigation.getParam('currency', '');
    const {
      booking: {bookingData},
    } = this.props;

    if (auth.isConnected) {
      this.setState({
        loading: true,
      });
      let data = {};

      data.reservationId = reservationID;
      data.refId = refNumber;
      data.invoiceId = invoiceID;
      data.startTime = startTime;
      data.endTime = endTime;
      data.emailLang = languageData;

      console.log('Data: ', data);
      let sDate = navigation.getParam('sDate', '');
      let eDate = navigation.getParam('eDate', '');

      getApiData(BaseSetting.endpoints.verifyPayment, 'post', data)
        .then(result => {
          if (_.isObject(result)) {
            if (
              _.isBoolean(result.status) &&
              result.status === true &&
              _.isObject(result.data)
            ) {
              if (result.data.response_code == '2') {
                navigation.navigate('EventTicket', {
                  itemDetails: itemDetails,
                  priceDetail: priceDetail,
                  selectedDate:
                    bookingData && bookingData.startingDate
                      ? bookingData.startingDate
                      : selectedDate,
                  selectedCategory: selectedCategory,
                  currency,
                  period: period,
                  picsArray: picsArray,
                  downPayment: downPayment,
                  selectedPrice: selectedPrice,
                  reservationID: reservationID,
                  startTime: startTime,
                  endTime: endTime,
                  sDate,
                  eDate,
                });
              } else {
                this.setState(
                  {
                    loading: false,
                  },
                  () => {
                    CAlert(
                      translate('Payment_Failed'),
                      translate('error'),
                      () => {
                        navigation.goBack();
                      },
                      null,
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
          this.setState({
            loading: false,
          });
        });
    } else {
      CAlert(translate('Internet'), translate('alert'));
    }
  };

  hideSpinner = () => {
    this.setState({visible: false});
  };
  showSpinner = () => {
    this.setState({visible: true});
  };

  handleScroll = event => {
    this.setState({
      opacityValue: event.nativeEvent.contentOffset.y / 200,
    });
  };

  render() {
    const {
      auth,
      navigation,
      language: {languageData},
    } = this.props;
    const {
      heightHeader,
      showModal,
      selectedPayment,
      termModal,
      paymentURL,
      loading,
      opacityValue,
      specialReq,
      isAgreeTerms,
      isAgreePolicy,
    } = this.state;
    console.log(
      'isAgreePolicy===>',
      selectedDate !== '',
      isAgreePolicy,
      isAgreeTerms,
    );
    console.log('condition===>', !isAgreeTerms && !isAgreePolicy);

    let selectedCategory = navigation.getParam('selectedCategory', '');
    let priceDetail = navigation.getParam('priceDetail', {});
    let selectedDate = navigation.getParam('selectedDate', '');
    let period = navigation.getParam('period', '');
    let picsArray = navigation.getParam('picsArray', []);
    let itemDetails = navigation.getParam('itemDetails', {});
    let downPayment = navigation.getParam('downPayment', '');
    let selectedPrice = navigation.getParam('selectedPrice', '');
    let startPeriod = navigation.getParam('startPeriod', '');
    let endPeriod = navigation.getParam('endPeriod', '');
    let msgTermCOndition = '';
    console.log('render -> selectedPrice', selectedPrice);

    if (selectedCategory === categoryName.pools) {
      msgTermCOndition = translate('Terms_Pool');
    } else if (selectedCategory === categoryName.chalets) {
      msgTermCOndition = translate('Terms_Chalet');
    } else if (selectedCategory === categoryName.camps) {
      msgTermCOndition = translate('Terms_Camp');
    }

    console.log('itemDetails=', itemDetails, priceDetail, priceDetail.offer_Id);

    const heightImageBanner = Utils.scaleWithPixel(250, 1);
    const marginTopBanner = heightImageBanner - heightHeader - 40;

    let timeDisplay = '';

    if (
      selectedCategory !== categoryName.pools &&
      (priceDetail.offer_Id == '' || priceDetail.offer_Id == '0')
    ) {
      timeDisplay = `${itemDetails.start_time} to ${itemDetails.end_time}`;
    } else if (period === translate('Full_Day')) {
      timeDisplay =
        priceDetail.full_day_start_time +
        ' ' +
        'to' +
        ' ' +
        priceDetail.full_day_end_time;
    } else if (period === translate('Morning')) {
      timeDisplay =
        itemDetails.morning_start_time +
        ' ' +
        'to' +
        ' ' +
        itemDetails.morning_end_time;
    } else if (period === translate('Evening')) {
      timeDisplay =
        itemDetails.evening_start_time +
        ' ' +
        'to' +
        ' ' +
        itemDetails.evening_end_time;
    }

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

    const seperator = (
      <View
        style={{
          height: '100%',
          width: 0.5,
          backgroundColor: BaseColor.textSecondaryColor,
        }}
      />
    );

    const {
      booking: {bookingData},
    } = this.props;
    console.log('render -> bookingData', bookingData, this.props);
    let sDate = navigation.getParam('sDate', '');
    let eDate = navigation.getParam('eDate', '');
    const fromDate =
      bookingData && bookingData.startingDate
        ? Platform.select({
            ios: bookingData.startingDate,
            android: sDate,
          })
        : moment(selectedDate).format('ddd, DD MMMM YYYY');
    const toDate =
      bookingData && bookingData.endingDate
        ? Platform.select({
            ios: bookingData.endingDate,
            android: eDate,
          })
        : moment(selectedDate).format('ddd, DD MMMM YYYY');
    let perType = 'Morning';
    if (selectedCategory !== categoryName.pools) {
      perType = 'FullDay';
    }
    console.log('period===', period);
    const sPeriod =
      period === 'Full Day'
        ? {title: period}
        : period && period[0] && period[0].id && period[0].id === 3
        ? {title: period[0].title}
        : startPeriod
        ? startPeriod
        : '';
    const ePeriod =
      period === 'Full Day'
        ? {title: period}
        : period && period[0] && period[0].id && period[0].id === 3
        ? {title: period[0].title}
        : endPeriod
        ? endPeriod
        : '';
    const currency = navigation.getParam('currency', '');
    return (
      <KeyboardAvoidingView
        enabled={IOS ? true : false}
        behavior="padding"
        style={{flex: 1}}>
        <Animated.View style={{flex: 1}}>
          {/* Header */}
          <Header
            style={{
              // position: 'absolute',
              // top: 0,
              // left: 0,
              // right: 0,
              width: '100%',
              paddingTop: IOS ? getStatusBarHeight() : 0,
              height: headerHeight,
              zIndex: 999999,
              backgroundColor: BaseColor.primaryColor,
            }}
            title="Preview Booking"
            titleStyle={{
              color: BaseColor.whiteColor,
              fontWeight: '600',
              fontSize: 20,
            }}
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
              this.handleBackPress();
            }}
          />
          <View
            style={[
              BaseStyle.safeAreaView,
              IOS && isIphoneX() ? BaseStyle.iphoneXStyle : {},
            ]}>
            <Animated.ScrollView
              // bounces={false}
              scrollEventThrottle={16}
              onScroll={Animated.event([
                {nativeEvent: {contentOffset: {y: this.state.scrollY}}},
              ])}>
              {/* Main Container */}
              <View>
                {/* <View
                  style={{
                    height: imageViewHeight,
                    width: '100%',
                    alignItems: 'center',
                    justifyContent: 'center',
                  }}>
                  <Swiper
                    dotStyle={{
                      backgroundColor: BaseColor.textSecondaryColor,
                    }}
                    activeDotColor={BaseColor.primaryColor}>
                    {picsArray.map((item, key) => {
                      return (
                        <Image
                          key={key}
                          style={{width: '100%', height: '100%'}}
                          resizeMode="cover"
                          // source={{uri: item.serverPath + item.name}}
                          source={{
                            uri:
                              item.serverPath +
                              (item.thumb && item.thumb[4]
                                ? item.thumb[4]
                                : item.name),
                            // : item.serverPath + item.name,
                          }}
                        />
                      );
                    })}
                  </Swiper>
                </View> */}
                {/* Information */}
                {/* <View style={{paddingHorizontal: 20}}>
                  <Text title3 semibold>
                    Price Details
                  </Text>
                </View> */}
                <View style={styles.contentBoxTop}>
                  <View
                    onPress={() => {
                      this.setState({
                        shoModal: true,
                      });
                    }}
                    activeOpacity={0.7}
                    style={styles.detailCard}>
                    <View>
                      <View
                        onPress={this.openCalendar}
                        style={{paddingLeft: 10, paddingRight: 20}}>
                        <View>
                          <Text
                            title3
                            semibold
                            style={{textAlign: 'center', marginBottom: 10}}>
                            {languageData === 'en'
                              ? itemDetails.name_EN
                              : itemDetails.name_AR}{' '}
                            - ({itemDetails.size})
                          </Text>
                        </View>
                        <View
                          style={{
                            width: '100%',
                            flexDirection: 'row',
                            margin: 5,
                          }}>
                          <View
                            style={{
                              flex: 1,
                              justifyContent: 'center',
                              alignItems: 'flex-start',
                            }}>
                            <Text title style={{marginBottom: 10}}>
                              {translate('From')}
                            </Text>
                            <Text title>
                              {fromDate &&
                                moment(fromDate).format('ddd, DD MMMM YYYY')}
                              {/* {moment(selectedDate).format('ddd, DD MMMM YYYY')} */}
                            </Text>
                            {!_.isEmpty(sPeriod) && (
                              <View
                                activeOpacity={0.8}
                                style={[styles.singlePeriod, styles.singleTag]}>
                                <Text
                                  caption2
                                  medium
                                  style={{color: BaseColor.primaryColor}}>
                                  {sPeriod.title}
                                </Text>
                              </View>
                            )}
                          </View>
                          {seperator}
                          <View
                            style={{
                              flex: 1,
                              justifyContent: 'center',
                              alignItems: 'flex-end',
                            }}>
                            <Text title style={{marginBottom: 10}}>
                              {/* {translate('Select_Period')} */}
                              {translate('To')}
                            </Text>
                            <Text title>
                              {toDate &&
                                moment(toDate).format('ddd, DD MMMM YYYY')}
                            </Text>
                            {!_.isEmpty(ePeriod) && (
                              <View
                                activeOpacity={0.8}
                                style={[styles.singlePeriod, styles.singleTag]}>
                                <Text
                                  caption2
                                  medium
                                  style={{color: BaseColor.primaryColor}}>
                                  {ePeriod.title}
                                </Text>
                              </View>
                            )}
                          </View>
                        </View>
                      </View>
                    </View>
                    {/* Payment Detrail */}
                    <View style={[{padding: 20, paddingLeft: 10}]}>
                      {/* <View
                        style={{
                          width: '100%',
                          flexDirection: 'row',
                          margin: 5,
                          justifyContent: 'space-between',
                          alignItems: 'center',
                          paddingBottom: 5,
                        }}>
                        <Text title>{translate('Select_Period')}:</Text>
                        <View style={styles.pWrapper}>
                          {bookingData && bookingData.periodType ? (
                            bookingData.periodType.map(item => {
                              return (
                                <View
                                  activeOpacity={0.8}
                                  style={[
                                    styles.singlePeriod,
                                    {
                                      borderWidth: 1,
                                      borderColor: BaseColor.primaryColor,
                                      backgroundColor: BaseColor.whiteColor,
                                      marginLeft: 5,
                                    },
                                  ]}>
                                  <Text
                                    caption2
                                    medium
                                    style={{color: BaseColor.primaryColor}}>
                                    {item.title}
                                  </Text>
                                </View>
                              );
                            })
                          ) : (
                            <View
                              style={[
                                styles.singlePeriod,
                                {
                                  borderWidth: 1,
                                  borderColor: BaseColor.primaryColor,
                                  backgroundColor: BaseColor.whiteColor,
                                  marginLeft: 5,
                                },
                              ]}>
                              <Text
                                caption2
                                medium
                                style={{color: BaseColor.primaryColor}}>
                                {perType}
                              </Text>
                            </View>
                          )}
                        </View>
                      </View> */}
                      {/* {seperatorHor} */}
                      <View
                        style={{
                          width: '100%',
                          flexDirection: 'row',
                          margin: 5,
                          justifyContent: 'space-between',
                          alignItems: 'center',
                          paddingBottom: 5,
                        }}>
                        <Text title>{translate('total_price')}:</Text>
                        <Text title>
                          {selectedPrice} {currency}
                        </Text>
                      </View>
                      {/* {seperatorHor} */}
                      <View
                        style={{
                          width: '100%',
                          flexDirection: 'row',
                          margin: 5,
                          justifyContent: 'space-between',
                          alignItems: 'center',
                          paddingVertical: 6,
                        }}>
                        <Text title>{translate('Down')}:</Text>
                        <Text title>
                          {downPayment} {currency}
                        </Text>
                      </View>
                      {/* {seperatorHor} */}
                      {/* <View
                        style={{
                          width: '100%',
                          flexDirection: 'row',
                          margin: 5,
                          justifyContent: 'space-between',
                          alignItems: 'center',
                          paddingTop: 5,
                        }}>
                        <Text title>{translate('Insurance')}</Text>
                        <Text title>
                          {itemDetails.insurance === '0'
                            ? translate('No_Insurance')
                            : `${itemDetails.insurance} BHD`}{' '}
                        </Text>
                      </View> */}
                    </View>
                  </View>
                </View>
              </View>
              <View
                style={[
                  styles.detailCard,
                  {
                    width: 'auto',
                    marginTop: 0,
                    marginHorizontal: 15,
                    marginBottom: 20,
                    padding: 10,
                    paddingHorizontal: 15,
                  },
                ]}>
                <View
                  style={{
                    flexDirection: 'row',
                    alignItems: 'center',
                    paddingHorizontal: 10,
                    paddingVertical: IOS ? 10 : 0,
                    borderColor: '#000',
                    borderRadius: 5,
                    borderWidth: 0.5,
                    // width: 'auto',
                  }}>
                  <Text title style={{marginRight: 10}}>
                    {translate('Special_Request')}
                  </Text>
                  <TextInput
                    style={{flex: 1}}
                    placeholder={translate('Special_Request_Placeholder')}
                    value={specialReq}
                    onChangeText={text => this.setState({specialReq: text})}
                  />
                </View>
              </View>
              <View
                style={[
                  styles.detailCard,
                  {
                    margin: 0,
                    marginTop: 0,
                    marginHorizontal: 15,
                    width: 'auto',
                    paddingVertical: 10,
                  },
                ]}>
                <Text
                  body1
                  semibold
                  style={{textAlign: 'center', marginBottom: 20}}>
                  {translate('Payment_Method')}
                </Text>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    this.setState({
                      selectedPayment: 1,
                    });
                  }}>
                  <View
                    style={[
                      styles.paymentButton,
                      {
                        backgroundColor:
                          selectedPayment === 1
                            ? BaseColor.primaryColor
                            : BaseColor.whiteColor,
                      },
                    ]}>
                    <Image
                      resizeMode="contain"
                      source={Images.credit}
                      style={{width: 45, height: 15}}
                    />
                    <Text
                      title
                      style={{
                        flex: 1,
                        textAlign: 'center',
                        color:
                          selectedPayment === 1
                            ? BaseColor.whiteColor
                            : BaseColor.primaryColor,
                      }}>
                      {translate('Credit_Card')}
                    </Text>
                  </View>
                </TouchableOpacity>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    this.setState({
                      selectedPayment: 2,
                    });
                  }}>
                  <View
                    style={[
                      styles.paymentButton,
                      {
                        backgroundColor:
                          selectedPayment === 2
                            ? BaseColor.primaryColor
                            : BaseColor.whiteColor,
                      },
                    ]}>
                    <Image
                      resizeMode="contain"
                      source={Images.debit}
                      style={{width: 45, height: 25}}
                    />
                    <Text
                      title
                      style={{
                        flex: 1,
                        textAlign: 'center',
                        color:
                          selectedPayment === 2
                            ? BaseColor.whiteColor
                            : BaseColor.primaryColor,
                      }}>
                      {translate('Debit_Card')}
                    </Text>
                  </View>
                </TouchableOpacity>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    this.setState({
                      selectedPayment: 3,
                    });
                  }}>
                  <View
                    style={[
                      styles.paymentButton,
                      {
                        backgroundColor:
                          selectedPayment === 3
                            ? BaseColor.primaryColor
                            : BaseColor.whiteColor,
                      },
                    ]}>
                    <Image
                      resizeMode="contain"
                      source={Images.sadad}
                      style={{width: 45, height: 15}}
                    />
                    <Text
                      title
                      style={{
                        flex: 1,
                        textAlign: 'center',
                        color:
                          selectedPayment === 3
                            ? BaseColor.whiteColor
                            : BaseColor.primaryColor,
                      }}>
                      {translate('Sadad_Card')}
                    </Text>
                  </View>
                </TouchableOpacity>
              </View>
              <View
                style={{
                  paddingLeft: 15,
                  paddingTop: 15,
                  paddingBottom: 10,
                }}>
                <Text title semibold>
                  {translate('Proceeding_Condtions')}{' '}
                </Text>
              </View>
              <View
                style={{
                  flexDirection: 'row',
                  alignItems: 'center',
                  paddingLeft: 15,
                  paddingRight: 15,
                  // paddingVertical: 5,
                  // justifyContent: 'center',
                  // backgroundColor: 'aqua'
                }}>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    this.setState({isAgreeTerms: !isAgreeTerms});
                  }}>
                  <McIcon
                    name={
                      isAgreeTerms
                        ? 'check-box-outline'
                        : 'checkbox-blank-outline'
                    }
                    size={20}
                    color={BaseColor.primaryColor}
                    style={{paddingRight: 5}}
                  />
                </TouchableOpacity>
                <Text title semibold>
                  <Text
                    semibold
                    onPress={() => {
                      this.setState({
                        showModal: true,
                        termModal: true,
                      });
                    }}
                    style={{color: 'red'}}>
                    {msgTermCOndition}
                  </Text>{' '}
                  {translate('And')}{' '}
                </Text>
              </View>
              <View
                style={{
                  flexDirection: 'row',
                  alignItems: 'center',
                  paddingHorizontal: 15,
                  // paddingRight: 15,
                  paddingBottom: 15,
                  // justifyContent: 'center',
                  // backgroundColor: 'aqua'
                }}>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => {
                    this.setState({isAgreePolicy: !isAgreePolicy});
                  }}>
                  <McIcon
                    name={
                      isAgreePolicy
                        ? 'check-box-outline'
                        : 'checkbox-blank-outline'
                    }
                    size={20}
                    color={BaseColor.primaryColor}
                    style={{paddingRight: 5}}
                  />
                </TouchableOpacity>
                <Text
                  semibold
                  onPress={() => {
                    this.setState({
                      showModal: true,
                      termModal: false,
                    });
                  }}
                  style={{color: 'red'}}>
                  {translate('Cancelation')}
                </Text>
              </View>
            </Animated.ScrollView>
            {/* Pricing & Booking Process */}
            <View style={styles.contentButtonBottom}>
              <View>
                <Text title>
                  {translate('Price')} / {translate('Down')}
                </Text>
                <Text title primaryColor semibold>
                  {_.toInteger(downPayment)} {currency}
                </Text>
              </View>
              <Button
                disable={loading || !isAgreeTerms || !isAgreePolicy}
                loading={loading}
                style={{
                  height: 46,
                  opacity: !isAgreeTerms || !isAgreePolicy ? 0.6 : 1.0,
                }}
                di
                onPress={() => {
                  this.sendSpecialReq();
                }}>
                {translate('Book')}
              </Button>
            </View>
          </View>

          <Modal
            visible={showModal}
            animationType="fade"
            transparent={true}
            onRequestClose={() => {
              this.setState({
                showModal: false,
              });
            }}>
            {paymentURL === '' ? (
              <SafeAreaView
                style={{
                  flex: 1,
                  alignItems: 'center',
                  justifyContent: 'center',
                  backgroundColor: BaseColor.primaryColor,
                }}>
                {/* <LinearGradient
                colors={[
                  BaseColor.primaryColor,
                  '#00000000',
                  // `${BaseColor.primaryColor}9c`,
                ]}
                style={styles.linearGradient}
              /> */}
                <View
                  style={{
                    flexDirection: 'row',
                    alignItems: 'center',
                    padding: 10,
                    // paddingVertical: 17,
                    // zIndex: 100,
                  }}>
                  <TouchableOpacity
                    style={{width: 50}}
                    onPress={() => {
                      this.setState({
                        showModal: false,
                      });
                    }}>
                    <Icon
                      name="arrow-left"
                      size={20}
                      color={BaseColor.whiteColor}
                    />
                  </TouchableOpacity>
                  <Text
                    title3
                    bold
                    numberOfLines={1}
                    style={{
                      flex: 1,
                      textAlign: 'center',
                      color: BaseColor.whiteColor,
                    }}>
                    {termModal ? msgTermCOndition : translate('Cancelation')}
                  </Text>
                  <View style={{width: 50}} />
                </View>
                {/* <View
                style={{
                  marginVertical: 10,
                  // marginBottom: 15,
                  height: 0.5,
                  backgroundColor: '#ddd',
                  width: '100%',
                }}
              /> */}
                <WebView
                  style={{
                    height: Utils.getHeightDevice(),
                    width: Utils.getWidthDevice(),
                    marginTop: 15,
                  }}
                  originWhitelist={['*']}
                  source={{
                    html: termModal
                      ? languageData === 'en'
                        ? itemDetails.terms_EN
                        : itemDetails.terms_AR
                      : translate('Our_Terms'),
                  }}
                  onError={syntheticEvent => {
                    const {nativeEvent} = syntheticEvent;
                    console.log('WebView error: ', nativeEvent);
                    this.setState({visible: false});
                  }}
                />
              </SafeAreaView>
            ) : (
              <SafeAreaView
                style={{
                  flex: 1,
                  alignItems: 'center',
                  justifyContent: 'center',
                  backgroundColor: BaseColor.primaryColor,
                  // margin: 70,
                }}>
                {/* <LinearGradient
                colors={[
                  BaseColor.primaryColor,
                  '#00000000',
                  // `${BaseColor.primaryColor}9c`,
                ]}
                style={styles.linearGradient}
              /> */}
                <View
                  style={{
                    flexDirection: 'row',
                    alignItems: 'center',
                    padding: 14,
                    // paddingTop: 15,
                    // zIndex: 100,
                  }}>
                  <TouchableOpacity
                    style={{width: 50}}
                    onPress={() => {
                      this.setState({
                        showModal: false,
                      });
                    }}>
                    <Icon
                      name="arrow-left"
                      size={20}
                      color={BaseColor.whiteColor}
                    />
                    {/* <McIcon
                    name="arrow-left"
                    size={24}
                    color={BaseColor.whiteColor}
                    style={{fontWeight: 600}}
                  /> */}
                  </TouchableOpacity>
                  <Text
                    title3
                    bold
                    style={{
                      flex: 1,
                      textAlign: 'center',
                      color: '#fff',
                      // color: BaseColor.primaryColor,
                    }}>
                    Payment
                  </Text>
                  <View style={{width: 50}} />
                </View>
                {/* <View
                style={{
                  marginVertical: 10,
                  height: 0.5,
                  backgroundColor: '#a9a9a9',
                  width: '100%',
                }}
              /> */}
                <WebView
                  onLoadStart={() => this.showSpinner()}
                  onLoad={() => this.hideSpinner()}
                  style={{
                    height: Utils.getHeightDevice(),
                    width: Utils.getWidthDevice(),
                  }}
                  source={{
                    uri: paymentURL,
                  }}
                  onNavigationStateChange={this.onNavigationStateChange}
                  onError={syntheticEvent => {
                    const {nativeEvent} = syntheticEvent;
                    console.log('WebView error: ', nativeEvent);
                    this.setState({visible: false});
                  }}
                />
                {this.state.visible && (
                  <ActivityIndicator
                    style={{
                      flex: 1,
                      left: 0,
                      right: 0,
                      top: 0,
                      bottom: 0,
                      position: 'absolute',
                      alignItems: 'center',
                      justifyContent: 'center',
                    }}
                    size="large"
                  />
                )}
              </SafeAreaView>
            )}
          </Modal>
        </Animated.View>
      </KeyboardAvoidingView>
    );
  }
}

PreviewBooking.defaultProps = {
  auth: {},
  language: {},
  booking: {},
};

PreviewBooking.propTypes = {
  auth: PropTypes.objectOf(PropTypes.any),
  language: PropTypes.objectOf(PropTypes.any),
  booking: PropTypes.objectOf(PropTypes.any),
};

const mapStateToProps = state => ({
  auth: state.auth,
  language: state.language,
  booking: state.booking,
});

export default connect(mapStateToProps, null)(PreviewBooking);
