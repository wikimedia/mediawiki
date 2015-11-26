<?php

/**
 * HTML form generation and submission handling, OOUI style.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Compact stacked vertical format for forms, implemented using OOUI widgets.
 */
class OOUIHTMLForm extends HTMLForm {
<<<<<<< HEAD
	/**
	 * Wrapper and its legend are never generated in OOUI mode.
	 * @var boolean
	 */
	protected $mWrapperLegend = false;
=======
	private $oouiErrors;
>>>>>>> 365e22ee61035f953b47387af92ef832f09d5982

	public function __construct( $descriptor, $context = null, $messagePrefix = '' ) {
		parent::__construct( $descriptor, $context, $messagePrefix );
		$this->getOutput()->enableOOUI();
<<<<<<< HEAD
		$this->getOutput()->addModules( 'mediawiki.htmlform.ooui' );
=======
>>>>>>> 365e22ee61035f953b47387af92ef832f09d5982
		$this->getOutput()->addModuleStyles( 'mediawiki.htmlform.ooui.styles' );
	}

	/**
	 * Symbolic display format name.
	 * @var string
	 */
	protected $displayFormat = 'ooui';

	public static function loadInputFromParameters( $fieldname, $descriptor, HTMLForm $parent = null ) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	function getButtons() {
		$buttons = '';

		if ( $this->mShowSubmit ) {
<<<<<<< HEAD
			$attribs = array();
=======
			$attribs = array( 'infusable' => true );
>>>>>>> 365e22ee61035f953b47387af92ef832f09d5982

			if ( isset( $this->mSubmitID ) ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( isset( $this->mSubmitName ) ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( isset( $this->mSubmitTooltip ) ) {
				$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
			}

<<<<<<< HEAD
			$attribs['classes'] = array(
				'mw-htmlform-submit',
				$this->mSubmitModifierClass,
			);

			$attribs['type'] = 'submit';
			$attribs['label'] = $this->getSubmitText();
			$attribs['value'] = $this->getSubmitText();
			$attribs['flags'] = array( 'primary', 'constructive' );
=======
			$attribs['classes'] = array( 'mw-htmlform-submit' );
			$attribs['type'] = 'submit';
			$attribs['label'] = $this->getSubmitText();
			$attribs['value'] = $this->getSubmitText();
			$attribs['flags'] = $this->mSubmitFlags;
>>>>>>> 365e22ee61035f953b47387af92ef832f09d5982

			$buttons .= new OOUI\ButtonInputWidget( $attribs );
		}

		if ( $this->mShowReset ) {
			$buttons .= new OOUI\ButtonInputWidget( array(
				'type' => 'reset',
				'label' => $this->msg( 'htmlform-reset' )->text(),
			) );
		}

		foreach ( $this->mButtons as $button ) {
			$attrs = array();

			if ( $button['attribs'] ) {
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			$attrs['classes'] = isset( $attrs['class'] ) ? (array)$attrs['class'] : array();

			$buttons .= new OOUI\ButtonInputWidget( array(
				'type' => 'submit',
				'name' => $button['name'],
				'value' => $button['value'],
				'label' => $button['value'],
			) + $attrs );
		}

		$html = Html::rawElement( 'div',
			array( 'class' => 'mw-htmlform-submit-buttons' ), "\n$buttons" ) . "\n";

		return $html;
	}

<<<<<<< HEAD
	function getFormAttributes() {
		$attribs = parent::getFormAttributes();
		if ( !isset( $attribs['class'] ) ) {
			$attribs['class'] = '';
		}

		if ( is_string( $attribs['class'] ) ) {
			$attribs['class'] = trim( $attribs['class'] . ' mw-htmlform-ooui' );
		} else {
			$attribs['class'][] = 'mw-htmlform-ooui';
		}

		return $attribs;
	}

	function wrapForm( $html ) {
		// Always discard $this->mWrapperLegend
		return Html::rawElement( 'form', $this->getFormAttributes(), $html );
=======
	/**
	 * Put a form section together from the individual fields' HTML, merging it and wrapping.
	 * @param OOUI\\FieldLayout[] $fieldsHtml
	 * @param string $sectionName
	 * @param bool $anyFieldHasLabel Unused
	 * @return string HTML
	 */
	protected function formatSection( array $fieldsHtml, $sectionName, $anyFieldHasLabel ) {
		$config = array(
			'items' => $fieldsHtml,
		);
		if ( $sectionName ) {
			$config['id'] = Sanitizer::escapeId( $sectionName );
		}
		if ( is_string( $this->mWrapperLegend ) ) {
			$config['label'] = $this->mWrapperLegend;
		}
		return new OOUI\FieldsetLayout( $config );
	}

	/**
	 * @param string|array|Status $err
	 * @return string
	 */
	function getErrors( $err ) {
		if ( !$err ) {
			$errors = array();
		} else if ( $err instanceof Status ) {
			if ( $err->isOK() ) {
				$errors = array();
			} else {
				$errors = $err->getErrorsByType( 'error' );
				foreach ( $errors as &$error ) {
					// Input:  array( 'message' => 'foo', 'errors' => array( 'a', 'b', 'c' ) )
					// Output: array( 'foo', 'a', 'b', 'c' )
					$error = array_merge( array( $error['message'] ), $error['params'] );
				}
			}
		} else {
			$errors = $err;
			if ( !is_array( $errors ) ) {
				$errors = array( $errors );
			}
		}

		foreach ( $errors as &$error ) {
			if ( is_array( $error ) ) {
				$msg = array_shift( $error );
			} else {
				$msg = $error;
				$error = array();
			}
			$error = $this->msg( $msg, $error )->parse();
			$error = new OOUI\HtmlSnippet( $error );
		}

		// Used in getBody()
		$this->oouiErrors = $errors;
		return '';
	}

	function getHeaderText( $section = null ) {
		if ( is_null( $section ) ) {
			// We handle $this->mHeader elsewhere, in getBody()
			return '';
		} else {
			return parent::getHeaderText( $section );
		}
	}

	function getBody() {
		$fieldset = parent::getBody();
		// FIXME This only works for forms with no subsections
		if ( $fieldset instanceof OOUI\FieldsetLayout ) {
			$classes = array( 'mw-htmlform-ooui-header' );
			if ( !$this->mHeader ) {
				$classes[] = 'mw-htmlform-ooui-header-empty';
			}
			if ( $this->oouiErrors ) {
				$classes[] = 'mw-htmlform-ooui-header-errors';
			}
			$fieldset->addItems( array(
				new OOUI\FieldLayout(
					new OOUI\LabelWidget( array( 'label' => new OOUI\HtmlSnippet( $this->mHeader ) ) ),
					array(
						'align' => 'top',
						'errors' => $this->oouiErrors,
						'classes' => $classes,
					)
				)
			), 0 );
		}
		return $fieldset;
	}

	function wrapForm( $html ) {
		$form = new OOUI\FormLayout( $this->getFormAttributes() + array(
			'classes' => array( 'mw-htmlform-ooui' ),
			'content' => new OOUI\HtmlSnippet( $html ),
		) );

		// Include a wrapper for style, if requested.
		$form = new OOUI\PanelLayout( array(
			'classes' => array( 'mw-htmlform-ooui-wrapper' ),
			'expanded' => false,
			'padded' => $this->mWrapperLegend !== false,
			'framed' => $this->mWrapperLegend !== false,
			'content' => $form,
		) );

		return $form;
>>>>>>> 365e22ee61035f953b47387af92ef832f09d5982
	}
}
