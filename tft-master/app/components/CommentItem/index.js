import React, {Component} from 'react';
import {View} from 'react-native';
import {Images, BaseColor} from '@config';
import _ from 'lodash';
import {Text, Image, StarRating} from '@components';
import PropTypes from 'prop-types';
import styles from './styles';
import * as Utils from '@utils';
import moment from 'moment';

export default class CommentItem extends Component {
  render() {
    const {style, image, fName, lName, rate, date, title, comment} = this.props;
    const fullName = `${fName}  ${lName}`;
    const fLetters = `${fName.charAt(0)}${lName.charAt(0)}`;
    const formatedTime = moment(date * 1000).format('DD MMM YYYY');
    return (
      <View style={[styles.contain, style]}>
        <View style={{marginBottom: 10}}>
          <View style={styles.contentLeft}>
            <View style={[styles.thumb]}>
              <Text style={styles.profilePictxt}>{fLetters.toUpperCase()}</Text>
            </View>
            {/* <Image source={image} style={styles.thumb} /> */}
            <View>
              <Text headline semibold numberOfLines={1} style={{width: 190}}>
                {fullName}
              </Text>
              <View style={styles.contentRate}>
                <StarRating
                  disabled={true}
                  starSize={14}
                  maxStars={5}
                  rating={rate}
                  selectedStar={rating => {}}
                  fullStarColor={BaseColor.yellowColor}
                />
                {/* <View style={styles.contentRight}>
                  <Text caption2 grayColor numberOfLines={1}>
                    {formatedTime}
                  </Text>
                </View> */}
              </View>
            </View>
            <View style={styles.contentRight}>
              <Text caption2 grayColor numberOfLines={1}>
                {formatedTime}
              </Text>
            </View>
          </View>
        </View>
        <View>
          {title && !_.isEmpty(title) ? (
            <Text subhead semibold>
              {title}
            </Text>
          ) : null}
          {/* <Text
            body2
            grayColor
            style={{
              marginTop: 10,
            }}>
            {comment}
          </Text> */}
        </View>
      </View>
    );
  }
}

CommentItem.propTypes = {
  style: PropTypes.oneOfType([PropTypes.object, PropTypes.array]),
  image: PropTypes.node.isRequired,
  name: PropTypes.string,
  rate: PropTypes.number,
  date: PropTypes.string,
  title: PropTypes.string,
  comment: PropTypes.string,
};

CommentItem.defaultProps = {
  style: {},
  image: Images.profile2,
  name: '',
  rate: 0,
  date: '',
  title: '',
  comment: '',
};
