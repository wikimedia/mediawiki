<?php
/**
 * Job for asynchronous upload-by-url.
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
 * @ingroup JobQueue
 */

/**
 * Job for asynchronous upload-by-url.
 *
 * This job is in fact an interface to UploadFromUrl, which is designed such
 * that it does not require any globals. If it does, fix it elsewhere, do not
 * add globals in here.
 *
 * @ingroup JobQueue
 */
class UploadFromUrlJob extends Job {
	const SESSION_KEYNAME = 'wsUploadFromUrlJobData';

	/**
	 * @var UploadFromUrl
	 */
	public $upload;

	/**
	 * @var User
	 */
	protected $user;

	public function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'uploadFromUrl', $title, $params, $id );
	}

	public function run() {
		global $wgCopyUploadAsyncTimeout;
		# Initialize this object and the upload object
		$this->upload = new UploadFromUrl();
		$this->upload->initialize(
			$this->title->getText(),
			$this->params['url'],
			false
		);
		$this->user = User::newFromName( $this->params['userName'] );

		# Fetch the file
		$opts = array();
		if ( $wgCopyUploadAsyncTimeout ) {
			$opts['timeout'] = $wgCopyUploadAsyncTimeout;
		}
		$status = $this->upload->fetchFile( $opts );
		if ( !$status->isOk() ) {
			$this->leaveMessage( $status );
			return true;
		}

		# Verify upload
		$result = $this->upload->verifyUpload();
		if ( $result['status'] != UploadBase::OK ) {
			$status = $this->upload->convertVerifyErrorToStatus( $result );
			$this->leaveMessage( $status );
			return true;
		}

		# Check warnings
		if ( !$this->params['ignoreWarnings'] ) {
			$warnings = $this->upload->checkWarnings();
			if ( $warnings ) {

				# Stash the upload
				$key = $this->upload->stashFile();

				if ( $this->params['leaveMessage'] ) {
					$this->user->leaveUserMessage(
						wfMessage( 'upload-warning-subj' )->text(),
						wfMessage( 'upload-warning-msg',
							$key,
							$this->params['url'] )->text()
					);
				} else {
					wfSetupSession( $this->params['sessionId'] );
					$this->storeResultInSession( 'Warning',
						'warnings', $warnings );
					session_write_close();
				}

				return true;
			}
		}

		# Perform the upload
		$status = $this->upload->performUpload(
			$this->params['comment'],
			$this->params['pageText'],
			$this->params['watch'],
			$this->user
		);
		$this->leaveMessage( $status );
		return true;

	}

	/**
	 * Leave a message on the user talk page or in the session according to
	 * $params['leaveMessage'].
	 *
	 * @param $status Status
	 */
	protected function leaveMessage( $status ) {
		if ( $this->params['leaveMessage'] ) {
			if ( $status->isGood() ) {
				$this->user->leaveUserMessage( wfMessage( 'upload-success-subj' )->text(),
					wfMessage( 'upload-success-msg',
						$this->upload->getTitle()->getText(),
						$this->params['url']
					)->text() );
			} else {
				$this->user->leaveUserMessage( wfMessage( 'upload-failure-subj' )->text(),
					wfMessage( 'upload-failure-msg',
						$status->getWikiText(),
						$this->params['url']
					)->text() );
			}
		} else {
			wfSetupSession( $this->params['sessionId'] );
			if ( $status->isOk() ) {
				$this->storeResultInSession( 'Success',
					'filename', $this->upload->getLocalFile()->getName() );
			} else {
				$this->storeResultInSession( 'Failure',
					'errors', $status->getErrorsArray() );
			}
			session_write_close();
		}
	}

	/**
	 * Store a result in the session data. Note that the caller is responsible
	 * for appropriate session_start and session_write_close calls.
	 *
	 * @param string $result the result (Success|Warning|Failure)
	 * @param string $dataKey the key of the extra data
	 * @param $dataValue Mixed: the extra data itself
	 */
	protected function storeResultInSession( $result, $dataKey, $dataValue ) {
		$session =& self::getSessionData( $this->params['sessionKey'] );
		$session['result'] = $result;
		$session[$dataKey] = $dataValue;
	}

	/**
	 * Initialize the session data. Sets the intial result to queued.
	 */
	public function initializeSessionData() {
		$session =& self::getSessionData( $this->params['sessionKey'] );
		$$session['result'] = 'Queued';
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public static function &getSessionData( $key ) {
		if ( !isset( $_SESSION[self::SESSION_KEYNAME][$key] ) ) {
			$_SESSION[self::SESSION_KEYNAME][$key] = array();
		}
		return $_SESSION[self::SESSION_KEYNAME][$key];
	}
}
