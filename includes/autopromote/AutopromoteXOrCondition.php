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
 * A XOR-Condition for autopromote
 * Implements: XOR (exactly one cond passes)
 */
class AutopromoteXOrCondition extends AutopromoteConditionBase {

	public function getName() {
		return 'xor';
	}

	public function evaluate( User $user ) {
		$cond = $this->getParameter();
		if ( count( $cond ) > 2 ) {
			wfWarn( __METHOD__ . ' given XOR ("^") condition on three or more conditions.' .
				' Check your $wgAutopromote and $wgAutopromoteOnce settings.' );
		}
		$cond1Obj = AutopromoteConditionBase::newFromArray( $cond[0] );
		$cond2Obj = AutopromoteConditionBase::newFromArray( $cond[1] );
		return $cond1Obj->evaluate( $user )
			xor $cond2Obj->evaluate( $user );
	}

	public function getDescriptionParamter( IContextSource $context, User $user ) {
		$cond = $this->getParameter();
		$params = [];
		foreach ( $cond as $subcond ) {
			$condObj = AutopromoteConditionBase::newFromArray( $subcond );
			$params[] = $condObj->getDescription( $context, $user );
		}

		return [
			Message::numParam( count( $params ) ),
			AutopromoteConditionBase::conditionToList( $params ),
		];
	}
}
