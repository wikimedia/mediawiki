<?php

/**
 * @covers SkinTemplate
 *
 * @group Output
 *
 * @licence GNU GPL v2+
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
		return array(
			array(
				'<li class="class" title="itemtitle"><a href="url" title="title">text</a></li>',
				'',
				array( 'class' => 'class', 'itemtitle' => 'itemtitle', 'href' => 'url', 'title' => 'title', 'text' => 'text' ),
				array(),
				'Test makteListItem with normal values'
			)
		);
	}
}
