<?php

/* Gaeilge - Irish */

/*
Seo é an dara leagan den comhad LanguageGa.php. Úsáidtear an iomaí foclóirí agus
achmhainní idirlín - má tá aon contrárthacht, pléigh é ar an leathanch phlé ach
NA HATHRAIGH É GO DÍREACH, MÁS É DO THOIL É!

This is the second version of the LanguageGa.php file. Multiple dictionaries and
internet resources have been utilised - if there is any inconsistency or other
error, discuss it on the talk page but PLEASE DO NOT CHANGE IT DIRECTLY WITHOUT
DISCUSSION!

*/

# FIXME: Use $wgSitename, $wgMetaNamespace etc
#  - that could be hard:
#
# NOTE TO DEVELOPERS - a genitive version of the sitename Vicipéid
# is needed for "of" uses etc. The genitive varies for each sitename.
# How can this be incorporated?

/* private */ $wgNamespaceNamesGa = array(
	NS_MEDIA            => 'Meán',
	NS_SPECIAL          => 'Speisialta',
	NS_MAIN	            => '',
	NS_TALK	            => 'Plé',
	NS_USER             => 'Úsáideoir',
	NS_USER_TALK        => 'Plé_úsáideora',
	NS_WIKIPEDIA        => 'Vicipéid',
	NS_WIKIPEDIA_TALK   => 'Plé_Vicipéide', # NOTE TO DEVELOPERS: A different genitive spelling is needed for
	                            # each Wiki name, as can be seen. So the $wgMetaNamespace tag has not been used
	                            # here for the moment in some cases, and in other places with a FIXME flag.
	NS_IMAGE            => 'Íomhá',
	NS_IMAGE_TALK       => 'Plé_í­omhá',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Plé_MediaWiki',
	NS_TEMPLATE         => 'Múnla',
	NS_TEMPLATE_TALK    => 'Plé_múnla',
	NS_HELP             => 'Cabhair',
	NS_HELP_TALK        => 'Plé_cabhrach',
	NS_CATEGORY	    => 'Rang',
	NS_CATEGORY_TALK    => 'Plé_ranga'
) + $wgNamespaceNamesEn;

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
    "Faic", "Greamaithe ar chlé", "Greamaithe ar an taobh deas", "Ag faoileáil ar chlé"
);

/* private */ $wgSkinNamesGa = array(
	'standard' => "Gnáth",
	'nostalgia' => "Sean-nós",
	'cologneblue' => "Gorm na Colóin",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Aondath",
	'monobook' => "ÚrLeabhar",
 "myskin" => "Sean-nós Nua"
);


/* private */ $wgDateFormatsGa = array(
    "Is cuma liom",
    "Eanáir 15, 2001",
    "15 Eanáir 2001",
    "2001 Eanáir 15",
    "2001-01-15"
);

# If possible, find Irish language book services on the Internet, searchable by ISBN
# $wgBookstoreListGa = ..

/* private */ $wgBookstoreListGa = array(
    "AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
    "PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
    "Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
    "Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

# The following exist for the purpose of being translated:

/* private */ $wgMagicWordsGa = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    "#redirect", "#athsheoladh"              ),
    MAG_NOTOC                => array( 0,    "__NOTOC__"              ),
    MAG_NOEDITSECTION        => array( 0,    "__NOEDITSECTION__"      ),
    MAG_START                => array( 0,    "__START__"              ),
    MAG_CURRENTMONTH         => array( 1,    "CURRENTMONTH", "MÍREATHA"           ),
    MAG_CURRENTMONTHNAME     => array( 1,    "CURRENTMONTHNAME", "AINMMHÍREATHA"       ),
    MAG_CURRENTDAY           => array( 1,    "CURRENTDAY", "LÁREATHA"             ),
    MAG_CURRENTDAYNAME       => array( 1,    "CURRENTDAYNAME", "AINMLAEREATHA"         ),
    MAG_CURRENTYEAR          => array( 1,    "CURRENTYEAR", "BLIAINREATHA"            ),
    MAG_CURRENTTIME          => array( 1,    "CURRENTTIME", "AMREATHA"            ),
    MAG_NUMBEROFARTICLES     => array( 1,    "NUMBEROFARTICLES", "MÉIDAILT"       ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    "CURRENTMONTHNAMEGEN"    ), #FIXME - what is this?
    MAG_MSG                  => array( 1,    "MSG:", "TCHT"                   ),
    MAG_SUBST                => array( 1,    "SUBST:"                 ),
    MAG_MSGNW                => array( 1,    "MSGNW:"                 ),
	MAG_END                  => array( 0,    "__END__", "__DEIREADH__"             ),
    MAG_IMG_THUMBNAIL        => array( 1,    "thumbnail", "thumb", "reamhspléach", "beag"     ),
    MAG_IMG_RIGHT            => array( 1,    "right", "taobhdeas"                  ),
    MAG_IMG_LEFT             => array( 1,    "left", "clé"                   ),
    MAG_IMG_NONE             => array( 1,    "none", "faic"                   ),
    MAG_IMG_WIDTH            => array( 1,    "$1px"                   ),
    MAG_IMG_CENTER           => array( 1,    "center", "centre", "lár"       ),
    MAG_INT                  => array( 0,    "INT:"                   ) #FIXME - what is this?

);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesGa = array(
    "Userlogin"     => "",
    "Userlogout"    => "",
    "Preferences"   => "Athraigh mo socruithe",
    "Watchlist"     => "Mo fairechlár", # List of pages, which the user has chosen to watch
    "Recentchanges" => "Leathanaigh leasaithe le déanaí­",
    "Upload"        => "Suaslódáil comhaid agus í­omhánna",
    "Imagelist"     => "Liosta í­omhánna",
    "Listusers"     => "Úsáideoirí­ cláraithe",
    "Statistics"    => "Staitistic an shuí­omh",
    "Randompage"    => "Leathanach fánach",

    "Lonelypages"   => "Leathanaigh dí­lleachtaí­",
    "Unusedimages"  => "Íomhánna dí­lleachtaí­",
    "Popularpages"  => "Ailt coitianta",
    "Wantedpages"   => "Ailt santaithe",
    "Shortpages"    => "Ailt gairide",
    "Longpages"     => "Ailt fada",
    "Newpages"      => "Ailt nua",
    "Ancientpages"  => "Ailt ársa",
#   "Intl"      => "Lúibí­ní­ idirtheangacha",
    "Allpages"      => "Gach leathanach de réir ainm",

    "Ipblocklist"   => "Úsáideoirí­/IP-sheolaidh coisctha",
    "Maintenance"   => "Leathanach coiméadta",
    "Specialpages"  => "",
    "Contributions" => "",
    "Emailuser"     => "",
    "Whatlinkshere" => "",
    "Recentchangeslinked" => "",
    "Movepage"      => "",
    "Booksources"   => "Leabharfhoinsí­ seachtraí­",
#   "Categories"    => "Ranganna leathanaigh",
    "Export"        => "Onnmhairigh XML",
    "Version"		=> "Leagan",
);

/* private */ $wgSysopSpecialPagesGa = array(
    "Blockip"       => "Cuir cosc ar úsáideoir/IP-sheoladh",
    "Asksql"        => "Cuir ceist ar an bhunachar sonraí­",
    "Undelete"      => "Cuir leathanaigh scriosaithe ar ais"
);

