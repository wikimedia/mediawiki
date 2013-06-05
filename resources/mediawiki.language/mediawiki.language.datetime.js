/**
 * Date parser port from /languages/Language.php.
 * See corresponding documentation for PHP methods.
 */

( function( mw, $ ) {

	var gregDays = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ],
		iranianDays = [ 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 ],
		romanTransformTable = [
			[ '', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X' ],
			[ '', 'X', 'XX', 'XXX', 'XL', 'L', 'LX', 'LXX', 'LXXX', 'XC', 'C' ],
			[ '', 'C', 'CC', 'CCC', 'CD', 'D', 'DC', 'DCC', 'DCCC', 'CM', 'M' ],
			[ '', 'M', 'MM', 'MMM', 'MMMM', 'MMMMM', 'MMMMMM', 'MMMMMMM', 'MMMMMMMM', 'MMMMMMMMM', 'MMMMMMMMMM' ]
		],
		hebrewTransformTable = [
			[ '', 'א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט', 'י' ],
			[ '', 'י', 'כ', 'ל', 'מ', 'נ', 'ס', 'ע', 'פ', 'צ', 'ק' ],
			[ '', 'ק', 'ר', 'ש', 'ת', 'תק', 'תר', 'תש', 'תת', 'תתק', 'תתר' ],
			[ '', 'א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט', 'י' ]
		];

	/**
	 * Get a Date object based on provided timestamp
	 *
	 * @param {String} ts
	 * @returns {Date}
	 */
	function getDateObj( ts ) {
		var parsedTime = ts.match( /(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/ );
		return new Date( parsedTime[1], parsedTime[2] - 1, parsedTime[3], parsedTime[4], parsedTime[5], parsedTime[6] );
	}

	/**
	 * Converting Gregorian dates to Hebrew dates.
	 *
	 * JS port of PHP Language::tsToHebrew() which is
	 * a PHP port of the original JS solution by
	 * Abu Mami and Yisrael Hersch (abu-mami@kaluach.net,
	 * http://www.kaluach.net), who permitted
	 * to translate the relevant functions into PHP and release them under
	 * GNU GPL.
	 *
	 * @param {String} ts Timestamp
	 * @returns {Array}
	 */
	function tsToHebrew( ts ) {
		var
			// Parse date
			year = ts.substr( 0, 4 ) - 0,
			month = ts.substr( 4, 2 ) - 0,
			day = ts.substr( 6, 2 ) - 0,

			dayOfYear = day,

			// Calculate Hebrew year
			hebrewYear = year + 3760,

			// temporary variables
			i, start, nextStart, hebrewDayOfYear, diff, yearPattern, isLeap, hebrewDay, hebrewMonth, days;

		// Month number when September = 1, August = 12
		month += 4;
		if ( month > 12 ) {
			// Next year
			month -= 12;
			year++;
			hebrewYear++;
		}

		// Calculate day of year from 1 September
		for ( i = 1; i < month; i++ ) {
			if ( i === 6 ) {
				// February
				dayOfYear += 28;
				// Check if the year is leap
				if ( year % 400 === 0 || ( year % 4 === 0 && year % 100 > 0 ) ) {
					dayOfYear++;
				}
			} else if ( i === 8 || i === 10 || i === 1 || i === 3 ) {
				dayOfYear += 30;
			} else {
				dayOfYear += 31;
			}
		}

		// Calculate the start of the Hebrew year
		start = hebrewYearStart( hebrewYear );

		// Calculate next year's start
		if ( dayOfYear <= start ) {
			// Day is before the start of the year - it is the previous year
			// Next year's start
			nextStart = start;
			// Previous year
			year--;
			hebrewYear--;
			// Add days since previous year's 1 September
			dayOfYear += 365;
			if ( ( year % 400 === 0 ) || ( year % 100 !== 0 && year % 4 === 0 ) ) {
				// Leap year
				dayOfYear++;
			}
			// Start of the new (previous) year
			start = hebrewYearStart( hebrewYear );
		} else {
		// Next year's start
			nextStart = hebrewYearStart( hebrewYear + 1 );
		}

		// Calculate Hebrew day of year
		hebrewDayOfYear = dayOfYear - start;

		// Difference between year's days
		diff = nextStart - start;
		// Add 12 (or 13 for leap years) days to ignore the difference between
		// Hebrew and Gregorian year (353 at least vs. 365/6) - now the
		// difference is only about the year type
		if ( ( year % 400 === 0 ) || ( year % 100 !== 0 && year % 4 === 0 ) ) {
			diff += 13;
		} else {
			diff += 12;
		}

		// Check the year pattern, and is leap year
		// 0 means an incomplete year, 1 means a regular year, 2 means a complete year
		// This is mod 30, to work on both leap years (which add 30 days of Adar I)
		// and non-leap years
		yearPattern = diff % 30;
		// Check if leap year
		isLeap = diff >= 30;

		// Calculate day in the month from number of day in the Hebrew year
		// Don't check Adar - if the day is not in Adar, we will stop before;
		// if it is in Adar, we will use it to check if it is Adar I or Adar II
		hebrewDay = hebrewDayOfYear;
		hebrewMonth = 1;
		days = 0;
		while ( hebrewMonth <= 12 ) {
			// Calculate days in this month
			if ( isLeap && hebrewMonth === 6 ) {
				// Adar in a leap year
				if ( isLeap ) {
					// Leap year - has Adar I, with 30 days, and Adar II, with 29 days
					days = 30;
					if ( hebrewDay <= days ) {
						// Day in Adar I
						hebrewMonth = 13;
					} else {
						// Subtract the days of Adar I
						hebrewDay -= days;
						// Try Adar II
						days = 29;
						if ( hebrewDay <= days ) {
						// Day in Adar II
							hebrewMonth = 14;
						}
					}
				}
			} else if ( hebrewMonth === 2 && yearPattern === 2 ) {
				// Cheshvan in a complete year (otherwise as the rule below)
				days = 30;
			} else if ( hebrewMonth === 3 && yearPattern === 0 ) {
				// Kislev in an incomplete year (otherwise as the rule below)
				days = 29;
			} else {
				// Odd months have 30 days, even have 29
				days = 30 - ( hebrewMonth - 1 ) % 2;
			}
			if ( hebrewDay <= days ) {
				// In the current month
				break;
			} else {
				// Subtract the days of the current month
				hebrewDay -= days;
				// Try in the next month
				hebrewMonth++;
			}
		}

		return [ hebrewYear, hebrewMonth, hebrewDay, days ];
	}

	/**
	 * This calculates the Hebrew year start, as days since 1 September.
	 * Based on Carl Friedrich Gauss algorithm for finding Easter date.
	 * Used for Hebrew date.
	 *
	 * @param {Number} year
	 *
	 * @return {Number}
	 */
	function hebrewYearStart( year ) {
		var a, b, c, m, Mar;
		a = parseInt( ( 12 * ( year - 1 ) + 17 ) % 19, 10 );
		b = parseInt( ( year - 1 ) % 4, 10 );
		m = 32.044093161144 + 1.5542417966212 * a + b / 4.0 - 0.0031777940220923 * ( year - 1 );
		if ( m < 0 ) {
			m--;
		}
		Mar = parseInt( m, 10 );
		if ( m < 0 ) {
			m++;
		}
		m -= Mar;

		c = parseInt( ( Mar + 3 * ( year - 1 ) + 5 * b + 5 ) % 7, 10 );
		if ( c === 0 && a > 11 && m >= 0.89772376543210 ) {
			Mar++;
		} else if ( c === 1 && a > 6 && m >= 0.63287037037037 ) {
			Mar += 2;
		} else if ( c === 2 || c === 4 || c === 6 ) {
			Mar++;
		}

		Mar += parseInt( ( year - 3761 ) / 100, 10 ) - parseInt( ( year - 3761 ) / 400, 10 ) - 24;
		return Mar;
	}

	/**
	 * Algorithm by Roozbeh Pournader and Mohammad Toossi to convert
	 * Gregorian dates to Iranian dates. Originally written in C, it
	 * is released under the terms of GNU Lesser General Public
	 * License. Preceding conversion to PHP was performed by Niklas Laxström.
	 *
	 * Link: http://www.farsiweb.info/jalali/jalali.c
	 *
	 * @param {String} ts
	 * @returns {Array}
	 */
	function tsToIranian( ts ) {
		var gy, gm, gd, gDayNo, i, jDayNo, jNp, jy, jm, jd;

		gy = ts.substr( 0, 4 ) - 1600;
		gm = ts.substr( 4, 2 ) - 1;
		gd = ts.substr( 6, 2 ) - 1;

		// Days passed from the beginning (including leap years)
		gDayNo = 365 * gy
			+ Math.floor( ( gy + 3 ) / 4 )
			- Math.floor( ( gy + 99 ) / 100 )
			+ Math.floor( ( gy + 399 ) / 400 );

		// Add days of the past months of this year
		for ( i = 0; i < gm; i++ ) {
			gDayNo += gregDays[i];
		}

		// Leap years
		if ( gm > 1 && ( ( gy % 4 === 0 && gy % 100 !== 0 || ( gy % 400 === 0 ) ) ) ) {
			gDayNo++;
		}

		// Days passed in current month
		gDayNo += gd - 0;

		jDayNo = gDayNo - 79;

		jNp = Math.floor( jDayNo / 12053 );
		jDayNo %= 12053;

		jy = 979 + 33 * jNp + 4 * Math.floor( jDayNo / 1461 );
		jDayNo %= 1461;

		if ( jDayNo >= 366 ) {
			jy += Math.floor( ( jDayNo - 1 ) / 365 );
			jDayNo = Math.floor( ( jDayNo - 1 ) % 365 );
		}

		for ( i = 0; i < 11 && jDayNo >= iranianDays[i]; i++ ) {
			jDayNo -= iranianDays[i];
		}

		jm = i + 1;
		jd = jDayNo + 1;

		return [ jy, jm, jd ];
	}

	/**
	 * Converting Gregorian dates to Hijri dates.
	 *
	 * Based on a PHP-Nuke block by Sharjeel which is released under GNU/GPL license
	 *
	 * @see http://phpnuke.org/modules.php?name=News&file=article&sid=8234&mode=thread&order=0&thold=0
	 * @param {String} ts
	 * @returns {Array}
	 */
	function tsToHijri( ts ) {
		var year, month, day, zyr, zd, zm, zy, zjd, zl, zn, zj;
		year = ts.substr( 0, 4 );
		month = ts.substr( 4, 2 );
		day = ts.substr( 6, 2 );

		zyr = year - 0;
		zd = day - 0;
		zm = month - 0;
		zy = zyr - 0;

		if (
			( zy > 1582 ) || ( ( zy === 1582 ) && ( zm > 10 ) ) ||
				( ( zy === 1582 ) && ( zm === 10 ) && ( zd > 14 ) )
			)
		{
			zjd = parseInt( ( 1461 * ( zy + 4800 + parseInt( ( zm - 14 ) / 12, 10 ) ) ) / 4, 10 ) +
				parseInt( ( 367 * ( zm - 2 - 12 * ( parseInt( ( zm - 14 ) / 12, 10 ) ) ) ) / 12, 10 ) -
				parseInt( ( 3 * parseInt( ( ( zy + 4900 + parseInt( ( zm - 14 ) / 12, 10 ) ) / 100 ), 10 ) ) / 4, 10 ) +
				zd - 32075;
		} else {
			zjd = 367 * zy - parseInt( ( 7 * ( zy + 5001 + parseInt( ( zm - 9 ) / 7, 10 ) ) ) / 4, 10 ) +
				parseInt( ( 275 * zm ) / 9, 10 ) + zd + 1729777;
		}

		zl = zjd -1948440 + 10632;
		zn = parseInt( ( zl - 1 ) / 10631, 10 );
		zl = zl - 10631 * zn + 354;
		zj = ( parseInt( ( 10985 - zl ) / 5316, 10 ) ) * ( parseInt( ( 50 * zl ) / 17719, 10 ) ) + ( parseInt( zl / 5670, 10 ) ) * ( parseInt( ( 43 * zl ) / 15238, 10 ) );
		zl = zl - ( parseInt( ( 30 - zj ) / 15, 10 ) ) * ( parseInt( ( 17719 * zj ) / 50, 10 ) ) - ( parseInt( zj / 16, 10 ) ) * ( parseInt( ( 15238 * zj ) / 43, 10 ) ) + 29;
		zm = parseInt( ( 24 * zl ) / 709, 10 );
		zd = zl - parseInt( ( 709 * zm ) / 24, 10 );
		zy = 30 * zn + zj - 30;

		return [ zy, zm, zd ];
	}

	/**
	 * Algorithm to convert Gregorian dates to Thai solar dates,
	 * Minguo dates or Minguo dates.
	 *
	 * Link: http://en.wikipedia.org/wiki/Thai_solar_calendar
	 *       http://en.wikipedia.org/wiki/Minguo_calendar
	 *       http://en.wikipedia.org/wiki/Japanese_era_name
	 * @param {String} ts Timestamp
	 * @param {String} cName Calendar name; [ 'thai', 'minguo', 'tenno' ] available
	 * @returns {Array}
	 */
	function tsToYear( ts, cName ) {
		var gy, gm, gd, gyOffset, gyGannen;

		gy = ts.substr( 0, 4 ) - 0;
		gm = ts.substr( 4, 2 ) - 0;
		gd = ts.substr( 6, 2 ) - 0;

		if ( cName === 'thai' ) {
			// Thai solar dates
			// Add 543 years to the Gregorian calendar
			// Months and days are identical
			gyOffset = gy + 543;
		} else if ( ( cName === 'minguo' ) || cName === 'juche' ) {
			// Minguo dates
			// Deduct 1911 years from the Gregorian calendar
			// Months and days are identical
			gyOffset = gy - 1911;
		} else if ( cName === 'tenno' ) {
			// Nengō dates up to Meiji period
			// Deduct years from the Gregorian calendar
			// depending on the nengo periods
			// Months and days are identical
			if ( ( gy < 1912 ) || ( ( gy === 1912 ) && ( gm < 7 ) ) || ( ( gy === 1912 ) && ( gm === 7 ) && ( gd < 31 ) ) ) {
				// Meiji period
				gyGannen = gy - 1868 + 1;
				gyOffset = gyGannen;
				if ( gyGannen === 1 ) {
					gyOffset = '元';
				}
				gyOffset = '明治' + gyOffset;
			} else if (
				( ( gy === 1912 ) && ( gm === 7 ) && ( gd === 31 ) ) ||
					( ( gy === 1912 ) && ( gm >= 8 ) ) ||
					( ( gy > 1912 ) && ( gy < 1926 ) ) ||
					( ( gy === 1926 ) && ( gm < 12 ) ) ||
					( ( gy === 1926 ) && ( gm === 12 ) && ( gd < 26 ) )
			)
			{
				// Taishō period
				gyGannen = gy - 1912 + 1;
				gyOffset = gyGannen;
				if ( gyGannen === 1 ) {
					gyOffset = '元';
				}
				gyOffset = '大正' + gyOffset;
			} else if (
				( ( gy === 1926 ) && ( gm === 12 ) && ( gd >= 26 ) ) ||
					( ( gy > 1926 ) && ( gy < 1989 ) ) ||
					( ( gy === 1989 ) && ( gm === 1 ) && ( gd < 8 ) )
			)
			{
				// Shōwa period
				gyGannen = gy - 1926 + 1;
				gyOffset = gyGannen;
				if ( gyGannen === 1 ) {
					gyOffset = '元';
				}
				gyOffset = '昭和' + gyOffset;
			} else {
				// Heisei period
				gyGannen = gy - 1989 + 1;
				gyOffset = gyGannen;
				if ( gyGannen === 1 ) {
					gyOffset = '元';
				}
				gyOffset = '平成' + gyOffset;
			}
		} else {
			gyOffset = gy;
		}

		return [ gyOffset, gm, gd ];
	}

	$.extend( mw.language, {

		/**
		 * Converts the timestamp according to the supplied format
		 *
		 * @param {String} format
		 * @param {String} ts
		 * @todo Implement time zones
		 * @returns {String}
		 */
		sprintfDate: function ( format, ts ) {
			if ( ts.length !== 14 ) {
				throw new Error( 'The timestamp ' + ts + ' should have 14 characters' );
			}

			if ( !ts.match( /\d{14}/ ) ) {
				throw new Error( 'The timestamp ' + ts + ' should be a number' );
			}

			var s = '',
				raw = false,
				roman = false,
				hebrewNum = false,
				rawToggle = false,
				datetime = [],
				dateTimeObj, yearStartObj, firstThursdayObj,
				iranian, hebrew, hijri, thai, minguo, tenno,

				// temporary variables
				p, h, num, endQuote, firstThursday, weekNumber,
				code = '';

			datetime = ts.match( /(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/ );
			datetime.shift();
			$.each( datetime, function( i ) {
				datetime[i] -= 0;
			} );
			yearStartObj = new Date( datetime[0], 0, 1 );

			for ( p = 0; p < format.length; p++ ) {
				num = false;
				code = format[p];
				if ( code === 'x' && p < format.length - 1 ) {
					code += format[++p];
				}

				if ( ( code === 'xi' || code === 'xj' || code === 'xk' || code === 'xm' || code === 'xo' || code === 'xt' ) && p < format.length - 1 ) {
					code += format[++p];
				}

				switch ( code ) {
					case 'xx':
						s += 'x';
						break;
					case 'xn':
						raw = true;
						break;
					case 'xN':
						rawToggle = !rawToggle;
						break;
					case 'xr':
						roman = true;
						break;
					case 'xh':
						hebrewNum = true;
						break;
					case 'xg':
						s += mw.language.getMonthNameGen( datetime[1] );
						break;
					case 'xjx':
						if ( !hebrew ) {
							hebrew = tsToHebrew( ts );
						}
						s += mw.language.getHebrewCalendarMonthNameGen( hebrew[1] );
						break;
					case 'd':
						num = ts.substr( 4, 2 );
						break;
					case 'D':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						s += mw.language.getWeekdayAbbreviation( dateTimeObj.getDay() + 1 );
						break;
					case 'j':
						num = datetime[2];
						break;
					case 'xij':
						if ( !iranian ) {
							iranian = tsToIranian( ts );
						}
						num = iranian[2];
						break;
					case 'xmj':
						if ( !hijri ) {
							hijri = tsToHijri( ts );
						}
						num = hijri[2];
						break;
					case 'xjj':
						if ( !hebrew ) {
							hebrew = tsToHebrew( ts );
						}
						num = hebrew[2];
						break;
					case 'l':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						s += mw.language.getWeekdayName( dateTimeObj.getDay() + 1 );
						break;
					case 'F':
						s += mw.language.getMonthName( datetime[1] );
						break;
					case 'xiF':
						if ( !iranian ) {
							iranian = tsToIranian( ts );
						}
						s += mw.language.getIranianCalendarMonthName( iranian[1] );
						break;
					case 'xmF':
						if ( !hijri ) {
							hijri = tsToHijri( ts );
						}
						s += mw.language.getHijriCalendarMonthName( hijri[1] );
						break;
					case 'xjF':
						if ( !hebrew ) {
							hebrew = tsToHebrew( ts );
						}
						s += mw.language.getHebrewCalendarMonthName( hebrew[1] );
						break;
					case 'm':
						num = ts.substr( 4, 2 );
						break;
					case 'M':
						s += mw.language.getMonthAbbreviation( datetime[1] );
						break;
					case 'n':
						num = datetime[1];
						break;
					case 'xin':
						if ( !iranian ) {
							iranian = tsToIranian( ts );
						}
						num = iranian[1];
						break;
					case 'xmn':
						if ( !hijri ) {
							hijri = tsToHijri( ts );
						}
						num = hijri[1];
						break;
					case 'xjn':
						if ( !hebrew ) {
							hebrew = tsToHebrew( ts );
						}
						num = hebrew[1];
						break;
					case 'xjt':
						if ( !hebrew ) {
							hebrew = tsToHebrew( ts );
						}
						num = hebrew[3];
						break;
					case 'Y':
						num = datetime[0];
						break;
					case 'xiY':
						if ( !iranian ) {
							iranian = tsToIranian( ts );
						}
						num = iranian[0];
						break;
					case 'xmY':
						if ( !hijri ) {
							hijri = tsToHijri( ts );
						}
						num = hijri[0];
						break;
					case 'xjY':
						if ( !hebrew ) {
							hebrew = tsToHebrew( ts );
						}
						num = hebrew[0];
						break;
					case 'xkY':
						if ( !thai ) {
							thai = tsToYear( ts, 'thai' );
						}
						num = thai[0];
						break;
					case 'xoY':
						if ( !minguo ) {
							minguo = tsToYear( ts, 'minguo' );
						}
						num = minguo[0];
						break;
					case 'xtY':
						if ( !tenno ) {
							tenno = tsToYear( ts, 'tenno' );
						}
						num = tenno[0];
						break;
					case 'y':
						num = ts.substr( 2, 2 );
						break;
					case 'xiy':
						if ( !iranian ) {
							iranian = tsToIranian( ts );
						}
						num = iranian[0].substr( -2 );
						break;
					case 'a':
						s += ( datetime[3] < 12 ) ? 'am' : 'pm';
						break;
					case 'A':
						s += ( datetime[3] < 12 ) ? 'AM' : 'PM';
						break;
					case 'g':
						h = datetime[3];
						num = h % 12 ? h % 12 : 12;
						break;
					case 'G':
						num = datetime[3];
						break;
					case 'h':
						h = datetime[3];
						num = ( '0' + ( h % 12 ? h % 12 : 12 ) ).slice( -2 );
						break;
					case 'H':
						num = ts.substr( 8, 2 );
						break;
					case 'i':
						num = ts.substr( 10, 2 );
						break;
					case 's':
						num = ts.substr( 12, 2 );
						break;
					case 'c':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						s += dateTimeObj.toISOString();
						break;
					case 'r':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						//FIXME: RFC 5322 says UTC zone should be displayed with - instead of + if
						//FIXME: there is no sufficient information about the time zone.
						//FIXME: Related: case 'c'
						s += dateTimeObj.toUTCString().replace( /GMT$/, '+0000' );
						break;
					//case 'e':
					//case 'O':
					//case 'P':
					//case 'T':
					case 'w':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						num = dateTimeObj.getDay();
						break;
					case 'N':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						num = dateTimeObj.getDay() + 1;
						break;
					case 'z':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						num = Math.floor( ( dateTimeObj - yearStartObj ) / ( 1000 * 60 * 60 * 24 ) );
						break;
					case 'W':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						/**
						 * Copyright (C) 2009 Taco van den Broek
						 * This piece of software is subject to the MIT License
						 *
						 * @see http://techblog.procurios.nl/k/news/view/33796/14863/Calculate-ISO-8601-week-and-year-in-javascript.html
						 */
						firstThursdayObj = new Date( dateTimeObj.valueOf() );
						firstThursdayObj.setDate( firstThursdayObj.getDate() - ( firstThursdayObj.getDay() + 6 ) % 7 + 3 );
						firstThursday = firstThursdayObj.valueOf();
						firstThursdayObj.setMonth( 0, 1 );
						if ( firstThursdayObj.getDay() !== 4 ) {
							firstThursdayObj.setMonth( 0, 1 + ( (4 - firstThursdayObj.getDay() ) + 7 ) % 7 );
						}
						weekNumber = 1 + Math.ceil( ( firstThursday - firstThursdayObj ) / ( 1000 * 60 * 60 * 24 * 7 ) );
						s += ( weekNumber < 10 ? '0' : '' ) + mw.language.convertGrammar( weekNumber, false, false );
						break;
					case 't':
						num = gregDays[datetime[1] - 1];
						break;
					case 'L':
						num = ( new Date( datetime[0], 1, 29 ).getMonth() - 1 ) ? 0 : 1;
						break;
					case 'o':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						firstThursdayObj = new Date( dateTimeObj.valueOf() );
						firstThursdayObj.setDate( firstThursdayObj.getDate() - ( ( dateTimeObj.getDay() + 6 ) % 7 ) + 3 );
						num = firstThursdayObj.getFullYear();
						break;
					case 'U':
						if ( !dateTimeObj ) {
							dateTimeObj = getDateObj( ts );
						}
						num = dateTimeObj.valueOf() / 1000;
						break;
					// case 'I':
					// case 'Z':
					case '\\':
						if ( p < format.length - 1 ) {
							s += format[++p];
						} else {
							s += '\\';
						}
						break;
					case '"':
						if ( p < format.length - 1 ) {
							endQuote = format.indexOf( '"', p + 1 );
							if ( endQuote === -1 ) {
								// No terminating quote, assuming literal "
								s += '"';
							} else {
								s += format.substr( p + 1, endQuote - p -1 );
								p = endQuote;
							}
						} else {
							// Quote at the end of string, assuming literal "
							s += '"';
						}
						break;
					default:
						s += format[p];
				}

				if ( num !== false ) {
					if ( rawToggle || raw ) {
						s += num;
						raw = false;
					} else if ( roman ) {
						s += mw.language.romanNumeral( num );
						roman = false;
					} else if ( hebrewNum ) {
						s += mw.language.hebrewNumeral( num );
						hebrewNum = false;
					} else {
						s += mw.language.convertNumber( num, false, false );
					}
				}
			}

			return s;
		},

		/**
		 * Get the name of month
		 *
		 * @param {Number} key No. of month 1-12
		 * @returns {String}
		 */
		getMonthName: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).monthNames.local.nominative[key];
		},

		/**
		 * Get the name of month in genitive case
		 *
		 * @param {Number} key No. of month 1-12
		 * @returns {String}
		 */
		getMonthNameGen: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).monthNames.local.genitive[key];
		},

		/**
		 * Get the month abbreviation
		 *
		 * @param {Number} key No. of month 1-12
		 * @returns {String}
		 */
		getMonthAbbreviation: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).monthAbbreviations[ key ];
		},

		/**
		 * Get the week day abbreviation
		 *
		 * @param {Number} key No. of week day 1-7
		 * @returns {String}
		 */
		getWeekdayAbbreviation: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).weekdayAbbreviations[ key - 1 ];
		},

		/**
		 * Get the week day name
		 *
		 * @param {Number} key No. of week day 1-7
		 * @returns {String}
		 */
		getWeekdayName: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).weekdayNames[ key - 1 ];
		},

		/**
		 * Get the month name in Iranian calendar
		 *
		 * @param {Number} key No. of Iranian month 1-12
		 * @returns {String}
		 */
		getIranianCalendarMonthName: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).monthNames.iranian[key];
		},

		/**
		 * Get the month name in Hijri calendar
		 *
		 * @param {Number} key No. of Hijri month 1-12
		 * @returns {String}
		 */
		getHijriCalendarMonthName: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).monthNames.hijri[key];
		},

		/**
		 * Get the month name in Hebrew calendar
		 *
		 * @param {Number} key No. of Hebrew month 1-14
		 * @returns {String}
		 */
		getHebrewCalendarMonthName: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).monthNames.hebrew.nominative[key];
		},

		/**
		 * Get the month name in Hebrew calendar, genitive case
		 *
		 * @param {Number} key No. of Hebrew month 1-14
		 * @returns {String}
		 */
		getHebrewCalendarMonthNameGen: function ( key ) {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ) ).monthNames.hebrew.genitive[key];
		},

		/**
		 * Roman number formatting up to 10000
		 *
		 * @param {Number} num
		 * @returns {String}
		 */
		romanNumeral: function ( num ) {
			var s = '', pow10, i;
			num = parseInt( num, 10 );
			if ( num > 10000  || num <= 0 ) {
				return num;
			}

			for ( pow10 = 1000, i = 3; i >= 0; pow10 /= 10, i-- ) {
				if ( num >= pow10 ) {
					s += romanTransformTable[i][parseInt( Math.floor( num / pow10 ), 10 )];
				}
				num = num % pow10;
			}
			return s;
		},

		/**
		 * Hebrew Gematria number formatting up to 9999
		 *
		 * @param {Number} num
		 * @returns {String}
		 */
		hebrewNumeral: function ( num ) {
			var s = '', str = '', pow10, i, start, end;
			num = parseInt( num, 10 );
			if ( num > 9999 || num <= 0 ) {
				return num;
			}

			for ( pow10 = 1000, i = 3; i >= 0; pow10 /= 10, i-- ) {
				if ( num >= pow10 ) {
					if ( num === 15 || num === 16 ) {
						s += hebrewTransformTable[0][9] + hebrewTransformTable[0][num - 9];
						num = 0;
					} else {
						s += hebrewTransformTable[i][parseInt( ( num / pow10 ), 10 )];
						if ( pow10 === 1000 ) {
							s += '\'';
						}
					}
				}
				num = num % pow10;
			}

			// Unless PHP, JavaScript counts characters instead of bytes
			if ( s.length === 1 ) {
				str = s + '\'';
			} else {
				str = s.substr( 0, s.length - 1 ) + '"';
				str += s.substr( s.length - 1, 1 );
			}
			start = str.substr( 0, str.length - 1 );
			end = str.substr( str.length - 1 );
			switch ( end ) {
				case 'כ':
					str = start + 'ך';
					break;
				case 'מ':
					str = start + 'ם';
					break;
				case 'נ':
					str = start + 'ן';
					break;
				case 'פ':
					str = start + 'ף';
					break;
				case 'צ':
					str = start + 'ץ';
					break;
			}
			return str;
		}

	} );

}( mediaWiki, jQuery ) );
