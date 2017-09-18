/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function ( mw, $ ) {
	$( function () {
		var $preftoc, $preferences, $fieldsets, labelFunc, previousTab;

		labelFunc = function () {
			return this.id.replace( /^mw-prefsection/g, 'preftab' );
		};

		$preftoc = $( '#preftoc' );
		$preferences = $( '#preferences' );

		$fieldsets = $preferences.children( 'fieldset' )
			.attr( {
				role: 'tabpanel',
				'aria-labelledby': labelFunc
			} );
		$fieldsets.not( '#mw-prefsection-personal' )
			.hide()
			.attr( 'aria-hidden', 'true' );

		// T115692: The following is kept for backwards compatibility with older skins
		$preferences.addClass( 'jsprefs' );
		$fieldsets.addClass( 'prefsection' );
		$fieldsets.children( 'legend' ).addClass( 'mainLegend' );

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
				} ).focus()
					.parent().addClass( 'selected' );

				$preferences.children( 'fieldset' ).hide().attr( 'aria-hidden', 'true' );
				$( document.getElementById( 'mw-prefsection-' + name ) ).show().attr( 'aria-hidden', 'false' );
			}
		}

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

		// Jump to correct section as indicated by the hash.
		// This function is called onload and onhashchange.
		function detectHash() {
			var hash = location.hash,
				matchedElement, parentSection;
			if ( hash.match( /^#mw-prefsection-[\w]+$/ ) ) {
				mw.storage.session.remove( 'mwpreferences-prevTab' );
				switchPrefTab( hash.replace( '#mw-prefsection-', '' ) );
			} else if ( hash.match( /^#mw-[\w-]+$/ ) ) {
				matchedElement = document.getElementById( hash.slice( 1 ) );
				parentSection = $( matchedElement ).parent().closest( '[id^="mw-prefsection-"]' );
				if ( parentSection.length ) {
					mw.storage.session.remove( 'mwpreferences-prevTab' );
					// Switch to proper tab and scroll to selected item.
					switchPrefTab( parentSection.attr( 'id' ).replace( 'mw-prefsection-', '' ), 'noHash' );
					matchedElement.scrollIntoView();
				}
			}
		}

		// In browsers that support the onhashchange event we will not bind click
		// handlers and instead let the browser do the default behavior (clicking the
		// <a href="#.."> will naturally set the hash, handled by onhashchange.
		// But other things that change the hash will also be caught (e.g. using
		// the Back and Forward browser navigation).
		// Note the special check for IE "compatibility" mode.
		if ( 'onhashchange' in window &&
			( document.documentMode === undefined || document.documentMode >= 8 )
		) {
			$( window ).on( 'hashchange', function () {
				var hash = location.hash;
				if ( hash.match( /^#mw-[\w-]+/ ) ) {
					detectHash();
				} else if ( hash === '' ) {
					switchPrefTab( 'personal', 'noHash' );
				}
			} )
				// Run the function immediately to select the proper tab on startup.
				.trigger( 'hashchange' );
		// In older browsers we'll bind a click handler as fallback.
		// We must not have onhashchange *and* the click handlers, otherwise
		// the click handler calls switchPrefTab() which sets the hash value,
		// which triggers onhashchange and calls switchPrefTab() again.
		} else {
			$preftoc.on( 'click', 'li a', function ( e ) {
				switchPrefTab( $( this ).attr( 'href' ).replace( '#mw-prefsection-', '' ) );
				e.preventDefault();
			} );
			// If we've reloaded the page or followed an open-in-new-window,
			// make the selected tab visible.
			detectHash();
		}

		// Restore the active tab after saving the preferences
		previousTab = mw.storage.session.get( 'mwpreferences-prevTab' );
		if ( previousTab ) {
			switchPrefTab( previousTab, 'noHash' );
			// Deleting the key, the tab states should be reset until we press Save
			mw.storage.session.remove( 'mwpreferences-prevTab' );
		}

		$( '#mw-prefs-form' ).on( 'submit', function () {
			var value = $( $preftoc ).find( 'li.selected a' ).attr( 'id' ).replace( 'preftab-', '' );
			mw.storage.session.set( 'mwpreferences-prevTab', value );
		} );

	} );
}( mediaWiki, jQuery ) );
