'use strict';

const config = require( './config.json' );

const instanceCache = {
	user: null,
	utc: null,
	site: null
};

let supportsOffsetCache = null;

// Private function collection.
// JSDOC doesn't like top-level function declarations.
// These can probably be private static methods when we have ES2022.
const priv = {
	/**
	 * Determine whether the browser supports offset strings like "+00:00", which
	 * were introduced in ECMA-402, 11th edition (2024).
	 *
	 * @return {boolean}
	 */
	supportsOffset() {
		if ( supportsOffsetCache === null ) {
			try {
				// eslint-disable-next-line no-new
				new Intl.DateTimeFormat( undefined, { timeZone: '+01:00' } );
				supportsOffsetCache = true;
			} catch ( e ) {
				supportsOffsetCache = false;
			}
		}
		return supportsOffsetCache;
	},

	/**
	 * Convert an offset in minutes to a string suitable for the timeZone option to
	 * the Intl.DateTimeFormat constructor. We use the MW sign convention for the
	 * input, where positive is positive, not the inverted JS convention.
	 *
	 * @param {number} offset
	 * @return {string|undefined}
	 */
	offsetToZone( offset ) {
		const hour = Math.floor( Math.abs( offset ) / 60 );
		const minute = Math.abs( offset ) % 60;
		if ( priv.supportsOffset() ) {
			const pad = ( x ) => `${ x < 10 ? '0' : '' }${ x }`;
			return `${ offset < 0 ? '-' : '+' }${ pad( hour ) }:${ pad( minute ) }`;
		}
		if ( minute !== 0 ) {
			// Can't represent this zone -- just use the system time zone
			return undefined;
		}
		return `Etc/GMT${ offset < 0 ? '+' : '-' }${ hour }`;
	},

	/**
	 * Check if a zone name is usable as an input to the Intl.DateTimeFormat constructor.
	 * If this returns false, we will use the offset instead.
	 *
	 * @param {string} zoneName
	 * @return {boolean}
	 */
	isKnownZoneName( zoneName ) {
		// Feature test for Safari 11.1
		// eslint-disable-next-line compat/compat
		return Intl.supportedValuesOf && Intl.supportedValuesOf( 'timeZone' ).includes( zoneName );
	},

	/**
	 * Convert a MW time zone option value to an identifier suitable for
	 * Intl.DateTimeFormat. UserOptionsModule does some normalisation so this is
	 * not quite as complex as UserTimeCorrection::parse()
	 *
	 * @param {string|null} optionValue The user option value, if any
	 * @param {string} localZone The configured default zone
	 * @param {number} localOffset The configured default offset, in minutes
	 * @return {string|undefined}
	 */
	normalizeZone( optionValue, localZone, localOffset ) {
		if ( optionValue ) {
			const parts = optionValue.split( '|', 3 );
			if ( parts[ 0 ] === 'ZoneInfo' ) {
				if ( priv.isKnownZoneName( parts[ 2 ] ) ) {
					return parts[ 2 ];
				} else {
					return priv.offsetToZone( parseInt( parts[ 1 ], 10 ) );
				}
			} else if ( parts[ 0 ] === 'Offset' ) {
				return priv.offsetToZone( parts[ 1 ] );
			}
		}
		return priv.isKnownZoneName( localZone ) ? localZone : priv.offsetToZone( localOffset );
	},

	/**
	 * Get the normalized user time zone
	 *
	 * @return {string|undefined}
	 */
	getUserZone() {
		return priv.normalizeZone(
			mw.user.options.get( 'timecorrection' ),
			config.localZone,
			config.localOffset
		);
	},

	/**
	 * Get the normalized site default zone
	 *
	 * @return {string|undefined}
	 */
	getSiteZone() {
		return priv.normalizeZone( null, config.localZone, config.localOffset );
	},

	/**
	 * Get the date style desired by the user, e.g. "dmy".
	 *
	 * @return {string}
	 */
	getUserDateStyle() {
		const def = config.defaultStyle;
		const style = mw.user.options.get( 'date', def );
		return ( !style || style === 'default' ) ? def : style;
	},

	/**
	 * Convert an array of parts as returned by Intl.DateTimeFormat.formatParts
	 * to an object.
	 *
	 * @param {Array} parts
	 * @return {Object}
	 */
	partsToInfo( parts ) {
		const info = {};
		for ( const part of parts ) {
			info[ part.type ] = part.value;
		}
		return info;
	},

	/**
	 * Substitute parameters into the given pattern string
	 *
	 * @param {Object} info
	 * @param {string} pattern
	 * @return {string}
	 */
	formatPattern( info, pattern ) {
		return pattern.replace(
			/\{(\w+)}/g,
			( _, name ) => name in info ? info[ name ] : '?'
		);
	},

	/**
	 * Convert an English "long offset" part value to a string like "+01:00"
	 *
	 * @param {string} offset
	 * @return {string}
	 */
	longOffsetToIsoOffset( offset ) {
		const m = /(GMT|UTC)?([+-][0-9]{2}:[0-9]{2})/.exec( offset );
		return m ? m[ 2 ] : '+00:00';
	}
};

