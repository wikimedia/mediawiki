/**
 * Provides a {@link jQuery} plugin that creates a sortable table.
 *
 * Depends on mw.config (wgDigitTransformTable, wgDefaultDateFormat, wgPageViewLanguage)
 * and {@link mw.language.months}.
 *
 * Uses 'tableSorterCollation' in {@link mw.config} (if available).
 *
 * @module jquery.tablesorter
 * @author Written 2011 Leo Koppelkamm. Based on tablesorter.com plugin, written (c) 2007 Christian Bach/christian.bach@polyester.se
 * @license Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) and  GPL (http://www.gnu.org/licenses/gpl.html) licenses
 */
/**
 * @typedef {Object} module:jquery.tablesorter~TableSorterOptions
 * @property {string} [cssHeader="headerSort"] A string of the class name to be appended to sortable
 *         tr elements in the thead of the table.
 * @property {string} [cssAsc="headerSortUp"] A string of the class name to be appended to
 *         sortable tr elements in the thead on a ascending sort.
 * @property {string} [cssDesc="headerSortDown"] A string of the class name to be appended to
 *         sortable tr elements in the thead on a descending sort.
 * @property {string} [sortMultisortKey="shiftKey"] A string of the multi-column sort key.
 * @property {boolean} [cancelSelection=true] Boolean flag indicating iftablesorter should cancel
 *         selection of the table headers text.
 * @property {Array} [sortList] An array containing objects specifying sorting. By passing more
 *         than one object, multi-sorting will be applied. Object structure:
 *         { <Integer column index>: <String 'asc' or 'desc'> }
 */
