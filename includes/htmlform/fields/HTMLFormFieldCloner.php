<?php

namespace MediaWiki\HTMLForm\Field;

use InvalidArgumentException;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Request\DerivativeRequest;

/**
 * A container for HTMLFormFields that allows for multiple copies of the set of
 * fields to be displayed to and entered by the user.
 *
 * Recognized parameters, besides the general ones, include:
 *   fields - HTMLFormField descriptors for the subfields this cloner manages.
 *     The format is just like for the HTMLForm. A field with key 'delete' is
 *     special: it must have type = submit and will serve to delete the group
 *     of fields.
 *   required - If specified, at least one group of fields must be submitted.
 *   format - HTMLForm display format to use when displaying the subfields:
 *     'table', 'div', or 'raw'. This is ignored when using OOUI.
 *   row-legend - If non-empty, each group of subfields will be enclosed in a
 *     fieldset. The value is the name of a message key to use as the legend.
 *   create-button-message - Message to use as the text of the button to
 *     add an additional group of fields.
 *   delete-button-message - Message to use as the text of automatically-
 *     generated 'delete' button. Ignored if 'delete' is included in 'fields'.
 *
 * In the generated HTML, the subfields will be named along the lines of
 * "clonerName[index][fieldname]", with ids "clonerId--index--fieldid". 'index'
 * may be a number or an arbitrary string, and may likely change when the page
 * is resubmitted. Cloners may be nested, resulting in field names along the
 * lines of "cloner1Name[index1][cloner2Name][index2][fieldname]" and
 * corresponding ids.
 *
 * Use of cloner may result in submissions of the page that are not submissions
 * of the HTMLForm, when non-JavaScript clients use the create or remove buttons.
 *
 * The result is an array, with values being arrays mapping subfield names to
 * their values. On non-HTMLForm-submission page loads, there may also be
 * additional (string) keys present with other types of values.
 *
 * @since 1.23
 * @stable to extend
 */
class HTMLFormFieldCloner extends HTMLFormField {
	/** @var int */
	private static $counter = 0;

	/**
	 * @var string String uniquely identifying this cloner instance and
	 * unlikely to exist otherwise in the generated HTML, while still being
	 * valid as part of an HTML id.
	 */
	protected $uniqueId;

	/** @var array<string, HTMLFormField[]> */
	protected $mFields = [];

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		$this->uniqueId = $this->getClassName() . ++self::$counter . 'x';
		parent::__construct( $params );

		if ( empty( $this->mParams['fields'] ) || !is_array( $this->mParams['fields'] ) ) {
			throw new InvalidArgumentException( 'HTMLFormFieldCloner called without any fields' );
		}

