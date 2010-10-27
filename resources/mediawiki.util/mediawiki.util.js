/*
 * Utilities
 */

(function ($, mw) {

	mediaWiki.util = {

		/* Initialisation */
		'initialised' : false,
		'init' : function () {
			if ( this.initialised === false ) {
				this.initialised = true;

				// Any initialisation after the DOM is ready
				$(function () {
					
					// Populate clientProfile var
					mw.util.clientProfile = $.client.profile();

					// Set tooltipAccessKeyPrefix
					
						// Opera on any platform
						if ( mw.util.isBrowser('opera') ) {
							this.tooltipAccessKeyPrefix = 'shift-esc-';
						
						// Chrome on any platform
						} else if ( mw.util.isBrowser('chrome') ) {
							// Chrome on Mac or Chrome on other platform ?
							this.tooltipAccessKeyPrefix = mw.util.isPlatform('mac') ? 'ctrl-option-' : 'alt-';
						
						// Non-Windows Safari with webkit_version > 526
						} else if ( !mw.util.isPlatform('win') && mw.util.isBrowser('safari') && webkit_version > 526 ) {
							this.tooltipAccessKeyPrefix = 'ctrl-alt-';
						
						// Safari/Konqueror on any platform, or any browser on Mac (but not Safari on Windows)
						} else if ( !( mw.util.isPlatform('win') && mw.util.isBrowser('safari') )
										&& ( mw.util.isBrowser('safari')
										  || mw.util.isPlatform('mac')
										  || mw.util.isBrowser('konqueror') ) ) {
							this.tooltipAccessKeyPrefix = 'ctrl-';
						
						// Firefox 2.x
						} else if ( mw.util.isBrowser('firefox') && mw.util.isBrowserVersion('2') ) {
							this.tooltipAccessKeyPrefix = 'alt-shift-';
						}

					// Enable CheckboxShiftClick
					$('input[type=checkbox]:not(.noshiftselect)').checkboxShiftClick();

					// Fill $content var
					if ( $('#bodyContent').length ) {
						mw.util.$content = $('#bodyContent');
					} else if ( $('#article').length ) {
						mw.util.$content = $('#article');
					} else {
						mw.util.$content = $('#content');
					} 
				});


				return true;
			}
			return false;
		},

		/* Main body */

		// Holds result of $.client.profile()
		// Populated by init()
		'clientProfile' : {},

		/**
		* Checks if the current browser matches
		*
		* @example	mw.util.isBrowser( 'safari' );
		* @param	String	str		name of a browser (case insensitive). Check jquery.client.js for possible values
		* @return	Boolean			true of the browsername matches the clients browser
		*/
		'isBrowser' : function( str ) {
			str = (str + '').toLowerCase();
			return this.clientProfile.name == str;
		},

		/**
		* Checks if the current layout matches
		*
		* @example	mw.util.isLayout( 'webkit' );
		* @param	String	str		name of a layout engine (case insensitive). Check jquery.client.js for possible values
		* @return	Boolean			true of the layout engine matches the clients browser
		*/
		'isLayout' : function( str ) {
			str = (str + '').toLowerCase();
			return this.clientProfile.layout == str;
		},

		/**
		* Checks if the current layout matches
		*
		* @example	mw.util.isPlatform( 'mac' );
		* @param	String	str		name of a platform (case insensitive). Check jquery.client.js for possible values
		* @return	Boolean			true of the platform matches the clients platform
		*/
		'isPlatform' : function( str ) {
			str = (str + '').toLowerCase();
			return this.clientProfile.platform == str;
		},

		/**
		* Checks if the current browser version matches
		*
		* @example	mw.util.isBrowserVersion( '5' );
		* @param	String	str		version number without decimals
		* @return	Boolean			true of the version number matches the clients browser
		*/
		'isBrowserVersion' : function( str ) {
			return this.clientProfile.versionBase === str;
		},

		/**
		* Encodes the string like PHP's rawurlencode
		*
		* @param String str		string to be encoded
		*/
		'rawurlencode' : function( str ) {
			str = (str + '').toString();
			return encodeURIComponent( str ).replace( /!/g, '%21' ).replace( /'/g, '%27' ).replace( /\(/g, '%28' )
				.replace( /\)/g, '%29' ).replace( /\*/g, '%2A' ).replace( /~/g, '%7E' );
		},

		/**
		* Encode pagetitles for use in a URL
		* We want / and : to be included as literal characters in our title URLs
		* as they otherwise fatally break the title
		*
		* @param String str		string to be encoded
		*/
		'wfUrlencode' : function( str ) {
			return this.rawurlencode( str ).replace( /%20/g, '_' ).replace( /%3A/g, ':' ).replace( /%2F/g, '/' );
		},

		/**
		* Get the full url to a pagename
		*
		* @param String str		pagename to link to
		*/
		'wfGetlink' : function( str ) {
			return wgServer + wgArticlePath.replace( '$1', this.wfUrlencode( str ) );
		},

		/**
		* Check is a variable is empty. Support for strings, booleans, arrays and objects.
		* String "0" is considered empty. String containing only whitespace (ie. " ") is considered not empty.
		*
		* @param Mixed v	the variable to check for empty ness
		*/
		'isEmpty' : function( v ) {
			var key;
			if ( v === "" || v === 0 || v === "0" || v === null || v === false || typeof v === 'undefined' ) {
				return true;
			}
			if ( v.length === 0 ) {
				return true;
			}
			if ( typeof v === 'object' ) {
				for ( key in v ) {
					return false;
				}
				return true;
			}
			return false;
		},


		/**
		* Grabs the url parameter value for the given parameter
		* Returns null if not found
		*
		* @param String	param	paramter name
		* @param String url		url to search through (optional)
		*/
		'getParamValue' : function( param, url ) {
			url = url ? url : document.location.href;
			var re = new RegExp( '[^#]*[&?]' + param.escapeRE() + '=([^&#]*)' ); // Get last match, stop at hash
			var m = re.exec( url );
			if ( m && m.length > 1 ) {
				return decodeURIComponent( m[1] );
			}
			return null;
		},

		/**
		* Converts special characters to their HTML entities
		*
		* @param String			str text to escape
		* @param Bool			quotes if true escapes single and double quotes aswell (by default false)
		*/
		'htmlEscape' : function( str, quotes ) {
			str = $('<div/>').text( str ).html();
			if ( typeof quotes === 'undefined' ) {
				quotes = false;
			}
			if ( quotes === true ) {
				str = str.replace( /'/g, '&#039;' ).replace( /"/g, '&quot;' );
			}
			return str;
		},

		/**
		* Converts HTML entities back to text
		*
		* @param String str		text to unescape
		*/
		'htmlUnescape' : function( str ) {
			return $('<div/>').html( str ).text();
		},

		// Access key prefix
		// will be re-defined based on browser/operating system detection in mw.util.init()
		'tooltipAccessKeyPrefix' : 'alt-',

		// Regex to match accesskey tooltips
		'tooltipAccessKeyRegexp': /\[(ctrl-)?(alt-)?(shift-)?(esc-)?(.)\]$/,

		/**
		 * Add the appropriate prefix to the accesskey shown in the tooltip.
		 * If the nodeList parameter is given, only those nodes are updated;
		 * otherwise, all the nodes that will probably have accesskeys by
		 * default are updated.
		 *
		 * @param Mixed nodeList	jQuery object, or array of elements
		 */
		'updateTooltipAccessKeys' : function( nodeList ) {
			var $nodes;
			if ( nodeList instanceof jQuery ) {
				$nodes = nodeList;
			} else if ( nodeList ) {
				$nodes = $(nodeList);
			} else {
				// Rather than scanning all links, just the elements that contain the relevant links
				this.updateTooltipAccessKeys( $('#column-one a, #mw-head a, #mw-panel a, #p-logo a') );

				// these are rare enough that no such optimization is needed
				this.updateTooltipAccessKeys( $('input') );
				this.updateTooltipAccessKeys( $('label') );
				return;
			}

			$nodes.each(function ( i ) {
				var tip = $(this).attr( 'title' );
				if ( !!tip && mw.util.tooltipAccessKeyRegexp.exec( tip ) ) {
					tip = tip.replace( mw.util.tooltipAccessKeyRegexp, '[' + mw.util.tooltipAccessKeyPrefix + "$5]" );
					$(this).attr( 'title', tip );
				}
			});
		},

		// jQuery object that refers to the page-content element
		// Populated by init()
		'$content' : null,

		/**
		 * Add a link to a portlet menu on the page, such as:
		 *
		 * p-cactions (Content actions), p-personal (Personal tools), p-navigation (Navigation), p-tb (Toolbox)
		 *
		 * The first three paramters are required, others are optionals. Though
		 * providing an id and tooltip is recommended.
		 *
		 * By default the new link will be added to the end of the list. To add the link before a given existing item,
		 * pass the DOM node (document.getElementById('foobar') or the jQuery-selector ('#foobar') of that item.
		 *
		 * @example mw.util.addPortletLink('p-tb', 'http://mediawiki.org/', 'MediaWiki.org', 't-mworg', 'Go to MediaWiki.org ', 'm', '#t-print')
		 *
		 * @param String portlet	id of the target portlet ('p-cactions' or 'p-personal' etc.)
		 * @param String href		link URL
		 * @param String text		link text (will be automatically lowercased by CSS for p-cactions in Monobook)
		 * @param String id			id of the new item, should be unique and preferably have the appropriate prefix ('ca-', 'pt-', 'n-' or 't-')
		 * @param String tooltip	text to show when hovering over the link, without accesskey suffix
		 * @param String accesskey	accesskey to activate this link (one character, try to avoid conflicts. Use $('[accesskey=x').get() in the console to see if 'x' is already used.
		 * @param mixed nextnode	DOM node or jQuery-selector of the item that the new item should be added before, should be another item in the same list will be ignored if not the so
		 *
		 * @return Node				the DOM node of the new item (a LI element, or A element for older skins) or null
		 */
		'addPortletLink' : function( portlet, href, text, id, tooltip, accesskey, nextnode ) {

			// Setup the anchor tag
			var $link = $('<a />').attr( 'href', href ).text( text );

			// Some skins don't have any portlets
			// just add it to the bottom of their 'sidebar' element as a fallback
			switch ( skin ) {
			case 'standard' :
			case 'cologneblue' :
				$("#quickbar").append($link.after( '<br />' ));
				return $link.get(0);
			case 'nostalgia' :
				$("#searchform").before($link).before( ' &#124; ' );
				return $link.get(0);
			default : // Skins like chick, modern, monobook, myskin, simple, vector...

				// Select the specified portlet
				var $portlet = $('#' + portlet);
				if ( $portlet.length === 0 ) {
					return null;
				}
				// Select the first (most likely only) unordered list inside the portlet
				var $ul = $portlet.find( 'ul' ).eq( 0 );

				// If it didn't have an unordered list yet, create it
				if ($ul.length === 0) {
					// If there's no <div> inside, append it to the portlet directly
					if ($portlet.find( 'div' ).length === 0) {
						$portlet.append( '<ul/>' );
					} else {
						// otherwise if there's a div (such as div.body or div.pBody) append the <ul> to last (most likely only) div
						$portlet.find( 'div' ).eq( -1 ).append( '<ul/>' );
					}
					// Select the created element
					$ul = $portlet.find( 'ul' ).eq( 0 );
				}
				// Just in case..
				if ( $ul.length === 0 ) {
					return null;
				}

				// Unhide portlet if it was hidden before
				$portlet.removeClass( 'emptyPortlet' );

				// Wrap the anchor tag in a <span> and create a list item for it
				// and back up the selector to the list item
				var $item = $link.wrap( '<li><span /></li>' ).parent().parent();

				// Implement the properties passed to the function
				if ( id ) {
					$item.attr( 'id', id );
				}
				if ( accesskey ) {
					$link.attr( 'accesskey', accesskey );
					tooltip += ' [' + accesskey + ']';
				}
				if ( tooltip ) {
					$link.attr( 'title', tooltip );
				}
				if ( accesskey && tooltip ) {
					this.updateTooltipAccessKeys( $link );
				}

				// Append using DOM-element passing
				if ( nextnode && nextnode.parentNode == $ul.get( 0 ) ) {
					$(nextnode).before( $item );
				} else {
					// If the jQuery selector isn't found within the <ul>, just append it at the end
					if ( $ul.find( nextnode ).length === 0 ) {
						$ul.append( $item );
					} else {
						// Append using jQuery CSS selector
						$ul.find( nextnode ).eq( 0 ).before( $item );
					}
				}

				return $item.get( 0 );
			}
		}

	};

	mediaWiki.util.init();

})(jQuery, mediaWiki);