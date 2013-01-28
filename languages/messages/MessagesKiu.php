<?php
/** Kirmanjki (Kırmancki)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Erdemaslancan
 * @author Mirzali
 */

$fallback = 'tr';

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Xısusi',
	NS_TALK             => 'Werênayış',
	NS_USER             => 'Karber',
	NS_USER_TALK        => 'Karber_werênayış',
	NS_PROJECT_TALK     => '$1_werênayış',
	NS_FILE             => 'Dosye',
	NS_FILE_TALK        => 'Dosya_werênayış',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_werênayış',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_werênayış',
	NS_HELP             => 'Peşti',
	NS_HELP_TALK        => 'Peşti_werênayış',
	NS_CATEGORY         => 'Kategoriye',
	NS_CATEGORY_TALK    => 'Kategori_werênayış',
);

$namespaceAliases = array(
	// Turkish namespace names.
	'Medya'              => NS_MEDIA,
	'Özel'               => NS_SPECIAL,
	'Tartışma'           => NS_TALK,
	'Kullanıcı'          => NS_USER,
	'Kullanıcı_mesaj'    => NS_USER_TALK,
	'$1_tartışma'        => NS_PROJECT_TALK,
	'Dosya'              => NS_FILE,
	'Dosya_tartışma'     => NS_FILE_TALK,
	'MediaWiki_tartışma' => NS_MEDIAWIKI_TALK,
	'Şablon'             => NS_TEMPLATE,
	'Şablon_tartışma'    => NS_TEMPLATE_TALK,
	'Yardım'             => NS_HELP,
	'Yardım_tartışma'    => NS_HELP_TALK,
	'Kategori'           => NS_CATEGORY,
	'Kategori_tartışma'  => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline' => 'Bınê girey de xete bıonce:',
'tog-justify' => 'Paragrafu ayar ke',
'tog-hideminor' => 'Vurnayisunê senıku pela vurnayisunê peyênu de wedare',
'tog-hidepatrolled' => 'Vurnayisunê qontrolkerdu pela vurnayisê peyêni de wedare',
'tog-newpageshidepatrolled' => 'Pelunê qontrolkerdu lista pelunê newu de wedare',
'tog-extendwatchlist' => 'Lista şêrkerdene hira bıke ke vurnayişi pêro bıasê, teyna tewr peyêni nê',
'tog-usenewrc' => 'Pele be vurnayisunê grube de vurnayisê peyêni u lista şêrkerdişi (JavaScript lazımo)',
'tog-numberheadings' => 'Sernustu be ho numra cı sane',
'tog-showtoolbar' => 'Gozagunê hacetunê vurnayişi bıasne (JavaScript lazımo)',
'tog-editondblclick' => 'Pê dı rey teqnayene pele sero bıguriye (JavaScript lazımo)',
'tog-editsection' => 'Vurnayena qısımi ebe gireyunê [bıvurne] ra feal ke',
'tog-editsectiononrightclick' => 'Qısımu be teqnayena serrêze ra ebe gozaga raste bıvurne (JavaScript lazımo)',
'tog-showtoc' => 'Tablowê tedeestewu bıasne (de pelunê be hirê sernustu ra jêdêri de)',
'tog-rememberpassword' => 'Parola mı nê cıcêrawoği de bia ho viri (serba tewr jêde $1 {{PLURAL:$1|roze|rozu}}).',
'tog-watchcreations' => 'Pelê ke mı afernê u dosyê ke mı bar kerdê lista mına şêrkerdişi ke',
'tog-watchdefault' => 'Pel u dosyê ke mı vurnê lista mına şêrkerdişi ke',
'tog-watchmoves' => 'Pel u dosyê ke mı kırısnê lista mına şêrkerdişi ke',
'tog-watchdeletion' => 'Pel u dosyê ke mı esterıtê, lista mına şêrkerdişi ke',
'tog-minordefault' => 'Vurnayisunê ho pêrune ‘vurnayiso qızkek’ nisan bıde',
'tog-previewontop' => 'Verqayti pela nustene ser de bıasne',
'tog-previewonfirst' => 'Vurnayiso verên de verqayti tım bıasne',
'tog-nocache' => 'Pelunê cıfeteliyawoği mia ho viri',
'tog-enotifwatchlistpages' => 'Zû pele ya ki dosyawa ke lista mına şêrkerdişi de vurnê mı rê e-poste bırusne',
'tog-enotifusertalkpages' => 'Pela mına hurênayişi ke vurnê mı rê e-poste bırusne',
'tog-enotifminoredits' => 'Vurnayisunê qızkekunê pelun u dosyu de ki mı rê e-poste bırusne',
'tog-enotifrevealaddr' => 'Adresa e-postê mı postê xeberu de bıasne',
'tog-shownumberswatching' => 'Amorê karberunê şêrkerdoğu bıasne',
'tog-oldsig' => 'İmza mewcude:',
'tog-fancysig' => 'İmza rê mamelê wikimeqaley bıke (bê girewo otomatik)',
'tog-externaleditor' => 'Editorê teberi standart bıgurene (teyna serba ekspertuno, komputerê sıma de ayarê xusışiy lazımê. [//www.mediawiki.org/wiki/Manual:External_editors Melumato jêdêr.])',
'tog-externaldiff' => 'Têversanaene pê programê teberi vıraze (teyna serba ekspertuno, komputerê sıma de ayarê xısuşiy lazımê. [//www.mediawiki.org/wiki/Manual:External_editors Melumato jêdêr.])',
'tog-showjumplinks' => 'Girê "so"y feal ke',
'tog-uselivepreview' => 'Verqayto cande bıgurene (JavaScript) (hona cerrebnayene dero)',
'tog-forceeditsummary' => 'Mı ke xulasa kerde cı vira, hay be mı ser de',
'tog-watchlisthideown' => 'Vurnayisunê mı lista mına şêrkerdişi de wedare',
'tog-watchlisthidebots' => 'Vurnayisunê boti lista mına şêrkerdişi de wedare',
'tog-watchlisthideminor' => 'Vurnayisunê qızkeku lista mına şêrkerdişi de wedare',
'tog-watchlisthideliu' => 'Lista şêrkerdişi ra vurnayisunê karberunê cıkotu wedare',
'tog-watchlisthideanons' => 'Lista şêrkerdişi ra vurnayisunê karberunê anonimu wedare',
'tog-watchlisthidepatrolled' => 'Lista şêrkerdişi ra vurnayisunê qontrolkerdu wedare',
'tog-ccmeonemails' => 'E-postunê ke ez karberunê binu rê rusnenu, mı rê kopya inu bırusne',
'tog-diffonly' => 'Qıyasê versiyonu de tek ferqu bıasne, pela butıne nê',
'tog-showhiddencats' => 'Kategoriyunê dızdêni bıasne',
'tog-norollbackdiff' => 'Peyserardene ra dıme ferqi caverde',

'underline-always' => 'Tım',
'underline-never' => 'Qet',
'underline-default' => 'Cild ya ki cıfeteliyayoğo hesebiyaye',

# Font style option in Special:Preferences
'editfont-style' => 'Warê vurnayena terzê nustey:',
'editfont-default' => 'Fereziya cıfeteliyawoği',
'editfont-monospace' => 'Terzê nustê sabıtcaguretoği',
'editfont-sansserif' => 'Babetê Sans-serifi',
'editfont-serif' => 'Babetê serifi',

# Dates
'sunday' => 'Bazar',
'monday' => 'Dıseme',
'tuesday' => 'Şêseme',
'wednesday' => 'Çarseme',
'thursday' => 'Phonseme',
'friday' => 'Yene',
'saturday' => 'Seme',
'sun' => 'Baz',
'mon' => 'Dıs',
'tue' => 'Şês',
'wed' => 'Çrs',
'thu' => 'Pho',
'fri' => 'Yen',
'sat' => 'Sem',
'january' => 'Çele',
'february' => 'Gucige',
'march' => 'Mart',
'april' => 'Nisane',
'may_long' => 'Gulane',
'june' => 'Hezirane',
'july' => 'Temuze',
'august' => 'Tebaxe',
'september' => 'Eylule',
'october' => 'Keşkelun',
'november' => 'Teşrine',
'december' => 'Gağan',
'january-gen' => 'Çeley',
'february-gen' => 'Gucige',
'march-gen' => 'Marti',
'april-gen' => 'Nisane',
'may-gen' => 'Gulane',
'june-gen' => 'Hezirani',
'july-gen' => 'Temuze',
'august-gen' => 'Tebaxe',
'september-gen' => 'Eylule',
'october-gen' => 'Keşkeluni',
'november-gen' => 'Teşrine',
'december-gen' => 'Gağani',
'jan' => 'Çel',
'feb' => 'Guc',
'mar' => 'Mar',
'apr' => 'Nis',
'may' => 'Gul',
'jun' => 'Hez',
'jul' => 'Tem',
'aug' => 'Teb',
'sep' => 'Eyl',
'oct' => 'Keş',
'nov' => 'Teş',
'dec' => 'Gağ',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategoriye|Kategoriy}}',
'category_header' => 'Pelê ke kategoriya "$1" derê',
'subcategories' => 'Kategoriyê bınêni',
'category-media-header' => 'Medyawa ke kategoriya "$1" dera',
'category-empty' => "''Na kategoriye de hona qet nustey ya ki medya çinê.''",
'hidden-categories' => '{{PLURAL:$1|Kategoriya wedariyaiye|Kategoriyê wedariyaey}}',
'hidden-category-category' => 'Kategoriyê wedariyaey',
'category-subcat-count' => '{{PLURAL:$2|Na kategoriye de ana kategoriya bınêne esta.|Na kategoriye de $2 ra pêro pia, {{PLURAL:$1|ana kategoriya bınêne esta|ani $1 kategoriyê bınêni estê.}}, be $2 ra pia.}}',
'category-subcat-count-limited' => 'Na kategoriye de {{PLURAL:$1|ana kategoriya bınêne esta|ani $1 kategoriyê bınêni estê}}.',
'category-article-count' => '{{PLURAL:$2|Na kategoriye de teyna ana pele esta.|Na kategoriye de $2 ra pêro pia, {{PLURAL:$1|ana pele esta|ani $1 peli estê.}}, be $2 ra pêro pia}}',
'category-article-count-limited' => '{{PLURAL:$1|Ana pele kategoriya peyêne dera|Ani $1 peli kategoriya peyêne derê}}.',
'category-file-count' => '{{PLURAL:$2|Na kategoriye de teyna ana dosya esta.|Na kategoriye de $2 ra pêro pia, {{PLURAL:$1|ana dosya esta|ani $1 dosyey estê.}}}}',
'category-file-count-limited' => '{{PLURAL:$1|Ana dosya kategoriya peyêne dera|Ani $1 dosyey kategoriya peyêne derê}}.',
'listingcontinuesabbrev' => 'dewam',
'index-category' => 'Pelê endeksıni',
'noindex-category' => 'Pelê bêendeksıni',
'broken-file-category' => 'Peli be gıreunê dosyeunê sıkıtau',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about' => 'Heqa',
'article' => 'Pela tedeesteyu',
'newwindow' => '(zerrê pençerê dê newey de beno ra)',
'cancel' => 'Bıtexelne',
'moredotdotdot' => 'Jêde...',
'mypage' => 'Pela mı',
'mytalk' => 'Hurênayişê mı',
'anontalk' => "Pela hurênaisê ni ''IP''y",
'navigation' => 'Pusula',
'and' => '&#32;u',

# Cologne Blue skin
'qbfind' => 'Bıvêne',
'qbbrowse' => 'Çım ra viarne',
'qbedit' => 'Bıvurne',
'qbpageoptions' => 'Na pele',
'qbpageinfo' => 'Gire',
'qbmyoptions' => 'Pelê mı',
'qbspecialpages' => 'Pelê xısusiy',
'faq' => 'PZP (Persê ke zaf perşinê)',
'faqpage' => 'Project:PZP',

