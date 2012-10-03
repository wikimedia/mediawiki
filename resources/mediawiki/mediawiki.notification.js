/**
 * Implements mediaWiki.notification library
 */
( function ( mw, $ ) {
	'use strict';

	var isPageReady = false,
		isInitialized = false,
		preReadyNotifQueue = [],
		/**
		 * @var {jQuery}
		 * The #mw-notification-area div that all notifications are contained inside.
		 */
		$area = null;

	/**
	 * Creates a Notification object for 1 message.
	 * Does not insert anything into the document (see .start()).
	 *
	 * @constructor
	 * @see mw.notification.notify
	 */
	function Notification( message, options ) {
		var $notification, $notificationTitle, $notificationContent;

		$notification = $( '<div class="mw-notification"></div>' )
			.data( 'mw.notification', this )
			.addClass( options.autoHide ? 'mw-notification-autohide' : 'mw-notification-noautohide' );

		if ( options.tag ) {
			// Sanitize options.tag before it is used by any code. (Including Notification class methods)
			options.tag = options.tag.replace( /[ _\-]+/g, '-' ).replace( /[^\-a-z0-9]+/ig, '' );
			if ( options.tag ) {
				$notification.addClass( 'mw-notification-tag-' + options.tag );
			} else {
				delete options.tag;
			}
		}

		if ( options.title ) {
			$notificationTitle = $( '<div class="mw-notification-title"></div>' )
				.text( options.title )
				.appendTo( $notification );
		}

		$notificationContent = $( '<div class="mw-notification-content"></div>' );

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
		// isOpen: Set to true after .start() is called to avoid double calls.
		//         Set back to false after .close() to avoid duplicating the close animation.
		// isPaused: false after .resume(), true after .pause(). Avoids duplicating or breaking the hide timeouts.
		//           Set to true initially so .start() can call .resume().
		// message: The message passed to the notification. Unused now but may be used in the future
		//          to stop replacement of a tagged notification with another notification using the same message.
		// options: The options passed to the notification with a little sanitization. Used by various methods.
		// $notification: jQuery object containing the notification DOM node.
		this.isOpen = false;
		this.isPaused = true;
		this.message = message;
		this.options = options;
		this.$notification = $notification;
	}

	/**
	 * Start the notification.
	 * This inserts it into the page, closes any matching tagged notifications,
	 * handles the fadeIn animations and repacement transitions, and starts autoHide timers.
	 */
	Notification.prototype.start = function () {
		var
			// Local references
			$notification, options,
			// Original opacity so that we can animate back to it later
			opacity,
			// Other notification elements matching the same tag
			$tagMatches,
			outerHeight,
			placeholderHeight;

		if ( this.isOpen ) {
			return;
		}

		this.isOpen = true;

		options = this.options;
		$notification = this.$notification;

		opacity = this.$notification.css( 'opacity' );

		// Set the opacity to 0 so we can fade in later.
		$notification.css( 'opacity', 0 );

		if ( options.tag ) {
			// Check to see if there are any tagged notifications with the same tag as the new one
			$tagMatches = $area.find( '.mw-notification-tag-' + options.tag );
		}

		// If we found a tagged notification use the replacement pattern instead of the new
		// notification fade-in pattern.
		if ( options.tag && $tagMatches.length ) {

			// Iterate over the tag matches to find the outerHeight we should use
			// for the placeholder.
			outerHeight = 0;
			$tagMatches.each( function () {
				var notif = $( this ).data( 'mw.notification' );
				if ( notif ) {
					// Use the notification's height + padding + border + margins
					// as the placeholder height.
					outerHeight = notif.$notification.outerHeight( true );
					if ( notif.$replacementPlaceholder ) {
						// Grab the height of a placeholder that has not finished animating.
						placeholderHeight = notif.$replacementPlaceholder.height();
						// Remove any placeholders added by a previous tagged
						// notification that was in the middle of replacing another.
						// This also makes sure that we only grab the placeholderHeight
						// for the most recent notification.
						notif.$replacementPlaceholder.remove();
						delete notif.$replacementPlaceholder;
					}
					// Close the previous tagged notification
					// Since we're replacing it do this with a fast speed and don't output a placeholder
					// since we're taking care of that transition ourselves.
					notif.close( { speed: 'fast', placeholder: false } );
				}
			} );
			if ( placeholderHeight !== undefined ) {
				// If the other tagged notification was in the middle of replacing another
				// tagged notification, continue from the placeholder's height instead of
				// using the outerHeight of the notification.
				outerHeight = placeholderHeight;
			}

			$notification
				// Insert the new notification before the tagged notification(s)
				.insertBefore( $tagMatches.first() )
				.css( {
					// Use an absolute position so that we can use a placeholder to gracefully push other notifications
					// into the right spot.
					position: 'absolute',
					width: $notification.width()
				} )
				// Fade-in the notification
				.animate( { opacity: opacity },
					{
						duration: 'slow',
						complete: function () {
							// After we've faded in clear the opacity and let css take over
							$( this ).css( { opacity: '' } );
						}
					} );

			// Create a clear placeholder we can use to make the notifications around the notification that is being
			// replaced expand or contract gracefully to fit the height of the new notification.
			var self = this;
			self.$replacementPlaceholder = $( '<div>' )
				// Set the height to the space the previous notification or placeholder took
				.css( 'height', outerHeight )
				// Make sure that this placeholder is at the very end of this tagged notification group
				.insertAfter( $tagMatches.eq( -1 ) )
				// Animate the placeholder height to the space that this new notification will take up
				.animate( { height: $notification.outerHeight( true ) },
					{
						// Do space animations fast
						speed: 'fast',
						complete: function () {
							// Reset the notification position after we've finished the space animation
							// However do not do it if the placeholder was removed because another tagged
							// notification went and closed this one.
							if ( self.$replacementPlaceholder ) {
								$notification.css( 'position', '' );
							}
							// Finally, remove the placeholder from the DOM
							$( this ).remove();
						}
					} );
		} else {
			// Append to the notification area and fade in to the original opacity.
			$notification
				.appendTo( $area )
				.animate( { opacity: opacity },
					{
						duration: 'fast',
						complete: function () {
							// After we've faded in clear the opacity and let css take over
							$( this ).css( 'opacity', '' );
						}
					}
				);
		}

		// By default a notification is paused.
		// If this notification is within the first {autoHideLimit} notifications then
		// start the auto-hide timer as soon as it's created.
		var autohideCount = $area.find( '.mw-notification-autohide' ).length;
		if ( autohideCount <= notification.autoHideLimit ) {
			this.resume();
		}
	};

	/**
	 * Pause any running auto-hide timer for this notification
	 */
	Notification.prototype.pause = function () {
		if ( this.isPaused ) {
			return;
		}
		this.isPaused = true;

		if ( this.timeout ) {
			clearTimeout( this.timeout );
			delete this.timeout;
		}
	};

	/**
	 * Start autoHide timer if not already started.
	 * Does nothing if autoHide is disabled.
	 * Either to resume from pause or to make the first start.
	 */
	Notification.prototype.resume = function () {
		var notif = this;
		if ( !notif.isPaused ) {
			return;
		}
		// Start any autoHide timeouts
		if ( notif.options.autoHide ) {
			notif.isPaused = false;
			notif.timeout = setTimeout( function () {
				// Already finished, so don't try to re-clear it
				delete notif.timeout;
				notif.close();
			}, notification.autoHideSeconds * 1000 );
		}
	};

	/**
	 * Close/hide the notification.
	 *
	 * @param {Object} options An object containing options for the closing of the notification.
	 *  These are typically only used internally.
	 *  - speed: Use a close speed different than the default 'slow'.
	 *  - placeholder: Set to false to disable the placeholder transition.
	 */
	Notification.prototype.close = function ( options ) {
		if ( !this.isOpen ) {
			return;
		}
		this.isOpen = false;
		// Clear any remaining timeout on close
		this.pause();

		options = $.extend( {
			speed: 'slow',
			placeholder: true
		}, options );

		// Remove the mw-notification-autohide class from the notification to avoid
		// having a half-closed notification counted as a notification to resume
		// when handling {autoHideLimit}.
		this.$notification.removeClass( 'mw-notification-autohide' );

		// Now that a notification is being closed. Start auto-hide timers for any
		// notification that has now become one of the first {autoHideLimit} notifications.
		notification.resume();

		this.$notification
			.css( {
				// Don't trigger any mouse events while fading out, just in case the cursor
				// happens to be right above us when we transition upwards.
				pointerEvents: 'none',
				// Set an absolute position so we can move upwards in the animation.
				// Notification replacement doesn't look right unless we use an animation like this.
				position: 'absolute',
				// We must fix the width to avoid it shrinking horizontally.
				width: this.$notification.width()
			} )
			// Fix the top/left position to the current computed position from which we
			// can animate upwards.
			.css( this.$notification.position() );

		// This needs to be done *after* notification's position has been made absolute.
		if ( options.placeholder ) {
			// Insert a placeholder with a height equal to the height of the
			// notification plus it's vertical margins in place of the notification
			var $placeholder = $( '<div>' )
				.css( 'height', this.$notification.outerHeight( true ) )
				.insertBefore( this.$notification );
		}

		// Animate opacity and top to create fade upwards animation for notification closing
		this.$notification
			.animate( {
				opacity: 0,
				top: '-=35'
			}, {
				duration: options.speed,
				complete: function () {
					// Remove the notification
					$( this ).remove();
					if ( options.placeholder ) {
						// Use a fast slide up animation after closing to make it look like the notifications
						// below slide up into place when the notification disappears
						$placeholder.slideUp( 'fast', function () {
							// Remove the placeholder
							$( this ).remove();
						} );
					}
				}
			} );
	};

	/**
	 * Helper function, take a list of notification divs and call
	 * a function on the Notification instance attached to them
	 *
	 * @param {jQuery} $notifications A jQuery object containing notification divs
	 * @param {string} fn The name of the function to call on the Notification instance
	 */
	function callEachNotification( $notifications, fn ) {
		$notifications.each( function () {
			var notif = $( this ).data( 'mw.notification' );
			if ( notif ) {
				notif[fn]();
			}
		} );
	}

	/**
	 * Initialisation
	 * (don't call before document ready)
	 */
	function init() {
		if ( !isInitialized ) {
			isInitialized = true;
			$area = $( '<div id="mw-notification-area"></div>' )
				// Pause auto-hide timers when the mouse is in the notification area.
				.on( {
					mouseenter: notification.pause,
					mouseleave: notification.resume
				} )
				// When clicking on a notification close it.
				.on( 'click', '.mw-notification', function () {
					var notif = $( this ).data( 'mw.notification' );
					if ( notif ) {
						notif.close();
					}
				} )
				// Stop click events from <a> tags from propogating to prevent clicking.
				// on links from hiding a notification.
				.on( 'click', 'a', function ( e ) {
					e.stopPropagation();
				} );

			// Prepend the notification area to the content area and save it's object.
			mw.util.$content.prepend( $area );
		}
	}

	var notification = {
		/**
		 * Pause auto-hide timers for all notifications.
		 * Notifications will not auto-hide until resume is called.
		 */
		pause: function () {
			callEachNotification(
				$area.children( '.mw-notification' ),
				'pause'
			);
		},

		/**
		 * Resume any paused auto-hide timers from the beginning.
		 * Only the first {autoHideLimit} timers will be resumed.
		 */
		resume: function () {
			callEachNotification(
				// Only call resume on the first {autoHideLimit} notifications.
				// Exclude noautohide notifications to avoid bugs where {autoHideLimit}
				// { autoHide: false } notifications are at the start preventing any
				// auto-hide notifications from being autohidden.
				$area.children( '.mw-notification-autohide' ).slice( 0, notification.autoHideLimit ),
				'resume'
			);
		},

		/**
		 * Display a notification message to the user.
		 *
		 * @param {mixed} message The DOM-element, jQuery object, mw.Message instance,
		 *  or plaintext string to be used as the message.
		 * @param {Object} options The options to use for the notification.
		 *  See mw.notification.defaults for details.
		 */
		notify: function ( message, options ) {
			var notif;
			options = $.extend( {}, notification.defaults, options );

			notif = new Notification( message, options );

			if ( isPageReady ) {
				notif.start();
			} else {
				preReadyNotifQueue.push( notif );
			}
		},

		/**
		 * @var {Object}
		 * The defaults for mw.notification.notify's options parameter
		 *   autoHide:
		 *     A boolean indicating whether the notifification should automatically
		 *     be hidden after shown. Or if it should persist.
		 *
		 *   tag:
		 *     An optional string. When a notification is tagged only one message
		 *     with that tag will be displayed. Trying to display a new notification
		 *     with the same tag as one already being displayed will cause the other
		 *     notification to be closed and this new notification to open up inside
		 *     the same place as the previous notification.
		 *
		 *   title:
		 *     An optional title for the notification. Will be displayed above the
		 *     content. Usually in bold.
		 */
		defaults: {
			autoHide: true,
			tag: false,
			title: undefined
		},

		/**
		 * @var {number}
		 * Number of seconds to wait before auto-hiding notifications.
		 */
		autoHideSeconds: 5,

		/**
		 * @var {number}
		 * Maximum number of notifications to count down auto-hide timers for.
		 * Only the first {autoHideLimit} notifications being displayed will
		 * auto-hide. Any notifications further down in the list will only start
		 * counting down to auto-hide after the first few messages have closed.
		 *
		 * This basically represents the number of notifications the user should
		 * be able to process in {autoHideSeconds} time.
		 */
		autoHideLimit: 3
	};

	$( function () {
		var notif;

		init();

		// Handle pre-ready queue.
		isPageReady = true;
		while ( preReadyNotifQueue.length ) {
			notif = preReadyNotifQueue.shift();
			notif.start();
		}
	} );

	mw.notification = notification;

}( mediaWiki, jQuery ) );
