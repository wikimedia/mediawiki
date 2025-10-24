<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use MediaWiki\User\UserGroupAssignmentService;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\Xml\XmlSelect;
use OOUI\FieldLayout;
use OOUI\FieldsetLayout;
use OOUI\HtmlSnippet;
use OOUI\LabelWidget;
use OOUI\PanelLayout;
use Status;

/**
 * A base class for special pages that allow to view and edit user groups.
 *
 * @stable to extend
 * @ingroup SpecialPage
 */
abstract class UserGroupsSpecialPage extends SpecialPage {

	/** @var string The bare name of the target user, e.g. "Foo" in a form suitable for {{GENDER:}} */
	protected string $targetBareName = '';

	/**
	 * @var string The display name of the target user, e.g. "Foo", "Foo@wiki". It will also be used as a value
	 *   for the hidden target field in the edit groups form.
	 */
	protected string $targetDisplayName = '';

	/** @var list<string> An array of all explicit groups in the system */
	protected array $explicitGroups = [];

	/**
	 * @var array<string,UserGroupMembership> An array of group name => UserGroupMembership objects that the target
	 *   user belongs to
	 */
	protected array $groupMemberships = [];

	/** @var array<string> An array of group names that can be added by the current user to the current target */
	protected array $addableGroups = [];

	/** @var array<string> An array of group names that can be removed by the current user to the current target */
	protected array $removableGroups = [];

	/** @var array<string,list<Message|string>> An array of group name => list of annotations to show below the group */
	protected array $groupAnnotations = [];

	/** @var bool Whether the "Watch the user page" checkbox should be available on the page */
	protected bool $enableWatchUser = true;

	/** @var string Name of session flag that's saved when the user groups are successfully saved */
	private const SAVE_SUCCESS_FLAG = 'specialUserrightsSaveSuccess';

	/** @var string Name of the form field, which stores the conflict check key */
	private const CONFLICT_CHECK_FIELD = 'conflictcheck-originalgroups';

	/**
	 * Sets the name of the target user. If this page uses a special notation for the username (e.g. "Foo@wiki"),
	 * which is different from actual bare username, this additional form should be passed as the second parameter.
	 * The second form will be used in the interface messages and in the hidden target field in the groups form.
	 * @param string $bareName A form of the name that can be used with {{GENDER:}}
	 * @param string|null $displayName A form of the name that will be used as a value of the target field
	 *   in the edit groups form. If null, $targetName is used.
	 */
	protected function setTargetName( string $bareName, ?string $displayName = null ): void {
		$this->targetBareName = $bareName;
		$this->targetDisplayName = $displayName ?? $bareName;
	}

	/**
	 * Sets the groups that can be added and removed by the current user to/from the target user.
	 * If there are any restricted groups, adds appropriate annotations for them. This method accepts
	 * the same input structure as returned by {@see UserGroupAssignmentService::getChangeableGroups()}.
	 * @param array{add:list<string>,remove:list<string>,restricted:array<string,array>} $changeableGroups
	 */
	protected function setChangeableGroups( array $changeableGroups ): void {
		$this->addableGroups = $changeableGroups['add'];
		$this->removableGroups = $changeableGroups['remove'];
		foreach ( $changeableGroups['restricted'] as $group => $details ) {
			if ( !$details['condition-met'] ) {
				$this->addGroupAnnotation( $group, $details['message'] );
			}
		}
	}

	/**
	 * Adds ResourceLoader modules that are used by this page.
	 */
	protected function addModules(): void {
		$out = $this->getOutput();
		$out->addModules( [ 'mediawiki.special.userrights' ] );
		$out->addModuleStyles( [ 'mediawiki.special', 'mediawiki.codex.messagebox.styles' ] );
	}

