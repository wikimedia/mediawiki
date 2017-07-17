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

use Content;
use WebRequest;
use User;
use WikiPage;
use Title;

/**
 * Container for variables related to an attempted edit
 *
 * @since 1.30
 */
class EditRequest {

	/**
	 * @var Content
	 */
	private $content;

	/**
	 * @var string
	 */
	private $summary;

	/**
	 * @var WebRequest
	 */
	private $request;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var WikiPage
	 */
	private $wikiPage;

	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @param array $info
	 */
	public function __construct( array $info ) {
		static $members = [
			'content', 'summary', 'request', 'user', 'wikiPage', 'title'
		];
		foreach ( $members as $var ) {
			$this->$var = $info[$var];
		}
	}

	/**
	 * @return Content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return WikiPage
	 */
	public function getWikiPage() {
		return $this->wikiPage;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}
}
