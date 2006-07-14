<?php
/** Romanian (Română)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesRo.php');
}

class LanguageRo extends LanguageUtf8 {
	private $mMessagesRo, $mNamespaceNamesRo = null;

	private $mQuickbarSettingsRo = array(
		'Fără', 'Fixă, în stânga', 'Fixă, în dreapta', 'Liberă'
	);
	
	private $mSkinNamesRo = array(
		'standard' => 'Normală',
		'nostalgia' => 'Nostalgie'
	);
		
	private $mMagicWordsRo = array(
	#   ID                                 CASE  SYNONYMS
		'redirect'               => array( 0,    '#redirect'                                       ),
		'notoc'                  => array( 0,    '__NOTOC__', '__FARACUPRINS__'                    ),
		'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__FARAEDITSECTIUNE__'       ),
		'start'                  => array( 0,    '__START__'                                       ),
		'currentmonth'           => array( 1,    'CURRENTMONTH', '{{NUMARLUNACURENTA}}'            ),
		'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', '{{NUMELUNACURENTA}}'         ),
		'currentday'             => array( 1,    'CURRENTDAY', '{{NUMARZIUACURENTA}}'              ),
		'currentdayname'         => array( 1,    'CURRENTDAYNAME', '{{NUMEZIUACURENTA}}'           ),
		'currentyear'            => array( 1,    'CURRENTYEAR', '{{ANULCURENT}}'                   ),
		'currenttime'            => array( 1,    'CURRENTTIME', '{{ORACURENTA}}'                   ),
		'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', '{{NUMARDEARTICOLE}}'         ),
		'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', '{{NUMELUNACURENTAGEN}}'   ),
		'subst'                  => array( 0,    'SUBST:'                                          ),
		'msgnw'                  => array( 0,    'MSGNW:', 'MSJNOU:'                               ),
		'end'                    => array( 0,    '__END__', '__FINAL__'                            ),
		'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'                              ),
		'img_right'              => array( 1,    'right'                                           ),
		'img_left'               => array( 1,    'left'                                            ),
		'img_none'               => array( 1,    'none'                                            ),
		'img_width'              => array( 1,    '$1px'                                            ),
		'img_center'             => array( 1,    'center', 'centre'                                ),
		'int'                    => array( 0,    'INT:'                                            )
	);

	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesRo;
		$this->mMessagesRo =& $wgAllMessagesRo;

		global $wgMetaNamespace;
		$this->mNamespaceNamesRo = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Special',
			NS_MAIN           => '',
			NS_TALK           => 'Discuţie',
			NS_USER           => 'Utilizator',
			NS_USER_TALK      => 'Discuţie_Utilizator',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Discuţie_'.$wgMetaNamespace,
			NS_IMAGE          => 'Imagine',
			NS_IMAGE_TALK     => 'Discuţie_Imagine',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Discuţie_MediaWiki',
			NS_TEMPLATE       => 'Format',
			NS_TEMPLATE_TALK  => 'Discuţie_Format',
			NS_HELP           => 'Ajutor',
			NS_HELP_TALK      => 'Discuţie_Ajutor',
			NS_CATEGORY       => 'Categorie',
			NS_CATEGORY_TALK  => 'Discuţie_Categorie'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesRo + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsRo;
	}

	function getSkinNames() {
		return $this->mSkinNamesRo + parent::getSkinNames();
	}

	function getDateFormats() {
		return false;
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsRo + parent::getMagicWords();
		return $t;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesRo[$key] ) ) {
			return $this->mMessagesRo[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesRo;
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}

	function timeBeforeDate() {
		return false;
	}

	function fallback8bitEncoding() {
		return 'iso8859-2';
	}

}

?>