	/**
	 * If the session contains a flag that the user rights were successfully saved,
	 * shows a success message and removes the flag from the session.
	 */
	protected function showMessageOnSuccess(): void {
		$session = $this->getRequest()->getSession();
		if ( $session->get( self::SAVE_SUCCESS_FLAG ) ) {
			// Remove session data for the success message
			$session->remove( self::SAVE_SUCCESS_FLAG );

			$out = $this->getOutput();
			$out->addModuleStyles( 'mediawiki.notification.convertmessagebox.styles' );
			$out->addHTML(
				Html::successBox(
					Html::element(
						'p',
						[],
						$this->msg( 'savedrights', $this->targetDisplayName )->text()
					),
					'mw-notify-success'
				)
			);
		}
	}

	/**
	 * Sets a flag in the session that the user rights were successfully saved.
	 * Next requests can call {@see showMessageOnSuccess()} to show a success message.
	 */
	protected function setSuccessFlag(): void {
		$session = $this->getRequest()->getSession();
		$session->set( self::SAVE_SUCCESS_FLAG, 1 );
	}

	/**
	 * Builds the user groups form, either in view or edit mode.
	 * @return string The HTML of the form
	 */
	protected function buildGroupsForm(): string {
		$groups = $this->prepareAvailableGroups();

		$canChangeAny = array_any(
			$groups,
			static fn ( $group ) => $group['canAdd'] || $group['canRemove']
		);

		return $canChangeAny ?
			$this->buildEditGroupsFormContent( $groups ) :
			$this->buildViewGroupsFormContent();
	}

	private function buildFormHeader( string $messageKey ): string {
		return $this->msg( $messageKey, $this->targetBareName )->text();
	}

	private function buildFormDescription( string $messageKey ): string {
		return $this->msg( $messageKey )
			->params( wfEscapeWikiText( $this->targetDisplayName ) )
			->rawParams( $this->getTargetUserToolLinks() )->parse();
	}

	private function buildFormGroupsLists(): array {
		return array_map( static function ( $field ) {
			return $field['label'] . ' ' . $field['list'];
		}, $this->getCurrentUserGroupsFields() );
	}

	/**
	 * Allow subclasses to add extra information. This is displayed on the edit and
	 * view panels, after the lists of the target user's groups.
	 *
	 * @return ?string Parsed HTML
	 */
	protected function buildFormExtraInfo(): ?string {
		return null;
	}

	/**
	 * Builds the user groups form in view-only mode.
	 * @return string The HTML of the form
	 */
	private function buildViewGroupsFormContent(): string {
		$panelLabel = $this->buildFormHeader( 'userrights-viewusergroup' );

		$panelItems = array_filter( [
			$this->buildFormDescription( 'viewinguserrights' ),
			...$this->buildFormGroupsLists(),
			$this->buildFormExtraInfo(),
		] );
		$panelItems = array_map( static function ( $label ) {
			return new FieldLayout(
				new LabelWidget( [
					'label' => new HtmlSnippet( $label )
				] )
			);
		}, $panelItems );

		return new PanelLayout( [
			'expanded' => false,
			'padded' => true,
			'framed' => true,
			'content' => new FieldsetLayout( [
				'label' => $panelLabel,
				'items' => $panelItems,
			] )
		] );
	}