/* private */ $wgDeveloperSpecialPagesGa = array(
    "Lockdb"        => "Cuir glas ar an mbunachar sonraí­",
    "Unlockdb"      => "Bain an glas den bunachar sonraí­",
    "Debug"     => "Eolas chun fadhtanna a réitigh"
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

/* private */ $wgAllMessagesGa = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User Toggles
#

"tog-hover"     => "Taispeáin airebhoscaí­ os cionn na vicilúibí­ní­",
"tog-underline" => "Cuir lí­nte faoi na lúibí­ní­",
"tog-highlightbroken" => "Cuir dath dearg ar lúibí­ní­ briste, <a href=\"\" class=\"new\">mar sin</a> (rogha eile: mar sin<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"   => "Comhfhadaigh na paragraif",
"tog-hideminor" => "Ná taispeáin fo-eagair sna hathruithe is déanaí",
"tog-usenewrc" => "Stí­l nua do na hathruithe le déanaí (le JavaScript)",
"tog-numberheadings" => "Uimhrigh ceannteidil go huathoibrí­och",
"tog-editondblclick" => "Cuir leathanaigh in eagar le roghna dúbailte (JavaScript)",
"tog-editsection"=>"Cumasaigh eagarthóireacht mí­r le lúibí­ní­ [athraithe]",
"tog-editsectiononrightclick"=>"Cumasaigh eagarthóireacht mí­r le deas-roghna<br> ar ceannteidil (JavaScript)",
"tog-showtoc"=>"Déan liosta na ceannteideal<br>(do ailt le ní­os mó ná 3 ceannteidil)",
"tog-rememberpassword" => "Cuimhnigh mo focal faire",
"tog-editwidth" => "Cuir uasméid ar an mbosca eagair",
"tog-watchdefault" => "Breathnaigh ar leathanaigh a d'athraigh tú",
"tog-minordefault" => "Cuir marc mionathraithe ar gach athrú, mar réamhshocrú",
"tog-previewontop" => "Cuir an réamhthaispeántas os cionn an bhosca eagair, agus ná taobh thí­os de",
"tog-nocache" => "Ciorraigh an taisce leathanaigh",

# Dates
#
# NOTE TO DEVELOPERS: please note that different spellings of days are necessary
# when talking about a particular day or the day in general. Here they have been
# rendered in the "particular day" version as that seems more useful; the others,
# more useful as titles, have been moved outside, as follows:
#
'sunday' => 'Dé Domhnaigh', # Domhnach
'monday' => 'Dé Luain', # Luan
'tuesday' => 'Dé Mháirt', # Máirt
'wednesday' => 'Dé Chéadaoin', # Céadaoin
'thursday' => 'Déardaoin', # same spelling
'friday' => 'Dé hAoine', # Aoine
'saturday' => 'Dé Sathairn', # Satharn
'january' => 'Eanáir',
'february' => 'Feabhra',
'march' => 'Márta',
'april' => 'Aibreán',
'may_long' => 'Bealtaine',
'june' => 'Meitheamh',
'july' => 'Iúil',
'august' => 'Lúnasa',
'september' => 'Meán Fómhair',
'october' => 'Deireadh Fómhair',
'november' => 'Mí­ na Samhna',
'december' => 'Mí­ na Nollag',
'jan' => 'Ean',
'feb' => 'Fea',
'mar' => 'Már',
'apr' => 'Aib',
'may' => 'Bea',
'jun' => 'Mei',
'jul' => 'Iúi',
'aug' => 'Lún',
'sep' => 'Mea',
'oct' => 'Dei',
'nov' => 'Samh',
'dec' => 'Nol',


# Bits of text used by many pages:
#
# FIXME
#
"categories" => "Ranganna leathanaigh",
"category" => "rang",
"category_header" => "Ailt sa rang \"$1\"",
"subcategories" => "Fo-ranganna",

"linktrail"     => "/^([a-z]+)(.*)\$/sD",
"mainpage"      => "Príomh­leathanach",
"mainpagetext"  => "D'insealbhaí­odh an oideas mearshuímh go rathúil.",
"mainpagedocfooter" => "Féach ar [http://meta.wikimedia.org/wiki/MediaWiki_i18n doiciméid um conas an chomhéadán a athrú]
agus an [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Lámhleabhar úsáideora] chun cabhair úsáide agus fíoraíochta a fháil.",
'portal'		=> 'Lárionad chomhphobail',
'portal-url'		=> '{{ns:4}}:Lárionad chomhphobail',
"about"     => "Faoi",
"aboutwikipedia"    => "Faoin Vicipéid",
'article'       => "Clár ábhair",
"aboutpage"     => "Vicipéid:Faoi",
"help"      => "Cabhair",
"helppage"      => "Vicipéid:Cabhair",
"wikititlesuffix" => "Vicipéid",
"bugreports"    => "Fabht-thuairiscí­",
"bugreportspage"    => "Vicipéid:Fabht-thuairiscí­",
"faq"           => "Ceisteanna Coiteanta",
"faqpage"       => "Vicipéid:Ceisteanna_Coiteanta",
"edithelp"      => "Cabhair eagarthóireachta",
"newwindow"     => "(osclaítear i fuinneog eile é)",
"edithelppage"  => "Vicipéid:Conas_a_cuirtear_alt_in_eagar",
"cancel"        => "Ná déan",
"qbfind"        => "Faigh",
"qbbrowse"      => "Siortaigh",
"qbedit"        => "Athraigh",
"qbpageoptions" => "An lch seo",
"qbpageinfo"    => "Comhthéacs",
"qbmyoptions"   => "Mo chuid lgh",
"qbspecialpages" => "Lgh speisialta",
"moredotdotdot" => "Tuilleadh...",
"mypage"        => "Mo leathanach",
"mytalk"        => "Mo chuid phlé",
"anontalk"      => "Plé den IP seo",
"navigation"    => "Taiscéalaíocht",
"currentevents" => "Cursaí­ reatha",
'disclaimers' => 'Séanadh',
"disclaimerpage" => "{{ns:4}}:Séanadh_ginearálta",-
"errorpagetitle" => "Earráid",
"returnto"      => "Dul ar ais go $1.",
"fromwikipedia" => "Ón Vicipéid, an chiclipéid shaor.",
"whatlinkshere" => "Leathanaigh a nascaí­onn chuig an leathanach seo",
"help"      => "Cabhair",
"search"        => "Cuardaigh",
"go"            => "Dul",
"history"       => "Stair an lgh seo",
'history_short' => 'Stair',
'info_short'	=> 'Eolas',
"printableversion" => "Eagrán inphriontáilte",
"edit"          => "Athraigh",
"editthispage"  => "Athraigh an lch seo",
"delete"        => "Scrios",
"deletethispage"    => "Scrios an lch seo",
"undelete_short" => "Cuir ar ais $1 athruithe",
"protect"        => "Cuir faoi ghlas",
"protectthispage" => "Cuir glas ar an lch seo",
"unprotect"     =>  "Bain an glas",
"unprotectthispage" => "Bain an glas den lch seo",
"newpage"       => "Leathanach nua",
"talkpage"      => "Pléigh an lch seo",
'specialpage' => 'Leathanach Speisialta',
'personaltools' => 'Uirlisí pearsánta',
"postcomment"   => "Trácht ar an lch",
'addsection'   => '+',
"articlepage"   => "Féach ar an alt",
"subjectpage"   => "Féach ar an t-ábhar", # For compatibility
'talk' => 'Plé',
'toolbox' => 'Bosca uirlisí',
"userpage"      => "Féach ar lch úsáideora",
"wikipediapage" => "Féach ar meitea-lch",
"imagepage"     => "Féach ar lch í­omhá",
"viewtalkpage"  => "Féach ar phlé",
"otherlanguages"    => "Teangacha eile",
"redirectedfrom"    => "(Athsheolta ó $1)",
"lastmodified"  => "Athraí­odh an leathanach seo ag $1.",
"viewcount"     => "Rochtainí­odh an leathanach seo $1 uair.",
'copyright'	=> "Tá an t-ábhar le fáil faoin $1.",
'poweredby'	=> "Cumhachtaítear an Vicipéid le [http://www.mediawiki.org/ MediaWiki], inneall mearshuímh shaor-fhoinse.",
"gnunote"       => "Tá an téacs ar fad le fáil faoi na téarmaí­ an <a class=internal href='$wgScriptPath/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(Ó http://ga.wikipedia.org)",
"protectedpage" => "Lch faoi ghlas",
"administrators"    => "Vicipéid:Riarthóirí­",
"sysoptitle"    => "Tá cuntas ceannasaí­ riachtanach",
"sysoptext"     => "Ní mór duit bheith i do \"ceannasaí­\"
chun an gní­omh seo a dhéanamh.
Féach ar $1.",
"developertitle"    => "Tá cuntas chumadóra riachtanach",
"developertext" => "Ní mór duit bheith i do \"cumadóir\"
chun an gní­omh seo a dhéanamh.
Féach ar $1.",
'bureaucrattitle'	=> 'Tá cuntas maorlathaigh riachtanach',
"bureaucrattext"	=> "Ní mór duit bheith i do \"maorlathach\"
chun an gníomh seo a dhéanamh.",
"nbytes"        => "$1 ochtáin",
"go"            => "Dul",
"ok"            => "Déan",
"sitetitle"     => "Vicipéid",
'pagetitle'		=> "$1 - Vicipéid",
"sitesubtitle"  => "An Chiclipéid Shaor",
"retrievedfrom" => "Fuarthas ar ais ó \"$1\"",
"newmessages"   => "Tá $1 agat.",
"newmessageslink" => "teachtaireachtaí­ nua",
"editsection"   => "athraigh",
"toc"           => "Clár ábhair",
"showtoc"       => "taispeáin",
"hidetoc"       => "folaigh",
"thisisdeleted" => "Breathnaigh nó cuir ar ais $1?",
"restorelink"   => "$1 athruithe scriosaithe",
'feedlinks' => 'Sní eolais:',
'sitenotice'	=> '', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Alt',
'nstab-user' => 'Lch úsáideora',
'nstab-media' => 'Meán',
'nstab-special' => 'Speisialta',
'nstab-wp' => 'Faoi',
'nstab-image' => 'Íomhá',
'nstab-mediawiki' => 'Teachtaireacht',
'nstab-template' => 'Múnla',
'nstab-help' => 'Cabhair',
'nstab-category' => 'Rang',

# Main script and global functions
#
"nosuchaction"  => "Ní­l a leithéid de ghní­omh ann",
"nosuchactiontext" => "Ní­l aithní­onn an oideas Vicipéide
an gní­omh ('action') atá ann san lí­onsheoladh.",
"nosuchspecialpage" => "Ní­l a leithéid de leathanach speisialta ann",
"nospecialpagetext" => "Ní­l aithní­onn an oideas Vicipéide
an leathanach speisialta a d'iarr tú ar.",

# General errors
#
"error"         => "Earráid",
"databaseerror"     => "Earráid sa bunachar sonraí­",
"dberrortext"   => "Tharlaigh earráid chomhréir in órdú chuig an bhunachar sonraí­.
<blockquote><tt>$1</tt></blockquote>, ón suim \"<tt>$2</tt>\",
an órdú deireanach chuig an bhunachar sonrai.
Chuir MySQL an earráid seo ar ais: \"<tt>$3: $4</tt>\".",
"dberrortextcl"     => "Tharlaigh earráid chomhréir in órdú chuig an bhunachar sonraí­.
\"$1\", ón suim \"$2\",
ab ea an órdú fiosraithe deireanach chuig an bhunachar sonrai,
Chuir MySQL an earráid seo ar ais: \"$3: $4\".\n",
"noconnect"     => "Tá brón orainn! Chuaigh an oideas Wiki in abar teicniúil, agus theipeadh an nasc leis an mbunachar sonraí­ .",
"nodb"      => "Theipeadh an rogha den bhunachar sonraí­ $1",
"cachederror"   => "Seo í­ cóip athscrí­obhtha den leathanach a raibh tú ag lorg (is dócha go nach bhfuil sí­ bord ar bhord leis an eagrán reatha).",
"readonly"      => "Bunachar sonraí­ faoi ghlas",
"enterlockreason" => "Iontráil cúis don ghlas, agus meastachán
den cathain a mbainfear an ghlas de.",
"readonlytext"  => "Tá an bunachar sonraí­ Vicipéide faoi ghlas anois do iontráilí­ agus athruithe nua
(is dócha go bhfuil sé do gnáthchothabháil).
Tar éis seo, beidh an bunachar sonraí­ tofa ar ais.
Thug an riarthóir a ghlasaigh an mí­niú seo:
<p>$1",
"missingarticle" => "Chuardaigh an bunachar sonraí­ ar leathanach go mba chóir a bheith faighte, darbh ainm \"$1\". Ní­or bhfuarthas an leathanach.

<p>Ní­ earráid san bunachar sonraí­ é seo, ach b'fhéidir go bhfuair tú amach fabht
san oideasra MediaWiki. De ghnáth, tarlaí­onn sé sin nuair a leantar nasc staire nó difrí­ochta go leathanach a raibh scriosaithe cheana féin.

<p>Déan nóta den URL le do thoil, agus cuir an ábhar in iúl do riarthóir.",
"internalerror" => "Earráid inmhéanach",
"filecopyerror" => "Ní­ féidir an comhad \"$1\" a chóipeáil go \"$2\".",
"filerenameerror" => "Ní­ féidir an comhad \"$1\" a athainmnigh bheith \"$2\".",
"filedeleteerror" => "Ní­ féidir an comhad \"$1\" a scriosaigh amach.",
"filenotfound"  => "Ní­ bhfuarthas an comhad \"$1\".",
"unexpected"    => "Luach gan súil leis: \"$1\"=\"$2\".",
"formerror"     => "Earráid: ní­ féidir an foirm a tabhair isteach",
"badarticleerror" => "Ní­ féidir an gní­omh seo a dhéanamh ar an leathanach seo.",
"cannotdelete"  => "Ní­ féidir an leathanach nó í­omhá sonraithe a scriosaigh. (B'fhéidir go shcriosaigh duine eile é cheana féin.)",
"badtitle"      => "Teideal neamhbhailí­",
"badtitletext"  => "Bhí­ teideal an leanthanaigh a d'iarr tú ar neamhbhailí­, folamh, nó
teideal idirtheangach no idir-Wiki nasctha go mí­cheart.",
"perfdisabled" => "Tá brón orainnn! Mhí­chumasaí­odh an gné seo go sealadach chun luas an bunachair sonraí­ a chosaint.",
"perfdisabledsub" => "Seo cóip i dtaisce ó $1:",
"wrong_wfQuery_params" => "Paraiméadair mí­chearta don wfQuery()<br>
Feidhm: $1<br>
Órdú: $2
",
'perfcached' => 'Fuarthas na sonraí seo as dtaisce, agus is dócha nach bhfuil siad reatha:',
"viewsource" => "Féach ar fhoinse",
"protectedtext" => "Chuirtear ghlas ar an leathanach seo chun é a chosaint in aghaidh athruithe. Tá go leor
cúiseanna féideartha don scéal seo. Féach ar
[[$wgMetaNamespace:Leathanach faoi ghlas]] más é do thoil é.

Is féidir leat foinse an leathanaigh seo a féachaint agus a chóipeáil:",
'seriousxhtmlerrors' => 'Chonacthas earráidí tábhachtacha xhtml i rith an glanadh.',

# Login and logout pages
#
"logouttitle"   => "Logáil amach",
"logouttext" => "Tá tú logáilte amach anois.
Is féidir leat an Vicipéid a úsáid fós gan ainm, nó is féidir leat logáil isteach
arí­s mar an úsáideoir céanna, nó mar úsáideoir eile. Tabhair faoi deara go taispeáinfear roinnt
 leathanaigh mar atá tú logtha ann fós, go dtí­ go ghlanfá amach do taisce lí­onleitheora\n",

"welcomecreation" => "<h2>Tá fáilte romhat, a $1!</h2><p>Chruthaí­odh do chuntas.
Ná déan dearmad do sainroghanna phearsanta a athrú.",

"loginpagetitle" => "Logáil isteach",
"yourname"      => "D'ainm úsáideora",
"yourpassword"  => "D'fhocal faire",
"yourpasswordagain" => "Athiontráil d'fhocal faire",
"newusersonly"  => " (D'úsáideoirí­ úrnua amháin)",
"remembermypassword" => "Cuimhnigh m'fhocal faire.",
"loginproblem"  => "<b>Bhí­ fadhb leis an logáil isteach.</b><br>Déan iarracht eile!",
"alreadyloggedin" => "<font color=red><b>A húsáideoir $1, tá tú logáilte isteach cheana féin!</b></font><br>\n",

"areyounew"     => "Má tá tú i do núí­osach chuig an Vicipéid agus tá cuntas úsáideora ag teastáil uait,
iontráil ainm úsáideora, agus ansin iontráil agus athiontráil focal faire.
Níl an seoladh rí­omhphoist ach rud roghnach; dá scríobhfá d'fhocal faire, is feidir leat a iarradh
go seolfar é chuig an seoladh rí­omhphoist a thug tú.<br>\n",

"login"     => "Log ann",
'loginprompt'   => "Tá chomhaid aithintáin (<i>cookies</i>) riachtanach chun logáil isteach san {{SITENAME}} a dhéanamh.",
"userlogin"     => "Logáil isteach",
"logout"        => "Log as",
"userlogout"    => "Logáil amach",
"notloggedin"   => "Ní­l tú logáilte isteach",
"createaccount" => "Cruthaigh cuntas nua",
"createaccountmail" => "le rí­omhphost",
"badretype"     => "D'iontráil tú dhá fhocal faire difriúla.",
"userexists"    => "Tá an ainm úsáideora a d'iontráil tú in úsáid cheana féin. Déan rogha d'ainm eile, más é do thoil é.",
"youremail"     => "Do rí­omhphost*",
'yourrealname'  => "D'ainm ceart*",
"yournick"      => "Do leasainm (do sí­niúithe)",
"emailforlost"  => "* Níl na boscaí le réalt (*) ach roghnach.  Le seoladh ríomhphoist i dtaisce, ba féidir le daoine teagmhail a dhéanamh leat
trí­d an suí­omh gan do sheoladh rí­omhphoist a nochtaigh dóibh. Ina theannta sin,
is cabhair é má dheanfá dearmad ar d'fhocal faire.<br /><br />Má toghaíonn tú d'ainm ceart a chur isteach, úsáidfear é chun do chuid dreachtaí a chur i leith tusa",
'prefs-help-userdata' => '* <strong>Ainm ceart</strong> (roghnach): má toghaíonn tú é sin a chur ar fáil, úsáidfear é chun do chuid dreachtaí a chur i leith tusa.<br/>
* <strong>Ríomhphost</strong> (roghnach): Ba féidir le daoine teagmháil a dhéanamh leat gan do sheoladh ríomhphoist
a thaispeáint dóibh, agus ba féidir focal faire nua a sheol chugat má dhéanfá dearmad air.',
"loginerror"    => "Earráid leis an logáil isteach",
'nocookiesnew'	=> "Chruthaíodh an cuntas úsáideora, ach níl tú logáilte isteach. Úsáideann an {{SITENAME}} comhaid aithintáin (<i>cookies</i>) chun úsáideoirí a logáil isteach. Tá comhaid aithintáin míchumasaithe agat. Cumasaigh iad le do thoil, agus ansin logáil isteach le d'ainm úsáideora agus d'fhocal faire úrnua.",
"nocookieslogin"	=> "Úsáideann an {{SITENAME}} comhaid aithintáin (<i>cookies</i>) chun úsáideoirí a logáil isteach. Tá comhaid aithintáin míchumasaithe agat. Cumasaigh iad agus déan athiarracht, le do thoil.",
"noname"        => "Ní­ shonraigh tú ainm úsáideora bailí­.",
"loginsuccesstitle" => "Logáil isteach rathúil",
"loginsuccess"  => "Tá tú logáilte isteach anois sa Vicipéid mar \"$1\".",
"nosuchuser"    => "Ní­l aon úsáideoir ann leis an ainm \"$1\".
Cinntigh do litriú, nó bain úsáid as an foirm thí­os chun cuntas úsáideora nua a chruthú.",
"wrongpassword" => "Focal faire mí­cheart ab ea é a d'iontráil tú. Déan iarracht eile le do thoil.",
"mailmypassword" => "Seol focal faire nua chugam",
"passwordremindertitle" => "Cuimhneachán focail faire ón Vicipéid",
"passwordremindertext" => "D'iarr duine éigin (tusa de réir cosúlachta, ón seoladh IP $1)
go sheolfaimis focal faire Vicipéide nua logála isteach chugat.
Is é an focal faire don úsáideoir \"$2\" ná \"$3\" anois.
Ba chóir duit logáil isteach anois agus d'focal faire a athrú.",
"noemail"       => "Ní­l aon seoladh rí­omhphoist i gcuntas don úsáideoir \"$1\".",
"passwordsent"  => "Cuireadh focal faire nua chuig an seoladh rí­omhphoist cláraithe do \"$1\".
Agus atá sé agat, logáil isteach arí­s leis le do thoil.",
'loginend'		=> '&nbsp;',
'mailerror' => "Tharlaigh earráid leis an seoladh: $1",
'acct_creation_throttle_hit' => 'Tá brón orainn, ach tá tú tar éis ag chruthú $1 cuntas. Ní féidir lead níos mó a dhéanamh.',

# Edit page toolbar
'bold_sample'=>'Cló dubh',
'bold_tip'=>'Cló dubh',
'italic_sample'=>'Cló Iodáileach',
'italic_tip'=>'Cló Iodáileach',
'link_sample'=>'Ainm naisc',
'link_tip'=>'Nasc inmhéanach',
'extlink_sample'=>'http://www.sampla.com ainm naisc',
'extlink_tip'=>'Nasc seachtrach (cuimhnigh an réimír http://)',
'headline_sample'=>'Cló ceannlíne',
'headline_tip'=>'Ceannlíne Leibhéil 2',
'math_sample'=>'Cuir foirmle isteach anseo',
'math_tip'=>'Foirmle matamataice (LaTeX)',
'nowiki_sample'=>'Cuir téacs neamh-fhormáide anseo',
'nowiki_tip'=>'Scaoil thar ar fhormáid mearshuímh',
'image_sample'=>'Sámpla.jpg',
'image_tip'=>'Íomhá leabaithe',
'media_sample'=>'Sámpla.mp3',
'media_tip'=>'Nasc chuig comhad meáin',
'sig_tip'=>'Do shíniú le stampa ama',
'hr_tip'=>'Líne cothrománach (inúsáidte go coigilteach)',
'infobox'=>'Roghnaigh cnaipe chun téacs sámpla a fháil',
# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror
'infobox_alert'=>"Cuir isteach an téacs ba mhaith leat a fhormáidigh.\\n Taispeánfar é san bosca eolais chun gearr agus greamaigh a dhéanamh.\\nSámpla:\\nTaispeánfar\\n$1\\nmar sin:\\n$2",

# Edit pages
#
"summary"       => "Achoimriú",
"subject"       => "Ábhar/ceannlí­ne",
"minoredit"     => "Is mionathrú é seo",
"watchthis"     => "Déan faire ar an lch seo",
"savearticle"   => "Cuir an lch i dtaisce",
"preview"       => "Reamhspéachadh",
"showpreview"   => "Reamhspléach",
"blockedtitle"  => "Tá an úsáideoir seo faoi chosc",
"blockedtext"   => "Chuir $1 cosc ar d'ainm úsáideora nó do seoladh IP.
Seo é an cúis a thugadh:<br>''$2''<p>Is féidir leat teagmháil a dhéanamh le $1 nó le ceann eile de na
[[$wgMetaNamespace:Riarthóirí­|riarthóirí­]] chun an cosc a phléigh.

Tabhair faoi deara nach bhfuil cead agat an gné \"cuir rí­omhphost chuig an úsáideoir seo\" a úsáid
mura bhfuil seoladh rí­omhphoist bailí­ cláraithe i do [[Speisialta:Preferences|shainroghanna úsáideora]].

Is é $3 do sheoladh IP. Más é do thoil é, déan tagairt den seoladh seo le gach ceist a chuirfeá.
",
"whitelistedittitle" => "Logáil isteach chun athrú a dhéanamh",
"whitelistedittext" => "Ní mór duit [[Speisialta:Userlogin|logáil isteach]] chun ailt a athrú.",
"whitelistreadtitle" => "Logáil isteach chun ailt a léamh",
"whitelistreadtext" => "Ní mór duit [[Speisialta:Userlogin|logáil isteach]] chun ailt a léigh.",
"whitelistacctitle" => "Ní­l cead agat cuntas a chruthaigh",
"whitelistacctext" => "Chun cuntais nua a chruthaigh san Wiki seo caithfidh tú [[Speisialta:Userlogin|logáil isteach]] agus caithfidh bheith an cead riachtanach agat.",
'loginreqtitle'	=> 'Tá logáil isteach riachtanach',
'loginreqtext'	=> 'Ní mór duit logáil isteach chun leathanaigh eila a fhéiceáil.',
"accmailtitle" => "Seoladh an focal faire.",
"accmailtext" => "Seoladh an focal faire don úsáideoir '$1' chuig $2.",
"newarticle"    => "(Nua)",
"newarticletext" =>
"Lean tú nasc chuig leathanach a nach bhfuil ann fós.
Chun an leathanach a chruthú, tosaigh ag clóscrí­obh san bosca anseo thí­os
(féach ar an [[Vicipéid:Cabhair|leathanach cabhrach]] chun a thuilleadh eolas a fháil).
Má tháinig tú anseo as dearmad, brúigh an cnaipe '''ar ais''' ar do lí­onléitheoir.",
'talkpagetext' => '<!-- MediaWiki:teacsphle -->',
"anontalkpagetext" => "---- ''Seo é an leathanach plé do úsáideoir gan ainm a nach chruthaigh
cuntas fós nó a nach úsáideann a chuntas. Dá bhrí­ sin caithfimid an [[seoladh IP]] uimhriúil
chun é/í­ a ionannaigh. Is féidir cuid mhaith úsáideoirí­ an seoladh IP céanna a úsáid. Má tá tú
i do úsáideoir gan ainm agus má tá sé do thuairim go rinneadh léiriuithe neamhfheidhmeacha fút,
[[Special:Userlogin|cruthaigh cuntas nó logáil isteach]] le do thoil chun mearbhall le húsáideoirí­ eile
gan ainmneacha a héalaigh amach anseo.'' ",
"noarticletext" => "(Ní­l aon téacs ar an leathanach seo faoi láthair)",
'clearyourcache' => "'''Tabhair faoi deara:''' Tar éis duit ábhar a shábháil, ní mór duit taisce do líonléitheora chun na hathruithe a fheiceáil After saving, you have to clear your browser cache to see the changes: '''Mozilla / Netscape:''' roghnaigh ''Athlódáil'' (nó ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'usercssjsyoucanpreview' => "<strong>Leid:</strong> Sula sábhálaím tú, úsáid an cnaipe 'Reamhspléach' chun do CSS/JS nua a tástáil.",
'usercsspreview' => "'''Cuimhnigh nach bhfuil seo ach reamhspléachadh do CSS úsáideora - níor sábháladh é go fóill!'''",
'userjspreview' => "'''Cuimhnigh nach bhfuil seo ach reamhspléachadh do JavaScript úsáideora - níor sábháladh é go fóill!'''",

"updated"       => "(Leasaithe)",
"note"          => "<strong>Tabhair faoi deara:</strong> ",
"previewnote"   => "Tabhair faoi deara go nach bhfuil seo ach reamhspléachadh, agus go nach sábháladh é fós!",
"previewconflict" => "San reamhspléachadh seo, feachann tú an téacs dé réir an eagarbhosca
thuas mar a taispeáinfear é má sábháilfear é.",
"editing"       => "Ag athrú $1",
"sectionedit"   => " (roinnt)",
"commentedit"   => " (trácht)",
"editconflict"  => "Coimhlint athraithe: $1",
"explainconflict" => "D'athraigh duine eile an leathanach seo ó shin a thosaigh tú ag cur é in eagar.
San bhosca seo thuas feiceann tú téacs an leathanaigh mar a bhfuil sé faoi láthair.
Tá do chuid athruithe san bhosca thí­os.
Caithfidh tú do chuid athruithe a chumasadh leis an eagrán reatha.
Nuair a brúann tú ar an cnaipe \"Sábháil an leathanach\", ní­ sábhálfar <b>ach amháin</b> an téacs san bhosca thuas.\n<p>",
"yourtext"      => "Do chuid téacs",
"storedversion" => "Eagrán sábháilte",
"editingold"    => "<strong>AIRE: Tá tú ag athrú eagrán an leathanaigh atá as dáta.
Dá shábhálfá é, caillfear aon athrú a rinneadh ó shin an eagrán seo.</strong>\n",
"yourdiff"      => "Difrí­ochtaí­",
"copyrightwarning" => "Tabhair faoi deara go scaoilí­tear gach dréacht chuig an Vicipéid maidir lena tearmaí­ an <i>GNU Free Documentation License</i>
(tuilleadh eolais ag $1).
Muna aontaíonn tú go cuirfear do chuid scrí­bhinn in eagar go héadrócaireach agus go athdálfar é gan teorainn,
ná tabhair isteach é anseo.<br>
Ina theannta sin, geallann tú duinn go scrí­obh tusa féin an rud seo, nó go chóipeáil tú é as
fhoinse atá gan chóipcheart.
<strong>NÁ TABHAIR ISTEACH OBAIR ATÁ FAOI CHÓIPCHEART GAN CEAD!</strong>",
"longpagewarning" => "AIRE: Tá an leathanach seo $1 cilibhearta i bhfad; ní­ féidir le roinnt lí­onléitheoirí­
leathanaigh atá breis agus nó ní­os fada ná 32kb a athrú.
Más féidir, giotaigh an leathanach i gcodanna níos bige.",
"readonlywarning" => "AIRE: Cuireadh glas ar an mbunachar sonraí­, agus mar sin
ní­ féidir leat do chuid athruithe a shábháil dí­reach anois. B'fhéidir gur mhaith leat an téacs a ghearr is greamaigh i gcomhad téacs agus é a úsáid níos déanaí.",
"protectedpagewarning" => "AIRE:  Cuireadh glas ar an leathanach seo, agus ní féidir le duine ar bith é a athrú
ach amhaín na húsáideoirí le pribhléidí­ ceannasaí­. Bí­ cinnte go leanann tú na
<a href='$wgScriptPath/$wgMetaNamespace:Treoirlí­nte_do_leathanaigh_glasáilte'>treoirlí­nte do leathanaigh glasáilte</a>.",

# History pages
#
"revhistory"    => "Stáir athraithe",
"nohistory"     => "Ní­l aon stáir athraithe ag an leathanach seo.",
"revnotfound"   => "Ní­ bhfuarthas an athrú",
"revnotfoundtext" => "Ní­ bhfuarthas seaneagrán an leathanaigh a d'iarr tú ar.
Cinntigh an URL a d'úsáid tú chun an leathanach seo a rochtain.\n",
"loadhist"      => "Ag lódáil stáir an leathanaigh",
"currentrev"    => "Eagrán reatha",
"revisionasof"  => "Eagrán ó $1",
"cur"           => "rth",
"next"          => "lns",
"last"          => "rmh",
"orig"          => "bun",
'histlegend'	=> "Difríochtaí a roghnú: marcáil na boscaí de na eagráin atá ag teastail uait á cuir i gcomparáid, agus brúigh Iontráil nó an cnaipe ag bun an leathanaigh.<br/>
Eochair: (rth) = difrí­ocht leis an eagrán reatha,
(rmh) = difrí­ocht leis an eagrán roimhe, M = mionathrú",

# Diffs
#
"difference"    => "(Difrí­ochtaí­ idir eagráin)",
"loadingrev"    => "ag lódáil eagrán don difrí­ocht",
"lineno"        => "Lí­ne $1:",
"editcurrent"   => "Athraigh eagrán reatha an leathanaigh seo",
'selectnewerversionfordiff' => 'Roghnaigh eagrán níos nuaí do comparáid',
'selectolderversionfordiff' => 'Roghnaigh eagrán níos sine do comparáid',
'compareselectedversions' => 'Cuir i gcomparáid na heagráin atá roghnaithe',


# Search results
#
"searchresults" => "Toraidh an cuardaigh",
"searchresulttext" => "Féach ar [[Vicipéid:Cuardach|Ag cuardach sa Vicipéid]] chun a thuilleadh eolais a fháil faoi chuardach Vicipéide.",
"searchquery"   => "Don órdú \"$1\"",
"badquery"      => "Órdú fiosraithe neamhbhailí­",
"badquerytext"  => "Nior éirigh linn d'órdú a phróiseáil.
Is dócha go rinne tú cuardach ar focal le ní­os lú ná trí­ litir,
gné a nach bhfuil le tacaí­ocht aige fós.
B'fhéidir freisin go mhí­chlóshcrí­obh tú an leagan, mar shampla
\"éisc agus agus lanna\". Déan athiarracht.",
"matchtotals"   => "Bhí­ an cheist \"$1\" ina mhacasamhail le $2 ainmneacha alt
agus leis an téacs i $3 ailt.",
"nogomatch" => "Ní­l aon leathanach leis an ainm áirithe seo. Déantar cuardach an téacs ar fad...",
"titlematches"  => "Tá macasamhla ainm alt ann",
"notitlematches" => "Ní­l macasamhla ainm alt ann",
"textmatches"   => "Tá macasamhla téacs alt ann",
"notextmatches" => "Ní­l macasamhla téacs alt ann",
"prevn"         => "na $1 roimhe",
"nextn"         => "an chéad $1 eile",
"viewprevnext"  => "Taispeáin ($1) ($2) ($3).",
"showingresults" => "Ag taispeáint thí­os <b>$1</b> toraidh, ag tosachh le #<b>$2</b>.",
"showingresultsnum" => "Ag taispeáint thí­os <b>$3</b> toraidh, ag tosach le #<b>$2</b>.",
"nonefound"     => "<strong>Tabhair faoi deara</strong>: go minic, ní éiríonn cuardaigh nuair a cuardaí­tear focail an-coiteanta, m.sh., \"ag\" is \"an\",
a nach bhfuil innéacsaí­tear, nó nuair a ceisteann tú ní­os mó ná téarma amháin (ní­
taispeáintear sna toraidh ach na leathanaigh ina bhfuil go leoir na téarmaí­ cuardaigh).",
"powersearch" => "Cuardaigh",
"powersearchtext" => "
Cuardaigh sna hainmranna :<br>
$1<br>
$2 Cuir athsheolaidh in áireamh &nbsp; Cuardaigh ar $3 $9",
"searchdisabled" => "<p>Tá brón orainn! Mhí­chumasaí­odh an cuardach téacs iomlán go sealadach chun luas an suí­mh
a chosaint. Idir an dá linn, is féidir leat an cuardach Google anseo thí­os a úsáid - b'fhéidir go bhfuil sé as dáta.</p>",

"googlesearch"  => "
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
"blanknamespace" => "(Gnáth)",

# Preferences page
#
"preferences"   => "Sainroghanna",
"prefsnologin" => "Ní­l tú logáilte isteach",
"prefsnologintext"  => "Ní mór duit bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logáilte isteach</a>
chun do chuid sainroghanna phearsanta a athrú.",
"prefslogintext" => "Tá tú logáilte isteach mar \"$1\".
Is é $2 d'uimhir aitheantais inmhéanach.

Féach ar [[{{ns:4}}:Cabhair do sainroghanna úsáideora]] chun cabhair a fháil mar gheall ar na roghanna.",
 "prefsreset"    => "D'athraí­odh do chuid sainroghanna ar ais chuig an leagan bunúsach ón stóras.",
"qbsettings"    => "Sainroghanna an bosca uirlisí­",
'qbsettingsnote'	=> 'Ní oibríonn an sainrogha seo ach sna cumaí \'Gnáth\' agus \'Gorm Colóin\'.',
"changepassword" => "Athraigh d'fhocal faire",
"skin"          => "Cuma",
"math"          => "Ag aistriú an matamaitic",
"dateformat"    => "Formáid dáta",
"math_failure"      => "Theipeadh anailí­s an foirmle",
"math_unknown_error"    => "earráid anaithnid",
"math_unknown_function" => "foirmle anaithnid ",
"math_lexing_error" => "Theipeadh anailí­s an foclóra",
"math_syntax_error" => "earráid comhréire",
'math_image_error'	=> 'Theipeadh aistriú an PNG; tástáil má tá na ríomh-oidis latex, dvips, gs, agus convert i suite go maith.',
'math_bad_tmpdir'	=> 'Ní féidir scríobh chuig an fillteán mata sealadach, nó é a chruthú',
'math_bad_output'	=> 'Ní féidir scríobh chuig an fillteán mata aschomhaid, nó é a chruthú',
'math_notexvc'	=> 'Níl an oideas texvc ann; féach ar mata/EOLAIS chun é a sainathrú.',
'prefs-personal' => 'Sonraí úsáideora',
'prefs-rc' => 'Athruithe le déanaí agus taispeántas stumpaí',
'prefs-misc' => 'Sainroghanna éagsúla',
"saveprefs"     => "Sábháil sainroghanna",
"resetprefs"    => "Athshuigh sainroghanna",
"oldpassword"   => "Seanfhocal faire",
"newpassword"   => "Nuafhocal faire",
"retypenew"     => "Athchlóshcrí­obh an nuafhocal faire",
"textboxsize"   => "Eagarthóireacht",
"rows"          => "Sraitheanna",
"columns"       => "Colúin",
"searchresultshead" => "Sainroghanna do toraidh cuardaigh",
"resultsperpage" => "Cuairt le taispeáint ar gach leathanach",
"contextlines"  => "Lí­nte le taispeáint do gach cuairt",
"contextchars"  => "Litreacha chomhthéacs ar gach líne",
"stubthreshold" => "Tairseach do taispeántas stumpaí",
"recentchangescount" => "Méid ainmneacha sna hathruithe le déanaí",
"savedprefs"    => "Sábháladh do chuid socruithe.",
'timezonelegend' => 'Crios ama',
"timezonetext"  => "Iontráil an méid uaireanta a difrí­onn do am áitiúil
den am an innill friothála­ (UTC).",
"localtime" => "An t-am áitiúil",
"timezoneoffset" => "Difear",
"servertime"    => "Am an innill friothála anois",
"guesstimezone" => "Líon ón lí­onléitheoir",
"emailflag"     => "Coisc rí­omhphost a sheolann úsáideoirí­ eile",
"defaultns"     => "Cuardaigh sna ranna seo a los éagmaise:",

# Recent changes
#
"changes" => "athruithe",
"recentchanges" => "Athruithe is déanaí",
"recentchangestext" => "Ar an leathanach seo, déan fáire ar na hathruithe is déanaí sa vicí.",
"rcloaderr"     => "Ag lódáil na athruithe is déanaí",
"rcnote"        => "Is iad seo a leanas na <strong>$1</strong> athruithe deireanacha sna <strong>$2</strong> lae seo caite.",
"rcnotefrom"    => "Is iad seo a leanas na athruithe ó <b>$2</b> (go dti <b>$1</b> taispeánaithe).",
"rclistfrom"    => "Taispeáin nua-athruithe ó $1 anuas",
# "rclinks"     => "Taispeáin na $1 athruithe is déanaí sna $2 uaire seo caite / $3 laethanta seo caite.",
# "rclinks"     => "Taispeáin na $1 athruithe is déanaí sna $2 laethanta seo caite.",
"rclinks"       => "Taispeáin na $1 athruithe is déanaí sna $2 laethanta seo caite; $3 mionathruithe",
"rchide"        => "sa cuma $4; $1 mionathruithe; $2 fo-ainmranna; $3 athruithe ilchodacha.",
"rcliu"         => "; $1 athruithe de úsáideoirí­ atá logáilte isteach",
"diff"          => "difrí­ochtaí­",
"hist"          => "stáir",
"hide"          => "folaigh",
"show"          => "taispeán",
"tableform"     => "tábla",
"listform"      => "liosta",
"nchanges"      => "$1 athruithe",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"        => "Uaslódáil comhad",
"uploadbtn"     => "Uaslódáil comhad",
"uploadlink"    => "Uaslódáil í­omhánna",
"reupload"      => "Athuaslódáil",
"reuploaddesc"  => "Dul ar ais chuig an fhoirm uaslódála.",
"uploadnologin" => "Nil tú logáilte isteach",
"uploadnologintext" => "Caithfifh tú bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logáilte isteach</a>
chun comhaid a uaslódáil.",
"uploadfile"    => "Uaslódáil í­omhánna, fuaimeanna, doiciméid srl.",
"uploaderror"   => "Earráid uaslódála",
"uploadtext"    => "'''STOP!''' Roimh a uaslódálaíonn tú rud ar bith anseo,
bí­ cinnte leigh agus géill don
[[Project:Polasaí­_úsáide_í­omhá|polasaí­ úsáide í­omhá]] atá ag an Vicipéid.

Má bhfuil aon comhad i dtaisce fós leis an ainm céanna a bhfuil tú ag
tabhairt don comhad nua, cuirfear an nuachomhad in ionad an seanchomhad gan fógra.
Mar sin, mura nuashonraí­onn tú comhad éigin, is scéal maith é cinntigh má bhfuil comhad
leis an ainm seo ann fós.

Dul go dti an[[Speisialta:Imagelist|liosta í­omhánna]]chun féach ar nó chuardaigh idir í­omhánna atá uaslódáilte roimhe seo.
Déantar liosta de uaslódála agus scriosaidh ar an
[[Project:Liosta_uaslódála|liosta uaslódála]].

Bain úsáid as an fhoirm anseo thí­os chun í­omháchomhaid nua a chaith in airde.
Ba féidir leat na í­omhánna a úsáid i do chuid ailt.
Ar an chuid is mó de lí­onléitheoirí­, feicfidh tú cnaipe \"siortaigh...\" no mar sin. Lé brú ar an cnaipe seo,
gheobhaigh tú an gháthbhosca agallaimh comhadtheachta do [[córas dubhshraithe|chórais dubhshraithe]].
Nuair a luí­onn tú comhad, lí­onfar ainm an comhaid san téacsbhosca in aice leis an cnaipe.
Ní mór duit a admháil le brú san bosca beag nach
bhfuil tú ag sáraigh aon chóipcheart leis an caitheamh in airde seo.
Brúigh an cnaipe \"Uaslódáil\" chun an caitheamh in airde a chrí­ochnaigh.
Mura bhfuil nasc idirlí­n tapaidh agat, beidh roinnt ama uait chun an rud sin a dhéanamh.

Is iad na formáide inmholta ná JPEG do í­omhánna grianghrafacha, PNG
do pictiúir tarraingthe agus léaráide, agus [[OGG]] do fuaimeanna.
Ainmnigh do comhaid go tuairisciúil chun mearbhall a héalú.
Chun an í­omhá a úsáid san alt, úsáid nasc mar sin:
'''<nowiki>[[í­omhá:comhad.jpg]]</nowiki>''' nó '''<nowiki>[[image:í­omhá.png|téacs eile]]</nowiki>'''
nó '''<nowiki>[[meán:comhad.ogg]]</nowiki>''' do fuaimeanna.

Tabhair faoi deara go, cosúil le leathanaigh Vicipéide, is féidir le daoine eile do suaslódálacha a
athraigh nó a scriosadh amach, má sí­ltear go bhfuil sé i gcabhair
don ciclipéid, agus má bhainfeá mí­-úsáid as an córas tá seans go coiscfí­ tú ón gcóras.",

"uploadlog"     => "liosta suaslódála",
"uploadlogpage" => "Liosta_suaslódála",
"uploadlogpagetext" => "Is liosta é seo a leanas de na suaslódálacha comhad is deireanacha.
Is am an friothálaí­ (UTC) iad na hamanna atá anseo thí­os.
<ul>
</ul>
",
"filename"      => "Ainm comhaid",
"filedesc"      => "Achoimriú",
"filestatus" => "Stádas cóipchirt",
"filesource" => "Foinse",
"affirmation"   => "Dearbhaí­m go aontaí­onn coimeádaí­ cóipchirt an comhaid seo
é a ceadúnaigh de réir na téarmaí­ an $1.",
"copyrightpage" => "Vicipéid:Cóipchearta",
"copyrightpagename" => "Cóipcheart Vicipéide",
"uploadedfiles" => "Comhaid suaslódálaithe",
"noaffirmation" => "Ní mór duit a dearbhaigh nach sáraí­onn do suaslódáil
aon cóipchearta.",
"ignorewarning" => "Scaoil tharat an rabhadh agus sábháil an comhad ar aon chaoi.",
"minlength"     => "Caithfidh trí­ litreacha ar a laghad bheith ann sa ainm í­omhá.",
'illegalfilename'	=> 'Tá litreacha san comhadainm  "$1" a nach ceadaítear in ainm leathanaigh. Athainmnigh an comhad agus déan athiarracht, más é do thoil é.',
"badfilename"   => "D'athraí­odh an ainm í­omhá go \"$1\".",
"badfiletype"   => "Ní­l \".$1\" ina formáid comhaid í­omhá inmholta.",
"largefile"     => "Moltar nach uaslódálaítear comhaid í­omhá thar 100kb i méid.",
'emptyfile'		=> "De réir cuma, ní aon rud san chomhad a d'uaslódáil tú ach comhad folamh. Is dócha gur míchruinneas é seo san ainm chomhaid. Seiceáil más é an comhad seo atá le huaslódáil agat.",
"successfulupload" => "Uaslódáil rathúil",
"fileuploaded"  => "Uaslódáladh an comhad \"$1\" go rathúil.
Lean an nasc seo: ($2) chuig an leathanach cuir sios agus lí­on isteach
eolas faoin comhad, mar shampla cá bhfuarthas é, cathain a
chruthaí­odh é agus rud eile ar bith tá 'fhios agat faoi.",
"uploadwarning" => "Rabhadh suaslódála",
"savefile"      => "Sábháil comhad",
"uploadedimage" => "D'uaslódáladh \"$1\"",

# Image list
#
"imagelist"     => "Liosta í­omhánna",
"imagelisttext" => "Is liosta é seo a leanas de $1 í­omhánna, curtha in eagar le $2.",
"getimagelist"  => "ag fáil an liosta í­omhánna",
"ilshowmatch"   => "Taispeáin na í­omhánna le ainmneacha maith go léir",
"ilsubmit"      => "Cuardaigh",
"showlast"      => "Taispeáin na $1 í­omhánna seo caite, curtha in eagar le $2.",
"all"           => "go léir",
"byname"        => "de réir hainm",
"bydate"        => "de réir dáta",
"bysize"        => "de réir méid",
"imgdelete"     => "scrios",
"imgdesc"       => "cur",
"imglegend"     => "Eochair: (cur) = taispeáin/athraigh cur sí­os an í­omhá.",
"imghistory"    => "Stair an í­omhá",
"revertimg"     => "ath",
"deleteimg"     => "scr",
"deleteimgcompletely"     => "scr",
"imghistlegend" => "Eochair: (rth) = seo é an eagrán reatha, (scr) = scrios an
sean-eagrán seo, (ath) = athúsáid an sean-eagrán seo.
<br><i>Bruigh an dáta chun feach ar an í­omhá mar a suaslódálaí­odh é ar an dáta sin</i>.",
"imagelinks"    => "Naisc í­omhá",
"linkstoimage"  => "Is iad na leathanaigh seo a leanas a nascaí­onn chuig an í­omhá seo:",
"nolinkstoimage" => "Ní­l aon leathanach ann a nascaí­onn chuig an í­omhá seo.",

# Statistics
#
"statistics"    => "Staitistic",
"sitestats"     => "Staitistic suí­mh",
"userstats"     => "Staitistic úsáideora",
"sitestatstext" => "Is é '''$1''' an méid leathanach in iomlán sa bhunachar sonraí­.
Cuirtear san áireamh \"plé\"-leathanaigh, leathanaigh faoin Vicipéid, ailt \"stumpaí­\"
í­osmhéadacha, athsheolaidh, agus leathanaigh eile a nach cáileann mar ailt.
Ag fágáil na leathanaigh seo as, tá '''$2''' leathanaigh ann atá ailt dlisteanacha, is dócha.

In iomlán bhí­ '''$3''' radhairc leathanaigh, agus ''''$4''' athruithe leathanaigh
ó thus athchóiriú an vicí (25 Eanáir, 2004).
Sin '''$5''' athruithe ar meán do gach leathanach, agus '''$6''' radhairc do gach athrú.",
"userstatstext" => "Tá '''$1''' úsáideoirí­ cláraithe ann.
Is iad '''$2''' de na úsáideoirí­ seo ina riarthóirí­ (féach ar $3).",

# Maintenance Page
#
"maintenance"       => "Leathanach coinneála",
"maintnancepagetext"    => "Sa leathanach seo faightear uirlisí­ éagsúla don gnáthchoinneáil. Is féidir le roinnt
de na feidhmeanna seo an bunachar sonraí­ a cuir strus ar, mar sin ná athbhruigh athlódáil tar éis gach mí­r a
chrí­ochnaí­onn tú ;-)",
"maintenancebacklink"   => "Ar ais go Leathanach Coinneála",
"disambiguations"   => "Leathanaigh easathbhrí­ochais",
"disambiguationspage"   => "Vicipéid:Naisc_go_leathanaigh_easathbhrí­ochais",
"disambiguationstext"   => "Nascaí­onn na ailt seo a leanas go <i>leathanach easathbhrí­ochais</i>. Ba chóir dóibh nasc a
dhéanamh leis an ábhar oiriúnach ina áit.<br>Tugtar an teideal easathbhrí­ochais ar leathanach má bhfuil násc aige
ó $1.<br><i>Ní­</i> cuirtear naisc ó ranna eile ar an liosta seo.",
"doubleredirects"   => "Athsheolaidh Dúbailte",
"doubleredirectstext"   => "<b>Tabhair faoi deara:</b> B'fheidir go bhfuil toraidh bréagacha ar an liosta seo.
De ghnáth cí­allaí­onn sé sin go bhfuil téacs breise le naisc thí­os sa chéad #REDIRECT no #ATHSHEOLADH.<br>\n Sa gach
sraith tá náisc chuig an chéad is an dara athsheoladh, chomh maith le chéad lí­ne an dara téacs athsheolaidh. De ghnáth
tugann sé sin an sprioc-alt \"fí­or\".",
"brokenredirects"   => "Athsheolaidh Briste",
"brokenredirectstext"   => "Is iad na athsheolaidh seo a leanas a nascaí­onn go ailt a nach bhfuil ann.",
"selflinks"     => "Leathanaigh le féin-naisc",
"selflinkstext"     => "Sna leathanaigh seo a leanas tá naisc a nascaí­onn chuig an leathanach céanna é fhéin. Seo é flúirseach.",
"mispeelings"           => "Leathanaigh mí­litrithe",
"mispeelingstext"               => "Sna leathanaigh seo a leanas tá mí­litriú coiteanta, atá san liosta ar $1. Is dócha go taispeántar an litriú ceart (mar sin).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Naisc Teangacha Ar Iarraidh",
"missinglanguagelinksbutton"    => "Cuardaigh ar naisc teangacha ar iarraidh do",
"missinglanguagelinkstext"      => "<i>Ní­</i> nascaí­onn na ailt seo chuig a macasamhail sa $1. <i>Ní­</i> taispeántar athsheolaidh nó fo-leathanaigh.",


