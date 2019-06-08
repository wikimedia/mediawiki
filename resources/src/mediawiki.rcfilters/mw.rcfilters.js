( function () {
	/**
	 * @class
	 * @singleton
	 */
	mw.rcfilters = {
		Controller: require( './Controller.js' ),
		UriProcessor: require( './UriProcessor.js' ),
		dm: {
			ChangesListViewModel: require( './dm/ChangesListViewModel.js' ),
			FilterGroup: require( './dm/FilterGroup.js' ),
			FilterItem: require( './dm/FilterItem.js' ),
			FiltersViewModel: require( './dm/FiltersViewModel.js' ),
			ItemModel: require( './dm/ItemModel.js' ),
			SavedQueriesModel: require( './dm/SavedQueriesModel.js' ),
			SavedQueryItemModel: require( './dm/SavedQueryItemModel.js' )
		},
		ui: {},
		utils: {
			addArrayElementsUnique: function ( arr, elements ) {
				elements = Array.isArray( elements ) ? elements : [ elements ];

				elements.forEach( function ( element ) {
					if ( arr.indexOf( element ) === -1 ) {
						arr.push( element );
					}
				} );

				return arr;
			},
			normalizeParamOptions: function ( givenOptions, legalOptions ) {
				var result = [];

				if ( givenOptions.indexOf( 'all' ) > -1 ) {
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
				givenOptions.forEach( function ( value ) {
					if (
						legalOptions.indexOf( value ) > -1 &&
						result.indexOf( value ) === -1
					) {
						result.push( value );
					}
				} );

				return result;
			}
		}
	};

	module.exports = mw.rcfilters;
}() );
