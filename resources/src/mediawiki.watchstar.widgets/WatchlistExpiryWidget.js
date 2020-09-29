/* eslint-disable no-implicit-globals */
/**
 * A special widget that displays a message that a page is being watched/unwatched
 * with a selection widget that can determine how long the page will be watched.
 * If a page is being watched then a dropdown with expiry options is included.
 *
 * @class
 * @extends OO.ui.Widget
 * @param {string} action One of 'watch', 'unwatch'
 * @param {string} pageTitle Title of page that this widget will watch or unwatch
 * @param {Function} updateWatchLink
 * @param {Object} config Configuration object
 */

var WatchlistExpiryWidget = function ( action, pageTitle, updateWatchLink, config ) {
	var dataExpiryOptions = require( './data.json' ).options,
		messageLabel, dropdownLabel,
		expiryDropdown, onDropdownChange, api, $link, $li,
		expiryOptions = [];

	config = config || {};
	$link = config.$link;
	$li = config.$li;

	WatchlistExpiryWidget.parent.call( this, config );

	messageLabel = new OO.ui.LabelWidget( {
		label: config.message
	} );

	this.$element
		.addClass( 'mw-watchstar-WatchlistExpiryWidget' )
		.append( messageLabel.$element );

	/**
	 * Allows user to tab into the expiry dropdown from the watch link.
	 * Valid only for the initial keystroke after the popup appears, so as to
	 * avoid listening to every keystroke for the entire session.
	 */
	function addTabKeyListener() {
		$( window ).one( 'keydown.watchlistExpiry', function ( e ) {
			if ( ( e.keyCode || e.which ) !== OO.ui.Keys.TAB ) {
				return;
			}

			// Here we look for focus on the watch link, going by the accessKey.
			// This is because there is no CSS class or ID on the link itself,
			// and skins could manipulate the position of the link. The accessKey
			// however is always present on the link.
			if ( document.activeElement.accessKey === mw.message( 'accesskey-ca-watch' ).text() ) {
				e.preventDefault();
				expiryDropdown.focus();

				// Add another tab key listener so they can tab back to the watch link.
				addTabKeyListener();
			} else if ( $( e.target ).parents( '.mw-watchexpiry' ).length ) {
				// Move focus to the watch link if they're tabbing from the dropdown.
				e.preventDefault();
				$( '#ca-unwatch a' ).trigger( 'focus' );
			}
		} );
	}

	if ( action === 'watch' ) {
		addTabKeyListener();

		Object.keys( dataExpiryOptions ).forEach( function ( key ) {
			expiryOptions.push( { data: dataExpiryOptions[ key ], label: key } );
		} );

		dropdownLabel = new OO.ui.LabelWidget( {
			label: mw.message( 'addedwatchexpiry-options-label' ).parseDom(),
			classes: [ 'mw-WatchlistExpiryWidgetwatchlist-dropdown-label' ]
		} );
		expiryDropdown = new OO.ui.DropdownInputWidget( {
			options: expiryOptions,
			classes: [ 'mw-watchexpiry' ]
		} );
		onDropdownChange = function ( value ) {
			var notif = mw.notification,
				optionSelectedLabel = expiryDropdown.dropdownWidget.label;

			if ( typeof $link !== 'undefined' ) {
				updateWatchLink( $link, 'watch', 'loading' );
			}

			// Pause the mw.notify so that we can wait for watch request to finish
			notif.pause();
			api = new mw.Api();
			api.watch( pageTitle, value )
				.done( function ( watchResponse ) {
					var message,
						mwTitle = mw.Title.newFromText( pageTitle );
					if ( mwTitle.isTalkPage() ) {
						message = value === 'infinite' ? 'addedwatchindefinitelytext-talk' : 'addedwatchexpirytext-talk';
					} else {
						message = value === 'infinite' ? 'addedwatchindefinitelytext' : 'addedwatchexpirytext';
					}

					// The following messages can be used here:
					// * addedwatchindefinitelytext-talk
					// * addedwatchindefinitelytext
					// * addedwatchexpirytext-talk
					// * addedwatchexpirytext
					messageLabel.setLabel(
						mw.message( message, mwTitle.getPrefixedText(), optionSelectedLabel ).parseDom()
					);
					// Resume the mw.notify once the label has been updated
					notif.resume();

					updateWatchLink( $link, 'unwatch', 'idle', watchResponse.expiry );

					if ( typeof $li !== 'undefined' ) {
						if ( value === 'infinite' ) {
							$li.removeClass( 'mw-watchlink-temp' );
						} else {
							$li.addClass( 'mw-watchlink-temp' );
						}
					}

					// Update the "Watch this page" checkbox on action=edit when the
					// page is watched or unwatched via the tab.
					if ( document.getElementById( 'wpWatchlistExpiryWidget' ) ) {
						OO.ui.infuse( '#wpWatchlistExpiryWidget' ).setValue( value );
					}
				} )
				.fail( function ( code, data ) {
					// Format error message
					var $msg = api.getErrorMessage( data );

					// Report to user about the error
					mw.notify( $msg, {
						tag: 'watch-self',
						type: 'error'
					} );
					// Resume the mw.notify once the error has been reported
					notif.resume();
				} );
		};

		expiryDropdown.on( 'change', onDropdownChange );
		this.$element.append( dropdownLabel.$element, expiryDropdown.$element );
	} else {
		if ( typeof $li !== 'undefined' ) {
			$li.removeClass( 'mw-watchlink-temp' );
		}
	}
};

OO.inheritClass( WatchlistExpiryWidget, OO.ui.Widget );

module.exports = WatchlistExpiryWidget;
