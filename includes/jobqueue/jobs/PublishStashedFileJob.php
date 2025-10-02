<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @defgroup JobQueue JobQueue
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\Status\Status;
use UploadBase;
use UploadFromStash;

/**
 * Upload a file from the upload stash into the local file repo.
 *
 * @ingroup Upload
 * @ingroup JobQueue
 */
class PublishStashedFileJob extends Job implements GenericParameterJob {
	use UploadJobTrait;

	public function __construct( array $params ) {
		parent::__construct( 'PublishStashedFile', $params );
		$this->removeDuplicates = true;
		$this->initialiseUploadJob( $this->params['filekey'] );
	}

	/** @inheritDoc */
	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		if ( is_array( $info['params'] ) ) {
			$info['params'] = [ 'filekey' => $info['params']['filekey'] ];
		}

		return $info;
	}

	/**
	 * Get the parameters for job logging
	 *
	 * @param Status[] $status
	 * @return array
	 */
	public function logJobParams( $status ): array {
		return [
			'stage' => $status['stage'] ?? '-',
			'result' => $status['result'] ?? '-',
			'status' => (string)( $status['status'] ?? '-' ),
			'filekey' => $this->params['filekey'],
			'filename' => $this->params['filename'],
			'user' => $this->user->getName(),
		];
	}

	/**
	 * getter for the upload
	 *
	 * @return UploadFromStash
	 */
	protected function getUpload(): UploadBase {
		if ( $this->upload === null ) {
			$this->upload = new UploadFromStash( $this->user );
			// @todo initialize() causes a GET, ideally we could frontload the antivirus
			// checks and anything else to the stash stage (which includes concatenation and
			// the local file is thus already there). That way, instead of GET+PUT, there could
			// just be a COPY operation from the stash to the public zone.
			$this->upload->initialize( $this->params['filekey'], $this->params['filename'] );
		}
		return $this->upload;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PublishStashedFileJob::class, 'PublishStashedFileJob' );
