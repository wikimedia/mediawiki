<?php
/**
 * Helper class for representing operations with transaction support.
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
 * @ingroup FileBackend
 */

use Wikimedia\AtEase\AtEase;

/**
 * Store a file into the backend from a file on the file system.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class StoreFileOp extends FileOp {
	protected function allowedParams() {
		return [
			[ 'src', 'dst' ],
			[ 'overwrite', 'overwriteSame', 'headers' ],
			[ 'src', 'dst' ]
		];
	}

	protected function doPrecheck( array &$predicates ) {
		$status = StatusValue::newGood();

		// Check if the source file exists in the file system and is not too big
		if ( !is_file( $this->params['src'] ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );

			return $status;
		}
		// Check if the source file is too big
		$maxBytes = $this->backend->maxFileSizeInternal();
		if ( filesize( $this->params['src'] ) > $maxBytes ) {
			$status->fatal( 'backend-fail-maxsize', $this->params['dst'], $maxBytes );

			return $status;
		}
		// Check if an incompatible destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()

		// Update file existence predicates if the operation is expected to be allowed to run
		if ( $status->isOK() ) {
			$predicates[self::ASSUMED_EXISTS][$this->params['dst']] = true;
			$predicates[self::ASSUMED_SIZE][$this->params['dst']] = $this->sourceSize;
			$predicates[self::ASSUMED_SHA1][$this->params['dst']] = $this->sourceSha1;
		}

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		if ( $this->overwriteSameCase ) {
			$status = StatusValue::newGood(); // nothing to do
		} else {
			// Store the file at the destination
			$status = $this->backend->storeInternal( $this->setFlags( $this->params ) );
		}

		return $status;
	}

	protected function getSourceSize() {
		AtEase::suppressWarnings();
		$size = filesize( $this->params['src'] );
		AtEase::restoreWarnings();

		return $size;
	}

	protected function getSourceSha1Base36() {
		AtEase::suppressWarnings();
		$hash = sha1_file( $this->params['src'] );
		AtEase::restoreWarnings();
		if ( $hash !== false ) {
			$hash = Wikimedia\base_convert( $hash, 16, 36, 31 );
		}

		return $hash;
	}

	public function storagePathsChanged() {
		return [ $this->params['dst'] ];
	}
}
