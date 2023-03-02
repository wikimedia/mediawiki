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

use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;

/**
 * Request-dependent objects containers.
 *
 * @since 1.26
 */
interface MutableContext {

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config );

	/**
	 * @param WebRequest $request
	 */
	public function setRequest( WebRequest $request );

	/**
	 * @param Title $title
	 */
	public function setTitle( Title $title );

	/**
	 * @param WikiPage $wikiPage
	 */
	public function setWikiPage( WikiPage $wikiPage );

	/**
	 * @since 1.38
	 * @param string $action
	 */
	public function setActionName( string $action ): void;

	/**
	 * @param OutputPage $output
	 */
	public function setOutput( OutputPage $output );

	/**
	 * @param User $user
	 */
	public function setUser( User $user );

	/**
	 * @unstable
	 * @param Authority $authority
	 */
	public function setAuthority( Authority $authority );

	/**
	 * @param Language|string $language Language instance or language code
	 */
	public function setLanguage( $language );

	/**
	 * @param Skin $skin
	 */
	public function setSkin( Skin $skin );

}
