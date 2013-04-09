/**
 * Utility functions for jazzing up HTMLForm elements.
 */
( function ( $ ) {

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
		var $name = $(this).find( 'input:first-child' ).attr( 'name' );
		var $select = $( '<select>' );
		var $dataPlaceholder = mw.message( 'htmlform-chosen-placeholder' );
		$select.attr( {
			name: $name,
			multiple: 'multiple',
			class: 'chzn-select mw-input',
			'data-placeholder': $dataPlaceholder.escaped()
		} );
		$(this).find( 'input' ).each( function() {
			var $checked = $(this).attr( 'checked' );
			var $option = $( '<option>' );
			$option.attr( 'value', $(this).attr( 'value' ) );
			if ( $checked == 'checked' ) {
				$option.attr( 'selected', 'selected' );
			}
			$option.text( $(this).attr( 'value' ) );
			$select.append( $option );
		} );
		$container.append( $select );
	}

	$.fn.convertCheckboxesToMulti = function( $type ) {
		if ( $type == 'table' ) {
			var $fieldLabel = $( '<td>' );
			var $container = $( '<tr>' );
			var $td = $( '<td>' );
			$(this).addMulti( $td );
			$container.append( $td );
		} else if ( $type == 'div' ) {
			var $fieldLabel = $( '<div>' );
			var $container = $( '<div>' );
			$(this).addMulti( $container );
		}
		$fieldLabel.attr( 'class', 'mw-label' );
		var $fieldLabelText = $( '<label>' );
		$fieldLabelText.text( $(this).find( '.mw-label label' ).text() );
		$fieldLabel.append( $fieldLabelText );
		$container.prepend( $fieldLabel );
		$(this).parent().append( $container );
		$(this).remove();
	}

	mw.loader.using( 'jquery.chosen', function () {	
		$( 'table .chosen' ).convertCheckboxesToMulti( 'table' );
		$( 'div .chosen' ).convertCheckboxesToMulti( 'div' );
		$( '.chzn-select' ).chosen();
	} );


}( jQuery ) );
