<?php
/**
 * Implements Special:Activeusers
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
 * @ingroup SpecialPage
 */

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentityLookup;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Implements Special:Activeusers
 *
 * @ingroup SpecialPage
 */
class SpecialActiveUsers extends SpecialPage {

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var UserGroupManager */
	private $userGroupManager;

	/** @var UserIdentityLookup */
	private $userIdentityLookup;

	/**
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param ILoadBalancer $loadBalancer
	 * @param UserGroupManager $userGroupManager
	 * @param UserIdentityLookup $userIdentityLookup
	 */
	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		ILoadBalancer $loadBalancer,
		UserGroupManager $userGroupManager,
		UserIdentityLookup $userIdentityLookup
	) {
		parent::__construct( 'Activeusers' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->loadBalancer = $loadBalancer;
		$this->userGroupManager = $userGroupManager;
		$this->userIdentityLookup = $userIdentityLookup;
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
			$this->loadBalancer,
			$this->userGroupManager,
			$this->userIdentityLookup,
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
		$dbr = $this->loadBalancer->getConnection( ILoadBalancer::DB_REPLICA );
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

	protected function getGroupName() {
		return 'users';
	}
}
