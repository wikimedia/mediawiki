/**
 * Implements mediaWiki.notification library
 */
( function ( mw, $, undefined ) {
	'use strict';

	/**
	 * The Notification class.
	 * @see mw.notification.notify
	 */
	function Notification( message, options ) {
		var $notification = $( '<div class="mw-notification"></div>' );
		$notification.data( '-mw-notification', this );
		$notification.addClass( options.autoHide ? 'mw-notification-autohide' : 'mw-notification-noautohide' );
		if ( options.tag ) {
			options.tag = options.tag.replace( /[ _-]+/g, '-' ).replace( /[^-a-z0-9]+/ig, '' );
		}
		if ( options.tag ) {
			$notification.addClass( 'mw-notification-tag-' + options.tag );
		}
		if ( options.title ) {
			var $notificationTitle = $( '<div class="mw-notification-title"></div>' );
			$notificationTitle.text( options.title );
			$notificationTitle.appendTo( $notification );
		}
		var $notificationContent = $( '<div class="mw-notification-content"></div>' );
		if ( typeof message === 'object' ) {
			// A message has a key, parameters, and a parse method. If it looks like a message, use it.
			if ( message.key && $.isArray( message.parameters ) && $.isFunction( message.parse ) ) {
				$notificationContent.html( message.parse() );
			} else {
				$notificationContent.append( message );
			}
		} else {
			$notificationContent.text( message );
		}
		$notificationContent.appendTo( $notification );

		this.isOpen = false;
		this.isPaused = true; // Notifications are initally paused so we can use resume to start them
		this.message = message;
		this.options = options;
		this.$notification = $notification;
	}

	/**
	 * Start the notification. This inserts it, closes any matching tagged notifications,
	 * handles the fadeIn animations and repacement transitions, and turns on autoHide timers.
	 */
	Notification.prototype.start = function() {
		if ( this.isOpen ) {
			return;
		}
		this.isOpen = true;
		var $tagged,
			options = this.options,
			$notification = this.$notification,
			$area = notification.$area,
			// Save the default opacity so that we can animate to it instead of animating to 1
			// and then creating a bad visual effect for the user.
			opacity = this.$notification.css( 'opacity' );
		// Set the opacity to 0 so we can fade-in
		$notification.css( 'opacity', 0 );

		if ( options.tag ) {
			// Check to see if there are any tagged notifications with the same tag as the new one
			var $tagged = $area.find( '.mw-notification-tag-' + options.tag );
		}
		if ( options.tag && $tagged.length ) {
			// If we found a tagged notification use the replacement pattern instead of the new
			// notification fade-in pattern.

			// Iterate over the other tagged notifications to find the outerHeight we should use
			// for the placeholder.
			var outerHeight = 0, placeholderHeight = false;
			$tagged.each( function() {
				var n = $( this ).data( '-mw-notification' );
				if ( n ) {
					// Use the notification's height + padding + border + margins as the placeholder height
					outerHeight = n.$notification.outerHeight( true );
					if ( n.$replacementPlaceholder ) {
						// Grab the height of a placeholder that has not finished animating.
						placeholderHeight = n.$replacementPlaceholder.height();
						// Remove any placeholders added by a previous tagged notification that was in the middle of replacing another.
						// This also makes sure that we only grab the placeholderHeight for the most recent notification.
						n.$replacementPlaceholder.remove();
						n.$replacementPlaceholder = undefined;
					}
					// Close the previous tagged notification
					// Since we're replacing it do this with a fast speed and don't output a placeholder
					// since we're taking care of that transition ourselves.
					n.close( { speed: 'fast', placeholder: false } );
				}
			} );
			if ( placeholderHeight !== false ) {
				// If the other tagged notification was in the middle of replacing another tagged notification
				// continue from the placeholder's height instead of using the outerHeight of the notification
				outerHeight = placeholderHeight;
			}

			$notification
				// Insert the new notification before the tagged notification(s)
				.insertBefore( $tagged.first() )
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
						complete: function() {
							// After we've faded in clear the opacity and let css take over
							$( this ).css( { opacity: '' } );
						}
					} );

			// Create a clear placeholder we can use to make the notifications around the notification that is being
			// replaced expand or contract gracefully to fit the height of the new notification.
			var self = this;
			self.$replacementPlaceholder = $( '<div></div>' )
				// Set the height to the space the previous notification or placeholder took
				.css( 'height', outerHeight )
				// Make sure that this placeholder is at the very end of this tagged notification group
				.insertAfter( $tagged.length ? $tagged[$tagged.length-1] : $notification )
				// Animate the placeholder height to the space that this new notification will take up
				.animate( { height: $notification.outerHeight( true ) },
					{
						// Do space animations fast
						speed: 'fast',
						complete: function() {
							// Reset the notification position after we've finished the space animation
							// However do not do it if the placeholder was removed because another tagged
							// notification went and closed this one.
							if ( self.$replacementPlaceholder ) {
								$notification.css( 'position', '' );
							}
							// Remove the placeholder
							$( this ).remove();
						}
					} );
		} else {
			// Append to the notification area and
			// fastly fade in using the default opacity
			$notification
				// Append the notification to the end of the notification area
				.appendTo( $area )
				// Fade-in the notification
				.animate( { opacity: opacity },
					{
						duration: 'fast',
						complete: function() {
							// After we've faded in clear the opacity and let css take over
							$( this ).css( 'opacity', '' );
						}
					} );
		}

		// If this notification is within the first {autoHideLimit} notifications then
		// start the auto-hide timer as soon as it's created.
		var autohideCount = notification.$area.find( '.mw-notification-autohide' ).length;
		if ( autohideCount <= notification.autoHideLimit ) {
			this.resume();
		}
	};

	/**
	 * Pause any running auto-hide timer for this notification
	 */
	Notification.prototype.pause = function() {
		if ( this.isPaused ) {
			return;
		}
		this.isPaused = true;

		if ( this._timeout ) {
			clearTimeout( this._timeout );
			delete this._timeout;
		}
	};

	/**
	 * If this notification's auto-hide timer has been paused resume it from the start.
	 */
	Notification.prototype.resume = function() {
		var self = this;
		if ( !self.isPaused ) {
			return;
		}
		// Start any autoHide timeouts
		if ( self.options.autoHide ) {
			self.isPaused = false;
			self._timeout = setTimeout( function() {
				// Already finished, so don't try to re-clear it
				delete self._timeout;
				self.close();
			}, notification.autoHideSeconds * 1000 );
		}
	};

	/**
	 * Close/hide the notification.
	 *
	 * @param options object An object containing options for the closing of the notification.
	 *                       These are typically only used internally.
	 *                         speed: Use a close speed different than the default 'slow'
	 *                         placeholder: Set to false to disable the placeholder transition
	 */
	Notification.prototype.close = function( options ) {
		// Don't re-close a notificatio in the middle of fading-out
		if ( !this.isOpen ) {
			return;
		}
		this.isOpen = false;
		// Clear any remaining timeout on close
		this.pause();
		// Options
		options = $.extend( {
			speed: 'slow',
			placeholder: true
		}, options );

		// Remove the mw-notification-autohide class from the notification to avoid having a half-closed
		// notification counted as a notification to resume when handling {autoHideLimit}
		this.$notification.removeClass( 'mw-notification-autohide' );

		// Now that a notification is being closed. Start auto-hide timers for any notification that has
		// now become one of the first {autoHideLimit} notifications.
		notification.resume();

		this.$notification
			.css( {
				// Don't trigger any mouse events while fading out, just in case the cursor happens to be
				// right above us when we transition upwards.
				'pointer-events': 'none',
				// Set an absolute position so we can move upwards in the animation.
				// Notification replacement doesn't look right unless we use an animation like this.
				position: 'absolute',
				// We must fix the width to avoid it shrinking horizontally.
				width: this.$notification.width()
			} )
			// Fix the top/left position to the current computed position to that we can animate top:
			.css( this.$notification.position() )
			// Animate opacity and top to create fade upwards animation for notification closing
			.animate( {
				opacity: 0,
				top: '-=35'
			}, {
				duration: options.speed,
				complete: function() {
					// Remove the notification
					$( this ).remove();
					if ( options.placeholder ) {
						// Use a fast slide up animation after closing to make it look like the notifications
						// below slide up into place when the notification disappears
						$placeholder.slideUp( 'fast', function() {
							// Remove the placeholder
							$( this ).remove();
						} );
					}
				}
			} );

		if ( options.placeholder ) {
			// Insert a placeholder with a height equal to the height of the
			// notification plus it's vertical margins in place of the notification
			var $placeholder = $( '<div></div>' )
				.css( 'height', this.$notification.outerHeight( true ) )
				.insertBefore( this.$notification )
		}
	}

	/**
	 * Helper function, take a list of notification divs and call
	 * a function on the Notification instance attached to them
	 *
	 * @param $notifications jQuery A jQuery object containing notification divs
	 * @param fn string The name of the function to call on the Notification instance
	 */
	function callEachNotification( $notifications, fn ) {
		$notifications.each( function() {
			var n = $( this ).data( '-mw-notification' );
			if ( n ) {
				n[fn]();
			}
		} );
	}

	var notification = {
		/**
		 * Initialisation
		 * (don't call before document ready)
		 */
		init: function() {
			var $area = $( '<div id="mw-notification-area"></div>' );
			$area
				// Pause auto-hide timers when the mouse is in the notification area
				.on( {
					mouseenter: function() {
						notification.pause();
					},
					mouseleave: function() {
						notification.resume();
					}
				} )
				// When clicking on a notification close it
				.on( 'click', '.mw-notification', function() {
					var n = $( this ).data( '-mw-notification' );
					if ( n ) {
						n.close();
					}
				} )
				// Stop click events from <a> tags from propogating to prevent clicking
				// on links from hiding a notification.
				.on( 'click', 'a', function( e ) {
					e.stopPropagation();
				} );

			// Prepend the notification area to the content area and save it's object
			mw.util.$content.prepend( $area );
			notification.$area = $area;

			// Make this a no-op when finished
			notification.init = function() {};
		},

		/**
		 * Pause auto-hide timers for all notifications.
		 * Notifications will not auto-hide until resume is called.
		 */
		pause: function() {
			callEachNotification(
				notification.$area.children( '.mw-notification' ),
				'pause' );
		},

		/**
		 * Resume any paused auto-hide timers from the beginning.
		 * Only the first {autoHideLimit} timers will be resumed.
		 */
		resume: function() {
			callEachNotification(
				// Only call resume on the first {autoHideLimit} notifications.
				// Exclude noautohide notifications to avoid bugs where {autoHideLimit}
				// { autoHide: false } notifications are at the start preventing any
				// auto-hide notifications from being autohidden.
				mw.notification.$area.children( '.mw-notification-autohide' ).slice( 0, notification.autoHideLimit ),
				'resume' );
		},

		/**
		 * Display a notification message to the user.
		 *
		 * @param message {mixed} The DOM-element, jQuery object, mw.Message instance,
		 *                        or plaintext string to be used as the message.
		 * @param options {object} The options to use for the notification.
		 *                         See mw.notification.defaults for details.
		 */
		notify: function( message, options ) {
			notification.init();
			options = $.extend( {}, notification.defaults, options );

			// Create the notification and then start it
			var n = new Notification( message, options );
			n.start();
		},

		/**
		 * @var Object
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
		 * @var number
		 * Number of seconds to wait before auto-hiding notifications.
		 */
		autoHideSeconds: 5,

		/**
		 * @var number
		 * Maximum number of notifications to count down auto-hide timers for.
		 * Only the first {autoHideLimit} notifications being displayed will
		 * auto-hide. Any notifications further down in the list will only start
		 * counting down to auto-hide after the first few messages have closed.
		 */
		autoHideLimit: 3,

		/**
		 * @var jQuery
		 * The #mw-notification-area div that all notifications are contained inside.
		 */
		$area: null
	};

	mw.notification = notification;

}( mediaWiki, jQuery ) );
