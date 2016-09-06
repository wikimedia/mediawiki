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

class ExtensionList {

	/**
	 * @var string
	 */
	private $path;

	private $content;

	/**
	 * @return ExtensionList
	 */
	public static function getInstance() {
		static $instance = null;

		if ( $instance === null ) {
			global $wgExtensionListFile;
			$instance = new self( $wgExtensionListFile );
		}

		return $instance;
	}

	/**
	 * @param string $path Location to extension list file
	 */
	public function __construct( $path ) {
		$this->path = $path;
	}

	protected function read() {
		if ( $this->content !== null ) {
			return;
		}

		if ( !file_exists( $this->path ) ) {
			$this->content = (object)[
				'extensions' => new stdClass,
				'skins' => new stdClass
			];
			return;
		}

		$content = file_get_contents( $this->path );
		if ( $content === false ) {
			throw new Exception( "Unable to read {$this->path}" );
		}

		$json = FormatJson::decode( $content );
		if ( !is_object( $json ) ) {
			throw new Exception( "{$this->path} is not a valid JSON object" );
		}

		$this->content = $json;
	}

	/**
	 * @return array
	 */
	public function getEnabledExtensions() {
		$this->read();
		return array_keys( array_filter( (array)$this->content->extensions ) );
	}

	/**
	 * @return array
	 */
	public function getEnabledSkins() {
		$this->read();
		return array_keys( array_filter( (array)$this->content->skins ) );
	}

	/**
	 * @param string $type either 'extensions' or 'skins
	 * @param string $name
	 */
	public function enable( $type, $name ) {
		$this->read();
		if ( !in_array( $type, [ 'extensions', 'skins' ] ) ) {
			throw new InvalidArgumentException(
				"\$type must be either 'extensions' or 'skins', not '$type'" );
		}
		$this->content->{$type}->{$name} = true;
	}


	/**
	 * @param string $type either 'extensions' or 'skins
	 * @param string $name
	 */
	public function disable( $type, $name) {
		$this->read();
		if ( !in_array( $type, [ 'extensions', 'skins' ] ) ) {
			throw new InvalidArgumentException(
				"\$type must be either 'extensions' or 'skins', not '$type'" );
		}
		$this->content->{$type}->{$name} = false;
	}

	public function save() {
		$this->read();
		$good = file_put_contents(
			$this->path,
			FormatJson::encode( $this->content, "\t", FormatJson::ALL_OK )
		);
		if ( $good === false ) {
			throw new Exception( "Unable to edit {$this->path}, check file permissions?" );
		}
	}
}
