<?php
/** Copyright (C) 2004 Thomas Gries <mail@tgries.de>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html
**/

/**
 * See deferred.doc
 *
 * @package MediaWiki
 */

/**
 *
 * @package MediaWiki
 */

class UserTalkUpdate {

	/* private */ var $mAction, $mNamespace, $mTitle, $mSummary, $mMinorEdit, $mTimestamp;

	function UserTalkUpdate( $action, $ns, $title, $summary, $minoredit, $timestamp) {
		global $wgUser, $wgLang, $wgMemc, $wgDBname, $wgEnotif;
		global $wgEmailNotificationForUserTalkPages, $wgEmailNotificationSystembeep;
		global $wgEmailAuthentication;
		$fname = 'UserTalkUpdate::UserTalkUpdate';

		$this->mAction = $action;
		$this->mNamespace = $ns;
		$this->mTitle = $title; # str_replace( '_', ' ', $title ); # I do not know, why this was needed . T. Gries 23.11.2004
		$this->mSummary = $summary;
		$this->mMinorEdit = $minoredit;
		$this->mTimestamp = $timestamp;

		# If namespace isn't User_talk:, do nothing.
		if ( $this->mNamespace != Namespace::getTalk(Namespace::getUser() ) ) {
			return;
		}

		# If the user talk page is our own, clear the flag
		# when we are reading it or writing it.
		if ( 0 == strcmp( $this->mTitle, $wgUser->getName() ) ) {
			$wgUser->setNewtalk( 0 );
			$wgUser->saveSettings();
		} else {
			# Not ours.  If writing, then mark it as modified.
			$sql = false;
			if ( 1 == $this->mAction ) {
				$user = new User();
				$user->setID(User::idFromName($this->mTitle));
				if ($id=$user->getID()) {
					$sql = true;
					$wgMemc->delete( "$wgDBname:user:id:$id" );
				} else {
					if ( $wgUser->isIP($this->mTitle) ) { # anonymous
						$dbw =& wfGetDB( DB_MASTER );
						$dbw->replace( 'watchlist',
							array(array('wl_user','wl_namespace', 'wl_title', 'wl_notificationtimestamp')),
							  array('wl_user' 			=> 0,
								'wl_namespace' 			=> NS_USER_TALK,
								'wl_title' 			=> $this->mTitle,
								'wl_notificationtimestamp' 	=> 1
								), 'UserTalkUpdate'
							);
						$sql = true;
						$wgMemc->delete( "$wgDBname:newtalk:ip:$this->mTitle" );
					}
				}

				if($sql && !$user->getNewtalk() ) {
					# create an artificial watchlist table entry for the owner X of the user_talk page X
					# only insert if X is a real user and the page is not yet watched
					# mark the changed watch-listed page with a timestamp, so that the page is listed with
					# an "updated since your last visit" icon in the watch list, ...
					# ... no matter, if the watching user has or has not indicated an email address in his/her preferences.
					# We memorise the event of sending out a notification and use this as a flag to suppress
					# further mails for changes on the same page for that watching user
					$dbw =& wfGetDB( DB_MASTER );
					$dbw->replace( 'watchlist',
						array(array('wl_user','wl_namespace', 'wl_title', 'wl_notificationtimestamp')),
						  array('wl_user' 			=> $id,
							'wl_namespace' 			=> NS_USER_TALK,
							'wl_title' 			=> $this->mTitle,
							'wl_notificationtimestamp' 	=> 1
							), 'UserTalkUpdate'
						);
				}
			}
		}
	}
}

?>
