import {StyleSheet, Dimensions} from 'react-native';
import {BaseColor, isIphoneX} from '@config';

export default StyleSheet.create({
  contentModal: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    // height: Dimensions.get('window').height * 0.7,
  },
  bottomModal: {
    justifyContent: 'flex-end',
    margin: 0,
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
  contentCalendar: {
    borderRadius: 8,
    width: '100%',
    backgroundColor: 'white',
  },
  contentActionCalendar: {
    marginTop: 10,
    flexDirection: 'row',
    justifyContent: 'space-between',
    padding: 15,
    backgroundColor: '#fff',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 4,
    },
    shadowOpacity: 0.3,
    shadowRadius: 4.65,

    elevation: 8,
    borderBottomLeftRadius: 5,
    borderBottomRightRadius: 5,
  },
  contentFilterBottom: {
    width: '100%',
    borderTopLeftRadius: 8,
    borderTopRightRadius: 8,
    backgroundColor: BaseColor.whiteColor,
    minHeight: Dimensions.get('window').height / 2 + 20,
    paddingBottom: isIphoneX() ? 50 : 0,
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
  img: {
    height: 25,
    width: 25,
    marginRight: 15,
  },
});
