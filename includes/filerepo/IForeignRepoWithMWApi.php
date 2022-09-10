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

/**
 * A foreign repo that implement support for API queries.
 *
 * Extension file repos should implement this if they support making API queries
 * against the foreign repos. Media handler extensions (e.g. TimedMediaHandler)
 * can look for this interface if they need to look up additional information.
 * However, media handler extensions are encouraged to only use direct api calls
 * as a last resort, and try to use other methods to get the information they
 * need instead.
 *
 * @since 1.38
 * @ingroup FileRepo
 * @stable to implement
 */
interface IForeignRepoWithMWApi {
	/**
	 * Make an API query in the foreign repo, caching results
	 *
	 * @note action=query, format=json, redirects=true and uselang are automatically set.
	 * @param array $query Fields to pass to the query
	 * @return array|null
	 * @since 1.38
	 */
	public function fetchImageQuery( $query );
}
