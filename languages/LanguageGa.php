<?
#--------------------------------------------------------------------------
# Constants
#--------------------------------------------------------------------------

# Magic words
define("MAG_REDIRECT", 0);
define("MAG_NOTOC", 1);
define("MAG_START", 2);
define("MAG_CURRENTMONTH", 3);
define("MAG_CURRENTMONTHNAME", 4);
define("MAG_CURRENTDAY", 5);
define("MAG_CURRENTDAYNAME", 6);
define("MAG_CURRENTYEAR", 7);
define("MAG_CURRENTTIME", 8);
define("MAG_NUMBEROFARTICLES", 9);
define("MAG_CURRENTMONTHNAMEGEN", 10);
define("MAG_MSG", 11);
define("MAG_SUBST", 12);
define("MAG_MSGNW", 13);
define("MAG_NOEDITSECTION", 14);

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesGa = array(
    -2  => "Media",
    -1  => "Speisialta",
    0   => "",
    1   => "Pl�",
    2   => "�s�ideoir",
    3   => "Pl�_�s�ideora",
    4   => "Vicip�id",
    5   => "Pl�_Vicip�ide",
    6   => "�omh�",
    7   => "Pl�_�omh�",
    8   => "MediaWiki",
    9   => "Pl�_MediaWiki"
);

/* private */ $wgDefaultUserOptionsGa = array(
    "quickbar" => 1, "underline" => 1, "hover" => 1,
    "cols" => 80, "rows" => 25, "searchlimit" => 20,
    "contextlines" => 5, "contextchars" => 50,
    "skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
    "highlightbroken" => 1, "stubthreshold" => 0,
    "previewontop" => 1, "editsection"=>1,"editsectiononrightclick"=>0, "showtoc"=>1,
    "date" => 0
);

/* private */ $wgQuickbarSettingsGa = array(
    "Faic", "Greamaithe ar chl�", "Greamaithe ar an taobh deas", "Ag faoile�il ar chl�"
);

/* private */ $wgSkinNamesGa = array(
    "Gn�th", "Sean-n�s", "Gorm na Cologne", "Paddington", "Montparnasse"
);

/* private */ $wgMathNamesGa = array(
    "D�an PNG-�omh� gach uair",
    "D�an HTML m� t� sin an-easca, n� PNG ar mhodh eile",
    "D�an HTML m�s f�idir, n� PNG ar mhodh eile",
    "F�g mar cl� TeX (do teacsleitheoir�)",
    "Inmholta do l�onleitheoir� nua"
);

/* private */ $wgDateFormatsGa = array(
    "Is cuma liom",
    "Ean�ir 15, 2001",
    "15 Ean�ir 2001",
    "2001 Ean�ir 15",
    "2001-01-15"
);

/* private */ $wgUserTogglesGa = array(
    "hover"     => "Taispe�in airebhosca� os cionn na vicil�ib�n�",
    "underline" => "Cuir l�nte faoi na l�ib�n�",
    "highlightbroken" => "Cuir dath dearg ar l�ib�n� briste, <a href=\"\" class=\"new\">mar sin</a> (rogha eile: mar sin<a href=\"\" class=\"internal\">?</a>).",
    "justify"   => "Comhfhadaigh na paragraif",
    "hideminor" => "N� taispe�in fo-eagair sna athruithe deireanacha",
    "usenewrc" => "St�l nua do na athruithe deireanacha (le JavaScript)",
    "numberheadings" => "Uimhrigh ceannteidil go huathoibr�och",
    "editondblclick" => "Cuir leathanaigh in eagar le roghna d�bailte (JavaScript)",
    "editsection"=>"Cumasaigh eagarth�ireacht m�r le l�ib�n� [athraithe]",
    "editsectiononrightclick"=>"Cumasaigh eagarth�ireacht m�r le deas-roghna<br> ar ceannteidil (JavaScript)",
    "showtoc"=>"D�an liosta na ceannteideal<br>(do ailt le n�os m� n� 3 ceannteidil)",
    "rememberpassword" => "Cuimhnigh mo focal faire",
    "editwidth" => "Cuir uasm�id ar an athr�bhosca",
    "watchdefault" => "Breathnaigh ar leathanaigh a d'athraigh t�",
    "minordefault" => "Cuir marc mionathraithe ar gach athr�, mar r�amhshocr�",
    "previewontop" => "Cuir an r�amhthaispe�ntas os cionn an athr�bhosca, agus n� taobh th�os de",
    "nocache" => "Ciorraigh an taisce leathanaigh"
);

# If possible, find Irish language book services on the Internet, searchable by ISBN
# $wgBookstoreListGa = ..

/* private */ $wgBookstoreListGa = array(
    "AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
    "PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
    "Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
    "Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

# Here, when possible, use Irish language names for languages

/* private */ $wgLanguageNamesGa = array(
    "aa"    => "Afar",
    "ab"    => "Abkhazian",
    "af"    => "Afrikaans", # Afrac�inis
    "am"    => "Amharic",
    "ar" => "&#8238;&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;&#8236; (Araby)", # Araibis
    "as"    => "Assamese",
    "ay"    => "Aymara",
    "az"    => "Azerbaijani",
    "ba"    => "Bashkir",
    "be" => "&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1080;",
    "bh"    => "Bihara",
    "bi"    => "Bislama",
    "bn"    => "Bengali",
    "bo"    => "Tibetan", # Tib�adis
    "br" => "Brezhoneg",
    "bs" => "Bosnian",
    "ca" => "Catal&#224;", # Catal�inis
    "ch" => "Chamoru",
    "co"    => "Corsican",
    "cs" => "&#268;esk&#225;",
    "cy" => "Cymraeg", # Breatnais
    "da" => "Dansk", # Danmhairgis. Note two different subdomains.
    "dk" => "Dansk", # 'da' is correct for the language.
    "de" => "Deutsch", # Gearm�inis
    "dz"    => "Bhutani",
    "el" => "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940; (Ellenika)",
    "en"    => "English", # B�arla
    "eo"    => "Esperanto", # Espeirant�
    "es" => "Espa&#241;ol", # Sp�innis
    "et" => "Eesti",
    "eu" => "Euskara",
    "fa" => "&#8238;&#1601;&#1585;&#1587;&#1609;&#8236; (Farsi)",
    "fi" => "Suomi",
    "fj"    => "Fijian", # Fidsis? Fiji= An Fhids�.
    "fo"    => "Faeroese",
    "fr" => "Fran&#231;ais", # Fraincis
    "fy" => "Frysk",
    "ga" => "Gaeilge", # Gaeilge
    "gl"    => "Galician",
    "gn"    => "Guarani",
    "gu" => "&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752; (Gujarati)",
    "ha"    => "Hausa",
    "he" => "&#1506;&#1489;&#1512;&#1497;&#1514; (Ivrit)",
    "hi" => "&#2361;&#2367;&#2344;&#2381;&#2342;&#2368; (Hindi)", # Hiond�is
    "hr" => "Hrvatski",
    "hu" => "Magyar", # Ung�iris
    "hy"    => "Armenian", # Airm�inis
    "ia"    => "Interlingua",
    "id"    => "Indonesia", # Indin�isis
    "ik"    => "Inupiak",
    "is" => "&#205;slenska",
    "it" => "Italiano", # Iod�ilis
    "iu"    => "Inuktitut",
    "ja" => "&#26085;&#26412;&#35486; (Nihongo)", # Seap�inis
    "jv"    => "Javanese", # Iavais? Java = An Iava.
    "ka" => "&#4325;&#4304;&#4320;&#4311;&#4309;&#4308;&#4314;&#4312; (Kartuli)",
    "kk"    => "Kazakh",
    "kl"    => "Greenlandic", # Graonlainnis
    "km"    => "Cambodian",
    "kn"    => "Kannada",
    "ko" => "&#54620;&#44397;&#50612; (Hangukeo)",
    "ks"    => "Kashmiri",
    "kw" => "Kernewek",
    "ky"    => "Kirghiz",
    "la" => "Latina", # Laidin
    "ln"    => "Lingala",
    "lo"    => "Laotian",
    "lt" => "Lietuvi&#371;",
    "lv"    => "Latvian", # Laitvis
    "mg" => "Malagasy",
    "mi"    => "Maori",
    "mk"    => "Macedonian", # Macad�inis
    "ml"    => "Malayalam",
    "mn"    => "Mongolian", # Mong�ilis
    "mo"    => "Moldavian",
    "mr"    => "Marathi",
    "ms" => "Bahasa Melayu",
    "my"    => "Burmese", # Burmais? Burma = Burma
    "na"    => "Nauru",
    "ne" => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368; (Nepali)", # Neipealis
    "nl" => "Nederlands", # Ollainnis
    "no" => "Norsk", # Ioruais
    "oc"    => "Occitan",
    "om"    => "Oromo",
    "or"    => "Oriya",
    "pa"    => "Punjabi",
    "pl" => "Polski", # Polainnis
    "ps"    => "Pashto",
    "pt" => "Portugu&#234;s", # Portaing�ilis
    "qu"    => "Quechua",
    "rm"    => "Rhaeto-Romance",
    "rn"    => "Kirundi",
    "ro" => "Rom&#226;n&#259;",
    "ru" => "&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081; (Russkij)", # R�isis
    "rw"    => "Kinyarwanda",
    "sa" => "&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340; (Samskrta)",
    "sd"    => "Sindhi",
    "sg"    => "Sangro",
    "sh"    => "Serbocroatian", # ? Serbian = Serbis; Croatian = Cr�itis.
    "si"    => "Sinhalese",
    "simple" => "Simple English", # B�arla Simpl�
    "sk"    => "Slovak", # Sl�vaicis
    "sl"    => "Slovensko",
    "sm"    => "Samoan",
    "sn"    => "Shona",
    "so" => "Soomaali",
    "sq" => "Shqiptare",
    "sr" => "Srpski",
    "ss"    => "Siswati",
    "st"    => "Sesotho",
    "su"    => "Sundanese",
    "sv" => "Svenska", # Sualainnis
    "sw" => "Kiswahili",
    "ta"    => "Tamil",
    "te"    => "Telugu",
    "tg"    => "Tajik",
    "th"    => "Thai",
    "ti"    => "Tigrinya",
    "tk"    => "Turkmen",
    "tl"    => "Tagalog",
    "tn"    => "Setswana",
    "to"    => "Tonga",
    "tr" => "T&#252;rk&#231;e", # Tuircis
    "ts"    => "Tsonga",
    "tt"    => "Tatar",
    "tw"    => "Twi",
    "ug"    => "Uighur",
    "uk" => "&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072; (Ukrayins`ka)",
    "ur"    => "Urdu",
    "uz"    => "Uzbek",
    "vi"    => "Vietnamese", # V�tneamis
    "vo" => "Volap&#252;k",
    "wo"    => "Wolof",
    "xh" => "isiXhosa",
    "yi"    => "Yiddish", # Gi�dais
    "yo"    => "Yoruba",
    "za"    => "Zhuang",
    "zh" => "&#20013;&#25991; (Zhongwen)", # S�nis
    "zh-cn" => "&#20013;&#25991;(&#31616;&#20307;) (Simplified Chinese)", # S�nis Simplithe
    "zh-tw" => "&#20013;&#25991;(&#32321;&#20307;) (Traditional Chinese)", # S�nis Traidisi�nta
    "zu"    => "Zulu"
);

# Different spellings of days  (with D�) may be needed for some uses

/* private */ $wgWeekdayNamesGa = array(
    "Domhnach", "Luan", "M�irt", "C�adaoin", "D�ardaoin",
    "Aoine", "Satharn"
);

/* private */ $wgMonthNamesGa = array(
    "Ean�ir", "Feabhra", "M�rta", "Aibre�n", "Bealtaine", "Meitheamh",
    "I�il", "L�nasa", "Me�n F�mhair", "Deireadh F�mhair", "M� na Samhna",
    "M� na Nollag"
);

/* private */ $wgMonthAbbreviationsGa = array(
    "Ean", "Fea", "M�r", "Aib", "Bea", "Mei", "I�i", "L�n",
    "Mea", "Dei", "Samh", "Nol"
);

# Are the following safe to translate?

/* private */ $wgMagicWordsGa = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    "#redirect"              ),
    MAG_NOTOC                => array( 0,    "__NOTOC__"              ),
    MAG_NOEDITSECTION        => array( 0,    "__NOEDITSECTION__"      ),
    MAG_START                => array( 0,    "__START__"              ),
    MAG_CURRENTMONTH         => array( 1,    "{{CURRENTMONTH}}"       ),
    MAG_CURRENTMONTHNAME     => array( 1,    "{{CURRENTMONTHNAME}}"   ),
    MAG_CURRENTDAY           => array( 1,    "{{CURRENTDAY}}"         ),   
    MAG_CURRENTDAYNAME       => array( 1,    "{{CURRENTDAYNAME}}"     ),
    MAG_CURRENTYEAR          => array( 1,    "{{CURRENTYEAR}}"        ),
    MAG_CURRENTTIME          => array( 1,    "{{CURRENTTIME}}"        ),
    MAG_NUMBEROFARTICLES     => array( 1,    "{{NUMBEROFARTICLES}}"   ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    "{{CURRENTMONTHNAMEGEN}}"),
    MAG_MSG                  => array( 1,    "{{MSG:$1}}"             ),
    MAG_SUBST                => array( 1,    "{{SUBST:$1}}"           ),
    MAG_MSGNW                => array( 1,    "{{MSGNW:$1}}"           )
);
    
# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesGa = array(
    "Userlogin"     => "",
    "Userlogout"    => "",
    "Preferences"   => "Athraigh mo socruithe",
    "Watchlist"     => "Mo fairechl�r", # List of pages, which the user has chosen to watch
    "Recentchanges" => "Leathanaigh athraithe le d�ana�",
    "Upload"        => "Suasl�d�il comhaid agus �omh�nna",
    "Imagelist"     => "Liosta �omh�nna",
    "Listusers"     => "�s�ideoir� cl�raithe",
    "Statistics"    => "Staitistic an shu�omh",
    "Randompage"    => "Leathanach f�nach",

    "Lonelypages"   => "Leathanaigh d�lleachta�",
    "Unusedimages"  => "�omh�nna d�lleachta�",
    "Popularpages"  => "Ailt coitianta",
    "Wantedpages"   => "Ailt santaithe",
    "Shortpages"    => "Ailt gairide",
    "Longpages"     => "Ailt fada",
    "Newpages"      => "Ailt nua",
    "Ancientpages"  => "Ailt �rsa",
#   "Intl"      => "L�ib�n� idirtheangacha",
    "Allpages"      => "Gach leathanach de r�ir teidil",

    "Ipblocklist"   => "�s�ideoir�/IP-sheolaidh coisctha",
    "Maintenance"   => "Leathanach coim�adta",
    "Specialpages"  => "",
    "Contributions" => "",
    "Emailuser"     => "",
    "Whatlinkshere" => "",
    "Recentchangeslinked" => "",
    "Movepage"      => "",
    "Booksources"   => "Leabharfhoins� seachtra�",
#   "Categories"    => "Ranganna leathanaigh",
    "Export"        => ""
);

/* private */ $wgSysopSpecialPagesGa = array(
    "Blockip"       => "Cuir cosc ar �s�ideoir/IP-sheoladh",
    "Asksql"        => "Cuir ceist ar an bhunachar sonra�",
    "Undelete"      => "Cuir leathanaigh scriosaithe ar ais"
);

