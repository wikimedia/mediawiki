/**
 * TableSorter for MediaWiki
 *
 * Written 2011 Leo Koppelkamm
 * Based on tablesorter.com plugin, written (c) 2007 Christian Bach.
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Depends on mw.config (wgDigitTransformTable, wgMonthNames, wgMonthNamesShort,
 * wgDefaultDateFormat, wgContentLanguage)
 * Uses 'tableSorterCollation' in mw.config (if available)
 */
/**
 *
 * @description Create a sortable table with multi-column sorting capabilitys
 *
 * @example $( 'table' ).tablesorter();
 * @desc Create a simple tablesorter interface.
 *
 * @option String cssHeader ( optional ) A string of the class name to be appended
 *         to sortable tr elements in the thead of the table. Default value:
 *         "header"
 *
 * @option String cssAsc ( optional ) A string of the class name to be appended to
 *         sortable tr elements in the thead on a ascending sort. Default value:
 *         "headerSortUp"
 *
 * @option String cssDesc ( optional ) A string of the class name to be appended
 *         to sortable tr elements in the thead on a descending sort. Default
 *         value: "headerSortDown"
 *
 * @option String sortInitialOrder ( optional ) A string of the inital sorting
 *         order can be asc or desc. Default value: "asc"
 *
 * @option String sortMultisortKey ( optional ) A string of the multi-column sort
 *         key. Default value: "shiftKey"
 *
 * @option Boolean sortLocaleCompare ( optional ) Boolean flag indicating whatever
 *         to use String.localeCampare method or not. Set to false.
 *
 * @option Boolean cancelSelection ( optional ) Boolean flag indicating if
 *         tablesorter should cancel selection of the table headers text.
 *         Default value: true
 *
 * @option Boolean debug ( optional ) Boolean flag indicating if tablesorter
 *         should display debuging information usefull for development.
 *
 * @type jQuery
 *
 * @name tablesorter
 *
 * @cat Plugins/Tablesorter
 *
 * @author Christian Bach/christian.bach@polyester.se
 */

