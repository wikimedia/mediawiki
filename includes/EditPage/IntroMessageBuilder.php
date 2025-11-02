<?php

namespace MediaWiki\EditPage;

use LogicException;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Config\Config;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Html\Html;
use MediaWiki\Language\RawMessage;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Skin\Skin;
use MediaWiki\Skin\SkinFactory;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserRigorOptions;
use MediaWiki\Utils\UrlUtils;
use MessageLocalizer;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Provides the intro messages (edit notices and others) to be displayed before an edit form.
 *
 * Used by EditPage, and may be used by extensions providing alternative editors.
 *
 * @since 1.41
 */
class IntroMessageBuilder {

	use ParametersHelper;

	// Parameters for getIntroMessages()
	public const MORE_FRAMES = 1;
	public const LESS_FRAMES = 2;

	private Config $config;
	private LinkRenderer $linkRenderer;
	private PermissionManager $permManager;
	private UserNameUtils $userNameUtils;
	private TempUserCreator $tempUserCreator;
	private UserFactory $userFactory;
	private RestrictionStore $restrictionStore;
	private DatabaseBlockStore $blockStore;
	private ReadOnlyMode $readOnlyMode;
	private SpecialPageFactory $specialPageFactory;
	private RepoGroup $repoGroup;
	private NamespaceInfo $namespaceInfo;
	private SkinFactory $skinFactory;
	private IConnectionProvider $dbProvider;
	private UrlUtils $urlUtils;

	public function __construct(
		Config $config,
		LinkRenderer $linkRenderer,
		PermissionManager $permManager,
		UserNameUtils $userNameUtils,
		TempUserCreator $tempUserCreator,
		UserFactory $userFactory,
		RestrictionStore $restrictionStore,
		DatabaseBlockStore $blockStore,
		ReadOnlyMode $readOnlyMode,
		SpecialPageFactory $specialPageFactory,
		RepoGroup $repoGroup,
		NamespaceInfo $namespaceInfo,
		SkinFactory $skinFactory,
		IConnectionProvider $dbProvider,
		UrlUtils $urlUtils
	) {
		$this->config = $config;
		$this->linkRenderer = $linkRenderer;
		$this->permManager = $permManager;
		$this->userNameUtils = $userNameUtils;
		$this->tempUserCreator = $tempUserCreator;
		$this->userFactory = $userFactory;
		$this->restrictionStore = $restrictionStore;
		$this->blockStore = $blockStore;
		$this->readOnlyMode = $readOnlyMode;
		$this->specialPageFactory = $specialPageFactory;
		$this->repoGroup = $repoGroup;
		$this->namespaceInfo = $namespaceInfo;
		$this->skinFactory = $skinFactory;
		$this->dbProvider = $dbProvider;
		$this->urlUtils = $urlUtils;
	}

	/**
	 * Wrapper for LogEventsList::showLogExtract() that returns the string with the output.
	 *
	 * LogEventsList::showLogExtract() has some side effects affecting the global state (main request
	 * context), which should not be relied upon.
	 *
	 * @param string|array $types See LogEventsList::showLogExtract()
	 * @param string|PageReference $page See LogEventsList::showLogExtract()
	 * @param string $user See LogEventsList::showLogExtract()
	 * @param array $param See LogEventsList::showLogExtract()
	 * @return string
	 */
	private function getLogExtract( $types = [], $page = '', $user = '', $param = [] ): string {
		$outString = '';
		LogEventsList::showLogExtract( $outString, $types, $page, $user, $param );
		return $outString;
	}

