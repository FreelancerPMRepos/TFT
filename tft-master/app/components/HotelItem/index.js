/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {
  View,
  TouchableOpacity,
  FlatList,
  Dimensions,
  ScrollView,
} from 'react-native';
import {SharedElement} from 'react-navigation-shared-element';
import {Text, Icon, StarRating, Tag, Image} from '@components';
import MCIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import {BaseColor, Images, GreenColor} from '@config';
import PropTypes from 'prop-types';
import styles from './styles';
import Icon2 from 'react-native-vector-icons/FontAwesome5';
import * as Utils from '@utils';
import MIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import {translate} from '../../lang/Translate';
import categoryName from '../../config/category';
export default class HotelItem extends Component {
  constructor(props) {
    super(props);
  }

  /**
   * Display hotel item as block
   */
  renderBlock() {
    const {
      style,
      image,
      name,
      location,
      price,
      available,
      rate,
      rateStatus,
      onPress,
      onPressTag,
      services,
      onOffer,
      poolSize,
      placeHolder,
      isBookMarked,
      onPressBookMark,
      amenities,
      baseColor,
      id,
      currency,
      languageData,
    } = this.props;
    const amenitiesArray = amenities ? amenities : [];
    return (
      <View style={style}>
        <TouchableOpacity onPress={onPress} activeOpacity={0.9}>
          {image === '' ? (
            <View style={[styles.blockImage, {backgroundColor: baseColor}]} />
          ) : (
            <SharedElement id={`image_${id}`}>
              <Image
                source={{uri: image}}
                style={styles.blockImage}
                defaultSource={placeHolder}
              />
            </SharedElement>
          )}
          {onOffer === '1' ? (
            <Image source={Images.offer} style={styles.offerImgBlock} />
          ) : null}
          {/* <TouchableOpacity
            onPress={onPressBookMark}
            style={styles.bookmarkHeart}>
            <MIcon
              name={this.props.isBookmarked ? 'heart' : 'heart-outline'}
              size={27}
              color={BaseColor.lightPrimaryColor}
            />
          </TouchableOpacity> */}
          <View
            style={{
              flexDirection: 'row',
              position: 'absolute',
              top: 5,
              right: 10,
              backgroundColor: 'rgba(0, 0, 0, 0.5)',
              borderRadius: 5,
            }}>
            {rate ? (
              <Tag
                onPress={onPressTag}
                rate
                style={{
                  backgroundColor: 'rgba(0, 0, 0, 0)',
                  borderColor: 'rgba(0, 0, 0, 0)',
                }}>
                {parseFloat(rate).toFixed(1)}
              </Tag>
            ) : null}
            {rate ? (
              <View
                style={{
                  marginRight: 10,
                  marginVertical: 10,
                  paddingTop: 2,
                }}>
                <StarRating
                  disabled={true}
                  starSize={11.5}
                  maxStars={5}
                  rating={rate}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
              </View>
            ) : null}
          </View>
          <Text style={styles.poolSize}>{poolSize}</Text>
        </TouchableOpacity>
        <View style={{paddingHorizontal: 20}}>
          <View
            style={{
              flexDirection: 'row',
              justifyContent: 'space-between',
              paddingTop: 5,
            }}>
            <View style={{flexDirection: 'row', alignItems: 'center'}}>
              <Text title2 semibold numberOfLines={1}>
                {name}
                {'  '}
              </Text>
              {/* <TouchableOpacity onPress={onPressBookMark}>
                <MIcon
                  name={isBookMarked ? 'bookmark-remove' : 'bookmark-plus'}
                  size={30}
                  color={
                    isBookMarked
                      ? BaseColor.yellowColor
                      : BaseColor.primaryColor
                  }
                />
              </TouchableOpacity> */}
            </View>
            <TouchableOpacity
              onPress={onPressBookMark}
              style={styles.bookmarkHeart1}>
              <MIcon
                name={this.props.isBookmarked ? 'heart' : 'heart-outline'}
                size={27}
                color={BaseColor.primaryColor}
              />
            </TouchableOpacity>
            {/* <Text title3 primaryColor semibold style={{textAlign: 'right'}}>
              {price} {currency}
            </Text> */}
          </View>
          <View
            style={[
              styles.blockContentAddress,
              {justifyContent: 'space-between', marginTop: 5},
            ]}>
            <View style={styles.blockContentAddress}>
              <Icon
                name="map-marker-alt"
                color={BaseColor.lightPrimaryColor}
                size={10}
              />
              <Text
                caption1
                grayColor
                style={{
                  marginLeft: 3,
                }}
                numberOfLines={1}>
                {location}
              </Text>
            </View>
            <Text title3 primaryColor semibold style={{textAlign: 'right'}}>
              <Text caption1 primaryColor>
                {translate('Start_From')}
              </Text>{' '}
              {price} {currency}
            </Text>
          </View>
        </View>
        <View style={styles.contentService}>
          <FlatList
            horizontal={true}
            showsHorizontalScrollIndicator={false}
            data={services}
            keyExtractor={(item, index) => item.id}
            renderItem={({item, index}) => (
              <View style={styles.serviceItemBlock} key={'block' + index}>
                <Icon
                  name={item.icon}
                  size={16}
                  color={BaseColor.accentColor}
                />
                <Text
                  overline
                  grayColor
                  style={{marginTop: 4}}
                  numberOfLines={1}>
                  {item.name}
                </Text>
              </View>
            )}
          />
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            style={styles.amenitiesWrapper}>
            {amenitiesArray.map((item, index) => (
              <View
                style={{
                  alignItems: 'center',
                  paddingVertical: 5,
                  // paddingHorizontal: 5,
                  width: Dimensions.get('window').width * 0.25,
                }}
                key={'service' + index}>
                {item.iconType === 'image' ? (
                  <Image
                    tintColor={BaseColor.primaryColor}
                    source={{uri: item.serverImgLink}}
                    style={styles.img}
                  />
                ) : (
                  <MCIcon
                    name={item.iconClass}
                    color={BaseColor.primaryColor}
                    size={22}
                  />
                )}
                <Text overline grayColor style={{marginTop: 4}}>
                  {item.value} x{' '}
                  {languageData === 'en' ? item.name : item.name_AR}
                </Text>
              </View>
            ))}
          </ScrollView>
          {/* <TouchableOpacity
            style={{
              alignItems: 'flex-end',
              justifyContent: 'center',
              width: 16,
            }}>
            <Icon
              name="angle-right"
              size={16}
              color={BaseColor.textSecondaryColor}
            />
          </TouchableOpacity> */}
        </View>
      </View>
    );
  }