/**
 * @description
 * Time and date formatter class.
 *
 * The main aim of this class is to make it easy to produce a date which is
 * consistent with the way server-side MediaWiki formats dates, in the current
 * user's timezone and preferred format.
 *
 * The input format is always a native Date object. This isn't a date parser
 * class.
 *
 * The most common operations are available as static methods, which have no
 * dependency on "this", so they can be called via destructuring assignment.
 *
 * The constructor is internal. Instances should be obtained via the static
 * factory methods.
 *
 * @example
 * // Static interface
 * const { formatTimeAndDate } = require( 'mediawiki.DateFormatter' );
 * const now = formatTimeAndDate( new Date() );
 *
 * // Non-static interface
 * const DateFormatter = require( 'mediawiki.DateFormatter' );
 * const mwTimestamp = DateFormatter.forUtc().formatMw( new Date() );
 *
 * @since 1.44
 * @exports mediawiki.DateFormatter
 */
class DateFormatter {

	/**
	 * Get a DateFormatter instance configured for the current user
	 *
	 * @return {module:mediawiki.DateFormatter}
	 */
	static forUser() {
		if ( !instanceCache.user ) {
			instanceCache.user = new DateFormatter(
				config.locales,
				config.formats,
				priv.getUserZone(),
				priv.getUserDateStyle()
			);
		}
		return instanceCache.user;
	}

	/**
	 * Get a DateFormatter instance, configured for the current user except with
	 * the time zone set to UTC.
	 *
	 * @return {module:mediawiki.DateFormatter}
	 */
	static forUtc() {
		if ( !instanceCache.utc ) {
			instanceCache.utc = new DateFormatter(
				config.locales,
				config.formats,
				'Etc/GMT',
				priv.getUserDateStyle()
			);
		}
		return instanceCache.utc;
	}

	/**
	 * Get a DateFormatter instance, configured for the current user except with
	 * the site default time zone.
	 *
	 * @return {module:mediawiki.DateFormatter}
	 */
	static forSiteZone() {
		if ( !instanceCache.site ) {
			instanceCache.site = new DateFormatter(
				config.locales,
				config.formats,
				priv.getSiteZone(),
				priv.getUserDateStyle()
			);
		}
		return instanceCache.site;
	}