# Vector skin
'vector-action-addsection' => 'Mewzu ilawe ke',
'vector-action-delete' => 'Bestere',
'vector-action-move' => 'Bere',
'vector-action-protect' => 'Bısevekne',
'vector-action-undelete' => 'Esterıtene peyser bıcê',
'vector-action-unprotect' => 'Rake',
'vector-simplesearch-preference' => 'Tewsiyunê cıcêraişiê raverberdu rake (Teyna vector skin de)',
'vector-view-create' => 'Vıraze',
'vector-view-edit' => 'Bıvurne',
'vector-view-history' => 'Tarixi basne',
'vector-view-view' => 'Bıwane',
'vector-view-viewsource' => 'Çımey bıvêne',
'actions' => 'Kerdey',
'namespaces' => 'Caê namey',
'variants' => 'Varyanti',

'errorpagetitle' => 'Xeta',
'returnto' => 'Peyser so $1.',
'tagline' => '{{SITENAME}} ra',
'help' => 'Phoşti',
'search' => 'Cı feteliye',
'searchbutton' => 'Cı feteliye',
'go' => 'So',
'searcharticle' => 'So',
'history' => 'Tarixê pele',
'history_short' => 'Tarix',
'updatedmarker' => 'cıkotena mına peyêne ra dıme biya rocaniye',
'printableversion' => 'Asaena çapkerdene',
'permalink' => 'Gireo daimki',
'print' => 'Çap ke',
'view' => 'Bıvêne',
'edit' => 'Bıvurne',
'create' => 'Vıraze',
'editthispage' => 'Na pele bıvurne',
'create-this-page' => 'Na pele baferne',
'delete' => 'Bestere',
'deletethispage' => 'Na pele bestere',
'undelete_short' => '{{PLURAL:$1|Jü vurnaişi|$1 Vurnaisu}} mestere',
'viewdeleted_short' => '{{PLURAL:$1|Jü vurnaiso esterıte|$1 Vurnaisunê esterıtu}} basne',
'protect' => 'Bısevekne',
'protect_change' => 'bıvurne',
'protectthispage' => 'Na pele bısevekne',
'unprotect' => 'Rake',
'unprotectthispage' => 'Na pele rake',
'newpage' => 'Pela newiye',
'talkpage' => 'Na pele sero hurêne',
'talkpagelinktext' => 'Hurênayis',
'specialpage' => 'Pela xısusiye',
'personaltools' => 'Hacetê keşi',
'postcomment' => 'Qısımo newe',
'articlepage' => 'Pela zerreki bıvêne',
'talk' => 'Hurênais',
'views' => 'Asaişi',
'toolbox' => 'Qutiya hacetu',
'userpage' => 'Pela karberi bıvêne',
'projectpage' => 'Pela procey bıvêne',
'imagepage' => 'Pela dosya bıvêne',
'mediawikipage' => 'Pela mesacu bıvêne',
'templatepage' => 'Pela nımunu bıvêne',
'viewhelppage' => 'Pela phoşti bıvêne',
'categorypage' => 'Pela kategoriye bıvêne',
'viewtalkpage' => 'Hurênaişi bıvêne',
'otherlanguages' => 'Zonunê binu de',
'redirectedfrom' => '($1 ra ard)',
'redirectpagesub' => 'Pela berdene',
'lastmodifiedat' => 'Na pele tewr peyên roca $2, $1 de biya rocaniye.',
'viewcount' => 'Na pele {{PLURAL:$1|jü rae|$1 rey}} vêniya.',
'protectedpage' => 'Pela sevekiyaiye',
'jumpto' => 'So be:',
'jumptonavigation' => 'pusula',
'jumptosearch' => 'cı feteliye',
'view-pool-error' => 'Qaytê qusıri mekerê, serverê ma nıka jêde bar gureto ho ser.
Hedê ho ra jêde karberi kenê ke şêrê na pele bıkerê.
Sıma rê zamet, tenê vınderê, hata ke reyna kenê ke na pele kuyê.

$1',
'pool-timeout' => 'Kilıtbiyaene sero waxtê vınetişi',
'pool-queuefull' => 'Rêza hewze pırra',
'pool-errorunknown' => 'Xeta nêzanıtiye',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Heqa {{SITENAME}} de',
'aboutpage' => 'Project:Heqa',
'copyright' => 'Zerrek bınê $1 dero.',
'copyrightpage' => '{{ns:project}}:Telifheqiye',
'currentevents' => 'Veng u vaz',
'currentevents-url' => 'Project:Veng u vaz',
'disclaimers' => 'Diwanê mesulêti',
'disclaimerpage' => 'Project:Diwanê mesuliyetê bıngey',
'edithelp' => 'Phoştdariya vurnaişi',
'edithelppage' => 'Help:Pele çıturi vurnina',
'helppage' => 'Help:Tedeestey',
'mainpage' => 'Pela Seri',
'mainpage-description' => 'Pela seri',
'policy-url' => 'Project:Poliça',
'portal' => 'Portalê kome',
'portal-url' => 'Project:Portalê kome',
'privacy' => 'Madê dızdêni',
'privacypage' => 'Project:Madê dızdêni',

'badaccess' => 'Xeta desturi',
'badaccess-group0' => 'Faeliyeto ke sıma wazenê, sıma nêşikinê ney raver berê.',
'badaccess-groups' => 'No faeliyet teyna, keso ke {{PLURAL:$2|grube|grubu ra jüye}}: $1 dero, serba dino.',

'versionrequired' => 'MediaWiki ra vurnaisê $1 lazımo',
'versionrequiredtext' => 'MediaWiki ra vurnaisê $1 lazımo ke na pele bıgurenê. Qaytê [[Special:Version|vurnaisê pele]] ke.',

'ok' => 'Temam',
'pagetitle-view-mainpage' => '',
'retrievedfrom' => '"$1" ra ard',
'youhavenewmessages' => 'Yê sıma $1 ($2) esto.',
'newmessageslink' => 'mesacê newey',
'newmessagesdifflink' => 'vurnaiso peyên',
'youhavenewmessagesmulti' => '$1 de mesacê sımaê newey estê',
'editsection' => 'bıvurne',
'editsection-brackets' => '[$1]',
'editold' => 'bıvurne',
'viewsourceold' => 'çımey bıvêne',
'editlink' => 'bıvurne',
'viewsourcelink' => 'çıme bıvêne',
'editsectionhint' => 'Qısımi bıvurne: $1',
'toc' => 'Tedeestey',
'showtoc' => 'basne',
'hidetoc' => 'bınımne',
'collapsible-collapse' => 'Kılmever ke',
'collapsible-expand' => 'Verın ke',
'thisisdeleted' => '$1i basne ya ki peyser biya?',
'viewdeleted' => '$1 basne?',
'restorelink' => '{{PLURAL:$1|jü vurnaiso esterıte|$1 vurnaisê esterıtey}}',
'feedlinks' => 'Cı de:',
'feed-invalid' => 'Aboneo nêvêrdoğ.',
'feed-unavailable' => 'Weyikerdena sendikasyoni çina',
'site-rss-feed' => '$1 Weyikerdena RSSi',
'site-atom-feed' => '$1 Weyikerdena Atomi',
'page-rss-feed' => '"$1" Weyikerdena RSSi',
'page-atom-feed' => '"$1" Weyikerdena Atomi',
'feed-atom' => 'Atom',
'feed-rss' => 'RSS',
'red-link-title' => '$1 (pele çina)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Pele',
'nstab-user' => 'Pela karberi',
'nstab-media' => 'Pela medya',
'nstab-special' => 'Pela xase',
'nstab-project' => 'Pela procey',
'nstab-image' => 'Dosya',
'nstab-mediawiki' => 'Mesac',
'nstab-template' => 'Nımune',
'nstab-help' => 'Pela phoşti',
'nstab-category' => 'Kategoriye',

# Main script and global functions
'nosuchaction' => 'Çiyo de nianên çino',
'nosuchactiontext' => "Faeliyeto ke hetê URL ra tesnif biyo, nêvêreno.
To, beno ke URL ğelet nusno ya ki tı girêo de ğelet dıma şiya.
No mısneno ke, ''software''o ke terefê {{SITENAME}} ra gurenino, jü ğeleliye tede esta.",
'nosuchspecialpage' => 'Pela de xususiya nianêne çina',
'nospecialpagetext' => '<strong>Sıma pela xususiya de nêvêrdiye kerde ra.</strong>

Jü lista pelunê vêrdoğu bınê [[Special:SpecialPages|{{int:specialpages}}]] de vênina.',

# General errors
'error' => 'Xeta',
'databaseerror' => 'Xeta panga daeyu',
'dberrortext' => 'Jü xeta persê cumla panga daeyu de amê meydan.
Heni aseno ke na xeta nustene de esta.
Persê panga daeyuno peyên nia bi:
<blockquote><tt>$1</tt></blockquote>
ebe gurê zerrê "<tt>$2</tt>"y ra.
Panga daeyu xetawa ke asnena "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Rêza cumleunê panga daeyu de jü xeta amê meydan.
Heni aseno ke na xeta nustene de esta.
Persê panga daeyuno peyên nia bi:
"$1"
Fonksiyono ke gureniyo "$2".
Panga daeyu xetawa ke asnena "$3: $4"',
'laggedslavemode' => 'Teme: Beno ke vurnaisê peyêni pele de niyê.',
'readonly' => 'Panga daeyu kılit kerdiya',
'enterlockreason' => 'Serba kılit-kerdişi jü sebeb bıde ke, kılitkerdis texminen key beno ra',
'readonlytext' => 'Panga daeyu nustunê newun u vurnaisunê binu rê kılita, seba ke bena pak, hama badêna oncia normal gurina.

İdarekeru ra kami ke kılit kerda, na tesrih aseno: $1',
'missing-article' => "Panga, pela be namê \"\$1\" \$2 ke gunê bıbo, nêdiye.

Na belkia serba jü vurnaiso khan ya ki tarixê girê jü pelge esteriya.

