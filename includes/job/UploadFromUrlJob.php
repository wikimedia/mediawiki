<?php
/**
 * Job for asynchronous upload-by-url.
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
	public $upload;
	protected $user;

	public function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'uploadFromUrl', $title, $params, $id );
	}

	public function run() {
		# Until we find a way to store data in sessions, set leaveMessage to
		# true unconditionally
		$this->params['leaveMessage'] = true;
		# Similar for ignorewarnings. This is not really a good fallback, but
		# there is no easy way to get a wikitext formatted warning message to
		# show to the user
		$this->params['ignoreWarnings'] = true;
		
		# Initialize this object and the upload object
		$this->upload = new UploadFromUrl();
		$this->upload->initialize( 
			$this->title->getText(), 
			$this->params['url'],
			false
		);
		$this->user = User::newFromName( $this->params['userName'] );
		
		# Fetch the file
		$status = $this->upload->fetchFile();
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
				if ( $this->params['leaveMessage'] ) {
					$this->user->leaveUserMessage( 
						wfMsg( 'upload-warning-subj' ),
						wfMsg( 'upload-warning-msg', 
							$this->params['sessionKey'],
							$this->params['url'] )
					);
				} else {
					$this->storeResultInSession( 'Warning',
						'warnings', $warnings );
				}
				
				// FIXME: stash in session
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
				$this->user->leaveUserMessage( wfMsg( 'upload-success-subj' ),
					wfMsg( 'upload-success-msg', 
						$this->upload->getTitle()->getText(),
						$this->params['url'] 
					) );
			} else {
				$this->user->leaveUserMessage( wfMsg( 'upload-failure-subj' ),
					wfMsg( 'upload-failure-msg', 
						$status->getWikiText(),
						$this->params['url']
					) );
			}
		} else {
			if ( $status->isOk() ) {
				$this->storeResultInSession( 'Success', 
					'filename', $this->getLocalFile()->getName() );
			} else {
				$this->storeResultInSession( 'Failure',
					'errors', $status->getErrorsArray() );
			}
			
		}
	}

	/**
	 * Store a result in the session data
	 * THIS IS BROKEN. $_SESSION does not exist when using runJobs.php
	 * 
	 * @param $result string The result (Success|Warning|Failure)
	 * @param $dataKey string The key of the extra data
	 * @param $dataKey mixed The extra data itself
	 */
	protected function storeResultInSession( $result, $dataKey, $dataValue ) {
		$session &= $_SESSION[UploadBase::getSessionKeyname()][$this->params['sessionKey']];
		$session['result'] = $result;
		$session[$dataKey] = $dataValue;
	}
}
