<?php
/** Latin (Latina)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author UV
 * @author Siebrand Mazeland
 * @author Helix84
 * @author LeighvsOptimvsMaximvs
 * @author Siebrand
 * @author לערי ריינהארט
 */

$skinNames = array(
	'standard' => 'Norma',
	'cologneblue' => 'Caerulus Colonia'
);

$namespaceNames = array(
	NS_SPECIAL        => 'Specialis',
	NS_MAIN           => '',
	NS_TALK           => 'Disputatio',
	NS_USER           => 'Usor',
	NS_USER_TALK      => 'Disputatio_Usoris',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Disputatio_{{grammar:genitive|$1}}',
	NS_IMAGE          => 'Imago',
	NS_IMAGE_TALK     => 'Disputatio_Imaginis',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Disputatio_MediaWiki',
	NS_TEMPLATE       => 'Formula',
	NS_TEMPLATE_TALK  => 'Disputatio_Formulae',
	NS_HELP           => 'Auxilium',
	NS_HELP_TALK      => 'Disputatio_Auxilii',
	NS_CATEGORY       => 'Categoria',
	NS_CATEGORY_TALK  => 'Disputatio_Categoriae',
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
	'DoubleRedirects'           => array( 'Redirectiones_duplices' ),
	'BrokenRedirects'           => array( 'Redirectiones_fractae' ),
	'Disambiguations'           => array( 'Paginae_disambiguationis', 'Disambiguationes' ),
	'Userlogin'                 => array( 'Conventum_aperire' ),
	'Userlogout'                => array( 'Conventum_concludere' ),
	'Preferences'               => array( 'Praeferentiae' ),
	'Watchlist'                 => array( 'Paginae_custoditae' ),
	'Recentchanges'             => array( 'Nuper_mutata', 'Mutationes_recentes' ),
	'Upload'                    => array( 'Fasciculos_onerare', 'Imagines_onerare' ),
	'Imagelist'                 => array( 'Fasciculi', 'Imagines' ),
	'Newimages'                 => array( 'Fasciculi_novi', 'Imagines_novae' ),
	'Listusers'                 => array( 'Usores' ),
	'Statistics'                => array( 'Census' ),
	'Randompage'                => array( 'Pagina_fortuita' ),
	'Lonelypages'               => array( 'Paginae_non_annexae' ),
	'Uncategorizedpages'        => array( 'Paginae_sine_categoriis' ),
	'Uncategorizedcategories'   => array( 'Categoriae_sine_categoriis' ),
	'Uncategorizedimages'       => array( 'Fasciculi_sine_categoriis', 'Imagines_sine_categoriis' ),
	'Uncategorizedtemplates'    => array( 'Formulae_sine_categoriis' ),
	'Unusedcategories'          => array( 'Categoriae_non_in_usu', 'Categoriae_vacuae' ),
	'Unusedimages'              => array( 'Fasciculi_non_in_usu', 'Imagines_non_in_usu' ),
	'Wantedpages'               => array( 'Paginae_desideratae', 'Nexus_fracti' ),
	'Wantedcategories'          => array( 'Categoriae_desideratae' ),
	'Mostlinked'                => array( 'Paginae_maxime_annexae' ),
	'Mostlinkedcategories'      => array( 'Categoriae_maxime_annexae' ),
	'Mostlinkedtemplates'       => array( 'Formulae_maxime_annexae' ),
	'Mostcategories'            => array( 'Paginae_plurimis_categoriis' ),
	'Mostimages'                => array( 'Fasciculi_maxime_annexi', 'Imagines_maxime_annexae' ),
	'Mostrevisions'             => array( 'Paginae_plurimum_mutatae' ),
	'Fewestrevisions'           => array( 'Paginae_minime_mutatae' ),
	'Shortpages'                => array( 'Paginae_breves' ),
	'Longpages'                 => array( 'Paginae_longae' ),
	'Newpages'                  => array( 'Paginae_novae' ),
	'Ancientpages'              => array( 'Paginae_veterrimae' ),
	'Deadendpages'              => array( 'Paginae_sine_nexu' ),
	'Protectedpages'            => array( 'Paginae_protectae' ),
	'Protectedtitles'           => array( 'Tituli_protecti' ),
	'Allpages'                  => array( 'Paginae_omnes', 'Omnes_paginae' ),
	'Prefixindex'               => array( 'Praefixa', 'Quaerere_per_praefixa' ),
	'Ipblocklist'               => array( 'Usores_obstructi' ),
	'Specialpages'              => array( 'Paginae_speciales' ),
	'Contributions'             => array( 'Conlationes', 'Conlationes_usoris' ),
	'Emailuser'                 => array( 'Litteras_electronicas_usori_mittere', 'Littera_electronica' ),
	'Confirmemail'              => array( 'Inscriptionem_electronicam_confirmare' ),
	'Whatlinkshere'             => array( 'Nexus_ad_paginam' ),
	'Recentchangeslinked'       => array( 'Nuper_mutata_annexorum' ),
	'Movepage'                  => array( 'Paginam_movere', 'Movere' ),
	'Blockme'                   => array( 'Usor_obstructus' ),
	'Booksources'               => array( 'Librorum_fontes' ),
	'Categories'                => array( 'Categoriae' ),
	'Export'                    => array( 'Exportare', 'Paginas_exportare' ),
	'Version'                   => array( 'Versio' ),
	'Allmessages'               => array( 'Nuntia_systematis' ),
	'Log'                       => array( 'Acta' ),
	'Blockip'                   => array( 'Usorem_obstruere' ),
	'Undelete'                  => array( 'Paginam_restituere' ),
	'Import'                    => array( 'Importare', 'Paginas_importare' ),
	'Lockdb'                    => array( 'Basem_datorum_obstruere' ),
	'Unlockdb'                  => array( 'Basem_datorum_deobstruere' ),
	'Userrights'                => array( 'Iures_usorum' ),
	'MIMEsearch'                => array( 'Quaerere_per_MIME' ),
	'Unwatchedpages'            => array( 'Paginae_incustoditae' ),
	'Listredirects'             => array( 'Redirectiones' ),
	'Revisiondelete'            => array( 'Emendationem_delere' ),
	'Unusedtemplates'           => array( 'Formulae_non_in_usu' ),
	'Randomredirect'            => array( 'Redirectio_fortuita' ),
	'Mypage'                    => array( 'Pagina_mea' ),
	'Mytalk'                    => array( 'Disputatio_mea' ),
	'Mycontributions'           => array( 'Conlationes_meae' ),
	'Listadmins'                => array( 'Magistratus' ),
	'Listbots'                  => array( 'Bots' ),
	'Popularpages'              => array( 'Paginae_saepe_monstratae' ),
	'Search'                    => array( 'Quaerere' ),
	'Resetpass'                 => array( 'Tesseram_novam_creare' ),
	'Withoutinterwiki'          => array( 'Paginae_sine_nexibus_ad_linguas_alias', 'Paginae_sine_nexibus_intervicis' ),
	'MergeHistory'              => array( 'Historias_paginarum_confundere' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Nexus cum linea subscribere:',
'tog-highlightbroken'         => 'Formare nexos fractos <a href="" class="new">sici</a> (alioqui: sic<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Iustificare paragrapha',
'tog-hideminor'               => 'Celare recensiones minores in nuper mutatibus',
'tog-extendwatchlist'         => 'Extendere indicem paginarum custoditarum ut omnes emendationes monstrentur',
'tog-usenewrc'                => 'Nuper mutata amplificata (JavaScript)',
'tog-numberheadings'          => 'Numerare indices necessario',
'tog-showtoolbar'             => 'Instrumenta pro recensendo monstrare (JavaScript)',
'tog-editondblclick'          => 'Premere bis ad paginam recensendum (JavaScript)',
'tog-editsection'             => 'Licere paginarum partibus recensier via nexus [recensere]',
'tog-editsectiononrightclick' => 'Licere paginarum partibus recensier si<br />dextram murem premam in titulis partum (JavaScript)',
'tog-showtoc'                 => 'Indicem contenta monstrare (paginis in quibus sunt plus quam 3 partes)',
'tog-rememberpassword'        => 'Recordare tesseram inter conventa (utere cookies)',
'tog-editwidth'               => 'Capsa recensitorum totam latitudinem habet',
'tog-watchcreations'          => 'Paginas quas creo in paginarum custoditarum indicem addere',
'tog-watchdefault'            => 'Paginas quas recenseo in paginarum custoditarum indicem addere',
'tog-watchmoves'              => 'Paginas quas moveo in paginarum custoditarum indicem addere',
'tog-watchdeletion'           => 'Paginas quas deleo in paginarum custoditarum indicem addere',
'tog-minordefault'            => 'Notare omnes recensiones quasi minores',
'tog-previewontop'            => 'Monstrare praevisum ante capsam recensiti, non post ipsam',
'tog-previewonfirst'          => 'Praevisum monstrare recensione incipiente',
'tog-enotifwatchlistpages'    => 'Mittere mihi litteras electronicas si pagina a me custodita mutatur',
'tog-enotifusertalkpages'     => 'Mittere mihi litteras electronicas si mea disputatio mutatur',
'tog-enotifminoredits'        => 'Mittere mihi litteras electronicas etiam pro recensionibus minoribus',
'tog-enotifrevealaddr'        => 'Monstrare inscriptio mea electronica in nuntiis notificantibus',
'tog-shownumberswatching'     => 'Numerum usorum custodientium monstrare',
'tog-fancysig'                => 'Subscriptio cruda (sine nexu automatico)',
'tog-externaleditor'          => 'Utere editore externo semper',
'tog-externaldiff'            => 'Utere dissimilitudine externa semper',
'tog-uselivepreview'          => 'Praevisum viventem adhibere (JavaScript)',
'tog-forceeditsummary'        => 'Si recensionem non summatim descripsero, me roga si continuare velim',
'tog-watchlisthideown'        => 'Celare meas recensiones in paginarum custoditarum indice',
'tog-watchlisthidebots'       => 'Celare recensiones automatarias in paginarum custoditarum indice',
'tog-watchlisthideminor'      => 'Celare recensiones minores in paginarum custoditarum indice',

'underline-always'  => 'Semper',
'underline-never'   => 'Numquam',
'underline-default' => 'Defalta navigatri interretialis',

'skinpreview' => '(Praevisum)',

# Dates
'sunday'        => 'dies Solis',
'monday'        => 'dies Lunae',
'tuesday'       => 'dies Martis',
'wednesday'     => 'dies Mercurii',
'thursday'      => 'dies Iovis',
'friday'        => 'dies Veneris',
'saturday'      => 'dies Saturni',
'sun'           => 'Sol',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mer',
'thu'           => 'Iov',
'fri'           => 'Ven',
'sat'           => 'Sat',
'january'       => 'Ianuarius',
'february'      => 'Februarius',
'march'         => 'Martius',
'april'         => 'Aprilis',
'may_long'      => 'Maius',
'june'          => 'Iunius',
'july'          => 'Iulius',
'august'        => 'Augustus',
'september'     => 'September',
'october'       => 'October',
'november'      => 'November',
'december'      => 'December',
'january-gen'   => 'Ianuarii',
'february-gen'  => 'Februarii',
'march-gen'     => 'Martii',
'april-gen'     => 'Aprilis',
'may-gen'       => 'Maii',
'june-gen'      => 'Iunii',
'july-gen'      => 'Iulii',
'august-gen'    => 'Augusti',
'september-gen' => 'Septembris',
'october-gen'   => 'Octobris',
'november-gen'  => 'Novembris',
'december-gen'  => 'Decembris',
'jan'           => 'Ian',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mai',
'jun'           => 'Iun',
'jul'           => 'Iul',
'aug'           => 'Aug',
'sep'           => 'Sep',
'oct'           => 'Oct',
'nov'           => 'Nov',
'dec'           => 'Dec',

# Bits of text used by many pages
'categories'            => 'Categoriae',
'pagecategories'        => '{{PLURAL:$1|Categoria|Categoriae}}',
'category_header'       => 'Paginae in categoria "$1"',
'subcategories'         => 'Subcategoriae',
'category-media-header' => 'Media in categoria "$1"',
'category-empty'        => "''Huic categoriae non sunt paginae vel fasciculi.''",

'about'          => 'De',
'article'        => 'Pagina contenta continens',
'newwindow'      => '(in fenestra nova aperietur)',
'cancel'         => 'Abrogare',
'qbfind'         => 'Invenire',
'qbbrowse'       => 'Perspicere',
'qbedit'         => 'Recensere',
'qbpageoptions'  => 'Optiones paginae',
'qbpageinfo'     => 'Indicium paginae',
'qbmyoptions'    => 'Optiones meae',
'qbspecialpages' => 'Paginae speciales',
'moredotdotdot'  => 'Plus...',
'mypage'         => 'Pagina mea',
'mytalk'         => 'Disputatum meum',
'anontalk'       => 'Disputatio huius IP',
'navigation'     => 'Navigatio',

'errorpagetitle'    => 'Error',
'returnto'          => 'Redire ad $1.',
'tagline'           => 'E {{grammar:ablative|{{SITENAME}}}}',
'help'              => 'Adiutatum',
'search'            => 'Quaerere',
'searchbutton'      => 'Quaerere',
'go'                => 'Ire',
'searcharticle'     => 'Ire',
'history'           => 'Historia paginae',
'history_short'     => 'Historia',
'info_short'        => 'Informatio',
'printableversion'  => 'Forma impressibilis',
'permalink'         => 'Nexus perpetuus',
'print'             => 'Imprimere',
'edit'              => 'Recensere',
'editthispage'      => 'Recensere hanc paginam',
'delete'            => 'Delere',
'deletethispage'    => 'Delere hanc paginam',
'protect'           => 'Protegere',
'protectthispage'   => 'Protegere hanc paginam',
'unprotect'         => 'Deprotegere',
'unprotectthispage' => 'Deprotegere hanc paginam',
'newpage'           => 'Nova pagina',
'talkpage'          => 'Disputare hanc paginam',
'talkpagelinktext'  => 'Disputatio',
'specialpage'       => 'Pagina specialis',
'postcomment'       => 'Adnotare',
'articlepage'       => 'Videre rem',
'talk'              => 'Disputatio',
'views'             => 'Visae',
'toolbox'           => 'Arca ferramentorum',
'userpage'          => 'Videre paginam usoris',
'projectpage'       => 'Videre consilium',
'imagepage'         => 'Videre paginam fasciculi',
'mediawikipage'     => 'Videre nuntium',
'templatepage'      => 'Videre formulam',
'viewhelppage'      => 'Videre auxilium',
'categorypage'      => 'Videre categoriam',
'viewtalkpage'      => 'Videre disputatum',
'otherlanguages'    => 'Linguis aliis',
'redirectedfrom'    => '(Redirectum de $1)',
'redirectpagesub'   => 'Pagina redirectionis',
'lastmodifiedat'    => 'Ultima mutatio: $2, $1.', # $1 date, $2 time
'protectedpage'     => 'Pagina protecta',
'jumpto'            => 'Salire ad:',
'jumptonavigation'  => 'navigationem',
'jumptosearch'      => 'quaerere',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'De {{grammar:ablative|{{SITENAME}}}}',
'aboutpage'         => 'Project:De {{grammar:ablative|{{SITENAME}}}}',
'bugreports'        => 'Renuntiare errores',
'bugreportspage'    => 'Project:Renuntiare errores',
'copyright'         => 'Res ad manum sub $1.',
'copyrightpagename' => '{{grammar:genitive|{{SITENAME}}}} verba privata',
'copyrightpage'     => '{{ns:project}}:Verba privata',
'currentevents'     => 'Novissima',
'currentevents-url' => 'Project:Novissima',
'disclaimers'       => 'Repudiationes',
'disclaimerpage'    => 'Project:Repudiationes',
'edithelp'          => 'Opes pro recensendo',
'edithelppage'      => 'Help:Quam paginam recensere',
'faq'               => 'Quaestiones frequentes',
'faqpage'           => 'Project:Quaestiones frequentes',
'helppage'          => 'Help:Auxilium pro editione',
'mainpage'          => 'Pagina prima',
'portal'            => 'Porta communis',
'portal-url'        => 'Project:Porta communis',
'privacy'           => 'Consilium de secreto',
'privacypage'       => 'Project:Consilium de secreto',
'sitesupport'       => 'Donationes',
'sitesupport-url'   => 'Project:Donationes',

'badaccess'        => 'Error permissu',
'badaccess-group0' => 'Non licet tibi actum quod petivisti agere.',
'badaccess-group1' => 'Actum quod petivisti solum potest agi ab usoribus ex grege $1.',
'badaccess-group2' => 'Actum quod petivisti solum potest agi ab usoribus ex uno gregum $1.',
'badaccess-groups' => 'Actum quod petivisti solum potest agi ab usoribus ex uno gregum $1.',

'retrievedfrom'           => 'Receptum de "$1"',
'youhavenewmessages'      => 'Habes $1 ($2).',
'newmessageslink'         => 'nuntia nova',
'newmessagesdifflink'     => 'dissimilia post mutationem ultimam',
'youhavenewmessagesmulti' => 'Habes nuntia nova in $1',
'editsection'             => 'recensere',
'editold'                 => 'recensere',
'editsectionhint'         => 'Recensere partem: $1',
'toc'                     => 'Index',
'showtoc'                 => 'monstrare',
'hidetoc'                 => 'celare',
'thisisdeleted'           => 'Videre aut restituere $1?',
'restorelink'             => '{{PLURAL:$1|unam emendationem deletam|$1 emendationes deletas}}',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Res',
'nstab-user'      => 'Pagina usoris',
'nstab-media'     => 'Media',
'nstab-special'   => 'Specialis',
'nstab-project'   => 'Consilium',
'nstab-image'     => 'Fasciculus',
'nstab-mediawiki' => 'Nuntium',
'nstab-template'  => 'Formula',
'nstab-help'      => 'Auxilium',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Actio non est',
'nosuchactiontext'  => 'Actio in URL designata non agnoscitur a hoc vici.',
'nosuchspecialpage' => 'Pagina specialis non est',
'nospecialpagetext' => 'Paginam specialem invalidam petivisti. Pro indice paginarum specialum validarum, vide [[Special:Specialpages|{{MediaWiki:Specialpages}}]].',

# General errors
'error'               => 'Error',
'databaseerror'       => 'Error in basi datorum',
'noconnect'           => 'Nos paenitet! {{SITENAME}} per aerumnas technicas agit, et server basis datorum invenire non potest. <br />
$1',
'cachederror'         => 'Quae sequuntur sunt ex exemplo conditivo paginae quaesitae, fortasse non recente.',
'internalerror'       => 'Error internus',
'internalerror_info'  => 'Error internus: $1',
'badarticleerror'     => 'Haec actio non perfici potest in hac pagina.',
'cannotdelete'        => 'Pagina vel fasciculus deleri non potuit. (Fortasse usor alius iam deleverat.)',
'badtitle'            => 'Titulus malus',
'badtitletext'        => 'Nomen paginae quaestae fuit invalidum, vacuum, aut praeverbium interlingualem vel intervicialem habuit. Fortasse insunt una aut plus litterarum quae in titulis non possunt inscribier.',
'viewsource'          => 'Fontem videre',
'viewsourcefor'       => 'pro $1',
'protectedpagetext'   => 'Haec pagina protecta est, ut emendationes prohibeantur.',
'viewsourcetext'      => 'Fontem videas et exscribeas:',
'protectedinterface'  => 'Haec pagina dat textum interfaciei pro logiciali, et est protecta ad vandalismum vetandum.',
'editinginterface'    => "'''Caveat censor:''' Emendas iam paginam quae textum interfaciei logicialem dat. Mutationes vultum {{grammar:genitive|{{SITENAME}}}} omnibus usoribus afficient. In nuntia MediaWiki vertendo, quaesumus te uti [http://translatewiki.net/wiki/Translating:Intro Betawiki].",
'ns-specialprotected' => 'Paginae in spatio nominali "{{ns:special}}" recenseri non possunt.',
'titleprotected'      => 'Hic titulus protectus est ab usore [[User:$1|$1]] ne creetur. Ratio data est <i>$2</i>.',

# Login and logout pages
'logouttitle'                => 'Conventum concludere',
'logouttext'                 => '<strong>Conventum tuum conclusum est.</strong><br />
Ignote continues {{grammar:ablative|{{SITENAME}}}} uti, aut conventum novum vel sub eodem vel novo nomine aperias. Nota bene paginas fortasse videantur quasi tuum conventum esset apertum, priusquam navigatrum purgaveris.',
'welcomecreation'            => '== Salve, $1! ==

Ratio tua iam creata est. Noli oblivisci praeferentias tuas mutare.',
'loginpagetitle'             => 'Conventum aperire',
'yourname'                   => 'Nomen tuum usoris:',
'yourpassword'               => 'Tessera tua:',
'yourpasswordagain'          => 'Tesseram tuam adfirmare:',
'remembermypassword'         => 'Tesseram meam inter conventa memento',
'yourdomainname'             => 'Regnum tuum:',
'loginproblem'               => '<b>Problema erat aperiens conventum tuum.</b><br />Conare denuo!',
'login'                      => 'Conventum aperire',
'loginprompt'                => 'Cookies potestatem facere debes ut conventum aperire.',
'userlogin'                  => 'Conventum aperire',
'logout'                     => 'Conventum concludere',
'userlogout'                 => 'Conventum concludere',
'notloggedin'                => 'Conventum non apertum est',
'nologin'                    => 'Num rationem non habes? $1!',
'nologinlink'                => 'Eam crea',
'createaccount'              => 'Rationem novam creare',
'gotaccount'                 => 'Habesne iam rationem? $1.',
'gotaccountlink'             => 'Conventum aperi',
'createaccountmail'          => 'ab inscriptione electronica',
'badretype'                  => 'Tesserae quas scripsisti inter se non congruunt.',
'userexists'                 => 'Nomen usoris quod selegisti iam est. Nomen usoris alium selige.',
'youremail'                  => 'Inscriptio tua electronica:',
'username'                   => 'Nomen usoris:',
'uid'                        => 'ID usoris:',
'yourrealname'               => 'Nomen tuum verum:',
'yourlanguage'               => 'Lingua tua:',
'yourvariant'                => 'Differentia:',
'yournick'                   => 'Agnomen tuum (in subscriptionibus):',
'badsig'                     => 'Subscriptio cruda non est valida; scrutina HTML textos.',
'badsiglength'               => 'Agnomen nimis longum; $1 litterae sunt longitudo maxima.',
'email'                      => 'Litterae electronicae',
'prefs-help-realname'        => 'Nomen verum (non necesse): si vis id dare, opera tua tibi ascribantur.',
'loginerror'                 => 'Error factus est in aperiendo conventum',
'prefs-help-email'           => 'Inscriptio tua electronica (non necesse): Sinit aliis tecum loqui per tuam paginam usoris vel disputationis, nisi te reveles.',
'nocookiesnew'               => "Ratio usoris creata est, sed conventum non apertum est. {{SITENAME}} ''Cookies'' utitur in usorum conventa aperiendo. Cookies tua debiles sunt. Eis potestatem fac, tum conventum aperi cum nomine usoris tesseraque tua nova.",
'nocookieslogin'             => "{{SITENAME}} ''Cookies'' utitur in usorum conventa aperiendo. Cookies tua debiles sunt. Eis potestatem fac, tum conare denuo.",
'noname'                     => 'Nominem usoris ratum non designavisti.',
'loginsuccesstitle'          => 'Conventum prospere apertum est',
'loginsuccess'               => "'''Apud {{grammar:accusative|{{SITENAME}}}} agnosceris ut \"\$1\".'''",
'nosuchuser'                 => 'Nomen usoris "$1" non est. Orthographiam confirma, aut novam rationem usoris crea.',
'nosuchusershort'            => 'Nomen usoris "$1" non est. Orthographiam confirma.',
'nouserspecified'            => 'Nomen usoris indicare debes.',
'wrongpassword'              => 'Tessera quam scripsisti non constat. Conare denuo.',
'wrongpasswordempty'         => 'Tesseram vacuam scripsisti. Conare denuo.',
'mailmypassword'             => 'Tesseram novam per litteras electronicas petere',
'passwordremindertitle'      => 'Nova tessera apud {{grammar:accusative|{{SITENAME}}}}',
'passwordremindertext'       => 'Aliquis (tu probabiliter, cum loco de IP $1)
tesseram novam petivit pro {{grammar:ablative|{{SITENAME}}}} ($4).
Tessera usoris "$2" nunc est "$3".
Conventum aperias et statim tesseram tuam mutes.

Si non ipse hanc petitionem fecisti, aut si tesseram tuam
meministi et etiam nolis eam mutare, potes hunc nuntium
ignorare, et tessera seni uti continuare.',
'acct_creation_throttle_hit' => 'Nos paenitet, etiam rationes $1 creavisti. Plurimas non tibi licet creare.',
'emailauthenticated'         => 'Tua inscriptio electronica recognita est $1.',
'accountcreated'             => 'Ratio creata',
'accountcreatedtext'         => 'Ratio pro usore $1 creata est.',
'createaccount-title'        => 'Ratio creata apud {{grammar:accusative|{{SITENAME}}}}',
'createaccount-text'         => 'Aliquis ($1) creavit rationem pro $2 apud {{grammar:accusative|{{SITENAME}}}}
($4). Tessera usoris "$2" est "$3". Conventum aperias et statim tesseram tuam mutes.

Hunc nuntium ignorare potes, si nolis hac ratione uti.',
'loginlanguagelabel'         => 'Lingua: $1',

# Password reset dialog
'resetpass' => 'Tesseram novam creare',

# Edit page toolbar
'bold_sample'    => 'Litterae pingues',
'bold_tip'       => 'Litterae pingues',
'italic_sample'  => 'Textus litteris italicis scriptus',
'italic_tip'     => 'Textus litteris italicis scriptus',
'link_sample'    => 'Titulum nexere',
'link_tip'       => 'Nexus internus',
'extlink_sample' => 'http://www.example.com titulus nexus externi',
'extlink_tip'    => 'Nexus externus (memento praefixi http://)',
'math_sample'    => 'Hic inscribe formulam',
'math_tip'       => 'Formula mathematica (LaTeX)',
'image_sample'   => 'Exemplum.jpg',
'image_tip'      => 'Imago in pagina imposita',
'media_sample'   => 'Exemplum.ogg',
'media_tip'      => 'Nexus ad fasciculum mediorum',
'sig_tip'        => 'Subscriptio tua cum indicatione temporis',
'hr_tip'         => 'Linea horizontalis (noli saepe uti)',

# Edit pages
'summary'                   => 'Summarium',
'subject'                   => 'Res/titulus',
'minoredit'                 => 'Haec est recensio minor',
'watchthis'                 => 'Custodire hanc paginam',
'savearticle'               => 'Servare hanc rem',
'preview'                   => 'Praevidere',
'showpreview'               => 'Monstrare praevisum',
'showlivepreview'           => 'Monstrare praevisum viventem',
'showdiff'                  => 'Mutata ostendere',
'anoneditwarning'           => "'''Monitio:''' Conventum tuum non apertum. Locus IP tuus in historia huius paginae notabitur.",
'missingcommenttext'        => 'Sententiam subter inscribe.',
'summary-preview'           => 'Praevisum summarii',
'subject-preview'           => 'Praevisum rei/tituli',
'blockedtitle'              => 'Usor obstructus est',
'blockedtext'               => '<big>\'\'\'Nomen usoris aut locus IP tuus obstructus est\'\'\'</big> a magistratu $1.

Ratio data est: \'\'$2\'\'.

* Initium obstructionis: $8
* Finis obstructionis: $6
* Obstructus est: $7

Potes ad $1 aut [[{{MediaWiki:Grouppage-sysop}}|magistratum]] alium nuntium mittere ad impedimentum disputandum.
Nota bene te non posse proprietate "Litteras electronicas usori mittere" uti, nisi tibi est inscriptio electronica confirmata apud [[Special:Preferences|praeferentias usoris tuas]] vel si tibi etiam litterae electronicae obstructi sunt.
Locus IP tuus est $3, et numerus obstructionis est #$5. Quaesumus te eos scripturum si quaestiones ullas roges.',
'autoblockedtext'           => 'Locus IP tuus automatice obstructus est quia usor alius, qui a magistratu $1 obstructus est, eum adhiberat.
Ratio data est:

:\'\'$2\'\'

* Initium obstructionis erit: $8
* Finis obstructionis erit: $6

Potes ad $1 aut [[{{MediaWiki:Grouppage-sysop}}|magistratum]] alium nuntium mittere ad impedimentum disputandum.

Nota bene te non posse proprietate "Litteras electronicas usori mittere" uti, nisi tibi est inscriptio electronica confirmata apud [[Special:Preferences|praeferentias usoris tuas]].

Numerus obstructionis tuus est #$5. Quaesumus te eum scripturum si quaestiones ullas roges.',
'blockedoriginalsource'     => "Fons '''$1''' subter monstratur:",
'blockededitsource'         => "Textus '''tuarum emendationum''' in '''$1''' subter monstratur:",
'whitelistedittitle'        => 'Conventum aperiendum ut recenseas',
'whitelistedittext'         => 'Necesse est tibi $1 priusquam paginas recenseas.',
'whitelistreadtitle'        => 'Conventum aperiendum ut legas',
'whitelistreadtext'         => 'Necesse est tibi [[Special:Userlogin|conventum aperire]] priusquam paginas legas.',
'whitelistacctitle'         => 'Non licet tibi rationem creare',
'confirmedittitle'          => 'Adfirmanda est inscriptio tua electronica prisuquam recenseas',
'confirmedittext'           => 'Tua inscriptio electronica est adfirmanda priusquam paginas recenseas. Quaesumus eam selige et adfirma per tuas [[Special:Preferences|praeferentias]].',
'nosuchsectiontitle'        => 'Haec pars non est',
'nosuchsectiontext'         => 'Partem inexistentem recensere conaris. Quia pars $1 non est, recensio tua servari non potest.',
'loginreqtitle'             => 'Conventum aperiendum',
'loginreqlink'              => 'conventum aperire',
'loginreqpagetext'          => 'Necesse est tibi $1 priusquam paginas alias legas.',
'accmailtitle'              => 'Tessera missa est.',
'accmailtext'               => 'Tessera usoris "$1" ad $2 missa est.',
'newarticle'                => '(Nova)',
'newarticletext'            => "Per nexum progressus es ad paginam quae nondum exsistit.
Novam paginam si vis creare, in capsam infra praebitam scribe.
(Vide [[{{MediaWiki:Helppage}}|paginam auxilii]] si plura cognoscere vis.)
Si hic es propter errorem, solum '''Retrorsum''' in navigatro tuo preme.",
'anontalkpagetext'          => "---- ''Haec est pagina disputationis usoris anonymi, solum a loco IP suo noti. Memento locos IP aliquando mutaturos, et a usoribus multis fortasse adhibitos. Si es usor ignotus, et tibi querulae sine ratione datae sunt, conventum [[Special:Userlogin|aperi vel crea]] ad confusionem solvendam. Nota locum IP tuum concelatum esse convento aperto si de rebus privatis tuis es sollicitatus.''",
'noarticletext'             => 'In hac pagina nondum litterae sunt. Potes etiam [[Special:Search/{{PAGENAME}}|hanc rem in aliis paginis quaerere]] aut [{{fullurl:{{FULLPAGENAME}}|action=edit}} hanc paginam creare].',
'userpage-userdoesnotexist' => 'Usor "$1" non est. Visne re vera hanc paginam creare vel recensere?',
'updated'                   => '(Novata)',
'note'                      => '<strong>Nota:</strong>',
'previewnote'               => '<strong>Memento hanc paginam solum praevisum esse, neque iam servatam!</strong>',
'editing'                   => 'Recensio paginae "$1"',
'editinguser'               => 'Recensio <b>$1</b>',
'editingsection'            => 'Recensens $1 (partem)',
'editingcomment'            => 'Recensens $1 (adnotum)',
'editconflict'              => 'Contentio recensionis: $1',
'explainconflict'           => 'Alius hanc paginam mutavit postquam eadem mutare incipiebas.
Capsa superior paginae verba recentissima continet.
Mutationes tuae in capsa inferiore monstrantur.
Mutationes tuae in verbam superiorem adiungare debes.
<b>Solum</b> verba capsae superioris servabuntur quando "Servare hanc rem" premes.<br />',
'yourtext'                  => 'Sententia tua',
'storedversion'             => 'Verba recentissima',
'nonunicodebrowser'         => '<strong>CAVETO: Navigatorium retiale tuum systemati UNICODE morem non gerit. Modum habemus quo commentationes sine damno recenseas: litterae non-ASCII in capsa sub veste hexadecimali ostendentur.</strong>',
'editingold'                => '<strong>MONITIO: Formam obsoletam huius paginae mutas.
Si eam servaveris, omnes mutationes recentiores obrogatae peribunt!</strong>',
'yourdiff'                  => 'Dissimilitudo',
'copyrightwarning'          => 'Nota bene omnia contributa divulgari sub \'\'$2\'\' (vide singula apud $1).
Nisi vis verba tua crudelissime recenseri, mutari, et ad libidinem redistribui, noli ea submittere.<br />
Nobis etiam spondes te esse ipsum horum verborum scriptorem primum, aut ex opere in "dominio publico" exscripsisse.
<strong>NOLI OPERIBUS SUB IURE DIVULGANDI UTI SINE POTESTATE!</strong>',
'copyrightwarning2'         => 'Nota bene omnia contributa divulgari sub \'\'$2\'\' (vide singula apud $1).
Nisi vis verba tua crudelissime recenseri, mutari, et ad libidinem redistribui, noli ea submittere.<br />
Nobis etiam spondes te esse ipsum horum verborum scriptorem primum, aut ex opere in "dominio publico" exscripsisse.
<strong>NOLI OPERIBUS SUB IURE DIVULGANDI UTI SINE POTESTATE!</strong>',
'longpagewarning'           => 'MONITIO: Haec pagina est $1 chilioctetis longa;
aliquae navigatra paginas longiores quam 32 chiliocteti recensere non possunt.
Considera paginam in partes minores frangere.',
'protectedpagewarning'      => '<strong>CAVE: Haec pagina protecta est ut magistratus soli eam recenseant.</strong>',
'templatesused'             => 'Formulae hac in pagina adhibitae:',
'templatesusedpreview'      => 'Formulae hoc in praeviso adhibitae:',
'templatesusedsection'      => 'Formulae hac in parte adhibitae:',
'template-protected'        => '(protecta)',
'template-semiprotected'    => '(semi-protecta)',
'permissionserrors'         => 'Errores permissionis',

# "Undo" feature
'undo-summary' => 'abrogans recensionem $1 ab usore [[User:$2|$2]] ([[User talk:$2|Disputatio]] | [[Special:Contributions/$2|conlationes]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ratio creari non potest',
'cantcreateaccount-text' => "Creatio rationum ab hoc loco IP (<b>$1</b>) obstructa est ab usore [[User:$3|$3]].

Ille hanc causam dedit: ''$2''",

# History pages
'viewpagelogs'        => 'Vide acta huius paginae',
'nohistory'           => 'Huic paginae non est historia.',
'revnotfound'         => 'Emendatio non inventa',
'revnotfoundtext'     => 'Emendatio quem rogavisti non inventa est. 
Confirma URL paginae.',
'loadhist'            => 'Onerans historiam paginae',
'currentrev'          => 'Emendatio recentissima',
'revisionasof'        => 'Emendatio ex $1',
'revision-info'       => 'Emendatio ex $1 ab $2',
'previousrevision'    => '← Emendatio senior',
'nextrevision'        => 'Emendatio novior →',
'currentrevisionlink' => 'Emendatio currens',
'cur'                 => 'nov',
'next'                => 'seq',
'last'                => 'prox',
'page_first'          => 'prim',
'page_last'           => 'ult',
'histlegend'          => 'Selige pro dissimilitudine: indica in botones radiales et "intrare" in claviatura imprime ut conferas.

Titulus: (nov) = dissimilis ab forma novissima, (prox) = dissimilis ab forma proxima, M = recensio minor',
'deletedrev'          => '[deleta]',
'histfirst'           => 'Veterrimus',
'histlast'            => 'Novissimus',
'historysize'         => '({{PLURAL:$1|1 octetus|$1 octeti}})',
'historyempty'        => '(vacua)',

# Revision feed
'history-feed-title' => 'Historia',

# Revision deletion
'rev-deleted-user' => '(nomen usoris remotum est)',
'revisiondelete'   => 'Emendationem delere',

# Diffs
'history-title'           => 'Historia paginae "$1"',
'difference'              => '(Dissimilitudo inter emendationes)',
'compareselectedversions' => 'Conferre versiones selectas',
'editundo'                => 'abrogare',

# Search results
'searchresults'         => 'Eventum investigationis',
'searchresulttext'      => 'Pro plurimis nuntiis de investigatione in {{grammar:ablative|{{SITENAME}}}}, vide [[{{MediaWiki:Helppage}}|{{MediaWiki:Help}}]].',
'searchsubtitle'        => "Pro investigatione '''[[:$1]]'''",
'searchsubtitleinvalid' => 'Pro investigatione "$1"',
'noexactmatch'          => "'''Nulla pagina cum titulo \"\$1\" exacto existit.''' Potes [[:\$1|eam creare]].",
'noexactmatch-nocreate' => "'''Nulla pagina cum titulo \"\$1\" exacto existit.'''",
'titlematches'          => 'Exaequata indicibus rerum',
'notitlematches'        => 'Nulla exaequata',
'prevn'                 => '$1 superiores',
'nextn'                 => '$1 proxima',
'viewprevnext'          => 'Videre ($1) ($2) ($3).',
'showingresults'        => "Subter monstrans {{PLURAL:$1|'''1''' eventu|'''$1''' eventibus}} tenus incipiens ab #'''$2'''.",
'showingresultsnum'     => "Subter monstrans {{PLURAL:$3|'''1''' eventum|'''$3''' eventus}} incipiens ab #'''$2'''.",
'nonefound'             => "'''Nota''': investigationes saepe infelices sunt propter verba frequentes huiusmodi \"que\" et \"illo\", aut quod plus unum verba quaerere designavisti (solae paginae qui tota verba investigationis continent in evento apparebit).",
'powersearch'           => 'Quaerere',
'powersearchtext'       => 'Quaerere in spatiis nominalibus:<br />$1<br />$2 Monstrare redirectiones<br />Quaerere $3 $9',
'searchdisabled'        => 'Per {{grammar:accusative|{{SITENAME}}}} ad tempus non potes quaerere. Interea per [http://www.google.com Googlem] quaeras. Nota indices {{grammar:genitive|{{SITENAME}}}} contentorum apud Googlem fortasse antiquiores esse.',

# Preferences page
'preferences'              => 'Praeferentiae',
'mypreferences'            => 'Praeferentiae meae',
'prefs-edits'              => 'Numerus recensionum:',
'prefsnologin'             => 'Conventum non apertum',
'prefsnologintext'         => '[[Special:Userlogin|Conventum aperire]] debes ut praeferentiae tuae perscribere.',
'prefsreset'               => 'Praeferentiae tuae reperscriptae sunt.',
'qbsettings'               => 'Figuratio claustri celeris',
'qbsettings-none'          => 'Nullus',
'qbsettings-fixedleft'     => 'Constituere a sinistra',
'qbsettings-fixedright'    => 'Constituere a dextra',
'qbsettings-floatingleft'  => 'Innens a sinistra',
'qbsettings-floatingright' => 'Innens a dextra',
'changepassword'           => 'Mutare tesseram',
'skin'                     => 'Aspectum',
'math'                     => 'Interpretatio artis mathematicae',
'dateformat'               => 'Forma diei',
'datedefault'              => 'Nullum praeferentiae',
'datetime'                 => 'Dies et tempus',
'math_failure'             => 'Excutare non potest',
'math_unknown_error'       => 'error ignotus',
'math_unknown_function'    => 'functio ignota',
'prefs-personal'           => 'Minutiae rationis',
'prefs-rc'                 => 'Nuper mutata',
'prefs-watchlist'          => 'Paginae custoditae',
'prefs-watchlist-days'     => 'Numerus dierum displicandus in paginis tuis custoditis:',
'prefs-watchlist-edits'    => 'Numerus recensionum displicandus in paginis tuis custoditis extensis:',
'saveprefs'                => 'Servare praeferentias',
'resetprefs'               => 'Reddere praeferentias',
'oldpassword'              => 'Tessera vetus:',
'newpassword'              => 'Tessera nova:',
'retypenew'                => 'Adfirmare tesseram novam:',
'textboxsize'              => 'Mensura capsae verbi',
'rows'                     => 'Lineae:',
'columns'                  => 'Columnae:',
'searchresultshead'        => 'Figuratio eventorum investigationis',
'resultsperpage'           => 'Eventa per paginam:',
'contextlines'             => 'Lineae per eventum:',
'contextchars'             => 'Litterae contexti per lineam:',
'recentchangesdays'        => 'Quot dies in nuper mutatis monstrandi:',
'recentchangescount'       => 'Quantum rerum in nuper mutatis:',
'savedprefs'               => 'Praeferentiae tuae servatae sunt.',
'timezonetext'             => 'Scribere numerum horae inter horam tuam et illam moderatri (UTC).',
'localtime'                => 'Hora indigena',
'timezoneoffset'           => 'Dissimilitudo cinguli horae¹',
'servertime'               => 'Hora moderatri nunc est',
'guesstimezone'            => 'Hora ex navigatro scribere',
'allowemail'               => 'Sinere litteras electronicas mitti tuae inscriptioni electronicae',
'defaultns'                => 'Quaerere per haec spatia nominalia a defalta:',
'files'                    => 'Fasciculi',

# User rights
'editusergroup'               => 'Greges usorum recensere',
'userrights-available-add'    => 'Potes usores addere ad $1.',
'userrights-available-remove' => 'Potes usores removere ex $1.',

# Groups
'group'            => 'Grex:',
'group-sysop'      => 'Magistratus',
'group-bureaucrat' => 'Grapheocrates',
'group-all'        => '(omnes)',

'group-sysop-member'      => 'Magistratus',
'group-bureaucrat-member' => 'Grapheocrates',

'grouppage-sysop'      => '{{ns:project}}:Magistratus',
'grouppage-bureaucrat' => '{{ns:project}}:Grapheocrates',

# User rights log
'rightslog'     => 'Index mutationum iuribus usorum',
'rightslogtext' => 'Haec est index mutationum iuribus usorum.',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|mutatio|mutationes}}',
'recentchanges'                     => 'Nuper mutata',
'recentchangestext'                 => 'Inspice mutationes recentes huic vici in hac pagina.',
'rcnote'                            => "Subter {{PLURAL:$1|est '''1''' nuper mutatum|sunt '''$1''' nuperrime mutata}} in {{PLURAL:$2|die proximo|'''$2''' diebus proximis}}, ad $3 tempus.",
'rcnotefrom'                        => 'Subter sunt <b>$1</b> nuperrime mutata in proxima <b>$2</b> die.',
'rclistfrom'                        => 'Monstrare mutata nova incipiens ab $1',
'rcshowhideminor'                   => '$1 recensiones minores',
'rcshowhideliu'                     => '$1 usores notos',
'rcshowhideanons'                   => '$1 usores ignotos',
'rcshowhidemine'                    => '$1 conlationes meas',
'rclinks'                           => 'Monstrare $1 nuperrime mutata in $2 diebus proximis.<br />$3',
'diff'                              => 'diss',
'hist'                              => 'hist',
'hide'                              => 'celare',
'show'                              => 'monstrare',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|usor custodiens|usores custodientes}}]',
'rc_categories_any'                 => 'Ulla',
'newsectionsummary'                 => '/* $1 */ nova pars',

