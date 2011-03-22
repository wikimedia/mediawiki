<?php
/**
 * Implements Special:Block
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
 * A special page that allows users with 'block' right to block users from
 * editing pages and other actions
 *
 * @ingroup SpecialPage
 */
class SpecialBlock extends SpecialPage {

	/** The maximum number of edits a user can have and still be hidden
	 * TODO: config setting? */
	const HIDEUSER_CONTRIBLIMIT = 1000;

	/** @var User user to be blocked, as passed either by parameter (url?wpTarget=Foo)
	 * or as subpage (Special:Block/Foo) */
	protected $target;

	/// @var Block::TYPE_ constant
	protected $type;

	/// @var Bool
	protected $alreadyBlocked;

	public function __construct() {
		parent::__construct( 'Block', 'block' );
	}

	public function execute( $par ) {
		global $wgUser, $wgOut, $wgRequest;

		# Can't block when the database is locked
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		# Permission check
		if( !$this->userCanExecute( $wgUser ) ) {
			$wgOut->permissionRequired( 'block' );
			return;
		}

		# Extract variables from the request.  Try not to get into a situation where we
		# need to extract *every* variable from the form just for processing here, but
		# there are legitimate uses for some variables
		list( $this->target, $this->type ) = self::getTargetAndType( $par, $wgRequest );
		if ( $this->target instanceof User ) {
			# Set the 'relevant user' in the skin, so it displays links like Contributions,
			# User logs, UserRights, etc.
			$wgUser->getSkin()->setRelevantUser( $this->target );
		}

		# bug 15810: blocked admins should have limited access here
		$status = self::checkUnblockSelf( $this->target );
		if ( $status !== true ) {
			throw new ErrorPageError( 'badaccess', $status );
		}

		$wgOut->setPageTitle( wfMsg( 'blockip-title' ) );
		$wgOut->addModules( 'mediawiki.special', 'mediawiki.special.block' );

		$fields = self::getFormFields();
		$this->alreadyBlocked = $this->maybeAlterFormDefaults( $fields );

		$form = new HTMLForm( $fields );
		$form->setTitle( $this->getTitle() );
		$form->setWrapperLegend( wfMsg( 'blockip-legend' ) );
		$form->setSubmitCallback( array( __CLASS__, 'processForm' ) );

		$t = $this->alreadyBlocked
			? wfMsg( 'ipb-change-block' )
			: wfMsg( 'ipbsubmit' );
		$form->setSubmitText( $t );

		$this->doPreText( $form );
		$this->doPostText( $form );

		if( $form->show() ){
			$wgOut->setPageTitle( wfMsg( 'blockipsuccesssub' ) );
			$wgOut->addWikiMsg( 'blockipsuccesstext',  $this->target );
		}
	}

	/**
	 * Get the HTMLForm descriptor array for the block form
	 * @return Array
	 */
	protected static function getFormFields(){
		global $wgUser, $wgBlockAllowsUTEdit;

		$a = array(
			'Target' => array(
				'type' => 'text',
				'label-message' => 'ipadressorusername',
				'tabindex' => '1',
				'id' => 'mw-bi-target',
				'size' => '45',
				'required' => true,
				'validation-callback' => array( __CLASS__, 'validateTargetField' ),
			),
			'Expiry' => array(
				'type' => !count( self::getSuggestedDurations() ) ? 'text' : 'selectorother',
				'label-message' => 'ipbexpiry',
				'required' => true,
				'tabindex' => '2',
				'options' => self::getSuggestedDurations(),
				'other' => wfMsg( 'ipbother' ),
			),
			'Reason' => array(
				'type' => 'selectandother',
				'label-message' => 'ipbreason',
				'options-message' => 'ipbreason-dropdown',
			),
			'CreateAccount' => array(
				'type' => 'check',
				'label-message' => 'ipbcreateaccount',
				'default' => true,
			),
		);

		if( self::canBlockEmail( $wgUser ) ) {
			$a['DisableEmail'] = array(
				'type' => 'check',
				'label-message' => 'ipbemailban',
			);
		}

		if( $wgBlockAllowsUTEdit ){
			$a['DisableUTEdit'] = array(
				'type' => 'check',
				'label-message' => 'ipb-disableusertalk',
				'default' => false,
			);
		}

		$a['AutoBlock'] = array(
			'type' => 'check',
			'label-message' => 'ipbenableautoblock',
			'default' => true,
		);

		# Allow some users to hide name from block log, blocklist and listusers
		if( $wgUser->isAllowed( 'hideuser' ) ) {
			$a['HideUser'] = array(
				'type' => 'check',
				'label-message' => 'ipbhidename',
				'cssclass' => 'mw-block-hideuser',
			);
		}

		# Watchlist their user page? (Only if user is logged in)
		if( $wgUser->isLoggedIn() ) {
			$a['Watch'] = array(
				'type' => 'check',
				'label-message' => 'ipbwatchuser',
			);
		}

		$a['HardBlock'] = array(
			'type' => 'check',
			'label-message' => 'ipb-hardblock',
			'default' => false,
		);

		$a['AlreadyBlocked'] = array(
			'type' => 'hidden',
			'default' => false,
		);

		return $a;
	}

