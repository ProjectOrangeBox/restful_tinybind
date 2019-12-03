class orangeLoader {
	/* on construction */
	constructor(app) {
		this.app = app;
	}
	/**
	 * load just a model then...
	 */
	model(modelEndPoint, then) {
		let orangeLoader = this;

		this.app.request.on(200, function (data, status, xhr) {
			orangeLoader.app.rebind(data, then);
		}).get(modelEndPoint);

		return this; /* allow chaining */
	}

	/**
	 * load just a template then...
	 */
	template(templateEndPoint, then) {
		let orangeLoader = this;
		let cacheKey = templateEndPoint + '.template';
		let template = undefined;

		/* is this stored in our local template cache */
		if (this.app.templates[templateEndPoint] !== undefined) {
			/* yes it is so grab it */
			template = this.app.templates[templateEndPoint];
		} else if (storage !== undefined) {
			/* is this stored in our cached data */
			template = storage.getItem(cacheKey, undefined);

			console.log('getItem', cacheKey, template);
		}

		/* have we already loaded the template? */
		if (template !== undefined) {
			this.app.replace(template);

			if (then) {
				then();
			}
		} else {
			let url = this.app.config.templateUrl + templateEndPoint;

			console.log('load.template ' + url);

			/* setup retrieve model - success */
			this.app.request.on(200, function (data, status, xhr) {
				/* if storage is setup than store a copy */
				if (storage !== undefined) {
					let cacheSeconds = data.template.cache ? data.template.cache : orangeLoader.app.config.templateCache;

					console.log('cache key set ' + cacheKey, cacheSeconds);

					storage.setItem('setItem', cacheKey, data.template.source, cacheSeconds);
				}

				orangeLoader.app.replace(data.template.source);

				if (then) {
					then();
				}
			}).get(url);
		}

		return this; /* allow chaining */
	}

	/**
	 * load a template and then a model then...
	 */
	block(templateEndPoint, modelEndPoint, then) {
		let orangeLoader = this;

		modelEndPoint = this.app.config.modelUrl + modelEndPoint;

		console.log('load.block ' + modelEndPoint);

		if (templateEndPoint) {
			/* load the template then the model */
			this.template(templateEndPoint, function () {
				orangeLoader.model(modelEndPoint, then);
			});
		} else {
			/* just load the model */
			this.model(modelEndPoint, then);
		}

		return this; /* allow chaining */
	}

}