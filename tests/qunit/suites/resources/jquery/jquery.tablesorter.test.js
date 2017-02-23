( function ( $, mw ) {
	var header = [ 'Planet', 'Radius (km)' ],

		// Data set "planets"
		mercury = [ 'Mercury', '2439.7' ],
		venus = [ 'Venus', '6051.8' ],
		earth = [ 'Earth', '6371.0' ],
		mars = [ 'Mars', '3390.0' ],
		jupiter = [ 'Jupiter', '69911' ],
		saturn = [ 'Saturn', '58232' ],
		planets = [ mercury, venus, earth, mars, jupiter, saturn ],
		planetsAscName = [ earth, jupiter, mars, mercury, saturn, venus ],
		planetsAscRadius = [ mercury, mars, venus, earth, saturn, jupiter ],
		planetsRowspan,
		planetsRowspanII,
		planetsAscNameLegacy,

		// Data set "simple"
		a1 = [ 'A', '1' ],
		a2 = [ 'A', '2' ],
		a3 = [ 'A', '3' ],
		b1 = [ 'B', '1' ],
		b2 = [ 'B', '2' ],
		b3 = [ 'B', '3' ],
		simple = [ a2, b3, a1, a3, b2, b1 ],
		simpleAsc = [ a1, a2, a3, b1, b2, b3 ],
		simpleDescasc = [ b1, b2, b3, a1, a2, a3 ],

		// Data set "colspan"
		header4 = [ 'column1a', 'column1b', 'column1c', 'column2' ],
		aaa1 = [ 'A', 'A', 'A', '1' ],
		aab5 = [ 'A', 'A', 'B', '5' ],
		abc3 = [ 'A', 'B', 'C', '3' ],
		bbc2 = [ 'B', 'B', 'C', '2' ],
		caa4 = [ 'C', 'A', 'A', '4' ],
		colspanInitial = [ aab5, aaa1, abc3, bbc2, caa4 ],

		// Data set "ipv4"
		ipv4 = [
			// Some randomly generated fake IPs
			[ '45.238.27.109' ],
			[ '44.172.9.22' ],
			[ '247.240.82.209' ],
			[ '204.204.132.158' ],
			[ '170.38.91.162' ],
			[ '197.219.164.9' ],
			[ '45.68.154.72' ],
			[ '182.195.149.80' ]
		],
		ipv4Sorted = [
			// Sort order should go octet by octet
			[ '44.172.9.22' ],
			[ '45.68.154.72' ],
			[ '45.238.27.109' ],
			[ '170.38.91.162' ],
			[ '182.195.149.80' ],
			[ '197.219.164.9' ],
			[ '204.204.132.158' ],
			[ '247.240.82.209' ]
		],

		// Data set "umlaut"
		umlautWords = [
			[ 'Günther' ],
			[ 'Peter' ],
			[ 'Björn' ],
			[ 'Bjorn' ],
			[ 'Apfel' ],
			[ 'Äpfel' ],
			[ 'Strasse' ],
			[ 'Sträßschen' ]
		],
		umlautWordsSorted = [
			[ 'Äpfel' ],
			[ 'Apfel' ],
			[ 'Björn' ],
			[ 'Bjorn' ],
			[ 'Günther' ],
			[ 'Peter' ],
			[ 'Sträßschen' ],
			[ 'Strasse' ]
		],

		// Data set "digraph"
		digraphWords = [
			[ 'London' ],
			[ 'Ljubljana' ],
			[ 'Luxembourg' ],
			[ 'Njivice' ],
			[ 'Norwich' ],
			[ 'New York' ]
		],
		digraphWordsSorted = [
			[ 'London' ],
			[ 'Luxembourg' ],
			[ 'Ljubljana' ],
			[ 'New York' ],
			[ 'Norwich' ],
			[ 'Njivice' ]
		],

		complexMDYDates = [
			[ 'January, 19 2010' ],
			[ 'April 21 1991' ],
			[ '04 22 1991' ],
			[ '5.12.1990' ],
			[ 'December 12 \'10' ]
		],
		complexMDYSorted = [
			[ '5.12.1990' ],
			[ 'April 21 1991' ],
			[ '04 22 1991' ],
			[ 'January, 19 2010' ],
			[ 'December 12 \'10' ]
		],

		currencyUnsorted = [
			[ '1.02 $' ],
			[ '$ 3.00' ],
			[ '€ 2,99' ],
			[ '$ 1.00' ],
			[ '$3.50' ],
			[ '$ 1.50' ],
			[ '€ 0.99' ]
		],
		currencySorted = [
			[ '€ 0.99' ],
			[ '$ 1.00' ],
			[ '1.02 $' ],
			[ '$ 1.50' ],
			[ '$ 3.00' ],
			[ '$3.50' ],
			// Comma's sort after dots
			// Not intentional but test to detect changes
			[ '€ 2,99' ]
		],

		numbers = [
			[ '12' ],
			[ '7' ],
			[ '13,000' ],
			[ '9' ],
			[ '14' ],
			[ '8.0' ]
		],
		numbersAsc = [
			[ '7' ],
			[ '8.0' ],
			[ '9' ],
			[ '12' ],
			[ '14' ],
			[ '13,000' ]
		],

		correctDateSorting1 = [
			[ '01 January 2010' ],
			[ '05 February 2010' ],
			[ '16 January 2010' ]
		],
		correctDateSortingSorted1 = [
			[ '01 January 2010' ],
			[ '16 January 2010' ],
			[ '05 February 2010' ]
		],

		correctDateSorting2 = [
			[ 'January 01 2010' ],
			[ 'February 05 2010' ],
			[ 'January 16 2010' ]
		],
		correctDateSortingSorted2 = [
			[ 'January 01 2010' ],
			[ 'January 16 2010' ],
			[ 'February 05 2010' ]
		],
		isoDateSorting = [
			[ '2010-02-01' ],
			[ '2009-12-25T12:30:45.001Z' ],
			[ '2010-01-31' ],
			[ '2009' ],
			[ '2009-12-25T12:30:45' ],
			[ '2009-12-25T12:30:45.111' ],
			[ '2009-12-25T12:30:45+01:00' ]
		],
		isoDateSortingSorted = [
			[ '2009' ],
			[ '2009-12-25T12:30:45' ],
			[ '2009-12-25T12:30:45+01:00' ],
			[ '2009-12-25T12:30:45.001Z' ],
			[ '2009-12-25T12:30:45.111' ],
			[ '2010-01-31' ],
			[ '2010-02-01' ]
		];

	QUnit.module( 'jquery.tablesorter', QUnit.newMwEnvironment( {
		setup: function () {
			this.liveMonths = mw.language.months;
			mw.language.months = {
				keys: {
					names: [ 'january', 'february', 'march', 'april', 'may_long', 'june',
						'july', 'august', 'september', 'october', 'november', 'december' ],
					genitive: [ 'january-gen', 'february-gen', 'march-gen', 'april-gen', 'may-gen', 'june-gen',
						'july-gen', 'august-gen', 'september-gen', 'october-gen', 'november-gen', 'december-gen' ],
					abbrev: [ 'jan', 'feb', 'mar', 'apr', 'may', 'jun',
						'jul', 'aug', 'sep', 'oct', 'nov', 'dec' ]
				},
				names: [ 'January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December' ],
				genitive: [ 'January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December' ],
				abbrev: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
					'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ]
			};
		},
		teardown: function () {
			mw.language.months = this.liveMonths;
		},
		config: {
			wgDefaultDateFormat: 'dmy',
			wgSeparatorTransformTable: [ '', '' ],
			wgDigitTransformTable: [ '', '' ],
			wgPageContentLanguage: 'en'
		}
	} ) );

	/**
	 * Create an HTML table from an array of row arrays containing text strings.
	 * First row will be header row. No fancy rowspan/colspan stuff.
	 *
	 * @param {string[]} header
	 * @param {string[][]} data
	 * @return {jQuery}
	 */
	function tableCreate( header, data ) {
		var i,
			$table = $( '<table class="sortable"><thead></thead><tbody></tbody></table>' ),
			$thead = $table.find( 'thead' ),
			$tbody = $table.find( 'tbody' ),
			$tr = $( '<tr>' );

		$.each( header, function ( i, str ) {
			var $th = $( '<th>' );
			$th.text( str ).appendTo( $tr );
		} );
		$tr.appendTo( $thead );

		for ( i = 0; i < data.length; i++ ) {
			$tr = $( '<tr>' );
			// eslint-disable-next-line no-loop-func
			$.each( data[ i ], function ( j, str ) {
				var $td = $( '<td>' );
				$td.text( str ).appendTo( $tr );
			} );
			$tr.appendTo( $tbody );
		}
		return $table;
	}

	/**
	 * Extract text from table.
	 *
	 * @param {jQuery} $table
	 * @return {string[][]}
	 */
	function tableExtract( $table ) {
		var data = [];

		$table.find( 'tbody' ).find( 'tr' ).each( function ( i, tr ) {
			var row = [];
			$( tr ).find( 'td,th' ).each( function ( i, td ) {
				row.push( $( td ).text() );
			} );
			data.push( row );
		} );
		return data;
	}

	/**
	 * Run a table test by building a table with the given data,
	 * running some callback on it, then checking the results.
	 *
	 * @param {string} msg text to pass on to qunit for the comparison
	 * @param {string[]} header cols to make the table
	 * @param {string[][]} data rows/cols to make the table
	 * @param {string[][]} expected rows/cols to compare against at end
	 * @param {function($table)} callback something to do with the table before we compare
	 */
	function tableTest( msg, header, data, expected, callback ) {
		QUnit.test( msg, 1, function ( assert ) {
			var extracted,
				$table = tableCreate( header, data );

			// Give caller a chance to set up sorting and manipulate the table.
			callback( $table );

			// Table sorting is done synchronously; if it ever needs to change back
			// to asynchronous, we'll need a timeout or a callback here.
			extracted = tableExtract( $table );
			assert.deepEqual( extracted, expected, msg );
		} );
	}

	/**
	 * Run a table test by building a table with the given HTML,
	 * running some callback on it, then checking the results.
	 *
	 * @param {string} msg text to pass on to qunit for the comparison
	 * @param {string} html HTML to make the table
	 * @param {string[][]} expected Rows/cols to compare against at end
	 * @param {function($table)} callback Something to do with the table before we compare
	 */
	function tableTestHTML( msg, html, expected, callback ) {
		QUnit.test( msg, 1, function ( assert ) {
			var extracted,
				$table = $( html );

			// Give caller a chance to set up sorting and manipulate the table.
			if ( callback ) {
				callback( $table );
			} else {
				$table.tablesorter();
				$table.find( '#sortme' ).click();
			}

			// Table sorting is done synchronously; if it ever needs to change back
			// to asynchronous, we'll need a timeout or a callback here.
			extracted = tableExtract( $table );
			assert.deepEqual( extracted, expected, msg );
		} );
	}

	function reversed( arr ) {
		// Clone array
		var arr2 = arr.slice( 0 );

		arr2.reverse();

		return arr2;
	}

	// Sample data set using planets named and their radius

	tableTest(
		'Basic planet table: sorting initially - ascending by name',
		header,
		planets,
		planetsAscName,
		function ( $table ) {
			$table.tablesorter( { sortList: [
				{ 0: 'asc' }
			] } );
		}
	);
	tableTest(
		'Basic planet table: sorting initially - descending by radius',
		header,
		planets,
		reversed( planetsAscRadius ),
		function ( $table ) {
			$table.tablesorter( { sortList: [
				{ 1: 'desc' }
			] } );
		}
	);
	tableTest(
		'Basic planet table: ascending by name',
		header,
		planets,
		planetsAscName,
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest(
		'Basic planet table: ascending by name a second time',
		header,
		planets,
		planetsAscName,
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest(
		'Basic planet table: ascending by name (multiple clicks)',
		header,
		planets,
		planetsAscName,
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
			$table.find( '.headerSort:eq(1)' ).click();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest(
		'Basic planet table: descending by name',
		header,
		planets,
		reversed( planetsAscName ),
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click().click();
		}
	);
	tableTest(
		'Basic planet table: ascending radius',
		header,
		planets,
		planetsAscRadius,
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(1)' ).click();
		}
	);
	tableTest(
		'Basic planet table: descending radius',
		header,
		planets,
		reversed( planetsAscRadius ),
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(1)' ).click().click();
		}
	);
	tableTest(
		'Sorting multiple columns by passing sort list',
		header,
		simple,
		simpleAsc,
		function ( $table ) {
			$table.tablesorter(
				{ sortList: [
					{ 0: 'asc' },
					{ 1: 'asc' }
				] }
			);
		}
	);
	tableTest(
		'Sorting multiple columns by programmatically triggering sort()',
		header,
		simple,
		simpleDescasc,
		function ( $table ) {
			$table.tablesorter();
			$table.data( 'tablesorter' ).sort(
				[
					{ 0: 'desc' },
					{ 1: 'asc' }
				]
			);
		}
	);
	tableTest(
		'Reset to initial sorting by triggering sort() without any parameters',
		header,
		simple,
		simpleAsc,
		function ( $table ) {
			$table.tablesorter(
				{ sortList: [
					{ 0: 'asc' },
					{ 1: 'asc' }
				] }
			);
			$table.data( 'tablesorter' ).sort(
				[
					{ 0: 'desc' },
					{ 1: 'asc' }
				]
			);
			$table.data( 'tablesorter' ).sort();
		}
	);
	tableTest(
		'Sort via click event after having initialized the tablesorter with initial sorting',
		header,
		simple,
		simpleDescasc,
		function ( $table ) {
			$table.tablesorter(
				{ sortList: [ { 0: 'asc' }, { 1: 'asc' } ] }
			);
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest(
		'Multi-sort via click event after having initialized the tablesorter with initial sorting',
		header,
		simple,
		simpleAsc,
		function ( $table ) {
			var event;
			$table.tablesorter(
				{ sortList: [ { 0: 'desc' }, { 1: 'desc' } ] }
			);
			$table.find( '.headerSort:eq(0)' ).click();

			// Pretend to click while pressing the multi-sort key
			event = $.Event( 'click' );
			event[ $table.data( 'tablesorter' ).config.sortMultiSortKey ] = true;
			$table.find( '.headerSort:eq(1)' ).trigger( event );
		}
	);
	QUnit.test( 'Reset sorting making table appear unsorted', function ( assert ) {
		var $table = tableCreate( header, simple );
		$table.tablesorter(
			{ sortList: [
				{ 0: 'desc' },
				{ 1: 'asc' }
			] }
		);
		$table.data( 'tablesorter' ).sort( [] );

		assert.equal(
			$table.find( 'th.headerSortUp' ).length + $table.find( 'th.headerSortDown' ).length,
			0,
			'No sort specific sort classes addign to header cells'
		);

		assert.equal(
			$table.find( 'th' ).first().attr( 'title' ),
			mw.msg( 'sort-ascending' ),
			'First header cell has default title'
		);

		assert.equal(
			$table.find( 'th' ).first().attr( 'title' ),
			$table.find( 'th' ).last().attr( 'title' ),
			'Both header cells\' titles match'
		);
	} );

	// Sorting with colspans

	tableTest( 'Sorting with colspanned headers: spanned column',
		header4,
		colspanInitial,
		[ aaa1, aab5, abc3, bbc2, caa4 ],
		function ( $table ) {
			// Make colspanned header for test
			$table.find( 'tr:eq(0) th:eq(1), tr:eq(0) th:eq(2)' ).remove();
			$table.find( 'tr:eq(0) th:eq(0)' ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest( 'Sorting with colspanned headers: sort spanned column twice',
		header4,
		colspanInitial,
		[ caa4, bbc2, abc3, aab5, aaa1 ],
		function ( $table ) {
			// Make colspanned header for test
			$table.find( 'tr:eq(0) th:eq(1), tr:eq(0) th:eq(2)' ).remove();
			$table.find( 'tr:eq(0) th:eq(0)' ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest( 'Sorting with colspanned headers: subsequent column',
		header4,
		colspanInitial,
		[ aaa1, bbc2, abc3, caa4, aab5 ],
		function ( $table ) {
			// Make colspanned header for test
			$table.find( 'tr:eq(0) th:eq(1), tr:eq(0) th:eq(2)' ).remove();
			$table.find( 'tr:eq(0) th:eq(0)' ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(1)' ).click();
		}
	);
	tableTest( 'Sorting with colspanned headers: sort subsequent column twice',
		header4,
		colspanInitial,
		[ aab5, caa4, abc3, bbc2, aaa1 ],
		function ( $table ) {
			// Make colspanned header for test
			$table.find( 'tr:eq(0) th:eq(1), tr:eq(0) th:eq(2)' ).remove();
			$table.find( 'tr:eq(0) th:eq(0)' ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(1)' ).click();
			$table.find( '.headerSort:eq(1)' ).click();
		}
	);

	QUnit.test( 'Basic planet table: one unsortable column', function ( assert ) {
		var $table = tableCreate( header, planets ),
			$cell;
		$table.find( 'tr:eq(0) > th:eq(0)' ).addClass( 'unsortable' );

		$table.tablesorter();
		$table.find( 'tr:eq(0) > th:eq(0)' ).click();

		assert.deepEqual(
			tableExtract( $table ),
			planets,
			'table not sorted'
		);

		$cell = $table.find( 'tr:eq(0) > th:eq(0)' );
		$table.find( 'tr:eq(0) > th:eq(1)' ).click();

		assert.equal(
			$cell.hasClass( 'headerSortUp' ) || $cell.hasClass( 'headerSortDown' ),
			false,
			'after sort: no class headerSortUp or headerSortDown'
		);

		assert.equal(
			$cell.attr( 'title' ),
			undefined,
			'after sort: no title tag added'
		);

	} );

	// Regression tests!
	tableTest(
		'T30775: German-style (dmy) short numeric dates',
		[ 'Date' ],
		[
			// German-style dates are day-month-year
			[ '11.11.2011' ],
			[ '01.11.2011' ],
			[ '02.10.2011' ],
			[ '03.08.2011' ],
			[ '09.11.2011' ]
		],
		[
			// Sorted by ascending date
			[ '03.08.2011' ],
			[ '02.10.2011' ],
			[ '01.11.2011' ],
			[ '09.11.2011' ],
			[ '11.11.2011' ]
		],
		function ( $table ) {
			mw.config.set( 'wgDefaultDateFormat', 'dmy' );
			mw.config.set( 'wgPageContentLanguage', 'de' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest(
		'T30775: American-style (mdy) short numeric dates',
		[ 'Date' ],
		[
			// American-style dates are month-day-year
			[ '11.11.2011' ],
			[ '01.11.2011' ],
			[ '02.10.2011' ],
			[ '03.08.2011' ],
			[ '09.11.2011' ]
		],
		[
			// Sorted by ascending date
			[ '01.11.2011' ],
			[ '02.10.2011' ],
			[ '03.08.2011' ],
			[ '09.11.2011' ],
			[ '11.11.2011' ]
		],
		function ( $table ) {
			mw.config.set( 'wgDefaultDateFormat', 'mdy' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest(
		'T19141: IPv4 address sorting',
		[ 'IP' ],
		ipv4,
		ipv4Sorted,
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest(
		'T19141: IPv4 address sorting (reverse)',
		[ 'IP' ],
		ipv4,
		reversed( ipv4Sorted ),
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click().click();
		}
	);

	tableTest(
		'Accented Characters with custom collation',
		[ 'Name' ],
		umlautWords,
		umlautWordsSorted,
		function ( $table ) {
			mw.config.set( 'tableSorterCollation', {
				ä: 'ae',
				ö: 'oe',
				ß: 'ss',
				ü: 'ue'
			} );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest(
		'Digraphs with custom collation',
		[ 'City' ],
		digraphWords,
		digraphWordsSorted,
		function ( $table ) {
			mw.config.set( 'tableSorterCollation', {
				lj: 'lzzzz',
				nj: 'nzzzz'
			} );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	QUnit.test( 'Rowspan not exploded on init', function ( assert ) {
		var $table = tableCreate( header, planets );

		// Modify the table to have a multiple-row-spanning cell:
		// - Remove 2nd cell of 4th row, and, 2nd cell or 5th row.
		$table.find( 'tr:eq(3) td:eq(1), tr:eq(4) td:eq(1)' ).remove();
		// - Set rowspan for 2nd cell of 3rd row to 3.
		//   This covers the removed cell in the 4th and 5th row.
		$table.find( 'tr:eq(2) td:eq(1)' ).attr( 'rowspan', '3' );

		$table.tablesorter();

		assert.equal(
			$table.find( 'tr:eq(2) td:eq(1)' ).prop( 'rowSpan' ),
			3,
			'Rowspan not exploded'
		);
	} );

	planetsRowspan = [
		[ 'Earth', '6051.8' ],
		jupiter,
		[ 'Mars', '6051.8' ],
		mercury,
		saturn,
		venus
	];
	planetsRowspanII = [ jupiter, mercury, saturn, venus, [ 'Venus', '6371.0' ], [ 'Venus', '3390.0' ] ];

	tableTest(
		'Basic planet table: same value for multiple rows via rowspan',
		header,
		planets,
		planetsRowspan,
		function ( $table ) {
			// Modify the table to have a multiple-row-spanning cell:
			// - Remove 2nd cell of 4th row, and, 2nd cell or 5th row.
			$table.find( 'tr:eq(3) td:eq(1), tr:eq(4) td:eq(1)' ).remove();
			// - Set rowspan for 2nd cell of 3rd row to 3.
			//   This covers the removed cell in the 4th and 5th row.
			$table.find( 'tr:eq(2) td:eq(1)' ).attr( 'rowspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);
	tableTest(
		'Basic planet table: same value for multiple rows via rowspan (sorting initially)',
		header,
		planets,
		planetsRowspan,
		function ( $table ) {
			// Modify the table to have a multiple-row-spanning cell:
			// - Remove 2nd cell of 4th row, and, 2nd cell or 5th row.
			$table.find( 'tr:eq(3) td:eq(1), tr:eq(4) td:eq(1)' ).remove();
			// - Set rowspan for 2nd cell of 3rd row to 3.
			//   This covers the removed cell in the 4th and 5th row.
			$table.find( 'tr:eq(2) td:eq(1)' ).attr( 'rowspan', '3' );

			$table.tablesorter( { sortList: [
				{ 0: 'asc' }
			] } );
		}
	);
	tableTest(
		'Basic planet table: Same value for multiple rows via rowspan II',
		header,
		planets,
		planetsRowspanII,
		function ( $table ) {
			// Modify the table to have a multiple-row-spanning cell:
			// - Remove 1st cell of 4th row, and, 1st cell or 5th row.
			$table.find( 'tr:eq(3) td:eq(0), tr:eq(4) td:eq(0)' ).remove();
			// - Set rowspan for 1st cell of 3rd row to 3.
			//   This covers the removed cell in the 4th and 5th row.
			$table.find( 'tr:eq(2) td:eq(0)' ).attr( 'rowspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest(
		'Complex date parsing I',
		[ 'date' ],
		complexMDYDates,
		complexMDYSorted,
		function ( $table ) {
			mw.config.set( 'wgDefaultDateFormat', 'mdy' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest(
		'Currency parsing I',
		[ 'currency' ],
		currencyUnsorted,
		currencySorted,
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	planetsAscNameLegacy = planetsAscName.slice( 0 );
	planetsAscNameLegacy[ 4 ] = planetsAscNameLegacy[ 5 ];
	planetsAscNameLegacy.pop();

	tableTest(
		'Legacy compat with .sortbottom',
		header,
		planets,
		planetsAscNameLegacy,
		function ( $table ) {
			$table.find( 'tr:last' ).addClass( 'sortbottom' );
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	QUnit.test( 'Test detection routine', function ( assert ) {
		var $table;
		$table = $(
			'<table class="sortable">' +
				'<caption>CAPTION</caption>' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td>1</td></tr>' +
				'<tr class="sortbottom"><td>text</td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		$table.find( '.headerSort:eq(0)' ).click();

		assert.equal(
			$table.data( 'tablesorter' ).config.parsers[ 0 ].id,
			'number',
			'Correctly detected column content skipping sortbottom'
		);
	} );

	/** FIXME: the diff output is not very readeable. */
	QUnit.test( 'T34047 - caption must be before thead', function ( assert ) {
		var $table;
		$table = $(
			'<table class="sortable">' +
				'<caption>CAPTION</caption>' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td>A</td></tr>' +
				'<tr><td>B</td></tr>' +
				'<tr class="sortbottom"><td>TFOOT</td></tr>' +
				'</table>'
		);
		$table.tablesorter();

		assert.equal(
			$table.children().get( 0 ).nodeName,
			'CAPTION',
			'First element after <thead> must be <caption> (T34047)'
		);
	} );

	QUnit.test( 'data-sort-value attribute, when available, should override sorting position', function ( assert ) {
		var $table, data;

		// Example 1: All cells except one cell without data-sort-value,
		// which should be sorted at it's text content value.
		$table = $(
			'<table class="sortable"><thead><tr><th>Data</th></tr></thead>' +
				'<tbody>' +
				'<tr><td>Cheetah</td></tr>' +
				'<tr><td data-sort-value="Apple">Bird</td></tr>' +
				'<tr><td data-sort-value="Bananna">Ferret</td></tr>' +
				'<tr><td data-sort-value="Drupe">Elephant</td></tr>' +
				'<tr><td data-sort-value="Cherry">Dolphin</td></tr>' +
				'</tbody></table>'
		);
		$table.tablesorter().find( '.headerSort:eq(0)' ).click();

		data = [];
		$table.find( 'tbody > tr' ).each( function ( i, tr ) {
			$( tr ).find( 'td' ).each( function ( i, td ) {
				data.push( {
					data: $( td ).data( 'sortValue' ),
					text: $( td ).text()
				} );
			} );
		} );

		assert.deepEqual( data, [
			{
				data: 'Apple',
				text: 'Bird'
			},
			{
				data: 'Bananna',
				text: 'Ferret'
			},
			{
				data: undefined,
				text: 'Cheetah'
			},
			{
				data: 'Cherry',
				text: 'Dolphin'
			},
			{
				data: 'Drupe',
				text: 'Elephant'
			}
		], 'Order matches expected order (based on data-sort-value attribute values)' );

		// Example 2
		$table = $(
			'<table class="sortable"><thead><tr><th>Data</th></tr></thead>' +
				'<tbody>' +
				'<tr><td>D</td></tr>' +
				'<tr><td data-sort-value="E">A</td></tr>' +
				'<tr><td>B</td></tr>' +
				'<tr><td>G</td></tr>' +
				'<tr><td data-sort-value="F">C</td></tr>' +
				'</tbody></table>'
		);
		$table.tablesorter().find( '.headerSort:eq(0)' ).click();

		data = [];
		$table.find( 'tbody > tr' ).each( function ( i, tr ) {
			$( tr ).find( 'td' ).each( function ( i, td ) {
				data.push( {
					data: $( td ).data( 'sortValue' ),
					text: $( td ).text()
				} );
			} );
		} );

		assert.deepEqual( data, [
			{
				data: undefined,
				text: 'B'
			},
			{
				data: undefined,
				text: 'D'
			},
			{
				data: 'E',
				text: 'A'
			},
			{
				data: 'F',
				text: 'C'
			},
			{
				data: undefined,
				text: 'G'
			}
		], 'Order matches expected order (based on data-sort-value attribute values)' );

		// Example 3: Test that live changes are used from data-sort-value,
		// even if they change after the tablesorter is constructed (T40152).
		$table = $(
			'<table class="sortable"><thead><tr><th>Data</th></tr></thead>' +
				'<tbody>' +
				'<tr><td>D</td></tr>' +
				'<tr><td data-sort-value="1">A</td></tr>' +
				'<tr><td>B</td></tr>' +
				'<tr><td data-sort-value="2">G</td></tr>' +
				'<tr><td>C</td></tr>' +
				'</tbody></table>'
		);
		// initialize table sorter and sort once
		$table
			.tablesorter()
			.find( '.headerSort:eq(0)' ).click();

		// Change the sortValue data properties (T40152)
		// - change data
		$table.find( 'td:contains(A)' ).data( 'sortValue', 3 );
		// - add data
		$table.find( 'td:contains(B)' ).data( 'sortValue', 1 );
		// - remove data, bring back attribute: 2
		$table.find( 'td:contains(G)' ).removeData( 'sortValue' );

		// Now sort again (twice, so it is back at Ascending)
		$table.find( '.headerSort:eq(0)' ).click();
		$table.find( '.headerSort:eq(0)' ).click();

		data = [];
		$table.find( 'tbody > tr' ).each( function ( i, tr ) {
			$( tr ).find( 'td' ).each( function ( i, td ) {
				data.push( {
					data: $( td ).data( 'sortValue' ),
					text: $( td ).text()
				} );
			} );
		} );

		assert.deepEqual( data, [
			{
				data: 1,
				text: 'B'
			},
			{
				data: 2,
				text: 'G'
			},
			{
				data: 3,
				text: 'A'
			},
			{
				data: undefined,
				text: 'C'
			},
			{
				data: undefined,
				text: 'D'
			}
		], 'Order matches expected order, using the current sortValue in $.data()' );

	} );

	tableTest( 'T10115: sort numbers with commas (ascending)',
		[ 'Numbers' ], numbers, numbersAsc,
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest( 'T10115: sort numbers with commas (descending)',
		[ 'Numbers' ], numbers, reversed( numbersAsc ),
		function ( $table ) {
			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click().click();
		}
	);
	// TODO add numbers sorting tests for T10115 with a different language

	QUnit.test( 'T34888 - Tables inside a tableheader cell', function ( assert ) {
		var $table;
		$table = $(
			'<table class="sortable" id="mw-bug-32888">' +
				'<tr><th>header<table id="mw-bug-32888-2">' +
				'<tr><th>1</th><th>2</th></tr>' +
				'</table></th></tr>' +
				'<tr><td>A</td></tr>' +
				'<tr><td>B</td></tr>' +
				'</table>'
		);
		$table.tablesorter();

		assert.equal(
			$table.find( '> thead:eq(0) > tr > th.headerSort' ).length,
			1,
			'Child tables inside a headercell should not interfere with sortable headers (T34888)'
		);
		assert.equal(
			$( '#mw-bug-32888-2' ).find( 'th.headerSort' ).length,
			0,
			'The headers of child tables inside a headercell should not be sortable themselves (T34888)'
		);
	} );

	tableTest(
		'Correct date sorting I',
		[ 'date' ],
		correctDateSorting1,
		correctDateSortingSorted1,
		function ( $table ) {
			mw.config.set( 'wgDefaultDateFormat', 'mdy' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest(
		'Correct date sorting II',
		[ 'date' ],
		correctDateSorting2,
		correctDateSortingSorted2,
		function ( $table ) {
			mw.config.set( 'wgDefaultDateFormat', 'dmy' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	tableTest(
		'ISO date sorting',
		[ 'isoDate' ],
		isoDateSorting,
		isoDateSortingSorted,
		function ( $table ) {
			mw.config.set( 'wgDefaultDateFormat', 'dmy' );

			$table.tablesorter();
			$table.find( '.headerSort:eq(0)' ).click();
		}
	);

	QUnit.test( 'Sorting images using alt text', function ( assert ) {
		var $table = $(
			'<table class="sortable">' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td><img alt="2"/></td></tr>' +
				'<tr><td>1</td></tr>' +
				'</table>'
		);
		$table.tablesorter().find( '.headerSort:eq(0)' ).click();

		assert.equal(
			$table.find( 'td' ).first().text(),
			'1',
			'Applied correct sorting order'
		);
	} );

	QUnit.test( 'Sorting images using alt text (complex)', function ( assert ) {
		var $table = $(
			'<table class="sortable">' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td><img alt="D" />A</td></tr>' +
				'<tr><td>CC</td></tr>' +
				'<tr><td><a><img alt="A" /></a>F</tr>' +
				'<tr><td><img alt="A" /><strong>E</strong></tr>' +
				'<tr><td><strong><img alt="A" />D</strong></tr>' +
				'<tr><td><img alt="A" />C</tr>' +
				'</table>'
		);
		$table.tablesorter().find( '.headerSort:eq(0)' ).click();

		assert.equal(
			$table.find( 'td' ).text(),
			'CDEFCCA',
			'Applied correct sorting order'
		);
	} );

	QUnit.test( 'Sorting images using alt text (with format autodetection)', function ( assert ) {
		var $table = $(
			'<table class="sortable">' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td><img alt="1" />7</td></tr>' +
				'<tr><td>1<img alt="6" /></td></tr>' +
				'<tr><td>5</td></tr>' +
				'<tr><td>4</td></tr>' +
				'</table>'
		);
		$table.tablesorter().find( '.headerSort:eq(0)' ).click();

		assert.equal(
			$table.find( 'td' ).text(),
			'4517',
			'Applied correct sorting order'
		);
	} );

	QUnit.test( 'T40911 - The row with the largest amount of columns should receive the sort indicators', function ( assert ) {
		var $table = $(
			'<table class="sortable">' +
				'<thead>' +
				'<tr><th rowspan="2" id="A1">A1</th><th colspan="2">B2a</th></tr>' +
				'<tr><th id="B2b">B2b</th><th id="C2b">C2b</th></tr>' +
				'</thead>' +
				'<tr><td>A</td><td>Aa</td><td>Ab</td></tr>' +
				'<tr><td>B</td><td>Ba</td><td>Bb</td></tr>' +
				'</table>'
		);
		$table.tablesorter();

		assert.equal(
			$table.find( '#A1' ).attr( 'class' ),
			'headerSort',
			'The first column of the first row should be sortable'
		);
		assert.equal(
			$table.find( '#B2b' ).attr( 'class' ),
			'headerSort',
			'The th element of the 2nd row of the 2nd column should be sortable'
		);
		assert.equal(
			$table.find( '#C2b' ).attr( 'class' ),
			'headerSort',
			'The th element of the 2nd row of the 3rd column should be sortable'
		);
	} );

	QUnit.test( 'rowspans in table headers should prefer the last row when rows are equal in length', function ( assert ) {
		var $table = $(
			'<table class="sortable">' +
				'<thead>' +
				'<tr><th rowspan="2" id="A1">A1</th><th>B2a</th></tr>' +
				'<tr><th id="B2b">B2b</th></tr>' +
				'</thead>' +
				'<tr><td>A</td><td>Aa</td></tr>' +
				'<tr><td>B</td><td>Ba</td></tr>' +
				'</table>'
		);
		$table.tablesorter();

		assert.equal(
			$table.find( '#A1' ).attr( 'class' ),
			'headerSort',
			'The first column of the first row should be sortable'
		);
		assert.equal(
			$table.find( '#B2b' ).attr( 'class' ),
			'headerSort',
			'The th element of the 2nd row of the 2nd column should be sortable'
		);
	} );

	QUnit.test( 'holes in the table headers should not throw JS errors', function ( assert ) {
		var $table = $(
			'<table class="sortable">' +
				'<thead>' +
				'<tr><th id="A1">A1</th><th>B1</th><th id="C1" rowspan="2">C1</th></tr>' +
				'<tr><th id="A2">A2</th></tr>' +
				'</thead>' +
				'<tr><td>A</td><td>Aa</td><td>Aaa</td></tr>' +
				'<tr><td>B</td><td>Ba</td><td>Bbb</td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		assert.equal( $table.find( '#A2' ).data( 'headerIndex' ),
			undefined,
			'A2 should not be a sort header'
		);
		assert.equal( $table.find( '#C1' ).data( 'headerIndex' ),
			2,
			'C1 should be a sort header'
		);
	} );

	// T55527
	QUnit.test( 'td cells in thead should not be taken into account for longest row calculation', function ( assert ) {
		var $table = $(
			'<table class="sortable">' +
				'<thead>' +
				'<tr><th id="A1">A1</th><th>B1</th><td id="C1">C1</td></tr>' +
				'<tr><th id="A2">A2</th><th>B2</th><th id="C2">C2</th></tr>' +
				'</thead>' +
				'</table>'
		);
		$table.tablesorter();
		assert.equal( $table.find( '#C2' ).data( 'headerIndex' ),
			2,
			'C2 should be a sort header'
		);
		assert.equal( $table.find( '#C1' ).data( 'headerIndex' ),
			undefined,
			'C1 should not be a sort header'
		);
	} );

	// T43889 - exploding rowspans in more complex cases
	tableTestHTML(
		'Rowspan exploding with row headers',
		'<table class="sortable">' +
			'<thead><tr><th id="sortme">n</th><th>foo</th><th>bar</th><th>baz</th></tr></thead>' +
			'<tbody>' +
			'<tr><td>1</td><th rowspan="2">foo</th><td rowspan="2">bar</td><td>baz</td></tr>' +
			'<tr><td>2</td><td>baz</td></tr>' +
			'</tbody></table>',
		[
			[ '1', 'foo', 'bar', 'baz' ],
			[ '2', 'foo', 'bar', 'baz' ]
		]
	);

	// T55211 - exploding rowspans in more complex cases
	QUnit.test(
		'Rowspan exploding with row headers and colspans', function ( assert ) {
			var $table = $( '<table class="sortable">' +
				'<thead><tr><th rowspan="2">n</th><th colspan="2">foo</th><th rowspan="2">baz</th></tr>' +
				'<tr><th>foo</th><th>bar</th></tr></thead>' +
				'<tbody>' +
				'<tr><td>1</td><td>foo</td><td>bar</td><td>baz</td></tr>' +
				'<tr><td>2</td><td>foo</td><td>bar</td><td>baz</td></tr>' +
				'</tbody></table>' );

			$table.tablesorter();
			assert.equal( $table.find( 'tr:eq(1) th:eq(1)' ).data( 'headerIndex' ),
				2,
				'Incorrect index of sort header'
			);
		}
	);

	tableTestHTML(
		'Rowspan exploding with colspanned cells',
		'<table class="sortable">' +
			'<thead><tr><th id="sortme">n</th><th>foo</th><th>bar</th><th>baz</th></tr></thead>' +
			'<tbody>' +
			'<tr><td>1</td><td>foo</td><td>bar</td><td rowspan="2">baz</td></tr>' +
			'<tr><td>2</td><td colspan="2">foobar</td></tr>' +
			'</tbody></table>',
		[
			[ '1', 'foo', 'bar', 'baz' ],
			[ '2', 'foobar', 'baz' ]
		]
	);

	tableTestHTML(
		'Rowspan exploding with colspanned cells (2)',
		'<table class="sortable">' +
			'<thead><tr><th>n</th><th>foo</th><th>bar</th><th>baz</th><th id="sortme">n2</th></tr></thead>' +
			'<tbody>' +
			'<tr><td>1</td><td>foo</td><td>bar</td><td rowspan="2">baz</td><td>2</td></tr>' +
			'<tr><td>2</td><td colspan="2">foobar</td><td>1</td></tr>' +
			'</tbody></table>',
		[
			[ '2', 'foobar', 'baz', '1' ],
			[ '1', 'foo', 'bar', 'baz', '2' ]
		]
	);

	tableTestHTML(
		'Rowspan exploding with rightmost rows spanning most',
		'<table class="sortable">' +
			'<thead><tr><th id="sortme">n</th><th>foo</th><th>bar</th></tr></thead>' +
			'<tbody>' +
			'<tr><td>1</td><td rowspan="2">foo</td><td rowspan="4">bar</td></tr>' +
			'<tr><td>2</td></tr>' +
			'<tr><td>3</td><td rowspan="2">foo</td></tr>' +
			'<tr><td>4</td></tr>' +
			'</tbody></table>',
		[
			[ '1', 'foo', 'bar' ],
			[ '2', 'foo', 'bar' ],
			[ '3', 'foo', 'bar' ],
			[ '4', 'foo', 'bar' ]
		]
	);

	tableTestHTML(
		'Rowspan exploding with rightmost rows spanning most (2)',
		'<table class="sortable">' +
			'<thead><tr><th id="sortme">n</th><th>foo</th><th>bar</th><th>baz</th></tr></thead>' +
			'<tbody>' +
			'<tr><td>1</td><td rowspan="2">foo</td><td rowspan="4">bar</td><td>baz</td></tr>' +
			'<tr><td>2</td><td>baz</td></tr>' +
			'<tr><td>3</td><td rowspan="2">foo</td><td>baz</td></tr>' +
			'<tr><td>4</td><td>baz</td></tr>' +
			'</tbody></table>',
		[
			[ '1', 'foo', 'bar', 'baz' ],
			[ '2', 'foo', 'bar', 'baz' ],
			[ '3', 'foo', 'bar', 'baz' ],
			[ '4', 'foo', 'bar', 'baz' ]
		]
	);

	tableTestHTML(
		'Rowspan exploding with row-and-colspanned cells',
		'<table class="sortable">' +
			'<thead><tr><th id="sortme">n</th><th>foo1</th><th>foo2</th><th>bar</th><th>baz</th></tr></thead>' +
			'<tbody>' +
			'<tr><td>1</td><td rowspan="2">foo1</td><td rowspan="2">foo2</td><td rowspan="4">bar</td><td>baz</td></tr>' +
			'<tr><td>2</td><td>baz</td></tr>' +
			'<tr><td>3</td><td colspan="2" rowspan="2">foo</td><td>baz</td></tr>' +
			'<tr><td>4</td><td>baz</td></tr>' +
			'</tbody></table>',
		[
			[ '1', 'foo1', 'foo2', 'bar', 'baz' ],
			[ '2', 'foo1', 'foo2', 'bar', 'baz' ],
			[ '3', 'foo', 'bar', 'baz' ],
			[ '4', 'foo', 'bar', 'baz' ]
		]
	);

	tableTestHTML(
		'Rowspan exploding with uneven rowspan layout',
		'<table class="sortable">' +
			'<thead><tr><th id="sortme">n</th><th>foo1</th><th>foo2</th><th>foo3</th><th>bar</th><th>baz</th></tr></thead>' +
			'<tbody>' +
			'<tr><td>1</td><td rowspan="2">foo1</td><td rowspan="2">foo2</td><td rowspan="2">foo3</td><td>bar</td><td>baz</td></tr>' +
			'<tr><td>2</td><td rowspan="3">bar</td><td>baz</td></tr>' +
			'<tr><td>3</td><td rowspan="2">foo1</td><td rowspan="2">foo2</td><td rowspan="2">foo3</td><td>baz</td></tr>' +
			'<tr><td>4</td><td>baz</td></tr>' +
			'</tbody></table>',
		[
			[ '1', 'foo1', 'foo2', 'foo3', 'bar', 'baz' ],
			[ '2', 'foo1', 'foo2', 'foo3', 'bar', 'baz' ],
			[ '3', 'foo1', 'foo2', 'foo3', 'bar', 'baz' ],
			[ '4', 'foo1', 'foo2', 'foo3', 'bar', 'baz' ]
		]
	);

	QUnit.test( 'T105731 - incomplete rows in table body', function ( assert ) {
		var $table, parsers;
		$table = $(
			'<table class="sortable">' +
				'<tr><th>A</th><th>B</th></tr>' +
				'<tr><td>3</td></tr>' +
				'<tr><td>1</td><td>2</td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		$table.find( '.headerSort:eq(0)' ).click();
		// now the first row have 2 columns
		$table.find( '.headerSort:eq(1)' ).click();

		parsers = $table.data( 'tablesorter' ).config.parsers;

		assert.equal(
			parsers.length,
			2,
			'detectParserForColumn() detect 2 parsers'
		);

		assert.equal(
			parsers[ 1 ].id,
			'number',
			'detectParserForColumn() detect parser.id "number" for second column'
		);

		assert.equal(
			parsers[ 1 ].format( $table.find( 'tbody > tr > td:eq(1)' ).text() ),
			-Infinity,
			'empty cell is sorted as number -Infinity'
		);
	} );

	QUnit.test( 'bug T114721 - use of expand-child class', function ( assert ) {
		var $table, parsers;
		$table = $(
			'<table class="sortable">' +
				'<tr><th>A</th><th>B</th></tr>' +
				'<tr><td>b</td><td>4</td></tr>' +
				'<tr class="expand-child"><td colspan="2">some text follow b</td></tr>' +
				'<tr><td>a</td><td>2</td></tr>' +
				'<tr class="expand-child"><td colspan="2">some text follow a</td></tr>' +
				'<tr class="expand-child"><td colspan="2">more text</td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		$table.find( '.headerSort:eq(0)' ).click();

		assert.deepEqual(
			tableExtract( $table ),
			[
				[ 'a', '2' ],
				[ 'some text follow a' ],
				[ 'more text' ],
				[ 'b', '4' ],
				[ 'some text follow b' ]
			],
			'row with expand-child class follow above row'
		);

		parsers = $table.data( 'tablesorter' ).config.parsers;
		assert.equal(
			parsers[ 1 ].id,
			'number',
			'detectParserForColumn() detect parser.id "number" for second column'
		);
	} );

}( jQuery, mediaWiki ) );
