/**
 * Smarter Browser Storage
 */
var storage = {
	storage: undefined, /* local reference */
	config : {
		dbPrefix:'{superStorge}', /* every key must start with this */
		storage: 'localStorage', /* which storage to use localStorage or sessionStorage */
		defaultSecondCache: 2629746, /* default seconds to cache a value before it expires (roughly 1 month) */
	},
	getItem : function(key,defaultValue) {
		/* get the complete record regardless of the expiration */
		var record = this._getByComplete(this.config.dbPrefix + key);
		var now = Math.floor(new Date().getTime() / 1000);

		/* if the unix timestamp is currently greater than records expires timestamp */
		if (now > record.expires) {
			/* remove the record */
			this.removeItem(key);

			/* and make sure the records data is really nothing */
			record = this._emptyRecord();
		}

		/* if the records data is undefined then return the default value */
		return (record.data) ? record.data : defaultValue;
	},
	removeItem : function(key,completeKey) {
		if (this.capable()) {
			/* if they didn't send in the complete key build it */
			key = (completeKey) ? key : this.config.dbPrefix + key;

			console.debug('removing',key);

			this.storage.removeItem(key);
		}
	},
	/* clear all of the storage key that match our prefix passing no argument uses right now as the timestamp */
	clear : function() {
		this.removeOlderThan();
	},
	getDetailed : function(key) {
		return this._getByComplete(this.config.dbPrefix + key);
	},
	removeOlderThan : function(seconds) {
		var localKeys = Object.keys(this.storage);
		var totalKeys = localKeys.length;
		var now = Math.floor(new Date().getTime() / 1000);

		seconds = seconds || 0;

		for (var i = 0; i < totalKeys; i++) {
			var key = localKeys[i];
			if (key.startsWith(this.config.dbPrefix)) {
				var record = this._getByComplete(key);
				/* remove any record regardless of the expires */
				if (record.created + seconds < now) {
						/* sending in complete key */
					this.removeItem(key,true);
				}
			}
		}
	},
	/* Set a storage value */
	setItem : function(key,data,seconds) {
		if (this.capable()) {
			var	now = Math.floor(new Date().getTime() / 1000);
			var expiresSeconds = (seconds != undefined) ? now + seconds : now + this.config.defaultSecondCache;
			var seconds = (seconds == undefined) ? 0 : parseInt(seconds);

			try {
				var completeKey = this.config.dbPrefix + key;

				this.storage.setItem(completeKey, JSON.stringify({data:data,created:now,expires:expiresSeconds,cacheFor:seconds}));

				return true;
			} catch(e) {
				/* if the save fails, it's likely because Storage is full we get around this by deleting the oldest record to free space... */
				this.removeItem(this._findOldest(),true);

				/* ...then try the save again! */
				this.setItem(key,data,seconds);

				return false;
			}
		}
	},
	/* includes the record prefix */
	_getByComplete: function (completeKey) {
		/* default empty record */
		var record = this._emptyRecord();

		if (this.capable()) {
			console.debug('reading',completeKey);

			var jsonData = this.storage[completeKey];

			record = (jsonData) ? JSON.parse(jsonData) : record;
		}

		return record;
	},
	_emptyRecord : function() {
		return {expires: -1,created: -1, data: undefined};
	},
	/* find the oldest record and return the complete key */
	_findOldest : function() {
		/* should really only be used internally */
		var localKeys = Object.keys(this.storage);
		var totalKeys = localKeys.length;
		var oldestRecordKey = 0;
		var oldestTimestamp = 0;

		for (var i = 0; i < totalKeys; i++) {
			var key = localKeys[i];
			if (key.startsWith(this.config.dbPrefix)) {
				var record = this._getByComplete(key);
				if (record.created < oldestTimestamp || oldestTimestamp === 0) {
					oldestRecordKey = key;
					oldestTimestamp = record.created;
				}
			}
		}

		return oldestRecordKey;
	},
	/* is storage supported? */
	capable : function(type) {
		if (this.storage == undefined) {
			type = (type) ? type : this.config.storage;

			try {
				this.storage = window[type];
				var x = '__storage_test__';
				this.storage.setItem(x, x);
				this.storage.removeItem(x);
			} catch(e) {
				this.storage = undefined;
			}
		}

		return (this.storage !== undefined);
	}
};