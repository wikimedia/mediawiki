<?php

/**
 * Represents a Message/MessageValue parameter user group membership to be used with ParamType::OBJECT.
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
 */

namespace MediaWiki\Message;

use MediaWiki\User\UserIdentity;
use Stringable;

/**
 * @since 1.38
 */
class UserGroupMembershipParam implements Stringable {
	/** @var string */
	private $group;

	/** @var UserIdentity */
	private $member;

	public function __construct( string $group, UserIdentity $member ) {
		$this->group = $group;
		$this->member = $member;
	}

	public function getGroup(): string {
		return $this->group;
	}

	public function getMember(): UserIdentity {
		return $this->member;
	}

	public function __toString() {
		return $this->group . ':' . $this->member->getName();
	}
}
