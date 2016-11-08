( function ( mw, $ ) {
	/**
	 * A widget representing a single toggle filter
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterItemWidget = function MwRcfiltersUiFilterItemWidget( controller, model, config ) {
		var layout,
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterItemWidget-label' );

		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterItemWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;

		this.checkboxWidget = new OO.ui.CheckboxInputWidget( {
			value: this.model.getName(),
			selected: this.model.isSelected()
		} );

		$label.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterItemWidget-label-title' )
				.text( this.model.getLabel() )
		);
		if ( config.description ) {
			$label.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterItemWidget-label-desc' )
					.text( config.description )
			);
		}

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );

		// Event
		this.checkboxWidget.connect( this, { change: 'onCheckboxChange' } );
		this.model.connect( this, { update: 'onModelUpdate' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterItemWidget' )
			.append(
				layout.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterItemWidget, OO.ui.Widget );

	mw.rcfilters.ui.FilterItemWidget.prototype.onCheckboxChange = function ( isSelected ) {
		this.controller.updateFilter( this.model.getName(), isSelected );
	};

	mw.rcfilters.ui.FilterItemWidget.prototype.onModelUpdate = function ( isSelected ) {
 		this.checkboxWidget.setSelected( isSelected );
	};

	mw.rcfilters.ui.FilterItemWidget.prototype.getName = function () {
 		return this.model.getName();
	};

} )( mediaWiki, jQuery );
