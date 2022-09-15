/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {
  View,
  StyleSheet,
  TouchableOpacity,
  Dimensions,
  Platform,
} from 'react-native';
import {translate} from '../lang/Translate';
import {Calendar, CalendarList} from 'react-native-calendars';
// import {TouchableOpacity} from 'react-native-gesture-handler';
import _ from 'lodash';
import {BaseColor, GreenColor, YellowColor} from '@config';
import bookingAction from '../redux/reducers/booking/actions';
import MIcon from 'react-native-vector-icons/MaterialIcons';
import MIIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import Moment from 'moment';
import FIcon from 'react-native-vector-icons/Ionicons';
import {getPriceofDates} from '@utils/booking';
import {getApiData} from '../utils/apiHelper';
import CAlert from '../components/CAlert';
import {BaseSetting} from '../config/setting';
import moment from 'moment';
import categoryName, {periodTypes} from '../config/category';
import CalendarDayComponent from '../components/CustomDay';
import {Text} from '@components';
import LottieView from 'lottie-react-native';
import CLoader from './CLoader';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import FilterActions from '../redux/reducers/filter/actions';
import {
  getPeriodsFromPrice,
  getCurrentFilterType,
  getDefaultPeriodofType,
} from 'app/utils/booking';
import * as Utils from '@utils';
import {setIntervalTime} from 'app/utils/CommonFunction';

const styles = StyleSheet.create({
  container: {
    width: '100%',
    backgroundColor: '#fff',
    borderRadius: 10,
    overflow: 'hidden',
    position: 'relative',
    height: Dimensions.get('window').height * 0.69 + 10,
  },
  topView: {
    width: '100%',
    flexDirection: 'row',
    // height: 40,
    padding: 10,
    justifyContent: 'space-between',
    // alignItems: 'center',
    borderBottomWidth: 1,
    borderColor: '#ddd',
    backgroundColor: '#fff',
    // shadowColor: '#000',
    // shadowOffset: {
    //   width: 0,
    //   height: 6,
    // },
    // shadowOpacity: 0.41,
    // shadowRadius: 9.32,
    // elevation: 6,
  },
  titleText: {
    color: BaseColor.primaryColor,
    fontSize: 16,
    // fontWeight: 'bold',
    // flex: 1,
    textAlign: 'center',
    paddingBottom: 5,
  },
  bottomView: {
    width: '100%',
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  morningView: {
    width: 120,
    borderRadius: 5,
    marginRight: 10,
    backgroundColor: '#f9d71c',
    flexDirection: 'row',
    paddingVertical: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  eveningView: {
    width: 120,
    borderRadius: 5,
    backgroundColor: '#000',
    flexDirection: 'row',
    paddingVertical: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  fullDayView: {
    width: '100%',
    borderRadius: 5,
    padding: 20,
    flexDirection: 'row',
  },
  fullDayText: {
    flex: 1,
    color: '#FFF',
    fontSize: 17,
    textAlign: 'center',
  },
  viewIndicator: {
    height: 10,
    width: 10,
    borderRadius: 5,
    overflow: 'hidden',
  },
  indicatorContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 5,
  },
  calendarFooter: {
    marginTop: 15,
    padding: 10,
    backgroundColor: '#fff',
    // shadowColor: BaseColor.primaryColor,
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 6,
    },
    shadowOpacity: 0.41,
    shadowRadius: 9.32,
    elevation: 6,
  },
  footerContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  doneBtn: {
    backgroundColor: BaseColor.primaryColor,
    padding: 10,
    borderRadius: 5,
  },
  periodWrapper: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    // borderWidth: 1,
    borderColor: BaseColor.lightPrimaryColor,
    // paddingVertical: 5,
    // paddingHorizontal: 7,
    marginTop: 5,
    borderRadius: 3,
  },
  singlePeriod: {
    // backgroundColor: 'red',
    paddingVertical: 5,
    borderRadius: 3,
    paddingHorizontal: 7,
  },
  animationWrap: {
    position: 'absolute',
    top: -100,
    left: -20,
    height: Dimensions.get('window').height,
    width: Dimensions.get('window').width,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'rgba(0,0,0,0.3)',
  },
  animation: {
    width: 150,
    height: 150,
  },
  contentActionCalendar: {
    padding: 5,
  },
  innerCardWrapper: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginVertical: 3,
  },
});

class CCalendar extends Component {
  constructor(props) {
    super(props);
    /* Get selected Period Type from Props */
    const periodType = props.periodType ? props.periodType : periodTypes[0];
    // CAlert(` ${this.props.selectedDate}`);
    this.state = {
      dateSelected: this.props.selectedDate,
      // startingDay: this.props.startDate || this.props.selectedDate,
      startingDay: '',
      endingDay: '',
      // endingDay: this.props.endingDay || this.props.selectedDate,
      currentMonth: new Date().getMonth() + 1,
      currentYear: new Date().getFullYear(),
      selectedMonth: {
        year: moment(this.props.selectedDate).year(),
        month: moment(this.props.selectedDate).month() + 1,
        dateString: this.props.selectedDate,
      },
      offers: [],
      disableDates: [],
      reservations: [],
      disabledDatesArray: [],
      pageLoad: true,
      activeType: periodType,
      loading: this.props.fromFilter ? false : true,
      totalPrice: 0,
      totalDownpayment: 0,
      checkedPeriod: periodType,
      pickPeriod: false,
      activeStartPeriod: periodType,
      activeEndPeriod: periodType,
    };
  }

