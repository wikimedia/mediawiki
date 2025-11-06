<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Title;

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
		if ( $foreignNamespaces !== null ) {
			$this->foreignNamespacesFlipped = [];
			foreach ( $foreignNamespaces as $id => $name ) {
				$newKey = self::normalizeNamespaceName( $name );
				$this->foreignNamespacesFlipped[$newKey] = $id;
			}
		}
	}

	/**
	 * Create a ForeignTitle object.
	 *
	 * Based on the page title and optionally the namespace ID, of a page on a foreign wiki.
	 * These values could be, for example, the `<title>` and `<ns>` attributes found in an
	 * XML dump.
	 *
	 * @param string $title The page title
	 * @param int|null $ns The namespace ID, or null if this data is not available
	 * @return ForeignTitle
	 */
	public function createForeignTitle( $title, $ns = null ) {
		// Export schema version 0.5 and earlier (MW 1.18 and earlier) does not
		// contain a <ns> tag, so we need to be able to handle that case.
		if ( $ns === null ) {
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
			[ $namespaceName, $pageName ] = $pieces;
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

		// Is $title of the form Namespace:Title (true), or just Title (false)?
		$titleIncludesNamespace = ( $ns != '0' && count( $pieces ) === 2 );

		if ( isset( $this->foreignNamespaces[$ns] ) ) {
			$namespaceName = $this->foreignNamespaces[$ns];
		} else {
			// If the foreign wiki is misconfigured, XML dumps can contain a page with
			// a non-zero namespace ID, but whose title doesn't contain a colon
			// (T114115). In those cases, output a made-up namespace name to avoid
			// collisions. The ImportTitleFactory might replace this with something
			// more appropriate.
			$namespaceName = $titleIncludesNamespace ? $pieces[0] : "Ns$ns";
		}

		// We assume that the portion of the page title before the colon is the
		// namespace name, except in the case of namespace 0.
		if ( $titleIncludesNamespace ) {
			$pageName = $pieces[1];
		} else {
			$pageName = $title;
		}

		return new ForeignTitle( $ns, $namespaceName, $pageName );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( NamespaceAwareForeignTitleFactory::class, 'NamespaceAwareForeignTitleFactory' );
