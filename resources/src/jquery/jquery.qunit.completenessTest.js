/**
 * jQuery QUnit CompletenessTest 0.4
 *
 * Tests the completeness of test suites for object oriented javascript
 * libraries. Written to be used in environments with jQuery and QUnit.
 * Requires jQuery 1.7.2 or higher.
 *
 * Built for and tested with:
 * - Chrome 19
 * - Firefox 4
 * - Safari 5
 *
 * @author Timo Tijhof, 2011-2012
 */
( function ( mw, $ ) {
	'use strict';

	var util,
		hasOwn = Object.prototype.hasOwnProperty,
		log = ( window.console && window.console.log )
			? function () { return window.console.log.apply( window.console, arguments ); }
			: function () {};

	// Simplified version of a few jQuery methods, except that they don't
	// call other jQuery methods. Required to be able to run the CompletenessTest
	// on jQuery itself as well.
	util = {
		keys: Object.keys || function ( object ) {
			var key, keys = [];
			for ( key in object ) {
				if ( hasOwn.call( object, key ) ) {
					keys.push( key );
				}
			}
			return keys;
		},
		each: function ( object, callback ) {
			var name;
			for ( name in object ) {
				if ( callback.call( object[ name ], name, object[ name ] ) === false ) {
					break;
				}
			}
		},
		// $.type and $.isEmptyObject are safe as is, they don't call
		// other $.* methods. Still need to be derefenced into `util`
		// since the CompletenessTest will overload them with spies.
		type: $.type,
		isEmptyObject: $.isEmptyObject
	};

	/**
	 * CompletenessTest
	 *
	 * @constructor
	 * @example
	 *  var myTester = new CompletenessTest( myLib );
	 * @param {Object} masterVariable The root variable that contains all object
	 *  members. CompletenessTest will recursively traverse objects and keep track
	 *  of all methods.
	 * @param {Function} [ignoreFn] Optionally pass a function to filter out certain
	 *  methods. Example: You may want to filter out instances of jQuery or some
	 *  other constructor. Otherwise "missingTests" will include all methods that
	 *  were not called from that instance.
	 */
	function CompletenessTest( masterVariable, ignoreFn ) {
		var warn,
			that = this;

		// Keep track in these objects. Keyed by strings with the
		// method names (ie. 'my.foo', 'my.bar', etc.) values are boolean true.
		this.injectionTracker = {};
		this.methodCallTracker = {};
		this.missingTests = {};

		this.ignoreFn = ignoreFn === undefined ? function () { return false; } : ignoreFn;

		// Lazy limit in case something weird happends (like recurse (part of) ourself).
		this.lazyLimit = 2000;
		this.lazyCounter = 0;

		// Bind begin and end to QUnit.
		QUnit.begin( function () {
			// Suppress warnings (e.g. deprecation notices for accessing the properties)
			warn = mw.log.warn;
			mw.log.warn = $.noop;

			that.walkTheObject( masterVariable, null, masterVariable, [] );
			log( 'CompletenessTest/walkTheObject', that );

			// Restore warnings
			mw.log.warn = warn;
			warn = undefined;
		} );

		QUnit.done( function () {
			that.populateMissingTests();
			log( 'CompletenessTest/populateMissingTests', that );

			var toolbar, testResults, cntTotal, cntCalled, cntMissing;

			cntTotal = util.keys( that.injectionTracker ).length;
			cntCalled = util.keys( that.methodCallTracker ).length;
			cntMissing = util.keys( that.missingTests ).length;

			function makeTestResults( blob, title, style ) {
				var elOutputWrapper, elTitle, elContainer, elList, elFoot;

				elTitle = document.createElement( 'strong' );
				elTitle.textContent = title || 'Values';

				elList = document.createElement( 'ul' );
				util.each( blob, function ( key ) {
					var elItem = document.createElement( 'li' );
					elItem.textContent = key;
					elList.appendChild( elItem );
				} );

				elFoot = document.createElement( 'p' );
				elFoot.innerHTML = '<em>&mdash; CompletenessTest</em>';

				elContainer = document.createElement( 'div' );
				elContainer.appendChild( elTitle );
				elContainer.appendChild( elList );
				elContainer.appendChild( elFoot );

				elOutputWrapper = document.getElementById( 'qunit-completenesstest' );
				if ( !elOutputWrapper ) {
					elOutputWrapper = document.createElement( 'div' );
					elOutputWrapper.id = 'qunit-completenesstest';
				}
				elOutputWrapper.appendChild( elContainer );

				util.each( style, function ( key, value ) {
					elOutputWrapper.style[ key ] = value;
				} );
				return elOutputWrapper;
			}

			if ( cntMissing === 0 ) {
				// Good
				testResults = makeTestResults(
					{},
					'Detected calls to ' + cntCalled + '/' + cntTotal + ' methods. No missing tests!',
					{
						backgroundColor: '#D2E0E6',
						color: '#366097',
						paddingTop: '1em',
						paddingRight: '1em',
						paddingBottom: '1em',
						paddingLeft: '1em'
					}
				);
			} else {
				// Bad
				testResults = makeTestResults(
					that.missingTests,
					'Detected calls to ' + cntCalled + '/' + cntTotal + ' methods. ' + cntMissing + ' methods not covered:',
					{
						backgroundColor: '#EE5757',
						color: 'black',
						paddingTop: '1em',
						paddingRight: '1em',
						paddingBottom: '1em',
						paddingLeft: '1em'
					}
				);
			}

			toolbar = document.getElementById( 'qunit-testrunner-toolbar' );
			if ( toolbar ) {
				toolbar.insertBefore( testResults, toolbar.firstChild );
			}
		} );

		return this;
	}

	/* Public methods */
	CompletenessTest.fn = CompletenessTest.prototype = {

		/**
		 * CompletenessTest.fn.walkTheObject
		 *
		 * This function recursively walks through the given object, calling itself as it goes.
		 * Depending on the action it either injects our listener into the methods, or
		 * reads from our tracker and records which methods have not been called by the test suite.
		 *
		 * @param {Mixed} currObj The variable to check (initially an object,
		 *  further down it could be anything).
		 * @param {string|null} currName Name of the given object member (Initially this is null).
		 * @param {Object} masterVariable Throughout our interation, always keep track of the master/root.
		 *  Initially this is the same as currVar.
		 * @param {Array} parentPathArray Array of names that indicate our breadcrumb path starting at
		 *  masterVariable. Not including currName.
		 */
		walkTheObject: function ( currObj, currName, masterVariable, parentPathArray ) {
			var key, currVal, type,
				ct = this,
				currPathArray = parentPathArray;

			if ( currName ) {
				currPathArray.push( currName );
				currVal = currObj[ currName ];
			} else {
				currName = '(root)';
				currVal = currObj;
			}

			type = util.type( currVal );

			// Hard ignores
			if ( this.ignoreFn( currVal, this, currPathArray ) ) {
				return null;
			}

			// Handle the lazy limit
			this.lazyCounter++;
			if ( this.lazyCounter > this.lazyLimit ) {
				log( 'CompletenessTest.fn.walkTheObject> Limit reached: ' + this.lazyCounter, currPathArray );
				return null;
			}

			// Functions
			if ( type === 'function' ) {
				// Don't put a spy in constructor functions as it messes with
				// instanceof etc.
				if ( !currVal.prototype || util.isEmptyObject( currVal.prototype ) ) {
					this.injectionTracker[ currPathArray.join( '.' ) ] = true;
					this.injectCheck( currObj, currName, function () {
						ct.methodCallTracker[ currPathArray.join( '.' ) ] = true;
					} );
				}
			}

			// Recursively. After all, this is the *completeness* test
			// This also traverses static properties and the prototype of a constructor
			if ( type === 'object' || type === 'function' ) {
				for ( key in currVal ) {
					if ( hasOwn.call( currVal, key ) ) {
						this.walkTheObject( currVal, key, masterVariable, currPathArray.slice() );
					}
				}
			}
		},

		populateMissingTests: function () {
			var ct = this;
			util.each( ct.injectionTracker, function ( key ) {
				ct.hasTest( key );
			} );
		},

		/**
		 * CompletenessTest.fn.hasTest
		 *
		 * Checks if the given method name (ie. 'my.foo.bar')
		 * was called during the test suite (as far as the tracker knows).
		 * If not it adds it to missingTests.
		 *
		 * @param {string} fnName
		 * @return {boolean}
		 */
		hasTest: function ( fnName ) {
			if ( !( fnName in this.methodCallTracker ) ) {
				this.missingTests[ fnName ] = true;
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
		 * @param {Object} obj The object into which `injectFn` will be inserted
		 * @param {Array} key The key by which `injectFn` will be known in `obj`; if this already
		 *   exists, a wrapper will first call `injectFn` and then the original `obj[key]` function.
		 * @param {Function} injectFn The function to insert
		 */
		injectCheck: function ( obj, key, injectFn ) {
			var spy,
				val = obj[ key ];

			spy = function () {
				injectFn();
				return val.apply( this, arguments );
			};

			// Make the spy inherit from the original so that its static methods are also
			// visible in the spy (e.g. when we inject a check into mw.log, mw.log.warn
			// must remain accessible).
			// XXX: https://github.com/jshint/jshint/issues/2656
			/*jshint ignore:start */
			/*jshint proto:true */
			spy.__proto__ = val;
			/*jshint ignore:end */

			// Objects are by reference, members (unless objects) are not.
			obj[ key ] = spy;
		}
	};

	/* Expose */
	window.CompletenessTest = CompletenessTest;

}( mediaWiki, jQuery ) );
