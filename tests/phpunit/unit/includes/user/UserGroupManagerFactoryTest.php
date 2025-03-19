<?php

namespace MediaWiki\Tests\User;

use FactoryArgTestTrait;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\WikiMap\WikiMap;
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
		return [ 'hookContainer', 'configuredReadOnlyMode', 'jobQueueGroupFactory' ];
	}

	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		if ( $param->getType() && $param->getType()->getName() === ILBFactory::class ) {
			$mock = $this->createNoOpMock( ILBFactory::class, [ 'getMainLB', 'getLocalDomainID' ] );
			$mock->method( 'getMainLB' )
				->willReturn( $this->createNoOpMock( ILoadBalancer::class ) );
			$mock->method( 'getLocalDomainID' )
				->willReturn( WikiMap::getCurrentWikiId() );
			return [ $mock ];
		}
		if ( $param->getType() && $param->getType()->getName() === JobQueueGroupFactory::class ) {
			$mock = $this->createNoOpMock( JobQueueGroupFactory::class, [ 'makeJobQueueGroup' ] );
			$mock->method( 'makeJobQueueGroup' )
				->willReturn( $this->createNoOpMock( JobQueueGroup::class ) );
			return [ $mock ];
		}
		return [];
	}
}
