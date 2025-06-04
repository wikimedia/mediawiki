/**
 * A special widget that displays a message that a page is being watched/unwatched
 * with a selection widget that can determine how long the page will be watched.
 * If a page is being watched then a dropdown with expiry options is included.
 *
 * @exports mediawiki.watchstar.widgets
 * @extends OO.ui.Widget
 * @param {string} action One of 'watch', 'unwatch'
 * @param {string} pageTitle Title of page that this widget will watch or unwatch
 * @param {string|null} expiry ISO 8601 timestamp of the expiry to be pre-selected, or 'infinity'
 * @param {Function} updateWatchLink
 * @param {Object} config Configuration object
 */
function WatchlistExpiryWidget( action, pageTitle, expiry, updateWatchLink, config ) {
	const dataExpiryOptions = require( './data.json' ).options,
		expiryOptions = [];
	let expiryDropdown;

	config = config || {};
	const $link = config.$link;

	WatchlistExpiryWidget.super.call( this, config );

	const messageLabel = new OO.ui.LabelWidget( {
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
		$( window ).one( 'keydown.watchlistExpiry', ( e ) => {
			if ( ( e.keyCode || e.which ) !== OO.ui.Keys.TAB ) {
				return;
			}

			// Here we look for focus on the watch link, going by the accessKey.
			// This is because there is no CSS class or ID on the link itself,
			// and skins could manipulate the position of the link. The accessKey
			// however is always present on the link.
			if ( document.activeElement.accessKey === mw.msg( 'accesskey-ca-watch' ) ) {
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

		for ( const key in dataExpiryOptions ) {
			expiryOptions.push( { data: dataExpiryOptions[ key ], label: key } );
		}

		const dropdownLabel = new OO.ui.LabelWidget( {
			label: mw.message( 'addedwatchexpiry-options-label' ).parseDom(),
			classes: [ 'mw-WatchlistExpiryWidgetwatchlist-dropdown-label' ]
		} );
		expiryDropdown = new OO.ui.DropdownInputWidget( {
			options: expiryOptions,
			classes: [ 'mw-watchexpiry' ]
		} );

		expiryDropdown.setValue( mw.user.options.get( 'watchstar-expiry', 'infinite' ) );
		const onDropdownChange = function ( value ) {
			const notif = mw.notification,
				optionSelectedLabel = expiryDropdown.dropdownWidget.label;

			if ( typeof $link !== 'undefined' ) {
				updateWatchLink( $link, 'watch', 'loading', expiry );
			}

			// Pause the mw.notify so that we can wait for watch request to finish
			notif.pause();
			const api = new mw.Api();
			api.watch( pageTitle, value )
				.done( ( watchResponse ) => {
					let message;
					const mwTitle = mw.Title.newFromText( pageTitle ),
						isInfinity = mw.util.isInfinity( value );
					if ( mwTitle.isTalkPage() ) {
						message = isInfinity ? 'addedwatchindefinitelytext-talk' : 'addedwatchexpirytext-talk';
					} else {
						message = isInfinity ? 'addedwatchindefinitelytext' : 'addedwatchexpirytext';
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

					updateWatchLink( mwTitle, 'unwatch', 'idle', watchResponse.expiry, value );
				} )
				.fail( ( code, data ) => {
					// Format error message
					const $msg = api.getErrorMessage( data );

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
}

OO.inheritClass( WatchlistExpiryWidget, OO.ui.Widget );

module.exports = WatchlistExpiryWidget;
