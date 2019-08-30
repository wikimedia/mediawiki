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
 * Change metadata for a file at the given storage path in the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class DescribeFileOp extends FileOp {
	protected function allowedParams() {
		return [ [ 'src' ], [ 'headers' ], [ 'src' ] ];
	}

	protected function doPrecheck( array &$predicates ) {
		$status = StatusValue::newGood();

		// Check source file existence
		$srcExists = $this->fileExists( $this->params['src'], $predicates );
		if ( $srcExists === false ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );

			return $status;
		} elseif ( $srcExists === FileBackend::EXISTENCE_ERROR ) {
			$status->fatal( 'backend-fail-stat', $this->params['src'] );

			return $status;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['src']] = $srcExists;
		$predicates['sha1'][$this->params['src']] =
			$this->fileSha1( $this->params['src'], $predicates );

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		// Update the source file's metadata
		return $this->backend->describeInternal( $this->setFlags( $this->params ) );
	}

	public function storagePathsChanged() {
		return [ $this->params['src'] ];
	}
}