# Recent changes linked
'recentchangeslinked'         => 'Nuper mutata annexorum',
'recentchangeslinked-title'   => 'Nuper mutata in paginis quibus pagina $1 nectit',
'recentchangeslinked-summary' => "Subter sunt nuperrime mutata paginis annexis. Paginae a te custoditae '''litteris pinguis''' monstrantur.",

# Upload
'upload'            => 'Fasciculum onerare',
'uploadbtn'         => 'Fasciculum onerare',
'reupload'          => 'Reonerare',
'reuploaddesc'      => 'Redire ad formulam onerationis.',
'uploadnologin'     => 'Conventum non apertum est',
'uploadnologintext' => '[[Special:Userlogin|Aperire conventum]] debes ut fasciculos oneres.',
'uploaderror'       => 'Error onerati',
'uploadtext'        => "Utere formam subter ad fasciculos onerandos. Ut videas aut quaeras fasciculos oneratos antea, adi [[Special:Imagelist|indicem fasciculorum oneratorum]]. Onerata et deleta in [[Special:Log/upload|notatione oneratorum]] notata sunt.

Ad imaginem includendum in pagina, utere nexu
'''<nowiki>[[</nowiki>{{ns:image}}:File.jpg]]''' aut
'''<nowiki>[[</nowiki>{{ns:image}}:File.png|verba alia]]''' aut
'''<nowiki>[[</nowiki>{{ns:media}}:File.ogg]]''' pro nexum directum ad fasciculum.",
'uploadlog'         => 'Notatio onerati',
'uploadlogpage'     => 'Notatio onerati',
'uploadlogpagetext' => 'Subter est index fasciculorum recentissimorum oneratorum.',
'filename'          => 'Nomen fasciculi',
'filedesc'          => 'Descriptio',
'fileuploadsummary' => 'Descriptio:',
'filestatus'        => 'Locus verborum privatorum',
'filesource'        => 'Fons',
'uploadedfiles'     => 'Fasciculi onerati',
'ignorewarning'     => 'Ignorare monita et servare fasciculum.',
'ignorewarnings'    => 'Ignorare monita omnes',
'minlength1'        => 'Nomina fasciculorum saltem unam litteram habere debent.',
'badfilename'       => 'Nomen fasciculi ad "$1" mutatum est.',
'large-file'        => 'Suasum est ut fasciculi $1 magnitudine non excedant; magnitudo huius fasciculi est $2.',
'fileexists-thumb'  => "<center>'''Imago quae iam est'''</center>",
'successfulupload'  => 'Oneratum perfectum',
'uploadwarning'     => 'Monitus onerati',
'savefile'          => 'Servare fasciculum',
'uploadedimage'     => 'oneravit "[[$1]]"',
'uploadvirus'       => 'Fasciculi huic est virus! Singula: $1',
'watchthisupload'   => 'Custodire hanc paginam',

