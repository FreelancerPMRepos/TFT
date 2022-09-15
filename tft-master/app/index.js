import React, {Component} from 'react';
import {
  StyleSheet,
  View,
  StatusBar,
  Platform,
  SafeAreaView,
} from 'react-native';
import {BaseColor} from '@config';
import {Provider} from 'react-redux';
import {PersistGate} from 'redux-persist/integration/react';
import {Client} from 'bugsnag-react-native';
import codePush from 'react-native-code-push';
import Orientation from 'react-native-orientation-locker';
import {BaseSetting} from './config/setting';
import App from './navigation';
import {store, persistor} from './redux/store/configureStore';
import {getStatusBarHeight} from 'react-native-status-bar-height';
import {InAppNotificationProvider} from '@libs/react-native-in-app-notification';

import Toast from '@components/Toast';
import CTopNotify from '@components/CTopNotify';

console.disableYellowBox = true;
const IOS = Platform.OS === 'ios';

const codePushOptions = {
  installMode: codePush.InstallMode.IMMEDIATE,
  checkFrequency: codePush.CheckFrequency.ON_APP_START,

  updateDialog: {
    appendReleaseDescription: true,
    descriptionPrefix: "\n\nWhat's New:",
    mandatoryContinueButtonLabel: 'Install',
  },
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  statusBar: {
    height: getStatusBarHeight(),
  },
  content: {
    flex: 1,
    backgroundColor: BaseColor.primaryColor,
  },
});

class index extends Component {
  constructor(props) {
    super(props);

    this.state = {
      processing: false,
    };
  }

  /* Codepush Events */
  codePushStatusDidChange(status) {
    console.log('Codepush status change ==> ', status);
    this.codepushStatus = status;
    switch (status) {
      case codePush.SyncStatus.CHECKING_FOR_UPDATE:
        console.log('Codepush: Checking for updates.');
        break;
      case codePush.SyncStatus.DOWNLOADING_PACKAGE:
        this.setState({
          processing: true,
        });
        this.showToast('New app update is available and being downloaded.');
        console.log('Codepush: Downloading package.');
        break;
      case codePush.SyncStatus.INSTALLING_UPDATE:
        this.showToast('New app update is available and being installed.');
        console.log('Codepush: Installing update.');
        break;
      case codePush.SyncStatus.UP_TO_DATE:
        console.log('Codepush: Up-to-date.');
        break;
      case codePush.SyncStatus.UPDATE_INSTALLED:
        console.log('Codepush: Update installed.');
        break;
    }
  }

  showToast = message => {
    if (this.refs.notifyToast) {
      this.refs.notifyToast.show(message, 2000);
    }
  };

  componentDidMount() {
    console.log('StatusBar Height: ', getStatusBarHeight());
    IOS
      ? StatusBar.setBarStyle('dark-content', true)
      : StatusBar.setBarStyle('light-content', true);
    StatusBar.setBackgroundColor(BaseColor.primaryColor, true);
    // StatusBar.setTranslucent(true);
    // StatusBar.setBackgroundColor('#0000', true);
    // eslint-disable-next-line no-unused-vars
    if (!__DEV__) {
      const bugsnag = new Client(BaseSetting.bugsnagApiKey);
    }

    /* Let's lock device to porttrait */
    console.log('Orientation ===> ', Orientation);
    Orientation.lockToPortrait();
  }

  render() {
    const {processing} = this.state;
    return (
      <Provider store={store}>
        <PersistGate loading={null} persistor={persistor}>
          {/* <SafeAreaView style={{paddingTop: getStatusBarHeight()}} /> */}
          <InAppNotificationProvider>
            <App />
          </InAppNotificationProvider>
          {processing && <CTopNotify title="Installing Updates..." />}
        </PersistGate>
        <Toast
          ref="notifyToast"
          position="top"
          positionValue={100}
          fadeInDuration={750}
          fadeOutDuration={2000}
          opacity={0.8}
        />
      </Provider>
    );
  }
}

let indexExport = index;
if (!__DEV__) {
  indexExport = codePush(codePushOptions)(index);
}

export default indexExport;
