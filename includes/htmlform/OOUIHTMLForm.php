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
	private $oouiErrors;
	private $oouiWarnings;

	public function __construct( $descriptor, $context = null, $messagePrefix = '' ) {
		parent::__construct( $descriptor, $context, $messagePrefix );
		$this->getOutput()->enableOOUI();
		$this->getOutput()->addModuleStyles( 'mediawiki.htmlform.ooui.styles' );
	}

	/**
	 * Symbolic display format name.
	 * @var string
	 */
	protected $displayFormat = 'ooui';

	public static function loadInputFromParameters( $fieldname, $descriptor,
		HTMLForm $parent = null
	) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	public function getButtons() {
		$buttons = '';

		// IE<8 has bugs with <button>, so we'll need to avoid them.
		$isBadIE = preg_match( '/MSIE [1-7]\./i', $this->getRequest()->getHeader( 'User-Agent' ) );

		if ( $this->mShowSubmit ) {
			$attribs = [ 'infusable' => true ];

			if ( isset( $this->mSubmitID ) ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( isset( $this->mSubmitName ) ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( isset( $this->mSubmitTooltip ) ) {
				$attribs += [
					'title' => Linker::titleAttrib( $this->mSubmitTooltip ),
					'accessKey' => Linker::accesskey( $this->mSubmitTooltip ),
				];
			}

			$attribs['classes'] = [ 'mw-htmlform-submit' ];
			$attribs['type'] = 'submit';
			$attribs['label'] = $this->getSubmitText();
			$attribs['value'] = $this->getSubmitText();
			$attribs['flags'] = $this->mSubmitFlags;
			$attribs['useInputTag'] = $isBadIE;

			$buttons .= new OOUI\ButtonInputWidget( $attribs );
		}

		if ( $this->mShowReset ) {
			$buttons .= new OOUI\ButtonInputWidget( [
				'type' => 'reset',
				'label' => $this->msg( 'htmlform-reset' )->text(),
				'useInputTag' => $isBadIE,
			] );
		}

		if ( $this->mShowCancel ) {
			$target = $this->mCancelTarget ?: Title::newMainPage();
			if ( $target instanceof Title ) {
				$target = $target->getLocalURL();
			}
			$buttons .= new OOUI\ButtonWidget( [
				'label' => $this->msg( 'cancel' )->text(),
				'href' => $target,
			] );
		}

		foreach ( $this->mButtons as $button ) {
			$attrs = [];

			if ( $button['attribs'] ) {
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			if ( $isBadIE ) {
				$label = $button['value'];
			} elseif ( isset( $button['label-message'] ) ) {
				$label = new OOUI\HtmlSnippet( $this->getMessage( $button['label-message'] )->parse() );
			} elseif ( isset( $button['label'] ) ) {
				$label = $button['label'];
			} elseif ( isset( $button['label-raw'] ) ) {
				$label = new OOUI\HtmlSnippet( $button['label-raw'] );
			} else {
				$label = $button['value'];
			}

			$attrs['classes'] = isset( $attrs['class'] ) ? (array)$attrs['class'] : [];

			$buttons .= new OOUI\ButtonInputWidget( [
				'type' => 'submit',
				'name' => $button['name'],
				'value' => $button['value'],
				'label' => $label,
				'flags' => $button['flags'],
				'framed' => $button['framed'],
				'useInputTag' => $isBadIE,
			] + $attrs );
		}

		if ( !$buttons ) {
			return '';
		}

		return Html::rawElement( 'div',
			[ 'class' => 'mw-htmlform-submit-buttons' ], "\n$buttons" ) . "\n";
	}

	protected function wrapFieldSetSection( $legend, $section, $attributes ) {
		// to get a user visible effect, wrap the fieldset into a framed panel layout
		$layout = new OOUI\PanelLayout( [
			'expanded' => false,
			'padded' => true,
			'framed' => true,
			'infusable' => false,
		] );

		$layout->appendContent(
			new OOUI\FieldsetLayout( [
				'label' => $legend,
				'infusable' => false,
				'items' => [
					new OOUI\Widget( [
						'content' => new OOUI\HtmlSnippet( $section )
					] ),
				],
			] + $attributes )
		);
		return $layout;
	}

	/**
	 * Put a form section together from the individual fields' HTML, merging it and wrapping.
	 * @param OOUI\FieldLayout[] $fieldsHtml
	 * @param string $sectionName
	 * @param bool $anyFieldHasLabel Unused
	 * @return string HTML
	 */
	protected function formatSection( array $fieldsHtml, $sectionName, $anyFieldHasLabel ) {
		if ( !$fieldsHtml ) {
			// Do not generate any wrappers for empty sections. Sections may be empty if they only have
			// subsections, but no fields. A legend will still be added in wrapFieldSetSection().
			return '';
		}

		$html = implode( '', $fieldsHtml );

		if ( $sectionName ) {
			$html = Html::rawElement(
				'div',
				[ 'id' => Sanitizer::escapeIdForAttribute( $sectionName ) ],
				$html
			);
		}
		return $html;
	}

	/**
	 * @param string|array|Status $elements
	 * @param string $elementsType
	 * @return string
	 */
	public function getErrorsOrWarnings( $elements, $elementsType ) {
		if ( $elements === '' ) {
			return '';
		}

		if ( !in_array( $elementsType, [ 'error', 'warning' ], true ) ) {
			throw new DomainException( $elementsType . ' is not a valid type.' );
		}
		$errors = [];
		if ( $elements instanceof Status ) {
			if ( !$elements->isGood() ) {
				$errors = $elements->getErrorsByType( $elementsType );
				foreach ( $errors as &$error ) {
					// Input:  [ 'message' => 'foo', 'errors' => [ 'a', 'b', 'c' ] ]
					// Output: [ 'foo', 'a', 'b', 'c' ]
					$error = array_merge( [ $error['message'] ], $error['params'] );
				}
			}
		} elseif ( $elementsType === 'error' ) {
			if ( is_array( $elements ) ) {
				$errors = $elements;
			} elseif ( is_string( $elements ) ) {
				$errors = [ $elements ];
			}
		}

		foreach ( $errors as &$error ) {
			$error = $this->getMessage( $error )->parse();
			$error = new OOUI\HtmlSnippet( $error );
		}

		// Used in getBody()
		if ( $elementsType === 'error' ) {
			$this->oouiErrors = $errors;
		} else {
			$this->oouiWarnings = $errors;
		}
		return '';
	}

	public function getHeaderText( $section = null ) {
		if ( is_null( $section ) ) {
			// We handle $this->mHeader elsewhere, in getBody()
			return '';
		} else {
			return parent::getHeaderText( $section );
		}
	}

	public function getBody() {
		$html = parent::getBody();
		if ( $this->mHeader || $this->oouiErrors || $this->oouiWarnings ) {
			$classes = [ 'mw-htmlform-ooui-header' ];
			if ( $this->oouiErrors ) {
				$classes[] = 'mw-htmlform-ooui-header-errors';
			}
			if ( $this->oouiWarnings ) {
				$classes[] = 'mw-htmlform-ooui-header-warnings';
			}
			// if there's no header, don't create an (empty) LabelWidget, simply use a placeholder
			if ( $this->mHeader ) {
				$element = new OOUI\LabelWidget( [ 'label' => new OOUI\HtmlSnippet( $this->mHeader ) ] );
			} else {
				$element = new OOUI\Widget( [] );
			}
			$html = new OOUI\FieldLayout(
				$element,
				[
					'align' => 'top',
					'errors' => $this->oouiErrors,
					'notices' => $this->oouiWarnings,
					'classes' => $classes,
				]
			) . $html;
		}
		return $html;
	}

	public function wrapForm( $html ) {
		if ( is_string( $this->mWrapperLegend ) ) {
			$content = new OOUI\FieldsetLayout( [
				'label' => $this->mWrapperLegend,
				'items' => [
					new OOUI\Widget( [
						'content' => new OOUI\HtmlSnippet( $html )
					] ),
				],
			] );
		} else {
			$content = new OOUI\HtmlSnippet( $html );
		}

		$form = new OOUI\FormLayout( $this->getFormAttributes() + [
			'classes' => [ 'mw-htmlform', 'mw-htmlform-ooui' ],
			'content' => $content,
		] );

		// Include a wrapper for style, if requested.
		$form = new OOUI\PanelLayout( [
			'classes' => [ 'mw-htmlform-ooui-wrapper' ],
			'expanded' => false,
			'padded' => $this->mWrapperLegend !== false,
			'framed' => $this->mWrapperLegend !== false,
			'content' => $form,
		] );

		return $form;
	}
}
