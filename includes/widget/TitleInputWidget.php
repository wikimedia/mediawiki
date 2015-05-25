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
 */

namespace MediaWiki\Widget;

use OOUI\TextInputWidget;

/**
 * Title input widget.
 */
class TitleInputWidget extends TextInputWidget {

	protected $namespace = null;

	/**
	 * @param array $config Configuration options
	 * @param number|null $config['namespace'] Namespace to prepend to queries
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( array_merge( $config, array( 'infusable' => true ) ) );

		// Properties
		if ( isset( $config['namespace'] ) ) {
			// Actually ignored in PHP, we just ship it back to JS
			$this->namespace = $config['namespace'];
		}

		// Initialization
		$this->addClasses( array( 'mw-widget-TitleInputWidget' ) );
	}

	public function getConfig( &$config ) {
		if ( $this->namespace !== null ) {
			$config['namespace'] = $this->namespace;
		}
		return parent::getConfig( $config );
	}
}
