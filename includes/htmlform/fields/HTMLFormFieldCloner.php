<?php

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
 *     'table', 'div', or 'raw'.
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
 */
class HTMLFormFieldCloner extends HTMLFormField {
	private static $counter = 0;

	/**
	 * @var string String uniquely identifying this cloner instance and
	 * unlikely to exist otherwise in the generated HTML, while still being
	 * valid as part of an HTML id.
	 */
	protected $uniqueId;

	public function __construct( $params ) {
		$this->uniqueId = static::class . ++self::$counter . 'x';
		parent::__construct( $params );

		if ( empty( $this->mParams['fields'] ) || !is_array( $this->mParams['fields'] ) ) {
			throw new MWException( 'HTMLFormFieldCloner called without any fields' );
		}

		// Make sure the delete button, if explicitly specified, is sane
		if ( isset( $this->mParams['fields']['delete'] ) ) {
			$class = 'mw-htmlform-cloner-delete-button';
			$info = $this->mParams['fields']['delete'] + [
				'formnovalidate' => true,
				'cssclass' => $class
			];
			unset( $info['name'], $info['class'] );

			if ( !isset( $info['type'] ) || $info['type'] !== 'submit' ) {
				throw new MWException(
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
				$info['id'] = Sanitizer::escapeId( "{$this->mID}--$key--{$info['id']}" );
			} else {
				$info['id'] = Sanitizer::escapeId( "{$this->mID}--$key--$fieldname" );
			}
			// Copy the hide-if rules to "child" fields, so that the JavaScript code handling them
			// (resources/src/mediawiki/htmlform/hide-if.js) doesn't have to handle nested fields.
			if ( $this->mHideIf ) {
				if ( isset( $info['hide-if'] ) ) {
					// Hide child field if either its rules say it's hidden, or parent's rules say it's hidden
					$info['hide-if'] = [ 'OR', $info['hide-if'], $this->mHideIf ];
				} else {
					// Hide child field if parent's rules say it's hidden
					$info['hide-if'] = $this->mHideIf;
				}
			}
			$field = HTMLForm::loadInputFromParameters( $name, $info, $this->mParent );
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

	protected function needsLabel() {
		return false;
	}

	public function loadDataFromRequest( $request ) {
		// It's possible that this might be posted with no fields. Detect that
		// by looking for an edit token.
		if ( !$request->getCheck( 'wpEditToken' ) && $request->getArray( $this->mName ) === null ) {
			return $this->getDefault();
		}

		$values = $request->getArray( $this->mName );
		if ( $values === null ) {
			$values = [];
		}

		$ret = [];
		foreach ( $values as $key => $value ) {
			if ( $key === 'create' || isset( $value['delete'] ) ) {
				$ret['nonjs'] = 1;
				continue;
			}

			// Add back in $request->getValues() so things that look for e.g.
			// wpEditToken don't fail.
			$data = $this->rekeyValuesArray( $key, $value ) + $request->getValues();

			$fields = $this->createFieldsForKey( $key );
			$subrequest = new DerivativeRequest( $request, $data, $request->wasPosted() );
			$row = [];
			foreach ( $fields as $fieldname => $field ) {
				if ( $field->skipLoadData( $subrequest ) ) {
					continue;
				} elseif ( !empty( $field->mParams['disabled'] ) ) {
					$row[$fieldname] = $field->getDefault();
				} else {
					$row[$fieldname] = $field->loadDataFromRequest( $subrequest );
				}
			}
			$ret[] = $row;
		}

		if ( isset( $values['create'] ) ) {
			// Non-JS client clicked the "create" button.
			$fields = $this->createFieldsForKey( $this->uniqueId );
			$row = [];
			foreach ( $fields as $fieldname => $field ) {
				if ( !empty( $field->mParams['nodata'] ) ) {
					continue;
				} else {
					$row[$fieldname] = $field->getDefault();
				}
			}
			$ret[] = $row;
		}

		return $ret;
	}

	public function getDefault() {
		$ret = parent::getDefault();

		// The default default is one entry with all subfields at their
		// defaults.
		if ( $ret === null ) {
			$fields = $this->createFieldsForKey( $this->uniqueId );
			$row = [];
			foreach ( $fields as $fieldname => $field ) {
				if ( !empty( $field->mParams['nodata'] ) ) {
					continue;
				} else {
					$row[$fieldname] = $field->getDefault();
				}
			}
			$ret = [ $row ];
		}

		return $ret;
	}

	public function cancelSubmit( $values, $alldata ) {
		if ( isset( $values['nonjs'] ) ) {
			return true;
		}

		foreach ( $values as $key => $value ) {
			$fields = $this->createFieldsForKey( $key );
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
			$fields = $this->createFieldsForKey( $key );
			foreach ( $fields as $fieldname => $field ) {
				if ( !array_key_exists( $fieldname, $value ) ) {
					continue;
				}
				if ( $field->isHidden( $alldata ) ) {
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
		$displayFormat = isset( $this->mParams['format'] )
			? $this->mParams['format']
			: $this->mParent->getDisplayFormat();

		// Conveniently, PHP method names are case-insensitive.
		$getFieldHtmlMethod = $displayFormat == 'table' ? 'getTableRow' : ( 'get' . $displayFormat );

		$html = '';
		$hidden = '';
		$hasLabel = false;

		$fields = $this->createFieldsForKey( $key );
		foreach ( $fields as $fieldname => $field ) {
			$v = array_key_exists( $fieldname, $values )
				? $values[$fieldname]
				: $field->getDefault();

			if ( $field instanceof HTMLHiddenField ) {
				// HTMLHiddenField doesn't generate its own HTML
				list( $name, $value, $params ) = $field->getHiddenFieldData( $v );
				$hidden .= Html::hidden( $name, $value, $params ) . "\n";
			} else {
				$html .= $field->$getFieldHtmlMethod( $v );

				$labelValue = trim( $field->getLabel() );
				if ( $labelValue != '&#160;' && $labelValue !== '' ) {
					$hasLabel = true;
				}
			}
		}

		if ( !isset( $fields['delete'] ) ) {
			$name = "{$this->mName}[$key][delete]";
			$label = isset( $this->mParams['delete-button-message'] )
				? $this->mParams['delete-button-message']
				: 'htmlform-cloner-delete';
			$field = HTMLForm::loadInputFromParameters( $name, [
				'type' => 'submit',
				'formnovalidate' => true,
				'name' => $name,
				'id' => Sanitizer::escapeId( "{$this->mID}--$key--delete" ),
				'cssclass' => 'mw-htmlform-cloner-delete-button',
				'default' => $this->getMessage( $label )->text(),
			], $this->mParent );
			$v = $field->getDefault();

			if ( $displayFormat === 'table' ) {
				$html .= $field->$getFieldHtmlMethod( $v );
			} else {
				$html .= $field->getInputHTML( $v );
			}
		}

		if ( $displayFormat !== 'raw' ) {
			$classes = [
				'mw-htmlform-cloner-row',
			];

			if ( !$hasLabel ) { // Avoid strange spacing when no labels exist
				$classes[] = 'mw-htmlform-nolabel';
			}

			$attribs = [
				'class' => implode( ' ', $classes ),
			];

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
			$html = Xml::fieldset( $legend, $html );
		}

		return $html;
	}

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

		$name = "{$this->mName}[create]";
		$label = isset( $this->mParams['create-button-message'] )
			? $this->mParams['create-button-message']
			: 'htmlform-cloner-create';
		$field = HTMLForm::loadInputFromParameters( $name, [
			'type' => 'submit',
			'formnovalidate' => true,
			'name' => $name,
			'id' => Sanitizer::escapeId( "{$this->mID}--create" ),
			'cssclass' => 'mw-htmlform-cloner-create-button',
			'default' => $this->getMessage( $label )->text(),
		], $this->mParent );
		$html .= $field->getInputHTML( $field->getDefault() );

		return $html;
	}
}
