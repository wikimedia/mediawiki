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
use MediaWiki\Page\PageIdentity;

/**
 * A SlotRoleHandler for providing basic functionality for undefined slot roles.
 *
 * This class is intended to be used when encountering slots with a role that used to be defined
 * by an extension, but no longer is backed by hany specific handler, since the extension in
 * question has been uninstalled. It may also be used for pages imported from another wiki.
 *
 * @since 1.33
 */
class FallbackSlotRoleHandler extends SlotRoleHandler {

	public function __construct( string $role ) {
		parent::__construct( $role, CONTENT_MODEL_UNKNOWN );
	}

	/**
	 * @param LinkTarget $page
	 *
	 * @return bool Always false, to prevent undefined slots from being used in new revisions.
	 */
	public function isAllowedOn( LinkTarget $page ) {
		return false;
	}

	/**
	 * @param string $model
	 * @param PageIdentity $page
	 *
	 * @return bool Always false, to prevent undefined slots from being used for
	 *         arbitrary content.
	 */
	public function isAllowedModel( $model, PageIdentity $page ) {
		return false;
	}

	public function getOutputLayoutHints() {
		// TODO: should we return [ 'display' => 'none'] here, causing undefined slots
		// to be hidden? We'd still need some place to surface the content of such
		// slots, see T209923.

		return parent::getOutputLayoutHints();
	}

}
