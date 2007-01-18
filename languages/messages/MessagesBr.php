<?php

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Dibar',
	NS_MAIN           => '',
	NS_TALK           => 'Kaozeal',
	NS_USER           => 'Implijer',
	NS_USER_TALK      => 'Kaozeadenn_Implijer',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Kaozeadenn_$1',
	NS_IMAGE          => 'Skeudenn',
	NS_IMAGE_TALK     => 'Kaozeadenn_Skeudenn',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Kaozeadenn_MediaWiki',
	NS_TEMPLATE       => 'Patrom',
	NS_TEMPLATE_TALK  => 'Kaozeadenn_Patrom',
	NS_HELP           => 'Skoazell',
	NS_HELP_TALK      => 'Kaozeadenn_Skoazell',
	NS_CATEGORY       => 'Rummad',
	NS_CATEGORY_TALK  => 'Kaozeadenn_Rummad'
);

$quickbarSettings = array(
	'Hini ebet', 'Kleiz', 'Dehou', 'War-neuñv a-gleiz'
);

$skinNames = array(
	'standard'     => 'Standard',
	'nostalgia'    => 'Melkoni',
	'cologneblue'  => 'Glaz Kologn',
	'smarty'       => 'Paddington',
	'montparnasse' => 'Montparnasse',
	'davinci'      => 'DaVinci',
	'mono'         => 'Mono',
	'monobook'     => 'MonoBook',
	'myskin'       => 'MySkin'
);

$bookstoreList = array(
	'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y "da" H:i',
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );
$linkTrail = "/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙ]+)(.*)$/sDu";

