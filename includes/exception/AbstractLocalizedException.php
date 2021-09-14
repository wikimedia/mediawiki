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

use MediaWiki\Message\Converter;
use Wikimedia\Message\MessageValue;

/**
 * @since 1.37
 * @ingroup Exception
 */
abstract class AbstractLocalizedException extends MWException implements ILocalizedException {

	/**
	 * Return a Message object for this exception
	 * @return Message
	 */
	abstract public function getMessageObject();

	/**
	 * Return a MessageValue object for this exception
	 * @return MessageValue
	 */
	public function getMessageValue() {
		$converter = new Converter();
		return $converter->convertMessage( $this->getMessageObject() );
	}
}
