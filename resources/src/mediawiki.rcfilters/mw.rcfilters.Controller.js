( function ( mw, $ ) {
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( model ) {
		this.model = model;

		this.preferences = {};
		// TODO: When we are ready, update the URL when a filter is updated
		// this.model.connect( this, { itemUpdate: 'updateURL' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Initialize the filter and parameter states
	 */
	mw.rcfilters.Controller.prototype.initialize = function () {
		var paramData,
			controller = this,
			uri = new mw.Uri();

		// Combine preference data with url params (URL should override)
		paramData = $.extend( {}, this.getFilterPreferences(), uri.query );

		// Give the model a full parameter state from which to
		// update the filters
		this.model.updateFilters(
			this.model.getParametersToFilters( paramData )
		);

		this.model.updateParameters( paramData );
	};

	/**
	 * Update the state of a filter
	 *
	 * @param {string} filterName Filter name
	 * @param {boolean} isSelected Filter selected state
	 */
	mw.rcfilters.Controller.prototype.updateFilter = function ( filterName, isSelected ) {
		var obj = {};

		obj[ filterName ] = isSelected;
		this.model.updateFilters( obj );
	};

	/**
	 * Update the URL of the page to reflect current filters
	 */
	mw.rcfilters.Controller.prototype.updateURL = function () {
		var uri = new mw.Uri();

		uri.query = this.makeFiltersQuery();

		// Update the URL itself
		window.history.pushState( uri.query, '', uri.toString() );
	};

	/**
	 * Create the object for the url query based on filter states
	 *
	 * @return {Object} Filter query
	 */
	mw.rcfilters.Controller.prototype.makeFiltersQuery = function () {
		var filter,
			uri = new mw.Uri(),
			currFilters = this.model.getFiltersToParameters();

		// Add to existing queries in URL
		currFilters = $.extend( uri.query, currFilters );

		// TODO: Clean up the list of filters; perhaps 'falsy' filters
		// shouldn't appear at all? Or compare to existing query string
		// and see if current state of a specific filter is needed?
		return currFilters;
	};

	/**
	 * Go over filter definition to get the values in the user preferences
	 */
	mw.rcfilters.Controller.prototype.updateFilterPreferences = function () {
		var prefParamStates = {};

		/*********** TEST ****************/
		mw.user.options.set( 'hidemyself', 1 ); // Test purpose only!
		/*********** TEST ****************/

		$.each( this.model.getFilterGroups(), function( group, data ) {
			var prefValue;

			if ( data.type === 'string_options' ) {
				// Preference is set on the group
				if ( data.preference ) {
					prefValue = mw.user.options.get( data.preference );
					if ( prefValue !== null ) {
						prefParamStates[ group ] = prefValue;
					}
				}
			} else if ( data.type === 'send_unselected_if_any' ) {
				// Preference is set on the individual filters
				data.filters.forEach( function ( filterItem ) {
					if ( filterItem.getPreferenceName() ) {
						prefParamStates[ filterItem.getName() ] = mw.user.options.get( filterItem.getPreferenceName() );
					}
				} );
			}
		} );

		this.preferences = prefParamStates;
	};

	/**
	 * Get the filte values that are in the user preferences
	 *
	 * @return {Object} Filter values from user preferences
	 */
	mw.rcfilters.Controller.prototype.getFilterPreferences = function () {
		if ( $.isEmptyObject( this.preferences ) ) {
			this.updateFilterPreferences();
		}

		return this.preferences;
	};
}( mediaWiki, jQuery ) );
