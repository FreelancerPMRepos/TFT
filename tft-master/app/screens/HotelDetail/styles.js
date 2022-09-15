import React from 'react';
import {StyleSheet, Platform, Dimensions} from 'react-native';
import {getStatusBarHeight} from 'react-native-status-bar-height';
import {BaseColor, isIphoneX} from '@config';
import {BaseSetting} from '../../config/setting';

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
  imgBanner: {
    width: '100%',
    height: 250,
    position: 'relative',
  },
  blockView: {
    flex: 1,
    width: '100%',
    paddingVertical: 10,
  },
  contentService: {
    paddingVertical: 10,
    flexWrap: 'wrap',
    flexDirection: 'row',
    padding: 20,
  },
  contentBoxTop: {
    // padding: 20,
    width: '100%',
    borderRadius: 8,
    alignItems: 'center',
    // marginBottom: 20,
    backgroundColor: BaseColor.whiteColor,
    // shadowOffset: {width: 3, height: 3},
    // shadowColor: BaseColor.grayColor,
    // shadowOpacity: 1.0,
    // elevation: 5,
  },
  circlePoint: {
    width: 60,
    height: 60,
    borderRadius: 30,
    marginRight: 5,
    backgroundColor: BaseColor.primaryColor,
    alignItems: 'center',
    justifyContent: 'center',
  },
  contentRateDetail: {
    flexDirection: 'row',
    paddingTop: 20,
  },
  lineBaseRate: {
    width: '100%',
    height: 12,
    borderRadius: 8,
    backgroundColor: BaseColor.textSecondaryColor,
  },
  linePercent: {
    width: '80%',
    height: 12,
    borderTopLeftRadius: 8,
    borderBottomLeftRadius: 8,
    backgroundColor: BaseColor.primaryColor,
    position: 'absolute',
    bottom: 0,
  },
  contentLineRate: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'flex-end',
  },
  listContentIcon: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    height: 50,
    width: '100%',
  },
  map: {
    ...StyleSheet.absoluteFillObject,
  },
  mapView: {
    flexWrap: 'wrap',
    justifyContent: 'flex-end',
    alignContent: 'flex-end',
    width: BaseSetting.deviceWidth - 40,
    height: 200,
    borderRadius: 13,
    // marginVertical: 20,
  },
  iconButton: {
    position: 'absolute',
    top: 10,
    right: 10,
  },
  itemReason: {
    paddingLeft: 10,
    marginTop: 10,
    flexDirection: 'row',
  },
  contentButtonBottom: {
    borderTopColor: BaseColor.textSecondaryColor,
    borderTopWidth: 1,
    paddingVertical: 10,
    paddingHorizontal: 20,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  img: {
    width: 32,
    height: 32,
    // borderRadius: 18,
    resizeMode: 'contain',
  },
  divideLine: {
    backgroundColor: '#a9a9a9',
    height: 0.5,
    width: '100%',
    margin: 10,
  },
  showPriceContainer: {
    flex: 1,
    width: '100%',
    justifyContent: 'center',
    alignItems: 'center',
  },
  priceDetailContainer: {
    flex: 1,
    flexDirection: 'row',
    width: '100%',
    marginHorizontal: 10,
    alignItems: 'center',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  modalContainer: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.3)',
    justifyContent: 'center',
    alignItems: 'center',
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
  wrapContent: {
    flexWrap: 'wrap',
    flexDirection: 'row',
    borderBottomWidth: 1,
    borderColor: BaseColor.textSecondaryColor,
    paddingBottom: 10,
  },
  titleWrapper: {
    flexDirection: 'row',
    // marginBottom: 7,
    alignItems: 'center',
    flex: 1,
    justifyContent: 'space-between',
    width: '100%',
    paddingHorizontal: 10,
    paddingVertical: 5,
  },
  contentWrapper: {
    width: '100%',
    borderBottomWidth: 1,
    borderColor: '#ddd',
  },
  animationWrap: {
    position: 'absolute',
    top: 0,
    left: 0,
    height: Dimensions.get('window').height,
    width: Dimensions.get('window').width,
    alignItems: 'center',
    justifyContent: 'center',
  },
  animation: {
    width: 270,
    height: 270,
  },
  detailCard: {
    // margin: 20,
    marginHorizontal: 18,
    marginBottom: 15,
    paddingTop: 10,
    borderRadius: 7,
    backgroundColor: BaseColor.whiteColor,
    shadowColor: BaseColor.primaryColor,
    shadowOffset: {
      width: 0,
      height: 4,
    },
    shadowOpacity: 0.32,
    shadowRadius: 5.46,
    elevation: 9,
  },
  singlePeriod: {
    // backgroundColor: 'red',
    paddingVertical: 5,
    borderRadius: 3,
    paddingHorizontal: 7,
  },
  singleTag: {
    borderWidth: 1,
    borderColor: BaseColor.primaryColor,
    backgroundColor: BaseColor.whiteColor,
    marginTop: 10,
  },
  pWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  bottomModal: {
    justifyContent: 'flex-end',
    margin: 0,
  },
  modalWrapper: {
    borderRadius: 8,
    backgroundColor: BaseColor.whiteColor,
    flex: 1,
    alignItems: 'flex-start',
  },
  contentFilterBottom: {
    width: '100%',
    borderTopLeftRadius: 8,
    borderTopRightRadius: 8,
    backgroundColor: BaseColor.whiteColor,
  },
  modalHeader: {
    padding: 10,
    alignItems: 'center',
    borderBottomWidth: 1,
    borderColor: '#ddd',
    backgroundColor: BaseColor.whiteColor,
    shadowColor: '#ddd',
    shadowOffset: {
      width: 0,
      height: 4,
    },
    shadowOpacity: 0.32,
    shadowRadius: 5.46,
    elevation: 0,
    borderTopRightRadius: 8,
    borderTopLeftRadius: 8,
  },
  subHeader: {
    padding: 10,
    flexDirection: 'row',
    justifyContent: 'space-between',
    // alignItems: 'center',
    paddingHorizontal: 15,
  },
  footer: {
    padding: 10,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 15,
    borderTopWidth: 1,
    borderColor: '#ddd',
    paddingBottom: isIphoneX() ? 35 : 20,
  },
  modalContentWrapper: {
    flex: 1,
    //  maxHeight: Dimensions.get('window').height * 0.7,
  },
  contentSwipeDown: {
    paddingTop: 10,
    alignItems: 'center',
  },
  lineSwipeDown: {
    width: 30,
    height: 2.5,
    backgroundColor: BaseColor.dividerColor,
  },
  contentCalendar: {
    borderRadius: 8,
    backgroundColor: 'white',
  },
  actionList: {
    flexDirection: 'row',
    justifyContent: 'flex-start',
    alignItems: 'flex-start',
  },
});
