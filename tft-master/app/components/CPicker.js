/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {Picker, View} from 'react-native';
import {BaseColor} from '@config';
import _ from 'lodash';

class CPicker extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const items =
      _.isArray(this.props.Items) && this.props.Items.length > 0
        ? this.props.Items
        : [];
    return (
      <View
        style={
          this.props.containerStyle
            ? this.props.containerStyle
            : {width: '100%', color: '#000'}
        }>
        <Picker
          selectedValue={this.props.selectedValue}
          style={this.props.style ? this.props.style : {width: 300}}
          onValueChange={this.props.onValueChange}>
          {items.map(element => (
            <Picker.Item
              label={element.label}
              value={element.value}
              color={
                element.value == this.props.selectedValue
                  ? BaseColor.primaryColor
                  : '#000'
              }
            />
          ))}
        </Picker>
      </View>
    );
  }
}

export default CPicker;
