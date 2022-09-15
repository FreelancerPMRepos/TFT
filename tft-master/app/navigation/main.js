import React from 'react';
import {createBottomTabNavigator} from 'react-navigation-tabs';
import {createStackNavigator, TransitionPresets} from 'react-navigation-stack';
import {createSharedElementStackNavigator} from 'react-navigation-shared-element';
import {BaseColor} from '@config';
import * as Utils from '@utils';
import {Platform} from 'react-native';

/* Bottom Screen */
import Home from '@screens/Home';
import Booking from '@screens/Booking';
import BookMark from '@screens/BookMarks';
// import Post from '@screens/Post';
import Profile from '@screens/Profile';
/* Modal Screen only affect iOS */
import Filter from '@screens/Filter';
import Search from '@screens/Search';
import PreviewImage from '@screens/PreviewImage';
/* Stack Screen */
import EventTicket from '@screens/EventTicket';
import Review from '@screens/Review';
import Feedback from '@screens/Feedback';
import Notification from '@screens/Notification';
import Walkthrough from '@screens/Walkthrough';
import SignUp from '@screens/SignUp';
import SignIn from '@screens/SignIn';
import ResetPassword from '@screens/ResetPassword';
import ChangePassword from '@screens/ChangePassword';
import ChangeLanguage from '@screens/ChangeLanguage';
import Currency from '@screens/Currency';
import HotelDetail from '@screens/HotelDetail';
import PreviewBooking from '@screens/PreviewBooking';
import BookingDetail from '@screens/BookingDetail';
import AddPool from '@screens/AddPool';
import ChangeProfile from '@screens/ChangeProfile';
import AccountSettings from '@screens/AccountSettings';
import ChangePhone from '@screens/ChangePhone';
import NoInternet from '@screens/NoInternet';

/* Tabbar component */
import Tabbar from './Tabbar';

// Transition for navigation by screen name
const handleCustomTransition = ({scenes}) => {
  const nextScene = scenes[scenes.length - 1].route.routeName;
  switch (nextScene) {
    case 'PreviewImage':
      Utils.enableExperimental();
      return Utils.zoomIn();
    default:
      return false;
  }
};

// Config for bottom navigator
const bottomTabNavigatorConfig = {
  initialRouteName: 'Home',
  tabBarComponent: Tabbar,
  tabBarOptions: {
    showIcon: true,
    showLabel: true,
    activeTintColor: BaseColor.primaryColor,
    inactiveTintColor: BaseColor.grayColor,
  },
};

const SharedDefaultNavigationOptions = {
  mode: 'modal',
  headerMode: 'none',
  defaultNavigationOptions: {
    cardStyleInterpolator: ({current: {progress}}) => {
      const opacity = progress.interpolate({
        inputRange: [0, 1],
        outputRange: [0, 1],
        extrapolate: 'clamp',
      });
      return {cardStyle: {opacity}};
    },
    cardStyle: {
      backgroundColor: 'transparent',
    },
  },
};

const HomeStack = createSharedElementStackNavigator(
  {
    Home: {
      screen: Home,
      navigationOptions: () => ({
        title: 'Home',
      }),
    },
    HotelDetail: {
      screen: HotelDetail,
      navigationOptions: () => ({
        tabBarVisible: false,
        gestureEnabled: false,
      }),
    },
    Search: {
      screen: Search,
    },
  },
  SharedDefaultNavigationOptions,
);

const BookmarkStack = createStackNavigator(
  {
    BookMark: {
      screen: BookMark,
      navigationOptions: () => ({
        title: 'Bookmarks',
      }),
    },
    HotelDetail: {
      screen: HotelDetail,
      navigationOptions: () => ({
        tabBarVisible: false,
        gestureEnabled: false,
      }),
    },
  },
  SharedDefaultNavigationOptions,
);

// Tab bar navigation
const routeConfigs = {
  Home: HomeStack,
  Booking: {
    screen: Booking,
    navigationOptions: () => ({
      title: 'Booking',
    }),
  },
  Bookmarks: BookmarkStack,
  Account: {
    screen: Profile,
    navigationOptions: () => ({
      title: 'Account',
    }),
  },
};

// Define bottom navigator as a screen in stack
const BottomTabNavigator = createBottomTabNavigator(
  routeConfigs,
  bottomTabNavigatorConfig,
);

let defaultStackNavigationOptions = {
  headerMode: 'none',
  initialRouteName: 'BottomTabNavigator',
  defaultNavigationOptions: {
    mode: 'card',
    cardStyle: {
      backgroundColor: '#FFF',
    },
  },
};

if (Platform.OS !== 'ios') {
  defaultStackNavigationOptions.defaultNavigationOptions = {
    ...defaultStackNavigationOptions.defaultNavigationOptions,
    ...TransitionPresets.ModalSlideFromBottomIOS,
  };
} else {
  defaultStackNavigationOptions.defaultNavigationOptions = {
    ...defaultStackNavigationOptions.defaultNavigationOptions,
    ...TransitionPresets.SlideFromRightIOS,
  };
}

if (!__DEV__) {
  console.log();
}
// Main Stack View App
const StackNavigator = createStackNavigator(
  {
    BottomTabNavigator: {
      screen: BottomTabNavigator,
    },
    Review: {
      screen: Review,
    },
    Feedback: {
      screen: Feedback,
      navigationOptions: {
        gesturesEnabled: false,
      },
    },
    Notification: {
      screen: Notification,
    },
    Walkthrough: {
      screen: Walkthrough,
    },
    SignUp: {
      screen: SignUp,
    },
    SignIn: {
      screen: SignIn,
    },
    ResetPassword: {
      screen: ResetPassword,
    },
    ChangePassword: {
      screen: ChangePassword,
    },
    ChangeProfile: {
      screen: ChangeProfile,
    },
    ChangeLanguage: {
      screen: ChangeLanguage,
    },
    Currency: {
      screen: Currency,
    },
    HotelDetail: {
      screen: HotelDetail,
    },
    PreviewBooking: {
      screen: PreviewBooking,
      navigationOptions: {
        gesturesEnabled: false,
      },
    },
    BookingDetail: {
      screen: BookingDetail,
    },
    EventTicket: {
      screen: EventTicket,
      navigationOptions: {
        gesturesEnabled: false,
      },
    },
    AddPool: {
      screen: AddPool,
    },
    AccountSettings: {
      screen: AccountSettings,
    },
    ChangePhone: {
      screen: ChangePhone,
    },
    NoInternet: {
      screen: NoInternet,
    },
  },
  defaultStackNavigationOptions,
);

const RootStack = createStackNavigator(
  {
    Filter: {
      screen: Filter,
    },
    PreviewImage: {
      screen: PreviewImage,
    },
    StackNavigator: {
      screen: StackNavigator,
    },
  },
  {
    mode: 'modal',
    headerMode: 'none',
    initialRouteName: 'StackNavigator',
    transitionConfig: screen => handleCustomTransition(screen),
  },
);

export default RootStack;
