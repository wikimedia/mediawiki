<?php
/**
 * A value object representing page identity.
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
 */

namespace MediaWiki\Storage;

use MediaWiki\Linker\LinkTarget;
use TitleValue;

/**
 * A value object representing page identity.
 *
 * @since 1.31
 */
class PageIdentityValue implements PageIdentity {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var LinkTarget
	 */
	private $asLinkTarget;

	/**
	 * @param int $id The page id, with 0 indicating that the page does not exist.
	 * @param int $namespace The namespace ID. This is not validated.
	 * @param string $dbkey The page title in valid DBkey form. No normalization is applied.
	 *
	 * @return PageIdentity
	 */
	public static function newFromDBKey( $id, $namespace, $dbkey ) {
		return new self( $id, new TitleValue( $namespace, $dbkey ) );
	}

	/**
	 * @param int $id The page id, with 0 indicating that the page does not exist.
	 * @param LinkTarget $asLinkTarget
	 */
	public function __construct( $id, LinkTarget $asLinkTarget ) {
		$this->id = $id;
		$this->asLinkTarget = $asLinkTarget;
	}

	/**
	 * @return bool Whether the page exists
	 */
	public function exists() {
		return $this->getId() > 0;
	}

	/**
	 * @return int The page ID. May be 0 if the page does not exist.
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return LinkTarget A LinkTarget giving the title and namespace of the page.
	 */
	public function getAsLinkTarget() {
		return $this->asLinkTarget;
	}

	/**
	 * Get the namespace index.
	 *
	 * @see LinkTarget::getNamespace()
	 *
	 * @return int Namespace index
	 */
	public function getNamespace() {
		return $this->asLinkTarget->getNamespace();
	}

	/**
	 * Convenience function to test if it is in the namespace
	 *
	 * @see LinkTarget::inNamespace()
	 *
	 * @param int $ns
	 * @return bool
	 */
	public function inNamespace( $ns ) {
		return $this->asLinkTarget->inNamespace( $ns );
	}

	/**
	 * Returns the page's title in database key form (with underscores), without namespace prefix.
	 *
	 * @see LinkTarget::getDBkey()
	 *
	 * @return string Main part of the link, with underscores (for use in href attributes)
	 */
	public function getTitleDBkey() {
		return $this->asLinkTarget->getDBkey();
	}

	/**
	 * Returns the page's title in text form (with spaces), without namespace prefix.
	 *
	 * @see LinkTarget::getText()
	 *
	 * @return string
	 */
	public function getTitleText() {
		return $this->asLinkTarget->getText();
	}

	/**
	 * Returns an informative human readable representation of the page,
	 * for use in logging and debugging.
	 *
	 * @return string
	 */
	public function __toString() {
		return "page #{$this->id} ($this->asLinkTarget)";
	}

}
