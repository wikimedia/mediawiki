<?php
/**
 * Base code for update jobs that do something with some secondary
 * data extracted from article.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Deferred;

/**
 * Abstract base class for update jobs that do something with some secondary
 * data extracted from article.
 *
 * @stable to extend
 */
abstract class DataUpdate implements DeferrableUpdate {
	/** @var mixed Result from LBFactory::getEmptyTransactionTicket() */
	protected $ticket;
	/** @var string Short update cause action description */
	protected $causeAction = 'unknown';
	/** @var string Short update cause user description */
	protected $causeAgent = 'unknown';

	/**
	 * @stable to call
	 */
	public function __construct() {
		// noop
	}

	/**
	 * @param mixed $ticket Result of getEmptyTransactionTicket()
	 * @since 1.28
	 */
	public function setTransactionTicket( $ticket ) {
		$this->ticket = $ticket;
	}

	/**
	 * @param string $action Action type
	 * @param string $user User name
	 */
	public function setCause( $action, $user ) {
		$this->causeAction = $action;
		$this->causeAgent = $user;
	}

	/**
	 * @return string
	 */
	public function getCauseAction() {
		return $this->causeAction;
	}

	/**
	 * @return string
	 */
	public function getCauseAgent() {
		return $this->causeAgent;
	}

}

/** @deprecated class alias since 1.42 */
class_alias( DataUpdate::class, 'DataUpdate' );
