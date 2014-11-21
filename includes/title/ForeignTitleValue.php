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
class ForeignTitleValue extends TitleValue {
	/**
	 * The internal value used for the namespace ID field when the namespace ID
	 * is not known.
	 */
	const UNKNOWN_NAMESPACE = -2147483648;

	/**
	 * @var string
	 */
	protected $namespaceName;

	/**
	 * Creates a new ForeignTitleValue object.
	 *
	 * @param int $namespaceId Pass ForeignTitleValue::UNKNOWN_NAMESPACE here if
	 * the namespace ID is unknown (for example, interwiki links)
	 * @param string $namespaceName
	 * @param string $pageName
	 */
	public function __construct( $namespaceId, $namespaceName, $pageName ) {
		parent::__construct( intval( $namespaceId ),
			str_replace( ' ', '_', $pageName ) );
		$this->namespaceName = $namespaceName;
	}

	/**
	 * Returns the current ForeignTitleValue object. ForeignTitleValue does not
	 * support fragments.
	 *
	 * @param string $fragment
	 * @return ForeignTitleValue
	 */
	public function createFragmentTitle( $fragment ) {
		return $this;
	}

	/** @return string */
	public function getNamespaceName() {
		return $this->namespaceName;
	}

	/** @return string */
	public function getFullText() {
		$result = '';
		if ( $this->namespaceName ) {
			$result .= $this->namespaceName . ':';
		}
		$result .= $this->getText();
		return $result;
	}
}
