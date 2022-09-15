/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {View, FlatList, RefreshControl, ScrollView} from 'react-native';
import PropTypes from 'prop-types';
import _ from 'lodash';
import {BaseStyle, BaseColor, BaseSetting} from '@config';
import {
  Header,
  SafeAreaView,
  Icon,
  Text,
  RateDetail,
  CommentItem,
  StarRating,
} from '@components';
import styles from './styles';

// Load sample data
import {ReviewData} from '@data';
import {getApiData} from 'app/utils/apiHelper';
import {connect} from 'react-redux';
import CLoader from 'app/components/CLoader';
import {ReviewLoader} from 'app/components/CContentLoder';

class Review extends Component {
  constructor(props) {
    super(props);
    this.state = {
      rateDetail: {
        point: 4.7,
        maxPoint: 5,
        totalRating: 25,
        data: ['5%', '5%', '35%', '20%', '10%'],
      },
      loading: true,
      refreshing: false,
      reviewList: ReviewData,
    };
  }

  componentDidMount = () => {
    this.ratingDetails();
    console.log('Review -> componentDidMount -> ratingData');
  };

  ratingDetails = () => {
    // return new Promise((resolve, reject) => {
    const {navigation, auth} = this.props;
    let sId = navigation.getParam('serviceID', '');
    if (auth.isConnected) {
      // let sType = getCurrentFilterType(selectedCategory, 'sType');

      const data = {
        apiVersion: 2,
        serviceId: sId,
        page: 1,
        count: 10,
      };
      console.log('data==', data);
      this.setState({loading: true}, () => {
        getApiData(BaseSetting.endpoints.ratingDetails, 'post', data)
          .then(result => {
            console.log('Review -> getDayPrices -> result', result);
            if (result) {
              console.log('CCalendar -> getDayPrices -> result', result);
              if (result.status && result.data) {
                this.setState({
                  loading: false,
                  refreshing: false,
                  ratingData: result,
                });
              } else {
                this.setState({
                  loading: false,
                  refreshing: false,
                  ratingData: {},
                });
              }
            } else {
              this.setState({
                loading: false,
                refreshing: false,
                ratingData: {},
              });
              // resolve(false);
            }
          })
          .catch(err => {
            this.setState({loading: false, refreshing: false, ratingData: {}});
            console.log(`Error: ${err}`);
            // reject(err);
          });
      });
    } else {
      this.setState({loading: false, refreshing: false, ratingData: {}});
      // reject(null);
    }
    // });
  };
  renderEmpty = () => {
    return (
      <View
        style={{
          flex: 1,
          justifyContent: 'center',
          alignItems: 'center',
          // backgroundColor: 'red',
        }}>
        <ReviewLoader />
      </View>
    );
  };