'upload-file-error' => 'Error internus',

'license-nopreview' => '(Praevisum monstrari non potest)',

# Image list
'imagelist'                 => 'Fasciculi',
'imagelisttext'             => "Subter est index {{plural:$1|'''unius''' fasciculi|'''$1''' fasciculorum}} digestus $2.",
'getimagelist'              => 'onerans indicem fasciculorum',
'ilsubmit'                  => 'Quaerere',
'byname'                    => 'ex nomine',
'bydate'                    => 'ex die',
'bysize'                    => 'ex magnitudine',
'imgfile'                   => 'fasciculus',
'filehist'                  => 'Historia fasciculi',
'filehist-help'             => 'Ad emendationem fasciculi inspiciendum, preme in diem/tempus.',
'filehist-deleteall'        => 'delere omnes emendationes',
'filehist-deleteone'        => 'delere hanc emendationem',
'filehist-revert'           => 'revertere',
'filehist-current'          => 'recentissima',
'filehist-datetime'         => 'Dies/Tempus',
'filehist-user'             => 'Usor',
'filehist-dimensions'       => 'Dimensiones',
'filehist-filesize'         => 'Magnitudo fasciculi',
'filehist-comment'          => 'Summarium',
'imagelinks'                => 'Nexus',
'linkstoimage'              => 'Paginae sequentes ad hunc fasciculum nectunt:',
'nolinkstoimage'            => 'Nullae paginae ad hunc fasciculum nectunt.',
'noimage'                   => 'Fasciculus huius nominis non est. $1 potes.',
'noimage-linktext'          => 'Fasciculum huius nominis onerare',
'uploadnewversion-linktext' => 'Emendationem novam huius fasciculi onerare',
'imagelist_name'            => 'Nomen',
'imagelist_user'            => 'Usor',
'imagelist_size'            => 'Magnitudo',
'imagelist_description'     => 'Descriptio',

