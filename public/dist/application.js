var storage={_capable:void 0,settings:{dbPrefix:"db"},prefixRegex:function(){return new RegExp(this.settings.dbPrefix,"g")},modifiedRegex:function(){return new RegExp("modified","g")},capable:function(){if(null==this._capable){try{return localStorage.setItem("ls","ls"),localStorage.removeItem("ls"),this._capable=!0,!0}catch(t){return this._capable=!1,!1}}return this._capable},findOldest:function(){for(var t=0,e=0,n=Object.keys(localStorage),r=n.length,i=0;i<r;i++)if(this.prefixRegex().test(n[i])&&this.modifiedRegex().test(n[i])){var a=n[i].replace(this.settings.dbPrefix,"").replace("-modified",""),o=localStorage[this.settings.dbPrefix+a+"-modified"];(o<t||0===t)&&(e=a,t=o)}return e},getItem:function(t,e){var n=this.getDetailed(t);return n.expires>0&&Math.floor((new Date).getTime()/1e3)>n.expires&&(n.data=void 0,this.removeItem(t)),n.data?n.data:e},getDetailed:function(t){var e={};if(e.uID=t,!0===this.capable()){var n=this.settings.dbPrefix+t;e.data=localStorage[n+"-data"],e.created=localStorage[n+"-created"],e.modified=localStorage[n+"-modified"],e.expires=localStorage[n+"-expires"]}return e},removeItem:function(t){var e=this.settings.dbPrefix+t;!0===this.capable()&&(localStorage.removeItem(e+"-data"),localStorage.removeItem(e+"-created"),localStorage.removeItem(e+"-modified"),localStorage.removeItem(e+"-expires"))},clear:function(){!0===this.capable()&&localStorage.clear()},setItem:function(t,e,n){if(!0===this.capable()){var r=this.settings.dbPrefix+t,i=Math.floor((new Date).getTime()/1e3),a=n?Math.floor((new Date).getTime()/1e3)+n:-1;try{return localStorage.setItem(r+"-data",e),localStorage.setItem(r+"-modified",i),localStorage.setItem(r+"-expires",a),void 0===localStorage[r+"-created"]&&localStorage.setItem(r+"-created",i),!0}catch(r){return this.removeItem(t),this.removeItem(this.findOldest()),this.setItem(t,e,n),!1}}}},BoundTableSearch={search:function(){var t=BoundTableSearch.getField();if("string"==typeof t){if(t.length>0){$(BoundTableSearch.table_class).hide();var e=t.replace(/[-[\]{}()+?.,\\^$|#\s]/g,"\\$&");e=e.replace(/\*/gi,"(.*)"),console.log("filter regular expression "+e);var n=new RegExp(e,"img");$(BoundTableSearch.table_class).filter(function(){return n.test($(this).text().replace(/(\r\n|\n|\r)/gm," "))}).show()}else $(BoundTableSearch.table_class).show();jQuery("body").trigger("BoundTableSearch","search"),BoundTableSearch.save(t),BoundTableSearch.determineIcons(t),BoundTableSearch.updateCount(t)}},updateCount:function(t){var e=$(BoundTableSearch.table_class+":visible").length,n=$(BoundTableSearch.table_class).length,r=e!=n?e+" of "+n:n;BoundTableSearch.count_element.html(r),console.log('bound table search filtering on "'+t+'" showing '+r)},load:function(){BoundTableSearch.setField(storage.getItem(window.location.pathname+".bts",""))},save:function(t){storage.setItem(window.location.pathname+".bts",t)},setField:function(t){BoundTableSearch.field.val(t)},getField:function(){return!!BoundTableSearch.field&&BoundTableSearch.field.val()},determineIcons:function(t){t.length>0?(BoundTableSearch.field.css({"background-color":"#F0F2F7"}),BoundTableSearch.field.next().addClass("text-info")):(BoundTableSearch.field.css({"background-color":""}),BoundTableSearch.field.next().removeClass("text-info"))},init:function(){BoundTableSearch.table_class="table.bound-table-search tbody tr",BoundTableSearch.field=$("#bound-table-search-field"),BoundTableSearch.count_element=$("#table-search-field-count"),BoundTableSearch.tbody=$("table.bound-table-search tbody"),BoundTableSearch.field.on("keyup",debounce(function(){BoundTableSearch.search()},500)),BoundTableSearch.load(),BoundTableSearch.search(),BoundTableSearch.bound=!0}};$("body").on("bound",function(t,e){e?(BoundTableSearch.bound||BoundTableSearch.init(),BoundTableSearch.search()):BoundTableSearch.bound=!1});var orange=orange||{},messages=messages||[],plugins=plugins||[];plugins.flash_msg={},plugins.flash_msg.pause=3e3,function(t){t.extend({noticeAdd:function(e){var n,r,i;e.stayTime<15&&(e.stayTime=1e3*e.stayTime),e=t.extend({},{inEffect:{opacity:"show"},inEffectDuration:600,stayTime:3e3,text:"",stay:!1,type:"info"},e),n=t(".notice-wrap").length?t(".notice-wrap"):t("<div></div>").addClass("notice-wrap").appendTo("body"),r=t("<div></div>").addClass("notice-item-wrapper"),i=t("<div></div>").hide().addClass("notice-item alert alert-"+e.type).attr("data-dismiss","alert").appendTo(n).html(e.text).animate(e.inEffect,e.inEffectDuration).wrap(r),t("<div></div>").addClass("close").prependTo(i).html("&times;").click(function(e){e.stopPropagation(),t.noticeRemove(i)}),navigator.userAgent.match(/MSIE 6/i)&&n.css({top:document.documentElement.scrollTop}),e.stay||setTimeout(function(){t.noticeRemove(i)},e.stayTime)},noticeRemove:function(t){t.animate({opacity:"0"},600,function(){t.parent().animate({height:"0px"},300,function(){t.parent().remove()})})},noticeRemoveAll:function(){$(".notice-item-wrapper").each(function(t){$(this).remove()})},noticeReloadAdd:function(e){"danger"==(e=t.extend({},{stayTime:3e3,text:"",stay:!1,type:"info"},e)).type&&(e.stay=!0),storage.set("flash_msg",e)}})}(jQuery),orange.flash_msg=function(t,e,n){sticky="danger"==e||"warning"==e;var r={};r.text=t,r.type={red:"danger",yellow:"warning",blue:"info",green:"success",danger:"danger",warning:"warning",info:"info",success:"success"}[e],r.sticky=sticky,!1===n?$.noticeAdd(r):(storage.setItem("flash_msg",r),void 0!==n&&orange.redirect(n))};var flash_msg=storage.getItem("flash_msg",null);if(flash_msg&&($.noticeAdd(flash_msg),storage.removeItem("flash_msg")),messages)for(var i=0,len=messages.length;i<len;i++)$.noticeAdd(messages[i]);var notify={addInfo:function(t,e){notify.add(t,"info",e)},addSuccess:function(t,e){notify.add(t,"success",e)},addError:function(t,e){notify.add(t,"danger",e)},add:function(t,e,n){var r=notify.buildMsg(t,e);if(null==n)notify.show(r);else switch(notify.save(r),n){case"@back":window.history.back();break;default:window.location.href=n}},removeAll:function(){jQuery(".notice-item-wrapper").each(function(){$(this).remove()})},show:function(t){var e,n,r;e=jQuery(".notice-wrap").length?jQuery(".notice-wrap"):jQuery("<div></div>").addClass("notice-wrap").appendTo("body"),n=jQuery("<div></div>").addClass("notice-item-wrapper"),r=jQuery("<div></div>").hide().addClass("notice-item alert alert-"+t.style).attr("data-dismiss","alert").appendTo(e).html(t.text).animate({opacity:"show"},600).wrap(n),jQuery("<div></div>").addClass("close").prependTo(r).html("&times;").click(function(t){t.stopPropagation(),notify._remove(r)}),t.stay||(t.stayTime=null!=t.stayTime?t.stayTime:notify.stayTime,t.stayTime=t.stayTime>30?t.stayTime:1e3*t.stayTime,setTimeout(function(){notify._remove(r)},t.stayTime))},save:function(t){var e=storage.getItem("notify_flash_msg",[]);e.push(t),storage.setItem("notify_flash_msg",e)},init:function(){void 0!==messages?(notify.stayTime=null!=messages.pause?messages.pause:3e3,notify.stayTime=notify.stayTime>30?notify.stayTime:1e3*notify.stayTime):notify.stayTime=3e3;var t=notify._load();t&&t.forEach(function(t){notify.add(t.text,t.style)}),void 0!==messages&&messages.forEach(function(t){notify.add(t.msg,t.type)})},buildMsg:function(t,e){var n={};return n.text=null!=t?t:"No Message Giving.",n.style=null!=e?{red:"danger",yellow:"warning",blue:"info",green:"success",danger:"danger",warning:"warning",info:"info",success:"info",error:"danager",failure:"danager"}[e]:"info",n.stay="danger"==n.style,n},_load:function(t){var e=storage.getItem("notify_flash_msg",!1);return storage.removeItem("notify_flash_msg"),e},_remove:function(t){t.animate({opacity:"0"},600,function(){t.parent().animate({height:"0px"},300,function(){t.parent().remove()})})}};notify.init(),$(window).scroll(function(){storage.setItem(window.location.pathname+".st",$(window).scrollTop())}),$("body").on("bound",function(t,e){if(e){var n=storage.getItem(window.location.pathname+".st",null);n>0&&$(window).scrollTop(n)}});var tableSort={dir:"desc",index:0,iconsAdded:!1,sort:function(t,e){t>0&&(console.log("Sorting column:"+t+" direction:"+e+" sorting:"+$("table.table-sort tbody tr").length),tableSort.determineIcons(t,e),$("table.table-sort tbody tr").length&&(tinysort("table.table-sort tbody tr",{selector:"td:nth-child("+t+")",order:e,data:"value"},{selector:"td:nth-child("+t+")",order:e}),jQuery("body").trigger("tableSort","sort"))),tableSort.save(t,e)},addSortIcons:function(){tableSort.iconsAdded||($("table.table-sort thead tr th:not(.nosort)").prepend('<i class="fa fa-sort"></i> '),tableSort.iconsAdded=!0)},determineIcons:function(t,e){$("table.table-sort thead tr th i").removeClass("fa-sort-asc").removeClass("fa-sort-desc").addClass("fa-sort"),$("table.table-sort thead tr th").removeClass("active"),$("table.table-sort thead tr th:nth-child("+t+") i").addClass("fa-sort-"+e).removeClass("fa-sort"),$("table.table-sort thead tr th:nth-child("+t+")").addClass("active")},load:function(){var t=storage.getItem(window.location.pathname+".ssc","");""!=t&&(parts=t.split("\t"),tableSort.index=parts[0],tableSort.dir=parts[1]),tableSort.sort(tableSort.index,tableSort.dir)},save:function(t,e){storage.setItem(window.location.pathname+".ssc",t+"\t"+e)},init:function(){tableSort.addSortIcons(),$("table.table-sort thead tr th:not(.nosort)").on("click",function(){tableSort.dir="asc"==tableSort.dir?"desc":"asc",tableSort.index=$("table.table-sort thead tr th").index(this)+1,tableSort.sort(tableSort.index,tableSort.dir)}),tableSort.bound=!0}};$("body").on("bound",function(t,e){e?(tableSort.bound||tableSort.init(),tableSort.load()):(tableSort.bound=void 0,tableSort.iconsAdded=!1)}),function(t,e,n){"use strict";var r="stickyTableHeaders",i=0,a={fixedOffset:0,leftOffset:0,marginTop:39,scrollableArea:e};function o(n,o){var s=this;s.$el=t(n),s.el=n,s.id=i++,s.$window=t(e),s.$document=t(document),s.$el.bind("destroyed",t.proxy(s.teardown,s)),s.$clonedHeader=null,s.$originalHeader=null,s.isSticky=!1,s.hasBeenSticky=!1,s.leftOffset=null,s.topOffset=null,s.init=function(){s.$el.each(function(){var e=t(this);e.css("padding",0),s.$originalHeader=t("thead:first",this),s.$clonedHeader=s.$originalHeader.clone(),e.trigger("clonedHeader."+r,[s.$clonedHeader]),s.$clonedHeader.addClass("tableFloatingHeader"),s.$clonedHeader.css("display","none"),s.$originalHeader.addClass("tableFloatingHeaderOriginal"),s.$originalHeader.after(s.$clonedHeader),s.$printStyle=t('<style type="text/css" media="print">.tableFloatingHeader{display:none !important;}.tableFloatingHeaderOriginal{position:static !important;}</style>'),t("head").append(s.$printStyle)}),s.setOptions(o),s.updateWidth(),s.toggleHeaders(),s.bind()},s.destroy=function(){s.$el.unbind("destroyed",s.teardown),s.teardown()},s.teardown=function(){s.isSticky&&s.$originalHeader.css("position","static"),t.removeData(s.el,"plugin_"+r),s.unbind(),s.$clonedHeader.remove(),s.$originalHeader.removeClass("tableFloatingHeaderOriginal"),s.$originalHeader.css("visibility","visible"),s.$printStyle.remove(),s.el=null,s.$el=null},s.bind=function(){s.$scrollableArea.on("scroll."+r,s.toggleHeaders),s.isWindowScrolling||(s.$window.on("scroll."+r+s.id,s.setPositionValues),s.$window.on("resize."+r+s.id,s.toggleHeaders)),s.$scrollableArea.on("resize."+r,s.toggleHeaders),s.$scrollableArea.on("resize."+r,s.updateWidth)},s.unbind=function(){s.$scrollableArea.off("."+r,s.toggleHeaders),s.isWindowScrolling||(s.$window.off("."+r+s.id,s.setPositionValues),s.$window.off("."+r+s.id,s.toggleHeaders)),s.$scrollableArea.off("."+r,s.updateWidth)},s.toggleHeaders=function(){s.$el&&s.$el.each(function(){var e,n=t(this),r=s.isWindowScrolling?isNaN(s.options.fixedOffset)?s.options.fixedOffset.outerHeight():s.options.fixedOffset:s.$scrollableArea.offset().top+(isNaN(s.options.fixedOffset)?0:s.options.fixedOffset),i=n.offset(),a=s.$scrollableArea.scrollTop()+r,o=s.$scrollableArea.scrollLeft(),l=s.isWindowScrolling?a>i.top:r>i.top,d=(s.isWindowScrolling?a:0)<i.top+n.height()-s.$clonedHeader.height()-(s.isWindowScrolling?0:r);if(l&&d){e=i.left-o+s.options.leftOffset;var c=document.getElementById("navbar"),f=c?c.clientHeight:0;s.$originalHeader.css({position:"fixed","margin-top":f,left:e,"z-index":3}),s.leftOffset=e,s.topOffset=r,s.$clonedHeader.css("display",""),s.isSticky||(s.isSticky=!0,s.updateWidth()),s.setPositionValues()}else s.isSticky&&(s.$originalHeader.css("position","static"),s.$clonedHeader.css("display","none"),s.isSticky=!1,s.resetWidth(t("td,th",s.$clonedHeader),t("td,th",s.$originalHeader)))})},s.setPositionValues=function(){var t=s.$window.scrollTop(),e=s.$window.scrollLeft();!s.isSticky||t<0||t+s.$window.height()>s.$document.height()||e<0||e+s.$window.width()>s.$document.width()||s.$originalHeader.css({top:s.topOffset-(s.isWindowScrolling?0:t),left:s.leftOffset-(s.isWindowScrolling?0:e)})},s.updateWidth=function(){if(s.isSticky){s.$originalHeaderCells||(s.$originalHeaderCells=t("th,td",s.$originalHeader)),s.$clonedHeaderCells||(s.$clonedHeaderCells=t("th,td",s.$clonedHeader));var e=s.getWidth(s.$clonedHeaderCells);s.setWidth(e,s.$clonedHeaderCells,s.$originalHeaderCells),s.$originalHeader.css("width",s.$clonedHeader.width())}},s.getWidth=function(n){var r=[];return n.each(function(n){var i,a=t(this);if("border-box"===a.css("box-sizing")){var o=a[0].getBoundingClientRect();i=o.width?o.width:o.right-o.left}else{if("collapse"===t("th",s.$originalHeader).css("border-collapse"))if(e.getComputedStyle)i=parseFloat(e.getComputedStyle(this,null).width);else{var l=parseFloat(a.css("padding-left")),d=parseFloat(a.css("padding-right")),c=parseFloat(a.css("border-width"));i=a.outerWidth()-l-d-c}else i=a.width()}r[n]=i}),r},s.setWidth=function(t,e,n){e.each(function(e){var r=t[e];n.eq(e).css({"min-width":r,"max-width":r})})},s.resetWidth=function(e,n){e.each(function(e){var r=t(this);n.eq(e).css({"min-width":r.css("min-width"),"max-width":r.css("max-width")})})},s.setOptions=function(n){s.options=t.extend({},a,n),s.$scrollableArea=t(s.options.scrollableArea),s.isWindowScrolling=s.$scrollableArea[0]===e},s.updateOptions=function(t){s.setOptions(t),s.unbind(),s.bind(),s.updateWidth(),s.toggleHeaders()},s.init()}t.fn[r]=function(e){return this.each(function(){var n=t.data(this,"plugin_"+r);n?"string"==typeof e?n[e].apply(n):n.updateOptions(e):"destroy"!==e&&t.data(this,"plugin_"+r,new o(this,e))})}}(jQuery,window),$("body").on("bound",function(t,e){e&&$(".table-sticky-header").stickyTableHeaders({fixedOffset:$(".page-header.navbar.navbar-fixed-top")})}),tinybind.stdlib={defaultPrecision:2,defaultThousandSeparator:"'",defaultDecimalSeparator:".",defaultDateFormat:"YYYY-MM-DD",defaultTimeFormat:"HH:mm:ss",defaultDatetimeFormat:"YYYY-MM-DD HH:mm:ss"},tinybind.formatters.log=function(t){return console.log(t)},tinybind.formatters.default=function(t,e){return tinybind.formatters.isEmpty(t)?e:t},tinybind.formatters.add=function(t,e){return t+e},tinybind.formatters.sub=function(t,e){return t-e},tinybind.formatters.map=function(t,e,n){var r=Array.prototype.slice.call(arguments);return r.splice(1,2),e[n].apply(e,r)},tinybind.formatters.isBoolean=function(t){return"boolean"==typeof t},tinybind.formatters.isNumeric=function(t){return!isNaN(t)},tinybind.formatters.isNaN=function(t){return!!tinybind.formatters.isArray(t)||isNaN(t)},tinybind.formatters.isInteger=function(t){return t===+t&&t===(0|t)},tinybind.formatters.isFloat=function(t){return 1/0!==t&&t===+t&&t!==(0|t)},tinybind.formatters.isNumber=function(t){return tinybind.formatters.isFloat(t)||tinybind.formatters.isInteger(t)},tinybind.formatters.isObject=function(t){return tinybind.formatters.toBoolean(t)&&"object"==typeof t&&!tinybind.formatters.isArray(t)},tinybind.formatters.isFunction=function(t){return"function"==typeof t},tinybind.formatters.isArray=function(t){return tinybind.formatters.isFunction(Array.isArray)?Array.isArray(t):t instanceof Array},tinybind.formatters.isString=function(t){return"string"==typeof t||t instanceof String},tinybind.formatters.isInfinity=function(t){return t===1/0},tinybind.formatters.toBoolean=function(t){return!!t},tinybind.formatters.toInteger=function(t){var e=parseInt(1*t,10);return isNaN(e)?0:e},tinybind.formatters.toFloat=function(t){var e=parseFloat(1*t);return isNaN(e)?0:e},tinybind.formatters.toDecimal=function(t){var e=tinybind.formatters.toInteger(1*t),n=tinybind.formatters.toFloat(t);return e==n?e:n},tinybind.formatters.toArray=function(t){return tinybind.formatters.isArray(t)?t:tinybind.formatters.isObject(t)?tinybind.formatters.values(t):[t]},tinybind.formatters.toString=function(t){return t?t.toString():""},tinybind.formatters.integer={read:function(t){return tinybind.formatters.toInteger(t)},publish:function(t){return tinybind.formatters.toInteger(t)}},tinybind.formatters.sum=function(t,e){return 1*t+1*e},tinybind.formatters.substract=function(t,e){return 1*t-1*e},tinybind.formatters.multiply=function(t,e){return 1*t*(1*e)},tinybind.formatters.divide=function(t,e){return 1*t/(1*e)},tinybind.formatters.min=function(){return Math.min.apply(Math,arguments)},tinybind.formatters.max=function(){return Math.max.apply(Math,arguments)},tinybind.formatters.isEqual=function(t,e){return t===e},tinybind.formatters.isNotEqual=function(t,e){return t!==e},tinybind.formatters.isLess=function(t,e){return 1*t<1*e},tinybind.formatters.isGreater=function(t,e){return 1*t>1*e},tinybind.formatters.isLessEqual=function(t,e){return 1*t<=1*e},tinybind.formatters.isGreaterEqual=function(t,e){return 1*t>=1*e},tinybind.formatters.or=function(){for(var t=0;t<arguments.length;t++)if(tinybind.formatters.toBoolean(arguments[t]))return!0;return!1},tinybind.formatters.and=function(){for(var t=0;t<arguments.length;t++)if(!tinybind.formatters.toBoolean(arguments[t]))return!1;return!0},tinybind.formatters.negate=function(t){return!tinybind.formatters.toBoolean(t)},tinybind.formatters.if=function(t,e,n){return tinybind.formatters.toBoolean(t)?e:n},tinybind.formatters.numberFormat=function(t,e,n,r){t=tinybind.formatters.isNumber(t)?t:tinybind.formatters.toDecimal(t),tinybind.formatters.isInteger(e)||(e=tinybind.stdlib.defaultPrecision),n||(n=tinybind.stdlib.defaultDecimalSeparator),r||(r=tinybind.stdlib.defaultThousandSeparator);var i=(+(Math.round(+(Math.abs(t)+"e"+e))+"e"+-e)).toFixed(e);return t<0&&(i="-"+i),2==(i=i.split(".")).length?i[0].replace(/\B(?=(\d{3})+(?!\d))/g,r)+n+i[1]:i[0].replace(/\B(?=(\d{3})+(?!\d))/g,r)},tinybind.formatters.date=function(t){return moment(t).format(tinybind.stdlib.defaultDateFormat)},tinybind.formatters.time=function(t){return moment(t).format(tinybind.stdlib.defaultTimeFormat)},tinybind.formatters.datetime=function(t){return moment(t).format(tinybind.stdlib.defaultDatetimeFormat)},tinybind.formatters.toTimestamp=function(t){return moment(t).format("X")},tinybind.formatters.toDate=function(t){return moment.unix(t).toDate()},tinybind.formatters.toMoment=function(t){return moment(t)},tinybind.formatters.dateFormat=function(t,e){return moment(t).format(e)},tinybind.formatters.pairs=function(t){return Object.keys(t).map(function(e){return{object:t,property:e,value:t[e]}})},tinybind.formatters.catalog=function(t,e,n,r){n=n||"id",r=r||"value";for(const i in e)if(e[i][n]==t)return e[i][r];return""},tinybind.formatters.keys=function(t){return Object.keys(t)},tinybind.formatters.values=function(t){return Object.keys(t).map(function(e){return t[e]})},tinybind.formatters.stringFormat=function(t){for(var e=1;e<arguments.length;e++){var n=t.indexOf("%s");if(-1===n)break;t=t.slice(0,n)+arguments[e]+t.slice(n+2)}return t},tinybind.formatters.split=function(t,e){return t.split(e)},tinybind.formatters.lower=function(t){return t.toLowerCase()},tinybind.formatters.upper=function(t){return t.toUpperCase()},tinybind.formatters.capitalize=function(t){return(t=tinybind.formatters.toString(t)).split(" ").map(function(t){return t.split("-").map(function(t){return t.charAt(0).toUpperCase()+t.slice(1)}).join("-")}).join(" ")},tinybind.formatters.contains=function(t,e){return-1!==t.indexOf(e)},tinybind.formatters.doesNotContain=function(t,e){return tinybind.formatters.negate(tinybind.formatters.contains(t,e))},tinybind.formatters.prettyPrint=function(t){return JSON.stringify(t,null,2)},tinybind.formatters.isEmpty=function(t){return!tinybind.formatters.toBoolean(t)||0===tinybind.formatters.toArray(t).length},tinybind.formatters.length=function(t){return tinybind.formatters.isString(t)?t.length:tinybind.formatters.toArray(t).length},tinybind.formatters.join=function(t,e){return tinybind.formatters.toArray(t).join(e)},tinybind.formatters.wrap=function(t){var e=Array.prototype.slice.call(arguments);return e.splice(0,1),function(n){var r=e.slice();return Array.prototype.push.apply(r,Array.prototype.slice.call(arguments)),t.apply(this,r)}},tinybind.formatters.delay=function(t,e){var n=this;return function(){setTimeout(function(){t.apply(n,arguments)},e)}},tinybind.formatters.preventDefault=function(t){var e=this;return function(n){return n.preventDefault(),t.call(e,n),!1}},tinybind.binders.width=function(t,e){t.style.width=e},tinybind.binders.height=function(t,e){t.style.height=e},tinybind.binders.intcheck={publishes:!0,priority:2e3,bind:function(t){var e=this;this.callback||(this.callback=function(){e.publish()}),t.addEventListener("change",this.callback)},unbind:function(t){t.removeEventListener("change",this.callback)},routine:function(t,e){t.checked=t.value==e},getValue:function(t){return t.checked?t.value:0}},tinybind.formatters.enum=function(t,e){return arguments[parseInt(t)+1]},tinybind.formatters.eq=tinybind.formatters.isEqual,tinybind.formatters.ne=function(t,e){return tinybind.formatters.negate(tinybind.formatters.isEqual(t,e))},tinybind.formatters.lt=tinybind.formatters.isLess,tinybind.formatters.gt=tinybind.formatters.isGreater,tinybind.formatters.le=tinybind.formatters.isLessEqual,tinybind.formatters.lte=tinybind.formatters.isLessEqual,tinybind.formatters.ge=tinybind.formatters.isGreaterEqual,tinybind.formatters.gte=tinybind.formatters.isGreaterEqual,tinybind.formatters.prv=tinybind.formatters.preventDefault,tinybind.formatters.inject=tinybind.formatters.stringFormat,tinybind.formatters.sprintf=tinybind.formatters.stringFormat,tinybind.formatters.format=tinybind.formatters.dateFormat,tinybind.formatters.len=tinybind.formatters.length,tinybind.formatters.def=tinybind.formatters.default,tinybind.formatters.neg=tinybind.formatters.negate,tinybind.formatters.date=tinybind.formatters.dateFormat,tinybind.formatters.stringify=tinybind.formatters.prettyPrint,tinybind.formatters.int=tinybind.formatters.integer,tinybind.formatters.isLower=tinybind.formatters.isLess,tinybind.formatters.isLowerEqual=tinybind.formatters.isLessEqual;var app={id:"#app",local:{},error:!1,errors:{},record:{},records:[],page:{},form:{},events:{},bound:void 0,init(){app.helpers.route()},event:{add(t,e){return app.events[t]=e,this}},router:{routes:[],mode:null,root:"/",config:function(t){return this.mode=t&&t.mode&&"history"==t.mode&&history.pushState?"history":"hash",this.root=t&&t.root?"/"+this.clearSlashes(t.root)+"/":"/",this},getFragment:function(){var t="";if("history"===this.mode)t=(t=this.clearSlashes(decodeURI(location.pathname+location.search))).replace(/\?(.*)$/,""),t="/"!=this.root?t.replace(this.root,""):t;else{var e=window.location.href.match(/#(.*)$/);t=e?e[1]:""}return this.clearSlashes(t)},clearSlashes:function(t){return t.toString().replace(/\/$/,"").replace(/^\//,"")},add:function(t,e){return"function"==typeof t&&(e=t,t=""),this.routes.push({re:t,handler:e}),this},remove:function(t){for(var e,n=0;this.routes.length,e=this.routes[n];n++)if(e.handler===t||e.re.toString()===t.toString())return this.routes.splice(n,1),this;return this},flush:function(){return this.routes=[],this.mode=null,this.root="/",this},check:function(t){for(var e=t||this.getFragment(),n=0;n<this.routes.length;n++){var r=e.match(this.routes[n].re);if(r)return r.shift(),this.routes[n].handler.apply({},r),this}return this},listen:function(){var t=this,e=t.getFragment();return clearInterval(this.interval),this.interval=setInterval(function(){e!==t.getFragment()&&(e=t.getFragment(),t.check(e))},50),this},navigate:function(t){return t=t||"","history"===this.mode?history.pushState(null,null,this.root+this.clearSlashes(t)):window.location.href=window.location.href.replace(/#(.*)$/,"")+"#"+t,this}},helpers:{response:{200:function(t,e,n){console.log(arguments),alert("200 (ok) handler")},201:function(t,e,n){console.log(arguments),alert("201 (created) handler")},202:function(t,e,n){console.log(arguments),alert("202 (accepted) handler")},401:function(t,e,n){console.log(arguments),alert("401 (unauthorized) handler")},404:function(t,e,n){console.log(arguments),alert("404 (not found) handler")},406:function(t,e,n){console.log(arguments),alert("406 (not accepted) handler")},409:function(t,e,n){console.log(arguments),alert("409 (conflict) handler")},500:function(t,e,n){console.log(arguments),alert("500 (server error) handler")}},ajax(t,e,n,r){jQuery.ajax({method:t,url:e,data:n,dataType:"json",cache:!1,timeout:5e3,async:!0,statusCode:Object.assign(app.helpers.response,r)})},getHandlers:()=>app.helpers.response,setData(t){t.error&&(app.error=t.error),t.errors&&(app.errors=t.errors),t.model&&(Array.isArray(t.model)?(app.records=t.model,app.record=void 0):(app.record=t.model,app.records=void 0));var e=["page","form"];for(var n in e){var r=e[n];if(null!=t[r])for(subkey in t[r])app[r][subkey]=t[r][subkey]}},getData:()=>({error:app.error,errors:app.errors,model:app.helpers.getModel(),page:app.page,form:app.form}),modelIsA:()=>app.record?"object":"array",getModel:()=>model=app.record?app.record:app.records,load(t,e){jQuery("body").trigger("bound",!1),app.helpers.loadTemplate(t,function(t){jQuery(app.id).html(t),e&&(app.helpers.response[200]=function(t,e,n){app.helpers.setData(t),app.bound=tinybind.bind(document.querySelector(app.id),app),jQuery("body").trigger("bound",!0)},app.helpers.ajax("get",e,{},app.helpers.getHandlers()))})},route(t){t=t||window.location.pathname,app.router.isSetup||(app.router.isSetup=!0,app.router.config({mode:"history"}).listen()),app.router.check(t)},loadTemplate(t,e){var n=storage.getItem(t+".bind");n?e(n):jQuery.get(t,function(n){storage.setItem(t+".bind",n),e(n)})}}};function debounce(t,e,n){var r;return function(){var i=this,a=arguments,o=n&&!r;clearTimeout(r),r=setTimeout(function(){r=null,n||t.apply(i,a)},e),o&&t.apply(i,a)}}document.addEventListener("DOMContentLoaded",function(){app.init()}),app.router.add(/catalog\/edit\/(.*)/,function(t){app.helpers.load("/layout/get/catalog/details","/catalog/edit/"+t)}).add(/catalog\/create/,function(){app.helpers.load("/layout/get/catalog/details","/catalog/create")}).add(/catalog/,function(t){notify.removeAll(),app.helpers.load("/layout/get/catalog/index","/catalog/index")}).add(/edit\/(.*)/,function(t){app.helpers.load("/layout/get/robot/details","/robot/edit/"+t)}).add(/create/,function(t){app.helpers.load("/layout/get/robot/details","/robot/create")}).add(function(){notify.removeAll(),app.helpers.load("/layout/get/robot/index","/robot/index")}),app.helpers.response[404]=function(t,e,n){app.helpers.load("/layout/get/notfound")},app.event.add("goto",function(t,e){e.preventDefault(),app.router.navigate(t)}).add("create",function(t,e){e.preventDefault(),app.router.navigate(t+"/create")}).add("edit",function(t,e,n){n.preventDefault(),app.router.navigate(t+"/edit/"+e)}).add("delete",function(t,e,n){n.preventDefault(),app.local.closest_tr=jQuery(this).closest("tr"),bootbox.confirm({message:"Are you sure you want to delete this record?",buttons:{cancel:{label:'<i class="fa fa-times"></i> Cancel'},confirm:{label:'<i class="fa fa-trash"></i> Delete',className:"btn-danger"}},callback:function(n){n&&(app.helpers.response[202]=function(t,e,n){app.local.closest_tr.remove()},app.helpers.ajax("delete",t+"/delete/"+e,{},app.helpers.getHandlers()))}})}).add("submit",function(t){t.preventDefault(),app.helpers.response[201]=function(t,e,n){app.router.navigate(app.page.path)},app.helpers.response[202]=function(t,e,n){app.router.navigate(app.page.path)},app.helpers.response[406]=function(t,e,n){if(app.helpers.setData(t.responseJSON),app.error){notify.removeAll();for(const t in app.errors)for(const e in app.errors[t])notify.addError(app.errors[t][e])}},app.helpers.ajax(app.form.method,app.form.action,app.helpers.getData(),app.helpers.getHandlers())});
//# sourceMappingURL=application.js.map