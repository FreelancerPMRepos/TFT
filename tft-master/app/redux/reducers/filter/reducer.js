import types from './actions';
import moment from 'moment';
import _ from 'lodash';
import {getDefaultPeriodofType} from 'app/utils/booking';

const initialState = {
  filterDataType: '',
  position: {},
  resetFilter: false,
  allFilters: {
    poolFilters: {
      desiredLocation: ['Everywhere'],
      byDate: '',
      waterType: '',
      byPeriod: '',
      lat: 0,
      lng: 0,
      minPrice: '',
      maxPrice: '',
      amenities: [],
      startDate: '',
      endDate: '',
    },
    chaletFilters: {
      desiredLocation: ['Everywhere'],
      byDate: '',
      lat: 0,
      lng: 0,
      minPrice: '',
      maxPrice: '',
      amenities: [],
      startDate: '',
      endDate: '',
    },
    campFilters: {
      desiredLocation: ['Everywhere'],
      byDate: '',
      lat: 0,
      lng: 0,
      minPrice: '',
      maxPrice: '',
      amenities: [],
      startDate: '',
      endDate: '',
    },
    resetFilter: false,
  },
  poolFilters: {
    desiredLocation: ['Everywhere'],
    byDate: '',
    waterType: '',
    byPeriod: '',
    lat: 0,
    lng: 0,
    minPrice: '',
    maxPrice: '',
    amenities: [],
    startDate: '',
    endDate: '',
  },
  chaletFilters: {
    desiredLocation: ['Everywhere'],
    byDate: '',
    lat: 0,
    lng: 0,
    minPrice: '',
    maxPrice: '',
    amenities: [],
    startDate: '',
    endDate: '',
  },
  campFilters: {
    desiredLocation: ['Everywhere'],
    byDate: '',
    lat: 0,
    lng: 0,
    minPrice: '',
    maxPrice: '',
    amenities: [],
    startDate: '',
    endDate: '',
  },
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case types.SET_FILTER_TYPE:
      return {
        ...state,
        filterDataType: action.filterDataType,
      };
    case types.SET_POSITION:
      return {
        ...state,
        position: action.position,
      };
    case types.SET_FILTERS:
      return {
        ...state,
        resetFilter: !_.isUndefined(action.allFilters.resetFilter)
          ? action.allFilters.resetFilter
          : state.resetFilter,
        allFilters: {
          ...state.allFilters,
          ...action.allFilters,
        },
        poolFilters: {...state.poolFilters, ...action.allFilters.poolFilters},
        chaletFilters: {
          ...state.chaletFilters,
          ...action.allFilters.chaletFilters,
        },
        campFilters: {...state.campFilters, ...action.allFilters.campFilters},
      };

    default:
      return state;
  }
}
