<?php

/**
 * Job for email notification mails
 *
 * @ingroup JobQueue
 */
class UploadFromUrlJob extends Job {

	public function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'uploadFromUrl', $title, $params, $id );
	}

	public function run() {
		global $wgUser;

		if ( $this->params['userID'] ) {
			$wgUser = User::newFromId( $this->params['userID'] );
		} else {
			$wgUser = new User;
		}
		$wgUser->mEffectiveGroups[] = 'sysop';
		$wgUser->mRights = null;

		$upload = new UploadFromUrl();
		$upload->initializeFromJob( $this );

		return $upload->doUpload();
	}
}
