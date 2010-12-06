/**
 * Password strength checker
 * @license WTFPL 2.0
 * All scores are ranged approximately 0 (total disaster) - 100 (_looks_ great)
 * @todo Check for popular passwords and keyboard sequences (QWERTY, etc)
 */

// Estimates how hard it would be to pick the password using brute force
window.bruteForceComplexity = function( pwd ) {
	var score = pwd.length * 5;

	var regexes = [
		/[a-z]/,
		/[A-Z]/,
		/[0-9]/,
		/[-_;:\.,'"`~!@#$%\^&\*\(\)\[\]\{\} ]/
	];

	var charClasses = 0;
	for ( var i=0; i< regexes.length; i++ ) {
		if ( pwd.match( regexes[i] ) ) {
			charClasses++;
		}
	}

	var matches = pwd.match( /[\x80-\uFFFF]/g );
	if ( matches ) {
		charClasses++;
		
		var s = matches.join( '' );
		// poor man's isUpper() and isLower()
		if ( s != s.toLowerCase() && s != s.toUpperCase() ) {
			charClasses++;
		}
	}
	score += ( charClasses - 1 ) * 10;

	return score;
};

// Calculates a penalty to brute force score due to character repetition
window.repetitionAdjustment = function( pwd ) {
	var unique = '';
	for ( var i=0; i< pwd.length; i++ ) {
		if ( unique.indexOf( pwd[i] ) < 0 ) {
			unique += pwd[i];
		}
	}
	var ratio = pwd.length / unique.length - 0.4; // allow up to 40% repetition, reward for less, penalize for more
	
	return ratio * 10;
};

// Checks how many simple sequences ("abc", "321") are there in the password
window.sequenceScore = function( pwd ) {
	pwd = pwd.concat( '\0' );
	var score = 100, sequence = 1;
	for ( var i = 1; i < pwd.length; i++ ) {
		if ( pwd.charCodeAt( i ) == pwd.charCodeAt(i - 1) + 1 ) {
			sequence++;
		} else {
			if ( sequence > 2 ) {
				score -= sequence * 7;
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
};

(function( $ ) {
	function passwordChanged() {
		retypeChanged();
		var pwd = $( passwordSecurity.password ).val();
		if ( pwd == '' ) {
			$( '#password-strength' ).html( '' );
			return;
		}
		if ( pwd.length > 100 ) pwd = pwd.slice( 0, 100 );
		var scores = [
			bruteForceComplexity( pwd ),
			repetitionAdjustment( pwd ),
			sequenceScore( pwd )
		];

		var score = Math.min( scores[0] - scores[1], scores[2] );
		var result = 'good';
		if ( score < 40 ) {
			result = 'bad';
		} else if ( score < 60 ) {
			result = 'mediocre';
		} else if ( score < 80 ) {
			result = 'acceptable';
		}
		var message = '<span class="mw-password-' + result + '">' + passwordSecurity.messages['password-strength-' + result]
			+ '</span>';
		$( '#password-strength' ).html(
			passwordSecurity.messages['password-strength'].replace( '$1', message )
			 //+ scores
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
