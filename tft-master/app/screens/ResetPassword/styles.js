import React from 'react';
import {StyleSheet, Platform} from 'react-native';
import {BaseColor} from '@config';

const IOS = Platform.OS === 'ios';
export default StyleSheet.create({
  textInput: {
    height: 46,
    backgroundColor: BaseColor.fieldColor,
    borderRadius: 5,
    marginTop: 65,
    padding: 10,
    width: '100%',
  },
  TextStyle: {
    color: '#000',
    textAlign: 'center',
    fontSize: 17,
  },
  contentTitle: {
    alignItems: 'flex-start',
    width: '100%',
    height: 'auto',
    justifyContent: 'center',
    marginVertical: 5,
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
  buttonStyle: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  AlertMessage: {
    fontSize: 22,
    color: '#000',
    textAlign: 'center',
    textAlignVertical: 'center',
    padding: 10,
    height: '40%',
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
});
