( function ( mw ) {
	/**
	 * Wrapper for the RC form with hide/show links
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.ChangesListViewModel} model Changes list view model
	 * @param {mw.rcfilters.Controller} controller RCfilters controller
	 * @param {jQuery} $formRoot Root element of the form to attach to
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FormWrapperWidget = function MwRcfiltersUiFormWrapperWidget( model, controller, $formRoot, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FormWrapperWidget.parent.call( this, $.extend( {}, config, {
			$element: $formRoot
		} ) );

		this.model = model;
		this.controller = controller;
		this.$submitButton = this.$element.find( 'form input[type=submit]' );

		this.$element
			.find( 'a[data-params]' )
			.on( 'click', this.onLinkClick.bind( this ) );

		this.$element
			.find( 'form' )
			.on( 'submit', this.onFormSubmit.bind( this ) );

		// Events
		this.model.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );

		// Initialize
		this.cleanupForm();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FormWrapperWidget, OO.ui.Widget );

	/**
	 * Clean up the base form we're getting from the back-end.
	 * Remove <strong> tags and replace those with classes, so
	 * we can toggle those on click.
	 */
	mw.rcfilters.ui.FormWrapperWidget.prototype.cleanupForm = function () {
		this.$element.find( '[data-keys] strong' ).each( function () {
			$( this )
				.parent().addClass( 'mw-rcfilters-staticfilters-selected' );

			$( this )
				.replaceWith( $( this ).contents() );
		} );
	};

	/**
	 * Respond to link click
	 *
	 * @param {jQuery.Event} e Event
	 * @return {boolean} false
	 */
	mw.rcfilters.ui.FormWrapperWidget.prototype.onLinkClick = function ( e ) {
		var $element = $( e.target ),
			data = $element.data( 'params' ),
			keys = $element.data( 'keys' ),
			$similarElements = $element.parent().find( '[data-keys="' + keys + '"]' );

		// Remove the class from similar elements
		$similarElements.removeClass( 'mw-rcfilters-staticfilters-selected' );
		// Add the class to this element
		$element.addClass( 'mw-rcfilters-staticfilters-selected' );

		// Identify if this is a show/hide feature, and if it is, change its text
		if ( $element.parents( '.rcshowhideoption' ).length ) {
			// Toggle the message
			// Toggle the param value (change the data)
		}

		this.controller.updateChangesList( data );
		return false;
	};

	/**
	 * Respond to form submit event
	 *
	 * @param {jQuery.Event} e Event
	 * @return {boolean} false
	 */
	mw.rcfilters.ui.FormWrapperWidget.prototype.onFormSubmit = function ( e ) {
		var data = {};

		// Collect all data from form
		$( e.target ).find( 'input:not([type="hidden"],[type="submit"]), select' ).each( function () {
			var val;

			if (
				!$( this ).is( ':checkbox' ) ||
				(
					$( this ).is( ':checkbox' ) && $( this ).is( ':checked' )
				)
			) {
				val = $( this ).val();
			}

			if ( val ) {
				data[ $( this ).prop( 'name' ) ] = val;
			}
		} );

		this.controller.updateChangesList( data );
		return false;
	};

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
