( function ( mw ) {
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( model ) {
		this.model = model;

		// TODO: When we are ready, update the URL when a filter is updated
		// this.model.connect( this, { itemUpdate: 'updateURL' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Initialize the filter and parameter states
	 */
	mw.rcfilters.Controller.prototype.initialize = function () {
		var uri = new mw.Uri();

		this.model.updateFilters(
			// Translate the url params to filter select states
			this.model.getFiltersFromParameters( uri.query )
		);
	};

	/**
	 * Take the existing static filter list and rebuild it so that only
	 * filters that are not reimplemented as filters in the current system
	 * are appearing.
	 *
	 * This method expects the jQuery object of the $( 'span.rcshowhide' ) in
	 * Special:RecentChanges, and will return a suitable element to replace it.
	 *
	 * HACK: This entire method is a hack, to make sure that existing filters that
	 * are not implemented yet are still visible and operable to the user, but
	 * filters that are implemented in the new system aren't. Just hiding the
	 * spans of those filters creates a very ugly string that includes multiple
	 * pipes (" | | | | existing filter") so this method rebuilds the structure
	 * using only the relevant non-implemented filters, preserving current view.
	 *
	 * NOTE: Since the entire method is one big hack, individual "HACK!" comments
	 * were spared below. To the observant pedantic reader - please mentally add
	 * "HACK" before each line of code.
	 *
	 * @param {jQuery} $filterList jQuery block of the current static filters list
	 * @return {jQuery} jQuery block of the new static filters to display
	 */
	mw.rcfilters.Controller.prototype.rebuildStaticFilterList = function ( $filterList ) {
		var controller = this,
			$newStructure = $filterList.clone( true );

		$newStructure
			.empty()
			.addClass( 'mw-rcfilters-rcshowhide' );
		// Extract the filters
		$filterList.children().each( function () {
			var name,
				classes = $( this ).attr( 'class' ).split( ' ' );

			// Remove the 'rcshowhideoption' class; We're only doing
			// this to make sure that we don't pick the wrong class
			// if split() gave us the wrong order
			classes.splice( classes.indexOf( 'rcshowhideoption' ), 1 );

			// Get rid of the 'rcshow' prefix
			// This is absolutely terrible, because we're making
			// an assumption that all prefixes are these, but
			// since the entire method is a temporary hack, we
			// will pinch our noses and do it
			name = classes[ 0 ].substr( 'rcshow'.length );

			// Ignore filters that exist in the view model
			if ( !controller.model.getItemByName( name ) ) {
				// This filter doesn't exist, add its element
				// back into the new structure
				$newStructure.append( $( this ) );
			}
		} );

		// Return the new element
		return $newStructure;
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

		// Add to existing queries in URL
		// TODO: Clean up the list of filters; perhaps 'falsy' filters
		// shouldn't appear at all? Or compare to existing query string
		// and see if current state of a specific filter is needed?
		uri.extend( this.model.getParametersFromFilters() );

		// Update the URL itself
		window.history.pushState( { tag: 'rcfilters' }, document.title, uri.toString() );
	};
}( mediaWiki ) );
