( function ( mw ) {
	/**
	 * Namespace widget. This is temporary, since in the design we
	 * will have the namespaces act as a secondary/additional menu
	 * in the general RCFilters menu. However, when that happens,
	 * most elements would just need to be moved from this widget.
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCfilters controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model Filters view model
	 * @param {Object} [config] Configuration options
	 */
	mw.rcfilters.ui.NamespaceWrapperWidget = function MwRcfiltersUiNamespaceWrapperWidget( controller, model, config ) {
		var fieldset;

		config = config || {};

		// Parent
		mw.rcfilters.ui.NamespaceWrapperWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		this.input = new OO.ui.TextInputWidget( {
			classes: [ 'mw-rcfilters-ui-namespaceWrapperWidget-input' ]
		} );

		this.menu = new OO.ui.FloatingMenuSelectWidget(
			this.input,
			{
			input: this.input,
			filterFromInput: true,
			disabled: this.isDisabled(),
			$autoCloseIgnore: this.input.$element.add( this.$overlay ),
			$overlay: this.$overlay,
			items: [
					// new mw.rcfilters.ui.NamespaceMenuOptionWidget(
					// 	new mw.rcfilters.dm.NamespaceItem( 1, 'Namespace1' ),
					// 	{ $overlay: this.$overlay }
					// ),
					// new mw.rcfilters.ui.NamespaceMenuOptionWidget(
					// 	new mw.rcfilters.dm.NamespaceItem( 2, 'Namespace2' ),
					// 	{ $overlay: this.$overlay }
					// )
			],
			classes: [ 'mw-rcfilters-ui-namespaceWrapperWidget-menu' ]
		} );

		fieldset = new OO.ui.FieldLayout( this.input, {
			align: 'inline',
			label: mw.msg( 'namespace' )
		} );

		// Events
		this.input.connect( this, { change: 'onInputChange' } );
		this.input.$input.on( {
			focus: this.onInputFocus.bind( this ),
		} );
		this.menu.connect( this, { choose: 'onMenuChoose' } );
		this.model.connect( this, {
			initializeNamespaces: 'onModelInitialize'
		} );

		// Initialization
		this.$overlay
			.append( this.menu.$element );
		this.$element
			.addClass( 'mw-rcfilters-ui-namespaceWrapperWidget' )
			.append( fieldset.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.NamespaceWrapperWidget, OO.ui.Widget );

	/* Methods */
	mw.rcfilters.ui.NamespaceWrapperWidget.prototype.onModelInitialize = function () {
		var model = this,
			items = [];

		this.menu.clearItems();

		this.model.getNamespacesModel().getItems().forEach( function ( namespaceModel ) {
			items.push(
				new mw.rcfilters.ui.NamespaceMenuOptionWidget(
					model.controller,
					namespaceModel,
					{ $overlay: this.$overlay }
				)
			);
		} );

		this.menu.addItems( items );
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceWrapperWidget.prototype.onMenuChoose = function ( item ) {
		this.controller.toggleNamespaceSelect( item.model.getName() );

		this.menu.toggle( true );
	};

	/**
	 * Respond to resize event
	 */
	mw.rcfilters.ui.NamespaceWrapperWidget.prototype.onResize = function () {
		// Reposition the menu
		this.menu.position();
	};

	/**
	 * Respond to input focus event
	 */
	mw.rcfilters.ui.NamespaceWrapperWidget.prototype.onInputFocus = function () {
		this.menu.toggle( true );
	};

	/**
	 * Respond to input change event
	 */
	mw.rcfilters.ui.NamespaceWrapperWidget.prototype.onInputChange = function () {
		this.menu.toggle( true );
	};
}( mediaWiki ) );
