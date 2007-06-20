<?php
/** Albanian (Shqip)
 *
 * @addtogroup Language
 */

$skinNames = array(
	'standard' => 'Standarte',
	'nostalgia' => 'Nostalgjike',
	'cologneblue' => 'Kolonjë Blu'
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Speciale',
	NS_MAIN           => '',
	NS_TALK           => 'Diskutim',
	NS_USER           => 'Përdoruesi',
	NS_USER_TALK      => 'Përdoruesi_diskutim',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_diskutim',
	NS_IMAGE          => 'Figura',
	NS_IMAGE_TALK     => 'Figura_diskutim',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_diskutim',
	NS_TEMPLATE       => 'Stampa',
	NS_TEMPLATE_TALK  => 'Stampa_diskutim',
	NS_HELP           => 'Ndihmë',
	NS_HELP_TALK      => 'Ndihmë_diskutim'
);

# Compatbility with alt names
$namespaceAliases = array(
	'Perdoruesi' => NS_USER,
	'Perdoruesi_diskutim' => NS_USER_TALK,
);

$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y H:i',
);

$separatorTransformTable = array(',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Nënvizo lidhjet',
'tog-highlightbroken'         => 'Trego lidhjet e faqeve bosh <a href="" class="new">kështu </a> (ndryshe: kështu<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Rregullim i kryeradhës',
'tog-hideminor'               => 'Mos trego redaktimet e vogla',
'tog-extendwatchlist'         => 'Zgjero listën mbikqyrëse të tregojë të tëra ndryshimet përkatëse',
'tog-usenewrc'                => 'Tregoji me formatin e ri (jo për të gjithë shfletuesit)',
'tog-numberheadings'          => 'Numëro automatikish mbishkrimet',
'tog-showtoolbar'             => 'Trego butonat e redaktimit',
'tog-editondblclick'          => 'Redakto faqet me dopjo-shtypje (JavaScript)',
'tog-editsection'             => 'Lejo redaktimin e seksioneve me [redakto] lidhje',
'tog-editsectiononrightclick' => 'Lejo redaktimin e seksioneve me djathtas-shtypje<br /> mbi emrin e seksionit (JavaScript)',
'tog-showtoc'                 => 'Trego tabelën e përmbajtjeve<br />(për faqet me më shume se 3 tituj)',
'tog-rememberpassword'        => 'Mbaj mënd fjalëkalimin për vizitën e ardhshme',
'tog-editwidth'               => 'Kutija e redaktimit ka gjerësi te plotë',
'tog-watchcreations'          => 'Shto faqet që krijoj tek lista mbikqyrëse',
'tog-watchdefault'            => 'Shto faqet që redaktoj tek lista mbikqyrëse',
'tog-watchmoves'              => 'Shto faqet që zhvendos tek lista mbikqyrëse',
'tog-watchdeletion'           => 'Shto faqet që gris tek lista mbikqyrëse',
'tog-minordefault'            => 'Shëno të gjitha redaktimet si të vogla paraprakisht',
'tog-previewontop'            => 'Trego parapamjen përpara kutisë redaktuese, jo mbas saj',
'tog-previewonfirst'          => 'Trego parapamje në redaktim të parë',
'tog-nocache'                 => 'Mos ruaj kopje te faqeve',
'tog-enotifwatchlistpages'    => 'Më ço email kur ndryshojnë faqet',
'tog-enotifusertalkpages'     => 'Më ço email kur ndryshon faqja ime e diskutimit',
'tog-enotifminoredits'        => 'Më ço email kur ka redaktime të vogla të faqeve',
'tog-enotifrevealaddr'        => 'Trego adresën time në email-et njoftuese',
'tog-shownumberswatching'     => 'Trego numrin e përdoruesve mbikqyrës',
'tog-fancysig'                => 'Mos e përpuno nënshkrimin për formatim',
'tog-externaleditor'          => 'Përdor program të jashtëm për redaktime',
'tog-externaldiff'            => 'Përdor program të jashtëm për të treguar ndryshimet',
'tog-showjumplinks'           => 'Lejo lidhjet e afrueshmërisë "kapërce tek"',
'tog-uselivepreview'          => 'Trego parapamjen e menjëhershme (JavaScript) (Eksperimentale)',
'tog-forceeditsummary'        => 'Më pyet kur e le përmbledhjen e redaktimit bosh',
'tog-watchlisthideown'        => 'Fshih redaktimet e mia nga lista mbikqyrëse',
'tog-watchlisthidebots'       => 'Fshih redaktimet e robotëve nga lista mbikqyrëse',
'tog-watchlisthideminor'      => 'Fshih redaktimet e vogla nga lista mbikqyrëse',
'tog-ccmeonemails'            => 'Më dërgo kopje të mesazheve që u dërgoj të tjerëve',
'tog-diffonly'                => 'Mos trego përmbajtjen e faqes nën ndryshimin',

'underline-always'  => 'gjithmonë',
'underline-never'   => 'asnjëherë',
'underline-default' => 'sipas shfletuesit',

'skinpreview' => '(Parapamje)',

# Dates
'sunday'    => 'E diel',
'monday'    => 'E hënë',
'tuesday'   => 'E martë',
'wednesday' => 'E mërkurë',
'thursday'  => 'E enjte',
'friday'    => 'E premte',
'saturday'  => 'E shtunë',
'january'   => 'Janar',
'february'  => 'Shkurt',
'march'     => 'Mars',
'april'     => 'Prill',
'may_long'  => 'Maj',
'june'      => 'Qershor',
'july'      => 'Korrik',
'august'    => 'Gusht',
'september' => 'Shtator',
'october'   => 'Tetor',
'november'  => 'Nëntor',
'december'  => 'Dhjetor',
'jan'       => 'Jan',
'feb'       => 'Shk',
'mar'       => 'Mar',
'apr'       => 'Pri',
'may'       => 'Maj',
'jun'       => 'Qer',
'jul'       => 'Kor',
'aug'       => 'Gus',
'sep'       => 'Sht',
'oct'       => 'Tet',
'nov'       => 'Nën',
'dec'       => 'Dhj',

# Bits of text used by many pages
'categories'            => 'Kategori',
'pagecategories'        => '{{PLURAL:$1|Kategoria|Kategoritë}}',
'category_header'       => 'Artikuj në kategorinë "$1"',
'subcategories'         => 'Nën-kategori',
'category-media-header' => 'Skeda në kategori "$1"',

'mainpagetext'      => 'Wiki software u instalua me sukses.',
'mainpagedocfooter' => 'Ju lutem shikoni [http://meta.wikimedia.org/wiki/MediaWiki_i18n dukumentacionin për ndryshimin e pamjes] dhe [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide faqet rreth përdorimit] për të mësuar se si përdoret dhe ndryshohet MediaWiki.',

'about'          => 'Rreth',
'article'        => 'Artikulli',
'newwindow'      => '(hapet në një dritare të re)',
'cancel'         => 'Harroji',
'qbfind'         => 'Kërko',
'qbbrowse'       => 'Shfletoni',
'qbedit'         => 'Redaktoni',
'qbpageoptions'  => 'Opsionet e faqes',
'qbpageinfo'     => 'Informacion mbi faqen',
'qbmyoptions'    => 'Opsionet e mia',
'qbspecialpages' => 'Faqet speciale',
'moredotdotdot'  => 'Më shumë...',
'mypage'         => 'Faqja ime',
'mytalk'         => 'Diskutimet e mia',
'anontalk'       => 'Diskutimet për këtë IP',
'navigation'     => 'Shfleto',

# Metadata in edit box
'metadata_help' => 'Metadata (shikoni [[Project:Metadata]] për sqarimin):',

'errorpagetitle'    => 'Gabim',
'returnto'          => 'Kthehu tek $1.',
'tagline'           => 'Nga {{SITENAME}}, Enciklopedia e Lirë',
'help'              => 'Ndihmë',
'search'            => 'Kërko',
'searchbutton'      => 'Kërko',
'go'                => 'Shko',
'searcharticle'     => 'Shko',
'history'           => 'Historiku i faqes',
'history_short'     => 'Historiku',
'updatedmarker'     => 'ndryshuar nga vizita e fundit',
'info_short'        => 'Informacion',
'printableversion'  => 'Version shtypi',
'permalink'         => 'Lidhja e përhershme',
'print'             => 'Shtype',
'edit'              => 'Redaktoni',
'editthispage'      => 'Redaktoni faqen',
'delete'            => 'grise',
'deletethispage'    => 'Grise faqen',
'undelete_short'    => 'Restauroni $1 redaktime',
'protect'           => 'Mbroje',
'protectthispage'   => 'Mbroje faqen',
'unprotect'         => 'Liroje',
'unprotectthispage' => 'Liroje faqen',
'newpage'           => 'Faqe e re',
'talkpage'          => 'Diskutoni faqen',
'talkpagelinktext'  => 'Diskuto',
'specialpage'       => 'Faqe speciale',
'personaltools'     => 'Mjete vetjake',
'postcomment'       => 'Shtoni koment',
'articlepage'       => 'Shikoni artikullin',
'talk'              => 'Diskutimet',
'views'             => 'Shikime',
'toolbox'           => 'Mjete',
'userpage'          => 'Shikoni faqen',
'projectpage'       => 'Shikoni projekt-faqen',
'imagepage'         => 'Shikoni faqen e figurës',
'mediawikipage'     => 'Shikoni faqen e mesazhit',
'categorypage'      => 'Shiko faqen e kategorisë',
'viewtalkpage'      => 'Shikoni diskutimet',
'otherlanguages'    => 'Në gjuhë të tjera',
'redirectedfrom'    => '(Përcjellë nga $1)',
'redirectpagesub'   => 'Faqe përcjellëse',
'lastmodifiedat'    => 'Kjo faqe është ndryshuar për herë te fundit më $2, $1.', # $1 date, $2 time
'viewcount'         => 'Kjo faqe është parë $1 herë.',
'protectedpage'     => 'Faqe e mbrojtur',
'jumpto'            => 'Shko te:',
'jumptonavigation'  => 'navigacion',
'jumptosearch'      => 'kërko',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Rreth {{SITENAME}}-s',
'aboutpage'         => 'Project:Rreth',
'bugreports'        => 'Kontakt',
'bugreportspage'    => '{{SITENAME}}:Kontakt',
'copyright'         => 'Përmbajtja është në disponim nëpërmjet licencës $1.',
'copyrightpagename' => '{{SITENAME}} Të drejta autori',
'copyrightpage'     => 'Project:Të drejta autori',
'currentevents'     => 'Ngjarjet e tanishme',
'currentevents-url' => 'Ngjarjet e tanishme',
'disclaimers'       => 'Shfajësimet',
'disclaimerpage'    => 'Project:Shfajësimet e përgjithshme',
'edithelp'          => 'Ndihmë për redaktim',
'edithelppage'      => 'Help:Si redaktohet një faqe',
'faq'               => 'Pyetje e Përgjigje',
'faqpage'           => 'Project:Pyetje e Përgjigje',
'helppage'          => 'Help:Ndihmë',
'mainpage'          => 'Faqja Kryesore',
'portal'            => 'Wikiportal',
'portal-url'        => 'Project:Wikiportal',
'privacy'           => 'Rreth të dhënave vetjake',
'privacypage'       => 'Project:Politika vetjake',
'sitesupport'       => 'Dhurime',
'sitesupport-url'   => 'Project:Dhurime',

'badaccess' => 'Gabim leje',

'versionrequired'     => 'Nevojitet versioni $1 i MediaWiki-it',
'versionrequiredtext' => 'Nevojitet versioni $1 i MediaWiki-it për përdorimin e kësaj faqeje. Shikoni [[Special:Version|versionin]] tuaj.',

'ok'                  => 'Shkoni',
'pagetitle'           => '$1 - {{SITENAME}}',
'retrievedfrom'       => 'Marrë nga "$1"',
'youhavenewmessages'  => 'Keni $1 ($2).',
'newmessageslink'     => 'mesazhe të reja',
'newmessagesdifflink' => 'ndryshimi i fundit',
'editsection'         => 'redaktoni',
'editold'             => 'redaktoni',
'editsectionhint'     => 'Redaktoni seksionin: 
Edit section: $1',
'toc'                 => 'Tabela e përmbajtjeve',
'showtoc'             => 'trego',
'hidetoc'             => 'fshih',
'thisisdeleted'       => 'Shikoni ose restauroni $1?',
'viewdeleted'         => 'Do ta shikosh $1?',
'restorelink'         => '$1 redaktime të grisura',
'feedlinks'           => 'Ushqyes:',
'feed-invalid'        => 'Lloji i burimit të pajtimit është i pavlefshëm.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikulli',
'nstab-user'      => 'Përdoruesi',
'nstab-media'     => 'Media-faqe',
'nstab-special'   => 'Speciale',
'nstab-project'   => 'Projekt-faqe',
'nstab-image'     => 'Figura',
'nstab-mediawiki' => 'Porosia',
'nstab-template'  => 'Stampa',
'nstab-help'      => 'Ndihmë',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Nuk ekziston ky veprim',
'nosuchactiontext'  => 'Veprimi i caktuar nga URL nuk
njihet nga wiki software',
'nosuchspecialpage' => 'Nuk ekziston kjo faqe',
'nospecialpagetext' => 'Keni kërkuar një faqe speciale që nuk njihet nga wiki software.',

# General errors
'error'                => 'Gabim',
'databaseerror'        => 'Gabim regjistri',
'dberrortext'          => 'Ka ndodhur një gabim me pyetjen e regjistrit. Kjo mund të ndodhi n.q.s. pyetja nuk është e vlehshme (shikoni $5),
ose mund të jetë një yçkël e softuerit. Pyetja e fundit që i keni bërë regjistrit ishte:
<blockquote><tt>$1</tt></blockquote>
nga funksioni "<tt>$2</tt>".
MySQL kthehu gabimin "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ka ndodhur një gabim me formatin e pyetjes së regjistrit. Pyetja e fundit qe i keni bërë regjistrit ishte:
"$1"
nga funksioni "$2".
MySQL kthehu gabimin "$3: $4".',
'noconnect'            => 'Ju kërkojmë ndjesë! Difekt teknik, rifillojmë së shpejti.<br />
$1',
'nodb'                 => 'Nuk mund të zgjidhja regjistrin $1',
'cachederror'          => 'Kjo është një kopje e faqes së kërkuar dhe mund të jetë e vjetër.',
'laggedslavemode'      => 'Kujdes: Kjo faqe mund të mos jetë përtërirë nga shërbyesi kryesorë dhe mund të ketë informacion të vjetër',
'readonly'             => 'Regjistri i bllokuar',
'enterlockreason'      => 'Fusni një arsye për bllokimin, gjithashtu fusni edhe kohën se kur pritet të çbllokohet',
'readonlytext'         => 'Regjistri i {{SITENAME}}-s është i bllokuar dhe nuk lejon redaktime dhe
artikuj të rinj. Ka mundësi të jetë bllokuar për mirëmbajtje,
dhe do të kthehet në gjëndje normale mbas mirëmbajtjes.

Mirëmbajtësi i cili e bllokoi dha këtë arsye: $1',
'missingarticle'       => 'Regjistri nuk e gjeti tekstin e faqes që duhet të kishte gjetur, të quajtur "$1".

Kjo ndodh zakonisht kur ndjek një ndryshim ose lidhje historie tek një
faqe që është grisur.

Në qoftë se ky nuk është rasti, atëherë mund të keni gjetur një yçkël në softuerin.
Tregojani këtë përmbledhje një administruesi, duke shënuar edhe URL-in.',
'readonly_lag'         => "Regjistri është bllokuar automatikisht për t'i dhënë kohë shërbyesve skllevër për të arritur kryesorin. Ju lutemi provojeni përsëri më vonë.",
'internalerror'        => 'Gabim i brendshëm',
'filecopyerror'        => 'Nuk munda të kopjojë skedën "$1" tek "$2".',
'filerenameerror'      => 'Nuk munda të ndërrojë emrin e skedës "$1" në "$2".',
'filedeleteerror'      => 'Nuk munda të gris skedën "$1".',
'filenotfound'         => 'Nuk munda të gjejë skedën "$1".',
'unexpected'           => 'Vlerë e papritur: "$1"="$2".',
'formerror'            => 'Gabim: nuk munda të dërgoj formularin',
'badarticleerror'      => 'Ky veprim nuk mund të bëhet në këtë faqe.',
'cannotdelete'         => 'Nuk munda të gris këtë faqe ose figurë të dhënë. (Ka mundësi të jetë grisur nga dikush tjeter.)',
'badtitle'             => 'Titull i pasaktë',
'badtitletext'         => 'Titulli i faqes që kërkuat nuk ishte i saktë, ishte bosh, ose ishte një lidhje gabim me një titull wiki internacional.',
'perfdisabled'         => 'Ju kërkoj ndjesë! Ky veprim është bllokuar përkohsisht sepse e ngadalëson regjistrin aq shumë sa nuk e përdor dot njeri tjetër.',
'perfcached'           => 'Informacioni i mëposhtëm është kopje e ruajtur dhe mund të mos jetë i freskët:',
'perfcachedts'         => 'Informacioni i mëposhtëm është një kopje e rifreskuar më $1.',
'wrong_wfQuery_params' => 'Parametra gabim tek wfQuery()<br />
Funksioni: $1<br />
Pyetja: $2',
'viewsource'           => 'Shikoni tekstin',
'viewsourcefor'        => 'e $1',
'viewsourcetext'       => 'Ju mund të shikoni dhe kopjoni tekstin burimor të kësaj faqe:',
'protectedinterface'   => 'Kjo faqe përmban tekst për pamjen gjuhësorë të softuerit dhe është e mbrojtur për të penguar keqpërdorimet.',
'editinginterface'     => "'''Kujdes:''' Po redaktoni një faqe që përdoret për tekstin ose pamjen e softuerit. Ndryshimet e kësaj faqeje do të prekin tekstin ose pamjen për të gjithë përdoruesit e tjerë.",
'sqlhidden'            => '(Pyetje SQL e fshehur)',

# Login and logout pages
'logouttitle'                => 'Përdoruesi doli',
'logouttext'                 => 'Keni dalë jashtë {{SITENAME}}-s. Mund të vazhdoni të përdorni {{SITENAME}}-n anonimisht, ose mund të hyni brënda përsëri.',
'welcomecreation'            => '<h2>Mirësevini, $1!</h2><p>Llogaria juaj është hapur. Mos harroni të ndryshoni parapëlqimet e {{SITENAME}}-s.',
'loginpagetitle'             => 'Hyrje përdoruesi',
'yourname'                   => 'Fusni nofkën tuaj',
'yourpassword'               => 'Fusni fjalëkalimin tuaj',
'yourpasswordagain'          => 'Fusni fjalëkalimin përsëri',
'remembermypassword'         => 'Mbaj mënd fjalëkalimin tim për tërë vizitat e ardhshme.',
'yourdomainname'             => 'Faqja juaj',
'externaldberror'            => 'Ose kishte një gabim tek regjistri i identifikimit të jashtëm, ose nuk ju lejohet të përtërini llogarinë tuaje të jashtme.',
'loginproblem'               => '<b>Kishte një problem me hyrjen tuaj.</b><br />Provojeni përsëri!',
'alreadyloggedin'            => '<font color=red><b>Përdorues $1, keni hyrë brënda më parë!</b></font><br />',
'login'                      => 'Hyni',
'loginprompt'                => 'Duhet të pranoni "biskota" për të hyrë brënda në {{SITENAME}}.',
'userlogin'                  => 'Hyni ose hapni një llogari',
'logout'                     => 'Dalje',
'userlogout'                 => 'Dalje',
'notloggedin'                => 'Nuk keni hyrë brenda',
'nologin'                    => 'Nuk keni një llogari? $1.',
'nologinlink'                => 'Hapeni',
'createaccount'              => 'Hap një llogari',
'gotaccount'                 => 'Keni një llogari? $1.',
'gotaccountlink'             => 'Hyni',
'createaccountmail'          => 'me email',
'badretype'                  => 'Fjalëkalimet nuk janë njësoj.',
'userexists'                 => 'Nofka që përdorët është në përdorim. Zgjidhni një nofkë tjetër.',
'youremail'                  => 'Adresa e email-it*',
'username'                   => 'Nofka e përdoruesit:',
'uid'                        => 'Nr. i identifikimit:',
'yourrealname'               => 'Emri juaj i vërtetë*',
'yourlanguage'               => 'Ndërfaqja gjuhësore',
'yourvariant'                => 'Varianti',
'yournick'                   => 'Nënshkrimi',
'badsig'                     => 'Sintaksa e signaturës është e pavlefshme, kontrolloni HTML-in.',
'email'                      => 'Email',
'prefs-help-realname'        => '* Emri i vërtetë (opsion): Për të përmendur emrin tuaj si kontribuues në varësi nga puna juaj këtu.',
'loginerror'                 => 'Gabim hyrje',
'prefs-help-email'           => '* Email (me dëshirë): mund të përdoret për tu kontaktuar nga përdorues të tjerë pa u treguar adresën, për ndërrimin e fjalëkalimit të llogarisë nëse e harroni, apo mjete të tjera.',
'nocookiesnew'               => 'Llogaria e përdoruesit u hap, por nuk keni hyrë brenda akoma. {{SITENAME}} përdor "biskota" për të futur brenda përdoruesit. Prandaj, duhet të pranoni biskota dhe të provoni përsëri me nofkën dhe fjalëkalimin tuaj.',
'nocookieslogin'             => '{{SITENAME}} përdor "biskota" për të futur brenda përdoruesit. Prandaj, duhet të pranoni "biskota" dhe të provoni përsëri.',
'noname'                     => 'Nuk keni dhënë një emër të saktë.',
'loginsuccesstitle'          => 'Hyrje me sukses',
'loginsuccess'               => 'Keni hyrë brënda në {{SITENAME}} si "$1".',
'nosuchuser'                 => 'Nuk ka ndonjë përdorues me emrin "$1". Kontrolloni gërmat, ose përdorni formularin e mëposhtëm për të hapur një llogari të re.',
'nosuchusershort'            => 'Nuk ka asnjë përdorues me emrin "$1".',
'nouserspecified'            => 'Ju duhet të jepni një nofkë',
'wrongpassword'              => 'Fjalëkalimi që futët nuk është i saktë. Provoni përsëri!',
'wrongpasswordempty'         => 'Fjalëkalimi juaj ishte bosh. Ju lutemi provoni përsëri.',
'mailmypassword'             => 'Më dërgo një fjalëkalim të ri tek adresa ime',
'passwordremindertitle'      => 'Kërkesë për fjalëkalim të ri tek {{SITENAME}}',
'passwordremindertext'       => 'Dikush (ndoshta ju, nga IP adresa $1) kërkojë një fjalëkalim të ri për hyrje tek {{SITENAME}}. Mund të përdoret fjalëkalimi "$3" për llogarinë e përdoruesit "$2" në qoftë se dëshironi. Nëse përdorni këtë fjalëkalim këshillohet ta ndërroni fjalëkalimin tuaj sapo të hyni.

Në qoftë se nuk e përdorni këtë fjalëkalim të ri, atëherë do të vazhdojë të përdoret ai i vjetri. Nuk ka nevojë ta ndryshoni fjalëkalimin në qoftë se nuk ishit ju që kërkuat fjalëkalim të ri.',
'noemail'                    => 'Regjistri nuk ka adresë për përdoruesin "$1".',
'passwordsent'               => 'Një fjalëkalim i ri është dërguar tek adresa e regjistruar për "$1". Provojeni përsëri hyrjen mbasi ta keni marrë fjalëkalimin.',
'eauthentsent'               => 'Një eMail konfirmues u dërgua te adresa e dhënë.
Para se të pranohen eMail nga përdoruesit e tjerë, duhet që adressa e juaj të vërtetohet. 
Ju lutemi ndiqni këshillat në eMailin e pranuar.',
'mailerror'                  => 'Gabim duke dërguar postën: $1',
'acct_creation_throttle_hit' => 'Më vjen keq, por brenda 24 orëve të fundit është hapur një llogari $1 me IP-adresën tuaj dhe në moment nuk mundeni të hapni më. Provoni 24 orë më vonë prap.',
'emailauthenticated'         => 'Adresa juaj është vërtetuar më $1.',
'emailnotauthenticated'      => 'Adresa juaj <strong>nuk është vërtetuar</strong> akoma prandaj nuk mund të merrni e-mail.',
'noemailprefs'               => '<strong>Detyrohet një adresë email-i për të përdorur këtë mjet.</strong>',
'emailconfirmlink'           => 'Vërtetoni adresën tuaj',
'invalidemailaddress'        => 'Adresa email e dhënë nuk mund të pranohet sepse nuk duket e rregullt. Ju lutem fusni një adresë të rregullt ose boshatisni kutinë e shtypit.',
'accountcreated'             => 'Llogarija e Përdoruesit u krijua',
'accountcreatedtext'         => 'Llogarija e Përdoruesit për $1 u krijua',

# Edit page toolbar
'bold_sample'     => 'Tekst i trashë',
'bold_tip'        => 'Tekst i trashë',
'italic_sample'   => 'Tekst i pjerrët',
'italic_tip'      => 'Tekst i pjerrët',
'link_sample'     => 'Titulli i lidhjes',
'link_tip'        => 'Lidhje e brëndshme',
'extlink_sample'  => 'http://www.shembull.al Titulli i lidhjes',
'extlink_tip'     => 'Lidhje e jashtme (most harro prefiksin http://)',
'headline_sample' => 'Titull shembull',
'headline_tip'    => 'Titull i nivelit 2',
'math_sample'     => 'Vendos formulen ketu',
'math_tip'        => 'Formulë matematike (LaTeX)',
'nowiki_sample'   => 'Vendos tekst që nuk duhet të formatohet',
'nowiki_tip'      => 'Mos përdor format wiki',
'image_sample'    => 'Shembull.jpg',
'image_tip'       => 'Vendos një figurë',
'media_sample'    => 'Shembull.ogg',
'media_tip'       => 'Lidhje media-skedash',
'sig_tip'         => 'Firma juaj me gjithë kohë',
'hr_tip'          => 'vijë horizontale (përdoreni rallë)',

# Edit pages
'summary'                  => 'Përmbledhje',
'subject'                  => 'Subjekt/Titull',
'minoredit'                => 'Ky është një redaktim i vogël',
'watchthis'                => 'Mbikqyre këtë faqe',
'savearticle'              => 'Kryej ndryshimet',
'preview'                  => 'Parapamje',
'showpreview'              => 'Trego parapamjen',
'showlivepreview'          => 'Parapamje e menjëhershme',
'showdiff'                 => 'Trego ndryshimet',
'anoneditwarning'          => "Ju nuk jeni regjistruar. IP adresa juaj do të regjistrohet në historinë e redaktimeve të kësaj faqe.
You are not logged in. Your IP address will be recorded in this page's edit history.",
'missingsummary'           => "'''Vërejtje:'''  Ju nuk keni shtuar një përmbledhje për redaktimet.",
'missingcommenttext'       => 'Ju lutemi shtoni një koment në vazhdim.',
'summary-preview'          => 'Parapamja e përmbledhjes',
'blockedtitle'             => 'Përdoruesi është bllokuar',
'blockedtext'              => 'Emri juaj ose adresa e IP-së është bllokuar nga $1. Arsyeja e dhënë është kjo:<br />\'\'$2\'\'<br />Mund të kontaktoni $1 ose një nga [[Project:Administruesit|administruesit]] e tjerë për të diskutuar bllokimin.

Vini re se nuk mund të përdorni "dërgoji email këtij përdoruesi" n.q.s. nuk keni një adresë të saktë të rregjistruar në [[Special:Preferences|parapëlqimet e përdoruesit]].

Adresa e IP-së që keni është $3. Jepni këtë adresë në çdo ankesë.',
'whitelistedittitle'       => 'Duhet të hyni brënda për të redaktuar',
'whitelistedittext'        => 'Duhet të $1 për të redaktuar artikuj.',
'whitelistreadtitle'       => 'Duhet të hyni brënda për të lexuar',
'whitelistreadtext'        => 'Duhet të [[Special:Userlogin|hyni brënda]] për të lexuar artikuj.',
'whitelistacctitle'        => 'Nuk ju lejohet të hapni një llogari',
'whitelistacctext'         => 'Duhet të [[Special:Userlogin|hyni brënda]] dhe të keni të drejta të posaçme pasi tu lejohet të hapni llogari në Wiki.',
'confirmedittitle'         => 'Nevojitet adresë email-i e vërtetuar për të redaktuar',
'confirmedittext'          => 'Ju duhet së pari ta vërtetoni e-mail adresen para se të redaktoni. Ju lutem plotësoni dhe vërtetoni e-mailin tuaj  te [[Special:Preferences|parapëlqimet]] e juaja.',
'loginreqtitle'            => 'Detyrohet hyrja',
'loginreqlink'             => 'hyni',
'loginreqpagetext'         => 'Ju duhet $1 për të parë faqe e tjera.',
'accmailtitle'             => 'Fjalëkalimi u dërgua.',
'accmailtext'              => "Fjalëkalimi për '$1' u dërgua tek $2.",
'newarticle'               => '(I Ri)',
'newarticletext'           => "{{SITENAME}} nuk ka akoma një ''{{NAMESPACE}} faqe'' të quajtur '''{{PAGENAME}}'''. Shtypni '''redaktoni''' më sipër ose [[Special:Search/{{PAGENAME}}|bëni një kërkim për {{PAGENAME}}]]",
'anontalkpagetext'         => "---- ''Kjo është një faqe diskutimi për një përdorues anonim i cili nuk ka hapur akoma një llogari ose nuk e përdor atë. Prandaj, më duhet të përdor numrin e adresës [[IP adresë|IP]] për ta identifikuar. Kjo adresë mund të përdoret nga disa njerëz. Në qoftë se jeni një përdorues anonim dhe mendoni se komente kot janë drejtuar ndaj jush, ju lutem [[Special:Userlogin|krijoni një llogari ose hyni brënda]] për të mos u ngatarruar me përdorues të tjerë anonim.''",
'noarticletext'            => 'Tani për tani nuk ka tekst në këtë faqe, mund ta [[Special:Search/{{PAGENAME}}|kërkoni]] këtë titull në faqe të tjera ose mund ta [{{fullurl:{{FULLPAGENAME}}|action=edit}} filloni] atë.',
'clearyourcache'           => "'''Shënim:''' Pasi të ruani parapëlqimet ose pasi të kryeni ndryshimet, duhet të pastroni ''cache''-në e shfletuesit tuaj për të parë ndryshimet: për '''Mozilla/Safari/Konqueror''' shtypni ''Ctrl+Shift+Reload'' (ose ''ctrl+shift+r''), për '''IE''' ''Ctrl+f5'', '''Opera''': ''F5''.",
'usercssjsyoucanpreview'   => "<strong>Këshillë:</strong> Përdorni butonin 'Trego parapamjen' për të provuar ndryshimet tuaja të faqeve css/js përpara se të kryeni ndryshimet.",
'usercsspreview'           => "'''Vini re se kjo është vetëm një provë ose parapamje e faqes tuaj CSS, ajo nuk është ruajtur akoma!'''",
'userjspreview'            => "'''Vini re se kjo është vetëm një provë ose parapamje e faqes tuaj JavaScript, ajo nuk është ruajtur akoma!'''",
'userinvalidcssjstitle'    => "'''Kujdes:''' Nuk ka pamje të quajtur \"\$1\". Vini re se faqet .css dhe .js përdorin titull me gërma të vogla, p.sh. Përdoruesi:Foo/monobook.css, jo Përdoruesi:Foo/Monobook.css.",
'updated'                  => '(E ndryshuar)',
'note'                     => '<strong>Shënim:</strong>',
'previewnote'              => 'Kini kujdes se kjo është vetëm një parapamje, nuk është ruajtur akoma!',
'previewconflict'          => 'Kjo parapamje reflekton tekstin sipër kutisë së redaktimit siç do të duket kur të kryeni ndryshimin.',
'session_fail_preview'     => '<strong>Ju kërkoj ndjesë. Nuk munda të kryej redaktimin tuaj sepse humba disa të dhëna. Provojeni përsëri dhe nëse nuk punon provoni të dilni dhe të hyni përsëri.</strong>',
'importing'                => 'Duke importuar $1',
'editing'                  => 'Duke redaktuar $1',
'editinguser'              => 'Duke redaktuar $1',
'editingsection'           => 'Duke redaktuar $1 (seksion)',
'editingcomment'           => 'Duke redaktuar $1 (koment)',
'editconflict'             => 'Konflikt redaktimi: $1',
'explainconflict'          => 'Dikush tjetër ka ndryshuar këtë faqe kur ju po e redaktonit. Kutiza e redaktimit mësipërme tregon tekstin e faqes siç ekziston tani. Ndryshimet juaja janë treguar poshtë kutisë së redaktimit. Ju duhet të përputhni ndryshimet tuaja me tekstin ekzistues. <b>Vetëm</b> teksti në kutinë e sipërme të redaktimit do të ruhet kur të shtypni "Ruaje faqen".',
'yourtext'                 => 'Teksti juaj',
'storedversion'            => 'Versioni i ruajtur',
'nonunicodebrowser'        => '<strong>KUJDES: Shfletuesi juaj nuk përdor dot unikode, ju lutem ndryshoni shfletues para se të redaktoni artikuj.</strong>',
'editingold'               => '<strong>KUJDES: Po redaktoni një version të vjetër të kësaj faqeje. Në qoftë se e ruani, çdo ndryshim i bërë deri tani do të humbet.</strong>',
'yourdiff'                 => 'Ndryshimet',
'copyrightwarning'         => "Kontributet tek {{SITENAME}} janë të konsideruara të dhëna nën licensën $2 (shikoni $1 për hollësirat).<br />
'''NDALOHET DHËNIA E PUNIMEVE PA PASUR LEJE NGA AUTORI NË MOSPËRPUTHJE ME KËTË LICENSË!'''<br />",
'copyrightwarning2'        => "Ju lutem vini re se të gjitha kontributet tek {{SITENAME}} mund të redaktohen, ndryshohen apo fshihen nga përdorues të tjerë. Në qoftë se nuk dëshironi që shkrimet tuaja të redaktohen pa mëshirë mos i jepni këtu.<br />
Po na premtoni që ç'ka po jepni këtu e keni kontributin tuaj ose e keni kopjuar nga domeni publik apo nga burime të tjera të lira sipas ligjeve përkatëse (shikoni $1 për hollësirat). 
<strong>NDALOHET DHËNIA E PUNIMEVE PA PASUR LEJE NGA AUTORI NË MOSPËRPUTHJE ME KËTË LICENSË!</strong>",
'longpagewarning'          => 'KUJDES: Kjo faqe është $1 kilobytes e gjatë; disa
shfletues mund të kenë probleme për të redaktuar faqe që afrohen ose janë akoma më shumë se 32kb.
Konsideroni ta ndani faqen në disa seksione më të vogla.',
'longpageerror'            => '<strong>GABIM: Tesksti që ju po e redaktoni është $1 kilobytes i gjatë dhe është më i gjatë se maksimumi i lejuar prej $1 kilobytes. Ndryshimet nuk mund të ruhen.</strong>',
'readonlywarning'          => 'KUJDES: Regjistri është bllokuar për mirëmbajtje,
kështuqë nuk do keni mundësi të ruani redaktimet e tuaja tani. Mund të kopjoni dhe ruani tekstin në një skedë për më vonë.',
'protectedpagewarning'     => 'KUJDES: Kjo faqe është bllokuar kështu që vetëm përdorues me titullin administrator mund ta redaktojnë. Ju lutem ndiqni rregullat e dhëna tek [[{{SITENAME}}:Faqe e mbrojtur|faqet e mbrojtura]].',
'semiprotectedpagewarning' => "'''Shënim:''' Redaktimi i kësaj faqeje mund të bëhet vetëm nga përdorues të regjistruar.",
'templatesused'            => 'Stampa të përdorura në këtë faqe:',
'templatesusedpreview'     => 'Stampa të përdorur në ketë parapamje:',
'template-protected'       => '(mbrojtur)',
'template-semiprotected'   => '(gjysëm-mbrojtur)',
'nocreatetitle'            => 'Krijimi i faqeve të reja është i kufizuar.',
'nocreatetext'             => 'Mundësia për të krijuar faqe të reja është kufizuar. Duhet të [[Special:Userlogin|hyni ose të hapni një llogari]] për të krijuar faqe të reja, ose mund të ktheheni mbrapsh dhe të redaktoni një faqe ekzistuese.',

# "Undo" feature
'undo-success' => 'Redaktimi nuk mund të kthehej. Ju lutem kontrolloni ndryshimet e mëposhtëme për të vërtetuar dëshirën e veprimit dhe pastaj kryeni ndryshimet për të plotësuar kthimin e redaktimit.',
'undo-failure' => 'Redaktimi nuk mund të kthehej për shkak të përplasjeve të ndërmjetshme.',
'undo-summary' => 'U kthye versioni $1 i bërë nga [[Special:Contributions/$2]] ([[User talk:$2]])',

# History pages
'revhistory'          => 'Historia e redaktimeve',
'viewpagelogs'        => 'Shiko regjistrat për këtë faqe',
'nohistory'           => 'Nuk ka histori redaktimesh për këtë faqe.',
'revnotfound'         => 'Versioni nuk u gjet',
'revnotfoundtext'     => 'Versioni i vjetër i faqes së kërkuar nuk mund të gjehej.Ju lutem kontrolloni URL-in që përdorët për të ardhur tek kjo faqe.',
'loadhist'            => 'Duke ngarkuar historinë e faqes',
'currentrev'          => 'Versioni i tanishëm',
'revisionasof'        => 'Versioni i $1',
'revision-info'       => 'Versioni më $1 nga $2',
'previousrevision'    => '← Version më i vjetër',
'nextrevision'        => 'Version më i ri →',
'currentrevisionlink' => 'shikoni versionin e tanishëm',
'cur'                 => 'tani',
'next'                => 'mbas',
'last'                => 'fund',
'orig'                => 'Origjinal',
'page_first'          => 'Së pari',
'page_last'           => 'Së fundmi',
'histlegend'          => 'Legjenda: (tani) = ndryshimet me versionin e tanishëm,
(fund) = ndryshimet me versionin e parardhshëm, V = redaktim i vogël',
'deletedrev'          => '[u gris]',
'histfirst'           => 'Së pari',
'histlast'            => 'Së fundmi',
'historyempty'        => '(bosh)',

# Revision feed
'history-feed-title'          => 'Historiku i versioneve',
'history-feed-description'    => 'Historiku i versioneve për këtë faqe në wiki',
'history-feed-item-nocomment' => '$1 tek $2', # user at time
'history-feed-empty'          => 'Faqja që kërkuat nuk ekziston. Ajo mund të jetë grisur nga wiki ose mund të jetë zhvendosur nën një emër tjetër. Mund të provoni ta gjeni duke e [[Special:Search|kërkuar]].',

# Revision deletion
'rev-deleted-comment'         => '(kometi u largua)',
'rev-deleted-user'            => '(përdoruesi u largua)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Ky version i faqes është shlyer nga arkivi publik i faqes.
Shiko tek [{{fullurl:Speciale:Log/delete|page={{PAGENAMEE}}}} regjistri i grisjeve], ndoshta gjenden atje më shumë informacione rreth kësaj.
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Ky version i faqes është shlyer nga arkivi publik i faqes. Ju si Administrator mund ta shikoni akoma këtë. 
Shiko tek [{{fullurl:Speciale:Log/delete|page={{PAGENAMEE}}}} regjistri i grisjeve], ndoshta gjenden atje më shumë informacione rreth kësaj.
</div>',
'rev-delundel'                => 'trego/fshih',
'revisiondelete'              => 'Shlyj/Reparo versionet',
'revdelete-selected'          => 'Versionet e zgjedhura për [[:$1]]:',
'revdelete-text'              => 'Përmbajtja dhe pjesët e tjera nuk janë të dukshme për të gjithë, por figurojnë në historikun e versioneve. Administratorët munden përmbajtjen e larguar ta shikojnë dhe restaurojnë, përveç në rastet kur një gjë e tillë është ndaluar ekstra.',
'revdelete-legend'            => 'Vendosni kufizimet për versionin:',
'revdelete-hide-text'         => 'Fshihe tekstin e versionit',
'revdelete-hide-comment'      => 'fshih komentin e redaktimit',
'revdelete-hide-user'         => 'Fshihe emrin/IP-në të redaktuesit',
'revdelete-hide-restricted'   => 'Këto përkufizme vlejnë edhe për Admintratorët (jo vetëm për përdoruesit "normal")',
'revdelete-log'               => 'Komenti/arsyetimi:',
'revdelete-submit'            => 'Apliko te versionet e zgjedhura',
'revdelete-logentry'          => 'Pamja e versionit u ndryshua për [[$1]]',

# Diffs
'difference'                => '(Ndryshime midis versioneve)',
'loadingrev'                => 'duke ngarkuar versionin për ndryshimin',
'lineno'                    => 'Rreshti $1:',
'editcurrent'               => 'Redaktoni versionin e tanishëm të kësaj faqeje',
'selectnewerversionfordiff' => 'Zgjidhni një version më të ri për krahasim',
'selectolderversionfordiff' => 'Zgjidhni një version më të vjetër për krahasim',
'compareselectedversions'   => 'Krahasoni versionet e zgjedhura',
'editundo'                  => 'ktheje',
'diff-multi'                => '({{plural:$1|Një redaktim ndërmjet nuk është|$1 redaktime ndërmjet nuk janë}} treguar.)',

# Search results
'searchresults'         => 'Rezultatet e kërkimit',
'searchsubtitle'        => 'Kërkim për "[[$1]]"',
'searchsubtitleinvalid' => 'Kërkim për "$1"',
'badquery'              => 'Pyetje kërkese e formuluar gabim',
'badquerytext'          => 'Nuk munda t\'i përgjigjem pyetjes tuaj. Kjo ka mundësi të ketë ndodhur ngaqë provuat të kërkoni për një fjalë me më pak se tre gërma, gjë që s\'mund të behet akoma. Ka mundësi që edhe të keni shtypur keq pyetjen, për shembull "peshku dhe dhe halat". Provoni një pyetje tjetër.',
'matchtotals'           => 'Pyetja "$1" u përpuq $2 tituj faqesh
dhe teksti i $3 artikujve te pasardhshëm.',
'noexactmatch'          => '<span style="font-size: 135%; font-weight: bold; margin-left: .6em">Faqja me atë titull nuk është krijuar akoma</span>

<span style="display: block; margin: 1.5em 2em">
Mund të [[$1|filloni një artikull]] me këtë titull.

<span style="display:block; font-size: 89%; margin-left:.2em">Ju lutem kërkoni {{SITENAME}}-n përpara se të krijoni një artikull të ri se mund të jetë nën një titull tjetër.</span>
</span>',
'titlematches'          => 'Tituj faqesh që përputhen',
'notitlematches'        => 'Nuk ka asnjë titull faqeje që përputhet',
'textmatches'           => 'Tekst faqesh që përputhet',
'notextmatches'         => 'Nuk ka asnjë tekst faqeje që përputhet',
'prevn'                 => '$1 më para',
'nextn'                 => '$1 më pas',
'viewprevnext'          => 'Shikoni ($1) ($2) ($3).',
'showingresults'        => 'Tregohen më poshtë <b>$1</b> rezultate duke filluar me #<b>$2</b>.',
'showingresultsnum'     => 'Tregohen më poshtë <b>$3</b> rezultate duke filluar me #<b>$2</b>.',
'nonefound'             => '<strong>Shënim</strong>: Kërkimet pa rezultate ndodhin kur kërkoni për fjalë që rastisen shpesh si "ke" dhe "nga", të cilat nuk janë të futura në regjistër, ose duke dhënë më shumë se një fjalë (vetëm faqet që i kanë të gjitha ato fjalë do të tregohen si rezultate).',
'powersearch'           => 'Kërko',
'powersearchtext'       => 'Kërko në hapësirën:<br>
$1<br />
$2 Lidhje përcjellëse &nbsp; Kërko për $3 $9',
'searchdisabled'        => '<p>Kërkimi me tekst të plotë është bllokuar tani për tani ngaqë shërbyesi është shumë i ngarkuar; shpresojmë ta nxjerrim prapë në gjendje normale pas disa punimeve. Deri atëherë mund të përdorni Google-in për kërkime:</p>',
'blanknamespace'        => '(Artikujt)',

# Preferences page
'preferences'              => 'Parapëlqimet',
'mypreferences'            => 'Parapëlqimet',
'prefsnologin'             => 'Nuk keni hyrë brenda',
'prefsnologintext'         => 'Duhet të keni [[Special:Userlogin|hyrë brenda]] për të ndryshuar parapëlqimet e përdoruesit.',
'prefsreset'               => 'Parapëlqimet janë rikthyer siç ishin.',
'qbsettings'               => 'Vendime të shpejta',
'qbsettings-none'          => 'Asnjë',
'qbsettings-fixedleft'     => 'Lidhur majtas',
'qbsettings-fixedright'    => 'Lidhur djathtas',
'qbsettings-floatingleft'  => 'Pezull majtas',
'qbsettings-floatingright' => 'Pezull djathtas',
'changepassword'           => 'Ndërroni fjalëkalimin',
'skin'                     => 'Pamja',
'math'                     => 'Formula',
'dateformat'               => 'Data',
'datedefault'              => 'Standard',
'datetime'                 => 'Data dhe Ora',
'math_failure'             => 'Nuk e kuptoj',
'math_unknown_error'       => 'gabim i panjohur',
'math_unknown_function'    => 'funksion i panjohur',
'math_lexing_error'        => 'gabim leximi',
'math_syntax_error'        => 'gabim sintakse',
'math_image_error'         => 'Konversioni PNG dështoi; kontrolloni për ndonjë gabim instalimi të latex-it, dvips-it, gs-it, dhe convert-it.',
'math_bad_tmpdir'          => 'Nuk munda të shkruaj ose krijoj dosjen e përkohshme për matematikë',
'math_bad_output'          => 'Nuk munda të shkruaj ose të krijoj prodhimin matematik në dosjen',
'math_notexvc'             => 'Mungon zbatuesi texvc; ju lutem shikoni math/README për konfigurimin.',
'prefs-personal'           => 'Përdoruesi',
'prefs-rc'                 => 'Ndryshime së fundmi',
'prefs-watchlist'          => 'Lista mbikqyrëse',
'prefs-watchlist-days'     => 'Numri i ditëve të treguara tek lista mbikqyrëse:',
'prefs-watchlist-edits'    => 'Numri i redaktimeve të treguara tek lista mbikqyrëse e zgjeruar:',
'prefs-misc'               => 'Të ndryshme',
'saveprefs'                => 'Ruaj parapëlqimet',
'resetprefs'               => 'Rikthe parapëlqimet',
'oldpassword'              => 'I vjetri',
'newpassword'              => 'I riu',
'retypenew'                => 'I riu përsëri',
'textboxsize'              => 'Redaktimi',
'rows'                     => 'Rreshta',
'columns'                  => 'Kolona',
'searchresultshead'        => 'Kërkimi',
'resultsperpage'           => 'Sa përputhje të tregohen për faqe',
'contextlines'             => 'Sa rreshta të tregohen për përputhje',
'contextchars'             => 'Sa germa të tregohen për çdo rresht',
'recentchangesdays'        => 'Numri i ditëve të treguara në ndryshime së fundmi:',
'recentchangescount'       => 'Numri i titujve në ndryshime së fundmi',
'savedprefs'               => 'Parapëlqimet tuaja janë ruajtur.',
'timezonelegend'           => 'Zona kohore',
'timezonetext'             => 'Fusni numrin e orëve prej të cilave ndryshon ora lokale nga ajo e shërbyesit (UTC).',
'localtime'                => 'Tregimi i orës lokale',
'timezoneoffset'           => 'Ndryshimi',
'servertime'               => 'Ora e shërbyesit tani është',
'guesstimezone'            => 'Gjeje nga shfletuesi',
'allowemail'               => 'Lejo përdoruesit të më dërgojnë email',
'defaultns'                => 'Kërko automatikisht vetëm në këto hapësira:',
'default'                  => 'parazgjedhje',
'files'                    => 'Figura',

# User rights
'userrights-lookup-user'     => 'Ndrysho grupet e përdoruesit',
'userrights-user-editname'   => 'Fusni emrin e përdoruesit:',
'editusergroup'              => 'Redaktoni Grupet e Përdoruesve',
'userrights-editusergroup'   => 'Anëtarësimi tek grupet',
'saveusergroups'             => 'Ruaj Grupin e Përdoruesve',
'userrights-groupsmember'    => 'Anëtar i:',
'userrights-groupsavailable' => 'Të mundshme:',
'userrights-groupshelp'      => 'Duke zgjedhur nga lista e anëtarësimit mund të çanëtarësosh, dhe duke zgjedhur nga lista e grupeve të mundshme mund të anëtarësosh. Nuk do të ndryshojë anëtarësimi tek grupet e pazgjedhura. Mund të zgjedhësh ose çzgjedhësh duke mbajtur shtypur butonin Ctrl dhe majtas-shtypur.',
'userrights-reason'          => 'Arsyeja për ndryshimin:',

# Groups
'group'            => 'Grupi:',
'group-bot'        => 'Robot',
'group-sysop'      => 'Administrues',
'group-bureaucrat' => 'Burokrat',
'group-all'        => '(të gjitha)',

'group-bot-member'        => 'Robot',
'group-sysop-member'      => 'Administrues',
'group-bureaucrat-member' => 'Burokrat',

'grouppage-bot'        => '{{ns:project}}:Robotë',
'grouppage-sysop'      => '{{ns:project}}:Administrues',
'grouppage-bureaucrat' => '{{ns:project}}:Burokratë',

# User rights log
'rightslog'      => 'Regjistri i privilegjeve',
'rightslogtext'  => 'Ky është një regjistër për ndryshimet e titujve të përdoruesve.',
'rightslogentry' => 'të drejtat e $1 u ndryshuan prej $2 në $3',
'rightsnone'     => '(asgjë)',

# Recent changes
'recentchanges'                     => 'Ndryshime së fundmi',
'recentchangestext'                 => 'Ndiqni ndryshime së fundmi tek kjo faqe.',
'rcnote'                            => 'Më poshtë janë <strong>$1</strong> ndryshime së fundmi gjatë <strong>$2</strong> ditëve sipas të dhënave nga $3.',
'rcnotefrom'                        => 'Më poshtë janë ndryshime së fundmi nga <b>$2</b> (treguar deri në <b>$1</b>).',
'rclistfrom'                        => 'Tregon ndryshime së fundmi duke filluar nga $1',
'rcshowhideminor'                   => '$1 redaktimet e vogla',
'rcshowhidebots'                    => '$1 robotët',
'rcshowhideliu'                     => '$1 përdoruesit e regjistruar',
'rcshowhideanons'                   => '$1 përdoruesit anonim',
'rcshowhidepatr'                    => '$1 redaktime të patrulluara',
'rcshowhidemine'                    => '$1 redaktimet e mia',
'rclinks'                           => 'Trego $1 ndryshime gjatë $2 ditëve<br />$3',
'diff'                              => 'ndrysh',
'hist'                              => 'hist',
'hide'                              => 'fshih',
'show'                              => 'trego',
'minoreditletter'                   => 'v',
'newpageletter'                     => 'R',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 përdorues mbikqyrës]',
'rc_categories'                     => 'Kufizimi i kategorive (të ndara me "|")',
'rc_categories_any'                 => 'Të gjitha',

