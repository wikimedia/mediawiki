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

use MediaWiki\MediaWikiServices;

/**
 * External storage using HTTP requests.
 *
 * Example class for HTTP accessible external objects.
 * Only supports reading, not storing.
 *
 * @see ExternalStoreAccess
 * @ingroup ExternalStorage
 */
class ExternalStoreHttp extends ExternalStoreMedium {
	/** @inheritDoc */
	public function fetchFromURL( $url ) {
		return MediaWikiServices::getInstance()->getHttpRequestFactory()->
			get( $url, [], __METHOD__ );
	}

	/** @inheritDoc */
	public function store( $location, $data ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( "ExternalStoreHttp is read-only and does not support store()." );
	}

	/** @inheritDoc */
	public function isReadOnly( $location ) {
		return true;
	}
}
