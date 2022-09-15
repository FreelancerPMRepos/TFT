const actions = {
  SET_FILTER_TYPE: 'auth/SET_FILTER_TYPE',
  SET_FILTERS: 'auth/SET_FILTERS',
  SET_POSITION: 'auth/SET_POSITION',

  setFilterType: filterDataType => dispatch =>
    dispatch({
      type: actions.SET_FILTER_TYPE,
      filterDataType,
    }),

  setFilters: allFilters => dispatch =>
    dispatch({
      type: actions.SET_FILTERS,
      allFilters,
    }),

  setPosition: position => dispatch =>
    dispatch({
      type: actions.SET_POSITION,
      position,
    }),
};

export default actions;
