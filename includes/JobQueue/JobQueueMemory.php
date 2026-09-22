<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue;

use ArrayIterator;
use Wikimedia\Iterators\MappedIterator;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * PHP memory-backed job queue storage, for testing.
 *
 * JobQueueGroup does not remember every queue instance, so statically track it here.
 *
 * @since 1.27
 * @ingroup JobQueue
 */
class JobQueueMemory extends JobQueue {
	/** @var array[] */
	protected static $data = [];

	public function __construct( array $params ) {
		$params['localClusterCache'] = new HashBagOStuff();

		parent::__construct( $params );
	}

	/** @inheritDoc */
	protected function doBatchPush( array $jobs, $flags ) {
		$unclaimed =& $this->getQueueData( 'unclaimed', [] );

		foreach ( $jobs as $job ) {
			if ( $job->ignoreDuplicates() ) {
				$sha1 = sha1( serialize( $job->getDeduplicationInfo() ) );
				if ( !isset( $unclaimed[$sha1] ) ) {
					$unclaimed[$sha1] = $job;
				}
			} else {
				$unclaimed[] = $job;
			}
		}
	}

	/** @inheritDoc */
	protected function supportedOrders() {
		return [ 'random', 'timestamp', 'fifo' ];
	}

	/** @inheritDoc */
	protected function optimalOrder() {
		return 'fifo';
	}

	/** @inheritDoc */
	protected function doIsEmpty() {
		return ( $this->doGetSize() == 0 );
	}

	/** @inheritDoc */
	protected function doGetSize() {
		$unclaimed = $this->getQueueData( 'unclaimed' );

		return $unclaimed ? count( $unclaimed ) : 0;
	}

	/** @inheritDoc */
	protected function doGetAcquiredCount() {
		$claimed = $this->getQueueData( 'claimed' );

		return $claimed ? count( $claimed ) : 0;
	}

	/** @inheritDoc */
	protected function doPop() {
		if ( $this->doGetSize() == 0 ) {
			return false;
		}

		$unclaimed =& $this->getQueueData( 'unclaimed' );
		$claimed =& $this->getQueueData( 'claimed', [] );

		if ( $this->order === 'random' ) {
			$key = array_rand( $unclaimed );
		} else {
			$key = array_key_first( $unclaimed );
		}

		$spec = $unclaimed[$key];
		unset( $unclaimed[$key] );
		$claimed[] = $spec;

		$job = $this->jobFromSpecInternal( $spec );

		$job->setMetadata( 'claimId', array_key_last( $claimed ) );

		return $job;
	}

	/** @inheritDoc */
	protected function doAck( RunnableJob $job ) {
		if ( $this->getAcquiredCount() == 0 ) {
			return;
		}

		$claimed =& $this->getQueueData( 'claimed' );
		unset( $claimed[$job->getMetadata( 'claimId' )] );
	}

	/** @inheritDoc */
	protected function doDelete() {
		if ( isset( self::$data[$this->type][$this->domain] ) ) {
			unset( self::$data[$this->type][$this->domain] );
			if ( !self::$data[$this->type] ) {
				unset( self::$data[$this->type] );
			}
		}
	}

	/** @inheritDoc */
	public function getAllQueuedJobs() {
		$unclaimed = $this->getQueueData( 'unclaimed' );
		return $unclaimed ?
			new MappedIterator( $unclaimed, $this->jobFromSpecInternal( ... ) ) :
			new ArrayIterator( [] );
	}

	/** @inheritDoc */
	public function getAllAcquiredJobs() {
		$claimed = $this->getQueueData( 'claimed' );
		return $claimed ?
			new MappedIterator( $claimed, $this->jobFromSpecInternal( ... ) ) :
			new ArrayIterator( [] );
	}

	/**
	 * @param IJobSpecification $spec
	 * @return RunnableJob
	 */
	public function jobFromSpecInternal( IJobSpecification $spec ) {
		return $this->factoryJob( $spec->getType(), $spec->getParams() );
	}

	/**
	 * @param string $field
	 * @param mixed|null $init
	 *
	 * @return mixed
	 */
	private function &getQueueData( $field, $init = null ) {
		if ( !isset( self::$data[$this->type][$this->domain][$field] ) ) {
			if ( $init !== null ) {
				self::$data[$this->type][$this->domain][$field] = $init;
			} else {
				return $init;
			}
		}

		return self::$data[$this->type][$this->domain][$field];
	}
}

/** @deprecated class alias since 1.44 */
class_alias( JobQueueMemory::class, 'JobQueueMemory' );
