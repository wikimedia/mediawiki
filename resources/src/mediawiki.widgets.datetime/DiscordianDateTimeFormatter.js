/* eslint-disable no-restricted-properties */
( function () {

	/**
	 * Provides various methods needed for formatting dates and times. This
	 * implementation implments the [Discordian calendar][1], mainly for testing with
	 * something very different from the usual Gregorian calendar.
	 *
	 * Being intended mainly for testing, niceties like i18n and better
	 * configurability have been omitted.
	 *
	 * [1]: https://en.wikipedia.org/wiki/Discordian_calendar
	 *
	 * @class
	 * @extends mw.widgets.datetime.DateTimeFormatter
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter = function MwWidgetsDatetimeDiscordianDateTimeFormatter( config ) {
		config = $.extend( {}, config );

		// Parent constructor
		mw.widgets.datetime.DiscordianDateTimeFormatter[ 'super' ].call( this, config );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.datetime.DiscordianDateTimeFormatter, mw.widgets.datetime.DateTimeFormatter );

	/* Static */

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.static.formats = {
		'@time': '${hour|0}:${minute|0}:${second|0}',
		'@date': '$!{dow|full}${not-intercalary|1|, }${season|full}${not-intercalary|1| }${day|#}, ${year|#}',
		'@datetime': '$!{dow|full}${not-intercalary|1|, }${season|full}${not-intercalary|1| }${day|#}, ${year|#} ${hour|0}:${minute|0}:${second|0} $!{zone|short}',
		'@default': '$!{dow|full}${not-intercalary|1|, }${season|full}${not-intercalary|1| }${day|#}, ${year|#} ${hour|0}:${minute|0}:${second|0} $!{zone|short}'
	};

	/* Methods */

	/**
	 * @inheritdoc
	 *
	 * Additional fields implemented here are:
	 * - ${year|#}: Year as a number
	 * - ${season|#}: Season as a number
	 * - ${season|full}: Season as a string
	 * - ${day|#}: Day of the month as a number
	 * - ${day|0}: Day of the month as a number with leading 0
	 * - ${dow|full}: Day of the week as a string
	 * - ${hour|#}: Hour as a number
	 * - ${hour|0}: Hour as a number with leading 0
	 * - ${minute|#}: Minute as a number
	 * - ${minute|0}: Minute as a number with leading 0
	 * - ${second|#}: Second as a number
	 * - ${second|0}: Second as a number with leading 0
	 * - ${millisecond|#}: Millisecond as a number
	 * - ${millisecond|0}: Millisecond as a number, zero-padded to 3 digits
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.getFieldForTag = function ( tag, params ) {
		var spec = null;

		switch ( tag + '|' + params[ 0 ] ) {
			case 'year|#':
				spec = {
					component: 'Year',
					calendarComponent: true,
					type: 'number',
					size: 4,
					zeropad: false
				};
				break;

			case 'season|#':
				spec = {
					component: 'Season',
					calendarComponent: true,
					type: 'number',
					size: 1,
					intercalarySize: { 1: 0 },
					zeropad: false
				};
				break;

			case 'season|full':
				spec = {
					component: 'Season',
					calendarComponent: true,
					type: 'string',
					intercalarySize: { 1: 0 },
					values: {
						1: 'Chaos',
						2: 'Discord',
						3: 'Confusion',
						4: 'Bureaucracy',
						5: 'The Aftermath'
					}
				};
				break;

			case 'dow|full':
				spec = {
					component: 'DOW',
					calendarComponent: true,
					editable: false,
					type: 'string',
					intercalarySize: { 1: 0 },
					values: {
						'-1': 'N/A',
						0: 'Sweetmorn',
						1: 'Boomtime',
						2: 'Pungenday',
						3: 'Prickle-Prickle',
						4: 'Setting Orange'
					}
				};
				break;

			case 'day|#':
			case 'day|0':
				spec = {
					component: 'Day',
					calendarComponent: true,
					type: 'string',
					size: 2,
					intercalarySize: { 1: 13 },
					zeropad: params[ 0 ] === '0',
					formatValue: function ( v ) {
						if ( v === 'tib' ) {
							return 'St. Tib\'s Day';
						}
						return mw.widgets.datetime.DateTimeFormatter.prototype.formatSpecValue.call( this, v );
					},
					parseValue: function ( v ) {
						if ( /^\s*(st.?\s*)?tib('?s)?(\s*day)?\s*$/i.test( v ) ) {
							return 'tib';
						}
						return mw.widgets.datetime.DateTimeFormatter.prototype.parseSpecValue.call( this, v );
					}
				};
				break;

			case 'hour|#':
			case 'hour|0':
			case 'minute|#':
			case 'minute|0':
			case 'second|#':
			case 'second|0':
				spec = {
					component: tag.charAt( 0 ).toUpperCase() + tag.slice( 1 ),
					calendarComponent: false,
					type: 'number',
					size: 2,
					zeropad: params[ 0 ] === '0'
				};
				break;

			case 'millisecond|#':
			case 'millisecond|0':
				spec = {
					component: 'Millisecond',
					calendarComponent: false,
					type: 'number',
					size: 3,
					zeropad: params[ 0 ] === '0'
				};
				break;

			default:
				return mw.widgets.datetime.DiscordianDateTimeFormatter[ 'super' ].prototype.getFieldForTag.call( this, tag, params );
		}

		if ( spec ) {
			if ( spec.editable === undefined ) {
				spec.editable = true;
			}
			if ( spec.component !== 'Day' ) {
				spec.formatValue = this.formatSpecValue;
				spec.parseValue = this.parseSpecValue;
			}
			if ( spec.values ) {
				spec.size = Math.max.apply(
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
	 * - Year {number}
	 * - Season {number} 1-5
	 * - Day {number|string} 1-73 or 'tib'
	 * - DOW {number} 0-4, or -1 on St. Tib's Day
	 * - Hour {number} 0-23
	 * - Minute {number} 0-59
	 * - Second {number} 0-59
	 * - Millisecond {number} 0-999
	 * - intercalary {string} '1' on St. Tib's Day
	 *
	 * @param {Date|null} date
	 * @return {Object} Components
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.getComponentsFromDate = function ( date ) {
		var ret, day, month,
			monthDays = [ 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 ];

		if ( !( date instanceof Date ) ) {
			date = this.defaultDate;
		}

		if ( this.local ) {
			day = date.getDate();
			month = date.getMonth();
			ret = {
				Year: date.getFullYear() + 1166,
				Hour: date.getHours(),
				Minute: date.getMinutes(),
				Second: date.getSeconds(),
				Millisecond: date.getMilliseconds(),
				zone: date.getTimezoneOffset()
			};
		} else {
			day = date.getUTCDate();
			month = date.getUTCMonth();
			ret = {
				Year: date.getUTCFullYear() + 1166,
				Hour: date.getUTCHours(),
				Minute: date.getUTCMinutes(),
				Second: date.getUTCSeconds(),
				Millisecond: date.getUTCMilliseconds(),
				zone: 0
			};
		}

		if ( month === 1 && day === 29 ) {
			ret.Season = 1;
			ret.Day = 'tib';
			ret.DOW = -1;
			ret.intercalary = '1';
		} else {
			day = monthDays[ month ] + day - 1;
			ret.Season = Math.floor( day / 73 ) + 1;
			ret.Day = ( day % 73 ) + 1;
			ret.DOW = day % 5;
			ret.intercalary = '';
		}

		return ret;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.adjustComponent = function ( date, component, delta, mode ) {
		return this.getDateFromComponents(
			this.adjustComponentInternal(
				this.getComponentsFromDate( date ), component, delta, mode
			)
		);
	};

	/**
	 * Adjust the components directly
	 *
	 * @private
	 * @param {Object} components Modified in place
	 * @param {string} component
	 * @param {number} delta
	 * @param {string} mode
	 * @return {Object} components
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.adjustComponentInternal = function ( components, component, delta, mode ) {
		var i, min, max, range, next, preTib, postTib, wasTib;

		if ( delta === 0 ) {
			return components;
		}

		switch ( component ) {
			case 'Year':
				min = 1166;
				max = 11165;
				next = null;
				break;
			case 'Season':
				min = 1;
				max = 5;
				next = 'Year';
				break;
			case 'Week':
				if ( components.Day === 'tib' ) {
					components.Day = 59; // Could choose either one...
					components.Season = 1;
				}
				min = 1;
				max = 73;
				next = 'Season';
				break;
			case 'Day':
				min = 1;
				max = 73;
				next = 'Season';
				break;
			case 'Hour':
				min = 0;
				max = 23;
				next = 'Day';
				break;
			case 'Minute':
				min = 0;
				max = 59;
				next = 'Hour';
				break;
			case 'Second':
				min = 0;
				max = 59;
				next = 'Minute';
				break;
			case 'Millisecond':
				min = 0;
				max = 999;
				next = 'Second';
				break;
			default:
				return components;
		}

		switch ( mode ) {
			case 'overflow':
			case 'clip':
			case 'wrap':
		}

		if ( component === 'Day' ) {
			i = Math.abs( delta );
			delta = delta < 0 ? -1 : 1;
			preTib = delta > 0 ? 59 : 60;
			postTib = delta > 0 ? 60 : 59;
			while ( i-- > 0 ) {
				if ( components.Day === preTib && components.Season === 1 && this.isLeapYear( components.Year ) ) {
					components.Day = 'tib';
				} else if ( components.Day === 'tib' ) {
					components.Day = postTib;
					components.Season = 1;
				} else {
					components.Day += delta;
					if ( components.Day < min ) {
						switch ( mode ) {
							case 'overflow':
								components.Day = max;
								this.adjustComponentInternal( components, 'Season', -1, mode );
								break;
							case 'wrap':
								components.Day = max;
								break;
							case 'clip':
								components.Day = min;
								i = 0;
								break;
						}
					}
					if ( components.Day > max ) {
						switch ( mode ) {
							case 'overflow':
								components.Day = min;
								this.adjustComponentInternal( components, 'Season', 1, mode );
								break;
							case 'wrap':
								components.Day = min;
								break;
							case 'clip':
								components.Day = max;
								i = 0;
								break;
						}
					}
				}
			}
		} else {
			if ( component === 'Week' ) {
				component = 'Day';
				delta *= 5;
			}
			if ( components.Day === 'tib' ) {
				// For sanity
				components.Season = 1;
			}
			switch ( mode ) {
				case 'overflow':
					if ( components.Day === 'tib' && ( component === 'Season' || component === 'Year' ) ) {
						components.Day = 59; // Could choose either one...
						wasTib = true;
					} else {
						wasTib = false;
					}
					i = Math.abs( delta );
					delta = delta < 0 ? -1 : 1;
					while ( i-- > 0 ) {
						components[ component ] += delta;
						if ( components[ component ] < min ) {
							components[ component ] = max;
							components = this.adjustComponentInternal( components, next, -1, mode );
						}
						if ( components[ component ] > max ) {
							components[ component ] = min;
							components = this.adjustComponentInternal( components, next, 1, mode );
						}
					}
					if ( wasTib && components.Season === 1 && this.isLeapYear( components.Year ) ) {
						components.Day = 'tib';
					}
					break;
				case 'wrap':
					range = max - min + 1;
					components[ component ] += delta;
					while ( components[ component ] < min ) {
						components[ component ] += range;
					}
					while ( components[ component ] > max ) {
						components[ component ] -= range;
					}
					break;
				case 'clip':
					components[ component ] += delta;
					if ( components[ component ] < min ) {
						components[ component ] = min;
					}
					if ( components[ component ] > max ) {
						components[ component ] = max;
					}
					break;
			}
			if ( components.Day === 'tib' &&
				( components.Season !== 1 || !this.isLeapYear( components.Year ) )
			) {
				components.Day = 59; // Could choose either one...
			}
		}

		return components;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.getDateFromComponents = function ( components ) {
		var month, day, days,
			date = new Date(),
			monthDays = [ 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365 ];

		components = $.extend( {}, this.getComponentsFromDate( null ), components );
		if ( components.Day === 'tib' ) {
			month = 1;
			day = 29;
		} else {
			days = components.Season * 73 + components.Day - 74;
			month = 0;
			while ( days >= monthDays[ month + 1 ] ) {
				month++;
			}
			day = days - monthDays[ month ] + 1;
		}

		if ( components.zone ) {
			// Can't just use the constructor because that's stupid about ancient years.
			date.setFullYear( components.Year - 1166, month, day );
			date.setHours( components.Hour, components.Minute, components.Second, components.Millisecond );
		} else {
			// Date.UTC() is stupid about ancient years too.
			date.setUTCFullYear( components.Year - 1166, month, day );
			date.setUTCHours( components.Hour, components.Minute, components.Second, components.Millisecond );
		}

		return date;
	};

	/**
	 * Get whether the year is a leap year
	 *
	 * @private
	 * @param {number} year
	 * @return {boolean}
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.isLeapYear = function ( year ) {
		year -= 1166;
		if ( year % 4 ) {
			return false;
		} else if ( year % 100 ) {
			return true;
		}
		return ( year % 400 ) === 0;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.getCalendarHeadings = function () {
		return [ 'SM', 'BT', 'PD', 'PP', null, 'SO' ];
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.sameCalendarGrid = function ( date1, date2 ) {
		var components1 = this.getComponentsFromDate( date1 ),
			components2 = this.getComponentsFromDate( date2 );

		return components1.Year === components2.Year && components1.Season === components2.Season;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DiscordianDateTimeFormatter.prototype.getCalendarData = function ( date ) {
		var dt, components, season, i, row,
			ret = {
				dayComponent: 'Day',
				weekComponent: 'Week',
				monthComponent: 'Season'
			},
			seasons = [ 'Chaos', 'Discord', 'Confusion', 'Bureaucracy', 'The Aftermath' ],
			seasonStart = [ 0, -3, -1, -4, -2 ];

		if ( !( date instanceof Date ) ) {
			date = this.defaultDate;
		}

		components = this.getComponentsFromDate( date );
		components.Day = 1;
		season = components.Season;

		ret.header = seasons[ season - 1 ] + ' ' + components.Year;

		if ( seasonStart[ season - 1 ] ) {
			this.adjustComponentInternal( components, 'Day', seasonStart[ season - 1 ], 'overflow' );
		}

		ret.rows = [];
		do {
			row = [];
			for ( i = 0; i < 6; i++ ) {
				dt = this.getDateFromComponents( components );
				row[ i ] = {
					display: components.Day === 'tib' ? 'Tib' : String( components.Day ),
					date: dt,
					extra: components.Season < season ? 'prev' : components.Season > season ? 'next' : null
				};

				this.adjustComponentInternal( components, 'Day', 1, 'overflow' );
				if ( components.Day !== 'tib' && i === 3 ) {
					row[ ++i ] = null;
				}
			}

			ret.rows.push( row );
		} while ( components.Season === season );

		return ret;
	};

}() );