$messages = array(
# User preference toggles
'tog-underline'               => 'Liammoù islinennet',
'tog-highlightbroken'         => "Lakaat e ruz al liammoù war-du<br /> an danvezioù n'eus ket anezho",
'tog-justify'                 => 'Rannbennadoù marzhekaet',
'tog-hideminor'               => "Kuzhat ar <i>C'hemmoù nevez</i> dister",
'tog-usenewrc'                => 'Kemmoù nevez gwellaet<br /> (gant merdeerioù zo hepken)',
'tog-numberheadings'          => 'Niverenniñ emgefre an titloù',
'tog-showtoolbar'             => 'Diskouez ar varrenn gant ar meuzioù aozañ',
'tog-editondblclick'          => 'Daouglikañ evit kemmañ ur bajenn (JavaScript)',
'tog-editsection'             => 'Kemmañ ur rann dre al liammoù [kemmañ]',
'tog-editsectiononrightclick' => 'Kemmañ ur rann dre glikañ a-zehou<br /> war titl ar rann',
'tog-showtoc'                 => 'Diskouez an daolenn<br /> (evit ar pennadoù zo ouzhpenn 3 rann enno)',
'tog-rememberpassword'        => "Derc'hel soñj eus ma ger-temen (toupin)",
'tog-editwidth'               => 'Digeriñ ar prenestr aozañ en e led brasañ',
'tog-watchcreations'          => 'Evezhiañ ar pajennoù krouet ganin',
'tog-watchdefault'            => 'Evezhiañ ar pennadoù savet pe kemmet ganin',
'tog-minordefault'            => "Sellet ouzh ar c'hemmoù degaset ganin<br /> evel kemmoù dister dre ziouer",
'tog-previewontop'            => 'Rakwelet e vo tres ar bajenn<br /> a-us ar voest skridaozañ',
'tog-previewonfirst'          => 'Rakwelet tres ar bajenn kerkent hag an aozadenn gentañ',
'tog-nocache'                 => 'Diweredekaat krubuilh ar pajennoù',
'tog-enotifwatchlistpages'    => 'Kas ur postel din pa vez degaset kemmoù war ur bajenn evezhiet ganin',
'tog-fancysig'                => 'Sinadurioù diliamm (hep liamm emgefre)',
'tog-uselivepreview'          => 'Implijout Rakwelet prim (JavaScript) (taol-arnod)',

'underline-always'  => 'Atav',
'underline-never'   => 'Morse',
'underline-default' => 'Diouzh ar merdeer',

'skinpreview' => '(Rakwelet)',

# Dates
'sunday'        => 'Sul',
'monday'        => 'Lun',
'tuesday'       => 'Meurzh',
'wednesday'     => "Merc'her",
'thursday'      => 'Yaou',
'friday'        => 'Gwener',
'saturday'      => 'Sadorn',
'sun'           => 'Sul',
'mon'           => 'Lun',
'tue'           => 'Meu',
'wed'           => 'Mer',
'thu'           => 'Meu',
'fri'           => 'Gwe',
'sat'           => 'Sad',
'january'       => 'Genver',
'february'      => "C'hwevrer",
'march'         => 'Meurzh',
'april'         => 'Ebrel',
'may_long'      => 'Mae',
'june'          => 'Mezheven',
'july'          => 'Gouere',
'august'        => 'Eost',
'september'     => 'Gwengolo',
'october'       => 'Here',
'november'      => 'Du',
'december'      => 'Kerzu',
'january-gen'   => 'Genver',
'february-gen'  => "C'hwevrer",
'march-gen'     => 'Meurzh',
'april-gen'     => 'Ebrel',
'may-gen'       => 'Mae',
'june-gen'      => 'Mezheven',
'july-gen'      => 'Gouere',
'august-gen'    => 'Eost',
'september-gen' => 'Gwengolo',
'october-gen'   => 'Here',
'november-gen'  => 'Du',
'december-gen'  => 'Kerzu',
'jan'           => 'Gen',
'feb'           => "C'hwe",
'mar'           => 'Meu',
'apr'           => 'Ebr',
'may'           => 'Mae',
'jun'           => 'Mez',
'jul'           => 'Gou',
'aug'           => 'Eos',
'sep'           => 'Gwe',
'oct'           => 'Her',
'nov'           => 'Du',
'dec'           => 'Kzu',

# Bits of text used by many pages
'categories'            => 'Rummadoù ar bajenn',
'pagecategories'        => 'Rummadoù ar bajenn',
'category_header'       => 'Niver a bennadoù er rummad "$1"',
'subcategories'         => 'Isrummad',
'category-media-header' => 'Restroù liesvedia er rummad "$1"',

'mainpage'     => 'Degemer',
'mainpagetext' => 'Meziant {{SITENAME}} staliet.',

'portal'          => 'Porched ar gumuniezh',
'portal-url'      => '{{ns:4}}:Degemer',
'about'           => 'Diwar-benn',
'aboutsite'       => 'Diwar-benn {{SITENAME}}',
'aboutpage'       => '{{ns:4}}:Diwar-benn',
'article'         => 'Pennad',
'help'            => 'Skoazell',
'helppage'        => '{{ns:4}}:Skoazell',
'bugreports'      => 'Teul an drein',
'bugreportspage'  => '{{ns:4}}:Teul an drein',
'sitesupport'     => 'Roadoù',
'sitesupport-url' => 'Project:Roadoù',
'faq'             => 'FAG',
'faqpage'         => '{{ns:4}}:FAG',
'edithelp'        => 'Skoazell',
'newwindow'       => '(digeriñ en ur prenestr nevez)',
'edithelppage'    => '{{ns:4}}:Penaos degas kemmoù en ur bajenn',
'cancel'          => 'Nullañ',
'qbfind'          => 'Klask',
'qbbrowse'        => 'Furchal',
'qbedit'          => 'Kemmañ',
'qbpageoptions'   => 'Pajenn an dibaboù',
'qbpageinfo'      => 'Pajenn gelaouiñ',
'qbmyoptions'     => 'Ma dibaboù',
'qbspecialpages'  => 'Pajennoù dibar',
'moredotdotdot'   => "Ha muioc'h c'hoazh...",
'mypage'          => 'Ma zammig pajenn',
'mytalk'          => "Ma c'haozeadennoù",
'anontalk'        => "Kaozeal gant ar chomlec'h IP-mañ",
'navigation'      => 'Merdeiñ',

'currentevents'     => 'Keleier',
'currentevents-url' => 'Keleier',

'disclaimers'       => 'Kemennoù',
'disclaimerpage'    => 'Project:Kemenn hollek',
'privacy'           => 'Reolennoù prevezded',
'privacypage'       => 'Project:Reolennoù prevezded',
'errorpagetitle'    => 'Fazi',
'returnto'          => "Distreiñ d'ar bajenn $1.",
'tagline'           => "Ur pennad tennet eus {{SITENAME}}, ar c'helc'hgeriadur digor.",
'search'            => 'Klask',
'searchbutton'      => 'Klask',
'go'                => 'Kas',
'searcharticle'     => 'Mont',
'history'           => 'Istor ar bajenn',
'history_short'     => 'Istor',
'printableversion'  => 'Stumm da voullañ',
'permalink'         => "Chomlec'h ar stumm-mañ",
'print'             => 'Moullañ',
'edit'              => 'Kemmañ',
'editthispage'      => 'Kemmañ ar bajenn-mañ',
'delete'            => 'Diverkañ',
'deletethispage'    => 'Diverkañ ar bajenn-mañ',
'undelete_short'    => 'Diziverkañ',
'protect'           => 'Gwareziñ',
'protectthispage'   => 'Gwareziñ ar bajenn-mañ',
'unprotect'         => 'Diwareziñ',
'unprotectthispage' => 'Diwareziñ ar bajenn-mañ',
'newpage'           => 'Pajenn nevez',
'talkpage'          => 'Pajenn gaozeal',
'specialpage'       => 'Pajenn zibar',
'personaltools'     => 'Ostilhoù personel',
'postcomment'       => 'Ouzhpennañ e soñj',
'articlepage'       => 'Sellet ouzh ar pennad',
'talk'              => 'Kaozeadenn',
'toolbox'           => 'Boest ostilhoù',
'userpage'          => 'Pajenn implijer',
'projectpage'       => 'Pajenn meta',
'imagepage'         => 'Pajenn skeudenn',
'viewhelppage'      => 'Gwelet ar bajenn skoazell',
'categorypage'      => 'Gwelet pajenn ar rummadoù',
'viewtalkpage'      => 'Pajenn gaozeal',
'otherlanguages'    => 'Yezhoù all',
'redirectedfrom'    => '(Adkaset eus $1)',
'redirectpagesub'   => 'Pajenn adkas',
'lastmodifiedat'    => "Kemmoù diwezhañ degaset d'ar bajenn-mañ : $2, $1.", # $1 date, $2 time
'viewcount'         => 'Sellet ez eus bet ouzh ar bajenn-mañ $1 (g)wech.',
'copyright'         => "Danvez a c'haller implijout dindan $1.",
'protectedpage'     => 'Pajenn warezet',
'jumpto'            => 'Mont da :',
'jumptonavigation'  => 'merdeiñ',
'jumptosearch'      => 'klask',

'badaccess'        => 'Fazi aotre',
'badaccess-group0' => "N'oc'h ket aotreet da seveniñ ar pezh hoc'h eus goulennet.",
'badaccess-group1' => 'Miret eo an ober-mañ evit an dud er strollad $1 hepken.',
'badaccess-group2' => 'Miret eo an ober-mañ evit an dud en unan eus ar strolladoù $1 hepken.',
'badaccess-groups' => 'Miret eo an ober-mañ evit an dud en unan eus ar strolladoù $1 hepken.',

'versionrequired'     => 'Rekis eo Stumm $1 MediaWiki',
'versionrequiredtext' => 'Rekis eo stumm $1 MediaWiki evit implijout ar bajenn-mañ. Sellit ouzh [[Special:Version]]',

'ok'                  => 'Mat eo',
'retrievedfrom'       => 'Adtapet diwar « $1 »',
'youhavenewmessages'  => "$1 zo ganeoc'h ($2).",
'newmessageslink'     => 'Kemennoù nevez',
'newmessagesdifflink' => "Diforc'hioù e-keñver ar stumm kent",
'editsection'         => 'kemmañ',
'editold'             => 'kemmañ',
'editsectionhint'     => 'Kemmañ ar rann : $1',
'toc'                 => 'Taolenn',
'showtoc'             => 'diskouez',
'hidetoc'             => 'kuzhat',
'thisisdeleted'       => 'Diskouez pe diziverkañ $1 ?',
'viewdeleted'         => 'Gwelet $1?',
'restorelink'         => "1 c'hemm diverket",
'feedlinks'           => 'Lusk:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Pennad',
'nstab-user'      => 'Pajenn implijer',
'nstab-media'     => 'Media',
'nstab-special'   => 'Dibar',
'nstab-project'   => 'Diwar-benn',
'nstab-image'     => 'Skeudenn',
'nstab-mediawiki' => 'Kemennadenn',
'nstab-template'  => 'Patrom',
'nstab-help'      => 'Skoazell',
'nstab-category'  => 'Rummad',

# Main script and global functions
'nosuchaction'      => 'Ober dianv',
'nosuchactiontext'  => "N'eo ket anavezet gant ar wiki an ober spisaet en Url.",
'nosuchspecialpage' => "N'eus ket eus ar bajenn zibar-mañ",
'nospecialpagetext' => "Goulennet hoc'h eus ur bajenn zibar n'eo ket anavezet gant ar wiki.",

# General errors
'error'                => 'Fazi',
'databaseerror'        => 'Fazi bank roadennoù',
'dberrortext'          => 'Fazi ereadur er bank roadennoù. Setu ar goulenn bet pledet gantañ da ziwezhañ :
<blockquote><tt>$1</tt></blockquote>
adal an arc\'hwel "<tt>$2</tt>".
Adkaset eo bet ar fazi "<tt>$3: $4</tt>" gant MySQL.',
'dberrortextcl'        => 'Ur fazi ereadur zo en ur goulenn graet ouzh ar bank roadennoù. Setu ar goulenn bet pledet gantañ da ziwezhañ :
"$1"
graet gant an arc\'hwel "$2"
adkaset eo bet ar fazi "$3 : $4" gant MySQL.',
'noconnect'            => "Ho tigarez! Da-heul kudennoù teknikel, n'haller ket kevreañ ouzh ar bank roadennoù evit poent.
<br />
$1",
'nodb'                 => 'Dibosupl dibab ar bank roadennoù $1',
'cachederror'          => "Un eilenn eus ar bajenn goulennet eo homañ; marteze n'eo ket bet hizivaet",
'laggedslavemode'      => "Diwallit : marteze a-walc'h n'emañ ket ar c'hemmoù diwezhañ war ar bajenn-mañ",
'readonly'             => 'Hizivadurioù stanket war ar bank roadennoù',
'enterlockreason'      => 'Merkit perak eo stanket hag istimit pegeit e chomo evel-henn',
'readonlytext'         => 'Stanket eo an ouzhpennadennoù hag an hizivadurioù war bank roadennoù {{SITENAME}}; moarvat peogwir emeur oc\h ober war-dro ar bank. Goude-se e vo plaen pep tra en-dro. Setu perak eo bet stanket ar bank gant ar merour :
<p>$1',
'missingarticle'       => "N'eo ket bet ar bank roadennoù evit kavout testenn ur bajenn zo anezhi c'hoazh gant an titl \"\$1\".
N'eo ket ur fazi gant ar bank roadennoù, un draen gant ar wiki marteze a-walc'h.
Kasit, ni ho ped, keloù eus an draen-mañ d'ur merour en ur verkañ mat dezhañ chomlec'h ar bajenn e kaoz.",
'readonly_lag'         => "Stanket eo bet ar bank roadennoù ent emgefre p'emañ an eilservijerioù oc'h adpakañ o dale e-keñver ar pennservijer",
'internalerror'        => 'Fazi diabarzh',
'filecopyerror'        => 'Dibosupl eilañ « $1 » war-du « $2 ».',
'filerenameerror'      => 'Dibosupl da adenvel « $1 » e « $2 ».',
'filedeleteerror'      => 'Dibosupl da ziverkañ « $1 ».',
'filenotfound'         => 'N\'haller ket kavout ar restr "$1".',
'unexpected'           => 'Talvoudenn dic\'hortoz : "$1"="$2".',
'formerror'            => 'Fazi: Dibosupl eo kinnig ar furmskrid',
'badarticleerror'      => "N'haller ket seveniñ an ober-mañ war ar bajenn-mañ.",
'cannotdelete'         => 'Dibosupl da ziverkañ ar bajenn pe ar skeudenn spisaet.',
'badtitle'             => 'Titl fall',
'badtitletext'         => "Faziek pe c'houllo eo titl ar bajenn goulennet; pe neuze eo faziek al liamm etreyezhel",
'perfdisabled'         => "Ho tigarez! Diweredekaet eo bet an arc'hwel-mañ evit poent rak gorrekaat a ra ar bank roadennoù kement ha ma n'hall ket mui den implijout ar wiki.",
'perfdisabledsub'      => 'Setu aze un eilenn savete eus $1:', # obsolete?
'wrong_wfQuery_params' => "Arventennoù faziek war an urzhiad wfQuery()<br />
Arc'hwel : $1<br />
Goulenn : $2",
'viewsource'           => 'Sellet ouzh tarzh an destenn',
'viewsourcefor'        => 'evit $1',
'viewsourcetext'       => 'Gallout a rit gwelet hag eilañ danvez ar bajenn-mañ',
'editinginterface'     => "'''Diwallit :''' Emaoc'h oc'h adaozañ ur bajenn a dalvez da sevel skridoù evit etrefas ar meziant. Ar c'hemmoù graet ouzh ar bajenn-mañ a zegaso kemmoù en etrefas evit an holl implijerien.",
'sqlhidden'            => '(Reked SQL kuzhet)',
'cascadeprotected'     => 'Gwarezet eo ar bajenn-mañ; n\'haller ket degas kemmoù enni peogwir he c\'haver er pajennoù da-heul zo bet gwarezet en ur zibab an dibarzh "diskennadoù" :',

# Login and logout pages
'logouttitle'                => 'Dilugañ',
'logouttext'                 => "Diluget oc'h bremañ.
Gallout a rit kenderc'hel da implijout {{SITENAME}} en un doare dizanv, pe en em lugañ en-dro gant un anv all mar fell deoc'h.",
'welcomecreation'            => '<h2>Degemer mat, $1!</h2><p>Krouet eo bet ho kont implijer.
Na zisoñjit ket da bersonelaat ho {{SITENAME}} en ur sellet ouzh pajenn ar Penndibaboù.',
'loginpagetitle'             => 'Ho tisklêriadenn',
'yourname'                   => "Hoc'h anv implijer",
'yourpassword'               => 'Ho ker-tremen',
'yourpasswordagain'          => 'Skrivit ho ker-tremen en-dro',
'remembermypassword'         => "Derc'hel soñj eus ma ger-tremen (toupin)",
'yourdomainname'             => 'Ho tomani',
'externaldberror'            => "Pe ez eus bet ur fazi gwiriekaat diavaez er bank titouroù pe n'oc'h ket aotreet da nevesaat ho kont diavaez.",
'loginproblem'               => '<b>Kudenn zisklêriañ.</b><br />Klaskit en-dro !',
'alreadyloggedin'            => "'''Implijer $1, disklêriet oc'h dija!'''<br />",
'login'                      => 'Disklêriañ',
'loginprompt'                => "Ret eo deoc'h bezañ gweredekaet an toupinoù evit bezañ luget ouzh {{SITENAME}}.",
'userlogin'                  => 'Krouiñ ur gont pe en em lugañ',
'logout'                     => 'Dilugañ',
'userlogout'                 => 'Dilugañ',
'notloggedin'                => 'Diluget',
'nologin'                    => "N'oc'h ket luget ? $1.",
'nologinlink'                => 'Krouiñ ur gont',
'createaccount'              => 'Krouiñ ur gont nevez',
'gotaccount'                 => "Ur gont zo ganeoc'h dija ? $1.",
'gotaccountlink'             => 'En em lugañ',
'createaccountmail'          => 'dre bostel',
'badretype'                  => "N'eo ket peurheñvel an eil ouzh egile an daou c'her-tremen bet lakaet ganeoc'h.",
'userexists'                 => "Implijet eo dija an anv implijer lakaet ganeoc'h. Dibabit unan all mar plij.",
'youremail'                  => "Ma chomlec'h elektronek",
'username'                   => 'Anv implijer :',
'uid'                        => 'Niv. identelezh an implijer :',
'yourrealname'               => "Hoc'h anv gwir*",
'yourlanguage'               => 'Yezh an etrefas&nbsp;',
'yourvariant'                => 'Adstumm',
'yournick'                   => "Sinadur evit ar c'haozeadennoù (gant ~~~)",
'badsig'                     => 'Direizh eo ho sinadur kriz; gwiriit ho palizennoù HTML.',
'email'                      => 'Postel',
'prefs-help-email-enotif'    => "Implijet e vez ar chomlec'h-mañ evit kas deoc'h kemennadennoù dre bostel ivez m'hoc'h eus dibabet an dibarzhioù ret evit se.",
'prefs-help-realname'        => "* <strong>Hoc'h anv</strong> (diret): ma vez spisaet ganeoc'h e vo implijet evit merkañ ho tegasadennoù.",
'loginerror'                 => 'Kudenn zisklêriañ',
'prefs-help-email'           => "* <strong>Chomlec'h postel</strong> (diret): gantañ e vo aes mont e darempred ganeoc'h adal al lec'hienn o terc'hel kuzh ho chomlec'h, hag adkas ur ger-tremen deoc'h ma tichañsfe deoc'h koll ho hini.",
'nocookiesnew'               => "krouet eo bet ar gont implijer met n'hoc'h ket luget. {{SITENAME}} a implij toupinoù evit al lugañ met diweredekaet eo an toupinoù ganeoc'h. Trugarez da weredekaat anezho ha d'en em lugañ en-dro.",
'nocookieslogin'             => "{{SITENAME}} a implij toupinoù evit al lugañ met diweredekaet eo an toupinoù ganeoc'h. Trugarez da weredekaat anezho ha d'en em lugañ en-dro.",
'noname'                     => "N'hoc'h eus lakaet anv implijer ebet.",
'loginsuccesstitle'          => "Disklêriet oc'h.",
'loginsuccess'               => 'Luget oc\'h bremañ war {{SITENAME}} evel "$1".',
'nosuchuser'                 => 'N\'eus ket eus an implijer "$1".
Gwiriit eo bet skrivet mat an anv ganeoc\'h pe implijit ar furmskrid a-is a-benn krouiñ ur gont implijer nevez.',
'nosuchusershort'            => "N'eus perzhiad ebet gantañ an anv « $1 ». Gwiriit ar reizhskrivadur.",
'nouserspecified'            => "Ret eo deoc'h spisaat un anv implijer.",
'wrongpassword'              => 'Ger-tremen kamm. Klaskit en-dro.',
'wrongpasswordempty'         => 'Ger-tremen ebet. Lakait unan mar plij.',
'mailmypassword'             => 'Kasit din ur ger-tremen nevez',
'passwordremindertitle'      => 'Ho ker-tremen nevez war {{SITENAME}}',
'passwordremindertext'       => 'Unan bennak (c\'hwi moarvat) gant ar chomlec\'h IP $1 en deus goulennet ma vo kaset deoc\'h ur ger-tremen nevez evit monet war ar wiki.
Ger-tremen an implijer "$2" zo bremañ "$3".
Erbediñ a reomp deoc\'h en em lugañ ha kemmañ ar ger-termen-mañ an abretañ ar gwellañ.',
'noemail'                    => 'N\'eus bet enrollet chomlec\'h elektronek ebet evit an implijer "$1".',
'passwordsent'               => 'Kaset ez eus bet ur ger-tremen nevez da chomlec\'h elektronek an implijer "$1".
Trugarez deoc\'h evit en em zisklêriañ kerkent ha ma vo bet resevet ganeoc\'h.',
'blocked-mailpassword'       => "N'haller ket degas kemmoù adal ar chomlec'h IP-mañ ken, gant se n'hallit ket implijout an arc'hwel adtapout gerioù-tremen, kuit m'en em ledfe kammvoazioù.",
'eauthentsent'               => "Kaset ez eus bet ur postel kadarnaat war-du ar chomlec'h postel spisaet. 
A-raok na vije kaset postel ebet d'ar gont-se e vo ret deoc'h heuliañ ar c'huzulioù merket er postel resevet evit kadarnaat ez eo mat ho kont deoc'h.",
'mailerror'                  => 'Fazi en ur gas ar postel : $1',
'acct_creation_throttle_hit' => "Ho tigarez, krouet ez eus bet $1 (c'h)gont ganeoc'h dija. N'hallit ket krouiñ unan nevez.",
'emailauthenticated'         => "Gwiriet eo bet ho chomlec'h postel d'an $1.",
'emailnotauthenticated'      => "N'eo ket bet gwiriekaet ho chomlec'h postel evit c'hoazh. Ne vo ket tu da gas postel ebet evit hini ebet eus an dezverkoù a-heul.",
'emailconfirmlink'           => "Kadarnait ho chomlec'h postel",
'accountcreated'             => 'Kont krouet',
'accountcreatedtext'         => 'Krouet eo bet kont implijer $1.',

# Edit page toolbar
'bold_sample'     => 'Testenn dev',
'bold_tip'        => 'Testenn dev',
'italic_sample'   => 'Testenn italek',
'italic_tip'      => 'Testenn italek',
'link_sample'     => 'Liamm titl',
'link_tip'        => 'Liamm diabarzh',
'extlink_sample'  => 'http://www.example.com liamm titl',
'extlink_tip'     => 'Liamm diavaez (na zisoñjit ket http://)',
'headline_sample' => 'Testenn istitl',
'headline_tip'    => 'Istitl live 2',
'math_sample'     => 'Lakait ho formulenn amañ',
'math_tip'        => 'Formulenn jedoniel (LaTeX)',
'nowiki_sample'   => 'Lakait an destenn anfurmadet amañ',
'nowiki_tip'      => 'Na deuler pled ouzh eradur ar wiki',
'image_sample'    => 'Skouer.jpg',
'image_tip'       => 'Skeudenn enframmet',
'media_sample'    => 'Skouer.ogg',
'media_tip'       => 'Liamm restr media',
'sig_tip'         => 'Ho sinadur gant an deiziad',
'hr_tip'          => 'Liamm a-led (arabat implijout re)',

# Edit pages
'summary'                  => 'Diverrañ&nbsp;',
'subject'                  => 'Danvez/titl',
'minoredit'                => 'Kemm dister.',
'watchthis'                => 'Evezhiañ ar pennad-mañ',
'savearticle'              => 'Enrollañ',
'preview'                  => 'Rakwelet',
'showpreview'              => 'Rakwelet',
'showlivepreview'          => 'Rakwelet prim',
'showdiff'                 => "Diskouez ar c'hemmoù",
'anoneditwarning'          => "'''Diwallit :''' N'oc'h ket luget. Ho chomlec'h IP eo a vo enrollet war istor kemmoù ar bajenn-mañ.",
'blockedtitle'             => 'Implijer stanket',
'blockedtext'              => "<big>'''Stanket eo bet ho kont implijer pe ho chomlec'h IP'''</big> 

Gant $1 eo bet graet. Setu an abeg : ''$2''.

Gallout a rit mont e darempred gant $1 pe gant unan eus ar [[{{ns:project}}:Merourien|verourien]] all evit eskemm ganto war se. N'hallit implijout an arc'hwel 'kas ur postel d'an implijer-mañ' nemet ma' eus bet spisaet ganeoc'h ur chomlec'h postel reizh en ho
[[Special:Preferences|penndibaboù kont]]. $3 eo ho chomlec'h IP, ha #$5 eo an niverenn identelezh stanket. Implijit anezho en ho koulennoù bep tro.",
'blockedoriginalsource'    => "Kavout a reot mammenn '''$1''' a-is:",
'blockededitsource'        => "Kavout a reot testenn ho '''kemmoù''' war '''$1''' a-is :",
'whitelistedittitle'       => 'Ret eo bezañ luget evit skridaozañ',
'whitelistedittext'        => "Ret eo deoc'h en em $1 evit gallout skridaozañ",
'whitelistreadtitle'       => 'Ret eo bezañ luget evit gallout lenn',
'whitelistreadtext'        => 'Ret eo bezañ [[Special:Userlogin|luget]] evit gallout lenn ar pennadoù',
'whitelistacctitle'        => "N'hoc'h ket aotreet da grouiñ ur gont",
'whitelistacctext'         => 'A-benn gallout krouiñ ur gont war ar Wiki-mañ e rankit bezañ [[Special:Userlogin|luget]] ha kaout an aotreoù rekis',
'confirmedittitle'         => "Ret eo kadarnaat e chomlec'h postel a-raok kemmañ pajennoù",
'confirmedittext'          => "Rankout a ri bezañ kadarnaet ho chomlec'h postel a-raok gellout degas kemmoù er pajennoù. Skrivit ha kadarnait ho chomlec'h postel en ho [[Special:Preferences|penndibaboù implijer]] mar plij.",
'loginreqtitle'            => 'Anv implijer rekis',
'loginreqpagetext'         => "Ret eo deoc'h $1 evit gwelet pajennoù all.",
'accmailtitle'             => 'Ger-tremen kaset.',
'accmailtext'              => 'Kaset eo bet ger-tremen « $1 » da $2.',
'newarticle'               => '(Nevez)',
'newarticletext'           => 'Skrivit amañ testenn ho pennad.',
'anontalkpagetext'         => "---- ''Homañ eo ar bajenn gaozeal evit un implijer(ez) dianv n'eus ket c'hoazh krouet kont ebet pe na implij ket anezhi. Setu perak e rankomp ober gant ar [[chomlec'h IP]] niverel evit disklêriañ anezhañ/i. Gallout a ra ur chomlec'h a seurt-se bezañ rannet etre meur a implijer(ez). Ma'z oc'h un implijer(ez) dianv ha ma stadit ez eus bet kaset deoc'h kemennadennoù na sellont ket ouzhoc'h, gallout a rit [[Special:Userlogin|krouiñ ur gont pe en em lugañ]] kuit a vagañ muioc'h a gemmesk.",
'noarticletext'            => "(N'eus evit poent tamm skrid ebet war ar bajenn-mañ)",
'clearyourcache'           => "'''Notenn:''' Goude bezañ enrollet ho pajenn e rankit adkargañ anezhi a-ratozh evit gwelet ar c'hemmoù : '''Internet Explorer''' : ''ctrl-f5'', '''Mozilla / Firefox''' : ''ctrl-shift-r'', '''Safari''' : ''cmd-shift-r'', '''Konqueror''' : ''f5''.",
'usercssjsyoucanpreview'   => "'''Tun:''' grit gant ar bouton '''Rakwelet''' evit testiñ ho follenn css/js nevez a-raok enrollañ anezhi.",
'usercsspreview'           => "'''Dalc'hit soñj emaoc'h o rakwelet ho follenn css deoc'h ha n'eo ket bet enrollet c'hoazh!'''",
'userjspreview'            => "'''Dalc'hit soñj emaoc'h o rakwelet pe o testiñ ho kod javascript deoc'h ha n'eo ket bet enrollet c'hoazh!'''",
'userinvalidcssjstitle'    => "'''Diwallit:''' N'eus tamm gwiskadur \"\$1\" ebet. Ho pez soñj e vez implijet lizherennoù bihan goude an anv implijer hag ar veskell / gant ar pajennoù personel dezho un astenn .css ha .js; da skouer eo mat ar follenn stil Implijer:Foo/monobook.css ha faziek an hini implijer:Foo/Monobook.css.",
'updated'                  => '(Hizivaet)',
'note'                     => '<strong>Notenn :</strong>',
'previewnote'              => "<strong>Diwallit mat, n'eo homañ nemet ur rakweladenn, n'eo ket enrollet an destenn c'hoazh!",
'previewconflict'          => 'Gant ar rakweladenn e teu testenn ar bajenn war wel evel ma vo pa vo bet enrollet.',
'session_fail_preview'     => "<strong>Ho tigarez! N'eus ket bet tu da enrollañ ho kemmoù rak kollet eo bet roadennoù an dalc'h. Klaskit en-dro mar plij. Ma ne'z a ket en-dro c'hoazh, klaskit dilugañ ho kont ha lugañ en-dro.</strong>",
'editing'                  => "oc'h aozañ $1",
'editinguser'              => "oc'h aozañ $1",
'editingsection'           => "oc'h aozañ $1 (rann)",
'editingcomment'           => "oc'h aozañ $1 (soñj)",
'editconflict'             => 'tabut kemmañ : $1',
'explainconflict'          => "<b>Enrollet eo bet ar bajenn-mañ war-lerc'h m'ho pefe kroget d'he c'hemmañ.
E-krec'h an takad aozañ emañ an destenn evel m'emañ enrollet bremañ er bank roadennoù. Ho kemmoù deoc'h a zeu war wel en takad aozañ traoñ. Ret e vo deoc'h degas ho kemmoù d'an destenn zo evit poent. N'eus nemet an destenn zo en takad krec'h a vo saveteet.<br />",
'yourtext'                 => 'Ho testenn',
'storedversion'            => 'Stumm enrollet',
'editingold'               => "<strong>Diwallit : o kemm ur stumm kozh eus ar bajenn-mañ emaoc'h. Mard enrollit bremañ e vo kollet an holl gemmoù bet graet abaoe ar stumm-se.</strong>",
'yourdiff'                 => "Diforc'hioù",
'copyrightwarning'         => "Sellet e vez ouzh an holl degasadennoù graet war {{SITENAME}} evel ouzh degasadennoù a zouj da dermenoù ar $2 (Sellet ouzh $1 evit gouzout hiroc'h). Mar ne fell ket deoc'h e vefe embannet ha skignet ho skridoù, arabat kas anezho.<br />
Heñveldra, prometiñ a rit kemer perzh dre zegas skridoù savet ganeoc'h hepken pe tennet eus ur vammenn frank a wirioù. 
<strong>NA IMPLIJIT KET LABOURIOÙ GANT GWIRIOÙ AOZER (COPYRIGHT) HEP AOTRE D'OBER KEMENT-SE!</strong>",
'copyrightwarning2'        => "Notit mat e c'hall kement degasadenn graet ganeoc'h war {{SITENAME}} bezañ kemmet, adaozet pe lamet kuit gant an implijerien all. Mar ne fell ket deoc'h e vije kemmet-digemmet ar pezh hoc'h eus skrivet na gemerit ket perzh er raktres-mañ.<br /> Gouestlañ a rit ivez eo bet savet ar boued spered ganeoc'h pe eilet diwar ur vammenn frank a wirioù pe en domani foran (gwelet $1 evit gouzout hiroc'h). <strong>NA IMPLIJIT KET LABOURIOÙ GANT GWIRIOÙ AOZER HEP AOTRE D'OBER KEMENT-SE!</strong>",
'longpagewarning'          => "<strong>KEMENN DIWALL: $1 ko eo hed ar bajenn-mañ;
merdeerioù zo o deus poan da verañ ar pajennoù tro-dro pe en tu all da 32 ko pa vezont savet.
Marteze e c'hallfec'h rannañ ar bajenn e rannoù bihanoc'h.</strong>",
'readonlywarning'          => "<strong>KEMENN DIWALL: stanket eo bet ar bajenn-mañ evit bezañ trezalc'het,
n'oc'h ket evit enrollañ ho kemmoù diouzhtu eta. Gallout a rit eilañ an destenn en ur restr hag enrollañ anezhi diwezhatoc'hik.</strong>",
'protectedpagewarning'     => "<strong>KEMENN DIWALL: stanket eo bet ar bajenn-mañ.
N'eus nemet an implijerien ganto ar statud a verourien a c'hall degas kemmoù enni. Bezit sur ec'h heuilhit an [[Project:Pajenn_warezet|erbedadennoù a denn d'ar pajennoù gwarezet]].<strong>",
'semiprotectedpagewarning' => "''Notenn :''' Gwarezet eo ar bajenn-mañ; n'eus nemet an implijerien bet krouet ur gont ganto a c'hall degas kemmoù enni.",
'cascadeprotectedwarning'  => "<strong>DIWALLIT: Prennet eo ar bajenn-mañ. N'eus nemet ar verourien a c'hall degas kemmoù enni peogwir he c'haver e-touez ar pajennoù da-heul zo bet gwarezet en ur zibab an dibarzh \"diskennadoù\".</strong>:",
'templatesused'            => 'Patromoù implijet war ar bajenn-mañ :',
'template-protected'       => '(gwarezet)',
'template-semiprotected'   => '(damwarezet)',
'edittools'                => '<!-- Diskouezet e vo an destenn kinniget amañ dindan ar sternioù kemmañ ha kargañ. -->',