	/**
	 * Builds the user groups form in edit mode.
	 * @param array $groups Prepared list of groups to show, {@see prepareAvailableGroups()}
	 * @return string The HTML of the form
	 */
	private function buildEditGroupsFormContent( array $groups ): string {
		$panelLabel = $this->buildFormHeader( 'userrights-editusergroup' );

		$panelItems = array_filter( [
			$this->buildFormDescription( 'editinguser' ),
			$this->msg( 'userrights-groups-help', $this->targetBareName )->parse(),
			...$this->buildFormGroupsLists(),
			$this->buildFormExtraInfo(),
		] );
		$panelItems = array_map( static function ( $label ) {
			return new FieldLayout(
				new LabelWidget( [
					'label' => new HtmlSnippet( $label )
				] )
			);
		}, $panelItems );

		$formContent =
			Html::hidden( 'user', $this->targetDisplayName ) .
			Html::hidden( 'wpEditToken', $this->getUser()->getEditToken( $this->targetDisplayName ) ) .
			Html::hidden(
				self::CONFLICT_CHECK_FIELD,
				$this->makeConflictCheckKey()
			) .
			Html::openElement( 'fieldset', [ 'class' => 'mw-userrights-edit-fieldset' ] );

		$memberships = $this->groupMemberships;
		$columns = [
			'unchangeable' => [],
			'changeable' => [],
		];
		foreach ( $groups as $group => $groupData ) {
			$isMember = array_key_exists( $group, $memberships );
			$expiry = null;
			if ( $isMember ) {
				$expiry = $memberships[$group]->getExpiry();
			}

			[ $checkbox, $isChangeable ] = $this->makeCheckbox(
				$groupData,
				$isMember,
				$expiry,
				$this->targetBareName
			);

			if ( $isChangeable ) {
				$columns['changeable'][] = $checkbox;
			} else {
				$columns['unchangeable'][] = $checkbox;
			}
		}

		$formContent .= $this->buildColumnsView( $columns ) .
			$this->buildReasonFields() .
			Html::closeElement( 'fieldset' );

		$form = Html::rawElement(
			'form',
			[
				'method' => 'post',
				'action' => $this->getPageTitle()->getLocalURL(),
				'name' => 'editGroup',
				'id' => 'mw-userrights-form2'
			],
			$formContent
		);

		return new PanelLayout( [
			'expanded' => false,
			'padded' => true,
			'framed' => true,
			'content' => [
				new FieldsetLayout( [
					'label' => $panelLabel,
					'items' => $panelItems,
				] ),
				new PanelLayout( [
					'expanded' => false,
					'content' => new HtmlSnippet( $form ),
				] )
			],
		] );
	}

	/**
	 * Builds the bottom part of the form, with the reason and watch fields, and the submit button.
	 * @return string The HTML of the fields
	 */
	private function buildReasonFields(): string {
		$output = Html::openElement( 'table', [ 'id' => 'mw-userrights-table-outer' ] ) .
			"<tr>
				<td class='mw-label'>" .
					Html::label( $this->msg( 'userrights-reason' )->text(), 'wpReason' ) .
				"</td>
				<td class='mw-input'>" .
					Html::input( 'user-reason', $this->getRequest()->getVal( 'user-reason' ) ?? false, 'text', [
						'size' => 60,
						'id' => 'wpReason',
						// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
						// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
						// Unicode codepoints.
						'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
					] ) .
				"</td>
			</tr>
			<tr>
				<td></td>
				<td class='mw-submit'>" .
					Html::submitButton( $this->msg( 'saveusergroups', $this->targetBareName )->text(),
						[ 'name' => 'saveusergroups' ] +
						Linker::tooltipAndAccesskeyAttribs( 'userrights-set' )
					) .
				"</td>
			</tr>";
		if ( $this->enableWatchUser ) {
			$output .= "<tr>
					<td></td>
					<td class='mw-input'>" .
						Html::check( 'wpWatch', false, [ 'id' => 'wpWatch' ] ) .
						'&nbsp;' . Html::label( $this->msg( 'userrights-watchuser' )->text(), 'wpWatch' ) .
					"</td>
				</tr>";
		}
		$output .= Html::closeElement( 'table' );

		return $output;
	}

	/**
	 * Given the columns with checkboxes, builds the HTML table to display them.
	 * @param array{changeable:string[],unchangeable:string[]} $columns Array containing
	 *   lists of HTML snippets with checkboxes to go into each column
	 * @return string The HTML of the table
	 */
	private function buildColumnsView( array $columns ): string {
		$output = '<table class="mw-userrights-groups"><tr>';

		foreach ( $columns as $name => $column ) {
			if ( count( $column ) === 0 ) {
				continue;
			}

			// Messages: userrights-changeable-col, userrights-unchangeable-col
			$output .= Html::element(
				'th',
				[],
				$this->msg( 'userrights-' . $name . '-col', count( $column ) )->text()
			);
		}
		$output .= '</tr><tr>';

		foreach ( $columns as $column ) {
			if ( count( $column ) === 0 ) {
				continue;
			}

			$output .= Html::rawElement(
				'td',
				[ 'style' => 'vertical-align:top;' ],
				implode( $column )
			);
		}

		$output .= '</tr></table>';

		return $output;
	}

