import {StyleSheet} from 'react-native';
import {BaseColor} from '@config';
import * as Utils from '@utils';

export default StyleSheet.create({
  contain: {
    padding: 20,
    flex: 1,
  },
  line: {
    width: '100%',
    height: 1,
    borderWidth: 0.5,
    borderColor: BaseColor.dividerColor,
    borderStyle: 'dashed',
    marginVertical: 10,
  },
  code: {
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 10,
  },
  imageBackground: {
    height: 50,
    width: 113,
    margin: 10,
    resizeMode: 'contain',
    alignSelf: 'center',
  },
  bottomLine: {
    height: 0.5,
    width: '100%',
    backgroundColor: '#a9a9a9',
    marginTop: 10,
  },
  dotedLine: {
    height: 1,
    flex: 1,
    // width: '100%',
    borderRadius: 1,
    borderWidth: 1,
    borderColor: '#a9a9a9',
    borderStyle: 'dotted',
    // margin Top: 25,
  },
  rowDisplayView: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  columnDisplayView: {flex: 1, justifyContent: 'flex-start'},
  qrCodeImage: {
    height: (Utils.getWidthDevice() - 80) / 4,
    width: Utils.getWidthDevice() - 80,
    margin: 10,
    resizeMode: 'contain',
    alignSelf: 'center',
  },
});
