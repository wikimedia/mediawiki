( function ( mw, $ ) {
	/**
	 * View model of all view models
	 * This model combines events and common functionality of the item models
	 * in the system: filters model and namespaces model.
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 */
	mw.rcfilters.dm.CombinedViewModel = function MwRcfiltersDmCombinedViewModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.FiltersViewModel );
	OO.mixinClass( mw.rcfilters.dm.FiltersViewModel, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.FiltersViewModel, OO.EmitterList );

	/* Events */

}( mediaWiki, jQuery ) );
