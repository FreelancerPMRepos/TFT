/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import authActions from '../../redux/reducers/auth/actions';
import {ActivityIndicator, View, Linking, Platform} from 'react-native';
import {bindActionCreators} from 'redux';
import {Images, BaseColor} from '@config';
import SplashScreen from 'react-native-splash-screen';
import {Image, Text} from '@components';
import styles from './styles';
import NetInfo from '@react-native-community/netinfo';
import {initTranslate} from '../../lang/Translate';
import {store} from '../../redux/store/configureStore';

class Loading extends Component {
  constructor(props) {
    super(props);
  }

  onProcess(home = false) {
    SplashScreen.hide();
    const {navigation, auth} = this.props;
    const status =
      (auth.userData !== undefined &&
        auth.userData !== null &&
        Object.keys(auth.userData).length > 0) ||
      home;

    switch (status) {
      case true:
        setTimeout(() => {
          navigation.navigate('Main');
        }, 500);
        break;
      case false:
        setTimeout(() => {
          navigation.navigate('Start');
        }, 500);
        break;
      default:
        break;
    }
  }

  componentDidMount() {
    const {
      authActions: {setInitialUrl},
    } = this.props;
    initTranslate(store);
    this.checkNetWorkStatus();

    this.setTime = setTimeout(() => {
      this.onProcess();
    }, 200);

    console.log('On Did mount ===>');
    if (Platform.OS === 'android') {
      Linking.getInitialURL().then(url => {
        if (url && url != null && !url.includes('com.googleusercontent.apps')) {
          console.log('On initital url ===> ', url);
          clearTimeout(this.setTime);
          this.onProcess(true);
        } else {
          clearTimeout(this.setTime);
          this.onProcess();
        }
      });
    } else {
      Linking.addEventListener('url', e => {
        // this.handleOpenURL(e);
        console.log(
          'IOS ==> URL Event ==>',
          e,
          !e.url.includes('com.googleusercontent.apps'),
        );
        console.log('check=>', !e.url.includes('com.googleusercontent.apps'));
        if (e.url) {
          if (!e.url.includes('com.googleusercontent.apps')) {
            console.log('Setting url=-=>', e);
            setInitialUrl(e.url);
          } else {
            return false;
          }
        }
        clearTimeout(this.setTime);
        this.onProcess(true);
      });
    }
  }

  checkNetWorkStatus = () => {
    const {
      authActions: {setNetworkStatus},
    } = this.props;
    NetInfo.addEventListener(state => {
      console.log(`Network is Connected: ${state.isConnected}`);
      setNetworkStatus(state.isConnected);
    });
  };

  render() {
    return (
      <View style={styles.container}>
        <Image
          source={Images.white_logo}
          style={styles.logo}
          resizeMode="contain"
        />
        <View
          style={{
            position: 'absolute',
            top: 220,
            left: 0,
            right: 0,
            bottom: 0,
            justifyContent: 'center',
            alignItems: 'center',
          }}>
          <ActivityIndicator
            size="large"
            color={BaseColor.whiteColor}
            style={{
              marginTop: 20,
            }}
          />
        </View>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
});

const mapDispatchToProps = dispatch => ({
  authActions: bindActionCreators(authActions, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(Loading);
