/**
 * Password strength checker
 * @license WTFPL 2.0
 * All scores are ranged approximately 0 (total disaster) - 100 (_looks_ great)
 * @todo Check for popular passwords and keyboard sequences (QWERTY, etc)
 */

function bruteForceComplexity( pwd ) {
	var score = 0;

	if ( pwd.length < 16 ) {
		score = pwd.length * 5;
	} else {
		score = 80;
	}

	var regexes = [
		/[a-z]/,
		/[A-Z]/,
		/[0-9]/,
		/[-_;:\.,'"`~!@#$%\^&\*\(\)\[\]\{\} ]/ ];

	var charClasses = 0;
	for ( var i in regexes ) {
		if ( pwd.match( regexes[i] ) ) {
			charClasses++;
		}
	}

	var matches = pwd.match( /[\x80-\uFFFF]/g );
	if ( matches ) {
		charClasses++;
		
		// poor man's isUpper() and isLower()
		var i, lower = false, upper = false;
		for ( i in matches ) {
			var ch = matches[i];
			upper |= ch != ch.toLowerCase();
			lower |= ch != ch.toUpperCase();
			if ( upper && lower ) break;
		}
		if ( upper && lower ) {
			charClasses++;
		}
	}
	score += ( charClasses - 1 ) * 10;

	return score;
}

function repetitionScore( pwd ) {
	var unique = '';
	for ( var i in pwd ) {
		if ( unique.indexOf( pwd[i] ) < 0 ) {
			unique += pwd[i];
		}
	}
	var ratio = pwd.length / unique.length - 0.4; // allow up to 40% repetition, reward for less, penalize for more
	
	return 100 / ratio;
}

function sequenceScore( pwd ) {
	pwd = pwd.concat( '\0' );
	var score = 100, sequence = 1;
	for ( var i = 1; i < pwd.length; i++ ) {
		if ( pwd.charCodeAt( i ) == pwd.charCodeAt(i - 1) + 1 ) {
			sequence++;
		} else {
			if ( sequence > 2 ) {
				score -= Math.sqrt( sequence ) * 15;
			}
			sequence = 1;
		}
	}
	for ( var i = 1; i < pwd.length; i++ ) {
		if ( pwd.charCodeAt( i ) == pwd.charCodeAt(i - 1) - 1 ) {
			sequence++;
		} else {
			if ( sequence > 2 ) {
				score -= Math.sqrt( sequence ) * 15;
			}
			sequence = 1;
		}
	}
	return score;
}

(function( $ ) {
	function passwordChanged() {
		retypeChanged();
		var pwd = $( passwordSecurity.password ).val();
		if ( pwd == '' ) {
			$( '#password-strength' ).html( '' );
			return;
		}
		if ( pwd.length > 100 ) pwd = pwd.slice( 0, 100 );
		var score = Math.min(
			bruteForceComplexity( pwd ),
			repetitionScore( pwd ),
			sequenceScore( pwd )
		);
		var result = 'good';
		if ( score < 40 ) {
			result = 'bad';
		} else if ( score < 60 ) {
			result = 'mediocre';
		} else if ( score < 85 ) {
			result = 'acceptable';
		}
		var message = '<span class="mw-password-' + result + '">' + passwordSecurity.messages['password-strength-' + result]
			+ '</span>';
		$( '#password-strength' ).html(
			passwordSecurity.messages['password-strength'].replace( '$1', message )
		);
	}

	function retypeChanged() {
		var pwd = $( passwordSecurity.password ).val();
		var retype = $( passwordSecurity.retype ).val();
		var message;
		if ( pwd == '' || pwd == retype ) {
			message = '';
		} else if ( retype == '' ) {
			message = passwordSecurity.messages['password-retype'];
		} else {
			message = passwordSecurity.messages['password-retype-mismatch'];
		}
		$( '#password-retype' ).html( message );
	}

	$( document ).ready( function() {
		$( passwordSecurity.password ).bind( 'keyup change', passwordChanged );
		$( passwordSecurity.retype ).bind( 'keyup change', retypeChanged );
	})
})(jQuery);