# Miscellaneous special pages
#
"orphans"       => "Leathanaigh dí­lleachtacha",
'geo'		=> 'Comhardanáidí GEO',
'validate'		=> 'Cuir an leathanach i bhailí',
"lonelypages"   => "Leathanaigh dí­lleachtacha",
'uncategorizedpages'	=> 'Leathanaigh gan rang',
"unusedimages"  => "Íomhánna tréigthe",
"popularpages"  => "Leathanaigh coitianta",
"nviews"        => "$1 radhairc",
"wantedpages"   => "Leathanaigh de dhí­th",
"nlinks"        => "$1 naisc",
"allpages"      => "Na leathanaigh go léir",
'nextpage'		=> 'An lch a leanas ($1)',
"randompage"    => "Leathanach fánach",
"shortpages"    => "Leathanaigh gearra",
"longpages"     => "Leathanaigh fada",
'deadendpages'  => 'Dead-end pages',
"listusers"     => "Liosta úsáideoirí­",
'listadmins'	=> 'Liosta riarthóirí',
"specialpages"  => "Lgh speisialta",
"spheading"     => "Lgh speisialta do gach úsáideoir",
"sysopspheading" => "Ach amháin do ceannasaithe",
"developerspheading" => "Ach amháin do cumadoirí­",
"protectpage"   => "Cuir glas ar leathanach",
"recentchangeslinked" => "Athruithe gaolmhara",
"rclsub"        => "(go leathanaigh nasctha ó \"$1\")",
"debug"         => "Bain fabhtanna",
"newpages"      => "Leathanaigh nua",
"ancientpages"      => "Na leathanaigh is sine",
"intl"      => "Naisc idirtheangacha",
'move' => 'Athainmnigh',
"movethispage"  => "Athainmnigh an leathanach seo",
"unusedimagestext" => "<p>Tabhair faoi deara go féidir le lí­onshuí­mh
eile, m.sh. na Vicipéidí­ eile, naisc a dhéanamh le í­omha le URL dí­reach,
agus mar sin beidh siad ar an liosta seo fós cé go bhfuil an í­omhá
in úsáid anois.",
"booksources"   => "Díoltóirí­ leabhar",
"booksourcetext" => "Seo é liosta anseo thí­os chuig suí­mh eile a
dí­olann leabhair nua agus athdhí­olta, agus tá seans go bhfuil eolas
breise acu faoina leabhair a bhfuil tú ag tnuth leis.
Ní­l aon baint ag Vicipéid le gnó ar bith anseo, agus ní­
aontú leo é an liosta seo.",
'isbn'	=> 'ISBN',
'rfcurl' =>  "http://www.faqs.org/rfcs/rfc$1.html",
"alphaindexline" => "$1 go $2",
'version'		=> 'Leagan',

