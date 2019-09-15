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
 * Attribution notice: when this file was created, much of its content was taken
 * from the Revision.php file as present in release 1.30. Refer to the history
 * of that file for original authorship.
 *
 * @file
 */

namespace MediaWiki\Revision;

use ActorMigration;
use CommentStore;
use Psr\Log\LoggerInterface;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStoreFactory;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\ILBFactory;

/**
 * Factory service for RevisionStore instances. This allows RevisionStores to be created for
 * cross-wiki access.
 *
 * @warning Beware compatibility issues with schema migration in the context of cross-wiki access!
 * This class assumes that all wikis are at compatible migration stages for all relevant schemas.
 * Relevant schemas are: revision storage (MCR), the revision comment table, and the actor table.
 * Migration stages are compatible as long as a) there are no wikis in the cluster that only write
 * the old schema or b) there are no wikis that read only the new schema.
 *
 * @since 1.32
 */
class RevisionStoreFactory {

	/** @var BlobStoreFactory */
	private $blobStoreFactory;
	/** @var ILBFactory */
	private $dbLoadBalancerFactory;
	/** @var WANObjectCache */
	private $cache;
	/** @var LoggerInterface */
	private $logger;

	/** @var CommentStore */
	private $commentStore;
	/** @var ActorMigration */
	private $actorMigration;
	/** @var int One of the MIGRATION_* constants */
	private $mcrMigrationStage;
	/**
	 * @var bool
	 * @see $wgContentHandlerUseDB
	 */
	private $contentHandlerUseDB;

	/** @var NameTableStoreFactory */
	private $nameTables;

	/** @var SlotRoleRegistry */
	private $slotRoleRegistry;

	/**
	 * @param ILBFactory $dbLoadBalancerFactory
	 * @param BlobStoreFactory $blobStoreFactory
	 * @param NameTableStoreFactory $nameTables
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param WANObjectCache $cache
	 * @param CommentStore $commentStore
	 * @param ActorMigration $actorMigration
	 * @param int $migrationStage
	 * @param LoggerInterface $logger
	 * @param bool $contentHandlerUseDB see {@link $wgContentHandlerUseDB}. Must be the same
	 *        for all wikis in the cluster. Will go away after MCR migration.
	 */
	public function __construct(
		ILBFactory $dbLoadBalancerFactory,
		BlobStoreFactory $blobStoreFactory,
		NameTableStoreFactory $nameTables,
		SlotRoleRegistry $slotRoleRegistry,
		WANObjectCache $cache,
		CommentStore $commentStore,
		ActorMigration $actorMigration,
		$migrationStage,
		LoggerInterface $logger,
		$contentHandlerUseDB
	) {
		Assert::parameterType( 'integer', $migrationStage, '$migrationStage' );
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->blobStoreFactory = $blobStoreFactory;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->nameTables = $nameTables;
		$this->cache = $cache;
		$this->commentStore = $commentStore;
		$this->actorMigration = $actorMigration;
		$this->mcrMigrationStage = $migrationStage;
		$this->logger = $logger;
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	/**
	 * @since 1.32
	 *
	 * @param bool|string $dbDomain DB domain of the relevant wiki or false for the current one
	 *
	 * @return RevisionStore for the given wikiId with all necessary services
	 */
	public function getRevisionStore( $dbDomain = false ) {
		Assert::parameterType( 'string|boolean', $dbDomain, '$dbDomain' );

		$store = new RevisionStore(
			$this->dbLoadBalancerFactory->getMainLB( $dbDomain ),
			// @phan-suppress-next-line PhanAccessMethodInternal
			$this->blobStoreFactory->newSqlBlobStore( $dbDomain ),
			$this->cache, // Pass local cache instance; Leave cache sharing to RevisionStore.
			$this->commentStore,
			$this->nameTables->getContentModels( $dbDomain ),
			$this->nameTables->getSlotRoles( $dbDomain ),
			$this->slotRoleRegistry,
			$this->mcrMigrationStage,
			$this->actorMigration,
			$dbDomain
		);

		$store->setLogger( $this->logger );
		$store->setContentHandlerUseDB( $this->contentHandlerUseDB );

		return $store;
	}
}
