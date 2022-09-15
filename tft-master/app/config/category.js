import _ from 'lodash';
const categoryName = {
  pools: 'Pools',
  chalets: 'Chalets',
  camps: 'Camps',
};

const periodTypes = [
  {
    id: 1,
    title: 'Morning',
  },
  {
    id: 2,
    title: 'Evening',
  },
  {
    id: 3,
    title: 'Full Day',
  },
];

const getCurrentPeriod = cFilterPeriod => {
  console.log('Get current Period ===> ', cFilterPeriod, periodTypes);
  if (_.isUndefined(cFilterPeriod)) {cFilterPeriod = 'Full Day';}
  return periodTypes[
    cFilterPeriod === 'Full Day' ? 2 : cFilterPeriod === 'Evening' ? 1 : 0
  ];
};

export {categoryName, periodTypes, getCurrentPeriod};
export default categoryName;
