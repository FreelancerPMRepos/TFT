import React from 'react';
import {StyleSheet, Platform} from 'react-native';
import {BaseColor} from '@config';

const IOS = Platform.OS === 'ios';
export default StyleSheet.create({
  contain: {
    flex: 1,
  },
  mainContainerStyle: {
    borderColor: '#bac3d2',
    borderWidth: 0,
    backgroundColor: '#FFF',
    // backgroundColor: '#f5f8fe',
  },
  tabContainerStyle: {
    height: 35,
    borderColor: '#ddd',
    width: 100,
    borderTopWidth: 0,
    // paddingVertical: 8,
  },
  tabTitleStyle: {
    fontSize: 13,
  },
  listContain: {
    shadowOffset: {height: 1},
    shadowColor: BaseColor.grayColor,
    shadowOpacity: 1.0,
    margin: 10,
    elevation: 3,
    borderWidth: IOS ? 0 : 1,
    borderColor: '#ddd',
    borderRadius: IOS ? 0 : 8,
    // marginHorizontal: 20,
  },
  nameContent: {
    // borderBottomWidth: 2,
    paddingHorizontal: 12,
    paddingVertical: 7,
    backgroundColor: BaseColor.fieldColor,
    borderBottomColor: BaseColor.lightPrimaryColor,
    borderTopRightRadius: 8,
    borderTopLeftRadius: 8,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  validContent: {
    flexDirection: 'row',
    paddingHorizontal: 12,
    paddingVertical: 7,
    backgroundColor: BaseColor.fieldColor,
    justifyContent: 'space-between',
    borderBottomRightRadius: 8,
    borderBottomLeftRadius: 8,
  },
  mainContent: {
    flex: 1,
    // backgroundColor: 'aqua',
    backgroundColor: BaseColor.whiteColor,
    paddingHorizontal: 12,
    paddingVertical: 20,
    flexDirection: 'row',
    // alignItems: 'center',
    justifyContent: 'space-between',
  },
  periodWrapper: {
    // backgroundColor: 'red',
    // alignSelf: 'flex-start',
    marginLeft: 7,
    flexDirection: 'row',
    alignItems: 'center',
  },
  singlePeriod: {
    backgroundColor: BaseColor.primaryColor,
    padding: 7,
    paddingVertical: 4,
    borderWidth: 1,
    borderColor: BaseColor.primaryColor,
    borderRadius: 4,
    marginHorizontal: 4,
  },
});
