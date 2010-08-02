<?php
/** French (Français)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Agzennay
 * @author Cedric31
 * @author ChrisPtDe
 * @author Coyau
 * @author Crochet.david
 * @author Damouns
 * @author Delhovlyn
 * @author Dereckson
 * @author Diti
 * @author Dodoïste
 * @author Elfix
 * @author Enzoreg
 * @author Esbardu
 * @author Fryed-peach
 * @author Grondin
 * @author Guillom
 * @author Hercule
 * @author Hégésippe Cormier
 * @author IAlex
 * @author Jagwar
 * @author Jean-Frédéric
 * @author JeanVoisin
 * @author Jens Liebenau
 * @author Klutzy
 * @author Korg
 * @author Korrigan
 * @author Kropotkine 113
 * @author Litlok
 * @author Louperivois
 * @author McDutchie
 * @author Meithal
 * @author Moyg
 * @author Omnipaedista
 * @author Peter17
 * @author PieRRoMaN
 * @author Quentinv57
 * @author Remember the dot
 * @author Robby
 * @author Seb35
 * @author Sherbrooke
 * @author Skalman
 * @author The Evil IP address
 * @author Urhixidur
 * @author Verdy p
 * @author Zetud
 * @author Горан Анђелковић
 * @author לערי ריינהארט
 */

$bookstoreList = array(
	'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

$namespaceNames = array(
	NS_MEDIA            => 'Média',
	NS_SPECIAL          => 'Spécial',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utilisateur',
	NS_USER_TALK        => 'Discussion_utilisateur',
	NS_PROJECT_TALK     => 'Discussion_$1',
	NS_FILE             => 'Fichier',
	NS_FILE_TALK        => 'Discussion_fichier',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
	NS_TEMPLATE         => 'Modèle',
	NS_TEMPLATE_TALK    => 'Discussion_modèle',
	NS_HELP             => 'Aide',
	NS_HELP_TALK        => 'Discussion_aide',
	NS_CATEGORY         => 'Catégorie',
	NS_CATEGORY_TALK    => 'Discussion_catégorie',
);

$namespaceAliases = array(
	'Discuter' => NS_TALK,
	'Discussion_Utilisateur' => NS_USER_TALK,
	'Discussion_Fichier' => NS_FILE_TALK,
	'Discussion_Image' => NS_FILE_TALK,
	'Discussion_Modèle' => NS_TEMPLATE_TALK,
	'Discussion_Aide' => NS_HELP_TALK,
	'Discussion_Catégorie' => NS_CATEGORY_TALK
);

$linkTrail = '/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙ]+)(.*)$/sDu';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'F j, Y à H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y à H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'Y F j à H:i',
);

