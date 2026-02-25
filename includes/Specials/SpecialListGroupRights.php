<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\RestrictedUserGroupConfigReader;
use MediaWiki\User\User;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserRequirementsConditionChecker;

/**
 * List all defined user groups and the associated rights.
 *
 * See also @ref $wgGroupPermissions.
 *
 * @ingroup SpecialPage
 * @author Petr Kadlec <mormegil@centrum.cz>
 */
class SpecialListGroupRights extends SpecialPage {

	public const RESTRICTED_GROUPS_SECTION_ID = 'restricted_groups';
	private const RESTRICTED_GROUPS_ID_PREFIX = 'group_restrictions-';

	private readonly ILanguageConverter $languageConverter;

	public function __construct(
		private readonly NamespaceInfo $nsInfo,
		private readonly UserGroupManager $userGroupManager,
		LanguageConverterFactory $languageConverterFactory,
		private readonly GroupPermissionsLookup $groupPermissionsLookup,
		private readonly RestrictedUserGroupConfigReader $restrictedUserGroupConfigReader,
	) {
		parent::__construct( 'Listgrouprights' );
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
	}

	/**
	 * Show the special page
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );
		$this->addHelpLink( 'Help:User_rights_and_groups' );

		$out->wrapWikiMsg( "<div class=\"mw-listgrouprights-key\">\n$1\n</div>", 'listgrouprights-key' );

		$out->addHTML(
			Html::openElement( 'table', [ 'class' => [ 'wikitable', 'mw-listgrouprights-table' ] ] ) .
				'<tr>' .
				Html::element( 'th', [], $this->msg( 'listgrouprights-group' )->text() ) .
				Html::element( 'th', [], $this->msg( 'listgrouprights-rights' )->text() ) .
				'</tr>'
		);

		$config = $this->getConfig();
		$addGroups = $config->get( MainConfigNames::AddGroups );
		$removeGroups = $config->get( MainConfigNames::RemoveGroups );
		$groupsAddToSelf = $config->get( MainConfigNames::GroupsAddToSelf );
		$groupsRemoveFromSelf = $config->get( MainConfigNames::GroupsRemoveFromSelf );
		$allGroups = array_merge(
			$this->userGroupManager->listAllGroups(),
			$this->userGroupManager->listAllImplicitGroups()
		);
		asort( $allGroups );

		$linkRenderer = $this->getLinkRenderer();
		$lang = $this->getLanguage();
		$restrictedGroups = $this->restrictedUserGroupConfigReader->getConfig();

		foreach ( $allGroups as $group ) {
			$permissions = $this->groupPermissionsLookup->getGrantedPermissions( $group );
			$groupname = ( $group == '*' ) // Replace * with a more descriptive groupname
				? 'all'
				: $group;

			$groupnameLocalized = $lang->getGroupName( $groupname );

			$grouppageLocalizedTitle = UserGroupMembership::getGroupPage( $groupname )
				?: Title::makeTitleSafe( NS_PROJECT, $groupname );

			if ( $group == '*' || !$grouppageLocalizedTitle ) {
				// Do not make a link for the generic * group or group with invalid group page
				$grouppage = htmlspecialchars( $groupnameLocalized );
			} else {
				$grouppage = $linkRenderer->makeLink(
					$grouppageLocalizedTitle,
					$groupnameLocalized
				);
			}

			$groupWithParentheses = $this->msg( 'parentheses' )->rawParams( $group )->escaped();
			$groupname = "<br /><code>$groupWithParentheses</code>";

			if ( $group === 'user' ) {
				// Link to Special:listusers for implicit group 'user'
				$grouplink = '<br />' . $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->text()
				);
			} elseif ( !in_array( $group, $config->get( MainConfigNames::ImplicitGroups ) ) ) {
				$grouplink = '<br />' . $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->text(),
					[],
					[ 'group' => $group ]
				);
			} else {
				// No link to Special:listusers for other implicit groups as they are unlistable
				$grouplink = '';
			}

			$restrictionsLink = '';
			if ( array_key_exists( $group, $restrictedGroups ) && $restrictedGroups[$group]->hasAnyConditions() ) {
				$restrictionsSection = Sanitizer::escapeIdForAttribute( self::RESTRICTED_GROUPS_ID_PREFIX . $group );
				$restrictionsLink = Html::rawElement( 'p', [],
					$this->msg( 'listgrouprights-restricted' )
						->params( '#' . $restrictionsSection )
						->parse()
				);
			}

			$revoke = $this->groupPermissionsLookup->getRevokedPermissions( $group );
			$addgroups = $addGroups[$group] ?? [];
			$removegroups = $removeGroups[$group] ?? [];
			$addgroupsSelf = $groupsAddToSelf[$group] ?? [];
			$removegroupsSelf = $groupsRemoveFromSelf[$group] ?? [];

			$id = $group == '*' ? false : Sanitizer::escapeIdForAttribute( $group );
			$out->addHTML( Html::rawElement( 'tr', [ 'id' => $id ], "
				<td>$grouppage$groupname$grouplink$restrictionsLink</td>
					<td>" .
					$this->formatPermissions( $permissions, $revoke, $addgroups, $removegroups,
						$addgroupsSelf, $removegroupsSelf ) .
					'</td>
				'
			) );
		}
		$out->addHTML( Html::closeElement( 'table' ) );
		$this->outputRestrictedGroupsConfig();
		$this->outputNamespaceProtectionInfo();
	}

	private function outputNamespaceProtectionInfo() {
		$out = $this->getOutput();
		$namespaceProtection = $this->getConfig()->get( MainConfigNames::NamespaceProtection );

		if ( count( $namespaceProtection ) == 0 ) {
			return;
		}

		$header = $this->msg( 'listgrouprights-namespaceprotection-header' )->text();
		$out->addHTML(
			Html::element( 'h2', [
				'id' => Sanitizer::escapeIdForAttribute( $header )
			], $header ) .
			Html::openElement( 'table', [ 'class' => 'wikitable' ] ) .
			Html::element(
				'th',
				[],
				$this->msg( 'listgrouprights-namespaceprotection-namespace' )->text()
			) .
			Html::element(
				'th',
				[],
				$this->msg( 'listgrouprights-namespaceprotection-restrictedto' )->text()
			)
		);
		$linkRenderer = $this->getLinkRenderer();
		ksort( $namespaceProtection );
		$validNamespaces = $this->nsInfo->getValidNamespaces();
		foreach ( $namespaceProtection as $namespace => $rights ) {
			if ( !in_array( $namespace, $validNamespaces ) ) {
				continue;
			}

			if ( $namespace == NS_MAIN ) {
				$namespaceText = $this->msg( 'blanknamespace' )->text();
			} else {
				$namespaceText = $this->languageConverter->convertNamespace( $namespace );
			}

			$out->addHTML(
				Html::openElement( 'tr' ) .
				Html::rawElement(
					'td',
					[],
					$linkRenderer->makeLink(
						SpecialPage::getTitleFor( 'Allpages' ),
						$namespaceText,
						[],
						[ 'namespace' => $namespace ]
					)
				) .
				Html::openElement( 'td' ) . Html::openElement( 'ul' )
			);

			if ( !is_array( $rights ) ) {
				$rights = [ $rights ];
			}

			foreach ( $rights as $right ) {
				$out->addHTML( Html::rawElement( 'li', [],
					$this->msg( 'listgrouprights-right-display' )
						->params( User::getRightDescription( $right ) )
						->rawParams( Html::element(
							'span',
							[ 'class' => 'mw-listgrouprights-right-name' ],
							$right
						) )->parse()
				) );
			}

			$out->addHTML(
				Html::closeElement( 'ul' ) .
				Html::closeElement( 'td' ) .
				Html::closeElement( 'tr' )
			);
		}
		$out->addHTML( Html::closeElement( 'table' ) );
	}

	private function outputRestrictedGroupsConfig() {
		$out = $this->getOutput();
		$restrictedGroups = $this->restrictedUserGroupConfigReader->getConfig();

		if ( !$restrictedGroups ) {
			return;
		}

		$header = $this->msg( 'listgrouprights-restrictedgroups-header' )->text();
		$out->addHTML(
			Html::element( 'h2', [
				'id' => Sanitizer::escapeIdForAttribute( self::RESTRICTED_GROUPS_SECTION_ID )
			], $header ) .
			Html::openElement( 'table', [ 'class' => 'wikitable mw-listgrouprights-table' ] ) .
			Html::element(
				'th',
				[],
				$this->msg( 'listgrouprights-group' )->text()
			) .
			Html::element(
				'th',
				[],
				$this->msg( 'listgrouprights-restrictedgroups-config' )->text()
			)
		);
		ksort( $restrictedGroups );

		$lang = $this->getLanguage();
		$linkRenderer = $this->getLinkRenderer();
		$allGroups = array_merge(
			$this->userGroupManager->listAllGroups(),
			$this->userGroupManager->listAllImplicitGroups()
		);
		foreach ( $restrictedGroups as $group => $groupConfig ) {
			if ( !$groupConfig->hasAnyConditions() || !in_array( $group, $allGroups ) ) {
				continue;
			}

			$out->addHTML(
				Html::openElement(
					'tr',
					[ 'id' => Sanitizer::escapeIdForAttribute( self::RESTRICTED_GROUPS_ID_PREFIX . $group ) ]
				) .
				Html::rawElement(
					'td',
					[],
					$linkRenderer->makeKnownLink(
						new TitleValue( NS_SPECIAL, $this->getLocalName(), $group ),
						$lang->getGroupName( $group )
					)
				) .
				Html::openElement( 'td' )
			);

			$conditionsParts = [];
			$memberConditions = $groupConfig->getMemberConditions();
			if ( $memberConditions ) {
				$memberHtml = $this->msg( 'listgrouprights-restrictedgroups-memberconditions' )->parse();
				$memberHtml .= Html::rawElement( 'ul', [],
					Html::rawElement( 'li', [], $this->formatCondition( $memberConditions ) )
				);
				$conditionsParts[] = $memberHtml;
			}
			$updaterConditions = $groupConfig->getUpdaterConditions();
			if ( $updaterConditions ) {
				$updaterHtml = $this->msg( 'listgrouprights-restrictedgroups-updaterconditions' )->parse();
				$updaterHtml .= Html::rawElement( 'ul', [],
					Html::rawElement( 'li', [], $this->formatCondition( $updaterConditions ) )
				);
				$conditionsParts[] = $updaterHtml;
			}
			if ( $groupConfig->canBeIgnored() ) {
				$conditionsParts[] = $this->msg( 'listgrouprights-restrictedgroups-bypassable' )
					->params( User::getRightDescription( 'ignore-restricted-groups' ) )
					->rawParams( Html::element( 'code', [], '(ignore-restricted-groups)' ) )
					->parse();
			}
			$out->addHTML( implode( '', $conditionsParts ) );

			$out->addHTML(
				Html::closeElement( 'td' ) .
				Html::closeElement( 'tr' )
			);
		}
		$out->addHTML( Html::closeElement( 'table' ) );
	}

	/**
	 * Create a user-readable list of permissions from the given array.
	 *
	 * @param string[] $permissions Array of granted permissions
	 * @param string[] $revoke Array of revoked permissions
	 * @param array $add Array of groups this group is allowed to add or true
	 * @param array $remove Array of groups this group is allowed to remove or true
	 * @param array $addSelf Array of groups this group is allowed to add to self or true
	 * @param array $removeSelf Array of group this group is allowed to remove from self or true
	 * @return string HTML list of all granted permissions
	 */
	private function formatPermissions( $permissions, $revoke, $add, $remove, $addSelf, $removeSelf ) {
		$r = [];
		foreach ( $permissions as $permission ) {
			// show as granted only if it isn't revoked to prevent duplicate display of permissions
			if ( !isset( $revoke[$permission] ) || !$revoke[$permission] ) {
				$r[] = $this->msg( 'listgrouprights-right-display' )
					->params( User::getRightDescription( $permission ) )
					->rawParams( Html::element(
						'span',
						[ 'class' => 'mw-listgrouprights-right-name' ],
						$permission
					) )->parse();
			}
		}
		foreach ( $revoke as $permission ) {
			$r[] = $this->msg( 'listgrouprights-right-revoked' )
				->params( User::getRightDescription( $permission ) )
				->rawParams( Html::element(
					'span',
					[ 'class' => 'mw-listgrouprights-right-name' ],
					$permission
				) )->parse();
		}

		sort( $r );

		$lang = $this->getLanguage();
		$allGroups = $this->userGroupManager->listAllGroups();

		$changeGroups = [
			'addgroup' => $add,
			'removegroup' => $remove,
			'addgroup-self' => $addSelf,
			'removegroup-self' => $removeSelf
		];

		foreach ( $changeGroups as $messageKey => $changeGroup ) {
			// @phan-suppress-next-line PhanTypeComparisonFromArray
			if ( $changeGroup === true ) {
				// For grep: listgrouprights-addgroup-all, listgrouprights-removegroup-all,
				// listgrouprights-addgroup-self-all, listgrouprights-removegroup-self-all
				$r[] = $this->msg( 'listgrouprights-' . $messageKey . '-all' )->escaped();
			} elseif ( is_array( $changeGroup ) ) {
				$changeGroup = array_intersect( array_values( array_unique( $changeGroup ) ), $allGroups );
				if ( count( $changeGroup ) ) {
					$groupLinks = [];
					foreach ( $changeGroup as $group ) {
						$groupLinks[] = UserGroupMembership::getLinkWiki( $group, $this->getContext() );
					}
					// For grep: listgrouprights-addgroup, listgrouprights-removegroup,
					// listgrouprights-addgroup-self, listgrouprights-removegroup-self
					$r[] = $this->msg( 'listgrouprights-' . $messageKey,
						$lang->listToText( $groupLinks ), count( $changeGroup ) )->parse();
				}
			}
		}

		if ( !$r ) {
			return '';
		} else {
			return '<ul><li>' . implode( "</li>\n<li>", $r ) . '</li></ul>';
		}
	}

