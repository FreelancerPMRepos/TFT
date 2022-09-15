import React, {Component} from 'react';
import {StyleSheet, Text, View, Image, Dimensions} from 'react-native';
import PropTypes from 'prop-types';
import {translate} from '../lang/Translate';
import LottieView from 'lottie-react-native';

const styles = StyleSheet.create({
  mainView: {
    flex: 1,
    height: 250,
    justifyContent: 'center',
    alignItems: 'center',
  },
  imageContainer: {
    height: 150,
    width: 150,
  },
  bigFont: {
    textAlign: 'center',
    fontSize: 24,
    fontWeight: 'bold',
    paddingVertical: 5,
  },
  smallFont: {
    textAlign: 'center',
    fontSize: 15,
    fontWeight: 'bold',
    paddingBottom: 10,
    color: '#a7a7a7',
  },
  animationWrap: {
    height: Dimensions.get('window').height / 2,
    width: Dimensions.get('window').width,
    // backgroundColor: 'red',
    justifyContent: 'center',
    alignItems: 'center',
  },
  animation: {
    height: 150,
    width: 200,
  },
});

class CNoDataFound extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    const {
      msgNoData,
      imageSource,
      style,
      imageStyle,
      onAnimationFinish,
    } = this.props;
    return (
      <View style={[styles.animationWrap, style]}>
        {/* <Image
          style={[styles.imageContainer, imageStyle]}
          resizeMode="contain"
          source={imageSource}
        /> */}
        <LottieView
          ref={animation => {
            this.animation1 = animation;
          }}
          onAnimationFinish={onAnimationFinish}
          autoSize={false}
          style={[styles.animation, imageStyle]}
          source={imageSource}
          autoPlay={true}
          loop={true}
        />

        <Text style={styles.bigFont}>{translate('Oops')}</Text>
        <Text style={styles.smallFont}>{msgNoData}</Text>
      </View>
    );
  }
}

CNoDataFound.propTypes = {
  msgNoData: PropTypes.string,
  imageSource: PropTypes.any,
  style: PropTypes.object,
  onAnimationFinish: PropTypes.func,
};

CNoDataFound.defaultProps = {
  msgNoData: '',
  imageSource: null,
  style: {},
  onAnimationFinish: () => {},
};

export default CNoDataFound;
