( function ( mw ) {
	/**
	 * Filter item model
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {string} name Filter name
	 * @param {Object} config Configuration object
	 * @cfg {boolean} [selected] Filter is selected
	 */
	mw.rcfilters.dm.FilterItem = function MwRcfiltersDmFilterItem( name, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		this.name = name;
		this.label = config.label || this.name;
		this.description = config.description;

		this.selected = !!config.selected;
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.FilterItem );
	OO.mixinClass( mw.rcfilters.dm.FilterItem, OO.EventEmitter );

	/* Events */

	/**
	 * @event update
	 * @param {boolean} isSelected Filter is selected
	 * @param {string} color Color used to mark this filter
	 *
	 * The state of this filter has changed
	 */

	/* Methods */

	/**
	 * Get the name of this filter
	 */
	mw.rcfilters.dm.FilterItem.prototype.getName = function () {
		return this.name;
	};
	/**
	 * Get the label of this filter
	 */
	mw.rcfilters.dm.FilterItem.prototype.getLabel = function () {
		return this.label;
	};
	/**
	 * Get the description of this filter
	 */
	mw.rcfilters.dm.FilterItem.prototype.getDescription = function () {
		return this.description;
	};

	/**
	 * Toggle the selected state of the item
	 *
	 * @param {boolean} isSelected Filter is selected
	 * @fires update
	 */
	mw.rcfilters.dm.FilterItem.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected === undefined ? !this.selected : isSelected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;
			this.emit( 'update', this.selected );
		}
	};
} )( mediaWiki );
