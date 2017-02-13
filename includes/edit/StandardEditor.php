<?php
/**
 * Controller for page editing via the standard UI
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
use Wikimedia\ScopedCallback;

/**
 * The standard MediaWiki Web editor
 *
 * In addition to what the basic interface provides it allows to:
 *  - get a view source page when the user cannot edit
 *  - get a preview of the edited content
 *  - get a diff of the edited content with the original content
 *  - get a minimalist resolution interface for conflicts.
 */
class StandardEditor extends BasicEditor {

	protected $viewClass = 'ExtendedEditView';

	private $previewOutput = '';

	protected function handleReadOnly() {
		// Force preview
		$this->requestType = 'preview';
	}

	protected function parseFormType( $request, $requestType ) {
		switch ( $requestType ) {
			case 'save':
			$this->formType = 'save';
			break;

			case 'preview':
			$this->formType = 'preview';
			break;

			case 'diff':
			$this->formType = 'diff';
			break;

			default: # First time through
			if ( $this->previewOnOpen( $request ) ) {
				$this->formType = 'preview';
			} else {
				$this->formType = 'initial';
			}
		}
	}

	protected function beforeStartEditForm() {
		# need to parse the preview early so that we know which templates are used,
		# otherwise users with "show preview after edit box" will get a blank list
		# we parse this near the beginning so that setHeaders can do the title
		# setting work instead of leaving it in getPreviewText
		if ( $this->formType === 'preview' ) {
			$this->previewOutput = $this->getPreviewText();
		}
	}

	protected function beforeEditForm() {
		if ( $this->user->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( true );
		}
	}

	protected function afterEditForm( $isUnicodeCompliant ) {
		if ( $this->isConflict ) {
			try {
				$this->showConflict( $isUnicodeCompliant );
			} catch ( MWContentSerializationException $ex ) {
				// this can't really happen, but be nice if it does.
				$msg = $this->context->msg(
					'content-failed-to-parse',
					$this->data->contentModel,
					$this->data->contentFormat,
					$ex->getMessage()
				);
				$this->output->addWikiText( '<div class="error">' . $msg->text() . '</div>' );
			}
		}

		// Set a hidden field so JS knows what edit form mode we are in
		if ( $this->isConflict ) {
			$mode = 'conflict';
		} elseif ( $this->formType === 'preview' ) {
			$mode = 'preview';
		} elseif ( $this->formType === 'diff' ) {
			$mode = 'diff';
		} else {
			$mode = 'text';
		}
		$this->output->addHTML( Html::hidden( 'mode', $mode, [ 'id' => 'mw-edit-mode' ] ) );
	}

	protected function onBeforeShowMainTextboxOnConflict() {
		// save original text before it gets overwritten so we can display it
		$this->data->textbox2 = $this->data->textbox1;
	}

	protected function endEdit() {
		if ( !$this->user->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( false );
		}
	}

