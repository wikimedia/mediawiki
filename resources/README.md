This documentation describes the public API that user scripts, gadgets, skins, and extensions can use to interact with [MediaWiki](https://www.mediawiki.org/wiki/Special:MyLanguage/MediaWiki).
To interact with MediaWiki from outside a wiki, use the [Action API](https://www.mediawiki.org/wiki/Special:MyLanguage/API:Main_page).

The MediaWiki frontend API consists of [global variables](namespaces.html) and [ResourceLoader modules](modules.html).

## Get started
- [Write your first user script](https://www.mediawiki.org/wiki/Special:MyLanguage/Gadget_kitchen)
- Develop [extensions](https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Developing_extensions) and [skins](https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:How_to_make_a_MediaWiki_skin)

## Explore the documentation
Browse namespaces and classes within the [MediaWiki base library](mw.html).

### Manage dependencies
Load modules and scripts to use in your code.

- [mw.loader](mw.loader.html)

### Access wiki configuration
Get information about wikis, pages, and users. See the complete [list of configuration values](https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Interface/JavaScript#mw.config).

- [mw.config](mw.html#.config)

### Use the API
Interact with a wiki's API to query pages, edit pages, perform patrolling actions, and more.

- [mw.Api](mw.Api.html) — The [Action API](https://www.mediawiki.org/wiki/Special:MyLanguage/API:Main_page) is a full-featured API that includes a complete set of actions and parameters. To try it out, visit [Special:ApiSandbox](https://www.mediawiki.org/wiki/Special:ApiSandbox) on any wiki.
- [mw.Rest](mw.Rest.html) — The [REST API](https://www.mediawiki.org/wiki/Special:MyLanguage/API:REST_API) is a simplified API for performing basic read and write operations.

### Integrate with wiki features
Hooks let you register and fire events that you can use to extend and enhance the behavior of MediaWiki.

- [mw.hook](Hook.html)
- [global events](Hooks.html)

### Format and parse system messages
Handle translatable text or HTML strings that are part of the MediaWiki interface.

- [mw.message](mw.html#.message)
- [mw.language](mw.language.html)

### Send notifications
Display pop-up notifications to users.

- [mw.notification](mw.notification.html)

### Interact with users
Get information about users, sessions, and user preferences.

- [mw.user](mw.user.html)

### Interact with pages
Construct and parse page elements.

- [mw.Title](mw.Title.html)
- [mw.Uri](mw.Uri.html)

### Utilities
Get helpful methods for handling URLs, CSS, regular expressions, and more.

- [mw.util](module-mediawiki.util.html)

### Debugging and error reporting
Log errors, send deprecation warnings, and debug your code.

- [mw.Debug](mw.Debug.html)
- [mw.errorLogger](mw.errorLogger.html)
- [mw.log](mw.log.html)

### Upstream
- [OOjs](https://doc.wikimedia.org/oojs/master/index.html) — JavaScript library for working with objects
- [OOUI](https://doc.wikimedia.org/oojs-ui/master/js/) — component-based JavaScript UI library

## Contribute
- [Report a bug](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?projects=JSDoc-WMF-theme) using [Wikimedia Phabricator](https://www.mediawiki.org/wiki/Special:MyLanguage/Phabricator)
- [JSDoc WMF theme](https://gerrit.wikimedia.org/g/jsdoc/wmf-theme)
- [MediaWiki core](https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/core/)
