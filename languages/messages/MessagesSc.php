<?php
/** Sardinian (Sardu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Andria
 * @author Marzedu
 * @author Node ue
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Ispetziale',
	NS_TALK             => 'Cuntierra',
	NS_USER             => 'Usuàriu',
	NS_USER_TALK        => 'Cuntierra_usuàriu',
	NS_PROJECT_TALK     => 'Cuntierra_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Cuntierra_file',
	NS_MEDIAWIKI_TALK   => 'Cuntierra_MediaWiki',
	NS_TEMPLATE_TALK    => 'Cuntierra_template',
	NS_HELP             => 'Agiudu',
	NS_HELP_TALK        => 'Cuntierra_agiudu',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Cuntierra_categoria',
);

$namespaceAliases = array(
	'Speciale'            => NS_SPECIAL,
	'Contièndha'          => NS_TALK,
	'Utente'              => NS_USER,
	'Utente_discussioni'  => NS_USER_TALK,
	'$1_discussioni'      => NS_PROJECT_TALK,
	'Immàgini'            => NS_FILE,
	'Immàgini_contièndha' => NS_FILE_TALK
);

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

$linkTrail = "/^([a-z]+)(.*)$/sD";

$messages = array(
# User preference toggles
'tog-underline'               => 'Sutalìnea is cullegamentos',
'tog-highlightbroken'         => 'Evidèntzia <a href="" class="new">de aici</a> is cullegamentos a pàginas inesistentes (si disativadu: de aici<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Alliniamentu paràgrafos giustificados',
'tog-hideminor'               => 'Cua is acontzos minores in sa pàgina de is ùrtimas mudàntzias',
'tog-hidepatrolled'           => 'Cua is acontzos verificados in is ùrtimas mudàntzias',
'tog-newpageshidepatrolled'   => 'Cua is pàginas verificadas dae sa lista de is pàginas noas',
'tog-extendwatchlist'         => 'Ammània sa watchlist pro ammustrare totu is mudàntzias, non feti is prus reghentes',
'tog-usenewrc'                => 'Imprea is ùrtimas mudàntzias megioradas (esigit JavaScript)',
'tog-numberheadings'          => 'Auto-numeratzione de is tìtulos',
'tog-showtoolbar'             => "Ammustra s'amusta de is ainas pro is acontzos (esigit JavaScript)",
'tog-editondblclick'          => 'Acontza pàginas cun dòpiu click (esigit JavaScript)',
'tog-editsection'             => 'Acontza setziones dae su butone [acontza]',
'tog-editsectiononrightclick' => "Abilita s'acontzu de is setziones cun dòpiu click in is tìtulos de is setziones (esigit JavaScript)",
'tog-showtoc'                 => "Ammustra s'ìndixe de is cuntènnidos (pro pàginas cun prus de 3 setziones)",
'tog-rememberpassword'        => 'Ammenta sa sessione in custu navigadore (pro unu màssimu de $1 {{PLURAL:$1|die|dies}})',
'tog-watchcreations'          => 'Aciungi is pàginas chi apo creadu a sa watchlist mea',
'tog-watchdefault'            => 'Aciungi is pàginas chi apo acontzadu a sa watchlist mea',
'tog-watchmoves'              => 'Aciungi is pàginas chi apo mòvidu a sa watchlist mea',
'tog-watchdeletion'           => 'Aciungi is pàginas chi apo fuliadu a sa watchlist mea',
'tog-minordefault'            => 'Signa totu is acontzos comente minores pro difetu',
'tog-previewontop'            => "Ammustra s'antiprima a subra sa casella de acontzu e no a suta",
'tog-previewonfirst'          => "Ammustra s'antiprima pro su primu acontzu",
'tog-nocache'                 => "Disativa sa ''cache'' pro is pàginas de su ''browser''",
'tog-enotifwatchlistpages'    => 'Spedi·mi una missada eletrònica cando una pàgina de sa watchlist mea est acontzada',
'tog-enotifusertalkpages'     => 'Spedi·mi una missada eletrònica cando sa pàgina de is cuntierras mias est acontzada',
'tog-enotifminoredits'        => 'Spedi·mi una missada eletrònica fintzas pro is acontzos minores de is pàginas',
'tog-enotifrevealaddr'        => "Faghe schire s'indiritzu e-mail miu in is notìficas de is e-mails",
'tog-shownumberswatching'     => 'Ammustra su nùmeru de is usuàrios ca sunt ponende ogru a sa pàgina',
'tog-oldsig'                  => 'Antiprima de sa firma atuale:',
'tog-fancysig'                => 'Trata sa firma comente unu testu wiki (chentza cullegamentos automaticos)',
'tog-uselivepreview'          => 'Imprea sa funtzione "live preview" (esigit JavaScript) (sperimentale)',
'tog-watchlisthideown'        => 'Cua is acontzos meos dae sa watclist',
'tog-watchlisthidebots'       => 'Cua is acontzos de is bots dae sa watchlist',
'tog-watchlisthideminor'      => 'Cua is acontzos minores dae sa watchlist',
'tog-watchlisthideliu'        => 'Cua is acontzos de is usuàrios intraus dae sa watchlist',
'tog-watchlisthideanons'      => 'Cua is acontzos de is usuàrios anonimus dae sa watchlist',
'tog-watchlisthidepatrolled'  => 'Cua acontzos verificados dae sa watchlist',
'tog-ccmeonemails'            => 'Spedi·mi is còpias de is e-mails ca spedu a is àteros usuàrios',
'tog-showhiddencats'          => 'Ammustra is categorias cuadas',

'underline-always'  => 'Semper',
'underline-never'   => 'Mai',
'underline-default' => 'Definiduras dae su browser tuo',

# Font style option in Special:Preferences
'editfont-style'     => "Stile lìteras in s'àrea de acontzu:",
'editfont-default'   => 'Definidu dae su browser',
'editfont-monospace' => 'Font monospàtziu',
'editfont-sansserif' => 'Font sans-serif',
'editfont-serif'     => 'Font serif',

# Dates
'sunday'        => 'Domìnigu',
'monday'        => 'Lunis',
'tuesday'       => 'Martis',
'wednesday'     => 'Mèrcuris',
'thursday'      => 'Giòvia',
'friday'        => 'Chenàbura',
'saturday'      => 'Sàbadu',
'sun'           => 'Dom',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mèr',
'thu'           => 'Giò',
'fri'           => 'Che',
'sat'           => 'Sàb',
'january'       => 'Ghennàrgiu',
'february'      => 'Freàrgiu',
'march'         => 'Martzu',
'april'         => 'Abrile',
'may_long'      => 'Maju',
'june'          => 'Làmpadas',
'july'          => 'Trìulas',
'august'        => 'Austu',
'september'     => 'Cabudanni',
'october'       => 'Santugaine',
'november'      => 'Santandria',
'december'      => 'Nadale',
'january-gen'   => 'Ghennàrgiu',
'february-gen'  => 'Freàrgiu',
'march-gen'     => 'Martzu',
'april-gen'     => 'Abrile',
'may-gen'       => 'Maju',
'june-gen'      => 'Làmpadas',
'july-gen'      => 'Trìulas',
'august-gen'    => 'Austu',
'september-gen' => 'Cabudanni',
'october-gen'   => 'Santugaine',
'november-gen'  => 'Santandria',
'december-gen'  => 'Nadale',
'jan'           => 'Ghe',
'feb'           => 'Fre',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'Maj',
'jun'           => 'Làm',
'jul'           => 'Trì',
'aug'           => 'Aus',
'sep'           => 'Cab',
'oct'           => 'Stg',
'nov'           => 'Std',
'dec'           => 'Nad',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'                => 'Pàginas in sa categoria "$1"',
'subcategories'                  => 'Subcategorias',
'category-media-header'          => 'Mèdios in sa categoria "$1"',
'category-empty'                 => "''In custa categoria non bi est peruna pàgina o mèdiu.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria cuada|Categorias cuadas}}',
'hidden-category-category'       => 'Categorias cuadas',
'category-subcat-count'          => "{{PLURAL:$2|Custa categoria cuntenet un'ùnica subcategoria ammustrada a suta.|Custa categoria cuntenet {{PLURAL:$1|sa subcategoria indicada|$1 subcategorias indicadas}} a suta, de $2 totales.}}",
'category-subcat-count-limited'  => 'Custa categoria tenet {{PLURAL:$1|una subcategoria, ammustrada|$1 subcategorias, ammustradas}} a suta.',
'category-article-count'         => '{{PLURAL:$2|Custa categoria cuntènnit isceti sa pàgina chi sighit.|Custa categoria cuntènnit {{PLURAL:$1|sa pàgina indicada|is $1 pàginas indicadas}} a suta, dae unu totale de $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Sa pàgina ki sighit est|Is $1 pàginas ki sighint sunt}} in custa categoria.',
'category-file-count'            => '{{PLURAL:$2|Custa categoria cuntenet feti su file ki sighit.|{{PLURAL:$1|Su file ki sighit est|Is $1 files ki sighint sunt}} in custa categoria, dae $2 totales.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Su file ki sighit est|Is $1 files ki sighint sunt}} in sa categoria currente.',
'listingcontinuesabbrev'         => 'sighit',
'index-category'                 => 'Pàginas indicizadas',
'noindex-category'               => 'Pàginas no indicitzadas',

'linkprefix'   => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext' => "'''MediaWiki est stadu installadu in modu currègidu.'''",

'about'         => 'A propòsitu de',
'article'       => 'Artìculu',
'newwindow'     => '(aberit in una bentana noa)',
'cancel'        => 'Burra',
'moredotdotdot' => 'Àteru…',
'mypage'        => 'Sa pàgina mea',
'mytalk'        => 'Cuntierras meas',
'anontalk'      => 'Cuntierras pro custu IP',
'navigation'    => 'Navigadura',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Busca',
'qbbrowse'       => 'Nàviga',
'qbedit'         => 'Acontza',
'qbpageoptions'  => 'Possibilidades de sa pàgina',
'qbpageinfo'     => 'Cuntestu de sa pàgina',
'qbmyoptions'    => 'Is preferèntzias meas',
'qbspecialpages' => 'Pàginas spetziales',
'faq'            => 'Pregontas/Respostas (FAQ)',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Giunghe cuntierra',
'vector-action-delete'     => 'Cantzella',
'vector-action-move'       => 'Move',
'vector-action-protect'    => 'Ampara',
'vector-action-undelete'   => 'Recùpera',
'vector-view-create'       => 'Crea',
'vector-view-edit'         => 'Acontza',
'vector-view-history'      => 'Càstia istòria',
'vector-view-view'         => 'Leghe',
'vector-view-viewsource'   => 'Càstia mitza',
'actions'                  => 'Atziones',
'namespaces'               => 'Nùmene-logos',
'variants'                 => 'Variantes',

'errorpagetitle'    => 'Faddina',
'returnto'          => 'Torra a $1.',
'tagline'           => 'Dae {{SITENAME}}',
'help'              => 'Agiudu',
'search'            => 'Chirca',
'searchbutton'      => 'Chirca',
'go'                => 'Bae',
'searcharticle'     => 'Bae',
'history'           => 'Istòria de sa pàgina',
'history_short'     => 'Istòria',
'updatedmarker'     => "agiornada dae s'ùrtima bìsita mia",
'info_short'        => 'Informatziones',
'printableversion'  => 'Versione de imprenta',
'permalink'         => 'Acàpiu fitianu',
'print'             => 'Imprenta',
'edit'              => 'Acontza',
'create'            => 'Crea',
'editthispage'      => 'Acontza custa pàgina',
'create-this-page'  => 'Crea custa pàgina',
'delete'            => 'Fùlia',
'deletethispage'    => 'Fùlia custa pàgina',
'undelete_short'    => 'Restaurare {{PLURAL:$1|un acontzu|$1 acontzos}}',
'protect'           => 'Ampara',
'protect_change'    => 'mudàntzia',
'protectthispage'   => 'Ampara custa pàgina',
'unprotect'         => 'Disampara',
'unprotectthispage' => 'Disampara custa pàgina',
'newpage'           => 'Pàgina noa',
'talkpage'          => 'Pàgina de cuntierra',
'talkpagelinktext'  => 'Cuntierra',
'specialpage'       => 'Pàgina Ispetziale',
'personaltools'     => 'Ainas personales',
'postcomment'       => 'Setzione noa',
'articlepage'       => "Càstia s'artìculu",
'talk'              => 'Cuntierras',
'views'             => 'Bisuras',
'toolbox'           => 'Ainas',
'userpage'          => 'Càstia sa pàgina usuàriu',
'projectpage'       => 'Càstia sa pàgina meta',
'imagepage'         => 'Càstia sa pàgina de su file',
'mediawikipage'     => 'Càstia su messàgiu',
'templatepage'      => 'Càstia su template',
'viewhelppage'      => 'Càstia sa pàgina de agiudu',
'categorypage'      => 'Càstia sa categoria',
'viewtalkpage'      => 'Càstia cuntierras',
'otherlanguages'    => 'Àteras limbas',
'redirectedfrom'    => '(Reindiritzadu dae $1)',
'redirectpagesub'   => 'Pàgina de reindiritzadura',
'lastmodifiedat'    => 'Ùrtimu acontzu su $1, a is $2.',
'viewcount'         => 'Custu artìculu est stadu lìgiu {{PLURAL:$1|borta|$1 bortas}}.',
'protectedpage'     => 'Pàgina amparada',
'jumpto'            => 'Bae a:',
'jumptonavigation'  => 'navigadura',
'jumptosearch'      => 'chirca',
'pool-errorunknown' => 'Faddina disconnota',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A propòsitu de {{SITENAME}}',
'aboutpage'            => 'Project:Informatziones',
'copyright'            => 'Cuntènnidu a suta licèntzia $1.',
'copyrightpage'        => '{{ns:project}}:Copyrights',
'currentevents'        => 'Noas',
'currentevents-url'    => 'Project:Noas',
'disclaimers'          => 'Abbertimentos',
'disclaimerpage'       => 'Project:Abbertimentos generales',
'edithelp'             => "Agiudu pro s'acontzu o sa scritura",
'edithelppage'         => 'Help:Acontzare',
'helppage'             => 'Help:Agiudu',
'mainpage'             => 'Pàgina Base',
'mainpage-description' => 'Pàgina Base',
'policy-url'           => 'Project:Polìticas',
'portal'               => 'Portale comunidade',
'privacy'              => 'Polìtica pro is datos privados',
'privacypage'          => 'Project:Polìtica pro is datos privados',

'badaccess' => 'Permissu non bastante',

'ok'                      => 'OK',
'pagetitle'               => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Bogau dae  "$1"',
'youhavenewmessages'      => 'Tenes $1 ($2).',
'newmessageslink'         => 'messàgios noos',
'newmessagesdifflink'     => 'ùrtima mudàntzia',
'youhavenewmessagesmulti' => 'Tenes messàgios noos in $1',
'editsection'             => 'acontza',
'editsection-brackets'    => '[$1]',
'editold'                 => 'acontza',
'viewsourceold'           => 'càstia mitza',
'editlink'                => 'acontza',
'viewsourcelink'          => 'càstia mitza',
'editsectionhint'         => 'Acontza sa setzione: $1',
'toc'                     => 'Cuntènnidu',
'showtoc'                 => 'ammustra',
'hidetoc'                 => 'cua',
'thisisdeleted'           => 'Càstiare o recuperare $1?',
'viewdeleted'             => 'Bisi $1?',
'restorelink'             => '{{PLURAL:$1|unu acontzu burradu|$1 acontzos burrados}}',
'feedlinks'               => 'Feed:',
'site-rss-feed'           => 'Feed Atom de $1',
'site-atom-feed'          => 'Feed Atom de $1',
'page-rss-feed'           => 'Feed RSS pro "$1"',
'page-atom-feed'          => 'Feed Atom pro "$1"',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (sa pàgina no esistit)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pàgina',
'nstab-user'      => 'Pàgina usuàriu',
'nstab-media'     => 'File multimediale',
'nstab-special'   => 'Pàgina ispetziale',
'nstab-project'   => 'Pàgina de servìtziu',
'nstab-image'     => 'File',
'nstab-mediawiki' => 'Messàgiu',
'nstab-template'  => 'Template',
'nstab-help'      => 'Agiudu',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'No esistit tale atzione',
'nosuchactiontext'  => 'S\'atzione spetzificada in sa URL no est vàlida.
Est possìbile ki sa URL siat stada carcada male, o si siat sighidu unu cullegamentu non vàlidu.
Custu diat a pòder èsser unu "bug" de {{SITENAME}}.',
'nosuchspecialpage' => 'Custa pàgina ispetziale no esistit',
'nospecialpagetext' => "<strong>As pediu una pàgina ispetziale non balida.</strong>

Una lista de pàginas ispetziales bàlidas d'agatas in [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'               => 'Faddina',
'databaseerror'       => 'Faddina de su database',
'dberrortext'         => 'Faddina de sintassi in sa pregunta fata a su database.
Custu podet indicare unu sbàlliu de su software.
S\'ùrtima consulta imbiada a su database est istada:
<blockquote><tt>$1</tt></blockquote>
aintru de sa funtzione "<tt>$2</tt>".
Su database at torradu custa faddina "<tt>$3: $4</tt>".',
'readonly'            => 'Database bloccadu',
'enterlockreason'     => 'Inserta su motivu de su bloccu, ispetzifichende su momentu probabile chi su bloccu at a acabai',
'readonlytext'        => "In custu momentu su database est bloccadu dae aciunturas e àteras modificas, probabilmente pro ordinaria manutentzione a su database, a pustis de custas at a èssere normale torra.

S'aministradore chi dd'at bloccadu at donadu custa ispiegatzione: $1",
'missing-article'     => 'Su database no at agatadu su testu de una pàgina ki diat àer agatadu a suta de su nùmene "$1" $2.

Custu a su sòlitu si verìficat cando bi est unu ligàmine in sa stòria o in unu cunfrontu intre revisiones de una pàgina ki est stada fuliada.

Si no est custu su casu, s\'est agatada una faddina de su software.
Pro praxere signa s\'acuntèssidu a unu [[Special:ListUsers/sysop|amministradore]] spetzifichende su URL de sa faddina.',
'missingarticle-rev'  => '(revisione nùmeru: $1)',
'missingarticle-diff' => '(Dif: $1, $2)',
'internalerror'       => 'Faddina interna',
'internalerror_info'  => 'Faddina interna: $1',
'filecopyerror'       => 'No est stadu possìbile copiare su file "$1" comente "$2".',
'filerenameerror'     => 'No est stadu possìbile re-numenare su file "$1" in "$2".',
'filedeleteerror'     => 'No est stadu possìbile cantzellare su file "$1".',
'filenotfound'        => 'No est stadu possìbile agatare "$1".',
'unexpected'          => 'Valore non previstu: "$1"="$2".',
'formerror'           => 'Errore: impossìbile imbiare su modellu',
'badarticleerror'     => 'Operatzione non cunsentida pro custa pàgina.',
'cannotdelete'        => 'No est stadu possìbile burrare sa pàgina o su file "$1".
Podet èsser stadu burradu dae calicunu àteru.',
'badtitle'            => 'Tìtulu malu',
'badtitletext'        => "Su tìtulu de sa pàgina ch'as pediu est bùidu, isballiau, o iscritu in is cullegamentus inter-wiki in manera non currègia o cun caràteres no amìtius.",
'viewsource'          => 'Càstia mitza',
'viewsourcefor'       => 'pro $1',
'actionthrottled'     => 'Atzione rimandada',
'sqlhidden'           => '(Consulta SQL cuada)',
'namespaceprotected'  => "Non tenes su permissu de acontzare is pàginas in su nùmene-lugu '''$1'''.",
'ns-specialprotected' => 'Is pàginas ispetziales non podent èssere acontzadas.',

# Virus scanner
'virus-scanfailed'     => 'scansione faddida (còdixe $1)',
'virus-unknownscanner' => 'antivirus disconnotu:',

# Login and logout pages
'logouttext'              => "'''As acabadu sa sessione.'''

Immoe podes sighire a impreare {{SITENAME}} in forma anònima, o ti podes [[Special:UserLogin|identificare torra]] comente su de prima o comente usuàriu diferente.
Tene contu ca is pàginas ki sunt giai abertas in àteras bentanas podent sighire a pàrrer comente cando fias identificadu, fintzas a cando non ddas renfriscas.",
'welcomecreation'         => "== Benènnidu, $1! ==
S'account tuo est istadu creadu.
No iscaressa de personalizare sas [[Special:Preferences|preferèntzias de {{SITENAME}}]].",
'yourname'                => 'Nùmene usuàriu',
'yourpassword'            => 'Password:',
'yourpasswordagain'       => 'Repite sa password:',
'remembermypassword'      => 'Ammenta sa password in custu carculadore (pro unu màssimu de $1 {{PLURAL:$1|die|dies}})',
'yourdomainname'          => 'Spetzificare su domìniu',
'login'                   => 'Intra',
'nav-login-createaccount' => 'Intra / crea account',
'loginprompt'             => "Est netzessàriu abilitare is ''cookies'' pro si registrare in {{SITENAME}}.",
'userlogin'               => 'Intra / crea account',
'userloginnocreate'       => 'Intra',
'logout'                  => 'Serra sessione',
'userlogout'              => 'Essida',
'notloggedin'             => 'Non ses intradu',
'nologin'                 => "Non tenes unu account? '''$1'''.",
'nologinlink'             => 'Crea unu account',
'createaccount'           => 'Crea account',
'gotaccount'              => 'Tenes giai unu account? $1.',
'gotaccountlink'          => 'Identifica·ti',
'createaccountmail'       => 'via e-mail',
'createaccountreason'     => 'Motivu:',
'badretype'               => 'Sas passwords chi as insertau non currenspundint.',
'userexists'              => 'Su nùmene usuàriu insertadu est giai impreadu.
Sèbera unu nùmene diferente.',
'loginerror'              => 'Faddina de identificatzione',
'noname'                  => 'Su nùmene usuàriu insertadu no est vàlidu.',
'loginsuccesstitle'       => 'Ti ses identificadu',
'loginsuccess'            => "'''Immoe ses intradu in {{SITENAME}} cun su nùmene usuàriu \"\$1\".'''",
'nosuchuser'              => 'Non bi est usuàriu cun su nùmene "$1".
Is nùmenes usuàriu sunt sensìbiles a is lìteras mannas.
Verìfica su nùmene insertadu o [[Special:UserLogin/signup|crea unu account nou]].',
'nouserspecified'         => 'Depes spetzificare unu nùmene usuàriu.',
'wrongpassword'           => 'Sa password insertada no est bona. Prova torra.',
'wrongpasswordempty'      => 'No as scritu sa password.
Prova torra.',
'passwordtooshort'        => 'Is passwords depent tènner a su mancu {{PLURAL:$1|1 caràtere|$1 caràteres}}.',
'password-name-match'     => 'Sa password tua depet èsser diferente dae su nùmene usuàriu tuo.',
'mailmypassword'          => "Ispedi una password noa a s'indiritzu e-mail miu",
'passwordremindertitle'   => 'Servitziu Password Reminder di {{SITENAME}}',
'passwordremindertext'    => 'Calicunu (probabilmenti tue, cun s\'indiritzu IP $1) at pediu de arritziri una password noa pro intrare a {{SITENAME}} ($4).
Una password temporanea pro s\'usuàriu "$2" est istada impostada a "$3".
Chi custu fiat ne is intentziones tuas, depis intrare (log in) e scioberari una password noa.
Sa password temporanea tua at a iscadiri in {{PLURAL:$5|una die|$5 dies}}.

Chi non ses istadu a pediri sa password, o chi as torrau a agatare sa password torra e non da depis cambiari prus, non cunsideras custu messagiu e sighi a impreare sa password beccia.',
'noemail'                 => 'Peruna e-mail resurtat registrada pro s\'usuàriu "$1".',
'passwordsent'            => 'Una password noa est stada mandada a s\'indiritzu e-mail de s\'usuàriu "$1".
Pro praxere, cando dda retzis identìfica·ti torra.',
'mailerror'               => 'Faddina imbiende su messàgiu: $1',
'emailauthenticated'      => "S'indiritzu e-mail tuo est istadu autenticau su $2 a is $3.",
'emailconfirmlink'        => "Cunfirma s'indiritzu e-mail tuo",
'accountcreated'          => 'Account creadu',
'accountcreatedtext'      => "S'account usuàriu pro $1 est stadu creadu.",
'createaccount-title'     => 'Creatzione de unu account pro {{SITENAME}}',
'loginlanguagelabel'      => 'Limba: $1',

# JavaScript password checks
'password-strength-bad'        => 'MALA',
'password-strength-mediocre'   => 'discreta',
'password-strength-acceptable' => 'atzetàbile',
'password-strength-good'       => 'bona',
'password-retype'              => 'Repite sa password:',
'password-retype-mismatch'     => 'Sas passwords non cointzident',

# Password reset dialog
'resetpass'                 => 'Càmbia sa password',
'resetpass_header'          => 'Càmbia sa password de su account',
'oldpassword'               => 'Password betza:',
'newpassword'               => 'Password noa:',
'retypenew'                 => 'Re-scrie sa password noa:',
'resetpass_submit'          => 'Càmbia sa password e identifica·ti',
'resetpass_forbidden'       => 'No est possìbile cambiare is passwords',
'resetpass-no-info'         => 'Depes èsser identificadu pro abèrrer custa pàgina deretu.',
'resetpass-submit-loggedin' => 'Càmbia password',
'resetpass-submit-cancel'   => 'Burra',
'resetpass-temp-password'   => 'Password temporànea:',

# Edit page toolbar
'bold_sample'     => 'Grassu',
'bold_tip'        => 'Grassu',
'italic_sample'   => 'Corsivu',
'italic_tip'      => 'Corsivu',
'link_sample'     => 'Tìtulu cullegamentu',
'link_tip'        => 'Cullegamentu internu',
'extlink_sample'  => "http://www.example.com tìtulu de s'acàpiu",
'extlink_tip'     => 'Acàpiu a foras (ammenta su prefissu http://)',
'headline_sample' => 'Testu de su tìtulu',
'headline_tip'    => 'Tìtulu de su de duos livellu',
'math_sample'     => 'Inserta sa fòrmula inoghe',
'math_tip'        => 'Fòrmula matemàtica (LaTeX)',
'nowiki_sample'   => 'Inserta su testu non-formatadu inoghe',
'nowiki_tip'      => 'Ignora sa formatatzione wiki',
'image_sample'    => 'Esèmpiu.jpg',
'image_tip'       => 'Incòrpora una pintura',
'media_sample'    => 'Esèmpiu.ogg',
'media_tip'       => 'Cullegamentu a unu file',
'sig_tip'         => 'Firma cun data e ora',
'hr_tip'          => 'Lìnia orizontale (de usare cun critèriu)',

# Edit pages
'summary'                          => 'Ogetu:',
'subject'                          => 'Tema/tìtulu:',
'minoredit'                        => "Custu est un'acontzu minore:",
'watchthis'                        => 'Pone custa pàgina in sa watchlist mea',
'savearticle'                      => 'Sarva sa pàgina',
'preview'                          => 'Antiprima',
'showpreview'                      => "Ammustra s'antiprima",
'showlivepreview'                  => "Funtzione ''Live preview''",
'showdiff'                         => 'Ammustra is mudàntzias',
'anoneditwarning'                  => "'''Atentzione:''' Non ses identificadu (log in).
S'indiritzu IP tuo at a èsser registradu in sa stòria de custa pàgina.",
'anonpreviewwarning'               => "''Non ses identificadu. Sarbende s'indiritzu IP tuo at a èsser registradu in sa stòria de sa pàgina.''",
'missingcommenttext'               => 'Inserta unu cummentu inoghe a suta.',
'summary-preview'                  => 'Antiprima ogetu:',
'subject-preview'                  => 'Antiprima tema/tìtulu:',
'blockedtitle'                     => "S'usuàriu est istadu bloccau",
'blockedtext'                      => "'''Custu nùmene usuàriu o indiritzu IP est stadu bloccadu.'''

Su bloccu est stadu postu dae $1. Su motivu de su bloccu est: ''$2''

* Su bloccu incumentzat: $8
* Su bloccu scadit: $6
* Intervallu de bloccu: $7

Si boles, podes tzerriare $1 o un'àteru [[{{MediaWiki:Grouppage-sysop}}|amministradore]] pro faeddare de su bloccu.

Nota ca sa funtzione 'Ispedi un'e-mail a custu usuàriu' no est ativa ki no est stadu registradu un'indiritzu e-mail vàlidu in is [[Special:Preferences|preferèntzias]] tuas o ki s'usu de custa funtzione est stadu bloccadu.

S'indiritzu IP atuale est $3, su nùmeru ID de su bloccu est #$5.
Pro praxere spetzìfica totu is particulares in antis in carche siat pregunta de acrarimentu.",
'blockednoreason'                  => 'perunu motivu indicadu',
'whitelistedittitle'               => "Esigit s'identificatzione pro acontzare is pàginas",
'loginreqtitle'                    => 'Identificatzione rekesta',
'loginreqlink'                     => 'identifica·ti',
'loginreqpagetext'                 => 'Depes èsser $1 pro bìer àteras pàginas.',
'accmailtitle'                     => 'Password ispedia.',
'newarticle'                       => '(Nou)',
'newarticletext'                   => "Custa pàgina no esistit galu.
Pro creare sa pàgina, scrie in su box inoghe in bàsciu (abbàida sa [[{{MediaWiki:Helppage}}|pàgina de agiudu]] pro àteras informatziones).
Si ses intradu inoghe pro sbàlliu, carca in su browser tuo su butone '''back/indietro'''.",
'anontalkpagetext'                 => "----''Custa est sa pàgina de cuntierra de unu usuàriu anònimu ki no at creadu unu account galu, o ki non dd'usat. Pro custu impreamus su nùmeru de indiritzos IP pro ddu identificare. Is indiritzos IP podent però èsser cundivìdidos dae unos cantos usuàrios. Si ses unu usuàriu anònimu e ritenes ki custos cummentos non sunt diretos a tue, pro praxere [[Special:UserLogin/signup|crea unu account]] o [[Special:UserLogin|identifica·ti (log in)]] pro evitare cunfusione cun àteros usuàrios anònimos.''",
'noarticletext'                    => 'In custu momentu sa pàgina est bùida.
Podes [[Special:Search/{{PAGENAME}}|chircare custu tìtulu]] in àteras pàginas, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} chircare in is registros ligados] oppuru [{{fullurl:{{FULLPAGENAME}}|action=edit}} acontzare sa pàgina]</span>.',
'userpage-userdoesnotexist'        => 'S\'account de s\'usuàriu "$1" no est stadu registradu.
Pro praxere abbàida si boles creare/acontzare custa pàgina.',
'userpage-userdoesnotexist-view'   => 'S\'account de s\'usuàriu "$1" no est stadu registradu.',
'updated'                          => '(Agiornadu)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Regorda·ti ca custa est feti una ANTIPRIMA. Is mudàntzias tuas non sunt galu sarbadas!'''",
'previewconflict'                  => "Custa antiprima rapresentat su testu in s'àrea acontzu testu de susu comente at a pàrrer si dda sarbas.",
'editing'                          => 'Acontzu de $1',
'editingsection'                   => 'Acontzende $1 (setzione)',
'editingcomment'                   => 'Acontzu de $1 (setzione noa)',
'editconflict'                     => 'Cunflitu de editzione: $1',
'explainconflict'                  => "Calicunu àteru at acontzadu custa pàgina in su tempus ki dda fias acontzende tue.
S'àrea de testu de susu cuntènnet su testu de sa pàgina in sa forma atuale.
Is mudàntzias tuas sunt ammustradas in s'àrea de testu de bàsciu.
As a dèper insertare is mudàntzias tuas in su testu atuale, e pro custu a ddas scrìer in s'àrea de susu.
'''Solu''' su testu in s'àrea de susu at a èsser sarbadu si carcas su butone \"{{int:savearticle}}\".",
'yourtext'                         => 'Su testu tuo',
'storedversion'                    => 'Revisione in arkìvio',
'editingold'                       => "'''ATENTZIONE: Ses acontzende una revisione non-agiornada de sa pàgina.'''
Si dda sarbas de aici, totu is acontzos fatos a pustis de custa revisione ant a bènner pèrdidos pro semper.",
'yourdiff'                         => 'Diferèntzias',
'copyrightwarning'                 => "Abbàida, pro praxere, ki totu is contributziones a {{SITENAME}} sunt cunsideradas lassadas a suta permissu de tipu $2 (càstia $1 pro nde schire de prus). Si non keris ki su scritu tuo potzat èsser acontzadu e re-distribuidu dae kie si siat sena piedade e sena àteros lìmites, non ddu imbies a {{SITENAME}}.<br />
Cun s'imbiu de custu scritu ses garantende, a responsabilidade tua, si su scritu ddu as cumpostu tue de persona e in originale, o puru si est stadu copiadu dae una fonte de domìniu pùblicu, o una fonte de gasi, o puru si as otentu permissu craru de impreare custu scritu e si ddu podes dimustrare. '''NO IMPREARE MATERIALE COBERTU DAE DERETU DE AUTORE SENA PERMISSU CRARU!'''",
'templatesused'                    => '{{PLURAL:$1|Template impreadu|Templates impreados}} in custa pàgina:',
'templatesusedpreview'             => '{{PLURAL:$1|Template impreadu|Templates impreados}} in custa antiprima:',
'templatesusedsection'             => '{{PLURAL:$1|Template impreadu|Templates impreados}} in custa setzione:',
'template-protected'               => '(amparadu)',
'template-semiprotected'           => '(mesu-amparadu)',
'hiddencategories'                 => 'Custa pàgina faghet parte de {{PLURAL:$1|1 categoria cuada|$1 categorias cuadas}}:',
'nocreatetitle'                    => 'Creatzione de pàginas limitada',
'nocreate-loggedin'                => 'Non tenes su permissu de creare pàginas noas.',
'permissionserrors'                => 'Faddina de permissos',
'permissionserrorstext-withaction' => 'Non tenes su permissu de $2, pro {{PLURAL:$1|custu motivu|custus motivus}}:',
'moveddeleted-notice'              => 'Custa pàgina est istada fuliada.
Su registru de is fuliaduras e moviduras de sa pàgina est ammustradu pro informatzione.',
'log-fulllog'                      => 'Abbista su registru intreu',
'edit-conflict'                    => 'Cunflitu de editzione.',

# History pages
'viewpagelogs'           => 'Càstia sos registros de custa pàgina',
'nohistory'              => "Non b'est sa stòria de is acontzos pro custa pàgina.",
'currentrev'             => 'Revisione currente',
'currentrev-asof'        => 'Versione currente de is $1',
'revisionasof'           => 'Revisione de is $1',
'revision-info'          => 'Revisione de is $1 dae $2',
'previousrevision'       => '← Acontzu in antis',
'nextrevision'           => 'Acontzu in fatu →',
'currentrevisionlink'    => 'Revisione currente',
'cur'                    => 'curr',
'next'                   => 'in fatu',
'last'                   => 'ant',
'page_first'             => 'prima',
'page_last'              => 'ùrtima',
'histlegend'             => "Cunfrontu intre versiones: sebera sa casella de sa versione ki boles e carca \"Invio\" o su butone in bàsciu.<br />
Cosas de ammentare: '''({{int:cur}})''' = diferèntzias cun sa versione currente,
'''({{int:last}})''' = diferèntzias cun sa versione in antis, '''{{int:minoreditletter}}''' = acontzu minore.",
'history-fieldset-title' => 'Sfògia sa stòria',
'history-show-deleted'   => 'Petzi borrados',
'histfirst'              => 'Prima',
'histlast'               => 'Ùrtima',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(bùida)',

# Revision feed
'history-feed-item-nocomment' => '$1 su $2',

# Revision deletion
'rev-delundel'               => 'ammustra/cua',
'rev-showdeleted'            => 'ammustra',
'revdelete-show-file-submit' => 'Eja',
'revdelete-radio-set'        => 'Eja',
'revdel-restore'             => 'Muda sa visibilidade',
'revdel-restore-deleted'     => 'revisiones burradas',
'revdel-restore-visible'     => 'revisiones visìbiles',
'pagehist'                   => 'Istòria de sa pàgina',
'deletedhist'                => 'Istòria fuliada',
'revdelete-content'          => 'cuntènnidu',
'revdelete-summary'          => "ogetu de s'acontzu",
'revdelete-uname'            => 'nùmene usuàriu',
'revdelete-hid'              => 'cua $1',
'revdelete-unhid'            => 'ammustra $1',
'revdelete-log-message'      => '$1 pro $2 {{PLURAL:$2|revisione|revisiones}}',
'logdelete-log-message'      => '$1 pro $2 {{PLURAL:$2|eventu|eventos}}',
'revdelete-reasonotherlist'  => 'Àteru motivu',

# Revision move
'revmove-reasonfield' => 'Motivu:',

# History merging
'mergehistory-from'      => 'Pàgina de orìgine:',
'mergehistory-into'      => 'Pàgina de destinatzione:',
'mergehistory-no-source' => 'Sa pàgina de orìgine $1 no esistit.',
'mergehistory-reason'    => 'Motivu:',

# Merge log
'revertmerge' => "Fùrria s'unione",

# Diffs
'history-title'           => 'Istòria de is revisiones de "$1"',
'difference'              => '(Diferèntzias intre revisiones)',
'lineno'                  => 'Lìnia $1:',
'compareselectedversions' => 'Cumpara versiones scioberadas',
'editundo'                => 'annudda',

# Search results
'searchresults'                  => 'Resurtados de sa chirca',
'searchresults-title'            => 'Resurtados pro sa chirca de "$1"',
'searchresulttext'               => 'Pro àteras informatziones a subra sa chirca intre de {{SITENAME}}, càstia [[{{MediaWiki:Helppage}}|Chirca in {{SITENAME}}]].',
'searchsubtitle'                 => 'Chirca de \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|totu is pàginas ca incumentzant pro "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|totu is pàginas chi ligant a "$1"]])',
'searchsubtitleinvalid'          => 'As chircadu "$1"',
'titlematches'                   => "Currispondèntzias in su tìtulu de s'artìculu",
'notitlematches'                 => 'Peruna currispondentzia de is tìtulos de pàgina',
'textmatches'                    => "Currispondèntzias in su testu de s'artìculu",
'notextmatches'                  => "Peruna currispondèntzia in su testu de s'artìculu",
'prevn'                          => '{{PLURAL:$1|cabudianu|cabudianos $1}}',
'nextn'                          => '{{PLURAL:$1|imbeniente|imbenientes $1}}',
'shown-title'                    => 'Ammustra $1 {{PLURAL:$1|resurtadu|resurtados}} pro pàgina',
'viewprevnext'                   => 'Càstia ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'              => 'Possibilidades de chirca',
'searchhelp-url'                 => 'Help:Agiudu',
'searchprofile-everything'       => 'Totu',
'searchprofile-advanced'         => 'Avantzada',
'searchprofile-articles-tooltip' => 'Chirca in $1',
'searchprofile-project-tooltip'  => 'Chirca in $1',
'searchprofile-images-tooltip'   => 'Chirca files',
'search-result-size'             => '$1 ({{PLURAL:$2|1 faeddu|$2 faeddos}})',
'search-result-score'            => 'Rilevàntzia: $1%',
'search-redirect'                => '(redirect $1)',
'search-section'                 => '(setzione $1)',
'search-suggest'                 => 'Fortzis fias chirkende: $1',
'search-interwiki-caption'       => 'Progetos frades',
'search-interwiki-default'       => '$1 resurtados:',
'search-interwiki-more'          => '(àteru)',
'search-mwsuggest-enabled'       => 'cun impostos',
'search-mwsuggest-disabled'      => 'chentza impostos',
'searcheverything-enable'        => 'Chirca in totu is nùmene-logos:',
'searchall'                      => 'totu',
'showingresults'                 => "Inoghe sighende {{PLURAL:$1|benit ammustradu '''1''' resurtadu|benint ammustrados '''$1''' resurtados}} incumentzende dae su nùmeru '''$2'''.",
'showingresultsheader'           => "{{PLURAL:$5|Resultadu '''$1''' de '''$3'''|Resultadus '''$1 - $2''' de '''$3'''}} pro '''$4'''",
'nonefound'                      => "'''Annota''': sa chirca est fata \"pro definidura\" sceti in unos cantos Nùmene-logos.
Prova a seberare ''totu:'' pro chircare in totu su cuntènnidu (inclùdidas pàginas de cuntierra, template, etc), opuru sèbera comente prefissu su pretzisu Nùmene-logu ki boles.",
'powersearch'                    => 'Chirca',
'powersearch-legend'             => 'Chirca delantada',
'powersearch-ns'                 => 'Chirca in su nùmene-logu:',
'powersearch-redir'              => 'Lista re-indiritzamentos',
'powersearch-field'              => 'Chirca',
'powersearch-togglelabel'        => 'Seletziona:',
'powersearch-toggleall'          => 'Totu',
'powersearch-togglenone'         => 'Nudda',

# Quickbar
'qbsettings'               => 'Settaggio della barra menu',
'qbsettings-none'          => 'Nessuno',
'qbsettings-fixedleft'     => 'Fisso a sinistra',
'qbsettings-fixedright'    => 'Fisso a destra',
'qbsettings-floatingleft'  => 'Fluttuante a sinistra',
'qbsettings-floatingright' => 'Fluttuante a destra',

# Preferences page
'preferences'                 => 'Preferèntzias',
'mypreferences'               => 'Preferèntzias meas',
'prefs-edits'                 => 'Nùmeru de acontzos:',
'prefsnologin'                => 'Non ses intrau',
'prefsnologintext'            => 'Depes èsser <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} intradu]</span> pro seberare is preferèntzias.',
'changepassword'              => 'Càmbia password',
'prefs-skin'                  => 'Bisura',
'skin-preview'                => 'Antiprima',
'prefs-math'                  => 'Fòrmulas matemàticas',
'prefs-datetime'              => 'Data e ora',
'prefs-personal'              => 'Datos personales',
'prefs-rc'                    => 'Ùrtimas mudàntzias',
'prefs-watchlist'             => 'Watchlist',
'prefs-misc'                  => 'Àteras preferèntzias',
'prefs-resetpass'             => 'Càmbia password',
'saveprefs'                   => 'Sarva preferèntzias',
'resetprefs'                  => 'Re-imposta is preferèntzias',
'prefs-editing'               => 'Box de acontzadura',
'rows'                        => 'Lìnias:',
'columns'                     => 'Colunnas:',
'searchresultshead'           => 'Settaggio delle preferenze per la ricerca',
'resultsperpage'              => 'Risultati da visualizzare per pagina',
'contextlines'                => 'Righe di testo da mostrare per ciascun risultato',
'contextchars'                => 'Caratteri per linea',
'stub-threshold-disabled'     => 'Disativadu',
'recentchangescount'          => 'Nùmeru de acontzos de amostare pro definidura:',
'savedprefs'                  => 'Is preferèntzias tuas sunt stadas sarbadas.',
'timezonelegend'              => 'Zona de oràriu:',
'localtime'                   => 'Ora locale:',
'timezoneoffset'              => 'Diferèntzia¹:',
'timezoneregion-africa'       => 'Àfrica',
'timezoneregion-america'      => 'Amèrica',
'timezoneregion-antarctica'   => 'Antàrtide',
'timezoneregion-arctic'       => 'Àrtide',
'timezoneregion-asia'         => 'Àsia',
'timezoneregion-australia'    => 'Austràlia',
'timezoneregion-europe'       => 'Europa',
'prefs-searchoptions'         => 'Possibilidades a subra de sa chirca',
'prefs-namespaces'            => 'Nùmene-logos',
'prefs-files'                 => 'Files',
'youremail'                   => 'E-mail:',
'username'                    => 'Nùmene usuàriu:',
'uid'                         => 'ID usuàriu:',
'prefs-registration'          => 'Ora de registratzione:',
'yourrealname'                => 'Nùmene beru:',
'yourlanguage'                => 'Limba:',
'yournick'                    => 'Sa firma tua:',
'yourgender'                  => 'Natura:',
'gender-unknown'              => 'Non spetzificadu',
'gender-male'                 => 'Mascu',
'gender-female'               => 'Fèmina',
'email'                       => 'E-mail',
'prefs-info'                  => 'Informatzione bàsica',
'prefs-signature'             => 'Firma',
'prefs-dateformat'            => 'Formadu data',
'prefs-advancedediting'       => 'Sèberos avantzados',
'prefs-advancedrc'            => 'Sèberos avantzados',
'prefs-advancedrendering'     => 'Sèberos avantzados',
'prefs-advancedsearchoptions' => 'Sèberos avantzados',
'prefs-advancedwatchlist'     => 'Sèberos avantzados',
'prefs-diffs'                 => 'Diferèntzias',

# User rights
'userrights-user-editname'       => 'Inserta unu nùmene usuàriu:',
'editinguser'                    => "Cambiamentu de is deretos usuàriu de s'usuàriu '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-reason'              => 'Motivu:',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Grupu:',
'group-user'          => 'Usuàrios',
'group-autoconfirmed' => 'Usuàrios autocunfirmadus',
'group-bot'           => 'Bots',
'group-sysop'         => 'Amministradores',
'group-bureaucrat'    => 'Buròcrates',
'group-all'           => '(totus)',

'group-user-member'          => 'Usuàriu',
'group-autoconfirmed-member' => 'Autocunfirmados usuàrios',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Amministradore',
'group-bureaucrat-member'    => 'burocrate',

'grouppage-user'          => '{{ns:project}}:Usuàrios',
'grouppage-autoconfirmed' => '{{ns:project}}:Usuàrios autocunfirmadus',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Amministradores',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocrates',

# Rights
'right-read'               => 'Lègere pàginas',
'right-edit'               => 'Acontzare pàginas',
'right-move'               => 'Mòver pàginas',
'right-move-subpages'      => 'Mòvere pàginas cun is suta-pàginas issoru',
'right-move-rootuserpages' => 'Mòvere is pàginas base de is usuàrios',
'right-movefile'           => 'Mòvere files',
'right-upload'             => 'Carrigare files',
'right-reupload'           => 'Subra-iscrìere files esistentes',
'right-reupload-own'       => 'Subra-iscrìere files esistentes carrigados dae issetotu',
'right-upload_by_url'      => 'Carrigare files dae unu URL',
'right-autoconfirmed'      => 'Acontzare pàginas mesu-amparadas',
'right-delete'             => 'Fuliare pàginas',
'right-browsearchive'      => 'Chircare pàginas fuliadas',
'right-undelete'           => 'Restaurare una pàgina',
'right-siteadmin'          => 'Bloccare e sbloccare su database',

# User rights log
'rightslog'  => 'Deretos de is usuàrios',
'rightsnone' => '(nisciunu)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'          => 'lègher custa pàgina',
'action-edit'          => 'acontzare custa pàgina',
'action-createpage'    => 'creare pàginas',
'action-move'          => 'mòvere custa pàgina',
'action-movefile'      => 'mòvere custu file',
'action-delete'        => 'burrare custa pàgina',
'action-browsearchive' => 'chircare pàginas fuliadas',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|mudàntzia|mudàntzias}}',
'recentchanges'                  => 'Ùrtimas mudàntzias',
'recentchanges-legend'           => 'Possibilidades subra ùrtimas mudàntzias',
'recentchanges-feed-description' => 'Custu feed riportada is ùrtimas mudàntzias a is cuntènnidos de su giassu.',
'recentchanges-label-newpage'    => 'Custu acontzu at creadu una pàgina noa',
'recentchanges-label-minor'      => 'Custu est unu acontzu minore',
'recentchanges-label-bot'        => 'Custu acontzu est stadu fatu dae unu bot',
'rcnote'                         => "Inoghe sighende {{PLURAL:$1|b'est s'ùrtima mudàntzia|bi sunt is ùrtimas '''$1''' mudàntzias}} {{PLURAL:$2|in s'ùrtima die|in is ùrtimas '''$2''' dies}}; is datos sunt agiornados a  $5, $4.",
'rcnotefrom'                     => "Sas chi sighint sunt sas mudàntzias dae '''$2''' (fintzas a '''$1''').",
'rclistfrom'                     => 'Ammustra mudàntzias dae $1',
'rcshowhideminor'                => '$1 acontzos minores',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 usuàrios intraus',
'rcshowhideanons'                => '$1 usuàrios anònimos',
'rcshowhidemine'                 => '$1 acontzos meos',
'rclinks'                        => 'Ammustra is ùrtimas $1 mudàntzias fatas in is ùrtimas $2 dies<br />$3',
'diff'                           => 'dif',
'hist'                           => 'ist',
'hide'                           => 'Cua',
'show'                           => 'Ammustra',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc_categories_any'              => 'Calesisiat',
'rc-change-size'                 => '$1',
'newsectionsummary'              => '/* $1 */ setzione noa',
'rc-enhanced-expand'             => 'Ammustra particulares (esigit JavaScript)',
'rc-enhanced-hide'               => 'Cua particulares',

