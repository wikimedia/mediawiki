<?php
/**
 * Tentative to make a skin for wikimedia.org
 *
 * @version $Id$
 * @package MediaWiki
 * @subpackage Skins
 */

/** */

if($wgUsePHPTal) {
require_once('includes/SkinPHPTal.php');

$wgExtraSkins['wikimediawiki'] = 'Wikimediawiki';

require_once('MonoBook.php');

/**
 *
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinWikimediawiki extends SkinMonoBook {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'wikimediawiki';
		$this->stylename = 'monobook';
		$this->template = 'WikimediaWiki';
	}

	# build array of common navigation links
	function buildNavUrls () {
		global $wgTitle, $wgUser, $wgRequest;
		global $wgSiteSupportPage;

		$action = $wgRequest->getText( 'action' );
		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		// XXX: remove htmlspecialchars when tal:attributes works with i18n:attributes
		$nav_urls = array();
		$nav_urls['mainpage'] = array('href' => htmlspecialchars( $this->makeI18nUrl('mainpage')));
		$nav_urls['randompage'] = (wfMsgForContent('randompage') != '-') ? array('href' => htmlspecialchars( $this->makeSpecialUrl('Randompage'))) : false;
		$nav_urls['recentchanges'] = (wfMsgForContent('recentchanges') != '-') ? array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchanges'))) : false;
		$nav_urls['whatlinkshere'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Whatlinkshere', 'target='.urlencode( $this->thispage ))));
		$nav_urls['currentevents'] = (wfMsgForContent('currentevents') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('currentevents'))) : false;
		$nav_urls['portal'] = (wfMsgForContent('portal') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('portal-url'))) : false;
		$nav_urls['recentchangeslinked'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchangeslinked', 'target='.urlencode( $this->thispage ))));
		$nav_urls['bugreports'] = (wfMsgForContent('bugreports') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('bugreportspage'))) : false;
		$nav_urls['sitesupport'] = array('href' => htmlspecialchars( $wgSiteSupportPage));
		$nav_urls['help'] = array('href' => htmlspecialchars( $this->makeI18nUrl('helppage')));
		$nav_urls['upload'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Upload')));
		$nav_urls['specialpages'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Specialpages')));


		# Specific for mediawiki.org menu
		$nav_urls['aboutmediawiki'] = (wfMsgForContent('aboutmediawiki') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('aboutmediawiki-url'))) : false;
		$nav_urls['projects'] = (wfMsgForContent('projects') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('projects-url'))) : false;
		$nav_urls['membership'] = (wfMsgForContent('membership') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('membership-url'))) : false;
		$nav_urls['pressroom'] = (wfMsgForContent('pressroom') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('pressroom-url'))) : false;
		$nav_urls['software'] = (wfMsgForContent('software') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('software-url'))) : false;
		$nav_urls['localchapters'] = (wfMsgForContent('localchapters') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('localchapters-url'))) : false;
		$nav_urls['contactus'] = (wfMsgForContent('contactus') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('contactus-url'))) : false;

		if( $wgTitle->getNamespace() == NS_USER || $wgTitle->getNamespace() == NS_USER_TALK ) {
			$id = User::idFromName($wgTitle->getText());
			$ip = User::isIP($wgTitle->getText());
		} else {
			$id = 0;
			$ip = false;
		}

		if ( 0 != $wgUser->getID() ) { # show only to signed in users
			if($id) {
				# can only email non-anons
				$nav_urls['emailuser'] = array(
					'href' => htmlspecialchars( $this->makeSpecialUrl('Emailuser', "target=" . $wgTitle->getPartialURL() ) )
				);
				# only non-anons have contrib list
				$nav_urls['contributions'] = array(
					'href' => htmlspecialchars( $this->makeSpecialUrl('Contributions', "target=" . $wgTitle->getPartialURL() ) )
				);
			}
		}


		return $nav_urls;
	}
}

}
?>
