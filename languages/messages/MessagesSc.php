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
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_SPECIAL         => 'Speciale',
	NS_MAIN            => '',
	NS_TALK            => 'Contièndha',
	NS_USER            => 'Utente',
	NS_USER_TALK       => 'Utente_discussioni',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK    => '$1_discussioni',
	NS_FILE            => 'Immàgini',
	NS_FILE_TALK       => 'Immàgini_contièndha'
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

$linkTrail = "/^([a-z]+)(.*)\$/sD";

$messages = array(
# User preference toggles
'tog-underline'            => 'Sutalìnia is cullegamentos',
'tog-highlightbroken'      => 'Evidèntzia <a href="" class="new">aici</a> is cullegamentos a pàginas inesistentes (si disativau: aici<a href="" class="internal">?</a>).',
'tog-justify'              => 'Alliniamentu paràgrafos giustificados',
'tog-hideminor'            => 'Cua is acontzos minores in sa pàgina de is ùrtimas mudàntzias',
'tog-usenewrc'             => 'Imprea is ùrtimas mudàntzias megioradas (esigit JavaScript)',
'tog-numberheadings'       => 'Auto-numeratzione de is tìtulos',
'tog-showtoolbar'          => "Amosta s'amusta de is ainas pro is acontzos (esigit JavaScript)",
'tog-editondblclick'       => 'Acontza pàginas cun dòpiu click (esigit JavaScript)',
'tog-rememberpassword'     => 'Amenta sa password in custu carculadore',
'tog-editwidth'            => 'Amànnia su box pro acontzare a sa largària màssima',
'tog-watchcreations'       => 'Aciungi is pàginas chi apo creadu a sa watchlist mea',
'tog-watchdefault'         => 'Aciungi is pàginas chi apo acontzadu a sa watchlist mea',
'tog-watchmoves'           => 'Aciungi is pàginas chi apo mòvidu a sa watchlist mea',
'tog-watchdeletion'        => 'Aciungi is pàginas chi apo fuliadu a sa watchlist mea',
'tog-minordefault'         => 'Signa totu is acontzos comente minores pro difetu',
'tog-enotifwatchlistpages' => 'Ispedimia una missada eletrònica candu una pàgina de sa watchlist mes est acontzada',
'tog-enotifusertalkpages'  => 'Ispedimia una missada eletrònica candu sa pàgina de is cuntierras mias est acontzada',
'tog-enotifminoredits'     => 'Ispedimia una missada eletrònica fintzas pro is acontzos minores de is pàginas',
'tog-shownumberswatching'  => 'Amosta su nùmeru de is usuàrios ca funt ponende ogru a sa pàgina',
'tog-fancysig'             => 'Trata sa firma comente unu testu wiki (chentza cullegamentos automaticos)',
'tog-watchlisthideown'     => 'Cua is acontzos meos dae sa watclist',
'tog-watchlisthidebots'    => 'Cua is acontzos de is bots dae sa watchlist',
'tog-watchlisthideminor'   => 'Cua is acontzos minores dae sa watchlist',
'tog-watchlisthideliu'     => 'Cua is acontzos de is usuàrios intraus dae sa watchlist',
'tog-watchlisthideanons'   => 'Cua is acontzos de is usuàrios anonimus dae sa watchlist',
'tog-showhiddencats'       => 'Amosta is categorias cuadas',

'underline-always'  => 'Semper',
'underline-never'   => 'Mai',
'underline-default' => 'Definiduras dae su browser tuo',

# Dates
'sunday'        => 'Domìniga',
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
'pagecategories'                => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'               => 'Pàginas in sa categoria "$1"',
'subcategories'                 => 'Subcategorias',
'category-media-header'         => 'Mèdius in sa categoria "$1"',
'category-empty'                => "''In custa categoria non bi est peruna pàgina o mèdiu.''",
'hidden-categories'             => '{{PLURAL:$1|Categoria cuada|Categorias cuadas}}',
'hidden-category-category'      => 'Categorias cuadas', # Name of the category where hidden categories will be listed
'category-subcat-count'         => "{{PLURAL:$2|Custa categoria cuntenet un'ùnica subcategoria. Amostada a suta.|Custa categoria cuntenet {{PLURAL:$1|sa subcategoria indicada|$1 subcategorias indicadas}} a suta, de $2 totales.}}",
'category-subcat-count-limited' => 'Custa categoria tenet {{PLURAL:$1|una subcategoria, amostada|$1 subcategorias, amostadas}} a suta.',
'category-article-count'        => '{{PLURAL:$2|Custa categoria cuntènnit isceti sa pàgina chi sighit.|Custa categoria cuntènnit {{PLURAL:$1|sa pàgina indicada|is $1 pàginas indicadas}} a suta, dae unu totale de $2.}}',
'listingcontinuesabbrev'        => 'sighit',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about'          => 'A propòsitu de',
'article'        => 'Artìculu',
'newwindow'      => '(oberit in d-una fentana noa)',
'cancel'         => 'Burra',
'qbfind'         => 'Busca',
'qbbrowse'       => 'Nàviga',
'qbedit'         => 'Acontza',
'qbpageoptions'  => 'Possibbilidades de sa pàgina',
'qbpageinfo'     => 'Cuntestu de sa pàgina',
'qbmyoptions'    => 'Is preferèntzias meas',
'qbspecialpages' => 'Pàginas ispetziales',
'moredotdotdot'  => 'Àteru…',
'mypage'         => 'Sa pàgina mea',
'mytalk'         => 'Cuntierras meas',
'anontalk'       => 'Cuntierras pro custu IP',
'navigation'     => 'Navigadura',
'and'            => '&#32;e',

# Metadata in edit box
'metadata_help' => 'Metadatos:',

'errorpagetitle'    => 'Faddina',
'returnto'          => 'Torra a $1.',
'tagline'           => 'Dae {{SITENAME}}',
'help'              => 'Agiudu',
'search'            => 'Kirca',
'searchbutton'      => 'Kirca',
'go'                => 'Bae',
'searcharticle'     => 'Bae',
'history'           => 'Istòria de sa pàgina',
'history_short'     => 'Istòria',
'info_short'        => 'Informatziones',
'printableversion'  => 'Versione de imprentai',
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
'lastmodifiedat'    => 'Ùrtimu acontzu su $1, a is $2.', # $1 date, $2 time
'viewcount'         => 'Custu artìculu est istadu lìgiu {{PLURAL:$1|borta|$1 bortas}}.',
'protectedpage'     => 'Pàgina amparada',
'jumpto'            => 'Bae a:',
'jumptonavigation'  => 'navigadura',
'jumptosearch'      => 'kirca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A propòsitu de {{SITENAME}}',
'aboutpage'            => 'Project:Informatziones',
'copyright'            => 'Cuntènnidu a suta lissèntzia $1.',
'copyrightpagename'    => 'Copyright de {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Copyrights',
'currentevents'        => 'Noas',
'currentevents-url'    => 'Project:Noas',
'disclaimers'          => 'Abbertimentos',
'disclaimerpage'       => 'Project:Abbertimentos generales',
'edithelp'             => "Agiudu pro s'acontzu o s'iscritura",
'edithelppage'         => 'Help:Acontzare',
'helppage'             => 'Help:Agiudu',
'mainpage'             => 'Pàgina printzipale',
'mainpage-description' => 'Pàgina printzipale',
'policy-url'           => 'Project:Polìtigas',
'privacy'              => 'Polìtiga pro is datos brivados',
'privacypage'          => 'Project:Polìtiga pro is datos brivados',

'badaccess' => 'Permissu non bastante',

'ok'                      => 'OK',
'pagetitle'               => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Bogau dae  "$1"',
'youhavenewmessages'      => 'Tenes $1 ($2).',
'newmessageslink'         => 'messàgios nous',
'newmessagesdifflink'     => 'ùrtima mudàntzia',
'editsection'             => 'acontza',
'editsection-brackets'    => '[$1]',
'editold'                 => 'acontza',
'viewsourceold'           => 'càstia mitza',
'editlink'                => 'acontza',
'viewsourcelink'          => 'càstia mitza',
'editsectionhint'         => 'Acontza sa setzione: $1',
'toc'                     => 'Cuntènnidu',
'showtoc'                 => 'amosta',
'hidetoc'                 => 'cua',
'thisisdeleted'           => 'Càstiare o recuperare $1?',
'viewdeleted'             => 'Bisi $1?',
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
'nosuchaction'      => 'Atzione non connota',
'nosuchactiontext'  => "S'atzione ispetzificada in sa URL no est vàlida. 
Est possibile chi sa URL siat istada cracada male, o si siat sighidu unu cullegamentu non vàlidu. 
Custu iat a poder esser unu bug de {{SITENAME}}.",
'nosuchspecialpage' => 'Custa pàgina ispetziale no esistit',
'nospecialpagetext' => "<big>'''As pediu una pàgina ispetziale non balida.'''</big>

Una lista de pàginas ispetziales bàlidas d'agatas in [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'               => 'Faddina',
'databaseerror'       => 'Faddina de su database',
'dberrortext'         => 'Faddina de sintassi in sa pregunta fata a su database.
Custu podet indicare unu isballiu de su software.
S\'ùrtima consulta chi as tentadu fiat:
<blockquote><tt>$1</tt></blockquote>
aintru de sa funtzione "<tt>$2</tt>".
MySQL at torradu custa faddina "<tt>$3: $4</tt>".',
'noconnect'           => 'Connessione a su database faddia pro unu problema tennicu de su giassu.<br />
$1',
'nodb'                => 'Seletzione de su database $1 no arrenèxia',
'readonly'            => 'Database bloccadu',
'enterlockreason'     => 'Inserta su motivu de su bloccu, ispetzifichende su momentu probabile chi su bloccu at a acabai',
'readonlytext'        => "In custu momentu su database est bloccadu dae aciunturas e àteras modificas, probabilmente pro ordinaria manutentzione a su database, a pustis de custas at a èssere normale torra.

S'aministradore chi dd'at bloccadu at donadu custa ispiegatzione: $1",
'missing-article'     => 'Su database no at agatau su testu de una pàgina chi diat àere agatau a suta de su nòmene "$1" $2.

Custu a su sòlitu si verìficat candu du est unu acàpiu in s\'istòria o in d\'unu cunfruntu tra arrevisiones de una pàgina chi est istada fuliada.

Si no est custu su casu, s\'est agatada una faddina de su software. 
Pro praxeri signala s\'acuntèssiu a unu [[Special:ListUsers/sysop|aministradore]] spetzifichede su URL de sa faddina.',
'missingarticle-rev'  => '(arrevisioni nùmeru: $1)',
'missingarticle-diff' => '(Dif: $1, $2)',
'internalerror'       => 'Faddina interna',
'filecopyerror'       => 'Non è stato possibile copiare il file "$1" come "$2".',
'filerenameerror'     => 'Non è stato possibile rinominare il file "$1" in "$2".',
'filedeleteerror'     => 'Non è stato possibile cancellare il file "$1".',
'filenotfound'        => 'No est istadu possìbili agatare "$1".',
'unexpected'          => 'Valore imprevisto: "$1"="$2".',
'formerror'           => 'Errore: il modulo non è stato inviato correttamente',
'badarticleerror'     => 'Questa operazione non è consentita su questa pagina.',
'cannotdelete'        => "Impossibile cancellare la pagina o l'immagine richiesta.",
'badtitle'            => 'Titolo non corretto',
'badtitletext'        => "Su tìtulu de sa pagina c'as pediu est bùidu, isbaliau, o iscritu ne is cullegamentus inter-wiki in modu non curregiu o cun carateres no amitius.",
'viewsource'          => 'Càstia mitza',
'viewsourcefor'       => 'pro $1',
'ns-specialprotected' => 'Is pàginas ispetziales non podent èssere acontzadas.',

# Login and logout pages
'logouttitle'             => 'Bessida usuàriu',
'logouttext'              => 'Logout effettuato.
Ora puoi continuare ad usare {{SITENAME}} come utente anonimo (ma il tuo indirizzo IP resterà riconoscibile), oppure puoi nuovamente richiedere il login con il precedente username, oppure come uno diverso.',
'welcomecreation'         => '<h2>Benvenuto, $1!</h2><p>Il tuo account è stato creato con successo.<br />Grazie per aver scelto di far crescere {{SITENAME}} con il tuo aiuto.<br />Per rendere {{SITENAME}} più tua, e per usarla più scorrevolmente, non dimenticare di personalizzare le tue preferenze.',
'loginpagetitle'          => 'Login usuàriu',
'yourname'                => 'Nòmene usuàriu',
'yourpassword'            => 'Password:',
'yourpasswordagain'       => 'Arripiti sa password',
'remembermypassword'      => 'Amenta sa password in custu computer',
'login'                   => 'Intra',
'nav-login-createaccount' => 'Intra / crea account',
'userlogin'               => 'Intra / crea account',
'logout'                  => 'Serra sessione',
'userlogout'              => 'Bessida',
'nologin'                 => 'Non tenes unu account? $1.',
'nologinlink'             => 'Crea unu account',
'createaccount'           => 'Crea account',
'gotaccountlink'          => 'Intra',
'createaccountmail'       => 'via e-mail',
'badretype'               => 'Sas passwords chi as insertau non currenspundint.',
'userexists'              => 'Su nòmene usuàriu insertadu est giai arregistradu. 
Scebera unu nòmene diferente.',
'youremail'               => 'E-mail:',
'username'                => 'Nòmene usuàriu:',
'uid'                     => 'ID usuàriu:',
'yourrealname'            => 'Nòmene beru:',
'yourlanguage'            => 'Limba:',
'yournick'                => 'Sa firma tua:',
'email'                   => 'E-mail',
'loginerror'              => 'Login error',
'noname'                  => 'Su nòmene usuàriu insertau no est bonu.',
'loginsuccesstitle'       => 'Ses intrau',
'loginsuccess'            => "'''Imoe ses intrau in {{SITENAME}} cun su nòmene usuàriu \"\$1\".'''",
'nosuchuser'              => 'Non ddu est usuàriu cun su nòmene "$1". 
Is nòmenes usuàriu intendent is lìteras mannas. 
Apura su nòmene insertadu o [[Special:UserLogin/signup|crea unu account nou]].',
'nouserspecified'         => 'Depes ispetzificare unu nòmene usuàriu.',
'wrongpassword'           => 'Sa password insertada no est bona. Prova torra.',
'mailmypassword'          => "Ispedi una password noa a s'indiritzu e-mail miu",
'passwordremindertitle'   => 'Servitziu Password Reminder di {{SITENAME}}',
'passwordremindertext'    => 'Calicunu (probabilmenti tue, cun s\'indiritzu IP $1) at pediu de arritziri una password noa pro intrare a {{SITENAME}} ($4).
Una password temporanea pro s\'usuàriu "$2" est istada impostada a "$3".
Chi custu fiat ne is intentziones tuas, depis intrare (log in) e scioberari una password noa.
Sa password temporanea tua at a iscadiri in {{PLURAL:$5|una die|$5 dies}}.

Chi non ses istadu a pediri sa password, o chi as torrau a agatare sa password torra e non da depis cambiari prus, non cunsideras custu messagiu e sighi a impreare sa password beccia.',
'noemail'                 => 'Peruna e-mail risultada registrada pro s\'usuàriu "$1".',
'passwordsent'            => 'Una password noa est istada ispedia a s\'indiritzu e-mail de s\'usuàriu "$1".
Pro pregheri, candu d\'arretzis faghe su login.',
'emailauthenticated'      => "S'indiritzu e-mail tuo est istadu autenticau su $2 a is $3.",
'emailconfirmlink'        => "Cunfirma s'indiritzu e-mail tuo",
'accountcreated'          => 'Account creadu',
'loginlanguagelabel'      => 'Limba: $1',

# Password reset dialog
'resetpass'                 => 'Càmbia sa password',
'resetpass_header'          => 'Càmbia sa password de su account',
'oldpassword'               => 'Password betza:',
'newpassword'               => 'Password noa:',
'retypenew'                 => 'Re-iscrie sa password noa:',
'resetpass-submit-loggedin' => 'Càmbia password',

# Edit page toolbar
'bold_sample'     => 'Grassetu',
'bold_tip'        => 'Grassettu',
'italic_sample'   => 'Corsivu',
'italic_tip'      => 'Corsivu',
'link_sample'     => 'Tìtulu cullegamentu',
'link_tip'        => 'Cullegamentu internu',
'extlink_sample'  => "http://www.example.com tìtulu de s'acàpiu",
'extlink_tip'     => 'Acàpiu a foras (amenta su prefissu http://)',
'headline_sample' => 'Testu de su tìtulu',
'headline_tip'    => 'Tìtulu de secundu livellu',
'math_sample'     => 'Inserta sa fòrmula innoe',
'math_tip'        => 'Formula matematica (LaTeX)',
'nowiki_sample'   => 'Inserta su testu non-formatau innoi',
'nowiki_tip'      => 'Ignora sa formatatzione wiki',
'image_sample'    => 'Esèmpiu.jpg',
'image_tip'       => 'Incòrpora una pintura',
'media_sample'    => 'Esèmpiu.ogg',
'media_tip'       => 'Cullegamentu a unu file',
'sig_tip'         => 'Firma cun data e ora',
'hr_tip'          => 'Lìnia orizontale (de usai cun critèriu)',

# Edit pages
'summary'                          => 'Ogetu:',
'subject'                          => 'Tema/tìtulu:',
'minoredit'                        => "Custu est un'acontzu minore:",
'watchthis'                        => 'Pone custa pàgina in sa watchlist mea',
'savearticle'                      => 'Sarva pàgina',
'preview'                          => 'Antiprima',
'showpreview'                      => "Amosta s'antiprima",
'showdiff'                         => 'Amosta mudàntzias',
'anoneditwarning'                  => "'''Atentzione:''' Non ses intrau (log in). 
S'indiritzu IP tuo at a èssere arregistradu in s'istòria de custa pàgina.",
'summary-preview'                  => 'Antiprima ogetu:',
'subject-preview'                  => 'Antiprima tema/tìtulu:',
'blockedtitle'                     => "S'usuàriu est istadu bloccau",
'blockedtext'                      => "<big>'''Custu nòmene usuàriu o indiritzu IP est istadu bloccau.'''</big>

Su bloccu est istadu postu dae $1. Su motivu de su bloccu est: ''$2''

* Su bloccu incumentzat: $8
* Su bloccu iscadit: $6
* Intervallu de bloccu: $7

Chi boles, podes cuntatare $1 o un àteru [[{{MediaWiki:Grouppage-sysop}}|aministradore]] pro faeddare de su bloccu.

Nota ca sa funtzioni 'Ispedi un'e-mail a custu usuàriu' no est ativa chi no est istadu registrau un indiritzu e-mail validu ne is [[Special:Preferences|preferèntzias]] tuas o chi s'usu de custa funtzioni est istadu bloccau.

S'indiritzu IP atuale est $3, su numeru ID de su bloccu est #$5.
Pro pregheri ispetzìfica totu is particolares in antis in carchi siat pregunta de chiarimentu.",
'accmailtitle'                     => 'Password ispedia.',
'newarticle'                       => '(Nou)',
'newarticletext'                   => "Custa pagina no esistit ancora.
Pro creare sa pagina, iscrie in su box inoghe in basciu (abàida sa [[{{MediaWiki:Helppage}}|pàgina de agiudu]] pro àteras informatziones).
Chi ses intrau inoghe pro isballiu, clicca in su browser tuo su butoni '''back/indietro'''.",
'noarticletext'                    => 'In custu momentu sa pàgina est bùida.
Podes [[Special:Search/{{PAGENAME}}|kircare custu tìtulu]] in àteras pàginas, <span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} kircare ne is registros ligados] oppuru [{{fullurl:{{FULLPAGENAME}}|action=edit}} acontzare sa pàgina]</span>.',
'updated'                          => '(Agiornau)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Arregodadia  ca custa est isceti una ANTIPRIMA. Sa versione tua no est istada ancora allogada!'''",
'previewconflict'                  => "Custa antiprima rapresentada su testu in s'area acontzu testu de susu comente at a aparire chi da sarvas.",
'editing'                          => 'Acontzu de $1',
'editingsection'                   => 'Acontzende $1 (setzione)',
'editingcomment'                   => 'Acontzu de $1 (setzione noa)',
'editconflict'                     => 'Cunflitu de editzione: $1',
'explainconflict'                  => 'Qualcun altro ha salvato una sua versione dell\'articolo nel tempo in cui tu stavi preparando la tua versione.<br />
La casella di modifica di sopra contiene il testo dell\'articolo nella sua forma attuale (cioè il testo attualmente online). Le tue modifiche sono invece contenute nella casella di modifica inferiore.
Dovrai inserire, se lo desideri, le tue modifiche nel testo esistente, e perciò scriverle nella casella di sopra.
<b>Soltanto</b> il testo nella casella di sopra sarà sakvato se premerai il bottone "Salva".<br />',
'yourtext'                         => 'Il tuo testo',
'storedversion'                    => 'Versione in archivio',
'editingold'                       => "'''ATTENZIONE: Stai modificando una versione dell'articolo non aggiornata.
Se la salvi così, tutti i cambiamenti apportati dopo questa revisione verranno persi per sempre.'''",
'yourdiff'                         => 'Diferèntzias',
'copyrightwarning'                 => "Abàida, pro pregheri, chi totu is contributziones a {{SITENAME}} sunt cunsideradas lassadas a suta permissu de tipu $2 (càstia $1 pro nde ischire de prus). Si non cheres chi s'iscritu tuo podat èssere acontzau e redistribuidu dae chie si siat sena piedade e sena àteros lìmites, non ddu imbies a {{SITENAME}}.<br />
Cun s'imbiu de custu iscritu ses garantende, a responsabilidade tua, chi s'iscritu ddu as cumpostu tue de persona e in originale, opuru chi est istadu copiadu dae una fonte de domìniu pùbricu, o una fonte de gai, opuru chi as otentu permissu craru de impreare custu iscritu e chi ddu podes demustrare. '''NO IMPREARE MATERIALE COBERTU DAE DERETU DE AUTORE SENA PERMISSU CRARU!'''",
'templatesused'                    => 'Templates impreaus in custa pàgina:',
'templatesusedpreview'             => 'Templates impreadus in custa antiprima:',
'templatesusedsection'             => 'Templates impreaus in custa setzione:',
'template-protected'               => '(amparadu)',
'template-semiprotected'           => '(semi-amparadu)',
'hiddencategories'                 => 'Custa pàgina faghet parti de {{PLURAL:$1|1 categoria cuada|$1 categorias cuadas}}:',
'permissionserrors'                => 'Faddina de permissos',
'permissionserrorstext-withaction' => 'Non tenes su permissu de $2, pro {{PLURAL:$1|custu motivu|custus motivus}}:',

# History pages
'viewpagelogs'           => 'Càstia sos registros de custa pàgina',
'nohistory'              => 'Cronologia delle versioni di questa pagina non reperibile.',
'currentrev'             => 'Versione currenti',
'currentrev-asof'        => 'Versione currente de is $1',
'revisionasof'           => 'Arrevisione de is $1',
'previousrevision'       => '← Acontzu in antis',
'nextrevision'           => 'Acontzu in fatu →',
'currentrevisionlink'    => 'Arrevisione currente',
'cur'                    => 'curr',
'next'                   => 'in fatu',
'last'                   => 'ant',
'page_first'             => 'prima',
'page_last'              => 'ùrtima',
'histlegend'             => "Cunfruntu intre versiones: scebera sa casella de sa versione chi boles e craca Invio o su butone in bàsciu.<br />
Legenda: '''({{int:cur}})''' = diferèntzias cun sa versione currente, 
'''({{int:last}})''' = diferèntzias cun sa versione in antis, '''{{int:minoreditletter}}''' = acontzu minore.",
'history-fieldset-title' => "Isfògia s'istòria",
'deletedrev'             => '[fuliada]',
'histfirst'              => 'Prima',
'histlast'               => 'Ùrtima',
'historyempty'           => '(bùida)',

# Revision feed
'history-feed-item-nocomment' => '$1 su $2', # user at time

# Revision deletion
'rev-delundel'          => 'amosta/cua',
'revdel-restore'        => 'Muda sa visibilidadi',
'pagehist'              => 'Istòria de sa pàgina',
'deletedhist'           => 'Istòria fuliada',
'revdelete-uname'       => 'Nòmene usuàriu',
'revdelete-hid'         => 'cua $1',
'revdelete-unhid'       => 'amosta $1',
'revdelete-log-message' => '$1 pro $2 {{PLURAL:$2|arrevisione|arrevisiones}}',

# History merging
'mergehistory-reason' => 'Motivu:',

# Merge log
'revertmerge' => "Fùrria s'unione",

# Diffs
'history-title'           => 'Istòria de is arrevisiones de "$1"',
'difference'              => '(Diferèntzias tra arrevisiones)',
'lineno'                  => 'Lìnia $1:',
'compareselectedversions' => 'Cumpara versiones scioberadas',
'editundo'                => 'annudda',
'diff-movedto'            => 'mòvidu a $1',
'diff-src'                => 'mitza',
'diff-with'               => '&#32;cun $1 $2',
'diff-with-additional'    => '$1 $2',
'diff-with-final'         => '&#32;e $1 $2',

# Search results
'searchresults'                  => 'Arresurtadus de sa kirca',
'searchresults-title'            => 'Arresurtadus pro sa kirca de "$1"',
'searchresulttext'               => 'Pro àteras informatziones pro sa kirca intre de {{SITENAME}}, càstia [[{{MediaWiki:Helppage}}|Kirca in {{SITENAME}}]].',
'searchsubtitle'                 => 'Kirca de \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|totu is pàginas ca incumentzant pro "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|totu is pàginas ca ligant a "$1"]])',
'searchsubtitleinvalid'          => 'As chircadu "$1"',
'noexactmatch'                   => "'''Sa pàgina \"\$1\" no esistit.''' 
Podes [[:\$1|creare custa pàgina]].",
'noexactmatch-nocreate'          => "'''Sa pàgina tìtolada \"\$1\" no esistit.'''",
'titlematches'                   => 'Nei titoli degli articoli',
'notitlematches'                 => 'Peruna currispondentzia de is tìtulos de pàgina',
'textmatches'                    => 'Nel testo degli articoli',
'notextmatches'                  => "Peruna currispondèntzia in su testu de s'artìculu",
'prevn'                          => 'cabudianos $1',
'nextn'                          => 'imbenientes $1',
'viewprevnext'                   => 'Càstia ($1) ($2) ($3).',
'searchhelp-url'                 => 'Help:Agiudu',
'searchprofile-articles-tooltip' => 'Chirca in $1',
'searchprofile-project-tooltip'  => 'Chirca in $1',
'searchprofile-images-tooltip'   => 'Chirca files',
'search-result-size'             => '$1 ({{PLURAL:$2|1 fueddu|$2 fueddus}})',
'search-redirect'                => '(redirect $1)',
'search-section'                 => '(setzione $1)',
'search-suggest'                 => 'Fortzis fias kirchende: $1',
'search-interwiki-caption'       => 'Progetos frades',
'search-interwiki-default'       => '$1 arresurtaus:',
'search-interwiki-more'          => '(àteru)',
'search-mwsuggest-enabled'       => 'cun impostos',
'search-mwsuggest-disabled'      => 'chentza impostos',
'searchall'                      => 'totu',
'showingresults'                 => "Innoe sighende {{PLURAL:$1|benit amostau '''1''' arresurtau|benint amostaus '''$1''' arresurtaos}} incumentzende dae su numeru '''$2'''.",
'showingresultstotal'            => "Sighende {{PLURAL:$4|benit amostadu s'arresurtadu '''$1''' de '''$3'''|benint amostados is arresurtados '''$1 - $2''' de '''$3'''}}",
'nonefound'                      => "'''Annota''': sa chirca est fata pro difetu isceti in unos Nòmene-logos. 
Prova a scioberai ''totu:'' pro chircare in totu su cuntènnidu (includius pàginas de cuntierra, template, etc), oppuru sciobera comente prefissu su pretzisu Nòmene-logu chi boles.",
'powersearch'                    => 'Kirca',
'powersearch-legend'             => 'Kirca delantada',
'powersearch-ns'                 => 'Kirca in su nòmene-logu:',
'powersearch-redir'              => 'Lista re-indiritzamentos',
'powersearch-field'              => 'Kirca',

