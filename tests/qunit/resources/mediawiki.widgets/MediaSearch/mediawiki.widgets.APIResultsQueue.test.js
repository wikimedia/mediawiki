/*!
 * VisualEditor DataModel ResourceQueue tests.
 *
 * @copyright 2011-2018 VisualEditor Team and others; see http://ve.mit-license.org
 */

QUnit.module( 'mediawiki.widgets.APIResultsQueue' );

( function () {
	let itemCounter = 0;
	const FullResourceProvider = function ( config ) {
		this.timer = null;
		this.responseDelay = 1;
		// Inheritance
		FullResourceProvider.super.call( this, '', config );
	};
	const EmptyResourceProvider = function ( config ) {
		this.timer = null;
		this.responseDelay = 1;
		// Inheritance
		EmptyResourceProvider.super.call( this, '', config );
	};
	const SingleResultResourceProvider = function ( config ) {
		this.timer = null;
		this.responseDelay = 1;
		// Inheritance
		SingleResultResourceProvider.super.call( this, '', config );
	};

	OO.inheritClass( FullResourceProvider, mw.widgets.APIResultsProvider );
	OO.inheritClass( EmptyResourceProvider, mw.widgets.APIResultsProvider );
	OO.inheritClass( SingleResultResourceProvider, mw.widgets.APIResultsProvider );

	FullResourceProvider.prototype.getResults = function ( howMany ) {
		const result = [],
			deferred = $.Deferred();

		let i;
		for ( i = itemCounter; i < itemCounter + howMany; i++ ) {
			result.push( 'result ' + ( i + 1 ) );
		}
		itemCounter = i;

		const timer = setTimeout(
			() => {
				// Always resolve with some values
				deferred.resolve( result );
			},
			this.responseDelay );

		return deferred.promise( { abort: function () {
			clearTimeout( timer );
		} } );
	};

	EmptyResourceProvider.prototype.getResults = function () {
		const deferred = $.Deferred(),
			timer = setTimeout(
				() => {
					this.toggleDepleted( true );
					// Always resolve with empty value
					deferred.resolve( [] );
				},
				this.responseDelay );

		return deferred.promise( { abort: function () {
			clearTimeout( timer );
		} } );
	};

	SingleResultResourceProvider.prototype.getResults = function ( howMany ) {
		const deferred = $.Deferred();

		const timer = setTimeout(
			() => {
				this.toggleDepleted( howMany > 1 );
				// Always resolve with one value
				deferred.resolve( [ 'one result (' + ( itemCounter++ + 1 ) + ')' ] );
			},
			this.responseDelay );

		return deferred.promise( { abort: function () {
			clearTimeout( timer );
		} } );
	};

	/* Tests */

	QUnit.test( 'Query providers', ( assert ) => {
		const done = assert.async(),
			providers = [
				new FullResourceProvider(),
				new EmptyResourceProvider(),
				new SingleResultResourceProvider()
			],
			queue = new mw.widgets.APIResultsQueue( {
				threshold: 2
			} );

		// Add providers to queue
		queue.setProviders( providers );

		// Set parameters and fetch
		queue.setParams( { foo: 'bar' } );

		queue.get( 10 )
			.then( ( data ) => {
				// Check that we received all requested results
				assert.strictEqual( data.length, 10, 'Query 1: Results received.' );
				// We've asked for 10 items + 2 threshold from all providers.
				// Provider 1 returned 12 results
				// Provider 2 returned 0 results
				// Provider 3 returned 1 results
				// Overall 13 results. 10 were retrieved. 3 left in queue.
				assert.strictEqual( queue.getQueueSize(), 3, 'Query 1: Remaining queue size.' );

				// Check if sources are depleted
				assert.false( providers[ 0 ].isDepleted(), 'Query 1: Full provider not depleted.' );
				assert.true( providers[ 1 ].isDepleted(), 'Query 1: Empty provider is depleted.' );
				assert.true( providers[ 2 ].isDepleted(), 'Query 1: Single result provider is depleted.' );

				// Ask for more results
				return queue.get( 10 );
			} )
			.then( ( data1 ) => {
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
				assert.false( providers[ 0 ].isDepleted(), 'Query 2: Full provider not depleted.' );
				assert.false( providers[ 1 ].isDepleted(), 'Query 2: Empty provider not depleted.' );
				assert.false( providers[ 2 ].isDepleted(), 'Query 2: Single result provider not depleted.' );

				return queue.get( 10 );
			} )
			.then( ( data2 ) => {
				// This should be the same as the very first result
				assert.strictEqual( data2.length, 10, 'Query 2: Results received.' );
				assert.strictEqual( queue.getQueueSize(), 3, 'Query 2: Remaining queue size.' );
				// Check if sources are depleted
				assert.false( providers[ 0 ].isDepleted(), 'Query 2: Full provider not depleted.' );
				assert.true( providers[ 1 ].isDepleted(), 'Query 2: Empty provider is not depleted.' );
				assert.true( providers[ 2 ].isDepleted(), 'Query 2: Single result provider is not depleted.' );
			} )
			// Finish the async test
			.then( done );
	} );

	QUnit.test( 'Abort providers', ( assert ) => {
		const done = assert.async(),
			biggerQueue = new mw.widgets.APIResultsQueue( {
				threshold: 5
			} ),
			providers = [
				new FullResourceProvider(),
				new EmptyResourceProvider(),
				new SingleResultResourceProvider()
			];
		let completed = false;

		// Make the delay higher
		providers.forEach( ( provider ) => {
			provider.responseDelay = 3;
		} );

		// Add providers to queue
		biggerQueue.setProviders( providers );

		biggerQueue.setParams( { foo: 'bar' } );
		biggerQueue.get( 100 )
			.always( () => {
				// This should only run if the promise wasn't aborted
				completed = true;
			} );

		// Make the delay higher
		providers.forEach( ( provider ) => {
			provider.responseDelay = 5;
		} );

		biggerQueue.setParams( { foo: 'baz' } );
		biggerQueue.get( 10 )
			.then( () => {
				assert.false( completed, 'Provider promises aborted.' );
			} )
			// Finish the async test
			.then( done );
	} );
}() );
