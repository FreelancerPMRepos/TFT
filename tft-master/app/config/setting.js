/**
 * Basic Setting Variables Define
 */
import {Dimensions, Platform} from 'react-native';

const device = Dimensions.get('window');
const devMode = false;

const baseUrl = 'https://www.kashtahapp.com';
// const devMode = __DEV__;

export const BaseSetting = {
  name: 'TFT',
  displayName: 'TFT',
  appVersion: '1.3.7',
  bugsnagApiKey: '6c0c7c64d2ddc38b0274f5207eca3c50',
  baseUrl,
  api: devMode
    ? 'http://192.168.0.137/kashtah/kahstahApi/backend/v1/'
    : baseUrl + '/kahstahApi/backend/v1/',
  shareEndPoint: baseUrl,
  endpoints: {
    loginEmail: 'loginEmail.php',
    registration: 'registration.php',
    verifyOtp: 'verifyOtp.php',
    loginMobile: 'loginPhone.php',
    getPools: 'getPools.php',
    getChalets: 'getChalets.php',
    getCamps: 'getCamps.php',
    poolsDetail: 'getDetails.php',
    chaletsDetail: 'getChaletDetails.php',
    campsDetail: 'getCampDetails.php',
    poolsDisableDays: 'getDisableDays.php',
    poolsPrices: 'getPrices.php',
    poolsPics: 'getPics.php',
    chaletsDisableDays: 'getChaletDisableDays.php',
    chaletsPrices: 'getChaletsPrices.php',
    chaletsPics: 'getChaletPics.php',
    campsDisableDays: 'getCampDisableDays.php',
    campsPrices: 'getCampsPrices.php',
    campsPics: 'getCampPics.php',
    reservationsList: 'getReservationsList.php',
    filteredPools: 'getFilteredPools.php',
    filteredChalets: 'getFilteredChalets.php',
    filteredCamps: 'getFilteredCamps.php',
    getCountries: 'getCountries.php',

    poolDisables: 'getPoolDisables.php',
    poolOffers: 'getPoolOffers.php',
    poolReservation: 'getPoolReservations.php',

    chaletsDisables: 'getChaletDisables.php',
    chaletsOffers: 'getChaletOffers.php',
    chaletsReservation: 'getChaletReservations.php',

    campsDisables: 'getCampDisables.php',
    campsOffers: 'getCampOffers.php',
    campssReservation: 'getCampReservations.php',

    addReservation: 'addReservation.php',
    cancelReservation: 'cancelReservation.php',

    createPayment: 'createPaymentPageMyFatoorah.php',
    verifyPayment: 'verifyPaymentPageMyFatoorah.php',

    bookMarks: 'getBookmark.php',
    addBookMark: 'setBookmark.php',
    removeBookMark: 'removeBookmark.php',

    addRating: 'addRating.php',
    checkRating: 'checkRating.php',
    recovery: 'recovery.php',
    addNote: 'addNote.php',
    sendPoolRequest: 'addHelpUs.php',
    changePassword: 'changePassword.php',
    changeProfile: 'changeProfile.php',
    checkPhoneExist: 'checkPhoneExist.php',
    changeNumber: 'changeNumber.php',
    dayPrices: 'getDaybydayPrice.php',
    disableFacility: 'facilityDisable.php',
    ratingDetails: 'getRatings.php',
    verifyEmail: 'verifyEmail.php',
    verifyEmailOTP: 'verifyEmailOTP.php',
  },
  version: {
    android: '0.0.1',
    ios: '0.0.1',
  },
  devMode,
  paypalTest: true,
  token: null,
  homepageNoEntryToast: 'Please select location',
  borderIssue: Platform.OS === 'android' && Platform.Version < 21,
  isIphoneX:
    Platform.OS === 'ios' &&
    !Platform.isPad &&
    !Platform.isTVOS &&
    (device.height === 812 || device.width === 812),
  geolocationOptions: {
    enableHighAccuracy: false,
    timeout: 20000,
    maximumAge: 10000,
    distanceFilter: 1,
  },
  deviceHeight: device.height,
  deviceWidth: device.width,
};
