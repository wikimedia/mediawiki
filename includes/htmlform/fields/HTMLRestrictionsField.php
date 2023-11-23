<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;

/**
 * Class for updating an MWRestrictions value (which is, currently, basically just an IP address
 * list).
 *
 * Will be represented as a textarea with one address per line, with intelligent defaults for
 * label, help text and row count.
 *
 * The value returned will be an MWRestrictions or the input string if it was not a list of
 * valid IP ranges.
 *
 */
class HTMLRestrictionsField extends HTMLFormField {
	protected const DEFAULT_ROWS = 5;

	private HTMLTextAreaField $ipField;

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$this->ipField = new HTMLTextAreaField( [
			'parent' => $params['parent'],
			'fieldname' => $params['fieldname'] . '-ip',
			'rows' => self::DEFAULT_ROWS,
			'required' => $params['required'] ?? false,
			'help-message' => 'restrictionsfield-help',
			'label-message' => 'restrictionsfield-label',
		] );
	}

	/**
	 * @param WebRequest $request
	 * @return MWRestrictions Restrictions object
	 */
	public function loadDataFromRequest( $request ) {
		if ( !$request->getCheck( $this->mName . '-ip' ) ) {
			return $this->getDefault();
		}

		$ipValue = rtrim( $request->getText( $this->mName . '-ip' ), "\r\n" );
		$ips = $ipValue === '' ? [] : explode( "\n", $ipValue );
		return MWRestrictions::newFromArray( [ 'IPAddresses' => $ips ] );
	}

	/**
	 * @return MWRestrictions
	 */
	public function getDefault() {
		return parent::getDefault() ?? MWRestrictions::newDefault();
	}

	/**
	 * @param MWRestrictions $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 *
	 * @return bool|string|Message True on success, or String/Message error to display, or
	 *   false to fail validation without displaying an error.
	 */
	public function validate( $value, $alldata ) {
		if ( $this->isHidden( $alldata ) ) {
			return true;
		}

		if (
			isset( $this->mParams['required'] ) && $this->mParams['required'] !== false
			&& !$value->toArray()['IPAddresses']
		) {
			return $this->msg( 'htmlform-required' );
		}

		if ( !$value->validity->isGood() ) {
			$statusFormatter = MediaWikiServices::getInstance()->getFormatterFactory()->getStatusFormatter(
				$this->mParent->getContext()
			);
			return $statusFormatter->getMessage( $value->validity );
		}

		if ( isset( $this->mValidationCallback ) ) {
			return call_user_func( $this->mValidationCallback, $value, $alldata, $this->mParent );
		}

		return true;
	}

	/**
	 * @param MWRestrictions $value
	 * @return string
	 */
	public function getInputHTML( $value ) {
		$ipValue = implode( "\n", $value->toArray()['IPAddresses'] );
		return $this->ipField->getDiv( $ipValue );
	}

	/**
	 * @param MWRestrictions $value
	 * @return string
	 * @suppress PhanParamSignatureMismatch
	 */
	public function getInputOOUI( $value ) {
		$ipValue = implode( "\n", $value->toArray()['IPAddresses'] );
		return $this->ipField->getOOUI( $ipValue )->toString();
	}
}
