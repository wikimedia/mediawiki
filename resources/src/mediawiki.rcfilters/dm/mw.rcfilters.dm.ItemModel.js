( function ( mw ) {
	/**
	 * RCFilter base item model
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {string} param Filter param name
	 * @param {Object} config Configuration object
	 * @cfg {string} [label] The label for the filter
	 * @cfg {string} [description] The description of the filter
	 * @cfg {string|Object} [labelPrefixKey] An i18n key defining the prefix label for this
	 *  group. If the prefix has 'invert' state, the parameter is expected to be an object
	 *  with 'default' and 'inverted' as keys.
	 * @cfg {boolean} [active=true] The filter is active and affecting the result
	 * @cfg {boolean} [selected] The item is selected
	 * @cfg {boolean} [inverted] The item is inverted, meaning the search is excluding
	 *  this parameter.
	 * @cfg {string} [namePrefix='item_'] A prefix to add to the param name to act as a unique
	 *  identifier
	 * @cfg {string} [cssClass] The class identifying the results that match this filter
	 * @cfg {string[]} [identifiers] An array of identifiers for this item. They will be
	 *  added and considered in the view.
	 */
	mw.rcfilters.dm.ItemModel = function MwRcfiltersDmItemModel( param, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		this.param = param;
		this.namePrefix = config.namePrefix || 'item_';
		this.name = this.namePrefix + param;

		this.label = config.label || this.name;
		this.labelPrefixKey = config.labelPrefixKey;
		this.description = config.description || '';
		this.selected = !!config.selected;

		this.inverted = !!config.inverted;
		this.identifiers = config.identifiers || [];

		// Highlight
		this.cssClass = config.cssClass;
		this.highlightColor = null;
		this.highlightEnabled = false;
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.ItemModel );
	OO.mixinClass( mw.rcfilters.dm.ItemModel, OO.EventEmitter );

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
	mw.rcfilters.dm.ItemModel.prototype.getState = function () {
		return {
			selected: this.isSelected(),
			inverted: this.isInverted()
		};
	};

	/**
	 * Get the name of this filter
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.dm.ItemModel.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Get a prefixed label
	 *
	 * @return {string} Prefixed label
	 */
	mw.rcfilters.dm.ItemModel.prototype.getPrefixedLabel = function () {
		if ( this.labelPrefixKey ) {
			if ( typeof this.labelPrefixKey === 'string' ) {
				return mw.message( this.labelPrefixKey, this.getLabel() ).parse();
			} else {
				return mw.message(
					this.labelPrefixKey[
						// Only use inverted-prefix if the item is selected
						// Highlight-only an inverted item makes no sense
						this.isInverted() && this.isSelected() ?
							'inverted' : 'default'
					],
					this.getLabel()
				).parse();
			}
		} else {
			return this.getLabel();
		}
	};

	/**
	 * Get the param name or value of this filter
	 *
	 * @return {string} Filter param name
	 */
	mw.rcfilters.dm.ItemModel.prototype.getParamName = function () {
		return this.param;
	};

	/**
	 * Get the message representing the state of this model.
	 *
	 * @return {string} State message
	 */
	mw.rcfilters.dm.ItemModel.prototype.getStateMessage = function () {
		// Display description
		return this.getDescription();
	};

	/**
	 * Get the label of this filter
	 *
	 * @return {string} Filter label
	 */
	mw.rcfilters.dm.ItemModel.prototype.getLabel = function () {
		return this.label;
	};

	/**
	 * Get the description of this filter
	 *
	 * @return {string} Filter description
	 */
	mw.rcfilters.dm.ItemModel.prototype.getDescription = function () {
		return this.description;
	};

	/**
	 * Get the default value of this filter
	 *
	 * @return {boolean} Filter default
	 */
	mw.rcfilters.dm.ItemModel.prototype.getDefault = function () {
		return this.default;
	};

	/**
	 * Get the selected state of this filter
	 *
	 * @return {boolean} Filter is selected
	 */
	mw.rcfilters.dm.ItemModel.prototype.isSelected = function () {
		return this.selected;
	};

	/**
	 * Toggle the selected state of the item
	 *
	 * @param {boolean} [isSelected] Filter is selected
	 * @fires update
	 */
	mw.rcfilters.dm.ItemModel.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected === undefined ? !this.selected : isSelected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;
			this.emit( 'update' );
		}
	};

	/**
	 * Get the inverted state of this item
	 *
	 * @return {boolean} Item is inverted
	 */
	mw.rcfilters.dm.ItemModel.prototype.isInverted = function () {
		return this.inverted;
	};

	/**
	 * Toggle the inverted state of the item
	 *
	 * @param {boolean} [isInverted] Item is inverted
	 * @fires update
	 */
	mw.rcfilters.dm.ItemModel.prototype.toggleInverted = function ( isInverted ) {
		isInverted = isInverted === undefined ? !this.inverted : isInverted;

		if ( this.inverted !== isInverted ) {
			this.inverted = isInverted;
			this.emit( 'update' );
		}
	};

	/**
	 * Set the highlight color
	 *
	 * @param {string|null} highlightColor
	 */
	mw.rcfilters.dm.ItemModel.prototype.setHighlightColor = function ( highlightColor ) {
		if ( this.highlightColor !== highlightColor ) {
			this.highlightColor = highlightColor;
			this.emit( 'update' );
		}
	};

	/**
	 * Clear the highlight color
	 */
	mw.rcfilters.dm.ItemModel.prototype.clearHighlightColor = function () {
		this.setHighlightColor( null );
	};

	/**
	 * Get the highlight color, or null if none is configured
	 *
	 * @return {string|null}
	 */
	mw.rcfilters.dm.ItemModel.prototype.getHighlightColor = function () {
		return this.highlightColor;
	};

	/**
	 * Get the CSS class that matches changes that fit this filter
	 * or null if none is configured
	 *
	 * @return {string|null}
	 */
	mw.rcfilters.dm.ItemModel.prototype.getCssClass = function () {
		return this.cssClass;
	};

	/**
	 * Get the item's identifiers
	 *
	 * @return {string[]}
	 */
	mw.rcfilters.dm.ItemModel.prototype.getIdentifiers = function () {
		return this.identifiers;
	};

	/**
	 * Toggle the highlight feature on and off for this filter.
	 * It only works if highlight is supported for this filter.
	 *
	 * @param {boolean} enable Highlight should be enabled
	 */
	mw.rcfilters.dm.ItemModel.prototype.toggleHighlight = function ( enable ) {
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
	mw.rcfilters.dm.ItemModel.prototype.isHighlightEnabled = function () {
		return !!this.highlightEnabled;
	};

	/**
	 * Check if the highlight feature is supported for this filter
	 *
	 * @return {boolean}
	 */
	mw.rcfilters.dm.ItemModel.prototype.isHighlightSupported = function () {
		return !!this.getCssClass();
	};

	/**
	 * Check if the filter is currently highlighted
	 *
	 * @return {boolean}
	 */
	mw.rcfilters.dm.ItemModel.prototype.isHighlighted = function () {
		return this.isHighlightEnabled() && !!this.getHighlightColor();
	};
}( mediaWiki ) );
