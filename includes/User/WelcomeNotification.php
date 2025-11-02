<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Notification\AgentAware;
use MediaWiki\Notification\Notification;

class WelcomeNotification extends Notification implements AgentAware {

	public function __construct( private readonly UserIdentity $agent ) {
		parent::__construct( 'welcome' );
	}

	public function getAgent(): UserIdentity {
		return $this->agent;
	}

}