	/**
	 * Return intro messages to be shown before an edit form.
	 *
	 * The message identifiers used as array keys are stable. Callers of this method may recognize
	 * specific messages and omit them when displaying, if they're not applicable to some interface or
	 * if they provide the same information in an alternative way.
	 *
	 * Callers should load the 'mediawiki.interface.helpers.styles' ResourceLoader module, as some of
	 * the possible messages rely on those styles.
	 *
	 * @param int $frames Some intro messages come with optional wrapper frames.
	 *   Pass IntroMessageBuilder::MORE_FRAMES to include the frames whenever possible,
	 *   or IntroMessageBuilder::LESS_FRAMES to omit them whenever possible.
	 * @param string[] $skip Identifiers of messages not to generate
	 * @param MessageLocalizer $localizer
	 * @param ProperPageIdentity $page Page being viewed
	 * @param RevisionRecord|null $revRecord Revision being viewed, null if page doesn't exist
	 * @param Authority $performer
	 * @param string|null $editIntro
	 * @param string|null $returnToQuery
	 * @param bool $preview
	 * @param string|null $section
	 * @return array<string,string> Ordered map of identifiers to message HTML
	 */
	public function getIntroMessages(
		int $frames,
		array $skip,
		MessageLocalizer $localizer,
		ProperPageIdentity $page,
		?RevisionRecord $revRecord,
		Authority $performer,
		?string $editIntro,
		?string $returnToQuery,
		bool $preview,
		?string $section = null
	): array {
		$title = Title::newFromPageIdentity( $page );
		$messages = new IntroMessageList( $frames, $skip );

		$this->addOldRevisionWarning( $messages, $localizer, $revRecord );

		if ( !$preview ) {
			$this->addCodeEditingIntro( $messages, $localizer, $title, $performer );
			$this->addSharedRepoHint( $messages, $localizer, $page );
			$this->addUserWarnings( $messages, $localizer, $title, $performer );
			$this->addEditIntro( $messages, $localizer, $page, $performer, $editIntro, $section );
			$this->addRecreateWarning( $messages, $localizer, $page );
		}

		$this->addTalkPageText( $messages, $localizer, $title );
		$this->addEditNotices( $messages, $localizer, $title, $revRecord );

		$this->addReadOnlyWarning( $messages, $localizer );
		$this->addAnonEditWarning( $messages, $localizer, $title, $performer, $returnToQuery, $preview );
		$this->addUserConfigPageInfo( $messages, $localizer, $title, $performer, $preview );
		$this->addPageProtectionWarningHeaders( $messages, $localizer, $page );
		$this->addHeaderCopyrightWarning( $messages, $localizer );

		return $messages->getList();
	}

	/**
	 * Adds introduction to code editing.
	 */
	private function addCodeEditingIntro(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		Title $title,
		Authority $performer
	): void {
		$isUserJsConfig = $title->isUserJsConfigPage();
		$namespace = $title->getNamespace();
		$intro = '';

		if (
			$title->isUserConfigPage() &&
			$title->isSubpageOf( Title::makeTitle( NS_USER, $performer->getUser()->getName() ) )
		) {
			$isUserCssConfig = $title->isUserCssConfigPage();
			$isUserJsonConfig = $title->isUserJsonConfigPage();
			$isUserJsConfig = $title->isUserJsConfigPage();

			if ( $isUserCssConfig ) {
				$warning = 'usercssispublic';
			} elseif ( $isUserJsonConfig ) {
				$warning = 'userjsonispublic';
			} else {
				$warning = 'userjsispublic';
			}

			$warningText = $localizer->msg( $warning )->parse();
			$intro .= $warningText ? Html::rawElement(
				'div',
				[ 'class' => 'mw-userconfigpublic' ],
				$warningText
			) : '';

		}
		$codeMsg = $localizer->msg( 'editpage-code-message' );
		$codeMessageText = $codeMsg->isDisabled() ? '' : $codeMsg->parseAsBlock();
		$isJavaScript = $title->hasContentModel( CONTENT_MODEL_JAVASCRIPT ) ||
			$title->hasContentModel( CONTENT_MODEL_VUE );
		$isCSS = $title->hasContentModel( CONTENT_MODEL_CSS );

		if ( $namespace === NS_MEDIAWIKI ) {
			$interfaceMsg = $localizer->msg( 'editinginterface' );
			$interfaceMsgText = $interfaceMsg->parse();
			# Show a warning if editing an interface message
			$intro .= $interfaceMsgText ? Html::rawElement(
				'div',
				[ 'class' => 'mw-editinginterface' ],
				$interfaceMsgText
			) : '';
			# If this is a default message (but not css, json, js or vue),
			# show a hint that it is translatable on translatewiki.net
			if (
				!$isCSS
				&& !$title->hasContentModel( CONTENT_MODEL_JSON )
				&& !$isJavaScript
			) {
				$defaultMessageText = $title->getDefaultMessageText();
				if ( $defaultMessageText !== false ) {
					$translateInterfaceText = $localizer->msg( 'translateinterface' )->parse();
					$intro .= $translateInterfaceText ? Html::rawElement(
						'div',
						[ 'class' => 'mw-translateinterface' ],
						$translateInterfaceText
					) : '';
				}
			}
		}

		if ( $isUserJsConfig ) {
			$userConfigDangerousMsg = $localizer->msg( 'userjsdangerous' )->parse();
			$intro .= $userConfigDangerousMsg ? Html::rawElement(
				'div',
				[ 'class' => 'mw-userconfigdangerous' ],
				$userConfigDangerousMsg
			) : '';
		}

		// If the wiki page contains JavaScript or CSS link add message specific to code.
		if ( $isJavaScript || $isCSS ) {
			$intro .= $codeMessageText;
		}

		$messages->addWithKey(
			'code-editing-intro',
			$intro,
			// While semantically this is a warning, given the impact of editing these pages,
			// it's best to deter users who don't understand what they are doing by
			// acknowledging the danger here. This is a potentially destructive action
			// so requires destructive coloring.
			Html::errorBox( '$1' )
		);
	}

