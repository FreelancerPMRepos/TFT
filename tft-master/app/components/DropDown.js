/* eslint-disable react-native/no-inline-styles */
/* eslint-disable no-lone-blocks */
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import _ from 'lodash';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Modal,
  Platform,
  Dimensions,
  ScrollView,
  Picker,
} from 'react-native';
import FIcon from 'react-native-vector-icons/MaterialCommunityIcons';
import Icon from 'react-native-vector-icons/FontAwesome';
import {BaseColor} from '../config/color';
import {FontFamily} from '@config';
// import {FORTAB} from '../config/MQ';
// import colors from '../config/colors';

const IOS = Platform.OS === 'ios';
// Data like this
// const dData = [
//   'Javascript',
//   'Go',
//   'Java',
//   'Kotlin',
//   'C++',
//   'C#',
//   'PHP',
// ];

// How to use
{
  /* <DropDown
  containerStyle={styles.dropdownStyle} // change as requirement
  placeholder="PlaceholderText"
  labelText="LabelTitle" // This is for label in left side
  data={startData}
  value={selectedValue}
  onChange={(value) => {
    this.setState({ selectedValue: value });
  }}
/> */
}

const styles = StyleSheet.create({
  container: {
    margin: 10,
    marginBottom: 0,
    marginRight: 0,
  },
  labelView: {
    width: '33%',
    justifyContent: 'center',
    paddingVertical: 5,
    paddingHorizontal: 15,
    backgroundColor: '#f5f8fe',
  },
  labelTextStyle: {
    color: '#708095',
    fontSize: 13,
  },
  valueTextStyle: {
    flex: 1,
    color: '#000',
    fontSize: 13,
    paddingLeft: 10,
    fontFamily: FontFamily.default,
    fontWeight: '600',
  },
  dropdownView: {
    flexDirection: 'row',
    backgroundColor: BaseColor.fieldColor,
    width: '94%',
    height: 46,
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: 6,
  },
});

class DropDown extends Component {
  constructor(props) {
    super(props);
    this.state = {
      modalVisible: false,
      iosModal: false,
      dataValue: {},
      selectedValue: '',
    };
  }

  closeModal = () => {
    this.setState({
      modalVisible: false,
      iosModal: false,
    });
  };

  openModal = () => {
    this.setState({
      modalVisible: true,
    });
  };

  isIphoneX = () => {
    const dimen = Dimensions.get('window');
    return (
      Platform.OS === 'ios' &&
      !Platform.isPad &&
      !Platform.isTVOS &&
      (dimen.height === 812 ||
        dimen.width === 812 ||
        dimen.height === 896 ||
        dimen.width === 896)
    );
  };

  setData = data => {
    const {onChange} = this.props;
    onChange(data);
    this.closeModal();
  };

