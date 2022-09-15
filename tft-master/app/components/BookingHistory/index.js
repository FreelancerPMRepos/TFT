/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {View, TouchableOpacity, Image} from 'react-native';
import {Text} from '@components';
import PropTypes from 'prop-types';
import Moment from 'moment';
import styles from './styles';
export default class BookingHistory extends Component {
  render() {
    const {
      style,
      name,
      imageUrl,
      date,
      price,
      id,
      onPress,
      period,
    } = this.props;
    let periodType;
    const mdate = Moment(date, 'YYYY-MM-DD');
    const weekDay = mdate.format('dddd');
    if (period === '1') {
      periodType = 'Morning';
    } else if (period === '2') {
      periodType = 'Evening';
    } else {
      periodType = 'Full day';
    }

    return (
      <TouchableOpacity
        style={[styles.contain, style]}
        onPress={onPress}
        activeOpacity={0.9}>
        <View style={styles.nameContent}>
          <Text body2 whiteColor semibold>
            {name}
          </Text>
        </View>
        <View style={[styles.mainContent, {paddingVertical: 10}]}>
          <View style={{flex: 1}}>
            <Image
              style={{width: 70, height: 70, borderRadius: 5}}
              source={{uri: imageUrl}}
            />
          </View>
          <View style={{flex: 3, alignItems: 'flex-end'}}>
            <Text caption2 whiteColor>
              {weekDay}, {periodType}
            </Text>
            <Text body1 whiteColor semibold>
              {mdate.format('DD MMM YYYY')}
            </Text>
          </View>
        </View>
        <View style={styles.validContent}>
          <Text semibold>ID: {id}</Text>
          <Text semibold>Price: {price}</Text>
        </View>
      </TouchableOpacity>
    );
  }
}

BookingHistory.propTypes = {
  style: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  name: PropTypes.string,
  imageUrl: PropTypes.string,
  date: PropTypes.string,
  checkOut: PropTypes.string,
  total: PropTypes.string,
  price: PropTypes.string,
  onPress: PropTypes.func,
};

BookingHistory.defaultProps = {
  style: {},
  name: '',
  imageUrl: '',
  date: '',
  checkOut: '',
  total: '',
  price: '',
  onPress: () => {},
};
