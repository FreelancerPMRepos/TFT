import {StyleSheet} from 'react-native';
import {BaseColor} from '@config';

export default StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: BaseColor.primaryColor,
    justifyContent: 'center',
    alignItems: 'center',
  },
  logo: {
    width: 190,
    height: 190,
    color: BaseColor.primaryColor,
  },
});
