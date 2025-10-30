<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Mail;

use MediaWiki\JobQueue\Job;
use MediaWiki\Title\Title;

/**
 * Send an arbitrary single email.
 *
 * This is kept for backwards-compatibility.
 *
 * @ingroup JobQueue
 */
class EmaillingJob extends Job {

	/** @var IEmailer */
	private $emailer;

	public function __construct( ?Title $title, array $params, IEmailer $emailer ) {
		parent::__construct( 'sendMail', Title::newMainPage(), $params );
		$this->emailer = $emailer;
	}

	/** @inheritDoc */
	public function run() {
		$status = $this->emailer->send(
			[ $this->params['to'] ],
			$this->params['from'],
			$this->params['subj'],
			$this->params['body'],
			null,
			[ 'replyTo' => $this->params['replyto'] ]
		);

		return $status->isOK();
	}
}

/** @deprecated class alias since 1.45 */
class_alias( EmaillingJob::class, 'EmaillingJob' );
