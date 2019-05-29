/**
 * Based On:
 * https://github.com/benjaminallison/SuperLocal
 *
 * Added expires, mirrors localStorage Syntax
 * Added removeOlderThan
 * Removed a few "extra" methods & settings
 *
 */
var storage = {
	_capable: undefined,
	settings : {
		dbPrefix:'db',
	},
	prefixRegex : function() {
		return new RegExp(this.settings.dbPrefix,'g');
	},
	modifiedRegex : function() {
		return new RegExp('modified','g');
	},
	capable : function() {
		if (this._capable == undefined) {
			var testData = 'ls';
			try {
				localStorage.setItem(testData, testData);
				localStorage.removeItem(testData);
				this._capable = true;
				return true;
			} catch(e) {
				this._capable = false;
				return false;
			}
		}

		return this._capable;
	},
	findOldest : function() {
		/* should really only be used internally */
		var timestamp = 0;
		var localKeys = Object.keys(localStorage);
		var totalKeys = localKeys.length;
		var oldestRecordID = 0;

		for (var i = 0; i < totalKeys; i++) {
			if (this.prefixRegex().test(localKeys[i]) && this.modifiedRegex().test(localKeys[i])) {
				var currentKey = localKeys[i].replace(this.settings.dbPrefix,'').replace('-modified','');
				var thisModified = parseInt(localStorage[this.settings.dbPrefix + currentKey + '-modified']);

				if (thisModified < timestamp || timestamp === 0) {
					oldestRecordID = currentKey;
					timestamp = thisModified;
				}
			}
		}
		return oldestRecordID;
	},
	removeOlderThan : function(seconds) {
		/* should really only be used internally */
		var timestamp = (seconds == 0 || seconds === undefined) ? 0 : Math.floor(new Date().getTime()/1000) - seconds;
		var localKeys = Object.keys(localStorage);
		var totalKeys = localKeys.length;

		for (var i = 0; i < totalKeys; i++) {
			if (this.prefixRegex().test(localKeys[i]) && this.modifiedRegex().test(localKeys[i])) {
				var currentKey = localKeys[i].replace(this.settings.dbPrefix,'').replace('-modified','');
				var thisModified = parseInt(localStorage[this.settings.dbPrefix + currentKey + '-modified']);

				if (timestamp > thisModified || timestamp === 0) {
					this.removeItem(currentKey);
				}
			}
		}
	},
	getItem : function(uID,defaultValue) {
		var data = this.getDetailed(uID);

		if (data.expires > 0) {
			if (Math.floor(new Date().getTime()/1000) > data.expires) {
				data.data = undefined;
				this.removeItem(uID);
			}
		}

		return (data.data) ? data.data : defaultValue;
	},
	getDetailed : function(uID) {
		var data = {};

		data.uID = uID;

		if (this.capable() === true) {
			var recordPrefix = this.settings.dbPrefix + uID;
			var jsonData = localStorage[recordPrefix + '-data'];

			data.data = (jsonData) ? JSON.parse(jsonData) : undefined;
			data.created = localStorage[recordPrefix + '-created'];
			data.modified = localStorage[recordPrefix + '-modified'];
			data.expires = localStorage[recordPrefix + '-expires'];
		}

		return data;
	},
	removeItem : function(uID) {
		var recordPrefix = this.settings.dbPrefix + uID;

		if (this.capable() === true) {
			console.log('remove ' + recordPrefix);

			localStorage.removeItem(recordPrefix + '-data');
			localStorage.removeItem(recordPrefix + '-created');
			localStorage.removeItem(recordPrefix + '-modified');
			localStorage.removeItem(recordPrefix + '-expires');
		}
	},
	clear : function() {
		if (this.capable() === true) {
			localStorage.clear();
		}
	},
	setItem : function(uID,data,expireSeconds) {
		if (this.capable() === true) {
			var recordPrefix = this.settings.dbPrefix + uID;

			// sets timestamp, and sets it to customTime if it's been provided
			var	timestamp = Math.floor(new Date().getTime()/1000);
			var expireSeconds = (expireSeconds) ? Math.floor(new Date().getTime()/1000) + expireSeconds : -1;

			try {
				// attempt to save the record
				localStorage.setItem(recordPrefix + '-data', JSON.stringify(data));
				localStorage.setItem(recordPrefix + '-modified', timestamp);
				localStorage.setItem(recordPrefix + '-expires', expireSeconds);
				// if there's currently no 'created' record for the supplied ID, we know that this is a record, so we'll make the associated 'created' record
				if (typeof(localStorage[recordPrefix + '-created']) === 'undefined' ) {
					localStorage.setItem(recordPrefix + '-created', timestamp);
				}
				return true;
			} catch(e) {
				// remove any partially failed data (example, 'modified' might have saved, but 'data' failed)
				this.removeItem(uID);
				// if the save fails, it's likely because localStorage is full
				// we get around this by deleting the oldest record to free space...
				this.removeItem(this.findOldest());
				// ...then try the save again!
				this.setItem(uID,data,expireSeconds);

				return false;
			}
		}
	}
};