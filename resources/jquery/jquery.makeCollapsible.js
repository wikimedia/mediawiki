/**
 * jQuery makeCollapsible
 *
 * This will enable collapsible-functionality on all passed elements.
 * Will prevent binding twice to the same element.
 * Initial state is expanded by default, this can be overriden by adding class
 * "mw-collapsed" to the "mw-collapsible" element.
 * Elements made collapsible have class "mw-made-collapsible".
 * Except for tables and lists, the inner content is wrapped in "mw-collapsible-content".
 *
 * @author Krinkle <krinklemail@gmail.com>
 *
 * Dual license:
 * @license CC-BY 3.0 <http://creativecommons.org/licenses/by/3.0>
 * @license GPL2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

$.fn.makeCollapsible = function() {

	return this.each(function() {
		mw.config.set( 'mw.log.prefix', 'jquery.makeCollapsible' );

		// Define reused variables and functions
		var	$that = $(this).addClass( 'mw-collapsible' ), // case: $( '#myAJAXelement' ).makeCollapsible()
			that = this,
			collapsetext = $(this).attr( 'data-collapsetext' ),
			expandtext = $(this).attr( 'data-expandtext' ),
			toggleElement = function( $collapsible, action, $defaultToggle ) {
				// Validate parameters
				if ( !$collapsible.jquery ) { // $collapsible must be an instance of jQuery
					return;
				}
				if ( action != 'expand' && action != 'collapse' ) {
					// action must be string with 'expand' or 'collapse'
					return;
				}
				if ( typeof $defaultToggle !== 'undefined' && !$defaultToggle.jquery ) {
					// is optional, but if passed must be an instance of jQuery
					return;
				}

				if ( action == 'collapse' ) {

					// Collapse the element
					if ( $collapsible.is( 'table' ) ) {
						// Hide all table rows of this table
						// Slide doens't work with tables, but fade does as of jQuery 1.1.3
						// http://stackoverflow.com/questions/467336#920480

						if ( $defaultToggle.jquery ) {
							// Exclude tablerow containing togglelink
							$collapsible.find( '>tbody>tr' ).not( $defaultToggle.parent().parent() ).stop(true, true).fadeOut();
						} else {
							$collapsible.find( '>tbody>tr' ).stop( true, true ).fadeOut();
						}
	
					} else if ( $collapsible.is( 'ul' ) || $collapsible.is( 'ol' ) ) {
						if ( $defaultToggle.jquery ) {
							// Exclude list-item containing togglelink
							$collapsible.find( '> li' ).not( $defaultToggle.parent() ).stop( true, true ).slideUp();
						} else {
							$collapsible.find( '> li' ).stop( true, true ).slideUp();
						}
	
					} else { // <div>, <p> etc.
						$collapsible.find( '> .mw-collapsible-content' ).slideUp();
					}

				} else {
				
					// Expand the element
					if ( $collapsible.is( 'table' ) ) {
						if ( $defaultToggle.jquery ) {
							// Exclude tablerow containing togglelink
							$collapsible.find( '>tbody>tr' ).not( $defaultToggle.parent().parent() ).stop(true, true).fadeIn();
						} else {
							$collapsible.find( '>tbody>tr' ).stop(true, true).fadeIn();
						}
	
					} else if ( $collapsible.is( 'ul' ) || $collapsible.is( 'ol' ) ) {
						if ( $defaultToggle.jquery ) {
							// Exclude list-item containing togglelink
							$collapsible.find( '> li' ).not( $defaultToggle.parent() ).stop( true, true ).slideDown();
						} else {
							$collapsible.find( '> li' ).stop( true, true ).slideDown();
						}
	
					} else { // <div>, <p> etc.
						$collapsible.find( '> .mw-collapsible-content' ).slideDown();
					}
				}
			},
			toggleLinkDefault = function( that, e ) {
				var	$that = $(that),
					$collapsible = $that.closest( '.mw-collapsible.mw-made-collapsible' ).toggleClass( 'mw-collapsed' );
				e.preventDefault();
				
				// It's expanded right now
				if ( $that.hasClass( 'mw-collapsible-toggle-expanded' ) ) {
					// Change link to "Show"
					$that.removeClass( 'mw-collapsible-toggle-expanded' ).addClass( 'mw-collapsible-toggle-collapsed' );
					if ( $that.find( '> a' ).size() ) {
						$that.find( '> a' ).text( expandtext );
					} else {
						$that.text( expandtext );
					}
					// Collapse element
					toggleElement( $collapsible, 'collapse', $that );

				// It's collapsed right now
				} else {
					// Change link to "Hide"
					$that.removeClass( 'mw-collapsible-toggle-collapsed' ).addClass( 'mw-collapsible-toggle-expanded' );
					if ( $that.find( '> a' ).size() ) {
						$that.find( '> a' ).text( collapsetext );
					} else {
						$that.text( collapsetext );
					}
					// Expand element
					toggleElement( $collapsible, 'expand', $that );
				}
				return;
			},
			toggleLinkCustom = function( that, e ) {
				var	$that = $(that),
					classes = that.className.split(' ');
				e.preventDefault();
				// Check each class to see if it belongs to a customcollapse
				for ( i = 0; i < classes.length; i++ ) {
					if ( classes[i].indexOf( 'mw-customtoggle-' ) === 0 ) {
						var id = '#' + classes[i].replace( 'mw-customtoggle-', 'mw-customcollapsible-' ),
							$collapsible = $( id ),
							action = $collapsible.hasClass( 'mw-collapsed' ) ? 'expand' : 'collapse';

						$collapsible.toggleClass( 'mw-collapsed' );
						toggleElement( $collapsible, action, $that );
					}
				}
			};

		// Use custom text or default ?
		if( !collapsetext || collapsetext === '' ){
			collapsetext = mw.msg( 'collapsible-collapse', 'Collapse' );
		}
		if ( !expandtext || expandtext === '' ){
			expandtext = mw.msg( 'collapsible-expand', 'Expand' );
		}

		// Create toggle link with a space around the brackets (&nbsp;[text]&nbsp;)
		var $toggleLink = $( '<a href="#">' ).text( collapsetext ).wrap( '<span class="mw-collapsible-toggle mw-collapsible-toggle-expanded">' ).parent().prepend( '&nbsp;[' ).append( ']&nbsp;' ).bind( 'click.mw-collapse', function(e){
			toggleLinkDefault( this, e );
		} );

		// Return if it has been enabled already.
		if ( $that.hasClass( 'mw-made-collapsible' ) ) {
			return;
		} else {
			$that.addClass( 'mw-made-collapsible' );
		}
		
		// Check if this element has a custom position for the toggle link
		// (ie. outside the container or deeper inside the tree)
		// Then: Locate the custom toggle link(s) and bind them
		if ( $that.attr( 'id' ).indexOf( 'mw-customcollapsible-' ) === 0 ) {
			// @FIXME: Incomplete
			var thatId = $that.attr( 'id' ),
				$customTogglers = $( '.' + thatId.replace( 'mw-customcollapsible', 'mw-customtoggle' ) );
			mw.log( 'Found custom collapsible: #' + thatId );
						
			// Double check that there is actually a customtoggle link
			if ( $customTogglers.size() ) {
				$customTogglers.bind( 'click.mw-collapse', function( e ) {
					toggleLinkCustom( this, e );
				} );
			} else {
				mw.log( '#' + thatId + ': Missing toggler!' );
			}
			
			// To change initial state at the bottom of the script
			// Set this variable to one of the togglers
			var $toggleLink = $customTogglers.eq(0);

		// If this is not a custom case, do the default:
		// Wrap the contents add the toggle link 
		} else {

			// Elements are treated differently
			if ( $that.is( 'table' ) ) {
				// The toggle-link will be in the last cell (td or th) of the first row
				var	$lastCell = $( 'tr:first th, tr:first td', that ).eq(-1),
					$toggle = $lastCell.find( '> .mw-collapsible-toggle' );
	
				// If theres no toggle link, add it
				if ( !$toggle.size() ) {
					$lastCell.prepend( $toggleLink );
				} else {
					$toggleLink = $toggle.unbind( 'click.mw-collapse' ).bind( 'click.mw-collapse', function( e ){
						toggleLinkDefault( this, e );
					} );
				}
				
			} else if ( $that.is( 'ul' ) || $that.is( 'ol' ) ) {
				// The toggle-link will be in the first list-item
				var	$firstItem = $( 'li:first', $that),
					$toggle = $firstItem.find( '> .mw-collapsible-toggle' );
	
				// If theres no toggle link, add it
				if ( !$toggle.size() ) {
					// Make sure the numeral order doesn't get messed up, reset to 1 unless value-attribute is already used
					// WebKit return '' if no value, Mozilla returns '-1' is no value
					if ( $firstItem.attr( 'value' ) == '' || $firstItem.attr( 'value' ) == '-1' ) { // Will fail with ===
						$firstItem.attr( 'value', '1' );
					}
					$that.prepend( $toggleLink.wrap( '<li class="mw-collapsible-toggle-li">' ).parent() );
				} else {
					$toggleLink = $toggle.unbind( 'click.mw-collapse' ).bind( 'click.mw-collapse', function( e ){
						toggleLinkDefault( this, e );
					} );
				}
	
			} else { // <div>, <p> etc.
				// If a direct child .content-wrapper does not exists, create it
				if ( !$that.find( '> .mw-collapsible-content' ).size() ) {
					$that.wrapInner( '<div class="mw-collapsible-content">' );
				}
	
				// The toggle-link will be the first child of the element
				var $toggle = $that.find( '> .mw-collapsible-toggle' );
	
				// If theres no toggle link, add it
				if ( !$toggle.size() ) {
					$that.prepend( $toggleLink );
				} else {
					$toggleLink = $toggle.unbind( 'click.mw-collapse' ).bind( 'click.mw-collapse', function( e ){
						toggleLinkDefault( this, e );
					} );
				}
			}
		}

		// Initial state
		if ( $that.hasClass( 'mw-collapsed' ) ) {
			$that.removeClass( 'mw-collapsed' );
			$toggleLink.click();
		}
	} );
};