	/**
	 * Should we show a preview when the edit form is first shown?
	 *
	 * @return bool
	 */
	protected function previewOnOpen( $request ) {
		global $wgPreviewOnOpenNamespaces;
		if ( $request->getVal( 'preview' ) === 'yes' ) {
			// Explicit override from request
			return true;
		} elseif ( $request->getVal( 'preview' ) === 'no' ) {
			// Explicit override from request
			return false;
		} elseif ( $this->data->section === 'new' ) {
			// Nothing *to* preview for new sections
			return false;
		} elseif ( ( $request->getVal( 'preload' ) !== null || $this->title->exists() )
			&& $this->user->getOption( 'previewonfirst' )
		) {
			// Standard preference behavior
			return true;
		} elseif ( !$this->title->exists()
			&& isset( $wgPreviewOnOpenNamespaces[$this->title->getNamespace()] )
			&& $wgPreviewOnOpenNamespaces[$this->title->getNamespace()]
		) {
			// Categories are special
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Append preview output to page.
	 *
	 * @param string $text The HTML to be output for the preview.
	 */
	public function showPreview( $text ) {
		# This hook seems slightly odd here, but makes things more
		# consistent for extensions.
		Hooks::run( 'OutputPageBeforeHTML', [ &$this->output, &$text ] );
		$this->output->addHTML( $text );
		if ( $this->article instanceof CategoryPage ) {
			// append category list to preview
			$this->output->addHTML( $this->article->getCategoryList() );
			// add category help link
			$this->article->addHelpLink( 'Help:Categories' );
		}
	}

	/**
	 * Get a diff between the current contents of the edit box and the
	 * version of the page we're editing from.
	 *
	 * If this is a section edit, we'll replace the section as for final
	 * save and then make a comparison.
	 */
	public function showDiff() {
		global $wgContLang;

		$oldtitlemsg = 'currentrev';
		# if message does not exist, show diff against the preloaded default
		if ( $this->title->getNamespace() === NS_MEDIAWIKI && !$this->title->exists() ) {
			$oldtext = $this->title->getDefaultMessageText();
			if ( $oldtext !== false ) {
				$oldtitlemsg = 'defaultmessagetext';
				$oldContent = $this->toEditContent( $oldtext );
			} else {
				$oldContent = null;
			}
		} else {
			$oldContent = $this->facilitator->getCurrentContent();
		}

		$textboxContent = $this->toEditContent( $this->data->textbox1 );
		if ( $this->data->editRevId !== null ) {
			$newContent = $this->page->replaceSectionAtRev(
				$this->data->section, $textboxContent, $this->data->summary, $this->data->editRevId
			);
		} else {
			$newContent = $this->page->replaceSectionContent(
				$this->data->section, $textboxContent, $this->data->summary, $this->data->editTime
			);
		}

		if ( $newContent ) {
			Hooks::run( 'EditorGetDiffContent', [ $this, &$newContent ] );

			$popts = ParserOptions::newFromUserAndLang( $this->user, $wgContLang );
			$newContent = $newContent->preSaveTransform( $this->title, $this->user, $popts );
		}

		if ( ( $oldContent && !$oldContent->isEmpty() ) || ( $newContent && !$newContent->isEmpty() ) ) {
			$oldtitle = $this->context->msg( $oldtitlemsg )->parse();
			$newtitle = $this->context->msg( 'yourtext' )->parse();

			if ( !$oldContent ) {
				$oldContent = $newContent->getContentHandler()->makeEmptyContent();
			}

			if ( !$newContent ) {
				$newContent = $oldContent->getContentHandler()->makeEmptyContent();
			}

			$de = $oldContent->getContentHandler()->createDifferenceEngine( $this->context );
			$de->setContent( $oldContent, $newContent );

			$difftext = $de->getDiff( $oldtitle, $newtitle );
			$de->showDiffStyle();
		} else {
			$difftext = '';
		}

		$this->output->addHTML( '<div id="wikiDiff">' . $difftext . '</div>' );
	}

	/**
	 * Get the rendered text for previewing.
	 * @throws MWException
	 * @return string
	 */
	public function getPreviewText() {
		global $wgRawHtml, $wgAllowUserCss, $wgAllowUserJs;

		if ( $wgRawHtml && !$this->facilitator->isTokenOk() ) {
			// Could be an offsite preview attempt. This is very unsafe if
			// HTML is enabled, as it could be an attack.
			$parsedNote = '';
			if ( $this->data->textbox1 !== '' ) {
				// Do not put big scary notice, if previewing the empty
				// string, which happens when you initially edit
				// a category page, due to automatic preview-on-open.
				$parsedNote = $this->output->parse( "<div class='previewnote'>" .
					$this->context->msg( 'session_fail_preview_html' )->text() . "</div>",
					true, /* interface */true );
			}
			$this->incrementEditFailureStats( 'session_loss' );
			return $parsedNote;
		}

		$note = '';

		try {
			$content = $this->toEditContent( $this->data->textbox1 );

			$previewHTML = '';
			$parserOutput = null;
			if ( !Hooks::run( 'EditorAlternateEditPreview',
				[ $this, &$content, &$previewHTML, &$parserOutput ] )
			) {
				$this->view->setParserOutput( $parserOutput );
				return $previewHTML;
			}

			# provide a anchor link to the editform
			$continueEditing = $this->view->getContinueEditingForPreview();

			if ( $this->facilitator->triedSave() && !$this->facilitator->isTokenOk() ) {
				if ( $this->facilitator->isTokenOkExceptSuffix() ) {
					$note = $this->context->msg( 'token_suffix_mismatch' )->plain();
					$this->incrementEditFailureStats( 'bad_token' );
				} else {
					$note = $this->context->msg( 'session_fail_preview' )->plain();
					$this->incrementEditFailureStats( 'session_loss' );
				}
			} elseif ( $this->facilitator->isIncompleteForm() ) {
				$note = $this->context->msg( 'edit_form_incomplete' )->plain();
				if ( $this->facilitator->triedSave() ) {
					$this->incrementEditFailureStats( 'incomplete_form' );
				}
			} else {
				$note = $this->context->msg( 'previewnote' )->plain() . ' ' . $continueEditing;
			}

			# don't parse non-wikitext pages, show message about preview
			if ( $this->title->isCssJsSubpage() || $this->title->isCssOrJsPage() ) {
				if ( $this->title->isCssJsSubpage() ) {
					$level = 'user';
				} elseif ( $this->title->isCssOrJsPage() ) {
					$level = 'site';
				} else {
					$level = false;
				}

				if ( $content->getModel() === CONTENT_MODEL_CSS ) {
					$format = 'css';
					if ( $level === 'user' && !$wgAllowUserCss ) {
						$format = false;
					}
				} elseif ( $content->getModel() === CONTENT_MODEL_JAVASCRIPT ) {
					$format = 'js';
					if ( $level === 'user' && !$wgAllowUserJs ) {
						$format = false;
					}
				} else {
					$format = false;
				}

				# Used messages to make sure grep find them:
				# Messages: usercsspreview, userjspreview, sitecsspreview, sitejspreview
				if ( $level && $format ) {
					$note = "<div id='mw-{$level}{$format}preview'>" .
						$this->context->msg( "{$level}{$format}preview" )->text() .
						' ' . $continueEditing . "</div>";
				}
			}

			# If we're adding a comment, we need to show the
			# summary as the headline
			if ( $this->data->section === "new" && $this->data->summary !== "" ) {
				$content = $content->addSectionHeader( $this->data->summary );
			}

			$hook_args = [ $this, $this->view, &$content ];
			Hooks::run( 'EditorGetPreviewContent', $hook_args );

			$parserOptions = $this->getPreviewParserOptions();
			$parserResult = $this->doPreviewParse( $content, $parserOptions );
			$parserOutput = $parserResult['parserOutput'];
			$previewHTML = $parserResult['html'];
			$this->view->setParserOutput( $parserOutput );
			$this->output->addParserOutputMetadata( $parserOutput );

			if ( count( $parserOutput->getWarnings() ) ) {
				$note .= "\n\n" . implode( "\n\n", $parserOutput->getWarnings() );
			}

		} catch ( MWContentSerializationException $ex ) {
			$m = $this->context->msg(
				'content-failed-to-parse',
				$this->data->contentModel,
				$this->data->contentFormat,
				$ex->getMessage()
			);
			$note .= "\n\n" . $m->parse();
			$previewHTML = '';
		}

		if ( $this->isConflict ) {
			$conflict = '<h2 id="mw-previewconflict">'
				. $this->context->msg( 'previewconflict' )->escaped() . "</h2>\n";
		} else {
			$conflict = '<hr />';
		}

		$previewhead = "<div class='previewnote'>\n" .
			'<h2 id="mw-previewheader">' . $this->context->msg( 'preview' )->escaped() . "</h2>" .
			$this->output->parse( $note, true, /* interface */true ) . $conflict . "</div>\n";

		return $this->view->outputPreviewText( $previewhead, $previewHTML );
	}

	private function incrementEditFailureStats( $failureType ) {
		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$stats->increment( 'edit.failures.' . $failureType );
	}

	/**
	 * Get parser options for a preview
	 * @return ParserOptions
	 */
	protected function getPreviewParserOptions() {
		$parserOptions = $this->page->makeParserOptions( $this->context );
		$parserOptions->setIsPreview( true );
		$parserOptions->setIsSectionPreview( !is_null( $this->data->section ) &&
			$this->data->section !== '' );
		$parserOptions->enableLimitReport();
		return $parserOptions;
	}

	/**
	 * Show an edit conflict. textbox1 is already shown in showEditForm().
	 * If you want to use another entry point to this function, be careful.
	 */
	protected function showConflict( $isUnicodeCompliant ) {
		if ( Hooks::run( 'EditorBeforeConflictDiff', [ $this, $this->output ] ) ) {
			$this->incrementConflictStats();

			$this->output->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );

			$content1 = $this->toEditContent( $this->data->textbox1 );
			$content2 = $this->toEditContent( $this->data->textbox2 );

			$handler = ContentHandler::getForModelID( $this->data->contentModel );
			$de = $handler->createDifferenceEngine( $this->context );
			$de->setContent( $content2, $content1 );
			$de->showDiff(
				$this->context->msg( 'yourtext' )->parse(),
				$this->context->msg( 'storedversion' )->text()
			);

			$this->output->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );
			$this->view->showConflictTextbox( $isUnicodeCompliant,
				$this->user->getOption( 'editfont' ) );
		}
	}