# File reversion
'filerevert-legend'         => 'Reverti fasciculum',
'filerevert-intro'          => '<span class="plainlinks">Reverteris \'\'\'[[Media:$1|$1]]\'\'\' ad [emendationem $4 ex $3, $2].</span>',
'filerevert-comment'        => 'Sententia:',
'filerevert-defaultcomment' => 'Reverti ad emendationem ex $2, $1',
'filerevert-submit'         => 'Revertere',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' reversum est ad [emendationem $4 ex $3, $2].</span>',

# File deletion
'filedelete'             => 'Delere $1',
'filedelete-legend'      => 'Fasciculum delere',
'filedelete-intro'       => "Deles '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Deles fasciculi \'\'\'[[Media:$1|$1]]\'\'\' emendationem [$4 ex $3, $2].</span>',
'filedelete-comment'     => 'Summarium:',
'filedelete-submit'      => 'Delere',
'filedelete-success'     => "'''$1''' deletum est.",
'filedelete-success-old' => '<span class="plainlinks">Emendatio fasciculi \'\'\'[[Media:$1|$1]]\'\'\' ex $3, $2 deletum est.</span>',
'filedelete-iscurrent'   => 'Emendationem recentissimam huius fasciculi delere conaris. Necesse est antea ad aliam emendationem reverti.',