Eke nia niyo, belkia ''software''i de jü xeta esta.
Kerem kerê, nae be namê ''URL''i jü [[Special:ListUsers/sysop|karber]]i ra vazê.",
'missingarticle-rev' => '(tekrar diyais#: $1)',
'missingarticle-diff' => '(Ferq: $1, $2)',
'readonly_lag' => "Panga daeyu otomatikman qapan biye ''slave-database-servers''i ra be hata ''master''i",
'internalerror' => 'Xeta zerrey',
'internalerror_info' => 'Xeta zerrey: $1',
'fileappenderrorread' => 'İlawe ke bi "$1" nêşikiya bıwaniyo.',
'fileappenderror' => 'Dosya "$1"ine dosya "$2"ine ser nêbena.',
'filecopyerror' => 'Dosya "$1"i kopya nêbiye be dosya "$2".',
'filerenameerror' => 'Namê dosya "$1"i nêvuriya be dosya "$2".',
'filedeleteerror' => 'Dosya "$1"i nêesteriye.',
'directorycreateerror' => 'İndeksê "$1"i nêvıraşt.',
'filenotfound' => 'Dosya "$1"i nêvêniye.',
'fileexistserror' => 'Sıma nêşikinê dosya "$1" de bınusê: dosya esta',
'unexpected' => 'Qimeto nêpawıte: "$1"="$2".',
'formerror' => 'Xeta: form niard',
'badarticleerror' => 'No faeliyet na pele de nêvıracino.',
'cannotdelete' => 'Pela ya ki dosya "$1"  nêesteriye.
Beno ke, verênde terefê kesê de bini ra esteriya.',
'badtitle' => 'Sernameo xırabın',
'badtitletext' => "Sernamê pela ke sıma wast, nêvêrde, thal, ya girê mabênê zoni ğelet ya ki sernamê mabênê ''wiki'' bi.
Beno ke, tede jü ya ki jêdê isareti estê ke sernameu de nêgurinê.",
'perfcached' => 'Ni daey nımıteyê u beno ke rocaney niyê. A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.',
'perfcachedts' => 'Ni daey nımıteyê, u tewr peyên $1 de biyê rocaniy. A maximum of {{PLURAL:$4|one result is|$4 results are}} available in the cache.',
'querypage-no-updates' => 'Rocane-biyaena na pele nıka cadaiyê.
Daey ita nıka newe nêbenê.',
'wrong_wfQuery_params' => 'Parametreo ğelet serba wfPers()<br />
Fonksiyon: $1<br />
Pers: $2',
'viewsource' => 'Çımey bıvêne',
'actionthrottled' => 'Faeliyet xenekıt',
'actionthrottledtext' => "Berqestiya tedbirê ''anti-spam''i ra vırastena ni faeliyeti rê sıma zafê rey zemano senık de  sindor viarna ra.
Kerem kerê, deqêna oncia bıcerrebnê.",
'protectedpagetext' => 'Na pele vurnaisu rê qapan biya.',
'viewsourcetext' => 'Sıma şikinê çımê na pele bıvênê u kopya kerê:',
'protectedinterface' => "Na pele ''software'' rê meqalunê caunê bırnau dana, u qapana ke suıstımalu rê engel bo.",
'editinginterface' => "'''Teme:''' Sıma hao jü pela ke serba nustê meqalunê caunê bırnau dana, vurnenê.
Vurnaisê na pele karberunê binu rê serpela karberi kena ke bıasno.
Serba çarnaişi, yardımê [//translatewiki.net/wiki/Main_Page?setlang=kiu translatewiki.net]i ra procêdoskerdene rê diqet kerê.",
'sqlhidden' => '(Persê SQLi nımıteo)',
'cascadeprotected' => 'Na pele esterıtene ra sıtar biya, çıke na zerrê {{PLURAL:$1|pela ke|pelunê ke}} dera/derê be "cascading" opsiyoni kılit biya, $2 de bena ra.',
'namespaceprotected' => "'''$1''' ''namespace'' de desturê sıma be vurnaisê pelu çino.",
'ns-specialprotected' => 'Pelê xususi nêvurrinê.',
'titleprotected' => "No sername terefê [[User:$1|$1]]i ra, afernaene ra sıtar biyo.
Sebebê ho ''$2'' dero.",

# Virus scanner
'virus-badscanner' => "Sıkılo xırabın: ''scanner''ê ''virus''ê nêzanıtey: ''$1''",
'virus-scanfailed' => "''scan'' nêbi (code $1)",
'virus-unknownscanner' => "''antivirus''o nêzanıte:",

# Login and logout pages
'logouttext' => "'''Sıma nıka cı ra veciyê.'''

Sıma şikinê dızdêni {{SITENAME}} de dewam kerê, ya jê eyni karberi ya ki jê jüyê de bini [[Special:UserLogin|oncia cıkuyê]].
Beno ke taê peli sıma hona cıkote asnenê, hata ke sıma ''browser cache''ê ho kerd pak.",
'welcomecreation' => '== Xêr amê, $1! ==
Hesabê sıma vıraciya.
Vurnaena [[Special:Preferences|melumatanê {{SITENAME}}]] ho vira mekerê.',
'yourname' => 'Namê karberi:',
'yourpassword' => 'Parola:',
'yourpasswordagain' => 'Parola tekrar ke:',
'remembermypassword' => 'Cıkotena mı na komputeri de bia ho viri (seba tewr jêde $1 {{PLURAL:$1|roze|rozu}})',
'securelogin-stick-https' => 'Cıkotene ra dıme HTTPS rê giredae bımane',
'yourdomainname' => 'Bandıra sıma:',
'externaldberror' => 'Cıfeteliyaisê naskerdene de ya xeta esta ya ki tebera vırastena hesabê sıma rê destur çino.',
'login' => 'Cı kuye',
'nav-login-createaccount' => 'Cı kuye / hesab vıraze',
'loginprompt' => "Cıkotena {{SITENAME}} rê gunê ''cookies'' akerdey bê.",
'userlogin' => 'Cı kuye / hesab vıraze',
'userloginnocreate' => 'Cı kuye',
'logout' => 'Veciye',
'userlogout' => 'Veciye',
'notloggedin' => 'Cı nêkota',
'nologin' => "Hesabê sıma çino? '''$1'''.",
'nologinlink' => 'Jü hesab rake',
'createaccount' => 'Hesab vıraze',
'gotaccount' => "Hesabê sıma ke esto? '''$1'''.",
'gotaccountlink' => 'Cı kuye',
'userlogin-resetlink' => 'Melumatê cıkewtışi xo vira kerdê?',
'createaccountmail' => 'e-poste sera',
'createaccountreason' => 'Sebeb:',
'badretype' => 'Parola sıma nêvêrena.',
'userexists' => 'No namê karberi guretiyo.
Kerem ke, namêna weçine.',
'loginerror' => 'Xeta cıkotene',
'createaccounterror' => 'Hesab nêvırajino: $1',
'nocookiesnew' => 'Hesabê karberi vıraziya, hama sıma nêşikiyay cı kuyê.
Serba rakerdena hesabi çerezê {{SITENAME}}i gurêninê.
Sıma çerezi qapan kerdi.
Ravêri ine rakerê, dıma be name u parola sımawa newiye cı kuyê.',
'nocookieslogin' => 'Serba rakerdena hesabi çerezê {{SITENAME}}i gurêninê.
Sıma çerezi qapan kerdi.
Ravêri ine rakerê u reyna bıcerrebnê.',
'noname' => 'Ebe namê do vêrdoği ra cınêkota.',
'loginsuccesstitle' => 'Cıkotene biye ra',
'loginsuccess' => "'''Sıta {{SITENAME}} de ebe namê karberi \"\$1\" kota cı.'''",
'nosuchuser' => 'Ebe namê "$1"i jü karber çino.
Nustena namunê karberu de herfa pil u qıze rê diqet kerê.
Nustena ho qonrol kerê, ya ki [[Special:UserLogin/signup|jü hesabo newe rakerê]].',
'nosuchusershort' => 'Karberê do ebe namê "$1" çino.
Nustena cı qontrol ke.',
'nouserspecified' => 'Gunê namê jü karberi bıdekernê.',
'login-userblocked' => 'No karber engel biyo. Cıkotene rê mısade cı nêdino.',
'wrongpassword' => 'Parola ğelete kota cı.
Kerem ke, oncia bıcerrebne.',
'wrongpasswordempty' => 'Parola thale kota cı.
Kerem ke, oncia bıcerrebne.',
'passwordtooshort' => 'Paroley tewr senık ebe {{PLURAL:$1|1 karakter|$1 karakteru}} gunê derg bê.',
'password-name-match' => 'Parola sıma namê sımaê karberi ra gunê ferqın bo.',
'password-login-forbidden' => 'Namê nê karberi u gurenaena parola qedeğen biya.',
'mailmypassword' => 'E-mail sera parola newiye bırusne',
'passwordremindertitle' => 'Serba {{SITENAME}} parola newiya vêrdoğe',
'noemail' => 'Adresa de e-posteya ke ebe namê karberi "$1" beqeyda, çina.',
'mailerror' => 'Xeta rusnaena e-postey: $1',
'emailconfirmlink' => 'Adresa e-postê ho tesdiq ke',
'accountcreated' => 'Hesab vırajiya',
'accountcreatedtext' => 'Serba $1i hesabê karberi vırajiya.',
'createaccount-title' => 'Serba {{SITENAME}}i vırajiyaene hesabê karberi',
'usernamehasherror' => 'Namê karberi de karakteri gunê têwerte ra mebê',
'loginlanguagelabel' => 'Zon: $1',

# Change password dialog
'resetpass' => 'Parola bıvurne',
'resetpass_header' => 'Parola hesabi bıvurne',
'oldpassword' => 'Parola khane:',
'newpassword' => 'Parola newiye:',
'retypenew' => 'Parola newiye tekrar ke:',
'resetpass_submit' => 'Parola ayar ke u cı kuye',
'resetpass_success' => 'Parola sıma ebe serkotene vurriye! Nıka hesabê sıma beno ra...',
'resetpass_forbidden' => 'Paroley nêşikinê bıvurniyê',
'resetpass-submit-loggedin' => 'Parola bıvurne',
'resetpass-submit-cancel' => 'Bıtexelne',
'resetpass-temp-password' => 'Parola vêrdiye:',

# Edit page toolbar
'bold_sample' => 'Nusto qolınd',
'bold_tip' => 'Nusto qolınd',
'italic_sample' => 'Meqalo italik',
'italic_tip' => 'Meqalo italik',
'link_sample' => 'Serrêza girêy',
'link_tip' => 'Girê zerri',
'extlink_sample' => 'http://www.example.com arezekerdena adrese',
'extlink_tip' => 'Girê teberi (verbendê http:// ho vira mekerê)',
'headline_sample' => 'Nustê serrêze',
'headline_tip' => 'Serrêza sewiya 2ine',
'nowiki_sample' => 'Formatê nustê huyo serbeti ita bınuse',
'nowiki_tip' => 'Ehemêt formatê wikiy mede',
'image_tip' => 'Dosya arêkerdiye',
'media_tip' => 'Girê dosya',
'sig_tip' => 'İmza to be tarix',
'hr_tip' => 'Xeta ufqiye (zaf megurene)',

# Edit pages
'summary' => 'Xulasa:',
'subject' => 'Mewzu/serrêze:',
'minoredit' => 'No jü vurnaiso qızkeko',
'watchthis' => 'Na pele de şêr ke',
'savearticle' => 'Pele qeyd ke',
'preview' => 'Verqayt',
'showpreview' => 'Verqayti bıasne',
'showlivepreview' => 'Verqayto cınde',
'showdiff' => 'Vurnaisun bıasne',
'anoneditwarning' => "'''Diqet:''' Tı cınêkota.
Tarixê vurnaena na pele de, hurêndia leqeme de numra tuya IPy qeyd bena.",
'missingcommenttext' => 'Cêr de jü xulasa bınuse.',
'summary-preview' => 'Verqaytê xulasa:',
'subject-preview' => 'Verqaytê mewzuy/serrêze:',
'blockedtitle' => 'Karber kilıt bi',
'blockednoreason' => 'sebeb nêdiyo',
'whitelistedittext' => 'Serba vurnaene $1.',
'nosuchsectiontitle' => 'Qısım nêşikiya bıvêniyo',
'nosuchsectiontext' => 'To waşt ke jü qısım kuyê, uyo ke çino.
Sırewo ke to qaytê pele kerdêne, beno ke no kırışiyo ya ki esteriyo.',
'loginreqtitle' => 'Gunê cı kuyê',
'loginreqlink' => 'cı kuye',
'loginreqpagetext' => 'Serba diyaena pelunê binu tı gunê $1 bıbê.',
'accmailtitle' => 'Parola rusniye.',
'newarticle' => '(Newe)',
'newarticletext' => "To jü girê teqna be jü pela ke hona çina.
Serba afernaena pele qutiya metnê cêrêni bıgurêne. Serba melumati qaytê [[{{MediaWiki:Helppage}}|pela phoşti]] ke.
Eke be ğeletêni ama ita, wa gozaga '''peyser'''i programê ho de bıteqne.",
'noarticletext' => 'Na pele de hona thowa çino.
Tı şikina zerrê pelunê binu de [[Special:Search/{{PAGENAME}}|serba sernamê na pele cı feteliye]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cıkotena aide rê cı feteliye],
ya ki [{{fullurl:{{FULLPAGENAME}}|action=edit}} na pele bıvurne]</span>.',
'noarticletext-nopermission' => 'Na pele de hona thowa çino.
Tı şikina zerrê pelunê binu de [[Special:Search/{{PAGENAME}}|seba sernamê na pele cıfeteliyê]],
ya ki <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cıkotena aide rê cıfeteliyê].</span>',
'userpage-userdoesnotexist' => 'Hesabê karberi "<nowiki>$1</nowiki>" qeyd nêbiyo.
Kerem ke, tı ke wazena na pele vırazê/bıvurnê, qontrol ke.',
'userpage-userdoesnotexist-view' => 'Hesabê karberi "$1" qeyd nêbiyo.',
'userinvalidcssjstitle' => "'''Teme:''' Mewzuyê \"\$1\" çino.
Dosyunê ebe namê .css u .js'y de herfa hurdiye bıgurêne, mesela hurêndia {{ns:user}}:Foo/Vector.css'i de {{ns:user}}:Foo/vector.css bınuse.",
'updated' => '(Bi rozane)',
'note' => "'''Not:'''",
'previewnote' => "Teme! ena teyna verqeyda.'''
Vurnayışê tu hama qeyd nıbiyo!",
'editing' => '$1 Vurnayis',
'editingsection' => 'Vurnaena $1 (qısım)',
'editingcomment' => '$1 vurnino (qısımo newe)',
'editconflict' => 'Têverabiyaena vurnaişi: $1',
'yourtext' => 'Metnê to',
'storedversion' => 'Metıno qeydkerde',
'yourdiff' => 'Ferqi',
'copyrightwarning' => "Diqet ke, iştıraqê ke benê be pela {{SITENAME}}i, pêro bınê $2 de rakerde vêrenê (serba daêna melumati qaytê $1 ke).
İştırakunê ho, eke nêwazena wa terefê binu ra bıvuriyê ya ki caunê binu ra vıla bê, o taw ita menuse.<br />
Zobina ki ebe ita nustene ra sond wena ke nê iştıraki terefê to ra nuşiyê, ya çımê do rakerdey ra ya ki çımê do serbest ra kopya biyê.
'''Gurêo ke ebe telifheqiye ra sevekiyo bê destur ita darde meke!'''",
'templatesused' => '{{PLURAL:$1|Şablono ke na pele de gurenino|Şablonê ke na pele de gureninê}}:',
'templatesusedpreview' => '{{PLURAL:$1|Şablono ke na verqayt de gureno|Şablonê ke na verqayt de gurenê}}:',
'template-protected' => '(sevekna)',
'template-semiprotected' => '(nêm-seveknais)',
'hiddencategories' => 'Na pele mensuba {{PLURAL:$1|1 kategoriya nımıtiya|$1 kategoriunê nımıtuna}}:',
'permissionserrors' => 'Xetê desturi',
'permissionserrorstext-withaction' => 'Desturê to be $2 çino, serba {{PLURAL:$1|na sebebi|nê sebebu}} ra:',
'recreate-moveddeleted-warn' => "'''Hişyari: no pel o ke şıma vırazeni vere cû vırazyayo.'''

