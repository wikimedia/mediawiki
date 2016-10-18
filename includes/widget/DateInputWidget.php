<?php
/**
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

use DateTime;

/**
 * Date input widget.
 *
 * @since 1.27
 */
class DateInputWidget extends \OOUI\TextInputWidget {

	protected $inputFormat = null;
	protected $displayFormat = null;
	protected $placeholderLabel = null;
	protected $placeholderDateFormat = null;
	protected $precision = null;
	protected $mustBeAfter = null;
	protected $mustBeBefore = null;
	protected $overlay = null;

	/**
	 * @param array $config Configuration options
	 * @param string $config['inputFormat'] Date format string to use for the textual input field.
	 *   Displayed while the widget is active, and the user can type in a date in this format.
	 *   Should be short and easy to type. (default: 'YYYY-MM-DD' or 'YYYY-MM', depending on
	 *   `precision`)
	 * @param string $config['displayFormat'] Date format string to use for the clickable label.
	 *   while the widget is inactive. Should be as unambiguous as possible (for example, prefer
	 *   to spell out the month, rather than rely on the order), even if that makes it longer.
	 *   Applicable only if the widget is infused. (default: language-specific)
	 * @param string $config['placeholderLabel'] Placeholder text shown when the widget is not
	 *   selected. Applicable only if the widget is infused. (default: taken from message
	 *   `mw-widgets-dateinput-no-date`)
	 * @param string $config['placeholderDateFormat'] User-visible date format string displayed
	 *   in the textual input field when it's empty. Should be the same as `inputFormat`, but
	 *   translated to the user's language. (default: 'YYYY-MM-DD' or 'YYYY-MM', depending on
	 *   `precision`)
	 * @param string $config['precision'] Date precision to use, 'day' or 'month' (default: 'day')
	 * @param string $config['mustBeAfter']	Validates the date to be after this.
	 *   In the 'YYYY-MM-DD' or 'YYYY-MM' format, depending on `precision`.
	 * @param string $config['mustBeBefore'] Validates the date to be before this.
	 *   In the 'YYYY-MM-DD' or 'YYYY-MM' format, depending on `precision`.
	 * @param string $config['overlay'] The jQuery selector for the overlay layer on which to render
	 *   the calendar. This configuration is useful in cases where the expanded calendar is larger
	 *   than its container. The specified overlay layer is usually on top of the container and has
	 *   a larger area. Applicable only if the widget is infused. By default, the calendar uses
	 *   relative positioning.
	 */
	public function __construct( array $config = [] ) {
		$config = array_merge( [
			// Default config values
			'precision' => 'day',
		], $config );

		// Properties
		if ( isset( $config['inputFormat'] ) ) {
			$this->inputFormat = $config['inputFormat'];
		}
		if ( isset( $config['placeholderDateFormat'] ) ) {
			$this->placeholderDateFormat = $config['placeholderDateFormat'];
		}
		$this->precision = $config['precision'];
		// For month precision, type=month
		if ( $config['precision'] === 'month' ) {
			$config['type'] = 'month';
		}
		if ( isset( $config['mustBeAfter'] ) ) {
			$this->mustBeAfter = $config['mustBeAfter'];
		}
		if ( isset( $config['mustBeBefore'] ) ) {
			$this->mustBeBefore = $config['mustBeBefore'];
		}

		// Properties stored for the infused JS widget
		if ( isset( $config['displayFormat'] ) ) {
			$this->displayFormat = $config['displayFormat'];
		}
		if ( isset( $config['placeholderLabel'] ) ) {
			$this->placeholderLabel = $config['placeholderLabel'];
		}
		if ( isset( $config['overlay'] ) ) {
			$this->overlay = $config['overlay'];
		}

		// Set up placeholder text visible if the browser doesn't override it (logic taken from JS)
		if ( $this->placeholderDateFormat !== null ) {
			$placeholder = $this->placeholderDateFormat;
		} elseif ( $this->inputFormat !== null ) {
			// We have no way to display a translated placeholder for custom formats
			$placeholder = '';
		} else {
			$placeholder = wfMessage( "mw-widgets-dateinput-placeholder-$this->precision" )->text();
		}

		$config = array_merge( [
			// Config values not relevant to DateInputWidget
			'type' => 'date',
			// Processed config values
			'placeholder' => $placeholder,
		], $config );

		// Parent constructor
		parent::__construct( $config );

		// Calculate min/max attributes (which are skipped by TextInputWidget) and add to <input>
		// min/max attributes are inclusive, but mustBeAfter/Before are exclusive
		if ( $this->mustBeAfter !== null ) {
			$min = new DateTime( $this->mustBeAfter );
			$min = $min->modify( '+1 day' );
			$min = $min->format( 'Y-m-d' );
			$this->input->setAttributes( [ 'min' => $min ] );
		}
		if ( $this->mustBeBefore !== null ) {
			$max = new DateTime( $this->mustBeBefore );
			$max = $max->modify( '-1 day' );
			$max = $max->format( 'Y-m-d' );
			$this->input->setAttributes( [ 'max' => $max ] );
		}

		// Initialization
		$this->addClasses( [ 'mw-widget-dateInputWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.DateInputWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->inputFormat !== null ) {
			$config['inputFormat'] = $this->inputFormat;
		}
		if ( $this->displayFormat !== null ) {
			$config['displayFormat'] = $this->displayFormat;
		}
		if ( $this->placeholderLabel !== null ) {
			$config['placeholderLabel'] = $this->placeholderLabel;
		}
		if ( $this->placeholderDateFormat !== null ) {
			$config['placeholderDateFormat'] = $this->placeholderDateFormat;
		}
		if ( $this->precision !== null ) {
			$config['precision'] = $this->precision;
		}
		if ( $this->mustBeAfter !== null ) {
			$config['mustBeAfter'] = $this->mustBeAfter;
		}
		if ( $this->mustBeBefore !== null ) {
			$config['mustBeBefore'] = $this->mustBeBefore;
		}
		if ( $this->overlay !== null ) {
			$config['overlay'] = $this->overlay;
		}
		return parent::getConfig( $config );
	}
}
