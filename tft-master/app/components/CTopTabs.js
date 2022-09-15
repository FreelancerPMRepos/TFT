/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {View, Text, StyleSheet, TouchableOpacity, Keyboard} from 'react-native';
import _ from 'lodash';
import {BaseColor} from '@config';

const styles = StyleSheet.create({
  container: {
    width: '100%',
    flexDirection: 'row',
    alignSelf: 'flex-start',
    backgroundColor: '#fff',
    justifyContent: 'flex-start',
  },
  tabItem: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    flexDirection: 'row',
  },
  badgeCount: {
    backgroundColor: BaseColor.brandBtnColor,
    paddingHorizontal: 4,
    minWidth: 20,
    marginLeft: 5,
  },
  bdgeTxt: {
    color: '#fff',
    fontSize: 10,
  },
  badgeWrapper: {
    height: 20,
    width: 20,
    borderRadius: 10,
    // backgroundColor: BaseColor.primaryColor,
    backgroundColor: '#f42f4c',
    justifyContent: 'center',
    alignItems: 'center',
    marginLeft: 5,
    shadowColor: '#f42f4c',
    // shadowColor: BaseColor.lightPrimaryColor,
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.23,
    shadowRadius: 2.62,

    elevation: 4,
  },
});

class CTopTabs extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }
  render() {
    const {
      tabs,
      onChangeIndex,
      tKey,
      selectedIndex,
      tabContainerStyle,
      tabTitleStyle,
      mainContainerStyle,
      showBadge,
      count,
    } = this.props;
    return (
      <View style={[styles.container, mainContainerStyle]}>
        {_.isArray(tabs) && tabs.length > 0
          ? tabs.map((tab, index) => (
              <TouchableOpacity
                activeOpacity={0.7}
                key={`top_item_${tKey}_${tab.id}`}
                style={[
                  styles.tabItem,
                  {
                    borderBottomColor:
                      selectedIndex === index + 1
                        ? BaseColor.primaryColor
                        : '#bac3d2',
                    borderBottomWidth: selectedIndex === index + 1 ? 2 : 0.3,
                  },
                  tabContainerStyle,
                ]}
                onPress={
                  onChangeIndex
                    ? () => {
                        Keyboard.dismiss();
                        onChangeIndex(index + 1);
                      }
                    : null
                }>
                <Text
                  allowFontScaling={false}
                  style={[
                    {
                      lineHeight: 21.67,
                      fontSize: 10,
                      color:
                        selectedIndex === index + 1
                          ? BaseColor.primaryColor
                          : '#111',
                      // borderBottomColor: selectedIndex === index + 1 ? BaseColor.primary : '#DDD',
                      // borderBottomWidth: 2,
                      // paddingBottom: 15,
                      // bottom: -10,
                    },
                    tabTitleStyle,
                  ]}>
                  {tab.title}
                </Text>
                {showBadge && Number(count) > 0 && index === 1 ? (
                  <View style={styles.badgeWrapper}>
                    <Text caption2 style={{color: '#fff', fontSize: 12}}>
                      {count}
                    </Text>
                  </View>
                ) : null}
              </TouchableOpacity>
            ))
          : null}
      </View>
    );
  }
}

CTopTabs.propTypes = {
  tabs: PropTypes.arrayOf(PropTypes.any),
  selectedIndex: PropTypes.number,
  onChangeIndex: PropTypes.func,
  tKey: PropTypes.string,
  count: PropTypes.string,
  showBadge: PropTypes.bool,
  tabContainerStyle: PropTypes.oneOfType([PropTypes.object, PropTypes.any]),
  tabTitleStyle: PropTypes.oneOfType([PropTypes.object, PropTypes.any]),
  mainContainerStyle: PropTypes.oneOfType([PropTypes.object, PropTypes.any]),
};

CTopTabs.defaultProps = {
  tabs: [],
  selectedIndex: 0,
  onChangeIndex: null,
  tKey: '',
  tabContainerStyle: {},
  tabTitleStyle: {},
  mainContainerStyle: {},
  showBadge: false,
  count: '0',
};

// make this component available to the app
export default CTopTabs;
