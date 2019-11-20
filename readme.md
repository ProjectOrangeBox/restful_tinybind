# TinyBind / OrangeBind Setup

Download the standard CodeIgniter 3 Package and install

Add the core `MY_Controller`, `MY_Input`, `MY_Model`.

Create a `/assets` folder which is combined into the public dist (distribution) folder

ie. `/public/dist/bundle.js`

npm install for all of the gulp packages

`gulp watch`
`gulp watch:js`
`gulp watch:css`
`gulp watch:pug`

In you `javascript` `app` is declared as a global variable in `orangeBind/orangeBind.js`

# /assets/plugins/orangeBind/\*

## orangeBind.js

### The Model <-> View Binder with support for a URL Router

orangeBind properties and methods

---

`id` the applications div wrapper id (default is app)

`config: {}` storage for default a additional configuration

`local: {}` local app storage (not saved between full page refreshes)

`error: {}` storage for a single error

`errors: {}` storage for multiple errors

`model: {}` storage for the current bound model

`records: {}` storage for multiple records

`record: []` storage for a single records

`page: {}` storage for page variables

`form: {}` storage for form variables

`events: {}` storage for tinybind events

`triggers: {}` storage for tinybind triggers - defaults bound / unbound which trigger a jquery event on the document body

`init()` application init

`event.add(name, callback)` add additional events to app.events

---

`router.check()` check the current URL and preform the callback found if any

`router.getUrl()` get the current url

`router.change(regularExpression, callback)` change a route

`router.add(regularExpression, callback)` (wrapper for change for syntax completeness)

`router.remove(param)` remove a route based on the regular expression

`router.flush()` remove ALL routes

`router.listen()` turn on the route listener 

`router.navigate(url, redirect)` navigate to a different url

---

`response.change(code, callback)` change a response codes action

`response.add(code, callback)` (wrapper for change for syntax completeness)

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

`setData(data)` merge passed data with the current application data (error, errors, model, page, form, config, record, records, config)

`getData(data)` return the current application error, errors, models, page, form values

`cacheCleanUp(config)` clean up the cached based on passed class clearCache = [t/f] olderThanCache [integer]

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

# /assets/app/app.js

Setup the application

Then the following methods are called to add additional values

_required_
`app.config.add('url','/get/config')`
the url to call to get the applications configuration from the server

`app.config.add('layoutUrl','/get/layout')`
the url to call to get a layout

_optional_
`app.config.add(name,value)`
`app.config.change(name,value)`
add or change application configuration values (add is a wrapper for change to make the syntax read better)

`app.router.add(name,callback)`
your application routes and actions (callbacks) to take when a route is encountered

`app.events.add(name,callback)`
these are attached using tinybind

#### Note: the last app.router.add(function() {...}) is the default route

We also declare our "user methods"
`app.userMethods{}`
a place to attach user methods to the globally available app

# /assets/js/\*

## application.js

File to place your own javascript

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

