import moment from 'moment';
import _ from 'lodash';
import {PermissionsAndroid, Platform, Linking} from 'react-native';
import Geolocation from '@react-native-community/geolocation';
import CAlert from 'app/components/CAlert';
import categoryName from 'app/config/category';
import {translate} from 'app/lang/Translate';

const getDayPrice = (
  dayPrices,
  m,
  sDf,
  eDf,
  startPeriodK,
  endPeriodK,
  showMin = false,
) => {
  let dPrice = 0;
  let downPayment = 0;
  let explanation = {};
  if (_.has(dayPrices, `${m.format('YYYY-MM-DD')}`)) {
    const priceData = dayPrices[m.format('YYYY-MM-DD')];
    _.map(priceData, (pdata, pkey) => {
      if (sDf === m.format('YYYY-MM-DD')) {
        console.log('START DATE SAME ===> ', pdata, pkey);
      }
      /* Total price logic */
      if (
        (sDf === m.format('YYYY-MM-DD') &&
          startPeriodK !== pkey &&
          startPeriodK === 'E' &&
          pkey === 'M') ||
        (eDf === m.format('YYYY-MM-DD') &&
          endPeriodK !== pkey &&
          endPeriodK === 'M' &&
          pkey === 'E')
      ) {
        return;
      }

      explanation[pkey] = {
        price: _.toNumber(pdata.price),
        downPayment: _.toNumber(pdata.down_payment),
      };
      if (showMin && dPrice > 0) {
        dPrice = Math.min(_.toNumber(pdata.price), dPrice);
        downPayment = Math.min(_.toNumber(pdata.down_payment), downPayment);
      } else {
        dPrice += _.toNumber(pdata.price);
        downPayment += _.toNumber(pdata.down_payment);
      }
    });
  }
  return {dPrice, downPayment, explanation};
};

/* Function for formating any date to "YYYY_MM_DD" */
// export const parseDate = (sDate, eDate) => {
//   console.log('parseDate -> sDate, eDate', sDate, eDate);
// };

/* Rewriting a common function to get price of Facility between Dates of Choosen Period */
export const getPriceofDates = (
  startingDay,
  endingDay,
  period,
  dayPrices,
  currentDay = false,
) => {
  /* TODO: Add Logic per Period Continuous only booking affect */
  let dPrice = 0;
  let downPayment = 0;
  let explanation = {};

  if (
    (startingDay &&
      !_.isEmpty(startingDay) &&
      endingDay &&
      !_.isEmpty(endingDay)) ||
    currentDay
  ) {
    const startPeriodK = _.has(period, 'activeStartPeriod.title')
      ? period.activeStartPeriod.title.charAt(0)
      : '';
    const endPeriodK = _.has(period, 'activeEndPeriod.title')
      ? period.activeEndPeriod.title.charAt(0)
      : '';

    const sDf = moment(startingDay).format('YYYY-MM-DD');
    const eDf = moment(endingDay).format('YYYY-MM-DD');

    if (currentDay) {
      const cDf = moment(currentDay);

      const showMin = !cDf.isBetween(
        moment(startingDay),
        moment(endingDay),
        null,
        '[]',
      );

      const dPO = getDayPrice(
        dayPrices,
        cDf,
        sDf,
        eDf,
        startPeriodK,
        endPeriodK,
        showMin,
      );
      dPrice = dPO.dPrice;
      downPayment = dPO.downPayment;
      explanation[cDf.format('YYYY-MM-DD')] = dPO.explanation;
    } else {
      for (
        var m = moment(startingDay);
        m.isBefore(moment(endingDay).add(1, 'days'));
        m.add(1, 'days')
      ) {
        const dPO = getDayPrice(
          dayPrices,
          m,
          sDf,
          eDf,
          startPeriodK,
          endPeriodK,
        );
        dPrice += dPO.dPrice;
        downPayment += dPO.downPayment;
        explanation[m.format('YYYY-MM-DD')] = dPO.explanation;
      }
    }
  } else {
    console.log('Returning 0 as Start or end is not defined!!');
  }
  return {dPrice, downPayment, explanation};
};

export const getPeriodsFromPrice = dayPrice => {
  const Periods = {
    M: true,
    E: true,
    F: true,
  };

  if (_.isEmpty(dayPrice)) {
    return {M: false, E: false, F: false};
  }
  Object.keys(dayPrice).map(date => {
    const priceData = dayPrice[date];
    if (priceData) {
      if (!priceData.M) {
        Periods.M = false;
      }
      if (!priceData.E) {
        Periods.E = false;
      }
      if (!priceData.F) {
        Periods.F = false;
      }
    } else {
      console.log('Price Error');
    }
  });

  return Periods;
};