	/**
	 * Returns an array of all user groups that should be presented in the form, along with
	 * information whether the current user can add/remove them and any annotations.
	 * @return array<string,array{group:string,canAdd:bool,canRemove:bool,annotations:list<Message|string>}>
	 */
	private function prepareAvailableGroups(): array {
		$allGroups = $this->explicitGroups;

		// We store user groups with information whether the current user can add/remove them
		// and possibly other data that will be then used for rendering the form
		$result = [];

		foreach ( $allGroups as $group ) {
			$result[$group] = [
				'group' => $group,
				'canAdd' => $this->canAdd( $group ),
				'canRemove' => $this->canRemove( $group ),
				'annotations' => $this->getGroupAnnotations( $group ),
			];
		}

		return $result;
	}

	/**
	 * Creates an HTML code for a single item in the user groups form: a checkbox along with the expiry field
	 * (if applicable) and any annotations.
	 * @param array $groupData The group data as returned by {@see prepareAvailableGroups()}
	 * @param bool $isMember Whether the target user is currently a member of this group
	 * @param string|null $expiry The expiry time of this group for the target user, or null if it has no expiry.
	 *   Ignored if the user is not a member of this group.
	 * @param string $userName The username of the target user, used for {{GENDER:}}
	 * @return array{0:string,1:bool} The HTML of the item, and whether it is changeable (i.e. the checkbox or
	 *   expiry field is not disabled)
	 */
	private function makeCheckbox( array $groupData, bool $isMember, ?string $expiry, string $userName ): array {
		$group = $groupData['group'];
		$uiLanguage = $this->getLanguage();
		$member = $uiLanguage->getGroupMemberName( $group, $userName );

		// Users who can add the group, but not remove it, can only lengthen
		// expiries, not shorten them. So they should only see the expiry
		// dropdown if the group currently has a finite expiry
		$canOnlyLengthenExpiry = (
			$isMember && $expiry &&
			$groupData['canAdd'] && !$groupData['canRemove']
		);

		// Should the checkbox be disabled?
		$disabledCheckbox = !(
			( $isMember && $groupData['canRemove'] ) ||
			( !$isMember && $groupData['canAdd'] )
		);

		// Should the expiry elements be disabled?
		$disabledExpiry = $disabledCheckbox && !$canOnlyLengthenExpiry;

		// Do we need to point out that this action is irreversible?
		$irreversible = !$disabledCheckbox && (
				( $isMember && !$groupData['canAdd'] ) ||
				( !$isMember && !$groupData['canRemove'] ) );

		if ( $irreversible ) {
			$text = $this->msg( 'userrights-irreversible-marker', $member )->text();
		} elseif ( $disabledCheckbox && !$disabledExpiry ) {
			$text = $this->msg( 'userrights-no-shorten-expiry-marker', $member )->text();
		} else {
			$text = $member;
		}

		$checkboxHtml = Html::element( 'input', [
				'type' => 'checkbox', 'name' => "wpGroup-$group", 'value' => '1',
				'id' => "wpGroup-$group", 'checked' => $isMember,
				'class' => 'mw-userrights-groupcheckbox',
				'disabled' => $disabledCheckbox,
			] ) . '&nbsp;' . Html::label( $text, "wpGroup-$group" );

		foreach ( $groupData['annotations'] as $annotation ) {
			if ( !$annotation instanceof Message ) {
				$message = $this->msg( $annotation );
			} else {
				$message = $annotation;
			}

			$checkboxHtml .= Html::rawElement(
				'div',
				[ 'class' => 'mw-userrights-annotation' ],
				$message->parse()
			);
		}

		$uiUser = $this->getUser();

		// If the user can't modify the expiry, print the current expiry below
		// it in plain text. Otherwise, provide UI to set/change the expiry
		if ( $isMember && ( $irreversible || $disabledExpiry ) ) {
			if ( $expiry ) {
				$expiryFormatted = $uiLanguage->userTimeAndDate( $expiry, $uiUser );
				$expiryFormattedD = $uiLanguage->userDate( $expiry, $uiUser );
				$expiryFormattedT = $uiLanguage->userTime( $expiry, $uiUser );
				$expiryHtml = Html::element( 'span', [],
					$this->msg( 'userrights-expiry-current' )->params(
						$expiryFormatted, $expiryFormattedD, $expiryFormattedT )->text() );
			} else {
				$expiryHtml = Html::element( 'span', [],
					$this->msg( 'userrights-expiry-none' )->text() );
			}
			// T171345: Add a hidden form element so that other groups can still be manipulated,
			// otherwise saving errors out with an invalid expiry time for this group.
			$expiryHtml .= Html::hidden( "wpExpiry-$group",
				$expiry ? 'existing' : 'infinite' );
			$expiryHtml .= "<br />\n";
		} else {
			$expiryHtml = Html::element( 'span', [],
				$this->msg( 'userrights-expiry' )->text() );
			$expiryHtml .= Html::openElement( 'span' );

			// add a form element to set the expiry date
			$expiryFormOptions = new XmlSelect(
				"wpExpiry-$group",
				"mw-input-wpExpiry-$group", // forward compatibility with HTMLForm
				( $isMember && $expiry ) ? 'existing' : 'infinite'
			);
			if ( $disabledExpiry ) {
				$expiryFormOptions->setAttribute( 'disabled', 'disabled' );
			}

			if ( $isMember && $expiry ) {
				$timestamp = $uiLanguage->userTimeAndDate( $expiry, $uiUser );
				$d = $uiLanguage->userDate( $expiry, $uiUser );
				$t = $uiLanguage->userTime( $expiry, $uiUser );
				$existingExpiryMessage = $this->msg( 'userrights-expiry-existing',
					$timestamp, $d, $t );
				$expiryFormOptions->addOption( $existingExpiryMessage->text(), 'existing' );
			}

			$expiryFormOptions->addOption(
				$this->msg( 'userrights-expiry-none' )->text(),
				'infinite'
			);
			$expiryFormOptions->addOption(
				$this->msg( 'userrights-expiry-othertime' )->text(),
				'other'
			);

			$expiryOptionsMsg = $this->msg( 'userrights-expiry-options' )->inContentLanguage();
			$expiryOptions = $expiryOptionsMsg->isDisabled()
				? []
				: XmlSelect::parseOptionsMessage( $expiryOptionsMsg->text() );
			$expiryFormOptions->addOptions( $expiryOptions );

			// Add expiry dropdown
			$expiryHtml .= $expiryFormOptions->getHTML() . '<br />';

			// Add custom expiry field
			$expiryHtml .= Html::element( 'input', [
				'name' => "wpExpiry-$group-other", 'size' => 30, 'value' => '',
				'id' => "mw-input-wpExpiry-$group-other",
				'class' => 'mw-userrights-expiryfield',
				'disabled' => $disabledExpiry,
			] );

			// If the user group is set but the checkbox is disabled, mimic a
			// checked checkbox in the form submission
			if ( $isMember && $disabledCheckbox ) {
				$expiryHtml .= Html::hidden( "wpGroup-$group", 1 );
			}

			$expiryHtml .= Html::closeElement( 'span' );
		}

		$divAttribs = [
			'id' => "mw-userrights-nested-wpGroup-$group",
			'class' => 'mw-userrights-nested',
		];
		$checkboxHtml .= Html::rawElement( 'div', $divAttribs, $expiryHtml ) . "\n";

		$fullyDisabled = $disabledCheckbox && $disabledExpiry;
		$outHtml = $fullyDisabled
			? Html::rawElement( 'div', [ 'class' => 'mw-userrights-disabled' ], $checkboxHtml )
			: Html::rawElement( 'div', [], $checkboxHtml );

		return [ $outHtml, !$fullyDisabled ];
	}

