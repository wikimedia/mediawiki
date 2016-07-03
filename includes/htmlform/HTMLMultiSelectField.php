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

		$attribs = $this->getAttributes( [ 'disabled', 'tabindex' ] );

		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', [], $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$thisAttribs = [
					'id' => "{$this->mID}-$info",
					'value' => $info,
				];
				$checked = in_array( $info, $value, true );

				$checkbox = $this->getOneCheckbox( $checked, $attribs + $thisAttribs, $label );

				$html .= ' ' . Html::rawElement(
					'div',
					[ 'class' => 'mw-htmlform-flatlist-item' ],
					$checkbox
				);
			}
		}

		return $html;
	}

	protected function getOneCheckbox( $checked, $attribs, $label ) {
		if ( $this->mParent instanceof OOUIHTMLForm ) {
			if ( $this->mOptionsLabelsNotFromMessage ) {
				$label = new OOUI\HtmlSnippet( $label );
			}
			return new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => "{$this->mName}[]",
					'selected' => $checked,
				] + OOUI\Element::configFromHtmlAttributes(
					$attribs
				) ),
				[
					'label' => $label,
					'align' => 'inline',
				]
			);
		} else {
			$elementFunc = [ 'Html', $this->mOptionsLabelsNotFromMessage ? 'rawElement' : 'element' ];
			$checkbox =
				Xml::check( "{$this->mName}[]", $checked, $attribs ) .
				'&#160;' .
				call_user_func( $elementFunc,
					'label',
					[ 'for' => $attribs['id'] ],
					$label
				);
			if ( $this->mParent->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
				$checkbox = Html::openElement( 'div', [ 'class' => 'mw-ui-checkbox' ] ) .
					$checkbox .
					Html::closeElement( 'div' );
			}
			return $checkbox;
		}
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return string
	 */
	function loadDataFromRequest( $request ) {
		if ( $this->mParent->getMethod() == 'post' ) {
			if ( $request->wasPosted() ) {
				# Checkboxes are just not added to the request arrays if they're not checked,
				# so it's perfectly possible for there not to be an entry at all
				return $request->getArray( $this->mName, [] );
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
			return $request->getArray( $this->mName, [] );
		}
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			return $this->mDefault;
		} else {
			return [];
		}
	}

	function filterDataForSubmit( $data ) {
		$data = HTMLFormField::forceToStringRecursive( $data );
		$options = HTMLFormField::flattenOptions( $this->getOptions() );

		$res = [];
		foreach ( $options as $opt ) {
			$res["$opt"] = in_array( $opt, $data, true );
		}

		return $res;
	}

	protected function needsLabel() {
		return false;
	}
}
