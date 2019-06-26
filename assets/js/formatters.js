/* general */
tinybind.formatters.log = function(target) {
	return console.log(target);
};

tinybind.formatters.default = function(target, val) {
	return !tinybind.formatters.isEmpty(target) ? target : val;
};

tinybind.formatters.add = function(target, val) {
	return target + val;
};

tinybind.formatters.sub = function(target, val) {
	return target - val;
};

tinybind.formatters.map = function(target, obj, prop) {
	var args = Array.prototype.slice.call(arguments);
	args.splice(1,2);
	return obj[prop].apply(obj, args);
};

/* check JS types */
tinybind.formatters.isBoolean = function(target) {
	return typeof target === "boolean";
};

tinybind.formatters.isNumeric = function(target) {
	return !isNaN(target);
};

tinybind.formatters.isNaN = function(target) {
	if (tinybind.formatters.isArray(target)) {
		return true;
	}

	return isNaN(target);
};

tinybind.formatters.isInteger = function(n) {
	/**
	 * thanks a lot to Dagg Nabbit
	 * http://stackoverflow.com/questions/3885817/how-to-check-if-a-number-is-float-or-integer
	 */
	return n === +n && n === (n|0);
};

tinybind.formatters.isFloat = function(n) {
	/**
	 * thanks a lot to Dagg Nabbit
	 * http://stackoverflow.com/questions/3885817/how-to-check-if-a-number-is-float-or-integer
	 */
	return Infinity !== n && n === +n && n !== (n|0);
};

tinybind.formatters.isNumber = function(target) {
	return tinybind.formatters.isFloat(target) || tinybind.formatters.isInteger(target);
};

tinybind.formatters.isObject = function(target) {
	return tinybind.formatters.toBoolean(target) && typeof target === "object" && !tinybind.formatters.isArray(target);
};

tinybind.formatters.isFunction = function(target) {
	return typeof target === "function";
};

tinybind.formatters.isArray = function(target) {
	return tinybind.formatters.isFunction(Array.isArray) ? Array.isArray(target) : target instanceof Array;
};

tinybind.formatters.isString = function(target) {
	return typeof target === "string" || target instanceof String;
};

tinybind.formatters.isInfinity = function(target) {
	return target === Infinity;
};

/* convert JS types */
tinybind.formatters.toBoolean = function(target) {
	return !!target;
};

tinybind.formatters.toInteger = function(target) {
	var ret = parseInt(target * 1, 10);
	return isNaN(ret) ? 0 : ret;
};

tinybind.formatters.toFloat = function(target) {
	var ret = parseFloat(target * 1.0);
	return isNaN(ret) ? 0.0 : ret;
};

tinybind.formatters.toDecimal = function(target) {
	var retI = tinybind.formatters.toInteger(target*1);
	var retF = tinybind.formatters.toFloat(target);
	return retI === retF ? retI : retF;
};

tinybind.formatters.toArray = function(target) {
	if (tinybind.formatters.isArray(target)) {
		return target;
	} else if (tinybind.formatters.isObject(target)) {
		return tinybind.formatters.values(target);
	}

	return [target];
};

tinybind.formatters.toString = function(target) {
	return target ? target.toString() : "";
};

tinybind.formatters.valuesToString = function(target) {
	if (Array.isArray(target)) {
		target.forEach(function(value,index) {
			target[index] = value.toString();
		});
	}

	return target;
};

tinybind.formatters.integer = {
	read: function(target) {
		return tinybind.formatters.toInteger(target);
	},

	publish: function(target) {
		return tinybind.formatters.toInteger(target);
	}
};

/* Math functions */
tinybind.formatters.sum = function(target, val) {
	return (1 * target) + (1 * val);
};

tinybind.formatters.substract = function(target, val) {
	return (1 * target) - (1 * val);
};

tinybind.formatters.multiply = function(target, val) {
	return (1 * target) * (1 * val);
};

tinybind.formatters.divide = function(target, val) {
	return (1 * target) / (1 * val);
};

tinybind.formatters.min = function() {
	return Math.min.apply(Math, arguments);
};

tinybind.formatters.max = function() {
	return Math.max.apply(Math, arguments);
};

/* comparisons */
tinybind.formatters.isEqual = function(target, val) {
	return target === val;
};

tinybind.formatters.isNotEqual = function(target, val) {
	return target !== val;
};

tinybind.formatters.isLess = function(target, val) {
	return (target * 1) < (val * 1);
};

tinybind.formatters.isGreater = function(target, val) {
	return (target * 1) > (val * 1);
};

