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
	var mwTimestampRegex = /^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/;

	// This is a key-value store of every time unit for which we have i18n messages.
	// If you want to add something here, feel free, but also add messages by the same name to
	// core/languages/messages/MessagesEn.php and .../MessagesQqq.php, so we can render them.
	// You'll also need to include those messages in the dependencies for this module, somewhere
	// in core/mediawiki/resources/Resources.php, and it would be helpful to add tests that verify
	// your intended results.
	var tunits = [
		[ 'seconds', 1000 ], // 1000 milliseconds per second
		[ 'minutes', 60 ], // 60 seconds per minute
		[ 'hours', 60 ], // 60 minutes per hour
		[ 'days', 24 ] // 24 hours per day
	];

	/**
	 * Constructs Time object.
	 * @constructor
	 * @param {Date|String} Time/Date string, or a Date object.
	 * This parameter is optional. If omitted (or set to undefined, null or empty string), then an object will be created
	 * for the current date and time.
	 * @param {Object} Object with options. Anticipate use in the future, currently no effect.
	 */
	mw.Time = function ( time, options ) {
		/**
		 * parse
		 *
		 * Parse a string and set our properties accordingly.
		 * @param {String} Time string (many possible formats)
		 * @param {Object} options
		 * @return {Boolean} success
		 */
		var parse = function ( tobj, str, options ) {
			// If we're lucky, there's no need to do any parsing, and it will
			// just work.
			tobj.dateObj = new Date( str );

			if ( !isNaN( tobj.dateObj.getTime() ) ) {
				// The date object got created fine, we can carry on.
				return true;
			}

			// We weren't lucky. Well, the most likely culprit is MW-style
			// timestamps, so we'd better try to handle that.
			var mwTsMatch = str.match( mwTimestampRegex );
			// JavaScript match returns the entire string, so get rid of that.
			mwTsMatch.shift();
			if ( mwTsMatch.length === 6 ) {
				// We have a MW timestamp, now we can create a Date object with it
				tobj.dateObj = new Date(
					parseInt( mwTsMatch[0], 10 ),
					// JS Date objects use 0-index for the month
					parseInt( mwTsMatch[1], 10 ) - 1,
					parseInt( mwTsMatch[2], 10 ),
					parseInt( mwTsMatch[3], 10 ),
					parseInt( mwTsMatch[4], 10 ),
					parseInt( mwTsMatch[5], 10 )
				);
				if ( !isNaN( tobj.dateObj.getTime() ) ) {
					// The date object got created fine, we can carry on.
					return true;
				}
			}

			// We're out of options, so return failure and reset the dateObj.
			delete tobj.dateObj;
			return false;
		};

		options = typeof options == 'object' ? options : {};
		options = $.extend( {}, options );

		if ( time !== undefined && time !== null && time !== '' ) {
			if ( typeof time === 'string' ) {
				parse( this, time, options );
			} else if ( time instanceof Date ) {
				// Copy data over from existing Date object
				this.dateObj = new Date( time );
			} else if ( time instanceof mw.Time ) {
				// If it's already an instance, then copy the Date object.
				this.dateObj = new Date( time.getDateObj() );
			} else {
				// If the dateObj didn't get created, or if it's not a Date,
				// then we have some kind of problem. Maybe an invalid date.
				throw new Error( 'Could not create Time object. Check format and/or type of the time argument.' );
			}
		} else {
			// If we didn't get a time in the constructor, use the current time.
			this.dateObj = new Date();
		}
	}

	mw.Time.prototype = {
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
			return '' + this.dateObj.getFullYear()
				+ formatDateNumber( this.dateObj.getMonth() + 1 )
				+ formatDateNumber( this.dateObj.getDate() )
				+ formatDateNumber( this.dateObj.getHours() )
				+ formatDateNumber( this.dateObj.getMinutes() )
				+ formatDateNumber( this.dateObj.getSeconds() );
		},

		/**
		 * Returns a user-friendly timestamp (like "3 minutes ago")
		 * @return {String}
		 */
		getHumanTimestamp: function () {
			// JS Date objects are handily subtractable.
			var timeago = new Date() - this.dateObj;

			// This variable holds the current denominator. Start at 1
			// because we'll be multiplying this incrementally.
			var curoff = 1;

			// Define an index for our loop.
			var index;

			// Finally, define a place for the current unit name.
			var curunit;

			// Loop through all of the possible time units in sequence.
			for ( index = 0; index < tunits.length; index++ ) {
				if ( timeago / ( curoff * tunits[index][1] ) < 1 ) {
					// If this unit cannot be used in positive integers to
					// represent the time that has passed, then we break out.
					break;
				}
				// Otherwise, multiply this unit's offset into the current offset.
				curoff *= tunits[index][1];

				// And set the current unit to be this one.
				curunit = tunits[index][0];
			}
			if ( curunit ) {
				// Do some fancy footwork to get a nice-looking timestamp
				return mw.msg( 'ago', mw.msg( curunit, Math.floor( timeago / curoff ) ) );
			} else {
				// Fall back to the "just now" message if our other messages don't have enough resolution.
				return mw.msg( 'just-now' );
			}
		},

		/**
		 * Returns the real Date object we're using for all of this.
		 * @return {Date}
		 */
		getDateObj: function () {
			return new Date( this.dateObj );
		}
	};
}( mediaWiki, jQuery ) );
