/*
 * JavaScript backwards-compatibility alternatives and other convenience functions
 */
( function ( $ ) {

	$.extend({
		trimLeft: function ( str ) {
			return str === null ? '' : str.toString().replace( /^\s+/, '' );
		},
		trimRight: function ( str ) {
			return str === null ?
					'' : str.toString().replace( /\s+$/, '' );
		},
		ucFirst: function ( str ) {
			return str.charAt( 0 ).toUpperCase() + str.substr( 1 );
		},
		escapeRE: function ( str ) {
			return str.replace ( /([\\{}()|.?*+\-\^$\[\]])/g, "\\$1" );
		},
		isDomElement: function ( el ) {
			return !!el && !!el.nodeType;
		},
		isEmpty: function ( v ) {
			if ( v === '' || v === 0 || v === '0' || v === null
				|| v === false || v === undefined )
			{
				return true;
			}
			// the for-loop could potentially contain prototypes
			// to avoid that we check it's length first
			if ( v.length === 0 ) {
				return true;
			}
			if ( typeof v === 'object' ) {
				for ( var key in v ) {
					return false;
				}
				return true;
			}
			return false;
		},
		compareArray: function ( arrThis, arrAgainst ) {
			if ( arrThis.length !== arrAgainst.length ) {
				return false;
			}
			for ( var i = 0; i < arrThis.length; i++ ) {
				if ( $.isArray( arrThis[i] ) ) {
					if ( !$.compareArray( arrThis[i], arrAgainst[i] ) ) {
						return false;
					}
				} else if ( arrThis[i] !== arrAgainst[i] ) {
					return false;
				}
			}
			return true;
		},
		compareObject: function ( objectA, objectB ) {
			var prop, type;

			// Do a simple check if the types match
			if ( typeof objectA === typeof objectB ) {

				// Only loop over the contents if it really is an object
				if ( typeof objectA === 'object' ) {
					// If they are aliases of the same object (ie. mw and mediaWiki) return now
					if ( objectA === objectB ) {
						return true;
					} else {
						// Iterate over each property
						for ( prop in objectA ) {
							// Check if this property is also present in the other object
							if ( prop in objectB ) {
								// Compare the types of the properties
								type = typeof objectA[prop];
								if ( type === typeof objectB[prop] ) {
									// Recursively check objects inside this one
									switch ( type ) {
										case 'object' :
											if ( !$.compareObject( objectA[prop], objectB[prop] ) ) {
												return false;
											}
											break;
										case 'function' :
											// Functions need to be strings to compare them properly
											if ( objectA[prop].toString() !== objectB[prop].toString() ) {
												return false;
											}
											break;
										default:
											// Strings, numbers
											if ( objectA[prop] !== objectB[prop] ) {
												return false;
											}
											break;
									}
								} else {
									return false;
								}
							} else {
								return false;
							}
						}
						// Check for properties in B but not in A
						// This is about 15% faster (tested in Safari 5 and Firefox 3.6)
						// ...than incrementing a count variable in the above and below loops
						// See also: http://www.mediawiki.org/wiki/ResourceLoader/Default_modules/compareObject_test#Results
						for ( prop in objectB ) {
							if ( !( prop in objectA ) ) {
								return false;
							}
						}
					}
				}
			} else {
				return false;
			}
			return true;
		}
	});

}( jQuery ) );
