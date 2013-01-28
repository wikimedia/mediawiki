<?php
/** Franco-Provençal (arpetan)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Cedric31
 * @author ChrisPtDe
 * @author Reedy
 * @author לערי ריינהארט
 */

$fallback = 'fr';

$bookstoreList = array(
    'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
    'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
    'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
    'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

$namespaceNames = array(
	NS_MEDIA            => 'Mèdia',
	NS_SPECIAL          => 'Spèciâl',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utilisator',
	NS_USER_TALK        => 'Discussion_utilisator',
	NS_PROJECT_TALK     => 'Discussion_$1',
	NS_FILE             => 'Fichiér',
	NS_FILE_TALK        => 'Discussion_fichiér',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
	NS_TEMPLATE         => 'Modèlo',
	NS_TEMPLATE_TALK    => 'Discussion_modèlo',
	NS_HELP             => 'Éde',
	NS_HELP_TALK        => 'Discussion_éde',
	NS_CATEGORY         => 'Catègorie',
	NS_CATEGORY_TALK    => 'Discussion_catègorie',
);

$namespaceAliases = array(
	'Discutar'              => NS_TALK,
	'Usanciér'              => NS_USER,
	'Discussion_usanciér'   => NS_USER_TALK,
	'Émâge'                 => NS_FILE,
	'Discussion_Émâge'      => NS_FILE_TALK,
	'Discussion_Modèlo'     => NS_TEMPLATE_TALK,
	'Discussion_Éde'        => NS_HELP_TALK,
	'Discussion_Catègorie'  => NS_CATEGORY_TALK
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Usanciérs_actifs', 'UsanciérsActifs' ),
	'Allmessages'               => array( 'Mèssâjos_sistèmo', 'MèssâjosSistèmo' ),
	'Allpages'                  => array( 'Totes_les_pâges', 'TotesLesPâges' ),
	'Ancientpages'              => array( 'Pâges_les_muens_dèrriérement_changiês', 'PâgesLesMuensDèrriérementChangiês' ),
	'Blankpage'                 => array( 'Pâge_voueda', 'PâgeVoueda' ),
	'Block'                     => array( 'Blocar', 'Blocâjo' ),
	'Blockme'                   => array( 'Blocâd-mè', 'BlocâdMè' ),
	'Booksources'               => array( 'Ôvres_de_refèrence', 'ÔvresDeRefèrence' ),
	'BrokenRedirects'           => array( 'Redirèccions_câsses', 'RedirèccionsCâsses' ),
	'Categories'                => array( 'Catègories' ),
	'ChangePassword'            => array( 'Changement_de_contresegno', 'ChangementDeContresegno' ),
	'Confirmemail'              => array( 'Confirmar_l\'adrèce_èlèctronica', 'ConfirmarLAdrèceÈlèctronica' ),
	'Contributions'             => array( 'Contribucions' ),
	'CreateAccount'             => array( 'Fâre_un_compto', 'FâreUnCompto' ),
	'Deadendpages'              => array( 'Pâges_en_cul-de-sac', 'PâgesEnCulDeSac' ),
	'DeletedContributions'      => array( 'Contribucions_suprimâs', 'ContribucionsSuprimâs' ),
	'Disambiguations'           => array( 'Homonimia', 'Homonimies', 'Pâges_d\'homonimia', 'PâgesDHomonimia' ),
	'DoubleRedirects'           => array( 'Redirèccions_dobles', 'RedirèccionsDobles' ),
	'Emailuser'                 => array( 'Mandar_un_mèssâjo', 'MandarUnMèssâjo', 'Mèssâjo' ),
	'Export'                    => array( 'Èxportar', 'Èxportacion' ),
	'Fewestrevisions'           => array( 'Pâges_les_muens_changiês', 'PâgesLesMuensChangiês' ),
	'FileDuplicateSearch'       => array( 'Rechèrche_des_fichiérs_en_doblo', 'RechèrcheDesFichiérsEnDoblo' ),
	'Filepath'                  => array( 'Chemin_d\'accès_du_fichiér', 'CheminDAccèsDuFichiér' ),
	'Import'                    => array( 'Importar', 'Importacion' ),
	'Invalidateemail'           => array( 'Envalidar_l\'adrèce_èlèctronica', 'EnvalidarLAdrèceÈlèctronica' ),
	'BlockList'                 => array( 'Lista_des_blocâjos', 'ListaDesBlocâjos', 'Blocâjos', 'Usanciérs_blocâs', 'UsanciérsBlocâs', 'Adrèces_IP_blocâs', 'AdrècesIPBlocâs' ),
	'LinkSearch'                => array( 'Rechèrche_de_lims', 'RechèrcheDeLims' ),
	'Listadmins'                => array( 'Lista_ux_administrators', 'ListaUxAdministrators' ),
	'Listbots'                  => array( 'Lista_ux_bots', 'ListaUxBots' ),
	'Listfiles'                 => array( 'Lista_des_fichiérs', 'ListaDesFichiérs', 'Lista_de_les_émâges', 'ListaDeLesÉmâges' ),
	'Listgrouprights'           => array( 'Lista_des_drêts_a_les_tropes_d\'usanciérs', 'ListaDesDrêtsALesTropesDUsanciérs' ),
	'Listredirects'             => array( 'Lista_de_les_redirèccions', 'ListaDeLesRedirèccions' ),
	'Listusers'                 => array( 'Lista_ux_usanciérs', 'ListaUxUsanciérs', 'Usanciérs' ),
	'Lockdb'                    => array( 'Vèrrolyér_la_bâsa_de_balyês', 'VèrrolyérLaBâsaDeBalyês' ),
	'Log'                       => array( 'Jornal', 'Jornals' ),
	'Lonelypages'               => array( 'Pâges_orfenes', 'PâgesOrfenes' ),
	'Longpages'                 => array( 'Pâges_longes', 'PâgesLonges' ),
	'MergeHistory'              => array( 'Fusionar_los_historicos', 'FusionarLosHistoricos' ),
	'MIMEsearch'                => array( 'Rechèrche_per_tipo_de_contegnu_MIME', 'RechèrchePerTipoDeContegnuMIME' ),
	'Mostcategories'            => array( 'Pâges_utilisent_lo_més_de_catègories', 'PâgesUtilisentLoMésDeCatègories' ),
	'Mostimages'                => array( 'Fichiérs_los_ples_liyês', 'FichiérsLosPlesLiyês', 'Fichiérs_los_ples_utilisâs', 'FichiérsLosPlesUtilisâs', 'Émâges_les_ples_liyês', 'ÉmâgesLesPlesLiyês', 'Émâges_les_ples_utilisâs', 'ÉmâgesLesPlesUtilisâs' ),
	'Mostlinked'                => array( 'Pâges_les_ples_liyês', 'PâgesLesPlesLiyês' ),
	'Mostlinkedcategories'      => array( 'Catègories_les_ples_liyês', 'CatègoriesLesPlesLiyês', 'Catègories_les_ples_utilisâs', 'CatègoriesLesPlesUtilisâs' ),
	'Mostlinkedtemplates'       => array( 'Modèlos_los_ples_liyês', 'ModèlosLosPlesLiyês', 'Modèlos_los_ples_utilisâs', 'ModèlosLosPlesUtilisâs' ),
	'Mostrevisions'             => array( 'Pâges_les_ples_changiês', 'PâgesLesPlesChangiês' ),
	'Movepage'                  => array( 'Renomar_una_pâge', 'RenomarUnaPâge', 'Changement_de_nom', 'ChangementDeNom' ),
	'Mycontributions'           => array( 'Mes_contribucions', 'MesContribucions' ),
	'Mypage'                    => array( 'Ma_pâge', 'MaPâge' ),
	'Mytalk'                    => array( 'Mes_discussions', 'MesDiscussions' ),
	'Newimages'                 => array( 'Novéls_fichiérs', 'NovélsFichiérs', 'Émâges_novèles', 'ÉmâgesNovèles' ),
	'Newpages'                  => array( 'Pâges_novèles', 'PâgesNovèles' ),
	'PermanentLink'             => array( 'Lim_fixo', 'LimFixo' ),
	'Popularpages'              => array( 'Pâges_les_ples_consultâs', 'PâgesLesPlesConsultâs' ),
	'Preferences'               => array( 'Prèferences' ),
	'Prefixindex'               => array( 'Endèxe_des_prèfixos', 'EndèxeDesPrèfixos' ),
	'Protectedpages'            => array( 'Pâges_protègiês', 'PâgesProtègiês' ),
	'Protectedtitles'           => array( 'Titros_protègiês', 'TitrosProtègiês' ),
	'Randompage'                => array( 'Pâge_a_l\'hasârd', 'PâgeALHasârd' ),
	'Randomredirect'            => array( 'Redirèccion_a_l\'hasârd', 'RedirèccionALHasârd' ),
	'Recentchanges'             => array( 'Dèrriérs_changements', 'DèrriérsChangements' ),
	'Recentchangeslinked'       => array( 'Survelyence_des_lims', 'SurvelyenceDesLims' ),
	'Revisiondelete'            => array( 'Vèrsions_suprimâs', 'VèrsionsSuprimâs' ),
	'Search'                    => array( 'Rechèrchiér', 'Rechèrche' ),
	'Shortpages'                => array( 'Pâges_côrtes', 'PâgesCôrtes' ),
	'Specialpages'              => array( 'Pâges_spèciâles', 'PâgesSpèciâles' ),
	'Statistics'                => array( 'Statistiques' ),
	'Tags'                      => array( 'Balises' ),
	'Uncategorizedcategories'   => array( 'Catègories_sen_catègorie', 'CatègoriesSenCatègorie' ),
	'Uncategorizedimages'       => array( 'Fichiérs_sen_catègorie', 'FichiérsSenCatègorie', 'Émâges_sen_catègorie', 'ÉmâgesSenCatègorie' ),
	'Uncategorizedpages'        => array( 'Pâges_sen_catègorie', 'PâgesSenCatègorie' ),
	'Uncategorizedtemplates'    => array( 'Modèlos_sen_catègorie', 'ModèlosSenCatègorie' ),
	'Undelete'                  => array( 'Refâre', 'Rèstoracion' ),
	'Unlockdb'                  => array( 'Dèvèrrolyér_la_bâsa_de_balyês', 'DèvèrrolyérLaBâsaDeBalyês' ),
	'Unusedcategories'          => array( 'Catègories_inutilisâs', 'CatègoriesInutilisâs' ),
	'Unusedimages'              => array( 'Fichiérs_inutilisâs', 'FichiérsInutilisâs', 'Émâges_inutilisâs', 'ÉmâgesInutilisâs' ),
	'Unusedtemplates'           => array( 'Modèlos_inutilisâs', 'ModèlosInutilisâs' ),
	'Unwatchedpages'            => array( 'Pâges_pas_siuvues', 'PâgesPasSiuvues' ),
	'Upload'                    => array( 'Tèlèchargiér', 'Tèlèchargement' ),
	'Userlogin'                 => array( 'Branchiér', 'Branchement' ),
	'Userlogout'                => array( 'Dèbranchiér', 'Dèbranchement' ),
	'Userrights'                => array( 'Drêts_d\'usanciér', 'DrêtsDUsanciér' ),
	'Version'                   => array( 'Vèrsion' ),
	'Wantedcategories'          => array( 'Catègories_les_ples_demandâs', 'CatègoriesLesPlesDemandâs' ),
	'Wantedfiles'               => array( 'Fichiérs_los_ples_demandâs', 'FichiérsLosPlesDemandâs' ),
	'Wantedpages'               => array( 'Pâges_les_ples_demandâs', 'PâgesLesPlesDemandâs', 'Lims_câssos', 'LimsCâssos' ),
	'Wantedtemplates'           => array( 'Modèlos_los_ples_demandâs', 'ModèlosLosPlesDemandâs' ),
	'Watchlist'                 => array( 'Lista_de_survelyence', 'ListaDeSurvelyence', 'Survelyence' ),
	'Whatlinkshere'             => array( 'Pâges_liyês', 'PâgesLiyês' ),
	'Withoutinterwiki'          => array( 'Pâges_sen_lims_entèrlengoues', 'PâgesSenLimsEntèrlengoues', 'Pâges_sen_lims_entèrvouiqui', 'PâgesSenLimsEntèrvouiqui' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#REDIRÈCCION', '#REDIRECTION', '#REDIRECT' ),
	'notoc'                     => array( '0', '__NION_SOMÈRO__', '__NIONA_TRÂBLA__', '__AUCUNSOMMAIRE__', '__AUCUNETDM__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__NIONA_GALERIE__', '__AUCUNEGALERIE__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__FORCIÉR_LO_SOMÈRO__', '__FORCIÉR_LA_TRÂBLA__', '__FORCERSOMMAIRE__', '__FORCERTDM__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__SOMÈRO__', '__TRÂBLA__', '__SOMMAIRE__', '__TDM__', '__TOC__' ),
	'noeditsection'             => array( '0', '__SÈCCION_QUE_PÔT_PAS_ÉTRE_CHANGIÊ__', '__SECTIONNONEDITABLE__', '__NOEDITSECTION__' ),
	'noheader'                  => array( '0', '__NION_EN_TÉTA__', '__AUCUNENTETE__', '__NOHEADER__' ),
	'currentmonth'              => array( '1', 'MÊS_D_ORA', 'MÊS_D_ORA_2', 'MOISACTUEL', 'MOIS2ACTUEL', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'MÊS_D_ORA_1', 'MOIS1ACTUEL', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'NOM_DU_MÊS_D_ORA', 'NOMMOISACTUEL', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'GÈNITIF_DU_NOM_DU_MÊS_D_ORA', 'NOMGENMOISACTUEL', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'ABRÈV_DU_MÊS_D_ORA', 'ABREVMOISACTUEL', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'JORN_D_ORA', 'JOURACTUEL', 'JOUR1ACTUEL', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'JORN_D_ORA_2', 'JOUR2ACTUEL', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NOM_DU_JORN_D_ORA', 'NOMJOURACTUEL', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'AN_D_ORA', 'ANNEEACTUELLE', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'HORÈRO_D_ORA', 'HORAIREACTUEL', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'HORA_D_ORA', 'HEUREACTUELLE', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'MÊS_LOCAL', 'MÊS_LOCAL_2', 'MOISLOCAL', 'MOIS2LOCAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'MÊS_LOCAL_1', 'MOIS1LOCAL', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'NOM_DU_MÊS_LOCAL', 'NOMMOISLOCAL', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'GÈNITIF_DU_NOM_DU_MÊS_LOCAL', 'NOMGENMOISLOCAL', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'ABRÈV_DU_MÊS_LOCAL', 'ABREVMOISLOCAL', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'JORN_LOCAL', 'JOURLOCAL', 'JOUR1LOCAL', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'JORN_LOCAL_2', 'JOUR2LOCAL', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'NOM_DU_JORN_LOCAL', 'NOMJOURLOCAL', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'AN_LOCAL', 'ANNEELOCALE', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'HORÈRO_LOCAL', 'HORAIRELOCAL', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'HORA_LOCALA', 'HEURELOCALE', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'NOMBRO_DE_PÂGES', 'NOMBREPAGES', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'NOMBRO_D_ARTICLLOS', 'NOMBREARTICLES', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NOMBRO_DE_FICHIÉRS', 'NOMBREFICHIERS', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NOMBRO_D_USANCIÉRS', 'NOMBREUTILISATEURS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'NOMBRO_D_USANCIÉRS_ACTIFS', 'NOMBREUTILISATEURSACTIFS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'NOMBRO_DE_CHANGEMENTS', 'NOMBREMODIFS', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'NOMBRO_DE_VUES', 'NOMBREVUES', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'NOM_DE_LA_PÂGE', 'NOMPAGE', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'NOM_DE_LA_PÂGE_URL', 'NOMPAGEX', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ÈSPÂÇO_DE_NOMS', 'ESPACENOMMAGE', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ÈSPÂÇO_DE_NOMS_URL', 'ESPACENOMMAGEX', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'ÈSPÂÇO_DE_DISCUSSION', 'ESPACEDISCUSSION', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ÈSPÂÇO_DE_DISCUSSION_URL', 'ESPACEDISCUSSIONX', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ÈSPÂÇO_DU_SUJÈT', 'ÈSPÂÇO_DE_L_ARTICLLO', 'ESPACESUJET', 'ESPACEARTICLE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ÈSPÂÇO_DU_SUJÈT_URL', 'ÈSPÂÇO_DE_L_ARTICLLO_URL', 'ESPACESUJETX', 'ESPACEARTICLEX', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'NOM_COMPLÈT_DE_LA_PÂGE', 'NOMPAGECOMPLET', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'NOM_COMPLÈT_DE_LA_PÂGE_URL', 'NOMPAGECOMPLETX', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'NOM_DE_LA_SOT_PÂGE', 'NOMSOUSPAGE', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'NOM_DE_LA_SOT_PÂGE_URL', 'NOMSOUSPAGEX', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'NOM_DE_LA_PÂGE_DE_BÂSA', 'NOMBASEDEPAGE', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'NOM_DE_LA_PÂGE_DE_BÂSA_URL', 'NOMBASEDEPAGEX', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'NOM_DE_LA_PÂGE_DE_DISCUSSION', 'NOMPAGEDISCUSSION', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'NOM_DE_LA_PÂGE_DE_DISCUSSION_URL', 'NOMPAGEDISCUSSIONX', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'NOM_DE_LA_PÂGE_DU_SUJÈT', 'NOM_DE_LA_PÂGE_DE_L_ARTICLLO', 'NOMPAGESUJET', 'NOMPAGEARTICLE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'NOM_DE_LA_PÂGE_DU_SUJÈT_URL', 'NOM_DE_LA_PÂGE_DE_L_ARTICLLO_URL', 'NOMPAGESUJETX', 'NOMPAGEARTICLEX', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'MSJ:', 'MSG:' ),
	'msgnw'                     => array( '0', 'MSJNV:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'figura', 'vignette', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'figura=$1', 'vignette=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'drêta', 'droite', 'right' ),
	'img_left'                  => array( '1', 'gôche', 'gauche', 'left' ),
	'img_none'                  => array( '1', 'vouedo', 'néant', 'neant', 'none' ),
	'img_center'                => array( '1', 'centrâ', 'centré', 'center', 'centre' ),
	'img_framed'                => array( '1', 'encâdrâ', 'câdro', 'cadre', 'encadré', 'encadre', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'sen_câdro', 'pas_encâdrâ', 'sans_cadre', 'non_encadré', 'non_encadre', 'frameless' ),
	'img_page'                  => array( '1', 'pâge=$1', 'pâge $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'drêt', 'drêt=$1', 'drêt $1', 'redresse', 'redresse=$1', 'redresse $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'bordura', 'bordure', 'border' ),
	'img_baseline'              => array( '1', 'legne_de_bâsa', 'ligne_de_base', 'base', 'baseline' ),
	'img_sub'                   => array( '1', 'segno', 'indice', 'ind', 'sub' ),
	'img_super'                 => array( '1', 'èxposent', 'èxp', 'exposant', 'exp', 'super', 'sup' ),
	'img_top'                   => array( '1', 'd\'amont', 'haut', 'top' ),
	'img_text_top'              => array( '1', 'tèxto-d\'amont', 'haut-texte', 'haut-txt', 'text-top' ),
	'img_middle'                => array( '1', 'entre-mié', 'milieu', 'middle' ),
	'img_bottom'                => array( '1', 'd\'avâl', 'bas', 'bottom' ),
	'img_text_bottom'           => array( '1', 'tèxto-d\'avâl', 'bas-texte', 'bas-txt', 'text-bottom' ),
	'img_link'                  => array( '1', 'lim=$1', 'lien=$1', 'link=$1' ),
	'int'                       => array( '0', 'ENT:', 'INT:' ),
	'sitename'                  => array( '1', 'NOM_DU_SETO', 'NOMSITE', 'SITENAME' ),
	'ns'                        => array( '0', 'ÈDN:', 'ESPACEN:', 'NS:' ),
	'nse'                       => array( '0', 'ÈDN_URL:', 'ESPACENX:', 'NSE:' ),
	'localurl'                  => array( '0', 'URL_LOCALA:', 'URLLOCALE:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'URL_LOCALA_URL:', 'URLLOCALEX:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'CHEMIN_DE_L_ARTICLLO', 'CHEMINARTICLE', 'ARTICLEPATH' ),
	'server'                    => array( '0', 'SÈRVOR', 'SERVEUR', 'SERVER' ),
	'servername'                => array( '0', 'NOM_DU_SÈRVOR', 'NOMSERVEUR', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'CHEMIN_DU_SCRIPTE', 'CHEMINSCRIPT', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'CHEMIN_DU_STILO', 'CHEMINSTYLE', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'GRAMÈRE:', 'GRAMMAIRE:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'GENRO:', 'GENRE:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__SEN_CONVÈRSION_DE_TITRO__', '__SENCDT__', '__SANSCONVERSIONTITRE__', '__SANSCT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__SEN_CONVÈRSION_DE_CONTEGNU__', '__SENCDC__', '__SANSCONVERSIONCONTENU__', '__SANSCC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'SEMANA_D_ORA', 'SEMAINEACTUELLE', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'JDS_D_ORA', 'JDSACTUEL', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'SEMANA_LOCALA', 'SEMAINELOCALE', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'JDS_LOCAL', 'JDSLOCAL', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'NUMERÔ_DE_LA_VÈRSION', 'IDVERSION', 'REVISIONID' ),
	'revisionday'               => array( '1', 'JORN_DE_LA_VÈRSION', 'JOURVERSION', 'JOUR1VERSION', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'JORN_DE_LA_VÈRSION_2', 'JOUR2VERSION', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'MÊS_DE_LA_VÈRSION', 'MOISVERSION', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'MÊS_DE_LA_VÈRSION_1', 'MOISVERSION1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'AN_DE_LA_VÈRSION', 'ANNEEVERSION', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'DÂTA_ET_HORA_DE_LA_VÈRSION', 'INSTANTVERSION', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'USANCIÉR_DE_LA_VÈRSION', 'UTILISATEURVERSION', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'PLURÂL:', 'PLURIEL:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'URL_COMPLÈTA:', 'URLCOMPLETE:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'URL_COMPLÈTA_URL:', 'URLCOMPLETEX:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'PREMIÉRE_PETIÔTA_LÈTRA:', 'INITMINUS:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'PREMIÉRE_GRANTA_LÈTRA:', 'INITMAJUS:', 'INITCAPIT:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'PETIÔTA_LÈTRA:', 'MINUS:', 'LC:' ),
	'uc'                        => array( '0', 'GRANTA_LÈTRA:', 'MAJUS:', 'CAPIT:', 'UC:' ),
	'raw'                       => array( '0', 'BRUTO:', 'BRUT:', 'RAW:' ),
	'displaytitle'              => array( '1', 'FÂRE_VÊRE_LO_TITRO', 'AFFICHERTITRE', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'B', 'BRUT', 'R' ),
	'newsectionlink'            => array( '1', '__LIM_DE_NOVÈLA_SÈCCION__', '__LIENNOUVELLESECTION__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__NION_LIM_DE_NOVÈLA_SÈCCION__', '__AUCUNLIENNOUVELLESECTION__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'VÈRSION_D_ORA', 'VERSIONACTUELLE', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'URL_ENCODÂ:', 'ENCODEURL:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'ANCRO_ENCODÂ', 'ENCODEANCRE', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'DÂTA_ET_HORA_D_ORA', 'INSTANTACTUEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'DÂTA_ET_HORA_LOCALA', 'INSTANTLOCAL', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'MÂRCA_DE_DIRÈCCION', 'MARQUEDIRECTION', 'MARQUEDIR', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#LENGOUA:', '#LANGUE:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'LENGOUA_DU_CONTEGNU', 'LANGUECONTENU', 'LANGCONTENU', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'PÂGES_DENS_L_ÈSPÂÇO_DE_NOMS:', 'PÂGES_DENS_L_ÈDN:', 'PAGESDANSESPACE:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'NOMBRO_D_ADMINS', 'NOMBREADMINS', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FORMAT_NOMBRO', 'FORMATNOMBRE', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'BORRÂJO_A_GÔCHE', 'BOURRAGEGAUCHE', 'BOURREGAUCHE', 'PADLEFT' ),
	'padright'                  => array( '0', 'BORRÂJO_A_DRÊTA', 'BOURRAGEDROITE', 'BOURREDROITE', 'PADRIGHT' ),
	'special'                   => array( '0', 'spèciâl', 'spécial', 'special' ),
	'defaultsort'               => array( '1', 'CLLÂF_DE_TRI:', 'CLEFDETRI:', 'CLEDETRI:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'CHEMIN_D_ACCÈS:', 'CHEMIN:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'balisa', 'balise', 'tag' ),
	'hiddencat'                 => array( '1', '__CATÈGORIE_CACHIÊ__', '__CATCACHEE__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PÂGES_DENS_LA_CATÈGORIE', 'PAGESDANSCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'TALYE_DE_LA_PÂGE', 'TAILLEPAGE', 'PAGESIZE' ),
	'index'                     => array( '1', '__ENDÈXE__', '__INDEX__' ),
	'noindex'                   => array( '1', '__NION_ENDÈXE__', '__AUCUNINDEX__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'NOMBRO_D_USANCIÉRS_DENS_LA_TROPA', 'NOMBREDANSGROUPE', 'NBDANSGROUPE', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__REDIRÈCCION_IMOBILA__', '__REDIRECTIONSTATIQUE__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'NIVÉL_DE_PROTÈCCION', 'NIVEAUDEPROTECTION', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'format_de_dâta', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'CHEMIN', 'PATH' ),
	'url_wiki'                  => array( '0', 'VOUIQUI', 'WIKI' ),
);

$linkTrail = '/^([a-zàâçéèêîœôû·’æäåāăëēïīòöōùü‘]+)(.*)$/sDu';

$dateFormats = array(
    'mdy time' => 'H:i',
    'mdy date' => 'F j, Y',
    'mdy both' => 'F j, Y "a" H:i',

    'dmy time' => 'H:i',
    'dmy date' => 'j F Y',
    'dmy both' => 'j F Y "a" H:i',

    'ymd time' => 'H:i',
    'ymd date' => 'Y F j',
    'ymd both' => 'Y F j "a" H:i',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline' => 'Solegnér los lims :',
'tog-justify' => 'Justifiar los paragrafos',
'tog-hideminor' => 'Cachiér los petiôts changements dedens los dèrriérs changements',
'tog-hidepatrolled' => 'Cachiér los changements gouardâs dedens los dèrriérs changements',
'tog-newpageshidepatrolled' => 'Cachiér les pâges gouardâyes entre-mié la lista de les pâges novèles',
'tog-extendwatchlist' => 'Ètendre la lista de siuvu por montrar tôs los changements et pas ren que los ples novéls',
'tog-usenewrc' => 'Rassemblar los changements per pâge dedens los dèrriérs changements et la lista de siuvu (at fôta de JavaScript)',
'tog-numberheadings' => 'Numerotar ôtomaticament los titros de sèccion',
'tog-showtoolbar' => 'Montrar la bârra d’outils de changement (at fôta de JavaScript)',
'tog-editondblclick' => 'Changiér des pâges sur doblo-clic (at fôta de JavaScript)',
'tog-editsection' => 'Activar lo changement de sèccions avouéc los lims « [changiér] »',
'tog-editsectiononrightclick' => 'Activar lo changement de sèccions per clic drêt sur lors titros (at fôta de JavaScript)',
'tog-showtoc' => 'Montrar la trâbla de les matiéres (por les pâges qu’ont més de 3 sèccions)',
'tog-rememberpassword' => 'Sè rapelar de mon contresegno sur ceti navigator (por lo més $1 jorn{{PLURAL:$1||s}})',
'tog-watchcreations' => 'Apondre les pâges que fé et pués los fichiérs que tèlècharjo a ma lista de siuvu',
'tog-watchdefault' => 'Apondre les pâges et los fichiérs que chanjo a ma lista de siuvu',
'tog-watchmoves' => 'Apondre les pâges et los fichiérs que dèplaço a ma lista de siuvu',
'tog-watchdeletion' => 'Apondre les pâges et los fichiérs que suprimo a ma lista de siuvu',
'tog-minordefault' => 'Marcar per dèfôt tôs los changements coment petiôts',
'tog-previewontop' => 'Montrar l’apèrçu d’amont la zona de changement',
'tog-previewonfirst' => 'Montrar l’apèrçu pendent lo premiér changement',
'tog-nocache' => 'Dèsactivar lo cacho de les pâges per lo navigator',
'tog-enotifwatchlistpages' => 'Mè mandar un mèssâjo quand na pâge un fichiér de ma lista de siuvu est changiê(e)',
'tog-enotifusertalkpages' => 'Mè mandar un mèssâjo quand ma pâge de discussion est changiêe',
'tog-enotifminoredits' => 'Mè mandar un mèssâjo mémo en câs de petiôts changements de les pâges et des fichiérs',
'tog-enotifrevealaddr' => 'Rèvèlar mon adrèce èlèctronica dedens los mèssâjos de notificacion',
'tog-shownumberswatching' => 'Montrar lo nombro d’utilisators que siuvont na pâge',
'tog-oldsig' => 'Signatura ègzistenta :',
'tog-fancysig' => 'Trètar la signatura coment de vouiquitèxto (sen lim ôtomatico)',
'tog-externaleditor' => 'Empleyér per dèfôt un changior de tèxto de defôr (solament por los utilisators avanciês, at fôta d’una configuracion spèciâla sur voutron ordenator. [//www.mediawiki.org/wiki/Manual:External_editors Més d’enformacions.])',
'tog-externaldiff' => 'Empleyér per dèfôt un comparator de defôr (solament por los utilisators avanciês, at fôta d’una configuracion spèciâla sur voutron ordenator. [//www.mediawiki.org/wiki/Manual:External_editors Més d’enformacions.])',
'tog-showjumplinks' => 'Activar los lims d’accèssibilitât « {{int:jumpto}} »',
'tog-uselivepreview' => 'Empleyér l’apèrçu rapido (at fôta de JavaScript) (èxpèrimentâl)',
'tog-forceeditsummary' => 'M’avèrtir quand j’é pas buchiê de rèsumâ de changement',
'tog-watchlisthideown' => 'Cachiér los mins changements dedens la lista de siuvu',
'tog-watchlisthidebots' => 'Cachiér los changements fêts per des robots dedens la lista de siuvu',
'tog-watchlisthideminor' => 'Cachiér los petiôts changements dedens la lista de siuvu',
'tog-watchlisthideliu' => 'Cachiér los changements fêts per des utilisators branchiês dedens la lista de siuvu',
'tog-watchlisthideanons' => 'Cachiér los changements fêts per des utilisators anonimos dedens la lista de siuvu',
'tog-watchlisthidepatrolled' => 'Cachiér los changements gouardâs dedens la lista de siuvu',
'tog-ccmeonemails' => 'Mè mandar na copia des mèssâjos que mando ux ôtros utilisators',
'tog-diffonly' => 'Pas montrar lo contegnu de les pâges desot les difs',
'tog-showhiddencats' => 'Montrar les catègories cachiêes',
'tog-noconvertlink' => 'Dèsactivar la convèrsion des titros des lims',
'tog-norollbackdiff' => 'Pas fâre vêre la dif pendent na rèvocacion',

'underline-always' => 'Tojorn',
'underline-never' => 'Jamés',
'underline-default' => 'Valor de l’habelyâjo du navigator per dèfôt',

# Font style option in Special:Preferences
'editfont-style' => 'Stilo de police de la zona de changement :',
'editfont-default' => 'Police du navigator per dèfôt',
'editfont-monospace' => 'Police de chace fixa',
'editfont-sansserif' => 'Police sen empiotament',
'editfont-serif' => 'Police avouéc empiotament',

# Dates
'sunday' => 'demenge',
'monday' => 'delon',
'tuesday' => 'demârs',
'wednesday' => 'demécro',
'thursday' => 'dejô',
'friday' => 'devendro',
'saturday' => 'dessando',
'sun' => 'dg',
'mon' => 'dl',
'tue' => 'dr',
'wed' => 'dc',
'thu' => 'dj',
'fri' => 'dv',
'sat' => 'ds',
'january' => 'de janviér',
'february' => 'de fevriér',
'march' => 'de mârs',
'april' => 'd’avril',
'may_long' => 'de mê',
'june' => 'de jouen',
'july' => 'de julyèt',
'august' => 'd’oût',
'september' => 'de septembro',
'october' => 'd’octobro',
'november' => 'de novembro',
'december' => 'de dècembro',
'january-gen' => 'de janviér',
'february-gen' => 'de fevriér',
'march-gen' => 'de mârs',
'april-gen' => 'd’avril',
'may-gen' => 'de mê',
'june-gen' => 'de jouen',
'july-gen' => 'de julyèt',
'august-gen' => 'd’oût',
'september-gen' => 'de septembro',
'october-gen' => 'd’octobro',
'november-gen' => 'de novembro',
'december-gen' => 'de dècembro',
'jan' => 'jan',
'feb' => 'fev',
'mar' => 'mâr',
'apr' => 'avr',
'may' => 'mê',
'jun' => 'jou',
'jul' => 'jul',
'aug' => 'oût',
'sep' => 'sep',
'oct' => 'oct',
'nov' => 'nov',
'dec' => 'dèc',

# Categories related messages
'pagecategories' => 'Catègorie{{PLURAL:$1||s}}',
'category_header' => 'Pâges dedens la catègorie « $1 »',
'subcategories' => 'Sot-catègories',
'category-media-header' => 'Fichiérs mèdia dedens la catègorie « $1 »',
'category-empty' => "''Ora ceta catègorie contint gins de pâge de fichiér mèdia.''",
'hidden-categories' => '{{PLURAL:$1|Catègorie cachiêe|Catègories cachiêes}}',
'hidden-category-category' => 'Catègories cachiêes',
'category-subcat-count' => 'Cela catègorie-que at {{PLURAL:$2|ren que ceta sot-catègorie.|{{PLURAL:$1|ceta sot-catègorie|cetes $1 sot-catègories}}, sur na soma de $2.}}',
'category-subcat-count-limited' => 'Cela catègorie-que at {{PLURAL:$1|ceta sot-catègorie|cetes $1 sot-catègories}}.',
'category-article-count' => '{{PLURAL:$2|Cela catègorie-que contint ren que ceta pâge.|{{PLURAL:$1|Ceta pâge figure|Cetes $1 pâges figuront}} dedens cela catègorie-que, sur na soma de $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Ceta pâge figure|Cetes $1 pâges figuront}} dedens la presenta catègorie.',
'category-file-count' => '{{PLURAL:$2|Cela catègorie-que contint ren que ceti fichiér.|{{PLURAL:$1|Ceti fichiér figure|Cetos $1 fichiérs figuront}} dedens cela catègorie-que, sur na soma de $2.}}',
'category-file-count-limited' => '{{PLURAL:$1|Ceti fichiér figure|Cetos $1 fichiérs figuront}} dedens la presenta catègorie.',
'listingcontinuesabbrev' => '(suita)',
'index-category' => 'Pâges endèxâyes',
'noindex-category' => 'Pâges pas endèxâyes',
'broken-file-category' => 'Pâges avouéc des lims de fichiérs câssos',

'about' => 'A propôs',
'article' => 'Pâge de contegnu',
'newwindow' => '(ôvre na fenétra novèla)',
'cancel' => 'Anular',
'moredotdotdot' => 'Més...',
'mypage' => 'Pâge',
'mytalk' => 'Discussion',
'anontalk' => 'Discussion avouéc cet’adrèce IP',
'navigation' => 'Navigacion',
'and' => '&#32;et',

# Cologne Blue skin
'qbfind' => 'Trovar',
'qbbrowse' => 'Fâre dèfelar',
'qbedit' => 'Changiér',
'qbpageoptions' => 'Ceta pâge',
'qbpageinfo' => 'Contèxto',
'qbmyoptions' => 'Mes pâges',
'qbspecialpages' => 'Pâges spèciâles',
'faq' => 'Quèstions sovent posâyes',
'faqpage' => 'Project:Quèstions sovent posâyes',

# Vector skin
'vector-action-addsection' => 'Apondre na chousa',
'vector-action-delete' => 'Suprimar',
'vector-action-move' => 'Dèplaciér',
'vector-action-protect' => 'Protègiér',
'vector-action-undelete' => 'Refâre',
'vector-action-unprotect' => 'Changiér la protèccion',
'vector-simplesearch-preference' => 'Activar la bârra de rechèrche simplifiâye (solament por l’habelyâjo « Vèctor »)',
'vector-view-create' => 'Fâre',
'vector-view-edit' => 'Changiér',
'vector-view-history' => 'Vêre l’historico',
'vector-view-view' => 'Liére',
'vector-view-viewsource' => 'Vêre lo tèxto sôrsa',
'actions' => 'Accions',
'namespaces' => 'Èspâços de noms',
'variants' => 'Variantes',

'errorpagetitle' => 'Fôta',
'returnto' => 'Tornar a la pâge $1.',
'tagline' => 'De {{SITENAME}}',
'help' => 'Éde',
'search' => 'Rechèrche',
'searchbutton' => 'Rechèrchiér',
'go' => 'Alar trovar',
'searcharticle' => 'Liére',
'history' => 'Historico de la pâge',
'history_short' => 'Historico',
'updatedmarker' => 'betâye a jorn dês la mina dèrriére visita',
'printableversion' => 'Vèrsion emprimâbla',
'permalink' => 'Lim fixo',
'print' => 'Emprimar',
'view' => 'Liére',
'edit' => 'Changiér',
'create' => 'Fâre',
'editthispage' => 'Changiér ceta pâge',
'create-this-page' => 'Fâre cela pâge',
'delete' => 'Suprimar',
'deletethispage' => 'Suprimar ceta pâge',
'undelete_short' => 'Refâre {{PLURAL:$1|un changement|$1 changements}}',
'viewdeleted_short' => 'Vêre {{PLURAL:$1|un changement suprimâ|$1 changements suprimâs}}',
'protect' => 'Protègiér',
'protect_change' => 'changiér',
'protectthispage' => 'Protègiér ceta pâge',
'unprotect' => 'Changiér la protèccion',
'unprotectthispage' => 'Changiér la protèccion de ceta pâge',
'newpage' => 'Pâge novèla',
'talkpage' => 'Discussion sur ceta pâge',
'talkpagelinktext' => 'discutar',
'specialpage' => 'Pâge spèciâla',
'personaltools' => 'Outils a sè',
'postcomment' => 'Novèla sèccion',
'articlepage' => 'Vêde la pâge de contegnu',
'talk' => 'Discussion',
'views' => 'Vues',
'toolbox' => 'Bouèta d’outils',
'userpage' => 'Vêde la pâge utilisator',
'projectpage' => 'Vêde la pâge projèt',
'imagepage' => 'Vêde la pâge du fichiér',
'mediawikipage' => 'Vêde la pâge du mèssâjo',
'templatepage' => 'Vêde la pâge du modèlo',
'viewhelppage' => 'Vêde la pâge d’éde',
'categorypage' => 'Vêde la pâge de catègorie',
'viewtalkpage' => 'Vêde la pâge de discussion',
'otherlanguages' => 'Ôtres lengoues',
'redirectedfrom' => '(Redirigiêe dês $1)',
'redirectpagesub' => 'Pâge de redirèccion',
'lastmodifiedat' => 'Dèrriér changement de ceta pâge lo $1 a $2.',
'viewcount' => 'Ceta pâge est étâye vua {{PLURAL:$1|un côp|$1 côps}}.',
'protectedpage' => 'Pâge protègiêe',
'jumpto' => 'Alar a :',
'jumptonavigation' => 'navigacion',
'jumptosearch' => 'rechèrche',
'view-pool-error' => 'Dèconsolâ, los sèrviors sont lapidâs d’ôvra cetos temps.
Trop d’utilisators tâchont de vêre ceta pâge.
Volyéd atendre un moment devant que tornar tâchiér d’arrevar a ceta pâge.

$1',
'pool-timeout' => 'Dèlê dèpassâ pendent l’atenta du vèrroly',
'pool-queuefull' => 'La renche d’ôvra est plêna',
'pool-errorunknown' => 'Fôta encognua',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Sur {{SITENAME}}',
'aboutpage' => 'Project:A propôs',
'copyright' => 'Lo contegnu est disponiblo desot licence $1.',
'copyrightpage' => '{{ns:project}}:Drêts d’ôtor',
'currentevents' => 'Novèles',
'currentevents-url' => 'Project:Novèles',
'disclaimers' => 'Avèrtissements',
'disclaimerpage' => 'Project:Avèrtissements g·ènèrals',
'edithelp' => 'Éde',
'edithelppage' => 'Help:Coment changiér na pâge',
'helppage' => 'Help:Somèro',
'mainpage' => 'Reçua',
'mainpage-description' => 'Reçua',
'policy-url' => 'Project:Règlles de dedens',
'portal' => 'Comunôtât',
'portal-url' => 'Project:Reçua de la comunôtât',
'privacy' => 'Politica de confidencialitât',
'privacypage' => 'Project:Politica de confidencialitât',

'badaccess' => 'Fôta de pèrmission',
'badaccess-group0' => 'Vos éte pas ôtorisâ a fâre l’accion demandâye.',
'badaccess-groups' => 'L’accion demandâye est limitâye ux utilisators de {{PLURAL:$2|la tropa|yona de les tropes}} : $1.',

'versionrequired' => 'Vèrsion $1 de MediaWiki nècèssèra',
'versionrequiredtext' => 'La vèrsion $1 de MediaWiki est nècèssèra por empleyér ceta pâge.
Vêde la [[Special:Version|pâge de les vèrsions]].',

'ok' => 'D’acôrd',
'retrievedfrom' => 'Rècupèrâye de « $1 »',
'youhavenewmessages' => 'Vos avéd de $1 ($2).',
'newmessageslink' => 'mèssâjos novéls',
'newmessagesdifflink' => 'dèrriér changement',
'youhavenewmessagesfromusers' => 'Vos avéd $1 {{PLURAL:$3|d’un ôtr’utilisator|de $3 ôtros utilisators}} ($2).',
'youhavenewmessagesmanyusers' => 'Vos avéd $1 d’un mouél d’utilisators ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|un mèssâjo novél|de mèssâjos novéls}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|dèrriér changement|dèrriérs changements}}',
'youhavenewmessagesmulti' => 'Vos avéd de mèssâjos novéls sur $1',
'editsection' => 'changiér',
'editold' => 'changiér',
'viewsourceold' => 'vêre lo tèxto sôrsa',
'editlink' => 'changiér',
'viewsourcelink' => 'vêre lo tèxto sôrsa',
'editsectionhint' => 'Changiér la sèccion : $1',
'toc' => 'Somèro',
'showtoc' => 'montrar',
'hidetoc' => 'cachiér',
'collapsible-collapse' => 'repleyér',
'collapsible-expand' => 'dèpleyér',
'thisisdeleted' => 'Voléd-vos vêre ou ben refâre $1 ?',
'viewdeleted' => 'Vêre $1 ?',
'restorelink' => '{{PLURAL:$1|un changement suprimâ|$1 changements suprimâs}}',
'feedlinks' => 'Flux :',
'feed-invalid' => 'Tipo d’abonement du flux pas justo.',
'feed-unavailable' => 'Los flux de sindicacion sont pas disponiblos',
'site-rss-feed' => 'Flux RSS de $1',
'site-atom-feed' => 'Flux Atom de $1',
'page-rss-feed' => 'Flux RSS de « $1 »',
'page-atom-feed' => 'Flux Atom de « $1 »',
'red-link-title' => '$1 (pâge pas ègzistenta)',
'sort-descending' => 'Betar per ôrdre dèscendent',
'sort-ascending' => 'Betar per ôrdre montent',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Pâge',
'nstab-user' => 'Pâge utilisator',
'nstab-media' => 'Fichiér mèdia',
'nstab-special' => 'Pâge spèciâla',
'nstab-project' => 'Pâge projèt',
'nstab-image' => 'Fichiér',
'nstab-mediawiki' => 'Mèssâjo',
'nstab-template' => 'Modèlo',
'nstab-help' => 'Éde',
'nstab-category' => 'Catègorie',

# Main script and global functions
'nosuchaction' => 'Accion encognua',
'nosuchactiontext' => 'L’accion spècifiâye dens l’URL est pas justa.
Pôt-étre vos éd mâl-buchiê l’URL ou ben siuvu un lim fôx.
Pôt asse-ben étre quèstion d’una cofierie dedens la programeria empleyêe per {{SITENAME}}.',
'nosuchspecialpage' => 'Pâge spèciâla pas ègzistenta',
'nospecialpagetext' => '<strong>Vos éd demandâ na pâge spèciâla qu’ègziste pas.</strong>

Na lista de les pâges spèciâles justes sè trôve dessus [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Fôta',
'databaseerror' => 'Fôta de la bâsa de donâs',
'dberrortext' => 'Na fôta de sintaxa de la demanda dens la bâsa de donâs est arrevâye.
Cen pôt endicar na cofierie dedens la programeria.
La dèrriére demanda trètâye per la bâsa de donâs ére :
<blockquote><code>$1</code></blockquote>
dês la fonccion « <code>$2</code> ».
La bâsa de donâs at retornâ la fôta « <samp>$3 : $4</samp> ».',
'dberrortextcl' => 'Na fôta de sintaxa de la demanda dens la bâsa de donâs est arrevâye.
La dèrriére demanda trètâye per la bâsa de donâs ére :
« $1 »
dês la fonccion « $2 ».
La bâsa de donâs at retornâ la fôta « $3 : $4 ».',
'laggedslavemode' => "'''Atencion :''' cela pâge pôt pas contegnir tôs los dèrriérs changements fêts.",
'readonly' => 'Bâsa de donâs vèrrolyêe',
'enterlockreason' => 'Buchiéd na rêson du vèrroly et pués n’èstimacion de la sina durâ',
'readonlytext' => 'Ora la bâsa de donâs est vèrrolyêe por les entrâs novèles et los ôtros changements, de sûr por pèrmetre la sina mantegnence, dês cen tot tornerat en ôrdre.

L’administrator que l’at vèrrolyê at balyê cet’èxplicacion : $1',
'missing-article' => 'La bâsa de donâs at pas trovâ lo tèxto d’una pâge qu’el arêt diu trovar, apelâye « $1 » $2.

En g·ènèral cen arreve en siuvent un lim d’una dif d’un historico dèpassâ(ye) de vers na pâge qu’est étâye suprimâye.

S’o est pas lo câs, pôt étre quèstion d’una cofierie dedens la programeria.
La volyéd signalar a un [[Special:ListUsers/sysop|administrator]] sen oubliar de lui endicar l’URL du lim.',
'missingarticle-rev' => '(numerô de vèrsion : $1)',
'missingarticle-diff' => '(dif : $1, $2)',
'readonly_lag' => 'La bâsa de donâs est étâye vèrrolyêe ôtomaticament pendent que los sèrviors secondèros ratrapont lor retârd sur lo sèrvior principâl.',
'internalerror' => 'Fôta de dedens',
'internalerror_info' => 'Fôta de dedens : $1',
'fileappenderrorread' => 'Empossiblo de liére « $1 » pendent l’aponsa.',
'fileappenderror' => 'Empossiblo d’apondre « $1 » a « $2 ».',
'filecopyerror' => 'Empossiblo de copiyér lo fichiér « $1 » vers « $2 ».',
'filerenameerror' => 'Empossiblo de renomar lo fichiér « $1 » en « $2 ».',
'filedeleteerror' => 'Empossiblo de suprimar lo fichiér « $1 ».',
'directorycreateerror' => 'Empossiblo de fâre lo dossiér « $1 ».',
'filenotfound' => 'Empossiblo de trovar lo fichiér « $1 ».',
'fileexistserror' => 'Empossiblo d’ècrire lo fichiér « $1 » : lo fichiér ègziste.',
'unexpected' => 'Valor emprèvua : « $1 » = « $2 ».',
'formerror' => 'Fôta : empossiblo de sometre lo formulèro.',
'badarticleerror' => 'Cel’accion pôt pas étre fêta sur ceta pâge.',
'cannotdelete' => 'Empossiblo de suprimar la pâge lo fichiér « $1 ».
Pôt-étre la suprèssion est ja étâye fêta per un ôtro.',
'cannotdelete-title' => 'Empossiblo de suprimar la pâge « $1 »',
'delete-hook-aborted' => 'Suprèssion anulâye per un grèfon.
Nion’èxplicacion est étâye balyêe.',
'badtitle' => 'Crouyo titro',
'badtitletext' => 'Lo titro de la pâge demandâye est pas justo, vouedo ou ben o est un titro entèrlengoua ou entèrvouiqui mâl-liyê.
Contint sûrament yon ou ben un mouél de caractèros que pôvont pas étre empleyês dedens los titros.',
'perfcached' => 'Cetes donâs sont en cacho et pôvont pas étre a jorn. Por lo més {{PLURAL:$1|un rèsultat est disponiblo|$1 rèsultats sont disponiblos}} dedens lo cacho.',
'perfcachedts' => 'Cetes donâs sont en cacho et sont étâyes betâyes a jorn por lo dèrriér côp a $1. Por lo més {{PLURAL:$1|un rèsultat est disponiblo|$1 rèsultats sont disponiblos}} dedens lo cacho.',
'querypage-no-updates' => 'Ora les mises a jorn por ceta pâge sont dèsactivâyes.
Les donâs ique seront pas betâyes a jorn.',
'wrong_wfQuery_params' => 'Paramètros fôx dessus wfQuery()<br />
Fonccion : $1<br />
Demanda : $2',
'viewsource' => 'Vêre lo tèxto sôrsa',
'viewsource-title' => 'Vêre lo tèxto sôrsa de $1',
'actionthrottled' => 'Accion limitâye',
'actionthrottledtext' => 'Por combatre lo spame, l’usâjo de cel’accion est limitâ a doux-três côps dens un moment prod côrt. S’acomplét que vos éd dèpassâ ceta limita.
Volyéd tornar èprovar dens un tôrn.',
'protectedpagetext' => 'Ceta pâge est étâye protègiêe por empachiér son changement ou ben d’ôtres accions.',
'viewsourcetext' => 'Vos pouede vêre et pués copiyér lo tèxto sôrsa de ceta pâge :',
'viewyourtext' => "Vos pouede vêre et pués copiyér lo tèxto sôrsa de '''voutros changements''' a ceta pâge :",
'protectedinterface' => 'Cela pâge-que balye de tèxto d’entèrface por la programeria sur ceti vouiqui, et est vêr protègiêe por èvitar los abus.
Por apondre ou ben changiér des traduccions sur tôs los vouiquis, volyéd empleyér [//translatewiki.net/ translatewiki.net], lo projèt de localisacion de MediaWiki.',
'editinginterface' => "'''Atencion :''' vos éte aprés changiér na pâge empleyêe por fâre lo tèxto d’entèrface de la programeria.
Los changements sè cognetront sur l’aparence de l’entèrface utilisator por los ôtros utilisators de ceti vouiqui.
Por apondre ou ben changiér des traduccions sur tôs los vouiquis, volyéd empleyér [//translatewiki.net/ translatewiki.net], lo projèt de localisacion de MediaWiki.",
'sqlhidden' => '(Demanda SQL cachiêe)',
'cascadeprotected' => 'Cela pâge-que est protègiêe, el est entrebetâye dedens {{PLURAL:$1|ceta pâge qu’est étâye protègiêe|cetes pâges que sont étâyes protègiêes}} avouéc lo chouèx « protèccion en cascâda » activâ :
$2',
'namespaceprotected' => "Vos avéd pas la pèrmission de changiér les pâges de l’èspâço de noms « '''$1''' ».",
'customcssprotected' => 'Vos avéd pas la pèrmission de changiér cela pâge CSS, contint la configuracion a sè d’un ôtr’utilisator.',
'customjsprotected' => 'Vos avéd pas la pèrmission de changiér cela pâge JavaScript, contint la configuracion a sè d’un ôtr’utilisator.',
'ns-specialprotected' => 'Les pâges spèciâles pôvont pas étre changiêes.',
'titleprotected' => "Cél titro est étâ protègiê a la crèacion per [[User:$1|$1]].
La rêson balyêe est « ''$2'' ».",
'filereadonlyerror' => 'Empossiblo de changiér lo fichiér « $1 » perce que lo dèpôt de fichiérs « $2 » est en lèctura solèta.

L’administrator que l’at vèrrolyê at balyê cet’èxplicacion : « $3 ».',
'invalidtitle-knownnamespace' => 'Titro pas justo avouéc l’èspâço de noms « $2 » et lo tèxto « $3 »',
'invalidtitle-unknownnamespace' => 'Titro pas justo avouéc lo numerô d’èspâço de noms encognu $1 et lo tèxto « $2 »',
'exception-nologin' => 'Pas branchiê',
'exception-nologin-text' => 'Cela pâge cel’accion at fôta d’étre branchiê sur ceti vouiqui.',

# Virus scanner
'virus-badscanner' => "Crouye configuracion : scanor de virus encognu : ''$1''",
'virus-scanfailed' => 'Falyita de la rechèrche (code $1)',
'virus-unknownscanner' => 'antivirus encognu :',

# Login and logout pages
'logouttext' => "'''Ora vos éte dèbranchiê{{GENDER:||ye|(ye)}}.'''

Vos pouede continuar a empleyér {{SITENAME}} de façon anonima ou ben [[Special:UserLogin|vos tornar branchiér]] desot lo mémo nom ou un ôtro.
Notâd qu’y at des pâges que pôvont étre oncor fêtes vêre coment se vos érâd adés branchiê{{GENDER:||ye|(ye)}}, tant que vos èfaciéd lo cacho de voutron navigator.",
'welcomecreation' => '== Benvegnua, $1 ! ==
Voutron compto est étâ fêt.
Oubliâd pas de changiér voutres [[Special:Preferences|prèferences dessus {{SITENAME}}]].',
'yourname' => 'Nom d’utilisator :',
'yourpassword' => 'Contresegno :',
'yourpasswordagain' => 'Confirmâd lo contresegno :',
'remembermypassword' => 'Sè rapelar de mon contresegno sur ceti navigator (por lo més $1 jorn{{PLURAL:$1||s}})',
'securelogin-stick-https' => 'Réstar branchiê en HTTPS aprés lo branchement',
'yourdomainname' => 'Voutron domêno :',
'password-change-forbidden' => 'Vos pouede pas changiér los contresegnos sur ceti vouiqui.',
'externaldberror' => 'Ou ben na fôta est arrevâye avouéc la bâsa de donâs d’ôtentificacion de defôr ou ben vos éte pas ôtorisâ{{GENDER:||ye|(ye)}} a betar a jorn voutron compto de defôr.',
'login' => 'Branchement',
'nav-login-createaccount' => 'Sè branchiér / fâre un compto',
'loginprompt' => "Vos dête activar los tèmouens (''cookies'') por vos branchiér a {{SITENAME}}.",
'userlogin' => 'Sè branchiér / fâre un compto',
'userloginnocreate' => 'Sè branchiér',
'logout' => 'Sè dèbranchiér',
'userlogout' => 'Dèbranchement',
'notloggedin' => 'Pas branchiê',
'nologin' => "Vos avéd p’oncor un compto ? '''$1.'''",
'nologinlink' => 'Féte un compto',
'createaccount' => 'Fâre un compto',
'gotaccount' => "Vos avéd ja un compto ? '''$1.'''",
'gotaccountlink' => 'Branchiéd-vos',
'userlogin-resetlink' => 'Vos éd oubliâ voutros dètalys de branchement ?',
'createaccountmail' => 'per mèssageria èlèctronica',
'createaccountreason' => 'Rêson :',
'badretype' => 'Los contresegnos que vos éd buchiês sont pas pariérs.',
'userexists' => 'Lo nom d’utilisator buchiê est ja empleyê.
Nen volyéd chouèsir un ôtro.',
'loginerror' => 'Fôta de branchement',
'createaccounterror' => 'Empossiblo de fâre lo compto : $1',
'nocookiesnew' => "Lo compto utilisator est étâ fêt, mas vos éte pas branchiê{{GENDER:||e|(e)}}.
{{SITENAME}} emplèye des tèmouens (''cookies'') por lo branchement mas vos los éd dèsactivâs.
Los volyéd activar et pués vos tornar branchiér avouéc lo mémo nom et lo mémo contresegno.",
'nocookieslogin' => "{{SITENAME}} emplèye des tèmouens (''cookies'') por lo branchement mas vos los éd dèsactivâs.
Los volyéd activar et pués tornar èprovar.",
'nocookiesfornew' => "Lo compto utilisator est pas étâ fêt, nos ens pas possu confirmar la sina sôrsa.
Controlâd que vos éd activâ los tèmouens (''cookies''), rechargiéd la pâge et pués tornâd èprovar.",
'noname' => 'Vos éd pas spècifiâ un nom d’utilisator justo.',
'loginsuccesstitle' => 'Branchement reussi',
'loginsuccess' => "'''Ora vos éte branchiê{{GENDER:||e|(e)}} a {{SITENAME}} por « $1 ».'''",
'nosuchuser' => 'L’utilisator « $1 » ègziste pas.
Los noms d’utilisator sont sensiblos a la câssa.
Controlâd l’ortografia ou ben [[Special:UserLogin/signup|féte un compto novél]].',
'nosuchusershort' => 'Y at pas un utilisator avouéc lo nom « $1 ».
Volyéd controlar l’ortografia.',
'nouserspecified' => 'Vos dête spècifiar un nom d’utilisator.',
'login-userblocked' => 'Cet’utilisator est blocâ. Branchement pas ôtorisâ.',
'wrongpassword' => 'Lo contresegno buchiê est fôx.
Volyéd tornar èprovar.',
'wrongpasswordempty' => 'Vos éd pas buchiê de contresegno.
Volyéd tornar èprovar.',
'passwordtooshort' => 'Voutron contresegno dêt contegnir u muens $1 caractèro{{PLURAL:$1||s}}.',
'password-name-match' => 'Voutron contresegno dêt étre difèrent de voutron nom d’utilisator.',
'password-login-forbidden' => 'L’usâjo de cél nom d’utilisator et de cél contresegno est étâ dèfendu.',
'mailmypassword' => 'Recêvre un contresegno novél per mèssageria èlèctronica',
'passwordremindertitle' => 'Contresegno temporèro novél por {{SITENAME}}',
'passwordremindertext' => 'Yon (probâblament vos, dês l’adrèce IP $1) at demandâ un contresegno
novél por {{SITENAME}} ($4). Un contresegno temporèro est étâ fêt por
l’utilisator « $2 » et est « $3 ». S’o ére voutra entencion, vos vos devréd
branchiér et pués chouèsir un contresegno novél.
Voutron contresegno temporèro èxpirerat dens {{PLURAL:$5|un jorn|$5 jorns}}.

Se cela demanda vint pas de vos ou ben que vos vos éte rapelâ
de voutron contresegno et que vos souhètâd pas més lo changiér, vos
pouede ignorar ceti mèssâjo et continuar a empleyér voutron viely contresegno.',
'noemail' => 'Nion’adrèce èlèctronica est étâye encartâye por l’utilisator « $1 ».',
'noemailcreate' => 'Vos dête balyér n’adrèce èlèctronica justa',
'passwordsent' => 'Un contresegno novél est étâ mandâ a l’adrèce èlèctronica de l’utilisator « $1 ».
Vos volyéd tornar branchiér aprés l’avêr reçu.',
'blocked-mailpassword' => 'Voutron adrèce IP est blocâye en ècritura, la fonccion de sovegnence du contresegno est vêr dèsactivâye por èvitar los abus.',
'eauthentsent' => 'Un mèssâjo de confirmacion est étâ mandâ a l’adrèce èlèctronica endicâye.
Devant qu’un ôtro mèssâjo seye mandâ a ceti compto, vos devréd siuvre les enstruccions du mèssâjo et pués confirmar que lo compto est franc lo voutro.',
'throttled-mailpassword' => 'Un mèssâjo de sovegnence de voutron contresegno est ja étâ mandâ pendent {{PLURAL:$1|l’hora passâye|les $1 hores passâyes}}.
Por èvitar los abus, ren que yon serat mandâ per {{PLURAL:$1|hora|entèrvalo de $1 hores}}.',
'mailerror' => 'Fôta pendent l’èxpèdicion du mèssâjo : $1',
'acct_creation_throttle_hit' => 'Yon qu’emplèye voutron adrèce IP at fêt {{PLURAL:$1|un compto|$1 comptos}} pendent les 24 hores passâyes, cen qu’est la limita ôtorisâye dens ceti temps.
Du côp la crèacion de compto est étâye dèsactivâye temporèrament por cel’adrèce IP.',
'emailauthenticated' => 'Voutron adrèce èlèctronica est étâye ôtentifiâye lo $2 a $3.',
'emailnotauthenticated' => 'Voutron adrèce èlèctronica est p’oncor ôtentifiâye.
Nion mèssâjo serat mandâ por châcuna de cetes fonccionalitâts.',
'noemailprefs' => 'Spècifiâd n’adrèce èlèctronica dens voutres prèferences por empleyér cetes fonccionalitâts.',
'emailconfirmlink' => 'Confirmâd voutron adrèce èlèctronica',
'invalidemailaddress' => 'Cet’adrèce èlèctronica pôt pas étre accèptâye, semble avêr un format pas justo.
Volyéd buchiér n’adrèce bien formatâye ou ben lèssiér cél champ vouedo.',
'cannotchangeemail' => 'Les adrèces èlèctroniques des comptos pôvont pas étre changiêes sur ceti vouiqui.',
'emaildisabled' => 'Ceti seto pôt pas mandar des mèssâjos.',
'accountcreated' => 'Compto fêt',
'accountcreatedtext' => 'Lo compto utilisator por $1 est étâ fêt.',
'createaccount-title' => 'Crèacion d’un compto por {{SITENAME}}',
'createaccount-text' => 'Yon at fêt un compto por voutron adrèce èlèctronica dessus {{SITENAME}} ($4) apelâ « $2 », avouéc lo contresegno « $3 ».
Vos vos devriâd branchiér et pués changiér dês ora voutron contresegno.

Ignorâd ceti mèssâjo se cél compto est étâ fêt per fôta.',
'usernamehasherror' => 'Lo nom d’utilisator pôt pas contegnir des caractèros de chaplâjo',
'login-throttled' => 'Dês pou vos éd èprovâ un mouél de branchements.
Volyéd atendre devant que tornar èprovar.',
'login-abort-generic' => 'Voutra tentativa de branchement at pas reussi - Anulâye',
'loginlanguagelabel' => 'Lengoua : $1',
'suspicious-userlogout' => 'Voutra demanda de dèbranchement est étâye refusâye, semble qu’el est étâye mandâye per un navigator câsso ou ben la misa en cacho d’un proxi.',

# E-mail sending
'php-mail-error-unknown' => 'Fôta encognua dens la fonccion mail() de PHP.',
'user-mail-no-addy' => 'At tâchiê de mandar un mèssâjo sen adrèce èlèctronica.',

# Change password dialog
'resetpass' => 'Changiér lo contresegno',
'resetpass_announce' => 'Vos vos éte branchiê{{GENDER:||e|(e)}} avouéc un contresegno temporèro mandâ per mèssageria èlèctronica.
Por chavonar lo branchement, vos dête buchiér un contresegno novél ique :',
'resetpass_text' => '<!-- Apondéd lo tèxto ique -->',
'resetpass_header' => 'Changiér lo contresegno du compto',
'oldpassword' => 'Viely contresegno :',
'newpassword' => 'Contresegno novél :',
'retypenew' => 'Confirmar lo contresegno novél :',
'resetpass_submit' => 'Changiér lo contresegno et pués sè branchiér',
'resetpass_success' => 'Voutron contresegno est étâ changiê avouéc reusséta !
Branchement en cors...',
'resetpass_forbidden' => 'Los contresegnos pôvont pas étre changiês',
'resetpass-no-info' => 'Vos dête étre branchiê por arrevar tot drêt a cela pâge.',
'resetpass-submit-loggedin' => 'Changiér lo contresegno',
'resetpass-submit-cancel' => 'Anular',
'resetpass-wrong-oldpass' => 'Contresegno temporèro ou ben d’ora pas justo.
Pôt-étre vos éd ja changiê voutron contresegno avouéc reusséta ou ben demandâ un contresegno temporèro novél.',
'resetpass-temp-password' => 'Contresegno temporèro :',

# Special:PasswordReset
'passwordreset' => 'Remisa a zérô du contresegno',
'passwordreset-text' => 'Rempléd ceti formulèro por recêvre un mèssâjo de sovegnence des dètalys de voutron compto.',
'passwordreset-legend' => 'Remetre a zérô lo contresegno',
'passwordreset-disabled' => 'La remisa a zérô des contresegnos est étâye dèsactivâye sur ceti vouiqui.',
'passwordreset-pretext' => '{{PLURAL:$1||Buchiéd yona de les piéces de donâs ce-desot}}',
'passwordreset-username' => 'Nom d’utilisator :',
'passwordreset-domain' => 'Domêno :',
'passwordreset-capture' => 'Vêre lo mèssâjo que rèsulte ?',
'passwordreset-capture-help' => 'Se vos pouentâd cela câsa, lo mèssâjo (avouéc lo contresegno temporèro) vos serat montrâ quand serat mandâ a l’utilisator.',
'passwordreset-email' => 'Adrèce èlèctronica :',
'passwordreset-emailtitle' => 'Dètalys du compto dessus {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Yon (probâblament vos, dês l’adrèce IP $1) at demandâ na sovegnence des dètalys
de voutron compto por {{SITENAME}} ($4). {{PLURAL:$3|Ceti compto utilisator est associyê|Cetos comptos utilisators sont associyês}}
a cel’adrèce èlèctronica :

$2

{{PLURAL:$3|Cél contresegno temporèro èxpirerat|Celos contresegnos temporèros èxpireront}} dens {{PLURAL:$5|un jorn|$5 jorns}}.
Ora vos vos dête branchiér et pués chouèsir un contresegno novél. Se cela demanda vint pas de vos
ou ben que vos vos éte rapelâ de voutron contresegno originâl et que vos souhètâd pas més lo changiér,
vos pouede ignorar ceti mèssâjo et continuar a empleyér voutron viely contresegno.',
'passwordreset-emailtext-user' => 'L’utilisator $1 dessus {{SITENAME}} at demandâ na sovegnence des dètalys
de voutron compto por {{SITENAME}} ($4). {{PLURAL:$3|Ceti compto utilisator est associyê|Cetos comptos utilisators sont associyês}}
a cel’adrèce èlèctronica :

$2

{{PLURAL:$3|Cél contresegno temporèro èxpirerat|Celos contresegnos temporèros èxpireront}} dens {{PLURAL:$5|un jorn|$5 jorns}}.
Ora vos vos dête branchiér et pués chouèsir un contresegno novél. Se cela demanda vint pas de vos
ou ben que vos vos éte rapelâ de voutron contresegno originâl et que vos souhètâd pas més lo changiér,
vos pouede ignorar ceti mèssâjo et continuar a empleyér voutron viely contresegno.',
'passwordreset-emailelement' => 'Nom d’utilisator : $1
Contresegno temporèro : $2',
'passwordreset-emailsent' => 'Un mèssâjo de sovegnence est étâ mandâ.',
'passwordreset-emailsent-capture' => 'Un mèssâjo de sovegnence est étâ mandâ, qu’est montrâ ce-desot.',
'passwordreset-emailerror-capture' => 'Un mèssâjo de sovegnence est étâ fêt, qu’est montrâ ce-desot, mas l’èxpèdicion a l’utilisator at pas reussi : $1',

# Special:ChangeEmail
'changeemail' => 'Changiér l’adrèce èlèctronica',
'changeemail-header' => 'Changiér l’adrèce èlèctronica du compto',
'changeemail-text' => 'Rempléd ceti formulèro por changiér voutron adrèce èlèctronica. Vos devréd buchiér voutron contresegno por confirmar cél changement.',
'changeemail-no-info' => 'Vos dête étre branchiê por arrevar tot drêt a cela pâge.',
'changeemail-oldemail' => 'Adrèce èlèctronica d’ora :',
'changeemail-newemail' => 'Novèl’adrèce èlèctronica :',
'changeemail-none' => '(pas yona)',
'changeemail-submit' => 'Changiér l’adrèce èlèctronica',
'changeemail-cancel' => 'Anular',

# Edit page toolbar
'bold_sample' => 'Tèxto grâs',
'bold_tip' => 'Tèxto grâs',
'italic_sample' => 'Tèxto étalico',
'italic_tip' => 'Tèxto étalico',
'link_sample' => 'Titro du lim',
'link_tip' => 'Lim de dedens',
'extlink_sample' => 'http://www.example.com titro du lim',
'extlink_tip' => 'Lim de defôr (oubliâd pas lo prèfixo http://)',
'headline_sample' => 'Tèxto du titro',
'headline_tip' => 'Sot-titro nivél 2',
'nowiki_sample' => 'Buchiéd lo tèxto pas formatâ ique',
'nowiki_tip' => 'Ignorar lo formatâjo vouiqui',
'image_sample' => 'Ègzemplo.jpg',
'image_tip' => 'Fichiér apondu',
'media_sample' => 'Ègzemplo.ogg',
'media_tip' => 'Lim de vers un fichiér',
'sig_tip' => 'Voutra signatura avouéc la dâta et hora',
'hr_tip' => 'Legne plana (pas nen abusar)',

# Edit pages
'summary' => 'Rèsumâ :',
'subject' => 'Chousa / titro :',
'minoredit' => 'O est un petiôt changement',
'watchthis' => 'Siuvre ceta pâge',
'savearticle' => 'Encartar la pâge',
'preview' => 'Apèrçu',
'showpreview' => 'Montrar un apèrçu',
'showlivepreview' => 'Apèrçu drêt',
'showdiff' => 'Montrar los changements',
'anoneditwarning' => "'''Atencion :''' vos éte pas branchiê(e).
Voutron adrèce IP serat encartâye dedens l’historico des changements de ceta pâge.",
'anonpreviewwarning' => "''Vos éte pas branchiê(e). Sôvar encarterat voutron adrèce IP dedens l’historico des changements de ceta pâge.''",
'missingsummary' => "'''Sovegnence :''' vos éd balyê gins de rèsumâ de changement.
Se vos tornâd clicar sur lo boton « {{int:savearticle}} », voutron changement serat encartâ sen rèsumâ.",
'missingcommenttext' => 'Volyéd buchiér un comentèro ce-desot.',
'missingcommentheader' => "'''Sovegnence :''' vos éd balyê gins de chousa / titro a ceti comentèro.
Se vos tornâd clicar sur lo boton « {{int:savearticle}} », voutron changement serat encartâ sen chousa / titro.",
'summary-preview' => 'Apèrçu du rèsumâ :',
'subject-preview' => 'Apèrçu de la chousa / du titro :',
'blockedtitle' => 'L’utilisator est blocâ',
'blockedtext' => "'''Voutron nom d’utilisator voutron adrèce IP est étâ(ye) blocâ(ye).'''

Lo blocâjo est étâ fêt per $1.
La rêson balyêe est ''$2''.

* Comencement du blocâjo : $8
* Èxpiracion du blocâjo : $6
* Compto blocâ : $7

Vos vos pouede veriér vers $1 ou ben un ôtr’[[{{MediaWiki:Grouppage-sysop}}|administrator]] por nen discutar.
Vos pouede pas empleyér la fonccionalitât « Lui mandar un mèssâjo » a muens qu’un’adrèce èlèctronica justa est spècifiâye dens voutres [[Special:Preferences|prèferences]] et que vos éte pas étâ blocâ de l’empleyér.
Voutron adrèce IP d’ora est $3, et l’identifient de blocâjo est $5.
Volyéd entrebetar tôs los dètalys ce-dessus dedens na sé-quinta demanda que vos faréd.",
'autoblockedtext' => "Voutron adrèce IP est étâye blocâye ôtomaticament, el est étâye empleyêe per un ôtr’utilisator, lui-mémo blocâ per $1.
La rêson balyêe est :

:''$2''

* Comencement du blocâjo : $8
* Èxpiracion du blocâjo : $6
* Compto blocâ : $7

Vos vos pouede veriér vers $1 ou ben yon des ôtros [[{{MediaWiki:Grouppage-sysop}}|administrators]] por nen discutar.

Notâd que vos porréd pas empleyér la fonccionalitât « Lui mandar un mèssâjo » a muens que vos avéd n’adrèce èlèctronica justa encartâye dens voutres [[Special:Preferences|prèferences]] et que vos éte pas étâ blocâ de l’empleyér.

Voutron adrèce IP d’ora est $3, et l’identifient de blocâjo est $5.
Volyéd entrebetar tôs los dètalys ce-dessus dedens na sé-quinta demanda que vos faréd.",
'blockednoreason' => 'niona rêson balyêe',
'whitelistedittext' => 'Vos vos dête $1 por povêr changiér les pâges.',
'confirmedittext' => 'Vos dête confirmar voutron adrèce èlèctronica devant que changiér les pâges.
Volyéd buchiér et pués validar voutron adrèce èlèctronica dens voutres [[Special:Preferences|prèferences]].',
'nosuchsectiontitle' => 'Empossiblo de trovar la sèccion',
'nosuchsectiontext' => 'Vos éd tâchiê de changiér na sèccion qu’ègziste pas.
Pôt-étre el est étâye dèplaciêe ou ben ôtâye dês que vos éd liesu cela pâge.',
'loginreqtitle' => 'Branchement nècèssèro',
'loginreqlink' => 'branchiér',
'loginreqpagetext' => 'Vos vos dête $1 por povêr vêre les ôtres pâges.',
'accmailtitle' => 'Contresegno mandâ.',
'accmailtext' => "Un contresegno fêt per hasârd por [[User talk:$1|$1]] est étâ mandâ a $2.

Lo contresegno por cél compto novél pôt étre changiê sur la pâge de ''[[Special:ChangePassword|changement de contresegno]]'' aprés s’étre branchiê.",
'newarticle' => '(Novél)',
'newarticletext' => "Vos éd siuvu un lim de vers na pâge qu’ègziste p’oncor.
Por fâre cela pâge, buchiéd voutron tèxto dedens la bouèta ce-desot (vêde la [[{{MediaWiki:Helppage}}|pâge d’éde]] por més d’enformacions).
Se vos éte arrevâ{{GENDER:||ye|(ye)}} ice per fôta, clicâd sur lo boton '''Devant''' de voutron navigator.",
'anontalkpagetext' => "----''O est la pâge de discussion d’un utilisator anonimo qu’at p’oncor fêt un compto ou ben que nen emplèye pas.
Por cen nos devens empleyér la sin’adrèce IP numerica por l’identifiar.
N’adrèce IP pôt étre partagiêe per un mouél d’utilisators.
Se vos éte {{GENDER:|un utilisator|n’utilisatrice|un utilisator}} anonim{{GENDER:|o|a|o}} et pués se vos constatâd que des comentèros que vos regârdont pas vos sont étâs adrèciês, volyéd [[Special:UserLogin/signup|fâre un compto]] ou ben [[Special:UserLogin|vos branchiér]] por èvitar tota confusion a vegnir avouéc d’ôtros utilisators anonimos.''",
'noarticletext' => 'Ora y at gins de tèxto dedens cela pâge.
Vos pouede [[Special:Search/{{PAGENAME}}|fâre na rechèrche sur cél titro]] dedens les ôtres pâges,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} rechèrchiér dedens los jornals liyês]
ou ben [{{fullurl:{{FULLPAGENAME}}|action=edit}} fâre cela pâge]</span>.',
'noarticletext-nopermission' => 'Ora y at gins de tèxto dedens cela pâge.
Vos pouede [[Special:Search/{{PAGENAME}}|fâre na rechèrche sur cél titro]] dedens les ôtres pâges ou ben <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} rechèrchiér dedens los jornals liyês]</span>, mas vos avéd pas la pèrmission de fâre cela pâge.',
'missing-revision' => 'La vèrsion numerô $1 de la pâge apelâye « {{PAGENAME}} » ègziste pas.

En g·ènèral cen arreve en siuvent un lim d’un historico dèpassâ de vers na pâge qu’est étâye suprimâye.
Vos pouede trovar més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].',
'userpage-userdoesnotexist' => 'Lo compto utilisator « $1 » est pas encartâ.
Volyéd controlar que vos voléd fâre ou ben changiér cela pâge.',
'userpage-userdoesnotexist-view' => 'Lo compto utilisator « $1 » est pas encartâ.',
'blocked-notice-logextract' => '{{GENDER:$1|Cél utilisator|Cel’utilisatrice|Cél utilisator}} est ora blocâ{{GENDER:$1||ye|}}.
La dèrriére entrâ du jornal des blocâjos est disponibla ce-desot :',
'clearyourcache' => "'''Nota :''' aprés avêr encartâ, vos devréd forciér lo rechargement complèt du cacho de voutron navigator por vêre los changements.
* '''Firefox / Safari :''' mantegnéd la toche ''Granta Lètra'' (''Shift'') en cliquent sur lo boton ''Rechargiér'' (''Reload'') ou ben prèssâd ''Ctrl-F5'' ou ''Ctrl-R'' (''⌘-R'' sur un Mac)
* '''Google Chrome :''' prèssâd ''Ctrl-Shift-R'' (''⌘-Shift-R'' sur un Mac)
* '''Internet Explorer :''' mantegnéd la toche ''Ctrl'' en cliquent sur lo boton ''Rafrèchir'' (''Refresh'') ou ben prèssâd ''Ctrl-F5''
* '''Opera :''' èfaciéd lo cacho dedens ''Outils → Prèferences''",
'usercssyoucanpreview' => "'''Combina :''' empleyéd lo boton « {{int:showpreview}} » por èprovar voutra fôlye CSS novèla devant que l’encartar.",
'userjsyoucanpreview' => "'''Combina :''' empleyéd lo boton « {{int:showpreview}} » por èprovar voutra fôlye JS novèla devant que l’encartar.",
'usercsspreview' => "'''Rapelâd-vos que vos éte ren qu’aprés prèvêre voutra prôpra fôlye CSS.'''
'''El est p’oncor étâye encartâye !'''",
'userjspreview' => "'''Rapelâd-vos que vos éte ren qu’aprés èprovar / prèvêre voutron code JavaScript.'''
'''Il est p’oncor étâ encartâ !'''",
'sitecsspreview' => "'''Rapelâd-vos que vos éte ren qu’aprés prèvêre cela fôlye CSS.'''
'''El est p’oncor étâye encartâye !'''",
'sitejspreview' => "'''Rapelâd-vos que vos éte ren qu’aprés prèvêre cél code JavaScript.'''
'''Il est p’oncor étâ encartâ !'''",
'userinvalidcssjstitle' => "'''Atencion :''' ègziste gins d’habelyâjo « $1 ».
Rapelâd-vos que les pâges a sè avouéc èxtensions .css et .js emplèyont des titros en petiôtes lètres, per ègzemplo {{ns:user}}:Foo/vector.css et pas {{ns:user}}:Foo/Vector.css.",
'updated' => '(Betâ a jorn)',
'note' => "'''Nota :'''",
'previewnote' => "'''Rapelâd-vos qu’o est ren qu’un apèrçu.'''
Voutros changements sont p’oncor étâs encartâs !",
'continue-editing' => 'Alar a la zona de changement',
'previewconflict' => 'Cet’apèrçu fât vêre lo tèxto de la zona de changement de d’amont coment aparêtrat se vos chouèsésséd de l’encartar.',
'session_fail_preview' => "'''Dèconsolâ ! Nos povens pas encartar voutron changement a côsa d’una pèrta d’enformacions sur voutra sèance.'''
Volyéd tornar èprovar.
Se cen tôrne pas reussir, vos volyéd [[Special:UserLogout|dèbranchiér]] et pués vos tornar branchiér.",
'session_fail_preview_html' => "'''Dèconsolâ ! Nos povens pas encartar voutron changement a côsa d’una pèrta d’enformacions sur voutra sèance.'''

''Perce que {{SITENAME}} at activâ l’HTML bruto, l’apèrçu est étâ cachiê por prèvegnir les ataques per JavaScript.''

'''Se la tentativa de changement ére lèg·itima, volyéd tornar èprovar.'''
Se cen tôrne pas reussir, vos volyéd [[Special:UserLogout|dèbranchiér]] et pués vos tornar branchiér.",
'token_suffix_mismatch' => "'''Voutron changement est pas étâ accèptâ, voutron cliant at mècllâ los caractèros de ponctuacion dedens lo jeton de changement.'''
Lo changement est étâ refusâ por empachiér la corrupcion du tèxto de la pâge.
Des côps ceti problèmo arreve quand vos empleyéd un sèrviço de proxi Vouèbe anonimo qu’est pas de sûr.",
'edit_form_incomplete' => "'''Quârques parties du formulèro de changement ont pas avengiê lo sèrvior ; controlâd que voutros changements sont entiérs et pués tornâd èprovar.'''",
'editing' => 'Changement de $1',
'creating' => 'Crèacion de $1',
'editingsection' => 'Changement de $1 (sèccion)',
'editingcomment' => 'Changement de $1 (novèla sèccion)',
'editconflict' => 'Conflit de changement : $1',
'explainconflict' => "Un ôtro at changiê ceta pâge aprés que vos vos seyâd betâ a la changiér.
La zona de changement de d’amont contint lo tèxto de la pâge coment ègziste orendrêt.
Voutros changements aparèssont dedens la zona de changement de desot.
Vos voléd devêr fusionar voutros changements dedens lo tèxto ègzistent.
'''Solament''' lo tèxto de la zona de changement de d’amont serat encartâ se vos clicâd dessus « {{int:savearticle}} ».",
'yourtext' => 'Voutron tèxto',
'storedversion' => 'Vèrsion encartâye',
'nonunicodebrowser' => "'''Atencion : voutron navigator recognêt pas l’Unicode.'''
Na solucion de rechanjo est étâye trovâye por vos pèrmetre de changiér en tota suretât na pâge : los caractèros nan-ASCII aparêtront dedens la zona de changement por codes hègzadècimâls.",
'editingold' => "'''Atencion : vos éte aprés changiér na vèrsion dèpassâye de cela pâge.'''
Se vos l’encartâd, tôs los changements fêts dês ceta vèrsion seront pèrdus.",
'yourdiff' => 'Difèrences',
'copyrightwarning' => "Volyéd notar que totes les contribucions a {{SITENAME}} sont considèrâyes coment publeyêes desot los tèrmos de la $2 (vêde $1 por més de dètalys).
Se vos voléd pas que voutros ècrits seyont changiês sen pouent de rèstriccion et pués rebalyês a volontât, adonc los volyéd pas sometre ique.<br />
Vos nos assurâd asse-ben que vos éd cen ècrit vos-mémo ou ben que vos l’éd copiyê d’una sôrsa que vint du domêno publico ou ben d’un’ôtra ressôrsa libra.
'''Empleyéd gins d’ôvra desot drêt d’ôtor sen pèrmission èxprèssa !'''",
'copyrightwarning2' => "Volyéd notar que totes les contribucions a {{SITENAME}} pôvont étre changiêes ou ben enlevâyes per d’ôtros contributors.
Se vos voléd pas que voutros ècrits seyont changiês sen pouent de rèstriccion, adonc los volyéd pas sometre ique.<br />
Vos nos assurâd asse-ben que vos éd cen ècrit vos-mémo ou ben que vos l’éd copiyê d’una sôrsa que vint du domêno publico ou ben d’un’ôtra ressôrsa libra (vêde $1 por més de dètalys).
'''Empleyéd gins d’ôvra desot drêt d’ôtor sen pèrmission èxprèssa !'''",
'longpageerror' => "'''Fôta : lo tèxto que vos éd somês fât {{PLURAL:$1|un Kio|$1 Kio}}, cen que dèpâsse la limita fixâye a {{PLURAL:$2|un Kio|$2 Kio}}.'''
Pôt pas étre encartâ.",
'readonlywarning' => "'''Atencion : la bâsa de donâs est étâye vèrrolyêe por mantegnence, vos porréd vêr pas encartar voutros changements d’abôrd.'''
Vos pouede copiyér et côlar voutron tèxto dedens un fichiér tèxto et pués l’encartar por ples târd.

L’administrator qu’at vèrrolyê la bâsa de donâs at balyê cet’èxplicacion : $1",
'protectedpagewarning' => "'''Atencion : ceta pâge est étâye protègiêe de façon que solament los utilisators qu’ont lo statut d’administrator la pouessont changiér.'''
La dèrriére entrâ du jornal est montrâye ce-desot por refèrence :",
'semiprotectedpagewarning' => "'''Nota :''' ceta pâge est étâye protègiêe de façon que solament los utilisators encartâs la pouessont changiér.
La dèrriére entrâ du jornal est montrâye ce-desot por refèrence :",
'cascadeprotectedwarning' => "'''Atencion :''' cela pâge-que est étâye protègiêe de façon que solament los utilisators qu’ont lo statut d’administrator la pouessont changiér, perce qu’el est entrebetâye dedens {{PLURAL:$1|ceta pâge protègiêe|cetes pâges protègiêes}} avouéc la « protèccion en cascâda » activâye :",
'titleprotectedwarning' => "'''Atencion : ceta pâge est étâye protègiêe de façon que des [[Special:ListGroupRights|drêts spècificos]] sont nècèssèros por la povêr fâre.'''
La dèrriére entrâ du jornal est montrâye ce-desot por refèrence :",
'templatesused' => '{{PLURAL:$1|Modèlo empleyê|Modèlos empleyês}} per ceta pâge :',
'templatesusedpreview' => '{{PLURAL:$1|Modèlo empleyê|Modèlos empleyês}} dedens cet’apèrçu :',
'templatesusedsection' => '{{PLURAL:$1|Modèlo empleyê|Modèlos empleyês}} dedens ceta sèccion :',
'template-protected' => '(protègiê)',
'template-semiprotected' => '(mié-protègiê)',
'hiddencategories' => '{{PLURAL:$1|Catègorie cachiêe|Catègories cachiêes}} que ceta pâge est avouéc :',
'edittools' => '<!-- Tot tèxto buchiê ique serat montrâ desot les bouètes d’èdicion ou ben los formulèros de tèlèchargement de fichiér. -->',
'nocreatetitle' => 'Crèacion de pâge limitâ',
'nocreatetext' => '{{SITENAME}} at rètrent la possibilitât de fâre de pâges novèles.
Vos pouede tornar arriér et pués changiér na pâge ègzistenta ou ben [[Special:UserLogin|vos branchiér ou fâre un compto]].',
'nocreate-loggedin' => 'Vos avéd pas la pèrmission de fâre de pâges novèles.',
'sectioneditnotsupported-title' => 'Changement de sèccion pas recognu',
'sectioneditnotsupported-text' => 'Lo changement d’una sèccion est pas recognu dens cela pâge.',
'permissionserrors' => 'Fôta de pèrmissions',
'permissionserrorstext' => 'Vos avéd pas la pèrmission de fâre l’accion demandâye por {{PLURAL:$1|ceta rêson|cetes rêsons}} :',
'permissionserrorstext-withaction' => 'Vos avéd pas la pèrmission de $2 por {{PLURAL:$1|ceta rêson|cetes rêsons}} :',
'recreate-moveddeleted-warn' => "'''Atencion : vos éte aprés refâre na pâge qu’est étâye suprimâye dês devant.'''

Demandâd-vos se fôt franc continuar son changement.
Por comoditât, lo jornal de les suprèssions et des dèplacements de cela pâge est montrâ ce-desot :",
'moveddeleted-notice' => 'Ceta pâge est étâye suprimâye.
Por refèrence, lo jornal de les suprèssions et des dèplacements de cela pâge est montrâ ce-desot.',
'log-fulllog' => 'Vêre lo jornal complèt',
'edit-hook-aborted' => 'Changement anulâ per un grèfon.
Nion’èxplicacion est étâye balyêe.',
'edit-gone-missing' => 'Empossiblo de betar a jorn la pâge.
Semble que seye étâye suprimâye.',
'edit-conflict' => 'Conflit de changement.',
'edit-no-change' => 'Voutron changement est étâ ignorâ, nion changement est étâ fêt u tèxto.',
'edit-already-exists' => 'Empossiblo de fâre na pâge novèla.
Ègziste ja.',
'defaultmessagetext' => 'Mèssâjo per dèfôt',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Atencion :''' cela pâge contint trop d’apèls a des fonccions du parsor que revegnont chieres.

Y devrêt avêr muens de $2 apèl{{PLURAL:$2||s}}, pendent qu’y en at ora $1.",
'expensive-parserfunction-category' => 'Pâges avouéc trop d’apèls a des fonccions du parsor que revegnont chieres',
'post-expand-template-inclusion-warning' => "'''Atencion :''' la talye des modèlos entrebetâs est trop grôssa.
Quârques modèlos seront pas entrebetâs.",
'post-expand-template-inclusion-category' => 'Pâges yô que la talye des modèlos entrebetâs est dèpassâye',
'post-expand-template-argument-warning' => "'''Atencion :''' cela pâge contint u muens un argument de modèlo qu’at na talye d’èxpension trop grôssa.
Celos arguments sont pas étâs betâs.",
'post-expand-template-argument-category' => 'Pâges que contegnont des arguments de modèlo pas betâs',
'parser-template-loop-warning' => 'Modèlo en boclla dècelâ : [[$1]]',
'parser-template-recursion-depth-warning' => 'Limita de provondior des apèls de modèlos dèpassâye ($1)',
'language-converter-depth-warning' => 'Limita de provondior du convèrtissor de lengoua dèpassâye ($1)',
'node-count-exceeded-category' => 'Pâges yô que lo nombro de nuods est dèpassâ',
'node-count-exceeded-warning' => 'Pâge que dèpâsse lo nombro de nuods',
'expansion-depth-exceeded-category' => 'Pâges yô que la provondior d’èxpension est dèpassâye',
'expansion-depth-exceeded-warning' => 'Pâge que dèpâsse la provondior d’èxpension',
'parser-unstrip-loop-warning' => 'Boclla pas dèmontâbla dècelâye',
'parser-unstrip-recursion-limit' => 'Limita de rècursion pas dèmontâbla dèpassâye ($1)',
'converter-manual-rule-error' => 'Fôta dècelâye dens la règlla de convèrsion de lengoua manuèla',

# "Undo" feature
'undo-success' => 'Lo changement pôt étre dèfêt.
Volyéd controlar la comparèson ce-desot por vos assurar qu’o est franc cen que vos voléd fâre et pués encartar los changements ce-desot por chavonar la sina dèfêta.',
'undo-failure' => 'Lo changement at pas possu étre dèfêt a côsa d’un conflit avouéc des changements entèrmèdièros.',
'undo-norev' => 'Lo changement at pas possu étre dèfêt perce qu’il est pas ègzistent ou ben qu’il est étâ suprimâ.',
'undo-summary' => 'Dèfêta du changement $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|discutar]])',

# Account creation failure
'cantcreateaccounttitle' => 'Empossiblo de fâre lo compto',
'cantcreateaccount-text' => "La crèacion de compto dês cet’adrèce IP ('''$1''') est étâye blocâye per [[User:$3|$3]].

La rêson balyêe per $3 ére ''$2''.",

# History pages
'viewpagelogs' => 'Vêde los jornals de ceta pâge',
'nohistory' => 'Ègziste gins d’historico por ceta pâge.',
'currentrev' => 'Vèrsion d’ora',
'currentrev-asof' => 'Vèrsion d’ora du $2 a $3',
'revisionasof' => 'Vèrsion du $2 a $3',
'revision-info' => 'Vèrsion du $4 a $5 per $2',
'previousrevision' => '← Vèrsion ples vielye',
'nextrevision' => 'Vèrsion ples novèla →',
'currentrevisionlink' => 'Vèrsion d’ora',
'cur' => 'd’ora',
'next' => 'aprés',
'last' => 'devant',
'page_first' => 'Premiére',
'page_last' => 'dèrriére',
'histlegend' => "Chouèx de difs : pouentâd les câses de les vèrsions a comparar et pués apoyéd dessus « Entrâ » ou ben lo boton d’avâl.<br />
Lègenda : '''({{int:cur}})''' = difèrence avouéc la vèrsion d’ora, '''({{int:last}})''' = difèrence avouéc la vèrsion devant, '''{{int:minoreditletter}}''' = petiôt changement.",
'history-fieldset-title' => 'Fâre dèfelar l’historico',
'history-show-deleted' => 'Ren que les suprimâyes',
'histfirst' => 'premiére',
'histlast' => 'Dèrriére',
'historysize' => '($1 octèt{{PLURAL:$1||s}})',
'historyempty' => '(voueda)',

# Revision feed
'history-feed-title' => 'Historico de les vèrsions',
'history-feed-description' => 'Historico por ceta pâge sur lo vouiqui',
'history-feed-item-nocomment' => '$1 lo $3 a $4',
'history-feed-empty' => 'La pâge demandâye ègziste pas.
Pôt-étre el est étâye suprimâye du vouiqui ou ben renomâye.
Tâchiéd de [[Special:Search|rechèrchiér sur lo vouiqui]] por trovar des pâges novèles que vont avouéc.',

# Revision deletion
'rev-deleted-comment' => '(rèsumâ de changement enlevâ)',
'rev-deleted-user' => '(nom d’utilisator enlevâ)',
'rev-deleted-event' => '(accion du jornal enlevâye)',
'rev-deleted-user-contribs' => '[nom d’utilisator ou ben adrèce IP enlevâ(ye) - changement cachiê sur les contribucions]',
'rev-deleted-text-permission' => "Ceta vèrsion de la pâge est étâye '''suprimâye'''.
Y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].",
'rev-deleted-text-unhide' => "Ceta vèrsion de la pâge est étâye '''suprimâye'''.
Y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].
Vos pouede adés [$1 vêre cela vèrsion] se vos o voléd.",
'rev-suppressed-text-unhide' => "Ceta vèrsion de la pâge est étâye '''rèprimâye'''.
Y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les rèprèssions].
Vos pouede adés [$1 vêre cela vèrsion] se vos o voléd.",
'rev-deleted-text-view' => "Ceta vèrsion de la pâge est étâye '''suprimâye'''.
Vos la pouede vêre ; y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].",
'rev-suppressed-text-view' => "Ceta vèrsion de la pâge est étâye '''rèprimâye'''.
Vos la pouede vêre ; y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jornal de les rèprèssions].",
'rev-deleted-no-diff' => "Vos pouede pas vêre ceta dif perce que yona de les vèrsions est étâye '''suprimâye'''.
Y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].",
'rev-suppressed-no-diff' => "Vos pouede pas vêre ceta dif, yona de les vèrsions est étâye '''suprimâye'''.",
'rev-deleted-unhide-diff' => "Yona de les vèrsions de ceta dif est étâye '''suprimâye'''.
Y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].
Vos pouede adés [$1 vêre cela dif] se vos o voléd.",
'rev-suppressed-unhide-diff' => "Yona de les vèrsions de ceta dif est étâye '''rèprimâye'''.
Y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les rèprèssions].
Vos pouede adés [$1 vêre cela dif] se vos o voléd.",
'rev-deleted-diff-view' => "Yona de les vèrsions de ceta dif est étâye '''suprimâye'''.
Vos pouede vêre ceta dif ; y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].",
'rev-suppressed-diff-view' => "Yona de les vèrsions de ceta dif est étâye '''rèprimâye'''.
Vos pouede vêre ceta dif ; y pôt avêr més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jornal de les rèprèssions].",
'rev-delundel' => 'montrar / cachiér',
'rev-showdeleted' => 'montrar',
'revisiondelete' => 'Suprimar / refâre des vèrsions',
'revdelete-nooldid-title' => 'Vèrsion ciba pas justa',
'revdelete-nooldid-text' => 'Vos éd pas spècifiâ na vèrsion ciba (des vèrsions cibes) por fâre cela
fonccion, la vèrsion spècifiâye ègziste pas ou ben vos tâchiéd de cachiér la vèrsion d’ora.',
'revdelete-nologtype-title' => 'Nion tipo de jornal balyê',
'revdelete-nologtype-text' => 'Vos éd pas spècifiâ un tipo de jornal por fâre cel’accion.',
'revdelete-nologid-title' => 'Entrâ du jornal pas justa',
'revdelete-nologid-text' => 'Ou ben vos éd pas spècifiâ un èvènement du jornal ciba por fâre cela fonccion ou ben l’entrâ spècifiâye ègziste pas.',
'revdelete-no-file' => 'Lo fichiér spècifiâ ègziste pas.',
'revdelete-show-file-confirm' => 'Éte-vos de sûr de volêr vêre na vèrsion suprimâye du fichiér « <nowiki>$1</nowiki> » du $2 a $3 ?',
'revdelete-show-file-submit' => 'Ouè',
'revdelete-selected' => "'''{{PLURAL:$2|Vèrsion chouèsia|Vèrsions chouèsies}} de [[:$1]] :'''",
'logdelete-selected' => "'''{{PLURAL:$1|Èvènement du jornal chouèsi|Èvènements du jornal chouèsis}} :'''",
'revdelete-text' => "'''Les vèrsions et los èvènements suprimâ(ye)s aparètront adés dedens l’historico de la pâge et pués dedens los jornals, mas quârques parties de lor contegnu seront inaccèssibles u publico.'''
Los ôtros administrators de {{SITENAME}} porront tojorn arrevar u contegnu cachiê et lo refâre per cela mém’entèrface, a muens que des rèstriccions de ples seyont pas dèfenies.",
'revdelete-confirm' => 'Volyéd confirmar qu’o est franc cen que vos voléd fâre, que vos en compregnéd les consèquences et pués que vos o féte en acôrd avouéc les [[{{MediaWiki:Policy-url}}|règlles de dedens]].',
'revdelete-suppress-text' => "La rèprèssion dêt étre empleyêe '''ren que''' dens cetos câs :
* Enformacions que pôvont étre difamatouères
* Enformacions a sè que vont pas avouéc
*: ''adrèces et numerôs de tèlèfono, numerôs de sècuritât sociâla, ...''",
'revdelete-legend' => 'Dèfenir des rèstriccions de visibilitât',
'revdelete-hide-text' => 'Cachiér lo tèxto de la vèrsion',
'revdelete-hide-image' => 'Cachiér lo contegnu du fichiér',
'revdelete-hide-name' => 'Cachiér l’accion et la ciba',
'revdelete-hide-comment' => 'Cachiér lo rèsumâ de changement',
'revdelete-hide-user' => 'Cachiér lo nom d’utilisator / l’adrèce IP du contributor',
'revdelete-hide-restricted' => 'Rèprimar celes donâs ux administrators et pués ux ôtros',
'revdelete-radio-same' => '(pas changiér)',
'revdelete-radio-set' => 'Ouè',
'revdelete-radio-unset' => 'Nan',
'revdelete-suppress' => 'Rèprimar celes donâs ux administrators et pués ux ôtros',
'revdelete-unsuppress' => 'Enlevar les rèstriccions sur les vèrsions refêtes',
'revdelete-log' => 'Rêson :',
'revdelete-submit' => 'Aplicar a {{PLURAL:$1|la vèrsion chouèsia|les vèrsions chouèsies}}',
'revdelete-success' => "'''Visibilitât de la vèrsion betâye a jorn avouéc reusséta.'''",
'revdelete-failure' => "'''La visibilitât de la vèrsion at pas possu étre betâye a jorn :'''
$1",
'logdelete-success' => "'''Visibilitât du jornal dèfenia avouéc reusséta.'''",
'logdelete-failure' => "'''La visibilitât du jornal at pas possu étre dèfenia :'''
$1",
'revdel-restore' => 'changiér la visibilitât',
'revdel-restore-deleted' => 'vèrsions suprimâyes',
'revdel-restore-visible' => 'vèrsions visibles',
'pagehist' => 'Historico de la pâge',
'deletedhist' => 'Historico suprimâ',
'revdelete-hide-current' => 'Fôta en cachient la piéce datâye du $1 a $2 : o est la vèrsion d’ora.
Pôt pas étre cachiêe.',
'revdelete-show-no-access' => 'Fôta en montrent la piéce datâye du $1 a $2 : el est marcâye coment « rètrenta ».
Vos y avéd pas accès.',
'revdelete-modify-no-access' => 'Fôta en changient la piéce datâye du $1 a $2 : el est marcâye coment « rètrenta ».
Vos y avéd pas accès.',
'revdelete-modify-missing' => 'Fôta en changient la piéce avouéc l’identifient $1 : el est manquenta dedens la bâsa de donâs !',
'revdelete-no-change' => "'''Atencion :''' la piéce datâye du $1 a $2 at ja la configuracion de visibilitât demandâye.",
'revdelete-concurrent-change' => 'Fôta en changient la piéce datâye du $1 a $2 : lo sin statut semble étre étâ changiê per un ôtro justo que vos tâchiêvâd d’o changiér.
Volyéd controlar los jornals.',
'revdelete-only-restricted' => 'Fôta en cachient la piéce datâye du $1 a $2 : vos pouede pas rèprimar celes piéces de la vua ux administrators sen chouèsir avouéc des ôtros chouèx de visibilitât.',
'revdelete-reason-dropdown' => '*Rêsons corentes de suprèssion
** Violacion du drêt d’ôtor
** Comentèros ou ben enformacions a sè que vont pas avouéc
** Nom d’utilisator que vat pas avouéc
** Enformacions que pôvont étre difamatouères',
'revdelete-otherreason' => 'Ôtra rêson / rêson de ples :',
'revdelete-reasonotherlist' => 'Ôtra rêson',
'revdelete-edit-reasonlist' => 'Changiér les rêsons de suprèssion',
'revdelete-offender' => 'Ôtor de la vèrsion :',

# Suppression log
'suppressionlog' => 'Jornal de les suprèssions',
'suppressionlogtext' => 'Vê-que na lista de les suprèssions et des blocâjos qu’ont de contegnu cachiê ux administrators.
Vêde la [[Special:BlockList|lista des blocâjos]] por la lista des banissements et des blocâjos que sont ora actifs.',

# History merging
'mergehistory' => 'Fusionar los historicos de les pâges',
'mergehistory-header' => 'Ceta pâge vos pèrmèt de fusionar des vèrsions de l’historico d’una pâge d’origina vers na pâge novèla.
Assurâd-vos que cél changement consèrverat la continuitât de l’historico de la pâge.',
'mergehistory-box' => 'Fusionar les vèrsions de doves pâges :',
'mergehistory-from' => 'Pâge d’origina :',
'mergehistory-into' => 'Pâge de dèstinacion :',
'mergehistory-list' => 'Historico des changements que pôvont étre fusionâs',
'mergehistory-merge' => 'Cetes vèrsions de [[:$1]] pôvont étre fusionâyes dedens [[:$2]].
Empleyéd la colona de botons de chouèx por fusionar ren que les vèrsions fêtes du comencement tant qu’a la dâta spècifiâye.
Notâd que l’usâjo des lims de navigacion remetrat a zérô cela colona.',
'mergehistory-go' => 'Montrar los changements que pôvont étre fusionâs',
'mergehistory-submit' => 'Fusionar les vèrsions',
'mergehistory-empty' => 'Niona vèrsion pôt étre fusionâye.',
'mergehistory-success' => '$3 vèrsion{{PLURAL:$3||s}} de [[:$1]] fusionâye{{PLURAL:$3||s}} avouéc reusséta dedens [[:$2]].',
'mergehistory-fail' => 'Empossiblo de fâre la fusion des historicos, volyéd tornar chouèsir la pâge et pués los paramètros de dâta.',
'mergehistory-no-source' => 'La pâge d’origina $1 ègziste pas.',
'mergehistory-no-destination' => 'La pâge de dèstinacion $1 ègziste pas.',
'mergehistory-invalid-source' => 'La pâge d’origina dêt avêr un titro justo.',
'mergehistory-invalid-destination' => 'La pâge de dèstinacion dêt avêr un titro justo.',
'mergehistory-autocomment' => '[[:$1]] fusionâye dedens [[:$2]]',
'mergehistory-comment' => '[[:$1]] fusionâye dedens [[:$2]] : $3',
'mergehistory-same-destination' => 'Les pâges d’origina et de dèstinacion pôvont pas étre la méma',
'mergehistory-reason' => 'Rêson :',

# Merge log
'mergelog' => 'Jornal de les fusions',
'pagemerge-logentry' => '[[$1]] fusionâye dedens [[$2]] (vèrsions tant qu’u $3)',
'revertmerge' => 'Sèparar',
'mergelogpagetext' => 'Vê-que na lista de les fusions les ples novèles de l’historico d’una pâge dedens celi d’un’ôtra.',

# Diffs
'history-title' => 'Historico de les vèrsions de « $1 »',
'difference-title' => 'Difèrences entre les vèrsions de « $1 »',
'difference-title-multipage' => 'Difèrences entre les pâges « $1 » et « $2 »',
'difference-multipage' => '(Difèrences entre les pâges)',
'lineno' => 'Legne $1 :',
'compareselectedversions' => 'Comparar les vèrsions chouèsies',
'showhideselectedversions' => 'Montrar / cachiér les vèrsions chouèsies',
'editundo' => 'dèfâre',
'diff-multi' => '({{PLURAL:$1|Na vèrsion entèrmèdièra|$1 vèrsions entèrmèdières}} per {{PLURAL:$2|un utilisator|$2 utilisators}} {{PLURAL:$1|est pas montrâye|sont pas montrâyes}})',
'diff-multi-manyusers' => '({{PLURAL:$1|Na vèrsion entèrmèdièra|$1 vèrsions entèrmèdières}} per més de $2 utilisator{{PLURAL:$2||s}} {{PLURAL:$1|est pas montrâye|sont pas montrâyes}})',
'difference-missing-revision' => '{{PLURAL:$2|Na vèrsion|$2 vèrsions}} de cela difèrence ($1) {{PLURAL:$2|est pas étâye trovâye|sont pas étâyes trovâyes}}.

En g·ènèral cen arreve en siuvent un lim d’una dif dèpassâye de vers na pâge qu’est étâye suprimâye.
Vos pouede trovar més de dètalys dedens lo [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jornal de les suprèssions].',

# Search results
'searchresults' => 'Rèsultats de la rechèrche',
'searchresults-title' => 'Rèsultats de la rechèrche por « $1 »',
'searchresulttext' => 'Por més d’enformacions sur la rechèrche dedens {{SITENAME}}, vêde [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => "Vos éd rechèrchiê « '''[[:$1]]''' » ([[Special:Prefixindex/$1|totes les pâges que començont per « $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|totes les pâges qu’ont un lim de vers « $1 »]])",
'searchsubtitleinvalid' => "Vos éd rechèrchiê « '''$1''' »",
'toomanymatches' => 'Un mouél de corrèspondances est étâ retornâ, volyéd èprovar na rechèrche difèrenta.',
'titlematches' => 'Corrèspondances dedens los titros de les pâges',
'notitlematches' => 'Niona corrèspondance dedens los titros de les pâges',
'textmatches' => 'Corrèspondances dedens lo tèxto de les pâges',
'notextmatches' => 'Niona corrèspondance dedens lo tèxto de les pâges',
'prevn' => '{{PLURAL:$1|$1}} devant',
'nextn' => '{{PLURAL:$1|$1}} aprés',
'prevn-title' => '$1 rèsultat{{PLURAL:$1||s}} devant',
'nextn-title' => '$1 rèsultat{{PLURAL:$1||s}} aprés',
'shown-title' => 'Montrar $1 rèsultat{{PLURAL:$1||s}} per pâge',
'viewprevnext' => 'Vêre ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Chouèx de rechèrche',
'searchmenu-exists' => "'''Y at na pâge apelâye « [[:$1]] » sur ceti vouiqui.'''",
'searchmenu-new' => "'''Fâre la pâge « [[:$1|$1]] » sur ceti vouiqui !'''",
'searchhelp-url' => 'Help:Somèro',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Fâre dèfelar les pâges que començont per ceti prèfixo]]',
'searchprofile-articles' => 'Pâges de contegnu',
'searchprofile-project' => 'Pâges d’éde et pâges projèt',
'searchprofile-images' => 'Multimèdia',
'searchprofile-everything' => 'Tot',
'searchprofile-advanced' => 'Rechèrche avanciêe',
'searchprofile-articles-tooltip' => 'Rechèrchiér dedens $1',
'searchprofile-project-tooltip' => 'Rechèrchiér dedens $1',
'searchprofile-images-tooltip' => 'Rechèrchiér des fichiérs',
'searchprofile-everything-tooltip' => 'Rechèrchiér dedens tot lo contegnu (les pâges de discussion avouéc)',
'searchprofile-advanced-tooltip' => 'Chouèsir los èspâços de noms por la rechèrche',
'search-result-size' => '$1 ($2 mot{{PLURAL:$2||s}})',
'search-result-category-size' => '$1 membro{{PLURAL:$1||s}} ($2 sot-catègorie{{PLURAL:$2||s}}, $3 fichiér{{PLURAL:$3||s}})',
'search-result-score' => 'Rapôrt : $1%',
'search-redirect' => '(redirèccion dês $1)',
'search-section' => '(sèccion $1)',
'search-suggest' => 'Voléd-vos dére : $1',
'search-interwiki-caption' => 'Projèts frâres',
'search-interwiki-default' => 'Rèsultats dessus $1 :',
'search-interwiki-more' => '(més)',
'search-relatedarticle' => 'Aparentâ',
'mwsuggest-disable' => 'Dèsactivar les idês AJAX',
'searcheverything-enable' => 'Rechèrchiér dedens tôs los èspâços de noms',
'searchrelated' => 'aparentâ',
'searchall' => 'tot',
'showingresults' => "Vua de '''$1''' rèsultat{{PLURAL:$1||s}} dês lo numerô '''$2'''.",
'showingresultsnum' => "Vua de '''$3''' rèsultat{{PLURAL:$3||s}} dês lo numerô '''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Rèsultat '''$1'''|Rèsultats '''$1 - $2'''}} de '''$3''' por '''$4'''",
'nonefound' => "'''Nota :''' solament quârques èspâços de noms sont rechèrchiês per dèfôt.
Èprovâd en empleyent lo prèfixo ''all:'' por rechèrchiér dedens tot lo contegnu (les pâges de discussion, los modèlos, ... avouéc) ou ben empleyéd l’èspâço de noms volu coment prèfixo.",
'search-nonefound' => 'Y at gins de rèsultat que corrèspond a la rechèrche.',
'powersearch' => 'Rechèrche avanciêe',
'powersearch-legend' => 'Rechèrche avanciêe',
'powersearch-ns' => 'Rechèrchiér dedens los èspâços de noms :',
'powersearch-redir' => 'Listar les redirèccions',
'powersearch-field' => 'Rechèrchiér',
'powersearch-togglelabel' => 'Chouèsir :',
'powersearch-toggleall' => 'Tot',
'powersearch-togglenone' => 'Nion',
'search-external' => 'Rechèrche de defôr',
'searchdisabled' => 'La rechèrche dessus {{SITENAME}} est dèsactivâye.
Pendent cél temps, vos pouede fâre na rechèrche avouéc Google.
Notâd que lor endèxacion du contegnu de {{SITENAME}} pôt pas étre a jorn.',

# Quickbar
'qbsettings' => 'Bârra rapida',
'qbsettings-none' => 'Pas yona',
'qbsettings-fixedleft' => 'Fixa a gôche',
'qbsettings-fixedright' => 'Fixa a drêta',
'qbsettings-floatingleft' => 'Fllotenta a gôche',
'qbsettings-floatingright' => 'Fllotenta a drêta',
'qbsettings-directionality' => 'Fixa, d’aprés la dirèccionalitât d’ècritura de voutra lengoua',

# Preferences page
'preferences' => 'Prèferences',
'mypreferences' => 'Prèferences',
'prefs-edits' => 'Nombro de changements :',
'prefsnologin' => 'Pas branchiê',
'prefsnologintext' => 'Vos dête étre <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} branchiê]</span> por dèfenir les prèferences utilisator.',
'changepassword' => 'Changiér lo contresegno',
'prefs-skin' => 'Habelyâjo',
'skin-preview' => 'Prèvêre',
'datedefault' => 'Niona prèference',
'prefs-beta' => 'Fonccionalitâts « Bèta »',
'prefs-datetime' => 'Dâta et hora',
'prefs-labs' => 'Fonccionalitâts « Laboratiors »',
'prefs-user-pages' => 'Pâges utilisator',
'prefs-personal' => 'Enformacions a sè',
'prefs-rc' => 'Dèrriérs changements',
'prefs-watchlist' => 'Lista de siuvu',
'prefs-watchlist-days' => 'Nombro de jorns a montrar dedens la lista de siuvu :',
'prefs-watchlist-days-max' => 'Por lo més $1 jorn{{PLURAL:$1||s}}',
'prefs-watchlist-edits' => 'Nombro maximon de changements a montrar dedens la lista de siuvu ètendua :',
'prefs-watchlist-edits-max' => 'Nombro maximon : 1000',
'prefs-watchlist-token' => 'Jeton por la lista de siuvu :',
'prefs-misc' => 'De totes sôrtes',
'prefs-resetpass' => 'Changiér lo contresegno',
'prefs-changeemail' => 'Changiér l’adrèce èlèctronica',
'prefs-setemail' => 'Dèfenir n’adrèce èlèctronica',
'prefs-email' => 'Chouèx de mèssageria èlèctronica',
'prefs-rendering' => 'Aparence',
'saveprefs' => 'Encartar',
'resetprefs' => 'Èfaciér los changements pas encartâs',
'restoreprefs' => 'Rètablir tota la configuracion per dèfôt',
'prefs-editing' => 'Changement',
'prefs-edit-boxsize' => 'Talye de la fenétra de changement.',
'rows' => 'Renches :',
'columns' => 'Colones :',
'searchresultshead' => 'Rechèrche',
'resultsperpage' => 'Nombro de rèponses per pâge :',
'stub-threshold' => 'Limita d’amont por los <a href="#" class="stub">lims de vers los començons</a> (octèts) :',
'stub-threshold-disabled' => 'Dèsactivâ',
'recentchangesdays' => 'Nombro de jorns a montrar dedens los dèrriérs changements :',
'recentchangesdays-max' => 'Por lo més $1 jorn{{PLURAL:$1||s}}',
'recentchangescount' => 'Nombro de changements a montrar per dèfôt :',
'prefs-help-recentchangescount' => 'Los dèrriérs changements, los historicos de pâges et pués los jornals avouéc.',
'prefs-help-watchlist-token' => 'Rempléd ceti champ avouéc na cllâf secrèta et pués un flux RSS serat fêt por voutra lista de siuvu.
Tôs celos que cognessont cela cllâf porront liére voutra lista de siuvu, chouèsésséd vêr na valor sècurisâye.
Vê-que na valor fêta per hasârd que vos pouede empleyér : $1',
'savedprefs' => 'Voutres prèferences sont étâyes encartâyes.',
'timezonelegend' => 'Fus horèro :',
'localtime' => 'Hora locala :',
'timezoneuseserverdefault' => 'Empleyér la valor du vouiqui per dèfôt ($1)',
'timezoneuseoffset' => 'Ôtro (spècifiar lo dècalâjo)',
'timezoneoffset' => 'Dècalâjo horèro¹ :',
'servertime' => 'Hora du sèrvior :',
'guesstimezone' => 'Empleyér la valor du navigator',
'timezoneregion-africa' => 'Africa',
'timezoneregion-america' => 'Amèriques',
'timezoneregion-antarctica' => 'Antartica',
'timezoneregion-arctic' => 'Artico',
'timezoneregion-asia' => 'Asia',
'timezoneregion-atlantic' => 'Ocèan atlantico',
'timezoneregion-australia' => 'Ôstralie',
'timezoneregion-europe' => 'Eropa',
'timezoneregion-indian' => 'Ocèan endien',
'timezoneregion-pacific' => 'Ocèan pacifico',
'allowemail' => 'Activar l’èxpèdicion de mèssâjos que vegnont d’ôtros utilisators',
'prefs-searchoptions' => 'Rechèrche',
'prefs-namespaces' => 'Èspâços de noms',
'defaultns' => 'Ôtrament rechèrchiér dedens cetos èspâços de noms :',
'default' => 'dèfôt',
'prefs-files' => 'Fichiérs',
'prefs-custom-css' => 'CSS pèrsonalisâ',
'prefs-custom-js' => 'JavaScript pèrsonalisâ',
'prefs-common-css-js' => 'CSS / JS partagiê por tôs los habelyâjos :',
'prefs-reset-intro' => 'Vos pouede empleyér ceta pâge por rètablir voutres prèferences a les valors du seto per dèfôt.
Cen pôt pas étre dèfêt.',
'prefs-emailconfirm-label' => 'Confirmacion de l’adrèce èlèctronica :',
'prefs-textboxsize' => 'Talye de la fenétra de changement',
'youremail' => 'Adrèce èlèctronica :',
'username' => 'Nom d’usanciér :',
'uid' => 'Numerô d’usanciér :',
'prefs-memberingroups' => 'Membro a {{PLURAL:$1|la tropa|les tropes}} :',
'prefs-registration' => 'Dâta d’encartâjo :',
'yourrealname' => 'Veré nom :',
'yourlanguage' => 'Lengoua :',
'yourvariant' => 'Varianta de la lengoua du contegnu :',
'prefs-help-variant' => 'Voutra varianta voutron ortografia prèferâye por fâre vêre les pâges de contegnu de ceti vouiqui.',
'yournick' => 'Signatura novèla :',
'prefs-help-signature' => 'Los comentèros sur les pâges de discussion dêvont étre signês avouéc « <nowiki>~~~~</nowiki> » que serat convèrti per voutra signatura avouéc la dâta et hora.',
'badsig' => 'Signatura bruta pas justa.
Controlâd les balises HTML.',
'badsiglength' => 'Voutra signatura est trop longe.
Dêt pas dèpassar $1 caractèro{{PLURAL:$1||s}}.',
'yourgender' => 'Sèxo :',
'gender-unknown' => 'Pas rensègnê',
'gender-male' => 'Masculin',
'gender-female' => 'Femenin',
'prefs-help-gender' => 'U chouèx : empleyê por acordar en sèxo los mèssâjos de la programeria.
Cel’enformacion serat publica.',
'email' => 'Mèssageria èlèctronica',
'prefs-help-realname' => 'U chouèx : se vos lo balyéd, serat empleyê por vos atribuar voutres ôvres.',
'prefs-help-email' => 'U chouèx : mas el est nècèssèra por remetre a zérô voutron contresegno, se vos vegnévâd a l’oubliar.',
'prefs-help-email-others' => 'Vos porriâd asse-ben chouèsir de lèssiér los ôtros sè veriér vers vos per mèssageria èlèctronica avouéc un lim sur voutra pâge utilisator ou ben de discussion sen que seye nècèssèro de rèvèlar voutron identitât.',
'prefs-help-email-required' => 'N’adrèce èlèctronica est nècèssèra.',
'prefs-info' => 'Enformacions de bâsa',
'prefs-i18n' => 'Entèrnacionalisacion',
'prefs-signature' => 'Signatura',
'prefs-dateformat' => 'Format de la dâta',
'prefs-timeoffset' => 'Dècalâjo horèro',
'prefs-advancedediting' => 'Chouèx avanciês',
'prefs-advancedrc' => 'Chouèx avanciês',
'prefs-advancedrendering' => 'Chouèx avanciês',
'prefs-advancedsearchoptions' => 'Chouèx avanciês',
'prefs-advancedwatchlist' => 'Chouèx avanciês',
'prefs-displayrc' => 'Chouèx de vua',
'prefs-displaysearchoptions' => 'Chouèx de vua',
'prefs-displaywatchlist' => 'Chouèx de vua',
'prefs-diffs' => 'Difèrences',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'L’adrèce èlèctronica semble justa',
'email-address-validity-invalid' => 'Buchiéd n’adrèce èlèctronica justa',

# User rights
'userrights' => 'Administracion des drêts d’utilisator',
'userrights-lookup-user' => 'Administracion de les tropes d’utilisators',
'userrights-user-editname' => 'Buchiéd un nom d’utilisator :',
'editusergroup' => 'Changiér les tropes d’utilisators',
'editinguser' => "Changement des drêts d’utilisator de l’utilisat{{GENDER:$1|or|rice}} '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Changiér les tropes d’utilisators',
'saveusergroups' => 'Encartar les tropes d’utilisators',
'userrights-groupsmember' => 'Membr{{GENDER:$2|o|a}} de :',
'userrights-groupsmember-auto' => '{{GENDER:$2|Membro tacito|Membra tacita}} de :',
'userrights-groups-help' => 'Vos pouede changiér les tropes a lesquintes est cet’utilisat{{GENDER:$1|or|rice}} :
* Na câsa pouentâye vôt dére que l’utilisat{{GENDER:$1|or|rice}} sè trôve dedens cela tropa.
* Na câsa pas pouentâye vôt dére que s’y trôve pas.
* Na petiôt’ètêla (*) endique que vos pouede pas enlevar cela tropa setout que vos l’éd apondua ou ben l’una l’ôtra.',
'userrights-reason' => 'Rêson :',
'userrights-no-interwiki' => 'Vos avéd pas la pèrmission de changiér des drêts d’utilisator dessus d’ôtros vouiquis.',
'userrights-nodatabase' => 'La bâsa de donâs « $1 » ègziste pas ou ben el est pas locala.',
'userrights-nologin' => 'Vos vos dête [[Special:UserLogin|branchiér]] avouéc un compto d’administrator por balyér des drêts d’utilisator.',
'userrights-notallowed' => 'Voutron compto at pas la pèrmission de balyér ou ben enlevar des drêts d’utilisator.',
'userrights-changeable-col' => 'Les tropes que vos pouede changiér',
'userrights-unchangeable-col' => 'Les tropes que vos pouede pas changiér',

# Groups
'group' => 'Tropa :',
'group-user' => 'Utilisators',
'group-autoconfirmed' => 'Utilisators ôtoconfirmâs',
'group-bot' => 'Robots',
'group-sysop' => 'Administrators',
'group-bureaucrat' => 'Grata-papiérs',
'group-suppress' => 'Supèrvisors',
'group-all' => '(tôs)',

'group-user-member' => 'utilisat{{GENDER:$1|or|rice}}',
'group-autoconfirmed-member' => 'utilisat{{GENDER:$1|or ôtoconfirmâ|rice ôtoconfirmâye}}',
'group-bot-member' => '{{GENDER:$1|robot}}',
'group-sysop-member' => 'administrat{{GENDER:$1|or|rice}}',
'group-bureaucrat-member' => '{{GENDER:$1|grata-papiér}}',
'group-suppress-member' => 'supèrviso{{GENDER:$1|r|sa}}',

'grouppage-user' => '{{ns:project}}:Utilisators',
'grouppage-autoconfirmed' => '{{ns:project}}:Utilisators ôtoconfirmâs',
'grouppage-bot' => '{{ns:project}}:Robots',
'grouppage-sysop' => '{{ns:project}}:Administrators',
'grouppage-bureaucrat' => '{{ns:project}}:Grata-papiérs',
'grouppage-suppress' => '{{ns:project}}:Supèrvisors',

# Rights
'right-read' => 'Liére les pâges',
'right-edit' => 'Changiér les pâges',
'right-createpage' => 'Fâre des pâges (que sont pas des pâges de discussion)',
'right-createtalk' => 'Fâre des pâges de discussion',
'right-createaccount' => 'Fâre des comptos utilisator novéls',
'right-minoredit' => 'Marcar los changements coment petiôts',
'right-move' => 'Dèplaciér des pâges',
'right-move-subpages' => 'Dèplaciér des pâges avouéc lors sot-pâges',
'right-move-rootuserpages' => 'Dèplaciér des pâges utilisator principâles',
'right-movefile' => 'Dèplaciér des fichiérs',
'right-suppressredirect' => 'Pas fâre de redirèccion dês la pâge d’origina en dèplacient na pâge',
'right-upload' => 'Tèlèchargiér des fichiérs',
'right-reupload' => 'Ècllafar un fichiér ègzistent',
'right-reupload-own' => 'Ècllafar un fichiér ègzistent tèlèchargiê per sè-mémo',
'right-reupload-shared' => 'Ècllafar localament un fichiér present sur un dèpôt de fichiérs mèdia partagiê',
'right-upload_by_url' => 'Tèlèchargiér un fichiér dês n’URL',
'right-purge' => 'Purgiér lo cacho du seto d’una pâge sen confirmacion',
'right-autoconfirmed' => 'Changiér les pâges mié-protègiêes',
'right-bot' => 'Étre trètâ coment na mètoda ôtomatisâye',
'right-nominornewtalk' => 'Pas dècllenchiér la notificacion de mèssâjo novél quand font un petiôt changement sur la pâge de discussion d’un utilisator',
'right-apihighlimits' => 'Empleyér des limites ples hôtes dedens les demandes API',
'right-writeapi' => 'Empleyér l’API d’ècritura',
'right-delete' => 'Suprimar des pâges',
'right-bigdelete' => 'Suprimar des pâges qu’ont un grôs historico',
'right-deletelogentry' => 'Suprimar et refâre n’entrâ spècifica du jornal',
'right-deleterevision' => 'Suprimar et refâre na vèrsion spècifica d’una pâge',
'right-deletedhistory' => 'Vêre les entrâs suprimâyes de l’historico sen lor tèxto',
'right-deletedtext' => 'Vêre lo tèxto suprimâ et pués los changements entre les vèrsions suprimâyes',
'right-browsearchive' => 'Rechèrchiér des pâges suprimâyes',
'right-undelete' => 'Refâre na pâge',
'right-suppressrevision' => 'Revêre et refâre les vèrsions cachiêes ux administrators',
'right-suppressionlog' => 'Vêre los jornals privâs',
'right-block' => 'Blocar en ècritura d’ôtros utilisators',
'right-blockemail' => 'Empachiér un utilisator de mandar des mèssâjos',
'right-hideuser' => 'Blocar un utilisator en cachient son nom u publico',
'right-ipblock-exempt' => 'Èvitar los blocâjos d’adrèces IP, los blocâjos ôtomaticos et pués los blocâjos de plages d’adrèces IP',
'right-proxyunbannable' => 'Èvitar los blocâjos ôtomaticos de proxis',
'right-unblockself' => 'Sè dèblocar lor-mémos',
'right-protect' => 'Changiér lo nivél de protèccion et pués changiér les pâges protègiêes',
'right-editprotected' => 'Changiér les pâges protègiêes (sen protèccion en cascâda)',
'right-editinterface' => 'Changiér l’entèrface utilisator',
'right-editusercssjs' => 'Changiér los fichiérs CSS et JavaScript d’ôtros utilisators',
'right-editusercss' => 'Changiér los fichiérs CSS d’ôtros utilisators',
'right-edituserjs' => 'Changiér los fichiérs JavaScript d’ôtros utilisators',
'right-rollback' => 'Rèvocar rêdo los changements du dèrriér utilisator qu’at changiê na pâge particuliére',
'right-markbotedits' => 'Marcar des changements rèvocâs coment étent étâs fêts per un robot',
'right-noratelimit' => 'Pas étre afèctâ per les limites de quota',
'right-import' => 'Importar des pâges dês d’ôtros vouiquis',
'right-importupload' => 'Importar des pâges dês un fichiér tèlèchargiê',
'right-patrol' => 'Marcar los changements des ôtros coment gouardâs',
'right-autopatrol' => 'Avêr los sins changements marcâs ôtomaticament coment gouardâs',
'right-patrolmarks' => 'Vêre les mârques de gouârda dedens los dèrriérs changements',
'right-unwatchedpages' => 'Vêre na lista de les pâges pas siuvues',
'right-mergehistory' => 'Fusionar los historicos de les pâges',
'right-userrights' => 'Changiér tôs los drêts d’un utilisator',
'right-userrights-interwiki' => 'Changiér los drêts d’utilisator des utilisators que sont sur un ôtro vouiqui',
'right-siteadmin' => 'Vèrrolyér et dèvèrrolyér la bâsa de donâs',
'right-override-export-depth' => 'Èxportar les pâges avouéc les pâges liyêes tant qu’a na provondior de 5 nivéls',
'right-sendemail' => 'Mandar un mèssâjo ux ôtros utilisators',
'right-passwordreset' => 'Vêre los mèssâjos de remisa a zérô des contresegnos',

# User rights log
'rightslog' => 'Jornal des drêts d’utilisator',
'rightslogtext' => 'O est un jornal des changements des drêts d’utilisator.',
'rightslogentry' => 'at changiê los drêts de l’utilisator « $1 » de $2 a $3',
'rightslogentry-autopromote' => 'est étâ nomâ ôtomaticament de $2 a $3',
'rightsnone' => '(nion)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'liére cela pâge',
'action-edit' => 'changiér cela pâge',
'action-createpage' => 'fâre des pâges',
'action-createtalk' => 'fâre des pâges de discussion',
'action-createaccount' => 'fâre cél compto utilisator',
'action-minoredit' => 'marcar cél changement coment petiôt',
'action-move' => 'dèplaciér cela pâge',
'action-move-subpages' => 'dèplaciér cela pâge et les sines sot-pâges',
'action-move-rootuserpages' => 'dèplaciér les pâges utilisator principâles',
'action-movefile' => 'dèplaciér cél fichiér',
'action-upload' => 'tèlèchargiér cél fichiér',
'action-reupload' => 'ècllafar cél fichiér ègzistent',
'action-reupload-shared' => 'ècllafar localament cél fichiér present sur un dèpôt partagiê',
'action-upload_by_url' => 'tèlèchargiér cél fichiér dês n’URL',
'action-writeapi' => 'empleyér l’API d’ècritura',
'action-delete' => 'suprimar cela pâge',
'action-deleterevision' => 'suprimar cela vèrsion',
'action-deletedhistory' => 'vêre l’historico suprimâ de cela pâge',
'action-browsearchive' => 'rechèrchiér des pâges suprimâyes',
'action-undelete' => 'refâre cela pâge',
'action-suppressrevision' => 'revêre et refâre cela vèrsion cachiêe',
'action-suppressionlog' => 'vêre cél jornal privâ',
'action-block' => 'blocar en ècritura cél utilisator',
'action-protect' => 'changiér los nivéls de protèccion por cela pâge',
'action-rollback' => 'rèvocar rêdo los changements du dèrriér utilisator qu’at changiê na pâge particuliére',
'action-import' => 'importar cela pâge dês un ôtro vouiqui',
'action-importupload' => 'importar cela pâge dês un fichiér tèlèchargiê',
'action-patrol' => 'marcar lo changement des ôtros coment gouardâ',
'action-autopatrol' => 'avêr voutron changement marcâ coment gouardâ',
'action-unwatchedpages' => 'vêre la lista de les pâges pas siuvues',
'action-mergehistory' => 'fusionar l’historico de cela pâge',
'action-userrights' => 'changiér tôs los drêts d’utilisator',
'action-userrights-interwiki' => 'changiér los drêts d’utilisator des utilisators que sont sur un ôtro vouiqui',
'action-siteadmin' => 'vèrrolyér ou ben dèvèrrolyér la bâsa de donâs',
'action-sendemail' => 'mandar des mèssâjos',

# Recent changes
'nchanges' => '$1 changement{{PLURAL:$1||s}}',
'recentchanges' => 'Dèrriérs changements',
'recentchanges-legend' => 'Chouèx des dèrriérs changements',
'recentchanges-summary' => 'Siude los dèrriérs changements du vouiqui sur ceta pâge.',
'recentchanges-feed-description' => 'Siude los dèrriérs changements du vouiqui dedens ceti flux.',
'recentchanges-label-newpage' => 'Ceti changement at fêt na pâge novèla',
'recentchanges-label-minor' => 'O est un petiôt changement',
'recentchanges-label-bot' => 'Ceti changement est étâ fêt per un robot',
'recentchanges-label-unpatrolled' => 'Ceti changement est p’oncor étâ gouardâ',
'rcnote' => "Vê-que {{PLURAL:$1|lo dèrriér changement fêt|los '''$1''' dèrriérs changements fêts}} pendent {{PLURAL:$2|lo jorn passâ|los '''$2''' jorns passâs}} tant qu’a $5 lo $4.",
'rcnotefrom' => "Vê-que los changements fêts dês lo '''$2''' (tant qu’a '''$1''' montrâs).",
'rclistfrom' => 'Montrar los novéls changements dês lo $1',
'rcshowhideminor' => '$1 los petiôts changements',
'rcshowhidebots' => '$1 los robots',
'rcshowhideliu' => '$1 los utilisators branchiês',
'rcshowhideanons' => '$1 los utilisators anonimos',
'rcshowhidepatr' => '$1 los changements gouardâs',
'rcshowhidemine' => '$1 los mins changements',
'rclinks' => 'Montrar los $1 dèrriérs changements fêts pendent los $2 jorns passâs<br />$3',
'diff' => 'dif',
'hist' => 'hist',
'hide' => 'Cachiér',
'show' => 'Montrar',
'minoreditletter' => 'p',
'newpageletter' => 'N',
'boteditletter' => 'r',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilisator qu’est|utilisators que sont}} aprés siuvre]',
'rc_categories' => 'Limita de les catègories (sèparacion avouéc « | »)',
'rc_categories_any' => 'Totes',
'rc-change-size-new' => '$1 octèt{{PLURAL:$1||s}} aprés changement',
'newsectionsummary' => '/* $1 */ novèla sèccion',
'rc-enhanced-expand' => 'Montrar los dètalys (at fôta de JavaScript)',
'rc-enhanced-hide' => 'Cachiér los dètalys',
'rc-old-title' => 'fêt avouéc lo titro « $1 »',