	/**
	 * Create a user-readable tree describing the given condition
	 * and format it as a HTML list (potentially nested).
	 * @param mixed $condition
	 */
	private function formatCondition( $condition ): string {
		if ( is_array( $condition ) && count( $condition ) > 0 ) {
			$condName = array_shift( $condition );
			if ( in_array( $condName, UserRequirementsConditionChecker::VALID_OPS ) ) {
				$listItems = '';
				foreach ( $condition as $subcond ) {
					$listItems .= Html::rawElement( 'li', [], $this->formatCondition( $subcond ) );
				}
				$htmlList = Html::rawElement( 'ul', [], $listItems );
				$condName = match ( $condName ) {
					'&' => 'listgrouprights-restrictedgroups-op-and',
					'|' => 'listgrouprights-restrictedgroups-op-or',
					'^' => 'listgrouprights-restrictedgroups-op-xor',
					// Even though '!' is usually understood as 'NOT', in fact it's 'NAND' as it can accept
					// multiple arguments
					'!' => 'listgrouprights-restrictedgroups-op-nand',
				};
				return $this->msg( $condName )
					->rawParams( $htmlList )
					->parse();
			} else {
				return $this->formatAtomicCondition( $condName, $condition );
			}
		} elseif ( is_array( $condition ) ) {
			return '';
		} else {
			return $this->formatAtomicCondition( $condition, [] );
		}
	}

