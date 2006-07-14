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
	'redirect'               => array( 0,    '#REDIRECT', '#redir'    ),
	'notoc'                  => array( 0,    '__NOTOC__'              ),
	'forcetoc'               => array( 0,    '__FORCETOC__'           ),
	'toc'                    => array( 0,    '__TOC__'                ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__'      ),
	'start'                  => array( 0,    '__START__'              ),
	'currentmonth'           => array( 1,    'CURRENTMONTH'           ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME'       ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV'     ),
	'currentday'             => array( 1,    'CURRENTDAY'             ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME'         ),
	'currentyear'            => array( 1,    'CURRENTYEAR'            ),
	'currenttime'            => array( 1,    'CURRENTTIME'            ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES'       ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES'          ),
	'pagename'               => array( 1,    'PAGENAME'               ),
	'pagenamee'              => array( 1,    'PAGENAMEE'              ),
	'namespace'              => array( 1,    'NAMESPACE'              ),
	'msg'                    => array( 0,    'MSG:'                   ),
	'subst'                  => array( 0,    'SUBST:'                 ),
	'msgnw'                  => array( 0,    'MSGNW:'                 ),
	'end'                    => array( 0,    '__END__'                ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'     ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'right', 'direita'       ),
	'img_left'               => array( 1,    'left', 'esquerda'       ),
	'img_none'               => array( 1,    'none', 'nenhum'         ),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre'       ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' ),
	'int'                    => array( 0,    'INT:'                   ),
	'sitename'               => array( 1,    'SITENAME'               ),
	'ns'                     => array( 0,    'NS:'                    ),
	'localurl'               => array( 0,    'LOCALURL:'              ),
	'localurle'              => array( 0,    'LOCALURLE:'             ),
	'server'                 => array( 0,    'SERVER'                 ),
	'servername'             => array( 0,    'SERVERNAME'             ),
	'scriptpath'             => array( 0,    'SCRIPTPATH'             ),
	'grammar'                => array( 0,    'GRAMMAR:'               ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1,    'CURRENTWEEK'            ),
	'currentdow'             => array( 1,    'CURRENTDOW'             ),
	'revisionid'             => array( 1,    'REVISIONID'             ),
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
	function &getMagicWords()  {
		global $wgMagicWordsPt;
		return $wgMagicWordsPt;
	}
}
?>