# MIME search
'mimesearch' => 'Quaerere per MIME',

# Unwatched pages
'unwatchedpages' => 'Paginae incustoditae',

# List redirects
'listredirects' => 'Redirectiones',

# Unused templates
'unusedtemplates' => 'Formulae non in usu',

# Random page
'randompage'         => 'Pagina fortuita',
'randompage-nopages' => 'Non est ulla pagina hoc in spatio nominali.',

# Random redirect
'randomredirect'         => 'Redirectio fortuita',
'randomredirect-nopages' => 'Non est ulla redirectio hoc in spatio nominali.',

# Statistics
'statistics'    => 'Census',
'sitestats'     => 'Census {{grammar:genitive|{{SITENAME}}}}',
'userstats'     => 'Census usorum',
'sitestatstext' => "Basis datorum '''$1''' {{PLURAL:$1|paginam|paginas}} habet.
Hic numerus paginas disputationum includit, paginas de {{grammar:ablative|{{SITENAME}}}}, stipulas, paginas redirectionum, et paginas alias quae probabiliter non sunt paginae contenta habentes legitimae.
His exclusis, {{PLURAL:$2|est '''1''' pagina|sunt '''$2''' paginae}} quae contenta recta habere {{PLURAL:$2|putatur|putantur}}.

'''$8''' {{PLURAL:$8|fasciculus oneratus est.|fasciculi onerati sunt.}}

{{PLURAL:$3|Pagina '''1''' visa est|Paginae '''$3''' visae sunt}}, et '''$4''' {{PLURAL:$4|recensio paginae facta est|recensiones paginarum factae sunt}}
ab initio {{grammar:genitive|{{SITENAME}}}}.
Hoc aequat fere '''$5''' mutationes per paginam, et '''$6''' visae per mutationem.

'''$7''' [http://meta.wikimedia.org/wiki/Help:Job_queue operationes etiam exspectant perfacier].",
'userstatstext' => "'''$1''' {{PLURAL:$1|[[Special:Listusers|usor]] relatus est|[[Special:Listusers|usores]] relati sunt}}, quorum '''$2''' (vel '''$4%''') {{PLURAL:$2|est|sunt}} $5.",

'disambiguations'      => 'Paginae disambiguationis',
'disambiguationspage'  => 'Template:Discretiva',
'disambiguations-text' => "Paginae subsequentes ad '''paginam discretivam''' nectunt. Ad aptam paginam nectere debent.<br />Pagina discretivam esse putatur si formulam adhibet ad quem [[MediaWiki:disambiguationspage]] nectit.",

'doubleredirects' => 'Redirectiones duplices',

'brokenredirects'        => 'Redirectiones fractae',
'brokenredirectstext'    => 'Redirectiones sequentes ad paginas inexistentes nectunt:',
'brokenredirects-edit'   => '(recensere)',
'brokenredirects-delete' => '(delere)',

'withoutinterwiki' => 'Paginae sine nexibus ad linguas alias',

'fewestrevisions' => 'Paginae minime mutatae',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|octetus|octeti}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categoriae}}',
'nlinks'                  => '$1 {{PLURAL:$1|nexus|nexus}}',
'nrevisions'              => '$1 {{PLURAL:$1|emendatio|emendationes}}',
'nviews'                  => '$1 {{PLURAL:$1|visa|visae}}',
'lonelypages'             => 'Paginae non annexae',
'uncategorizedpages'      => 'Paginae sine categoriis',
'uncategorizedcategories' => 'Categoriae sine categoriis',
'uncategorizedimages'     => 'Fasciculi sine categoriis',
'uncategorizedtemplates'  => 'Formulae sine categoriis',
'unusedcategories'        => 'Categoriae non in usu',
'unusedimages'            => 'Fasciculi non in usu',
'popularpages'            => 'Paginae saepe monstratae',
'wantedcategories'        => 'Categoriae desideratae',
'wantedpages'             => 'Paginae desideratae',
'mostlinked'              => 'Paginae maxime annexae',
'mostlinkedcategories'    => 'Categoriae maxime annexae',
'mostlinkedtemplates'     => 'Formulae maxime annexae',
'mostcategories'          => 'Paginae plurimis categoriis',
'mostimages'              => 'Fasciculi maxime annexi',
'mostrevisions'           => 'Paginae plurimum mutatae',
'allpages'                => 'Paginae omnes',
'prefixindex'             => 'Quaerere per praefixa',
'shortpages'              => 'Paginae breves',
'longpages'               => 'Paginae longae',
'deadendpages'            => 'Paginae sine nexu',
'deadendpagestext'        => 'Paginae hae sequentes non nectunt ad alias paginas ullas in {{grammar:ablative|{{SITENAME}}}}.',
'protectedpages'          => 'Paginae protectae',
'protectedpagestext'      => 'Paginae sequentes protectae sunt a movendo ac recensendo',
'protectedtitles'         => 'Tituli protecti',
'protectedtitlestext'     => 'Tituli sequentes protecti sunt ne creentur paginae',
'listusers'               => 'Usores',
'specialpages'            => 'Paginae speciales',
'spheading'               => 'Paginae speciales',
'restrictedpheading'      => 'Paginae speciales propriae',
'newpages'                => 'Paginae novae',
'newpages-username'       => 'Nomen usoris:',
'ancientpages'            => 'Paginae veterrimae',
'intl'                    => 'Nexus inter linguas',
'move'                    => 'Movere',
'movethispage'            => 'Movere hanc paginam',
'notargettitle'           => 'Nullus scopus',
'notargettext'            => 'Paginam aut usorem non notavisti.',
'pager-newer-n'           => '{{PLURAL:$1|novior 1|noviores $1}}',
'pager-older-n'           => '{{PLURAL:$1|senior 1|seniores $1}}',