	/**
	 * Prepares a message for atomic condition and its arguments.
	 * Atomic conditions are conditions that do not contain any other conditions.
	 * @param mixed $condName
	 * @param array $args
	 */
	private function formatAtomicCondition( $condName, array $args ): string {
		$msgKey = match ( $condName ) {
			APCOND_EDITCOUNT => 'listgrouprights-restrictedgroups-cond-editcount',
			APCOND_AGE => 'listgrouprights-restrictedgroups-cond-age',
			APCOND_EMAILCONFIRMED => 'listgrouprights-restrictedgroups-cond-emailconfirmed',
			APCOND_INGROUPS => 'listgrouprights-restrictedgroups-cond-ingroups',
			APCOND_ISIP => 'listgrouprights-restrictedgroups-cond-isip',
			APCOND_IPINRANGE => 'listgrouprights-restrictedgroups-cond-ipinrange',
			APCOND_AGE_FROM_EDIT => 'listgrouprights-restrictedgroups-cond-age-from-edit',
			APCOND_BLOCKED => 'listgrouprights-restrictedgroups-cond-blocked',
			APCOND_ISBOT => 'listgrouprights-restrictedgroups-cond-isbot',
			default => 'listgrouprights-restrictedgroups-cond-' . $condName,
		};
		$msg = $this->msg( $msgKey );

		if ( $condName === APCOND_AGE || $condName === APCOND_AGE_FROM_EDIT ) {
			$minAge = $args[0] ?? $this->getConfig()->get( MainConfigNames::AutoConfirmAge );
			$msg->durationParams( $minAge );
		} elseif ( $condName === APCOND_INGROUPS ) {
			$groupNames = [];
			foreach ( $args as $group ) {
				$groupNames[] = $this->getLanguage()->getGroupName( $group );
			}
			$msg->params( count( $args ), $this->getLanguage()->listToText( $groupNames ) );
		} elseif ( $condName === APCOND_EDITCOUNT ) {
			$minEdits = $args[0] ?? $this->getConfig()->get( MainConfigNames::AutoConfirmCount );
			$msg->numParams( $minEdits );
		} else {
			$msg->params( ...$args );
		}

		return $msg->parse();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialListGroupRights::class, 'SpecialListGroupRights' );
