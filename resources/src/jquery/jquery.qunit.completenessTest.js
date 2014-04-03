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
( function ( $ ) {
	'use strict';

	var util,
		hasOwn = Object.prototype.hasOwnProperty,
		log = (window.console && window.console.log)
			? function () { return window.console.log.apply(window.console, arguments); }
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
		extend: function () {
			var options, name, src, copy,
				target = arguments[0] || {},
				i = 1,
				length = arguments.length;

			for ( ; i < length; i++ ) {
				options = arguments[ i ];
				// Only deal with non-null/undefined values
				if ( options !== null && options !== undefined ) {
					// Extend the base object
					for ( name in options ) {
						src = target[ name ];
						copy = options[ name ];

						// Prevent never-ending loop
						if ( target === copy ) {
							continue;
						}

						if ( copy !== undefined ) {
							target[ name ] = copy;
						}
					}
				}
			}

			// Return the modified object
			return target;
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
	function CompletenessTest( masterVariable, ignoreFn ) {

		// Keep track in these objects. Keyed by strings with the
		// method names (ie. 'my.foo', 'my.bar', etc.) values are boolean true.
		this.injectionTracker = {};
		this.methodCallTracker = {};
		this.missingTests = {};

		this.ignoreFn = undefined === ignoreFn ? function () { return false; } : ignoreFn;

		// Lazy limit in case something weird happends (like recurse (part of) ourself).
		this.lazyLimit = 2000;
		this.lazyCounter = 0;

		var that = this;

		// Bind begin and end to QUnit.
		QUnit.begin( function () {
			that.walkTheObject( null, masterVariable, masterVariable, [], CompletenessTest.ACTION_INJECT );
			log( 'CompletenessTest/walkTheObject/ACTION_INJECT', that );
		});

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
				});

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
					elOutputWrapper.style[key] = value;
				});
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
		});

		return this;
	}

	/* Static members */
	CompletenessTest.ACTION_INJECT = 500;
	CompletenessTest.ACTION_CHECK = 501;

	/* Public methods */
	CompletenessTest.fn = CompletenessTest.prototype = {

		/**
		 * CompletenessTest.fn.walkTheObject
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
		walkTheObject: function ( currName, currVar, masterVariable, parentPathArray, action ) {

			var key, value, tmpPathArray,
				type = util.type( currVar ),
				that = this;

			// Hard ignores
			if ( this.ignoreFn( currVar, that, parentPathArray ) ) {
				return null;
			}

			// Handle the lazy limit
			this.lazyCounter++;
			if ( this.lazyCounter > this.lazyLimit ) {
				log( 'CompletenessTest.fn.walkTheObject> Limit reached: ' + this.lazyCounter, parentPathArray );
				return null;
			}

			// Functions
			if ( type === 'function' ) {

				if ( !currVar.prototype || util.isEmptyObject( currVar.prototype ) ) {

					if ( action === CompletenessTest.ACTION_INJECT ) {

						that.injectionTracker[ parentPathArray.join( '.' ) ] = true;
						that.injectCheck( masterVariable, parentPathArray, function () {
							that.methodCallTracker[ parentPathArray.join( '.' ) ] = true;
						} );
					}

				// We don't support checking object constructors yet...
				// ...we can check the prototypes fine, though.
				} else {
					if ( action === CompletenessTest.ACTION_INJECT ) {

						for ( key in currVar.prototype ) {
							if ( hasOwn.call( currVar.prototype, key ) ) {
								value = currVar.prototype[key];
								if ( key === 'constructor' ) {
									continue;
								}

								// Clone and break reference to parentPathArray
								tmpPathArray = util.extend( [], parentPathArray );
								tmpPathArray.push( 'prototype' );
								tmpPathArray.push( key );

								that.walkTheObject( key, value, masterVariable, tmpPathArray, action );
							}
						}

					}
				}

			}

			// Recursively. After all, this is the *completeness* test
			if ( type === 'function' || type === 'object' ) {
				for ( key in currVar ) {
					if ( hasOwn.call( currVar, key ) ) {
						value = currVar[key];

						// Clone and break reference to parentPathArray
						tmpPathArray = util.extend( [], parentPathArray );
						tmpPathArray.push( key );

						that.walkTheObject( key, value, masterVariable, tmpPathArray, action );
					}
				}
			}
		},

		populateMissingTests: function () {
			var ct = this;
			util.each( ct.injectionTracker, function ( key ) {
				ct.hasTest( key );
			});
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
		hasTest: function ( fnName ) {
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
		injectCheck: function ( masterVariable, objectPathArray, injectFn ) {
			var i, len, prev, memberName, lastMember,
				curr = masterVariable;

			// Get the object in question through the path from the master variable,
			// We can't pass the value directly because we need to re-define the object
			// member and keep references to the parent object, member name and member
			// value at all times.
			for ( i = 0, len = objectPathArray.length; i < len; i++ ) {
				memberName = objectPathArray[i];

				prev = curr;
				curr = prev[memberName];
				lastMember = memberName;
			}

			// Objects are by reference, members (unless objects) are not.
			prev[lastMember] = function () {
				injectFn();
				return curr.apply( this, arguments );
			};
		}
	};

	/* Expose */
	window.CompletenessTest = CompletenessTest;

}( jQuery ) );
