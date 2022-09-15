import React, {Component} from 'react';
import {
  View,
  TouchableOpacity,
  FlatList,
  Image,
  Platform,
  Dimensions,
  TouchableWithoutFeedback,
} from 'react-native';
import PropTypes from 'prop-types';
import _ from 'lodash';
import {Text, Icon} from '@components';
import styles from './styles';
import {Calendar, CalendarList} from 'react-native-calendars';
import Modal from 'react-native-modal';
import {BaseColor, FontFamily, Images} from '@config';
import MCIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import moment from 'moment';
import {connect} from 'react-redux';
import categoryName from '../../config/category';
import {translate} from '../../lang/Translate';
import CalendarDayComponent from '../../components/CustomDay';
import CCalendar from '../CCalendar';
import {bindActionCreators} from 'redux';
import FilterActions from '../../redux/reducers/filter/actions';
import {getDefaultPeriodofType, getCurrentFilterType} from 'app/utils/booking';

let selected = {
  mark: {},
  tempCurrent: '',
  tempMarked: '',
  period: '',
};
class BookingTime extends Component {
  constructor(props) {
    super(props);
    this.state = {
      periodType: [
        {
          id: '3',
          type: 'Full Day',
          icon: Images.day_night,
        },
        {
          id: '1',
          type: 'Morning',
          icon: Images.day,
        },
        {
          id: '2',
          type: 'Evening',
          icon: Images.night,
        },
      ],
      selectedPeriod: 'Morning',
      selectedDate: moment().format('DD MMM YYYY'),
      markedDates: {
        [moment().format('YYYY-MM-DD')]: {
          selected: true,
          selectedColor: BaseColor.primaryColor,
        },
      },
      currentDate: moment().format('YYYY-MM-DD'),
      modalVisible: false,
      periodmodalVisible: false,
      shoModal: false,
    };
  }

  componentDidMount() {
    console.log('Check=====');
    this.setFilters();
    /* Pass Ref of component */
    const {childRef} = this.props;
    if (childRef) {
      childRef(this);
    }
  }

  componentDidUpdate(prevProps) {
    /* If filters reset */
    if (
      _.has(this, 'props.filter.poolFilters.byPeriod') &&
      !_.isEqual(
        this.props.filter.poolFilters.byPeriod,
        prevProps.filter.poolFilters.byPeriod,
      ) &&
      _.isEmpty(this.props.filter.poolFilters.byPeriod)
    ) {
      console.log(
        'Booking time on component did update ===> Reset Periods ==>',
      );
      this.resetPeriods();
    }
  }

