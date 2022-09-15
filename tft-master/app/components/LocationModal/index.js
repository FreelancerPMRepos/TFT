/* eslint-disable react-native/no-inline-styles */
import React, {Component} from 'react';
import _ from 'lodash';
import Modal from 'react-native-modal';
import {
  View,
  TouchableOpacity,
  FlatList,
  Dimensions,
  Platform,
} from 'react-native';
import styles from './styles';
import {Text, Icon} from '@components';
import {BaseColor} from '@config';
import {connect} from 'react-redux';

const IOS = Platform.OS === 'ios';

let selected = {
  location: [],
};

class LocationModal extends Component {
  constructor(props) {
    super(props);
    this.state = {
      selectedLocation: ['Everywhere'],
      modalVisible: props.modalVisible,
      location: props.location,
    };
  }

  componentDidMount() {
    /* Pass Ref of component */
    const {childLocRef} = this.props;
    if (childLocRef) {
      childLocRef(this);
    }
  }

  componentDidUpdate(prevProps) {
    if (!_.isEqual(prevProps.location, this.props.location)) {
      this.setState({
        location: this.props.location,
      });
    }
    if (!_.isEqual(prevProps.modalVisible, this.props.modalVisible)) {
      this.setState({
        modalVisible: this.props.modalVisible,
      });
    }
  }
  checkProps = () => {
    console.log('filterType', this.props);
    const {
      filterDataType,
      chaletFilters,
      poolFilters,
      campFilters,
    } = this.props.filter;
    console.log(
      'LocationModal -> checkProps -> filterDataType',
      filterDataType,
    );
    let prevProps = poolFilters && poolFilters.desiredLocation;
    if (filterDataType === 'Chalets') {
      prevProps = chaletFilters && chaletFilters.desiredLocation;
      console.log(
        'LocationModal -> checkProps -> prevProps',
        this.props,
        prevProps,
      );
    } else if (filterDataType === 'Camps') {
      prevProps = campFilters && campFilters.desiredLocation;
    }
    const nxtProps = this.props.sLocations;
    console.log('LocationModal -> checkProps', nxtProps, prevProps, isEqual);
    let isEqual = false;
    if (_.isEqual(nxtProps, prevProps)) {
      isEqual = true;
    }
    return isEqual;
  };

  setLocation() {
    const isSame = this.checkProps();
    console.log('LocationModal -> isSame', this.props, isSame);
    const {onModalClose} = this.props;
    if (!isSame) {
      this.setState(
        {
          modalVisible: false,
          selectedLocation: !_.isEmpty(selected.location)
            ? selected.location
            : this.state.selectedLocation,
        },
        onModalClose(this.state.selectedLocation),
      );
    } else {
      this.setState({modalVisible: false}, onModalClose([]));
    }
  }

  render() {
    const {selectedLocation, modalVisible} = this.state;
    const {translate, location, onChangeLocation, onModalClose} = this.props;
    const {
      language: {languageData},
    } = this.props;
    return (
      <Modal
        propagateSwipe={true}
        isVisible={modalVisible}
        // onBackdropPress={() => {}}
        onSwipeComplete={() => {
          this.setLocation();
        }}
        swipeDirection={'down'}
        swipeThreshold={200}
        onBackdropPress={() => {
          this.setLocation();
        }}
        deviceHeight={Dimensions.get('window').height / 2}
        deviceWidth={Dimensions.get('window').width}
        style={styles.bottomModal}>
        <View style={styles.contentFilterBottom}>
          <View style={styles.contentSwipeDown}>
            <View style={styles.lineSwipeDown} />
          </View>
          <View
            style={[styles.contentActionModalBottom, {height: IOS ? 55 : 50}]}>
            <TouchableOpacity
              onPress={() => {
                this.setState(
                  {
                    modalVisible: false,
                    location: this.state.location.map(item => {
                      if (selectedLocation.includes(item.city)) {
                        selected.water = item.city;
                        return {
                          ...item,
                          checked: true,
                        };
                      } else {
                        return {
                          ...item,
                          checked: false,
                        };
                      }
                    }),
                  },
                  () => {
                    onModalClose(this.state.selectedLocation);
                  },
                );
              }}>
              <Text body1>{translate('cancel')}</Text>
            </TouchableOpacity>
            <TouchableOpacity
              onPress={() => {
                this.setState(
                  {
                    modalVisible: false,
                    selectedLocation: !_.isEmpty(selected.location)
                      ? selected.location
                      : this.state.selectedLocation,
                  },
                  () => {
                    onModalClose(this.state.selectedLocation);
                  },
                );
              }}>
              <Text body1 primaryColor>
                {translate('save')}
              </Text>
            </TouchableOpacity>
          </View>
          <View
            style={[
              styles.lineRow,
              {
                paddingBottom: 0,
                maxHeight: Dimensions.get('window').height * 0.5,
              },
            ]}>
            <FlatList
              data={location}
              bounces={false}
              keyExtractor={(item, index) => item.id}
              contentContainerStyle={styles.flatList}
              renderItem={({item}) => {
                return (
                  <TouchableOpacity
                    style={styles.item}
                    onPress={() => onChangeLocation(item)}>
                    <Text
                      body1
                      style={
                        item.checked
                          ? {
                              color: BaseColor.primaryColor,
                            }
                          : {}
                      }>
                      {languageData === 'en' ? item.city : item.city_AR}
                    </Text>
                    {item.checked && (
                      <Icon
                        name="check"
                        size={14}
                        color={BaseColor.primaryColor}
                      />
                    )}
                  </TouchableOpacity>
                );
              }}
            />
          </View>
        </View>
      </Modal>
    );
  }
}

const mapStateToProps = state => ({
  language: state.language,
  filter: state.filter,
  poolFilters: state.filter.poolFilters,
});

export default connect(mapStateToProps, null)(LocationModal);
