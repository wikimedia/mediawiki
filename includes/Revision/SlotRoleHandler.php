<?php
/**
 * This file is part of MediaWiki.
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

namespace MediaWiki\Revision;

use MediaWiki\Linker\LinkTarget;

/**
 * SlotRoleHandler instances are used to declare the existence and behavior of slot roles.
 * Most importantly, they control which content model can be used for the slot, and how it is
 * represented in the rendered verswion of page content.
 *
 * @since 1.33
 */
class SlotRoleHandler {

	/**
	 * @var string
	 */
	private $role;

	/**
	 * @var array
	 * @see getOutputLayoutHints
	 */
	private $layout = [
		'display' => 'section', // use 'none' to suppress
		'region' => 'center',
		'placement' => 'append'
	];

	/**
	 * @var string
	 */
	private $contentModel;

	/**
	 * @param string $role The name of the slot role defined by this SlotRoleHandler. See
	 *        SlotRoleRegistry::defineRole for more information.
	 * @param string $contentModel The default content model for this slot. As per the default
	 *        implementation of isAllowedModel(), also the only content model allowed for the
	 *        slot. Subclasses may however handle default and allowed models differently.
	 * @param array $layout Layout hints, for use by RevisionRenderer. See getOutputLayoutHints.
	 */
	public function __construct( $role, $contentModel, $layout = [] ) {
		$this->role = $role;
		$this->contentModel = $contentModel;
		$this->layout = array_merge( $this->layout, $layout );
	}

	/**
	 * @return string The role this SlotRoleHandler applies to
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * Layout hints for use while laying out the combined output of all slots, typically by
	 * RevisionRenderer. The layout hints are given as an associative array. Well-known keys
	 * to use:
	 *
	 * * "display": how the output of this slot should be represented. Supported values:
	 *   - "section": show as a top level section of the region.
	 *   - "none": do not show at all
	 *   Further values that may be supported in the future include "box" and "banner".
	 * * "region": in which region of the page the output should be placed. Supported values:
	 *   - "center": the central content area.
	 *   Further values that may be supported in the future include "top" and "bottom", "left"
	 *   and "right", "header" and "footer".
	 * * "placement": placement relative to other content of the same area.
	 *   - "append": place at the end, after any output processed previously.
	 *   Further values that may be supported in the future include "prepend". A "weight" key
	 *   may be introduced for more fine grained control.
	 *
	 * @return array an associative array of hints
	 */
	public function getOutputLayoutHints() {
		return $this->layout;
	}

	/**
	 * The message key for the translation of the slot name.
	 *
	 * @return string
	 */
	public function getNameMessageKey() {
		return 'slot-name-' . $this->role;
	}

	/**
	 * Determines the content model to use per default for this slot on the given page.
	 *
	 * The default implementation always returns the content model provided to the constructor.
	 * Subclasses may base the choice on default model on the page title or namespace.
	 * The choice should not depend on external state, such as the page content.
	 *
	 * @param LinkTarget $page
	 *
	 * @return string
	 */
	public function getDefaultModel( LinkTarget $page ) {
		return $this->contentModel;
	}

	/**
	 * Determines whether the given model can be used on this slot on the given page.
	 *
	 * The default implementation checks whether $model is the content model provided to the
	 * constructor. Subclasses may allow other models and may base the decision on the page title
	 * or namespace. The choice should not depend on external state, such as the page content.
	 *
	 * @note This should be checked when creating new revisions. Existing revisions
	 *       are not guaranteed to comply with the return value.
	 *
	 * @param string $model
	 * @param LinkTarget $page
	 *
	 * @return bool
	 */
	public function isAllowedModel( $model, LinkTarget $page ) {
		return ( $model === $this->contentModel );
	}

	/**
	 * Whether this slot should be considered when determining whether a page should be counted
	 * as an "article" in the site statistics.
	 *
	 * For a page to be considered countable, one of the page's slots must return true from this
	 * method, and Content::isCountable() must return true for the content of that slot.
	 *
	 * The default implementation always returns false.
	 *
	 * @return string
	 */
	public function supportsArticleCount() {
		return false;
	}

}