Diqet bıkeri no vurnayişê şıma re gerek esto:",
'moveddeleted-notice' => 'Ma ena pele wederna.
Qe referansi logê wedernayışi bın de mocnayiya.',
'edit-conflict' => 'Têverabiyaena vurnaişi.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Teme''': Zerrekê şabloni zaf gırso.
Taê şabloni ilawe nêbenê.",
'post-expand-template-inclusion-category' => 'Pelê ke tede gırşênia şabloni serde sono',
'post-expand-template-argument-warning' => 'Hişyari: No pel de tewr tay yew şablono herayi esto.',
'post-expand-template-argument-category' => 'Pelê ke şablonê eyi qebul niye',

# History pages
'viewpagelogs' => 'Qeydê ke na pele ra alaqedarê, inu bıasne',
'currentrev' => 'Çımraviarnaoğo rozane',
'currentrev-asof' => '$1 ra gore pele be halo nıkaên',
'revisionasof' => 'Halê roca $1ine',
'revision-info' => 'Tesdiq kerdışê roca $1ine be terefê $2',
'previousrevision' => '← Halo khanêr',
'nextrevision' => 'Tekrardiyaiso newêr →',
'currentrevisionlink' => 'Halo nıkaên',
'cur' => 'pey',
'next' => 'badên',
'last' => 'peyên',
'page_first' => 'verên',
'page_last' => 'peyên',
'histlegend' => "Ferqê weçinıtene: Qutiya verziyonun serba têversanaene isaret ke u dest be ''enter''i ya ki gozaga cêrêne ro ne.<br />
Cedwel: (pey) = ferqê verziyonê peyêni,
(ver) = ferqê verziyonê verêni, Q = vurnaiso qızkek.",
'history-fieldset-title' => 'Tarixê cıcêraişi',
'history-show-deleted' => 'Teyna esterıtey',
'histfirst' => 'Verênêr',
'histlast' => 'Peyênêr',
'historysize' => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty' => '(thal)',

# Revision feed
'history-feed-title' => 'Tarixê çımraviarnaişi',
'history-feed-description' => 'Wiki de tarixê çımraviarnaişê na pele',
'history-feed-item-nocomment' => '$1 wertê $2i de',
'history-feed-empty' => 'Pela cıfeteliyaiye çina.
Beno ke na esteriya, ya ki namê cı vuriyo.
Serba pelunê muhimunê newun [[Special:Cıcêre|cıcêraişê wiki de]] bıcerebne.',

# Revision deletion
'rev-deleted-comment' => '(tefşir esteriyo)',
'rev-deleted-user' => '(namê karberi esteriyo)',
'rev-deleted-event' => '(faeliyetê cıkotene esteriyo)',
'rev-deleted-user-contribs' => '[namê karberi ya ki adresa IPy dariya we - vurnaene iştıraku ra nımniya]',
'rev-deleted-text-permission' => "Çımraviarnaena na pele '''esteriya'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.",
'rev-deleted-text-unhide' => "Çımraviarnaena na pele '''esteriya'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.
Sıma be idarekerênia ho ra şikinê hona [$1 na çımraviarnaene bıvênê], eke wazenê dewam kerê.",
'rev-suppressed-text-unhide' => "Çımraviarnaena na pele '''dowoşiya'''.
Beno ke [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log] de teferruat esto. Sıma be idarekerênia ho ra şikinê hona [$1 na çımraviarnaene bıvênê], eke wazenê dewam kerê.",
'rev-deleted-text-view' => "Çımraviarnaena na pele '''esteriya'''.
Sıma be idarekerênia ho ra şikinê ae bıvênê; beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.",
'rev-suppressed-text-view' => "Çımraviarnaena na pele '''dowoşiya'''.
Sıma be idarekerênia ho ra şikinê ae bıvênê; beno ke [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log] de teferruat esto.",
'rev-deleted-no-diff' => "Sıma nêşikinê nê ferqi bıvênê, çıke çımraviarnaisu ra  jü '''esteriyo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.",
'rev-deleted-unhide-diff' => "Çımraviarnaisunê na ferqi ra  jü '''esteriyo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.
Sıma be idarekerênia ho ra şikinê hona [$1 nê ferqi bıvênê], eke wazenê dewam kerê.",
'rev-delundel' => 'bıasne/wedare',
'rev-showdeleted' => 'bıasne',
'revisiondelete' => 'Çımraviarnaisu bıestere/peyser bia',
'revdelete-nooldid-title' => 'Çımraviarnaena waştiye nêvêrena',
'revdelete-nooldid-text' => 'Sıma vırastena nê fonksiyoni rê ya jü çımraviarnaena waştiye diyar nêkerdo, çımraviarnaena diyarkerdiye çına, ya ki sıma wazenê ke çımraviarnaena nıkaêne bınımnê.',
'revdelete-nologtype-title' => 'Qet qeydê cı nêdiya',
'revdelete-nologtype-text' => 'Qeydê sımao diyar çino ke nê fealiyet kuyê.',
'revdelete-nologid-title' => 'Cıkotene qebul nêbiye',
'revdelete-nologid-text' => 'Sıma vırastena nê fonksiyoni rê ya jü cıkotena waştiye diyar nêkerda, ya ki çıkotena diyarkerdiye çina.',
'revdelete-no-file' => 'Dosya diyarkerdiye çina.',
'revdelete-show-file-confirm' => 'Sıma eminê ke wazenê çımraviarnaena esterıtiya na dosya "<nowiki>$1</nowiki>" $2 ra $3 de bıvênê?',
'revdelete-show-file-submit' => 'Heya',
'revdelete-selected' => "'''[[:$1]]: ra {{PLURAL:$2|Çımraviarnaiso weçinıte|Çımraviarnaisê weçinıtey}}'''",
'revdelete-text' => "Çımraviarnaişê esterıtey u kerdişi hewna tarixê pele u qeydan de asenê, hama parçê zerrekê dine areze nêbenê.'''
Eke şertê ilawekerdey ke niyê ro, idarekerê bini {{SITENAME}} de nêşikinê hona bıresê zerrekê nımıtey u şikinê ey oncia na eyni mianpele ra peyser biarê.",
'revdelete-suppress-text' => "Wedardene gunê '''teyna''' nê halunê cêrênu de bıguriyo:
* Melumatê kıfırio mıhtemel
* Melumatê şexsio bêmınasıb
*: ''adresa çêi u numrê têlefoni, numrê siğorta sosyale, uêb.''",
'revdelete-legend' => 'Şertunê vênaişi rone',
'revdelete-hide-text' => 'Nustê çımraviarnaene bınımne',
'revdelete-hide-image' => 'Zerrekê dosya bınımne',
'revdelete-hide-name' => 'Biyaen u hedefi bınımne',
'revdelete-hide-comment' => 'Xulasa measne',
'revdelete-hide-user' => 'Namê karberiê/Adresa IPya vurnaoği bınımne',
'revdelete-radio-same' => '(mevurne)',
'revdelete-radio-set' => 'Heya',
'revdelete-radio-unset' => 'Nê',
'revdelete-log' => 'Sebeb:',
'revdelete-submit' => '{{PLURAL:$1|Çımraviarnaiso ke çiniyo|Çımraviarnaisê ke çiniyê}} we tetbiq ke',
'revdel-restore' => 'asaişi bıvurne',
'revdel-restore-deleted' => 'revizyonê esterıtey',
'revdel-restore-visible' => 'revizyonê asaey',
'pagehist' => 'Tarixê pele',
'deletedhist' => 'Tarixo esterıte',
'revdelete-otherreason' => 'Sebebo bin/ilaweki:',
'revdelete-reasonotherlist' => 'Sebebo bin',
'revdelete-edit-reasonlist' => 'Sebebunê esterıtene bıvurne',
'revdelete-offender' => 'Nustoğê revizyoni:',

# History merging
'mergehistory-from' => 'Pela çımey:',
'mergehistory-into' => 'Pela hedefi:',
'mergehistory-reason' => 'Sebeb:',

# Merge log
'revertmerge' => 'Cia ke',

# Diffs
'history-title' => 'Rewizyonê $1:',
'lineno' => 'Rêza $1i:',
'compareselectedversions' => 'Varyantunê weçinıtun têver sane',
'editundo' => 'peyser bia',
'diff-multi' => '({{PLURAL:$1|Jü çımraviarnaena wertey|$1 çımraviarnaena wertey}} terefê {{PLURAL:$2|zu karberi|$2 karberu}} ra nêasnino)',

