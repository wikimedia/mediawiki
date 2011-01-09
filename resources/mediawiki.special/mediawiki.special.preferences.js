/*
 * JavaScript for Special:Preferences
 */

$( '#prefsubmit' ).attr( 'id', 'prefcontrol' );
$( '#preferences' )
	.addClass( 'jsprefs' )
	.before( $( '<ul id="preftoc"></ul>' ) )
	.children( 'fieldset' )
	.hide()
	.addClass( 'prefsection' )
	.children( 'legend' )
	.addClass( 'mainLegend' )
	.each( function( i ) {
		$(this).parent().attr( 'id', 'prefsection-' + i );
		if ( i === 0 ) {
			$(this).parent().show();
		}
		$( '#preftoc' ).append(
			$( '<li></li>' )
				.addClass( i === 0 ? 'selected' : null )
				.append(
					$( '<a></a>')
						.text( $(this).text() )
						.attr( 'href', '#prefsection-' + i )
						.mousedown( function( e ) {
							$(this).parent().parent().find( 'li' ).removeClass( 'selected' );
							$(this).parent().addClass( 'selected' );
							e.preventDefault();
							return false;
						} )
						.click( function( e ) {
							$( '#preferences > fieldset' ).hide();
							$( '#prefsection-' + i ).show();
							e.preventDefault();
							return false;
						} )
				)
		);
	} );

// User preference form validation
$( '#mw-prefs-form' )
	.submit( function () {
		var isValid = wfValidateEmail( $('#mw-input-wpemailaddress').val() );
		wfUpdateMailValidityLabel( isValid );
		if(isValid == false ) {
			$('#mw-input-wpemailaddress').focus();
			return false;
		}
	}
);

// Lame tip to let user know if its email is valid. See bug 22449
$( '#mw-input-wpemailaddress' )
	.blur( function () {
		if( $( "#mw-emailaddress-validity" ).length == 0 ) {
			$(this).after( '<label for="mw-input-wpemailaddress" id="mw-emailaddress-validity"></label>' );
		}
		var isValid = wfValidateEmail( $(this).val() );
		wfUpdateMailValidityLabel( isValid );
	} );

/**
 * Given an email validity status (true, false, null) update the label CSS class
 */
wfUpdateMailValidityLabel = function( isValid ) {
	var class_to_add    = isValid ? 'valid' : 'invalid';
	var class_to_remove = isValid ? 'invalid' : 'valid';

	// We allow null address
	if( isValid == null ) {
		$( '#mw-emailaddress-validity' ).text( '' )
		.removeClass( 'valid invalid');
	} else {
	$( '#mw-emailaddress-validity' )
		.text(
			isValid ?
			mediaWiki.msg( 'email-address-validity-valid' )
			: mediaWiki.msg( 'email-address-validity-invalid' )
		)
		.addClass( class_to_add )
		.removeClass( class_to_remove );
	}
}

/**
 *  Validate a string as representing a valid e-mail address
 * according to HTML5 specification. Please note the specification
 * does not validate a domain with one character.
 *
 * FIXME: should be moved to a JavaScript validation module.
 */
wfValidateEmail = function( mailtxt ) {
	if( mailtxt == '' ) { return null; }

	/**
	 * HTML 5 define a string as valid e-mail address if it matches
	 * the ABNF :
	 *   1 * ( atext / "." ) "@" ldh-str 1*( "." ldh-str )
	 * With:
	 * - atext   : defined in RFC 5322 section 3.2.3
	 * - ldh-str : defined in RFC 1034 section 3.5
	 *
	 * (see STD 68 / RFC 5234 http://tools.ietf.org/html/std68):
	 */

	/**
	 * First, define the RFC 5322 'atext' which is pretty easy :
	 * atext = ALPHA / DIGIT /    ; Printable US-ASCII
                       "!" / "#" /        ;  characters not including
                       "$" / "%" /        ;  specials.  Used for atoms.
                       "&" / "'" /
                       "*" / "+" /
                       "-" / "/" /
                       "=" / "?" /
                       "^" / "_" /
                       "`" / "{" /
                       "|" / "}" /
                       "~"
	*/
	var rfc5322_atext   = "a-z0-9!#$%&'*+-/=?^_`{|}—~" ;

	/**
	 * Next define the RFC 1034 'ldh-str'
	 *   <domain> ::= <subdomain> | " "
	 *   <subdomain> ::= <label> | <subdomain> "." <label>
	 *   <label> ::= <letter> [ [ <ldh-str> ] <let-dig> ]
	 *   <ldh-str> ::= <let-dig-hyp> | <let-dig-hyp> <ldh-str>
	 *   <let-dig-hyp> ::= <let-dig> | "-"
	 *   <let-dig> ::= <letter> | <digit>
	 */
	var rfc1034_ldh_str = "a-z0-9-" ;

	var HTML5_email_regexp = new RegExp(
		// start of string
		'^'
		+
		// User part which is liberal :p
		'[' + rfc5322_atext + '\\.' + ']' + '+'
		+
		// "apostrophe"
		'@'
		+
		// Domain first part
		'[' + rfc1034_ldh_str + ']+'
		+
		// Second part and following are separated by a dot
		'(?:\\.[' + rfc1034_ldh_str + ']+)+'
		+
		// End of string
		'$',
		// RegExp is case insensitive
		'i'
		);
	return (null != mailtxt.match( HTML5_email_regexp ) );
};
