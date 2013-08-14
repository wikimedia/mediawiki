/**
 * Utility functions for jazzing up HTMLForm elements.
 */
( function ( mw, $ ) {

	/**
	 * jQuery plugin to fade or snap to visible state.
	 *
	 * @param {boolean} instantToggle [optional]
	 * @return {jQuery}
	 */
	$.fn.goIn = function ( instantToggle ) {
		if ( instantToggle === true ) {
			return $(this).show();
		}
		return $(this).stop( true, true ).fadeIn();
	};

	/**
	 * jQuery plugin to fade or snap to hiding state.
	 *
	 * @param {boolean} instantToggle [optional]
	 * @return jQuery
	 */
	$.fn.goOut = function ( instantToggle ) {
		if ( instantToggle === true ) {
			return $(this).hide();
		}
		return $(this).stop( true, true ).fadeOut();
	};

	/**
	 * Bind a function to the jQuery object via live(), and also immediately trigger
	 * the function on the objects with an 'instant' parameter set to true.
	 * @param {Function} callback Takes one parameter, which is {true} when the
	 *  event is called immediately, and {jQuery.Event} when triggered from an event.
	 */
	$.fn.liveAndTestAtStart = function ( callback ){
		$(this)
			.live( 'change', callback )
			.each( function () {
				callback.call( this, true );
			} );
	};

	$( function () {

		// Animate the SelectOrOther fields, to only show the text field when
		// 'other' is selected.
		$( '.mw-htmlform-select-or-other' ).liveAndTestAtStart( function ( instant ) {
			var $other = $( '#' + $(this).attr( 'id' ) + '-other' );
			$other = $other.add( $other.siblings( 'br' ) );
			if ( $(this).val() === 'other' ) {
				$other.goIn( instant );
			} else {
				$other.goOut( instant );
			}
		});

	} );

	function addMulti( $oldContainer, $container ) {
		var name = $oldContainer.find( 'input:first-child' ).attr( 'name' ),
			oldClass = ( ' ' + $oldContainer.attr( 'class' ) + ' ' ).replace( /(mw-htmlform-field-HTMLMultiSelectField|mw-chosen)/g, '' ),
			$select = $( '<select>' ),
			dataPlaceholder = mw.message( 'htmlform-chosen-placeholder' );
		oldClass = $.trim( oldClass );
		$select.attr( {
			name: name,
			multiple: 'multiple',
			'data-placeholder': dataPlaceholder.plain(),
			'class': 'htmlform-chzn-select mw-input ' + oldClass
		} );
		$oldContainer.find( 'input' ).each( function () {
			var $oldInput = $(this),
			checked = $oldInput.prop( 'checked' ),
			$option = $( '<option>' );
			$option.prop( 'value', $oldInput.prop( 'value' ) );
			if ( checked ) {
				$option.prop( 'selected', true );
			}
			$option.text( $oldInput.prop( 'value' ) );
			$select.append( $option );
		} );
		$container.append( $select );
	}

	function convertCheckboxesToMulti( $oldContainer, type ) {
		var $fieldLabel = $( '<td>' ),
		$td = $( '<td>' ),
		$fieldLabelText = $( '<label>' ),
		$container;
		if ( type === 'tr' ) {
			addMulti( $oldContainer, $td );
			$container = $( '<tr>' );
			$container.append( $td );
		} else if ( type === 'div' ) {
			$fieldLabel = $( '<div>' );
			$container = $( '<div>' );
			addMulti( $oldContainer, $container );
		}
		$fieldLabel.attr( 'class', 'mw-label' );
		$fieldLabelText.text( $oldContainer.find( '.mw-label label' ).text() );
		$fieldLabel.append( $fieldLabelText );
		$container.prepend( $fieldLabel );
		$oldContainer.replaceWith( $container );
		return $container;
	}

	if ( $( '.mw-chosen' ).length ) {
		mw.loader.using( 'jquery.chosen', function () {
			$( '.mw-chosen' ).each( function () {
				var type = this.nodeName.toLowerCase(),
					$converted = convertCheckboxesToMulti( $( this ), type );
				$converted.find( '.htmlform-chzn-select' ).chosen( { width: 'auto' } );
			} );
		} );
	}

	$( function () {
		var $matrixTooltips = $( '.mw-htmlform-matrix .mw-htmlform-tooltip' );
		if ( $matrixTooltips.length ) {
			mw.loader.using( 'jquery.tipsy', function () {
				$matrixTooltips.tipsy( { gravity: 's' } );
			} );
		}
	} );
}( mediaWiki, jQuery ) );
