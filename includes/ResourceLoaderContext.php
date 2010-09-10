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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Object passed around to modules which contains information about the state of a specific loader request
 */
class ResourceLoaderContext {
	/* Protected Members */

	protected $request;
	protected $server;
	protected $modules;
	protected $language;
	protected $direction;
	protected $skin;
	protected $debug;
	protected $only;
	protected $hash;

	/* Methods */

	public function __construct( WebRequest $request, $server ) {
		global $wgLang, $wgDefaultSkin;

		$this->request = $request;
		$this->server = $server;
		// Interperet request
		$this->modules = explode( '|', $request->getVal( 'modules' ) );
		$this->language = $request->getVal( 'lang' );
		$this->direction = $request->getVal( 'dir' );
		$this->skin = $request->getVal( 'skin' );
		$this->debug = $request->getVal( 'debug' ) === 'true' || $request->getBool( 'debug' );
		$this->only = $request->getVal( 'only' );

		// Fallback on system defaults
		if ( !$this->language ) {
			$this->language = $wgLang->getCode();
		}

		if ( !$this->direction ) {
			$this->direction = Language::factory( $this->language )->getDir();
		}

		if ( !$this->skin ) {
			$this->skin = $wgDefaultSkin;
		}
	}

	public function getRequest() {
		return $this->request;
	}

	public function getServer() {
		return $this->server;
	}

	public function getModules() {
		return $this->modules;
	}

	public function getLanguage() {
		return $this->language;
	}

	public function getDirection() {
		return $this->direction;
	}

	public function getSkin() {
		return $this->skin;
	}

	public function getDebug() {
		return $this->debug;
	}

	public function getOnly() {
		return $this->only;
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
		return isset( $this->hash ) ?
			$this->hash : $this->hash =
				implode( '|', array( $this->language, $this->skin, $this->debug, $this->only ) );
	}
}