$magicWords = array(
	'redirect'              => array( '0', '#REDIRECTION', '#REDIRECT' ),
	'notoc'                 => array( '0', '__AUCUNSOMMAIRE__', '__AUCUNETDM__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__AUCUNEGALERIE__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__FORCERSOMMAIRE__', '__FORCERTDM__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__SOMMAIRE__', '__TDM__', '__TOC__' ),
	'noeditsection'         => array( '0', '__SECTIONNONEDITABLE__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__AUCUNENTETE__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'MOISACTUEL', 'MOIS2ACTUEL', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'MOIS1ACTUEL', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'NOMMOISACTUEL', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'NOMGENMOISACTUEL', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ABREVMOISACTUEL', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'JOURACTUEL', 'JOUR1ACTUEL', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'JOUR2ACTUEL', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NOMJOURACTUEL', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ANNEEACTUELLE', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'HORAIREACTUEL', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'HEUREACTUELLE', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'MOISLOCAL', 'MOIS2LOCAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'MOIS1LOCAL', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'NOMMOISLOCAL', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'NOMGENMOISLOCAL', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'ABREVMOISLOCAL', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'JOURLOCAL', 'JOUR1LOCAL', 'LOCALDAY' ),
	'localday2'             => array( '1', 'JOUR2LOCAL', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'NOMJOURLOCAL', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ANNEELOCALE', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'HORAIRELOCAL', 'LOCALTIME' ),
	'localhour'             => array( '1', 'HEURELOCALE', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NOMBREPAGES', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NOMBREARTICLES', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NOMBREFICHIERS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NOMBREUTILISATEURS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'NOMBREUTILISATEURSACTIFS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'NOMBREMODIFS', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'NOMBREVUES', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'NOMPAGE', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'NOMPAGEX', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ESPACENOMMAGE', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ESPACENOMMAGEX', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'ESPACEDISCUSSION', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'ESPACEDISCUSSIONX', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'ESPACESUJET', 'ESPACEARTICLE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'ESPACESUJETX', 'ESPACEARTICLEX', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'NOMPAGECOMPLET', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'NOMPAGECOMPLETX', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'NOMSOUSPAGE', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'NOMSOUSPAGEX', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'NOMBASEDEPAGE', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'NOMBASEDEPAGEX', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'NOMPAGEDISCUSSION', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'NOMPAGEDISCUSSIONX', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'NOMPAGESUJET', 'NOMPAGEARTICLE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'NOMPAGESUJETX', 'NOMPAGEARTICLEX', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'img_thumbnail'         => array( '1', 'vignette', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'vignette=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'droite', 'right' ),
	'img_left'              => array( '1', 'gauche', 'left' ),
	'img_none'              => array( '1', 'néant', 'neant', 'none' ),
	'img_center'            => array( '1', 'centré', 'center', 'centre' ),
	'img_framed'            => array( '1', 'cadre', 'encadré', 'encadre', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'sans_cadre', 'sans cadre', 'non encadré', 'non encadre', 'frameless' ),
	'img_upright'           => array( '1', 'redresse', 'redresse=$1', 'redresse $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'bordure', 'border' ),
	'img_baseline'          => array( '1', 'ligne de base', 'base', 'baseline' ),
	'img_sub'               => array( '1', 'indice', 'ind', 'sub' ),
	'img_super'             => array( '1', 'exposant', 'exp', 'super', 'sup' ),
	'img_top'               => array( '1', 'haut', 'top' ),
	'img_text_top'          => array( '1', 'haut-texte', 'haut-txt', 'text-top' ),
	'img_middle'            => array( '1', 'milieu', 'middle' ),
	'img_bottom'            => array( '1', 'bas', 'bottom' ),
	'img_text_bottom'       => array( '1', 'bas-texte', 'bas-txt', 'text-bottom' ),
	'img_link'              => array( '1', 'lien=$1', 'link=$1' ),
	'sitename'              => array( '1', 'NOMSITE', 'SITENAME' ),
	'ns'                    => array( '0', 'ESPACEN:', 'NS:' ),
	'nse'                   => array( '0', 'ESPACENX:', 'NSE:' ),
	'localurl'              => array( '0', 'URLLOCALE:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URLLOCALEX:', 'LOCALURLE:' ),
	'server'                => array( '0', 'SERVEUR', 'SERVER' ),
	'servername'            => array( '0', 'NOMSERVEUR', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'CHEMINSCRIPT', 'SCRIPTPATH' ),
	'stylepath'             => array( '0', 'CHEMINSTYLE', 'STYLEPATH' ),
	'grammar'               => array( '0', 'GRAMMAIRE:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'GENRE:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__SANSCONVERSIONTITRE__', '__SANSCT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__SANSCONVERSIONCONTENU__', '__SANSCC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'SEMAINEACTUELLE', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'JDSACTUEL', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'SEMAINELOCALE', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'JDSLOCAL', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'IDVERSION', 'REVISIONID' ),
	'revisionday'           => array( '1', 'JOURVERSION', 'JOUR1VERSION', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'JOUR2VERSION', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'MOISVERSION', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'ANNEEVERSION', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'INSTANTVERSION', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'UTILISATEURVERSION', 'REVISIONUSER' ),
	'plural'                => array( '0', 'PLURIEL:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'URLCOMPLETE:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'URLCOMPLETEX:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'INITMINUS:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'INITMAJUS:', 'INITCAPIT:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'MINUS:', 'LC:' ),
	'uc'                    => array( '0', 'MAJUS:', 'CAPIT:', 'UC:' ),
	'raw'                   => array( '0', 'BRUT:', 'RAW:' ),
	'displaytitle'          => array( '1', 'AFFICHERTITRE', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'BRUT', 'B', 'R' ),
	'newsectionlink'        => array( '1', '__LIENNOUVELLESECTION__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__AUCUNLIENNOUVELLESECTION__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'VERSIONACTUELLE', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'ENCODEURL:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'ENCODEANCRE', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'INSTANTACTUEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'INSTANTLOCAL', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'MARQUEDIRECTION', 'MARQUEDIR', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#LANGUE:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'LANGUECONTENU', 'LANGCONTENU', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'PAGESDANSESPACE:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'NOMBREADMINS', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'FORMATNOMBRE', 'FORMATNUM' ),
	'padleft'               => array( '0', 'BOURRAGEGAUCHE', 'BOURREGAUCHE', 'PADLEFT' ),
	'padright'              => array( '0', 'BOURRAGEDROITE', 'BOURREDROITE', 'PADRIGHT' ),
	'special'               => array( '0', 'spécial', 'special' ),
	'defaultsort'           => array( '1', 'CLEFDETRI:', 'CLEDETRI:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'CHEMIN:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'balise', 'tag' ),
	'hiddencat'             => array( '1', '__CATCACHEE__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'PAGESDANSCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'TAILLEPAGE', 'PAGESIZE' ),
	'noindex'               => array( '1', '__AUCUNINDEX__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'NOMBREDANSGROUPE', 'NBDANSGROUPE', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__REDIRECTIONSTATIQUE__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'NIVEAUDEPROTECTION', 'PROTECTIONLEVEL' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Doubles redirections', 'DoublesRedirections', 'Redirections doubles', 'RedirectionsDoubles' ),
	'BrokenRedirects'           => array( 'Redirections cassées', 'RedirectionCassées', 'Redirections cassees', 'RedirectionsCassees' ),
	'Disambiguations'           => array( 'Homonymies', 'Homonymie', 'Pages d\'homonymie' ),
	'Userlogin'                 => array( 'Connexion', 'Identification' ),
	'Userlogout'                => array( 'Déconnexion', 'Deconnexion' ),
	'CreateAccount'             => array( 'Créer un compte', 'CréerUnCompte', 'CréerCompte' ),
	'Preferences'               => array( 'Préférences' ),
	'Watchlist'                 => array( 'Liste de suivi', 'ListeDeSuivi', 'Suivi' ),
	'Recentchanges'             => array( 'Modifications récentes', 'Modifications recentes', 'ModificationsRécentes', 'ModificationsRecentes' ),
	'Upload'                    => array( 'Téléverser', 'Televerser', 'Téléversement', 'Televersement' ),
	'Listfiles'                 => array( 'Liste des fichiers', 'ListeDesFichiers', 'Liste des images', 'ListeDesImages' ),
	'Newimages'                 => array( 'Nouveaux fichiers', 'NouveauxFichiers', 'Nouvelles images', 'NouvellesImages' ),
	'Listusers'                 => array( 'Liste des utilisateurs', 'ListeDesUtilisateurs', 'Utilisateurs' ),
	'Listgrouprights'           => array( 'Liste des droits de groupe', 'ListeDesDroitsDeGroupes' ),
	'Statistics'                => array( 'Statistiques', 'Stats' ),
	'Randompage'                => array( 'Page au hasard', 'PageAuHasard', 'Au hasard', 'AuHasard', 'Aléatoire', 'Aleatoire' ),
	'Lonelypages'               => array( 'Pages orphelines', 'PagesOrphelines' ),
	'Uncategorizedpages'        => array( 'Pages non catégorisées', 'PagesNonCatégorisées', 'Pages non categorisees', 'PagesNonCategorisees', 'Pages sans catégorie', 'PagesSansCatégorie', 'Pages sans categorie', 'PagesSansCategorie' ),
	'Uncategorizedcategories'   => array( 'Catégories non catégorisées', 'CatégoriesNonCatégorisées', 'Categories non categorisees', 'CategoriesNonCategorisees', 'Catégories sans catégorie', 'CatégoriesSansCatégorie', 'Categories sans categorie', 'CategoriesSansCategorie' ),
	'Uncategorizedimages'       => array( 'Fichiers non catégorisés', 'FichiersNonCatégorisés', 'Fichiers non categorises', 'FichiersNonCategorises', 'Fichiers sans catégorie', 'FichiersSansCatégorie', 'Fichiers sans categorie', 'FichiersSansCategorie', 'Images non catégorisées', 'ImagesNonCatégorisées', 'Images non categorisees', 'ImagesNonCategorisees', 'Images sans catégorie', 'ImagesSansCatégorie', 'Images sans categorie', 'ImagesSansCategorie' ),
	'Uncategorizedtemplates'    => array( 'Modèles non catégorisés', 'ModèlesNonCatégorisés', 'Modeles non categorises', 'ModelesNonCategorises', 'Modèles sans catégorie', 'ModèlesSansCatégorie', 'Modeles sans catégorie', 'ModelesSansCatégorie' ),
	'Unusedcategories'          => array( 'Catégories inutilisées', 'CatégoriesInutilisées', 'Categories inutilisees', 'CategoriesInutilisees', 'Catégories non utilisées', 'CatégoriesNonUtilisées', 'Categories non utilisees', 'CategoriesNonUtilisees' ),
	'Unusedimages'              => array( 'Fichiers inutilisés', 'FichiersInutilisés', 'Fichiers inutilises', 'FichiersInutilises', 'Fichiers non utilisés', 'FichiersNon utilisés', 'Fichiers non utilises', 'FichiersNon utilises', 'Images inutilisées', 'ImagesInutilisées', 'Images inutilisees', 'ImagesInutilisees', 'Images non utilisées', 'ImagesNonUtilisées', 'Images non utilisees', 'ImagesNonUtilisees' ),
	'Wantedpages'               => array( 'Pages demandées', 'PagesDemandées', 'Pages demandees', 'PagesDemandees', 'Liens brisés', 'LiensBrisés', 'Liens brises', 'LiensBrises' ),
	'Wantedcategories'          => array( 'Catégories demandées', 'CatégoriesDemandées', 'Categories demandees', 'CategoriesDemandees' ),
	'Wantedfiles'               => array( 'Fichiers demandés', 'FichiersDemandés', 'Fichiers demandes', 'FichiersDemandes' ),
	'Wantedtemplates'           => array( 'Modèles demandés', 'ModèlesDemandés', 'Modeles demandes', 'ModelesDemandes' ),
	'Mostlinked'                => array( 'Pages les plus liées', 'PagesLesPlusLiées', 'Pages les plus liees', 'PagesLesPlusLiees', 'Les plus liées', 'LesPlusLiées', 'Les plus liees', 'LesPlusLiees' ),
	'Mostlinkedcategories'      => array( 'Catégories les plus liées', 'CatégoriesLesPlusLiées', 'Categories les plus liees', 'CategoriesLesPlusLiees', 'Catégories les plus utilisées', 'CatégoriesLesPlusUtilisées', 'Categories les plus utilisees', 'CategoriesLesPlusUtilisees' ),
	'Mostlinkedtemplates'       => array( 'Modèles les plus liés', 'ModèlesLesPlusLiés', 'Modeles les plus lies', 'ModelesLesPlusLies', 'Modèles les plus utilisés', 'ModèlesLesPlusUtilisés', 'Modeles les plus utilises', 'ModelesLesPlusUtilises' ),
	'Mostimages'                => array( 'Fichiers les plus liés', 'FichiersLesPlusLiés', 'Fichiers les plus lies', 'FichiersLesPlusLies', 'Fichiers les plus utilisés', 'FichiersLesPlusUtilisés', 'Fichiers les plus utilises', 'FichiersLesPlusUtilises', 'Images les plus liées', 'ImagesLesPlusLiées', 'Images les plus liees', 'ImagesLesPlusLiees', 'Images les plus utilisées', 'ImagesLesPlusUtilisées', 'Images les plus utilisees', 'ImagesLesPlusUtilisees' ),
	'Mostcategories'            => array( 'Pages les plus catégorisées', 'PagesLesPlusCatégorisées', 'Pages les plus categorisees', 'PagesLesPlusCategorisees', 'Les plus catégorisés', 'LesPlusCatégorisés', 'Les plus categorises', 'LesPlusCategorises' ),
	'Mostrevisions'             => array( 'Pages les plus modifiées', 'PagesLesPlusModifiées', 'Pages les plus modifiees', 'PagesLesPlusModifiees', 'Les plus modifiés', 'LesPlusModifiés', 'Les plus modifies', 'LesPlusModifies' ),
	'Fewestrevisions'           => array( 'Pages les moins modifiées', 'PagesLesMoinsModifiées', 'Pages les moins modifiees', 'PagesLesMoinsModifiees', 'Les moins modifiés', 'LesMoinsModifiés', 'Les moins modifies', 'LesMoinsModifies' ),
	'Shortpages'                => array( 'Pages courtes', 'PagesCourtes' ),
	'Longpages'                 => array( 'Pages longues', 'PagesLongues' ),
	'Newpages'                  => array( 'Nouvelles pages', 'NouvellesPages', 'Pages récentes', 'PagesRécentes', 'Pages recentes', 'PagesRecentes' ),
	'Ancientpages'              => array( 'Pages anciennes', 'PagesAnciennes', 'Anciennes pages', 'AnciennesPages' ),
	'Deadendpages'              => array( 'Pages en impasse', 'PagesEnImpasse' ),
	'Protectedpages'            => array( 'Pages protégées', 'PagesProtégées', 'Pages protegees', 'PagesProtegees' ),
	'Protectedtitles'           => array( 'Titres protégés', 'TitresProtégés', 'Titres proteges', 'TitresProteges' ),
	'Allpages'                  => array( 'Toutes les pages', 'ToutesLesPages' ),
	'Prefixindex'               => array( 'Index', 'Préfixes', 'Prefixes' ),
	'Ipblocklist'               => array( 'Liste des blocages', 'ListeDesBlocages', 'Blocages', 'Utilisateurs bloqués', 'UtilisateursBloqués', 'Utilisateurs bloques', 'UtilisateursBloques', 'IP bloquées', 'IPBloquees', 'IPBloquées' ),
	'Unblock'                   => array( 'Débloquer', 'Déblocage' ),
	'Specialpages'              => array( 'Pages spéciales', 'PagesSpéciales', 'Pages speciales', 'PagesSpeciales' ),
	'Emailuser'                 => array( 'Envoyer un courriel', 'EnvoyerUnCourriel', 'Courriel', 'Envoyer un e-mail', 'EnvoyerUnEMail', 'E-mail', 'EMail' ),
	'Confirmemail'              => array( 'Confirmer l\'adresse de contact', 'Confirmer le courriel', 'ConfirmerLeCourriel' ),
	'Whatlinkshere'             => array( 'Pages liées', 'PagesLiées', 'Pages liees', 'PagesLiees' ),
	'Recentchangeslinked'       => array( 'Suivi des liens', 'SuiviDesLiens' ),
	'Movepage'                  => array( 'Renommer une page', 'Renommer', 'Renommage' ),
	'Blockme'                   => array( 'Bloquez-moi', 'Bloquezmoi' ),
	'Booksources'               => array( 'Ouvrages de référence', 'Ouvrages de reference', 'Ouvragesderéférence', 'Ouvragesdereference', 'Recherche ISBN', 'Recherche isbn', 'RechercheISBN', 'Rechercheisbn' ),
	'Categories'                => array( 'Catégories' ),
	'Export'                    => array( 'Exporter', 'Exportation' ),
	'Allmessages'               => array( 'Messages système', 'Messages systeme', 'Messagessystème', 'Messagessysteme' ),
	'Log'                       => array( 'Journal', 'Journaux' ),
	'Blockip'                   => array( 'Bloquer', 'Blocage' ),
	'Undelete'                  => array( 'Restaurer', 'Restauration' ),
	'Import'                    => array( 'Importer', 'Importation' ),
	'Lockdb'                    => array( 'Verrouiller la base', 'Verrouillerlabase', 'Verrouiller base', 'Verrouillerbase', 'Verrouiller BD', 'VerrouillerBD', 'Verrouiller bd', 'Verrouillerbd' ),
	'Unlockdb'                  => array( 'Déverrouiller la base', 'Déverrouillerlabase', 'Deverrouiller la base', 'Deverrouillerlabase', 'Déverrouiller base', 'Déverrouillerbase', 'Deverrouiller base', 'Deverrouillerbase', 'Déverrouiller BD', 'DéverrouillerBD', 'Deverrouiller BD', 'DeverrouillerBD', 'Déverrouiller bd', 'Déverrouillerbd', 'Deverrouiller bd', 'Deverrouillerbd' ),
	'Userrights'                => array( 'Permissions', 'Droits', 'Droits des utilisateurs' ),
	'MIMEsearch'                => array( 'Recherche MIME', 'RechercheMIME', 'Recherche mime', 'Recherchemime' ),
	'FileDuplicateSearch'       => array( 'Recherche fichier en double', 'RechercheFichierEnDouble' ),
	'Unwatchedpages'            => array( 'Pages non suivies', 'Pagesnonsuivies' ),
	'Listredirects'             => array( 'Liste des redirections', 'Listedesredirections', 'Liste des redirects', 'Listedesredirects', 'Liste redirections', 'Listeredirections', 'Liste redirects', 'Listeredirects' ),
	'Revisiondelete'            => array( 'Versions supprimées', 'Versions supprimees', 'Versionsupprimées', 'Versionsupprimees' ),
	'Unusedtemplates'           => array( 'Modèles inutilisés', 'Modèlesinutilisés', 'Modeles inutilises', 'Modelesinutilises', 'Modèles non utilisés', 'Modèlesnonutilisés', 'Modeles non utilises', 'Modelesnonutilises' ),
	'Randomredirect'            => array( 'Redirection au hasard', 'Redirect au hasard', 'Redirectionauhasard', 'Redirectauhasard', 'Redirection aléatoire', 'Redirect aléatoire', 'Redirectionaléatoire', 'Redirectaléatoire', 'Redirection aleatoire', 'Redirect aleatoire', 'Redirectionaleatoire', 'Redirectaleatoire' ),
	'Mypage'                    => array( 'Ma page', 'Mapage' ),
	'Mytalk'                    => array( 'Mes discussions', 'Mesdiscussions' ),
	'Mycontributions'           => array( 'Mes contributions', 'Mescontributions' ),
	'Listadmins'                => array( 'Liste des administrateurs', 'Listedesadministrateurs', 'Liste des admins', 'Listedesadmins', 'Liste admins', 'Listeadmins' ),
	'Listbots'                  => array( 'Liste des bots', 'ListedesBots' ),
	'Popularpages'              => array( 'Pages les plus visitées', 'Pages les plus visitees', 'Pageslesplusvisitées', 'Pageslesplusvisitees' ),
	'Search'                    => array( 'Recherche', 'Rechercher', 'Chercher' ),
	'Resetpass'                 => array( 'Changement du mot de passe', 'ChangementDuMotDePasse' ),
	'Withoutinterwiki'          => array( 'Sans interwiki', 'Sansinterwiki', 'Sans interwikis', 'Sansinterwikis' ),
	'MergeHistory'              => array( 'Fusionner les historiques', 'FusionnerHistoriques', 'Fusionner l\'historique', 'Fusionnerlhistorique' ),
	'Filepath'                  => array( 'Chemin du fichier', 'CheminDuFichier', 'CheminFichier' ),
	'Invalidateemail'           => array( 'Invalider le courriel', 'Invalider courriel', 'InvaliderCourriel' ),
	'Blankpage'                 => array( 'Page blanche', 'PageBlanche' ),
	'LinkSearch'                => array( 'Recherche de lien', 'Recherche de liens' ),
	'DeletedContributions'      => array( 'Contributions supprimées', 'ContributionsSupprimées', 'ContributionSupprimees' ),
	'Tags'                      => array( 'Balises' ),
	'Activeusers'               => array( 'Utilisateurs actifs', 'UtilisateursActifs' ),
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Souligner les liens :',
'tog-highlightbroken'         => 'Afficher <a href="" class="new">en rouge</a> les liens vers les pages inexistantes (sinon :  comme ceci<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Justifier les paragraphes',
'tog-hideminor'               => 'Cacher les modifications récentes mineures',
'tog-hidepatrolled'           => 'Masquer les modifications surveillées parmi les modifications récentes',
'tog-newpageshidepatrolled'   => 'Masquer les pages surveillées parmi les nouvelles pages',
'tog-extendwatchlist'         => 'Étendre la liste de suivi pour afficher toutes les modifications et non seulement les plus récentes',
'tog-usenewrc'                => 'Utiliser les modifications récentes améliorées (nécessite JavaScript)',
'tog-numberheadings'          => 'Numéroter automatiquement les titres de section',
'tog-showtoolbar'             => 'Montrer la barre de menu de modification (nécessite JavaScript)',
'tog-editondblclick'          => 'Double-cliquer permet de modifier une page (nécessite JavaScript)',
'tog-editsection'             => 'Activer les modifications de sections grâce aux liens « [modifier] »',
'tog-editsectiononrightclick' => 'Un clic droit sur un titre de section permet de modifier celle-ci (nécessite JavaScript)',
'tog-showtoc'                 => 'Afficher la table des matières (pour les pages ayant plus de 3 sections)',
'tog-rememberpassword'        => 'Se souvenir de mon identification sur cet ordinateur (au maximum $1 {{PLURAL:$1|jour|jours}})',
'tog-watchcreations'          => 'Ajouter les pages que je crée à ma liste de suivi',
'tog-watchdefault'            => 'Ajouter les pages que je modifie à ma liste de suivi',
'tog-watchmoves'              => 'Ajouter les pages que je renomme à ma liste de suivi',
'tog-watchdeletion'           => 'Ajouter les pages que je supprime à ma liste de suivi',
'tog-previewontop'            => 'Montrer la prévisualisation au-dessus de la zone de modification',
'tog-previewonfirst'          => 'Montrer la prévisualisation lors de la première modification',
'tog-nocache'                 => 'Désactiver le cache des pages par le navigateur',
'tog-enotifwatchlistpages'    => 'M’avertir par courrier électronique lorsqu’une page de ma liste de suivi est modifiée',
'tog-enotifusertalkpages'     => 'M’avertir par courriel si ma page de discussion est modifiée',
'tog-enotifminoredits'        => 'M’avertir par courriel même en cas de modifications mineures des pages',
'tog-enotifrevealaddr'        => 'Afficher mon adresse e-mail dans les e-mails de notification',
'tog-shownumberswatching'     => 'Afficher le nombre d’utilisateurs qui suivent cette page',
'tog-oldsig'                  => 'Aperçu de la signature existante :',
'tog-fancysig'                => 'Traiter la signature comme du wikitexte (sans lien automatique)',
'tog-externaleditor'          => 'Utiliser par défaut un éditeur de texte externe (pour les utilisateurs avancés, nécessite des réglages spécifiques sur votre ordinateur)',
'tog-externaldiff'            => 'Utiliser un comparateur externe par défaut (pour les utilisateurs avancés, nécessite des réglages sur votre ordinateur)',
'tog-showjumplinks'           => 'Activer les liens « navigation » et « recherche » en haut de page',
'tog-uselivepreview'          => 'Utiliser l’aperçu rapide (nécessite JavaScript) (expérimental)',
'tog-forceeditsummary'        => 'M’avertir lorsque je n’ai pas spécifié de résumé de modification',
'tog-watchlisthideown'        => 'Masquer mes propres modifications dans la liste de suivi',
'tog-watchlisthidebots'       => 'Masquer les modifications faites par des robots dans la liste de suivi',
'tog-watchlisthideminor'      => 'Masquer les modifications mineures dans la liste de suivi',
'tog-watchlisthideliu'        => 'Masquer les modifications faites par des utilisateurs inscrits dans la liste de suivi',
'tog-watchlisthideanons'      => 'Masquer les modifications anonymes dans la liste de suivi',
'tog-watchlisthidepatrolled'  => 'Masquer les modifications surveillées dans la liste de suivi',
'tog-nolangconversion'        => 'Désactiver la conversion des variantes linguistiques',
'tog-ccmeonemails'            => 'M’envoyer une copie des courriels que j’envoie aux autres utilisateurs',
'tog-diffonly'                => 'Ne pas montrer le contenu des pages sous les diffs',
'tog-showhiddencats'          => 'Afficher les catégories cachées',
'tog-noconvertlink'           => 'Désactiver la conversion des titres',
'tog-norollbackdiff'          => 'Ne pas afficher le diff lors d’une révocation',

'underline-always'  => 'Toujours',
'underline-never'   => 'Jamais',
'underline-default' => 'Selon le navigateur',

# Font style option in Special:Preferences
'editfont-style'     => 'Style de police de la zone d’édition :',
'editfont-default'   => 'Celui par défaut du navigateur',
'editfont-monospace' => 'Police de chasse fixe',
'editfont-sansserif' => 'Police sans empattement',
'editfont-serif'     => 'Police avec empattements',

# Dates
'sunday'        => 'dimanche',
'monday'        => 'lundi',
'tuesday'       => 'mardi',
'wednesday'     => 'mercredi',
'thursday'      => 'jeudi',
'friday'        => 'vendredi',
'saturday'      => 'samedi',
'sun'           => 'dim',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'jeu',
'fri'           => 'ven',
'sat'           => 'sam',
'january'       => 'janvier',
'february'      => 'février',
'march'         => 'mars',
'april'         => 'avril',
'may_long'      => 'mai',
'june'          => 'juin',
'july'          => 'juillet',
'august'        => 'août',
'september'     => 'septembre',
'october'       => 'octobre',
'november'      => 'novembre',
'december'      => 'décembre',
'january-gen'   => 'janvier',
'february-gen'  => 'février',
'march-gen'     => 'mars',
'april-gen'     => 'avril',
'may-gen'       => 'mai',
'june-gen'      => 'juin',
'july-gen'      => 'juillet',
'august-gen'    => 'août',
'september-gen' => 'septembre',
'october-gen'   => 'octobre',
'november-gen'  => 'novembre',
'december-gen'  => 'décembre',
'jan'           => 'jan',
'feb'           => 'fév',
'mar'           => 'mar',
'apr'           => 'avr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aoû',
'sep'           => 'sep',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'déc',

# Categories related messages
'pagecategories'                 => 'Catégorie{{PLURAL:$1||s}}',
'category_header'                => 'Pages dans la catégorie « $1 »',
'subcategories'                  => 'Sous-catégories',
'category-media-header'          => 'Fichiers multimédias dans la catégorie « $1 »',
'category-empty'                 => "''Cette catégorie ne contient aucune page, sous-catégorie ou fichier multimédia.''",
'hidden-categories'              => '{{PLURAL:$1|Catégorie cachée|Catégories cachées}}',
'hidden-category-category'       => 'Catégories cachées',
'category-subcat-count'          => 'Cette catégorie comprend {{PLURAL:$2|la sous-catégorie|$2 sous-catégories, dont {{PLURAL:$1|celle|les $1}}}} ci-dessous.',
'category-subcat-count-limited'  => 'Cette catégorie comprend {{PLURAL:$1|la sous-catégorie|les $1 sous-catégories}} ci-dessous.',
'category-article-count'         => 'Cette catégorie contient {{PLURAL:$2|la page suivante|$2 pages, dont {{PLURAL:$1|celle|les $1}} ci-dessous}}.',
'category-article-count-limited' => '{{PLURAL:$1|La page suivante figure|Les $1 pages suivantes figurent}} dans la présente catégorie.',
'category-file-count'            => 'Cette catégorie contient {{PLURAL:$2|le fichier suivant|$2 fichiers, dont {{PLURAL:$1|celui|les $1}} ci-dessous}}.',
'category-file-count-limited'    => '{{PLURAL:$1|Le fichier suivant figure|Les $1 fichiers suivants figurent}} dans la présente catégorie.',
'listingcontinuesabbrev'         => '(suite)',
'index-category'                 => 'Pages indexées',
'noindex-category'               => 'Pages non indexées',

'mainpagetext'      => "'''MediaWiki a été installé avec succès.'''",
'mainpagedocfooter' => 'Consultez le [http://meta.wikimedia.org/wiki/Aide:Contenu Guide de l’utilisateur] pour plus d’informations sur l’utilisation de ce logiciel.

== Démarrer avec MediaWiki ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste des paramètres de configuration]
* [http://www.mediawiki.org/wiki/Manual:FAQ/fr FAQ sur MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Liste de discussion sur les distributions de MediaWiki]',

'about'         => 'À propos',
'article'       => 'Page de contenu',
'newwindow'     => '(ouvre une nouvelle fenêtre)',
'cancel'        => 'Annuler',
'moredotdotdot' => 'Et plus …',
'mypage'        => 'Ma page',
'mytalk'        => 'Page de discussion',
'anontalk'      => 'Discussion avec cette adresse IP',
'navigation'    => 'Navigation',
'and'           => '&#32;et',

# Cologne Blue skin
'qbfind'         => 'Rechercher',
'qbbrowse'       => 'Parcourir',
'qbedit'         => 'Modifier',
'qbpageoptions'  => 'Cette page',
'qbpageinfo'     => 'Contexte',
'qbmyoptions'    => 'Mes pages',
'qbspecialpages' => 'Pages spéciales',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Ajouter un sujet',
'vector-action-delete'           => 'Supprimer',
'vector-action-move'             => 'Renommer',
'vector-action-protect'          => 'Protéger',
'vector-action-undelete'         => 'Rétablir',
'vector-action-unprotect'        => 'Déprotéger',
'vector-namespace-category'      => 'Catégorie',
'vector-namespace-help'          => 'Aide',
'vector-namespace-image'         => 'Fichier',
'vector-namespace-main'          => 'Page',
'vector-namespace-media'         => 'Page de média',
'vector-namespace-mediawiki'     => 'Message',
'vector-namespace-project'       => 'Page de projet',
'vector-namespace-special'       => 'Page spéciale',
'vector-namespace-talk'          => 'Discussion',
'vector-namespace-template'      => 'Modèle',
'vector-namespace-user'          => 'Page utilisateur',
'vector-simplesearch-preference' => 'Activer les suggestions de recherche améliorées (seulement pour Vector)',
'vector-view-create'             => 'Créer',
'vector-view-edit'               => 'Modifier',
'vector-view-history'            => 'Afficher l’historique',
'vector-view-view'               => 'Lire',
'vector-view-viewsource'         => 'Voir la source',
'actions'                        => 'Actions',
'namespaces'                     => 'Espaces de noms',
'variants'                       => 'Variantes',

'errorpagetitle'    => 'Erreur',
'returnto'          => 'Revenir à la page $1.',
'tagline'           => 'De {{SITENAME}}.',
'help'              => 'Aide',
'search'            => 'Rechercher',
'searchbutton'      => 'Rechercher',
'go'                => 'Consulter',
'searcharticle'     => 'Lire',
'history'           => 'Historique de la page',
'history_short'     => 'Historique',
'updatedmarker'     => 'modifié depuis ma dernière visite',
'info_short'        => 'Informations',
'printableversion'  => 'Version imprimable',
'permalink'         => 'Lien historique',
'print'             => 'Imprimer',
'edit'              => 'Modifier',
'create'            => 'Créer',
'editthispage'      => 'Modifier cette page',
'create-this-page'  => 'Créer cette page',
'delete'            => 'Supprimer',
'deletethispage'    => 'Supprimer cette page',
'undelete_short'    => 'Restaurer $1 modification{{PLURAL:$1||s}}',
'protect'           => 'Protéger',
'protect_change'    => 'modifier',
'protectthispage'   => 'Protéger cette page',
'unprotect'         => 'Déprotéger',
'unprotectthispage' => 'Déprotéger cette page',
'newpage'           => 'Nouvelle page',
'talkpage'          => 'Discussion sur cette page',
'talkpagelinktext'  => 'discuter',
'specialpage'       => 'Page spéciale',
'personaltools'     => 'Outils personnels',
'postcomment'       => 'Nouvelle section',
'articlepage'       => 'Voir la page de contenu',
'talk'              => 'Discussion',
'views'             => 'Affichages',
'toolbox'           => 'Boîte à outils',
'userpage'          => 'Page utilisateur',
'projectpage'       => 'Page méta',
'imagepage'         => 'Voir la page du fichier',
'mediawikipage'     => 'Voir la page du message',
'templatepage'      => 'Voir la page du modèle',
'viewhelppage'      => 'Voir la page d’aide',
'categorypage'      => 'Voir la page de catégorie',
'viewtalkpage'      => 'Page de discussion',
'otherlanguages'    => 'Autres langues',
'redirectedfrom'    => '(Redirigé depuis $1)',
'redirectpagesub'   => 'Page de redirection',
'lastmodifiedat'    => 'Dernière modification de cette page le $1 à $2.<br />',
'viewcount'         => 'Cette page a été consultée $1 fois{{PLURAL:$1||}}.',
'protectedpage'     => 'Page protégée',
'jumpto'            => 'Aller à :',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'rechercher',
'view-pool-error'   => 'Désolé, les serveurs sont surchargés en ce moment.
Trop d’utilisateurs cherchent à consulter cette page.
Veuillez attendre un moment avant de retenter l’accès à cette page.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'À propos de {{SITENAME}}',
'aboutpage'            => 'Project:À propos',
'copyright'            => 'Contenu disponible sous $1.',
'copyrightpage'        => '{{ns:project}}:Copyrights',
'currentevents'        => 'Actualités',
'currentevents-url'    => 'Project:Actualités',
'disclaimers'          => 'Avertissements',
'disclaimerpage'       => 'Project:Avertissements généraux',
'edithelp'             => 'Aide',
'edithelppage'         => 'Help:Comment modifier une page',
'helppage'             => 'Help:Accueil',
'mainpage'             => 'Accueil',
'mainpage-description' => 'Accueil',
'policy-url'           => 'Project:Règles',
'portal'               => 'Communauté',
'portal-url'           => 'Project:Accueil',
'privacy'              => 'Politique de confidentialité',
'privacypage'          => 'Project:Confidentialité',

'badaccess'        => 'Erreur de permission',
'badaccess-group0' => 'Vous n’avez pas les droits suffisants pour réaliser l’action que vous demandez.',
'badaccess-groups' => 'L’action que vous essayez de réaliser n’est accessible qu’aux utilisateurs {{PLURAL:$2|du groupe|des groupes}} : $1.',

'versionrequired'     => 'Version $1 de MediaWiki nécessaire',
'versionrequiredtext' => 'La version $1 de MediaWiki est nécessaire pour utiliser cette page. Consultez [[Special:Version|la page des versions]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Récupérée de « $1 »',
'youhavenewmessages'      => 'Vous avez $1 ($2).',
'newmessageslink'         => 'de nouveaux messages',
'newmessagesdifflink'     => 'dernière modification',
'youhavenewmessagesmulti' => 'Vous avez de nouveaux messages sur $1.',
'editsection'             => 'modifier',
'editold'                 => 'modifier',
'viewsourceold'           => 'voir la source',
'editlink'                => 'modifier',
'viewsourcelink'          => 'voir la source',
'editsectionhint'         => 'Modifier la section : $1',
'toc'                     => 'Sommaire',
'showtoc'                 => 'afficher',
'hidetoc'                 => 'masquer',
'thisisdeleted'           => 'Désirez-vous afficher ou restaurer $1 ?',
'viewdeleted'             => 'Voir $1 ?',
'restorelink'             => '{{PLURAL:$1|la modification effacée|les $1 modifications effacées}}',
'feedlinks'               => 'Flux',
'feed-invalid'            => 'Type de flux invalide.',
'feed-unavailable'        => 'Les flux de syndication ne sont pas disponibles',
'site-rss-feed'           => 'Flux RSS de $1',
'site-atom-feed'          => 'Flux Atom de $1',
'page-rss-feed'           => 'Flux RSS de « $1 »',
'page-atom-feed'          => 'Flux Atom de « $1 »',
'red-link-title'          => '$1 (page inexistante)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Page',
'nstab-user'      => 'Page utilisateur',
'nstab-media'     => 'Média',
'nstab-special'   => 'Page spéciale',
'nstab-project'   => 'À propos',
'nstab-image'     => 'Fichier',
'nstab-mediawiki' => 'Message',
'nstab-template'  => 'Modèle',
'nstab-help'      => 'Aide',
'nstab-category'  => 'Catégorie',

# Main script and global functions
'nosuchaction'      => 'Action inconnue',
'nosuchactiontext'  => 'L’action spécifiée dans l’URL est invalide.
Vous avez peut-être mal entré l’URL ou suivi un lien erroné.
Il peut également s’agir d’un bogue dans le logiciel utilisé par {{SITENAME}}.',
'nosuchspecialpage' => 'Page spéciale inexistante',
'nospecialpagetext' => '<strong>Vous avez demandé une page spéciale qui n’existe pas.</strong>

Une liste des pages spéciales valides se trouve sur [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Erreur',
'databaseerror'        => 'Erreur de la base de données',
'dberrortext'          => 'Une erreur de syntaxe de la requête dans la base de données est survenue.
Ceci peut indiquer un bogue dans le logiciel.
La dernière requête traitée par la base de données était :
<blockquote><tt>$1</tt></blockquote>
depuis la fonction « <tt>$2</tt> ».
La base de données a renvoyé l’erreur « <tt>$3 : $4</tt> ».',
'dberrortextcl'        => 'Une requête dans la base de données comporte une erreur de syntaxe.
La dernière requête émise était :
« $1 »
dans la fonction « $2 ».
La base de données a renvoyé l’erreur « $3 : $4 ».',
'laggedslavemode'      => 'Attention, cette page peut ne pas contenir les toutes dernières modifications effectuées',
'readonly'             => 'Base de données verrouillée',
'enterlockreason'      => 'Indiquez la raison du verrouillage ainsi qu’une estimation de sa durée',
'readonlytext'         => 'Les ajouts et mises à jour de la base de données sont actuellement bloqués, probablement pour permettre la maintenance de la base, après quoi, tout rentrera dans l’ordre.

L’administrateur ayant verrouillé la base de données a fourni l’explication suivante :<br />$1',
'missing-article'      => 'La base de données n’a pas trouvé le texte d’une page qu’elle aurait dû trouver, intitulée « $1 » $2.

Généralement, cela survient en suivant un lien vers un diff périmé ou vers l’historique d’une page supprimée.

Si ce n’est pas le cas, il peut s’agir d’un bogue dans le programme.
Veuillez le signaler à un [[Special:ListUsers/sysop|administrateur]] sans oublier de lui indiquer l’URL du lien.',
'missingarticle-rev'   => '(numéro de version : $1)',
'missingarticle-diff'  => '(diff : $1, $2)',
'readonly_lag'         => 'La base de données a été automatiquement verrouillée pendant que les serveurs secondaires rattrapent leur retard sur le serveur principal.',
'internalerror'        => 'Erreur interne',
'internalerror_info'   => 'Erreur interne : $1',
'fileappenderrorread'  => 'Impossible de lire « $1 » lors de l’insertion',
'fileappenderror'      => 'Impossible d’ajouter « $1 » à « $2 ».',
'filecopyerror'        => 'Impossible de copier le fichier « $1 » vers « $2 ».',
'filerenameerror'      => 'Impossible de renommer le fichier « $1 » en « $2 ».',
'filedeleteerror'      => 'Impossible de supprimer le fichier « $1 ».',
'directorycreateerror' => 'Impossible de créer le dossier « $1 ».',
'filenotfound'         => 'Impossible de trouver le fichier « $1 ».',
'fileexistserror'      => 'Impossible d’écrire dans le dossier « $1 » : le fichier existe',
'unexpected'           => 'Valeur inattendue : « $1 » = « $2 ».',
'formerror'            => 'Erreur : Impossible de soumettre le formulaire',
'badarticleerror'      => 'Cette action ne peut pas être effectuée sur cette page.',
'cannotdelete'         => 'Impossible de supprimer la page ou le fichier « $1 ».
La suppression a peut-être déjà été effectuée par quelqu’un d’autre.',
'badtitle'             => 'Mauvais titre',
'badtitletext'         => 'Le titre de la page demandée est invalide, vide, ou il s’agit d’un titre inter-langue ou inter-projet mal lié. Il contient peut-être un ou plusieurs caractères qui ne peuvent pas être utilisés dans les titres.',
'perfcached'           => 'Les données suivantes sont en cache et peuvent ne pas être à jour.',
'perfcachedts'         => 'Les données suivantes sont en cache, elles ne sont donc pas obligatoirement à jour. La dernière actualisation date du $1.',
'querypage-no-updates' => 'Les mises à jour pour cette page sont actuellement désactivées. Les données ci-dessous ne sont pas mises à jour.',
'wrong_wfQuery_params' => 'Paramètres incorrects sur wfQuery()<br />
Fonction : $1<br />
Requête : $2',
'viewsource'           => 'Voir le texte source',
'viewsourcefor'        => 'pour $1',
'actionthrottled'      => 'Action limitée',
'actionthrottledtext'  => 'Pour lutter contre les pourriels, la fréquence d’exécution de cette action est limitée dans un laps de temps assez court, et vous avez dépassé cette limite.
Veuillez essayer de nouveau dans quelques minutes.',
'protectedpagetext'    => 'Cette page a été protégée pour empêcher sa modification.',
'viewsourcetext'       => 'Vous pouvez voir et copier le contenu de la page :',
'protectedinterface'   => 'Cette page fournit du texte d’interface pour le logiciel et est protégée pour éviter les abus.',
'editinginterface'     => "'''Attention :''' vous êtes en train de modifier une page utilisée pour créer le texte de l’interface du logiciel. Les changements se répercuteront, selon le contexte, sur toutes ou certaines pages visibles par les autres utilisateurs. Pour les traductions, nous vous invitons à utiliser le projet MediaWiki d’internationalisation des messages [http://translatewiki.net/wiki/Main_Page?setlang=fr translatewiki.net].",
'sqlhidden'            => '(Requête SQL cachée)',
'cascadeprotected'     => 'Cette page est protégée car elle est incluse par {{PLURAL:$1|la page suivante, qui a été protégée|les pages suivantes, qui ont été protégées}} avec l’option « protection en cascade » activée :
$2',
'namespaceprotected'   => "Vous n’avez pas la permission de modifier les pages de l’espace de noms « '''$1''' ».",
'customcssjsprotected' => 'Vous n’avez pas la permission de modifier cette page, car elle contient les préférences d’un autre utilisateur.',
'ns-specialprotected'  => 'Les pages dans l’espace de noms « {{ns:special}} » ne peuvent pas être modifiées.',
'titleprotected'       => "Ce titre a été protégé à la création par [[User:$1|$1]].
Le motif avancé est « ''$2'' ».",

# Virus scanner
'virus-badscanner'     => "Mauvaise configuration : scanneur de virus inconnu : ''$1''",
'virus-scanfailed'     => 'Échec de la recherche (code $1)',
'virus-unknownscanner' => 'antivirus inconnu :',

# Login and logout pages
'logouttext'                 => "'''Vous êtes à présent déconnecté(e).'''

Vous pouvez continuer à utiliser {{SITENAME}} de façon anonyme, [[Special:UserLogin|vous reconnecter]] sous le même nom ou un autre.
Notez que certaines pages peuvent être encore affichées comme si vous étiez toujours connecté(e), jusqu’à ce que vous effaciez le cache de votre navigateur.",
'welcomecreation'            => '== Bienvenue, $1 ! ==

Votre compte a été créé.
N’oubliez pas de personnaliser vos [[Special:Preferences|préférences sur {{SITENAME}}]].',
'yourname'                   => 'Nom d’utilisateur :',
'yourpassword'               => 'Mot de passe :',
'yourpasswordagain'          => 'Confirmez le mot de passe :',
'remembermypassword'         => 'Me reconnecter automatiquement aux prochaines visites (au maximum $1 {{PLURAL:$1|jour|jours}})',
'yourdomainname'             => 'Votre domaine',
'externaldberror'            => 'Une erreur s’est produite avec la base de données d’authentification externe, ou bien vous n’êtes pas autorisé{{GENDER:||e|(e)}} à mettre à jour votre compte externe.',
'login'                      => 'Connexion',
'nav-login-createaccount'    => 'Créer un compte ou se connecter',
'loginprompt'                => "Vous devez activer les témoins (''cookies'') pour vous connecter à {{SITENAME}}.",
'userlogin'                  => 'Créer un compte ou se connecter',
'userloginnocreate'          => 'Connexion',
'logout'                     => 'Se déconnecter',
'userlogout'                 => 'Déconnexion',
'notloggedin'                => 'Non connecté',
'nologin'                    => 'Vous n’êtes pas encore inscrit ? $1.',
'nologinlink'                => 'Créer un compte',
'createaccount'              => 'Créer un compte',
'gotaccount'                 => "Vous avez déjà un compte ? '''$1'''.",
'gotaccountlink'             => 'Connectez-vous',
'createaccountmail'          => 'par courriel',
'createaccountreason'        => 'Motif :',
'badretype'                  => 'Les mots de passe que vous avez saisis ne correspondent pas.',
'userexists'                 => 'Le nom d’utilisateur que vous avez saisi est déjà utilisé.
Veuillez en choisir un autre.',
'loginerror'                 => 'Erreur de connexion',
'createaccounterror'         => 'Impossible de créer le compte : $1',
'nocookiesnew'               => "Le compte utilisateur a été créé, mais vous n’êtes pas connecté{{GENDER:||e|(e)}}. {{SITENAME}} utilise des témoins (''cookies'') pour la connexion mais vous les avez désactivés. Veuillez les activer et vous reconnecter avec le même nom et le même mot de passe.",
'nocookieslogin'             => "{{SITENAME}} utilise des témoins (''cookies'') pour la connexion mais vous les avez désactivés. Veuillez les activer et vous reconnecter.",
'noname'                     => 'Vous n’avez pas saisi un nom d’utilisateur valide.',
'loginsuccesstitle'          => 'Connexion réussie',
'loginsuccess'               => 'Vous êtes maintenant connecté{{GENDER:$1||e|(e)}} à {{SITENAME}} en tant que « $1 ».',
'nosuchuser'                 => 'L’utilisateur « $1 » n’existe pas.
Les noms d’utilisateurs sont sensibles à la casse.
Vérifiez l’orthographe, ou [[Special:UserLogin/signup|créez un nouveau compte]].',
'nosuchusershort'            => 'Il n’y a pas de contributeur avec le nom « <nowiki>$1</nowiki> ». Veuillez vérifier l’orthographe.',
'nouserspecified'            => 'Vous devez saisir un nom d’utilisateur.',
'login-userblocked'          => 'Cet utilisateur est bloqué. Connexion non autorisée.',
'wrongpassword'              => 'Le mot de passe est incorrect. Veuillez essayer à nouveau.',
'wrongpasswordempty'         => 'Vous n’avez pas entré de mot de passe. Veuillez essayer à nouveau.',
'passwordtooshort'           => 'Votre mot de passe doit contenir au moins $1 caractère{{PLURAL:$1||s}}.',
'password-name-match'        => 'Votre mot de passe doit être différent de votre nom d’utilisateur.',
'mailmypassword'             => 'Recevoir un nouveau mot de passe par courriel',
'passwordremindertitle'      => 'Nouveau mot de passe temporaire pour {{SITENAME}}',
'passwordremindertext'       => 'Quelqu’un (probablement vous, ayant l’adresse IP $1) a demandé un nouveau mot de
passe pour {{SITENAME}} ($4 ). Un mot de passe temporaire a été créé pour
l’utilisateur « $2 » et est « $3 ». Si cela était votre intention, vous devrez
vous connecter et choisir un nouveau mot de passe.
Votre mot de passe temporaire expirera dans $5 jour{{PLURAL:$5||s}}.

Si vous n’êtes pas l’auteur de cette demande, ou si vous vous souvenez à présent
de votre ancien mot de passe et que vous ne souhaitez plus en changer, vous
pouvez ignorer ce message et continuer à utiliser votre ancien mot de passe.',
'noemail'                    => 'Aucune adresse de courriel n’a été enregistrée pour l’utilisateur « $1 ».',
'noemailcreate'              => 'Vous devez fournir une adresse de courriel valide',
'passwordsent'               => 'Un nouveau mot de passe a été envoyé à l’adresse de courriel de l’utilisateur « $1 ». Veuillez vous reconnecter après l’avoir reçu.',
'blocked-mailpassword'       => 'Votre adresse IP est bloquée en écriture, la fonction de rappel du mot de passe est donc désactivée pour éviter les abus.',
'eauthentsent'               => 'Un courriel de confirmation a été envoyé à l’adresse indiquée.
Avant qu’un autre courriel ne soit envoyé à ce compte, vous devrez suivre les instructions du courriel et confirmer que le compte est bien le vôtre.',
'throttled-mailpassword'     => 'Un courriel de rappel de votre mot de passe a déjà été envoyé durant {{PLURAL:$1|la dernière heure|les $1 dernières heures}}. Afin d’éviter les abus, un seul courriel de rappel sera envoyé par {{PLURAL:$1|heure|intervalle de $1 heures}}.',
'mailerror'                  => 'Erreur lors de l’envoi du courriel : $1',
'acct_creation_throttle_hit' => 'Quelqu’un utilisant votre adresse IP a créé {{PLURAL:$1|un compte|$1 comptes}} au cours des dernières 24 heures, ce qui constitue la limite autorisée dans cet intervalle de temps.
Par conséquent, la création de compte a été temporairement désactivée pour cette adresse IP.',
'emailauthenticated'         => 'Votre adresse de courriel a été authentifiée le $2 à $3.',
'emailnotauthenticated'      => 'Votre adresse de courriel n’est <strong>pas encore authentifiée</strong>. Aucun courriel ne sera envoyé pour chacune des fonctions suivantes.',
'noemailprefs'               => 'Indiquez une adresse de courriel dans vos préférences pour utiliser ces fonctions.',
'emailconfirmlink'           => 'Confirmez votre adresse de courriel',
'invalidemailaddress'        => 'Cette adresse courriel ne peut pas être acceptée car elle semble avoir un format incorrect.
Entrez une adresse bien formatée ou laissez ce champ vide.',
'accountcreated'             => 'Compte créé',
'accountcreatedtext'         => 'Le compte utilisateur pour $1 a été créé.',
'createaccount-title'        => 'Création d’un compte pour {{SITENAME}}',
'createaccount-text'         => 'Quelqu’un a créé un compte pour votre adresse de courriel sur {{SITENAME}} ($4) intitulé « $2 », avec le mot de passe « $3 ».
Vous devriez ouvrir une session et modifier dès à présent votre mot de passe.

Ignorez ce message si ce compte a été créé par erreur.',
'usernamehasherror'          => 'Le nom d’utilisateur ne peut pas contenir des caractères de hachage',
'login-throttled'            => 'Vous avez tenté un trop grand nombre de connexions dernièrement.
Veuillez attendre avant d’essayer à nouveau.',
'loginlanguagelabel'         => 'Langue : $1',
'suspicious-userlogout'      => 'Votre demande de déconnexion a été refusée car il semble qu’elle a été envoyée par un navigateur cassé ou la mise en cache d’un proxy.',

# Password reset dialog
'resetpass'                 => 'Changer le mot de passe',
'resetpass_announce'        => 'Vous vous êtes enregistré{{GENDER:||e|(e)}} avec un mot de passe temporaire envoyé par courriel. Pour terminer l’enregistrement, vous devez entrer un nouveau mot de passe ici :',
'resetpass_text'            => '<!-- Ajoutez le texte ici -->',
'resetpass_header'          => 'Changer le mot de passe du compte',
'oldpassword'               => 'Ancien mot de passe :',
'newpassword'               => 'Nouveau mot de passe :',
'retypenew'                 => 'Confirmer le nouveau mot de passe :',
'resetpass_submit'          => 'Changer le mot de passe et se connecter',
'resetpass_success'         => 'Votre mot de passe a été changé avec succès ! Connexion en cours…',
'resetpass_forbidden'       => 'Les mots de passe ne peuvent pas être changés',
'resetpass-no-info'         => 'Vous devez être connecté pour avoir accès à cette page.',
'resetpass-submit-loggedin' => 'Changer le mot de passe',
'resetpass-submit-cancel'   => 'Annuler',
'resetpass-wrong-oldpass'   => 'Mot de passe actuel ou temporaire invalide.
Vous avez peut-être déjà changé votre mot de passe ou demandé un nouveau mot de passe temporaire.',
'resetpass-temp-password'   => 'Mot de passe temporaire :',

# Edit page toolbar
'bold_sample'     => 'Texte gras',
'bold_tip'        => 'Texte gras',
'italic_sample'   => 'Texte italique',
'italic_tip'      => 'Texte italique',
'link_sample'     => 'Titre du lien',
'link_tip'        => 'Lien interne',
'extlink_sample'  => 'http://www.example.com titre du lien',
'extlink_tip'     => 'Lien externe (n’oubliez pas le préfixe http://)',
'headline_sample' => 'Texte de sous-titre',
'headline_tip'    => 'Sous-titre niveau 2',
'math_sample'     => 'Entrez votre formule ici',
'math_tip'        => 'Formule mathématique (LaTeX)',
'nowiki_sample'   => 'Entrez le texte non formaté ici',
'nowiki_tip'      => 'Ignorer la syntaxe wiki',
'image_sample'    => 'Exemple.jpg',
'image_tip'       => 'Fichier inséré',
'media_sample'    => 'Exemple.ogg',
'media_tip'       => 'Lien vers un fichier média',
'sig_tip'         => 'Votre signature avec la date',
'hr_tip'          => 'Ligne horizontale (ne pas en abuser)',

# Edit pages
'summary'                          => 'Résumé :',
'subject'                          => 'Sujet / titre :',
'minoredit'                        => 'Modification mineure',
'watchthis'                        => 'Suivre cette page',
'savearticle'                      => 'Publier',
'preview'                          => 'Prévisualisation',
'showpreview'                      => 'Prévisualiser',
'showlivepreview'                  => 'Aperçu rapide',
'showdiff'                         => 'Modifications en cours',
'anoneditwarning'                  => "'''Attention :''' vous n’êtes pas identifié(e). Votre adresse IP sera enregistrée dans l’historique de cette page.",
'anonpreviewwarning'               => "''Vous n’êtes pas identifié. Sauvegarder enregistrera votre adresse IP dans l’historique des modifications de la page.''",
'missingsummary'                   => "'''Rappel :''' vous n’avez pas encore fourni le résumé de votre modification.
Si vous cliquez de nouveau sur le bouton « {{MediaWiki:Savearticle}} », la publication sera faite sans nouvel avertissement.",
'missingcommenttext'               => 'Veuillez entrer un commentaire ci-dessous.',
'missingcommentheader'             => "'''Rappel :''' vous n’avez pas fourni de sujet ou de titre à ce commentaire.
Si vous cliquez de nouveau sur « {{int:Savearticle}} », votre modification sera enregistrée sans titre.",
'summary-preview'                  => 'Aperçu du résumé :',
'subject-preview'                  => 'Prévisualisation du sujet/titre :',
'blockedtitle'                     => 'L’utilisateur est bloqué.',
'blockedtext'                      => "'''Votre compte utilisateur ou votre adresse IP a été bloqué.'''

Le blocage a été effectué par $1.
La raison invoquée est la suivante : ''$2''.

* Début du blocage : $8
* Expiration du blocage : $6
* Compte bloqué : $7.

Vous pouvez contacter $1 ou un autre [[{{MediaWiki:Grouppage-sysop}}|administrateur]] pour en discuter.
Vous ne pouvez utiliser la fonction « Envoyer un courriel à cet utilisateur » que si une adresse de courriel valide est spécifiée dans vos [[Special:Preferences|préférences]] et que cette fonctionnalité n’a pas été bloquée.
Votre adresse IP actuelle est $3 et votre identifiant de blocage est $5.
Veuillez préciser ces indications dans toutes les requêtes que vous ferez.",
'autoblockedtext'                  => "Votre adresse IP a été bloquée automatiquement car elle a été utilisée par un autre utilisateur, lui-même bloqué par $1.
La raison invoquée est :

:''$2''

* Début du blocage : $8
* Expiration du blocage : $6
* Compte bloqué : $7

Vous pouvez contacter $1 ou l’un des autres [[{{MediaWiki:Grouppage-sysop}}|administrateurs]] pour discuter de ce blocage.

Notez que vous ne pourrez utiliser la fonctionnalité d’envoi de courriel que si vous avez une adresse de courriel validée dans vos [[Special:Preferences|préférences]] et que la fonctionnalité n’a pas été désactivée.

Votre adresse IP actuelle est $3, et le numéro de blocage est $5.
Veuillez préciser ces indications dans toutes les requêtes que vous ferez.",
'blockednoreason'                  => 'aucune raison donnée',
'blockedoriginalsource'            => "Le code source de  '''$1''' est indiqué ci-dessous :",
'blockededitsource'                => "Le contenu de '''vos modifications''' apportées à '''$1''' est indiqué ci-dessous :",
'whitelistedittitle'               => 'Connexion nécessaire pour modifier le contenu',
'whitelistedittext'                => 'Vous devez être $1 pour avoir la permission de modifier le contenu.',
'confirmedittext'                  => 'Vous devez confirmer votre adresse de courriel avant de modifier les pages.
Veuillez entrer et valider votre adresse de courriel dans vos [[Special:Preferences|préférences utilisateur]].',
'nosuchsectiontitle'               => 'Impossible de trouver la section',
'nosuchsectiontext'                => 'Vous avez essayé de modifier une section qui n’existe pas.
Elle a peut-être été déplacée ou supprimée depuis que vous avez lu cette page.',
'loginreqtitle'                    => 'Connexion nécessaire',
'loginreqlink'                     => 'connecter',
'loginreqpagetext'                 => 'Vous devez vous $1 pour voir les autres pages.',
'accmailtitle'                     => 'Mot de passe envoyé.',
'accmailtext'                      => "Un mot de passe généré aléatoirement pour [[User talk:$1|$1]] a été envoyé à $2.
Le mot de passe pour ce nouveau compte peut être changé sur la page ''[[Special:ChangePassword|de changement de mot de passe]]'' après s’être connecté.",
'newarticle'                       => '(Nouveau)',
'newarticletext'                   => "Vous avez suivi un lien vers une page qui n’existe pas encore ou qui a été [{{fullurl:Special:Log|type=delete&page={{FULLPAGENAMEE}}}} effacée].
Pour créer cette page, entrez votre texte dans la boîte ci-dessous (vous pouvez consulter [[{{MediaWiki:Helppage}}|la page d’aide]] pour plus d’informations).
Si vous êtes arrivé{{GENDER:||e|(e)}} ici par erreur, cliquez sur le bouton '''retour''' de votre navigateur.",
'anontalkpagetext'                 => "---- ''Vous êtes sur la page de discussion d’un utilisateur anonyme qui n’a pas encore créé de compte ou qui n’en utilise pas. Pour cette raison, nous devons utiliser son adresse IP pour l’identifier. Une adresse IP peut être partagée par plusieurs utilisateurs. Si vous êtes un{{GENDER:||e|}} utilisat{{GENDER:|eur|rice|eur}} anonyme et si vous constatez que des commentaires qui ne vous concernent pas vous ont été adressés, vous pouvez [[Special:UserLogin/signup|créer un compte]] ou [[Special:UserLogin|vous connecter]] afin d’éviter toute confusion future avec d’autres contributeurs anonymes.''",
'noarticletext'                    => 'Il n’y a pour l’instant aucun texte sur cette page.
Vous pouvez [[Special:Search/{{PAGENAME}}|lancer une recherche sur ce titre]] dans les autres pages,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} rechercher dans les opérations liées]
ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} créer cette page]</span>.',
'noarticletext-nopermission'       => 'Il n’y a pour l’instant aucun texte sur cette page.
Vous pouvez [[Special:Search/{{PAGENAME}}|faire une recherche sur ce titre]] dans les autres pages,
ou <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} rechercher dans les journaux associés]</span>.',
'userpage-userdoesnotexist'        => 'Le compte utilisateur « $1 » n’est pas enregistré. Veuillez vérifier que vous voulez créer cette page.',
'userpage-userdoesnotexist-view'   => 'Le compte utilisateur « $1 » n’est pas enregistré.',
'blocked-notice-logextract'        => 'Cet utilisateur est actuellement bloqué.
La dernière entrée du registre des blocages est indiquée ci-dessous à titre d’information :',
'clearyourcache'                   => "'''Note :''' après avoir enregistré vos préférences, vous devrez forcer le rechargement complet du cache de votre navigateur pour voir les changements.
'''Mozilla / Firefox / Konqueror / Safari :''' maintenez la touche ''Majuscule'' (''Shift'') en cliquant sur le bouton ''Actualiser'' (''Reload'') ou pressez ''Maj-Ctrl-R'' (''Maj-Cmd-R'' sur Apple Mac) ;
'''Internet Explorer / Opera :''' maintenez la touche ''Ctrl'' en cliquant sur le bouton ''Actualiser'' ou pressez ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Astuce :''' utilisez le bouton « {{int:showpreview}} » pour tester votre nouvelle feuille CSS avant de l’enregistrer.",
'userjsyoucanpreview'              => "'''Astuce :''' utilisez le bouton « {{int:showpreview}} » pour tester votre nouvelle feuille JavaScript avant de l’enregistrer.",
'usercsspreview'                   => "'''Rappelez-vous que vous n’êtes qu’en train de prévisualiser votre propre feuille CSS.'''
'''Elle n’a pas encore été enregistrée !'''",
'userjspreview'                    => "'''Rappelez-vous que vous êtes en train de visualiser ou de tester votre code JavaScript et qu’il n’a pas encore été enregistré !'''",
'userinvalidcssjstitle'            => "'''Attention :''' il n’existe pas d’habillage « $1 ». Rappelez-vous que les pages personnelles avec extensions .css et .js utilisent des titres en minuscules, par exemple {{ns:user}}:Foo/monobook.css et non {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Mis à jour)',
'note'                             => "'''Note :'''",
'previewnote'                      => "'''Attention, ce texte n’est qu’une prévisualisation et n’a pas encore été publié !'''",
'previewconflict'                  => 'Cette prévisualisation montre le texte de la boîte supérieure de modification tel qu’il apparaîtra si vous choisissez de le publier.',
'session_fail_preview'             => "'''Nous ne pouvons enregistrer votre modification à cause d’une perte d’informations concernant votre session.'''
Veuillez réessayer.
Si cela échoue de nouveau, essayez en vous [[Special:UserLogout|déconnectant]], puis en vous reconnectant.",
'session_fail_preview_html'        => "'''Nous ne pouvons enregistrer votre modification à cause d’une perte d’informations concernant votre session.'''

''Parce que {{SITENAME}} a activé le HTML brut, la prévisualisation a été masquée afin de prévenir les attaques par JavaScript.''

'''Si la tentative de modification était légitime, veuillez réessayer.'''
Si cela échoue de nouveau, [[Special:UserLogout|déconnectez-vous]], puis reconnectez-vous.",
'token_suffix_mismatch'            => "'''Votre modification n’a pas été acceptée car votre navigateur a mal codé les caractères de ponctuation dans l’identifiant de modification.'''
Ce rejet est nécessaire pour empêcher la corruption du texte de la page.
Ce problème se produit parfois lorsque vous utilisez un serveur mandataire anonyme problématique basé sur le web.",
'editing'                          => 'Modification de $1',
'editingsection'                   => 'Modification de $1 (section)',
'editingcomment'                   => 'Modification de $1 (nouvelle section)',
'editconflict'                     => 'Conflit de modification : $1',
'explainconflict'                  => "Cette page a été sauvegardée après que vous avez commencé à la modifier.
La zone de modification supérieure contient le texte tel qu’il est enregistré actuellement dans la base de données.
Vos modifications apparaissent dans la zone de modification inférieure.
Vous allez devoir fusionner vos modifications dans le texte existant.
'''Seul''' le texte de la zone supérieure sera sauvegardé quand vous cliquerez sur « {{MediaWiki:Savearticle}} ».",
'yourtext'                         => 'Votre texte',
'storedversion'                    => 'La version enregistrée',
'nonunicodebrowser'                => "'''Attention : Votre navigateur ne supporte pas l’Unicode.'''
Une solution de rechange a été trouvée pour vous permettre de modifier en toute sûreté une page : les caractères non-ASCII apparaîtront dans votre boîte de modification en tant que codes hexadécimaux. Vous devriez utiliser un navigateur plus récent.",
'editingold'                       => "'''Attention : vous êtes en train de modifier une ancienne version de cette page.
Si vous la publiez, toutes les modifications effectuées depuis cette version seront perdues.'''",
'yourdiff'                         => 'Différences',
'copyrightwarning'                 => "Toutes les contributions à {{SITENAME}} sont considérées comme publiées sous les termes de la $2 (voir $1 pour plus de détails). Si vous ne désirez pas que vos écrits soient modifiés et distribués à volonté, merci de ne pas les soumettre ici.<br />
Vous nous promettez aussi que vous avez écrit ceci vous-même, ou que vous l’avez copié d’une source provenant du domaine public, ou d’une ressource libre. '''N’UTILISEZ PAS DE TRAVAUX SOUS DROIT D’AUTEUR SANS AUTORISATION EXPRESSE !'''",
'copyrightwarning2'                => "Toutes les contributions à {{SITENAME}} peuvent être modifiées ou supprimées par d’autres utilisateurs. Si vous ne désirez pas que vos écrits soient modifiés et distribués à volonté, merci de ne pas les soumettre ici.<br />
Vous nous promettez aussi que vous avez écrit ceci vous-même, ou que vous l’avez copié d’une source provenant du domaine public, ou d’une ressource libre. (voir $1 pour plus de détails).
'''N’UTILISEZ PAS DE TRAVAUX SOUS DROIT D’AUTEUR SANS AUTORISATION EXPRESSE !'''",
'longpagewarning'                  => "'''AVERTISSEMENT : cette page a une longueur de $1 Kio ;
certains navigateurs Web gèrent mal la modification des pages approchant ou dépassant 32 Kio. Peut-être devriez-vous diviser la page en sections plus petites.'''",
'longpageerror'                    => "'''ERREUR : Le texte que vous avez soumis fait $1 Kio, ce qui dépasse la limite fixée à $2 Kio. Le texte ne peut pas être sauvegardé.'''",
'readonlywarning'                  => "'''AVERTISSEMENT : La base de données a été verrouillée pour des opérations de maintenance. Vous ne pouvez donc pas publier vos modifications pour l’instant.'''
Vous pouvez copier le texte dans un fichier texte et le conserver pour plus tard.

L’administrateur ayant verrouillé la base de données a donné l’explication suivante : $1",
'protectedpagewarning'             => "'''AVERTISSEMENT : cette page est protégée. Seuls les utilisateurs ayant le statut d’administrateur peuvent la modifier.''' La dernière entrée du journal est affichée ci-dessous pour référence :",
'semiprotectedpagewarning'         => "'''Note :''' Cette page a été protégée de telle façon que seuls les contributeurs enregistrés puissent la modifier. La dernière entrée du journal est affichée ci-dessous pour référence :",
'cascadeprotectedwarning'          => "'''ATTENTION :''' Cette page a été protégée de manière à ce que seuls les administrateurs puissent l’éditer. Cette protection est héritée par son inclusion par {{PLURAL:$1|la page protégée suivante, qui a|les pages protégées suivantes, qui ont}} la « protection en cascade » activée :",
'titleprotectedwarning'            => "'''ATTENTION : Cette page a été protégée de telle manière que des [[Special:ListGroupRights|droits spécifiques]] sont requis pour pouvoir la créer.''' La dernière entrée du journal est affichée ci-dessous pour référence :",
'templatesused'                    => '{{PLURAL:$1|Modèle utilisé|Modèles utilisés}} par cette page :',
'templatesusedpreview'             => '{{PLURAL:$1|Modèle utilisé|Modèles utilisés}} dans cette prévisualisation :',
'templatesusedsection'             => '{{PLURAL:$1|Modèle utilisé|Modèles utilisés}} dans cette section :',
'template-protected'               => '(protégé)',
'template-semiprotected'           => '(semi-protégé)',
'hiddencategories'                 => '{{PLURAL:$1|Catégorie cachée|Catégories cachées}} dont cette page fait partie :',
'edittools'                        => '<!-- Tout texte entré ici sera affiché sous les boîtes de modification ou les formulaires de téléversement de fichier. -->',
'nocreatetitle'                    => 'Création de page limitée',
'nocreatetext'                     => '{{SITENAME}} a restreint la possibilité de créer de nouvelles pages.
Vous pouvez revenir en arrière et modifier une page existante, ou bien [[Special:UserLogin|vous connecter ou créer un compte]].',
'nocreate-loggedin'                => 'Vous n’avez pas la permission de créer de nouvelles pages.',
'sectioneditnotsupported-title'    => 'Modification de section non prise en charge',
'sectioneditnotsupported-text'     => 'La modification d’une section n’est pas supportée dans cette page de modification.',
'permissionserrors'                => 'Erreur de permissions',
'permissionserrorstext'            => 'Vous n’avez pas la permission d’effectuer l’opération demandée pour {{PLURAL:$1|la raison suivante|les raisons suivantes}} :',
'permissionserrorstext-withaction' => 'Vous n’êtes pas autorisé{{GENDER:||e|(e)}} à $2, pour {{PLURAL:$1|la raison suivante|les raisons suivantes}} :',
'recreate-moveddeleted-warn'       => "'''Attention : vous êtes en train de recréer une page qui a été précédemment supprimée.'''

Assurez-vous qu’il est pertinent de poursuivre les modifications sur cette page. Le journal des suppressions et des déplacements est affiché ci-dessous :",
'moveddeleted-notice'              => 'Cette page a été supprimée. Le journal des suppressions et des déplacements est affiché ci-dessous pour référence.',
'log-fulllog'                      => 'Voir le journal complet',
'edit-hook-aborted'                => 'Échec de la modification par une extension.
Cause inconnue',
'edit-gone-missing'                => 'N’a pas pu mettre à jour la page.
Il semble qu’elle ait été supprimée.',
'edit-conflict'                    => 'Conflit de modification.',
'edit-no-change'                   => 'Votre modification a été ignorée car aucun changement n’a été fait au texte.',
'edit-already-exists'              => 'La nouvelle page n’a pas pu être créée.
Elle existe déjà.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Attention : cette page contient de trop nombreux appels à des fonctions coûteuses du parseur.

Il devrait y avoir moins de $2 appel{{PLURAL:$2||s}}, alors qu’il y en a maintenant $1.',
'expensive-parserfunction-category'       => 'Pages avec trop d’appels dispendieux de fonctions parseurs',
'post-expand-template-inclusion-warning'  => 'Attention : Cette page contient trop d’inclusions de modèles. Certaines inclusions ne seront pas effectuées.',
'post-expand-template-inclusion-category' => 'Pages contenant trop d’inclusions de modèles',
'post-expand-template-argument-warning'   => 'Attention : Cette page contient au moins un paramètre de modèle dont l’inclusion est rendue impossible. Après extension, celui-ci aurait produit un résultat trop long, il n’a donc pas été inclus.',
'post-expand-template-argument-category'  => 'Pages contenant des paramètres de modèle non évalués',
'parser-template-loop-warning'            => 'Modèle en boucle détecté : [[$1]]',
'parser-template-recursion-depth-warning' => 'Limite de profondeur des appels de modèles dépassée ($1)',
'language-converter-depth-warning'        => 'Limite de profondeur du convertisseur de langue dépassée ($1)',

# "Undo" feature
'undo-success' => 'Cette modification va être défaite. Veuillez vérifier les modifications ci-dessous, puis publier si c’est bien ce que vous voulez faire.',
'undo-failure' => 'Cette modification ne peut pas être défaite : cela entrerait en conflit avec les modifications intermédiaires.',
'undo-norev'   => 'La modification n’a pas pu être défaite parce qu’elle est inexistante ou qu’elle a été supprimée.',
'undo-summary' => 'Annulation des modifications $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|discussion]])',

# Account creation failure
'cantcreateaccounttitle' => 'Vous ne pouvez pas créer de compte.',
'cantcreateaccount-text' => "La création de compte depuis cette adresse IP (<b>$1</b>) a été bloquée par [[User:$3|$3]].

La raison donnée était ''$2''.",

# History pages
'viewpagelogs'           => 'Voir les opérations sur cette page',
'nohistory'              => 'Il n’existe pas d’historique pour cette page.',
'currentrev'             => 'Version actuelle',
'currentrev-asof'        => 'Version actuelle en date du $1',
'revisionasof'           => 'Version du $1',
'revision-info'          => 'Version du $1 par $2',
'previousrevision'       => '← Version précédente',
'nextrevision'           => 'Version suivante →',
'currentrevisionlink'    => 'Voir la version courante',
'cur'                    => 'actu',
'next'                   => 'suivant',
'last'                   => 'diff',
'page_first'             => 'première',
'page_last'              => 'dernière',
'histlegend'             => 'Légende : ({{MediaWiki:Cur}}) = différence avec la version actuelle, ({{MediaWiki:Last}}) = différence avec la version précédente, <b>m</b> = modification mineure',
'history-fieldset-title' => 'Naviguer dans l’historique',
'history-show-deleted'   => 'Supprimés seulement',
'histfirst'              => 'toute première',
'histlast'               => 'toute dernière',
'historysize'            => '($1 octet{{PLURAL:$1||s}})',
'historyempty'           => '(vide)',

# Revision feed
'history-feed-title'          => 'Historique des versions',
'history-feed-description'    => 'Historique pour cette page sur le wiki',
'history-feed-item-nocomment' => '$1 le $2',
'history-feed-empty'          => 'La page demandée n’existe pas.
Elle a peut-être été effacée ou renommée.
Essayez de [[Special:Search|rechercher sur le wiki]] pour trouver des pages en rapport.',

# Revision deletion
'rev-deleted-comment'         => '(commentaire supprimé)',
'rev-deleted-user'            => '(nom d’utilisateur supprimé)',
'rev-deleted-event'           => '(entrée supprimée)',
'rev-deleted-user-contribs'   => '[nom d’utilisateur ou adresse IP supprimée - modification cachée sur les contributions]',
'rev-deleted-text-permission' => "Cette version de la page a été '''effacée'''.
Des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} journal des effacements].",
'rev-deleted-text-unhide'     => "Cette version de la page a été '''effacée'''.
Des détails sont disponibles dans [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} le journal des effacements].
Comme administrateur, vous pouvez toujours [$1 voir cette version] si vous le voulez.",
'rev-suppressed-text-unhide'  => "Cette version de la page a été '''supprimée'''.
Des détails sont disponibles dans [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} le journal des suppression].
Comme administrateur, vous pouvez toujours [$1 voir cette version] si vous le voulez.",
'rev-deleted-text-view'       => "Cette version de la page a été '''effacée'''.
En tant qu’administrateur, vous pouvez la visualiser ; des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} journal des effacements].",
'rev-suppressed-text-view'    => "Cette version de la page a été '''supprimée'''.
En tant qu’administrateur, vous pouvez la visualiser ; des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} journal des suppressions].",
'rev-deleted-no-diff'         => "Vous ne pouvez pas voir ce diff parce qu’une des versions a été '''effacée'''.
Des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} journal des effacements].",
'rev-suppressed-no-diff'      => "Vous ne pouvez pas voir cette différence car une des révisions a été '''supprimée'''.",
'rev-deleted-unhide-diff'     => "Une des révisions de cette différence a été '''effacée'''.
Des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} journal des effacements].
En tant qu’administrateur vous pouvez toujours [$1 voir cette différence] si vous le voulez.",
'rev-suppressed-unhide-diff'  => "L’une des révisions de ce diff a été '''supprimée'''.
Des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} journal des suppressions].
En tant qu’administrateur, vous pouvez toujours [$1 voir ce diff] si vous souhaitez poursuivre.",
'rev-deleted-diff-view'       => "Une des révisions de ce diff a été '''supprimée'''.
En tant qu’administrateur, vous pouvez voir ce diff ; des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} journal des suppressions].",
'rev-suppressed-diff-view'    => "Une des révisions de ce diff a été '''effacée'''.
En tant qu’administrateur, vous pouvez voir ce diff ; des détails sont disponibles dans le [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} journal des effacements].",
'rev-delundel'                => 'afficher/masquer',
'rev-showdeleted'             => 'afficher',
'revisiondelete'              => 'Supprimer ou restaurer des événements',
'revdelete-nooldid-title'     => 'Version cible non valide',
'revdelete-nooldid-text'      => 'Vous n’avez pas précisé la version cible de cette fonction, elle n’existe pas, ou il s’agit de la version actuelle.',
'revdelete-nologtype-title'   => 'Aucun type de journal spécifié',
'revdelete-nologtype-text'    => 'Vous n’avez pas spécifié un type de journal sur lequel cette action doit être réalisée.',
'revdelete-nologid-title'     => 'Entrée du journal invalide',
'revdelete-nologid-text'      => 'Vous n’avez pas spécifié une entrée du journal sur laquelle cette action doit être effectuée, ou alors l’événement spécifié n’existe pas.',
'revdelete-no-file'           => 'Le fichier spécifié n’existe pas.',
'revdelete-show-file-confirm' => 'Êtes-vous sûr de vouloir voir la révision supprimée du fichier « <nowiki>$1</nowiki> » datant du $2 à $3?',
'revdelete-show-file-submit'  => 'Oui',
'revdelete-selected'          => "'''{{PLURAL:$2|Version sélectionnée|Versions sélectionnées}} de '''[[:$1]]''' :'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Événement d’historique sélectionné|Événements d’historique sélectionnés}} :'''",
'revdelete-text'              => "'''Les versions et événements supprimés seront encore présents dans l’historique de la page et dans les journaux, mais leur contenu textuel sera inaccessible au public.'''
Les autres administrateurs de {{SITENAME}} pourront toujours accéder au contenu caché et le restaurer à travers cette même interface, à moins que des restrictions supplémentaires ne soient mises en place.",
'revdelete-confirm'           => 'Confirmez que vous voulez effectuer cette action, que vous en comprenez les conséquences, et que vous le faites en accord avec [[{{MediaWiki:Policy-url}}|les règles]].',
'revdelete-suppress-text'     => "La suppression ne doit être utilisée '''que''' dans les cas suivants :
* Informations personnelles inappropriées
*: ''adresse, numéro de téléphone, numéro de sécurité sociale, …''",
'revdelete-legend'            => 'Mettre en place des restrictions de visibilité :',
'revdelete-hide-text'         => 'Masquer le texte de la version',
'revdelete-hide-image'        => 'Masquer le contenu du fichier',
'revdelete-hide-name'         => 'Masquer l’action et la cible',
'revdelete-hide-comment'      => 'Masquer le commentaire de modification',
'revdelete-hide-user'         => 'Masquer le pseudo ou l’adresse IP du contributeur.',
'revdelete-hide-restricted'   => 'Supprimer ces données aux administrateurs ainsi qu’aux autres',
'revdelete-radio-same'        => '(ne pas changer)',
'revdelete-radio-set'         => 'Oui',
'revdelete-radio-unset'       => 'Non',
'revdelete-suppress'          => 'Cacher les données également pour les administrateurs',
'revdelete-unsuppress'        => 'Enlever les restrictions sur les versions restaurées',
'revdelete-log'               => 'Motif :',
'revdelete-submit'            => 'Appliquer {{PLURAL:$1|à la révision sélectionnée|aux révisions sélectionnées}}',
'revdelete-logentry'          => 'a modifié la visibilité de la version de [[$1]]',
'logdelete-logentry'          => 'a modifié la visibilité de l’événement de [[$1]]',
'revdelete-success'           => "'''Visibilité des versions mise à jour avec succès.'''",
'revdelete-failure'           => "'''La visibilité de la version n’a pas pu être mise à jour :'''
$1",
'logdelete-success'           => "'''Visibilité du journal paramétrée avec succès.'''",
'logdelete-failure'           => "'''La visibilité du journal n’a pas pu être définie :'''
$1",
'revdel-restore'              => 'modifier la visibilité',
'revdel-restore-deleted'      => 'révisions supprimées',
'revdel-restore-visible'      => 'révisions visibles',
'pagehist'                    => 'Historique de la page',
'deletedhist'                 => 'Historique supprimé',
'revdelete-content'           => 'contenu',
'revdelete-summary'           => 'résumé de modification',
'revdelete-uname'             => 'nom d’utilisateur',
'revdelete-restricted'        => 'a appliqué les restrictions aux administrateurs',
'revdelete-unrestricted'      => 'restrictions retirées pour les administrateurs',
'revdelete-hid'               => 'a caché $1',
'revdelete-unhid'             => 'a démasqué $1',
'revdelete-log-message'       => '$1 pour $2 version{{PLURAL:$2||s}}',
'logdelete-log-message'       => '$1 sur $2 évènement{{PLURAL:$2||s}}',
'revdelete-hide-current'      => 'Erreur lors de la suppression de l’élément daté du $1 à $2 : il est la révision courante.
Il ne peut pas être supprimé.',
'revdelete-show-no-access'    => 'Erreur lors de l’affichage de l’élément daté du $1 à $2 : il est marqué comme « restreint ».
Vous n’y avez pas accès.',
'revdelete-modify-no-access'  => 'Erreur lors de la modification de l’élément daté du $1 à $2 : il est marqué comme « restreint ».
Vous n’y avez pas accès.',
'revdelete-modify-missing'    => 'Erreur lors de la modification de l’élément avec l’ID $1 : il est manquant dans la base de données !',
'revdelete-no-change'         => "'''Attention :''' L’élément daté du $1 à $2 a déjà les paramètres de visibilité demandés.",
'revdelete-concurrent-change' => 'Erreur lors de la modification de l’élément daté du $1 à $2 : son statut a été changé par quelqu’un d’autre pendant que vous le modifiez.
Vérifiez les journaux.',
'revdelete-only-restricted'   => 'Erreur lors de la suppression de l’entrée datée du $1 à $2 : vous ne pouvez pas supprimer ces éléments aux administrateurs sans également sélectionner des autres options de suppression.',
'revdelete-reason-dropdown'   => '* Raisons courantes de suppression
** Violation des droits d’auteurs
** Renseignements personnels inappropriés',
'revdelete-otherreason'       => 'Autre raison / raison supplémentaire :',
'revdelete-reasonotherlist'   => 'Autre raison',
'revdelete-edit-reasonlist'   => 'Modifier les motifs fréquents de suppression',
'revdelete-offender'          => 'Auteur de la révision :',

