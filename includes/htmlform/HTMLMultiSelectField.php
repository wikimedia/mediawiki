<?php

/**
 * Multi-select field
 */
class HTMLMultiSelectField extends HTMLFormField implements HTMLNestedFilterable {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !is_array( $value ) ) {
			return false;
		}

		# If all options are valid, array_intersect of the valid options
		# and the provided options will return the provided options.
		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		$validValues = array_intersect( $value, $validOptions );
		if ( count( $validValues ) == count( $value ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	function getInputHTML( $value ) {
		$value = HTMLFormField::forceToStringRecursive( $value );
		$html = $this->formatOptions( $this->getOptions(), $value );

		return $html;
	}

	function formatOptions( $options, $value ) {
		$html = '';

		$attribs = $this->getAttributes( array( 'disabled', 'tabindex' ) );
		$elementFunc = array( 'Html', $this->mOptionsLabelsNotFromMessage ? 'rawElement' : 'element' );

		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', array(), $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$thisAttribs = array( 'id' => "{$this->mID}-$info", 'value' => $info );

				$checkbox = Xml::check(
					$this->mName . '[]',
					in_array( $info, $value, true ),
					$attribs + $thisAttribs
				);
				$checkbox .= '&#160;' . call_user_func( $elementFunc,
					'label',
					array( 'for' => "{$this->mID}-$info" ),
					$label
				);

				$html .= ' ' . Html::rawElement(
					'div',
					array( 'class' => 'mw-htmlform-flatlist-item' ),
					$checkbox
				);
			}
		}

		return $html;
	}

	/**
	 * @param $request WebRequest
	 *
	 * @return String
	 */
	function loadDataFromRequest( $request ) {
		if ( $this->mParent->getMethod() == 'post' ) {
			if ( $request->wasPosted() ) {
				# Checkboxes are just not added to the request arrays if they're not checked,
				# so it's perfectly possible for there not to be an entry at all
				return $request->getArray( $this->mName, array() );
			} else {
				# That's ok, the user has not yet submitted the form, so show the defaults
				return $this->getDefault();
			}
		} else {
			# This is the impossible case: if we look at $_GET and see no data for our
			# field, is it because the user has not yet submitted the form, or that they
			# have submitted it with all the options unchecked? We will have to assume the
			# latter, which basically means that you can't specify 'positive' defaults
			# for GET forms.
			# @todo FIXME...
			return $request->getArray( $this->mName, array() );
		}
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return array();
		}
	}

	function filterDataForSubmit( $data ) {
		$data = HTMLFormField::forceToStringRecursive( $data );
		$options = HTMLFormField::flattenOptions( $this->getOptions() );

		$res = array();
		foreach ( $options as $opt ) {
			$res["$opt"] = in_array( $opt, $data, true );
		}

		return $res;
	}

	protected function needsLabel() {
		return false;
	}
}
