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

namespace MediaWiki\FileRepo;

use Closure;
use LogicException;
use MediaWiki\FileRepo\File\ForeignDBFile;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory.
 *
 * @ingroup FileRepo
 */
class ForeignDBViaLBRepo extends LocalRepo implements IForeignRepoWithDB {
	/** @var array */
	protected $fileFactory = [ ForeignDBFile::class, 'newFromTitle' ];

	/** @var array */
	protected $fileFromRowFactory = [ ForeignDBFile::class, 'newFromRow' ];

	/**
	 * @param array|null $info
	 */
	public function __construct( $info ) {
		parent::__construct( $info );
		'@phan-var array $info';
		$this->dbDomain = $info['wiki'];
		$this->hasAccessibleSharedCache = $info['hasSharedCache'];
	}

	/** @inheritDoc */
	public function getPrimaryDB() {
		return $this->getDbProvider()->getPrimaryDatabase( $this->dbDomain );
	}

	/** @inheritDoc */
	public function getReplicaDB() {
		return $this->getDbProvider()->getReplicaDatabase( $this->dbDomain );
	}

	/**
	 * @return Closure
	 */
	protected function getDBFactory() {
		return function ( $index ) {
			if ( $index == DB_PRIMARY ) {
				return $this->getDbProvider()->getPrimaryDatabase( $this->dbDomain );
			} else {
				return $this->getDbProvider()->getReplicaDatabase( $this->dbDomain );
			}
		};
	}

	protected function getDbProvider(): IConnectionProvider {
		return MediaWikiServices::getInstance()->getConnectionProvider();
	}

	protected function assertWritableRepo(): never {
		throw new LogicException( static::class . ': write operations are not supported.' );
	}

	/** @inheritDoc */
	public function getInfo() {
		return FileRepo::getInfo();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ForeignDBViaLBRepo::class, 'ForeignDBViaLBRepo' );
