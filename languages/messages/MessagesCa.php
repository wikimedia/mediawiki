<?php
/** Catalan (català)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Discussió',
	NS_USER             => 'Usuari',
	NS_USER_TALK        => 'Usuari_Discussió',
	NS_PROJECT_TALK     => '$1_Discussió',
	NS_FILE             => 'Fitxer',
	NS_FILE_TALK        => 'Fitxer_Discussió',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Discussió',
	NS_TEMPLATE         => 'Plantilla',
	NS_TEMPLATE_TALK    => 'Plantilla_Discussió',
	NS_HELP             => 'Ajuda',
	NS_HELP_TALK        => 'Ajuda_Discussió',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Categoria_Discussió',
);

$namespaceAliases = array(
	'Imatge' => NS_FILE,
	'Imatge_Discussió' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Usuaris_actius' ),
	'Allmessages'               => array( 'Missatges', 'MediaWiki' ),
	'Allpages'                  => array( 'Llista_de_pàgines' ),
	'Ancientpages'              => array( 'Pàgines_velles' ),
	'Badtitle'                  => array( 'Títol_incorrecte' ),
	'Blankpage'                 => array( 'Pàgina_en_blanc', 'Blanc' ),
	'Block'                     => array( 'Bloca' ),
	'Booksources'               => array( 'Fonts_bibliogràfiques' ),
	'BrokenRedirects'           => array( 'Redireccions_rompudes' ),
	'ChangeEmail'               => array( 'Canvia_adreça_electrònica' ),
	'ChangePassword'            => array( 'Reinicia_contrasenya' ),
	'Confirmemail'              => array( 'Confirma_adreça' ),
	'Contributions'             => array( 'Contribucions' ),
	'CreateAccount'             => array( 'Crea_compte' ),
	'Deadendpages'              => array( 'Atzucacs' ),
	'DeletedContributions'      => array( 'Contribucions_esborrades' ),
	'DoubleRedirects'           => array( 'Redireccions_dobles' ),
	'Emailuser'                 => array( 'Envia_missatge' ),
	'ExpandTemplates'           => array( 'Expansió_de_plantilles' ),
	'Export'                    => array( 'Exporta' ),
	'Fewestrevisions'           => array( 'Pàgines_menys_editades' ),
	'FileDuplicateSearch'       => array( 'Cerca_fitxers_duplicats' ),
	'Import'                    => array( 'Importa' ),
	'BlockList'                 => array( 'Usuaris_blocats' ),
	'LinkSearch'                => array( 'Enllaços_web', 'Busca_enllaços', 'Recerca_d\'enllaços_web' ),
	'Listadmins'                => array( 'Administradors' ),
	'Listbots'                  => array( 'Bots' ),
	'Listfiles'                 => array( 'Imatges', 'Fitxers' ),
	'Listgrouprights'           => array( 'Drets_dels_grups_d\'usuaris' ),
	'Listredirects'             => array( 'Redireccions' ),
	'Listusers'                 => array( 'Usuaris' ),
	'Lockdb'                    => array( 'Bloca_bd' ),
	'Log'                       => array( 'Registre' ),
	'Lonelypages'               => array( 'Pàgines_òrfenes' ),
	'Longpages'                 => array( 'Pàgines_llargues' ),
	'MergeHistory'              => array( 'Fusiona_historial' ),
	'MIMEsearch'                => array( 'Cerca_MIME' ),
	'Mostcategories'            => array( 'Pàgines_amb_més_categories' ),
	'Mostimages'                => array( 'Imatges_més_útils' ),
	'Mostlinked'                => array( 'Pàgines_més_enllaçades' ),
	'Mostlinkedcategories'      => array( 'Categories_més_útils' ),
	'Mostlinkedtemplates'       => array( 'Plantilles_més_útils' ),
	'Mostrevisions'             => array( 'Pàgines_més_editades' ),
	'Movepage'                  => array( 'Reanomena' ),
	'Mycontributions'           => array( 'Contribucions_pròpies' ),
	'Mypage'                    => array( 'Pàgina_personal' ),
	'Mytalk'                    => array( 'Discussió_personal' ),
	'Newimages'                 => array( 'Imatges_noves', 'Fitxers_nous' ),
	'Newpages'                  => array( 'Pàgines_noves' ),
	'Preferences'               => array( 'Preferències' ),
	'Prefixindex'               => array( 'Cerca_per_prefix' ),
	'Protectedpages'            => array( 'Pàgines_protegides' ),
	'Protectedtitles'           => array( 'Títols_protegits' ),
	'Randompage'                => array( 'Article_aleatori', 'Atzar', 'Aleatori' ),
	'Randomredirect'            => array( 'Redirecció_aleatòria' ),
	'Recentchanges'             => array( 'Canvis_recents' ),
	'Recentchangeslinked'       => array( 'Seguiment' ),
	'Revisiondelete'            => array( 'Esborra_versió' ),
	'Search'                    => array( 'Cerca' ),
	'Shortpages'                => array( 'Pàgines_curtes' ),
	'Specialpages'              => array( 'Pàgines_especials' ),
	'Statistics'                => array( 'Estadístiques' ),
	'Unblock'                   => array( 'Desbloca', 'Desbloqueja' ),
	'Uncategorizedcategories'   => array( 'Categories_sense_categoria' ),
	'Uncategorizedimages'       => array( 'Imatges_sense_categoria' ),
	'Uncategorizedpages'        => array( 'Pàgines_sense_categoria' ),
	'Uncategorizedtemplates'    => array( 'Plantilles_sense_categoria' ),
	'Undelete'                  => array( 'Restaura' ),
	'Unlockdb'                  => array( 'Desbloca_bd' ),
	'Unusedcategories'          => array( 'Categories_no_usades' ),
	'Unusedimages'              => array( 'Imatges_no_usades' ),
	'Unusedtemplates'           => array( 'Plantilles_no_usades' ),
	'Unwatchedpages'            => array( 'Pàgines_desateses' ),
	'Upload'                    => array( 'Carrega' ),
	'Userlogin'                 => array( 'Registre_i_entrada' ),
	'Userlogout'                => array( 'Finalitza_sessió' ),
	'Userrights'                => array( 'Drets' ),
	'Version'                   => array( 'Versió' ),
	'Wantedcategories'          => array( 'Categories_demanades' ),
	'Wantedfiles'               => array( 'Arxius_demanats' ),
	'Wantedpages'               => array( 'Pàgines_demanades' ),
	'Wantedtemplates'           => array( 'Plantilles_demanades' ),
	'Watchlist'                 => array( 'Llista_de_seguiment' ),
	'Whatlinkshere'             => array( 'Enllaços' ),
	'Withoutinterwiki'          => array( 'Sense_interwiki' ),
);

$magicWords = array(
	'numberofarticles'          => array( '1', 'NOMBRED\'ARTICLES', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NOMBRED\'ARXIUS', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NOMBRED\'USUARIS', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'NOMBRED\'EDICIONS', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'NOMDELAPLANA', 'PAGENAME' ),
	'img_right'                 => array( '1', 'dreta', 'right' ),
	'img_left'                  => array( '1', 'esquerra', 'left' ),
	'img_border'                => array( '1', 'vora', 'border' ),
	'img_link'                  => array( '1', 'enllaç=$1', 'link=$1' ),
	'displaytitle'              => array( '1', 'TÍTOL', 'DISPLAYTITLE' ),
	'language'                  => array( '0', '#IDIOMA:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'especial', 'special' ),
	'defaultsort'               => array( '1', 'ORDENA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'pagesize'                  => array( '1', 'MIDADELAPLANA', 'PAGESIZE' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i, j M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
);

$bookstoreList = array(
	'Catàleg Col·lectiu de les Universitats de Catalunya' => 'http://ccuc.cbuc.es/cgi-bin/vtls.web.gateway?searchtype=control+numcard&searcharg=$1',
	'Totselsllibres.com' => 'http://www.totselsllibres.com/tel/publi/busquedaAvanzadaLibros.do?ISBN=$1',
	'inherit' => true,
);

$linkTrail = "/^((?:[a-zàèéíòóúç·ïü]|'(?!'))+)(.*)$/sDu";

