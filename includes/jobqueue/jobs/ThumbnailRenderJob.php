<?php
/**
 * Job for asynchronous rendering of thumbnails.
 *
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
 * @ingroup JobQueue
 */

/**
 * Job for asynchronous rendering of thumbnails.
 *
 * @ingroup JobQueue
 */
class ThumbnailRenderJob extends Job {
	public function __construct( Title $title, array $params ) {
		parent::__construct( 'ThumbnailRender', $title, $params );
	}

	public function run() {
		global $wgUploadThumbnailRenderMethod;

		$transformParams = $this->params['transformParams'];

		$file = wfLocalFile( $this->title );
		$file->load( File::READ_LATEST );

		if ( $file && $file->exists() ) {
			if ( $wgUploadThumbnailRenderMethod === 'jobqueue' ) {
				$thumb = $file->transform( $transformParams, File::RENDER_NOW );

				if ( !$thumb || $thumb->isError() ) {
					if ( $thumb instanceof MediaTransformError ) {
						$this->setLastError( __METHOD__ . ': thumbnail couln\'t be generated:' .
							$thumb->toText() );
					} else {
						$this->setLastError( __METHOD__ . ': thumbnail couln\'t be generated' );
					}
					return false;
				}
				return true;
			} elseif ( $wgUploadThumbnailRenderMethod === 'http' ) {
				return $this->hitThumbUrl( $file, $transformParams );
			} else {
				$this->setLastError( __METHOD__ . ': unknown thumbnail render method ' .
					$wgUploadThumbnailRenderMethod );
				return false;
			}
		} else {
			$this->setLastError( __METHOD__ . ': file doesn\'t exist' );
			return false;
		}
	}

	/**
	 * @param LocalFile $file
	 * @param array $transformParams
	 * @return bool Success status (error will be set via setLastError() when false)
	 */
	protected function hitThumbUrl( LocalFile $file, $transformParams ) {
		global $wgUploadThumbnailRenderHttpCustomHost, $wgUploadThumbnailRenderHttpCustomDomain;

		$handler = $file->getHandler();
		if ( !$handler ) {
			$this->setLastError( __METHOD__ . ': could not get handler' );
			return false;
		} elseif ( !$handler->normaliseParams( $file, $transformParams ) ) {
			$this->setLastError( __METHOD__ . ': failed to normalize' );
			return false;
		}
		$thumbName = $file->thumbName( $transformParams );
		$thumbUrl = $file->getThumbUrl( $thumbName );

		if ( $thumbUrl === null ) {
			$this->setLastError( __METHOD__ . ': could not get thumb URL' );
			return false;
		}

		if ( $wgUploadThumbnailRenderHttpCustomDomain ) {
			$parsedUrl = wfParseUrl( $thumbUrl );

			if ( !$parsedUrl || !isset( $parsedUrl['path'] ) || !strlen( $parsedUrl['path'] ) ) {
				$this->setLastError( __METHOD__ . ": invalid thumb URL: $thumbUrl" );
				return false;
			}

			$thumbUrl = '//' . $wgUploadThumbnailRenderHttpCustomDomain . $parsedUrl['path'];
		}

		wfDebug( __METHOD__ . ": hitting url {$thumbUrl}\n" );

		// T203135 We don't wait for the request to complete, as this is mostly fire & forget.
		// Looking at the HTTP status of requests that take less than 1s is a sanity check.
		$request = MWHttpRequest::factory( $thumbUrl,
			[ 'method' => 'HEAD', 'followRedirects' => true, 'timeout' => 1 ],
			__METHOD__
		);

		if ( $wgUploadThumbnailRenderHttpCustomHost ) {
			$request->setHeader( 'Host', $wgUploadThumbnailRenderHttpCustomHost );
		}

		$status = $request->execute();
		$statusCode = $request->getStatus();
		wfDebug( __METHOD__ . ": received status {$statusCode}\n" );

		// 400 happens when requesting a size greater or equal than the original
		// TODO use proper error signaling. 400 could mean a number of other things.
		if ( $statusCode === 200 || $statusCode === 301 || $statusCode === 302 || $statusCode === 400 ) {
			return true;
		} elseif ( $statusCode ) {
			$this->setLastError( __METHOD__ . ": incorrect HTTP status $statusCode when hitting $thumbUrl" );
		} elseif ( $status->hasMessage( 'http-timed-out' ) ) {
			// T203135 we ignore timeouts, as it would be inefficient for this job to wait for
			// minutes for the slower thumbnails to complete.
			return true;
		} else {
			$this->setLastError( __METHOD__ . ': HTTP request failure: '
				. Status::wrap( $status )->getWikiText( null, null, 'en' ) );
		}
		return false;
	}

	/**
	 * Whether to retry the job.
	 * @return bool
	 */
	public function allowRetries() {
		// ThumbnailRenderJob is a warmup for the thumbnails cache,
		// so loosing it is not a problem. Most times the job fails
		// for non-renderable or missing images which will not be fixed
		// by a retry, but will create additional load on the renderer.
		return false;
	}
}