  renderHeader = () => {
    const {ratingData} = this.state;
    const avgRating =
      ratingData && ratingData.avgRating
        ? Number(ratingData.avgRating).toFixed(1)
        : 0;
    const priceRating =
      ratingData && ratingData.avgPriceRating
        ? Number(ratingData.avgPriceRating).toFixed(1)
        : 0;
    const locationRating =
      ratingData && ratingData.avgLocationRating
        ? Number(ratingData.avgLocationRating).toFixed(1)
        : 0;
    const cleanlinessRating =
      ratingData && ratingData.avgClealinessRating
        ? Number(ratingData.avgClealinessRating).toFixed(1)
        : 0;
    const amenitiesRating =
      ratingData && ratingData.avgAmenitiesRating
        ? Number(ratingData.avgAmenitiesRating).toFixed(1)
        : 0;
    const noOfReviews =
      ratingData && ratingData.totalRatings
        ? Number(ratingData.totalRatings)
        : 0;
    return (
      <View style={{paddingBottom: 10, flex: 1}}>
        <View style={styles.overViewWrapper}>
          <View style={styles.starWrapper}>
            <View style={{marginRight: 5}}>
              <View style={[styles.row, {justifyContent: 'center'}]}>
                <StarRating
                  disabled={true}
                  starSize={16}
                  maxStars={5}
                  rating={avgRating}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
                <Text
                  style={{
                    fontSize: 15,
                    paddingLeft: 5,
                  }}>
                  {avgRating}
                </Text>
              </View>
              <Text
                style={{
                  fontSize: 12,
                  textAlign: 'center',
                  paddingTop: 4,
                }}>
                Based on {noOfReviews} reviews
              </Text>
            </View>
          </View>
          <View style={[styles.starTypeWrapper, {paddingTop: 22}]}>
            <View style={[styles.row]}>
              <Text>Price</Text>
              <View style={[styles.row, {justifyContent: 'flex-end'}]}>
                <StarRating
                  disabled={true}
                  starSize={16}
                  maxStars={5}
                  rating={priceRating}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
                <Text
                  style={{
                    fontSize: 12,
                    paddingLeft: 5,
                  }}>
                  {priceRating}
                </Text>
              </View>
            </View>
          </View>
          <View style={[styles.starTypeWrapper]}>
            <View style={[styles.row]}>
              <Text>Location</Text>
              <View style={[styles.row, {justifyContent: 'flex-end'}]}>
                <StarRating
                  disabled={true}
                  starSize={16}
                  maxStars={5}
                  rating={locationRating}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
                <Text
                  style={{
                    fontSize: 12,
                    paddingLeft: 5,
                  }}>
                  {locationRating}
                </Text>
              </View>
            </View>
          </View>
          <View style={[styles.starTypeWrapper]}>
            <View style={[styles.row]}>
              <Text>Cleanliness</Text>
              <View style={[styles.row, {justifyContent: 'flex-end'}]}>
                <StarRating
                  disabled={true}
                  starSize={16}
                  maxStars={5}
                  rating={cleanlinessRating}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
                <Text
                  style={{
                    fontSize: 12,
                    paddingLeft: 5,
                  }}>
                  {cleanlinessRating}
                </Text>
              </View>
            </View>
          </View>
          <View style={[styles.starTypeWrapper]}>
            <View style={[styles.row]}>
              <Text>Amenities</Text>
              <View style={[styles.row, {justifyContent: 'flex-end'}]}>
                <StarRating
                  disabled={true}
                  starSize={16}
                  maxStars={5}
                  rating={amenitiesRating}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
                <Text
                  style={{
                    fontSize: 12,
                    paddingLeft: 5,
                  }}>
                  {amenitiesRating}
                </Text>
              </View>
            </View>
          </View>
        </View>
        {/* <RateDetail
    point={avgRating}
    maxPoint={rateDetail.maxPoint}
    totalRating={noOfReviews}
    data={rateDetail.data}
  /> */}
      </View>
    );
  };

  render() {
    const {navigation} = this.props;
    let {ratingData, loading, refreshing} = this.state;
    const rateData = ratingData && ratingData.data ? ratingData.data : [];
    // if (loading) {
    //   return <CLoader />;
    // }
    return (
      <SafeAreaView style={BaseStyle.safeAreaView} forceInset={{top: 'always'}}>
        <Header
          title="Review"
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
        />
        {/* Sample User Review List */}
        <FlatList
          style={{padding: 20, flex: 1}}
          refreshControl={
            <RefreshControl
              colors={[BaseColor.primaryColor]}
              tintColor={BaseColor.primaryColor}
              refreshing={this.state.refreshing}
              onRefresh={() => {
                this.ratingDetails();
                // this.setState({loading: true}, () => {
                // });
              }}
            />
          }
          data={rateData}
          keyExtractor={(item, index) => item.id}
          ListEmptyComponent={this.renderEmpty}
          ListHeaderComponent={!loading && !refreshing && this.renderHeader}
          renderItem={({item}) => {
            const fName = item.first_name;
            const lName = item.last_name;
            return (
              <CommentItem
                style={{marginTop: 10}}
                image={item.source}
                fName={fName}
                lName={lName}
                rate={item.userAvgRating}
                date={item.created_at}
                title={item.comment}
                // comment={item.comment}
              />
            );
          }}
        />
        {/* </ScrollView> */}
      </SafeAreaView>
    );
  }
}

Review.defaultProps = {
  auth: {},
};

Review.propTypes = {
  auth: PropTypes.objectOf(PropTypes.any),
};

const mapStateToProps = state => ({
  auth: state.auth,
});

const mapDispatchToProps = dispatch => {
  return {
    dispatch,
    // AuthActions: bindActionCreators(AuthActions, dispatch),
    // BookActions: bindActionCreators(BookActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(Review);
