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

use MediaWiki\Block\HideUserUtils;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\MainConfigNames;
use MediaWiki\User\TempUser\TempUserConfig;
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
		MainConfigNames::SharedDB,
		MainConfigNames::SharedTables,
	];

	private ILBFactory $loadBalancerFactory;
	private UserNameUtils $userNameUtils;
	private TempUserConfig $tempUserConfig;
	private LoggerInterface $logger;
	private HideUserUtils $hideUserUtils;

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
	 * @param TempUserConfig $tempUserConfig
	 * @param LoggerInterface $logger
	 * @param HideUserUtils $hideUserUtils
	 */
	public function __construct(
		ServiceOptions $options,
		ILBFactory $loadBalancerFactory,
		UserNameUtils $userNameUtils,
		TempUserConfig $tempUserConfig,
		LoggerInterface $logger,
		HideUserUtils $hideUserUtils
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->loadBalancerFactory = $loadBalancerFactory;
		$this->sharedDB = $options->get( MainConfigNames::SharedDB );
		$this->sharedTables = $options->get( MainConfigNames::SharedTables );
		$this->userNameUtils = $userNameUtils;
		$this->tempUserConfig = $tempUserConfig;
		$this->logger = $logger;
		$this->hideUserUtils = $hideUserUtils;
	}

	/**
	 * @param string|false $wikiId
	 * @return ActorNormalization
	 */
	public function getActorNormalization( $wikiId = WikiAwareEntity::LOCAL ): ActorNormalization {
		return $this->getActorStore( $wikiId );
	}

	/**
	 * @since 1.42
	 * @param string|false $wikiId
	 * @return ActorNormalization
	 */
	public function getActorNormalizationForImport(
		$wikiId = WikiAwareEntity::LOCAL
	): ActorNormalization {
		return $this->getActorStoreForImport( $wikiId );
	}

	/**
	 * @param string|false $wikiId
	 * @return ActorStore
	 */
	public function getActorStore( $wikiId = WikiAwareEntity::LOCAL ): ActorStore {
		return $this->getStore( $wikiId, false );
	}

	/**
	 * @since 1.42
	 * @param string|false $wikiId
	 * @return ActorStore
	 */
	public function getActorStoreForImport( $wikiId = WikiAwareEntity::LOCAL ): ActorStore {
		return $this->getStore( $wikiId, true );
	}

	/**
	 * @param string|false $wikiId
	 * @param bool $forImport
	 * @return ActorStore
	 */
	private function getStore( $wikiId, $forImport ): ActorStore {
		// During the transition from User, we still have old User objects
		// representing users from a different wiki, so we still have IDatabase::getDomainId
		// passed as $wikiId, so we need to remap it back to LOCAL.
		if ( is_string( $wikiId ) && $this->loadBalancerFactory->getLocalDomainID() === $wikiId ) {
			$wikiId = WikiAwareEntity::LOCAL;
		}

		$storeCacheKey = ( $forImport ? 'import' : '' ) .
			( $wikiId === WikiAwareEntity::LOCAL ? 'LOCAL' : $wikiId );

		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$store = $this->storeCache[$storeCacheKey] = new ActorStore(
				$this->getLoadBalancerForTable( 'actor', $wikiId ),
				$this->userNameUtils,
				$this->tempUserConfig,
				$this->logger,
				$this->hideUserUtils,
				$wikiId
			);
			if ( $forImport ) {
				$store->setAllowCreateIpActors( true );
			}
			$this->storeCache[$storeCacheKey] = $store;
		}
		return $this->storeCache[$storeCacheKey];
	}

	/**
	 * @param string|false $wikiId
	 * @return UserIdentityLookup
	 */
	public function getUserIdentityLookup(
		$wikiId = WikiAwareEntity::LOCAL
	): UserIdentityLookup {
		return $this->getActorStore( $wikiId );
	}

	/**
	 * Returns a load balancer for the database that has the $table
	 * for the given $wikiId.
	 *
	 * @param string $table
	 * @param string|false $wikiId
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
