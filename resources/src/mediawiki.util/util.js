'use strict';

let config = require( './config.json' );
const portletLinkOptions = require( './portletLinkOptions.json' );
const infinityValues = require( './infinityValues.json' );

require( './jquery.accessKeyLabel.js' );

/**
 * Encode the string like PHP's rawurlencode.
 *
 * @ignore
 * @param {string} str String to be encoded.
 * @return {string} Encoded string
 */
function rawurlencode( str ) {
	return encodeURIComponent( String( str ) )
		.replace( /!/g, '%21' )
		.replace( /'/g, '%27' )
		.replace( /\(/g, '%28' )
		.replace( /\)/g, '%29' )
		.replace( /\*/g, '%2A' )
		.replace( /~/g, '%7E' );
}

/**
 * Private helper function used by util.escapeId*()
 *
 * @ignore
 * @param {string} str String to be encoded
 * @param {string} mode Encoding mode, see documentation at
 *     MainConfigSchema::FragmentMode.
 * @return {string} Encoded string
 */
function escapeIdInternal( str, mode ) {
	str = String( str );

	switch ( mode ) {
		case 'html5':
			return str.replace( / /g, '_' );
		case 'legacy':
			return rawurlencode( str.replace( / /g, '_' ) )
				.replace( /%3A/g, ':' )
				.replace( /%/g, '.' );
		default:
			throw new Error( 'Unrecognized ID escaping mode ' + mode );
	}
}

/**
 * Library providing useful common skin-agnostic utility functions. Please see
 * [mediawiki.util]{@link module:mediawiki.util}.
 *
 * Alias for the [mediawiki.util]{@link module:mediawiki.util} module.
 *
 * @namespace mw.util
 */

/**
 * Utility library provided by the `mediawiki.util` ResourceLoader module. Accessible inside ResourceLoader modules
 * or for gadgets as part of the [mw global object]{@link mw}.
 *
 * @example
 * // Inside MediaWiki extensions
 * const util = require( 'mediawiki.util' );
 * // In gadgets
 * const mwUtil = mw.util;
 * @exports mediawiki.util
 */
const util = {

	/**
	 * Encode the string like PHP's rawurlencode.
	 *
	 * @method
	 * @param {string} str String to be encoded.
	 * @return {string} Encoded string
	 */
	rawurlencode: rawurlencode,

	/**
	 * Encode a string as CSS id, for use as HTML id attribute value.
	 *
	 * Analog to `Sanitizer::escapeIdForAttribute()` in PHP.
	 *
	 * @since 1.30
	 * @param {string} str String to encode
	 * @return {string} Encoded string
	 */
	escapeIdForAttribute( str ) {
		return escapeIdInternal( str, config.FragmentMode[ 0 ] );
	},

	/**
	 * Encode a string as URL fragment, for use as HTML anchor link.
	 *
	 * Analog to `Sanitizer::escapeIdForLink()` in PHP.
	 *
	 * @since 1.30
	 * @param {string} str String to encode
	 * @return {string} Encoded string
	 */
	escapeIdForLink( str ) {
		return escapeIdInternal( str, config.FragmentMode[ 0 ] );
	},

	/**
	 * Get the target element from a link hash.
	 *
	 * This is the same element as you would get from
	 * document.querySelectorAll(':target'), but can be used on
	 * an arbitrary hash fragment, or after pushState/replaceState
	 * has been used.
	 *
	 * Link fragments can be unencoded, fully encoded or partially
	 * encoded, as defined in the spec.
	 *
	 * We can't just use decodeURI as that assumes the fragment
	 * is fully encoded, and throws an error on a string like '%A',
	 * so we use the percent-decode.
	 *
	 * @param {string} [hash] Hash fragment, without the leading '#'.
	 *  Taken from location.hash if omitted.
	 * @return {HTMLElement|null} Element, if found
	 */
	getTargetFromFragment( hash ) {
		hash = hash || location.hash.slice( 1 );
		if ( !hash ) {
			// Firefox emits a console warning if you pass an empty string
			// to getElementById (T272844).
			return null;
		}
		// Per https://html.spec.whatwg.org/multipage/browsing-the-web.html#target-element
		// we try the raw fragment first, then the percent-decoded fragment.
		const element = document.getElementById( hash );
		if ( element ) {
			return element;
		}
		const decodedHash = this.percentDecodeFragment( hash );
		if ( !decodedHash ) {
			// decodedHash can return null, calling getElementById would cast it to a string
			return null;
		}
		return document.getElementById( decodedHash );
	},

	/**
	 * Percent-decode a string, as found in a URL hash fragment.
	 *
	 * Implements the percent-decode method as defined in
	 * https://url.spec.whatwg.org/#percent-decode.
	 *
	 * URLSearchParams implements https://url.spec.whatwg.org/#concept-urlencoded-parser
	 * which performs a '+' to ' ' substitution before running percent-decode.
	 *
	 * To get the desired behaviour we percent-encode any '+' in the fragment
	 * to effectively expose the percent-decode implementation.
	 *
	 * @param {string} text Text to decode
	 * @return {string|null} Decoded text, null if decoding failed
	 */
	percentDecodeFragment( text ) {
		const params = new URLSearchParams(
			'q=' +
			text
				// Query string param decoding replaces '+' with ' ' before doing the
				// percent_decode, so encode '+' to prevent this.
				.replace( /\+/g, '%2B' )
				// Query strings are split on '&' and then '=' so encode these too.
				.replace( /&/g, '%26' )
				.replace( /=/g, '%3D' )
		);
		return params.get( 'q' );
	},

	/**
	 * Return a function, that, as long as it continues to be invoked, will not
	 * be triggered. The function will be called after it stops being called for
	 * N milliseconds. If `immediate` is passed, trigger the function on the
	 * leading edge, instead of the trailing.
	 *
	 * Ported from Underscore.js 1.5.2, Copyright 2009-2013 Jeremy Ashkenas, DocumentCloud
	 * and Investigative Reporters & Editors, distributed under the MIT license, from
	 * <https://github.com/jashkenas/underscore/blob/1.5.2/underscore.js#L689>.
	 *
	 * @since 1.34
	 * @param {Function} func Function to debounce
	 * @param {number} [wait=0] Wait period in milliseconds
	 * @param {boolean} [immediate] Trigger on leading edge
	 * @return {Function} Debounced function
	 */
	debounce( func, wait, immediate ) {
		// Old signature (wait, func).
		if ( typeof func === 'number' ) {
			const tmpWait = wait;
			wait = func;
			func = tmpWait;
		}
		let timeout;
		return function () {
			const context = this,
				args = arguments,
				later = function () {
					timeout = null;
					if ( !immediate ) {
						func.apply( context, args );
					}
				};
			if ( immediate && !timeout ) {
				func.apply( context, args );
			}
			if ( !timeout || wait ) {
				clearTimeout( timeout );
				timeout = setTimeout( later, wait );
			}
		};
	},

	/**
	 * Return a function, that, when invoked, will only be triggered at most once
	 * during a given window of time. If called again during that window, it will
	 * wait until the window ends and then trigger itself again.
	 *
	 * As it's not knowable to the caller whether the function will actually run
	 * when the wrapper is called, return values from the function are entirely
	 * discarded.
	 *
	 * Ported from OOUI.
	 *
	 * @param {Function} func Function to throttle
	 * @param {number} wait Throttle window length, in milliseconds
	 * @return {Function} Throttled function
	 */
	throttle( func, wait ) {
		let context, args, timeout,
			previous = Date.now() - wait;
		const run = function () {
			timeout = null;
			previous = Date.now();
			func.apply( context, args );
		};
		return function () {
			// Check how long it's been since the last time the function was
			// called, and whether it's more or less than the requested throttle
			// period. If it's less, run the function immediately. If it's more,
			// set a timeout for the remaining time -- but don't replace an
			// existing timeout, since that'd indefinitely prolong the wait.
			const remaining = Math.max( wait - ( Date.now() - previous ), 0 );
			context = this;
			args = arguments;
			if ( !timeout ) {
				// If time is up, do setTimeout( run, 0 ) so the function
				// always runs asynchronously, just like Promise#then .
				timeout = setTimeout( run, remaining );
			}
		};
	},

	/**
	 * Encode page titles in a way that matches `wfUrlencode` in PHP.
	 *
	 * This is important both for readability and consistency in the user experience,
	 * as well as for caching. If URLs are not formatted in the canonical way, they
	 * may be subject to drastically shorter cache durations and/or miss automatic
	 * purging after edits, thus leading to stale content being served from a
	 * non-canonical URL.
	 *
	 * @method
	 * @param {string} str String to be encoded.
	 * @return {string} Encoded string
	 */
	wikiUrlencode: mw.internalWikiUrlencode,

	/**
	 * Get the URL to a given local wiki page name.
	 *
	 * @param {string|null} [pageName=wgPageName] Page name
	 * @param {Object} [params] A mapping of query parameter names to values,
	 *  e.g. `{ action: 'edit' }`
	 * @return {string} URL, relative to `wgServer`.
	 */
	getUrl( pageName, params ) {
		let url, query, fragment,
			title = typeof pageName === 'string' ? pageName : mw.config.get( 'wgPageName' );

		// Find any fragment
		const fragmentIdx = title.indexOf( '#' );
		if ( fragmentIdx !== -1 ) {
			fragment = title.slice( fragmentIdx + 1 );
			// Exclude the fragment from the page name
			title = title.slice( 0, fragmentIdx );
		}

		// Produce query string
		if ( params ) {
			query = $.param( params );
		}

		if ( !title && fragment ) {
			// If only a fragment was given, make a fragment-only link (T288415)
			url = '';
		} else if ( query ) {
			url = title ?
				util.wikiScript() + '?title=' + util.wikiUrlencode( title ) + '&' + query :
				util.wikiScript() + '?' + query;
		} else {
			// Specify a function as the replacement,
			// so that "$" characters in title are not interpreted.
			url = mw.config.get( 'wgArticlePath' )
				.replace( '$1', () => util.wikiUrlencode( title ) );
		}

		// Append the encoded fragment
		if ( fragment ) {
			url += '#' + util.escapeIdForLink( fragment );
		}

		return url;
	},

	/**
	 * Get URL to a MediaWiki entry point.
	 *
	 * Similar to `wfScript()` in PHP.
	 *
	 * @since 1.18
	 * @param {string} [str="index"] Name of entry point (e.g. 'index' or 'api')
	 * @return {string} URL to the script file (e.g. `/w/api.php`)
	 */
	wikiScript( str ) {
		if ( !str || str === 'index' ) {
			return mw.config.get( 'wgScript' );
		} else if ( str === 'load' ) {
			return config.LoadScript;
		} else {
			return mw.config.get( 'wgScriptPath' ) + '/' + str + '.php';
		}
	},

	/**
	 * Append a new style block to the head and return the CSSStyleSheet object.
	 *
	 * To access the `<style>` element, reference `sheet.ownerNode`, or call
	 * the {@link mw.loader.addStyleTag} method directly.
	 *
	 * This function returns the CSSStyleSheet object for convenience with features
	 * that are managed at that level, such as toggling of styles:
	 * ```
	 * var sheet = util.addCSS( '.foobar { display: none; }' );
	 * $( '#myButton' ).click( function () {
	 *     // Toggle the sheet on and off
	 *     sheet.disabled = !sheet.disabled;
	 * } );
	 * ```
	 *
	 * See also [MDN: CSSStyleSheet](https://developer.mozilla.org/en-US/docs/Web/API/CSSStyleSheet).
	 *
	 * @param {string} text CSS to be appended
	 * @return {CSSStyleSheet} The sheet object
	 */
	addCSS( text ) {
		const s = mw.loader.addStyleTag( text );
		return s.sheet;
	},

	/**
	 * Get the value for a given URL query parameter.
	 *
	 * @example
	 * mw.util.getParamValue( 'foo', '/?foo=x' ); // "x"
	 * mw.util.getParamValue( 'foo', '/?foo=' ); // ""
	 * mw.util.getParamValue( 'foo', '/' ); // null
	 *
	 * @param {string} param The parameter name.
	 * @param {string} [url=location.href] URL to search through, defaulting to the current browsing location.
	 * @return {string|null} Parameter value, or null if parameter was not found.
	 */
	getParamValue( param, url ) {
		// Get last match, stop at hash

		const re = new RegExp( '^[^#]*[&?]' + util.escapeRegExp( param ) + '=([^&#]*)' ),
			m = re.exec( url !== undefined ? url : location.href );

		if ( m ) {
			// Beware that decodeURIComponent is not required to understand '+'
			// by spec, as encodeURIComponent does not produce it.
			try {
				return decodeURIComponent( m[ 1 ].replace( /\+/g, '%20' ) );
			} catch ( e ) {
				// catch URIError if parameter is invalid UTF-8
				// due to malformed or double-decoded values (T106244),
				// e.g. "Autom%F3vil" instead of "Autom%C3%B3vil".
			}
		}
		return null;
	},

	/**
	 * Get the value for an array query parameter, combined according to similar rules as PHP uses.
	 * Currently this does not handle associative or multi-dimensional arrays, but that may be
	 * improved in the future.
	 *
	 * @example
	 * mw.util.getArrayParam( 'foo', new URLSearchParams( '?foo[0]=a&foo[1]=b' ) ); // [ 'a', 'b' ]
	 * mw.util.getArrayParam( 'foo', new URLSearchParams( '?foo[]=a&foo[]=b' ) ); // [ 'a', 'b' ]
	 * mw.util.getArrayParam( 'foo', new URLSearchParams( '?foo=a' ) ); // null
	 *
	 * @param {string} param The parameter name.
	 * @param {URLSearchParams} [params] Parsed URL parameters to search through, defaulting to the current browsing location.
	 * @return {string[]|null} Parameter value, or null if parameter was not found.
	 */
	getArrayParam( param, params ) {

		const paramRe = new RegExp( '^' + util.escapeRegExp( param ) + '\\[(\\d*)\\]$' );

		if ( !params ) {
			params = new URLSearchParams( location.search );
		}

		const arr = [];
		params.forEach( ( v, k ) => {
			const paramMatch = k.match( paramRe );
			if ( paramMatch ) {
				let i = paramMatch[ 1 ];
				if ( i === '' ) {
					// If no explicit index, append at the end
					i = arr.length;
				}
				arr[ i ] = v;
			}
		} );

		return arr.length ? arr : null;
	},

	/**
	 * The content wrapper of the skin (`.mw-body`, for example).
	 *
	 * Populated on document ready. To use this property,
	 * wait for `$.ready` and be sure to have a module dependency on
	 * `mediawiki.util` which will ensure
	 * your document ready handler fires after initialization.
	 *
	 * Because of the lazy-initialised nature of this property,
	 * you're discouraged from using it.
	 *
	 * If you need just the wikipage content (not any of the
	 * extra elements output by the skin), use `$( '#mw-content-text' )`
	 * instead. Or listen to {@link event:'wikipage.content' wikipage.content}
	 * which will allow your code to re-run when the page changes (e.g. live preview
	 * or re-render after ajax save).
	 *
	 * @type {jQuery}
	 */
	$content: null,

	/**
	 * Hide a portlet.
	 *
	 * @param {string} portletId ID of the target portlet (e.g. 'p-cactions' or 'p-personal')
	 */
	hidePortlet( portletId ) {
		const portlet = document.getElementById( portletId );
		if ( portlet ) {
			portlet.classList.add( 'emptyPortlet' );
		}
	},

	/**
	 * Whether a portlet is visible.
	 *
	 * @param {string} portletId ID of the target portlet (e.g. 'p-cactions' or 'p-personal')
	 * @return {boolean}
	 */
	isPortletVisible( portletId ) {
		const portlet = document.getElementById( portletId );
		return portlet && !portlet.classList.contains( 'emptyPortlet' );
	},

	/**
	 * Reveal a portlet if it is hidden.
	 *
	 * @param {string} portletId ID of the target portlet (e.g. 'p-cactions' or 'p-personal')
	 */
	showPortlet( portletId ) {
		const portlet = document.getElementById( portletId );
		if ( portlet ) {
			portlet.classList.remove( 'emptyPortlet' );
		}
	},

	/**
	 * Clears the entire subtitle if present in the page. Used for refreshing subtitle
	 * after edit with response from parse API.
	 */
	clearSubtitle() {
		const subtitle = document.getElementById( 'mw-content-subtitle' );
		if ( subtitle ) {
			subtitle.innerHTML = '';
		}
	},

	/**
	 * Create a message box element. Callers are responsible for ensuring suitable Codex styles
	 * have been added to the page e.g. mediawiki.codex.messagebox.styles.
	 *
	 * @since 1.43
	 * @param {string|Node} textOrElement text or node.
	 * @param {string} [type] defaults to notice.
	 * @param {boolean} [inline] whether the notice should be inline.
	 * @return {Element}
	 */
	messageBox( textOrElement, type = 'notice', inline = false ) {
		const msgBoxElement = document.createElement( 'div' );
		msgBoxElement.classList.add( 'cdx-message' );

		if ( [ 'error', 'warning', 'success', 'notice' ].includes( type ) ) {
			// The following CSS classes are used here:
			// * cdx-message--notice
			// * cdx-message--warning
			// * cdx-message--error
			// * cdx-message--success
			msgBoxElement.classList.add( `cdx-message--${ type }` );
		}
		msgBoxElement.classList.add( inline ? 'cdx-message--inline' : 'cdx-message--block' );

		if ( type === 'error' ) {
			msgBoxElement.setAttribute( 'role', 'alert' );
		} else {
			msgBoxElement.setAttribute( 'aria-live', 'polite' );
		}

		const iconElement = document.createElement( 'span' );
		iconElement.classList.add( 'cdx-message__icon' );
		const contentElement = document.createElement( 'div' );
		contentElement.classList.add( 'cdx-message__content' );
		if ( typeof textOrElement === 'string' ) {
			contentElement.textContent = textOrElement;
		} else {
			contentElement.appendChild( textOrElement );
		}
		msgBoxElement.appendChild( iconElement );
		msgBoxElement.appendChild( contentElement );
		return msgBoxElement;
	},

	/**
	 * Add content to the subtitle of the skin.
	 *
	 * @param {HTMLElement|string} nodeOrHTMLString
	 */
	addSubtitle( nodeOrHTMLString ) {
		const subtitle = document.getElementById( 'mw-content-subtitle' );
		if ( subtitle ) {
			if ( typeof nodeOrHTMLString === 'string' ) {
				subtitle.innerHTML += nodeOrHTMLString;
			} else {
				subtitle.appendChild( nodeOrHTMLString );
			}
		} else {
			throw new Error( 'This skin does not support additions to the subtitle.' );
		}
	},

	/**
	 * Creates a detached portlet Element in the skin with no elements.
	 *
	 * @example
	 * // Create a portlet with 2 menu items that is styled as a dropdown in certain skins.
	 * const p = mw.util.addPortlet( 'p-myportlet', 'My label', '#p-cactions' );
	 * mw.util.addPortletLink( 'p-myportlet', '#', 'Link 1' );
	 * mw.util.addPortletLink( 'p-myportlet', '#', 'Link 2' );
	 * @param {string} id of the new portlet.
	 * @param {string} [label] of the new portlet.
	 * @param {string} [selectorHint] selector of the element the new portlet would like to
	 *  be inserted near. Typically the portlet will be inserted after this selector, but in some
	 *  skins, the skin may relocate the element when provided to the closest available space.
	 *  If this argument is not passed then the caller is responsible for appending the element
	 *  to the DOM before using addPortletLink.
	 *  To add a portlet in an exact position do not rely on this parameter, instead using the return
	 *  element (make sure to also assign the result to a variable), use
	 *  ```p.parentNode.appendChild( p );```
	 *  When provided, skins can use the parameter to infer information about how the user intended
	 *  the menu to be rendered. For example, in vector and vector-2022 targeting '#p-cactions' will
	 *  result in the creation of a dropdown.
	 * @fires Hooks~'util.addPortlet'
	 * @return {HTMLElement|null} will be null if it was not possible to create an portlet with
	 *  the required information e.g. the selector given in `selectorHint` parameter could not be resolved
	 *  to an existing element in the page.
	 */
	addPortlet( id, label, selectorHint ) {
		const portlet = document.createElement( 'div' );
		// These classes should be kept in sync with includes/skins/components/SkinComponentMenu.php.
		// eslint-disable-next-line mediawiki/class-doc
		portlet.classList.add( 'mw-portlet', 'mw-portlet-' + id, 'emptyPortlet',
			// Additional class is added to allow skins to track portlets added via this mechanism.
			'mw-portlet-js'
		);
		portlet.id = id;
		if ( label ) {
			const labelNode = document.createElement( 'label' );
			labelNode.textContent = label;
			portlet.appendChild( labelNode );
		}
		const listWrapper = document.createElement( 'div' );
		const list = document.createElement( 'ul' );
		listWrapper.appendChild( list );
		portlet.appendChild( listWrapper );
		if ( selectorHint ) {
			let referenceNode;
			try {
				referenceNode = document.querySelector( selectorHint );
			} catch ( e ) {
				// CSS selector not supported by browser.
			}
			if ( referenceNode ) {
				const parentNode = referenceNode.parentNode;
				parentNode.insertBefore( portlet, referenceNode );
			} else {
				return null;
			}
		}
		/**
		 * Fires when a portlet is successfully created.
		 *
		 * @event ~'util.addPortlet'
		 * @memberof Hooks
		 * @param {HTMLElement} portlet the portlet that was created.
		 * @param {string|null} selectorHint the css selector used to append to the DOM.
		 *
		 * @example
		 * mw.hook( 'util.addPortlet' ).add( ( p ) => {
		 *     p.style.border = 'solid 1px black';
		 * } );
		 */
		mw.hook( 'util.addPortlet' ).fire( portlet, selectorHint );
		return portlet;
	},
	/**
	 * Add a link to a portlet menu on the page.
	 *
	 * The portlets that are supported include:
	 *
	 * - p-cactions (Content actions)
	 * - p-personal (Personal tools)
	 * - p-navigation (Navigation)
	 * - p-tb (Toolbox)
	 * - p-associated-pages (For namespaces and special page tabs on supported skins)
	 * - p-dock-bottom (A sticky menu fixed to bottom of viewport on supported skins)
	 * - p-namespaces (For namespaces on legacy skins)
	 *
	 * Additional menus can be discovered through the following code:
	 * ```$('.mw-portlet').toArray().map((el) => el.id);```
	 *
	 * Menu availability varies by skin, wiki, and current page.
	 *
	 * The first three parameters are required, the others are optional and
	 * may be null. Though providing an id and tooltip is recommended.
	 *
	 * By default, the new link will be added to the end of the menu. To
	 * add the link before an existing item, pass the DOM node or a CSS selector
	 * for that item, e.g. `'#foobar'` or `document.getElementById( 'foobar' )`.
	 * ```
	 * mw.util.addPortletLink(
	 *     'p-tb', 'https://www.mediawiki.org/',
	 *     'mediawiki.org', 't-mworg', 'Go to mediawiki.org', 'm', '#t-print'
	 * );
	 *
	 * var node = mw.util.addPortletLink(
	 *     'p-tb',
	 *     mw.util.getUrl( 'Special:Example' ),
	 *     'Example'
	 * );
	 * $( node ).on( 'click', function ( e ) {
	 *     console.log( 'Example' );
	 *     e.preventDefault();
	 * } );
	 * ```
	 *
	 * Remember that to call this inside a user script, you may have to ensure the
	 * `mediawiki.util` is loaded first:
	 * ```
	 * $.when( mw.loader.using( [ 'mediawiki.util' ] ), $.ready ).then( function () {
	 *      mw.util.addPortletLink( 'p-tb', 'https://www.mediawiki.org/', 'mediawiki.org' );
	 * } );
	 * ```
	 *
	 * @param {string} portletId ID of the target portlet (e.g. 'p-cactions' or 'p-personal')
	 * @param {string} href Link URL
	 * @param {string} text Link text
	 * @param {string} [id] ID of the list item, should be unique and preferably have
	 *  the appropriate prefix ('ca-', 'pt-', 'n-' or 't-')
	 * @param {string} [tooltip] Text to show when hovering over the link, without accesskey suffix
	 * @param {string} [accesskey] Access key to activate this link. One character only,
	 *  avoid conflicts with other links. Use `$( '[accesskey=x]' )` in the console to
	 *  see if 'x' is already used.
	 * @param {HTMLElement|jQuery|string} [nextnode] Element that the new item should be added before.
	 *  Must be another item in the same list, it will be ignored otherwise.
	 *  Can be specified as DOM reference, as jQuery object, or as CSS selector string.
	 * @fires Hooks~'util.addPortletLink'
	 * @return {HTMLElement|null} The added list item, or null if no element was added.
	 */
	addPortletLink( portletId, href, text, id, tooltip, accesskey, nextnode ) {
		if ( !portletId ) {
			// Avoid confusing id="undefined" lookup
			return null;
		}

		const portlet = document.getElementById( portletId );
		if ( !portlet ) {
			// Invalid portlet ID
			return null;
		}

		// Setup the anchor tag and set any the properties
		const link = document.createElement( 'a' );
		link.href = href;

		let linkChild = document.createTextNode( text );
		let i = portletLinkOptions[ 'text-wrapper' ].length;
		// Wrap link using text-wrapper option if provided
		// Iterate backward since the wrappers are declared from outer to inner,
		// and we build it up from the inside out.
		while ( i-- ) {
			const wrapper = portletLinkOptions[ 'text-wrapper' ][ i ];
			const wrapperElement = document.createElement( wrapper.tag );
			if ( wrapper.attributes ) {
				$( wrapperElement ).attr( wrapper.attributes );
			}
			wrapperElement.appendChild( linkChild );
			linkChild = wrapperElement;
		}
		link.appendChild( linkChild );

		if ( tooltip ) {
			link.title = tooltip;
		}
		if ( accesskey ) {
			link.accessKey = accesskey;
		}

		// Unhide portlet if it was hidden before
		util.showPortlet( portletId );

		const item = $( '<li>' ).append( link )[ 0 ];
		// mw-list-item-js distinguishes portlet links added via javascript and the server
		item.className = 'mw-list-item mw-list-item-js';
		if ( id ) {
			item.id = id;
		}

		// Select the first (most likely only) unordered list inside the portlet
		let ul = portlet.tagName.toLowerCase() === 'ul' ? portlet : portlet.querySelector( 'ul' );
		if ( !ul ) {
			// If it didn't have an unordered list yet, create one
			ul = document.createElement( 'ul' );
			const portletDiv = portlet.querySelector( 'div' );
			if ( portletDiv ) {
				// Support: Legacy skins have a div (such as div.body or div.pBody).
				// Append the <ul> to that.
				portletDiv.appendChild( ul );
			} else {
				// Append it to the portlet directly
				portlet.appendChild( ul );
			}
		}

		let next;
		if ( nextnode && ( typeof nextnode === 'string' || nextnode.nodeType || nextnode.jquery ) ) {
			// eslint-disable-next-line no-jquery/variable-pattern
			nextnode = $( ul ).find( nextnode );
			if ( nextnode.length === 1 && nextnode[ 0 ].parentNode === ul ) {
				// Insertion point: Before nextnode
				nextnode.before( item );
				next = true;
			}
			// Else: Invalid nextnode value (no match, more than one match, or not a direct child)
			// Else: Invalid nextnode type
		}

		if ( !next ) {
			// Insertion point: End of list (default)
			ul.appendChild( item );
		}

		// Update tooltip for the access key after inserting into DOM
		// to get a localized access key label (T69946).
		if ( accesskey ) {
			$( link ).updateTooltipAccessKeys();
		}

		/**
		 * Fires when a portlet link is successfully created.
		 *
		 * @event ~'util.addPortletLink'
		 * @memberof Hooks
		 * @param {HTMLElement} item the portlet link that was created.
		 * @param {Object} information about the item include id.
		 *
		 * @example
		 * mw.hook( 'util.addPortletLink' ).add( ( link ) => {
		 *     const span = $( '<span class="icon">' );
		 *     link.appendChild( span );
		 * } );
		 */
		mw.hook( 'util.addPortletLink' ).fire( item, {
			id: id
		} );
		return item;
	},

	/**
	 * Validate a string as representing a valid e-mail address.
	 *
	 * This validation is based on the HTML5 specification.
	 *
	 * @example
	 * mw.util.validateEmail( "me@example.org" ) === true;
	 *
	 * @param {string} email E-mail address
	 * @return {boolean|null} True if valid, false if invalid, null if `email` was empty.
	 */
	validateEmail( email ) {
		if ( email === '' ) {
			return null;
		}

		// HTML5 defines a string as valid e-mail address if it matches
		// the ABNF:
		//     1 * ( atext / "." ) "@" ldh-str 1*( "." ldh-str )
		// With:
		// - atext   : defined in RFC 5322 section 3.2.3
		// - ldh-str : defined in RFC 1034 section 3.5
		//
		// (see STD 68 / RFC 5234 https://tools.ietf.org/html/std68)
		// First, define the RFC 5322 'atext' which is pretty easy:
		// atext = ALPHA / DIGIT / ; Printable US-ASCII
		//     "!" / "#" /    ; characters not including
		//     "$" / "%" /    ; specials. Used for atoms.
		//     "&" / "'" /
		//     "*" / "+" /
		//     "-" / "/" /
		//     "=" / "?" /
		//     "^" / "_" /
		//     "`" / "{" /
		//     "|" / "}" /
		//     "~"
		const rfc5322Atext = 'a-z0-9!#$%&\'*+\\-/=?^_`{|}~';

		// Next define the RFC 1034 'ldh-str'
		//     <domain> ::= <subdomain> | " "
		//     <subdomain> ::= <label> | <subdomain> "." <label>
		//     <label> ::= <letter> [ [ <ldh-str> ] <let-dig> ]
		//     <ldh-str> ::= <let-dig-hyp> | <let-dig-hyp> <ldh-str>
		//     <let-dig-hyp> ::= <let-dig> | "-"
		//     <let-dig> ::= <letter> | <digit>
		const rfc1034LdhStr = 'a-z0-9\\-';

		const html5EmailRegexp = new RegExp(
			// start of string
			'^' +
			// User part which is liberal :p
			'[' + rfc5322Atext + '\\.]+' +
			// 'at'
			'@' +
			// Domain first part
			'[' + rfc1034LdhStr + ']+' +
			// Optional second part and following are separated by a dot
			'(?:\\.[' + rfc1034LdhStr + ']+)*' +
			// End of string
			'$',
			// RegExp is case insensitive
			'i'
		);
		return ( email.match( html5EmailRegexp ) !== null );
	},

	/**
	 * Whether a string is a valid IPv4 address or not.
	 *
	 * Based on \Wikimedia\IPUtils::isIPv4 in PHP.
	 *
	 * @example
	 * // Valid
	 * mw.util.isIPv4Address( '80.100.20.101' );
	 * mw.util.isIPv4Address( '192.168.1.101' );
	 *
	 * // Invalid
	 * mw.util.isIPv4Address( '192.0.2.0/24' );
	 * mw.util.isIPv4Address( 'hello' );
	 *
	 * @param {string} address
	 * @param {boolean} [allowBlock=false]
	 * @return {boolean}
	 */
	isIPv4Address( address, allowBlock ) {

		if ( typeof address !== 'string' ) {
			return false;
		}

		const RE_IP_BYTE = '(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|0?[0-9]?[0-9])';
		const RE_IP_ADD = '(?:' + RE_IP_BYTE + '\\.){3}' + RE_IP_BYTE;
		const block = allowBlock ? '(?:\\/(?:3[0-2]|[12]?\\d))?' : '';

		return ( new RegExp( '^' + RE_IP_ADD + block + '$' ).test( address ) );
	},

	/**
	 * Whether a string is a valid IPv6 address or not.
	 *
	 * Based on \Wikimedia\IPUtils::isIPv6 in PHP.
	 *
	 * @example
	 * // Valid
	 * mw.util.isIPv6Address( '2001:db8:a:0:0:0:0:0' );
	 * mw.util.isIPv6Address( '2001:db8:a::' );
	 *
	 * // Invalid
	 * mw.util.isIPv6Address( '2001:db8:a::/32' );
	 * mw.util.isIPv6Address( 'hello' );
	 *
	 * @param {string} address
	 * @param {boolean} [allowBlock=false]
	 * @return {boolean}
	 */
	isIPv6Address( address, allowBlock ) {
		if ( typeof address !== 'string' ) {
			return false;
		}

		const block = allowBlock ? '(?:\\/(?:12[0-8]|1[01][0-9]|[1-9]?\\d))?' : '';
		let RE_IPV6_ADD =
			'(?:' + // starts with "::" (including "::")
				':(?::|(?::' +
					'[0-9A-Fa-f]{1,4}' +
				'){1,7})' +
				'|' + // ends with "::" (except "::")
				'[0-9A-Fa-f]{1,4}' +
				'(?::' +
					'[0-9A-Fa-f]{1,4}' +
				'){0,6}::' +
				'|' + // contains no "::"
				'[0-9A-Fa-f]{1,4}' +
				'(?::' +
					'[0-9A-Fa-f]{1,4}' +
				'){7}' +
			')';

		if ( new RegExp( '^' + RE_IPV6_ADD + block + '$' ).test( address ) ) {
			return true;
		}

		// contains one "::" in the middle (single '::' check below)
		RE_IPV6_ADD =
			'[0-9A-Fa-f]{1,4}' +
			'(?:::?' +
				'[0-9A-Fa-f]{1,4}' +
			'){1,6}';

		return (

			new RegExp( '^' + RE_IPV6_ADD + block + '$' ).test( address ) &&
			/::/.test( address ) &&
			!/::.*::/.test( address )
		);
	},

	/**
	 * Check whether a string is a valid IP address.
	 *
	 * @since 1.25
	 * @param {string} address String to check
	 * @param {boolean} [allowBlock=false] If a block of IPs should be allowed
	 * @return {boolean}
	 */
	isIPAddress( address, allowBlock ) {
		return util.isIPv4Address( address, allowBlock ) ||
			util.isIPv6Address( address, allowBlock );
	},

	/**
	 * @typedef {Object} ResizeableThumbnailUrl
	 * @property {string} name File name (same format as Title.getMainText()).
	 * @property {number} [width] Thumbnail width, in pixels. Null when the file is not
	 *   a thumbnail.
	 * @property {function(number):string} [resizeUrl] A function that takes a width
	 *   parameter and returns a thumbnail URL (URL-encoded) with that width. The width
	 *   parameter must be smaller than the width of the original image (or equal to it; that
	 *   only works if MediaHandler::mustRender returns true for the file). Null when the
	 *   file in the original URL is not a thumbnail.
	 *   On wikis with $wgGenerateThumbnailOnParse set to true, this will fall back to using
	 *   Special:Redirect which is less efficient. Otherwise, it is a direct thumbnail URL.
	 */

	/**
	 * Parse the URL of an image uploaded to MediaWiki, or a thumbnail for such an image,
	 * and return the image name, thumbnail size and a template that can be used to resize
	 * the image.
	 *
	 * @param {string} url URL to parse (URL-encoded)
	 * @return {ResizeableThumbnailUrl|null} null if the URL is not a valid MediaWiki
	 *   image/thumbnail URL.
	 */
	parseImageUrl( url ) {
		let name, decodedName, width, urlTemplate;

		// thumb.php-generated thumbnails
		// thumb.php?f=<name>&w[idth]=<width>[px]
		if ( /thumb\.php/.test( url ) ) {
			decodedName = mw.util.getParamValue( 'f', url );
			name = encodeURIComponent( decodedName );
			width = mw.util.getParamValue( 'width', url ) || mw.util.getParamValue( 'w', url );
			urlTemplate = url.replace( /([&?])w(?:idth)?=[^&]+/g, '' ) + '&width={width}';
		} else {
			const regexes = [
				// Thumbnails
				// /<hash prefix>/<name>/[<options>-]<width>-<name*>[.<ext>]
				// where <name*> could be the filename, 'thumbnail.<ext>' (for long filenames)
				// or the base-36 SHA1 of the filename.

				/\/[\da-f]\/[\da-f]{2}\/([^\s/]+)\/(?:[^\s/]+-)?(\d+)px-(?:\1|thumbnail|[a-z\d]{31})(\.[^\s/]+)?$/,

				// Full size images
				// /<hash prefix>/<name>
				/\/[\da-f]\/[\da-f]{2}\/([^\s/]+)$/,

				// Thumbnails in non-hashed upload directories
				// /<name>/[<options>-]<width>-<name*>[.<ext>]

				/\/([^\s/]+)\/(?:[^\s/]+-)?(\d+)px-(?:\1|thumbnail|[a-z\d]{31})[^\s/]*$/,

				// Full-size images in non-hashed upload directories
				// /<name>
				/\/([^\s/]+)$/
			];
			for ( let i = 0; i < regexes.length; i++ ) {
				const match = url.match( regexes[ i ] );
				if ( match ) {
					name = match[ 1 ];
					decodedName = decodeURIComponent( name );
					width = match[ 2 ] || null;
					break;
				}
			}
		}

		if ( name ) {
			if ( width !== null ) {
				width = parseInt( width, 10 ) || null;
			}
			if ( config.GenerateThumbnailOnParse ) {
				// The wiki cannot generate thumbnails on demand. Use a special page - this means
				// an extra redirect and PHP request, but it will generate the thumbnail if it does
				// not exist.
				urlTemplate = mw.util.getUrl( 'Special:Redirect/file/' + decodedName, { width: '{width}' } )
					// getUrl urlencodes the template variable, fix that
					.replace( '%7Bwidth%7D', '{width}' );
			} else if ( width && !urlTemplate ) {
				// Javascript does not expose regexp capturing group indexes, and the width
				// part could in theory also occur in the filename so hide that first.
				const strippedUrl = url.replace( name, '{name}' )
					.replace( name, '{name}' )
					.replace( width + 'px-', '{width}px-' );
				urlTemplate = strippedUrl.replace( /\{name\}/g, name );
			}
			return {
				name: decodedName.replace( /_/g, ' ' ),
				width,
				resizeUrl: urlTemplate ? ( w ) => urlTemplate.replace( '{width}', w ) : null
			};
		}
		return null;
	},

	/**
	 * Escape string for safe inclusion in regular expression.
	 *
	 * The following characters are escaped:
	 *
	 *     \ { } ( ) | . ? * + - ^ $ [ ]
	 *
	 * @since 1.26; moved to mw.util in 1.34
	 * @param {string} str String to escape
	 * @return {string} Escaped string
	 */
	escapeRegExp( str ) {
		// eslint-disable-next-line no-useless-escape
		return str.replace( /([\\{}()|.?*+\-^$\[\]])/g, '\\$1' );
	},

	/**
	 * Convert an IP into a verbose, uppercase, normalized form.
	 *
	 * Both IPv4 and IPv6 addresses are trimmed. Additionally,
	 * IPv6 addresses in octet notation are expanded to 8 words;
	 * IPv4 addresses have leading zeros, in each octet, removed.
	 *
	 * This functionality has been adapted from \Wikimedia\IPUtils::sanitizeIP()
	 *
	 * @param {string} ip IP address in quad or octet form (CIDR or not).
	 * @return {string|null}
	 */
	sanitizeIP( ip ) {
		if ( typeof ip !== 'string' ) {
			return null;
		}
		ip = ip.trim();
		if ( ip === '' ) {
			return null;
		}
		if ( !this.isIPAddress( ip, true ) ) {
			return ip;
		}
		if ( this.isIPv4Address( ip, true ) ) {
			return ip.replace( /(^|\.)0+(\d)/g, '$1$2' );
		}
		ip = ip.toUpperCase();
		const abbrevPos = ip.indexOf( '::' );
		if ( abbrevPos !== -1 ) {
			const CIDRStart = ip.indexOf( '/' );
			const addressEnd = ( CIDRStart !== -1 ) ? CIDRStart - 1 : ip.length - 1;
			let repeatStr, extra, pad;
			if ( abbrevPos === 0 ) {
				repeatStr = '0:';
				extra = ip === '::' ? '0' : '';
				pad = 9;
			} else if ( abbrevPos === addressEnd - 1 ) {
				repeatStr = ':0';
				extra = '';
				pad = 9;
			} else {
				repeatStr = ':0';
				extra = ':';
				pad = 8;
			}
			const count = pad - ( ip.split( ':' ).length - 1 );
			ip = ip.replace( '::', repeatStr.repeat( count ) + extra );
		}
		return ip.replace( /(^|:)0+(([0-9A-Fa-f]{1,4}))/g, '$1$2' );
	},

	/**
	 * Prettify an IP for display to end users.
	 *
	 * This will make it more compact and lower-case.
	 *
	 * This functionality has been adapted from \Wikimedia\IPUtils::prettifyIP()
	 *
	 * @param {string} ip IP address in quad or octet form (CIDR or not).
	 * @return {string|null}
	 */
	prettifyIP( ip ) {
		ip = this.sanitizeIP( ip );
		if ( ip === null ) {
			return null;
		}
		if ( this.isIPv6Address( ip, true ) ) {
			let cidr, replaceZeros;
			if ( ip.includes( '/' ) ) {
				const ipCidrSplit = ip.split( '/', 2 );
				ip = ipCidrSplit[ 0 ];
				cidr = ipCidrSplit[ 1 ];
			} else {
				cidr = '';
			}
			const matches = ip.match( /(?:^|:)0(?::0)+(?:$|:)/g );
			if ( matches ) {
				replaceZeros = matches[ 0 ];
				for ( let i = 1; i < matches.length; i++ ) {
					if ( matches[ i ].length > replaceZeros.length ) {
						replaceZeros = matches[ i ];
					}
				}
			}
			ip = ip.replace( replaceZeros, '::' );

			if ( cidr !== '' ) {
				ip = ip.concat( '/', cidr );
			}
			ip = ip.toLowerCase();
		}
		return ip;
	},

	/**
	 * Checks if the given username matches $wgAutoCreateTempUser.
	 *
	 * This functionality has been adapted from MediaWiki\User\TempUser\Pattern::isMatch()
	 *
	 * @param {string|null} username
	 * @return {boolean}
	 */
	isTemporaryUser( username ) {
		// Just return early if temporary accounts are not known about.
		if ( !config.AutoCreateTempUser.enabled && !config.AutoCreateTempUser.known ) {
			return false;
		}
		if ( username === null ) {
			return false;
		}
		/** @type {string|string[]} */
		let matchPatterns = config.AutoCreateTempUser.matchPattern;
		if ( typeof matchPatterns === 'string' ) {
			matchPatterns = [ matchPatterns ];
		} else if ( matchPatterns === null ) {
			matchPatterns = [ config.AutoCreateTempUser.genPattern ];
		}
		for ( let i = 0; i < matchPatterns.length; i++ ) {
			const autoCreateUserMatchPattern = matchPatterns[ i ];
			// Check each match pattern, and if any matches then return a match.
			const position = autoCreateUserMatchPattern.indexOf( '$1' );

			// '$1' was not found in autoCreateUserMatchPattern
			if ( position === -1 ) {
				return false;
			}
			const prefix = autoCreateUserMatchPattern.slice( 0, position );
			const suffix = autoCreateUserMatchPattern.slice( position + '$1'.length );

			let match = true;
			if ( prefix !== '' ) {
				match = username.startsWith( prefix );
			}
			if ( match && suffix !== '' ) {
				match = ( username.endsWith( suffix ) ) &&
					( username.length >= prefix.length + suffix.length );
			}
			if ( match ) {
				return true;
			}
		}
		// No match patterns matched the username, so the given username is not a temporary user.
		return false;
	},

	/**
	 * Determine if an input string represents a value of infinity.
	 * This is used when testing for infinity in the context of expiries,
	 * such as watchlisting, page protection, and block expiries.
	 *
	 * @param {string|null} str
	 * @return {boolean}
	 * @stable
	 */
	isInfinity( str ) {
		return infinityValues.includes( str );
	}
};

/**
 * Initialisation of mw.util.$content
 *
 * @ignore
 */
function init() {
	// The preferred standard is class "mw-body".
	// You may also use class "mw-body mw-body-primary" if you use
	// mw-body in multiple locations. Or class "mw-body-primary" if
	// you use mw-body deeper in the DOM.
	const content = document.querySelector( '.mw-body-primary' ) ||
		document.querySelector( '.mw-body' ) ||
		// If the skin has no such class, fall back to the parser output
		document.querySelector( '#mw-content-text' ) ||
		// Should never happen..., except if the skin is still in development.
		document.body;

	util.$content = $( content );
}

// Backwards-compatible alias for mediawiki.RegExp module.
// @deprecated since 1.34
mw.RegExp = {};
mw.log.deprecate( mw.RegExp, 'escape', util.escapeRegExp, 'Use mw.util.escapeRegExp() instead.', 'mw.RegExp.escape' );

if ( window.QUnit ) {
	// Not allowed outside unit tests
	util.setOptionsForTest = function ( opts ) {
		config = !opts ? require( './config.json' ) : Object.assign( {}, config, opts );
	};
	util.init = init;
} else {
	$( init );
}

mw.util = util;
module.exports = util;