		// Make sure the delete button, if explicitly specified, is sensible
		if ( isset( $this->mParams['fields']['delete'] ) ) {
			$class = 'mw-htmlform-cloner-delete-button';
			$info = $this->mParams['fields']['delete'] + [
				'formnovalidate' => true,
				'cssclass' => $class
			];
			unset( $info['name'], $info['class'] );

			if ( !isset( $info['type'] ) || $info['type'] !== 'submit' ) {
				throw new InvalidArgumentException(
					'HTMLFormFieldCloner delete field, if specified, must be of type "submit"'
				);
			}

			if ( !in_array( $class, explode( ' ', $info['cssclass'] ) ) ) {
				$info['cssclass'] .= " $class";
			}

			$this->mParams['fields']['delete'] = $info;
		}
	}

	/**
	 * @param string $key Array key under which these fields should be named
	 * @return HTMLFormField[]
	 */
	protected function getFieldsForKey( $key ) {
		if ( !isset( $this->mFields[$key] ) ) {
			$this->mFields[$key] = $this->createFieldsForKey( $key );
		}
		return $this->mFields[$key];
	}

	/**
	 * Create the HTMLFormFields that go inside this element, using the
	 * specified key.
	 *
	 * @param string $key Array key under which these fields should be named
	 * @return HTMLFormField[]
	 */
	protected function createFieldsForKey( $key ) {
		$fields = [];
		foreach ( $this->mParams['fields'] as $fieldname => $info ) {
			$name = "{$this->mName}[$key][$fieldname]";
			if ( isset( $info['name'] ) ) {
				$info['name'] = "{$this->mName}[$key][{$info['name']}]";
			} else {
				$info['name'] = $name;
			}
			if ( isset( $info['id'] ) ) {
				$info['id'] = Sanitizer::escapeIdForAttribute( "{$this->mID}--$key--{$info['id']}" );
			} else {
				$info['id'] = Sanitizer::escapeIdForAttribute( "{$this->mID}--$key--$fieldname" );
			}
			// Copy the hide-if and disable-if rules to "child" fields, so that the JavaScript code handling them
			// (resources/src/mediawiki.htmlform/cond-state.js) doesn't have to handle nested fields.
			if ( $this->mCondState ) {
				foreach ( [ 'hide', 'disable' ] as $type ) {
					if ( !isset( $this->mCondState[$type] ) ) {
						continue;
					}
					$param = $type . '-if';
					if ( isset( $info[$param] ) ) {
						// Hide or disable child field if either its rules say so, or parent's rules say so.
						$info[$param] = [ 'OR', $info[$param], $this->mCondState[$type] ];
					} else {
						// Hide or disable child field if parent's rules say so.
						$info[$param] = $this->mCondState[$type];
					}
				}
			}
			$cloner = $this;
			$info['cloner'] = &$cloner;
			$info['cloner-key'] = $key;
			$field = HTMLForm::loadInputFromParameters( $fieldname, $info, $this->mParent );
			$fields[$fieldname] = $field;
		}
		return $fields;
	}

	/**
	 * Re-key the specified values array to match the names applied by
	 * createFieldsForKey().
	 *
	 * @param string $key Array key under which these fields should be named
	 * @param array $values Values array from the request
	 * @return array
	 */
	protected function rekeyValuesArray( $key, $values ) {
		$data = [];
		foreach ( $values as $fieldname => $value ) {
			$name = "{$this->mName}[$key][$fieldname]";
			$data[$name] = $value;
		}
		return $data;
	}

	/**
	 * @param string $name
	 * @return string[]
	 */
	protected function parseFieldPath( $name ) {
		$fieldKeys = [];
		while ( preg_match( '/^(.+)\[([^\]]+)\]$/', $name, $m ) ) {
			array_unshift( $fieldKeys, $m[2] );
			$name = $m[1];
		}
		array_unshift( $fieldKeys, $name );
		return $fieldKeys;
	}

	/**
	 * Find the nearest field to a field in this cloner matched the given name,
	 * walk through the chain of cloners.
	 *
	 * @param HTMLFormField $field
	 * @param string $find
	 * @return HTMLFormField|null
	 */
	public function findNearestField( $field, $find ) {
		$findPath = $this->parseFieldPath( $find );
		// Access to fields as child or in other group is not allowed.
		// Further support for a more complicated path may conduct here.
		if ( count( $findPath ) > 1 ) {
			return null;
		}
		if ( !isset( $this->mParams['fields'][$find] ) ) {
			$cloner = $this->mParams['cloner'] ?? null;
			if ( $cloner instanceof self ) {
				return $cloner->findNearestField( $this, $find );
			}
			return null;
		}
		$fields = $this->getFieldsForKey( $field->mParams['cloner-key'] );
		return $fields[$find];
	}

	/**
	 * @param HTMLFormField $field
	 * @return string[]
	 */
	protected function getFieldPath( $field ) {
		$path = [ $this->mParams['fieldname'], $field->mParams['cloner-key'] ];
		$cloner = $this->mParams['cloner'] ?? null;
		if ( $cloner instanceof self ) {
			$path = array_merge( $cloner->getFieldPath( $this ), $path );
		}
		return $path;
	}

	/**
	 * Extract field data for a given field that belongs to this cloner.
	 *
	 * @param HTMLFormField $field
	 * @param mixed[] $alldata
	 * @return mixed
	 */
	public function extractFieldData( $field, $alldata ) {
		foreach ( $this->getFieldPath( $field ) as $key ) {
			$alldata = $alldata[$key];
		}
		return $alldata[$field->mParams['fieldname']];
	}

	/** @inheritDoc */
	protected function needsLabel() {
		return false;
	}

	/** @inheritDoc */
	public function loadDataFromRequest( $request ) {
		// It's possible that this might be posted with no fields. Detect that
		// by looking for an edit token.
		if ( !$request->getCheck( 'wpEditToken' ) && $request->getArray( $this->mName ) === null ) {
			return $this->getDefault();
		}

		$values = $request->getArray( $this->mName ) ?? [];

		$ret = [];
		foreach ( $values as $key => $value ) {
			if ( $key === 'create' || isset( $value['delete'] ) ) {
				$ret['nonjs'] = 1;
				continue;
			}

			// Add back in $request->getValues() so things that look for e.g.
			// wpEditToken don't fail.
			$data = $this->rekeyValuesArray( $key, $value ) + $request->getValues();

			$fields = $this->getFieldsForKey( $key );
			$subrequest = new DerivativeRequest( $request, $data, $request->wasPosted() );
			$row = [];
			foreach ( $fields as $fieldname => $field ) {
				if ( $field->skipLoadData( $subrequest ) ) {
					continue;
				}
				if ( !empty( $field->mParams['disabled'] ) ) {
					$row[$fieldname] = $field->getDefault();
				} else {
					$row[$fieldname] = $field->loadDataFromRequest( $subrequest );
				}
			}
			$ret[] = $row;
		}

		if ( isset( $values['create'] ) ) {
			// Non-JS client clicked the "create" button.
			$fields = $this->getFieldsForKey( $this->uniqueId );
			$row = [];
			foreach ( $fields as $fieldname => $field ) {
				if ( !empty( $field->mParams['nodata'] ) ) {
					continue;
				}
				$row[$fieldname] = $field->getDefault();
			}
			$ret[] = $row;
		}

		return $ret;
	}

	/** @inheritDoc */
	public function getDefault() {
		$ret = parent::getDefault();

		// The default is one entry with all subfields at their defaults.
		if ( $ret === null ) {
			$fields = $this->getFieldsForKey( $this->uniqueId );
			$row = [];
			foreach ( $fields as $fieldname => $field ) {
				if ( !empty( $field->mParams['nodata'] ) ) {
					continue;
				}
				$row[$fieldname] = $field->getDefault();
			}
			$ret = [ $row ];
		}

		return $ret;
	}

	/**
	 * @inheritDoc
	 * @phan-param array[] $values
	 */
	public function cancelSubmit( $values, $alldata ) {
		if ( isset( $values['nonjs'] ) ) {
			return true;
		}

		foreach ( $values as $key => $value ) {
			$fields = $this->getFieldsForKey( $key );
			foreach ( $fields as $fieldname => $field ) {
				if ( !array_key_exists( $fieldname, $value ) ) {
					continue;
				}
				if ( $field->cancelSubmit( $value[$fieldname], $alldata ) ) {
					return true;
				}
			}
		}

		return parent::cancelSubmit( $values, $alldata );
	}

	/**
	 * @inheritDoc
	 * @phan-param array[] $values
	 */
	public function validate( $values, $alldata ) {
		if ( isset( $this->mParams['required'] )
			&& $this->mParams['required'] !== false
			&& !$values
		) {
			return $this->msg( 'htmlform-cloner-required' );
		}

		if ( isset( $values['nonjs'] ) ) {
			// The submission was a non-JS create/delete click, so fail
			// validation in case cancelSubmit() somehow didn't already handle
			// it.
			return false;
		}

		foreach ( $values as $key => $value ) {
			$fields = $this->getFieldsForKey( $key );
			foreach ( $fields as $fieldname => $field ) {
				if ( !array_key_exists( $fieldname, $value ) || $field->isHidden( $alldata ) ) {
					continue;
				}
				$ok = $field->validate( $value[$fieldname], $alldata );
				if ( $ok !== true ) {
					return false;
				}
			}
		}

		return parent::validate( $values, $alldata );
	}

	/**
	 * Get the input HTML for the specified key.
	 *
	 * @param string $key Array key under which the fields should be named
	 * @param array $values
	 * @return string
	 */
	protected function getInputHTMLForKey( $key, array $values ) {
		$displayFormat = $this->mParams['format'] ?? $this->mParent->getDisplayFormat();

		// Conveniently, PHP method names are case-insensitive.
		$getFieldHtmlMethod = $displayFormat == 'table' ? 'getTableRow' : ( 'get' . $displayFormat );

		$html = '';
		$hidden = '';
		$hasLabel = false;

		$fields = $this->getFieldsForKey( $key );
		foreach ( $fields as $fieldname => $field ) {
			$v = array_key_exists( $fieldname, $values )
				? $values[$fieldname]
				: $field->getDefault();

			if ( $field instanceof HTMLHiddenField ) {
				// HTMLHiddenField doesn't generate its own HTML
				[ $name, $value, $params ] = $field->getHiddenFieldData( $v );
				$hidden .= Html::hidden( $name, $value, $params ) . "\n";
			} else {
				$html .= $field->$getFieldHtmlMethod( $v );

				$labelValue = trim( $field->getLabel() );
				if ( $labelValue !== "\u{00A0}" && $labelValue !== '&#160;' && $labelValue !== '' ) {
					$hasLabel = true;
				}
			}
		}

		if ( !isset( $fields['delete'] ) ) {
			$field = $this->getDeleteButtonHtml( $key );

			if ( $displayFormat === 'table' ) {
				$html .= $field->$getFieldHtmlMethod( $field->getDefault() );
			} else {
				$html .= $field->getInputHTML( $field->getDefault() );
			}
		}

		if ( $displayFormat !== 'raw' ) {
			$classes = [ 'mw-htmlform-cloner-row' ];

			if ( !$hasLabel ) { // Avoid strange spacing when no labels exist
				$classes[] = 'mw-htmlform-nolabel';
			}

			$attribs = [ 'class' => $classes ];

			if ( $displayFormat === 'table' ) {
				$html = Html::rawElement( 'table',
					$attribs,
					Html::rawElement( 'tbody', [], "\n$html\n" ) ) . "\n";
			} else {
				$html = Html::rawElement( 'div', $attribs, "\n$html\n" );
			}
		}

		$html .= $hidden;

		if ( !empty( $this->mParams['row-legend'] ) ) {
			$legend = $this->msg( $this->mParams['row-legend'] )->text();
			$legend = $legend ? Html::element( 'legend', [], $legend ) : '';
			$html = Html::rawElement(
				'fieldset',
				[],
				$legend . $html
			);
		}

		return $html;
	}

	/**
	 * @param string $key Array key indicating to which field the delete button belongs
	 * @return HTMLFormField
	 */
	protected function getDeleteButtonHtml( $key ): HTMLFormField {
		$name = "{$this->mName}[$key][delete]";
		$label = $this->mParams['delete-button-message'] ?? 'htmlform-cloner-delete';
		$field = HTMLForm::loadInputFromParameters( $name, [
			'type' => 'submit',
			'formnovalidate' => true,
			'name' => $name,
			'id' => Sanitizer::escapeIdForAttribute( "{$this->mID}--$key--delete" ),
			'cssclass' => 'mw-htmlform-cloner-delete-button',
			'default' => $this->getMessage( $label )->text(),
			'disabled' => $this->mParams['disabled'] ?? false,
		], $this->mParent );
		return $field;
	}

	protected function getCreateButtonHtml(): HTMLFormField {
		$name = "{$this->mName}[create]";
		$label = $this->mParams['create-button-message'] ?? 'htmlform-cloner-create';
		return HTMLForm::loadInputFromParameters( $name, [
			'type' => 'submit',
			'formnovalidate' => true,
			'name' => $name,
			'id' => Sanitizer::escapeIdForAttribute( "{$this->mID}--create" ),
			'cssclass' => 'mw-htmlform-cloner-create-button',
			'default' => $this->getMessage( $label )->text(),
			'disabled' => $this->mParams['disabled'] ?? false,
		], $this->mParent );
	}

	/** @inheritDoc */
	public function getInputHTML( $values ) {
		$html = '';

		foreach ( (array)$values as $key => $value ) {
			if ( $key === 'nonjs' ) {
				continue;
			}
			$html .= Html::rawElement( 'li', [ 'class' => 'mw-htmlform-cloner-li' ],
				$this->getInputHTMLForKey( $key, $value )
			);
		}

		$template = $this->getInputHTMLForKey( $this->uniqueId, [] );
		$html = Html::rawElement( 'ul', [
			'id' => "mw-htmlform-cloner-list-{$this->mID}",
			'class' => 'mw-htmlform-cloner-ul',
			'data-template' => $template,
			'data-unique-id' => $this->uniqueId,
		], $html );

		$field = $this->getCreateButtonHtml();
		$html .= $field->getInputHTML( $field->getDefault() );

		return $html;
	}

	/**
	 * Get the input OOUI HTML for the specified key.
	 *
	 * @param string $key Array key under which the fields should be named
	 * @param array $values
	 * @return string
	 */
	protected function getInputOOUIForKey( $key, array $values ) {
		$html = '';
		$hidden = '';

		$fields = $this->getFieldsForKey( $key );
		foreach ( $fields as $fieldname => $field ) {
			$v = array_key_exists( $fieldname, $values )
				? $values[$fieldname]
				: $field->getDefault();

			if ( $field instanceof HTMLHiddenField ) {
				// HTMLHiddenField doesn't generate its own HTML
				[ $name, $value, $params ] = $field->getHiddenFieldData( $v );
				$hidden .= Html::hidden( $name, $value, $params ) . "\n";
			} else {
				$html .= $field->getOOUI( $v );
			}
		}

		if ( !isset( $fields['delete'] ) ) {
			$field = $this->getDeleteButtonHtml( $key );
			$fieldHtml = $field->getInputOOUI( $field->getDefault() );
			$fieldHtml->setInfusable( true );

			$html .= $fieldHtml;
		}

		$html = Html::rawElement( 'div', [ 'class' => 'mw-htmlform-cloner-row' ], "\n$html\n" );

		$html .= $hidden;

		if ( !empty( $this->mParams['row-legend'] ) ) {
			$legend = $this->msg( $this->mParams['row-legend'] )->text();
			$legend = $legend ? Html::element( 'legend', [], $legend ) : '';
			$html = Html::rawElement(
				'fieldset',
				[],
				$legend . $html
			);
		}

		return $html;
	}

	/** @inheritDoc */
	public function getInputOOUI( $values ) {
		$html = '';

		foreach ( (array)$values as $key => $value ) {
			if ( $key === 'nonjs' ) {
				continue;
			}
			$html .= Html::rawElement( 'li', [ 'class' => 'mw-htmlform-cloner-li' ],
				$this->getInputOOUIForKey( $key, $value )
			);
		}

		$template = $this->getInputOOUIForKey( $this->uniqueId, [] );
		$html = Html::rawElement( 'ul', [
			'id' => "mw-htmlform-cloner-list-{$this->mID}",
			'class' => 'mw-htmlform-cloner-ul',
			'data-template' => $template,
			'data-unique-id' => $this->uniqueId,
		], $html );

		$field = $this->getCreateButtonHtml();
		$fieldHtml = $field->getInputOOUI( $field->getDefault() );
		$fieldHtml->setInfusable( true );

		$html .= $fieldHtml;

		return $html;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLFormFieldCloner::class, 'HTMLFormFieldCloner' );
