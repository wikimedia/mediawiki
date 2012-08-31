<?php
/**
 * Classes for URI-related handling
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 */

/**
 * Object representing the query component of a URI.
 *
 * @since 1.23
 * @author Tyler Romeo
 */
abstract class UriQuery implements Serializable
{
	/**
	 * Make a new query object. Default functionality is to call
	 * UriQuery::setQuery to do so.
	 *
	 * @param array $query Query to wrap
	 */
	public function __construct( $query ) {
		$this->setQuery( $query );
	}

	/**
	 * Get the query as a string.
	 *
	 * @return string
	 */
	abstract public function getQueryString();

	/**
	 * Set the query using the given input.
	 *
	 * @param mixed $query Query to set (string/array/object)
	 */
	abstract public function setQuery( $query );

	/**
	 * Add more information to the query.
	 *
	 * @param mixed $parameters Data to add (string/array/object)
	 */
	abstract public function extendQuery( $parameters );

	/**
	 * Convenience function to convert to string. Default
	 * functionality is to call UriQuery::getQueryString.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getQueryString();
	}

	function serialize() {
		return $this->getQueryString();
	}

	function unserialize( $serialized ) {
		$this->setQuery( $serialized );
	}
}