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

namespace MediaWiki\Page;

use MediaWiki\DAO\WikiAwareEntityTrait;
use Stringable;
use Wikimedia\Assert\Assert;
use Wikimedia\NonSerializable\NonSerializableTrait;

/**
 * Immutable value object representing a page reference.
 *
 * Instances of this class are expected to always represent a viewable pages, that is,
 * pages that can at least potentially be visited on the wiki.
 * This class may represent Special pages, but not interwiki links, section links, etc.
 *
 * Code that deserializes instances of PageReferenceValue must ensure that the original
 * meaning of the "local" Wiki ID is preserved: When an instance of PageReferenceValue
 * is created with self::LOCAL as the Wiki ID on one wiki, gets serialized,
 * stored, and later read and unserialized on another wiki, the value of the Wiki ID
 * must be adjusted to refer to the original wiki.
 *
 * @since 1.37
 */
class PageReferenceValue implements Stringable, PageReference {

	/* Use JSON, but beware the note on serialization above. */
	use NonSerializableTrait;
	use WikiAwareEntityTrait;

	/** @var int */
	private $namespace;

	/** @var string */
	private $dbKey;

	/** @var string|false */
	private $wikiId;

	/**
	 * @param int $namespace A valid namespace ID. Validation is the caller's responsibility!
	 * @param string $dbKey A valid DB key. Validation is the caller's responsibility!
	 * @param string|false $wikiId The Id of the wiki this page belongs to,
	 *        or self::LOCAL for the local wiki. The method {@link PageReferenceValue::localReference}
	 *        is available as a shorthand for local wikis (only requires 2 parameters).
	 */
	public function __construct( int $namespace, string $dbKey, $wikiId ) {
		$this->assertWikiIdParam( $wikiId );

		Assert::parameter( $dbKey !== '', '$dbKey', 'must not be empty' );

		// Replace spaces with underscores
		$dbKey = str_replace( ' ', '_', $dbKey );

		$this->wikiId = $wikiId;
		$this->namespace = $namespace;
		$this->dbKey = $dbKey;
	}

	/**
	 * Create PageReference for a local page.
	 *
	 * @param int $namespace
	 * @param string $dbKey
	 * @return PageReferenceValue
	 */
	public static function localReference( int $namespace, string $dbKey ): self {
		return new self( $namespace, $dbKey, self::LOCAL );
	}

	/**
	 * Get the ID of the wiki provided to the constructor.
	 *
	 * @return string|false
	 */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * @inheritDoc
	 *
	 * @return int
	 */
	public function getNamespace(): int {
		return $this->namespace;
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function getDBkey(): string {
		return $this->dbKey;
	}

	/**
	 * @inheritDoc
	 */
	public function isSamePageAs( PageReference $other ): bool {
		// NOTE: keep in sync with Title::isSamePageAs()!
		// NOTE: keep in sync with WikiPage::isSamePageAs()!
		return $this->getWikiId() === $other->getWikiId()
			&& $this->getNamespace() === $other->getNamespace()
			&& $this->getDBkey() === $other->getDBkey();
	}

	/**
	 * Returns a string representation of the title, for logging. This is purely informative
	 * and must not be used programmatically.
	 */
	public function __toString(): string {
		$s = '[' . $this->namespace . ':' . $this->dbKey . ']';

		if ( $this->wikiId ) {
			$s .= '@' . $this->wikiId;
		}

		return $s;
	}

}
