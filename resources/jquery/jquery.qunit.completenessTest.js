/**
 * jQuery QUnit CompletenessTest 0.3
 *
 * Tests the completeness of test suites for object oriented javascript
 * libraries. Written to be used in environments with jQuery and QUnit.
 * Requires jQuery 1.5.2 or higher.
 *
 * Globals: jQuery, QUnit, console.log
 *
 * Built for and tested with:
 * - Safari 5
 * - Firefox 4
 *
 * @author Timo Tijhof, 2011
 */
( function( $ ) {

/**
 * CompletenessTest
 * @constructor
 *
 * @example
 *  var myTester = new CompletenessTest( myLib );
 * @param masterVariable {Object} The root variable that contains all object
 *  members. CompletenessTest will recursively traverse objects and keep track
 *  of all methods.
 * @param ignoreFn {Function} Optionally pass a function to filter out certain
 *  methods. Example: You may want to filter out instances of jQuery or some
 *  other constructor. Otherwise "missingTests" will include all methods that
 *  were not called from that instance.
 */
var CompletenessTest = function ( masterVariable, ignoreFn ) {

	// Keep track in these objects. Keyed by strings with the
	// method names (ie. 'my.foo', 'my.bar', etc.) values are boolean true.
	this.methodCallTracker = {};
	this.missingTests = {};

	this.ignoreFn = undefined === ignoreFn ? function(){ return false; } : ignoreFn;

	// Lazy limit in case something weird happends (like recurse (part of) ourself).
	this.lazyLimit = 1000;
	this.lazyCounter = 0;

	var that = this;

	// Bind begin and end to QUnit.
	QUnit.begin = function(){
		that.checkTests( null, masterVariable, masterVariable, [], CompletenessTest.ACTION_INJECT );
	};

	QUnit.done = function(){
		that.checkTests( null, masterVariable, masterVariable, [], CompletenessTest.ACTION_CHECK );
		console.log( 'CompletenessTest.ACTION_CHECK', that );

		// Build HTML representing the outcome from CompletenessTest
		// And insert it into the header.

		var makeList = function( blob, title, style ) {
			title = title || 'Values';
			var html = '<strong>' + mw.html.escape(title) + '</strong>';
			$.each( blob, function( key ) {
				html += '<br />' + mw.html.escape(key);
			});
			html += '<br /><br /><em>&mdash; CompletenessTest</em>';
			var	$oldResult = $( '#qunit-completenesstest' ),
				$result = $oldResult.length ? $oldResult : $( '<div id="qunit-completenesstest"></div>' );
			return $result.css( style ).html( html );
		};

		if ( $.isEmptyObject( that.missingTests ) ) {
			// Good
			var $testResults = makeList(
				{ 'No missing tests!': true },
				'missingTests',
				{
					background: '#D2E0E6',
					color: '#366097',
					padding: '1em'
				}
			);
		} else {
			// Bad
			var $testResults = makeList(
				that.missingTests,
				'missingTests',
				{
					background: '#EE5757',
					color: 'black',
					padding: '1em'
				}
			);
		}

		$( '#qunit-testrunner-toolbar' ).prepend( $testResults );
	};

	return this;
};

/* Static members */
CompletenessTest.ACTION_INJECT = 500;
CompletenessTest.ACTION_CHECK = 501;

/* Public methods */
CompletenessTest.fn = CompletenessTest.prototype = {

	/**
	 * CompletenessTest.fn.checkTests
	 *
	 * This function recursively walks through the given object, calling itself as it goes.
	 * Depending on the action it either injects our listener into the methods, or
	 * reads from our tracker and records which methods have not been called by the test suite.
	 *
	 * @param currName {String|Null} Name of the given object member (Initially this is null).
	 * @param currVar {mixed} The variable to check (initially an object,
	 *  further down it could be anything).
	 * @param masterVariable {Object} Throughout our interation, always keep track of the master/root.
	 *  Initially this is the same as currVar.
	 * @param parentPathArray {Array} Array of names that indicate our breadcrumb path starting at
	 *  masterVariable. Not including currName.
	 * @param action {Number} What is this function supposed to do (ACTION_INJECT or ACTION_CHECK)
	 */
	checkTests: function( currName, currVar, masterVariable, parentPathArray, action ) {

		// Handle the lazy limit
		this.lazyCounter++;
		if ( this.lazyCounter > this.lazyLimit ) {
			console.log( 'CompletenessTest.fn.checkTests> Limit reached: ' + this.lazyCounter );
			return null;
		}

		var	type = $.type( currVar ),
			that = this;

		// Hard ignores
		if ( this.ignoreFn( currVar, that, parentPathArray ) ) {
			return null;

		// Functions
		} else  if ( type === 'function' ) {

			/* CHECK MODE */

			if ( action === CompletenessTest.ACTION_CHECK ) {

				if ( !currVar.prototype || $.isEmptyObject( currVar.prototype ) ) {

					that.hasTest( parentPathArray.join( '.' ) );

				// We don't support checking object constructors yet...
				} else {

					// ...the prototypes are fine tho
					$.each( currVar.prototype, function( key, value ) {
						if ( key === 'constructor' ) return;

						// Clone and break reference to parentPathArray
						var tmpPathArray = $.extend( [], parentPathArray );
						tmpPathArray.push( 'prototype' ); tmpPathArray.push( key );

						that.hasTest( tmpPathArray.join( '.' ) );
					} );
				}

			/* INJECT MODE */

			} else if ( action === CompletenessTest.ACTION_INJECT ) {

				if ( !currVar.prototype || $.isEmptyObject( currVar.prototype ) ) {

					// Inject check
					that.injectCheck( masterVariable, parentPathArray, function() {
						that.methodCallTracker[ parentPathArray.join( '.' ) ] = true;
					} );

				// We don't support checking object constructors yet...
				} else {

					// ... the prototypes are fine tho
					$.each( currVar.prototype, function( key, value ) {
						if ( key === 'constructor' ) return;

						// Clone and break reference to parentPathArray
						var tmpPathArray = $.extend( [], parentPathArray );
						tmpPathArray.push( 'prototype' ); tmpPathArray.push( key );

						that.checkTests( key, value, masterVariable, tmpPathArray, action );
					} );
				}

			}

		// Recursively. After all, this *is* the completeness test
		} else if ( type === 'object' ) {

			$.each( currVar, function( key, value ) {

				// Clone and break reference to parentPathArray
				var tmpPathArray = $.extend( [], parentPathArray );
				tmpPathArray.push( key );

				that.checkTests( key, value, masterVariable, tmpPathArray, action );

			} );

		}
	},

	/**
	 * CompletenessTest.fn.hasTest
	 *
	 * Checks if the given method name (ie. 'my.foo.bar')
	 * was called during the test suite (as far as the tracker knows).
	 * If not it adds it to missingTests.
	 *
	 * @param fnName {String}
	 * @return {Boolean}
	 */
	hasTest: function( fnName ) {
		if ( !( fnName in this.methodCallTracker ) ) {
			this.missingTests[fnName] = true;
			return false;
		}
		return true;
	},

	/**
	 * CompletenessTest.fn.injectCheck
	 *
	 * Injects a function (such as a spy that updates methodCallTracker when
	 * it's called) inside another function.
	 *
	 * @param masterVariable {Object}
	 * @param objectPathArray {Array}
	 * @param injectFn {Function}
	 */
	injectCheck: function( masterVariable, objectPathArray, injectFn ) {
		var	prev,
			curr = masterVariable,
			lastMember;

		$.each( objectPathArray, function( i, memberName ) {
			prev = curr;
			curr = prev[memberName];
			lastMember = memberName;
		});

		// Objects are by reference, members (unless objects) are not.
		prev[lastMember] = function() {
			injectFn();
			return curr.apply( this, arguments );
		};
	}
};

window.CompletenessTest = CompletenessTest;

} )( jQuery );
