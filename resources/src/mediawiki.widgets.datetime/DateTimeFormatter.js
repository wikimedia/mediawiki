( function ( $, mw ) {

	/**
	 * Provides various methods needed for formatting dates and times.
	 *
	 * @class
	 * @abstract
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [format='@default'] May be a key from the {@link #static-formats static formats},
	 *  or a format specification as defined by {@link #method-parseFieldSpec parseFieldSpec}
	 *  and {@link #method-getFieldForTag getFieldForTag}.
	 * @cfg {boolean} [local=false] Whether dates are local time or UTC
	 * @cfg {string[]} [fullZones] Time zone indicators. Array of 2 strings, for
	 *  UTC and local time.
	 * @cfg {string[]} [shortZones] Abbreviated time zone indicators. Array of 2
	 *  strings, for UTC and local time.
	 * @cfg {Date} [defaultDate] Default date, for filling unspecified components.
	 *  Defaults to the current date and time (with 0 milliseconds).
	 */
	mw.widgets.datetime.DateTimeFormatter = function MwWidgetsDatetimeDateTimeFormatter( config ) {
		this.constructor.static.setupDefaults();

		config = $.extend( {
			format: '@default',
			local: false,
			fullZones: this.constructor.static.fullZones,
			shortZones: this.constructor.static.shortZones
		}, config );

		// Mixin constructors
		OO.EventEmitter.call( this );

		// Properties
		if ( this.constructor.static.formats[ config.format ] ) {
			this.format = this.constructor.static.formats[ config.format ];
		} else {
			this.format = config.format;
		}
		this.local = !!config.local;
		this.fullZones = config.fullZones;
		this.shortZones = config.shortZones;
		if ( config.defaultDate instanceof Date ) {
			this.defaultDate = config.defaultDate;
		} else {
			this.defaultDate = new Date();
			if ( this.local ) {
				this.defaultDate.setMilliseconds( 0 );
			} else {
				this.defaultDate.setUTCMilliseconds( 0 );
			}
		}
	};

	/* Setup */

	OO.initClass( mw.widgets.datetime.DateTimeFormatter );
	OO.mixinClass( mw.widgets.datetime.DateTimeFormatter, OO.EventEmitter );

	/* Static */

	/**
	 * Default format specifications. See the {@link #format format} parameter.
	 *
	 * @static
	 * @inheritable
	 * @property {Object}
	 */
	mw.widgets.datetime.DateTimeFormatter.static.formats = {};

	/**
	 * Default time zone indicators
	 *
	 * @static
	 * @inheritable
	 * @property {string[]}
	 */
	mw.widgets.datetime.DateTimeFormatter.static.fullZones = null;

	/**
	 * Default abbreviated time zone indicators
	 *
	 * @static
	 * @inheritable
	 * @property {string[]}
	 */
	mw.widgets.datetime.DateTimeFormatter.static.shortZones = null;

	mw.widgets.datetime.DateTimeFormatter.static.setupDefaults = function () {
		if ( !this.fullZones ) {
			this.fullZones = [
				mw.msg( 'timezone-utc' ),
				mw.msg( 'timezone-local' )
			];
		}
		if ( !this.shortZones ) {
			this.shortZones = [
				'Z',
				this.fullZones[ 1 ].substr( 0, 1 ).toUpperCase()
			];
			if ( this.shortZones[ 1 ] === 'Z' ) {
				this.shortZones[ 1 ] = 'L';
			}
		}
	};

	/* Events */

	/**
	 * A `local` event is emitted when the 'local' flag is changed.
	 *
	 * @event local
	 */

	/* Methods */

	/**
	 * Whether dates are in local time or UTC
	 *
	 * @return {boolean} True if local time
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getLocal = function () {
		return this.local;
	};

	// eslint-disable-next-line valid-jsdoc
	/**
	 * Toggle whether dates are in local time or UTC
	 *
	 * @param {boolean} [flag] Set the flag instead of toggling it
	 * @fires local
	 * @chainable
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.toggleLocal = function ( flag ) {
		if ( flag === undefined ) {
			flag = !this.local;
		} else {
			flag = !!flag;
		}
		if ( this.local !== flag ) {
			this.local = flag;
			this.emit( 'local', this.local );
		}
		return this;
	};

	/**
	 * Get the default date
	 *
	 * @return {Date}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getDefaultDate = function () {
		return new Date( this.defaultDate.getTime() );
	};

	/**
	 * Fetch the field specification array for this object.
	 *
	 * See {@link #parseFieldSpec parseFieldSpec} for details on the return value structure.
	 *
	 * @return {Array}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getFieldSpec = function () {
		return this.parseFieldSpec( this.format );
	};

	/**
	 * Parse a format string into a field specification
	 *
	 * The input is a string containing tags formatted as ${tag|param|param...}
	 * (for editable fields) and $!{tag|param|param...} (for non-editable fields).
	 * Most tags are defined by {@link #getFieldForTag getFieldForTag}, but a few
	 * are defined here:
	 * - ${intercalary|X|text}: Text that is only displayed when the 'intercalary'
	 *   component is X.
	 * - ${not-intercalary|X|text}: Text that is displayed unless the 'intercalary'
	 *   component is X.
	 *
	 * Elements of the returned array are strings or objects. Strings are meant to
	 * be displayed as-is. Objects are as returned by {@link #getFieldForTag getFieldForTag}.
	 *
	 * @protected
	 * @param {string} format
	 * @return {Array}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.parseFieldSpec = function ( format ) {
		var m, last, tag, params, spec,
			ret = [],
			re = /(.*?)(\$(!?)\{([^}]+)\})/g;

		last = 0;
		while ( ( m = re.exec( format ) ) !== null ) {
			last = re.lastIndex;

			if ( m[ 1 ] !== '' ) {
				ret.push( m[ 1 ] );
			}

			params = m[ 4 ].split( '|' );
			tag = params.shift();
			spec = this.getFieldForTag( tag, params );
			if ( spec ) {
				if ( m[ 3 ] === '!' ) {
					spec.editable = false;
				}
				ret.push( spec );
			} else {
				ret.push( m[ 2 ] );
			}
		}
		if ( last < format.length ) {
			ret.push( format.substr( last ) );
		}

		return ret;
	};

	/**
	 * Turn a tag into a field specification object
	 *
	 * Fields implemented here are:
	 * - ${intercalary|X|text}: Text that is only displayed when the 'intercalary'
	 *   component is X.
	 * - ${not-intercalary|X|text}: Text that is displayed unless the 'intercalary'
	 *   component is X.
	 * - ${zone|#}: Timezone offset, "+0000" format.
	 * - ${zone|:}: Timezone offset, "+00:00" format.
	 * - ${zone|short}: Timezone from 'shortZones' configuration setting.
	 * - ${zone|full}: Timezone from 'fullZones' configuration setting.
	 *
	 * @protected
	 * @abstract
	 * @param {string} tag
	 * @param {string[]} params
	 * @return {Object|null} Field specification object, or null if the tag+params are unrecognized.
	 * @return {string|null} return.component Date component corresponding to this field, if any.
	 * @return {boolean} return.editable Whether this field is editable.
	 * @return {string} return.type What kind of field this is:
	 *  - 'static': The field is a static string; component will be null.
	 *  - 'number': The field is generally numeric.
	 *  - 'string': The field is generally textual.
	 *  - 'boolean': The field is a boolean.
	 *  - 'toggleLocal': The field represents {@link #getLocal this.getLocal()}.
	 *    Editing should directly call {@link #toggleLocal this.toggleLocal()}.
	 * @return {boolean} return.calendarComponent Whether this field is part of a calendar, e.g.
	 *  part of the date instead of the time.
	 * @return {number} return.size Maximum number of characters in the field (when
	 *  the 'intercalary' component is falsey). If 0, the field should be hidden entirely.
	 * @return {Object.<string,number>} return.intercalarySize Map from
	 *  'intercalary' component values to overridden sizes.
	 * @return {string} return.value For type='static', the string to display.
	 * @return {function(Mixed): string} return.formatValue A function to format a
	 *  component value as a display string.
	 * @return {function(string): Mixed} return.parseValue A function to parse a
	 *  display string into a component value. If parsing fails, returns undefined.
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getFieldForTag = function ( tag, params ) {
		var c, spec = null;

		switch ( tag ) {
			case 'intercalary':
			case 'not-intercalary':
				if ( params.length < 2 || !params[ 0 ] ) {
					return null;
				}
				spec = {
					component: null,
					calendarComponent: false,
					editable: false,
					type: 'static',
					value: params.slice( 1 ).join( '|' ),
					size: 0,
					intercalarySize: {}
				};
				if ( tag === 'intercalary' ) {
					spec.intercalarySize[ params[ 0 ] ] = spec.value.length;
				} else {
					spec.size = spec.value.length;
					spec.intercalarySize[ params[ 0 ] ] = 0;
				}
				return spec;

			case 'zone':
				switch ( params[ 0 ] ) {
					case '#':
					case ':':
						c = params[ 0 ] === '#' ? '' : ':';
						return {
							component: 'zone',
							calendarComponent: false,
							editable: true,
							type: 'toggleLocal',
							size: 5 + c.length,
							formatValue: function ( v ) {
								var o, r;
								if ( v ) {
									o = new Date().getTimezoneOffset();
									r = String( Math.abs( o ) % 60 );
									while ( r.length < 2 ) {
										r = '0' + r;
									}
									r = String( Math.floor( Math.abs( o ) / 60 ) ) + c + r;
									while ( r.length < 4 + c.length ) {
										r = '0' + r;
									}
									return ( o <= 0 ? '+' : '−' ) + r;
								} else {
									return '+00' + c + '00';
								}
							},
							parseValue: function ( v ) {
								var m;
								v = String( v ).trim();
								if ( ( m = /^([+-−])([0-9]{1,2}):?([0-9]{2})$/.test( v ) ) ) {
									return ( m[ 2 ] * 60 + m[ 3 ] ) * ( m[ 1 ] === '+' ? -1 : 1 );
								} else {
									return undefined;
								}
							}
						};

					case 'short':
					case 'full':
						spec = {
							component: 'zone',
							calendarComponent: false,
							editable: true,
							type: 'toggleLocal',
							values: params[ 0 ] === 'short' ? this.shortZones : this.fullZones,
							formatValue: this.formatSpecValue,
							parseValue: this.parseSpecValue
						};
						spec.size = Math.max.apply(
							null, $.map( spec.values, function ( v ) { return v.length; } )
						);
						return spec;
				}
				return null;

			default:
				return null;
		}
	};

	/**
	 * Format a value for a field specification
	 *
	 * 'this' must be the field specification object. The intention is that you
	 * could just assign this function as the 'formatValue' for each field spec.
	 *
	 * Besides the publicly-documented fields, uses the following:
	 * - values: Enumerated values for the field
	 * - zeropad: Whether to pad the number with zeros.
	 *
	 * @protected
	 * @param {Mixed} v
	 * @return {string}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.formatSpecValue = function ( v ) {
		if ( v === undefined || v === null ) {
			return '';
		}

		if ( typeof v === 'boolean' || this.type === 'toggleLocal' ) {
			v = v ? 1 : 0;
		}

		if ( this.values ) {
			return this.values[ v ];
		}

		v = String( v );
		if ( this.zeropad ) {
			while ( v.length < this.size ) {
				v = '0' + v;
			}
		}
		return v;
	};

	/**
	 * Parse a value for a field specification
	 *
	 * 'this' must be the field specification object. The intention is that you
	 * could just assign this function as the 'parseValue' for each field spec.
	 *
	 * Besides the publicly-documented fields, uses the following:
	 * - values: Enumerated values for the field
	 *
	 * @protected
	 * @param {string} v
	 * @return {number|string|null}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.parseSpecValue = function ( v ) {
		var k, re;

		if ( v === '' ) {
			return null;
		}

		if ( !this.values ) {
			v = +v;
			if ( this.type === 'boolean' || this.type === 'toggleLocal' ) {
				return isNaN( v ) ? undefined : !!v;
			} else {
				return isNaN( v ) ? undefined : v;
			}
		}

		if ( v.normalize ) {
			v = v.normalize();
		}
		re = new RegExp( '^\\s*' + v.replace( /([\\{}()|.?*+\-^$\[\]])/g, '\\$1' ), 'i' ); // eslint-disable-line no-useless-escape
		for ( k in this.values ) {
			k = +k;
			if ( !isNaN( k ) && re.test( this.values[ k ] ) ) {
				if ( this.type === 'boolean' || this.type === 'toggleLocal' ) {
					return !!k;
				} else {
					return k;
				}
			}
		}
		return undefined;
	};

	/**
	 * Get components from a Date object
	 *
	 * Most specific components are defined by the subclass. "Global" components
	 * are:
	 * - intercalary: {string} Non-falsey values are used to indicate intercalary days.
	 * - zone: {number} Timezone offset in minutes.
	 *
	 * @abstract
	 * @param {Date|null} date
	 * @return {Object} Components
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getComponentsFromDate = function ( date ) {
		// Should be overridden by subclass
		return {
			zone: this.local ? date.getTimezoneOffset() : 0
		};
	};

	/**
	 * Get a Date object from components
	 *
	 * @param {Object} components Date components
	 * @return {Date}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getDateFromComponents = function ( /* components */ ) {
		// Should be overridden by subclass
		return new Date();
	};

	/**
	 * Adjust a date
	 *
	 * @param {Date|null} date To be adjusted
	 * @param {string} component To adjust
	 * @param {number} delta Adjustment amount
	 * @param {string} mode Adjustment mode:
	 *  - 'overflow': "Jan 32" => "Feb 1", "Jan 33" => "Feb 2", "Feb 0" => "Jan 31", etc.
	 *  - 'wrap': "Jan 32" => "Jan 1", "Jan 33" => "Jan 2", "Jan 0" => "Jan 31", etc.
	 *  - 'clip': "Jan 32" => "Jan 31", "Feb 32" => "Feb 28" (or 29), "Feb 0" => "Feb 1", etc.
	 * @return {Date} Adjusted date
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.adjustComponent = function ( date /* , component, delta, mode */ ) {
		// Should be overridden by subclass
		return date;
	};

	/**
	 * Get the column headings (weekday abbreviations) for a calendar grid
	 *
	 * Null-valued columns are hidden if getCalendarData() returns no "day" object
	 * for all days in that column.
	 *
	 * @abstract
	 * @return {Array} string or null
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getCalendarHeadings = function () {
		// Should be overridden by subclass
		return [];
	};

	/**
	 * Test whether two dates are in the same calendar grid
	 *
	 * @abstract
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {boolean}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.sameCalendarGrid = function ( date1, date2 ) {
		// Should be overridden by subclass
		return date1.getTime() === date2.getTime();
	};

	/**
	 * Test whether the date parts of two Dates are equal
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {boolean}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.datePartIsEqual = function ( date1, date2 ) {
		if ( this.local ) {
			return (
				date1.getFullYear() === date2.getFullYear() &&
				date1.getMonth() === date2.getMonth() &&
				date1.getDate() === date2.getDate()
			);
		} else {
			return (
				date1.getUTCFullYear() === date2.getUTCFullYear() &&
				date1.getUTCMonth() === date2.getUTCMonth() &&
				date1.getUTCDate() === date2.getUTCDate()
			);
		}
	};

	/**
	 * Test whether the time parts of two Dates are equal
	 *
	 * @param {Date} date1
	 * @param {Date} date2
	 * @return {boolean}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.timePartIsEqual = function ( date1, date2 ) {
		if ( this.local ) {
			return (
				date1.getHours() === date2.getHours() &&
				date1.getMinutes() === date2.getMinutes() &&
				date1.getSeconds() === date2.getSeconds() &&
				date1.getMilliseconds() === date2.getMilliseconds()
			);
		} else {
			return (
				date1.getUTCHours() === date2.getUTCHours() &&
				date1.getUTCMinutes() === date2.getUTCMinutes() &&
				date1.getUTCSeconds() === date2.getUTCSeconds() &&
				date1.getUTCMilliseconds() === date2.getUTCMilliseconds()
			);
		}
	};

	/**
	 * Test whether toggleLocal() changes the date part
	 *
	 * @param {Date} date
	 * @return {boolean}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.localChangesDatePart = function ( date ) {
		return (
			date.getUTCFullYear() !== date.getFullYear() ||
			date.getUTCMonth() !== date.getMonth() ||
			date.getUTCDate() !== date.getDate()
		);
	};

	/**
	 * Create a new Date by merging the date part from one with the time part from
	 * another.
	 *
	 * @param {Date} datepart
	 * @param {Date} timepart
	 * @return {Date}
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.mergeDateAndTime = function ( datepart, timepart ) {
		var ret = new Date( datepart.getTime() );

		if ( this.local ) {
			ret.setHours(
				timepart.getHours(),
				timepart.getMinutes(),
				timepart.getSeconds(),
				timepart.getMilliseconds()
			);
		} else {
			ret.setUTCHours(
				timepart.getUTCHours(),
				timepart.getUTCMinutes(),
				timepart.getUTCSeconds(),
				timepart.getUTCMilliseconds()
			);
		}

		return ret;
	};

	/**
	 * Get data for a calendar grid
	 *
	 * A "day" object is:
	 * - display: {string} Display text for the day.
	 * - date: {Date} Date to use when the day is selected.
	 * - extra: {string|null} 'prev' or 'next' on days used to fill out the weeks
	 *   at the start and end of the month.
	 *
	 * In any one result object, 'extra' + 'display' will always be unique.
	 *
	 * @abstract
	 * @param {Date|null} current Current date
	 * @return {Object} Data
	 * @return {string} return.header String to display as the calendar header
	 * @return {string} return.monthComponent Component to adjust by ±1 to change months.
	 * @return {string} return.dayComponent Component to adjust by ±1 to change days.
	 * @return {string} [return.weekComponent] Component to adjust by ±1 to change
	 *   weeks. If omitted, the dayComponent should be adjusted by ±the number of
	 *   non-nullable columns returned by this.getCalendarHeadings() to change weeks.
	 * @return {Array} return.rows Array of arrays of "day" objects or null/undefined.
	 */
	mw.widgets.datetime.DateTimeFormatter.prototype.getCalendarData = function ( /* components */ ) {
		// Should be overridden by subclass
		return {
			header: '',
			monthComponent: 'month',
			dayComponent: 'day',
			rows: []
		};
	};

}( jQuery, mediaWiki ) );
