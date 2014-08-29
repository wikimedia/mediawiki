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
	public function __construct( $title, $params ) {
		parent::__construct( 'ThumbnailRender', $title, $params );
	}

	public function run() {
		global $wgUploadThumbnailRenderMethod;

		$transformParams = $this->params['transformParams'];

		$file = wfLocalFile( $this->title );

		if ( $file && $file->exists() ) {
			if ( $wgUploadThumbnailRenderMethod === 'jobqueue' ) {
				$thumb = $file->transform( $transformParams, File::RENDER_NOW );

				if ( $thumb && !$thumb->isError() ) {
					return true;
				} else {
					$this->setLastError( __METHOD__ . ': thumbnail couln\'t be generated' );
					return false;
				}
			} elseif ( $wgUploadThumbnailRenderMethod === 'curl' ) {
				$status = $this->hitThumbUrl( $file, $transformParams );

				if ( $status === 200 || $status === 301 || $status === 302 ) {
					return true;
				} elseif ( $status ) {
					// Note that this currently happens (500) when requesting sizes larger then or
					// equal to the original, which is harmless.
					$this->setLastError( __METHOD__ . ': incorrect HTTP status ' . $status );
					return false;
				} else {
					$this->setLastError( __METHOD__ . ': cURL failure' );
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

	protected function hitThumbUrl( $file, $transformParams ) {
		global $wgUploadThumbnailRenderCurlCustomHost, $wgUploadThumbnailRenderCurlCustomDomain;

		$ch = curl_init();

		if ( !$ch ) {
			return false;
		}

		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'HEAD' );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );

		if ( $wgUploadThumbnailRenderCurlCustomHost ) {
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Host: ' . $wgUploadThumbnailRenderCurlCustomHost ) );
		}

		$thumbName = $file->thumbName( $transformParams );
		$thumbUrl = $file->getThumbUrl( $thumbName );

		if ( $wgUploadThumbnailRenderCurlCustomDomain ) {
			$thumbUrl = 'http://' . $wgUploadThumbnailRenderCurlCustomDomain . $thumbUrl;
		} else {
			$thumbUrl = wfExpandUrl( $thumbUrl, PROTO_RELATIVE );
		}

		wfDebug( __METHOD__ . ' hitting url: ' . $thumbUrl . "\n" );

		curl_setopt( $ch, CURLOPT_URL, $thumbUrl );

		if ( !curl_exec( $ch ) ) {
			return false;
		}

		$status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		curl_close( $ch );

		return $status;
	}
}
