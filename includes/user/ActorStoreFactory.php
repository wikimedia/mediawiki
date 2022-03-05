<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * ActorStore factory for various domains.
 *
 * @package MediaWiki\User
 * @since 1.36
 */
class ActorStoreFactory {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		'SharedDB',
		'SharedTables',
	];

	/** @var ILBFactory */
	private $loadBalancerFactory;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var LoggerInterface */
	private $logger;

	/** @var string|false */
	private $sharedDB;

	/** @var string[] */
	private $sharedTables;

	/** @var ActorStore[] */
	private $storeCache = [];

	/**
	 * @param ServiceOptions $options
	 * @param ILBFactory $loadBalancerFactory
	 * @param UserNameUtils $userNameUtils
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		ServiceOptions $options,
		ILBFactory $loadBalancerFactory,
		UserNameUtils $userNameUtils,
		LoggerInterface $logger
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->loadBalancerFactory = $loadBalancerFactory;
		$this->sharedDB = $options->get( 'SharedDB' );
		$this->sharedTables = $options->get( 'SharedTables' );
		$this->userNameUtils = $userNameUtils;
		$this->logger = $logger;
	}

	/**
	 * @param string|false $wikiId
	 * @return ActorNormalization
	 */
	public function getActorNormalization( $wikiId = WikiAwareEntity::LOCAL ): ActorNormalization {
		return $this->getActorStore( $wikiId );
	}

	/**
	 * @param string|false $wikiId
	 * @return ActorStore
	 */
	public function getActorStore( $wikiId = WikiAwareEntity::LOCAL ): ActorStore {
		// During the transition from User, we still have old User objects
		// representing users from a different wiki, so we still have IDatabase::getDomainId
		// passed as $wikiId, so we need to remap it back to LOCAL.
		if ( is_string( $wikiId ) && $this->loadBalancerFactory->getLocalDomainID() === $wikiId ) {
			$wikiId = WikiAwareEntity::LOCAL;
		}

		$storeCacheKey = $this->getStoreCacheKey( $wikiId );
		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$this->storeCache[$storeCacheKey] = new ActorStore(
				$this->getLoadBalancerForTable( 'actor', $wikiId ),
				$this->userNameUtils,
				$this->logger,
				$wikiId
			);
		}
		return $this->storeCache[$storeCacheKey];
	}

	/**
	 * @param bool $wikiId
	 * @return UserIdentityLookup
	 */
	public function getUserIdentityLookup(
		$wikiId = WikiAwareEntity::LOCAL
	): UserIdentityLookup {
		return $this->getActorStore( $wikiId );
	}

	/**
	 * @param string|false $wikiId
	 * @return string
	 */
	private function getStoreCacheKey( $wikiId ): string {
		return $wikiId === WikiAwareEntity::LOCAL ? 'LOCAL' : $wikiId;
	}

	/**
	 * Returns a load balancer for the database that has the $table
	 * for the given $wikiId.
	 *
	 * @param string $table
	 * @param bool $wikiId
	 * @return ILoadBalancer
	 */
	private function getLoadBalancerForTable(
		string $table,
		$wikiId = WikiAwareEntity::LOCAL
	): ILoadBalancer {
		if ( $this->sharedDB && in_array( $table, $this->sharedTables ) ) {
			// The main LB is already properly set up for shared databases early in Setup.php
			return $this->loadBalancerFactory->getMainLB();
		}
		return $this->loadBalancerFactory->getMainLB( $wikiId );
	}
}
