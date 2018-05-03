<?php
/**
 * Implements Special:Log
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
 * A special page that lists log entries
 *
 * @ingroup SpecialPage
 */
class SpecialLog extends SpecialPage {
	public function __construct() {
		parent::__construct( 'Log' );
	}

	public function execute( $par ) {
		global $wgActorTableSchemaMigrationStage;

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModules( 'mediawiki.userSuggest' );
		$this->addHelpLink( 'Help:Log' );

		$opts = new FormOptions;
		$opts->add( 'type', '' );
		$opts->add( 'user', '' );
		$opts->add( 'page', '' );
		$opts->add( 'pattern', false );
		$opts->add( 'year', null, FormOptions::INTNULL );
		$opts->add( 'month', null, FormOptions::INTNULL );
		$opts->add( 'tagfilter', '' );
		$opts->add( 'offset', '' );
		$opts->add( 'dir', '' );
		$opts->add( 'offender', '' );
		$opts->add( 'subtype', '' );
		$opts->add( 'logid', '' );

		// Set values
		$opts->fetchValuesFromRequest( $this->getRequest() );
		if ( $par !== null ) {
			$this->parseParams( $opts, (string)$par );
		}

		# Don't let the user get stuck with a certain date
		if ( $opts->getValue( 'offset' ) || $opts->getValue( 'dir' ) == 'prev' ) {
			$opts->setValue( 'year', '' );
			$opts->setValue( 'month', '' );
		}

		// If the user doesn't have the right permission to view the specific
		// log type, throw a PermissionsError
		// If the log type is invalid, just show all public logs
		$logRestrictions = $this->getConfig()->get( 'LogRestrictions' );
		$type = $opts->getValue( 'type' );
		if ( !LogPage::isLogType( $type ) ) {
			$opts->setValue( 'type', '' );
		} elseif ( isset( $logRestrictions[$type] )
			&& !$this->getUser()->isAllowed( $logRestrictions[$type] )
		) {
			throw new PermissionsError( $logRestrictions[$type] );
		}

		# Handle type-specific inputs
		$qc = [];
		if ( $opts->getValue( 'type' ) == 'suppress' ) {
			$offenderName = $opts->getValue( 'offender' );
			$offender = empty( $offenderName ) ? null : User::newFromName( $offenderName, false );
			if ( $offender ) {
				if ( $wgActorTableSchemaMigrationStage === MIGRATION_NEW ) {
					$qc = [ 'ls_field' => 'target_author_actor', 'ls_value' => $offender->getActorId() ];
				} else {
					if ( $offender->getId() > 0 ) {
						$field = 'target_author_id';
						$value = $offender->getId();
					} else {
						$field = 'target_author_ip';
						$value = $offender->getName();
					}
					if ( !$offender->getActorId() ) {
						$qc = [ 'ls_field' => $field, 'ls_value' => $value ];
					} else {
						$db = wfGetDB( DB_REPLICA );
						$qc = [
							'ls_field' => [ 'target_author_actor', $field ], // So LogPager::getQueryInfo() works right
							$db->makeList( [
								$db->makeList(
									[ 'ls_field' => 'target_author_actor', 'ls_value' => $offender->getActorId() ], LIST_AND
								),
								$db->makeList( [ 'ls_field' => $field, 'ls_value' => $value ], LIST_AND ),
							], LIST_OR ),
						];
					}
				}
			}
		} else {
			// Allow extensions to add relations to their search types
			Hooks::run(
				'SpecialLogAddLogSearchRelations',
				[ $opts->getValue( 'type' ), $this->getRequest(), &$qc ]
			);
		}

		# Some log types are only for a 'User:' title but we might have been given
		# only the username instead of the full title 'User:username'. This part try
		# to lookup for a user by that name and eventually fix user input. See T3697.
		if ( in_array( $opts->getValue( 'type' ), self::getLogTypesOnUser() ) ) {
			# ok we have a type of log which expect a user title.
			$target = Title::newFromText( $opts->getValue( 'page' ) );
			if ( $target && $target->getNamespace() === NS_MAIN ) {
				# User forgot to add 'User:', we are adding it for him
				$opts->setValue( 'page',
					Title::makeTitleSafe( NS_USER, $opts->getValue( 'page' ) )
				);
			}
		}

		$this->show( $opts, $qc );
	}

	/**
	 * List log type for which the target is a user
	 * Thus if the given target is in NS_MAIN we can alter it to be an NS_USER
	 * Title user instead.
	 *
	 * @since 1.25
	 * @return array
	 */
	public static function getLogTypesOnUser() {
		static $types = null;
		if ( $types !== null ) {
			return $types;
		}
		$types = [
			'block',
			'newusers',
			'rights',
		];

		Hooks::run( 'GetLogTypesOnUser', [ &$types ] );
		return $types;
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		$subpages = $this->getConfig()->get( 'LogTypes' );
		$subpages[] = 'all';
		sort( $subpages );
		return $subpages;
	}

