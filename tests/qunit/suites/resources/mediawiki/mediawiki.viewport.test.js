( function ( mw, $ ) {

	// Simulate square element with 20px long edges placed at (20, 20) on the page
	var
		DEFAULT_VIEWPORT = {
			top: 0,
			left: 0,
			right: 100,
			bottom: 100
		};

	QUnit.module( 'mediawiki.viewport', QUnit.newMwEnvironment( {
		setup: function () {
			this.el = $( '<div />' )
				.appendTo( '#qunit-fixture' )
				.width( 20 )
				.height( 20 )
				.offset( {
					top: 20,
					left: 20
				} )
				.get( 0 );
			this.sandbox.stub( mw.viewport, 'makeViewportFromWindow' )
				.returns( DEFAULT_VIEWPORT );
		}
	} ) );

	QUnit.test( 'isElementInViewport', function ( assert ) {
		var viewport = $.extend( {}, DEFAULT_VIEWPORT );
		assert.ok( mw.viewport.isElementInViewport( this.el, viewport ),
			'It should return true when the element is fully enclosed in the viewport' );

		viewport.right = 20;
		viewport.bottom = 20;
		assert.ok( mw.viewport.isElementInViewport( this.el, viewport ),
			'It should return true when only the top-left of the element is within the viewport' );

		viewport.top = 40;
		viewport.left = 40;
		viewport.right = 50;
		viewport.bottom = 50;
		assert.ok( mw.viewport.isElementInViewport( this.el, viewport ),
			'It should return true when only the bottom-right is within the viewport' );

		viewport.top = 30;
		viewport.left = 30;
		viewport.right = 35;
		viewport.bottom = 35;
		assert.ok( mw.viewport.isElementInViewport( this.el, viewport ),
			'It should return true when the element encapsulates the viewport' );

		viewport.top = 0;
		viewport.left = 0;
		viewport.right = 19;
		viewport.bottom = 19;
		assert.notOk( mw.viewport.isElementInViewport( this.el, viewport ),
			'It should return false when the element is not within the viewport' );

		assert.ok( mw.viewport.isElementInViewport( this.el ),
			'It should default to the window object if no viewport is given' );
	} );

	QUnit.test( 'isElementInViewport with scrolled page', function ( assert ) {
		var viewport = {
				top: 2000,
				left: 0,
				right: 1000,
				bottom: 2500
			},
			el = $( '<div />' )
				.appendTo( '#qunit-fixture' )
				.width( 20 )
				.height( 20 )
				.offset( {
					top: 2300,
					left: 20
				} )
				.get( 0 );
		window.scrollTo( viewport.left, viewport.top );
		assert.ok( mw.viewport.isElementInViewport( el, viewport ),
			'It should return true when the element is fully enclosed in the ' +
			'viewport even when the page is scrolled down' );
		window.scrollTo( 0, 0 );
	} );

	QUnit.test( 'isElementCloseToViewport', function ( assert ) {
		var
			viewport = {
				top: 90,
				left: 90,
				right: 100,
				bottom: 100
			},
			distantElement = $( '<div />' )
				.appendTo( '#qunit-fixture' )
				.width( 20 )
				.height( 20 )
				.offset( {
					top: 220,
					left: 20
				} )
				.get( 0 );

		assert.ok( mw.viewport.isElementCloseToViewport( this.el, 60, viewport ),
			'It should return true when the element is within the given threshold away' );
		assert.notOk( mw.viewport.isElementCloseToViewport( this.el, 20, viewport ),
			'It should return false when the element is further than the given threshold away' );
		assert.notOk( mw.viewport.isElementCloseToViewport( distantElement ),
			'It should default to a threshold of 50px and the window\'s viewport' );
	} );

}( mediaWiki, jQuery ) );