# Email this user
#
"mailnologin"   => "Ní­l aon seoladh maith ann",
"mailnologintext" => "Ní mór duit bheith  <a href=\"{{localurl:Special:Userlogin\">logáilte isteach</a>
agus bheith le seoladh rí­omhphoist bhailí­ i do chuid <a href=\"{{localurl:Special:Preferences}}\">sainroghanna</a>
más mian leat rí­omhphost a sheoladh chuig úsáideoirí­ eile.",
"emailuser"     => "Cuir rí­omhphost chuig an úsáideoir seo",
"emailpage"     => "Seol rí­omhphost",
"emailpagetext" => "Ma d'iontráil an úsáideoir seo seoladh rí­omhphoist bhailí­
ina chuid sainroghanna úsáideora, cuirfidh an foirm anseo thí­os teachtaireacht amháin do.
Beidh do seoladh rí­omhphoist, a d'iontráil tú i do chuid sainroghanna úsáideora, ann
san bhosca \"Seoltóir\" an riomhphoist, agus mar sin ba féidir léis an faighteoir rí­omhphost a chur leatsa.",
'usermailererror' => 'Earráid leis an píosa ríomhphoist: ',
'defemailsubject'  => "Ríomhphost Vicipéide",
"noemailtitle"  => "Ní­l aon seoladh rí­omhphoist ann",
"noemailtext"   => "Ní­or thug an úsáideoir seo seoladh rí­omhphoist bhailí­, nó shocraigh sé nach
mian leis rí­omhphost a fháil ón úsáideoirí­ eile.",
"emailfrom"     => "Seoltóir",
"emailto"       => "Chuig",
"emailsubject"  => "Ábhar",
"emailmessage"  => "Teachtaireacht",
"emailsend"     => "Seol",
"emailsent"     => "Rí­omhphost seolta",
"emailsenttext" => "Seoladh do theachtaireacht rí­omhphoist go ráthúil.",

