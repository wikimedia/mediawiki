<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Notification\AgentAware;
use MediaWiki\Notification\Notification;

class WelcomeNotification extends Notification implements AgentAware {

	private UserIdentity $agent;

	public function __construct( UserIdentity $agent ) {
		$this->agent = $agent;
		parent::__construct( 'welcome' );
	}

	public function getAgent(): UserIdentity {
		return $this->agent;
	}

}
