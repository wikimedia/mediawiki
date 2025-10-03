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

namespace MediaWiki\SpecialPage;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Message\Message;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserGroupsSpecialPageTarget;
use MediaWiki\Xml\XmlSelect;

/**
 * A base class for special pages that allow to view and edit user groups.
 *
 * This class is not stable to extend yet, will receive a few more refactorings.
 * @ingroup SpecialPage
 */
abstract class UserGroupsSpecialPage extends SpecialPage {

	/**
	 * Builds the user groups form, either in view or edit mode.
	 * @param UserGroupsSpecialPageTarget $target The target user
	 * @return string The HTML of the form
	 */
	protected function buildGroupsForm( UserGroupsSpecialPageTarget $target ): string {
		$groups = $this->prepareAvailableGroups();

		$canChangeAny = array_any(
			$groups,
			static fn ( $group ) => $group['canAdd'] || $group['canRemove']
		);

		$formContent = $canChangeAny ?
			$this->buildEditGroupsFormContent( $target, $groups ) :
			$this->buildViewGroupsFormContent( $target );

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
		return $form;
	}

	/**
	 * Builds the user groups form in view-only mode.
	 * @param UserGroupsSpecialPageTarget $target The target user
	 * @return string The HTML of the form
	 */
	private function buildViewGroupsFormContent( UserGroupsSpecialPageTarget $target ): string {
		$formContent =
			Html::openElement( 'fieldset' ) .
			Html::element(
				'legend',
				[],
				$this->msg( 'userrights-viewusergroup', $target->userName )->text()
			) .
			$this->msg( 'viewinguserrights'	)->params(
				wfEscapeWikiText( $this->getDisplayUsername() )
			)->rawParams( $this->getTargetUserToolLinks() )->parse() .
			$this->getCurrentUserGroupsText( $target ) .
			Html::closeElement( 'fieldset' );
		return $formContent;
	}

	/**
	 * Builds the user groups form in edit mode.
	 * @param UserGroupsSpecialPageTarget $target The target user
	 * @param array $groups Prepared list of groups to show, {@see prepareAvailableGroups()}
	 * @return string The HTML of the form
	 */
	private function buildEditGroupsFormContent( UserGroupsSpecialPageTarget $target, array $groups ): string {
		$formContent =
			Html::hidden( 'user', $this->getTargetDescriptor() ) .
			Html::hidden( 'wpEditToken', $this->getUser()->getEditToken( $this->getTargetDescriptor() ) ) .
			Html::hidden(
				'conflictcheck-originalgroups',
				$this->makeConflictCheckKey( $target )
			) .
			Html::openElement( 'fieldset' ) .
			Html::element(
				'legend',
				[],
				$this->msg( 'userrights-editusergroup',	$target->userName )->text()
			) .
			$this->msg( 'editinguser' )->params(
				wfEscapeWikiText( $this->getDisplayUsername() )
			)->rawParams( $this->getTargetUserToolLinks() )->parse() .
			$this->msg( 'userrights-groups-help', $target->userName )->parse() .
			$this->getCurrentUserGroupsText( $target );

		$memberships = $this->getGroupMemberships( $target );
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
				$target->userName
			);

