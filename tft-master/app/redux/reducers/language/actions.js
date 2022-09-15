const actions = {
  SET_LANGUAGE: 'auth/SET_LANGUAGE',

  setLanguage: (languageData, languageName) => dispatch =>
    dispatch({
      type: actions.SET_LANGUAGE,
      languageData,
      languageName,
    }),
};

export default actions;
