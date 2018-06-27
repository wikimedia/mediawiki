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

namespace MediaWiki\Storage;

use ActorMigration;
use CommentStore;
use Psr\Log\LoggerInterface;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @since 1.32
 */
class RevisionStoreFactory {

	/** @var SqlBlobStore */
	private $blobStore;

	/** @var LoadBalancer */
	private $loadBalancer;

	/** @var WANObjectCache */
	private $cache;

	/** @var CommentStore */
	private $commentStore;

	/** @var ActorMigration */
	private $actorMigration;

	/** @var NameTableStore */
	private $contentModelStore;

	/** @var NameTableStore */
	private $slotRoleStore;

	/** @var int One of the MIGRATION_* constants */
	private $mcrMigrationStage;

	/**
	 * @var bool
	 * @see $wgContentHandlerUseDB
	 */
	private $contentHandlerUseDB;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @todo $blobStore should be allowed to be any BlobStore!
	 *
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param WANObjectCache $cache
	 * @param CommentStore $commentStore
	 * @param NameTableStore $contentModelStore
	 * @param NameTableStore $slotRoleStore
	 * @param int $migrationStage
	 * @param ActorMigration $actorMigration
	 * @param LoggerInterface $logger
	 * @param bool $contentHandlerUseDB see {@link $wgContentHandlerUseDB}
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		SqlBlobStore $blobStore,
		WANObjectCache $cache,
		CommentStore $commentStore,
		NameTableStore $contentModelStore,
		NameTableStore $slotRoleStore,
		$migrationStage,
		ActorMigration $actorMigration,
		LoggerInterface $logger,
		$contentHandlerUseDB
	) {
		Assert::parameterType( 'integer', $migrationStage, '$migrationStage' );

		$this->loadBalancer = $loadBalancer;
		$this->blobStore = $blobStore;
		$this->cache = $cache;
		$this->commentStore = $commentStore;
		$this->contentModelStore = $contentModelStore;
		$this->slotRoleStore = $slotRoleStore;
		$this->mcrMigrationStage = $migrationStage;
		$this->actorMigration = $actorMigration;
		$this->logger = $logger;
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	/**
	 * @since 1.32
	 *
	 * @param bool|string $wikiId false for the current domain / wikid
	 *
	 * @return RevisionStore for the given wikiId with all necessary services and a logger
	 */
	public function getRevisionStore( $wikiId = false ) {
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );

		$store = new RevisionStore(
			$this->loadBalancer,
			$this->blobStore,
			$this->cache,
			$this->commentStore,
			$this->contentModelStore,
			$this->slotRoleStore,
			$this->mcrMigrationStage,
			$this->actorMigration,
			$wikiId
		);

		$store->setLogger( $this->logger );
		$store->setContentHandlerUseDB( $this->contentHandlerUseDB );

		return $store;
	}

}
