import _ from 'lodash';
import {Platform} from 'react-native';
import firebase from '@react-native-firebase/app';
import '@react-native-firebase/auth';
import moment from 'moment';

export function addOrReplace(array, item, key) {
  console.log('addOrReplace called ===>');
  console.log('Initial Array ', _.clone(array), item, key);
  const i = array.findIndex(_item => _item[key] === item[key]);
  if (i > -1) {
    array[i] = item;
  }
  // (2)
  else {
    array.push(item);
  }
  console.log('Found index is: ', i);
  console.log('New Array is ', array);
  return array;
}

export function getCurrentDateString() {
  const currentMonth = new Date().getMonth() + 1;
  const currentYear = new Date().getFullYear();
  const currentDay = new Date().getDate();
  console.log('Date', currentYear + '-' + currentMonth + '-' + currentDay);
  return currentYear + '-' + currentMonth + '-' + currentDay;
}

export async function handleSendCode(selectedValue, phone, code) {
  console.log(
    'TCL: SignUp -> handleSendCode -> handleSendCode',
    selectedValue,
    phone,
  );
  console.log('SignUp -> handleSendCode -> selectedValue', selectedValue);
  const pCode = selectedValue ? selectedValue.label : '';
  // const val = pCode.split('+').pop();
  const val = `+${pCode.split('+').pop()} ${phone}`;
  console.log(val);
  try {
    return new Promise(async (resolve, reject) => {
      let confirmResponse = {};
      await firebase
        .auth()
        .signInWithPhoneNumber(val)
        .then(confirmResult => {
          confirmResponse = {confirmResult, modalVisible: true};
          resolve(confirmResponse);
        })
        .catch(error => {
          alert(error.message);
          console.log(error);
          reject({error, loading: false});
        });
    });
  } catch (error) {
    console.log('TCL: SignUp -> handleSendCode -> error', error);
  }
}

export async function verifyCode(code, confirmResult) {
  try {
    return new Promise(async (resolve, reject) => {
      await confirmResult
        .confirm(code)
        .then(user => {
          const uId = user && !_.isEmpty(user) && user.uid ? user.uid : '';
          console.log('uid==>', uId);
          console.log('user.uid==>', user.uid);
          const response = {user, modalVisible: false};
          // apiCall(uId);
          resolve(response);
          // this.setState({modalVisible: false, userId: uId}, () => {
          //   apiCall(uId);
          // });
          console.log('user', user);
        })
        .catch(error => {
          alert(error.message);
          console.log(error);
          reject(error);
        });
    });
  } catch (error) {
    console.log('error', error);
  }
}

export function getGreetingTime(m) {
  var g = null; //return g
  var split_evening = 17; //24hr time to split the evening
  var currentHour = parseFloat(m.format('HH'));

  if (currentHour >= split_evening) {
    g = 'evening';
  } else {
    g = 'morning';
  }

  return g;
}

export function setIntervalTime(
  morningStartTime,
  eveningStartTime,
  startTime,
  intervalTime,
  startingDate,
) {
  const isCurrentorOldDate = moment(
    moment(startingDate).format('DD MMM YYYY'),
  ).isSameOrBefore(moment().format('DD MMM YYYY'));
  console.log(
    'DEBUG setIntervalTime -> intervalTime',
    'mst => ',
    morningStartTime,
    'est => ',
    eveningStartTime,
    'st => ',
    startTime,
    'it => ',
    intervalTime,
    'sd => ',
    startingDate,
    'icd => ',
    isCurrentorOldDate,
  );
  let isMorningDisable = false;
  let isEveningDisable = false;
  let isFullDayDisable = false;

  /* If the starting date is in Future let's not validate the time */
  if (!isCurrentorOldDate) {
    return {isMorningDisable, isEveningDisable, isFullDayDisable};
  }
  /* Set default interval to 2 hours */
  if (parseInt(intervalTime) <= 0) {
    intervalTime = 2;
  }

  const currentHour = moment();
  /* If Period type is morning / evening */
  if (startTime === '') {
    console.log('DEBUG setIntervalTime -> Mrng / Eve');
    /* Check if Evening is disabled - If yes then disable both mrng and even */
    if (eveningStartTime && eveningStartTime !== '') {
      const ESTime = moment(eveningStartTime, 'hA').subtract(
        Number(intervalTime),
        'hour',
      ); // Format to 24hr
      console.log(
        'DEBUG setIntervalTime -> condition for Evening ==> ',
        currentHour,
        ESTime,
        ESTime.isBefore(currentHour),
      );
      /* If Evening time is after Current Hour + Interval Time */
      if (ESTime.isBefore(currentHour)) {
        isEveningDisable = true;
        isMorningDisable = true;
      }
    }

    /* Check if Morning is disabled - If yes then disable both mrng and even */
    if (!isMorningDisable && morningStartTime && morningStartTime !== '') {
      const MSTime = moment(morningStartTime, 'hA').subtract(
        Number(intervalTime),
        'hour',
      ); //MSTime - intervalTime;
      console.log(
        'DEBUG setIntervalTime -> condition for Morning ==> ',
        currentHour,
        MSTime,
        MSTime.isBefore(currentHour),
      );
      /* If Morning time is after Current Hour + Interval Time */
      if (MSTime.isBefore(currentHour)) {
        isMorningDisable = true;
      }
    }
  } else {
    console.log('DEBUG setIntervalTime -> If Full date facility');
    const sTime = moment(startTime, ['hA']).subtract(
      Number(intervalTime),
      'hour',
    ); //sTime - intervalTime;
    console.log(
      'DEBUG setIntervalTime -> condition for Full Day ==> ',
      currentHour,
      sTime,
      sTime.isBefore(currentHour),
    );

    /* If Starting time is after Current Hour + Interval Time */
    if (sTime.isBefore(currentHour)) {
      isFullDayDisable = true;
    }
  }
  console.log('Interval check result ===> ', {
    isMorningDisable,
    isEveningDisable,
    isFullDayDisable,
  });
  return {isMorningDisable, isEveningDisable, isFullDayDisable};
}