# Search results
'searchresults' => 'Neticê cıfeteliyaene',
'searchresults-title' => '"$1" rê neticê cıfeteliyaene',
'searchresulttext' => 'Zerrê {{SITENAME}} de heqa cıfeteliyaene de serba melumat guretene, şikina qaytê [[{{MediaWiki:Helppage}}|{{int:help}}]] ke.',
'searchsubtitle' => 'Tı serba \'\'\'[[:$1]]\'\'\' cıfeteliya. ([[Special:Prefixindex/$1|pelê ke pêro be "$1" ra dest niyê pıra]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|pelê ke pêro be "$1"\' ra girê ho esto]])',
'searchsubtitleinvalid' => "Serbacıfeteliyae: '''$1'''",
'notitlematches' => 'Qet zu serrêze de nêvêniya',
'notextmatches' => 'Qet zu pele de nêvêniya',
'prevn' => '{{PLURAL:$1|$1}} verên',
'nextn' => '{{PLURAL:$1|$1}} peyên',
'prevn-title' => 'Verên $1 {{PLURAL:$1|netice|neticey}}',
'nextn-title' => 'Peyên $1 {{PLURAL:$1|netice|neticey}}',
'shown-title' => 'Zu pele rê $1 {{PLURAL:$1|netice|neticu}} basne',
'viewprevnext' => 'Bıvêne ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists' => "''Ena 'Wikipediya de ser \"[[:\$1]]\" yew pel esto'''",
'searchmenu-new' => "'''Nê wiki de pela \"[[:\$1]]\" baferne!'''",
'searchhelp-url' => 'Help:Tedeestey',
'searchprofile-articles' => 'Pelê tedeesteu',
'searchprofile-project' => 'Pelê yardımi u procey',
'searchprofile-images' => 'Multimedya',
'searchprofile-everything' => 'Her çi',
'searchprofile-advanced' => 'Raverşiyae',
'searchprofile-articles-tooltip' => '$1 de cıfeteliye',
'searchprofile-project-tooltip' => '$1 de cıfeteliye',
'searchprofile-images-tooltip' => 'Dosya bıfeteliye',
'searchprofile-everything-tooltip' => 'Zerreki pêro sae ke (pelunê hurênaişi daxıl)',
'searchprofile-advanced-tooltip' => 'Caunê namunê xısusiu de cıfeteliye',
'search-result-size' => '$1 ({{PLURAL:$2|1 kelime|$2 kelimey}})',
'search-result-category-size' => '{{PLURAL:$1|1 eza|$1 ezey}} ({{PLURAL:$2|1 kategoriya bınêne|$2 kategoriyê bınêni}}, {{PLURAL:$3|1 dosya|$3 dosyey}})',
'search-redirect' => '(hetêcıraberdene $1)',
'search-section' => '(qısımo $1)',
'search-suggest' => 'To ney rê vat: $1',
'search-interwiki-caption' => 'Procê bıray',
'search-interwiki-default' => '$1 neticey:',
'search-interwiki-more' => '(zafêr)',
'search-relatedarticle' => 'alaqedar',
'searchrelated' => 'alaqedar',
'searchall' => 'pêro',
'showingresultsheader' => "'''$4''' rê {{PLURAL:$5|'''$3''' netice ra '''$1'''i|'''$1 - $2''' miyan de '''$3''' netice}}",
'nonefound' => "'''Not''': Teyna taê namê cau jê saybiyau cı fetelino.
Verê cıfeteliyaene de be ilawekerdena verbendê '''all:'''i ra (ebe pelunê hurênaişi, şablonu uêb.) bıcerebnê ya ki namê cayo ke wajino jê verbendi bıgurênê.",
'search-nonefound' => 'Neticeo ke ebe perşi zubini cêno çino.',
'powersearch' => 'Cıcêraiso hira',
'powersearch-legend' => 'Cıcêraiso hira',
'powersearch-ns' => 'Caunê namun de cıcêre:',
'powersearch-redir' => 'Girêun lista ke',
'powersearch-field' => 'Serba cı qayt ke',
'powersearch-togglelabel' => 'Weçine:',
'powersearch-toggleall' => 'Pêro',
'powersearch-togglenone' => 'Qet',
'search-external' => 'Cıcêraisê teberi',
'searchdisabled' => "Cıcêraisê {{SITENAME}} qapan biyo.
Sıma şikinê na sıre ''Google'' de şêr kerê.
Diqet kerê, beno ke tedeestê {{SITENAME}} uza endi rozane niyê.",

# Quickbar
'qbsettings' => 'Herbişiyaena hedefi',
'qbsettings-none' => 'Qet',
'qbsettings-fixedleft' => 'Çhep de bestniyo pa',
'qbsettings-fixedright' => 'Rast de bestniyo pa',
'qbsettings-floatingleft' => 'Çhepi ser aznino',
'qbsettings-floatingright' => 'Rasti ser aznino',

# Preferences page
'preferences' => 'Tercihi',
'mypreferences' => 'Tercihê mı',
'prefs-edits' => 'Numra vurnaisun:',
'prefsnologin' => 'Cı nêkota',
'prefsnologintext' => 'Sıma gunê <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} cı kuyê]</span> ke dıma tercihunê karberi bınusnê.',
'changepassword' => 'Parola bıvurne',
'prefs-skin' => 'Çerme',
'skin-preview' => 'Verqayt',
'datedefault' => 'Tercihi çinê',
'prefs-datetime' => 'Tarix u zeman',
'prefs-personal' => 'Dosya karberi',
'prefs-rc' => 'Vurnaisê peyêni',
'prefs-watchlist' => 'Lista şêr-kerdişi',
'prefs-watchlist-days' => 'Rozê ke lista şêr-kerdişi de asenê:',
'prefs-watchlist-edits' => 'Miqdarê tewr jêdêr vurnaisuno ke lista şêr-kerdişia hirakerdiye derê:',
'prefs-misc' => 'Ğelet',
'prefs-resetpass' => 'Parola bıvurne',
'prefs-rendering' => 'Asais',
'saveprefs' => 'Qeyd ke',
'resetprefs' => 'Vurnaisunê qeydnêkerdun pak ke',
'prefs-editing' => 'Vurnais',
'rows' => 'Rêji:',
'columns' => 'Ustıni:',
'searchresultshead' => 'Cıcêre',
'resultsperpage' => 'Pelgê be vênıtun:',
'stub-threshold' => 'Tertibê şêmıga <a href="#" class="stub">stub link</a> (\'\'bytes\'\'):',
'recentchangesdays' => 'Rozê ke vurnaisunê peyênun de asenê:',
'recentchangesdays-max' => 'Maksimum $1 {{PLURAL:$1|roze|roji}}',
'recentchangescount' => 'Miqdarê vurnaisuno ke standardwari asniyenê:',
'savedprefs' => 'Tercihê sıma qeydi biy.',
'timezonelegend' => 'Warê saete:',
'localtime' => 'Waxto mehelın:',
'timezoneoffset' => 'Ware¹:',
'servertime' => "Waxtê ''server''i:",
'guesstimezone' => "''Browser''i ra pırr ke",
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antartika',
'timezoneregion-arctic' => 'Arktik',
'timezoneregion-asia' => 'Asya',
'timezoneregion-atlantic' => 'Okyanuso Atlantik',
'timezoneregion-australia' => 'Australya',
'timezoneregion-europe' => 'Awrupa',
'timezoneregion-indian' => 'Okyanuso Hind',
'timezoneregion-pacific' => 'Okyanuso Pasifik',
'allowemail' => "Karberunê binun ra ''e-mail''i fael ke",
'prefs-searchoptions' => 'Alternatifê cıcêraişi',
'prefs-namespaces' => 'Caê namey',
'defaultns' => 'Halo bin de zerrê nê caunê namey de cıfeteliye:',
'default' => 'ihmal',
'prefs-files' => 'Dosyey',
'youremail' => 'E-poste:',
'username' => 'Namê karberi:',
'uid' => 'Kamiya karberi:',
'prefs-memberingroups' => 'Ezaê de {{PLURAL:$1|gruba|grubunê}}:',
'yourrealname' => 'Namo rastıkên:',
'yourlanguage' => 'Zon:',
'yourvariant' => 'Varyant:',
'yournick' => 'İmza:',
'badsig' => "İmza kala nêvêrdiye.
Etiketê ''HTML''i qontrol ke.",
'badsiglength' => 'İmza to zaf derga.
Gunê $1 {{PLURAL:$1|herfe|herfun}} ra senık bo.',
'yourgender' => 'Cınsiyet:',
'gender-male' => 'Cüamêrd',
'gender-female' => 'Cüanıke',
'email' => 'E-poste',
'prefs-help-realname' => 'Namo rastıkên serbesto.
Sıma ke ney bıgurenê, karê sıma de no namdarêni dano.',
'prefs-help-email' => 'Dayışê adresa e-postey keyfiyo, labelê seba eyarê parola lazıma, wexto ke şıma naye xo vira kerê.',
'prefs-help-email-others' => 'Şıma şenê weçinê ke ê bini be yew gırey pela şımaya karberi ya zi pela werênayışi sera şıma de ebe e-poste irtıbat kewê.
Kaberê bini ke şıma de kewti irtıbat, adresa e-postey şıma eşkera nêbena.',
'prefs-help-email-required' => 'Adresa emaili lazıma.',
'prefs-signature' => 'İmza',
'prefs-diffs' => 'Ferqi',

# User rights
'userrights' => 'İdarê hequnê karberi',
'userrights-lookup-user' => 'Komunê karberun idare ke',
'userrights-user-editname' => 'Jü namê karberi bıdê:',
'editusergroup' => 'Komunê karberun bıvurne',
'editinguser' => "Hequnê karberê '''[[User:$1|$1]]'''i vurnais ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Komunê karberun bıvurne',
'saveusergroups' => 'Komunê karberun qeyd ke',
'userrights-groupsmember' => 'Ezaê de:',
'userrights-reason' => 'Sebeb:',

# Groups
'group' => 'Kom:',
'group-user' => 'Karberi',
'group-bot' => 'Boti',
'group-sysop' => 'İdarekeri',
'group-bureaucrat' => 'Burokrati',
'group-all' => '(pêro)',

'group-user-member' => 'Karber',
'group-bot-member' => 'Bot',
'group-sysop-member' => 'İdareker',
'group-bureaucrat-member' => 'Burokrat',

'grouppage-user' => '{{ns:project}}:Karberi',
'grouppage-bot' => '{{ns:project}}:Boti',
'grouppage-sysop' => '{{ns:project}}:İdarekeri',
'grouppage-bureaucrat' => '{{ns:project}}:Burokrati',

# Rights
'right-read' => 'Pelan bıwane',
'right-edit' => 'Pelu bıvurne',
'right-move' => 'Pelu bere',
'right-movefile' => 'Dosyu bere',
'right-upload' => 'Dosyu bar ke',
'right-delete' => 'Pelu bıestere',
'right-undelete' => 'Esterıtena na pele peyser bıcê',

# User rights log
'rightslog' => 'Qeydê hequnê karberi',
'rightslogtext' => 'No jü qeydê vurnaisê hequnê karberio.',
'rightslogentry' => 'selahiyetê $1i $2 ra vurniya be $3i',
'rightsnone' => '(qet jü)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'na pele bıwane',
'action-edit' => 'na pele bıvurne',
'action-createpage' => 'pelu bıaferne',
'action-createtalk' => 'pelunê hurênaişi bıaferne',
'action-createaccount' => 'na hesabê karberi bıaferne',
'action-move' => 'na pele bere',
'action-movefile' => 'na dosya bere',
'action-upload' => 'na dosya bar ke',
'action-delete' => 'na pele bıestere',
'action-undelete' => 'na pele meestere',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|tedil|tedili}}',
'recentchanges' => 'Vurnaisê peyêni',
'recentchanges-legend' => 'Alternatifê vurnaisunê peyênu',
'recentchanges-summary' => 'Na weiyekerdena wiki de vurnaisê tewrpeyêne ke biyê ine teqib ke.',
'recentchanges-feed-description' => 'Na weiyekerdena wiki de vurnaisê tewrpeyêne ke biyê ine teqib ke.',
'recentchanges-label-newpage' => 'Nê vurnaişi pelê da newiye afernê.',
'recentchanges-label-minor' => 'No zu vurnaiso qıckeko',
'recentchanges-label-bot' => 'No vurnais terefê zu boti ra bi',
'recentchanges-label-unpatrolled' => 'No vurnais hona çım ra ranêvêrdo',
'rcnote' => "Cêr {{PLURAL:$1|'''1''' vurnaiso peyên|'''$1''' vurnaisê peyêni}} be {{PLURAL:$2|roza peyêne|'''$2''' rozunê peyênunê}} $5, $4 ra estê.",
'rcnotefrom' => "Cêr de vurnayîşê esto ke '''$2''' ra raver  (heta '''$1''' mucnayiyo).",
'rclistfrom' => '$1 ra hata nıka vurnaisunê newu bıasne',
'rcshowhideminor' => '$1 vurnaisê qızkeki',
'rcshowhidebots' => 'Botu $1',
'rcshowhideliu' => 'Karberunê qeydbiyayu $1',
'rcshowhideanons' => '$1 karberê anonimi',
'rcshowhidepatr' => 'Vurnayışê cıyê vênıtey $1',
'rcshowhidemine' => 'Vurnayisanê mı $1',
'rclinks' => 'Peyniya $2 rozu de $1 vurnayisu bıasne <br />$3',
'diff' => 'ferq',
'hist' => 'ver',
'hide' => 'Bınımne',
'show' => 'Bıasne',
'minoreditletter' => 'q',
'newpageletter' => 'N',
'boteditletter' => 'b',
'rc-enhanced-expand' => 'Tefsilatu bıasne (JavaScript lazımo)',
'rc-enhanced-hide' => 'Tefsilatu bınımne',