	/**
	 * Format a Date to a time and date string (static variant)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	static formatTimeAndDate( date ) {
		return DateFormatter.forUser().formatTimeAndDate( date );
	}

	/**
	 * Format a Date to a date string (static variant)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	static formatDate( date ) {
		return DateFormatter.forUser().formatDate( date );
	}

	/**
	 * Format a Date to a time string (static variant)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	static formatTime( date ) {
		return DateFormatter.forUser().formatTime( date );
	}

	/**
	 * Format a Date to a date string without the year (static variant)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	static formatPrettyDate( date ) {
		return DateFormatter.forUser().formatPrettyDate( date );
	}

	// No static method for formatMw() -- use forUtc().formatMw()

	/**
	 * Format a Date in ISO 8601 format, including timezone designator.
	 * (static variant)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	static formatIso( date ) {
		return DateFormatter.forUser().formatIso( date );
	}

	/**
	 * Format a Date in ISO 8601 format, without the timezone designator,
	 * implying unqualified local time, and without seconds. This is suitable
	 * for passing to a datetime-local input. (static variant)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	static formatForDateTimeInput( date ) {
		return DateFormatter.forUser().formatForDateTimeInput( date );
	}

	/**
	 * Format a date range, showing time and date. (static variant)
	 *
	 * TODO: Have MW provide localisation.
	 *   This function depends on the browser for localisation. It does not
	 *   properly respect the user's date format preferences, and has limited
	 *   language support.
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {string}
	 */
	static formatTimeAndDateRange( date1, date2 ) {
		return DateFormatter.forUser().formatTimeAndDateRange( date1, date2 );
	}

	/**
	 * Format a date range, showing time only. (static variant)
	 *
	 * TODO: Have MW provide localisation.
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {string}
	 */
	static formatTimeRange( date1, date2 ) {
		return DateFormatter.forUser().formatTimeRange( date1, date2 );
	}

	/**
	 * Format a date range, showing date only. (static variant)
	 *
	 * TODO: Have MW provide localisation.
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {string}
	 */
	static formatDateRange( date1, date2 ) {
		return DateFormatter.forUser().formatDateRange( date1, date2 );
	}

	/**
	 * Get the short name of the time zone, e.g. "PST".
	 *
	 * @param {Date} date The reference date
	 * @return {string}
	 */
	static getShortZoneName( date ) {
		return DateFormatter.forUser().getShortZoneName( date );
	}

	/**
	 * Get the time zone offset as a number of minutes, in the sign-preserving
	 * convention, e.g. "+01:00" becomes 60.
	 *
	 * @param {Date} date The reference date for DST
	 * @return {number}
	 */
	static getZoneOffsetMinutes( date ) {
		return DateFormatter.forUser().getZoneOffsetMinutes( date );
	}

	/**
	 * Clear the instance cache. For testing.
	 *
	 * @internal
	 * @ignore
	 */
	static clearInstanceCache() {
		instanceCache.user = null;
		instanceCache.utc = null;
		instanceCache.site = null;
		supportsOffsetCache = null;
	}

	/**
	 * @internal
	 * @hideconstructor
	 *
	 * @param {string[]} locales The locale fallback chain
	 * @param {Object} formats The available date formats, indexed by combined
	 *   style+type key, e.g. "dmy pretty". The property values are objects with
	 *   the following properties:
	 *     - locale: {string} A locale name, overriding the locales parameter
	 *     - pattern: {string} A pattern for formatPattern()
	 *     - options: {object} Options to pass to Intl.DateTimeFormat()
	 *     - error: {string} A parse error relating to the localisation source
	 * @param {string|undefined} zone The normalized time zone identifier
	 * @param {string} style The selected date style, one of those available in
	 *   formats, e.g. "dmy". The user's preferred format with fallbacks applied.
	 */
	constructor( locales, formats, zone, style ) {
		this.locales = locales;
		this.formats = Object.assign( {}, formats );
		const machineTemplate = {
			locale: 'en',
			options: {
				year: 'numeric', month: '2-digit', day: '2-digit',
				hour: '2-digit', minute: '2-digit', second: '2-digit',
				hour12: false, timeZoneName: 'longOffset'
			}
		};
		this.formats.datetime = Object.assign(
			{ pattern: '{year}-{month}-{day}T{hour}:{minute}' },
			machineTemplate
		);
		this.formats.iso = Object.assign(
			{ pattern: '{year}-{month}-{day}T{hour}:{minute}:{second}{mwZoneOffset}' },
			machineTemplate
		);
		this.formats.mw = Object.assign(
			{ pattern: '{year}{month}{day}{hour}{minute}{second}' },
			machineTemplate
		);
		this.formats.monthNumber = { locale: 'en', options: { month: 'numeric' } };
		this.zone = zone;
		this.intlFormatters = {};
		this.style = style;
	}

