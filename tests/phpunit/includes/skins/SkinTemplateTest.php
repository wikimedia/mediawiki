<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers SkinTemplate
 *
 * @group Output
 *
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class SkinTemplateTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider makeListItemProvider
	 */
	public function testMakeListItem( $expected, $key, array $item, array $options, $message ) {
		$template = $this->getMockForAbstractClass( BaseTemplate::class );
		$template->set( 'skin', new SkinFallback( [
			'name' => 'fallback',
			'templateDirectory' => __DIR__,
		] ) );

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
	 * @param bool $isSyndicated
	 * @param string $html
	 * @return OutputPage
	 */
	private function getMockOutputPage( $isSyndicated, $html ) {
		$mock = $this->createMock( OutputPage::class );
		$mock->expects( $this->once() )
			->method( 'isSyndicated' )
			->willReturn( $isSyndicated );
		$mock->expects( $this->any() )
			->method( 'getHTML' )
			->willReturn( $html );
		return $mock;
	}

	public function provideGetDefaultModules() {
		return [
			[
				false,
				'',
				[]
			],
			[
				true,
				'',
				[ 'mediawiki.feedlink' ]
			],
			[
				false,
				'FOO mw-ui-button BAR',
				[ 'mediawiki.ui.button' ]
			],
			[
				true,
				'FOO mw-ui-button BAR',
				[ 'mediawiki.ui.button', 'mediawiki.feedlink' ]
			],
		];
	}

	/**
	 * @covers Skin::getDefaultModules
	 * @dataProvider provideGetDefaultModules
	 */
	public function testgetDefaultModules( $isSyndicated, $html, array $expectedModuleStyles ) {
		$skin = new SkinTemplate();

		$context = new DerivativeContext( $skin->getContext() );
		$context->setOutput( $this->getMockOutputPage( $isSyndicated, $html ) );
		$skin->setContext( $context );

		$modules = $skin->getDefaultModules();

		$actualStylesModule = array_merge( ...array_values( $modules['styles'] ) );
		foreach ( $expectedModuleStyles as $expected ) {
			$this->assertContains( $expected, $actualStylesModule );
		}
	}

	/**
	 * @covers SkinTemplate::insertNotificationsIntoPersonalTools
	 * @dataProvider provideContentNavigation
	 *
	 * @param array $contentNavigation
	 * @param array $expected
	 */
	public function testInsertNotificationsIntoPersonalTools(
		array $contentNavigation,
		array $expected
	) {
		$wrapper = TestingAccessWrapper::newFromObject( new SkinTemplate() );

		$this->assertEquals(
			$expected,
			$wrapper->insertNotificationsIntoPersonalTools( $contentNavigation )
		);
	}

	public function provideContentNavigation() : array {
		return [
			'No userpage set' => [
				'contentNavigation' => [
					'notifications' => [
						'notification 1' => []
					],
					'user-menu' => [
						'item 1' => [],
						'item 2' => [],
						'item 3' => []
					]
				],
				'expected' => [
					'item 1' => [],
					'item 2' => [],
					'item 3' => []
				]
			],
			'userpage set, no notifications' => [
				'contentNavigation' => [
					'notifications' => [],
					'user-menu' => [
						'item 1' => [],
						'userpage' => [],
						'item 2' => [],
						'item 3' => []
					]
				],
				'expected' => [
					'item 1' => [],
					'userpage' => [],
					'item 2' => [],
					'item 3' => []
				]
			],
			'userpage set, notification defined' => [
				'contentNavigation' => [
					'notifications' => [
						'notification 1' => []
					],
					'user-menu' => [
						'item 1' => [],
						'userpage' => [],
						'item 2' => [],
						'item 3' => []
					]
				],
				'expected' => [
					'item 1' => [],
					'userpage' => [],
					'notification 1' => [],
					'item 2' => [],
					'item 3' => []
				]
			]
		];
	}
}
