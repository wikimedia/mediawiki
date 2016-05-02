/**
 * jQuery makeCollapsible
 *
 * Dual licensed:
 * - CC BY 3.0 <http://creativecommons.org/licenses/by/3.0>
 * - GPL2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 *
 * @class jQuery.plugin.makeCollapsible
 */
( function ( $, mw ) {

	/**
	 * Handler for a click on a collapsible toggler.
	 *
	 * @private
	 * @param {jQuery} $collapsible
	 * @param {string} action The action this function will take ('expand' or 'collapse').
	 * @param {jQuery|null} [$defaultToggle]
	 * @param {Object|undefined} [options]
	 */
	function toggleElement( $collapsible, action, $defaultToggle, options ) {
		var $collapsibleContent, $containers, hookCallback;
		options = options || {};

		// Validate parameters

		// $collapsible must be an instance of jQuery
		if ( !$collapsible.jquery ) {
			return;
		}
		if ( action !== 'expand' && action !== 'collapse' ) {
			// action must be string with 'expand' or 'collapse'
			return;
		}
		if ( $defaultToggle === undefined ) {
			$defaultToggle = null;
		}
		if ( $defaultToggle !== null && !$defaultToggle.jquery ) {
			// is optional (may be undefined), but if defined it must be an instance of jQuery.
			// If it's not, abort right away.
			// After this $defaultToggle is either null or a valid jQuery instance.
			return;
		}

		// Trigger a custom event to allow callers to hook to the collapsing/expanding,
		// allowing the module to be testable, and making it possible to
		// e.g. implement persistence via cookies
		$collapsible.trigger( action === 'expand' ? 'beforeExpand.mw-collapsible' : 'beforeCollapse.mw-collapsible' );
		hookCallback = function () {
			$collapsible.trigger( action === 'expand' ? 'afterExpand.mw-collapsible' : 'afterCollapse.mw-collapsible' );
		};

		// Handle different kinds of elements

		if ( !options.plainMode && $collapsible.is( 'table' ) ) {
			// Tables
			// If there is a caption, hide all rows; otherwise, only hide body rows
			if ( $collapsible.find( '> caption' ).length ) {
				$containers = $collapsible.find( '> * > tr' );
			} else {
				$containers = $collapsible.find( '> tbody > tr' );
			}
			if ( $defaultToggle ) {
				// Exclude table row containing togglelink
				$containers = $containers.not( $defaultToggle.closest( 'tr' ) );
			}

			if ( action === 'collapse' ) {
				// Hide all table rows of this table
				// Slide doesn't work with tables, but fade does as of jQuery 1.1.3
				// http://stackoverflow.com/questions/467336#920480
				if ( options.instantHide ) {
					$containers.hide();
					hookCallback();
				} else {
					$containers.stop( true, true ).fadeOut().promise().done( hookCallback );
				}
			} else {
				$containers.stop( true, true ).fadeIn().promise().done( hookCallback );
			}

		} else if ( !options.plainMode && ( $collapsible.is( 'ul' ) || $collapsible.is( 'ol' ) ) ) {
			// Lists
			$containers = $collapsible.find( '> li' );
			if ( $defaultToggle ) {
				// Exclude list-item containing togglelink
				$containers = $containers.not( $defaultToggle.parent() );
			}

			if ( action === 'collapse' ) {
				if ( options.instantHide ) {
					$containers.hide();
					hookCallback();
				} else {
					$containers.stop( true, true ).slideUp().promise().done( hookCallback );
				}
			} else {
				$containers.stop( true, true ).slideDown().promise().done( hookCallback );
			}

		} else {
			// Everything else: <div>, <p> etc.
			$collapsibleContent = $collapsible.find( '> .mw-collapsible-content' );

			// If a collapsible-content is defined, act on it
			if ( !options.plainMode && $collapsibleContent.length ) {
				if ( action === 'collapse' ) {
					if ( options.instantHide ) {
						$collapsibleContent.hide();
						hookCallback();
					} else {
						$collapsibleContent.slideUp().promise().done( hookCallback );
					}
				} else {
					$collapsibleContent.slideDown().promise().done( hookCallback );
				}

			// Otherwise assume this is a customcollapse with a remote toggle
			// .. and there is no collapsible-content because the entire element should be toggled
			} else {
				if ( action === 'collapse' ) {
					if ( options.instantHide ) {
						$collapsible.hide();
						hookCallback();
					} else {
						if ( $collapsible.is( 'tr' ) || $collapsible.is( 'td' ) || $collapsible.is( 'th' ) ) {
							$collapsible.fadeOut().promise().done( hookCallback );
						} else {
							$collapsible.slideUp().promise().done( hookCallback );
						}
					}
				} else {
					if ( $collapsible.is( 'tr' ) || $collapsible.is( 'td' ) || $collapsible.is( 'th' ) ) {
						$collapsible.fadeIn().promise().done( hookCallback );
					} else {
						$collapsible.slideDown().promise().done( hookCallback );
					}
				}
			}
		}
	}

	/**
	 * Handle clicking/keypressing on the collapsible element toggle and other
	 * situations where a collapsible element is toggled (e.g. the initial
	 * toggle for collapsed ones).
	 *
	 * @private
	 * @param {jQuery} $toggle the clickable toggle itself
	 * @param {jQuery} $collapsible the collapsible element
	 * @param {jQuery.Event|null} e either the event or null if unavailable
	 * @param {Object|undefined} options
	 */
	function togglingHandler( $toggle, $collapsible, e, options ) {
		var wasCollapsed, $textContainer, collapseText, expandText;
		options = options || {};

		if ( e ) {
			if (
				e.type === 'click' &&
				options.linksPassthru &&
				$.nodeName( e.target, 'a' ) &&
				$( e.target ).attr( 'href' ) &&
				$( e.target ).attr( 'href' ) !== '#'
			) {
				// Don't fire if a link with href !== '#' was clicked, if requested  (for premade togglers by default)
				return;
			} else if ( e.type === 'keypress' && e.which !== 13 && e.which !== 32 ) {
				// Only handle keypresses on the "Enter" or "Space" keys
				return;
			} else {
				e.preventDefault();
				e.stopPropagation();
			}
		}

		// This allows the element to be hidden on initial toggle without fiddling with the class
		if ( options.wasCollapsed !== undefined ) {
			wasCollapsed = options.wasCollapsed;
		} else {
			wasCollapsed = $collapsible.hasClass( 'mw-collapsed' );
		}

		// Toggle the state of the collapsible element (that is, expand or collapse)
		$collapsible.toggleClass( 'mw-collapsed', !wasCollapsed );

		// Toggle the mw-collapsible-toggle classes, if requested (for default and premade togglers by default)
		if ( options.toggleClasses ) {
			$toggle
				.toggleClass( 'mw-collapsible-toggle-collapsed', !wasCollapsed )
				.toggleClass( 'mw-collapsible-toggle-expanded', wasCollapsed );
		}

		// Toggle the text ("Show"/"Hide"), if requested (for default togglers by default)
		if ( options.toggleText ) {
			collapseText = options.toggleText.collapseText;
			expandText = options.toggleText.expandText;

			$textContainer = $toggle.find( '> a' );
			if ( !$textContainer.length ) {
				$textContainer = $toggle;
			}
			$textContainer.text( wasCollapsed ? collapseText : expandText );
		}

		// And finally toggle the element state itself
		toggleElement( $collapsible, wasCollapsed ? 'expand' : 'collapse', $toggle, options );
	}

	/**
	 * Enable collapsible-functionality on all elements in the collection.
	 *
	 * - Will prevent binding twice to the same element.
	 * - Initial state is expanded by default, this can be overridden by adding class
	 *   "mw-collapsed" to the "mw-collapsible" element.
	 * - Elements made collapsible have jQuery data "mw-made-collapsible" set to true.
	 * - The inner content is wrapped in a "div.mw-collapsible-content" (except for tables and lists).
	 *
	 * @param {Object} [options]
	 * @param {string} [options.collapseText] Text used for the toggler, when clicking it would
	 *   collapse the element. Default: the 'data-collapsetext' attribute of the
	 *   collapsible element or the content of 'collapsible-collapse' message.
	 * @param {string} [options.expandText] Text used for the toggler, when clicking it would
	 *   expand the element. Default: the 'data-expandtext' attribute of the
	 *   collapsible element or the content of 'collapsible-expand' message.
	 * @param {boolean} [options.collapsed] Whether to collapse immediately. By default
	 *   collapse only if the elements has the 'mw-collapsible' class.
	 * @param {jQuery} [options.$customTogglers] Elements to be used as togglers
	 *   for this collapsible element. By default, if the collapsible element
	 *   has an id attribute like 'mw-customcollapsible-XXX', elements with a
	 *   *class* of 'mw-customtoggle-XXX' are made togglers for it.
	 * @param {boolean} [options.plainMode=false] Whether to use a "plain mode" when making the
	 *   element collapsible - that is, hide entire tables and lists (instead
	 *   of hiding only all rows but first of tables, and hiding each list
	 *   item separately for lists) and don't wrap other elements in
	 *   div.mw-collapsible-content. May only be used with custom togglers.
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.makeCollapsible = function ( options ) {
		options = options || {};

		this.each( function () {
			var $collapsible, collapseText, expandText, $caption, $toggle, actionHandler, buildDefaultToggleLink,
				premadeToggleHandler, $toggleLink, $firstItem, collapsibleId, $customTogglers, firstval;

			// Ensure class "mw-collapsible" is present in case .makeCollapsible()
			// is called on element(s) that don't have it yet.
			$collapsible = $( this ).addClass( 'mw-collapsible' );

			// Return if it has been enabled already.
			if ( $collapsible.data( 'mw-made-collapsible' ) ) {
				return;
			} else {
				$collapsible.data( 'mw-made-collapsible', true );
			}

			// Use custom text or default?
			collapseText = options.collapseText || $collapsible.attr( 'data-collapsetext' ) || mw.msg( 'collapsible-collapse' );
			expandText = options.expandText || $collapsible.attr( 'data-expandtext' ) || mw.msg( 'collapsible-expand' );

			// Default click/keypress handler and toggle link to use when none is present
			actionHandler = function ( e, opts ) {
				var defaultOpts = {
					toggleClasses: true,
					toggleText: { collapseText: collapseText, expandText: expandText }
				};
				opts = $.extend( defaultOpts, options, opts );
				togglingHandler( $( this ), $collapsible, e, opts );
			};
			// Default toggle link. Only build it when needed to avoid jQuery memory leaks (event data).
			buildDefaultToggleLink = function () {
				return $( '<a href="#"></a>' )
					.text( collapseText )
					.wrap( '<span class="mw-collapsible-toggle"></span>' )
						.parent()
						.prepend( '<span class="mw-collapsible-bracket">[</span>' )
						.append( '<span class="mw-collapsible-bracket">]</span>' )
						.on( 'click.mw-collapsible keypress.mw-collapsible', actionHandler );
			};

			// Default handler for clicking on premade toggles
			premadeToggleHandler = function ( e, opts ) {
				var defaultOpts = { toggleClasses: true, linksPassthru: true };
				opts = $.extend( defaultOpts, options, opts );
				togglingHandler( $( this ), $collapsible, e, opts );
			};

			// Check if this element has a custom position for the toggle link
			// (ie. outside the container or deeper inside the tree)
			if ( options.$customTogglers ) {
				$customTogglers = $( options.$customTogglers );
			} else {
				collapsibleId = $collapsible.attr( 'id' ) || '';
				if ( collapsibleId.indexOf( 'mw-customcollapsible-' ) === 0 ) {
					$customTogglers = $( '.' + collapsibleId.replace( 'mw-customcollapsible', 'mw-customtoggle' ) )
						.addClass( 'mw-customtoggle' );
				}
			}

			// Add event handlers to custom togglers or create our own ones
			if ( $customTogglers && $customTogglers.length ) {
				actionHandler = function ( e, opts ) {
					var defaultOpts = {};
					opts = $.extend( defaultOpts, options, opts );
					togglingHandler( $( this ), $collapsible, e, opts );
				};

				$toggleLink = $customTogglers
					.on( 'click.mw-collapsible keypress.mw-collapsible', actionHandler )
					.prop( 'tabIndex', 0 );

			} else {
				// If this is not a custom case, do the default: wrap the
				// contents and add the toggle link. Different elements are
				// treated differently.
				if ( $collapsible.is( 'table' ) ) {

					// If the table has a caption, collapse to the caption
					// as opposed to the first row
					$caption = $collapsible.find( '> caption' );
					if ( $caption.length ) {
						$toggle = $caption.find( '> .mw-collapsible-toggle' );

						// If there is no toggle link, add it to the end of the caption
						if ( !$toggle.length ) {
							$toggleLink = buildDefaultToggleLink().appendTo( $caption );
						} else {
							actionHandler = premadeToggleHandler;
							$toggleLink = $toggle.on( 'click.mw-collapsible keypress.mw-collapsible', actionHandler )
								.prop( 'tabIndex', 0 );
						}
					} else {
						// The toggle-link will be in one of the cells (td or th) of the first row
						$firstItem = $collapsible.find( 'tr:first th, tr:first td' );
						$toggle = $firstItem.find( '> .mw-collapsible-toggle' );

						// If theres no toggle link, add it to the last cell
						if ( !$toggle.length ) {
							$toggleLink = buildDefaultToggleLink().prependTo( $firstItem.eq( -1 ) );
						} else {
							actionHandler = premadeToggleHandler;
							$toggleLink = $toggle.on( 'click.mw-collapsible keypress.mw-collapsible', actionHandler )
								.prop( 'tabIndex', 0 );
						}
					}

				} else if ( $collapsible.is( 'ul' ) || $collapsible.is( 'ol' ) ) {
					// The toggle-link will be in the first list-item
					$firstItem = $collapsible.find( 'li:first' );
					$toggle = $firstItem.find( '> .mw-collapsible-toggle' );

					// If theres no toggle link, add it
					if ( !$toggle.length ) {
						// Make sure the numeral order doesn't get messed up, force the first (soon to be second) item
						// to be "1". Except if the value-attribute is already used.
						// If no value was set WebKit returns "", Mozilla returns '-1', others return 0, null or undefined.
						firstval = $firstItem.prop( 'value' );
						if ( firstval === undefined || !firstval || firstval === '-1' || firstval === -1 ) {
							$firstItem.prop( 'value', '1' );
						}
						$toggleLink = buildDefaultToggleLink();
						$toggleLink.wrap( '<li class="mw-collapsible-toggle-li"></li>' ).parent().prependTo( $collapsible );
					} else {
						actionHandler = premadeToggleHandler;
						$toggleLink = $toggle.on( 'click.mw-collapsible keypress.mw-collapsible', actionHandler )
							.prop( 'tabIndex', 0 );
					}

				} else { // <div>, <p> etc.

					// The toggle-link will be the first child of the element
					$toggle = $collapsible.find( '> .mw-collapsible-toggle' );

					// If a direct child .content-wrapper does not exists, create it
					if ( !$collapsible.find( '> .mw-collapsible-content' ).length ) {
						$collapsible.wrapInner( '<div class="mw-collapsible-content"></div>' );
					}

					// If theres no toggle link, add it
					if ( !$toggle.length ) {
						$toggleLink = buildDefaultToggleLink().prependTo( $collapsible );
					} else {
						actionHandler = premadeToggleHandler;
						$toggleLink = $toggle.on( 'click.mw-collapsible keypress.mw-collapsible', actionHandler )
							.prop( 'tabIndex', 0 );
					}
				}
			}

			$( this ).data( 'mw-collapsible', {
				collapse: function () {
					actionHandler.call( $toggleLink.get( 0 ), null, { instantHide: true, wasCollapsed: false } );
				},
				expand: function () {
					actionHandler.call( $toggleLink.get( 0 ), null, { instantHide: true, wasCollapsed: true } );
				},
				toggle: function () {
					actionHandler.call( $toggleLink.get( 0 ), null, null );
				}
			} );

			// Initial state
			if ( options.collapsed || $collapsible.hasClass( 'mw-collapsed' ) ) {
				// One toggler can hook to multiple elements, and one element can have
				// multiple togglers. This is the sanest way to handle that.
				actionHandler.call( $toggleLink.get( 0 ), null, { instantHide: true, wasCollapsed: false } );
			}

		} );

		/**
		 * Fired after collapsible content has been initialized
		 *
		 * This gives an option to modify the collapsible behavior.
		 *
		 * @event wikipage_collapsibleContent
		 * @member mw.hook
		 * @param {jQuery} $content All the elements that have been made collapsible
		 */
		mw.hook( 'wikipage.collapsibleContent' ).fire( this );

		return this;
	};

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.makeCollapsible
	 */

}( jQuery, mediaWiki ) );
