( function () {
	/**
	 * View model for a single saved query
	 *
	 * @class
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {string} id Unique identifier
	 * @param {string} label Saved query label
	 * @param {Object} data Saved query data
	 * @param {Object} [config] Configuration options
	 * @cfg {boolean} [default] This item is the default
	 */
	mw.rcfilters.dm.SavedQueryItemModel = function MwRcfiltersDmSavedQueriesModel( id, label, data, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		this.id = id;
		this.label = label;
		this.data = data;
		this.default = !!config.default;
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.SavedQueryItemModel );
	OO.mixinClass( mw.rcfilters.dm.SavedQueryItemModel, OO.EventEmitter );

	/* Events */

	/**
	 * @event update
	 *
	 * Model has been updated
	 */

	/* Methods */

	/**
	 * Get an object representing the state of this item
	 *
	 * @return {Object} Object representing the current data state
	 *  of the object
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getState = function () {
		return {
			data: this.getData(),
			label: this.getLabel()
		};
	};

	/**
	 * Get the query's identifier
	 *
	 * @return {string} Query identifier
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getID = function () {
		return this.id;
	};

	/**
	 * Get query label
	 *
	 * @return {string} Query label
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getLabel = function () {
		return this.label;
	};

	/**
	 * Update the query label
	 *
	 * @param {string} newLabel New label
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.updateLabel = function ( newLabel ) {
		if ( newLabel && this.label !== newLabel ) {
			this.label = newLabel;
			this.emit( 'update' );
		}
	};

	/**
	 * Get query data
	 *
	 * @return {Object} Object representing parameter and highlight data
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getData = function () {
		return this.data;
	};

	/**
	 * Get the combined data of this item as a flat object of parameters
	 *
	 * @return {Object} Combined parameter data
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getCombinedData = function () {
		return $.extend( true, {}, this.data.params, this.data.highlights );
	};

	/**
	 * Check whether this item is the default
	 *
	 * @return {boolean} Query is set to be default
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.isDefault = function () {
		return this.default;
	};

	/**
	 * Toggle the default state of this query item
	 *
	 * @param {boolean} isDefault Query is default
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.toggleDefault = function ( isDefault ) {
		isDefault = isDefault === undefined ? !this.default : isDefault;

		if ( this.default !== isDefault ) {
			this.default = isDefault;
			this.emit( 'update' );
		}
	};
}() );