# Account creation failure
'cantcreateaccounttitle' => 'Dibosupl krouiñ ar gont',
'cantcreateaccounttext'  => "Stanket eo bet ar c'hrouiñ kontoù adal ar chomlec'h IP-mañ (<b>$1</b>). Moarvat eo abalamour d'an taolioù vandalerezh dizehan adal ho skol pe ho pourchaser servij Kenrouedad.",

# History pages
'revhistory'          => 'Stummoù kent',
'viewpagelogs'        => 'Gwelet al logoù evit ar bajenn-mañ',
'nohistory'           => "Ar bajenn-mañ n'he deus tamm istor ebet.",
'revnotfound'         => "N'eo ket bet kavet ar stumm-mañ",
'revnotfoundtext'     => "N'eo ket bet kavet stumm kent ar bajenn-mañ. Gwiriit an URL lakaet ganeoc'h evit mont d'ar bajenn-mañ.",
'loadhist'            => 'O kargañ istor ar bajenn',
'currentrev'          => 'Stumm a-vremañ pe stumm red',
'revisionasof'        => 'Stumm eus an $1',
'previousrevision'    => '← Stumm kent',
'nextrevision'        => "Stumm war-lerc'h →",
'currentrevisionlink' => 'Gwelet ar stumm red',
'cur'                 => 'red',
'next'                => 'goude',
'last'                => 'diwez',
'orig'                => 'kent',
'histlegend'          => "Alc'hwez : (brem) = diforc'hioù gant ar stumm a-vremañ,
(diwez) = diforc'hioù gant ar stumm kent, K = kemm bihan",
'deletedrev'          => '[diverket]',
'histfirst'           => 'Kentañ',
'histlast'            => 'Diwezhañ',

# Diffs
'difference'                => "(Diforc'hioù etre ar stummoù)",
'loadingrev'                => 'o kargañ ar stumm kent evit keñveriañ',
'lineno'                    => 'Linenn $1:',
'editcurrent'               => 'Kemmañ stumm red ar bajenn-mañ',
'selectnewerversionfordiff' => "Dibab ur stumm nevesoc'h",
'selectolderversionfordiff' => "Dibab ur stumm koshoc'h",
'compareselectedversions'   => 'Keñveriañ ar stummoù diuzet',
'editundo'                  => 'dizober',
'diff-multi'                => "({{plural:$1|Ur reizhadenn da c'hortoz|$1 reizhadenn da c'hortoz}} kuzhet.)",

# Search results
'searchresults'         => "Disoc'h ar c'hlask",
'searchresulttext'      => "Evit kaout muioc'h a ditouroù diwar-benn ar c'hlask e {{SITENAME}}, sellet ouzh [[Project:Klask|Klask e-barzh {{SITENAME}}]].",
'searchsubtitle'        => 'Evit ar goulenn "[[:$1]]"',
'searchsubtitleinvalid' => 'Evit ar goulenn "$1"',
'badquery'              => 'Goulenn savet a-dreuz',
'badquerytext'          => "N'eus ket bet gallet plediñ gant ho koulenn.
Klasket hoc'h eus, moarvat, ur ger dindan teir lizherenn, ar pezh n'hallomp ket ober evit c'hoazh. Gallet hoc'h eus ober, ivez, ur fazi ereadur evel \"pesked ha skantenn\".
Klaskit gant ur goulenn all.",
'matchtotals'           => 'Klotañ a ra ar goulenn "$1" gant $2 (d/z)titl
pennad ha gant testenn $3 (b/f)pennad.',
'noexactmatch'          => "'''N'eus pajenn ebet anvet \"\$1\".''' Gallout a rit [[:\$1|krouiñ ar bajenn]].",
'titlematches'          => 'Klotadurioù gant an titloù',
'notitlematches'        => "N'emañ ar ger(ioù) goulennet e titl pennad ebet",
'textmatches'           => 'Klotadurioù en testennoù',
'notextmatches'         => "N'emañ ar ger(ioù) goulennet e testenn pennad ebet",
'prevn'                 => '$1 kent',
'nextn'                 => "$1 war-lerc'h",
'viewprevnext'          => 'Gwelet ($1) ($2) ($3).',
'showingresults'        => "Diskouez <b>$1</b> disoc'h adal an #<b>$2</b>.",
'showingresultsnum'     => "Diskouez <b>$3</b> disoc'h adal an #<b>$2</b>.",
'nonefound'             => '<strong>Notenn</strong>: alies eo liammet an diouer a zisoc\'hoù ouzh an implij a vez graet eus termenoù klask re stank, evel "da" pe "ha",
termenoù n\'int ket menegeret, pe ouzh an implij a meur a dermen klask (en disoc\'hoù ne gaver nemet ar pajennoù enno an holl c\'herioù spisaet).',
'powersearch'           => 'Klask',
'powersearchtext'       => '
Klask en esaouennoù :<br />
$1<br />
$2 Lakaat ivez ar pajennoù adkas &nbsp; Klask $3 $9',
'searchdisabled'        => "<p>Diweredekaet eo bet an arc'hwel klask war an destenn a-bezh evit ur frapad rak ur samm re vras e oa evit ar servijer. Emichañs e vo tu d'e adlakaat pa vo ur servijer galloudusoc'h ganeomp. Da c'hortoz e c'hallit klask gant Google:</p>",
'blanknamespace'        => '(Pennañ)',

