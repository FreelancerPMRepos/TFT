import _ from 'lodash';
import {Client} from 'bugsnag-react-native';
import {BaseSetting} from '../../../config/setting';

let bugsnag = {};
if (!__DEV__) {
  bugsnag = new Client(BaseSetting.bugsnagApiKey);
}

const actions = {
  SET_DATA: 'auth/SET_DATA',
  SET_NETWORK_STATUS: 'auth/SET_NETWORK_STATUS',
  SET_INITIAL_URL: 'auth/SET_INITIAL_URL',
  SET_USER_COUNTRY: 'auth/SET_USER_COUNTRY',
  SET_INTRO_SHOWN: 'auth/SET_INTRO_SHOWN',
  DISABLE_FACILITY_DATA: 'auth/DISABLE_FACILITY_DATA',

  setNetworkStatus: isConnected => dispatch =>
    dispatch({
      type: actions.SET_NETWORK_STATUS,
      isConnected,
    }),

  setInitialUrl: url => dispatch =>
    dispatch({
      type: actions.SET_INITIAL_URL,
      url,
    }),

  setUserCountry: country => dispatch =>
    dispatch({
      type: actions.SET_USER_COUNTRY,
      country,
    }),

  setIntroShown: introShown => dispatch =>
    dispatch({
      type: actions.SET_INTRO_SHOWN,
      introShown,
    }),

  setUserData: data => {
    let uData = {};
    if (data !== undefined && data !== null && Object.keys(data).length > 0) {
      uData = data;
    }

    const userID = _.isObject(uData) && _.isString(uData.ID) ? uData.ID : '0';
    const fName =
      _.isObject(uData) && _.isString(uData.first_name) ? uData.first_name : '';
    const lName =
      _.isObject(uData) && _.isString(uData.last_name) ? uData.last_name : '';
    const userEid =
      _.isObject(uData) && _.isString(uData.email) ? uData.email : '';

    const userName = `${fName} ${lName}`;
    if (bugsnag.setUser) {
      bugsnag.setUser(userID, userName, userEid);
    }

    return dispatch =>
      dispatch({
        type: actions.SET_DATA,
        userData: uData,
      });
  },

  disableFacilityData: disableFacilityData => dispatch =>
    dispatch({
      type: actions.DISABLE_FACILITY_DATA,
      disableFacilityData,
    }),
};

export default actions;
