( function ( mw ) {
	/**
	 * A widget representing a base toggle item
	 *
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.ItemModel} model Item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.ItemMenuOptionWidget = function MwRcfiltersUiItemMenuOptionWidget( controller, model, config ) {
		var layout,
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-label' );

		config = config || {};

		this.controller = controller;
		this.model = model;

		// Parent
		mw.rcfilters.ui.ItemMenuOptionWidget.parent.call( this, $.extend( {
			// Override the 'check' icon that OOUI defines
			icon: '',
			data: this.model.getName(),
			label: this.model.getLabel()
		}, config ) );

		this.checkboxWidget = new mw.rcfilters.ui.CheckboxInputWidget( {
			value: this.model.getName(),
			selected: this.model.isSelected()
		} );

		$label.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-label-title' )
				.append( this.$label )
		);
		if ( this.model.getDescription() ) {
			$label.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-label-desc' )
					.text( this.model.getDescription() )
			);
		}

		this.highlightButton = new mw.rcfilters.ui.FilterItemHighlightButton(
			this.controller,
			this.model,
			{
				$overlay: config.$overlay || this.$element,
				title: mw.msg( 'rcfilters-highlightmenu-help' )
			}
		);
		this.highlightButton.toggle( this.model.isHighlightEnabled() );

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );
		// HACK: Prevent defaults on 'click' for the label so it
		// doesn't steal the focus away from the input. This means
		// we can continue arrow-movement after we click the label
		// and is consistent with the checkbox *itself* also preventing
		// defaults on 'click' as well.
		layout.$label.on( 'click', false );

		this.$element
			.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-itemCheckbox' )
									.append( layout.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-highlightButton' )
									.append( this.highlightButton.$element )
							)
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ItemMenuOptionWidget, OO.ui.MenuOptionWidget );

	/* Static properties */

	// We do our own scrolling to top
	mw.rcfilters.ui.ItemMenuOptionWidget.static.scrollIntoViewOnSelect = false;

	/* Methods */

	/**
	 * Respond to item model update event
	 */
	mw.rcfilters.ui.ItemMenuOptionWidget.prototype.onModelUpdate = function () {
		this.checkboxWidget.setSelected( this.model.isSelected() );

		this.highlightButton.toggle( this.model.isHighlightEnabled() );
	};

	/**
	 * Get the name of this filter
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.ui.ItemMenuOptionWidget.prototype.getName = function () {
		return this.model.getName();
	};

	mw.rcfilters.ui.ItemMenuOptionWidget.prototype.getModel = function () {
		return this.model;
	};

}( mediaWiki ) );