	private function incrementConflictStats() {
		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$stats->increment( 'edit.failures.conflict' );
		// Only include 'standard' namespaces to avoid creating unknown numbers of statsd metrics
		if (
			$this->title->getNamespace() >= NS_MAIN &&
			$this->title->getNamespace() <= NS_CATEGORY_TALK
		) {
			$stats->increment( 'edit.failures.conflict.byNamespaceId.' . $this->title->getNamespace() );
		}
	}

	protected function displayPreviewArea( $isOnTop = false ) {
		$classes = [];
		if ( $isOnTop ) {
			$classes[] = 'ontop';
		}

		$attribs = [ 'id' => 'wikiPreview', 'class' => implode( ' ', $classes ) ];

		if ( $this->formType !== 'preview' ) {
			$attribs['style'] = 'display: none;';
		}

		$this->output->addHTML( Xml::openElement( 'div', $attribs ) );

		if ( $this->formType === 'preview' ) {
			$this->showPreview( $this->previewOutput );
		} else {
			// Empty content container for LivePreview
			$pageViewLang = $this->title->getPageViewLanguage();
			$attribs = [ 'lang' => $pageViewLang->getHtmlCode(), 'dir' => $pageViewLang->getDir(),
				'class' => 'mw-content-' . $pageViewLang->getDir() ];
			$this->output->addHTML( Html::rawElement( 'div', $attribs ) );
		}

		$this->output->addHTML( '</div>' );

		if ( $this->formType === 'diff' ) {
			try {
				$this->showDiff();
			} catch ( MWContentSerializationException $ex ) {
				$msg = $this->context->msg(
					'content-failed-to-parse',
					$this->data->contentModel,
					$this->data->contentFormat,
					$ex->getMessage()
				);
				$this->output->addWikiText( '<div class="error">' . $msg->text() . '</div>' );
			}
		}
	}

