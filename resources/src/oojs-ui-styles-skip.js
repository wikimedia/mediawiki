/*!
 * Skip function for OOjs UI PHP style modules.
 *
 * The `<meta name="X-OOUI-PHP" />` is added to pages by OutputPage::enableOOUI().
 *
 * Looking for elements in the DOM might be expensive, but it's probably better than double-loading
 * 200 KB of CSS with embedded images because of bug T87871.
 */
return !!jQuery( 'meta[name="X-OOUI-PHP"]' ).length;
