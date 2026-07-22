<?php

namespace MediaWiki\Tests\Unit\Preferences;

use FactoryArgTestTrait;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\LintErrorChecker;
use MediaWiki\Preferences\SignatureValidator;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\User\UserIdentity;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Preferences\SignatureValidatorFactory
 */
class SignatureValidatorFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return SignatureValidatorFactory::class;
	}

	protected static function getInstanceClass() {
		return SignatureValidator::class;
	}

	protected static function getExtraClassArgCount(): int {
		// $wiki, $localizer, $popts
		return 3;
	}

	protected function getIgnoredParamNames(): array {
		return [ 'parserFactoryClosure', 'lintErrorCheckerClosure' ];
	}

	protected function getOverriddenMockValueForParam( $param ) {
		if ( $param->getName() === 'user' ) {
			return [ $this->createNoOpMock( UserIdentity::class ) ];
		}
		if ( $param->getName() === 'localizer' ) {
			return [ $this->createNoOpMock( MessageLocalizer::class ) ];
		}
		if ( $param->getName() === 'popts' ) {
			return [ $this->createNoOpMock( ParserOptions::class ) ];
		}
		if ( $param->getName() === 'parserFactoryClosure' ) {
			return [
				function () {
					return $this->createNoOpMock( ParserFactory::class );
				},
			];
		}
		if ( $param->getName() === 'lintErrorCheckerClosure' ) {
			return [
				function () {
					return $this->createNoOpMock( LintErrorChecker::class );
				},
			];
		}
		return [];
	}
}
