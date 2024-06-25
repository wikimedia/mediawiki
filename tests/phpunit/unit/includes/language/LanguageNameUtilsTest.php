<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;

/**
 * @group Language
 * @covers \MediaWiki\Languages\LanguageNameUtils
 */
class LanguageNameUtilsTest extends MediaWikiUnitTestCase {
	use LanguageNameUtilsTestTrait;

	/** @var HookContainer */
	private $hookContainer;

	protected function setUp(): void {
		parent::setUp();
		$this->hookContainer = $this->createHookContainer();
	}

	private function newObj( array $optionsArray = [] ): LanguageNameUtils {
		// NOTE: hookContainer can be undefined here because
		// data providers run before test case instantiation,
		// and provideExceptionFromInvalidCode() calls newObj() via getFileName()
		// TODO: Fix this by making the data provider not dependent on newObj()
		// and instead calling getFileName() during the test.
		$this->hookContainer ??= $this->createHookContainer();
		return new LanguageNameUtils(
			new ServiceOptions(
				LanguageNameUtils::CONSTRUCTOR_OPTIONS,
				$optionsArray,
				[
					MainConfigNames::ExtraLanguageNames => [],
					MainConfigNames::LanguageCode => 'en',
					MainConfigNames::UsePigLatinVariant => true,
					MainConfigNames::UseXssLanguage => false,
				]
			),
			$this->hookContainer
		);
	}

	protected function setLanguageTemporaryHook( string $hookName, $handler ): void {
		$this->hookContainer->register( $hookName, $handler );
	}

	protected function clearLanguageHook( string $hookName ): void {
		$this->hookContainer->clear( $hookName );
	}

	private function isSupportedLanguage( $code ) {
		return $this->newObj()->isSupportedLanguage( $code );
	}

	private function isValidCode( $code ) {
		return $this->newObj()->isValidCode( $code );
	}

	private function isValidBuiltInCode( $code ) {
		return $this->newObj()->isValidBuiltInCode( $code );
	}

	private function isKnownLanguageTag( $code ) {
		return $this->newObj()->isKnownLanguageTag( $code );
	}

	private function assertGetLanguageNames( array $options, $expected, $code, ...$otherArgs ) {
		$this->assertSame( $expected, $this->newObj( $options )
			->getLanguageNames( ...$otherArgs )[strtolower( $code )] ?? '' );
		$this->assertSame( $expected,
			$this->newObj( $options )->getLanguageName( $code, ...$otherArgs ) );
	}

	private function getLanguageNames( ...$args ) {
		return $this->newObj()->getLanguageNames( ...$args );
	}

	private function getLanguageName( ...$args ) {
		return $this->newObj()->getLanguageName( ...$args );
	}

	private function getFileName( ...$args ) {
		return $this->newObj()->getFileName( ...$args );
	}

	private function getMessagesFileName( $code ) {
		return $this->newObj()->getMessagesFileName( $code );
	}

	private function getJsonMessagesFileName( $code ) {
		return $this->newObj()->getJsonMessagesFileName( $code );
	}
}
