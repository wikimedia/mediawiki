<?php
/**
 * Basic user interface for page editing.
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
 * A basic user interface for editing
 * Controlled by the BasicEditor class
 *
 * It provides a text box where the text can be edited, various notices, checkboxes
  * and an edit button.
 */
class BasicEditView extends EditView {

	/**
	 * HTML id and name for the beginning of the edit form.
	 */
	const EDITFORM_ID = 'editform';

	/**
	 * Stuff that hooks can append to
	 */

	/** @var string */
	protected $editFormPageTop = '';

	/** @var string */
	protected $editFormTextTop = '';

	/** @var string */
	protected $editFormTextBeforeContent = '';

	/** @var string */
	protected $editFormTextAfterContent = '';

	/** @var string */
	protected $editFormTextAfterWarn = '';

	/** @var string */
	protected $editFormTextAfterTools = '';

	/** @var string */
	protected $editFormTextBottom = '';

	/**
	 * Set by controller (from user values)
	 */

	/** @var string */
	private $showMinorCheck = false;

	/** @var string */
	private $showWatchCheck = false;

	/**
	 * For subclasses to prevent showing some UI elements
	 */

	/** @var bool */
	protected $showSummaryField = true;

	/** @var bool */
	protected $suppressIntro = false;

	public function showMinorCheck( $val ) {
		$this->showMinorCheck = $val;
	}

	public function showWatchCheck( $val ) {
		$this->showWatchCheck = $val;
	}

	/**
	 * append methods for hooks
	 */
	public function appendToEditFormPageTop( $text ) {
		$this->editFormPageTop .= $text;
	}

	public function appendToEditFormTextTop( $text ) {
		$this->editFormTextTop .= $text;
	}

	public function appendToEditFormTextBeforeContent( $text ) {
		$this->editFormTextBeforeContent .= $text;
	}

	public function appendToEditFormTextAfterContent( $text ) {
		$this->editFormTextAfterContent .= $text;
	}

	public function appendToEditFormTextAfterWarn( $text ) {
		$this->editFormTextAfterWarn .= $text;
	}

	public function appendToEditFormTextAfterTools( $text ) {
		$this->editFormTextAfterTools .= $text;
	}

	public function appendToEditFormTextBottom( $text ) {
		$this->editFormTextBottom .= $text;
	}

