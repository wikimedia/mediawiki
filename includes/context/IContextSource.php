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
 * @since 1.18
 *
 * @author Happy-melon
 * @file
 */

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
 * All of the objects are useful for the vast majority of MediaWiki requests.
 * The site configuration object is included on grounds of extreme
 * utility, even though it should not actually depend on the web request.
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
 * @unstable for implementation, extensions should subclass ContextSource instead.
 */
interface IContextSource extends MessageLocalizer {

	/**
	 * @return WebRequest
	 */
	public function getRequest();

	/**
	 * @return Title|null
	 */
	public function getTitle();

	/**
	 * Check whether a WikiPage object can be get with getWikiPage().
	 * Callers should expect that an exception is thrown from getWikiPage()
	 * if this method returns false.
	 *
	 * @since 1.19
	 * @return bool
	 */
	public function canUseWikiPage();

	/**
	 * Get the WikiPage object.
	 * May throw an exception if there's no Title object set or the Title object
	 * belongs to a special namespace that doesn't have WikiPage, so use first
	 * canUseWikiPage() to check whether this method can be called safely.
	 *
	 * @since 1.19
	 * @return WikiPage
	 */
	public function getWikiPage();

	/**
	 * @return OutputPage
	 */
	public function getOutput();

	/**
	 * @return User
	 */
	public function getUser();

	/**
	 * @return Language
	 * @since 1.19
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
	 * @deprecated since 1.27 use a StatsdDataFactory from MediaWikiServices (preferably injected)
	 *
	 * @since 1.25
	 * @return IBufferingStatsdDataFactory
	 */
	public function getStats();

	/**
	 * @since 1.27
	 * @return Timing
	 */
	public function getTiming();

	/**
	 * Export the resolved user IP, HTTP headers, user ID, and session ID.
	 * The result will be reasonably sized to allow for serialization.
	 *
	 * @return array
	 * @since 1.21
	 */
	public function exportSession();
}