# Watchlist
#
"watchlist"     => "Mo liosta faire",
"watchlistsub"  => "(don úsáideoir \"$1\")",
"nowatchlist"   => "Ní­l aon rud i do liosta faire.",
"watchnologin"  => "Ní­l tú logáilte isteach",
"watchnologintext"  => "Ní mór duit bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logáilte isteach</a>
chun do liosta faire a athrú.",
"addedwatch"    => "Curtha san liosta faire",
"addedwatchtext" => "Cuireadh an leathanach \"$1\" le do <a href=\"" .
  wfLocalUrl( "Speisialta:Watchlist" ) . "\">liosta faire</a>.
Cuirfear athruithe amach anseo don leathanach sin agus a leathanach phlé leis an liosta ann,
agus beidh <b>cló dubh</b> ar a teideal san <a href=\"" .
  wfLocalUrl( "Speisialta:Recentchanges" ) . "\">liosta de na hathruithe is déanaí</a> sa chaoi go
bhfeicfeá iad go héasca.</p>

<p>Más mian leat an leathanach a bain amach do liosta faire ní­os déanaí­, brúigh ar \"Stop ag faire\" ar an taobhbharra.",
"removedwatch"  => "Bainthe amach ón liosta faire",
"removedwatchtext" => "Baineadh an leathanach \"$1\" amach ó do liosta faire.",
'watch' => 'Faire',
"watchthispage" => "Faire ar an leathanach seo",
'unwatch' => 'Stop ag faire',
"unwatchthispage" => "Stop ag faire",
"notanarticle"  => "Ní­l alt ann",
"watchnochange" => "Ní­or athraí­odh aon de na leathanaigh i do liosta faire taobh istigh den am socraithe.",
"watchdetails" => "(Tá tú ag faire ar $1 leathanaigh chomh maith leis na leathanaigh phlé;
le déanach athraí­odh $2 leathanaigh in iomlán;
$3...
<a href='$4'>athraigh do liosta</a>.)",
"watchmethod-recent" => "ag seiceáil na athruithe deireanacha do leathanaigh faire",
"watchmethod-list" => "ag seiceáil na leathanaigh faire do athruithe deireanacha",
"removechecked" => "Bain mí­reanna marcálaithe amach as do liosta faire",
"watchlistcontains" => "Tá $1 leathanaigh i do liosta faire.",
"watcheditlist" => "Seo liosta na leathanaigh i do liosta faire, in ord aibitre.
Marcáil boscaí­ de na leathanaigh atá le baint amach an liosta faire, agus bruigh
an cnaipe 'bain amach le marcanna' ag bun an leathanaigh.",
"removingchecked" => "Ag baint amach na mí­reanna ón liosta faire mar a iarraidh...",
"couldntremove" => "Ní­or baineadh amach an mí­r '$1'...",
"iteminvalidname" => "Fadhb leis an mí­r '$1', ainm neamhbhailí­...",
"wlnote" => "Seo iad na $1 athruithe seo caite sna <b>$2</b> uaire seo caite.",
"wlshowlast" => "Taispeáin na mí­reanna deireanacha $1 uaire $2 laethanta $3", #FIXME
'wlsaved'			=> 'Seo leagan sábháilte do liosta faire.',

