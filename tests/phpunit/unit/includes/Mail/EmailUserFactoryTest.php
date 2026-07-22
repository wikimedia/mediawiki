<?php

namespace MediaWiki\Tests\Unit\Mail;

use FactoryArgTestTrait;
use MediaWiki\Mail\EmailUser;
use MediaWiki\Mail\EmailUserFactory;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Mail\EmailUserFactory
 */
class EmailUserFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;
	use MockAuthorityTrait;

	protected static function getFactoryClass() {
		return EmailUserFactory::class;
	}

	protected static function getInstanceClass() {
		return EmailUser::class;
	}

	protected static function getExtraClassArgCount(): int {
		// $sender
		return 1;
	}

	protected function getFactoryMethodName(): string {
		return 'newEmailUser';
	}

	protected function getOverriddenMockValueForParam( $param ) {
		if ( $param->getName() === 'sender' ) {
			return [ $this->mockRegisteredNullAuthority() ];
		}
		return [];
	}
}
