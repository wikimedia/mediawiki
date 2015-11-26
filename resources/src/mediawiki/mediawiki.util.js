( function ( mw, $ ) {
	'use strict';

	/**
	 * Utility library
	 * @class mw.util
	 * @singleton
	 */
	var util = {

		/**
		 * Initialisation
		 * (don't call before document ready)
		 */
		init: function () {
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
					'#mw-content-text',

					// Should never happen... well, it could if someone is not finished writing a
					// skin and has not yet inserted bodytext yet.
					'body'
				];

				for ( i = 0, l = selectors.length; i < l; i++ ) {
					$node = $( selectors[ i ] );
					if ( $node.length ) {
						return $node.first();
					}
				}

				// Preserve existing customized value in case it was preset
				return util.$content;
			}() );
		},

		/* Main body */

		/**
		 * Encode the string like PHP's rawurlencode
		 *
		 * @param {string} str String to be encoded.
		 */
		rawurlencode: function ( str ) {
			str = String( str );
			return encodeURIComponent( str )
				.replace( /!/g, '%21' ).replace( /'/g, '%27' ).replace( /\(/g, '%28' )
				.replace( /\)/g, '%29' ).replace( /\*/g, '%2A' ).replace( /~/g, '%7E' );
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
		 * @param {string|null} [str=wgPageName] Page name
		 * @param {Object} [params] A mapping of query parameter names to values,
		 *  e.g. `{ action: 'edit' }`
		 * @return {string} Url of the page with name of `str`
		 */
		getUrl: function ( str, params ) {
			var url = mw.config.get( 'wgArticlePath' ).replace(
				'$1',
				util.wikiUrlencode( typeof str === 'string' ? str : mw.config.get( 'wgPageName' ) )
			);

			if ( params && !$.isEmptyObject( params ) ) {
				url += ( url.indexOf( '?' ) !== -1 ? '&' : '?' ) + $.param( params );
			}

			return url;
		},

		/**
		 * Get address to a script in the wiki root.
		 * For index.php use `mw.config.get( 'wgScript' )`.
		 *
		 * @since 1.18
		 * @param {string} str Name of script (e.g. 'api'), defaults to 'index'
		 * @return {string} Address to script (e.g. '/w/api.php' )
		 */
		wikiScript: function ( str ) {
			str = str || 'index';
			if ( str === 'index' ) {
				return mw.config.get( 'wgScript' );
			} else if ( str === 'load' ) {
				return mw.config.get( 'wgLoadScript' );
			} else {
				return mw.config.get( 'wgScriptPath' ) + '/' + str +
					mw.config.get( 'wgScriptExtension' );
			}
		},

		/**
		 * Append a new style block to the head and return the CSSStyleSheet object.
		 * Use .ownerNode to access the `<style>` element, or use mw.loader#addStyleTag.
		 * This function returns the styleSheet object for convience (due to cross-browsers
		 * difference as to where it is located).
		 *
		 *     var sheet = mw.util.addCSS( '.foobar { display: none; }' );
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
			return s.sheet || s.styleSheet || s;
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
			if ( url === undefined ) {
				url = location.href;
			}
			// Get last match, stop at hash
			var	re = new RegExp( '^[^#]*[&?]' + mw.RegExp.escape( param ) + '=([^&#]*)' ),
				m = re.exec( url );
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
		 * Populated on document ready by #init. To use this property,
		 * wait for `$.ready` and be sure to have a module depedendency on
		 * `mediawiki.util` and `mediawiki.page.startup` which will ensure
		 * your document ready handler fires after #init.
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
		 *     mw.util.addPortletLink(
		 *         'p-tb', 'http://mediawiki.org/',
		 *         'MediaWiki.org', 't-mworg', 'Go to MediaWiki.org ', 'm', '#t-print'
		 *     );
		 *
		 * @param {string} portlet ID of the target portlet ( 'p-cactions' or 'p-personal' etc.)
		 * @param {string} href Link URL
		 * @param {string} text Link text
		 * @param {string} [id] ID of the new item, should be unique and preferably have
		 *  the appropriate prefix ( 'ca-', 'pt-', 'n-' or 't-' )
		 * @param {string} [tooltip] Text to show when hovering over the link, without accesskey suffix
		 * @param {string} [accesskey] Access key to activate this link (one character, try
		 *  to avoid conflicts. Use `$( '[accesskey=x]' ).get()` in the console to
		 *  see if 'x' is already used.
		 * @param {HTMLElement|jQuery|string} [nextnode] Element or jQuery-selector string to the item that
		 *  the new item should be added before, should be another item in the same
		 *  list, it will be ignored otherwise
		 *
		 * @return {HTMLElement|null} The added element (a ListItem or Anchor element,
		 * depending on the skin) or null if no element was added to the document.
		 */
		addPortletLink: function ( portlet, href, text, id, tooltip, accesskey, nextnode ) {
			var $item, $link, $portlet, $ul;

			// Check if there's at least 3 arguments to prevent a TypeError
			if ( arguments.length < 3 ) {
				return null;
			}
			// Setup the anchor tag
			$link = $( '<a>' ).attr( 'href', href ).text( text );
			if ( tooltip ) {
				$link.attr( 'title', tooltip );
			}

			// Select the specified portlet
			$portlet = $( '#' + portlet );
			if ( $portlet.length === 0 ) {
				return null;
			}
			// Select the first (most likely only) unordered list inside the portlet
			$ul = $portlet.find( 'ul' ).eq( 0 );

			// If it didn't have an unordered list yet, create it
			if ( $ul.length === 0 ) {

				$ul = $( '<ul>' );

				// If there's no <div> inside, append it to the portlet directly
				if ( $portlet.find( 'div:first' ).length === 0 ) {
					$portlet.append( $ul );
				} else {
					// otherwise if there's a div (such as div.body or div.pBody)
					// append the <ul> to last (most likely only) div
					$portlet.find( 'div' ).eq( -1 ).append( $ul );
				}
			}
			// Just in case..
			if ( $ul.length === 0 ) {
				return null;
			}

			// Unhide portlet if it was hidden before
			$portlet.removeClass( 'emptyPortlet' );

			// Wrap the anchor tag in a list item (and a span if $portlet is a Vector tab)
			// and back up the selector to the list item
			if ( $portlet.hasClass( 'vectorTabs' ) ) {
				$item = $link.wrap( '<li><span></span></li>' ).parent().parent();
			} else {
				$item = $link.wrap( '<li></li>' ).parent();
			}

			// Implement the properties passed to the function
			if ( id ) {
				$item.attr( 'id', id );
			}

			if ( accesskey ) {
				$link.attr( 'accesskey', accesskey );
			}

			if ( tooltip ) {
				$link.attr( 'title', tooltip );
			}

			if ( nextnode ) {
				// Case: nextnode is a DOM element (was the only option before MW 1.17, in wikibits.js)
				// Case: nextnode is a CSS selector for jQuery
				if ( nextnode.nodeType || typeof nextnode === 'string' ) {
					nextnode = $ul.find( nextnode );
				} else if ( !nextnode.jquery ) {
					// Error: Invalid nextnode
					nextnode = undefined;
				}
				if ( nextnode && ( nextnode.length !== 1 || nextnode[ 0 ].parentNode !== $ul[ 0 ] ) ) {
					// Error: nextnode must resolve to a single node
					// Error: nextnode must have the associated <ul> as its parent
					nextnode = undefined;
				}
			}

			// Case: nextnode is a jQuery-wrapped DOM element
			if ( nextnode ) {
				nextnode.before( $item );
			} else {
				// Fallback (this is the default behavior)
				$ul.append( $item );
			}

			// Update tooltip for the access key after inserting into DOM
			// to get a localized access key label (bug 67946).
			$link.updateTooltipAccessKeys();

			return $item[ 0 ];
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
			// (see STD 68 / RFC 5234 http://tools.ietf.org/html/std68)
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
				'^'
				+
				// User part which is liberal :p
				'[' + rfc5322Atext + '\\.]+'
				+
				// 'at'
				'@'
				+
				// Domain first part
				'[' + rfc1034LdhStr + ']+'
				+
				// Optional second part and following are separated by a dot
				'(?:\\.[' + rfc1034LdhStr + ']+)*'
				+
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
		 * @param {boolean} allowBlock
		 * @return {boolean}
		 */
		isIPv4Address: function ( address, allowBlock ) {
			if ( typeof address !== 'string' ) {
				return false;
			}

			var	block = allowBlock ? '(?:\\/(?:3[0-2]|[12]?\\d))?' : '',
				RE_IP_BYTE = '(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|0?[0-9]?[0-9])',
				RE_IP_ADD = '(?:' + RE_IP_BYTE + '\\.){3}' + RE_IP_BYTE;

			return address.search( new RegExp( '^' + RE_IP_ADD + block + '$' ) ) !== -1;
		},

		/**
		 * Note: borrows from IP::isIPv6
		 *
		 * @param {string} address
		 * @param {boolean} allowBlock
		 * @return {boolean}
		 */
		isIPv6Address: function ( address, allowBlock ) {
			if ( typeof address !== 'string' ) {
				return false;
			}

			var	block = allowBlock ? '(?:\\/(?:12[0-8]|1[01][0-9]|[1-9]?\\d))?' : '',
				RE_IPV6_ADD =
			'(?:' + // starts with "::" (including "::")
			':(?::|(?::' + '[0-9A-Fa-f]{1,4}' + '){1,7})' +
			'|' + // ends with "::" (except "::")
			'[0-9A-Fa-f]{1,4}' + '(?::' + '[0-9A-Fa-f]{1,4}' + '){0,6}::' +
			'|' + // contains no "::"
			'[0-9A-Fa-f]{1,4}' + '(?::' + '[0-9A-Fa-f]{1,4}' + '){7}' +
			')';

			if ( address.search( new RegExp( '^' + RE_IPV6_ADD + block + '$' ) ) !== -1 ) {
				return true;
			}

			RE_IPV6_ADD = // contains one "::" in the middle (single '::' check below)
				'[0-9A-Fa-f]{1,4}' + '(?:::?' + '[0-9A-Fa-f]{1,4}' + '){1,6}';

			return address.search( new RegExp( '^' + RE_IPV6_ADD + block + '$' ) ) !== -1
				&& address.search( /::/ ) !== -1 && address.search( /::.*::/ ) === -1;
		},

		/**
		 * Check whether a string is an IP address
		 *
		 * @since 1.25
		 * @param {string} address String to check
		 * @param {boolean} allowBlock True if a block of IPs should be allowed
		 * @return {boolean}
		 */
		isIPAddress: function ( address, allowBlock ) {
			return util.isIPv4Address( address, allowBlock ) ||
				util.isIPv6Address( address, allowBlock );
		}
	};

	/**
	 * @method wikiGetlink
	 * @inheritdoc #getUrl
	 * @deprecated since 1.23 Use #getUrl instead.
	 */
	mw.log.deprecate( util, 'wikiGetlink', util.getUrl, 'Use mw.util.getUrl instead.' );

	/**
	 * Access key prefix. Might be wrong for browsers implementing the accessKeyLabel property.
	 * @property {string} tooltipAccessKeyPrefix
	 * @deprecated since 1.24 Use the module jquery.accessKeyLabel instead.
	 */
	mw.log.deprecate( util, 'tooltipAccessKeyPrefix', $.fn.updateTooltipAccessKeys.getAccessKeyPrefix(), 'Use jquery.accessKeyLabel instead.' );

	/**
	 * Regex to match accesskey tooltips.
	 *
	 * Should match:
	 *
	 * - "[ctrl-option-x]"
	 * - "[alt-shift-x]"
	 * - "[ctrl-alt-x]"
	 * - "[ctrl-x]"
	 *
	 * The accesskey is matched in group $6.
	 *
	 * Will probably not work for browsers implementing the accessKeyLabel property.
	 *
	 * @property {RegExp} tooltipAccessKeyRegexp
	 * @deprecated since 1.24 Use the module jquery.accessKeyLabel instead.
	 */
	mw.log.deprecate( util, 'tooltipAccessKeyRegexp', /\[(ctrl-)?(option-)?(alt-)?(shift-)?(esc-)?(.)\]$/, 'Use jquery.accessKeyLabel instead.' );

	/**
	 * Add the appropriate prefix to the accesskey shown in the tooltip.
	 *
	 * If the `$nodes` parameter is given, only those nodes are updated;
	 * otherwise, depending on browser support, we update either all elements
	 * with accesskeys on the page or a bunch of elements which are likely to
	 * have them on core skins.
	 *
	 * @method updateTooltipAccessKeys
	 * @param {Array|jQuery} [$nodes] A jQuery object, or array of nodes to update.
	 * @deprecated since 1.24 Use the module jquery.accessKeyLabel instead.
	 */
	mw.log.deprecate( util, 'updateTooltipAccessKeys', function ( $nodes ) {
		if ( !$nodes ) {
			if ( document.querySelectorAll ) {
				// If we're running on a browser where we can do this efficiently,
				// just find all elements that have accesskeys. We can't use jQuery's
				// polyfill for the selector since looping over all elements on page
				// load might be too slow.
				$nodes = $( document.querySelectorAll( '[accesskey]' ) );
			} else {
				// Otherwise go through some elements likely to have accesskeys rather
				// than looping over all of them. Unfortunately this will not fully
				// work for custom skins with different HTML structures. Input, label
				// and button should be rare enough that no optimizations are needed.
				$nodes = $( '#column-one a, #mw-head a, #mw-panel a, #p-logo a, input, label, button' );
			}
		} else if ( !( $nodes instanceof $ ) ) {
			$nodes = $( $nodes );
		}

		$nodes.updateTooltipAccessKeys();
	}, 'Use jquery.accessKeyLabel instead.' );

	/**
	 * Add a little box at the top of the screen to inform the user of
	 * something, replacing any previous message.
	 * Calling with no arguments, with an empty string or null will hide the message
	 *
	 * @method jsMessage
	 * @deprecated since 1.20 Use mw#notify
	 * @param {Mixed} message The DOM-element, jQuery object or HTML-string to be put inside the message box.
	 *  to allow CSS/JS to hide different boxes. null = no class used.
	 */
	mw.log.deprecate( util, 'jsMessage', function ( message ) {
		if ( !arguments.length || message === '' || message === null ) {
			return true;
		}
		if ( typeof message !== 'object' ) {
			message = $.parseHTML( message );
		}
		mw.notify( message, { autoHide: true, tag: 'legacy' } );
		return true;
	}, 'Use mw.notify instead.' );

	mw.util = util;

}( mediaWiki, jQuery ) );
