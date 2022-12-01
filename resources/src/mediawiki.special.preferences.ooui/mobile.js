/*!
 * JavaScript for Special:Preferences: mobileLayout.
 */
( function () {
	/*
	 * Adds a ToggleSwitchWidget to control each checkboxWidget
	 * Hides each checkboxWidget
	 */
	function insertToggles() {
		var checkboxes = document.querySelectorAll( 'span.oo-ui-checkboxInputWidget' );
		Array.prototype.forEach.call( checkboxes, function ( checkboxWidget ) {
			var checkboxInput = checkboxWidget.querySelector( 'input' );
			var toggleSwitchWidget = new OO.ui.ToggleSwitchWidget( {
				value: checkboxInput.checked,
				disabled: checkboxInput.disabled
			} );
			toggleSwitchWidget.on( 'change', function ( value ) {
				toggleSwitchWidget.setValue( value );
				checkboxInput.checked = value;
			} );
			checkboxWidget.insertAdjacentElement( 'afterend', toggleSwitchWidget.$element[ 0 ] );
			checkboxWidget.classList.add( 'hidden' );
		} );
	}
	$( function () {
		insertToggles();
		var options, windowManager, preferencesForm, prefOptionsContainer, prefContent, prefFormWrapper;
		options = OO.ui.infuse( document.querySelector( '.mw-mobile-preferences-container' ) );
		windowManager = new OO.ui.WindowManager();
		preferencesForm = document.querySelector( '#mw-prefs-form' );
		prefOptionsContainer = document.querySelector( '#mw-prefs-container' );
		prefFormWrapper = document.querySelector( '.mw-htmlform-ooui-wrapper' );

		function showContent( element ) {
			prefContent = document.querySelector( '#' + element.elementId + '-content' );
			prefContent.classList.remove( 'mw-prefs-hidden' );
			prefOptionsContainer.classList.add( 'mw-prefs-hidden' );
			prefOptionsContainer.removeAttribute( 'style' );
			preferencesForm.insertBefore( prefContent, preferencesForm.firstChild );

			function PrefDialog( config ) {
				PrefDialog.super.call( this, config );
			}

			OO.inheritClass( PrefDialog, OO.ui.Dialog );
			PrefDialog.static.name = element.elementId;
			PrefDialog.static.escapable = false;
			PrefDialog.prototype.initialize = function () {
				PrefDialog.super.prototype.initialize.call( this );
				this.content = new OO.ui.PanelLayout( { padded: true, expanded: true } );
				this.$body.append( preferencesForm );
			};

			PrefDialog.prototype.getBodyHeight = function () {
				return this.content.$element.outerHeight( true );
			};

			var prefDialog = new PrefDialog( { size: 'full' } );

			$( document.body ).append( windowManager.$element );
			windowManager.addWindows( [ prefDialog ] );
			windowManager.openWindow( prefDialog );

			if ( prefDialog.isOpening() ) {
				document.querySelector( '#mw-mf-viewport' ).classList.add( 'hidden' );
			}
		}

		options.items.forEach( function ( element ) {
			document.querySelector( '#' + element.elementId ).addEventListener( 'click', function () {
				showContent( element );
			} );

			var backButtonId = '#' + element.elementId + '-back-button';
			document.querySelector( backButtonId ).addEventListener( 'click', function () {
				prefContent.classList.add( 'mw-prefs-hidden' );
				prefOptionsContainer.classList.remove( 'mw-prefs-hidden' );
				prefFormWrapper.insertBefore( preferencesForm, prefFormWrapper.firstChild );
				document.querySelector( '#mw-mf-viewport' ).classList.remove( 'hidden' );
				windowManager.currentWindow.close();
			} );
		} );
	} );
}() );
