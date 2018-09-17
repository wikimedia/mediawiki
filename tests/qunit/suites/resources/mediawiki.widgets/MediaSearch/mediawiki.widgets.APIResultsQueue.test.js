/*!
 * VisualEditor DataModel ResourceQueue tests.
 *
 * @copyright 2011-2018 VisualEditor Team and others; see http://ve.mit-license.org
 */

QUnit.module( 'mediawiki.widgets.APIResultsQueue' );

( function () {
	var itemCounter, FullResourceProvider, EmptyResourceProvider, SingleResultResourceProvider;

	itemCounter = 0;
	FullResourceProvider = function ( config ) {
		this.timer = null;
		this.responseDelay = 1;
		// Inheritance
		FullResourceProvider.super.call( this, '', config );
	};
	EmptyResourceProvider = function ( config ) {
		this.timer = null;
		this.responseDelay = 1;
		// Inheritance
		EmptyResourceProvider.super.call( this, '', config );
	};
	SingleResultResourceProvider = function ( config ) {
		this.timer = null;
		this.responseDelay = 1;
		// Inheritance
		SingleResultResourceProvider.super.call( this, '', config );
	};

	OO.inheritClass( FullResourceProvider, mw.widgets.APIResultsProvider );
	OO.inheritClass( EmptyResourceProvider, mw.widgets.APIResultsProvider );
	OO.inheritClass( SingleResultResourceProvider, mw.widgets.APIResultsProvider );

	FullResourceProvider.prototype.getResults = function ( howMany ) {
		var i, timer,
			result = [],
			deferred = $.Deferred();

		for ( i = itemCounter; i < itemCounter + howMany; i++ ) {
			result.push( 'result ' + ( i + 1 ) );
		}
		itemCounter = i;

		timer = setTimeout(
			function () {
				// Always resolve with some values
				deferred.resolve( result );
			},
			this.responseDelay );

		return deferred.promise( { abort: function () { clearTimeout( timer ); } } );
	};

	EmptyResourceProvider.prototype.getResults = function () {
		var provider = this,
			deferred = $.Deferred(),
			timer = setTimeout(
				function () {
					provider.toggleDepleted( true );
					// Always resolve with empty value
					deferred.resolve( [] );
				},
				this.responseDelay );

		return deferred.promise( { abort: function () { clearTimeout( timer ); } } );
	};

	SingleResultResourceProvider.prototype.getResults = function ( howMany ) {
		var timer,
			provider = this,
			deferred = $.Deferred();

		timer = setTimeout(
			function () {
				provider.toggleDepleted( howMany > 1 );
				// Always resolve with one value
				deferred.resolve( [ 'one result (' + ( itemCounter++ + 1 ) + ')' ] );
			},
			this.responseDelay );

		return deferred.promise( { abort: function () { clearTimeout( timer ); } } );
	};

	/* Tests */

	QUnit.test( 'Query providers', function ( assert ) {
		var done = assert.async(),
			providers = [
				new FullResourceProvider(),
				new EmptyResourceProvider(),
				new SingleResultResourceProvider()
			],
			queue = new mw.widgets.APIResultsQueue( {
				threshold: 2
			} );

		assert.expect( 15 );

		// Add providers to queue
		queue.setProviders( providers );

		// Set parameters and fetch
		queue.setParams( { foo: 'bar' } );

		queue.get( 10 )
			.then( function ( data ) {
				// Check that we received all requested results
				assert.strictEqual( data.length, 10, 'Query 1: Results received.' );
				// We've asked for 10 items + 2 threshold from all providers.
				// Provider 1 returned 12 results
				// Provider 2 returned 0 results
				// Provider 3 returned 1 results
				// Overall 13 results. 10 were retrieved. 3 left in queue.
				assert.strictEqual( queue.getQueueSize(), 3, 'Query 1: Remaining queue size.' );

				// Check if sources are depleted
				assert.strictEqual( providers[ 0 ].isDepleted(), false, 'Query 1: Full provider not depleted.' );
				assert.strictEqual( providers[ 1 ].isDepleted(), true, 'Query 1: Empty provider is depleted.' );
				assert.strictEqual( providers[ 2 ].isDepleted(), true, 'Query 1: Single result provider is depleted.' );

				// Ask for more results
				return queue.get( 10 );
			} )
			.then( function ( data1 ) {
				// This time, only provider 1 was queried, because the other
				// two were marked as depleted.
				// * We asked for 10 items
				// * There are currently 3 items in the queue
				// * The queue queried provider #1 for 12 items
				// * The queue returned 10 results as requested
				// * 5 results are now left in the queue.
				assert.strictEqual( data1.length, 10, 'Query 1: Second set of results received.' );
				assert.strictEqual( queue.getQueueSize(), 5, 'Query 1: Remaining queue size.' );

				// Change the query
				queue.setParams( { foo: 'baz' } );
				// Check if sources are depleted
				assert.strictEqual( providers[ 0 ].isDepleted(), false, 'Query 2: Full provider not depleted.' );
				assert.strictEqual( providers[ 1 ].isDepleted(), false, 'Query 2: Empty provider not depleted.' );
				assert.strictEqual( providers[ 2 ].isDepleted(), false, 'Query 2: Single result provider not depleted.' );

				return queue.get( 10 );
			} )
			.then( function ( data2 ) {
				// This should be the same as the very first result
				assert.strictEqual( data2.length, 10, 'Query 2: Results received.' );
				assert.strictEqual( queue.getQueueSize(), 3, 'Query 2: Remaining queue size.' );
				// Check if sources are depleted
				assert.strictEqual( providers[ 0 ].isDepleted(), false, 'Query 2: Full provider not depleted.' );
				assert.strictEqual( providers[ 1 ].isDepleted(), true, 'Query 2: Empty provider is not depleted.' );
				assert.strictEqual( providers[ 2 ].isDepleted(), true, 'Query 2: Single result provider is not depleted.' );
			} )
			// Finish the async test
			.then( done );
	} );

	QUnit.test( 'Abort providers', function ( assert ) {
		var done = assert.async(),
			completed = false,
			biggerQueue = new mw.widgets.APIResultsQueue( {
				threshold: 5
			} ),
			providers = [
				new FullResourceProvider(),
				new EmptyResourceProvider(),
				new SingleResultResourceProvider()
			];

		assert.expect( 1 );

		// Make the delay higher
		providers.forEach( function ( provider ) { provider.responseDelay = 3; } );

		// Add providers to queue
		biggerQueue.setProviders( providers );

		biggerQueue.setParams( { foo: 'bar' } );
		biggerQueue.get( 100 )
			.always( function () {
				// This should only run if the promise wasn't aborted
				completed = true;
			} );

		// Make the delay higher
		providers.forEach( function ( provider ) { provider.responseDelay = 5; } );

		biggerQueue.setParams( { foo: 'baz' } );
		biggerQueue.get( 10 )
			.then( function () {
				assert.strictEqual( completed, false, 'Provider promises aborted.' );
			} )
			// Finish the async test
			.then( done );
	} );
}() );
