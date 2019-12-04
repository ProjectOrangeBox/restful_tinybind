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

# Required Node Modules

    "dependencies": {
    	"bootbox": "^5.3.4",
    	"bootstrap-select": "^1.13.12",
    	"bootstrap3": "^3.3.5",
    	"del": "^4.1.1",
    	"font-awesome": "^4.7.0",
    	"handlebars": "^4.5.3",
    	"jquery": "^3.4.1",
    	"jstorage": "^0.4.8",
    	"keymaster": "^1.6.2",
    	"localstorage-fifo": "^2.0.1",
    	"popper.js": "^1.16.0",
    	"roboto-fontface": "^0.10.0",
    	"sprintf-js": "^1.1.2",
    	"tinybind": "^1.0.0",
    	"tinysort": "^3.2.5"
    },

## sprintf.js

`sprintf(format,mixed...)`

https://www.npmjs.com/package/sprintf-js

## tinyBind.js

Lightweight and powerful data binding + templating solution for building modern web applications.
The espiritual sucessor of Rivets.js

https://blikblum.github.io/tinybind/

---

# Orange Bind

# /assets/plugins/orangeBind/\*

## orangeBind.js

#### orangeBind properties and methods

Relies on jQuery for:

`ajax(url,{settings as object})` - request.send() ajax calls

`trigger()` - calling custom DOM triggers others can pick up

```javascript
app.listener("something-happened", function() {
	alert("Hey! Something Happened!");
});

app.trigger("something-happened");
```

---

### Properties

`id` the applications div wrapper id (default is app)

---

`error: boolean` storage for bound has error

`errors: {}` storage for bound errors

---

`page: {}` storage for page bound variables

`form: {}` storage for form bound variables

`user: {}` storage for user bound variables

`local: {}` local app storage (not saved between full page refreshes)

---

`model: {}` storage for the currently bound model (record OR records)

`records: []` storage for currently bound records

`record: {}` storage for a currently bound record

---

`config: {}` storage for application configuration collection\*

`method: {}` storage for user methods collection\*

`events: {}` storage for tinybind events collection\*

`templates: {}` storage for templates collection\*

---

`router.match()` check the current URL and preform the callback found if any

`router.url()` get the current url

`router.alter(regularExpression, callback)` add or change a route

`router.remove(regularExpression)` remove a route based on the regular expression

`router.flush()` remove ALL routes

`router.start()` turn on the route listener

`router.stop()` turn off the route listener

`router.listener()` the actual listener method

`router.navigate(url, redirect)` navigate to a different url

---

`request.status` last request status code

`request.statusMsg` last request status message

`setStatus(code, msg)` set the status code and message

`request.on(code|object, callback)` add or change a response codes action(s)

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

`load.model(modelEndPoint, then)` load a model from the server or load a template then a model from the server

`load.template(templateEndPoint, then)` load a model from the server (or locally from the browser if cached) then call something else

`load.block(templateEndPoint, modelEndPoint , then)` load a template then a model from the server then call something else

---

`trigger(event,arguments)` trigger an event with optional arguments (wrapper for jQuery trigger - these are attached to the document body)

`listener(event,callback)` listen for an event (wrapper for jQuery on - listening on the document body)

`set(data,settable)` set data based on default settable values or provided array of properties

`get(data,gettable)` return the current application data on default settable values or provided array of properties

`html(html)` replace the DOM element id

`element()` get the DOM element for the provided id

`bind(data, then)` (tiny)bind the DOM to the data

`unbind(data, then)` unbind the data from the DOM

## orangeBinders.js

All of the bundled tinybind.js binders.
If you need to add additional binders you can add them to this file

https://blikblum.github.io/tinybind/docs/guide/#binders

## orangeFormatters.js

All of the bundled tinybind.js formatters.
If you need to add additional formatters you can add them to this file

https://blikblum.github.io/tinybind/docs/guide/#formatters

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
app.router.alter((object) );
```

Alter TinyBind Event Values individually or in bulk

```javascript
app.events.alter((string) name,(function) callback);
app.events.alter((object) name);
app.events.collect();
```

Alter Trigger Values individually or in bulk

```javascript
app.trigger((string) name);

app.listener((string) name, (function) callback);
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

## onReady.js

File to put all of your on DOM ready javascript
This is also what calls `app.init()` once the DOM is Ready