# Recent changes linked
'recentchangeslinked' => 'Ndryshimet fqinje',

# Upload
'upload'                      => 'Ngarkoni skeda',
'uploadbtn'                   => 'Ngarkoje',
'reupload'                    => 'Ngarkojeni përsëri',
'reuploaddesc'                => 'Kthehu tek formulari i dhënies.',
'uploadnologin'               => 'Nuk keni hyrë brënda',
'uploadnologintext'           => 'Duhet të keni [[Special:Userlogin|hyrë brenda]] për të dhënë skeda.',
'upload_directory_read_only'  => 'Skedari i ngarkimit ($1) nuk mund të shkruhet nga shërbyesi.',
'uploaderror'                 => 'Gabim dhënie',
'uploadtext'                  => "'''NDALO!''' Përpara se të jepni këtu skedë, lexoni dhe ndiqni [[Project:Rregullat e përdorimit të figurave|Rregullat e përdorimit të figurave]] të {{SITENAME}}-s. Mos jepni skeda për të cilat autori (ose ju) nuk ka dhënë të drejtë për përdorim nën licencat e përdorura nga {{SITENAME}}.

Për të parë ose për të kërkuar figurat e dhëna më parë,
shkoni tek [[Special:Imagelist|lista e figurave të dhëna]].
Dhëniet dhe grisjet janë të regjistruara në [[Special:Log|faqen e regjistrave]].

