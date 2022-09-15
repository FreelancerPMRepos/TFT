/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {TouchableOpacity, StyleSheet, ActivityIndicator} from 'react-native';
import {BaseColor} from '@config';
import PropTypes from 'prop-types';
import {Text} from '@components';
import styles from './styles';

export default class Button extends Component {
  render() {
    const {
      style,
      styleText,
      icon,
      outline,
      full,
      round,
      loading,
      disable,
      iconLeft,
      ...rest
    } = this.props;

    return (
      <TouchableOpacity
        {...rest}
        style={StyleSheet.flatten([
          styles.default,
          outline && styles.outline,
          full && styles.full,
          round && styles.round,
          style,
        ])}
        activeOpacity={0.9}
        disabled={disable}>
        {iconLeft ? iconLeft : null}
        <Text
          style={StyleSheet.flatten([
            styles.textDefault,
            outline && styles.textOuline,
            styleText,
          ])}
          numberOfLines={1}>
          {this.props.children || 'Button'}
        </Text>
        {icon ? icon : null}
        {loading ? (
          <ActivityIndicator
            size="small"
            color={outline ? BaseColor.primaryColor : BaseColor.whiteColor}
            style={{paddingLeft: 5}}
          />
        ) : null}
      </TouchableOpacity>
    );
  }
}

Button.propTypes = {
  style: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  icon: PropTypes.node,
  iconLeft: PropTypes.node,
  outline: PropTypes.bool,
  full: PropTypes.bool,
  round: PropTypes.bool,
  loading: PropTypes.bool,
  disable: PropTypes.bool,
};

Button.defaultProps = {
  style: {},
  icon: null,
  iconLeft: null,
  outline: false,
  full: false,
  round: false,
  loading: false,
  disable: false,
};