# Delete/protect/revert
#
"deletepage"    => "Scrios an leathanach",
"confirm"       => "Cinntigh",
"excontent" => "sin a raibh an ábhar:",
"exbeforeblank" => "sin a raibh an ábhar roimh an folmhadh:",
"exblank" => "bhí­ an leathanach folamh",
"confirmdelete" => "Cinntigh an scriosadh",
"deletesub"     => "(Ag scriosadh \"$1\")",
"historywarning" => "Aire: Ta stair ag an leathanach a bhfuil tú ar tí­ é a scrios: ",
"confirmdeletetext" => "Tá tú ar tí­ leathanach nó í­omhá a scrios,
chomh maith leis a chuid stair, ón bunachar sonraí­.
Cinntigh go mian leis an méid seo a dhéanamh, go dtuigeann tú na
iarmhairtaí­, agus go ndéanann tú é dar leis [[Vicipéid:Polasaí­]].",
"confirmcheck"  => "Sea, is mian liom go fí­rinneach an rud seo a scrios.",
"actioncomplete" => "Gní­omh déanta",
"deletedtext"   => "\"$1\" atá scriosaithe.
Féach ar $2 chun cuntas den scriosadh deireanacha a fháil.",
"deletedarticle" => "scriosadh \"$1\"",
"dellogpage"    => "Cuntas_scriosaidh",
"dellogpagetext" => "Seo é liosta de na scriosaidh is deireanacha.
Is in am an innill friothála­ (UTC) iad na hamanna anseo thí­os.
<ul>
</ul>
",
"deletionlog"   => "cuntas scriosaidh",
"reverted"      => "Tá eagrán ní­os luaithe in úsáid anois",
"deletecomment" => "Cúis don scriosadh",
"imagereverted" => "D'éirigh le athúsáid eagráin ní­os luaithe.",
"rollback"      => "Athúsáid athruithe",
"rollbacklink"  => "athúsáid",
"rollbackfailed" => "Theipeadh an athúsáid",
"cantrollback"  => "Ní­ féidir an athrú a áthúsáid; ba é údar an ailt an aon scrí­bhneoir atá ann.",
"alreadyrolled" => "Ní­ féidir eagrán ní­os luath an leathanach [[$1]]
le [[Úsáideoir:$2|$2]] ([[Plé úsáideora:$2|Plé]]) a athúsáid; d'athraigh duine eile é fós nó
d'athúsáid duine eile eagrán ní­os luaithe fós.

Ba é [[Úsáideoir:$3|$3]] ([[Plé úsáideora:$3|Plé]]) an té a rinne an athrú seo caite. ",
#   only shown if there is an edit comment
"editcomment" => "Seo a raibh an mí­nithe athraithe: \"<i>$1</i>\".",
"revertpage"    => "D'athúsáideadh an athrú seo caite le $1",
"protectlogpage" => "Cuntas_cosanta",
"protectlogtext" => "Seo é liosta de glais a cuireadh ar / baineadh de leathanaigh.
Féach ar [[$wgMetaNamespace:Leathanach faoi ghlas]] chun a thuilleadh eolais a fháil.",
"protectedarticle" => "faoi ghlas [[$1]]",
"unprotectedarticle" => "gan ghlas [[$1]]",
'protectsub' =>"(Ag curadh \"$1\" faoi ghlas)",
'confirmprotecttext' => 'Ar mhaith leat go fírinneach glas a chur ar an leathanach seo?',
'confirmprotect' => 'Cinntigh an glas',
'protectcomment' => 'Cúis don glas',
'unprotectsub' =>"(Ag baint an glas de \"$1\")",
'confirmunprotecttext' => 'Ar mhaith leat go fírinneach an glas a bhaint den leathanach seo?',
'confirmunprotect' => 'Cinntigh baint an glais',
'unprotectcomment' => 'Cúis do baint an glais',
'protectreason' => '(tabhair cúis)',


# Undelete
"undelete" => "Cuir leathanach scriosaithe ar ais",
"undeletepage" => "Féach ar agus cuir ar ais leathanaigh scriosaithe",
"undeletepagetext" => "Scriosaí­odh na leathanaigh seo a leanas cheana, ach
tá sí­ad ann fós san cartlann agus is féidir iad a cuir ar ais.
Ó am go ham, is féidir leis an cartlann bheith folmhaithe.",
"undeletearticle" => "Cuir alt scriosaithe ar ais",
"undeleterevisions" => "Cuireadh $1 leagain sa chartlann",
"undeletehistory" => "Má chuirfeá ab leathanach ar ais, cuirfear ar ais gach athbhreithniú chuig an stair.
Má chruthaí­odh leathanach nua leis an ainm céanna ó shin an scriosadh, taispeáinfear
na sean-athruithe san stair roimhe seo, agus ní­ athshuighfear an eagrán reatha an leathanaigh go huathoibrí­och.",
"undeleterevision" => "Leagan scriosaithe den dáta $1",
"undeletebtn" => "Cuir ar ais!",
"undeletedarticle" => "cuireadh \"$1\" ar ais",
"undeletedtext"   => "Cuireadh an alt [[$1]] ar ais go rathúil.
Féach ar [[Vicipéid:Cuntas_scriosaidh]] chun cuntas de scriosaidh agus athchóirithe deireanacha a fháil.",

# Contributions
#
"contributions" => "Dréachtaí­ úsáideora",
"mycontris" => "Mo chuid dréachtaí­",
"contribsub"    => "Do $1",
"nocontribs"    => "Ní­or bhfuarthas aon athrú a raibh cosúil le na crí­téir seo.",
"ucnote"        => "Is iad seo thí­os na <b>$1</b> athruithe is deireanaí­ an úsáideora sna <b>$2</b> lae seo caite.",
"uclinks"       => "Féach ar na $1 athruithe is déanaí­; féach ar na $2 lae seo caite.",
"uctop"     => " (barr)" ,

