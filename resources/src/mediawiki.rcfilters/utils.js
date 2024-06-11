/**
 * Utils used by RecentChanges Filters.
 *
 * @namespace rcfilters.utils
 * @private
 */
module.exports = {
	/**
	 * @param {Node[]} arr
	 * @param {Node[]|Node} elements
	 * @return {Node[]}
	 */
	addArrayElementsUnique: function ( arr, elements ) {
		elements = Array.isArray( elements ) ? elements : [ elements ];

		elements.forEach( ( element ) => {
			if ( arr.indexOf( element ) === -1 ) {
				arr.push( element );
			}
		} );

		return arr;
	},
	/**
	 * @param {string[]} givenOptions
	 * @param {string[]} legalOptions
	 * @param {boolean} [supportsAll] defaults to true.
	 * @return {string[]}
	 */
	normalizeParamOptions: function ( givenOptions, legalOptions, supportsAll ) {
		var result = [];
		supportsAll = supportsAll === undefined ? true : !!supportsAll;

		if ( supportsAll && givenOptions.indexOf( 'all' ) > -1 ) {
			// If anywhere in the values there's 'all', we
			// treat it as if only 'all' was selected.
			// Example: param=valid1,valid2,all
			// Result: param=all
			return [ 'all' ];
		}

		// Get rid of any dupe and invalid parameter, only output
		// valid ones
		// Example: param=valid1,valid2,invalid1,valid1
		// Result: param=valid1,valid2
		givenOptions.forEach( ( value ) => {
			if (
				legalOptions.indexOf( value ) > -1 &&
				result.indexOf( value ) === -1
			) {
				result.push( value );
			}
		} );

		return result;
	}
};
