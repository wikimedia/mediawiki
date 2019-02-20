( function () {

	/**
	 * Provides various methods needed for formatting dates and times. This
	 * implementation implements the proleptic Gregorian calendar over years
	 * 0000–9999.
	 *
	 * @class
	 * @extends mw.widgets.datetime.DateTimeFormatter
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {Object} [fullMonthNames] Mapping 1–12 to full month names.
	 * @cfg {Object} [shortMonthNames] Mapping 1–12 to abbreviated month names.
	 *  If {@link #fullMonthNames fullMonthNames} is given and this is not,
	 *  defaults to the first three characters from that setting.
	 * @cfg {Object} [fullDayNames] Mapping 0–6 to full day of week names. 0 is Sunday, 6 is Saturday.
	 * @cfg {Object} [shortDayNames] Mapping 0–6 to abbreviated day of week names. 0 is Sunday, 6 is Saturday.
	 *  If {@link #fullDayNames fullDayNames} is given and this is not, defaults to
	 *  the first three characters from that setting.
	 * @cfg {string[]} [dayLetters] Weekday column headers for a calendar. Array of 7 strings.
	 *  If {@link #fullDayNames fullDayNames} or {@link #shortDayNames shortDayNames}
	 *  are given and this is not, defaults to the first character from
	 *  shortDayNames.
	 * @cfg {string[]} [hour12Periods] AM and PM texts. Array of 2 strings, AM and PM.
	 * @cfg {number} [weekStartsOn=0] What day the week starts on: 0 is Sunday, 1 is Monday, 6 is Saturday.
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter = function MwWidgetsDatetimeProlepticGregorianDateTimeFormatter( config ) {
		this.constructor.static.setupDefaults();

		config = $.extend( {
			weekStartsOn: 0,
			hour12Periods: this.constructor.static.hour12Periods
		}, config );

		if ( config.fullMonthNames && !config.shortMonthNames ) {
			config.shortMonthNames = {};
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( config.fullMonthNames, function ( k, v ) {
				config.shortMonthNames[ k ] = v.substr( 0, 3 );
			} );
		}
		if ( config.shortDayNames && !config.dayLetters ) {
			config.dayLetters = [];
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( config.shortDayNames, function ( k, v ) {
				config.dayLetters[ k ] = v.substr( 0, 1 );
			} );
		}
		if ( config.fullDayNames && !config.dayLetters ) {
			config.dayLetters = [];
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( config.fullDayNames, function ( k, v ) {
				config.dayLetters[ k ] = v.substr( 0, 1 );
			} );
		}
		if ( config.fullDayNames && !config.shortDayNames ) {
			config.shortDayNames = {};
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( config.fullDayNames, function ( k, v ) {
				config.shortDayNames[ k ] = v.substr( 0, 3 );
			} );
		}
		config = $.extend( {
			fullMonthNames: this.constructor.static.fullMonthNames,
			shortMonthNames: this.constructor.static.shortMonthNames,
			fullDayNames: this.constructor.static.fullDayNames,
			shortDayNames: this.constructor.static.shortDayNames,
			dayLetters: this.constructor.static.dayLetters
		}, config );

		// Parent constructor
		mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.super.call( this, config );

		// Properties
		this.weekStartsOn = config.weekStartsOn % 7;
		this.fullMonthNames = config.fullMonthNames;
		this.shortMonthNames = config.shortMonthNames;
		this.fullDayNames = config.fullDayNames;
		this.shortDayNames = config.shortDayNames;
		this.dayLetters = config.dayLetters;
		this.hour12Periods = config.hour12Periods;
	};

	/* Setup */

	OO.inheritClass( mw.widgets.datetime.ProlepticGregorianDateTimeFormatter, mw.widgets.datetime.DateTimeFormatter );

	/* Static */

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.formats = {
		'@time': '${hour|0}:${minute|0}:${second|0}',
		'@date': '$!{dow|short} ${day|#} ${month|short} ${year|#}',
		'@datetime': '$!{dow|short} ${day|#} ${month|short} ${year|#} ${hour|0}:${minute|0}:${second|0} $!{zone|short}',
		'@default': '$!{dow|short} ${day|#} ${month|short} ${year|#} ${hour|0}:${minute|0}:${second|0} $!{zone|short}'
	};

	/**
	 * Default full month names.
	 *
	 * @static
	 * @inheritable
	 * @property {Object}
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.fullMonthNames = null;

	/**
	 * Default abbreviated month names.
	 *
	 * @static
	 * @inheritable
	 * @property {Object}
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.shortMonthNames = null;

	/**
	 * Default full day of week names.
	 *
	 * @static
	 * @inheritable
	 * @property {Object}
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.fullDayNames = null;

	/**
	 * Default abbreviated day of week names.
	 *
	 * @static
	 * @inheritable
	 * @property {Object}
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.shortDayNames = null;

	/**
	 * Default day letters.
	 *
	 * @static
	 * @inheritable
	 * @property {string[]}
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.dayLetters = null;

	/**
	 * Default AM/PM indicators
	 *
	 * @static
	 * @inheritable
	 * @property {string[]}
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.hour12Periods = null;

	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.static.setupDefaults = function () {
		mw.widgets.datetime.DateTimeFormatter.static.setupDefaults.call( this );

		if ( this.fullMonthNames && !this.shortMonthNames ) {
			this.shortMonthNames = {};
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.fullMonthNames, function ( k, v ) {
				this.shortMonthNames[ k ] = v.substr( 0, 3 );
			}.bind( this ) );
		}
		if ( this.shortDayNames && !this.dayLetters ) {
			this.dayLetters = [];
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.shortDayNames, function ( k, v ) {
				this.dayLetters[ k ] = v.substr( 0, 1 );
			}.bind( this ) );
		}
		if ( this.fullDayNames && !this.dayLetters ) {
			this.dayLetters = [];
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.fullDayNames, function ( k, v ) {
				this.dayLetters[ k ] = v.substr( 0, 1 );
			}.bind( this ) );
		}
		if ( this.fullDayNames && !this.shortDayNames ) {
			this.shortDayNames = {};
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.fullDayNames, function ( k, v ) {
				this.shortDayNames[ k ] = v.substr( 0, 3 );
			}.bind( this ) );
		}

		if ( !this.fullMonthNames ) {
			this.fullMonthNames = {
				1: mw.msg( 'january' ),
				2: mw.msg( 'february' ),
				3: mw.msg( 'march' ),
				4: mw.msg( 'april' ),
				5: mw.msg( 'may_long' ),
				6: mw.msg( 'june' ),
				7: mw.msg( 'july' ),
				8: mw.msg( 'august' ),
				9: mw.msg( 'september' ),
				10: mw.msg( 'october' ),
				11: mw.msg( 'november' ),
				12: mw.msg( 'december' )
			};
		}
		if ( !this.shortMonthNames ) {
			this.shortMonthNames = {
				1: mw.msg( 'jan' ),
				2: mw.msg( 'feb' ),
				3: mw.msg( 'mar' ),
				4: mw.msg( 'apr' ),
				5: mw.msg( 'may' ),
				6: mw.msg( 'jun' ),
				7: mw.msg( 'jul' ),
				8: mw.msg( 'aug' ),
				9: mw.msg( 'sep' ),
				10: mw.msg( 'oct' ),
				11: mw.msg( 'nov' ),
				12: mw.msg( 'dec' )
			};
		}

		if ( !this.fullDayNames ) {
			this.fullDayNames = {
				0: mw.msg( 'sunday' ),
				1: mw.msg( 'monday' ),
				2: mw.msg( 'tuesday' ),
				3: mw.msg( 'wednesday' ),
				4: mw.msg( 'thursday' ),
				5: mw.msg( 'friday' ),
				6: mw.msg( 'saturday' )
			};
		}
		if ( !this.shortDayNames ) {
			this.shortDayNames = {
				0: mw.msg( 'sun' ),
				1: mw.msg( 'mon' ),
				2: mw.msg( 'tue' ),
				3: mw.msg( 'wed' ),
				4: mw.msg( 'thu' ),
				5: mw.msg( 'fri' ),
				6: mw.msg( 'sat' )
			};
		}
		if ( !this.dayLetters ) {
			this.dayLetters = [];
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.shortDayNames, function ( k, v ) {
				this.dayLetters[ k ] = v.substr( 0, 1 );
			}.bind( this ) );
		}

		if ( !this.hour12Periods ) {
			this.hour12Periods = [
				mw.msg( 'period-am' ),
				mw.msg( 'period-pm' )
			];
		}
	};

	/* Methods */

	/**
	 * @inheritdoc
	 *
	 * Additional fields implemented here are:
	 * - ${year|#}: Year as a number
	 * - ${year|0}: Year as a number, zero-padded to 4 digits
	 * - ${month|#}: Month as a number
	 * - ${month|0}: Month as a number with leading 0
	 * - ${month|short}: Month from 'shortMonthNames' configuration setting
	 * - ${month|full}: Month from 'fullMonthNames' configuration setting
	 * - ${day|#}: Day of the month as a number
	 * - ${day|0}: Day of the month as a number with leading 0
	 * - ${dow|short}: Day of the week from 'shortDayNames' configuration setting
	 * - ${dow|full}: Day of the week from 'fullDayNames' configuration setting
	 * - ${hour|#}: Hour as a number
	 * - ${hour|0}: Hour as a number with leading 0
	 * - ${hour|12}: Hour in a 12-hour clock as a number
	 * - ${hour|012}: Hour in a 12-hour clock as a number, with leading 0
	 * - ${hour|period}: Value from 'hour12Periods' configuration setting
	 * - ${minute|#}: Minute as a number
	 * - ${minute|0}: Minute as a number with leading 0
	 * - ${second|#}: Second as a number
	 * - ${second|0}: Second as a number with leading 0
	 * - ${millisecond|#}: Millisecond as a number
	 * - ${millisecond|0}: Millisecond as a number, zero-padded to 3 digits
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.getFieldForTag = function ( tag, params ) {
		var spec = null;

		switch ( tag + '|' + params[ 0 ] ) {
			case 'year|#':
			case 'year|0':
				spec = {
					component: 'year',
					calendarComponent: true,
					type: 'number',
					size: 4,
					zeropad: params[ 0 ] === '0'
				};
				break;

			case 'month|short':
			case 'month|full':
				spec = {
					component: 'month',
					calendarComponent: true,
					type: 'string',
					values: params[ 0 ] === 'short' ? this.shortMonthNames : this.fullMonthNames
				};
				break;

			case 'dow|short':
			case 'dow|full':
				spec = {
					component: 'dow',
					calendarComponent: true,
					editable: false,
					type: 'string',
					values: params[ 0 ] === 'short' ? this.shortDayNames : this.fullDayNames
				};
				break;

			case 'month|#':
			case 'month|0':
			case 'day|#':
			case 'day|0':
				spec = {
					component: tag,
					calendarComponent: true,
					type: 'number',
					size: 2,
					zeropad: params[ 0 ] === '0'
				};
				break;

			case 'hour|#':
			case 'hour|0':
			case 'minute|#':
			case 'minute|0':
			case 'second|#':
			case 'second|0':
				spec = {
					component: tag,
					calendarComponent: false,
					type: 'number',
					size: 2,
					zeropad: params[ 0 ] === '0'
				};
				break;

			case 'hour|12':
			case 'hour|012':
				spec = {
					component: 'hour12',
					calendarComponent: false,
					type: 'number',
					size: 2,
					zeropad: params[ 0 ] === '012'
				};
				break;

			case 'hour|period':
				spec = {
					component: 'hour12period',
					calendarComponent: false,
					type: 'boolean',
					values: this.hour12Periods
				};
				break;

			case 'millisecond|#':
			case 'millisecond|0':
				spec = {
					component: 'millisecond',
					calendarComponent: false,
					type: 'number',
					size: 3,
					zeropad: params[ 0 ] === '0'
				};
				break;

			default:
				return mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.super.prototype.getFieldForTag.call( this, tag, params );
		}

		if ( spec ) {
			if ( spec.editable === undefined ) {
				spec.editable = true;
			}
			spec.formatValue = this.formatSpecValue;
			spec.parseValue = this.parseSpecValue;
			if ( spec.values ) {
				spec.size = Math.max.apply(
					// eslint-disable-next-line no-jquery/no-map-util
					null, $.map( spec.values, function ( v ) { return v.length; } )
				);
			}
		}

		return spec;
	};

	/**
	 * Get components from a Date object
	 *
	 * Components are:
	 * - year {number}
	 * - month {number} (1-12)
	 * - day {number} (1-31)
	 * - dow {number} (0-6, 0 is Sunday)
	 * - hour {number} (0-23)
	 * - hour12 {number} (1-12)
	 * - hour12period {boolean}
	 * - minute {number} (0-59)
	 * - second {number} (0-59)
	 * - millisecond {number} (0-999)
	 * - zone {number}
	 *
	 * @param {Date|null} date
	 * @return {Object} Components
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.getComponentsFromDate = function ( date ) {
		var ret;

		if ( !( date instanceof Date ) ) {
			date = this.defaultDate;
		}

		if ( this.local ) {
			ret = {
				year: date.getFullYear(),
				month: date.getMonth() + 1,
				day: date.getDate(),
				dow: date.getDay() % 7,
				hour: date.getHours(),
				minute: date.getMinutes(),
				second: date.getSeconds(),
				millisecond: date.getMilliseconds(),
				zone: date.getTimezoneOffset()
			};
		} else {
			ret = {
				year: date.getUTCFullYear(),
				month: date.getUTCMonth() + 1,
				day: date.getUTCDate(),
				dow: date.getUTCDay() % 7,
				hour: date.getUTCHours(),
				minute: date.getUTCMinutes(),
				second: date.getUTCSeconds(),
				millisecond: date.getUTCMilliseconds(),
				zone: 0
			};
		}

		ret.hour12period = ret.hour >= 12 ? 1 : 0;
		ret.hour12 = ret.hour % 12;
		if ( ret.hour12 === 0 ) {
			ret.hour12 = 12;
		}

		return ret;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.getDateFromComponents = function ( components ) {
		var date = new Date();

		components = $.extend( {}, components );
		if ( components.hour === undefined && components.hour12 !== undefined && components.hour12period !== undefined ) {
			components.hour = ( components.hour12 % 12 ) + ( components.hour12period ? 12 : 0 );
		}
		components = $.extend( {}, this.getComponentsFromDate( null ), components );

		if ( components.zone ) {
			// Can't just use the constructor because that's stupid about ancient years.
			date.setFullYear( components.year, components.month - 1, components.day );
			date.setHours( components.hour, components.minute, components.second, components.millisecond );
		} else {
			// Date.UTC() is stupid about ancient years too.
			date.setUTCFullYear( components.year, components.month - 1, components.day );
			date.setUTCHours( components.hour, components.minute, components.second, components.millisecond );
		}

		return date;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.adjustComponent = function ( date, component, delta, mode ) {
		var min, max, range, components;

		if ( !( date instanceof Date ) ) {
			date = this.defaultDate;
		}
		components = this.getComponentsFromDate( date );

		switch ( component ) {
			case 'year':
				min = 0;
				max = 9999;
				break;
			case 'month':
				min = 1;
				max = 12;
				break;
			case 'day':
				min = 1;
				max = this.getDaysInMonth( components.month, components.year );
				break;
			case 'hour':
				min = 0;
				max = 23;
				break;
			case 'minute':
			case 'second':
				min = 0;
				max = 59;
				break;
			case 'millisecond':
				min = 0;
				max = 999;
				break;
			case 'hour12period':
				component = 'hour';
				min = 0;
				max = 23;
				delta *= 12;
				break;
			case 'hour12':
				component = 'hour';
				min = components.hour12period ? 12 : 0;
				max = components.hour12period ? 23 : 11;
				break;
			default:
				return new Date( date.getTime() );
		}

		components[ component ] += delta;
		range = max - min + 1;
		switch ( mode ) {
			case 'overflow':
				// Date() will mostly handle it automatically. But months need
				// manual handling to prevent e.g. Jan 31 => Mar 3.
				if ( component === 'month' || component === 'year' ) {
					while ( components.month < 1 ) {
						components[ component ] += 12;
						components.year--;
					}
					while ( components.month > 12 ) {
						components[ component ] -= 12;
						components.year++;
					}
				}
				break;
			case 'wrap':
				while ( components[ component ] < min ) {
					components[ component ] += range;
				}
				while ( components[ component ] > max ) {
					components[ component ] -= range;
				}
				break;
			case 'clip':
				if ( components[ component ] < min ) {
					components[ component ] = min;
				}
				if ( components[ component ] < max ) {
					components[ component ] = max;
				}
				break;
		}
		if ( component === 'month' || component === 'year' ) {
			components.day = Math.min( components.day, this.getDaysInMonth( components.month, components.year ) );
		}

		return this.getDateFromComponents( components );
	};

	/**
	 * Get the number of days in a month
	 *
	 * @protected
	 * @param {number} month
	 * @param {number} year
	 * @return {number}
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.getDaysInMonth = function ( month, year ) {
		switch ( month ) {
			case 4:
			case 6:
			case 9:
			case 11:
				return 30;
			case 2:
				if ( year % 4 ) {
					return 28;
				} else if ( year % 100 ) {
					return 29;
				}
				return ( year % 400 ) ? 28 : 29;
			default:
				return 31;
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.getCalendarHeadings = function () {
		var a = this.dayLetters;

		if ( this.weekStartsOn ) {
			return a.slice( this.weekStartsOn ).concat( a.slice( 0, this.weekStartsOn ) );
		} else {
			return a.slice( 0 ); // clone
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.sameCalendarGrid = function ( date1, date2 ) {
		if ( this.local ) {
			return date1.getFullYear() === date2.getFullYear() && date1.getMonth() === date2.getMonth();
		} else {
			return date1.getUTCFullYear() === date2.getUTCFullYear() && date1.getUTCMonth() === date2.getUTCMonth();
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.ProlepticGregorianDateTimeFormatter.prototype.getCalendarData = function ( date ) {
		var dt, t, d, e, i, row,
			getDate = this.local ? 'getDate' : 'getUTCDate',
			setDate = this.local ? 'setDate' : 'setUTCDate',
			ret = {
				dayComponent: 'day',
				monthComponent: 'month'
			};

		if ( !( date instanceof Date ) ) {
			date = this.defaultDate;
		}

		dt = new Date( date.getTime() );
		dt[ setDate ]( 1 );
		t = dt.getTime();

		if ( this.local ) {
			ret.header = this.fullMonthNames[ dt.getMonth() + 1 ] + ' ' + dt.getFullYear();
			d = dt.getDay() % 7;
			e = this.getDaysInMonth( dt.getMonth() + 1, dt.getFullYear() );
		} else {
			ret.header = this.fullMonthNames[ dt.getUTCMonth() + 1 ] + ' ' + dt.getUTCFullYear();
			d = dt.getUTCDay() % 7;
			e = this.getDaysInMonth( dt.getUTCMonth() + 1, dt.getUTCFullYear() );
		}

		if ( this.weekStartsOn ) {
			d = ( d + 7 - this.weekStartsOn ) % 7;
		}
		d = 1 - d;

		ret.rows = [];
		while ( d <= e ) {
			row = [];
			for ( i = 0; i < 7; i++, d++ ) {
				dt = new Date( t );
				dt[ setDate ]( d );
				row[ i ] = {
					display: String( dt[ getDate ]() ),
					date: dt,
					extra: d < 1 ? 'prev' : d > e ? 'next' : null
				};
			}
			ret.rows.push( row );
		}

		return ret;
	};

}() );