# Book sources
'booksources'    => 'Librorum fontes',
'booksources-go' => 'Ire',

'categoriespagetext' => 'Huic vici sunt hae categoriae.',
'userrights'         => 'Usorum potestas',
'alphaindexline'     => '$1 ad $2',
'version'            => 'Versio',

# Special:Log
'specialloguserlabel'  => 'Usor:',
'speciallogtitlelabel' => 'Titulus:',
'log'                  => 'Acta',
'all-logs-page'        => 'Acta omnia',
'log-search-legend'    => 'In actis quaerere',
'log-search-submit'    => 'Ire',
'alllogstext'          => 'Ostentantur mixte indices onerationum, deletionum, protectionum, obstructionum, et administratorum.
Adspectum graciliorem potes facere modum indicum, nomen usoris, vel paginam petitam seligendo.',
'log-title-wildcard'   => 'Quaerere titulos qui incipiunt litteris',

# Special:Allpages
'nextpage'          => 'Pagina proxima ($1)',
'prevpage'          => 'Pagina superior ($1)',
'allpagesfrom'      => 'Monstrare paginas ab:',
'allarticles'       => 'Omnes paginae',
'allinnamespace'    => 'Omnes paginae (in spatio nominali $1)',
'allnotinnamespace' => 'Omnes paginae (quibus in spatio nominali $1 exclusis)',
'allpagesprev'      => 'Superior',
'allpagesnext'      => 'Proxima',
'allpagessubmit'    => 'Ire',
'allpagesprefix'    => 'Monstrare paginas quibus est praeverbium:',
'allpagesbadtitle'  => 'Nomen paginae datum fuit invalidum aut praverbium interlinguale vel interviciale habuit. Fortasse insunt una aut plus litterarum quae in titulis non possunt inscribier.',
'allpages-bad-ns'   => 'Non est spatium nominale "$1" apud {{grammar:accusative|{{SITENAME}}}}.',

# Special:Listusers
'listusers-submit' => 'Monstrare',

# E-mail user
'emailuser'       => 'Litteras electronicas usori mittere',
'emailpage'       => 'Mittere litteras electronicas huic usori',
'emailpagetext'   => 'Si hic usor inscriptionem electronicam ratum in praeferentias usorum eius dedit, forma subter nuntium mittet.
Inscriptio electronica qui in praeferentiis tuis dedis ut "Ab" inscriptione apparebit. Hoc modo usor tibi respondere poterit.',
'defemailsubject' => '{{SITENAME}} - Litterae electronicae',
'noemailtitle'    => 'Nulla inscriptio electronica',
'noemailtext'     => 'Hic usor inscriptionem electronicam ratam non dedit, aut nuntia ab aliis usoribus non vult.',
'emailfrom'       => 'Ab',
'emailto'         => 'Ad',
'emailsubject'    => 'Res',
'emailmessage'    => 'Nuntium',
'emailsend'       => 'Mittere',
'emailsent'       => 'Litterae electronicae missae sunt',
'emailsenttext'   => 'Nuntium tuum missum est.',

# Watchlist
'watchlist'            => 'Paginae custoditae',
'mywatchlist'          => 'Paginae custoditae',
'watchlistfor'         => "(pro usore '''$1''')",
'nowatchlist'          => 'Nullas paginas custodis.',
'watchlistanontext'    => 'Necesse est $1 ad indicem paginarum custoditarum inspiciendum vel recensendum.',
'watchnologin'         => 'Conventum non est apertum',
'watchnologintext'     => '[[Special:Userlogin|Conventum aperire]] debes ut indicem paginarum custoditarum mutes.',
'addedwatch'           => 'Pagina custodita',
'addedwatchtext'       => "Pagina \"[[:\$1]]\" in [[Special:Watchlist|paginas tuas custoditas]] addita est. Mutationes posthac huic paginae et paginae disputationis ibi notabuntur, et pagina '''litteris pinguibus''' apparebit in [[Special:Recentchanges|nuper mutatorum]] indice, ut sit facilius electu.

Si paginam ex indice paginarum custoditarum removere vis, imprime \"decustodire\" ab summa pagina.",
'removedwatch'         => 'Non iam custodita',
'removedwatchtext'     => 'Pagina "[[:$1]]" non iam custodita est.',
'watch'                => 'custodire',
'watchthispage'        => 'Custodire hanc paginam',
'unwatch'              => 'Decustodire',
'unwatchthispage'      => 'Abrogare custoditum',
'notanarticle'         => 'Res non est',
'watchnochange'        => 'Nullae paginarum custoditarum tuarum recensitae sunt in hoc tempore.',
'watchlist-details'    => '{{PLURAL:$1|$1 pagina custodita|$1 paginae custoditae}} sine paginis disputationis.',
'watchmethod-recent'   => 'recensita recenta quaerens pro pagina custodita',
'watchmethod-list'     => 'paginas custoditas quaerens pro recensitis recentibus',
'watchlistcontains'    => 'Index paginarum custoditarum tuus $1 {{PLURAL:$1|paginam|paginas}} habet.',
'iteminvalidname'      => "Aerumna cum pagina '$1', nomen non est rectum...",
'wlnote'               => "Subter {{PLURAL:$1|est mutatio proxima|sunt '''$1''' mutationes proximae}} in {{PLURAL:$2|proxima hora|proximis '''$2''' horis}}.",
'wlshowlast'           => 'Monstrare proximas $1 horas $2 dies $3',
'watchlist-show-bots'  => 'Monstrare recensiones automatarias',
'watchlist-hide-bots'  => 'Celare recensiones automatarias',
'watchlist-show-own'   => 'Monstrare recensiones meas',
'watchlist-hide-own'   => 'Celare recensiones meas',
'watchlist-show-minor' => 'Monstrare recensiones minores',
'watchlist-hide-minor' => 'Celare recensiones minores',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Custodiens...',
'unwatching' => 'Decustodiens...',

'enotif_impersonal_salutation' => 'Usor {{grammar:genitive|{{SITENAME}}}}',
'changed'                      => 'mutata',
'created'                      => 'creata',
'enotif_lastdiff'              => 'Vide $1 ad hanc recensionem inspiciendum.',
'enotif_anon_editor'           => 'usor ignotus $1',

# Delete/protect/revert
'deletepage'                => 'Delere paginam',
'confirm'                   => 'Adfirmare',
'excontent'                 => "contenta erant: '$1'",
'excontentauthor'           => "contenta erant: '$1' (et contributor unicus erat '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'             => "contenta priusquam pagina facta vacua erant: '$1'",
'exblank'                   => 'pagina vacua erat',
'confirmdelete'             => 'Adfirmare deletionem',
'deletesub'                 => '(Deletio de "$1")',
'historywarning'            => 'Monitio: Pagina quam delere vis historiam habet:',
'confirmdeletetext'         => 'Paginam cum tota historia eius delebis ex base datorum.
Adfirma quaesumus te paginam re vera delere velle, te consequentias intellere, et actionem tuam [[{{MediaWiki:Policy-url}}|consilio]] congruere.',
'actioncomplete'            => 'Actum perfectum',
'deletedtext'               => '"$1" deletum est.
Vide $2 pro indice deletionum recentum.',
'deletedarticle'            => 'delevit "[[$1]]"',
'dellogpage'                => 'Index deletionum',
'dellogpagetext'            => 'Subter est index deletionum recentissimarum.',
'deletionlog'               => 'index deletionum',
'reverted'                  => 'Reversum ad emendationem proximam',
'deletecomment'             => 'Ratio deletionis',
'deleteotherreason'         => 'Causa alia vel explicatio:',
'deletereasonotherlist'     => 'Causa alia',
'rollback'                  => 'Reverti mutationes',
'rollback_short'            => 'Reverti',
'rollbacklink'              => 'reverti',
'rollbackfailed'            => 'Reversum defecit',
'cantrollback'              => 'Haec non potest reverti; conlator proximus solus auctor huius rei est.',
'alreadyrolled'             => 'Ad mutationem proxima paginae "[[$1]]" ab usore "[[User:$2|$2]]" ([[User talk:$2|disputatio]]) reverti non potest; alius paginam iam recensuit vel revertit. Mutatio proxima ab usore "[[User:$3|$3]]" ([[User talk:$3|disputatio]]) effecta est.',
'editcomment'               => 'Dictum recensiti erat: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                => 'Reverti recensiones ab usore [[User:$2|$2]] ([[User talk:$2|Disputatio]] | [[Special:Contributions/$2|conlationes]]) ad mutationem proximam ab [[User:$1|$1]]',
'protectlogpage'            => 'Index protectionum',
'protectlogtext'            => 'Subter est index protectionum et deprotectionum paginarum. Vide [[Special:Protectedpages|indicem paginarum nunc protectarum]].',
'protectedarticle'          => 'protegit "[[$1]]"',
'modifiedarticleprotection' => 'mutavit protectionem paginae "[[$1]]"',
'unprotectedarticle'        => 'deprotegit "[[$1]]"',
'protectsub'                => '(Protegere "$1")',
'confirmprotect'            => 'Protectionem adfirmare',
'protectcomment'            => 'Ratio protegendo:',
'protectexpiry'             => 'Exitus:',
'protect_expiry_invalid'    => 'Tempus exeundo invalidum fuit.',
'unprotectsub'              => '(Deprotegere "$1")',
'protect-level-sysop'       => 'Magistratus soli',
'protect-expiring'          => 'exit $1',
'protect-cascade'           => 'Formulas aliasque paginas hac in pagina adhibitas protegere (protectio quasi cataracta)',
'pagesize'                  => '(octeti)',

# Restrictions (nouns)
'restriction-edit'   => 'Recensio',
'restriction-move'   => 'Motio',
'restriction-create' => 'Creatio',

# Restriction levels
'restriction-level-sysop'         => 'protecta',
'restriction-level-autoconfirmed' => 'semi-protecta',

