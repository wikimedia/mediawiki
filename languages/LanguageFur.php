<?php
/** Friulian (Furlan)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesFur.php');
}

class LanguageFur extends LanguageUtf8 {
	private $mMessagesFur, $mNamespaceNamesFur = null;

	private $mQuickbarSettingsFur = array(
		'Nissune', 'Fis a Çampe', 'Fis a Drete', 'Flutuant a çampe'
	);
	
	private $mSkinNamesFur = array(
		'nostalgia' => 'Nostalgie',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesFur;
		$this->mMessagesFur =& $wgAllMessagesFur;

		global $wgMetaNamespace;
		$this->mNamespaceNamesFur = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Speciâl',
			NS_MAIN           => '',
			NS_TALK           => 'Discussion',
			NS_USER           => 'Utent',
			NS_USER_TALK      => 'Discussion_utent',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Discussion_' . $wgMetaNamespace,
			NS_IMAGE          => 'Figure',
			NS_IMAGE_TALK     => 'Discussion_figure',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
			NS_TEMPLATE       => 'Model',
			NS_TEMPLATE_TALK  => 'Discussion_model',
			NS_HELP	          => 'Jutori',
			NS_HELP_TALK      => 'Discussion_jutori',
			NS_CATEGORY       => 'Categorie',
			NS_CATEGORY_TALK  => 'Discussion_categorie'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesFur + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsFur;
	}

	function getSkinNames() {
		return $this->mSkinNamesFur + parent::getSkinNames();
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesFur[$key] ) ) {
			return $this->mMessagesFur[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesFur;
	}

	function timeDateSeparator( $format ) {
		return ' a lis ';
	}

	function timeBeforeDate() {
		return false;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return $this->formatNum( 0 + $day, true ) . ' di ';
	}

	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

}

?>