# Recent changes linked
'recentchangeslinked'          => 'Mudàntzias ligadas',
'recentchangeslinked-feed'     => 'Mudàntzias ligadas',
'recentchangeslinked-toolbox'  => 'Mudàntzias ligadas',
'recentchangeslinked-title'    => 'Mudàntzias ligadas a "$1"',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-summary'  => "Custa est una lista de is mudàntzias fatas dae pagu a is pàginas ligadas a cussa spetzificada.
Is pàginas de sa [[Special:Watchlist|watchlist tua]] sunt in '''grassu'''.",
'recentchangeslinked-page'     => 'Nùmene pàgina:',
'recentchangeslinked-to'       => 'Ammustra feti mudàntzias a pàginas ligadas a cussa spetzificada',

# Upload
'upload'              => 'Càrriga file',
'uploadbtn'           => 'Càrriga file',
'reuploaddesc'        => 'Torra a su mòdulu pro su carrigamentu.',
'uploadnologin'       => 'Non ses intrau',
'uploadnologintext'   => 'Depes èsser [[Special:UserLogin|identificadu (log in)]] pro carrigare files.',
'uploaderror'         => 'Faddina de carrigamentu',
'uploadtext'          => "Imprea su modulu a suta pro carrigare files nous.
Pro castiare o chircare is files giai carrigaus, bae a sa [[Special:FileList|lista de is files carrigaus]]. Carrigamentos de files e de noas versiones de files sunt registradas in su [[Special:Log/upload|registru de carrigamentu]], is burraduras in su [[Special:Log/delete|registru burraduras]].

