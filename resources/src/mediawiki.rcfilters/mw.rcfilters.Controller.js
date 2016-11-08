( function ( mw, $ ) {
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( model, config ) {
		config = config || {};

		this.model = model;
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

} )( mediaWiki, jQuery );
