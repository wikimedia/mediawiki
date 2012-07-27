/**
 * Library for creating and parsing MW-style timestamps.
 *
 * Dependencies: mw, mw.msg
 *
 * @author Mark Holmquist, 2012
 * @since 1.20
 */

( function ( mw, $ ) {
	// This is the regex that will be used to test for MW-style timestamps in the
	// parser. Change at your own risk.
	var mwTimestampRegex = /\d{14}/;

	// This is a key-value store of every time unit for which we have i18n messages.
	// If you want to add something here, feel free, but also add messages by the same name to
	// core/languages/messages/MessagesEn.php and .../MessagesQqq.php, so we can render them.
	// You'll also need to include those messages in the dependencies for this module, somewhere
	// in core/mediawiki/resources/Resources.php, and it would be helpful to add tests that verify
	// your intended results.
	var tunits = {
		seconds: 1000, // 1000 milliseconds per second
		minutes: 60, // 60 seconds per minute
		hours: 60, // 60 minutes per hour
		days: 24 // 24 hours per day
	};

	/**
	 * Constructs Time object.
	 * @constructor
	 * @param {Object|String} Time/Date string, or a Date object.
	 * This parameter is optional. If omitted (or set to undefined, null or empty string), then an object will be created
	 * for the current date and time.
	 * @param {Date} Object with options. Anticipate use in the future, currently no effect.
	 */
	mw.Time = function ( time, options ) {
		options = typeof options == 'object' ? options : {};
		options = $.extend( {}, options );

		if ( time !== undefined && time !== null && time !== '' ) {
			if ( typeof time === 'string' ) {
				this.parse( time, options );
			} else if ( time instanceof Date ) {
				// Copy data over from existing Date object
				this.dateObj = time;
			} else if ( time instanceof mw.Time ) {
				// If it's already an instance, no reason to convert anything.
				// If you expected a copy, use mw.Time.clone();
				return time;
			}
		} else {
			// If we didn't get a time in the constructor, use the current time.
			this.dateObj = new Date();
		}

		if ( !this.dateObj || !( this.dateObj instanceof Date ) ) {
			// If the dateObj got created, or if it's not a Date, then we have
			// some kind of problem. Maybe an invalid date.
			throw new Error( 'Could not create Time object. Check format and/or type of the time argument.' );
		}
	}

	mw.Time.prototype = {
		/**
		 * mw.Time.parse
		 *
		 * Parse a string and set our properties accordingly.
		 * @param {String} Time string (many possible formats)
		 * @param {Object} options
		 * @return {Boolean} success
		 */
		parse: function ( str, options ) {
			// If we're lucky, there's no need to do any parsing, and it will
			// just work.
			this.dateObj = new Date( str );

			if ( !isNaN( this.dateObj.getTime() ) ) {
				// The date object got created fine, we can carry on.
				return true;
			}

			// We weren't lucky. Well, the most likely culprit is MW-style
			// timestamps, so we'd better try to handle that.
			if ( mwTimestampRegex.test( str ) ) {
				// We have a MW timestamp, now we can create a Date object with it
				this.dateObj = new Date(
					parseInt( str.substr( 0, 4 ), 10 ),
					// JS Date objects use 0-index for the month
					parseInt( str.substr( 4, 2 ), 10 ) - 1,
					parseInt( str.substr( 6, 2 ), 10 ),
					parseInt( str.substr( 8, 2 ), 10 ),
					parseInt( str.substr( 10, 2 ), 10 ),
					parseInt( str.substr( 12, 2 ), 10 )
				);
				if ( !isNaN( this.dateObj.getTime() ) ) {
					// The date object got created fine, we can carry on.
					return true;
				}
			}

			// We're out of options, so return failure and reset the dateObj.
			this.dateObj = false;
			return false;
		},

		/**
		 * Returns an MW-style timestamp.
		 * @return {String}
		 */
		getMwTimestamp: function () {
			function formatDateNumber( dnum ) {
				if ( dnum < 10 ) {
					return '0' + dnum;
				} else {
					return '' + dnum;
				}
			}
			var timeString = '';
			timeString += this.dateObj.getFullYear();
			timeString += formatDateNumber( this.dateObj.getMonth() + 1 );
			timeString += formatDateNumber( this.dateObj.getDate() );
			timeString += formatDateNumber( this.dateObj.getHours() );
			timeString += formatDateNumber( this.dateObj.getMinutes() );
			timeString += formatDateNumber( this.dateObj.getSeconds() );
			return timeString;
		},

		/**
		 * Returns a user-friendly timestamp (like "3 minutes ago")
		 * @return {String}
		 */
		getHumanTimestamp: function () {
			// JS Date objects are handily subtractable.
			var timeago = new Date() - this.dateObj;
			// Fall back to the "justnow" message if our other messages don't have enough resolution.
			var timestring = mw.msg( 'just-now' );
			// This variable holds the current denominator. Start at 1
			// because we'll be multiplying this incrementally.
			var curoff = 1;
			// The tunits object is defined at the beginning of this class,
			// it houses conversion rates.
			for ( var tunit in tunits ) {
				curoff *= tunits[tunit];
				if ( timeago / curoff >= 1 ) {
					// If we can say at least 1 of this time unit has passed,
					// then use that message to represent the time rather than
					// whatever we had been using previously--but first round down.
					timeago = Math.floor( timeago );
					timestring = mw.msg( tunit, Math.floor( timeago / curoff ) );
					timestring = mw.msg( 'ago', timestring );
				} else {
					break;
				}
			}
			return timestring;
		},

		/**
		 * Returns the real Date object we're using for all of this.
		 * @return {Date}
		 */
		getDateObj: function () {
			return this.dateObj;
		}
	};
}( mediaWiki, jQuery ) );
