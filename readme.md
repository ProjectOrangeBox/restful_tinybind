# TinyBind / OrangeBind Setup

Download the standard CodeIgniter 3 Package and install

Then to your application folder add the `MY_Controller`, `MY_Input`, `MY_Model` files to your core folder.

MY_Controller provides some really basic routing using the CodeIgniter Controller \_remap method.

- indexGet()
- indexGetAjax()
- createGetAjax()
- createPostAjax()
- editGetAjax()
- editPatchAjax()
- deleteDeleteAjax()
- send(success,fail)

MY_Input provides a wrapper around the raw input stream

- request(key) get a value from the raw input

MY_Model provides a some simple database functions for:

- all() return all records
- empty() return a empty record
- get(primary id) get a record based on it's primary id
- insert(data array) insert a record
- update(data array) update a record
- delete(primary id) delete a record based on it primary id
- check(data array, primary key required) simple validation that only checks for required fields

Create a `/assets` folder

These are combined by gulp into your distro in `/public/dist/bundle.js`

npm install for all of the gulp packages

Use the following to create the distro files

`gulp watch` run once
`gulp watch:js` run once and continue to watch javascript
`gulp watch:css` run once and continue to watch css
`gulp watch:pug` run once and continue to watch pug template

---

# Orange Bind

In you `javascript` `app` is declared as a global variable in `orangeBind/orangeBind.js`

# /assets/plugins/orangeBind/\*

## orangeBind.js

### The Model <-> View Binder with support for a URL Router

#### orangeBind properties and methods

Relies on jQuery for:

`.ajax(url,{settings as object})` - request.send() ajax calls
`.extend()` - setData() object merge
`.trigger()` - calling custom DOM triggers others can pick up

```javascript
jQuery("body").trigger("something-happened");
jQuery("body").on("something-happened", function() {
	alert("Hey! Something Happened!");
});
```

---

`id` the applications div wrapper id (default is app)

`init()` application init

---

`error: boolean` storage for bound has error

`errors: {}` storage for bound errors

---

`local: {}` local app storage (not saved between full page refreshes)

`model: {}` storage for the currently bound model (record OR records)

`records: []` storage for currently bound records

`record: {}` storage for a currently bound record

`page: {}` storage for page bound variables

`form: {}` storage for form bound variables

---

`config: {}` storage for application configuration

`config.alter(name|object,value)` add or change a configuration value(s)

---

`trigger: {}` storage for application specific DOM triggers

`trigger.bound` trigger DOM bound event

`trigger.unbound` trigger DOM unbound event

`trigger.alter(name|object, callback)` add or change a trigger(s)

---

`events: {}` storage for tinybind events

`event.alter(name|object, callback)` add or change a tinybind event(s)

```html
<a
	class="btn btn-default btn-sm js-esc"
	rv-on-click="events.navigate | wrap page.path"
	><i class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> Go Back</a
>
```

https://blikblum.github.io/tinybind/docs/reference/#on-[event]

---

`method: {}` storage for user methods

`method.alter(name|object, callback)` add or change a user method(s)

---

`router.check()` check the current URL and preform the callback found if any

`router.getUrl()` get the current url

`router.alter(regularExpression, callback)` add or change a route

`router.remove(param)` remove a route based on the regular expression

`router.flush()` remove ALL routes

`router.listen()` turn on the route listener

`router.navigate(url, redirect)` navigate to a different url

---

`response.alter(code|object, callback)` add or change a response codes action(s)

`response.merge(callbacks)` add multiple callbacks at once

---

`request.send(method, url, data, callbacks)` send a raw request

`request.get(url, data, callbacks)` wrapper for get request

`request.post(url, data, callbacks)` wrapper for post request

`request.patch(url, data, callbacks)` wrapper for patch request

`request.delete(url, data, callbacks)` wrapper for delete request

`request.create(url, data, callbacks)` wrapper for create (post) request

`request.read(url, data, callbacks)` wrapper for read (get) request

`request.update(url, data, callbacks)` wrapper for update (patch) request

`request.insert(url, data, callbacks)` wrapper for insert (post) request

---

`setData(data)` merge passed data with the current application data

- error
- errors
- model
- page
- form
- config
- record
- records
- config

`getData(data)` return the current application data

- error
- errors
- models
- page
- form
- values

`cacheCleanUp(config)` clean up the cached based on passed class

- clearCache = boolean
- olderThanCache seconds (integer)

`loadModel(modelEndPoint, templateEndPoint)` load a model from the server or load a template then a model from the server

`loadTemplate(templateEndPoint, then)` load a model from the server (or locally from the browser if cached) then call something else

`refresh(data, then)` unbind the model from the DOM set the new data then rebind the "new" model back on the DOM then call something else

## superStorage.js

### Smarter Browser Storage

Support caching by time (with 1 year default)
Retrieve with default value if expired or unavailable
Auto clearing oldest if the browser storage is full

`storage.getItem(key,defaultValue)`
get a locally cached value with a default value (optional) if it's unavailable or expired

`storage.removeItem(key)`
manually remove a cached item based on it's key

`storage.clear()`
clear ALL cached items

`storage.getDetailed(key)`
get the details about a cached item these include: created, data, expires, life

`storage.removeOlderThan(seconds)`
remove cached items older than X seconds
(caches expire automatically but this allows you to manually expire cached values)

`storage.setItem(key,data,seconds)`
set a browser cached item based on it's key. if no seconds provided then the default will be used (1 year)

### Note: if you try to set a item an your browsers maximum storage for cached items has been reached the oldest records will automatically be removed to make enough room.

## sprintf.js

`sprintf(format,mixed...)`
Javascript Implementation of sprintf

https://www.tutorialspoint.com/c_standard_library/c_function_sprintf.htm
https://github.com/kvz/locutus/blob/master/src/php/strings/sprintf.js

## tinyBind.js

Lightweight and powerful data binding + templating solution for building modern web applications.
The espiritual sucessor of Rivets.js

https://blikblum.github.io/tinybind/

---

# /assets/app/app.js

Setup the application

Alter Configuration Value individually or in bulk

```javascript
app.config.alter((string) name,(mixed) value);
app.config.alter((object) name);
app.config.collect();
```

Alter Route Value individually or in bulk

```javascript
app.router.alter((string) regular expression, (function) callback);
app.router.alter((object) regular expression);
```

Alter TinyBind Event Values individually or in bulk

```javascript
app.events.alter((string) name,(function) callback);
app.events.alter((object) name);
app.events.collect();
```

Alter Trigger Values individually or in bulk

```javascript
app.triggers.alter((string) name,(function) callback);
app.triggers.alter((object) name);
app.triggers.collect();
```

Alter User Method Values individually or in bulk

```javascript
app.method.alter((string) name,(function) callback);
app.method.alter((object) name);
app.methods.collect();
```

Alter Response Based on HTTP status code individually or in bulk

```javascript
app.response.alter((integer) code, (function) callback);
app.response.alter((object) code);
```

# /assets/js/\*

## application.js

File to place your own javascript which is combined in the distro.

## binders.js

All of the bundled tinybind.js binders.
If you need to add additional binders you can add them to this file

https://blikblum.github.io/tinybind/docs/guide/#binders

## formatters.js

All of the bundled tinybind.js formatters.
If you need to add additional formatters you can add them to this file

https://blikblum.github.io/tinybind/docs/guide/#formatters

## onReady.js

File to put all of your on DOM ready javascript
This is also what calls `app.init()` once the DOM is Ready