	private function addSharedRepoHint(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		ProperPageIdentity $page
	): void {
		$namespace = $page->getNamespace();
		if ( $namespace === NS_FILE ) {
			# Show a hint to shared repo
			$file = $this->repoGroup->findFile( $page );
			if ( $file && !$file->isLocal() ) {
				$descUrl = $file->getDescriptionUrl();
				# there must be a description url to show a hint to shared repo
				if ( $descUrl ) {
					if ( !$page->exists() ) {
						$messages->add(
							$localizer->msg(
								'sharedupload-desc-create',
								$file->getRepo()->getDisplayName(),
								$descUrl
							),
							"<div class=\"mw-sharedupload-desc-create\">\n$1\n</div>"
						);
					} else {
						$messages->add(
							$localizer->msg(
								'sharedupload-desc-edit',
								$file->getRepo()->getDisplayName(),
								$descUrl
							),
							"<div class=\"mw-sharedupload-desc-edit\">\n$1\n</div>"
						);
					}
				}
			}
		}
	}

	private function addUserWarnings(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		Title $title,
		Authority $performer
	): void {
		$namespace = $title->getNamespace();
		# Show a warning message when someone creates/edits a user (talk) page but the user does not exist
		# Show log extract when the user is currently blocked
		if ( $namespace === NS_USER || $namespace === NS_USER_TALK ) {
			$username = explode( '/', $title->getText(), 2 )[0];
			// Allow IP users
			$validation = UserRigorOptions::RIGOR_NONE;
			$user = $this->userFactory->newFromName( $username, $validation );
			$ip = $this->userNameUtils->isIP( $username );

			$userExists = ( $user && $user->isRegistered() );
			if ( $userExists && $user->isHidden() && !$performer->isAllowed( 'hideuser' ) ) {
				// If the user exists, but is hidden, and the viewer cannot see hidden
				// users, pretend like they don't exist at all. See T120883
				$userExists = false;
			}

			if ( !$userExists && !$ip ) {
				$messages->addWithKey(
					'userpage-userdoesnotexist',
					// This wrapper frame, for whatever reason, is not optional
					Html::warningBox(
						$localizer->msg( 'userpage-userdoesnotexist', wfEscapeWikiText( $username ) )->parse(),
						'mw-userpage-userdoesnotexist'
					)
				);
				return;
			}

			$blockLogBox = LogEventsList::getBlockLogWarningBox(
				$this->blockStore,
				$this->namespaceInfo,
				$localizer,
				$this->linkRenderer,
				$user,
				$title
			);
			if ( $blockLogBox !== null ) {
				$messages->addWithKey( 'blocked-notice-logextract', $blockLogBox );
			}
		}
	}

	/**
	 * Try to add a custom edit intro, or use the standard one if this is not possible.
	 */
	private function addEditIntro(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		ProperPageIdentity $page,
		Authority $performer,
		?string $editIntro,
		?string $section
	): void {
		if ( ( $editIntro === null || $editIntro === '' ) && $section === 'new' ) {
			// Custom edit intro for new sections
			$editIntro = 'MediaWiki:addsection-editintro';
		}
		if ( $editIntro !== null && $editIntro !== '' ) {
			$introTitle = Title::newFromText( $editIntro );

			// (T334855) Use SpecialMyLanguage redirect so that nonexistent translated pages can
			// fall back to the corresponding page in a suitable language
			$introTitle = $this->getTargetTitleIfSpecialMyLanguage( $introTitle );

			if ( $this->isPageExistingAndViewable( $introTitle, $performer ) ) {
				$messages->addWithKey(
					'editintro',
					$localizer->msg( new RawMessage(
						// Added using template syntax, to take <noinclude>'s into account.
						'<div class="mw-editintro">{{:' . $introTitle->getFullText() . '}}</div>'
					) )
						// Parse as content to enable language conversion (T353870)
						->inContentLanguage()
						->parse()
				);
				return;
			}
		}

		if ( !$page->exists() ) {
			$helpLink = $this->urlUtils->expand(
				Skin::makeInternalOrExternalUrl(
					$localizer->msg( 'helppage' )->inContentLanguage()->text()
				),
				PROTO_CURRENT
			);
			if ( $helpLink === null ) {
				throw new LogicException( 'Help link was invalid, this should be impossible' );
			}
			if ( $performer->getUser()->isRegistered() ) {
				$messages->add(
					$localizer->msg( 'newarticletext', $helpLink ),
					// Suppress the external link icon, consider the help url an internal one
					"<div class=\"mw-newarticletext plainlinks\">\n$1\n</div>"
				);
			} else {
				$messages->add(
					$localizer->msg( 'newarticletextanon', $helpLink ),
					// Suppress the external link icon, consider the help url an internal one
					"<div class=\"mw-newarticletextanon plainlinks\">\n$1\n</div>"
				);
			}
		}
	}