  render() {
    const {
      containerStyle,
      placeHolder,
      value,
      data,
      labelText,
      ios,
      cancelButton,
      confirmButton,
      labelViewStyle,
      containViewStyle,
      valueStyle,
      rightIcon,
      iconSize,
      iconStyle,
      labelStyle,
      placeHolderTextColor,
      atoll,
      disabled,
    } = this.props;
    const {modalVisible, selectedValue, dataValue} = this.state;

    const iosStyles = {
      container: {
        flex: 1,
        justifyContent: 'flex-end',
        zIndex: 999,
      },
      content: {
        margin: 15,
        backgroundColor: 'white',
        borderRadius: 10,
        borderColor: 'rgba(0, 0, 0, 0.1)',
      },
      confirmButtonView: {
        borderBottomEndRadius: 10,
        borderBottomStartRadius: 10,
        backgroundColor: '#FFF',
        borderTopWidth: 1,
        borderTopColor: 'rgba(165,165,165,0.2)',
        paddingVertical: 15,
      },
      confirmButtonText: {
        fontWeight: '500',
        fontSize: 18,
        textAlign: 'center',
        color: 'rgba(0,122,255,1)',
      },
      cancelButton: {
        marginVertical: 10,
      },
      cancelButtonView: {
        marginHorizontal: 15,
        marginBottom: this.isIphoneX() ? 50 : 15,
        backgroundColor: '#FFF',
        padding: 15,
        borderRadius: 10,
      },
      cancelButtonText: {
        fontWeight: 'bold',
        fontSize: 18,
        textAlign: 'center',
        color: 'rgba(0,122,255,1)',
      },
      titleView: {
        padding: 12,
        borderBottomWidth: 1,
        borderBottomColor: 'rgba(165,165,165,0.2)',
      },
      titleText: {
        fontWeight: '500',
        fontSize: 14,
        textAlign: 'center',
        color: '#bdbdbd',
      },
    };

    return (
      <View style={[styles.container, containerStyle]}>
        {labelText ? (
          <View style={[styles.labelView, labelViewStyle]}>
            <Text
              allowFontScaling={false}
              style={[styles.labelTextStyle, labelStyle]}>
              {labelText}
            </Text>
          </View>
        ) : null}
        <TouchableOpacity
          style={[styles.dropdownView, containViewStyle]}
          activeOpacity={disabled ? 1 : 0.5}
          onPress={() =>
            disabled
              ? () => null
              : IOS
              ? this.setState({
                  iosModal: true,
                  selectedValue: _.isEmpty(value)
                    ? !_.isEmpty(data)
                      ? data[0].label
                      : ''
                    : value.label,
                  dataValue: _.isEmpty(value)
                    ? !_.isEmpty(data)
                      ? data[0]
                      : {}
                    : value,
                })
              : this.openModal()
          }>
          <Text
            numberOfLines={1}
            allowFontScaling={false}
            style={[styles.valueTextStyle, valueStyle]}>
            {_.isObject(value) && !_.isEmpty(value)
              ? value.label
              : _.isEmpty(selectedValue)
              ? placeHolder
              : !_.isEmpty(data)
              ? data[0].label
              : placeHolder}
          </Text>
          {rightIcon === 'caret-down' ? (
            <Icon
              name={rightIcon}
              size={iconSize}
              style={[{color: '#bac3d2'}, iconStyle]}
            />
          ) : (
            <FIcon
              name={rightIcon}
              size={iconSize}
              style={[{color: '#bac3d2', paddingRight: 10}, iconStyle]}
            />
          )}
        </TouchableOpacity>
        <Modal
          animationType="fade"
          transparent
          visible={modalVisible}
          onRequestClose={() => this.closeModal()}
          style={{flex: 1}}>
          <TouchableOpacity
            activeOpacity={1}
            onPress={this.closeModal}
            style={{
              flex: 1,
              alignItems: 'center',
              justifyContent: 'center',
              backgroundColor: 'rgba(0,0,0,0.6)',
              padding: 30,
            }}>
            <View
              style={{
                width: '100%',
                backgroundColor: '#FFF',
                borderRadius: 5,
              }}>
              {/* <View
                style={{
                  padding: 15,
                  flex: 1,
                  borderBottomWidth: 1,
                  borderBottomColor: '#ddd',
                }}
              >
                <Text>{placeHolder}</Text>
              </View> */}
              <ScrollView
                contentContainerStyle={{
                  width: '100%',
                }}
                style={{width: '100%'}}
                showsVerticalScrollIndicator={false}>
                {_.isArray(data) && data.length > 0
                  ? data.map((item, index) => (
                      <TouchableOpacity
                        key={`dropDown_${index}`}
                        activeOpacity={0.5}
                        style={{
                          padding: 15,
                          flex: 1,
                          borderBottomWidth:
                            !_.isEmpty(data) && data.length - 1 === index
                              ? 0
                              : 1,
                          borderBottomColor: '#ddd',
                        }}
                        onPress={() => this.setData(item)}>
                        <Text
                          style={{
                            // fontFamily: fonts.roboto.regular,
                            fontSize: 14,
                          }}>
                          {item.label}
                        </Text>
                      </TouchableOpacity>
                    ))
                  : null}
              </ScrollView>
            </View>
          </TouchableOpacity>
        </Modal>
        <Modal
          style={{flex: 1}}
          visible={this.state.iosModal}
          animationType="none"
          transparent>
          <View
            style={{
              flex: 1,
              backgroundColor: 'rgba(0,0,0,0.6)',
            }}>
            <View style={iosStyles.container}>
              <View style={iosStyles.content}>
                <View style={iosStyles.titleView}>
                  <Text style={[iosStyles.titleText, ios.titleStyle]}>
                    {placeHolder}
                  </Text>
                </View>
                <Picker
                  selectedValue={
                    selectedValue === null
                      ? !_.isEmpty(data)
                        ? data[0].label
                        : 0
                      : selectedValue
                  }
                  style={{maxHeight: 200, overflow: 'hidden'}}
                  onValueChange={(itemValue, itemIndex) => {
                    this.setState({
                      dataValue: {value: itemIndex + 1, label: itemValue},
                      selectedValue: itemValue,
                    });
                  }}>
                  {data.map((val, index) => (
                    <Picker.Item
                      key={`item-${index}`}
                      label={val.label}
                      value={val.label}
                    />
                  ))}
                </Picker>
                <TouchableOpacity
                  activeOpacity={0.9}
                  onPress={() => this.setData(dataValue)}>
                  <View
                    style={[
                      iosStyles.confirmButtonView,
                      {
                        opacity:
                          selectedValue !== null
                            ? selectedValue !== this.props.value
                              ? 1
                              : 0.1
                            : 1,
                      },
                    ]}>
                    <Text style={iosStyles.confirmButtonText}>
                      {confirmButton}
                    </Text>
                  </View>
                </TouchableOpacity>
              </View>
              <View style={iosStyles.cancelButton}>
                <TouchableOpacity activeOpacity={0.9} onPress={this.closeModal}>
                  <View style={iosStyles.cancelButtonView}>
                    <Text style={iosStyles.cancelButtonText}>
                      {cancelButton}
                    </Text>
                  </View>
                </TouchableOpacity>
              </View>
            </View>
          </View>
        </Modal>
      </View>
    );
  }
}

