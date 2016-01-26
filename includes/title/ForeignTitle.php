<?php
/**
 * A structure to hold the title of a page on a foreign MediaWiki installation
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
 * @author This, that and the other
 */

/**
 * A simple, immutable structure to hold the title of a page on a foreign
 * MediaWiki installation.
 */
class ForeignTitle {
	/**
	 * @var int|null
	 * Null if we don't know the namespace ID (e.g. interwiki links)
	 */
	private $namespaceId;
	/** @var string */
	private $namespaceName;
	/** @var string */
	private $pageName;

	/**
	 * Creates a new ForeignTitle object.
	 *
	 * @param int|null $namespaceId Null if the namespace ID is unknown (e.g.
	 * interwiki links)
	 * @param string $namespaceName
	 * @param string $pageName
	 */
	public function __construct( $namespaceId, $namespaceName, $pageName ) {
		if ( is_null( $namespaceId ) ) {
			$this->namespaceId = null;
		} else {
			$this->namespaceId = intval( $namespaceId );
		}
		$this->namespaceName = str_replace( ' ', '_', $namespaceName );
		$this->pageName = str_replace( ' ', '_', $pageName );
	}

	/**
	 * Do we know the namespace ID of the page on the foreign wiki?
	 * @return bool
	 */
	public function isNamespaceIdKnown() {
		return !is_null( $this->namespaceId );
	}

	/**
	 * @return int
	 * @throws MWException If isNamespaceIdKnown() is false, it does not make
	 * sense to call this function.
	 */
	public function getNamespaceId() {
		if ( is_null( $this->namespaceId ) ) {
			throw new MWException(
				"Attempted to call getNamespaceId when the namespace ID is not known" );
		}
		return $this->namespaceId;
	}

	/** @return string */
	public function getNamespaceName() {
		return $this->namespaceName;
	}

	/** @return string */
	public function getText() {
		return $this->pageName;
	}

	/** @return string */
	public function getFullText() {
		$result = '';
		if ( $this->namespaceName ) {
			$result .= $this->namespaceName . ':';
		}
		$result .= $this->pageName;
		return $result;
	}

	/**
	 * Returns a string representation of the title, for logging. This is purely
	 * informative and must not be used programmatically. Use the appropriate
	 * ImportTitleFactory to generate the correct string representation for a
	 * given use.
	 *
	 * @return string
	 */
	public function __toString() {
		$name = '';
		if ( $this->isNamespaceIdKnown() ) {
			$name .= '{ns' . $this->namespaceId . '}';
		} else {
			$name .= '{ns??}';
		}
		$name .= $this->namespaceName . ':' . $this->pageName;

		return $name;
	}
}
