/**
 * @format
 * @lint-ignore-every XPLATJSCOPYRIGHT1
 */
import 'react-native-gesture-handler';
import {enableScreens} from 'react-native-screens';
import {AppRegistry} from 'react-native';
import App from './app/index';
import {BaseSetting} from '@config';

enableScreens();
AppRegistry.registerComponent(BaseSetting.name, () => App);
