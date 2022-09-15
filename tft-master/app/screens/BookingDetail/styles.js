import {StyleSheet, Platform} from 'react-native';
import {BaseColor} from '@config';
import {version} from 'react';
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
  tabbar: {
    backgroundColor: 'white',
    height: 40,
  },
  tab: {
    width: 100,
  },
  indicator: {
    backgroundColor: BaseColor.primaryColor,
    height: 1,
  },
  label: {
    fontWeight: '400',
  },
  containProfileItem: {
    paddingLeft: 20,
    paddingRight: 20,
  },
  profileItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    borderBottomColor: BaseColor.textSecondaryColor,
    borderBottomWidth: 1,
    paddingBottom: 20,
    paddingTop: 20,
  },
  contentBoxTop: {
    top: 20,
    marginHorizontal: 10,
    padding: 10,
    width: '95%',
    borderRadius: 8,
    marginBottom: 20,
    backgroundColor: BaseColor.whiteColor,
    shadowOffset: {width: 3, height: 3},
    shadowColor: BaseColor.grayColor,
    shadowOpacity: 1.0,
    elevation: 5,
  },
  button: {
    marginRight: 20,
    height: 30,
    width: 100,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: BaseColor.primaryColor,
    borderRadius: 5,
  },
  buttonRound: {
    position: 'absolute',
    bottom: -20,
    right: 20,
    height: 50,
    width: 50,
    borderRadius: 25,
    overflow: 'hidden',
    backgroundColor: BaseColor.primaryColor,
    justifyContent: 'center',
    alignItems: 'center',
  },
  actionList: {
    flexDirection: 'row',
    justifyContent: 'flex-start',
    alignItems: 'flex-start',
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
});
