/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {View, TouchableOpacity, StatusBar, Animated} from 'react-native';
import {Text} from '@components';
import {BaseColor} from '@config';
import styles from './styles';
import PropTypes from 'prop-types';

export default class Header extends Component {
  componentDidMount() {
    StatusBar.setBackgroundColor(BaseColor.primaryColor, true);
  }

  render() {
    const {
      style,
      styleLeft,
      styleCenter,
      styleRight,
      styleRightSecond,
      title,
      subTitle,
      onPressLeft,
      onPressRight,
      onPressRightSecond,
      renderCenter,
      titleStyle,
    } = this.props;

    return (
      <Animated.View style={[style]}>
        <View style={[styles.contain]}>
          <View style={{flex: 1}}>
            <TouchableOpacity
              style={[styles.contentLeft, styleLeft]}
              onPress={onPressLeft}>
              {this.props.renderLeft()}
            </TouchableOpacity>
          </View>
          <View style={[styles.contentCenter, styleCenter]}>
            {renderCenter && renderCenter()}
            <Text numberOfLines={1} headline style={titleStyle}>
              {title}
            </Text>
            {subTitle != '' && (
              <Text caption2 light>
                {subTitle}
              </Text>
            )}
          </View>
          <View style={styles.right}>
            <TouchableOpacity
              style={[styles.contentRightSecond, styleRightSecond]}
              onPress={onPressRightSecond}>
              {this.props.renderRightSecond()}
            </TouchableOpacity>
            <TouchableOpacity
              style={[styles.contentRight, styleRight]}
              onPress={onPressRight}>
              {this.props.renderRight()}
            </TouchableOpacity>
          </View>
        </View>
      </Animated.View>
    );
  }
}

Header.propTypes = {
  style: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  titleStyle: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  styleLeft: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  styleCenter: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  styleRight: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  styleRightSecond: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  renderLeft: PropTypes.func,
  renderRight: PropTypes.func,
  renderRightSecond: PropTypes.func,
  onPressRightSecond: PropTypes.func,
  onPressLeft: PropTypes.func,
  onPressRight: PropTypes.func,
  title: PropTypes.string,
  subTitle: PropTypes.string,
  barStyle: PropTypes.string,
};

Header.defaultProps = {
  style: {},
  titleStyle: {},
  styleLeft: {},
  styleCenter: {},
  styleRight: {},
  styleRightSecond: {},
  renderLeft: () => {},
  renderRight: () => {},
  renderRightSecond: () => {},
  onPressLeft: () => {},
  onPressRight: () => {},
  onPressRightSecond: () => {},
  title: 'Title',
  subTitle: '',
  barStyle: 'dark-content',
};
