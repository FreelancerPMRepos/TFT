import {StatusBar} from 'react-native';
import {BaseColor} from '@config';

export function setStatusbar(type = 'brand') {
  if (type === 'light') {
    StatusBar.setBackgroundColor(BaseColor.whiteColor, true);
    StatusBar.setBarStyle('dark-content', true);
  } else if (type === 'dark') {
    StatusBar.setBackgroundColor(BaseColor.blackColor, true);
    StatusBar.setBarStyle('light-content', true);
  } else {
    StatusBar.setBackgroundColor(BaseColor.primaryColor, true);
    StatusBar.setBarStyle('light-content', true);
  }
}

export const codePushVersion = 34;
