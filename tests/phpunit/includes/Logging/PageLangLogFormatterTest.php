<?php
namespace MediaWiki\Tests\Logging;

use MediaWiki\Logging\PageLangLogFormatter;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;

/**
 * @covers \MediaWiki\Logging\PageLangLogFormatter
 */
class PageLangLogFormatterTest extends LogFormatterTestCase {

	protected function setUp(): void {
		parent::setUp();

		// Clear all hooks to disable cldr extension
		$this->clearHooks();

		// Register LogHandler, see $wgPageLanguageUseDB in Setup.php
		$this->overrideConfigValue(
			MainConfigNames::LogActionsHandlers,
			MainConfigSchema::getDefaultValue( MainConfigNames::LogActionsHandlers ) +
			[
				'pagelang/pagelang' => [
					'class' => PageLangLogFormatter::class,
					'services' => [
						'LanguageNameUtils',
					]
				]
			]
		);
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function providePageLangLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'pagelang',
					'action' => 'pagelang',
					'comment' => 'page lang comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::oldlanguage' => 'en',
						'5::newlanguage' => 'de[def]',
					],
				],
				[
					'text' => 'User changed the language of Page from English (en) to Deutsch (de) [default]',
					'api' => [
						'oldlanguage' => 'en',
						'newlanguage' => 'de[def]'
					],
				],
			],
		];
	}

	/**
	 * @dataProvider providePageLangLogDatabaseRows
	 */
	public function testPageLangLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
