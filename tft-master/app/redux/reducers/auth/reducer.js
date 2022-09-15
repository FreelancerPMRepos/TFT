import types from './actions';

const initialState = {
  userData: {},
  isConnected: true,
  url: '',
  country: '',
  introShown: false,
  disableFacilityData: {},
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case 'persist/REHYDRATE':
      if (
        action.payload &&
        action.payload.auth &&
        action.payload.auth.introShown
      ) {
        return {
          ...state,
          ...action.payload.auth,
          introShown: false,
        };
      }
      return state;
    case types.SET_DATA:
      console.log(`${types.SET_DATA} => `);
      return {
        ...state,
        userData: action.userData,
      };
    case types.SET_NETWORK_STATUS:
      return {
        ...state,
        isConnected: action.isConnected,
      };
    case types.SET_INITIAL_URL:
      console.log(`${types.SET_INITIAL_URL} => `);
      return {
        ...state,
        url: action.url,
      };
    case types.SET_USER_COUNTRY:
      console.log(`${types.SET_USER_COUNTRY} => `);
      return {
        ...state,
        country: action.country,
      };
    case types.SET_INTRO_SHOWN:
      console.log(`${types.SET_INTRO_SHOWN} => `);
      return {
        ...state,
        introShown: action.introShown,
      };
    case types.DISABLE_FACILITY_DATA:
      return {
        ...state,
        disableFacilityData: action.disableFacilityData,
      };
    default:
      return state;
  }
}
