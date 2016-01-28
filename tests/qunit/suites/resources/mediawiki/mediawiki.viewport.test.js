( function ( mw, $ ) {

	// Simulate square element with 20px long edges placed at (20, 20) on the page
	var
		el = {
			offset: function () {
				return {
					top: 20,
					left: 20
				};
			},
			height: function () {
				return 20;
			},
			width: function () {
				return 20;
			}
		},
		DEFAULT_VIEWPORT = {
			top: 0,
			left: 0,
			right: 100,
			bottom: 100
		};

	QUnit.module( 'mediawiki.viewport', QUnit.newMwEnvironment( {
		setup: function () {
			$.fn.updateTooltipAccessKeys.setTestMode( true );
			this.sandbox.stub( mw.viewport, 'makeViewportFromWindow' )
				.returns( DEFAULT_VIEWPORT );

		},
		teardown: function () {
			$.fn.updateTooltipAccessKeys.setTestMode( false );
		}
	} ) );

	QUnit.test( 'isElementInViewport', 6, function ( assert ) {
		var viewport = $.extend( {}, DEFAULT_VIEWPORT );
		assert.ok( mw.viewport.isElementInViewport( el, viewport ),
			'It should return true when the element is fully enclosed in the viewport' );

		viewport.right = 20;
		viewport.bottom = 20;
		assert.ok( mw.viewport.isElementInViewport( el, viewport ),
			'It should return true when only the top-left of the element is within the viewport' );

		viewport.top = 40;
		viewport.left = 40;
		viewport.right = 50;
		viewport.bottom = 50;
		assert.ok( mw.viewport.isElementInViewport( el, viewport ),
			'It should return true when only the bottom-right is within the viewport' );

		viewport.top = 30;
		viewport.left = 30;
		viewport.right = 35;
		viewport.bottom = 35;
		assert.ok( mw.viewport.isElementInViewport( el, viewport ),
			'It should return true when the element encapsulates the viewport' );

		viewport.top = 0;
		viewport.left = 0;
		viewport.right = 19;
		viewport.bottom = 19;
		assert.notOk( mw.viewport.isElementInViewport( el, viewport ),
			'It should return false when the element is not within the viewport' );

		assert.ok( mw.viewport.isElementInViewport( el ),
			'It should default to the window object if no viewport is given' );
	} );

	QUnit.test( 'isElementCloseToViewport', 3, function ( assert ) {
		var
			viewport = {
				top: 90,
				left: 90,
				right: 100,
				bottom: 100
			},
			distantElement = {
				offset: function () {
					return {
						top: 220,
						left: 20
					};
				},
				height: function () {
					return 20;
				},
				width: function () {
					return 20;
				}
			};

		assert.ok( mw.viewport.isElementCloseToViewport( el, 60, viewport ),
			'It should return true when the element is within the given threshold away' );
		assert.notOk( mw.viewport.isElementCloseToViewport( el, 20, viewport ),
			'It should return false when the element is further than the given threshold away' );
		assert.notOk( mw.viewport.isElementCloseToViewport( distantElement ),
			'It should default to a threshold of 50px and the window\'s viewport' );
	} );

}( mediaWiki, jQuery ) );
