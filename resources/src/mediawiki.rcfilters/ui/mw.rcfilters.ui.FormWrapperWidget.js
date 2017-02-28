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
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.model = model;
		this.controller = controller;
		this.$submitButton = this.$element.find( 'form input[type=submit]' );

		this.$element
			.on( 'click', 'a[data-params]', this.onLinkClick.bind( this ) );

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
	OO.mixinClass( mw.rcfilters.ui.FormWrapperWidget, OO.ui.mixin.PendingElement );

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

		// Only highlight choice if this link isn't a show/hide link
		if ( !$element.parents( '.rcshowhideoption' ).length ) {
			// Remove the class from similar elements
			$similarElements.removeClass( 'mw-rcfilters-staticfilters-selected' );
			// Add the class to this element
			$element.addClass( 'mw-rcfilters-staticfilters-selected' );
		}

		e.stopPropagation();

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
		this.pushPending();
		this.$submitButton.prop( 'disabled', true );
	};

	/**
	 * Respond to model update, replace the show/hide links with the ones from the
	 * server so they feature the correct state.
	 *
	 * @param {jQuery|string} $changesList Updated changes list
	 * @param {jQuery} $fieldset Updated fieldset
	 */
	mw.rcfilters.ui.FormWrapperWidget.prototype.onModelUpdate = function ( $changesList, $fieldset ) {
		this.$submitButton.prop( 'disabled', false );

		// Replace the links we have in the content
		// We don't want to replace the entire thing, because there is a big difference between
		// the links in the backend and the links we have initialized, since we are removing
		// the ones that are implemented in the new system
		this.$element.find( '.rcshowhide' ).children().each( function () {
			// Go over existing links and replace only them
			var classes = $( this ).attr( 'class' ).split( ' ' ),
				// Look for that item in the fieldset from the server
				$remoteItem = $fieldset.find( '.' + classes.join( '.' ) );

			if ( $remoteItem ) {
				$( this ).replaceWith( $remoteItem );
			}
		} );

		this.popPending();
	};
}( mediaWiki ) );
