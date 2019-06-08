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

/**
 * Implements Special:Activeusers
 *
 * @ingroup SpecialPage
 */
class SpecialActiveUsers extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Activeusers' );
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

		$pager = new ActiveUsersPager( $this->getContext(), $opts );
		$usersBody = $pager->getBody();

		$this->buildForm();

		if ( $usersBody ) {
			$out->addHTML(
				$pager->getNavigationBar() .
				Html::rawElement( 'ul', [], $usersBody ) .
				$pager->getNavigationBar()
			);
		} else {
			$out->addWikiMsg( 'activeusers-noresult' );
		}
	}

	/**
	 * Generate and output the form
	 */
	protected function buildForm() {
		$groups = User::getAllGroups();

		$options = [];
		foreach ( $groups as $group ) {
			$msg = htmlspecialchars( UserGroupMembership::getGroupName( $group ) );
			$options[$msg] = $group;
		}
		asort( $options );

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
			->setIntro( $this->getIntroText() )
			->setWrapperLegendMsg( 'activeusers' )
			->setSubmitTextMsg( 'activeusers-submit' )
			// prevent setting subpage and 'username' parameter at the same time
			->setAction( $this->getPageTitle()->getLocalURL() )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
	}

	/**
	 * Return introductory message.
	 * @return string
	 */
	protected function getIntroText() {
		$days = $this->getConfig()->get( 'ActiveUserDays' );

		$intro = $this->msg( 'activeusers-intro' )->numParams( $days )->parse();

		// Mention the level of cache staleness...
		$dbr = wfGetDB( DB_REPLICA, 'recentchanges' );
		$rcMax = $dbr->selectField( 'recentchanges', 'MAX(rc_timestamp)', '', __METHOD__ );
		if ( $rcMax ) {
			$cTime = $dbr->selectField( 'querycache_info',
				'qci_timestamp',
				[ 'qci_type' => 'activeusers' ],
				__METHOD__
			);
			if ( $cTime ) {
				$secondsOld = wfTimestamp( TS_UNIX, $rcMax ) - wfTimestamp( TS_UNIX, $cTime );
			} else {
				$rcMin = $dbr->selectField( 'recentchanges', 'MIN(rc_timestamp)' );
				$secondsOld = time() - wfTimestamp( TS_UNIX, $rcMin );
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