  enumerateDaysBetweenDates = (startDate, endDate) => {
    var dates = [];

    var currDate = moment(startDate).startOf('day');
    var lastDate = moment(endDate).startOf('day');
    dates.push(currDate.format('YYYY-MM-DD'));
    while (currDate.add(1, 'days').diff(lastDate) < 0) {
      dates.push(currDate.format('YYYY-MM-DD'));
    }
    dates.push(lastDate.format('YYYY-MM-DD'));
    return dates;
  };

  getMarkedDates() {
    let markedObj = {};

    const selectedProps = {
      selected: true,
      selectedColor: BaseColor.primaryColor,
    };

    const {startingDay, endingDay, disabledDatesArray} = this.state;

    markedObj = {
      [moment(startingDay).format('YYYY-MM-DD')]: {
        ...selectedProps,
        startingDay: true,
      },
    };

    if (startingDay === endingDay) {
      markedObj[moment(startingDay).format('YYYY-MM-DD')].endingDay = true;
    } else if (!_.isEmpty(endingDay)) {
      markedObj[moment(endingDay).format('YYYY-MM-DD')] = {
        ...selectedProps,
        endingDay: true,
      };
    }

    if (
      !_.isEmpty(startingDay) &&
      !_.isEmpty(endingDay) &&
      startingDay !== endingDay
    ) {
      for (
        var m = moment(startingDay);
        m.isBefore(endingDay);
        m.add(1, 'days')
      ) {
        if (
          m.format('YYYY-MM-DD') === moment(startingDay).format('YYYY-MM-DD')
        ) {
          continue;
        }
        markedObj[m.format('YYYY-MM-DD')] = {
          ...selectedProps,
        };
      }
    }
    if (!this.props.fromFilter) {
      disabledDatesArray.map(date => {
        markedObj[date] = {disabled: true, disableTouchEvent: true};
      });
    } else {
      markedObj;
    }
    return markedObj;
  }

  /* From active start */
  getDefaultPeriod = () => {
    const {activeStartPeriod} = this.state;
    if (activeStartPeriod && activeStartPeriod[0]) {
      return activeStartPeriod[0];
    }
    return {id: 1, title: 'Morning'};
  };

  setSavedData = () => {
    const {
      booking: {bookingData},
      fromFilter,
    } = this.props;
    const {
      filter: {filterDataType},
      filter,
    } = this.props;

    const startDate =
      filter &&
      _.has(filter, `${getCurrentFilterType(filterDataType)}.startDate`)
        ? moment(filter[getCurrentFilterType(filterDataType)].startDate)
        : '';
    const endDate =
      filter && _.has(filter, `${getCurrentFilterType(filterDataType)}.endDate`)
        ? moment(filter[getCurrentFilterType()].endDate)
        : '';
    let startPeriod =
      filter &&
      _.has(filter, `${getCurrentFilterType(filterDataType)}.startPeriod`) &&
      !_.isEmpty(filter[getCurrentFilterType(filterDataType)].startPeriod)
        ? filter[getCurrentFilterType(filterDataType)].startPeriod
        : this.getDefaultPeriod();
    let endPeriod =
      filter &&
      _.has(filter, `${getCurrentFilterType(filterDataType)}.endPeriod`) &&
      !_.isEmpty(filter[getCurrentFilterType(filterDataType)].endPeriod)
        ? filter[getCurrentFilterType(filterDataType)].endPeriod
        : this.getDefaultPeriod();

    const periodType = this.props.periodType
      ? this.props.periodType
      : periodTypes[0];
    const fromDate =
      bookingData && bookingData.startingDate
        ? bookingData.startingDate
        : startDate;
    const toDate =
      bookingData && bookingData.endingDate ? bookingData.endingDate : endDate;
    const tPrice =
      bookingData && bookingData.totalPrice ? bookingData.totalPrice : 0;
    const sPeriod =
      bookingData &&
      bookingData.startPeriod &&
      !_.isEmpty(bookingData.startPeriod)
        ? bookingData.startPeriod
        : periodType;
    const ePeriod =
      bookingData && bookingData.endPeriod && !_.isEmpty(bookingData.endPeriod)
        ? bookingData.endPeriod
        : periodType;
    const pType =
      bookingData && bookingData.periodType
        ? bookingData.periodType
        : [this.getDefaultPeriod()];
    // const fDates = parseDate(fromDate, toDate);

    this.setState({
      startingDay: Platform.select({
        ios: moment(fromDate).format('YYYY-MM-DD'),
        android: moment(fromDate, 'DD MMM YYYY').format('YYYY-MM-DD'),
      }),
      endingDay: Platform.select({
        ios: moment(toDate).format('YYYY-MM-DD'),
        android: moment(toDate, 'DD MMM YYYY').format('YYYY-MM-DD'),
      }),
      activeType: pType,
      totalPrice: tPrice,
      activeStartPeriod:
        startPeriod && !_.isEmpty(startPeriod)
          ? startPeriod
          : _.isObject(sPeriod) && !_.isEmpty(sPeriod)
          ? sPeriod
          : sPeriod[0],
      activeEndPeriod:
        endPeriod && !_.isEmpty(endPeriod)
          ? endPeriod
          : _.isObject(ePeriod) && !_.isEmpty(ePeriod)
          ? ePeriod
          : ePeriod[0],
    });
  };