( function () {
	const parsers = [];
	let ts = null;

	/* Parser utility functions */

	function getParserById( name ) {
		for ( let i = 0; i < parsers.length; i++ ) {
			if ( parsers[ i ].id.toLowerCase() === name.toLowerCase() ) {
				return parsers[ i ];
			}
		}
		return false;
	}

	/**
	 * @param {HTMLElement} node
	 * @return {string}
	 */
	function getElementSortKey( node ) {
		// Browse the node to build the raw sort key, which will then be normalized.
		function buildRawSortKey( currentNode ) {
			// Get data-sort-value attribute. Uses jQuery to allow live value
			// changes from other code paths via data(), which reside only in jQuery.
			// Must use $().data() instead of $.data(), as the latter *only*
			// accesses the live values, without reading HTML5 attribs first (T40152).
			const data = $( currentNode ).data( 'sortValue' );

			if ( data !== null && data !== undefined ) {
				// Cast any numbers or other stuff to a string. Methods
				// like charAt, toLowerCase and split are expected in callers.
				return String( data );
			}

			// Iterate the NodeList (not an array).
			// Also uses null-return as filter in the same pass.
			// eslint-disable-next-line no-jquery/no-map-util
			return $.map( currentNode.childNodes, ( elem ) => {
				if ( elem.nodeType === Node.ELEMENT_NODE ) {
					const nodeName = elem.nodeName.toLowerCase();
					if ( nodeName === 'img' ) {
						return elem.alt;
					}
					if ( nodeName === 'br' ) {
						return ' ';
					}
					if ( nodeName === 'style' ) {
						return null;
					}
					if ( elem.classList.contains( 'reference' ) ) {
						return null;
					}
					return buildRawSortKey( elem );
				}
				if ( elem.nodeType === Node.TEXT_NODE ) {
					return elem.textContent;
				}
				// Ignore other node types, such as HTML comments.
				return null;
			} ).join( '' );
		}

		return buildRawSortKey( node ).replace( /  +/g, ' ' ).trim();
	}

	function detectParserForColumn( table, rows, column ) {
		const l = parsers.length,
			config = $( table ).data( 'tablesorter' ).config,
			needed = ( rows.length > 4 ) ? 5 : rows.length;
		// Start with 1 because 0 is the fallback parser
		let i = 1,
			nextRow = false,
			lastRowIndex = -1,
			rowIndex = 0,
			concurrent = 0,
			empty = 0;

		let nodeValue;
		while ( i < l ) {
			// if this is a child row, continue to the next row (as buildCache())
			// eslint-disable-next-line no-jquery/no-class-state
			if ( rows[ rowIndex ] && !$( rows[ rowIndex ] ).hasClass( config.cssChildRow ) ) {
				if ( rowIndex !== lastRowIndex ) {
					lastRowIndex = rowIndex;
					const cellIndex = $( rows[ rowIndex ] ).data( 'columnToCell' )[ column ];
					nodeValue = getElementSortKey( rows[ rowIndex ].cells[ cellIndex ] );
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
		const rows = table.tBodies[ 0 ].rows,
			config = $( table ).data( 'tablesorter' ).config,
			cachedParsers = [];

		if ( rows[ 0 ] ) {
			for ( let j = 0; j < config.columns; j++ ) {
				let parser = false;
				const sortType = $headers.eq( config.columnToHeader[ j ] ).data( 'sortType' );
				if ( sortType !== undefined ) {
					// Cast any numbers or other stuff to a string. Methods
					// like charAt, toLowerCase and split are expected in callers.
					parser = getParserById( String( sortType ) );
				}

				if ( parser === false ) {
					parser = detectParserForColumn( table, rows, j );
				}

				cachedParsers.push( parser );
			}
		}
		return cachedParsers;
	}

	/* Other utility functions */

	function buildCache( table ) {
		const totalRows = ( table.tBodies[ 0 ] && table.tBodies[ 0 ].rows.length ) || 0,
			config = $( table ).data( 'tablesorter' ).config,
			cachedParsers = config.parsers,
			cache = {
				row: [],
				normalized: []
			};

		for ( let i = 0; i < totalRows; i++ ) {

			// Add the table data to main data array
			const $row = $( table.tBodies[ 0 ].rows[ i ] );
			let cols = [];

			// if this is a child row, add it to the last row's children and
			// continue to the next row
			// eslint-disable-next-line no-jquery/no-class-state
			if ( $row.hasClass( config.cssChildRow ) ) {
				cache.row[ cache.row.length - 1 ] = cache.row[ cache.row.length - 1 ].add( $row );
				// go to the next for loop
				continue;
			}

			cache.row.push( $row );

			if ( $row.data( 'initialOrder' ) === undefined ) {
				$row.data( 'initialOrder', i );
			}

			for ( let j = 0; j < cachedParsers.length; j++ ) {
				const cellIndex = $row.data( 'columnToCell' )[ j ];
				cols.push( cachedParsers[ j ].format( getElementSortKey( $row[ 0 ].cells[ cellIndex ] ) ) );
			}

			// Store the initial sort order, from when the page was loaded
			cols.push( $row.data( 'initialOrder' ) );

			// Store the current sort order, before rows are re-sorted
			cols.push( cache.normalized.length );

			cache.normalized.push( cols );
			cols = null;
		}

		return cache;
	}

	function appendToTable( table, cache ) {
		const row = cache.row,
			normalized = cache.normalized,
			totalRows = normalized.length,
			checkCell = ( normalized[ 0 ].length - 1 ),
			fragment = document.createDocumentFragment();

		for ( let i = 0; i < totalRows; i++ ) {
			const pos = normalized[ i ][ checkCell ];

			const l = row[ pos ].length;
			for ( let j = 0; j < l; j++ ) {
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
		const $rows = $table.find( '> tbody > tr' );

		if ( !$table.get( 0 ).tHead ) {
			const $thead = $( '<thead>' );
			$rows.each( function () {
				if ( $( this ).children( 'td' ).length ) {
					// This row contains a <td>, so it's not a header row
					// Stop here
					return false;
				}
				$thead.append( this );
			} );
			$table.find( '> tbody' ).first().before( $thead );
		}
		if ( !$table.get( 0 ).tFoot ) {
			const $tfoot = $( '<tfoot>' );
			let tfootRows = [],
				remainingCellRowSpan = 0;

			$rows.each( function () {
				$( this ).children( 'td' ).each( function () {
					remainingCellRowSpan = Math.max( this.rowSpan, remainingCellRowSpan );
				} );

				if ( remainingCellRowSpan > 0 ) {
					tfootRows = [];
					remainingCellRowSpan--;
				} else {
					tfootRows.push( this );
				}
			} );

			$tfoot.append( tfootRows );
			$table.append( $tfoot );
		}
	}

	function uniqueElements( array ) {
		const uniques = [];
		array.forEach( ( elem ) => {
			if ( elem !== undefined && uniques.indexOf( elem ) === -1 ) {
				uniques.push( elem );
			}
		} );
		return uniques;
	}

	function buildHeaders( table, msg ) {
		const config = $( table ).data( 'tablesorter' ).config,
			$tableRows = $( table ).find( 'thead' ).eq( 0 ).find( '> tr:not(.sorttop)' );
		let $tableHeaders = $( [] );

		let maxSeen = 0,
			colspanOffset = 0;

		if ( $tableRows.length <= 1 ) {
			$tableHeaders = $tableRows.children( 'th' );
		} else {
			const exploded = [];

			// Loop through all the dom cells of the thead
			$tableRows.each( ( rowIndex, row ) => {
				// eslint-disable-next-line no-jquery/no-each-util
				$.each( row.cells, ( columnIndex, cell ) => {
					const rowspan = Number( cell.rowSpan );
					const colspan = Number( cell.colSpan );

					// Skip the spots in the exploded matrix that are already filled
					while ( exploded[ rowIndex ] && exploded[ rowIndex ][ columnIndex ] !== undefined ) {
						++columnIndex;
					}

					let matrixRowIndex,
						matrixColumnIndex;
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
			let longestTR;
			// We want to find the row that has the most columns (ignoring colspan)
			exploded.forEach( ( cellArray, index ) => {
				const headerCount = $( uniqueElements( cellArray ) ).filter( 'th' ).length;
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
		let headerIndex = 0;
		$tableHeaders.each( function () {
			const $cell = $( this );
			const columns = [];

			// eslint-disable-next-line no-jquery/no-class-state
			if ( !$cell.hasClass( config.unsortableClass ) ) {
				$cell
					// The following classes are used here:
					// * headerSort
					// * other passed by config
					.addClass( config.cssHeader )
					.prop( 'tabIndex', 0 )
					.attr( {
						role: 'columnheader button',
						title: msg[ 2 ]
					} );

				for ( let k = 0; k < this.colSpan; k++ ) {
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
		for ( let i = 0; i < a.length; i++ ) {
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
		headerToColumns.forEach( ( columns, headerIndex ) => {

			columns.forEach( ( columnIndex, i ) => {
				const header = $headers[ headerIndex ],
					$header = $( header );

				if ( !isValueInArray( columnIndex, sortList ) ) {
					// Column shall not be sorted: Reset header count and order.
					$header.data( {
						order: 0,
						count: 0
					} );
				} else {
					// Column shall be sorted: Apply designated count and order.
					for ( let j = 0; j < sortList.length; j++ ) {
						const sortColumn = sortList[ j ];
						if ( sortColumn[ 0 ] === i ) {
							$header.data( {
								order: sortColumn[ 1 ],
								count: sortColumn[ 1 ] + 1
							} );
							break;
						}
					}
				}
			} );

		} );
	}

	function setHeadersCss( table, $headers, list, css, msg, columnToHeader ) {
		// Remove all header information and reset titles to default message
		// The following classes are used here:
		// * headerSortUp
		// * headerSortDown
		$headers.removeClass( css ).attr( 'title', msg[ 2 ] );

		for ( let i = 0; i < list.length; i++ ) {
			// The following classes are used here:
			// * headerSortUp
			// * headerSortDown
			$headers
				.eq( columnToHeader[ list[ i ][ 0 ] ] )
				.addClass( css[ list[ i ][ 1 ] ] )
				.attr( 'title', msg[ list[ i ][ 1 ] ] );
		}
	}

	function sortText( a, b ) {
		return ts.collator.compare( a, b );
	}

	function sortNumeric( a, b ) {
		return ( ( a < b ) ? -1 : ( ( a > b ) ? 1 : 0 ) );
	}

	function multisort( table, sortList, cache ) {
		const sortFn = [],
			cachedParsers = $( table ).data( 'tablesorter' ).config.parsers;

		for ( let i = 0; i < sortList.length; i++ ) {
			// Android doesn't support Intl.Collator
			if ( window.Intl && Intl.Collator && cachedParsers[ sortList[ i ][ 0 ] ].type === 'text' ) {
				sortFn[ i ] = sortText;
			} else {
				sortFn[ i ] = sortNumeric;
			}
		}
		cache.normalized.sort( function ( array1, array2 ) {
			for ( let n = 0; n < sortList.length; n++ ) {
				const col = sortList[ n ][ 0 ];
				let ret;
				if ( sortList[ n ][ 1 ] === 2 ) {
					// initial order
					const orderIndex = array1.length - 2;
					ret = sortNumeric.call( this, array1[ orderIndex ], array2[ orderIndex ] );
				} else if ( sortList[ n ][ 1 ] === 1 ) {
					// descending
					ret = sortFn[ n ].call( this, array2[ col ], array1[ col ] );
				} else {
					// ascending
					ret = sortFn[ n ].call( this, array1[ col ], array2[ col ] );
				}
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
		const digits = '0123456789,.'.split( '' ),
			separatorTransformTable = mw.config.get( 'wgSeparatorTransformTable' ),
			digitTransformTable = mw.config.get( 'wgDigitTransformTable' );

		if ( separatorTransformTable === null || ( separatorTransformTable[ 0 ] === '' && digitTransformTable[ 2 ] === '' ) ) {
			ts.transformTable = false;
		} else {
			ts.transformTable = {};

			// Unpack the transform table
			const ascii = separatorTransformTable[ 0 ].split( '\t' ).concat( digitTransformTable[ 0 ].split( '\t' ) );
			const localised = separatorTransformTable[ 1 ].split( '\t' ).concat( digitTransformTable[ 1 ].split( '\t' ) );

			// Construct regexes for number identification
			for ( let i = 0; i < ascii.length; i++ ) {
				ts.transformTable[ localised[ i ] ] = ascii[ i ];
				digits.push( mw.util.escapeRegExp( localised[ i ] ) );
			}
		}
		const digitClass = '[' + digits.join( '', digits ) + ']';

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
		let regex = [];

		ts.monthNames = {};

		for ( let i = 0; i < 12; i++ ) {
			let name = mw.language.months.names[ i ].toLowerCase();
			ts.monthNames[ name ] = i + 1;
			regex.push( mw.util.escapeRegExp( name ) );
			name = mw.language.months.genitive[ i ].toLowerCase();
			ts.monthNames[ name ] = i + 1;
			regex.push( mw.util.escapeRegExp( name ) );
			name = mw.language.months.abbrev[ i ].toLowerCase().replace( '.', '' );
			ts.monthNames[ name ] = i + 1;
			regex.push( mw.util.escapeRegExp( name ) );
		}

		// Build piped string
		regex = regex.join( '|' );

		// Build RegEx
		// Any date formated with . , ' - or /
		ts.dateRegex[ 0 ] = new RegExp( /^\s*(\d{1,2})[,.\-/'\s]{1,2}(\d{1,2})[,.\-/'\s]{1,2}(\d{2,4})\s*?/i );

		// Written Month name, dmy

		ts.dateRegex[ 1 ] = new RegExp(
			'^\\s*(\\d{1,2})[\\,\\.\\-\\/\'º\\s]+(' +
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
		let spanningRealCellIndex, colSpan,
			rowspanCells = $table.find( '> tbody > tr > [rowspan]' ).get();

		// Short circuit
		if ( !rowspanCells.length ) {
			return;
		}

		// First, we need to make a property like cellIndex but taking into
		// account colspans. We also cache the rowIndex to avoid having to take
		// cell.parentNode.rowIndex in the sorting function below.
		$table.find( '> tbody > tr' ).each( function () {
			let col = 0;
			for ( let c = 0; c < this.cells.length; c++ ) {
				$( this.cells[ c ] ).data( 'tablesorter', {
					realCellIndex: col,
					realRowIndex: this.rowIndex
				} );
				col += this.cells[ c ].colSpan;
			}
		} );

		// Split multi row cells into multiple cells with the same content.
		// Sort by column then row index to avoid problems with odd table structures.
		// Re-sort whenever a rowspanned cell's realCellIndex is changed, because it
		// might change the sort order.
		function resortCells() {
			rowspanCells = rowspanCells.sort( ( a, b ) => {
				const cellAData = $.data( a, 'tablesorter' );
				const cellBData = $.data( b, 'tablesorter' );
				let ret = cellAData.realCellIndex - cellBData.realCellIndex;
				if ( !ret ) {
					ret = cellAData.realRowIndex - cellBData.realRowIndex;
				}
				return ret;
			} );
			rowspanCells.forEach( ( cellNode ) => {
				$.data( cellNode, 'tablesorter' ).needResort = false;
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

			const cell = rowspanCells.shift();
			const cellData = $.data( cell, 'tablesorter' );
			const rowSpan = cell.rowSpan;
			colSpan = cell.colSpan;
			spanningRealCellIndex = cellData.realCellIndex;
			cell.rowSpan = 1;
			const $nextRows = $( cell ).parent().nextAll();

			for ( let i = 0; i < rowSpan - 1; i++ ) {
				const row = $nextRows[ i ];
				if ( !row ) {
					// Badly formatted HTML for table.
					// Ignore this row, but leave a warning for someone to be able to find this.
					// Perhaps in future this could be a wikitext linter rule, or preview warning
					// on the edit page.
					mw.log.warn( mw.message( 'sort-rowspan-error' ).plain() );
					break;
				}
				const $tds = $( row.cells ).filter( filterfunc );
				const $clone = $( cell ).clone();
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
		const $rows = $table.find( '> tbody > tr' ),
			totalRows = $rows.length || 0,
			config = $table.data( 'tablesorter' ).config,
			columns = config.columns;

		for ( let i = 0; i < totalRows; i++ ) {

			const $row = $rows.eq( i );
			// if this is a child row, continue to the next row (as buildCache())
			// eslint-disable-next-line no-jquery/no-class-state
			if ( $row.hasClass( config.cssChildRow ) ) {
				// go to the next for loop
				continue;
			}

			const columnToCell = [];
			let cellsInRow = ( $row[ 0 ].cells.length ) || 0; // all cells in this row
			let index = 0; // real cell index in this row
			for ( let j = 0; j < columns; index++ ) {
				if ( index === cellsInRow ) {
					// Row with cells less than columns: add empty cell
					$row.append( '<td>' );
					cellsInRow++;
				}
				for ( let k = 0; k < $row[ 0 ].cells[ index ].colSpan; k++ ) {
					columnToCell[ j++ ] = index;
				}
			}
			// Store it in $row
			$row.data( 'columnToCell', columnToCell );
		}
	}

	function buildCollation() {
		const keys = [];
		ts.collationTable = mw.config.get( 'tableSorterCollation' );
		ts.collationRegex = null;
		if ( ts.collationTable ) {
			// Build array of key names
			for ( const key in ts.collationTable ) {
				keys.push( mw.util.escapeRegExp( key ) );
			}
			if ( keys.length ) {

				ts.collationRegex = new RegExp( keys.join( '|' ), 'ig' );
			}
		}
		if ( window.Intl && Intl.Collator ) {
			ts.collator = new Intl.Collator( [
				mw.config.get( 'wgPageViewLanguage' ),
				mw.config.get( 'wgUserLanguage' )
			], {
				numeric: true
			} );
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
		const sortList = [];
		sortObjects.forEach( ( sortObject ) => {
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( sortObject, ( columnIndex, order ) => {
				const orderIndex = ( order === 'desc' ) ? 1 : 0;
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
			cssInitial: '',
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
			return $tables.each( ( i, table ) => {
				// Declare and cache.
				let cache,
					firstTime = true;
				const $table = $( table );

				// Don't construct twice on the same table
				if ( $.data( table, 'tablesorter' ) ) {
					return;
				}
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
				// The `sortable` class is used to identify tables which will become sortable
				// If not used it will create a FOUC but it should be added since the sortable class
				// is responsible for certain crucial style elements. If the class is already present
				// this action will be harmless.
				$table.addClass( 'jquery-tablesorter sortable' );

				// Merge and extend
				const config = Object.assign( {}, $.tablesorter.defaultOptions, settings );

				// Save the settings where they read
				$.data( table, 'tablesorter', { config: config } );

				// Get the CSS class names, could be done elsewhere
				const sortCSS = [ config.cssAsc, config.cssDesc, config.cssInitial ];
				// Messages tell the user what the *next* state will be
				// so are shifted by one relative to the CSS classes.
				const sortMsg = [ mw.msg( 'sort-descending' ), mw.msg( 'sort-initial' ), mw.msg( 'sort-ascending' ) ];

				// Build headers
				const $headers = buildHeaders( table, sortMsg );

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
					buildCollation();

					// Move .sortbottom rows to the <tfoot> at the bottom of the <table>
					const $sortbottoms = $table.find( '> tbody > tr.sortbottom' );
					if ( $sortbottoms.length ) {
						const $tfoot = $table.children( 'tfoot' );
						if ( $tfoot.length ) {
							$tfoot.eq( 0 ).prepend( $sortbottoms );
						} else {
							$table.append( $( '<tfoot>' ).append( $sortbottoms ) );
						}
					}

					// Move .sorttop rows to the <thead> at the top of the <table>
					// <thead> should exist if we got this far
					const $sorttops = $table.find( '> tbody > tr.sorttop' );
					if ( $sorttops.length ) {
						$table.children( 'thead' ).append( $sorttops );
					}

					explodeRowspans( $table );
					manageColspans( $table );

					// Try to auto detect column type, and store in tables config
					config.parsers = buildParserCache( table, $headers );
				}

				// Apply event handling to headers
				// this is too big, perhaps break it out?
				$headers.on( 'keypress click', function ( e ) {
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
					// Re-calculated each time a sort action is performed due to possibility
					// that sort values change. Shouldn't be too expensive, but if it becomes
					// too slow an event based system should be implemented somehow where
					// cells get event .change() and bubbles up to the <table> here
					cache = buildCache( table );

					const totalRows = ( $table[ 0 ].tBodies[ 0 ] && $table[ 0 ].tBodies[ 0 ].rows.length ) || 0;
					if ( totalRows > 0 ) {
						const cell = this;
						const $cell = $( cell );
						const numSortOrders = 3;

						// Get current column sort order
						$cell.data( {
							order: $cell.data( 'count' ) % numSortOrders,
							count: $cell.data( 'count' ) + 1
						} );

						// Get current column index
						const columns = config.headerToColumns[ $cell.data( 'headerIndex' ) ];
						const newSortList = columns.map( ( c ) => [ c, $cell.data( 'order' ) ] );
						// Index of first column belonging to this header
						const col = columns[ 0 ];

						if ( !e[ config.sortMultiSortKey ] ) {
							// User only wants to sort on one column set
							// Flush the sort list and add new columns
							config.sortList = newSortList;
						} else {
							// Multi column sorting
							// It is not possible for one column to belong to multiple headers,
							// so this is okay - we don't need to check for every value in the columns array
							if ( isValueInArray( col, config.sortList ) ) {
								// The user has clicked on an already sorted column.
								// Reverse the sorting direction for all tables.
								for ( let j = 0; j < config.sortList.length; j++ ) {
									const s = config.sortList[ j ];
									const o = config.headerList[ config.columnToHeader[ s[ 0 ] ] ];
									if ( isValueInArray( s[ 0 ], newSortList ) ) {
										$( o ).data( 'count', s[ 1 ] + 1 );
										s[ 1 ] = $( o ).data( 'count' ) % numSortOrders;
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
				} ).on( 'mousedown', function () {
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
				 * @ignore
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
			if ( ts.transformTable !== false ) {
				let out = '';
				for ( let p = 0; p < s.length; p++ ) {
					const c = s.charAt( p );
					if ( c in ts.transformTable ) {
						out += ts.transformTable[ c ];
					} else {
						out += c;
					}
				}
				s = out;
			}
			const i = parseFloat( s.replace( /[, ]/g, '' ).replace( '\u2212', '-' ) );
			return isNaN( i ) ? -Infinity : i;
		},

		formatFloat: function ( s ) {
			const i = parseFloat( s );
			return isNaN( i ) ? -Infinity : i;
		},

		formatInt: function ( s ) {
			const i = parseInt( s, 10 );
			return isNaN( i ) ? -Infinity : i;
		},

		clearTableBody: function ( table ) {
			$( table.tBodies[ 0 ] ).empty();
		},

		getParser: function ( id ) {
			buildTransformTable();
			buildDateTable();
			cacheRegexs();
			buildCollation();

			return getParserById( id );
		},

		getParsers: function () { // for table diagnosis
			return parsers;
		}
	};

	// Shortcut
	ts = $.tablesorter;

	// Register as jQuery prototype method
	/**
	 * Create a sortable table with multi-column sorting capabilities.
	 *
	 * To use this {@link jQuery} plugin, load the `jquery.tablesorter` module with {@link mw.loader}.
	 *
	 * @memberof module:jquery.tablesorter
	 * @example
	 * mw.loader.using( 'jquery.tablesorter' ).then( () => {
	 *      // Create a simple tablesorter interface
	 *      $( 'table' ).tablesorter();
	 *
	 *      // Create a tablesorter interface, initially sorting on the first and second column
	 *      $( 'table' ).tablesorter( { sortList: [ { 0: 'desc' }, { 1: 'asc' } ] } )
	 *          .on( 'sortEnd.tablesorter', () => console.log( 'Triggered as soon as any sorting has been applied.' ) );
	 * } );
	 * @param {module:jquery.tablesorter~TableSorterOptions} settings
	 * @return {jQuery}
	 */
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
			if ( ts.collationRegex ) {
				const tsc = ts.collationTable;
				s = s.replace( ts.collationRegex, ( match ) => {
					const upper = match.toUpperCase(),
						lower = match.toLowerCase();
					let r;
					if ( upper === match && !lower === match ) {
						r = tsc[ lower ] ? tsc[ lower ] : tsc[ upper ];
						r = r.toUpperCase();
					} else {
						r = tsc[ lower ];
					}
					return r;
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
			const a = s.split( '.' );
			let r = '';
			for ( let i = 0; i < a.length; i++ ) {
				const item = a[ i ];
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
			s = s.toLowerCase();

			let match;
			if ( ( match = s.match( ts.dateRegex[ 0 ] ) ) !== null ) {
				if ( mw.config.get( 'wgDefaultDateFormat' ) === 'mdy' || mw.config.get( 'wgPageViewLanguage' ) === 'en' ) {
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

			let y;
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
			return $.tablesorter.numberRegex.test( s );
		},
		format: function ( s ) {
			return $.tablesorter.formatDigit( s );
		},
		type: 'numeric'
	} );

}() );
