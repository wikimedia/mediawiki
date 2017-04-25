( function ( mw ) {
	/**
	 * A widget representing a single toggle namespace
	 *
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.NamespaceItem} model Namespace item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.NamespaceMenuOptionWidget = function MwRcfiltersUiNamespaceMenuOptionWidget( controller, model, config ) {
		var layout,
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-namespaceMenuOptionWidget-label' );

		config = config || {};

		this.controller = controller;
		this.model = model;

		// Parent
		mw.rcfilters.ui.NamespaceMenuOptionWidget.parent.call( this, $.extend( {
			// Override the 'check' icon that OOUI defines
			icon: '',
			data: this.model.getNamespaceID(),
			label: this.model.getLabel()
		}, config ) );

		this.checkboxWidget = new mw.rcfilters.ui.CheckboxInputWidget( {
			value: this.model.getNamespaceID(),
			selected: this.model.isSelected()
		} );

		this.excludeButton = new OO.ui.ToggleButtonWidget( {
			label: 'Exclude',
			icon: 'cancel'
		} );
		this.excludeButton.toggle( this.model.isSelected() );

		$label.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-namespaceMenuOptionWidget-label-title' )
				.append( this.$label )
		);
		if ( this.model.getDescription() ) {
			$label.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-namespaceMenuOptionWidget-label-desc' )
					.text( this.model.getDescription() )
			);
		}

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );
		// Event
		this.model.connect( this, { update: 'onModelUpdate' } );
		// HACK: Prevent defaults on 'click' for the label so it
		// doesn't steal the focus away from the input. This means
		// we can continue arrow-movement after we click the label
		// and is consistent with the checkbox *itself* also preventing
		// defaults on 'click' as well.
		layout.$label.on( 'click', false );
		this.excludeButton.connect( this, { click: 'onExcludeButtonClick' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-namespaceMenuOptionWidget' )
			.addClass(
				'mw-rcfilters-ui-namespaceMenuOptionWidget-type-' + (
					this.model.getNamespaceID() % 2 === 0 ?
					'subject' : 'talk'
				)
			)
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-namespaceMenuOptionWidget-filterCheckbox' )
									.append( layout.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-namespaceMenuOptionWidget-excludeButton' )
									.append( this.excludeButton.$element )
								// $( '<div>' )
								// 	.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-namespaceMenuOptionWidget-highlightButton' )
								// 	.append( this.highlightButton.$element )
							)
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.NamespaceMenuOptionWidget, OO.ui.MenuOptionWidget );

	/* Static properties */

	// We do our own scrolling to top
	mw.rcfilters.ui.NamespaceMenuOptionWidget.static.scrollIntoViewOnSelect = false;

	/* Methods */

	/**
	 * Respond to item model update event
	 */
	mw.rcfilters.ui.NamespaceMenuOptionWidget.prototype.onModelUpdate = function () {
		this.checkboxWidget.setSelected( this.model.isSelected() );
		this.excludeButton.toggle( this.model.isSelected() );
		this.excludeButton.setActive( this.model.isExcluded() );
	};

	mw.rcfilters.ui.NamespaceMenuOptionWidget.prototype.onExcludeButtonClick = function () {
		this.controller.toggleNamespaceExclude( this.model.getName() );
	};

	mw.rcfilters.ui.NamespaceMenuOptionWidget.prototype.getModel = function () {
		return this.model;
	};

}( mediaWiki ) );
