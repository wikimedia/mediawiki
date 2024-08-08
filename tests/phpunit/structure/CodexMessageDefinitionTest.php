<?php
/**
 * Ensures that all message keys defined by Codex exist in MediaWiki's i18n files.
 *
 * @coversNothing
 */
class CodexMessageDefinitionTest extends MediaWikiIntegrationTestCase {

	public function testThatMessagesExist() {
		$resourcesPath = __DIR__ . '/../../../resources';
		$languagesPath = __DIR__ . '/../../../languages';
		$messageKeys = json_decode( file_get_contents( "$resourcesPath/lib/codex/messageKeys.json" ), true );
		$qqq = json_decode( file_get_contents( "$languagesPath/i18n/codex/qqq.json" ), true );
		$en = json_decode( file_get_contents( "$languagesPath/i18n/codex/en.json" ), true );

		foreach ( $messageKeys as $key ) {
			$this->assertArrayHasKey( $key, $qqq, "$key must be defined in $languagesPath/i18n/codex/qqq.json" );
		}

		foreach ( $messageKeys as $key ) {
			$this->assertArrayHasKey( $key, $en, "$key must be defined in $languagesPath/i18n/codex/en.json" );
		}
	}
}
