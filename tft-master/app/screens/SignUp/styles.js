import React from 'react';
import {StyleSheet, Platform} from 'react-native';
import {BaseColor} from '@config';

const IOS = Platform.OS === 'ios';
export default StyleSheet.create({
  contain: {
    alignItems: 'center',
    padding: 20,
    // width: '100%',
  },
  contentTitle: {
    alignItems: 'flex-start',
    width: '100%',
    height: 32,
    justifyContent: 'center',
    marginTop: 10,
  },
  textInput: {
    height: 46,
    backgroundColor: BaseColor.fieldColor,
    borderRadius: 5,
    marginTop: 10,
    padding: 10,
    width: '100%',
  },
  dropdownStyle: {
    marginLeft: 0,
    width: 130,
  },
  MainAlertView: {
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'white',
    height: 200,
    width: '90%',
    borderColor: IOS ? '#fff' : '#ddd',
    borderRadius: 10,
    overflow: IOS ? 'hidden' : 'visible',
    elevation: 8,
    borderWidth: IOS ? 0 : 1,
  },
  AlertTitle: {
    fontSize: 20,
    color: '#000',
    textAlign: 'center',
    margin: 10,
  },
  underlineStyleBase: {
    width: 30,
    height: 45,
    borderWidth: 0,
    borderBottomWidth: 1,
    color: '#000',
  },
  underlineStyleHighLighted: {
    borderColor: BaseColor.primaryColor,
  },
  buttonStyle: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  TextStyle: {
    color: '#000',
    textAlign: 'center',
    fontSize: 17,
  },
});