Pro insertare unu file aintru de una pàgina, tocat a faghere unu cullegamentu tipu custu:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' pro impreare sa versione cumpleta de su file
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|testo alternativo]]</nowiki></tt>''' pro impreare una versione lada 200 pixel insertada in d'unu box, allinniada a manca e cun 'testu alternativu' comente didascalia
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' pro ingenerare unu cullegamentu a su file chentza de du biri",
'uploadlogpage'       => 'Carrigadas',
'uploadlogpagetext'   => 'A suta ddoi est sa lista de is files carrigados de reghente.
Càstia sa [[Special:NewFiles|galleria de files nous]] pro una presentada prus bisuale.',
'filename'            => 'Nùmene file',
'filedesc'            => 'Ogetu',
'fileuploadsummary'   => 'Ogetu:',
'filereuploadsummary' => 'Mudàntzias a su file:',
'filesource'          => 'Orìgine:',
'uploadedfiles'       => 'Files carrigadus',
'badfilename'         => 'Su nùmene de su file est stadu cunvertidu in "$1".',
'uploadwarning'       => 'Avvisu de carrigamentu',
'savefile'            => 'Sarva file',
'uploadedimage'       => 'carrigadu "[[$1]]"',
'upload-source'       => 'File de orìgine',
'sourcefilename'      => 'Nùmene de su file de orìgine:',
'sourceurl'           => 'Diretzione originària:',
'destfilename'        => 'Nùmene de su file de destinatzione:',
'upload-description'  => 'Descritzione de su file',
'upload-success-subj' => 'Carrigamentu acabau',

'upload-file-error' => 'Faddina a intru',

'license'            => 'Licèntzia:',
'license-header'     => 'Licèntzia',
'upload_source_file' => ' (unu file in su computer tuo)',

# Special:ListFiles
'imgfile'               => 'file',
'listfiles'             => 'Lista de is files',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nùmene',
'listfiles_user'        => 'Usuàriu',
'listfiles_size'        => 'Mannesa in byte',
'listfiles_description' => 'Descritzione',
'listfiles_count'       => 'Versiones',

# File description page
'file-anchor-link'          => 'File',
'filehist'                  => 'Stòria de su file',
'filehist-help'             => 'Carca unu grupu data/ora pro castiare su file comente si presentada in su tempus indicadu.',
'filehist-deleteall'        => 'fùlia totu',
'filehist-deleteone'        => 'cantzella',
'filehist-revert'           => 'fùrria',
'filehist-current'          => 'currente',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura de sa versione de is $1',
'filehist-nothumb'          => 'Peruna miniatura',
'filehist-user'             => 'Usuàriu',
'filehist-dimensions'       => 'Dimensiones',
'filehist-filesize'         => 'Mannesa de su file',
'filehist-comment'          => 'Cummentu',
'filehist-missing'          => 'File pèrdidu',
'imagelinks'                => 'Ligant a custu file',
'linkstoimage'              => '{{PLURAL:$1|Sa pàgina ki sighit ligat|Is $1 pàginas ki sighint ligant}} a custu file:',
'nolinkstoimage'            => 'Peruna pàgina ligat a custu file.',
'sharedupload'              => 'Custu file benit dae $1 e podet èssere impreau in àteros progetos.',
'uploadnewversion-linktext' => 'Carriga una versione noa de custu file',
'shared-repo-from'          => 'dae $1',

# File reversion
'filerevert-backlink' => '← $1',
'filerevert-comment'  => 'Motivu:',

# File deletion
'filedelete'                  => 'Cantzella $1',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'Cantzella su file',
'filedelete-submit'           => 'Cantzella',
'filedelete-success'          => "Su file '''$1''' est istadu fuliau.",
'filedelete-reason-otherlist' => 'Àteru motivu',

# MIME search
'download' => 'scàrriga',

# List redirects
'listredirects' => 'Lista de totu is redirects',

# Random page
'randompage' => 'Pàgina a sa tzurpa',

# Statistics
'statistics'              => 'Statìsticas',
'statistics-header-users' => 'Statìsticas subra is usuàrios',
'statistics-pages'        => 'Pàginas',

'disambiguationspage' => 'Template:Disambìgua',

'doubleredirects'     => 'Redirects dòpios',
'doubleredirectstext' => 'Custa pàgina cuntenet una lista de pàginas ki re-indiritzant a àteras pàginas de re-indiritzamentu.
Ogni lìnia cuntenet ligàmines a su primu e a su de duos re-indiritzamentu, aici comente sa prima lìnia de sa de duos re-indiritzamentos, chi de sòlitu adòbiat s\'artìculu "beru", a sa cale fintzas su primu re-indiritzamentu dia depet puntare.
Is re-indiritzamentos <del>cantzellados</del> sunt stados curretos.',

'brokenredirects'        => 'Redirects isballiaus',
'brokenredirectstext'    => 'Custus redirects ligant cun pàginas chi no esistint.',
'brokenredirects-edit'   => 'acontza',
'brokenredirects-delete' => 'cantzella',

'withoutinterwiki-legend' => 'Prefissu',
'withoutinterwiki-submit' => 'Ammustra',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'       => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'            => '$1 {{PLURAL:$1|ligàmene|ligàmenes}}',
'nmembers'          => '$1 {{PLURAL:$1|cumponente|cumponentes}}',
'nrevisions'        => '$1 {{PLURAL:$1|revisione|revisiones}}',
'nviews'            => '$1 {{PLURAL:$1|bisura|bisuras}}',
'lonelypages'       => 'Pàginas burdas',
'unusedimages'      => 'Files no impreaus',
'popularpages'      => 'Pàginas populares',
'wantedpages'       => 'Artìculos prus chircados',
'mostrevisions'     => 'Pàginas cun prus revisiones',
'prefixindex'       => 'Ìndighe de is pàginas pro initziales',
'shortpages'        => 'Pàginas crutzas',
'longpages'         => 'Pàginas longas',
'deadendpages'      => 'Pàginas chentza bessida',
'protectedpages'    => 'Pàginas amparadas',
'protectedtitles'   => 'Tìtulus amparadus',
'listusers'         => 'Lista usuàrios',
'usereditcount'     => '$1 {{PLURAL:$1|acontzu|acontzos}}',
'usercreated'       => 'Creadu su $1 a is $2',
'newpages'          => 'Pàginas noas',
'newpages-username' => 'Nùmene usuàriu:',
'move'              => 'Movi',
'movethispage'      => 'Move custa pàgina (càmbia su tìtulu)',
'unusedimagestext'  => 'Is files ki sighint sunt stados carrigados ma non sunt impreados.
Dia podent essere immàgines impreadas dae àteros giassos cun unu ligàmine diretu, e tando podent essere listados inoghe comente usu ativu.',
'notargettitle'     => 'Perunu obietivu',
'notargettext'      => "Non hai specificato una pagina o un Utente in relazione al quale eseguire l'operazione richiesta.",
'pager-newer-n'     => '{{PLURAL:$1|1 prus nou|$1 prus nous}}',
'pager-older-n'     => '{{PLURAL:$1|1 prus betzu|$1 prus betzos}}',

# Book sources
'booksources'               => 'Fontes libràrias',
'booksources-search-legend' => 'Chirca fontes libràrias',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Bae',

# Special:Log
'specialloguserlabel'  => 'Usuàriu:',
'speciallogtitlelabel' => 'Tìtulu:',
'log'                  => 'Registros',

# Special:AllPages
'allpages'        => 'Totu is pàginas',
'alphaindexline'  => 'dae $1 a $2',
'prevpage'        => 'Pàgina in antis ($1)',
'allpagesfrom'    => 'Ammustra pàginas a partire dae:',
'allpagesto'      => 'Ammustra pàginas fintzas a:',
'allarticles'     => 'Totu is pàginas',
'allinnamespace'  => 'Totu is pàginas (nùmene-logu $1)',
'allpagessubmit'  => 'Bae',
'allpages-bad-ns' => 'Su nùmene-logu "$1" non esistit in {{SITENAME}}.',

# Special:Categories
'categories' => 'Categorias',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'contributziones',

# Special:LinkSearch
'linksearch'    => 'Acàpios a foras',
'linksearch-ns' => 'Nùmene-logu:',
'linksearch-ok' => 'Chirca',

# Special:ListUsers
'listusers-submit' => 'Ammustra',

# Special:ActiveUsers
'activeusers-hidebots'   => 'Cua bots',
'activeusers-hidesysops' => 'Cua amministradores',

# Special:Log/newusers
'newuserlogpage'          => 'Usuàrios nous',
'newuserlog-byemail'      => 'password imbiada via e-mail',
'newuserlog-create-entry' => 'Account usuàriu nou',

# Special:ListGroupRights
'listgrouprights-group'   => 'Grupu',
'listgrouprights-members' => '(lista de is cumponentes)',

# E-mail user
'mailnologintext' => "Depes èsser [[Special:UserLogin|identificadu (login)]] e àer registradu un'indiritzu e-mail vàlidu in is [[Special:Preferences|preferèntzias tuas]] pro imbiare e-mail a àteros usuàrios.",
'emailuser'       => 'E-mail a custu usuàriu',
'emailpage'       => "Ispedi una missada a s'usuàriu",
'emailpagetext'   => "Imprea su mòdulu a suta pro ispedire una missada eletrònica a custu usuàriu.
S'indiritzu chi as insertadu in is [[Special:Preferences|preferèntzias usuàriu tuas]] at a pàrrere comente su chi at ispedidu sa e-mail, pro fàghere sa manera chi su destinatàriu ti respundat deretu.",
'defemailsubject' => 'Missada dae {{SITENAME}}',
'noemailtitle'    => 'Perunu indiritzu e-mail',
'noemailtext'     => 'Custu usuàriu no at ispetzificadu un indiritzu e-mail vàlidu.',
'email-legend'    => 'Imbia una missada e-mail a un àteru usuàriu de {{SITENAME}}',
'emailfrom'       => 'Dae:',
'emailto'         => 'A:',
'emailsubject'    => 'Ogetu:',
'emailmessage'    => 'Messàgiu:',
'emailsend'       => 'Imbia',
'emailccme'       => 'Ispedimia una còpia de su messàgiu miu.',
'emailsent'       => 'E-mail ispedia',
'emailsenttext'   => 'Sa e-mail tua est istada imbiada.',

# User Messenger
'usermessage-editor' => 'Missu de su sistema',

# Watchlist
'watchlist'         => 'Sa watchlist mea',
'mywatchlist'       => 'Sa watchlist mea',
'nowatchlist'       => 'No as indicadu pàginas in sa watchlist tua.',
'watchnologin'      => 'No intrau (log in)',
'watchnologintext'  => 'Devi prima fare il [[Special:UserLogin|login]]
per modificare la tua lista di osservati speciali.',
'addedwatch'        => 'Agiuntu a sa watchlist tua',
'addedwatchtext'    => "Sa pàgina \"[[:\$1]]\" est istada aciunta a sa [[Special:Watchlist|watchlist]] tua.
Is mudàntzias de custa pàgina e de sa pàgina de cuntierras sua ant a bennere elencadas inoe, e su tìtulu at a aparire in '''grassetto''' in sa pàgina de is [[Special:RecentChanges|ùrtimas mudàntzias]] pro du bidere mengius.",
'removedwatch'      => 'Bogadu dae sa watchlist tua',
'removedwatchtext'  => 'Sa pàgina  "[[:$1]]" est istada tirada dae sa [[Special:Watchlist|watchlist tua]].',
'watch'             => 'Pone in sa watchlist',
'watchthispage'     => 'Pone ogru a custu artìculu',
'unwatch'           => 'Tira dae sa watchlist',
'unwatchthispage'   => 'Boga custa pàgina dae sa watchlist tua',
'notanarticle'      => 'Custa pàgina no est unu artìculu',
'watchlist-details' => 'Sa watchlist tua cuntènnit {{PLURAL:$1|$1 pàgina|$1 pàginas}}, chentza contare is pàginas de cuntierras.',
'wlshowlast'        => 'Ammustra is ùrtimas $1 oras $2 dies $3',
'watchlist-options' => 'Possibilidades subra sa watchlist',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Giunghende a sa watchlist...',
'unwatching' => 'Boghende dae sa watchlist...',

'enotif_newpagetext'           => 'Custa est una pàgina noa.',
'enotif_impersonal_salutation' => 'Usuàriu de {{SITENAME}}',
'created'                      => 'creada',

# Delete
'deletepage'            => 'Fùlia pàgina',
'confirm'               => 'Cunfima',
'excontent'             => "su cuntènnidu fiat: '$1'",
'excontentauthor'       => "su cuntènnidu fiat: '$1' (e s'ùnicu contribudori fiat '[[Special:Contributions/$2|$2]]')",
'exblank'               => 'sa pàgina fiat bùida',
'delete-confirm'        => 'Fùlia "$1"',
'delete-backlink'       => '← $1',
'delete-legend'         => 'Fuliare',
'confirmdeletetext'     => "Ses acanta de burrare una pàgina cun totu su stòria sua.
Pro praxere, cunfirma ca est intentzione tua fàgher custu, ca connosches is cosseguèntzias de s'atzione tua, a ca custa est cunforma a is [[{{MediaWiki:Policy-url}}|lìnias polìticas]].",
'actioncomplete'        => "Acabada s'atzione",
'actionfailed'          => 'Atzione faddida',
'deletedtext'           => 'Sa pàgina "<nowiki>$1</nowiki>" est istada fuliada.
Càstia su log $2 pro unu registru de is ùrtimas fuliaduras.',
'deletedarticle'        => 'at fuliadu "[[$1]]"',
'dellogpage'            => 'Burraduras',
'dellogpagetext'        => 'A sighire una lista de is prus reghentes burraduras.',
'reverted'              => 'Torrada a sa versione in antis',
'deletecomment'         => 'Motivu:',
'deleteotherreason'     => 'Àteru motivu o motivu agiuntivu:',
'deletereasonotherlist' => 'Àteru motivu',

# Rollback
'rollback'       => 'Annudda is acontzos',
'rollbacklink'   => 'rollback',
'rollbackfailed' => 'Rollback faddidu',
'cantrollback'   => "Non si podet furriare s'acontzu;
s'ùrtimu contribuidore est s'ùnicu autore de custa pàgina.",
'revertpage'     => 'Burradas is mudàntzias de [[Special:Contributions/$2|$2]] ([[User talk:$2|cuntierras]]), torrada a sa versione cabudiana de [[User:$1|$1]]',

# Protect
'protectlogpage'              => 'Amparaduras',
'protectedarticle'            => 'at amparau "[[$1]]"',
'modifiedarticleprotection'   => 'at cambiau su livellu de amparadura pro "[[$1]]"',
'protect-backlink'            => '← $1',
'protectcomment'              => 'Motivu:',
'protectexpiry'               => 'Iscadèntzia:',
'protect_expiry_invalid'      => "S'iscadèntzia est imbàlida.",
'protect_expiry_old'          => 'Iscadentzia giai passada.',
'protect-text'                => "Custu modulu serbit pro castiari e cambiari su livellu de amparadura de sa pàgina '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Non tenes su permissu pro cambiare is livellus de amparadura de sa pàgina.
Is impostatziones atuales pro sa pàgina '''$1''':",
'protect-cascadeon'           => "A su momentu custa pàgina est bloccada pro ite est inclùdia {{PLURAL:$1|in sa pàgina indicada a suta, pro sa cali|in is pàginas indicadas a suta, pro is calis}} est ativa s'amparadura ricorsiva. Est possìbile cambiare su livellu de amparadura de custa pàgina, ma is impostatziones derivadas dae s'amparadura ricorsiva non ant a èssere mudadas.",
'protect-default'             => 'Autoritza totu is usuàrios',
'protect-fallback'            => 'Esigit su permissu "$1"',
'protect-level-autoconfirmed' => 'Blocca is usuàrios nobos o non registrados',
'protect-level-sysop'         => 'Isceti aministradores',
'protect-summary-cascade'     => 'ricorsiva',
'protect-expiring'            => 'iscadèntzia: $1 (UTC)',
'protect-cascade'             => 'Ampara totu is pàginas inclùdias in custa (amparadura ricorsiva)',
'protect-cantedit'            => 'Non podes cambiare is livellus de amparadura pro sa pàgina, pro ite non tenes su permissu de acontzare sa pàgina etotu.',
'protect-othertime'           => 'Àteru perìodu:',
'protect-expiry-options'      => '1 ora:1 hour,1 die:1 day,1 chida:1 week,2 chidas:2 weeks,1 mese:1 month,3 meses:3 months,6 meses:6 months,1 annu:1 year,infinidu:infinite',
'restriction-type'            => 'Permissu:',
'restriction-level'           => 'Livellu de restritzioni:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Acontzadura',
'restriction-move'   => 'Movimentu',
'restriction-create' => 'Creatzione',
'restriction-upload' => 'Carrigadroxiu',

# Restriction levels
'restriction-level-sysop'         => 'amparadura intrea',
'restriction-level-autoconfirmed' => 'mesu-amparada',
'restriction-level-all'           => 'ogni livellu',

# Undelete
'undelete'                  => 'Càstia pàginas fuliadas',
'undeletepage'              => 'Càstia e restaura pàginas fuliadas',
'viewdeletedpage'           => 'Càstia pàginas fuliadas',
'undeletepagetext'          => "{{PLURAL:$1|Sa pàgina chi sighit est istada fuliada, ma est ancora in archiviu e podit èssere recuperada|Is pàginas chi sighint sunt istadas fuliadas, ma sunt ancora in archiviu e podint èssere recuperadas}}. S'archiviu podit èssere sbudiau a periodus.",
'undelete-fieldset-title'   => 'Restàura revisiones',
'undeleterevisions'         => '$1 {{PLURAL:$1|revisione|revisiones}} in archìviu',
'undeletehistory'           => 'Restaurende custa pàgina, totu is revisiones ant a torrare in sa istòria sua.
Chi est istada creada una pàgina cun su matessi tìtulu, is revisiones recuperadas ant a insertare in sa istoria in antis.',
'undeletebtn'               => 'Ripristina',
'undeletelink'              => 'càstia/riprìstina',
'undeleteviewlink'          => 'abbista',
'undeleteinvert'            => 'Fùrria sa seletzione',
'undeletecomment'           => 'Motivu:',
'undeletedarticle'          => 'at restauradu "$1"',
'undeletedrevisions'        => '{{PLURAL:$1|1 revisione restaurada|$1 revisiones restauradas}}',
'undeletedrevisions-files'  => '{{PLURAL:$1|1 revisione|$1 revisiones}} e {{PLURAL:$2|1 file|$2 files}} restaurados',
'undeletedfiles'            => '{{PLURAL:$1|1 file restauradu|$1 files restaurados}}',
'undelete-search-box'       => 'Chirca pàginas fuliadas',
'undelete-search-prefix'    => 'Ammustra is pàginas ca su tìtulu cumentzat cun:',
'undelete-search-submit'    => 'Chirca',
'undelete-show-file-submit' => 'Eja',

# Namespace form on various pages
'namespace'      => 'Nùmene-logu:',
'invert'         => 'Fùrria sa seletzione',
'blanknamespace' => '(Printzipale)',

# Contributions
'contributions'       => 'Contributziones usuàriu',
'contributions-title' => 'Contributziones de $1',
'mycontris'           => 'Contributziones meas',
'contribsub2'         => 'Pro $1 ($2)',
'nocontribs'          => 'Nessuna modifica trovata conformemente a questi criteri.',
'uctop'               => '(ùrtimu de sa pàgina)',
'month'               => 'Dae su mese (e in antis):',
'year'                => "Dae s'annu (e in antis):",

'sp-contributions-newbies'  => 'Ammustra feti is contributziones de is accounts noos',
'sp-contributions-blocklog' => 'registru de is bloccos',
'sp-contributions-talk'     => 'cuntierra',
'sp-contributions-search'   => 'Chirca contributziones',
'sp-contributions-username' => 'Indiritzu IP o nùmene usuàriu:',
'sp-contributions-submit'   => 'Chirca',

# What links here
'whatlinkshere'            => 'Pàginas chi ligant a custa',
'whatlinkshere-title'      => 'Pàginas chi ligant a "$1"',
'whatlinkshere-page'       => 'Pàgina:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "Sas pàginas chi sighint ligant a '''[[:$1]]''':",
'nolinkshere'              => "Peruna pàgina ligat a '''[[:$1]]'''.",
'nolinkshere-ns'           => "Peruna pàgina ligat a '''[[:$1]]''' in su nùmene-logu seberadu.",
'isredirect'               => 'redirect',
'istemplate'               => 'inclusione',
'isimage'                  => 'acàpiu pintura',
'whatlinkshere-prev'       => '{{PLURAL:$1|cabudianu|cabudianos $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|imbeniente|imbenientes $1}}',
'whatlinkshere-links'      => '← acàpius',
'whatlinkshere-hideredirs' => '$1 redirects',
'whatlinkshere-hidetrans'  => '$1 inclusionis',
'whatlinkshere-hidelinks'  => '$1 acàpius',
'whatlinkshere-filters'    => 'Filtros',

# Block/unblock
'blockip'                  => 'Blocca usuàriu',
'blockip-title'            => 'Blocca usuàriu',
'blockip-legend'           => 'Blocca usuàriu',
'blockiptext'              => "Usa il modulo sottostante per bloccare l'accesso con diritto di scrittura da uno specifico indirizzo IP. Questo blocco deve essere operato SOLO per prevenire atti di vandalismo, ed in stretta osservanza dei principi tutti della [[{{MediaWiki:Policy-url}}|policy di {{SITENAME}}]]. Il blocco non può in nessun caso essere applicato per motivi ideologici.
Scrivi un motivo specifico per il quale questo indirizzo IP dovrebbe a tuo avviso essere bloccato (per esempio, cita i titoli di pagine eventualmente già oggetto di vandalismo editoriale).",
'ipadressorusername'       => 'Indiritzu IP o nùmene usuàriu:',
'ipbexpiry'                => 'Scadèntzia:',
'ipbreason'                => 'Motivu:',
'ipbreasonotherlist'       => 'Àteru motivu',
'ipbsubmit'                => 'Blocca custu usuàriu',
'ipbother'                 => 'Àteru perìodu:',
'ipboptions'               => '2 oras:2 hours,1 die:1 day,3 dies:3 days,1 chida:1 week,2 chidas:2 weeks,1 mese:1 month,3 meses:3 months,6 meses:6 months,1 annu:1 year,infinidu:infinite',
'ipbotheroption'           => 'àteru',
'badipaddress'             => "S'indiritzu IP indicadu non est currègidu.",
'blockipsuccesssub'        => 'Bloccu esecutivu',
'blockipsuccesstext'       => '[[Special:Contributions/$1|$1]] è stadu bloccadu. <br />
Abbàida sa [[Special:IPBlockList|lista de IP bloccados]] pro bìder sas bloccaduras.',
'ipb-edit-dropdown'        => 'Acontza su motivu de su bloccu',
'ipb-unblock-addr'         => 'Sblocca $1',
'ipb-blocklist-contribs'   => 'Contributziones de $1',
'unblockip'                => "Sblocca s'usuàriu",
'unblockiptext'            => 'Usa il modulo sottostante per restituire il diritto di scrittura ad un indirizzo IP precedentemente bloccato.',
'ipusubmit'                => 'Boga custu bloccu',
'ipblocklist'              => 'Usuàrios e indiritzos bloccados',
'ipblocklist-username'     => 'Nùmene usuàriu o indiritzu IP:',
'ipblocklist-submit'       => 'Chirca',
'blocklistline'            => '$1, $2 ha bloccato $3 ($4)',
'infiniteblock'            => 'infinitu',
'expiringblock'            => 'scadit su $1 a is $2',
'blocklink'                => 'blocca',
'unblocklink'              => 'sblocca',
'change-blocklink'         => 'tramuda su bloccu',
'contribslink'             => 'contributziones',
'blocklogpage'             => 'Bloccos de usuàrios',
'blocklogentry'            => 'bloccau [[$1]] pro unu tempu de $2 $3',
'unblocklogentry'          => 'at sbloccau $1',
'block-log-flags-nocreate' => 'creatzione account bloccada',
'blockme'                  => 'Blocca·mi',
'proxyblocksuccess'        => 'Fatu.',
'sorbs'                    => 'DNSBL',

# Developer tools
'lockdb'              => 'Blocca su database',
'unlockdb'            => 'Sblocca su database',
'lockdbtext'          => 'Bloccare il database sospenderà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere non consentirà a nessuno di eseguire operazioni che richiedano modifiche del database.<br /><br />
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare e, soprattutto, che il prima possibile sbloccherai nuovamente il database, ripristinandone la corretta funzionalità, non appena avrai terminato le tue manutenzioni.',
'unlockdbtext'        => 'Sbloccare il database ripristinerà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere di eseguire operazioni che richiedano modifiche del database.
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare.',
'lockconfirm'         => 'Sì, effettivamente intendo, sotto la mia responsabilità, bloccare il database.',
'unlockconfirm'       => ' Sì, effettivamente intendo, sotto la mia responsabilità, sbloccare il database.',
'lockbtn'             => 'Blocca su database',
'unlockbtn'           => 'Sblocca su database',
'locknoconfirm'       => 'Non hai spuntato la casellina di conferma.',
'lockdbsuccesssub'    => 'Blocco del database eseguito',
'unlockdbsuccesssub'  => 'Sblocco del database eseguito, rimosso blocco',
'lockdbsuccesstext'   => 'Il database di {{SITENAME}} è stato bloccato.
<br />Ricordati di rimuovere il blocco non appena avrai terminatoi le tue manutenzioni.',
'unlockdbsuccesstext' => 'Su database est istadu sbloccau.',
'databasenotlocked'   => 'Su database no est bloccadu.',

# Move page
'move-page'               => 'Movimentu de $1',
'move-page-legend'        => 'Movimentu pàgina',
'movepagetext'            => "Cun custu mòdellu podes renumenare una pàgina, movende totu sa stòria sua a sa pàgina noa.
Su tìtulu bèciu at a diventare una pàgina de reindiritzamentu a su tìtulu nou.
Podes agiornare automaticamente is redirects ca ligant a su tìtulu originàriu.
Si sèberas de no, assicura·ti de controllare pro [[Special:DoubleRedirects| reindiritzaduras dòpias]] o [[Special:BrokenRedirects|sballiadas]].
Ses responsàbile de t'assigurare ca is cullegamentos sighint a puntare  a ue depent puntare.

Annota ca sa pàgina '''non''' s'at a mòver si nde esistit giai un'àtera a su tìtulu nou, si no est ki siat bùida o cun sceti unu reindiritzamentu a sa bècia e siat chentza acontzos in antis. In casu de movimentu sballiadu, duncas, si podet torrare a su tìtulu bèciu, ma non podes subrascrìer una pàgina chi giai esistit.

'''ATENTZIONE:'''
Unu cambiamentu dràsticu podet creare problemas, mescamente a is pàginas prus populares;
pro praxere depes èsser seguru de àer cumpresu is cunsighèntzias prima de sighire a in antis.",
'movepagetalktext'        => "Sa pàgina cuntierras asotziada, chi esistit, at a èssere movida automaticamenti impare a sa pàgina base, '''a parte in custos casos''':
* su movimentu de sa pàgina est intre namespaces diversos;
* in currispondèntzia de su tìtulu nou esistit giai una pàgina de cuntierras (non bùida);
* sa casella inoe in bàsciu no est istata sceberada.

In custos casos, si cheres, depes mòvere a manu su cuntènnidu de sa pàgina.",
'movearticle'             => 'Move sa pàgina',
'movenologin'             => 'No identificadu (login)',
'movenologintext'         => 'Depes èsser unu usuàriu registradu e [[Special:UserLogin|identificadu]] pro pòder mòver una pàgina',
'newtitle'                => 'Tìtulu nou:',
'move-watch'              => 'Pone ogru a custa pàgina',
'movepagebtn'             => 'Move sa pàgina',
'pagemovedsub'            => 'Movimentu andadu bene',
'movepage-moved'          => '\'\'\'"$1" est istada mòvida a "$2"\'\'\'',
'articleexists'           => "Una pàgina cun custu nùmene esistit giai, o su nùmene ki as seberadu no est bàlidu.
Pro praxere sèbera un'àteru nùmene.",
'talkexists'              => "'''Su movimentu de sa pàgina est andadu bene, ma no est stadu possìbile mòver sa pàgina de cuntierras pro ite nde esistit giai un'àtera cun su matessi tìtulu. Pro praxere giunghe tue su cuntestu de sa pàgina betza.'''",
'movedto'                 => 'mòvida a',
'movetalk'                => 'Move sa pàgina de cuntierra galu',
'1movedto2'               => 'at mòvidu [[$1]] a [[$2]]',
'1movedto2_redir'         => 'at mòvidu [[$1]] a [[$2]] subra redirect',
'movelogpage'             => 'Moviduras',
'movereason'              => 'Motivu:',
'revertmove'              => 'fùrria',
'delete_and_move_confirm' => 'Eja, cantzella sa pàgina',
'selfmove'                => 'Is tìtulos de orìgine e de destinatzione sunt uguales;
impossìbile mòver sa pàgina a issa etotu.',
'immobile-source-page'    => 'Non si podet mòver custa pàgina.',
'move-leave-redirect'     => 'Lassa unu reindiritzamentu a palas',

