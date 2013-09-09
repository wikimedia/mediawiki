/**
 * Collapisble navigation for Vector
 */
( function ( mw, $ ) {
	'use strict';
	var map, version;

	// Use the same function for all navigation headings - don't repeat
	function toggle( $element ) {
		$.cookie(
			'vector-nav-' + $element.parent().attr( 'id' ),
			$element.parent().is( '.collapsed' ),
			{ 'expires': 30, 'path': '/' }
		);
		$element
			.parent()
			.toggleClass( 'expanded' )
			.toggleClass( 'collapsed' )
			.find( '.body' )
			.slideToggle( 'fast' );
	}

	/* Browser Support */

	map = {
		// Left-to-right languages
		ltr: {
			// Collapsible Nav is broken in Opera < 9.6 and Konqueror < 4
			opera: [['>=', 9.6]],
			konqueror: [['>=', 4.0]],
			blackberry: false,
			ipod: false,
			iphone: false,
			ps3: false
		},
		// Right-to-left languages
		rtl: {
			opera: [['>=', 9.6]],
			konqueror: [['>=', 4.0]],
			blackberry: false,
			ipod: false,
			iphone: false,
			ps3: false
		}
	};
	if ( !$.client.test( map ) ) {
		return true;
	}

	/* Bucket Testing */

	// Fallback to old version
	version = 1;
	// Allow new version override
	if ( mw.config.get( 'wgCollapsibleNavForceNewVersion' ) ) {
		version = 2;
	} else {
		// Make bucket testing optional
		if ( mw.config.get( 'wgCollapsibleNavBucketTest' ) ) {
			// This is be determined randomly, and then stored in a cookie
			version = $.cookie( 'vector-nav-pref-version' );
			// If the cookie didn't exist, or the value is out of range, generate a new one and save it
			if ( version === null ) {
				// 50% of the people will get the new version
				version = Math.round( Math.random() + 1 );
				$.cookie( 'vector-nav-pref-version', version, {
					expires: 30,
					path: '/'
				});
			}
		}
	}

	$( function ( $ ) {
		var limit, threshold, languages, acceptLangCookie,
			$primary, $secondary,
			i, $link, count,
			$headings, tabIndex;

		/* Special Language Portal Handling */

		// Language portal splitting feature (if it's turned on)
		if ( version === 2 ) {
			// How many links to show in the primary languages portal
			limit = 5;
			// How many links there must be in the secondary portal to justify having a secondary portal
			threshold = 3;
			// Make the interwiki language links list a secondary list, and create a new list before it as primary list
			$( '#p-lang ul' ).addClass( 'secondary' ).before( '<ul class="primary"></ul>' );
			// This is a list of languages in order of Wikipedia project size. This is the lowest fallback for choosing
			// which links to show in the primary list. Ideally the browser's accept-language headers should steer this
			// list, and we should fallback on a site configured (MediaWiki:Common.js) list of prefered languages.
			languages = [
				'en', 'fr', 'de', 'es', 'pt', 'it', 'ru', 'ja', 'nl', 'pl', 'zh', 'sv', 'ar', 'tr', 'uk', 'fi', 'no', 'ca',
				'ro', 'hu', 'ksh', 'id',  'he', 'cs', 'vi', 'ko', 'sr', 'fa', 'da', 'eo', 'sk', 'th', 'lt', 'vo', 'bg',
				'sl', 'hr', 'hi', 'et', 'mk', 'simple', 'new', 'ms', 'nn', 'gl', 'el', 'eu', 'ka', 'tl', 'bn', 'lv', 'ml',
				'bs', 'te', 'la', 'az', 'sh', 'war', 'br', 'is', 'mr', 'be-x-old', 'sq', 'cy', 'lb', 'ta', 'zh-classical',
				'an', 'jv', 'ht', 'oc', 'bpy', 'ceb', 'ur', 'zh-yue', 'pms', 'scn', 'be', 'roa-rup', 'qu', 'af', 'sw',
				'nds', 'fy', 'lmo', 'wa', 'ku', 'hy', 'su', 'yi', 'io', 'os', 'ga', 'ast', 'nap', 'vec', 'gu', 'cv',
				'bat-smg', 'kn', 'uz', 'zh-min-nan', 'si', 'als', 'yo', 'li', 'gan', 'arz', 'sah', 'tt', 'bar', 'gd', 'tg',
				'kk', 'pam', 'hsb', 'roa-tara', 'nah', 'mn', 'vls', 'gv', 'mi', 'am', 'ia', 'co', 'ne', 'fo', 'nds-nl',
				'glk', 'mt', 'ang', 'wuu', 'dv', 'km', 'sco', 'bcl', 'mg', 'my', 'diq', 'tk', 'szl', 'ug', 'fiu-vro', 'sc',
				'rm', 'nrm', 'ps', 'nv', 'hif', 'bo', 'se', 'sa', 'pnb', 'map-bms', 'lad', 'lij', 'crh', 'fur', 'kw', 'to',
				'pa', 'jbo', 'ba', 'ilo', 'csb', 'wo', 'xal', 'krc', 'ckb', 'pag', 'ln', 'frp', 'mzn', 'ce', 'nov', 'kv',
				'eml', 'gn', 'ky', 'pdc', 'lo', 'haw', 'mhr', 'dsb', 'stq', 'tpi', 'arc', 'hak', 'ie', 'so', 'bh', 'ext',
				'mwl', 'sd', 'ig', 'myv', 'ay', 'iu', 'na', 'cu', 'pi', 'kl', 'ty', 'lbe', 'ab', 'got', 'sm', 'as', 'mo',
				'ee', 'zea', 'av', 'ace', 'kg', 'bm', 'cdo', 'cbk-zam', 'kab', 'om', 'chr', 'pap', 'udm', 'ks', 'zu', 'rmy',
				'cr', 'ch', 'st', 'ik', 'mdf', 'kaa', 'aa', 'fj', 'srn', 'tet', 'or', 'pnt', 'bug', 'ss', 'ts', 'pcd',
				'pih', 'za', 'sg', 'lg', 'bxr', 'xh', 'ak', 'ha', 'bi', 've', 'tn', 'ff', 'dz', 'ti', 'ki', 'ny', 'rw',
				'chy', 'tw', 'sn', 'tum', 'ng', 'rn', 'mh', 'ii', 'cho', 'hz', 'kr', 'ho', 'mus', 'kj'
			];
			// If the user has an Accept-Language cookie, use it. Otherwise, set it asynchronously but keep the default
			// behavior for this page view.
			acceptLangCookie = $.cookie( 'accept-language' );
			if ( acceptLangCookie !== null ) {
				// Put the user's accepted languages before the list ordered by wiki size
				if ( acceptLangCookie !== '' ) {
					languages = acceptLangCookie.split( ',' ).concat( languages );
				}
			} else {
				$.getJSON(
					mw.util.wikiScript( 'api' ),
					'format=json&action=query&meta=userinfo&uiprop=acceptlang',
					function ( data ) {
						var langs = [], j, len, lang;
						if ( data.query &&
								data.query.userinfo &&
								data.query.userinfo.acceptlang !== undefined
						) {
							for ( j = 0, lang = data.query.userinfo.acceptlang, len = lang.length; j < len; j++ ) {
								if ( lang[j].q !== 0 ) {
									langs.push( lang[j]['*'] );
								}
							}
						}
						$.cookie( 'accept-language', langs.join( ',' ), {
							path: '/',
							expires: 30
						});
					}
				);
			}
			// Shortcuts to the two lists
			$primary = $( '#p-lang ul.primary' );
			$secondary = $( '#p-lang ul.secondary' );
			// Adjust the limit based on the threshold
			if ( $secondary.children().length < limit + threshold ) {
				limit += threshold;
			}
			// Move up to 5 of the links into the primary list, based on the priorities set forth in the languages list
			count = 0;
			for ( i = 0; i < languages.length; i++ ) {
				$link = $secondary.find( '.interwiki-' + languages[i] );
				if ( $link.length ) {
					if ( count++ < limit ) {
						$link.appendTo( $primary );
					} else {
						break;
					}
				}
			}
			// If there's still links in the secondary list and we havn't filled the primary list to it's limit yet, move
			// links into the primary list in order of appearance
			if ( count < limit ) {
				$secondary.children().each( function () {
					if ( count++ < limit ) {
						$(this).appendTo( $primary );
					} else {
						return false;
					}
				} );
			}
			// Hide the more portal if it's now empty, otherwise make the list into it's very own portal
			if ( $secondary.children().length === 0 ) {
				$secondary.remove();
			} else {
				$( '#p-lang' ).after( '<div id="p-lang-more" class="portal"><h3></h3><div class="body"></div></div>' );
				$( '#p-lang-more h3' ).text( mw.msg( 'vector-collapsiblenav-more' ) );
				$secondary.appendTo( $( '#p-lang-more .body' ) );
			}
			// Always show the primary interwiki language portal
			$( '#p-lang' ).addClass( 'persistent' );
		}

		/* General Portal Modification */

		// Always show the first portal
		$( '#mw-panel > .portal:first' ).addClass( 'first persistent' );
		// Apply a class to the entire panel to activate styles
		$( '#mw-panel' ).addClass( 'collapsible-nav' );
		// Use cookie data to restore preferences of what to show and hide
		$( '#mw-panel > .portal:not(.persistent)' )
			.each( function ( i ) {
				var id = $(this).attr( 'id' ),
					state = $.cookie( 'vector-nav-' + id );
				// Add anchor tag to heading for better accessibility
				$( this ).find( 'h3' ).wrapInner( $( '<a href="#"></a>' ).click( false ) );
				// In the case that we are not showing the new version, let's show the languages by default
				if (
					state === 'true' ||
					( state === null && i < 1 ) ||
					( state === null && version === 1 && id === 'p-lang' )
				) {
					$(this)
						.addClass( 'expanded' )
						.removeClass( 'collapsed' )
						.find( '.body' )
						.hide() // bug 34450
						.show();
				} else {
					$(this)
						.addClass( 'collapsed' )
						.removeClass( 'expanded' );
				}
				// Re-save cookie
				if ( state !== null ) {
					$.cookie( 'vector-nav-' + $(this).attr( 'id' ), state, { 'expires': 30, 'path': '/' } );
				}
			} );

		/* Tab Indexing */

		$headings = $( '#mw-panel > .portal:not(.persistent) > h3' );

		// Get the highest tab index
		tabIndex = $( document ).lastTabIndex() + 1;

		// Fix the search not having a tabindex
		$( '#searchInput' ).attr( 'tabindex', tabIndex++ );

		// Make it keyboard accessible
		$headings.attr( 'tabindex', function () {
			return tabIndex++;
		});

		// Toggle the selected menu's class and expand or collapse the menu
		$( '#mw-panel' )
			.delegate( '.portal:not(.persistent) > h3', 'keydown', function ( e ) {
				// Make the space and enter keys act as a click
				if ( e.which === 13 /* Enter */ || e.which === 32 /* Space */ ) {
					toggle( $(this) );
				}
			} )
			.delegate( '.portal:not(.persistent) > h3', 'mousedown', function ( e ) {
				if ( e.which !== 3 ) { // Right mouse click
					toggle( $(this) );
					$(this).blur();
				}
				return false;
			} );
	});

}( mediaWiki, jQuery ) );
