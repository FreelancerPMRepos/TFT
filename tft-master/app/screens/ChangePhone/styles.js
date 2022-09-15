import {StyleSheet} from 'react-native';
import {BaseColor} from '@config';

export default StyleSheet.create({
  textInput: {
    height: 46,
    backgroundColor: BaseColor.fieldColor,
    borderRadius: 5,
    marginTop: 10,
    padding: 10,
    width: '100%',
  },
  contain: {
    alignItems: 'center',
    padding: 20,
    width: '100%',
  },
  MainAlertView: {
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'white',
    height: 200,
    width: '90%',
    borderColor: '#fff',
    borderRadius: 10,
    overflow: 'hidden',
  },
  AlertTitle: {
    fontSize: 20,
    color: '#000',
    textAlign: 'center',
    margin: 10,
  },
  AlertMessage: {
    fontSize: 22,
    color: '#000',
    textAlign: 'center',
    textAlignVertical: 'center',
    padding: 10,
    height: '40%',
  },
  buttonStyle: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  TextStyle: {
    color: '#000',
    textAlign: 'center',
    fontSize: 17,
  },
  borderStyleBase: {
    width: 30,
    height: 45,
  },

  borderStyleHighLighted: {
    borderColor: '#03DAC6',
  },

  underlineStyleBase: {
    width: 30,
    height: 45,
    borderWidth: 0,
    borderBottomWidth: 1,
    color: '#000',
  },

  underlineStyleHighLighted: {
    borderColor: BaseColor.primaryColor,
  },
});
