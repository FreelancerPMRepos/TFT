/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from 'react-redux';
import AuthActions from '../../redux/reducers/auth/actions';
import {bindActionCreators} from 'redux';
import _ from 'lodash';
import {
  FlatList,
  SafeAreaView,
  View,
  RefreshControl,
  Dimensions,
} from 'react-native';
import {Text, HotelItem, Button} from '@components';
import {BaseColor, Images, setStatusbar} from '@config';
import {getApiData} from '../../utils/apiHelper';
import categoryName from '../../config/category';
import {BookMarkLoader, BookMarkLoader1} from '../../components/CContentLoder';
import CAlert from '../../components/CAlert';
import {BaseSetting} from '../../config/setting';
import {translate} from '../../lang/Translate';
import CNoDataFound from '../../components/CNoDataFound';
import {getCurrencySymbol} from 'app/utils/booking';
import {NavigationEvents} from 'react-navigation';

class BookMark extends Component {
  constructor(props) {
    super(props);
    this.state = {
      itemList: [],
      noData: false,
      isLoading: false,
      isRefreshing: false,
      cSymbol: 'BHD',
    };
  }

  componentDidMount() {
    setStatusbar('light');
    this.getSymbol();
    this.didFocusListener = this.props.navigation.addListener(
      'didFocus',
      () => {
        this.getBookMarksAPICall();
      },
    );
  }