	/**
	 * If the user has already been blocked with similar settings, load that block
	 * and change the defaults for the form fields to match the existing settings.
	 * @param &$fields Array HTMLForm descriptor array
	 * @return Bool whether fields were altered (that is, whether the target is
	 *     already blocked)
	 */
	protected function maybeAlterFormDefaults( &$fields ){
		$fields['Target']['default'] = (string)$this->target;

		$block = Block::newFromTarget( $this->target );

		if( $block instanceof Block && !$block->mAuto # The block exists and isn't an autoblock
			&& ( $this->type != Block::TYPE_RANGE # The block isn't a rangeblock
			  || $block->getTarget() == $this->target ) # or if it is, the range is what we're about to block
			)
		{
			$fields['HardBlock']['default'] = $block->isHardblock();
			$fields['CreateAccount']['default'] = $block->prevents( 'createaccount' );
			$fields['AutoBlock']['default'] = $block->isAutoblocking();
			if( isset( $fields['DisableEmail'] ) ){
				$fields['DisableEmail']['default'] = $block->prevents( 'sendemail' );
			}
			if( isset( $fields['HideUser'] ) ){
				$fields['HideUser']['default'] = $block->mHideName;
			}
			if( isset( $fields['DisableUTEdit'] ) ){
				$fields['DisableUTEdit']['default'] = $block->prevents( 'editownusertalk' );
			}
			$fields['Reason']['default'] = $block->mReason;
			$fields['AlreadyBlocked']['default'] = true;

			if( $block->mExpiry == 'infinity' ) {
				$fields['Expiry']['default'] = 'indefinite';
			} else {
				$fields['Expiry']['default'] = wfTimestamp( TS_RFC2822, $block->mExpiry );
			}

			return true;
		}
		return false;
	}

	/**
	 * Add header elements like block log entries, etc.
	 * @param  $form HTMLForm
	 * @return void
	 */
	protected function doPreText( HTMLForm &$form ){
		$form->addPreText( wfMsgExt( 'blockiptext', 'parse' ) );

		$otherBlockMessages = array();
		if( $this->target !== null ) {
			# Get other blocks, i.e. from GlobalBlocking or TorBlock extension
			wfRunHooks( 'OtherBlockLogLink', array( &$otherBlockMessages, $this->target ) );

			if( count( $otherBlockMessages ) ) {
				$s = Html::rawElement(
					'h2',
					array(),
					wfMsgExt( 'ipb-otherblocks-header', 'parseinline', count( $otherBlockMessages ) )
				) . "\n";
				$list = '';
				foreach( $otherBlockMessages as $link ) {
					$list .= Html::rawElement( 'li', array(), $link ) . "\n";
				}
				$s .= Html::rawElement(
					'ul',
					array( 'class' => 'mw-blockip-alreadyblocked' ),
					$list
				) . "\n";
				$form->addPreText( $s );
			}
		}

		# Username/IP is blocked already locally
		if( $this->alreadyBlocked ) {
			$form->addPreText( Html::rawElement(
				'div',
				array( 'class' => 'mw-ipb-needreblock', ),
				wfMsgExt(
					'ipb-needreblock',
					array( 'parseinline' ),
					$this->target
			) ) );
		}
	}

