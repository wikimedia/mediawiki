<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;

class LanguageNameUtilsTest extends MediaWikiUnitTestCase {
	use LanguageNameUtilsTestTrait;

	/** @var HookContainer */
	private $hookContainer;

	protected function setUp(): void {
		parent::setUp();
		$this->hookContainer = $this->createHookContainer();
	}

	/**
	 * @param array $optionsArray
	 * @return LanguageNameUtils
	 */
	private function newObj( array $optionsArray = [] ): LanguageNameUtils {
		// TODO Why is hookContainer unset here sometimes?
		$this->hookContainer = $this->hookContainer ?? $this->createHookContainer();
		return new LanguageNameUtils(
			new ServiceOptions(
				LanguageNameUtils::CONSTRUCTOR_OPTIONS,
				$optionsArray,
				[
					MainConfigNames::ExtraLanguageNames => [],
					MainConfigNames::LanguageCode => 'en',
					MainConfigNames::UsePigLatinVariant => false,
				]
			),
			$this->hookContainer
		);
	}

	protected function setLanguageTemporaryHook( string $hookName, $handler ): void {
		$this->hookContainer->register( $hookName, $handler );
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