# What links here
#
"whatlinkshere" => "Naisc don lch seo",
"notargettitle" => "Ní­l aon cuspóir ann",
"notargettext"  => "Ní­or thug tú leathanach nó úsáideoir sprice
chun an gní­omh seo a dhéanamh ar.",
"linklistsub"   => "(Liosta nasc)",
"linkshere"     => "Nascaí­onn na leathanaigh seo a leanas chuig an leathanach seo:",
"nolinkshere"   => "Ní­ nascaí­onn aon leathanach chuig an leathanach seo.",
"isredirect"    => "Leathanach athsheolaidh",

# Block/unblock IP
#
"blockip"       => "Coisc úsáideoir",
"blockiptext"   => "Úsáid an foirm anseo thí­os chun bealach scrí­ofa a chosc ó
seoladh IP nó ainm úsáideora áirithe.
Is féidir leat an rud seo a dhéanamh amháin chun an chreachadóireacht a chosc, de réir
mar a deirtear san [[Vicipéid:Polasaí­|polasaí­ Vicipéide]].
Lí­onaigh cúis áirithe anseo thí­os (mar shampla, is féidir leat a luaigh
leathanaigh áirithe a rinne an duine damáiste ar).",
"ipaddress"     => "Seoladh IP / ainm úsáideora",
'ipbexpiry'		=> 'Am éaga',
"ipbreason"     => "Cúis",
"ipbsubmit"     => "Coisc an úsáideoir seo",
"badipaddress"  => "Ní­l aon úsáideoir ann leis an ainm seo.",
"noblockreason" => "Ní mór duit cúis a thabhairt don cosc.",
"blockipsuccesssub" => "D'éirigh leis an cosc",
"blockipsuccesstext" => "Choisceadh \"$1\".
<br>Féach ar [[Speisialta:Ipblocklist|Liosta coisc IP]] chun coisc a athbhreithnigh.",
"unblockip"     => "Bain an cosc den úsáideoir",
"unblockiptext" => "Úsáid an foirm anseo thí­os chun bealach scrí­ofa a thabhairt ar ais go seoladh
IP nó ainm úsáideora a raibh coiscthe roimhe seo.",
"ipusubmit"     => "Bain an cosc den seoladh seo",
"ipusuccess"    => "\"$1\" gan cosc",
"ipblocklist"   => "Liosta seoltaí­ IP agus ainmneacha úsáideoirí­ coiscthe",
"blocklistline" => "$1, $2 a choisc $3 (am éaga $4)",
"blocklink"     => "Coisceadh",
"unblocklink"   => "bain an cosc den",
"contribslink"  => "dréachtaí­",
"autoblocker"   => "Coiscthe go huathoibrí­och de bhrí­ go roinneann tú an seoladh IP céanna le \"$1\". Cúis \"$2\".",
"blocklogpage"  => "Cuntas_coisc",
"blocklogentry" => 'coisceadh "$1"; is é $2 an am éaga',
"blocklogtext"  => "Seo é cuntas de gní­omhartha coisc úsáideoirí­ agus mí­choisc úsáideoirí­. Ní­ cuirtear
seoltaí­ IP a raibh coiscthe go huathoibrí­och ar an liosta seo. Féach ar an [[Speisialta:Ipblocklist|Liosta coisc IP]] chun
liosta a fháil de coisc atá i bhfeidhm faoi láthair.",
"unblocklogentry"   => 'baineadh an cosc den "$1"',
'range_block_disabled'	=> 'Faoi láthair, míchumasaítear an cumas riarthóra chun coisc réimse a dhéanamh.',
'ipb_expiry_invalid'	=> 'Am éaga neamhbhailí.',
'ip_range_invalid'	=> "Réimse IP neamhbhailí.\n",
'proxyblocker'	=> 'Cosc do inneall fo-friothála', #FIXME
'proxyblockreason'	=> "Cuireadh cosc ar do sheoladh IP de bharr gur bealach fo-friothála neamhshlándála é. Déan teagmháil le do chomhlacht idirlín nó do lucht cabhrach teicneolaíochta go mbeidh 'fhios acu faoin fadhb slándála tábhachtach seo.",
'proxyblocksuccess'	=> "Críochnaithe.\n",

# Developer tools
#
"lockdb"        => "Cuir glas ar an bunachar sonraí­",
"unlockdb"      => "Bain an glas den bunachar sonraí­",
"lockdbtext"    => "Má chuirfeá glas ar an bunachar sonraí­, ní­ beidh cead ar aon úsáideoir
leathanaigh a chur in eagar, a socruithe a athrú, a liostaí­ faire a athrú, nó rudaí­ eile a thrachtann le
athruithe san bunachar sonraí­.
Cinntigh go bhfuil an scéal seo d'intinn agat, is go bainfidh tú an glas den bunachar sonraí­ nuair a bhfuil
do chuid coinneáil críochnaithe.",
"unlockdbtext"  => "Má bhainfeá an glas den bunachar sonraí­, beidh ceat ag gach úsáideoirí­ aris
na leathanaigh a chur in eagar, a sainroghanna a athrú, a liostaí­ faire a athrú, agus rudaí­ eile
a dhéanamh a thrachtann le athruithe san bunachar sonraí­.
Cinntigh go bhfuil an scéal seo d'intinn agat.",
"lockconfirm"   => "Sea, is mian liom glas a chur ar an bunachar sonraí­.",
"unlockconfirm" => "Sea, is mian liom glas a bhain den bunachar sonraí­.",
"lockbtn"       => "Cuir glas ar an mbunachar sonraí­",
"unlockbtn"     => "Bain an glas den bunachar sonraí­",
"locknoconfirm" => "Ní­or mharcáil tú an bosca daingnithe.",
"lockdbsuccesssub" => "D'éirigh le glas an bunachair sonraí­",
"unlockdbsuccesssub" => "Baineadh an glas den bunachar sonraí­",
"lockdbsuccesstext" => "Cuireadh glas ar an mbunachar sonraí­ Vicipéide.
<br />Cuimhnigh nach mór duit an glas a bhaint tar éis do chuid coinneála.",
"unlockdbsuccesstext" => "Baineadh an glas den bunachar sonraí­ Vicipéide.",

# SQL query
#
"asksql"        => "Órdú SQL",
"asksqltext"    => "Úsáid an foirm anseo thí­os chun órdú dí­reach a chur chuig an bunachar sonraí­ Vicipéide.
Úsáid comharthaí­ athfhriotail singile ('mar sin') chun teorainn a chur le litriúla sraithe. Úsáid an gné seo go coigilteach.",
"sqlislogged"   => "Tabhair faoi deara go cuirtear gach órdú i gcuntas.",
"sqlquery"      => "Iontráil órdú",
"querybtn"      => "Cuir órdú",
"selectonly"    => "Ní­l na ceisteanna ina theannta \"SELECT\" ann ach amháin do
cumadóirí­ Vicipéide.",
"querysuccessful" => "D'éirigh leis an t-órdú",

# Make sysop
'makesysoptitle'	=> 'Déan ceannasaí de úsáideoir',
'makesysoptext'		=> 'Úsáideann maorlathaigh an fhoirm seo chun riarthóirí a dhéanamh de ghnáthúsáideoirí.
Iontráil ainm an úsáideora sa bosca seo agus brúigh an cnaipe chun riarthóir a dhéanamh den úsáideoir',
'makesysopname'		=> 'Ainm an úsáideora:',
'makesysopsubmit'	=> 'Déan ceannasaí den úsáideoir seo',
'makesysopok'		=> "Is ceannasaí atá san <b>Úsáideoir \"$1\" anois.</b>",
'makesysopfail'		=> "<b>Níor rinneadh ceannasaí den Úsáideoir \"$1\". (Ar iontráil tú an ainm go ceart?)</b>",
'setbureaucratflag' => 'Athraigh an brat maotharlach',
'bureaucratlog'		=> 'Liosta_maotharlach',
'bureaucratlogentry'	=> "Tá na cearta don úsáideoir \"$1\" athraithe bheith \"$2\"",
'rights'			=> 'Cearta:',
'set_user_rights'	=> 'Athraigh cearta úsáideora',
'user_rights_set'	=> "<b>Leasaíodh na cearta úsáideora do \"$1\"</b>",
'set_rights_fail'	=> "<b>Níorbh fhéidir na cearta úsáideora do \"$1\" a athrú. (Ar iontráil tú an ainm go ceart?)</b>",
'makesysop'         => 'Déan ceannasaí de úsáideoir',

# Validation
'val_clear_old' => 'Glan mo chuid sonraí bailíochta do $1',
'val_merge_old' => 'Úsáid mo measúnacht roimhe seo nuair atá roghnaithe \'Gan tuairim\'',
'val_form_note' => '<b>Leid:</b> Má chumaiscítear do chuid sonraí, athreofar
gach sainrogha ina chuir tú <i>gan tuairim</i> don leagan ailt a roghnaíonn
tú bheith an luach is an trácht atá ag an t-athrú is déanach ina thóg tú
tuairim. Mar shampla, más maith leat rogha amháin do athrú níos nuaí a
hathrú, ach ba mhaith leat do roghanna eile don alt a chosaint, ná roghnaigh
rud ar bith ach amháin an rogha ba mhaith leat a <i>athrú</i>, agus le
cumaisc líonfar na roghanna eile de réir na roghanna a raibh agat roimhe ré.',
'val_noop' => 'Gan tuairim',
'val_percent' => '<b>$1%</b><br>($2 de $3 pointí<br>a rinne $4 úsáideoirí)',
'val_percent_single' => '<b>$1%</b><br>($2 de $3 pointí<br>a rinne aon úsáideoir)',
'val_total' => 'Iomlán',
'val_version' => 'Leagan',
'val_tab' => 'Seiceáil an bhailíocht',
'val_this_is_current_version' => 'is é seo an t-eagrán is deireanach',
'val_version_of' => "Leagan ó $1" ,
'val_table_header' => "<tr><th>Rang</th>$1<th colspan=4>Tuairim</th>$1<th>Trácht</th></tr>\n",
'val_stat_link_text' => 'Staitistic bhailíochta don alt seo',
'val_view_version' => 'Féach ar an leagan seo',
'val_validate_version' => 'Déan bhailíocht den leagan seo',
'val_user_validations' => 'Rinne an t-úsáideoir seo bhailíocht ar $1 leathanaigh.',
'val_no_anon_validation' => 'Caithfidh tú bheith logáilte isteach chun bailíocht a dhéanamh ar alt.',
'val_validate_article_namespace_only' => 'Ní féidir ach amháin ailt a chur i bhailíocht. <i>Níl</i> an leathanach seo san ainmroinn do ailt.',
'val_validated' => 'Tá an bhailíocht críochnaithe.',
'val_article_lists' => 'Líosta ailt le bailíocht',
'val_page_validation_statistics' => 'Staitistic bhailíochta ailt do $1',

# Move page
#
"movepage"      => "Athainmnigh an leathanach",
"movepagetext"  => "Úsáid an foirm anseo thí­os chun leathanach a hathainmniú. Aistreofar a chuid
stair go léir chuig an ainm nua.
Déanfar leathanach athsheolaidh den sean-ainm chuig an ainm nua.
Ní­ athreofar naisc chuig sean-ainmneacha an leathanaigh. Bí cinnte go ndéanfá
[[Special:Maintenance|cuardach]] ar athsheolaidh dubáilte nó briste.
Tá tú freagrach i cinnteach go leanann naisc chuig an pointe a bhfuil siad ag aimsiú ar.

Tabhair faoi deara '''nach''' athainmneofar an leathanach má bhfuil leathanach
ann cheana féin leis an ainm nua, mura bhfuil sé folamh nó athsheoladh nó mura bhfuil aon
stair athraithe aige cheana. Ciallaí­onn sé sin go féidir leat leathanach a athainmniú ar ais
chuig an áit ina raibh sé roimhe má dhéanfá botún, agus ní­ féidir leat leathanach atá ann a forshcriobh ar.