			if ( $isChangeable ) {
				$columns['changeable'][] = $checkbox;
			} else {
				$columns['unchangeable'][] = $checkbox;
			}
		}

		$formContent .= $this->buildColumnsView( $columns ) .
			$this->buildReasonFields( $target ) .
			Html::closeElement( 'fieldset' );
		return $formContent;
	}

	/**
	 * Builds the bottom part of the form, with the reason and watch fields, and the submit button.
	 * @param UserGroupsSpecialPageTarget $target The target user
	 * @return string The HTML of the fields
	 */
	private function buildReasonFields( UserGroupsSpecialPageTarget $target ): string {
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
					Html::submitButton( $this->msg( 'saveusergroups', $target->userName )->text(),
						[ 'name' => 'saveusergroups' ] +
						Linker::tooltipAndAccesskeyAttribs( 'userrights-set' )
					) .
				"</td>
			</tr>";
		if ( $this->supportsWatchUser( $target ) ) {
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
		$allGroups = $this->listAllExplicitGroups();

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

		if ( $this->canProcessExpiries() ) {
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
		}
		$fullyDisabled = $disabledCheckbox && $disabledExpiry;
		$outHtml = $fullyDisabled
			? Html::rawElement( 'div', [ 'class' => 'mw-userrights-disabled' ], $checkboxHtml )
			: Html::rawElement( 'div', [], $checkboxHtml );

		return [ $outHtml, !$fullyDisabled ];
	}

	/**
	 * Returns an HTML snippet that describes the current user groups the target belongs to.
	 * There are no specific requirements on the format, e.g. the implementation may choose to
	 * split them into several paragraphs etc.
	 */
	protected function getCurrentUserGroupsText( UserGroupsSpecialPageTarget $target ): string {
		$userGroups = $this->getGroupMemberships( $target );
		$userGroups = $this->sortGroupMemberships( $userGroups );

		$groupParagraphs = $this->categorizeUserGroupsForDisplay( $userGroups, $target );

		$context = $this->getContext();
		$userName = $target->userName;
		$language = $this->getLanguage();

		$output = '';
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

			$output .= Html::rawElement(
				'p',
				[],
				$paragraphHeader . ' ' . $displayedList
			);
		}
		return $output;
	}

	/**
	 * This function is invoked when constructing the "current user groups" part of the form. It can be
	 * overridden by the implementations to split the user groups into several paragraphs or add more
	 * groups to the list, which are not expected to be editable through the form.
	 *
	 * @param array<string,UserGroupMembership> $userGroups The user groups the target belongs to, as
	 *   returned by {@see getGroupMemberships()}. The groups are sorted in such a way that permanent
	 *   memberships are after temporary ones.
	 * @param UserGroupsSpecialPageTarget $target The target user
	 * @return array<string,list<UserGroupMembership>> List of groups to show, keyed by the message key to
	 *   include at the beginning of the respective paragraph. The default implementation returns a single
	 *   paragraph with all the groups, keyed by 'userrights-groupsmember'.
	 */
	protected function categorizeUserGroupsForDisplay(
		array $userGroups,
		UserGroupsSpecialPageTarget $target
	): array {
		return [
			'userrights-groupsmember' => array_values( $userGroups ),
		];
	}

	/**
	 * Returns a string that represents the current state of the target's groups. It is used to
	 * detect attempts of concurrent modifications to the user groups.
	 */
	abstract protected function makeConflictCheckKey( UserGroupsSpecialPageTarget $target ): string;

	/**
	 * Returns the descriptor of the current target, e.g. "Foo", "Foo@wiki" or "#123". This will
	 * not be presented to the user, apart from using it as a value of the target field.
	 */
	abstract protected function getTargetDescriptor(): string;

	/**
	 * Returns the target username in a format suitable for displaying. It will be used in the
	 * "Changing user groups of" header.
	 *
	 * TODO: Should be further refactored and joined with getTargetUserToolLinks()
	 */
	abstract protected function getDisplayUsername(): string;

	/**
	 * Returns an HTML snippet with links to pages like user talk, contributions etc. for the
	 * target user. It will be used in the "Changing user groups of" header.
	 *
	 * TODO: Should be further refactored and joined with getDisplayUsername()
	 */
	abstract protected function getTargetUserToolLinks(): string;

	/**
	 * Returns a list of all groups that should be presented in the form.
	 * @return list<string>
	 */
	abstract protected function listAllExplicitGroups(): array;

	/**
	 * Returns the groups the target user currently belongs to, keyed by the group name.
	 * @return array<string,UserGroupMembership>
	 */
	abstract protected function getGroupMemberships( UserGroupsSpecialPageTarget $target ): array;

	/**
	 * Whether the current user can add the target user to the given group.
	 */
	abstract protected function canAdd( string $group ): bool;

	/**
	 * Whether the current user can remove the target user from the given group.
	 */
	abstract protected function canRemove( string $group ): bool;

	/**
	 * Returns an array of annotations (messages or message keys) that should be displayed
	 * below the checkbox for the given group. The default implementation returns an empty array.
	 *
	 * Annotations can signify special properties of the group, e.g. conditions required to grant this
	 * group or consequences of adding the user etc.
	 * @return list<Message|string>
	 */
	protected function getGroupAnnotations( string $group ): array {
		return [];
	}

	/**
	 * Returns true if this user rights form can set and change user group expiries.
	 * Subclasses may wish to override this to return false.
	 *
	 * TODO: Delete?
	 * @return bool
	 */
	protected function canProcessExpiries() {
		return true;
	}

	/**
	 * Returns whether the "Watch user page" checkbox should be shown.
	 */
	protected function supportsWatchUser( UserGroupsSpecialPageTarget $target ): bool {
		return true;
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
}
