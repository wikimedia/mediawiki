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
