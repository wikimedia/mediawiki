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
				$file->transform( $transformParams, File::RENDER_NOW );
			} elseif ( $wgUploadThumbnailRenderMethod === 'curl' ) {
				$this->hitThumbUrl( $file, $transformParams );
			}
		}

		return true;
	}

	protected function hitThumbUrl( $file, $transformParams ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'HEAD' );
		curl_setopt( $ch, CURLOPT_HEADER, true );

		$thumbName = $file->thumbName( $transformParams );
		$thumbUrl = $file->getThumbUrl( $thumbName );
		$thumbUrl = wfExpandUrl( $thumbUrl, PROTO_RELATIVE );

		curl_setopt( $ch, CURLOPT_URL, $thumbUrl );

		curl_exec( $ch );
		curl_close( $ch );
	}
}