# Export
'export'          => 'Esporta pàginas',
'export-download' => 'Sarba comente file',

# Namespace 8 related
'allmessagesname'           => 'Nùmene',
'allmessages-filter-legend' => 'Filtru',
'allmessages-filter-all'    => 'Totu',
'allmessages-language'      => 'Limba:',
'allmessages-filter-submit' => 'Bae',

# Thumbnails
'thumbnail-more' => 'Amannia',

# Special:Import
'import-interwiki-namespace' => 'Nùmene-logu de destinatzione:',
'import-upload-filename'     => 'Nùmene file:',
'import-comment'             => 'Cummentu:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Sa pàgina usuàriu tua',
'tooltip-pt-mytalk'               => 'Sa pàgina de is cuntierras tuas',
'tooltip-pt-preferences'          => 'Is preferèntzias chi podes scioberai',
'tooltip-pt-watchlist'            => 'Sa lista de is pàginas chi tue ses ponende ogru',
'tooltip-pt-mycontris'            => 'Sa lista de is contributziones meas',
'tooltip-pt-login'                => 'Si cunsìgiat sa registratzione; mancari non siat obligatoria',
'tooltip-pt-logout'               => 'Bessida (log out)',
'tooltip-ca-talk'                 => 'Cuntierras a propositu de su cuntestu de sa pàgina',
'tooltip-ca-edit'                 => "Podes acontzare custa pàgina.
Pro praxere, prima de sarbare càstia s'antiprima",
'tooltip-ca-addsection'           => 'Incumintza una setzione noa',
'tooltip-ca-viewsource'           => 'Sa pàgina est amparada.
Podes castiare sa mitza sua',
'tooltip-ca-history'              => 'Versiones coladas de custa pàgina',
'tooltip-ca-protect'              => 'Ampara custa pàgina',
'tooltip-ca-delete'               => 'Fùlia custa pàgina',
'tooltip-ca-move'                 => 'Move custa pàgina (càmbia su tìtulu)',
'tooltip-ca-watch'                => "Giunghe custa pàgina a sa ''watchlist'' tua",
'tooltip-ca-unwatch'              => 'Tira custa pàgina da sa watchlist tua',
'tooltip-search'                  => 'Chirca a intru de {{SITENAME}}',
'tooltip-search-go'               => 'Anda a una pàgina cun custu nùmene, si esistit',
'tooltip-search-fulltext'         => 'Chirca custu testu in sas pàginas',
'tooltip-p-logo'                  => 'Bìsita sa pàgina base',
'tooltip-n-mainpage'              => 'Bìsita sa pàgina base',
'tooltip-n-mainpage-description'  => 'Bìsita sa pàgina base',
'tooltip-n-portal'                => 'Descritzione de su progetu, ite podes fàgher, a innue agatas is cosas',
'tooltip-n-currentevents'         => 'Informatziones subra acuntèssias atuales',
'tooltip-n-recentchanges'         => 'Sa lista de is ùrtimas mudàntzias de su giassu',
'tooltip-n-randompage'            => 'Càrriga una pàgina a sorte',
'tooltip-n-help'                  => 'Pàginas de agiudu',
'tooltip-t-whatlinkshere'         => 'Lista de totu is pàginas chi ligant a custa',
'tooltip-t-recentchangeslinked'   => 'Lista de is ùrtimas mudàntzias de is pàginas ki ligant a custa',
'tooltip-feed-rss'                => 'RSS feed pro custa pàgina',
'tooltip-feed-atom'               => 'Atom feed pro custa pàgina',
'tooltip-t-contributions'         => 'Càstia sa lista de is contributziones de custu usuàriu',
'tooltip-t-emailuser'             => 'Ispedi una missada eletronica a custu usuàriu',
'tooltip-t-upload'                => 'Càrriga file multimediale',
'tooltip-t-specialpages'          => 'Lista de is pàginas ispetziales',
'tooltip-t-print'                 => "Versione de custa pàgina pro s'imprenta",
'tooltip-t-permalink'             => 'Cullegamentu permanente a custa versione de sa pàgina',
'tooltip-ca-nstab-main'           => 'Càstia su cuntènnidu de sa pàgina',
'tooltip-ca-nstab-user'           => 'Càstia sa pàgina usuàriu',
'tooltip-ca-nstab-special'        => 'Custa est una pàgina ispetziale, non dda podes acontzare',
'tooltip-ca-nstab-project'        => 'Càstia sa pàgina de servìtziu',
'tooltip-ca-nstab-image'          => 'Càstia sa pàgina de su file',
'tooltip-ca-nstab-template'       => 'Càstia su template',
'tooltip-ca-nstab-category'       => 'Càstia sa pàgina de sa categoria',
'tooltip-minoredit'               => 'Signa comente acontzu minore',
'tooltip-save'                    => 'Sarva is mudàntzias tuas',
'tooltip-preview'                 => 'Antiprima de is mudàntzias, pro pregeri usa custu prima de sarvari!',
'tooltip-diff'                    => 'Ammustra is mudàntzias ki as fatu a su testu',
'tooltip-compareselectedversions' => 'Càstia is diferèntzias de is duas versiones seberadas de custa pàgina',
'tooltip-watch'                   => 'Aciungi custa pàgina a sa watchlist tua',
'tooltip-recreate'                => 'Torra a creare sa pàgina mancari siat stada fuliada',
'tooltip-upload'                  => 'Cumentza a carrigare',
'tooltip-rollback'                => '"Rollback" annudda is mudàntzias de custa pàgina fatas dae s\'ùrtimu contribudori',
'tooltip-undo'                    => '"Annudda" fùrriat custu acontzu e aberit su mòdulu de acontzu comente antiprima.
Podes agiùnger unu motivu in s\'ogetu de s\'acontzu.',