  /**
   * Display hotel item as list
   */
  renderList() {
    const {
      style,
      image,
      name,
      location,
      price,
      available,
      rate,
      rateCount,
      onPress,
      poolSize,
      onPressBookMark,
      serviceType,
      id,
      rightSideStyle,
      sType,
      currency,
    } = this.props;

    let baseColor =
      serviceType === categoryName.pools
        ? BaseColor.lightPrimaryColor
        : serviceType === categoryName.chalets
        ? BaseColor.accentColor
        : serviceType === categoryName.camps
        ? GreenColor.lightPrimaryColor
        : null;

    console.log('Listing ===> ', `image_${id}`);
    return (
      <TouchableOpacity
        onPress={onPress}
        activeOpacity={0.9}
        style={[styles.listContent, style]}>
        <View>
          {image === '' ? (
            <View style={[styles.listImage, {backgroundColor: baseColor}]} />
          ) : (
            <SharedElement id={`image_${id}`}>
              <Image source={{uri: image}} style={styles.listImage} />
            </SharedElement>
          )}
        </View>
        <View style={[styles.listContentRight, rightSideStyle]}>
          <View
            style={{
              flexDirection: 'row',
              alignItems: 'center',
              justifyContent: 'space-between',
            }}>
            <SharedElement id={`text_${id}`}>
              <Text headline semibold numberOfLines={1}>
                {name}
                {'  '}
              </Text>
            </SharedElement>
            <TouchableOpacity onPress={onPressBookMark}>
              <MIcon name={'heart'} size={20} color={BaseColor.primaryColor} />
            </TouchableOpacity>
          </View>
          <View
            style={[
              styles.listContentRow,
              {justifyContent: 'space-between', paddingTop: 3},
            ]}>
            <SharedElement id={`location_${id}`}>
              <View style={styles.listContentRow}>
                <Icon
                  name="map-marker-alt"
                  color={BaseColor.lightPrimaryColor}
                  size={15}
                />
                <Text
                  grayColor
                  semibold
                  style={{
                    marginLeft: 3,
                  }}
                  numberOfLines={1}>
                  {location}
                </Text>
              </View>
            </SharedElement>
            {rate && rate > 0 ? (
              <View style={[styles.listContentRow]}>
                <StarRating
                  disabled={true}
                  starSize={15}
                  maxStars={5}
                  rating={parseFloat(rate).toFixed(2)}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
                {/* <Text
                  grayColor
                  style={{
                    marginLeft: 10,
                    marginRight: 3,
                  }}>
                  Rating
                </Text> */}
                <Text caption1 primaryColor semibold>
                  {rateCount}
                </Text>
              </View>
            ) : null}
          </View>
          <View
            style={[
              styles.listContentRow,
              {justifyContent: 'space-between', paddingTop: 3},
            ]}>
            {sType === 'Pools' ? (
              <Text body2 semibold>
                {poolSize}
                {'  '}
                <Text grayColor>{translate('Size')}</Text>
              </Text>
            ) : null}
            <Text
              body2
              primaryColor
              semibold
              style={{marginTop: 5, marginBottom: 5}}>
              <Text caption1 primaryColor>
                {translate('Start_From')}
              </Text>{' '}
              {price} {currency}
            </Text>
          </View>
          {/* <Text footnote accentColor style={{marginTop: 3}}>
            {available}
          </Text> */}
        </View>
      </TouchableOpacity>
    );
  }

