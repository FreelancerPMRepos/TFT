import React, {Component} from 'react';
import {View, StyleSheet, Dimensions} from 'react-native';
import {connect} from 'react-redux';
import LottieView from 'lottie-react-native';
import {Text} from '@components';

const styles = StyleSheet.create({
  animationWrap: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  animation: {
    height: Dimensions.get('window').height / 1.5,
    width: Dimensions.get('window').width / 1.5,
  },
});

class NoInternet extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <View style={[styles.animationWrap]}>
        <LottieView
          ref={animation => {
            this.animation1 = animation;
          }}
          // onAnimationFinish={onAnimationFinish}
          autoSize={false}
          style={[styles.animation]}
          source={require('@assets/lottie/noInternet.json')}
          autoPlay={true}
          loop={true}
        />
        <Text heading1>Oops!</Text>
        <Text heading1>No Internet </Text>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
  filter: state.filter,
});

export default connect(mapStateToProps)(NoInternet);
