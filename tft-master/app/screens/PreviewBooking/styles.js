import React from 'react';
import {StyleSheet, Platform} from 'react-native';
import {BaseColor, BaseStyle} from '@config';
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
    // flexWrap: 'wrap',
  },
  contentBoxTop: {
    padding: 13,
    paddingBottom: 0,
    width: '100%',
    borderRadius: 8,
    alignItems: 'center',
    backgroundColor: BaseColor.whiteColor,
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
    width: 36,
    height: 36,
    borderRadius: 18,
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
  paymentButton: {
    flexDirection: 'row',
    paddingVertical: 5,
    paddingHorizontal: 10,
    width: '70%',
    height: 40,
    alignSelf: 'center',
    alignItems: 'center',
    borderRadius: 5,
    borderWidth: 0.5,
    borderColor: BaseColor.primaryColor,
    marginBottom: 10,
  },
  closeIconWrapper: {
    height: 24,
    width: 24,
    borderRadius: 12,
    backgroundColor: BaseColor.primaryColor,
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
    height: headerHeight + 10,
    zIndex: 100,
  },
  detailCard: {
    margin: 20,
    width: '100%',
    marginTop: 15,
    paddingTop: 10,
    borderRadius: 7,
    backgroundColor: BaseColor.whiteColor,
    shadowColor: BaseColor.primaryColor,
    shadowOffset: {
      width: 0,
      height: 1,
    },
    shadowOpacity: 0.32,
    shadowRadius: 4.5,
    elevation: 9,
  },
  singlePeriod: {
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
});
