/************************************************/
//		 Simple PubSub client/server
//	Push the minera stats to the Minera HQ server
//		To be served from getminera.com website
/************************************************/

var redis = require('redis'),
    client = redis.createClient(),
    request = require('request'),
    apiUrl = 'https://getminera.com/api';

client.subscribe('minera-channel');

client.on('message', function(channel, message){
	var message = JSON.parse(message), url;
	
	if (message.livestat) {
		url = [apiUrl, 'minera/stats', message.minera_id].join('/');
	} else {
		url = [apiUrl, 'minera/settings', message.minera_id].join('/')
	}

	request({
		method: 'PUT',
		uri: url,
		json: true,
		body: {data: message}
	}, function (err, response, body) {
		if (err) console.log({error: err});
		//console.log('Stats sent');
	});
});