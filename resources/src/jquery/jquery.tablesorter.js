/*!
 * TableSorter for MediaWiki
 *
 * Written 2011 Leo Koppelkamm
 * Based on tablesorter.com plugin, written (c) 2007 Christian Bach.
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Depends on mw.config (wgDigitTransformTable, wgDefaultDateFormat, wgPageContentLanguage)
 * and mw.language.months.
 *
 * Uses 'tableSorterCollation' in mw.config (if available)
 *
 * Create a sortable table with multi-column sorting capabilities
 *
 *      // Create a simple tablesorter interface
 *      $( 'table' ).tablesorter();
 *
 *      // Create a tablesorter interface, initially sorting on the first and second column
 *      $( 'table' ).tablesorter( { sortList: [ { 0: 'desc' }, { 1: 'asc' } ] } );
 *
 * @param {string} [cssHeader="header"] A string of the class name to be appended to sortable
 *         tr elements in the thead of the table.
 *
 * @param {string} [cssAsc="headerSortUp"] A string of the class name to be appended to
 *         sortable tr elements in the thead on a ascending sort.
 *
 * @param {string} [cssDesc="headerSortDown"] A string of the class name to be appended to
 *         sortable tr elements in the thead on a descending sort.
 *
 * @param {string} [sortMultisortKey="shiftKey"] A string of the multi-column sort key.
 *
 * @param {boolean} [cancelSelection=true] Boolean flag indicating iftablesorter should cancel
 *         selection of the table headers text.
 *
 * @param {Array} [sortList] An array containing objects specifying sorting. By passing more
 *         than one object, multi-sorting will be applied. Object structure:
 *         { <Integer column index>: <String 'asc' or 'desc'> }
 *
 * @event sortEnd.tablesorter: Triggered as soon as any sorting has been applied.
 *
 * @author Christian Bach/christian.bach@polyester.se
 */
