/*
 * Collapsible tabs jQuery Plugin
 */
jQuery.fn.collapsibleTabs = function( options ) {
	// return if the function is called on an empty jquery object
	if( !this.length ) return this;
	//merge options into the defaults
	var $settings = $.extend( {}, $.collapsibleTabs.defaults, options );

	this.each( function() {
		var $this = $( this );
		// add the element to our array of collapsible managers
		$.collapsibleTabs.instances = ( $.collapsibleTabs.instances.length == 0 ?
			$this : $.collapsibleTabs.instances.add( $this ) );
		// attach the settings to the elements
		$this.data( 'collapsibleTabsSettings', $settings );
		// attach data to our collapsible elements
		$this.children( $settings.collapsible ).each( function() {
			$.collapsibleTabs.addData( $( this ) );
		} );
	} );
	
	// if we haven't already bound our resize hanlder, bind it now
	if( !$.collapsibleTabs.boundEvent ) {
		$( window )
			.delayedBind( '500', 'resize', function( ) { $.collapsibleTabs.handleResize(); } );
	}
	// call our resize handler to setup the page
	$.collapsibleTabs.handleResize();
	return this;
};
jQuery.collapsibleTabs = {
	instances: [],
	boundEvent: null,
	defaults: {
		expandedContainer: '#p-views ul',
		collapsedContainer: '#p-cactions ul',
		collapsible: 'li.collapsible',
		shifting: false,
		expandCondition: function( eleWidth ) {
			return ( $( '#left-navigation' ).position().left + $( '#left-navigation' ).width() )
				< ( $( '#right-navigation' ).position().left - eleWidth );
		},
		collapseCondition: function() {
			return ( $( '#left-navigation' ).position().left + $( '#left-navigation' ).width() )
				> $( '#right-navigation' ).position().left;
		}
	},
	addData: function( $collapsible ) {
		var $settings = $collapsible.parent().data( 'collapsibleTabsSettings' );
		$collapsible.data( 'collapsibleTabsSettings', {
			'expandedContainer': $settings.expandedContainer,
			'collapsedContainer': $settings.collapsedContainer,
			'expandedWidth': $collapsible.width(),
			'prevElement': $collapsible.prev()
		} );
	},
	getSettings: function( $collapsible ) {
		var $settings = $collapsible.data( 'collapsibleTabsSettings' );
		if ( typeof $settings == 'undefined' ) {
			$.collapsibleTabs.addData( $collapsible );
			$settings = $collapsible.data( 'collapsibleTabsSettings' );
		}
		return $settings;
	},
	handleResize: function( e ){
		$.collapsibleTabs.instances.each( function() {
			var $this = $( this ), data = $.collapsibleTabs.getSettings( $this );
			if( data.shifting ) return;

			// if the two navigations are colliding
			if( $this.children( data.collapsible ).length > 0 && data.collapseCondition() ) {
				
				$this.trigger( "beforeTabCollapse" );
				// move the element to the dropdown menu
				$.collapsibleTabs.moveToCollapsed( $this.children( data.collapsible + ':last' ) );
			}

			// if there are still moveable items in the dropdown menu,
			// and there is sufficient space to place them in the tab container
			if( $( data.collapsedContainer + ' ' + data.collapsible ).length > 0
					&& data.expandCondition( $.collapsibleTabs.getSettings( $( data.collapsedContainer ).children(
							data.collapsible+":first" ) ).expandedWidth ) ) {
				//move the element from the dropdown to the tab
				$this.trigger( "beforeTabExpand" );
				$.collapsibleTabs
					.moveToExpanded( data.collapsedContainer + " " + data.collapsible + ':first' );
			}
		});
	},
	moveToCollapsed: function( ele ) {
		var $moving = $( ele );
		var data = $.collapsibleTabs.getSettings( $moving );
		var dataExp = $.collapsibleTabs.getSettings( data.expandedContainer );
		dataExp.shifting = true;
		$moving
			.remove()
			.prependTo( data.collapsedContainer )
			.data( 'collapsibleTabsSettings', data );
		dataExp.shifting = false;
		$.collapsibleTabs.handleResize();
	},
	moveToExpanded: function( ele ) {
		var $moving = $( ele );
		var data = $.collapsibleTabs.getSettings( $moving );
		var dataExp = $.collapsibleTabs.getSettings( data.expandedContainer );
		dataExp.shifting = true;
		// remove this element from where it's at and put it in the dropdown menu
		$moving.remove().insertAfter( data.prevElement ).data( 'collapsibleTabsSettings', data );
		dataExp.shifting = false;
		$.collapsibleTabs.handleResize();
	}
};