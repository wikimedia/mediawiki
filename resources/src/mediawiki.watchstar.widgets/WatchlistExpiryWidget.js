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
 * @param  {Object} config Configuration object
 */

var WatchlistExpiryWidget = function ( action, pageTitle, config ) {
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

	if ( action === 'watch' ) {
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
			var notif = mw.notification;

			if ( typeof $link !== 'undefined' ) {
				$link.addClass( 'loading' )
					.text( mw.msg( 'watching' ) );
			}
			// Pause the mw.notify so that we can wait for watch request to finish
			notif.pause();
			api = new mw.Api();
			api.watch( pageTitle, value )
				.done( function () {
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
						mw.message( message, mwTitle.getPrefixedText(), value ).parseDom()
					);
					// Resume the mw.notify once the label has been updated
					notif.resume();

					if ( typeof $link !== 'undefined' ) {
						$link.removeClass( 'loading' );
					}

					if ( typeof $li !== 'undefined' ) {
						if ( value === 'infinite' ) {
							$li.removeClass( 'mw-watchlink-temp' );
						} else {
							$li.addClass( 'mw-watchlink-temp' );
						}
						$link.text( mw.msg( 'unwatch' ) );
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
	}
};

OO.inheritClass( WatchlistExpiryWidget, OO.ui.Widget );

module.exports = WatchlistExpiryWidget;