# Suppression log
'suppressionlog'     => 'Journal des suppressions',
'suppressionlogtext' => 'Voici la liste des suppressions et des blocages qui portent sur du contenu caché aux administrateurs.
Voir la [[Special:IPBlockList|liste des adresses IP et utilisateurs bloqués]] pour la liste des bannissements et des blocages actuellement opérationnels.',

# Revision move
'moverevlogentry'              => 'a déplacé {{PLURAL:$3|une révision|$3 révisions}} de $1 à $2',
'revisionmove'                 => 'Déplacer des révisions depuis « $1 »',
'revmove-explain'              => "Les révisions suivantes seront déplacés de $1 vers la page cible spécifiée. Si la cible n'existe pas, elle sera créée, sinon ces révisions seront fusionnées avec l'historique de la page.",
'revmove-legend'               => 'Entrez la page cible et la raison',
'revmove-submit'               => 'Déplacer les révisions vers la page sélectionnée',
'revisionmoveselectedversions' => 'Déplacer les révisions sélectionnées',
'revmove-reasonfield'          => 'Motif :',
'revmove-titlefield'           => 'Page cible :',
'revmove-badparam-title'       => 'Mauvais paramètres',
'revmove-badparam'             => 'Votre requête contient des paramètres insuffisants ou illégaux. Veuillez cliquer sur « précédent » et essayez à nouveau.',
'revmove-norevisions-title'    => 'Révision cible invalide',
'revmove-norevisions'          => "Vous n'avez pas spécifié un ou plusieurs révisions cibles pour effectuer cette fonctionnalité ou la révision spécifiée n'existe pas.",
'revmove-nullmove-title'       => 'Mauvais titre',
'revmove-nullmove'             => 'Les pages source et cible sont identiques. Veuillez cliquer sur « précédent » et entrez un nom de page différent de « $1 ».',
'revmove-success-existing'     => '{{PLURAL:$1|Une révision de [[$2]] a été déplacée|$1 révisions de [[$2]] ont été déplacées}} vers la page existante [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Une révision de [[$2]] a été déplacée|$1 révisions de [[$2]] ont été déplacées}} vers la page [[$3]] nouvellement créée.',

# History merging
'mergehistory'                     => 'Fusionner les historiques des pages',
'mergehistory-header'              => 'Cette page vous permet de fusionner des versions de l’historique d’une page d’origine vers une nouvelle page.
Assurez-vous que cette opération conservera la continuité de l’historique de la page.',
'mergehistory-box'                 => 'Fusionner les versions de deux pages :',
'mergehistory-from'                => 'Page d’origine :',
'mergehistory-into'                => 'Page de destination :',
'mergehistory-list'                => 'Historique des modifications fusionnable',
'mergehistory-merge'               => 'Les versions suivantes de [[:$1]] peuvent être fusionnées avec [[:$2]]. Utilisez la colonne de boutons radio pour fusionner uniquement les versions créées du début jusqu’à la date indiquée. Notez bien que l’utilisation des liens de navigation réinitialisera cette colonne.',
'mergehistory-go'                  => 'Voir les modifications qui peuvent être fusionnées',
'mergehistory-submit'              => 'Fusionner les versions',
'mergehistory-empty'               => 'Aucune version ne peut être fusionnée.',
'mergehistory-success'             => '$3 version{{PLURAL:$3||s}} de [[:$1]] fusionnée{{PLURAL:$3||s}} dans [[:$2]].',
'mergehistory-fail'                => 'Impossible de procéder à la fusion des historiques. Resélectionner la page ainsi que les paramètres de date.',
'mergehistory-no-source'           => 'La page d’origine $1 n’existe pas.',
'mergehistory-no-destination'      => 'La page de destination $1 n’existe pas.',
'mergehistory-invalid-source'      => 'La page d’origine doit avoir un titre valide.',
'mergehistory-invalid-destination' => 'La page de destination doit avoir un titre valide.',
'mergehistory-autocomment'         => '[[:$1]] fusionnée avec [[:$2]]',
'mergehistory-comment'             => '[[:$1]] fusionnée avec [[:$2]] : $3',
'mergehistory-same-destination'    => 'Les pages d’origine et de destination ne peuvent pas être la même',
'mergehistory-reason'              => 'Motif :',

# Merge log
'mergelog'           => 'Journal des fusions',
'pagemerge-logentry' => '[[$1]] fusionnée avec [[$2]] (versions jusqu’au $3)',
'revertmerge'        => 'Séparer',
'mergelogpagetext'   => 'Voici la liste des fusions de l’historique d’une page dans celui d’une autre les plus récentes.',

# Diffs
'history-title'            => 'Historique des versions de « $1 »',
'difference'               => '(Différences entre les versions)',
'lineno'                   => 'Ligne $1 :',
'compareselectedversions'  => 'Comparer les versions sélectionnées',
'showhideselectedversions' => 'Afficher/masquer les versions sélectionnées',
'editundo'                 => 'défaire',
'diff-multi'               => '({{PLURAL:$1|Une version intermédiaire masquée|$1 versions intermédiaires masquées}})',

