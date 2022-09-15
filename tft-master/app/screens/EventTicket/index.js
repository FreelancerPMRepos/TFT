/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from 'react-redux';
import {View, ScrollView, Image, Platform, BackHandler} from 'react-native';
import {StackActions, NavigationActions} from 'react-navigation';
import {BaseStyle, BaseColor, Images} from '@config';
import {SafeAreaView, Text, Button} from '@components';
import styles from './styles';
import {translate} from '../../lang/Translate';
import categoryName from '../../config/category';
import moment from 'moment';
import _ from 'lodash';
import CAlert from 'app/components/CAlert';

const IOS = Platform.OS === 'ios';
class EventTicket extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
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
    console.log(
      'PreviewBooking -> handleBackButtonClick -> handleBackButtonClick',
    );
    return true;
  };

  render() {
    const {
      navigation,
      language: {languageData},
      booking: {bookingData},
    } = this.props;

    let selectedCategory = navigation.getParam('selectedCategory', '');
    let priceDetail = navigation.getParam('priceDetail', {});
    let selectedDate = navigation.getParam('selectedDate', '');
    let period = navigation.getParam('period', '');
    let itemDetails = navigation.getParam('itemDetails', {});
    let downPayment = navigation.getParam('downPayment', '');
    let selectedPrice = navigation.getParam('selectedPrice', '');
    let reservationID = navigation.getParam('reservationID', '');

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
    const toDate =
      bookingData && bookingData.endingDate ? bookingData.endingDate : '';
    let sDate = navigation.getParam('sDate', '');
    let eDate = navigation.getParam('eDate', '');
    console.log(
      'itemDetails==',
      itemDetails,
      bookingData,
      bookingData.periodType.length,
    );
    let pType = 'Morning';
    if (bookingData && bookingData.periodType[0]) {
      if (bookingData.periodType.length >= 2) {
        pType = 'Morning, Evening';
      } else if (bookingData.periodType[0].id === 1) {
        pType = 'Morning';
      } else if (bookingData.periodType[0].id === 2) {
        console.log('In both');
        pType = 'Evening';
      } else {
        pType = 'Full day';
      }
    }
    console.log('BookingData==>', bookingData);
    const sPeriod = bookingData.startPeriod && bookingData.startPeriod.title;
    const ePeriod = bookingData.endPeriod && bookingData.endPeriod.title;
    const currency = navigation.getParam('currency', '');
    const dStart = Platform.select({
      ios: moment(selectedDate).format('ddd, DD MMMM YYYY'),
      android: moment(sDate).format('ddd, DD MMMM YYYY'),
    });
    const dEnd = Platform.select({
      ios: moment(toDate).format('ddd, DD MMMM YYYY'),
      android: moment(eDate).format('ddd, DD MMMM YYYY'),
    });
    // CAlert(`${dStart} ${dEnd}`);
    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <View
          style={{
            backgroundColor: IOS
              ? BaseColor.whiteColor
              : BaseColor.primaryColor,
            paddingVertical: IOS ? 0 : 8,
          }}>
          <Text
            title3
            style={{
              color: IOS ? BaseColor.primaryColor : BaseColor.whiteColor,
              textAlign: 'center',
              marginBottom: 10,
            }}>
            {translate('Reservation_Receipt')}
          </Text>
        </View>
        <View style={{flex: 1, backgroundColor: '#a9a9a9', padding: 20}}>
          <ScrollView contentContainerStyle={{backgroundColor: '#fff'}}>
            <Image source={Images.blue_logo} style={styles.imageBackground} />
            <View style={styles.contain}>
              <Text body2 light>
                {selectedCategory === categoryName.pools
                  ? translate('Pool') + '(' + translate('Size') + ')'
                  : selectedCategory === categoryName.chalets
                  ? translate('Chalet')
                  : selectedCategory === categoryName.camps
                  ? translate('Camp')
                  : ''}
              </Text>
              <Text headline style={{marginTop: 10}}>
                {languageData === 'en'
                  ? itemDetails.name_EN
                  : itemDetails.name_AR}{' '}
                - ({itemDetails.size})
              </Text>
              {pType === 'Full day' && <View style={styles.bottomLine} />}
              {pType === 'Full day' ? (
                <View style={{marginTop: 8}}>
                  <Text body2 light>
                    Period
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {pType}
                  </Text>
                </View>
              ) : null}
              <View style={[styles.bottomLine, {marginBottom: 5}]} />
              <View style={styles.rowDisplayView}>
                <View style={styles.columnDisplayView}>
                  <Text body2 light style={{marginTop: 5}}>
                    {translate('Reservation_ID')}
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {reservationID}
                  </Text>
                </View>

                <View style={styles.columnDisplayView}>
                  <Text body2 light style={{marginTop: 5}}>
                    {translate('Reservation_Price')}
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {selectedPrice} {currency}
                  </Text>
                </View>
              </View>
              <View style={styles.bottomLine} />
              <View style={styles.rowDisplayView}>
                <View style={styles.columnDisplayView}>
                  <Text body2 light style={{marginTop: 5}}>
                    {translate('start_date')}
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {dStart}
                  </Text>
                  {pType !== 'Full day' && (
                    <Text body2 light style={{marginTop: 5}}>
                      {sPeriod}
                    </Text>
                  )}
                </View>
                {toDate ? (
                  <View style={styles.columnDisplayView}>
                    <Text body2 light style={{marginTop: 5}}>
                      {translate('end_date')}
                    </Text>
                    <Text headline style={{marginTop: 10}}>
                      {dEnd}
                    </Text>
                    {pType !== 'Full day' && (
                      <Text body2 light style={{marginTop: 5}}>
                        {ePeriod}
                      </Text>
                    )}
                  </View>
                ) : (
                  <View style={styles.columnDisplayView}>
                    <Text body2 light style={{marginTop: 5}}>
                      {translate('Timing')}
                    </Text>
                    <Text headline style={{marginTop: 10}}>
                      {timeDisplay}
                    </Text>
                  </View>
                )}
              </View>
              <View style={styles.bottomLine} />
              <View style={styles.rowDisplayView}>
                <View style={styles.columnDisplayView}>
                  <Text body2 light style={{marginTop: 5}}>
                    {translate('location')}
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {languageData === 'en'
                      ? itemDetails.city_EN
                      : itemDetails.city_AR}
                  </Text>
                </View>

                <View style={styles.columnDisplayView}>
                  <Text body2 light style={{marginTop: 5}}>
                    {translate('For_Inquiries')}
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {itemDetails.customer_service}
                  </Text>
                </View>
              </View>
              <View style={styles.bottomLine} />
              <View style={styles.rowDisplayView}>
                <View style={styles.columnDisplayView}>
                  <Text body2 light style={{marginTop: 5}}>
                    {translate('Paid')}
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {downPayment} {currency}
                  </Text>
                </View>

                <View style={styles.columnDisplayView}>
                  <Text body2 light style={{marginTop: 5}}>
                    {selectedCategory === categoryName.pools
                      ? translate('Cash_On_Site_Pool')
                      : selectedCategory === categoryName.chalets
                      ? translate('Cash_On_Site_Chalet')
                      : selectedCategory === categoryName.camps
                      ? translate('Cash_On_Site_Camp')
                      : ''}
                  </Text>
                  <Text headline style={{marginTop: 10}}>
                    {_.toInteger(selectedPrice) - _.toInteger(downPayment)}{' '}
                    {currency}
                  </Text>
                </View>
              </View>
              <View
                style={{
                  flex: 1,
                  flexDirection: 'row',
                  justifyContent: 'space-between',
                  alignItems: 'center',
                  marginTop: 25,
                  marginHorizontal: -20,
                }}>
                <View
                  style={{
                    marginLeft: -20,
                    height: 40,
                    width: 40,
                    borderRadius: 20,
                    backgroundColor: '#a9a9a9',
                  }}
                />
                <View style={styles.dotedLine} />
                <View
                  style={{
                    height: 40,
                    width: 40,
                    borderRadius: 20,
                    marginRight: -20,
                    backgroundColor: '#a9a9a9',
                  }}
                />
              </View>
              <View style={styles.code}>
                <Image source={Images.barCode} style={styles.qrCodeImage} />
              </View>
              <View style={{margin: 20}}>
                <Button
                  full
                  onPress={() => {
                    // const {state} = navigation;
                    // const currentRouteKey = state.routes[state.index].key;
                    // console.log(
                    //   'EventTicket -> render -> currentRouteKey',
                    //   state,
                    //   currentRouteKey,
                    // );
                    // const popAction = StackActions.pop(4);
                    // navigation.dispatch(popAction);
                    // const resetAction = StackActions.reset({
                    //   index: 0,
                    //   key: null,
                    //   actions: [
                    //     NavigationActions.navigate({routeName: 'Home'}),
                    //   ],
                    // });
                    // this.props.navigation.dispatch(resetAction);
                    if (IOS) {
                      navigation.navigate('Home', {fromPayment: true});
                    } else {
                      navigation.navigate('HotelDetail', {fromPayment: true});
                    }
                  }}>
                  {translate('go_home')}
                </Button>
              </View>
            </View>
          </ScrollView>
        </View>
      </SafeAreaView>
    );
  }
}

EventTicket.defaultProps = {
  language: {},
  booking: {},
};

EventTicket.propTypes = {
  language: PropTypes.objectOf(PropTypes.any),
  booking: PropTypes.objectOf(PropTypes.any),
};

const mapStateToProps = state => ({
  language: state.language,
  booking: state.booking,
});

export default connect(mapStateToProps, null)(EventTicket);
