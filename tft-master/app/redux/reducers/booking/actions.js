const actions = {
  SET_BOOKING_DATA: 'auth/SET_BOOKING_DATA',

  setBookingData: bookingData => dispatch =>
    dispatch({
      type: actions.SET_BOOKING_DATA,
      bookingData,
    }),
};

export default actions;