	/**
	 * Format a Date to a time and date string (non-static)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	formatTimeAndDate( date ) {
		return this.formatInternal( this.style, 'both', date );
	}

	/**
	 * Format a Date to a date string (non-static)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	formatDate( date ) {
		return this.formatInternal( this.style, 'date', date );
	}

	/**
	 * Format a Date to a time string (non-static)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	formatTime( date ) {
		return this.formatInternal( this.style, 'time', date );
	}

	/**
	 * Format a Date to a date string without the year (non-static)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	formatPrettyDate( date ) {
		return this.formatInternal( this.style, 'pretty', date );
	}

	/**
	 * Format a Date to a MediaWiki 14-character timestamp. Since MediaWiki
	 * timestamps are conventionally UTC, this should typically be called on an
	 * instance retrieved with forUtc().
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	formatMw( date ) {
		return this.formatInternal( null, 'mw', date );
	}

	/**
	 * Format a Date in ISO 8601 format, including timezone designator.
	 * (non-static)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	formatIso( date ) {
		return this.formatInternal( null, 'iso', date );
	}

	/**
	 * Format a Date in ISO 8601 format, without the timezone designator,
	 * implying unqualified local time, and without seconds. This is suitable
	 * for passing to a datetime-local input. (non-static)
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	formatForDateTimeInput( date ) {
		return this.formatInternal( null, 'datetime', date );
	}

	/**
	 * Format a date range, showing time and date. (non-static)
	 *
	 * TODO: Have MW provide localisation.
	 *   This function depends on the browser for localisation. It does not
	 *   properly respect the user's date format preferences, and has limited
	 *   language support.
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {string}
	 */
	formatTimeAndDateRange( date1, date2 ) {
		return this.formatRangeInternal( this.style, 'both', date1, date2 );
	}

	/**
	 * Format a date range, showing time only. (non-static)
	 *
	 * TODO: Have MW provide localisation.
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {string}
	 */
	formatTimeRange( date1, date2 ) {
		return this.formatRangeInternal( this.style, 'time', date1, date2 );
	}

	/**
	 * Format a date range, showing date only. (non-static)
	 *
	 * TODO: Have MW provide localisation.
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {string}
	 */
	formatDateRange( date1, date2 ) {
		return this.formatRangeInternal( this.style, 'date', date1, date2 );
	}

	/**
	 * Get the short name of the time zone, e.g. "PST".
	 *
	 * @param {Date} date The reference date
	 * @return {string}
	 */
	getShortZoneName( date ) {
		return this.getLocalInfoInternal( 'timeZoneName', 'short', date );
	}

	/**
	 * Get the time zone offset as a number of minutes, in the sign-preserving
	 * convention, e.g. "+01:00" becomes 60.
	 *
	 * @param {Date} date The reference date for DST
	 * @return {number}
	 */
	getZoneOffsetMinutes( date ) {
		const [ h, m ] =
			priv.longOffsetToIsoOffset(
				this.getLocalInfoInternal( 'timeZoneName', 'longOffset', date )
			).split( ':' );
		return h * 60 + Math.sign( +h ) * m;
	}