	/**
	 * Show all applicable editing introductions
	 */
	public function showIntro( $isLoggedIn, $customEditIntro ) {
		if ( $this->suppressIntro ) {
			return;
		}
		$namespace = $this->title->getNamespace();

		if ( $namespace == NS_MEDIAWIKI ) {
			# Show a warning if editing an interface message
			$this->output->wrapWikiMsg( "<div class='mw-editinginterface'>\n$1\n</div>", 'editinginterface' );
			# If this is a default message (but not css or js),
			# show a hint that it is translatable on translatewiki.net
			if ( !$this->title->hasContentModel( CONTENT_MODEL_CSS )
				&& !$this->title->hasContentModel( CONTENT_MODEL_JAVASCRIPT )
			) {
				$defaultMessageText = $this->title->getDefaultMessageText();
				if ( $defaultMessageText !== false ) {
					$this->output->wrapWikiMsg( "<div class='mw-translateinterface'>\n$1\n</div>",
						'translateinterface' );
				}
			}
		} elseif ( $namespace == NS_FILE ) {
			# Show a hint to shared repo
			$file = wfFindFile( $this->title );
			if ( $file && !$file->isLocal() ) {
				$descUrl = $file->getDescriptionUrl();
				# there must be a description url to show a hint to shared repo
				if ( $descUrl ) {
					if ( !$this->title->exists() ) {
						$this->output->wrapWikiMsg( "<div class=\"mw-sharedupload-desc-create\">\n$1\n</div>", [
									'sharedupload-desc-create', $file->getRepo()->getDisplayName(), $descUrl
						] );
					} else {
						$this->output->wrapWikiMsg( "<div class=\"mw-sharedupload-desc-edit\">\n$1\n</div>", [
									'sharedupload-desc-edit', $file->getRepo()->getDisplayName(), $descUrl
						] );
					}
				}
			}
		}

		# Show a warning message when someone creates/edits a user (talk) page but the user does not exist
		# Show log extract when the user is currently blocked
		if ( $namespace == NS_USER || $namespace == NS_USER_TALK ) {
			$username = explode( '/', $this->title->getText(), 2 )[0];
			$user = User::newFromName( $username, false /* allow IP users*/ );
			$ip = User::isIP( $username );
			$block = Block::newFromTarget( $user, $user );
			if ( !( $user && $user->isLoggedIn() ) && !$ip ) { # User does not exist
				$this->output->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n$1\n</div>",
					[ 'userpage-userdoesnotexist', wfEscapeWikiText( $username ) ] );
			} elseif ( !is_null( $block ) && $block->getType() != Block::TYPE_AUTO ) {
				# Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$this->output,
					'block',
					MWNamespace::getCanonicalName( NS_USER ) . ':' . $block->getTarget(),
					'',
					[
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => [
							'blocked-notice-logextract',
							$user->getName() # Support GENDER in notice
						]
					]
				);
			}
		}
		# Try to add a custom edit intro, or use the standard one if this is not possible.
		if ( !$this->showCustomIntro( $customEditIntro ) && !$this->title->exists() ) {
			$helpLink = wfExpandUrl( Skin::makeInternalOrExternalUrl(
				$this->context->msg( 'helppage' )->inContentLanguage()->text()
			) );
			if ( $isLoggedIn ) {
				$this->output->wrapWikiMsg(
					// Suppress the external link icon, consider the help url an internal one
					"<div class=\"mw-newarticletext plainlinks\">\n$1\n</div>",
					[
						'newarticletext',
						$helpLink
					]
				);
			} else {
				$this->output->wrapWikiMsg(
					// Suppress the external link icon, consider the help url an internal one
					"<div class=\"mw-newarticletextanon plainlinks\">\n$1\n</div>",
					[
						'newarticletextanon',
						$helpLink
					]
				);
			}
		}
		# Give a notice if the user is editing a deleted/moved page...
		if ( !$this->title->exists() ) {
			LogEventsList::showLogExtract( $this->output, [ 'delete', 'move' ], $this->title,
				'',
				[
					'lim' => 10,
					'conds' => [ "log_action != 'revision'" ],
					'showIfEmpty' => false,
					'msgKey' => [ 'recreate-moveddeleted-warn' ]
				]
			);
		}
	}

	/**
	 * Attempt to show a custom editing introduction, if supplied
	 *
	 * @return bool
	 */
	protected function showCustomIntro( $customEditIntro ) {
		if ( $customEditIntro ) {
			$title = Title::newFromText( $customEditIntro );
			if ( $title instanceof Title && $title->exists() && $title->userCan( 'read' ) ) {
				// Added using template syntax, to take <noinclude>'s into account.
				$this->output->addWikiTextTitleTidy(
					'<div class="mw-editintro">{{:' . $title->getFullText() . '}}</div>',
					$this->title
				);
				return true;
			}
		}
		return false;
	}

	/**
	 * Send the edit form and related headers to $this->output
	 */
	public function startEditForm( $options ) {
		Hooks::run( 'EditView::showEditForm:initial', [ $this ] );

		$this->setHeaders( $options );

		$this->addTalkPageText();
		$this->addEditNotices();
	}

	public function showEditFormTop() {
		$this->output->addHTML( $this->editFormPageTop );
	}
	public function showBeforeEditForm( $isUnicodeCompliant) {
		$this->output->addHTML( $this->editFormTextTop );

		// @todo add EditForm plugin interface and use it here!
		//       search for textarea1 and textares2, and allow EditForm to override all uses.
		$this->output->addHTML( Html::openElement(
			'form',
			[
				'id' => self::EDITFORM_ID,
				'name' => self::EDITFORM_ID,
				'method' => 'post',
				'action' => $this->getActionURL( $this->contextTitle ),
				'enctype' => 'multipart/form-data'
			]
		) );

		// Add an empty field to trip up spambots
		$this->output->addHTML(
			Xml::openElement( 'div', [ 'id' => 'antispam-container', 'style' => 'display: none;' ] )
			. Html::rawElement(
				'label',
				[ 'for' => 'wpAntispam' ],
				$this->context->msg( 'simpleantispam-label' )->parse()
			)
			. Xml::element(
				'input',
				[
					'type' => 'text',
					'name' => 'wpAntispam',
					'id' => 'wpAntispam',
					'value' => ''
				]
			)
			. Xml::closeElement( 'div' )
		);

		Hooks::run( 'EditView::showEditForm:fields', [ $this ] );

		// Put these up at the top to ensure they aren't lost on early form submission
		$this->showFormBeforeText( $isUnicodeCompliant );

		if ( $this->dataSource->section == 'new' ) {
			$this->showSummaryInput( true, $this->dataSource->summary );
			$this->output->addHTML( $this->getSummaryPreview( true, $this->dataSource->summary ) );
		}

		$this->output->addHTML( $this->editFormTextBeforeContent );
	}

	public function showAfterEditForm() {
		$this->output->addHTML( $this->editFormTextAfterContent );

		$this->showStandardInputs();

		$this->showTosSummary();

		$this->showEditTools();

		$this->output->addHTML( $this->editFormTextAfterTools . "\n" );
	}

	public function showBeforeEditEnd() {
		// Marker for detecting truncated form data.  This must be the last
		// parameter sent in order to be of use, so do not move me.
		$this->output->addHTML( Html::hidden( 'wpUltimateParam', true ) );
		$this->output->addHTML( $this->editFormTextBottom . "\n" );
		$this->output->addHTML( Html::closeElement( 'form' ) . "\n" );
	}

	public function setHeaders( $options ) {
		global $wgAjaxEditStash, $wgCookieSetOnAutoblock;

		$this->output->addModules( 'mediawiki.action.edit' );
		if ( $wgCookieSetOnAutoblock === true ) {
			$this->output->addModules( 'mediawiki.user.blockcookie' );
		}
		$this->output->addModuleStyles( 'mediawiki.action.edit.styles' );

		if ( $options['showtoolbar'] ) {
			// The addition of default buttons is handled by getEditToolbar() which
			// has its own dependency on this module. The call here ensures the module
			// is loaded in time (it has position "top") for other modules to register
			// buttons (e.g. extensions, gadgets, user scripts).
			$this->output->addModules( 'mediawiki.toolbar' );
		}

		if ( $options['uselivepreview'] ) {
			$this->output->addModules( 'mediawiki.action.edit.preview' );
		}

		if ( $options['useeditwarning'] ) {
			$this->output->addModules( 'mediawiki.action.edit.editWarning' );
		}

		# Enabled article-related sidebar, toplinks, etc.
		$this->output->setArticleRelated( true );

		if ( $this->isConflict() ) {
			$msg = 'editconflict';
		} elseif ( $this->contextTitle->exists() && $this->dataSource->section != '' ) {
			$msg = $this->dataSource->section == 'new' ? 'editingcomment' : 'editingsection';
		} else {
			$msg = $this->contextTitle->exists()
				|| ( $this->contextTitle->getNamespace() == NS_MEDIAWIKI
					&& $this->contextTitle->getDefaultMessageText() !== false
				)
				? 'editing'
				: 'creating';
		}

		$displayTitle = $this->getDisplayTitle();
		if ( $displayTitle === false ) {
			$displayTitle = $this->contextTitle->getPrefixedText();
		}
		$this->output->setPageTitle( $this->context->msg( $msg, $displayTitle ) );
		# Transmit the name of the message to JavaScript for live preview
		# Keep Resources.php/mediawiki.action.edit.preview in sync with the possible keys
		$this->output->addJsConfigVars( [
			'wgEditMessage' => $msg,
			'wgAjaxEditStash' => $wgAjaxEditStash,
		] );
	}

	protected function getDisplayTitle() {
		return false;
	}

	/**
	 * @since 1.29
	 */
	protected function addTalkPageText() {
		if ( $this->title->isTalkPage() ) {
			$this->output->addWikiMsg( 'talkpagetext' );
		}
	}

	/**
	 * @since 1.29
	 */
	protected function addEditNotices() {
		$editNotices = $this->title->getEditNotices( $this->dataSource->oldid );
		if ( count( $editNotices ) ) {
			$this->output->addHTML( implode( "\n", $editNotices ) );
		} else {
			$msg = $this->context->msg( 'editnotice-notext' );
			if ( !$msg->isDisabled() ) {
				$this->output->addHTML(
					'<div class="mw-editnotice-notext">'
					. $msg->parseAsBlock()
					. '</div>'
				);
			}
		}
	}

	/**
	 * @since 1.29
	 */
	public function addPageProtectionWarningHeaders() {
		if ( $this->title->isProtected( 'edit' ) &&
			MWNamespace::getRestrictionLevels( $this->title->getNamespace() ) !== [ '' ]
		) {
			# Is the title semi-protected?
			if ( $this->title->isSemiProtected() ) {
				$noticeMsg = 'semiprotectedpagewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagewarning';
			}
			LogEventsList::showLogExtract( $this->output, 'protect', $this->title, '',
				[ 'lim' => 1, 'msgKey' => [ $noticeMsg ] ] );
		}
		if ( $this->title->isCascadeProtected() ) {
			# Is this page under cascading protection from some source pages?
			/** @var Title[] $cascadeSources */
			list( $cascadeSources, /* $restrictions */ ) = $this->title->getCascadeProtectionSources();
			$notice = "<div class='mw-cascadeprotectedwarning'>\n$1\n";
			$cascadeSourcesCount = count( $cascadeSources );
			if ( $cascadeSourcesCount > 0 ) {
				# Explain, and list the titles responsible
				foreach ( $cascadeSources as $page ) {
					$notice .= '* [[:' . $page->getPrefixedText() . "]]\n";
				}
			}
			$notice .= '</div>';
			$this->output->wrapWikiMsg( $notice, [ 'cascadeprotectedwarning', $cascadeSourcesCount ] );
		}
		if ( !$this->title->exists() && $this->title->getRestrictions( 'create' ) ) {
			LogEventsList::showLogExtract( $this->output, 'protect', $this->title, '',
				[ 'lim' => 1,
					'showIfEmpty' => false,
					'msgKey' => [ 'titleprotectedwarning' ],
					'wrap' => "<div class=\"mw-titleprotectedwarning\">\n$1</div>" ] );
		}
	}

	/**
	 * @since 1.29
	 */
	public function addLongPageWarningHeader( $contentLength, $tooBig ) {
		global $wgMaxArticleSize;

		if ( $tooBig || $contentLength > $wgMaxArticleSize * 1024 ) {
			$this->output->wrapWikiMsg( "<div class='error' id='mw-edit-longpageerror'>\n$1\n</div>",
				[
					'longpageerror',
					$this->context->getLanguage()->formatNum( round( $contentLength / 1024, 3 ) ),
					$this->context->getLanguage()->formatNum( $wgMaxArticleSize )
				]
			);
		} else {
			if ( !$this->context->msg( 'longpage-hint' )->isDisabled() ) {
				$this->output->wrapWikiMsg( "<div id='mw-edit-longpage-hint'>\n$1\n</div>",
					[
						'longpage-hint',
						$this->context->getLanguage()->formatSize( $contentLength ),
						$contentLength
					]
				);
			}
		}
	}

	/**
	 * Show the header copyright warning.
	 */
	public function showHeaderCopyrightWarning() {
		$msg = 'editpage-head-copy-warn';
		if ( !$this->context->msg( $msg )->isDisabled() ) {
			$this->output->wrapWikiMsg( "<div class='editpage-head-copywarn'>\n$1\n</div>",
				'editpage-head-copy-warn' );
		}
	}

	protected function showFormBeforeText( $isUnicodeCompliant ) {
		$section = htmlspecialchars( $this->dataSource->section );
		$this->output->addHTML( <<<HTML
<input type='hidden' value="{$section}" name="wpSection"/>
<input type='hidden' value="{$this->dataSource->startTime}" name="wpStarttime" />
<input type='hidden' value="{$this->dataSource->editTime}" name="wpEdittime" />
<input type='hidden' value="{$this->dataSource->editRevId}" name="editRevId" />
<input type='hidden' value="{$this->dataSource->scrollTop}" name="wpScrolltop" id="wpScrolltop" />

HTML
		);
		if ( !$isUnicodeCompliant ) {
			$this->output->addHTML( Html::hidden( 'safemode', '1' ) );
		}
	}

	/**
	 * Subpage overridable method for printing the form for page content editing
	 * By default this simply outputs wpTextbox1
	 * Subclasses can override this to provide a custom UI for editing;
	 * be it a form, or simply wpTextbox1 with a modified content that will be
	 * reverse modified when extracted from the post data.
	 * Note that this is basically the inverse for importContentFormData
	 */
	public function showContentForm( $displayNone, $isUnicodeCompliant, $isOldRev, $font ) {
		$this->showMainTextbox( $displayNone, $isUnicodeCompliant, $isOldRev, $font );
	}

	/**
	 * Method to output wpTextbox1
	 * The $textoverride method can be used by subclasses overriding showContentForm
	 * to pass back to this method.
	 *
	 * @param array $customAttribs Array of html attributes to use in the textarea
	 */
	public function showMainTextbox( $displayNone, $isUnicodeCompliant, $isOldRev, $font, $customAttribs = null
	) {
		if ( $displayNone ) {
			$attribs = [ 'style' => 'display:none;' ];
		} else {
			$classes = []; // Textarea CSS
			if ( $this->title->isProtected( 'edit' ) &&
				MWNamespace::getRestrictionLevels( $this->title->getNamespace() ) !== [ '' ]
			) {
				# Is the title semi-protected?
				if ( $this->title->isSemiProtected() ) {
					$classes[] = 'mw-textarea-sprotected';
				} else {
					# Then it must be protected based on static groups (regular)
					$classes[] = 'mw-textarea-protected';
				}
				# Is the title cascade-protected?
				if ( $this->title->isCascadeProtected() ) {
					$classes[] = 'mw-textarea-cprotected';
				}
			}
			# Is an old revision being edited?
			if ( $isOldRev ) {
				$classes[] = 'mw-textarea-oldrev';
			}

			$attribs = [ 'tabindex' => 1 ];

			if ( is_array( $customAttribs ) ) {
				$attribs += $customAttribs;
			}

			if ( count( $classes ) ) {
				if ( isset( $attribs['class'] ) ) {
					$classes[] = $attribs['class'];
				}
				$attribs['class'] = implode( ' ', $classes );
			}
		}

		$this->showTextbox(
			$this->dataSource->textbox1,
			'wpTextbox1',
			$attribs,
			$isUnicodeCompliant,
			$font
		);
	}

	protected function showStandardInputs( &$tabindex = 2 ) {
		$this->output->addHTML( "<div class='editOptions'>\n" );

		if ( $this->dataSource->section != 'new' ) {
			$this->showSummaryInput( false, $this->dataSource->summary );
			$this->output->addHTML( $this->getSummaryPreview( false, $this->dataSource->summary ) );
		}

		$checkboxes = $this->getCheckboxes( $tabindex,
			[ 'minor' => $this->dataSource->minorEdit, 'watch' => $this->dataSource->watchThis ] );
		$this->output->addHTML( "<div class='editCheckboxes'>" . implode( $checkboxes, "\n" ) . "</div>\n" );

		// Show copyright warning.
		$this->output->addWikiText( $this->getCopywarn() );
		$this->output->addHTML( $this->editFormTextAfterWarn );

		$this->output->addHTML( "<div class='editButtons'>\n" );
		$this->output->addHTML( implode( $this->getEditButtons( $tabindex ), "\n" ) . "\n" );

		$cancel = $this->getCancelLink();
		if ( $cancel !== '' ) {
			$cancel .= Html::element( 'span',
				[ 'class' => 'mw-editButtons-pipe-separator' ],
				$this->context->msg( 'pipe-separator' )->text() );
		}

		$message = $this->context->msg( 'edithelppage' )->inContentLanguage()->text();
		$edithelpurl = Skin::makeInternalOrExternalUrl( $message );
		$attrs = [
			'target' => 'helpwindow',
			'href' => $edithelpurl,
		];
		$edithelp = Html::linkButton( $this->context->msg( 'edithelp' )->text(),
			$attrs, [ 'mw-ui-quiet' ] ) .
			$this->context->msg( 'word-separator' )->escaped() .
			$this->context->msg( 'newwindow' )->parse();

		$this->output->addHTML( "	<span class='cancelLink'>{$cancel}</span>\n" );
		$this->output->addHTML( "	<span class='editHelp'>{$edithelp}</span>\n" );
		$this->output->addHTML( "</div><!-- editButtons -->\n" );

		Hooks::run( 'EditView::showStandardInputs:options', [ $this, &$tabindex ] );

		$this->output->addHTML( "</div><!-- editOptions -->\n" );
	}

	/**
	 * Returns an array of html code of the following checkboxes:
	 * minor and watch
	 *
	 * @param int $tabindex Current tabindex
	 * @param array $checked Array of checkbox => bool, where bool indicates the checked
	 *                 status of the checkbox
	 *
	 * @return array
	 */
	public function getCheckboxes( &$tabindex, $checked ) {
		global $wgUseMediaWikiUIEverywhere;

		$checkboxes = [];

		// don't show the minor edit checkbox if it's a new page or section
		if ( $this->title->exists() && $this->dataSource->section !== 'new' ) {
			$checkboxes['minor'] = '';
			$minorLabel = $this->context->msg( 'minoredit' )->parse();
			if ( $this->showMinorCheck ) {
				$attribs = [
					'tabindex' => ++$tabindex,
					'accesskey' => $this->context->msg( 'accesskey-minoredit' )->text(),
					'id' => 'wpMinoredit',
				];
				$minorEditHtml =
					Xml::check( 'wpMinoredit', $checked['minor'], $attribs ) .
					"&#160;<label for='wpMinoredit' id='mw-editpage-minoredit'" .
					Xml::expandAttributes( [ 'title' => Linker::titleAttrib( 'minoredit', 'withaccess' ) ] ) .
					">{$minorLabel}</label>";

				if ( $wgUseMediaWikiUIEverywhere ) {
					$checkboxes['minor'] = Html::openElement( 'div', [ 'class' => 'mw-ui-checkbox' ] ) .
						$minorEditHtml .
					Html::closeElement( 'div' );
				} else {
					$checkboxes['minor'] = $minorEditHtml;
				}
			}
		}

		$watchLabel = $this->context->msg( 'watchthis' )->parse();
		$checkboxes['watch'] = '';
		if ( $this->showWatchCheck ) {
			$attribs = [
				'tabindex' => ++$tabindex,
				'accesskey' => $this->context->msg( 'accesskey-watch' )->text(),
				'id' => 'wpWatchthis',
			];
			$watchThisHtml =
				Xml::check( 'wpWatchthis', $checked['watch'], $attribs ) .
				"&#160;<label for='wpWatchthis' id='mw-editpage-watch'" .
				Xml::expandAttributes( [ 'title' => Linker::titleAttrib( 'watch', 'withaccess' ) ] ) .
				">{$watchLabel}</label>";
			if ( $wgUseMediaWikiUIEverywhere ) {
				$checkboxes['watch'] = Html::openElement( 'div', [ 'class' => 'mw-ui-checkbox' ] ) .
					$watchThisHtml .
					Html::closeElement( 'div' );
			} else {
				$checkboxes['watch'] = $watchThisHtml;
			}
		}

		Hooks::run( 'EditViewBeforeEditChecks', [ $this, &$checkboxes, &$tabindex ] );
		return $checkboxes;
	}

	/**
	 * Get the copyright warning
	 *
	 * Renamed to getCopyrightWarning(), old name kept around for backwards compatibility
	 * @return string
	 */
	protected function getCopywarn() {
		return EditUtilities::getCopyrightWarning( $this->title );
	}

	/**
	 * @return string
	 */
	protected function getCancelLink() {
		$cancelParams = [];
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		if ( !$this->isConflict() && $this->dataSource->oldid > 0 ) {
			$cancelParams['oldid'] = $this->dataSource->oldid;
		} elseif ( $this->contextTitle->isRedirect() ) {
			$cancelParams['redirect'] = 'no';
		}
		$attrs = [ 'id' => 'mw-editform-cancel' ];

		return $linkRenderer->makeKnownLink(
			$this->contextTitle,
			new HtmlArmor( $this->context->msg( 'cancel' )->parse() ),
			Html::buttonAttributes( $attrs, [ 'mw-ui-quiet' ] ),
			$cancelParams
		);
	}

	/**
	 * Give a chance for site and per-namespace customizations of
	 * terms of service summary link that might exist separately
	 * from the copyright notice.
	 *
	 * This will display between the save button and the edit tools,
	 * so should remain short!
	 */
	protected function showTosSummary() {
		$msg = 'editpage-tos-summary';
		Hooks::run( 'EditPageTosSummary', [ $this->title, &$msg ] );
		if ( !$this->context->msg( $msg )->isDisabled() ) {
			$this->output->addHTML( '<div class="mw-tos-summary">' );
			$this->output->addWikiMsg( $msg );
			$this->output->addHTML( '</div>' );
		}
	}

	protected function showEditTools() {
		$this->output->addHTML( '<div class="mw-editTools">' .
			$this->context->msg( 'edittools' )->inContentLanguage()->parse() .
			'</div>' );
	}

	/**
	 * Creates a basic error page which informs the user that
	 * they have attempted to edit a nonexistent section.
	 */
	public function noSuchSectionPage() {
		$this->output->prepareErrorPage( $this->context->msg( 'nosuchsectiontitle' ) );

		$res = $this->context->msg( 'nosuchsectiontext', $this->dataSource->section )->parseAsBlock();

		Hooks::run( 'EditViewNoSuchSection', [ $this, &$res ] );
		$this->output->addHTML( $res );

		$this->output->returnToMain( false, $this->title );
	}

	public function outputAnonWarning() {
		$this->output->wrapWikiMsg(
			"<div id='mw-anon-edit-warning' class='warningbox'>\n$1\n</div>",
			[ 'anoneditwarning',
				// Log-in link
				SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
					'returnto' => $this->title->getPrefixedDBkey()
				] ),
				// Sign-up link
				SpecialPage::getTitleFor( 'CreateAccount' )->getFullURL( [
					'returnto' => $this->title->getPrefixedDBkey()
				] )
			]
		);
	}

}
