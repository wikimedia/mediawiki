<?php

namespace MediaWiki\Storage\Transaction;

use Exception;
use Wikimedia\Assert\Assert;

/**
 * A simple transaction manager based on callbacks.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class TransactionController implements Transaction, TransactionBuilder {

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
	private $prepareCallbacks = [];

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
		// if the transaction was already begun, run the callback immediately
		if ( $this->state === 'begun' ) {
			call_user_func( $callback, $this );
			return;
		}

		$this->assertState( 'init', __FUNCTION__ );
		Assert::parameterType( 'callback', $callback, '$callback' );
		Assert::parameterType( 'string', $method, '$method' );

		$this->beginCallbacks[] = [ $callback, $method ];
	}

	public function onPrepareDo( $callback, $method ) {
		$this->assertState( 'init|begun', __FUNCTION__ );
		Assert::parameterType( 'callback', $callback, '$callback' );
		Assert::parameterType( 'string', $method, '$method' );

		$this->prepareCallbacks[] = [ $callback, $method ];
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
			while ( $callbackInfo = array_shift( $this->beginCallbacks ) ) {
				list( $callback, $method ) = $callbackInfo;
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

	private function prepare() {
		$this->assertState( 'begun', __FUNCTION__ );

		$count = 0;
		$method = '';
		try {
			while ( $callbackInfo = array_pop( $this->prepareCallbacks ) ) {
				list( $callback, $method ) = $callbackInfo;
				call_user_func( $callback, $this );
				$count++;
			}
		} catch ( Exception $ex ) {
			wfDebugLog( __CLASS__, "Transaction {$this->name} prepare failed after $count "
				. "callbacks at callback for $method!" );

			$this->rollback();
			throw $ex;
		}

		$this->state = 'prepared';
	}

	public function commit() {
		$this->assertState( 'begun', __FUNCTION__ );

		$this->prepare();

		$count = 0;
		$method = '';
		try {
			while ( $callbackInfo = array_pop( $this->commitCallbacks ) ) {
				list( $callback, $method ) = $callbackInfo;
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
		while ( $callbackInfo = array_pop( $this->abortCallbacks ) ) {
			list( $callback, $method ) = $callbackInfo;
			try {
				call_user_func( $callback, $this );
			} catch ( Exception $ex ) {
				// This shouldn't happen. Complain and keep trying!
				wfLogWarning( "Rollback failed for $method on transaction {$this->name}! "
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
