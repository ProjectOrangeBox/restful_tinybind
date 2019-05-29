/**
 * Originally Based On:
 * https://github.com/benjaminallison/SuperLocal
 *
 * Added expires, mirrors localStorage Syntax
 * Added removeOlderThan
 * Removed a few "extra" methods & settings
 *
 */
var storage = {
	settings : {
		dbPrefix:'superStorge',
		storage: 'localStorage',
		capable: undefined
	},
	getItem : function(key,defaultValue) {
		var record = this._getByComplete(this.settings.dbPrefix + key);

		if (record.expires > 0) {
			if (Math.floor(new Date().getTime()/1000) > record.expires) {
				this.removeItem(key);
				record.data = undefined;
			}
		}

		return (record.data) ? record.data : defaultValue;
	},
	removeItem : function(key,completeId) {
		if (this.capable()) {
			var completeId = (completeId) ? completeId : this.settings.dbPrefix + key;

			localStorage.removeItem(completeId);
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
				localStorage.setItem(this.settings.dbPrefix + key, JSON.stringify({data:data,modified:timestamp,expires:expireSeconds}));

				return true;
			} catch(e) {
				/* if the save fails, it's likely because localStorage is full we get around this by deleting the oldest record to free space... */
				this.removeItem(this._findOldest(),true);

				/* ...then try the save again! */
				this.setItem(key,data,expireSeconds);

				return false;
			}
		}
	},
	getDetailed : function(key) {
		return this._getByComplete(this.settings.dbPrefix + key);
	},
	removeOlderThan : function(seconds) {
		/* should really only be used internally */
		var timestamp = Math.floor(new Date().getTime()/1000);
		var olderThanTimestamp = (seconds == 0 || seconds === undefined) ? timestamp : timestamp - seconds;
		var localKeys = Object.keys(localStorage);
		var totalKeys = localKeys.length;

		for (var i = 0; i < totalKeys; i++) {
			var key = localKeys[i];
			if (key.startsWith(this.settings.dbPrefix)) {
				var record = this._getByComplete(key);
				if (olderThanTimestamp > record.modified || olderThanTimestamp > record.expires) {
					this.removeItem(key,true);
				}
			}
		}
	},
	_getByComplete: function (completeId) {
		var record = {};

		if (this.capable()) {
			var jsonData = localStorage[completeId];

			record = (jsonData) ? JSON.parse(jsonData) : record;
		}

		return record;
	},
	_findOldest : function() {
		/* should really only be used internally */
		var oldestTimestamp = 0;
		var localKeys = Object.keys(localStorage);
		var totalKeys = localKeys.length;
		var oldestRecordKey = 0;

		for (var i = 0; i < totalKeys; i++) {
			var key = localKeys[i];
			if (key.startsWith(this.settings.dbPrefix)) {
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
		if (this.settings.capable == undefined) {
			type = (type) ? type : this.settings.storage;

			try {
				var x = '__storage_test__';
				window[type].setItem(x, x);
				window[type].removeItem(x);

				this.settings.capable = true;
			} catch(e) {
				this.settings.capable = false;
			}
		}

		return this.settings.capable;
	}
};