# Preferences page
'preferences'           => 'Penndibaboù',
'mypreferences'         => 'penndibaboù',
'prefsnologin'          => 'Diluget',
'prefsnologintext'      => "ret eo deoc'h bezañ [[Special:Userlogin|luget]] evit kemm ho tibaboù implijer.",
'prefsreset'            => 'Adlakaet eo bet ar penndibaboù diouzh ar stumm bet enrollet.',
'qbsettings'            => 'Personelaat ar varrenn ostilhoù',
'changepassword'        => 'Kemmañ ar ger-tremen',
'skin'                  => 'Gwiskadur',
'math'                  => 'Tres ar jedoniezh',
'dateformat'            => 'Stumm an deiziad',
'datedefault'           => 'Dre ziouer',
'datetime'              => 'Deiziad hag eur',
'math_failure'          => 'Fazi jedoniezh',
'math_unknown_error'    => 'fazi dianv',
'math_unknown_function' => 'kevreizhenn jedoniel dianv',
'math_lexing_error'     => 'fazi ger',
'math_syntax_error'     => 'fazi ereadur',
'math_image_error'      => "C'hwitet eo bet ar gaozeadenn e PNG, gwiriit staliadur Latex, dvips, gs ha convert",
'math_bad_tmpdir'       => "N'hall ket krouiñ pe skrivañ er c'havlec'h da c'hortoz",
'math_bad_output'       => "N'hall ket krouiñ pe skrivañ er c'havlec'h ermaeziañ",
'math_notexvc'          => "N'hall ket an erounezeg 'texvc' bezañ kavet. Lennit math/README evit he c'hefluniañ.",
'prefs-personal'        => 'Titouroù personel',
'prefs-rc'              => 'Kemmoù diwezhañ ha diskouez ar rakweladurioù',
'prefs-watchlist'       => 'Roll evezhiañ',
'prefs-misc'            => 'Penndibaboù liesseurt',
'saveprefs'             => 'Enrollañ ar penndibaboù',
'resetprefs'            => 'Adlakaat ar penndibaboù kent',
'oldpassword'           => 'Ger-tremen kozh',
'newpassword'           => 'Ger-temen nevez&nbsp;',
'retypenew'             => 'Kadarnaat ar ger-tremen nevez',
'textboxsize'           => 'Ment ar prenestr kemmañ',
'rows'                  => 'Renkennadoù&nbsp;',
'columns'               => 'Bannoù',
'searchresultshead'     => "Doare diskouez disoc'hoù an enklaskoù",
'resultsperpage'        => 'Niver a respontoù dre bajenn&nbsp;',
'contextlines'          => 'Niver a linennoù dre respont',
'contextchars'          => 'Niver a arouezennoù kendestenn dre linenn',
'stubthreshold'         => 'Ment vihanañ ar pennadoù berr',
'recentchangescount'    => "Niver a ditloù er c'hemmoù diwezhañ",
'savedprefs'            => 'Enrollet eo bet ar penndibaboù.',
'timezonelegend'        => 'Takad eur',
'timezonetext'          => "Mar ne resisait ket al linkadur eur e vo graet gant eur Europa ar C'hornôg dre ziouer.",
'localtime'             => "Eur lec'hel",
'timezoneoffset'        => 'Linkadur eur',
'servertime'            => 'Eur ar servijer',
'guesstimezone'         => 'Ober gant talvoudenn ar merdeer',
'allowemail'            => 'Aotren ar posteloù a-berzh an implijerien all',
'defaultns'             => 'Klask en esaouennoù-mañ dre ziouer :',
'default'               => 'dre ziouer',
'files'                 => 'Restroù',

# User rights
'userrights-user-editname'   => 'Lakait un anv implijer :',
'editusergroup'              => 'Kemmañ ar strolladoù implijerien',
'userrights-editusergroup'   => 'Kemmañ strolladoù an implijer',
'userrights-groupsmember'    => 'Ezel eus :',
'userrights-groupsavailable' => 'Strolladoù zo :',
'userrights-groupshelp'      => "Diuzit ar strolladoù a fell deoc'h e vefe ouzhpennet an implijer dezho pe tennet diouto. 
Ne vo ket cheñchet ar strolladoù n'int ket bet diuzet. Gallout a rit diziuzañ ur strollad gant CTRL + Klik kleiz",

'grouppage-sysop' => '{{ns:project}}:Merourien',

# Recent changes
'changes'           => 'Kemm',
'recentchanges'     => 'Kemmoù diwezhañ',
'recentchangestext' => "War ar bajenn-mañ e c'hallot heuliañ ar c'hemmoù diwezhañ c'hoarvezet war {{SITENAME}}.
[[{{ns:4}}:Degemer|Degemer mat]] d'ar berzhidi nevez!
Taolit ur sell war ar pajennoù-mañ&nbsp;: [[{{ns:4}}:FAG|foar ar goulennoù]],
[[{{ns:4}}:Erbedadennoù ha reolennoù da heuliañ|erbedadennoù ha reolennoù da heuliañ]]
(peurgetket [[{{ns:4}}:Reolennoù envel|reolennoù envel]],
[[{{ns:4}}:Ur savboent neptu|ur savboent neptu]]),
hag [[{{ns:4}}:Ar fazioù stankañ|ar fazioù stankañ]].

Mar fell deoc'h e rafe berzh {{SITENAME}}, trugarez da chom hep degas ennañ dafar gwarezet gant [[{{ns:4}}:Copyright|gwirioù aozer (copyrights)]]. An atebegezh wiraouel a c'hallfe ober gaou d'ar raktres.",
'rcnote'            => "Setu aze an <strong>$1</strong> kemm diwezhañ bet c'hoarvezet e-pad an <strong>$2</strong> deiz diwezhañ, da $3.",
'rcnotefrom'        => "Setu aze roll ar c'hemmoù c'hoarvezet abaoe an/ar <strong>$2</strong> (<b>$1</b> d'ar muiañ).",
'rclistfrom'        => "Diskouez ar c'hemmoù diwezhañ abaoe an/ar $1.",
'rcshowhideminor'   => "$1 ar c'hemmoù dister",
'rcshowhidebots'    => '$1 ar robotoù',
'rcshowhideliu'     => '$1 an implijerien enrollet',
'rcshowhideanons'   => '$1 an implijerien dizanv',
'rcshowhidemine'    => "$1 ma c'hemmoù",
'rclinks'           => "Diskouez an/ar $1 (g/c'h)kemm diwezhañ c'hoarvezet e-pad an/ar $2 devezh diwezhañ; $3 kemmoù dister.",
'diff'              => "diforc'h",
'hist'              => 'ist',
'hide'              => 'kuzhat',
'show'              => 'diskouez',
'minoreditletter'   => 'D',
'rc_categories'     => 'Bevenn ar rummadoù (dispartiañ gant "|")',
'rc_categories_any' => 'An holl',

# Upload
'upload'                      => 'Eilañ war ar servijer',
'uploadbtn'                   => 'Eilañ ur restr',
'reupload'                    => 'Eilañ adarre',
'reuploaddesc'                => "Distreiñ d'ar furmskrid.",
'uploadnologin'               => 'diluget',
'uploadnologintext'           => "ret eo deoc'h bezañ [[Special:Userlogin|luget]]
evit eilañ restroù war ar servijer.",
'uploaderror'                 => 'Fazi',
'uploadtext'                  => "'''PAOUEZIT!''' A-raok eilañ ho restr war ar servijer,
sellit ouzh ar [[Project:Reolennoù implijout ar skeudennoù|reolennoù implijout skeudennoù]] war {{SITENAME}} ha bezit sur e rit diouto.<br />
Na zisoñjit ket leuniañ ar [[Project:Pajenn zeskrivañ ur skeudenn|bajenn zeskrivañ ur skeudenn]] pa vo war ar servijer.

Evit gwelet ar skeudennoù bet karget war ar servijer c'hoazh pe evit klask en o zouez, kit da [[Special:Imagelist|roll ar skeudennoù]].
Rollet eo an enporzhiadennoù hag an diverkadennoù war [[Project:Kazetenn_an_enporzhiadennoù|kazetenn an enporzhiadennoù]].

Grit gant ar furmskrid a-is evit eilañ war ar servijer skeudennoù da vezañ implijet en ho pennadoù.
War an darn vrasañ eus ar merdeerioù e welot ur bouton \"Browse...\" a zigor prenestr kendivizout boas ho reizhiad korvoiñ evit digeriñ restroù.
Diuzit ur restr a zeuio hec'h anv war wel er vaezienn zo e-kichen ar bouton.
Kadarnaat a rankit ober ivez, en ur askañ al log zo aze evit se, e touj eilenn ar restr-mañ d'ar gwirioù aozer.
Klikit war ar bouton \"Kas\" a-benn echuiñ ganti.
Mard eo gorrek ho kevreadenn e c'hall padout ur frapadig.

Ar furmadoù erbedet zo JPEG evit al luc'hskeudennoù, PNG
evit an tresadennoù hag ar skeudennoù all, hag OGG evit ar restroù son.
Lakait anvioù deskrivañ fraezh d'ho restroù, kuit dezho da vezañ kammgemmesket.
Evit enklozañ ar skeudenn en ur pennad, lakait er pennad-se ul liamm skrivet evel-henn :
'''<nowiki>[[image:anv_ar_restr.jpg]]</nowiki>''' pe
'''<nowiki>[[image:anv_ar_restr.png|testenn all]]</nowiki>''' pe
'''<nowiki>[[media:anv_ar_restr.ogg]]</nowiki>''' evit ar sonioù.

Na zisoñjit ket e c'hall bezañ degaset kemmoù er restroù eilet ganeoc'h, evel war kement pajenn zo eus {{SITENAME}}, ma soñj d'an implijidi all ez eo mat evit ar c'helc'hgeriadur. Mat eo deoc'h gouzout ivez e c'haller stankañ ouzhoc'h ar gwir da vont ouzh ar servijer ma ne implijit ket ar reizhiad evel m'eo dleet.",
'uploadlog'                   => 'log upload',
'uploadlogpage'               => 'Log_upload',
'uploadlogpagetext'           => 'Setu roll ar restroù diwezhañ bet eilet war ar servijer.
An eur merket eo hini ar servijer (UTC).',
'filename'                    => 'Anv&nbsp;',
'filedesc'                    => 'Deskrivadur&nbsp;',
'fileuploadsummary'           => 'Diverrañ :',
'filestatus'                  => 'Statud ar gwirioù aozer',
'filesource'                  => 'Mammenn',
'copyrightpage'               => '{{ns:4}}:Gwirioù aozer (Copyright)',
'copyrightpagename'           => 'aotre {{SITENAME}}',
'uploadedfiles'               => 'Restroù eilet',
'ignorewarning'               => "Na ober van eus ar c'hemennoù diwall ha saveteiñ ar restr forzh penaos.",
'ignorewarnings'              => "Na ober van ouzh ar c'hemennoù diwall",
'minlength'                   => 'Teir lizherenn da nebeutañ a rank bezañ lakaet da anvioù evit ar skeudennoù.',
'illegalfilename'             => "Lakaet ez eus bet er restr « $1 » arouezennoù n'int ket aotreet evit titl ur bajenn. Mar plij, adanvit ar restr hag adkasit anezhi.",
'badfilename'                 => 'Anvet eo bet ar skeudenn « $1 ».',
'badfiletype'                 => "« .$1 » n'eo ket ur furmad erbedet evit ar restroù skeudenn.",
'largefileserver'             => "Brasoc'h eo ar restr-mañ eget ar pezh a c'hall ar servijer aotren.",
'emptyfile'                   => "Evit doare eo goullo ar restr bet karget ganeoc'h. Moarvat eo abalamour d'an tipo en anv ar restr. Gwiriit mat e fell deoc'h pellgargañ ar restr-mañ.",
'fileexists'                  => "Ur restr all gant an anv-se zo c'hoazh. Trugarez da wiriañ $1. Ha sur oc'h da gaout c'hoant da gemmañ ar restr-mañ ?",
'fileexists-forbidden'        => "Ur restr all gant an anv-se zo c'hoazh; kit war ho kiz hag enporzhiit ar restr dindan un anv all [[Skeudenn:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Ur restr all gant an hevelep anv zo c'hoazh er c'havlec'h eskemm restroù; kit war ho kiz hag enpozhiit ar restr adarre dindan un anv all. [[Skeudenn:$1|thumb|center|$1]]",
'successfulupload'            => 'Eiladenn kaset da benn vat',
'fileuploaded'                => 'Eilet eo bet ar restr "$1" war ar servijer.
Heuilhit al liamm-mañ : ($2) evit mont ouzh ar bajenn zeskrivañ ha reiñ titouroù diwar-benn ar restr, da skouer an orin anezhi, an deiz m\'eo bet savet, an aozer anezhi, pe kement titour all a c\'hall bezañ ganeoc\'h.',
'uploadwarning'               => 'Diwallit!',
'savefile'                    => 'Enrollañ ar restr',
'uploadedimage'               => '« [[$1]] » eilet war ar servijer',
'uploaddisabled'              => 'Ho tigarez, diweredekaet eo bet kas ar restr-mañ.',
'uploadcorrupt'               => 'Brein eo ar restr-mañ, par eo he ment da netra pe fall eo an astenn anezhi.
Gwiriit anezhi mar plij.',
'uploadvirus'                 => 'Viruzet eo ar restr! Titouroù : $1',
'destfilename'                => "Anv a vo roet d'ar restr enrollet",
'watchthisupload'             => 'Evezhiañ ar bajenn-mañ',

'upload-file-error' => 'Fazi diabarzh',

# Image list
'imagelist'             => 'Roll ar skeudennoù',
'imagelisttext'         => 'Setu ur roll $1 skeudenn rummet $2.',
'getimagelist'          => "Oc'h adtapout roll ar skeudennoù",
'ilsubmit'              => 'Klask',
'showlast'              => 'diskouez an/ar $1 skeudenn ziwezhañ rummet dre $2.',
'byname'                => 'dre o anv',
'bydate'                => 'dre an deiziad anezho',
'bysize'                => 'dre o ment',
'imgdelete'             => 'diverk',
'imgdesc'               => 'deskr',
'imgfile'               => 'restr',
'imglegend'             => "Alc'hwez: (deskr) = diskouez/kemmañ deskrivadur ar skeudenn.",
'imghistory'            => 'Istor ar skeudenn',
'revertimg'             => 'adlak',
'deleteimg'             => 'diverk',
'deleteimgcompletely'   => 'diverk',
'imghistlegend'         => "Alc'hwez: (brem) = setu ar skeudenn zo bremañ, (diverk) = diverkañ ar stumm kozh-mañ, (adlak) = adlakaat ar stumm kozh-mañ.
<br /><i>Klikit war an deiziad evit gwelet ar skeudenn eilet d'an deiziad-se</i>.",
'imagelinks'            => 'Liammoù war-du ar skeudenn',
'linkstoimage'          => 'Ul liamm war-du ar skeudenn-mañ zo war ar pajennoù a-is :',
'nolinkstoimage'        => "N'eus liamm ebet war-du ar skeudenn-mañ war pajenn ebet.",
'imagelist_date'        => 'Deiziad',
'imagelist_name'        => 'Anv',
'imagelist_user'        => 'Implijer',
'imagelist_description' => 'Deskrivadur',