/* private */ $wgDeveloperSpecialPagesGa = array(
    "Lockdb"        => "Cuir glas ar an mbunachar sonra�",
    "Unlockdb"      => "Bain an glas den bunachar sonra�",
    "Debug"     => "Eolas chun fadhtanna a r�itigh"
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and 
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

/* private */ $wgAllMessagesGa = array(

# Bits of text used by many pages:
#
"categories" => "Ranganna leathanaigh",
"category" => "rang",
"category_header" => "Ailt sa rang \"$1\"",
"subcategories" => "Fo-ranganna",

"linktrail"     => "/^([a-z]+)(.*)\$/sD",
"mainpage"      => "Ceannleathanach",
"mainpagetext"  => "D'insealbha�odh an oideas Wiki go rath�il.",
"about"     => "Faoi",
"aboutwikipedia"    => "Faoi Vicip�id",
"aboutpage"     => "Vicip�id:Faoi",
"help"      => "Cabhair",
"helppage"      => "Vicip�id:Cabhair",
"wikititlesuffix" => "Vicip�id",
"bugreports"    => "Fabht-thuairisc�",
"bugreportspage"    => "Vicip�id:Fabht-thuairisc�",
"faq"           => "Ceisteanna Coiteanta",
"faqpage"       => "Vicip�id:Ceisteanna Coiteanta",
"edithelp"      => "Cabhair eagarth�ireachta",
"edithelppage"  => "Vicip�id:Conas_alt_a_cur_in_eagar",
"cancel"        => "Cealaigh",
"qbfind"        => "Faigh",
"qbbrowse"      => "�tam�il",
"qbedit"        => "Athraigh",
"qbpageoptions" => "Roghanna leathanaigh",
"qbpageinfo"    => "Eolas leathanaigh",
"qbmyoptions"   => "Mo roghanna",
"mypage"        => "Mo leathanach",
"mytalk"        => "Mo pl�",
"currentevents" => "Cursa� reatha",
"errorpagetitle"    => "Earr�id",
"returnto"      => "Dul ar ais go $1.",
"fromwikipedia" => "�n Vicip�id, an chiclip�id shaor.",
"whatlinkshere" => "Leathanaigh a cheangla�onn chuig an leathanach seo",
"help"      => "Cabhair",
"search"        => "Cuardaigh",
"go"            => "Dul",
"history"       => "Stair leathanaigh",
"printableversion" => "Eagr�n cl�bhuala",
"editthispage"  => "Athraigh an leathanach seo",
"deletethispage"    => "Dealaigh an leathanach seo",
"protectthispage" => "Cuir glas ar an leathanach seo",
"unprotectthispage" => "Bain an glas den leathanach seo",
"newpage"       => "Leathanach nua",
"talkpage"      => "Pl� an leathanach seo",
"postcomment"   => "Cuir m�nithe leis an leathanach",
"articlepage"   => "Feach ar an alt",
"subjectpage"   => "Feach ar an t-�bhar", # For compatibility
"userpage"      => "Feach ar leathanach �s�ideora",
"wikipediapage" => "Feach ar meitea-leathanach",
"imagepage"     => "Feach ar leathanach �omh�",
"viewtalkpage"  => "Feach ar phl�",
"otherlanguages"    => "Teangacha eile",
"redirectedfrom"    => "(Athsheoladh � $1)",
"lastmodified"  => "Mhionathra�odh an leathanach seo ar $1.",
"viewcount"     => "Rochtain�odh an leathanach seo $1 uair.",
"gnunote"       => "T� an teacs ar fad le f�il faoi na t�arma� an <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(� http://ga.wikipedia.org)",
"protectedpage" => "Leathanach faoi ghlas",
"administrators"    => "Vicip�id:Riarth�ir�",
"sysoptitle"    => "Cuntas ceannasa� de dh�th",
"sysoptext"     => "Caithfidh t� bheith i do \"ceannasa�\" 
chun an gn�omh seo a dh�anamh.
F�ach ar $1.",
"developertitle"    => "Cuntas r�omhchl�raitheora de dh�th",
"developertext" => "Caithfidh t� bheith i do \"cl�raitheoir\" 
chun an gn�omh seo a dh�anamh.
F�ach ar $1.",
"nbytes"        => "$1 bearta",
"go"            => "Dul",
"ok"            => "Go maith",
"sitetitle"     => "Vicip�id",
"sitesubtitle"  => "An Chiclip�id Shaor",
"retrievedfrom" => "Faightear ar ais � \"$1\"",
"newmessages"   => "T� $1 agat.",
"newmessageslink" => "teachtaireachta� nua",
"editsection"   => "athraigh",
"toc"           => "Cl�r �bhair",
"showtoc"       => "taispe�in",
"hidetoc"       => "folaigh",
"thisisdeleted" => "Breathnaigh n� cuir ar ais $1?",
"restorelink"   => "$1 athruithe scriosaithe",

# Main script and global functions
#
"nosuchaction"  => "N�l a leith�id de ghn�omh ann",
"nosuchactiontext" => "N�l aithn�onn an oideas Vicip�ide 
an gn�omh ('action') at� ann san l�onsheoladh.",
"nosuchspecialpage" => "N�l a leith�id de leathanach speisialta ann",
"nospecialpagetext" => "N�l aithn�onn an oideas Vicip�ide 
an leathanach speisialta a d'iarr t� ar.",

# General errors
#
"error"         => "Earr�id",
"databaseerror"     => "Earr�id an bhunachar sonra�",
"dberrortext"   => "Tharlaigh earr�id chomhr�ir sa cheist chuig an bhunachar sonra�.
T� seans gur cuireadh ceist cuardach neamhcheart (f�ach ar $5),
n� t� seans go bhfuil fabht san oideas.
<blockquote><tt>$1</tt></blockquote>, �n suim \"<tt>$2</tt>\",
ab ea an ceist seo caite chuig an bhunachar sonrai.
Chuir MySQL an earr�id seo ar ais: \"<tt>$3: $4</tt>\".",
"dberrortextcl"     => "Tharlaigh earr�id chomhr�ir sa cheist chuig an bhunachar sonra�.
\"$1\", �n suim \"$2\",
ab ea an ceist seo caite chuig an bhunachar sonrai,
Chuir MySQL an earr�id seo ar ais: \"$3: $4\".\n",
"noconnect"     => "T� br�n orainn! Chuaigh an oideas Wiki in abar teicni�il, agus theipeadh an nasc leis an mbunachar sonra� .",
"nodb"      => "Theipeadh an rogha den bhunachar sonra� $1",
"cachederror"   => "Seo � c�ip athscr�obhtha den leathanach a raibh t� ag lorg (is d�cha go nach bhfuil s� bord ar bhord leis an eagr�n reatha).",
"readonly"      => "Bunachar sonra� faoi ghlas",
"enterlockreason" => "Iontr�il c�is don ghlas, agus meastach�n
den cathain a mbainfear an ghlas de.",
"readonlytext"  => "T� an bunachar sonra� Vicip�ide faoi ghlas anois do iontr�il� agus athruithe nua 
(is d�cha go bhfuil s� do gn�thchothabh�il).
Tar �is seo, beidh an bunachar sonra� tofa ar ais.
Thug an riarth�ir a ghlasaigh an m�ni� seo:
<p>$1",
"missingarticle" => "Chuardaigh an bunachar sonra� ar leathanach go mba ch�ir a bheith faighte, darbh ainm \"$1\". N�or bhfuarthas an leathanach.

<p>N� earr�id san bunachar sonra� � seo, ach b'fh�idir go bhfuair t� amach fabht 
sna oideasra MediaWiki. De ghn�th, tarla�onn s� sin nuair a leantar nasc staire n� difr�ochta go leathanach a raibh scriosaithe cheana f�in.

<p>D�an n�ta den URL le do thoil, agus cuir an �bhar in i�l do riarth�ir.",
"internalerror" => "Earr�id inmh�anach",
"filecopyerror" => "N� f�idir an comhad \"$1\" a ch�ipe�il go \"$2\".",
"filerenameerror" => "N� f�idir an comhad \"$1\" a athainmnigh bheith \"$2\".",
"filedeleteerror" => "N� f�idir an comhad \"$1\" a scriosaigh amach.",
"filenotfound"  => "N� bhfuarthas an comhad \"$1\".",
"unexpected"    => "Luach gan s�il leis: \"$1\"=\"$2\".",
"formerror"     => "Earr�id: n� f�idir an foirm a tabhair isteach", 
"badarticleerror" => "N� f�idir an gn�omh seo a dh�anamh ar an leathanach seo.",
"cannotdelete"  => "N� f�idir an leathanach n� �omh� sonraithe a scriosaigh. (B'fh�idir go shcriosaigh duine eile � cheana f�in.)",
"badtitle"      => "Teideal neamhbhail�",
"badtitletext"  => "Bh� teideal an leanthanaigh a d'iarr t� ar neamhbhail�, folamh, n�
teideal idirtheangach no idir-Wiki nasctha go m�cheart.",
"perfdisabled" => "T� br�n orainnn! Mh�chumasa�odh an gn� seo go sealadach chun luas an bunachair sonra� a chosaint.",
"perfdisabledsub" => "Seo c�ip s�bh�ilte � $1:",
"wrong_wfQuery_params" => "Paraim�adair m�chearta don wfQuery()<br>
Feidhm: $1<br>
Ceist: $2
",
"viewsource" => "F�ach ar foinse",
"protectedtext" => "Chuirtear ghlas ar an leathanach seo chun � a chosaint in aghaidh athruithe. T� go leor
c�iseanna f�ideartha don sc�al seo. F�ach ar 
[[$wgMetaNamespace:Leathanach faoi ghlas]] le do thoil.

Is f�idir leat foinse an leathanaigh seo a feachaint ar agus a ch�ipe�il:",

# Login and logout pages
#
"logouttitle"   => "Log as",
"logouttext" => "T� t� logtha as anois.
Is f�idir leat an Vicip�id a �s�id f�s gan ainm, n� is f�idir leat log ann 
ar�s mar an �s�ideoir c�anna, n� mar �s�ideoir eile. Tabhair faoi deara go taispe�infear roinnt
leathanaigh mar at� t� logtha ann f�s, go dt� go ghlanf� amach do taisce brabhs�la\n",

"welcomecreation" => "<h2>T� f�ilte romhat, a $1!</h2><p>Chrutha�odh do chuntas.
N� d�an dearmad do socruithe phearsanta a gcr�ch.",

"loginpagetitle" => "Log ann",
"yourname"      => "Do ainm �s�ideora",
"yourpassword"  => "Do focal faire",
"yourpasswordagain" => "Athiontr�il do focal faire",
"newusersonly"  => " (Do �s�ideoir� nua amh�in)",
"remembermypassword" => "Cuimhnigh mo focal faire.",
"loginproblem"  => "<b>Bh� fadhb le do logadh ann.</b><br>D�an iarracht eile!",
"alreadyloggedin" => "<font color=red><b>A h�s�ideoir $1, t� t� logtha ann cheana f�in!</b></font><br>\n",

"areyounew"     => "M� t� t� i do n��osach chuig an Vicip�id agus t� cuntas �s�ideora uait,
iontr�il ainm �s�ideora, agus ansin iontr�il agus athiontr�il focal faire.
T� an seoladh r�omhphoist rud roghnach; d� bhf�gf� do focal faire, is feidir leat a iarradh
go seolfar � chuig an seoladh r�omhphoist a thug t�.<br>\n",

"login"     => "Log ann",
"userlogin"     => "Log ann",
"logout"        => "Log as",
"userlogout"    => "Log as",
"notloggedin"   => "N�l t� logtha ann",
"createaccount" => "Cruthaigh cuntas nua",
"createaccountmail" => "le r�omhphost",
"badretype"     => "D'iontr�il t� dh� focail faire difri�la.",
"userexists"    => "T� an ainm �s�ideora a d'iontr�il t� in �s�id cheana f�in. D�an rogha de ainm eile, le do thoil.",
"youremail"     => "Do r�omhphost*",
"yournick"      => "Do leasainm (do s�ni�ithe)",
"emailforlost"  => "* Is roghnach � do seoladh r�omhphoist a iontr�il.  Ach ba f�idir daoine teagmhail a dh�anamh leat 
tr�d an su�omh gan do seoladh r�omhphoist a nochtaigh d�ibh. Ina theannta sin,  
is cabhair � m� dheanf� dearmad ar do focal faire.",
"loginerror"    => "Earr�id leis an log ann",
"noname"        => "N� shonraigh t� ainm �s�ideora bail�.",
"loginsuccesstitle" => "Log ann rath�il",
"loginsuccess"  => "T� t� logtha ann anois go Vicip�id mar \"$1\".",
"nosuchuser"    => "N�l aon �s�ideoir ann leis an ainm \"$1\".
Cinntigh do litri�, n� bain �s�id as an foirm th�os chun cuntas �s�ideora nua a chruthaigh.",
"wrongpassword" => "Bh� an focal faire a d'iontr�il t� m�cheart. D�an iarracht eile le do thoil.",
"mailmypassword" => "Cuir focal faire nua chugam",
"passwordremindertitle" => "Cuimhneach�n focail faire � Vicip�id",
"passwordremindertext" => "D'iarr duine �igin (tusa de r�ir cos�lachta, �n seoladh IP $1)
go sheolfaimis focal faire Vicip�ide nua do log ann duit.
Is � an focal faire don �s�ideoir \"$2\" n� \"$3\" anois.
Ba ch�ir duit log ann anois agus athraigh do focal faire.",
"noemail"       => "N�l aon seoladh r�omhphoist i gcuntas don �s�ideoir \"$1\".",
"passwordsent"  => "Cuireadh focal faire nua chuig an seoladh r�omhphoist cl�raithe do \"$1\".
Agus at� s� agat, log ann ar�s leis le do thoil.",

# Edit pages
#
"summary"       => "Achomair",
"subject"       => "�bhar/ceannl�ne",
"minoredit"     => "Seo � mionathr�",
"watchthis"     => "D�an faire ar an leathanach seo",
"savearticle"   => "S�bh�il an leathanach",
"preview"       => "Reamhthaispe�ntas",
"showpreview"   => "Reamhthaispe�in",
"blockedtitle"  => "T� an �s�ideoir seo coiscthe",
"blockedtext"   => "Chuir $1 cosc ar do ainm �s�ideora n� do seoladh IP. 
Seo � an c�is a thugadh:<br>''$2''<p>Is f�idir leat teagmh�il a dh�anamh le $1 n� le ceann eile de na 
[[$wgMetaNamespace:Riarth�ir�|riarth�ir�]] chun an cosc a phl�igh. 

Tabhair faoi deara go nach f�idir leat an gn� \"cuir r�omhphost chuig an �s�ideoir seo\" 
mura bhfuil seoladh r�omhphoist bail� cl�raithe i do [[Speisialta:Preferences|socruithe �s�ideora]]. 

Is � $3 do sheoladh IP. M�s � do thoil �, d�an tagairt den seoladh seo le gach ceist a chuirfe�.

==N�ta do �s�ideoir� AOL==
De bhr� ghn�omhartha lean�nacha creachad�ireachta de haon �s�ideoir AOL �irithe, 
is minic a coisceann Vicip�id ar frioth�laithe AOL. Go m�fhort�nach, �fach, is f�idir 
go leor �s��deoir� AOL an frioth�la� c�anna a �s�id, agus mar sin is minic a coisca�tear 
�s�ideoir� AOL neamhchiontacha. Iarraimis pard�n do aon triobl�id. 

M� tharl�dh an sc�al seo duit, cuir r�omhphost chuig riarth�ir le seoladh r�omhphoist AOL. Bheith cinnte tagairt a dh�anamh leis an seoladh IP seo thuas.",
"whitelistedittitle" => "Log ann chun athr� a dh�anamh",
"whitelistedittext" => "Caithfidh t� [[Speisialta:Userlogin|log ann]] chun ailt a athraigh.",
"whitelistreadtitle" => "Log ann chun ailt a l�igh",
"whitelistreadtext" => "Caithfidh t� [[Speisialta:Userlogin|log ann]] chun ailt a l�igh.",
"whitelistacctitle" => "N�l cead agat cuntas a chruthaigh",
"whitelistacctext" => "Chun cuntais nua a chruthaigh san Wiki seo caithfidh t� [[Speisialta:Userlogin|log ann]] agus caithfidh bheith an cead riachtanach agat.",
"accmailtitle" => "Cuireadh an focal faire.",
"accmailtext" => "Cuireadh an focal faire do '$1' chuig $2.",
"newarticle"    => "(Nua)",
"newarticletext" =>
"Lean t� nasc go leathanach a nach bhfuil ann f�s. 
Chun an leathanach a chruthaigh, tosaigh ag cl�scr�obh san bosca anseo th�os 
(f�ach ar an [[Vicip�id:Cabhair|leathanach cabhrach]] chun n�os m� eolas a fh�il).
M� th�inig t� anseo as dearmad, br�igh an cnaipe '''ar ais''' ar do l�onl�itheoir.",
"anontalkpagetext" => "---- ''Seo � an leathanach pl� do �s�ideoir gan ainm a nach chruthaigh 
cuntas f�s n� a nach �s�ideann a chuntas. D� bhr� sin caithfimid an [[seoladh IP]] uimhri�il 
chun �/� a ionannaigh. Is f�idir cuid mhaith �s�ideoir� an seoladh IP c�anna a �s�id. M� t� t� 
i do �s�ideoir gan ainm agus m� t� s� do thuairim go rinneadh l�iriuithe neamhfheidhmeacha f�t, 
[[Special:Userlogin|cruthaigh cuntas n� log ann]] le do thoil chun mearbhall le h�s�ideoir� eile 
gan ainmneacha a h�alaigh amach anseo.'' ",
"noarticletext" => "(N�l aon t�acs ar an leathanach seo)",
"updated"       => "(Nuashonraithe)",
"note"          => "<strong>Tabhair faoi deara:</strong> ",
"previewnote"   => "Tabhair faoi deara go nach bhfuil seo ach reamhthaispe�ntas, agus go nach s�bh�ladh � f�s!",
"previewconflict" => "San reamhthaispe�ntas seo, feachann t� an t�acs d� r�ir an eagarbhosca 
thuas mar a taispe�infear � m� s�bh�ilfear �.",
"editing"       => "Ag athraigh $1",
"sectionedit"   => " (roinnt)",
"commentedit"   => " (l�iri�)",
"editconflict"  => "Coimhlint athraithe: $1",
"explainconflict" => "D'athraigh duine eile an leathanach seo � shin a thosaigh t� ag cuireadh � in eagar.
San bhosca thuas feiceann t� t�acs an leathanaigh mar a bhfuil s� faoi l�thair.
T� do athruithe san bhosca th�os.
Caithfidh t� do athruithe a chumasadh leis an eagr�n at� ann anois.
Nuair a br�ann t� ar an cnaipe \"S�bh�il an leathanach\", n� s�bh�lfar <b>ach amh�in</b> an t�acs san bhosca thuas.\n<p>",
"yourtext"      => "Do t�acs",
"storedversion" => "Eagr�n i dtaisce",
"editingold"    => "<strong>AIRE: Cuireann t� in eagar eagr�n an leathanach seo as d�ta.
M� sh�bh�lf� �, caillfear aon athr� a rinneadh � shin an eagr�n seo.</strong>\n",
"yourdiff"      => "Difr�ochta�",
"copyrightwarning" => "Tabhair faoi dearadh go scaoil�tear gach c�namh go Vicip�id maidir lena tearma� an <i>GNU Free Documentation License</i>
(f�ach ar $1 chun eolas a fh�il).
M� nach mian leat go cuirfear do scr�bhinn in eagar go h�adr�caireach agus go athd�lfar � gan teorainn, 
n� tabhair � isteach anseo.<br>
Ina theannta sin, geallann t� duinn go shcr�obh t� f�in an rud seo, n� go ch�ipe�il t� � �n 
fhoinse gan ch�ipcheart.
<strong>N� TABHAIR ISTEACH OBAIR LE C�IPCHEART GAN CEAD!</strong>",
"longpagewarning" => "AIRE: T� an leathanach seo $1 cilibhirt i bhfad; n� f�idir le roinnt l�onl�itheoir�
leathanaigh breis agus n� n�os fada n� 32kb a athraigh.
Me�igh an seans go mbrisfeadh t� an leathanach sna codanna n�os bige.",
"readonlywarning" => "AIRE: Cuireadh ghlas ar an bhunachar sonra�, agus mar sin 
n� f�idir leat do athruithe a sh�bh�il d�reach anois. B'fh�idir go mhaith leat an t�acs a 
ch�ipe�il is a taosaigh go chomhad t�acs agus � a sh�bh�il do �s�id n�os d�anach.",
"protectedpagewarning" => "AIRE:  Cuireadh ghlas ar an leathanach seo, agus is f�idir amh�in na �s�ideoir� le 
pribhl�id� ceannasa� � a athraigh. B� cinnte go leanann t� na 
<a href='/wiki/Vicip�id:Treoirl�nte_do_leathanaigh_cosnaithe'>treoirl�nte do leathanaigh cosnaithe</a>.",

# History pages
#
"revhistory"    => "St�ir athruithe",
"nohistory"     => "N�l aon st�ir athruithe don leathanach seo.",
"revnotfound"   => "N� bhfuarthas an athr�",
"revnotfoundtext" => "N� bhfuarthas seaneagr�n an leathanagh a d'iarr t� ar. 
Cinntigh an URL a d'�s�id t� chun an leathanach seo a rochtain.\n",
"loadhist"      => "Ag l�d�il st�ir an leathanaigh",
"currentrev"    => "Eagr�n reatha",
"revisionasof"  => "Eagr�n � $1",
"cur"           => "rea",
"next"          => "lea",
"last"          => "roi",
"orig"          => "bun",
"histlegend"    => "Eochair: (rea) = difr�ocht leis an eagr�n reatha,
(roi) = difr�ocht leis an eagr�n roimhe, M = mionathr�",

# Diffs
#
"difference"    => "(Difr�ochta� idir eagr�in)",
"loadingrev"    => "ag l�d�il eagr�n don difr�ocht",
"lineno"        => "L�ne $1:",
"editcurrent"   => "Athraigh eagr�n reatha an leathanaigh seo",

# Search results
#
"searchresults" => "Toraidh an cuardaigh",
"searchhelppage" => "Vicip�id:Ag_cuardaigh",
"searchingwikipedia" => "Ag cuardaigh sa Vicip�id",
"searchresulttext" => "Chun n�os m� eolas a fh�il mar gheall ar cuardach Vicip�ide, f�ach ar $1.",
"searchquery"   => "Do cheist \"$1\"",
"badquery"      => "Ceist cuardaigh neamhbhail�",
"badquerytext"  => "Nior �irigh linn do cheist a phr�ise�il. 
Is docha go rinne t� cuardach ar focal le n�os l� n� tr� litir, 
gn� a nach bhfuil le taca�ocht aige f�s. 
B'fh�idir freisin go mh�chl�shcr�obh t� an leagan, mar shampla 
\"�isc agus agus lanna\". D�an iarracht eile.",
"matchtotals"   => "Bh� an cheist \"$1\" ina mhacasamhail le $2 teidil alt
agus le t�acs de $3 ailt.",
"nogomatch" => "N�l aon leathanach leis an teideal �irithe seo. D�antar cuardach an t�acs ar fad...",
"titlematches"  => "T� macasamhla teideal alt ann",
"notitlematches" => "N�l macasamhla teideal alt ann",
"textmatches"   => "T� macasamhla t�acs alt ann",
"notextmatches" => "N�l macasamhla t�acs alt ann",
"prevn"         => "na $1 roimhe",
"nextn"         => "an ch�ad $1 eile",
"viewprevnext"  => "Taispe�in ($1) ($2) ($3).",
"showingresults" => "Ag taispe�int th�os <b>$1</b> toraidh, ag tosaigh le #<b>$2</b>.",
"showingresultsnum" => "Ag taispe�int th�os <b>$3</b> toraidh, ag tosaigh le #<b>$2</b>.",
"nonefound"     => "<strong>Tabhair faoi deara</strong>: d�antar cuardaigh m�rath�la go minic nuair a cuarda�tear focail coiteanta, m.sh., \"ag\" is \"an\",
a nach bhfuil inn�acsa�tear, n� nuair a ceisteann t� n�os m� n� t�arma amh�in (n�
taispe�intear sna toraidh ach na leathanaigh ina bhfuil go leoir na t�arma� cuardaigh).",
"powersearch" => "Cuardaigh",
"powersearchtext" => "
Cuardaigh sna roinn :<br>
$1<br>
$2 Cuir athsheolaidh in aireamh &nbsp; Cuardaigh ar $3 $9",
"searchdisabled" => "<p>T� br�n orainn! Mh�chumasa�odh an ghn� l�nchuardaigh t�acs go sealadach chun luas an su�mh 
a chosaint. Idir an d� linn, is f�idir leat an cuardach Google anseo th�os a �s�id - b'fh�idir go bhfuil s� as d�ta.</p>

<!-- SiteSearch Google -->
<!-- Get Irish version of this!!! -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.ie/ga/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\">
<br><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
"blanknamespace" => "(Ceannleathanach)",

# Preferences page
#
"preferences"   => "Socruithe",
"prefsnologin" => "N�l t� logtha ann",
"prefsnologintext"  => "Caithfidh t� bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun do socruithe phearsanta a athraigh.",
"prefslogintext" => "T� t� logtha ann mar \"$1\".
Is � $2 do uimir aitheantais inmh�anach.

F�ach ar [[Vicip�id:Cabhair do socruithe �s�ideora]] chun cabhair a fh�il mar gheall ar na roghanna.",
"prefsreset"    => "D'athraigh do socruithe ar ais chuig an leagan bun�sach, mar gheall ar st�r�il.",
"qbsettings"    => "Socruithe an bosca uirlis�", 
"changepassword" => "Athraigh do focal faire",
"skin"          => "Cuma",
"math"          => "Ag aistrigh an matamaitic",
"dateformat"    => "Cruth an d�ta",
"math_failure"      => "Theipeadh anail�s an foirmle",
"math_unknown_error"    => "earr�id anaithnid",
"math_unknown_function" => "foirmle anaithnid ",
"math_lexing_error" => "Theipeadh anail�s an focl�ra",
"math_syntax_error" => "earr�id comhr�ire",
"saveprefs"     => "S�bh�il socruithe",
"resetprefs"    => "Athshuigh socruithe",
"oldpassword"   => "Seanfhocal faire",
"newpassword"   => "Nuafhocal faire",
"retypenew"     => "Athchl�shcr�obh an nuafhocal faire",
"textboxsize"   => "M�id an th�acsbhosca",
"rows"          => "Sraitheanna",
"columns"       => "Col�in",
"searchresultshead" => "Socruithe na toraidh cuardaigh",
"resultsperpage" => "Taispe�in faighte de r�ir",
"contextlines"  => "Taispe�in l�nte de r�ir",
"contextchars"  => "Taispe�in litreacha de r�ir",
"stubthreshold" => "Cuir comhartha� ar leathanaigh n�os big� n�",
"recentchangescount" => "M�id teidil sna athruithe deireanacha",
"savedprefs"    => "S�bh�ladh do socruithe.",
"timezonetext"  => "Iontr�il an m�id uaireanta a difr�onn do am �iti�il den am an frioth�la� (UTC).",
"localtime" => "An t-am �iti�il",
"timezoneoffset" => "Difear",
"servertime"    => "Am an frioth�la� anois",
"guesstimezone" => "Cuardaigh �n l�onl�itheoir",
"emailflag"     => "Coisc r�omhphost �n �s�ideoir� eile",
"defaultns"     => "Cuardaigh sna ranna seo a los �agmaise:",

# Recent changes
#
"changes" => "athruithe",
"recentchanges" => "Athruithe deireanacha",
"recentchangestext" => 
"Lean na athruithe is deireanacha go Vicip�id ar an leathanach seo.
[[Wikipedia:F�ilte,_a_n��osaigh|F�ilte, a n��osaigh]]!
F�ach ar na leathanaigh seo, m�s � do thoil �: [[Vicip�id:CMT|CMT Vicip�ide]],
[[Vicip�id:Polasaithe agus treoirl�nte|Polasa� Vicip�ide]]
(go h�irithe [[Vicip�id:Coinbhinsi�in ainmneacha|coinbhinsi�in ainmneacha]],
[[Vicip�id:Dearcadh neodrach|dearcadh neodrach]]),
agus [[Vicip�id:Na bot�in Vicip�ide is coitianta|na bot�in Vicip�ide is coitianta]].

M�s maith leat go �ire�idh Vicip�id, t� s� an-tabhachtach go nach cuireann t� �bhair 
a nach bhfuil teorainnaithe de na [[Vicip�id:C�ipchearta|c�ipchearta]] de ghr�pa� eile.
Ba f�idir leis an dliteanas an tionscnamh a gortaigh go f�or, mar sin n� d�an �.
F�ach ar an [http://meta.wikipedia.org/wiki/Special:Recentchanges meiteaphl� deireanach] freisin.",
"rcloaderr"     => "Ag l�d�il athruithe deireanacha",
"rcnote"        => "Is iad seo a leanas na <strong>$1</strong> athruithe deireanacha sna <strong>$2</strong> lae seo caite.",
"rcnotefrom"    => "Is iad seo a leanas na athruithe � <b>$2</b> (go dti <b>$1</b> taispe�naithe).",
"rclistfrom"    => "Taispe�in nua-athruithe � $1 anuas",
# "rclinks"     => "Taispe�in na $1 athruithe is deireanacha sna $2 uaire seo caite / $3 laethanta seo caite.",
# "rclinks"     => "Taispe�in na $1 athruithe is deireanacha sna $2 laethanta seo caite.",
"rclinks"       => "Taispe�in na $1 athruithe is deireanacha sna $2 laethanta seo caite; $3 mionathruithe",
"rchide"        => "sa cuma $4; $1 mionathruithe; $2 foranna; $3 athruithe ilchodacha.",
"rcliu"         => "; $1 athruithe de �s�ideoir� logtha ann",
"diff"          => "difr�ochta�",
"hist"          => "st�ir",
"hide"          => "folaigh",
"show"          => "taispe�in",
"tableform"     => "t�bla",
"listform"      => "liosta",
"nchanges"      => "$1 athruithe",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"        => "Suasl�d�ilcomhad",
"uploadbtn"     => "Suasl�d�il comhad",
"uploadlink"    => "Suasl�d�il �omh�nna",
"reupload"      => "Athshuasl�d�il",
"reuploaddesc"  => "Fill ar ais chuig an fhoirm shuasl�d�la.",
"uploadnologin" => "Nil t� logtha ann",
"uploadnologintext" => "Caithfifh t� bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun comhaid a shuasl�d�il.",
"uploadfile"    => "Suasl�d�il �omh�nna, fuaimeanna, doicim�id srl.",
"uploaderror"   => "Earr�id suasl�d�la",
"uploadtext"    => "<strong>STOP!</strong> Roimh a suasl�d�la�onn t� anseo, 
b� cinnte leigh agus g�ill don <a href=\"" .
wfLocalUrlE( "Vicip�id:Polasa�_�s�ide_�omh�" ) . "\">polasa� �s�ide �omh�</a> at� ag Vicip�id.
<p>M� bhfuil aon comhad ann f�s leis an ainm c�anna a bhfuil t� ag
tabhairt don comhad nua, cuirfear an nuachomhad in ionad an seanchomhad gan f�gr.
Mar sin, mura nuashonra�onn t� comhad �igin, is sc�al maith � cinntigh m� bhfuil comhad 
leis an ainm seo ann f�s.
<p>To view or search previously uploaded images,
go to the Dul go dti an<a href=\"" . wfLocalUrlE( "Speisialta:Imagelist" ) .
"\">liosta �omh�nna suasl�d�laithe</a>chun f�ach ar n� chuardaigh idir �omh�nna a raibh suasl�d�laithe roimhe seo.
D�antar liosta de suasl�s�la agus scriosaidh ar an <a href=\"" .
wfLocalUrlE( "Vicip�id:Liosta_suasl�d�la" ) . "\">liosta suasl�d�la</a>.
<p>Bain �s�id as an fhoirm anseo th�os chun �omh�chomhaid nua a suasl�d�il. 
Ba f�idir leat na �omh�nna a �s�id i do ailt. 
Ar an chuid is m� de na l�onl�itheoir�, feicfidh t� cnaipe \"Brabhs�il...\" no mar sin. L� br� ar an cnaipe seo, 
gheobhaigh t� an gh�thbhosca agallaimh comhadtheacta do ch�ras oibri�ch�in. 
Nuair a lu�onn t� comhad, l�onfar ainm an comhaid san t�acsbhosca in aice leis an cnaipe.
Caithfidh t� a admh�il le br� san bosca beag go nach 
bhfuil t� ag s�raigh aon ch�ipcheart leis an s�asl�d�il seo.
Br�igh an cnaipe \"Suasl�d�il\" chun an suasl�d�il a chr�ochnaigh.
Mura bhfuil nasc idirl�n tapaidh agat, beidh roinnt ama uait chun an rud sin a dh�anamh. 
<p>Is iad na form�ide inmholta n� JPEG do �omh�nna grianghrafacha, PNG 
do picti�ir tarraingthe agus l�ar�ide, agus OGG do fuaimeanna. 
Ainmnigh do comhaid go tuairisci�il chun mearbhall a h�alaigh. 
Chun an �omh� a �s�id san alt, �s�id nasc mar sin:
<b>[[�omh�:comhad.jpg]]</b> n� <b>[[image:�omh�.png|t�acs eile]]</b>
n� <b>[[me�n:comhad.ogg]]</b> do fuaimeanna.
<p>Tabhair faoi deara go, cos�il le leathanaigh Vicip�ide, is f�idir le daoine eile do suasl�d�lacha a 
athraigh n� a scriosadh amach, m� s�ltear go bhfuil s� i gcabhair 
don ciclip�id, agus m� bhainfe� m�-�s�id as an c�ras ta seans go coiscf� t� �n gc�ras.",

"uploadlog"     => "liosta suasl�d�la",
"uploadlogpage" => "Liosta_suasl�d�la",
"uploadlogpagetext" => "Is liosta � seo a leanas de na suasl�d�lacha comhad is deireanacha.
Is am an frioth�la� (UTC) iad na hamanna at� anseo th�os.
<ul>
</ul>
",
"filename"      => "Ainm comhaid",
"filedesc"      => "Achoimri�",
"filestatus" => "St�das c�ipchirt",
"filesource" => "Foinse",
"affirmation"   => "Dearbha�m go aonta�onn coime�da� c�ipchirt an comhaid seo
chun � a cead�naigh de r�ir na t�arma� an $1.",
"copyrightpage" => "Vicip�id:C�ipchearta",
"copyrightpagename" => "C�ipcheart Vicip�ide",
"uploadedfiles" => "Comhaid suasl�d�laithe",
"noaffirmation" => "Caithfidh t� a dearbhaigh go nach s�ra�onn do suasl�d�il
aon c�ipchearta.",
"ignorewarning" => "Scaoil tharat an rabhadh agus s�bh�il an comhad ar aon chaoi.",
"minlength"     => "Caithfidh tr� litreacha ar a laghad bheith ann sa ainm �omh�.",
"badfilename"   => "D'athra�odh an ainm �omh� go \"$1\".",
"badfiletype"   => "N�l \".$1\" ina form�id comhaid �omh� inmholta.",
"largefile"     => "Moltar go nach t�ann comhaid �omh� thar 100k i m�id.",
"successfulupload" => "Suasl�d�il rath�il",
"fileuploaded"  => "Suasl�d�ladh an comhad \"$1\" go rath�il.
Lean an nasc seo: ($2) chuig an leathanach cuir sios agus l�on isteach
eolas mar gheall ar an comhad, mar shampla c� bhfuair t� �, cathain a 
chrutha�odh � agus rud eile ar bith t� an fhios agat faoi.",
"uploadwarning" => "Rabhadh suasl�d�la",
"savefile"      => "S�bh�il comhad",
"uploadedimage" => "suasl�d�laithe \"$1\"",

# Image list
#
"imagelist"     => "Liosta �omh�nna",
"imagelisttext" => "Is liosta � seo a leanas de $1 �omh�nna, curtha in eagar le $2.",
"getimagelist"  => "ag f�il an liosta �omh�nna",
"ilshowmatch"   => "Taispe�in na �omh�nna le ainmneacha maith go l�ir",
"ilsubmit"      => "Cuardaigh",
"showlast"      => "Taispe�in na $1 �omh�nna seo caite, curtha in eagar le $2.",
"all"           => "go l�ir",
"byname"        => "de r�ir hainm",
"bydate"        => "de r�ir d�ta",
"bysize"        => "de r�ir m�id",
"imgdelete"     => "scrios",
"imgdesc"       => "cur",
"imglegend"     => "Eochair: (cur) = taispe�in/athraigh cur s�os an �omh�.",
"imghistory"    => "Stair an �omh�",
"revertimg"     => "ath",
"deleteimg"     => "scr",
"imghistlegend" => "Legend: (rea) = seo � an eagr�n reatha, (scr) = scrios an
sean-eagr�n seo, (ath) = ath�s�id an sean-eagr�n seo.
<br><i>Bruigh an d�ta chun feach ar an �omh� mar a suasl�d�la�odh � ar an d�ta sin</i>.",
"imagelinks"    => "Naisc �omh�",
"linkstoimage"  => "Is iad na leathanaigh seo a leanas a nasca�onn chuig an �omh� seo:",
"nolinkstoimage" => "N�l aon leathanach ann a nasca�onn chuig an �omh� seo.",

# Statistics
#
"statistics"    => "Staitistic",
"sitestats"     => "Staitistic su�mh",
"userstats"     => "Staitistic �s�ideora",
"sitestatstext" => "Is � <b>$1</b> an m�id leathanach in ioml�n san bunachar sonra�.
Cuirtear san �ireamh \"pl�\"-leathanaigh, leathanaigh faoi Vicip�id, ailt \"stumpa�\"
�osmh�adacha, athsheolaidh, agus leathanaigh eile a nach c�ileann mar ailt.
Ag f�g�il na leathanaigh seo as, t� <b>$2</b> leathanaigh ann at� ailt dlisteanacha, is d�cha.<p>
In ioml�n bh� <b>$3</b> radhairc leathanaigh, agus <b>$4</b> athruithe leathanaigh
� thus athch�iri� na hoideasra (25 Ean�ir, 2004).
Sin � <b>$5</b> athruithe ar me�n do gach leathanach, agus <b>$6</b> radhairc do gach athr�.",
"userstatstext" => "T� <b>$1</b> �s�ideoir� cl�raithe ann.
Is iad <b>$2</b> de na �s�ideoir� seo ina riarth�ir� (f�ach ar $3).",

# Maintenance Page
#
"maintenance"       => "Leathanach coinne�la",
"maintnancepagetext"    => "Sa leathanach seo faightear uirlis� �ags�la don gn�thchoinne�il. Is f�idir le roinnt 
de na feidhmeanna seo an bunachar sonra� a cuir strus ar, mar sin n� athbhruigh athl�d�il tar �is gach m�r a 
chr�ochna�onn t� ;-)",
"maintenancebacklink"   => "Ar ais go Leathanach Coinne�la",
"disambiguations"   => "Leathanaigh easathbhr�ochais",
"disambiguationspage"   => "Vicip�id:Naisc_go_leathanaigh_easathbhr�ochais",
"disambiguationstext"   => "Nasca�onn na ailt seo a leanas go <i>leathanach easathbhr�ochais</i>. Ba ch�ir d�ibh nasc a 
dh�anamh leis an �bhar oiri�nach ina �it.<br>Tugtar an teideal easathbhr�ochais ar leathanach m� bhfuil n�sc aige 
� $1.<br><i>N�</i> cuirtear naisc � ranna eile ar an liosta seo.",
"doubleredirects"   => "Athsheolaidh D�bailte",
"doubleredirectstext"   => "<b>Tabhair faoi deara:</b> B'fheidir go bhfuil toraidh br�agacha ar an liosta seo. 
De ghn�th c�alla�onn s� sin go bhfuil t�acs breise le naisc th�os san ch�ad #REDIRECT.<br>\n Sa gach sraith t� 
n�isc chuig an ch�ad is an dara athsheoladh, chomh maith le ch�ad l�ne an dara t�acs athsheolaidh. De ghn�th 
tugann s� sin an sprioc-alt \"f�or\".",
"brokenredirects"   => "Athsheolaidh Briste",
"brokenredirectstext"   => "Is iad na athsheolaidh seo a leanas a nasca�onn go ailt a nach bhfuil ann.",
"selflinks"     => "Leathanaigh le f�in-naisc",
"selflinkstext"     => "Sna leathanaigh seo a leanas t� naisc a nasca�onn chuig an leathanach c�anna � fh�in. Seo � fl�irseach.",
"mispeelings"           => "Leathanaigh m�litrithe",
"mispeelingstext"               => "Sna leathanaigh seo a leanas t� m�litri� coiteanta, at� san liosta ar $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Naisc Teangacha Ar Iarraidh",
"missinglanguagelinksbutton"    => "Cuardaigh ar naisc teangacha ar iarraidh do",
"missinglanguagelinkstext"      => "<i>N�</i> nasca�onn na ailt seo chuig a macasamhail sa $1. <i>N�</i> taispe�ntar athsheolaidh n� foleathanaigh.",


# Miscellaneous special pages
#
"orphans"       => "Leathanaigh d�lleachtacha",
"lonelypages"   => "Leathanaigh d�lleachtacha",
"unusedimages"  => "�omh�nna tr�igthe",
"popularpages"  => "Leathanaigh coitianta",
"nviews"        => "$1 radhairc",
"wantedpages"   => "Leathanaigh de dh�th",
"nlinks"        => "$1 naisc",
"allpages"      => "Na leathanaigh go l�ir",
"randompage"    => "Leathanach f�nach",
"shortpages"    => "Leathanaigh gearra",
"longpages"     => "Leathanaigh fada",
"listusers"     => "Liosta �s�ideoir�",
"specialpages"  => "Leathanaigh speisialta",
"spheading"     => "Leathanaigh speisialta go gach �s�ideoir",
"sysopspheading" => "Amh�in do ceannasaithe",
"developerspheading" => "Amh�in do cl�raitheoir�",
"protectpage"   => "Cuir glas ar leathanach",
"recentchangeslinked" => "Athruithe gaolmharas",
"rclsub"        => "(go leathanaigh nasctha � \"$1\")",
"debug"         => "Bain fabhtanna",
"newpages"      => "Leathanaigh nua",
"ancientpages"      => "Na leathanaigh is sine",
"intl"      => "Naisc idirtheangacha",
"movethispage"  => "Aistrigh an leathanach seo",
"unusedimagestext" => "<p>Tabhair faoi deara go f�idir le l�onshu�mh
eile, m.sh. na Vicip�id� eile, naisc a dh�anamh le �omha le URL d�reach, 
agus mar sin beidh siad ar an liosta seo f�s c� go bhfuil an �omh� 
in �s�id anois.",
"booksources"   => "Foins� leabhar",
"booksourcetext" => "Seo � liosta anseo th�os go su�mh eile a
d�olann leabhair nua agus athdh�olta, agus t� seans go bhfuil eolas
breise acu faoina leabhair a bhfuil t� ag tnuth leis.
N�l Vicip�id comhcheangaltha le aon de na gn�tha� seo, agus n�
aont� leo � an liosta seo.",
"alphaindexline" => "$1 go $2",

# Email this user
#
"mailnologin"   => "N�l aon seoladh maith ann",
"mailnologintext" => "Caithfidh t� bheith  <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
agus bheith le seoladh r�omhphoist bhail� i do chuid <a href=\"" .
  wfLocalUrl( "Speisialta:Preferences" ) . "\">socruithe</a>
m�s mian leat r�omhphost a chur go �s�ideoir� eile.",
"emailuser"     => "Cuir r�omhphost chuig an �s�ideoir seo",
"emailpage"     => "Seol r�omhphost",
"emailpagetext" => "Ma d'iontr�il an �s�ideoir seo seoladh r�omhphoist bhail� 
ina socruithe �s�ideora, cuirfidh an foirm anseo th�os teactaireacht amh�in do.
Beidh do seoladh r�omhphoist, a d'iontr�il t� i do socruithe �s�ideora, ann
san bhosca \"�\" an riomhphoist, agus mar sin ba f�idir l�is an faighteoir r�omhphost a chur leatsa.",
"noemailtitle"  => "N�l aon seoladh r�omhphoist ann",
"noemailtext"   => "N�or thug an �s�ideoir seo seoladh r�omhphoist bhail�, n� shocraigh s� nach
mian leis r�omhphost a fh�il �n �s�ideoir� eile.",
"emailfrom"     => "�",
"emailto"       => "Go",
"emailsubject"  => "�bhar",
"emailmessage"  => "Teachtaireacht",
"emailsend"     => "Cuir an r�omhphost",
"emailsent"     => "R�omhphost curtha",
"emailsenttext" => "Cuireadh do teachtaireacht r�omhphoist go r�th�il.",

# Watchlist
#
"watchlist"     => "Mo liosta faire",
"watchlistsub"  => "(don �s�ideoir \"$1\")",
"nowatchlist"   => "N�l aon rud i do liosta faire.",
"watchnologin"  => "N�l t� logtha ann",
"watchnologintext"  => "Caithfidh t� bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun do liosta faire a athraigh.",
"addedwatch"    => "Curtha san liosta faire",
"addedwatchtext" => "Cuireadh an leathanach \"$1\" le do <a href=\"" .
  wfLocalUrl( "Speisialta:Watchlist" ) . "\">liosta faire</a>.
Cuirfear athruithe amach anseo don leathanach sin agus a leathanach phl� leis an liosta ann,
agus beidh <b>cl� dubh</b> ar a teideal san <a href=\"" .
  wfLocalUrl( "Speisialta:Recentchanges" ) . "\">liosta den athruithe deireanacha</a> sa chaoi go 
bhfeicfe� iad go h�asca.</p>

<p>M�s mian leat an leathanach a bain amach do liosta faire n�os d�ana�, br�igh ar \"Stop ag faire\" ar an taobhbharra.",
"removedwatch"  => "Bainthe amach �n liosta faire",
"removedwatchtext" => "Baineadh an leathanach \"$1\" amach � do liosta faire.",
"watchthispage" => "Faire ar an leathanach seo",
"unwatchthispage" => "Stop ag faire",
"notanarticle"  => "N�l alt ann",
"watchnochange" => "N�or athra�odh aon de na leathanaigh i do liosta faire taobh istigh den am socraithe.",
"watchdetails" => "(T� t� ag faire ar $1 leathanaigh chomh maith leis na leathanaigh phl�;
le d�anach athra�odh $2 leathanaigh in ioml�n;
$3...
<a href='$4'>athraigh do liosta</a>.)",
"watchmethod-recent" => "ag seice�il na athruithe deireanacha do leathanaigh faire",
"watchmethod-list" => "ag seice�il na leathanaigh faire do athruithe deireanacha",
"removechecked" => "Bain m�reanna marc�laithe amach as do liosta faire",
"watchlistcontains" => "T� $1 leathanaigh i do liosta faire.",
"watcheditlist" => "Seo liosta na leathanaigh i do liosta faire, in ord aibitre. 
Marc�il bosca� de na leathanaigh at� le baint amach an liosta faire, agus bruigh 
an cnaipe 'bain amach le marcanna' ag bun an leathanaigh.",
"removingchecked" => "Ag baint amach na m�reanna �n liosta faire mar a iarraidh...",
"couldntremove" => "N�or baineadh amach an m�r '$1'...",
"iteminvalidname" => "Fadhb leis an m�r '$1', ainm neamhbhail�...",
"wlnote" => "Seo iad na $1 athruithe seo caite sna <b>$2</b> uaire seo caite.",
"wlshowlast" => "Taispe�in na m�reanna deireanacha $1 uaire $2 laethanta $3", #FIXME

# Delete/protect/revert
#
"deletepage"    => "Scrios leathanach",
"confirm"       => "Cinntigh",
"excontent" => "sin a raibh an �bhar:",
"exbeforeblank" => "sin a raibh an �bhar roimh an folmhadh:",
"exblank" => "bh� an leathanach folamh",
"confirmdelete" => "Cinntigh an scriosadh",
"deletesub"     => "(Ag scriosadh \"$1\")",
"historywarning" => "Aire: Ta stair ag an leathanach a bhfuil t� ar t� � a scrios: ",
"confirmdeletetext" => "T� t� ar t� leathanach n� �omh� a scrios, 
chomh maith leis a chuid stair, �n bunachar sonra�. 
Cinntigh go mian leis an m�id seo a dh�anamh, go dtuigeann t� na
iarmhairta�, agus go nd�anann t� � dar leis [[Vicip�id:Polasa�]].",
"confirmcheck"  => "Sea, is mian liom go f�rinneach an rud seo a scrios.",
"actioncomplete" => "Gn�omh d�anta",
"deletedtext"   => "\"$1\" at� scriosaithe.
F�ach ar $2 chun cuntas den scriosadh deireanacha a fh�il.",
"deletedarticle" => "scriosadh \"$1\"",
"dellogpage"    => "Cuntas_scriosaidh",
"dellogpagetext" => "Seo � liosta de na scriosaidh is deireanacha. 
Is am an frioth�la� (UTC) iad na hamanna at� anseo th�os.
<ul>
</ul>
",
"deletionlog"   => "cuntas scriosaidh",
"reverted"      => "T� eagr�n n�os luaithe in �s�id anois",
"deletecomment" => "C�is do scriosadh",
"imagereverted" => "D'�irigh le ath�s�id go eagr�n n�os luath.",
"rollback"      => "Aththosaigh athruithe", #FIXME
"rollbacklink"  => "ath�s�id",
"rollbackfailed" => "Theipeadh an ath�s�id",
"cantrollback"  => "N� f�idir an athr� a �th�s�id; ba � �dar an ailt an aon scr�bhneoir at� ann.",
"alreadyrolled" => "N� f�idir eagr�n n�os luath an leathanach [[$1]] 
le [[�s�ideoir:$2|$2]] ([[Pl� �s�ideora:$2|Pl�]]) a ath�s�id; d'athraigh duine eile � f�s n� 
d'ath�s�id duine eile eagr�n n�os luaithe f�s.

Ba � [[�s�ideoir:$3|$3]] ([[Pl� �s�ideora:$3|Pl�]]) an t� a rinne an athr� seo caite. ",
#   only shown if there is an edit comment
"editcomment" => "Seo a raibh an m�nithe athraithe: \"<i>$1</i>\".", 
"revertpage"    => "D'ath�s�ideadh an athr� seo caite le $1",
"protectlogpage" => "Cuntas_cosanta",
"protectlogtext" => "Seo � liosta de glais a cuireadh ar / baineadh de leathanaigh.
F�ach ar [[$wgMetaNamespace:Leathanach faoi ghlas]] chun n�os m� eolais a fh�il.",
"protectedarticle" => "faoi ghlas [[$1]]",
"unprotectedarticle" => "gan ghlas [[$1]]",

# Undelete
"undelete" => "Cuir leathanach scriosaithe ar ais",
"undeletepage" => "F�ach ar agus cuir ar ais leathanaigh scriosaithe",
"undeletepagetext" => "Scriosa�odh na leathanaigh seo a leanas cheana, ach
t� s�ad ann f�s san cartlann agus is f�idir iad a cuir ar ais. 
� am go ham, is f�idir leis an cartlann bheith folmhaithe.",
"undeletearticle" => "Cuir alt scriosaithe ar ais",
"undeleterevisions" => "Cuireadh $1 athbhreithniuthe sa chartlann",
"undeletehistory" => "M� chuirfe� ab leathanach ar ais, cuirfear ar ais gach athbhreithni� chuig an stair.
M� chrutha�odh leathanach nua leis an ainm c�anna � shin an scriosadh, taispe�infear
na sean-athruithe san stair roimhe seo, agus n� athshuighfear an eagr�n reatha an leathanaigh go huathoibr�och.",
"undeleterevision" => "Athbhreithni� scriosaithe den d�ta $1",
"undeletebtn" => "Cuir ar ais!",
"undeletedarticle" => "cuireadh \"$1\" ar ais",
"undeletedtext"   => "Cuireadh an alt [[$1]] ar ais go rath�il.
F�ach ar [[Vicip�id:Cuntas_scriosaidh]] chun cuntas de scriosaidh agus athch�irithe deireanacha a fh�il.",

# Contributions
#
"contributions" => "Dr�achta� �s�ideora",
"mycontris" => "Mo dr�achta�",
"contribsub"    => "Do $1",
"nocontribs"    => "N�or bhfuarthas aon athr� a raibh cos�il le na cr�t�ir seo.",
"ucnote"        => "Is iad seo th�os na <b>$1</b> athruithe is deireana� an �s�ideora sna <b>$2</b> lae seo caite.",
"uclinks"       => "F�ach ar na $1 athruithe is deireana�; f�ach ar na $2 lae seo caite.",
"uctop"     => " (barr)" ,

# What links here
#
"whatlinkshere" => "Cad a nasca�onn anseo",
"notargettitle" => "N�l aon sprioc ann",
"notargettext"  => "N�or thug t� leathanach n� �s�ideoir sprice 
chun an gn�omh seo a dh�anamh ar.",
"linklistsub"   => "(Liosta nasc)",
"linkshere"     => "Nasca�onn na leathanaigh seo a leanas chuig an leathanach seo:",
"nolinkshere"   => "N� nasca�onn aon leathanach chuig an leathanach seo.",
"isredirect"    => "Leathanach athsheolaidh",

# Block/unblock IP
#
"blockip"       => "Coisc �s�ideoir",
"blockiptext"   => "�s�id an foirm anseo th�os chun bealach scr�ofa a chosc � 
seoladh IP n� ainm �s�ideora �irithe.
Is f�idir leat an rud seo a dh�anamh amh�in chun an chreachad�ireacht a chosc, de r�ir
mar a deirtear san [[Vicip�id:Polasa�|polasa� Vicip�ide]].
L�onaigh c�is �irithe anseo th�os (mar shampla, is f�idir leat a luaigh
leathanaigh �irithe a rinne an duine dam�iste ar).",
"ipaddress"     => "Seoladh IP / ainm �s�ideora",
"ipbreason"     => "C�is",
"ipbsubmit"     => "Coisc an �s�ideoir seo",
"badipaddress"  => "N�l aon �s�ideoir ann leis an ainm seo.",
"noblockreason" => "Caithfidh t� c�is a thabhairt don cosc.",
"blockipsuccesssub" => "D'�irigh leis an cosc",
"blockipsuccesstext" => "Choisceadh \"$1\".
<br>F�ach ar [[Speisialta:Ipblocklist|Liosta coisc IP]] chun coisc a athbhreithnigh.",
"unblockip"     => "Bain an cosc den �s�ideoir",
"unblockiptext" => "�s�id an foirm anseo th�os chun bealach scr�ofa a thabhairt ar ais go seoladh 
IP n� ainm �s�ideora a raibh coiscthe roimhe seo.",
"ipusubmit"     => "Bain an cosc den seoladh seo",
"ipusuccess"    => "\"$1\" gan cosc",
"ipblocklist"   => "Liosta seolta� IP agus ainmneacha �s�ideoir� coiscthe",
"blocklistline" => "$1, $2 a choisc$3",
"blocklink"     => "Coisceadh",
"unblocklink"   => "bain an cosc den",
"contribslink"  => "dr�achta�",
"autoblocker"   => "Coiscthe go sealadach go huathoibr�och de bhr� go roinneann t� an seoladh IP c�anna le \"$1\". C�is \"$2\".",
"blocklogpage"  => "Cuntas_coisc",
"blocklogentry" => 'coisceadh "$1"',
"blocklogtext"  => "Seo � cuntas de gn�omhartha coisc �s�ideoir� agus m�choisc �s�ideoir�. N� cuirtear
seolta� IP a raibh coiscthe go huathoibr�och ar an liosta seo. F�ach ar an [[Speisialta:Ipblocklist|Liosta coisc IP]] chun
liosta a fh�il de coisc at� i bhfeidhm faoi l�thair.",
"unblocklogentry"   => 'baineadh an cosc den "$1"',

# Developer tools
#
"lockdb"        => "Cuir glas ar an bunachar sonra�",
"unlockdb"      => "Bain an glas den bunachar sonra�",
"lockdbtext"    => "M� chuirfe� glas ar an bunachar sonra�, n� beidh cead ar aon �s�ideoir
leathanaigh a athraigh, a socruithe a athraigh, a liosta� faire a athraigh, n� ruda� eile a thrachtann le 
athruithe san bunachar sonra�.
Cinntigh go bhfuil an sc�al seo d'intinn agat, is go bainfidh t� an glas den bunachar sonra� nuair a bhfuil 
do chuid coinne�la d�anta.",
"unlockdbtext"  => "M� bhainfe� an glas den bunachar sonra�, beidh ceat ag gach �s�ideoir� aris
na leathanaigh a cuir in eagar, a socruithe a athraigh, a liosta� faire a athraigh, agus ruda� eile
a dh�anamh a thrachtann le athruithe san bunachar sonra�. 
Cinntigh go bhfuil an sc�al seo d'intinn agat.",
"lockconfirm"   => "Sea, is mian liom glas a chur ar an bunachar sonra�.",
"unlockconfirm" => "Sea, is mian liom glas a bhain den bunachar sonra�.",
"lockbtn"       => "Cuir glas ar an bunachar sonra�",
"unlockbtn"     => "Bain an glas den bunachar sonra�",
"locknoconfirm" => "N�or mharc�il t� an bosca daingnithe.",
"lockdbsuccesssub" => "D'�irigh le glas an bunachair sonra�",
"unlockdbsuccesssub" => "Baineadh an glas den bunachar sonra�",
"lockdbsuccesstext" => "Cuireadh glas ar an bunachar sonra� Vicip�ide.
<br>Cuimhnigh go caithfidh t� an glas a bhaint tar �is do chuid coinne�la.",
"unlockdbsuccesstext" => "Baineadh an glas den bunachar sonra� Vicip�ide.",

# SQL query
#
"asksql"        => "Ceist SQL",
"asksqltext"    => "�s�id an foirm anseo th�os chun ceist d�reach a dh�anamh den bunachar sonra� Vicip�ide. 
�s�id comhartha� athfhriotail singile ('mar sin') chun teorainn a chur le litri�la sraithe. �s�id an gn� seo go coigilteach.",
"sqlislogged"   => "Tabhair faoi deara go cuirtear gach ceist i gcuntas.",
"sqlquery"      => "Iontr�il ceist",
"querybtn"      => "Cuir ceist",
"selectonly"    => "N�l na ceisteanna ina theannta \"SELECT\" ann ach amh�in do 
cl�raitheoir� Vicip�ide.",
"querysuccessful" => "D'�irigh leis an ceist",

# Move page
#
"movepage"      => "Aistrigh an leathanach",
"movepagetext"  => "�s�is an foirm anseo th�os chun leathanach a athainmnigh. Aistreofar a chuid
stair go l�ir chuig an ainm nua.
D�anfar leathanach athsheolaidh den sean-teideal chuig an teideal nua.
N� athreofar naisc chuig sean-teideal an leathanach. Bheith cinnte chun 
[[Special:Maintenance|cuardach]] a dh�anamh ar athsheolaidh dub�ilte n� briste.
T� t� freagrach i cinnteach go leanann naisc chuig an pointe a bhfuil siad ag aimsigh ar.

Tabhair faoi deara go '''nach''' aistreofar an leathanach m� bhfuil leathanach 
ann cheana ag an teideal nua, mura bhfuil s� folamh n� athsheoladh n� mura bhfuil aon 
stair athraithe aige cheana. Cialla�onn s� sin go f�idir leat leathanach a athainmnigh ar ais
chuig an �it ina raibh s� roimhe m� dh�anf� bot�n, agus n� f�idir leat leathanach at� ann a forshcriobh ar.

<b>AIRE!</b>
Is f�idir leis an m�id seo bheith athr� borb gan s�il leis do leathanach coitianta;
cinntigh go dtuigeann t� na iarmhairt� go l�ir roimh a leanf�.",
"movepagetalktext" => "Aistreofar an leathanach phl� leis, m� t� sin ann:
*'''mura''' bhfuil t� ag aistrigh an leathanach trasna ranna,
*'''mura''' bhfuil leathanach phl� neamhfholamh ann leis an ainm nua, n�
*'''mura''' baineann t� an marc den bosca anseo th�os.

Sna sc�il sin, caithfidh t� an leathanach a aistrigh n� a b�igh leis na l�mha m� t� sin an rud at� uait.",
"movearticle"   => "Aistrigh an leathanach",
"movenologin"   => "N�l t� logtha ann",
"movenologintext" => "Caithfidh t� bheith �s�ideoir cl�raithe agus <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun leathanach a aistrigh.",
"newtitle"      => "Go teideal nua",
"movepagebtn"   => "Aistrigh an leathanach",
"pagemovedsub"  => "D'�irigh leis an aistri�",
"pagemovedtext" => "D'aistra�odh an leathanach \"[[$1]]\" go \"[[$2]]\".",
"articleexists" => "T� leathanach leis an ainm seo ann f�s, n� n�l an
ainm a rinne t� rogha air ina ainm bail�.
Toghaigh ainm eile le do thoil.",
"talkexists"    => "D'aistra�odh an leathanach � f�in go rath�il, ach n� raibh s� ar a chumas an 
leathanach phl� a aistrigh de bhr� go bhfuil ceann ann f�s ag an
teideal nua. B�igh iad go l�imhe le do thoil.",
"movedto"       => "aistraithe go",
"movetalk"      => "Aistrigh an leathanach \"phl�\" freisin, m� bhfuil an leathanach sin ann.",
"talkpagemoved" => "D'aistra�odh an leathanach phl� frithiontr�il.",
"talkpagenotmoved" => "<strong>N�or</strong> aistra�odh an leathanach phl� frithiontr�il.",

"export"        => "Onnmhairigh leathanaigh",
"exporttext"    => "Is f�idir leat an t�acs agus stair athraithe de leathanach �irithe a onnmhairi�, 
fillte i bp�osa XML; is f�idir leat ansin � a iomp�rt�l isteach wiki eile at� le na oideasra MediaWiki
air, n� is f�idir leat � a coinnigh do do siamsa f�in.",
"exportcuronly" => "N� cuir san �ireamh ach an eagr�n reatha, n� cuir ann an stair in ioml�n",

# Namespace 8 related

"allmessages"   => "Teachtaireachta�_go_l�ir",
"allmessagestext"   => "Seo � liosta de na teachtaireachta� go l�ir at� le f�il san roinn MediaWiki: ."
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class Language {

    function getDefaultUserOptions () {
        global $wgDefaultUserOptionsGa ;
        return $wgDefaultUserOptionsGa ;
        }

    function getBookstoreList () {
        global $wgBookstoreListGa ;
        return $wgBookstoreListGa ;
    }

    function getNamespaces() {
        global $wgNamespaceNamesGa;
        return $wgNamespaceNamesGa;
    }

    function getNsText( $index ) {
        global $wgNamespaceNamesGa;
        return $wgNamespaceNamesGa[$index];
    }

    function getNsIndex( $text ) {
        global $wgNamespaceNamesGa;

        foreach ( $wgNamespaceNamesGa as $i => $n ) {
            if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
        }
        return false;
    }

    function specialPage( $name ) {
        return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
    }

    function getQuickbarSettings() {
        global $wgQuickbarSettingsGa;
        return $wgQuickbarSettingsGa;
    }

    function getSkinNames() {
        global $wgSkinNamesGa;
        return $wgSkinNamesGa;
    }

    function getMathNames() {
        global $wgMathNamesGa;
        return $wgMathNamesGa;
    }
    
    function getDateFormats() {
        global $wgDateFormatsGa;
        return $wgDateFormatsGa;
    }

    function getUserToggles() {
        global $wgUserTogglesGa;
        return $wgUserTogglesGa;
    }

    function getLanguageNames() {
        global $wgLanguageNamesGa;
        return $wgLanguageNamesGa;
    }

    function getLanguageName( $code ) {
        global $wgLanguageNamesGa;
        if ( ! array_key_exists( $code, $wgLanguageNamesGa ) ) {
            return "";
        }
        return $wgLanguageNamesGa[$code];
    }

    function getMonthName( $key )
    {
        global $wgMonthNamesGa;
        return $wgMonthNamesGa[$key-1];
    }
    
    /* by default we just return base form */
    function getMonthNameGen( $key )
    {
        global $wgMonthNamesGa;
        return $wgMonthNamesGa[$key-1];
    }

    function getMonthAbbreviation( $key )
    {
        global $wgMonthAbbreviationsGa;
        return $wgMonthAbbreviationsGa[$key-1];
    }

    function getWeekdayName( $key )
    {
        global $wgWeekdayNamesGa;
        return $wgWeekdayNamesGa[$key-1];
    }

    function userAdjust( $ts )
    {
        global $wgUser, $wgLocalTZoffset;

        $diff = $wgUser->getOption( "timecorrection" );
        if ( ! is_numeric( $diff ) ) {
            $diff = isset( $wgLocalTZoffset ) ? $wgLocalTZoffset : 0;
        }
        if ( 0 == $diff ) { return $ts; }

        $t = mktime( ( (int)substr( $ts, 8, 2) ) + $diff,
          (int)substr( $ts, 10, 2 ), (int)substr( $ts, 12, 2 ),
          (int)substr( $ts, 4, 2 ), (int)substr( $ts, 6, 2 ),
          (int)substr( $ts, 0, 4 ) );
        return date( "YmdHis", $t );
    }
 
    function date( $ts, $adj = false )
    {
        global $wgAmericanDates, $wgUser, $wgUseDynamicDates;

        if ( $adj ) { $ts = $this->userAdjust( $ts ); }
        
        if ( $wgUseDynamicDates ) {
            $datePreference = $wgUser->getOption( 'date' );     
            if ( $datePreference == 0 ) {
                $datePreference = $wgAmericanDates ? 1 : 2;
            }
        } else {
            $datePreference = $wgAmericanDates ? 1 : 2;
        }
        
        if ( $datePreference == 1 ) {
            # MDY
            $d = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
              " " . (0 + substr( $ts, 6, 2 )) . ", " .
              substr( $ts, 0, 4 );
        } else if ( $datePreference == 2 ) {
            #DMY
            $d = (0 + substr( $ts, 6, 2 )) . " " .
              $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
              substr( $ts, 0, 4 );
        } else {
            #YMD
            $d = substr( $ts, 0, 4 ) . " " . $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
                " " . (0 + substr( $ts, 6, 2 ));
        }

        return $d;
    }

    function time( $ts, $adj = false )
    {
        if ( $adj ) { $ts = $this->userAdjust( $ts ); }

        $t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
        return $t;
    }

    function timeanddate( $ts, $adj = false )
    {
        return $this->time( $ts, $adj ) . ", " . $this->date( $ts, $adj );
    }

    function rfc1123( $ts )
    {
        return date( "D, d M Y H:i:s T", $ts );
    }

    function getValidSpecialPages()
    {
        global $wgValidSpecialPagesGa;
        return $wgValidSpecialPagesGa;
    }

    function getSysopSpecialPages()
    {
        global $wgSysopSpecialPagesGa;
        return $wgSysopSpecialPagesGa;
    }

    function getDeveloperSpecialPages()
    {
        global $wgDeveloperSpecialPagesGa;
        return $wgDeveloperSpecialPagesGa;
    }

    function getMessage( $key )
    {
        global $wgAllMessagesGa;
        return $wgAllMessagesGa[$key];
    }
    
    function getAllMessages()
    {
        global $wgAllMessagesGa;
        return $wgAllMessagesGa;
    }

    function iconv( $in, $out, $string ) {
        # For most languages, this is a wrapper for iconv
        return iconv( $in, $out, $string );
    }
    
    function ucfirst( $string ) {
        # For most languages, this is a wrapper for ucfirst()
        return ucfirst( $string );
    }
    
    function checkTitleEncoding( $s ) {
        global $wgInputEncoding;
        
        # Check for UTF-8 URLs; Internet Explorer produces these if you
        # type non-ASCII chars in the URL bar or follow unescaped links.
        $ishigh = preg_match( '/[\x80-\xff]/', $s);
        $isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

        if( ($wgInputEncoding != "utf-8") and $ishigh and $isutf )
            return iconv( "UTF-8", $wgInputEncoding, $s );
        
        if( ($wgInputEncoding == "utf-8") and $ishigh and !$isutf )
            return utf8_encode( $s );
        
        # Other languages can safely leave this function, or replace
        # it with one to detect and convert another legacy encoding.
        return $s;
    }
    
    function stripForSearch( $in ) {
        # Some languages have special punctuation to strip out
        # or characters which need to be converted for MySQL's
        # indexing to grok it correctly. Make such changes here.
        return $in;
    }


    function setAltEncoding() {
        # Some languages may have an alternate char encoding option
        # (Esperanto X-coding, Japanese furigana conversion, etc)
        # If 'altencoding' is checked in user prefs, this gives a
        # chance to swap out the default encoding settings.
        #global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
    }

    function recodeForEdit( $s ) {
        # For some languages we'll want to explicitly specify
        # which characters make it into the edit box raw
        # or are converted in some way or another.
        # Note that if wgOutputEncoding is different from
        # wgInputEncoding, this text will be further converted
        # to wgOutputEncoding.
        global $wgInputEncoding, $wgEditEncoding;
        if( $wgEditEncoding == "" or
          $wgEditEncoding == $wgInputEncoding ) {
            return $s;
        } else {
            return $this->iconv( $wgInputEncoding, $wgEditEncoding, $s );
        }
    }

    function recodeInput( $s ) {
        # Take the previous into account.
        global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
        if($wgEditEncoding != "") {
            $enc = $wgEditEncoding;
        } else {
            $enc = $wgOutputEncoding;
        }
        if( $enc == $wgInputEncoding ) {
            return $s;
        } else {
            return $this->iconv( $enc, $wgInputEncoding, $s );
        }
    }

    # For right-to-left language support
    function isRTL() { return false; }

    function getMagicWords() 
    {
        global $wgMagicWordsGa;
        return $wgMagicWordsGa;
    }

    # Fill a MagicWord object with data from here
    function getMagic( &$mw )
    {
        $raw = $this->getMagicWords(); # don't worry, it's reference counted not deep copy
        $rawEntry = $raw[$mw->mId];
        $mw->mCaseSensitive = $rawEntry[0];
        $mw->mSynonyms = array_slice( $rawEntry, 1 );
    }
}

include_once( "Language" . ucfirst( $wgLanguageCode ) . ".php" );

?>
