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
	 * @cfg {boolean} [active=true] The filter is active and affecting the result
	 * @cfg {string[]} [excludes=[]] A list of filter names this filter, if
	 *  selected, makes inactive.
	 * @cfg {boolean} [selected] The item is selected
	 * @cfg {string[]} [subset] Defining the names of filters that are a subset of this filter
	 * @cfg {string[]} [conflictsWith] Defining the names of filters that conflic with this item
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

		// Interaction definitions
		this.subset = config.subset || [];
		this.conflicts = config.conflicts || [];
		this.superset = [];

		// Interaction states
		this.included = false;
		this.conflicted = false;

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
	 * Get the default value of this filter
	 *
	 * @return {boolean} Filter default
	 */
	mw.rcfilters.dm.FilterItem.prototype.getDefault = function () {
		return this.default;
	};

	/**
	 * Get filter subset
	 *
	 * @return {string[]} Filter subset
	 */
	mw.rcfilters.dm.FilterItem.prototype.getSubset = function () {
		return this.subset;
	};

	/**
	 * Get filter superset
	 *
	 * @return {string[]} Filter superset
	 */
	mw.rcfilters.dm.FilterItem.prototype.getSuperset = function () {
		return this.superset;
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
	 * Check whether the filter is currently in a conflict state
	 *
	 * @return {boolean} Filter is in conflict state
	 */
	mw.rcfilters.dm.FilterItem.prototype.isConflicted = function () {
		return this.conflicted;
	};

	/**
	 * Check whether the filter is currently in an already included subset
	 *
	 * @return {boolean} Filter is in an alread-included subset
	 */
	mw.rcfilters.dm.FilterItem.prototype.isIncluded = function () {
		return this.included;
	};

	/**
	 * Get filter conflicts
	 *
	 * @return {string[]} Filter conflicts
	 */
	mw.rcfilters.dm.FilterItem.prototype.getConflicts = function () {
		return this.conflicts;
	};

	/**
	 * Set filter conflicts
	 *
	 * @param {string[]} conflicts Filter conflicts
	 */
	mw.rcfilters.dm.FilterItem.prototype.setConflicts = function ( conflicts ) {
		this.conflicts = conflicts || [];
	};

	/**
	 * Set filter superset
	 *
	 * @param {string[]} superset Filter superset
	 */
	mw.rcfilters.dm.FilterItem.prototype.setSuperset = function ( superset ) {
		this.superset = superset || [];
	};

	/**
	 * Check whether a filter exists in the subset list for this filter
	 *
	 * @param {string} filterName Filter name
	 * @return {boolean} Filter name is in the subset list
	 */
	mw.rcfilters.dm.FilterItem.prototype.existsInSubset = function ( filterName ) {
		return this.subset.indexOf( filterName ) > -1;
	};

	/**
	 * Check whether this item is conflicting a given item
	 *
	 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter name
	 * @return {boolean} This item has a conflict with the given item
	 */
	mw.rcfilters.dm.FilterItem.prototype.hasConflictWith = function ( filterItem ) {
		return this.conflicts.indexOf( filterItem.getName() ) > -1;
	};

	/**
	 * Set the state of this filter as being conflicted
	 * (This means any filters in its conflicts are selected)
	 *
	 * @param {boolean} [conflicted] Filter is in conflict state
	 * @fires update
	 */
	mw.rcfilters.dm.FilterItem.prototype.toggleConflicted = function ( conflicted ) {
		conflicted = conflicted === undefined ? !this.conflicted : conflicted;

		if ( this.conflicted !== conflicted ) {
			this.conflicted = conflicted;
			this.emit( 'update' );
		}
	};

	/**
	 * Set the state of this filter as being already included
	 * (This means any filters in its superset are selected)
	 *
	 * @param {boolean} [included] Filter is included as part of a subset
	 * @fires update
	 */
	mw.rcfilters.dm.FilterItem.prototype.toggleIncluded = function ( included ) {
		included = included === undefined ? !this.included : included;

		if ( this.included !== included ) {
			this.included = included;
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
