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
		// Check if the source file exists on the file system
		if ( !is_file( $this->params['src'] ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );

			return $status;
			// Check if the source file is too big
		} elseif ( filesize( $this->params['src'] ) > $this->backend->maxFileSizeInternal() ) {
			$status->fatal( 'backend-fail-maxsize',
				$this->params['dst'], $this->backend->maxFileSizeInternal() );
			$status->fatal( 'backend-fail-store', $this->params['src'], $this->params['dst'] );

			return $status;
			// Check if a file can be placed/changed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-store', $this->params['src'], $this->params['dst'] );

			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		if ( !$this->overwriteSameCase ) {
			// Store the file at the destination
			return $this->backend->storeInternal( $this->setFlags( $this->params ) );
		}

		return StatusValue::newGood();
	}

	protected function getSourceSha1Base36() {
		Wikimedia\suppressWarnings();
		$hash = sha1_file( $this->params['src'] );
		Wikimedia\restoreWarnings();
		if ( $hash !== false ) {
			$hash = Wikimedia\base_convert( $hash, 16, 36, 31 );
		}

		return $hash;
	}

	public function storagePathsChanged() {
		return [ $this->params['dst'] ];
	}
}
