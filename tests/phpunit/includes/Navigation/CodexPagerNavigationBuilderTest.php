<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Navigation\CodexPagerNavigationBuilder;
use MediaWiki\Page\PageReferenceValue;

/**
 * @covers \MediaWiki\Navigation\CodexPagerNavigationBuilder
 */
class CodexPagerNavigationBuilderTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );
	}

	private function initializeNavBuilder(): CodexPagerNavigationBuilder {
		$output = $this->createMock( OutputPage::class );
		$output->expects( $this->once() )->method( 'addModuleStyles' );
		$output->expects( $this->once() )->method( 'addModules' );

		$context = $this->createMock( RequestContext::class );
		$context->method( 'getOutput' )
			->willReturn( $output );
		$context->method( 'msg' )
			->willReturnCallback( function ( $key, ...$params ) {
				$message = $this->getMockMessage( $key, $params );
				$message->method( 'page' )
					->willReturn( $message );
				return $message;
			} );

		return new CodexPagerNavigationBuilder( $context, [] );
	}

	public function testBasic() {
		$navBuilder = $this->initializeNavBuilder();
		$navBuilder->setPage( PageReferenceValue::localReference( NS_MAIN, 'A' ) );
		$output = $navBuilder->getHtml();
		// check for the table pager class
		$this->assertStringContainsString( "cdx-table-pager", $output );
		// check for the limits dropdown form
		$this->assertStringContainsString( "form", $output );
		$this->assertStringContainsString( "select", $output );
		// check for the buttons
		$this->assertStringContainsString( "table_pager_first", $output );
		$this->assertStringContainsString( "table_pager_next", $output );
		$this->assertStringContainsString( "table_pager_prev", $output );
		$this->assertStringContainsString( "table_pager_last", $output );
	}

	public function testOverrides() {
		$navBuilder = $this->initializeNavBuilder();
		$navBuilder
			->setPage( PageReferenceValue::localReference( NS_MAIN, 'A' ) )
			->setLinkQuery( [ 'a' => '1' ] )
			->setPrevLinkQuery( [ 'b' => '2' ] )
			->setNextLinkQuery( [ 'c' => '3' ] )
			->setFirstLinkQuery( [ 'd' => '4' ] )
			->setLastLinkQuery( [ 'e' => '5' ] )
			->setLimits( [ 1, 2 ] )
			->setNavClass( 'test-nav-class' );
		$output = html_entity_decode( $navBuilder->getHtml() );
		// check the nav class
		$this->assertStringContainsString( 'test-nav-class', $output );
		// check the links
		$this->assertStringContainsString( 'href="/w/index.php?title=A&a=1&b=2"', $output );
		$this->assertStringContainsString( 'href="/w/index.php?title=A&a=1&c=3"', $output );
		$this->assertStringContainsString( 'href="/w/index.php?title=A&a=1&d=4"', $output );
		$this->assertStringContainsString( 'href="/w/index.php?title=A&a=1&e=5"', $output );
	}
}