export const getLatLng = async () => {
  console.log('getLatLng -> getLatLng');
  try {
    const granted = await PermissionsAndroid.request(
      PermissionsAndroid.PERMISSIONS.ACCESS_FINE_LOCATION,
    );
    return new Promise((resolve, reject) => {
      if (
        granted === PermissionsAndroid.RESULTS.GRANTED ||
        Platform.OS === 'ios'
      ) {
        Geolocation.getCurrentPosition(
          position => {
            console.log('getLatLng -> position', position);
            resolve(position);
          },
          error => {
            console.log('Location get currnet position ===> ', error);
            const msg = Platform.select({
              ios:
                'You denied location permission. For Granting permission Go to Settings -> Find app and set Permission to while using app',
              android: error.message,
            });
            Platform.OS === 'ios'
              ? CAlert(
                  JSON.stringify(msg),
                  'Alert!',
                  () => {
                    Linking.openSettings();
                    reject(error);
                  },
                  () => {
                    reject(error);
                  },
                  'Go to Settings',
                )
              : CAlert(JSON.stringify(msg));
          },
          {
            enableHighAccuracy: false,
            timeout: 20000,
            // maximumAge: 1000,
          },
        );
      } else {
        // await this.getLocation();
        reject('Permission Denied by user');
      }
    });
  } catch (err) {
    console.warn(err);
  }
};

const typeObj = {
  [categoryName.pools]: {
    default: 'poolFilters',
    data: 'poolsData',
    city: 'poolCities',
    sType: 'is_pool',
    detail: 'poolsDetail',
    disable: 'poolsDisableDays',
    disables: 'poolDisables',
    offers: 'poolOffers',
    pics: 'poolsPics',
    familyMsg: 'Family_Only_Alert_Pools',
    name: 'pool',
    id: 'poolId',
  },
  [categoryName.chalets]: {
    default: 'chaletFilters',
    data: 'chaletsData',
    city: 'chaletesCities',
    sType: 'is_chalet',
    detail: 'chaletsDetail',
    disable: 'chaletsDisableDays',
    disables: 'chaletsDisables',
    offers: 'chaletsOffers',
    pics: 'chaletsPics',
    familyMsg: 'Family_Only_Alert_Chalets',
    name: 'chalet',
    id: 'chaletId',
  },
  [categoryName.camps]: {
    default: 'campFilters',
    data: 'campsData',
    city: 'campsCities',
    sType: 'is_camp',
    detail: 'campsDetail',
    disable: 'campsDisableDays',
    disables: 'campsDisables',
    offers: 'campsOffers',
    pics: 'campsPics',
    familyMsg: 'Family_Only_Alert_Camps',
    name: 'camp',
    id: 'campId',
  },
};

export const getCurrentFilterType = (
  selectedCategory,
  type = 'default',
  shouldTrans = false,
) => {
  if (_.has(typeObj, `${selectedCategory}.${type}`)) {
    if (shouldTrans) {
      return translate(typeObj[selectedCategory][type]);
    }
    return typeObj[selectedCategory][type];
  }
  return 'poolFilters';
};

/* getDefaultPeriods From Price Data */
export const getDefaultPeriodofType = type => {
  if (type === categoryName.pools) {
    return 'Morning';
  } else {
    return 'Full Day';
  }
};

const filterFields = {
  common: {
    desiredLocation: ['Everywhere'],
    byDate: '',
  },
  [categoryName.pools]: {
    waterType: '',
    byPeriod: '',
    startPeriod: '',
    endPeriod: '',
  },
  [categoryName.chalets]: {},
  [categoryName.camps]: {},
};

/* getResetFilterObject */
export const getResetFilterObject = filter => {
  categoryName.map(catName => {
    const fType = getCurrentFilterType(catName);

    _.map(filterFields.common, (value, key) => {
      filter[fType][key] = value;
    });

    _.map(filterFields[catName], (value, key) => {
      filter[fType][key] = value;
    });

    filter[fType].resetFilter = true;
  });
  filter.resetFilter = true;

  return filter;
};

/* Get Currency Symbol */
export const getCurrencySymbol = countryObj => {
  const countryCode =
    countryObj && !_.isEmpty(countryObj) && countryObj.currency
      ? countryObj.currency
      : 'BHD';

  console.log('countryCode', countryCode);
  return countryCode;
};
