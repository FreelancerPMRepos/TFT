import React, {Component} from 'react';
import {
  View,
  StyleSheet,
  TouchableOpacity,
  Dimensions,
  Text,
  Platform,
  Animated,
} from 'react-native';
import LottieView from 'lottie-react-native';
import {BaseStyle} from '@config';
import {isIphoneX} from '../config/isIphoneX';
import styles from '../screens/SignUp/styles';
import {translate} from '../lang/Translate';

const windowWidth = Dimensions.get('window').width;
const tabWidth = windowWidth / 4;
const IOS = Platform.OS === 'ios';

const S = StyleSheet.create({
  container: {
    ...BaseStyle.tabBar,
    flexDirection: 'row',
    height: 64,
    alignItems: 'center',
    justifyContent: 'center',
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
  },
  tabButton: {flex: 1},
  spotLight: {
    width: tabWidth,
    height: '100%',
    justifyContent: 'center',
    alignItems: 'center',
    overflow: 'hidden',
  },
  iconWrap: {
    height: 34,
    width: 34,
    overflow: 'visible',
    justifyContent: 'center',
    alignItems: 'center',
  },
  xContainer: {
    paddingBottom: 24,
    height: 75,
  },
});

class TabBar extends Component {
  constructor(props) {
    super(props);

    this.lottie = [
      {
        height: 25,
        width: 25,
        src: require('@assets/lottie/home_primary.json'),
      },
      {
        height: 110,
        width: 110,
        src: require('@assets/lottie/done_primary.json'),
      },
      {
        height: 26,
        width: 26,
        src: require('@assets/lottie/heart_primary.json'),
      },
      {
        height: 28,
        width: 28,
        src: require('@assets/lottie/user_primary.json'),
      },
    ];
    this.lottieRef = [];

    this.state = {
      offset: new Animated.Value(0),
    };
  }

  componentDidMount() {
    const {index: activeRouteIndex} = this.props.navigation.state;
    console.log('Tabbar did mount ===> ', activeRouteIndex);
    if (this.lottieRef && this.lottieRef[activeRouteIndex]) {
      this.lottieRef[activeRouteIndex].play();
    }
  }

  componentDidUpdate(prevProps, prevState) {
    const {index: oldRouteIndex} = prevProps.navigation.state;
    const {index: activeRouteIndex} = this.props.navigation.state;

    /* Hide tab bar */
    const newState = this.props.navigation.state;
    const newIndex = newState.routes[newState.index].index;

    console.log('Bottom Tabs ===> ', newIndex);
    if (newIndex > 0) {
      Animated.timing(this.state.offset, {
        toValue: 80,
        duration: 200,
        useNativeDriver: true,
      }).start();
    } else {
      Animated.timing(this.state.offset, {
        toValue: 0,
        duration: 200,
        useNativeDriver: true,
      }).start();
    }

    console.log('Tabbar update ===> ', activeRouteIndex, oldRouteIndex);
    if (activeRouteIndex === oldRouteIndex) {
      return false;
    }
    if (this.lottieRef && this.lottieRef[activeRouteIndex]) {
      this.lottieRef[activeRouteIndex].play();
    }
    if (this.lottieRef && this.lottieRef[oldRouteIndex]) {
      this.lottieRef[oldRouteIndex].reset();
    }
  }

  render() {
    const {
      activeTintColor,
      inactiveTintColor,
      onTabPress,
      onTabLongPress,
      getAccessibilityLabel,
      navigation,
    } = this.props;

    const {routes, index: activeRouteIndex} = navigation.state;
    console.log('Routes ====> ', routes);
    return (
      <Animated.View
        style={[
          S.container,
          IOS && isIphoneX() ? S.xContainer : {},
          {transform: [{translateY: this.state.offset}]},
        ]}>
        {routes.map((route, routeIndex) => {
          const isRouteActive = routeIndex === activeRouteIndex;
          const tintColor = isRouteActive ? activeTintColor : inactiveTintColor;

          return (
            <TouchableOpacity
              key={routeIndex}
              style={S.spotLight}
              onPress={() => {
                onTabPress({route});
              }}
              onLongPress={() => {
                onTabLongPress({route});
              }}
              accessibilityLabel={getAccessibilityLabel({route})}>
              <View style={S.iconWrap}>
                <LottieView
                  ref={animation => {
                    this.lottieRef[routeIndex] = animation;
                  }}
                  style={{
                    height: this.lottie[routeIndex].height,
                    width: this.lottie[routeIndex].width,
                  }}
                  source={this.lottie[routeIndex].src}
                  autoPlay={false}
                  loop={false}
                />
              </View>
              <Text style={{color: tintColor, fontSize: 12}}>
                {translate(route.key)}
              </Text>
            </TouchableOpacity>
          );
        })}
      </Animated.View>
    );
  }
}

export default TabBar;
