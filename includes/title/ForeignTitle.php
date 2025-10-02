<?php
/**
 * A structure to hold the title of a page on a foreign MediaWiki installation
 *
 * @license GPL-2.0-or-later
 * @file
 * @author This, that and the other
 */

namespace MediaWiki\Title;

use RuntimeException;
use Stringable;

/**
 * A simple, immutable structure to hold the title of a page on a foreign
 * MediaWiki installation.
 */
class ForeignTitle implements Stringable {
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
		if ( $namespaceId === null ) {
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
		return $this->namespaceId !== null;
	}

	/**
	 * @note Callers should make sure that isNamespaceIdKnown() is true before calling this method.
	 * @return int
	 */
	public function getNamespaceId() {
		if ( $this->namespaceId === null ) {
			throw new RuntimeException(
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

/** @deprecated class alias since 1.41 */
class_alias( ForeignTitle::class, 'ForeignTitle' );