	/**
	 * Format a date/time with a specified style.
	 *
	 * @internal
	 * @ignore
	 *
	 * @param {string|null} style The style, e.g. "dmy", or null to use an
	 *   internal type identifier for a machine-readable style.
	 * @param {string} type The type, e.g. "both" for both time and date. If
	 *   style is null then this can be an internal style added by the
	 *   constructor.
	 * @param {Date} date
	 * @return {string}
	 */
	formatInternal( style, type, date ) {
		const formatName = this.makeValidFormatName( style, type );
		const formatter = this.getIntlFormatInternal( formatName );
		const pattern = this.formats[ formatName ].pattern;
		if ( pattern ) {
			const info = priv.partsToInfo( formatter.formatToParts( date ) );
			if ( pattern.includes( '{mwMonth' ) ) {
				const i = +this.getIntlFormatInternal( 'monthNumber' ).format( date );
				[ info.mwMonth, info.mwMonthGen, info.mwMonthAbbrev ] = config.months[ i ] || [];
			}
			if ( pattern.includes( '{mwZoneOffset}' ) ) {
				info.mwZoneOffset = priv.longOffsetToIsoOffset( info.timeZoneName );
			}
			return priv.formatPattern( info, pattern );
		} else {
			return formatter.format( date );
		}
	}

	/**
	 * Validate a style/type and combine them into a single string, falling
	 * back to the default style if the user style is not available with the
	 * specified type.
	 *
	 * @internal
	 * @ignore
	 *
	 * @param {string|null} style
	 * @param {string} type
	 * @return {string}
	 */
	makeValidFormatName( style, type ) {
		if ( !style ) {
			return type;
		}
		// Try the specified style, then the site default style, then "dmy", a
		// final fallback which should always exist, because localised date
		// format arrays are merged with English, which has "dmy".
		for ( const tryStyle of [ style, config.defaultStyle, 'dmy' ] ) {
			const name = `${ tryStyle } ${ type }`;
			if ( name in this.formats ) {
				return name;
			}
		}
		// Perhaps an invalid type, or bad config?
		throw new Error( `Unable to find a valid date format for "${ style } ${ type }"` );
	}

	/**
	 * Format a time/date range with a specified style
	 *
	 * @internal
	 * @ignore
	 *
	 * @param {string} style
	 * @param {string} type
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {string}
	 */
	formatRangeInternal( style, type, date1, date2 ) {
		const formatName = this.makeValidFormatName( style, type );
		const formatter = this.getIntlFormatInternal( formatName );
		const pattern = this.formats[ formatName ].rangePattern;
		if ( pattern ) {
			return priv.formatPattern(
				priv.partsToInfo( formatter.formatRangeToParts( date1, date2 ) ),
				pattern
			);
		} else {
			return formatter.formatRange( date1, date2 );
		}
	}

	/**
	 * Get a DateTimeFormat object configured for the specified combined
	 * type/style.
	 *
	 * @internal
	 * @ignore
	 *
	 * @param {string} formatName
	 * @return {Intl.DateTimeFormat}
	 */
	getIntlFormatInternal( formatName ) {
		if ( !( formatName in this.intlFormatters ) ) {
			if ( !( formatName in this.formats ) ) {
				throw new Error( `Unknown date format "${ formatName }"` );
			}
			const format = this.formats[ formatName ];
			const locale = format.locale || this.locales;
			const options = Object.assign( {}, format.options );
			options.timeZone = this.zone;
			this.intlFormatters[ formatName ] = new Intl.DateTimeFormat( locale, options );
		}
		return this.intlFormatters[ formatName ];
	}

	/**
	 * Extract a part value from a formatted date, with the given configuration
	 * for that part value.
	 *
	 * @internal
	 * @ignore
	 *
	 * @param {string} fieldName
	 * @param {string} configValue
	 * @param {Date} date
	 * @return {string}
	 */
	getLocalInfoInternal( fieldName, configValue, date ) {
		const key = `info ${ fieldName }.${ configValue }`;
		if ( !this.formats[ key ] ) {
			this.formats[ key ] = { options: { [ fieldName ]: configValue } };
		}
		const formatter = this.getIntlFormatInternal( key );
		const info = priv.partsToInfo( formatter.formatToParts( date ) );
		return info[ fieldName ] || '';
	}
}

if ( window.QUnit ) {
	DateFormatter.priv = priv;
	DateFormatter.config = config;
}

module.exports = DateFormatter;
