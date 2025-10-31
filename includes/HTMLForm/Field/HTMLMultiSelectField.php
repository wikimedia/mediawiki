<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\HTMLForm\HTMLNestedFilterable;
use MediaWiki\HTMLForm\OOUIHTMLForm;
use MediaWiki\Request\WebRequest;
use MediaWiki\Widget\MenuTagMultiselectWidget;
use RuntimeException;

/**
 * Multi-select field
 *
 * @stable to extend
 */
class HTMLMultiSelectField extends HTMLFormField implements HTMLNestedFilterable {

	private bool $mDropdown = false;

	private ?string $mPlaceholder = null;

	/**
	 * @stable to call
	 *
	 * @param array $params
	 *   In adition to the usual HTMLFormField parameters, this can take the following fields:
	 *   - dropdown: If given, the options will be displayed inside a dropdown with a text field that
	 *     can be used to filter them. This is desirable mostly for very long lists of options.
	 *     This only works for users with JavaScript support and falls back to the list of checkboxes.
	 *   - flatlist: If given, the options will be displayed on a single line (wrapping to following
	 *     lines if necessary), rather than each one on a line of its own. This is desirable mostly
	 *     for very short lists of concisely labelled options.
	 *   - max: Maximum number of elements that can be selected. On the client-side, this is only
	 *     enforced when using a dropdown.
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		// If the disabled-options parameter is not provided, use an empty array
		if ( !isset( $this->mParams['disabled-options'] ) ) {
			$this->mParams['disabled-options'] = [];
		}

		if ( isset( $params['dropdown'] ) ) {
			$this->mDropdown = true;
			if ( isset( $params['placeholder'] ) ) {
				$this->mPlaceholder = $params['placeholder'];
			} elseif ( isset( $params['placeholder-message'] ) ) {
				$this->mPlaceholder = $this->msg( $params['placeholder-message'] )->text();
			}
		}

		if ( isset( $params['flatlist'] ) ) {
			$this->mClass .= ' mw-htmlform-flatlist';
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !is_array( $value ) ) {
			return false;
		}

		// Reject nested arrays (T274955)
		$value = array_filter( $value, 'is_scalar' );

		if ( isset( $this->mParams['required'] )
			&& $this->mParams['required'] !== false
			&& $value === []
		) {
			return $this->msg( 'htmlform-required' );
		}

		if ( isset( $this->mParams['max'] ) && ( count( $value ) > $this->mParams['max'] ) ) {
			return $this->msg( 'htmlform-multiselect-toomany', $this->mParams['max'] );
		}

		# If all options are valid, array_intersect of the valid options
		# and the provided options will return the provided options.
		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		$validValues = array_intersect( $value, $validOptions );
		if ( count( $validValues ) == count( $value ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputHTML( $value ) {
		$value = HTMLFormField::forceToStringRecursive( $value );
		$html = $this->formatOptions( $this->getOptions(), $value );

		return $html;
	}

	/**
	 * @stable to override
	 *
	 * @param array $options
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function formatOptions( $options, $value ) {
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
				if ( in_array( $info, $this->mParams['disabled-options'], true ) ) {
					$thisAttribs['disabled'] = 'disabled';
				}
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

	/**
	 * @param bool $checked
	 * @param array $attribs
	 * @param string $label
	 * @return string
	 */
	protected function getOneCheckbox( $checked, $attribs, $label ) {
		if ( $this->mParent instanceof OOUIHTMLForm ) {
			throw new RuntimeException( __METHOD__ . ' is not supported' );
		} else {
			$checkbox =
				Html::check( "{$this->mName}[]", $checked, $attribs ) .
				"\u{00A0}" .
				Html::rawElement(
					'label',
					[ 'for' => $attribs['id'] ],
					$this->escapeLabel( $label )
				);
			return $checkbox;
		}
	}

	/** @inheritDoc */
	public function getOptionsOOUI() {
		$optionsOouiSections = [];
		$options = $this->getOptions();

		// If the options are supposed to be split into sections, each section becomes a separate
		// CheckboxMultiselectInputWidget.
		foreach ( $options as $label => $section ) {
			if ( is_array( $section ) ) {
				$optionsOouiSections[ $label ] = Html::listDropdownOptionsOoui( $section );
				unset( $options[$label] );
			}
		}

		// If anything remains in the array, they are sectionless options. Put them at the beginning.
		if ( $options ) {
			$optionsOouiSections = array_merge(
				[ '' => Html::listDropdownOptionsOoui( $options ) ],
				$optionsOouiSections
			);
		}

		return $optionsOouiSections;
	}

