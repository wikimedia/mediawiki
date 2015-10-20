/*!
 * JavaScript for Special:Preferences
 * This script is loaded at the top of the page,
 * keep as small as possible.
 */
( function ( mw, $ ) {
	$( function () {
		var $preftoc, $preferences, $fieldsets, $legends;

		var labelFunc = function () {
			return this.id.replace( /^mw-prefsection/g, 'preftab' );
		};

		$( '#prefsubmit' ).attr( 'id', 'prefcontrol' );
		$preftoc = $( '<ul>' )
			.attr( {
				id: 'preftoc',
				role: 'tablist'
			} );
		$preferences = $( '#preferences' )
			.addClass( 'jsprefs' )
			.before( $preftoc );
		$fieldsets = $preferences.children( 'fieldset' )
			.hide()
			.attr( {
				role: 'tabpanel',
				'aria-hidden': 'true',
				'aria-labelledby': labelFunc
			} )
			.addClass( 'prefsection' );
		$legends = $fieldsets
			.children( 'legend' )
			.addClass( 'mainLegend' );

		// Make sure the accessibility tip is selectable so that screen reader users take notice,
		// but hide it per default to reduce interface clutter. Also make sure it becomes visible
		// when selected. Similar to jquery.mw-jump
		$( '<div>' ).addClass( 'mw-navigation-hint' )
			.text( mw.msg( 'prefs-tabs-navigation-hint' ) )
			.attr( 'tabIndex', 0 )
			.on( 'focus blur', function ( e ) {
				if ( e.type === 'blur' || e.type === 'focusout' ) {
					$( this ).css( 'height', '0' );
				} else {
					$( this ).css( 'height', 'auto' );
				}
			} ).insertBefore( $preftoc );

		/**
		 * It uses document.getElementById for security reasons (HTML injections in $()).
		 *
		 * @ignore
		 * @param {string} name the name of a tab without the prefix ("mw-prefsection-")
		 * @param {string} [mode] A hash will be set according to the current
		 *  open section. Set mode 'noHash' to surpress this.
		 */
		function switchPrefTab( name, mode ) {
			var $tab, scrollTop;
			// Handle hash manually to prevent jumping,
			// therefore save and restore scrollTop to prevent jumping.
			scrollTop = $( window ).scrollTop();
			if ( mode !== 'noHash' ) {
				location.hash = '#mw-prefsection-' + name;
			}
			$( window ).scrollTop( scrollTop );

			$preftoc.find( 'li' ).removeClass( 'selected' )
				.find( 'a' ).attr( {
					tabIndex: -1,
					'aria-selected': 'false'
				} );

			$tab = $( document.getElementById( 'preftab-' + name ) );
			if ( $tab.length ) {
				$tab.attr( {
					tabIndex: 0,
					'aria-selected': 'true'
				} )
				.focus()
					.parent().addClass( 'selected' );

				$preferences.children( 'fieldset' ).hide().attr( 'aria-hidden', 'true' );
				$( document.getElementById( 'mw-prefsection-' + name ) ).show().attr( 'aria-hidden', 'false' );
			}
		}

		// Check for messageboxes (.successbox, .warningbox, .errorbox) to replace with notifications
		if ( $( '.mw-preferences-messagebox' ).length ) {
			// If there is a #mw-preferences-success box and javascript is enabled, use a slick notification instead!
			if ( $( '#mw-preferences-success' ).length ) {
				var notif = mw.notification.notify( mw.message( 'savedprefs' ), { autoHide: false } );
				// 'change' event not reliable!
				$( '#preftoc, .prefsection' ).one( 'change keydown mousedown', function () {
					if ( notif ) {
						notif.close();
						notif = null;
					}
				} );

				// Remove now-unnecessary success=1 querystring to prevent reappearance of notification on reload
				if ( history.replaceState ) {
					history.replaceState( {}, document.title, location.href.replace( /&?success=1/, '' ) );
				}
			}
		}

		// Populate the prefToc
		$legends.each( function ( i, legend ) {
			var $legend = $( legend ),
				ident, $li, $a;
			if ( i === 0 ) {
				$legend.parent().show();
			}
			ident = $legend.parent().attr( 'id' );

			$li = $( '<li>' )
				.attr( 'role', 'presentation' )
				.addClass( i === 0 ? 'selected' : '' );
			$a = $( '<a>' )
				.attr( {
					id: ident.replace( 'mw-prefsection', 'preftab' ),
					href: '#' + ident,
					role: 'tab',
					tabIndex: i === 0 ? 0 : -1,
					'aria-selected': i === 0 ? 'true' : 'false',
					'aria-controls': ident
				} )
				.text( $legend.text() );
			$li.append( $a );
			$preftoc.append( $li );
		} );

		// Disable the button to save preferences unless preferences have changed
		$( '#prefcontrol' ).prop( 'disabled', true );
		$( '.prefsection' ).one( 'change keydown mousedown', function () {
			$( '#prefcontrol' ).prop( 'disabled', false );
		} );

		// Enable keyboard users to use left and right keys to switch tabs
		$preftoc.on( 'keydown', function ( event ) {
			var keyLeft = 37,
				keyRight = 39,
				$el;

			if ( event.keyCode === keyLeft ) {
				$el = $( '#preftoc li.selected' ).prev().find( 'a' );
			} else if ( event.keyCode === keyRight ) {
				$el = $( '#preftoc li.selected' ).next().find( 'a' );
			} else {
				return;
			}
			if ( $el.length > 0 ) {
				switchPrefTab( $el.attr( 'href' ).replace( '#mw-prefsection-', '' ) );
			}
		} );

		// If we've reloaded the page or followed an open-in-new-window,
		// make the selected tab visible.
		if ( location.hash.match( /^#mw-prefsection-[\w\-]+/ ) ) {
			switchPrefTab( location.hash.replace( '#mw-prefsection-', '' ) );
		}

		// In browsers that support the onhashchange event we will not bind click
		// handlers and instead let the browser do the default behavior (clicking the
		// <a href="#.."> will naturally set the hash, handled by onhashchange.
		// But other things that change the hash will also be catched (e.g. using
		// the Back and Forward browser navigation).
		// Note the special check for IE "compatibility" mode.
		if ( 'onhashchange' in window &&
			( document.documentMode === undefined || document.documentMode >= 8 )
		) {
			$( window ).on( 'hashchange', function () {
				var hash = location.hash;
				if ( hash.match( /^#mw-prefsection-[\w\-]+/ ) ) {
					switchPrefTab( hash.replace( '#mw-prefsection-', '' ) );
				} else if ( hash === '' ) {
					switchPrefTab( 'personal', 'noHash' );
				}
			} );
		// In older browsers we'll bind a click handler as fallback.
		// We must not have onhashchange *and* the click handlers, other wise
		// the click handler calls switchPrefTab() which sets the hash value,
		// which triggers onhashcange and calls switchPrefTab() again.
		} else {
			$preftoc.on( 'click', 'li a', function ( e ) {
				switchPrefTab( $( this ).attr( 'href' ).replace( '#mw-prefsection-', '' ) );
				e.preventDefault();
			} );
		}
		// Preserve the tab after saving the preferences
		// Not using cookies, because their deletion results are inconsistent.
		// Not using jStorage due to its enormous size (for this feature)
		if ( window.sessionStorage ) {
			if ( sessionStorage.getItem( 'mediawikiPreferencesTab' ) !== null ) {
				switchPrefTab( sessionStorage.getItem( 'mediawikiPreferencesTab' ), 'noHash' );
			}
			// Deleting the key, the tab states should be reset until we press Save
			sessionStorage.removeItem( 'mediawikiPreferencesTab' );

			$( '#mw-prefs-form' ).submit( function () {
				var storageData = $( $preftoc ).find( 'li.selected a' ).attr( 'id' ).replace( 'preftab-', '' );
				sessionStorage.setItem( 'mediawikiPreferencesTab', storageData );
			} );
		}
	} );
}( mediaWiki, jQuery ) );
