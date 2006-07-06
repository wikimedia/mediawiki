<?php
/** Telugu (Telugu)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesTe.php');
}

class LanguageTe extends LanguageUtf8 {
	private $mMessagesTe, $mNamespaceNamesTe = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesTe;
		$this->mMessagesTe =& $wgAllMessagesTe;

		global $wgMetaNamespace;
		$this->mNamespaceNamesTe = array(
			NS_MEDIA            => 'మీడియా',
			NS_SPECIAL          => 'ప్రత్యేక',
			NS_MAIN             => '',
			NS_TALK             => 'చర్చ',
			NS_USER             => 'సభ్యుడు',
			NS_USER_TALK        => 'సభ్యునిపై_చర్చ',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_చర్చ',
			NS_IMAGE            => 'బొమ్మ',
			NS_IMAGE_TALK       => 'బొమ్మపై_చర్చ',
			NS_MEDIAWIKI        => 'మీడియావికీ',
			NS_MEDIAWIKI_TALK   => 'మీడియావికీ_చర్చ',
			NS_TEMPLATE         => 'మూస',
			NS_TEMPLATE_TALK    => 'మూస_చర్చ',
			NS_HELP             => 'సహాయము',
			NS_HELP_TALK        => 'సహాయము_చర్చ',
			NS_CATEGORY         => 'వర్గం',
			NS_CATEGORY_TALK    => 'వర్గం_చర్చ'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesTe + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesTe[$key] ) ) {
			return $this->mMessagesTe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesTe;
	}

	// nobody seems to use these anymore
	/*function digitTransformTable() {
		 
		return array(
			'0' => '౦',
			'1' => '౧',
			'2' => '౨',
			'3' => '౩',
			'4' => '౪',
			'5' => '౫',
			'6' => '౬',
			'7' => '౭',
			'8' => '౮',
			'9' => '౯'
		);
	}*/

}
?>
