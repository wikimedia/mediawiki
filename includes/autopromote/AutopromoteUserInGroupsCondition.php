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
 * @since 1.28
 */

/**
 * The User-In-Groups-Condition for autopromote
 */
class AutopromoteUserInGroupsCondition extends AutopromoteConditionBase {

	public function getName() {
		return 'ingroups';
	}

	public function evaluate( User $user ) {
		$groups = $this->getParameter();
		return count( $this->getGroupIntersect( $groups, $user ) ) == count( $groups );
	}

	public function getDescriptionParamter( IContextSource $context, User $user ) {
		$groups = $this->getParameter();
		$groupIntersect = $this->getGroupIntersect( $groups, $user );
		$language = $context->getLanguage();
		return [
			$language->formatNum( count( $groupIntersect ) ),
			$language->commaList( $this->buildGroupList( $groupIntersect, $user ) ),
			$language->formatNum( count( $groups ) ),
			$language->commaList( $this->buildGroupList( $groups, $user ) ),
		];
	}

	private function getGroupIntersect( array $groups, User $user ) {
		return array_intersect( $groups, $user->getGroups() );
	}

	private function buildGroupList( array $groups, User $user ) {
		$list = [];
		$username = $user->getName();
		foreach ( $groups as $group ) {
			$list[] = User::getGroupMember( $group, $username );
		}
		return $list;
	}
}
