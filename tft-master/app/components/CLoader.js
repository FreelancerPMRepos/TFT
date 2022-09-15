import React, {Component} from 'react';
import {StyleSheet, ActivityIndicator, View} from 'react-native';
import {BaseColor} from '@config';
const styles = StyleSheet.create({
  mainConSty: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
});

class CLoader extends Component {
  render() {
    return (
      <View style={styles.mainConSty}>
        <ActivityIndicator
          size={'small'}
          color={BaseColor.primaryColor}
          animating
        />
      </View>
    );
  }
}

export default CLoader;