	/**
	 * Add footer elements to the form
	 * @param  $form HTMLForm
	 * @return void
	 */
	protected function doPostText( HTMLForm &$form ){
		global $wgUser, $wgLang;

		$skin = $wgUser->getSkin();

		# Link to the user's contributions, if applicable
		if( $this->target instanceof User ){
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $this->target->getName() );
			$links[] = $skin->link(
				$contribsPage,
				wfMsgExt( 'ipb-blocklist-contribs', 'escape', $this->target->getName() )
			);
		}

		# Link to unblock the specified user, or to a blank unblock form
		if( $this->target instanceof User ) {
			$message = wfMsgExt( 'ipb-unblock-addr', array( 'parseinline' ), $this->target->getName() );
			$list = SpecialPage::getTitleFor( 'Unblock', $this->target->getName() );
		} else {
			$message = wfMsgExt( 'ipb-unblock', array( 'parseinline' ) );
			$list = SpecialPage::getTitleFor( 'Unblock' );
		}
		$links[] = $skin->linkKnown( $list, $message, array() );

		# Link to the block list
		$links[] = $skin->linkKnown(
			SpecialPage::getTitleFor( 'BlockList' ),
			wfMsg( 'ipb-blocklist' )
		);

		# Link to edit the block dropdown reasons, if applicable
		if ( $wgUser->isAllowed( 'editinterface' ) ) {
			$links[] = $skin->link(
				Title::makeTitle( NS_MEDIAWIKI, 'Ipbreason-dropdown' ),
				wfMsgHtml( 'ipb-edit-dropdown' ),
				array(),
				array( 'action' => 'edit' )
			);
		}

		$form->addPostText( Html::rawElement(
			'p',
			array( 'class' => 'mw-ipb-conveniencelinks' ),
			$wgLang->pipeList( $links )
		) );

