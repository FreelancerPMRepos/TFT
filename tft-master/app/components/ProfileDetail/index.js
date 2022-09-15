/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {View, TouchableOpacity} from 'react-native';
import {Image, Icon, Text} from '@components';
import styles from './styles';
import PropTypes from 'prop-types';
import {BaseColor} from '@config';

export default class ProfileDetail extends Component {
  render() {
    const {
      style,
      image,
      styleLeft,
      styleThumb,
      styleRight,
      onPress,
      textFirst,
      point,
      textSecond,
      textThird,
      icon,
      imageTxt,
    } = this.props;
    return (
      <TouchableOpacity
        style={[styles.contain, style]}
        onPress={onPress}
        activeOpacity={1}>
        <View style={[styles.contentLeft, styleLeft]}>
          <View style={[styles.thumb]}>
            <Text style={styles.profilePictxt}>{imageTxt.toUpperCase()}</Text>
          </View>
          <View>
            <Text headline semibold numberOfLines={1}>
              {textFirst}
            </Text>
            <Text
              body2
              style={{
                marginTop: 3,
                paddingRight: 10,
                paddingTop: 3,
              }}
              numberOfLines={1}>
              {textSecond}
            </Text>
            <Text
              footnote
              semibold
              grayColor
              numberOfLines={1}
              style={{paddingTop: 4}}>
              {textThird}
            </Text>
          </View>
        </View>
        {icon && (
          <View style={[styles.contentRight, styleRight]}>
            <Icon name="angle-right" size={18} color={BaseColor.grayColor} />
          </View>
        )}
      </TouchableOpacity>
    );
  }
}

ProfileDetail.propTypes = {
  style: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  image: PropTypes.node.isRequired,
  textFirst: PropTypes.string,
  imageTxt: PropTypes.string,
  point: PropTypes.string,
  textSecond: PropTypes.string,
  textThird: PropTypes.string,
  styleLeft: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  styleThumb: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  styleRight: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  icon: PropTypes.bool,
  onPress: PropTypes.func,
};

ProfileDetail.defaultProps = {
  image: '',
  textFirst: '',
  textSecond: '',
  imageTxt: '',
  icon: true,
  point: '',
  style: {},
  styleLeft: {},
  styleThumb: {},
  styleRight: {},
  onPress: () => {},
};
