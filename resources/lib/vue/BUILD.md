## Why we have vue.common.prod.unminified.js

Vue ships with vue.common.dev.js (which is unminified), and vue.common.prod.js (which is minified).
But prod.js is not simply a minified version of dev.js, it contains slightly different code. To
satisfy Debian package policies, we need to have an unminified version of all minified code, so we
need an unminified version of vue.common.prod.js. Vue doesn't publish one, so we have to build it
ourselves.

## How to build it

Download a forked version of Vue 2.6.11 whose build script has been hacked to disable minification
for all build targets. Then run the build, and use the prod.js file from the build output:

```
$ git clone git@github.com:catrope/vue --branch unminified-build
$ cd vue
$ npm install
$ npm run build
$ cp dist/vue.common.prod.js path/to/mediawiki/resources/lib/vue.common.prod.unminified.js
```