	/**
	 * Get the OOUI version of this field.
	 *
	 * Returns OOUI\CheckboxMultiselectInputWidget for fields that only have one section,
	 * string otherwise.
	 *
	 * @stable to override
	 * @since 1.28
	 * @param string[] $value
	 * @return \OOUI\Widget|string
	 * @suppress PhanParamSignatureMismatch
	 */
	public function getInputOOUI( $value ) {
		$this->mParent->getOutput()->addModules( 'oojs-ui-widgets' );
		if ( $this->mDropdown ) {
			$this->mParent->getOutput()->addModuleStyles( 'mediawiki.widgets.TagMultiselectWidget.styles' );
		}

		// Reject nested arrays (T274955)
		$value = array_filter( $value, 'is_scalar' );

		$out = [];
		$optionsSections = $this->getOptionsOOUI();
		foreach ( $optionsSections as $sectionLabel => &$groupedOptions ) {
			$attr = [];
			$attr['name'] = "{$this->mName}[]";

			$attr['value'] = $value;

			foreach ( $groupedOptions as &$option ) {
				$option['disabled'] = in_array( $option['data'], $this->mParams['disabled-options'], true );
			}
			foreach ( $groupedOptions as &$option ) {
				$option['label'] = $this->makeLabelSnippet( $option['label'] );
			}
			unset( $option );
			$attr['options'] = $groupedOptions;

			$attr += \OOUI\Element::configFromHtmlAttributes(
				$this->getAttributes( [ 'disabled', 'tabindex' ] )
			);

			if ( $this->mClass !== '' ) {
				$attr['classes'] = [ $this->mClass ];
			}

			$widget = new \OOUI\CheckboxMultiselectInputWidget( $attr );
			if ( $sectionLabel ) {
				$out[] = new \OOUI\FieldsetLayout( [
					'items' => [ $widget ],
					'label' => $this->makeLabelSnippet( $sectionLabel ),
				] );
			} else {
				$out[] = $widget;
			}
		}
		unset( $groupedOptions );

		$params = [];
		if ( $this->mPlaceholder ) {
			$params['placeholder'] = $this->mPlaceholder;
		}
		if ( isset( $this->mParams['max'] ) ) {
			$params['tagLimit'] = $this->mParams['max'];
		}
		if ( $this->mDropdown ) {
			return new MenuTagMultiselectWidget( [
				'name' => $this->mName,
				'options' => $optionsSections,
				'default' => $value,
				'noJsFallback' => $out,
				'allowReordering' => false,
			] + $params );
		} elseif ( count( $out ) === 1 ) {
			$firstFieldData = $out[0]->getData() ?: [];
			$out[0]->setData( $firstFieldData + $params );
			// Directly return the only OOUI\CheckboxMultiselectInputWidget.
			// This allows it to be made infusable and later tweaked by JS code.
			return $out[0];
		}

		return implode( '', $out );
	}

	/** @inheritDoc */
	protected function getOOUIModules() {
		return $this->mDropdown ? [ 'mediawiki.widgets.MenuTagMultiselectWidget' ] : [];
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return $this->mDropdown;
	}

	/**
	 * @stable to override
	 * @param WebRequest $request
	 *
	 * @return string|array
	 */
	public function loadDataFromRequest( $request ) {
		$fromRequest = $request->getArray( $this->mName, [] );
		// Fetch the value in either one of the two following case:
		// - we have a valid submit attempt (form was just submitted)
		// - we have a value (an URL manually built by the user, or GET form with no wpFormIdentifier)
		if ( $this->isSubmitAttempt( $request ) || $fromRequest ) {
			// Checkboxes are just not added to the request arrays if they're not checked,
			// so it's perfectly possible for there not to be an entry at all
			// @phan-suppress-next-line PhanTypeMismatchReturnNullable getArray does not return null
			return $fromRequest;
		} else {
			// That's ok, the user has not yet submitted the form, so show the defaults
			return $this->getDefault();
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getDefault() {
		return $this->mDefault ?? [];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function filterDataForSubmit( $data ) {
		$data = HTMLFormField::forceToStringRecursive( $data );
		$options = HTMLFormField::flattenOptions( $this->getOptions() );
		$forcedOn = array_intersect( $this->mParams['disabled-options'], $this->getDefault() );

		$res = [];
		foreach ( $options as $opt ) {
			$res["$opt"] = in_array( $opt, $forcedOn, true ) || in_array( $opt, $data, true );
		}

		return $res;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function needsLabel() {
		return false;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLMultiSelectField::class, 'HTMLMultiSelectField' );
