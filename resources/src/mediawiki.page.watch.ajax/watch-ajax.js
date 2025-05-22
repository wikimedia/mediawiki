( function () {
	// The name of the page to watch or unwatch
	const pageTitle = mw.config.get( 'wgRelevantPageName' ),
		isWatchlistExpiryEnabled = require( './config.json' ).WatchlistExpiry,
		// Use Object.create( null ) instead of {} to get an Object without predefined properties.
		// This avoids problems if the title is 'hasOwnPropery' or similar. Bug: T342137
		watchstarsByTitle = Object.create( null );

	/**
	 * Update the link text, link href attribute and (if applicable) "loading" class.
	 *
	 * @param {jQuery} $link Anchor tag of (un)watch link
	 * @param {string} action One of 'watch', 'unwatch'
	 * @param {string} [state='idle'] 'idle' or 'loading'. Default is 'idle'
	 * @param {string} [expiry='infinity'] The expiry date if a page is being watched temporarily.
	 * @private
	 */
	function updateWatchLinkAttributes( $link, action, state, expiry ) {
		// A valid but empty jQuery object shouldn't throw a TypeError
		if ( !$link.length ) {
			return;
		}

		expiry = expiry || 'infinity';

		// Invalid actions shouldn't silently turn the page in an unrecoverable state
		if ( action !== 'watch' && action !== 'unwatch' ) {
			throw new Error( 'Invalid action' );
		}

		const otherAction = action === 'watch' ? 'unwatch' : 'watch';
		const $li = $link.closest( 'li' );

		if ( state !== 'loading' ) {
			// jQuery event, @deprecated in 1.38
			// Trigger a 'watchpage' event for this List item.
			// NB: A expiry of 'infinity' is cast to null here, but not above
			$li.trigger( 'watchpage.mw', [ otherAction, mw.util.isInfinity( expiry ) ? null : expiry ] );
		}

		let tooltipAction = action;
		let daysLeftExpiry = null;
		let watchExpiry = null;
		// Checking to see what if the expiry is set or indefinite to display the correct message
		if ( isWatchlistExpiryEnabled && action === 'unwatch' ) {
			if ( mw.util.isInfinity( expiry ) ) {
				// Resolves to tooltip-ca-unwatch message
				tooltipAction = 'unwatch';
			} else {
				const expiryDate = new Date( expiry );
				const currentDate = new Date();
				// Using the Math.ceil function instead of floor so when, for example, a user selects one week
				// the tooltip shows 7 days instead of 6 days (see Phab ticket T253936)
				daysLeftExpiry = Math.ceil( ( expiryDate - currentDate ) / ( 1000 * 60 * 60 * 24 ) );
				if ( daysLeftExpiry > 0 ) {
					// Resolves to tooltip-ca-unwatch-expiring message
					tooltipAction = 'unwatch-expiring';
				} else {
					// Resolves to tooltip-ca-unwatch-expiring-hours message
					tooltipAction = 'unwatch-expiring-hours';
				}
				watchExpiry = expiryDate.toISOString();
			}
		}

		const msgKey = state === 'loading' ? action + 'ing' : action;
		// The following messages can be used here:
		// * watch
		// * watching
		// * unwatch
		// * unwatching
		const msg = mw.msg( msgKey );
		const link = $link.get( 0 );
		if ( link.children.length > 1 && link.lastElementChild.tagName === 'SPAN' ) {
			// Handle updated button markup,
			// where the watchstar contains an icon element and a span element containing the text
			link.lastElementChild.textContent = msg;
		} else {
			link.textContent = msg;
		}

		$link.toggleClass( 'loading', state === 'loading' )
			// The following messages can be used here:
			// * tooltip-ca-watch
			// * tooltip-ca-unwatch
			// * tooltip-ca-unwatch-expiring
			// * tooltip-ca-unwatch-expiring-hours
			.attr( 'title', mw.msg( 'tooltip-ca-' + tooltipAction, daysLeftExpiry ) )
			.updateTooltipAccessKeys()
			.attr( 'href', mw.util.getUrl( pageTitle, { action: action } ) )
			.attr( 'data-mw-expiry', watchExpiry );

		$li.toggleClass( 'mw-watchlink-temp', expiry !== null && expiry !== 'infinity' );

		// Most common ID style
		if ( state !== 'loading' && $li.prop( 'id' ) === 'ca-' + otherAction ) {
			$li.prop( 'id', 'ca-' + action );
		}
	}

	/**
	 * Notify hooks listeners of the new page watch status
	 *
	 * Watchstars should not need to use this hook, as they are updated via
	 * callback, and automatically kept in sync if a watchstar with the same
	 * title is changed.
	 *
	 * This hook should be used by other interfaces that care if the watch
	 * status of the page has changed, e.g. an edit form which wants to
	 * update a 'watch this page' checkbox.
	 *
	 * Users which change the watch status of the page without using a
	 * watchstar (e.g. edit forms again) should use the updatePageWatchStatus
	 * method to ensure watchstars are updated and this hook is fired.
	 *
	 * @param {boolean} isWatched The page is watched
	 * @param {string} [expiry='infinity'] The expiry date if a page is being watched temporarily.
	 * @param {string} [expirySelected='infinite'] The expiry length that was just selected from a dropdown, e.g. '1 week'
	 * @private
	 */
	function notifyPageWatchStatus( isWatched, expiry, expirySelected ) {
		expiry = expiry || 'infinity';
		expirySelected = expirySelected || 'infinite';

		/**
		 * Fires when the page watch status has changed.
		 *
		 * @event ~'wikipage.watchlistChange'
		 * @memberof Hooks
		 * @param {boolean} isWatched
		 * @param {string} expiry The expiry date if the page is being watched temporarily.
		 * @param {string} expirySelected The expiry length that was selected from a dropdown, e.g. '1 week'
		 * @example
		 * mw.hook( 'wikipage.watchlistChange' ).add( ( isWatched, expiry, expirySelected ) => {
		 *     // Do things
		 * } );
		 */
		mw.hook( 'wikipage.watchlistChange' ).fire(
			isWatched,
			expiry,
			expirySelected
		);
	}

	/**
	 * Update the page watch status.
	 *
	 * @memberof module:mediawiki.page.watch.ajax
	 * @param {boolean} isWatched The page is watched
	 * @param {string} [expiry='infinity'] The expiry date if a page is being watched temporarily.
	 * @param {string} [expirySelected='infinite'] The expiry length that was just selected from a dropdown, e.g. '1 week'
	 * @fires Hooks~'wikipage.watchlistChange'
	 * @stable
	 */
	function updatePageWatchStatus( isWatched, expiry, expirySelected ) {
		// Update all watchstars associated with the current page
		( watchstarsByTitle[ pageTitle ] || [] ).forEach( ( w ) => {
			w.update( isWatched, expiry );
		} );

		notifyPageWatchStatus( isWatched, expiry, expirySelected );
	}

	/**
	 * Update the link text, link `href` attribute and (if applicable) "loading" class.
	 *
	 * For an individual link being set to 'loading', the first
	 * argument can be a jQuery collection. When updating to an
	 * "idle" state, an {@link mw.Title} object should be passed to that
	 * all watchstars associated with that title are updated.
	 *
	 * @memberof module:mediawiki.page.watch.ajax
	 * @param {mw.Title|jQuery} titleOrLink Title of watchlinks to update (when state is idle), or an individual watchlink
	 * @param {string} action One of 'watch', 'unwatch'
	 * @param {string} [state="idle"] 'idle' or 'loading'. Default is 'idle'
	 * @param {string} [expiry] The expiry date if a page is being watched temporarily.
	 * @param {string} [expirySelected='infinite'] The expiry length that was just selected from a dropdown, e.g. '1 week'
	 * @fires Hooks~'wikipage.watchlistChange'
	 * @stable
	 */
	function updateWatchLink( titleOrLink, action, state, expiry, expirySelected ) {
		if ( titleOrLink instanceof $ ) {
			updateWatchLinkAttributes( titleOrLink, action, state, expiry );
		} else {
			// Assumed state is 'idle' when update a group of watchstars by title
			const isWatched = action === 'unwatch';
			const normalizedTitle = titleOrLink.getPrefixedDb();
			( watchstarsByTitle[ normalizedTitle ] || [] ).forEach( ( w ) => {
				w.update( isWatched, expiry, expirySelected );
			} );
			if ( normalizedTitle === pageTitle ) {
				notifyPageWatchStatus( isWatched, expiry, expirySelected );
			}
		}
	}

	/**
	 * TODO: This should be moved somewhere more accessible.
	 *
	 * @param {string} url
	 * @return {string} The extracted action, defaults to 'view'
	 * @private
	 */
	function mwUriGetAction( url ) {
		// TODO: Does MediaWiki give action path or query param
		// precedence? If the former, move this to the bottom
		const action = mw.util.getParamValue( 'action', url );
		if ( action !== null ) {
			return action;
		}

		const actionPaths = mw.config.get( 'wgActionPaths' );
		for ( const key in actionPaths ) {
			let parts = actionPaths[ key ].split( '$1' );
			parts = parts.map( mw.util.escapeRegExp );

			const m = new RegExp( parts.join( '(.+)' ) ).exec( url );
			if ( m && m[ 1 ] ) {
				return key;
			}
		}

		return 'view';
	}

	/**
	 * @private
	 */
	function init() {
		let $pageWatchLinks = $( '.mw-watchlink a[data-mw="interface"], a.mw-watchlink[data-mw="interface"]' );
		if ( !$pageWatchLinks.length ) {
			// Fallback to the class-based exclusion method for backwards-compatibility
			$pageWatchLinks = $( '.mw-watchlink a, a.mw-watchlink' );
			// Restrict to core interfaces, ignore user-generated content
			$pageWatchLinks = $pageWatchLinks.filter( ':not( #bodyContent *, #content * )' );
		}
		if ( $pageWatchLinks.length ) {
			watchstar( $pageWatchLinks, pageTitle );
		}
	}

	/**
	 * Class representing an individual watchstar
	 *
	 * @param {jQuery} $link Watch element
	 * @param {mw.Title} title Title
	 * @param {module:mediawiki.page.watch.ajax~callback} [callback]
	 * @private
	 */
	function Watchstar( $link, title, callback ) {
		this.$link = $link;
		this.title = title;
		this.callback = callback;
	}

	/**
	 * Update the watchstar
	 *
	 * @param {boolean} isWatched The page is watched
	 * @param {string} [expiry='infinity'] The expiry date if a page is being watched temporarily.
	 * @private
	 */
	Watchstar.prototype.update = function ( isWatched, expiry ) {
		expiry = expiry || 'infinity';
		updateWatchLinkAttributes( this.$link, isWatched ? 'unwatch' : 'watch', 'idle', expiry );
		if ( this.callback ) {
			/**
			 * @callback module:mediawiki.page.watch.ajax~callback
			 * @param {jQuery} $link The element being manipulated.
			 * @param {boolean} isWatched Whether the page is now watched.
			 * @param {string} expiry The expiry date if the page is being watched temporarily,
			 *   or an 'infinity'-like value (see [mw.util.isIninity()]{@link module:mediawiki.util.isInfinity})
			 */
			this.callback( this.$link, isWatched, expiry );
		}
	};

	/**
	 * Bind a given watchstar element to make it interactive.
	 *
	 * This is meant to allow binding of watchstars for arbitrary page titles,
	 * especially if different from the currently viewed page. As such, this function
	 * will *not* synchronise its state with any "Watch this page" checkbox such as
	 * found on the "Edit page" and "Publish changes" forms. The caller should either make
	 * "current page" watchstars picked up by init (and not use this function) or sync it manually
	 * from the callback this function provides.
	 *
	 * @memberof module:mediawiki.page.watch.ajax
	 * @param {jQuery} $links One or more anchor elements that must have an href
	 *  with a URL containing a `action=watch` or `action=unwatch` query parameter,
	 *  from which the current state will be learned (e.g. link to unwatch is currently watched)
	 * @param {string} title Title of page that this watchstar will affect
	 * @param {module:mediawiki.page.watch.ajax~callback} [callback] Callback to run after the action has been
	 *  processed and API request completed.
	 * @stable
	 */
	function watchstar( $links, title, callback ) {
		// Set up the ARIA connection between the watch link and the notification.
		// This is set outside the click handler so that it's already present when the user clicks.
		const notificationId = 'mw-watchlink-notification';
		const mwTitle = mw.Title.newFromText( title );
		const preferredExpiry = mw.user.options.get( 'watchstar-expiry', 'infinity' );

		if ( !mwTitle ) {
			return;
		}

		const normalizedTitle = mwTitle.getPrefixedDb();
		watchstarsByTitle[ normalizedTitle ] = watchstarsByTitle[ normalizedTitle ] || [];

		$links.each( function () {
			watchstarsByTitle[ normalizedTitle ].push(
				new Watchstar( $( this ), mwTitle, callback )
			);
		} );

		$links.attr( 'aria-controls', notificationId );

		// Add click handler.
		$links.on( 'click', function ( e ) {
			const action = mwUriGetAction( this.href );

			if ( !mwTitle || ( action !== 'watch' && action !== 'unwatch' ) ) {
				// Let native browsing handle the link
				return true;
			}
			e.preventDefault();
			e.stopPropagation();

			const $link = $( this );

			// eslint-disable-next-line no-jquery/no-class-state
			if ( $link.hasClass( 'loading' ) ) {
				return;
			}

			updateWatchLinkAttributes( $link, action, 'loading' );

			// Preload the notification module for mw.notify
			const modulesToLoad = [ 'mediawiki.notification' ];

			// Preload watchlist expiry widget so it runs in parallel with the api call
			if ( isWatchlistExpiryEnabled ) {
				modulesToLoad.push( 'mediawiki.watchstar.widgets' );
			}

			mw.loader.load( modulesToLoad );

			const api = new mw.Api();
			api[ action ]( title, preferredExpiry )
				.done( ( watchResponse ) => {
					const isWatched = watchResponse.watched === true;

					let message = isWatched ? 'addedwatchtext' : 'removedwatchtext';
					if ( mwTitle.isTalkPage() ) {
						message += '-talk';
					}

					let notifyPromise;
					let watchlistPopup;
					// @since 1.35 - pop up notification will be loaded with OOUI
					// only if Watchlist Expiry is enabled
					if ( isWatchlistExpiryEnabled ) {
						if ( isWatched ) {
							if ( !preferredExpiry || mw.util.isInfinity( preferredExpiry ) ) {
								// The message should include `infinite` watch period
								message = mwTitle.isTalkPage() ? 'addedwatchindefinitelytext-talk' : 'addedwatchindefinitelytext';
							} else {
								message = mwTitle.isTalkPage() ? 'addedwatchexpirytext-talk' : 'addedwatchexpirytext';
							}
						}

						notifyPromise = mw.loader.using( 'mediawiki.watchstar.widgets' ).then( ( require ) => {
							const WatchlistExpiryWidget = require( 'mediawiki.watchstar.widgets' );

							if ( !watchlistPopup ) {
								watchlistPopup = new WatchlistExpiryWidget(
									action,
									title,
									watchResponse.expiry,
									updateWatchLink,
									{
										// The following messages can be used here:
										// * addedwatchindefinitelytext-talk
										// * addedwatchindefinitelytext
										// * removedwatchtext-talk
										// * removedwatchtext
										message: mw.message( message, mwTitle.getPrefixedText(), preferredExpiry ).parseDom(),
										$link: $link
									} );
							}

							mw.notify( watchlistPopup.$element, {
								tag: 'watch-self',
								id: notificationId,
								autoHideSeconds: 'short'
							} );
						} );
					} else {
						// The following messages can be used here:
						// * addedwatchtext-talk
						// * addedwatchtext
						// * removedwatchtext-talk
						// * removedwatchtext
						notifyPromise = mw.notify(
							mw.message( message, mwTitle.getPrefixedText() ).parseDom(), {
								tag: 'watch-self',
								id: notificationId
							}
						);
					}

					// The notifications are stored as a promise and the watch link is only updated
					// once it is resolved. Otherwise, if $wgWatchlistExpiry set, the loading of
					// OOUI could cause a race condition and the link is updated before the popup
					// actually is shown. See T263135
					notifyPromise.always( () => {
						// Update all watchstars associated with this title
						watchstarsByTitle[ normalizedTitle ].forEach( ( w ) => {
							w.update( isWatched );
						} );

						// For the current page, also trigger the hook
						if ( normalizedTitle === pageTitle ) {
							notifyPageWatchStatus( isWatched, watchResponse.expiry );
						}
					} );
				} )
				.fail( ( code, data ) => {
					// Reset link to non-loading mode
					updateWatchLinkAttributes( $link, action );

					// Format error message
					const $msg = api.getErrorMessage( data );

					// Report to user about the error
					mw.notify( $msg, {
						tag: 'watch-self',
						type: 'error',
						id: notificationId
					} );
				} );
		} );
	}

	$( init );

	/**
	 * Animate watch/unwatch links to use asynchronous API requests to
	 * watch pages, rather than navigating to a different URI.
	 *
	 * @example
	 * var watch = require( 'mediawiki.page.watch.ajax' );
	 * watch.updateWatchLink(
	 *     $node,
	 *     'watch',
	 *     'loading'
	 * );
	 * // When the watch status of the page has been updated:
	 * watch.updatePageWatchStatus( true );
	 *
	 * @exports mediawiki.page.watch.ajax
	 */
	module.exports = {
		watchstar: watchstar,
		updateWatchLink: updateWatchLink,
		updatePageWatchStatus: updatePageWatchStatus
	};

}() );