	/**
	 * Reads the user groups set in the form. Returns them wrapped in a Status object.
	 * On success, the value is an array of group name => expiry pairs. The expiry
	 * is either a timestamp, null or 'existing' (meaning no change).
	 * On failure, the status is fatal and contains an appropriate error message.
	 *
	 * NOTE: This method doesn't check whether the current user is actually allowed
	 * to add/remove the groups. Normally, the result doesn't contain groups that
	 * the user is not supposed to change.
	 */
	protected function readGroupsForm(): Status {
		$allGroups = $this->explicitGroups;
		// New state of the user groups, read from the form (group name => expiry)
		// The expiry is either timestamp, null or 'existing' (meaning no change)
		$newGroups = [];

		foreach ( $allGroups as $group ) {
			// We'll tell it to remove all unchecked groups, and add all checked groups.
			// Later on, this gets filtered for what can actually be removed
			if ( $this->getRequest()->getCheck( "wpGroup-$group" ) ) {
				// Default expiry is infinity, may be changed below
				$newGroups[$group] = null;

				// read the expiry information from the request
				$expiryDropdown = $this->getRequest()->getVal( "wpExpiry-$group" );
				if ( $expiryDropdown === 'existing' ) {
					$newGroups[$group] = 'existing';
					continue;
				}

				if ( $expiryDropdown === 'other' ) {
					$expiryValue = $this->getRequest()->getVal( "wpExpiry-$group-other" );
				} else {
					$expiryValue = $expiryDropdown;
				}

				// validate the expiry
				$expiry = UserGroupAssignmentService::expiryToTimestamp( $expiryValue );

				if ( $expiry === false ) {
					return Status::newFatal( 'userrights-invalid-expiry', $group );
				}

				// not allowed to have things expiring in the past
				if ( $expiry && $expiry < wfTimestampNow() ) {
					return Status::newFatal( 'userrights-expiry-in-past', $group );
				}

				$newGroups[$group] = $expiry;
			}
		}

		return Status::newGood( $newGroups );
	}

