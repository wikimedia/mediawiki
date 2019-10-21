<?php

/**
 * A field that will contain a date and/or time
 *
 * Currently recognizes only {YYYY}-{MM}-{DD}T{HH}:{MM}:{SS.S*}Z formatted dates.
 *
 * Besides the parameters recognized by HTMLTextField, additional recognized
 * parameters in the field descriptor array include:
 *  type - 'date', 'time', or 'datetime'
 *  min - The minimum date to allow, in any recognized format.
 *  max - The maximum date to allow, in any recognized format.
 *  placeholder - The default comes from the htmlform-(date|time|datetime)-placeholder message.
 *
 * The result is a formatted date.
 *
 * @note This widget is not likely to work well in non-OOUI forms.
 */
class HTMLDateTimeField extends HTMLTextField {
	protected static $patterns = [
		'date' => '[0-9]{4}-[01][0-9]-[0-3][0-9]',
		'time' => '[0-2][0-9]:[0-5][0-9]:[0-5][0-9](?:\.[0-9]+)?',
		'datetime' => '[0-9]{4}-[01][0-9]-[0-3][0-9][T ][0-2][0-9]:[0-5][0-9]:[0-5][0-9](?:\.[0-9]+)?Z?',
	];

	protected $mType = 'datetime';

	public function __construct( $params ) {
		parent::__construct( $params );

		$this->mType = array_key_exists( 'type', $params )
			? $params['type']
			: 'datetime';

		if ( !in_array( $this->mType, [ 'date', 'time', 'datetime' ] ) ) {
			throw new InvalidArgumentException( "Invalid type '$this->mType'" );
		}

		if ( $this->mPlaceholder === '' ) {
			// Messages: htmlform-date-placeholder htmlform-time-placeholder htmlform-datetime-placeholder
			$this->mPlaceholder = $this->msg( "htmlform-{$this->mType}-placeholder" )->text();
		}

		$this->mClass .= ' mw-htmlform-datetime-field';
	}

	public function getAttributes( array $list ) {
		$parentList = array_diff( $list, [ 'min', 'max' ] );
		$ret = parent::getAttributes( $parentList );

		if ( in_array( 'min', $list ) && isset( $this->mParams['min'] ) ) {
			$min = $this->parseDate( $this->mParams['min'] );
			if ( $min ) {
				$ret['min'] = $this->formatDate( $min );
			}
		}
		if ( in_array( 'max', $list ) && isset( $this->mParams['max'] ) ) {
			$max = $this->parseDate( $this->mParams['max'] );
			if ( $max ) {
				$ret['max'] = $this->formatDate( $max );
			}
		}

		$ret['step'] = 1;

		$ret['type'] = $this->mType;
		$ret['pattern'] = static::$patterns[$this->mType];

		return $ret;
	}

	public function loadDataFromRequest( $request ) {
		if ( !$request->getCheck( $this->mName ) ) {
			return $this->getDefault();
		}

		$value = $request->getText( $this->mName );
		$date = $this->parseDate( $value );
		return $date ? $this->formatDate( $date ) : $value;
	}

	public function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( $value === '' ) {
			// required was already checked by parent::validate
			return true;
		}

		$date = $this->parseDate( $value );
		if ( !$date ) {
			// Messages: htmlform-date-invalid htmlform-time-invalid htmlform-datetime-invalid
			return $this->msg( "htmlform-{$this->mType}-invalid" );
		}

		if ( isset( $this->mParams['min'] ) ) {
			$min = $this->parseDate( $this->mParams['min'] );
			if ( $min && $date < $min ) {
				// Messages: htmlform-date-toolow htmlform-time-toolow htmlform-datetime-toolow
				return $this->msg( "htmlform-{$this->mType}-toolow", $this->formatDate( $min ) );
			}
		}

		if ( isset( $this->mParams['max'] ) ) {
			$max = $this->parseDate( $this->mParams['max'] );
			if ( $max && $date > $max ) {
				// Messages: htmlform-date-toohigh htmlform-time-toohigh htmlform-datetime-toohigh
				return $this->msg( "htmlform-{$this->mType}-toohigh", $this->formatDate( $max ) );
			}
		}

		return true;
	}

	protected function parseDate( $value ) {
		$value = trim( $value );
		if ( $value === '' ) {
			return false;
		}

		if ( $this->mType === 'date' ) {
			$value .= ' T00:00:00+0000';
		}
		if ( $this->mType === 'time' ) {
			$value = '1970-01-01 ' . $value . '+0000';
		}

		try {
			$date = new DateTime( $value, new DateTimeZone( 'GMT' ) );
			return $date->getTimestamp();
		} catch ( Exception $ex ) {
			return false;
		}
	}

	protected function formatDate( $value ) {
		switch ( $this->mType ) {
			case 'date':
				return gmdate( 'Y-m-d', $value );

			case 'time':
				return gmdate( 'H:i:s', $value );

			case 'datetime':
				return gmdate( 'Y-m-d\\TH:i:s\\Z', $value );
		}
	}

	public function getInputOOUI( $value ) {
		$params = [
			'type' => $this->mType,
			'value' => $value,
			'name' => $this->mName,
			'id' => $this->mID,
		];

		if ( isset( $this->mParams['min'] ) ) {
			$min = $this->parseDate( $this->mParams['min'] );
			if ( $min ) {
				$params['min'] = $this->formatDate( $min );
			}
		}
		if ( isset( $this->mParams['max'] ) ) {
			$max = $this->parseDate( $this->mParams['max'] );
			if ( $max ) {
				$params['max'] = $this->formatDate( $max );
			}
		}

		if ( $this->mType === 'date' ) {
			$this->mParent->getOutput()->addModuleStyles( 'mediawiki.widgets.DateInputWidget.styles' );
			return new MediaWiki\Widget\DateInputWidget( $params );
		} else {
			return new MediaWiki\Widget\DateTimeInputWidget( $params );
		}
	}

	protected function getOOUIModules() {
		if ( $this->mType === 'date' ) {
			return [ 'mediawiki.widgets.DateInputWidget' ];
		} else {
			return [ 'mediawiki.widgets.datetime' ];
		}
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

}
