import React from 'react';
import {StyleSheet, Platform, Dimensions} from 'react-native';
import {BaseColor} from '@config';
import {isIphoneX} from '../../config/isIphoneX';
import {getStatusBarHeight} from 'react-native-status-bar-height';

const IOS = Platform.OS === 'ios';
let headerHeight;
if (!IOS) {
  headerHeight = 55;
} else if (IOS && isIphoneX()) {
  headerHeight = 90;
} else {
  headerHeight = 70;
}

export default StyleSheet.create({
  image: {
    width: '100%',
    height: 200,
    // borderRadius: 50,
    // borderWidth: 3,
    borderColor: BaseColor.primaryColor,
  },
  container: {
    alignItems: 'center',
    justifyContent: 'center',
    // padding: 20,
  },
  rateView: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    // borderBottomWidth: 1,
    borderColor: '#ddd',
    padding: 8,
    // flex: 1,
  },
  linearGradient: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    width: '100%',
    paddingTop: IOS ? getStatusBarHeight() : 0,
    height: headerHeight,
    zIndex: 100,
  },
  animationWrap: {
    position: 'absolute',
    top: 0,
    left: 0,
    height: Dimensions.get('window').height,
    width: Dimensions.get('window').width,
    alignItems: 'center',
    justifyContent: 'center',
    // backgroundColor: 'red'
  },
  animation: {
    height: Dimensions.get('window').height,
    width: Dimensions.get('window').width,
  // margin: 15
  },
});