# Recent changes linked
'recentchangeslinked' => 'Ney sero vurnaene',
'recentchangeslinked-toolbox' => 'Ney sero vurnaene',
'recentchangeslinked-title' => 'Heqa "$1"i de vurnais',
'recentchangeslinked-noresult' => 'Pelanê ke link biye ey vurnayîşî çino.',
'recentchangeslinked-summary' => "Lista cêrêne, pela bêlikerdiye rê (ya ki karberunê kategoriya bêlikerdiye rê) pelunê girêdaoğu de lista de vurnaisê peyênuna.
[[Special:Watchlist|Lista sımawa şêrkedişi de]] peli be nusto '''qolınd''' bêli kerdê.",
'recentchangeslinked-page' => 'Namê pele:',
'recentchangeslinked-to' => 'Hurêndia pela ke yena daene, vurnaisunê pelunê ke dae ra girêdaiyê, inu bıasne',

# Upload
'upload' => 'Dosya bar ke',
'uploadbtn' => 'Dosya bar ke',
'reuploaddesc' => 'Bar-kerdene bıtexelne u racêre ra formê bar-kerdene',
'uploadnologin' => 'Ede cı nêkotê',
'uploadnologintext' => 'Sıma gunê [[Special:UserLogin|cı kuyê]] ke dosyeun bar kerê.',
'upload_directory_missing' => "İndeksê bar-kerdena ($1)i çino u terefê ''webserver''i ra nêşikino ke bıaferiyo.",
'upload_directory_read_only' => "İndeksê bar-kerdena ($1)i terefê ''webserver''i nênuşino.",
'uploaderror' => 'Xeta bar-kerdene',
'uploadlog' => 'qeydê barkerdene',
'uploadlogpage' => 'Qeydê dosya barkerdene',
'filename' => 'Namê dosya',
'filedesc' => 'Xulasa',
'fileuploadsummary' => 'Xulasa:',
'filereuploadsummary' => 'Vurnaisê dosya:',
'filestatus' => 'Halê heqa telifi:',
'filesource' => 'Çıme:',
'uploadedfiles' => 'Dosyê barkerdey',
'empty-file' => 'Dosya ke ti şawit veng biy.',
'savefile' => 'Dosya qeyd ke',
'uploadedimage' => '"[[$1]]" bar bi',
'upload-source' => 'Dosya çımey',
'sourcefilename' => 'Namê dosya çımey:',
'watchthisupload' => 'Na dosya de şêr ke',

'license' => 'Lisans:',
'license-header' => 'Lisansdais',

# Special:ListFiles
'imgfile' => 'dosya',
'listfiles' => 'Lista dosya',
'listfiles_thumb' => 'Resmo qızkek',
'listfiles_date' => 'Tarix',
'listfiles_name' => 'Name',
'listfiles_user' => 'Karber',
'listfiles_size' => 'Ebad',
'listfiles_description' => 'Terif',
'listfiles_count' => 'Verziyoni',

# File description page
'file-anchor-link' => 'Dosya',
'filehist' => 'Tarixê dosya',
'filehist-help' => "Serba diyaena viartê dosya tarixê ke qısımê tarix/zeman'i derê inu bıteqne.",
'filehist-deleteall' => 'Pêrune bıestere',
'filehist-deleteone' => 'bestere',
'filehist-revert' => 'raçarne',
'filehist-current' => 'nıkaên',
'filehist-datetime' => 'Tarix/Dem',
'filehist-thumb' => 'Resmo qızkek',
'filehist-thumbtext' => 'Halo qızkek be versiyonê roza $1ine',
'filehist-nothumb' => 'Resmo qızkek çino',
'filehist-user' => 'Karber',
'filehist-dimensions' => 'Budi',
'filehist-filesize' => 'Gırşênia dosya',
'filehist-comment' => 'Areze-kerdene',
'filehist-missing' => 'Dosya vindbiyaiya',
'imagelinks' => 'Gurenaisê dosya',
'linkstoimage' => 'Ano {{PLURAL:$1|girê pele|$1 girê pelu}} be na dosya:',
'nolinkstoimage' => 'Pelanê ser ena dosyayê link biyê çin o.',
'sharedupload' => 'Na dosya depoê $1 rawa u beno ke procunê binu de gurenina.',
'sharedupload-desc-here' => 'Beno ke depoê na dosya $1 ra be terefê procunê bınu ra gurenino.
Cêr [$2 pela arezekerdena dosya de] arezekerdene asnina.',
'uploadnewversion-linktext' => 'Dosya de newiye bar ke',
'shared-repo-from' => '$1 ra',

# File reversion
'filerevert' => '$1 raçarne',
'filerevert-legend' => 'Dosya raçarne',
'filerevert-comment' => 'Arezekerdene:',
'filerevert-submit' => 'Raçarne',

# File deletion
'filedelete' => 'Bıestere $1',
'filedelete-legend' => 'Dosya bıestere',
'filedelete-comment' => 'Sebeb:',
'filedelete-submit' => 'Bestere',
'filedelete-otherreason' => 'Sebebo bin/ilaweki:',
'filedelete-reason-otherlist' => 'Sebebo bin',
'filedelete-edit-reasonlist' => 'Sebebunê esterıtene bıvurne',

# MIME search
'download' => 'bar ke',

# Random page
'randompage' => 'Pela rastamaiye',

# Statistics
'statistics' => 'İstatistiki',
'statistics-header-pages' => 'İstatistikê pele',
'statistics-header-edits' => 'İstatistikê vurnaene',
'statistics-header-views' => 'İstatistikê asaene',
'statistics-header-users' => 'İstatistikê karberi',
'statistics-header-hooks' => 'İstatistikê bini',
'statistics-articles' => 'Pelê tedeesteu',
'statistics-pages' => 'Peli',

'disambiguationspage' => 'Template:vuriyaisê maney',

'brokenredirects-edit' => 'bıvurne',
'brokenredirects-delete' => 'bestere',

'withoutinterwiki-legend' => 'Verbend',
'withoutinterwiki-submit' => 'Bıasne',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bayt|bayti}}',
'ncategories' => '$1 {{PLURAL:$1|kategoriye|kategoriy}}',
'nlinks' => '$1 {{PLURAL:$1|gire|girey}}',
'nmembers' => '$1 {{PLURAL:$1|eza|ezay}}',
'nrevisions' => '$1 {{PLURAL:$1|çım-ra-viarnais|çım-ra-viarnaişi}}',
'nviews' => '$1 {{PLURAL:$1|vênais|vênaişi}}',
'uncategorizedpages' => 'Pelê kategorizenêkerdey',
'prefixindex' => 'Peli pêro be verbend',
'shortpages' => 'Pelê kılmi',
'longpages' => 'Pelê dergi',
'listusers' => 'Lista karberi',
'usercreated' => '$2 de $1 {{GENDER:$3|viraziya}}',
'newpages' => 'Pelê newey',
'newpages-username' => 'Namê karberi:',
'ancientpages' => 'Pelê khanêri',
'move' => 'Bere',
'movethispage' => 'Na pele bere',
'pager-newer-n' => '{{PLURAL:$1|1 newêr|$1 newêri}}',
'pager-older-n' => '{{PLURAL:$1|1 khanêr|$1 khanêri}}',

# Book sources
'booksources' => 'Çımê kıtabun',
'booksources-search-legend' => 'Serba çımeunê kıtabu cıfeteliye',
'booksources-go' => 'So',

# Special:Log
'specialloguserlabel' => 'Kerdoğ:',
'speciallogtitlelabel' => 'Sernuste:',
'log' => 'Qeydi',

# Special:AllPages
'allpages' => 'Peli pêro',
'alphaindexline' => '$1 bere $2',
'nextpage' => 'Pela peyê coy ($1)',
'prevpage' => 'Pela verêne ($1)',
'allpagesfrom' => 'Pelê ke be na herfe dest niyo pıra bıasne:',
'allpagesto' => 'Pelunê ke be na herfe qedinê bıasne:',
'allarticles' => 'Peli pêro',
'allpagessubmit' => 'So',

# Special:Categories
'categories' => 'Kategoriy',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'iştıraki',

# Special:LinkSearch
'linksearch' => 'Girê teberi',
'linksearch-ok' => 'Cıfeteliye',
'linksearch-line' => '$1rê $2ra gırê diyo',

# Special:ListUsers
'listusers-submit' => 'Bıasne',
'listusers-noresult' => 'Karber nêdiya.',
'listusers-blocked' => '(kilıt biyo)',

# Special:ActiveUsers
'activeusers' => 'Lista karberunê fealu',

# Special:Log/newusers
'newuserlogpage' => 'Qeydê karberiê newey',

# Special:ListGroupRights
'listgrouprights-group' => 'Kome',
'listgrouprights-rights' => 'Heqi',
'listgrouprights-helppage' => 'Hewar:Heqê grube',
'listgrouprights-members' => '(lista azau)',
'listgrouprights-addgroup' => '{{PLURAL:$2|Grube|Grubi}} şikinê ilawe bê: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|Grube|Grubi}} şikinê wedariyê: $1',
'listgrouprights-addgroup-all' => 'Heme grubu ilawe ke',
'listgrouprights-removegroup-all' => 'Heme grubu wedare',

# E-mail user
'emailuser' => 'Nê karberi rê e-poste bırusne',
'emailpage' => 'Karberi rê e-poste bırusne',
'emailfrom' => 'Kami ra:',
'emailto' => 'Kami rê:',
'emailsubject' => 'Mewzu:',
'emailmessage' => 'Mesac:',
'emailsend' => 'Bırusne',

# Watchlist
'watchlist' => 'Pela mına şêrkerdene',
'mywatchlist' => 'Lista şêrkerdışi',
'watchlistfor2' => 'Serba $1 ($2)',
'addedwatchtext' => "Pela \"[[:\$1]]\"i ilawe biye be [[Special:Watchlist|pela şêrkerdişi]].
Nara dıme, vurnaisê na pele u pela hurênaisê dawa alaqedare ita bena lista, u pele [[Special:RecentChanges|lista vurnaisunê peyênu]] de '''qolınd''' asena ke cı ra asan weçiniyo.",
'removedwatchtext' => 'Na pele "[[:$1]]" [[Special:Watchlist|lista tuya şêrkerdişi]] ra esteriya.',
'watch' => 'Şêr ke',
'watchthispage' => 'Na pele de şêr ke',
'unwatch' => 'Endi şêr meke',
'watchlist-details' => 'Pelunê hurênaişi ra qêri {{PLURAL:$1|$1 pele lista şêrkerdişi dera|$1 peli lista şêrkerdişi derê}}.',
'wlshowlast' => '$1 saetunê $2 rozunê peyênu bıasne $3',
'watchlist-options' => 'Alternatifê lista şêrkerdene',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Şêr ke…',
'unwatching' => 'Şêr meke…',

'changed' => 'vuriya',

