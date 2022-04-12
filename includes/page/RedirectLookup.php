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
 * @author Derick Alangi
 */

namespace MediaWiki\Page;

use MediaWiki\Linker\LinkTarget;

/**
 * Service for resolving a wiki page redirect.
 *
 * Default implementation is RedirectStore.
 *
 * @unstable
 * @since 1.38
 */
interface RedirectLookup {
	/**
	 * Get the redirect destination.
	 *
	 * @since 1.38
	 * @param PageIdentity $page
	 * @return LinkTarget|null Returns null if this page is not a redirect
	 * @throws PageAssertionException If page does not represent an editable page
	 */
	public function getRedirectTarget( PageIdentity $page ): ?LinkTarget;
}
