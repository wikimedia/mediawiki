( function ( mw ) {
	/**
	 * Wrapper for the RC form with hide/show links
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.ChangesListViewModel} model Changes list view model
	 * @param {Object} config Configuration object
	 * @cfg {Object} $element Root element of the form to attach to
	 */
	mw.rcfilters.ui.FormWrapperWidget = function MwRcfiltersUiFormWrapperWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FormWrapperWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.$element = config.$element;
		this.$submitButton = this.$element.find( 'input[type=submit]' );

		// Events
		this.model.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );

		this.$element.submit( this.onFormSubmit.bind( this ) );
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

	/**
	 * Respond to form submit
	 *
	 * @returns {boolean} True to cancel form submission
	 */
	mw.rcfilters.ui.FormWrapperWidget.prototype.onFormSubmit = function () {
		this.controller.updateURL();
		this.controller.updateChangesList();
		// Prevent native form submission
		return false;
	};
}( mediaWiki ) );