# Delete
'deletepage' => 'Pele bıestere',
'delete-legend' => 'Bestere',
'confirmdeletetext' => 'Tı hawo kena ke jü pele be tarixê dae pêro bıne ra bıesterê.
Eke ferqê neticê na kerdene de bena u no kar be gorê [[{{MediaWiki:Policy-url}}|qeydunê esterıtene]] beno, wa gurêy tesdiq ke.',
'actioncomplete' => 'Kar bi temam',
'actionfailed' => 'Fealiyet era ser nêkot',
'deletedtext' => '"$1" esteriya.
Serba diyaena esterıteyunê peyênu $2 bıvêne.',
'dellogpage' => 'Qeydê esterıtene',
'deletecomment' => 'Sebeb:',
'deleteotherreason' => 'Sebebo bin/ilaweki:',
'deletereasonotherlist' => 'Sebebo bin',

# Rollback
'rollbacklink' => 'peyser bia',

# Protect
'protectlogpage' => 'Qeydê seveknaene',
'protectedarticle' => '"[[$1]]" sevekna',
'modifiedarticleprotection' => 'serba "[[$1]]" sewiya seveknaene vurriye',
'protectcomment' => 'Sebeb:',
'protectexpiry' => 'Tarixê qediyaene:',
'protect_expiry_invalid' => 'Tarixê qediyaena nêvêreno.',
'protect_expiry_old' => 'Waxtê gurênaena peyêna vêrdiye.',
'protect-text' => "Tı şikina halê seveknaena pela '''$1'''i ita bıvênê u bıvurnê.",
'protect-locked-access' => "Hesabê karberê to vurnaisê sewiya seveknaena rê selahiyetdar niyo.
Eyarê pela '''$1'''ina vêrdey nêyê:",
'protect-cascadeon' => 'Na pele na sate sevekiya, çıke {{PLURAL:$1|na pele de|nê pelu de}} sevekiyaena qedemeine biya feal.
Tı şikina sewiya sevekiyaena na pele bıvurnê, hema yê nae sevekiyaena qedemeine rê tesirê ho nêbeno.',
'protect-default' => 'Destur bıde be karberu pêrune',
'protect-fallback' => 'Desturê "$1"i lazımo',
'protect-level-autoconfirmed' => 'Karberunê newun u qeydnêbiyaoğu kilıt ke',
'protect-level-sysop' => 'Teyna idarekeri',
'protect-summary-cascade' => 'qedemein',
'protect-expiring' => 'tarixê qediyaene $1 (UTC)',
'protect-expiring-local' => '$1 de qedino',
'protect-expiry-indefinite' => 'bêmıdet',
'protect-cascade' => 'Pelê ke na pele derê bısevekne (seveknaena qedemeine)',
'protect-cantedit' => 'Tı nêşikina sinorê kilıtbiyaena na pele bıvurnê, çıke desturê to be vurnaene çino.',
'protect-othertime' => 'Waxto bin:',
'protect-othertime-op' => 'waxto bin',
'protect-existing-expiry' => 'Waxtê qediyaeneo mewcıd: $3, $2',
'protect-otherreason' => 'Sebebo bin/ilaweki:',
'protect-otherreason-op' => 'Sebebo bin',
'protect-expiry-options' => '2 saeti:2 hours,1 roze:1 day,3 roji:3 days,1 hefte:1 week,2 heftey:2 weeks,1 asme:1 month,3 asmi:3 months,6 asmi:6 months,1 serre:1 year,bêmıdet:infinite',
'restriction-type' => 'Destur:',
'restriction-level' => 'Sinorê desturi:',
'minimum-size' => 'ebado tewr qızkek',
'maximum-size' => 'Ebado tewr gırs:',
'pagesize' => '(bayti)',

# Restrictions (nouns)
'restriction-edit' => 'Bıvurne',
'restriction-move' => 'Bere',
'restriction-create' => 'Vıraze',
'restriction-upload' => 'Bar ke',

# Restriction levels
'restriction-level-sysop' => 'tam sevekiyo',
'restriction-level-autoconfirmed' => 'nêm sevekiyo',
'restriction-level-all' => 'heme jü sewiya',

# Undelete
'undelete' => 'Pelunê esteriyau basna',
'undeletepage' => 'Pelunê esteriyau bıvine u peyser biya',
'undeletepagetitle' => "'''Ni, [[:$1|$1]] be çımraviarnaunê pele ra yenê pêra'''.",
'viewdeletedpage' => 'Pelunê esteriyau bıvine',
'undelete-fieldset-title' => 'Çımraviarnau peyser biya',
'undeletebtn' => 'Peyser bia',
'undeletelink' => 'bıvêne/peyser bia',
'undeleteviewlink' => 'bıvêne',
'undeletereset' => 'Vaz be',
'undeleteinvert' => 'Weçinıtey ters bıçarne',
'undeletecomment' => 'Sebeb:',
'undelete-header' => 'Peleyê ke veror de besterneyayê êna bıvinê: [[Special:Log/delete|qeydê esterneya]].',
'undelete-search-submit' => 'Cıfeteliye',
'undelete-show-file-submit' => 'Heya',

# Namespace form on various pages
'namespace' => 'Caê namey:',
'invert' => 'Weçinıtiso peyserki',
'blanknamespace' => '(Ser)',

# Contributions
'contributions' => 'İştırakê karberi',
'contributions-title' => '$1 de iştırakê karberi',
'mycontris' => 'İştıraqi',
'contribsub2' => 'Serba $1 ($2)',
'uctop' => '(ser)',
'month' => 'Asme ra (u ravêr):',
'year' => 'Serre ra (u ravêr):',

'sp-contributions-newbies' => 'Teyna iştırakunê neweqeydbiyaoğu basne',
'sp-contributions-blocklog' => 'qeydê engeli',
'sp-contributions-uploads' => 'barbiyaey',
'sp-contributions-logs' => 'qeydi',
'sp-contributions-talk' => 'hurênais',
'sp-contributions-search' => 'Ebe iştıraku cı feteliye',
'sp-contributions-username' => 'IP ya ki karber:',
'sp-contributions-toponly' => 'Tenya rewizyonanê tewr peyniyan bimocne',
'sp-contributions-submit' => 'Cı feteliye',

# What links here
'whatlinkshere' => 'Çı itay rê gırê beno',
'whatlinkshere-title' => 'Pelê ke be "$1"i bestninê pa',
'whatlinkshere-page' => 'Pele:',
'linkshere' => "Ni pelgi '''[[:$1]]'''i asnenê:",
'nolinkshere' => "Pelgê ke '''[[:$1]]'''i asnenê çinê.",
'isredirect' => 'pela ciheti',
'istemplate' => 'ilawekerdis',
'isimage' => 'girê dosya',
'whatlinkshere-prev' => '{{PLURAL:$1|verêni|verên $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|tepiyaên|tepiyaên $1}}',
'whatlinkshere-links' => '← girê beno',
'whatlinkshere-hideredirs' => 'peyser sono $1',
'whatlinkshere-hidetrans' => 'İlawekerdê çaprazi $1',
'whatlinkshere-hidelinks' => '$1 girey',
'whatlinkshere-hideimages' => 'Girê dosya $1',
'whatlinkshere-filters' => 'Filtrey',

# Block/unblock
'blockip' => 'Karberi kilıt ke',
'ipboptions' => '2 saeti:2 hours,1 roze:1 day,3 roji:3 days,1 hefte:1 week,2 heftey:2 weeks,1 asme:1 month,3 asmi:3 months,6 asmi:6 months,1 serre:1 year,bêmıdet:infinite',
'ipblocklist' => 'Karberê kilıtbiyaey',
'blocklist-timestamp' => 'Demğê tarixi',
'blocklist-target' => 'Hedef',
'blocklist-expiry' => 'Tarixê qediyaene',
'blocklist-by' => 'İdarekero kilıtkerdoğ',
'blocklist-params' => 'Parametrê kilıtkerdene',
'blocklist-reason' => 'Sebeb',
'ipblocklist-submit' => 'Cıfeteliye',
'infiniteblock' => 'bêmıdet',
'expiringblock' => '$1 $2 de qedino',
'anononlyblock' => 'teyna anonim',
'noautoblockblock' => 'kilıto otomatik qapan bi',
'createaccountblock' => 'afernaena hesabi qapan biye',
'emailblock' => 'e-poste kilıt bi',
'blocklist-nousertalk' => 'pela hurênaişi ho nêşikina bıvurnê',
'ipblocklist-empty' => 'Lista kilıtkerdişi thala.',
'blocklink' => 'kilıt ke',
'unblocklink' => 'ra ke',
'change-blocklink' => 'mani bıvurne',
'contribslink' => 'iştıraqi',
'emaillink' => 'e-poste bırusne',
'autoblocker' => 'Sıma otomatikmen kılit biy, çıke adresa sımawa \'\'IP\'\'y terefê "[[User:$1|$1]]" gurenina.
Sebebê kılit-biyaena $1\'i: "$2"o',
'blocklogpage' => 'Protokolê kilıti',
'blocklogentry' => '[[$1]] hata peyê $2 $3ine kilıt bi',
'reblock-logentry' => 'kilıt-kerdena [[$1]]i hata peyê $2 $3ine vurnê',
'blocklogtext' => "No jü protokolê faeliyetê kilıt- u rakerdena karberuno.
Otomatikmen kilıt biyaiyê adresê ''IP''y lista de çinê.
Serba men- u kilıt-biyaene nıkaêne qaytê [[Special:BlockList|lista kilıt-kerdena ''IP''y]] ke.",
'unblocklogentry' => "kilıt-kerdena $1'i",
'block-log-flags-anononly' => 'teyna karberê bênamey',
'block-log-flags-nocreate' => 'afernaena hesabi qapan biye',
'block-log-flags-noautoblock' => 'kilıto otomatik qapan bi',
'block-log-flags-noemail' => 'e-mail kilıt bi',
'block-log-flags-nousertalk' => 'pela hurênaişi ho nêşikina bıvurnê',
'block-log-flags-angry-autoblock' => 'kilıto otomatiko qewetın bi ra',
'block-log-flags-hiddenname' => 'namê karberi wedariyaeo',
'range_block_disabled' => 'Qabılıyetê idarekeri be afernaena komuna têdine qapan bi.',
'ipb_expiry_invalid' => 'Xêlê zeman nêvêreno.',

# Move page
'movepagetext' => "Ebe gurênaena formê cêrêni namê jü pele vurino, qeydê cıyê verêni pêro tede sonê be namê newey ser.
Nameo khan jü pela de cihetiê be namê newey cêna.
Tı şikina ita de cihetu otomatikman hetê namê oricinali ser rocane kerê.
Eke tı nêwazena otomatikman bıkerê, gunê [[Special:DoubleRedirects|cihetunê çıftu]] ya ki [[Special:BrokenRedirects|cihetunê nêvêrdeyu]] pêroine be ho duz kerê.
Ho vira meke ke be na vurnaiso ke tı kena, gurênaisê girêu be caunê rastu pêroine ra tı mesula.

Diqet ke, namê newey de hora ke jü madde esto, vurnaisê namey '''nêbeno''', wa no ke thalo ya ki jü cihetiserberdiso u vurnaisê huyo verên çino. No yeno na mana ke tı şikina namê jü pele peyser bıcêrê, koti ra ke namê ae vuriyo, beno ke to ğelet kerd u zobina ki qarısê pela de bine nêbena.

'''Teme!'''
No vurnais beno ke serba jü pela populere neticunê nêbiyau biaro meydan;
kerem ke, verê vurnaişi neticunê biyau bia be çımu ver.",
'movepagetalktext' => "Na pela hurênaişia ke tedera otomatikmen kırışina be namê newey, hama nê halu ra '''qêri''':
*Jü pela hurênaişia pırre bınê namê newey de hora esta, ya ki
*Qutiya bınêne to nêçinıte we.