# Preferences page
'preferences'              => 'Preferèntzias',
'mypreferences'            => 'Preferèntzias meas',
'prefs-edits'              => 'Nùmeru de acontzos:',
'prefsnologin'             => 'Non ses intrau',
'prefsnologintext'         => 'Depis èssere <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} intrau]</span> pro scioberai is preferèntzias.',
'prefsreset'               => 'Le tue Preferenze sono state ripescate dalla memoria di sistema del potente server di {{SITENAME}}.',
'qbsettings'               => 'Settaggio della barra menu',
'qbsettings-none'          => 'Nessuno',
'qbsettings-fixedleft'     => 'Fisso a sinistra',
'qbsettings-fixedright'    => 'Fisso a destra',
'qbsettings-floatingleft'  => 'Fluttuante a sinistra',
'qbsettings-floatingright' => 'Fluttuante a destra',
'changepassword'           => 'Càmbia password',
'skin'                     => 'Aspetto',
'skin-preview'             => 'Antiprima',
'prefs-watchlist'          => 'Watchlist',
'prefs-resetpass'          => 'Càmbia password',
'saveprefs'                => 'Sarva preferèntzias',
'resetprefs'               => 'Resetta preferenze',
'textboxsize'              => 'Dimensione della casella di edizione',
'rows'                     => 'Lìnias:',
'columns'                  => 'Colunnas:',
'searchresultshead'        => 'Settaggio delle preferenze per la ricerca',
'resultsperpage'           => 'Risultati da visualizzare per pagina',
'contextlines'             => 'Righe di testo da mostrare per ciascun risultato',
'contextchars'             => 'Caratteri per linea',
'recentchangescount'       => 'Nùmeru de lìnias ne in ùrtimas mudàntzias (predefinidu):',
'savedprefs'               => 'Le tue preferenze sono state salvate.',
'timezonetext'             => 'Immetti il numero di ore di differenza fra la tua ora locale e la ora del server (UTC).',
'localtime'                => 'Ora locale:',
'timezoneoffset'           => 'Diferèntzia¹:',
'timezoneregion-africa'    => 'Àfrica',
'timezoneregion-america'   => 'Amèrica',
'timezoneregion-asia'      => 'Àsia',
'timezoneregion-australia' => 'Austràlia',
'timezoneregion-europe'    => 'Europa',

