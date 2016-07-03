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

	function getButtons() {
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
				$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
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
		$config = [
			'items' => $fieldsHtml,
		];
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
			$errors = [];
		} elseif ( $err instanceof Status ) {
			if ( $err->isOK() ) {
				$errors = [];
			} else {
				$errors = $err->getErrorsByType( 'error' );
				foreach ( $errors as &$error ) {
					// Input:  array( 'message' => 'foo', 'errors' => array( 'a', 'b', 'c' ) )
					// Output: array( 'foo', 'a', 'b', 'c' )
					$error = array_merge( [ $error['message'] ], $error['params'] );
				}
			}
		} else {
			$errors = $err;
			if ( !is_array( $errors ) ) {
				$errors = [ $errors ];
			}
		}

		foreach ( $errors as &$error ) {
			$error = $this->getMessage( $error )->parse();
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
			$classes = [ 'mw-htmlform-ooui-header' ];
			if ( !$this->mHeader ) {
				$classes[] = 'mw-htmlform-ooui-header-empty';
			}
			if ( $this->oouiErrors ) {
				$classes[] = 'mw-htmlform-ooui-header-errors';
			}
			$fieldset->addItems( [
				new OOUI\FieldLayout(
					new OOUI\LabelWidget( [ 'label' => new OOUI\HtmlSnippet( $this->mHeader ) ] ),
					[
						'align' => 'top',
						'errors' => $this->oouiErrors,
						'classes' => $classes,
					]
				)
			], 0 );
		}
		return $fieldset;
	}

	function wrapForm( $html ) {
		$form = new OOUI\FormLayout( $this->getFormAttributes() + [
			'classes' => [ 'mw-htmlform-ooui' ],
			'content' => new OOUI\HtmlSnippet( $html ),
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
