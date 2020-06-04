<?php

namespace MediaWiki\Tests\User;

use FactoryArgTestTrait;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWikiUnitTestCase;
use ReflectionParameter;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @covers \MediaWiki\User\UserGroupManagerFactory
 */
class UserGroupManagerFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return UserGroupManagerFactory::class;
	}

	protected static function getInstanceClass() {
		return UserGroupManager::class;
	}

	protected static function getExtraClassArgCount() {
		return 1;
	}

	protected function getFactoryMethodName() {
		return 'getUserGroupManager';
	}

	protected function getIgnoredParamNames() {
		return [ 'hookContainer', 'configuredReadOnlyMode' ];
	}

	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		if ( $param->getType() && $param->getType()->getName() === ILBFactory::class ) {
			$mock = $this->createNoOpMock( ILBFactory::class, [ 'getMainLB' ] );
			$mock->method( 'getMainLB' )
				->willReturn( $this->createNoOpMock( ILoadBalancer::class ) );
			return [ $mock ];
		}
		return [];
	}
}
