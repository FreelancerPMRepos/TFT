import {StyleSheet} from 'react-native';
import * as Utils from '@utils';
import {BaseColor, isIphoneX} from '@config';

export default StyleSheet.create({
  //block css
  blockImage: {
    height: Utils.scaleWithPixel(200),
    width: '100%',
  },
  blockContentAddress: {
    flexDirection: 'row',
    marginTop: 3,
    alignItems: 'center',
  },
  blockContentDetail: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-end',
    marginTop: 10,
  },
  blockListContentIcon: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    height: 50,
    width: '100%',
    marginTop: 4,
  },
  contentService: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    marginTop: 10,
    borderColor: BaseColor.fieldColor,
    borderBottomWidth: 1,
  },
  serviceItemBlock: {
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 10,
    width: 60,
  },
  //list css
  listImage: {
    height: Utils.scaleWithPixel(isIphoneX() ? 100 : 110),
    width: Utils.scaleWithPixel(isIphoneX() ? 100 : 110),
    // borderRadius: 8
    borderTopLeftRadius: 8,
    borderBottomLeftRadius: 8,
  },
  listContent: {
    flexDirection: 'row',
  },
  listContentRight: {
    paddingHorizontal: 10,
    paddingVertical: 2,
    flex: 1,
  },
  listContentRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 5,
  },
  //gird css
  girdImage: {
    borderRadius: 8,
    height: Utils.scaleWithPixel(120),
    width: '100%',
  },
  girdContent: {
    // flex: 1,
  },
  girdContentLocation: {
    flexDirection: 'row',
    justifyContent: 'flex-start',
    marginTop: 5,
  },
  girdContentRate: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 10,
  },
  offerImg: {
    position: 'absolute',
    top: 0,
    left: 10,
    height: 25,
    width: 25,
  },
  offerImgBlock: {
    position: 'absolute',
    top: 0,
    left: 20,
    height: 35,
    width: 35,
  },
  bookmarkHeart: {
    position: 'absolute',
    bottom: 5,
    right: 10,
    // height: 35,
    // width: 35,
  },
  poolSize: {
    position: 'absolute',

    bottom: 5,
    right: 10,
    paddingVertical: 2,
    paddingHorizontal: 7,
    backgroundColor: BaseColor.primaryColor,
    color: '#fff',
    borderRadius: 5,
    overflow: 'hidden',
    fontSize: 12,
  },
  poolSizeGrid: {
    position: 'absolute',
    top: 5,
    right: 10,
    paddingVertical: 2,
    paddingHorizontal: 7,
    backgroundColor: BaseColor.primaryColor,
    color: '#fff',
    borderRadius: 5,
    overflow: 'hidden',
    fontSize: 12,
  },
  girdContentOffer: {
    position: 'absolute',
    flex: 1,
    flexDirection: 'row',
    paddingHorizontal: 10,
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  amenitiesWrapper: {
    flexDirection: 'row',
    marginBottom: 7,
  },
  img: {
    width: 24,
    height: 24,
    // borderRadius: 18,
    resizeMode: 'center',
  },
  rowStyle: {},
});
