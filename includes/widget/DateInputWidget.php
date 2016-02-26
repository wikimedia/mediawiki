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

	protected $displayFormat = null;
	protected $inputFormat = null;
	protected $placeholderDateFormat = null;
	protected $precision = null;

	/**
	 * @param array $config Configuration options
	 * @param string $config['displayFormat'] Date format string to use for the clickable label.
	 *   while the widget is inactive. Should be as unambiguous as possible (for example, prefer
	 *   to spell out the month, rather than rely on the order), even if that makes it longer.
	 *   (default: language-specific)
	 * @param string $config['placeholderDateFormat'] User-visible date format string displayed
	 *   in the textual input (default: 'YYYY-MM-DD')
	 * @param string $config['precision'] Date precision to use, 'day' or 'month' (default: 'day')
	 */
	public function __construct( array $config = [] ) {
		// Properties
		if ( isset( $config['displayFormat'] ) ) {
			$this->displayFormat = $config['displayFormat'];
		}
		if ( isset( $config['inputFormat'] ) ) {
			$this->inputFormat = $config['inputFormat'];
		}
		if ( isset( $config['placeholderDateFormat'] ) ) {
			$this->placeholderDateFormat = $config['placeholderDateFormat'];
		}
		if ( isset( $config['precision'] ) ) {
			$this->precision = $config['precision'];
		}

		// Set up placeholder text (logic taken from JS)
		$placeholder = 'YYYY-MM-DD';
		if ( $this->placeholderDateFormat !== null ) {
			$placeholder = $this->placeholderDateFormat;
		} else if ( $this->inputFormat !== null ) {
			// We have no way to display a translated placeholder for custom formats
			$placeholder = '';
		} else {
			$placeholder = wfMessage( "mw-widgets-dateinput-placeholder-$this->precision" )->text();
		}

		// Parent constructor
		parent::__construct(
			array_merge( [
				'infusable' => true,
				'type' => 'date',
				'placeholder' => $placeholder,
			], $config )
		);


		// Initialization
		$this->addClasses( [ 'mw-widget-dateInputWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.DateInputWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->displayFormat !== null ) {
			$config['displayFormat'] = $this->displayFormat;
		}
		if ( $this->inputFormat !== null ) {
			$config['inputFormat'] = $this->inputFormat;
		}
		if ( $this->placeholderDateFormat !== null ) {
			$config['placeholderDateFormat'] = $this->placeholderDateFormat;
		}
		if ( $this->precision !== null ) {
			$config['precision'] = $this->precision;
		}
		return parent::getConfig( $config );
	}
}
