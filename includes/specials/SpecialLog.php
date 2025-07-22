<?php
/**
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

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Html\ListToggle;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Pager\LogPager;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Timestamp\TimestampException;

/**
 * A special page that lists log entries
 *
 * @ingroup SpecialPage
 */
class SpecialLog extends SpecialPage {

	private LinkBatchFactory $linkBatchFactory;

	private IConnectionProvider $dbProvider;

	private ActorNormalization $actorNormalization;

	private UserIdentityLookup $userIdentityLookup;

	private UserNameUtils $userNameUtils;

	private LogFormatterFactory $logFormatterFactory;

	private TempUserConfig $tempUserConfig;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		ActorNormalization $actorNormalization,
		UserIdentityLookup $userIdentityLookup,
		UserNameUtils $userNameUtils,
		LogFormatterFactory $logFormatterFactory,
		?TempUserConfig $tempUserConfig = null
	) {
		parent::__construct( 'Log' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
		$this->actorNormalization = $actorNormalization;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userNameUtils = $userNameUtils;
		$this->logFormatterFactory = $logFormatterFactory;
		if ( $tempUserConfig instanceof TempUserConfig ) {
			$this->tempUserConfig = $tempUserConfig;
		} else {
			$this->tempUserConfig = MediaWikiServices::getInstance()->getTempUserConfig();
		}
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.interface.helpers.styles' );
		$this->addHelpLink( 'Help:Log' );

		$opts = new FormOptions;
		$opts->add( 'type', '' );
		$opts->add( 'user', '' );
		$opts->add( 'page', [] );
		$opts->add( 'pattern', false );
		$opts->add( 'year', null, FormOptions::INTNULL );
		$opts->add( 'month', null, FormOptions::INTNULL );
		$opts->add( 'day', null, FormOptions::INTNULL );
		$opts->add( 'tagfilter', '' );
		$opts->add( 'tagInvert', false );
		$opts->add( 'offset', '' );
		$opts->add( 'dir', '' );
		$opts->add( 'offender', '' );
		$opts->add( 'subtype', '' );
		$opts->add( 'logid', '' );

		// Set values
		if ( $par !== null ) {
			$this->parseParams( (string)$par );
		}
		$opts->fetchValuesFromRequest( $this->getRequest() );

		// Set date values
		$dateString = $this->getRequest()->getVal( 'wpdate' );
		if ( $dateString ) {
			try {
				$dateStamp = MWTimestamp::getInstance( $dateString . ' 00:00:00' );
			} catch ( TimestampException ) {
				// If users provide an invalid date, silently ignore it
				// instead of letting an exception bubble up (T201411)
				$dateStamp = false;
			}
			if ( $dateStamp ) {
				$opts->setValue( 'year', (int)$dateStamp->format( 'Y' ) );
				$opts->setValue( 'month', (int)$dateStamp->format( 'm' ) );
				$opts->setValue( 'day', (int)$dateStamp->format( 'd' ) );
			}
		}

		// If the user doesn't have the right permission to view the specific
		// log type, throw a PermissionsError
		$logRestrictions = $this->getConfig()->get( MainConfigNames::LogRestrictions );
		$type = $opts->getValue( 'type' );
		if ( isset( $logRestrictions[$type] )
			&& !$this->getAuthority()->isAllowed( $logRestrictions[$type] )
		) {
			throw new PermissionsError( $logRestrictions[$type] );
		}

		# TODO: Move this into LogPager like other query conditions.
		# Handle type-specific inputs
		$qc = [];
		$offenderName = $opts->getValue( 'offender' );
		if ( $opts->getValue( 'type' ) == 'suppress' && $offenderName !== '' ) {
			$dbr = $this->dbProvider->getReplicaDatabase();
			$offenderId = $this->actorNormalization->findActorIdByName( $offenderName, $dbr );
			if ( $offenderId ) {
				$qc = [ 'ls_field' => 'target_author_actor', 'ls_value' => strval( $offenderId ) ];
			} else {
				// Unknown offender, thus results have to be empty
				$qc = [ '1=0' ];
			}
		} else {
			if ( $this->tempUserConfig->isKnown() ) {
				// See T398423
				// Three cases possible:
				// 1. Special:Log/newusers is loaded as-is. The checkbox will be shown checked by default
				// but have no value and is expected to exclude temporary accounts
				// 2. form submitted, exclude temp accounts
				// 3. form submitted, include temp accounts
				// Check for cases 1 and 2 and omit temporary accounts in the pager query.
				if (
					(
						!$this->getRequest()->getVal( 'issubmitted' ) &&
						!$this->getRequest()->getVal( 'excludetempacct' )
					) ||
					(
						$this->getRequest()->getVal( 'issubmitted' ) &&
						$this->getRequest()->getVal( 'excludetempacct' )
					)
				) {
					$dbr = $this->dbProvider->getReplicaDatabase();
					if ( $opts->getValue( 'type' ) === '' ) {
						// Support excluding temporary account creations on Special:Log
						$qc = [
							$dbr->expr( 'log_type', '!=', 'newusers' )->orExpr(
								$dbr->expr( 'log_type', '=', 'newusers' )
									->andExpr( $this->tempUserConfig
									->getMatchCondition( $dbr, 'logging_actor.actor_name', IExpression::NOT_LIKE ) )
							)
						];
					} elseif ( $opts->getValue( 'type' ) === 'newusers' ) {
						$qc = [
							$this->tempUserConfig
								->getMatchCondition( $dbr, 'logging_actor.actor_name', IExpression::NOT_LIKE )
						];
					}
				}
			}

			// Allow extensions to add relations to their search types
			$this->getHookRunner()->onSpecialLogAddLogSearchRelations(
				$opts->getValue( 'type' ), $this->getRequest(), $qc );
		}

		# TODO: Move this into LogEventList and use it as filter-callback in the field descriptor.
		# Some log types are only for a 'User:' title but we might have been given
		# only the username instead of the full title 'User:username'. This part try
		# to lookup for a user by that name and eventually fix user input. See T3697.
		if ( in_array( $opts->getValue( 'type' ), self::getLogTypesOnUser( $this->getHookRunner() ) ) ) {
			$pages = [];
			foreach ( $opts->getValue( 'page' ) as $page ) {
				$page = $this->normalizeUserPage( $page );
				if ( $page !== null ) {
					$pages[] = $page->getPrefixedText();
				}
			}
			$opts->setValue( 'page', $pages );
		}

		$this->show( $opts, $qc );
	}

	/**
	 * Add the namespace prefix to a user page and validate it
	 *
	 * @param string $page
	 * @return Title|null
	 */
	private function normalizeUserPage( $page ) {
		$target = Title::newFromText( $page );
		if ( $target && $target->getNamespace() === NS_MAIN ) {
			if ( IPUtils::isValidRange( $target->getText() ) ) {
				$page = IPUtils::sanitizeRange( $target->getText() );
			}
			# User forgot to add 'User:', we are adding it for them
			$target = Title::makeTitleSafe( NS_USER, $page );
		} elseif ( $target && $target->getNamespace() === NS_USER
			&& IPUtils::isValidRange( $target->getText() )
		) {
			$ipOrRange = IPUtils::sanitizeRange( $target->getText() );
			if ( $ipOrRange !== $target->getText() ) {
				$target = Title::makeTitleSafe( NS_USER, $ipOrRange );
			}
		}
		return $target;
	}

	/**
	 * List log type for which the target is a user
	 * Thus if the given target is in NS_MAIN we can alter it to be an NS_USER
	 * Title user instead.
	 *
	 * @since 1.25
	 * @since 1.36 Added $runner parameter
	 *
	 * @param HookRunner|null $runner
	 * @return array
	 */
	public static function getLogTypesOnUser( ?HookRunner $runner = null ) {
		static $types = null;
		if ( $types !== null ) {
			return $types;
		}
		$types = [
			'block',
			'newusers',
			'rights',
			'renameuser',
		];

		( $runner ?? new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			->onGetLogTypesOnUser( $types );
		return $types;
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		$subpages = LogPage::validTypes();
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
	 * @param string $par
	 */
	private function parseParams( string $par ) {
		$params = explode( '/', $par, 2 );
		$logType = $this->resolveLogType( $params );

		if ( $logType ) {
			$this->getRequest()->setVal( 'type', $logType );
			if ( count( $params ) === 2 ) {
				$this->getRequest()->setVal( 'user', $params[1] );
			}
		} elseif ( $par !== '' ) {
			$this->getRequest()->setVal( 'user', $par );
		}
	}

	/**
	 * Determines the requested log type based on the parameters from the
	 * requested URL, which are obtained by splitting the path by the slash
	 * character.
	 *
	 * This method returns the requested type, if one is provided and is '*',
	 * 'all', or is included in the values from LogPage::validTypes(); or an
	 * empty string otherwise.
	 *
	 * Extensions may modify the requested type by implementing the
	 * SpecialLogResolveLogType hook, which may be used to change the log type
	 * obtained from the URL and other request parameters.
	 *
	 * @param array $params Values resulting from splitting the URL by '/'.
	 * @return string The requested type if valid, or an empty string otherwise.
	 */
	private function resolveLogType( array $params ): string {
		// Mechanism for changing the parameters of Special:Log
		// from extensions (T381875)
		$logType = $params[0] ?? null;

		$this->getHookRunner()->onSpecialLogResolveLogType(
			$params,
			$logType
		);

		if ( $logType !== '' ) {
			$symsForAll = [ '*', 'all' ];
			$allowedTypes = array_merge( LogPage::validTypes(), $symsForAll );

			if ( in_array( $logType, $allowedTypes ) ) {
				return $logType;
			}
		}

		return '';
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
			$opts->getValue( 'day' ),
			$opts->getValue( 'tagfilter' ),
			$opts->getValue( 'subtype' ),
			$opts->getValue( 'logid' ),
			$this->linkBatchFactory,
			$this->actorNormalization,
			$this->logFormatterFactory,
			$opts->getValue( 'tagInvert' )
		);

		# Set relevant user
		$performer = $pager->getPerformer();
		if ( $performer ) {
			$performerUser = $this->userIdentityLookup->getUserIdentityByName( $performer );
			// Only set valid local user as the relevant user (T344886)
			// Uses the same condition as the SpecialContributions class did
			if ( $performerUser && !IPUtils::isValidRange( $performer ) &&
				( $this->userNameUtils->isIP( $performer ) || $performerUser->isRegistered() )
			) {
				$this->getSkin()->setRelevantUser( $performerUser );
			}
		}

		# Show form options
		$succeed = $loglist->showOptions(
			$opts->getValue( 'type' ),
			$opts->getValue( 'year' ),
			$opts->getValue( 'month' ),
			$opts->getValue( 'day' )
		);
		if ( !$succeed ) {
			return;
		}

		$this->getOutput()->setPageTitleMsg(
			( new LogPage( $opts->getValue( 'type' ) ) )->getName()
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

	private function getActionButtons( string $formcontents ): string {
		$canRevDelete = $this->getAuthority()
			->isAllowedAll( 'deletedhistory', 'deletelogentry' );
		$showTagEditUI = ChangeTags::showTagEditingUI( $this->getAuthority() );
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
		$s .= Html::hidden( 'type', 'logging' ) . "\n";

		$buttons = '';
		if ( $canRevDelete ) {
			$buttons .= Html::element(
				'button',
				[
					'type' => 'submit',
					'name' => 'title',
					'value' => SpecialPage::getTitleFor( 'Revisiondelete' )->getPrefixedDBkey(),
					'class' => "deleterevision-log-submit mw-log-deleterevision-button mw-ui-button"
				],
				$this->msg( 'showhideselectedlogentries' )->text()
			) . "\n";
		}
		if ( $showTagEditUI ) {
			$buttons .= Html::element(
				'button',
				[
					'type' => 'submit',
					'name' => 'title',
					'value' => SpecialPage::getTitleFor( 'EditTags' )->getPrefixedDBkey(),
					'class' => "editchangetags-log-submit mw-log-editchangetags-button mw-ui-button"
				],
				$this->msg( 'log-edit-tags' )->text()
			) . "\n";
		}

		$buttons .= ( new ListToggle( $this->getOutput() ) )->getHTML();

		$s .= $buttons . $formcontents . $buttons;
		$s .= Html::closeElement( 'form' );

		return $s;
	}

	protected function getGroupName() {
		return 'changes';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialLog::class, 'SpecialLog' );
