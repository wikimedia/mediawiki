<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo;

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A foreign repo that allows for direct access to the foreign MW database.
 *
 * Extension file repos should implement this if they support making DB queries
 * against the foreign repos. Media handler extensions (e.g. TimedMediaHandler)
 * can look for this interface if they need to look up additional information.
 *
 * An extension will typically implement this by extending the LocalRepo class
 * and overriding these methods. If the foreign repo is meant to be non-writable,
 * the extension should additionally override LocalRepo::assertWritableRepo() and
 * throw an exception -- see ForeignDBRepo and ForeignDBViaLBRepo for examples.
 *
 * @since 1.41
 * @ingroup FileRepo
 * @stable to implement
 */
interface IForeignRepoWithDB {
	/**
	 * Get a connection to the primary DB for the foreign repo.
	 * @return IDatabase
	 * @since 1.41
	 */
	public function getPrimaryDB();

	/**
	 * Get a connection to the replica DB for the foreign repo.
	 * @return IReadableDatabase
	 * @since 1.41
	 */
	public function getReplicaDB();
}

/** @deprecated class alias since 1.44 */
class_alias( IForeignRepoWithDB::class, 'IForeignRepoWithDB' );