	protected function addExplainConflictHeader() {
		$this->output->wrapWikiMsg( "<div class='mw-explainconflict'>\n$1\n</div>", 'explainconflict' );
	}

	protected function onGetUndoContent( $undoMsg ) {
		// Messages: undo-success, undo-failure, undo-norev, undo-nochange
		$class = ( $undoMsg === 'success' ? '' : 'error ' ) . "mw-undo-{$undoMsg}";
			$this->view->appendToEditFormPageTop( $this->output->parse( "<div class=\"{$class}\">" .
			$this->context->msg( 'undo-' . $undoMsg )->plain() . '</div>', true, /* interface */true ) );
		if ( $undoMsg === 'success' ) {
			$this->formType = 'diff';
		}
	}

	protected function showCssJsWarnings() {
		return $this->formType !== 'preview';
	}

	protected function onGetEditPermissionErrors( &$permErrors ) {
		# Ignore some permissions errors when a user is just previewing/viewing diffs
		$remove = [];
		foreach ( $permErrors as $error ) {
			if ( ( $this->formType === 'preview' || $this->formType === 'diff' )
				&& (
					$error[0] === 'blockedtext' ||
					$error[0] === 'autoblockedtext' ||
					$error[0] === 'systemblockedtext'
				)
			) {
				$remove[] = $error;
			}
		}
		$permErrors = wfArrayDiff2( $permErrors, $remove );
	}