( function ( $, mw ) {

	/* Local scope */

	var ts,
		parsers = [];

	/* Parser utility functions */

	function getParserById( name ) {
		var len = parsers.length;
		for ( var i = 0; i < len; i++ ) {
			if ( parsers[i].id.toLowerCase() === name.toLowerCase() ) {
				return parsers[i];
			}
		}
		return false;
	}

	function getElementText( node ) {
		var $node = $( node ),
			// Use data-sort-value attribute.
			// Use data() instead of attr() so that live value changes
			// are processed as well (bug 38152).
			data = $node.data( 'sortValue' );

		if ( data !== null && data !== undefined ) {
			// Cast any numbers or other stuff to a string, methods
			// like charAt, toLowerCase and split are expected.
			return String( data );
		} else {
			return $node.text();
		}
	}

	function getTextFromRowAndCellIndex( rows, rowIndex, cellIndex ) {
		if ( rows[rowIndex] && rows[rowIndex].cells[cellIndex] ) {
			return $.trim( getElementText( rows[rowIndex].cells[cellIndex] ) );
		} else {
			return '';
		}
	}

	function detectParserForColumn( table, rows, cellIndex ) {
		var l = parsers.length,
			nodeValue,
			// Start with 1 because 0 is the fallback parser
			i = 1,
			rowIndex = 0,
			concurrent = 0,
			needed = ( rows.length > 4 ) ? 5 : rows.length;

		while( i < l ) {
			nodeValue = getTextFromRowAndCellIndex( rows, rowIndex, cellIndex );
			if ( nodeValue !== '') {
				if ( parsers[i].is( nodeValue, table ) ) {
					concurrent++;
					rowIndex++;
					if ( concurrent >= needed ) {
						// Confirmed the parser for multiple cells, let's return it
						return parsers[i];
					}
				} else {
					// Check next parser, reset rows
					i++;
					rowIndex = 0;
					concurrent = 0;
				}
			} else {
				// Empty cell
				rowIndex++;
				if ( rowIndex > rows.length ) {
					rowIndex = 0;
					i++;
				}
			}
		}

		// 0 is always the generic parser (text)
		return parsers[0];
	}

	function buildParserCache( table, $headers ) {
		var rows = table.tBodies[0].rows,
			sortType,
			parsers = [];

		if ( rows[0] ) {

			var cells = rows[0].cells,
				len = cells.length,
				i, parser;

			for ( i = 0; i < len; i++ ) {
				parser = false;
				sortType = $headers.eq( i ).data( 'sort-type' );
				if ( sortType !== undefined ) {
					parser = getParserById( sortType );
				}

				if ( parser === false ) {
					parser = detectParserForColumn( table, rows, i );
				}

				parsers.push( parser );
			}
		}
		return parsers;
	}

	/* Other utility functions */

	function buildCache( table ) {
		var totalRows = ( table.tBodies[0] && table.tBodies[0].rows.length ) || 0,
			totalCells = ( table.tBodies[0].rows[0] && table.tBodies[0].rows[0].cells.length ) || 0,
			parsers = table.config.parsers,
			cache = {
				row: [],
				normalized: []
			};

		for ( var i = 0; i < totalRows; ++i ) {

			// Add the table data to main data array
			var $row = $( table.tBodies[0].rows[i] ),
				cols = [];

			// if this is a child row, add it to the last row's children and
			// continue to the next row
			if ( $row.hasClass( table.config.cssChildRow ) ) {
				cache.row[cache.row.length - 1] = cache.row[cache.row.length - 1].add( $row );
				// go to the next for loop
				continue;
			}

			cache.row.push( $row );

			for ( var j = 0; j < totalCells; ++j ) {
				cols.push( parsers[j].format( getElementText( $row[0].cells[j] ), table, $row[0].cells[j] ) );
			}

			cols.push( cache.normalized.length ); // add position for rowCache
			cache.normalized.push( cols );
			cols = null;
		}

		return cache;
	}

	function appendToTable( table, cache ) {
		var row = cache.row,
			normalized = cache.normalized,
			totalRows = normalized.length,
			checkCell = ( normalized[0].length - 1 ),
			fragment = document.createDocumentFragment();

		for ( var i = 0; i < totalRows; i++ ) {
			var pos = normalized[i][checkCell];

			var l = row[pos].length;

			for ( var j = 0; j < l; j++ ) {
				fragment.appendChild( row[pos][j] );
			}

		}
		table.tBodies[0].appendChild( fragment );
	}

	/**
	 * Find all header rows in a thead-less table and put them in a <thead> tag.
	 * This only treats a row as a header row if it contains only <th>s (no <td>s)
	 * and if it is preceded entirely by header rows. The algorithm stops when
	 * it encounters the first non-header row.
	 *
	 * After this, it will look at all rows at the bottom for footer rows
	 * And place these in a tfoot using similar rules.
	 * @param $table jQuery object for a <table>
	 */
	function emulateTHeadAndFoot( $table ) {
		var $rows = $table.find( '> tbody > tr' );
		if( !$table.get(0).tHead ) {
			var $thead = $( '<thead>' );
			$rows.each( function () {
				if ( $(this).children( 'td' ).length > 0 ) {
					// This row contains a <td>, so it's not a header row
					// Stop here
					return false;
				}
				$thead.append( this );
			} );
			$table.find(' > tbody:first').before( $thead );
		}
		if( !$table.get(0).tFoot ) {
			var $tfoot = $( '<tfoot>' );
			var len = $rows.length;
			for ( var i = len-1; i >= 0; i-- ) {
				if( $( $rows[i] ).children( 'td' ).length > 0 ){
					break;
				}
				$tfoot.prepend( $( $rows[i] ));
			}
			$table.append( $tfoot );
		}
	}

	function buildHeaders( table, msg ) {
		var maxSeen = 0,
			longest,
			realCellIndex = 0,
			$tableHeaders = $( 'thead:eq(0) > tr', table );
		if ( $tableHeaders.length > 1 ) {
			$tableHeaders.each( function () {
				if ( this.cells.length > maxSeen ) {
					maxSeen = this.cells.length;
					longest = this;
				}
			});
			$tableHeaders = $( longest );
		}
		$tableHeaders = $tableHeaders.children( 'th' ).each( function ( index ) {
			this.column = realCellIndex;

			var colspan = this.colspan;
			colspan = colspan ? parseInt( colspan, 10 ) : 1;
			realCellIndex += colspan;

			this.order = 0;
			this.count = 0;

			if ( $( this ).is( '.unsortable' ) ) {
				this.sortDisabled = true;
			}

			if ( !this.sortDisabled ) {
				var $th = $( this ).addClass( table.config.cssHeader ).attr( 'title', msg[1] );
			}

			// add cell to headerList
			table.config.headerList[index] = this;
		} );

		return $tableHeaders;

	}

	function isValueInArray( v, a ) {
		var l = a.length;
		for ( var i = 0; i < l; i++ ) {
			if ( a[i][0] === v ) {
				return true;
			}
		}
		return false;
	}

	function setHeadersCss( table, $headers, list, css, msg ) {
		// Remove all header information
		$headers.removeClass( css[0] ).removeClass( css[1] );

		var h = [];
		$headers.each( function ( offset ) {
			if ( !this.sortDisabled ) {
				h[this.column] = $( this );
			}
		} );

		var l = list.length;
		for ( var i = 0; i < l; i++ ) {
			h[ list[i][0] ].addClass( css[ list[i][1] ] ).attr( 'title', msg[ list[i][1] ] );
		}
	}

	function sortText( a, b ) {
		return ( (a < b) ? -1 : ((a > b) ? 1 : 0) );
	}

	function sortTextDesc( a, b ) {
		return ( (b < a) ? -1 : ((b > a) ? 1 : 0) );
	}

	function multisort( table, sortList, cache ) {
		var sortFn = [];
		var len = sortList.length;
		for ( var i = 0; i < len; i++ ) {
			sortFn[i] = ( sortList[i][1] ) ? sortTextDesc : sortText;
		}
		cache.normalized.sort( function ( array1, array2 ) {
			var col, ret;
			for ( var i = 0; i < len; i++ ) {
				col = sortList[i][0];
				ret = sortFn[i].call( this, array1[col], array2[col] );
				if ( ret !== 0 ) {
					return ret;
				}
			}
			// Fall back to index number column to ensure stable sort
			return sortText.call( this, array1[array1.length - 1], array2[array2.length - 1] );
		} );
		return cache;
	}

	function buildTransformTable() {
		var digits = '0123456789,.'.split( '' );
		var separatorTransformTable = mw.config.get( 'wgSeparatorTransformTable' );
		var digitTransformTable = mw.config.get( 'wgDigitTransformTable' );
		if ( separatorTransformTable === null || ( separatorTransformTable[0] === '' && digitTransformTable[2] === '' ) ) {
			ts.transformTable = false;
		} else {
			ts.transformTable = {};

			// Unpack the transform table
			var ascii = separatorTransformTable[0].split( "\t" ).concat( digitTransformTable[0].split( "\t" ) );
			var localised = separatorTransformTable[1].split( "\t" ).concat( digitTransformTable[1].split( "\t" ) );

			// Construct regex for number identification
			for ( var i = 0; i < ascii.length; i++ ) {
				ts.transformTable[localised[i]] = ascii[i];
				digits.push( $.escapeRE( localised[i] ) );
			}
		}
		var digitClass = '[' + digits.join( '', digits ) + ']';

		// We allow a trailing percent sign, which we just strip. This works fine
		// if percents and regular numbers aren't being mixed.
		ts.numberRegex = new RegExp("^(" + "[-+\u2212]?[0-9][0-9,]*(\\.[0-9,]*)?(E[-+\u2212]?[0-9][0-9,]*)?" + // Fortran-style scientific
		"|" + "[-+\u2212]?" + digitClass + "+[\\s\\xa0]*%?" + // Generic localised
		")$", "i");
	}

	function buildDateTable() {
		var regex = [];
		ts.monthNames = {};

		for ( var i = 1; i < 13; i++ ) {
			var name = mw.config.get( 'wgMonthNames' )[i].toLowerCase();
			ts.monthNames[name] = i;
			regex.push( $.escapeRE( name ) );
			name = mw.config.get( 'wgMonthNamesShort' )[i].toLowerCase().replace( '.', '' );
			ts.monthNames[name] = i;
			regex.push( $.escapeRE( name ) );
		}

		// Build piped string
		regex = regex.join( '|' );

		// Build RegEx
		// Any date formated with . , ' - or /
		ts.dateRegex[0] = new RegExp( /^\s*(\d{1,2})[\,\.\-\/'\s]{1,2}(\d{1,2})[\,\.\-\/'\s]{1,2}(\d{2,4})\s*?/i);

		// Written Month name, dmy
		ts.dateRegex[1] = new RegExp( '^\\s*(\\d{1,2})[\\,\\.\\-\\/\'\\s]*(' + regex + ')' + '[\\,\\.\\-\\/\'\\s]*(\\d{2,4})\\s*$', 'i' );

		// Written Month name, mdy
		ts.dateRegex[2] = new RegExp( '^\\s*(' + regex + ')' + '[\\,\\.\\-\\/\'\\s]*(\\d{1,2})[\\,\\.\\-\\/\'\\s]*(\\d{2,4})\\s*$', 'i' );

	}

	function explodeRowspans( $table ) {
		// Split multi row cells into multiple cells with the same content
		$table.find( '> tbody > tr > [rowspan]' ).each(function () {
			var rowSpan = this.rowSpan;
			this.rowSpan = 1;
			var cell = $( this );
			var next = cell.parent().nextAll();
			for ( var i = 0; i < rowSpan - 1; i++ ) {
				var td = next.eq( i ).children( 'td' );
				if ( !td.length ) {
					next.eq( i ).append( cell.clone() );
				} else if ( this.cellIndex === 0 ) {
					td.eq( this.cellIndex ).before( cell.clone() );
				} else {
					td.eq( this.cellIndex - 1 ).after( cell.clone() );
				}
			}
		});
	}

	function buildCollationTable() {
		ts.collationTable = mw.config.get( 'tableSorterCollation' );
		ts.collationRegex = null;
		if ( ts.collationTable ) {
			var keys = [];

			// Build array of key names
			for ( var key in ts.collationTable ) {
				if ( ts.collationTable.hasOwnProperty(key) ) { //to be safe
					keys.push(key);
				}
			}
			if (keys.length) {
				ts.collationRegex = new RegExp( '[' + keys.join( '' ) + ']', 'ig' );
			}
		}
	}

	function cacheRegexs() {
		if ( ts.rgx ) {
			return;
		}
		ts.rgx = {
			IPAddress: [
				new RegExp( /^\d{1,3}[\.]\d{1,3}[\.]\d{1,3}[\.]\d{1,3}$/)
			],
			currency: [
				new RegExp( /(^[£$€¥]|[£$€¥]$)/),
				new RegExp( /[£$€¥]/g)
			],
			url: [
				new RegExp( /^(https?|ftp|file):\/\/$/),
				new RegExp( /(https?|ftp|file):\/\//)
			],
			isoDate: [
				new RegExp( /^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/)
			],
			usLongDate: [
				new RegExp( /^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/)
			],
			time: [
				new RegExp( /^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(am|pm)))$/)
			]
		};
	}

	/* Public scope */

	$.tablesorter = {

			defaultOptions: {
				cssHeader: 'headerSort',
				cssAsc: 'headerSortUp',
				cssDesc: 'headerSortDown',
				cssChildRow: 'expand-child',
				sortInitialOrder: 'asc',
				sortMultiSortKey: 'shiftKey',
				sortLocaleCompare: false,
				parsers: {},
				widgets: [],
				headers: {},
				cancelSelection: true,
				sortList: [],
				headerList: [],
				selectorHeaders: 'thead tr:eq(0) th',
				debug: false
			},

			dateRegex: [],
			monthNames: {},

			/**
			 * @param $tables {jQuery}
			 * @param settings {Object} (optional)
			 */
			construct: function ( $tables, settings ) {
				return $tables.each( function ( i, table ) {
					// Declare and cache.
					var $document, $headers, cache, config, sortOrder,
						$table = $( table ),
						shiftDown = 0,
						firstTime = true;

					// Quit if no tbody
					if ( !table.tBodies ) {
						return;
					}
					if ( !table.tHead ) {
						// No thead found. Look for rows with <th>s and
						// move them into a <thead> tag or a <tfoot> tag
						emulateTHeadAndFoot( $table );

						// Still no thead? Then quit
						if ( !table.tHead ) {
							return;
						}
					}
					$table.addClass( "jquery-tablesorter" );

					// New config object.
					table.config = {};

					// Merge and extend.
					config = $.extend( table.config, $.tablesorter.defaultOptions, settings );

					// Save the settings where they read
					$.data( table, 'tablesorter', config );

					// Get the CSS class names, could be done else where.
					var sortCSS = [ config.cssDesc, config.cssAsc ];
					var sortMsg = [ mw.msg( 'sort-descending' ), mw.msg( 'sort-ascending' ) ];

					// Build headers
					$headers = buildHeaders( table, sortMsg );

					// Grab and process locale settings
					buildTransformTable();
					buildDateTable();
					buildCollationTable();

					// Precaching regexps can bring 10 fold
					// performance improvements in some browsers.
					cacheRegexs();

					// Apply event handling to headers
					// this is too big, perhaps break it out?
					$headers.click( function ( e ) {
						if ( e.target.nodeName.toLowerCase() === 'a' ) {
							// The user clicked on a link inside a table header
							// Do nothing and let the default link click action continue
							return true;
						}

						if ( firstTime ) {
							firstTime = false;

							// Legacy fix of .sortbottoms
							// Wrap them inside inside a tfoot (because that's what they actually want to be) &
							// and put the <tfoot> at the end of the <table>
							var $sortbottoms = $table.find( '> tbody > tr.sortbottom' );
							if ( $sortbottoms.length ) {
								var $tfoot = $table.children( 'tfoot' );
								if ( $tfoot.length ) {
									$tfoot.eq(0).prepend( $sortbottoms );
								} else {
									$table.append( $( '<tfoot>' ).append( $sortbottoms ) );
								}
							}

							explodeRowspans( $table );
							// try to auto detect column type, and store in tables config
							table.config.parsers = buildParserCache( table, $headers );
						}

						// Build the cache for the tbody cells
						// to share between calculations for this sort action.
						// Re-calculated each time a sort action is performed due to possiblity
						// that sort values change. Shouldn't be too expensive, but if it becomes
						// too slow an event based system should be implemented somehow where
						// cells get event .change() and bubbles up to the <table> here
						cache = buildCache( table );

						var totalRows = ( $table[0].tBodies[0] && $table[0].tBodies[0].rows.length ) || 0;
						if ( !table.sortDisabled && totalRows > 0 ) {

							// Cache jQuery object
							var $cell = $( this );

							// Get current column index
							var i = this.column;

							// Get current column sort order
							this.order = this.count % 2;
							this.count++;

							// User only wants to sort on one column
							if ( !e[config.sortMultiSortKey] ) {
								// Flush the sort list
								config.sortList = [];
								// Add column to sort list
								config.sortList.push( [i, this.order] );

							// Multi column sorting
							} else {
								// The user has clicked on an already sorted column.
								if ( isValueInArray( i, config.sortList ) ) {
									// Reverse the sorting direction for all tables.
									for ( var j = 0; j < config.sortList.length; j++ ) {
										var s = config.sortList[j],
											o = config.headerList[s[0]];
										if ( s[0] === i ) {
											o.count = s[1];
											o.count++;
											s[1] = o.count % 2;
										}
									}
								} else {
									// Add column to sort list array
									config.sortList.push( [i, this.order] );
								}
							}

							// Set CSS for headers
							setHeadersCss( $table[0], $headers, config.sortList, sortCSS, sortMsg );
							appendToTable(
								$table[0], multisort( $table[0], config.sortList, cache )
							);

							// Stop normal event by returning false
							return false;
						}

					// Cancel selection
					} ).mousedown( function () {
						if ( config.cancelSelection ) {
							this.onselectstart = function () {
								return false;
							};
							return false;
						}
					} );
				} );
			},

			addParser: function ( parser ) {
				var l = parsers.length,
					a = true;
				for ( var i = 0; i < l; i++ ) {
					if ( parsers[i].id.toLowerCase() === parser.id.toLowerCase() ) {
						a = false;
					}
				}
				if ( a ) {
					parsers.push( parser );
				}
			},

			formatDigit: function ( s ) {
				if ( ts.transformTable !== false ) {
					var out = '',
						c;
					for ( var p = 0; p < s.length; p++ ) {
						c = s.charAt(p);
						if ( c in ts.transformTable ) {
							out += ts.transformTable[c];
						} else {
							out += c;
						}
					}
					s = out;
				}
				var i = parseFloat( s.replace( /[, ]/g, '' ).replace( "\u2212", '-' ) );
				return ( isNaN(i)) ? 0 : i;
			},

			formatFloat: function ( s ) {
				var i = parseFloat(s);
				return ( isNaN(i)) ? 0 : i;
			},

			formatInt: function ( s ) {
				var i = parseInt( s, 10 );
				return ( isNaN(i)) ? 0 : i;
			},

			clearTableBody: function ( table ) {
				if ( $.browser.msie ) {
					var empty = function ( el ) {
						while ( el.firstChild ) {
							el.removeChild( el.firstChild );
						}
					};
					empty( table.tBodies[0] );
				} else {
					table.tBodies[0].innerHTML = '';
				}
			}
		};

	// Shortcut
	ts = $.tablesorter;

	// Register as jQuery prototype method
	$.fn.tablesorter = function ( settings ) {
		return ts.construct( this, settings );
	};

	// Add default parsers
	ts.addParser( {
		id: 'text',
		is: function ( s ) {
			return true;
		},
		format: function ( s ) {
			s = $.trim( s.toLowerCase() );
			if ( ts.collationRegex ) {
				var tsc = ts.collationTable;
				s = s.replace( ts.collationRegex, function ( match ) {
					var r = tsc[match] ? tsc[match] : tsc[match.toUpperCase()];
					return r.toLowerCase();
				} );
			}
			return s;
		},
		type: 'text'
	} );

	ts.addParser( {
		id: 'IPAddress',
		is: function ( s ) {
			return ts.rgx.IPAddress[0].test(s);
		},
		format: function ( s ) {
			var a = s.split( '.' ),
				r = '',
				l = a.length;
			for ( var i = 0; i < l; i++ ) {
				var item = a[i];
				if ( item.length === 1 ) {
					r += '00' + item;
				} else if ( item.length === 2 ) {
					r += '0' + item;
				} else {
					r += item;
				}
			}
			return $.tablesorter.formatFloat(r);
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'currency',
		is: function ( s ) {
			return ts.rgx.currency[0].test(s);
		},
		format: function ( s ) {
			return $.tablesorter.formatDigit( s.replace( ts.rgx.currency[1], '' ) );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'url',
		is: function ( s ) {
			return ts.rgx.url[0].test(s);
		},
		format: function ( s ) {
			return $.trim( s.replace( ts.rgx.url[1], '' ) );
		},
		type: 'text'
	} );

	ts.addParser( {
		id: 'isoDate',
		is: function ( s ) {
			return ts.rgx.isoDate[0].test(s);
		},
		format: function ( s ) {
			return $.tablesorter.formatFloat((s !== '') ? new Date(s.replace(
			new RegExp( /-/g), '/')).getTime() : '0' );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'usLongDate',
		is: function ( s ) {
			return ts.rgx.usLongDate[0].test(s);
		},
		format: function ( s ) {
			return $.tablesorter.formatFloat( new Date(s).getTime() );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'date',
		is: function ( s ) {
			return ( ts.dateRegex[0].test(s) || ts.dateRegex[1].test(s) || ts.dateRegex[2].test(s ));
		},
		format: function ( s, table ) {
			var match;
			s = $.trim( s.toLowerCase() );

			if ( ( match = s.match( ts.dateRegex[0] ) ) !== null ) {
				if ( mw.config.get( 'wgDefaultDateFormat' ) === 'mdy' || mw.config.get( 'wgContentLanguage' ) === 'en' ) {
					s = [ match[3], match[1], match[2] ];
				} else if ( mw.config.get( 'wgDefaultDateFormat' ) === 'dmy' ) {
					s = [ match[3], match[2], match[1] ];
				}
			} else if ( ( match = s.match( ts.dateRegex[1] ) ) !== null ) {
				s = [ match[3], '' + ts.monthNames[match[2]], match[1] ];
			} else if ( ( match = s.match( ts.dateRegex[2] ) ) !== null ) {
				s = [ match[3], '' + ts.monthNames[match[1]], match[2] ];
			} else {
				// Should never get here
				return '99999999';
			}

			// Pad Month and Day
			if ( s[1].length === 1 ) {
				s[1] = '0' + s[1];
			}
			if ( s[2].length === 1 ) {
				s[2] = '0' + s[2];
			}

			var y;
			if ( ( y = parseInt( s[0], 10) ) < 100 ) {
				// Guestimate years without centuries
				if ( y < 30 ) {
					s[0] = 2000 + y;
				} else {
					s[0] = 1900 + y;
				}
			}
			while ( s[0].length < 4 ) {
				s[0] = '0' + s[0];
			}
			return parseInt( s.join( '' ), 10 );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'time',
		is: function ( s ) {
			return ts.rgx.time[0].test(s);
		},
		format: function ( s ) {
			return $.tablesorter.formatFloat( new Date( '2000/01/01 ' + s ).getTime() );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'number',
		is: function ( s, table ) {
			return $.tablesorter.numberRegex.test( $.trim( s ));
		},
		format: function ( s ) {
			return $.tablesorter.formatDigit(s);
		},
		type: 'numeric'
	} );

}( jQuery, mediaWiki ) );
