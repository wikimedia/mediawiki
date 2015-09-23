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

				if ( $thumb && !$thumb->isError() ) {
					return true;
				} else {
					$this->setLastError( __METHOD__ . ': thumbnail couln\'t be generated' );
					return false;
				}
			} elseif ( $wgUploadThumbnailRenderMethod === 'http' ) {
				$thumbUrl = '';
				$status = $this->hitThumbUrl( $file, $transformParams, $thumbUrl );

				wfDebug( __METHOD__ . ": received status {$status}\n" );

				// 400 happens when requesting a size greater or equal than the original
				if ( $status === 200 || $status === 301 || $status === 302 || $status === 400 ) {
					return true;
				} elseif ( $status ) {
					$this->setLastError( __METHOD__ . ': incorrect HTTP status ' . $status . ' when hitting ' . $thumbUrl );
					return false;
				} else {
					$this->setLastError( __METHOD__ . ': HTTP request failure' );
					return false;
				}
			} else {
				$this->setLastError( __METHOD__ . ': unknown thumbnail render method ' . $wgUploadThumbnailRenderMethod );
				return false;
			}
		} else {
			$this->setLastError( __METHOD__ . ': file doesn\'t exist' );
			return false;
		}
	}

	protected function hitThumbUrl( $file, $transformParams, &$thumbUrl ) {
		global $wgUploadThumbnailRenderHttpCustomHost, $wgUploadThumbnailRenderHttpCustomDomain;

		$thumbName = $file->thumbName( $transformParams );
		$thumbUrl = $file->getThumbUrl( $thumbName );

		if ( $wgUploadThumbnailRenderHttpCustomDomain ) {
			$parsedUrl = wfParseUrl( $thumbUrl );

			if ( !$parsedUrl || !isset( $parsedUrl['path'] ) || !strlen( $parsedUrl['path'] ) ) {
				return false;
			}

			$thumbUrl = '//' . $wgUploadThumbnailRenderHttpCustomDomain . $parsedUrl['path'];
		}

		wfDebug( __METHOD__ . ": hitting url {$thumbUrl}\n" );

		$request = MWHttpRequest::factory( $thumbUrl,
			array( 'method' => 'HEAD', 'followRedirects' => true ),
			__METHOD__
		);

		if ( $wgUploadThumbnailRenderHttpCustomHost ) {
			$request->setHeader( 'Host', $wgUploadThumbnailRenderHttpCustomHost );
		}

		$status = $request->execute();

		return $request->getStatus();
	}
}
