import types from './actions';
import actions from './actions';

const initialState = {
  languageData: 'en',
  languageName: 'English',
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case types.SET_LANGUAGE:
      return {
        ...state,
        languageData: action.languageData,
        languageName: action.languageName,
      };

    default:
      return state;
  }
}
