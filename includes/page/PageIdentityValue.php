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
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\NonSerializable\NonSerializableTrait;

/**
 * Immutable value object representing a page identity.
 *
 * Instances of this class are expected to always represent proper pages, that is,
 * pages that can at least potentially exist as editable pages on the wiki.
 * This class cannot represent Special pages, interwiki links, section links, etc.
 *
 * Code that deserializes instances of PageIdentityValue must ensure that the original
 * meaning of the "local" Wiki ID is preserved: When an instance of PageIdentityValue
 * is created with self::LOCAL as the Wiki ID on one wiki, gets serialized,
 * stored, and later read and unserialized on another wiki, the value of the Wiki ID
 * must be adjusted to refer to the original wiki.
 *
 * @since 1.36
 */
class PageIdentityValue implements ProperPageIdentity {

	/* Use JSON, but beware the note on serialization above. */
	use NonSerializableTrait;
	use WikiAwareEntityTrait;

	/** @var int */
	private $pageId;

	/** @var int */
	private $namespace;

	/** @var string */
	private $dbKey;

	/** @var bool|string */
	private $wikiId;

	/**
	 * @param int $pageId The ID of this page, or 0 if the page does not exist.
	 * @param int $namespace A valid namespace ID. Validation is the caller's responsibility!
	 * @param string $dbKey A valid DB key. Validation is the caller's responsibility!
	 * @param string|bool $wikiId The Id of the wiki this page belongs to,
	 *        or self::LOCAL for the local wiki.
	 */
	public function __construct( int $pageId, int $namespace, string $dbKey, $wikiId ) {
		Assert::parameter( $pageId >= 0, '$pageId', 'must not be negative' );
		Assert::parameter( $namespace >= 0, '$namespace', 'must not be negative' );
		$this->assertWikiIdParam( $wikiId );

		if ( $dbKey === '' ) {
			throw new ParameterAssertionException(
				'$dbKey',
				'PageIdentityValue cannot be created for an empty title.'
			);
		}

		// Don't be mad about spaces.
		$dbKey = str_replace( ' ', '_', $dbKey );

		// Not full validation, but catches commons issues:
		if ( preg_match( '/[\s#|]/', $dbKey ) ) {
			throw new ParameterAssertionException(
				'$dbKey',
				'PageIdentityValue contains a bad character: ' . $dbKey
			);
		}

		$this->pageId = $pageId;
		$this->wikiId = $wikiId;
		$this->namespace = $namespace;
		$this->dbKey = $dbKey;
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
	 * The numerical page ID provided to the constructor.
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 *        Omit if expecting the local wiki.
	 *
	 * @return int
	 */
	public function getId( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		return $this->pageId;
	}

	/**
	 * Returns whether the page currently exists.
	 * Returns true if getId() returns a value greater than zero.
	 * @return bool
	 */
	public function exists(): bool {
		return $this->getId( $this->wikiId ) > 0;
	}

	/**
	 * @return bool always true
	 */
	public function canExist(): bool {
		return true;
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
	 * Returns a string representation of the title, for logging. This is purely informative
	 * and must not be used programmatically.
	 *
	 * @return string
	 */
	public function __toString(): string {
		$name = '#' . $this->pageId;

		if ( $this->wikiId ) {
			$name .= '@' . $this->wikiId;
		}

		return $name . ' [' . $this->namespace . ':' . $this->dbKey . ']';
	}

	/**
	 * @param PageIdentity $other
	 *
	 * @return bool
	 */
	public function isSamePageAs( PageIdentity $other ) {
		// NOTE: keep in sync with Title::isSamePageAs()!
		// NOTE: keep in sync with WikiPage::isSamePageAs()!

		$wikiId = $this->getWikiId();
		if ( $other->getWikiId() !== $wikiId
			|| $other->getId( $wikiId ) !== $this->getId( $wikiId ) ) {
			return false;
		}

		if ( $this->getId( $wikiId ) === 0 ) {
			if ( $other->getNamespace() !== $this->getNamespace()
				|| $other->getDBkey() !== $this->getDBkey() ) {
				return false;
			}
		}

		return true;
	}

}
