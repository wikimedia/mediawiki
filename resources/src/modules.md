# Modules

Modules in MediaWiki are based on [JavaScript modules](https://developer.mozilla.org/docs/Web/JavaScript/Guide/Modules) and are provided by
[ResourceLoader](https://www.mediawiki.org/wiki/Special:MyLanguage/ResourceLoader/Developing_with_ResourceLoader).
Modules are the preferred way to interact with the MediaWiki frontend API.

This section documents [stable](https://www.mediawiki.org/wiki/Special:MyLanguage/Stable_interface_policy/Frontend) modules included with MediaWiki core.
For information about modules defined by extensions, see the documentation for those extensions.

To use a module, load the module asynchronously with `mw.loader`:

```js
mw.loader.using( 'moduleName' ).then( ( require ) => {
  const module = require( 'moduleName' );
  module.func();
} );();
```

Or, you can list the module in your extension's [ResourceLoader module](https://www.mediawiki.org/wiki/Special:MyLanguage/ResourceLoader/Developing_with_ResourceLoader#Registering) as a dependency. For example, to use `mediawiki.util`:

`extension.json`
```json
"ResourceModules": {
    "ext.MyExtension": {
        "localBasePath": "modules/ext.MyExtension",
        "remoteExtPath": "MyExtension/modules/ext.MyExtension",
        "packageFiles": [
            "index.js"
        ],
        "dependencies": [
            "mediawiki.util"
        ]
    }
}
```

`index.js`
```js
const module = require( 'mediawiki.util' );
module.clearSubtitle();
```

## jQuery plugins

MediaWiki uses the jQuery library. You can access and extend the jQuery object using plugins
included with MediaWiki core by loading certain ResourceLoader modules.
