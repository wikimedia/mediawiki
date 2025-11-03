<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\JobSpecification;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\RevertedTagUpdate;

/**
 * Job for deferring the execution of RevertedTagUpdate.
 *
 * @internal For use by \MediaWiki\Storage\RevertedTagUpdate
 * @since 1.36
 * @ingroup JobQueue
 * @author Ostrzyciel
 */
class RevertedTagUpdateJob extends Job implements GenericParameterJob {

	/**
	 * Returns a JobSpecification for this job.
	 *
	 * @param int $revertRevisionId
	 * @param EditResult $editResult
	 *
	 * @return JobSpecification
	 */
	public static function newSpec(
		int $revertRevisionId,
		EditResult $editResult
	): JobSpecification {
		return new JobSpecification(
			'revertedTagUpdate',
			[
				'revertId' => $revertRevisionId,
				'editResult' => $editResult->jsonSerialize()
			]
		);
	}

	/**
	 * @param array $params
	 * @phan-param array{revertId:int,editResult:array} $params
	 */
	public function __construct( array $params ) {
		parent::__construct( 'revertedTagUpdate', $params );
	}

	/**
	 * Unpacks the job arguments and runs the update.
	 *
	 * @return bool
	 */
	public function run() {
		$services = MediaWikiServices::getInstance();
		$editResult = EditResult::newFromArray(
			$this->params['editResult']
		);

		$update = new RevertedTagUpdate(
			$services->getRevisionStore(),
			LoggerFactory::getInstance( 'RevertedTagUpdate' ),
			$services->getChangeTagsStore(),
			$services->getConnectionProvider(),
			new ServiceOptions(
				RevertedTagUpdate::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$this->params['revertId'],
			$editResult
		);

		$update->doUpdate();
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RevertedTagUpdateJob::class, 'RevertedTagUpdateJob' );