	/**
	 * Compares the current and new groups and splits them into groups to add, to remove, and prepares
	 * the new expiries of the groups in 'add'. If a group has its expiry changed, but the user is already
	 * a member of it, this group will be included in 'add' (to update the expiry).
	 * @param array<string, ?string> $newGroups An array of group name => expiry pairs, as returned
	 *   by {@see readGroupsForm()}. The expiry is either a timestamp, null (meaning infinity) or
	 *   'existing' (meaning no change).
	 * @param array<string, UserGroupMembership> $existingUGMs The current group memberships of
	 *   the target user, in the same format as in {@see $groupMemberships}.
	 * @return array{0:list<string>,1:list<string>,2:array<string,?string>} Respectively: the groups
	 *   to add, to remove, and the expiries to set on the groups to add.
	 */
	protected function splitGroupsIntoAddRemove( array $newGroups, array $existingUGMs ): array {
		$involvedGroups = array_unique( array_merge( array_keys( $existingUGMs ), array_keys( $newGroups ) ) );

		$addGroups = [];
		$removeGroups = [];
		$groupExpiries = [];
		foreach ( $involvedGroups as $group ) {
			// By definition of $involvedGroups, at least one of $hasGroup and $wantsGroup is true
			$hasGroup = array_key_exists( $group, $existingUGMs );
			$wantsGroup = array_key_exists( $group, $newGroups );

			if ( $wantsGroup && $newGroups[$group] === 'existing' ) {
				// No change requested for this group
				continue;
			}

			if ( $hasGroup && !$wantsGroup ) {
				$removeGroups[] = $group;
				continue;
			}
			if ( !$hasGroup && $wantsGroup ) {
				$addGroups[] = $group;
				$groupExpiries[$group] = $newGroups[$group];
				continue;
			}

			$currentExpiry = $existingUGMs[$group]->getExpiry();
			$wantedExpiry = $newGroups[$group];
			if ( $currentExpiry !== $wantedExpiry ) {
				$addGroups[] = $group;
				$groupExpiries[$group] = $wantedExpiry;
			}
		}

		return [ $addGroups, $removeGroups, $groupExpiries ];
	}

