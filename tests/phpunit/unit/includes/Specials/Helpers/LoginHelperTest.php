<?php

namespace MediaWiki\Tests\Unit\Specials\Helpers;

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\Helpers\LoginHelper;
use MediaWikiUnitTestCase;

/**
 * @covers MediaWiki\Specials\Helpers\LoginHelper
 */
class LoginHelperTest extends MediaWikiUnitTestCase {

	public function testGetDisplayModeWithoutParameter() {
		$context = $this->createTestContext();
		$helper = new LoginHelper( $context );

		$this->assertSame( 'page', $helper->getDisplayMode() );
	}

	public function testGetDisplayModeWithPageParameter() {
		$context = $this->createTestContext( [ 'display' => 'page' ] );
		$helper = new LoginHelper( $context );

		$this->assertSame( 'page', $helper->getDisplayMode() );
	}

	public function testGetDisplayModeWithPopupParameter() {
		$context = $this->createTestContext( [ 'display' => 'popup' ] );
		$helper = new LoginHelper( $context );

		$this->assertSame( 'popup', $helper->getDisplayMode() );
	}

	public function testGetDisplayModeWithInvalidParameter() {
		$context = $this->createTestContext( [ 'display' => 'invalid' ] );
		$helper = new LoginHelper( $context );

		$this->assertSame( 'page', $helper->getDisplayMode() );
	}

	public function testIsDisplayModePopupReturnsTrue() {
		$context = $this->createTestContext( [ 'display' => 'popup' ] );
		$helper = new LoginHelper( $context );

		$this->assertTrue( $helper->isDisplayModePopup() );
	}

	public function testIsDisplayModePopupReturnsFalse() {
		$context = $this->createTestContext( [ 'display' => 'page' ] );
		$helper = new LoginHelper( $context );

		$this->assertFalse( $helper->isDisplayModePopup() );
	}

	private function createTestContext( array $requestData = [] ): RequestContext {
		$context = new RequestContext();
		$request = new FauxRequest( $requestData );
		$context->setRequest( $request );

		return $context;
	}
}
