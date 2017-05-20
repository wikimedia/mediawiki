<?php

namespace MediaWiki\Exception;

use MediaWikiTestCase;
use MWException;
use Wikimedia\TestingAccessWrapper;

class RendererTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideTextGetOutputPage
	 */
	public function testGetOutputPage( $expectedNull, $langObj, $wgFullyInitialised, $wgOut ) {
		$this->setMwGlobals( [
			'wgLang' => $langObj,
			'wgFullyInitialised' => $wgFullyInitialised,
			'wgOut' => $wgOut,
		] );
		$renderer = $this->getMockForAbstractClass( Renderer::class );
		$wrapped = TestingAccessWrapper::newFromObject( $renderer );
		$e = new MWException();
		if ( $expectedNull ) {
			$this->assertNull( $wrapped->getOutputPage( $e ) );
		} else {
			$this->assertNotNull( $wrapped->getOutputPage( $e ) );
		}
	}

	public function provideTextGetOutputPage() {
		return [
			// expectedNull, langObj, wgFullyInitialised, wgOut
			[ true, null, null, null ],
			[ true, $this->getMockLanguage(), null, null ],
			[ true, $this->getMockLanguage(), true, null ],
			[ true, null, true, null ],
			[ true, null, null, true ],
			[ false, $this->getMockLanguage(), true, true ],
		];
	}

	/**
	 * @dataProvider provideIsCommandLine
	 */
	public function testIsCommandLine( $expected, $wgCommandLineMode ) {
		$this->setMwGlobals( [
			'wgCommandLineMode' => $wgCommandLineMode,
		] );
		$renderer = $this->getMockForAbstractClass( Renderer::class );
		$wrapped = TestingAccessWrapper::newFromObject( $renderer );
		$this->assertEquals( $expected, $wrapped->isCommandLine() );
	}

	public static function provideIsCommandLine() {
		return [
			[ false, null ],
			[ true, true ],
		];
	}

	private function getMockLanguage() {
		return $this->getMockBuilder( 'Language' )
			->disableOriginalConstructor()
			->getMock();
	}

}
