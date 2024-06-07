# Globals

## [mw](mw.html)

The `mw` object is the historical entry point for MediaWiki's public-facing frontend API. In some cases, APIs are available on the `mw` object in addition to inside a [module](modules.html).
Generally, the use of a module is recommended where possible as it is more robust to changes in how code is organized.

## [Hooks](Hooks.html)

The [`mw.hook`](mw.html#.hook) method provides an API to run JavaScript code when certain events occur.
For example, you can use a hook to run code after a page is edited.
For a list of stable hooks provided by MediaWiki core, see [Hooks](Hooks.html).

Extensions and skins can also add hooks. See the documentation for those codebases for more information on the hooks they provide.

## [window](window.html)

Historical methods defined on the window object.
