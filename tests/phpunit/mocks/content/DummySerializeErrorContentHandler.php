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
 */

use MediaWiki\Content\Content;
use MediaWiki\Exception\MWContentSerializationException;

/**
 * A dummy content handler that will throw on an attempt to serialize content.
 */
class DummySerializeErrorContentHandler extends DummyContentHandlerForTesting {

	/** @inheritDoc */
	public function __construct( $dataModel ) {
		parent::__construct( $dataModel, [ "testing-serialize-error" ] );
	}

	/**
	 * @see ContentHandler::unserializeContent
	 *
	 * @param string $blob
	 * @param string|null $format
	 *
	 * @return Content
	 */
	public function unserializeContent( $blob, $format = null ) {
		throw new MWContentSerializationException( 'Could not unserialize content' );
	}

	/**
	 * @see ContentHandler::supportsDirectEditing
	 *
	 * @return bool
	 *
	 * @todo Should this be in the parent class?
	 */
	public function supportsDirectApiEditing() {
		return true;
	}

}
