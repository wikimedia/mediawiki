<?php

namespace MediaWiki\Storage;

use InvalidArgumentException;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * RevisionStore implementation using the old single content schema.
 */
class MultiContentRevisionStore extends AbstractRevisionStore {

	use SingleContentRevisionQueryInfo;

	/**
	 * @var SqlBlobStore
	 */
	private $blobStore;

	/**
	 * @var RevisionTitleLookup
	 */
	private $revisionTitleLookup;

	/**
	 * @todo $blobStore should be allowed to be any BlobStore!
	 *
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param RevisionTitleLookup $revisionTitleLookup
	 * @param bool|string $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		SqlBlobStore $blobStore,
		RevisionTitleLookup $revisionTitleLookup,
		$wikiId = false
	) {
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );
		parent::__construct( $loadBalancer, $wikiId );

		$this->blobStore = $blobStore;
		$this->revisionTitleLookup = $revisionTitleLookup;
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * MCR migration note: this replaces Revision::insertOn
	 *
	 * @param RevisionRecord $rev
	 * @param IDatabase $dbw (master connection)
	 *
	 * @throws InvalidArgumentException
	 * @return RevisionRecord the new revision record.
	 */
	public function insertRevisionOn( RevisionRecord $rev, IDatabase $dbw ) {
		throw new \RuntimeException( 'Not yet implemented' );
	}

	/**
	 * Do a batched query for the sizes of a set of revisions.
	 *
	 * MCR migration note: this replaces Revision::getParentLengths
	 *
	 * @deprecated use RevisionStore::getRevisionSizes instead.
	 *
	 * @param IDatabase $db
	 * @param int[] $revIds
	 * @return int[] associative array mapping revision IDs from $revIds to the nominal size
	 *         of the corresponding revision.
	 */
	public function listRevisionSizes( IDatabase $db, array $revIds ) {
		//TODO could be schema dependant? or?
		throw new \RuntimeException( 'Not yet implemented' );
	}

	// TODO: move relevant methods from Title here, e.g. getFirstRevision, isBigDeletion, etc.

}
