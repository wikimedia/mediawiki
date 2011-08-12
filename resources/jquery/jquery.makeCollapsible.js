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
( function( $, mw ) {

$.fn.makeCollapsible = function() {

	return this.each(function() {
		var _fn = 'jquery.makeCollapsible> ';

		// Define reused variables and functions
		var	$that = $(this).addClass( 'mw-collapsible' ), // case: $( '#myAJAXelement' ).makeCollapsible()
			that = this,
			collapsetext = $(this).attr( 'data-collapsetext' ),
			expandtext = $(this).attr( 'data-expandtext' ),
			toggleElement = function( $collapsible, action, $defaultToggle, instantHide ) {
				// Validate parameters
				if ( !$collapsible.jquery ) { // $collapsible must be an instance of jQuery
					return;
				}
				if ( action != 'expand' && action != 'collapse' ) {
					// action must be string with 'expand' or 'collapse'
					return;
				}
				if ( typeof $defaultToggle == 'undefined' ) {
					$defaultToggle = null;
				}
				if ( $defaultToggle !== null && !($defaultToggle instanceof $) ) {
					// is optional (may be undefined), but if defined it must be an instance of jQuery.
					// If it's not, abort right away.
					// After this $defaultToggle is either null or a valid jQuery instance.
					return;
				}

				var $containers = null;

				if ( action == 'collapse' ) {

					// Collapse the element
					if ( $collapsible.is( 'table' ) ) {
						// Hide all table rows of this table
						// Slide doens't work with tables, but fade does as of jQuery 1.1.3
						// http://stackoverflow.com/questions/467336#920480
						$containers = $collapsible.find( '>tbody>tr' );
						if ( $defaultToggle ) {
							// Exclude tablerow containing togglelink
							$containers.not( $defaultToggle.closest( 'tr' ) ).stop(true, true).fadeOut();
						} else {
							if ( instantHide ) {
								$containers.hide();
							} else {
								$containers.stop( true, true ).fadeOut();
							}
						}

					} else if ( $collapsible.is( 'ul' ) || $collapsible.is( 'ol' ) ) {
						$containers = $collapsible.find( '> li' );
						if ( $defaultToggle ) {
							// Exclude list-item containing togglelink
							$containers.not( $defaultToggle.parent() ).stop( true, true ).slideUp();
						} else {
							if ( instantHide ) {
								$containers.hide();
							} else {
								$containers.stop( true, true ).slideUp();
							}
						}

					} else { // <div>, <p> etc.
						var $collapsibleContent = $collapsible.find( '> .mw-collapsible-content' );

						// If a collapsible-content is defined, collapse it
						if ( $collapsibleContent.length ) {
							if ( instantHide ) {
								$collapsibleContent.hide();
							} else {
								$collapsibleContent.slideUp();
							}

						// Otherwise assume this is a customcollapse with a remote toggle
						// .. and there is no collapsible-content because the entire element should be toggled
						} else {
							if ( $collapsible.is( 'tr' ) || $collapsible.is( 'td' ) || $collapsible.is( 'th' ) ) {
								$collapsible.fadeOut();
							} else {
								$collapsible.slideUp();
							}
						}
					}

				} else {

					// Expand the element
					if ( $collapsible.is( 'table' ) ) {
						$containers = $collapsible.find( '>tbody>tr' );
						if ( $defaultToggle ) {
							// Exclude tablerow containing togglelink
							$containers.not( $defaultToggle.parent().parent() ).stop(true, true).fadeIn();
						} else {
							$containers.stop(true, true).fadeIn();
						}

					} else if ( $collapsible.is( 'ul' ) || $collapsible.is( 'ol' ) ) {
						$containers = $collapsible.find( '> li' );
						if ( $defaultToggle ) {
							// Exclude list-item containing togglelink
							$containers.not( $defaultToggle.parent() ).stop( true, true ).slideDown();
						} else {
							$containers.stop( true, true ).slideDown();
						}

					} else { // <div>, <p> etc.
						var $collapsibleContent = $collapsible.find( '> .mw-collapsible-content' );

						// If a collapsible-content is defined, collapse it
						if ( $collapsibleContent.length ) {
							$collapsibleContent.slideDown();

						// Otherwise assume this is a customcollapse with a remote toggle
						// .. and there is no collapsible-content because the entire element should be toggled
						} else {
							if ( $collapsible.is( 'tr' ) || $collapsible.is( 'td' ) || $collapsible.is( 'th' ) ) {
								$collapsible.fadeIn();
							} else {
								$collapsible.slideDown();
							}
						}
					}
				}
			},
			// Toggles collapsible and togglelink class and updates text label
			toggleLinkDefault = function( that, e ) {
				var	$that = $(that),
					$collapsible = $that.closest( '.mw-collapsible.mw-made-collapsible' ).toggleClass( 'mw-collapsed' );
				e.preventDefault();
				e.stopPropagation();

				// It's expanded right now
				if ( !$that.hasClass( 'mw-collapsible-toggle-collapsed' ) ) {
					// Change link to "Show"
					$that.removeClass( 'mw-collapsible-toggle-expanded' ).addClass( 'mw-collapsible-toggle-collapsed' );
					if ( $that.find( '> a' ).length ) {
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
					if ( $that.find( '> a' ).length ) {
						$that.find( '> a' ).text( collapsetext );
					} else {
						$that.text( collapsetext );
					}
					// Expand element
					toggleElement( $collapsible, 'expand', $that );
				}
				return;
			},
			// Toggles collapsible and togglelink class
			toggleLinkPremade = function( $that, e ) {
				var	$collapsible = $that.eq(0).closest( '.mw-collapsible.mw-made-collapsible' ).toggleClass( 'mw-collapsed' );
				if ( $(e.target).is('a') ) {
					return true;
				}
				e.preventDefault();
				e.stopPropagation();

				// It's expanded right now
				if ( !$that.hasClass( 'mw-collapsible-toggle-collapsed' ) ) {
					// Change toggle to collapsed
					$that.removeClass( 'mw-collapsible-toggle-expanded' ).addClass( 'mw-collapsible-toggle-collapsed' );
					// Collapse element
					toggleElement( $collapsible, 'collapse', $that );

				// It's collapsed right now
				} else {
					// Change toggle to expanded
					$that.removeClass( 'mw-collapsible-toggle-collapsed' ).addClass( 'mw-collapsible-toggle-expanded' );
					// Expand element
					toggleElement( $collapsible, 'expand', $that );
				}
				return;
			},
			// Toggles customcollapsible
			toggleLinkCustom = function( $that, e, $collapsible ) {
				// For the initial state call of customtogglers there is no event passed
				if (e) {
					e.preventDefault();
				e.stopPropagation();
				}
				// Get current state and toggle to the opposite
				var action = $collapsible.hasClass( 'mw-collapsed' ) ? 'expand' : 'collapse';
				$collapsible.toggleClass( 'mw-collapsed' );
				toggleElement( $collapsible, action, $that );

			};

		// Use custom text or default ?
		if( !collapsetext ) {
			collapsetext = mw.msg( 'collapsible-collapse' );
		}
		if ( !expandtext ) {
			expandtext = mw.msg( 'collapsible-expand' );
		}

		// Create toggle link with a space around the brackets (&nbsp;[text]&nbsp;)
		var $toggleLink =
			$( '<a href="#"></a>' )
				.text( collapsetext )
				.wrap( '<span class="mw-collapsible-toggle"></span>' )
				.parent()
				.prepend( '&nbsp;[' )
				.append( ']&nbsp;' )
				.bind( 'click.mw-collapse', function(e) {
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
		if ( ( $that.attr( 'id' ) || '' ).indexOf( 'mw-customcollapsible-' ) === 0 ) {

			var thatId = $that.attr( 'id' ),
				$customTogglers = $( '.' + thatId.replace( 'mw-customcollapsible', 'mw-customtoggle' ) );
			mw.log( _fn + 'Found custom collapsible: #' + thatId );

			// Double check that there is actually a customtoggle link
			if ( $customTogglers.length ) {
				$customTogglers.bind( 'click.mw-collapse', function( e ) {
					toggleLinkCustom( $(this), e, $that );
				} );
			} else {
				mw.log( _fn + '#' + thatId + ': Missing toggler!' );
			}

			// Initial state
			if ( $that.hasClass( 'mw-collapsed' ) ) {
				$that.removeClass( 'mw-collapsed' );
				toggleLinkCustom( $customTogglers, null, $that );
			}

		// If this is not a custom case, do the default:
		// Wrap the contents add the toggle link
		} else {

			// Elements are treated differently
			if ( $that.is( 'table' ) ) {
				// The toggle-link will be in one the the cells (td or th) of the first row
				var	$firstRowCells = $( 'tr:first th, tr:first td', that ),
					$toggle = $firstRowCells.find( '> .mw-collapsible-toggle' );

				// If theres no toggle link, add it to the last cell
				if ( !$toggle.length ) {
					$firstRowCells.eq(-1).prepend( $toggleLink );
				} else {
					$toggleLink = $toggle.unbind( 'click.mw-collapse' ).bind( 'click.mw-collapse', function( e ) {
						toggleLinkPremade( $toggle, e );
					} );
				}

			} else if ( $that.is( 'ul' ) || $that.is( 'ol' ) ) {
				// The toggle-link will be in the first list-item
				var	$firstItem = $( 'li:first', $that),
					$toggle = $firstItem.find( '> .mw-collapsible-toggle' );

				// If theres no toggle link, add it
				if ( !$toggle.length ) {
					// Make sure the numeral order doesn't get messed up, force the first (soon to be second) item
					// to be "1". Except if the value-attribute is already used.
					// If no value was set WebKit returns "", Mozilla returns '-1', others return null or undefined.
					var firstval = $firstItem.attr( 'value' );
					if ( firstval === undefined || !firstval || firstval == '-1' ) {
						$firstItem.attr( 'value', '1' );
					}
					$that.prepend( $toggleLink.wrap( '<li class="mw-collapsible-toggle-li"></li>' ).parent() );
				} else {
					$toggleLink = $toggle.unbind( 'click.mw-collapse' ).bind( 'click.mw-collapse', function( e ) {
						toggleLinkPremade( $toggle, e );
					} );
				}

			} else { // <div>, <p> etc.

				// The toggle-link will be the first child of the element
				var $toggle = $that.find( '> .mw-collapsible-toggle' );

				// If a direct child .content-wrapper does not exists, create it
				if ( !$that.find( '> .mw-collapsible-content' ).length ) {
					$that.wrapInner( '<div class="mw-collapsible-content"></div>' );
				}

				// If theres no toggle link, add it
				if ( !$toggle.length ) {
					$that.prepend( $toggleLink );
				} else {
					$toggleLink = $toggle.unbind( 'click.mw-collapse' ).bind( 'click.mw-collapse', function( e ) {
						toggleLinkPremade( $toggle, e );
					} );
				}
			}
		}

		// Initial state (only for those that are not custom)
		if ( $that.hasClass( 'mw-collapsed' ) && ( $that.attr( 'id' ) || '').indexOf( 'mw-customcollapsible-' ) !== 0 ) {
			$that.removeClass( 'mw-collapsed' );
			// The collapsible element could have multiple togglers
			// To toggle the initial state only click one of them (ie. the first one, eq(0) )
			// Else it would go like: hide,show,hide,show for each toggle link.
			toggleElement( $that, 'collapse', $toggleLink.eq(0), /* instantHide = */ true );
			$toggleLink.eq(0).click();
		}
	} );
};
} )( jQuery, mediaWiki );
