/**
 * The purpose of this file is to aid the generation of our jsdoc documentation.
 * It documents global JSDoc types. It is never shipped in production.
 * @ignore
 */

/**
 * The following commonly used hooks are documented here temporarily.
 * TODO: Move hook documentation to mediawiki.page.ready.js when that is migrated to JSDoc.
 */

/**
 * Fired when wiki content has been added to the DOM.
 *
 * This should only be fired after $content has been attached.
 *
 * This includes the ready event on a page load (including post-edit loads)
 * and when content has been previewed with LivePreview.
 *
 * @event ~'wikipage.content'
 * @memberof Hooks
 * @param {jQuery} $content The most appropriate element containing the content, such as
 *   `#mw-content-text` (regular content root) or `#wikiPreview` (live preview root)
 */

/**
 * Fired when categories are being added to the DOM
 *
 * It is encouraged to fire it before the main DOM is changed (when `$content`
 * is still detached).  However, this order is not defined either way, so you
 * should only rely on `$content` itself.
 *
 * This includes the ready event on a page load (including post-edit loads)
 * and when content has been previewed with LivePreview.
 *
 * @event ~'wikipage.categories'
 * @memberof Hooks
 * @param {jQuery} $content The most appropriate element containing the content, such as `.catlinks`
 */

/**
 * Fired when a trusted UI element to perform a logout has been activated.
 *
 * This will end the user session, and either redirect to the given URL
 * on success, or queue an error message via {@link mw.notification}.
 *
 * @event ~'skin.logout'
 * @memberof Hooks
 * @param {string} href Full URL
 */

/**
 * MediaWiki includes the jQuery library. This is extended by a series of plugins.
 *
 * This page documents the list of jQuery plugins that can be used on a jQuery object. When using them
 * please pay attention to their installation instructions.
 *
 * @namespace jQueryPlugins
 */

/**
 * The global window object.
 *
 * @namespace window
 */