# Attribution
'siteuser'    => '$1, {{GENDER:$1|usuàriu|usuària}} de {{SITENAME}}',
'anonuser'    => ' $1, usuàriu anònimu de {{SITENAME}}',
'others'      => 'àteros',
'siteusers'   => '$1, {{PLURAL:$2|usuàriu|usuàrios}} de {{SITENAME}}',
'anonusers'   => '$1, {{PLURAL:$2|usuàriu anònimu|usuàrios anònimos}} de {{SITENAME}}',
'creditspage' => 'Autores de sa pàgina',

# Info page
'infosubtitle' => 'Informatziones pro sa pàgina',
'numedits'     => 'Nùmeru de acontzos (pàgina): $1',
'numtalkedits' => 'Nùmeru de acontzos (pàgina de cuntierra): $1',

# Math errors
'math_unknown_error' => 'faddina disconnota',

# Browsing diffs
'previousdiff' => '← Acontzu in antis',
'nextdiff'     => 'Acontzu in fatu →',

# Media information
'file-info-size'       => '$1 × $2 pixels, mannesa de su file: $3, tipu de MIME: $4',
'file-nohires'         => '<small>Non si tenent risolutziones prus artas.</small>',
'svg-long-desc'        => 'file in formadu SVG, mannesa nominale $1 × $2 pixel, mannesa de su file: $3',
'show-big-image'       => 'Versione a risolutzione arta',
'show-big-image-thumb' => '<small>Mannesa de custa antiprima: $1 × $2 pixels</small>',

