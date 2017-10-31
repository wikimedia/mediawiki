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
 * Interface for a component that controls page display beased on a user request.
 *
 * This interface was designed to cover the part of the old Article class that
 * is concerned with controlling page display.
 *
 * @since 1.31
 */
interface PageDisplayController {

	/**
	 * @return int The oldid of the article that is to be shown, 0 for the current revision
	 */
	public function getOldID();

	/**
	 * Returns true if the currently-referenced revision is the current edit
	 * to this page (and it exists).
	 * @return bool
	 */
	public function isCurrent();

	/**
	 * Get the fetched Revision object depending on request parameters or null
	 * on failure.
	 *
	 * @return Revision|null
	 */
	public function getRevisionFetched();

	/**
	 * Use this to fetch the rev ID used on page views
	 *
	 * @return int Revision ID of last article revision
	 */
	public function getRevIdFetched();

	/**
	 * Get the robot policy to be used for the current view
	 * @param string $action The action= GET parameter
	 * @param ParserOutput|null $pOutput
	 * @return array The policy that should be set
	 * @todo actions other than 'view'
	 */
	public function getRobotPolicy( $action, $pOutput = null );

	/**
	 * Get the page this view was redirected from
	 * @return Title|null
	 */
	public function getRedirectedFrom();

}
