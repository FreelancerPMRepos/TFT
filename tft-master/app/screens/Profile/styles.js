import React from 'react';
import {StyleSheet} from 'react-native';
import {BaseColor} from '@config';

export default StyleSheet.create({
  contentTitle: {
    alignItems: 'flex-start',
    width: '100%',
    height: 32,
    justifyContent: 'center',
  },
  contain: {
    alignItems: 'center',
    padding: 20,
    width: '100%',
  },
  textInput: {
    height: 56,
    backgroundColor: BaseColor.fieldColor,
    borderRadius: 5,
    padding: 10,
    width: '100%',
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
  contentModal: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  contentFilterBottom: {
    width: '100%',
    borderTopLeftRadius: 8,
    borderTopRightRadius: 8,
    paddingHorizontal: 20,
    backgroundColor: BaseColor.whiteColor,
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
    marginBottom: 10,
    justifyContent: 'space-between',
    borderBottomColor: BaseColor.textSecondaryColor,
    borderBottomWidth: 1,
  },
  bottomModal: {
    justifyContent: 'flex-end',
    margin: 0,
    marginTop: 200,
    // flex: 1,
  },
  lineRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingBottom: 20,
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
});
