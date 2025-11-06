<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Block\HideUserUtils;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\ActiveUsersPager;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentityLookup;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Activeusers
 *
 * @ingroup SpecialPage
 */
class SpecialActiveUsers extends SpecialPage {

	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;
	private UserGroupManager $userGroupManager;
	private UserIdentityLookup $userIdentityLookup;
	private HideUserUtils $hideUserUtils;
	private TempUserConfig $tempUserConfig;
	private RecentChangeLookup $recentChangeLookup;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		UserGroupManager $userGroupManager,
		UserIdentityLookup $userIdentityLookup,
		HideUserUtils $hideUserUtils,
		TempUserConfig $tempUserConfig,
		RecentChangeLookup $recentChangeLookup
	) {
		parent::__construct( 'Activeusers' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
		$this->userGroupManager = $userGroupManager;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->hideUserUtils = $hideUserUtils;
		$this->tempUserConfig = $tempUserConfig;
		$this->recentChangeLookup = $recentChangeLookup;
	}

	/**
	 * @param string|null $par Parameter passed to the page or null
	 */
	public function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		$opts = new FormOptions();

		$opts->add( 'username', '' );
		$opts->add( 'groups', [] );
		$opts->add( 'excludegroups', [] );
		// Backwards-compatibility with old URLs
		$opts->add( 'hidebots', false, FormOptions::BOOL );
		$opts->add( 'hidesysops', false, FormOptions::BOOL );

		$opts->fetchValuesFromRequest( $this->getRequest() );

		if ( $par !== null ) {
			$opts->setValue( 'username', $par );
		}

		$pager = new ActiveUsersPager(
			$this->getContext(),
			$this->getHookContainer(),
			$this->linkBatchFactory,
			$this->dbProvider,
			$this->userGroupManager,
			$this->userIdentityLookup,
			$this->hideUserUtils,
			$this->tempUserConfig,
			$this->recentChangeLookup,
			$opts
		);
		$usersBody = $pager->getBody();

		$this->buildForm();

		if ( $usersBody ) {
			$out->addHTML(
				$pager->getNavigationBar() .
				Html::rawElement( 'ul', [], $usersBody ) .
				$pager->getNavigationBar()
			);
			$out->addModuleStyles( 'mediawiki.interface.helpers.styles' );
		} else {
			$out->addWikiMsg( 'activeusers-noresult' );
		}
	}

	/**
	 * Generate and output the form
	 */
	protected function buildForm() {
		$groups = $this->userGroupManager->listAllGroups();

		$options = [];
		$lang = $this->getLanguage();
		foreach ( $groups as $group ) {
			$msg = htmlspecialchars( $lang->getGroupName( $group ) );
			$options[$msg] = $group;
		}
		ksort( $options );

		// Backwards-compatibility with old URLs
		$req = $this->getRequest();
		$excludeDefault = [];
		if ( $req->getCheck( 'hidebots' ) ) {
			$excludeDefault[] = 'bot';
		}
		if ( $req->getCheck( 'hidesysops' ) ) {
			$excludeDefault[] = 'sysop';
		}

		$formDescriptor = [
			'username' => [
				'type' => 'user',
				'name' => 'username',
				'label-message' => 'activeusers-from',
			],
			'groups' => [
				'type' => 'multiselect',
				'dropdown' => true,
				'flatlist' => true,
				'name' => 'groups',
				'label-message' => 'activeusers-groups',
				'options' => $options,
			],
			'excludegroups' => [
				'type' => 'multiselect',
				'dropdown' => true,
				'flatlist' => true,
				'name' => 'excludegroups',
				'label-message' => 'activeusers-excludegroups',
				'options' => $options,
				'default' => $excludeDefault,
			],
		];

		HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			// For the 'multiselect' field values to be preserved on submit
			->setFormIdentifier( 'specialactiveusers' )
			->setPreHtml( $this->getIntroText() )
			->setWrapperLegendMsg( 'activeusers' )
			->setSubmitTextMsg( 'activeusers-submit' )
			// prevent setting subpage and 'username' parameter at the same time
			->setTitle( $this->getPageTitle() )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
	}

	/**
	 * Return introductory message.
	 * @return string
	 */
	protected function getIntroText() {
		$days = $this->getConfig()->get( MainConfigNames::ActiveUserDays );

		$intro = $this->msg( 'activeusers-intro' )->numParams( $days )->parse();

		// Mention the level of cache staleness...
		$dbr = $this->dbProvider->getReplicaDatabase();

		$rcMax = $dbr->newSelectQueryBuilder()
			->select( 'MAX(rc_timestamp)' )
			->from( 'recentchanges' )
			->caller( __METHOD__ )->fetchField();
		if ( $rcMax ) {
			$cTime = $dbr->newSelectQueryBuilder()
				->select( 'qci_timestamp' )
				->from( 'querycache_info' )
				->where( [ 'qci_type' => 'activeusers' ] )
				->caller( __METHOD__ )->fetchField();
			if ( $cTime ) {
				$secondsOld = (int)wfTimestamp( TS_UNIX, $rcMax ) - (int)wfTimestamp( TS_UNIX, $cTime );
			} else {
				$rcMin = $dbr->newSelectQueryBuilder()
					->select( 'MIN(rc_timestamp)' )
					->from( 'recentchanges' )
					->caller( __METHOD__ )->fetchField();
				$secondsOld = time() - (int)wfTimestamp( TS_UNIX, $rcMin );
			}
			if ( $secondsOld > 0 ) {
				$intro .= $this->msg( 'cachedspecial-viewing-cached-ttl' )
					->durationParams( $secondsOld )->parseAsBlock();
			}
		}

		return $intro;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialActiveUsers::class, 'SpecialActiveUsers' );
