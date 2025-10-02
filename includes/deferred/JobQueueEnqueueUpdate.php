<?php
/**
 * Handler for triggering the enqueuing of lazy-pushed jobs
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Deferred;

use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\JobQueue\IJobSpecification;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\MediaWikiServices;
use Throwable;
use Wikimedia\Assert\Assert;

/**
 * Enqueue lazy-pushed jobs that have accumulated from JobQueueGroup
 *
 * @ingroup JobQueue
 * @since 1.33
 */
class JobQueueEnqueueUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var IJobSpecification[][] */
	private $jobsByDomain;

	/** @var JobQueueGroupFactory */
	private $jobQueueGroupFactory;

	/**
	 * @param string $domain DB domain ID
	 * @param IJobSpecification[] $jobs
	 */
	public function __construct( string $domain, array $jobs ) {
		$this->jobsByDomain[$domain] = $jobs;
		// TODO Inject services, when DeferredUpdates supports DI
		$this->jobQueueGroupFactory = MediaWikiServices::getInstance()->getJobQueueGroupFactory();
	}

	/** @inheritDoc */
	public function merge( MergeableUpdate $update ) {
		/** @var self $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var self $update';

		foreach ( $update->jobsByDomain as $domain => $jobs ) {
			$this->jobsByDomain[$domain] = array_merge(
				$this->jobsByDomain[$domain] ?? [],
				$jobs
			);
		}
	}

	/** @inheritDoc */
	public function doUpdate() {
		foreach ( $this->jobsByDomain as $domain => $jobs ) {
			$group = $this->jobQueueGroupFactory->makeJobQueueGroup( $domain );
			try {
				$group->push( $jobs );
			} catch ( Throwable $e ) {
				// Get in as many jobs as possible and let other post-send updates happen
				MWExceptionHandler::logException( $e );
			}
		}
	}
}

/** @deprecated class alias since 1.42 */
class_alias( JobQueueEnqueueUpdate::class, 'JobQueueEnqueueUpdate' );
