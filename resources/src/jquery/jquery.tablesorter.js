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
 * Depends on mw.config (wgDigitTransformTable, wgDefaultDateFormat, wgContentLanguage)
 * and mw.language.months.
 *
 * Uses 'tableSorterCollation' in mw.config (if available)
 */
/**
 *
 * @description Create a sortable table with multi-column sorting capabilitys
 *
 * @example $( 'table' ).tablesorter();
 * @desc Create a simple tablesorter interface.
 *
 * @example $( 'table' ).tablesorter( { sortList: [ { 0: 'desc' }, { 1: 'asc' } ] } );
 * @desc Create a tablesorter interface initially sorting on the first and second column.
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
 * @option Array sortList ( optional ) An array containing objects specifying sorting.
 *         By passing more than one object, multi-sorting will be applied. Object structure:
 *         { <Integer column index>: <String 'asc' or 'desc'> }
 *         Default value: []
 *
 * @option Boolean debug ( optional ) Boolean flag indicating if tablesorter
 *         should display debuging information usefull for development.
 *
 * @event sortEnd.tablesorter: Triggered as soon as any sorting has been applied.
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
		var i,
			len = parsers.length;
		for ( i = 0; i < len; i++ ) {
			if ( parsers[i].id.toLowerCase() === name.toLowerCase() ) {
				return parsers[i];
			}
		}
		return false;
	}

	function getElementSortKey( node ) {
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
			if ( !node ) {
				return $node.text();
			} else if ( node.tagName.toLowerCase() === 'img' ) {
				return $node.attr( 'alt' ) || ''; // handle undefined alt
			} else {
				return $.map( $.makeArray( node.childNodes ), function( elem ) {
					// 1 is for document.ELEMENT_NODE (the constant is undefined on old browsers)
					if ( elem.nodeType === 1 ) {
						return getElementSortKey( elem );
					} else {
						return $.text( elem );
					}
				} ).join( '' );
			}
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

		while ( i < l ) {
			if ( rows[rowIndex] && rows[rowIndex].cells[cellIndex] ) {
				nodeValue = $.trim( getElementSortKey( rows[rowIndex].cells[cellIndex] ) );
			} else {
				nodeValue = '';
			}

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
				sortType = $headers.eq( i ).data( 'sortType' );
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
				cols.push( parsers[j].format( getElementSortKey( $row[0].cells[j] ), table, $row[0].cells[j] ) );
			}

			cols.push( cache.normalized.length ); // add position for rowCache
			cache.normalized.push( cols );
			cols = null;
		}

		return cache;
	}

	function appendToTable( table, cache ) {
		var i, pos, l, j,
			row = cache.row,
			normalized = cache.normalized,
			totalRows = normalized.length,
			checkCell = ( normalized[0].length - 1 ),
			fragment = document.createDocumentFragment();

		for ( i = 0; i < totalRows; i++ ) {
			pos = normalized[i][checkCell];

			l = row[pos].length;

			for ( j = 0; j < l; j++ ) {
				fragment.appendChild( row[pos][j] );
			}

		}
		table.tBodies[0].appendChild( fragment );

		$( table ).trigger( 'sortEnd.tablesorter' );
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
		var $thead, $tfoot, i, len,
			$rows = $table.find( '> tbody > tr' );
		if ( !$table.get(0).tHead ) {
			$thead = $( '<thead>' );
			$rows.each( function () {
				if ( $(this).children( 'td' ).length ) {
					// This row contains a <td>, so it's not a header row
					// Stop here
					return false;
				}
				$thead.append( this );
			} );
			$table.find(' > tbody:first').before( $thead );
		}
		if ( !$table.get(0).tFoot ) {
			$tfoot = $( '<tfoot>' );
			len = $rows.length;
			for ( i = len - 1; i >= 0; i-- ) {
				if ( $( $rows[i] ).children( 'td' ).length ){
					break;
				}
				$tfoot.prepend( $( $rows[i] ));
			}
			$table.append( $tfoot );
		}
	}

	function buildHeaders( table, msg ) {
		var maxSeen = 0,
			colspanOffset = 0,
			columns,
			i,
			$tableHeaders = $( [] ),
			$tableRows = $( 'thead:eq(0) > tr', table );
		if ( $tableRows.length <= 1 ) {
			$tableHeaders = $tableRows.children( 'th' );
		} else {
			var rowspan,
				colspan,
				headerCount,
				longestTR,
				matrixRowIndex,
				matrixColumnIndex,
				exploded = [];

			// Loop through all the dom cells of the thead
			$tableRows.each( function ( rowIndex, row ) {
				$.each( row.cells, function( columnIndex, cell ) {
					rowspan = Number( cell.rowSpan );
					colspan = Number( cell.colSpan );

					// Skip the spots in the exploded matrix that are already filled
					while ( exploded[rowIndex] && exploded[rowIndex][columnIndex] !== undefined ) {
						++columnIndex;
					}

					// Find the actual dimensions of the thead, by placing each cell
					// in the exploded matrix rowspan times colspan times, with the proper offsets
					for ( matrixColumnIndex = columnIndex; matrixColumnIndex < columnIndex + colspan; ++matrixColumnIndex ) {
						for ( matrixRowIndex = rowIndex; matrixRowIndex < rowIndex + rowspan; ++matrixRowIndex ) {
							if ( !exploded[matrixRowIndex] ) {
								exploded[matrixRowIndex] = [];
							}
							exploded[matrixRowIndex][matrixColumnIndex] = cell;
						}
					}
				} );
			} );
			// We want to find the row that has the most columns (ignoring colspan)
			$.each( exploded, function ( index, cellArray ) {
				headerCount = $.unique( $(cellArray) ).length;
				if ( headerCount >= maxSeen ) {
					maxSeen = headerCount;
					longestTR = index;
				}
			} );
			// We cannot use $.unique() here because it sorts into dom order, which is undesirable
			$tableHeaders = $( uniqueElements( exploded[longestTR] ) );
		}

		// as each header can span over multiple columns (using colspan=N),
		// we have to bidirectionally map headers to their columns and columns to their headers
		table.headerToColumns = [];
		table.columnToHeader = [];

		$tableHeaders.each( function ( headerIndex ) {
			columns = [];
			for ( i = 0; i < this.colSpan; i++ ) {
				table.columnToHeader[ colspanOffset + i ] = headerIndex;
				columns.push( colspanOffset + i );
			}

			table.headerToColumns[ headerIndex ] = columns;
			colspanOffset += this.colSpan;

			this.headerIndex = headerIndex;
			this.order = 0;
			this.count = 0;

			if ( $( this ).hasClass( table.config.unsortableClass ) ) {
				this.sortDisabled = true;
			}

			if ( !this.sortDisabled ) {
				$( this )
					.addClass( table.config.cssHeader )
					.prop( 'tabIndex', 0 )
					.attr( {
						role: 'columnheader button',
						title: msg[1]
					} );
			}

			// add cell to headerList
			table.config.headerList[headerIndex] = this;
		} );

		return $tableHeaders;

	}

	/**
	 * Sets the sort count of the columns that are not affected by the sorting to have them sorted
	 * in default (ascending) order when their header cell is clicked the next time.
	 *
	 * @param {jQuery} $headers
	 * @param {Number[][]} sortList
	 * @param {Number[][]} headerToColumns
	 */
	function setHeadersOrder( $headers, sortList, headerToColumns ) {
		// Loop through all headers to retrieve the indices of the columns the header spans across:
		$.each( headerToColumns, function( headerIndex, columns ) {

			$.each( columns, function( i, columnIndex ) {
				var header = $headers[headerIndex];

				if ( !isValueInArray( columnIndex, sortList ) ) {
					// Column shall not be sorted: Reset header count and order.
					header.order = 0;
					header.count = 0;
				} else {
					// Column shall be sorted: Apply designated count and order.
					$.each( sortList, function( j, sortColumn ) {
						if ( sortColumn[0] === i ) {
							header.order = sortColumn[1];
							header.count = sortColumn[1] + 1;
							return false;
						}
					} );
				}
			} );

		} );
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

	function uniqueElements( array ) {
		var uniques = [];
		$.each( array, function( index, elem ) {
			if ( elem !== undefined && $.inArray( elem, uniques ) === -1 ) {
				uniques.push( elem );
			}
		} );
		return uniques;
	}

	function setHeadersCss( table, $headers, list, css, msg, columnToHeader ) {
		// Remove all header information and reset titles to default message
		$headers.removeClass( css[0] ).removeClass( css[1] ).attr( 'title', msg[1] );

		for ( var i = 0; i < list.length; i++ ) {
			$headers.eq( columnToHeader[ list[i][0] ] )
				.addClass( css[ list[i][1] ] )
				.attr( 'title', msg[ list[i][1] ] );
		}
	}

	function sortText( a, b ) {
		return ( (a < b) ? -1 : ((a > b) ? 1 : 0) );
	}

	function sortTextDesc( a, b ) {
		return ( (b < a) ? -1 : ((b > a) ? 1 : 0) );
	}

	function multisort( table, sortList, cache ) {
		var i,
			sortFn = [],
			len = sortList.length;
		for ( i = 0; i < len; i++ ) {
			sortFn[i] = ( sortList[i][1] ) ? sortTextDesc : sortText;
		}
		cache.normalized.sort( function ( array1, array2 ) {
			var i, col, ret;
			for ( i = 0; i < len; i++ ) {
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
		var ascii, localised, i, digitClass,
			digits = '0123456789,.'.split( '' ),
			separatorTransformTable = mw.config.get( 'wgSeparatorTransformTable' ),
			digitTransformTable = mw.config.get( 'wgDigitTransformTable' );

		if ( separatorTransformTable === null || ( separatorTransformTable[0] === '' && digitTransformTable[2] === '' ) ) {
			ts.transformTable = false;
		} else {
			ts.transformTable = {};

			// Unpack the transform table
			ascii = separatorTransformTable[0].split( '\t' ).concat( digitTransformTable[0].split( '\t' ) );
			localised = separatorTransformTable[1].split( '\t' ).concat( digitTransformTable[1].split( '\t' ) );

			// Construct regex for number identification
			for ( i = 0; i < ascii.length; i++ ) {
				ts.transformTable[localised[i]] = ascii[i];
				digits.push( $.escapeRE( localised[i] ) );
			}
		}
		digitClass = '[' + digits.join( '', digits ) + ']';

		// We allow a trailing percent sign, which we just strip. This works fine
		// if percents and regular numbers aren't being mixed.
		ts.numberRegex = new RegExp('^(' + '[-+\u2212]?[0-9][0-9,]*(\\.[0-9,]*)?(E[-+\u2212]?[0-9][0-9,]*)?' + // Fortran-style scientific
		'|' + '[-+\u2212]?' + digitClass + '+[\\s\\xa0]*%?' + // Generic localised
		')$', 'i');
	}

	function buildDateTable() {
		var i, name,
			regex = [];

		ts.monthNames = {};

		for ( i = 0; i < 12; i++ ) {
			name = mw.language.months.names[i].toLowerCase();
			ts.monthNames[name] = i + 1;
			regex.push( $.escapeRE( name ) );
			name = mw.language.months.genitive[i].toLowerCase();
			ts.monthNames[name] = i + 1;
			regex.push( $.escapeRE( name ) );
			name = mw.language.months.abbrev[i].toLowerCase().replace( '.', '' );
			ts.monthNames[name] = i + 1;
			regex.push( $.escapeRE( name ) );
		}

		// Build piped string
		regex = regex.join( '|' );

		// Build RegEx
		// Any date formated with . , ' - or /
		ts.dateRegex[0] = new RegExp( /^\s*(\d{1,2})[\,\.\-\/'\s]{1,2}(\d{1,2})[\,\.\-\/'\s]{1,2}(\d{2,4})\s*?/i);

		// Written Month name, dmy
		ts.dateRegex[1] = new RegExp( '^\\s*(\\d{1,2})[\\,\\.\\-\\/\'\\s]+(' + regex + ')' + '[\\,\\.\\-\\/\'\\s]+(\\d{2,4})\\s*$', 'i' );

		// Written Month name, mdy
		ts.dateRegex[2] = new RegExp( '^\\s*(' + regex + ')' + '[\\,\\.\\-\\/\'\\s]+(\\d{1,2})[\\,\\.\\-\\/\'\\s]+(\\d{2,4})\\s*$', 'i' );

	}

	/**
	 * Replace all rowspanned cells in the body with clones in each row, so sorting
	 * need not worry about them.
	 *
	 * @param $table jQuery object for a <table>
	 */
	function explodeRowspans( $table ) {
		var spanningRealCellIndex, rowSpan, colSpan,
			cell, i, $tds, $clone, $nextRows,
			rowspanCells = $table.find( '> tbody > tr > [rowspan]' ).get();

		// Short circuit
		if ( !rowspanCells.length ) {
			return;
		}

		// First, we need to make a property like cellIndex but taking into
		// account colspans. We also cache the rowIndex to avoid having to take
		// cell.parentNode.rowIndex in the sorting function below.
		$table.find( '> tbody > tr' ).each( function () {
			var i,
				col = 0,
				l = this.cells.length;
			for ( i = 0; i < l; i++ ) {
				this.cells[i].realCellIndex = col;
				this.cells[i].realRowIndex = this.rowIndex;
				col += this.cells[i].colSpan;
			}
		} );

		// Split multi row cells into multiple cells with the same content.
		// Sort by column then row index to avoid problems with odd table structures.
		// Re-sort whenever a rowspanned cell's realCellIndex is changed, because it
		// might change the sort order.
		function resortCells() {
			rowspanCells = rowspanCells.sort( function ( a, b ) {
				var ret = a.realCellIndex - b.realCellIndex;
				if ( !ret ) {
					ret = a.realRowIndex - b.realRowIndex;
				}
				return ret;
			} );
			$.each( rowspanCells, function () {
				this.needResort = false;
			} );
		}
		resortCells();

		function filterfunc() {
			return this.realCellIndex >= spanningRealCellIndex;
		}

		function fixTdCellIndex() {
			this.realCellIndex += colSpan;
			if ( this.rowSpan > 1 ) {
				this.needResort = true;
			}
		}

		while ( rowspanCells.length ) {
			if ( rowspanCells[0].needResort ) {
				resortCells();
			}

			cell = rowspanCells.shift();
			rowSpan = cell.rowSpan;
			colSpan = cell.colSpan;
			spanningRealCellIndex = cell.realCellIndex;
			cell.rowSpan = 1;
			$nextRows = $( cell ).parent().nextAll();
			for ( i = 0; i < rowSpan - 1; i++ ) {
				$tds = $( $nextRows[i].cells ).filter( filterfunc );
				$clone = $( cell ).clone();
				$clone[0].realCellIndex = spanningRealCellIndex;
				if ( $tds.length ) {
					$tds.each( fixTdCellIndex );
					$tds.first().before( $clone );
				} else {
					$nextRows.eq( i ).append( $clone );
				}
			}
		}
	}

	function buildCollationTable() {
		ts.collationTable = mw.config.get( 'tableSorterCollation' );
		ts.collationRegex = null;
		if ( ts.collationTable ) {
			var key,
				keys = [];

			// Build array of key names
			for ( key in ts.collationTable ) {
				// Check hasOwn to be safe
				if ( ts.collationTable.hasOwnProperty(key) ) {
					keys.push(key);
				}
			}
			if ( keys.length ) {
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

	/**
	 * Converts sort objects [ { Integer: String }, ... ] to the internally used nested array
	 * structure [ [ Integer , Integer ], ... ]
	 *
	 * @param sortObjects {Array} List of sort objects.
	 * @return {Array} List of internal sort definitions.
	 */

	function convertSortList( sortObjects ) {
		var sortList = [];
		$.each( sortObjects, function( i, sortObject ) {
			$.each ( sortObject, function( columnIndex, order ) {
				var orderIndex = ( order === 'desc' ) ? 1 : 0;
				sortList.push( [parseInt( columnIndex, 10 ), orderIndex] );
			} );
		} );
		return sortList;
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
				unsortableClass: 'unsortable',
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
					var $headers, cache, config, sortCSS, sortMsg,
						$table = $( table ),
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
					$table.addClass( 'jquery-tablesorter' );

					// FIXME config should probably not be stored in the plain table node
					// New config object.
					table.config = {};

					// Merge and extend.
					config = $.extend( table.config, $.tablesorter.defaultOptions, settings );

					// Save the settings where they read
					$.data( table, 'tablesorter', { config: config } );

					// Get the CSS class names, could be done else where.
					sortCSS = [ config.cssDesc, config.cssAsc ];
					sortMsg = [ mw.msg( 'sort-descending' ), mw.msg( 'sort-ascending' ) ];

					// Build headers
					$headers = buildHeaders( table, sortMsg );

					// Grab and process locale settings.
					buildTransformTable();
					buildDateTable();

					// Precaching regexps can bring 10 fold
					// performance improvements in some browsers.
					cacheRegexs();

					function setupForFirstSort() {
						firstTime = false;

						// Defer buildCollationTable to first sort. As user and site scripts
						// may customize tableSorterCollation but load after $.ready(), other
						// scripts may call .tablesorter() before they have done the
						// tableSorterCollation customizations.
						buildCollationTable();

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

					// Apply event handling to headers
					// this is too big, perhaps break it out?
					$headers.not( '.' + table.config.unsortableClass ).on( 'keypress click', function ( e ) {
						if ( e.type === 'click' && e.target.nodeName.toLowerCase() === 'a' ) {
							// The user clicked on a link inside a table header.
							// Do nothing and let the default link click action continue.
							return true;
						}

						if ( e.type === 'keypress' && e.which !== 13 ) {
							// Only handle keypresses on the "Enter" key.
							return true;
						}

						if ( firstTime ) {
							setupForFirstSort();
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
							// Get current column sort order
							this.order = this.count % 2;
							this.count++;

							var cell, columns, newSortList, i;

							cell = this;
							// Get current column index
							columns = table.headerToColumns[ this.headerIndex ];
							newSortList = $.map( columns, function ( c ) {
								// jQuery "helpfully" flattens the arrays...
								return [[c, cell.order]];
							});
							// Index of first column belonging to this header
							i = columns[0];

							if ( !e[config.sortMultiSortKey] ) {
								// User only wants to sort on one column set
								// Flush the sort list and add new columns
								config.sortList = newSortList;
							} else {
								// Multi column sorting
								// It is not possible for one column to belong to multiple headers,
								// so this is okay - we don't need to check for every value in the columns array
								if ( isValueInArray( i, config.sortList ) ) {
									// The user has clicked on an already sorted column.
									// Reverse the sorting direction for all tables.
									for ( var j = 0; j < config.sortList.length; j++ ) {
										var s = config.sortList[j],
											o = config.headerList[s[0]];
										if ( isValueInArray( s[0], newSortList ) ) {
											o.count = s[1];
											o.count++;
											s[1] = o.count % 2;
										}
									}
								} else {
									// Add columns to sort list array
									config.sortList = config.sortList.concat( newSortList );
								}
							}

							// Reset order/counts of cells not affected by sorting
							setHeadersOrder( $headers, config.sortList, table.headerToColumns );

							// Set CSS for headers
							setHeadersCss( $table[0], $headers, config.sortList, sortCSS, sortMsg, table.columnToHeader );
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

					/**
					 * Sorts the table. If no sorting is specified by passing a list of sort
					 * objects, the table is sorted according to the initial sorting order.
					 * Passing an empty array will reset sorting (basically just reset the headers
					 * making the table appear unsorted).
					 *
					 * @param sortList {Array} (optional) List of sort objects.
					 */
					$table.data( 'tablesorter' ).sort = function ( sortList ) {

						if ( firstTime ) {
							setupForFirstSort();
						}

						if ( sortList === undefined ) {
							sortList = config.sortList;
						} else if ( sortList.length > 0 ) {
							sortList = convertSortList( sortList );
						}

						// Set each column's sort count to be able to determine the correct sort
						// order when clicking on a header cell the next time
						setHeadersOrder( $headers, sortList, table.headerToColumns );

						// re-build the cache for the tbody cells
						cache = buildCache( table );

						// set css for headers
						setHeadersCss( table, $headers, sortList, sortCSS, sortMsg, table.columnToHeader );

						// sort the table and append it to the dom
						appendToTable( table, multisort( table, sortList, cache ) );
					};

					// sort initially
					if ( config.sortList.length > 0 ) {
						setupForFirstSort();
						config.sortList = convertSortList( config.sortList );
						$table.data( 'tablesorter' ).sort();
					}

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
				var out, c, p, i;
				if ( ts.transformTable !== false ) {
					out = '';
					for ( p = 0; p < s.length; p++ ) {
						c = s.charAt(p);
						if ( c in ts.transformTable ) {
							out += ts.transformTable[c];
						} else {
							out += c;
						}
					}
					s = out;
				}
				i = parseFloat( s.replace( /[, ]/g, '' ).replace( '\u2212', '-' ) );
				return isNaN( i ) ? 0 : i;
			},

			formatFloat: function ( s ) {
				var i = parseFloat(s);
				return isNaN( i ) ? 0 : i;
			},

			formatInt: function ( s ) {
				var i = parseInt( s, 10 );
				return isNaN( i ) ? 0 : i;
			},

			clearTableBody: function ( table ) {
				$( table.tBodies[0] ).empty();
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
		is: function () {
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
		format: function ( s ) {
			var match;
			s = $.trim( s.toLowerCase() );

			if ( ( match = s.match( ts.dateRegex[0] ) ) !== null ) {
				if ( mw.config.get( 'wgDefaultDateFormat' ) === 'mdy' || mw.config.get( 'wgContentLanguage' ) === 'en' ) {
					s = [ match[3], match[1], match[2] ];
				} else if ( mw.config.get( 'wgDefaultDateFormat' ) === 'dmy' ) {
					s = [ match[3], match[2], match[1] ];
				} else {
					// If we get here, we don't know which order the dd-dd-dddd
					// date is in. So return something not entirely invalid.
					return '99999999';
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
		is: function ( s ) {
			return $.tablesorter.numberRegex.test( $.trim( s ));
		},
		format: function ( s ) {
			return $.tablesorter.formatDigit(s);
		},
		type: 'numeric'
	} );

}( jQuery, mediaWiki ) );