	/**
	 * Get the message translations for displaying the types of groups memberships the user has, and the
	 * list of groups for each type.
	 *
	 * @return array<array{label:string,list:string}>
	 */
	private function getCurrentUserGroupsFields(): array {
		$userGroups = $this->sortGroupMemberships( $this->groupMemberships );
		$groupParagraphs = $this->categorizeUserGroupsForDisplay( $userGroups );

		$context = $this->getContext();
		$userName = $this->targetBareName;
		$language = $this->getLanguage();

		$fields = [];
		foreach ( $groupParagraphs as $paragraphKey => $groups ) {
			if ( count( $groups ) === 0 ) {
				continue;
			}

			$groupLinks = array_map(
				static fn ( $group ) => UserGroupMembership::getLinkHTML( $group, $context ),
				$groups
			);
			$memberLinks = array_map(
				static fn ( $group ) => UserGroupMembership::getLinkHTML( $group, $context, $userName ),
				$groups
			);

			// Some languages prefer to have group names listed and some others prefer the member names,
			// i.e. "Administrators" or "Administrator", respectively. This message acts as a switch between these.
			$displayedList = $this->msg( 'userrights-groupsmember-type' )
				->rawParams(
					$language->commaList( $groupLinks ),
					$language->commaList( $memberLinks )
				)->escaped();

			$paragraphHeader = $this->msg( $paragraphKey )
				->numParams( count( $groups ) )
				->params( $userName )
				->parse();

			$fields[] = [
				'label' => $paragraphHeader,
				'list' => $displayedList
			];
		}
		return $fields;
	}

	/**
	 * Shows a log fragment for the current target user, i.e. page "User:{$this->targetDisplayName}".
	 *
	 * @param string $logType The type of the log to show
	 * @param string $logSubType The subtype of the log to show
	 */
	protected function showLogFragment( string $logType, string $logSubType ): void {
		$logPage = new LogPage( $logType );

		$logTitle = $logPage->getName()
			// setContext allows us to test it - otherwise, English text would be used in tests
			->setContext( $this->getContext() )
			->text();

		$output = $this->getOutput();
		$output->addHTML( Html::element( 'h2', [], $logTitle ) );
		LogEventsList::showLogExtract(
			$output,
			$logSubType,
			Title::makeTitle( NS_USER, $this->targetDisplayName )
		);
	}

	/**
	 * This function is invoked when constructing the "current user groups" part of the form. It can be
	 * overridden by the implementations to split the user groups into several paragraphs or add more
	 * groups to the list, which are not expected to be editable through the form.
	 *
	 * @param array<string,UserGroupMembership> $userGroups The user groups the target belongs to, in
	 *   the same format as {@see $groupMemberships}. The groups are sorted in such a way that permanent
	 *   memberships are after temporary ones.
	 * @return array<string,list<UserGroupMembership|string>> List of groups to show, keyed by the message key to
	 *   include at the beginning of the respective paragraph. The default implementation returns a single
	 *   paragraph with all the groups, keyed by 'userrights-groupsmember'.
	 */
	protected function categorizeUserGroupsForDisplay( array $userGroups ): array {
		return [
			'userrights-groupsmember' => array_values( $userGroups ),
		];
	}