Nê halu de, tı gunê pele ebe dest berê ya ki ser kerê eke wajiye.",
'movearticle' => 'Pele bere:',
'newtitle' => 'Ebe nameo newe:',
'move-watch' => 'Na pele de şêr ke',
'movepagebtn' => 'Pele bere',
'pagemovedsub' => 'Vurnaena namey biye temam',
'movepage-moved' => '\'\'\'"$1" berd be pela "$2"\'\'\'',
'articleexists' => 'Pelê da ebe nê namey çina, ya ki nameo ke çiniyo we nêvêreno.
Kerem ke, nameo de bin bıcerrebne.',
'talkexists' => "'''Pele be ho ebe mıweffeq kırışiye, hema pela hurênaişi nêşikiye ke bıkırışiyo, çıke bınê na namey de hora jüye esta.
Kerem ke, zerreki ebe ho dest bere.'''",
'movedto' => 'berd be',
'movetalk' => 'Pela hurênaişiê alaqedare bere',
'movelogpage' => 'Qeydê berdene',
'movereason' => 'Sebeb:',
'revertmove' => 'raçarnaene',

# Export
'export' => 'Pelu qeyd ke',

# Namespace 8 related
'allmessages' => 'Mesacê sistemi',
'allmessagesname' => 'Name',
'allmessagesdefault' => 'Metnê mesacê saebiyaoği',
'allmessages-language' => 'Zon:',
'allmessages-filter-submit' => 'So',

# Thumbnails
'thumbnail-more' => 'Gırs ke',
'thumbnail_error' => 'Vıraştena resımê qıckeki de xeta: $1',

# Special:Import
'import-upload-filename' => 'Namê dosya:',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Pela sımawa karberi',
'tooltip-pt-mytalk' => 'Pela sımawa hurênaişi',
'tooltip-pt-preferences' => 'Tercihê mı',
'tooltip-pt-watchlist' => 'Lista pelunê ke to guretê şêrkerdene',
'tooltip-pt-mycontris' => 'Lista iştıraqunê sıma',
'tooltip-pt-login' => 'Serba cıkotene sıma rê sılaiya; hama, na zeruriye niya',
'tooltip-pt-anonlogin' => 'Serba cıkotene sıma rê sılaiya, hama na zeruriye niya.',
'tooltip-pt-logout' => 'Veciye',
'tooltip-ca-talk' => 'Pela tedeesteu sero hurênais',
'tooltip-ca-edit' => 'Tı şikina na pele bıvurnê.
Kerem ke, qeydkerdene ra ver gozaga verqayti bıgurene.',
'tooltip-ca-addsection' => 'Jü qısımo newe rake',
'tooltip-ca-viewsource' => 'Na pele seveknaiya.
Tı şikina çımunê dae bıvênê',
'tooltip-ca-history' => 'Versiyonê verênê na pele',
'tooltip-ca-protect' => 'Na pele bısevekne',
'tooltip-ca-delete' => 'Na pele bıestere',
'tooltip-ca-move' => 'Namê na pele bıvurne',
'tooltip-ca-watch' => 'Na pele bıcê lista huya şêrkerdene',
'tooltip-ca-unwatch' => 'Na pele lista huya şêrkerdene ra wedare',
'tooltip-search' => 'Zerrê {{SITENAME}} de cı feteliye',
'tooltip-search-go' => 'Ebe ni namê tami so jü pela ke esta',
'tooltip-search-fulltext' => 'Serba ni meqaley pelu seha ke',
'tooltip-p-logo' => 'Pela Seri',
'tooltip-n-mainpage' => 'Pela Seri bıvêne',
'tooltip-n-mainpage-description' => 'Pela Seri bıvêne',
'tooltip-n-portal' => 'Heqa procey de, çı şikina bıvırazê, çı koti vênino',
'tooltip-n-currentevents' => 'Vurnaisunê peyênu sero melumatê pey bıvêne',
'tooltip-n-recentchanges' => 'Wiki de lista vurnaisunê peyênu',
'tooltip-n-randompage' => 'Pelê da rastamaiye bar ke',
'tooltip-n-help' => 'Caê doskerdene',
'tooltip-t-whatlinkshere' => 'Lista pelunê wikia pêroina ke ita girê bena',
'tooltip-t-recentchangeslinked' => 'Vurnaisê peyênê pelunê ke na pela ra girê biyê',
'tooltip-feed-rss' => 'Qutê RSSê na pele',
'tooltip-feed-atom' => 'Qutê atomê na pele',
'tooltip-t-contributions' => 'Lista iştırakunê ni karberi bıvêne',
'tooltip-t-emailuser' => 'Jü e-mail ni karberi rê bırusne',
'tooltip-t-upload' => 'Dosya bar ke',
'tooltip-t-specialpages' => 'Lista pelunê xasunê pêroinu',
'tooltip-t-print' => 'Nımunê kopyakerdena na pele',
'tooltip-t-permalink' => 'Girêo daimi be na versiyonê pele',
'tooltip-ca-nstab-main' => 'Pela tede esteu bıvêne',
'tooltip-ca-nstab-user' => 'Pela karberi bıvêne',
'tooltip-ca-nstab-media' => 'Pela medya bıvêne',
'tooltip-ca-nstab-special' => 'Na jü pelê da xususiya, sıma nêşikinê nae bıvurnê',
'tooltip-ca-nstab-project' => 'Pela procey bıvêne',
'tooltip-ca-nstab-image' => 'Pela dosya bıvêne',
'tooltip-ca-nstab-mediawiki' => 'Mesacê sistemi bıvêne',
'tooltip-ca-nstab-template' => 'Nımuney bıvêne',
'tooltip-ca-nstab-help' => 'Pela phoşti bıvêne',
'tooltip-ca-nstab-category' => 'Pela kategoriye bıvêne',
'tooltip-minoredit' => 'Ney jê vurnaiso qıc isaret ke',
'tooltip-save' => 'Vurnaisunê ho qeyd ke',
'tooltip-preview' => 'Kerem ke, vurnaisunê ho qeyd-kerdene ra ravêr be verqayt bıasne!',
'tooltip-diff' => 'Kamci vurnaişi ke to meqale de kerdê, naine bıasne.',
'tooltip-compareselectedversions' => 'Ferqunê wertê ni dı nımınunê weçinıtu bıvêne.',
'tooltip-watch' => 'Na pele lista huya şêrkerdişi ser ke',
'tooltip-recreate' => 'Na pele esterıte bo ki, nae oncia bıaferne',
'tooltip-upload' => 'Dest be bar-kerdene ke',
'tooltip-rollback' => '"Peyser bia" ebe jü tık pela iştırak(un)ê peyên|i(u) peyser ano.',
'tooltip-undo' => '"Peyser" ni vurnaişi peyser ano u modusê verqayt de vurnaisê formi keno ra.
Têser-kerdena jü sebebi rê xulasa de imkan dano cı.',
'tooltip-summary' => 'Xulasê da kılme cı kuye',

# Stylesheets
'common.css' => '/* CSSo ke itaro, serba çermu pêroine gurenino */',

# Browsing diffs
'previousdiff' => '← Vurnaiso khanêr',
'nextdiff' => 'Vurnaena newêre →',

# Media information
'file-info-size' => '$1 × $2 piksel, gırsênia dosya: $3, MIME tipê cı: $4',
'file-nohires' => 'Tewrêna berz rovıleşiyaene nêbena.',
'svg-long-desc' => 'Dosya SVGy, seha ke $1 × $2 pixels, gırşênia dosya: $3',
'show-big-image' => 'Rovıleşiyaena tame',

# Bad image list
'bad_image_list' => 'Sıkılo umumi niaro:

Teyna çiyo ke beno lista (rezê ke be * dest kenê cı) çımun ver de vênino.
Jü rêze de girêo sıftein gunê girêo de dosya xırabıne bo.
Na rêze de her girêo bin jê istisna vênino, yanê pelê ke dosya beno ke sero rêzbiyae asena.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-mo' => 'mo',
'variantname-zh-sg' => 'sg',
'variantname-zh-my' => 'mın',
'variantname-zh' => 'zh',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ek',
'variantname-sr-el' => 'sr-el',
'variantname-sr' => 'sr',

# Variants for Kazakh language
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-cn' => 'kk-kn',
'variantname-kk-cyrl' => 'kk-kırl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-areb',
'variantname-kk' => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Areb',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku' => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Kırl',
'variantname-tg-latn' => 'tg-Latn',
'variantname-tg' => 'tg',

# Metadata
'metadata' => 'Daê seri',
'metadata-help' => 'Na dosya de mıxtemelen melumatê ilawekerdeyê ke terefê kamera dicitale u cıfeteliyaoği ra darde biyê, estê.
Eke dosya de peydêna vuriyais biyo ki, beno ke taê melumati gorê vurnaisê newey khan mendê.',
'metadata-expand' => 'Arezekerdu bıasne',
'metadata-collapse' => 'Arezekerdu measne',
'metadata-fields' => "Resımê meydanê metadataê ke na pele de benê lista, pela resımasnaene de ke tabloê metadata gına waro, gureninê.
İ bini zê ''saekerdoğu'' nıminê.
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
* gpsaltitude",

# EXIF tags
'exif-imagewidth' => 'Verındêni',
'exif-imagelength' => 'Berzêni',

'exif-meteringmode-255' => 'Bin',

# External editor support
'edit-externally' => 'Na dosya be mırecaetê de teberi bıvurne',
'edit-externally-help' => '(Serba daêna melumati qaytê pelga [//www.mediawiki.org/wiki/Manual:External_editors ayarê gurenaena teberi] be)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'pêro',
'namespacesall' => 'pêro',
'monthsall' => 'pêro',
'limitall' => 'pêro',

# action=purge
'confirm_purge_button' => 'Temam',

# Table pager
'table_pager_limit_submit' => 'So',

# Watchlist editing tools
'watchlisttools-view' => 'Vurnaisunê alaqadaru bımısne',
'watchlisttools-edit' => 'Lista şêrkerdene bıvêne u vıraze',
'watchlisttools-raw' => 'Lista şêrkerdena xame vıraze',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Teme:\'\'\' Tuşê default sort "$2" sero tuşê default sort "$1"î ra şino.',

# Special:FilePath
'filepath-page' => 'Dosya:',
'filepath-submit' => 'So',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Namê dosya:',

# Special:SpecialPages
'specialpages' => 'Pelê xaşi',
'specialpages-note' => '----
* Pelê xususiyê normali.
* <span class="mw-specialpagerestricted">Pelê xususiyê mehcuri.</span>',
'specialpages-group-maintenance' => 'Tebliğê baxımi',
'specialpages-group-other' => 'Pelê xususiyê bini',
'specialpages-group-login' => 'Cıkotene / qeyd',
'specialpages-group-changes' => 'Vurnais u protokolê pêyêni',
'specialpages-group-media' => 'Raporê medya u bar-kerdey',
'specialpages-group-users' => 'Karber u heqi',
'specialpages-group-highuse' => 'Pelê jêdêr gurenaey',
'specialpages-group-pages' => 'Lista pelun',
'specialpages-group-pagetools' => 'Hacetê pele',
'specialpages-group-wiki' => "Daê ''Wiki''y u haceti",
'specialpages-group-redirects' => 'Newe-vırastena pelunê xususiyun',
'specialpages-group-spam' => "Hacetê ''spam''i",

# Special:BlankPage
'blankpage' => 'Pela thale',

# External image whitelist
'external_image_whitelist' => '  #no satır zey xo verde/raverde<pre>
#parçeyê ifadeya rêzbiyayeyani (têna zerreyê ıney de // ) u çıtayo/çiyo zi mende cêr de têare kerê.
#ney URL ya (hotlink) resmê teberi de hemcıta benî.
#Ê yê ke hemcıt (eşleşmek-hemçift) biyê zey resımi asenî, eqsê hal de zi zey gıreyê resmi aseno.
satır ê ke pê ney # # destpêkenê zey mışore/mıjore muamele vineno.
#herfa gırd û qıci ferq nêkeno

#parçeyê ifadeya rêzbiyayeyani bıerzê serê ney satıri. no satır zey xo verde/raverde </pre>',

# Special:Tags
'tag-filter' => 'Parzünê [[Special:Tags|etiket]]i:',

# Special:ComparePages
'compare-page1' => 'Pele 1',
'compare-page2' => 'Pele 2',

);
