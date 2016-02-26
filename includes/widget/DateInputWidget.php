<?php
/**
 *
 *
 * Created on Feb 26, 2016
 *
 * Copyright © 2016 Geoffrey Mon <geofbot@gmail.com>
 *
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

namespace MediaWiki\Widget;

/**
 * Date input widget.
 *
 * @since 1.27
 */
class DateInputWidget extends \OOUI\TextInputWidget {
	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		// Parent constructor
		parent::__construct(
			array_merge( [ 'infusable' => true, 'type' => 'date' ], $config )
		);

		// Initialization
		$this->addClasses( [ 'mw-widget-dateInputWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.DateInputWidget';
	}

	public function getConfig( &$config ) {
		return parent::getConfig( $config );
	}
}
