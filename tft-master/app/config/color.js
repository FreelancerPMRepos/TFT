/**
 * Color Palette Define
 */

const OrangeColor = {
  primaryColor: '#E5634D',
  darkPrimaryColor: '#C31C0D',
  lightPrimaryColor: '#FF8A65',
  accentColor: '#4A90A4',
};

const BlueColor = {
  primaryColor: '#4db2e5',
  darkPrimaryColor: '#4BB7ED',
  lightPrimaryColor: '#8BCCED',
  xLightPrimaryColor: '#8ac1e6',
  accentColor: '#FF8A65',
};

const PinkColor = {
  primaryColor: '#A569BD',
  darkPrimaryColor: '#C2185B',
  lightPrimaryColor: '#F8BBD0',
  accentColor: '#8BC34A',
};

const GreenColor = {
  primaryColor: '#58D68D',
  darkPrimaryColor: '#388E3C',
  lightPrimaryColor: '#C8E6C9',
  accentColor: '#607D8B',
};

const YellowColor = {
  primaryColor: '#FDC60A',
  darkPrimaryColor: '#FFA000',
  lightPrimaryColor: '#FFECB3',
  accentColor: '#795548',
};

/**
 * Main color use for whole application
 */
const BaseColor = {
  ...BlueColor,
  ...{
    textPrimaryColor: '#212121',
    textSecondaryColor: '#E0E0E1',
    grayColor: '#9B9B9B',
    lightGreyColor: '#CCC',
    darkBlueColor: '#24253D',
    dividerColor: '#BDBDBD',
    whiteColor: '#FFFFFF',
    blackColor: '#000000',
    fieldColor: '#EEEEEE',
    yellowColor: '#FDC60A',
    navyBlue: '#3C5A99',
    kashmir: '#5D6D7E',
  },
};

export {BaseColor, OrangeColor, BlueColor, PinkColor, GreenColor, YellowColor};