# Special:NewFiles
'imagelisttext' => "Innoe sighendi du est una lista de '''$1''' {{PLURAL:$1|file|files}} ordinada $2.",
'ilsubmit'      => 'Chirca',
'bydate'        => 'data',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'h',

# Bad image list
'bad_image_list' => 'Su formau est su chi sighit:

Benint consideraus isceti is listas putadas (lìnias chi incumentzant cun *).
Su primu cullegamentu depit èssere unu acàpiu a unu file malu (o indesiderau).
Is acàpius chi sighint in sa matessi lìnia sunt cunsideraus comente eccetziones (ossiat, pàginas innui si podet usare su file).',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Custu file cuntènnit informatziones aciuntivas, probabilmente aciuntas dae sa fotocamera o dae su scannerizadori impreaus pro ddu creare o ddu digitalizare. Si su file est istadu acontzau, unos particolares podent non currispundere a sa realtade.',
'metadata-expand'   => 'Ammustra particulares',
'metadata-collapse' => 'Cua particulares',
'metadata-fields'   => "Is campus de is metadatos EXIF listaus in custu messàgiu ant a èssere amostaus in sa pàgina de s'immàgine candu sa tabella de is metadatos est presentada in forma breve. Pro impostatzione predefinia, is àteros campus ant a èssere cuaus.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-artist'              => 'Autore',
'exif-exposuretime-format' => '$1 s ($2)',
'exif-fnumber-format'      => 'f/$1',
'exif-flash'               => 'Flash',
'exif-focallength-format'  => '$1 mm',

