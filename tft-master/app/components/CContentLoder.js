import React from 'react';
import {Image} from 'react-native';
import ContentLoader, {Rect, Circle} from 'react-content-loader/native';
import * as Utils from '@utils';
import {SharedElement} from 'react-native-shared-element';
import {View} from 'react-native-animatable';
import {isIphoneX} from '@config';

const deviceWidth = Utils.getWidthDevice();
const deviceHeight = Utils.getHeightDevice();

const MyLoader = () => (
  <ContentLoader
    speed={1}
    width={deviceWidth}
    height={200}
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect
      x="20"
      y="0"
      rx="10"
      ry="10"
      width={(deviceWidth - 55) / 2}
      height="100"
    />
    <Rect
      x="20"
      y="110"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
    <Rect
      x="20"
      y="130"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
    <Rect
      x="20"
      y="150"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
    <Rect
      x="20"
      y="170"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
    <Rect
      x={(deviceWidth - 55) / 2 + 35}
      y="0"
      rx="10"
      ry="10"
      width={(deviceWidth - 55) / 2}
      height="100"
    />
    <Rect
      x={(deviceWidth - 55) / 2 + 35}
      y="110"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
    <Rect
      x={(deviceWidth - 55) / 2 + 35}
      y="130"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
    <Rect
      x={(deviceWidth - 55) / 2 + 35}
      y="150"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
    <Rect
      x={(deviceWidth - 55) / 2 + 35}
      y="170"
      rx="3"
      ry="3"
      width={(deviceWidth - 55) / 2}
      height="10"
    />
  </ContentLoader>
);

export default MyLoader;

const ListLoader = () => (
  <ContentLoader
    speed={2}
    width={deviceWidth + 15}
    height={isIphoneX() ? deviceHeight / 2.5 : deviceHeight / 2}
    // viewBox="0 0 500 400"
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect x="0" y="0" rx="9" ry="9" width={deviceWidth + 15} height="173" />
    <Rect x="15" y="199" rx="0" ry="0" width="177" height="12" />
    <Rect x="266" y="197" rx="0" ry="0" width="104" height="12" />
    <Rect x="15" y="224" rx="0" ry="0" width="123" height="10" />
    <Rect x="15" y="248" rx="4" ry="4" width={deviceWidth - 20} height="42" />
  </ContentLoader>
);

const ImageLoder = () => (
  <ContentLoader
    speed={1}
    width={(deviceWidth - 35) / 2}
    height={Utils.scaleWithPixel(120)}
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect
      x="0"
      y="0"
      rx="0"
      ry="0"
      width={(deviceWidth - 55) / 2}
      height={Utils.scaleWithPixel(120)}
    />
  </ContentLoader>
);

const ReviewLoader = () => (
  <ContentLoader
    speed={2}
    width={deviceWidth}
    height={deviceHeight}
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect x="15" y="12" rx="6" ry="6" width={deviceWidth - 30} height="223" />
    <Rect x="15" y="250" rx="6" ry="6" width={deviceWidth - 30} height="124" />
    <Rect x="15" y="390" rx="6" ry="6" width={deviceWidth - 30} height="124" />
  </ContentLoader>
);

const DetailLoder = props => {
  console.log('Details Loader ===> ', props);
  return (
    <View style={{flex: 1}}>
      <SharedElement id={`image_${props.id}`}>
        <Image
          style={{height: 400, width: 400}}
          resizeMode="cover"
          source={{uri: props.image}}
        />
      </SharedElement>
      <ContentLoader
        width={deviceWidth}
        height={deviceHeight}
        speed={2}
        primaryColor="#f3f3f3"
        secondaryColor="#ecebeb">
        <Rect
          x="0"
          y="0"
          rx="7"
          ry="7"
          width={deviceWidth}
          height={deviceHeight * 0.35}
        />
        <Rect
          x="15.5"
          y="300.2"
          rx="8"
          ry="8"
          width={deviceWidth - 30}
          height="100"
        />
        <Circle cx="55" cy="390" r="23" />
        {/* <Circle cx="130" cy="284.7" r="23" /> */}
        {/* <Circle cx="133.0495097567964" cy="299.7495097567964" r="23" /> */}
        <Circle cx="140" cy="390" r="23" />
        <Circle cx="230" cy="390" r="23" />
        <Circle cx="300" cy="390" r="23" />
        <Circle cx="55" cy="445" r="23" />
        <Circle cx="140" cy="445" r="23" />
        <Circle cx="230" cy="445" r="23" />
        <Circle cx="300" cy="445" r="23" />
        <Circle cx="55" cy="510" r="23" />
        <Circle cx="140" cy="510" r="23" />
        <Circle cx="230" cy="510" r="23" />
        <Circle cx="300" cy="510" r="23" />
        <Rect x="18.5" y="570" rx="10" ry="10" width="170" height="78" />
        <Rect x="210.5" y="570" rx="10" ry="10" width="170" height="78" />
        <Rect x="0" y="645.2" rx="8" ry="8" width={deviceWidth} height="125" />
      </ContentLoader>
    </View>
  );
};

