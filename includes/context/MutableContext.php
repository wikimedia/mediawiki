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
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Request-dependent objects containers.
 *
 * @since 1.26
 */
interface MutableContext {

	/** @return void */
	public function setConfig( Config $config );

	/** @return void */
	public function setRequest( WebRequest $request );

	/** @return void */
	public function setTitle( Title $title );

	/** @return void */
	public function setWikiPage( WikiPage $wikiPage );

	/**
	 * @since 1.38
	 * @param string $action
	 */
	public function setActionName( string $action ): void;

	/** @return void */
	public function setOutput( OutputPage $output );

	/** @return void */
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

	/** @return void */
	public function setSkin( Skin $skin );

}

/** @deprecated class alias since 1.42 */
class_alias( MutableContext::class, 'MutableContext' );
