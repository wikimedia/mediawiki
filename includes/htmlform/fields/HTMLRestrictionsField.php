<?php

/**
 * Class for updating an MWRestrictions value (which is, currently, basically just an IP address
 * list).
 *
 * Will be represented as a textarea with one address per line, with intelligent defaults for
 * label, help text and row count.
 *
 * The value returned will be an MWRestrictions or the input string if it was not a list of
 * valid IP ranges.
 */
class HTMLRestrictionsField extends HTMLTextAreaField {
	const DEFAULT_ROWS = 5;

	public function __construct( array $params ) {
		parent::__construct( $params );
		if ( !$this->mLabel ) {
			$this->mLabel = $this->msg( 'restrictionsfield-label' )->parse();
		}
	}

	public function getHelpText() {
		$helpText = parent::getHelpText();
		if ( $helpText === null ) {
			$helpText = $this->msg( 'restrictionsfield-help' )->parse();
		}
		return $helpText;
	}

	/**
	 * @param WebRequest $request
	 * @return string|MWRestrictions Restrictions object or original string if invalid
	 */
	function loadDataFromRequest( $request ) {
		if ( !$request->getCheck( $this->mName ) ) {
			return $this->getDefault();
		}

		$value = rtrim( $request->getText( $this->mName ), "\r\n" );
		$ips = $value === '' ? [] : explode( PHP_EOL, $value );
		try {
			return MWRestrictions::newFromArray( [ 'IPAddresses' => $ips ] );
		} catch ( InvalidArgumentException $e ) {
			return $value;
		}
	}

	/**
	 * @return MWRestrictions
	 */
	public function getDefault() {
		$default = parent::getDefault();
		if ( $default === null ) {
			$default = MWRestrictions::newDefault();
		}
		return $default;
	}

	/**
	 * @param string|MWRestrictions $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 *
	 * @return bool|string True on success, or String error to display, or
	 *   false to fail validation without displaying an error.
	 */
	public function validate( $value, $alldata ) {
		if ( $this->isHidden( $alldata ) ) {
			return true;
		}

		if (
			isset( $this->mParams['required'] ) && $this->mParams['required'] !== false
			&& $value instanceof MWRestrictions && !$value->toArray()['IPAddresses']
		) {
			return $this->msg( 'htmlform-required' )->parse();
		}

		if ( is_string( $value ) ) {
			// MWRestrictions::newFromArray failed; one of the IP ranges must be invalid
			$status = Status::newGood();
			foreach ( explode( PHP_EOL,  $value ) as $range ) {
				if ( !\IP::isIPAddress( $range ) ) {
					$status->fatal( 'restrictionsfield-badip', $range );
				}
			}
			if ( $status->isOK() ) {
				$status->fatal( 'unknown-error' );
			}
			return $status->getMessage()->parse();
		}

		if ( isset( $this->mValidationCallback ) ) {
			return call_user_func( $this->mValidationCallback, $value, $alldata, $this->mParent );
		}

		return true;
	}

	/**
	 * @param string|MWRestrictions $value
	 * @return string
	 */
	public function getInputHTML( $value ) {
		if ( $value instanceof MWRestrictions ) {
			$value = implode( PHP_EOL, $value->toArray()['IPAddresses'] );
		}
		return parent::getInputHTML( $value );
	}

	/**
	 * @param MWRestrictions $value
	 * @return string
	 */
	public function getInputOOUI( $value ) {
		if ( $value instanceof MWRestrictions ) {
			$value = implode( PHP_EOL, $value->toArray()['IPAddresses'] );
		}
		return parent::getInputOOUI( $value );
	}
}