# Search results
'searchresults'                    => 'Résultats de la recherche',
'searchresults-title'              => 'Résultats de recherche pour « $1 »',
'searchresulttext'                 => 'Pour plus d’informations sur la recherche dans {{SITENAME}}, voir [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Vous avez recherché « '''[[:$1]]''' » ([[Special:Prefixindex/$1|toutes les pages commençant par « $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|toutes les pages qui ont un lien vers « $1 »]])",
'searchsubtitleinvalid'            => "Vous avez recherché « '''$1''' »",
'toomanymatches'                   => 'Un trop grand nombre d’occurrences a été renvoyé, veuillez soumettre une requête différente.',
'titlematches'                     => 'Correspondances dans les titres des pages',
'notitlematches'                   => 'Aucun titre de page ne correspond à la recherche.',
'textmatches'                      => 'Correspondances dans le texte des pages',
'notextmatches'                    => 'Aucun texte de page ne correspond à la recherche.',
'prevn'                            => '{{PLURAL:$1|précédente|$1 précédentes}}',
'nextn'                            => '{{PLURAL:$1|suivante|$1 suivantes}}',
'prevn-title'                      => '$1 {{PLURAL:$1|résultat précédent|résultats précédents}}',
'nextn-title'                      => '$1 {{PLURAL:$1|résultat suivant|résultats suivants}}',
'shown-title'                      => 'Afficher $1 résultat{{PLURAL:$1||s}} par page',
'viewprevnext'                     => 'Voir ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Options de recherche',
'searchmenu-exists'                => "'''Il existe une page nommée « [[:$1]] » sur ce wiki'''",
'searchmenu-new'                   => "'''Créer la page « [[:$1|$1]] » sur ce wiki !'''",
'searchhelp-url'                   => 'Help:Accueil',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Rechercher les pages commençant par ce préfixe]]',
'searchprofile-articles'           => 'Pages de contenu',
'searchprofile-project'            => 'Pages d’aide et du projet',
'searchprofile-images'             => 'Multimédia',
'searchprofile-everything'         => 'Tout',
'searchprofile-advanced'           => 'Recherche avancée',
'searchprofile-articles-tooltip'   => 'Rechercher dans $1',
'searchprofile-project-tooltip'    => 'Rechercher dans $1',
'searchprofile-images-tooltip'     => 'Rechercher des fichiers multimédias',
'searchprofile-everything-tooltip' => 'Rechercher dans tout le site (y compris dans les pages de discussion)',
'searchprofile-advanced-tooltip'   => 'Choisir les espaces de noms pour la recherche',
'search-result-size'               => '$1 ($2 mot{{PLURAL:$2||s}})',
'search-result-category-size'      => '$1 membre{{PLURAL:$1||s}} ($2 sous-catégorie{{PLURAL:$2||s}}, $3 fichier{{PLURAL:$3||s}})',
'search-result-score'              => 'Pertinence : $1%',
'search-redirect'                  => '(redirection depuis $1)',
'search-section'                   => '(section $1)',
'search-suggest'                   => 'Essayez avec cette orthographe : $1',
'search-interwiki-caption'         => 'Projets frères',
'search-interwiki-default'         => 'Résultats sur $1 :',
'search-interwiki-more'            => '(plus)',
'search-mwsuggest-enabled'         => 'avec suggestions',
'search-mwsuggest-disabled'        => 'sans suggestions',
'search-relatedarticle'            => 'Relaté',
'mwsuggest-disable'                => 'Désactiver les suggestions AJAX',
'searcheverything-enable'          => 'Rechercher dans tous les espaces de noms',
'searchrelated'                    => 'relaté',
'searchall'                        => 'tout',
'showingresults'                   => 'Affichage de <b>$1</b> résultat{{PLURAL:$1||s}} à partir du #<b>$2</b>.',
'showingresultsnum'                => 'Affichage de <b>$3</b> résultat{{PLURAL:$3||s}} à partir du #<b>$2</b>.',
'showingresultsheader'             => "{{PLURAL:$5|Résultat '''$1'''|Résultats '''$1–$2'''}} de '''$3''' pour '''$4'''",
'nonefound'                        => "'''Note''' : seuls certains espaces de noms sont fouillés par défaut.
Essayez en utilisant le préfixe ''all:'' pour rechercher dans tout le contenu (y compris les pages de discussion, les modèles, etc.) ou bien utilisez l’espace de noms souhaité comme préfixe.",
'search-nonefound'                 => 'Il n’y a aucun résultat correspondant à la requête.',
'powersearch'                      => 'Rechercher',
'powersearch-legend'               => 'Recherche avancée',
'powersearch-ns'                   => 'Rechercher dans les espaces de noms :',
'powersearch-redir'                => 'Afficher les redirections',
'powersearch-field'                => 'Rechercher',
'powersearch-togglelabel'          => 'Cocher :',
'powersearch-toggleall'            => 'Tout',
'powersearch-togglenone'           => 'Aucune',
'search-external'                  => 'Recherche externe',
'searchdisabled'                   => 'La recherche sur {{SITENAME}} est désactivée. En attendant la réactivation, vous pouvez effectuer une recherche via Google. Attention, leur indexation du contenu de {{SITENAME}} peut ne pas être à jour.',

# Quickbar
'qbsettings'               => 'Barre d’outils',
'qbsettings-none'          => 'Aucune',
'qbsettings-fixedleft'     => 'Gauche',
'qbsettings-fixedright'    => 'Droite',
'qbsettings-floatingleft'  => 'Flottante à gauche',
'qbsettings-floatingright' => 'Flottante à droite',

# Preferences page
'preferences'                   => 'Préférences',
'mypreferences'                 => 'Préférences',
'prefs-edits'                   => 'Nombre de modifications :',
'prefsnologin'                  => 'Non connecté',
'prefsnologintext'              => 'Vous devez être <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} connecté]</span> pour modifier vos préférences d’utilisateur.',
'changepassword'                => 'Modification du mot de passe',
'prefs-skin'                    => 'Habillage',
'skin-preview'                  => 'Prévisualiser',
'prefs-math'                    => 'Rendu des maths',
'datedefault'                   => 'Aucune préférence',
'prefs-datetime'                => 'Date et heure',
'prefs-personal'                => 'Informations personnelles',
'prefs-rc'                      => 'Modifications récentes',
'prefs-watchlist'               => 'Liste de suivi',
'prefs-watchlist-days'          => 'Nombre de jours à afficher dans la liste de suivi :',
'prefs-watchlist-days-max'      => '(maximum 7 jours)',
'prefs-watchlist-edits'         => 'Nombre de modifications à afficher dans la liste de suivi étendue :',
'prefs-watchlist-edits-max'     => '(nombre maximum : 1000)',
'prefs-watchlist-token'         => 'Jeton pour la liste de suivi :',
'prefs-misc'                    => 'Préférences diverses',
'prefs-resetpass'               => 'Modifier le mot de passe',
'prefs-email'                   => 'Options des courriels',
'prefs-rendering'               => 'Apparence',
'saveprefs'                     => 'Enregistrer les préférences',
'resetprefs'                    => 'Rétablir les préférences',
'restoreprefs'                  => 'Restaurer toutes les valeurs par défaut',
'prefs-editing'                 => 'Fenêtre de modification',
'prefs-edit-boxsize'            => 'Taille de la fenêtre de modification.',
'rows'                          => 'Rangées :',
'columns'                       => 'Colonnes :',
'searchresultshead'             => 'Recherches',
'resultsperpage'                => 'Nombre de réponses par page :',
'contextlines'                  => 'Nombre de lignes par réponse :',
'contextchars'                  => 'Nombre de caractères de contexte par ligne :',
'stub-threshold'                => 'Limite supérieure pour les <a href="#" class="stub">liens vers les ébauches</a> (octets) :',
'recentchangesdays'             => 'Nombre de jours à afficher dans les modifications récentes :',
'recentchangesdays-max'         => '(maximum $1 jour{{PLURAL:$1||s}})',
'recentchangescount'            => 'Nombre de modifications à afficher par défaut :',
'prefs-help-recentchangescount' => 'Ceci inclut les modifications récentes, les pages d’historiques et les journaux.',
'prefs-help-watchlist-token'    => 'En remplissant ceci avec une valeur secrète générera un flux RSS pour votre liste de suivi.
Toute personne connaissant ce jeton pourra lire votre liste de suivi, choisissez donc une valeur sécurisée.
Voici une valeur générée aléatoirement que vous pouvez utiliser : $1',
'savedprefs'                    => 'Les préférences ont été sauvegardées.',
'timezonelegend'                => 'Fuseau horaire :',
'localtime'                     => 'Heure locale :',
'timezoneuseserverdefault'      => 'Utiliser la valeur du serveur',
'timezoneuseoffset'             => 'Autre (spécifier le décalage)',
'timezoneoffset'                => 'Décalage horaire¹ :',
'servertime'                    => 'Heure du serveur :',
'guesstimezone'                 => 'Utiliser la valeur du navigateur',
'timezoneregion-africa'         => 'Afrique',
'timezoneregion-america'        => 'Amérique',
'timezoneregion-antarctica'     => 'Antarctique',
'timezoneregion-arctic'         => 'Arctique',
'timezoneregion-asia'           => 'Asie',
'timezoneregion-atlantic'       => 'Océan atlantique',
'timezoneregion-australia'      => 'Australie',
'timezoneregion-europe'         => 'Europe',
'timezoneregion-indian'         => 'Océan indien',
'timezoneregion-pacific'        => 'Océan pacifique',
'allowemail'                    => 'Autoriser l’envoi de courriels venant d’autres utilisateurs',
'prefs-searchoptions'           => 'Options de recherche',
'prefs-namespaces'              => 'Espaces de noms',
'defaultns'                     => 'Rechercher par défaut dans ces espaces de noms :',
'default'                       => 'défaut',
'prefs-files'                   => 'Fichiers',
'prefs-custom-css'              => 'CSS personnalisé',
'prefs-custom-js'               => 'JavaScript personnalisé',
'prefs-common-css-js'           => 'JavaScript et CSS partagé pour tous les habillages :',
'prefs-reset-intro'             => 'Vous pouvez utiliser cette page pour restaurer vos préférences aux valeurs par défaut du site. Ceci ne peut pas être défait.',
'prefs-emailconfirm-label'      => 'Confirmation du courriel :',
'prefs-textboxsize'             => 'Taille de la fenêtre de modification',
'youremail'                     => 'Adresse électronique :',
'username'                      => 'Nom d’utilisateur :',
'uid'                           => 'Numéro d’utilisateur :',
'prefs-memberingroups'          => 'Membre {{PLURAL:$1|du groupe|des groupes}} :',
'prefs-registration'            => 'Date d’inscription :',
'yourrealname'                  => 'Nom réel :',
'yourlanguage'                  => 'Langue de l’interface :',
'yourvariant'                   => 'Variante :',
'yournick'                      => 'Signature pour les discussions :',
'prefs-help-signature'          => 'Les commentaires sur les pages de discussion doivent être signés avec « <nowiki>~~~~</nowiki> », qui sera converti par votre signature et un horodatage.',
'badsig'                        => 'Signature brute incorrecte.
Vérifiez vos balises HTML.',
'badsiglength'                  => 'Votre signature est trop longue.
Elle ne doit pas dépasser $1 caractère{{PLURAL:$1||s}}.',
'yourgender'                    => 'Sexe :',
'gender-unknown'                => 'Non renseigné',
'gender-male'                   => 'Masculin',
'gender-female'                 => 'Féminin',
'prefs-help-gender'             => 'Facultatif : utilisé pour accorder en genre les messages de l’interface. Cette information sera publique.',
'email'                         => 'Courriel',
'prefs-help-realname'           => 'Facultatif : si vous le spécifiez, il sera utilisé pour vous attribuer vos contributions.',
'prefs-help-email'              => 'Facultatif : indiquer votre adresse de courriel permet de vous envoyer un nouveau mot de passe si vous oubliez le vôtre.
Vous pouvez également décider de laisser les autres vous contacter via votre page de discussion, sans avoir besoin de révéler votre identité.',
'prefs-help-email-required'     => 'Une adresse de courriel est requise.',
'prefs-info'                    => 'Information de base',
'prefs-i18n'                    => 'Internationalisation',
'prefs-signature'               => 'Signature',
'prefs-dateformat'              => 'Format des dates',
'prefs-timeoffset'              => 'Décalage horaire',
'prefs-advancedediting'         => 'Options avancées',
'prefs-advancedrc'              => 'Options avancées',
'prefs-advancedrendering'       => 'Options avancées',
'prefs-advancedsearchoptions'   => 'Options avancées',
'prefs-advancedwatchlist'       => 'Options avancées',
'prefs-displayrc'               => 'Options d’affichage',
'prefs-displaysearchoptions'    => 'Options d’affichage',
'prefs-displaywatchlist'        => 'Options d’affichage',
'prefs-diffs'                   => 'Différences',

# User rights
'userrights'                   => 'Gestion des droits des utilisateurs',
'userrights-lookup-user'       => 'Gestion des groupes d’utilisateurs',
'userrights-user-editname'     => 'Entrez un nom d’utilisateur :',
'editusergroup'                => 'Modification des groupes d’utilisateurs',
'editinguser'                  => "Modification des droits de l’utilisateur '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Modifier les groupes de l’utilisateur',
'saveusergroups'               => 'Sauvegarder les groupes d’utilisateurs',
'userrights-groupsmember'      => 'Membre de :',
'userrights-groupsmember-auto' => 'Membre implicite de :',
'userrights-groups-help'       => 'Vous pouvez modifier les groupes auxquels appartient cet utilisateur.
* Une case cochée signifie que l’utilisateur se trouve dans ce groupe.
* Une case non cochée signifie qu’il ne s’y trouve pas.
* Un astérisque (*) indique que vous ne pouvez pas retirer ce groupe une fois que vous l’avez ajouté.',
'userrights-reason'            => 'Motif :',
'userrights-no-interwiki'      => 'Vous n’avez pas la permission de modifier des droits d’utilisateurs sur d’autres wikis.',
'userrights-nodatabase'        => 'La base de donnée « $1 » n’existe pas ou n’est pas locale.',
'userrights-nologin'           => 'Vous devez vous [[Special:UserLogin|connecter]] avec un compte d’administrateur pour modifier des droits d’utilisateur.',
'userrights-notallowed'        => 'Votre compte n’a pas la permission de modifier des droits d’utilisateur.',
'userrights-changeable-col'    => 'Les groupes que vous pouvez modifier',
'userrights-unchangeable-col'  => 'Les groupes que vous ne pouvez pas modifier',

# Groups
'group'               => 'Groupe :',
'group-user'          => 'Utilisateurs',
'group-autoconfirmed' => 'Utilisateurs enregistrés',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administrateurs',
'group-bureaucrat'    => 'Bureaucrates',
'group-suppress'      => 'Superviseurs',
'group-all'           => 'Tous',

'group-user-member'          => 'utilisateur',
'group-autoconfirmed-member' => 'utilisateur enregistré',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'administrateur',
'group-bureaucrat-member'    => 'bureaucrate',
'group-suppress-member'      => 'superviseur',

'grouppage-user'          => '{{ns:project}}:Utilisateurs',
'grouppage-autoconfirmed' => '{{ns:project}}:Utilisateurs enregistrés',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administrateurs',
'grouppage-bureaucrat'    => '{{ns:project}}:Bureaucrates',
'grouppage-suppress'      => '{{ns:project}}:Superviseurs',

# Rights
'right-read'                  => 'Lire les pages',
'right-edit'                  => 'Modifier les pages',
'right-createpage'            => 'Créer des pages (qui ne sont pas des pages de discussion)',
'right-createtalk'            => 'Créer des pages de discussion',
'right-createaccount'         => 'Créer des comptes utilisateur',
'right-minoredit'             => 'Marquer ses modifications comme mineures',
'right-move'                  => 'Renommer des pages',
'right-move-subpages'         => 'Renommer des pages avec leurs sous-pages',
'right-move-rootuserpages'    => 'Renommer la page principale d’un utilisateur',
'right-movefile'              => 'Renommer des fichiers',
'right-suppressredirect'      => 'Ne pas créer de redirection depuis le titre d’origine en renommant une page',
'right-upload'                => 'Téléverser des fichiers',
'right-reupload'              => 'Écraser un fichier existant',
'right-reupload-own'          => 'Écraser un fichier que l’on a soi-même téléversé',
'right-reupload-shared'       => 'Écraser localement un fichier présent sur un dépôt partagé',
'right-upload_by_url'         => 'Téléverser un fichier depuis une adresse URL',
'right-purge'                 => 'Purger le cache des pages sans demande de confirmation',
'right-autoconfirmed'         => 'Modifier les pages semi-protégées',
'right-bot'                   => 'Être traité comme un processus automatisé',
'right-nominornewtalk'        => 'Ne pas déclencher la notification de nouveau message lorsqu’on effectue une modification mineure sur la page de discussion d’un utilisateur',
'right-apihighlimits'         => 'Utiliser des limites plus élevées dans les requêtes API',
'right-writeapi'              => 'Utiliser l’API de modification du wiki',
'right-delete'                => 'Supprimer des pages',
'right-bigdelete'             => 'Supprimer des pages ayant un gros historique',
'right-deleterevision'        => 'Supprimer ou restaurer une version particulière d’une page',
'right-deletedhistory'        => 'Voir les entrées des historiques supprimés mais sans leur texte',
'right-deletedtext'           => 'Voir le texte supprimé et les différences entre les versions supprimées',
'right-browsearchive'         => 'Rechercher des pages supprimées',
'right-undelete'              => 'Restaurer une page supprimée',
'right-suppressrevision'      => 'Examiner et restaurer les versions masquées aux administrateurs',
'right-suppressionlog'        => 'Voir les journaux privés',
'right-block'                 => 'Bloquer en écriture d’autres utilisateurs',
'right-blockemail'            => 'Empêcher un utilisateur d’envoyer des courriels',
'right-hideuser'              => 'Bloquer un utilisateur en masquant son nom au public',
'right-ipblock-exempt'        => 'Ne pas être affecté par les IP bloquées, les blocages automatiques et les blocages de plages d’IP',
'right-proxyunbannable'       => 'Ne pas être affecté par les blocages automatiques de serveurs mandataires',
'right-unblockself'           => 'Se débloquer eux-mêmes',
'right-protect'               => 'Modifier le niveau de protection des pages et modifier les pages protégées',
'right-editprotected'         => 'Modifier les pages protégées (sans protection en cascade)',
'right-editinterface'         => 'Modifier l’interface utilisateur',
'right-editusercssjs'         => 'Modifier les fichiers CSS et JavaScript d’autres utilisateurs',
'right-editusercss'           => 'Modifier les fichiers CSS d’autres utilisateurs',
'right-edituserjs'            => 'Modifier les fichiers JavaScript d’autres utilisateurs',
'right-rollback'              => 'Révoquer rapidement les modifications du dernier contributeur d’une page particulière',
'right-markbotedits'          => 'Marquer des modifications révoquées comme ayant été faites par un robot.',
'right-noratelimit'           => 'Ne pas être affecté par les limites de taux',
'right-import'                => 'Importer des pages depuis d’autres wikis',
'right-importupload'          => 'Importer des pages depuis un fichier',
'right-patrol'                => 'Marquer des modifications des autres comme vérifiées',
'right-autopatrol'            => 'Avoir ses modifications automatiquement marquées comme surveillées',
'right-patrolmarks'           => 'Voir les marquages de surveillance dans les modifications récentes',
'right-unwatchedpages'        => 'Voir la liste des pages non suivies',
'right-trackback'             => 'Ajouter des rétroliens',
'right-mergehistory'          => 'Fusionner les historiques des pages',
'right-userrights'            => 'Modifier tous les droits d’un utilisateur',
'right-userrights-interwiki'  => 'Modifier les droits d’utilisateurs qui sont sur un autre wiki',
'right-siteadmin'             => 'Verrouiller ou déverrouiller la base de données',
'right-reset-passwords'       => 'Changer le mot de passe d’autres utilisateurs',
'right-override-export-depth' => 'Exporter les pages en incluant les pages liées jusqu’à une profondeur de 5 niveaux',
'right-sendemail'             => 'Envoyer un courriel aux autres utilisateurs',
'right-revisionmove'          => 'Déplacer des révisions',
'right-selenium'              => 'Exécuter les tests Selenium',

# User rights log
'rightslog'      => 'Journal des modifications de droits d’utilisateurs',
'rightslogtext'  => 'Voici l’historique des modifications des droits des utilisateurs.',
'rightslogentry' => 'a modifié les droits de l’utilisateur « $1 » de $2 à $3',
'rightsnone'     => '(aucun)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lire cette page',
'action-edit'                 => 'modifier cette page',
'action-createpage'           => 'créer des pages',
'action-createtalk'           => 'créer des pages de discussion',
'action-createaccount'        => 'créer ce compte utilisateur',
'action-minoredit'            => 'marquer cette modification comme mineure',
'action-move'                 => 'renommer cette page',
'action-move-subpages'        => 'renommer cette page et ses sous-pages',
'action-move-rootuserpages'   => 'renommer la page principale d’un utilisateur',
'action-movefile'             => 'renommer ce fichier',
'action-upload'               => 'téléverser ce fichier',
'action-reupload'             => 'écraser ce fichier existant',
'action-reupload-shared'      => 'outrepasser localement ce fichier présent sur un dépôt partagé',
'action-upload_by_url'        => 'téléverser ce fichier à partir d’une adresse URL',
'action-writeapi'             => 'utiliser l‘API d’écriture',
'action-delete'               => 'supprimer cette page',
'action-deleterevision'       => 'supprimer cette version',
'action-deletedhistory'       => 'voir l’historique supprimé de cette page',
'action-browsearchive'        => 'rechercher des pages supprimées',
'action-undelete'             => 'restaurer cette page',
'action-suppressrevision'     => 'visionner et rétablir cette version supprimée',
'action-suppressionlog'       => 'voir ce journal privé',
'action-block'                => 'bloquer en écriture cet utilisateur',
'action-protect'              => 'modifier les niveaux de protection pour cette page',
'action-import'               => 'importer cette page à partir d’un autre wiki',
'action-importupload'         => 'importer cette page à partir d’un fichier téléversé',
'action-patrol'               => 'marquer la modification des autres comme patrouillée',
'action-autopatrol'           => 'avoir votre modification marquée comme patrouillée',
'action-unwatchedpages'       => 'voir la liste des pages non suivies',
'action-trackback'            => 'soumettre un rétrolien',
'action-mergehistory'         => 'fusionner l’historique de cette page',
'action-userrights'           => 'modifier tous les droits d’utilisateur',
'action-userrights-interwiki' => 'modifier les droits des utilisateurs sur d’autres wikis',
'action-siteadmin'            => 'verrouiller ou déverrouiller la base de données',
'action-revisionmove'         => 'déplacer des révisions',

# Recent changes
'nchanges'                          => '$1 modification{{PLURAL:$1||s}}',
'recentchanges'                     => 'Modifications récentes',
'recentchanges-legend'              => 'Options des modifications récentes',
'recentchangestext'                 => 'Piste les changements les plus récents du wiki sur cette page.',
'recentchanges-feed-description'    => 'Suivre les dernières modifications de ce wiki dans un flux.',
'recentchanges-label-legend'        => 'Légende : $1.',
'recentchanges-legend-newpage'      => '$1 - nouvelle page',
'recentchanges-label-newpage'       => 'Cette modification a créé une nouvelle page',
'recentchanges-legend-minor'        => '$1 - modification mineure',
'recentchanges-label-minor'         => 'Cette modification est mineure',
'recentchanges-legend-bot'          => '$1 - modification faite par un robot',
'recentchanges-label-bot'           => 'Cette modification a été effectuée par un robot.',
'recentchanges-legend-unpatrolled'  => '$1 - modification non patrouillée',
'recentchanges-label-unpatrolled'   => 'Cette modification n’a pas encore été patrouillée.',
'rcnote'                            => 'Voici {{PLURAL:$1|la dernière modification effectuée|les $1 dernières modifications effectuées}} durant {{PLURAL:$2|la dernière journée|les <b>$2</b> derniers jours}} jusqu’à $5 le $4.',
'rcnotefrom'                        => "Voici les modifications effectuées depuis le '''$2''' ('''$1''' au maximum).",
'rclistfrom'                        => 'Afficher les nouvelles modifications depuis le $1.',
'rcshowhideminor'                   => '$1 les modifications mineures',
'rcshowhidebots'                    => '$1 les robots',
'rcshowhideliu'                     => '$1 les utilisateurs inscrits',
'rcshowhideanons'                   => '$1 les utilisateurs anonymes',
'rcshowhidepatr'                    => '$1 les modifications surveillées',
'rcshowhidemine'                    => '$1 mes modifications',
'rclinks'                           => 'Afficher les $1 dernières modifications effectuées au cours des $2 derniers jours<br />$3.',
'diff'                              => 'diff',
'hist'                              => 'hist',
'hide'                              => 'masquer',
'show'                              => 'afficher',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 utilisateur{{PLURAL:$1||s}} en train de suivre]',
'rc_categories'                     => 'Limite des catégories (séparation avec « | »)',
'rc_categories_any'                 => 'Toutes',
'newsectionsummary'                 => '/* $1 */ nouvelle section',
'rc-enhanced-expand'                => 'Voir les détails (nécessite JavaScript)',
'rc-enhanced-hide'                  => 'Masquer les détails',

# Recent changes linked
'recentchangeslinked'          => 'Suivi des pages liées',
'recentchangeslinked-feed'     => 'Suivi des pages liées',
'recentchangeslinked-toolbox'  => 'Suivi des pages liées',
'recentchangeslinked-title'    => 'Suivi des pages associées à « $1 »',
'recentchangeslinked-noresult' => 'Il n’y a pas de modification sur les pages liées pendant la période choisie.',
'recentchangeslinked-summary'  => "Cette page spéciale montre les modifications récentes sur les pages qui sont liées. Les pages de votre liste de suivi sont '''en gras'''.",
'recentchangeslinked-page'     => 'Nom de la page :',
'recentchangeslinked-to'       => 'Afficher les modifications des pages qui comportent un lien vers la page donnée plutôt que l’inverse',

# Upload
'upload'                      => 'Téléverser un fichier',
'uploadbtn'                   => 'Téléverser le fichier',
'reuploaddesc'                => 'Annuler et retourner au formulaire de téléversement',
'upload-tryagain'             => 'Envoyer la description du fichier modifiée',
'uploadnologin'               => 'Non connecté(e)',
'uploadnologintext'           => 'Vous devez être [[Special:UserLogin|connecté(e)]] pour téléverser des fichiers sur le serveur.',
'upload_directory_missing'    => 'Le répertoire de téléversement ($1) est introuvable et n’a pas pu être créé par le serveur web.',
'upload_directory_read_only'  => 'Le répertoire de téléversement ($1) n’est pas accessible en écriture depuis le serveur web.',
'uploaderror'                 => 'Erreur de téléversement',
'upload-recreate-warning'     => "'''Attention : Un fichier portant ce nom a été supprimé ou déplacé.'''

Le journal des suppressions et celui des déplacements de cette page sont affichés ici pour informations :",
'uploadtext'                  => "Utilisez ce formulaire pour téléverser des fichiers sur le serveur.
Pour voir ou rechercher des images précédemment envoyées, consultez la [[Special:FileList|liste des images]]. Les téléversements sont aussi enregistrés dans le [[Special:Log/upload|journal des téléversements]], et les suppressions dans le [[Special:Log/delete|journal des suppressions]].

Pour inclure un fichier dans une page, utilisez un lien de la forme :
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:fichier.jpg]]</nowiki></code>''', pour afficher le fichier en pleine résolution (dans le cas d’une image) ;
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:fichier.png|200px|thumb|left|texte descriptif]]</nowiki></code>''' pour utiliser une miniature de 200 pixels de large dans une boîte à gauche avec « texte descriptif » comme description ;
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:fichier.ogg]]</nowiki></code>''' pour lier directement vers le fichier sans l’afficher.",
'upload-permitted'            => 'Formats de fichiers autorisés : $1.',
'upload-preferred'            => 'Formats de fichiers préférés : $1.',
'upload-prohibited'           => 'Formats de fichiers interdits : $1.',
'uploadlog'                   => 'Journal des téléversements',
'uploadlogpage'               => 'Journal des téléversements de fichiers',
'uploadlogpagetext'           => 'Voici la liste des derniers fichiers téléversés sur le serveur.
Voyez la [[Special:NewFiles|galerie des nouvelles images]] pour une présentation plus visuelle.',
'filename'                    => 'Nom du fichier',
'filedesc'                    => 'Description',
'fileuploadsummary'           => 'Description :',
'filereuploadsummary'         => 'Modifications du fichier :',
'filestatus'                  => 'Statut des droits d’auteur :',
'filesource'                  => 'Source :',
'uploadedfiles'               => 'Fichiers téléversés',
'ignorewarning'               => 'Ignorer l’avertissement et sauvegarder le fichier.',
'ignorewarnings'              => 'Ignorer les avertissements',
'minlength1'                  => 'Le noms de fichiers doivent comprendre au moins une lettre.',
'illegalfilename'             => 'Le nom de fichier « $1 » contient des caractères interdits dans les titres de pages. Merci de le renommer et de le téléverser à nouveau.',
'badfilename'                 => 'Le fichier a été renommé en « $1 ».',
'filetype-mime-mismatch'      => 'L’extension du fichier ne correspond pas au type MIME.',
'filetype-badmime'            => 'Les fichiers du type MIME « $1 » ne peuvent pas être téléversés.',
'filetype-bad-ie-mime'        => 'Le fichier ne peut pas être téléversé parce qu’il serait détecté comme « $1 » par Internet Explorer, ce qui correspond à un type de fichier interdit car potentiellement dangereux.',
'filetype-unwanted-type'      => "'''« .$1 »''' est un format de fichier non désiré.
{{PLURAL:$3|Le type de fichier préconisé est|Les types de fichiers préconisés sont}} $2.",
'filetype-banned-type'        => "'''« .$1 »''' n’est pas une extension de fichier autorisée.
{{PLURAL:$3|Le type de fichiers accepté est|Les types de fichiers acceptés sont}} $2.",
'filetype-missing'            => 'Le fichier n’a aucune extension (comme « .jpg » par exemple).',
'empty-file'                  => 'Le fichier que vous avez soumis était vide.',
'file-too-large'              => 'Le fichier que vous avez soumis était trop grand.',
'filename-tooshort'           => 'Le nom du fichier est trop court.',
'filetype-banned'             => 'Ce type de fichier est interdit.',
'verification-error'          => 'Ce fichier ne passe pas la vérification des fichiers.',
'hookaborted'                 => "La modification que vous avez essayé de faire a été arrêtée par un crochet d'une extension.",
'illegal-filename'            => "Le nom du fichier n'est pas autorisé.",
'overwrite'                   => "Écraser un fichier existant n'est pas autorisé.",
'unknown-error'               => "Une erreur inconnue s'est produite.",
'tmp-create-error'            => 'Impossible de créer le fichier temporaire.',
'tmp-write-error'             => "Erreur d'écriture du fichier temporaire.",
'large-file'                  => 'Les fichiers téléversés ne devraient pas être plus gros que $1 ; ce fichier fait $2.',
'largefileserver'             => 'La taille de ce fichier est supérieure au maximum autorisé.',
'emptyfile'                   => 'Le fichier que vous voulez téléverser semble vide.
Ceci peut être dû à une erreur dans le nom du fichier.
Veuillez vérifier que vous désirez vraiment téléverser ce fichier.',
'fileexists'                  => "Un fichier existe déjà sous ce nom.
Merci de vérifier '''<tt>[[:$1]]</tt>''' si vous n’êtes pas certain{{GENDER:||e|}} de vouloir le modifier.
[[$1|thumb]]",
'filepageexists'              => "La page de description pour ce fichier a déjà été créée ici '''<tt>[[:$1]]</tt>''', mais aucun fichier n’existe actuellement sous ce nom.
Le résumé que vous allez spécifier n’apparaîtra pas sur la page de description.
Pour que ce soit le cas, vous devrez modifier manuellement la page. [[$1|thumb]]",
'fileexists-extension'        => "Un fichier existe avec un nom proche : [[$2|thumb]]
* Nom du fichier à téléverser : '''<tt>[[:$1]]</tt>'''
* Nom du fichier existant : '''<tt>[[:$2]]</tt>'''
Veuillez choisir un autre nom.",
'fileexists-thumbnail-yes'    => "Le fichier semble être une image en taille réduite ''(vignette)''. [[$1|thumb]]
Veuillez vérifier le fichier '''<tt>[[:$1]]</tt>'''.
Si le fichier vérifié est la même image avec la taille initiale, il n’y a pas besoin d’importer une version réduite.",
'file-thumbnail-no'           => "Le nom du fichier commence par '''<tt>$1</tt>'''.
Il est possible qu’il s’agisse d’une version réduite ''(vignette)''.
Si vous disposez du fichier en haute résolution, importez-le, sinon veuillez modifier son nom.",
'fileexists-forbidden'        => 'Un fichier avec ce nom existe déjà et ne peut pas être écrasé.
Si vous voulez toujours téléverser votre fichier, merci de retourner en arrière et d’utiliser un nouveau nom. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fichier portant ce nom existe déjà dans le dépôt de fichiers partagé.
Si vous voulez toujours téléverser votre fichier, veuillez revenir en arrière et utiliser un autre nom. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ce fichier est un doublon {{PLURAL:$1|du fichier suivant|des fichiers suivants}} :',
'file-deleted-duplicate'      => 'Un fichier identique à celui-ci ([[$1]]) a déjà été supprimé. Vous devriez vérifier le journal des suppressions de ce fichier avant de le téléverser à nouveau.',
'uploadwarning'               => 'Attention !',
'uploadwarning-text'          => 'Modifiez la description du fichier et essayez de nouveau.',
'savefile'                    => 'Sauvegarder le fichier',
'uploadedimage'               => 'a téléversé « [[$1]] »',
'overwroteimage'              => 'a téléversé une nouvelle version de « [[$1]] »',
'uploaddisabled'              => 'Désolé, le téléversement de fichiers est désactivé.',
'copyuploaddisabled'          => 'Téléversement de fichier par URL désactivé.',
'uploadfromurl-queued'        => "Votre téléversement a été mis dans la file d'attente.",
'uploaddisabledtext'          => 'Le téléversement de fichiers est désactivé sur ce wiki.',
'php-uploaddisabledtext'      => 'Le téléversement de fichiers a été désactivé dans PHP. Vérifiez l’option de configuration file_uploads.',
'uploadscripted'              => 'Ce fichier contient du code HTML ou un script qui pourrait être interprété de façon incorrecte par un navigateur web.',
'uploadvirus'                 => 'Ce fichier contient un virus ! Pour plus de détails, consultez : $1',
'upload-source'               => 'Fichier source',
'sourcefilename'              => 'Nom du fichier source :',
'sourceurl'                   => 'URL source :',
'destfilename'                => 'Nom sous lequel le fichier sera enregistré :',
'upload-maxfilesize'          => 'Taille maximale du fichier : $1',
'upload-description'          => 'Description du fichier',
'upload-options'              => 'Options de téléversement',
'watchthisupload'             => 'Suivre ce fichier',
'filewasdeleted'              => 'Un fichier avec ce nom a déjà été téléversé, puis supprimé.
Vous devriez vérifier le $1 avant de procéder à un nouveau téléversement.',
'upload-wasdeleted'           => "'''Attention : vous êtes en train de recréer un fichier qui a déjà été supprimé auparavant.'''

