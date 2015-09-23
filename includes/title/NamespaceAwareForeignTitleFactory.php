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
 * @license GPL 2+
 */

/**
 * A parser that translates page titles on a foreign wiki into ForeignTitle
 * objects, using information about the namespace setup on the foreign site.
 */
class NamespaceAwareForeignTitleFactory implements ForeignTitleFactory {
	/**
	 * @var array
	 */
	protected $foreignNamespaces;
	/**
	 * @var array
	 */
	private $foreignNamespacesFlipped;

	/**
	 * Normalizes an array name for $foreignNamespacesFlipped.
	 * @param string $name
	 * @return string
	 */
	private function normalizeNamespaceName( $name ) {
		return strtolower( str_replace( ' ', '_', $name ) );
	}

	/**
	 * @param array|null $foreignNamespaces An array 'id' => 'name' which contains
	 * the complete namespace setup of the foreign wiki. Such data could be
	 * obtained from siteinfo/namespaces in an XML dump file, or by an action API
	 * query such as api.php?action=query&meta=siteinfo&siprop=namespaces. If
	 * this data is unavailable, use NaiveForeignTitleFactory instead.
	 */
	public function __construct( $foreignNamespaces ) {
		$this->foreignNamespaces = $foreignNamespaces;
		if ( !is_null( $foreignNamespaces ) ) {
			$this->foreignNamespacesFlipped = array();
			foreach ( $foreignNamespaces as $id => $name ) {
				$newKey = self::normalizeNamespaceName( $name );
				$this->foreignNamespacesFlipped[$newKey] = $id;
			}
		}
	}

	/**
	 * Creates a ForeignTitle object based on the page title, and optionally the
	 * namespace ID, of a page on a foreign wiki. These values could be, for
	 * example, the <title> and <ns> attributes found in an XML dump.
	 *
	 * @param string $title The page title
	 * @param int|null $ns The namespace ID, or null if this data is not available
	 * @return ForeignTitle
	 */
	public function createForeignTitle( $title, $ns = null ) {
		// Export schema version 0.5 and earlier (MW 1.18 and earlier) does not
		// contain a <ns> tag, so we need to be able to handle that case.
		if ( is_null( $ns ) ) {
			return self::parseTitleNoNs( $title );
		} else {
			return self::parseTitleWithNs( $title, $ns );
		}
	}

	/**
	 * Helper function to parse the title when the namespace ID is not specified.
	 *
	 * @param string $title
	 * @return ForeignTitle
	 */
	protected function parseTitleNoNs( $title ) {
		$pieces = explode( ':', $title, 2 );
		$key = self::normalizeNamespaceName( $pieces[0] );

		// Does the part before the colon match a known namespace? Check the
		// foreign namespaces
		$isNamespacePartValid = isset( $this->foreignNamespacesFlipped[$key] );

		if ( count( $pieces ) === 2 && $isNamespacePartValid ) {
			list( $namespaceName, $pageName ) = $pieces;
			$ns = $this->foreignNamespacesFlipped[$key];
		} else {
			$namespaceName = '';
			$pageName = $title;
			$ns = 0;
		}

		return new ForeignTitle( $ns, $namespaceName, $pageName );
	}

	/**
	 * Helper function to parse the title when the namespace value is known.
	 *
	 * @param string $title
	 * @param int $ns
	 * @return ForeignTitle
	 */
	protected function parseTitleWithNs( $title, $ns ) {
		$pieces = explode( ':', $title, 2 );

		if ( isset( $this->foreignNamespaces[$ns] ) ) {
			$namespaceName = $this->foreignNamespaces[$ns];
		} else {
			$namespaceName = $ns == '0' ? '' : $pieces[0];
		}

		// We assume that the portion of the page title before the colon is the
		// namespace name, except in the case of namespace 0
		if ( $ns != '0' ) {
			$pageName = $pieces[1];
		} else {
			$pageName = $title;
		}

		return new ForeignTitle( $ns, $namespaceName, $pageName );
	}
}
