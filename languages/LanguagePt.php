<?php
/** Portuguese (Português)
 * This translation was made by:
 *  - Yves Marques Junqueira
 *  - Rodrigo Calanca Nishino
 *  - Nuno Tavares
 *  - Paulo Juntas
 *  - Manuel Menezes de Sequeira
 *  - Sérgio Ribeiro
 * from the Portuguese Wikipedia
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

#
# In general you should not make customizations in these language files
# directly, but should use the MediaWiki: special namespace to customize
# user interface messages through the wiki.
# See http://meta.wikimedia.org/wiki/MediaWiki_namespace
#

/* private */ $wgNamespaceNamesPt = array(
	NS_MEDIA            => 'Media', # -2
	NS_SPECIAL          => 'Especial', # -1
	NS_MAIN             => '', # 0
	NS_TALK             => 'Discussão', # 1
	NS_USER             => 'Usuário',
	NS_USER_TALK        => 'Usuário_Discussão',
/*
	Above entries are for PT_br. The following entries should
    be used instead. But:

     DO NOT USE THOSE ENTRIES WITHOUT MIGRATING STUFF ON
     WIKIMEDIA WEB SERVERS FIRST !! You will just break a lot
     of links 8-)

	NS_USER             => 'Utilizador', # 2
	NS_USER_TALK        => 'Utilizador_Discussão', # 3
*/
	NS_PROJECT          => $wgMetaNamespace, # 4
	NS_PROJECT_TALK     => $wgMetaNamespace.'_Discussão', # 5
	NS_IMAGE            => 'Imagem', # 6
	NS_IMAGE_TALK       => 'Imagem_Discussão', # 7
	NS_MEDIAWIKI        => 'MediaWiki', # 8
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Discussão', # 9
	NS_TEMPLATE         => 'Predefinição', # 10
	NS_TEMPLATE_TALK    => 'Predefinição_Discussão', # 11
	NS_HELP             => 'Ajuda', # 12
	NS_HELP_TALK        => 'Ajuda_Discussão', # 13
	NS_CATEGORY         => 'Categoria', # 14
	NS_CATEGORY_TALK    => 'Categoria_Discussão' # 15
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsPt = array(
	'Nenhuma', 'Fixo à esquerda', 'Fixo à direita', 'Flutuando à esquerda', 'Flutuando à direita'
);

/* private */ $wgSkinNamesPt = array(
	'standard' => 'Clássico',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Azul colonial',
	'davinci' => 'DaVinci',
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
) + $wgSkinNamesEn;

# Whether to use user or default setting in Language::date()
/* private */ $wgDateFormatsPt = array(
	MW_DATE_DEFAULT => 'Sem preferência',
	MW_DATE_DMY => '16:12, 15 Janeiro 2001',
	MW_DATE_MDY => '16:12, Janeiro 15, 2001',
	MW_DATE_YMD => '16:12, 2001 Janeiro 15',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);


# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsPt = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#REDIRECT', '#redir'    ),
	MAG_NOTOC                => array( 0,    '__NOTOC__'              ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__'           ),
	MAG_TOC                  => array( 0,    '__TOC__'                ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__'      ),
	MAG_START                => array( 0,    '__START__'              ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH'           ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME'       ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV'     ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'             ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'         ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'            ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'            ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'       ),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES'          ),
	MAG_PAGENAME             => array( 1,    'PAGENAME'               ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE'              ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE'              ),
	MAG_MSG                  => array( 0,    'MSG:'                   ),
	MAG_SUBST                => array( 0,    'SUBST:'                 ),
	MAG_MSGNW                => array( 0,    'MSGNW:'                 ),
	MAG_END                  => array( 0,    '__END__'                ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'     ),
	MAG_IMG_MANUALTHUMB      => array( 1,    'thumbnail=$1', 'thumb=$1'),
	MAG_IMG_RIGHT            => array( 1,    'right', 'direita'       ),
	MAG_IMG_LEFT             => array( 1,    'left', 'esquerda'       ),
	MAG_IMG_NONE             => array( 1,    'none', 'nenhum'         ),
	MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre'       ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' ),
	MAG_INT                  => array( 0,    'INT:'                   ),
	MAG_SITENAME             => array( 1,    'SITENAME'               ),
	MAG_NS                   => array( 0,    'NS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'                 ),
	MAG_SERVERNAME           => array( 0,    'SERVERNAME'             ),
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH'             ),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'               ),
	MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__'),
	MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'),
	MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK'            ),
	MAG_CURRENTDOW           => array( 1,    'CURRENTDOW'             ),
	MAG_REVISIONID           => array( 1,    'REVISIONID'             ),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesPt.php');
}

class LanguagePt extends LanguageUtf8 {

	/**
	 * Portuguese numeric format is 123 456,78
	 */
	function separatorTransformTable() {
		return array(',' => ' ', '.' => ',' );
	}

	/**
	* Exports $wgNamespaceNamesPt
	* @return array
	*/
	function getNamespaces() {
		global $wgNamespaceNamesPt;
		return $wgNamespaceNamesPt;
	}

	/**
	* Exports $wgQuickbarSettingsPt
	* @return array
	*/
	function getQuickbarSettings() {
		global $wgQuickbarSettingsPt;
		return $wgQuickbarSettingsPt;
	}

	/**
	* Exports $wgSkinNamesPt
	* @return array
	*/
	function getSkinNames() {
		global $wgSkinNamesPt;
		return $wgSkinNamesPt;
	}

	/**
	* Exports $wgDateFormatsPt
	* @return array
	*/
	function getDateFormats() {
		global $wgDateFormatsPt;
		return $wgDateFormatsPt;
	}

	function getMessage( $key ) {
		global $wgAllMessagesPt;
		if ( isset( $wgAllMessagesPt[$key] ) ) {
			return $wgAllMessagesPt[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	* Exports $wgMagicWordsPt
	* @return array
	*/
	function getMagicWords()  {
		global $wgMagicWordsPt;
		return $wgMagicWordsPt;
	}
}
?>
