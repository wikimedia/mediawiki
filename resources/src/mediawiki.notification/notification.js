( function () {
	'use strict';

	let notification = null,
		// The .mw-notification-area div that all notifications are contained inside.
		$area,
		// Number of open notification boxes at any time
		openNotificationCount = 0,
		isPageReady = false;
	const preReadyNotifQueue = [];

	/**
	* @typedef {Object} mw.notification~Notification
	* @property {mw.Message|jQuery|HTMLElement|string} message
	* @property {mw.notification.NotificationOptions} options
	*/

	/**
	 * @classdesc Describes a notification. See [mw.notification module]{@link mw.notification}. A Notification object for 1 message.
	 *
	 * The constructor is not publicly accessible; use [mw.notification.notify]{@link mw.notification} instead.
	 * This does not insert anything into the document. To add to document use
	 * [mw.notification.notify]{@link mw.notification#notify}.
	 *
	 * @class Notification
	 * @global
	 * @hideconstructor
	 * @param {mw.notification~Notification} Notification object
	 */
	function Notification( message, options ) {

		const $notification = $( '<div>' )
			.data( 'mw-notification', this )
			.attr( 'role', 'status' )
			.addClass( [
				'mw-notification',
				options.autoHide ? 'mw-notification-autohide' : 'mw-notification-noautohide'
			] );

		if ( options.tag ) {
			// Sanitize options.tag before it is used by any code. (Including Notification class methods)
			options.tag = options.tag.replace( /[ _-]+/g, '-' ).replace( /[^-a-z0-9]+/ig, '' );
			if ( options.tag ) {
				// eslint-disable-next-line mediawiki/class-doc
				$notification.addClass( 'mw-notification-tag-' + options.tag );
			} else {
				delete options.tag;
			}
		}

		if ( options.type ) {
			// Sanitize options.type
			options.type = options.type.replace( /[ _-]+/g, '-' ).replace( /[^-a-z0-9]+/ig, '' );
			// The following classes are used here:
			// * mw-notification-type-error
			// * mw-notification-type-warn
			$notification.addClass( 'mw-notification-type-' + options.type );
		}

		if ( options.title ) {
			$( '<div>' )
				.addClass( 'mw-notification-title' )
				.text( options.title )
				.appendTo( $notification );
		}

		if ( options.id ) {
			$notification.attr( 'id', options.id );
		}

		if ( options.classes ) {
			// eslint-disable-next-line mediawiki/class-doc
			$notification.addClass( options.classes );
		}

		const $notificationContent = $( '<div>' ).addClass( 'mw-notification-content' );

		if ( typeof message === 'object' ) {
			// Handle mw.Message objects separately from DOM nodes and jQuery objects
			if ( message instanceof mw.Message ) {
				$notificationContent.html( message.parse() );
			} else {
				$notificationContent.append( message );
			}
		} else {
			$notificationContent.text( message );
		}

		$notificationContent.appendTo( $notification );

		// Private state parameters, meant for internal use only
		// autoHideSeconds: String alias for number of seconds for timeout of auto-hiding notifications.
		// isOpen: Set to true after .start() is called to avoid double calls.
		//         Set back to false after .close() to avoid duplicating the close animation.
		// isPaused: false after .resume(), true after .pause(). Avoids duplicating or breaking the hide timeouts.
		//           Set to true initially so .start() can call .resume().
		// message: The message passed to the notification. Unused now but may be used in the future
		//          to stop replacement of a tagged notification with another notification using the same message.
		// options: The options passed to the notification with a little sanitization. Used by various methods.
		// $notification: jQuery object containing the notification DOM node.
		// timeout: Holds appropriate methods to set/clear timeouts
		this.autoHideSeconds = options.autoHideSeconds &&
			notification.autoHideSeconds[ options.autoHideSeconds ] ||
			notification.autoHideSeconds.short;
		this.isOpen = false;
		this.isPaused = true;
		this.message = message;
		this.options = options;
		this.$notification = $notification;
		if ( options.visibleTimeout ) {
			this.timeout = require( 'mediawiki.visibleTimeout' );
		} else {
			this.timeout = {
				set: setTimeout,
				clear: clearTimeout
			};
		}
	}

	/**
	 * Start the notification. Called automatically by mw.notification#notify
	 * (possibly asynchronously on document-ready).
	 *
	 * This inserts the notification into the page, closes any matching tagged notifications,
	 * handles the fadeIn animations and replacement transitions, and starts autoHide timers.
	 *
	 * @private
	 */
	Notification.prototype.start = function () {
		$area.css( 'display', '' );

		if ( this.isOpen ) {
			return;
		}

		this.isOpen = true;
		openNotificationCount++;

		const options = this.options;
		const $notification = this.$notification;

		let $tagMatches;
		if ( options.tag ) {
			// Find notifications with the same tag
			$tagMatches = $area.find( '.mw-notification-tag-' + options.tag );
		}

		// If we found existing notification with the same tag, replace them
		if ( options.tag && $tagMatches.length ) {

			// While there can be only one "open" notif with a given tag, there can be several
			// matches here because they remain in the DOM until the animation is finished.
			$tagMatches.each( function () {
				const notif = $( this ).data( 'mw-notification' );
				if ( notif && notif.isOpen ) {
					// Detach from render flow with position absolute so that the new tag can
					// occupy its space instead.
					notif.$notification
						.css( {
							position: 'absolute',
							width: notif.$notification.width()
						} )
						.css( notif.$notification.position() )
						.addClass( 'mw-notification-replaced' );
					notif.close();
				}
			} );

			$notification
				.insertBefore( $tagMatches.first() )
				.addClass( 'mw-notification-visible' );
		} else {
			$area.append( $notification );
			requestAnimationFrame( () => {
				// This frame renders the element in the area (invisible)
				requestAnimationFrame( () => {
					$notification.addClass( 'mw-notification-visible' );
				} );
			} );
		}

		// By default a notification is paused.
		// If this notification is within the first {autoHideLimit} notifications then
		// start the auto-hide timer as soon as it's created.
		const autohideCount = $area.find( '.mw-notification-autohide' ).length;
		if ( autohideCount <= notification.autoHideLimit ) {
			this.resume();
		}
	};

	/**
	 * Pause any running auto-hide timer for this notification.
	 *
	 * @memberof Notification
	 */
	Notification.prototype.pause = function () {
		if ( this.isPaused ) {
			return;
		}
		this.isPaused = true;

		if ( this.timeoutId ) {
			this.timeout.clear( this.timeoutId );
			delete this.timeoutId;
		}
	};

	/**
	 * Start autoHide timer if not already started.
	 * Does nothing if autoHide is disabled.
	 * Either to resume from pause or to make the first start.
	 *
	 * @memberof Notification
	 */
	Notification.prototype.resume = function () {
		if ( !this.isPaused ) {
			return;
		}
		// Start any autoHide timeouts
		if ( this.options.autoHide ) {
			this.isPaused = false;
			this.timeoutId = this.timeout.set( () => {
				// Already finished, so don't try to re-clear it
				delete this.timeoutId;
				this.close();
			}, this.autoHideSeconds * 1000 );
		}
	};

	/**
	 * Close the notification.
	 *
	 * @memberof Notification
	 */
	Notification.prototype.close = function () {
		if ( !this.isOpen ) {
			return;
		}

		this.isOpen = false;
		openNotificationCount--;

		// Clear any remaining timeout on close
		this.pause();

		// Remove the mw-notification-autohide class from the notification to avoid
		// having a half-closed notification counted as a notification to resume
		// when handling {autoHideLimit}.
		this.$notification.removeClass( 'mw-notification-autohide' );

		// Now that a notification is being closed. Start auto-hide timers for any
		// notification that has now become one of the first {autoHideLimit} notifications.
		notification.resume();

		requestAnimationFrame( () => {
			this.$notification.removeClass( 'mw-notification-visible' );

			setTimeout( () => {
				if ( openNotificationCount === 0 ) {
					// Hide the area after the last notification closes. Otherwise, the padding on
					// the area can be obscure content, despite the area being empty/invisible (T54659). // FIXME
					$area.css( 'display', 'none' );
					this.$notification.remove();
				} else {
					// FIXME: Use CSS transition
					// eslint-disable-next-line no-jquery/no-slide
					this.$notification.slideUp( 'fast', function () {
						$( this ).remove();
					} );
				}
			}, 500 );
		} );
	};

	/**
	 * Helper function, take a list of notification divs and call
	 * a function on the Notification instance attached to them.
	 *
	 * @private
	 * @static
	 * @param {jQuery} $notifications A jQuery object containing notification divs
	 * @param {string} fn The name of the function to call on the Notification instance
	 */
	function callEachNotification( $notifications, fn ) {
		$notifications.each( function () {
			const notif = $( this ).data( 'mw-notification' );
			if ( notif ) {
				notif[ fn ]();
			}
		} );
	}

	/**
	 * Initialisation.
	 * Must only be called once, and not before the document is ready.
	 *
	 * @ignore
	 */
	function init() {
		let offset, $overlay,
			isFloating = false;

		function updateAreaMode() {
			const shouldFloat = window.pageYOffset > offset.top;
			if ( isFloating === shouldFloat ) {
				return;
			}
			isFloating = shouldFloat;
			$area
				.toggleClass( 'mw-notification-area-floating', isFloating )
				.toggleClass( 'mw-notification-area-layout', !isFloating );
		}

		// Look for a preset notification area in the skin.
		// 'data-mw*' attributes are banned from user content in Sanitizer.
		$area = $( '.mw-notification-area[data-mw="interface"]' ).first();
		if ( !$area.length ) {
			$area = $( '<div>' ).addClass( 'mw-notification-area' );
			// Create overlay div for the notification area
			$overlay = $( '<div>' ).addClass( 'mw-notification-area-overlay' );
			// Append the notification area to the overlay wrapper area
			$overlay.append( $area );
			$( document.body ).append( $overlay );
		}
		$area
			.addClass( 'mw-notification-area-layout' )
			// The ID attribute here is deprecated.
			.attr( 'id', 'mw-notification-area' )
			// Pause auto-hide timers when the mouse is in the notification area.
			.on( {
				mouseenter: notification.pause,
				mouseleave: notification.resume
			} )
			// When clicking on a notification close it.
			.on( 'click', '.mw-notification', function () {
				const notif = $( this ).data( 'mw-notification' );
				if ( notif ) {
					notif.close();
				}
			} )
			// Stop click events from <a> and <select> tags from propagating to prevent clicks
			// from hiding a notification. stopPropagation() bubbles up, not down,
			// hence this should not conflict with OOUI's own click handlers.
			.on( 'click', 'a, select, .oo-ui-dropdownInputWidget', ( e ) => {
				e.stopPropagation();
			} );

		// Read from the DOM:
		// Must be in the next frame to avoid synchronous layout
		// computation from offset()/getBoundingClientRect().
		requestAnimationFrame( () => {
			let notif;

			offset = $area.offset();

			// Initial mode (reads, and then maybe writes)
			updateAreaMode();

			// Once we have the offset for where it would normally render, set the
			// initial state of the (currently empty) notification area to be hidden.
			$area.css( 'display', 'none' );

			$( window ).on( 'scroll', updateAreaMode );

			// Handle pre-ready queue.
			isPageReady = true;
			while ( preReadyNotifQueue.length ) {
				notif = preReadyNotifQueue.shift();
				notif.start();
			}
		} );
	}

	/**
	 * Library for sending notifications to end users.
	 *
	 * @namespace mw.notification
	 * @memberof mw
	 * @singleton
	 */
	notification = {
		/**
		 * Pause auto-hide timers for all notifications.
		 * Notifications will not auto-hide until resume is called.
		 *
		 * @memberof mw.notification
		 */
		pause: function () {
			callEachNotification(
				$area.children( '.mw-notification' ),
				'pause'
			);
		},

		/**
		 * Resume any paused auto-hide timers from the beginning.
		 * Only the first {@link mw.notification.autoHideLimit} timers will be resumed.
		 *
		 * @memberof mw.notification
		 */
		resume: function () {
			callEachNotification(
				// Only call resume on the first #autoHideLimit notifications.
				// Exclude noautohide notifications to avoid bugs where #autoHideLimit
				// `{ autoHide: false }` notifications are at the start preventing any
				// auto-hide notifications from being autohidden.
				$area.children( '.mw-notification-autohide' ).slice( 0, notification.autoHideLimit ),
				'resume'
			);
		},

		/**
		 * Display a notification message to the user.
		 *
		 * @memberof mw.notification
		 * @param {HTMLElement|HTMLElement[]|jQuery|mw.Message|string} message
		 * @param {mw.notification.NotificationOptions} [options] The options to use
		 *  for the notification. Options not specified default to the values in
		 *  [#defaults]{@link mw.notification.defaults}.
		 * @return {mw.notification~Notification} Notification object
		 */
		notify: function ( message, options ) {
			options = Object.assign( {}, notification.defaults, options );

			const notif = new Notification( message, options );

			if ( isPageReady ) {
				notif.start();
			} else {
				preReadyNotifQueue.push( notif );
			}

			return notif;
		},

		/**
		 * @memberof mw.notification
		 * @typedef {Object} NotificationOptions
		 * @property {boolean} autoHide Whether the notification should automatically
		 *   be hidden after shown. Or if it should persist.
		 * @property {string} autoHideSeconds Key to
		 *   [#autoHideSeconds]{@link mw.notification.autoHideSeconds} for number of
		 *   seconds for timeout of auto-hide notifications.
		 * @property {string|null} tag When a notification is tagged only one message
		 *   with that tag will be displayed. Trying to display a new notification
		 *   with the same tag as one already being displayed will cause the other
		 *   notification to be closed and this new notification to open up inside
		 *   the same place as the previous notification.
		 * @property {string|null} title Title for the notification. Will be displayed
		 *   above the content. Usually in bold.
		 * @property {string|null} type The type of the message used for styling.
		 *   Examples: `info`, `warn`, `error`, `success`.
		 * @property {boolean} visibleTimeout Whether the autoHide timeout should be
		 *   based on time the page was visible to user. Or if it should use wall
		 *   clock time.
		 * @property {string|false} id HTML ID to set on the notification element.
		 * @property {string|string[]|false} classes CSS class names to be set on the
		 *   notification element.
		 */

		/**
		 * The defaults for [#notify]{@link mw.notification.notify} options parameter.
		 *
		 * @memberof mw.notification
		 * @type {mw.notification.NotificationOptions}
		 */
		defaults: {
			autoHide: true,
			autoHideSeconds: 'short',
			tag: null,
			title: null,
			type: null,
			visibleTimeout: true,
			id: false,
			classes: false
		},

		/**
		 * Map of predefined auto-hide timeout keys to second values. `short` is
		 * used by default, and other values can be added for use in [#notify]{@link mw.notification.notify}.
		 *
		 * @memberof mw.notification
		 * @type {Object.<string, number>}
		 * @property {number} short 5 seconds (default)
		 * @property {number} long 30 seconds
		 */
		autoHideSeconds: {
			short: 5,
			long: 30
		},

		/**
		 * Maximum number of simultaneous notifications to start auto-hide timers for.
		 * Only this number of notifications being displayed will be auto-hidden at one time.
		 * Any additional notifications in the list will only start counting their timeout for
		 * auto-hiding after the previous messages have been closed.
		 *
		 * This basically represents the minimal number of notifications the user should
		 * be able to process during the {@link mw.notification.defaults default} `autoHideSeconds` time.
		 *
		 * @memberof mw.notification
		 * @type {number}
		 */
		autoHideLimit: 3
	};

	if ( window.QUnit ) {
		$area = $( document.body );
	} else {
		// Don't run UI logic while under test.
		// Let the test control this instead.
		$( init );
	}

	mw.notification = notification;

}() );