  componentDidMount() {
    const {childCalRef} = this.props;
    if (childCalRef) {
      childCalRef(this);
    }
    const {fromFilter} = this.props;
    if (!fromFilter) {
      this.concurrentAPICalls();
    }
    this.setSavedData();
  }

  async concurrentAPICalls() {
    const offres = await this.getOffersAPICall();
    const disableDates = await this.getDisableDaysAPICall();
    const reservations = await this.getReservationAPICall();

    if (
      _.isArray(offres) &&
      _.isArray(disableDates) &&
      _.isArray(reservations)
    ) {
      const disabledDatesArray = this.getDisableDates(
        reservations,
        disableDates,
      );
      this.setState({
        pageLoad: false,
        offers: offres,
        disableDates: disableDates,
        reservations: reservations,
        disabledDatesArray,
        loading: false,
      });
    } else {
      CAlert(
        translate('Loading_Error'),
        translate('Error'),
        () => {
          this.concurrentAPICalls();
        },
        null,
        translate('Retry'),
      );
    }
  }

  getOffersAPICall = () => {
    return new Promise((resolve, reject) => {
      const {isConnected, category, itemID} = this.props;

      if (isConnected) {
        let UrlString =
          BaseSetting.endpoints[getCurrentFilterType(category, 'offers')];
        let data = {
          [getCurrentFilterType(category, 'id')]: itemID,
        };

        getApiData(UrlString, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (
                _.isBoolean(result.status) &&
                result.status === true &&
                _.isArray(result.data)
              ) {
                resolve(result.data);
              } else {
                if (
                  _.isString(result.message) &&
                  result.message === 'No data found'
                ) {
                  resolve([]);
                } else {
                  resolve(false);
                }
              }
            } else {
              resolve(false);
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

  getDisableDaysAPICall = () => {
    return new Promise((resolve, reject) => {
      const {isConnected, itemID, category} = this.props;

      if (isConnected) {
        let UrlString =
          BaseSetting.endpoints[getCurrentFilterType(category, 'disables')];
        let data = {
          [getCurrentFilterType(category, 'id')]: itemID,
        };

        getApiData(UrlString, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (
                _.isBoolean(result.status) &&
                result.status === true &&
                _.isArray(result.data)
              ) {
                resolve(result.data);
              } else {
                if (
                  _.isString(result.message) &&
                  result.message === 'No data found'
                ) {
                  resolve([]);
                } else {
                  resolve(false);
                }
              }
            } else {
              resolve(false);
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

  getReservationAPICall = () => {
    return new Promise((resolve, reject) => {
      const {isConnected, itemID, category} = this.props;

      if (isConnected) {
        let UrlString = '';
        let data = {};

        if (category === categoryName.pools) {
          UrlString = BaseSetting.endpoints.poolReservation;
          data = {poolId: itemID};
        } else if (category === categoryName.chalets) {
          UrlString = BaseSetting.endpoints.chaletsReservation;
          data = {chaletId: itemID};
        } else if (category === categoryName.camps) {
          UrlString = BaseSetting.endpoints.campssReservation;
          data = {campId: itemID};
        }

        getApiData(UrlString, 'post', data)
          .then(result => {
            if (_.isObject(result)) {
              if (
                _.isBoolean(result.status) &&
                result.status === true &&
                _.isArray(result.data)
              ) {
                let data = result.data;
                if (
                  _.isArray(data) &&
                  !_.isEmpty(data) &&
                  _.has(data, '[0].date')
                ) {
                  data = _.orderBy(result.data, ['date'], ['asc']);
                }
                resolve(data);
              } else {
                if (
                  _.isString(result.message) &&
                  result.message === 'No data found'
                ) {
                  resolve([]);
                } else {
                  resolve(false);
                }
              }
            } else {
              reject(null);
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

  setDays = days => {
    const {
      startingDay,
      endingDay,
      checkedPeriod,
      reservations,
      disableDates,
      activeStartPeriod,
      activeEndPeriod,
    } = this.state;
    const markedDates = this.getMarkedDates();

    const newDates = {
      startingDay,
      endingDay,
    };

    if (
      (!_.isEmpty(startingDay) && !_.isEmpty(endingDay)) ||
      (_.isEmpty(startingDay) && _.isEmpty(endingDay)) ||
      moment(days.dateString) < moment(startingDay)
    ) {
      newDates.startingDay = days.dateString;
      newDates.endingDay = null;
    } else if (_.isEmpty(endingDay)) {
      let anyBooked = false;
      const _tmpEndingDate = days.dateString;
      for (
        var m = moment(startingDay);
        m.isBefore(days.dateString);
        m.add(1, 'days')
      ) {
        reservations.map(obj => {
          /* Any Reservation beween Starting and Ending Date */
          if (
            moment(obj.date).isBetween(startingDay, _tmpEndingDate, null, '()')
          ) {
            anyBooked = true;
          }

          /* If user select end date and it has a reservation in morning */
          if (
            moment(obj.date).isSame(_tmpEndingDate) &&
            obj.end_period === '1'
          ) {
            anyBooked = true;
          }
        });

        disableDates.map(obj => {
          let isBetween = moment(obj.start_date).isBetween(
            startingDay,
            _tmpEndingDate,
            null,
            '()',
          );

          /* If Only disabled single evening period &  Disable matches Start date & End date is not same as Start date */
          if (
            moment(obj.start_date).isSame(startingDay) &&
            obj.period === '2' &&
            moment(obj.start_date).isBefore(_tmpEndingDate)
          ) {
            isBetween = true;
          }

          /* If Disable period start date is same as Ending Date and If Morning Period is disabled */
          if (
            moment(obj.start_date).isSame(_tmpEndingDate) &&
            obj.period === '1'
          ) {
            isBetween = true;
          }

          if (isBetween) {
            anyBooked = true;
          }
        });

        if (m.format('YYYY-MM-DD') === startingDay) {
          continue;
        }
        if (
          markedDates[m.format('YYYY-MM-DD')] &&
          markedDates[m.format('YYYY-MM-DD')].disabled
        ) {
          anyBooked = true;
          break;
        }
      }

      if (anyBooked) {
        newDates.startingDay = days.dateString;
      } else {
        newDates.endingDay = days.dateString;
      }
    }

    const startAP = this.getAvailablePeriods(
      newDates.startingDay,
      newDates.startingDay,
    );

    /* If currently selected period is not available in starting date */
    if (
      (activeStartPeriod.id === 1 && !startAP.morningEnable) ||
      (activeStartPeriod.id === 2 && !startAP.eveningEnable)
    ) {
      newDates.activeStartPeriod = {};
    }

    if (!_.isEmpty(newDates.endingDay)) {
      const endAP = this.getAvailablePeriods(
        newDates.endingDay,
        newDates.endingDay,
      );
      /* If currently selected period is not available in ending date */
      if (
        (activeEndPeriod.id === 1 && !endAP.morningEnable) ||
        (activeEndPeriod.id === 2 && !endAP.eveningEnable)
      ) {
        newDates.activeEndPeriod = {};
      }
    }

    /* On date change - Unselect period that's not available */
    Utils.enableExperimental();
    this.setState(newDates);
  };

  handlePeriodChange = (period, index) => {
    const {checkedPeriod} = this.state;
    const checked = [...checkedPeriod];
    if (checked.indexOf(period) >= 0) {
      checked.splice(checked.indexOf(period), 1);
    } else if (checked.indexOf(period) === -1) {
      checked.push(period);
    }
    this.setState({checkedPeriod: checked});
  };

  /* getDefaultPeriods From Price Data */
  getDefaultPeriods = () => {
    const {dayPrices} = this.props;
    const availablePeriodsFromPrice = getPeriodsFromPrice(dayPrices);
    const aP = [];
    if (availablePeriodsFromPrice.M) {
      aP.push(periodTypes[0]);
    }
    if (availablePeriodsFromPrice.E) {
      aP.push(periodTypes[1]);
    }
    if (availablePeriodsFromPrice.F) {
      aP.push(periodTypes[2]);
    }
    return aP;
  };

  /* Logic to get available periods extract from Render */
  getAvailablePeriods = (startD = null, endD = null) => {
    const {reservations, disableDates} = this.state;
    const aP = {
      fulldayEnable: true,
      morningEnable: true,
      eveningEnable: true,
    };

    const startingDay = startD !== null ? startD : this.state.startingDay;
    const endingDay = startD !== null ? endD : this.state.endingDay;

    disableDates.map(obj => {
      // let range = moment().range(obj.start_date, obj.end_date);
      let isContain =
        moment(startingDay).isBetween(
          obj.start_date,
          obj.end_date,
          null,
          '[]',
        ) ||
        moment(endingDay).isBetween(obj.start_date, obj.end_date, null, '[]') ||
        moment(obj.start_date).isBetween(startingDay, endingDay, null, '[]') ||
        moment(obj.end_date).isBetween(startingDay, endingDay, null, '[]');

      if (isContain && obj.period === '3' && obj.state === '1') {
        aP.fulldayEnable = false;
      }
      if (isContain && obj.period === '1' && obj.state === '1') {
        aP.morningEnable = false;
      }
      if (isContain && obj.period === '2' && obj.state === '1') {
        aP.eveningEnable = false;
      }
    });

    reservations.map(obj => {
      if (
        (moment(startingDay).isSame(obj.date) ||
          moment(startingDay).isSame(obj.end_date) ||
          moment(startingDay).isBetween(obj.date, obj.end_date, null, '[]')) &&
        obj.state === '1'
      ) {
        /* What if date is between start and end date of reservation??
        No need to care as it's already been disabled from DisabledDates */

        /* If Date is Matching with Reservation Start day */
        if (moment(startingDay).isSame(obj.date)) {
          if (obj.period === '1') {
            aP.morningEnable = false;
          }
          if (obj.period === '2') {
            aP.eveningEnable = false;
          }
        }

        /* If Date is Matching with Reservation end day */
        if (moment(startingDay).isSame(obj.end_date)) {
          if (obj.end_period === '1') {
            aP.morningEnable = false;
          }
          if (obj.end_period === '2') {
            aP.eveningEnable = false;
          }
        }
      }
    });

    return aP;
  };

  /* Logic to get disable dates based on Disables from Admin and reservations */
  getDisableDates = (reservations = null, disableDates = null) => {
    const debugThis = true;
    if (reservations === null) {
      reservations = this.state.reservations;
    }
    if (disableDates === null) {
      disableDates = this.state.disableDates;
    }
    const disableDatesArray = [];
    const datePeriodBooked = {};
    disableDates.map(obj => {
      let dates = _.uniq(
        this.enumerateDaysBetweenDates(obj.start_date, obj.end_date),
      );
      if (dates.length === 1 && _.toNumber(obj.period) !== 3) {
        datePeriodBooked[obj.start_date] = {period: obj.period};
      } else {
        dates.map(date => {
          if (_.toNumber(obj.period) === 3) {
            disableDatesArray.push(date);
          }
        });
      }
    });

    if (debugThis) {
      console.log('DEBUG DISABLED: Reservations => ', reservations);
    }

    reservations.map(obj => {
      let dates = _.uniq(
        this.enumerateDaysBetweenDates(obj.date, obj.end_date),
      );
      dates.map((date, key) => {
        if (dates.length === 1) {
          if (
            datePeriodBooked[date] &&
            datePeriodBooked[date].period &&
            datePeriodBooked[date].period !== obj.period
          ) {
            disableDatesArray.push(date);
          } else {
            datePeriodBooked[date] = {period: obj.period};
          }
        }
        if (key === 0 && _.toNumber(obj.period) === 2) {
          if (
            datePeriodBooked[date] &&
            datePeriodBooked[date].period &&
            datePeriodBooked[date].period !== obj.period
          ) {
            disableDatesArray.push(date);
          }
          return;
        } else if (
          key === dates.length - 1 &&
          _.toNumber(obj.end_period) === 1
        ) {
          datePeriodBooked[date] = {period: obj.end_period};
          return;
        } else {
          disableDatesArray.push(date);
        }
      });
    });

    const {
      morningStartTime,
      eveningStartTime,
      startTime,
      intervalTime,
    } = this.props;
    let {isEveningDisable, isFullDayDisable} = setIntervalTime(
      morningStartTime,
      eveningStartTime,
      startTime,
      intervalTime,
      moment(),
    );

    if (isEveningDisable || isFullDayDisable) {
      disableDatesArray.push(moment().format('YYYY-MM-DD'));
    }

    return disableDatesArray;
    // return aP;
  };

  handleBottomButtons = () => {
    const {activeType, checkedPeriod} = this.state;
    const {dayPrices} = this.props;

    /* Disable Periods if Price not available */
    const availablePeriodsFromPrice = getPeriodsFromPrice(dayPrices);
    let fDisable = false;
    /* No need to show periods for Morning / Evening */
    if (availablePeriodsFromPrice && !availablePeriodsFromPrice.F) {
      return;
    }
    return (
      <View>
        <Text caption2>{translate('period')}</Text>
        <View style={styles.periodWrapper}>
          {periodTypes &&
            periodTypes.map((item, i) => {
              const sType =
                checkedPeriod.indexOf(item) >= 0 || activeType.length >= 2;
              let isDisabled = fDisable;
              const color = isDisabled ? '#999' : sType ? '#FFF' : '#212121';
              if (item.id === 3) {
                return (
                  <TouchableOpacity
                    activeOpacity={0.8}
                    disabled={isDisabled}
                    style={[
                      styles.singlePeriod,
                      {
                        borderWidth: 1,
                        borderColor: isDisabled
                          ? '#CCC'
                          : BaseColor.primaryColor,
                        backgroundColor: isDisabled
                          ? '#CCC'
                          : sType
                          ? BaseColor.primaryColor
                          : BaseColor.whiteColor,
                        marginRight: 5,
                      },
                    ]}>
                    <Text caption2 medium style={{color}}>
                      {item.title}
                    </Text>
                  </TouchableOpacity>
                );
              }
            })}
        </View>
      </View>
    );
  };

  onPressed = (onPress, date) => {
    requestAnimationFrame(() => onPress(date));
  };

  onDonePress = () => {
    const {
      startingDay,
      endingDay,
      activeType,
      totalPrice,
      totalDownpayment,
      checkedPeriod,
      activeStartPeriod,
      activeEndPeriod,
    } = this.state;

    if (_.isEmpty(checkedPeriod)) {
      return;
    }
    const {
      bookingAction: {setBookingData},
      booking: {bookingData},
      onClose,
    } = this.props;
    const bData = {};
    bData.startingDate = startingDay ? startingDay : '';
    bData.endingDate = endingDay ? endingDay : '';
    bData.periodType = checkedPeriod ? checkedPeriod : [];
    bData.totalPrice = totalPrice ? totalPrice : 0;
    bData.totalDownpayment = totalDownpayment ? totalDownpayment : 0;
    bData.startPeriod = activeStartPeriod ? activeStartPeriod : {};
    bData.endPeriod = activeEndPeriod ? activeEndPeriod : {};
    setBookingData(bData);
    onClose();
  };

  renderPeriod = type => {
    const {
      activeStartPeriod,
      activeEndPeriod,
      startingDay,
      endingDay,
    } = this.state;

    let periodDate = startingDay;

    let activePeriod = activeStartPeriod;
    if (type === 'end') {
      activePeriod = activeEndPeriod;
      periodDate = endingDay;
    }

    const {
      morningStartTime,
      eveningStartTime,
      startTime,
      intervalTime,
    } = this.props;
    let {isMorningDisable, isEveningDisable} = setIntervalTime(
      morningStartTime,
      eveningStartTime,
      startTime,
      intervalTime,
      periodDate,
    );

    if (!isMorningDisable || !isEveningDisable) {
      const dateTocheck = type === 'start' ? startingDay : endingDay;
      const bookingPeriods = this.getAvailablePeriods(dateTocheck, dateTocheck);
      if (!isMorningDisable) {
        isMorningDisable = !bookingPeriods.morningEnable;
      }
      if (!isEveningDisable) {
        isEveningDisable = !bookingPeriods.eveningEnable;
      }
    }

    return (
      <View style={styles.periodWrapper}>
        {periodTypes &&
          periodTypes.map(item => {
            const sPeriod = _.isEqual(activePeriod, item);
            const isDisabled =
              item.id === 1 ? isMorningDisable : isEveningDisable;
            // const color1 = isDisabled ? '#999' : sType ? '#FFF' : '#212121';
            const color = isDisabled
              ? '#999'
              : !sPeriod
              ? BaseColor.primaryColor
              : BaseColor.whiteColor;
            if (item.id <= 2) {
              return (
                <TouchableOpacity
                  activeOpacity={0.8}
                  disabled={isDisabled}
                  style={[
                    styles.singlePeriod,
                    {
                      borderWidth: 1,
                      borderColor: isDisabled ? '#999' : BaseColor.primaryColor,
                      backgroundColor: isDisabled
                        ? '#DDD'
                        : sPeriod
                        ? BaseColor.primaryColor
                        : BaseColor.whiteColor,
                      marginRight: 5,
                    },
                  ]}
                  onPress={() => {
                    if (type === 'start') {
                      this.setState({activeStartPeriod: item});
                    } else {
                      this.setState({activeEndPeriod: item});
                    }
                  }}>
                  <Text caption2 medium style={{color}}>
                    {translate(item.title)}
                  </Text>
                </TouchableOpacity>
              );
            }
          })}
      </View>
    );
  };

  render() {
    const {
      onClose,
      dayPrices,
      morningStartTime,
      eveningStartTime,
      startTime,
      intervalTime,
      filter,
    } = this.props;
    const {
      dateSelected,
      selectedMonth,
      startingDay,
      endingDay,
      checkedPeriod,
      activeStartPeriod,
      activeEndPeriod,
    } = this.state;

    /* Get current by period */
    const cPeriod =
      filter &&
      _.has(filter, `${getCurrentFilterType(filter.filterDataType)}.byPeriod`)
        ? filter[getCurrentFilterType(filter.filterDataType)].byPeriod
        : getDefaultPeriodofType('poolFilters');

    let dayPrice = {};
    if (!this.props.fromFilter) {
      dayPrice = getPriceofDates(
        startingDay,
        endingDay,
        {
          activeStartPeriod,
          activeEndPeriod,
        },
        dayPrices,
      );
    }
    let mDisable = false;
    let eDisable = false;
    let fDisable = false;
    if (!this.props.fromFilter) {
      const intervalDisable = setIntervalTime(
        morningStartTime,
        eveningStartTime,
        startTime,
        intervalTime,
        this.state.startingDay,
      );

      mDisable = intervalDisable && intervalDisable.isMorningDisable;
      eDisable = intervalDisable && intervalDisable.isEveningDisable;
      fDisable = intervalDisable && intervalDisable.isFullDayDisable;
    }
    // CAlert(`Start ${moment(startingDay).isAfter(moment())}`);
    let headerText = '';
    if (moment(startingDay).isAfter(moment())) {
      headerText = Platform.select({
        ios: Moment(startingDay, 'YYYY-MM-DD').format('DD MMMM YYYY'),
        android: Moment(startingDay, 'YYYY-MM-DD').format('DD MMM YYYY'),
      });
    }
    // CAlert(`header ${headerText}`);
    // CAlert();

    let disableLeft = true;
    if (
      selectedMonth.month > moment().month() + 1 &&
      selectedMonth.year >= moment().year()
    ) {
      disableLeft = false;
    }
    let disableRight = true;
    if (selectedMonth.month < moment().month() + 4) {
      disableRight = false;
    }
    const markedDates = this.getMarkedDates();
    var a = moment(startingDay);
    var b = moment(endingDay);
    let diffDate = 0;
    if (
      startingDay &&
      !_.isEmpty(startingDay) &&
      endingDay &&
      !_.isEmpty(endingDay) &&
      !_.isEqual(startingDay, endingDay)
    ) {
      diffDate = b.diff(a, 'days') + 1;
      headerText = `${Moment(startingDay, 'YYYY-MM-DD').format(
        'MMM DD',
      )} - ${Moment(endingDay, 'YYYY-MM-DD').format('MMM DD')}`;
    }
    // CAlert(startingDay);

    const calendarWidth = Dimensions.get('window').width - 60;
    let hText = `${translate('booking_duration')} 1 ${translate('day')}`;
    if (!this.props.fromFiter && diffDate > 0) {
      hText = `Selected days ${diffDate}`;
    } else if (!this.props.fromFiter) {
      hText = '1 Day Selected';
    } else if (diffDate > 0) {
      hText = `${translate('booking_duration')} ${diffDate} ${translate(
        'days',
      )}`;
    }

    let saveDisable =
      dayPrice.dPrice === 0 ||
      _.isEmpty(activeStartPeriod) ||
      _.isEmpty(activeEndPeriod) ||
      (mDisable && activeStartPeriod && activeStartPeriod.id === 1) ||
      eDisable ||
      fDisable;
    if (checkedPeriod && checkedPeriod[0] && checkedPeriod[0].id === 3) {
      saveDisable = dayPrice.dPrice === 0 || mDisable || eDisable || fDisable;
    }
    let cDate = moment(dateSelected).format('YYYY-MM-DD');

    if (dateSelected) {
      cDate = Platform.select({
        ios: dateSelected,
        android: moment(dateSelected).format('YYYY-MM-DD'),
      });
    } else {
      cDate = Platform.select({
        ios: selectedMonth.dateString,
        android: moment(selectedMonth.dateString).format('YYYY-MM-DD'),
      });
      cDate = selectedMonth.dateString;
    }

    const seperatorHor = (
      <View
        style={{
          height: 1,
          width: '100%',
          backgroundColor: BaseColor.textSecondaryColor,
          marginVertical: 10,
        }}
      />
    );
    const availablePeriodsFromPrice = getPeriodsFromPrice(dayPrices);

    const showEndDate =
      endingDay && moment(endingDay).format('DD MM YYYY') !== 'Invalid date';

    const currentDate = moment();
    var futureMonth = moment(currentDate).add(3, 'M');
    const futureMonthEnd = moment(futureMonth).endOf('month');

    if (
      currentDate.date() != futureMonth.date() &&
      futureMonth.isSame(futureMonthEnd.format('YYYY-MM-DD'))
    ) {
      futureMonth = futureMonth.add(1, 'd');
    }

    return (
      <View style={styles.container}>
        <View style={styles.topView}>
          <View />
          <View style={{alignItems: 'center'}}>
            {moment(startingDay).format('DD MM YYYY') !== 'Invalid date' && (
              <Text style={styles.titleText} primaryColor>
                {headerText}
              </Text>
            )}
            {moment(startingDay).format('DD MM YYYY') !== 'Invalid date' &&
              !_.isEmpty(headerText) && (
                <>
                  {diffDate > 0 ? (
                    <Text caption2 regular>
                      {translate('booking_duration')} {diffDate}{' '}
                      {translate('days')}
                    </Text>
                  ) : (
                    <Text caption2 regular>
                      {translate('booking_duration')} 1 {translate('day')}
                    </Text>
                  )}
                </>
              )}
          </View>
          {!this.props.fromFilter ? (
            <TouchableOpacity onPress={onClose}>
              <MIcon name="close" size={21} color={BaseColor.primaryColor} />
            </TouchableOpacity>
          ) : (
            <View />
          )}
        </View>
        <View
          style={{
            flex: 1,
            width: calendarWidth,
            paddingHorizontal: 10,
          }}>
          <CalendarList
            scrollEnabled={true}
            pastScrollRange={0}
            futureScrollRange={3}
            showScrollIndicator={false}
            minDate={currentDate.format('YYYY-MM-DD')}
            maxDate={futureMonth.format('YYYY-MM-DD')}
            // current={'2020-05-06'}
            // current={cDate}
            // current={dateSelected ? dateSelected : selectedMonth.dateString}
            disableArrowLeft={disableLeft}
            disableArrowRight={disableRight}
            dayComponent={({date, state}) => {
              return (
                <CalendarDayComponent
                  fromFilter={this.props.fromFilter}
                  currencySymbol={this.props.currency}
                  date={date}
                  state={state}
                  markedDates={markedDates}
                  setDays={this.setDays}
                  dayPrices={dayPrices}
                  selectedStartDay={startingDay}
                  selectEndDay={endingDay}
                  periodType={{
                    activeStartPeriod,
                    activeEndPeriod,
                  }}
                  defaultPeriods={this.getDefaultPeriods()}
                />
              );
            }}
            // Handler which gets executed when press arrow icon left. It receive a callback can go back month
            onPressArrowLeft={substractMonth => {
              if (
                selectedMonth.month > moment().month() + 1 &&
                selectedMonth.year >= moment().year()
              ) {
                substractMonth();
              }
            }}
            // Handler which gets executed when press arrow icon right. It receive a callback can go next month
            onPressArrowRight={addMonth => {
              if (selectedMonth.month < moment().month() + 4) {
                addMonth();
              }
            }}
            // onDayPress={day => {
            //   this.setState({dateSelected: day.dateString});
            // }}
            theme={{
              arrowColor: BaseColor.primaryColor,
              todayTextColor: BaseColor.primaryColor,
              'stylesheet.calendar-list.main': {
                container: {
                  width: calendarWidth,
                },
                calendar: {
                  width: calendarWidth,
                },
                placeholder: {
                  width: calendarWidth,
                  alignItems: 'center',
                  justifyContent: 'center',
                },
                // placeholderText: {
                //   fontSize: 12,
                // },
              },
              'stylesheet.calendar.main': {
                container: {
                  width: calendarWidth,
                },
                monthView: {
                  width: calendarWidth,
                },
                week: {
                  marginTop: 0,
                  marginBottom: 0,
                  flexDirection: 'row',
                  justifyContent: 'space-around',
                  width: '100%',
                },
              },
            }}
            markedDates={this.getMarkedDates()}
            markingType={'period'}
          />
        </View>
        <View style={styles.calendarFooter}>
          {availablePeriodsFromPrice &&
            !availablePeriodsFromPrice.F &&
            cPeriod !== 'Full Day' &&
            moment(startingDay).format('DD MM YYYY') !== 'Invalid date' &&
            moment(startingDay).isAfter(moment()) && (
              <View style={styles.calCardWrapper}>
                <View style={styles.innerCardWrapper}>
                  <View>
                    <Text grayColor caption2>
                      {translate('From')}
                    </Text>
                    <Text>
                      {moment(startingDay).format('ddd, DD MMMM YYYY')}
                    </Text>
                  </View>
                  {this.renderPeriod('start')}
                </View>
                {showEndDate && seperatorHor}
                {showEndDate && (
                  <View style={styles.innerCardWrapper}>
                    <View>
                      <Text grayColor caption2>
                        {translate('To')}
                      </Text>
                      <Text>
                        {showEndDate
                          ? moment(endingDay).format('ddd, DD MMMM YYYY')
                          : 'Select end date'}
                      </Text>
                    </View>
                    {showEndDate && this.renderPeriod('end')}
                  </View>
                )}
                {seperatorHor}
              </View>
            )}
          {!this.props.fromFilter ? (
            <View style={styles.footerContent}>
              {this.handleBottomButtons()}
              {dayPrice && dayPrice.dPrice > 0 && dateSelected !== '' ? (
                <View>
                  <Text caption2>{translate('total_price')}</Text>
                  <Text callout semibold>
                    {dayPrice.dPrice} {this.props.currency}
                  </Text>
                </View>
              ) : (
                <View />
              )}
              <TouchableOpacity
                onPress={() => {
                  this.setState(
                    {
                      totalPrice: dayPrice.dPrice,
                      totalDownpayment: dayPrice.downPayment,
                    },
                    () => {
                      this.onDonePress();
                    },
                  );
                }}
                disabled={saveDisable}
                style={[styles.doneBtn, {opacity: saveDisable ? 0.6 : 1}]}>
                <Text
                  style={{color: '#FFF', fontSize: 13, marginHorizontal: 10}}>
                  {translate('Save')}
                </Text>
              </TouchableOpacity>
            </View>
          ) : (
            // <View />
            <View style={[styles.footerContent, styles.contentActionCalendar]}>
              <TouchableOpacity
                onPress={() => {
                  this.props.onCancelPress(startingDay, endingDay);
                }}>
                <Text body1>{translate('Cancel')}</Text>
              </TouchableOpacity>
              {endingDay &&
              moment(endingDay).format('DD MM YYYY') !== 'Invalid date' ? (
                <TouchableOpacity
                  disabled={saveDisable}
                  onPress={() => {
                    this.props.onCalDonePress(
                      startingDay,
                      endingDay,
                      activeStartPeriod,
                      activeEndPeriod,
                    );
                  }}
                  style={[styles.doneBtn, {opacity: saveDisable ? 0.6 : 1}]}>
                  <Text
                    style={{color: '#FFF', fontSize: 13, marginHorizontal: 10}}>
                    {translate('Done')}
                  </Text>
                </TouchableOpacity>
              ) : null}
            </View>
          )}
        </View>
        {this.state.loading ? (
          <View style={[styles.animationWrap]}>
            <LottieView
              ref={animation => {
                this.animation1 = animation;
              }}
              // onAnimationFinish={() => {
              //   this.setState({showAnimation: false});
              // }}
              autoSize={false}
              style={[styles.animation]}
              source={require('@assets/lottie/loading2.json')}
              autoPlay={true}
              loop
            />
          </View>
        ) : null}
      </View>
    );
  }
}

CCalendar.propTypes = {
  selectedDate: PropTypes.string,
  reservationDates: PropTypes.array,
  pageLoad: PropTypes.bool,
  category: PropTypes.string,
  onDateSelect: PropTypes.func,
  onDateChange: PropTypes.func,
  onClose: PropTypes.func,
  isConnected: PropTypes.bool,
  itemID: PropTypes.string,
  normalPrice: PropTypes.string,
  offerPrice: PropTypes.string,
};

CCalendar.defaultProps = {
  selectedDate: '',
  reservationDates: [],
  pageLoad: true,
  category: '',
  onDateSelect: () => {},
  onDateChange: () => {},
  onClose: () => {},
  isConnected: true,
  itemID: '',
  normalPrice: '',
  offerPrice: '',
  morningStartTime: '8AM',
  eveningStartTime: '8PM',
  startTime: '',
  intervalTime: 1,
};

const mapStateToProps = state => ({
  auth: state.auth,
  language: state.language,
  filter: state.filter,
  booking: state.booking,
});

const mapDispatchToProps = dispatch => {
  return {
    bookingAction: bindActionCreators(bookingAction, dispatch),
    FilterActions: bindActionCreators(FilterActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(CCalendar);
