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

	public static function loadInputFromParameters( $fieldname, $descriptor, HTMLForm $parent = null ) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	function getButtons() {
		$buttons = '';

		if ( $this->mShowSubmit ) {
			$attribs = array( 'infusable' => true );

			if ( isset( $this->mSubmitID ) ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( isset( $this->mSubmitName ) ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( isset( $this->mSubmitTooltip ) ) {
				$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
			}

			$attribs['classes'] = array( 'mw-htmlform-submit' );
			$attribs['type'] = 'submit';
			$attribs['label'] = $this->getSubmitText();
			$attribs['value'] = $this->getSubmitText();
			$attribs['flags'] = $this->mSubmitFlags;

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
	 * @param string|array|Status $errors
	 * @return string
	 */
	function getErrors( $errors ) {
		// TODO Write me!
		return '';
	}

	function getBody() {
		$fieldset = parent::getBody();
		// FIXME This only works for forms with no subsections
		if ( $fieldset instanceof OOUI\FieldsetLayout ) {
			$fieldset->group->prependContent( new OOUI\HtmlSnippet( $this->mHeader ) );
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
	}
}