# User rights
'editinguser'                    => "Cambiamentu de is deretos usuàriu de s'usuàriu '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Grupu:',
'group-user'          => 'Usuàrios',
'group-autoconfirmed' => 'Usuàrios autocunfirmadus',
'group-bot'           => 'Bots',
'group-sysop'         => 'Aministradores',
'group-bureaucrat'    => 'Buròcrates',
'group-all'           => '(totus)',

'group-user-member'          => 'Usuàriu',
'group-autoconfirmed-member' => 'Autocunfirmados usuàrios',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Aministradore',
'group-bureaucrat-member'    => 'Burocrate',

'grouppage-user'          => '{{ns:project}}:Usuàrios',
'grouppage-autoconfirmed' => '{{ns:project}}:Usuàrios autocunfirmadus',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Aministradores',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocrates',

# Rights
'right-read' => 'Lègere pàginas',

# User rights log
'rightslog' => 'Deretos de is usuàrios',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'          => 'acontzare custa pàgina',
'action-move'          => 'mòvere custa pàgina',
'action-movefile'      => 'mòvere custu file',
'action-browsearchive' => 'chircare pàginas fuliadas',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|mudàntzia|mudàntzias}}',
'recentchanges'                  => 'Ùrtimas mudàntzias',
'recentchanges-legend'           => 'Possibilidades subra ùrtimas mudàntzias',
'recentchanges-feed-description' => 'Custu feed riportada is ùrtimas mudàntzias a is cuntènnidos de su giassu.',
'rcnote'                         => "Innoe sighende {{PLURAL:$1|du est s'ùrtima mudàntzia|is ùrtimas '''$1''' mudàntzias}} {{PLURAL:$2|in s'ùrtima die|ne is ùrtimas '''$2''' dies}}; is datos funt agiornaus a  $5, $4.",
'rcnotefrom'                     => "Sas chi sighint sunt sas mudàntzias dae '''$2''' (fintzas a '''$1''').",
'rclistfrom'                     => 'Amosta mudàntzias dae $1',
'rcshowhideminor'                => '$1 acontzos minores',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 usuàrios intraus',
'rcshowhideanons'                => '$1 usuàrios anònimos',
'rcshowhidemine'                 => '$1 acontzos meos',
'rclinks'                        => 'Amosta is ùrtimas $1 mudàntzias fatas ne is ùrtimas $2 dies<br />$3',
'diff'                           => 'dif',
'hist'                           => 'ist',
'hide'                           => 'Cua',
'show'                           => 'Amosta',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'sectionlink'                    => '→',
'rc-change-size'                 => '$1',
'rc-enhanced-expand'             => 'Amosta particulares (esigit JavaScript)',
'rc-enhanced-hide'               => 'Cua particulares',

