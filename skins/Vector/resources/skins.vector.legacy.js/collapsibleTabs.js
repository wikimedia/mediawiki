/**
 * This adds behaviour to Vector's tabs in the bottom right so that at smaller
 * displays they collapse under the more menu.
 */

/** @interface CollapsibleTabsOptions */
function init() {
	/** @type {boolean|undefined} */ let boundEvent;
	const isRTL = document.documentElement.dir === 'rtl';
	const rAF = window.requestAnimationFrame || setTimeout;

	// Mark the tabs which can be collapsed under the more menu
	// eslint-disable-next-line no-jquery/no-global-selector
	$( '#p-views li' )
		.not( '#ca-watch, #ca-unwatch' ).addClass( 'collapsible' );

	$.fn.collapsibleTabs = function ( options ) {
		// Merge options into the defaults
		const settings = $.extend( {}, $.collapsibleTabs.defaults, options );

		// return if the function is called on an empty jquery object
		if ( !this.length ) {
			return this;
		}

		this.each( function () {
			const $el = $( this );
			// add the element to our array of collapsible managers
			$.collapsibleTabs.instances.push( $el );
			// attach the settings to the elements
			$el.data( 'collapsibleTabsSettings', settings );
			// attach data to our collapsible elements
			$el.children( settings.collapsible ).each( function () {
				$.collapsibleTabs.addData( $( this ) );
			} );
		} );

		// if we haven't already bound our resize handler, bind it now
		if ( !boundEvent ) {
			boundEvent = true;
			$( window ).on( 'resize', mw.util.debounce( function () {
				rAF( $.collapsibleTabs.handleResize );
			}, 10 ) );
		}

		// call our resize handler to setup the page
		rAF( $.collapsibleTabs.handleResize );
		// When adding new links, a resize should be triggered (T139830).
		mw.hook( 'util.addPortletLink' ).add( $.collapsibleTabs.handleResize );
		return this;
	};
	$.collapsibleTabs = {
		instances: [],
		defaults: {
			expandedContainer: '#p-views ul',
			collapsedContainer: '#p-cactions ul',
			collapsible: 'li.collapsible',
			shifting: false,
			expandedWidth: 0,
			expandCondition: function ( eleWidth ) {
				// If there are at least eleWidth + 1 pixels of free space, expand.
				// We add 1 because .width() will truncate fractional values but .offset() will not.
				return $.collapsibleTabs.calculateTabDistance() >= eleWidth + 1;
			},
			collapseCondition: function () {
				// If there's an overlap, collapse.
				return $.collapsibleTabs.calculateTabDistance() < 0;
			}
		},
		addData: function ( $collapsible ) {
			const settings = $collapsible.parent().data( 'collapsibleTabsSettings' );
			if ( settings ) {
				$collapsible.data( 'collapsibleTabsSettings', {
					expandedContainer: settings.expandedContainer,
					collapsedContainer: settings.collapsedContainer,
					expandedWidth: $collapsible.width()
				} );
			}
		},
		getSettings: function ( $collapsible ) {
			let settings = $collapsible.data( 'collapsibleTabsSettings' );
			if ( !settings ) {
				$.collapsibleTabs.addData( $collapsible );
				settings = $collapsible.data( 'collapsibleTabsSettings' );
			}
			// it's possible for getSettings to return undefined
			// if no data attributes have been set
			// see T177108#6310908.
			// In particular, a gadget may have added a collapsible link to the list:
			// e.g.
			// $('<li class="collapsible">my link</a>').appendTo( $('#p-cactions ul') )
			return settings || {};
		},
		handleResize: function () {
			$.collapsibleTabs.instances.forEach( function ( $el ) {
				const data = $.collapsibleTabs.getSettings( $el );

				if ( $.isEmptyObject( data ) || data.shifting ) {
					return;
				}

				// if the two navigations are colliding
				if ( $el.children( data.collapsible ).length && data.collapseCondition() ) {
					/**
					 * Fired before tabs are moved to "collapsedContainer".
					 *
					 * @event beforeTabCollapse
					 * @memberof jQuery.plugin.collapsibleTabs
					 */
					$el.trigger( 'beforeTabCollapse' );
					// Move the element to the dropdown menu.
					$.collapsibleTabs.moveToCollapsed( $el.children( data.collapsible ).last() );
				}

				const $tab = $( data.collapsedContainer ).children( data.collapsible ).first();
				// if there are still moveable items in the dropdown menu,
				// and there is sufficient space to place them in the tab container
				if (
					$( data.collapsedContainer + ' ' + data.collapsible ).length &&
					data.expandCondition(
						$.collapsibleTabs.getSettings( $tab ).expandedWidth
					)
				) {
					/**
					 * Fired before tabs are moved to "expandedContainer".
					 *
					 * @event beforeTabExpand
					 * @memberof jQuery.plugin.collapsibleTabs
					 */
					$el.trigger( 'beforeTabExpand' );
					$.collapsibleTabs.moveToExpanded( $tab );
				}
			} );
		},
		moveToCollapsed: function ( $moving ) {
			const outerData = $.collapsibleTabs.getSettings( $moving );
			if ( !outerData ) {
				return;
			}
			const collapsedContainerSettings = $.collapsibleTabs.getSettings(
				$( outerData.expandedContainer )
			);
			if ( !collapsedContainerSettings ) {
				return;
			}
			collapsedContainerSettings.shifting = true;

			// Remove the element from where it's at and put it in the dropdown menu
			const target = outerData.collapsedContainer;
			// eslint-disable-next-line no-jquery/no-animate
			$moving.css( 'position', 'relative' )
				.css( ( isRTL ? 'left' : 'right' ), 0 )
				.animate( { width: '1px' }, 'normal', function () {
					$( this ).hide();
					// add the placeholder
					$( '<span>' ).addClass( 'placeholder' ).css( 'display', 'none' ).insertAfter( this );
					$( this ).detach().prependTo( target ).data( 'collapsibleTabsSettings', outerData );
					$( this ).attr( 'style', 'display: list-item;' );
					collapsedContainerSettings.shifting = false;
					rAF( $.collapsibleTabs.handleResize );
				} );
		},
		moveToExpanded: function ( $moving ) {
			const data = $.collapsibleTabs.getSettings( $moving );
			if ( !data ) {
				return;
			}
			const expandedContainerSettings =
				$.collapsibleTabs.getSettings( $( data.expandedContainer ) );
			if ( !expandedContainerSettings ) {
				return;
			}
			expandedContainerSettings.shifting = true;

			// grab the next appearing placeholder so we can use it for replacing
			const $target = $( data.expandedContainer ).find( 'span.placeholder' ).first();
			const expandedWidth = data.expandedWidth;
			$moving.css( 'position', 'relative' ).css( ( isRTL ? 'right' : 'left' ), 0 ).css( 'width', '1px' );
			$target.replaceWith(
				// eslint-disable-next-line no-jquery/no-animate
				$moving
					.detach()
					.css( 'width', '1px' )
					.data( 'collapsibleTabsSettings', data )
					.animate( { width: expandedWidth + 'px' }, 'normal', function () {
						$( this ).attr( 'style', 'display: block;' );
						rAF( function () {
							// Update the 'expandedWidth' in case someone was brazen enough to
							// change the tab's contents after the page load *gasp* (T71729). This
							// doesn't prevent a tab from collapsing back and forth once, but at
							// least it won't continue to do that forever.
							data.expandedWidth = $moving.width() || 0;
							$moving.data( 'collapsibleTabsSettings', data );
							expandedContainerSettings.shifting = false;
							$.collapsibleTabs.handleResize();
						} );
					} )
			);
		},
		/**
		 * Get the amount of horizontal distance between the two tabs groups in pixels.
		 *
		 * Uses `#left-navigation` and `#right-navigation`. If negative, this
		 * means that the tabs overlap, and the value is the width of overlapping
		 * parts.
		 *
		 * Used in default `expandCondition` and `collapseCondition` options.
		 *
		 * @return {number} distance/overlap in pixels
		 */
		calculateTabDistance: function () {
			let leftTab, rightTab, leftEnd, rightStart;

			// In RTL, #right-navigation is actually on the left and vice versa.
			// Hooray for descriptive naming.
			if ( !isRTL ) {
				leftTab = document.getElementById( 'left-navigation' );
				rightTab = document.getElementById( 'right-navigation' );
			} else {
				leftTab = document.getElementById( 'right-navigation' );
				rightTab = document.getElementById( 'left-navigation' );
			}
			if ( leftTab && rightTab ) {
				leftEnd = leftTab.getBoundingClientRect().right;
				rightStart = rightTab.getBoundingClientRect().left;
				return rightStart - leftEnd;
			}
			return 0;
		}
	};
}

module.exports = Object.freeze( { init: init } );
