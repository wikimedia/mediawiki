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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Object passed around to modules which contains information about the state 
 * of a specific loader request
 */
class ResourceLoaderContext {

	/* Protected Members */

	protected $resourceLoader;
	protected $request;
	protected $modules;
	protected $language;
	protected $direction;
	protected $skin;
	protected $user;
	protected $debug;
	protected $only;
	protected $version;
	protected $hash;

	/* Methods */

	public function __construct( ResourceLoader $resourceLoader, WebRequest $request ) {
		global $wgDefaultSkin, $wgResourceLoaderDebug;

		$this->resourceLoader = $resourceLoader;
		$this->request = $request;

		// Interpret request
		// List of modules
		$modules = $request->getVal( 'modules' );
		$this->modules   = $modules ? explode( '|', $modules ) : array();
		// Various parameters
		$this->skin      = $request->getVal( 'skin' );
		$this->user      = $request->getVal( 'user' );
		$this->debug     = $request->getFuzzyBool( 'debug', $wgResourceLoaderDebug );
		$this->only      = $request->getVal( 'only' );
		$this->version   = $request->getVal( 'version' );

		if ( !$this->skin ) {
			$this->skin = $wgDefaultSkin;
		}
	}

	public function getResourceLoader() {
		return $this->resourceLoader;
	}
	
	public function getRequest() {
		return $this->request;
	}

	public function getModules() {
		return $this->modules;
	}

	public function getLanguage() {
		if ( $this->language === null ) {
			global $wgLang;
			$this->language  = $this->request->getVal( 'lang' );
			if ( !$this->language ) {
				$this->language = $wgLang->getCode();
			}
		}
		return $this->language;
	}

	public function getDirection() {
		if ( $this->direction === null ) {
			$this->direction = $this->request->getVal( 'dir' );
			if ( !$this->direction ) {
				global $wgContLang;
				$this->direction = $wgContLang->getDir();
			}
		}
		return $this->direction;
	}

	public function getSkin() {
		return $this->skin;
	}

	public function getUser() {
		return $this->user;
	}

	public function getDebug() {
		return $this->debug;
	}

	public function getOnly() {
		return $this->only;
	}

	public function getVersion() {
		return $this->version;
	}

	public function shouldIncludeScripts() {
		return is_null( $this->only ) || $this->only === 'scripts';
	}

	public function shouldIncludeStyles() {
		return is_null( $this->only ) || $this->only === 'styles';
	}

	public function shouldIncludeMessages() {
		return is_null( $this->only ) || $this->only === 'messages';
	}

	public function getHash() {
		if ( !isset( $this->hash ) ) {
			$this->hash = implode( '|', array(
				$this->getLanguage(), $this->getDirection(), $this->skin, $this->user, 
				$this->debug, $this->only, $this->version
			) );
		}
		return $this->hash;
	}
}