	/**
	 * Returns a string that represents the current state of the target's groups. It is used to
	 * detect attempts of concurrent modifications to the user groups.
	 * @param ?array<string,UserGroupMembership> $groupMemberships The group memberships to use
	 *   in the conflict key generation. If null, defaults to the value of {@see $groupMemberships}.
	 *   It's advised to use set this parameter to memberships fetched from the primary database when
	 *   trying to detect conflicts on handling a request to save changes to user groups.
	 */
	protected function makeConflictCheckKey( ?array $groupMemberships = null ): string {
		$groupMemberships ??= $this->groupMemberships;
		$groups = array_keys( $groupMemberships );
		// Sort, so that the keys are safe to compare
		sort( $groups );
		return implode( ',', $groups );
	}

	/**
	 * Tests if a conflict occurred when trying to save changes to user groups, by comparing
	 * the conflict check key received from the form with the expected one.
	 * @param ?array<string,UserGroupMembership> $groupMembershipsPrimary The group memberships
	 *   to use when generating the expected conflict check key. If null, defaults to the value
	 *   of {@see $groupMemberships}. It's recommended to pass memberships fetched from the primary
	 *   database, so that concurrent changes made by other requests are detected.
	 */
	protected function conflictOccured( ?array $groupMembershipsPrimary = null ): bool {
		$request = $this->getRequest();
		$receivedConflictCheck = $request->getVal( self::CONFLICT_CHECK_FIELD );
		$expectedConflictCheck = $this->makeConflictCheckKey( $groupMembershipsPrimary );

		return $receivedConflictCheck !== $expectedConflictCheck;
	}

	/**
	 * Returns an HTML snippet with links to pages like user talk, contributions etc. for the
	 * target user. It will be used in the "Changing user groups of" header.
	 */
	abstract protected function getTargetUserToolLinks(): string;

	/**
	 * Whether the current user can add the target user to the given group.
	 */
	protected function canAdd( string $group ): bool {
		return in_array( $group, $this->addableGroups );
	}

	/**
	 * Whether the current user can remove the target user from the given group.
	 */
	protected function canRemove( string $group ): bool {
		return in_array( $group, $this->removableGroups );
	}

	/**
	 * Returns an array of annotations (messages or message keys) that should be displayed
	 * below the checkbox for the given group. The default implementation returns an empty array.
	 *
	 * Annotations can signify special properties of the group, e.g. conditions required to grant this
	 * group or consequences of adding the user etc.
	 * @return list<Message|string>
	 */
	protected function getGroupAnnotations( string $group ): array {
		return $this->groupAnnotations[$group] ?? [];
	}

	/**
	 * Adds an annotation (message or message key) that should be displayed below the checkbox
	 * for the given group. The annotation will be appended to any existing annotations
	 * for this group.
	 */
	protected function addGroupAnnotation( string $group, Message|string $annotation ): void {
		$this->groupAnnotations[$group][] = $annotation;
	}

	/**
	 * Sorts the given group memberships so that the temporary memberships come first, followed
	 * by the permanent ones; within each category, sorts alphabetically by group name.
	 * @param array<string,UserGroupMembership> $memberships
	 * @return array<string,UserGroupMembership>
	 */
	private function sortGroupMemberships( array $memberships ): array {
		uasort( $memberships, static function ( $a, $b ) {
			$aPermanent = $a->getExpiry() === null;
			$bPermanent = $b->getExpiry() === null;

			if ( $aPermanent === $bPermanent ) {
				return $a->getGroup() <=> $b->getGroup();
			} else {
				return $aPermanent ? 1 : -1;
			}
		} );
		return $memberships;
	}

	/**
	 * @inheritDoc
	 * @codeCoverageIgnore Merely declarative
	 */
	public function doesWrites() {
		return true;
	}

	/**
	 * @inheritDoc
	 * @codeCoverageIgnore Merely declarative
	 */
	protected function getGroupName() {
		return 'users';
	}
}
