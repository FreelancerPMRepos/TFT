import React from 'react';
import {StyleSheet} from 'react-native';
import {BaseColor} from '@config';

export default StyleSheet.create({
  contain: {
    borderRadius: 8,
    backgroundColor: BaseColor.fieldColor,
    paddingHorizontal: 15,
    paddingVertical: 10,
  },
  contentLeft: {
    // flex: 8,
    flexDirection: 'row',
    justifyContent: 'flex-start',
    alignItems: 'flex-start',
    // backgroundColor:'red'
  },
  thumb1: {
    width: 40,
    height: 40,
    borderRadius: 20,
    marginRight: 5,
  },
  contentRight: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'flex-end',
    paddingTop: 5
    // backgroundColor:'red'
  },
  contentRate: {
    flex: 1,
    marginTop: 5,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    // backgroundColor:'red'
  },
  thumb: {
    width: 40,
    height: 40,
    borderRadius: 20,
    marginRight: 5,
    backgroundColor: BaseColor.primaryColor,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
  },
  profilePictxt: {
    color: '#fff',
    fontSize: 15,
  },
});