	private function addRecreateWarning(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		ProperPageIdentity $page
	): void {
		# Give a notice if the user is editing a deleted/moved page...
		if ( !$page->exists() ) {
			$dbr = $this->dbProvider->getReplicaDatabase();

			$messages->addWithKey(
				'recreate-moveddeleted-warn',
				$this->getLogExtract( [ 'delete', 'move', 'merge' ], $page, '', [
					'lim' => 10,
					'conds' => [ $dbr->expr( 'log_action', '!=', 'revision' ) ],
					'showIfEmpty' => false,
					'msgKey' => [ 'recreate-moveddeleted-warn' ],
				] )
			);
		}
	}

	private function addTalkPageText(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		Title $title
	): void {
		if ( $title->isTalkPage() ) {
			$messages->add( $localizer->msg( 'talkpagetext' ) );
		}
	}

	private function addEditNotices(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		Title $title,
		?RevisionRecord $revRecord
	): void {
		$editNotices = $title->getEditNotices( $revRecord ? $revRecord->getId() : 0 );
		if ( count( $editNotices ) ) {
			foreach ( $editNotices as $key => $html ) {
				$messages->addWithKey( $key, $html );
			}
		} else {
			$msg = $localizer->msg( 'editnotice-notext' );
			if ( !$msg->isDisabled() ) {
				$messages->addWithKey(
					'editnotice-notext',
					Html::rawElement(
						'div',
						[ 'class' => 'mw-editnotice-notext' ],
						$msg->parseAsBlock()
					)
				);
			}
		}
	}

	private function addOldRevisionWarning(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		?RevisionRecord $revRecord
	): void {
		if ( $revRecord && !$revRecord->isCurrent() ) {
			// This wrapper frame is not optional (T337071)
			$messages->addWithKey( 'editingold', Html::warningBox( $localizer->msg( 'editingold' )->parse() ) );
		}
	}

	private function addReadOnlyWarning(
		IntroMessageList $messages,
		MessageLocalizer $localizer
	): void {
		if ( $this->readOnlyMode->isReadOnly() ) {
			$messages->add(
				$localizer->msg( 'readonlywarning', $this->readOnlyMode->getReason() ),
				"<div id=\"mw-read-only-warning\">\n$1\n</div>"
			);
		}
	}

