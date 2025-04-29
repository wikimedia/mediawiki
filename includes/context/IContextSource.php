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

namespace MediaWiki\Context;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Language\LocalizationContext;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\CsrfTokenSetProvider;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Timing\Timing;

/**
 * Interface for objects which can provide a MediaWiki context on request
 *
 * Context objects contain request-dependent objects that manage the core
 * web request/response logic for essentially all requests to MediaWiki.
 * The contained objects include:
 *   a) Key objects that depend (for construction/loading) on the HTTP request
 *   b) Key objects used for response building and PHP session state control
 *   c) Performance metric deltas accumulated from request execution
 *   d) The site configuration object
 * All these objects are useful for the vast majority of MediaWiki requests.
 * The site configuration object is included on grounds of extreme
 * utility, even though it should not depend on the web request.
 *
 * More specifically, the scope of the context includes:
 *   a) Objects that represent the HTTP request/response and PHP session state
 *   b) Object representing the MediaWiki user (as determined by the HTTP request)
 *   c) Primary MediaWiki output builder objects (OutputPage, user skin object)
 *   d) The language object for the user/request
 *   e) The title and wiki page objects requested via URL (if any)
 *   f) Performance metric deltas accumulated from request execution
 *   g) The site configuration object
 *
 * This class is not intended as a service-locator nor a service singleton.
 * Objects that only depend on site configuration do not belong here (aside
 * from Config itself). Objects that represent persistent data stores do not
 * belong here either. Session state changes should only be propagated on
 * shutdown by separate persistence handler objects, for example.
 *
 * Must not be implemented directly by extensions, extend ContextSource instead.
 *
 * @since 1.18
 * @stable to type
 * @author Happy-melon
 */
interface IContextSource extends LocalizationContext, CsrfTokenSetProvider {

	/**
	 * @return WebRequest
	 */
	public function getRequest();

	/**
	 * @return Title|null
	 */
	public function getTitle();

	/**
	 * Check whether a WikiPage object can be obtained with getWikiPage().
	 *
	 * Callers should expect that an exception is thrown from getWikiPage()
	 * if this method returns false.
	 *
	 * @since 1.19
	 * @return bool
	 */
	public function canUseWikiPage();

	/**
	 * Get the WikiPage object.
	 *
	 * May throw an exception if there's no Title object set or the Title object
	 * belongs to a special namespace that doesn't have WikiPage, so use first
	 * canUseWikiPage() to check whether this method can be called safely.
	 *
	 * @since 1.19
	 * @return WikiPage
	 */
	public function getWikiPage();

	/**
	 * Get the action name for the current web request.
	 *
	 * @since 1.38
	 * @return string
	 */
	public function getActionName(): string;

	/**
	 * @return OutputPage
	 */
	public function getOutput();

	/**
	 * @return User
	 */
	public function getUser();

	/**
	 * @since 1.36
	 * @return Authority
	 */
	public function getAuthority(): Authority;

	/**
	 * @since 1.19
	 * @return Language
	 */
	public function getLanguage();

	/**
	 * @return Skin
	 */
	public function getSkin();

	/**
	 * Get the site configuration
	 *
	 * @since 1.23
	 * @return Config
	 */
	public function getConfig();

	/**
	 * @since 1.27
	 * @return Timing
	 */
	public function getTiming();

	/**
	 * Export the resolved user IP, HTTP headers, user ID, and session ID.
	 *
	 * The result will be reasonably sized to allow for serialization.
	 *
	 * @return array
	 * @since 1.21
	 */
	public function exportSession();
}

/** @deprecated class alias since 1.42 */
class_alias( IContextSource::class, 'IContextSource' );
