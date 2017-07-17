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


namespace MediaWiki\Edit;


/**
 * Container for variables related to an attempted edit
 *
 * @since 1.30
 */
class EditAttempt {

	/**
	 * @var \Content
	 */
	public $content;

	/**
	 * @var string
	 */
	public $summary;

	/**
	 * @var \WebRequest
	 */
	public $request;

	/**
	 * @var \User
	 */
	public $user;

	/**
	 * @var \WikiPage
	 */
	public $wikiPage;

	/**
	 * @var \Title
	 */
	public $title;
}