	/**
	 * Set options based on the subpage title parts:
	 * - One part that is a valid log type: Special:Log/logtype
	 * - Two parts: Special:Log/logtype/username
	 * - Otherwise, assume the whole subpage is a username.
	 *
	 * @param FormOptions $opts
	 * @param $par
	 * @throws ConfigException
	 */
	private function parseParams( FormOptions $opts, $par ) {
		# Get parameters
		$par = $par !== null ? $par : '';
		$parms = explode( '/', $par );
		$symsForAll = [ '*', 'all' ];
		if ( $parms[0] != '' &&
			( in_array( $par, $this->getConfig()->get( 'LogTypes' ) ) || in_array( $par, $symsForAll ) )
		) {
			$opts->setValue( 'type', $par );
		} elseif ( count( $parms ) == 2 ) {
			$opts->setValue( 'type', $parms[0] );
			$opts->setValue( 'user', $parms[1] );
		} elseif ( $par != '' ) {
			$opts->setValue( 'user', $par );
		}
	}

	private function show( FormOptions $opts, array $extraConds ) {
		# Create a LogPager item to get the results and a LogEventsList item to format them...
		$loglist = new LogEventsList(
			$this->getContext(),
			$this->getLinkRenderer(),
			LogEventsList::USE_CHECKBOXES
		);

		$pager = new LogPager(
			$loglist,
			$opts->getValue( 'type' ),
			$opts->getValue( 'user' ),
			$opts->getValue( 'page' ),
			$opts->getValue( 'pattern' ),
			$extraConds,
			$opts->getValue( 'year' ),
			$opts->getValue( 'month' ),
			$opts->getValue( 'tagfilter' ),
			$opts->getValue( 'subtype' ),
			$opts->getValue( 'logid' )
		);

		$this->addHeader( $opts->getValue( 'type' ) );

		# Set relevant user
		if ( $pager->getPerformer() ) {
			$performerUser = User::newFromName( $pager->getPerformer(), false );
			$this->getSkin()->setRelevantUser( $performerUser );
		}

		# Show form options
		$loglist->showOptions(
			$pager->getType(),
			$pager->getPerformer(),
			$pager->getPage(),
			$pager->getPattern(),
			$pager->getYear(),
			$pager->getMonth(),
			$pager->getFilterParams(),
			$pager->getTagFilter(),
			$pager->getAction()
		);

		# Insert list
		$logBody = $pager->getBody();
		if ( $logBody ) {
			$this->getOutput()->addHTML(
				$pager->getNavigationBar() .
					$this->getActionButtons(
						$loglist->beginLogEventsList() .
							$logBody .
							$loglist->endLogEventsList()
					) .
					$pager->getNavigationBar()
			);
		} else {
			$this->getOutput()->addWikiMsg( 'logempty' );
		}
	}

	private function getActionButtons( $formcontents ) {
		$user = $this->getUser();
		$canRevDelete = $user->isAllowedAll( 'deletedhistory', 'deletelogentry' );
		$showTagEditUI = ChangeTags::showTagEditingUI( $user );
		# If the user doesn't have the ability to delete log entries nor edit tags,
		# don't bother showing them the button(s).
		if ( !$canRevDelete && !$showTagEditUI ) {
			return $formcontents;
		}

		# Show button to hide log entries and/or edit change tags
		$s = Html::openElement(
			'form',
			[ 'action' => wfScript(), 'id' => 'mw-log-deleterevision-submit' ]
		) . "\n";
		$s .= Html::hidden( 'action', 'historysubmit' ) . "\n";
		$s .= Html::hidden( 'type', 'logging' ) . "\n";

		$buttons = '';
		if ( $canRevDelete ) {
			$buttons .= Html::element(
				'button',
				[
					'type' => 'submit',
					'name' => 'revisiondelete',
					'value' => '1',
					'class' => "deleterevision-log-submit mw-log-deleterevision-button"
				],
				$this->msg( 'showhideselectedlogentries' )->text()
			) . "\n";
		}
		if ( $showTagEditUI ) {
			$buttons .= Html::element(
				'button',
				[
					'type' => 'submit',
					'name' => 'editchangetags',
					'value' => '1',
					'class' => "editchangetags-log-submit mw-log-editchangetags-button"
				],
				$this->msg( 'log-edit-tags' )->text()
			) . "\n";
		}

		$buttons .= ( new ListToggle( $this->getOutput() ) )->getHTML();

		$s .= $buttons . $formcontents . $buttons;
		$s .= Html::closeElement( 'form' );

		return $s;
	}

	/**
	 * Set page title and show header for this log type
	 * @param string $type
	 * @since 1.19
	 */
	protected function addHeader( $type ) {
		$page = new LogPage( $type );
		$this->getOutput()->setPageTitle( $page->getName() );
		$this->getOutput()->addHTML( $page->getDescription()
			->setContext( $this->getContext() )->parseAsBlock() );
	}

	protected function getGroupName() {
		return 'changes';
	}
}
