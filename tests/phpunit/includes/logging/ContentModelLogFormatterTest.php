<?php

/**
 * @covers ContentModelLogFormatter
 */
class ContentModelLogFormatterTest extends LogFormatterTestCase {
	public static function provideNewLogDatabaseRows() {
		return [
			[
				[
					'type' => 'contentmodel',
					'action' => 'new',
					'comment' => 'new content model comment',
					'namespace' => NS_MAIN,
					'title' => 'ContentModelPage',
					'params' => [
						'5::newModel' => 'testcontentmodel',
					],
				],
				[
					'text' => 'User created the page ContentModelPage using a non-default content model "testcontentmodel"',
					'api' => [
						'newModel' => 'testcontentmodel',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideNewLogDatabaseRows
	 */
	public function testNewLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	public static function provideChangeLogDatabaseRows() {
		return [
			[
				[
					'type' => 'contentmodel',
					'action' => 'change',
					'comment' => 'change content model comment',
					'namespace' => NS_MAIN,
					'title' => 'ContentModelPage',
					'params' => [
						'4::oldmodel' => 'wikitext',
						'5::newModel' => 'testcontentmodel',
					],
				],
				[
					'text' => 'User changed the content model of the page ContentModelPage from "wikitext" to "testcontentmodel"',
					'api' => [
						'oldmodel' => 'wikitext',
						'newModel' => 'testcontentmodel',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideChangeLogDatabaseRows
	 */
	public function testChangeLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
