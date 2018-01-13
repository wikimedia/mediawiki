<?php

namespace MediaWiki\Storage;

use CommentStoreComment;
use MWException;
use Title;
use User;
use Wikimedia\Rdbms\IDatabase;

class MultiContentRevisionFactory extends AbstractRevisionFactory {

	/**
	 * @deprecated, don't use me, use a method that doesn't exist yet...
	 *
	 * @param IDatabase $dbw
	 * @param Title $title
	 * @param CommentStoreComment $comment
	 * @param bool $minor
	 * @param User $user
	 * @return mixed
	 */
	public function newNullRevision(
		IDatabase $dbw, Title $title, CommentStoreComment $comment, $minor, User $user
	) {
		throw new \RuntimeException( 'Not yet implemented' );
	}

	/**
	 * Constructs a new RevisionRecord based on the given associative array following the MW1.29
	 * database convention for the Revision constructor.
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @deprecated since 1.31. Use a MutableRevisionRecord instead.
	 *
	 * @param array $fields
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param Title|null $title
	 *
	 * @return MutableRevisionRecord
	 * @throws MWException
	 */
	public function newMutableRevisionFromArray( array $fields, $queryFlags = 0, Title $title = null
	) {
		throw new \RuntimeException( 'Not yet implemented' );
	}

	/**
	 * Constructs a RevisionRecord given a database row and content slots.
	 *
	 * MCR migration note: this replaces Revision::newFromRow for rows based on the
	 * revision, slot, and content tables defined for MCR since MW1.31.
	 *
	 * @param object $row A query result row as a raw object.
	 *        Use RevisionStore::getQueryInfo() to build a query that yields the required fields.
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, $queryFlags = 0, Title $title = null ) {
		throw new \RuntimeException( 'Not yet implemented' );
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * MCR migration note: this replaces Revision::newFromArchiveRow
	 *
	 * @param object $row A query result row as a raw object.
	 *        Use RevisionStore::getArchiveQueryInfo() to build a query that yields the
	 *        required fields.
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param Title $title
	 * @param array $overrides An associative array that allows fields in $row to be overwritten.
	 *        Keys in this array correspond to field names in $row without the "ar_" prefix, so
	 *        $overrides['user'] will override $row->ar_user, etc.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArchiveRow(
		$row, $queryFlags = 0, Title $title = null, array $overrides = []
	) {
		throw new \RuntimeException( 'Not yet implemented' );
	}
}
