import {combineReducers} from 'redux';
import auth from './auth/reducer';
import language from './language/reducer';
import filter from './filter/reducer';
import booking from './booking/reducer';

const rootReducer = combineReducers({
  auth,
  language,
  filter,
  booking,
});

export default rootReducer;
