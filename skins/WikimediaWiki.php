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

class SkinWikimediawiki extends SkinMonoBook {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'wikimediawiki';
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
		$nav_urls['randompage'] = (wfMsg('randompage') != '-') ? array('href' => htmlspecialchars( $this->makeSpecialUrl('Randompage'))) : false;
		$nav_urls['recentchanges'] = (wfMsg('recentchanges') != '-') ? array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchanges'))) : false;
		$nav_urls['whatlinkshere'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Whatlinkshere', 'target='.urlencode( $this->thispage ))));
		$nav_urls['currentevents'] = (wfMsg('currentevents') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('currentevents'))) : false;
		$nav_urls['portal'] = (wfMsg('portal') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('portal-url'))) : false;
		$nav_urls['recentchangeslinked'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchangeslinked', 'target='.urlencode( $this->thispage ))));
		$nav_urls['bugreports'] = (wfMsg('bugreports') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('bugreportspage'))) : false;
		$nav_urls['sitesupport'] = array('href' => htmlspecialchars( $wgSiteSupportPage));
		$nav_urls['help'] = array('href' => htmlspecialchars( $this->makeI18nUrl('helppage')));
		$nav_urls['upload'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Upload')));
		$nav_urls['specialpages'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Specialpages')));


		# Specific for mediawiki.org menu
		$nav_urls['aboutmediawiki'] = (wfMsg('aboutmediawiki') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('aboutmediawiki-url'))) : false;
		$nav_urls['projects'] = (wfMsg('projects') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('projects-url'))) : false;
		$nav_urls['membership'] = (wfMsg('membership') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('membership-url'))) : false;
		$nav_urls['pressroom'] = (wfMsg('pressroom') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('pressroom-url'))) : false;
		$nav_urls['software'] = (wfMsg('software') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('software-url'))) : false;
		$nav_urls['localchapters'] = (wfMsg('localchapters') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('localchapters-url'))) : false;
		$nav_urls['contactus'] = (wfMsg('contactus') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('contactus-url'))) : false;

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
