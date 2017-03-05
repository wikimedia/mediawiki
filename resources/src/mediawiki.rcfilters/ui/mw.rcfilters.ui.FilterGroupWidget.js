( function ( mw, $ ) {
	/**
	 * A group of filters
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.GroupWidget
	 * @mixins OO.ui.mixin.LabelElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FilterGroup} model Filter group model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterGroupWidget = function MwRcfiltersUiFilterGroupWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterGroupWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.filters = {};

		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );
		OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
			label: this.model.getTitle(),
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterGroupWidget-title' )
		} ) );
		this.$overlay = config.$overlay || this.$element;

		// Populate
		this.populateFromModel();

		this.model.connect( this, { update: 'onModelUpdate' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterGroupWidget' )
			.addClass( 'mw-rcfilters-ui-filterGroupWidget-name-' + this.model.getName() )
			.append(
				this.$label,
				this.$group
					.addClass( 'mw-rcfilters-ui-filterGroupWidget-group' )
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.GroupWidget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.LabelElement );

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.onModelUpdate = function () {
		this.$element.toggleClass(
			'mw-rcfilters-ui-filterGroupWidget-active',
			this.model.isActive()
		);
	};

	/**
	 * Get an item widget from its filter name
	 *
	 * @param {string} filterName Filter name
	 * @return {mw.rcfilters.ui.FilterItemWidget} Item widget
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.getItemWidget = function ( filterName ) {
		return this.filters[ filterName ];
	};

	/**
	 * Populate data from the model
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.populateFromModel = function () {
		var widget = this;

		this.clearItems();
		this.filters = {};

		this.addItems(
			this.model.getItems().map( function ( filterItem ) {
				var groupWidget = new mw.rcfilters.ui.FilterItemWidget(
					widget.controller,
					filterItem,
					{
						label: filterItem.getLabel(),
						description: filterItem.getDescription(),
						$overlay: widget.$overlay
					}
				);

				widget.filters[ filterItem.getName() ] = groupWidget;

				return groupWidget;
			} )
		);
	};

	/**
	 * Get the group name
	 *
	 * @return {string} Group name
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.getName = function () {
		return this.model.getName();
	};
}( mediaWiki, jQuery ) );