# Undelete
'undelete'               => 'Paginam deletam restituere',
'undeletepage'           => 'Videre et restituere paginas deletas',
'viewdeletedpage'        => 'Paginas deletas inspicere',
'undeletepagetext'       => 'Paginae sequentes deletae sunt sed in tabulis sunt et restitui possunt. Tabulae nonnumquam deletae sunt.',
'undeleterevisions'      => '$1 {{PLURAL:$1|emendatio servata|emendationes servatae}}',
'undeletehistory'        => 'Si paginam restituis, tota recensita restituentur ad historiam.
Si pagina nova cum ipso nomine post deletionem creata est, recensita restituta in historia prior apparebit, et recensitum recentissimum paginae necessario non renovabitur.',
'undelete-revision'      => 'Emendatio deleta paginae $1 (ex $2) ab usore $3:',
'undeletebtn'            => 'Restituere',
'undeletecomment'        => 'Sententia:',
'undeletedarticle'       => 'restituit "[[$1]]"',
'cannotundelete'         => 'Abrogatio deletionis fefellit; fortasse aliquis iam paginam restituit.',
'undelete-header'        => 'Pro paginis nuper deletis, vide [[Special:Log/delete|indicem deletionum]].',
'undelete-search-box'    => 'Quaerere inter paginas iam deletas',
'undelete-search-prefix' => 'Monstrare paginas quibus est praeverbium:',
'undelete-search-submit' => 'Quaerere',
'undelete-no-results'    => 'Nullae paginae inventae sunt ex his indicibus deletionum.',

# Namespace form on various pages
'namespace'      => 'Spatium nominale:',
'invert'         => 'Selectionem invertere',
'blanknamespace' => '(principale)',

# Contributions
'contributions' => 'Conlationes usoris',
'mycontris'     => 'Conlationes meae',
'contribsub2'   => 'Pro $1 ($2)',
'nocontribs'    => 'Nullae mutationes inventae sunt ex his indiciis.',
'ucnote'        => 'Subter sunt <b>$1</b> mutationes proximae huius usoris in <b>$2</b> diebus proximis.',
'uclinks'       => 'Videre $1 mutationes proximas; videre $2 dies proximos.',
'uctop'         => ' (vertex)',
'month'         => 'Ab mense (et prior):',
'year'          => 'Ab anno (et prior):',

'sp-contributions-newbies'     => 'Monstrare solum conlationes rationum novarum',
'sp-contributions-newbies-sub' => 'Conlationes rationum novarum',
'sp-contributions-blocklog'    => 'Acta obstructionum',
'sp-contributions-search'      => 'Conlationes usoris quaerere',
'sp-contributions-username'    => 'Locus IP aut nomen usoris:',
'sp-contributions-submit'      => 'Ire',

# What links here
'whatlinkshere'       => 'Nexus ad paginam',
'whatlinkshere-title' => 'Paginae quae ad $1 nectunt',
'whatlinkshere-page'  => 'Pagina:',
'linklistsub'         => '(Index nexuum)',
'linkshere'           => "Paginae sequentes ad '''[[:$1]]''' nectunt:",
'nolinkshere'         => "Nullae paginae ad '''[[:$1]]''' nectunt.",
'isredirect'          => 'pagina redirectionis',
'istemplate'          => 'inclusio',
'whatlinkshere-prev'  => '{{PLURAL:$1|superior|$1 superiores}}',
'whatlinkshere-next'  => '{{PLURAL:$1|proxima|proxima $1}}',
'whatlinkshere-links' => '← nexus',

# Block/unblock
'blockip'                  => 'Usorem obstruere',
'blockiptext'              => 'Forma infera utere ut quendam usorem vel locum IP obstruas ne plus scribere potest. Hoc non nisi secundum [[{{MediaWiki:Policy-url}}|consilium]] fieri potest. Rationem certam subscribe (exempli gratia titulos paginarum quas iste usor modo vandalorum recensuit).',
'ipaddress'                => 'Locus IP:',
'ipadressorusername'       => 'Locus IP aut nomen usoris:',
'ipbexpiry'                => 'Exitus:',
'ipbreason'                => 'Causa:',
'ipbreasonotherlist'       => 'Causa alia',
'ipbemailban'              => 'Litteras electronicas vetare',
'ipbsubmit'                => 'Obstruere hunc locum',
'ipbother'                 => 'Exitus alius:',
'ipboptions'               => '2 horas:2 hours,1 diem:1 day,3 dies:3 days,1 hebdomadem:1 week,2 hebdomades:2 weeks,1 mensem:1 month,3 menses:3 months,6 menses:6 months,1 annum:1 year,infinite:infinite', # display1:time1,display2:time2,...
'ipbotheroption'           => 'alius',
'ipbotherreason'           => 'Causa alia vel explicatio:',
'badipaddress'             => 'Locus IP male formatus',
'blockipsuccesssub'        => 'Locus prospere obstructus est',
'blockipsuccesstext'       => '[[Special:Contributions/$1|$1]] obstructus est.
<br />Vide [[Special:Ipblocklist|indicem usorum obstructorum]] ut obstructos revideas.',
'ipb-edit-dropdown'        => 'Causas obstructionum recensere',
'ipb-unblock-addr'         => 'Deobstruere $1',
'ipb-unblock'              => 'Deobstruere nomen usoris vel locum IP',
'unblockip'                => 'Deobstruere locum IP',
'unblockiptext'            => 'Formam inferam usere ut locum IP deobstruere.',
'ipusubmit'                => 'Deobstruere hanc locum',
'ipblocklist'              => 'Usores obstructi',
'ipblocklist-legend'       => 'Usorem obstructum quaerere',
'ipblocklist-username'     => 'Nomen usoris vel locus IP:',
'ipblocklist-submit'       => 'Quaerere',
'blocklistline'            => '$1, $2 obstruxit $3 (exire $4)',
'infiniteblock'            => 'infinita',
'expiringblock'            => 'exit $1',
'anononlyblock'            => 'solum usores ignoti',
'createaccountblock'       => 'Creatio rationum obstructa',
'emailblock'               => 'Litterae electronicae obstructae',
'blocklink'                => 'obstruere',
'unblocklink'              => 'deobstruere',
'contribslink'             => 'conlationes',
'autoblocker'              => 'Obstructus es automatice quia "[[User:$1|$1]]" nuper tuum locum IP adhibuit. Ratio data ob obstructionem usoris $1 est "\'\'\'$2\'\'\'".',
'blocklogpage'             => 'Index obstructorum',
'blocklogentry'            => 'obstruxit "[[$1]]", exire $2 $3',
'blocklogtext'             => 'Hic est index actorum obstructionis deobstructionisque. Loci IP qui automatice obstructi sunt non enumerantur. Vide [[Special:Ipblocklist|indicem usorum locorumque IP obstructorum]] pro indice toto.',
'unblocklogentry'          => 'deobstruxit "$1"',
'block-log-flags-nocreate' => 'creatio rationum obstructa',
'block-log-flags-noemail'  => 'Litterae electronicae obstructae',
'ipb_expiry_invalid'       => 'Tempus exeundo invalidum fuit.',
'proxyblocksuccess'        => 'Factum.',

# Developer tools
'lockdb'              => 'Basem datorum obstruere',
'unlockdb'            => 'Basem datorum deobstruere',
'lockdbtext'          => 'Obstructio basis datorum potestatem omnium usorum suspendebit paginas recensendi et praeferentiarum earum et indicem paginarum custoditarum mutandi.
Adfirma te basem datorum obstruere velle, et te dein basem datorum deobstruendum.',
'lockconfirm'         => 'Ita, vere basem datorum obstruere volo.',
'unlockconfirm'       => 'Ita, vere basem datorum deobstruere volo.',
'lockbtn'             => 'Basem datorum obstruere',
'unlockbtn'           => 'Basem datorum deobstruere',
'locknoconfirm'       => 'Capsam non notavis.',
'lockdbsuccesssub'    => 'Basis datorum prospere obstructa est',
'unlockdbsuccesssub'  => 'Basis datorum prospere deobstructa est',
'lockdbsuccesstext'   => 'Basis datorum obstructa est.
<br />Memento eam dein [[Special:Unlockdb|deobstruere]].',
'unlockdbsuccesstext' => 'Basis datorum deobstructa est.',
'databasenotlocked'   => 'Basis datorum non obstructa est.',

# Move page
'movepage'                => 'Paginam movere',
'movepagetext'            => "Formam inferam utere ad paginam renominandum et ad historiam eius ad nomen novum movendum.
Index vetus paginam redirectionis ad indicem novum fiet.
Nexus ad paginam veterem non mutabuntur;
redirectiones duplices aut fractas quaerere et figere debebis.

Pagina '''non''' movebitur si pagina sub indice novo iam est, nisi est vacua aut pagina redirectionis et nullam historiam habet.

<b>MONITUM!</b>
Haec mutatio vehemens et improvisa potest esse pro pagina populare;
adfirma te consequentias intellegere antequam procedis.",
'movepagetalktext'        => "Pagina disputationis huius paginae, si est, etiam necessario motabitur '''nisi''':

*Disputatio sub paginae novae nomine contenta habet, aut
*Capsam subter non nota.

Ergo manu necesse disputationes motare vel contribuere erit, si vis.",
'movearticle'             => 'Paginam movere:',
'movenologin'             => 'Conventum non apertum',
'movenologintext'         => 'Rationem usoris habere et [[Special:Userlogin|conventum aperire]] debes ad movendum paginam.',
'newtitle'                => 'Ad indicem novum:',
'move-watch'              => 'Hanc paginam custodire',
'movepagebtn'             => 'Paginam movere',
'pagemovedsub'            => 'Pagina mota est',
'articleexists'           => "'''Non licet hanc paginam movere:''' pagina cum hoc nomine iam est, aut invalidum est nomen electum. 

Quaesumus, nomen alterum elege aut opem pete [[{{MediaWiki:Grouppage-sysop}}|magistratum]].",
'cantmove-titleprotected' => 'Tibi non licet paginam ad hunc titulum movere, quia hic titulus protectus est ne creetur',
'talkexists'              => "'''Pagina prospere mota est, sed pagina disputationis not moveri potuit quia iam est pagina disputationis sub titulo novo. Disputationes recensendo iunge.'''",
'movedto'                 => 'mota ad',
'movetalk'                => 'Movere etiam paginam disputationis',
'talkpagemoved'           => 'Pagina disputationis etiam mota est.',
'talkpagenotmoved'        => 'Pagina disputationis <strong>non</strong> mota est.',
'1movedto2'               => 'movit [[$1]] ad [[$2]]',
'1movedto2_redir'         => 'movit [[$1]] ad [[$2]] praeter redirectionem',
'movereason'              => 'Ratio:',
'revertmove'              => 'reverti',
'delete_and_move'         => 'Delere et movere',
'delete_and_move_text'    => '==Deletio necesse est==

Quaesitum "[[$1]]" etiam existit. Vin tu eam delere ut moveas?',
'delete_and_move_confirm' => 'Ita, paginam delere',
'delete_and_move_reason'  => 'Deleta ut moveatur',

# Export
'export'            => 'Paginas exportare',
'export-submit'     => 'Exportare',
'export-addcattext' => 'Addere paginas categoriae:',
'export-addcat'     => 'Addere',

# Namespace 8 related
'allmessages'               => 'Nuntia systematis',
'allmessagesname'           => 'Nomen',
'allmessagesdefault'        => 'Textus originalis',
'allmessagescurrent'        => 'Textus recens',
'allmessagestext'           => 'Hic est index omnium nuntiorum in MediaWiki.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' non adhibier potest, quia '''\$wgUseDatabaseMessages''' non iam agitur.",
'allmessagesfilter'         => 'Colum nominibus nuntiorum:',
'allmessagesmodified'       => 'Ea modificata sola monstrare',

