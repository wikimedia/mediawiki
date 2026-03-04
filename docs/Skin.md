Skins {#skin}
=======

## Core Skins

MediaWiki bundles five skins:

* Vector 2022: The default skin. A modernised version of the Vector skin, with improved
  accessibility and mobile support. Introduced in the 1.36 release (2021), it has been the
  default in MediaWiki since 1.37 (2022).
* Vector (legacy): Introduced in the 1.16 release (2010), it had been the default skin since
  MediaWiki 1.17 (2011) until the introduction of Vector 2022. It was designed to be more
  user-friendly and visually appealing than the previous default, MonoBook.
* MinervaNeue: A mobile-optimized skin, introduced in the 1.19 release (2012). It is designed
  for mobile devices, but can also be used on desktop.
* Timeless: A responsive skin for different screen sizes, equally focussed on reading and editing
  interfaces, introduced in the 1.31 release (2018).
* MonoBook: Named after the black-and-white photo of a book in the page background. Introduced
  in the 1.3 release (2004), it had been the default skin since then, before being replaced
  by Vector.


## Custom CSS/JS

It is possible to customise the site CSS and JavaScript without editing any
server-side source files. This is done by editing some pages on the wiki:

* `MediaWiki:Common.css` for skin-independent CSS
* `MediaWiki:Common.js` for skin-independent JavaScript
* `MediaWiki:Vector.css`, `MediaWiki:Minerva.css`, etc. for skin-dependent CSS
* `MediaWiki:Vector.js`, `MediaWiki:Minerva.js`, etc. for skin-dependent
  JavaScript

These can also be customised on a per-user basis, by editing
`User:<name>/vector.css`, `User:<name>/vector.js`, etc.


## Custom skins

Several custom skins are available as of 2019. List of all skins is available at
[MediaWiki.org](https://www.mediawiki.org/wiki/Special:MyLanguage/Category:All_skins).

Installing a skin requires adding its files in a subdirectory under `skins/` and
adding an appropriate `wfLoadSkin` line to `LocalSettings.php`, similarly to
how extensions are installed.

You can then make that skin the default by adding:

```php
$wgDefaultSkin = '<name>';
```


Or disable it entirely by removing the `wfLoadSkin` line. (User settings will
not be lost if it's reenabled later.)

See https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Skinning for more
information on writing new skins.
