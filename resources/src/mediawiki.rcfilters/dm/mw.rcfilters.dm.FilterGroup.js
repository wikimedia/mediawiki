( function ( mw ) {
	/**
	 * View model for a filter group
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 * @param {string} name Group name
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [type='send_unselected_if_any'] Group type
	 * @cfg {string} [title] Group title
	 * @cfg {string} [separator='|'] Value separator for 'string_options' groups
	 * @cfg {boolean} [active] Group is active
	 * @cfg {boolean} [fullCoverage] This filters in this group collectively cover all results
	 * @cfg {Object} [conflicts] Defines the conflicts for this filter group
	 * @cfg {Object} [whatsThis] Defines the messages that should appear for the 'what's this' popup
	 * @cfg {string} [whatsThis.header] The header of the whatsThis popup message
	 * @cfg {string} [whatsThis.body] The body of the whatsThis popup message
	 * @cfg {string} [whatsThis.url] The url for the link in the whatsThis popup message
	 * @cfg {string} [whatsThis.linkMessage] The text for the link in the whatsThis popup message
	 */
	mw.rcfilters.dm.FilterGroup = function MwRcfiltersDmFilterGroup( name, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.name = name;
		this.type = config.type || 'send_unselected_if_any';
		this.title = config.title;
		this.separator = config.separator || '|';

		this.active = !!config.active;
		this.fullCoverage = !!config.fullCoverage;

		this.whatsThis = config.whatsThis || {};

		this.conflicts = config.conflicts || {};

		this.aggregate( { update: 'filterItemUpdate' } );
		this.connect( this, { filterItemUpdate: 'onFilterItemUpdate' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.FilterGroup );
	OO.mixinClass( mw.rcfilters.dm.FilterGroup, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.FilterGroup, OO.EmitterList );

	/* Events */

	/**
	 * @event update
	 *
	 * Group state has been updated
	 */

	/* Methods */

	/**
	 * Respond to filterItem update event
	 *
	 * @fires update
	 */
	mw.rcfilters.dm.FilterGroup.prototype.onFilterItemUpdate = function () {
		// Update state
		var active = this.areAnySelected();

		if ( this.active !== active ) {
			this.active = active;
			this.emit( 'update' );
		}
	};

	/**
	 * Get group active state
	 *
	 * @return {boolean} Active state
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isActive = function () {
		return this.active;
	};

	/**
	 * Get group name
	 *
	 * @return {string} Group name
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Get the messags defining the 'whats this' popup for this group
	 *
	 * @return {Object} What's this messages
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getWhatsThis = function () {
		return this.whatsThis;
	};

	/**
	 * Check whether this group has a 'what's this' message
	 *
	 * @return {boolean} This group has a what's this message
	 */
	mw.rcfilters.dm.FilterGroup.prototype.hasWhatsThis = function () {
		return !!this.whatsThis.body;
	};

	/**
	 * Get the conflicts associated with the entire group.
	 * Conflict object is set up by filter name keys and conflict
	 * definition. For example:
	 * [
	 * 		{
	 * 			filterName: {
	 * 				filter: filterName,
	 * 				group: group1
	 * 			}
	 * 		},
	 * 		{
	 * 			filterName2: {
	 * 				filter: filterName2,
	 * 				group: group2
	 * 			}
	 * 		}
	 * ]
	 * @return {Object} Conflict definition
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getConflicts = function () {
		return this.conflicts;
	};

	/**
	 * Set conflicts for this group. See #getConflicts for the expected
	 * structure of the definition.
	 *
	 * @param {Object} conflicts Conflicts for this group
	 */
	mw.rcfilters.dm.FilterGroup.prototype.setConflicts = function ( conflicts ) {
		this.conflicts = conflicts;
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
	mw.rcfilters.dm.FilterGroup.prototype.existsInConflicts = function ( filterItem ) {
		return Object.prototype.hasOwnProperty.call( this.getConflicts(), filterItem.getName() );
	};

	/**
	 * Check whether there are any items selected
	 *
	 * @return {boolean} Any items in the group are selected
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAnySelected = function () {
		return this.getItems().some( function ( filterItem ) {
			return filterItem.isSelected();
		} );
	};

	/**
	 * Check whether all items selected
	 *
	 * @return {boolean} All items are selected
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAllSelected = function () {
		return this.getItems().every( function ( filterItem ) {
			return filterItem.isSelected();
		} );
	};

	/**
	 * Get all selected items in this group
	 *
	 * @param {mw.rcfilters.dm.FilterItem} [excludeItem] Item to exclude from the list
	 * @return {mw.rcfilters.dm.FilterItem[]} Selected items
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getSelectedItems = function ( excludeItem ) {
		var excludeName = ( excludeItem && excludeItem.getName() ) || '';

		return this.getItems().filter( function ( item ) {
			return item.getName() !== excludeName && item.isSelected();
		} );
	};

	/**
	 * Check whether all selected items are in conflict with the given item
	 *
	 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item to test
	 * @return {boolean} All selected items are in conflict with this item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAllSelectedInConflictWith = function ( filterItem ) {
		var selectedItems = this.getSelectedItems( filterItem );

		return selectedItems.length > 0 &&
			(
				// The group as a whole is in conflict with this item
				this.existsInConflicts( filterItem ) ||
				// All selected items are in conflict individually
				selectedItems.every( function ( selectedFilter ) {
					return selectedFilter.existsInConflicts( filterItem );
				} )
			);
	};

	/**
	 * Check whether any of the selected items are in conflict with the given item
	 *
	 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item to test
	 * @return {boolean} Any of the selected items are in conflict with this item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAnySelectedInConflictWith = function ( filterItem ) {
		var selectedItems = this.getSelectedItems( filterItem );

		return selectedItems.length > 0 && (
			// The group as a whole is in conflict with this item
			this.existsInConflicts( filterItem ) ||
			// Any selected items are in conflict individually
			selectedItems.some( function ( selectedFilter ) {
				return selectedFilter.existsInConflicts( filterItem );
			} )
		);
	};

	/**
	 * Get the parameter representation from this group
	 *
	 * @return {Object} Parameter representation
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getParamRepresentation = function () {
		var i, values,
			result = {},
			filterItems = this.getItems();

		if ( this.getType() === 'send_unselected_if_any' ) {
			// First, check if any of the items are selected at all.
			// If none is selected, we're treating it as if they are
			// all false

			// Go over the items and define the correct values
			for ( i = 0; i < filterItems.length; i++ ) {
				result[ filterItems[ i ].getParamName() ] = this.areAnySelected() ?
					Number( !filterItems[ i ].isSelected() ) : 0;
			}

		} else if ( this.getType() === 'string_options' ) {
			values = [];
			for ( i = 0; i < filterItems.length; i++ ) {
				if ( filterItems[ i ].isSelected() ) {
					values.push( filterItems[ i ].getParamName() );
				}
			}

			result[ this.getName() ] = ( values.length === filterItems.length ) ?
				'all' : values.join( this.getSeparator() );
		}

		return result;
	};

	/**
	 * Get group type
	 *
	 * @return {string} Group type
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getType = function () {
		return this.type;
	};

	/**
	 * Get the prefix used for the filter names inside this group
	 *
	 * @return {string} Group prefix
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getNamePrefix = function () {
		return this.getName() + '__';
	};

	/**
	 * Get group's title
	 *
	 * @return {string} Title
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getTitle = function () {
		return this.title;
	};

	/**
	 * Get group's values separator
	 *
	 * @return {string} Values separator
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getSeparator = function () {
		return this.separator;
	};

	/**
	 * Check whether the group is defined as full coverage
	 *
	 * @return {boolean} Group is full coverage
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isFullCoverage = function () {
		return this.fullCoverage;
	};
}( mediaWiki ) );
