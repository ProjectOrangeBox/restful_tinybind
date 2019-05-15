
tinybind.stdlib = {
		defaultPrecision: 2,
		defaultThousandSeparator: "'",
		defaultDecimalSeparator: ".",

		defaultDateFormat: "YYYY-MM-DD",
		defaultTimeFormat: "HH:mm:ss",
		defaultDatetimeFormat: "YYYY-MM-DD HH:mm:ss",
};

/*
	* basic formatters for rivets
	*
	*/

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
		return typeof target == "boolean";
};

tinybind.formatters.isNumeric = function(target) {
		return !isNaN(target);
};

tinybind.formatters.isNaN = function(target) {
		if(tinybind.formatters.isArray(target))
				return true;
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
		return tinybind.formatters.toBoolean(target) && typeof target == "object" && !tinybind.formatters.isArray(target);
};

tinybind.formatters.isFunction = function(target) {
		return typeof target == "function";
};

tinybind.formatters.isArray = function(target) {
		return tinybind.formatters.isFunction(Array.isArray) ? Array.isArray(target) : target instanceof Array;
};

tinybind.formatters.isString = function(target) {
		return typeof target == "string" || target instanceof String;
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
		return retI == retF ? retI : retF;
};

tinybind.formatters.toArray = function(target) {
		if(tinybind.formatters.isArray(target)) {
				return target;
		}else if(tinybind.formatters.isObject(target)) {
				return tinybind.formatters.values(target);
		}

		return [target];
};

tinybind.formatters.toString = function(target) {
		return target ? target.toString() : "";
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

/*
tinybind.formaters.crossMultiplication = function(target, base, total) {
		return (target / base) * total
}

tinybind.formaters.percents = function(target, base, total) {
		return tinybind.formaters.crossMultiplication(target, base, total) + "%";
}
*/

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
		for(var i = 0; i < arguments.length; i++) {
				if(tinybind.formatters.toBoolean(arguments[i])) {
						return true;
				}
		}
		return false;
};

tinybind.formatters.and = function() {
		for(var i = 0; i < arguments.length; i++) {
				if(!tinybind.formatters.toBoolean(arguments[i])) {
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

		if(!tinybind.formatters.isInteger(precision))
				precision = tinybind.stdlib.defaultPrecision;
		if(!decimalSeparator)
				decimalSeparator = tinybind.stdlib.defaultDecimalSeparator;
		if(!thousandSeparator)
				thousandSeparator = tinybind.stdlib.defaultThousandSeparator;

		/*
			*thanks to user2823670
			* http://stackoverflow.com/questions/10015027/javascript-tofixed-not-rounding
			*/
		var ret = (+(Math.round(+(Math.abs(target) + 'e' + precision)) + 'e' + -precision)).toFixed(precision)
		if(target < 0)
				ret = '-' + ret;

		/**
		 * thanks to Elias Zamaria
		 * http://stackoverflow.com/questions/2901102/how-to-print-a-number-with-commas-as-thousands-separators-in-javascript
		 */
		ret = ret.split(".");
		if(ret.length==2) {
				return ret[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator) + decimalSeparator + ret[1];
		}

		return ret[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
};

/* date functions */

tinybind.formatters.date = function(target) {
		return moment(target).format(tinybind.stdlib.defaultDateFormat);
};

tinybind.formatters.time = function(target) {
		return moment(target).format(tinybind.stdlib.defaultTimeFormat);
};

tinybind.formatters.datetime = function(target) {
		return moment(target).format(tinybind.stdlib.defaultDatetimeFormat);
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

	for (const key in object) {
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

tinybind.formatters.stringFormat = function(target) {
		for (var i = 1; i < arguments.length; i++) {
				var offset = target.indexOf("%s");
				if (offset === -1){
						break;
				}

				target = target.slice(0, offset) + arguments[i] + target.slice(offset + 2);
		}

		return target;
}


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
		return target.length
	}

	return tinybind.formatters.toArray(target).length;
}

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

/**
 *
 * basic bindings for rivets
 *
 */

tinybind.binders.width = function(el, value) {
		el.style.width = value;
};

tinybind.binders.height = function(el, value) {
		el.style.height = value;
};

/* tiny integer 0/1 checkbox */
tinybind.binders.intcheck = {
	publishes: true,
	priority: 2000,

	bind: function(el) {
		var self = this;
		if (!this.callback) {
			this.callback = function () {
				self.publish();
			}
		}
		el.addEventListener('change', this.callback)
	},
	unbind: function(el) {
		el.removeEventListener('change', this.callback)
	},
	routine: function(el, value) {
		el.checked = (el.value == value);
	},
	getValue: function(t) {
		return (t.checked) ? t.value : 0;
	}
}

tinybind.formatters.enum = function (el, value) {
	return arguments[(parseInt(arguments[0]) + 1)];
}

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
tinybind.formatters.inject = tinybind.formatters.stringFormat;
tinybind.formatters.sprintf = tinybind.formatters.stringFormat;
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