tinybind.formatters.isLessEqual = function(target, val) {
	return (target * 1) <= (val * 1);
};

tinybind.formatters.isGreaterEqual = function(target, val) {
	return (target * 1) >= (val * 1);
};

/* logical functions */
tinybind.formatters.or = function() {
	for (var i = 0; i < arguments.length; i++) {
		if (tinybind.formatters.toBoolean(arguments[i])) {
			return true;
		}
	}

	return false;
};

tinybind.formatters.and = function() {
	for (var i = 0; i < arguments.length; i++) {
		if (!tinybind.formatters.toBoolean(arguments[i])) {
			return false;
		}
	}

	return true;
};

tinybind.formatters.negate = function(target) {
	return !tinybind.formatters.toBoolean(target);
};

tinybind.formatters.if = function(target, trueCase, falseCase) {
	return tinybind.formatters.toBoolean(target) ? trueCase : falseCase;
};

/* number functions */
tinybind.formatters.numberFormat = function(target, precision, decimalSeparator, thousandSeparator) {
	target = tinybind.formatters.isNumber(target) ? target : tinybind.formatters.toDecimal(target);

	if (!tinybind.formatters.isInteger(precision)) {
		precision = tinybind.config.defaultPrecision;
	}

	if (!decimalSeparator) {
		decimalSeparator = tinybind.config.defaultDecimalSeparator;
	}

	if (!thousandSeparator) {
		thousandSeparator = tinybind.config.defaultThousandSeparator;
	}

	/*
		*thanks to user2823670
		* http://stackoverflow.com/questions/10015027/javascript-tofixed-not-rounding
		*/
	var ret = (+(Math.round(+(Math.abs(target) + 'e' + precision)) + 'e' + -precision)).toFixed(precision);

	if (target < 0) {
		ret = '-' + ret;
	}

	/**
	 * thanks to Elias Zamaria
	 * http://stackoverflow.com/questions/2901102/how-to-print-a-number-with-commas-as-thousands-separators-in-javascript
	 */
	ret = ret.split(".");
	if (ret.length ===2) {
		return ret[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator) + decimalSeparator + ret[1];
	}

	return ret[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
};

/* date functions */

tinybind.formatters.date = function(target) {
	return moment(target).format(tinybind.config.defaultDateFormat);
};

tinybind.formatters.time = function(target) {
	return moment(target).format(tinybind.config.defaultTimeFormat);
};

tinybind.formatters.datetime = function(target) {
	return moment(target).format(tinybind.config.defaultDatetimeFormat);
};

tinybind.formatters.toTimestamp = function(target) {
	return moment(target).format("X");
};

tinybind.formatters.toDate = function(target) {
	return moment.unix(target).toDate();
};

tinybind.formatters.toMoment = function(target) {
	return moment(target);
};

/**
 * The date formatter returns a formatted date string according to the moment.js
 * formatting syntax.
 *
 * ```html
 * <span rv-value="model:date | date 'dddd, MMMM Do'"></span>
 * ```
 *
 * @see {@link http://momentjs.com/docs/#/displaying} for format options.
 */
tinybind.formatters.dateFormat = function(target, val) {
	return moment(target).format(val);
};

/* object functions */

tinybind.formatters.pairs = function(target) {
	return Object.keys(target).map(function(key) {
		return {
		'object': target,
		'property': key,
		'value': target[key]
		};
	});
};

tinybind.formatters.catalog = function(input,object,id,value) {
	id = (id) ? id : 'id';
	value = (value) ? value : 'value';

	for (var key in object) {
		if (object[key][id] == input) {
			return object[key][value];
		}
	}

	return '';
};

tinybind.formatters.keys = function(target) {
	return Object.keys(target);
};

tinybind.formatters.values = function(target) {
	return Object.keys(target).map(function(key) { return target[key]; });
};

/* string functions */
tinybind.formatters.split = function(target, val) {
	return target.split(val);
};

tinybind.formatters.lower = function(target) {
	return target.toLowerCase();
};

tinybind.formatters.upper = function(target) {
	return target.toUpperCase();
};

tinybind.formatters.capitalize = function(target) {
	target = tinybind.formatters.toString(target);

	return target.split(" ").map(function(seq) {
		return seq.split("-").map(function(word) {
			return word.charAt(0).toUpperCase() + word.slice(1);
		}).join("-");
	}).join(" ");
};

/* string&array functions */
tinybind.formatters.contains = function(target, val) {
	return target.indexOf(val) !== -1;
};

tinybind.formatters.doesNotContain = function(target, val) {
	return tinybind.formatters.negate(tinybind.formatters.contains(target, val));
};

tinybind.formatters.prettyPrint = function(target) {
	return JSON.stringify(target, null, 2);
};

tinybind.formatters.isEmpty = function(target) {
	if (!tinybind.formatters.toBoolean(target)) {
		return true;
	}

	return tinybind.formatters.toArray(target).length === 0;
};

/* array formatters */

tinybind.formatters.length = function(target) {
	if (tinybind.formatters.isString(target)) {
		return target.length;
	}

	return tinybind.formatters.toArray(target).length;
};

tinybind.formatters.join = function(target, val) {
	return tinybind.formatters.toArray(target).join(val);
};

/* functions formatters */

tinybind.formatters.wrap = function(target) {
	var args = Array.prototype.slice.call(arguments);
	args.splice(0,1);

	return function(evt) {
		var cpy = args.slice();
		Array.prototype.push.apply(cpy, Array.prototype.slice.call(arguments));
		return target.apply(this, cpy);
	};
};

tinybind.formatters.delay = function(target, ts) {
	var self = this;

	return function() {
		setTimeout(function() { target.apply(self, arguments); }, ts);
	};
};

tinybind.formatters.preventDefault = function(target) {
	var self = this;
	return function(evt) {
		evt.preventDefault();
		target.call(self, evt);
		return false;
	};
};

tinybind.formatters.enum = function(el, value) {
	return arguments[(parseInt(arguments[0]) + 1)];
};

tinybind.formatters.bootstrap_nav = function(records) {
	var html = '';

	html += tinybind.config.nav.open;

	var submenu = function(record,isRoot) {
		var html = '';

		if (Array.isArray(record.children)) {
			if (record.text) {
				html += (isRoot) ? tinybind.config.nav.item.open : tinybind.config.nav.item.openSub;
				html += sprintf(tinybind.config.nav.item.rowSub,record.url,record.text);

				for (var idx in record.children) {
					if (record.children[idx]) {
						html += submenu(record.children[idx],false);
					}
				}

				html += tinybind.config.nav.item.close;
			}
		} else {
			/* item */
			if (record.text) {
				record.target = (record.target != null) ? record.target : '';
				html += (record.text == '{hr}') ? tinybind.config.nav.item.hr : sprintf(tinybind.config.nav.item.rowSingle,record.url,record.text,record.target);
			}
		}

		return html;
	};

	/* start with the navbar level */
	for (var idx in records) {
		if (records[idx]) {
			html += submenu(records[idx],true);
		}
	};

	html += tinybind.config.nav.close;

	if (!tinybind.formatters.bootstrap_navINIT) {
		setInterval(function(){
			var json = JSON.stringify(app.page.nav);

			if (json !== tinybind.formatters.app_page_nav) {
				var saved = app.page.nav;
				app.page.nav = {};
				app.page.nav = saved;
			}

			tinybind.formatters.app_page_nav = json;
		},300);
		tinybind.formatters.bootstrap_navINIT = true;
	}

	return html;
};

/* formatter shortcuts */
tinybind.formatters.eq = tinybind.formatters.isEqual;
tinybind.formatters.ne = function(target, val) {
	return tinybind.formatters.negate(tinybind.formatters.isEqual(target, val));
};

tinybind.formatters.lt = tinybind.formatters.isLess;
tinybind.formatters.gt = tinybind.formatters.isGreater;

tinybind.formatters.le = tinybind.formatters.isLessEqual;
tinybind.formatters.lte = tinybind.formatters.isLessEqual;

tinybind.formatters.ge = tinybind.formatters.isGreaterEqual;
tinybind.formatters.gte = tinybind.formatters.isGreaterEqual;

tinybind.formatters.prv = tinybind.formatters.preventDefault;
tinybind.formatters.format = tinybind.formatters.dateFormat;
tinybind.formatters.len = tinybind.formatters.length;
tinybind.formatters.def = tinybind.formatters.default;
tinybind.formatters.neg = tinybind.formatters.negate;

tinybind.formatters.date = tinybind.formatters.dateFormat;

tinybind.formatters.stringify = tinybind.formatters.prettyPrint;
tinybind.formatters.int = tinybind.formatters.integer;

// backwards compatibility
tinybind.formatters.isLower = tinybind.formatters.isLess;
tinybind.formatters.isLowerEqual = tinybind.formatters.isLessEqual;

tinybind.formatters.stringFormat = sprintf();
tinybind.formatters.inject = sprintf();
tinybind.formatters.sprintf = sprintf();