# Recent changes linked
'recentchangeslinked' => 'Changements de les pâges liyês',
'recentchangeslinked-feed' => 'Changements de les pâges liyês',
'recentchangeslinked-toolbox' => 'Changements de les pâges liyês',
'recentchangeslinked-title' => 'Changements de les pâges liyês a « $1 »',
'recentchangeslinked-noresult' => 'Y at gins de changement sur les pâges liyês pendent lo temps chouèsi.',
'recentchangeslinked-summary' => "Ceta pâge spèciâla montre los dèrriérs changements sur les pâges que sont liyês.
Les pâges de voutra [[Special:Watchlist|lista de survelyence]] sont '''en grâs'''.",
'recentchangeslinked-page' => 'Nom de la pâge :',
'recentchangeslinked-to' => 'Fâre vêre los changements de les pâges qu’ont un lim de vers la pâge balyê pletout que l’envèrsa',

# Upload
'upload' => 'Tèlèchargiér un fichiér',
'uploadbtn' => 'Tèlèchargiér lo fichiér',
'reuploaddesc' => 'Anular lo tèlèchargement et tornar u formulèro de tèlèchargement.',
'upload-tryagain' => 'Mandar la dèscripcion du fichiér changiê',
'uploadnologin' => 'Pas branchiê',
'uploadnologintext' => 'Vos dête étre [[Special:UserLogin|branchiê]] por tèlèchargiér des fichiérs sur lo sèrvor.',
'upload_directory_missing' => 'Lo rèpèrtouèro de tèlèchargement ($1) est entrovâblo et pués at pas possu étre fêt per lo sèrvor vouèbe.',
'upload_directory_read_only' => 'Lo rèpèrtouèro de tèlèchargement ($1) est pas accèssiblo en ècritura dês lo sèrvor vouèbe.',
'uploaderror' => 'Èrror pendent lo tèlèchargement',
'upload-recreate-warning' => "'''Atencion : un fichiér avouéc cél nom at étâ suprimâ ou ben dèplaciê.'''

