/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  RefreshControl,
  View,
  FlatList,
  TouchableOpacity,
  Image,
} from 'react-native';
import {BaseColor, Images} from '@config';
import {SafeAreaView, BookingHistory, Text} from '@components';
import {connect} from 'react-redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {bindActionCreators} from 'redux';
import {getApiData} from '../../utils/apiHelper';
import {BaseSetting} from '../../config/setting';
import styles from './styles';
import _ from 'lodash';
import CAlert from '../../components/CAlert';
import {translate} from '../../lang/Translate';
import CNoDataFound from '../../components/CNoDataFound';
import {
  BookMarkLoader,
  BookingListLoader,
} from '../../components/CContentLoder';
import CTopTabs from '../../components/CTopTabs';
import moment from 'moment';
import {NavigationEvents} from 'react-navigation';

class Booking extends Component {
  constructor(props) {
    super(props);

    // Temp data define
    this.state = {
      refreshing: false,
      bookingHistory: [],
      selectedIndex: 1,
      upcomingArray: [],
      previousArray: [],
      isLoading: false,
      rateCount: '',
    };
  }

  componentDidMount() {
    this.getBookings();
  }

  getBookings = () => {
    const {auth, navigation} = this.props;
    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : true;
    if (!isGuestUser) {
      const userid =
        _.isObject(auth.userData) && _.isString(auth.userData.ID)
          ? auth.userData.ID
          : '';
      console.log('AUTH', auth.userData.ID);
      if (auth.isConnected) {
        const data = {
          user_ID: userid,
        };
        this.setState({isLoading: true}, () => {
          getApiData(BaseSetting.endpoints.reservationsList, 'post', data)
            .then(result => {
              console.log('Booking -> getBookings -> result', result);
              if (_.isObject(result)) {
                if (_.isBoolean(result.status) && result.status === true) {
                  if (_.isObject(result.data)) {
                    console.log('Booking List', result.data);
                    let upcoming =
                      result.data && result.data.upcoming
                        ? result.data.upcoming
                        : [];
                    let previous =
                      result.data && result.data.previous
                        ? result.data.previous
                        : [];
                    this.setState({
                      // bookingHistory: bookingList,
                      upcomingArray: upcoming,
                      previousArray: previous,
                      rateCount: result.data
                        ? result.data.rateRemainCount
                        : '0',
                      isLoading: false,
                      refreshing: false,
                    });
                  }
                } else {
                  let bookingList = [
                    {title: translate('Upcoming'), data: []},
                    {title: translate('Previous'), data: []},
                  ];
                  this.setState(
                    {
                      bookingHistory: bookingList,
                      isLoading: false,
                      refreshing: false,
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
                let bookingList = [
                  {title: translate('Upcoming'), data: []},
                  {title: translate('Previous'), data: []},
                ];
                this.setState(
                  {
                    bookingHistory: bookingList,
                    isLoading: false,
                    refreshing: false,
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
        });
      } else {
        let bookingList = [
          {title: translate('Upcoming'), data: []},
          {title: translate('Previous'), data: []},
        ];
        this.setState(
          {
            bookingHistory: bookingList,
            isLoading: false,
            refreshing: false,
          },
          () => {
            CAlert(translate('Internet'), translate('alert'));
          },
        );
      }
    } else {
      CAlert(translate('login_feature'), translate('alert'), () => {
        navigation.navigate('Start');
      });
    }
  };

  renderItem(item) {
    return (
      <BookingHistory
        name={item.name_EN}
        imageUrl={item.url}
        date={item.date}
        period={item.period}
        id={item.ID}
        price={item.price}
        style={{paddingVertical: 10, marginHorizontal: 20}}
        onPress={() => {
          this.props.navigation.navigate('BookingDetail', {item});
        }}
      />
    );
  }

  handlePullToRefresh = () => {
    this.setState(
      {
        refreshing: true,
      },
      () => {
        this.getBookings();
      },
    );
  };

  handleEmptyComponent = () => {
    const renderMap = [1, 2, 3, 4, 5, 6];
    const loader = renderMap.map(() => <BookMarkLoader />);
    return renderMap.map(() => <BookMarkLoader />);
  };

  renderNoContent = (type, data) => {
    const {selectedIndex, isLoading} = this.state;
    if (isLoading && _.isEmpty(data)) {
      return (
        <View style={{paddingTop: 10}}>
          <BookingListLoader />
        </View>
      );
    } else if (type === 'upcoming' && _.isEmpty(data) && selectedIndex === 1) {
      return (
        <CNoDataFound
          msgNoData={translate('No_Upcoming')}
          imageSource={Images.pools_nodata}
        />
      );
    } else if (type === 'previous' && _.isEmpty(data) && selectedIndex === 2) {
      return (
        <CNoDataFound
          msgNoData={translate('No_Previous')}
          imageSource={Images.pools_nodata}
        />
      );
    }
  };

  onChangeIndex = index => {
    this.setState({selectedIndex: index});
  };

  render() {
    let {
      refreshing,
      bookingHistory,
      selectedIndex,
      upcomingArray,
      previousArray,
      rateCount,
    } = this.state;
    console.log('render -> previousArray', previousArray);

    const upTabs = [
      {
        id: 1,
        title: translate('Upcoming'),
        showNoti: false,
      },
      {
        id: 2,
        title: translate('Previous'),
        showNoti: true,
      },
    ];

    return (
      <SafeAreaView style={{flex: 1}}>
        <NavigationEvents
          onWillFocus={payload => {
            this.getBookings();
          }}
        />
        <Text
          body1
          style={{
            // color: BaseColor.primaryColor,
            textAlign: 'center',
            marginVertical: 10,
          }}>
          {translate('booking_history')}
        </Text>
        <View
          style={{
            marginVertical: 8,
            // height: 0.5,
            width: '100%',
            backgroundColor: '#a9a9a9',
          }}
        />
        <CTopTabs
          {...this.props}
          tabs={upTabs}
          onChangeIndex={this.onChangeIndex}
          selectedIndex={selectedIndex}
          tabContainerStyle={styles.tabContainerStyle}
          tabTitleStyle={styles.tabTitleStyle}
          mainContainerStyle={styles.mainContainerStyle}
          showBadge
          count={rateCount}
        />
        {selectedIndex === 1 && upcomingArray ? (
          <FlatList
            data={upcomingArray}
            contentContainerStyle={{paddingBottom: 70}}
            keyExtractor={(item, index) => index.toString()}
            renderItem={item => {
              const nItem = item.item ? item.item : {};
              let periodType;
              let ePeriodType;
              let periodType1 = '';
              const sDate = moment(nItem.start_date, 'YYYY-MM-DD');
              let eDate = moment(nItem.start_date, 'YYYY-MM-DD');
              if (nItem.end_date && !_.isNull(nItem.end_date)) {
                eDate = moment(nItem.end_date, 'YYYY-MM-DD');
              }
              if (nItem.period === '1') {
                periodType = 'Morning';
              } else if (nItem.period === '2') {
                periodType = 'Evening';
              } else if (nItem.period === '3') {
                periodType = 'Full Day';
              } else {
                periodType = 'Morning';
                periodType1 = 'Evening';
              }

              if (nItem.end_period === '1') {
                ePeriodType = 'Morning';
              } else if (nItem.end_period === '2') {
                ePeriodType = 'Evening';
              } else if (nItem.end_period === '3') {
                ePeriodType = 'Full Day';
              }
              const imgUrlPath =
                nItem && nItem.thumb && nItem.thumb[3] ? nItem.thumb[3] : '';
              const sPath = nItem && nItem.serverPath ? nItem.serverPath : '';
              const imgUrl = `${sPath}${imgUrlPath}`;
              const isEqual = _.isEqual(
                moment(sDate).format('DD MMM YYYY'),
                moment(eDate).format('DD MMM YYYY'),
              );
              const currency = nItem && nItem.currency ? nItem.currency : 'BHD';
              return (
                <TouchableOpacity
                  style={[styles.listContain]}
                  onPress={() => {
                    this.props.navigation.navigate('BookingDetail', {
                      item: nItem,
                    });
                  }}
                  activeOpacity={0.9}>
                  <View style={styles.nameContent}>
                    <Text body2 primaryColor semibold>
                      {nItem.name_EN}
                    </Text>
                  </View>
                  <View style={[styles.mainContent, {paddingVertical: 10}]}>
                    <View
                      style={{
                        shadowColor: '#000',
                        shadowOffset: {
                          width: 0,
                          height: 3,
                        },
                        shadowOpacity: 0.29,
                        shadowRadius: 4.65,
                      }}>
                      <Image
                        style={{
                          width: 70,
                          height: 70,
                          borderRadius: 5,
                        }}
                        source={{uri: imgUrl ? imgUrl : nItem.url}}
                      />
                    </View>
                    <View style={{flex: 1}}>
                      <View
                        style={{
                          flex: 1,
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          paddingHorizontal: 10,
                        }}>
                        <View>
                          <Text caption2 primaryColor>
                            {isEqual ? 'Selected Date' : 'From'}
                          </Text>
                          <Text body1 primaryColor semibold>
                            {moment(sDate).format('DD MMM YYYY')}
                            {isEqual ? '' : ','}
                          </Text>
                        </View>
                        {!isEqual ? (
                          <View>
                            <Text caption2 primaryColor>
                              To
                            </Text>
                            <Text body1 primaryColor semibold>
                              {moment(eDate).format('DD MMM YYYY')}
                            </Text>
                          </View>
                        ) : null}
                      </View>
                      <View
                        style={[
                          styles.periodWrapper,
                          {
                            justifyContent: 'space-between',
                            flex: 1,
                          },
                        ]}>
                        <View style={styles.singlePeriod}>
                          <Text semibold caption2 whiteColor>
                            {periodType}
                          </Text>
                        </View>
                        {!isEqual && (
                          <View style={styles.singlePeriod}>
                            <Text semibold caption2 whiteColor>
                              {ePeriodType}
                            </Text>
                          </View>
                        )}
                        {periodType1 ? (
                          <View style={styles.singlePeriod}>
                            <Text semibold caption2 whiteColor>
                              {periodType1}
                            </Text>
                          </View>
                        ) : null}
                      </View>
                    </View>
                  </View>
                  <View style={styles.validContent}>
                    <Text semibold>
                      {translate('ID')}: {nItem.ID}
                    </Text>
                    <Text semibold>
                      {translate('Price')}: {nItem.price} {currency}
                    </Text>
                  </View>
                </TouchableOpacity>
              );
            }}
            ListEmptyComponent={this.renderNoContent('upcoming', upcomingArray)}
            // ListFooterComponent={this.renderNoContent}
            refreshControl={
              <RefreshControl
                colors={[BaseColor.primaryColor]}
                tintColor={BaseColor.primaryColor}
                refreshing={refreshing}
                onRefresh={() => {
                  this.handlePullToRefresh();
                }}
              />
            }
          />
        ) : (
          <FlatList
            data={previousArray}
            keyExtractor={(item, index) => index.toString()}
            contentContainerStyle={{paddingBottom: 70}}
            renderItem={item => {
              const nItem = item.item ? item.item : {};
              let periodType;
              // const sDate = moment(nItem.start_date, 'YYYY-MM-DD');
              // const weekDay = sDate.format('dddd');
              let periodType1 = '';
              const sDate = moment(nItem.start_date, 'YYYY-MM-DD');
              let eDate = moment(nItem.start_date, 'YYYY-MM-DD');
              if (nItem.end_date && !_.isNull(nItem.end_date)) {
                eDate = moment(nItem.end_date, 'YYYY-MM-DD');
              }
              if (nItem.period === '1') {
                periodType = 'Morning';
              } else if (nItem.period === '2') {
                periodType = 'Evening';
              } else if (nItem.period === '3') {
                periodType = 'Full Day';
              } else {
                periodType = 'Morning';
                periodType1 = 'Evening';
              }
              const imgUrlPath =
                nItem && nItem.thumb && nItem.thumb[3] ? nItem.thumb[3] : '';
              const sPath = nItem && nItem.serverPath ? nItem.serverPath : '';
              const imgUrl = `${sPath}${imgUrlPath}`;
              const isEqual = _.isEqual(
                moment(sDate).format('DD MMM YYYY'),
                moment(eDate).format('DD MMM YYYY'),
              );
              console.log('EndingDATE', isEqual, nItem, eDate);
              const currency = nItem && nItem.currency ? nItem.currency : 'BHD';
              return (
                <TouchableOpacity
                  style={[styles.listContain]}
                  onPress={() => {
                    this.props.navigation.navigate('BookingDetail', {
                      item: nItem,
                    });
                  }}
                  activeOpacity={0.9}>
                  <View style={styles.nameContent}>
                    <Text body2 primaryColor semibold>
                      {nItem.name_EN}
                    </Text>
                    {nItem.rated ? null : (
                      <Text
                        onPress={() => {
                          this.props.navigation.navigate('BookingDetail', {
                            item: nItem,
                            fromReview: true,
                          });
                        }}
                        caption1
                        bold
                        primaryColor>
                        {translate('Submit Review')}
                      </Text>
                    )}
                  </View>
                  <View style={[styles.mainContent, {paddingVertical: 10}]}>
                    <View
                      style={{
                        shadowColor: '#000',
                        shadowOffset: {
                          width: 0,
                          height: 3,
                        },
                        shadowOpacity: 0.29,
                        shadowRadius: 4.65,
                      }}>
                      <Image
                        style={{
                          width: 70,
                          height: 70,
                          borderRadius: 5,
                        }}
                        source={{uri: imgUrl ? imgUrl : nItem.url}}
                      />
                    </View>
                    <View style={{flex: 1}}>
                      <View
                        style={{
                          flex: 1,
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          paddingHorizontal: 10,
                        }}>
                        <View>
                          <Text caption2 primaryColor>
                            {isEqual && !_.isUndefined(isEqual)
                              ? 'Selected Date'
                              : 'From'}
                          </Text>
                          <Text body1 primaryColor semibold>
                            {moment(sDate).format('DD MMM YYYY')}
                            {isEqual ? '' : ','}
                          </Text>
                        </View>
                        {!isEqual ? (
                          <View>
                            <Text caption2 primaryColor>
                              To
                            </Text>
                            <Text body1 primaryColor semibold>
                              {moment(eDate).format('DD MMM YYYY')}
                            </Text>
                          </View>
                        ) : null}
                      </View>
                      <View style={styles.periodWrapper}>
                        <View style={styles.singlePeriod}>
                          <Text semibold caption2 whiteColor>
                            {periodType}
                          </Text>
                        </View>
                        {periodType1 ? (
                          <View style={styles.singlePeriod}>
                            <Text semibold caption2 whiteColor>
                              {periodType1}
                            </Text>
                          </View>
                        ) : null}
                      </View>
                    </View>
                  </View>
                  <View style={styles.validContent}>
                    <Text semibold>
                      {translate('ID')}: {nItem.ID}
                    </Text>
                    <Text semibold>
                      {translate('Price')}: {nItem.price} {currency}
                    </Text>
                  </View>
                </TouchableOpacity>
              );
            }}
            // ListFooterComponent={this.renderNoContent}
            ListEmptyComponent={this.renderNoContent('previous', previousArray)}
            refreshControl={
              <RefreshControl
                colors={[BaseColor.primaryColor]}
                tintColor={BaseColor.primaryColor}
                refreshing={refreshing}
                onRefresh={() => {
                  this.handlePullToRefresh();
                }}
              />
            }
          />
        )}
      </SafeAreaView>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
});

const mapDispatchToProps = dispatch => {
  return {
    AuthActions: bindActionCreators(AuthActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(Booking);
