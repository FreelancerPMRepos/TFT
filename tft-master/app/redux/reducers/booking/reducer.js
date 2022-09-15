import types from './actions';

const initialState = {
  bookingData: {
    startingDate: null,
    endingDate: null,
  },
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case types.SET_BOOKING_DATA:
      console.log('SET BOOKING DATA CALLED ===> ', action, state);
      return {
        ...state,
        bookingData: {
          ...state.bookingData,
          ...action.bookingData,
        },
      };

    default:
      return state;
  }
}
