<?php

namespace MediaWiki\Widget;

use DateTime;
use OOUI\TextInputWidget;

/**
 * Date input widget.
 *
 * @since 1.29
 * @copyright 2016 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class DateInputWidget extends TextInputWidget {

	/** @var string|null */
	protected $inputFormat = null;
	/** @var string|null */
	protected $displayFormat = null;
	/** @var string|null|false */
	protected $longDisplayFormat = null;
	/** @var string|null */
	protected $placeholderLabel = null;
	/** @var string|null */
	protected $placeholderDateFormat = null;
	/** @var string|null */
	protected $precision = null;
	/** @var string|null */
	protected $mustBeAfter = null;
	/** @var string|null */
	protected $mustBeBefore = null;

	/**
	 * @param array $config Configuration options
	 *   - string $config['inputFormat'] Date format string to use for the textual input field.
	 *     Displayed while the widget is active, and the user can type in a date in this format.
	 *     Should be short and easy to type. (default: 'YYYY-MM-DD' or 'YYYY-MM', depending on
	 *     `precision`)
	 *   - string $config['displayFormat'] Date format string to use for the clickable label.
	 *     while the widget is inactive. Should be as unambiguous as possible (for example, prefer
	 *     to spell out the month, rather than rely on the order), even if that makes it longer.
	 *     Applicable only if the widget is infused. (default: language-specific)
	 *   - string $config['longDisplayFormat'] If a custom displayFormat is not specified, use
	 *     unabbreviated day of the week and month names in the default language-specific
	 *     displayFormat. (default: false)
	 *   - string $config['placeholderLabel'] Placeholder text shown when the widget is not
	 *     selected. Applicable only if the widget is infused. (default: taken from message
	 *     `mw-widgets-dateinput-no-date`)
	 *   - string $config['placeholderDateFormat'] User-visible date format string displayed
	 *     in the textual input field when it's empty. Should be the same as `inputFormat`, but
	 *     translated to the user's language. (default: 'YYYY-MM-DD' or 'YYYY-MM', depending on
	 *     `precision`)
	 *   - string $config['precision'] Date precision to use, 'day' or 'month' (default: 'day')
	 *   - string $config['min'] Validates the date to be no earlier than this.
	 *     In the 'YYYY-MM-DD' or 'YYYY-MM' format, depending on `precision`.
	 *   - string $config['mustBeAfter'] Validates the date to be after this. Overrides 'min'.
	 *     In the 'YYYY-MM-DD' or 'YYYY-MM' format, depending on `precision`.
	 *   - string $config['max'] Validates the date to be no later than this.
	 *     In the 'YYYY-MM-DD' or 'YYYY-MM' format, depending on `precision`.
	 *   - string $config['mustBeBefore'] Validates the date to be before this. Overrides 'max'.
	 *     In the 'YYYY-MM-DD' or 'YYYY-MM' format, depending on `precision`.
	 */
	public function __construct( array $config = [] ) {
		$config = array_merge( [
			// Default config values
			'precision' => 'day',
			'longDisplayFormat' => false,
		], $config );

		// Properties
		if ( isset( $config['inputFormat'] ) ) {
			$this->inputFormat = $config['inputFormat'];
		}
		if ( isset( $config['placeholderDateFormat'] ) ) {
			$this->placeholderDateFormat = $config['placeholderDateFormat'];
		}
		$this->precision = $config['precision'];
		if ( isset( $config['mustBeAfter'] ) ) {
			$this->mustBeAfter = $config['mustBeAfter'];
		} elseif ( isset( $config['min'] ) ) {
			$this->mustBeAfter = $this->modifyDate( $config['min'], '-1 day' );
		}
		if ( isset( $config['mustBeBefore'] ) ) {
			$this->mustBeBefore = $config['mustBeBefore'];
		} elseif ( isset( $config['max'] ) ) {
			$this->mustBeBefore = $this->modifyDate( $config['max'], '+1 day' );
		}

		// Properties stored for the infused JS widget
		if ( isset( $config['displayFormat'] ) ) {
			$this->displayFormat = $config['displayFormat'];
		}
		if ( isset( $config['longDisplayFormat'] ) ) {
			$this->longDisplayFormat = $config['longDisplayFormat'];
		}
		if ( isset( $config['placeholderLabel'] ) ) {
			$this->placeholderLabel = $config['placeholderLabel'];
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
			// Processed config values
			'placeholder' => $placeholder,
		], $config );

		parent::__construct( $config );

		// Calculate min/max attributes (which are skipped by TextInputWidget) and add to <input>
		// min/max attributes are inclusive, but mustBeAfter/Before are exclusive
		if ( $this->mustBeAfter !== null ) {
			$this->input->setAttributes( [ 'min' => $this->modifyDate( $this->mustBeAfter, '+1 day' ) ] );
		}
		if ( $this->mustBeBefore !== null ) {
			$this->input->setAttributes( [ 'max' => $this->modifyDate( $this->mustBeBefore, '-1 day' ) ] );
		}

		// Initialization
		$this->addClasses( [ 'mw-widget-dateInputWidget' ] );
		$this->appendContent( new PendingTextInputWidget() );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.DateInputWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		if ( $this->inputFormat !== null ) {
			$config['inputFormat'] = $this->inputFormat;
		}
		if ( $this->displayFormat !== null ) {
			$config['displayFormat'] = $this->displayFormat;
		}
		if ( $this->longDisplayFormat !== null ) {
			$config['longDisplayFormat'] = $this->longDisplayFormat;
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
		$config['$overlay'] = true;
		return parent::getConfig( $config );
	}

	/** @inheritDoc */
	public function getInputElement( $config ) {
		// Inserts date/month type attribute
		return parent::getInputElement( $config )
			->setAttributes( [
				'type' => ( $config['precision'] === 'month' ) ? 'month' : 'date'
			] );
	}

	private function modifyDate( string $date, string $modifier ): string {
		$datetime = new DateTime( $date );
		$datetime->modify( $modifier );
		return $datetime->format( 'Y-m-d' );
	}
}
