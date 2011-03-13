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
 * @ingroup SpecialPage
 */

/**
 * A special page for unblocking users
 *
 * @ingroup SpecialPage
 */
class SpecialUnblock extends SpecialPage {

	protected $target;
	protected $type;
	protected $block;

	public function __construct(){
		parent::__construct( 'Unblock', 'block' );
	}

	public function execute( $par ){
		global $wgUser, $wgOut, $wgRequest;

		# Check permissions
		if( !$wgUser->isAllowed( 'block' ) ) {
			$wgOut->permissionRequired( 'block' );
			return;
		}
		# Check for database lock
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		list( $this->target, $this->type ) = SpecialBlock::getTargetAndType( $par, $wgRequest );
		$this->block = Block::newFromTargetAndType( $this->target, $this->type );

		# bug 15810: blocked admins should have limited access here.  This won't allow sysops
		# to remove autoblocks on themselves, but they should have ipblock-exempt anyway
		$status = SpecialBlock::checkUnblockSelf( $this->target );
		if ( $status !== true ) {
			throw new ErrorPageError( 'badaccess', $status );
		}

		$wgOut->setPageTitle( wfMsg( 'unblockip' ) );
		$wgOut->addModules( 'mediawiki.special' );

		$form = new HTMLForm( $this->getFields() );
		$form->setTitle( $this->getTitle() );
		$form->setWrapperLegend( wfMsg( 'unblockip' ) );
		$form->setSubmitCallback( array( __CLASS__, 'processUnblock' ) );
		$form->setSubmitText( wfMsg( 'ipusubmit' ) );
		$form->addPreText( wfMsgExt( 'unblockiptext', 'parse' ) );

		if( $form->show() ){
			switch( $this->type ){
				case Block::TYPE_USER:
				case Block::TYPE_IP:
					$wgOut->addWikiMsg( 'unblocked',  $this->target );
					break;
				case Block::TYPE_RANGE:
					$wgOut->addWikiMsg( 'unblocked-range', $this->target );
					break;
				case Block::TYPE_ID:
				case Block::TYPE_AUTO:
					$wgOut->addWikiMsg( 'unblocked-id', $this->target );
					break;
			}
		}
	}

	protected function getFields(){
		$fields = array(
			'Target' => array(
				'type' => 'text',
				'label-message' => 'ipadressorusername',
				'tabindex' => '1',
				'size' => '45',
				'required' => true,
			),
			'Name' => array(
				'type' => 'info',
				'label-message' => 'ipadressorusername',
			),
			'Reason' => array(
				'type' => 'text',
				'label-message' => 'ipbreason',
			)
		);

		if( $this->block instanceof Block ){
			list( $target, $type ) = $this->block->getTargetAndType();

			# Autoblocks are logged as "autoblock #123 because the IP was recently used by
			# User:Foo, and we've just got any block, auto or not, that applies to a target
			# the user has specified.  Someone could be fishing to connect IPs to autoblocks,
			# so don't show any distinction between unblocked IPs and autoblocked IPs
			if( $type == Block::TYPE_AUTO && $this->type == Block::TYPE_IP ){
				$fields['Target']['default'] = $this->target;
				unset( $fields['Name'] );

			} else {
				$fields['Target']['default'] = $target;
				$fields['Target']['type'] = 'hidden';
				switch( $type ){
					case Block::TYPE_USER:
					case Block::TYPE_IP:
						global $wgUser;
						$skin = $wgUser->getSkin();
						$fields['Name']['default'] = $skin->link(
							$target->getUserPage(),
							$target->getName()
						);
						$fields['Name']['raw'] = true;
						break;

					case Block::TYPE_RANGE:
						$fields['Name']['default'] = $target;
						break;

					case Block::TYPE_AUTO:
						$fields['Name']['default'] = $this->block->getRedactedName();
						$fields['Name']['raw'] = true;
						# Don't expose the real target of the autoblock
						$fields['Target']['default'] = "#{$this->target}";
						break;
				}
			}
			
		} else {
			$fields['Target']['default'] = $this->target;
			unset( $fields['Name'] );
		}
		return $fields;
	}

	/**
	 * Process the form
	 * @return Array( Array(message key, parameters) ) on failure, True on success
	 */
	public static function processUnblock( array $data ){
		global $wgUser;

		$target = $data['Target'];
		$block = Block::newFromTarget( $data['Target'] );

		if( !$block instanceof Block ){
			return array( array( 'ipb_cant_unblock', $target ) );
		}

		# If the specified IP is a single address, and the block is a range block, don't
		# unblock the whole range.
		list( $target, $type ) = SpecialBlock::getTargetAndType( $target );
		if( $block->getType() == Block::TYPE_RANGE && $type == Block::TYPE_IP ) {
			 $range = $block->mAddress;
			 return array( array( 'ipb_blocked_as_range', $target, $range ) );
		}

		# If the name was hidden and the blocking user cannot hide
		# names, then don't allow any block removals...
		if( !$wgUser->isAllowed( 'hideuser' ) && $block->mHideName ) {
			return array( 'unblock-hideuser' );
		}

		# Delete block
		if ( !$block->delete() ) {
			return array( 'ipb_cant_unblock', htmlspecialchars( $block->getTarget() ) );
		}

		# Unset _deleted fields as needed
		if( $block->mHideName ) {
			RevisionDeleteUser::unsuppressUserName( $block->mAddress, $block->mUser );
		}

		# Make log entry
		$log = new LogPage( 'block' );
		$page = $block->getTarget() instanceof User
			? $block->getTarget()->getUserpage()
			: Title::makeTitle( NS_USER, $block->getTarget() );
		$log->addEntry( 'unblock', $page, $data['Reason'] );

		return true;
	}
}