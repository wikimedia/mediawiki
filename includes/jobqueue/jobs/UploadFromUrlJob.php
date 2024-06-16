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
 */

use MediaWiki\Status\Status;

/**
 * Upload a file by URL, via the jobqueue.
 *
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

	/**
	 * getter for the upload
	 *
	 * @return UploadBase
	 */
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