	private function addAnonEditWarning(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		Title $title,
		Authority $performer,
		?string $returnToQuery,
		bool $preview
	): void {
		if ( !$performer->getUser()->isRegistered() ) {
			$tempUserCreateActive = $this->tempUserCreator->shouldAutoCreate( $performer, 'edit' );
			if ( !$preview ) {
				$messages->addWithKey(
					'anoneditwarning',
					$localizer->msg(
						$tempUserCreateActive ? 'autocreate-edit-warning' : 'anoneditwarning',
						// Log-in link
						SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
							'returnto' => $title->getPrefixedDBkey(),
							'returntoquery' => $returnToQuery,
						] ),
						// Sign-up link
						SpecialPage::getTitleFor( 'CreateAccount' )->getFullURL( [
							'returnto' => $title->getPrefixedDBkey(),
							'returntoquery' => $returnToQuery,
						] )
					)->parse(),
					Html::warningBox( '$1', 'mw-anon-edit-warning' )
				);
			} else {
				$messages->addWithKey(
					'anoneditwarning',
					$localizer->msg( $tempUserCreateActive ? 'autocreate-preview-warning' : 'anonpreviewwarning' )
						->parse(),
					Html::warningBox( '$1', 'mw-anon-preview-warning' ) );
			}
		}
	}

	/**
	 * Checks whether the user entered a skin name in uppercase,
	 * e.g. "User:Example/Monobook.css" instead of "monobook.css"
	 */
	private function isWrongCaseUserConfigPage( Title $title ): bool {
		if ( $title->isUserCssConfigPage() || $title->isUserJsConfigPage() ) {
			$name = $title->getSkinFromConfigSubpage();
			$skins = array_merge(
				array_keys( $this->skinFactory->getInstalledSkins() ),
				[ 'common' ]
			);
			return !in_array( $name, $skins, true )
				&& in_array( strtolower( $name ), $skins, true );
		} else {
			return false;
		}
	}

	private function addUserConfigPageInfo(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		Title $title,
		Authority $performer,
		bool $preview
	): void {
		if ( $title->isUserConfigPage() ) {
			# Check the skin exists
			if ( $this->isWrongCaseUserConfigPage( $title ) ) {
				$messages->add(
					$localizer->msg( 'userinvalidconfigtitle', $title->getSkinFromConfigSubpage() ),
					Html::errorBox( '$1', '', 'mw-userinvalidconfigtitle' )
				);
			}
			if ( $title->isSubpageOf( Title::makeTitle( NS_USER, $performer->getUser()->getName() ) ) ) {
				$isUserCssConfig = $title->isUserCssConfigPage();
				$isUserJsonConfig = $title->isUserJsonConfigPage();
				$isUserJsConfig = $title->isUserJsConfigPage();

				if ( !$preview ) {
					if ( $isUserCssConfig && $this->config->get( MainConfigNames::AllowUserCss ) ) {
						$messages->add(
							$localizer->msg( 'usercssyoucanpreview' ),
							"<div id='mw-usercssyoucanpreview'>\n$1\n</div>"
						);
					} elseif ( $isUserJsonConfig /* No comparable 'AllowUserJson' */ ) {
						$messages->add(
							$localizer->msg( 'userjsonyoucanpreview' ),
							"<div id='mw-userjsonyoucanpreview'>\n$1\n</div>"
						);
					} elseif ( $isUserJsConfig && $this->config->get( MainConfigNames::AllowUserJs ) ) {
						$messages->add(
							$localizer->msg( 'userjsyoucanpreview' ),
							"<div id='mw-userjsyoucanpreview'>\n$1\n</div>"
						);
					}
				}
			}
		}
	}

	private function addPageProtectionWarningHeaders(
		IntroMessageList $messages,
		MessageLocalizer $localizer,
		ProperPageIdentity $page
	): void {
		if ( $this->restrictionStore->isProtected( $page, 'edit' ) &&
			$this->permManager->getNamespaceRestrictionLevels(
				$page->getNamespace()
			) !== [ '' ]
		) {
			# Is the title semi-protected?
			if ( $this->restrictionStore->isSemiProtected( $page ) ) {
				$noticeMsg = 'semiprotectedpagewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagewarning';
			}
			$messages->addWithKey(
				$noticeMsg,
				$this->getLogExtract( 'protect', $page, '', [ 'lim' => 1, 'msgKey' => [ $noticeMsg ] ] )
			);
		}
		if ( $this->restrictionStore->isCascadeProtected( $page ) ) {
			# Is this page under cascading protection from some source pages?
			$tlCascadeSources = $this->restrictionStore->getCascadeProtectionSources( $page )[2];
			if ( $tlCascadeSources ) {
				$htmlList = '';
				# Explain, and list the titles responsible
				foreach ( $tlCascadeSources as $source ) {
					$htmlList .= Html::rawElement( 'li', [], $this->linkRenderer->makeLink( $source ) );
				}
				$messages->addWithKey(
					'cascadeprotectedwarning',
					$localizer->msg( 'cascadeprotectedwarning', count( $tlCascadeSources ) )->parse() .
						( $htmlList ? Html::rawElement( 'ul', [], $htmlList ) : '' ),
					Html::warningBox( '$1', 'mw-cascadeprotectedwarning' )
				);
			}
		}
		if ( !$page->exists() && $this->restrictionStore->getRestrictions( $page, 'create' ) ) {
			$messages->addWithKey(
				'titleprotectedwarning',
				$this->getLogExtract(
					'protect', $page,
					'',
					[
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => [ 'titleprotectedwarning' ],
						'wrap' => "<div class=\"mw-titleprotectedwarning\">\n$1</div>"
					]
				)
			);
		}
	}

	private function addHeaderCopyrightWarning(
		IntroMessageList $messages,
		MessageLocalizer $localizer
	): void {
		$messages->add(
			$localizer->msg( 'editpage-head-copy-warn' ),
			"<div class='editpage-head-copywarn'>\n$1\n</div>"
		);
	}
}
