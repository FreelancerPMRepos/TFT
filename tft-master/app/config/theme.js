import {StyleSheet, I18nManager} from 'react-native';
import {BaseColor} from './color';
import {FontFamily} from '@config';

/**
 * Common basic style defines
 */
export const BaseStyle = StyleSheet.create({
  tabBar: {
    borderTopWidth: 0,
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 12,
    },
    shadowOpacity: 0.58,
    shadowRadius: 16.0,

    elevation: 24,
    backgroundColor: BaseColor.whiteColor,
  },
  bodyPaddingDefault: {
    paddingHorizontal: 20,
  },
  bodyMarginDefault: {
    marginHorizontal: 20,
  },
  textInput: {
    height: 46,
    backgroundColor: BaseColor.fieldColor,
    borderRadius: 5,
    padding: 10,
    width: '100%',
    justifyContent: 'center',
    textAlign: I18nManager.isRTL ? 'right' : 'auto',
    fontFamily: FontFamily.default,
  },
  safeAreaView: {
    flex: 1,
  },
  iphoneXStyle: {
    paddingBottom: 25,
  },
});