  getBookMarksAPICall = () => {
    const {auth} = this.props;
    const isGuestUser =
      _.isObject(auth.userData) && _.isBoolean(auth.userData.isGuest)
        ? auth.userData.isGuest
        : true;

    if (isGuestUser) {
      this.showFeatureAlert();
    } else {
      if (auth.isConnected) {
        let data = {
          user_id:
            _.isObject(auth.userData) && auth.userData.ID
              ? auth.userData.ID
              : 0,
        };
        this.setState({isLoading: true}, () => {
          getApiData(BaseSetting.endpoints.bookMarks, 'post', data)
            .then(result => {
              if (_.isObject(result)) {
                if (_.isBoolean(result.status) && result.status === true) {
                  if (_.isArray(result.data) && result.data.length > 0) {
                    this.setState({
                      itemList: result.data,
                      isRefreshing: false,
                      isLoading: false,
                    });
                  } else {
                    this.setState({
                      itemList: [],
                      noData: true,
                      isLoading: false,
                      isRefreshing: false,
                    });
                  }
                } else {
                  this.setState({
                    itemList: [],
                    noData: true,
                    isLoading: false,
                    isRefreshing: false,
                  });
                }
              } else {
                this.setState(
                  {
                    itemList: [],
                    noData: true,
                    isLoading: false,
                    isRefreshing: false,
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
        this.setState(
          {
            noData: true,
            isRefreshing: false,
            isLoading: false,
          },
          () => {
            CAlert(translate('Internet'), translate('alert'));
          },
        );
      }
    }
  };

  renderEmpty = () => {
    const {noData, isLoading} = this.state;
    console.log('TCL: BookMark -> renderEmpty -> noData', noData, isLoading);

    if (isLoading) {
      const renderMap = [1];
      return renderMap.map(() => {
        return <BookMarkLoader1 />;
      });
    } else {
      return (
        <CNoDataFound
          style={{
            flex: 1,
            height: Dimensions.get('screen').height - 150,
            justifyContent: 'center',
            alignItems: 'center',
          }}
          msgNoData={translate('no_bookmark')}
          imageSource={Images.pools_nodata}
        />
      );
    }
  };

  removeBookMarkAlert = (itemID, serviceType) => {
    CAlert(
      translate('delete_Bookmark'),
      translate('alert'),
      () => {
        this.removeBookMarkAPICall(itemID, serviceType);
      },
      () => {},
      translate('delete'),
    );
  };

  removeBookMarkAPICall = (itemID, serviceType) => {
    console.log('removeBookMarkAPICall -> serviceType', serviceType);
    const {auth} = this.props;

    let sType = '';
    if (serviceType === 'Pools') {
      sType = 'pool';
    } else if (serviceType === 'Chalets') {
      sType = 'chalet';
    } else if (serviceType === 'Camps') {
      sType = 'camp';
    }

    if (auth.isConnected) {
      let data = {
        service_id: itemID,
        service_type: sType,
        user_id:
          _.isObject(auth.userData) && auth.userData.ID ? auth.userData.ID : 0,
      };

      getApiData(BaseSetting.endpoints.removeBookMark, 'post', data)
        .then(result => {
          if (_.isObject(result)) {
            if (_.isBoolean(result.status) && result.status === true) {
              let itemList = this.state.itemList;
              let itemIndex = itemList.findIndex(
                item => item.service_id === itemID,
              );
              itemList.splice(itemIndex, 1);
              this.setState({
                itemList: itemList,
                noData: itemList.length > 0 ? false : true,
              });
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

  showFeatureAlert = () => {
    const {
      navigation,
      AuthActions: {setUserData},
    } = this.props;
    CAlert(translate('login_feature'), translate('alert'), () => {
      setUserData({});
      navigation.navigate('Start');
    });
  };

  handlePullToReferesh = () => {
    this.setState(
      {
        isRefreshing: true,
      },
      () => {
        this.getBookMarksAPICall();
      },
    );
  };

  getSymbol = () => {
    const {country} = this.props.auth;
    const cSymbol = getCurrencySymbol(country);
    console.log('getCurrencySymbol===', cSymbol);
    this.setState({cSymbol});
  };

  render() {
    const {itemList, isRefreshing, cSymbol} = this.state;
    const {
      navigation,
      language: {languageData},
    } = this.props;

    console.log('render -> itemList', itemList);
    return (
      <SafeAreaView style={{flex: 1}}>
        <NavigationEvents
          onWillFocus={payload => {
            this.getSymbol();
          }}
        />
        <Text
          body1
          style={{
            // color: BaseColor.primaryColor,
            textAlign: 'center',
            marginVertical: 8,
          }}>
          {translate('bookmark')}
        </Text>
        <View
          style={{
            marginVertical: 8,
            // height: 0.5,
            width: '100%',
            backgroundColor: '#a9a9a9',
          }}
        />
        <FlatList
          data={itemList}
          keyExtractor={(item, index) => `key_${index + 1}`}
          contentContainerStyle={{paddingBottom: 70}}
          renderItem={({item, index}) => {
            let serverPath = _.isString(item.serverPath) ? item.serverPath : '';
            console.log('render -> itemList', itemList);
            let thumbArray = _.isArray(item.thumb) ? item.thumb : [];
            let imagePath =
              thumbArray.length > 0 ? serverPath + thumbArray[7] : '';
            return (
              <View>
                <HotelItem
                  image={imagePath}
                  currency={cSymbol}
                  serviceType={item.service_type}
                  name={languageData === 'en' ? item.name_EN : item.name_AR}
                  location={languageData === 'en' ? item.city_EN : item.city_AR}
                  price={item.price}
                  rate={item.avgRating || 0}
                  numReviews={item.totalRating}
                  poolSize={item.size}
                  rateStatus={'Very Good'}
                  sType={item.service_type}
                  style={{
                    margin: 10,
                    borderWidth: 1,
                    borderColor: '#ddd',
                    shadowColor: BaseColor.lightPrimaryColor,
                    shadowOffset: {
                      width: 0,
                      height: 3,
                    },
                    shadowOpacity: 0.27,
                    shadowRadius: 4.65,
                    elevation: 3,
                    backgroundColor: '#fff',
                    borderRadius: 8,
                  }}
                  rightSideStyle={{
                    paddingTop: 8,
                    // backgroundColor: 'red'
                  }}
                  onPress={() => {
                    navigation.navigate('HotelDetail', {
                      itemID: item.service_id,
                      selectedCategory: item.service_type,
                      fromBookmark: false,
                    });
                  }}
                  onPressBookMark={() => {
                    this.removeBookMarkAlert(
                      item.service_id,
                      item.service_type,
                    );
                  }}
                />
              </View>
            );
          }}
          ListEmptyComponent={this.renderEmpty}
          refreshControl={
            <RefreshControl
              refreshing={isRefreshing}
              onRefresh={() => {
                this.handlePullToReferesh();
              }}
            />
          }
        />
      </SafeAreaView>
    );
  }
}

BookMark.defaultProps = {
  auth: {},
  language: {},
};

BookMark.propTypes = {
  auth: PropTypes.objectOf(PropTypes.any),
  language: PropTypes.objectOf(PropTypes.any),
};

const mapStateToProps = state => ({
  auth: state.auth,
  language: state.language,
  filter: state.filter,
});

const mapDispatchToProps = dispatch => {
  return {
    AuthActions: bindActionCreators(AuthActions, dispatch),
  };
};

export default connect(mapStateToProps, mapDispatchToProps)(BookMark);
