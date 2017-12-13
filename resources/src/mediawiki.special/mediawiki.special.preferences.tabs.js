/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function ( mw, $ ) {
	$( function () {
		var $preferences, tabs, wrapper, previousTab;

		$preferences = $( '#preferences' );

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
			} ).prependTo( '#mw-content-text' );

		tabs = new OO.ui.IndexLayout( {
			expanded: false,
			// Do not remove focus from the tabs menu after choosing a tab
			autoFocus: false
		} );

		mw.config.get( 'wgPreferencesTabs' ).forEach( function ( tabConfig ) {
			var panel, $panelContents;

			panel = new OO.ui.TabPanelLayout( tabConfig.name, {
				expanded: false,
				label: tabConfig.label
			} );
			$panelContents = $( '#mw-prefsection-' + tabConfig.name );

			// Hide the unnecessary PHP PanelLayouts
			// (Do not use .remove(), as that would remove event handlers for everything inside them)
			$panelContents.parent().detach();

			panel.$element.append( $panelContents );
			tabs.addTabPanels( [ panel ] );

			// Remove duplicate labels
			// (This must be after .addTabPanels(), otherwise the tab item doesn't exist yet)
			$panelContents.children( 'legend' ).remove();
			$panelContents.attr( 'aria-labelledby', panel.getTabItem().getElementId() );
		} );

		wrapper = new OO.ui.PanelLayout( {
			expanded: false,
			padded: false,
			framed: true
		} );
		wrapper.$element.append( tabs.$element );
		$preferences.prepend( wrapper.$element );

		function updateHash( panel ) {
			var scrollTop, active;
			// Handle hash manually to prevent jumping,
			// therefore save and restore scrollTop to prevent jumping.
			scrollTop = $( window ).scrollTop();
			// Changing the hash apparently causes keyboard focus to be lost?
			// Save and restore it. This makes no sense though.
			active = document.activeElement;
			location.hash = '#mw-prefsection-' + panel.getName();
			if ( active ) {
				active.focus();
			}
			$( window ).scrollTop( scrollTop );
		}

		tabs.on( 'set', updateHash );

		/**
		 * @ignore
		 * @param {string} name the name of a tab without the prefix ("mw-prefsection-")
		 * @param {string} [mode] A hash will be set according to the current
		 *  open section. Set mode 'noHash' to supress this.
		 */
		function switchPrefTab( name, mode ) {
			if ( mode === 'noHash' ) {
				tabs.off( 'set', updateHash );
			}
			tabs.setTabPanel( name );
			if ( mode === 'noHash' ) {
				tabs.on( 'set', updateHash );
			}
		}

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

		// Handle other things that change the hash (e.g. using
		// the Back and Forward browser navigation).
		if ( 'onhashchange' in window ) {
			$( window ).on( 'hashchange', function () {
				var hash = location.hash;
				if ( hash.match( /^#mw-[\w-]+/ ) ) {
					detectHash();
				} else if ( hash === '' ) {
					switchPrefTab( 'personal', 'noHash' );
				}
			} );
		}

		// If we've reloaded the page or followed an open-in-new-window,
		// make the selected tab visible.
		detectHash();

		// Restore the active tab after saving the preferences
		previousTab = mw.storage.session.get( 'mwpreferences-prevTab' );
		if ( previousTab ) {
			switchPrefTab( previousTab, 'noHash' );
			// Deleting the key, the tab states should be reset until we press Save
			mw.storage.session.remove( 'mwpreferences-prevTab' );
		}

		$( '#mw-prefs-form' ).on( 'submit', function () {
			var value = tabs.getCurrentTabPanelName();
			mw.storage.session.set( 'mwpreferences-prevTab', value );
		} );

	} );
}( mediaWiki, jQuery ) );
