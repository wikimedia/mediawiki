<?php
/**
 * Request-dependant objects containers.
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
 * @since 1.26
 *
 * @file
 */

interface MutableContext {
	/**
	 * Set the Config object
	 *
	 * @param Config $c
	 */
	public function setConfig( Config $c );

	/**
	 * Set the WebRequest object
	 *
	 * @param WebRequest $r
	 */
	public function setRequest( WebRequest $r );

	/**
	 * Set the Title object
	 *
	 * @param Title $t
	 */
	public function setTitle( Title $t );

	/**
	 * Set the WikiPage object
	 *
	 * @param WikiPage $p
	 */
	public function setWikiPage( WikiPage $p );

	/**
	 * Set the OutputPage object
	 *
	 * @param OutputPage $o
	 */
	public function setOutput( OutputPage $o );

	/**
	 * Set the User object
	 *
	 * @param User $u
	 */
	public function setUser( User $u );

	/**
	 * Set the Language object
	 *
	 * @param Language|string $l Language instance or language code
	 */
	public function setLanguage( $l );

	/**
	 * Set the Skin object
	 *
	 * @param Skin $s
	 */
	public function setSkin( Skin $s );

}
