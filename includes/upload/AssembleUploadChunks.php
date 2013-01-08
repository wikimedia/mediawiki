<?php
/**
 * Assemble the segments of a chunked upload.
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
set_time_limit( 3600 ); // 1 hour

/**
 * Assemble the segments of a chunked upload.
 *
 * @ingroup Maintenance
 */
class AssembleUploadChunks extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Re-assemble the segments of a chunked upload into a single file";
		$this->addOption( 'filename', "Desired file name", true, true );
		$this->addOption( 'filekey', "Upload stash file key", true, true );
		$this->addOption( 'userid', "Upload owner user ID", true, true );
		$this->addOption( 'sessionid', "Upload owner session ID", true, true );
	}

	public function execute() {
		$e = null;
		wfDebug( "Started assembly for file {$this->getOption( 'filename' )}\n" );
		wfSetupSession( $this->getOption( 'sessionid' ) );
		try {
			$user = User::newFromId( $this->getOption( 'userid' ) );
			if ( !$user ) {
				throw new MWException( "No user with ID " . $this->getOption( 'userid' ) . "." );
			}

			UploadBase::setSessionStatus(
				$this->getOption( 'filekey' ),
				array( 'result' => 'Poll', 'stage' => 'assembling', 'status' => Status::newGood() )
			);

			$upload = new UploadFromChunks( $user );
			$upload->continueChunks(
				$this->getOption( 'filename' ),
				$this->getOption( 'filekey' ),
				// @TODO: set User?
				RequestContext::getMain()->getRequest() // dummy request
			);

			// Combine all of the chunks into a local file and upload that to a new stash file
			$status = $upload->concatenateChunks();
			if ( !$status->isGood() ) {
				UploadBase::setSessionStatus(
					$this->getOption( 'filekey' ),
					array( 'result' => 'Failure', 'stage' => 'assembling', 'status' => $status )
				);
				session_write_close();
				$this->error( $status->getWikiText() . "\n", 1 ); // die
			}

			// We have a new filekey for the fully concatenated file
			$newFileKey = $upload->getLocalFile()->getFileKey();

			// Remove the old stash file row and first chunk file
			$upload->stash->removeFileNoAuth( $this->getOption( 'filekey' ) );

			// Build the image info array while we have the local reference handy
			$apiMain = new ApiMain(); // dummy object (XXX)
			$imageInfo = $upload->getImageInfo( $apiMain->getResult() );

			// Cleanup any temporary local file
			$upload->cleanupTempFile();

			// Cache the info so the user doesn't have to wait forever to get the final info
			UploadBase::setSessionStatus(
				$this->getOption( 'filekey' ),
				array(
					'result'    => 'Success',
					'stage'     => 'assembling',
					'filekey'   => $newFileKey,
					'imageinfo' => $imageInfo,
					'status'    => Status::newGood()
				)
			);
		} catch ( MWException $e ) {
			UploadBase::setSessionStatus(
				$this->getOption( 'filekey' ),
				array(
					'result' => 'Failure',
					'stage'  => 'assembling',
					'status' => Status::newFatal( 'api-error-stashfailed' )
				)
			);
		}
		session_write_close();
		if ( $e ) {
			throw $e;
		}
		wfDebug( "Finished assembly for file {$this->getOption( 'filename' )}\n" );
	}
}

$maintClass = "AssembleUploadChunks";
require_once( RUN_MAINTENANCE_IF_MAIN );
