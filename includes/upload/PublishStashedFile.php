<?php
/**
 * Upload a file from the upload stash into the local file repo.
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
 * @ingroup Maintenance
 */
require_once( __DIR__ . '/../../maintenance/Maintenance.php' );

/**
 * Upload a file from the upload stash into the local file repo.
 *
 * @ingroup Maintenance
 */
class PublishStashedFile extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Upload stashed file into the local file repo";
		$this->addOption( 'filename', "Desired file name", true, true );
		$this->addOption( 'filekey', "Upload stash file key", true, true );
		$this->addOption( 'userid', "Upload owner user ID", true, true );
		$this->addOption( 'comment', "Upload comment", true, true );
		$this->addOption( 'text', "Upload description", true, true );
		$this->addOption( 'watch', "Whether the uploader should watch the page", true, true );
	}

	public function execute() {
		try {
			$user = User::newFromId( $this->getOption( 'userid' ) );
			if ( !$user ) {
				throw new MWException( "No user with ID " . $this->getOption( 'userid' ) . "." );
			}

			$upload = new UploadFromStash( $user );
			// @TODO: initialize() causes a GET, ideally we could frontload the antivirus
			// checks and anything else to the stash stage (which includes concatenation and
			// the local file is thus already there). That way, instead of GET+PUT, there could
			// just be a COPY operation from the stash to the public zone.
			$upload->initialize( $this->getOption( 'filekey' ), $this->getOption( 'filename' ) );

			// Check if the local file checks out (this is generally a no-op)
			$verification = $upload->verifyUpload();
			if ( $verification['status'] !== UploadBase::OK ) {
				$status = Status::newFatal( 'verification-error' );
				$status->value = array( 'verification' => $verification );
				UploadBase::setCurrentStatus(
					$this->getOption( 'filekey' ),
					array( 'result' => 'Failure', 'status' => $status )
				);
				$this->error( "Could not verify upload.\n", 1 ); // die
			}

			// Upload the stashed file to a permanent location
			$status = $upload->performUpload(
				$this->getOption( 'comment' ),
				$this->getOption( 'text' ),
				$this->getOption( 'watch' ),
				$this->getUser()
			);
			if ( !$status->isGood() ) {
				UploadBase::setCurrentStatus(
					$this->getOption( 'filekey' ),
					array( 'result' => 'Failure', 'status' => $status )
				);
				$this->error( $status->getWikiText() . "\n", 1 ); // die
			}

			// Build the image info array while we have the local reference handy
			$apiMain = new ApiMain(); // dummy object (XXX)
			$imageInfo = $upload->getImageInfo( $apiMain->getResult() );

			// Cleanup any temporary local file
			$upload->cleanupTempFile();

			// Cache the info so the user doesn't have to wait forever to get the final info
			UploadBase::setCurrentStatus(
				$this->getOption( 'filekey' ),
				array(
					'result'    => 'Success',
					'filename'  => $upload->getLocalFile()->getName(),
					'imageinfo' => $imageInfo,
					'status'    => Status::newGood()
				)
			);
		} catch ( MWException $e ) {
			UploadBase::setCurrentStatus(
				$this->getOption( 'filekey' ),
				array(
					'result' => 'Failure',
					'status' => Status::newFatal( 'api-error-stashfailed' )
				)
			);
			throw $e;
		}
	}
}

$maintClass = "PublishStashedFile";
require_once( RUN_MAINTENANCE_IF_MAIN );
