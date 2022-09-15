import {StyleSheet, Dimensions} from 'react-native';
import * as Utils from '@utils';

const deviceHeight = Utils.getHeightDevice();
const deviceWidth = Utils.getWidthDevice();

export default StyleSheet.create({
  contain: {
    paddingHorizontal: 20,
    marginVertical: 20,
  },
  wrapper: {
    backgroundColor: '#FFF',
    flex: 1,
  },
  contentPage: {
    bottom: 50,
  },
  contentActionBottom: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 25,
  },
  slide: {
    flex: 1,
    // backgroundColor: 'red',
    paddingTop: Dimensions.get('window').height * 0.6,
  },
  contentBox: {
    // backgroundColor: 'yellow',
    paddingHorizontal: 40,
    marginBottom: 100,
  },
  slideTitle: {
    marginTop: 0,
    textAlign: 'center',
  },
  slideSubTitle: {
    color: '#333',
    marginTop: 10,
    textAlign: 'center',
  },
  skip: {
    color: '#666',
    textAlign: 'right',
    padding: 20,
  },
});
