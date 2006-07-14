<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

/* Cymraeg - Welsh */

/* private */ $wgNamespaceNamesCy = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Arbennig",
	NS_MAIN           => "",
	NS_TALK           => "Sgwrs",
	NS_USER           => "Defnyddiwr",
	NS_USER_TALK      => "Sgwrs_Defnyddiwr",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => "Sgwrs_".$wgMetaNamespace,
	NS_IMAGE          => "Delwedd",
	NS_IMAGE_TALK     => "Sgwrs_Delwedd",
	NS_MEDIAWIKI      => "MediaWici",
	NS_MEDIAWIKI_TALK => "Sgwrs_MediaWici",
	NS_TEMPLATE       => "Nodyn",
	NS_TEMPLATE_TALK  => "Sgwrs_Nodyn",
	NS_CATEGORY		  => "Categori",
	NS_CATEGORY_TALK  => "Sgwrs_Categori",
	NS_HELP			  => "Cymorth",
	NS_HELP_TALK	  => "Sgwrs Cymorth"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsCy = array(
	"Dim", "Sefydlog chwith", "Sefydlog de", "Arnawf de"
);

/* private */ $wgSkinNamesCy = array(
	'standard' => "Safonol",
	'nostalgia' => "Hiraeth",
	'cologneblue' => "Glas Cwlen",
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsCy = array(
#	"Dim dewis",
);

/* private */ $wgBookstoreListCy = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1",
	"Amazon.co.uk" => "http://www.amazon.co.uk/exec/obidos/ISBN=$1"
);


/* private */ $wgMagicWordsCy = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    "#redirect", "#ail-cyfeirio"                 ),
	'notoc'                  => array( 0,    "__NOTOC__", "__DIMTAFLENCYNNWYS__"          ),
	'noeditsection'          => array( 0,    "__NOEDITSECTION__", "__DIMADRANGOLYGU__"    ),
	'start'                  => array( 0,    "__START__", "__DECHRAU__"                   ),
	'currentmonth'           => array( 1, "CURRENTMONTH", "MISCYFOES"                ),
	'currentmonthname'       => array( 1,    "CURRENTMONTHNAME", "ENWMISCYFOES"           ),
	'currentday'             => array( 1,    "CURRENTDAY", "DYDDIADCYFOES"                ),
	'currentdayname'         => array( 1,    "CURRENTDAYNAME", "ENWDYDDCYFOES"            ),
	'currentyear'            => array( 1,    "CURRENTYEAR", "FLWYDDYNCYFOES"              ),
	'currenttime'            => array( 1,    "CURRENTTIME", "AMSERCYFOES"                 ),
	'numberofarticles'       => array( 1, "NUMBEROFARTICLES","NIFEROERTHYGLAU"       ),
	'currentmonthnamegen'    => array( 1,    "CURRENTMONTHNAMEGEN", "GENENWMISCYFOES"     ),
	'subst'                  => array( 1,    "SUBST:"                                     ),
	'msgnw'                  => array( 0,    "MSGNW:"                                     ),
	'end'                    => array( 0, "__DIWEDD__"                                   ),
	'img_thumbnail'          => array( 1, "ewin bawd", "bawd", "thumb", "thumbnail"  ),
	'img_right'              => array( 1,    "de", "right"                                ),
	'img_left'               => array( 1,    "chwith", "left"                             ),
	'img_none'               => array( 1,    "dim", "none"                                ),
	'img_width'              => array( 1,    "$1px"                                       ),
	'img_center'             => array( 1, "canol", "centre", "center"                ),
	'int'                    => array( 0,    "INT:"                                       )

);

if (!$wgCachedMessageArrays) {
	require_once('MessagesCy.php');
}


/** */
require_once( 'LanguageUtf8.php' );

/** @package MediaWiki */
class LanguageCy extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListCy;
		return $wgBookstoreListCy;
	}

	function getNamespaces() {
		global $wgNamespaceNamesCy;
		return $wgNamespaceNamesCy;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsCy;
		return $wgQuickbarSettingsCy;
	}

	function getSkinNames() {
		global $wgSkinNamesCy;
		return $wgSkinNamesCy;
	}

	function getDateFormats() {
		global $wgDateFormatsCy;
		return $wgDateFormatsCy;
	}

	function getMessage( $key ) {
		global $wgAllMessagesCy;
		if( isset( $wgAllMessagesCy[$key] ) ) {
			return $wgAllMessagesCy[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		global $wgAllMessagesCy;
		return $wgAllMessagesCy;
	}

	function &getMagicWords() {
		global $wgMagicWordsCy, $wgMagicWordsEn;
		return $wgMagicWordsCy + $wgMagicWordsEn;
	}

}

?>
