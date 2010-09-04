/*
 * Web-installer progressive enhancement (ported from skins/common/config.js)
 * 
 * Makes elements in the configuration form interactive and hides portions of the form when not in-use
 */

( function( $, mw ) {

/* Initialization */

$( document ).ready( function() {
	// Show/hide code for help text
	$( '.config-show-help a' ).click( function() {
		$(this).parent().siblings( '.config-help-message' ).show( 'slow' );
		$(this).parent().siblings( '.config-hide-help' ).show();
		$(this).parent().hide();
		return false;
	} );
	$( '.config-hide-help a' ).click( function() {
		$(this).parent().siblings( '.config-help-message' ).hide( 'slow' );
		$(this).parent().siblings( '.config-show-help' ).show();
		$(this).parent().hide();
		return false;
	} );	
	// Show/hide code for DB-specific options
	// FIXME: Do we want slow, fast, or even non-animated (instantaneous) showing/hiding here?
	$( '.dbRadio' ).each( function() { $( '#' + $(this).attr( 'rel' ) ).hide(); } );
	$( '#' + $( '.dbRadio:checked' ).attr( 'rel' ) ).show();
	$( '.dbRadio' ).click( function() {
		var $checked = $( '.dbRadio:checked' );
		var $wrapper = $( '#' + $checked.attr( 'rel' ) );
		if ( !$wrapper.is( ':visible' ) ) {
			$( '.dbWrapper' ).hide( 'slow' );
			$wrapper.show( 'slow' );
		}
	} );	
	// Scroll to the bottom of upgrade log
	$( "#config-update-log" ).each( function() { this.scrollTop = this.scrollHeight; } );
	// Show/hide Creative Commons thingy
	$( '.licenseRadio' ).click( function() {
		var $wrapper = $( '#config-cc-wrapper' );
		if ( $( '#config__LicenseCode_cc-choose' ).is( ':checked' ) ) {
			$wrapper.show( 'slow' );
		} else {
			$wrapper.hide( 'slow' );
		}
	} );
	// Show/hide random stuff (email, upload)
	$( '.showHideRadio' ).click( function() {
		var $wrapper = $( '#' + $(this).attr( 'rel' ) );
		if ( $(this).is( ':checked' ) ) {
			$wrapper.show( 'slow' );
		} else {
			$wrapper.hide( 'slow' );
		}
	} );
	$( '.hideShowRadio' ).click( function() {
		var $wrapper = $( '#' + $(this).attr( 'rel' ) );
		if ( $(this).is( ':checked' ) ) {
			$wrapper.hide( 'slow' );
		} else {
			$wrapper.show( 'slow' );
		}
	} );
	// Enable/disable "other" textboxes
	$( '.enableForOther' ).click( function() {
		var $textbox = $( '#' + $(this).attr( 'rel' ) );
		if ( $(this).val() == 'other' ) { // FIXME: Ugh, this is ugly
			$textbox.removeAttr( 'disabled' );
		} else {
			$textbox.attr( 'disabled', 'disabled' );
		}
	} );
	// Synchronize radio button label for sitename with textbox
	$label = $( 'label[for=config__NamespaceType_site-name]' );
	labelText = $label.text();
	$label.text( labelText.replace( '$1', '' ) );
	$( '#config_wgSitename' ).bind( 'keyup change', syncText ).each( syncText );
	function syncText() {
		var value = $(this).val()
			.replace( /[\[\]\{\}|#<>%+? ]/g, '_' )
			.replace( /&/, '&amp;' )
			.replace( /__+/g, '_' )
			.replace( /^_+/, '' )
			.replace( /_+$/, '' );
		value = value.substr( 0, 1 ).toUpperCase() + value.substr( 1 );
		$label.text( labelText.replace( '$1', value ) );
	}
	// Show/Hide memcached servers when needed
	$( "input[name$='config_wgMainCacheType']" ).change( function() {
		var $memc = $( "#config-memcachewrapper" );
		if ( $( "input[name$='config_wgMainCacheType']:checked" ).val() == 'memcached' ) {
			$memc.show( 'slow' );
		} else {
			$memc.hide( 'slow' );
		}
	} );
} );

} )( jQuery, mediaWiki );