  setFilters = () => {
    const {
      filter,
      booking: {bookingData},
    } = this.props;
    console.log('BookingTime -> setFilters -> bookingData', bookingData);
    const nPeriod =
      filter && _.has(filter, 'poolFilters.byPeriod')
        ? filter.poolFilters.byPeriod
        : '';
    console.log(
      'BookingTime -> setFilters -> filter.poolFilters.byPeriod',
      filter.poolFilters.byPeriod,
    );

    const todayDate = moment().format('YYYY-MM-DD');
    const currentFilterType = getCurrentFilterType(filter.filterDataType);

    let nDate =
      filter && _.has(filter, `${currentFilterType}.byDate`)
        ? filter[currentFilterType].byDate
        : '';
    let startDate =
      filter && _.has(filter, `${currentFilterType}.startDate`)
        ? filter[currentFilterType].startDate
        : moment().format('DD MMM YYYY');
    let endDate =
      filter && _.has(filter, `${currentFilterType}.endDate`)
        ? filter[currentFilterType].endDate
        : moment().format('DD MMM YYYY');
    const sDate = nDate ? moment(nDate).format('YYYY-MM-DD') : todayDate;
    const showDate = nDate
      ? Platform.select({
          ios: moment(nDate).format('DD MMM YYYY'),
          android: moment(nDate).format('YYYY-MM-DD'),
        })
      : Platform.select({
          ios: moment().format('DD MMM YYYY'),
          android: moment().format('YYYY-MM-DD'),
        });
    const nMarksDate = {
      [sDate]: {selected: true, selectedColor: BaseColor.primaryColor},
    };
    console.log('StartDate===>', startDate);
    this.setState({
      selectedPeriod: nPeriod,
      selectedDate: showDate,
      currentDate: sDate,
      markedDates: nMarksDate,
      startDate,
      endDate,
      startingDay: startDate,
      endingDay: endDate,
      periodType:
        nPeriod === 'Full Day'
          ? this.state.periodType.map(item => {
              selected.period = nPeriod;
              return {
                ...item,
                checked: true,
              };
            })
          : this.state.periodType.map(item => {
              if (item.type === nPeriod) {
                selected.period = item.type;
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
  };
  getMarkedDates() {
    let markedObj = {};

    const selectedProps = {
      selected: true,
      selectedColor: BaseColor.primaryColor,
    };

    const {startingDay, endingDay} = this.state;

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
        if (m.format('YYYY-MM-DD') === startingDay) {
          continue;
        }
        markedObj[m.format('YYYY-MM-DD')] = {
          ...selectedProps,
        };
      }
    }

    this.props.disableDays.map(date => {
      markedObj[date] = {disabled: true, disableTouchEvent: true};
    });

    console.log('markedObj: ', markedObj);
    return markedObj;
  }
  setDays = days => {
    console.log('Set Days ===> ', days);
    const {startingDay, endingDay} = this.state;
    const markedDates = this.getMarkedDates();

    const newDates = {
      startingDay,
      endingDay,
    };

    console.log(
      'Set Days Before ===> ',
      startingDay,
      endingDay,
      !_.isEmpty(startingDay),
      !_.isEmpty(endingDay),
    );

    if (
      (!_.isEmpty(startingDay) && !_.isEmpty(endingDay)) ||
      (_.isEmpty(startingDay) && _.isEmpty(endingDay)) ||
      moment(days.dateString) < moment(startingDay)
    ) {
      newDates.startingDay = days.dateString;
      newDates.endingDay = null;
    } else if (_.isEmpty(endingDay)) {
      let anyBooked = false;
      for (
        var m = moment(startingDay);
        m.isBefore(days.dateString);
        m.add(1, 'days')
      ) {
        if (m.format('YYYY-MM-DD') === startingDay) {
          continue;
        }
        // console.log(m.format('YYYY-MM-DD'));
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

    console.log('Set Days After ===> ', newDates);

    this.setState(newDates);
  };
  openModal(open) {
    this.setState({
      modalVisible: open,
    });
  }

  openPeriodModal(open) {
    this.setState({
      periodmodalVisible: open,
    });
  }

  resetPeriods = () => {
    this.setState({
      periodType: this.state.periodType.map(item => {
        return {
          ...item,
          checked: false,
        };
      }),
    });
  };

  onChangePeriodType(select) {
    // If Full Day set both Morning && Evening = true
    if (select.type === 'Full Day') {
      this.setState({
        periodType: this.state.periodType.map(item => {
          // console.log('SELECTED TYPE ==>', select.type);
          selected.period = select.type;
          return {
            ...item,
            checked: true,
          };
        }),
      });
    } else {
      // If Not Full Day set any one Morning OR Evening = true
      this.setState({
        periodType: this.state.periodType.map(item => {
          if (item.type === select.type) {
            // console.log('SELECTED TYPE ==>', select.type);
            selected.period = item.type;
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
  }

  saveStartEndDate = (startingDay, endingDay, startPeriod, endPeriod) => {
    console.log(
      'CCalendar -> saveStartEndDate -> startingDay, endingDay',
      startingDay,
      endingDay,
    );
    const {
      booking: {bookingData},
    } = this.props;
    console.log('BookingTime -> setFilters -> bookingData', bookingData);
    if (startingDay && endingDay && startPeriod && endPeriod) {
      const {
        FilterActions: {setFilters},
        filter: {filterDataType, allFilters},
      } = this.props;
      const fData = allFilters && _.isObject(allFilters) ? allFilters : {};
      const pData =
        allFilters &&
        _.isObject(allFilters) &&
        allFilters[getCurrentFilterType(filterDataType, 'default')]
          ? allFilters[getCurrentFilterType(filterDataType, 'default')]
          : {};

      /* Updates Pool / Chalet / Camp filter object under All Filters */
      Object.assign(pData, {
        startDate: startingDay,
        endDate: endingDay,
        startPeriod,
        endPeriod,
      });

      fData.resetFilter = true;
      setFilters(fData);
      // console.log();
    }
  };

  saveSelectedDates = (s, e, asp, aep) => {
    const date = s ? s : this.state.currentDate;
    const eDate = e ? e : this.state.currentDate;
    this.saveStartEndDate(s, e, asp, aep);
    this.setState(
      {
        modalVisible: false,
        selectedDate: moment(date).format('DD MMM YYYY'),
        startDate: moment(s).format('DD MMM YYYY'),
        endDate: moment(e).format('DD MMM YYYY'),
        startPeriod: asp,
        endPeriod: aep,
      },
      () => {
        // const date = s ? s : this.state.currentDate;
        // const eDate = e ? e : this.state.currentDate;
        this.props.onChange('date', {
          startDate: moment(date, 'YYYY-MM-DD'),
          endDate: moment(eDate, 'YYYY-MM-DD'),
          startPeriod: asp,
          endPeriod: aep,
        });
      },
    );
  };

  render() {
    const {auth, style, showPeriod, layoutHidden} = this.props;
    const {
      modalVisible,
      markedDates,
      periodType,
      selectedPeriod,
      selectedDate,
      currentDate,
      startDate,
      endDate,
    } = this.state;
    const calendarWidth = Dimensions.get('window').width - 60;
    console.log('BookingTime -> render -> selectedDate', selectedDate);
    let todayDate = moment().format('YYYY-MM-DD');
    var maxDate = moment(todayDate, 'YYYY-MM-DD')
      .add('months', 3)
      .format('YYYY-MM-DD');
    console.log(
      'Days--->',
      startDate,
      moment(endDate).format('DD MM YYYY') === 'Invalid date',
    );
    const sDate = startDate ? startDate : this.state.currentDate;
    let diffDate = moment(sDate).format('DD MMM YYYY');
    if (
      _.isEqual(startDate, endDate) ||
      moment(endDate).format('DD MM YYYY') === 'Invalid date'
    ) {
      diffDate = moment(startDate).format('DD MMM YYYY');
    } else if (startDate && endDate) {
      var a = moment(startDate);
      var b = moment(endDate);
      diffDate = b.diff(a, 'days') + 1;
    } else {
      diffDate = moment(todayDate).format('DD MMM YYYY');
      // console.log('eDate', moment(sDate).format('DD MMM YYYY'));
    }
    console.log('diffDate==', diffDate);
    return (
      <View style={[styles.contentPickDate, style]}>
        <Modal
          isVisible={modalVisible}
          onBackdropPress={() => {
            if (this.calendaeRef) {
              const {
                filterDataType,
                chaletFilters,
                poolFilters,
                campFilters,
              } = this.props.filter;

              let prevSDate = poolFilters && poolFilters.startDate;
              let prevEDate = poolFilters && poolFilters.endDate;
              const prevSPeriod = poolFilters && poolFilters.startPeriod;
              const prevEPeriod = poolFilters && poolFilters.endPeriod;

              if (filterDataType === 'Chalets') {
                prevSDate = chaletFilters && chaletFilters.startDate;
                prevEDate = chaletFilters && chaletFilters.endDate;
              } else if (filterDataType === 'Camps') {
                prevSDate = campFilters && campFilters.startDate;
                prevEDate = campFilters && campFilters.endDate;
              }
              const calendatStates = this.calendaeRef.state;
              if (
                calendatStates.startingDay &&
                calendatStates.startingDay !== 'Invalid date' &&
                calendatStates.endingDay &&
                calendatStates.endingDay !== 'Invalid date' &&
                (!_.isEqual(prevSDate, calendatStates.startingDay) ||
                  !_.isEqual(prevEDate, calendatStates.endingDay) ||
                  (!_.isEqual(prevSPeriod, calendatStates.activeStartPeriod) &&
                    filterDataType === 'Pools') ||
                  (!_.isEqual(prevEPeriod, calendatStates.activeEndPeriod) &&
                    filterDataType === 'Pools'))
              ) {
                const stDate =
                  calendatStates && calendatStates.startingDay
                    ? calendatStates.startingDay
                    : '';
                const eDate =
                  calendatStates && calendatStates.endingDay
                    ? calendatStates.endingDay
                    : '';
                const sPeriod =
                  calendatStates && calendatStates.activeStartPeriod
                    ? calendatStates.activeStartPeriod
                    : {};
                const ePeriod =
                  calendatStates && calendatStates.activeEndPeriod
                    ? calendatStates.activeEndPeriod
                    : {};
                // this.setState({modalVisible: false});
                this.saveSelectedDates(stDate, eDate, sPeriod, ePeriod);
              } else {
                this.setState({modalVisible: false});
              }
              // this.calendaeRef.openPeriodModal(true);
            }
          }}
          backdropColor="rgba(0, 0, 0, 0.5)"
          backdropOpacity={1}
          animationIn="fadeIn"
          animationInTiming={600}
          animationOutTiming={600}
          backdropTransitionInTiming={600}
          backdropTransitionOutTiming={600}>
          <View
            style={[
              styles.contentCalendar,
              {
                height: Dimensions.get('window').height * 0.7,
                width: calendarWidth + 20,
              },
            ]}>
            <CCalendar
              childCalRef={tcmp => {
                console.log('REf ===> ', tcmp);
                this.calendaeRef = tcmp;
              }}
              fromFilter
              isConnected={auth.isConnected}
              // itemID={itemID}
              // category={selectedCategory}
              selectedDate={
                selectedDate !== ''
                  ? selectedDate
                  : moment().format('YYYY-MM-DD')
              }
              // dayPrices={dayPrices}
              onClose={() => {
                this.setState({
                  shoModal: false,
                });
              }}
              disableDays={this.props.disableDays}
              onDateSelect={async day => {
                console.log('render -> day', day);
                const pricedata = await this.getPriceAPICall(day.date);
                if (_.isObject(pricedata)) {
                  this.setState({
                    shoModal: false,
                    priceDetail: pricedata,
                    selectedDate: day.date,
                    period: day.period,
                  });
                }
              }}
              onCancelPress={(s, e) => {
                const date = s ? s : this.state.currentDate;
                const eDate = e ? e : this.state.currentDate;
                this.setState(
                  {
                    modalVisible: false,
                    currentDate: selected.tempCurrent,
                    markedDates: selected.tempMarked,
                  },
                  () => {
                    this.props.onChange(
                      'date',
                      moment(date, 'YYYY-MM-DD'),
                      moment(eDate, 'YYYY-MM-DD'),
                    );
                  },
                );
              }}
              onCalDonePress={(s, e, asp, aep) => {
                console.log('BookingTime -> render -> s, e', s, e, asp, aep);
                this.saveSelectedDates(s, e, asp, aep);
              }}
            />
            {/* <View style={styles.contentActionCalendar}>
              <TouchableOpacity
                onPress={() => {
                  this.setState(
                    {
                      modalVisible: false,
                      currentDate: selected.tempCurrent,
                      markedDates: selected.tempMarked,
                    },
                    () => {
                      this.props.onChange(
                        'date',
                        moment(this.state.currentDate, 'YYYY-MM-DD'),
                      );
                    },
                  );
                }}>
                <Text body1>Cancel</Text>
              </TouchableOpacity>
              <TouchableOpacity
                onPress={() => {
                  this.setState(
                    {
                      modalVisible: false,
                      selectedDate: moment(this.state.currentDate).format(
                        'DD MMM YYYY',
                      ),
                    },
                    () => {
                      this.props.onChange(
                        'date',
                        moment(this.state.currentDate, 'YYYY-MM-DD'),
                      );
                    },
                  );
                }}>
                <Text body1 primaryColor>
                  Done
                </Text>
              </TouchableOpacity>
            </View> */}
          </View>
        </Modal>
        <Modal
          isVisible={this.state.periodmodalVisible}
          onBackdropPress={() => {
            // this.setState({periodmodalVisible: false});
            this.setState(
              {
                periodmodalVisible: false,
                selectedPeriod: selected.period,
              },
              () => {
                this.props.onChange('period', selected.period);
              },
            );
          }}
          onSwipeComplete={() => {
            // this.setState({periodmodalVisible: false});
            this.setState(
              {
                periodmodalVisible: false,
                selectedPeriod: selected.period,
              },
              () => {
                this.props.onChange('period', selected.period);
              },
            );
          }}
          swipeDirection={['down']}
          style={styles.bottomModal}>
          <View style={styles.contentFilterBottom}>
            <View style={styles.contentSwipeDown}>
              <View style={styles.lineSwipeDown} />
            </View>
            <View style={styles.contentActionModalBottom}>
              <TouchableOpacity
                onPress={() =>
                  this.setState({
                    periodmodalVisible: false,
                    periodType: this.state.periodType.map(item => {
                      if (item.type === selectedPeriod) {
                        selected.period = item.type;
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
                  })
                }>
                <Text body1>{translate('cancel')}</Text>
              </TouchableOpacity>
              <TouchableOpacity
                onPress={() => {
                  this.setState(
                    {
                      periodmodalVisible: false,
                      selectedPeriod: selected.period,
                    },
                    () => {
                      this.props.onChange('period', selected.period);
                    },
                  );
                }}>
                <Text body1 primaryColor>
                  {translate('save')}
                </Text>
              </TouchableOpacity>
            </View>
            <View style={[styles.lineRow, {marginBottom: 40}]}>
              <FlatList
                data={periodType}
                keyExtractor={(item, index) => item.id}
                renderItem={({item}) => (
                  <TouchableOpacity
                    style={styles.item}
                    onPress={() => {
                      this.onChangePeriodType(item);
                    }}>
                    <View style={{flexDirection: 'row'}}>
                      <Image
                        tintColor={
                          item.checked
                            ? BaseColor.primaryColor
                            : BaseColor.grayColor
                        }
                        source={item.icon}
                        //For Ios Dont Pass tint color as props, instead pass inside styles
                        style={[
                          styles.img,
                          {
                            tintColor: item.checked
                              ? BaseColor.primaryColor
                              : BaseColor.grayColor,
                          },
                        ]}
                      />
                      <Text
                        body1
                        style={
                          item.checked
                            ? {
                                color: BaseColor.primaryColor,
                              }
                            : {}
                        }>
                        {item.type}
                      </Text>
                    </View>
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
        </Modal>
        {layoutHidden !== true && (
          <TouchableOpacity
            style={styles.itemPick}
            onPress={() => this.openModal(true)}>
            <Text caption1 light style={{marginBottom: 5}}>
              {translate('date')}
            </Text>
            <View style={{flexDirection: 'row'}}>
              <MCIcon
                name="calendar-month-outline"
                size={25}
                style={{paddingRight: 10}}
                color={BaseColor.primaryColor}
              />
              <Text headline semibold>
                {diffDate > 0 ? `${diffDate} days` : `${diffDate}`}
              </Text>
            </View>
          </TouchableOpacity>
        )}
        {layoutHidden !== true && showPeriod ? (
          <View style={styles.linePick} />
        ) : null}
        {layoutHidden !== true && showPeriod ? (
          <TouchableOpacity
            style={styles.itemPick}
            onPress={() => this.openPeriodModal(true)}>
            <Text caption1 light style={{marginBottom: 5}}>
              {translate('period')}
            </Text>
            <View style={{flexDirection: 'row'}}>
              <MCIcon
                name="clock-outline"
                size={25}
                style={{paddingRight: 10}}
                color={BaseColor.primaryColor}
              />
              <Text headline semibold>
                {selectedPeriod}
              </Text>
            </View>
          </TouchableOpacity>
        ) : null}
      </View>
    );
  }
}

BookingTime.propTypes = {
  style: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  filter: PropTypes.objectOf(PropTypes.any),
  checkInTime: PropTypes.string,
  period: PropTypes.string,
  onCancel: PropTypes.func,
  onChange: PropTypes.func,
  onChangePeriodType: PropTypes.func,
  showPeriod: PropTypes.bool,
  booking: PropTypes.any,
};

BookingTime.defaultProps = {
  style: {},
  filter: {},
  checkInTime: moment().format('YYYY-MM-DD'),
  onCancel: () => {},
  onChange: () => {},
  onChangePeriodType: () => {},
  showPeriod: true,
  booking: PropTypes.objectOf(PropTypes.any),
};

const mapStateToProps = state => ({
  auth: state.auth,
  filter: state.filter,
  booking: state.booking,
});

const mapDispatchToProps = dispatch => {
  return {
    FilterActions: bindActionCreators(FilterActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(BookingTime);
