import AsyncStorage from '@react-native-community/async-storage';
import {createStore, applyMiddleware} from 'redux';
import {persistStore, persistReducer} from 'redux-persist';
import autoMergeLevel2 from 'redux-persist/lib/stateReconciler/autoMergeLevel2';
import thunk from 'redux-thunk';
import rootReducer from '../reducers/rootReducer';

/**
 * Redux Setting
 */
const persistConfig = {
  key: 'root',
  storage: AsyncStorage,
  stateReconciler: autoMergeLevel2,
  blacklist: ['filter', 'booking'],
};

let middleware = [thunk];
middleware = [...middleware];

const persistedReducer = persistReducer(persistConfig, rootReducer);
const store = createStore(persistedReducer, applyMiddleware(...middleware));
const persistor = persistStore(store);

export {store, persistor};