  /**
   * Display hotel item as grid
   */
  renderGrid() {
    const {
      style,
      image,
      name,
      location,
      price,
      rate,
      numReviews,
      onPress,
      onOffer,
      poolSize,
      placeHolder,
      isBookMarked,
      onPressBookMark,
      baseColor,
      id,
      allData,
      currency,
    } = this.props;
    const oddLength = allData.length % 2 === 1;
    return (
      <View
        style={[
          styles.girdContent,
          style,
          {width: oddLength ? '50%' : null},
          {flex: oddLength ? null : 1},
        ]}>
        <TouchableOpacity onPress={onPress} activeOpacity={0.9}>
          {image === '' ? (
            <View style={[styles.girdImage, {backgroundColor: baseColor}]} />
          ) : (
            <SharedElement id={`image_${id}`}>
              <Image
                source={{uri: image}}
                style={styles.girdImage}
                defaultSource={placeHolder}
              />
            </SharedElement>
          )}
          {onOffer === '1' ? (
            <Image source={Images.offer} style={styles.offerImg} />
          ) : null}
          <TouchableOpacity
            onPress={onPressBookMark}
            style={styles.bookmarkHeart}>
            <MIcon
              name={isBookMarked ? 'heart' : 'heart-outline'}
              size={20}
              color={BaseColor.lightPrimaryColor}
            />
          </TouchableOpacity>
          <Text style={styles.poolSizeGrid}>{poolSize}</Text>
        </TouchableOpacity>
        <View
          style={[
            styles.girdContentRate,
            {marginTop: 0, alignItems: 'center'},
          ]}>
          <SharedElement id={`location_${id}`}>
            <View style={styles.girdContentLocation}>
              <Icon
                name="map-marker-alt"
                color={BaseColor.primaryColor}
                size={10}
              />
              <Text
                caption1
                grayColor
                semibold
                ellipsizeMode="tail"
                style={{
                  marginLeft: 3,
                  width: 80,
                }}
                numberOfLines={1}>
                {location}
              </Text>
            </View>
          </SharedElement>
          <SharedElement id={`text_${id}`}>
            <Text body2 semibold>
              {name}
              {'  '}
            </Text>
          </SharedElement>
          {/* <View>
            <Text
              body2
              primaryColor
              semibold
              style={{
                marginTop: 5,
              }}>
              <Text caption1 primaryColor>
                {translate('Start_From')}
              </Text>{' '}
              {price} {currency}
            </Text>
          </View> */}
        </View>
        <View
          style={{
            flexDirection: 'row',
            marginTop: 5,
            alignItems: 'center',
            justifyContent: 'space-between',
          }}>
          <Text caption1 primaryColor>
            {translate('Start_From')}
          </Text>
          <Text body2 primaryColor semibold>
            {/* {' '} */}
            {price} {currency}
          </Text>
        </View>
        <View>
          {/* <TouchableOpacity onPress={onPressBookMark}>
            <MIcon
              name={isBookMarked ? 'bookmark-remove' : 'bookmark-plus'}
              size={20}
              color={
                isBookMarked ? BaseColor.yellowColor : BaseColor.primaryColor
              }
            />
          </TouchableOpacity> */}
        </View>
        {numReviews && numReviews > 0 ? (
          <View style={[styles.girdContentRate, {marginTop: 5}]}>
            <StarRating
              disabled={true}
              starSize={10}
              maxStars={5}
              rating={parseFloat(rate).toFixed(2)}
              selectedStar={rating => {}}
              fullStarColor={BaseColor.yellowColor}
            />
            <Text caption2 grayColor>
              {numReviews} reviews
            </Text>
          </View>
        ) : null}
      </View>
    );
  }

  render() {
    let {block, grid} = this.props;
    if (grid) {
      return this.renderGrid();
    } else if (block) {
      return this.renderBlock();
    } else {
      console.log('in Else');
      return this.renderList();
    }
  }
}

HotelItem.propTypes = {
  style: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  rightSideStyle: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  image: PropTypes.node.isRequired,
  list: PropTypes.bool,
  block: PropTypes.bool,
  grid: PropTypes.bool,
  onOffer: PropTypes.string,
  name: PropTypes.string,
  location: PropTypes.string,
  price: PropTypes.string,
  available: PropTypes.string,
  rate: PropTypes.number,
  rateCount: PropTypes.string,
  rateStatus: PropTypes.string,
  numReviews: PropTypes.number,
  services: PropTypes.array,
  onPress: PropTypes.func,
  onPressTag: PropTypes.func,
  poolSize: PropTypes.string,
  placeHolder: PropTypes.node,
  isBookMarked: PropTypes.bool,
  onPressBookMark: PropTypes.func,
  serviceType: PropTypes.string,
  baseColor: PropTypes.string,
  sType: PropTypes.string,
};

HotelItem.defaultProps = {
  style: {},
  image: '',
  list: true,
  block: false,
  grid: false,
  onOffer: '0',
  poolSize: '',
  name: '',
  location: '',
  price: '',
  available: '',
  rate: 0,
  rateCount: '',
  rateStatus: '',
  numReviews: 0,
  services: [],
  onPress: () => {},
  onPressTag: () => {},
  placeHolder: null,
  isBookMarked: false,
  onPressBookMark: () => {},
  serviceType: '',
  baseColor: '',
  rightSideStyle: {},
  sType: '',
};
