import {StyleSheet} from 'react-native';
import {BaseColor} from '@config';

export default StyleSheet.create({
  overViewWrapper: {
    // shadowColor: 'rgba(66, 133, 244, 0.1)',
    // shadowOffset: {width: 12, height: 0},
    // shadowRadius: 12,
    borderRadius: 5,
    borderColor: '#708095',
    borderStyle: 'solid',
    borderWidth: 0.7,
    // elevation: 2,
    marginTop: 10,
    paddingVertical: 16,
    paddingHorizontal: 18,
  },
  starTypeWrapper: {
    // marginTop: 10,
    paddingVertical: 13,
    borderBottomWidth: 0.5,
    borderColor: '#BAC3D2',
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
});