# MIME search
'mimesearch' => 'Klask MIME',
'mimetype'   => 'Seurt MIME :',
'download'   => 'pellgargañ',

# Unwatched pages
'unwatchedpages' => "Pajennoù n'int ket evezhiet",

# Unused templates
'unusedtemplates'    => 'Patromoù dizimplij',
'unusedtemplateswlh' => 'liammoù all',

# Statistics
'statistics'    => 'Stadegoù',
'sitestats'     => "Stadegoù al lec'hienn",
'userstats'     => 'Stadegoù implijer',
'sitestatstext' => '<b>$1</b> (b/f)pajenn zo er bank roadennoù evit poent.

Er sifr-mañ emañ ar pajennoù "kaozeal", ar pajennoù a denn da {{SITENAME}}, ar pajennoù bihanañ ("stouvoù"), ar pajennoù adkas ha meur a seurt pajenn all n\'haller ket sellet outo evel pennadoù.
Mar lakaer ar pajennoù-se er-maez e chom <b>$2</b> (b/f)pajenn zo moarvat gwir pennadoù.<p>
<b>$3</b> (b/f)pajenn zo bet sellet outo ha <b>$4</b> (b/f)pajenn zo bet kemmet

\'\'\'$8\'\'\' files have been uploaded.

abaoe m\'eo bet hizivaet ar meziant (31 Here 2002).
Ar pezh a ra ur geidenn a <b>$5</b> (g/c\'h)kemm dre bajenn ha <b>$6</b> selladenn evit ur c\'hemm.",
"userstatstext" => "<b>$1</b> implijer enrollet zo.
En o zouez, <b>$2</b> zo ganto ar statud merour (sellet ouzh $3).

The [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] length is \'\'\'$7\'\'\'.',
'userstatstext' => "'''$1''' implijer enrollet zo; en o zouez ez eus 
'''$2''' (pe '''$4%''') zo $5.",

'disambiguations'     => 'Pajennoù digejañ',
'disambiguationspage' => '{{ns:4}}:Liammoù_ouzh_ar_pajennoù_disheñvelaat',

'doubleredirects'     => 'Adkasoù doubl',
'doubleredirectstext' => '<b>Diwallit:</b> Gallout a ra bezañ "pozitivoù faos\\ er roll-mañ. D\'ar mare-se eo moarvat peogwir ez eus testenn war bajenn an #REDIRECT kentañ ivez.<br />War bep linenn emañ al liammoù war-du pajenn an adkas 1{{añ}} hag en eil hag ivez linenn gentañ pajenn an eil adkas zo sañset reiñ ar pal "gwirion". War-du ar pal-se e tlefe liammañ an #REDIRECT kentañ.',

'brokenredirects'     => 'Adkasoù torret',
'brokenredirectstext' => "Kas a ra an adkasoù-mañ d'ur bajenn n'eus ket anezhi.",

# Miscellaneous special pages
'nbytes'                  => '$1 eizhbit',
'ncategories'             => '$1 rummad',
'nlinks'                  => '$1 daveenn',
'nviews'                  => '$1 selladenn',
'lonelypages'             => 'Pajennoù en o-unan',
'uncategorizedpages'      => 'Pajennoù hep rummad ebet',
'uncategorizedcategories' => 'Rummadoù hep rummadoù',
'uncategorizedimages'     => 'Skeudennoù dirummad',
'unusedcategories'        => 'Rummadoù dizimplij',
'unusedimages'            => 'Skeudennoù en o-unan',
'popularpages'            => 'Pajennoù sellet ar muiañ',
'wantedcategories'        => 'Rummadoù a vank',
'wantedpages'             => 'Pajennoù goulennet ar muiañ',
'mostlinked'              => 'Pajennoù dezho al liammoù niverusañ',
'mostlinkedcategories'    => 'Rummadoù dezho al liammoù niverusañ',
'mostcategories'          => 'Pennadoù rummatet ar muiañ',
'mostimages'              => 'Skeudennoù dezho al liammoù niverusañ',
'mostrevisions'           => 'Pennadoù bet kemmet ar muiañ',
'allpages'                => 'An holl bajennoù',
'prefixindex'             => 'An holl bajennoù dre o lizherenn gentañ',
'randompage'              => 'Ur bajenn dre zegouezh',
'shortpages'              => 'Pennadoù berr',
'longpages'               => 'Pennadoù hir',
'deadendpages'            => 'Pajennoù dall (hep liamm diabarzh)',
'deadendpagestext'        => "Ar pajennoù da-heul n'int ket liammet ouzh pajenn ebet all er wiki-mañ.",
'listusers'               => 'Roll ar berzhidi',
'specialpages'            => 'Pajennoù dibar',
'spheading'               => 'Pajennoù dibar',
'restrictedpheading'      => 'Pajennoù dibar miret-strizh',
'recentchangeslinked'     => 'Heuliañ al liammoù',
'rclsub'                  => '(eus ar pajennoù liammet ouzh "$1")',
'newpages'                => 'Pajennoù nevez',
'newpages-username'       => 'Anv implijer :',
'ancientpages'            => 'Pennadoù koshañ',
'intl'                    => 'Liammoù etrewiki',
'move'                    => 'adenvel',
'movethispage'            => 'Adenvel ar bajenn',
'unusedimagestext'        => "<p>Na zisoñjit e c'hall lec'hiennoù all, {{SITENAME}}où all, kaout ul liamm eeun war-du ar skeudenn-mañ hag e c'hall neuze ar skeudenn-mañ bezañ bet lakaet war ar roll-mañ tra m'emañ implijet e lec'h all.",
'unusedcategoriestext'    => "Krouet eo bet ar rummadoù-mañ met n'int ket bet implijet e pennad pe rummad ebet.",

# Book sources
'booksources'               => 'Oberennoù dave',
'booksources-search-legend' => 'Klask en oberennoù dave',
'booksources-go'            => 'Kadarnaat',
'booksources-text'          => "Ur roll liammoù a gas da lec'hiennoù all ma werzher levrioù kozh ha nevez a gavot a-is; marteze e kavot eno titouroù pelloc'h war al levrioù a glaskit :",

'categoriespagetext' => 'War ar wiki emañ ar rummadoù da-heul :',
'data'               => 'Roadennoù',
'userrights'         => 'Merañ ar gwirioù implijer',
'groups'             => 'Strolladoù implijer',
'alphaindexline'     => '$1 da $2',
'version'            => 'Stumm',
'alllogstext'        => "Diskwel ar c'hazetennoù pellgargañ, diverkañ, gwareziñ, stankañ ha merañ. Gallout a rit personelaat ar mod diskwel en ur ziuzañ ar seurt kazetenn, an anv implijer pe ar bajenn a fell deoc'h.",

# Special:Allpages
'nextpage'          => "Pajenn war-lerc'h ($1)",
'allpagesfrom'      => 'Diskouez ar pajennoù adal :',
'allarticles'       => 'An holl bennadoù',
'allinnamespace'    => 'An holl bajennoù (esaouenn $1)',
'allnotinnamespace' => "An holl bajennoù (ar re n'emaint ket en esaouenn anv $1)",
'allpagesprev'      => 'Kent',
'allpagesnext'      => "War-lerc'h",
'allpagessubmit'    => 'Kadarnaat',
'allpagesprefix'    => 'Diskouez ar pajennoù a grog gant :',
'allpagesbadtitle'  => "Fall e oa anv ar bajenn lakaet pe neuze ez eus ennañ ur rakger etrewiki pe etreyezhoù. Evit doare ez arouezennoù n'haller ket implijout en titloù.",

# E-mail user
'mailnologin'     => "Chomlec'h ebet",
'mailnologintext' => "Ret eo deoc'h bezañ [[Special:Userlogin|luget]]
ha bezañ merket ur chomlec'h postel reizh en ho [[Special:Preferences|penndibaboù]]
evit gallout kas ur postel d'un implijer all.",
'emailuser'       => "Kas ur postel d'an implijer-mañ",
'emailpage'       => 'Postel implijer',
'emailpagetext'   => "M'en deus an implijer-se merket ur chomlec'h postel reizh en e benndibaboù e vo kaset ur postel dezhañ dre ar furmskrid a-is.
E maezienn \"Kaser\" ho postel e vo merket ar chomlec'h postel resisaet ganeoc'h-c'hwi, d'ar resever da c'halloud respont deoc'h ma kar.",
'usermailererror' => 'Fazi postel :',
'defemailsubject' => 'postel kaset eus {{SITENAME}}',
'noemailtitle'    => "Chomlec'h elektronek ebet",
'noemailtext'     => "N'en deus ket an implijer-mañ resisaet chomlec'h postel reizh ebet pe dibabet en deus chom hep resev posteloù a-berzh an implijerien all.",
'emailfrom'       => 'Kaser',
'emailto'         => 'Resever',
'emailsubject'    => 'Danvez',
'emailmessage'    => 'Postel',
'emailsend'       => 'Kas',
'emailccme'       => "Kasit din un eilenn eus ma c'hemennadenn dre bostel.",
'emailccsubject'  => 'Eilenn eus ho kemennadenn da $1: $2',
'emailsent'       => 'Postel kaset',
'emailsenttext'   => 'Kaset eo bet ho postel.',

# Watchlist
'watchlist'            => 'Rollad evezhiañ',
'watchlistfor'         => "(evit '''$1''')",
'nowatchlist'          => "N'eus pennad ebet en ho rollad evezhiañ.",
'watchlistcount'       => "'''$1 pajenn zo en ho rollad evezhiañ, gant ar pajennoù kaozeal en o zouez'''",
'clearwatchlist'       => 'Goullonderiñ ar roll evezhiañ',
'watchlistcleartext'   => "Ha sur oc'h e fell deoc'h lemel anezho kuit?",
'watchlistclearbutton' => 'Riñsañ ar rollad evezhiañ',
'watchlistcleardone'   => 'Riñset eo bet ho rollad evezhiañ. $1 pajenn zo bet lamet kuit.',
'watchnologin'         => 'Diluget',
'watchnologintext'     => "Ret eo deoc'h bezañ [[Special:Userlogin|luget]]
evit kemmañ ho roll.",
'addedwatch'           => "Ouzhpennet d'ar roll",
'addedwatchtext'       => '<p>Ouzh ho [[Special:Watchlis|rollad evezhiañ]] eo bet ouzhpennet ar bajenn "$1".
Kemmoù da zont ar bajenn-mañ ha re ar bajenn gaozeal stag outi a vo rollet amañ hag e teuio ar bajenn <b>e tev</b> er [[Special:Recentchanges|roll kemmoù diwezhañ]] evit bezañ gwelet aesoc\'h ganeoc\'h.</p>

<p>Evit tennañ ar bajenn-mañ a-ziwar ho rollad evezhiañ. klikit war "Paouez da evezhiañ" er framm merdeiñ.</p>',
'removedwatch'         => 'Lamet a-ziwar ar rollad evezhiañ',
'removedwatchtext'     => 'Lamet eo bet ar bajenn « $1 » a-ziwar ho rollad evezhiañ.',
'watch'                => 'Evezhiañ',
'watchthispage'        => 'Evezhiañ ar bajenn-mañ',
'unwatch'              => 'paouez da evezhiañ',
'unwatchthispage'      => 'Paouez da evezhiañ',
'notanarticle'         => 'Pennad ebet',
'watchnochange'        => "Pajenn ebet eus ar re evezhiet ganeoc'h n'eo bet kemmet e-pad ar prantad spisaet",
'watchdetails'         => "Lakaet hoc'h eus $1 (b/f)pajenn dindan evezh, anez kontañ ar pajennoù kaozeal.  [$4 Diskouez ha kemmañ ar roll klok].",
'wlheader-enotif'      => "* War enaou emañ ar c'has posteloù.",
'wlheader-showupdated' => "* E '''tev''' emañ merket ar pajennoù bet degaset kemmoù enno abaoe ar wech ziwezhañ hoc'h eus sellet outo",
'watchmethod-recent'   => "Gwiriañ ar c'hemmoù diwezhañ er pajennoù dindan evezh",
'watchmethod-list'     => "Gwiriañ ar c'hemmoù diwezhañ evit ar pajennoù evezhiet",
'removechecked'        => 'Lemel ar pennadoù diuzet a-ziwar ar rollad evezhiañ',
'watchlistcontains'    => '$1 (b/f)pajenn zo en ho rollad evezhiañ',
'watcheditlist'        => 'Setu aze ho rollad evezhiañ dre urzh al lizherenneg. Diuzit ar pajennoù hoc\'h eus c\'hoant da lemel a-ziwar ar roll ha klikit war ar bouton "lemel a-ziwar ar rollad evezhiañ" e traoñ ar skramm.',
'removingchecked'      => 'Lamet eo ar pennadoù diuzet a-ziwar ho rollad evezhiañ...',
'couldntremove'        => 'Dibosupl da lemel kuit ar pennad « $1 »...',
'iteminvalidname'      => "ur gudenn zo gant ar pennad « $1 » : n'eo ket mat e anv...",
'wlnote'               => "A-is emañ an/ar $1 (g/c'h)kemm diwezhañ abaoe an/ar <b>$2</b> eurvezh diwezhañ.",
'wlshowlast'           => 'diskouez an/ar $1 eurvezh $2 (z)devezh $3 diwezhañ',
'wlsaved'              => 'Ne vez hizivaet ar rollad evezhiañ nemet ur wech bep eurvezh kuit da sammañ ar servijer betek re.',
'wldone'               => 'Graet.',

'enotif_mailer'      => 'Posteler Kemenn {{SITENAME}}',
'enotif_reset'       => 'Merkañ an holl bajennoù evel gwelet',
'enotif_newpagetext' => 'Ur bajenn nevez eo homañ.',
'changed'            => 'kemmet',
'created'            => 'Krouet',
'enotif_subject'     => '$CHANGEDORCREATED eo bet pajenn $PAGETITLE {{SITENAME}} gant $PAGEEDITOR',
'enotif_lastvisited' => 'Sellet ouzh $1 evit gwelet an holl gemmoù abaoe ho selladenn ziwezhañ.',
'enotif_body'        => '$WATCHINGUSERNAME ker, 

$CHANGEDORCREATED Kemmet eo bet ar bajenn {{SITENAME}} evit $PAGETITLE gant $PAGEEDITOR d\'an $PAGEEDITDATE, gwelet $PAGETITLE_URL evit gwelet ar stumm red. 

$NEWPAGE 

Diverrañ an Implijer : $PAGESUMMARY $PAGEMINOREDIT 

Mont e darempred gant an implijer: 
postel: $PAGEEDITOR_EMAIL 
wiki: $PAGEEDITOR_WIKI 

Nemet e yafec\'h da welet ar bajenn end-eeun, ne vo kemenn all ebet ma vez degaset kemmoù enni pelloc\'h. Gallout a rit nevesaat doare ar pennadoù evezhiet ganeoc\'h en ho rollad evezhiañ ivez. 

            Ho reizhiad kemenn {{SITENAME}} muiañ karet 

-- 
A-benn kemmañ doare ho rollad evezhiañ, sellit ouzh {{fullurl:{{ns:Special}}:Watchlist/edit}} 

Evezhiadennoù ha skoazell pelloc\'h : 
{{fullurl:{{ns:help}}:Skoazell}}',

# Delete/protect/revert
'deletepage'                  => 'Diverkañ ur bajenn',
'confirm'                     => 'Kadarnaat',
'excontent'                   => "endalc'had '$1'",
'excontentauthor'             => "an danvez a oa : '$1' (ha '[[Special:Contributions/$2|$2]]' a oa bet an implijer nemetañ)",
'exbeforeblank'               => "A-raok diverkañ e oa an endalc'had : '$1'",
'exblank'                     => "pajenn c'houllo",
'confirmdelete'               => 'Kadarnaat an diverkañ',
'deletesub'                   => '(O tiverkañ "$1")',
'historywarning'              => "Diwallit: War-nes diverkañ ur bajenn ganti un istor emaoc'h :",
'confirmdeletetext'           => "War-nes diverkañ da vat ur bajenn pe ur skeudenn eus ar bank roadennoù emaoc'h. Diverket e vo ivez an holl stummoù kozh stag outi.
Kadarnait, mar plij, eo mat an dra-se hoc'h eus c'hoant, e komprenit mat an heuliadoù, hag e rit se diouzh an [[{{ns:4}}:Erbedadennoù ha reolennoù da heuliañ|erbedadennoù ha reolennoù da heuliañ]].",
'actioncomplete'              => 'Diverkadenn kaset da benn',
'deletedtext'                 => '"Diverket eo bet $1".
Sellet ouzh $2 evit roll an diverkadennoù diwezhañ.',
'deletedarticle'              => 'o tiverkañ "$1"',
'dellogpage'                  => 'Istor an diverkadennoù',
'dellogpagetext'              => 'Setu roll an diverkadennoù diwezhañ.
Eur ar servijer (UTC) eo an eur merket.
<ul>
</ul>',
'deletionlog'                 => 'istor an diverkadennoù',
'reverted'                    => 'Adlakaat ar stumm kent',
'deletecomment'               => 'Abeg an diverkadenn',
'imagereverted'               => 'Adlakaet eo bet ar stumm kent.',
'rollback'                    => "disteuler ar c'hemmoù",
'rollback_short'              => 'Disteuler',
'rollbacklink'                => 'disteuler',
'rollbackfailed'              => "C'hwitet eo bet an distaoladenn",
'cantrollback'                => 'Dibosupl da zisteuler: an aozer diwezhañ eo an hini nemetañ da vezañ kemmet ar pennad-mañ',
'alreadyrolled'               => "Dibosupl eo disteuler ar c'hemm diwezhañ graet e [[:$1]]
gant [[User:$2|$2]] ([[User talk:$2|Talk]]); kemmet pe distaolet eo bet c'hoazh gant unan bennak all.

Ar c'hemm diwezhañ a oa gant [[User:$3|$3]] ([[User talk:$3|Talk]]).",
'editcomment'                 => 'Diverradenn ar c\'hemm a oa: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => "Kemmoù distaolet gant [[Special:Contributions/$2|$2]] ([[User_talk:$2|Kaozeal]]); adlakaet d'ar stumm diwezhañ a-gent gant [[User:$1|$1]]",
'protectlogpage'              => 'Log_gwareziñ',
'protectlogtext'              => "Sellet ouzh ar [[{{ns:4}}:Pajenn warezet|c'huzulioù diwar-benn ar pajennoù gwarezet]].",
'protectedarticle'            => 'en/he deus gwarezet [[$1]]',
'unprotectedarticle'          => 'en/he deus diwarezet [[$1]]',
'protectsub'                  => '(Stankañ "$1")',
'confirmprotecttext'          => "Ha mennet oc'h da wareziñ ar bajenn-mañ ?",
'confirmprotect'              => 'Kadarnaat ar stankañ',
'protectmoveonly'             => 'Gwareziñ an adkasoù hepken',
'protectcomment'              => 'Abeg ar stankañ',
'unprotectsub'                => '(Distankañ "$1")',
'confirmunprotecttext'        => "Ha mennet oc'h da ziwareziñ ar bajenn-mañ?",
'confirmunprotect'            => 'Abeg an distankañ',
'unprotectcomment'            => 'Abeg an distankañ',
'protect-unchain'             => 'Dibrennañ an aotreoù adenvel',
'protect-default'             => '(dre ziouer)',
'protect-level-autoconfirmed' => "Stankañ an implijerien n'int ket enrollet",
'protect-level-sysop'         => 'Merourien hepken',

# Restrictions (nouns)
'restriction-edit' => 'Kemmañ',
'restriction-move' => 'Adenvel',

# Undelete
'undelete'               => 'Diziverkañ ar bajenn ziverket',
'undeletepage'           => 'Gwelet ha diziverkañ ar bajenn ziverket',
'viewdeletedpage'        => 'Gwelet ar pajennoù diverket',
'undeletepagetext'       => "Diverket eo bet ar pajennoù-mañ, er pod-lastez emaint met er bank roadennoù emaint c'hoazh ha gallout a reont bezañ diziverket eta.
Ingal e c'hall ar pod-lastez bezañ goullonderet.",
'undeletearticle'        => 'Diziverkañ ar pennadoù diverket',
'undeleterevisions'      => "$1 (g/c'h)kemm diellaouet",
'undeletehistory'        => "Ma tiziverkit ar bajenn e vo diziverket an holl gemmoù bet degaset en hec'h istor.
Ma'z eus bet krouet ur bajenn nevez dezhi an hevelep anv abaoe an diverkadenn, e teuio war wel ar c'hemmoù diziverket er rann istor a-raok, ha ne vo ket erlec'hiet ar stumm red ent emgefre.",
'undeletehistorynoadmin' => "Diverket eo bet ar pennad-mañ. Displeget eo perak en diverradenn a-is, war un dro gant munudoù an implijerien o deus bet degaset kemmoù er bajenn a-raok na vije diverket. N'eus nemet ar verourien a c'hall tapout krog war an destenn bet diverket.",
'undeleterevision'       => 'Stumm diverket ($1)',
'undeletebtn'            => 'Diziverkañ!',
'undeletedarticle'       => 'Diziverket"$1"',
'undeletedrevisions'     => '$1 stumm bet diziverket',
'cannotundelete'         => "Dibosupl eo diziverkañ; moarvat eo bet diziverket gant unan bennak all araozoc'h.",

# Namespace form on various pages
'namespace' => 'Esaouenn anv :',
'invert'    => 'Eilpennañ an dibab',

# Contributions
'contributions' => 'diazezet war labour $1.',
'mycontris'     => 'Ma degasadennnoù',
'contribsub'    => 'Evit $1',
'nocontribs'    => "N'eus bet kavet kemm ebet o klotañ gant an dezverkoù-se.",
'ucnote'        => "Setu an/ar <b>$1</b> (b/c'h)kemm diwezhañ bet graet gant an implijer-mañ e-pad an/ar <b>$2</b> devezh diwezhañ.",
'uclinks'       => "diskouez an/ar $1 (g/c'h)kemm diwezhañ; diskouez an/ar $2 devezh diwezhañ.",
'uctop'         => ' (diwezhañ)',

'sp-contributions-newest' => 'Nevesañ',
'sp-contributions-oldest' => 'Koshañ',
'sp-contributions-newer'  => "Nevesoc'h $1",
'sp-contributions-older'  => "Koshoc'h $1",

# What links here
'whatlinkshere' => 'Pajennoù liammet',
'notargettitle' => 'netra da gavout',
'notargettext'  => 'Merkit anv ur bajenn da gavout pe hini un implijer.',
'linklistsub'   => '(Roll al liammoù)',
'linkshere'     => "Ar pajennoù a-is zo enno ul liamm a gas war-du '''[[:$1]]''':",
'nolinkshere'   => "N'eus pajenn ebet enni ul liamm war-du '''[[:$1]]'''.",
'isredirect'    => 'pajenn adkas',

# Block/unblock
'blockip'                   => "Stankañ ouzh ur chomlec'h IP",
'blockiptext'               => "Grit gant ar furmskrid a-is evit stankañ ar moned skrivañ ouzh ur chomlec'h IP bennak.
Seurt diarbennoù n'hallont bezañ kemeret nemet evit mirout ouzh an taolioù gaou hag a-du gant an [[{{ns:4}}:Erbedadennoù ha reolennoù da heuliañ|erbedadennoù ha reolennoù da heuliañ]].
Roit a-is an abeg resis (o verkañ, da skouer, roll ar pajennoù bet graet gaou outo).",
'ipaddress'                 => "Chomlec'h IP",
'ipadressorusername'        => "Chomlec'h IP pe anv implijer",
'ipbexpiry'                 => 'Pad ar stankadenn',
'ipbreason'                 => 'Abeg ar stankañ',
'ipbsubmit'                 => "Stankañ ouzh ar chomlec'h-mañ",
'ipbother'                  => 'Prantad all',
'ipboptions'                => '2 eurvezh:2 hours,1 devezh:1 day,3 devezh:3 days,1 sizhunvezh:1 week,2 sizhunvezh:2 weeks,1 mizvezh:1 month,3 mizvezh:3 months,6 mizvezh:6 months,1 bloaz:1 year,da viken:infinite',
'ipbotheroption'            => 'prantad all',
'badipaddress'              => "Kamm eo ar chomlec'h IP.",
'blockipsuccesssub'         => 'Stankadenn deuet da benn vat',
'blockipsuccesstext'        => 'Stanket ez eus bet ouzh chomlec\'h IP "$1".
<br />Gallout a rit sellet ouzh ar [[Special:Ipblocklist|bajenn-mañ]] evit gwelet roll ar chomlec\'hioù IP stanket outo.',
'unblockip'                 => "Distankañ ur chomlec'h IP",
'unblockiptext'             => "Grit gant ar furmskrid a-is evit adsevel ar moned skrivañ ouzh ur chomlec'h IP bet stanket a-gent.",
'ipusubmit'                 => "Distankañ ar chomlec'h-mañ",
'ipblocklist'               => "Roll ar chomlec'hioù IP stanket outo",
'blocklistline'             => '$1, $2 en/he deus stanket $3 ($4)',
'anononlyblock'             => 'implijerien dizanv hepken',
'createaccountblock'        => "Harzet eo ar c'hrouiñ kontoù",
'ipblocklistempty'          => 'Goullo eo roll ar stankadennoù',
'blocklink'                 => 'stankañ',
'unblocklink'               => 'distankañ',
'contribslink'              => 'degasadenn',
'autoblocker'               => 'Emstanket rak rannañ a rit ur chomlec\'h IP gant "$1". Abeg : "$2".',
'blocklogpage'              => 'Log stankañ',
'blocklogentry'             => 'stanket "[[$1]]" da echuiñ a-benn $2 $3',
'blocklogtext'              => "Setu roud stankadennoù ha distankadennoù an implijerien. N'eo ket bet rollet ar chomlec'hioù IP bet stanket outo ent emgefre. Sellet ouzh [[Special:Ipblocklist|roll an implijerien stanket]] evit gwelet piv zo stanket e gwirionez.",
'unblocklogentry'           => 'o tistankañ "$1"',
'block-log-flags-anononly'  => 'implijerien dizanv hepken',
'block-log-flags-nocreate'  => 'berzet eo krouiñ kontoù',
'block-log-flags-autoblock' => 'Gweredekaet eo an emstankañ IPoù',
'range_block_disabled'      => "Diweredekaet eo bet ar stankañ stuc'hadoù IP.",
'ipb_expiry_invalid'        => 'amzer termen direizh.',
'ip_range_invalid'          => 'Stankañ IP direizh.',
'proxyblocker'              => 'Stanker proksi',
'proxyblockreason'          => "Stanket eo bet hoc'h IP rak ur proksi digor eo. Trugarez da gelaouiñ ho pourvezer moned ouzh ar Genrouedad pe ho skoazell deknikel eus ar gudenn surentez-mañ.",
'proxyblocksuccess'         => 'Echu.',

# Developer tools
'lockdb'              => 'Prennañ ar bank',
'unlockdb'            => 'Dibrennañ ar bank',
'lockdbtext'          => "Ma vez prennet ar bank roadennoù n'hallo ket mui implijer ebet kemmañ pajennoù, enrollañ e benndibaboù, kemmañ e rollad evezhiañ na seveniñ oberiadenn ebet a c'houlenn degas kemm pe gemm er bank roadennoù.
Kadarnait, mar plij, eo se hoc'h eus c'hoant da ober hag e vo dibrennet ar bank ganeoc'h kerkent ha ma vo bet kaset da benn hoc'h oberiadenn drezalc'h.",
'unlockdbtext'        => "Dibrennañ ar bank a lakay adarre an holl implijerien e-tailh da gemmañ pajennoù, hizivaat o fenndibaboù hag o rollad evezhiañ ha seveniñ an holl oberiadennoù a c'houlenn ma vefe kemmet ar bank roadennoù.
Kadarnait, mar plij, eo se hoc'h eus c'hoant da ober.",
'lockconfirm'         => 'Ya, kadarnaat a ran e fell din prennañ ar bank roadennoù.',
'unlockconfirm'       => 'Ya, kadarnaat a ran e fell din dibrennañ ar bank roadennoù.',
'lockbtn'             => 'Prennañ ar bank',
'unlockbtn'           => 'Dibrennañ ar bank',
'locknoconfirm'       => "N'eo ket bet asket al log kadarnaat ganeoc'h.",
'lockdbsuccesssub'    => 'Bank prennet.',
'unlockdbsuccesssub'  => 'Bank dibrennet.',
'lockdbsuccesstext'   => "Prennet eo bank roadennnoù {{SITENAME}}.

<br />Na zisoñjit ket e zibrennañ pa vo bet kaset da benn vat hoc'h oberiadenn drezalc'h.",
'unlockdbsuccesstext' => 'Dibrennet eo bank roadennoù {{SITENAME}}.',
'databasenotlocked'   => "N'eo ket prennet ar bank titouroù.",

# Move page
'movepage'                => 'Adenvel ur pennad',
'movepagetext'            => "Grit gant ar furmskrid a-is evit adenvel ur pennad hag adkas an holl stummoù kent anezhañ war-du an anv nevez.
Dont a raio an titl kentañ da vezañ ur bajenn adkas war-du an titl nevez.
Ne vo ket kemmet liammoù an titl kozh ha ne vo ket dilec'hiet ar bajenn gaozeal, ma'z eus anezhi.

'''DIWALLIT!'''
Gallout a ra kement-se bezañ ur c'hemm bras ha dic'hortoz evit ur pennad a vez sellet outi alies;
bezit sur e komprenit mat an heuliadoù a-raok kenderc'hel ganti.",
'movepagetalktext'        => "Gant se e vo adanvet ent emgefre ar bajenn gaozeal stag, ma'z eus anezhi '''nemet ma:'''
*ec'h adanvit ur bajenn war-du ul lec'h all,
*ez eus ur bajenn gaozeal c'hoazh gant an anv nevez, pe
*diweredekaet hoc'h eus ar bouton a-is.

En degouezh-se e rankot adenvel pe gendeuziñ ar bajenn c'hwi hoc'h-unan ma karit.",
'movearticle'             => "Dilec'hiañ ar pennad",
'movenologin'             => 'Diluget',
'movenologintext'         => 'Evit adenvel ur pennad e rankit bezañ [[Special:Userlogin|luget]] evel un implijer enrollet.',
'newtitle'                => 'anv nevez',
'move-watch'              => 'Evezhiañ ar bajenn-mañ',
'movepagebtn'             => 'Adenvel ar pennad',
'pagemovedsub'            => "Dilec'hiadenn kaset da benn vat",
'pagemovedtext'           => 'Adkaset eo bet ar pennad "[[$1]]" da "[[$2]]".',
'articleexists'           => "Ur pennad gantañ an anv-se zo dija pe n'eo ket reizh an titl hoc'h eus dibabet.
Dibabit unan all mar plij.",
'talkexists'              => "Dilec'hiet mat eo bet ar bajenn hec'h-unan met chomet eo ar bajenn gaozeal rak unan all a oa dija gant an anv nevez-se. Kendeuzit anezho c'hwi hoc'h-unan mar plij.",
'movedto'                 => 'adanvet e',
'movetalk'                => 'Adenvel ivez ar bajenn "gaozeal", mar bez ret.',
'talkpagemoved'           => "Dilec'hiet eo bet ivez ar bajenn gaozeal stag.",
'talkpagenotmoved'        => "<strong>N'eo ket bet</strong> dilec'hiet ar bajenn gaozeal stag.",
'1movedto2'               => '$1 adkaset war-du $2',
'1movedto2_redir'         => '$1 adkaset war-du $2 (adkas)',
'movereason'              => 'Abeg an adkas',
'revertmove'              => 'nullañ',
'delete_and_move'         => 'Diverkañ ha sevel adkas',
'delete_and_move_text'    => "==Ezhomm diverkañ== 

Savet eo ar pennad tal \"[[\$1]]\" c'hoazh. Diverkañ anezhañ a fell deoc'h ober evit reiñ lec'h d'an adkas ?",
'delete_and_move_confirm' => 'Ya, diverkañ ar bajenn',
'delete_and_move_reason'  => "Diverket evit ober lec'h d'an adkas",

# Export
'export'          => 'Ezporzhiañ pajennoù',
'exporttext'      => "Gallout a rit ezporzhiañ en XML an destenn ha pennad istor ur bajenn pe ur strollad pajennoù; a-benn neuze e c'hall an disoc'h bezañ enporzhiet en ur wiki all a ya en-dro gant ar meziant MediaWiki, treuzfurmet pe enrollet da vezañ implijet diouzh ma karot.",
'exportcuronly'   => 'Ezporzhiañ hepken ar stumm red hep an istor anezhañ',
'exportnohistory' => "---- 
'''Notenn :''' Dilezet eo bet an ezporzhiañ istor klok ar pajennoù evit poent peogwir e veze gorrekaet ar reizhiad diwar se.",
'export-submit'   => 'Ezporzhiañ',

# Namespace 8 related
'allmessages'               => 'Roll kemennoù ar reizhiad',
'allmessagesname'           => 'Anv',
'allmessagesdefault'        => 'Testenn dre ziouer',
'allmessagescurrent'        => 'Testenn zo bremañ',
'allmessagestext'           => "Setu roll an holl gemennadennoù a c'haller kaout e bed MediaWiki",
'allmessagesnotsupportedUI' => "Ne zegemer ket Special:AllMessages yezh hoc'h etrefas (<b>$1</b>) war al lec'hienn-mañ.",
'allmessagesnotsupportedDB' => "N'haller ket kaout Special:AllMessages rak diweredekaet eo bet wgUseDatabaseMessages.",
'allmessagesfilter'         => 'Sil anv kemennadenn :',
'allmessagesmodified'       => 'Diskouez ar re bet kemmet hepken',

# Thumbnails
'thumbnail-more'  => 'Brasaat',
'missingimage'    => '<b>Skeudenn a vank</b><br /><i>$1</i>',
'filemissing'     => 'Restr ezvezant',
'thumbnail_error' => 'Fazi e-ser krouiñ an alberz : $1',

# Special:Import
'import'                => 'Enporzhiañ pajennoù',
'importtext'            => "Ezporzhiit ur restr adal ar wiki orin en ur implij an arc'hwel Special:Export, enrollit ar bajenn war ho pladenn ha degasit anezhi amañ.",
'importfailed'          => "C'hwitet eo an enporzhiadenn: $1",
'importnotext'          => 'Goullo pe hep tamm testenn ebet',
'importsuccess'         => 'Deuet eo an enporzhiadenn da benn vat!',
'importhistoryconflict' => "Divankadennoù zo er pennad istor ha tabut zo gant se (marteze eo bet enporzhiet ar bajenn araozoc'h)",

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ma fajenn implijer',
'tooltip-pt-anonuserpage'         => "Ar bajenn implijer evit ar c'homlec'h IP implijet ganeoc'h",
'tooltip-pt-mytalk'               => 'Ma fajenn gaozeal',
'tooltip-pt-anontalk'             => "Kaozeadennoù diwar-benn ar c'hemmoù graet adal ar chomlec'h-mañ",
'tooltip-pt-preferences'          => 'Ma fenndibaboù',
'tooltip-pt-watchlist'            => "Roll ar pajennoù evezhiet ganeoc'h.",
'tooltip-pt-mycontris'            => 'Roll ma degasadennoù',
'tooltip-pt-login'                => "Daoust ma n'eo ket ret, ec'h aliomp deoc'h d'en em lugañ.",
'tooltip-pt-anonlogin'            => "Daoust ma n'eo ket ret, ec'h aliomp deoc'h d'en em lugañ.",
'tooltip-pt-logout'               => 'Dilugañ',
'tooltip-ca-talk'                 => 'Kaozeadennoù diwar-benn ar pennad',
'tooltip-ca-edit'                 => 'Gallout a rit degas kemmoù er bajenn-mañ. Implijit ar stokell Rakwelet a-raok enrollañ, mar plij.',
'tooltip-ca-addsection'           => 'Kemerit perzh er gaoz-mañ.',
'tooltip-ca-viewsource'           => 'Gwarezet eo ar bajenn-mañ. Gallout a rit gwelet an danvez anezhañ memes tra.',
'tooltip-ca-history'              => 'Stummoù kozh ar bajenn-mañ gant an aozerien anezhi.',
'tooltip-ca-protect'              => 'Gwareziñ ar bajenn-mañ',
'tooltip-ca-delete'               => 'Diverkañ ar bajenn-mañ',
'tooltip-ca-undelete'             => 'Adsevel ar bajenn-mañ',
'tooltip-ca-move'                 => 'Adenvel ar bajenn-mañ',
'tooltip-ca-watch'                => "Ouzhpennañ ar bajenn-mañ d'ho roll evezhiañ",
'tooltip-ca-unwatch'              => 'Paouez da evezhiañ ar bajenn-mañ',
'tooltip-search'                  => 'Klaskit er wiki-mañ',
'tooltip-p-logo'                  => 'Pajenn bennañ',
'tooltip-n-mainpage'              => 'Diskouez ar Bajenn bennañ',
'tooltip-n-portal'                => "Diwar-benn ar raktres, ar pezh a c'hallit ober, pelec'h kavout an traoù",
'tooltip-n-currentevents'         => 'Tapout keleier diwar-benn an darvoudoù diwezhañ',
'tooltip-n-recentchanges'         => "Roll ar c'hemmoù diwezhañ c'hoarvezet war ar wiki.",
'tooltip-n-randompage'            => 'Diskwel ur bajenn dre zegouezh',
'tooltip-n-help'                  => 'Skoazell.',
'tooltip-n-sitesupport'           => 'Skoazellit ar raktres',
'tooltip-t-whatlinkshere'         => 'Roll ar pajennoù liammet ouzh ar bajenn-mañ',
'tooltip-t-recentchangeslinked'   => "Roll ar c'hemmoù diwezhañ war ar pajennoù liammet ouzh ar bajenn-mañ",
'tooltip-feed-rss'                => 'Magañ ar red RSS evit ar bajenn-mañ',
'tooltip-feed-atom'               => 'Magañ ar red Atom evit ar bajenn-mañ',
'tooltip-t-contributions'         => 'Gwelet roll degasadennoù an implijer-mañ',
'tooltip-t-emailuser'             => "Kas ur postel d'an implijer-mañ",
'tooltip-t-upload'                => 'Enporzhiañ ur skeudenn pe ur restr media war ar servijer',
'tooltip-t-specialpages'          => 'Roll an holl bajennoù dibar',
'tooltip-ca-nstab-main'           => 'Gwelet ar pennad',
'tooltip-ca-nstab-user'           => 'Gwelet ar bajenn implijer',
'tooltip-ca-nstab-media'          => 'Gwelet pajenn ar media',
'tooltip-ca-nstab-special'        => "Ur bajenn dibar eo homañ, n'oc'h ket evit degas kemmoù enni.",
'tooltip-ca-nstab-project'        => 'Gwelet pajenn ar raktres',
'tooltip-ca-nstab-image'          => 'Gwelet pajenn deskrivañ ar bajenn-mañ',
'tooltip-ca-nstab-mediawiki'      => 'Gwelet kemenn ar reizhiad',
'tooltip-ca-nstab-template'       => 'Gwelet ar patrom',
'tooltip-ca-nstab-help'           => 'Gwelet ar bajenn soazell',
'tooltip-ca-nstab-category'       => 'Gwelet pajenn ar rummad',
'tooltip-minoredit'               => "Merkañ ar c'hemm-mañ evel dister",
'tooltip-save'                    => 'Enrollañ ho kemmoù',
'tooltip-preview'                 => "Rakwelet ar c'hemmoù; trugarez d'ober gantañ a-raok enrollañ!",
'tooltip-diff'                    => "Diskouez ar c'hemmoù degaset ganeoc'h en destenn.",
'tooltip-compareselectedversions' => "Sellet ouzh an diforc'hioù zo etre daou stumm diuzet ar bajenn-mañ.",
'tooltip-watch'                   => 'Ouzhpennañ ar bajenn-mañ ouzh ho rollad evezhiañ',

# Stylesheets
'common.css'   => '/** Talvezout a raio ar CSS lakaet amañ evit an holl gwiskadurioù */',
'monobook.css' => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Monobook */',

# Scripts
'common.js'   => '* Forzh pe JavaScript amañ a vo karget evit an holl implijerien war kement pajenn lennet ganto. */',
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */
/*Lagadennoù titouriñ ha stokelloù prim*/',

# Metadata
'nodublincore'      => "Diweredekaet eo ar roadennoù meta 'Dublin Core RDF' war ar servijer-mañ.",
'nocreativecommons' => "N'eo ket gweredekaet ar roadennoù meta 'Creative Commons RDF' war ar servijer-mañ.",
'notacceptable'     => "N'eo ket ar servijer wiki-mañ evit pourchas roadennoù eo gouest hoc'h arval da lenn.",

# Attribution
'anonymous'        => 'Implijer(ez) dianv eus {{SITENAME}}',
'siteuser'         => 'Implijer(ez) $1 eus {{SITENAME}}',
'lastmodifiedatby' => "Kemmet eo bet ar bajenn-mañ da ziwezhañ d'an/ar $2, $1 gant $3", # $1 date, $2 time, $3 user
'and'              => 'ha(g)',
'othercontribs'    => 'Diazezet war labour $1.',
'others'           => 're all',
'siteusers'        => 'Implijer(ez) $1 eus {{SITENAME}}',
'creditspage'      => 'Pajennoù kredoù',

# Spam protection
'spamprotectiontitle'  => "Pajenn warezet ent emgefre abalamour d'ar Spam",
'spamprotectiontext'   => "Pajenn warezet ent emgefre abalamour d'ar Spam",
'spamprotectionmatch'  => 'Dihunet eo bet an detektour Spam: $1 gant an destenn-mañ',
'subcategorycount'     => "$1 isrummad zo d'ar rummad-mañ.",
'categoryarticlecount' => '$1 pennad zo er rummad-mañ.',
'category-media-count' => "Bez'ez eus {{PLURAL:$1|ur restr|$1 restr}} er rummad-mañ.",
'spambot_username'     => 'Naetaat spam MediaWiki',
'spam_reverting'       => "Distreiñ d'ar stumm diwezhañ hep liamm davet $1",
'spam_blanking'        => 'Diverkañ an holl stummoù enno liammoù davet $1',

# Info page
'numauthors' => 'Niver a aozerien zisheñvel (pennad): $1',

# Math options
'mw_math_png'    => 'Produiñ atav ur skeudenn PNG',
'mw_math_simple' => "HTML m'eo eeun-kenañ, a-hend-all ober gant PNG",
'mw_math_html'   => 'HTML mar bez tu, a-hend-all PNG',
'mw_math_source' => "Leuskel ar c'hod TeX orin",
'mw_math_modern' => 'Evit ar merdeerioù arnevez',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'   => 'Merkañ evel gwiriet',
'markaspatrolledtext'   => 'Merkañ ar pennad-mañ evel gwiriet',
'markedaspatrolled'     => 'Merkañ evel gwiriet',
'markedaspatrolledtext' => 'Merket eo bet ar stumm diuzet evel gwiriet.',
'rcpatroldisabled'      => "Diweredekaet ar gwiriañ ar C'hemmoù diwezhañ",
'rcpatroldisabledtext'  => "Diweredekaet eo bet an arc'hwel evezhiañ ar c'hemmoù diwezhañ.",

# Image deletion
'deletedrevision' => 'Diverket stumm kozh $1.',

# Browsing diffs
'previousdiff' => "← Diforc'h kent",
'nextdiff'     => "Diforc'h war-lerc'h →",

'thumbsize'    => 'Ment an alberz :',
'showbigimage' => 'Pellgargañ ur stumm uhel e bizhder ($1x$2, $3 Ko)',

# Labels for User: and Title: on Special:Log pages
'specialloguserlabel'  => 'Implijer :',
'speciallogtitlelabel' => 'Titl :',

'passwordtooshort' => 'Re verr eo ho ker-tremen. $1 arouezenn a rank bezañ ennañ da nebeutañ .',

'fileinfo' => '$1Ko, seurt MIME: <tt>$2</tt>',

# EXIF tags
'exif-imagewidth'                  => 'Led',
'exif-imagelength'                 => 'Hed',
'exif-bitspersample'               => 'Niv. a vitoù dre barzhioù',
'exif-compression'                 => 'Seurt gwaskadur',
'exif-samplesperpixel'             => 'Niver a standilhonoù',
'exif-xresolution'                 => 'Pizhder led ar skeudenn',
'exif-yresolution'                 => 'Pizhder hed ar skeudenn',
'exif-jpeginterchangeformat'       => "Lec'hiadur ar SOI JPEG",
'exif-jpeginterchangeformatlength' => 'Ment ar roadennoù JPEG en eizhbitoù',
'exif-transferfunction'            => "Arc'hwel treuzkas",
'exif-datetime'                    => 'Deiziad hag eur kemm restr',
'exif-imagedescription'            => 'Titl ar skeudenn',
'exif-make'                        => 'Oberier ar benveg',
'exif-model'                       => 'Doare ar benveg',
'exif-software'                    => 'Meziant bet implijet',
'exif-artist'                      => 'Aozer',
'exif-copyright'                   => "Perc'henn ar gwirioù aozer (copyright)",
'exif-exifversion'                 => 'Stumm exif',
'exif-flashpixversion'             => 'Skoret ganti stumm Flashpix',
'exif-colorspace'                  => "Lec'h al livioù",
'exif-componentsconfiguration'     => 'Talvoudegezh pep parzh',
'exif-compressedbitsperpixel'      => 'Doare gwaskañ ar skeudenn',
'exif-makernote'                   => 'Notennoù an oberier',
'exif-relatedsoundfile'            => 'Restr son stag',
'exif-datetimeoriginal'            => 'Deiziad hag eur ar sevel roadoù',
'exif-datetimedigitized'           => 'Deiziad hag eur an niverelaat',
'exif-exposuretime'                => 'Amzer dispakañ',
'exif-exposuretime-format'         => '$1 eilenn ($2)',
'exif-fnumber'                     => 'Hed etre sti',
'exif-exposureprogram'             => 'Programm dispakañ',
'exif-aperturevalue'               => 'Digorder',
'exif-brightnessvalue'             => 'Sklêrder',
'exif-exposurebiasvalue'           => 'Reizhadenn dispakañ',
'exif-flash'                       => "Luc'h",
'exif-focallength'                 => 'Hirder ar fokalenn',
'exif-flashenergy'                 => "Nerzh al luc'h",
'exif-focalplanexresolution'       => 'Muzuliadur a-led ur fokalenn blaen',
'exif-focalplaneyresolution'       => 'Muzuliadur a-serzh ur fokalenn blaen',
'exif-focalplaneresolutionunit'    => 'Unanenn spisder evit ur fokalenn blaen',
'exif-exposureindex'               => 'Meneger dispakañ',
'exif-filesource'                  => 'Tarzh ar restr',
'exif-scenetype'                   => 'Seurt arvest',
'exif-cfapattern'                  => 'Framm silañ al livioù',
'exif-customrendered'              => 'Plediñ gant ar skeudennoù personelaet',
'exif-exposuremode'                => 'Mod dispakañ',
'exif-whitebalance'                => 'Mentel ar gwennoù',
'exif-digitalzoomratio'            => 'Feur brasaat niverel (zoum)',
'exif-focallengthin35mmfilm'       => 'Hirder ar fokalenn e filmoù 35 mm',
'exif-gaincontrol'                 => 'Reizhañ ar sklêrder',
'exif-contrast'                    => 'Dargemm',
'exif-saturation'                  => 'Saturadur',
'exif-devicesettingdescription'    => 'Deskrivadur doare ar wikefre',
'exif-gpsaltituderef'              => 'Daveenn uhelder',
'exif-gpsaltitude'                 => 'Uhelder',
'exif-gpsdestlatituderef'          => 'Daveenn evit ledred ar pal',
'exif-gpsdestlatitude'             => 'Ledred ar pal',
'exif-gpsdestlongituderef'         => 'Daveenn evit hedred ar pal',
'exif-gpsdestlongitude'            => 'Hedred ar pal',
'exif-gpsdestdistanceref'          => 'Daveenn evit an hed betek ar pal',
'exif-gpsdestdistance'             => 'Hed betek ar pal',
'exif-gpsareainformation'          => 'Anv an takad GPS',
'exif-gpsdatestamp'                => 'Deiziad GPS',
'exif-gpsdifferential'             => "Reizhadenn diforc'hadus GPS",

# EXIF attributes
'exif-compression-1' => 'Hep gwaskañ',

'exif-orientation-2' => 'Eilpennet a-hed', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Troet eus 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Eilpennet a-serzh', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Troet eus 90° a-gleiz hag eilpennet a-serzh', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Troet eus 90° a-zehou', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Troet eus 90° a-zehou hag eilpennet a-serzh', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Troet eus 90° a-gleiz', # 0th row: left; 0th column: bottom

'exif-componentsconfiguration-0' => "n'eus ket anezhi",

'exif-exposureprogram-0' => 'Anspisaet',
'exif-exposureprogram-1' => 'Dre zorn',
'exif-exposureprogram-2' => 'Programm boutin',
'exif-exposureprogram-3' => 'Rakgwir digeriñ',
'exif-exposureprogram-4' => 'Rakkwir serriñ',
'exif-exposureprogram-5' => 'Programm krouiñ (tuadur e-keñver donder ar maez)',
'exif-exposureprogram-6' => 'Programm seveniñ (tuadur e-keñver an tizh serriñ)',
'exif-exposureprogram-7' => 'Mod poltred (evit skeudennoù a-dost gant an diadreñv dispis)',
'exif-exposureprogram-8' => 'Mod gweledva (evit skeudennoù gweledva gant an diadreñv spis)',

'exif-focalplaneresolutionunit-2' => 'meudad',

'exif-customrendered-0' => 'Plediñ boutin',
'exif-customrendered-1' => 'Plediñ personelaet',

'exif-exposuremode-0' => 'Emzispakañ',
'exif-exposuremode-1' => 'Dispakañ dre zorn',
'exif-exposuremode-2' => 'Emvraketiñ',

'exif-scenecapturetype-1' => 'Gweledva',
'exif-scenecapturetype-2' => 'Poltred',
'exif-scenecapturetype-3' => 'Arvest noz',

'exif-gaincontrol-0' => 'Hini ebet',

'exif-contrast-0' => 'Boutin',
'exif-contrast-1' => 'Dister',
'exif-contrast-2' => 'Kreñv',

'exif-saturation-1' => 'Saturadur izel',
'exif-saturation-2' => 'Saturadur uhel',

# External editor support
'edit-externally'      => 'Kemmañ ar restr-mañ dre un arload diavaez',
'edit-externally-help' => "Gwelet skoazell an [http://meta.wikimedia.org/wiki/Help:External_editors arloadoù diavaez] a-benn gouzout hiroc'h.",

# 'all' in various places, this might be different for inflected languages
'watchlistall1' => 'pep tra',
'watchlistall2' => 'pep tra',
'namespacesall' => 'pep tra',

# E-mail address confirmation
'confirmemail'            => "Kadarnaat ar chomlec'h postel",
'confirmemail_noemail'    => "N'hoc'h eus ket spisaet chomlec'h postel mat ebet en ho [[Special:Preferences|penndibaboù implijer]].",
'confirmemail_text'       => "Rankout a ra ar wiki-mañ bezañ gwiriet ho chomlec'h postel a-raok gallout implijout nep arc'hwel postel. Implijit ar bouton a-is evit kas ur postel kadarnaat d'ho chomlec'h. Ul liamm ennañ ur c'hod a vo er postel. Kargit al liamm-se en o merdeer evit kadarnaat ho chomlec'h.",
'confirmemail_pending'    => "<div class=\"error\"> 
Ur c'hod kadarnaat zo bet kaset deoc'h dre bostel c'hoazh; a-raok klask goulenn unan nevez, m'emaoc'h o paouez krouiñ ho kont, e vo fur eus ho perzh gortoz un nebeud munutoù ha leuskel anezhañ d'en em gavout betek ennoc'h. 
</div>",
'confirmemail_send'       => "Kas ur c'hod kadarnaat",
'confirmemail_sent'       => 'Postel kadarnaat kaset',
'confirmemail_oncreate'   => "Kaset ez eus bet ur c'hod kadarnaat d'ho chomlec'h postel. 
N'eus ket ezhomm eus ar c'hod-mañ evit en em lugañ met ret e vo deoc'h ober gantañ 
evit aotren hini pe hini eus arc'hwelioù postel ar wiki.",
'confirmemail_sendfailed' => "Dibosupl kas ar postel kadarnaat. Gwiriit ho chomlec'h.",
'confirmemail_invalid'    => "Kod kadarnaat kamm. Marteze eo aet ar c'hod d'e dermen",
'confirmemail_needlogin'  => "Ret eo deoc'h $1 evit kadarnaat ho chomlec'h postel.",
'confirmemail_success'    => "Kadarnaet eo ho chomlec'h postel. A-benn bremañ e c'hallit en em lugañ hag ober ho mad eus ar wiki.",
'confirmemail_loggedin'   => "Kadarnaet eo ho chomlec'h bremañ",
'confirmemail_error'      => 'Ur gudenn zo bet e-ser enrollañ ho kadarnadenn',
'confirmemail_subject'    => '{{SITENAME}} email address confirmation',
'confirmemail_body'       => "Unan bennak, c'hwi moarvat, gant ar chomlec'h postel \$1 en deus enrollet ur gont \"\$2\"  gant ar chomlec'h postel-mañ war lec'hienn {{SITENAME}}.

A-benn kadarnaat eo deoc'h ar gont-se ha gweredekaat an arc'hwelioù postelerezh war {{SITENAME}}, digorit, mar plij, al liamm a-is en ho merdeer :

\$3

Ma n'eo ket bet graet ganeoc'h na zigorit ket al liamm. Mont a raio ar c'hod-mañ d'e dermen d'an/ar \$4.",

# Inputbox extension, may be useful in other contexts as well
'createarticle' => 'Krouiñ pennad',

# Trackbacks
'trackbackremove' => ' ([Diverkañ $1])',

# Delete conflict
'deletedwhileediting' => "Diwallit : Diverket eo bet ar bajenn-mañ bremañ ha krog e oac'h da zegas kemmoù enni!",
'confirmrecreate'     => "Diverket eo bet ar pennad-mañ gant [[Implijer:$1|$1]] ([[Kaozeadenn Implijer:$1|kaozeal]]) goude ma vije bet kroget ganeoc'h kemmañ anezhañ : 
: ''$2'' 
Kadarnait mar plij e fell deoc'h krouiñ ar pennad-mañ da vat.",
'recreate'            => 'Adkrouiñ',

# HTML dump
'redirectingto' => "Oc'h adkas da [[$1]]...",

# action=purge
'confirm_purge'        => 'Spurjañ krubuilh ar bajenn-mañ? 

$1',
'confirm_purge_button' => 'Mat eo',

'youhavenewmessagesmulti' => "Kemennoù nevez zo ganeoc'h war $1",

'articletitles' => "Pennadoù a grog gant ''$1''",
'hideresults'   => "Kuzhat an disoc'hoù",

# DISPLAYTITLE
'displaytitle' => '(Liammañ war-du ar bajenn-mañ evel [[$1]])',

'loginlanguagelabel' => 'Yezh : $1',

# Table pager
'descending_abbrev' => 'diskenn',
'table_pager_first' => 'Pajenn gentañ',
'table_pager_last'  => 'Pajenn ziwezhañ',
'table_pager_empty' => "Disoc'h ebet",

# Auto-summaries
'autosumm-blank'   => 'O tiverkañ kement danvez zo war ar bajenn',
'autosumm-replace' => "Oc'h erlec'hiañ ar bajenn gant '$1'",
'autoredircomment' => 'Adkas war-du [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Pajenn nevez: $1',

# Unknown messages
'disambiguations-text' => "Liammet eo ar pajennoù da-heul ouzh ur '''bajenn digejañ'''. Padal e tlefent kas war-eeun d'an danvez anezho.<br />Sellet e vez ouzh ur bajenn evel ur bajenn digejañ m'eo liammet adal $1.<br />Ne gemerer ket e kont al liammoù adal <em>esaouennoù anv</em> all.",

);

?>
