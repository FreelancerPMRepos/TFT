import {createSwitchNavigator, createAppContainer} from 'react-navigation';
import Loading from '@screens/Loading';
import Main from './main';
import Start from './start';

const AppNavigator = createSwitchNavigator(
  {
    Loading,
    Main,
    Start,
  },
  {
    initialRouteName: 'Loading',
  },
);
export default createAppContainer(AppNavigator);
