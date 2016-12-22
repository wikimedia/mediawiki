( function ( mw ) {
	/**
	 * Wrapper for the RC form with hide/show links
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.ChangesListViewModel} model Changes list view model
	 * @param {Object} config Configuration object
	 * @cfg {Object} $element Root element of the form to attach to
	 */
	mw.rcfilters.ui.FormWrapperWidget = function MwRcfiltersUiFormWrapperWidget( model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FormWrapperWidget.parent.call( this, config );

		this.model = model;
		this.$submitButton = this.$element.find( 'input[type=submit]' );

		// Events
		this.model.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FormWrapperWidget, OO.ui.Widget );

	/**
	 * Respond to model invalidate
	 */
	mw.rcfilters.ui.FormWrapperWidget.prototype.onModelInvalidate = function () {
		this.$submitButton.prop( 'disabled', true );
	};

	/**
	 * Respond to model update
	 */
	mw.rcfilters.ui.FormWrapperWidget.prototype.onModelUpdate = function () {
		this.$submitButton.prop( 'disabled', false );
	};
}( mediaWiki ) );
