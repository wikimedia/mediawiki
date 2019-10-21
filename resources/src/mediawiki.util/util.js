'use strict';

var util,
	config = require( './config.json' );

require( './jquery.accessKeyLabel.js' );

/**
 * Encode the string like PHP's rawurlencode
 * @ignore
 *
 * @param {string} str String to be encoded.
 * @return {string} Encoded string
 */
function rawurlencode( str ) {
	return encodeURIComponent( String( str ) )
		.replace( /!/g, '%21' ).replace( /'/g, '%27' ).replace( /\(/g, '%28' )
		.replace( /\)/g, '%29' ).replace( /\*/g, '%2A' ).replace( /~/g, '%7E' );
}

/**
 * Private helper function used by util.escapeId*()
 * @ignore
 *
 * @param {string} str String to be encoded
 * @param {string} mode Encoding mode, see documentation for $wgFragmentMode
 *     in DefaultSettings.php
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
 * Utility library
 * @class mw.util
 * @singleton
 */
util = {

	/**
	 * Encode the string like PHP's rawurlencode
	 *
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
	escapeIdForAttribute: function ( str ) {
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
	escapeIdForLink: function ( str ) {
		return escapeIdInternal( str, config.FragmentMode[ 0 ] );
	},

	/**
	 * Return a wrapper function that is debounced for the given duration.
	 *
	 * When it is first called, a timeout is scheduled. If before the timer
	 * is reached the wrapper is called again, it gets rescheduled for the
	 * same duration from now until it stops being called. The original function
	 * is called from the "tail" of such chain, with the last set of arguments.
	 *
	 * @since 1.34
	 * @param {number} delay Time in milliseconds
	 * @param {Function} callback
	 * @return {Function}
	 */
	debounce: function ( delay, callback ) {
		var timeout;
		return function () {
			clearTimeout( timeout );
			timeout = setTimeout( Function.prototype.apply.bind( callback, this, arguments ), delay );
		};
	},

	/**
	 * Encode page titles for use in a URL
	 *
	 * We want / and : to be included as literal characters in our title URLs
	 * as they otherwise fatally break the title.
	 *
	 * The others are decoded because we can, it's prettier and matches behaviour
	 * of `wfUrlencode` in PHP.
	 *
	 * @param {string} str String to be encoded.
	 * @return {string} Encoded string
	 */
	wikiUrlencode: function ( str ) {
		return util.rawurlencode( str )
			.replace( /%20/g, '_' )
			// wfUrlencode replacements
			.replace( /%3B/g, ';' )
			.replace( /%40/g, '@' )
			.replace( /%24/g, '$' )
			.replace( /%21/g, '!' )
			.replace( /%2A/g, '*' )
			.replace( /%28/g, '(' )
			.replace( /%29/g, ')' )
			.replace( /%2C/g, ',' )
			.replace( /%2F/g, '/' )
			.replace( /%7E/g, '~' )
			.replace( /%3A/g, ':' );
	},

	/**
	 * Get the link to a page name (relative to `wgServer`),
	 *
	 * @param {string|null} [pageName=wgPageName] Page name
	 * @param {Object} [params] A mapping of query parameter names to values,
	 *  e.g. `{ action: 'edit' }`
	 * @return {string} Url of the page with name of `pageName`
	 */
	getUrl: function ( pageName, params ) {
		var fragmentIdx, url, query, fragment,
			title = typeof pageName === 'string' ? pageName : mw.config.get( 'wgPageName' );

		// Find any fragment
		fragmentIdx = title.indexOf( '#' );
		if ( fragmentIdx !== -1 ) {
			fragment = title.slice( fragmentIdx + 1 );
			// Exclude the fragment from the page name
			title = title.slice( 0, fragmentIdx );
		}

		// Produce query string
		if ( params ) {
			query = $.param( params );
		}
		if ( query ) {
			url = title ?
				util.wikiScript() + '?title=' + util.wikiUrlencode( title ) + '&' + query :
				util.wikiScript() + '?' + query;
		} else {
			url = mw.config.get( 'wgArticlePath' )
				.replace( '$1', util.wikiUrlencode( title ).replace( /\$/g, '$$$$' ) );
		}

		// Append the encoded fragment
		if ( fragment && fragment.length ) {
			url += '#' + util.escapeIdForLink( fragment );
		}

		return url;
	},

	/**
	 * Get URL to a MediaWiki entry point.
	 *
	 * @since 1.18
	 * @param {string} [str="index"] Name of MW entry point (e.g. 'index' or 'api')
	 * @return {string} URL to the script file (e.g. '/w/api.php' )
	 */
	wikiScript: function ( str ) {
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
	 * Use .ownerNode to access the `<style>` element, or use mw.loader#addStyleTag.
	 * This function returns the styleSheet object for convience (due to cross-browsers
	 * difference as to where it is located).
	 *
	 *     var sheet = util.addCSS( '.foobar { display: none; }' );
	 *     $( foo ).click( function () {
	 *         // Toggle the sheet on and off
	 *         sheet.disabled = !sheet.disabled;
	 *     } );
	 *
	 * @param {string} text CSS to be appended
	 * @return {CSSStyleSheet} Use .ownerNode to get to the `<style>` element.
	 */
	addCSS: function ( text ) {
		var s = mw.loader.addStyleTag( text );
		return s.sheet;
	},

	/**
	 * Grab the URL parameter value for the given parameter.
	 * Returns null if not found.
	 *
	 * @param {string} param The parameter name.
	 * @param {string} [url=location.href] URL to search through, defaulting to the current browsing location.
	 * @return {Mixed} Parameter value or null.
	 */
	getParamValue: function ( param, url ) {
		// Get last match, stop at hash
		var re = new RegExp( '^[^#]*[&?]' + util.escapeRegExp( param ) + '=([^&#]*)' ),
			m = re.exec( url !== undefined ? url : location.href );

		if ( m ) {
			// Beware that decodeURIComponent is not required to understand '+'
			// by spec, as encodeURIComponent does not produce it.
			return decodeURIComponent( m[ 1 ].replace( /\+/g, '%20' ) );
		}
		return null;
	},

	/**
	 * The content wrapper of the skin (e.g. `.mw-body`).
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
	 * instead. Or listen to mw.hook#wikipage_content which will
	 * allow your code to re-run when the page changes (e.g. live preview
	 * or re-render after ajax save).
	 *
	 * @property {jQuery}
	 */
	$content: null,

	/**
	 * Add a link to a portlet menu on the page, such as:
	 *
	 * p-cactions (Content actions), p-personal (Personal tools),
	 * p-navigation (Navigation), p-tb (Toolbox)
	 *
	 * The first three parameters are required, the others are optional and
	 * may be null. Though providing an id and tooltip is recommended.
	 *
	 * By default the new link will be added to the end of the list. To
	 * add the link before a given existing item, pass the DOM node
	 * (e.g. `document.getElementById( 'foobar' )`) or a jQuery-selector
	 * (e.g. `'#foobar'`) for that item.
	 *
	 *     util.addPortletLink(
	 *         'p-tb', 'https://www.mediawiki.org/',
	 *         'mediawiki.org', 't-mworg', 'Go to mediawiki.org', 'm', '#t-print'
	 *     );
	 *
	 *     var node = util.addPortletLink(
	 *         'p-tb',
	 *         new mw.Title( 'Special:Example' ).getUrl(),
	 *         'Example'
	 *     );
	 *     $( node ).on( 'click', function ( e ) {
	 *         console.log( 'Example' );
	 *         e.preventDefault();
	 *     } );
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
	 * @return {HTMLElement|null} The added list item, or null if no element was added.
	 */
	addPortletLink: function ( portletId, href, text, id, tooltip, accesskey, nextnode ) {
		var item, link, $portlet, portlet, portletDiv, ul, next;

		if ( !portletId ) {
			// Avoid confusing id="undefined" lookup
			return null;
		}

		portlet = document.getElementById( portletId );
		if ( !portlet ) {
			// Invalid portlet ID
			return null;
		}

		// Setup the anchor tag and set any the properties
		link = document.createElement( 'a' );
		link.href = href;
		link.textContent = text;
		if ( tooltip ) {
			link.title = tooltip;
		}
		if ( accesskey ) {
			link.accessKey = accesskey;
		}

		// Unhide portlet if it was hidden before
		$portlet = $( portlet );
		$portlet.removeClass( 'emptyPortlet' );

		// Setup the list item (and a span if $portlet is a Vector tab)
		// eslint-disable-next-line no-jquery/no-class-state
		if ( $portlet.hasClass( 'vectorTabs' ) ) {
			item = $( '<li>' ).append( $( '<span>' ).append( link )[ 0 ] )[ 0 ];
		} else {
			item = $( '<li>' ).append( link )[ 0 ];
		}
		if ( id ) {
			item.id = id;
		}

		// Select the first (most likely only) unordered list inside the portlet
		ul = portlet.querySelector( 'ul' );
		if ( !ul ) {
			// If it didn't have an unordered list yet, create one
			ul = document.createElement( 'ul' );
			portletDiv = portlet.querySelector( 'div' );
			if ( portletDiv ) {
				// Support: Legacy skins have a div (such as div.body or div.pBody).
				// Append the <ul> to that.
				portletDiv.appendChild( ul );
			} else {
				// Append it to the portlet directly
				portlet.appendChild( ul );
			}
		}

		if ( nextnode && ( typeof nextnode === 'string' || nextnode.nodeType || nextnode.jquery ) ) {
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

		return item;
	},

	/**
	 * Validate a string as representing a valid e-mail address
	 * according to HTML5 specification. Please note the specification
	 * does not validate a domain with one character.
	 *
	 * FIXME: should be moved to or replaced by a validation module.
	 *
	 * @param {string} mailtxt E-mail address to be validated.
	 * @return {boolean|null} Null if `mailtxt` was an empty string, otherwise true/false
	 * as determined by validation.
	 */
	validateEmail: function ( mailtxt ) {
		var rfc5322Atext, rfc1034LdhStr, html5EmailRegexp;

		if ( mailtxt === '' ) {
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
		rfc5322Atext = 'a-z0-9!#$%&\'*+\\-/=?^_`{|}~';

		// Next define the RFC 1034 'ldh-str'
		//     <domain> ::= <subdomain> | " "
		//     <subdomain> ::= <label> | <subdomain> "." <label>
		//     <label> ::= <letter> [ [ <ldh-str> ] <let-dig> ]
		//     <ldh-str> ::= <let-dig-hyp> | <let-dig-hyp> <ldh-str>
		//     <let-dig-hyp> ::= <let-dig> | "-"
		//     <let-dig> ::= <letter> | <digit>
		rfc1034LdhStr = 'a-z0-9\\-';

		html5EmailRegexp = new RegExp(
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
		return ( mailtxt.match( html5EmailRegexp ) !== null );
	},

	/**
	 * Note: borrows from IP::isIPv4
	 *
	 * @param {string} address
	 * @param {boolean} [allowBlock=false]
	 * @return {boolean}
	 */
	isIPv4Address: function ( address, allowBlock ) {
		var block,
			RE_IP_BYTE = '(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|0?[0-9]?[0-9])',
			RE_IP_ADD = '(?:' + RE_IP_BYTE + '\\.){3}' + RE_IP_BYTE;

		if ( typeof address !== 'string' ) {
			return false;
		}

		block = allowBlock ? '(?:\\/(?:3[0-2]|[12]?\\d))?' : '';

		return ( new RegExp( '^' + RE_IP_ADD + block + '$' ).test( address ) );
	},

	/**
	 * Note: borrows from IP::isIPv6
	 *
	 * @param {string} address
	 * @param {boolean} [allowBlock=false]
	 * @return {boolean}
	 */
	isIPv6Address: function ( address, allowBlock ) {
		var block, RE_IPV6_ADD;

		if ( typeof address !== 'string' ) {
			return false;
		}

		block = allowBlock ? '(?:\\/(?:12[0-8]|1[01][0-9]|[1-9]?\\d))?' : '';
		RE_IPV6_ADD =
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
	 * Check whether a string is an IP address
	 *
	 * @since 1.25
	 * @param {string} address String to check
	 * @param {boolean} [allowBlock=false] If a block of IPs should be allowed
	 * @return {boolean}
	 */
	isIPAddress: function ( address, allowBlock ) {
		return util.isIPv4Address( address, allowBlock ) ||
			util.isIPv6Address( address, allowBlock );
	},

	/**
	 * Escape string for safe inclusion in regular expression
	 *
	 * The following characters are escaped:
	 *
	 *     \ { } ( ) | . ? * + - ^ $ [ ]
	 *
	 * @since 1.26; moved to mw.util in 1.34
	 * @param {string} str String to escape
	 * @return {string} Escaped string
	 */
	escapeRegExp: function ( str ) {
		// eslint-disable-next-line no-useless-escape
		return str.replace( /([\\{}()|.?*+\-^$\[\]])/g, '\\$1' );
	}
};

// Backwards-compatible alias for mediawiki.RegExp module.
// @deprecated since 1.34
mw.RegExp = {};
mw.log.deprecate( mw.RegExp, 'escape', util.escapeRegExp, 'Use mw.util.escapeRegExp() instead.', 'mw.RegExp.escape' );

// Not allowed outside unit tests
if ( window.QUnit ) {
	util.setOptionsForTest = function ( opts ) {
		var oldConfig = config;
		config = $.extend( {}, config, opts );
		return oldConfig;
	};
}

/**
 * Initialisation of mw.util.$content
 */
function init() {
	util.$content = ( function () {
		var i, l, $node, selectors;

		selectors = [
			// The preferred standard is class "mw-body".
			// You may also use class "mw-body mw-body-primary" if you use
			// mw-body in multiple locations. Or class "mw-body-primary" if
			// you use mw-body deeper in the DOM.
			'.mw-body-primary',
			'.mw-body',

			// If the skin has no such class, fall back to the parser output
			'#mw-content-text'
		];

		for ( i = 0, l = selectors.length; i < l; i++ ) {
			$node = $( selectors[ i ] );
			if ( $node.length ) {
				return $node.first();
			}
		}

		// Should never happen... well, it could if someone is not finished writing a
		// skin and has not yet inserted bodytext yet.
		return $( 'body' );
	}() );
}

$( init );

mw.util = util;
module.exports = util;
