/**
 * jQuery messageBox
 *
 * Function to inform the user of something. Use sparingly (since there's mw.log for
 * messages aimed at developers / debuggers). Based on the function in MediaWiki's
 * legacy javascript (wikibits.js) by Aryeh Gregor called jsMsg() added in r23233.
 *
 * @author Krinkle <krinklemail@gmail.com>
 *
 * Dual license:
 * @license CC-BY 3.0 <http://creativecommons.org/licenses/by/3.0>
 * @license GPL2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */
( function( $ ) {
// @return jQuery object of the message box
$.messageBoxNew = function( options ) {
	options = $.extend( {
		'id': 'js-messagebox', // unique identifier for this message box
		'parent': 'body', // jQuery/CSS selector
		'insert': 'prepend' // 'prepend' or 'append'
	}, options );
	var $curBox = $( '#' + options.id );
	// Only create a new box if it doesn't exist already
	if ( $curBox.length > 0 ) {
		if ( $curBox.hasClass( 'js-messagebox' ) ) {
			return $curBox;
		} else {
			return $curBox.addClass( 'js-messagebox' );
		}
	} else {
		var $newBox = $( '<div>', {
			'id': options.id,
			'class': 'js-messagebox',
			'css': {
				'display': 'none'
			}
		});
		if ( $( options.parent ).length < 1 ) {
			options.parent = 'body';
		}
		if ( options.insert === 'append' ) {
			$newBox.appendTo( options.parent );
			return $newBox;
		} else {
			$newBox.prependTo( options.parent );
			return $newBox;
		}
	}
};
// Calling with no message or message set to empty string or null will hide the group,
// setting 'replace' to true as well will reset and hide the group entirely.
// If there are no visible groups the main message box is hidden automatically,
// and shown again once there are messages
// @return jQuery object of message group
$.messageBox = function( options ) {
	options = $.extend( {
		'message': '',
		'group': 'default',
		'replace': false, // if true replaces any previous message in this group
		'target': 'js-messagebox'
	}, options );
	var $target = $.messageBoxNew( { id: options.target } );
	var groupID = options.target + '-' + options.group;
	var $group = $( '#' + groupID );
	// Create group container if not existant
	if ( $group.length < 1 ) {
		$group = $( '<div>', {
			'id': groupID,
			'class': 'js-messagebox-group'
		});
		$target.prepend( $group );
	}
	// Replace ?
	if ( options.replace === true ) {
		$group.empty();
	}
	// Hide it ?
	if ( options.message === '' || options.message === null ) {
		$group.hide();
	} else {
		// Actual message addition
		$group.prepend( $( '<p>' ).append( options.message ) ).show();
		$target.slideDown();
	}
	// If the last visible group was just hidden, slide the entire box up
	// Othere wise slideDown (if already visible nothing will happen)
	if ( $target.find( '> *:visible' ).length === 0 ) {
		// to avoid a sudden dissapearance of the last group followed by
		// a slide up of only the outline, show it for a second
		$group.show();
		$target.slideUp();
		$group.hide();
	} else {
		$target.slideDown();
	}
	return $group;
};
} )( jQuery );