Vous devriez considérer s’il est opportun de continuer le téléversement de ce fichier. Le journal des suppressions vous donnera les éléments d’information :",
'filename-bad-prefix'         => "Le nom du fichier que vous téléversez commence par '''« $1 »''' qui est typiquement un nom attribué automatiquement par les appareils photo numériques.
Veuillez choisir un nom de fichier descriptif.",
'filename-prefix-blacklist'   => ' #<!-- laisser cette ligne telle quelle --><pre>
# La syntaxe est la suivante :
#  * Tout ce qui figure entre un caractère "#" jusqu’à la fin de la ligne est un commentaire ;
#  * Toute ligne non vide est un préfixe typique de nom de fichier assigné automatiquement par les appareils numériques :
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # certains téléphones mobiles
IMG # générique
JD # Jenoptik
MGP # Pentax
PICT # divers
 #</pre><!-- laisser cette ligne telle quelle -->',
'upload-success-subj'         => 'Téléversement effectué avec succès',
'upload-success-msg'          => 'Votre téléversement depuis [$2] a réussi. Il est disponible ici : [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Problème de téléversement',
'upload-failure-msg'          => 'Il y a eu un problème avec votre téléversement :

$1',
'upload-warning-subj'         => 'Avertissement lors du téléversement',
'upload-warning-msg'          => 'Un problème est survenu lors de votre téléversement depuis [$2]. Vous pouvez revenir au [[Special:Upload/stash/$1|formulaire de téléversement]] pour le résoudre.',

'upload-proto-error'        => 'Protocole incorrect',
'upload-proto-error-text'   => 'Le téléversement requiert des URL commençant par <code>http://</code> ou <code>ftp://</code>.',
'upload-file-error'         => 'Erreur interne',
'upload-file-error-text'    => 'Une erreur interne est survenue en voulant créer un fichier temporaire sur le serveur. Veuillez contacter un [[Special:ListUsers/sysop|administrateur]].',
'upload-misc-error'         => 'Erreur de téléversement inconnue',
'upload-misc-error-text'    => 'Une erreur inconnue est survenue pendant le téléversement.
Veuillez vérifier que l’URL est valide et accessible, puis essayer à nouveau.
Si le problème persiste, contactez un [[Special:ListUsers/sysop|administrateur]].',
'upload-too-many-redirects' => 'L’URL contient trop de redirections.',
'upload-unknown-size'       => 'Taille inconnue',
'upload-http-error'         => 'Une erreur HTTP est intervenue : $1',

# img_auth script messages
'img-auth-accessdenied' => 'Accès refusé',
'img-auth-nopathinfo'   => 'PATH_INFO manquant.
Votre serveur n’est pas paramétré pour passer cette information.
Il fonctionne peut-être en CGI et ne supporte pas img_atuh.
Consultez http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Le chemin demandé n’est pas le répertoire de téléversement configuré.',
'img-auth-badtitle'     => 'Impossible de construire un titre valide à partir de « $1 ».',
'img-auth-nologinnWL'   => 'Vous n’êtes pas connecté et « $1 » n’est pas dans la liste blanche.',
'img-auth-nofile'       => 'Le fichier « $1 » n’existe pas.',
'img-auth-isdir'        => 'Vous essayez d’accéder au répertoire « $1 ».
Seul l’accès aux fichiers est permis.',
'img-auth-streaming'    => 'Lecture en continu de « $1 ».',
'img-auth-public'       => 'La fonction de img_auth.php est d’afficher des fichiers d’un wiki privé.
Ce wiki est configuré comme un wiki public.
Pour une sécurité optimale, img_auth.php est désactivé.',
'img-auth-noread'       => 'L’utilisateur n’a pas le droit en lecture sur « $1 ».',

# HTTP errors
'http-invalid-url'      => 'URL incorrecte : $1',
'http-invalid-scheme'   => 'Les URLs avec le schéma « $1 » ne sont pas supportées',
'http-request-error'    => 'Erreur inconnue lors de l’envoi de la requête.',
'http-read-error'       => 'Erreur de lecture HTTP.',
'http-timed-out'        => 'La requête HTTP a expiré.',
'http-curl-error'       => 'Erreur lors de la récupération de l’URL : $1',
'http-host-unreachable' => 'Impossible d’atteindre l’URL.',
'http-bad-status'       => 'Il y a eu un problème lors de la requête HTTP : $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL injoignable',
'upload-curl-error6-text'  => 'L’URL fournie ne peut pas être atteinte. Veuillez vérifier que l’URL est correcte et que le site est en ligne.',
'upload-curl-error28'      => 'Dépassement du délai lors du téléversement',
'upload-curl-error28-text' => 'Le site a mis trop longtemps à répondre. Vérifiez que le site est en ligne, attendez un peu et réessayez. Vous pouvez aussi réessayer à une heure de moindre affluence.',

'license'            => 'Licence',
'license-header'     => 'Publié sous licence(s)',
'nolicense'          => 'Aucune licence sélectionnée',
'license-nopreview'  => '(Prévisualisation non disponible)',
'upload_source_url'  => ' (une URL valide et accessible publiquement)',
'upload_source_file' => ' (un fichier sur votre ordinateur)',

# Special:ListFiles
'listfiles-summary'     => 'Cette page spéciale permet de lister tous les fichiers téléversés.
Par défaut, les derniers fichiers téléversés sont affichés en tête de liste.
Cliquer sur un en-tête de colonne permet de changer l’ordre d’affichage.',
'listfiles_search_for'  => 'Rechercher un nom de média :',
'imgfile'               => 'fichier',
'listfiles'             => 'Liste de fichiers',
'listfiles_date'        => 'Date',
'listfiles_name'        => 'Nom',
'listfiles_user'        => 'Utilisateur',
'listfiles_size'        => 'Taille',
'listfiles_description' => 'Description',
'listfiles_count'       => 'Versions',

# File description page
'file-anchor-link'                  => 'Fichier',
'filehist'                          => 'Historique du fichier',
'filehist-help'                     => 'Cliquer sur une date et heure pour voir le fichier tel qu’il était à ce moment-là.',
'filehist-deleteall'                => 'supprimer tout',
'filehist-deleteone'                => 'supprimer',
'filehist-revert'                   => 'rétablir',
'filehist-current'                  => 'actuel',
'filehist-datetime'                 => 'Date et heure',
'filehist-thumb'                    => 'Miniature',
'filehist-thumbtext'                => 'Vignette pour la version du $1',
'filehist-nothumb'                  => 'Pas de miniature',
'filehist-user'                     => 'Utilisateur',
'filehist-dimensions'               => 'Dimensions',
'filehist-filesize'                 => 'Taille du fichier',
'filehist-comment'                  => 'Commentaire',
'filehist-missing'                  => 'Fichier manquant',
'imagelinks'                        => 'Utilisations du fichier',
'linkstoimage'                      => '{{PLURAL:$1|La page suivante utilise|Les $1 pages suivantes utilisent}} ce fichier :',
'linkstoimage-more'                 => 'Plus {{PLURAL:$1|d’une page utilise|de $1 pages utilisent}} ce fichier.
La liste suivante affiche seulement {{PLURAL:$1|la première page qui utilise|les $1 premières pages qui utilisent}} ce fichier.
Une [[Special:WhatLinksHere/$2|liste complète]] est disponible.',
'nolinkstoimage'                    => 'Aucune page n’utilise ce fichier.',
'morelinkstoimage'                  => 'Voir [[Special:WhatLinksHere/$1|plus de liens]] vers ce fichier.',
'redirectstofile'                   => '{{PLURAL:$1|Le fichier suivant redirige|Les fichiers suivants redirigent}} vers celui-ci :',
'duplicatesoffile'                  => '{{PLURAL:$1|Le fichier suivant est un duplicata|Les fichiers suivants sont des duplicatas}} de celui-ci ([[Special:FileDuplicateSearch/$2|plus de détails]]) :',
'sharedupload'                      => 'Ce fichier provient de $1 et peut être utilisé par d’autres projets.',
'sharedupload-desc-there'           => 'Ce fichier provient de $1 et peut être utilisé par d’autres projets. Voyez [$2 sa page de description] pour plus d’informations.',
'sharedupload-desc-here'            => 'Ce fichier provient de $1 et peut être utilisé par d’autres projets. Sa [$2 page de description] est affichée ci-dessous.',
'filepage-nofile'                   => 'Aucun fichier de ce nom existe.',
'filepage-nofile-link'              => 'Aucun fichier de ce nom n’existe, mais vous pouvez [$1 en téléverser un].',
'uploadnewversion-linktext'         => 'Téléverser une nouvelle version de ce fichier',
'shared-repo-from'                  => 'de $1',
'shared-repo'                       => 'un dépôt partagé',
'shared-repo-name-wikimediacommons' => 'Wikimédia Commons',

# File reversion
'filerevert'                => 'Rétablir $1',
'filerevert-legend'         => 'Rétablir le fichier',
'filerevert-intro'          => "Vous êtes sur le point de rétablir le fichier '''[[Media:$1|$1]]''' à la [$4 version du $2 à $3].",
'filerevert-comment'        => 'Motif :',
'filerevert-defaultcomment' => 'Version du $1 à $2 rétablie',
'filerevert-submit'         => 'Rétablir',
'filerevert-success'        => "'''[[Media:$1|$1]]''' a été rétabli à [$4 la version du $2 à $3].",
'filerevert-badversion'     => 'Il n’y a pas localement de version antérieure du fichier qui porte la date indiquée.',

# File deletion
'filedelete'                  => 'Supprimer $1',
'filedelete-legend'           => 'Supprimer le fichier',
'filedelete-intro'            => "Vous êtes sur le point de supprimer '''[[Media:$1|$1]]''' ainsi que tout son historique.",
'filedelete-intro-old'        => "Vous êtes en train d’effacer la version de '''[[Media:$1|$1]]''' du [$4 $2 à $3].",
'filedelete-comment'          => 'Motif :',
'filedelete-submit'           => 'Supprimer',
'filedelete-success'          => "'''$1''' a été supprimé.",
'filedelete-success-old'      => "La version de '''[[Media:$1|$1]]''' du $2 à $3 a été supprimée.",
'filedelete-nofile'           => "'''$1''' n’existe pas.",
'filedelete-nofile-old'       => "Il n’existe aucune version archivée de '''$1''' avec les attributs indiqués.",
'filedelete-otherreason'      => 'Motif autre / supplémentaire :',
'filedelete-reason-otherlist' => 'Autre motif',
'filedelete-reason-dropdown'  => '* Motifs fréquents de suppression de fichiers
** Violation du droit d’auteur
** Fichier dupliqué',
'filedelete-edit-reasonlist'  => 'Modifier les motifs fréquents de suppression',
'filedelete-maintenance'      => 'La suppression et restauration de fichiers est temporairement désactivée durant la maintenance.',

# MIME search
'mimesearch'         => 'Recherche par type de contenu MIME',
'mimesearch-summary' => "Cette page vous permet de lister les fichiers accessibles par ce wiki en fonction de leur type de contenu MIME.
Entrée : ''typedecontenu''/''sous-type'', par exemple <tt>image/jpeg</tt>.",
'mimetype'           => 'Type MIME :',
'download'           => 'Télécharger',

# Unwatched pages
'unwatchedpages' => 'Pages ne faisant partie d’aucune liste de suivi',

# List redirects
'listredirects' => 'Liste des redirections',

# Unused templates
'unusedtemplates'     => 'Modèles inutilisés',
'unusedtemplatestext' => 'Cette page liste toutes les pages de l’espace de noms « {{ns:template}} » qui ne sont incluses dans aucune autre page.
N’oubliez pas de vérifier s’il n’y a pas d’autres liens vers les modèles avant de les supprimer.',
'unusedtemplateswlh'  => 'autres liens',

# Random page
'randompage'         => 'Page au hasard',
'randompage-nopages' => 'Il n’y a aucune page dans {{PLURAL:$2|l’espace de noms|les espaces de noms}} : $1.',

# Random redirect
'randomredirect'         => 'Page de redirection au hasard',
'randomredirect-nopages' => 'Il n’y a aucune page de redirection dans l’espace de noms « $1 ».',

# Statistics
'statistics'                   => 'Statistiques',
'statistics-header-pages'      => 'Statistiques des pages',
'statistics-header-edits'      => 'Statistiques des modifications',
'statistics-header-views'      => 'Statistiques des visites',
'statistics-header-users'      => 'Statistiques des utilisateurs',
'statistics-header-hooks'      => 'Autres statistiques',
'statistics-articles'          => 'Pages de contenu',
'statistics-pages'             => 'Pages',
'statistics-pages-desc'        => 'Toutes les pages du wiki, y compris les pages de discussion, les redirections, etc.',
'statistics-files'             => 'Fichers téléversés',
'statistics-edits'             => 'Modifications de pages depuis l’installation de {{SITENAME}}',
'statistics-edits-average'     => 'Nombre moyen de modifications par page',
'statistics-views-total'       => 'Visites',
'statistics-views-peredit'     => 'Visites par modification',
'statistics-users'             => '[[Special:ListUsers|Utilisateurs]] enregistrés',
'statistics-users-active'      => 'Utilisateurs actifs',
'statistics-users-active-desc' => 'Utilisateurs ayant fait au moins une action durant {{PLURAL:$1|le dernier jours|les $1 derniers jours}}',
'statistics-mostpopular'       => 'Pages les plus consultées',

