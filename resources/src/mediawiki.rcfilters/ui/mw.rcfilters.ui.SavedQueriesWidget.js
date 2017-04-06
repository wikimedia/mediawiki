( function ( mw ) {
	/**
	 * Widget to display and manipulate saved queries
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.SavedQueriesModel} model Saved query model
	 * @param {Object} [config] Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.SavedQueriesWidget = function MwRcfiltersUiSavedQueriesWidget( controller, model, config ) {
		var defaultQuery,
			widget = this;

		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, $.extend( {
			label: mw.msg( 'rcfilters-savedqueries' ),
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-savedQueriesWidget-label' ),
		}, config ) );

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		this.queryGroupWidget = new OO.ui.ButtonGroupWidget();

		// Events
		this.model.connect( this, {
			add: 'onModelAdd',
			remove: 'onModelRemove',
			edit: 'onModelEdit',
			default: 'onModelDefault'
		} );
		// Aggregate events
		this.queryGroupWidget.aggregate( {
			remove: 'queryRemoved',
			click: 'queryClicked',
			edit: 'queryEdited',
			default: 'queryDefault'
		} );
		this.queryGroupWidget.connect( this, {
			queryRemoved: 'onQueryRemoved',
			queryClicked: 'onQueryClicked',
			queryEdited: 'onQueryEdited',
			queryDefault: 'onQueryDefault'
		} );

		// Initialize data
		$.each( this.model.getQueries(), function ( id ) {
			widget.addQueryOption( id );
		} );
		defaultQuery = this.model.getDefault();
		this.queryGroupWidget.getItems().forEach( function ( itemWidget ) {
			itemWidget.toggleDefault( itemWidget.getData() === defaultQuery );
		} );
		this.toggle( !this.queryGroupWidget.isEmpty() );

		// Initialize widget
		this.$element
			.addClass( 'mw-rcfilters-ui-savedQueriesWidget' )
			.append(
				this.$label,
				this.queryGroupWidget.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.SavedQueriesWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.SavedQueriesWidget, OO.ui.mixin.LabelElement );

	/* Methods */

	/**
	 * Respond to item remove event
	 *
	 * @param {mw.rcfilters.ui.QueryButtonWidget} itemWidget Item widget
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onQueryRemoved = function ( itemWidget ) {
		this.controller.removeSavedQuery( itemWidget.getData() );
	};

	/**
	 * Respond to item click event
	 *
	 * @param {mw.rcfilters.ui.QueryButtonWidget} itemWidget Item widget
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onQueryClicked = function ( itemWidget ) {
		this.controller.loadSavedQuery( itemWidget.getData() );
	};

	/**
	 * Respond to item edit event
	 *
	 * @param {mw.rcfilters.ui.QueryButtonWidget} itemWidget Item widget
	 * @param {string} newLabel New label
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onQueryEdited = function ( itemWidget, newLabel ) {
		this.controller.editSavedQuery( itemWidget.getData(), newLabel );
	};

	/**
	 * Respond to item set default event
	 *
	 * @param {mw.rcfilters.ui.QueryButtonWidget} itemWidget Item widget
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onQueryDefault = function ( itemWidget, isDefault ) {
		this.controller.setSavedQueryDefault(
			isDefault ?
				itemWidget.getData() :
				// Unset default
				null
		);
	};

	/**
	 * Respond to model add event
	 *
	 * @param {string} queryId Query ID
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onModelAdd = function ( queryId ) {
		this.addQueryOption( queryId );

		this.toggle( !this.queryGroupWidget.isEmpty() );
	};

	/**
	 * Respond to model remove event
	 *
	 * @param {string} queryId Query ID
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onModelRemove = function ( queryId ) {
		this.queryGroupWidget.removeItems( [ this.queryGroupWidget.getItemFromData( queryId ) ] );

		this.toggle( !this.queryGroupWidget.isEmpty() );
	};

	/**
	 * Respond to model edit event
	 *
	 * @param {string} queryId Query ID
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onModelEdit = function ( queryId ) {
		var data = this.model.getQueryByID( queryId );

		this.queryGroupWidget.getItemFromData( queryId ).changeLabel( data.label );
	};

	/**
	 * Respond to model set default event
	 *
	 * @param {string} queryId Query ID
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.onModelDefault = function ( queryId ) {
		this.queryGroupWidget.getItems().forEach( function ( itemWidget ) {
			itemWidget.toggleDefault( itemWidget.getData() === queryId );
		} );
	};

	/**
	 * Add a query option to the group
	 *
	 * @param {string} queryId Query ID
	 */
	mw.rcfilters.ui.SavedQueriesWidget.prototype.addQueryOption = function ( queryId ) {
		var details = this.model.getQueryByID( queryId );

		this.queryGroupWidget.addItems( [
			new mw.rcfilters.ui.QueryButtonWidget(
				details.label,
				{ data: queryId, $overlay: this.$overlay }
			)
		] );
	};
}( mediaWiki ) );
