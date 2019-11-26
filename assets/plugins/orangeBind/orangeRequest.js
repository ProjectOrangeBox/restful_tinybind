class orangeRequest {

	constructor(app) {
		this.app = app;
	}

	send(method, url, data, callbacks) {
		console.log('request', method, url, data);

		jQuery.ajax({
			method: method,
			url: url,
			data: data,
			dataType: 'json',
			cache: !this.app.config.ajaxCacheBuster,
			/* ajax cache buster? */
			async: true,
			/* always! */
			timeout: this.app.config.ajaxTimeout,
			/* 5 seconds */
			statusCode: this.app.response.alter(callbacks).callbacks
		});

		return this;
	}

	get(url, data, callbacks) {
		return this.send('get', url, data, callbacks);
	}

	post(url, data, callbacks) {
		return this.send('post', url, data, callbacks);
	}

	patch(url, data, callbacks) {
		return this.send('patch', url, data, callbacks);
	}

	delete(url, data, callbacks) {
		return this.send('delete', url, data, callbacks);
	}

	create(url, data, callbacks) {
		return this.send('post', url, data, callbacks);
	}

	read(url, data, callbacks) {
		return this.send('get', url, data, callbacks);
	}

	update(url, data, callbacks) {
		return this.send('patch', url, data, callbacks);
	}

	insert(url, data, callbacks) {
		return this.send('post', url, data, callbacks);
	}

}