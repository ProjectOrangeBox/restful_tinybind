/**
 * Smarter Browser Storage
 */
var storage = {
	storage: undefined, /* local reference */
	config : {
		dbPrefix:'superStorge', /* every key must start with this */
		storage: 'localStorage' /* which storage to use localStorage or sessionStorage */
	},
	getItem : function(key,defaultValue) {
		var record = this._getByComplete(this.config.dbPrefix + key);

		if (record.expires > 0) {
			/* if the unix timestamp is currently greater than records expires timestamp */
			if (Math.floor(new Date().getTime() / 1000) > record.expires) {
				/* remove it completely */
				this.removeItem(key);

				/* and make sure the records data is really nothing */
				record.data = undefined;
			}
		}

		/* if the records data is undefined then return the default value */
		return (record.data) ? record.data : defaultValue;
	},
	removeItem : function(key,completeKey) {
		if (this.capable()) {
			var completeKey = (completeKey) ? completeKey : this.config.dbPrefix + key;

			this.storage.removeItem(completeKey);
		}
	},
	clear : function() {
		this.removeOlderThan(0);
	},
	setItem : function(key,data,expireSeconds) {
		if (this.capable()) {
			var	timestamp = Math.floor(new Date().getTime()/1000);
			var expireSeconds = (expireSeconds) ? timestamp + expireSeconds : -1;

			try {
				this.storage.setItem(this.config.dbPrefix + key, JSON.stringify({data:data,modified:timestamp,expires:expireSeconds}));

				return true;
			} catch(e) {
				/* if the save fails, it's likely because Storage is full we get around this by deleting the oldest record to free space... */
				this.removeItem(this._findOldest(),true);

				/* ...then try the save again! */
				this.setItem(key,data,expireSeconds);

				return false;
			}
		}
	},
	getDetailed : function(key) {
		return this._getByComplete(this.config.dbPrefix + key);
	},
	removeOlderThan : function(seconds) {
		/* should really only be used internally */
		var timestamp = Math.floor(new Date().getTime()/1000);
		var olderThanTimestamp = (seconds == 0 || seconds === undefined) ? timestamp : timestamp - seconds;
		var localKeys = Object.keys(this.storage);
		var totalKeys = localKeys.length;

		for (var i = 0; i < totalKeys; i++) {
			var key = localKeys[i];
			if (key.startsWith(this.config.dbPrefix)) {
				var record = this._getByComplete(key);
				if (olderThanTimestamp > record.modified || olderThanTimestamp > record.expires) {
					this.removeItem(key,true);
				}
			}
		}
	},
	_getByComplete: function (completeKey) {
		/* default empty record */
		var record = {expires: -1,modified: -1, data: undefined};

		if (this.capable()) {
			var jsonData = this.storage[completeKey];

			record = (jsonData) ? JSON.parse(jsonData) : record;
		}

		return record;
	},
	_findOldest : function() {
		/* should really only be used internally */
		var oldestTimestamp = 0;
		var localKeys = Object.keys(this.storage);
		var totalKeys = localKeys.length;
		var oldestRecordKey = 0;

		for (var i = 0; i < totalKeys; i++) {
			var key = localKeys[i];
			if (key.startsWith(this.config.dbPrefix)) {
				var record = this._getByComplete(key);
				if (record.modified < oldestTimestamp || oldestTimestamp === 0) {
					oldestRecordKey = key;
					oldestTimestamp = record.modified;
				}
			}
		}

		return oldestRecordKey;
	},
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