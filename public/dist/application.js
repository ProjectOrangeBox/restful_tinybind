var storage={storage:void 0,config:{dbPrefix:"{superStorge}",storage:"localStorage",defaultSecondCache:31622400},getItem:function(t,e){var n=this._getByComplete(this.config.dbPrefix+t);return Math.floor((new Date).getTime()/1e3)>n.expires&&(this.removeItem(t),n=this._emptyRecord()),n.data?n.data:e},removeItem:function(t,e){this.capable()&&(t=e?t:this.config.dbPrefix+t,console.debug("removing",t),this.storage.removeItem(t))},clear:function(){this.removeOlderThan()},getDetailed:function(t){return this._getByComplete(this.config.dbPrefix+t)},removeOlderThan:function(t){var e=Object.keys(this.storage),n=e.length,i=Math.floor((new Date).getTime()/1e3);t=t||0;for(var r=0;r<n;r++){var o=e[r];if(o.startsWith(this.config.dbPrefix))this._getByComplete(o).created+t<i&&this.removeItem(o,!0)}},setItem:function(t,e,n){if(this.capable()){var i=Math.floor((new Date).getTime()/1e3);n=void 0===n?this.config.defaultSecondCache:parseInt(n);try{var r=this.config.dbPrefix+t;return this.storage.setItem(r,JSON.stringify({data:e,created:i,expires:i+n,life:n})),!0}catch(i){return this.removeItem(this._findOldest(),!0),this.setItem(t,e,n),!1}}},_getByComplete:function(t){var e=this._emptyRecord();if(this.capable()){console.debug("reading",t);var n=this.storage[t];e=n?JSON.parse(n):e}return e},_emptyRecord:function(){return{expires:-1,created:-1,data:void 0}},_findOldest:function(){for(var t=Object.keys(this.storage),e=t.length,n=0,i=0,r=0;r<e;r++){var o=t[r];if(o.startsWith(this.config.dbPrefix)){var a=this._getByComplete(o);(a.created<i||0===i)&&(n=o,i=a.created)}}return n},capable:function(t){if(void 0===this.storage){t=t||this.config.storage;try{this.storage=window[t];var e="__storage_test__";this.storage.setItem(e,e),this.storage.removeItem(e)}catch(t){this.storage=void 0}}return void 0!==this.storage}},messages=messages||[],notify={stayTime:3,storageKey:"notifyMsg",defaultMsg:"No Message Giving.",defaultStyle:"info",noticeWrapAll:void 0,map:{red:"danger",yellow:"warning",blue:"info",green:"success",danger:"danger",warning:"warning",info:"info",success:"info",error:"danger",failure:"danger"},stay:["danger"],init:function(){return this.noticeWrapAll||(this.noticeWrapAll=jQuery("<div></div>").addClass("notice-wrap").appendTo("body")),this.showAll()},showAll:function(){return this.loadFromStorage().loadFromVariable()},info:function(t,e){return this._autoDetect(t,this.map.info,e)},success:function(t,e){return this._autoDetect(t,this.map.success,e)},error:function(t,e){return this._autoDetect(t,this.map.error,e)},show:function(t,e){return this._show(this.msgObj(t,e))},add:function(t,e,n){return this.save(this.msgObj(t,e)).redirect(n)},redirect:function(t){return t&&("@back"===t?window.history.back():window.location.href=t),this},removeAll:function(){return jQuery(".notice-item-wrapper").each(function(){$(this).remove()}),this},flush:function(){return messages=[],storage.removeItem(this.storageKey),this},loadFromStorage:function(){var t=storage.getItem(this.storageKey,!1);return storage.removeItem(this.storageKey),this._asArray(t)},loadFromVariable:function(){var t=messages;return messages=[],this._asArray(t)},msgObj:function(t,e){var n={};return n.msg=t||this.defaultMsg,n.style=this.map.hasOwnProperty(e)?this.map[e]:this.defaultStyle,n},save:function(t){var e=storage.getItem(this.storageKey,[]);return e.push(t),storage.setItem(this.storageKey,e),this},_show:function(t){var e,n,i=this;return e=jQuery("<div></div>").addClass("notice-item-wrapper"),n=jQuery("<div></div>").hide().addClass("notice-item alert alert-"+t.style).attr("data-dismiss","alert").appendTo(this.noticeWrapAll).html(t.msg).animate({opacity:"show"},600).wrap(e),jQuery("<div></div>").addClass("close").prependTo(n).html("&times;").click(function(t){t.stopPropagation(),i._remove(n)}),-1===this.stay.indexOf(t.style)&&setTimeout(function(){i._remove(n)},1e3*this.stayTime),this},_remove:function(t){return t.animate({opacity:"0"},600,function(){t.parent().animate({height:"0px"},300,function(){t.parent().remove()})}),this},_asArray:function(t){for(var e in t)t.hasOwnProperty(e)&&this._show(t[e]);return this},_autoDetect:function(t,e,n){return n?this.add(t,e,n):this.show(t,e)}};notify.init();var BoundTableSearch={storageKey:".search",bound:!1,fieldSelector:"#bound-table-search-field",tableSelector:"table.bound-table-search",countSelector:"#table-search-field-count",highlightColor:"#F0F2F7",highlightIcon:"text-info",triggerOnSearch:"BoundTableSearch",element:{},init:function(){if(!this.bound){var t=this;this.element.table=this.tableSelector+" tbody tr",this.element.field=$(this.fieldSelector),this.element.field.on("keyup",debounce(function(){t.search()},500)),this.load(),this.search(),this.bound=!0}},uninit:function(){this.bound=!1},search:function(){var t=this.getField();if("string"==typeof t){if(t.length>0){$(this.element.table).hide();var e=t.replace(/[-[\]{}()+?.,\\^$|#\s]/g,"\\$&");e=e.replace(/\*/gi,"(.*)"),console.debug("filter regular expression "+e);var n=new RegExp(e,"img");$(this.element.table).filter(function(){return n.test($(this).text().replace(/(\r\n|\n|\r)/gm," "))}).show()}else $(this.element.table).show();jQuery("body").trigger(this.triggerOnSearch,t),this.save(t),this.determineIcons(t),this.updateCount(t)}},updateCount:function(t){var e=$(this.element.table+":visible").length,n=$(this.element.table).length,i=e!==n?e+" of "+n:n;$(this.countSelector).html(i),console.debug('bound table search filtering on "'+t+'" showing '+i)},load:function(){var t=storage.getItem(window.location.pathname+this.storageKey,{});"string"==typeof t.search&&this.setField(t.search)},save:function(t){storage.setItem(window.location.pathname+this.storageKey,{search:t})},setField:function(t){this.element.field.val(t)},getField:function(){return!!this.element.field&&this.element.field.val()},determineIcons:function(t){t.length>0?(this.element.field.css({"background-color":this.highlightColor}),this.element.field.next().addClass(this.highlightIcon)):(this.element.field.css({"background-color":""}),this.element.field.next().removeClass(this.highlightIcon))}};$("body").on("tiny-bind-bound",function(){BoundTableSearch.init()}),$("body").on("tiny-bind-unbound",function(){BoundTableSearch.uninit()}),$(window).scroll(function(){storage.setItem(window.location.pathname+".scroll_pos",$(window).scrollTop())}),$("body").on("tiny-bind-bound",function(){var t=storage.getItem(window.location.pathname+".scroll_pos",null);t>0&&$(window).scrollTop(t)});var tableSort={iconsAdded:!1,storageKey:".sort",bound:!1,has:!1,class:"table.table-sort",triggerOnSort:"tableSort",dir:void 0,index:void 0,init:function(){if(!this.bound){var t=this;this.addSortIcons(),$(this.class+" thead tr th:not(.nosort)").on("click",function(){t.dir="asc"===t.dir?"desc":"asc",t.index=$(t.class+" thead tr th").index(this)+1,t.sort(t.index,t.dir)}),this.bound=!0,this.load()}},uninit:function(){this.bound=!1,this.iconsAdded=!1},sort:function(t,e){t>0&&(console.debug("Sorting column:"+t+" direction:"+e+" sorting:"+$(this.class+" tbody tr").length),this.determineIcons(t,e),$(this.class+" tbody tr").length&&(tinysort(this.class+" tbody tr",{selector:"td:nth-child("+t+")",order:e,data:"value"},{selector:"td:nth-child("+t+")",order:e}),jQuery("body").trigger(this.triggerOnSort,"sort"))),this.save(t,e)},addSortIcons:function(){this.iconsAdded||($(this.class+" thead tr th:not(.nosort)").prepend('<i class="fa fa-sort"></i> '),this.iconsAdded=!0)},determineIcons:function(t,e){$(this.class+" thead tr th i").removeClass("fa-sort-asc").removeClass("fa-sort-desc").addClass("fa-sort"),$(this.class+" thead tr th").removeClass("active"),$(this.class+" thead tr th:nth-child("+t+") i").addClass("fa-sort-"+e).removeClass("fa-sort"),$(this.class+" thead tr th:nth-child("+t+")").addClass("active")},load:function(){if(this.exists()){var t=storage.getItem(this.getKey(),{});this.index=t.index,this.dir=t.dir,this.sort(this.index,this.dir)}},save:function(t,e){this.exists()&&storage.setItem(this.getKey(),{index:t,dir:e})},getKey:function(){return window.location.pathname+this.storageKey},exists:function(){return $(this.class+" thead tr th:not(.nosort)").length>0}};function debounce(t,e,n){var i;return function(){var r=this,o=arguments,a=n&&!i;clearTimeout(i),i=setTimeout(function(){i=null,n||t.apply(r,o)},e),a&&t.apply(r,o)}}$("body").on("tiny-bind-bound",function(){tableSort.init()}),$("body").on("tiny-bind-unbound",function(){tableSort.uninit()}),function(t,e,n){"use strict";var i="stickyTableHeaders",r=0,o={fixedOffset:0,leftOffset:0,marginTop:0,objDocument:document,objHead:"head",objWindow:e,scrollableArea:e,cacheHeaderHeight:!1,zIndex:3};function a(n,a){var s=this;s.$el=t(n),s.el=n,s.id=r++,s.$el.bind("destroyed",t.proxy(s.teardown,s)),s.$clonedHeader=null,s.$originalHeader=null,s.cachedHeaderHeight=null,s.isSticky=!1,s.hasBeenSticky=!1,s.leftOffset=null,s.topOffset=null,s.init=function(){s.setOptions(a),s.$el.each(function(){var e=t(this);e.css("padding",0),s.$originalHeader=t("thead:first",this),s.$clonedHeader=s.$originalHeader.clone(),e.trigger("clonedHeader."+i,[s.$clonedHeader]),s.$clonedHeader.addClass("tableFloatingHeader"),s.$clonedHeader.css({display:"none",opacity:0}),s.$originalHeader.addClass("tableFloatingHeaderOriginal"),s.$originalHeader.after(s.$clonedHeader),s.$printStyle=t('<style type="text/css" media="print">.tableFloatingHeader{display:none !important;}.tableFloatingHeaderOriginal{position:static !important;}</style>'),s.$head.append(s.$printStyle)}),s.$clonedHeader.find("input, select").attr("disabled",!0),s.updateWidth(),s.toggleHeaders(),s.bind()},s.destroy=function(){s.$el.unbind("destroyed",s.teardown),s.teardown()},s.teardown=function(){s.isSticky&&s.$originalHeader.css("position","static"),t.removeData(s.el,"plugin_"+i),s.unbind(),s.$clonedHeader.remove(),s.$originalHeader.removeClass("tableFloatingHeaderOriginal"),s.$originalHeader.css("visibility","visible"),s.$printStyle.remove(),s.el=null,s.$el=null},s.bind=function(){s.$scrollableArea.on("scroll."+i,s.toggleHeaders),s.isWindowScrolling||(s.$window.on("scroll."+i+s.id,s.setPositionValues),s.$window.on("resize."+i+s.id,s.toggleHeaders)),s.$scrollableArea.on("resize."+i,s.toggleHeaders),s.$scrollableArea.on("resize."+i,s.updateWidth)},s.unbind=function(){s.$scrollableArea.off("."+i,s.toggleHeaders),s.isWindowScrolling||(s.$window.off("."+i+s.id,s.setPositionValues),s.$window.off("."+i+s.id,s.toggleHeaders)),s.$scrollableArea.off("."+i,s.updateWidth)},s.debounce=function(t,e){var n=null;return function(){var i=this,r=arguments;clearTimeout(n),n=setTimeout(function(){t.apply(i,r)},e)}},s.toggleHeaders=s.debounce(function(){s.$el&&s.$el.each(function(){var e,n,r,o=t(this),a=s.isWindowScrolling?isNaN(s.options.fixedOffset)?s.options.fixedOffset.outerHeight():s.options.fixedOffset:s.$scrollableArea.offset().top+(isNaN(s.options.fixedOffset)?0:s.options.fixedOffset),d=o.offset(),l=s.$scrollableArea.scrollTop()+a,c=s.$scrollableArea.scrollLeft(),u=s.isWindowScrolling?l>d.top:a>d.top;u&&(n=s.options.cacheHeaderHeight?s.cachedHeaderHeight:s.$clonedHeader.height(),r=(s.isWindowScrolling?l:0)<d.top+o.height()-n-(s.isWindowScrolling?0:a)),u&&r?(e=d.left-c+s.options.leftOffset,s.$originalHeader.css({position:"fixed","margin-top":s.options.marginTop,top:0,left:e,"z-index":s.options.zIndex}),s.leftOffset=e,s.topOffset=a,s.$clonedHeader.css("display",""),s.isSticky||(s.isSticky=!0,s.updateWidth(),o.trigger("enabledStickiness."+i)),s.setPositionValues()):s.isSticky&&(s.$originalHeader.css("position","static"),s.$clonedHeader.css("display","none"),s.isSticky=!1,s.resetWidth(t("td,th",s.$clonedHeader),t("td,th",s.$originalHeader)),o.trigger("disabledStickiness."+i))})},0),s.setPositionValues=s.debounce(function(){var t=s.$window.scrollTop(),e=s.$window.scrollLeft();!s.isSticky||t<0||t+s.$window.height()>s.$document.height()||e<0||e+s.$window.width()>s.$document.width()||s.$originalHeader.css({top:s.topOffset-(s.isWindowScrolling?0:t),left:s.leftOffset-(s.isWindowScrolling?0:e)})},0),s.updateWidth=s.debounce(function(){if(s.isSticky){s.$originalHeaderCells||(s.$originalHeaderCells=t("th,td",s.$originalHeader)),s.$clonedHeaderCells||(s.$clonedHeaderCells=t("th,td",s.$clonedHeader));var e=s.getWidth(s.$clonedHeaderCells);s.setWidth(e,s.$clonedHeaderCells,s.$originalHeaderCells),s.$originalHeader.css("width",s.$clonedHeader.width()),s.options.cacheHeaderHeight&&(s.cachedHeaderHeight=s.$clonedHeader.height())}},0),s.getWidth=function(n){var i=[];return n.each(function(n){var r,o=t(this);if("border-box"===o.css("box-sizing")){var a=o[0].getBoundingClientRect();r=a.width?a.width:a.right-a.left}else{if("collapse"===t("th",s.$originalHeader).css("border-collapse"))if(e.getComputedStyle)r=parseFloat(e.getComputedStyle(this,null).width);else{var d=parseFloat(o.css("padding-left")),l=parseFloat(o.css("padding-right")),c=parseFloat(o.css("border-width"));r=o.outerWidth()-d-l-c}else r=o.width()}i[n]=r}),i},s.setWidth=function(t,e,n){e.each(function(e){var i=t[e];n.eq(e).css({"min-width":i,"max-width":i})})},s.resetWidth=function(e,n){e.each(function(e){var i=t(this);n.eq(e).css({"min-width":i.css("min-width"),"max-width":i.css("max-width")})})},s.setOptions=function(e){s.options=t.extend({},o,e),s.$window=t(s.options.objWindow),s.$head=t(s.options.objHead),s.$document=t(s.options.objDocument),s.$scrollableArea=t(s.options.scrollableArea),s.isWindowScrolling=s.$scrollableArea[0]===s.$window[0]},s.updateOptions=function(t){s.setOptions(t),s.unbind(),s.bind(),s.updateWidth(),s.toggleHeaders()},s.init()}t.fn[i]=function(e){return this.each(function(){var n=t.data(this,"plugin_"+i);n?"string"==typeof e?n[e].apply(n):n.updateOptions(e):"destroy"!==e&&t.data(this,"plugin_"+i,new a(this,e))})}}(jQuery,window),$("body").on("tiny-bind-bound",function(){$(".table-sticky-header").stickyTableHeaders({fixedOffset:$(".page-header.navbar.navbar-fixed-top")})}),$(document).on("click","[appNavigate]",function(){app.router.navigate($(this).attr("appnavigate"))}),$(document).on("tiny-bind-bound",function(){$("select").selectpicker()}),tinybind.stdlib={defaultPrecision:2,defaultThousandSeparator:"'",defaultDecimalSeparator:".",defaultDateFormat:"YYYY-MM-DD",defaultTimeFormat:"HH:mm:ss",defaultDatetimeFormat:"YYYY-MM-DD HH:mm:ss"},tinybind.formatters.log=function(t){return console.log(t)},tinybind.formatters.default=function(t,e){return tinybind.formatters.isEmpty(t)?e:t},tinybind.formatters.add=function(t,e){return t+e},tinybind.formatters.sub=function(t,e){return t-e},tinybind.formatters.map=function(t,e,n){var i=Array.prototype.slice.call(arguments);return i.splice(1,2),e[n].apply(e,i)},tinybind.formatters.isBoolean=function(t){return"boolean"==typeof t},tinybind.formatters.isNumeric=function(t){return!isNaN(t)},tinybind.formatters.isNaN=function(t){return!!tinybind.formatters.isArray(t)||isNaN(t)},tinybind.formatters.isInteger=function(t){return t===+t&&t===(0|t)},tinybind.formatters.isFloat=function(t){return 1/0!==t&&t===+t&&t!==(0|t)},tinybind.formatters.isNumber=function(t){return tinybind.formatters.isFloat(t)||tinybind.formatters.isInteger(t)},tinybind.formatters.isObject=function(t){return tinybind.formatters.toBoolean(t)&&"object"==typeof t&&!tinybind.formatters.isArray(t)},tinybind.formatters.isFunction=function(t){return"function"==typeof t},tinybind.formatters.isArray=function(t){return tinybind.formatters.isFunction(Array.isArray)?Array.isArray(t):t instanceof Array},tinybind.formatters.isString=function(t){return"string"==typeof t||t instanceof String},tinybind.formatters.isInfinity=function(t){return t===1/0},tinybind.formatters.toBoolean=function(t){return!!t},tinybind.formatters.toInteger=function(t){var e=parseInt(1*t,10);return isNaN(e)?0:e},tinybind.formatters.toFloat=function(t){var e=parseFloat(1*t);return isNaN(e)?0:e},tinybind.formatters.toDecimal=function(t){var e=tinybind.formatters.toInteger(1*t),n=tinybind.formatters.toFloat(t);return e===n?e:n},tinybind.formatters.toArray=function(t){return tinybind.formatters.isArray(t)?t:tinybind.formatters.isObject(t)?tinybind.formatters.values(t):[t]},tinybind.formatters.toString=function(t){return t?t.toString():""},tinybind.formatters.valuesToString=function(t){return Array.isArray(t)&&t.forEach(function(e,n){t[n]=e.toString()}),t},tinybind.formatters.integer={read:function(t){return tinybind.formatters.toInteger(t)},publish:function(t){return tinybind.formatters.toInteger(t)}},tinybind.formatters.sum=function(t,e){return 1*t+1*e},tinybind.formatters.substract=function(t,e){return 1*t-1*e},tinybind.formatters.multiply=function(t,e){return 1*t*(1*e)},tinybind.formatters.divide=function(t,e){return 1*t/(1*e)},tinybind.formatters.min=function(){return Math.min.apply(Math,arguments)},tinybind.formatters.max=function(){return Math.max.apply(Math,arguments)},tinybind.formatters.isEqual=function(t,e){return t===e},tinybind.formatters.isNotEqual=function(t,e){return t!==e},tinybind.formatters.isLess=function(t,e){return 1*t<1*e},tinybind.formatters.isGreater=function(t,e){return 1*t>1*e},tinybind.formatters.isLessEqual=function(t,e){return 1*t<=1*e},tinybind.formatters.isGreaterEqual=function(t,e){return 1*t>=1*e},tinybind.formatters.or=function(){for(var t=0;t<arguments.length;t++)if(tinybind.formatters.toBoolean(arguments[t]))return!0;return!1},tinybind.formatters.and=function(){for(var t=0;t<arguments.length;t++)if(!tinybind.formatters.toBoolean(arguments[t]))return!1;return!0},tinybind.formatters.negate=function(t){return!tinybind.formatters.toBoolean(t)},tinybind.formatters.if=function(t,e,n){return tinybind.formatters.toBoolean(t)?e:n},tinybind.formatters.numberFormat=function(t,e,n,i){t=tinybind.formatters.isNumber(t)?t:tinybind.formatters.toDecimal(t),tinybind.formatters.isInteger(e)||(e=tinybind.stdlib.defaultPrecision),n||(n=tinybind.stdlib.defaultDecimalSeparator),i||(i=tinybind.stdlib.defaultThousandSeparator);var r=(+(Math.round(+(Math.abs(t)+"e"+e))+"e"+-e)).toFixed(e);return t<0&&(r="-"+r),2===(r=r.split(".")).length?r[0].replace(/\B(?=(\d{3})+(?!\d))/g,i)+n+r[1]:r[0].replace(/\B(?=(\d{3})+(?!\d))/g,i)},tinybind.formatters.date=function(t){return moment(t).format(tinybind.stdlib.defaultDateFormat)},tinybind.formatters.time=function(t){return moment(t).format(tinybind.stdlib.defaultTimeFormat)},tinybind.formatters.datetime=function(t){return moment(t).format(tinybind.stdlib.defaultDatetimeFormat)},tinybind.formatters.toTimestamp=function(t){return moment(t).format("X")},tinybind.formatters.toDate=function(t){return moment.unix(t).toDate()},tinybind.formatters.toMoment=function(t){return moment(t)},tinybind.formatters.dateFormat=function(t,e){return moment(t).format(e)},tinybind.formatters.pairs=function(t){return Object.keys(t).map(function(e){return{object:t,property:e,value:t[e]}})},tinybind.formatters.catalog=function(t,e,n,i){n=n||"id",i=i||"value";for(const r in e)if(e[r][n]==t)return e[r][i];return""},tinybind.formatters.keys=function(t){return Object.keys(t)},tinybind.formatters.values=function(t){return Object.keys(t).map(function(e){return t[e]})},tinybind.formatters.stringFormat=function(t){for(var e=1;e<arguments.length;e++){var n=t.indexOf("%s");if(-1===n)break;t=t.slice(0,n)+arguments[e]+t.slice(n+2)}return t},tinybind.formatters.split=function(t,e){return t.split(e)},tinybind.formatters.lower=function(t){return t.toLowerCase()},tinybind.formatters.upper=function(t){return t.toUpperCase()},tinybind.formatters.capitalize=function(t){return(t=tinybind.formatters.toString(t)).split(" ").map(function(t){return t.split("-").map(function(t){return t.charAt(0).toUpperCase()+t.slice(1)}).join("-")}).join(" ")},tinybind.formatters.contains=function(t,e){return-1!==t.indexOf(e)},tinybind.formatters.doesNotContain=function(t,e){return tinybind.formatters.negate(tinybind.formatters.contains(t,e))},tinybind.formatters.prettyPrint=function(t){return JSON.stringify(t,null,2)},tinybind.formatters.isEmpty=function(t){return!tinybind.formatters.toBoolean(t)||0===tinybind.formatters.toArray(t).length},tinybind.formatters.length=function(t){return tinybind.formatters.isString(t)?t.length:tinybind.formatters.toArray(t).length},tinybind.formatters.join=function(t,e){return tinybind.formatters.toArray(t).join(e)},tinybind.formatters.wrap=function(t){var e=Array.prototype.slice.call(arguments);return e.splice(0,1),function(n){var i=e.slice();return Array.prototype.push.apply(i,Array.prototype.slice.call(arguments)),t.apply(this,i)}},tinybind.formatters.delay=function(t,e){var n=this;return function(){setTimeout(function(){t.apply(n,arguments)},e)}},tinybind.formatters.preventDefault=function(t){var e=this;return function(n){return n.preventDefault(),t.call(e,n),!1}},tinybind.binders.width=function(t,e){t.style.width=e},tinybind.binders.height=function(t,e){t.style.height=e},tinybind.binders.intcheck={publishes:!0,priority:2e3,bind:function(t){var e=this;this.callback||(this.callback=function(){e.publish()}),t.addEventListener("change",this.callback)},unbind:function(t){t.removeEventListener("change",this.callback)},routine:function(t,e){t.checked=t.value===e},getValue:function(t){return t.checked?t.value:0}},tinybind.formatters.enum=function(t,e){return arguments[parseInt(t)+1]},tinybind.formatters.eq=tinybind.formatters.isEqual,tinybind.formatters.ne=function(t,e){return tinybind.formatters.negate(tinybind.formatters.isEqual(t,e))},tinybind.formatters.lt=tinybind.formatters.isLess,tinybind.formatters.gt=tinybind.formatters.isGreater,tinybind.formatters.le=tinybind.formatters.isLessEqual,tinybind.formatters.lte=tinybind.formatters.isLessEqual,tinybind.formatters.ge=tinybind.formatters.isGreaterEqual,tinybind.formatters.gte=tinybind.formatters.isGreaterEqual,tinybind.formatters.prv=tinybind.formatters.preventDefault,tinybind.formatters.inject=tinybind.formatters.stringFormat,tinybind.formatters.sprintf=tinybind.formatters.stringFormat,tinybind.formatters.format=tinybind.formatters.dateFormat,tinybind.formatters.len=tinybind.formatters.length,tinybind.formatters.def=tinybind.formatters.default,tinybind.formatters.neg=tinybind.formatters.negate,tinybind.formatters.date=tinybind.formatters.dateFormat,tinybind.formatters.stringify=tinybind.formatters.prettyPrint,tinybind.formatters.int=tinybind.formatters.integer,tinybind.formatters.isLower=tinybind.formatters.isLess,tinybind.formatters.isLowerEqual=tinybind.formatters.isLessEqual;var app={id:"app",config:{url:"/",ajaxTimeout:5e3,routerRoot:"/",storageCache:2592e3,templateCache:0,clearCache:!1,ajaxCacheBuster:!1,tinyBind:{prefix:"rv",preloadData:!0,rootInterface:".",templateDelimiters:["{","}"]}},bound:void 0,local:{},error:!1,errors:{},model:{},record:{},records:[],page:{},form:{},events:{},triggers:{bound:function(){jQuery("body").trigger("tiny-bind-bound")},unbound:function(){jQuery("body").trigger("tiny-bind-unbound")}},init(){var t=this;this.response.change(200,function(e,n){t.setData(e),tinybind.configure(t.config.tinyBind),t.router.remove("foobar"),t.router.check()}),this.request.get(this.config.url)},event:{add:function(t,e){return app.events[t]=e,this}},router:{routes:[],interval:void 0,listening:void 0,check:function(t){t=t||this.getUrl(),console.info("looking for route "+t),this.listening||(this.listening=this.listen());for(var e=0;e<this.routes.length;e++){var n=t.match(this.routes[e].re);if(n){n.shift(),console.info("found match of "+this.routes[e].re.toString()),this.routes[e].handler.apply({},n);break}}return this},getUrl:function(){var t="";return t=(t=this._clearSlashes(decodeURI(location.pathname+location.search))).replace(/\?(.*)$/,""),t="/"!==app.config.routerRoot?t.replace(app.config.routerRoot,""):t,this._clearSlashes(t)},add:function(t,e){return"function"==typeof t&&(e=t,t=""),this.routes.push({re:t,handler:e}),this},remove:function(t){var e=this;return this.routes.forEach(function(n,i){n.handler!==t&&n.re.toString()!==t.toString()||e.routes.splice(i,1)}),this},flush:function(){return this.routes=[],this},listen:function(){var t=this,e=this.getUrl();return clearInterval(this.interval),this.interval=setInterval(function(){e!==t.getUrl()&&(e=t.getUrl(),t.check(e))},50),this},navigate:function(t){return t=t?app.config.routerRoot+this._clearSlashes(t):"",console.info("navigate to "+t),history.pushState(null,null,t),this},_clearSlashes:function(t){return t.toString().replace(/\/$/,"").replace(/^\//,"")}},response:{_handlers:{200:function(t,e,n){console.log(arguments),alert("200 (ok) handler")},201:function(t,e,n){console.log(arguments),alert("201 (created) handler")},202:function(t,e,n){console.log(arguments),alert("202 (accepted) handler")},401:function(t,e,n){console.log(arguments),alert("401 (unauthorized) handler")},404:function(t,e,n){console.log(arguments),alert("404 (not found) handler")},406:function(t,e,n){console.log(arguments),alert("406 (not accepted) handler")},409:function(t,e,n){console.log(arguments),alert("409 (conflict) handler")},500:function(t,e,n){console.log(arguments),alert("500 (server error) handler")}},change:function(t,e){return this._handlers[t]=e,this},add:function(t,e){return this.change(t,e)},handlers:function(t){return jQuery.extend(this._handlers,t)}},request:{send:function(t,e,n,i){return console.info(t,e,n),jQuery.ajax({method:t,url:e,data:n,dataType:"json",cache:!app.config.ajaxCacheBuster,async:!0,timeout:app.config.ajaxTimeout,statusCode:app.response.handlers(i)}),this},get:function(t,e,n){return this.send("get",t,e,n)},post:function(t,e,n){return this.send("post",t,e,n)},patch:function(t,e,n){return this.send("patch",t,e,n)},delete:function(t,e,n){return this.send("delete",t,e,n)},create:function(t,e,n){return this.send("post",t,e,n)},read:function(t,e,n){return this.send("get",t,e,n)},update:function(t,e,n){return this.send("patch",t,e,n)},insert:function(t,e,n){return this.send("post",t,e,n)}},setData:function(t){var e=this;return console.info(t),["error","errors","model"].forEach(function(n){t[n]&&(e[n]=t[n])}),["page","form","config"].forEach(function(n){t[n]&&(e[n]=jQuery.extend(!0,e[n],t[n]))}),this.records=this.model,this.record=this.model,this.cacheCleanUp(this.config),this},getData:function(){return{error:this.error,errors:this.errors,model:this.model,page:this.page,form:this.form}},cacheCleanUp:function(t){return t.clearCache&&storage.clear(),void 0!==t.olderThanCache&&storage.removeOlderThan(t.olderThanCache),this},loadModel:function(t,e){var n=this;return e?this.loadTemplate(e,function(){n._loadModel(t)}):this._loadModel(t),this},loadTemplate:function(t,e){var n=this,i=t+".template",r=storage.getItem(i,void 0);return r?(document.getElementById(this.id).innerHTML=r,e&&e()):(this.response.change(200,function(t,r,o){var a=t.template.cache?t.template.cache:n.config.templateCache;storage.setItem(i,t.template.source,a),document.getElementById(n.id).innerHTML=t.template.source,e&&e()}),n.request.get(t)),this},_loadModel:function(t,e){var n=this;return this.response.change(200,function(t,i,r){n.triggers.unbound(),n.bound&&n.bound.unbind(),n.setData(t),n.bound=tinybind.bind(document.getElementById(n.id),app),n.triggers.bound(),e&&e()}),n.request.get(t),this}};app.config.url="/layout/configuration",app.router.add(/people\/edit\/(.*)/,function(t){app.loadModel("/people/edit/"+t,"/layout/get/people/details")}).add(/people\/create/,function(){app.loadModel("/people/create","/layout/get/people/details")}).add(/people/,function(){app.loadModel("/people/index","/layout/get/people/index")}).add(/catalog\/edit\/(.*)/,function(t){app.loadModel("/catalog/edit/"+t,"/layout/get/catalog/details")}).add(/catalog\/create/,function(){app.loadModel("/catalog/create","/layout/get/catalog/details")}).add(/catalog/,function(){app.loadModel("/catalog/index","/layout/get/catalog/index")}).add(/robot\/edit\/(.*)/,function(t){app.loadModel("/robot/edit/"+t,"/layout/get/robot/details")}).add(/robot\/create/,function(){app.loadModel("/robot/create","/layout/get/robot/details")}).add(function(){app.loadModel("/robot/index","/layout/get/robot/index")}),app.response.change(404,function(t,e,n){app.loadTemplate("/layout/get/notfound")}),app.event.add("goto",function(t,e){e.preventDefault(),notify.removeAll(),app.router.navigate(t)}).add("create",function(t,e){e.preventDefault(),app.router.navigate(t+"/create")}).add("edit",function(t,e,n){n.preventDefault(),app.router.navigate(t+"/edit/"+e)}).add("delete",function(t,e,n){n.preventDefault(),app.local.closest_tr=jQuery(this).closest("tr"),bootbox.confirm({message:"Are you sure you want to delete this record?",buttons:{cancel:{label:'<i class="fa fa-times"></i> Cancel'},confirm:{label:'<i class="fa fa-trash"></i> Delete',className:"btn-danger"}},callback:function(n){n&&(app.response.change(202,function(t,e,n){app.local.closest_tr.remove()}),app.request.delete(t+"/delete/"+e))}})}).add("submit",function(t){t.preventDefault(),app.response.change(201,function(t,e,n){notify.removeAll(),app.router.navigate(app.page.path)}),app.response.change(202,function(t,e,n){notify.removeAll(),app.router.navigate(app.page.path)}),app.response.change(406,function(t,e,n){if(app.setData(t.responseJSON),app.error){notify.removeAll();for(const t in app.errors)for(const e in app.errors[t])notify.error(app.errors[t][e])}}),app.request[app.form.method](app.form.action,app.getData())}),document.addEventListener("DOMContentLoaded",function(t){app.init()});
//# sourceMappingURL=application.js.map