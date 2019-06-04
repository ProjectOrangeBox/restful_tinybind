/**
 *
 * Add Notification
 * optional redirect (see add examples)
 *
 * notify.info('Ya!')
 * notify.success('Saved')
 * notify.error('Oh Darn!')
 *
 * Show message on the Screen
 * notify.show('Oh No!','info')
 * notify.show('Oh No!','danger')
 * notify.show('Oh No!','warning')
 *
 * Save a message to display at a later time
 * optional redirect
 * notify.add('Oh No!','success')
 * notify.add('Oh No!','warning','@back')
 * notify.add('Oh No!','danger','/people/index')
 *
 * Show all variable and saved notifications
 * notify.showAll()
 *
 * Remove all of the notices on the screen
 * notify.removeAll()
 *
 * Flushes all variable and saved notifications
 * notify.flush()
 *
 * Redirect to another page
 * notify.redirect('@back')
 * notify.redirect('/people/index')
 *
 */
var messages = (messages) || [];

var notify = {
	stayTime: 3, /* seconds */
	storageKey: 'notifyMsg',
	defaultMsg: 'No Message Giving.',
	defaultStyle: 'info',
	noticeWrapAll: undefined,
	map: {
		red: 'danger',
		yellow: 'warning',
		blue: 'info',
		green: 'success',
		danger: 'danger',
		warning: 'warning',
		info: 'info',
		success: 'info',
		error: 'danger',
		failure: 'danger'
	},
	stay: ['danger'],
	init: function() {
		if (!this.noticeWrapAll) {
			this.noticeWrapAll	= jQuery('<div></div>').addClass('notice-wrap').appendTo('body');
		}

		return this.showAll();
	},
	showAll: function() {
		/* show all saved and variable messages */
		return this.loadFromStorage().loadFromVariable();
	},
	info: function(msg,redirect) {
		return this._autoDetect(msg,this.map.info,redirect);
	},
	success: function(msg,redirect) {
		return this._autoDetect(msg,this.map.success,redirect);
	},
	error: function(msg,redirect) {
		return this._autoDetect(msg,this.map.error,redirect);
	},
	show: function(msg,style) {
		/* immediately show a notification */
		return this._show(this.msgObj(msg,style));
	},
	add: function(msg,style,redirect) {
		/**
		 * Save the notification for the next page load
		 * if you included a optional redirect then redirect to it
		 */
		return this.save(this.msgObj(msg,style)).redirect(redirect);
	},
	redirect: function(redirect) {
		if (redirect) {
			if (redirect === '@back') {
				window.history.back();
			} else {
				window.location.href = redirect;
			}
		}

		return this;
	},
	removeAll: function() {
		/* remove from screen */
		jQuery('.notice-item-wrapper').each(function(){
			$(this).remove();
		});

		return this;
	},
	flush: function() {
		/* on page */
		messages = [];

		/* in storage */
		storage.removeItem(this.storageKey);

		return this;
	},
	loadFromStorage: function() {
		/**
		 * Any message saved in cold storage?
		 */
		var inStorageMessages = storage.getItem(this.storageKey,false);

		/* clear out */
		storage.removeItem(this.storageKey);

		return this._asArray(inStorageMessages);
	},
	loadFromVariable: function() {
		/**
		 * Any messages attached to the
		 * javascript global variable message on the page?
		 * this is inserted into the page from the server code
		 *
		 * <script>var messages = [{msg:'Foo',style:'success'},{msg:'Bar',style:'danger'}];</script>
		 */
		var onPageMessages = messages;

		/* clear out */
		messages = [];

		return this._asArray(onPageMessages);
	},
	msgObj: function(msg,style) {
		var msgObj = {};

		msgObj.msg = msg || this.defaultMsg;
		msgObj.style = this.map.hasOwnProperty(style) ? this.map[style] : this.defaultStyle;

		return msgObj;
	},
	save: function(msgObj) {
		var inStorageMessages = storage.getItem(this.storageKey,[]);

		inStorageMessages.push(msgObj);

		storage.setItem(this.storageKey,inStorageMessages);

		return this;
	},
	/* "Internal" Functions */
	_show: function(msgObj) {
		var parent = this;
		var noticeItemOuter, noticeItemInner, noticeItemClose;

		noticeItemOuter	= jQuery('<div></div>').addClass('notice-item-wrapper');
		noticeItemInner	= jQuery('<div></div>').hide().addClass('notice-item alert alert-' + msgObj.style).attr('data-dismiss','alert').appendTo(this.noticeWrapAll).html(msgObj.msg).animate({opacity: 'show'}, 600).wrap(noticeItemOuter);

		noticeItemClose	= jQuery('<div></div>').addClass('close').prependTo(noticeItemInner).html('&times;').click(function(event) {
			event.stopPropagation();
			parent._remove(noticeItemInner);
		});

		/* if it's NOT in the stay array set the timer to hide it */
		if (this.stay.indexOf(msgObj.style) === -1) {
			setTimeout(function() {
				parent._remove(noticeItemInner);
			}, this.stayTime  * 1000 /* convert to milliseconds */ );
		}

		return this;
	},
	_remove: function(obj) {
		obj.animate({opacity: '0'}, 600, function() {
			obj.parent().animate({height: '0px'}, 300, function() {
				obj.parent().remove();
			});
		});

		return this;
	},
	_asArray: function(messages) {
		for (var index in messages) {
			if (messages.hasOwnProperty(index)) {
				this._show(messages[index]);
			}
		}

		return this;
	},
	_autoDetect: function(msg,style,redirect) {
		return (redirect) ? this.add(msg,style,redirect) : this.show(msg,style);
	},
};

notify.init();