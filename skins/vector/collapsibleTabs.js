/**
 * Collapsible tabs jQuery Plugin
 */
( function ( $ ) {
	var rtl = $( 'html' ).attr( 'dir' ) === 'rtl';
	$.fn.collapsibleTabs = function ( options ) {
		// return if the function is called on an empty jquery object
		if ( !this.length ) {
			return this;
		}
		// Merge options into the defaults
		var $settings = $.extend( {}, $.collapsibleTabs.defaults, options );

		this.each( function () {
			var $el = $( this );
			// add the element to our array of collapsible managers
			$.collapsibleTabs.instances = ( $.collapsibleTabs.instances.length === 0 ?
				$el : $.collapsibleTabs.instances.add( $el ) );
			// attach the settings to the elements
			$el.data( 'collapsibleTabsSettings', $settings );
			// attach data to our collapsible elements
			$el.children( $settings.collapsible ).each( function () {
				$.collapsibleTabs.addData( $( this ) );
			} );
		} );

		// if we haven't already bound our resize handler, bind it now
		if ( !$.collapsibleTabs.boundEvent ) {
			$( window ).on( 'resize', $.debounce( 500, function () {
				$.collapsibleTabs.handleResize();
			} ) );
			$.collapsibleTabs.boundEvent = true;
		}

		// call our resize handler to setup the page
		$.collapsibleTabs.handleResize();
		return this;
	};
	/**
	 * Returns the amount of horizontal distance between the two tabs groups
	 * (#left-navigation and #right-navigation), in pixels. If negative, this
	 * means that the tabs overlap, and the value is the width of overlapping
	 * parts.
	 *
	 * Used in default expandCondition and collapseCondition.
	 *
	 * @return {Numeric} distance/overlap in pixels
	 */
	function calculateTabDistance() {
		var $leftTab, $rightTab, leftEnd, rightStart;

		// In RTL, #right-navigation is actually on the left and vice versa.
		// Hooray for descriptive naming.
		if ( !rtl ) {
			$leftTab = $( '#left-navigation' );
			$rightTab = $( '#right-navigation' );
		} else {
			$leftTab = $( '#right-navigation' );
			$rightTab = $( '#left-navigation' );
		}

		leftEnd = $leftTab.offset().left + $leftTab.width();
		rightStart = $rightTab.offset().left;

		return rightStart - leftEnd;
	}
	$.collapsibleTabs = {
		instances: [],
		boundEvent: null,
		defaults: {
			expandedContainer: '#p-views ul',
			collapsedContainer: '#p-cactions ul',
			collapsible: 'li.collapsible',
			shifting: false,
			expandCondition: function ( eleWidth ) {
				// If there are at least eleWidth + 1 pixels of free space, expand.
				// We add 1 because .width() will truncate fractional values
				// but .offset() will not.
				return calculateTabDistance() >= (eleWidth + 1);
			},
			collapseCondition: function () {
				// If there's an overlap, collapse.
				return calculateTabDistance() < 0;
			}
		},
		addData: function ( $collapsible ) {
			var $settings = $collapsible.parent().data( 'collapsibleTabsSettings' );
			if ( $settings ) {
				$collapsible.data( 'collapsibleTabsSettings', {
					expandedContainer: $settings.expandedContainer,
					collapsedContainer: $settings.collapsedContainer,
					expandedWidth: $collapsible.width(),
					prevElement: $collapsible.prev()
				} );
			}
		},
		getSettings: function ( $collapsible ) {
			var $settings = $collapsible.data( 'collapsibleTabsSettings' );
			if ( !$settings ) {
				$.collapsibleTabs.addData( $collapsible );
				$settings = $collapsible.data( 'collapsibleTabsSettings' );
			}
			return $settings;
		},
		handleResize: function () {
			$.collapsibleTabs.instances.each( function () {
				var $el = $( this ),
					data = $.collapsibleTabs.getSettings( $el );

				if ( data.shifting ) {
					return;
				}

				// if the two navigations are colliding
				if ( $el.children( data.collapsible ).length > 0 && data.collapseCondition() ) {

					$el.trigger( 'beforeTabCollapse' );
					// move the element to the dropdown menu
					$.collapsibleTabs.moveToCollapsed( $el.children( data.collapsible + ':last' ) );
				}

				// if there are still moveable items in the dropdown menu,
				// and there is sufficient space to place them in the tab container
				if ( $( data.collapsedContainer + ' ' + data.collapsible ).length > 0 &&
						data.expandCondition( $.collapsibleTabs.getSettings( $( data.collapsedContainer ).children(
								data.collapsible + ':first' ) ).expandedWidth ) ) {
					//move the element from the dropdown to the tab
					$el.trigger( 'beforeTabExpand' );
					$.collapsibleTabs
						.moveToExpanded( data.collapsedContainer + ' ' + data.collapsible + ':first' );
				}
			} );
		},
		moveToCollapsed: function ( ele ) {
			var outerData, expContainerSettings, target,
				$moving = $( ele );

			outerData = $.collapsibleTabs.getSettings( $moving );
			if ( !outerData ) {
				return;
			}
			expContainerSettings = $.collapsibleTabs.getSettings( $( outerData.expandedContainer ) );
			if ( !expContainerSettings ) {
				return;
			}
			expContainerSettings.shifting = true;

			// Remove the element from where it's at and put it in the dropdown menu
			target = outerData.collapsedContainer;
			$moving.css( 'position', 'relative' )
				.css( ( rtl ? 'left' : 'right' ), 0 )
				.animate( { width: '1px' }, 'normal', function () {
					var data, expContainerSettings;
					$( this ).hide();
					// add the placeholder
					$( '<span class="placeholder" style="display: none;"></span>' ).insertAfter( this );
					$( this ).detach().prependTo( target ).data( 'collapsibleTabsSettings', outerData );
					$( this ).attr( 'style', 'display: list-item;' );
					data = $.collapsibleTabs.getSettings( $( ele ) );
					if ( data ) {
						expContainerSettings = $.collapsibleTabs.getSettings( $( data.expandedContainer ) );
						if ( expContainerSettings ) {
							expContainerSettings.shifting = false;
							$.collapsibleTabs.handleResize();
						}
					}
				} );
		},
		moveToExpanded: function ( ele ) {
			var data, expContainerSettings, $target, expandedWidth,
				$moving = $( ele );

			data = $.collapsibleTabs.getSettings( $moving );
			if ( !data ) {
				return;
			}
			expContainerSettings = $.collapsibleTabs.getSettings( $( data.expandedContainer ) );
			if ( !expContainerSettings ) {
				return;
			}
			expContainerSettings.shifting = true;

			// grab the next appearing placeholder so we can use it for replacing
			$target = $( data.expandedContainer ).find( 'span.placeholder:first' );
			expandedWidth = data.expandedWidth;
			$moving.css( 'position', 'relative' ).css( ( rtl ? 'right' : 'left' ), 0 ).css( 'width', '1px' );
			$target.replaceWith(
				$moving
				.detach()
				.css( 'width', '1px' )
				.data( 'collapsibleTabsSettings', data )
				.animate( { width: expandedWidth + 'px' }, 'normal', function () {
					$( this ).attr( 'style', 'display: block;' );
					var data, expContainerSettings;
					data = $.collapsibleTabs.getSettings( $( this ) );
					if ( data ) {
						expContainerSettings = $.collapsibleTabs.getSettings( $( data.expandedContainer ) );
						if ( expContainerSettings ) {
							expContainerSettings.shifting = false;
							$.collapsibleTabs.handleResize();
						}
					}
				} )
			);
		}
	};

}( jQuery ) );
