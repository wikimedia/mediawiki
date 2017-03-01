( function ( mw ) {
	/**
	 * Wrapper for the RC form with hide/show links
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.ChangesListViewModel} model Changes list view model
	 * @param {jQuery} $formRoot Root element of the form to attach to
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FormWrapperWidget = function MwRcfiltersUiFormWrapperWidget( model, $formRoot, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FormWrapperWidget.parent.call( this, $.extend( {}, config, {
			$element: $formRoot
		} ) );

		this.model = model;
		this.$submitButton = this.$element.find( 'form input[type=submit]' );

		// Events
		this.model.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-FormWrapperWidget' )
			.addClass( 'mw-rcfilters-ui-ready' );
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