( function ( $, mw ) {
	var ts,
		parsers = [];

	/* Parser utility functions */

	function getParserById( name ) {
		var i;
		for ( i = 0; i < parsers.length; i++ ) {
			if ( parsers[ i ].id.toLowerCase() === name.toLowerCase() ) {
				return parsers[ i ];
			}
		}
		return false;
	}

	function getElementSortKey( node ) {
		var $node = $( node ),
			// Use data-sort-value attribute.
			// Use data() instead of attr() so that live value changes
			// are processed as well (T40152).
			data = $node.data( 'sortValue' );

		if ( data !== null && data !== undefined ) {
			// Cast any numbers or other stuff to a string, methods
			// like charAt, toLowerCase and split are expected.
			return String( data );
		}
		if ( !node ) {
			return $node.text();
		}
		if ( node.tagName.toLowerCase() === 'img' ) {
			return $node.attr( 'alt' ) || ''; // handle undefined alt
		}
		return $.makeArray( node.childNodes ).map( function ( elem ) {
			if ( elem.nodeType === Node.ELEMENT_NODE ) {
				return getElementSortKey( elem );
			}
			return $.text( elem );
		} ).join( '' );
	}

	function detectParserForColumn( table, rows, column ) {
		var l = parsers.length,
			config = $( table ).data( 'tablesorter' ).config,
			cellIndex,
			nodeValue,
			nextRow = false,
			// Start with 1 because 0 is the fallback parser
			i = 1,
			lastRowIndex = -1,
			rowIndex = 0,
			concurrent = 0,
			empty = 0,
			needed = ( rows.length > 4 ) ? 5 : rows.length;

		while ( i < l ) {
			// if this is a child row, continue to the next row (as buildCache())
			if ( rows[ rowIndex ] && !$( rows[ rowIndex ] ).hasClass( config.cssChildRow ) ) {
				if ( rowIndex !== lastRowIndex ) {
					lastRowIndex = rowIndex;
					cellIndex = $( rows[ rowIndex ] ).data( 'columnToCell' )[ column ];
					nodeValue = getElementSortKey( rows[ rowIndex ].cells[ cellIndex ] ).trim();
				}
			} else {
				nodeValue = '';
			}

			if ( nodeValue !== '' ) {
				if ( parsers[ i ].is( nodeValue, table ) ) {
					concurrent++;
					nextRow = true;
					if ( concurrent >= needed ) {
						// Confirmed the parser for multiple cells, let's return it
						return parsers[ i ];
					}
				} else if ( parsers[ i ].id.match( /isoDate/ ) && /^\D*(\d{1,4}) ?(\[.+\])?$/.test( nodeValue ) ) {
					// For 1-4 digits and maybe reference(s) parser "isoDate" or "number" is possible, check next row
					empty++;
					nextRow = true;
				} else {
					// Check next parser, reset rows
					i++;
					rowIndex = 0;
					concurrent = 0;
					empty = 0;
					nextRow = false;
				}
			} else {
				// Empty cell
				empty++;
				nextRow = true;
			}

			if ( nextRow ) {
				nextRow = false;
				rowIndex++;
				if ( rowIndex >= rows.length ) {
					if ( concurrent > 0 && concurrent >= rows.length - empty ) {
						// Confirmed the parser for all filled cells
						return parsers[ i ];
					}
					// Check next parser, reset rows
					i++;
					rowIndex = 0;
					concurrent = 0;
					empty = 0;
				}
			}
		}

		// 0 is always the generic parser (text)
		return parsers[ 0 ];
	}

	function buildParserCache( table, $headers ) {
		var sortType, len, j, parser,
			rows = table.tBodies[ 0 ].rows,
			config = $( table ).data( 'tablesorter' ).config,
			parsers = [];

		if ( rows[ 0 ] ) {
			len = config.columns;
			for ( j = 0; j < len; j++ ) {
				parser = false;
				sortType = $headers.eq( config.columnToHeader[ j ] ).data( 'sortType' );
				if ( sortType !== undefined ) {
					parser = getParserById( sortType );
				}

				if ( parser === false ) {
					parser = detectParserForColumn( table, rows, j );
				}

				parsers.push( parser );
			}
		}
		return parsers;
	}

	/* Other utility functions */

	function buildCache( table ) {
		var i, j, $row, cols,
			totalRows = ( table.tBodies[ 0 ] && table.tBodies[ 0 ].rows.length ) || 0,
			config = $( table ).data( 'tablesorter' ).config,
			parsers = config.parsers,
			len = parsers.length,
			cellIndex,
			cache = {
				row: [],
				normalized: []
			};

		for ( i = 0; i < totalRows; i++ ) {

			// Add the table data to main data array
			$row = $( table.tBodies[ 0 ].rows[ i ] );
			cols = [];

			// if this is a child row, add it to the last row's children and
			// continue to the next row
			if ( $row.hasClass( config.cssChildRow ) ) {
				cache.row[ cache.row.length - 1 ] = cache.row[ cache.row.length - 1 ].add( $row );
				// go to the next for loop
				continue;
			}

			cache.row.push( $row );

			for ( j = 0; j < len; j++ ) {
				cellIndex = $row.data( 'columnToCell' )[ j ];
				cols.push( parsers[ j ].format( getElementSortKey( $row[ 0 ].cells[ cellIndex ] ) ) );
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
			checkCell = ( normalized[ 0 ].length - 1 ),
			fragment = document.createDocumentFragment();

		for ( i = 0; i < totalRows; i++ ) {
			pos = normalized[ i ][ checkCell ];

			l = row[ pos ].length;
			for ( j = 0; j < l; j++ ) {
				fragment.appendChild( row[ pos ][ j ] );
			}

		}
		table.tBodies[ 0 ].appendChild( fragment );

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
	 *
	 * @param {jQuery} $table object for a <table>
	 */
	function emulateTHeadAndFoot( $table ) {
		var $thead, $tfoot, i, len,
			$rows = $table.find( '> tbody > tr' );
		if ( !$table.get( 0 ).tHead ) {
			$thead = $( '<thead>' );
			$rows.each( function () {
				if ( $( this ).children( 'td' ).length ) {
					// This row contains a <td>, so it's not a header row
					// Stop here
					return false;
				}
				$thead.append( this );
			} );
			$table.find( ' > tbody:first' ).before( $thead );
		}
		if ( !$table.get( 0 ).tFoot ) {
			$tfoot = $( '<tfoot>' );
			len = $rows.length;
			for ( i = len - 1; i >= 0; i-- ) {
				if ( $( $rows[ i ] ).children( 'td' ).length ) {
					break;
				}
				$tfoot.prepend( $( $rows[ i ] ) );
			}
			$table.append( $tfoot );
		}
	}

	function uniqueElements( array ) {
		var uniques = [];
		array.forEach( function ( elem ) {
			if ( elem !== undefined && uniques.indexOf( elem ) === -1 ) {
				uniques.push( elem );
			}
		} );
		return uniques;
	}

	function buildHeaders( table, msg ) {
		var config = $( table ).data( 'tablesorter' ).config,
			maxSeen = 0,
			colspanOffset = 0,
			columns,
			k,
			$cell,
			rowspan,
			colspan,
			headerCount,
			longestTR,
			headerIndex,
			exploded,
			$tableHeaders = $( [] ),
			$tableRows = $( 'thead:eq(0) > tr', table );

		if ( $tableRows.length <= 1 ) {
			$tableHeaders = $tableRows.children( 'th' );
		} else {
			exploded = [];

			// Loop through all the dom cells of the thead
			$tableRows.each( function ( rowIndex, row ) {
				$.each( row.cells, function ( columnIndex, cell ) {
					var matrixRowIndex,
						matrixColumnIndex;

					rowspan = Number( cell.rowSpan );
					colspan = Number( cell.colSpan );

					// Skip the spots in the exploded matrix that are already filled
					while ( exploded[ rowIndex ] && exploded[ rowIndex ][ columnIndex ] !== undefined ) {
						++columnIndex;
					}

					// Find the actual dimensions of the thead, by placing each cell
					// in the exploded matrix rowspan times colspan times, with the proper offsets
					for ( matrixColumnIndex = columnIndex; matrixColumnIndex < columnIndex + colspan; ++matrixColumnIndex ) {
						for ( matrixRowIndex = rowIndex; matrixRowIndex < rowIndex + rowspan; ++matrixRowIndex ) {
							if ( !exploded[ matrixRowIndex ] ) {
								exploded[ matrixRowIndex ] = [];
							}
							exploded[ matrixRowIndex ][ matrixColumnIndex ] = cell;
						}
					}
				} );
			} );
			// We want to find the row that has the most columns (ignoring colspan)
			exploded.forEach( function ( cellArray, index ) {
				headerCount = $( uniqueElements( cellArray ) ).filter( 'th' ).length;
				if ( headerCount >= maxSeen ) {
					maxSeen = headerCount;
					longestTR = index;
				}
			} );
			// We cannot use $.unique() here because it sorts into dom order, which is undesirable
			$tableHeaders = $( uniqueElements( exploded[ longestTR ] ) ).filter( 'th' );
		}

		// as each header can span over multiple columns (using colspan=N),
		// we have to bidirectionally map headers to their columns and columns to their headers
		config.columnToHeader = [];
		config.headerToColumns = [];
		config.headerList = [];
		headerIndex = 0;
		$tableHeaders.each( function () {
			$cell = $( this );
			columns = [];

			if ( !$cell.hasClass( config.unsortableClass ) ) {
				$cell
					.addClass( config.cssHeader )
					.prop( 'tabIndex', 0 )
					.attr( {
						role: 'columnheader button',
						title: msg[ 1 ]
					} );

				for ( k = 0; k < this.colSpan; k++ ) {
					config.columnToHeader[ colspanOffset + k ] = headerIndex;
					columns.push( colspanOffset + k );
				}

				config.headerToColumns[ headerIndex ] = columns;

				$cell.data( {
					headerIndex: headerIndex,
					order: 0,
					count: 0
				} );

				// add only sortable cells to headerList
				config.headerList[ headerIndex ] = this;
				headerIndex++;
			}

			colspanOffset += this.colSpan;
		} );

		// number of columns with extended colspan, inclusive unsortable
		// parsers[j], cache[][j], columnToHeader[j], columnToCell[j] have so many elements
		config.columns = colspanOffset;

		return $tableHeaders.not( '.' + config.unsortableClass );
	}

	function isValueInArray( v, a ) {
		var i;
		for ( i = 0; i < a.length; i++ ) {
			if ( a[ i ][ 0 ] === v ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Sets the sort count of the columns that are not affected by the sorting to have them sorted
	 * in default (ascending) order when their header cell is clicked the next time.
	 *
	 * @param {jQuery} $headers
	 * @param {Array} sortList 2D number array
	 * @param {Array} headerToColumns 2D number array
	 */
	function setHeadersOrder( $headers, sortList, headerToColumns ) {
		// Loop through all headers to retrieve the indices of the columns the header spans across:
		headerToColumns.forEach( function ( columns, headerIndex ) {

			columns.forEach( function ( columnIndex, i ) {
				var header = $headers[ headerIndex ],
					$header = $( header );

				if ( !isValueInArray( columnIndex, sortList ) ) {
					// Column shall not be sorted: Reset header count and order.
					$header.data( {
						order: 0,
						count: 0
					} );
				} else {
					// Column shall be sorted: Apply designated count and order.
					sortList.forEach( function ( sortColumn ) {
						if ( sortColumn[ 0 ] === i ) {
							$header.data( {
								order: sortColumn[ 1 ],
								count: sortColumn[ 1 ] + 1
							} );
							return false;
						}
					} );
				}
			} );

		} );
	}

	function setHeadersCss( table, $headers, list, css, msg, columnToHeader ) {
		var i, len;
		// Remove all header information and reset titles to default message
		$headers.removeClass( css[ 0 ] ).removeClass( css[ 1 ] ).attr( 'title', msg[ 1 ] );

		for ( i = 0, len = list.length; i < len; i++ ) {
			$headers
				.eq( columnToHeader[ list[ i ][ 0 ] ] )
				.addClass( css[ list[ i ][ 1 ] ] )
				.attr( 'title', msg[ list[ i ][ 1 ] ] );
		}
	}

	function sortText( a, b ) {
		return ( ( a < b ) ? -1 : ( ( a > b ) ? 1 : 0 ) );
	}

	function sortTextDesc( a, b ) {
		return ( ( b < a ) ? -1 : ( ( b > a ) ? 1 : 0 ) );
	}

	function multisort( table, sortList, cache ) {
		var i,
			sortFn = [];

		for ( i = 0; i < sortList.length; i++ ) {
			sortFn[ i ] = ( sortList[ i ][ 1 ] ) ? sortTextDesc : sortText;
		}
		cache.normalized.sort( function ( array1, array2 ) {
			var i, col, ret;
			for ( i = 0; i < sortList.length; i++ ) {
				col = sortList[ i ][ 0 ];
				ret = sortFn[ i ].call( this, array1[ col ], array2[ col ] );
				if ( ret !== 0 ) {
					return ret;
				}
			}
			// Fall back to index number column to ensure stable sort
			return sortText.call( this, array1[ array1.length - 1 ], array2[ array2.length - 1 ] );
		} );
		return cache;
	}

	function buildTransformTable() {
		var ascii, localised, i, digitClass,
			digits = '0123456789,.'.split( '' ),
			separatorTransformTable = mw.config.get( 'wgSeparatorTransformTable' ),
			digitTransformTable = mw.config.get( 'wgDigitTransformTable' );

		if ( separatorTransformTable === null || ( separatorTransformTable[ 0 ] === '' && digitTransformTable[ 2 ] === '' ) ) {
			ts.transformTable = false;
		} else {
			ts.transformTable = {};

			// Unpack the transform table
			ascii = separatorTransformTable[ 0 ].split( '\t' ).concat( digitTransformTable[ 0 ].split( '\t' ) );
			localised = separatorTransformTable[ 1 ].split( '\t' ).concat( digitTransformTable[ 1 ].split( '\t' ) );

			// Construct regexes for number identification
			for ( i = 0; i < ascii.length; i++ ) {
				ts.transformTable[ localised[ i ] ] = ascii[ i ];
				digits.push( mw.RegExp.escape( localised[ i ] ) );
			}
		}
		digitClass = '[' + digits.join( '', digits ) + ']';

		// We allow a trailing percent sign, which we just strip. This works fine
		// if percents and regular numbers aren't being mixed.
		ts.numberRegex = new RegExp(
			'^(' +
				'[-+\u2212]?[0-9][0-9,]*(\\.[0-9,]*)?(E[-+\u2212]?[0-9][0-9,]*)?' + // Fortran-style scientific
				'|' +
				'[-+\u2212]?' + digitClass + '+[\\s\\xa0]*%?' + // Generic localised
			')$',
			'i'
		);
	}

	function buildDateTable() {
		var i, name,
			regex = [];

		ts.monthNames = {};

		for ( i = 0; i < 12; i++ ) {
			name = mw.language.months.names[ i ].toLowerCase();
			ts.monthNames[ name ] = i + 1;
			regex.push( mw.RegExp.escape( name ) );
			name = mw.language.months.genitive[ i ].toLowerCase();
			ts.monthNames[ name ] = i + 1;
			regex.push( mw.RegExp.escape( name ) );
			name = mw.language.months.abbrev[ i ].toLowerCase().replace( '.', '' );
			ts.monthNames[ name ] = i + 1;
			regex.push( mw.RegExp.escape( name ) );
		}

		// Build piped string
		regex = regex.join( '|' );

		// Build RegEx
		// Any date formated with . , ' - or /
		ts.dateRegex[ 0 ] = new RegExp( /^\s*(\d{1,2})[,.\-/'\s]{1,2}(\d{1,2})[,.\-/'\s]{1,2}(\d{2,4})\s*?/i );

		// Written Month name, dmy
		ts.dateRegex[ 1 ] = new RegExp(
			'^\\s*(\\d{1,2})[\\,\\.\\-\\/\'\\s]+(' +
				regex +
			')' +
			'[\\,\\.\\-\\/\'\\s]+(\\d{2,4})\\s*$',
			'i'
		);

		// Written Month name, mdy
		ts.dateRegex[ 2 ] = new RegExp(
			'^\\s*(' + regex + ')' +
			'[\\,\\.\\-\\/\'\\s]+(\\d{1,2})[\\,\\.\\-\\/\'\\s]+(\\d{2,4})\\s*$',
			'i'
		);

	}

	/**
	 * Replace all rowspanned cells in the body with clones in each row, so sorting
	 * need not worry about them.
	 *
	 * @param {jQuery} $table jQuery object for a <table>
	 */
	function explodeRowspans( $table ) {
		var spanningRealCellIndex, rowSpan, colSpan,
			cell, cellData, i, $tds, $clone, $nextRows,
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
				len = this.cells.length;
			for ( i = 0; i < len; i++ ) {
				$( this.cells[ i ] ).data( 'tablesorter', {
					realCellIndex: col,
					realRowIndex: this.rowIndex
				} );
				col += this.cells[ i ].colSpan;
			}
		} );

		// Split multi row cells into multiple cells with the same content.
		// Sort by column then row index to avoid problems with odd table structures.
		// Re-sort whenever a rowspanned cell's realCellIndex is changed, because it
		// might change the sort order.
		function resortCells() {
			var cellAData,
				cellBData,
				ret;
			rowspanCells = rowspanCells.sort( function ( a, b ) {
				cellAData = $.data( a, 'tablesorter' );
				cellBData = $.data( b, 'tablesorter' );
				ret = cellAData.realCellIndex - cellBData.realCellIndex;
				if ( !ret ) {
					ret = cellAData.realRowIndex - cellBData.realRowIndex;
				}
				return ret;
			} );
			rowspanCells.forEach( function ( cell ) {
				$.data( cell, 'tablesorter' ).needResort = false;
			} );
		}
		resortCells();

		function filterfunc() {
			return $.data( this, 'tablesorter' ).realCellIndex >= spanningRealCellIndex;
		}

		function fixTdCellIndex() {
			$.data( this, 'tablesorter' ).realCellIndex += colSpan;
			if ( this.rowSpan > 1 ) {
				$.data( this, 'tablesorter' ).needResort = true;
			}
		}

		while ( rowspanCells.length ) {
			if ( $.data( rowspanCells[ 0 ], 'tablesorter' ).needResort ) {
				resortCells();
			}

			cell = rowspanCells.shift();
			cellData = $.data( cell, 'tablesorter' );
			rowSpan = cell.rowSpan;
			colSpan = cell.colSpan;
			spanningRealCellIndex = cellData.realCellIndex;
			cell.rowSpan = 1;
			$nextRows = $( cell ).parent().nextAll();
			for ( i = 0; i < rowSpan - 1; i++ ) {
				$tds = $( $nextRows[ i ].cells ).filter( filterfunc );
				$clone = $( cell ).clone();
				$clone.data( 'tablesorter', {
					realCellIndex: spanningRealCellIndex,
					realRowIndex: cellData.realRowIndex + i,
					needResort: true
				} );
				if ( $tds.length ) {
					$tds.each( fixTdCellIndex );
					$tds.first().before( $clone );
				} else {
					$nextRows.eq( i ).append( $clone );
				}
			}
		}
	}

	/**
	 * Build index to handle colspanned cells in the body.
	 * Set the cell index for each column in an array,
	 * so that colspaned cells set multiple in this array.
	 * columnToCell[collumnIndex] point at the real cell in this row.
	 *
	 * @param {jQuery} $table object for a <table>
	 */
	function manageColspans( $table ) {
		var i, j, k, $row,
			$rows = $table.find( '> tbody > tr' ),
			totalRows = $rows.length || 0,
			config = $table.data( 'tablesorter' ).config,
			columns = config.columns,
			columnToCell, cellsInRow, index;

		for ( i = 0; i < totalRows; i++ ) {

			$row = $rows.eq( i );
			// if this is a child row, continue to the next row (as buildCache())
			if ( $row.hasClass( config.cssChildRow ) ) {
				// go to the next for loop
				continue;
			}

			columnToCell = [];
			cellsInRow = ( $row[ 0 ].cells.length ) || 0; // all cells in this row
			index = 0; // real cell index in this row
			for ( j = 0; j < columns; index++ ) {
				if ( index === cellsInRow ) {
					// Row with cells less than columns: add empty cell
					$row.append( '<td>' );
					cellsInRow++;
				}
				for ( k = 0; k < $row[ 0 ].cells[ index ].colSpan; k++ ) {
					columnToCell[ j++ ] = index;
				}
			}
			// Store it in $row
			$row.data( 'columnToCell', columnToCell );
		}
	}

	function buildCollationTable() {
		var key, keys = [];
		ts.collationTable = mw.config.get( 'tableSorterCollation' );
		ts.collationRegex = null;
		if ( ts.collationTable ) {
			// Build array of key names
			for ( key in ts.collationTable ) {
				// Check hasOwn to be safe
				if ( ts.collationTable.hasOwnProperty( key ) ) {
					keys.push( mw.RegExp.escape( key ) );
				}
			}
			if ( keys.length ) {
				ts.collationRegex = new RegExp( keys.join( '|' ), 'ig' );
			}
		}
	}

	function cacheRegexs() {
		if ( ts.rgx ) {
			return;
		}
		ts.rgx = {
			IPAddress: [
				new RegExp( /^\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}$/ )
			],
			currency: [
				new RegExp( /(^[£$€¥]|[£$€¥]$)/ ),
				new RegExp( /[£$€¥]/g )
			],
			url: [
				new RegExp( /^(https?|ftp|file):\/\/$/ ),
				new RegExp( /(https?|ftp|file):\/\// )
			],
			isoDate: [
				new RegExp( /^[^-\d]*(-?\d{1,4})-(0\d|1[0-2])(-([0-3]\d))?([T\s]([01]\d|2[0-4]):?(([0-5]\d):?(([0-5]\d|60)([.,]\d{1,3})?)?)?([zZ]|([-+])([01]\d|2[0-3]):?([0-5]\d)?)?)?/ ),
				new RegExp( /^[^-\d]*(-?\d{1,4})-?(\d\d)?(-?(\d\d))?([T\s](\d\d):?((\d\d)?:?((\d\d)?([.,]\d{1,3})?)?)?([zZ]|([-+])(\d\d):?(\d\d)?)?)?/ )
			],
			usLongDate: [
				new RegExp( /^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/ )
			],
			time: [
				new RegExp( /^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(am|pm)))$/ )
			]
		};
	}

	/**
	 * Converts sort objects [ { Integer: String }, ... ] to the internally used nested array
	 * structure [ [ Integer, Integer ], ... ]
	 *
	 * @param {Array} sortObjects List of sort objects.
	 * @return {Array} List of internal sort definitions.
	 */
	function convertSortList( sortObjects ) {
		var sortList = [];
		sortObjects.forEach( function ( sortObject ) {
			$.each( sortObject, function ( columnIndex, order ) {
				var orderIndex = ( order === 'desc' ) ? 1 : 0;
				sortList.push( [ parseInt( columnIndex, 10 ), orderIndex ] );
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
			sortMultiSortKey: 'shiftKey',
			unsortableClass: 'unsortable',
			parsers: [],
			cancelSelection: true,
			sortList: [],
			headerList: [],
			headerToColumns: [],
			columnToHeader: [],
			columns: 0
		},

		dateRegex: [],
		monthNames: {},

		/**
		 * @param {jQuery} $tables
		 * @param {Object} [settings]
		 * @return {jQuery}
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

				// Merge and extend
				config = $.extend( {}, $.tablesorter.defaultOptions, settings );

				// Save the settings where they read
				$.data( table, 'tablesorter', { config: config } );

				// Get the CSS class names, could be done elsewhere
				sortCSS = [ config.cssAsc, config.cssDesc ];
				// Messages tell the the user what the *next* state will be
				// so are in reverse order to the CSS classes.
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
					var $tfoot, $sortbottoms;

					firstTime = false;

					// Defer buildCollationTable to first sort. As user and site scripts
					// may customize tableSorterCollation but load after $.ready(), other
					// scripts may call .tablesorter() before they have done the
					// tableSorterCollation customizations.
					buildCollationTable();

					// Legacy fix of .sortbottoms
					// Wrap them inside a tfoot (because that's what they actually want to be)
					// and put the <tfoot> at the end of the <table>
					$sortbottoms = $table.find( '> tbody > tr.sortbottom' );
					if ( $sortbottoms.length ) {
						$tfoot = $table.children( 'tfoot' );
						if ( $tfoot.length ) {
							$tfoot.eq( 0 ).prepend( $sortbottoms );
						} else {
							$table.append( $( '<tfoot>' ).append( $sortbottoms ) );
						}
					}

					explodeRowspans( $table );
					manageColspans( $table );

					// Try to auto detect column type, and store in tables config
					config.parsers = buildParserCache( table, $headers );
				}

				// Apply event handling to headers
				// this is too big, perhaps break it out?
				$headers.on( 'keypress click', function ( e ) {
					var cell, $cell, columns, newSortList, i,
						totalRows,
						j, s, o;

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

					totalRows = ( $table[ 0 ].tBodies[ 0 ] && $table[ 0 ].tBodies[ 0 ].rows.length ) || 0;
					if ( totalRows > 0 ) {
						cell = this;
						$cell = $( cell );

						// Get current column sort order
						$cell.data( {
							order: $cell.data( 'count' ) % 2,
							count: $cell.data( 'count' ) + 1
						} );

						cell = this;
						// Get current column index
						columns = config.headerToColumns[ $cell.data( 'headerIndex' ) ];
						newSortList = columns.map( function ( c ) {
							return [ c, $cell.data( 'order' ) ];
						} );
						// Index of first column belonging to this header
						i = columns[ 0 ];

						if ( !e[ config.sortMultiSortKey ] ) {
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
								for ( j = 0; j < config.sortList.length; j++ ) {
									s = config.sortList[ j ];
									o = config.headerList[ config.columnToHeader[ s[ 0 ] ] ];
									if ( isValueInArray( s[ 0 ], newSortList ) ) {
										$( o ).data( 'count', s[ 1 ] + 1 );
										s[ 1 ] = $( o ).data( 'count' ) % 2;
									}
								}
							} else {
								// Add columns to sort list array
								config.sortList = config.sortList.concat( newSortList );
							}
						}

						// Reset order/counts of cells not affected by sorting
						setHeadersOrder( $headers, config.sortList, config.headerToColumns );

						// Set CSS for headers
						setHeadersCss( $table[ 0 ], $headers, config.sortList, sortCSS, sortMsg, config.columnToHeader );
						appendToTable(
							$table[ 0 ], multisort( $table[ 0 ], config.sortList, cache )
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
				 * @param {Array} [sortList] List of sort objects.
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
					setHeadersOrder( $headers, sortList, config.headerToColumns );

					// re-build the cache for the tbody cells
					cache = buildCache( table );

					// set css for headers
					setHeadersCss( table, $headers, sortList, sortCSS, sortMsg, config.columnToHeader );

					// sort the table and append it to the dom
					appendToTable( table, multisort( table, sortList, cache ) );
				};

				// sort initially
				if ( config.sortList.length > 0 ) {
					config.sortList = convertSortList( config.sortList );
					$table.data( 'tablesorter' ).sort();
				}

			} );
		},

		addParser: function ( parser ) {
			if ( !getParserById( parser.id ) ) {
				parsers.push( parser );
			}
		},

		formatDigit: function ( s ) {
			var out, c, p, i;
			if ( ts.transformTable !== false ) {
				out = '';
				for ( p = 0; p < s.length; p++ ) {
					c = s.charAt( p );
					if ( c in ts.transformTable ) {
						out += ts.transformTable[ c ];
					} else {
						out += c;
					}
				}
				s = out;
			}
			i = parseFloat( s.replace( /[, ]/g, '' ).replace( '\u2212', '-' ) );
			return isNaN( i ) ? -Infinity : i;
		},

		formatFloat: function ( s ) {
			var i = parseFloat( s );
			return isNaN( i ) ? -Infinity : i;
		},

		formatInt: function ( s ) {
			var i = parseInt( s, 10 );
			return isNaN( i ) ? -Infinity : i;
		},

		clearTableBody: function ( table ) {
			$( table.tBodies[ 0 ] ).empty();
		},

		getParser: function ( id ) {
			buildTransformTable();
			buildDateTable();
			cacheRegexs();
			buildCollationTable();

			return getParserById( id );
		},

		getParsers: function () { // for table diagnosis
			return parsers;
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
			var tsc;
			s = s.toLowerCase().trim();
			if ( ts.collationRegex ) {
				tsc = ts.collationTable;
				s = s.replace( ts.collationRegex, function ( match ) {
					var r = tsc[ match ] ? tsc[ match ] : tsc[ match.toUpperCase() ];
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
			return ts.rgx.IPAddress[ 0 ].test( s );
		},
		format: function ( s ) {
			var i, item,
				a = s.split( '.' ),
				r = '';
			for ( i = 0; i < a.length; i++ ) {
				item = a[ i ];
				if ( item.length === 1 ) {
					r += '00' + item;
				} else if ( item.length === 2 ) {
					r += '0' + item;
				} else {
					r += item;
				}
			}
			return $.tablesorter.formatFloat( r );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'currency',
		is: function ( s ) {
			return ts.rgx.currency[ 0 ].test( s );
		},
		format: function ( s ) {
			return $.tablesorter.formatDigit( s.replace( ts.rgx.currency[ 1 ], '' ) );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'url',
		is: function ( s ) {
			return ts.rgx.url[ 0 ].test( s );
		},
		format: function ( s ) {
			return s.replace( ts.rgx.url[ 1 ], '' ).trim();
		},
		type: 'text'
	} );

	ts.addParser( {
		id: 'isoDate',
		is: function ( s ) {
			return ts.rgx.isoDate[ 0 ].test( s );
		},
		format: function ( s ) {
			var match, i, isodate, ms, hOffset, mOffset;
			match = s.match( ts.rgx.isoDate[ 0 ] );
			if ( match === null ) {
				// Otherwise a signed number with 1-4 digit is parsed as isoDate
				match = s.match( ts.rgx.isoDate[ 1 ] );
			}
			if ( !match ) {
				return -Infinity;
			}
			// Month and day
			for ( i = 2; i <= 4; i += 2 ) {
				if ( !match[ i ] || match[ i ].length === 0 ) {
					match[ i ] = 1;
				}
			}
			// Time
			for ( i = 6; i <= 15; i++ ) {
				if ( !match[ i ] || match[ i ].length === 0 ) {
					match[ i ] = '0';
				}
			}
			ms = parseFloat( match[ 11 ].replace( /,/, '.' ) ) * 1000;
			hOffset = $.tablesorter.formatInt( match[ 13 ] + match[ 14 ] );
			mOffset = $.tablesorter.formatInt( match[ 13 ] + match[ 15 ] );

			isodate = new Date( 0 );
			// Because Date constructor changes year 0-99 to 1900-1999, use setUTCFullYear()
			isodate.setUTCFullYear( match[ 1 ], match[ 2 ] - 1, match[ 4 ] );
			isodate.setUTCHours( match[ 6 ] - hOffset, match[ 8 ] - mOffset, match[ 10 ], ms );
			return isodate.getTime();
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'usLongDate',
		is: function ( s ) {
			return ts.rgx.usLongDate[ 0 ].test( s );
		},
		format: function ( s ) {
			return $.tablesorter.formatFloat( new Date( s ).getTime() );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'date',
		is: function ( s ) {
			return ( ts.dateRegex[ 0 ].test( s ) || ts.dateRegex[ 1 ].test( s ) || ts.dateRegex[ 2 ].test( s ) );
		},
		format: function ( s ) {
			var match, y;
			s = s.toLowerCase().trim();

			if ( ( match = s.match( ts.dateRegex[ 0 ] ) ) !== null ) {
				if ( mw.config.get( 'wgDefaultDateFormat' ) === 'mdy' || mw.config.get( 'wgPageContentLanguage' ) === 'en' ) {
					s = [ match[ 3 ], match[ 1 ], match[ 2 ] ];
				} else if ( mw.config.get( 'wgDefaultDateFormat' ) === 'dmy' ) {
					s = [ match[ 3 ], match[ 2 ], match[ 1 ] ];
				} else {
					// If we get here, we don't know which order the dd-dd-dddd
					// date is in. So return something not entirely invalid.
					return '99999999';
				}
			} else if ( ( match = s.match( ts.dateRegex[ 1 ] ) ) !== null ) {
				s = [ match[ 3 ], String( ts.monthNames[ match[ 2 ] ] ), match[ 1 ] ];
			} else if ( ( match = s.match( ts.dateRegex[ 2 ] ) ) !== null ) {
				s = [ match[ 3 ], String( ts.monthNames[ match[ 1 ] ] ), match[ 2 ] ];
			} else {
				// Should never get here
				return '99999999';
			}

			// Pad Month and Day
			if ( s[ 1 ].length === 1 ) {
				s[ 1 ] = '0' + s[ 1 ];
			}
			if ( s[ 2 ].length === 1 ) {
				s[ 2 ] = '0' + s[ 2 ];
			}

			if ( ( y = parseInt( s[ 0 ], 10 ) ) < 100 ) {
				// Guestimate years without centuries
				if ( y < 30 ) {
					s[ 0 ] = 2000 + y;
				} else {
					s[ 0 ] = 1900 + y;
				}
			}
			while ( s[ 0 ].length < 4 ) {
				s[ 0 ] = '0' + s[ 0 ];
			}
			return parseInt( s.join( '' ), 10 );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'time',
		is: function ( s ) {
			return ts.rgx.time[ 0 ].test( s );
		},
		format: function ( s ) {
			return $.tablesorter.formatFloat( new Date( '2000/01/01 ' + s ).getTime() );
		},
		type: 'numeric'
	} );

	ts.addParser( {
		id: 'number',
		is: function ( s ) {
			return $.tablesorter.numberRegex.test( s.trim() );
		},
		format: function ( s ) {
			return $.tablesorter.formatDigit( s );
		},
		type: 'numeric'
	} );

}( jQuery, mediaWiki ) );
