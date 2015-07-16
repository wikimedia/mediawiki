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

abstract class FileTransform {
	/** @var int */
	protected $id;

	/** @var string */
	protected $timestamp;

	/** @var string */
	protected $op;

	/** @var File */
	protected $file;

	/** @var string */
	protected $fileTimestamp;

	/** @var User */
	protected $user;

	/**
	 * @param File $file
	 * @param User $user
	 */
	public function __construct( $file = null, $user = null ) {
		$this->file = $file;
		if ( $file ) {
			$this->fileTimestamp = $file->getTimestamp();
		}
		$this->user = $user;
	}

	/**
	 * @param int $id
	 * @return mixed FileTransform or null
	 */
	public static function newFromId( $id, $dbmode=DB_SLAVE ) {
		$db = wfGetDB( $dbmode );
		$row = $db->selectRow( 'file_transform',
			'*',
			array( 'ft_id' => $id ),
			__METHOD__
		);
		if ( $row ) {
			return self::newFromRow( $row );
		}
	}

	/**
	 * @param stdClass $row
	 * @return mixed FileTransform or null
	 */
	public static function newFromRow( stdClass $row ) {
		$classes = array(
			'rotate' => 'RotateFileTransform',
		);
		if ( array_key_exists( $row->ft_op, $classMap ) ) {
			$class = $classMap[$row->ft_op];
			$obj = new $class();
			$obj->loadFromRow( $row );
			return $obj;
		} else {
			// invalid op type!
			return null;
		}
	}

	/**
	 * @param stdClass $row
	 */
	public function loadFromRow( stdClass $row ) {
		$this->id = intval( $row->ft_id );
		$this->timestamp = wfTimestamp( TS_MW, $row->ft_timestamp );

		$title = Title::makeTitle( NS_FILE, $row->ft_img_name );
		$this->file = wfLocalFile( $title );
		$this->fileTimestamp = wfTimestamp( TS_MW, $row->ft_img_timestamp );

		$this->user = User::newFromId( $row->ft_user_id );

		$this->op = strval( $row->ft_op );
		// any other fields are specific to the op subtype
	}

	/**
	 * The id number of this pending file transform.
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * The timestamp this pending file transform was queued.
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * The file we're going to operate on.
	 * @return File
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * The current timestamp of the file at queueing time, for conflict detection.
	 * @return string
	 */
	public function getFileTimestamp() {
		return $this->fileTimestamp;
	}

	/**
	 * The operation subtype of the pending transform.
	 * @return string
	 */
	public function getOp() {
		return $this->op;
	}

	/**
	 * Perform validity and permission checks, then the actual operation!
	 * @return Status
	 */
	public function execute() {
		$status = $this->checkPermissions();

		if ( $status->isGood() ) {
			$status->add( $this->transform() );
		}

		return $status;
	}

	/**
	 * Perform validity and permission checks without the operation.
	 * @return Status
	 */
	public function isValid() {
		$status = Status::newGood();

		if ( !$this->file || !$this->user ) {
			$status->fatal( 'file_transform_invalid' );
		}
		if ( !$this->file->exists() ) {
			$status->fatal( 'file_transform_file_does_not_exist' );
		}
		if ( !$this->file->isLocal() ) {
			$status->fatal( 'file_transform_file_not_local' );
		}
		if ( $this->file->getTimestamp() != $this->fileTimestamp ) {
			$status->fatal( 'file_transform_upload_conflict' );
		}
		$status->merge( $this->checkPermissions() );

		return $status;
	}

	/**
	 * @return Status
	 */
	public function checkPermissions() {
		$status = Status::newGood();

		$title = $this->file->getTitle();
		$permissionErrors = array_merge(
			$title->getUserPermissionsErrors( 'edit', $this->user ),
			$title->getUserPermissionsErrors( 'upload', $this->user )
		);
		foreach ( $permissionErrors as $msgParams ) {
			call_user_func_array( array( $status, 'fatal' ), $msgParams );
		}

		return $status;
	}

	/**
	 * @return boolean
	 */
	public function insert() {
		$db = wfGetDB( DB_MASTER );
		$ok = $db->insert( 'file_transform', $this->insertFields(), __METHOD__ );
		if ( $ok ) {
			$this->id = $db->insertId();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return boolean
	 */
	public function delete() {
		$db = wfGetDB( DB_MASTER );
		return $db->delete( 'file_transform',
			array( 'ft_id' => $this->id ),
			__METHOD__
		);
	}

	/**
	 * Save this bad boy!
	 */
	public function queue() {
		$status = $this->isValid();

		if ( $status->isGood() && !$this->id ) {
			if ( !$this->insert() ) {
				$status->fatal( 'file_transform_db_failure' );
			}
		}

		if ( $status->isGood() ) {
			$job = new FileTransformJob( $this->file->getTitle(), array(
				'ft_id' => $this->id,
			) );
			JobQueueGroup::singleton()->push( $job );
		}

		return $status;
	}

	/**
	 * @return array
	 */
	protected function insertFields() {
		$db = wfGetDB( DB_MASTER );
		return array(
			'ft_timestamp' => $db->timestamp( $this->timestamp ),
			'ft_img_name' => $this->file->getName(),
			'ft_img_timestamp' => $this->file->getTimestamp(),
			'ft_user_id' => $this->user->getId(),
			'ft_op' => $this->op
		);
		// Other fields to be added by subclasses
	}

	/**
	 * The sausage is made...
	 * Subclasses must override this method.
	 * @return Status
	 */
	protected abstract function transform();
}
