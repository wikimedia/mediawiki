( function ( mw, $ ) {
	/**
	 * View model for a single saved query
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {string} id Unique identifier
	 * @param {string} label Saved query label
	 * @param {Object} data Saved query data
	 * @param {Object} [config] Configuration options
	 */
	mw.rcfilters.dm.SavedQueryItemModel = function MwRcfiltersDmSavedQueriesModel( id, label, data, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		this.id = id;
		this.label = label || mw.msg( 'rcfilters-savedqueries-defaultlabel' );
		this.data = data;
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.SavedQueryItemModel );
	OO.mixinClass( mw.rcfilters.dm.SavedQueryItemModel, OO.EventEmitter );

	/* Events */

	/**
	 * @update
	 *
	 * Model has been updated
	 */

	/* Methods */

	/**
	 * Get an object representing the state of this item
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getState = function () {
		return {
			data: this.getData(),
			label: this.getLabel()
		};
	};

	mw.rcfilters.dm.SavedQueryItemModel.prototype.getID = function () {
		return this.id;
	};

	mw.rcfilters.dm.SavedQueryItemModel.prototype.getLabel = function () {
		return this.label;
	};

	mw.rcfilters.dm.SavedQueryItemModel.prototype.updateLabel = function ( newLabel ) {
		if ( this.label !== newLabel ) {
			this.label = newLabel;
			this.emit( 'update' );
		}
	};

	mw.rcfilters.dm.SavedQueryItemModel.prototype.getData = function () {
		return this.data;
	};
}( mediaWiki, jQuery ) );
