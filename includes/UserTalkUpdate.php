<?php
/**
 * See deferred.doc
 *
 */

/**
 *
 */
class UserTalkUpdate {

	/* private */ var $mAction, $mNamespace, $mTitle;

	function UserTalkUpdate( $action, $ns, $title ) {
		$this->mAction = $action;
		$this->mNamespace = $ns;
		$this->mTitle = str_replace( '_', ' ', $title );
	}

	function doUpdate() {	
		global $wgUser, $wgLang, $wgMemc, $wgDBname;
		$fname = 'UserTalkUpdate::doUpdate';

		# If namespace isn't User_talk:, do nothing.

		if ( $this->mNamespace != Namespace::getTalk(
		  Namespace::getUser() ) ) {
			return;
		}
		# If the user talk page is our own, clear the flag
		# whether we are reading it or writing it.
		if ( 0 == strcmp( $this->mTitle, $wgUser->getName() ) ) {
			$wgUser->setNewtalk( 0 );			
			$wgUser->saveSettings();

		} else {
			# Not ours.  If writing, mark it as modified.

			$sql = false;
			$dbw =& wfGetDB( DB_MASTER );
			$user_newtalk = $dbw->tableName( 'user_newtalk' );

			if ( 1 == $this->mAction ) {
				$user = new User();				
				$user->setID(User::idFromName($this->mTitle));
				if ($id=$user->getID()) {									
					$sql = "INSERT INTO $user_newtalk (user_id) values ({$id})";
					$wgMemc->delete( "$wgDBname:user:id:$id" );
				} else {
					#anon
					if(preg_match("/^\d{1,3}\.\d{1,3}.\d{1,3}\.\d{1,3}$/",$this->mTitle)) { #real anon (user:xxx.xxx.xxx.xxx)
						$sql = "INSERT INTO $user_newtalk (user_id,user_ip) values (0,\"{$this->mTitle}\")";		
						$wgMemc->delete( "$wgDBname:newtalk:ip:$this->mTitle" );
					}
				}
				
				if($sql && !$user->getNewtalk()) { # only insert if real user and it's not already there
					$dbw->query( $sql, $fname );
				}
			}
		}
	}
}

?>
