<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\Status\Status;
use UploadBase;
use UploadFromUrl;

/**
 * Upload a file by URL, via the jobqueue.
 */
class UploadFromUrlJob extends Job implements GenericParameterJob {
	use UploadJobTrait;

	public function __construct( array $params ) {
		// @TODO: fix the invokation of Job::__construct in the parent class
		parent::__construct( 'UploadFromUrl', $params );
		$this->removeDuplicates = true;
		$this->user = null;
		$this->cacheKey = UploadFromUrl::getCacheKey( $this->params );
	}

	/**
	 * Deduplicate on title, url alone.
	 *
	 * Please note that this could cause some
	 * edge case failure, when the image at the
	 * same remote url is changed before the first upload
	 * is ran.
	 *
	 * @return array
	 */
	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		if ( is_array( $info['params'] ) ) {
			$info['params'] = [ 'url' => $info['params']['url'], 'title' => $info['params']['filename'] ];
		}

		return $info;
	}

	protected function getUpload(): UploadBase {
		if ( $this->upload === null ) {
			$this->upload = new UploadFromUrl;
			$this->upload->initialize( $this->params['filename'], $this->params['url'] );
		}
		return $this->upload;
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
			'url' => $this->params['url'],
			'filename' => $this->params['filename'],
			'user' => $this->user->getName(),
		];
	}

}

/** @deprecated class alias since 1.44 */
class_alias( UploadFromUrlJob::class, 'UploadFromUrlJob' );