Los jornals de les suprèssions et des changements de nom de cela pâge sont montrâs ce-desot :",
'uploadtext' => "Empleyéd lo formulèro ce-desot por tèlèchargiér des fichiérs.
Por vêre ou ben rechèrchiér des fichiérs tèlèchargiês dês devant, vêde la [[Special:FileList|lista des fichiérs tèlèchargiês]]. Los tèlèchargements sont asse-ben encartâs dedens lo [[Special:Log/upload|jornal des tèlèchargements]], et pués les suprèssions dedens lo [[Special:Log/delete|jornal de les suprèssions]].

Por entrebetar un fichiér dedens na pâge, empleyéd un lim de yona de cetes fôrmes :
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fichiér.jpg]]</nowiki></code>''' por empleyér la vèrsion en plêna largior du fichiér
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fichiér.png|200px|thumb|left|tèxto dèscriptif]]</nowiki></code>''' por empleyér na figura de 200 pixèls de lârjo dedens na bouèta a gôche avouéc « tèxto dèscriptif » coment dèscripcion
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fichiér.ogg]]</nowiki></code>''' por liyér tot drêt vers lo fichiér sen lo fâre vêre",
'upload-permitted' => 'Formats de fichiérs ôtorisâs : $1.',
'upload-preferred' => 'Formats de fichiérs prèferâs : $1.',
'upload-prohibited' => 'Formats de fichiérs dèfendus : $1.',
'uploadlog' => 'Jornal des tèlèchargements',
'uploadlogpage' => 'Jornal des tèlèchargements',
'uploadlogpagetext' => 'Vê-que la lista des dèrriérs fichiérs tèlèchargiês sur lo sèrvor.
Vêde la [[Special:NewFiles|galerie des novéls fichiérs]] por una presentacion ples visuâla.',
'filename' => 'Nom du fichiér',
'filedesc' => 'Dèscripcion',
'fileuploadsummary' => 'Dèscripcion :',
'filereuploadsummary' => 'Changements du fichiér :',
'filestatus' => 'Statut des drêts d’ôtor :',
'filesource' => 'Sôrsa :',
'uploadedfiles' => 'Fichiérs tèlèchargiês',
'ignorewarning' => 'Ignorar l’avèrtissement et pués sôvar quand mémo lo fichiér',
'ignorewarnings' => 'Ignorar tôs los avèrtissements',
'minlength1' => 'Los noms de fichiér dêvont comprendre u muens yona lètra.',
'illegalfilename' => 'Lo nom de fichiér « $1 » contint des caractèros dèfendus dens los titros de pâges.
Lo volyéd renomar et pués lo tornar tèlèchargiér.',
'filename-toolong' => 'Lo nom du fichiér pôt pas dèpassar 240 octèts.',
'badfilename' => 'Lo fichiér at étâ renomâ en « $1 ».',
'filetype-mime-mismatch' => 'L’èxtension du fichiér « .$1 » corrèspond pas u tipo MIME dècelâ du fichiér ($2).',
'filetype-badmime' => 'Los fichiérs du tipo MIME « $1 » pôvont pas étre tèlèchargiês.',
'filetype-bad-ie-mime' => 'Lo fichiér pôt pas étre tèlèchargiê perce que serêt dècelâ coment « $1 » per Internet Explorer, cen que corrèspond a un tipo de fichiér dèfendu perce que pôt-étre dangerox.',
'filetype-unwanted-type' => "'''« .$1 »''' est un format de fichiér pas dèsirâ.
{{PLURAL:$3|Lo tipo de fichiérs recomandâ est|Los tipos de fichiérs recomandâs sont}} $2.",
'filetype-banned-type' => "'''« .$1 »''' {{PLURAL:$4|est pas un tipo de fichiérs ôtorisâ|sont pas des tipos de fichiérs ôtorisâs}}.
{{PLURAL:$3|Lo tipo de fichiérs accèptâ est|Los tipos de fichiérs accèptâs sont}} $2.",
'filetype-missing' => 'Lo fichiér at gins d’èxtension (coment « .jpg » per ègzemplo).',
'empty-file' => 'Lo fichiér que vos éd somês ére vouedo.',
'file-too-large' => 'Lo fichiér que vos éd somês ére trop grant.',
'filename-tooshort' => 'Lo nom du fichiér est trop côrt.',
'filetype-banned' => 'Cél tipo de fichiér est dèfendu.',
'verification-error' => 'Cél fichiér pâsse pas lo contrôlo des fichiérs.',
'hookaborted' => 'Lo changement que vos éd tâchiê de fâre at étâ arrètâ per un grèfon d’una èxtension.',
'illegal-filename' => 'Lo nom du fichiér est pas ôtorisâ.',
'overwrite' => 'Ècllafar un fichiér ègzistent est pas ôtorisâ.',
'unknown-error' => 'Una èrror encognua est arrevâ.',
'tmp-create-error' => 'Empossiblo de fâre lo fichiér temporèro.',
'tmp-write-error' => 'Èrror d’ècritura du fichiér temporèro.',
'large-file' => 'Los fichiérs tèlèchargiês devriant pas étre ples grant que $1 ;
cél fichiér fât $2.',
'largefileserver' => 'La talye de cél fichiér est d’amont lo nivél lo ples hôt ôtorisâ.',
'emptyfile' => 'Lo fichiér que vos voléd tèlèchargiér semble vouedo.
Cen pôt étre diu a una èrror dedens lo nom du fichiér.
Volyéd controlar que vos voléd franc tèlèchargiér cél fichiér.',
'windows-nonascii-filename' => 'Ceti vouiqui recognêt pas los noms de fichiérs avouéc des caractèros spèciâls.',
'fileexists' => 'Un fichiér avouéc cél nom ègziste ja.
Volyéd controlar <strong>[[:$1]]</strong>.
Éte-vos de sûr de lo volêr changiér ? [[$1|thumb]]',
'filepageexists' => 'La pâge de dèscripcion por cél fichiér at ja étâ fêta ique <strong>[[:$1]]</strong>, mas nion fichiér de cél nom ègziste ora.
Lo rèsumâ que vos voléd buchiér aparêtrat pas sur la pâge de dèscripcion.
Por cen fâre vos devréd changiér la pâge a la man.
[[$1|thumb]]',
'fileexists-extension' => 'Un fichiér avouéc un nom d’ense ègziste ja : [[$2|thumb]]
* Nom du fichiér a tèlèchargiér : <strong>[[:$1]]</strong>
* Nom du fichiér ègzistent : <strong>[[:$2]]</strong>
Volyéd chouèsir un ôtro nom.',
'fileexists-thumbnail-yes' => "Lo fichiér semble étre una émâge en talye rèduita ''(figura)''.
[[$1|thumb]]
Volyéd controlar lo fichiér <strong>[[:$1]]</strong>.
Se lo fichiér controlâ est la méma émâge avouéc la talye originèla, y at pas fôta de tèlèchargiér una vèrsion rèduita.",
'file-thumbnail-no' => "Lo nom du fichiér comence per <strong>$1</strong>.
O est possiblo que seye una vèrsion rèduita ''(figura)''.
Se vos avéd lo fichiér en rèsolucion ples hôta, tèlèchargiéd-lo, ôtrament volyéd changiér son nom.",
'fileexists-forbidden' => 'Un fichiér avouéc cél nom ègziste ja et pôt pas étre ècllafâ.
Se vos voléd adés tèlèchargiér voutron fichiér, volyéd tornar arriér et pués utilisar un novél nom.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fichiér avouéc cél nom ègziste ja dens lo dèpôt de fichiérs partagiê.
Se vos voléd adés tèlèchargiér voutron fichiér, volyéd tornar arriér et pués utilisar un novél nom.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Cél fichiér est un doblo {{PLURAL:$1|de ceti fichiér|de cetos fichiérs}} :',
'file-deleted-duplicate' => 'Un fichiér pariér a ceti ([[:$1]]) at ja étâ suprimâ.
Vos devriâd controlar lo jornal de les suprèssions de cél fichiér devant que lo tornar tèlèchargiér.',
'uploadwarning' => 'Atencion !',
'uploadwarning-text' => 'Changiéd la dèscripcion du fichiér et pués tornâd èprovar.',
'savefile' => 'Sôvar lo fichiér',
'uploadedimage' => 'at tèlèchargiê « [[$1]] »',
'overwroteimage' => 'at tèlèchargiê una novèla vèrsion de « [[$1]] »',
'uploaddisabled' => 'Tèlèchargements dèsactivâs',
'copyuploaddisabled' => 'Tèlèchargement de fichiér per URL dèsactivâ.',
'uploadfromurl-queued' => 'Voutron tèlèchargement at étâ betâ dens la fela d’atenta.',
'uploaddisabledtext' => 'Lo tèlèchargement de fichiérs est dèsactivâ.',
'php-uploaddisabledtext' => 'Lo tèlèchargement de fichiérs at étâ dèsactivâ dens PHP.
Volyéd controlar lo chouèx de configuracion « file_uploads ».',
'uploadscripted' => 'Cél fichiér contint de code HTML ou ben un scripte que porrêt étre entèrprètâ de façon fôssa per un navigator vouèbe.',
'uploadvirus' => 'Cél fichiér contint un virus ! Por més de dètalys, vêde : $1',
'uploadjava' => 'O est un fichiér ZIP que contint un fichiér Java .class.
Lo tèlèchargement de fichiérs Java est pas ôtorisâ, perce que pôvont entrênar des rèstriccions de sècuritât.',
'upload-source' => 'Fichiér sôrsa',
'sourcefilename' => 'Nom du fichiér sôrsa :',
'sourceurl' => 'URL sôrsa :',
'destfilename' => 'Nom du fichiér de dèstinacion :',
'upload-maxfilesize' => 'Talye la ples granta du fichiér : $1',
'upload-description' => 'Dèscripcion du fichiér',
'upload-options' => 'Chouèx de tèlèchargement',
'watchthisupload' => 'Siuvre ceti fichiér',
'filewasdeleted' => 'Un fichiér avouéc cél nom at ja étâ tèlèchargiê, et pués suprimâ.
Vos devriâd controlar lo $1 devant que lo tornar tèlèchargiér.',
'filename-bad-prefix' => "Lo nom du fichiér que vos tèlèchargiéd comence per '''« $1 »''' qu’est tipicament un nom balyê ôtomaticament per los aparèlys-fotô numericos.
Volyéd chouèsir un nom de fichiér dèscriptif.",
'filename-prefix-blacklist' => ' #<!-- lèssiéd ceta legne justo d’ense --> <pre>
# La sintaxa est ceta :
#  * Tot tèxto que siut un « # » tant qu’a la fin de la legne est un comentèro.
#  * Tota legne pas voueda est un prèfixo tipico de nom de fichiér balyê ôtomaticament per los aparèlys-fotô numericos :
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # quârques enfatâblos
IMG # g·ènèrico
JD # Jenoptik
MGP # Pentax
PICT # de totes sôrtes
 #</pre> <!-- lèssiéd ceta legne justo d’ense -->',
'upload-success-subj' => 'Tèlèchargement fêt avouéc reusséta',
'upload-success-msg' => 'Voutron tèlèchargement dês [$2] at reussi. Il est disponiblo ique : [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Problèmo de tèlèchargement',
'upload-failure-msg' => 'Y at avu un problèmo avouéc voutron tèlèchargement dês [$2] :

$1',
'upload-warning-subj' => 'Avèrtissement pendent lo tèlèchargement',
'upload-warning-msg' => 'Un problèmo est arrevâ pendent voutron tèlèchargement dês [$2]. Vos pouede tornar u [[Special:Upload/stash/$1|formulèro de tèlèchargement]] por trovar la solucion.',

'upload-proto-error' => 'Protocolo fôx',
'upload-proto-error-text' => 'Lo tèlèchargement a distance at fôta des URLs que començont per <code>http://</code> ou ben <code>ftp://</code>.',
'upload-file-error' => 'Èrror de dedens',
'upload-file-error-text' => 'Una èrror de dedens est arrevâ en volent fâre un fichiér temporèro sur lo sèrvor.
Vos volyéd veriér vers un [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error' => 'Èrror encognua pendent lo tèlèchargement',
'upload-misc-error-text' => 'Una èrror encognua est arrevâ pendent lo tèlèchargement.
Volyéd controlar que l’URL est valida et accèssibla, et pués tornâd èprovar.
Se lo problèmo continue, veriéd-vos vers un [[Special:ListUsers/sysop|administrator]].',
'upload-too-many-redirects' => 'L’URL contint trop de redirèccions',
'upload-unknown-size' => 'Talye encognua',
'upload-http-error' => 'Una èrror HTTP est arrevâ : $1',
'upload-copy-upload-invalid-domain' => 'La copia des tèlèchargements est pas disponibla dês ceti domêno.',

# File backend
'backend-fail-stream' => 'Empossiblo de liére lo fichiér $1.',
'backend-fail-backup' => 'Empossiblo de sôvar lo fichiér $1.',
'backend-fail-notexists' => 'Lo fichiér $1 ègziste pas.',
'backend-fail-hashes' => 'Empossiblo d’avêr los chaplâjos du fichiér por comparèson.',
'backend-fail-notsame' => 'Un fichiér difèrent ègziste ja por $1 .',
'backend-fail-invalidpath' => '$1 est pas un chemin de stocâjo valido.',
'backend-fail-delete' => 'Empossiblo de suprimar lo fichiér $1.',
'backend-fail-alreadyexists' => 'Lo fichiér $1 ègziste ja.',
'backend-fail-store' => 'Empossiblo de stocar lo fichiér $1 en $2.',
'backend-fail-copy' => 'Empossiblo de copiyér lo fichiér $1 vers $2.',
'backend-fail-move' => 'Empossiblo de dèplaciér lo fichiér $1 vers $2.',
'backend-fail-opentemp' => 'Empossiblo d’uvrir lo fichiér temporèro.',
'backend-fail-writetemp' => 'Empossiblo d’ècrire dedens lo fichiér temporèro.',
'backend-fail-closetemp' => 'Empossiblo de cllôre lo fichiér temporèro.',
'backend-fail-read' => 'Empossiblo de liére lo fichiér $1.',
'backend-fail-create' => 'Empossiblo d’ècrire lo fichiér $1.',
'backend-fail-maxsize' => 'Empossiblo d’ècrire lo fichiér « $1 » perce qu’il est ples grant que {{PLURAL:$2|yon octèt|$2 octèts}}.',
'backend-fail-readonly' => "Ora lo sistèmo de stocâjo « $1 » est en lèctura solèta. La rêson balyêye est : « ''$2'' »",
'backend-fail-synced' => 'Lo fichiér « $1 » est dens un ètat dèsordonâ dedens los sistèmos de stocâjo de dedens',
'backend-fail-connect' => 'Empossiblo de sè branchiér u sistèmo de stocâjo « $1 ».',
'backend-fail-internal' => 'Na fôta encognua est arrevâye dedens lo sistèmo de stocâjo « $1 ».',
'backend-fail-contenttype' => 'Empossiblo de dètèrmenar lo tipo de contegnu du fichiér a stocar en « $1 ».',
'backend-fail-batchsize' => 'Lo sistèmo de stocâjo at balyê na pârt de $1 {{PLURAL:$1|opèracion|opèracions}} de fichiér ; la limita est $2 {{PLURAL:$2|opèracion|opèracions}}.',
'backend-fail-usable' => 'Empossiblo de liére ou ben d’ècrire lo fichiér « $1 » a côsa de pèrmissions ensufisentes ou ben de rèpèrtouèros / conteniors manquents.',

# File journal errors
'filejournal-fail-dbconnect' => 'Empossiblo de sè branchiér a la bâsa de donâs du jornal por lo sistèmo de stocâjo « $1 ».',
'filejournal-fail-dbquery' => 'Empossiblo de betar a jorn la bâsa de donâs du jornal por lo sistèmo de stocâjo « $1 ».',

# Lock manager
'lockmanager-notlocked' => 'Empossiblo de dèvèrrolyér « $1 » ; el est pas vèrrolyê.',
'lockmanager-fail-closelock' => 'Empossiblo de cllôre lo fichiér de vèrroly por « $1 ».',
'lockmanager-fail-deletelock' => 'Empossiblo de suprimar lo fichiér de vèrroly por « $1 ».',
'lockmanager-fail-acquirelock' => 'Empossiblo d’avêr lo vèrroly por « $1 ».',
'lockmanager-fail-openlock' => 'Empossiblo d’uvrir lo fichiér de vèrroly por « $1 ».',
'lockmanager-fail-releaselock' => 'Empossiblo de relâchiér lo vèrroly por « $1 ».',
'lockmanager-fail-db-bucket' => 'Empossiblo de sè veriér vers prod de bâses de balyês de vèrrolyâjo dedens lo godèt $1.',
'lockmanager-fail-db-release' => 'Empossiblo de relâchiér los vèrrolys sur la bâsa de balyês $1.',
'lockmanager-fail-svr-acquire' => 'Empossiblo d’avêr des vèrrolys sur lo sèrvior $1.',
'lockmanager-fail-svr-release' => 'Empossiblo de relâchiér los vèrrolys sur lo sèrvior $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Una èrror est arrevâ pendent l’uvèrtura du fichiér ZIP por contrôlo.',
'zip-wrong-format' => 'Lo fichiér spècefiâ est pas un fichiér ZIP.',
'zip-bad' => 'Lo fichiér est un fichiér ZIP corrompu ou ben iliésiblo.
Pôt pas étre controlâ tot drêt por la sècuritât.',
'zip-unsupported' => 'Lo fichiér est un fichiér ZIP qu’utilise ZIP pas recognues per MediaWiki.
Pôt pas étre controlâ tot drêt por la sècuritât.',

# Special:UploadStash
'uploadstash' => 'Cache d’importacion',
'uploadstash-summary' => 'Ceta pâge balye accès ux fichiérs que sont importâs (ou ben en cors d’importacion), mas sont p’oncor publeyês dens lo vouiqui. Celos fichiérs sont p’oncor visiblos, solament por l’usanciér que los at importâs.',
'uploadstash-clear' => 'Èfaciér los fichiérs en cache d’importacion',
'uploadstash-nofiles' => 'Vos avéd gins de fichiér en cache d’importacion.',
'uploadstash-badtoken' => 'L’ègzécucion de cela accion at pas reussia, pôt-étre perce que voutres enformacions de branchement ont èxpirâs. Tornâd èprovar.',
'uploadstash-errclear' => 'L’èfacement des fichiérs at pas reussi.',
'uploadstash-refresh' => 'Rafrèchir la lista des fichiérs',
'invalid-chunk-offset' => 'Comencement de bocon envalido',

# img_auth script messages
'img-auth-accessdenied' => 'Accès refusâ',
'img-auth-nopathinfo' => 'PATH_INFO manquent.
Voutron sèrvor est pas dèfeni por passar cela enformacion.
Fonccione pôt-étre en CGI et pués recognêt pas img_auth.
Vêde https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir' => 'Lo chemin demandâ est pas lo rèpèrtouèro de tèlèchargement configurâ.',
'img-auth-badtitle' => 'Empossiblo de construire un titro valido dês « $1 ».',
'img-auth-nologinnWL' => 'Vos éte pas branchiê et pués « $1 » est pas dens la lista blanche.',
'img-auth-nofile' => 'Lo fichiér « $1 » ègziste pas.',
'img-auth-isdir' => 'Vos tâchiéd d’arrevar u rèpèrtouèro « $1 ».
Solament l’accès ux fichiérs est pèrmês.',
'img-auth-streaming' => 'Lèctura en continu de « $1 ».',
'img-auth-public' => 'La fonccion de img_auth.php est de fâre vêre des fichiérs d’un vouiqui privâ.
Ceti vouiqui est configurâ coment un vouiqui publico.
Por una sècuritât parfèta, img_auth.php est dèsactivâ.',
'img-auth-noread' => 'L’usanciér at pas lo drêt en lèctura dessus « $1 ».',
'img-auth-bad-query-string' => 'L’URL at una chêna de requéta envalida.',

# HTTP errors
'http-invalid-url' => 'URL fôssa : $1',
'http-invalid-scheme' => 'Les URLs avouéc lo plan « $1 » sont pas recognues.',
'http-request-error' => 'Èrror encognua pendent l’èxpèdicion de la requéta.',
'http-read-error' => 'Èrror de lèctura HTTP.',
'http-timed-out' => 'La requéta HTTP at èxpirâ.',
'http-curl-error' => 'Èrror pendent la rècupèracion de l’URL : $1',
'http-host-unreachable' => 'URL pas juentâbla.',
'http-bad-status' => 'Y at avu un problèmo pendent la requéta HTTP : $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL pas juentâbla',
'upload-curl-error6-text' => 'L’URL balyê pôt pas étre juenta.
Volyéd controlar que l’URL est justa et que lo seto est en legne.',
'upload-curl-error28' => 'Dèpassement du dèlê pendent lo tèlèchargement',
'upload-curl-error28-text' => 'Lo seto at betâ trop grant-temps a rèpondre.
Volyéd controlar que lo seto est en legne, atendre un pou et pués tornar èprovar.
Vos pouede asse-ben tornar èprovar a una hora de muendra afluence.',

'license' => 'Licence :',
'license-header' => 'Licence',
'nolicense' => 'Gins de licence chouèsia',
'license-nopreview' => '(Prèvisualisacion pas disponibla)',
'upload_source_url' => '  (una URL valida et accèssibla publicament)',
'upload_source_file' => '  (un fichiér sur voutron ordenator)',

# Special:ListFiles
'listfiles-summary' => 'Ceta pâge spèciâla montre tôs los fichiérs tèlèchargiês.
Quand el est filtrâ per usanciér, solament los fichiérs que la vèrsion la ples novèla at étâ importâ per cél usanciér sont montrâs.',
'listfiles_search_for' => 'Rechèrchiér un nom de mèdia :',
'imgfile' => 'fichiér',
'listfiles' => 'Lista des fichiérs',
'listfiles_thumb' => 'Figura',
'listfiles_date' => 'Dâta',
'listfiles_name' => 'Nom',
'listfiles_user' => 'Usanciér',
'listfiles_size' => 'Talye',
'listfiles_description' => 'Dèscripcion',
'listfiles_count' => 'Vèrsions',

# File description page
'file-anchor-link' => 'Fichiér',
'filehist' => 'Historico du fichiér',
'filehist-help' => 'Clicar sur na dâta et hora por vêre lo fichiér coment il ére a cél moment.',
'filehist-deleteall' => 'suprimar tot',
'filehist-deleteone' => 'suprimar',
'filehist-revert' => 'rètablir',
'filehist-current' => 'ora',
'filehist-datetime' => 'Dâta et hora',
'filehist-thumb' => 'Figura',
'filehist-thumbtext' => 'Figura por la vèrsion du $1',
'filehist-nothumb' => 'Gins de figura',
'filehist-user' => 'Usanciér',
'filehist-dimensions' => 'Dimensions',
'filehist-filesize' => 'Talye du fichiér',
'filehist-comment' => 'Comentèro',
'filehist-missing' => 'Fichiér manquent',
'imagelinks' => 'Usâjo du fichiér',
'linkstoimage' => '{{PLURAL:$1|Ceta pâge utilise|Cetes $1 pâges utilisont}} ceti fichiér :',
'linkstoimage-more' => 'Més de {{PLURAL:$1|yona pâge utilise|$1 pâges utilisont}} ceti fichiér.
Ceta lista montre ren que {{PLURAL:$1|la premiére pâge qu’utilise|les $1 premiéres pâges qu’utilisont}} ceti fichiér.
Una [[Special:WhatLinksHere/$2|lista complèta]] est disponibla.',
'nolinkstoimage' => 'Niona pâge utilise ceti fichiér.',
'morelinkstoimage' => 'Vêde [[Special:WhatLinksHere/$1|més de lims]] de vers ceti fichiér.',
'linkstoimage-redirect' => '$1 (redirèccion de fichiér) $2',
'duplicatesoffile' => '{{PLURAL:$1|Ceti fichiér est un doblo|Cetos fichiérs sont des doblos}} de ceti ([[Special:FileDuplicateSearch/$2|més de dètalys]]) :',
'sharedupload' => 'Ceti fichiér vint de $1 et pôt étre utilisâ per d’ôtros projèts.',
'sharedupload-desc-there' => 'Ceti fichiér vint de $1 et pôt étre utilisâ per d’ôtros projèts.
Vêde sa [$2 pâge de dèscripcion] por més d’enformacions.',
'sharedupload-desc-here' => 'Ceti fichiér vint de $1 et pôt étre utilisâ per d’ôtros projèts.
La dèscripcion de sa [$2 pâge de dèscripcion] est montrâ ce-desot.',
'filepage-nofile' => 'Nion fichiér de cél nom ègziste.',
'filepage-nofile-link' => 'Nion fichiér de cél nom ègziste, mas vos en pouede [$1 tèlèchargiér yon].',
'uploadnewversion-linktext' => 'Tèlèchargiér una novèla vèrsion de ceti fichiér',
'shared-repo-from' => 'de $1',
'shared-repo' => 'un dèpôt partagiê',
'filepage.css' => '/* Lo code CSS betâ ique est encllu dens la pâge de dèscripcion du fichiér, et pués dens los vouiquis cliants ètrangiérs. */',

# File reversion
'filerevert' => 'Rètablir $1',
'filerevert-legend' => 'Rètablir lo fichiér',
'filerevert-intro' => "Vos éte prèst a rètablir lo fichiér '''[[Media:$1|$1]]''' a la [$4 vèrsion du $2 a $3].",
'filerevert-comment' => 'Rêson :',
'filerevert-defaultcomment' => 'Vèrsion du $1 a $2 rètablia',
'filerevert-submit' => 'Rètablir',
'filerevert-success' => "'''[[Media:$1|$1]]''' at étâ rètabli a la [$4 vèrsion du $2 a $3].",
'filerevert-badversion' => 'Y at gins de vèrsion ples vielye du fichiér avouéc la dâta balyê.',

# File deletion
'filedelete' => 'Suprimar $1',
'filedelete-legend' => 'Suprimar lo fichiér',
'filedelete-intro' => "Vos éte prèst a suprimar '''[[Media:$1|$1]]''' et pués tot son historico.",
'filedelete-intro-old' => "Vos éte aprés suprimar la vèrsion de '''[[Media:$1|$1]]''' du [$4 $2 a $3].",
'filedelete-comment' => 'Rêson :',
'filedelete-submit' => 'Suprimar',
'filedelete-success' => "'''$1''' at étâ suprimâ.",
'filedelete-success-old' => "La vèrsion de '''[[Media:$1|$1]]''' du $2 a $3 at étâ suprimâ.",
'filedelete-nofile' => "'''$1''' ègziste pas.",
'filedelete-nofile-old' => "Ègziste gins de vèrsion arch·ivâ de '''$1''' avouéc los atributs spècefiâs.",
'filedelete-otherreason' => 'Ôtra rêson / rêson de ples :',
'filedelete-reason-otherlist' => 'Ôtra rêson',
'filedelete-reason-dropdown' => '*Rêsons corentes de suprèssion
** Violacion du drêt d’ôtor
** Fichiér en doblo',
'filedelete-edit-reasonlist' => 'Changiér les rêsons de suprèssion',
'filedelete-maintenance' => 'La suprèssion et la rèstoracion de fichiérs est dèsactivâ temporèrament pendent la mantegnence.',
'filedelete-maintenance-title' => 'Empossiblo de suprimar lo fichiér',

# MIME search
'mimesearch' => 'Rechèrche per tipo de contegnu MIME',
'mimesearch-summary' => "Ceta pâge vos pèrmèt de listar los fichiérs accèssiblos per ceti vouiqui d’aprés lor tipo de contegnu MIME.
Entrâ : ''tipo de contegnu''/''sot-tipo'', per ègzemplo <code>image/jpeg</code>.",
'mimetype' => 'Tipo MIME :',
'download' => 'Tèlèchargiér',

# Unwatched pages
'unwatchedpages' => 'Pâges pas siuvues',

# List redirects
'listredirects' => 'Lista de les redirèccions',

# Unused templates
'unusedtemplates' => 'Modèlos inutilisâs',
'unusedtemplatestext' => 'Ceta pâge liste totes les pâges de l’èspâço de noms « {{ns:template}} » que sont pas entrebetâyes dedens niona ôtra pâge.
Oubliâd pas de controlar s’y at gins d’ôtro lim de vers los modèlos devant que los suprimar.',
'unusedtemplateswlh' => 'ôtros lims',

# Random page
'randompage' => 'Pâge a l’hasârd',
'randompage-nopages' => 'Y at gins de pâge dens {{PLURAL:$2|ceti èspâço|cetos èspâços}} de noms : $1.',

# Random redirect
'randomredirect' => 'Pâge de redirèccion a l’hasârd',
'randomredirect-nopages' => 'Y at gins de pâge de redirèccion dens l’èspâço de noms « $1 ».',

# Statistics
'statistics' => 'Statistiques',
'statistics-header-pages' => 'Statistiques de les pâges',
'statistics-header-edits' => 'Statistiques des changements',
'statistics-header-views' => 'Statistiques de les visualisacions',
'statistics-header-users' => 'Statistiques ux usanciérs',
'statistics-header-hooks' => 'Ôtres statistiques',
'statistics-articles' => 'Pâges de contegnu',
'statistics-pages' => 'Pâges',
'statistics-pages-desc' => 'Totes les pâges du vouiqui, les pâges de discussion, les redirèccions, ... avouéc',
'statistics-files' => 'Fichiérs tèlèchargiês',
'statistics-edits' => 'Changements de pâges dês l’enstalacion de {{SITENAME}}',
'statistics-edits-average' => 'Nombro moyen de changements per pâge',
'statistics-views-total' => 'Soma de les visualisacions',
'statistics-views-total-desc' => 'Les visualisacions de les pâges pas ègzistentes et de les pâges spèciâles sont pas encllues',
'statistics-views-peredit' => 'Visualisacions per changement',
'statistics-users' => '[[Special:ListUsers|Usanciérs]] encartâs',
'statistics-users-active' => 'Usanciérs actifs',
'statistics-users-active-desc' => 'Usanciérs qu’ont fêt u muens una accion pendent {{PLURAL:$1|lo jorn passâ|los $1 jorns passâs}}',
'statistics-mostpopular' => 'Pâges les ples vues',

'disambiguations' => 'Pâges qu’ont des lims de vers des pâges d’homonimia',
'disambiguationspage' => 'Template:Homonimia',
'disambiguations-text' => "Cetes pâges ont u muens yon lim de vers na '''pâge d’homonimia'''.
Devriant pletout pouentar vers na pâge que vat avouéc.<br />
Na pâge est trètâye coment na pâge d’homonimia s’empleye un modèlo liyê a [[MediaWiki:Disambiguationspage]].",

'doubleredirects' => 'Redirèccions dobles',
'doubleredirectstext' => 'Vê-que la lista de les pâges que redirijont vers des pâges que sont lor-mémes des pâges de redirèccion.
Châque entrâ contint des lims de vers la premiére et la seconda redirèccion, et pués la premiére legne de tèxto de la seconda pâge, cen que balye habituèlament la « veré » pâge ciba, de vers laquinta la premiére redirèccion devrêt redirigiér.
Les entrâs <del>barrâs</del> ont étâ solucionâs.',
'double-redirect-fixed-move' => 'Cela redirèccion, que la ciba [[$1]] at étâ renomâ, mène ora vers [[$2]].',
'double-redirect-fixed-maintenance' => 'Correge la redirèccion dobla de [[$1]] vers [[$2]].',
'double-redirect-fixer' => 'Corrèctor de redirèccion',

'brokenredirects' => 'Redirèccions câsses',
'brokenredirectstext' => 'Cetes redirèccions mènont vers des pâges pas ègzistentes :',
'brokenredirects-edit' => 'changiér',
'brokenredirects-delete' => 'suprimar',

'withoutinterwiki' => 'Pâges sen lims entèrlengoues',
'withoutinterwiki-summary' => 'Cetes pâges ont gins de lim de vers d’ôtres lengoues :',
'withoutinterwiki-legend' => 'Prèfixo',
'withoutinterwiki-submit' => 'Montrar',

'fewestrevisions' => 'Pâges les muens changiês',

# Miscellaneous special pages
'nbytes' => '$1 octèt{{PLURAL:$1||s}}',
'ncategories' => '$1 catègorie{{PLURAL:$1||s}}',
'ninterwikis' => '$1 {{PLURAL:$1|lim entèrvouiqui|lims entèrvouiquis}}',
'nlinks' => '$1 lim{{PLURAL:$1||s}}',
'nmembers' => '$1 membro{{PLURAL:$1||s}}',
'nrevisions' => '$1 vèrsion{{PLURAL:$1||s}}',
'nviews' => '$1 visualisacion{{PLURAL:$1||s}}',
'nimagelinks' => 'Utilisâ dessus $1 pâge{{PLURAL:$1||s}}',
'ntransclusions' => 'utilisâ dessus $1 pâge{{PLURAL:$1||s}}',
'specialpage-empty' => 'Y at gins de rèsultat a fâre vêre.',
'lonelypages' => 'Pâges orfenes',
'lonelypagestext' => 'Cetes pâges sont pas liyês ou ben encllues dês d’ôtres pâges de {{SITENAME}}.',
'uncategorizedpages' => 'Pâges sen catègorie',
'uncategorizedcategories' => 'Catègories sen catègorie',
'uncategorizedimages' => 'Fichiérs sen catègorie',
'uncategorizedtemplates' => 'Modèlos sen catègorie',
'unusedcategories' => 'Catègories inutilisâs',
'unusedimages' => 'Fichiérs inutilisâs',
'popularpages' => 'Pâges les ples vues',
'wantedcategories' => 'Catègories les ples demandâs',
'wantedpages' => 'Pâges demandâyes',
'wantedpages-badtitle' => 'Titro envalido dens los rèsultats : $1',
'wantedfiles' => 'Fichiérs demandâs',
'wantedfiletext-cat' => 'Cetos fichiérs sont utilisâs, mas ègzistont pas. Los fichiérs de dèpôts a distance pôvont étre listâs mâlgrât qu’ègzistont. Tot celos fôx positifs seront <del>traciês</del>. Pués, les pâges qu’apondont des fichiérs qu’ègzistont pas sont rèpèrtoriyês dedens [[:$1]].',
'wantedfiletext-nocat' => 'Cetos fichiérs sont utilisâs, mas ègzistont pas. Los fichiérs de dèpôts a distance pôvont étre listâs mâlgrât qu’ègzistont. Tot celos fôx positifs seront <del>traciês</del>.',
'wantedtemplates' => 'Modèlos los ples demandâs',
'mostlinked' => 'Pâges les ples liyês',
'mostlinkedcategories' => 'Catègories les ples utilisâs',
'mostlinkedtemplates' => 'Modèlos los ples utilisâs',
'mostcategories' => 'Pâges qu’utilisont lo més de catègories',
'mostimages' => 'Fichiérs los ples utilisâs',
'mostinterwikis' => 'Pâges avouéc lo més de lims entèrvouiquis',
'mostrevisions' => 'Pâges les ples changiês',
'prefixindex' => 'Totes les pâges que començont per...',
'prefixindex-namespace' => 'Totes les pâges avouéc prèfixo (èspâço de noms $1)',
'shortpages' => 'Pâges côrtes',
'longpages' => 'Pâges longes',
'deadendpages' => 'Pâges en cul-de-sac',
'deadendpagestext' => 'Cetes pâges ont gins de lim de vers d’ôtres pâges de {{SITENAME}}.',
'protectedpages' => 'Pâges protègiês',
'protectedpages-indef' => 'Solament les protèccions sen fin',
'protectedpages-cascade' => 'Solament les protèccions en cascâda',
'protectedpagestext' => 'Cetes pâges sont protègiês contre los changements et/ou lo changement de nom :',
'protectedpagesempty' => 'Ora, niona pâge est protègiê avouéc celos paramètres.',
'protectedtitles' => 'Titros protègiês',
'protectedtitlestext' => 'Cetos titros sont protègiês a la crèacion :',
'protectedtitlesempty' => 'Ora, nion titro est protègiê avouéc celos paramètres.',
'listusers' => 'Lista ux usanciérs',
'listusers-editsonly' => 'Fâre vêre ren que los usanciérs qu’ont u muens yona contribucion',
'listusers-creationsort' => 'Triyér per dâta de crèacion',
'usereditcount' => '$1 changement{{PLURAL:$1||s}}',
'usercreated' => 'Fêt{{GENDER:$3||a}} lo $1 a $2',
'newpages' => 'Pâges novèles',
'newpages-username' => 'Nom d’utilisator :',
'ancientpages' => 'Pâges les muens dèrriérement changiês',
'move' => 'Renomar',
'movethispage' => 'Renomar ceta pâge',
'unusedimagestext' => 'Cetos fichiérs ègzistont, mas sont pas encllus dens niona pâge.
Volyéd notar que d’ôtros setos pôvont avêr un lim drêt de vers un fichiér, et donc qu’un fichiér pôt étre listâ ique pendent qu’il est en rèalitât utilisâ sur celos setos.',
'unusedcategoriestext' => 'Cetes catègories ègzistont mas gins de pâge ou ben de catègorie les utilise.',
'notargettitle' => 'Gins de ciba',
'notargettext' => 'Vos éd pas spècefiâ una pâge ou ben un usanciér ciba sur laquinta / loquint vos souhètâd fâre cela accion.',
'nopagetitle' => 'Gins de pâge ciba',
'nopagetext' => 'La pâge ciba que vos éd spècefiâ ègziste pas.',
'pager-newer-n' => '{{PLURAL:$1|ples novèla|$1 ples novèles}}',
'pager-older-n' => '{{PLURAL:$1|ples vielye|$1 ples vielyes}}',
'suppress' => 'Ôtar',
'querypage-disabled' => 'Ceta pâge spèciâla est dèsactivâ por des rêsons de capacitât.',

# Book sources
'booksources' => 'Ôvres de refèrence',
'booksources-search-legend' => 'Rechèrchiér permié des ôvres de refèrence',
'booksources-isbn' => 'ISBN :',
'booksources-go' => 'Listar',
'booksources-text' => 'Vê-que la lista endicativa et pas èxcllusiva de lims de vers d’ôtros setos que vendont des lévros nôfs et d’ocasion et sur losquints vos troveréd pôt-étre des enformacions sur les ôvres que vos chèrchiéd :',
'booksources-invalid-isbn' => 'L’ISBN balyê semble pas étre valido ; controlâd se vos éd fêt una èrror en copiyent la sôrsa originâla.',

# Special:Log
'specialloguserlabel' => 'Ôtor :',
'speciallogtitlelabel' => 'Ciba (titro ou ben usanciér) :',
'log' => 'Jornals',
'all-logs-page' => 'Tôs los jornals publicos',
'alllogstext' => 'Visualisacion combinâ de tôs los jornals disponiblos dessus {{SITENAME}}.
Vos pouede rètrendre la vua en chouèséssent un tipo de jornal, un nom d’usanciér (sensiblo a la câssa) ou ben una pâge afèctâ (sensibla a la câssa avouéc).',
'logempty' => 'Nion èlèment d’ense at étâ trovâ dens lo jornal.',
'log-title-wildcard' => 'Chèrchiér permié los titros que començont per ceti tèxto',
'showhideselectedlogentries' => 'Fâre vêre / cachiér les entrâs de jornal chouèsies',

# Special:AllPages
'allpages' => 'Totes les pâges',
'alphaindexline' => 'de $1 a $2',
'nextpage' => 'Pâge aprés ($1)',
'prevpage' => 'Pâge devant ($1)',
'allpagesfrom' => 'Fâre vêre les pâges dês :',
'allpagesto' => 'Fâre vêre les pâges tant qu’a :',
'allarticles' => 'Totes les pâges',
'allinnamespace' => 'Totes les pâges (dens l’èspâço de noms « $1 »)',
'allnotinnamespace' => 'Totes les pâges (en defôr de l’èspâço de noms « $1 »)',
'allpagesprev' => 'Devant',
'allpagesnext' => 'Aprés',
'allpagessubmit' => 'Listar',
'allpagesprefix' => 'Fâre vêre les pâges que començont per lo prèfixo :',
'allpagesbadtitle' => 'Lo titro de pâge balyê est fôx ou ben il at un prèfixo entèrlengoua ou entèrvouiqui resèrvâ.
Contint sûrement yon ou ben un mouél de caractèros que pôvont pas étre utilisâs dens los titros.',
'allpages-bad-ns' => '{{SITENAME}} at gins d’èspâço de noms « $1 ».',
'allpages-hide-redirects' => 'Cachiér les redirèccions',

# SpecialCachedPage
'cachedspecial-refresh-now' => 'Vêre lo ples novél.',

# Special:Categories
'categories' => 'Catègories',
'categoriespagetext' => '{{PLURAL:$1|Ceta catègorie contint|Cetes catègories contegnont}} des pâges ou ben des fichiérs mèdia.
Les [[Special:UnusedCategories|catègories inutilisâs]] sont pas montrâs ique.
Vêde asse-ben les [[Special:WantedCategories|catègories les ples demandâs]].',
'categoriesfrom' => 'Fâre vêre les catègories dês :',
'special-categories-sort-count' => 'tri per nombro d’èlèments',
'special-categories-sort-abc' => 'tri alfabètico',

# Special:DeletedContributions
'deletedcontributions' => 'Contribucions suprimâs',
'deletedcontributions-title' => 'Contribucions suprimâs',
'sp-deletedcontributions-contribs' => 'contribucions',

# Special:LinkSearch
'linksearch' => 'Rechèrche de lims de defôr',
'linksearch-pat' => 'Modèlo de rechèrche :',
'linksearch-ns' => 'Èspâço de noms :',
'linksearch-ok' => 'Rechèrchiér',
'linksearch-text' => 'Des caractèros j·oquères coment « *.wikipedia.org » pôvont étre utilisâs.
Ils ont fôta d’u muens un domêno de nivél supèrior, per ègzemplo « *.org ».<br />
Protocolos recognus : <code>$1</code> (apondéd gins de cetos dedens voutra rechèrche).',
'linksearch-line' => '$1 est liyê dês $2',
'linksearch-error' => 'Los caractèros j·oquères pôvont étre utilisâs ren qu’u comencement du nom de domêno de l’hôto.',

# Special:ListUsers
'listusersfrom' => 'Fâre vêre los utilisators dês :',
'listusers-submit' => 'Montrar',
'listusers-noresult' => 'Gins d’usanciér trovâ.',
'listusers-blocked' => '(blocâ)',

# Special:ActiveUsers
'activeusers' => 'Lista ux usanciérs actifs',
'activeusers-intro' => 'O est una lista ux usanciérs qu’ont ègzèrciê una activitât quinta que seye pendent {{PLURAL:$1|lo jorn passâ|los $1 jorns passâs}}.',
'activeusers-count' => '$1 {{PLURAL:$1|novél changement|novéls changements}} dens {{PLURAL:$3|lo jorn passâ|los $3 jorns passâs}}',
'activeusers-from' => 'Fâre vêre los utilisators dês :',
'activeusers-hidebots' => 'Cachiér los bots',
'activeusers-hidesysops' => 'Cachiér los administrators',
'activeusers-noresult' => 'Gins d’usanciér trovâ.',

# Special:Log/newusers
'newuserlogpage' => 'Jornal de les crèacions d’utilisators',
'newuserlogpagetext' => 'O est un jornal de les crèacions d’utilisators.',

# Special:ListGroupRights
'listgrouprights' => 'Drêts a les tropes d’usanciérs',
'listgrouprights-summary' => 'Ceta pâge contint una lista a les tropes dèfenies sur ceti vouiqui et pués los drêts d’accès que lor sont associyês.
Y pôt avêr [[{{MediaWiki:Listgrouprights-helppage}}|més d’enformacions]] sur los drêts particuliérs.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Drêt balyê</span>
* <span class="listgrouprights-revoked">Drêt rèvocâ</span>',
'listgrouprights-group' => 'Tropa',
'listgrouprights-rights' => 'Drêts associyês',
'listgrouprights-helppage' => 'Help:Drêts a les tropes',
'listgrouprights-members' => '(lista ux membros)',
'listgrouprights-addgroup' => 'Apondre des membros a {{PLURAL:$2|la tropa|les tropes}} : $1',
'listgrouprights-removegroup' => 'Enlevar des membros de {{PLURAL:$2|la tropa|les tropes}} : $1',
'listgrouprights-addgroup-all' => 'Apondre des membros a totes les tropes',
'listgrouprights-removegroup-all' => 'Enlevar des membros de totes les tropes',
'listgrouprights-addgroup-self' => 'Sè pôt apondre {{PLURAL:$2|la tropa|les tropes}} a son prôpro compto : $1',
'listgrouprights-removegroup-self' => 'Sè pôt enlevar {{PLURAL:$2|la tropa|les tropes}} de son prôpro compto : $1',
'listgrouprights-addgroup-self-all' => 'Sè pôt apondre totes les tropes a son prôpro compto',
'listgrouprights-removegroup-self-all' => 'Sè pôt enlevar totes les tropes de son prôpro compto',

# E-mail user
'mailnologin' => 'Gins d’adrèce d’èxpèdior',
'mailnologintext' => 'Vos dête étre [[Special:UserLogin|branchiê]] et avêr endicâ una adrèce èlèctronica valida dens voutres [[Special:Preferences|prèferences]] por povêr mandar des mèssâjos a d’ôtros usanciérs.',
'emailuser' => 'Lui mandar un mèssâjo',
'emailuser-title-target' => 'Mandar un mèssâjo a cet’utilisat{{GENDER:$1|or|rice}}',
'emailuser-title-notarget' => 'Mandar un mèssâjo a l’utilisator',
'emailpage' => 'Mandar un mèssâjo a l’utilisator',
'emailpagetext' => 'Vos pouede empleyér lo formulèro ce-desot por mandar un mèssâjo a cet’utilisat{{GENDER:$1|or|rice}}.
L’adrèce èlèctronica que vos éd buchiêye dens voutres [[Special:Preferences|prèferences]] aparètrat dedens lo champ « Èxpèdior » de voutron mèssâjo ; d’ense, lo dèstinatèro vos porrat rèpondre tot drêt.',
'usermailererror' => 'Èrror dens lo sujèt du mèssâjo :',
'defemailsubject' => 'Mèssâjo de {{SITENAME}} de l’usanciér « $1 »',
'usermaildisabled' => 'L’èxpèdicion de mèssâjos entre-mié usanciérs est dèsactivâ',
'usermaildisabledtext' => 'Vos pouede pas mandar des mèssâjos a d’ôtros usanciérs sur ceti vouiqui',
'noemailtitle' => 'Dèstinatèro sen adrèce èlèctronica',
'noemailtext' => 'Ceti usanciér at pas spècefiâ una adrèce èlèctronica valida.',
'nowikiemailtitle' => 'Gins de mèssageria èlèctronica ôtorisâ',
'nowikiemailtext' => 'Ceti usanciér at chouèsi de pas recêvre de mèssâjo de la pârt d’ôtros usanciérs.',
'emailnotarget' => 'Nom d’usanciér u dèstinatèro pas ègzistent ou ben envalido.',
'emailtarget' => 'Buchiéd lo nom d’usanciér u dèstinatèro',
'emailusername' => 'Nom d’utilisator :',
'emailusernamesubmit' => 'Sometre',
'email-legend' => 'Mandar un mèssâjo a un ôtro usanciér de {{SITENAME}}',
'emailfrom' => 'De :',
'emailto' => 'Dèstinatèro :',
'emailsubject' => 'Sujèt :',
'emailmessage' => 'Mèssâjo :',
'emailsend' => 'Mandar',
'emailccme' => 'Mè mandar per mèssageria èlèctronica una copia de mon mèssâjo.',
'emailccsubject' => 'Copia de voutron mèssâjo a $1 : $2',
'emailsent' => 'Mèssâjo mandâ',
'emailsenttext' => 'Voutron mèssâjo at étâ mandâ per mèssageria èlèctronica.',
'emailuserfooter' => 'Ceti mèssâjo at étâ mandâ per « $1 » a « $2 » per la fonccion « Lui mandar un mèssâjo » de {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'At lèssiê un mèssâjo sistèmo.',
'usermessage-editor' => 'Mèssagiér du sistèmo',
'usermessage-template' => 'MediaWiki:MèssâjoUtilisator',

# Watchlist
'watchlist' => 'Lista de survelyence',
'mywatchlist' => 'Lista de survelyence',
'watchlistfor2' => 'Por $1 $2',
'nowatchlist' => 'Voutra lista de survelyence contint gins d’èlèment.',
'watchlistanontext' => 'Vos volyéd $1 por vêre ou ben changiér les piéces de voutra lista de survelyence.',
'watchnologin' => 'Pas branchiê',
'watchnologintext' => 'Vos dête étre [[Special:UserLogin|branchiê]] por changiér voutra lista de survelyence.',
'addwatch' => 'Apondre a la lista de survelyence',
'addedwatchtext' => 'La pâge « [[:$1]] » est étâye apondua a voutra [[Special:Watchlist|lista de survelyence]].
Los changements que vegnont de ceta pâge et de la sina pâge de discussion y seront listâs.',
'removewatch' => 'Enlevar de la lista de survelyence',
'removedwatchtext' => 'La pâge « [[:$1]] » at étâ enlevâ de voutra [[Special:Watchlist|lista de survelyence]].',
'watch' => 'Siuvre',
'watchthispage' => 'Siuvre ceta pâge',
'unwatch' => 'Pas més siuvre',
'unwatchthispage' => 'Pas més siuvre',
'notanarticle' => 'Pas una pâge de contegnu',
'notvisiblerev' => 'La vèrsion at étâ suprimâ',
'watchnochange' => 'Nion des èlèments que vos siude at étâ changiê pendent lo temps montrâ.',
'watchlist-details' => 'Voutra lista de survelyence contint $1 pâge{{PLURAL:$1||s}}, sen comptar les pâges de discussion.',
'wlheader-enotif' => '* La notificacion per mèssageria èlèctronica est activâ.',
'wlheader-showupdated' => "* Les pâges qu’ont étâ changiês dês voutra dèrriére visita sont montrâs en '''grâs'''.",
'watchmethod-recent' => 'contrôlo des novéls changements por y trovar des pâges siuvues',
'watchmethod-list' => 'contrôlo de les pâges siuvues por y trovar des novéls changements',
'watchlistcontains' => 'Voutra lista de survelyence contint $1 pâge{{PLURAL:$1||s}}.',
'iteminvalidname' => 'Problèmo avouéc l’èlèment « $1 » : lo nom est envalido.',
'wlnote' => "Vê-que {{PLURAL:$1|lo dèrriér changement fêt|los '''$1''' dèrriérs changements fêts}} pendent {{PLURAL:$2|l’hora passâ|les '''$2''' hores passâs}}, dês $3, $4.",
'wlshowlast' => 'Montrar les $1 hores passâyes, los $2 jorns passâs ou ben $3',
'watchlist-options' => 'Chouèx de la lista de survelyence',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Survelyence...',
'unwatching' => 'Fin de la survelyence...',
'watcherrortext' => 'Una èrror est arrevâ pendent lo changement des paramètres de voutra lista de survelyence por « $1 ».',

'enotif_mailer' => 'Sistèmo de notificacion per mèssageria èlèctronica de {{SITENAME}}',
'enotif_reset' => 'Marcar totes les pâges coment visitâs',
'enotif_newpagetext' => 'O est una pâge novèla.',
'enotif_impersonal_salutation' => 'Usanciér de {{SITENAME}}',
'changed' => 'changiê',
'created' => 'fêta',
'enotif_subject' => 'La pâge « $PAGETITLE » de {{SITENAME}} at étâ $CHANGEDORCREATED per $PAGEEDITOR',
'enotif_lastvisited' => 'Vêde $1 por tôs los changements dês voutra dèrriére visita.',
'enotif_lastdiff' => 'Vêde $1 por vêre cél changement.',
'enotif_anon_editor' => 'utilisator anonimo $1',
'enotif_body' => 'Chier(a) $WATCHINGUSERNAME,


la pâge « $PAGETITLE » de {{SITENAME}} est étâye $CHANGEDORCREATED lo $PAGEEDITDATE per « $PAGEEDITOR », vêde $PAGETITLE_URL por la vèrsion d’ora.

$NEWPAGE

Rèsumâ du contributor : $PAGESUMMARY $PAGEMINOREDIT

Veriéd-vos vers lo contributor :
mèl. : $PAGEEDITOR_EMAIL
vouiqui : $PAGEEDITOR_WIKI

Y arat gins d’ôtra notificacion en câs de changements a vegnir, a muens que vos visitâd cela pâge. Vos pouede asse-ben tornar inicialisar los segnalements de notificacion por totes les pâges de voutra lista de survelyence.

Voutron sistèmo de notificacion de {{SITENAME}}

--
Por changiér la configuracion de notificacion per mèssageria èlèctronica, visitâd
{{canonicalurl:{{#special:Preferences}}}}

Por changiér la configuracion de voutra lista de survelyence, visitâd
{{canonicalurl:{{#special:EditWatchlist}}}}

Por suprimar la pâge de voutra lista de survelyence, visitâd
$UNWATCHURL

Avis et assistance de ples :
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Suprimar la pâge',
'confirm' => 'Confirmar',
'excontent' => 'contegnéve « $1 »',
'excontentauthor' => 'contegnéve « $1 » (et son solèt contributor ére « [[Special:Contributions/$2|$2]] »)',
'exbeforeblank' => 'contegnéve devant blanchiment « $1 »',
'exblank' => 'la pâge ére voueda',
'delete-confirm' => 'Suprimar « $1 »',
'delete-legend' => 'Suprimar',
'historywarning' => "'''Atencion :''' la pâge que vos éte prèst a suprimar at un historico que contint a pou prés $1 {{PLURAL:$1|vèrsion|vèrsions}} :",
'confirmdeletetext' => 'Vos éte prèst a suprimar una pâge ou ben un fichiér et pués tot son historico.
Volyéd confirmar qu’o est franc cen que vos voléd fâre, que vos en compregnéd les consèquences et pués que vos féte cen en acôrd avouéc les [[{{MediaWiki:Policy-url}}|règlles de dedens]].',
'actioncomplete' => 'Accion fêta',
'actionfailed' => 'L’accion at pas reussia',
'deletedtext' => '« $1 » at étâ suprimâ.
Vêde lo $2 por una lista de les novèles suprèssions.',
'dellogpage' => 'Jornal de les suprèssions',
'dellogpagetext' => 'Vê-que la lista de les suprèssions les ples novèles.',
'deletionlog' => 'jornal de les suprèssions',
'reverted' => 'Vèrsion devant rètablia',
'deletecomment' => 'Rêson :',
'deleteotherreason' => 'Ôtra rêson / rêson de ples :',
'deletereasonotherlist' => 'Ôtra rêson',
'deletereason-dropdown' => '*Rêsons corentes de suprèssion
** Demanda de l’ôtor
** Violacion du drêt d’ôtor
** Vandalismo',
'delete-edit-reasonlist' => 'Changiér les rêsons de suprèssion',
'delete-toobig' => 'Ceta pâge at un historico important, dèpassent $1 vèrsion{{PLURAL:$1||s}}.
La suprèssion de tâles pâges at étâ limitâ por èvitar des pèrturbacions emprèvues de {{SITENAME}}.',
'delete-warning-toobig' => 'Ceta pâge at un historico important, dèpassent $1 vèrsion{{PLURAL:$1||s}}.
La suprimar pôt troblar lo fonccionement de la bâsa de balyês de {{SITENAME}} ;
a fâre avouéc prudence.',

# Rollback
'rollback' => 'Rèvocar los changements',
'rollback_short' => 'Rèvocar',
'rollbacklink' => 'rèvocar',
'rollbacklinkcount' => 'rèvocar $1 changement{{PLURAL:$1||s}}',
'rollbacklinkcount-morethan' => 'rèvocar més de $1 changement{{PLURAL:$1||s}}',
'rollbackfailed' => 'La rèvocacion at pas reussia',
'cantrollback' => 'Empossiblo de rèvocar lo changement ;
lo dèrriér contributor est lo solèt ôtor de ceta pâge.',
'alreadyrolled' => 'Empossiblo de rèvocar lo dèrriér changement de la pâge « [[:$1]] » fêt per [[User:$2|$2]] ([[User talk:$2|Discutar]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) ;
quârqu’un d’ôtro at ja changiê ou ben rèvocâ la pâge.

Lo dèrriér changement de la pâge at étâ fêt per [[User:$3|$3]] ([[User talk:$3|Discutar]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Lo rèsumâ de changement ére : « ''$1'' ».",
'revertpage' => 'Rèvocacion des changements a [[Special:Contributions/$2|$2]] ([[User talk:$2|Discutar]]) de vers la dèrriére vèrsion a [[User:$1|$1]]',
'revertpage-nouser' => 'Rèvocacion des changements per (nom d’usanciér suprimâ) a la dèrriére vèrsion per [[User:$1|$1]]',
'rollback-success' => 'Rèvocacion des changements fêts per $1 ;
rètablissement de la dèrriére vèrsion per $2.',

# Edit tokens
'sessionfailure-title' => 'Èrror de sèance',
'sessionfailure' => 'Voutra sèance de branchement semble avêr des problèmos ;
cela accion at étâ anulâ en prèvencion d’un piratâjo de sèance.
Volyéd clicar dessus « Devant », rechargiér la pâge de yô que vos vegnéd, et pués tornar èprovar.',

# Protect
'protectlogpage' => 'Jornal de les protèccions',
'protectlogtext' => 'Vê-que na lista des changements de protèccion de les pâges.
Vêde la [[Special:ProtectedPages|lista de les pâges protègiêyes]] por la lista de les protèccions que sont ora actives.',
'protectedarticle' => 'at protègiê « [[$1]] »',
'modifiedarticleprotection' => 'at changiê lo nivél de protèccion de « [[$1]] »',
'unprotectedarticle' => 'at enlevâ la protèccion de « [[$1]] »',
'movedarticleprotection' => 'at dèplaciê los paramètres de protèccion dês « [[$2]] » vers « [[$1]] »',
'protect-title' => 'Changiér lo nivél de protèccion por « $1 »',
'protect-title-notallowed' => 'Vêre lo nivél de protèccion de « $1 »',
'prot_1movedto2' => 'at renomâ [[$1]] en [[$2]]',
'protect-badnamespace-title' => 'Èspâço de noms pas protèjâblo',
'protect-badnamespace-text' => 'Les pâges dens ceti èspâço de noms pôvont pas étre protègiês.',
'protect-legend' => 'Confirmar la protèccion',
'protectcomment' => 'Rêson :',
'protectexpiry' => 'Dâta d’èxpiracion :',
'protect_expiry_invalid' => 'La dâta d’èxpiracion est envalida.',
'protect_expiry_old' => 'La dâta d’èxpiracion est ja passâ.',
'protect-unchain-permissions' => 'Dèvèrrolyér adés més de chouèx de protèccion',
'protect-text' => "Vos pouede vêre et changiér lo nivél de protèccion de la pâge '''$1'''.",
'protect-locked-blocked' => "Vos pouede pas changiér los nivéls de protèccion tant que vos éte blocâ.
Vê-que la configuracion d’ora de la pâge '''$1''' :",
'protect-locked-dblock' => "Los nivéls de protèccion pôvont pas étre changiês perce que la bâsa de balyês est vèrrolyê.
Vê-que la configuracion d’ora de la pâge '''$1''' :",
'protect-locked-access' => "Vos avéd pas los drêts nècèssèros por changiér los nivéls de protèccion de pâges.
Vê-que la configuracion d’ora de la pâge '''$1''' :",
'protect-cascadeon' => 'Ora, ceta pâge est protègiê perce qu’el est encllua dens {{PLURAL:$1|ceta pâge|cetes pâges}}, {{PLURAL:$1|qu’at étâ protègiê|qu’ont étâ protègiês}} avouéc lo chouèx « Protèccion en cascâda » activâ.
Vos pouede changiér lo nivél de protèccion de ceta pâge sen que cen afècte la protèccion en cascâda.',
'protect-default' => 'Ôtorisar tôs los usanciérs',
'protect-fallback' => 'At fôta de la pèrmission « $1 »',
'protect-level-autoconfirmed' => 'Blocar los novéls usanciérs et los usanciérs pas encartâs',
'protect-level-sysop' => 'Solament los administrators',
'protect-summary-cascade' => 'protèccion en cascâda',
'protect-expiring' => 'èxpire lo $1 (UTC)',
'protect-expiring-local' => 'èxpire lo $1',
'protect-expiry-indefinite' => 'sen fin',
'protect-cascade' => 'Protège asse-ben les pâges encllues dens ceta (protèccion en cascâda).',
'protect-cantedit' => 'Vos pouede pas changiér los nivéls de protèccion de ceta pâge perce que vos avéd pas la pèrmission de la changiér.',
'protect-othertime' => 'Ôtra dâta d’èxpiracion :',
'protect-othertime-op' => 'ôtra dâta d’èxpiracion',
'protect-existing-expiry' => 'Dâta d’èxpiracion ègzistenta : $2 a $3',
'protect-otherreason' => 'Ôtra rêson / rêson de ples :',
'protect-otherreason-op' => 'Ôtra rêson',
'protect-dropdown' => '*Rêsons corentes de protèccion
** Vandalismo èxcèssif
** Spame èxcèssif
** Guèrres de changements contre-productives
** Pâge a trafic fôrt',
'protect-edit-reasonlist' => 'Changiér les rêsons de protèccion',
'protect-expiry-options' => '1 hora:1 hour,1 jorn:1 day,1 semana:1 week,2 semanes:2 weeks,1 mês:1 month,3 mês:3 months,6 mês:6 months,1 an:1 year,sen fin:infinite',
'restriction-type' => 'Pèrmission :',
'restriction-level' => 'Nivél de rèstriccion :',
'minimum-size' => 'Talye la ples petiôta',
'maximum-size' => 'Talye la ples granta :',
'pagesize' => '(octèts)',

# Restrictions (nouns)
'restriction-edit' => 'Changiér',
'restriction-move' => 'Renomar',
'restriction-create' => 'Fâre',
'restriction-upload' => 'Tèlèchargiér',

# Restriction levels
'restriction-level-sysop' => 'Protèccion complèta',
'restriction-level-autoconfirmed' => 'Mié-protèccion',
'restriction-level-all' => 'Tôs los nivéls',

# Undelete
'undelete' => 'Vêre les pâges suprimâyes',
'undeletepage' => 'Vêre et refâre des pâges suprimâyes',
'undeletepagetitle' => "'''Ceta lista contint des vèrsions suprimâs de [[:$1|$1]].'''",
'viewdeletedpage' => 'Vêre les pâges suprimâyes',
'undeletepagetext' => '{{PLURAL:$1|Ceta pâge at étâ suprimâ et sè trove|Cetes pâges ont étâ suprimâs et sè trovont}} dens les arch·ives, de yô que pô{{PLURAL:$1||von}}t adés étre refêt{{PLURAL:$1|a|es}}.
Les arch·ives pôvont étre èfaciês règuliérement.',
'undelete-fieldset-title' => 'Refâre les vèrsions',
'undeleteextrahelp' => "Por refâre l’historico complèt de la pâge, lèssiéd totes les câses pas pouentâs et pués clicâd dessus '''''{{int:undeletebtn}}'''''.
Por fâre una rèstoracion encomplèta, pouentâd les câses que corrèspondont a les vèrsions a refâre, et pués clicâd dessus '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|vèrsion arch·ivâ|vèrsions arch·ivâs}}',
'undeletehistory' => 'Se vos reféte la pâge, totes les vèrsions seront replaciês dens l’historico.
S’una pâge novèla avouéc lo mémo nom at étâ fêta dês la suprèssion, les vèrsions refêtes aparètront dens l’historico devant et la vèrsion d’ora serat pas remplaciê ôtomaticament.',
'undeleterevdel' => 'La rèstoracion serat pas fêta se, a la fin, la vèrsion la ples novèla de la pâge ou ben du fichiér réste suprimâ a mêtiêt.
Dens celos câs, vos dête pas pouentar ou ben pas cachiér les vèrsions suprimâs les ples novèles (d’amont la lista).',
'undeletehistorynoadmin' => 'Ceta pâge at étâ suprimâ.
La rêson de la suprèssion est montrâ dens lo rèsumâ ce-desot, avouéc los dètalys ux usanciérs que l’ont changiê devant sa suprèssion.
Lo contegnu èfèctif de celes vèrsions suprimâs est accèssiblo ren qu’ux administrators.',
'undelete-revision' => 'Vèrsion suprimâ de $1 (vèrsion du $4 a $5) per $3 :',
'undeleterevision-missing' => 'Vèrsion fôssa ou ben manquenta.
Vos avéd pôt-étre un crouyo lim, ou ben la vèrsion at possu étre refêta ou ben suprimâ de les arch·ives.',
'undelete-nodiff' => 'Gins de vèrsion devant trovâ.',
'undeletebtn' => 'Refâre',
'undeletelink' => 'vêre / refâre',
'undeleteviewlink' => 'vêre',
'undeletereset' => 'Tornar inicialisar',
'undeleteinvert' => 'Envèrsar lo chouèx',
'undeletecomment' => 'Rêson :',
'undeletedrevisions' => '$1 {{PLURAL:$1|vèrsion refêta|vèrsions refêtes}}',
'undeletedrevisions-files' => '$1 vèrsion{{PLURAL:$1||s}} et $2 fichiér{{PLURAL:$2||s}} refêts',
'undeletedfiles' => '$1 {{PLURAL:$1|fichiér refêt|fichiérs refêts}}',
'cannotundelete' => 'La rèstoracion at pas reussia ;
un ôtro usanciér at probâblament ja refêt la pâge.',
'undeletedpage' => "'''La pâge $1 est étâye refêta.'''

Vêde lo [[Special:Log/delete|jornal de les suprèssions]] por avêr la lista de les novèles suprèssions et rèstoracions.",
'undelete-header' => 'Vêde lo [[Special:Log/delete|jornal de les suprèssions]] por avêr la lista de les pâges suprimâyes dèrriérement.',
'undelete-search-title' => 'Rechèrchiér des pâges suprimâs',
'undelete-search-box' => 'Rechèrchiér des pâges suprimâs',
'undelete-search-prefix' => 'Montrar les pâges que començont per :',
'undelete-search-submit' => 'Rechèrchiér',
'undelete-no-results' => 'Niona pâge d’ense at étâ trovâ dens les arch·ives de suprèssion.',
'undelete-filename-mismatch' => 'Empossiblo de refâre la vèrsion du fichiér datâ du $1 : lo nom de fichiér corrèspond pas.',
'undelete-bad-store-key' => 'Empossiblo de refâre la vèrsion du fichiér datâ du $1 : lo fichiér ére absent devant la suprèssion.',
'undelete-cleanup-error' => 'Èrror pendent la suprèssion du fichiér de les arch·ives inutilisâ « $1 ».',
'undelete-missing-filearchive' => 'Empossiblo de refâre lo fichiér de les arch·ives avouéc lo numerô $1 perce qu’il est pas dens la bâsa de balyês.
Il at pôt-étre ja étâ refêt.',
'undelete-error' => 'Èrror pendent la rèstoracion de la pâge',
'undelete-error-short' => 'Èrror pendent la rèstoracion du fichiér : $1',
'undelete-error-long' => 'Des èrrors ont étâ rencontrâs pendent la rèstoracion du fichiér :

$1',
'undelete-show-file-confirm' => 'Éte-vos de sûr de volêr vêre una vèrsion suprimâ du fichiér « <nowiki>$1</nowiki> » que dâte du $2 a $3 ?',
'undelete-show-file-submit' => 'Ouè',

# Namespace form on various pages
'namespace' => 'Èspâço de noms :',
'invert' => 'Envèrsar lo chouèx',
'tooltip-invert' => 'Pouentâd ceta câsa por cachiér los changements de les pâges dens l’èspâço de noms chouèsi (et l’èspâço de noms associyê avouéc se pouentâ)',
'namespace_association' => 'Èspâço de noms associyê',
'tooltip-namespace_association' => 'Pouentâd ceta câsa por encllure avouéc l’èspâço de noms de discussion associyê a l’èspâço de noms chouèsi',
'blanknamespace' => '(Principâl)',

# Contributions
'contributions' => 'Contribucions a l’usanciér',
'contributions-title' => 'Lista de les contribucions a l’usanciér $1',
'mycontris' => 'Contribucions',
'contribsub2' => 'Por $1 ($2)',
'nocontribs' => 'Y at gins de changement que corrèspond a cetos critèros.',
'uctop' => '(dèrriére)',
'month' => 'Dês lo mês (et devant) :',
'year' => 'Dês l’an (et devant) :',

'sp-contributions-newbies' => 'Montrar ren que les contribucions des novéls utilisators',
'sp-contributions-newbies-sub' => 'Permié los comptos novéls',
'sp-contributions-newbies-title' => 'Contribucions d’usanciérs permié los comptos novéls',
'sp-contributions-blocklog' => 'jornal des blocâjos',
'sp-contributions-deleted' => 'contribucions suprimâs',
'sp-contributions-uploads' => 'tèlèchargements',
'sp-contributions-logs' => 'jornals',
'sp-contributions-talk' => 'discutar',
'sp-contributions-userrights' => 'administrar los drêts d’usanciér',
'sp-contributions-blocked-notice' => 'Ceti usanciér est ora blocâ.
La dèrriére entrâ du jornal des blocâjos est disponibla ce-desot :',
'sp-contributions-blocked-notice-anon' => 'Ceta adrèce IP est ora blocâ.
La dèrriére entrâ du jornal des blocâjos est disponibla ce-desot :',
'sp-contributions-search' => 'Rechèrchiér les contribucions',
'sp-contributions-username' => 'Adrèce IP ou ben nom d’usanciér :',
'sp-contributions-toponly' => 'Montrar ren que les novèles vèrsions',
'sp-contributions-submit' => 'Rechèrchiér',

# What links here
'whatlinkshere' => 'Pâges liyês',
'whatlinkshere-title' => 'Pâges que pouentont vers « $1 »',
'whatlinkshere-page' => 'Pâge :',
'linkshere' => "Les pâges ce-desot contegnont un lim de vers '''[[:$1]]''' :",
'nolinkshere' => "Niona pâge contint de lim de vers '''[[:$1]]'''.",
'nolinkshere-ns' => "Niona pâge contint de lim de vers '''[[:$1]]''' dens l’èspâço de noms chouèsi.",
'isredirect' => 'pâge de redirèccion',
'istemplate' => 'encllusion',
'isimage' => 'lim de vers lo fichiér',
'whatlinkshere-prev' => '{{PLURAL:$1|devant|$1 devant}}',
'whatlinkshere-next' => '{{PLURAL:$1|aprés|$1 aprés}}',
'whatlinkshere-links' => '← lims',
'whatlinkshere-hideredirs' => '$1 les redirèccions',
'whatlinkshere-hidetrans' => '$1 les encllusions',
'whatlinkshere-hidelinks' => '$1 los lims',
'whatlinkshere-hideimages' => '$1 los fichiérs liyês',
'whatlinkshere-filters' => 'Filtros',

# Block/unblock
'autoblockid' => 'Blocâjo ôtomatico #$1',
'block' => 'Blocar l’usanciér',
'unblock' => 'Dèblocar l’usanciér',
'blockip' => 'Blocar l’usanciér',
'blockip-title' => 'Blocar l’usanciér',
'blockip-legend' => 'Blocar l’usanciér',
'blockiptext' => 'Utilisâd lo formulèro ce-desot por blocar l’accès en ècritura dês una adrèce IP spècefica ou ben un nom d’usanciér.
Una tâla mesera devrêt étre prêsa ren que por empachiér lo vandalismo et en acôrd avouéc les [[{{MediaWiki:Policy-url}}|règlles de dedens]].
Balyéd ce-desot una rêson justa (per ègzemplo en citent les pâges qu’ont étâ vandalisâs).',
'ipadressorusername' => 'Adrèce IP ou ben nom d’usanciér :',
'ipbexpiry' => 'Temps devant èxpiracion :',
'ipbreason' => 'Rêson :',
'ipbreasonotherlist' => 'Ôtra rêson',
'ipbreason-dropdown' => '*Rêsons corentes de blocâjo
** Entrebetâ d’enformacions fôsses
** Suprèssion de contegnu de les pâges
** Entrebetâ de lims de defôr publicitèros (spame)
** Entrebetâ de contegnu sen gins de significacion et d’ècovelyes dedens les pâges
** Tentativa d’entimidacion ou ben de torment
** Abus d’usâjo d’un mouél de comptos
** Nom d’utilisator pas accèptâblo',
'ipb-hardblock' => 'Empache los changements des usanciérs encartâs qu’utilisont cela adrèce IP',
'ipbcreateaccount' => 'Empachiér la crèacion de compto',
'ipbemailban' => 'Empachiér l’usanciér de mandar des mèssâjos',
'ipbenableautoblock' => 'Blocar ôtomaticament la dèrriére adrèce IP utilisâ per l’usanciér et pués totes ses adrèces IP a vegnir que porrêt èprovar',
'ipbsubmit' => 'Blocar ceti usanciér',
'ipbother' => 'Ôtro temps :',
'ipboptions' => '2 hores:2 hours,1 jorn:1 day,3 jorns:3 days,1 semana:1 week,2 semanes:2 weeks,1 mês:1 month,3 mês:3 months,6 mês:6 months,1 an:1 year,sen fin:infinite',
'ipbotheroption' => 'ôtra',
'ipbotherreason' => 'Ôtra rêson / rêson de ples :',
'ipbhidename' => 'Cachiér lo nom d’usanciér des changements et de les listes',
'ipbwatchuser' => 'Siuvre les pâges usanciér et de discussion a ceti usanciér',
'ipb-disableusertalk' => 'Empache cél usanciér de changiér sa prôpra pâge de discussion pendent lo temps de son blocâjo',
'ipb-change-block' => 'Tornar blocar ceti usanciér avouéc celos paramètres',
'ipb-confirm' => 'Confirmar lo blocâjo',
'badipaddress' => 'L’adrèce IP est fôssa.',
'blockipsuccesssub' => 'Blocâjo reussi',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] at étâ blocâ.<br />
Vêde la [[Special:BlockList|lista des blocâjos]] por revêre los blocâjos.',
'ipb-blockingself' => 'Vos éte prèst a vos blocar vos-mémo !  Éte-vos de sûr de lo volêr fâre ?',
'ipb-confirmhideuser' => 'Vos éte prèst a blocar un usanciér avouéc « cachiér l’usanciér » activâ.  Cen suprime lo nom a l’usanciér dens totes les listes et les entrâs du jornal.  Éte-vos de sûr de lo volêr fâre ?',
'ipb-edit-dropdown' => 'Changiér les rêsons de blocâjo',
'ipb-unblock-addr' => 'Dèblocar $1',
'ipb-unblock' => 'Dèblocar un usanciér ou ben una adrèce IP',
'ipb-blocklist' => 'Vêde los blocâjos ègzistents',
'ipb-blocklist-contribs' => 'Contribucions por $1',
'unblockip' => 'Dèblocar l’usanciér',
'unblockiptext' => 'Utilisâd lo formulèro ce-desot por rètablir l’accès en ècritura dês una adrèce IP spècefica ou ben un nom d’usanciér.',
'ipusubmit' => 'Enlevar ceti blocâjo',
'unblocked' => '[[User:$1|$1]] at étâ dèblocâ',
'unblocked-range' => '$1 at étâ dèblocâ',
'unblocked-id' => 'Lo blocâjo $1 at étâ enlevâ',
'blocklist' => 'Usanciérs blocâs',
'ipblocklist' => 'Usanciérs blocâs',
'ipblocklist-legend' => 'Chèrchiér un usanciér blocâ',
'blocklist-userblocks' => 'Cachiér los blocâjos de comptos',
'blocklist-tempblocks' => 'Cachiér los blocâjos temporèros',
'blocklist-addressblocks' => 'Cachiér los blocâjos d’adrèces IP solètes',
'blocklist-rangeblocks' => 'Cachiér los blocos de portâ',
'blocklist-timestamp' => 'Dâta et hora',
'blocklist-target' => 'Ciba',
'blocklist-expiry' => 'Dâta d’èxpiracion',
'blocklist-by' => 'Administrator qu’at fêt lo blocâjo',
'blocklist-params' => 'Paramètres de blocâjo',
'blocklist-reason' => 'Rêson',
'ipblocklist-submit' => 'Rechèrchiér',
'ipblocklist-localblock' => 'Blocâjo local',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Ôtro blocâjo|Ôtros blocâjos}}',
'infiniteblock' => 'sen fin',
'expiringblock' => 'èxpire lo $1 a $2',
'anononlyblock' => 'solament los usanciérs pas encartâs',
'noautoblockblock' => 'blocâjo ôtomatico dèsactivâ',
'createaccountblock' => 'crèacion de compto dèfendua',
'emailblock' => 'mèssageria èlèctronica blocâ',
'blocklist-nousertalk' => 'pôt pas changiér sa prôpra pâge de discussion',
'ipblocklist-empty' => 'Ora, la lista a les adrèces IP blocâs est voueda.',
'ipblocklist-no-results' => 'L’adrèce IP ou ben l’usanciér demandâ est pas blocâ.',
'blocklink' => 'blocar',
'unblocklink' => 'dèblocar',
'change-blocklink' => 'changiér lo blocâjo',
'contribslink' => 'contribucions',
'emaillink' => 'mandar un mèssâjo',
'autoblocker' => 'Vos avéd étâ blocâ ôtomaticament perce que voutra adrèce IP at étâ utilisâ dèrriérement per « [[User:$1|$1]] ».
La rêson balyê por lo blocâjo a $1 est : « $2 ».',
'blocklogpage' => 'Jornal des blocâjos',
'blocklog-showlog' => 'Ceti usanciér at étâ blocâ dês devant.
Lo jornal des blocâjos est disponiblo ce-desot :',
'blocklog-showsuppresslog' => 'Ceti usanciér at étâ blocâ et pués cachiê dês devant.
Lo jornal de les suprèssions est disponiblo ce-desot :',
'blocklogentry' => 'at blocâ [[$1]] ; èxpiracion : $2 $3',
'reblock-logentry' => 'at changiê los paramètres du blocâjo a [[$1]] avouéc una èxpiracion u $2 $3',
'blocklogtext' => 'O est lo jornal de les accions de blocâjo et dèblocâjo d’utilisators.
Les adrèces IP blocâyes ôtomaticament sont pas listâyes.
Vêde la [[Special:BlockList|lista des blocâjos]] por la lista des banissements et des blocâjos que sont ora actifs.',
'unblocklogentry' => 'at dèblocâ $1',
'block-log-flags-anononly' => 'solament los usanciérs pas encartâs',
'block-log-flags-nocreate' => 'crèacion de compto dèfendua',
'block-log-flags-noautoblock' => 'ôtoblocâjo a les adrèces IP dèsactivâ',
'block-log-flags-noemail' => 'èxpèdicion de mèssâjo dèfendua',
'block-log-flags-nousertalk' => 'pôt pas changiér sa prôpra pâge de discussion',
'block-log-flags-angry-autoblock' => 'ôtoblocâjo mèlyorâ activâ',
'block-log-flags-hiddenname' => 'nom d’usanciér cachiê',
'range_block_disabled' => 'Lo povêr d’administrator de fâre des blocâjos de plages d’adrèces IP est dèsactivâ.',
'ipb_expiry_invalid' => 'Temps d’èxpiracion fôx.',
'ipb_expiry_temp' => 'Los blocâjos de noms d’usanciér cachiês dêvont étre sen fin.',
'ipb_hide_invalid' => 'Empossiblo de suprimar ceti compto ; semble avêr trop de changements.',
'ipb_already_blocked' => '« $1 » est ja blocâ',
'ipb-needreblock' => '$1 est ja blocâ.
Voléd-vos changiér los paramètres ?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Ôtro blocâjo|Ôtros blocâjos}}',
'unblock-hideuser' => 'Vos pouede pas dèblocar cél usanciér, perce que son nom d’usanciér at étâ cachiê.',
'ipb_cant_unblock' => 'Èrror : numerô de blocâjo $1 pas trovâ.
O est possiblo qu’un dèblocâjo èye ja étâ fêt.',
'ipb_blocked_as_range' => 'Èrror : l’adrèce IP $1 est pas blocâ tot drêt et pôt vêr pas étre dèblocâ.
Portant, el est avouéc la plage $2 que pôt étre dèblocâ.',
'ip_range_invalid' => 'Plage d’adrèces IP fôssa.',
'ip_range_toolarge' => 'Los blocâjos de plages d’adrèces IP ples grantes que /$1 sont pas ôtorisâs.',
'blockme' => 'Blocâd-mè',
'proxyblocker' => "Bloquior de sèrvors mandatèros (''proxies'')",
'proxyblocker-disabled' => 'Cela fonccion est dèsactivâ.',
'proxyblockreason' => "Voutra adrèce IP at étâ blocâ perce qu’o est un sèrvor mandatèro (''proxy'') uvèrt.
Vos volyéd veriér vers voutron fornissor d’accès u Malyâjo ou ben voutra assistance tècnica et l’enformar de cél problèmo de sècuritât sèriox.",
'proxyblocksuccess' => 'Chavonâ.',
'sorbsreason' => "Voutra adrèce IP est listâ coment sèrvor mandatèro (''proxy'') uvèrt dens lo DNSBL utilisâ per {{SITENAME}}.",
'sorbs_create_account_reason' => "Voutra adrèce IP est listâ coment sèrvor mandatèro (''proxy'') uvèrt dens lo DNSBL utilisâ per {{SITENAME}}.
Vos pouede pas fâre un compto.",
'cant-block-while-blocked' => 'Vos pouede pas blocar d’ôtros usanciérs tant que vos éte blocâ.',
'cant-see-hidden-user' => 'L’usanciér que vos tâchiéd de blocar at ja étâ blocâ et cachiê.
Pas èyent lo drêt hideuser, vos pouede pas vêre ou ben changiér lo blocâjo a l’usanciér.',
'ipbblocked' => 'Vos pouede pas blocar ou ben dèblocar d’ôtros usanciérs, perce que vos éte vos-mémo blocâ',
'ipbnounblockself' => 'Vos éte pas ôtorisâ a vos dèblocar vos-mémo',

# Developer tools
'lockdb' => 'Vèrrolyér la bâsa de balyês',
'unlockdb' => 'Dèvèrrolyér la bâsa de balyês',
'lockdbtext' => 'Lo vèrrolyâjo de la bâsa de balyês empachierat tôs los usanciérs de changiér des pâges, d’encartar lors prèferences, de changiér lor lista de survelyence et pués de fâre totes les ôtres opèracions qu’ont fôta des changements dens la bâsa de balyês.
Volyéd confirmar qu’o est franc cen que vos voléd fâre et que vos dèvèrrolyeréd la bâsa setout que voutra opèracion de mantegnence serat chavonâ.',
'unlockdbtext' => 'Lo dèvèrrolyâjo de la bâsa de balyês tornerat pèrmetre a tôs los usanciérs de changiér des pâges, d’encartar lors prèferences, de changiér lor lista de survelyence et pués de fâre totes les ôtres opèracions qu’ont fôta des changements dens la bâsa de balyês.
Volyéd confirmar qu’o est franc cen que vos voléd fâre.',
'lockconfirm' => 'Ouè, confirmo que souhèto vèrrolyér la bâsa de balyês.',
'unlockconfirm' => 'Ouè, confirmo que souhèto dèvèrrolyér la bâsa de balyês.',
'lockbtn' => 'Vèrrolyér la bâsa de balyês',
'unlockbtn' => 'Dèvèrrolyér la bâsa de balyês',
'locknoconfirm' => 'Vos éd pas pouentâ la câsa de confirmacion.',
'lockdbsuccesssub' => 'Vèrrolyâjo de la bâsa de balyês reussi',
'unlockdbsuccesssub' => 'Vèrrolyâjo de la bâsa de balyês enlevâ',
'lockdbsuccesstext' => 'La bâsa de donâs est étâye vèrrolyêye.<br />
Oubliâd pas de [[Special:UnlockDB|la dèvèrrolyér]] quand vos aréd chavonâ voutra opèracion de mantegnence.',
'unlockdbsuccesstext' => 'La bâsa de balyês at étâ dèvèrrolyê.',
'lockfilenotwritable' => 'Lo fichiér de vèrrolyâjo de la bâsa de balyês est pas enscriptiblo.
Por vèrrolyér ou ben dèvèrrolyér la bâsa de balyês, dêt étre accèssiblo en ècritura dês lo sèrvor vouèbe.',
'databasenotlocked' => 'La bâsa de balyês est pas vèrrolyê.',
'lockedbyandtime' => '(per $1 lo $2 a $3)',

# Move page
'move-page' => 'Renomar $1',
'move-page-legend' => 'Renomar una pâge',
'movepagetext' => "Utilisâd lo formulèro ce-desot por renomar una pâge, en dèplacient tot son historico vers lo novél nom.
Lo viely titro vindrat una pâge de redirèccion de vers lo titro novél.
Vos pouede betar a jorn ôtomaticament les redirèccions d’ora que pouentont vers lo titro originâl.
Se vos chouèsésséd de pas lo fâre, assurâd-vos de controlar tota [[Special:DoubleRedirects|redirèccion dobla]] ou ben [[Special:BrokenRedirects|câssa]].
Vos avéd la rèsponsabilitât de vos assurar que los lims continuont de pouentar vers lor dèstinacion suposâ.

Notâd que la pâge serat '''pas''' dèplaciê s’y at ja una pâge avouéc lo titro novél, a muens que cela dèrriére seye voueda ou ben seye ren qu’una redirèccion et que son historico des changements seye vouedo.
Cen vôt dére que vos pouede renomar una pâge vers sa posicion d’origina se vos éd fêt una èrror, mas que vos pouede pas ècllafar una pâge ja ègzistenta.

'''ATENCION !'''
Cen pôt provocar un changement fôrt et emprèvu por una pâge sovent vua ;
assurâd-vos de nen avêr comprês les consèquences devant que continuar.",
'movepagetext-noredirectfixer' => "Utilisâd lo formulèro ce-desot por renomar una pâge, en dèplacient tot son historico vers lo novél nom.
Lo viely titro vindrat una pâge de redirèccion de vers lo titro novél.
Controlâd bien les [[Special:DoubleRedirects|redirèccions dobles]] ou ben [[Special:BrokenRedirects|câsses]].
Vos avéd la rèsponsabilitât de vos assurar que los lims continuont de pouentar vers lor dèstinacion suposâ.

Notâd que la pâge serat '''pas''' dèplaciê s’y at ja una pâge avouéc lo titro novél, a muens que cela dèrriére seye voueda ou ben seye ren qu’una redirèccion et que son historico des changements seye vouedo.
Cen vôt dére que vos pouede renomar una pâge vers sa posicion d’origina se vos éd fêt una èrror, mas que vos pouede pas ècllafar una pâge ja ègzistenta.

'''ATENCION !'''
Cen pôt provocar un changement fôrt et emprèvu por una pâge sovent vua ;
assurâd-vos de nen avêr comprês les consèquences devant que continuar.",
'movepagetalktext' => "La pâge de discussion associyê, se presente, serat renomâ ôtomaticament avouéc '''a muens que :'''
* una pâge de discussion pas voueda ègziste ja avouéc lo novél nom, ou ben
* vos pouentâd pas la câsa ce-desot.

Dens celos câs, vos devréd renomar ou ben fusionar la pâge a la man se vos lo voléd.",
'movearticle' => 'Renomar la pâge :',
'moveuserpage-warning' => "'''Atencion :''' vos éte prèst a renomar una pâge usanciér. Volyéd notar que solament la pâge serat renomâ et que l’usanciér serat '''pas''' renomâ.",
'movenologin' => 'Pas branchiê',
'movenologintext' => 'Por povêr renomar una pâge, vos dête étre [[Special:UserLogin|branchiê]] coment usanciér encartâ.',
'movenotallowed' => 'Vos avéd pas la pèrmission de renomar les pâges.',
'movenotallowedfile' => 'Vos avéd pas la pèrmission de renomar los fichiérs.',
'cant-move-user-page' => 'Vos avéd pas la pèrmission de renomar les pâges principâles d’usanciérs (en defôr de lors sot-pâges).',
'cant-move-to-user-page' => 'Vos avéd pas la pèrmission de renomar una pâge vers una pâge usanciér (a l’èxcèpcion d’una sot-pâge).',
'newtitle' => 'De vers lo titro novél :',
'move-watch' => 'Siuvre les pâges sôrsa et ciba',
'movepagebtn' => 'Renomar la pâge',
'pagemovedsub' => 'Changement de nom reussi',
'movepage-moved' => "'''« $1 » at étâ renomâ en « $2 »'''",
'movepage-moved-redirect' => 'Una redirèccion dês lo viely nom at étâ fêta.',
'movepage-moved-noredirect' => 'La crèacion d’una redirèccion dês lo viely nom at étâ anulâ.',
'articleexists' => 'Ègziste ja una pâge que pôrte cél titro, ou ben lo titro que vos éd chouèsi est fôx.
Nen volyéd chouèsir un ôtro.',
'cantmove-titleprotected' => 'Vos pouede pas dèplaciér una pâge vers cél emplacement perce que lo titro novél at étâ protègiê a la crèacion.',
'talkexists' => "'''La pâge lyé-méma at étâ dèplaciê avouéc reusséta, mas la pâge de discussion at pas possu étre dèplaciê perce que nen ègzistâve ja yona desot lo novél nom.'''
'''Les volyéd fusionar a la man.'''",
'movedto' => 'renomâ en',
'movetalk' => 'Renomar avouéc la pâge de discussion associyê',
'move-subpages' => 'Renomar les sot-pâges (tant qu’a $1 pâges)',
'move-talk-subpages' => 'Renomar les sot-pâges de la pâge de discussion (tant qu’a $1 pâges)',
'movepage-page-exists' => 'La pâge $1 ègziste ja et pôt pas étre ècrasâ ôtomaticament.',
'movepage-page-moved' => 'La pâge $1 at étâ renomâ en $2.',
'movepage-page-unmoved' => 'La pâge $1 at pas possu étre renomâ en $2.',
'movepage-max-pages' => 'Lo més de $1 {{PLURAL:$1|pâge at étâ renomâ|pâges ont étâ renomâs}} et niona ôtra pâge serat renomâ ôtomaticament.',
'movelogpage' => 'Jornal des changements de nom',
'movelogpagetext' => 'Vê-que la lista de totes les pâges renomâs ou dèplaciês.',
'movesubpage' => 'Sot-pâge{{PLURAL:$1||s}}',
'movesubpagetext' => 'Cela pâge at $1 {{PLURAL:$1|sot-pâge montrâ|sot-pâges montrâs}} ce-desot.',
'movenosubpage' => 'Ceta pâge at gins de sot-pâge.',
'movereason' => 'Rêson :',
'revertmove' => 'rètablir',
'delete_and_move' => 'Suprimar et renomar',
'delete_and_move_text' => '== Suprèssion nècèssèra ==
La pâge de dèstinacion « [[:$1]] » ègziste ja.
La voléd-vos suprimar por pèrmetre lo changement de nom ?',
'delete_and_move_confirm' => 'Ouè, j’accèpto de suprimar la pâge de dèstinacion por pèrmetre lo changement de nom.',
'delete_and_move_reason' => 'Pâge suprimâ por pèrmetre lo changement de nom dês « [[$1]] »',
'selfmove' => 'Los titros d’origina et de dèstinacion sont los mémos ;
empossiblo de renomar una pâge sur lyé-méma.',
'immobile-source-namespace' => 'Vos pouede pas renomar les pâges dens l’èspâço de noms « $1 »',
'immobile-target-namespace' => 'Vos pouede pas renomar des pâges vers l’èspâço de noms « $1 »',
'immobile-target-namespace-iw' => 'Los lims entèrvouiquis sont pas una ciba valida por los changements de nom.',
'immobile-source-page' => 'Cela pâge est pas renomâbla.',
'immobile-target-page' => 'Empossiblo de renomar la pâge vers cél titro.',
'imagenocrossnamespace' => 'Empossiblo de renomar un fichiér vers un èspâço de noms ôtro que « {{ns:file}} ».',
'nonfile-cannot-move-to-file' => 'Empossiblo de renomar quârque-ren d’ôtro qu’un fichiér vers l’èspâço de noms « {{ns:file}} ».',
'imagetypemismatch' => 'La novèla èxtension de cél fichiér corrèspond pas a son tipo.',
'imageinvalidfilename' => 'Lo nom du fichiér ciba est fôx',
'fix-double-redirects' => 'Betar a jorn les redirèccions que pouentont vers lo titro originâl',
'move-leave-redirect' => 'Lèssiér una redirèccion de vers lo titro novél',
'protectedpagemovewarning' => "'''ATENCION :''' ceta pâge at étâ protègiê de façon que solament los usanciérs qu’ont lo statut d’administrator la pouessont renomar.
La dèrriére entrâ du jornal est montrâ ce-desot coment refèrence :",
'semiprotectedpagemovewarning' => "'''Nota :''' ceta pâge at étâ protègiê de façon que solament los usanciérs encartâs la pouessont renomar.
La dèrriére entrâ du jornal est montrâ ce-desot coment refèrence :",
'move-over-sharedrepo' => '== Lo fichiér ègziste ==
[[:$1]] ègziste ja sur un dèpôt partagiê. Renomar cél fichiér rendrat lo fichiér sur lo dèpôt partagiê pas accèssiblo.',
'file-exists-sharedrepo' => 'Lo nom chouèsi est ja utilisâ per un fichiér sur un dèpôt partagiê.
Nen volyéd chouèsir un ôtro.',

# Export
'export' => 'Èxportar des pâges',
'exporttext' => 'Vos pouede èxportar en XML lo tèxto et l’historico d’una pâge ou ben d’un ensemblo de pâges ;
lo rèsultat pôt adonc étre importâ dens un ôtro vouiqui qu’utilise la programeria MediaWiki avouéc la [[Special:Import|pâge d’importacion]].

Por èxportar des pâges, buchiéd lors titros dens la bouèta de tèxto ce-desot, yon titro per legne, et pués chouèsésséd se vos voléd ou pas la vèrsion d’ora avouéc totes les vielyes vèrsions, avouéc les legnes de l’historico de la pâge, ou ben simplament la pâge d’ora avouéc des enformacions sur lo dèrriér changement.

Dens cél dèrriér câs, vos pouede asse-ben utilisar un lim, coment [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] por la pâge « [[{{MediaWiki:Mainpage}}]] ».',
'exportall' => 'Èxportar totes les pâges',
'exportcuronly' => 'Èxportar ren que la vèrsion d’ora, sen l’historico complèt',
'exportnohistory' => "----
'''Nota :''' l’èxportacion de l’historico complèt de les pâges avouéc ceti formulèro at étâ dèsactivâ por des rêsons de capacitât.",
'exportlistauthors' => 'Encllure una lista complèta ux contributors por châque pâge',
'export-submit' => 'Èxportar',
'export-addcattext' => 'Apondre les pâges de la catègorie :',
'export-addcat' => 'Apondre',
'export-addnstext' => 'Apondre des pâges dens l’èspâço de noms :',
'export-addns' => 'Apondre',
'export-download' => 'Encartar dens un fichiér',
'export-templates' => 'Encllure los modèlos',
'export-pagelinks' => 'Encllure les pâges liyês a una provondior de :',

# Namespace 8 related
'allmessages' => 'Mèssâjos sistèmo',
'allmessagesname' => 'Nom du mèssâjo',
'allmessagesdefault' => 'Mèssâjo per dèfôt',
'allmessagescurrent' => 'Tèxto d’ora',
'allmessagestext' => 'O est la lista des mèssâjos sistèmo disponiblos dens l’èspâço MediaWiki.
Volyéd visitar la [//www.mediawiki.org/wiki/Localisation localisacion de MediaWiki] et pués [//translatewiki.net translatewiki.net] se vos voléd contribuar a la localisacion g·ènèrica de MediaWiki.',
'allmessagesnotsupportedDB' => "Ceta pâge '''{{ns:special}}:Allmessages''' est inutilisâbla perce que '''\$wgUseDatabaseMessages''' at étâ dèsactivâ.",
'allmessages-filter-legend' => 'Filtro',
'allmessages-filter' => 'Filtrar per ètat de changement :',
'allmessages-filter-unmodified' => 'Pas changiê',
'allmessages-filter-all' => 'Tôs',
'allmessages-filter-modified' => 'Changiê',
'allmessages-prefix' => 'Filtrar per prèfixo :',
'allmessages-language' => 'Lengoua :',
'allmessages-filter-submit' => 'Aplicar',

# Thumbnails
'thumbnail-more' => 'Agrantir',
'filemissing' => 'Fichiér manquent',
'thumbnail_error' => 'Èrror pendent la crèacion de la figura : $1',
'djvu_page_error' => 'Pâge DjVu en defôr de les limites',
'djvu_no_xml' => 'Empossiblo de rècupèrar lo XML por lo fichiér DjVu',
'thumbnail-temp-create' => 'Empossiblo de fâre lo fichiér de figura temporèra',
'thumbnail-dest-create' => 'Empossiblo d’encartar la figura sur la dèstinacion',
'thumbnail_invalid_params' => 'Paramètres de la figura fôx',
'thumbnail_dest_directory' => 'Empossiblo de fâre lo rèpèrtouèro de dèstinacion',
'thumbnail_image-type' => 'Tipo d’émâge pas recognu',
'thumbnail_gd-library' => 'Configuracion encomplèta de la bibliotèca GD : fonccion $1 entrovâbla',
'thumbnail_image-missing' => 'Ceti fichiér est entrovâblo : $1',

# Special:Import
'import' => 'Importar des pâges',
'importinterwiki' => 'Importacion entèrvouiqui',
'import-interwiki-text' => 'Chouèsésséd un vouiqui et un titro de pâge a importar.
Les dâtes de les vèrsions et los noms ux contributors seront presèrvâs.
Totes les accions d’importacion entèrvouiqui sont consignês dens lo [[Special:Log/import|jornal de les importacions]].',
'import-interwiki-source' => 'Vouiqui / pâge d’origina :',
'import-interwiki-history' => 'Copiyér totes les vèrsions de l’historico de ceta pâge',
'import-interwiki-templates' => 'Encllure tôs los modèlos',
'import-interwiki-submit' => 'Importar',
'import-interwiki-namespace' => 'Èspâço de noms de dèstinacion :',
'import-interwiki-rootpage' => 'Pâge racena de dèstinacion (u chouèx) :',
'import-upload-filename' => 'Nom du fichiér :',
'import-comment' => 'Comentèro :',
'importtext' => 'Volyéd èxportar lo fichiér dês lo vouiqui d’origina en utilisent son [[Special:Export|outil d’èxportacion]].
Sôvâd-lo sur voutron ordenator et pués tèlèchargiéd-lo ique.',
'importstart' => 'Importacion de les pâges...',
'import-revision-count' => '$1 vèrsion{{PLURAL:$1||s}}',
'importnopages' => 'Gins de pâge a importar.',
'imported-log-entries' => '$1 entrâ{{PLURAL:$1||s}} du jornal importâ{{PLURAL:$1||s}}.',
'importfailed' => 'Falyita de l’importacion : <nowiki>$1</nowiki>',
'importunknownsource' => 'Tipo de la sôrsa a importar encognu',
'importcantopen' => 'Empossiblo d’uvrir lo fichiér a importar',
'importbadinterwiki' => 'Crouyo lim entèrvouiqui',
'importnotext' => 'Vouedo ou ben sen tèxto',
'importsuccess' => 'L’importacion at reussia !',
'importhistoryconflict' => 'Un conflit at étâ dècelâ dens l’historico de les vèrsions (ceta pâge at possu étre importâ dês devant).',
'importnosources' => 'Niona sôrsa d’importacion entèrvouiqui at étâ dèfenia et los tèlèchargements drêts d’historicos sont dèsactivâs.',
'importnofile' => 'Nion fichiér a importar at étâ tèlèchargiê.',
'importuploaderrorsize' => 'Lo tèlèchargement du fichiér a importar at pas reussi.
Sa talye est ples granta que cela ôtorisâ.',
'importuploaderrorpartial' => 'Lo tèlèchargement du fichiér a importar at pas reussi.
Son contegnu at étâ tèlèchargiê ren qu’a mêtiêt.',
'importuploaderrortemp' => 'Lo tèlèchargement du fichiér a importar at pas reussi.
Un dossiér temporèro est manquent.',
'import-parse-failure' => 'Falyita pendent l’analise du XML a importar',
'import-noarticle' => 'Gins de pâge a importar !',
'import-nonewrevisions' => 'Totes les vèrsions ont étâ importâs dês devant.',
'xml-error-string' => '$1 a la legne $2, colona $3 (octèt $4) : $5',
'import-upload' => 'Tèlèchargement de balyês XML',
'import-token-mismatch' => 'Pèrta de les balyês de sèance.
Volyéd tornar èprovar.',
'import-invalid-interwiki' => 'Empossiblo d’importar dês lo vouiqui spècefiâ.',
'import-error-edit' => 'La pâge « $1 » est pas importâ perce que vos éte pas ôtorisâ a la changiér.',
'import-error-create' => 'La pâge « $1 » est pas importâ perce que vos éte pas ôtorisâ a la fâre.',
'import-options-wrong' => '{{PLURAL:$2|Crouyo chouèx|Crouyos chouèx}} : <nowiki>$1</nowiki>',

# Import log
'importlogpage' => 'Jornal de les importacions',
'importlogpagetext' => 'Importacions administratives de pâges avouéc lor historico de changements dês d’ôtros vouiquis.',
'import-logentry-upload' => 'at importâ [[$1]] per tèlèchargement de fichiér',
'import-logentry-upload-detail' => '$1 vèrsion{{PLURAL:$1||s}}',
'import-logentry-interwiki' => 'at importâ $1 per entèrvouiqui',
'import-logentry-interwiki-detail' => '$1 vèrsion{{PLURAL:$1||s}} dês $2',

# JavaScriptTest
'javascripttest' => 'Èprôva de JavaScript',
'javascripttest-disabled' => 'Cela fonccion-que est pas étâye activâye sur ceti vouiqui.',
'javascripttest-title' => 'Èprôves de $1 en cors',
'javascripttest-qunit-intro' => 'Vêde la [$1 documentacion de les èprôves] dessus mediawiki.org.',
'javascripttest-qunit-heading' => 'Suita d’èprôva QUnit de JavaScript dessus MediaWiki',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Voutra pâge usanciér',
'tooltip-pt-anonuserpage' => 'La pâge usanciér de l’adrèce IP avouéc laquinta vos contribuâd',
'tooltip-pt-mytalk' => 'Voutra pâge de discussion',
'tooltip-pt-anontalk' => 'La pâge de discussion por les contribucions dês cela adrèce IP',
'tooltip-pt-preferences' => 'Voutres prèferences',
'tooltip-pt-watchlist' => 'La lista de les pâges que vos siude los changements',
'tooltip-pt-mycontris' => 'La lista de voutres contribucions',
'tooltip-pt-login' => 'Vos éte encoragiê a vos branchiér ; portant, o est pas oblegatouèro.',
'tooltip-pt-anonlogin' => 'Vos éte encoragiê a vos branchiér ; portant, o est pas oblegatouèro.',
'tooltip-pt-logout' => 'Sè dèbranchiér',
'tooltip-ca-talk' => 'Discussion sur ceta pâge de contegnu',
'tooltip-ca-edit' => 'Vos pouede changiér ceta pâge.
Volyéd utilisar lo boton de prèvisualisacion devant que sôvar.',
'tooltip-ca-addsection' => 'Comenciér una novèla sèccion',
'tooltip-ca-viewsource' => 'Ceta pâge est protègiêye.
Portant vos en pouede vêre lo sin tèxto sôrsa',
'tooltip-ca-history' => 'Les vèrsions passâs de ceta pâge (avouéc lors contributors)',
'tooltip-ca-protect' => 'Protègiér ceta pâge',
'tooltip-ca-unprotect' => 'Changiér la protèccion de ceta pâge',
'tooltip-ca-delete' => 'Suprimar ceta pâge',
'tooltip-ca-undelete' => 'Refâre los changements fêts sur ceta pâge devant sa suprèssion',
'tooltip-ca-move' => 'Renomar ceta pâge',
'tooltip-ca-watch' => 'Apondre ceta pâge a voutra lista de survelyence',
'tooltip-ca-unwatch' => 'Enlevar ceta pâge de voutra lista de survelyence',
'tooltip-search' => 'Rechèrchiér dens {{SITENAME}}',
'tooltip-search-go' => 'Alar vers una pâge que pôrte justo ceti nom s’ègziste.',
'tooltip-search-fulltext' => 'Rechèrchiér les pâges que presentont ceti tèxto.',
'tooltip-p-logo' => 'Pâge principâla',
'tooltip-n-mainpage' => 'Visitar la pâge de reçua du seto',
'tooltip-n-mainpage-description' => 'Alar a la reçua',
'tooltip-n-portal' => 'Sur lo projèt, cen que vos pouede fâre, yô que trovar les chouses',
'tooltip-n-currentevents' => 'Trovar les enformacions de fond sur les dèrriéres novèles',
'tooltip-n-recentchanges' => 'Lista des dèrriérs changements sur lo vouiqui',
'tooltip-n-randompage' => 'Fâre vêre na pâge a l’hasârd',
'tooltip-n-help' => 'Éde',
'tooltip-t-whatlinkshere' => 'Lista de les pâges liyês a ceta',
'tooltip-t-recentchangeslinked' => 'Lista des dèrriérs changements de les pâges liyês a ceta',
'tooltip-feed-rss' => 'Flux RSS por ceta pâge',
'tooltip-feed-atom' => 'Flux Atom por ceta pâge',
'tooltip-t-contributions' => 'Vêre la lista de les contribucions de cet’utilisator',
'tooltip-t-emailuser' => 'Mandar un mèssâjo a ceti usanciér',
'tooltip-t-upload' => 'Tèlèchargiér des fichiérs',
'tooltip-t-specialpages' => 'Lista de totes les pâges spèciâles',
'tooltip-t-print' => 'Vèrsion emprimâbla de ceta pâge',
'tooltip-t-permalink' => 'Lim fixo de vers ceta vèrsion de la pâge',
'tooltip-ca-nstab-main' => 'Vêre la pâge de contegnu',
'tooltip-ca-nstab-user' => 'Vêre la pâge utilisator',
'tooltip-ca-nstab-media' => 'Vêre la pâge du fichiér mèdia',
'tooltip-ca-nstab-special' => 'O est una pâge spèciâla, vos la pouede pas changiér.',
'tooltip-ca-nstab-project' => 'Vêre la pâge projèt',
'tooltip-ca-nstab-image' => 'Vêre la pâge du fichiér',
'tooltip-ca-nstab-mediawiki' => 'Vêre lo mèssâjo sistèmo',
'tooltip-ca-nstab-template' => 'Vêre lo modèlo',
'tooltip-ca-nstab-help' => 'Vêre la pâge d’éde',
'tooltip-ca-nstab-category' => 'Vêre la pâge de la catègorie',
'tooltip-minoredit' => 'Marcar mos changements coment petiôts',
'tooltip-save' => 'Sôvar voutros changements',
'tooltip-preview' => 'Volyéd prèvisualisar voutros changements devant que los sôvar !',
'tooltip-diff' => 'Pèrmèt de montrar los changements que vos éd fêts',
'tooltip-compareselectedversions' => 'Fâre ressortir les difèrences entre doves vèrsions de ceta pâge',
'tooltip-watch' => 'Apondre ceta pâge a voutra lista de survelyence',
'tooltip-watchlistedit-normal-submit' => 'Enlevar los titros',
'tooltip-watchlistedit-raw-submit' => 'Betar a jorn la lista de survelyence',
'tooltip-recreate' => 'Refâre la pâge mémo se ceta at étâ suprimâ',
'tooltip-upload' => 'Emmodar lo tèlèchargement',
'tooltip-rollback' => '« Rèvocar » anule en yon clic lo ou ben los changement(s) de ceta pâge per son dèrriér contributor.',
'tooltip-undo' => '« Dèfâre » rèvoque ceti changement et ôvre la fenétra d’èdicion en fôrma de prèvisualisacion.
Pèrmèt de rètablir la vèrsion devant et pués d’apondre una rêson dens la bouèta de rèsumâ.',
'tooltip-preferences-save' => 'Encartar les prèferences',
'tooltip-summary' => 'Buchiéd un côrt rèsumâ',

# Stylesheets
'common.css' => '/* Lo code CSS betâ ique serat aplicâ a tôs los habelyâjos. */',
'standard.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « Estandârd ». */',
'nostalgia.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « Cafârd ». */',
'cologneblue.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « Blu de Cologne ». */',
'monobook.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « MonoBook ». */',
'myskin.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « MonHabelyâjo ». */',
'chick.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « Pugin ». */',
'simple.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « Simplo ». */',
'modern.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « Modèrno ». */',
'vector.css' => '/* Lo code CSS betâ ique afècterat los usanciérs de l’habelyâjo « Vèctor ». */',
'print.css' => '/* Lo code CSS betâ ique afècterat les emprèssions. */',
'handheld.css' => '/* Lo code CSS betâ ique afècterat los aparèlys mobilos d’aprés l’habelyâjo configurâ dedens « $wgHandheldStyle ». */',
'noscript.css' => '/* Lo code CSS betâ ique afècterat los usanciérs qu’ont dèsactivâ lo code JavaScript. */',
'group-autoconfirmed.css' => '/* Lo code CSS betâ ique afècterat ren que los usanciérs encartâs. */',
'group-bot.css' => '/* Lo code CSS betâ ique afècterat ren que los bots. */',
'group-sysop.css' => '/* Lo code CSS betâ ique afècterat ren que los administrators. */',
'group-bureaucrat.css' => '/* Lo code CSS betâ ique afècterat ren que los grata-papiérs. */',

# Scripts
'common.js' => '/* Tot code JavaScript betâ ique serat chargiê per tôs los usanciérs avouéc châque chargement de pâge. */',
'standard.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « Estandârd ». */',
'nostalgia.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « Cafârd ». */',
'cologneblue.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « Blu de Cologne ». */',
'monobook.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « MonoBook ». */',
'myskin.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « MonHabelyâjo ». */',
'chick.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « Pugin ». */',
'simple.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « Simplo ». */',
'modern.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « Modèrno ». */',
'vector.js' => '/* Tot code JavaScript betâ ique serat chargiê per los usanciérs de l’habelyâjo « Vèctor ». */',
'group-autoconfirmed.js' => '/* Tot code JavaScript betâ ique serat chargiê ren que por los usanciérs encartâs. */',
'group-bot.js' => '/* Tot code JavaScript betâ ique serat chargiê ren que por los bots. */',
'group-sysop.js' => '/* Tot code JavaScript betâ ique serat chargiê ren que por los administrators. */',
'group-bureaucrat.js' => '/* Tot code JavaScript betâ ique serat chargiê ren que por los grata-papiérs. */',

# Metadata
'notacceptable' => 'Lo sèrvor vouiqui pôt pas balyér les balyês dens un format que voutron cliant est capâblo de liére.',

# Attribution
'anonymous' => '{{PLURAL:$1|Usanciér pas encartâ|Usanciérs pas encartâs}} dessus {{SITENAME}}',
'siteuser' => 'l’usanciér{{GENDER:$2||e}} $1 de {{SITENAME}}',
'anonuser' => 'l’usanciér pas encartâ $1 de {{SITENAME}}',
'lastmodifiedatby' => 'Ceta pâge at étâ changiê por lo dèrriér côp lo $1 a $2 per $3.',
'othercontribs' => 'Basâ sur l’ôvra a $1.',
'others' => 'ôtros',
'siteusers' => '{{PLURAL:$2|l’usanciér|los usanciérs}} $1 de {{SITENAME}}',
'anonusers' => '{{PLURAL:$2|l’usanciér pas encartâ|los usanciérs pas encartâs}} $1 de {{SITENAME}}',
'creditspage' => 'Crèdits de la pâge',
'nocredits' => 'Y at gins d’enformacion d’atribucion disponibla por ceta pâge.',

# Spam protection
'spamprotectiontitle' => 'Pâge protègiê ôtomaticament a côsa de spame',
'spamprotectiontext' => 'La pâge que vos éd tâchiê de sôvar at étâ blocâ per lo filtro anti-spame.
O est probâblament diu a un lim de vers un seto de defôr qu’aparêt sur la lista nêre.',
'spamprotectionmatch' => "La chêna de caractèros « '''$1''' » at dècllenchiê lo dècelior de spame.",
'spambot_username' => 'Neteyâjo de spame per MediaWiki',
'spam_reverting' => 'Rètablissement de la dèrriére vèrsion que contint gins de lim de vers $1',
'spam_blanking' => 'Totes les vèrsions que contegnont des lims de vers $1 sont blanchies',
'spam_deleting' => 'Totes les vèrsions que contegnont des lims de vers $1 sont suprimâs',

# Info page
'pageinfo-title' => 'Enformacions por « $1 »',
'pageinfo-header-basic' => 'Enformacions de bâsa',
'pageinfo-header-edits' => 'Historico des changements',
'pageinfo-header-restrictions' => 'Protèccion de la pâge',
'pageinfo-header-properties' => 'Propriètâts de la pâge',
'pageinfo-display-title' => 'Titro montrâ',
'pageinfo-default-sort' => 'Cllâf de tri per dèfôt',
'pageinfo-length' => 'Talye de la pâge (en octèts)',
'pageinfo-article-id' => 'Numerô de la pâge',
'pageinfo-robot-policy' => 'Statut de motor de rechèrche',
'pageinfo-robot-index' => 'Endèxâblo',
'pageinfo-robot-noindex' => 'Pas endèxâblo',
'pageinfo-views' => 'Nombro de visualisacions',
'pageinfo-watchers' => 'Nombro de contributors qu’ont la pâge dedens lor lista de survelyence',
'pageinfo-redirects-name' => 'Redirèccions de vers ceta pâge',
'pageinfo-subpages-name' => 'Sot-pâges de ceta pâge',
'pageinfo-subpages-value' => '$1 ($2 redirèccion{{PLURAL:$2||s}} ; $3 nan-redirèccion{{PLURAL:$3||s}})',
'pageinfo-firstuser' => 'Crèator de la pâge',
'pageinfo-firsttime' => 'Dâta de crèacion de la pâge',
'pageinfo-lastuser' => 'Dèrriér contributor',
'pageinfo-lasttime' => 'Dâta du dèrriér changement',
'pageinfo-edits' => 'Soma totâla de changements',
'pageinfo-authors' => 'Soma totâla d’ôtors difèrents',
'pageinfo-recent-edits' => 'Nombro de novéls changements (dedens los $1 passâs)',
'pageinfo-recent-authors' => 'Nombro de novéls ôtors difèrents',
'pageinfo-magic-words' => '{{PLURAL:$1|Mot magico|Mots magicos}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Catègorie cachiêye|Catègories cachiêyes}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Modèlo encllu|Modèlos encllus}} ($1)',

# Skin names
'skinname-standard' => 'Estandârd',
'skinname-nostalgia' => 'Cafârd',
'skinname-cologneblue' => 'Blu de Cologne',
'skinname-monobook' => 'MonoBook',
'skinname-myskin' => 'MonHabelyâjo',
'skinname-chick' => 'Pugin',
'skinname-simple' => 'Simplo',
'skinname-modern' => 'Modèrno',
'skinname-vector' => 'Vèctor',

# Patrolling
'markaspatrolleddiff' => 'Marcar coment survelyê',
'markaspatrolledtext' => 'Marcar ceta pâge coment survelyê',
'markedaspatrolled' => 'Marcâye coment survelyêye',
'markedaspatrolledtext' => 'La vèrsion chouèsia de [[:$1]] est étâye marcâye coment survelyêye.',
'rcpatroldisabled' => 'La fonccion de survelyence des dèrriérs changements est pas activâ.',
'rcpatroldisabledtext' => 'La fonccionalitât de survelyence des dèrriérs changements est pas activâ.',
'markedaspatrollederror' => 'Pôt pas étre marcâye coment survelyêye',
'markedaspatrollederrortext' => 'Vos dête chouèsir una vèrsion por la povêr marcar coment survelyê.',
'markedaspatrollederror-noautopatrol' => 'Vos avéd pas lo drêt de marcar voutros prôpros changements coment survelyês.',

# Patrol log
'patrol-log-page' => 'Jornal de les vèrsions survelyês',
'patrol-log-header' => 'Vê-que un jornal de les vèrsions survelyês.',
'log-show-hide-patrol' => '$1 lo jornal de les vèrsions survelyês',

# Image deletion
'deletedrevision' => 'La vielye vèrsion $1 at étâ suprimâ.',
'filedeleteerror-short' => 'Èrror pendent la suprèssion du fichiér : $1',
'filedeleteerror-long' => 'Des èrrors ont étâ rencontrâs pendent la suprèssion du fichiér :

$1',
'filedelete-missing' => 'Lo fichiér « $1 » pôt pas étre suprimâ perce qu’ègziste pas.',
'filedelete-old-unregistered' => 'La vèrsion du fichiér spècefiâ « $1 » est pas dens la bâsa de balyês.',
'filedelete-current-unregistered' => 'Lo fichiér spècefiâ « $1 » est pas dens la bâsa de balyês.',
'filedelete-archive-read-only' => 'Lo dossiér d’arch·ivâjo « $1 » pôt pas étre changiê per lo sèrvor.',

# Browsing diffs
'previousdiff' => '← Changement devant',
'nextdiff' => 'Changement aprés →',

# Media information
'mediawarning' => "'''Atencion :''' ceti tipo de fichiér pôt contegnir de code mâlvelyent.
Se vos l’ègzécutâd, voutron sistèmo pôt étre compromês.",
'imagemaxsize' => "Format lo ples grant de les émâges :<br />''(por les pâges de dèscripcion d’émâges)''",
'thumbsize' => 'Talye de la figura :',
'widthheightpage' => '$1 × $2, $3 pâge{{PLURAL:$3||s}}',
'file-info' => 'Talye du fichiér : $1, tipo MIME : $2',
'file-info-size' => '$1 × $2 pixèls, talye du fichiér : $3, tipo MIME : $4',
'file-info-size-pages' => '$1 × $2 pixèls, talye du fichiér : $3, tipo MIME : $4, $5 pâge{{PLURAL:$5||s}}',
'file-nohires' => 'Gins de rèsolucion ples hôta disponibla.',
'svg-long-desc' => 'Fichiér SVG, rèsolucion de $1 × $2 pixèls, talye : $3',
'show-big-image' => 'Émâge en rèsolucion ples hôta',
'show-big-image-preview' => 'Talye de ceti apèrçu : $1.',
'show-big-image-other' => '{{PLURAL:$2|Ôtra rèsolucion|Ôtres rèsolucions}} : $1.',
'show-big-image-size' => '$1 × $2 pixèls',
'file-info-gif-looped' => 'en boclla',
'file-info-gif-frames' => '$1 émâge{{PLURAL:$1||s}}',
'file-info-png-looped' => 'en boclla',
'file-info-png-repeat' => 'jouyê $1 côp{{PLURAL:$1||s}}',
'file-info-png-frames' => '$1 émâge{{PLURAL:$1||s}}',

# Special:NewFiles
'newimages' => 'Galerie des novéls fichiérs',
'imagelisttext' => "Vê-que una lista de '''$1''' {{PLURAL:$1|fichiér rengiê|fichiérs rengiês}} $2.",
'newimages-summary' => 'Ceta pâge spèciâla montre los dèrriérs fichiérs tèlèchargiês.',
'newimages-legend' => 'Nom du fichiér',
'newimages-label' => 'Nom du fichiér (ou ben una partia de ceti) :',
'showhidebots' => '($1 los bots)',
'noimages' => 'Gins de fichiér a fâre vêre.',
'ilsubmit' => 'Rechèrchiér',
'bydate' => 'per dâta',
'sp-newimages-showfrom' => 'Montrar los novéls fichiérs dês lo $1 a $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'days-abbrev' => '$1j',
'seconds' => '$1 second{{PLURAL:$1|a|es}}',
'minutes' => '$1 menut{{PLURAL:$1|a|es}}',
'hours' => '$1 hor{{PLURAL:$1|a|es}}',
'days' => '$1 jorn{{PLURAL:$1||s}}',
'ago' => 'cen fât $1',

# Bad image list
'bad_image_list' => 'Lo format est ceti :

Solament les listes d’ènumèracion (que començont per *) sont considèrâs.
Lo premiér lim d’una legne dêt étre vers celi d’una crouye émâge.
Los ôtros lims sur la méma legne sont considèrâs coment des èxcèpcions, per ègzemplo des pâges sur lesquintes l’émâge pôt aparêtre.',

# Metadata
'metadata' => 'Mètabalyês',
'metadata-help' => 'Ceti fichiér contint des enformacions de ples, probâblament apondues per l’aparèly-fotô numerico ou ben lo scanor utilisâ por lo fâre.
Se lo fichiér at étâ changiê dês son ètat originâl, quârques dètalys pôvont pas reflètar a chavon l’émâge changiê.',
'metadata-expand' => 'Montrar los dètalys ètendus',
'metadata-collapse' => 'Cachiér los dètalys ètendus',
'metadata-fields' => 'Los champs de mètabalyês d’émâge listâs dens ceti mèssâjo seront encllus dens la pâge de dèscripcion de l’émâge quand la trâbla de mètabalyês serat rèduita.
Los ôtros champs seront cachiês per dèfôt.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',
'metadata-langitem' => "'''$2 :''' $1",

# EXIF tags
'exif-imagewidth' => 'Largior',
'exif-imagelength' => 'Hôtior',
'exif-bitspersample' => 'Bits per composenta',
'exif-compression' => 'Tipo de comprèssion',
'exif-photometricinterpretation' => 'Composicion des pixèls',
'exif-orientation' => 'Oriantacion',
'exif-samplesperpixel' => 'Nombro de composentes',
'exif-planarconfiguration' => 'Arrengement de les balyês',
'exif-ycbcrsubsampling' => 'Quota de sot-èchantelyonâjo de Y a C',
'exif-ycbcrpositioning' => 'Posicionement Y et C',
'exif-xresolution' => 'Rèsolucion plana',
'exif-yresolution' => 'Rèsolucion drêta',
'exif-stripoffsets' => 'Emplacement de les balyês de l’émâge',
'exif-rowsperstrip' => 'Nombro de legnes per benda',
'exif-stripbytecounts' => 'Talye en octèts per benda',
'exif-jpeginterchangeformat' => 'Posicion du SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Talye en octèts de les balyês JPEG',
'exif-whitepoint' => 'Cromaticitât du pouent blanc',
'exif-primarychromaticities' => 'Cromaticitât de les colors primères',
'exif-ycbcrcoefficients' => 'Factors de la matrice de transformacion de l’èspâço colorimètrico',
'exif-referenceblackwhite' => 'Valors de refèrence nêr et blanc',
'exif-datetime' => 'Dâta et hora de changement du fichiér',
'exif-imagedescription' => 'Titro de l’émâge',
'exif-make' => 'Fabrecant de l’aparèly-fotô',
'exif-model' => 'Modèlo de l’aparèly-fotô',
'exif-software' => 'Programeria utilisâ',
'exif-artist' => 'Ôtor',
'exif-copyright' => 'Dètentor du drêt d’ôtor',
'exif-exifversion' => 'Vèrsion Exif',
'exif-flashpixversion' => 'Vèrsion FlashPix recognua',
'exif-colorspace' => 'Èspâço colorimètrico',
'exif-componentsconfiguration' => 'Significacion de châque composenta',
'exif-compressedbitsperpixel' => 'Fôrma de comprèssion de l’émâge',
'exif-pixelydimension' => 'Largior de l’émâge',
'exif-pixelxdimension' => 'Hôtior de l’émâge',
'exif-usercomment' => 'Comentèros a l’usanciér',
'exif-relatedsoundfile' => 'Fichiér ôdiô associyê',
'exif-datetimeoriginal' => 'Dâta et hora de la g·ènèracion de les balyês',
'exif-datetimedigitized' => 'Dâta et hora de la numerisacion',
'exif-subsectime' => 'Dâta et hora en fraccions de secondes de changement du fichiér',
'exif-subsectimeoriginal' => 'Dâta et hora en fraccions de secondes de la g·ènèracion de les balyês',
'exif-subsectimedigitized' => 'Dâta et hora en fraccions de secondes de la numerisacion',
'exif-exposuretime' => 'Temps d’èxposicion',
'exif-exposuretime-format' => '$1 s ($2)',
'exif-fnumber' => 'Nombro f',
'exif-exposureprogram' => 'Programo d’èxposicion',
'exif-spectralsensitivity' => 'Sensibilitât spèctrâla',
'exif-isospeedratings' => 'Sensibilitât ISO',
'exif-shutterspeedvalue' => 'Vitèsse d’ètopâ de l’APEX',
'exif-aperturevalue' => 'Uvèrtura de l’APEX',
'exif-brightnessvalue' => 'Luminance APEX',
'exif-exposurebiasvalue' => 'Corrèccion d’èxposicion',
'exif-maxaperturevalue' => 'Uvèrtura la ples granta',
'exif-subjectdistance' => 'Distance du sujèt',
'exif-meteringmode' => 'Fôrma de mesera',
'exif-lightsource' => 'Sôrsa de lumiére',
'exif-flash' => 'Èludo',
'exif-focallength' => 'Longior focâla',
'exif-subjectarea' => 'Emplacement du sujèt',
'exif-flashenergy' => 'Nèrf de l’èludo',
'exif-focalplanexresolution' => 'Rèsolucion plana de la vision focâla',
'exif-focalplaneyresolution' => 'Rèsolucion drêta de la vision focâla',
'exif-focalplaneresolutionunit' => 'Unitât de rèsolucion de la vision focâla',
'exif-subjectlocation' => 'Posicion du sujèt',
'exif-exposureindex' => 'Endèxe d’èxposicion',
'exif-sensingmethod' => 'Tipo de captior',
'exif-filesource' => 'Sôrsa du fichiér',
'exif-scenetype' => 'Tipo de scèna',
'exif-customrendered' => 'Rendu d’émâge pèrsonalisâ',
'exif-exposuremode' => 'Fôrma d’èxposicion',
'exif-whitebalance' => 'Balance des blancs',
'exif-digitalzoomratio' => "Quota d’agrantissement numerico (''zoom'')",
'exif-focallengthin35mmfilm' => 'Longior focâla por un filme 35 mm',
'exif-scenecapturetype' => 'Tipo de prêsa de la scèna',
'exif-gaincontrol' => 'Contrôlo de scèna',
'exif-contrast' => 'Contraste',
'exif-saturation' => 'Saturacion',
'exif-sharpness' => 'Prècision',
'exif-devicesettingdescription' => 'Dèscripcion de la configuracion du dispositif',
'exif-subjectdistancerange' => 'Distance du sujèt',
'exif-imageuniqueid' => 'Numerô solèt de l’émâge',
'exif-gpsversionid' => 'Vèrsion de la balisa GPS',
'exif-gpslatituderef' => "Latituda bise (''nord'') ou mié-jorn (''sud'')",
'exif-gpslatitude' => 'Latituda',
'exif-gpslongituderef' => "Longituda levant (''èst'') ou ponant (''ouèst'')",
'exif-gpslongitude' => 'Longituda',
'exif-gpsaltituderef' => 'Refèrence d’hôtior',
'exif-gpsaltitude' => 'Hôtior',
'exif-gpstimestamp' => 'Hora GPS (relojo atomico)',
'exif-gpssatellites' => 'Satèlites utilisâs por la mesera',
'exif-gpsstatus' => 'Ètat du recevior',
'exif-gpsmeasuremode' => 'Fôrma de mesera',
'exif-gpsdop' => 'Prècision de la mesera',
'exif-gpsspeedref' => 'Unitât de vitèsse du recevior GPS',
'exif-gpsspeed' => 'Vitèsse du recevior GPS',
'exif-gpstrackref' => 'Refèrence por la dirèccion du mouvement',
'exif-gpstrack' => 'Dirèccion du mouvement',
'exif-gpsimgdirectionref' => 'Refèrence por la dirèccion de l’émâge',
'exif-gpsimgdirection' => 'Dirèccion de l’émâge',
'exif-gpsmapdatum' => 'Sistèmo g·eodèsico utilisâ',
'exif-gpsdestlatituderef' => 'Refèrence por la latituda de la dèstinacion',
'exif-gpsdestlatitude' => 'Latituda de la dèstinacion',
'exif-gpsdestlongituderef' => 'Refèrence por la longituda de la dèstinacion',
'exif-gpsdestlongitude' => 'Longituda de la dèstinacion',
'exif-gpsdestbearingref' => 'Refèrence por lo relèvament de la dèstinacion',
'exif-gpsdestbearing' => 'Relèvament de la dèstinacion',
'exif-gpsdestdistanceref' => 'Refèrence por la distance a la dèstinacion',
'exif-gpsdestdistance' => 'Distance a la dèstinacion',
'exif-gpsprocessingmethod' => 'Nom du tipo de trètament du GPS',
'exif-gpsareainformation' => 'Nom de la zona GPS',
'exif-gpsdatestamp' => 'Dâta GPS',
'exif-gpsdifferential' => 'Corrèccion difèrencièla GPS',
'exif-jpegfilecomment' => 'Comentèro de fichiér JPEG',
'exif-keywords' => 'Mots-cllâfs',
'exif-worldregioncreated' => 'Règ·ion du mondo que la fotô at étâ prêsa',
'exif-countrycreated' => 'Payis que la fotô at étâ prêsa',
'exif-countrycodecreated' => 'Code du payis que la fotô at étâ prêsa',
'exif-provinceorstatecreated' => 'Province ou ben ètat que la fotô at étâ prêsa',
'exif-citycreated' => 'Vela que la fotô at étâ prêsa',
'exif-sublocationcreated' => 'Partia de la vela que la fotô at étâ prêsa',
'exif-worldregiondest' => 'Règ·ion du mondo montrâ',
'exif-countrydest' => 'Payis montrâ',
'exif-countrycodedest' => 'Code du payis montrâ',
'exif-provinceorstatedest' => 'Province ou ben ètat montrâ',
'exif-citydest' => 'Vela montrâ',
'exif-sublocationdest' => 'Partia de la vela montrâ',
'exif-objectname' => 'Titro côrt',
'exif-specialinstructions' => 'Enstruccions spèciâles',
'exif-headline' => 'Titro',
'exif-credit' => 'Crèdit / fornissor',
'exif-source' => 'Sôrsa',
'exif-editstatus' => 'Statut èditoriâl de l’émâge',
'exif-urgency' => 'Urgence',
'exif-fixtureidentifier' => 'Nom de l’outil',
'exif-locationdest' => 'Endrêt fotografiâ',
'exif-locationdestcode' => 'Code de l’endrêt fotografiâ',
'exif-objectcycle' => 'Moment de la jornâ que ceti mèdia est dèstinâ',
'exif-contact' => 'Enformacions de contacte',
'exif-writer' => 'Ôtor',
'exif-languagecode' => 'Lengoua',
'exif-iimversion' => 'Vèrsion IIM',
'exif-iimcategory' => 'Catègorie',
'exif-iimsupplementalcategory' => 'Catègories de ples',
'exif-datetimeexpires' => 'Pas utilisar aprés',
'exif-datetimereleased' => 'Paru lo',
'exif-originaltransmissionref' => 'Code de l’endrêt de la transmission originâla',
'exif-identifier' => 'Numerô',
'exif-lens' => 'Lentelye utilisâ',
'exif-serialnumber' => 'Numerô de sèria de l’aparèly-fotô',
'exif-cameraownername' => 'Propriètèro de l’aparèly-fotô',
'exif-label' => 'Lambél',
'exif-datetimemetadata' => 'Dâta du dèrriér changement de les mètabalyês',
'exif-nickname' => 'Nom enformèl de l’émâge',
'exif-rating' => 'Nota (sur 5)',
'exif-rightscertificate' => 'Cèrtificat d’administracion des drêts',
'exif-copyrighted' => 'Statut des drêts d’ôtor',
'exif-copyrightowner' => 'Propriètèro du drêt d’ôtor',
'exif-usageterms' => 'Condicions d’usâjo',
'exif-webstatement' => 'Dècllaracion des drêts d’ôtor en legne',
'exif-originaldocumentid' => 'Numerô solèt du document originâl',
'exif-licenseurl' => 'URL de la licence',
'exif-morepermissionsurl' => 'Enformacions sur les licences altèrnatives',
'exif-attributionurl' => 'Pendent lo reusâjo de cela ôvra, volyéd liyér a',
'exif-preferredattributionname' => 'Pendent lo reusâjo de cela ôvra, volyéd crèditar',
'exif-pngfilecomment' => 'Comentèro de fichiér PNG',
'exif-disclaimer' => 'Avèrtissement',
'exif-contentwarning' => 'Avèrtissement sur lo contegnu',
'exif-giffilecomment' => 'Comentèro de fichiér GIF',
'exif-intellectualgenre' => 'Tipo d’èlèment',
'exif-subjectnewscode' => 'Code du sujèt',
'exif-scenecode' => 'Code de scèna IPTC',
'exif-event' => 'Èvènement fotografiâ',
'exif-organisationinimage' => 'Organisacion fotografiâ',
'exif-personinimage' => 'Pèrsona fotografiâ',
'exif-originalimageheight' => 'Hôtior de l’émâge devant qu’el èye étâ tornâ cadrar',
'exif-originalimagewidth' => 'Largior de l’émâge devant qu’el èye étâ tornâ cadrar',

# EXIF attributes
'exif-compression-1' => 'Pas comprèssâ',
'exif-compression-2' => 'CCITT tropa 3 longior du codâjo Huffman changiê de dimension 1',
'exif-compression-3' => 'CCITT tropa 3 codâjo du faxe',
'exif-compression-4' => 'CCITT tropa 4 codâjo du faxe',
'exif-compression-6' => 'JPEG (viely)',

'exif-copyrighted-true' => 'Somês a drêt d’ôtor',
'exif-copyrighted-false' => 'Domêno publico',

'exif-unknowndate' => 'Dâta encognua',

'exif-orientation-1' => 'Normala',
'exif-orientation-2' => 'Envèrsâ d’aplan',
'exif-orientation-3' => 'Veriê de 180°',
'exif-orientation-4' => 'Envèrsâ d’aplomb',
'exif-orientation-5' => 'Veriê de 90° dens la dirèccion antihorèra et envèrsâ d’aplomb',
'exif-orientation-6' => 'Veriê de 90° dens la dirèccion antihorèra',
'exif-orientation-7' => 'Veriê de 90° dens la dirèccion horèra et envèrsâ d’aplomb',
'exif-orientation-8' => 'Veriê de 90° dens la dirèccion horèra',

'exif-planarconfiguration-1' => 'Balyês ategnentes',
'exif-planarconfiguration-2' => 'Balyês sèparâs',

'exif-colorspace-65535' => 'Pas calibrâ',

'exif-componentsconfiguration-0' => 'Ègziste pas',
'exif-componentsconfiguration-5' => 'V',

'exif-exposureprogram-0' => 'Pas dèfeni',
'exif-exposureprogram-1' => 'Manuèl',
'exif-exposureprogram-2' => 'Programo normal',
'exif-exposureprogram-3' => 'Prioritât a l’uvèrtura',
'exif-exposureprogram-4' => 'Prioritât a l’ètopior',
'exif-exposureprogram-5' => 'Programo crèacion (prèference a la provondior de champ)',
'exif-exposureprogram-6' => 'Programo accion (prèference a la vitèsse d’ètopâ)',
'exif-exposureprogram-7' => 'Fôrma portrèt (por clich·ês de prés avouéc fond pas nèt)',
'exif-exposureprogram-8' => 'Fôrma payisâjo (por des clich·ês de payisâjos nèts)',

'exif-subjectdistance-value' => '$1 mètre{{PLURAL:$1||s}}',

'exif-meteringmode-0' => 'Encognua',
'exif-meteringmode-1' => 'Moyena',
'exif-meteringmode-2' => 'Moyena èquilibrâ u centro',
'exif-meteringmode-3' => 'Pouent',
'exif-meteringmode-4' => 'MultiPouent',
'exif-meteringmode-5' => 'Modèlo',
'exif-meteringmode-6' => 'Encomplèta',
'exif-meteringmode-255' => 'Ôtra',

'exif-lightsource-0' => 'Encognua',
'exif-lightsource-1' => 'Lumiére du jorn',
'exif-lightsource-2' => 'Fluorèscenta',
'exif-lightsource-3' => 'Tungstène (lumiére chôdâ a blanc)',
'exif-lightsource-4' => 'Èludo',
'exif-lightsource-9' => 'Temps cllâr',
'exif-lightsource-10' => 'Temps enneblo',
'exif-lightsource-11' => 'Ombra',
'exif-lightsource-12' => 'Lumiére fluorèscenta « lumiére du jorn » (D 5700 – 7100 K)',
'exif-lightsource-13' => 'Lumiére fluorèscenta blanche « jorn » (N 4600 – 5400 K)',
'exif-lightsource-14' => 'Lumiére fluorèscenta blanche « frêd » (W 3900 – 4500 K)',
'exif-lightsource-15' => 'Lumiére fluorèscenta blanche (WW 3200 – 3700 K)',
'exif-lightsource-17' => 'Lumiére estandârd A',
'exif-lightsource-18' => 'Lumiére estandârd B',
'exif-lightsource-19' => 'Lumiére estandârd C',
'exif-lightsource-24' => 'Tungstène ISO de studiô',
'exif-lightsource-255' => 'Ôtra sôrsa de lumiére',

# Flash modes
'exif-flash-fired-0' => 'Èludo pas dècllenchiê',
'exif-flash-fired-1' => 'Èludo dècllenchiê',
'exif-flash-return-0' => 'nion stroboscopo retorne una fonccion de dètèccion',
'exif-flash-return-2' => 'lo stroboscopo retorne una lumiére pas dècelâ',
'exif-flash-return-3' => 'lo stroboscopo retorne una lumiére dècelâ',
'exif-flash-mode-1' => 'lumiére de l’èludo oblegatouèra',
'exif-flash-mode-2' => 'suprèssion de l’èludo oblegatouèra',
'exif-flash-mode-3' => 'fôrma ôtomatica',
'exif-flash-function-1' => 'Gins de fonccion d’èludo',
'exif-flash-redeye-1' => 'fôrma anti-uelys rojos',

'exif-focalplaneresolutionunit-2' => 'pôjos',

'exif-sensingmethod-1' => 'Pas dèfeni',
'exif-sensingmethod-2' => 'Captior de color a yona puge',
'exif-sensingmethod-3' => 'Captior de color a doves puges',
'exif-sensingmethod-4' => 'Captior de color a três puges',
'exif-sensingmethod-5' => 'Captior de color sèquencièl',
'exif-sensingmethod-7' => 'Captior trilinèâr',
'exif-sensingmethod-8' => 'Captior de color linèâr sèquencièl',

'exif-filesource-3' => 'Aparèly-fotô numerico',

'exif-scenetype-1' => 'Émâge fotografiâ tot drêt',

'exif-customrendered-0' => 'Maniére normala',
'exif-customrendered-1' => 'Maniére pèrsonalisâ',

'exif-exposuremode-0' => 'Èxposicion ôtomatica',
'exif-exposuremode-1' => 'Èxposicion manuèla',
'exif-exposuremode-2' => 'Forchèta ôtomatica',

'exif-whitebalance-0' => 'Balance des blancs ôtomatica',
'exif-whitebalance-1' => 'Balance des blancs manuèla',

'exif-scenecapturetype-0' => 'Estandârd',
'exif-scenecapturetype-1' => 'Payisâjo',
'exif-scenecapturetype-2' => 'Portrèt',
'exif-scenecapturetype-3' => 'Scèna de nuet',

'exif-gaincontrol-0' => 'Nion',
'exif-gaincontrol-1' => 'Guen fêblament positif',
'exif-gaincontrol-2' => 'Guen fôrtament positif',
'exif-gaincontrol-3' => 'Guen fêblament nègatif',
'exif-gaincontrol-4' => 'Guen fôrtament nègatif',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Fêblo',
'exif-contrast-2' => 'Fôrt',

'exif-saturation-0' => 'Normala',
'exif-saturation-1' => 'Saturacion fêbla',
'exif-saturation-2' => 'Saturacion hôta',

'exif-sharpness-0' => 'Normala',
'exif-sharpness-1' => 'Doce',
'exif-sharpness-2' => 'Dura',

'exif-subjectdistancerange-0' => 'Encognua',
'exif-subjectdistancerange-1' => 'Vision en grôs',
'exif-subjectdistancerange-2' => 'Vision de prés',
'exif-subjectdistancerange-3' => 'Vision de luen',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => "Latituda bise (''nord'')",
'exif-gpslatitude-s' => "Latituda mié-jorn (''sud'')",

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => "Longituda levant (''èst'')",
'exif-gpslongitude-w' => "Longituda ponant (''ouèst'')",

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 mètre{{PLURAL:$1||s}} en-d’amont du nivél de la mar',
'exif-gpsaltitude-below-sealevel' => '$1 mètre{{PLURAL:$1||s}} en-desot du nivél de la mar',

'exif-gpsstatus-a' => 'Mesera en cors',
'exif-gpsstatus-v' => 'Entèropèrabilitât de la mesera',

'exif-gpsmeasuremode-2' => 'Mesera a 2 dimensions',
'exif-gpsmeasuremode-3' => 'Mesera a 3 dimensions',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilomètres per hora',
'exif-gpsspeed-m' => 'Miles per hora',
'exif-gpsspeed-n' => 'Nuods',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilomètres',
'exif-gpsdestdistance-m' => 'Miles',
'exif-gpsdestdistance-n' => 'Miles marins',

'exif-gpsdop-excellent' => 'Famosa ($1)',
'exif-gpsdop-good' => 'Bôna ($1)',
'exif-gpsdop-moderate' => 'Moyena ($1)',
'exif-gpsdop-fair' => 'Passâbla ($1)',
'exif-gpsdop-poor' => 'Crouye ($1)',

'exif-objectcycle-a' => 'Matin solament',
'exif-objectcycle-p' => 'Nuet solament',
'exif-objectcycle-b' => 'Matin et nuet',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Veré dirèccion',
'exif-gpsdirection-m' => 'Dirèccion magnètica',

'exif-ycbcrpositioning-1' => 'Centrâ',
'exif-ycbcrpositioning-2' => 'Co-situâ',

'exif-dc-contributor' => 'Contributors',
'exif-dc-coverage' => 'Portâ espaciâla ou ben temporèla du mèdia',
'exif-dc-date' => 'Dâta(/-es)',
'exif-dc-publisher' => 'Èditor',
'exif-dc-relation' => 'Mèdias liyês',
'exif-dc-rights' => 'Drêts',
'exif-dc-source' => 'Mèdia sôrsa',
'exif-dc-type' => 'Tipo de mèdia',

'exif-rating-rejected' => 'Refusâ',

'exif-isospeedratings-overflow' => 'Ples grant que 65535',

'exif-iimcategory-ace' => 'Ârts, cultura et amusament',
'exif-iimcategory-clj' => 'Crimo et drêt',
'exif-iimcategory-dis' => 'Catastrofes et accidents',
'exif-iimcategory-fin' => 'Èconomia et afâres',
'exif-iimcategory-edu' => 'Èducacion',
'exif-iimcategory-evn' => 'Enveronance',
'exif-iimcategory-hth' => 'Santât',
'exif-iimcategory-hum' => 'Entèrèt de l’homo',
'exif-iimcategory-lab' => 'Travâly',
'exif-iimcategory-lif' => 'Fôrma de via et pâssa-temps',
'exif-iimcategory-pol' => 'Politica',
'exif-iimcategory-rel' => 'Religion et creyences',
'exif-iimcategory-sci' => 'Science et tècnolog·ie',
'exif-iimcategory-soi' => 'Quèstions sociâles',
'exif-iimcategory-spo' => 'Sports',
'exif-iimcategory-war' => 'Guèrra, conflit et troblo',
'exif-iimcategory-wea' => 'Mètèô',

'exif-urgency-normal' => 'Normala ($1)',
'exif-urgency-low' => 'Fêbla ($1)',
'exif-urgency-high' => 'Hôta ($1)',
'exif-urgency-other' => 'Prioritât dèfenia per l’usanciér ($1)',

# External editor support
'edit-externally' => 'Changiér ceti fichiér en utilisent una aplicacion de defôr',
'edit-externally-help' => '(Vêde les [//www.mediawiki.org/wiki/Manual:External_editors enstruccions d’enstalacion] por més d’enformacions)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tot',
'namespacesall' => 'tôs',
'monthsall' => 'tôs',
'limitall' => 'tôs',

# E-mail address confirmation
'confirmemail' => 'Confirmar l’adrèce èlèctronica',
'confirmemail_noemail' => 'Vos éd pas dèfeni una adrèce èlèctronica valida dens voutres [[Special:Preferences|prèferences]].',
'confirmemail_text' => '{{SITENAME}} at fôta du contrôlo de voutra adrèce èlèctronica devant que povêr utilisar tota fonccion de mèssageria.
Utilisâd lo boton ce-desot por mandar un mèssâjo de confirmacion a voutra adrèce.
Lo mèssâjo encllurat un lim que contint un code a usâjo solèt et limitâ dens lo temps ;
chargiéd cél lim dens voutron navigator por confirmar que voutra adrèce èlèctronica est valida.',
'confirmemail_pending' => 'Un code de confirmacion vos at ja étâ mandâ per mèssageria èlèctronica ;
se vos vegnéd de fâre voutron compto, volyéd atendre doux-três menutes que lo mèssâjo arreve devant que demandar un code novél.',
'confirmemail_send' => 'Mandar un code de confirmacion',
'confirmemail_sent' => 'Mèssâjo de confirmacion mandâ.',
'confirmemail_oncreate' => 'Un code de confirmacion at étâ mandâ a voutra adrèce èlèctronica.
Cél code est pas nècèssèro por sè branchiér, mas vos lo devréd balyér por activar tota fonccionalitât liyê a la mèssageria èlèctronica sur ceti vouiqui.',
'confirmemail_sendfailed' => '{{SITENAME}} vos at pas possu mandar lo mèssâjo de confirmacion.
Volyéd controlar que voutra adrèce èlèctronica contint gins de caractèro dèfendu.

Lo programo d’èxpèdicion de mèssâjo at retornâ ceta endicacion : $1',
'confirmemail_invalid' => 'Code de confirmacion fôx.
Ceti at pôt-étre èxpirâ.',
'confirmemail_needlogin' => 'Vos vos dête $1 por confirmar voutra adrèce èlèctronica.',
'confirmemail_success' => 'Voutra adrèce èlèctronica at étâ confirmâ.
Ora, vos vos pouede [[Special:UserLogin|branchiér]] et profitar du vouiqui.',
'confirmemail_loggedin' => 'Ora, voutra adrèce èlèctronica est confirmâ.',
'confirmemail_error' => 'Un problèmo est arrevâ pendent l’encartâjo de voutra confirmacion.',
'confirmemail_subject' => 'Confirmacion d’adrèce èlèctronica por {{SITENAME}}',
'confirmemail_body' => 'Quârqu’un, probâblament vos, avouéc l’adrèce IP $1,
at encartâ un compto « $2 » avouéc cela adrèce èlèctronica dessus {{SITENAME}}.

Por confirmar que cél compto est franc a vos et por
activar les fonccions de mèssageria dessus {{SITENAME}},
volyéd uvrir ceti lim dens voutron navigator :

$3

Se vos éd *pas* encartâ lo compto, siude ceti lim
por anular la confirmacion de l’adrèce èlèctronica :

$5

Cél code de confirmacion èxpirerat lo $4.',
'confirmemail_body_changed' => 'Quârqu’un, probâblament vos, avouéc l’adrèce IP $1,
at changiê l’adrèce èlèctronica du compto « $2 » a cela adrèce dessus {{SITENAME}}.

Por confirmar que cél compto est franc a vos et por
reactivar les fonccions de mèssageria dessus {{SITENAME}},
volyéd uvrir ceti lim dens voutron navigator :

$3

Se lo compto est *pas* a vos, siude ceti lim
por anular la confirmacion de l’adrèce èlèctronica :

$5

Cél code de confirmacion èxpirerat lo $4.',
'confirmemail_body_set' => 'Quârqu’un, probâblament vos, avouéc l’adrèce IP $1,
at changiê l’adrèce èlèctronica du compto « $2 » a cela adrèce dessus {{SITENAME}}.

Por confirmar que cél compto est franc a vos et por
reactivar les fonccions de mèssageria dessus {{SITENAME}},
volyéd uvrir ceti lim dens voutron navigator :

$3

Se lo compto est *pas* a vos, siude ceti lim
por anular la confirmacion de l’adrèce èlèctronica :

$5

Cél code de confirmacion èxpirerat lo $4.',
'confirmemail_invalidated' => 'Confirmacion de l’adrèce èlèctronica anulâ',
'invalidateemail' => 'Anular la confirmacion de l’adrèce èlèctronica',

# Scary transclusion
'scarytranscludedisabled' => '[La transcllusion entèrvouiqui est dèsactivâ]',
'scarytranscludefailed' => '[La rècupèracion de modèlo at pas reussia por $1]',
'scarytranscludetoolong' => '[L’URL est trop longe]',

# Delete conflict
'deletedwhileediting' => "'''Atencion :''' ceta pâge at étâ suprimâ aprés que vos vos éte betâ a la changiér !",
'confirmrecreate' => "L’usanciér [[User:$1|$1]] ([[User talk:$1|Discussion]]) at suprimâ ceta pâge, pendent que vos vos érâd betâ a la changiér, por ceta rêson :
: ''$2''
Volyéd confirmar que vos voléd franc refâre cela pâge.",
'confirmrecreate-noreason' => 'L’usanciér [[User:$1|$1]] ([[User talk:$1|Discussion]]) at suprimâ ceta pâge, pendent que vos vos érâd betâ a la changiér.  Volyéd confirmar que vos voléd franc refâre cela pâge.',
'recreate' => 'Refâre',

# action=purge
'confirm_purge_button' => 'Confirmar',
'confirm-purge-top' => 'Voléd-vos purgiér lo cache de ceta pâge ?',
'confirm-purge-bottom' => 'Purgiér una pâge èface lo cache et pués fôrce la dèrriére vèrsion a étre montrâ.',

# action=watch/unwatch
'confirm-watch-button' => 'D’acôrd',
'confirm-watch-top' => 'Apondre ceta pâge a voutra lista de survelyence ?',
'confirm-unwatch-button' => 'D’acôrd',
'confirm-unwatch-top' => 'Enlevar ceta pâge de voutra lista de survelyence ?',

# Separators for various lists, etc.
'semicolon-separator' => '&nbsp;;&#32;',
'colon-separator' => '&nbsp;:&#32;',
'autocomment-prefix' => '&#32;–&#32;',
'percent' => '$1&nbsp;%',

# Multipage image navigation
'imgmultipageprev' => '← pâge devant',
'imgmultipagenext' => 'pâge aprés →',
'imgmultigo' => 'Listar !',
'imgmultigoto' => 'Alar a la pâge $1',

# Table pager
'ascending_abbrev' => 'que crêt',
'descending_abbrev' => 'que dècrêt',
'table_pager_next' => 'Pâge aprés',
'table_pager_prev' => 'Pâge devant',
'table_pager_first' => 'Premiére pâge',
'table_pager_last' => 'Dèrriére pâge',
'table_pager_limit' => 'Montrar $1 èlèment{{PLURAL:$1||s}} per pâge',
'table_pager_limit_label' => 'Rèsultats per pâge :',
'table_pager_limit_submit' => 'Listar',
'table_pager_empty' => 'Gins de rèsultat',

# Auto-summaries
'autosumm-blank' => 'Pâge blanchia',
'autosumm-replace' => 'Contegnu remplaciê per « $1 »',
'autoredircomment' => 'Pâge redirigiê vers [[$1]]',
'autosumm-new' => 'Pâge fêta avouéc « $1 »',

# Size units
'size-bytes' => '$1 o',
'size-kilobytes' => '$1 Kio',
'size-megabytes' => '$1 Mio',
'size-gigabytes' => '$1 Gio',

# Live preview
'livepreview-loading' => 'Chargement...',
'livepreview-ready' => 'Chargement... chavonâ !',
'livepreview-failed' => 'L’apèrçu vito fêt at pas reussi !
Èprovâd la prèvisualisacion normala.',
'livepreview-error' => 'Empossiblo de sè branchiér : $1 « $2 ».
Èprovâd la prèvisualisacion normala.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Los changements que dâtont de muens de $1 {{PLURAL:$1|seconda|secondes}} pôvont pas aparêtre dens ceta lista.',
'lag-warn-high' => 'En rêson d’un retârd important du sèrvor de bâsa de balyês, los changements que dâtont de muens de $1 {{PLURAL:$1|seconda|secondes}} pôvont pas aparêtre dens ceta lista.',

# Watchlist editor
'watchlistedit-numitems' => 'Voutra lista de survelyence contint {{PLURAL:$1|yon titro|$1 titros}}, sen comptar les pâges de discussion.',
'watchlistedit-noitems' => 'Voutra lista de survelyence contint gins de titro.',
'watchlistedit-normal-title' => 'Changiér la lista de survelyence',
'watchlistedit-normal-legend' => 'Enlevar des titros de la lista de survelyence',
'watchlistedit-normal-explain' => 'Los titros de voutra lista de survelyence sont montrâs ce-desot.
Por enlevar un titro (et sa pâge de discussion), pouentâd la câsa a coutâ et pués clicâd sur lo boton « {{int:Watchlistedit-normal-submit}} ».
Vos pouede asse-ben changiér la [[Special:EditWatchlist/raw|lista en fôrma bruta]].',
'watchlistedit-normal-submit' => 'Enlevar los titros',
'watchlistedit-normal-done' => '{{PLURAL:$1|Yon titro at étâ enlevâ|$1 titros ont étâ enlevâs}} de voutra lista de survelyence :',
'watchlistedit-raw-title' => 'Changiér la lista de survelyence en fôrma bruta',
'watchlistedit-raw-legend' => 'Changement de la lista de survelyence en fôrma bruta',
'watchlistedit-raw-explain' => 'Los titros de voutra lista de survelyence sont montrâs ce-desot et pôvont étre changiês en los apondent ou ben en los enlevent de la lista ;
yon titro per legne.
Quand vos éd feni, clicâd sur lo boton « {{int:Watchlistedit-raw-submit}} ».
Vos pouede asse-ben utilisar l’[[Special:EditWatchlist|èditor normal]].',
'watchlistedit-raw-titles' => 'Titros :',
'watchlistedit-raw-submit' => 'Betar a jorn la lista de survelyence',
'watchlistedit-raw-done' => 'Voutra lista de survelyence at étâ betâ a jorn.',
'watchlistedit-raw-added' => '{{PLURAL:$1|Yon titro at étâ apondu|$1 titros ont étâ apondus}} :',
'watchlistedit-raw-removed' => '{{PLURAL:$1|Yon titro at étâ enlevâ|$1 titros ont étâ enlevâs}} :',

# Watchlist editing tools
'watchlisttools-view' => 'Lista de survelyence',
'watchlisttools-edit' => 'Vêre et changiér la lista de survelyence',
'watchlisttools-raw' => 'Changiér la lista de survelyence en fôrma bruta',

# Iranian month names
'iranian-calendar-m1' => 'de farvardin',
'iranian-calendar-m2' => 'd’ordibehèch·ete',
'iranian-calendar-m3' => 'de c’hordâde',
'iranian-calendar-m4' => 'de tir',
'iranian-calendar-m5' => 'de mordâde',
'iranian-calendar-m6' => 'de ch·ahrivar',
'iranian-calendar-m7' => 'de mèhr',
'iranian-calendar-m8' => 'd’âbâne',
'iranian-calendar-m9' => 'd’âzar',
'iranian-calendar-m10' => 'de dê',
'iranian-calendar-m11' => 'de bahmane',
'iranian-calendar-m12' => 'd’èsfande',

# Hijri month names
'hijri-calendar-m1' => 'de mouharrame',
'hijri-calendar-m2' => 'de safar',
'hijri-calendar-m3' => 'de rabîʿ al-aval',
'hijri-calendar-m4' => 'de rabîʿ at-tânî',
'hijri-calendar-m5' => 'de j·oumâda al-oula',
'hijri-calendar-m6' => 'de j·oumâda at-tâniya',
'hijri-calendar-m7' => 'de raj·abe',
'hijri-calendar-m8' => 'de ch·aʿbâne',
'hijri-calendar-m9' => 'de ramadâne',
'hijri-calendar-m10' => 'de ch·avâl',
'hijri-calendar-m11' => 'de dou l-quaʿda',
'hijri-calendar-m12' => 'de dou l-hij·a',

# Hebrew month names
'hebrew-calendar-m1' => 'de tich·eri',
'hebrew-calendar-m2' => 'd’hèch·evane',
'hebrew-calendar-m3' => 'de quislèv',
'hebrew-calendar-m4' => 'de tevèt',
'hebrew-calendar-m5' => 'de ch·evat',
'hebrew-calendar-m6' => 'd’adar',
'hebrew-calendar-m6a' => 'd’adar-rich·one',
'hebrew-calendar-m6b' => 'd’adar-bèt',
'hebrew-calendar-m7' => 'de nissane',
'hebrew-calendar-m8' => 'd’iyar',
'hebrew-calendar-m9' => 'de sivane',
'hebrew-calendar-m10' => 'de tamouz',
'hebrew-calendar-m11' => 'd’av',
'hebrew-calendar-m12' => 'd’èloul',
'hebrew-calendar-m1-gen' => 'de tich·eri',
'hebrew-calendar-m2-gen' => 'd’hèch·evane',
'hebrew-calendar-m3-gen' => 'de quislèv',
'hebrew-calendar-m4-gen' => 'de tevèt',
'hebrew-calendar-m5-gen' => 'de ch·evat',
'hebrew-calendar-m6-gen' => 'd’adar',
'hebrew-calendar-m6a-gen' => 'd’adar-rich·one',
'hebrew-calendar-m6b-gen' => 'd’adar-bèt',
'hebrew-calendar-m7-gen' => 'de nissane',
'hebrew-calendar-m8-gen' => 'd’iyar',
'hebrew-calendar-m9-gen' => 'de sivane',
'hebrew-calendar-m10-gen' => 'de tamouz',
'hebrew-calendar-m11-gen' => 'd’av',
'hebrew-calendar-m12-gen' => 'd’èloul',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|discutar]])',

# Core parser functions
'unknown_extension_tag' => 'Balisa d’èxtension « $1 » encognua',
'duplicate-defaultsort' => "'''Atencion :''' la cllâf de tri per dèfôt « $2 » ècllafe cela « $1 ».",

# Special:Version
'version' => 'Vèrsion',
'version-extensions' => 'Èxtensions enstalâs',
'version-specialpages' => 'Pâges spèciâles',
'version-parserhooks' => 'Grèfons du parsor',
'version-variables' => 'Variâbles',
'version-antispam' => 'Prèvencion du spame',
'version-skins' => 'Habelyâjos',
'version-other' => 'De totes sôrtes',
'version-mediahandlers' => 'Maneyors de mèdia',
'version-hooks' => 'Grèfons',
'version-extension-functions' => 'Fonccions d’èxtension de dedens',
'version-parser-extensiontags' => 'Balises d’èxtension du parsor',
'version-parser-function-hooks' => 'Grèfons de les fonccions du parsor',
'version-hook-name' => 'Nom du grèfon',
'version-hook-subscribedby' => 'Soscrit per',
'version-version' => '(Vèrsion $1)',
'version-svn-revision' => '(v$2)',
'version-license' => 'Licence',
'version-poweredby-credits' => "Ceti vouiqui fonccione grâce a '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'ôtros',
'version-license-info' => 'MediaWiki est una programeria libra ; vos la pouede tornar distribuar et / ou changiér d’aprés los tèrmos de la Licence publica g·ènèrala GNU coment publeyê per la Free Software Foundation ; seye la vèrsion 2 de la Licence, ou ben (a voutron chouèx) tota novèla vèrsion.

MediaWiki est distribuâ dens l’èsperance que serat utila, mas SEN GINS DE GARANTIA ; sen mémo la garantia emplicita de COMÈRCIALISACION ou ben d’ADAPTACION A UN USÂJO PARTICULIÉR. Vêde la Licence publica g·ènèrala GNU por més de dètalys.

Vos devriâd avêr reçu un [{{SERVER}}{{SCRIPTPATH}}/COPYING ègzemplèro de la Licence publica g·ènèrala GNU] avouéc ceti programo ; ôtrament, ècrîde a la « Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA » ou ben [//www.gnu.org/licenses/old-licenses/gpl-2.0.html liéséd-la en legne].',
'version-software' => 'Programeries enstalâs',
'version-software-product' => 'Marchandie',
'version-software-version' => 'Vèrsion',
'version-entrypoints' => 'URL de pouent d’entrâ',
'version-entrypoints-header-entrypoint' => 'Pouent d’entrâ',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Chemin d’accès du fichiér',
'filepath-page' => 'Fichiér :',
'filepath-submit' => 'Alar trovar',
'filepath-summary' => 'Ceta pâge spèciâla montre lo chemin d’accès complèt d’un fichiér.
Les émâges sont montrâs dens lor plêna rèsolucion, los ôtros fichiérs sont chargiês et dèmarrâs tot drêt avouéc lor programo associyê.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Rechèrche des fichiérs en doblo',
'fileduplicatesearch-summary' => 'Rechèrche des fichiérs en doblo d’aprés lor mârca de chaplâjo.',
'fileduplicatesearch-legend' => 'Rechèrche d’un doblo',
'fileduplicatesearch-filename' => 'Nom du fichiér :',
'fileduplicatesearch-submit' => 'Rechèrchiér',
'fileduplicatesearch-info' => '$1 × $2 pixèls<br />Talye du fichiér : $3<br />Tipo MIME : $4',
'fileduplicatesearch-result-1' => 'Lo fichiér « $1 » at gins de doblo pariér.',
'fileduplicatesearch-result-n' => 'Lo fichiér « $1 » at $2 {{PLURAL:$2|doblo pariér|doblos pariérs}}.',
'fileduplicatesearch-noresults' => 'Nion fichiér apelâ « $1 » at étâ trovâ.',

# Special:SpecialPages
'specialpages' => 'Pâges spèciâles',
'specialpages-note' => '----
* Pâges spèciâles normales.
* <span class="mw-specialpagerestricted">Pâges spèciâles rètrentes.</span>
* <span class="mw-specialpagecached">Pâges spèciâles solament en cache (porriant étre dèpassâs).</span>',
'specialpages-group-maintenance' => 'Rapôrts de mantegnence',
'specialpages-group-other' => 'Ôtres pâges spèciâles',
'specialpages-group-login' => 'Sè branchiér / fâre un compto',
'specialpages-group-changes' => 'Dèrriérs changements et jornals',
'specialpages-group-media' => 'Rapôrts et tèlèchargements de fichiérs mèdia',
'specialpages-group-users' => 'Usanciérs et drêts apondus',
'specialpages-group-highuse' => 'Pâges d’usâjo important',
'specialpages-group-pages' => 'Listes de pâges',
'specialpages-group-pagetools' => 'Outils por les pâges',
'specialpages-group-wiki' => 'Balyês du vouiqui et outils',
'specialpages-group-redirects' => 'Pâges spèciâles redirigiês',
'specialpages-group-spam' => 'Outils anti-spame',

# Special:BlankPage
'blankpage' => 'Pâge voueda',
'intentionallyblankpage' => 'Ceta pâge est lèssiê èxprès voueda.',

# External image whitelist
'external_image_whitelist' => '  #Lèssiéd ceta legne justo d’ense.<pre>
#Endicâd los bocons d’èxprèssions racionèles (solament la partia endicâ entre-mié los //) ce-desot.
#Corrèspondront avouéc los lims hipèrtèxtos de les émâges (ben liyês) de defôr.
#Celos que corrèspondont sè montreront coment des émâges, ôtrament solament un lim de vers l’émâge serat montrâ.
#Les legnes que començont per un # seront considèrâs coment des comentèros.
#Ceta lista est pas sensibla a la câssa.

#Betâd tôs los bocons d’èxprèssions racionèles (*RegEx*) en-dessus de ceta legne. Lèssiéd ceta legne justo d’ense.</pre>',

# Special:Tags
'tags' => 'Balises des changements valides',
'tag-filter' => 'Filtrar les [[Special:Tags|balises]] :',
'tag-filter-submit' => 'Filtrar',
'tags-title' => 'Balises',
'tags-intro' => 'Ceta pâge liste les balises que la programeria pôt utilisar por marcar un changement et lor significacion.',
'tags-tag' => 'Nom de la balisa',
'tags-display-header' => 'Aparence dens les listes de changements',
'tags-description-header' => 'Dèscripcion complèta de la balisa',
'tags-hitcount-header' => 'Changements balisâs',
'tags-edit' => 'changiér',
'tags-hitcount' => '$1 changement{{PLURAL:$1||s}}',

# Special:ComparePages
'comparepages' => 'Comparar des pâges',
'compare-selector' => 'Comparar les vèrsions de les pâges',
'compare-page1' => 'Pâge 1',
'compare-page2' => 'Pâge 2',
'compare-rev1' => 'Vèrsion 1',
'compare-rev2' => 'Vèrsion 2',
'compare-submit' => 'Comparar',
'compare-invalid-title' => 'Lo titro que vos éd spècifiâ est envalido.',
'compare-title-not-exists' => 'Lo titro que vos éd spècefiâ ègziste pas.',
'compare-revision-not-exists' => 'La vèrsion que vos éd spècefiâ ègziste pas.',

# Database error messages
'dberr-header' => 'Ceti vouiqui at un problèmo',
'dberr-problems' => 'Dèsolâ ! Ceti seto rencontre des dificultâts tècniques.',
'dberr-again' => 'Tâchiéd d’atendre doux-três menutes et pués rechargiéd.',
'dberr-info' => '(Branchement u sèrvor de bâsa de balyês empossiblo : $1)',
'dberr-usegoogle' => 'Vos pouede tâchiér de chèrchiér avouéc Google pendent cél temps.',
'dberr-outofdate' => 'Notâd que lors endèxes de noutron contegnu pôvont étre dèpassâs.',
'dberr-cachederror' => 'O est una copia cachiê de la pâge demandâ et pôt étre dèpassâ.',

# HTML forms
'htmlform-invalid-input' => 'Des problèmos sont arrevâs avouéc quârques valors',
'htmlform-select-badoption' => 'La valor que vos éd spècefiâ est pas un chouèx valido.',
'htmlform-int-invalid' => 'La valor que vos éd spècefiâ est pas un entiér.',
'htmlform-float-invalid' => 'La valor que vos éd spècefiâ est pas un nombro.',
'htmlform-int-toolow' => 'La valor que vos éd spècefiâ est en-desot du muens de $1',
'htmlform-int-toohigh' => 'La valor que vos éd spècefiâ est en-dessus du més de $1',
'htmlform-required' => 'Ceta valor est nècèssèra',
'htmlform-submit' => 'Sometre',
'htmlform-reset' => 'Dèfâre los changements',
'htmlform-selectorother-other' => 'Ôtro',

# SQLite database support
'sqlite-has-fts' => '$1 avouéc rechèrche en tèxto complèt recognua',
'sqlite-no-fts' => '$1 sen rechèrche en tèxto complèt recognua',

# New logging system
'logentry-delete-delete' => '$1 at suprimâ la pâge $3',
'logentry-delete-restore' => '$1 at refêt la pâge $3',
'logentry-delete-event' => '$1 at changiê la visibilitât {{PLURAL:$5|d’un èvènement|de $5 èvènements}} du jornal dessus $3 : $4',
'logentry-delete-revision' => '$1 at changiê la visibilitât {{PLURAL:$5|d’una vèrsion|de $5 vèrsions}} sur la pâge $3 : $4',
'logentry-delete-event-legacy' => '$1 at changiê la visibilitât des èvènements du jornal dessus $3',
'logentry-delete-revision-legacy' => '$1 at changiê la visibilitât de les vèrsions sur la pâge $3',
'logentry-suppress-delete' => '$1 at suprimâ la pâge $3',
'logentry-suppress-event' => '$1 at changiê a cachon la visibilitât {{PLURAL:$5|d’un èvènement|de $5 èvènements}} du jornal dessus $3 : $4',
'logentry-suppress-revision' => '$1 at changiê a cachon la visibilitât {{PLURAL:$5|d’una vèrsion|de $5 vèrsions}} sur la pâge $3 : $4',
'logentry-suppress-event-legacy' => '$1 at changiê a cachon la visibilitât des èvènements du jornal dessus $3',
'logentry-suppress-revision-legacy' => '$1 at changiê a cachon la visibilitât de les vèrsions sur la pâge $3',
'revdelete-content-hid' => 'contegnu cachiê',
'revdelete-summary-hid' => 'rèsumâ de changement cachiê',
'revdelete-uname-hid' => 'nom d’usanciér cachiê',
'revdelete-content-unhid' => 'contegnu pas més cachiê',
'revdelete-summary-unhid' => 'rèsumâ de changement pas més cachiê',
'revdelete-uname-unhid' => 'nom d’usanciér pas més cachiê',
'revdelete-restricted' => 'at aplicâ les rèstriccions ux administrators',
'revdelete-unrestricted' => 'rèstriccions enlevâs por los administrators',
'logentry-move-move' => '$1 at dèplaciê la pâge $3 vers $4',
'logentry-move-move-noredirect' => '$1 at dèplaciê la pâge $3 vers $4 sen lèssiér una redirèccion',
'logentry-move-move_redir' => '$1 at dèplaciê la pâge $3 vers $4 en ècrasent sa redirèccion',
'logentry-move-move_redir-noredirect' => '$1 at dèplaciê la pâge $3 vers $4 en ècrasent sa redirèccion sen lèssiér una redirèccion',
'logentry-patrol-patrol' => '$1 at marcâ la vèrsion $4 de la pâge $3 coment controlâye',
'logentry-patrol-patrol-auto' => '$1 at marcâ ôtomaticament la vèrsion $4 de la pâge $3 coment controlâye',
'logentry-newusers-newusers' => 'Lo compto utilisator $1 est étâ fêt',
'logentry-newusers-create' => 'Lo compto utilisator $1 est étâ fêt',
'logentry-newusers-create2' => 'Lo compto utilisator $3 est étâ fêt per $1',
'logentry-newusers-autocreate' => 'Lo compto $1 at étâ fêt ôtomaticament',
'newuserlog-byemail' => 'contresegno mandâ per mèssageria èlèctronica',

# Feedback
'feedback-bugornote' => 'Se vos éte prèst a dècrire un problèmo tècnico en dètaly, volyéd [$1 signalar una cofierie].
Ôtrament, vos pouede utilisar lo formulèro simplifiâ ce-desot. Voutron comentèro serat apondu a la pâge « [$3 $2] », avouéc voutron nom d’usanciér et lo navigator que vos utilisâd.',
'feedback-subject' => 'Sujèt :',
'feedback-message' => 'Mèssâjo :',
'feedback-cancel' => 'Anular',
'feedback-submit' => 'Mandar voutron avis',
'feedback-adding' => 'Aponsa de voutros avis a la pâge...',
'feedback-error1' => 'Èrror : rèsultat de l’API pas recognu',
'feedback-error2' => 'Èrror : lo changement at pas reussi',
'feedback-error3' => 'Èrror : gins de rèponsa de l’API',
'feedback-thanks' => 'Grant-marci ! Voutron avis at étâ postâ sur la pâge « [$2 $1] ».',
'feedback-close' => 'Fêt',
'feedback-bugcheck' => 'Formidâblo ! Controlâd simplament qu’o est pas yona de les [$1 cofieries ja cognues].',
'feedback-bugnew' => 'J’é controlâ. Signalar una cofierie novèla',

# Search suggestions
'searchsuggest-search' => 'Rechèrchiér',
'searchsuggest-containing' => 'que contint...',

# API errors
'api-error-badaccess-groups' => 'Vos éte pas ôtorisâ a tèlèchargiér des fichiérs sur ceti vouiqui.',
'api-error-badtoken' => 'Èrror de dedens : crouyo « jeton ».',
'api-error-copyuploaddisabled' => 'Los tèlèchargements per URL sont dèsactivâs sur cél sèrvor.',
'api-error-duplicate' => 'Y at {{PLURAL:$1|[$2 un ôtro fichiér]|[$2 d’ôtros fichiérs]}} ja sur lo seto avouéc lo mémo contegnu.',
'api-error-duplicate-archive' => 'Y avéve {{PLURAL:$1|[$2 un ôtro fichiér]|[$2 d’ôtros fichiérs]}} ja sur lo seto avouéc lo mémo contegnu, mas {{PLURAL:$1|il at étâ suprimâ|ils ont étâ suprimâs}}.',
'api-error-duplicate-archive-popup-title' => 'Duplicar {{PLURAL:$1|lo fichiér|los fichiérs}} qu’{{PLURAL:$1|at ja étâ suprimâ|ont ja étâ suprimâs}}',
'api-error-duplicate-popup-title' => 'Fichiér{{PLURAL:$1||s}} en doblo',
'api-error-empty-file' => 'Lo fichiér que vos éd somês ére vouedo.',
'api-error-emptypage' => 'La crèacion de pâges novèles vouedes est pas ôtorisâ.',
'api-error-fetchfileerror' => 'Èrror de dedens : quârque-ren s’est mâl passâ pendent la rècupèracion du fichiér.',
'api-error-file-too-large' => 'Lo fichiér que vos éd somês ére trop grant.',
'api-error-filename-tooshort' => 'Lo nom du fichiér est trop côrt.',
'api-error-filetype-banned' => 'Cél tipo de fichiér est dèfendu.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|est pas un tipo de fichiér ôtorisâ|sont pas des tipos de fichiérs ôtorisâs}}. {{PLURAL:$3|Lo tipo de fichiér ôtorisâ est|Los tipos de fichiérs ôtorisâs sont}} $2.',
'api-error-filetype-missing' => 'L’èxtension du fichiér est manquenta.',
'api-error-hookaborted' => 'Lo changement que vos éd tâchiê de fâre est étâ anulâ per n’èxtension.',
'api-error-http' => 'Èrror de dedens : sè pôt pas branchiér u sèrvor.',
'api-error-illegal-filename' => 'Lo nom du fichiér est pas ôtorisâ.',
'api-error-internal-error' => 'Èrror de dedens : quârque-ren s’est mâl passâ pendent lo trètament de voutron tèlèchargement sur lo vouiqui.',
'api-error-invalid-file-key' => 'Èrror de dedens : gins de fichiér trovâ dens lo stocâjo temporèro.',
'api-error-missingparam' => 'Èrror de dedens : manque des paramètres dens la requéta.',
'api-error-missingresult' => 'Èrror de dedens : nos ens pas possu dètèrmenar se la copia avéve reussia.',
'api-error-mustbeloggedin' => 'Vos dête étre branchiê por tèlèchargiér des fichiérs.',
'api-error-mustbeposted' => 'Èrror de dedens : la requéta at fôta d’HTTP POST.',
'api-error-noimageinfo' => 'Lo tèlèchargement at reussi, mas lo sèrvor at pas balyê d’enformacions sur lo fichiér.',
'api-error-nomodule' => 'Èrror de dedens : gins de modulo de tèlèchargement dèfeni.',
'api-error-ok-but-empty' => 'Èrror de dedens : lo sèrvor at pas rèpondu.',
'api-error-overwrite' => 'Ècllafar un fichiér ègzistent est pas ôtorisâ.',
'api-error-stashfailed' => 'Èrror de dedens : lo sèrvor at pas possu encartar lo fichiér temporèro.',
'api-error-timeout' => 'Lo sèrvor at pas rèpondu dens lo dèlê atendu.',
'api-error-unclassified' => 'Una èrror encognua est arrevâ',
'api-error-unknown-code' => 'Èrror encognua : « $1 ».',
'api-error-unknown-error' => 'Èrror de dedens : quârque-ren s’est mâl passâ pendent lo tèlèchargement de voutron fichiér.',
'api-error-unknown-warning' => 'Avèrtissement encognu : $1',
'api-error-unknownerror' => 'Èrror encognua : « $1 ».',
'api-error-uploaddisabled' => 'Lo tèlèchargement est dèsactivâ sur ceti vouiqui.',
'api-error-verification-error' => 'Cél fichiér pôt étre corrompu, ou ben son èxtension est fôssa.',

# Durations
'duration-seconds' => '$1 second{{PLURAL:$1|a|es}}',
'duration-minutes' => '$1 menut{{PLURAL:$1|a|es}}',
'duration-hours' => '$1 hor{{PLURAL:$1|a|es}}',
'duration-days' => '$1 jorn{{PLURAL:$1||s}}',
'duration-weeks' => '$1 seman{{PLURAL:$1|a|es}}',
'duration-years' => '$1 an{{PLURAL:$1||s}}',
'duration-decades' => '$1 dècèni{{PLURAL:$1|a|es}}',
'duration-centuries' => '$1 sièclo{{PLURAL:$1||s}}',
'duration-millennia' => '$1 milènèro{{PLURAL:$1||s}}',

);
