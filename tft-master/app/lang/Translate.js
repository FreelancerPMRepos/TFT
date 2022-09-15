import i18n from 'i18n-js';
import actions from '../redux/reducers/language/actions';

const translationGetters = {
  en: () => require('../lang/en.json'),
  ar: () => require('../lang/ar.json'),
};

export const translate = (key, config) => i18n.t(key, config);

const setI18nConfig = (language, store) => {
  let isRTL = false;
  let appLanguage = language;
  if (language === null) {
    appLanguage = 'en';
    store.dispatch({
      type: actions.SET_LANGUAGE,
      languageData: appLanguage,
    });
  }

  if (appLanguage === 'ar') {
    isRTL = true;
  }

  console.log('appLanguage', appLanguage);
  console.log('isRTL', isRTL);

  const ReactNative = require('react-native');
  try {
    ReactNative.I18nManager.allowRTL(isRTL);
    ReactNative.I18nManager.forceRTL(isRTL);
  } catch (e) {
    console.log('Error in RTL', e);
  }
  i18n.translations = {[appLanguage]: translationGetters[appLanguage]()};
  i18n.locale = appLanguage;
};

export const initTranslate = store => {
  const {
    language: {languageData},
  } = store.getState();
  setI18nConfig(languageData, store);
};