Përdorni formularin e më poshtëm për të dhënë skeda të figurave të reja për tu përdorur në ilustrimet e artikujve. Për shumicën e shfletuesve, do të shihni një buton \"Browse...\", i cili do të hapi dialogun standard të skedave të sistemit operativ që përdorni. 

Për të vendosur një figurë në një artikull, përdorni lidhjen sipas formës
* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Skeda.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Skeda.png|tekst përshkrues]]</nowiki>'''
ose të tjerë
* '''<nowiki>[[</nowiki>{{ns:Media}}<nowiki>:Skeda.ogg]]</nowiki>'''.

Përdorni stampa tek përshkrimi për të cilësuar licencën e duhur.",
'uploadlog'                   => 'regjistër dhënjesh',
'uploadlogpage'               => 'Regjistri i ngarkimeve',
'uploadlogpagetext'           => 'Më poshtë është një listë e skedave më të reja që janë ngarkuar.
Të gjithë orët janë me orën e shërbyesit (UTC).',
'filename'                    => 'Emri i skedës',
'filedesc'                    => 'Përmbledhje',
'fileuploadsummary'           => 'Përshkrimi:',
'filestatus'                  => 'Gjendja e të drejtave të autorit',
'filesource'                  => 'Burimi',
'uploadedfiles'               => 'Ngarkoni skeda',
'ignorewarning'               => 'Shpërfille paralajmërimin dhe ruaje skedën.',
'ignorewarnings'              => 'Shpërfill çdo paralajmërim',
'minlength'                   => 'Emrat e skedave duhet të kenë të paktën tre germa.',
'illegalfilename'             => 'Skeda "$1" përmban gërma që nuk lejohen tek titujt e faqeve. Ju lutem ndërrojani emrin dhe provoni ta ngarkoni përsëri.',
'badfilename'                 => 'Emri i skedës është ndërruar në "$1".',
'largefileserver'             => 'Skeda është më e madhe se sa serveri e lejon këtë.',
'emptyfile'                   => 'Skeda që keni dhënë është bosh ose mbi madhësinë e lejushme. Kjo gjë mund të ndodhi nëse shtypni emrin gabim, prandaj kontrolloni nëse dëshironi të jepni skedën me këtë emër.',
'fileexists'                  => 'Ekziston një skedë me atë emër, ju lutem kontrolloni $1 në qoftë se nuk jeni të sigurt nëse dëshironi ta zëvendësoni.',
'fileexists-forbidden'        => 'Ekziston një skedë me të njëjtin emër. Ju lutemi kthehuni mbrapsh dhe ngarkoni këtë skedë me një emër të ri. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ekziston një skedë me të njëjtin emër në magazinën e përbashkët. Ju lutem kthehuni mbrapsh dhe ngarkojeni këtë skedë me një emër të ri. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Dhënie e sukseshme',
'fileuploaded'                => 'Skeda "$1" u ngarkua me sukses. Ju lutem ndiqni këtë lidhje : ($2) për të shkuar tek faqja e përshkrimit dhe për të futur
informacion për skedën, si p.sh. ku e gjetët, kur u bë, kush e bëri, dhe çdo gjë tjetër që na duhet të dimë për të.',
'uploadwarning'               => 'Kujdes dhënie',
'savefile'                    => 'Ruaj skedën',
'uploadedimage'               => 'dha "[[$1]]"',
'uploaddisabled'              => 'Ndjesë, dhëniet janë bllokuar në këtë shërbyes dhe nuk është gabimi juaj.',
'uploaddisabledtext'          => 'Ngarkimi i skedave është ndaluar tek ky wiki.',
'uploadscripted'              => 'Skeda përmban HTML ose kode të tjera që mund të interpretohen gabimisht nga një shfletues.',
'uploadcorrupt'               => 'Skeda është e dëmtuar ose ka emër të gabuar. Ju lutemi kontrolloni skedën dhe ngarkoni atë përsëri.',
'uploadvirus'                 => 'Skeda përmban një virus! Detaje: $1',
'sourcefilename'              => 'Emri i skedës',
'destfilename'                => 'Emri mbas dhënies',
'watchthisupload'             => 'Mbikqyre këtë faqe',
'filewasdeleted'              => 'Një skedë më këtë emër është ngarkuar një here dhe pastaj është grisur. Duhet të shikoni $1 përpara se ta ngarkoni përsëri.',

'license'   => 'Licencimi',
'nolicense' => 'Asnjë nuk është zgjedhur',

# Image list
'imagelist'                 => 'Lista e figurave',
'imagelisttext'             => 'Më poshtë është një listë e $1 figurave të renditura sipas $2.',
'imagelistforuser'          => 'Kjo faqe tregon skedat të ngarkuara nga $1.',
'getimagelist'              => 'duke ngarkuar të gjithë listën e figurave',
'ilsubmit'                  => 'Kërko',
'showlast'                  => 'Trego $1 figurat e fundit të renditura sipas $2.',
'byname'                    => 'emrit',
'bydate'                    => 'datës',
'bysize'                    => 'madhësisë',
'imgdelete'                 => 'gris',
'imgdesc'                   => 'për',
'imgfile'                   => 'skeda',
'imglegend'                 => 'Legjendë: (për) = trego/redakto përshkrimin e skedës.',
'imghistory'                => 'Historia e skedës',
'revertimg'                 => 'ktheje',
'deleteimg'                 => 'grise',
'deleteimgcompletely'       => 'grise',
'imghistlegend'             => 'Legjendë: (tani) = ky është skeda e tanishme, (gris) = grise
këtë version të vjetër, (ktheje) = ktheje në këtë version të vjetër.
<br /><i>Shtyp datën për të parë skedën e dhënë në atë ditë</i>.',
'imagelinks'                => 'Lidhje skedash',
'linkstoimage'              => 'Këto faqe lidhen tek kjo skedë:',
'nolinkstoimage'            => 'Asnjë faqe nuk lidhet tek kjo skedë.',
'sharedupload'              => 'Kjo skedë është një ngarkim i përbashkët dhe mund të përdoret nga projekte të tjera.',
'shareduploadwiki'          => 'Ju lutem shikoni $1 për më shumë informacion.',
'shareduploadwiki-linktext' => 'faqja përshkruese e skedës',
'noimage'                   => 'Një skedë me këtë emër nuk ekziston akoma, ju mundeni ta $1 atë.',
'noimage-linktext'          => 'ngarkoni',
'uploadnewversion-linktext' => 'Ngarkoni një version të ri për këtë skedë',
'imagelist_date'            => 'Data',
'imagelist_name'            => 'Emri',
'imagelist_user'            => 'Përdoruesi',
'imagelist_size'            => 'Madhësia (bytes)',
'imagelist_description'     => 'Përshkrimi',
'imagelist_search_for'      => 'Kërko për emrin e figurës:',

# MIME search
'mimesearch'         => 'Kërkime MIME',
'mimesearch-summary' => 'Kjo faqe lejon kërkimin e skedave sipas llojit MIME. Kërkimi duhet të jetë i llojit: contenttype/subtype, p.sh. <tt>image/jpeg</tt>.',
'mimetype'           => 'Lloji MIME:',
'download'           => 'shkarkim',

# Unwatched pages
'unwatchedpages' => 'Faqe të pambikqyrura',

# List redirects
'listredirects' => 'Lista e përcjellimeve',

# Unused templates
'unusedtemplates'     => 'Stampa të papërdorura',
'unusedtemplatestext' => "Kjo faqe jep listën e të gjitha faqeve nën hapësirën Stampa të cilat nuk janë përdorur në faqe të tjera. Kujtohu të kontrollosh edhe për lidhje tek stampat përpara se t'i grisësh si të papërdorura.",
'unusedtemplateswlh'  => 'lidhje',

# Random redirect
'randomredirect' => 'Përcjellim i rastit',

# Statistics
'statistics'    => 'Statistika',
'sitestats'     => 'Statistikat e faqeve',
'userstats'     => 'Statistikat e përdoruesve',
'sitestatstext' => "Gjënden '''\$1''' faqe në totalin e regjistrit. Këto përfshijnë faqet e \"diskutimit\", faqe rreth {{SITENAME}}-s, faqe \"cungje\" të vogla, ridrejtime, dhe të tjera që ndoshta nuk kualifikohen si artikuj. Duke mos i përfshirë këto, gjënden '''\$2''' faqe që janë artikuj të ligjshëm.

Gjithashtu janë bërë '''\$4''' redaktime faqesh, përafërsisht '''\$5''' për faqe, dhe janë ngarkuar '''\$8''' skeda.

[[meta:Help:Job queue|Radha e punës]] tani është '''\$7'''.",
'userstatstext' => "Gjënden '''$1''' përdorues të regjistruar. '''$2''' prej tyre (ose '''$4'''%) janë me titull administrues (shikoni [[Special:Listusers|Listën e përdoruesve]] dhe $3).",

'disambiguations'      => 'Faqe kthjelluese',
'disambiguationspage'  => 'Këto janë stampat në përdorim për faqet kthjelluese. Stampat e lidhura këtu përdoren për të gjetur faqet kthjelluese nga [[Special:Disambiguations|faqja speciale]]

* [[Template:Kthjellim]]',
'disambiguations-text' => "Faqet e mëposhtme lidhen tek një '''faqe kthjelluese'''. Ato duhet të kenë lidhje të drejtpërdrejtë tek artikujt e nevojshëm.<br /> Një faqe trajtohet si faqe kthjelluese nëse përdor stampat e lidhura nga [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Përcjellime dopjo',
'doubleredirectstext' => '<b>Kujdes:</b> Kjo listë mund të ketë lidhje gabim. D.m.th. ka tekst dhe lidhje mbas #REDIRECT-it të parë.

<br />
Çdo rresht ka lidhje tek përcjellimi i parë dhe i dytë, gjithashtu ka edhe rreshtin e parë të tekstit të përcjellimit të dytë, duke dhënë dhe artikullin e "vërtetë", me të cilin përcjellimi i parë duhet të lidhet.',

'brokenredirects'        => 'Përcjellime të prishura',
'brokenredirectstext'    => "Përcjellimet që vijojnë lidhen tek një artikull që s'ekziston.",
'brokenredirects-edit'   => '(redakto)',
'brokenredirects-delete' => '(grise)',

'withoutinterwiki'        => 'Artikuj pa lidhje interwiki',
'withoutinterwiki-header' => 'Artikujt në vazhdim nuk kanë asnjë lidhje te wikit në gjuhët tjera:',

'fewestrevisions' => 'Artikuj më të paredaktuar',

# Miscellaneous special pages
'nbytes'                  => '$1 bytes',
'ncategories'             => '$1 kategori',
'nlinks'                  => '$1 lidhje',
'nmembers'                => '$1 anëtarë',
'nrevisions'              => '$1 redaktime',
'nviews'                  => '$1 shikime',
'specialpage-empty'       => 'Kjo faqe është boshe.',
'lonelypages'             => 'Artikuj të palidhur',
'lonelypagestext'         => 'Te artikujt në vijim nuk lidh asnjë artikull tjetër në këtë wiki.',
'uncategorizedpages'      => 'Artikuj të pakategorizuar',
'uncategorizedcategories' => 'Kategori të pakategorizuara',
'uncategorizedimages'     => 'Figura pa kategori',
'unusedcategories'        => 'Kategori të papërdorura',
'unusedimages'            => 'Figura të papërdorura',
'popularpages'            => 'Artikuj të frekuentuar shpesh',
'wantedcategories'        => 'Kategori më të dëshiruara',
'wantedpages'             => 'Artikuj më të dëshiruar',
'mostlinked'              => 'Artikuj më të lidhur',
'mostlinkedcategories'    => 'Kategori më të lidhura',
'mostlinkedtemplates'     => 'Stampa më të lidhur',
'mostcategories'          => 'Artikuj më të kategorizuar',
'mostimages'              => 'Figura më të lidhura',
'mostrevisions'           => 'Artikuj më të redaktuar',
'allpages'                => 'Të gjitha faqet',
'prefixindex'             => 'Treguesi i parashtesave',
'randompage'              => 'Artikull i rastit',
'shortpages'              => 'Artikuj të shkurtër',
'longpages'               => 'Artikuj të gjatë',
'deadendpages'            => 'Artikuj pa rrugëdalje',
'deadendpagestext'        => 'Artikujt në vijim nuk kanë asnjë lidhje me artikuj e tjerë në këtë wiki.',
'protectedpages'          => 'Faqe të mbrojtura',
'protectedpagestext'      => 'Faqet e mëposhtme janë të mbrojtura nga zhvendosja apo redaktimi',
'listusers'               => 'Lista e përdoruesve',
'specialpages'            => 'Faqet speciale',
'spheading'               => 'Faqe speciale për të gjithë përdoruesit',
'restrictedpheading'      => 'Faqe speciale të kufizuara',
'rclsub'                  => '(për faqet e lidhura nga "$1")',
'newpages'                => 'Artikuj të rinj',
'newpages-username'       => 'Përdoruesi:',
'ancientpages'            => 'Artikuj më të vjetër',
'intl'                    => 'Gjuhë-lidhje',
'move'                    => 'Zhvendose',
'movethispage'            => 'Zhvendose faqen',
'unusedimagestext'        => '<p>Ju lutem, vini re se hapësira të tjera si p.sh ato që kanë të bëjnë me gjuhë të ndryshme mund të lidhin
një figurë me një URL në mënyrë direkte, kështuqë ka mundësi që këto figura të rreshtohen këtu megjithëse janë në përdorim.</p>',
'unusedcategoriestext'    => 'Kategoritë në vazhdim ekzistojnë edhe pse asnjë artikull ose kategori nuk i përdor ato.',

# Book sources
'booksources'               => 'Burime librash',
'booksources-search-legend' => 'Kërkim burimor librash',
'booksources-go'            => 'Shko',
'booksources-text'          => 'Më posht është një listë me lidhje të cilët shesin ose përdorin libra dhe munden të kenë informacione për librat që kërkoni ju:',

'categoriespagetext' => 'Ndodhen këto kategori:',
'data'               => 'Të dhëna',
'userrights'         => 'Ndrysho privilegjet e përdoruesve',
'groups'             => 'Grupet e përdoruesve',
'alphaindexline'     => '$1 deri në $2',
'version'            => 'Versioni',

# Special:Log
'specialloguserlabel'  => 'Përdoruesi:',
'speciallogtitlelabel' => 'Titulli:',
'log'                  => 'Regjistrat',
'alllogstext'          => 'Kjo faqe tregon një pamje të përmbledhur të regjistrave të ngarkimeve, grisjeve, mbrojtjeve, bllokimeve, dhe të veprimeve administrative. Mundeni të kufizoni informactionin sipas tipit të regjistrit, emrit të përdoruesit, si dhe faqes në çështje.',
'logempty'             => 'Nuk ka asnjë përputhje në regjistër.',

# Special:Allpages
'nextpage'          => 'Faqja më pas ($1)',
'prevpage'          => 'Faqja më parë ($1)',
'allpagesfrom'      => 'Trego faqet duke filluar nga:',
'allarticles'       => 'Të gjithë artikujt',
'allinnamespace'    => 'Të gjitha faqet (hapësira $1)',
'allnotinnamespace' => 'Të gjitha faqet (jo në hapësirën $1)',
'allpagesprev'      => 'Më para',
'allpagesnext'      => 'Më pas',
'allpagessubmit'    => 'Shko',
'allpagesprefix'    => 'Trego faqet me parashtesë:',

# Special:Listusers
'listusersfrom'      => 'Trego përdoruesit duke filluar prej te:',
'listusers-submit'   => 'Trego',
'listusers-noresult' => "Asnjë përdorues s'u gjet.",

# E-mail user
'mailnologin'     => "S'ka adresë dërgimi",
'mailnologintext' => 'Duhet të keni [[Special:Userlogin|hyrë brenda]] dhe të keni një adresë të saktë në [[Special:Preferences|parapëlqimet]] tuaja për tu dërguar email përdoruesve të tjerë.',
'emailuser'       => 'Email përdoruesit',
'emailpage'       => 'Dërgo email përdoruesve',
'emailpagetext'   => 'Në qoftë se ky përdorues ka dhënë një adresë të saktë në parapëlqimet, formulari më poshtë do t\'i dërgojë një mesazh. 

Adresa e email-it që keni dhënë në parapëlqimet do të duket si pjesa "From" e adresës së mesazhit, kështuqë marrësi do të ketë mundësi tu përgjigjet.',
'usermailererror' => 'Objekti postal ktheu gabimin:',
'defemailsubject' => '{{SITENAME}} email',
'noemailtitle'    => "S'ka adresë email-i",
'noemailtext'     => "Ky përdorues s'ka dhënë një adresë të saktë,
ose ka vendosur të mos pranojë mesazhe email-i nga përdorues të tjerë.",
'emailfrom'       => 'Nga',
'emailto'         => 'Për',
'emailsubject'    => 'Subjekti',
'emailmessage'    => 'Mesazh',
'emailsend'       => 'Dërgo',
'emailccme'       => 'Dërgom edhe mua një kopje të këtij emaili.',
'emailccsubject'  => 'Kopje e emailit tuaj për $1: $2',
'emailsent'       => 'Email-i u dërgua',
'emailsenttext'   => 'Email-i është dërguar.',

# Watchlist
'watchlist'            => 'Lista mbikqyrëse',
'mywatchlist'          => 'Lista mbikqyrëse',
'watchlistfor'         => "(për '''$1''')",
'nowatchlist'          => 'Nuk keni asnjë faqe në listën mbikqyrëse.',
'watchlistcount'       => "'''Keni $1 faqe nën mbikqyrje duke përfshirë dhe faqet e diskutimit.'''",
'clearwatchlist'       => 'Pastroni listën mbikqyrëse',
'watchlistcleartext'   => 'Dëshironi me të vërtetë ta boshatisni listën?',
'watchlistclearbutton' => 'Boshatise listën mbikqyrëse',
'watchlistcleardone'   => 'Lista mbikqyrëse është boshatisur. Janë hequr $1 faqe.',
'watchnologin'         => 'Nuk keni hyrë brënda',
'watchnologintext'     => 'Duhet të keni [[Special:Userlogin|hyrë brenda]] për të ndryshuar listën mbikqyrëse.',
'addedwatch'           => 'U shtua tek lista mbikqyrëse',
'addedwatchtext'       => "Faqja \"\$1\" është shtuar [[Special:Watchlist|listës mbikqyrëse]] tuaj. Ndryshimet e ardhshme të kësaj faqeje dhe faqes së diskutimit të saj do të jepen më poshtë, dhe emri i faqes do të duket i '''trashë''' në [[Special:Recentchanges|listën e ndryshimeve së fundmi]] për t'i dalluar më kollaj.

Në qoftë se dëshironi të hiqni një faqe nga lista mbikqyrëse më vonë, shtypni \"çmbikqyre\" në tabelën e sipërme.",
'removedwatch'         => 'U hoq nga lista mibkqyrëse',
'removedwatchtext'     => 'Faqja "$1" është hequr nga lista mbikqyrëse e juaj.',
'watch'                => 'Mbikqyre',
'watchthispage'        => 'Mbikqyre këtë faqe',
'unwatch'              => 'Çmbikqyre',
'unwatchthispage'      => 'Mos e mbikqyr',
'notanarticle'         => 'Nuk është artikull',
'watchnochange'        => 'Asnjë nga artikujt nën mbikqyrje nuk është redaktuar gjatë kohës së dhënë.',
'watchdetails'         => "*'''$1''' faqe nën mbikqyrje duke mos numëruar faqet e diskutimit
*'''$2''' faqe brënda përkufizimit janë redaktuar
<!--*$3...-->
<center>'''[$4 Trego dhe redakto tërë listën]'''</center>",
'wlheader-enotif'      => '* Njoftimi me email është lejuar.',
'wlheader-showupdated' => "* Faqet që kanë ndryshuar nga vizita juaj e fundit do të tregohen të '''trasha'''",
'watchmethod-recent'   => 'duke parë ndryshime së fundmi për faqe nën mbikqyrje',
'watchmethod-list'     => 'duke parë faqet nën mbikqyrje për ndryshime së fundmi',
'removechecked'        => 'Hiq të zgjedhurat',
'watchlistcontains'    => 'Lista mbikqyrëse e juaj ka $1 faqe.',
'watcheditlist'        => "Këtu jepet një listë e alfabetizuar e faqeve nën mbikqyrje. Zgjidhni kutinë e sejcilës faqe që dëshironi të hiqni nga lista dhe shtypni butonin 'Hiq të zgjedhurat' në fund të faqes.",
'removingchecked'      => 'Duke hequr artikujt e zgjedhur nga lista mbikqyrëse...',
'couldntremove'        => "S'mundi të heq arikullin '$1'...",
'iteminvalidname'      => "Problem me artikullin '$1', titull jo i saktë...",
'wlnote'               => 'Më poshtë janë $1 ndryshimet e <b>$2</b> orëve së fundmi.',
'wlshowlast'           => 'Trego $1 orët $2 ditët $3',
'wlsaved'              => 'Kjo është një kopje e ruajtur e listës mbikqyrëse tuaj.',
'wldone'               => 'Veprim i mbaruar',

'enotif_mailer'      => 'Postieri njoftues i {{SITENAME}}',
'enotif_reset'       => 'Markoi të gjitha faqet e vizituara',
'enotif_newpagetext' => 'Kjo është një faqe e re.',
'changed'            => 'ndryshuar',
'created'            => 'u krijua',
'enotif_subject'     => '{{SITENAME}} faqja $PAGETITLE u $CHANGEDORCREATED prej $PAGEEDITOR',
'enotif_lastvisited' => 'Shikoni $1 për të gjitha ndryshimet që prej vizitës tuaj të fundit.',
'enotif_body'        => 'I/E dashur $WATCHINGUSERNAME,

faqja $PAGETITLE tek {{SITENAME}} është $CHANGEDORCREATED më $PAGEEDITDATE nga $PAGEEDITOR, shikoni $PAGETITLE_URL për versionin e tanishëm.

$NEWPAGE

Përmbledhja e redaktorit: $PAGESUMMARY $PAGEMINOREDIT

Mund të lidheni me redaktorin nëpërmjet:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nuk do të ketë njoftime të tjera në rast se ka ndryshime vijuese në qoftë se nuk vizitoni faqen. Gjithashtu mund të ktheni gjendjen e njoftimeve për të gjitha faqet nën mbikqyrje.

             Miku juaj njoftues nga {{SITENAME}}

--
Për të ndryshuar parapëlqimet e mbikqyrjes shikoni {{fullurl:Special:Watchlist/edit}}

Për të na dhënë përshtypjet tuaja ose për ndihmë të mëtejshme:
{{fullurl:Help:Contents}}',

# Delete/protect/revert
'deletepage'                  => 'Grise faqen',
'confirm'                     => 'Konfirmoni',
'excontent'                   => "përmbajtja ishte: '$1'",
'excontentauthor'             => "përmbajtja ishte: '$1' (dhe i vetmi redaktor ishte '$2')",
'exbeforeblank'               => "përmbajtja përpara boshatisjes ishte: '$1'",
'exblank'                     => 'faqja është bosh',
'confirmdelete'               => 'Konfirmoni grisjen',
'deletesub'                   => '(Duke grisur "$1")',
'historywarning'              => 'Kujdes: Faqja që jeni bërë gati për të grisur ka histori:',
'confirmdeletetext'           => "Jeni duke grisur '''përfundimisht''' një faqe ose një skedë me tër historinë e saj nga regjistri. Ju lutem konfirmoni që keni ndër mënd ta bëni këtë gjë, që i kuptoni se cilat janë pasojat, dhe që po veproni në përputhje me [[{{MediaWiki:policy-url}}]].",
'actioncomplete'              => 'Veprim i mbaruar',
'deletedtext'                 => '"$1" është grisur nga regjistri. Shikoni $2 për një pasqyrë të grisjeve së fundmi.',
'deletedarticle'              => 'grisi "$1"',
'dellogpage'                  => 'Regjistri i grisjeve',
'dellogpagetext'              => 'Më poshtë është një listë e grisjeve më të fundit.
Të gjitha kohët janë sipas orës së shërbyesit (UTC).',
'deletionlog'                 => 'regjistrin e grisjeve',
'reverted'                    => 'Kthehu tek një version i vjetër',
'deletecomment'               => 'Arsyeja',
'imagereverted'               => 'Kthimi tek një version i sukseshëm.',
'rollback'                    => 'Riktheji mbrapsh redaktimet',
'rollback_short'              => 'Riktheje',
'rollbacklink'                => 'riktheje',
'rollbackfailed'              => 'Rikthimi dështoi',
'cantrollback'                => 'Nuk munda ta kthejë redaktimin; redaktori i fundit është i vetmi autor i këtij artikulli.',
'alreadyrolled'               => 'Nuk munda ta rikthej redaktimin e fundit e [[:$1]] nga [[User:$2|$2]] ([[User talk:$2|Diskutim]]); dikush tjetër e ka redaktuar ose rikthyer këtë faqe.

Redaktimi i fundit është bërë nga [[User:$3|$3]] ([[User talk:$3|Diskutim]]).',
'editcomment'                 => 'Komenti i redaktimit ishte: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Ndryshimet e [[Special:Contributions/$2|$2]] ([[User talk:$2|diskutimet]]) u kthyen mbrapsht, artikulli tani ndodhet në versionin e fundit nga [[User:$1|$1]].',
'sessionfailure'              => 'Më duket se ka një problem me seancën tuaj të hyrjes. Veprimi juaj nuk është kryer për tu mbrojtur nga ndonjë veprim dashakeq kundrejt shfletimit tuaj. Ju lutem kthehuni mbrapsh, rifreskoni faqen prej nga erdhët dhe provojeni përsëri veprimin.',
'protectlogpage'              => 'Regjistri i mbrojtjeve',
'protectlogtext'              => 'Më poshtë është një listë e "mbrojtjeve/lirimeve" të faqeve. Shikoni [[{{SITENAME}}:Faqe e mbrojtur]] për më shumë informacion.',
'protectedarticle'            => 'mbrojti [[$1]]',
'unprotectedarticle'          => 'liroi [[$1]]',
'protectsub'                  => '(Duke ndryshuar mbrojtjen e "$1")',
'confirmprotect'              => 'Konfirmoni',
'protectcomment'              => 'Arsyeja për mbrojtjen',
'protectexpiry'               => 'Afati',
'unprotectsub'                => '(Duke liruar "$1")',
'protect-unchain'             => 'Ndrysho lejen e zhvendosjeve',
'protect-text'                => 'Këtu mund të shikoni dhe ndryshoni nivelin e mbrojtjes për faqen [[$1]]. Ju lutem ndiqni rregullat e dhëna tek [[Project:Faqe e mbrojtur|faqet e mbrojtura]].',
'protect-locked-blocked'      => 'Nuk mund të ndryshoni nivelet e mbrojtjes duke qenë i bllokuar. Kufizimet e kësaj faqeje janë <strong>$1</strong>:',
'protect-locked-dblock'       => 'Nivelet e mbrojtjes nuk mund të ndryshohen pasi regjistri është i bllokuar. Kufizimet e kësaj faqeje janë <strong>$1</strong>:',
'protect-locked-access'       => 'Llogaria juaj nuk ka privilegjet e nevojitura për të ndryshuar nivelin e mbrojtjes. Kufizimet e kësaj faqeje janë <strong>$1</strong>:',
'protect-default'             => '(parazgjedhje)',
'protect-level-autoconfirmed' => 'Blloko përdoruesit pa llogari',
'protect-level-sysop'         => 'Lejo vetëm administruesit',
'protect-cascade'             => 'Mbrojtje e ndërlidhur - mbro çdo faqe që përfshihet në këtë faqe.',
'restriction-type'            => 'Lejet:',
'restriction-level'           => 'Mbrojtjet:',
'minimum-size'                => 'Madhësia minimale',
'maximum-size'                => 'Madhësia maksimale',

# Restrictions (nouns)
'restriction-edit' => 'Redaktimi',
'restriction-move' => 'Zhvendosja',

# Restriction levels
'restriction-level-sysop'         => 'mbrojtje e plotë',
'restriction-level-autoconfirmed' => 'gjysëm mbrojtje',
'restriction-level-all'           => 'çdo nivel',

# Undelete
'undelete'                 => 'Restauroni faqet e grisura',
'undeletepage'             => 'Shikoni ose restauroni faqet e grisura',
'viewdeletedpage'          => 'Shikoni faqet e grisura',
'undeletepagetext'         => 'Më poshtë janë faqet që janë grisur por që gjënden akoma në arkiv dhe mund të restaurohen. Arkivi boshatiset periodikisht.',
'undeleteextrahelp'        => "Lini bosh të gjitha kutitë e zgjedhjes dhe shqypni '''''Restauro!''''' për të restauruar të gjitha versionet e faqes. Për të bërë një restaurim të pjesshëm zgjidhni kutitë e versioneve që dëshironi të restauroni dhe shtypni '''''Restauro!'''''. Mund të boshatisni të gjitha zgjedhjet dhe arsyen duke shtypur '''''Boshatis'''''.",
'undeleterevisions'        => '$1 versione u futën në arkiv',
'undeletehistory'          => 'N.q.s. restauroni një faqe, të gjitha versionet do të restaurohen në histori. N.q.s. një faqe e re me të njëjtin titull është krijuar që nga grisja, versionet e restauruara do të duken më përpara në histori, dhe versioni i faqes së fundit nuk do të shkëmbehet automatikisht.',
'undeletehistorynoadmin'   => 'Kjo faqe është grisur. Arsyeja për grisjen është dhënë tek përmbledhja më poshtë bashkë me hollësitë e përdoruesve që e kanë redaktuar.',
'undelete-revision'        => 'Version i grisur i $1 nga $2:',
'undeletebtn'              => 'Restauro!',
'undeletereset'            => 'Boshatis',
'undeletecomment'          => 'Arsyeja:',
'undeletedarticle'         => 'u restaurua "$1"',
'undeletedrevisions'       => '$1 versione u restauruan',
'undeletedrevisions-files' => '$1 versione dhe $2 skeda janë restauruar',
'undeletedfiles'           => '$1 skeda u restauruan',
'cannotundelete'           => 'Restaurimi dështoi; dikush tjetër mund ta ketë restauruar faqen përpara jush.',
'undeletedpage'            => "<big>'''$1 është restauruar'''</big>

Shikoni [[Special:Log/delete|regjistrin e grisjeve]] për grisjet dhe restaurimet së fundmi.",
'undelete-header'          => 'Shikoni [[Special:Log/delete|regjistrin e grisjeve]] për faqet e grisura së fundmi.',
'undelete-search-box'      => 'Kërko faqet e grisura',
'undelete-search-prefix'   => 'Trego faqet duke filluar nga:',
'undelete-search-submit'   => 'Kërko',
'undelete-no-results'      => 'Nuk u gjet asnjë faqe përputhëse tek arkivi i grisjeve.',

# Namespace form on various pages
'namespace' => 'Hapësira:',
'invert'    => 'Kundër zgjedhjes',

# Contributions
'contributions' => 'Kontributet',
'mycontris'     => 'Redaktimet e mia',
'contribsub2'   => 'Për $1 ($2)',
'nocontribs'    => 'Nuk ka asnjë ndryshim që përputhet me këto kritere.',
'ucnote'        => 'Më poshtë janë redaktimet më të fundit të <b>$1</b> gjatë <b>$2</b> ditëve.',
'uclinks'       => 'Shikoni $1 redaktimet e fundit; shikoni $2 ditët e fundit.',
'uctop'         => ' (sipër)',

'sp-contributions-newest'      => 'Më të rejat',
'sp-contributions-oldest'      => 'Më të vjetrat',
'sp-contributions-newer'       => '$1 më para',
'sp-contributions-older'       => '$1 më pas',
'sp-contributions-newbies'     => 'Trego vetëm redaktimet e llogarive të reja',
'sp-contributions-newbies-sub' => 'Për newbies',
'sp-contributions-blocklog'    => 'Regjistri i bllokimeve',
'sp-contributions-search'      => 'Kërko tek kontributet',
'sp-contributions-username'    => 'IP Addresa ose Përdoruesi:',
'sp-contributions-submit'      => 'Kërko',

'sp-newimages-showfrom' => 'duke filluar nga $1',

# What links here
'whatlinkshere'       => 'Lidhjet këtu',
'notargettitle'       => 'Asnjë artikull',
'notargettext'        => 'Nuk keni dhënë asnjë artikull ose përdorues mbi të cilin të përdor këtë funksion.',
'linklistsub'         => '(Listë lidhjesh)',
'linkshere'           => 'Faqet e mëposhtme lidhen këtu:',
'nolinkshere'         => 'Asnjë faqe nuk lidhet këtu.',
'isredirect'          => 'faqe përcjellëse',
'istemplate'          => 'përfshirë',
'whatlinkshere-links' => '← lidhje',

# Block/unblock
'blockip'                     => 'Blloko përdorues',
'blockiptext'                 => 'Përdorni formularin e mëposhtëm për të hequr lejen e shkrimit për një përdorues ose IP-ë specifike.
Kjo duhet bërë vetëm në raste vandalizmi, dhe në përputhje me [[{{SITENAME}}:Rregullat|rregullat e {{SITENAME}}-s]].
Plotësoni arsyen specifike më poshtë (p.sh., tregoni faqet specifike që u vandalizuan).

Afati është sipas standardit GNU (http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html), p.sh. "1 hour", "2 days", "next Wednesday", "1 January 2017", ose për ndryshe "indefinite" ose "infinite".',
'ipaddress'                   => 'IP Adresë/përdorues',
'ipadressorusername'          => 'Adresë IP ose emër përdoruesi',
'ipbexpiry'                   => 'Afati',
'ipbreason'                   => 'Arsyeja',
'ipbreasonotherlist'          => 'Arsye tjetër',
'ipbreason-dropdown'          => '*Arsyet më të shpeshta të bllokimit
** Postimi i informacioneve të rreme
** Largimi i përmbajtjes së faqes
** Futja e lidhjeve "spam"
** Futja e informatave pa kuptim në faqe
** Sjellje arrogante/perverze 
** Përdorimi i më shumë llogarive të përdoruesve
** Nofkë të papranueshme',
'ipbanononly'                 => 'Blloko vetëm përdoruesin anonim',
'ipbcreateaccount'            => 'Mbroje krijimin e llogarive',
'ipbenableautoblock'          => 'Blloko edhe IP adresën që ka përdor ky përdorues deri tash, si dhe të gjitha subadresat nga të cilat mundohet ky përdorues të editoj.',
'ipbsubmit'                   => 'Blloko këtë përdorues',
'ipbother'                    => 'Kohë tjetër',
'ipboptions'                  => '1 Orë:1 hour,2 Orë:2 hours,6 Orë:6 hours,1 Ditë:1 day,3 Ditë:3 days,1 Javë:1 week,2 Javë:2 weeks,1 Muaj:1 month,3 Muaj:3 months,1 Vjet:1 year,Pa kufi:infinite',
'ipbotheroption'              => 'tjetër',
'ipbotherreason'              => 'Arsye tjetër/shtesë',
'badipaddress'                => 'Nuk ka asnjë përdorues me atë emër',
'blockipsuccesssub'           => 'Bllokimi u bë me sukses',
'blockipsuccesstext'          => 'Përdoruesi/IP-Adresa [[{{ns:special}}:Contributions/$1|$1]] u bllokua. Veprimi u regjistrua te [[{{ns:special}}:Log/block|Regjistri i bllokimeve]]. 

Shiko te [[{{ns:special}}:Ipblocklist|Lista e përdoruesve dhe e IP adresave të bllokuara]] për të çbllokuar Përdorues/IP.',
'ipb-edit-dropdown'           => 'Redakto arsyet e bllokimit',
'ipb-unblock-addr'            => 'Çblloko $1',
'ipb-unblock'                 => 'Çblloko përdorues dhe IP të bllokuara',
'ipb-blocklist-addr'          => 'Shikoni bllokime në fuqi për $1',
'ipb-blocklist'               => 'Përdorues dhe IP adresa të bllokuara',
'unblockip'                   => 'Çblloko përdoruesin',
'unblockiptext'               => "Përdor formularin e më poshtëm për t'i ridhënë leje shkrimi
një përdoruesi ose IP adreseje të bllokuar.",
'ipusubmit'                   => 'Çblloko këtë përdorues',
'unblocked'                   => '[[User:$1|$1]] është çbllokuar',
'ipblocklist'                 => 'Lista e përdoruesve dhe e IP adresave të bllokuara',
'blocklistline'               => '$1, $2 bllokoi $3 ($4)',
'infiniteblock'               => 'pakufi',
'expiringblock'               => 'mbaron më $1',
'anononlyblock'               => 'vetëm anonimët',
'createaccountblock'          => 'hapja e lloggarive është bllokuar',
'blocklink'                   => 'blloko',
'unblocklink'                 => 'çblloko',
'contribslink'                => 'kontribute',
'autoblocker'                 => 'I bllokuar automatikisht sepse përdor të njëjtën IP adresë si "$1". Arsye "$2".',
'blocklogpage'                => 'Regjistri i bllokimeve',
'blocklogentry'               => 'bllokoi [[$1]] për një kohë prej: $2',
'blocklogtext'                => 'Ky është një regjistër bllokimesh dhe çbllokimesh të përdoruesve. IP-të e bllokuara automatikisht nuk janë të dhëna. Shikoni dhe [[Special:Ipblocklist|listën e IP-ve të bllokuara]] për një listë të bllokimeve të tanishme.',
'unblocklogentry'             => 'çbllokoi "$1"',
'range_block_disabled'        => 'Mundësia e administruesve për të bllokuar me shtrirje është çaktivizuar.',
'ipb_expiry_invalid'          => 'Afati i kohës është gabim.',
'ip_range_invalid'            => 'Shtrirje IP gabim.',
'proxyblocker'                => 'Bllokuesi i ndërmjetëseve',
'proxyblockreason'            => 'IP adresa juaj është bllokuar sepse është një ndërmjetëse e hapur. Ju lutem lidhuni me kompaninë e shërbimeve të Internetit që përdorni dhe i informoni për këtë problem sigurije.',
'proxyblocksuccess'           => 'Mbaruar.',
'sorbs'                       => 'SORBS DNSBL',
'sorbsreason'                 => 'Adresa IP e juaj është radhitur si ndërmjetëse e hapur tek lista DNSBL.',
'sorbs_create_account_reason' => 'Adresa IP e juaj është radhitur si ndërmjetëse e hapur tek lista DNSBL. Nuk ju lejohet të hapni një llogari.',

# Developer tools
'lockdb'              => 'Blloko regjistrin',
'unlockdb'            => 'Çblloko regjistrin',
'lockdbtext'          => 'Bllokimi i regjistrit do të ndërpresi mundësinë e përdoruesve për të redaktuar faqet, për të ndryshuar parapëlqimet, për të ndryshuar listat mbikqyrëse të tyre, dhe për gjëra të tjera për të cilat nevojiten shkrime në regjistër.
Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim, dhe se do të çbllokoni regjistrin
kur të mbaroni së kryeri mirëmbajtjen.',
'unlockdbtext'        => 'Çbllokimi i regjistrit do të lejojë mundësinë e të gjithë përdoruesve për të redaktuar faqe, për të ndryshuar parapëlqimet e tyre, për të ndryshuar listat mbikqyrëse të tyre, dhe gjëra të tjera për të cilat nevojiten shkrime në regjistër. Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim.',
'lockconfirm'         => 'Po, dëshiroj me të vërtetë të bllokoj regjistrin.',
'unlockconfirm'       => 'Po, dëshiroj me të vërtetë të çbllokoj regjistrin',
'lockbtn'             => 'Blloko regjistrin',
'unlockbtn'           => 'Çblloko regjistrin',
'locknoconfirm'       => 'Nuk vendose kryqin tek kutia konfirmuese.',
'lockdbsuccesssub'    => 'Regjistri u bllokua me sukses',
'unlockdbsuccesssub'  => 'Regjistri u çbllokua me sukses',
'lockdbsuccesstext'   => 'Regjistri i {{SITENAME}} është bllokuar.
<br />Kujtohu ta çbllokosh mbasi të kesh mbaruar mirëmbajtjen.',
'unlockdbsuccesstext' => 'Regjistri i {{SITENAME}} është çbllokuar.',

# Move page
'movepage'                => 'Zhvendose faqen',
'movepagetext'            => "Duke përdorur formularin e mëposhtëm do të ndërroni titullin e një faqeje, duke zhvendosur gjithë historinë përkatëse tek titulli i ri. Titulli i vjetër do të bëhet një faqe përcjellëse tek titulli i ri. Lidhjet tek faqja e vjetër nuk do të ndryshohen; duhet të kontrolloni [[Special:SpecialPages|mirëmbajtjen]] për përcjellime të dyfishta ose të prishura.
Keni përgjegjësinë për tu siguruar që lidhjet të vazhdojnë të jenë të sakta.

Vini re se kjo faqe '''nuk''' do të zhvendoset n.q.s. ekziston një faqe me titullin e ri, përveçse kur ajo të jetë bosh ose një përcjellim dhe të mos ketë një histori të vjetër. Kjo do të thotë se mund ta zhvendosni një faqe prapë tek emri
i vjetër n.q.s. keni bërë një gabim, dhe s'mund ta prishësh një faqe që ekziston.

<b>KUJDES!</b>
Ky mund të jetë një ndryshim i madh dhe gjëra të papritura mund të ndodhin për një faqe të shumë-frekuentuar; ju lutem, kini kujdes dhe mendohuni mirë para se të përdorni këtë funksion.",
'movepagetalktext'        => "Faqja a bashkangjitur e diskutimit, n.q.s. ekziston, do të zhvendoset automatikisht '''përveçse''' kur:
*Zhvendosni një faqe midis hapësirave të ndryshme,
*Një faqe diskutimi jo-boshe ekziston nën titullin e ri, ose
*Nuk zgjidhni kutinë më poshtë.

Në ato raste, duhet ta zhvendosni ose përpuqni faqen vetë n.q.s. dëshironi.",
'movearticle'             => 'Zhvendose faqen',
'movenologin'             => 'Nuk keni hyrë brenda',
'movenologintext'         => 'Duhet të keni hapur një llogari dhe të keni [[Special:Userlogin|hyrë brenda]] për të zhvendosur një faqe.',
'newtitle'                => 'Tek titulli i ri',
'move-watch'              => 'Mbikqyre këtë faqe',
'movepagebtn'             => 'Zhvendose faqen',
'pagemovedsub'            => 'Zhvendosja doli me sukses',
'pagemovedtext'           => 'Faqja "[[$1]]" u zhvendos tek "[[$2]]".',
'articleexists'           => 'Një faqe me atë titull ekziston, ose titulli që zgjodhët nuk është i saktë. Ju lutem zgjidhni një tjetër.',
'talkexists'              => 'Faqja për vete u zhvendos, ndërsa faqja e diskutimit nuk u zhvendos sepse një e tillë ekziston tek titulli i ri. Ju lutem, përpuqini vetë.',
'movedto'                 => 'zhvendosur tek',
'movetalk'                => 'Zhvendos edhe faqen e diskutimeve, në qoftë se është e mundur.',
'talkpagemoved'           => 'Faqja e diskutimeve korrespondente u zhvendos gjithashtu.',
'talkpagenotmoved'        => 'Faqja e diskutimeve korrespondente <strong>nuk</strong> u zhvendos.',
'1movedto2'               => '[[$1]] u zhvendos tek [[$2]]',
'1movedto2_redir'         => '[[$1]] u zhvendos tek [[$2]] dhe u krijua një faqe përcjellimi',
'movelogpage'             => 'Regjistri i zhvendosjeve',
'movelogpagetext'         => 'Më poshtë është një listë e faqeve të zhvendosura',
'movereason'              => 'Arsyeja',
'revertmove'              => 'ktheje',
'delete_and_move'         => 'Grise dhe zhvendose',
'delete_and_move_text'    => '==Nevojitet grisje==

Faqja "[[$1]]" ekziston, dëshironi ta grisni për të mundësuar zhvendosjen?',
'delete_and_move_confirm' => 'Po, grise faqen',
'delete_and_move_reason'  => 'U gris për të liruar vendin për përcjellim',
'selfmove'                => 'Nuk munda ta zhvendos faqen sepse titulli i ri është i njëjtë me të vjetrin.',
'immobile_namespace'      => 'Titulli i dëshiruar i faqes është i veçantë; Faqja nuk mund të zhvendoset në hapësira me emër tjetër.',

# Export
'export'            => 'Eksportoni faqe',
'exporttext'        => 'Mund të eksportoni tekstin dhe historinë e redaktimit e një faqeje ose disa faqesh të mbështjesha në XML; kjo mund të importohet në një wiki tjetër që përdor softuerin MediaWiki (tani për tani, ky opsion nuk është përfshirë tek {{SITENAME}}).

Për të eksportuar faqe, thjesht shtypni një emër për çdo rresht, ose krijoni lidhje të tipit <nowiki>[[Special:Export/Faqja Kryesore]]</nowiki> si [[Special:Export/Faqja Kryesore]].',
'exportcuronly'     => 'Përfshi vetëm versionin e fundit, jo të gjithë historinë',
'exportnohistory' => "'''Shënim:''' Eksportimi i historisë së faqes për shkaqe të rendimentit nuk është e mundshme.",
'export-submit'     => 'Eksporto',
'export-addcattext' => 'Shto faqe nga kategoria:',
'export-addcat'     => 'Shto',

# Namespace 8 related
'allmessages'               => 'Mesazhet e sistemit',
'allmessagesname'           => 'Emri',
'allmessagesdefault'        => 'Teksti i parazgjedhur',
'allmessagescurrent'        => 'Teksti i tanishëshm',
'allmessagestext'           => 'Kjo është një listë e të gjitha faqeve në hapësirën MediaWiki:',
'allmessagesnotsupportedUI' => 'Ndërfaqja gjuhësore e juaj, <b>$1</b>, nuk mbulohet nga Special:AllMessages në këto faqe.',
'allmessagesnotsupportedDB' => 'special:Allmessages not supported because wgUseDatabaseMessages is off.',
'allmessagesfilter'         => 'Veço me shprehje të rregullta:',
'allmessagesmodified'       => 'Trego vetëm të ndryshuarat',

# Thumbnails
'thumbnail-more'  => 'Zmadho',
'missingimage'    => '<b>Mungon figura</b><br /><i>$1</i>',
'filemissing'     => 'Mungon skeda',
'thumbnail_error' => 'Gabim gjatë krijimit të figurës përmbledhëse: $1',

# Special:Import
'import'                => 'Importoni faqe',
'importinterwiki'       => 'Import ndër-wiki',
'importtext'            => 'Ju lutem eksportoni këtë skedë nga burimi wiki duke përdorur mjetin Special:Export, ruajeni në diskun tuaj dhe ngarkojeni këtu.',
'importfailed'          => 'Importimi dështoi: $1',
'importnotext'          => 'Bosh ose pa tekst',
'importsuccess'         => 'Importim i sukseshëm!',
'importhistoryconflict' => 'Ekzistojnë versione historiku në konflikt (kjo faqe mund të jetë importuar më parë)',
'importnosources'       => 'Nuk ka asnjë burim importi të përcaktuar dhe ngarkimet historike të drejtpërdrejta janë ndaluar.',
'importnofile'          => 'Nuk u ngarkua asnjë skedë importi.',
'importuploaderror'     => 'Ngarkimi i skedës së importit dështoi. Ndoshta skeda kishte madhësi më të madhe se lejohet.',

# Import log
'importlogpage' => 'Regjistri i importeve',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Faqja juaj e përdoruesit',
'tooltip-pt-anonuserpage'         => 'Faqja e përdoruesve anonim nga kjo adresë IP',
'tooltip-pt-mytalk'               => 'Faqja juaj e diskutimeve',
'tooltip-pt-anontalk'             => 'Faqja e diskutimeve të përdoruesve anonim për këtë adresë IP',
'tooltip-pt-preferences'          => 'Parapëlqimet tuaja',
'tooltip-pt-watchlist'            => 'Lista e faqeve nën mbikqyrjen tuaj.',
'tooltip-pt-mycontris'            => 'Lista e kontributeve tuaja',
'tooltip-pt-login'                => 'Të hysh brenda nuk është e detyrueshme, por ka shumë përparësi.',
'tooltip-pt-anonlogin'            => 'Të hysh brenda nuk është e detyrueshme, por ka shumë përparësi.',
'tooltip-pt-logout'               => 'Dalje',
'tooltip-ca-talk'                 => 'Diskuto për përmbajtjen e faqes',
'tooltip-ca-edit'                 => "Ju mund ta redaktoni këtë faqe. Përdorni butonin >>Trego parapamjen<< para se t'i kryeni ndryshimet.",
'tooltip-ca-addsection'           => 'Fillo një temë të re diskutimi.',
'tooltip-ca-viewsource'           => 'Kjo faqe është e mbrojtur. Ju mundeni vetëm ta shikoni burimin e tekstit.',
'tooltip-ca-history'              => 'Versione të mëparshme të artikullit.',
'tooltip-ca-protect'              => 'Mbroje këtë faqe',
'tooltip-ca-delete'               => 'Grise këtë faqe',
'tooltip-ca-undelete'             => 'Faqja u restaurua',
'tooltip-ca-move'                 => 'Me anë të zhvendosjes mund ta ndryshoni titullin e artikullit',
'tooltip-ca-watch'                => 'Shtoje faqen në lisën e faqeve nën mbikqyrje',
'tooltip-ca-unwatch'              => 'Hiqe faqen nga lista e faqeve nën mbikqyrje.',
'tooltip-search'                  => 'Kërko në projekt',
'tooltip-p-logo'                  => 'Figura e Faqes Kryesore',
'tooltip-n-mainpage'              => 'Vizitoni Faqen kryesore',
'tooltip-n-portal'                => 'Mbi projektin, çka mund të bëni për të dhe ku gjenden faqet.',
'tooltip-n-currentevents'         => 'Informacion rreth ngjarjeve aktuale.',
'tooltip-n-recentchanges'         => 'Lista e ndryshimeve së fundmi në projekt',
'tooltip-n-randompage'            => 'Shikoni një artikull të rastit.',
'tooltip-n-help'                  => 'Vendi ku mund të gjeni ndihmë.',
'tooltip-n-sitesupport'           => 'Përkrahni projektin',
'tooltip-t-whatlinkshere'         => 'Lista e faqeve që lidhen tek kjo faqe',
'tooltip-t-recentchangeslinked'   => 'Lista e ndryshimeve të faqeve që lidhen tek kjo faqe',
'tooltip-feed-rss'                => 'Burimi ushqyes "RSS" për këtë faqe',
'tooltip-feed-atom'               => 'Burimi ushqyes "Atom" për këtë faqe',
'tooltip-t-contributions'         => 'Shiko listën e kontributeve për përdoruesin në fjalë',
'tooltip-t-emailuser'             => 'Dërgoni një email përdoruesit',
'tooltip-t-upload'                => 'Ngarkoni figura ose skeda të tjera',
'tooltip-t-specialpages'          => 'Lista e të gjitha faqeve speciale.',
'tooltip-ca-nstab-main'           => 'Shikoni përmbajtjen e atikullit.',
'tooltip-ca-nstab-user'           => 'Shikoni faqen e përdoruesit',
'tooltip-ca-nstab-media'          => 'Shikoni faqen e skedës',
'tooltip-ca-nstab-special'        => 'Kjo është një faqe speciale. Ju nuk mundeni ta redaktoni këtë faqe',
'tooltip-ca-nstab-project'        => 'Shikoni faqen e projektit',
'tooltip-ca-nstab-image'          => 'Shikoni faqen e figurës',
'tooltip-ca-nstab-mediawiki'      => 'Shikoni mesazhet e sistemit',
'tooltip-ca-nstab-template'       => 'Shikoni stampën',
'tooltip-ca-nstab-help'           => 'Shikoni faqet ndihmëse',
'tooltip-ca-nstab-category'       => 'Shikoni faqen e kategorisë',
'tooltip-minoredit'               => 'Shënoje këtë redaktim të vogël',
'tooltip-save'                    => 'Kryej ndryshimet',
'tooltip-preview'                 => 'Shiko parapamjen e ndryshimeve, përdoreni këtë para se të kryeni ndryshimet!',
'tooltip-compareselectedversions' => 'Shikoni krahasimin midis dy versioneve të zgjedhura të kësaj faqeje.',
'tooltip-watch'                   => 'Mbikqyre këtë faqe',

# Stylesheets
'monobook.css' => '/* redaktoni këtë faqe për të përshtatur pamjen Monobook për tëra faqet tuaja */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata nuk është i mundshëm për këtë server.',
'nocreativecommons' => 'Creative Commons RDF metadata nuk është i mundshëm për këtë server.',
'notacceptable'     => 'Wiki server nuk mundet ti përgatit të dhënat për klintin tuaj.',

# Attribution
'anonymous'        => 'Përdorues anonim të {{SITENAME}}',
'lastmodifiedatby' => 'Kjo faqe është redaktuar së fundit më $2, $1 nga $3.', # $1 date, $2 time, $3 user
'and'              => 'dhe',
'othercontribs'    => 'Bazuar në punën e: $1',
'others'           => 'të tjerë',
'siteusers'        => 'Përdoruesit $1 e {{SITENAME}}',
'creditspage'      => 'Statistika e faqes',
'nocredits'        => 'Për këtë faqe nuk ka informacione.',

# Spam protection
'spamprotectiontitle'    => 'Mbrojtje ndaj teksteve të padëshiruara',
'spamprotectiontext'     => 'Faqja që dëshironit të ruani është bllokuar nga filtri i teksteve të padëshiruara. Ka mundësi që kjo të ketë ndodhur për shkak të ndonjë lidhjeje të jashtme.',
'spamprotectionmatch'    => 'Teksti në vijim është cilësuar i padëshiruar nga softueri: $1',
'subcategorycount'       => 'Ndodhen $1 nën-kategori në këtë kategori.',
'categoryarticlecount'   => 'Ndodhen $1 artikuj në këtë kategori.',
'category-media-count'   => 'Ndodhen {{PLURAL:$1|një skedë|$1 skeda}} në këtë kategori.',
'listingcontinuesabbrev' => ' vazh.',
'spambot_username'       => 'MediaWiki spam-pastrues',
'spam_reverting'         => "U kthye tek versioni i fundit që s'ka lidhje tek $1",
'spam_blanking'          => 'U boshatis sepse të gjitha versionet kanë lidhje tek $1',

# Info page
'infosubtitle'   => 'Informacion për faqen',
'numedits'       => 'Numri i versioneve të artikullit: $1',
'numtalkedits'   => 'Numrii versioneve të diskutimit të artikullit: $1',
'numwatchers'    => 'Numri i mbikqyrësve: $1',
'numauthors'     => 'Numri i autorëve të artikullit: $1',
'numtalkauthors' => 'Numri i diskutuesve për artikullin: $1',

# Math options
'mw_math_png'    => 'Gjithmonë PNG',
'mw_math_simple' => 'HTML në qoftë se është e thjeshtë ose ndryshe PNG',
'mw_math_html'   => 'HTML në qoftë se është e mundur ose ndryshe PNG',
'mw_math_source' => 'Lëre si TeX (për shfletuesit tekst)',
'mw_math_modern' => 'E rekomanduar për shfletuesit modern',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'        => 'Shënoje si të patrulluar',
'markaspatrolledtext'        => 'Shënoje këtë artikull të patrulluar',
'markedaspatrolled'          => 'Shënoje të patrulluar',
'markedaspatrolledtext'      => 'Versioni i zgjedhur është shënuar i patrulluar.',
'rcpatroldisabled'           => 'Kontrollimi i ndryshimeve së fundmi është bllokuar',
'rcpatroldisabledtext'       => 'Kontrollimi i ndryshimeve së fundmi nuk është i mundshëm për momentin.',
'markedaspatrollederror'     => 'Nuk munda ta shënoj të patrulluar',
'markedaspatrollederrortext' => 'Duhet të përcaktoni versionin për tu shënuar i patrulluar.',

# Image deletion
'deletedrevision' => 'Gris versionin e vjetër $1.',

# Browsing diffs
'previousdiff' => '← Ndryshimi më para',
'nextdiff'     => 'Ndryshimi më pas →',

# Media information
'mediawarning' => "'''Kujdes''': Kjo skedë mund të ketë përmbajtje të dëmshme, duke e përdorur sistemi juaj mund të rrezikohet.<hr />",
'imagemaxsize' => 'Kufizo pamjen e figurave në faqet përshkruese në rezolucionin:',
'thumbsize'    => 'Madhësia fotove përmbledhëse:',

'newimages'    => 'Galeria e figurave të reja',
'showhidebots' => '($1 robotët)',
'noimages'     => "S'ka gjë për të parë.",

'passwordtooshort' => 'Fjalëkalimi juaj është shumë i shkurtër. Ai duhet të ketë së paku $1 shkronja.',

# Metadata
'metadata-help'     => 'Kjo skedë përmban hollësira të tjera të cilat mund të jenë shtuar nga kamera ose skaneri dixhital që është përdorur për ta krijuar. Në qoftë se skeda është ndryshuar nga gjendja origjinale, disa hollësira mund të mos pasqyrojnë skedën e tanishme.',
'metadata-expand'   => 'Tregoji detajet',
'metadata-collapse' => 'Fshehi detajet',

# EXIF tags
'exif-imagewidth'                => 'Gjerësia',
'exif-imagelength'               => 'Gjatësia',
'exif-bitspersample'             => 'Bit për komponent',
'exif-compression'               => 'Lloji i ngjeshjes',
'exif-photometricinterpretation' => 'Përbërja pixel',
'exif-orientation'               => 'Orientimi',
'exif-samplesperpixel'           => 'Numri i përbërësve',
'exif-ycbcrpositioning'          => 'Pozicioni Y dhe C',
'exif-xresolution'               => 'Rezolucioni horizontal',
'exif-yresolution'               => 'Rezolucioni vertikal',
'exif-rowsperstrip'              => 'Numri i rreshtave për shirit',
'exif-datetime'                  => 'Data dhe ora e ndryshimit të skedës',
'exif-imagedescription'          => 'Titulli i figurës',
'exif-make'                      => 'Prodhuesi i kamerës',
'exif-model'                     => 'Modeli i kamerës',
'exif-software'                  => 'Softueri i përdorur',
'exif-artist'                    => 'Autor',
'exif-copyright'                 => 'Mbajtësi i të drejtave të autorit',
'exif-exifversion'               => 'Versioni Exif-it',
'exif-colorspace'                => 'Hapësira e ngjyrave',
'exif-compressedbitsperpixel'    => 'Lloji i ngjeshjes së figurës',
'exif-pixelydimension'           => 'Gjerësia e vlefshme e figurës',
'exif-pixelxdimension'           => 'Valind image height',
'exif-makernote'                 => 'Shënimet e prodhuesit',
'exif-usercomment'               => 'Vërejtjet e përdoruesit',
'exif-relatedsoundfile'          => 'Skeda audio shoqëruese',
'exif-datetimeoriginal'          => 'Data dhe koha e prodhimit të të dhënave',
'exif-datetimedigitized'         => 'Data dhe ora e digjitalizimit',
'exif-exposuretime'              => 'Kohëzgjatja e ekspozimit',
'exif-fnumber'                   => 'Numri F',
'exif-shutterspeedvalue'         => 'Shpejtësia e mbyllësit',
'exif-aperturevalue'             => 'Apertura',
'exif-brightnessvalue'           => 'Ndriçimi',
'exif-subjectdistance'           => 'Largësia e subjektit',
'exif-lightsource'               => 'Burimi i dritës',
'exif-flash'                     => 'Blici',
'exif-focallength'               => 'Gjatësia e vatrës',
'exif-flashenergy'               => 'Energjia e blicit',
'exif-subjectlocation'           => 'Vendndodhja e subjektit',
'exif-filesource'                => 'Burimi i skedës',
'exif-contrast'                  => 'Kontrasti',
'exif-saturation'                => 'Mbushja',
'exif-sharpness'                 => 'Ashpërsia',
'exif-subjectdistancerange'      => 'Shtrirja e largësisë së subjektit',
'exif-gpslatituderef'            => 'Gjerësi veriore ose jugore',
'exif-gpslatitude'               => 'Gjerësia gjeografike',
'exif-gpslongituderef'           => 'Gjatësi lindore ose perëndimore',
'exif-gpslongitude'              => 'Gjatësia gjeografike',
'exif-gpsaltituderef'            => 'Lartësia orientuese',
'exif-gpsaltitude'               => 'Lartësia',
'exif-gpssatellites'             => 'Janë përdorur satelitë për matjen',
'exif-gpstrack'                  => 'Drejtimi i lëvizjes',
'exif-gpsimgdirection'           => 'Orientimi i figurës',

# EXIF attributes
'exif-compression-1' => 'E pangjeshur',

'exif-orientation-1' => 'Normale', # 0th row: top; 0th column: left
'exif-orientation-2' => 'E kthyer horizontalisht', # 0th row: top; 0th column: right
'exif-orientation-3' => 'E rrotulluar 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'E kthyer vertikalisht', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'E rrotulluar 90° kundër orës dhe e kthyer vertikalisht', # 0th row: left; 0th column: top
'exif-orientation-6' => 'E rrotulluar 90° sipas orës', # 0th row: right; 0th column: top
'exif-orientation-7' => 'E rrotulluar 90° sipas orës dhe e kthyer vertikalisht', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'E rrotulluar 90° kundër orës', # 0th row: left; 0th column: bottom

'exif-componentsconfiguration-0' => 'nuk ekziston',

'exif-exposureprogram-4' => 'Përparësia e mbyllësit',

'exif-subjectdistance-value' => '$1 metra',

'exif-meteringmode-0'   => 'E panjohur',
'exif-meteringmode-1'   => 'Mesatare',
'exif-meteringmode-6'   => 'E pjesshme',
'exif-meteringmode-255' => 'Tjetër',

'exif-lightsource-0'   => 'I panjohur',
'exif-lightsource-1'   => 'Ditë',
'exif-lightsource-4'   => 'Blic',
'exif-lightsource-9'   => 'Kohë e hapur',
'exif-lightsource-10'  => 'Kohë e vrenjtur',
'exif-lightsource-11'  => 'Hije',
'exif-lightsource-255' => 'Tjetër burim drite',

'exif-scenecapturetype-1' => 'Peizazh',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Pamje nate',

'exif-contrast-0' => 'Normale',
'exif-contrast-1' => 'I dobët',
'exif-contrast-2' => 'I fortë',

'exif-saturation-0' => 'Normale',
'exif-saturation-1' => 'mbushje e pakët',
'exif-saturation-2' => 'mbushje e shumtë',

'exif-sharpness-0' => 'Normale',
'exif-sharpness-1' => 'E butë',
'exif-sharpness-2' => 'E fortë',

'exif-subjectdistancerange-0' => 'E panjohur',
'exif-subjectdistancerange-2' => 'Pamje nga afër',
'exif-subjectdistancerange-3' => 'Pamje nga larg',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Gjerësi veriore',
'exif-gpslatitude-s' => 'Gjerësi jugore',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Gjatësi lindore',
'exif-gpslongitude-w' => 'Gjatësi perëndimore',

'exif-gpsstatus-a' => 'Duke bërë matje',

'exif-gpsmeasuremode-2' => 'matje në 2 madhësi',
'exif-gpsmeasuremode-3' => 'matje në 3 madhësi',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometra në orë',
'exif-gpsspeed-m' => 'Milje në orë',
'exif-gpsspeed-n' => 'Nyje',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Drejtimi i vërtetë',
'exif-gpsdirection-m' => 'Drejtimi magnetik',

# External editor support
'edit-externally'      => 'Ndryshoni këtë skedë me një mjet të jashtëm',
'edit-externally-help' => 'Shikoni [http://meta.wikimedia.org/wiki/Help:External_editors udhëzimet e instalimit] për më shumë informacion.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'të gjitha',
'imagelistall'     => 'të gjitha',
'watchlistall1'    => 'të gjitha',
'watchlistall2'    => 'të gjitha',
'namespacesall'    => 'të gjitha',

# E-mail address confirmation
'confirmemail'            => 'Vërtetoni adresën tuaj',
'confirmemail_noemail'    => 'Ju nuk keni dhënë email të sakt te [[Special:Preferences|parapëlqimet e juaja]].',
'confirmemail_text'       => 'Për të marrë email duhet të vërtetoni adresen tuaj. Shtypni butonin e mëposhtëm për të dërguar një email vërtetimi tek adresa juaj. Email-i do të përmbajë një lidhje me kod të shifruar. Duke ndjekur lidhjen nëpërmjet shfletuesit tuaj do të vërtetoni adresën.',
'confirmemail_send'       => 'Dërgo vërtetimin',
'confirmemail_sent'       => 'Email-i për vërtetim është dërguar.',
'confirmemail_sendfailed' => 'Nuk munda të dërgoj email-in e vërtetimit. Kontrolloni adresën tuaj për gabime shtypi.',
'confirmemail_invalid'    => 'Kodi i shifrimit të vërtetimit është gabim ose ka skaduar.',
'confirmemail_needlogin'  => 'Ju duhet të $1 për ta konfirmuar email-adresën',
'confirmemail_success'    => 'Adresa juaj është vërtetuar. Mund të hyni brënda dhe të përdorni wiki-n.',
'confirmemail_loggedin'   => 'Adresa juaj është vërtetuar.',
'confirmemail_error'      => 'Pati gabim gjatë ruajtjes së vërtetimit tuaj.',
'confirmemail_subject'    => 'Vërtetim adrese nga {{SITENAME}}',
'confirmemail_body'       => 'Dikush, me siguri ju nga IP adresa $1, ka hapur llogarinë "$2" tek {{SITENAME}} dhe ka dhënë këtë adresë email-i.

Në qoftë se është me të vertetë llogaria juaj, vërtetoni këtë adresë duke ndjekur lidhjen e mëposhtme për të mundësuar përdorimin e mjeteve që kërkojnë email tek {{SITENAME}}:

$3

Në qoftë se nuk është llogaria juaj atëhere mos e ndiqni lidhjen. Kodi i shifruar do të skadojë më $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Kërko përputhje të plotë',
'searchfulltext' => 'Kërko tekstin e plotë',
'createarticle'  => 'Krijo artikull',

# Scary transclusion
'scarytranscludedisabled' => '[Lidhja Interwiki nuk është i mundshëm]',
'scarytranscludefailed'   => '[ju kërkoj ndjesë, marrja e stampës $1 dështoi]',
'scarytranscludetoolong'  => '[ju kërkoj ndjesë, URL-i është tepër i gjatë]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Lidhje ndjekëse për këtë artikull:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 hiqe])',
'trackbacklink'     => 'Lidhje ndjekëse',
'trackbackdeleteok' => 'Lidhja ndjekëse u hoq.',

# Delete conflict
'deletedwhileediting' => 'Kujdes! Kjo faqe është grisur pasi ju keni filluar redaktimin!',
'confirmrecreate'     => "Përdoruesi [[User:$1|$1]] ([[User talk:$1|diskutime]]) grisi këtë artikull mbasi ju filluat ta redaktoni për arsyen:
: ''$2''
Ju lutem konfirmoni nëse dëshironi me të vertetë ta ri-krijoni këtë artikull.",
'recreate'            => 'Rikrijo',

# HTML dump
'redirectingto' => 'Përcjellin tek [[$1]]...',

# action=purge
'confirm_purge'        => 'Pastro cache për këtë faqe?

$1',
'confirm_purge_button' => 'Shko',

'youhavenewmessagesmulti' => 'Ju keni mesazh të ri në $1',

'searchcontaining' => "Kërko për artikuj që përmbajnë ''$1''.",
'searchnamed'      => "Kërko për artikuj të quajtur ''$1''.",
'articletitles'    => "Artikuj që fillojnë me ''$1''",
'hideresults'      => 'Fshih rezultatet',

# DISPLAYTITLE
'displaytitle' => '(Lidhje te kjo faqe si [[$1]])',

'loginlanguagelabel' => 'Gjuha: $1',

# Table pager
'table_pager_next'         => 'Faqja më pas',
'table_pager_prev'         => 'Faqja më parë',
'table_pager_first'        => 'Faqja e parë',
'table_pager_last'         => 'Faqja e fundit',
'table_pager_limit'        => 'Trego $1 rreshta për faqe',
'table_pager_limit_submit' => 'Shko',
'table_pager_empty'        => 'Asnjë rezultat',

# Auto-summaries
'autosumm-blank'   => 'U largua krejt përmbajtja e artikullit',
'autosumm-replace' => "Faqja u zëvendësua me '$1'",
'autoredircomment' => 'Përcjellim te [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Faqe e re: $1',

);

?>
