( function ( mw ) {
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
		this.default = !!config.default;
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
	 *
	 * @returns {Object} Object representing the current data state
	 *  of the object
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getState = function () {
		return {
			data: {
				filters: this.getFilters(),
				highlights: this.getHighlights()
			},
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
	 * @return {label} Query label
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
	 * @return {Object} Object representing parameter and highlight data
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getData = function () {
		return this.data;
	};

	/**
	 * Get query parameters
	 *
	 * @return {Object} Object representing this query's parameters
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getFilters = function () {
		return this.data.filters || {};
	};

	/**
	 * Get highlight parameters
	 *
	 * @return {Object} Object representing highlight parameters
	 */
	mw.rcfilters.dm.SavedQueryItemModel.prototype.getHighlights = function () {
		return this.data.highlights || {};
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
}( mediaWiki ) );
