<?php

namespace MediaWiki\Tests\User;

use FactoryArgTestTrait;
use MediaWiki\User\UserRequirementsConditionChecker;
use MediaWiki\User\UserRequirementsConditionCheckerFactory;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\UserRequirementsConditionCheckerFactory
 */
class UserRequirementsConditionCheckerFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return UserRequirementsConditionCheckerFactory::class;
	}

	protected static function getInstanceClass() {
		return UserRequirementsConditionChecker::class;
	}

	protected static function getExtraClassArgCount() {
		return 2;
	}

	protected function getFactoryMethodName() {
		return 'getUserRequirementsConditionChecker';
	}
}
