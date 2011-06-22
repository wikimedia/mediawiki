(function() {

module( 'jquery.tablesorter.test.js' );

// setup hack
mw.config.set('wgMonthNames', window.wgMonthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);
mw.config.set('wgMonthNamesShort', window.wgMonthNamesShort = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);

test( '-- Initial check', function() {
	expect(1);
	ok( $.tablesorter, '$.tablesorter defined' );
});

/**
 * Create an HTML table from an array of row arrays containing text strings.
 * First row will be header row. No fancy rowspan/colspan stuff.
 *
 * @param {String[]} header
 * @param {String[][]} data
 * @return jQuery
 */
var tableCreate = function( header, data ) {
	var $table = $('<table class="sortable"><thead></thead><tbody></tbody></table>'),
		$thead = $table.find('thead'),
		$tbody = $table.find('tbody');
	var $tr = $('<tr>');
	$.each(header, function(i, str) {
		var $th = $('<th>');
		$th.text(str).appendTo($tr);
	})
	$tr.appendTo($thead);

	for (var i = 0; i < data.length; i++) {
		$tr = $('<tr>');
		$.each(data[i], function(j, str) {
			var $td = $('<td>');
			$td.text(str).appendTo($tr);
		})
		$tr.appendTo($tbody);
	}
	return $table;
};

/**
 * Extract text from table.
 *
 * @param {jQuery} $table
 * @return String[][]
 */
var tableExtract = function( $table ) {
	var data = [];
	$table.find('tbody').find('tr').each(function(i, tr) {
		var row = [];
		$(tr).find('td,th').each(function(i, td) {
			row.push($(td).text());
		})
		data.push(row);
	});
	return data;
};

/**
 * Run a table test by building a table with the given data,
 * running some callback on it, then checking the results.
 *
 * @param {String} msg text to pass on to qunit for the comparison
 * @param {String[]} header cols to make the table
 * @param {String[][]} data rows/cols to make the table
 * @param {String[][]} expected rows/cols to compare against at end
 * @param {function($table)} callback something to do with the table before we compare
 */
var tableTest = function( msg, header, data, expected, callback ) {
	test( msg, function() {
		expect(1);

		var $table = tableCreate( header, data );
		//$('body').append($table);

		// Give caller a chance to set up sorting and manipulate the table.
		callback( $table );
		
		// Table sorting is done asynchronously in the event loop by running
		// it through a setTimeout({},0); we need to do the same so we can
		// check our results after.
		stop( 1000 ); // timeout in 1s
		setTimeout(function() {
			start(); // continue the async tests...

			var extracted = tableExtract( $table );
			deepEqual( extracted, expected, msg )
		}, 150);
	});
};

var reversed = function(arr) {
	var arr2 = arr.slice(0);
	arr2.reverse();
	return arr2;
}

// Sample data set: some planets!
var header = ['Planet', 'Radius (km)'],
	mercury = ['Mercury', '2439.7'],
	venus = ['Venus', '6051.8'],
	earth = ['Earth', '6371.0'],
	mars = ['Mars', '3390.0'],
	jupiter = ['Jupiter', '69911'],
	saturn = ['Saturn', '58232'];

// Initial data set
var planets = [mercury, venus, earth, mars, jupiter, saturn];
var ascendingName = [earth, jupiter, mars, mercury, saturn, venus];
var ascendingRadius = [mercury, mars, venus, earth, saturn, jupiter];

tableTest(
	'Basic planet table: ascending by name',
	header,
	planets,
	ascendingName,
	function( $table ) {
		$table.tablesorter();
		$table.find('.headerSort:eq(0)').click();
	}
);
tableTest(
	'Basic planet table: ascending by name a second time',
	header,
	planets,
	ascendingName,
	function( $table ) {
		$table.tablesorter();
		$table.find('.headerSort:eq(0)').click();
	}
);
tableTest(
	'Basic planet table: descending by name',
	header,
	planets,
	reversed(ascendingName),
	function( $table ) {
		$table.tablesorter();
		$table.find('.headerSort:eq(0)').click().click();
	}
);
tableTest(
	'Basic planet table: ascending radius',
	header,
	planets,
	ascendingRadius,
	function( $table ) {
		$table.tablesorter();
		$table.find('.headerSort:eq(1)').click();
	}
);
tableTest(
	'Basic planet table: descending radius',
	header,
	planets,
	reversed(ascendingRadius),
	function( $table ) {
		$table.tablesorter();
		$table.find('.headerSort:eq(1)').click().click();
	}
);


// Regression tests!
tableTest(
	'Bug 28775: German-style short numeric dates',
	['Date'],
	[
		// German-style dates are day-month-year
		['11.11.2011'],
		['01.11.2011'],
		['02.10.2011'],
		['03.08.2011'],
		['09.11.2011']
	],
	[
		// Sorted by ascending date
		['03.08.2011'],
		['02.10.2011'],
		['01.11.2011'],
		['09.11.2011'],
		['11.11.2011']
	],
	function( $table ) {
		// @fixme reset it at end or change module to allow us to override it
		mw.config.set('wgDefaultDateFormat', window.wgDefaultDateFormat = 'dmy');
		$table.tablesorter();
		$table.find('.headerSort:eq(0)').click();
	}
);
tableTest(
	'Bug 28775: American-style short numeric dates',
	['Date'],
	[
		// American-style dates are month-day-year
		['11.11.2011'],
		['01.11.2011'],
		['02.10.2011'],
		['03.08.2011'],
		['09.11.2011']
	],
	[
		// Sorted by ascending date
		['01.11.2011'],
		['02.10.2011'],
		['03.08.2011'],
		['09.11.2011'],
		['11.11.2011']
	],
	function( $table ) {
		// @fixme reset it at end or change module to allow us to override it
		mw.config.set('wgDefaultDateFormat', window.wgDefaultDateFormat = 'mdy');
		$table.tablesorter();
		$table.find('.headerSort:eq(0)').click();
	}
);

})();
