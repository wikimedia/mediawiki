( function ( mw, $ ) {
	/**
	 * A group of filters
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {string} name Group name
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterItemWidget = function MwRcfiltersUiFilterItemWidget( model, config ) {
		var layout,
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-filterItemWidget-label' );

		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterItemWidget.parent.call( this, config );

		this.model = model;

		this.checkboxWidget = new OO.ui.CheckboxInputWidget( {
			value: this.model.getName()
		} );

		$label.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-filterItemWidget-label-title' )
				.text( this.model.getLabel() )
		);
		if ( config.description ) {
			$label.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-filterItemWidget-label-desc' )
					.text( config.description )
			);
		}

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );

		this.checkboxWidget.connect( this, { change: 'onCheckboxChange' } );

		this.$element
			.addClass( 'mw-rcfilters-filterItemWidget' )
			.append(
				layout.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterItemWidget, OO.ui.Widget );

	mw.rcfilters.ui.FilterItemWidget.prototype.onCheckboxChange = function ( isSelected ) {
		this.model.toggleSelected( isSelected );
	};

} )( mediaWiki, jQuery );