'disambiguations'      => 'Pages d’homonymie',
'disambiguationspage'  => 'Template:Homonymie',
'disambiguations-text' => "Les pages suivantes comportent un lien vers une '''page d’homonymie'''.
Ces liens ambigus devraient plutôt pointer vers le bon article.<br />
Une page est considérée comme une page d’homonymie si elle inclut (directement ou récursivement) un des modèles listés sur [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Doubles redirections',
'doubleredirectstext'        => 'Voici une liste des pages qui redirigent vers des pages qui sont elles-mêmes des pages de redirection.
Chaque entrée contient des liens vers la première et la seconde redirections, ainsi que la première ligne de texte de la seconde page, ce qui fournit habituellement la « vraie » page cible, vers laquelle la première redirection devrait rediriger.
Les entrées <del>barrées</del> ont été résolues.',
'double-redirect-fixed-move' => 'Cette redirection, dont la cible [[$1]] a été renommée, mène maintenant vers [[$2]].',
'double-redirect-fixer'      => 'Correcteur de redirection',

'brokenredirects'        => 'Redirections cassées',
'brokenredirectstext'    => 'Ces redirections mènent vers des pages inexistantes :',
'brokenredirects-edit'   => 'modifier',
'brokenredirects-delete' => 'supprimer',

'withoutinterwiki'         => 'Pages sans liens inter-langues',
'withoutinterwiki-summary' => 'Les pages suivantes ne possèdent pas de liens vers d’autres langues :',
'withoutinterwiki-legend'  => 'Préfixe',
'withoutinterwiki-submit'  => 'Afficher',

'fewestrevisions' => 'Pages les moins modifiées',

# Miscellaneous special pages
'nbytes'                  => '$1 octet{{PLURAL:$1||s}}',
'ncategories'             => '$1 catégorie{{PLURAL:$1||s}}',
'nlinks'                  => '$1 lien{{PLURAL:$1||s}}',
'nmembers'                => '$1 membre{{PLURAL:$1||s}}',
'nrevisions'              => '$1 version{{PLURAL:$1||s}}',
'nviews'                  => '$1 consultation{{PLURAL:$1||s}}',
'specialpage-empty'       => 'Il n’y a aucun résultat à afficher.',
'lonelypages'             => 'Pages orphelines',
'lonelypagestext'         => 'Les pages suivantes ne sont ni pointées, ni incluses par d’autres pages du wiki.',
'uncategorizedpages'      => 'Pages non catégorisées',
'uncategorizedcategories' => 'Catégories non catégorisées',
'uncategorizedimages'     => 'Fichiers non catégorisés',
'uncategorizedtemplates'  => 'Modèles sans catégorie',
'unusedcategories'        => 'Catégories inutilisées',
'unusedimages'            => 'Fichiers orphelins',
'popularpages'            => 'Pages les plus consultées',
'wantedcategories'        => 'Catégories les plus demandées',
'wantedpages'             => 'Pages les plus demandées',
'wantedpages-badtitle'    => 'Titre invalide dans les résultats : $1',
'wantedfiles'             => 'Fichiers les plus demandés',
'wantedtemplates'         => 'Modèles demandés',
'mostlinked'              => 'Pages les plus liées',
'mostlinkedcategories'    => 'Catégories les plus utilisées',
'mostlinkedtemplates'     => 'Modèles les plus utilisés',
'mostcategories'          => 'Pages utilisant le plus de catégories',
'mostimages'              => 'Fichiers les plus utilisés',
'mostrevisions'           => 'Pages les plus modifiées',
'prefixindex'             => 'Toutes les pages débutant par…',
'shortpages'              => 'Pages courtes',
'longpages'               => 'Pages longues',
'deadendpages'            => 'Pages en impasse',
'deadendpagestext'        => 'Les pages suivantes ne contiennent aucun lien vers d’autres pages du wiki.',
'protectedpages'          => 'Pages protégées',
'protectedpages-indef'    => 'Uniquement les protections permanentes',
'protectedpages-cascade'  => 'Uniquement les protections en cascade',
'protectedpagestext'      => 'Les pages suivantes sont protégées contre les modifications ou le déplacement.',
'protectedpagesempty'     => 'Aucune page n’est protégée de cette façon.',
'protectedtitles'         => 'Titres protégés',
'protectedtitlestext'     => 'Les titres suivants sont protégés à la création',
'protectedtitlesempty'    => 'Aucun titre n’est actuellement protégé avec ces paramètres.',
'listusers'               => 'Liste des utilisateurs',
'listusers-editsonly'     => 'Ne montrer que les utilisateurs ayant au moins une contribution',
'listusers-creationsort'  => 'Trier par date de création',
'usereditcount'           => '$1 modification{{PLURAL:$1||s}}',
'usercreated'             => 'Créé le $1 à $2',
'newpages'                => 'Nouvelles pages',
'newpages-username'       => 'Nom d’utilisateur :',
'ancientpages'            => 'Pages les plus anciennement modifiées',
'move'                    => 'Renommer',
'movethispage'            => 'Renommer cette page',
'unusedimagestext'        => 'Les fichiers suivants existent, mais ne sont inclus dans aucune page.
Veuillez noter que d’autres sites peuvent avoir un lien direct vers un fichier, et donc qu’un fichier peut être listé ici alors qu’il est en réalité utilisé sur ces sites.',
'unusedcategoriestext'    => 'Les catégories suivantes existent mais aucune page ou catégorie ne les utilise.',
'notargettitle'           => 'Pas de cible',
'notargettext'            => 'Vous n’avez pas indiqué une page ou un utilisateur sur lequel vous souhaitez effectuer cette action.',
'nopagetitle'             => 'Aucune telle page cible',
'nopagetext'              => 'La page cible que vous avez indiquée n’existe pas.',
'pager-newer-n'           => '{{PLURAL:$1|plus récente|$1 plus récentes}}',
'pager-older-n'           => '{{PLURAL:$1|plus ancienne|$1 plus anciennes}}',
'suppress'                => 'Superviser',

# Book sources
'booksources'               => 'Ouvrages de référence',
'booksources-search-legend' => 'Rechercher parmi des ouvrages de référence',
'booksources-isbn'          => 'ISBN :',
'booksources-go'            => 'Lister',
'booksources-text'          => 'Voici une liste indicative et non exclusive de liens vers d’autres sites vendant des livres neufs et d’occasion et sur lesquels vous trouverez peut-être des informations sur les ouvrages que vous cherchez :',
'booksources-invalid-isbn'  => 'L’ISBN donné ne semble pas être correct ; vérifiez si vous avez fait une erreur en copiant la source originale.',

# Special:Log
'specialloguserlabel'  => 'Utilisateur :',
'speciallogtitlelabel' => 'Titre :',
'log'                  => 'Journaux d’opérations',
'all-logs-page'        => 'Toutes les opérations publiques',
'alllogstext'          => 'Affichage combiné de tous les journaux disponibles des opérations sur {{SITENAME}}.
Vous pouvez restreindre la vue en sélectionnant un type de journal, un nom d’utilisateur ou une page affectée (tous les deux étant sensibles à la casse).',
'logempty'             => 'Aucune opération correspondante dans les journaux.',
'log-title-wildcard'   => 'Chercher parmi les titres commençant par ce texte',

# Special:AllPages
'allpages'          => 'Toutes les pages',
'alphaindexline'    => 'de $1 à $2',
'nextpage'          => 'Page suivante ($1)',
'prevpage'          => 'Page précédente ($1)',
'allpagesfrom'      => 'Afficher les pages à partir de :',
'allpagesto'        => 'Afficher les pages jusqu’à :',
'allarticles'       => 'Toutes les pages',
'allinnamespace'    => 'Toutes les pages (dans l’espace de noms $1)',
'allnotinnamespace' => 'Toutes les pages (hors de l’espace de noms $1)',
'allpagesprev'      => 'Précédent',
'allpagesnext'      => 'Suivant',
'allpagessubmit'    => 'Lister',
'allpagesprefix'    => 'Afficher les pages commençant par le préfixe :',
'allpagesbadtitle'  => 'Le titre de page indiqué est incorrect : il contient un préfixe inter-langue ou inter-wiki réservé, ou contient un ou plusieurs caractères inutilisables dans les titres.',
'allpages-bad-ns'   => '{{SITENAME}} n’a pas d’espace de noms « $1 ».',

# Special:Categories
'categories'                    => 'Catégories',
'categoriespagetext'            => '{{PLURAL:$1|La catégorie suivante est utilisée|Les catégories suivantes sont utilisées}} par des pages ou fichiers.
[[Special:UnusedCategories|Les catégories inutilisées]] ne sont pas affichées ici.
Voyez aussi [[Special:WantedCategories|les catégories demandées]].',
'categoriesfrom'                => 'Afficher les catégories à partir de :',
'special-categories-sort-count' => 'tri par nombre d’éléments',
'special-categories-sort-abc'   => 'tri alphabétique',

# Special:DeletedContributions
'deletedcontributions'             => 'Contributions supprimées',
'deletedcontributions-title'       => 'Contributions supprimées',
'sp-deletedcontributions-contribs' => 'contributions',

# Special:LinkSearch
'linksearch'       => 'Liens externes',
'linksearch-pat'   => 'Expression recherchée :',
'linksearch-ns'    => 'Espace de noms :',
'linksearch-ok'    => 'Rechercher',
'linksearch-text'  => 'Des caractères « jokers » peuvent être utilisés, par exemple <code>*.wikipedia.org</code>.<br />
Protocoles reconnus : <tt>$1</tt>.',
'linksearch-line'  => '$1 est lié depuis $2',
'linksearch-error' => 'Les caractères jokers ne peuvent être utilisés qu’au début du nom de domaine de l’hôte.',

# Special:ListUsers
'listusersfrom'      => 'Afficher les utilisateurs à partir de :',
'listusers-submit'   => 'Lister',
'listusers-noresult' => 'Aucun utilisateur trouvé. Vérifiez aussi les variantes de casse.',
'listusers-blocked'  => '(bloqué{{GENDER:$1||e|(e)}})',

# Special:ActiveUsers
'activeusers'            => 'Liste des utilisateurs actifs',
'activeusers-intro'      => 'Ceci est une liste des utilisateurs qui ont exercé une quelconque activité au cours {{PLURAL:$1|de la dernière journée|des $1 derniers jours}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|modification récente|modifications récentes}} dans {{PLURAL:$3|le dernier jour|les $3 derniers jours}}',
'activeusers-from'       => 'Afficher les utilisateurs depuis :',
'activeusers-hidebots'   => 'Masquer les robots',
'activeusers-hidesysops' => 'Masquer les administrateurs',
'activeusers-noresult'   => 'Aucun utilisateur trouvé.',

# Special:Log/newusers
'newuserlogpage'              => 'Journal des créations de comptes utilisateur',
'newuserlogpagetext'          => 'Cette page affiche l’historique des créations de comptes utilisateur.',
'newuserlog-byemail'          => 'mot de passe envoyé par courriel',
'newuserlog-create-entry'     => 'Nouveau compte utilisateur',
'newuserlog-create2-entry'    => 'a créé le nouveau compte $1',
'newuserlog-autocreate-entry' => 'Compte créé automatiquement',

# Special:ListGroupRights
'listgrouprights'                      => 'Droits des groupes d’utilisateurs',
'listgrouprights-summary'              => 'Cette page contient une liste des groupes définis sur ce wiki ainsi que les droits d’accès qui leur sont associés.
Des [[{{MediaWiki:Listgrouprights-helppage}}|informations additionnelles]] peuvent exister au sujet des droits individuels.',
'listgrouprights-key'                  => '*<span class="listgrouprights-granted">Droit octroyé</span>
*<span class="listgrouprights-revoked">Droit révoqué</span>',
'listgrouprights-group'                => 'Groupe',
'listgrouprights-rights'               => 'Droits associés',
'listgrouprights-helppage'             => 'Help:Droits des groupes',
'listgrouprights-members'              => '(liste des membres)',
'listgrouprights-addgroup'             => 'Ajouter des membres {{PLURAL:$2|au groupe|aux groupes}} : $1',
'listgrouprights-removegroup'          => 'Retirer des membres {{PLURAL:$2|du groupe|des groupes}} : $1',
'listgrouprights-addgroup-all'         => 'Ajouter des membres à tous les groupes',
'listgrouprights-removegroup-all'      => 'Retirer des membres de tous les groupes',
'listgrouprights-addgroup-self'        => 'Peut s’ajouter {{PLURAL:$2|le groupe|les groupes}} à son propre compte : $1',
'listgrouprights-removegroup-self'     => 'Peut se retirer {{PLURAL:$2|le groupe|les groupes}} de son propre compte : $1',
'listgrouprights-addgroup-self-all'    => 'Peut s’ajouter tous les groupes à son propre compte',
'listgrouprights-removegroup-self-all' => 'Peut se retirer tous les groupes de son propre compte',

# E-mail user
'mailnologin'          => 'Pas d’adresse d’expéditeur',
'mailnologintext'      => 'Vous devez être [[Special:UserLogin|identifié]] et avoir indiqué une adresse électronique valide dans vos [[Special:Preferences|préférences]] pour pouvoir envoyer des courriels à d’autres utilisateurs.',
'emailuser'            => 'Lui envoyer un courriel',
'emailpage'            => 'Envoyer un courriel à l’utilisateur',
'emailpagetext'        => 'Vous pouvez utiliser le formulaire ci-dessous pour envoyer un courriel à cet utilisateur.
L’adresse électronique que vous avez indiquée dans [[Special:Preferences|vos préférences]] apparaîtra dans le champ « Expéditeur » de votre message ; ainsi, le destinataire pourra vous répondre directement.',
'usermailererror'      => 'Erreur dans l’objet du courriel :',
'defemailsubject'      => 'Courriel de {{SITENAME}}',
'usermaildisabled'     => "L'envoi de courriels entre utilisateurs est désactivé",
'usermaildisabledtext' => "Vous ne pouvez pas envoyer de courriels à d'autres utilisateurs sur ce wiki",
'noemailtitle'         => 'Aucune adresse électronique',
'noemailtext'          => 'Cet utilisateur n’a pas spécifié une adresse de courriel valide.',
'nowikiemailtitle'     => 'Pas de courriel autorisé',
'nowikiemailtext'      => 'Cet utilisateur a choisi de ne pas recevoir de courriel de la part d’autre utilisateurs.',
'email-legend'         => 'Envoyer un courriel à un autre utilisateur de {{SITENAME}}',
'emailfrom'            => 'Expéditeur :',
'emailto'              => 'Destinataire :',
'emailsubject'         => 'Objet :',
'emailmessage'         => 'Message :',
'emailsend'            => 'Envoyer',
'emailccme'            => 'M’envoyer par courriel une copie de mon message.',
'emailccsubject'       => 'Copie de votre message à $1 : $2',
'emailsent'            => 'Courriel envoyé',
'emailsenttext'        => 'Votre message a été envoyé par courriel.',
'emailuserfooter'      => 'Ce courriel a été envoyé par « $1 » à « $2 » par la fonction « Envoyer un courriel à l’utilisateur » de {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'A laissé un message système.',
'usermessage-editor'  => 'Messager du système',

# Watchlist
'watchlist'            => 'Liste de suivi',
'mywatchlist'          => 'Liste de suivi',
'watchlistfor'         => "(pour '''$1''')",
'nowatchlist'          => 'Votre liste de suivi ne référence aucune page.',
'watchlistanontext'    => 'Veuillez vous $1 pour visualiser ou modifier les éléments de votre liste de suivi.',
'watchnologin'         => 'Non connecté',
'watchnologintext'     => 'Vous devez être [[Special:UserLogin|identifié]] pour modifier votre liste de suivi.',
'addedwatch'           => 'Ajouté à la liste de suivi',
'addedwatchtext'       => "La page « [[:$1]] » a été ajoutée à votre [[Special:Watchlist|liste de suivi]]. Les prochaines modifications de cette page et de la page de discussion associée y seront répertoriées. La page apparaîtra '''en gras''' dans la [[Special:RecentChanges|liste des modifications récentes]] pour en faciliter le repérage.",
'removedwatch'         => 'Retirée de la liste de suivi',
'removedwatchtext'     => 'La page « [[:$1]] » a été retirée de votre [[Special:Watchlist|liste de suivi]].',
'watch'                => 'Suivre',
'watchthispage'        => 'Suivre cette page',
'unwatch'              => 'Ne plus suivre',
'unwatchthispage'      => 'Ne plus suivre',
'notanarticle'         => 'Ce n’est pas une page de contenu',
'notvisiblerev'        => 'La version a été supprimée',
'watchnochange'        => 'Aucun des éléments que vous suivez n’a été modifié durant la période affichée.',
'watchlist-details'    => 'Votre liste de suivi référence $1 page{{PLURAL:$1||s}}, sans compter les pages de discussion.',
'wlheader-enotif'      => '* La notification par courriel est activée.',
'wlheader-showupdated' => "* Les pages qui ont été modifiées depuis votre dernière visite sont affichées en '''gras'''.",
'watchmethod-recent'   => 'vérification des modifications récentes pour y trouver des pages suivies',
'watchmethod-list'     => 'vérification des pages suivies pour y trouver des modifications récentes',
'watchlistcontains'    => 'Votre liste de suivi référence $1 page{{PLURAL:$1||s}}.',
'iteminvalidname'      => 'Problème avec l’élément « $1 » : le nom est invalide.',
'wlnote'               => "Ci-dessous {{PLURAL:$1|figure la dernière modification effectuée|figurent les '''$1''' dernières modifications effectuées}} durant {{PLURAL:$2|la dernière heure|les '''$2''' dernières heures}}.",
'wlshowlast'           => 'Montrer les dernières $1 heures, les derniers $2 jours ou bien $3',
'watchlist-options'    => 'Options de la liste de suivi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Suivi…',
'unwatching' => 'Fin du suivi…',

'enotif_mailer'                => 'Système de notification par courriel de {{SITENAME}}',
'enotif_reset'                 => 'Marquer toutes les pages comme visitées',
'enotif_newpagetext'           => 'Ceci est une nouvelle page.',
'enotif_impersonal_salutation' => 'Utilisateur de {{SITENAME}}',
'changed'                      => 'modifiée',
'created'                      => 'créée',
'enotif_subject'               => 'La page $PAGETITLE de {{SITENAME}} a été $CHANGEDORCREATED par $PAGEEDITOR',
'enotif_lastvisited'           => 'Voyez $1 pour tous les changements depuis votre dernière visite.',
'enotif_lastdiff'              => 'Voyez $1 pour visualiser ces changements.',
'enotif_anon_editor'           => 'utilisateur non-enregistré $1',
'enotif_body'                  => 'Cher $WATCHINGUSERNAME,

La page « $PAGETITLE » de {{SITENAME}} a été $CHANGEDORCREATED le $PAGEEDITDATE par « $PAGEEDITOR », visitez $PAGETITLE_URL pour visualiser la version actuelle.

$NEWPAGE

Résumé du contributeur : $PAGESUMMARY $PAGEMINOREDIT

Contactez ce contributeur :
courriel : $PAGEEDITOR_EMAIL
wiki : $PAGEEDITOR_WIKI

Il n’y aura pas d’autres notifications en cas de changements ultérieurs, à moins que vous ne visitiez cette page.
Vous pouvez aussi réinitialiser les drapeaux de notification pour toutes les pages de votre liste de suivi.

             Votre système de notification de {{SITENAME}}

--
Pour modifier les paramètres de votre liste de suivi, visitez
{{fullurl:{{#special:Watchlist}}/edit}}

Pour supprimer la page de votre liste de suivi, visitez
$UNWATCHURL

Retour et assistance :
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Supprimer la page',
'confirm'                => 'Confirmer',
'excontent'              => 'contenait « $1 »',
'excontentauthor'        => 'contenait « $1 » (et son seul contributeur était [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'contenait avant blanchiment « $1 »',
'exblank'                => 'la page était vide',
'delete-confirm'         => 'Supprimer « $1 »',
'delete-legend'          => 'Supprimer',
'historywarning'         => "'''Attention :''' la page que vous êtes sur le point de supprimer a un historique qui contient approximativement $1 {{PLURAL:$1|révision|révisions}} :",
'confirmdeletetext'      => 'Vous êtes sur le point de supprimer une page ou un fichier, ainsi que toutes ses versions antérieures historisées. Veuillez confirmer que c’est bien là ce que vous voulez faire, que vous en comprenez les conséquences et que vous faites ceci en accord avec les [[{{MediaWiki:Policy-url}}|règles internes]].',
'actioncomplete'         => 'Action effectuée',
'actionfailed'           => 'L’action a échoué',
'deletedtext'            => '« <nowiki>$1</nowiki> » a été supprimé.
Voir $2 pour une liste des suppressions récentes.',
'deletedarticle'         => 'a supprimé « [[$1]] »',
'suppressedarticle'      => 'a supprimé « [[$1]] »',
'dellogpage'             => 'Journal des suppressions de page',
'dellogpagetext'         => 'Voici la liste des suppressions les plus récentes.',
'deletionlog'            => 'journal des suppressions',
'reverted'               => 'Version précédente rétablie',
'deletecomment'          => 'Motif :',
'deleteotherreason'      => 'Motif autre ou supplémentaire :',
'deletereasonotherlist'  => 'Autre motif',
'deletereason-dropdown'  => '* Motifs de suppression les plus courants
** Demande de l’auteur
** Violation des droits d’auteur
** Vandalisme',
'delete-edit-reasonlist' => 'Modifier les motifs de suppression de page',
'delete-toobig'          => 'Cette page possède un historique important de modifications, dépassant $1 version{{PLURAL:$1||s}}.
La suppression de telles pages a été restreinte pour prévenir des perturbations accidentelles de {{SITENAME}}.',
'delete-warning-toobig'  => 'Cette page possède un historique important de modifications, dépassant $1 version{{PLURAL:$1||s}}.
La supprimer peut perturber le fonctionnement de la base de données de {{SITENAME}} ;
veuiller ne procéder qu’avec prudence.',

# Rollback
'rollback'          => 'Révoquer les modifications',
'rollback_short'    => 'Révoquer',
'rollbacklink'      => 'révoquer',
'rollbackfailed'    => 'La révocation a échoué',
'cantrollback'      => 'Impossible de révoquer la modification ;
le dernier contributeur est le seul auteur de cette page.',
'alreadyrolled'     => 'Impossible de révoquer la dernière modification de la page « [[:$1]] » effectuée par [[User:$2|$2]] ([[User talk:$2|Discuter]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) ;
quelqu’un d’autre a déjà modifié ou révoqué la page.

La dernière modification de la page a été effectuée par [[User:$3|$3]] ([[User talk:$3|Discuter]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Le résumé de la modification était : « ''$1'' ».",
'revertpage'        => 'Révocation des modifications de [[Special:Contributions/$2|$2]] ([[User talk:$2|discussion]]) vers la dernière version de [[User:$1|$1]]',
'revertpage-nouser' => 'Révocation des modifications par (nom d’utilisateur supprimé) à la dernière version par [[User:$1|$1]]',
'rollback-success'  => 'Révocation des modifications effectuées par $1 ;
rétablissement de la dernière version par $2.',

# Edit tokens
'sessionfailure-title' => 'Erreur de session',
'sessionfailure'       => 'Votre session de connexion semble avoir des problèmes ;
cette action a été annulée en prévention d’un piratage de session.
Veuillez cliquer sur « Précédent », rechargez la page d’où vous venez, puis réessayez.',

# Protect
'protectlogpage'              => 'Journal des protections',
'protectlogtext'              => 'Voici une liste historique des protections et déprotections de pages.
Consultez la [[Special:ProtectedPages|liste des pages protégées]] pour la liste des protections actuellement opérationnelles.',
'protectedarticle'            => 'a protégé « [[$1]] »',
'modifiedarticleprotection'   => 'a modifié le niveau de protection de « [[$1]] »',
'unprotectedarticle'          => 'a déprotégé « [[$1]] »',
'movedarticleprotection'      => 'a déplacé les paramètres de protection depuis « [[$2]] » vers « [[$1]] »',
'protect-title'               => 'Changer le niveau de protection pour « $1 »',
'prot_1movedto2'              => '[[$1]] renommé en [[$2]]',
'protect-legend'              => 'Confirmer la protection',
'protectcomment'              => 'Motif :',
'protectexpiry'               => 'Date d’expiration :',
'protect_expiry_invalid'      => 'La date d’expiration est invalide.',
'protect_expiry_old'          => 'La date d’expiration est déjà passée.',
'protect-unchain-permissions' => 'Déverrouiller davantage d’options de protection',
'protect-text'                => "Vous pouvez consulter et modifier le niveau de protection de la page '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Vous ne pouvez pas modifier les niveaux de protection tant que vous êtes bloqué{{GENDER:||e|(e)}}.
Voici les réglages actuels de la page '''$1''' :",
'protect-locked-dblock'       => "Le niveau de protection ne peut pas être modifié car la base de données est verrouillée.
Voici les réglages actuels de la page '''$1''' :",
'protect-locked-access'       => "Vous n’avez pas les droits nécessaires pour modifier les niveaux de protection de pages.
Voici les réglages actuels de la page '''$1''' :",
'protect-cascadeon'           => 'Cette page est protégée car incluse dans {{PLURAL:$1|la page suivante, qui a été protégée|les pages suivantes, qui ont été protégées}} avec l’option « protection en cascade » activée. Vous pouvez changer le niveau de protection de cette page sans que cela n’affecte la protection en cascade.',
'protect-default'             => 'Autoriser tous les utilisateurs',
'protect-fallback'            => 'Nécessite la permission « $1 »',
'protect-level-autoconfirmed' => 'Bloquer les nouveaux utilisateurs et les utilisateurs anonymes',
'protect-level-sysop'         => 'Administrateurs uniquement',
'protect-summary-cascade'     => 'protection en cascade',
'protect-expiring'            => 'expire le $1 (UTC)',
'protect-expiry-indefinite'   => 'infini',
'protect-cascade'             => 'Protège aussi les pages incluses dans celle-ci (protection en cascade)',
'protect-cantedit'            => 'Vous ne pouvez pas changer les niveaux de protection de cette page car vous n’avez pas la permission de la modifier.',
'protect-othertime'           => 'Autre date d’expiration :',
'protect-othertime-op'        => 'autre date d’expiration',
'protect-existing-expiry'     => 'Date d’expiration existante : $2 à $3',
'protect-otherreason'         => 'Motif autre ou supplémentaire :',
'protect-otherreason-op'      => 'Autre motif',
'protect-dropdown'            => '* Motifs de protection courants
** Vandalisme excessif
** Pourriels
** Conflits de modifications contre-productives
** Page à fort trafic',
'protect-edit-reasonlist'     => 'Modifier les motifs de protection',
'protect-expiry-options'      => '1 heure:1 hour,1 jour:1 day,1 semaine:1 week,2 semaines:2 weeks,1 mois:1 month,3 mois:3 months,6 mois:6 months,1 an:1 year,indéfiniment:infinite',
'restriction-type'            => 'Permission :',
'restriction-level'           => 'Niveau de restriction :',
'minimum-size'                => 'Taille minimum',
'maximum-size'                => 'Taille maximum',
'pagesize'                    => '(octets)',

# Restrictions (nouns)
'restriction-edit'   => 'Modifier',
'restriction-move'   => 'Renommer',
'restriction-create' => 'Créer',
'restriction-upload' => 'Téléverser',

# Restriction levels
'restriction-level-sysop'         => 'protection complète',
'restriction-level-autoconfirmed' => 'semi-protection',
'restriction-level-all'           => 'tout niveau',

# Undelete
'undelete'                     => 'Voir les pages supprimées',
'undeletepage'                 => 'Voir et restaurer des pages supprimées',
'undeletepagetitle'            => "'''La liste suivante contient des versions supprimées de [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Visualiser les pages supprimées',
'undeletepagetext'             => '{{PLURAL:$1|La page suivante a été supprimée et se trouve|Les pages suivantes ont été supprimées et se trouvent}} dans la base de données archive, d’où {{PLURAL:$1|elle peut|elles peuvent}} encore être restaurée{{PLURAL:$1||s}}.
L’archive peut être nettoyée périodiquement.',
'undelete-fieldset-title'      => 'Restaurer les versions',
'undeleteextrahelp'            => "Pour restaurer l’historique complet de cette page, laissez toutes les cases décochées et cliquez sur '''''Restaurer'''''.
Pour effectuer une restauration partielle, cochez les cases correspondant aux versions à rétablir, puis cliquez sur '''''Restaurer'''''.
Cliquer sur '''''Réinitialiser''''' effacera la boîte de commentaire et réinitialisera toutes les cases à cocher.",
'undeleterevisions'            => '$1 {{PLURAL:$1|version archivée|versions archivées}}',
'undeletehistory'              => 'Si vous restaurez la page, toutes les versions seront replacées dans l’historique.
Si une nouvelle page avec le même nom a été créée depuis la suppression, les versions restaurées apparaîtront dans l’historique antérieur et la version courante ne sera pas automatiquement remplacée.',
'undeleterevdel'               => 'La restauration ne sera pas effectuée si, au final, la version la plus récente de la page ou du fichier reste partiellement supprimée.
Dans de tels cas, vous devez décocher ou démasquer les versions effacées les plus récentes (en tête de liste).',
'undeletehistorynoadmin'       => 'Cette page a été supprimée.
Le motif de la suppression est indiqué dans le résumé ci-dessous, avec les détails des utilisateurs qui l’ont modifié avant sa suppression.
Le contenu effectif de ces versions supprimées n’est accessible qu’aux administrateurs.',
'undelete-revision'            => 'Version supprimée de $1 (version du $4 à $5) par $3 :',
'undeleterevision-missing'     => 'Version incorrecte ou manquante.
Vous avez peut-être un mauvais lien, ou la version a pu être restaurée ou supprimée de l’archive.',
'undelete-nodiff'              => 'Aucune version précédente trouvée.',
'undeletebtn'                  => 'Restaurer',
'undeletelink'                 => 'visualiser/rétablir',
'undeleteviewlink'             => 'voir',
'undeletereset'                => 'Réinitialiser',
'undeleteinvert'               => 'Inverser la sélection',
'undeletecomment'              => 'Motif :',
'undeletedarticle'             => 'a restauré « [[$1]] »',
'undeletedrevisions'           => '$1 {{PLURAL:$1|version restaurée|versions restaurées}}',
'undeletedrevisions-files'     => '$1 version{{PLURAL:$1||s}} et $2 fichier{{PLURAL:$2||s}} restauré{{PLURAL:$2||s}}',
'undeletedfiles'               => '$1 {{PLURAL:$1|fichier restauré|fichiers restaurés}}',
'cannotundelete'               => 'La restauration a échoué ;
un autre utilisateur a probablement déjà restauré la page.',
'undeletedpage'                => "'''La page $1 a été restaurée.'''

Consultez le [[Special:Log/delete|journal des suppressions]] pour obtenir la liste des récentes suppressions et restaurations.",
'undelete-header'              => 'Consultez le [[Special:Log/delete|journal des suppressions]] pour lister les pages récemment supprimées.',
'undelete-search-box'          => 'Rechercher des pages supprimées',
'undelete-search-prefix'       => 'Montrer les pages commençant par :',
'undelete-search-submit'       => 'Rechercher',
'undelete-no-results'          => 'Aucune page correspondante n’a été trouvée dans les archives de suppression.',
'undelete-filename-mismatch'   => 'Impossible de restaurer la version du fichier datée du $1 : le nom de fichier ne correspond pas.',
'undelete-bad-store-key'       => 'Impossible de restaurer la version du fichier datée du $1 : le fichier était absent avant la suppression.',
'undelete-cleanup-error'       => 'Erreur lors de la suppression du fichier d’archive inutilisé « $1 ».',
'undelete-missing-filearchive' => 'Impossible de restaurer le fichier d’archive avec l’identifiant $1 parce qu’il n’est pas dans la base de données.
Il a peut-être déjà été restauré.',
'undelete-error-short'         => 'Erreur lors de la restauration du fichier : $1',
'undelete-error-long'          => 'Des erreurs ont été rencontrées lors de la restauration du fichier :

$1',
'undelete-show-file-confirm'   => 'Êtes-vous sûr de vouloir visionner une version supprimée du fichier « <nowiki>$1</nowiki> » datant du $2 à $3 ?',
'undelete-show-file-submit'    => 'Oui',

# Namespace form on various pages
'namespace'      => 'Espace de noms :',
'invert'         => 'Inverser la sélection',
'blanknamespace' => '(Principal)',

# Contributions
'contributions'       => 'Contributions de l’utilisateur',
'contributions-title' => 'Liste des contributions de l’utilisateur $1',
'mycontris'           => 'Contributions',
'contribsub2'         => 'Pour $1 ($2)',
'nocontribs'          => 'Aucune modification correspondant à ces critères n’a été trouvée.',
'uctop'               => '(dernière)',
'month'               => 'À partir du mois (et précédents) :',
'year'                => 'À partir de l’année (et précédentes) :',

'sp-contributions-newbies'             => 'Ne montrer que les contributions des nouveaux utilisateurs',
'sp-contributions-newbies-sub'         => 'Parmi les nouveaux comptes',
'sp-contributions-newbies-title'       => 'Contributions d’utilisateurs parmi les nouveaux comptes',
'sp-contributions-blocklog'            => 'journal des blocages',
'sp-contributions-deleted'             => 'contributions supprimées',
'sp-contributions-logs'                => 'journaux',
'sp-contributions-talk'                => 'discuter',
'sp-contributions-userrights'          => 'gérer les droits',
'sp-contributions-blocked-notice'      => 'Cet utilisateur est actuellement bloqué. La dernière entrée du journal des blocages est indiquée ci-dessous à titre d’information :',
'sp-contributions-blocked-notice-anon' => 'Cette adresse IP est actuellement bloquée.
La dernière entrée du journal des blocages est indiquée ci-dessous à titre d’information :',
'sp-contributions-search'              => 'Rechercher les contributions',
'sp-contributions-username'            => 'Adresse IP ou nom d’utilisateur :',
'sp-contributions-toponly'             => 'Ne montrer que les révisions récentes',
'sp-contributions-submit'              => 'Rechercher',

# What links here
'whatlinkshere'            => 'Pages liées',
'whatlinkshere-title'      => 'Pages qui pointent vers « $1 »',
'whatlinkshere-page'       => 'Page :',
'linkshere'                => "Les pages ci-dessous contiennent un lien vers '''[[:$1]]''' :",
'nolinkshere'              => "Aucune page ne contient de lien vers '''[[:$1]]'''.",
'nolinkshere-ns'           => "Aucune page ne contient de lien vers '''[[:$1]]''' dans l’espace de noms choisi.",
'isredirect'               => 'page de redirection',
'istemplate'               => 'inclusion',
'isimage'                  => 'fichier lié',
'whatlinkshere-prev'       => '{{PLURAL:$1|précédente|$1 précédentes}}',
'whatlinkshere-next'       => '{{PLURAL:$1|suivante|$1 suivantes}}',
'whatlinkshere-links'      => '← liens',
'whatlinkshere-hideredirs' => '$1 les redirections',
'whatlinkshere-hidetrans'  => '$1 les inclusions',
'whatlinkshere-hidelinks'  => '$1 les liens',
'whatlinkshere-hideimages' => '$1 les fichiers liés',
'whatlinkshere-filters'    => 'Filtres',

# Block/unblock
'blockip'                         => 'Bloquer l’utilisateur',
'blockip-title'                   => 'Bloquer l’utilisateur',
'blockip-legend'                  => 'Bloquer l’utilisateur',
'blockiptext'                     => 'Utilisez le formulaire ci-dessous pour bloquer l’accès aux modifications faites à partir d’une adresse IP spécifique ou d’un nom d’utilisateur.
Une telle mesure ne devrait être prise que pour prévenir le vandalisme et en accord avec les [[{{MediaWiki:Policy-url}}|règles internes]].
Donnez ci-dessous un motif précis (par exemple en citant les pages qui ont été vandalisées).',
'ipaddress'                       => 'Adresse IP :',
'ipadressorusername'              => 'Adresse IP ou nom d’utilisateur :',
'ipbexpiry'                       => 'Durée avant expiration :',
'ipbreason'                       => 'Motif :',
'ipbreasonotherlist'              => 'Autre motif',
'ipbreason-dropdown'              => '* Motifs de blocage les plus fréquents
** Insertion de fausses informations
** Suppression injustifiée de contenu des pages
** Insertion répétée de liens externes publicitaires (spam)
** Insertion de contenu sans aucun sens et de déchets dans les pages
** Tentative d’intimidation ou harcèlement
** Abus d’utilisation de comptes multiples
** Nom d’utilisateur inacceptable, injurieux ou diffamant',
'ipbanononly'                     => 'Bloquer uniquement les utilisateurs anonymes',
'ipbcreateaccount'                => 'Empêcher la création de compte',
'ipbemailban'                     => 'Empêcher l’utilisateur d’envoyer des courriels',
'ipbenableautoblock'              => 'Bloquer automatiquement la dernière adresse IP utilisée par l’utilisateur et toutes ses IPs ultérieures qu’il pourrait essayer',
'ipbsubmit'                       => 'Bloquer cet utilisateur',
'ipbother'                        => 'Autre durée :',
'ipboptions'                      => '2 heures:2 hours,1 jour:1 day,3 jours:3 days,1 semaine:1 week,2 semaines:2 weeks,1 mois:1 month,3 mois:3 months,6 mois:6 months,1 an:1 year,indéfiniment:infinite',
'ipbotheroption'                  => 'autre',
'ipbotherreason'                  => 'Motif différent ou supplémentaire :',
'ipbhidename'                     => 'Masquer le nom d’utilisateur des modifications et des listes',
'ipbwatchuser'                    => 'Suivre les pages utilisateur et de discussion de cet utilisateur',
'ipballowusertalk'                => 'Permet à cet utilisateur de modifier sa propre page de discussion durant sa période de blocage',
'ipb-change-block'                => 'Bloquer à nouveau cet utilisateur avec ces paramètres',
'badipaddress'                    => 'Adresse IP incorrecte',
'blockipsuccesssub'               => 'Blocage réussi',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] a été bloqué.<br />
Consultez la [[Special:IPBlockList|liste des adresses IP et comptes bloqués]] pour réviser les blocages.',
'ipb-edit-dropdown'               => 'Modifier les motifs de blocage par défaut',
'ipb-unblock-addr'                => 'Débloquer $1',
'ipb-unblock'                     => 'Débloquer un compte utilisateur ou une adresse IP',
'ipb-blocklist-addr'              => 'Blocages existants pour $1',
'ipb-blocklist'                   => 'Voir les blocages existants',
'ipb-blocklist-contribs'          => 'Contributions pour $1',
'unblockip'                       => 'Débloquer un utilisateur ou une adresse IP',
'unblockiptext'                   => 'Utilisez le formulaire ci-dessous pour rétablir l’accès aux modifications depuis une adresse IP ou un nom d’utilisateur.',
'ipusubmit'                       => 'Supprimer ce blocage',
'unblocked'                       => '[[User:$1|$1]] a été débloqué',
'unblocked-id'                    => 'Le blocage $1 a été enlevé',
'ipblocklist'                     => 'Adresses IP et utilisateurs bloqués',
'ipblocklist-legend'              => 'Chercher un utilisateur bloqué',
'ipblocklist-username'            => 'Nom de l’utilisateur ou adresse IP :',
'ipblocklist-sh-userblocks'       => '$1 les blocages de comptes',
'ipblocklist-sh-tempblocks'       => '$1 les blocages temporaires',
'ipblocklist-sh-addressblocks'    => '$1 les blocages d’adresses IP uniques',
'ipblocklist-submit'              => 'Rechercher',
'ipblocklist-localblock'          => 'Blocage local',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Autre blocage|Autres blocages}}',
'blocklistline'                   => '$1 : $2 a bloqué $3 ; $4',
'infiniteblock'                   => 'permanent',
'expiringblock'                   => 'expire le $1 à $2',
'anononlyblock'                   => 'utilisateur non enregistré uniquement',
'noautoblockblock'                => 'blocage automatique désactivé',
'createaccountblock'              => 'création de compte bloquée',
'emailblock'                      => 'courriel bloqué',
'blocklist-nousertalk'            => 'ne peut modifier sa propre page de discussion',
'ipblocklist-empty'               => 'La liste des adresses IP bloquées est actuellement vide.',
'ipblocklist-no-results'          => 'L’adresse IP ou l’utilisateur demandé n’est pas bloqué.',
'blocklink'                       => 'bloquer',
'unblocklink'                     => 'débloquer',
'change-blocklink'                => 'modifier le blocage',
'contribslink'                    => 'contributions',
'autoblocker'                     => 'Vous avez été bloqué automatiquement parce que votre adresse IP a été récemment utilisée par « [[User:$1|$1]] ».
Le motif fourni pour le blocage de $1 est : « $2 ».',
'blocklogpage'                    => 'Journal des blocages',
'blocklog-showlog'                => 'Cet utilisateur a été bloqué précédemment. Le journal des blocages est disponible ci-dessous :',
'blocklog-showsuppresslog'        => 'Cet utilisateur a été bloqué et caché précédemment. Le journal des suppressions est disponible ci-dessous :',
'blocklogentry'                   => 'a bloqué [[$1]] ; expiration : $2 $3',
'reblock-logentry'                => 'a modifié les paramètres du blocage de [[$1]] avec une expiration au $2 $3',
'blocklogtext'                    => 'Ceci est le journal des actions de blocages et déblocages d’utilisateurs.
Les adresses IP automatiquement bloquées ne sont pas listées.
Consultez la [[Special:IPBlockList|liste des IP bloquées]] pour voir les bannissements et blocages effectivement en cours.',
'unblocklogentry'                 => 'a débloqué $1',
'block-log-flags-anononly'        => 'utilisateurs anonymes seulement',
'block-log-flags-nocreate'        => 'création de compte interdite',
'block-log-flags-noautoblock'     => 'autoblocage des IP désactivé',
'block-log-flags-noemail'         => 'envoi de courriel interdit',
'block-log-flags-nousertalk'      => 'ne peut modifier sa propre page de discussion',
'block-log-flags-angry-autoblock' => 'autoblocage amélioré activé',
'block-log-flags-hiddenname'      => 'nom d’utilisateur caché',
'range_block_disabled'            => 'Le droit administrateur de créer des blocages de plages IP est désactivé.',
'ipb_expiry_invalid'              => 'Durée d’expiration incorrecte.',
'ipb_expiry_temp'                 => 'Les blocages de noms d’utilisateurs cachés doivent être permanents.',
'ipb_hide_invalid'                => 'Impossible de supprimer ce compte ; il semble avoir trop de modifications.',
'ipb_already_blocked'             => '« $1 » est déjà bloqué',
'ipb-needreblock'                 => '== Déjà bloqué ==
$1 est déjà bloqué. Voulez-vous modifier les paramètres ?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Autre blocage|Autres blocages}}',
'ipb_cant_unblock'                => 'Erreur : identifiant de blocage $1 non trouvé.
Il est possible qu’un déblocage ait déjà été effectué.',
'ipb_blocked_as_range'            => 'Erreur : l’adresse IP $1 n’est pas bloquée directement et ne peut donc pas être débloquée.
Elle fait cependant partie de la plage $2 qui, elle, peut être débloquée.',
'ip_range_invalid'                => 'Plage IP incorrecte.',
'ip_range_toolarge'               => 'Les blocages de plages plus grandes que /$1 ne sont pas autorisées.',
'blockme'                         => 'Bloquez-moi',
'proxyblocker'                    => 'Bloqueur de mandataires',
'proxyblocker-disabled'           => 'Cette fonction est désactivée.',
'proxyblockreason'                => 'Votre adresse IP a été bloquée car il s’agit d’un mandataire ouvert.
Veuillez contacter votre fournisseur d’accès Internet ou votre support technique et l’informer de ce sérieux problème de sécurité.',
'proxyblocksuccess'               => 'Fait.',
'sorbsreason'                     => 'Votre adresse IP est listée comme mandataire ouvert dans le DNSBL utilisé par {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Votre adresse IP est listée comme mandataire ouvert dans le DNSBL utilisé par {{SITENAME}}.
Vous ne pouvez pas créer un compte.',
'cant-block-while-blocked'        => 'Vous ne pouvez pas bloquer d’autres utilisateurs tant que vous êtes bloqué{{GENDER:||e|(e)}}.',
'cant-see-hidden-user'            => "L’utilisateur que vous tentez de bloquer a déjà été bloqué et masqué. N’ayant pas le droit ''hideuser'', vous ne pouvez pas voir ou modifier le blocage de cet utilisateur.",
'ipbblocked'                      => "Vous ne pouvez pas bloquer ou débloquer d'autres utilisateurs, parce que vous êtes vous-même bloqué",
'ipbnounblockself'                => "Vous n'êtes pas autorisé à vous débloquer vous-même",

# Developer tools
'lockdb'              => 'Verrouiller la base de données',
'unlockdb'            => 'Déverrouiller la base de données',
'lockdbtext'          => 'Le verrouillage de la base de données empêchera tous les utilisateurs de modifier des pages, d’enregistrer leurs préférences, de modifier leur liste de suivi et d’effectuer toutes les autres opérations nécessitant des changements dans la base de données.
Veuillez confirmer que c’est bien là ce que vous voulez faire et que vous déverrouillerez la base dès que votre opération de maintenance sera terminée.',
'unlockdbtext'        => 'Le déverrouillage de la base de données permettra à nouveau à tous les utilisateurs de modifier des pages, de changer leurs préférences, de modifier leur liste de suivi et d’effectuer les autres opérations nécessitant des changements dans la base de données.
Veuillez confirmer que c’est bien là ce que vous voulez faire.',
'lockconfirm'         => 'Oui, je confirme que je souhaite verrouiller la base de données.',
'unlockconfirm'       => 'Oui, je confirme que je souhaite déverrouiller la base de données.',
'lockbtn'             => 'Verrouiller la base de données',
'unlockbtn'           => 'Déverrouiller la base de données',
'locknoconfirm'       => 'Vous n’avez pas coché la case de confirmation.',
'lockdbsuccesssub'    => 'Verrouillage de la base de données réussi',
'unlockdbsuccesssub'  => 'Verrouillage de la base de données supprimé',
'lockdbsuccesstext'   => 'La base de données a été verrouillée.<br />
N’oubliez pas de la [[Special:UnlockDB|déverrouiller]] lorsque vous aurez terminé votre opération de maintenance.',
'unlockdbsuccesstext' => 'La base de données a été déverrouillée.',
'lockfilenotwritable' => 'Le fichier de verrouillage de la base de données n’est pas inscriptible.
Pour bloquer ou débloquer la base de données, il doit être accessible par le serveur web.',
'databasenotlocked'   => 'La base de données n’est pas verrouillée.',

# Move page
'move-page'                    => 'Renommer $1',
'move-page-legend'             => 'Renommer une page',
'movepagetext'                 => "Utilisez le formulaire ci-dessous pour renommer une page, en déplaçant tout son historique vers le nouveau nom. L’ancien titre deviendra une page de redirection vers le nouveau titre. Vous pouvez mettre à jour automatiquement les redirections actuelles qui pointent vers le titre original. Si vous choisissez de ne pas le faire, asssurez-vous de vérifier toute [[Special:DoubleRedirects|double redirection]] ou [[Special:BrokenRedirects|redirection cassée]]. Vous avez la responsabilité de vous assurer que les liens continuent de pointer vers leur destination supposée.

Notez que la page ne sera '''pas''' déplacée s’il existe déjà une page avec le nouveau titre, sauf si cette dernière a un historique de modifications vierge et est soit vide, soit une simple redirection. Ceci permet de renommer une page vers sa position d’origine si le déplacement s’avère erroné.

'''ATTENTION !'''
Ceci peut provoquer un changement radical et imprévu pour une page souvent consultée ; assurez-vous d’en avoir compris les conséquences avant de continuer.",
'movepagetalktext'             => "La page de discussion associée, si présente, sera automatiquement renommée '''sauf si :'''
* vous déplacez la page vers un autre espace de noms, ou
* une page de discussion non vide existe déjà sous le nouveau nom, ou
* vous décochez la case ci-dessous.

Dans ces cas-là, vous devrez renommer ou fusionner cette page de discussion manuellement si vous le désirez.",
'movearticle'                  => 'Renommer la page :',
'moveuserpage-warning'         => "'''Attention :''' Vous êtes sur le point de renommer une page d’utilisateur. Veuillez noter que seul la page sera renommée et que l’utilisateur '''ne''' sera '''pas''' renommé.",
'movenologin'                  => 'Vous n’êtes pas identifié{{GENDER:||e|(e)}}.',
'movenologintext'              => 'Pour pouvoir renommer une page, vous devez être [[Special:UserLogin|identifié{{GENDER:||e|(e)}}]] avec un compte utilisateur enregistré et d’ancienneté suffisante.',
'movenotallowed'               => 'Vous n’avez pas la permission de renommer les pages.',
'movenotallowedfile'           => 'Vous n’avez pas la permission de renommer les fichiers.',
'cant-move-user-page'          => 'Vous n’avez pas la permission de renommer les pages principales d’utilisateurs (en dehors de leurs sous-pages).',
'cant-move-to-user-page'       => 'Vous n’avez pas la permission de renommer une page vers une page utilisateur (à l’exception d’une sous-page).',
'newtitle'                     => 'Vers le nouveau titre :',
'move-watch'                   => 'Suivre les pages originale et nouvelle',
'movepagebtn'                  => 'Renommer la page',
'pagemovedsub'                 => 'Déplacement réussi',
'movepage-moved'               => "'''« $1 »''' a été renommé '''« $2 »'''",
'movepage-moved-redirect'      => 'Une redirection depuis l’ancien nom a été créée.',
'movepage-moved-noredirect'    => 'La création d’une redirection depuis l’ancien nom a été annulée.',
'articleexists'                => 'Il existe déjà une page portant ce titre, ou le titre que vous avez choisi n’est pas correct.
Veuillez en choisir un autre.',
'cantmove-titleprotected'      => 'Vous ne pouvez pas déplacer une page vers cet emplacement car la création de page avec ce nouveau titre a été protégée.',
'talkexists'                   => "'''La page elle-même a été déplacée avec succès, mais la page de discussion n’a pas pu être déplacée car il en existait déjà une sous le nouveau nom. Veuillez les fusionner manuellement.'''",
'movedto'                      => 'renommé en',
'movetalk'                     => 'Renommer aussi la page de discussion associée',
'move-subpages'                => 'Renommer les sous-pages (jusqu’à $1 pages)',
'move-talk-subpages'           => 'Renommer les sous-pages de la page de discussion (jusqu’à $1 pages)',
'movepage-page-exists'         => 'La page $1 existe déjà et ne peut pas être écrasée automatiquement.',
'movepage-page-moved'          => 'La page $1 a été renommée en $2.',
'movepage-page-unmoved'        => 'La page $1 n’a pas pu être renommée en $2.',
'movepage-max-pages'           => 'Le maximum de $1 {{PLURAL:$1|page renommée|pages renommées}} a été atteint et aucune autre page ne sera renommée automatiquement.',
'1movedto2'                    => 'a déplacé [[$1]] vers [[$2]]',
'1movedto2_redir'              => 'a déplacé [[$1]] vers [[$2]] en écrasant sa redirection',
'move-redirect-suppressed'     => 'redirection supprimée',
'movelogpage'                  => 'Journal des déplacements',
'movelogpagetext'              => 'Voici la liste de toutes les pages renommées ou déplacées.',
'movesubpage'                  => 'Sous-page{{PLURAL:$1||s}}',
'movesubpagetext'              => 'Cette page a $1 {{PLURAL:$1|sous-page affichée|sous-pages affichées}} ci-dessous.',
'movenosubpage'                => 'Cette page n’a aucune sous-page.',
'movereason'                   => 'Motif :',
'revertmove'                   => 'rétablir',
'delete_and_move'              => 'Supprimer et déplacer',
'delete_and_move_text'         => '== Suppression nécessaire ==
La page de destination « [[:$1]] » existe déjà.
Êtes-vous certain de vouloir la supprimer pour permettre ce déplacement ?',
'delete_and_move_confirm'      => 'Oui, supprimer la page de destination',
'delete_and_move_reason'       => 'Page supprimée pour permettre un déplacement',
'selfmove'                     => 'Les titres d’origine et de destination sont les mêmes ;
impossible de renommer une page sur elle-même.',
'immobile-source-namespace'    => 'Vous ne pouvez pas renommer les pages dans l’espace de noms « $1 »',
'immobile-target-namespace'    => 'Vous ne pouvez pas renommer des pages vers l’espace de noms « $1 »',
'immobile-target-namespace-iw' => 'Les destinations interwikis ne sont pas une cible valide pour les déplacements.',
'immobile-source-page'         => 'Cette page n’est pas renommable.',
'immobile-target-page'         => 'Il n’est pas possible de renommer la page vers ce titre.',
'imagenocrossnamespace'        => 'Impossible de renommer un fichier vers un espace de noms autre que fichier.',
'nonfile-cannot-move-to-file'  => "Impossible de renommer quelque chose d'autre qu'un fichier vers l'espace de noms fichier.",
'imagetypemismatch'            => 'La nouvelle extension de ce fichier ne correspond pas à son type.',
'imageinvalidfilename'         => 'Le nom du fichier cible est incorrect',
'fix-double-redirects'         => 'Mettre à jour les redirections pointant vers le titre original',
'move-leave-redirect'          => 'Laisser une redirection vers le nouveau titre',
'protectedpagemovewarning'     => "'''Attention :''' Cette page a été protégée afin que seuls les utilisateurs possédant les droits d’administrateur puissent la renommer. La dernière entrée du journal est affichée ci-dessous pour référence :",
'semiprotectedpagemovewarning' => "'''Note :''' Cette page a été protégée afin que seuls les utilisateurs enregistrés puissent la renommer. La dernière entrée du journal est affichée ci-dessous pour référence :",
'move-over-sharedrepo'         => '== Le fichier existe ==
[[:$1]] existe déjà sur un dépôt partagé. Renommer ce fichier rendra le fichier sur le dépôt partage inaccessible.',
'file-exists-sharedrepo'       => 'Le nom choisi est déjà utilisé par un fichier sur un dépôt partagé.
Choisissez un autre nom.',

# Export
'export'            => 'Exporter des pages',
'exporttext'        => 'Vous pouvez exporter en XML le texte et l’historique d’une page ou d’un ensemble de pages ;
le résultat peut alors être importé dans un autre wiki utilisant le logiciel MediaWiki via la [[Special:Import|page d’importation]].

Pour exporter des pages, entrez leurs titres dans la boîte de texte ci-dessous, à raison d’un titre par ligne. Sélectionnez si vous désirez ou non la version actuelle avec toutes les anciennes versions, avec les lignes de l’historique de la page, ou simplement la page actuelle avec des informations sur la dernière modification.

Dans ce dernier cas vous pouvez aussi utiliser un lien, tel que [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pour la page [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Exporter uniquement la version courante, sans l’historique complet',
'exportnohistory'   => "----
'''Note :''' l’exportation de l’historique complet des pages à l’aide de ce formulaire a été désactivée pour des raisons de performance.",
'export-submit'     => 'Exporter',
'export-addcattext' => 'Ajouter les pages de la catégorie :',
'export-addcat'     => 'Ajouter',
'export-addnstext'  => 'Ajouter des pages dans l’espace de noms :',
'export-addns'      => 'Ajouter',
'export-download'   => 'Enregistrer dans un fichier',
'export-templates'  => 'Inclure les modèles',
'export-pagelinks'  => 'Inclure les pages liées à une profondeur de :',

# Namespace 8 related
'allmessages'                   => 'Messages système',
'allmessagesname'               => 'Nom du message',
'allmessagesdefault'            => 'Message par défaut',
'allmessagescurrent'            => 'Message actuel',
'allmessagestext'               => 'Ceci est la liste des messages disponibles dans l’espace MediaWiki.
Veuillez visiter la [http://www.mediawiki.org/wiki/Localisation Localisation de MediaWiki] et [http://translatewiki.net/ translatewiki.net] si vous désirez contribuer à la localisation générique de MediaWiki.',
'allmessagesnotsupportedDB'     => "Cette page '''{{ns:special}}:Allmessages''' n’est pas utilisable car '''\$wgUseDatabaseMessages''' a été désactivé.",
'allmessages-filter-legend'     => 'Filtrer',
'allmessages-filter'            => 'Filtrer par état de modification :',
'allmessages-filter-unmodified' => 'Non modifié',
'allmessages-filter-all'        => 'Tous',
'allmessages-filter-modified'   => 'Modifié',
'allmessages-prefix'            => 'Filtrer par préfixe :',
'allmessages-language'          => 'Langue :',
'allmessages-filter-submit'     => 'Appliquer',

# Thumbnails
'thumbnail-more'           => 'Agrandir',
'filemissing'              => 'Fichier manquant',
'thumbnail_error'          => 'Erreur lors de la création de la miniature : $1',
'djvu_page_error'          => 'Page DjVu hors limites',
'djvu_no_xml'              => 'Impossible de récupérer le XML pour le fichier DjVu',
'thumbnail_invalid_params' => 'Paramètres de la miniature incorrects',
'thumbnail_dest_directory' => 'Impossible de créer le répertoire de destination',
'thumbnail_image-type'     => 'Type d’image non supporté',
'thumbnail_gd-library'     => 'Configuration incomplète de la bibliothèque GD : fonction $1 introuvable',
'thumbnail_image-missing'  => 'Le fichier suivant est introuvable : $1',

# Special:Import
'import'                     => 'Importer des pages',
'importinterwiki'            => 'Importation inter-wiki',
'import-interwiki-text'      => 'Sélectionnez un wiki et un titre de page à importer.
Les dates des versions et les noms des contributeurs seront préservés.
Toutes les actions d’importation inter-wiki sont consignées dans l’[[Special:Log/import|historique des importations]].',
'import-interwiki-source'    => 'Wiki et page sources :',
'import-interwiki-history'   => 'Copier toutes les versions de l’historique de cette page',
'import-interwiki-templates' => 'Inclure tous les modèles',
'import-interwiki-submit'    => 'Importer',
'import-interwiki-namespace' => 'Espace de noms de destination :',
'import-upload-filename'     => 'Nom du fichier :',
'import-comment'             => 'Commentaire :',
'importtext'                 => 'Veuillez exporter le fichier depuis le wiki d’origine en utilisant son [[Special:Export|outil d’exportation]].
Sauvegardez-le sur votre disque dur puis téléversez-le ici.',
'importstart'                => 'Importation des pages…',
'import-revision-count'      => '$1 version{{PLURAL:$1||s}}',
'importnopages'              => 'Aucune page à importer.',
'imported-log-entries'       => '$1 {{PLURAL:$1|entrée|entrées}} du journal {{PLURAL:$1|importée|importées}}.',
'importfailed'               => 'Échec de l’importation : <nowiki>$1</nowiki>',
'importunknownsource'        => 'Type inconnu de la source à importer',
'importcantopen'             => 'Impossible d’ouvrir le fichier à importer',
'importbadinterwiki'         => 'Mauvais lien inter-wiki',
'importnotext'               => 'Vide ou sans texte',
'importsuccess'              => 'L’importation a réussi !',
'importhistoryconflict'      => 'Un conflit a été détecté dans l’historique des versions (cette page a pu être importée auparavant).',
'importnosources'            => 'Aucune source d’importation inter-wiki n’a été définie et les téléversements directs d’historiques sont désactivés.',
'importnofile'               => 'Aucun fichier d’importation n’a été téléversé.',
'importuploaderrorsize'      => 'Le téléversement du fichier à importer a échoué.
Sa taille est supérieure au maximum autorisé pour les téléversements.',
'importuploaderrorpartial'   => 'Le téléversement du fichier à importer a échoué.
Son contenu n’a été transféré que partiellement.',
'importuploaderrortemp'      => 'Le téléversement du fichier à importer a échoué.
Un dossier temporaire est manquant.',
'import-parse-failure'       => 'Échec lors de l’analyse du XML à importer',
'import-noarticle'           => 'Aucune page à importer !',
'import-nonewrevisions'      => 'Toutes les versions ont été importées auparavant.',
'xml-error-string'           => '$1 à la ligne $2, colonne $3 (octet $4) : $5',
'import-upload'              => 'Téléversement de données XML',
'import-token-mismatch'      => 'Perte des données de session. Veuillez réessayez.',
'import-invalid-interwiki'   => 'Impossible d’importer depuis le wiki spécifié.',

# Import log
'importlogpage'                    => 'Journal des importations',
'importlogpagetext'                => 'Importations administratives de pages avec leur historique de modifications depuis d’autres wikis.',
'import-logentry-upload'           => 'a importé [[$1]] par téléversement de fichier',
'import-logentry-upload-detail'    => '$1 version{{PLURAL:$1||s}}',
'import-logentry-interwiki'        => 'a importé $1 par transwiki',
'import-logentry-interwiki-detail' => '$1 version{{PLURAL:$1||s}} depuis $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Votre page utilisateur',
'tooltip-pt-anonuserpage'         => 'La page utilisateur de l’IP avec laquelle vous contribuez',
'tooltip-pt-mytalk'               => 'Votre page de discussion',
'tooltip-pt-anontalk'             => 'La page de discussion pour les contributions depuis cette adresse IP',
'tooltip-pt-preferences'          => 'Vos préférences',
'tooltip-pt-watchlist'            => 'La liste des pages dont vous suivez les modifications',
'tooltip-pt-mycontris'            => 'La liste de vos contributions',
'tooltip-pt-login'                => 'Vous êtes encouragé{{GENDER:||e|(e)}} à vous identifier ; ce n’est cependant pas obligatoire.',
'tooltip-pt-anonlogin'            => 'Vous êtes encouragé{{GENDER:||e|(e)}} à vous identifier ; ce n’est cependant pas obligatoire.',
'tooltip-pt-logout'               => 'Se déconnecter',
'tooltip-ca-talk'                 => 'Discussion au sujet de cette page de contenu',
'tooltip-ca-edit'                 => 'Vous pouvez modifier cette page.
Veuillez utiliser le bouton de prévisualisation avant d’enregistrer.',
'tooltip-ca-addsection'           => 'Commencer une nouvelle section',
'tooltip-ca-viewsource'           => 'Cette page est protégée.
Vous pouvez toutefois en visualiser la source.',
'tooltip-ca-history'              => 'Les versions passées de cette page (avec leurs contributeurs)',
'tooltip-ca-protect'              => 'Protéger cette page',
'tooltip-ca-unprotect'            => 'Déprotéger cette page',
'tooltip-ca-delete'               => 'Supprimer cette page',
'tooltip-ca-undelete'             => 'Rétablir les modifications faites sur cette page avant sa suppression',
'tooltip-ca-move'                 => 'Renommer cette page',
'tooltip-ca-watch'                => 'Ajouter cette page à votre liste de suivi',
'tooltip-ca-unwatch'              => 'Retirer cette page de votre liste de suivi',
'tooltip-search'                  => 'Rechercher dans {{SITENAME}}',
'tooltip-search-go'               => 'Aller vers une page portant exactement ce nom si elle existe.',
'tooltip-search-fulltext'         => 'Rechercher les pages comportant ce texte.',
'tooltip-p-logo'                  => 'Page principale',
'tooltip-n-mainpage'              => 'Visiter la page d’accueil du site',
'tooltip-n-mainpage-description'  => 'Aller à l’accueil',
'tooltip-n-portal'                => 'À propos du projet',
'tooltip-n-currentevents'         => 'Trouver les informations de fond sur l’actualité du moment',
'tooltip-n-recentchanges'         => 'Liste des modifications récentes sur le wiki',
'tooltip-n-randompage'            => 'Afficher une page au hasard',
'tooltip-n-help'                  => 'Aide',
'tooltip-t-whatlinkshere'         => 'Liste des pages liées à celle-ci',
'tooltip-t-recentchangeslinked'   => 'Liste des modifications récentes des pages liées à celle-ci',
'tooltip-feed-rss'                => 'Flux RSS pour cette page',
'tooltip-feed-atom'               => 'Flux Atom pour cette page',
'tooltip-t-contributions'         => 'Voir la liste des contributions de cet utilisateur',
'tooltip-t-emailuser'             => 'Envoyer un courriel à cet utilisateur',
'tooltip-t-upload'                => 'Envoyer une image ou fichier média sur le serveur',
'tooltip-t-specialpages'          => 'Liste de toutes les pages spéciales',
'tooltip-t-print'                 => 'Version imprimable de cette page',
'tooltip-t-permalink'             => 'Lien permanent vers cette version de la page',
'tooltip-ca-nstab-main'           => 'Voir la page de contenu',
'tooltip-ca-nstab-user'           => 'Voir la page utilisateur',
'tooltip-ca-nstab-media'          => 'Voir la page du média',
'tooltip-ca-nstab-special'        => 'Ceci est une page spéciale, vous ne pouvez pas la modifier.',
'tooltip-ca-nstab-project'        => 'Voir la page du projet',
'tooltip-ca-nstab-image'          => 'Voir la page du fichier',
'tooltip-ca-nstab-mediawiki'      => 'Voir le message système',
'tooltip-ca-nstab-template'       => 'Voir le modèle',
'tooltip-ca-nstab-help'           => 'Voir la page d’aide',
'tooltip-ca-nstab-category'       => 'Voir la page de la catégorie',
'tooltip-minoredit'               => 'Marquer mes modifications comme mineures',
'tooltip-save'                    => 'Publier vos modifications',
'tooltip-preview'                 => 'Merci de prévisualiser vos modifications avant de les publier',
'tooltip-diff'                    => 'Permet de visualiser les changements que vous avez effectués',
'tooltip-compareselectedversions' => 'Afficher les différences entre deux versions de cette page',
'tooltip-watch'                   => 'Ajouter cette page à votre liste de suivi',
'tooltip-recreate'                => 'Recréer la page même si celle-ci a été effacée',
'tooltip-upload'                  => 'Démarrer le téléversement',
'tooltip-rollback'                => '« Révoquer » annule en un clic la ou les modification(s) de cette page par son dernier contributeur.',
'tooltip-undo'                    => '« Défaire » révoque cette modification et ouvre la fenêtre de modification en mode prévisualisation.
Permet de rétablir la version précédente et d’ajouter un motif dans la boîte de résumé.',
'tooltip-preferences-save'        => 'Sauvegarder les préférences',
'tooltip-summary'                 => 'Entrez un bref résumé',

# Stylesheets
'common.css'      => '/* Le CSS placé ici sera appliqué à tous les habillages. */',
'standard.css'    => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Standard. */',
'nostalgia.css'   => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Nostalgia. */',
'cologneblue.css' => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Cologne Blue. */',
'monobook.css'    => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Monobook. */',
'myskin.css'      => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Myskin. */',
'chick.css'       => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Chick. */',
'simple.css'      => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Simple. */',
'modern.css'      => '/* Le CSS placé ici affectera les utilisateurs de l’habillage Modern. */',
'print.css'       => '/* Le CSS placé ici affectera les impressions */',
'handheld.css'    => '/* Le CSS placé ici affectera les appareils mobiles en fonction de l\'habillage configuré $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Tout JavaScript ici sera chargé avec chaque page accédée par n’importe quel utilisateur. */',
'standard.js'    => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage Standard uniquement */',
'nostalgia.js'   => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage Nostalgie uniquement */',
'cologneblue.js' => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage Bleu de cologne uniquement */',
'monobook.js'    => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage MonoBook uniquement. */',
'myskin.js'      => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage Mon habillage uniquement */',
'chick.js'       => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage Poussin uniquement */',
'simple.js'      => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage Simple uniquement */',
'modern.js'      => '/* Tout JavaScript ici sera chargé avec les pages accédées par les utilisateurs de l’habillage Moderne uniquement */',

# Metadata
'nodublincore'      => 'Les métadonnées « Dublin Core RDF » sont désactivées sur ce serveur.',
'nocreativecommons' => 'Les métadonnées « Creative Commons RDF » sont désactivées sur ce serveur.',
'notacceptable'     => 'Ce serveur wiki ne peut pas fournir les données dans un format que votre client soit capable de lire.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Utilisateur non enregistré|Utilisateurs non enregistrés}} sur {{SITENAME}}',
'siteuser'         => '{{GENDER:$2|l’utilisateur|l’utilisatrice|l’utilisateur}} $1 de {{SITENAME}}',
'anonuser'         => 'l’utilisateur anonyme $1 de {{SITENAME}}',
'lastmodifiedatby' => 'Cette page a été modifiée pour la dernière fois le $1 à $2 par $3.',
'othercontribs'    => 'Basé sur le travail de $1.',
'others'           => 'autres',
'siteusers'        => '{{PLURAL:$2|l’utilisateur|les utilisateurs}} $1 de {{SITENAME}}',
'anonusers'        => '{{PLURAL:$2|l’utilisateur anonyme|les utilisateurs anonymes}} $1 de {{SITENAME}}',
'creditspage'      => 'Crédits de la page',
'nocredits'        => 'Il n’y a pas d’informations d’attribution disponibles pour cette page.',

# Spam protection
'spamprotectiontitle' => 'Filtre de protection anti-pollution',
'spamprotectiontext'  => 'La page que vous avez voulu sauvegarder a été bloquée par le filtre anti-pollution. Ceci est probablement dû à l’introduction d’un lien vers un site externe apparaissant sur la liste noire. Cette dernière utilise les expressions rationnelles suivantes :',
'spamprotectionmatch' => "La chaîne de caractères « '''$1''' » a déclenché le détecteur de pourriel.",
'spambot_username'    => 'Nettoyage de pourriels par MediaWiki',
'spam_reverting'      => 'Rétablissement de la dernière version ne contenant pas de lien vers $1',
'spam_blanking'       => 'Toutes les versions contenant des liens vers $1 sont blanchies',

# Info page
'infosubtitle'   => 'Informations pour la page',
'numedits'       => 'Nombre de modifications : $1',
'numtalkedits'   => 'Nombre de modifications (page de discussion) : $1',
'numwatchers'    => 'Nombre de contributeurs ayant la page dans leur liste de suivi : $1',
'numauthors'     => 'Nombre d’auteurs distincts : $1',
'numtalkauthors' => 'Nombre d’auteurs distincts (page de discussion) : $1',

# Skin names
'skinname-standard'    => 'Standard',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Bleu de Cologne',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'Mon Interface',
'skinname-chick'       => 'Poussin',
'skinname-simple'      => 'Simple',
'skinname-modern'      => 'Moderne',
'skinname-vector'      => 'Vector',

# Math options
'mw_math_png'    => 'Toujours produire une image PNG',
'mw_math_simple' => 'HTML si très simple, autrement PNG',
'mw_math_html'   => 'HTML si possible, autrement PNG',
'mw_math_source' => 'Laisser le code TeX original',
'mw_math_modern' => 'Pour les navigateurs modernes',
'mw_math_mathml' => 'MathML',

# Math errors
'math_failure'          => 'Erreur math',
'math_unknown_error'    => 'erreur indéterminée',
'math_unknown_function' => 'fonction inconnue',
'math_lexing_error'     => 'erreur lexicale',
'math_syntax_error'     => 'erreur de syntaxe',
'math_image_error'      => 'La conversion en PNG a échoué ; vérifiez l’installation de LaTeX, dvips, gs et convert',
'math_bad_tmpdir'       => 'Impossible de créer ou d’écrire dans le répertoire math temporaire',
'math_bad_output'       => 'Impossible de créer ou d’écrire dans le répertoire math de sortie',
'math_notexvc'          => 'L’exécutable « texvc » est introuvable. Lisez math/README pour le configurer.',

# Patrolling
'markaspatrolleddiff'                 => 'Marquer comme patrouillée',
'markaspatrolledtext'                 => 'Marquer cette page comme patrouillée',
'markedaspatrolled'                   => 'Marqué comme patrouillé',
'markedaspatrolledtext'               => 'La révision sélectionnée de [[:$1]] a été marquée comme patrouillée.',
'rcpatroldisabled'                    => 'La fonction de patrouille des modifications récentes n’est pas activée.',
'rcpatroldisabledtext'                => 'La fonctionnalité de patrouille des modifications récentes est actuellement désactivée.',
'markedaspatrollederror'              => 'Ne peut être marquée comme patrouillée',
'markedaspatrollederrortext'          => 'Vous devez sélectionner une version pour pouvoir la marquer comme patrouillée.',
'markedaspatrollederror-noautopatrol' => 'Vous n’avez pas le droit de marquer vos propres modifications comme patrouillées.',

# Patrol log
'patrol-log-page'      => 'Journal des modifications patrouillées',
'patrol-log-header'    => 'Voici un journal des modifications patrouillées.',
'patrol-log-line'      => 'a marqué la $1 de $2 comme patrouillée $3',
'patrol-log-auto'      => '(automatique)',
'patrol-log-diff'      => 'modification $1',
'log-show-hide-patrol' => '$1 l’historique des versions patrouillées',

# Image deletion
'deletedrevision'                 => 'Suppression de l’ancienne version $1',
'filedeleteerror-short'           => 'Erreur lors de la suppression du fichier : $1',
'filedeleteerror-long'            => 'Des erreurs ont été rencontrées lors de la suppression du fichier :

$1',
'filedelete-missing'              => 'Le fichier « $1 » ne peut pas être supprimé parce qu’il n’existe pas.',
'filedelete-old-unregistered'     => 'La version du fichier spécifiée « $1 » n’est pas dans la base de données.',
'filedelete-current-unregistered' => 'Le fichier spécifié « $1 » n’est pas dans la base de données.',
'filedelete-archive-read-only'    => 'Le dossier d’archivage « $1 » n’est pas modifiable par le serveur.',

# Browsing diffs
'previousdiff' => '← Modification précédente',
'nextdiff'     => 'Modification suivante →',

# Media information
'mediawarning'         => "'''Attention :''' ce type de fichier peut contenir du code malveillant.
Si vous l’exécutez, votre système peut être compromis.",
'imagemaxsize'         => "Format maximal des images :<br />''(pour les pages de description d’images)''",
'thumbsize'            => 'Taille de la miniature :',
'widthheightpage'      => '$1 × $2, $3 page{{PLURAL:$3||s}}',
'file-info'            => 'Taille du fichier : $1, type MIME : $2',
'file-info-size'       => '($1 × $2 pixels, taille du fichier : $3, type MIME : $4)',
'file-nohires'         => '<small>Pas de plus haute résolution disponible.</small>',
'svg-long-desc'        => '(Fichier SVG, résolution de $1 × $2 pixels, taille : $3)',
'show-big-image'       => 'Image en plus haute résolution',
'show-big-image-thumb' => '<small>Taille de cet aperçu : $1 × $2 pixels</small>',
'file-info-gif-looped' => 'en boucle',
'file-info-gif-frames' => '$1 {{PLURAL:$1|image|images}}',
'file-info-png-looped' => 'en boucle',
'file-info-png-repeat' => 'joué $1 {{PLURAL:$1|fois|fois}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|image|images}}',

# Special:NewFiles
'newimages'             => 'Galerie des nouveaux fichiers',
'imagelisttext'         => "Voici une liste de '''$1''' fichier{{PLURAL:$1||s}} classée $2.",
'newimages-summary'     => 'Cette page spéciale affiche les derniers fichiers importés.',
'newimages-legend'      => 'Nom du fichier',
'newimages-label'       => 'Nom du fichier (ou une partie de celui-ci) :',
'showhidebots'          => '($1 robots)',
'noimages'              => 'Aucune image à afficher.',
'ilsubmit'              => 'Rechercher',
'bydate'                => 'par date',
'sp-newimages-showfrom' => 'Afficher les nouveaux fichiers à partir du $1 à $2',

# Bad image list
'bad_image_list' => 'Le format est le suivant :

Seules les listes d’énumération (commençant par *) sont prises en compte. Le premier lien d’une ligne doit être celui d’une mauvaise image.
Les autres liens sur la même ligne sont considérés comme des exceptions, par exemple des pages sur lesquelles l’image peut apparaître.',

# Metadata
'metadata'          => 'Métadonnées',
'metadata-help'     => 'Ce fichier contient des informations supplémentaires, probablement ajoutées par l’appareil photo numérique ou le numériseur utilisé pour le créer. Si le fichier a été modifié depuis son état original, certains détails peuvent ne pas refléter entièrement l’image modifiée.',
'metadata-expand'   => 'Montrer les informations détaillées',
'metadata-collapse' => 'Cacher les informations détaillées',
'metadata-fields'   => 'Les champs de métadonnées d’EXIF listés dans ce message seront inclus dans la page de description de l’image quand la table de métadonnées sera réduite. Les autres champs seront cachés par défaut.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Largeur',
'exif-imagelength'                 => 'Hauteur',
'exif-bitspersample'               => 'Bits par composante',
'exif-compression'                 => 'Type de compression',
'exif-photometricinterpretation'   => 'Modèle colorimétrique',
'exif-orientation'                 => 'Orientation',
'exif-samplesperpixel'             => 'Composantes par pixel',
'exif-planarconfiguration'         => 'Arrangement des données',
'exif-ycbcrsubsampling'            => 'Taux de sous-échantillonnage de Y à C',
'exif-ycbcrpositioning'            => 'Positionnement YCbCr',
'exif-xresolution'                 => 'Résolution horizontale',
'exif-yresolution'                 => 'Résolution verticale',
'exif-resolutionunit'              => 'Unité de résolution',
'exif-stripoffsets'                => 'Emplacement des données de l’image',
'exif-rowsperstrip'                => 'Nombre de lignes par bande',
'exif-stripbytecounts'             => 'Taille en octets par bande',
'exif-jpeginterchangeformat'       => 'Position du SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Taille en octets des données JPEG',
'exif-transferfunction'            => 'Fonction de transfert',
'exif-whitepoint'                  => 'Chromaticité du point blanc',
'exif-primarychromaticities'       => 'Chromaticité des primaires',
'exif-ycbcrcoefficients'           => 'Coefficients YCbCr',
'exif-referenceblackwhite'         => 'Valeurs de référence noir et blanc',
'exif-datetime'                    => 'Date de modification',
'exif-imagedescription'            => 'Description de l’image',
'exif-make'                        => 'Fabricant de l’appareil',
'exif-model'                       => 'Modèle de l’appareil',
'exif-software'                    => 'Logiciel utilisé',
'exif-artist'                      => 'Auteur',
'exif-copyright'                   => 'Détenteur du droit d’auteur',
'exif-exifversion'                 => 'Version EXIF',
'exif-flashpixversion'             => 'Version FlashPix',
'exif-colorspace'                  => 'Espace colorimétrique',
'exif-componentsconfiguration'     => 'Signification de chaque composante',
'exif-compressedbitsperpixel'      => 'Mode de compression de l’image',
'exif-pixelydimension'             => 'Hauteur d’image valide',
'exif-pixelxdimension'             => 'Largeur d’image valide',
'exif-makernote'                   => 'Notes du fabricant',
'exif-usercomment'                 => 'Commentaires de l’utilisateur',
'exif-relatedsoundfile'            => 'Fichier audio associé',
'exif-datetimeoriginal'            => 'Date de la prise originelle',
'exif-datetimedigitized'           => 'Date de la numérisation',
'exif-subsectime'                  => 'Date de modification',
'exif-subsectimeoriginal'          => 'Date de la prise originelle',
'exif-subsectimedigitized'         => 'Date de la numérisation',
'exif-exposuretime'                => 'Temps d’exposition',
'exif-exposuretime-format'         => '$1 s ($2 s)',
'exif-fnumber'                     => 'Ouverture',
'exif-exposureprogram'             => 'Programme d’exposition',
'exif-spectralsensitivity'         => 'Sensibilité spectrale',
'exif-isospeedratings'             => 'Sensibilité ISO',
'exif-oecf'                        => 'Fonction de conversion opto-électronique',
'exif-shutterspeedvalue'           => 'Vitesse d’obturation',
'exif-aperturevalue'               => 'Ouverture',
'exif-brightnessvalue'             => 'Luminance',
'exif-exposurebiasvalue'           => 'Correction d’exposition',
'exif-maxaperturevalue'            => 'Ouverture maximale',
'exif-subjectdistance'             => 'Distance du sujet',
'exif-meteringmode'                => 'Mode de mesure',
'exif-lightsource'                 => 'Source de lumière',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Longueur focale',
'exif-subjectarea'                 => 'Emplacement du sujet',
'exif-flashenergy'                 => 'Énergie du flash',
'exif-spatialfrequencyresponse'    => 'Fréquence spatiale',
'exif-focalplanexresolution'       => 'Résolution horizontale du plan focal',
'exif-focalplaneyresolution'       => 'Résolution verticale du plan focal',
'exif-focalplaneresolutionunit'    => 'Unité de résolution du plan focal',
'exif-subjectlocation'             => 'Localisation du sujet',
'exif-exposureindex'               => 'Index d’exposition',
'exif-sensingmethod'               => 'Type de capteur',
'exif-filesource'                  => 'Source du fichier',
'exif-scenetype'                   => 'Type de scène',
'exif-cfapattern'                  => 'Motif CFA',
'exif-customrendered'              => 'Rendu personnalisé',
'exif-exposuremode'                => 'Mode d’exposition',
'exif-whitebalance'                => 'Balance des blancs',
'exif-digitalzoomratio'            => 'Taux de zoom numérique',
'exif-focallengthin35mmfilm'       => 'Longueur focale pour un film 35 mm',
'exif-scenecapturetype'            => 'Type de capture de la scène',
'exif-gaincontrol'                 => 'Contrôle du gain',
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturation',
'exif-sharpness'                   => 'Netteté',
'exif-devicesettingdescription'    => 'Description de la configuration du dispositif',
'exif-subjectdistancerange'        => 'Distance du sujet',
'exif-imageuniqueid'               => 'Identifiant unique de l’image',
'exif-gpsversionid'                => 'Version de la balise GPS',
'exif-gpslatituderef'              => 'Référence pour la latitude',
'exif-gpslatitude'                 => 'Latitude',
'exif-gpslongituderef'             => 'Référence pour la longitude',
'exif-gpslongitude'                => 'Longitude',
'exif-gpsaltituderef'              => 'Référence d’altitude (0=altitude, 1=profondeur)',
'exif-gpsaltitude'                 => 'Altitude',
'exif-gpstimestamp'                => 'Heure GPS (horloge atomique)',
'exif-gpssatellites'               => 'Satellites utilisés pour la mesure',
'exif-gpsstatus'                   => 'État du récepteur',
'exif-gpsmeasuremode'              => 'Mode de mesure',
'exif-gpsdop'                      => 'Précision de la mesure',
'exif-gpsspeedref'                 => 'Unité de vitesse du récepteur GPS',
'exif-gpsspeed'                    => 'Vitesse du récepteur GPS',
'exif-gpstrackref'                 => 'Référence pour la direction du mouvement',
'exif-gpstrack'                    => 'Direction du mouvement',
'exif-gpsimgdirectionref'          => 'Référence pour la direction de l’image',
'exif-gpsimgdirection'             => 'Direction de l’image',
'exif-gpsmapdatum'                 => 'Système géodésique utilisé',
'exif-gpsdestlatituderef'          => 'Référence pour la latitude de la destination',
'exif-gpsdestlatitude'             => 'Latitude de la destination',
'exif-gpsdestlongituderef'         => 'Référence pour la longitude de la destination',
'exif-gpsdestlongitude'            => 'Longitude de la destination',
'exif-gpsdestbearingref'           => 'Référence pour le relèvement de la destination',
'exif-gpsdestbearing'              => 'Relèvement de la destination',
'exif-gpsdestdistanceref'          => 'Référence pour la distance à la destination',
'exif-gpsdestdistance'             => 'Distance à la destination',
'exif-gpsprocessingmethod'         => 'Nom de la méthode de traitement du GPS',
'exif-gpsareainformation'          => 'Nom de la zone GPS',
'exif-gpsdatestamp'                => 'Date GPS',
'exif-gpsdifferential'             => 'Correction différentielle GPS',

# EXIF attributes
'exif-compression-1' => 'Non compressé',

'exif-unknowndate' => 'Date inconnue',

'exif-orientation-1' => 'Normale',
'exif-orientation-2' => 'Inversée horizontalement',
'exif-orientation-3' => 'Tournée de 180°',
'exif-orientation-4' => 'Inversée verticalement',
'exif-orientation-5' => 'Tournée de 90° dans le sens antihoraire et inversée verticalement',
'exif-orientation-6' => 'Tournée de 90° dans le sens horaire',
'exif-orientation-7' => 'Tournée de 90° dans le sens horaire et inversée verticalement',
'exif-orientation-8' => 'Tournée de 90° dans le sens antihoraire',

'exif-planarconfiguration-1' => 'Données contiguës',
'exif-planarconfiguration-2' => 'Données séparées',

'exif-colorspace-ffff.h' => 'Non calibré',

'exif-componentsconfiguration-0' => 'N’existe pas',
'exif-componentsconfiguration-5' => 'V',

'exif-exposureprogram-0' => 'Indéfini',
'exif-exposureprogram-1' => 'Manuel',
'exif-exposureprogram-2' => 'Programme normal',
'exif-exposureprogram-3' => 'Priorité à l’ouverture',
'exif-exposureprogram-4' => 'Priorité à l’obturateur',
'exif-exposureprogram-5' => 'Programme création (préférence à la profondeur de champ)',
'exif-exposureprogram-6' => 'Programme action (préférence à la vitesse d’obturation)',
'exif-exposureprogram-7' => 'Mode portrait (pour clichés de près avec arrière-plan flou)',
'exif-exposureprogram-8' => 'Mode paysage (pour des clichés de paysages nets)',

'exif-subjectdistance-value' => '$1 mètre{{PLURAL:$1||s}}',

'exif-meteringmode-0'   => 'Inconnu',
'exif-meteringmode-1'   => 'Moyenne',
'exif-meteringmode-2'   => 'Moyenne pondérée au centre',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'Multi-spot',
'exif-meteringmode-5'   => 'Modèle',
'exif-meteringmode-6'   => 'Partielle',
'exif-meteringmode-255' => 'Autre',

'exif-lightsource-0'   => 'Inconnue',
'exif-lightsource-1'   => 'Lumière du jour',
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungstène (lumière incandescente)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Temps clair',
'exif-lightsource-10'  => 'Temps nuageux',
'exif-lightsource-11'  => 'Ombre',
'exif-lightsource-12'  => 'Éclairage fluorescent « lumière du jour » (D 5700 – 7100 K)',
'exif-lightsource-13'  => 'Éclairage fluorescent blanc « jour » (N 4600 – 5400 K)',
'exif-lightsource-14'  => 'Éclairage fluorescent blanc « froid » (W 3900 – 4500 K)',
'exif-lightsource-15'  => 'Éclairage fluorescent blanc (WW 3200 – 3700 K)',
'exif-lightsource-17'  => 'Lumière standard A',
'exif-lightsource-18'  => 'Lumière standard B',
'exif-lightsource-19'  => 'Lumière standard C',
'exif-lightsource-24'  => 'Tungstène ISO de studio',
'exif-lightsource-255' => 'Autre source de lumière',

# Flash modes
'exif-flash-fired-0'    => 'Flash non déclenché',
'exif-flash-fired-1'    => 'Flash déclenché',
'exif-flash-return-0'   => 'aucun stroboscope ne retourne une fonction de détection',
'exif-flash-return-2'   => 'le stroboscope ne détecte pas de lumière retournée',
'exif-flash-return-3'   => 'le stroboscope détecte un retour de lumière',
'exif-flash-mode-1'     => 'lumière du flash obligatoire',
'exif-flash-mode-2'     => 'suppression du flash obligatoire',
'exif-flash-mode-3'     => 'Mode automatique',
'exif-flash-function-1' => 'Pas de fonction de flash',
'exif-flash-redeye-1'   => 'Mode anti-yeux rouges',

'exif-focalplaneresolutionunit-2' => 'Pouce',

'exif-sensingmethod-1' => 'Non défini',
'exif-sensingmethod-2' => 'Capteur de couleur à une puce',
'exif-sensingmethod-3' => 'Capteur de couleur à deux puces',
'exif-sensingmethod-4' => 'Capteur de couleur à trois puces',
'exif-sensingmethod-5' => 'Capteur de couleur séquentiel',
'exif-sensingmethod-7' => 'Capteur trilinéaire',
'exif-sensingmethod-8' => 'Capteur de couleur linéaire séquentiel',

'exif-filesource-3' => 'Appareil photo numérique',

'exif-scenetype-1' => 'Image photographiée directement',

'exif-customrendered-0' => 'Procédé normal',
'exif-customrendered-1' => 'Procédé personnalisé',

'exif-exposuremode-0' => 'Automatique',
'exif-exposuremode-1' => 'Manuelle',
'exif-exposuremode-2' => 'Fourchette automatique',

'exif-whitebalance-0' => 'Automatique',
'exif-whitebalance-1' => 'Manuelle',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Paysage',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Scène de nuit',

'exif-gaincontrol-0' => 'Aucun',
'exif-gaincontrol-1' => 'Gain faiblement positif',
'exif-gaincontrol-2' => 'Gain fortement positif',
'exif-gaincontrol-3' => 'Gain faiblement négatif',
'exif-gaincontrol-4' => 'Gain fortement négatif',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Faible',
'exif-contrast-2' => 'Fort',

'exif-saturation-0' => 'Normale',
'exif-saturation-1' => 'Faible',
'exif-saturation-2' => 'Élevée',

'exif-sharpness-0' => 'Normale',
'exif-sharpness-1' => 'Douce',
'exif-sharpness-2' => 'Dure',

'exif-subjectdistancerange-0' => 'Inconnue',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Rapproché',
'exif-subjectdistancerange-3' => 'Distant',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Nord',
'exif-gpslatitude-s' => 'Sud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Est',
'exif-gpslongitude-w' => 'Ouest',

'exif-gpsstatus-a' => 'Mesure en cours',
'exif-gpsstatus-v' => 'Interfonctionnement de la mesure',

'exif-gpsmeasuremode-2' => 'Mesure à 2 dimensions',
'exif-gpsmeasuremode-3' => 'Mesure à 3 dimensions',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilomètres à l’heure',
'exif-gpsspeed-m' => 'Milles à l’heure',
'exif-gpsspeed-n' => 'Nœud',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Nord vrai',
'exif-gpsdirection-m' => 'Nord magnétique',

# External editor support
'edit-externally'      => 'Modifier ce fichier en utilisant une application externe',
'edit-externally-help' => '(Consulter [http://www.mediawiki.org/wiki/Manual:External_editors/fr les instructions d’installation] pour plus d’informations)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'toutes',
'imagelistall'     => 'toutes',
'watchlistall2'    => 'tout',
'namespacesall'    => 'Tous',
'monthsall'        => 'tous',
'limitall'         => 'tous',

# E-mail address confirmation
'confirmemail'              => 'Confirmer l’adresse de courriel',
'confirmemail_noemail'      => 'Vous n’avez pas défini une adresse de courriel valide dans vos [[Special:Preferences|préférences]].',
'confirmemail_text'         => 'Ce wiki nécessite la vérification de votre adresse de courriel avant de pouvoir utiliser toute fonction de messagerie.
Utilisez le bouton ci-dessous pour envoyer un courriel de confirmation à votre adresse.
Le courriel inclura un lien comportant un code à usage unique et limité dans le temps ;
chargez ce lien dans votre navigateur pour confirmer que votre adresse de courriel est valide.',
'confirmemail_pending'      => 'Un code de confirmation vous a déjà été envoyé par courriel ;
si vous venez de créer votre compte, veuillez attendre quelques minutes que le courriel arrive avant de demander un nouveau code.',
'confirmemail_send'         => 'Envoyer un code de confirmation',
'confirmemail_sent'         => 'Courriel de confirmation envoyé',
'confirmemail_oncreate'     => 'Un code de confirmation a été envoyé à votre adresse de courriel.
Ce code n’est pas requis pour vous identifier sur ce wiki, mais vous devrez le fournir pour activer toute fonctionnalité basée sur les courriels.',
'confirmemail_sendfailed'   => '{{SITENAME}} n’a pas pu vous envoyer le courriel de confirmation.
Veuillez vérifiez que votre adresse de courriel ne comprend aucun caractère incorrect.

Le programme d’envoi de courriel a retourné l’indication suivante : $1',
'confirmemail_invalid'      => 'Code de confirmation incorrect.
Celui-ci a peut-être expiré.',
'confirmemail_needlogin'    => 'Vous devez $1 pour confirmer votre adresse de courriel.',
'confirmemail_success'      => 'Votre adresse de courriel a été confirmée.
Vous pouvez maintenant vous [[Special:UserLogin|identifier]] et profiter du wiki.',
'confirmemail_loggedin'     => 'Votre adresse de courriel est maintenant confirmée.',
'confirmemail_error'        => 'Un problème est survenu lors de l’enregistrement de votre confirmation.',
'confirmemail_subject'      => 'Confirmation d’adresse de courriel pour {{SITENAME}}',
'confirmemail_body'         => 'Quelqu’un, probablement vous, à partir de l’adresse IP $1,
a enregistré un compte « $2 » avec cette adresse de courriel
sur le site {{SITENAME}}.

Pour confirmer que ce compte vous appartient vraiment et afin
d’activer les fonctions de messagerie sur {{SITENAME}},
veuillez suivre ce lien dans votre navigateur :

$3

Si vous n’avez *pas* enregistré ce compte, n’ouvrez pas ce lien ;
vous pouvez suivre l’autre lien ci-dessous pour annuler la
confirmation de votre adresse courriel :

$5

Ce code de confirmation expirera le $4.',
'confirmemail_body_changed' => 'Quelqu’un, probablement vous, à partir de l’adresse IP $1,
a modifié l’adresse de courriel associée au compte « $2 » de {{SITENAME}}
en cette adresse.

Pour confirmer que ce compte vous appartient vraiment et afin
de réactiver les fonctions de messagerie sur {{SITENAME}},
veuillez suivre ce lien dans votre navigateur :

$3

Si ce compte ne vous appartient *pas*, n’ouvrez pas ce lien ;
vous pouvez suivre l’autre lien ci-dessous pour annuler la
confirmation de votre adresse courriel :

$5

Ce code de confirmation expirera le $4.',
'confirmemail_invalidated'  => 'Confirmation de l’adresse courriel annulée',
'invalidateemail'           => 'Annuler la confirmation de l’adresse de courriel',

# Scary transclusion
'scarytranscludedisabled' => '[La transclusion interwiki est désactivée]',
'scarytranscludefailed'   => '[La récupération de modèle a échoué pour $1]',
'scarytranscludetoolong'  => '[L’URL est trop longue]',

# Trackbacks
'trackbackbox'      => 'Rétroliens vers cette page :<br />
$1',
'trackbackremove'   => '([$1 Supprimer])',
'trackbacklink'     => 'Rétrolien',
'trackbackdeleteok' => 'Le rétrolien a été supprimé avec succès.',

# Delete conflict
'deletedwhileediting' => "'''Attention''' : cette page a été supprimée après que vous ayez commencé à la modifier !",
'confirmrecreate'     => "L’utilisateur [[User:$1|$1]] ([[User talk:$1|Discussion]]) a supprimé cette page, alors que vous aviez commencé à l’éditer, pour le motif suivant :
: ''$2''
Veuillez confirmer que vous désirez réellement recréer cette page.",
'recreate'            => 'Recréer',

# action=purge
'confirm_purge_button' => 'Confirmer',
'confirm-purge-top'    => 'Voulez-vous rafraîchir cette page (purger le cache) ?',
'confirm-purge-bottom' => 'Purger une page l’efface du cache de rendu et force sa dernière version à être régénérée et affichée.',

# Separators for various lists, etc.
'semicolon-separator' => '&nbsp;;&#32;',
'colon-separator'     => '&nbsp;:&#32;',
'autocomment-prefix'  => '&#32;–&#32;',
'percent'             => '$1&nbsp;%',

# Multipage image navigation
'imgmultipageprev' => '← page précédente',
'imgmultipagenext' => 'page suivante →',
'imgmultigo'       => 'Accéder !',
'imgmultigoto'     => 'Aller à la page $1',

# Table pager
'ascending_abbrev'         => 'crois.',
'descending_abbrev'        => 'décr.',
'table_pager_next'         => 'Page suivante',
'table_pager_prev'         => 'Page précédente',
'table_pager_first'        => 'Première page',
'table_pager_last'         => 'Dernière page',
'table_pager_limit'        => 'Montrer $1 élément{{PLURAL:$1||s}} par page',
'table_pager_limit_label'  => 'Résultats par page :',
'table_pager_limit_submit' => 'Accéder',
'table_pager_empty'        => 'Aucun résultat',

# Auto-summaries
'autosumm-blank'   => 'Page blanchie',
'autosumm-replace' => 'Contenu remplacé par « $1 »',
'autoredircomment' => 'Page redirigée vers [[$1]]',
'autosumm-new'     => 'Page créée avec « $1 »',

# Size units
'size-bytes'     => '$1 o',
'size-kilobytes' => '$1 Kio',
'size-megabytes' => '$1 Mio',
'size-gigabytes' => '$1 Gio',

# Live preview
'livepreview-loading' => 'Chargement…',
'livepreview-ready'   => 'Chargement … terminé !',
'livepreview-failed'  => 'L’aperçu rapide a échoué !
Essayez la prévisualisation normale.',
'livepreview-error'   => 'Impossible de se connecter : $1 « $2 ».
Essayez la prévisualisation normale.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Les modifications datant de moins de $1 seconde{{PLURAL:$1||s}} peuvent ne pas apparaître dans cette liste.',
'lag-warn-high'   => 'En raison d’un retard important du serveur de base de données, les modifications datant de moins de $1 seconde{{PLURAL:$1||s}} peuvent ne pas apparaître dans cette liste.',

# Watchlist editor
'watchlistedit-numitems'       => 'Votre liste de suivi contient {{PLURAL:$1|un titre|$1 titres}}, sans compter les pages de discussion.',
'watchlistedit-noitems'        => 'Votre liste de suivi ne contient aucun titre.',
'watchlistedit-normal-title'   => 'Modifier la liste de suivi',
'watchlistedit-normal-legend'  => 'Retirer des titres de la liste de suivi',
'watchlistedit-normal-explain' => 'Les titres de votre liste de suivi sont visibles ci-dessous.
Pour retirer un titre de la liste (et sa page de discussion), cochez la case à côté puis cliquez sur le bouton « {{int:Watchlistedit-normal-submit}} ».
Vous pouvez aussi [[Special:Watchlist/raw|modifier la liste en mode brut]].',
'watchlistedit-normal-submit'  => 'Retirer les titres sélectionnés',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Un titre a été enlevé|$1 titres ont été enlevés}} de votre liste de suivi :',
'watchlistedit-raw-title'      => 'Modifier la liste de suivi en mode brut',
'watchlistedit-raw-legend'     => 'Modification de la liste de suivi en mode brut',
'watchlistedit-raw-explain'    => 'Les titres de votre liste de suivi sont affichés ci-dessous et peuvent être modifiés en les ajoutant ou les retirant de la liste (un titre par ligne).
Lorsque vous avez fini, cliquez sur le bouton « {{int:Watchlistedit-raw-submit}} » en bas.
Vous pouvez aussi [[Special:Watchlist/edit|utiliser l’éditeur normal]].',
'watchlistedit-raw-titles'     => 'Titres :',
'watchlistedit-raw-submit'     => 'Mettre à jour la liste de suivi',
'watchlistedit-raw-done'       => 'Votre liste de suivi a été mise à jour.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Un titre a été ajouté|$1 titres ont été ajoutés}} :',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Un titre a été retiré|$1 titres ont été retirés}} :',

# Watchlist editing tools
'watchlisttools-view' => 'Liste de suivi',
'watchlisttools-edit' => 'Voir et modifier la liste de suivi',
'watchlisttools-raw'  => 'Modifier la liste de suivi en mode brut',

# Hijri month names
'hijri-calendar-m3'  => 'Rabi’ al-awwal',
'hijri-calendar-m4'  => 'Rabi’ al-thani',
'hijri-calendar-m8'  => 'Sha’aban',
'hijri-calendar-m11' => 'Dhu al-Qi’dah',

# Core parser functions
'unknown_extension_tag' => 'Balise d’extension « $1 » inconnue',
'duplicate-defaultsort' => 'Attention : la clé de tri par défaut « $2 » écrase la précédente « $1 ».',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Extensions installées',
'version-specialpages'             => 'Pages spéciales',
'version-parserhooks'              => 'Greffons du parseur',
'version-variables'                => 'Variables',
'version-other'                    => 'Divers',
'version-mediahandlers'            => 'Manipulateurs de médias',
'version-hooks'                    => 'Greffons',
'version-extension-functions'      => 'Fonctions d’extension internes',
'version-parser-extensiontags'     => 'Balises étendues du parseur',
'version-parser-function-hooks'    => 'Fonctions étendues du parseur',
'version-skin-extension-functions' => 'Fonctions d’extension de l’interface',
'version-hook-name'                => 'Nom du greffon',
'version-hook-subscribedby'        => 'Abonnés :',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Licence',
'version-software'                 => 'Logiciels installés',
'version-software-product'         => 'Produit',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Chemin d’accès du fichier',
'filepath-page'    => 'Fichier :',
'filepath-submit'  => 'Aller',
'filepath-summary' => 'Cette page spéciale retourne le chemin d’accès complet d’un fichier.
Les images sont montrées dans leur pleine résolution, les autres fichiers sont chargés et démarrés directement avec leur programme associé.

Entrez le nom du fichier sans le préfixe « {{ns:file}}: »',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Recherche de doublons',
'fileduplicatesearch-summary'  => 'Recherche des copies de fichiers identiques d’après leur empreinte de hachage.

Entrez le nom du fichier sans le préfixe « {{ns:file}}: ».',
'fileduplicatesearch-legend'   => 'Rechercher un doublon',
'fileduplicatesearch-filename' => 'Nom du fichier :',
'fileduplicatesearch-submit'   => 'Rechercher',
'fileduplicatesearch-info'     => '$1 × $2 pixels<br />Taille du fichier : $3<br />Type MIME : $4',
'fileduplicatesearch-result-1' => "Le fichier « $1 » n'a pas de doublon.",
'fileduplicatesearch-result-n' => 'Le fichier « $1 » a {{PLURAL:$2|1 doublon|$2 doublons}}.',

# Special:SpecialPages
'specialpages'                   => 'Pages spéciales',
'specialpages-note'              => '----
* Pages spéciales normales.
* <strong class="mw-specialpagerestricted">Pages spéciales restreintes.</strong>',
'specialpages-group-maintenance' => 'Rapports de maintenance',
'specialpages-group-other'       => 'Autres pages spéciales',
'specialpages-group-login'       => 'S’identifier / s’inscrire',
'specialpages-group-changes'     => 'Modifications récentes et journaux',
'specialpages-group-media'       => 'Rapports et téléversements de fichiers médias',
'specialpages-group-users'       => 'Utilisateurs et droits rattachés',
'specialpages-group-highuse'     => 'Pages d’utilisation intensive',
'specialpages-group-pages'       => 'Listes de pages',
'specialpages-group-pagetools'   => 'Outils pour les pages',
'specialpages-group-wiki'        => 'Données du wiki et outils',
'specialpages-group-redirects'   => 'Pages spéciales redirigées',
'specialpages-group-spam'        => 'Outils anti-pourriel',

# Special:BlankPage
'blankpage'              => 'Page vide',
'intentionallyblankpage' => 'Cette page est laissée intentionellement vide.',

# External image whitelist
'external_image_whitelist' => ' #Laisser cette ligne exactement telle quelle.<pre>
#Indiquer les fragments d’expressions rationnelles (juste la partie indiquée entre les //) ci-dessous.
#Ils correspondront avec les URLs des images externes.
#Celles qui correspondent s’afficheront comme des images, sinon seul un lien vers l’image sera affiché.
#Les lignes commençant par un # seront considérées comme des commentaires.
#Cette liste n’est pas sensible à la casse.

#Mettez tous les fragments d’expressions rationnelles au-dessus de cette ligne. Laissez cette dernière ligne telle quelle.</pre>',

# Special:Tags
'tags'                    => 'Balises des modifications valides',
'tag-filter'              => 'Filtrer les [[Special:Tags|balises]] :',
'tag-filter-submit'       => 'Filtrer',
'tags-title'              => 'Balises',
'tags-intro'              => 'Cette page liste les balises que le logiciel peut utiliser pour marquer une modification et la signification de chacune.',
'tags-tag'                => 'Nom de la balise',
'tags-display-header'     => 'Apparence dans les listes de modifications',
'tags-description-header' => 'Description complète de la balise',
'tags-hitcount-header'    => 'Modifications balisées',
'tags-edit'               => 'modifier',
'tags-hitcount'           => '$1 modification{{PLURAL:$1||s}}',

# Special:ComparePages
'comparepages'     => 'Comparer des pages',
'compare-selector' => 'Comparer les versions des pages',
'compare-page1'    => 'Page 1',
'compare-page2'    => 'Page 2',
'compare-rev1'     => 'Version 1',
'compare-rev2'     => 'Version 2',
'compare-submit'   => 'Comparer',

# Database error messages
'dberr-header'      => 'Ce wiki a un problème',
'dberr-problems'    => 'Désolé ! Ce site rencontre des difficultés techniques.',
'dberr-again'       => 'Essayez d’attendre quelques minutes et rechargez.',
'dberr-info'        => '(Connexion au serveur de base de données impossible : $1)',
'dberr-usegoogle'   => 'Vous pouvez essayer de chercher avec Google pendant ce temps.',
'dberr-outofdate'   => 'Notez que leurs index de notre contenu peuvent être dépassés.',
'dberr-cachederror' => 'Ceci est une copie cachée de la page demandée et peut être dépassée.',

# HTML forms
'htmlform-invalid-input'       => 'Des problèmes sont survenus avec certaines valeurs',
'htmlform-select-badoption'    => 'La valeur que vous avez spécifiée n’est pas une option valide.',
'htmlform-int-invalid'         => 'La valeur que vous avec spécifiée n’est pas un entier.',
'htmlform-float-invalid'       => 'La valeur que vous avez spécifiée n’est pas un nombre.',
'htmlform-int-toolow'          => 'La valeur que vous avez spécifiée est plus petite que le minimum de $1',
'htmlform-int-toohigh'         => 'La valeur que vous avez spécifiée est plus grande que le maximum de $1',
'htmlform-required'            => 'Cette valeur est requise',
'htmlform-submit'              => 'Soumettre',
'htmlform-reset'               => 'Défaire les modifications',
'htmlform-selectorother-other' => 'Autre',

);
