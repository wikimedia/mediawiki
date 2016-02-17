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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Imago' => NS_FILE,
	'Disputatio_Imaginis' => NS_FILE_TALK,
];

$separatorTransformTable = [ ',' => "\xc2\xa0" ];

$dateFormats = [
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
];

$specialPageAliases = [
	'Allmessages'               => [ 'Nuntia_systematis' ],
	'Allpages'                  => [ 'Paginae_omnes', 'Omnes_paginae' ],
	'Ancientpages'              => [ 'Paginae_veterrimae' ],
	'Blankpage'                 => [ 'Pagina_vacua' ],
	'Block'                     => [ 'Usorem_obstruere' ],
	'Booksources'               => [ 'Librorum_fontes' ],
	'BrokenRedirects'           => [ 'Redirectiones_fractae' ],
	'Categories'                => [ 'Categoriae' ],
	'ChangePassword'            => [ 'Tesseram_novam_creare' ],
	'Confirmemail'              => [ 'Inscriptionem_electronicam_confirmare' ],
	'Contributions'             => [ 'Conlationes', 'Conlationes_usoris' ],
	'CreateAccount'             => [ 'Rationem_creare' ],
	'Deadendpages'              => [ 'Paginae_sine_nexu' ],
	'DeletedContributions'      => [ 'Conlationes_deletae', 'Conlationes_usoris_deletae' ],
	'DoubleRedirects'           => [ 'Redirectiones_duplices' ],
	'Emailuser'                 => [ 'Litteras_electronicas_usori_mittere', 'Littera_electronica' ],
	'ExpandTemplates'           => [ 'Formulas_resolvere' ],
	'Export'                    => [ 'Exportare', 'Paginas_exportare' ],
	'Fewestrevisions'           => [ 'Paginae_minime_mutatae' ],
	'FileDuplicateSearch'       => [ 'Quaerere_fasciculos_duplices', 'Quaerere_imagines_duplices' ],
	'Import'                    => [ 'Importare', 'Paginas_importare' ],
	'Invalidateemail'           => [ 'Adfimationem_inscriptionis_electronicae_abrogare' ],
	'BlockList'                 => [ 'Usores_obstructi' ],
	'LinkSearch'                => [ 'Quaerere_nexus_externos' ],
	'Listadmins'                => [ 'Magistratus' ],
	'Listbots'                  => [ 'Automata' ],
	'Listfiles'                 => [ 'Fasciculi', 'Imagines' ],
	'Listgrouprights'           => [ 'Gregum_usorum_potestates', 'Iura_gregum' ],
	'Listredirects'             => [ 'Redirectiones' ],
	'Listusers'                 => [ 'Usores' ],
	'Lockdb'                    => [ 'Basem_datorum_obstruere' ],
	'Log'                       => [ 'Acta' ],
	'Lonelypages'               => [ 'Paginae_non_annexae' ],
	'Longpages'                 => [ 'Paginae_longae' ],
	'MergeHistory'              => [ 'Historias_paginarum_confundere' ],
	'MIMEsearch'                => [ 'Quaerere_per_MIME' ],
	'Mostcategories'            => [ 'Paginae_plurimis_categoriis' ],
	'Mostimages'                => [ 'Fasciculi_maxime_annexi', 'Imagines_maxime_annexae' ],
	'Mostlinked'                => [ 'Paginae_maxime_annexae' ],
	'Mostlinkedcategories'      => [ 'Categoriae_maxime_annexae' ],
	'Mostlinkedtemplates'       => [ 'Formulae_maxime_annexae' ],
	'Mostrevisions'             => [ 'Paginae_plurimum_mutatae' ],
	'Movepage'                  => [ 'Paginam_movere', 'Movere' ],
	'Mycontributions'           => [ 'Conlationes_meae' ],
	'Mypage'                    => [ 'Pagina_mea' ],
	'Mytalk'                    => [ 'Disputatio_mea' ],
	'Newimages'                 => [ 'Fasciculi_novi', 'Imagines_novae' ],
	'Newpages'                  => [ 'Paginae_novae' ],
	'Preferences'               => [ 'Praeferentiae' ],
	'Prefixindex'               => [ 'Praefixa', 'Quaerere_per_praefixa' ],
	'Protectedpages'            => [ 'Paginae_protectae' ],
	'Protectedtitles'           => [ 'Tituli_protecti' ],
	'Randompage'                => [ 'Pagina_fortuita' ],
	'Randomredirect'            => [ 'Redirectio_fortuita' ],
	'Recentchanges'             => [ 'Nuper_mutata', 'Mutationes_recentes' ],
	'Recentchangeslinked'       => [ 'Nuper_mutata_annexorum' ],
	'Redirect'                  => [ 'Redirectio' ],
	'Revisiondelete'            => [ 'Emendationem_delere' ],
	'Search'                    => [ 'Quaerere' ],
	'Shortpages'                => [ 'Paginae_breves' ],
	'Specialpages'              => [ 'Paginae_speciales' ],
	'Statistics'                => [ 'Census' ],
	'Uncategorizedcategories'   => [ 'Categoriae_sine_categoriis' ],
	'Uncategorizedimages'       => [ 'Fasciculi_sine_categoriis', 'Imagines_sine_categoriis' ],
	'Uncategorizedpages'        => [ 'Paginae_sine_categoriis' ],
	'Uncategorizedtemplates'    => [ 'Formulae_sine_categoriis' ],
	'Undelete'                  => [ 'Paginam_restituere' ],
	'Unlockdb'                  => [ 'Basem_datorum_deobstruere' ],
	'Unusedcategories'          => [ 'Categoriae_non_in_usu', 'Categoriae_vacuae' ],
	'Unusedimages'              => [ 'Fasciculi_non_in_usu', 'Imagines_non_in_usu' ],
	'Unusedtemplates'           => [ 'Formulae_non_in_usu' ],
	'Unwatchedpages'            => [ 'Paginae_incustoditae' ],
	'Upload'                    => [ 'Fasciculos_onerare', 'Imagines_onerare' ],
	'Userlogin'                 => [ 'Conventum_aperire' ],
	'Userlogout'                => [ 'Conventum_concludere' ],
	'Userrights'                => [ 'Usorum_potestates', 'Iura_usorum' ],
	'Version'                   => [ 'Versio' ],
	'Wantedcategories'          => [ 'Categoriae_desideratae' ],
	'Wantedfiles'               => [ 'Fasciculi_desiderati', 'Imagines_desideratae' ],
	'Wantedpages'               => [ 'Paginae_desideratae', 'Nexus_fracti' ],
	'Wantedtemplates'           => [ 'Formulae_desideratae' ],
	'Watchlist'                 => [ 'Paginae_custoditae' ],
	'Whatlinkshere'             => [ 'Nexus_ad_paginam' ],
	'Withoutinterwiki'          => [ 'Paginae_sine_nexibus_ad_linguas_alias', 'Paginae_sine_nexibus_intervicis' ],
];

