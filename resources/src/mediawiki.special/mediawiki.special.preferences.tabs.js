/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function ( mw, $ ) {
	$( function () {
		var $preftoc, $preferences, $fieldsets, labelFunc, previousTab;


		$preftoc = $( '#preftoc' );
		$preferences = $( '#preferences' );



		// id => label
		var toplevelSections = {};

		$preftoc.find( 'a' ).each( function () {
			var $link = $( this );
			toplevelSections[ $link.attr( 'id' ).replace( 'preftab-', '' ) ] = $link.text();
		} );

		var tabs = new OO.ui.IndexLayout( {
			expanded: false
		} );

		for ( var sectionId in toplevelSections ) {
			var panel = new OO.ui.TabPanelLayout( sectionId, {
				expanded: false,
				label: toplevelSections[ sectionId ]
			} );
			var $panelContents = $( '#mw-prefsection-' + sectionId );

			// Hide the unnecessary PHP PanelLayouts
			// (Do not use .remove(), as that would remove event handlers for everything inside them)
			$panelContents.parent().detach();
			panel.$element.append( $panelContents );
			tabs.addTabPanels( [ panel ] );
			// Remove duplicate labels
			// (This must be after .addTabPanels(), otherwise the tab item doesn't exist yet)
			$panelContents.children( 'legend' ).remove();
			$panelContents.attr( 'aria-labelledby', panel.getTabItem().getElementId() );
		}

		var wrapper = new OO.ui.PanelLayout( {
			expanded: false,
			padded: false,
			framed: true
		} );
		wrapper.$element.append( tabs.$element );
		$preferences.prepend( wrapper.$element );

		function updateHash( panel ) {
			// Handle hash manually to prevent jumping,
			// therefore save and restore scrollTop to prevent jumping.
			var scrollTop = $( window ).scrollTop();
			location.hash = '#mw-prefsection-' + panel.getName();
			$( window ).scrollTop( scrollTop );
		}

		tabs.on( 'set', updateHash );


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
		$preftoc.remove();

		/**
		 * @ignore
		 * @param {string} name the name of a tab without the prefix ("mw-prefsection-")
		 * @param {string} [mode] A hash will be set according to the current
		 *  open section. Set mode 'noHash' to surpress this.
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
