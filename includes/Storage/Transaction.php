<?php

namespace MediaWiki\Storage;

use Exception;
use Wikimedia\Assert\Assert;

/**
 * A simple transaction manager based on callbacks.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class Transaction {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $state = 'init';

	/**
	 * @var array[] A list of [ $callback, $method ] pairs
	 */
	private $beginCallbacks = [];

	/**
	 * @var array[] A list of [ $callback, $method ] pairs
	 */
	private $commitCallbacks = [];

	/**
	 * @var array[] A list of [ $callback, $method ] pairs
	 */
	private $abortCallbacks = [];

	/**
	 * @param string $name Name to use in log messages
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	public function onBeginDo( $callback, $method ) {
		$this->assertState( 'init', __FUNCTION__ );
		Assert::parameterType( 'callback', $callback, '$callback' );
		Assert::parameterType( 'string', $method, '$method' );

		$this->beginCallbacks[] = [ $callback, $method ];
	}

	public function onCommitDo( $callback, $method ) {
		$this->assertState( 'init|begun', __FUNCTION__ );
		Assert::parameterType( 'callback', $callback, '$callback' );
		Assert::parameterType( 'string', $method, '$method' );

		$this->commitCallbacks[] = [ $callback, $method ];
	}

	public function onAbortDo( $callback, $method ) {
		$this->assertState( 'init|begun', __FUNCTION__ );
		Assert::parameterType( 'callback', $callback, '$callback' );
		Assert::parameterType( 'string', $method, '$method' );

		$this->abortCallbacks[] = [ $callback, $method ];
	}

	private function assertState( $expectedState, $action ) {
		$states = explode( '|', $expectedState );
		Assert::invariant(
			in_array( $this->state, $states ),
			'Action ' . $action . ' is only possible in ' . $expectedState . ' state'
		);
	}

	public function begin() {
		$this->assertState( 'init', __FUNCTION__ );

		$method = '';

		try {
			foreach ( $this->beginCallbacks as list( $callback, $method ) ) {
				call_user_func( $callback, $this );
			}
		} catch ( Exception $ex ) {
			wfDebugLog( __CLASS__, "Transaction {$this->name} begin failed "
				. "at callback for $method!" );

			$this->rollback();
			throw $ex;
		}

		$this->state = 'begun';
	}

	public function commit() {
		$this->assertState( 'begun', __FUNCTION__ );

		$count = 0;
		$method = '';
		try {
			foreach ( $this->commitCallbacks as list( $callback, $method ) ) {
				call_user_func( $callback, $this );
				$count++;
			}
		} catch ( Exception $ex ) {
			// This shouldn't happen. Still, clean up the best we can!
			wfLogWarning( "Transaction {$this->name} commit failed after $count "
				. "callbacks at callback for $method!" );

			$this->rollback();
			throw $ex;
		}

		$this->state = 'done';
	}

	public function abort() {
		$this->assertState( 'begun', __FUNCTION__ );

		$this->rollback();
	}

	private function rollback() {
		$count = 0;
		foreach ( $this->commitCallbacks as list( $callback, $method ) ) {
			try {
				call_user_func( $callback, $this );
				$count++;
			} catch ( Exception $ex ) {
				// This shouldn't happen. Still, clean up the best we can!
				wfLogWarning( "Rollback failed for $method on transation {$this->name}! "
					. $ex->getMessage() );
			}
		}

		$this->state = 'aborted';
	}

	public function __destruct() {
		if ( $this->state === 'begun' ) {
			wfLogWarning( "Transaction {$this->name} was left dirty, rolling back!" );
			$this->abort();
		}
	}

}
