( function ( mw ) {
	/**
	 * Namespace item model
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {integer} namespaceID Namespace ID
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.dm.NamespaceItem = function MwRcfiltersDmNamespaceItem( name, namespaceID, label, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		this.name = name;
		this.namespaceID = namespaceID;
		this.label = label;

		this.description = config.description;
		this.selected = !!config.selected;
		this.excluded = !!config.excluded;

		// Highlight
		this.cssClass = config.cssClass;
		this.highlightColor = null;
		this.highlightEnabled = false;
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.NamespaceItem );
	OO.mixinClass( mw.rcfilters.dm.NamespaceItem, OO.EventEmitter );

	/* Events */

	/**
	 * @event update
	 *
	 * The state of this filter has changed
	 */

	/* Methods */

	mw.rcfilters.dm.NamespaceItem.prototype.getType = function () {
		return 'namespace';
	};
	/**
	 * Return the representation of the state of this item.
	 *
	 * @return {Object} State of the object
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.getState = function () {
		return {
			selected: this.isSelected()
		};
	};

	/**
	 * Get the namespace ID
	 *
	 * @return {number} Namespace ID
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.getNamespaceID = function () {
		return this.namespaceID;
	};

	/**
	 * Get the item name
	 *
	 * @return {string} Unique item name
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Get the label of this filter
	 *
	 * @return {string} Filter label
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.getLabel = function () {
		return this.label;
	};

	/**
	 * Get the description of this filter
	 *
	 * @return {string} Filter description
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.getDescription = function () {
		return this.description;
	};

	mw.rcfilters.dm.NamespaceItem.prototype.getStateMessage = function () {
		return this.description;
	};

	/**
	 * Get the selected state of this filter
	 *
	 * @return {boolean} Filter is selected
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.isSelected = function () {
		return this.selected;
	};

	/**
	 * Toggle the selected state of the item
	 *
	 * @param {boolean} [isSelected] Filter is selected
	 * @fires update
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected === undefined ? !this.selected : isSelected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;
			this.emit( 'update' );
		}
	};

	/**
	 * Get the excluded state of this filter
	 *
	 * @return {boolean} Filter is excluded
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.isExcluded = function () {
		return this.excluded;
	};

	/**
	 * Toggle the selected state of the item
	 *
	 * @param {boolean} [isExcluded] Filter is excluded
	 * @fires update
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.toggleExcluded = function ( isExcluded ) {
		isExcluded = isExcluded === undefined ? !this.excluded : isExcluded;

		if ( this.excluded !== isExcluded ) {
			this.excluded = isExcluded;
			this.emit( 'update' );
		}
	};
}( mediaWiki ) );