# Recent changes linked
'recentchangeslinked'          => 'Mudàntzias ligadas',
'recentchangeslinked-title'    => 'Mudàntzias ligadas a "$1"',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-summary'  => "Custa est una lista de is mudàntzias fatas urtimamenti a is pàginas ligadas a cussa ispetzificada. 
Is pàginas de sa [[Special:Watchlist|watchlist tua]] sunt in '''grassetu'''.",
'recentchangeslinked-page'     => 'Nòmene pàgina:',
'recentchangeslinked-to'       => 'Amosta isceti is mudàntzias a is pàginas ligadas a cussa ispetzificada',

# Upload
'upload'            => 'Carriga file',
'uploadbtn'         => 'Carriga file',
'reupload'          => 'Torra a carrigai',
'reuploaddesc'      => 'Torra a su mòdulu pro su carrigamentu.',
'uploadnologin'     => 'Non ses intrau',
'uploadnologintext' => 'Su carrigamentu de files est permìtiu isceti a pustis de àere fatu su [[Special:UserLogin|log in]].',
'uploaderror'       => 'Faddina de carrigamentu',
'uploadtext'        => "Imprea su modulu a suta pro carrigare files nous. 
Pro castiare o chircare is files giai carrigaus, bae a sa [[Special:FileList|lista de is files carrigaus]]. Carrigamentos de files e de noas versiones de files sunt arregistradas in su [[Special:Log/upload|registru de carrigamentu]], is burraduras in su [[Special:Log/delete|registru burraduras]].

