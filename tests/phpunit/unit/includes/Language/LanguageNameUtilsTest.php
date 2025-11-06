<?php
namespace MediaWiki\Tests\Unit\Language;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWikiUnitTestCase;

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

	private function isSupportedLanguage( string $code ): bool {
		return $this->newObj()->isSupportedLanguage( $code );
	}

	private function isValidCode( string $code ): bool {
		return $this->newObj()->isValidCode( $code );
	}

	private function isValidBuiltInCode( string $code ): bool {
		return $this->newObj()->isValidBuiltInCode( $code );
	}

	private function isKnownLanguageTag( string $code ): bool {
		return $this->newObj()->isKnownLanguageTag( $code );
	}

	private function assertGetLanguageNames( array $options, string $expected, string $code, ?string ...$otherArgs ): void {
		$this->assertSame( $expected, $this->newObj( $options )
			->getLanguageNames( ...$otherArgs )[strtolower( $code )] ?? '' );
		$this->assertSame( $expected,
			$this->newObj( $options )->getLanguageName( $code, ...$otherArgs ) );
	}

	private function getLanguageNames( ?string ...$args ): array {
		return $this->newObj()->getLanguageNames( ...$args );
	}

	private function getLanguageName( ?string ...$args ): string {
		return $this->newObj()->getLanguageName( ...$args );
	}

	private function getFileName( string ...$args ): string {
		return $this->newObj()->getFileName( ...$args );
	}

	private function getMessagesFileName( string $code ): string {
		return $this->newObj()->getMessagesFileName( $code );
	}

	private function getJsonMessagesFileName( string $code ): string {
		return $this->newObj()->getJsonMessagesFileName( $code );
	}
}