# EXIF attributes
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normale',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',

'exif-subjectdistance-value' => '$1 metros',

'exif-lightsource-4' => 'Lampu',

'exif-gaincontrol-0' => 'Nudda',

'exif-contrast-0' => 'Normale',

'exif-sharpness-0' => 'Normale',

# External editor support
'edit-externally'      => 'Acontza custu file usendi unu programma de foras',
'edit-externally-help' => '(Pro àteras informatziones càstia is [http://www.mediawiki.org/wiki/Manual:External_editors istrutziones])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'totu',
'imagelistall'     => 'totu',
'watchlistall2'    => 'totu',
'namespacesall'    => 'totu',
'monthsall'        => 'totu',
'limitall'         => 'totu',

# E-mail address confirmation
'confirmemail' => "Cunfirma s'indiritzu e-mail",

# action=purge
'confirm_purge_button' => 'OK',

# Separators for various lists, etc.
'semicolon-separator' => ';&#32;',
'comma-separator'     => ',&#32;',
'colon-separator'     => ':&#32;',
'autocomment-prefix'  => '-&#32;',
'word-separator'      => '&#32;',
'ellipsis'            => '…',
'percent'             => '$1%',

# Multipage image navigation
'imgmultipageprev' => '← pàgina in antis',
'imgmultipagenext' => 'pàgina in fatu →',
'imgmultigo'       => 'Bae!',
'imgmultigoto'     => 'Bae a sa pàgina $1',