Pro insertare unu file aintru de una pàgina, tocat a faghere unu cullegamentu tipu custu:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' pro impreare sa versione cumpleta de su file
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|testo alternativo]]</nowiki></tt>''' pro impreare una versione lada 200 pixel insertada in d'unu box, allinniada a manca e cun 'testu alternativu' comente didascalia
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' pro ingenerare unu cullegamentu a su file chentza de du biri",
'uploadlogpage'     => 'Carrigadas',
'uploadlogpagetext' => 'A suta bi est sa lista de is files carrigaus de retzente.
Càstia sa [[Special:NewFiles|galleria de files nous]] pro una presentatzione prus bisuale.',
'filename'          => 'Nòmene file',
'filedesc'          => 'Ogetu',
'uploadedfiles'     => 'Files carrigaus',
'badfilename'       => 'Il nome del file immagine è stato convertito in "$1".',
'fileexists-thumb'  => "<center>'''File pre-esistente'''</center>",
'successfulupload'  => 'Carrigamentu acabau',
'uploadwarning'     => 'Avvisu de carrigamentu',
'savefile'          => 'Sarva file',
'uploadedimage'     => 'carrigadu "[[$1]]"',

'upload-file-error' => 'Faddina a intru',

# Special:ListFiles
'imgfile'               => 'file',
'listfiles'             => 'Lista de is files',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nòmene',
'listfiles_user'        => 'Usuàriu',
'listfiles_description' => 'Descritzione',
'listfiles_count'       => 'Versiones',

# File description page
'filehist'                  => 'Istòria de su file',
'filehist-help'             => 'Craca unu grupu data/ora pro castiari su file comente si presentada in su tempus indicau.',
'filehist-deleteall'        => 'fùlia totu',
'filehist-current'          => 'currente',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura de sa versione de is $1',
'filehist-nothumb'          => 'Peruna miniatura',
'filehist-user'             => 'Usuàriu',
'filehist-dimensions'       => 'Dimensiones',
'filehist-comment'          => 'Cummentu',
'imagelinks'                => 'Ligant a custu file',
'linkstoimage'              => '{{PLURAL:$1|Sa pàgina chi sighit ligat|$1 Sas pàginas chi sighint ligant}} a custu file:',
'nolinkstoimage'            => 'Peruna pàgina ligat cun custu file.',
'sharedupload'              => 'Custu file benit dae $1 e podet èssere impreau in àteros progetos.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki-linktext' => 'Pàgina che descriet su file',
'uploadnewversion-linktext' => 'Carriga una versione noa de custu file',
'shared-repo-from'          => 'dae $1', # $1 is the repository name

# File reversion
'filerevert-backlink' => '← $1',

# File deletion
'filedelete-backlink' => '← $1',
'filedelete-submit'   => 'Fùlia',
'filedelete-success'  => "Su file '''$1''' est istadu fuliau.",

# MIME search
'download' => 'scàrriga',

# List redirects
'listredirects' => 'Lista de totu is redirects',

# Random page
'randompage' => 'Una pàgina a sorte',

# Statistics
'statistics'              => 'Istatìsticas',
'statistics-header-users' => 'Istatìsticas usuàriu',

'disambiguationspage' => 'Template:Disambigua',

'doubleredirects'     => 'Redirects dòpius',
'doubleredirectstext' => '<b>Attenzione:</b> Questa lista può talvolta contenere dei risultati non corretti. Ciò potrebbe magari accadere perchè vi sono del testo aggiuntivo o dei link dopo il tag #REDIRECT.<br />
Ogni riga contiene i link al primo ed al secondo redirect, oltre alla prima riga di testo del secondo redirect che di solito contiene il "reale" articolo di destinazione, quello al quale anche il primo redirect dovrebbe puntare.',

'brokenredirects'        => 'Redirects isballiaus',
'brokenredirectstext'    => 'Custus redirects ligant cun pàginas chi no esistint.',
'brokenredirects-edit'   => '(acontza)',
'brokenredirects-delete' => '(fùlia)',

'withoutinterwiki-submit' => 'Amosta',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'       => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'            => '$1 {{PLURAL:$1|ligadura|ligaduras}}',
'nmembers'          => '$1 {{PLURAL:$1|cumponente|cumponentes}}',
'nrevisions'        => '$1 {{PLURAL:$1|arrevisione|arrevisiones}}',
'nviews'            => '$1 {{PLURAL:$1|bisura|bisuras}}',
'lonelypages'       => 'Pàginas burdas',
'unusedimages'      => 'Files no impreaus',
'popularpages'      => 'Pàginas populares',
'wantedpages'       => 'Artìculos prus chircados',
'prefixindex'       => 'Ìndighe de is pàginas pro initziales',
'shortpages'        => 'Pàginas crutzas',
'longpages'         => 'Pàginas longas',
'deadendpages'      => 'Pàginas chentza bessida',
'listusers'         => 'Lista usuàrios',
'usercreated'       => 'Creadu su $1 a is $2',
'newpages'          => 'Pàginas noas',
'newpages-username' => 'Nòmene usuàriu:',
'move'              => 'Movi',
'movethispage'      => 'Movi custa pàgina',
'unusedimagestext'  => '<p>Nota che altri siti web, come la {{SITENAME}} internazionale, potrebbero aver messo un link ad una immagine per mezzo di una URL diretta, perciò le immagini potrebbero essere listate qui anche essendo magari in uso.',
'notargettitle'     => 'No ddu est sa pàgina obietivu',
'notargettext'      => "Non hai specificato una pagina o un Utente in relazione al quale eseguire l'operazione richiesta.",
'pager-newer-n'     => '{{PLURAL:$1|1 prus nou|$1 prus nous}}',
'pager-older-n'     => '{{PLURAL:$1|1 prus betzu|$1 prus betzos}}',

# Book sources
'booksources'               => 'Fontes libràrias',
'booksources-search-legend' => 'Kirca fontes libràrias',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Bae',

# Special:Log
'specialloguserlabel'  => 'Usuàriu:',
'speciallogtitlelabel' => 'Tìtulu:',
'log'                  => 'Registros',

# Special:AllPages
'allpages'       => 'Totu is pàginas',
'alphaindexline' => 'da $1 a $2',
'prevpage'       => 'Pàgina in antis ($1)',
'allpagesfrom'   => 'Amosta pàginas a partiri dae:',
'allpagesto'     => 'Amosta is pàginas fintzas a:',
'allarticles'    => 'Totu is pàginas',
'allpagessubmit' => 'Bae',

# Special:Categories
'categories' => 'Categorias',

# Special:LinkSearch
'linksearch'    => 'Acàpius a foras',
'linksearch-ok' => 'Chirca',

# Special:ListUsers
'listusers-submit' => 'Amosta',

# Special:Log/newusers
'newuserlogpage'          => 'Usuàrios nous',
'newuserlog-create-entry' => 'Account usuàriu nou',

# Special:ListGroupRights
'listgrouprights-members'       => '(lista de is cumponentes)',
'listgrouprights-right-display' => '$1 ($2)',

# E-mail user
'mailnologintext' => 'Devi fare il [[Special:UserLogin|login]]
ed aver registrato una valida casella e-mail nelle tue [[Special:Preferences|preferenze]] per mandare posta elettronica ad altri Utenti.',
'emailuser'       => 'E-mail a custu usuàriu',
'emailpagetext'   => "Imprea su mòdulu a suta pro ispedire una missada eletrònica a custu usuàriu. 
S'indiritzu chi as insertadu ne is [[Special:Preferences|preferèntzias usuàriu tuas]] at a parriri comente su chi at ispediu sa e-mail, pro fàghere in modu chi su destinatariu t'arrespundat deretu.",
'defemailsubject' => 'Missada dae {{SITENAME}}',
'noemailtitle'    => 'Perunu indiritzu e-mail',
'noemailtext'     => 'Custu usuàriu no at ispetzificadu un indiritzu e-mail vàlidu.',
'emailsent'       => 'E-mail ispedia',
'emailsenttext'   => 'La tua e-mail è stata inviata.',

# Watchlist
'watchlist'         => 'Sa watchlist mea',
'mywatchlist'       => 'Sa watchlist mea',
'watchlistfor'      => "(pro '''$1''')",
'nowatchlist'       => "Non hai indicato articoli da tenere d'occhio.",
'watchnologin'      => 'No intrau (log in)',
'watchnologintext'  => 'Devi prima fare il [[Special:UserLogin|login]]
per modificare la tua lista di osservati speciali.',
'addedwatch'        => 'Aciùntu a sa watchlist tua',
'addedwatchtext'    => "Sa pàgina \"[[:\$1]]\" est istada aciunta a sa [[Special:Watchlist|watchlist]] tua. 
Is mudàntzias de custa pàgina e de sa pàgina de cuntierras sua ant a bennere elencadas inoe, e su tìtulu at a aparire in '''grassetto''' in sa pàgina de is [[Special:RecentChanges|ùrtimas mudàntzias]] pro du bidere mengius.",
'removedwatch'      => 'Tirau dae sa watchlist tua',
'removedwatchtext'  => 'Sa pàgina  "[[:$1]]" est istada tirada dae sa [[Special:Watchlist|watchlist tua]].',
'watch'             => 'Pone in sa watchlist',
'watchthispage'     => 'Pone ogru a custu artìculu',
'unwatch'           => 'Tira dae sa watchlist',
'unwatchthispage'   => 'Boga custa pàgina dae sa watchlist tua',
'notanarticle'      => 'Custa pàgina no est unu artìculu',
'watchlist-details' => 'Sa watchlist tua cuntènnit {{PLURAL:$1|$1 pàgina|$1 pàginas}}, chentza contare is pàginas de cuntierras.',
'wlshowlast'        => 'Amosta is ùrtimas $1 oras $2 dies $3',
'watchlist-options' => 'Possibilidades subra sa watchlist',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Aciungendi a sa watchlist...',
'unwatching' => 'Tirendi dae sa watchlist...',

'enotif_newpagetext'           => 'Custa est una pàgina noa.',
'enotif_impersonal_salutation' => 'Usuàriu de {{SITENAME}}',

# Delete
'deletepage'            => 'Fùlia pàgina',
'confirm'               => 'Cunfima',
'excontent'             => "su cuntènnidu fiat: '$1'",
'excontentauthor'       => "su cuntènnidu fiat: '$1' (e s'ùnicu contribudori fiat '[[Special:Contributions/$2|$2]]')",
'exblank'               => 'sa pàgina fiat bùida',
'delete-confirm'        => 'Fùlia "$1"',
'delete-backlink'       => '← $1',
'delete-legend'         => 'Fuliare',
'confirmdeletetext'     => "Ses acanta de burrare una pàgina cun totu s'istòria sua.
Pro pregheri, cunfirma ca est intentzioni tua faghere custu, ca connosches is conseguentzias de s'atzione tua, a ca custa est cunforme a is [[{{MediaWiki:Policy-url}}|lìnias polìtigas]].",
'actioncomplete'        => "Acabada s'atzione",
'deletedtext'           => 'Sa pàgina "<nowiki>$1</nowiki>" est istada fuliada.
Càstia su log $2 pro unu registru de is ùrtimas fuliaduras.',
'deletedarticle'        => 'at fuliadu "[[$1]]"',
'dellogpage'            => 'Burraduras',
'dellogpagetext'        => 'Qui di seguito, un elenco delle pagine cancellate di recente.
Tutti i tempi sono in ora del server.',
'reverted'              => 'Torrada a sa versione in antis',
'deletecomment'         => 'Motivu de sa burradura:',
'deleteotherreason'     => 'Àteru motivu o motivu agiuntivu:',
'deletereasonotherlist' => 'Àteru motivu',

# Rollback
'rollback'     => 'Annudda is acontzos',
'rollbacklink' => 'rollback',
'cantrollback' => "Non si podet furriai s'acontzu;
s'ùrtimu contribudori est s'ùnicu autori de custa pàgina.",
'revertpage'   => 'Burradas is mudàntzias de [[Special:Contributions/$2|$2]] ([[User talk:$2|cuntierras]]), torrada a sa versione cabudiana de [[User:$1|$1]]', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Protect
'protectlogpage'              => 'Amparaduras',
'protectedarticle'            => 'at amparau "[[$1]]"',
'modifiedarticleprotection'   => 'at cambiau su livellu de amparadura pro "[[$1]]"',
'protect-backlink'            => '← $1',
'protectcomment'              => 'Cummentu:',
'protectexpiry'               => 'Iscadèntzia:',
'protect_expiry_invalid'      => "S'iscadèntzia est imbàlida.",
'protect_expiry_old'          => 'Iscadentzia giai passada.',
'protect-unchain'             => 'Sblocca is permissos de mòvere',
'protect-text'                => "Custu modulu serbit pro castiari e cambiari su livellu de amparadura de sa pàgina '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Non tenes su permissu pro cambiare is livellus de amparadura de sa pàgina. 
Is impostatziones atuales pro sa pàgina '''$1''':",
'protect-cascadeon'           => "A su momentu custa pàgina est bloccada pro ite est inclùdia {{PLURAL:$1|in sa pàgina indicada a suta, pro sa cali|ne is pàginas indicadas a suta, pro is calis}} est ativa s'amparadura ricorsiva. Est possibile cambiare su livellu de amparadura de custa pàgina, ma is impostatziones derivadas dae s'amparadura ricorsiva non ant a èssere mudadas.",
'protect-default'             => 'Autoritza totu is usuàrios',
'protect-fallback'            => 'Esigit su permissu "$1"',
'protect-level-autoconfirmed' => 'Blocca is usuàrios nobos o non registrados',
'protect-level-sysop'         => 'Isceti aministradores',
'protect-summary-cascade'     => 'ricorsiva',
'protect-expiring'            => 'iscadèntzia: $1 (UTC)',
'protect-cascade'             => 'Ampara totu is pàginas inclùdias in custa (amparadura ricorsiva)',
'protect-cantedit'            => 'Non podes cambiare is livellus de amparadura pro sa pàgina, pro ite non tenes su permissu de acontzare sa pàgina etotu.',
'protect-expiry-options'      => '1 ora:1 hour,1 die:1 day,1 chida:1 week,2 chidas:2 weeks,1 mese:1 month,3 meses:3 months,6 meses:6 months,1 annu:1 year,infinidu:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Permissu:',
'restriction-level'           => 'Livellu de restritzioni:',
'pagesize'                    => '(bytes)',

# Undelete
'undelete'                  => 'Càstia pàginas fuliadas',
'undeletepage'              => 'Càstia e restaura pàginas fuliadas',
'viewdeletedpage'           => 'Càstia pàginas fuliadas',
'undeletepagetext'          => "{{PLURAL:$1|Sa pàgina chi sighit est istada fuliada, ma est ancora in archiviu e podit èssere recuperada|Is pàginas chi sighint sunt istadas fuliadas, ma sunt ancora in archiviu e podint èssere recuperadas}}. S'archiviu podit èssere sbudiau a periodus.",
'undeleterevisions'         => '$1 {{PLURAL:$1|arrevisioni|arrevisionis}} in archìviu',
'undeletehistory'           => 'Restaurende custa pàgina, totu is arrevisiones ant a torrare in sa istòria sua. 
Chi est istada creada una pàgina cun su matessi tìtulu, is arrevisiones recuperadas ant a insertare in sa istoria in antis.',
'undeletebtn'               => 'Ripristina',
'undeletelink'              => 'càstia/riprìstina',
'undeletecomment'           => 'Cummentu:',
'undeletedarticle'          => 'at restauradu "$1"',
'undelete-search-box'       => 'Chirca pàginas fuliadas',
'undelete-search-submit'    => 'Chirca',
'undelete-show-file-submit' => 'Eja',

# Namespace form on various pages
'namespace'      => 'Nòmene-logu:',
'invert'         => 'Fùrria sa seletzione',
'blanknamespace' => '(Printzipale)',

# Contributions
'contributions'       => 'Contributziones usuàriu',
'contributions-title' => 'Contributziones de $1',
'mycontris'           => 'Contributziones meas',
'contribsub2'         => 'Pro $1 ($2)',
'nocontribs'          => 'Nessuna modifica trovata conformemente a questi criteri.', # Optional parameter: $1 is the user name
'uctop'               => '(ùrtimu de sa pàgina)',
'month'               => 'Dae su mese (e in antis):',
'year'                => "Dae s'annu (e in antis):",

'sp-contributions-newbies'  => 'Amosta isceti is contributziones de is accounts nous',
'sp-contributions-blocklog' => 'registru de is bloccos',
'sp-contributions-search'   => 'Chirca contributziones',
'sp-contributions-username' => 'Indiritzu IP o nòmene usuàriu:',
'sp-contributions-submit'   => 'Kirca',

# What links here
'whatlinkshere'            => 'Pàginas chi ligant a custa',
'whatlinkshere-title'      => 'Pàginas chi ligant a "$1"',
'whatlinkshere-page'       => 'Pàgina:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "Sas pàginas chi sighint ligant a '''[[:$1]]''':",
'nolinkshere'              => "Peruna pàgina ligat a '''[[:$1]]'''.",
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
'blockiptext'              => "Usa il modulo sottostante per bloccare l'accesso con diritto di scrittura da uno specifico indirizzo IP. Questo blocco deve essere operato SOLO per prevenire atti di vandalismo, ed in stretta osservanza dei principi tutti della [[{{MediaWiki:Policy-url}}|policy di {{SITENAME}}]]. Il blocco non può in nessun caso essere applicato per motivi ideologici.
Scrivi un motivo specifico per il quale questo indirizzo IP dovrebbe a tuo avviso essere bloccato (per esempio, cita i titoli di pagine eventualmente già oggetto di vandalismo editoriale).",
'ipaddress'                => 'Indiritzu IP:',
'ipadressorusername'       => 'Indiritzu IP o nòmene usuàriu:',
'ipbreason'                => 'Motivu:',
'ipbreasonotherlist'       => 'Àteru motivu',
'ipbsubmit'                => 'Blocca custu usuàriu',
'ipboptions'               => '2 oras:2 hours,1 die:1 day,3 dies:3 days,1 chida:1 week,2 chidas:2 weeks,1 mese:1 month,3 meses:3 months,6 meses:6 months,1 annu:1 year,infinidu:infinite', # display1:time1,display2:time2,...
'badipaddress'             => "L'indirizzo IP indicato non è corretto.",
'blockipsuccesssub'        => 'Blocco eseguito',
'blockipsuccesstext'       => '[[Special:Contributions/$1|$1]] è istadu bloccau. <br />
Abàida sa [[Special:IPBlockList|lista de IP bloccados]] pro bider sas bloccaduras.',
'ipb-unblock-addr'         => 'Sblocca $1',
'unblockip'                => "Sblocca s'usuàriu",
'unblockiptext'            => 'Usa il modulo sottostante per restituire il diritto di scrittura ad un indirizzo IP precedentemente bloccato.',
'ipusubmit'                => 'Boga custu bloccu',
'ipblocklist'              => 'Usuàrios e indiritzos bloccados',
'ipblocklist-submit'       => 'Chirca',
'blocklistline'            => '$1, $2 ha bloccato $3 ($4)',
'blocklink'                => 'blocca',
'unblocklink'              => 'sblocca',
'change-blocklink'         => 'tramuda su bloccu',
'contribslink'             => 'contributziones',
'blocklogpage'             => 'Bloccos de usuàrios',
'blocklogentry'            => 'bloccau [[$1]] pro unu tempu de $2 $3',
'unblocklogentry'          => 'at sbloccau $1',
'block-log-flags-nocreate' => 'creatzione account bloccada',
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

# Move page
'move-page'          => 'Movimentu de $1',
'move-page-backlink' => '← $1',
'move-page-legend'   => 'Movimentu pàgina',
'movepagetext'       => "Cun custu mòdulu podes renomenare una pàgina, movende totu s'istòria sua a sa pàgina noa. 
Su tìtulu bèciu at a diventare una pàgina redirect a su tìtulu nou. 
Podes agiornare automaticamente is redirects ca ligant a su tìtulu originale. 
Chi scioberas de no, assicuradia de cuntrollare pro [[Special:DoubleRedirects| redirect dòpius]] o [[Special:BrokenRedirects|isballiaus]]. 
Ses responsàbile de t'assigurare ca is cullegamentos sighint a puntare  a ue depent puntare.

Annota ca sa pàgina '''non''' s'at a mòvere chi nde esistet giai un'àtera a su tìtulu nou, chi no est chi siat bùida o cun isceti unu redirect a sa bècia e siat chentza acontzos in antis. In casu de movidura isballiada, duncas, si podet torrai a su tìtulu bèciu, ma non podes subraiscrìere una pàgina chi giai esistet.

'''ATENTZIONE:'''
Unu cambiamentu dràsticu podet creare problemas, mescamente a is pàginas prus populares; 
pro preghere depis èssere siguru de àere cumpresu is cunseguèntzias prima de andare a in antis.",
'movepagetalktext'   => "Sa pàgina cuntierras asotziada, chi esistit, at a èssere movida automaticamenti impare a sa pàgina printzipale, '''a parte in custos casos''':
* su movimentu de sa pàgina est tra namespaces diversos;
* in currispondentzia de su tìtulu nou esistit giai una pàgina de cuntierras (non bùida);
* sa casella inoe in basciu no est istata sceberada.

In custus casos, chi boles, depis mòvere a manu su cuntentu de sa pàgina.",
'movearticle'        => 'Movi sa pàgina:',
'movenologin'        => 'Non hai effettuato il login',
'movenologintext'    => 'Depis èssere unu usuàriu registrau e [[Special:UserLogin|intrau]] pro poder mòvere una pàgina',
'newtitle'           => 'Tìtulu nou:',
'move-watch'         => 'Pone ogru a custa pàgina',
'movepagebtn'        => 'Movi sa pàgina',
'pagemovedsub'       => 'Movimentu andau beni',
'movepage-moved'     => '<big>\'\'\'"$1" est istada mòvida a "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'      => 'Una pàgina cun custu nòmene esistit giai, o su nòmene chi as sceberau no est bàlidu.
Pro pregheri scebera un àteru nòmene.',
'talkexists'         => "'''Su movimentu de sa pàgina est andau beni, ma no est istadu possibile moviri sa pàgina de cuntierras proite ndi esistit giai un àtera cun su stessu tìtulu. Pro preghere aciungi tue su cuntestu de sa pàgina becia.'''",
'movedto'            => 'mòvida a',
'movetalk'           => 'Movi puru sa pàgina de cuntierras',
'1movedto2'          => 'at mòvidu [[$1]] a [[$2]]',
'1movedto2_redir'    => 'at mòvidu [[$1]] a [[$2]] subra redirect',
'movelogpage'        => 'Moviduras',
'movereason'         => 'Motivu:',
'revertmove'         => 'fùrria',

# Export
'export'          => 'Esporta pàginas',
'export-download' => 'Sarva comente file',

# Namespace 8 related
'allmessagesname' => 'Nòmene',

# Thumbnails
'thumbnail-more' => 'Amannia',

# Special:Import
'import-comment' => 'Cummentu:',

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
Pro pregheri, prima de sarvari càstia s'antiprima",
'tooltip-ca-addsection'           => 'Incumintza una setzione noa',
'tooltip-ca-viewsource'           => 'Sa pàgina est amparada.
Podes castiare sa mitza sua',
'tooltip-ca-history'              => 'Versiones passadas de custa pàgina',
'tooltip-ca-protect'              => 'Ampara custa pàgina',
'tooltip-ca-delete'               => 'Fùlia custa pàgina',
'tooltip-ca-move'                 => 'Movi custa pàgina',
'tooltip-ca-watch'                => 'Aciungi custa pàgina a sa watchlist tua',
'tooltip-ca-unwatch'              => 'Tira custa pàgina da sa watchlist tua',
'tooltip-search'                  => 'Chirca a intru de {{SITENAME}}',
'tooltip-search-go'               => 'Bae a una pàgina cun custu nòmene, chi esistit',
'tooltip-search-fulltext'         => 'Kirca custu testu in sas pàginas',
'tooltip-n-mainpage'              => 'Vìsita sa pàgina printzipale',
'tooltip-n-portal'                => 'Descritzioni de su progetu, ita podes faghere, ainnui agatas cosas',
'tooltip-n-currentevents'         => 'Informatziones subra acuntèssias atuales',
'tooltip-n-recentchanges'         => 'Sa lista de is ùrtimas mudàntzias de su giassu',
'tooltip-n-randompage'            => 'Mosta una pàgina a sorte',
'tooltip-n-help'                  => 'Pàginas de agiudu',
'tooltip-t-whatlinkshere'         => 'Lista de totu is pàginas chi ligant a custa',
'tooltip-t-recentchangeslinked'   => 'Lista de is ùrtimas mudàntzias de is pàgina chi ligant a custa',
'tooltip-feed-rss'                => 'RSS feed pro custa pàgina',
'tooltip-feed-atom'               => 'Atom feed pro custa pàgina',
'tooltip-t-contributions'         => 'Càstia sa lista de is contributziones de custu usuàriu',
'tooltip-t-emailuser'             => 'Ispedi una missada eletronica a custu usuàriu',
'tooltip-t-upload'                => 'Carriga file multimediale',
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
'tooltip-diff'                    => 'Amosta is mudàntzias chi as fatu a su testu',
'tooltip-compareselectedversions' => 'Càstia is diferèntzias de is duas versiones scioberadas de custa pàgina',
'tooltip-watch'                   => 'Aciungi custa pàgina a sa watchlist tua',
'tooltip-recreate'                => 'Torra a creare sa pàgina mancari siat istada fuliada',
'tooltip-upload'                  => 'Cumentza a carrigari',
'tooltip-rollback'                => '"Rollback" annudda is mudàntzias de custa pàgina fatas dae s\'ùrtimu contribudori',
'tooltip-undo'                    => '"Annudda" fùrriat custu acontzu e aberit su mòdulu de acontzu comente antiprima.
Podes aciùngiri unu motivu in s\'ogetu de s\'acontzu.',

# Browsing diffs
'previousdiff' => '← Acontzu in antis',
'nextdiff'     => 'Acontzu in fatu →',

# Media information
'widthheight'          => '$1×$2',
'file-info-size'       => '($1 × $2 pixels, mannesa de su file: $3, tipu de MIME: $4)',
'file-nohires'         => '<small>Non si tenent risolutziones prus artas.</small>',
'svg-long-desc'        => '(file in formadu SVG, mannesa nominale $1 × $2 pixel, mannesa de su file: $3)',
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
'metadata-expand'   => 'Amosta particolaris',
'metadata-collapse' => 'Cua particolaris',
'metadata-fields'   => "Is campus de is metadatos EXIF listaus in custu messàgiu ant a èssere amostaus in sa pàgina de s'immàgine candu sa tabella de is metadatos est presentada in forma breve. Pro impostatzione predefinia, is àteros campus ant a èssere cuaus. 
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength", # Do not translate list items

# EXIF tags
'exif-exposuretime-format' => '$1 s ($2)',
'exif-fnumber-format'      => 'f/$1',
'exif-flash'               => 'Flash',
'exif-focallength-format'  => '$1 mm',

# EXIF attributes
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',

'exif-subjectdistance-value' => '$1 metros',

# External editor support
'edit-externally'      => 'Acontza custu file usendi unu programma de foras',
'edit-externally-help' => '(Pro àteras informatziones càstia is [http://www.mediawiki.org/wiki/Manual:External_editors istrutziones])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'totu',
'imagelistall'     => 'totu',
'watchlistall2'    => 'totu',
'namespacesall'    => 'totu',
'monthsall'        => 'totu',

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
'autoredircomment' => 'Redirect a sa pàgina [[$1]]',
'autosumm-new'     => "Pàgina creada cun '$1'",

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Watchlist editor
'watchlistedit-raw-titles' => 'Tìtulos:',

# Watchlist editing tools
'watchlisttools-view' => 'Càstia mudàntzias de importu',
'watchlisttools-edit' => 'Càstia e acontza sa watchlist',
'watchlisttools-raw'  => 'Acontza sa watchlist dae su testu',

# Signatures
'timezone-utc' => 'UTC',

# Special:Version
'version'                  => 'Versione', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'Pàginas ispetziales',
'version-software-version' => 'Versione',

# Special:FilePath
'filepath-page' => 'Nòmene de su file:',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Kirca',

# Special:SpecialPages
'specialpages' => 'Pàginas ispetziales',

);
