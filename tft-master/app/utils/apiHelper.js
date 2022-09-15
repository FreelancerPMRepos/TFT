import perf from '@react-native-firebase/perf';
import {BaseSetting} from '../config/setting';

const defaultheader = {
  'Content-Type': 'application/json',
};

export function getApiData(
  endpoint,
  method,
  data,
  headers = defaultheader,
  callType = '',
) {
  const startTime = new Date();
  return new Promise(async (resolve, reject) => {
    // if (method.toLowerCase() === 'get' || 1 == 1) {
    let query = '';
    let qs = '';
    data.apiVersion = 2;
    // eslint-disable-next-line no-undef
    for (key in data) {
      // eslint-disable-next-line no-undef
      query += `${encodeURIComponent(key)}=${encodeURIComponent(data[key])}&`;
    }
    // }
    const params = {};
    params.method = method.toLowerCase() === 'get' ? 'get' : 'post';
    if (headers) {
      params.headers = headers;
    } else {
      params.headers = {
        // Accept: 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
      };
    }

    if (data.token !== undefined && data.token !== '') {
      params.headers.Authorization = `Bearer ${data.token}`;
      delete data.token;
    }

    if (
      headers &&
      headers['Content-Type'] &&
      headers['Content-Type'] === 'application/json'
    ) {
      console.log('headers=>');
      console.log(headers);
      params.body = JSON.stringify(data);
    } else if (params.method === 'post') {
      params.body = query;
    } else {
      qs = `?${query}`;
    }

    console.log(params);

    const Url = BaseSetting.api + endpoint + qs;
    const metric = await perf().newHttpMetric(Url, params.method.toUpperCase());

    if (
      headers &&
      headers['Content-Type'] &&
      headers['Content-Type'] === 'application/json'
    ) {
      console.log(JSON.stringify(data));
    } else {
      let str = '';
      if (data && Object.keys(data).length > 0) {
        Object.keys(data).map(dk => {
          str += `${dk}:${data[dk]}\n`;
          metric.putAttribute(dk, data[dk]);
        });
      }
      console.log(str);
    }

    console.log('URL String:', Url);
    console.log('Params:', params);

    await metric.start();
    fetch(Url, params)
      .then(async response => {
        metric.setHttpResponseCode(response.status);
        metric.setResponseContentType(response.headers.get('Content-Type'));

        console.log(
          'HTTP RESP ===> ',
          response,
          response.headers.get('Content-Length'),
        );
        if (
          response.headers.get('Content-Length') &&
          Number(response.headers.get('Content-Length')) > 0
        ) {
          metric.setResponsePayloadSize(
            Number(response.headers.get('Content-Length')),
          );
        }
        // Stop the metric
        const timeTaken = new Date() - startTime;
        console.log(
          'timeTaken ===> URL => ',
          Url,
          '\n',
          ' Time Taken ==> ',
          timeTaken,
        );
        await metric.stop();
        return response.json();
      })
      .then(resposeJson => {
        console.log(resposeJson);
        if (resposeJson.logout) {
          // logOut();
        } else {
          resolve(resposeJson);
        }
      })
      .catch(err => {
        console.log(err);
        reject(err);
      });
  });
}

export function getApiDataProgress(endpoint, method, data, onProgress) {
  return new Promise((resolve, reject) => {
    const url = BaseSetting.api + endpoint;
    const oReq = new XMLHttpRequest();
    oReq.upload.addEventListener('progress', event => {
      if (event.lengthComputable) {
        const progress = (event.loaded * 100) / event.total;
        if (onProgress) {
          onProgress(progress);
        }
      } else {
        // Unable to compute progress information since the total size is unknown
      }
    });

    const query = new FormData();
    if (data && Object.keys(data).length > 0) {
      Object.keys(data).map(k => query.append(k, data[k]));
    }
    const params = query;
    oReq.open(method, url, true);
    console.log(params);
    console.log(url);
    oReq.send(params);
    oReq.onreadystatechange = () => {
      if (oReq.readyState === XMLHttpRequest.DONE) {
        try {
          // console.log(oReq.responseText);
          const resposeJson = JSON.parse(oReq.responseText);
          console.log(resposeJson);
          if (resposeJson.logout) {
            // logOut();
          } else {
            resolve(resposeJson);
          }
        } catch (exe) {
          console.log(exe);
          reject(exe);
        }
      }
    };
  });
}
