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
 * @since 1.18
 *
 * @author Happy-melon
 * @file
 */

/**
 * Interface for objects which can provide a context on request.
 */
interface IContextSource {

	/**
	 * Get the WebRequest object
	 *
	 * @return WebRequest
	 */
	public function getRequest();

	/**
	 * Get the Title object
	 *
	 * @return Title
	 */
	public function getTitle();

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage object
	 */
	public function getOutput();

	/**
	 * Get the User object
	 *
	 * @return User
	 */
	public function getUser();

	/**
	 * Get the Language object
	 *
	 * @deprecated 1.19 Use getLanguage instead
	 * @return Language
	 */
	public function getLang();

	/**
	 * Get the Language object
	 *
	 * @return Language
	 * @since 1.19
	 */
	public function getLanguage();

	/**
	 * Get the Skin object
	 *
	 * @return Skin
	 */
	public function getSkin();
}