# Special:Import
'import'                     => 'Paginas importare',
'import-interwiki-submit'    => 'Importare',
'import-interwiki-namespace' => 'Transferre paginas in spatium nominale:',
'importbadinterwiki'         => 'Nexus intervicus malus',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Pagina usoris mea',
'tooltip-pt-mytalk'               => 'Disputatum meum',
'tooltip-pt-preferences'          => 'Praeferentiae meae',
'tooltip-pt-watchlist'            => 'Paginae quae custodis ut eorum mutationes facilius vides',
'tooltip-pt-mycontris'            => 'Index conlationum mearum',
'tooltip-pt-login'                => 'Te conventum aperire hortamur, non autem requisitum',
'tooltip-pt-anonlogin'            => 'Te conventum aperire hortamur, non autem requisitum',
'tooltip-pt-logout'               => 'Conventum concludere',
'tooltip-ca-talk'                 => 'Disputatio de hac pagina',
'tooltip-ca-edit'                 => 'Hanc paginam recensere potes. Quaesumus praevisum inspice antequam servas.',
'tooltip-ca-addsection'           => 'Huic disputationi adnotare.',
'tooltip-ca-viewsource'           => 'Haec pagina protecta est. Fontem inspicere potes.',
'tooltip-ca-history'              => 'Emendationes huius paginae veteres.',
'tooltip-ca-protect'              => 'Protegere hanc paginam',
'tooltip-ca-delete'               => 'Delere hanc paginam',
'tooltip-ca-undelete'             => 'Restituere emendationes huic paginae conlatas antequam haec pagina deleta esset',
'tooltip-ca-move'                 => 'Movere hanc paginam',
'tooltip-ca-watch'                => 'Addere hanc paginam tuis paginis custoditis',
'tooltip-ca-unwatch'              => 'Removere hanc paginam ex tuis paginis custoditis',
'tooltip-search'                  => 'Quaerere aliquid in {{grammar:ablative|{{SITENAME}}}}',
'tooltip-p-logo'                  => 'Pagina prima',
'tooltip-n-mainpage'              => 'Ire ad paginam primam',
'tooltip-n-portal'                => 'De hoc incepto',
'tooltip-n-currentevents'         => 'Eventa novissima',
'tooltip-n-recentchanges'         => 'Index nuper mutatorum in hac vici',
'tooltip-n-randompage'            => 'Ire ad paginam fortuitam',
'tooltip-n-help'                  => 'Adiutatum de hoc vici',
'tooltip-n-sitesupport'           => 'Adiuvare hunc vici',
'tooltip-t-whatlinkshere'         => 'Index paginarum quae hic nectunt',
'tooltip-t-recentchangeslinked'   => 'Nuper mutata in paginis quibus haec pagina nectit',
'tooltip-feed-rss'                => 'RSS feed',
'tooltip-feed-atom'               => 'Atom feed',
'tooltip-t-contributions'         => 'Videre conlationes huius usoris',
'tooltip-t-emailuser'             => 'Mittere litteras electronicas huic usori',
'tooltip-t-upload'                => 'Fasciculos vel imagines onerare',
'tooltip-t-specialpages'          => 'Index paginarum specialium',
'tooltip-ca-nstab-main'           => 'Videre paginam',
'tooltip-ca-nstab-user'           => 'Videre paginam usoris',
'tooltip-ca-nstab-special'        => 'Haec est pagina specialis. Pagina ipsa recenseri non potest.',
'tooltip-ca-nstab-project'        => 'Videre paginam inceptorum',
'tooltip-ca-nstab-image'          => 'Videre paginam fasciculi',
'tooltip-ca-nstab-mediawiki'      => 'Videre nuntium systematis',
'tooltip-ca-nstab-template'       => 'Videre formulam',
'tooltip-ca-nstab-help'           => 'Videre paginam adiutatam',
'tooltip-ca-nstab-category'       => 'Videre paginam categoriae',
'tooltip-minoredit'               => 'Indicare hanc recensionem minorem',
'tooltip-save'                    => 'Servare mutationes tuas',
'tooltip-preview'                 => 'Praevidere mutationes tuas, quaesumus hoc utere antequam servas!',
'tooltip-diff'                    => 'Monstrare mutationes textui tuas',
'tooltip-compareselectedversions' => 'Videre dissimilitudinem inter ambas emendationes selectas huius paginae',
'tooltip-watch'                   => 'Addere hanc paginam tuis paginis custoditis',
'tooltip-recreate'                => 'Recreare hanc paginam etiamsi deleta est',

# Attribution
'anonymous'        => 'Usor ignotus {{grammar:genitive|{{SITENAME}}}}',
'siteuser'         => '{{SITENAME}} usor $1',
'lastmodifiedatby' => 'Ultima mutatio: $2, $1 ab $3.', # $1 date, $2 time, $3 user
'and'              => 'et',

# Spam protection
'subcategorycount'     => 'Huic categoriae {{PLURAL:$1|est una subcategoria|sunt $1 subcategoriae}}.',
'categoryarticlecount' => 'Huic categoriae {{PLURAL:$1|est una pagina|sunt $1 paginae}}.',
'category-media-count' => 'Huic categoriae {{PLURAL:$1|est unus fasciculus|sunt $1 fasciculi}}.',

# Info page
'numedits'     => 'Numerus recensionum (pagina): $1',
'numtalkedits' => 'Numerus recensionum (pagina disputationis): $1',

# Math options
'mw_math_png'    => 'Semper vertere PNG',
'mw_math_simple' => 'HTML si admodum simplex, alioqui PNG',
'mw_math_html'   => 'HTML si fieri potest, alioqui PNG',
'mw_math_source' => 'Stet ut TeX (pro navigatri texti)',
'mw_math_modern' => 'Commendatum pro navigatri recentes',
'mw_math_mathml' => 'MathML',

# Image deletion
'deletedrevision' => 'Delevit emendationem $1 veterem',

# Browsing diffs
'previousdiff' => '← Dissimilitudo superior',
'nextdiff'     => 'Dissimilitudo proxima →',

# Media information
'imagemaxsize' => 'Terminare imagines in paginis imaginum ad:',
'thumbsize'    => 'Magnitudo pollicisunguis:',

# Special:Newimages
'newimages' => 'Fasciculi novi',
'noimages'  => 'Nullum videndum.',

'exif-meteringmode-1' => 'Media',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Chiliometra per horam',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'omnes',
'imagelistall'     => 'omnes',
'watchlistall2'    => 'omnes',
'namespacesall'    => 'omnia',
'monthsall'        => 'omnes',

# E-mail address confirmation
'confirmemail'            => 'Inscriptionem electronicam adfirmare',
'confirmemail_noemail'    => 'Non est tibi inscriptio electronica valida in [[Special:Preferences|tuis praeferentiis]] posita.',
'confirmemail_text'       => '{{SITENAME}} te postulat inscriptionem tuam electronicam adfirmare priusquam proprietatibus litterarum electronicarum fruaris. Imprime botonem subter ut nuntium adfirmationis tibi mittatur. Nuntio nexus inerit quod est scribendus in tuo navigatro interretiali ut validum adfirmes tuam inscriptionem electronicam.',
'confirmemail_send'       => 'Mittere codicem adfirmationis',
'confirmemail_sent'       => 'Missae sunt litterae electronicae adfirmationis.',
'confirmemail_sendfailed' => 'Litteras electronicas adfirmationis non potuimus mittere. Inspice inscriptionem tuam electronicam ut errores invenias.

Nuntius reddidit: $1',
'confirmemail_invalid'    => 'Codex adfirmationis invalidus. Fortasse id exitum est.',
'confirmemail_needlogin'  => 'Necesse est tibi $1 ut inscriptionem tuam electronicam adfirmes.',
'confirmemail_success'    => 'Tua inscriptio electronica adfirmata est. Libenter utaris {{grammar:ablative|{{SITENAME}}}}.',
'confirmemail_loggedin'   => 'Inscriptio tua electronica iam adfirmata est.',
'confirmemail_error'      => 'Aliquid erravit quando adfirmationem tuam servabamus.',
'confirmemail_subject'    => '{{SITENAME}} - Adfirmatio inscriptionis electronicae',
'confirmemail_body'       => 'Aliquis (tu probabiliter, cum loco de IP $1) rationem "$2" creavit apud {{grammar:accusative|{{SITENAME}}}} sub hac inscriptione electronica.

Ut adfirmas te esse ipsum et proprietates inscriptionum electronicarum licere fieri apud {{grammar:accusative|{{SITENAME}}}}, hunc nexum aperi in tuo navigatro interretiali:

$3

Si *non* tu hoc fecisti, noli nexum sequi. Hic codex adfirmationis exibit $4.',

# Trackbacks
'trackbackremove' => ' ([$1 Delere])',

# Delete conflict
'deletedwhileediting' => 'Caveat censor: Haec pagina deleta est postquam inceperis eam recensere!',
'confirmrecreate'     => "Usor [[User:$1|$1]] ([[User talk:$1|disputatio]]) delevit hanc paginam postquam eam emendare inceperis cum ratione:
: ''$2''
Quaesumus, adfirma ut iterum hanc paginam crees.",
'recreate'            => 'Recreare',

# action=purge
'confirm_purge_button' => 'Licet',

# AJAX search
'articletitles' => "Paginae ab ''$1''",

# Multipage image navigation
'imgmultipageprev'   => '← pagina superior',
'imgmultipagenext'   => 'pagina proxima →',
'imgmultigo'         => 'I!',
'imgmultigotopre'    => 'Ire ad paginam',
'imgmultiparseerror' => 'Imago corrupta vel invalida videtur, ergo {{SITENAME}} indicem paginarum extrahere non potest.',

# Table pager
'table_pager_next'         => 'Pagina proxima',
'table_pager_prev'         => 'Pagina superior',
'table_pager_first'        => 'Prima pagina',
'table_pager_last'         => 'Ultima pagina',
'table_pager_limit_submit' => 'Ire',

# Auto-summaries
'autosumm-blank'   => 'paginam vacuavit',
'autosumm-replace' => "multa contenta ex pagina removit, contenta nova: '$1'",
'autoredircomment' => 'Redirigens ad [[$1]]',
'autosumm-new'     => 'Nova pagina: $1',

# Size units
'size-bytes'     => '$1 octeti',
'size-kilobytes' => '$1 chiliocteti',
'size-megabytes' => '$1 megaocteti',
'size-gigabytes' => '$1 gigaocteti',

# Live preview
'livepreview-loading' => 'Onerans…',
'livepreview-ready'   => 'Onerans… Factum!',

# Watchlist editor
'watchlistedit-raw-titles' => 'Tituli:',

# Watchlist editing tools
'watchlisttools-view' => 'Mutationes paginarum custoditarum monstrare',
'watchlisttools-edit' => 'Indicem paginarum custoditarum inspicere vel recensere',
'watchlisttools-raw'  => 'Indicem paginarum custoditarum quasi textum inspicere vel recensere',

);
