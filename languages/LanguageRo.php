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
		MAG_REDIRECT             => array( 0,    '#redirect'                                       ),
		MAG_NOTOC                => array( 0,    '__NOTOC__', '__FARACUPRINS__'                    ),
		MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__', '__FARAEDITSECTIUNE__'       ),
		MAG_START                => array( 0,    '__START__'                                       ),
		MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH', '{{NUMARLUNACURENTA}}'            ),
		MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME', '{{NUMELUNACURENTA}}'         ),
		MAG_CURRENTDAY           => array( 1,    'CURRENTDAY', '{{NUMARZIUACURENTA}}'              ),
		MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME', '{{NUMEZIUACURENTA}}'           ),
		MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR', '{{ANULCURENT}}'                   ),
		MAG_CURRENTTIME          => array( 1,    'CURRENTTIME', '{{ORACURENTA}}'                   ),
		MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES', '{{NUMARDEARTICOLE}}'         ),
		MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN', '{{NUMELUNACURENTAGEN}}'   ),
		MAG_SUBST                => array( 0,    'SUBST:'                                          ),
		MAG_MSGNW                => array( 0,    'MSGNW:', 'MSJNOU:'                               ),
		MAG_END                  => array( 0,    '__END__', '__FINAL__'                            ),
		MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'                              ),
		MAG_IMG_RIGHT            => array( 1,    'right'                                           ),
		MAG_IMG_LEFT             => array( 1,    'left'                                            ),
		MAG_IMG_NONE             => array( 1,    'none'                                            ),
		MAG_IMG_WIDTH            => array( 1,    '$1px'                                            ),
		MAG_IMG_CENTER           => array( 1,    'center', 'centre'                                ),
		MAG_INT                  => array( 0,    'INT:'                                            )
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
