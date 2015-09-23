<?php
/** Latin (Latina)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amahoney
 * @author Andrew Dalby
 * @author Autokrator
 * @author Candalua
 * @author Dferg
 * @author Esteban97
 * @author Geitost
 * @author Kaganer
 * @author LeighvsOptimvsMaximvs
 * @author MF-Warburg
 * @author McDutchie
 * @author MissPetticoats
 * @author Omnipaedista
 * @author OrbiliusMagister
 * @author Ornil
 * @author Rafaelgarcia
 * @author SPQRobin
 * @author UV
 * @author Žekřil71pl
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Specialis',
	NS_TALK             => 'Disputatio',
	NS_USER             => 'Usor',
	NS_USER_TALK        => 'Disputatio_Usoris',
	NS_PROJECT_TALK     => 'Disputatio_{{GRAMMAR:genitive|$1}}',
	NS_FILE             => 'Fasciculus',
	NS_FILE_TALK        => 'Disputatio_Fasciculi',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Disputatio_MediaWiki',
	NS_TEMPLATE         => 'Formula',
	NS_TEMPLATE_TALK    => 'Disputatio_Formulae',
	NS_HELP             => 'Auxilium',
	NS_HELP_TALK        => 'Disputatio_Auxilii',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Disputatio_Categoriae',
);

$namespaceAliases = array(
	'Imago' => NS_FILE,
	'Disputatio_Imaginis' => NS_FILE_TALK,
);

$separatorTransformTable = array( ',' => "\xc2\xa0" );

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Nuntia_systematis' ),
	'Allpages'                  => array( 'Paginae_omnes', 'Omnes_paginae' ),
	'Ancientpages'              => array( 'Paginae_veterrimae' ),
	'Blankpage'                 => array( 'Pagina_vacua' ),
	'Block'                     => array( 'Usorem_obstruere' ),
	'Booksources'               => array( 'Librorum_fontes' ),
	'BrokenRedirects'           => array( 'Redirectiones_fractae' ),
	'Categories'                => array( 'Categoriae' ),
	'ChangePassword'            => array( 'Tesseram_novam_creare' ),
	'Confirmemail'              => array( 'Inscriptionem_electronicam_confirmare' ),
	'Contributions'             => array( 'Conlationes', 'Conlationes_usoris' ),
	'CreateAccount'             => array( 'Rationem_creare' ),
	'Deadendpages'              => array( 'Paginae_sine_nexu' ),
	'DeletedContributions'      => array( 'Conlationes_deletae', 'Conlationes_usoris_deletae' ),
	'DoubleRedirects'           => array( 'Redirectiones_duplices' ),
	'Emailuser'                 => array( 'Litteras_electronicas_usori_mittere', 'Littera_electronica' ),
	'ExpandTemplates'           => array( 'Formulas_resolvere' ),
	'Export'                    => array( 'Exportare', 'Paginas_exportare' ),
	'Fewestrevisions'           => array( 'Paginae_minime_mutatae' ),
	'FileDuplicateSearch'       => array( 'Quaerere_fasciculos_duplices', 'Quaerere_imagines_duplices' ),
	'Import'                    => array( 'Importare', 'Paginas_importare' ),
	'Invalidateemail'           => array( 'Adfimationem_inscriptionis_electronicae_abrogare' ),
	'BlockList'                 => array( 'Usores_obstructi' ),
	'LinkSearch'                => array( 'Quaerere_nexus_externos' ),
	'Listadmins'                => array( 'Magistratus' ),
	'Listbots'                  => array( 'Automata' ),
	'Listfiles'                 => array( 'Fasciculi', 'Imagines' ),
	'Listgrouprights'           => array( 'Gregum_usorum_potestates', 'Iura_gregum' ),
	'Listredirects'             => array( 'Redirectiones' ),
	'Listusers'                 => array( 'Usores' ),
	'Lockdb'                    => array( 'Basem_datorum_obstruere' ),
	'Log'                       => array( 'Acta' ),
	'Lonelypages'               => array( 'Paginae_non_annexae' ),
	'Longpages'                 => array( 'Paginae_longae' ),
	'MergeHistory'              => array( 'Historias_paginarum_confundere' ),
	'MIMEsearch'                => array( 'Quaerere_per_MIME' ),
	'Mostcategories'            => array( 'Paginae_plurimis_categoriis' ),
	'Mostimages'                => array( 'Fasciculi_maxime_annexi', 'Imagines_maxime_annexae' ),
	'Mostlinked'                => array( 'Paginae_maxime_annexae' ),
	'Mostlinkedcategories'      => array( 'Categoriae_maxime_annexae' ),
	'Mostlinkedtemplates'       => array( 'Formulae_maxime_annexae' ),
	'Mostrevisions'             => array( 'Paginae_plurimum_mutatae' ),
	'Movepage'                  => array( 'Paginam_movere', 'Movere' ),
	'Mycontributions'           => array( 'Conlationes_meae' ),
	'Mypage'                    => array( 'Pagina_mea' ),
	'Mytalk'                    => array( 'Disputatio_mea' ),
	'Newimages'                 => array( 'Fasciculi_novi', 'Imagines_novae' ),
	'Newpages'                  => array( 'Paginae_novae' ),
	'Preferences'               => array( 'Praeferentiae' ),
	'Prefixindex'               => array( 'Praefixa', 'Quaerere_per_praefixa' ),
	'Protectedpages'            => array( 'Paginae_protectae' ),
	'Protectedtitles'           => array( 'Tituli_protecti' ),
	'Randompage'                => array( 'Pagina_fortuita' ),
	'Randomredirect'            => array( 'Redirectio_fortuita' ),
	'Recentchanges'             => array( 'Nuper_mutata', 'Mutationes_recentes' ),
	'Recentchangeslinked'       => array( 'Nuper_mutata_annexorum' ),
	'Redirect'                  => array( 'Redirectio' ),
	'Revisiondelete'            => array( 'Emendationem_delere' ),
	'Search'                    => array( 'Quaerere' ),
	'Shortpages'                => array( 'Paginae_breves' ),
	'Specialpages'              => array( 'Paginae_speciales' ),
	'Statistics'                => array( 'Census' ),
	'Uncategorizedcategories'   => array( 'Categoriae_sine_categoriis' ),
	'Uncategorizedimages'       => array( 'Fasciculi_sine_categoriis', 'Imagines_sine_categoriis' ),
	'Uncategorizedpages'        => array( 'Paginae_sine_categoriis' ),
	'Uncategorizedtemplates'    => array( 'Formulae_sine_categoriis' ),
	'Undelete'                  => array( 'Paginam_restituere' ),
	'Unlockdb'                  => array( 'Basem_datorum_deobstruere' ),
	'Unusedcategories'          => array( 'Categoriae_non_in_usu', 'Categoriae_vacuae' ),
	'Unusedimages'              => array( 'Fasciculi_non_in_usu', 'Imagines_non_in_usu' ),
	'Unusedtemplates'           => array( 'Formulae_non_in_usu' ),
	'Unwatchedpages'            => array( 'Paginae_incustoditae' ),
	'Upload'                    => array( 'Fasciculos_onerare', 'Imagines_onerare' ),
	'Userlogin'                 => array( 'Conventum_aperire' ),
	'Userlogout'                => array( 'Conventum_concludere' ),
	'Userrights'                => array( 'Usorum_potestates', 'Iura_usorum' ),
	'Version'                   => array( 'Versio' ),
	'Wantedcategories'          => array( 'Categoriae_desideratae' ),
	'Wantedfiles'               => array( 'Fasciculi_desiderati', 'Imagines_desideratae' ),
	'Wantedpages'               => array( 'Paginae_desideratae', 'Nexus_fracti' ),
	'Wantedtemplates'           => array( 'Formulae_desideratae' ),
	'Watchlist'                 => array( 'Paginae_custoditae' ),
	'Whatlinkshere'             => array( 'Nexus_ad_paginam' ),
	'Withoutinterwiki'          => array( 'Paginae_sine_nexibus_ad_linguas_alias', 'Paginae_sine_nexibus_intervicis' ),
);

