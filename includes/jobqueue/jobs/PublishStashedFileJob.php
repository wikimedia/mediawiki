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
 * @ingroup Upload
 * @ingroup JobQueue
 */
use Wikimedia\ScopedCallback;

/**
 * Upload a file from the upload stash into the local file repo.
 *
 * @ingroup Upload
 * @ingroup JobQueue
 */
class PublishStashedFileJob extends Job {
	public function __construct( Title $title, array $params ) {
		parent::__construct( 'PublishStashedFile', $title, $params );
		$this->removeDuplicates = true;
	}

	public function run() {
		$scope = RequestContext::importScopedSession( $this->params['session'] );
		$this->addTeardownCallback( function () use ( &$scope ) {
			ScopedCallback::consume( $scope ); // T126450
		} );

		$context = RequestContext::getMain();
		$user = $context->getUser();
		try {
			if ( !$user->isLoggedIn() ) {
				$this->setLastError( "Could not load the author user from session." );

				return false;
			}

			UploadBase::setSessionStatus(
				$user,
				$this->params['filekey'],
				[ 'result' => 'Poll', 'stage' => 'publish', 'status' => Status::newGood() ]
			);

			$upload = new UploadFromStash( $user );
			// @todo initialize() causes a GET, ideally we could frontload the antivirus
			// checks and anything else to the stash stage (which includes concatenation and
			// the local file is thus already there). That way, instead of GET+PUT, there could
			// just be a COPY operation from the stash to the public zone.
			$upload->initialize( $this->params['filekey'], $this->params['filename'] );

			// Check if the local file checks out (this is generally a no-op)
			$verification = $upload->verifyUpload();
			if ( $verification['status'] !== UploadBase::OK ) {
				$status = Status::newFatal( 'verification-error' );
				$status->value = [ 'verification' => $verification ];
				UploadBase::setSessionStatus(
					$user,
					$this->params['filekey'],
					[ 'result' => 'Failure', 'stage' => 'publish', 'status' => $status ]
				);
				$this->setLastError( "Could not verify upload." );

				return false;
			}

			// Upload the stashed file to a permanent location
			$status = $upload->performUpload(
				$this->params['comment'],
				$this->params['text'],
				$this->params['watch'],
				$user,
				isset( $this->params['tags'] ) ? $this->params['tags'] : []
			);
			if ( !$status->isGood() ) {
				UploadBase::setSessionStatus(
					$user,
					$this->params['filekey'],
					[ 'result' => 'Failure', 'stage' => 'publish', 'status' => $status ]
				);
				$this->setLastError( $status->getWikiText( false, false, 'en' ) );

				return false;
			}

			// Build the image info array while we have the local reference handy
			$apiMain = new ApiMain(); // dummy object (XXX)
			$imageInfo = $upload->getImageInfo( $apiMain->getResult() );

			// Cleanup any temporary local file
			$upload->cleanupTempFile();

			// Cache the info so the user doesn't have to wait forever to get the final info
			UploadBase::setSessionStatus(
				$user,
				$this->params['filekey'],
				[
					'result' => 'Success',
					'stage' => 'publish',
					'filename' => $upload->getLocalFile()->getName(),
					'imageinfo' => $imageInfo,
					'status' => Status::newGood()
				]
			);
		} catch ( Exception $e ) {
			UploadBase::setSessionStatus(
				$user,
				$this->params['filekey'],
				[
					'result' => 'Failure',
					'stage' => 'publish',
					'status' => Status::newFatal( 'api-error-publishfailed' )
				]
			);
			$this->setLastError( get_class( $e ) . ": " . $e->getMessage() );
			// To prevent potential database referential integrity issues.
			// See bug 32551.
			MWExceptionHandler::rollbackMasterChangesAndLog( $e );

			return false;
		}

		return true;
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		if ( is_array( $info['params'] ) ) {
			$info['params'] = [ 'filekey' => $info['params']['filekey'] ];
		}

		return $info;
	}

	public function allowRetries() {
		return false;
	}
}
