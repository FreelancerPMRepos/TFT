import React, {Component} from 'react';
import {View, Dimensions, ActivityIndicator, StyleSheet} from 'react-native';
import {Text} from 'react-native-animatable';

const styles = StyleSheet.create({
  wrapper: {
    alignItems: 'center',
    justifyContent: 'center',
    width: Dimensions.get('window').width,
    backgroundColor: '#EEE',
    borderTopColor: '#CCC',
    borderTopWidth: 1,
  },
  container: {
    alignItems: 'center',
    justifyContent: 'center',
  },
  textContainer: {
    height: 'auto',
    padding: 20,
  },
  text: {
    fontSize: 10,
    color: '#000',
    paddingTop: 10,
  },
});

export class CTopNotify extends Component {
  render() {
    const {title} = this.props;
    return (
      <View style={styles.wrapper}>
        <View style={[styles.container, styles.textContainer]}>
          <ActivityIndicator />
          {title && <Text style={styles.text}>{title}</Text>}
        </View>
      </View>
    );
  }
}

export default CTopNotify;
