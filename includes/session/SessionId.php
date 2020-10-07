<?php
/**
 * MediaWiki session ID holder
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
 * @ingroup Session
 */

namespace MediaWiki\Session;

/**
 * Value object holding the session ID in a manner that can be globally
 * updated.
 *
 * This class exists because we want WebRequest to refer to the session, but it
 * can't hold the Session itself due to issues with circular references and it
 * can't just hold the ID as a string because we need to be able to update the
 * ID when SessionBackend::resetId() is called.
 *
 * @newable
 *
 * @ingroup Session
 * @since 1.27
 */
final class SessionId {
	/** @var string */
	private $id;

	/**
	 * @stable to call
	 *
	 * @param string $id
	 */
	public function __construct( $id ) {
		$this->id = $id;
	}

	/**
	 * Get the ID
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set the ID
	 * @internal For use by \MediaWiki\Session\SessionManager only
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	public function __toString() {
		return $this->id;
	}

}
