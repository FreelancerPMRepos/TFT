import {createStackNavigator} from 'react-navigation-stack';
import Walkthrough from '@screens/Walkthrough';
import SignUp from '@screens/SignUp';
import SignIn from '@screens/SignIn';
import ResetPassword from '@screens/ResetPassword';

const StackNavigator = createStackNavigator(
  {
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
  },
  {
    headerMode: 'none',
    initialRouteName: 'Walkthrough',
    defaultNavigationOptions: {
      cardStyle: {
        backgroundColor: '#FFF',
      },
    },
  },
);

export default StackNavigator;