		if( $this->target instanceof User ){
			# Get relevant extracts from the block and suppression logs, if possible
			$userpage = $this->target->getUserPage();
			$out = '';

			LogEventsList::showLogExtract(
				$out,
				'block',
				$userpage->getPrefixedText(),
				'',
				array(
					'lim' => 10,
					'msgKey' => array( 'blocklog-showlog', $userpage->getText() ),
					'showIfEmpty' => false
				)
			);
			$form->addPostText( $out );

			# Add suppression block entries if allowed
			if( $wgUser->isAllowed( 'suppressionlog' ) ) {
				LogEventsList::showLogExtract(
					$out,
					'suppress',
					$userpage->getPrefixedText(),
					'',
					array(
						'lim' => 10,
						'conds' => array( 'log_action' => array( 'block', 'reblock', 'unblock' ) ),
						'msgKey' => array( 'blocklog-showsuppresslog', $userpage->getText() ),
						'showIfEmpty' => false
					)
				);
				$form->addPostText( $out );
			}
		}
	}

	/**
	 * Determine the target of the block, and the type of target
	 * TODO: should be in Block.php?
	 * @param $par String subpage parameter passed to setup, or data value from
	 *     the HTMLForm
	 * @param $request WebRequest optionally try and get data from a request too
	 * @return void
	 */
	public static function getTargetAndType( $par, WebRequest $request = null ){
		$i = 0;
		$target = null;
		while( true ){
			switch( $i++ ){
				case 0:
					# The HTMLForm will check wpTarget first and only if it doesn't get
					# a value use the default, which will be generated from the options
					# below; so this has to have a higher precedence here than $par, or
					# we could end up with different values in $this->target and the HTMLForm!
					if( $request instanceof WebRequest ){
						$target = $request->getText( 'wpTarget', null );
					}
					break;
				case 1:
					$target = $par;
					break;
				case 2:
					if( $request instanceof WebRequest ){
						$target = $request->getText( 'ip', null );
					}
					break;
				case 3:
					# B/C @since 1.18
					if( $request instanceof WebRequest ){
						$target = $request->getText( 'wpBlockAddress', null );
					}
					break;
				case 4:
					break 2;
			}
			list( $target, $type ) = Block::parseTarget( $target );
			if( $type !== null ){
				return array( $target, $type );
			}
		}
		return array( null, null );
	}

	/**
	 * HTMLForm field validation-callback for Target field.
	 * @since 1.18
	 * @return Message
	 */
	public static function validateTargetField( $value, $alldata = null ) {
		global $wgBlockCIDRLimit;

		list( $target, $type ) = self::getTargetAndType( $value );

		if( $type == Block::TYPE_USER ){
			# TODO: why do we not have a User->exists() method?
			if( !$target->getId() ){
				return wfMessage( 'nosuchusershort', $target->getName() );
			}

			$status = self::checkUnblockSelf( $target );
			if ( $status !== true ) {
				return wfMessage( 'badaccess', $status );
			}

		} elseif( $type == Block::TYPE_RANGE ){
			list( $ip, $range ) = explode( '/', $target, 2 );

			if( ( IP::isIPv4( $ip ) && $wgBlockCIDRLimit['IPv4'] == 32 )
				|| ( IP::isIPv6( $ip ) && $wgBlockCIDRLimit['IPV6'] == 128 ) )
			{
				# Range block effectively disabled
				return wfMessage( 'range_block_disabled' );
			}

			if( ( IP::isIPv4( $ip ) && $range > 32 )
				|| ( IP::isIPv6( $ip ) && $range > 128 ) )
			{
				# Dodgy range
				return wfMessage( 'ip_range_invalid' );
			}

			if( IP::isIPv4( $ip ) && $range < $wgBlockCIDRLimit['IPv4'] ) {
				return wfMessage( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv4'] );
			}

			if( IP::isIPv6( $ip ) && $range < $wgBlockCIDRLimit['IPv6'] ) {
				return wfMessage( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv6'] );
			}

		} elseif( $type == Block::TYPE_IP ){
			# All is well

		} else {
			return wfMessage( 'badipaddress' );
		}

		return true;
	}

	/**
	 * Given the form data, actually implement a block
	 * @param  $data Array
	 * @return Bool|String
	 */
	public static function processForm( array $data ){
		global $wgUser, $wgBlockAllowsUTEdit;

		// Handled by field validator callback
		// self::validateTargetField( $data['Target'] );

		list( $target, $type ) = self::getTargetAndType( $data['Target'] );
		if( $type == Block::TYPE_USER ){
			$user = $target;
			$target = $user->getName();
			$userId = $user->getId();
		} elseif( $type == Block::TYPE_RANGE ){
			$userId = 0;
		} elseif( $type == Block::TYPE_IP ){
			$target = $target->getName();
			$userId = 0;
		} else {
			# This should have been caught in the form field validation
			return array( 'badipaddress' );
		}

		if( ( strlen( $data['Expiry'] ) == 0) || ( strlen( $data['Expiry'] ) > 50 )
			|| !self::parseExpiryInput( $data['Expiry'] ) )
		{
			return array( 'ipb_expiry_invalid' );
		}

		if( !isset( $data['DisableEmail'] ) ){
			$data['DisableEmail'] = false;
		}

		# If the user has done the form 'properly', they won't even have been given the
		# option to suppress-block unless they have the 'hideuser' permission
		if( !isset( $data['HideUser'] ) ){
			$data['HideUser'] = false;
		}
		if( $data['HideUser'] ) {
			if( !$wgUser->isAllowed('hideuser') ){
				# this codepath is unreachable except by a malicious user spoofing forms,
				# or by race conditions (user has oversight and sysop, loads block form,
				# and is de-oversighted before submission); so need to fail completely
				# rather than just silently disable hiding
				return array( 'badaccess-group0' );
			}

			# Recheck params here...
			if( $type != Block::TYPE_USER ) {
				$data['HideUser'] = false; # IP users should not be hidden

			} elseif( !in_array( $data['Expiry'], array( 'infinite', 'infinity', 'indefinite' ) ) ) {
				# Bad expiry.
				return array( 'ipb_expiry_temp' );

			} elseif( $user->getEditCount() > self::HIDEUSER_CONTRIBLIMIT ) {
				# Typically, the user should have a handful of edits.
				# Disallow hiding users with many edits for performance.
				return array( 'ipb_hide_invalid' );
			}
		}

		# Create block object.
		$block = new Block();
		$block->setTarget( $target );
		$block->setBlocker( $wgUser );
		$block->mReason = $data['Reason'][0];
		$block->mExpiry = self::parseExpiryInput( $data['Expiry'] );
		$block->prevents( 'createaccount', $data['CreateAccount'] );
		$block->prevents( 'editownusertalk', ( !$wgBlockAllowsUTEdit || $data['DisableUTEdit'] ) );
		$block->prevents( 'sendemail', $data['DisableEmail'] );
		$block->isHardblock( $data['HardBlock'] );
		$block->isAutoblocking( $data['AutoBlock'] );
		$block->mHideName = $data['HideUser'];

		if( !wfRunHooks( 'BlockIp', array( &$block, &$wgUser ) ) ) {
			return array( 'hookaborted' );
		}

		# Try to insert block. Is there a conflicting block?
		$status = $block->insert();
		if( !$status ) {
			# Show form unless the user is already aware of this...
			if( !$data['AlreadyBlocked'] ) {
				return array( array( 'ipb_already_blocked', $block->getTarget() ) );
			# Otherwise, try to update the block...
			} else {
				# This returns direct blocks before autoblocks/rangeblocks, since we should
				# be sure the user is blocked by now it should work for our purposes
				$currentBlock = Block::newFromTarget( $target );

				if( $block->equals( $currentBlock ) ) {
					return array( array( 'ipb_already_blocked', $block->getTarget() ) );
				}

				# If the name was hidden and the blocking user cannot hide
				# names, then don't allow any block changes...
				if( $currentBlock->mHideName && !$wgUser->isAllowed( 'hideuser' ) ) {
					return array( 'cant-see-hidden-user' );
				}

				$currentBlock->delete();
				$status = $block->insert();
				$logaction = 'reblock';

				# Unset _deleted fields if requested
				if( $currentBlock->mHideName && !$data['HideUser'] ) {
					RevisionDeleteUser::unsuppressUserName( $target, $userId );
				}

				# If hiding/unhiding a name, this should go in the private logs
				if( (bool)$currentBlock->mHideName ){
					$data['HideUser'] = true;
				}
			}
		} else {
			$logaction = 'block';
		}

		wfRunHooks( 'BlockIpComplete', array( $block, $wgUser ) );

		# Set *_deleted fields if requested
		if( $data['HideUser'] ) {
			RevisionDeleteUser::suppressUserName( $target, $userId );
		}

		# Can't watch a rangeblock
		if( $type != Block::TYPE_RANGE && $data['Watch'] ) {
			$wgUser->addWatch( Title::makeTitle( NS_USER, $target ) );
		}

		# Block constructor sanitizes certain block options on insert
		$data['BlockEmail'] = $block->prevents( 'sendemail' );
		$data['AutoBlock'] = $block->isAutoblocking();

		# Prepare log parameters
		$logParams = array();
		$logParams[] = $data['Expiry'];
		$logParams[] = self::blockLogFlags( $data, $type );

		# Make log entry, if the name is hidden, put it in the oversight log
		$log_type = $data['HideUser'] ? 'suppress' : 'block';
		$log = new LogPage( $log_type );
		$log_id = $log->addEntry(
			$logaction,
			Title::makeTitle( NS_USER, $target ),
			$data['Reason'][0],
			$logParams
		);
		# Relate log ID to block IDs (bug 25763)
		$blockIds = array_merge( array( $status['id'] ), $status['autoIds']  );
		$log->addRelations( 'ipb_id', $blockIds, $log_id );

		# Report to the user
		return true;
	}

	/**
	 * Get an array of suggested block durations from MediaWiki:Ipboptions
	 * FIXME: this uses a rather odd syntax for the options, should it be converted
	 *     to the standard "**<duration>|<displayname>" format?
	 * @return Array
	 */
	public static function getSuggestedDurations( $lang = null ){
		$a = array();
		$msg = $lang === null
			? wfMessage( 'ipboptions' )->inContentLanguage()->text()
			: wfMessage( 'ipboptions' )->inLanguage( $lang )->text();

		if( $msg == '-' ){
			return array();
		}

		foreach( explode( ',', $msg ) as $option ) {
			if( strpos( $option, ':' ) === false ){
				$option = "$option:$option";
			}
			list( $show, $value ) = explode( ':', $option );
			$a[htmlspecialchars( $show )] = htmlspecialchars( $value );
		}
		return $a;
	}

	/**
	 * Convert a submitted expiry time, which may be relative ("2 weeks", etc) or absolute
	 * ("24 May 2034", etc), into an absolute timestamp we can put into the database.
	 * @param $expiry String: whatever was typed into the form
	 * @return String: timestamp or "infinity" string for the DB implementation
	 */
	public static function parseExpiryInput( $expiry ) {
		static $infinity;
		if( $infinity == null ){
			$infinity = wfGetDB( DB_READ )->getInfinity();
		}
		if ( $expiry == 'infinite' || $expiry == 'indefinite' ) {
			$expiry = $infinity;
		} else {
			$expiry = strtotime( $expiry );
			if ( $expiry < 0 || $expiry === false ) {
				return false;
			}
			$expiry = wfTimestamp( TS_MW, $expiry );
		}
		return $expiry;
	}

	/**
	 * Can we do an email block?
	 * @param $user User: the sysop wanting to make a block
	 * @return Boolean
	 */
	public static function canBlockEmail( $user ) {
		global $wgEnableUserEmail, $wgSysopEmailBans;
		return ( $wgEnableUserEmail && $wgSysopEmailBans && $user->isAllowed( 'blockemail' ) );
	}

	/**
	 * bug 15810: blocked admins should not be able to block/unblock
	 * others, and probably shouldn't be able to unblock themselves
	 * either.
	 * @param $user User|Int|String
	 */
	public static function checkUnblockSelf( $user ) {
		global $wgUser;
		if ( is_int( $user ) ) {
			$user = User::newFromId( $user );
		} elseif ( is_string( $user ) ) {
			$user = User::newFromName( $user );
		}
		if( $wgUser->isBlocked() ){
			if( $user instanceof User && $user->getId() == $wgUser->getId() ) {
				# User is trying to unblock themselves
				if ( $wgUser->isAllowed( 'unblockself' ) ) {
					return true;
				} else {
					return 'ipbnounblockself';
				}
			} else {
				# User is trying to block/unblock someone else
				return 'ipbblocked';
			}
		} else {
			return true;
		}
	}

	/**
	 * Return a comma-delimited list of "flags" to be passed to the log
	 * reader for this block, to provide more information in the logs
	 * @param $data Array from HTMLForm data
	 * @param $type Block::TYPE_ constant
	 * @return array
	 */
	protected static function blockLogFlags( array $data, $type ) {
		global $wgBlockAllowsUTEdit;
		$flags = array();

		# when blocking a user the option 'anononly' is not available/has no effect -> do not write this into log
		if( !$data['HardBlock'] && $type != Block::TYPE_USER ){
			$flags[] = 'anononly';
		}

		if( $data['CreateAccount'] ){
			$flags[] = 'nocreate';
		}

		# Same as anononly, this is not displayed when blocking an IP address
		if( !$data['AutoBlock'] && $type != Block::TYPE_IP ){
			$flags[] = 'noautoblock';
		}

		if( $data['DisableEmail'] ){
			$flags[] = 'noemail';
		}

		if( $data['DisableUTEdit'] && $wgBlockAllowsUTEdit ){
			$flags[] = 'nousertalk';
		}

		if( $data['HideUser'] ){
			$flags[] = 'hiddenname';
		}

		return implode( ',', $flags );
	}
}

# BC @since 1.18
class IPBlockForm extends SpecialBlock {}
