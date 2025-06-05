QUnit.module( 'jquery.tablesorter', QUnit.newMwEnvironment( {
	beforeEach: function () {
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
	afterEach: function () {
		mw.language.months = this.liveMonths;
	},
	config: {
		wgDefaultDateFormat: 'dmy',
		wgSeparatorTransformTable: [ '', '' ],
		wgDigitTransformTable: [ '', '' ],
		wgPageViewLanguage: 'en'
	}
} ), () => {
	/**
	 * Create an HTML table from an array of row arrays containing text strings.
	 * First row will be header row. No fancy rowspan/colspan stuff.
	 *
	 * @param {string[]} header
	 * @param {string[][]} data
	 * @return {jQuery}
	 */
	function tableCreate( header, data ) {
		const $table = $( '<table class="sortable"><thead></thead><tbody></tbody></table>' );
		const $thead = $table.find( 'thead' );
		const $tbody = $table.find( 'tbody' );
		let $tr = $( '<tr>' );

		header.forEach( ( str ) => {
			const $th = $( '<th>' );
			$th.text( str ).appendTo( $tr );
		} );
		$tr.appendTo( $thead );

		for ( let i = 0; i < data.length; i++ ) {
			$tr = $( '<tr>' );
			// eslint-disable-next-line no-loop-func
			data[ i ].forEach( ( str ) => {
				const $td = $( '<td>' );
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
		const data = [];

		$table.find( 'tbody tr' ).each( ( i, tr ) => {
			const row = [];
			$( tr ).find( 'td, th' ).each( ( j, td ) => {
				row.push( $( td ).text() );
			} );
			data.push( row );
		} );
		return data;
	}

	/**
	 * Run a table test by building a table with the given HTML,
	 * running some callback on it, then checking the results.
	 *
	 * @param {string} msg text to pass on to qunit for the comparison
	 * @param {string} html HTML to make the table
	 * @param {string[][]} expected Rows/cols to compare against at end
	 * @param {Function} callback Callback on $table before we compare
	 */
	function tableTestHTML( msg, html, expected, callback ) {
		QUnit.test( msg, ( assert ) => {
			const $table = $( html );

			// Let caller manipulate the table and setup sorting
			if ( callback ) {
				callback( $table );
			} else {
				$table.tablesorter();
				$table.find( '#sortme' ).trigger( 'click' );
			}

			// Table sorting is done synchronously; if it ever needs to change back
			// to asynchronous, we'll need a timeout or a callback here.
			const extracted = tableExtract( $table );
			assert.deepEqual( extracted, expected, msg );
		} );
	}

	function reversed( arr ) {
		// Clone array
		const arr2 = arr.slice( 0 );
		arr2.reverse();
		return arr2;
	}

	// Data set "planets"
	const planetHeader = [ 'Planet', 'Radius (km)' ];
	const mercury = [ 'Mercury', '2439.7' ];
	const venus = [ 'Venus', '6051.8' ];
	const earth = [ 'Earth', '6371.0' ];
	const mars = [ 'Mars', '3390.0' ];
	const jupiter = [ 'Jupiter', '69911' ];
	const saturn = [ 'Saturn', '58232' ];
	const planets = [ mercury, venus, earth, mars, jupiter, saturn ];
	const planetsAscName = [ earth, jupiter, mars, mercury, saturn, venus ];
	const planetsAscRadius = [ mercury, mars, venus, earth, saturn, jupiter ];
	const planetsTotal = [ [ 'total', '146395.5' ] ];

	// Data set "simple"
	const a1 = [ 'A', '1' ];
	const a2 = [ 'A', '2' ];
	const a3 = [ 'A', '3' ];
	const b1 = [ 'B', '1' ];
	const b2 = [ 'B', '2' ];
	const b3 = [ 'B', '3' ];
	const simple = [ a2, b3, a1, a3, b2, b1 ];
	const simpleAsc = [ a1, a2, a3, b1, b2, b3 ];
	const simpleDescasc = [ b1, b2, b3, a1, a2, a3 ];

	QUnit.test(
		'Planets: initial sort ascending by name',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter( { sortList: [
				{ 0: 'asc' }
			] } );

			assert.deepEqual( tableExtract( $table ), planetsAscName );
		}
	);
	QUnit.test(
		'Planets: initial sort descending by radius',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter( { sortList: [
				{ 1: 'desc' }
			] } );

			assert.deepEqual( tableExtract( $table ), reversed( planetsAscRadius ) );
		}
	);
	QUnit.test(
		'Planets: ascending by name',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsAscName );
		}
	);
	QUnit.test(
		'Planets: ascending by name (again)',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsAscName );
		}
	);
	QUnit.test(
		'Planets: ascending by name (multiple clicks)',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );
			$table.find( '.headerSort' ).eq( 1 ).trigger( 'click' );
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsAscName );
		}
	);
	QUnit.test(
		'Planets: descending by name',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), reversed( planetsAscName ) );
		}
	);
	QUnit.test(
		'Planets: return to initial sort',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' ).trigger( 'click' ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planets );
		}
	);
	QUnit.test(
		'Planets: ascending radius',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 1 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsAscRadius );
		}
	);
	QUnit.test(
		'Planets: descending radius',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 1 ).trigger( 'click' ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), reversed( planetsAscRadius ) );
		}
	);
	QUnit.test(
		'Sorting multiple columns by passing sort list',
		( assert ) => {
			const $table = tableCreate( planetHeader, simple );
			$table.tablesorter(
				{ sortList: [
					{ 0: 'asc' },
					{ 1: 'asc' }
				] }
			);

			assert.deepEqual( tableExtract( $table ), simpleAsc );
		}
	);
	QUnit.test(
		'Sorting multiple columns by programmatically triggering sort()',
		( assert ) => {
			const $table = tableCreate( planetHeader, simple );
			$table.tablesorter();
			$table.data( 'tablesorter' ).sort(
				[
					{ 0: 'desc' },
					{ 1: 'asc' }
				]
			);

			assert.deepEqual( tableExtract( $table ), simpleDescasc );
		}
	);
	QUnit.test(
		'Reset to initial sorting by triggering sort() without any parameters',
		( assert ) => {
			const $table = tableCreate( planetHeader, simple );
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

			assert.deepEqual( tableExtract( $table ), simpleAsc );
		}
	);
	QUnit.test(
		'Sort via click event after having initialized the tablesorter with initial sorting',
		( assert ) => {
			const $table = tableCreate( planetHeader, simple );
			$table.tablesorter(
				{ sortList: [ { 0: 'asc' }, { 1: 'asc' } ] }
			);
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), simpleDescasc );
		}
	);
	QUnit.test(
		'Multi-sort via click event after having initialized the tablesorter with initial sorting',
		( assert ) => {
			const $table = tableCreate( planetHeader, simple );
			$table.tablesorter(
				{ sortList: [ { 0: 'desc' }, { 1: 'desc' } ] }
			);

			// There are three sort orders, so cycle though
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			// Pretend to click while pressing the multi-sort key
			const event = $.Event( 'click' );
			event[ $table.data( 'tablesorter' ).config.sortMultiSortKey ] = true;
			$table.find( '.headerSort' ).eq( 1 ).trigger( event );

			assert.deepEqual( tableExtract( $table ), simpleAsc );
		}
	);
	QUnit.test( 'Reset sorting making table appear unsorted', ( assert ) => {
		const $table = tableCreate( planetHeader, simple );
		$table.tablesorter(
			{ sortList: [
				{ 0: 'desc' },
				{ 1: 'asc' }
			] }
		);
		$table.data( 'tablesorter' ).sort( [] );

		assert.strictEqual(
			$table.find( 'th.headerSortUp' ).length + $table.find( 'th.headerSortDown' ).length,
			0,
			'No sort specific sort classes addign to header cells'
		);

		assert.strictEqual(
			$table.find( 'th' ).first().attr( 'title' ),
			mw.msg( 'sort-ascending' ),
			'First header cell has default title'
		);

		assert.strictEqual(
			$table.find( 'th' ).first().attr( 'title' ),
			$table.find( 'th' ).last().attr( 'title' ),
			'Both header cells\' titles match'
		);
	} );

	// Sorting with colspans
	const header4 = [ 'column1a', 'column1b', 'column1c', 'column2' ];
	const aaa1 = [ 'A', 'A', 'A', '1' ];
	const aab5 = [ 'A', 'A', 'B', '5' ];
	const abc3 = [ 'A', 'B', 'C', '3' ];
	const bbc2 = [ 'B', 'B', 'C', '2' ];
	const caa4 = [ 'C', 'A', 'A', '4' ];
	const colspanInitial = [ aab5, aaa1, abc3, bbc2, caa4 ];
	QUnit.test( 'Sorting with colspanned headers: spanned column',
		( assert ) => {
			const $table = tableCreate( header4, colspanInitial );
			// Make colspanned header for test
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr th' ).eq( 0 ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [ aaa1, aab5, abc3, bbc2, caa4 ] );
		}
	);
	QUnit.test( 'Sorting with colspanned headers: sort spanned column twice',
		( assert ) => {
			const $table = tableCreate( header4, colspanInitial );
			// Make colspanned header for test
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr' ).eq( 0 ).find( 'th' ).eq( 0 ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [ caa4, bbc2, abc3, aab5, aaa1 ] );
		}
	);
	QUnit.test( 'Sorting with colspanned headers: subsequent column',
		( assert ) => {
			const $table = tableCreate( header4, colspanInitial );
			// Make colspanned header for test
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr th' ).eq( 0 ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 1 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [ aaa1, bbc2, abc3, caa4, aab5 ] );
		}
	);
	QUnit.test( 'Sorting with colspanned headers: sort subsequent column twice',
		( assert ) => {
			const $table = tableCreate( header4, colspanInitial );
			// Make colspanned header for test
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr th' ).eq( 1 ).remove();
			$table.find( 'tr th' ).eq( 0 ).attr( 'colspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 1 ).trigger( 'click' );
			$table.find( '.headerSort' ).eq( 1 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [ aab5, caa4, abc3, bbc2, aaa1 ] );
		}
	);

	QUnit.test( 'Basic planet table: one unsortable column', ( assert ) => {
		const $table = tableCreate( planetHeader, planets );
		$table.find( 'tr > th' ).eq( 0 ).addClass( 'unsortable' );

		$table.tablesorter();
		$table.find( 'tr > th' ).eq( 0 ).trigger( 'click' );

		assert.deepEqual(
			tableExtract( $table ),
			planets,
			'table not sorted'
		);

		const $cell = $table.find( 'tr > th' ).eq( 0 );
		$table.find( 'tr > th' ).eq( 1 ).trigger( 'click' );

		assert.false(
			// eslint-disable-next-line no-jquery/no-class-state
			$cell.hasClass( 'headerSortUp' ) || $cell.hasClass( 'headerSortDown' ),
			'after sort: no class headerSortUp or headerSortDown'
		);

		assert.strictEqual(
			$cell.attr( 'title' ),
			undefined,
			'after sort: no title tag added'
		);

	} );

	QUnit.test(
		'T30775: German-style (dmy) short numeric dates',
		( assert ) => {
			const $table = tableCreate( [ 'Date' ], [
				// German-style dates are day-month-year
				[ '11.11.2011' ],
				[ '01.11.2011' ],
				[ '02.10.2011' ],
				[ '03.08.2011' ],
				[ '09.11.2011' ]
			] );
			mw.config.set( 'wgDefaultDateFormat', 'dmy' );
			mw.config.set( 'wgPageViewLanguage', 'de' );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				// Sorted by ascending date
				[ '03.08.2011' ],
				[ '02.10.2011' ],
				[ '01.11.2011' ],
				[ '09.11.2011' ],
				[ '11.11.2011' ]
			] );
		}
	);

	QUnit.test(
		'T30775: American-style (mdy) short numeric dates',
		( assert ) => {
			const $table = tableCreate( [ 'Date' ], [
				// American-style dates are month-day-year
				[ '11.11.2011' ],
				[ '01.11.2011' ],
				[ '02.10.2011' ],
				[ '03.08.2011' ],
				[ '09.11.2011' ]
			] );
			mw.config.set( 'wgDefaultDateFormat', 'mdy' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				// Sorted by ascending date
				[ '01.11.2011' ],
				[ '02.10.2011' ],
				[ '03.08.2011' ],
				[ '09.11.2011' ],
				[ '11.11.2011' ]
			] );
		}
	);

	const ipv4 = [
		// Some randomly generated fake IPs
		[ '45.238.27.109' ],
		[ '44.172.9.22' ],
		[ '247.240.82.209' ],
		[ '204.204.132.158' ],
		[ '170.38.91.162' ],
		[ '197.219.164.9' ],
		[ '45.68.154.72' ],
		[ '182.195.149.80' ]
	];
	const ipv4Sorted = [
		// Sort order should go octet by octet
		[ '44.172.9.22' ],
		[ '45.68.154.72' ],
		[ '45.238.27.109' ],
		[ '170.38.91.162' ],
		[ '182.195.149.80' ],
		[ '197.219.164.9' ],
		[ '204.204.132.158' ],
		[ '247.240.82.209' ]
	];
	QUnit.test(
		'IPv4 address sorting (T19141)',
		( assert ) => {
			const $table = tableCreate( [ 'IP' ], ipv4 );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), ipv4Sorted );
		}
	);
	QUnit.test(
		'IPv4 address reverse sorting (T19141)',
		( assert ) => {
			const $table = tableCreate( [ 'IP' ], ipv4 );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), reversed( ipv4Sorted ) );
		}
	);

	const umlautWords = [
		[ 'Günther' ],
		[ 'Peter' ],
		[ 'Björn' ],
		[ 'ä' ],
		[ 'z' ],
		[ 'Bjorn' ],
		[ 'BjÖrn' ],
		[ 'apfel' ],
		[ 'Apfel' ],
		[ 'Äpfel' ],
		[ 'Strasse' ],
		[ 'Sträßschen' ]
	];
	const umlautWordsSortedEn = [
		[ 'ä' ],
		[ 'Äpfel' ],
		[ 'apfel' ],
		[ 'Apfel' ],
		[ 'Björn' ],
		[ 'BjÖrn' ],
		[ 'Bjorn' ],
		[ 'Günther' ],
		[ 'Peter' ],
		[ 'Sträßschen' ],
		[ 'Strasse' ],
		[ 'z' ]
	];
	const umlautWordsSortedSv = [
		[ 'apfel' ],
		[ 'Apfel' ],
		[ 'Bjorn' ],
		[ 'Björn' ],
		[ 'BjÖrn' ],
		[ 'Günther' ],
		[ 'Peter' ],
		[ 'Strasse' ],
		[ 'Sträßschen' ],
		[ 'z' ],
		[ 'ä' ], // ä sorts after z in Swedish
		[ 'Äpfel' ]
	];
	QUnit.test(
		'Accented Characters with custom collation',
		( assert ) => {
			const $table = tableCreate( [ 'Name' ], umlautWords );
			mw.config.set( 'tableSorterCollation', {
				ä: 'ae',
				ö: 'oe',
				ß: 'ss',
				ü: 'ue'
			} );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), umlautWordsSortedEn );
		}
	);
	QUnit.test(
		'Accented Characters Swedish locale',
		( assert ) => {
			const $table = tableCreate( [ 'Name' ], umlautWords );
			mw.config.set( 'wgPageViewLanguage', 'sv' );

			$table.tablesorter();
			// eslint-disable-next-line no-jquery/no-sizzle
			$table.find( '.headerSort:eq(0)' ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), umlautWordsSortedSv );
		}
	);
	QUnit.test(
		'Digraphs with custom collation',
		( assert ) => {
			const $table = tableCreate( [ 'City' ], [
				[ 'London' ],
				[ 'Ljubljana' ],
				[ 'Luxembourg' ],
				[ 'Njivice' ],
				[ 'Norwich' ],
				[ 'New York' ]
			] );
			mw.config.set( 'tableSorterCollation', {
				lj: 'lzzzz',
				nj: 'nzzzz'
			} );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				[ 'London' ],
				[ 'Luxembourg' ],
				[ 'Ljubljana' ],
				[ 'New York' ],
				[ 'Norwich' ],
				[ 'Njivice' ]
			] );
		}
	);

	QUnit.test( 'Rowspan not exploded on init', ( assert ) => {
		const $table = tableCreate( planetHeader, planets );

		// Modify the table to have a multiple-row-spanning cell:
		// - Remove 2nd cell of 4th row, and, 2nd cell or 5th row.
		$table.find( 'tr' ).eq( 3 ).find( 'td' ).eq( 1 ).remove();
		$table.find( 'tr' ).eq( 4 ).find( 'td' ).eq( 1 ).remove();
		// - Set rowspan for 2nd cell of 3rd row to 3.
		//   This covers the removed cell in the 4th and 5th row.
		$table.find( 'tr' ).eq( 2 ).find( 'td' ).eq( 1 ).attr( 'rowspan', '3' );

		$table.tablesorter();

		assert.strictEqual(
			$table.find( 'tr' ).eq( 2 ).find( 'td' ).eq( 1 ).prop( 'rowSpan' ),
			3,
			'Rowspan not exploded'
		);
	} );

	const planetsRowspan = [
		[ 'Earth', '6051.8' ],
		jupiter,
		[ 'Mars', '6051.8' ],
		mercury,
		saturn,
		venus
	];
	const planetsRowspanII = [ jupiter, mercury, saturn, venus, [ 'Venus', '6371.0' ], [ 'Venus', '3390.0' ] ];
	QUnit.test(
		'Basic planet table: same value for multiple rows via rowspan',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			// Modify the table to have a multiple-row-spanning cell:
			// - Remove 2nd cell of 4th row, and, 2nd cell or 5th row.
			$table.find( 'tr' ).eq( 3 ).find( 'td' ).eq( 1 ).remove();
			$table.find( 'tr' ).eq( 4 ).find( 'td' ).eq( 1 ).remove();
			// - Set rowspan for 2nd cell of 3rd row to 3.
			//   This covers the removed cell in the 4th and 5th row.
			$table.find( 'tr' ).eq( 2 ).find( 'td' ).eq( 1 ).attr( 'rowspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsRowspan );
		}
	);
	QUnit.test(
		'Basic planet table: same value for multiple rows via rowspan (sorting initially)',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			// Modify the table to have a multiple-row-spanning cell:
			// - Remove 2nd cell of 4th row, and, 2nd cell or 5th row.
			$table.find( 'tr' ).eq( 3 ).find( 'td' ).eq( 1 ).remove();
			$table.find( 'tr' ).eq( 4 ).find( 'td' ).eq( 1 ).remove();
			// - Set rowspan for 2nd cell of 3rd row to 3.
			//   This covers the removed cell in the 4th and 5th row.
			$table.find( 'tr' ).eq( 2 ).find( 'td' ).eq( 1 ).attr( 'rowspan', '3' );

			$table.tablesorter( { sortList: [
				{ 0: 'asc' }
			] } );

			assert.deepEqual( tableExtract( $table ), planetsRowspan );
		}
	);
	QUnit.test(
		'Basic planet table: Same value for multiple rows via rowspan II',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets );
			// Modify the table to have a multiple-row-spanning cell:
			// - Remove 1st cell of 4th row, and, 1st cell or 5th row.
			$table.find( 'tr' ).eq( 3 ).find( 'td' ).eq( 0 ).remove();
			$table.find( 'tr' ).eq( 4 ).find( 'td' ).eq( 0 ).remove();
			// - Set rowspan for 1st cell of 3rd row to 3.
			//   This covers the removed cell in the 4th and 5th row.
			$table.find( 'tr' ).eq( 2 ).find( 'td' ).eq( 0 ).attr( 'rowspan', '3' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsRowspanII );
		}
	);

	QUnit.test(
		'Complex date parsing I',
		( assert ) => {
			const $table = tableCreate( [ 'Date' ], [
				[ 'January, 19 2010' ],
				[ 'April 21 1991' ],
				[ '04 22 1991' ],
				[ '5.12.1990' ],
				[ 'December 12 \'10' ]
			] );
			mw.config.set( 'wgDefaultDateFormat', 'mdy' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				[ '5.12.1990' ],
				[ 'April 21 1991' ],
				[ '04 22 1991' ],
				[ 'January, 19 2010' ],
				[ 'December 12 \'10' ]
			] );
		}
	);

	QUnit.test(
		'Currency parsing I',
		( assert ) => {
			const $table = tableCreate( [ 'Currency' ], [
				[ '1.02 $' ],
				[ '$ 3.00' ],
				[ '€ 2,99' ],
				[ '$ 1.00' ],
				[ '$3.50' ],
				[ '$ 1.50' ],
				[ '€ 0.99' ]
			] );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				[ '€ 0.99' ],
				[ '$ 1.00' ],
				[ '1.02 $' ],
				[ '$ 1.50' ],
				[ '$ 3.00' ],
				[ '$3.50' ],
				// Commas sort after dots
				// Not intentional but test to detect changes
				[ '€ 2,99' ]
			] );
		}
	);

	QUnit.test(
		'Handling of .sortbottom',
		( assert ) => {
			const $table = tableCreate( planetHeader, planets.concat( planetsTotal ) );
			$table.find( 'tr' ).last().addClass( 'sortbottom' );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsAscName );
		}
	);

	QUnit.test(
		'Handling of .sorttop',
		( assert ) => {
			const $table = tableCreate( planetHeader, planetsTotal.concat( planets ) );
			$table.find( 'tbody > tr' ).first().addClass( 'sorttop' );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), planetsAscName );
		}
	);

	QUnit.test( 'Rowspan invalid value (T265503)', function ( assert ) {
		const rowspanText = 'Row 1 col 3, Row 2 col 3, row 3 col 3 (but there is no row 3)';
		const $table = $(
			'<table class="sortable">' +
				'<thead>' +
				'<tr><th>table heading 1</th><th>table heading 2</th><th>table heading 3</th></tr>' +
				'</thead>' +
				'<tr><td>Row 1 col 1</td><td>Row 1 col 2</td>' +
					'<td rowspan="3">' + rowspanText + '</td>' +
				'</tr>' +
				'<tr><td>Row 2 col 1</td><td>Row 2 col 2</td></tr>' +
				'</table>'
		);
		this.suppressWarnings(); // sort-rowspan-error

		$table.tablesorter();
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );
		assert.strictEqual(
			$table.find( 'tr' ).eq( 1 ).find( 'td' ).eq( 2 ).text(),
			rowspanText,
			'The invalid rowspan didn\'t throw an error and was setup correctly'
		);
	} );

	QUnit.test( 'Test sort buttons not added to .sorttop row', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<tr><th>Data</th></tr>' +
				'<tr class="sorttop"><th>2</th></tr>' +
				'<tr><td>1</td></tr>' +
				'<tr><td>1</td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		assert.strictEqual(
			$table.find( '.headerSort' ).eq( 0 ).text(),
			'Data',
			'Sort buttons are added to a header row without class sorttop'
		);
	} );

	QUnit.test( 'Test detection routine', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<caption>CAPTION</caption>' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td>1</td></tr>' +
				'<tr class="sortbottom"><td>text</td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		assert.strictEqual(
			$table.data( 'tablesorter' ).config.parsers[ 0 ].id,
			'number',
			'Correctly detected column content skipping sortbottom'
		);
	} );

	// FIXME: the diff output is not very readeable.
	QUnit.test( 'T34047 - caption must be before thead', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<caption>CAPTION</caption>' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td>A</td></tr>' +
				'<tr><td>B</td></tr>' +
				'<tr class="sortbottom"><td>TFOOT</td></tr>' +
				'</table>'
		);
		$table.tablesorter();

		assert.strictEqual(
			$table.children().get( 0 ).nodeName,
			'CAPTION',
			'First element after <thead> must be <caption> (T34047)'
		);
	} );

	QUnit.test( 'data-sort-value attribute, when available, should override sorting position', ( assert ) => {
		// Example 1: All cells except one cell without data-sort-value,
		// which should be sorted at it's text content value.
		let $table = $(
			'<table class="sortable"><thead><tr><th>Data</th></tr></thead>' +
				'<tbody>' +
				'<tr><td>Cheetah</td></tr>' +
				'<tr><td data-sort-value="Apple">Bird</td></tr>' +
				'<tr><td data-sort-value="Bananna">Ferret</td></tr>' +
				'<tr><td data-sort-value="Drupe">Elephant</td></tr>' +
				'<tr><td data-sort-value="Cherry">Dolphin</td></tr>' +
				'</tbody></table>'
		);
		$table.tablesorter().find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		let data = [];
		$table.find( 'tbody > tr' ).each( ( i, tr ) => {
			$( tr ).find( 'td' ).each( ( j, td ) => {
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
				'<tr><td><span data-sort-value="D">H</span></td></tr>' +
				'</tbody></table>'
		);
		$table.tablesorter().find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		data = [];
		$table.find( 'tbody > tr' ).each( ( i, tr ) => {
			$( tr ).find( 'td' ).each( ( j, td ) => {
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
				data: undefined,
				text: 'H'
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
		$table.tablesorter().find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		// Change the sortValue data properties (T40152)
		// - change data
		$table.find( 'td:contains(A)' ).data( 'sortValue', 3 );
		// - add data
		$table.find( 'td:contains(B)' ).data( 'sortValue', 1 );
		// - remove data, bring back attribute: 2
		$table.find( 'td:contains(G)' ).removeData( 'sortValue' );

		// Now sort again (three times, so it is back at Ascending)
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		data = [];
		$table.find( 'tbody > tr' ).each( ( i, tr ) => {
			$( tr ).find( 'td' ).each( ( j, td ) => {
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

	const numbers = [
		[ '12' ],
		[ '7' ],
		[ '13,000' ],
		[ '9' ],
		[ '14' ],
		[ '8.0' ]
	];
	const numbersAsc = [
		[ '7' ],
		[ '8.0' ],
		[ '9' ],
		[ '12' ],
		[ '14' ],
		[ '13,000' ]
	];
	QUnit.test( 'T10115: sort numbers with commas (ascending)',
		( assert ) => {
			const $table = tableCreate( [ 'Numbers' ], numbers );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), numbersAsc );
		}
	);

	QUnit.test( 'T10115: sort numbers with commas (descending)',
		( assert ) => {
			const $table = tableCreate( [ 'Numbers' ], numbers );
			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), reversed( numbersAsc ) );
		}
	);
	// TODO add numbers sorting tests for T10115 with a different language

	QUnit.test( 'T34888 - Tables inside a tableheader cell', ( assert ) => {
		const $table = $(
			'<table class="sortable" id="mw-bug-32888">' +
				'<tr><th>header<table id="mw-bug-32888-2">' +
				'<tr><th>1</th><th>2</th></tr>' +
				'</table></th></tr>' +
				'<tr><td>A</td></tr>' +
				'<tr><td>B</td></tr>' +
				'</table>'
		);
		$table.tablesorter();

		assert.strictEqual(
			$table.find( '> thead > tr > th.headerSort' ).length,
			1,
			'Child tables inside a headercell should not interfere with sortable headers (T34888)'
		);
		assert.strictEqual(
			$( '#mw-bug-32888-2' ).find( 'th.headerSort' ).length,
			0,
			'The headers of child tables inside a headercell should not be sortable themselves (T34888)'
		);
	} );

	QUnit.test(
		'Correct date sorting I',
		( assert ) => {
			const $table = tableCreate( [ 'Date' ], [
				[ '01 January 2010' ],
				[ '05 February 2010' ],
				[ '16 January 2010' ]
			] );
			mw.config.set( 'wgDefaultDateFormat', 'mdy' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				[ '01 January 2010' ],
				[ '16 January 2010' ],
				[ '05 February 2010' ]
			] );
		}
	);

	QUnit.test(
		'Correct date sorting II',
		( assert ) => {
			const $table = tableCreate( [ 'Date' ], [
				[ 'January 01 2010' ],
				[ 'February 05 2010' ],
				[ 'January 16 2010' ]
			] );
			mw.config.set( 'wgDefaultDateFormat', 'dmy' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				[ 'January 01 2010' ],
				[ 'January 16 2010' ],
				[ 'February 05 2010' ]
			] );
		}
	);

	QUnit.test(
		'ISO date sorting',
		( assert ) => {
			const $table = tableCreate( [ 'ISO date' ], [
				[ '2010-02-01' ],
				[ '2009-12-25T12:30:45.001Z' ],
				[ '2010-01-31' ],
				[ '2009' ],
				[ '2009-12-25T12:30:45' ],
				[ '2009-12-25T12:30:45.111' ],
				[ '2009-12-25T12:30:45+01:00' ]
			] );
			mw.config.set( 'wgDefaultDateFormat', 'dmy' );

			$table.tablesorter();
			$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

			assert.deepEqual( tableExtract( $table ), [
				[ '2009' ],
				[ '2009-12-25T12:30:45' ],
				[ '2009-12-25T12:30:45.001Z' ],
				[ '2009-12-25T12:30:45.111' ],
				// Effectively 11:30 UTC (earlier than above). No longer timezone-aware (T47161).
				[ '2009-12-25T12:30:45+01:00' ],
				[ '2010-01-31' ],
				[ '2010-02-01' ]
			] );
		}
	);

	QUnit.test( 'Sorting images using alt text', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td><img alt="2"/></td></tr>' +
				'<tr><td>1</td></tr>' +
				'</table>'
		);
		$table.tablesorter().find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		assert.strictEqual(
			$table.find( 'td' ).first().text(),
			'1',
			'Applied correct sorting order'
		);
	} );

	QUnit.test( 'Sorting images using alt text (complex)', ( assert ) => {
		const $table = $(
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
		$table.tablesorter().find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		assert.strictEqual(
			$table.find( 'td' ).text(),
			'CDEFCCA',
			'Applied correct sorting order'
		);
	} );

	QUnit.test( 'Sorting images using alt text (with format autodetection)', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<tr><th>THEAD</th></tr>' +
				'<tr><td><img alt="1" />7</td></tr>' +
				'<tr><td>1<img alt="6" /></td></tr>' +
				'<tr><td>5</td></tr>' +
				'<tr><td>4</td></tr>' +
				'</table>'
		);
		$table.tablesorter().find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		assert.strictEqual(
			$table.find( 'td' ).text(),
			'4517',
			'Applied correct sorting order'
		);
	} );

	QUnit.test( 'T40911 - The row with the largest amount of columns should receive the sort indicators', ( assert ) => {
		const $table = $(
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

		assert.strictEqual(
			$table.find( '#A1' ).attr( 'class' ),
			'headerSort',
			'The first column of the first row should be sortable'
		);
		assert.strictEqual(
			$table.find( '#B2b' ).attr( 'class' ),
			'headerSort',
			'The th element of the 2nd row of the 2nd column should be sortable'
		);
		assert.strictEqual(
			$table.find( '#C2b' ).attr( 'class' ),
			'headerSort',
			'The th element of the 2nd row of the 3rd column should be sortable'
		);
	} );

	QUnit.test( 'rowspans in table headers should prefer the last row when rows are equal in length', ( assert ) => {
		const $table = $(
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

		assert.strictEqual(
			$table.find( '#A1' ).attr( 'class' ),
			'headerSort',
			'The first column of the first row should be sortable'
		);
		assert.strictEqual(
			$table.find( '#B2b' ).attr( 'class' ),
			'headerSort',
			'The th element of the 2nd row of the 2nd column should be sortable'
		);
	} );

	QUnit.test( 'holes in the table headers should not throw JS errors', ( assert ) => {
		const $table = $(
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
		assert.strictEqual( $table.find( '#A2' ).data( 'headerIndex' ),
			undefined,
			'A2 should not be a sort header'
		);
		assert.strictEqual( $table.find( '#C1' ).data( 'headerIndex' ),
			2,
			'C1 should be a sort header'
		);
	} );

	// T55527
	QUnit.test( 'td cells in thead should not be taken into account for longest row calculation', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<thead>' +
				'<tr><th id="A1">A1</th><th>B1</th><td id="C1">C1</td></tr>' +
				'<tr><th id="A2">A2</th><th>B2</th><th id="C2">C2</th></tr>' +
				'</thead>' +
				'</table>'
		);
		$table.tablesorter();
		assert.strictEqual( $table.find( '#C2' ).data( 'headerIndex' ),
			2,
			'C2 should be a sort header'
		);
		assert.strictEqual( $table.find( '#C1' ).data( 'headerIndex' ),
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
		'Rowspan exploding with row headers and colspans', ( assert ) => {
			const $table = $( '<table class="sortable">' +
				'<thead><tr><th rowspan="2">n</th><th colspan="2">foo</th><th rowspan="2">baz</th></tr>' +
				'<tr><th>foo</th><th>bar</th></tr></thead>' +
				'<tbody>' +
				'<tr><td>1</td><td>foo</td><td>bar</td><td>baz</td></tr>' +
				'<tr><td>2</td><td>foo</td><td>bar</td><td>baz</td></tr>' +
				'</tbody></table>' );

			$table.tablesorter();
			assert.strictEqual( $table.find( 'tr' ).eq( 1 ).find( 'th' ).eq( 1 ).data( 'headerIndex' ),
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

	QUnit.test( 'T105731 - incomplete rows in table body', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<tr><th>A</th><th>B</th></tr>' +
				'<tr><td>3</td></tr>' +
				'<tr><td>1</td><td>2</td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );
		// now the first row have 2 columns
		$table.find( '.headerSort' ).eq( 1 ).trigger( 'click' );

		const parsers = $table.data( 'tablesorter' ).config.parsers;

		assert.strictEqual(
			parsers.length,
			2,
			'detectParserForColumn() detect 2 parsers'
		);

		assert.strictEqual(
			parsers[ 1 ].id,
			'number',
			'detectParserForColumn() detect parser.id "number" for second column'
		);

		assert.strictEqual(
			parsers[ 1 ].format( $table.find( 'tbody > tr > td' ).eq( 1 ).text() ),
			-Infinity,
			'empty cell is sorted as number -Infinity'
		);
	} );

	QUnit.test( 'bug T114721 - use of expand-child class', ( assert ) => {
		const $table = $(
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
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

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

		const parsers = $table.data( 'tablesorter' ).config.parsers;
		assert.strictEqual(
			parsers[ 1 ].id,
			'number',
			'detectParserForColumn() detect parser.id "number" for second column'
		);
	} );
	QUnit.test( 'T29745 - References ignored in sortkey', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
				'<tr><th>A</th></tr>' +
				'<tr><td>10</td></tr>' +
				'<tr><td>2<sup class="reference"><a href="#cite_note-1">[1]</a></sup></td></tr>' +
				'</table>'
		);
		$table.tablesorter();
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		assert.deepEqual(
			tableExtract( $table ),
			[
				[ '2[1]' ],
				[ '10' ]
			],
			'References ignored in sortkey'
		);

		const parsers = $table.data( 'tablesorter' ).config.parsers;
		assert.strictEqual(
			parsers[ 0 ].id,
			'number',
			'detectParserForColumn() detect parser.id "number"'
		);
	} );

	QUnit.test( 'T311145 - style tags ignored in sortkey', ( assert ) => {
		const $table = $(
			'<table class="sortable">' +
			'<tr><th>A</th></tr>' +
			'<tr><td>10</td></tr>' +
			'<tr><td>2<style>.sortable { display:none; }</style></td></tr>' +
			'</table>'
		);
		$table.tablesorter();
		$table.find( '.headerSort' ).eq( 0 ).trigger( 'click' );

		assert.deepEqual(
			tableExtract( $table ),
			[
				// The test doesn't actually test the sortkey, just contents
				[ '2.sortable { display:none; }' ],
				[ '10' ]
			],
			'style tags in sortkey'
		);

		const parsers = $table.data( 'tablesorter' ).config.parsers;
		assert.strictEqual(
			parsers[ 0 ].id,
			'number',
			'detectParserForColumn() detect parser.id "number"'
		);
	} );

	QUnit.module( 'parsers', ( hooks ) => {
		hooks.beforeEach( function () {
			/**
			 * Check how the parser recognizes and transforms the data
			 *
			 * @param {Object} assert
			 * @param {string} parserId The parser that will be tested
			 * @param {string[][]} data Array of testcases. Each testcase, array of:
			 *  - inputValue: The string value that we want to test the parser for.
			 *  - recognized: If we expect that this value's type is detectable by the parser.
			 *  - outputValue: The value the parser has converted the input to.
			 *  - msg: describing the testcase.
			 */
			this.parser = function assertParser( assert, parserId, data ) {
				const parser = $.tablesorter.getParser( parserId );
				data.forEach( ( testcase ) => {
					const extractedR = parser.is( testcase[ 0 ] );
					const extractedF = parser.format( testcase[ 0 ] );

					assert.strictEqual( extractedR, testcase[ 1 ], 'Detect: ' + testcase[ 3 ] );
					assert.strictEqual( extractedF, testcase[ 2 ], 'Sortkey: ' + testcase[ 3 ] );
				} );
			};
		} );

		QUnit.test( 'Textual keys', function ( assert ) {
			this.parser( assert, 'text', [
				[ 'Mars', true, 'Mars', 'Simple text' ],
				[ 'Mẘas', true, 'Mẘas', 'Non ascii character' ],
				[ 'A sentence', true, 'A sentence', 'A sentence with space chars' ]
			] );
		} );

		QUnit.test( 'IPv4', function ( assert ) {
			this.parser( assert, 'IPAddress', [
				// Some randomly generated fake IPs
				[ '0.0.0.0', true, 0, 'An IP address' ],
				[ '255.255.255.255', true, 255255255255, 'An IP address' ],
				[ '45.238.27.109', true, 45238027109, 'An IP address' ],
				[ '1.238.27.1', true, 1238027001, 'An IP address with small numbers' ],
				[ '238.27.1', false, 238027001, 'A malformed IP Address' ],
				[ '1', false, 1, 'A super malformed IP Address' ],
				[ 'Just text', false, -Infinity, 'A line with just text' ],
				[ '45.238.27.109Postfix', false, 45238027109, 'An IP address with a connected postfix' ],
				[ '45.238.27.109 postfix', false, 45238027109, 'An IP address with a separated postfix' ]
			] );
		} );

		QUnit.test( 'MDY Dates using mdy content language', function ( assert ) {
			this.parser( assert, 'date', [
				[ 'January 17, 2010', true, 20100117, 'Long middle endian date' ],
				[ 'Jan 17, 2010', true, 20100117, 'Short middle endian date' ],
				[ '1/17/2010', true, 20100117, 'Numeric middle endian date' ],
				[ '01/17/2010', true, 20100117, 'Numeric middle endian date with padding on month' ],
				[ '01/07/2010', true, 20100107, 'Numeric middle endian date with padding on day' ],
				[ '01/07/0010', true, 20100107, 'Numeric middle endian date with padding on year' ],
				[ '5.12.1990', true, 19900512, 'Numeric middle endian date with . separator' ]
			] );
		} );

		QUnit.test( 'MDY Dates using dmy content language', function ( assert ) {
			mw.config.set( {
				wgDefaultDateFormat: 'dmy',
				wgPageViewLanguage: 'de'
			} );
			this.parser( assert, 'date', [
				[ 'January 17, 2010', true, 20100117, 'Long middle endian date' ],
				[ 'Jan 17, 2010', true, 20100117, 'Short middle endian date' ],
				[ '1/17/2010', true, 20101701, 'Numeric middle endian date' ],
				[ '01/17/2010', true, 20101701, 'Numeric middle endian date with padding on month' ],
				[ '01/07/2010', true, 20100701, 'Numeric middle endian date with padding on day' ],
				[ '01/07/0010', true, 20100701, 'Numeric middle endian date with padding on year' ],
				[ '5.12.1990', true, 19901205, 'Numeric middle endian date with . separator' ]
			] );
		} );

		QUnit.test( 'Very old MDY dates', function ( assert ) {
			this.parser( assert, 'date', [
				[ 'January 19, 1400 BC', false, '99999999', 'BC' ],
				[ 'January 19, 1400BC', false, '99999999', 'Connected BC' ],
				[ 'January, 19 1400 B.C.', false, '99999999', 'B.C.' ],
				[ 'January 19, 1400 AD', false, '99999999', 'AD' ],
				[ 'January, 19 10', true, 20100119, 'AD' ],
				[ 'January, 19 1', false, '99999999', 'AD' ]
			] );
		} );

		QUnit.test( 'MDY Dates', function ( assert ) {
			this.parser( assert, 'date', [
				[ 'January, 19 2010', true, 20100119, 'Comma after month' ],
				[ 'January 19, 2010', true, 20100119, 'Comma after day' ],
				[ 'January/19/2010', true, 20100119, 'Forward slash separator' ],
				[ '04 22 1991', true, 19910422, 'Month with 0 padding' ],
				[ 'April 21 1991', true, 19910421, 'Space separation' ],
				[ '04 22 1991', true, 19910422, 'Month with 0 padding' ],
				[ 'December 12 \'10', true, 20101212, '' ],
				[ 'Dec 12 \'10', true, 20101212, '' ],
				[ 'Dec. 12 \'10', true, 20101212, '' ]
			] );
		} );

		QUnit.test( 'DMY Dates', function ( assert ) {
			mw.config.set( {
				wgDefaultDateFormat: 'dmy',
				wgPageViewLanguage: 'it'
			} );
			this.parser( assert, 'date', [
				[ '1º January 2010', true, 20100101, 'T305375 - dates with the ordinal indicator º' ]
			] );
		} );

		QUnit.test( 'Clobbered Dates', function ( assert ) {
			this.parser( assert, 'date', [
				[ 'January, 19 2010 - January, 20 2010', false, '99999999', 'Date range with hyphen' ],
				[ 'January, 19 2010 — January, 20 2010', false, '99999999', 'Date range with mdash' ],
				[ 'prefixJanuary, 19 2010', false, '99999999', 'Connected prefix' ],
				[ 'prefix January, 19 2010', false, '99999999', 'Prefix' ],
				[ 'December 12 2010postfix', false, '99999999', 'ConnectedPostfix' ],
				[ 'December 12 2010 postfix', false, '99999999', 'Postfix' ],
				[ 'A simple text', false, '99999999', 'Plain text in date sort' ],
				[ '04l22l1991', false, '99999999', 'l char as separator' ],
				[ 'January\\19\\2010', false, '99999999', 'backslash as date separator' ]
			] );
		} );

		QUnit.test( 'MY Dates', function ( assert ) {
			this.parser( assert, 'date', [
				[ 'December 2010', false, '99999999', 'Plain month year' ],
				[ 'Dec 2010', false, '99999999', 'Abreviated month year' ],
				[ '12 2010', false, '99999999', 'Numeric month year' ]
			] );
		} );

		QUnit.test( 'Y Dates', function ( assert ) {
			this.parser( assert, 'date', [
				[ '2010', false, '99999999', 'Plain 4-digit year' ],
				[ '876', false, '99999999', '3-digit year' ],
				[ '76', false, '99999999', '2-digit year' ],
				[ '\'76', false, '99999999', '2-digit millenium bug year' ],
				[ '2010 BC', false, '99999999', '4-digit year BC' ]
			] );
		} );

		QUnit.test( 'Currency', function ( assert ) {
			this.parser( assert, 'currency', [
				[ '1.02 $', true, 1.02, '' ],
				[ '$ 3.00', true, 3, '' ],
				[ '€ 2,99', true, 299, '' ],
				[ '$ 1.00', true, 1, '' ],
				[ '$3.50', true, 3.50, '' ],
				[ '$ 1.50', true, 1.50, '' ],
				[ '€ 0.99', true, 0.99, '' ],
				[ '$ 299.99', true, 299.99, '' ],
				[ '$ 2,299.99', true, 2299.99, '' ],
				[ '$ 2,989', true, 2989, '' ],
				[ '$ 2 299.99', true, 2299.99, '' ],
				[ '$ 2 989', true, 2989, '' ],
				[ '$ 2.989', true, 2.989, '' ]
			] );
		} );

		QUnit.test( 'Currency with european separators', function ( assert ) {
			mw.config.set( {
				// We expect 22'234.444,22
				// Map from ascii separators => localized separators
				wgSeparatorTransformTable: [ ',\t.\t,', '\'\t,\t.' ],
				wgDigitTransformTable: [ '', '' ]
			} );
			this.parser( assert, 'currency', [
				[ '1.02 $', true, 102, '' ],
				[ '$ 3.00', true, 300, '' ],
				[ '€ 2,99', true, 2.99, '' ],
				[ '$ 1.00', true, 100, '' ],
				[ '$3.50', true, 350, '' ],
				[ '$ 1.50', true, 150, '' ],
				[ '€ 0.99', true, 99, '' ],
				[ '$ 299.99', true, 29999, '' ],
				[ '$ 2\'299,99', true, 2299.99, '' ],
				[ '$ 2,989', true, 2.989, '' ],
				[ '$ 2 299.99', true, 229999, '' ],
				[ '2 989 $', true, 2989, '' ],
				[ '299.99 $', true, 29999, '' ],
				[ '2\'299,99 $', true, 2299.99, '' ],
				[ '2,989 $', true, 2.989, '' ],
				[ '2 299.99 $', true, 229999, '' ],
				[ '2 989 $', true, 2989, '' ]
			] );
		} );

		QUnit.test( 'T114604 - Breaking tfoot with rowspans', ( assert ) => {
			const $table = $(
					'<table class="sortable">' +
					'<tr><th>A1</th><th>A2</th></tr>' +
					'<tr><td>B1</td><td>B2</td></tr>' +
					'<tr><th>C1</th><th>C2</th></tr>' +
					'<tr><td>D1</td><td rowspan="2">D2</td></tr>' +
					'<tr><th>E1</th></tr>' +
					'<tr><th>F1</th><th>F2</th></tr>' +
					'</table>'
				),
				data = {
					THEAD: [],
					TBODY: [],
					TFOOT: []
				};

			$table.tablesorter();

			$table.find( 'thead,tbody,tfoot' ).find( 'tr' ).each( function () {
				const row = [],
					group = $( this ).parent().prop( 'nodeName' );

				$( this ).find( 'td,th' ).each( function () {
					row.push( $( this ).text() );
				} );

				data[ group ].push( row );
			} );

			assert.deepEqual(
				data,
				{
					THEAD: [
						[ 'A1', 'A2' ]
					],
					TBODY: [
						[ 'B1', 'B2' ],
						[ 'C1', 'C2' ],
						[ 'D1', 'D2' ],
						[ 'E1' ]
					],
					TFOOT: [
						[ 'F1', 'F2' ]
					]
				},
				'Row with rowspan td not added to tfoot'
			);
		} );

		// TODO add numbers sorting tests for T10115 with a different language
	} );

} );
