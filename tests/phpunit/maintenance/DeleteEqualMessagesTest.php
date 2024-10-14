<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteEqualMessages;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Title\Title;

/**
 * @covers \DeleteEqualMessages
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteEqualMessagesTest extends MaintenanceBaseTestCase {

	private bool $wasMessageCacheDisabled;

	protected function getMaintenanceClass() {
		return DeleteEqualMessages::class;
	}

	protected function setUp(): void {
		parent::setUp();
		// Set the message cache to enabled if it is not already, as we need it to be enabled for the tests to work.
		$messageCache = $this->getServiceContainer()->getMessageCache();
		$this->wasMessageCacheDisabled = $messageCache->isDisabled();
		if ( $this->wasMessageCacheDisabled ) {
			$messageCache->enable();
		}
	}

	protected function tearDown(): void {
		parent::tearDown();
		// Set the message cache back to disabled after the test so we don't affect other tests.
		if ( $this->wasMessageCacheDisabled ) {
			$this->getServiceContainer()->getMessageCache()->disable();
		}
	}

	/** @dataProvider provideExecuteForNoRelevantOverrides */
	public function testExecuteForNoMessageOverrides( $langCode, $expectedOutputRegex ) {
		if ( $langCode !== null ) {
			$this->maintenance->setOption( 'lang-code', $langCode );
		}
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecuteForNoRelevantOverrides() {
		return [
			'No lang-code provided' => [
				null,
				"/Checking for pages with default message[\s\S]*fetching message info for content language\ndone/",
			],
			'Spanish used as lang-code' => [
				'es', "/Checking for pages with default message[\s\S]*fetching message info for language: es\ndone/",
			],
		];
	}

	public function testExecuteForInvalidLanguageCode() {
		$this->maintenance->setOption( 'lang-code', 'invalidlanguagecode' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Invalid language code: invalidlanguagecode/' );
		$this->maintenance->execute();
	}

	private function performMediaWikiOverrideEdit( $title, $content ) {
		$this->editPage( $title, $content, 'test', NS_MEDIAWIKI );
	}

	/** @dataProvider provideExecuteWhenNoMessageOverridesContainDefaultContent */
	public function testExecuteWhenNoMessageOverridesContainDefaultContent( $title, $langCode, $expectedOutputRegex ) {
		// Create the message override with content not equal to the default value.
		$this->performMediaWikiOverrideEdit( $title, 'test-1234' );
		// Run deferred updates as this refreshes the message cache after the MediaWiki namespace edit.
		DeferredUpdates::doUpdates();
		// Call the maintenance script, and expect that it does not consider the override to be the default value.
		if ( $langCode !== null ) {
			$this->maintenance->setOption( 'lang-code', $langCode );
		}
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex );
		// Check that the message override was not deleted.
		Title::clearCaches();
		$this->assertTrue(
			$this->getServiceContainer()->getTitleFactory()->newFromText( $title )->exists(),
			"$title was deleted when it should not have been."
		);
	}

	public static function provideExecuteWhenNoMessageOverridesContainDefaultContent() {
		return [
			'No lang-code provided' => [
				'MediaWiki:Aboutpage', null,
				"/Checking for pages with default message[\s\S]*fetching message info for content language\ndone/",
			],
			'Spanish used as lang-code' => [
				'MediaWiki:Aboutpage/es', 'es',
				"/Checking for pages with default message[\s\S]*fetching message info for language: es\ndone/",
			],
		];
	}

	/** @dataProvider provideExecuteForDryRun */
	public function testExecuteForDryRun( $msgKey, $title, $langCode ) {
		// Get the default value for the message key.
		$aboutMessageDefault = wfMessage( $msgKey );
		if ( $langCode ) {
			$aboutMessageDefault->inLanguage( $langCode );
		}
		$aboutMessageDefault = $aboutMessageDefault->text();
		// Create the override such that the override is equal to the default
		$this->performMediaWikiOverrideEdit( $title, 'testing-1234' );
		$this->performMediaWikiOverrideEdit( $title, $aboutMessageDefault );
		// Create a talk page for this override, so that we can test how the script handles that page.
		/** @var Title $talkPage */
		$talkPage = Title::newFromText( $title )->getTalkPageIfDefined();
		$this->performMediaWikiOverrideEdit(
			$talkPage->getPrefixedText(),
			'testing1234'
		);
		// Run deferred updates as this refreshes the message cache after the MediaWiki namespace edit.
		DeferredUpdates::doUpdates();
		// Call the maintenance script, and expect that it finds the override that equals the default value.
		if ( $langCode !== null ) {
			$this->maintenance->setOption( 'lang-code', $langCode );
		}
		$this->maintenance->execute();
		$this->expectOutputRegex(
			"/1 pages are equal to the default message \(\+ 1 talk pages\)[\s\S]*" .
			preg_quote( $title, '/' ) . "[\s\S]*" . preg_quote( $talkPage->getPrefixedText(), '/' ) .
			"[\s\S]*Run the script again with --delete to delete these pages/"
		);
		// Check that the message override was not deleted, as a dry-run should not actually perform deletions.
		Title::clearCaches();
		$this->assertTrue(
			$this->getServiceContainer()->getTitleFactory()->newFromText( $title )->exists(),
			"$title should not have been deleted, as it is a dry-run."
		);
	}

	public static function provideExecuteForDryRun() {
		return [
			'No lang-code provided' => [ 'aboutpage', 'MediaWiki:Aboutpage', null ],
			'Spanish used as lang-code' => [ 'aboutpage', 'MediaWiki:Aboutpage/es', 'es' ],
		];
	}

	/** @dataProvider provideExecute */
	public function testExecute( $msgKey, $title, $langCode ) {
		// Get the default value for the message key.
		$aboutMessageDefault = wfMessage( $msgKey );
		if ( $langCode ) {
			$aboutMessageDefault->inLanguage( $langCode );
		}
		$aboutMessageDefault = $aboutMessageDefault->text();
		// Create the override such that the override is equal to the default
		$this->performMediaWikiOverrideEdit( $title, 'testing-1234' );
		$this->performMediaWikiOverrideEdit( $title, $aboutMessageDefault );
		// Create a talk page for this override, so that we can test that it gets deleted by the maintenance script.
		/** @var Title $talkPage */
		$talkPage = Title::newFromText( $title )->getTalkPageIfDefined();
		$this->performMediaWikiOverrideEdit(
			$talkPage->getPrefixedText(),
			'testing1234'
		);
		// Run deferred updates as this refreshes the message cache after the MediaWiki namespace edit.
		DeferredUpdates::doUpdates();
		// Call the maintenance script, and expect that it finds the override that equals the default value.
		if ( $langCode !== null ) {
			$this->maintenance->setOption( 'lang-code', $langCode );
		}
		$this->maintenance->setOption( 'delete', 1 );
		$this->maintenance->setOption( 'delete-talk', 1 );
		$this->maintenance->execute();
		$this->expectOutputRegex(
			"/1 pages are equal to the default message \(\+ 1 talk pages\)[\s\S]*" .
			"deleting equal messages[\s\S]*" . preg_quote( $title, '/' ) . "[\s\S]*" .
			preg_quote( $talkPage->getPrefixedText(), '/' ) . "[\s\S]*done/"
		);
		Title::clearCaches();
		$this->assertFalse(
			$this->getServiceContainer()->getTitleFactory()->newFromText( $title )->exists(),
			"$title should not have been deleted, as it duplicates the default."
		);
	}

	public static function provideExecute() {
		return [
			'No lang-code provided' => [ 'aboutpage', 'MediaWiki:Aboutpage', null, ],
			'Spanish used as lang-code' => [ 'aboutpage', 'MediaWiki:Aboutpage/es', 'es' ],
		];
	}
}
