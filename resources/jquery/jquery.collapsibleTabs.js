/**
 * Collapsible tabs jQuery Plugin
 */
( function ( $ ) {
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

		// if we haven't already bound our resize hanlder, bind it now
		if ( !$.collapsibleTabs.boundEvent ) {
			$( window )
				.delayedBind( '500', 'resize', function ( ) {
					$.collapsibleTabs.handleResize();
				} );
		}
		// call our resize handler to setup the page
		$.collapsibleTabs.handleResize();
		return this;
	};
	$.collapsibleTabs = {
		instances: [],
		boundEvent: null,
		defaults: {
			expandedContainer: '#p-views ul',
			collapsedContainer: '#p-cactions ul',
			collapsible: 'li.collapsible',
			shifting: false,
			expandCondition: function ( eleWidth ) {
				return ( $( '#left-navigation' ).position().left + $( '#left-navigation' ).width() )
					< ( $( '#right-navigation' ).position().left - eleWidth );
			},
			collapseCondition: function () {
				return ( $( '#left-navigation' ).position().left + $( '#left-navigation' ).width() )
					> $( '#right-navigation' ).position().left;
			}
		},
		addData: function ( $collapsible ) {
			var $settings = $collapsible.parent().data( 'collapsibleTabsSettings' );
			if ( $settings !== null ) {
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
			if ( $settings === undefined ) {
				$.collapsibleTabs.addData( $collapsible );
				$settings = $collapsible.data( 'collapsibleTabsSettings' );
			}
			return $settings;
		},
		handleResize: function ( e ) {
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
				if ( $( data.collapsedContainer + ' ' + data.collapsible ).length > 0
						&& data.expandCondition( $.collapsibleTabs.getSettings( $( data.collapsedContainer ).children(
								data.collapsible + ':first' ) ).expandedWidth ) ) {
					//move the element from the dropdown to the tab
					$el.trigger( 'beforeTabExpand' );
					$.collapsibleTabs
						.moveToExpanded( data.collapsedContainer + ' ' + data.collapsible + ':first' );
				}
			});
		},
		moveToCollapsed: function ( ele ) {
			var $moving = $( ele ),
				data = $.collapsibleTabs.getSettings( $moving ),
				dataExp = $.collapsibleTabs.getSettings( data.expandedContainer );
			dataExp.shifting = true;
			$moving
				.detach()
				.prependTo( data.collapsedContainer )
				.data( 'collapsibleTabsSettings', data );
			dataExp.shifting = false;
			$.collapsibleTabs.handleResize();
		},
		moveToExpanded: function ( ele ) {
			var $moving = $( ele ),
				data = $.collapsibleTabs.getSettings( $moving ),
				dataExp = $.collapsibleTabs.getSettings( data.expandedContainer );
			dataExp.shifting = true;
			// remove this element from where it's at and put it in the dropdown menu
			$moving.detach().insertAfter( data.prevElement ).data( 'collapsibleTabsSettings', data );
			dataExp.shifting = false;
			$.collapsibleTabs.handleResize();
		}
	};

}( jQuery ) );
