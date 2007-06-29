<?php

/**
 * Job for email notification mails
 */
class EnotifNotifyJob extends Job {

	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'enotifNotify', $title, $params, $id );
	}

	function run() {
		$enotif = new EmailNotification();
		$enotif->actuallyNotifyOnPageChange(
			User::newFromName( $this->params['editor'], false ),
				$this->title,
				$this->params['timestamp'],
				$this->params['summary'],
				$this->params['minorEdit'],
				$this->params['oldid']
		);
		return true;
	}
	
}

