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

class RotateFileTransform extends FileTransform {

	/** @var int */
	protected $rotation;

	public function __construct( $title = null, $user = null, $rotation = null ) {
		parent::__construct( $title, $user );
		$this->op = 'rotate';
		$this->params = array(
			'rotation' => intval( $rotation )
		);
	}

	public function loadFromRow( stdClass $row ) {
		parent::loadFromRow( $row );
	}

	/**
	 * @return Status
	 */
	public function isValid() {
		$status = parent::isValid();
		$rotation = $this->params['rotation'];

		if ( !( $rotation === 90 || $rotation === 180 || $rotation === 270 ) ) {
			$status->fatal( 'file_transform_rotate_bad_value' );
		}

		if ( !( $this->file->getHandler() && $this->file->getHandler()->canRotate() ) ) {
			$status->fatal( 'file_transform_rotate_cannot_rotate' );
		}
		return $status;
	}

	/*
	 * @return Status
	 */
	protected function transform() {
		$status = Status::newGood();
		$rotation = $this->params['rotation'];

		$srcPath = $this->file->getLocalRefPath();
		if ( $srcPath === false ) {
			$status->fatal( 'file_transform_file_missing' );
			return $status;
		}

		$ext = strtolower( pathinfo( "$srcPath", PATHINFO_EXTENSION ) );
		$tmpFile = TempFSFile::factory( 'rotate_', $ext );
		$dstPath = $tmpFile->getPath();
		$err = $this->file->getHandler()->rotate( $this->file, array(
			"srcPath" => $srcPath,
			"dstPath" => $dstPath,
			"rotation" => $rotation
		) );
		if ( $err ) {
			$status->fatal( 'file_transform_rotate_error' );
			return $status;
		}

		$comment = wfMessage(
			'rotate-comment'
		)->numParams( $rotation )->inContentLanguage()->text();

		$status->merge( $this->file->upload( $dstPath,
			$comment, $comment, 0, false, false, $this->user ) );

		return $status;
	}
}
