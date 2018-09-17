( function () {
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
	 * @cfg {*} [value] The value of this item
	 * @cfg {string} [namePrefix='item_'] A prefix to add to the param name to act as a unique
	 *  identifier
	 * @cfg {string} [cssClass] The class identifying the results that match this filter
	 * @cfg {string[]} [identifiers] An array of identifiers for this item. They will be
	 *  added and considered in the view.
	 * @cfg {string} [defaultHighlightColor=null] If set, highlight this filter by default with this color
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
		this.setValue( config.value || config.selected );

		this.identifiers = config.identifiers || [];

		// Highlight
		this.cssClass = config.cssClass;
		this.highlightColor = config.defaultHighlightColor || null;
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
			selected: this.isSelected()
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
	 * Get the message key to use to wrap the label. This message takes the label as a parameter.
	 *
	 * @param {boolean} inverted Whether this item should be considered inverted
	 * @return {string|null} Message key, or null if no message
	 */
	mw.rcfilters.dm.ItemModel.prototype.getLabelMessageKey = function ( inverted ) {
		if ( this.labelPrefixKey ) {
			if ( typeof this.labelPrefixKey === 'string' ) {
				return this.labelPrefixKey;
			}
			return this.labelPrefixKey[
				// Only use inverted-prefix if the item is selected
				// Highlight-only an inverted item makes no sense
				inverted && this.isSelected() ?
					'inverted' : 'default'
			];
		}
		return null;
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
		return !!this.value;
	};

	/**
	 * Toggle the selected state of the item
	 *
	 * @param {boolean} [isSelected] Filter is selected
	 * @fires update
	 */
	mw.rcfilters.dm.ItemModel.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected === undefined ? !this.isSelected() : isSelected;
		this.setValue( isSelected );
	};

	/**
	 * Get the value
	 *
	 * @return {*}
	 */
	mw.rcfilters.dm.ItemModel.prototype.getValue = function () {
		return this.value;
	};

	/**
	 * Convert a given value to the appropriate representation based on group type
	 *
	 * @param {*} value
	 * @return {*}
	 */
	mw.rcfilters.dm.ItemModel.prototype.coerceValue = function ( value ) {
		return this.getGroupModel().getType() === 'any_value' ? value : !!value;
	};

	/**
	 * Set the value
	 *
	 * @param {*} newValue
	 */
	mw.rcfilters.dm.ItemModel.prototype.setValue = function ( newValue ) {
		newValue = this.coerceValue( newValue );
		if ( this.value !== newValue ) {
			this.value = newValue;
			this.emit( 'update' );
		}
	};

	/**
	 * Set the highlight color
	 *
	 * @param {string|null} highlightColor
	 */
	mw.rcfilters.dm.ItemModel.prototype.setHighlightColor = function ( highlightColor ) {
		if ( !this.isHighlightSupported() ) {
			return;
		}
		// If the highlight color on the item and in the parameter is null/undefined, return early.
		if ( !this.highlightColor && !highlightColor ) {
			return;
		}

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
		return !!this.getHighlightColor();
	};
}() );
