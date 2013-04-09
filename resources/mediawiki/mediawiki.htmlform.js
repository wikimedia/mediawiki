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

	$.fn.addMulti = function( $container ) {
		var oldContainer = $(this),
		$name = $oldContainer.find( 'input:first-child' ).attr( 'name' ),
		$select = $( '<select>' ),
		$dataPlaceholder = mw.message( 'htmlform-chosen-placeholder' );
		$select.attr( {
			name: $name,
			multiple: 'multiple',
			class: 'chzn-select mw-input',
			'data-placeholder': $dataPlaceholder.escaped()
		} );
		$oldContainer.find( 'input' ).each( function() {
			var $checked = $oldContainer.attr( 'checked' ),
			$option = $( '<option>' );
			$option.attr( 'value', $oldContainer.attr( 'value' ) );
			if ( $checked === 'checked' ) {
				$option.attr( 'selected', 'selected' );
			}
			$option.text( $oldContainer.attr( 'value' ) );
			$select.append( $option );
		} );
		$container.append( $select );
	};

	$.fn.convertCheckboxesToMulti = function( $type ) {
		var $oldContainer = $(this),
		$fieldLabel = $( '<td>' ),
		$td = $( '<td>' ),
		$container = $( '<tr>' ),
		$fieldLabelText = $( '<label>' );
		if ( $type === 'table' ) {
			$oldContainer.addMulti( $td );
			$container.append( $td );
		} else if ( $type === 'div' ) {
			$fieldLabel = $( '<div>' );
			$container = $( '<div>' );
			$oldContainer.addMulti( $container );
		}
		$fieldLabel.attr( 'class', 'mw-label' );
		$fieldLabelText.text( $oldContainer.find( '.mw-label label' ).text() );
		$fieldLabel.append( $fieldLabelText );
		$container.prepend( $fieldLabel );
		$oldContainer.parent().append( $container );
		$oldContainer.remove();
	};

	mw.loader.using( 'jquery.chosen', function () {
		$( 'table .chosen' ).convertCheckboxesToMulti( 'table' );
		$( 'div .chosen' ).convertCheckboxesToMulti( 'div' );
		$( '.chzn-select' ).chosen();
	} );

}( mediaWiki, jQuery ) );
