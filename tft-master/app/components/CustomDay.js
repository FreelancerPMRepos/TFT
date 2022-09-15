/* eslint-disable react-native/no-inline-styles */
import React, {PureComponent} from 'react';
import {TouchableOpacity, View} from 'react-native';
import _ from 'lodash';
import {Text} from '@components';
import {BaseColor} from '@config';
import moment from 'moment';
import {getPriceofDates} from 'app/utils/booking';
export default class DateRangePicker extends PureComponent {
  render() {
    const {
      markedDates,
      date,
      state,
      setDays,
      dayPrices,
      periodType,
      fromFilter,
      defaultPeriods,
      selectedStartDay,
      selectEndDay,
      currencySymbol,
    } = this.props;
    const nDate = moment(date.dateString)
      .add(1, 'day')
      .format('YYYY-MM-DD');
    const selected =
      markedDates[date.dateString] && markedDates[date.dateString].selected;
    const nSelected = markedDates[nDate] && markedDates[nDate].selected;
    const textColor =
      markedDates[date.dateString] && markedDates[date.dateString].textColor;
    const color =
      markedDates[date.dateString] && markedDates[date.dateString].color;
    const startingDay =
      markedDates[date.dateString] && markedDates[date.dateString].startingDay;
    const endingDay =
      markedDates[date.dateString] && markedDates[date.dateString].endingDay;
    const disabled =
      state === 'disabled' ||
      (markedDates[date.dateString] && markedDates[date.dateString].disabled);
    let dPrices = 0;

    if (!fromFilter && !disabled) {
      dPrices = getPriceofDates(
        selectedStartDay,
        selectEndDay,
        !_.isEmpty(periodType) ? periodType : defaultPeriods,
        dayPrices,
        moment(date.dateString),
        _.isEmpty(periodType),
      ).dPrice;
    }
    const noPrice = dPrices <= 0 && !fromFilter;
    return (
      <TouchableOpacity
        style={[
          {
            flexGrow: 1,
            height: fromFilter ? 40 : 40,
            marginVertical: 2,
            width: '100%',
            alignItems: 'center',
            justifyContent: 'center',
          },
        ]}
        activeOpacity={disabled || noPrice ? 1 : 0.8}
        onPress={() => {
          !disabled && !noPrice && setDays(date);
        }}>
        {(!startingDay || nSelected) && (
          <View
            style={[
              {
                backgroundColor:
                  color ||
                  (selected ? BaseColor.xLightPrimaryColor : 'transparent'),
                width: startingDay || endingDay ? '50%' : '100%',
                height: 30,
                position: 'absolute',
              },
              startingDay ? {right: 0} : null,
              endingDay ? {left: 0} : null,
            ]}
          />
        )}
        {(startingDay || endingDay) && (
          <View
            style={{
              backgroundColor: BaseColor.primaryColor,
              height: 40,
              width: 40,
              borderRadius: 20,
              position: 'absolute',
            }}
          />
        )}
        <Text
          style={{
            textAlign: 'center',
            color: selected
              ? '#FFF'
              : disabled || noPrice
              ? '#d9e1e8'
              : textColor || '#2d4150',
            textDecorationLine: disabled || noPrice ? 'line-through' : 'none',
          }}>
          {date.day}
        </Text>
        {!fromFilter ? (
          <Text
            style={{
              textAlign: 'center',
              color: selected
                ? '#FFF'
                : disabled
                ? '#d9e1e8'
                : textColor || '#AAA',
              fontSize: 8,
              marginTop: 2,
            }}>
            {!disabled &&
              dPrices > 0 &&
              !fromFilter &&
              !_.isUndefined(dPrices) &&
              !_.isNull(dPrices) &&
              `${dPrices} ${dPrices !== '-' ? currencySymbol : ''}`}
          </Text>
        ) : null}
      </TouchableOpacity>
    );
  }
}

DateRangePicker.defaultProps = {
  theme: {markColor: '#00adf5', markTextColor: '#ffffff'},
};
