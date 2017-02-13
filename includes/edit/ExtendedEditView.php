<?php
/**
 * User interface for page editing.
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

use MediaWiki\MediaWikiServices;

/**
 * The standard MediaWiki Web editor user interface
 * Controlled by the StandardEditor class
 *
 * In addition to the UI provided by the basic UI, this can display
 * a preview, a diff, a view source page for permission errors,
 * a view of the content that tried to be saved in case of conflict.
 * It also provides additional information such as templates used,
 * hidden categories and parser output metadata.
 *
 */
class ExtendedEditView extends BasicEditView {

	/** @var ParserOutput */
	protected $parserOutput;

	/** @var string */
	protected $previewTextAfterContent = '';

	public function setParserOutput( $parserOutput ) {
		$this->parserOutput = $parserOutput;
	}

	public function appendToPreviewTextAfterContent( $text ) {
		$this->previewTextAfterContent .= $text;
	}

	public function showConflictTextbox( $isUnicodeCompliant, $font ) {
		$this->showTextbox( $this->dataSource->textbox2, 'wpTextbox2', [ 'tabindex' => 6, 'readonly' ],
			$isUnicodeCompliant, $font );
	}

	public function showAfterEditForm() {
		parent::showAfterEditForm();

		$this->output->addHTML( $this->makeTemplatesOnThisPageList( $this->getTemplates() ) );

		$this->output->addHTML( Html::rawElement( 'div', [ 'class' => 'hiddencats' ],
			Linker::formatHiddenCategories( $this->title->getHiddenCategories() ) ) );

		$this->output->addHTML( Html::rawElement( 'div', [ 'class' => 'limitreport' ],
			EditUtilities::getPreviewLimitReport( $this->parserOutput ) ) );

		$this->output->addModules( 'mediawiki.action.edit.collapsibleFooter' );
	}

	/**
	 * Returns an array of html code of the following buttons:
	 * save, diff and preview
	 *
	 * @param int $tabindex Current tabindex
	 *
	 * @return array
	 */
	public function getEditButtons( &$tabindex ) {
		$buttons = parent::getEditButtons( $tabindex );

		++$tabindex; // use the same for preview and live preview
		$attribs = [
			'id' => 'wpPreview',
			'name' => 'wpPreview',
			'tabindex' => $tabindex,
		] + Linker::tooltipAndAccesskeyAttribs( 'preview' );
		$buttons['preview'] = Html::submitButton( $this->context->msg( 'showpreview' )->text(),
			$attribs );

		$attribs = [
			'id' => 'wpDiff',
			'name' => 'wpDiff',
			'tabindex' => ++$tabindex,
		] + Linker::tooltipAndAccesskeyAttribs( 'diff' );
		$buttons['diff'] = Html::submitButton( $this->context->msg( 'showdiff' )->text(),
			$attribs );

		Hooks::run( 'EditViewBeforeEditButtons', [ $this, &$buttons, &$tabindex ] );
		return $buttons;
	}

	/**
	 * Wrapper around TemplatesOnThisPageFormatter to make
	 * a "templates on this page" list.
	 *
	 * @param Title[] $templates
	 * @return string HTML
	 */
	public function makeTemplatesOnThisPageList( array $templates ) {
		$templateListFormatter = new TemplatesOnThisPageFormatter(
			$this->context, MediaWikiServices::getInstance()->getLinkRenderer()
		);

		// preview if preview, else section if section, else false
		$type = false;
		if ( $this->dataSource->preview ) {
			$type = 'preview';
		} elseif ( $this->dataSource->section !== '' ) {
			$type = 'section';
		}

		return Html::rawElement( 'div', [ 'class' => 'templatesUsed' ],
			$templateListFormatter->format( $templates, $type )
		);
	}

	/**
	 * @return array
	 */
	public function getTemplates() {
		if ( $this->dataSource->preview || $this->dataSource->section !== '' ) {
			$templates = [];
			if ( !isset( $this->parserOutput ) ) {
				return $templates;
			}
			foreach ( $this->parserOutput->getTemplates() as $ns => $template ) {
				foreach ( array_keys( $template ) as $dbk ) {
					$templates[] = Title::makeTitle( $ns, $dbk );
				}
			}
			return $templates;
		} else {
			return $this->title->getTemplateLinksFrom();
		}
	}

	public function getContinueEditingForPreview() {
		return '<span class="mw-continue-editing">' .
			'[[#' . self::EDITFORM_ID . '|' . $this->context->getLanguage()->getArrow() . ' ' .
			$this->context->msg( 'continue-editing' )->text() . ']]</span>';
	}

	public function outputPreviewText( $previewhead, $previewHTML ) {
		$pageViewLang = $this->title->getPageViewLanguage();
		$attribs = [ 'lang' => $pageViewLang->getHtmlCode(), 'dir' => $pageViewLang->getDir(),
			'class' => 'mw-content-' . $pageViewLang->getDir() ];
		$previewHTML = Html::rawElement( 'div', $attribs, $previewHTML );

		return $previewhead . $previewHTML . $this->previewTextAfterContent;
	}

	/**
	 * Display a read-only View Source page
	 * @param Content $content content object
	 * @param string $errorMessage additional wikitext error message to display
	 */
	public function displayViewSourcePage( $text, $errorMessage = '', $isUnicodeCompliant, $font ) {
		Hooks::run( 'EditView::showReadOnlyForm:initial', [ $this ] );

		$this->output->setRobotPolicy( 'noindex,nofollow' );
		$this->output->setPageTitle( $this->context->msg(
			'viewsource-title',
			$this->contextTitle->getPrefixedText()
		) );
		$this->output->addBacklinkSubtitle( $this->contextTitle );
		$this->output->addHTML( $this->editFormPageTop );
		$this->output->addHTML( $this->editFormTextTop );

		if ( $errorMessage !== '' ) {
			$this->output->addWikiText( $errorMessage );
			$this->output->addHTML( "<hr />\n" );
		}

		$this->output->addHTML( $this->editFormTextBeforeContent );
		$this->showTextbox( $text, 'wpTextbox1', [ 'readonly' ], $isUnicodeCompliant, $font );
		$this->output->addHTML( $this->editFormTextAfterContent );

		$this->output->addHTML( $this->makeTemplatesOnThisPageList( $this->getTemplates() ) );

		$this->output->addModules( 'mediawiki.action.edit.collapsibleFooter' );

		$this->output->addHTML( $this->editFormTextBottom );
		if ( $this->title->exists() ) {
			$this->output->returnToMain( null, $this->title );
		}
	}

	protected function getDisplayTitle() {
		# Use the title defined by DISPLAYTITLE magic word when present
		# NOTE: getDisplayTitle() returns HTML while getPrefixedText() returns plain text.
		#       setPageTitle() treats the input as wikitext, which should be safe in either case.
		return $this->parserOutput !== null ? $this->parserOutput->getDisplayTitle() : false;
	}

	public function outputAnonWarning() {
		if ( !$this->dataSource->preview ) {
			parent::outputAnonWarning();
		} else {
			$this->output->wrapWikiMsg(
				"<div id=\"mw-anon-preview-warning\" class=\"warningbox\">\n$1</div>",
				'anonpreviewwarning'
			);
		}
	}
}
