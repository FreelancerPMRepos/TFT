import React from 'react';
import {StyleSheet} from 'react-native';
import {BaseColor} from '@config';

export default StyleSheet.create({
  contentTitle: {
    alignItems: 'flex-start',
    width: '100%',
    height: 32,
    justifyContent: 'center',
    marginTop: 10,
  },
  contain: {
    alignItems: 'flex-start',
    padding: 20,
    width: '100%',
  },
  textInput: {
    height: 46,
    backgroundColor: BaseColor.fieldColor,
    borderRadius: 5,
    padding: 10,
    width: '100%',
    color: BaseColor.grayColor,
  },
  thumb: {
    width: 100,
    height: 100,
    borderRadius: 50,
    marginBottom: 20,
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
  },
  gender: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    justifyContent: 'center',
  },
});
