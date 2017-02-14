( function ( mw ) {
	/**
	 * Filter item model
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {string} name Filter name
	 * @param {mw.rcfilters.dm.FilterGroup} groupModel Filter group model
	 * @param {Object} config Configuration object
	 * @cfg {string} [group] The group this item belongs to
	 * @cfg {string} [label] The label for the filter
	 * @cfg {string} [description] The description of the filter
	 * @cfg {boolean} [active=true] The filter is active and affecting the result
	 * @cfg {string[]} [excludes=[]] A list of filter names this filter, if
	 *  selected, makes inactive.
	 * @cfg {boolean} [selected] The item is selected
	 * @cfg {string[]} [subset] Defining the names of filters that are a subset of this filter
	 * @cfg {string[]} [conflictsWith] Defining the names of filters that conflict with this item
	 * @cfg {string} [cssClass] The class identifying the results that match this filter
	 */
	mw.rcfilters.dm.FilterItem = function MwRcfiltersDmFilterItem( name, groupModel, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		this.name = name;
		this.groupModel = groupModel;

		this.label = config.label ? config.label : this.name;
		this.description = config.description;
		this.selected = !!config.selected;

		// Interaction definitions
		this.subset = config.subset || [];
		this.conflicts = config.conflicts || [];
		this.superset = [];

		// Interaction states
		this.included = false;
		this.conflicted = false;
		this.fullyCovered = false;

		// Highlight
		this.cssClass = config.cssClass;
		this.highlightColor = null;
		this.highlightEnabled = false;
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
	 * Return the representation of the state of this item.
	 *
	 * @return {Object} State of the object
	 */
	mw.rcfilters.dm.FilterItem.prototype.getState = function () {
		return {
			selected: this.isSelected(),
			included: this.isIncluded(),
			conflicted: this.isConflicted(),
			fullyCovered: this.isFullyCovered()
		};
	};

	/**
	 * Get the name of this filter
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.dm.FilterItem.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Get the model of the group this filter belongs to
	 *
	 * @return {mw.rcfilters.dm.FilterGroup} Filter group model
	 */
	mw.rcfilters.dm.FilterItem.prototype.getGroupModel = function () {
		return this.groupModel;
	};

	/**
	 * Get the group name this filter belongs to
	 *
	 * @return {string} Filter group name
	 */
	mw.rcfilters.dm.FilterItem.prototype.getGroupName = function () {
		return this.groupModel.getName();
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
	 * This is a list of filter names that are defined to be included
	 * when this filter is selected.
	 *
	 * @return {string[]} Filter subset
	 */
	mw.rcfilters.dm.FilterItem.prototype.getSubset = function () {
		return this.subset;
	};

	/**
	 * Get filter superset
	 * This is a generated list of filters that define this filter
	 * to be included when either of them is selected.
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
	 * @return {boolean} Filter is in an already-included subset
	 */
	mw.rcfilters.dm.FilterItem.prototype.isIncluded = function () {
		return this.included;
	};

	/**
	 * Check whether the filter is currently fully covered
	 *
	 * @return {boolean} Filter is in fully-covered state
	 */
	mw.rcfilters.dm.FilterItem.prototype.isFullyCovered = function () {
		return this.fullyCovered;
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
	 * Check whether this item has a potential conflict with the given item
	 *
	 * This checks whether the given item is in the list of conflicts of
	 * the current item, but makes no judgment about whether the conflict
	 * is currently at play (either one of the items may not be selected)
	 *
	 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item
	 * @return {boolean} This item has a conflict with the given item
	 */
	mw.rcfilters.dm.FilterItem.prototype.existsInConflicts = function ( filterItem ) {
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

	/**
	 * Toggle the fully covered state of the item
	 *
	 * @param {boolean} [isFullyCovered] Filter is fully covered
	 * @fires update
	 */
	mw.rcfilters.dm.FilterItem.prototype.toggleFullyCovered = function ( isFullyCovered ) {
		isFullyCovered = isFullyCovered === undefined ? !this.fullycovered : isFullyCovered;

		if ( this.fullyCovered !== isFullyCovered ) {
			this.fullyCovered = isFullyCovered;
			this.emit( 'update' );
		}
	};

	/**
	 * Set the highlight color
	 *
	 * @param {string|null} highlightColor
	 */
	mw.rcfilters.dm.FilterItem.prototype.setHighlightColor = function ( highlightColor ) {
		if ( this.highlightColor !== highlightColor ) {
			this.highlightColor = highlightColor;
			this.emit( 'update' );
		}
	};

	/**
	 * Clear the highlight color
	 */
	mw.rcfilters.dm.FilterItem.prototype.clearHighlightColor = function () {
		this.setHighlightColor( null );
	};

	/**
	 * Get the highlight color, or null if none is configured
	 *
	 * @return {string|null}
	 */
	mw.rcfilters.dm.FilterItem.prototype.getHighlightColor = function () {
		return this.highlightColor;
	};

	/**
	 * Get the CSS class that matches changes that fit this filter
	 * or null if none is configured
	 *
	 * @return {string|null}
	 */
	mw.rcfilters.dm.FilterItem.prototype.getCssClass = function () {
		return this.cssClass;
	};

	/**
	 * Toggle the highlight feature on and off for this filter.
	 * It only works if highlight is supported for this filter.
	 *
	 * @param {boolean} enable Highlight should be enabled
	 */
	mw.rcfilters.dm.FilterItem.prototype.toggleHighlight = function ( enable ) {
		enable = enable === undefined ? !this.highlightEnabled : enable;

		if ( !this.isHighlightSupported() ) {
			return;
		}

		if ( enable === this.highlightEnabled ) {
			return;
		}

		this.highlightEnabled = enable;
		this.emit( 'update' );
	};

	/**
	 * Check if the highlight feature is currently enabled for this filter
	 *
	 * @return {boolean}
	 */
	mw.rcfilters.dm.FilterItem.prototype.isHighlightEnabled = function () {
		return !!this.highlightEnabled;
	};

	/**
	 * Check if the highlight feature is supported for this filter
	 *
	 * @return {boolean}
	 */
	mw.rcfilters.dm.FilterItem.prototype.isHighlightSupported = function () {
		return !!this.getCssClass();
	};

	/**
	 * Check if the filter is currently highlighted
	 *
	 * @return {boolean}
	 */
	mw.rcfilters.dm.FilterItem.prototype.isHighlighted = function () {
		return this.isHighlightEnabled() && !!this.getHighlightColor();
	};
}( mediaWiki ) );