# Table pager
'table_pager_first'        => 'Primu pàgina',
'table_pager_last'         => 'Ùrtima pàgina',
'table_pager_limit_submit' => 'Bae',

# Auto-summaries
'autosumm-blank'   => 'Pàgina isbuidada',
'autosumm-replace' => "Pàgina cambiada cun '$1'",
'autoredircomment' => 'Re-indiritzada a sa pàgina [[$1]]',
'autosumm-new'     => "Pàgina creada cun '$1'",

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Watchlist editor
'watchlistedit-normal-title' => 'Acontza sa watchlist',
'watchlistedit-raw-titles'   => 'Tìtulos:',

# Watchlist editing tools
'watchlisttools-view' => 'Càstia mudàntzias de importu',
'watchlisttools-edit' => 'Càstia e acontza sa watchlist',
'watchlisttools-raw'  => 'Acontza sa watchlist dae su testu',

# Signatures
'timezone-utc' => 'UTC',

# Special:Version
'version'                  => 'Versione',
'version-specialpages'     => 'Pàginas ispetziales',
'version-other'            => 'Àteru',
'version-version'          => '(Versione $1)',
'version-license'          => 'Licèntzia',
'version-software-version' => 'Versione',

# Special:FilePath
'filepath-page'   => 'Nùmene de su file:',
'filepath-submit' => 'Bae',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Chirca',

# Special:SpecialPages
'specialpages'             => 'Pàginas ispetziales',
'specialpages-group-pages' => 'Listas de is pàginas',

# Special:Tags
'tags-edit' => 'acontza',

# HTML forms
'htmlform-selectorother-other' => 'Àteru',

);
