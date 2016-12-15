( function ( mw ) {
	/**
	 * Filter item model
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {string} name Filter name
	 * @param {Object} config Configuration object
	 * @cfg {string} [group] The group this item belongs to
	 * @cfg {string} [label] The label for the filter
	 * @cfg {string} [description] The description of the filter
	 * @cfg {boolean} [selected] Filter is selected
	 * @cfg {boolean} [active=true] The filter is active and affecting the result
	 * @cfg {string[]} [excludes=array()] A list of filter names this filter, if
	 *  selected, makes inactive.
	 */
	mw.rcfilters.dm.FilterItem = function MwRcfiltersDmFilterItem( name, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		this.name = name;
		this.group = config.group || '';
		this.label = config.label || this.name;
		this.description = config.description;

		this.selected = !!config.selected;
		this.active = config.active === undefined ? true : !!config.active;
		this.excludes = config.excludes || [];
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.FilterItem );
	OO.mixinClass( mw.rcfilters.dm.FilterItem, OO.EventEmitter );

	/* Events */

	/**
	 * @event update
	 *
	 * The state of this filter has changed
	 */

	/* Methods */

	/**
	 * Get the name of this filter
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.dm.FilterItem.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Get the group name this filter belongs to
	 *
	 * @return {string} Filter group name
	 */
	mw.rcfilters.dm.FilterItem.prototype.getGroup = function () {
		return this.group;
	};

	/**
	 * Get the label of this filter
	 *
	 * @return {string} Filter label
	 */
	mw.rcfilters.dm.FilterItem.prototype.getLabel = function () {
		return this.label;
	};

	/**
	 * Get the description of this filter
	 *
	 * @return {string} Filter description
	 */
	mw.rcfilters.dm.FilterItem.prototype.getDescription = function () {
		return this.description;
	};

	/**
	 * Get the selected state of this filter
	 *
	 * @return {boolean} Filter is selected
	 */
	mw.rcfilters.dm.FilterItem.prototype.isSelected = function () {
		return this.selected;
	};

	/**
	 * Check if this filter is active
	 *
	 * @return {boolean} Filter is active
	 */
	mw.rcfilters.dm.FilterItem.prototype.isActive = function () {
		return this.active;
	};

	/**
	 * Check if this filter has a list of excluded filters
	 *
	 * @return {boolean} Filter has a list of excluded filters
	 */
	mw.rcfilters.dm.FilterItem.prototype.hasExcludedFilters = function () {
		return !!this.excludes.length;
	};

	/**
	 * Get this filter's list of excluded filters
	 *
	 * @return {string[]} Array of excluded filter names
	 */
	mw.rcfilters.dm.FilterItem.prototype.getExcludedFilters = function () {
		return this.excludes;
	};

	/**
	 * Toggle the active state of the item
	 *
	 * @param {boolean} [isActive] Filter is active
	 * @fires update
	 */
	mw.rcfilters.dm.FilterItem.prototype.toggleActive = function ( isActive ) {
		isActive = isActive === undefined ? !this.active : isActive;

		if ( this.active !== isActive ) {
			this.active = isActive;
			this.emit( 'update' );
		}
	};

	/**
	 * Toggle the selected state of the item
	 *
	 * @param {boolean} [isSelected] Filter is selected
	 * @fires update
	 */
	mw.rcfilters.dm.FilterItem.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected === undefined ? !this.selected : isSelected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;
			this.emit( 'update' );
		}
	};

}( mediaWiki ) );