	/**
	 * Display a permissions error page, like OutputPage::showPermissionsErrorPage(),
	 * but with the following differences:
	 * - If redlink=1, the user will be redirected to the page
	 * - If there is content to display or the error occurs while either saving,
	 *   previewing or showing the difference, it will be a
	 *   "View source for ..." page displaying the source code after the error message.
	 *
	 * @since 1.19
	 * @param array $permErrors Array of permissions errors, as returned by
	 *    Title::getUserPermissionsErrors().
	 * @throws PermissionsError
	 */
	protected function displayPermissionsError( array $permErrors, $revision ) {
		if ( $this->user->getRequest()->getBool( 'redlink' ) ) {
			// The edit page was reached via a red link.
			// Redirect to the article page and let them click the edit tab if
			// they really want a permission error.
			$this->output->redirect( $this->title->getFullURL() );
			return;
		}

		$content = $this->facilitator->getContentObject( $revision );

		# Use the normal message if there's nothing to display
		if ( $this->requestType === 'initial' && ( !$content || $content->isEmpty() ) ) {
			parent::displayPermissionsError( $permErrors, $revision );
		}

		$this->view->displayViewSourcePage(
			$this->getViewSourceText( $content ),
			$this->output->formatPermissionsErrorMessage( $permErrors, 'edit' ),
			$this->checkUnicodeCompliantBrowser(),
			$this->user->getOption( 'editfont' )
		);
	}

	protected function getViewSourceText( Content $content ) {
		# If the user made changes, preserve them when showing the markup
		# (This happens when a user is blocked during edit, for instance)
		if ( $this->requestType !== 'initial' ) {
			$text = $this->data->textbox1;
			$this->output->addWikiMsg( 'viewyourtext' );
		} else {
			try {
				$text = $this->toEditText( $content );
			} catch ( MWException $e ) {
				# Serialize using the default format if the content model is not supported
				# (e.g. for an old revision with a different model)
				$text = $content->serialize();
			}
			$this->output->addWikiMsg( 'viewsourcetext' );
		}
		return $text;
	}

	protected function disallowDifferentContentModel( $revision ) {
		$this->view->displayViewSourcePage(
			$this->getViewSourceText( $this->facilitator->getContentObject( $revision ) ),
			$this->context->msg(
				'contentmodelediterror',
				$revision->getContentModel(),
				$this->data->contentModel
			)->plain(),
			$this->checkUnicodeCompliantBrowser(),
			$this->user->getOption( 'editfont' )
		);
	}

	/**
	 * Parse the page for a preview. Subclasses may override this class, in order
	 * to parse with different options, or to otherwise modify the preview HTML.
	 *
	 * @param Content $content The page content
	 * @return array with keys:
	 *   - parserOutput: The ParserOutput object
	 *   - html: The HTML to be displayed
	 */
	public function doPreviewParse( Content $content, ParserOptions $parserOptions ) {
		$pstContent = $content->preSaveTransform( $this->title, $this->user, $parserOptions );
		$scopedCallback = $parserOptions->setupFakeRevision(
			$this->title, $pstContent, $this->user );
		$parserOutput = $pstContent->getParserOutput( $this->title, null, $parserOptions );
		ScopedCallback::consume( $scopedCallback );
		$parserOutput->setEditSectionTokens( false ); // no section edit links
		return [
			'parserOutput' => $parserOutput,
			'html' => $parserOutput->getText() ];
	}

	/**
	 * Show "your edit contains spam" page with your diff and text
	 *
	 * @param string|array|bool $match Text (or array of texts) which triggered one or more filters
	 */
	public function spamPageWithContent( $match = false ) {
		$this->data->textbox2 = $this->data->textbox1;

		if ( is_array( $match ) ) {
			$match = $this->context->getLanguage()->listToText( $match );
		}
		$this->output->prepareErrorPage( $this->context->msg( 'spamprotectiontitle' ) );

		$this->output->addHTML( '<div id="spamprotected">' );
		$this->output->addWikiMsg( 'spamprotectiontext' );
		if ( $match ) {
			$this->output->addWikiMsg( 'spamprotectionmatch', wfEscapeWikiText( $match ) );
		}
		$this->output->addHTML( '</div>' );

		$this->output->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );
		$this->showDiff();

		$this->output->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );
		$this->view->showSpamTextbox( $this->checkUnicodeCompliantBrowser(),
			$this->user->getOption( 'editfont' ) );

		$this->output->addReturnTo( $this->view->getContextTitle(), [ 'action' => 'edit' ] );
	}
}