<b>AIRE!</b>
Is athrú tábhachtach é athainmniú má tá leathanach coitianta i gceist;
cinntigh go dtuigeann tú na iarmhairtí­ go léir roimh a leanfá.",
"movepagetalktext" => "Aistreofar an leathanach phlé leis, má tá sin ann:
*'''mura''' bhfuil tú ag aistriú an leathanach trasna ainmranna,
*'''mura''' bhfuil leathanach phlé neamhfholamh ann leis an ainm nua, nó
*'''mura''' baineann tú an marc den bosca anseo thí­os.

Sna scéil sin, caithfidh tú an leathanach a aistrigh nó a báigh leis na lámha má tá sin an rud atá uait.",
"movearticle"   => "Aistrigh an leathanach",
"movenologin"   => "Ní­l tú logtha ann",
"movenologintext" => "Ní mór duit bheith úsáideoir cláraithe agus <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun leathanach a aistrigh.",
"newtitle"      => "Go teideal nua",
"movepagebtn"   => "Aistrigh an leathanach",
"pagemovedsub"  => "D'éirigh leis an athainmniú",
"pagemovedtext" => "D'aistraí­odh an leathanach \"[[$1]]\" chuig \"[[$2]]\".",
"articleexists" => "Tá leathanach leis an ainm seo ann fós, nó ní­l an
ainm a rinne tú rogha air ina ainm bailí­.
Toghaigh ainm eile le do thoil.",
"talkexists"    => "D'aistraí­odh an leathanach é féin go rathúil, ach ní­ raibh sé ar a chumas an
leathanach phlé a aistriú de bhrí­ go bhfuil ceann ann fós ag an
ainm nua. Báigh iad tusa féin, le do thoil.",
"movedto"       => "aistraithe go",
"movetalk"      => "Aistrigh an leathanach \"phlé\" freisin, má bhfuil an leathanach sin ann.",
"talkpagemoved" => "D'aistraí­odh an leathanach phlé frithiontráil.",
"talkpagenotmoved" => "<strong>Ní­or</strong> aistraí­odh an leathanach phlé frithiontráil.",
'1movedto2'		=> "D'aistríodh $1 chuig $2",
'1movedto2_redir' => "D'aistríodh $1 chuig $2 thar athsheoladh",

#Export

"export"        => "Onnmhairigh leathanaigh",
"exporttext"    => "Is féidir leat an téacs agus stair athraithe de leathanach áirithe a onnmhairiú,
fillte i bpí­osa XML; is féidir leat ansin é a iompórtáil isteach vicí eile atá leis an oideasra MediaWiki
air, nó is féidir leat é a coinniú do do chuid shiamsa féin.",
"exportcuronly" => "Ná cuir san áireamh ach an eagrán reatha, ná cuir ann an stair in iomlán",

# Namespace 8 related

"allmessages"   => "Gach teachtaireacht córais",
"allmessagestext"   => "Seo é liosta de na teachtaireachtaí­ go léir atá le fáil san ainmroinn MediaWiki: .",

# Thumbnails

'thumbnail-more'	=> 'Méadaigh',
'missingimage'		=> "<b>Íomhá ar iarraidh</b><br /><i>$1</i>\n",

# Special:Import
'import'	=> 'Iompórtáil leathanaigh',
'importtext'	=> 'Onnmhairigh an comhad ón fhoinse-vicí le do thoil (le húsáid an tréith Speisialta:Export), sábháil é ar do dhíosca agus uaslódáil é anseo.',
'importfailed'	=> "Theip ar an iompórtáil: $1",
'importnotext'	=> 'Folamh nó gan téacs',
'importsuccess'	=> "D'eirigh leis an iompórtáil!",
'importhistoryconflict' => 'Tá stair athraithe contrártha ann cheana féin (is dócha go uaslódáladh an leathanach seo roimh ré)',

# Keyboard access keys for power users
'accesskey-search' => 'c', # Cuardaigh
'accesskey-minoredit' => 'm', # Mionathrú
'accesskey-save' => 's', # Sábháil
'accesskey-preview' => 'r', # Reamhspléachas
'accesskey-compareselectedversions' => 'l', # Leagain

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Cuardaigh an vicí seo [alt-c]',
'tooltip-minoredit' => 'Déan mionathrú den athrú seo [alt-m]',
'tooltip-save' => 'Sábháil do chuid athruithe [alt-s]',
'tooltip-preview' => 'Reamhspléach do chuid athruithe; úsáid an gné seo roimh a shábhálaíonn tú! [alt-r]',
'tooltip-compareselectedversions' => 'Feic na difríochtaí idir na dhá leagain roghnaithe den leathanach seo. [alt-l]',

# stylesheets

'Monobook.css' => '/* athraigh an comhad seo chun an cuma monobook a athrú don suíomh ar fad */',
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
'nodublincore' => 'Míchumasaítear meitea-shonraí Dublin Core RDF ar an inneall friothála seo.',
'nocreativecommons' => 'Míchumasaítear meitea-shonraí Creative Commons RDF ar an inneall friothála seo.',
'notacceptable' => 'Ní féidir leis an inneall friothála vicí na sonraí a chur ar fáil i formáid a féidir le do chliant a léamh.',

# Attribution

'anonymous' => "Úsáideoir(í) gan ainm ar $wgSitename",
'siteuser' => "Úsáideoir Vicipéide $1", #FIXME - genitive needed here
'lastmodifiedby' => "Athraíodh an leathanach seo go deireanach ag $1 le $2.",
'and' => 'agus',
'othercontribs' => "Bunaithe ar oibre a rinne $1.",
'others' => 'daoine eile',
'siteusers' => "Úsáideoir(í) Vicipéide $1", #FIXME - genitive needed here
'creditspage' => 'Creidiúintí leathanaigh',
'nocredits' => 'Níl aon eolas creidiúna le fáil don leathanach seo.',

# Spam protection

'spamprotectiontitle' => 'Scagaire in aghaidh ríomhphost dramhála',
'spamprotectiontext' => 'Chuir an scagaire dramhála bac ar an leathanach a raibh tú ar iarradh sábháil. Is dócha gur nasc chuig suíomh seachtrach ba chúis leis.

Is dócha gur mhaith leat an gnáth-leagan seo a seiceáil chun patrúin a fheiceáil atá faoi bhac i láthair na huaire:',
'subcategorycount' => "Tá $1 fo-ranganna sa rang seo.",
'categoryarticlecount' => "Tá $1 ailt sa rang seo.",
'usenewcategorypage' => "1\n\nScríobh \"0\" mar an chéad litir chun an leagan amach nua ranga a mhíchumasaigh.",

# Info page
"infosubtitle" => "Eolas don leathanach",
"numedits" => "Méid athruithe (alt): $1",
"numtalkedits" => "Méid athruithe (leathanach phlé): $1",
"numwatchers" => "Méid féachnóirí: $1",
"numauthors" => "Méid údair ar leith (alt): $1",
"numtalkauthors" => "Méid údair ar leith (leathanach phlé): $1",

# Math options

    'mw_math_png' => "Déan PNG-í­omhá gach uair",
    'mw_math_simple' => "Déan HTML má tá sin an-easca, nó PNG ar mhodh eile",
    'mw_math_html' => "Déan HTML más féidir, nó PNG ar mhodh eile",
    'mw_math_source' => "Fág mar cló TeX (do teacsleitheoirí­)",
    'mw_math_modern' => "Inmholta do lí­onleitheoirí­ nua",
    'mw_math_mathml' => 'MathML más féidir (turgnamhach)',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* leideanna uirlisí agus cnaipí rochtana */
ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Mo leathanach úsáideora\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'An leathanach úsáideora don IP ina dhéanann tú do chuid athruithe\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Mo leathanach phlé
ta[\'pt-anontalk\'] = new Array(\'n\',\'Plé faoina athruithe a dhéantar ón seoladh IP seo\');
ta[\'pt-preferences\'] = new Array(\'\',\'Mo chuid sainroghanna\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Liosta de na leathanaigh a dhéanann tú faire ar do athruithe.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Liosta de mo chuid dréachtaí\');
ta[\'pt-login\'] = new Array(\'o\',\'Moltar duit logáil isteach, ach níl sé riachtanach.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Moltar duit logáil isteach, ach níl sé riachtanach.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Logáil amach\');
ta[\'ca-talk\'] = new Array(\'t\',\'Plé faoin leathanach ábhair\');
ta[\'ca-edit\'] = new Array(\'e\',\'Is féidir leat an leathanach seo a athrú. Más é do thoil é, bain úsáid as an cnaipe reamhspléachais roimh a shabhálaíonn tú.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Scríobh trácht don plé seo.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Tá an leathanach seo faoi ghlas. Is féidir leat a fhoinse a fheiceáil.\');
ta[\'ca-history\'] = new Array(\'h\',\'Leagain stairiúla den leathanach seo.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Cuir glas ar an leathanach seo\');
ta[\'ca-delete\'] = new Array(\'d\',\'Scrios an leathanach seo\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Cuir ar ais na hathruithe a raibh déanta don leathanach seo roimh a scriosadh é\');
ta[\'ca-move\'] = new Array(\'m\',\'Athainmnigh an leathanach\');
ta[\'ca-nomove\'] = new Array(\'\',\'Níl an cead riachtanach agat chun an leathanach a athainmniú\');
ta[\'ca-watch\'] = new Array(\'w\',\'Cuir an leathanach seo ar do liosta faire\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Bain an leathanach seo as do liosta faire\');
ta[\'search\'] = new Array(\'f\',\'Cuardaigh san vicí seo\');
ta[\'p-logo\'] = new Array(\'\',\'Príomhleathanach\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Tabhair cuairt ar an bPríomhleathanach\');
ta[\'n-portal\'] = new Array(\'\',\'Faoin tionscadal, cad is féidir leat a dhéanamh, cás féidir leat achmhainní a fháil\');
ta[\'n-currentevents\'] = new Array(\'\',\'Faigh eolas cúlrach faoi chursaí reatha\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Liosta de na hathruithe is déanaí sa vicí.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Lódáil leathanach fánach\');
ta[\'n-help\'] = new Array(\'\',\'An áit chun cabhair a fháil.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Tabhair tacaíocht duinn\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Liosta de gach leathanach sa vicí a nascaíonn don leathanach seo\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Na hathruithe is déanaí do leathanaigh a nascaíonn don an leathanach seo\');
ta[\'feed-rss\'] = new Array(\'\',\'Sní eolais RSS don leathanach seo\');
ta[\'feed-atom\'] = new Array(\'\',\'Sní eolais Atom don leathanach seo\');
ta[\'t-contributions\'] = new Array(\'\',\'Féach ar an liosta dréachtaí a rinne an t-úsáideoir seo\');
ta[\'t-emailuser\'] = new Array(\'\',\'Cuir teachtaireacht chuig an úsáideoir seo\');
ta[\'t-upload\'] = new Array(\'u\',\'Comhaid íomhá nó meáin a uaslódáil\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Liosta de gach leathanach speisialta\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Féach ar an leathanach ábhair\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Féach ar an leathanach úsáideora\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Féach ar an leathanach meáin\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Is leathanach speisialta é seo, ní féidir leat é fhéin a athrú.\');
ta[\'ca-nstab-wp\'] = new Array(\'a\',\'Féach ar an leathanach thionscadail\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Féach ar an leathanach íomhá\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Féach ar an teachtaireacht córais\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Féach ar an múnla\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Féach ar an leathanach cabhrach\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Féach ar an leathanach ranga\');'

);

require_once( "LanguageUtf8.php" );

class LanguageGa extends LanguageUtf8 {

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
        /* Fallback to English names for compatibility */
        return Language::getNsIndex( $text );
    }

    function getQuickbarSettings() {
        global $wgQuickbarSettingsGa;
        return $wgQuickbarSettingsGa;
    }

    function getSkinNames() {
        global $wgSkinNamesGa;
        return $wgSkinNamesGa;
    }

    function getDateFormats() {
        global $wgDateFormatsGa;
        return $wgDateFormatsGa;
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

	function getMagicWords()
	{
		global $wgMagicWordsGa;
		return $wgMagicWordsGa;
	}

}

?>
