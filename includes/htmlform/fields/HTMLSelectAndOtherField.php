<?php

/**
 * Double field with a dropdown list constructed from a system message in the format
 *     * Optgroup header
 *     ** <option value>
 *     * New Optgroup header
 * Plus a text field underneath for an additional reason.  The 'value' of the field is
 * "<select>: <extra reason>", or "<extra reason>" if nothing has been selected in the
 * select dropdown.
 * @todo FIXME: If made 'required', only the text field should be compulsory.
 */
class HTMLSelectAndOtherField extends HTMLSelectField {
	public function __construct( $params ) {
		if ( array_key_exists( 'other', $params ) ) {
			// Do nothing
		} elseif ( array_key_exists( 'other-message', $params ) ) {
			$params['other'] = $this->getMessage( $params['other-message'] )->plain();
		} else {
			$params['other'] = $this->msg( 'htmlform-selectorother-other' )->plain();
		}

		parent::__construct( $params );

		if ( $this->getOptions() === null ) {
			// Sulk
			throw new MWException( 'HTMLSelectAndOtherField called without any options' );
		}
		if ( !in_array( 'other', $this->mOptions, true ) ) {
			// Have 'other' always as first element
			$this->mOptions = [ $params['other'] => 'other' ] + $this->mOptions;
		}
		$this->mFlatOptions = self::flattenOptions( $this->getOptions() );
	}

	public function getInputHTML( $value ) {
		$select = parent::getInputHTML( $value[1] );

		$textAttribs = [
			'id' => $this->mID . '-other',
			'size' => $this->getSize(),
			'class' => [ 'mw-htmlform-select-and-other-field' ],
			'data-id-select' => $this->mID,
		];

		if ( $this->mClass !== '' ) {
			$textAttribs['class'][] = $this->mClass;
		}

		if ( isset( $this->mParams['maxlength-unit'] ) ) {
			$textAttribs['data-mw-maxlength-unit'] = $this->mParams['maxlength-unit'];
		}

		$allowedParams = [
			'required',
			'autofocus',
			'multiple',
			'disabled',
			'tabindex',
			'maxlength', // gets dynamic with javascript, see mediawiki.htmlform.js
			'maxlength-unit', // 'bytes' or 'codepoints', see mediawiki.htmlform.js
		];

		$textAttribs += $this->getAttributes( $allowedParams );

		$textbox = Html::input( $this->mName . '-other', $value[2], 'text', $textAttribs );

		return "$select<br />\n$textbox";
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.SelectWithInputWidget' ];
	}

	public function getInputOOUI( $value ) {
		$this->mParent->getOutput()->addModuleStyles( 'mediawiki.widgets.SelectWithInputWidget.styles' );

		# TextInput
		$textAttribs = [
			'name' => $this->mName . '-other',
			'value' => $value[2],
		];

		$allowedParams = [
			'required',
			'autofocus',
			'multiple',
			'disabled',
			'tabindex',
			'maxlength',
		];

		$textAttribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		if ( $this->mClass !== '' ) {
			$textAttribs['classes'] = [ $this->mClass ];
		}

		# DropdownInput
		$dropdownInputAttribs = [
			'name' => $this->mName,
			'id' => $this->mID . '-select',
			'options' => $this->getOptionsOOUI(),
			'value' => $value[1],
		];

		$allowedParams = [
			'tabindex',
			'disabled',
		];

		$dropdownInputAttribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		if ( $this->mClass !== '' ) {
			$dropdownInputAttribs['classes'] = [ $this->mClass ];
		}

		return $this->getInputWidget( [
			'id' => $this->mID,
			'textinput' => $textAttribs,
			'dropdowninput' => $dropdownInputAttribs,
			'or' => false,
			'classes' => [ 'mw-htmlform-select-and-other-field' ],
			'data' => [
				'maxlengthUnit' => isset( $this->mParams['maxlength-unit'] )
					? $this->mParams['maxlength-unit'] : 'bytes'
			],
		] );
	}

	public function getInputWidget( $params ) {
		return new MediaWiki\Widget\SelectWithInputWidget( $params );
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return array("<overall message>","<select value>","<text field value>")
	 */
	public function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			$list = $request->getText( $this->mName );
			$text = $request->getText( $this->mName . '-other' );

			// Should be built the same as in mediawiki.htmlform.js
			if ( $list == 'other' ) {
				$final = $text;
			} elseif ( !in_array( $list, $this->mFlatOptions, true ) ) {
				# User has spoofed the select form to give an option which wasn't
				# in the original offer.  Sulk...
				$final = $text;
			} elseif ( $text == '' ) {
				$final = $list;
			} else {
				$final = $list . $this->msg( 'colon-separator' )->inContentLanguage()->text() . $text;
			}
		} else {
			$final = $this->getDefault();

			$list = 'other';
			$text = $final;
			foreach ( $this->mFlatOptions as $option ) {
				$match = $option . $this->msg( 'colon-separator' )->inContentLanguage()->text();
				if ( strpos( $text, $match ) === 0 ) {
					$list = $option;
					$text = substr( $text, strlen( $match ) );
					break;
				}
			}
		}

		return [ $final, $list, $text ];
	}

	public function getSize() {
		return isset( $this->mParams['size'] ) ? $this->mParams['size'] : 45;
	}

	public function validate( $value, $alldata ) {
		# HTMLSelectField forces $value to be one of the options in the select
		# field, which is not useful here.  But we do want the validation further up
		# the chain
		$p = parent::validate( $value[1], $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( isset( $this->mParams['required'] )
			&& $this->mParams['required'] !== false
			&& $value[1] === ''
		) {
			return $this->msg( 'htmlform-required' );
		}

		return true;
	}
}
