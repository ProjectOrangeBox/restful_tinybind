var notify = {};

/**
 * Add a notice
 * notify.add('text',"[success|danger|warning|info]",(optional redirect)'/foo/bar);
 *
 * Remove all of the notices on the screen
 * notify.removeAll();
 *
 * Raw add msg
 * notify.show({text:'text message',style:"[success|danger|warning|info]",stay: [true|false]});
 *
 */

notify.addInfo = function(text,redirect) {
	notify.add(text,'info',redirect);
};

notify.addSuccess = function(text,redirect) {
	notify.add(text,'success',redirect);
};

notify.addError = function(text,redirect) {
	notify.add(text,'danger',redirect);
};

notify.add = function(text,style,redirect) {
	var msg = notify.buildMsg(text,style);

	if (redirect == undefined) {
		notify.show(msg);
	} else {
		notify.save(msg);

		/* special @ redirects? we only have 1 right now */
		switch(redirect) {
			case '@back':
				window.history.back();
			break;
			default:
				window.location.href = redirect;
		}
	}
};

notify.removeAll = function() {
	jQuery('.notice-item-wrapper').each(function(){
		$(this).remove();
	});
};

notify.show = function(msg) {
	var noticeWrapAll, noticeItemOuter, noticeItemInner, noticeItemClose;

	noticeWrapAll	= (!jQuery('.notice-wrap').length) ? jQuery('<div></div>').addClass('notice-wrap').appendTo('body') : jQuery('.notice-wrap');
	noticeItemOuter	= jQuery('<div></div>').addClass('notice-item-wrapper');
	noticeItemInner	= jQuery('<div></div>').hide().addClass('notice-item alert alert-' + msg.style).attr('data-dismiss','alert').appendTo(noticeWrapAll).html(msg.text).animate({opacity: 'show'}, 600).wrap(noticeItemOuter);
	noticeItemClose	= jQuery('<div></div>').addClass('close').prependTo(noticeItemInner).html('&times;').click(function(e) {
		e.stopPropagation();
		notify._remove(noticeItemInner)
	});

	if (!msg.stay) {
		/* if they didn't include anything then use 4 seconds */
		msg.stayTime = (msg.stayTime != undefined) ? msg.stayTime : notify.stayTime;

		/* if they didn't use milliseconds adjust it */
		msg.stayTime = (msg.stayTime > 30) ? msg.stayTime : msg.stayTime * 1000;

		setTimeout(function() {
			notify._remove(noticeItemInner);
		}, msg.stayTime);
	}
};

notify.save = function(msg) {
	var message = $.jStorage.get('notify_flash_msg',[]);

	message.push(msg);

	$.jStorage.set('notify_flash_msg',message);
};

notify.init = function() {
	if (typeof messages !== 'undefined') {
		/* if they didn't include anything then use 4 seconds */
		notify.stayTime = (messages.pause != undefined) ? messages.pause : 3000;

		/* if they didn't use milliseconds adjust it */
		notify.stayTime = (notify.stayTime > 30) ? notify.stayTime : notify.stayTime * 1000;
	} else {
		notify.stayTime = 3000;
	}

	/* Any message in cold storage? */
	var saved_messages = notify._load();

	if (saved_messages) {
		saved_messages.forEach(function(message) {
			notify.add(message.text,message.style);
		});
	}

	/**
	 * Any messages attached to the
	 * javascript global variable message on the page?
	 * this is inserted into the page from the server code
	 *
	 * <script>var $messages = "[{text:'Foo',style:'success'},{text:'Bar',style:'danager'}]";</script>
	 */
	if (typeof messages !== 'undefined') {
		messages.forEach(function(message) {
			notify.add(message.msg,message.type);
		});
	}
};

/**
 * "Internal" Functions
 */
notify.buildMsg = function(text,style) {
	var map = {
		red: 'danger',
		yellow: 'warning',
		blue: 'info',
		green: 'success',
		danger: 'danger',
		warning: 'warning',
		info: 'info',
		success: 'info',
		error: 'danager',
		failure: 'danager'
	};

	var msg = {};

	msg.text = (text != undefined) ? text : 'No Message Giving.';
	msg.style = (style != undefined) ? map[style] : 'info';
	msg.stay = (msg.style == 'danger');

	return msg;
};

notify._load = function(msg) {
	var messages = $.jStorage.get('notify_flash_msg',false);

	$.jStorage.deleteKey('notify_flash_msg');

	return messages;
};

notify._remove = function(obj) {
	obj.animate({opacity: '0'}, 600, function() {
		obj.parent().animate({height: '0px'}, 300, function() {
			obj.parent().remove();
		});
	});
};

notify.init();
