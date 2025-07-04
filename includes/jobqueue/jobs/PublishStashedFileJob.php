<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
