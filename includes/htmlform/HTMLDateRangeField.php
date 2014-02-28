<?php

/**
 * A field that will contain a date range
 *
 * Besides the parameters recognized by HTMLDateField, additional recognized
 * parameters in the field descriptor array include:
 *   options - If specified, the "number of days" field is displayed as a
 *     <select>. Otherwise it's an 'int' textbox.
 *   min-days - If the "number of days" field is a textbox, this is the minimum
 *     allowed. If less than 1, 1 is used.
 *   max-days - If the "number of days" field is a textbox, this is the maximum
 *     allowed.
 *   layout-message - The date field (as $1) and "number of days" field (as $2)
 *     are layed out using this message. Default is 'htmlform-daterange-layout'.
 *
 * The result is an array with two elements: the date and the number of days value.
 *
 * @since 1.23
 */
class HTMLDateRangeField extends HTMLDateField {
	function loadDataFromRequest( $request ) {
		if ( !$request->getCheck( $this->mName ) ||
			!$request->getCheck( $this->mName . '-days' )
		) {
			return $this->getDefault();
		}

		$value = $request->getText( $this->mName );
		$date = $this->parseDate( $value );

		$days = $request->getText( $this->mName . '-days' );

		return array(
			$date ? $this->formatDate( $date ) : $value,
			$days
		);
	}

	function validate( $value, $alldata ) {
		$p = parent::validate( $value[0], $alldata );
		if ( $p !== true ) {
			return $p;
		}

		$opts = $this->getOptions();
		if ( $opts ) {
			if ( !in_array( $value[1], $opts ) ) {
				return $this->msg( 'htmlform-daterange-days-badoption' )->parseAsBlock();
			}
		} else {
			if ( !preg_match( '/^((\+|\-)?\d+)?$/', trim( $value[1] ) ) ) {
				return $this->msg( 'htmlform-daterange-days-invalid' )->parseAsBlock();
			}

			if ( isset( $this->mParams['min-days'] ) ) {
				$min = max( 1, $this->mParams['min-days'] );
			} else {
				$min = 1;
			}
			if ( $min > $value[1] ) {
				return $this->msg( 'htmlform-daterange-days-toolow' )->numParams( $min )
					->parseAsBlock();
			}

			if ( isset( $this->mParams['max-days'] ) ) {
				$max = $this->mParams['max-days'];

				if ( $max < $value[1] ) {
					return $this->msg( 'htmlform-daterange-days-toohigh' )->numParams( $max )
						->parseAsBlock();
				}
			}
		}

		return true;
	}

	function getInputHTML( $value ) {
		$datepicker = parent::getInputHTML( $value[0] );

		$opts = $this->getOptions();
		if ( $opts ) {
			$select = new XmlSelect(
				$this->mName . '-days', $this->mID . '-days', strval( $value[1] )
			);

			if ( !empty( $this->mParams['disabled'] ) ) {
				$select->setAttribute( 'disabled', 'disabled' );
			}

			if ( isset( $this->mParams['tabindex'] ) ) {
				$select->setAttribute( 'tabindex', $this->mParams['tabindex'] );
			}

			if ( $this->mClass !== '' ) {
				$select->setAttribute( 'class', $this->mClass );
			}

			$select->addOptions( $this->getOptions() );

			$dayspicker = $select->getHTML();
		} else {
			$textAttribs = array(
				'id' => $this->mID . '-days',
				'size' => $this->getSize(),
				'min' => 1,
			);

			if ( $this->mClass !== '' ) {
				$textAttribs['class'] = $this->mClass;
			}

			$allowedParams = array(
				'required',
				'autofocus',
				'disabled',
				'tabindex'
			);

			$textAttribs += $this->getAttributes( $allowedParams );

			if ( isset( $this->mParams['min-days'] ) ) {
				$textAttribs['min'] = max( 1, $this->mParams['min-days'] );
			}

			if ( isset( $this->mParams['max-days'] ) ) {
				$textAttribs['max'] = $this->mParams['max-days'];
			}

			$dayspicker = Html::input( $this->mName . '-days', $value[1], 'number', $textAttribs );
		}

		$msg = isset( $this->mParams['layout-message'] )
			? $this->mParams['layout-message']
			: 'htmlform-daterange-layout';
		return $this->msg( $msg )->rawParams( $datepicker, $dayspicker )->parse();
	}

}
