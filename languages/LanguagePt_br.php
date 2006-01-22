<?php
/** Brazilian Portugese (Portuguêsi do Brasil)
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );
/** Inherit some stuff from Portuguese: */
require_once( 'LanguagePt.php' );
#
# This translation was made by Yves Marques Junqueira
# and Rodrigo Calanca Nishino from Portuguese Wikipedia
#
/* private */ $wgNamespaceNamesPt_br = array(
	NS_MEDIA		=> "Media",
	NS_SPECIAL		=> "Especial",
	NS_MAIN 		=> "",
	NS_TALK  		=> "Discussão",
	NS_USER   		=> "Usuário",
	NS_USER_TALK   	=> "Usuário_Discussão",
	NS_PROJECT      => $wgMetaNamespace,
	NS_PROJECT_TALK   => "{$wgMetaNamespace}_Discussão",
	NS_IMAGE   		=> "Imagem",
	NS_IMAGE_TALK   	=> "Imagem_Discussão",
	NS_MEDIAWIKI   	=> "MediaWiki",
	NS_MEDIAWIKI_TALK   => "MediaWiki_Discussão",
	NS_TEMPLATE  	=> "Predefinição",
	NS_TEMPLATE_TALK  	=> "Predefinição_Discussão",
	NS_HELP		=> "Ajuda",
	NS_HELP_TALK	=> "Ajuda_Discussão",
	NS_CATEGORY		=> "Categoria",
	NS_CATEGORY_TALK	=> "Categoria_Discussão"

) + $wgNamespaceNamesPt;

/* private */ $wgQuickbarSettingsPt_br = array(
	"Nada", "Fixado �  esquerda", "Fixado �  direita", "Flutuando �  Esquerda"
);

/* private */ $wgSkinNamesPt_br = array(
	'standard' => "Padrão",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Azul Colonial"
) + $wgSkinNamesPt;

/* private */ $wgDateFormatsPt_br = array(
#	"Sem preferência",
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesPt_br.php');
}

class LanguagePt_br extends LanguagePt {
	function getMessage( $key ) {
		 global $wgAllMessagesPt_br;
		 if( isset( $wgAllMessagesPt_br[$key] ) ) {
			 return $wgAllMessagesPt_br[$key];
		 } else {
			 return parent::getMessage( $key );
		}
	}
}

?>
