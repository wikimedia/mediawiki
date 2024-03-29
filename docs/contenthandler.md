ContentHandler {#contenthandler}
=====

The *ContentHandler* facility adds support for arbitrary content types on wiki pages, instead of relying on wikitext for everything. It was introduced in MediaWiki 1.21.

Each kind of content ("content model") supported by MediaWiki is identified by unique name. The content model determines how a page's content is rendered, compared, stored, edited, and so on.

Built-in content types are:

* wikitext - wikitext, as usual
* javascript - user provided javascript code
* json - simple implementation for use by extensions, etc.
* css - user provided css code
* text - plain text

In PHP, use the corresponding `CONTENT_MODEL_XXX` constant.

A page's content model is available using the `Title::getContentModel()` method. A page's default model is determined by `ContentHandler::getDefaultModelFor($title)` as follows:

* The global setting `$wgNamespaceContentModels` specifies a content model for the given namespace.
* The hook `ContentHandlerDefaultModelFor` may be used to override the page's default model.
* Pages in `NS_MEDIAWIKI` and `NS_USER` default to the CSS or JavaScript model if they end in .css or .js, respectively. Pages in `NS_MEDIAWIKI` default to the wikitext model otherwise.
* Otherwise, the wikitext model is used.

Note that there is no guarantee that revisions of a page will all have the same content model. To find the content model of the slot of a revision, use `SlotRecord::getModel()` -
the content model of the main slot can for now be assumed to be the content model for the overall revision.

## Architecture

Two class hierarchies are used to provide the functionality associated with the different content models:

* Content interface (and `AbstractContent` base class) define functionality that acts on the concrete content of a page, and
* `ContentHandler` base class provides functionality specific to a content model, but not acting on concrete content.

The most important function of ContentHandler is to act as a factory for the appropriate implementation of Content. These `Content` objects are to be used by MediaWiki everywhere, instead of passing page content around as text. All manipulation and analysis of page content must be done via the appropriate methods of the Content object.

For each content model, a subclass of ContentHandler has to be registered with `$wgContentHandlers`. The ContentHandler object for a given content model can be obtained using `ContentHandler::getForModelID( $id )`. Also `Title` and `WikiPage` now have `getContentHandler()` methods for convenience.

`ContentHandler` objects are singletons that provide functionality specific to the content type, but not directly acting on the content of some page. `ContentHandler::makeEmptyContent()` and `ContentHandler::unserializeContent()` can be used to create a Content object of the appropriate type. However, it is recommended to instead use `WikiPage::getContent()` resp. `RevisionRecord::getContent()` to get a page's content as a Content object. These two methods should be the ONLY way in which page content is accessed.
For `WikiPage::getContent()` the content of the main slot is returned, other slots can be retrieved by using `RevisionRecord::getContent()` and specifying the slot.

Another important function of ContentHandler objects is to define custom action handlers for a content model, see `ContentHandler::getActionOverrides()`. This is similar to what `WikiPage::getActionOverrides()` was already doing.

## Serialization

With the ContentHandler facility, page content no longer has to be text based. Objects implementing the Content interface are used to represent and handle the content internally. For storage and data exchange, each content model supports at least one serialization format via `ContentHandler::serializeContent( $content )`. The list of supported formats for a given content model can be accessed using `ContentHandler::getSupportedFormats()`.

Content serialization formats are identified using MIME type like strings. The following formats are built in:

* text/x-wiki - wikitext
* text/javascript - for js pages
* text/css - for css pages
* text/plain - for future use, e.g. with plain text messages.
* text/html - for future use, e.g. with plain html messages.
* application/vnd.php.serialized - for future use with the api and for extensions
* application/json - for future use with the api, and for use by extensions
* application/xml - for future use with the api, and for use by extensions

In PHP, use the corresponding `CONTENT_FORMAT_XXX` constant.

Note that when using the API to access page content, especially `action=edit`, `action=parse` and `action=query&prop=revisions`, the model and format of the content should always be handled explicitly. Without that information, interpretation of the provided content is not reliable. The same applies to XML dumps generated via `maintenance/dumpBackup.php` or `Special:Export`.

Also note that the API will provide encapsulated, serialized content - so if the API was called with `format=json`, and contentformat is also json (or rather, application/json), the page content is represented as a string containing an escaped json structure. Extensions that use JSON to serialize some types of page content may provide specialized API modules that allow access to that content in a more natural form.

## Compatibility

The ContentHandler facility is introduced in a way that should allow all existing code to keep functioning at least for pages that contain wikitext or other text based content. However, a number of functions and hooks have been deprecated in favor of new versions that are aware of the page's content model, and will now generate warnings when used.

Most importantly, the following functions have been deprecated:

* `Revision::getText()` was deprecated in favor of `Revision::getContent()` (though the Revision class was later fully removed as part of the migration to Multi-Content Revisions (MCR), see [documentation of mediawiki.org][mediawiki.org/wiki/Multi-Content_Revisions].
* `WikiPage::getText()` is deprecated in favor of `WikiPage::getContent()`

Also, the old `Article::getContent()` (which returns text) is superceded by `Article::getContentObject()`. However, both methods should be avoided since they do not provide clean access to the page's actual content. For instance, they may return a system message for non-existing pages. Use `WikiPage::getContent()` instead.

Code that relies on a textual representation of the page content should eventually be rewritten. However, `ContentHandler::getContentText()` provides a stop-gap that can be used to get text for a page. Its behavior is controlled by `$wgContentHandlerTextFallback`; per default it will return the text for text based content, and null for any other content.

For rendering page content, `Content::getParserOutput()` should be used instead of accessing the parser directly. `WikiPage::makeParserOptions()` can be used to construct appropriate options.

Besides some functions, some hooks have also been replaced by new versions (see hooks.txt for details). These hooks will now trigger a warning when used:

* `ArticleAfterFetchContent` was replaced by `ArticleAfterFetchContentObject`, later replaced by `ArticleRevisionViewCustom`
* `ArticleInsertComplete` was replaced by `PageContentInsertComplete`, later replaced by `PageSaveComplete`
* `ArticleSave` was replaced by `PageContentSave`
* `ArticleSaveComplete` was replaced by `PageContentSaveComplete`, later replaced by `PageSaveComplete`
* `ArticleViewCustom` was replaced by `ArticleContentViewCustom`, which was later removed entirely
* `EditFilterMerged` was replaced by `EditFilterMergedContent`
* `EditPageGetDiffText` was replaced by `EditPageGetDiffContent`
* `EditPageGetPreviewText` was replaced by `EditPageGetPreviewContent`
* `ShowRawCssJs` was deprecated in favor of custom rendering implemented in the respective ContentHandler object.

## Database Storage

Page content is stored in the database using the same mechanism as before. Non-text content is serialized first.

Each revision's content model and serialization format is stored in the revision table (resp. in the archive table, if the revision was deleted). The page's (current) content model (that is, the content model of the latest revision) is also stored in the page table.

Note however that the content model and format is only stored if it differs from the page's default, as determined by `ContentHandler::getDefaultModelFor( $title )`. The default values are represented as `NULL` in the database, to preserve space.

## Globals

There are some new globals that can be used to control the behavior of the ContentHandler facility:

* `$wgContentHandlers` associates content model IDs with the names of the appropriate ContentHandler subclasses or callbacks that create an instance of the appropriate ContentHandler subclass.

* `$wgNamespaceContentModels` maps namespace IDs to a content model that should be the default for that namespace.

* `$wgContentHandlerTextFallback` determines how the compatibility method `ContentHandler::getContentText()` will behave for non-text content:
    * `'ignore'` causes null to be returned for non-text content (default).
    * `'serialize'` causes the serialized form of any non-text content to be returned (scary).
    * `'fail'` causes an exception to be thrown for non-text content (strict).

## Caveats

There are some changes in behavior that might be surprising to users:

* Javascript and CSS pages are no longer parsed as wikitext (though pre-save transform is still applied). Most importantly, this means that links, including categorization links, contained in the code will not work.

* `action=edit` will fail for pages with non-text content, unless the respective ContentHandler implementation has provided a specialized handler for the edit action. This is true for the API as well.

* `action=raw` will fail for all non-text content. This seems better than serving content in other formats to an unsuspecting recipient. This will also cause client-side diffs to fail.

* File pages provide their own action overrides that do not combine gracefully with any custom handlers defined by a ContentHandler. If for example a File page used a content model with a custom revert action, this would be overridden by WikiFilePage's handler for the revert action.