DropDown.propTypes = {
  containerStyle: PropTypes.objectOf(PropTypes.any),
  data: PropTypes.arrayOf(PropTypes.any),
  placeHolder: PropTypes.string,
  value: PropTypes.string,
  labelText: PropTypes.string,
  onChange: PropTypes.func,
  ios: PropTypes.objectOf(PropTypes.any),
  labelViewStyle: PropTypes.objectOf(PropTypes.any),
  containViewStyle: PropTypes.objectOf(PropTypes.any),
  valueStyle: PropTypes.objectOf(PropTypes.any),
  cancelButton: PropTypes.string,
  confirmButton: PropTypes.string,
  rightIcon: PropTypes.string,
  iconSize: PropTypes.number,
  iconStyle: PropTypes.objectOf(PropTypes.any),
  labelStyle: PropTypes.objectOf(PropTypes.any),
  placeHolderTextColor: PropTypes.string,
  atoll: PropTypes.bool,
  disabled: PropTypes.bool,
};

DropDown.defaultProps = {
  containerStyle: {},
  placeHolder: '',
  value: '',
  data: [],
  onChange: () => null,
  labelText: '',
  ios: {
    duration: 330,
    overlayColor: 'rgba(0,0,0,0.3)',
  },
  cancelButton: 'Cancel',
  confirmButton: 'Confirm',
  labelViewStyle: {},
  containViewStyle: {},
  valueStyle: {},
  rightIcon: 'chevron-down',
  iconSize: 25,
  iconStyle: {},
  labelStyle: {},
  placeHolderTextColor: '#bac3d2',
  atoll: false,
  disabled: false,
};

export default DropDown;
