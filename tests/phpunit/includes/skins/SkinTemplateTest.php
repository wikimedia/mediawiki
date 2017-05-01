<?php

/**
 * @covers SkinTemplate
 *
 * @group Output
 *
 * @author Bene* < benestar.wikimedia@gmail.com >
 */

class SkinTemplateTest extends MediaWikiTestCase {
	/**
	 * @dataProvider makeListItemProvider
	 */
	public function testMakeListItem( $expected, $key, $item, $options, $message ) {
		$template = $this->getMockForAbstractClass( 'BaseTemplate' );

		$this->assertEquals(
			$expected,
			$template->makeListItem( $key, $item, $options ),
			$message
		);
	}

	public function makeListItemProvider() {
		return [
			[
				'<li class="class" title="itemtitle"><a href="url" title="title">text</a></li>',
				'',
				[
					'class' => 'class',
					'itemtitle' => 'itemtitle',
					'href' => 'url',
					'title' => 'title',
					'text' => 'text'
				],
				[],
				'Test makeListItem with normal values'
			]
		];
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|OutputPage
	 */
	private function getMockOutputPage( $isSyndicated, $html ) {
		$mock = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->once() )
			->method( 'isSyndicated' )
			->will( $this->returnValue( $isSyndicated ) );
		$mock->expects( $this->once() )
			->method( 'getHTML' )
			->will( $this->returnValue( $html ) );
		return $mock;
	}

	public function provideSetupSkinUserCss() {
		$defaultStyles = [
			'mediawiki.legacy.shared',
			'mediawiki.legacy.commonPrint',
			'mediawiki.sectionAnchor',
		];
		$buttonStyle = 'mediawiki.ui.button';
		$feedStyle = 'mediawiki.feedlink';
		return [
			[
				$this->getMockOutputPage( false, '' ),
				$defaultStyles
			],
			[
				$this->getMockOutputPage( true, '' ),
				array_merge( $defaultStyles, [ $feedStyle ] )
			],
			[
				$this->getMockOutputPage( false, 'FOO mw-ui-button BAR' ),
				array_merge( $defaultStyles, [ $buttonStyle ] )
			],
			[
				$this->getMockOutputPage( true, 'FOO mw-ui-button BAR' ),
				array_merge( $defaultStyles, [ $feedStyle, $buttonStyle ] )
			],
		];
	}

	/**
	 * @param PHPUnit_Framework_MockObject_MockObject|OutputPage $outputPageMock
	 * @param string[] $expectedModuleStyles
	 *
	 * @covers SkinTemplate::setupSkinUserCss
	 * @dataProvider provideSetupSkinUserCss
	 */
	public function testSetupSkinUserCss( $outputPageMock, $expectedModuleStyles ) {
		$outputPageMock->expects( $this->once() )
			->method( 'addModuleStyles' )
			->with( $expectedModuleStyles );

		$skinTemplate = new SkinTemplate();
		$skinTemplate->setupSkinUserCss( $outputPageMock );
	}
}
