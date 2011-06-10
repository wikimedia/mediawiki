/**
 * jQuery QUnit CompletenessTest 0.3
 *
 * Tests the completeness of test suites for object oriented javascript
 * libraries. Written to be used in enviroments with jQuery and QUnit.
 * Requires jQuery 1.5.2 or higher.
 *
 * Globals: jQuery, $, QUnit, console.log
 *
 * Built for and tested with:
 * - Safari 5
 * - Firefox 4
 *
 * @author Timo Tijhof, 2011
 */
(function(){

/* Private members */
var TYPE_SIMPLEFUNC = 101;
var TYPE_OBJCONSTRFUNC = 100;

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

	// Bind begin and end to QUnit.
	var that = this;
	QUnit.begin = function(){
		that.checkTests( null, masterVariable, masterVariable, [], CompletenessTest.ACTION_INJECT );
	};
	QUnit.done = function(){
		that.checkTests( null, masterVariable, masterVariable, [], CompletenessTest.ACTION_CHECK );
		console.log( 'CompletenessTest.ACTION_CHECK', that );

		// Insert HTML into header

		var makeList = function( blob, title, style ) {
			title = title || 'Values';
			var html = '<div style="'+style+'">'
			+ '<strong>' + mw.html.escape(title) + '</strong>';
			$.each( blob, function( key ) {
				html += '<br />' + mw.html.escape(key);
			});
			return html + '<br /><br /><em>&mdash; CompletenessTest</em></div>';
		};
		if ( $.isEmptyObject( that.missingTests ) ) { 
			var testResults = makeList( { 'No missing tests!': true }, 'missingTests', 'background: #D2E0E6; color: #366097; padding:1em' );
		} else {
			var testResults = makeList( that.missingTests, 'missingTests', 'background: #EE5757; color: black; padding: 1em' );
		}
		$( '#qunit-testrunner-toolbar' ).prepend( testResults );
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
	 * @param currName {String}
	 * @param currVar {mixed}
	 * @param masterVariable {Object}
	 * @param parentPathArray {Array}
	 * @param action {Number} What action is checkTests commanded to do ?
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

						// Clone and brake reference to parentPathArray
						var tmpPathArray = $.extend([], parentPathArray);
						tmpPathArray.push('prototype'); tmpPathArray.push(key);

						that.hasTest( tmpPathArray.join( '.' ) );
					} );
				}

			/* INJET MODE */

			} else if ( action === CompletenessTest.ACTION_INJECT ) {

				if ( !currVar.prototype || $.isEmptyObject( currVar.prototype ) ) {

					// Inject check
					that.injectCheck( masterVariable, parentPathArray, function(){

						that.methodCallTracker[ parentPathArray.join( '.' ) ] = true;

					}, TYPE_SIMPLEFUNC );

				// We don't support checking object constructors yet...
				} else {

					// ... the prototypes are fine tho
					$.each( currVar.prototype, function( key, value ) {

						// Clone and brake reference to parentPathArray
						var tmpPathArray = $.extend([], parentPathArray);
						tmpPathArray.push('prototype'); tmpPathArray.push(key);

						that.checkTests( key, value, masterVariable, tmpPathArray, action );
					} );
				}

			} //else { }

		// Recursively. After all, this *is* the completness test
		} else if ( type === 'object' ) {

			$.each( currVar, function( key, value ) {

				// Clone and brake reference to parentPathArray
				var tmpPathArray = $.extend([], parentPathArray);
				tmpPathArray.push(key);

				that.checkTests( key, value, masterVariable, tmpPathArray, action );

			} );

		} // else { }

		return 'End of checkTests';
	},

	/**
	 * CompletenessTest.fn.hasTest
	 *
	 * @param fnName {String}
	 */
	hasTest: function( fnName ) {
		if ( !(fnName in this.methodCallTracker) ) {
			this.missingTests[fnName] = true;
		}
	},

	/**
	 * CompletenessTest.fn.injectCheck
	 *
	 * @param masterVariable {Object}
	 * @param objectPathArray {Array}
	 * @param injectFn {Function}
	 */
	injectCheck: function( masterVariable, objectPathArray, injectFn, functionType ) {
		var	prev,
			curr = masterVariable,
			lastMember;

		$.each(objectPathArray, function(i, memberName){
			prev = curr;
			curr = prev[memberName];
			lastMember = memberName;
		});

		// Objects are by reference, members (unless objects) are not.
		prev[lastMember] = function(){
			injectFn();
			return curr.apply(this, arguments );
		};
	}
};

window.CompletenessTest = CompletenessTest;

})();
