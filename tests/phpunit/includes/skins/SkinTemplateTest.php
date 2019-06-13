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
		$template = $this->getMockForAbstractClass( BaseTemplate::class );

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
		$mock->expects( $this->any() )
			->method( 'getHTML' )
			->will( $this->returnValue( $html ) );
		return $mock;
	}

	public function provideGetDefaultModules() {
		$defaultStyles = [
			'mediawiki.legacy.shared',
			'mediawiki.legacy.commonPrint',
		];
		$buttonStyle = 'mediawiki.ui.button';
		$feedStyle = 'mediawiki.feedlink';
		return [
			[
				false,
				'',
				$defaultStyles
			],
			[
				true,
				'',
				array_merge( $defaultStyles, [ $feedStyle ] )
			],
			[
				false,
				'FOO mw-ui-button BAR',
				array_merge( $defaultStyles, [ $buttonStyle ] )
			],
			[
				true,
				'FOO mw-ui-button BAR',
				array_merge( $defaultStyles, [ $buttonStyle, $feedStyle ] )
			],
		];
	}

	/**
	 * @covers Skin::getDefaultModules
	 * @dataProvider provideGetDefaultModules
	 */
	public function testgetDefaultModules( $isSyndicated, $html, $expectedModuleStyles ) {
		$skin = new SkinTemplate();

		$context = new DerivativeContext( $skin->getContext() );
		$context->setOutput( $this->getMockOutputPage( $isSyndicated, $html ) );
		$skin->setContext( $context );

		$modules = $skin->getDefaultModules();

		$actualStylesModule = call_user_func_array( 'array_merge', $modules['styles'] );
		$this->assertArraySubset(
			$expectedModuleStyles,
			$actualStylesModule,
			'style modules'
		);
	}
}