const NewDetailLoader = () => (
  <ContentLoader
    speed={2}
    width={deviceWidth}
    height={deviceHeight}
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect x="4" y="10" rx="8" ry="8" width={deviceWidth - 15} height="120" />
    <Circle cx="56" cy="175" r="25" />
    <Circle cx="305" cy="175" r="25" />
    <Circle cx="136" cy="175" r="25" />
    <Circle cx="222" cy="175" r="25" />
    <Circle cx="56" cy="245" r="25" />
    <Circle cx="141" cy="245" r="25" />
    <Circle cx="223" cy="245" r="25" />
    <Circle cx="308" cy="245" r="25" />
    <Circle cx="56" cy="305" r="25" />
    <Circle cx="141" cy="305" r="25" />
    <Circle cx="223" cy="305" r="25" />
    <Circle cx="308" cy="305" r="25" />
    {/* <Circle cx="56" cy="375" r="25" />
    <Circle cx="141" cy="375" r="25" />
    <Circle cx="223" cy="375" r="25" />
    <Circle cx="308" cy="375" r="25" /> */}
  </ContentLoader>
);

const BookingListLoader = () => (
  <ContentLoader
    speed={2}
    width={deviceWidth}
    height={deviceHeight}
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect x="6" y="13" rx="10" ry="10" width={deviceWidth - 15} height="120" />
    <Rect x="6" y="145" rx="10" ry="10" width={deviceWidth - 15} height="120" />
    <Rect x="6" y="280" rx="10" ry="10" width={deviceWidth - 15} height="120" />
  </ContentLoader>
);

const BookMarkLoader = () => (
  <ContentLoader
    speed={1}
    width={deviceWidth}
    height={Utils.scaleWithPixel(200)}
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect
      x="20"
      y="0"
      rx="10"
      ry="10"
      width={Utils.scaleWithPixel(120)}
      height={Utils.scaleWithPixel(150)}
    />
    <Rect
      x={Utils.scaleWithPixel(120) + 30}
      y="5"
      rx="3"
      ry="3"
      width={deviceWidth - (Utils.scaleWithPixel(120) + 40)}
      height={Utils.scaleWithPixel(15)}
    />
    <Rect
      x={Utils.scaleWithPixel(120) + 30}
      y={Utils.scaleWithPixel(15) + 15}
      rx="3"
      ry="3"
      width={deviceWidth - (Utils.scaleWithPixel(120) + 55)}
      height={Utils.scaleWithPixel(15)}
    />
    <Rect
      x={Utils.scaleWithPixel(120) + 30}
      y={Utils.scaleWithPixel(30) + 25}
      rx="3"
      ry="3"
      width={deviceWidth - (Utils.scaleWithPixel(120) + 60)}
      height={Utils.scaleWithPixel(15)}
    />
    {/* <Rect
      x={Utils.scaleWithPixel(120) + 30}
      y={Utils.scaleWithPixel(45) + 30}
      rx="3"
      ry="3"
      width={deviceWidth - (Utils.scaleWithPixel(120) + 65)}
      height={Utils.scaleWithPixel(15)}
    /> */}
    <Rect
      x={Utils.scaleWithPixel(120) + 30}
      y={Utils.scaleWithPixel(45) + 35}
      rx="3"
      ry="3"
      width={deviceWidth - (Utils.scaleWithPixel(120) + 85)}
      height={Utils.scaleWithPixel(15)}
    />
    <Rect
      x={Utils.scaleWithPixel(120) + 30}
      y={Utils.scaleWithPixel(60) + 45}
      rx="3"
      ry="3"
      width={deviceWidth - (Utils.scaleWithPixel(120) + 100)}
      height={Utils.scaleWithPixel(15)}
    />
  </ContentLoader>
);
const BookMarkLoader1 = () => (
  <ContentLoader
    speed={2}
    width={deviceWidth}
    height={deviceHeight}
    backgroundColor="#f3f3f3"
    foregroundColor="#ecebeb">
    <Rect x="8" y="12" rx="6" ry="6" width={deviceWidth - 20} height="120" />
    <Rect x="8" y="144" rx="6" ry="6" width={deviceWidth - 20} height="120" />
    <Rect x="8" y="279" rx="6" ry="6" width={deviceWidth - 20} height="120" />
    <Rect x="8" y="415" rx="6" ry="6" width={deviceWidth - 20} height="120" />
  </ContentLoader>
);

export {
  ImageLoder,
  DetailLoder,
  BookMarkLoader,
  BookingListLoader,
  NewDetailLoader,
  BookMarkLoader1,
  ListLoader,
  ReviewLoader,
};
