import React from 'react';
import {StyleSheet, Dimensions} from 'react-native';
import {BaseColor, isIphoneX} from '@config';

export default StyleSheet.create({
  contentPickDate: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 15,
    borderRadius: 8,
    backgroundColor: BaseColor.fieldColor,
    padding: 6,
  },
  itemPick: {
    flex: 1,
    justifyContent: 'center',
  },
  linePick: {
    width: 1,
    backgroundColor: BaseColor.dividerColor,
    marginRight: 10,
  },
  img: {
    width: 22,
    height: 22,
    // borderRadius: 18,
    marginRight: 10,
    resizeMode: 'contain',
  },
  total: {
    flex: 6,
    borderRadius: 8,
    backgroundColor: BaseColor.fieldColor,
    padding: 10,
    marginRight: 15,
  },
  field: {
    flex: 1,
    borderRadius: 8,
    backgroundColor: BaseColor.fieldColor,
    padding: 10,
  },
  contentQuest: {
    // marginTop: 15,
    flexDirection: 'row',
    marginBottom: 15,
  },
  rangeContainer: {
    marginTop: 15,
    // flexDirection: 'row',
    marginBottom: 15,
  },
  contentModal: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  contentCalendar: {
    borderRadius: 8,
    width: '100%',
    backgroundColor: 'white',
  },
  contentActionCalendar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    padding: 15,
  },
  bottomModal: {
    justifyContent: 'flex-end',
    margin: 0,
    // marginTop: 200,
    // flex: 1,
  },
  lineRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingBottom: 20,
    marginBottom: isIphoneX() ? 50 : 0,
  },
  iconRight: {
    width: 100,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  contentFilterBottom: {
    width: '100%',
    borderTopLeftRadius: 8,
    borderTopRightRadius: 8,
    // paddingHorizontal: 20,
    backgroundColor: BaseColor.whiteColor,
    minHeight: Dimensions.get('window').height / 2 + 20,
  },
  flatList: {
    paddingHorizontal: 20,
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
  contentActionModalBottom: {
    flexDirection: 'row',
    paddingVertical: 10,
    // marginBottom: 10,
    justifyContent: 'space-between',
    borderBottomColor: BaseColor.textSecondaryColor,
    borderBottomWidth: 1,
    paddingHorizontal: 20,
  },
  item: {
    paddingTop: 15,
    paddingBottom: 15,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    borderBottomWidth: 1,
    borderBottomColor: BaseColor.fieldColor,
  },
  contentResultRange: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 10,
  },
  contentRange: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 5,
    marginTop: 10,
  },
  btnWrapper: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingBottom: 25,
    // width: '42%',
  },
  resetBtn: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: BaseColor.primaryColor,
    width: '45%',
  },
  titleStyle: {
    color: BaseColor.primaryColor,
  },
});
