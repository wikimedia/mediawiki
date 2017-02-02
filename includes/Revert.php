<?php
/**
 * Object representing a revert
 *
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

class Revert {

	/** @var Revision */
	protected $revision;

	/** @var int */
	protected $revertedId;

	/** @var int */
	protected $restoredId;

	/** @var bool */
	protected $isExact;

	/** @var Revision */
	protected $restoredRevision = null;

	/**
	 * @param Revision $revision Revision inserted by the revert
	 * @param int $revertedId Id of the reverted revision
	 * @param int $restoredId Id of the restored revision
	 * @param bool|null $isExact If known, whether the revert is exact, as in a rollback, null
	 * if we don't know (in that case this will be determined by loading the restored revision)
	 */
	public function __construct( Revision $revision, $revertedId, $restoredId, $isExact = null ) {
		$this->revision = $revision;
		$this->revertedId = $revertedId;
		$this->restoredId = $restoredId;
		$this->isExact = $isExact;
	}

	/**
	 * Id of reverted revision
	 *
	 * @return int
	 */
	public function getRevertedId() {
		return $this->revertedId;
	}

	/**
	 * Id of restored revision
	 *
	 * @return int
	 */
	public function getRestoredId() {
		return $this->restoredId;
	}

	/**
	 * Whether the revert is exact,
	 * i.e. the contents of the revert revision and restored revision match
	 *
	 * @return bool
	 */
	public function isExact() {
		if ( $this->isExact === null ) {
			$restoredRev = $this->getRestoredRevision();
			$this->isExact = $restoredRev &&
				$this->revision->getContent()->equals( $restoredRev->getContent() );
		}
		return $this->isExact;
	}

	/**
	 * Fetches the restored revision
	 *
	 * @return Revision|null
	 */
	protected function getRestoredRevision() {
		if ( $this->restoredRevision === null ) {
			$restoredRev = Revision::newFromId( $this->restoredId );
			if ( !$restoredRev ) {
				// this may happen with quick undos in case of replication lag, try master...
				$restoredRev = Revision::newFromId( $this->restoredId, Revision::READ_LATEST );
			}
			$this->restoredRevision = $restoredRev;
		}
		return $this->restoredRevision;
	}

}
