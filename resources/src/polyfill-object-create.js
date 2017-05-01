/**
 * Simplified version of es5-sham#Object-create that also works around a bug
 * in the actual es5-sham: https://github.com/es-shims/es5-shim/issues/252
 *
 * Does not:
 * - Support empty inheritance via `Object.create(null)`.
 * - Support getter and setter accessors via `Object.create( .., properties )`.
 * - Support custom property descriptor (e.g. writable, configurtable, enumerable).
 * - Leave behind an enumerable "__proto__" all over the place.
 *
 * @author Timo Tijhof, 2014
 */

// ES5 15.2.3.5
// http://es5.github.com/#x15.2.3.5
if ( !Object.create ) {
	( function () {
		var hasOwn = Object.hasOwnProperty,
			// https://developer.mozilla.org/en-US/docs/ECMAScript_DontEnum_attribute#JScript_DontEnum_Bug
			// http://whattheheadsaid.com/2010/10/a-safer-object-keys-compatibility-implementation
			isEnumBug = !{ valueOf: 0 }.propertyIsEnumerable( 'valueOf' );

		// Reusable constructor function for Object.create
		function Empty() {}

		function defineProperty( object, key, property ) {
			if ( hasOwn.call( property, 'value' ) ) {
				object[ key ] = property.value;
			} else {
				object[ key ] = property;
			}
		}

		Object.create = function create( prototype, properties ) {
			var object, key;

			if ( prototype !== Object( prototype ) ) {
				throw new TypeError( 'Called on non-object' );
			}

			Empty.prototype = prototype;
			object = new Empty();

			if ( properties !== undefined ) {
				if ( !isEnumBug ) {
					for ( key in properties ) {
						if ( hasOwn.call( properties, key ) ) {
							defineProperty( object, key, properties[ key ] );
						}
					}
				} else {
					Object.keys( properties ).forEach( function ( key ) {
						defineProperty( object, key, properties[ key ] );
					} );
				}
			}

			return object;
		};

	}() );
}
