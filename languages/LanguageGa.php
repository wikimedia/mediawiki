<?php

/* Irish */

/*
(This version of LanguageGa.php originally dates from the first version of 23:20,
14th February 2004. It was translated from LanguageEn.php and LanguageEo.php,
using the standard Ó Dónaill and De Bhaldraithe dictionaries, the Oxford
minidictionary and Ó Donnáile's computing wordlist. There are small mistakes
abound here, but corrections should principally be made to the live version on
Sourceforge (when that gets added.)
*/

if ( $wgSitename == "Wikipedia" ) {
	$wgSitename = "Vicipéid";
}
if ( $wgMetaNamespace = "Wikipedia" ) {
	$wgMetaNamespace = "Vicipéid";
}


/* private */ $wgNamespaceNamesGa = array(
    -2  => "Media",
    -1  => "Speisialta",
    0   => "",
    1   => "Plé",
    2   => "Úsáideoir",
    3   => "Plé_úsáideora",
    4   => $wgMetaNamespace,
    5   => "Plé_".$wgMetaNamespace,
    6   => "Íomhá",
    7   => "Plé_íomhá",
    8   => "MediaWiki",
    9   => "Plé_MediaWiki"
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
    "Faic", "Greamaithe ar chlé", "Greamaithe ar an taobh deas", "Ag faoileáil ar chlé"
);

/* private */ $wgSkinNamesGa = array(
    "Gnáth", "Sean-nós", "Gorm na Cologne", "Paddington", "Montparnasse"
);

/* private */ $wgMathNamesGa = array(
    "Déan PNG-íomhá gach uair",
    "Déan HTML má tá sin an-easca, nó PNG ar mhodh eile",
    "Déan HTML más féidir, nó PNG ar mhodh eile",
    "Fág mar cló TeX (do teacsleitheoirí)",
    "Inmholta do líonleitheoirí nua"
);

/* private */ $wgDateFormatsGa = array(
    "Is cuma liom",
    "Eanáir 15, 2001",
    "15 Eanáir 2001",
    "2001 Eanáir 15",
    "2001-01-15"
);

/* private */ $wgUserTogglesGa = array(
    "hover"     => "Taispeáin airebhoscaí os cionn na vicilúibíní",
    "underline" => "Cuir línte faoi na lúibíní",
    "highlightbroken" => "Cuir dath dearg ar lúibíní briste, <a href=\"\" class=\"new\">mar sin</a> (rogha eile: mar sin<a href=\"\" class=\"internal\">?</a>).",
    "justify"   => "Comhfhadaigh na paragraif",
    "hideminor" => "Ná taispeáin fo-eagair sna athruithe deireanacha",
    "usenewrc" => "Stíl nua do na athruithe deireanacha (le JavaScript)",
    "numberheadings" => "Uimhrigh ceannteidil go huathoibríoch",
    "editondblclick" => "Cuir leathanaigh in eagar le roghna dúbailte (JavaScript)",
    "editsection"=>"Cumasaigh eagarthóireacht mír le lúibíní [athraithe]",
    "editsectiononrightclick"=>"Cumasaigh eagarthóireacht mír le deas-roghna<br> ar ceannteidil (JavaScript)",
    "showtoc"=>"Déan liosta na ceannteideal<br>(do ailt le níos mó ná 3 ceannteidil)",
    "rememberpassword" => "Cuimhnigh mo focal faire",
    "editwidth" => "Cuir uasméid ar an athrúbhosca",
    "watchdefault" => "Breathnaigh ar leathanaigh a d'athraigh tú",
    "minordefault" => "Cuir marc mionathraithe ar gach athrú, mar réamhshocrú",
    "previewontop" => "Cuir an réamhthaispeántas os cionn an athrúbhosca, agus ná taobh thíos de",
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

# Different spellings of days  (with Dé) may be needed for some uses

/* private */ $wgWeekdayNamesGa = array(
    "Domhnach", "Luan", "Máirt", "Céadaoin", "Déardaoin",
    "Aoine", "Satharn"
);

/* private */ $wgMonthNamesGa = array(
    "Eanáir", "Feabhra", "Márta", "Aibreán", "Bealtaine", "Meitheamh",
    "Iúil", "Lúnasa", "Meán Fómhair", "Deireadh Fómhair", "Mí na Samhna",
    "Mí na Nollag"
);

/* private */ $wgMonthAbbreviationsGa = array(
    "Ean", "Fea", "Már", "Aib", "Bea", "Mei", "Iúi", "Lún",
    "Mea", "Dei", "Samh", "Nol"
);

# The following exist for the purpose of being translated:

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
    "Watchlist"     => "Mo fairechlár", # List of pages, which the user has chosen to watch
    "Recentchanges" => "Leathanaigh athraithe le déanaí",
    "Upload"        => "Suaslódáil comhaid agus íomhánna",
    "Imagelist"     => "Liosta íomhánna",
    "Listusers"     => "Úsáideoirí cláraithe",
    "Statistics"    => "Staitistic an shuíomh",
    "Randompage"    => "Leathanach fánach",

    "Lonelypages"   => "Leathanaigh dílleachtaí",
    "Unusedimages"  => "Íomhánna dílleachtaí",
    "Popularpages"  => "Ailt coitianta",
    "Wantedpages"   => "Ailt santaithe",
    "Shortpages"    => "Ailt gairide",
    "Longpages"     => "Ailt fada",
    "Newpages"      => "Ailt nua",
    "Ancientpages"  => "Ailt ársa",
#   "Intl"      => "Lúibíní idirtheangacha",
    "Allpages"      => "Gach leathanach de réir teidil",

    "Ipblocklist"   => "Úsáideoirí/IP-sheolaidh coisctha",
    "Maintenance"   => "Leathanach coiméadta",
    "Specialpages"  => "",
    "Contributions" => "",
    "Emailuser"     => "",
    "Whatlinkshere" => "",
    "Recentchangeslinked" => "",
    "Movepage"      => "",
    "Booksources"   => "Leabharfhoinsí seachtraí",
#   "Categories"    => "Ranganna leathanaigh",
    "Export"        => "XML Export",
    "Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesGa = array(
	"Makesysop" => "Turn a user into a sysop",
    "Blockip"       => "Cuir cosc ar úsáideoir/IP-sheoladh",
    "Asksql"        => "Cuir ceist ar an bhunachar sonraí",
    "Undelete"      => "Cuir leathanaigh scriosaithe ar ais"
);

/* private */ $wgDeveloperSpecialPagesGa = array(
    "Lockdb"        => "Cuir glas ar an mbunachar sonraí",
    "Unlockdb"      => "Bain an glas den bunachar sonraí",
    "Debug"     => "Eolas chun fadhtanna a réitigh"
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
"mainpagetext"  => "D'insealbhaíodh an oideas Wiki go rathúil.",
"about"     => "Faoi",
"aboutwikipedia"    => "Faoi Vicipéid",
"aboutpage"     => "{$wgMetaNamespace}:Faoi",
"help"      => "Cabhair",
"helppage"      => "{$wgMetaNamespace}:Cabhair",
"wikititlesuffix" => "Vicipéid",
"bugreports"    => "Fabht-thuairiscí",
"bugreportspage"    => "{$wgMetaNamespace}:Fabht-thuairiscí",
"faq"           => "Ceisteanna Coiteanta",
"faqpage"       => "{$wgMetaNamespace}:Ceisteanna Coiteanta",
"edithelp"      => "Cabhair eagarthóireachta",
"edithelppage"  => "{$wgMetaNamespace}:Conas_alt_a_cur_in_eagar",
"cancel"        => "Cealaigh",
"qbfind"        => "Faigh",
"qbbrowse"      => "Útamáil",
"qbedit"        => "Athraigh",
"qbpageoptions" => "Roghanna leathanaigh",
"qbpageinfo"    => "Eolas leathanaigh",
"qbmyoptions"   => "Mo roghanna",
"mypage"        => "Mo leathanach",
"mytalk"        => "Mo plé",
"currentevents" => "Cursaí reatha",
"errorpagetitle"    => "Earráid",
"returnto"      => "Dul ar ais go $1.",
"fromwikipedia" => "Ón Vicipéid, an chiclipéid shaor.",
"whatlinkshere" => "Leathanaigh a cheanglaíonn chuig an leathanach seo",
"help"      => "Cabhair",
"search"        => "Cuardaigh",
"go"            => "Dul",
"history"       => "Stair leathanaigh",
"printableversion" => "Eagrán clóbhuala",
"editthispage"  => "Athraigh an leathanach seo",
"deletethispage"    => "Dealaigh an leathanach seo",
"protectthispage" => "Cuir glas ar an leathanach seo",
"unprotectthispage" => "Bain an glas den leathanach seo",
"newpage"       => "Leathanach nua",
"talkpage"      => "Plé an leathanach seo",
"postcomment"   => "Cuir mínithe leis an leathanach",
"articlepage"   => "Feach ar an alt",
"subjectpage"   => "Feach ar an t-ábhar", # For compatibility
"userpage"      => "Feach ar leathanach úsáideora",
"wikipediapage" => "Feach ar meitea-leathanach",
"imagepage"     => "Feach ar leathanach íomhá",
"viewtalkpage"  => "Feach ar phlé",
"otherlanguages"    => "Teangacha eile",
"redirectedfrom"    => "(Athsheoladh ó $1)",
"lastmodified"  => "Mhionathraíodh an leathanach seo ar $1.",
"viewcount"     => "Rochtainíodh an leathanach seo $1 uair.",
"gnunote"       => "Tá an teacs ar fad le fáil faoi na téarmaí an <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(Ó http://ga.wikipedia.org)",
"protectedpage" => "Leathanach faoi ghlas",
"administrators"    => "{$wgMetaNamespace}:Riarthóirí",
"sysoptitle"    => "Cuntas ceannasaí de dhíth",
"sysoptext"     => "Caithfidh tú bheith i do \"ceannasaí\" 
chun an gníomh seo a dhéanamh.
Féach ar $1.",
"developertitle"    => "Cuntas ríomhchláraitheora de dhíth",
"developertext" => "Caithfidh tú bheith i do \"cláraitheoir\" 
chun an gníomh seo a dhéanamh.
Féach ar $1.",
"nbytes"        => "$1 bearta",
"go"            => "Dul",
"ok"            => "Go maith",
"sitetitle"     => "Vicipéid",
"sitesubtitle"  => "An Chiclipéid Shaor",
"retrievedfrom" => "Faightear ar ais ó \"$1\"",
"newmessages"   => "Tá $1 agat.",
"newmessageslink" => "teachtaireachtaí nua",
"editsection"   => "athraigh",
"toc"           => "Clár ábhair",
"showtoc"       => "taispeáin",
"hidetoc"       => "folaigh",
"thisisdeleted" => "Breathnaigh nó cuir ar ais $1?",
"restorelink"   => "$1 athruithe scriosaithe",

# Main script and global functions
#
"nosuchaction"  => "Níl a leithéid de ghníomh ann",
"nosuchactiontext" => "Níl aithníonn an oideas Vicipéide 
an gníomh ('action') atá ann san líonsheoladh.",
"nosuchspecialpage" => "Níl a leithéid de leathanach speisialta ann",
"nospecialpagetext" => "Níl aithníonn an oideas Vicipéide 
an leathanach speisialta a d'iarr tú ar.",

# General errors
#
"error"         => "Earráid",
"databaseerror"     => "Earráid an bhunachar sonraí",
"dberrortext"   => "Tharlaigh earráid chomhréir sa cheist chuig an bhunachar sonraí.
Tá seans gur cuireadh ceist cuardach neamhcheart (féach ar $5),
nó tá seans go bhfuil fabht san oideas.
<blockquote><tt>$1</tt></blockquote>, ón suim \"<tt>$2</tt>\",
ab ea an ceist seo caite chuig an bhunachar sonrai.
Chuir MySQL an earráid seo ar ais: \"<tt>$3: $4</tt>\".",
"dberrortextcl"     => "Tharlaigh earráid chomhréir sa cheist chuig an bhunachar sonraí.
\"$1\", ón suim \"$2\",
ab ea an ceist seo caite chuig an bhunachar sonrai,
Chuir MySQL an earráid seo ar ais: \"$3: $4\".\n",
"noconnect"     => "Tá brón orainn! Chuaigh an oideas Wiki in abar teicniúil, agus theipeadh an nasc leis an mbunachar sonraí .",
"nodb"      => "Theipeadh an rogha den bhunachar sonraí $1",
"cachederror"   => "Seo í cóip athscríobhtha den leathanach a raibh tú ag lorg (is dócha go nach bhfuil sí bord ar bhord leis an eagrán reatha).",
"readonly"      => "Bunachar sonraí faoi ghlas",
"enterlockreason" => "Iontráil cúis don ghlas, agus meastachán
den cathain a mbainfear an ghlas de.",
"readonlytext"  => "Tá an bunachar sonraí Vicipéide faoi ghlas anois do iontráilí agus athruithe nua 
(is dócha go bhfuil sé do gnáthchothabháil).
Tar éis seo, beidh an bunachar sonraí tofa ar ais.
Thug an riarthóir a ghlasaigh an míniú seo:
<p>$1",
"missingarticle" => "Chuardaigh an bunachar sonraí ar leathanach go mba chóir a bheith faighte, darbh ainm \"$1\". Níor bhfuarthas an leathanach.

<p>Ní earráid san bunachar sonraí é seo, ach b'fhéidir go bhfuair tú amach fabht 
sna oideasra MediaWiki. De ghnáth, tarlaíonn sé sin nuair a leantar nasc staire nó difríochta go leathanach a raibh scriosaithe cheana féin.

<p>Déan nóta den URL le do thoil, agus cuir an ábhar in iúl do riarthóir.",
"internalerror" => "Earráid inmhéanach",
"filecopyerror" => "Ní féidir an comhad \"$1\" a chóipeáil go \"$2\".",
"filerenameerror" => "Ní féidir an comhad \"$1\" a athainmnigh bheith \"$2\".",
"filedeleteerror" => "Ní féidir an comhad \"$1\" a scriosaigh amach.",
"filenotfound"  => "Ní bhfuarthas an comhad \"$1\".",
"unexpected"    => "Luach gan súil leis: \"$1\"=\"$2\".",
"formerror"     => "Earráid: ní féidir an foirm a tabhair isteach",  
"badarticleerror" => "Ní féidir an gníomh seo a dhéanamh ar an leathanach seo.",
"cannotdelete"  => "Ní féidir an leathanach nó íomhá sonraithe a scriosaigh. (B'fhéidir go shcriosaigh duine eile é cheana féin.)",
"badtitle"      => "Teideal neamhbhailí",
"badtitletext"  => "Bhí teideal an leanthanaigh a d'iarr tú ar neamhbhailí, folamh, nó
teideal idirtheangach no idir-Wiki nasctha go mícheart.",
"perfdisabled" => "Tá brón orainnn! Mhíchumasaíodh an gné seo go sealadach chun luas an bunachair sonraí a chosaint.",
"perfdisabledsub" => "Seo cóip sábháilte ó $1:",
"wrong_wfQuery_params" => "Paraiméadair míchearta don wfQuery()<br>
Feidhm: $1<br>
Ceist: $2
",
"viewsource" => "Féach ar foinse",
"protectedtext" => "Chuirtear ghlas ar an leathanach seo chun é a chosaint in aghaidh athruithe. Tá go leor
cúiseanna féideartha don scéal seo. Féach ar 
[[$wgMetaNamespace:Leathanach faoi ghlas]] le do thoil.

Is féidir leat foinse an leathanaigh seo a feachaint ar agus a chóipeáil:",

# Login and logout pages
#
"logouttitle"   => "Log as",
"logouttext" => "Tá tú logtha as anois.
Is féidir leat an Vicipéid a úsáid fós gan ainm, nó is féidir leat log ann 
arís mar an úsáideoir céanna, nó mar úsáideoir eile. Tabhair faoi deara go taispeáinfear roinnt
leathanaigh mar atá tú logtha ann fós, go dtí go ghlanfá amach do taisce brabhsála\n",

"welcomecreation" => "<h2>Tá fáilte romhat, a $1!</h2><p>Chruthaíodh do chuntas.
Ná déan dearmad do socruithe phearsanta a gcrích.",

"loginpagetitle" => "Log ann",
"yourname"      => "Do ainm úsáideora",
"yourpassword"  => "Do focal faire",
"yourpasswordagain" => "Athiontráil do focal faire",
"newusersonly"  => " (Do úsáideoirí nua amháin)",
"remembermypassword" => "Cuimhnigh mo focal faire.",
"loginproblem"  => "<b>Bhí fadhb le do logadh ann.</b><br>Déan iarracht eile!",
"alreadyloggedin" => "<font color=red><b>A hÚsáideoir $1, tá tú logtha ann cheana féin!</b></font><br>\n",

"areyounew"     => "Má tá tú i do núíosach chuig an Vicipéid agus tá cuntas úsáideora uait,
iontráil ainm úsáideora, agus ansin iontráil agus athiontráil focal faire.
Tá an seoladh ríomhphoist rud roghnach; dá bhfágfá do focal faire, is feidir leat a iarradh
go seolfar é chuig an seoladh ríomhphoist a thug tú.<br>\n",

"login"     => "Log ann",
"userlogin"     => "Log ann",
"logout"        => "Log as",
"userlogout"    => "Log as",
"notloggedin"   => "Níl tú logtha ann",
"createaccount" => "Cruthaigh cuntas nua",
"createaccountmail" => "le ríomhphost",
"badretype"     => "D'iontráil tú dhá focail faire difriúla.",
"userexists"    => "Tá an ainm úsáideora a d'iontráil tú in úsáid cheana féin. Déan rogha de ainm eile, le do thoil.",
"youremail"     => "Do ríomhphost*",
"yournick"      => "Do leasainm (do síniúithe)",
"emailforlost"  => "* Is roghnach é do seoladh ríomhphoist a iontráil.  Ach ba féidir daoine teagmhail a dhéanamh leat 
tríd an suíomh gan do seoladh ríomhphoist a nochtaigh dóibh. Ina theannta sin,  
is cabhair é má dheanfá dearmad ar do focal faire.",
"loginerror"    => "Earráid leis an log ann",
"noname"        => "Ní shonraigh tú ainm úsáideora bailí.",
"loginsuccesstitle" => "Log ann rathúil",
"loginsuccess"  => "Tá tú logtha ann anois go Vicipéid mar \"$1\".",
"nosuchuser"    => "Níl aon úsáideoir ann leis an ainm \"$1\".
Cinntigh do litriú, nó bain úsáid as an foirm thíos chun cuntas úsáideora nua a chruthaigh.",
"wrongpassword" => "Bhí an focal faire a d'iontráil tú mícheart. Déan iarracht eile le do thoil.",
"mailmypassword" => "Cuir focal faire nua chugam",
"passwordremindertitle" => "Cuimhneachán focail faire ó Vicipéid",
"passwordremindertext" => "D'iarr duine éigin (tusa de réir cosúlachta, ón seoladh IP $1)
go sheolfaimis focal faire Vicipéide nua do log ann duit.
Is é an focal faire don úsáideoir \"$2\" ná \"$3\" anois.
Ba chóir duit log ann anois agus athraigh do focal faire.",
"noemail"       => "Níl aon seoladh ríomhphoist i gcuntas don úsáideoir \"$1\".",
"passwordsent"  => "Cuireadh focal faire nua chuig an seoladh ríomhphoist cláraithe do \"$1\".
Agus atá sé agat, log ann arís leis le do thoil.",

# Edit pages
#
"summary"       => "Achomair",
"subject"       => "Ábhar/ceannlíne",
"minoredit"     => "Seo é mionathrú",
"watchthis"     => "Déan faire ar an leathanach seo",
"savearticle"   => "Sábháil an leathanach",
"preview"       => "Reamhthaispeántas",
"showpreview"   => "Reamhthaispeáin",
"blockedtitle"  => "Tá an úsáideoir seo coiscthe",
"blockedtext"   => "Chuir $1 cosc ar do ainm úsáideora nó do seoladh IP. 
Seo é an cúis a thugadh:<br>''$2''<p>Is féidir leat teagmháil a dhéanamh le $1 nó le ceann eile de na 
[[$wgMetaNamespace:Riarthóirí|riarthóirí]] chun an cosc a phléigh. 

Tabhair faoi deara go nach féidir leat an gné \"cuir ríomhphost chuig an úsáideoir seo\" 
mura bhfuil seoladh ríomhphoist bailí cláraithe i do [[Speisialta:Preferences|socruithe úsáideora]]. 

Is é $3 do sheoladh IP. Más é do thoil é, déan tagairt den seoladh seo le gach ceist a chuirfeá.

==Nóta do úsáideoirí AOL==
De bhrí ghníomhartha leanúnacha creachadóireachta de haon úsáideoir AOL áirithe, 
is minic a coisceann Vicipéid ar friothálaithe AOL. Go mífhortúnach, áfach, is féidir 
go leor úsáídeoirí AOL an friothálaí céanna a úsáid, agus mar sin is minic a coiscaítear 
úsáideoirí AOL neamhchiontacha. Iarraimis pardún do aon trioblóid. 

Má tharlódh an scéal seo duit, cuir ríomhphost chuig riarthóir le seoladh ríomhphoist AOL. Bheith cinnte tagairt a dhéanamh leis an seoladh IP seo thuas.",
"whitelistedittitle" => "Log ann chun athrú a dhéanamh",
"whitelistedittext" => "Caithfidh tú [[Speisialta:Userlogin|log ann]] chun ailt a athraigh.",
"whitelistreadtitle" => "Log ann chun ailt a léigh",
"whitelistreadtext" => "Caithfidh tú [[Speisialta:Userlogin|log ann]] chun ailt a léigh.",
"whitelistacctitle" => "Níl cead agat cuntas a chruthaigh",
"whitelistacctext" => "Chun cuntais nua a chruthaigh san Wiki seo caithfidh tú [[Speisialta:Userlogin|log ann]] agus caithfidh bheith an cead riachtanach agat.",
"accmailtitle" => "Cuireadh an focal faire.",
"accmailtext" => "Cuireadh an focal faire do '$1' chuig $2.",
"newarticle"    => "(Nua)",
"newarticletext" =>
"Lean tú nasc go leathanach a nach bhfuil ann fós. 
Chun an leathanach a chruthaigh, tosaigh ag clóscríobh san bosca anseo thíos 
(féach ar an [[{$wgMetaNamespace}:Cabhair|leathanach cabhrach]] chun níos mó eolas a fháil).
Má tháinig tú anseo as dearmad, brúigh an cnaipe '''ar ais''' ar do líonléitheoir.",
"anontalkpagetext" => "---- ''Seo é an leathanach plé do úsáideoir gan ainm a nach chruthaigh 
cuntas fós nó a nach úsáideann a chuntas. Dá bhrí sin caithfimid an [[seoladh IP]] uimhriúil 
chun é/í a ionannaigh. Is féidir cuid mhaith úsáideoirí an seoladh IP céanna a úsáid. Má tá tú 
i do úsáideoir gan ainm agus má tá sé do thuairim go rinneadh léiriuithe neamhfheidhmeacha fút, 
[[Special:Userlogin|cruthaigh cuntas nó log ann]] le do thoil chun mearbhall le húsáideoirí eile 
gan ainmneacha a héalaigh amach anseo.'' ",
"noarticletext" => "(Níl aon téacs ar an leathanach seo)",
"updated"       => "(Nuashonraithe)",
"note"          => "<strong>Tabhair faoi deara:</strong> ",
"previewnote"   => "Tabhair faoi deara go nach bhfuil seo ach reamhthaispeántas, agus go nach sábháladh é fós!",
"previewconflict" => "San reamhthaispeántas seo, feachann tú an téacs dé réir an eagarbhosca 
thuas mar a taispeáinfear é má sábháilfear é.",
"editing"       => "Ag athraigh $1",
"sectionedit"   => " (roinnt)",
"commentedit"   => " (léiriú)",
"editconflict"  => "Coimhlint athraithe: $1",
"explainconflict" => "D'athraigh duine eile an leathanach seo ó shin a thosaigh tú ag cuireadh é in eagar.
San bhosca thuas feiceann tú téacs an leathanaigh mar a bhfuil sé faoi láthair.
Tá do athruithe san bhosca thíos.
Caithfidh tú do athruithe a chumasadh leis an eagrán atá ann anois.
Nuair a brúann tú ar an cnaipe \"Sábháil an leathanach\", ní sábhálfar <b>ach amháin</b> an téacs san bhosca thuas.\n<p>",
"yourtext"      => "Do téacs",
"storedversion" => "Eagrán i dtaisce",
"editingold"    => "<strong>AIRE: Cuireann tú in eagar eagrán an leathanach seo as dáta.
Má shábhálfá é, caillfear aon athrú a rinneadh ó shin an eagrán seo.</strong>\n",
"yourdiff"      => "Difríochtaí",
"copyrightwarning" => "Tabhair faoi dearadh go scaoilítear gach cúnamh go Vicipéid maidir lena tearmaí an <i>GNU Free Documentation License</i>
(féach ar $1 chun eolas a fháil).
Má nach mian leat go cuirfear do scríbhinn in eagar go héadrócaireach agus go athdálfar é gan teorainn, 
ná tabhair é isteach anseo.<br>
Ina theannta sin, geallann tú duinn go shcríobh tú féin an rud seo, nó go chóipeáil tú é ón 
fhoinse gan chóipcheart.
<strong>NÁ TABHAIR ISTEACH OBAIR LE CÓIPCHEART GAN CEAD!</strong>",
"longpagewarning" => "AIRE: Tá an leathanach seo $1 cilibhirt i bhfad; ní féidir le roinnt líonléitheoirí
leathanaigh breis agus nó níos fada ná 32kb a athraigh.
Meáigh an seans go mbrisfeadh tú an leathanach sna codanna níos bige.",
"readonlywarning" => "AIRE: Cuireadh ghlas ar an bhunachar sonraí, agus mar sin 
ní féidir leat do athruithe a shábháil díreach anois. B'fhéidir go mhaith leat an téacs a 
chóipeáil is a taosaigh go chomhad téacs agus é a shábháil do úsáid níos déanach.",
"protectedpagewarning" => "AIRE:  Cuireadh ghlas ar an leathanach seo, agus is féidir amháin na úsáideoirí le 
pribhléidí ceannasaí é a athraigh. Bí cinnte go leanann tú na 
<a href='/wiki/{$wgMetaNamespace}:Treoirlínte_do_leathanaigh_cosnaithe'>treoirlínte do leathanaigh cosnaithe</a>.",

# History pages
#
"revhistory"    => "Stáir athruithe",
"nohistory"     => "Níl aon stáir athruithe don leathanach seo.",
"revnotfound"   => "Ní bhfuarthas an athrú",
"revnotfoundtext" => "Ní bhfuarthas seaneagrán an leathanagh a d'iarr tú ar. 
Cinntigh an URL a d'úsáid tú chun an leathanach seo a rochtain.\n",
"loadhist"      => "Ag lódáil stáir an leathanaigh",
"currentrev"    => "Eagrán reatha",
"revisionasof"  => "Eagrán ó $1",
"cur"           => "rea",
"next"          => "lea",
"last"          => "roi",
"orig"          => "bun",
"histlegend"    => "Eochair: (rea) = difríocht leis an eagrán reatha,
(roi) = difríocht leis an eagrán roimhe, M = mionathrú",

# Diffs
#
"difference"    => "(Difríochtaí idir eagráin)",
"loadingrev"    => "ag lódáil eagrán don difríocht",
"lineno"        => "Líne $1:",
"editcurrent"   => "Athraigh eagrán reatha an leathanaigh seo",

# Search results
#
"searchresults" => "Toraidh an cuardaigh",
"searchhelppage" => "{$wgMetaNamespace}:Ag_cuardaigh",
"searchingwikipedia" => "Ag cuardaigh sa Vicipéid",
"searchresulttext" => "Chun níos mó eolas a fháil mar gheall ar cuardach Vicipéide, féach ar $1.",
"searchquery"   => "Do cheist \"$1\"",
"badquery"      => "Ceist cuardaigh neamhbhailí",
"badquerytext"  => "Nior éirigh linn do cheist a phróiseáil. 
Is docha go rinne tú cuardach ar focal le níos lú ná trí litir, 
gné a nach bhfuil le tacaíocht aige fós. 
B'fhéidir freisin go mhíchlóshcríobh tú an leagan, mar shampla 
\"éisc agus agus lanna\". Déan iarracht eile.",
"matchtotals"   => "Bhí an cheist \"$1\" ina mhacasamhail le $2 teidil alt
agus le téacs de $3 ailt.",
"nogomatch" => "Níl aon leathanach leis an teideal áirithe seo. Déantar cuardach an téacs ar fad...",
"titlematches"  => "Tá macasamhla teideal alt ann",
"notitlematches" => "Níl macasamhla teideal alt ann",
"textmatches"   => "Tá macasamhla téacs alt ann",
"notextmatches" => "Níl macasamhla téacs alt ann",
"prevn"         => "na $1 roimhe",
"nextn"         => "an chéad $1 eile",
"viewprevnext"  => "Taispeáin ($1) ($2) ($3).",
"showingresults" => "Ag taispeáint thíos <b>$1</b> toraidh, ag tosaigh le #<b>$2</b>.",
"showingresultsnum" => "Ag taispeáint thíos <b>$3</b> toraidh, ag tosaigh le #<b>$2</b>.",
"nonefound"     => "<strong>Tabhair faoi deara</strong>: déantar cuardaigh mírathúla go minic nuair a cuardaítear focail coiteanta, m.sh., \"ag\" is \"an\",
a nach bhfuil innéacsaítear, nó nuair a ceisteann tú níos mó ná téarma amháin (ní
taispeáintear sna toraidh ach na leathanaigh ina bhfuil go leoir na téarmaí cuardaigh).",
"powersearch" => "Cuardaigh",
"powersearchtext" => "
Cuardaigh sna roinn :<br>
$1<br>
$2 Cuir athsheolaidh in aireamh &nbsp; Cuardaigh ar $3 $9",
"searchdisabled" => "<p>Tá brón orainn! Mhíchumasaíodh an ghné lánchuardaigh téacs go sealadach chun luas an suímh 
a chosaint. Idir an dá linn, is féidir leat an cuardach Google anseo thíos a úsáid - b'fhéidir go bhfuil sé as dáta.</p>

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
"prefsnologin" => "Níl tú logtha ann",
"prefsnologintext"  => "Caithfidh tú bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun do socruithe phearsanta a athraigh.",
"prefslogintext" => "Tá tú logtha ann mar \"$1\".
Is é $2 do uimir aitheantais inmhéanach.

Féach ar [[{$wgMetaNamespace}:Cabhair do socruithe úsáideora]] chun cabhair a fháil mar gheall ar na roghanna.",
"prefsreset"    => "D'athraigh do socruithe ar ais chuig an leagan bunúsach, mar gheall ar stóráil.",
"qbsettings"    => "Socruithe an bosca uirlisí", 
"changepassword" => "Athraigh do focal faire",
"skin"          => "Cuma",
"math"          => "Ag aistrigh an matamaitic",
"dateformat"    => "Cruth an dáta",
"math_failure"      => "Theipeadh anailís an foirmle",
"math_unknown_error"    => "earráid anaithnid",
"math_unknown_function" => "foirmle anaithnid ",
"math_lexing_error" => "Theipeadh anailís an foclóra",
"math_syntax_error" => "earráid comhréire",
"saveprefs"     => "Sábháil socruithe",
"resetprefs"    => "Athshuigh socruithe",
"oldpassword"   => "Seanfhocal faire",
"newpassword"   => "Nuafhocal faire",
"retypenew"     => "Athchlóshcríobh an nuafhocal faire",
"textboxsize"   => "Méid an théacsbhosca",
"rows"          => "Sraitheanna",
"columns"       => "Colúin",
"searchresultshead" => "Socruithe na toraidh cuardaigh",
"resultsperpage" => "Taispeáin faighte de réir",
"contextlines"  => "Taispeáin línte de réir",
"contextchars"  => "Taispeáin litreacha de réir",
"stubthreshold" => "Cuir comharthaí ar leathanaigh níos bigé ná",
"recentchangescount" => "Méid teidil sna athruithe deireanacha",
"savedprefs"    => "Sábháladh do socruithe.",
"timezonetext"  => "Iontráil an méid uaireanta a difríonn do am áitiúil den am an friothálaí (UTC).",
"localtime" => "An t-am áitiúil",
"timezoneoffset" => "Difear",
"servertime"    => "Am an friothálaí anois",
"guesstimezone" => "Cuardaigh ón líonléitheoir",
"emailflag"     => "Coisc ríomhphost ón úsáideoirí eile",
"defaultns"     => "Cuardaigh sna ranna seo a los éagmaise:",

# Recent changes
#
"changes" => "athruithe",
"recentchanges" => "Athruithe deireanacha",
"recentchangestext" => 
"Lean na athruithe is deireanacha go Vicipéid ar an leathanach seo.
[[{$wgMetaNamespace}:Fáilte,_a_núíosaigh|Fáilte, a núíosaigh]]!
Féach ar na leathanaigh seo, más é do thoil é: [[{$wgMetaNamespace}:CMT|CMT Vicipéide]],
[[{$wgMetaNamespace}:Polasaithe agus treoirlínte|Polasaí Vicipéide]]
(go háirithe [[{$wgMetaNamespace}:Coinbhinsiúin ainmneacha|coinbhinsiúin ainmneacha]],
[[{$wgMetaNamespace}:Dearcadh neodrach|dearcadh neodrach]]),
agus [[{$wgMetaNamespace}:Na botúin Vicipéide is coitianta|na botúin Vicipéide is coitianta]].

Más maith leat go éireóidh Vicipéid, tá sé an-tabhachtach go nach cuireann tú ábhair 
a nach bhfuil teorainnaithe de na [[{$wgMetaNamespace}:Cóipchearta|cóipchearta]] de ghrúpaí eile.
Ba féidir leis an dliteanas an tionscnamh a gortaigh go fíor, mar sin ná déan é.
Féach ar an [http://meta.wikipedia.org/wiki/Special:Recentchanges meiteaphlé deireanach] freisin.",
"rcloaderr"     => "Ag lódáil athruithe deireanacha",
"rcnote"        => "Is iad seo a leanas na <strong>$1</strong> athruithe deireanacha sna <strong>$2</strong> lae seo caite.",
"rcnotefrom"    => "Is iad seo a leanas na athruithe ó <b>$2</b> (go dti <b>$1</b> taispeánaithe).",
"rclistfrom"    => "Taispeáin nua-athruithe ó $1 anuas",
# "rclinks"     => "Taispeáin na $1 athruithe is deireanacha sna $2 uaire seo caite / $3 laethanta seo caite.",
# "rclinks"     => "Taispeáin na $1 athruithe is deireanacha sna $2 laethanta seo caite.",
"rclinks"       => "Taispeáin na $1 athruithe is deireanacha sna $2 laethanta seo caite; $3 mionathruithe",
"rchide"        => "sa cuma $4; $1 mionathruithe; $2 foranna; $3 athruithe ilchodacha.",
"rcliu"         => "; $1 athruithe de úsáideoirí logtha ann",
"diff"          => "difríochtaí",
"hist"          => "stáir",
"hide"          => "folaigh",
"show"          => "taispeáin",
"tableform"     => "tábla",
"listform"      => "liosta",
"nchanges"      => "$1 athruithe",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"        => "Suaslódáilcomhad",
"uploadbtn"     => "Suaslódáil comhad",
"uploadlink"    => "Suaslódáil íomhánna",
"reupload"      => "Athshuaslódáil",
"reuploaddesc"  => "Fill ar ais chuig an fhoirm shuaslódála.",
"uploadnologin" => "Nil tú logtha ann",
"uploadnologintext" => "Caithfifh tú bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun comhaid a shuaslódáil.",
"uploadfile"    => "Suaslódáil íomhánna, fuaimeanna, doiciméid srl.",
"uploaderror"   => "Earráid suaslódála",
"uploadtext"    => "<strong>STOP!</strong> Roimh a suaslódálaíonn tú anseo, 
bí cinnte leigh agus géill don <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Polasaí_úsáide_íomhá" ) . "\">polasaí úsáide íomhá</a> atá ag Vicipéid.
<p>Má bhfuil aon comhad ann fós leis an ainm céanna a bhfuil tú ag
tabhairt don comhad nua, cuirfear an nuachomhad in ionad an seanchomhad gan fógr.
Mar sin, mura nuashonraíonn tú comhad éigin, is scéal maith é cinntigh má bhfuil comhad 
leis an ainm seo ann fós.
<p>To view or search previously uploaded images,
go to the Dul go dti an<a href=\"" . wfLocalUrlE( "Speisialta:Imagelist" ) .
"\">liosta íomhánna suaslódálaithe</a>chun féach ar nó chuardaigh idir íomhánna a raibh suaslódálaithe roimhe seo.
Déantar liosta de suaslósála agus scriosaidh ar an <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Liosta_suaslódála" ) . "\">liosta suaslódála</a>.
<p>Bain úsáid as an fhoirm anseo thíos chun íomháchomhaid nua a suaslódáil. 
Ba féidir leat na íomhánna a úsáid i do ailt. 
Ar an chuid is mó de na líonléitheoirí, feicfidh tú cnaipe \"Brabhsáil...\" no mar sin. Lé brú ar an cnaipe seo, 
gheobhaigh tú an gháthbhosca agallaimh comhadtheacta do chóras oibriúcháin. 
Nuair a luíonn tú comhad, líonfar ainm an comhaid san téacsbhosca in aice leis an cnaipe.
Caithfidh tú a admháil le brú san bosca beag go nach 
bhfuil tú ag sáraigh aon chóipcheart leis an súaslódáil seo.
Brúigh an cnaipe \"Suaslódáil\" chun an suaslódáil a chríochnaigh.
Mura bhfuil nasc idirlín tapaidh agat, beidh roinnt ama uait chun an rud sin a dhéanamh. 
<p>Is iad na formáide inmholta ná JPEG do íomhánna grianghrafacha, PNG 
do pictiúir tarraingthe agus léaráide, agus OGG do fuaimeanna. 
Ainmnigh do comhaid go tuairisciúil chun mearbhall a héalaigh. 
Chun an íomhá a úsáid san alt, úsáid nasc mar sin:
<b>[[íomhá:comhad.jpg]]</b> nó <b>[[image:íomhá.png|téacs eile]]</b>
nó <b>[[meán:comhad.ogg]]</b> do fuaimeanna.
<p>Tabhair faoi deara go, cosúil le leathanaigh Vicipéide, is féidir le daoine eile do suaslódálacha a 
athraigh nó a scriosadh amach, má síltear go bhfuil sé i gcabhair 
don ciclipéid, agus má bhainfeá mí-úsáid as an córas ta seans go coiscfí tú ón gcóras.",

"uploadlog"     => "liosta suaslódála",
"uploadlogpage" => "Liosta_suaslódála",
"uploadlogpagetext" => "Is liosta é seo a leanas de na suaslódálacha comhad is deireanacha.
Is am an friothálaí (UTC) iad na hamanna atá anseo thíos.
<ul>
</ul>
",
"filename"      => "Ainm comhaid",
"filedesc"      => "Achoimriú",
"filestatus" => "Stádas cóipchirt",
"filesource" => "Foinse",
"affirmation"   => "Dearbhaím go aontaíonn coimeádaí cóipchirt an comhaid seo
chun é a ceadúnaigh de réir na téarmaí an $1.",
"copyrightpage" => "{$wgMetaNamespace}:Cóipchearta",
"copyrightpagename" => "Cóipcheart Vicipéide",
"uploadedfiles" => "Comhaid suaslódálaithe",
"noaffirmation" => "Caithfidh tú a dearbhaigh go nach sáraíonn do suaslódáil
aon cóipchearta.",
"ignorewarning" => "Scaoil tharat an rabhadh agus sábháil an comhad ar aon chaoi.",
"minlength"     => "Caithfidh trí litreacha ar a laghad bheith ann sa ainm íomhá.",
"badfilename"   => "D'athraíodh an ainm íomhá go \"$1\".",
"badfiletype"   => "Níl \".$1\" ina formáid comhaid íomhá inmholta.",
"largefile"     => "Moltar go nach téann comhaid íomhá thar 100k i méid.",
"successfulupload" => "Suaslódáil rathúil",
"fileuploaded"  => "Suaslódáladh an comhad \"$1\" go rathúil.
Lean an nasc seo: ($2) chuig an leathanach cuir sios agus líon isteach
eolas mar gheall ar an comhad, mar shampla cá bhfuair tú é, cathain a 
chruthaíodh é agus rud eile ar bith tá an fhios agat faoi.",
"uploadwarning" => "Rabhadh suaslódála",
"savefile"      => "Sábháil comhad",
"uploadedimage" => "suaslódálaithe \"$1\"",

# Image list
#
"imagelist"     => "Liosta íomhánna",
"imagelisttext" => "Is liosta é seo a leanas de $1 íomhánna, curtha in eagar le $2.",
"getimagelist"  => "ag fáil an liosta íomhánna",
"ilshowmatch"   => "Taispeáin na íomhánna le ainmneacha maith go léir",
"ilsubmit"      => "Cuardaigh",
"showlast"      => "Taispeáin na $1 íomhánna seo caite, curtha in eagar le $2.",
"all"           => "go léir",
"byname"        => "de réir hainm",
"bydate"        => "de réir dáta",
"bysize"        => "de réir méid",
"imgdelete"     => "scrios",
"imgdesc"       => "cur",
"imglegend"     => "Eochair: (cur) = taispeáin/athraigh cur síos an íomhá.",
"imghistory"    => "Stair an íomhá",
"revertimg"     => "ath",
"deleteimg"     => "scr",
"imghistlegend" => "Legend: (rea) = seo é an eagrán reatha, (scr) = scrios an
sean-eagrán seo, (ath) = athúsáid an sean-eagrán seo.
<br><i>Bruigh an dáta chun feach ar an íomhá mar a suaslódálaíodh é ar an dáta sin</i>.",
"imagelinks"    => "Naisc íomhá",
"linkstoimage"  => "Is iad na leathanaigh seo a leanas a nascaíonn chuig an íomhá seo:",
"nolinkstoimage" => "Níl aon leathanach ann a nascaíonn chuig an íomhá seo.",

# Statistics
#
"statistics"    => "Staitistic",
"sitestats"     => "Staitistic suímh",
"userstats"     => "Staitistic úsáideora",
"sitestatstext" => "Is é <b>$1</b> an méid leathanach in iomlán san bunachar sonraí.
Cuirtear san áireamh \"plé\"-leathanaigh, leathanaigh faoi Vicipéid, ailt \"stumpaí\"
íosmhéadacha, athsheolaidh, agus leathanaigh eile a nach cáileann mar ailt.
Ag fágáil na leathanaigh seo as, tá <b>$2</b> leathanaigh ann atá ailt dlisteanacha, is dócha.<p>
In iomlán bhí <b>$3</b> radhairc leathanaigh, agus <b>$4</b> athruithe leathanaigh
ó thus athchóiriú na hoideasra (25 Eanáir, 2004).
Sin é <b>$5</b> athruithe ar meán do gach leathanach, agus <b>$6</b> radhairc do gach athrú.",
"userstatstext" => "Tá <b>$1</b> úsáideoirí cláraithe ann.
Is iad <b>$2</b> de na úsáideoirí seo ina riarthóirí (féach ar $3).",

# Maintenance Page
#
"maintenance"       => "Leathanach coinneála",
"maintnancepagetext"    => "Sa leathanach seo faightear uirlisí éagsúla don gnáthchoinneáil. Is féidir le roinnt 
de na feidhmeanna seo an bunachar sonraí a cuir strus ar, mar sin ná athbhruigh athlódáil tar éis gach mír a 
chríochnaíonn tú ;-)",
"maintenancebacklink"   => "Ar ais go Leathanach Coinneála",
"disambiguations"   => "Leathanaigh easathbhríochais",
"disambiguationspage"   => "{$wgMetaNamespace}:Naisc_go_leathanaigh_easathbhríochais",
"disambiguationstext"   => "Nascaíonn na ailt seo a leanas go <i>leathanach easathbhríochais</i>. Ba chóir dóibh nasc a 
dhéanamh leis an ábhar oiriúnach ina áit.<br>Tugtar an teideal easathbhríochais ar leathanach má bhfuil násc aige 
ó $1.<br><i>Ní</i> cuirtear naisc ó ranna eile ar an liosta seo.",
"doubleredirects"   => "Athsheolaidh Dúbailte",
"doubleredirectstext"   => "<b>Tabhair faoi deara:</b> B'fheidir go bhfuil toraidh bréagacha ar an liosta seo. 
De ghnáth cíallaíonn sé sin go bhfuil téacs breise le naisc thíos san chéad #REDIRECT.<br>\n Sa gach sraith tá 
náisc chuig an chéad is an dara athsheoladh, chomh maith le chéad líne an dara téacs athsheolaidh. De ghnáth 
tugann sé sin an sprioc-alt \"fíor\".",
"brokenredirects"   => "Athsheolaidh Briste",
"brokenredirectstext"   => "Is iad na athsheolaidh seo a leanas a nascaíonn go ailt a nach bhfuil ann.",
"selflinks"     => "Leathanaigh le féin-naisc",
"selflinkstext"     => "Sna leathanaigh seo a leanas tá naisc a nascaíonn chuig an leathanach céanna é fhéin. Seo é flúirseach.",
"mispeelings"           => "Leathanaigh mílitrithe",
"mispeelingstext"               => "Sna leathanaigh seo a leanas tá mílitriú coiteanta, atá san liosta ar $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Naisc Teangacha Ar Iarraidh",
"missinglanguagelinksbutton"    => "Cuardaigh ar naisc teangacha ar iarraidh do",
"missinglanguagelinkstext"      => "<i>Ní</i> nascaíonn na ailt seo chuig a macasamhail sa $1. <i>Ní</i> taispeántar athsheolaidh nó foleathanaigh.",


# Miscellaneous special pages
#
"orphans"       => "Leathanaigh dílleachtacha",
"lonelypages"   => "Leathanaigh dílleachtacha",
"unusedimages"  => "Íomhánna tréigthe",
"popularpages"  => "Leathanaigh coitianta",
"nviews"        => "$1 radhairc",
"wantedpages"   => "Leathanaigh de dhíth",
"nlinks"        => "$1 naisc",
"allpages"      => "Na leathanaigh go léir",
"randompage"    => "Leathanach fánach",
"shortpages"    => "Leathanaigh gearra",
"longpages"     => "Leathanaigh fada",
"listusers"     => "Liosta úsáideoirí",
"specialpages"  => "Leathanaigh speisialta",
"spheading"     => "Leathanaigh speisialta go gach úsáideoir",
"sysopspheading" => "Amháin do ceannasaithe",
"developerspheading" => "Amháin do cláraitheoirí",
"protectpage"   => "Cuir glas ar leathanach",
"recentchangeslinked" => "Athruithe gaolmharas",
"rclsub"        => "(go leathanaigh nasctha ó \"$1\")",
"debug"         => "Bain fabhtanna",
"newpages"      => "Leathanaigh nua",
"ancientpages"      => "Na leathanaigh is sine",
"intl"      => "Naisc idirtheangacha",
"movethispage"  => "Aistrigh an leathanach seo",
"unusedimagestext" => "<p>Tabhair faoi deara go féidir le líonshuímh
eile, m.sh. na Vicipéidí eile, naisc a dhéanamh le íomha le URL díreach, 
agus mar sin beidh siad ar an liosta seo fós cé go bhfuil an íomhá 
in úsáid anois.",
"booksources"   => "Foinsí leabhar",
"booksourcetext" => "Seo é liosta anseo thíos go suímh eile a
díolann leabhair nua agus athdhíolta, agus tá seans go bhfuil eolas
breise acu faoina leabhair a bhfuil tú ag tnuth leis.
Níl Vicipéid comhcheangaltha le aon de na gnóthaí seo, agus ní
aontú leo é an liosta seo.",
"alphaindexline" => "$1 go $2",

# Email this user
#
"mailnologin"   => "Níl aon seoladh maith ann",
"mailnologintext" => "Caithfidh tú bheith  <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
agus bheith le seoladh ríomhphoist bhailí i do chuid <a href=\"" .
  wfLocalUrl( "Speisialta:Preferences" ) . "\">socruithe</a>
más mian leat ríomhphost a chur go úsáideoirí eile.",
"emailuser"     => "Cuir ríomhphost chuig an úsáideoir seo",
"emailpage"     => "Seol ríomhphost",
"emailpagetext" => "Ma d'iontráil an úsáideoir seo seoladh ríomhphoist bhailí 
ina socruithe úsáideora, cuirfidh an foirm anseo thíos teactaireacht amháin do.
Beidh do seoladh ríomhphoist, a d'iontráil tú i do socruithe úsáideora, ann
san bhosca \"Ó\" an riomhphoist, agus mar sin ba féidir léis an faighteoir ríomhphost a chur leatsa.",
"noemailtitle"  => "Níl aon seoladh ríomhphoist ann",
"noemailtext"   => "Níor thug an úsáideoir seo seoladh ríomhphoist bhailí, nó shocraigh sé nach
mian leis ríomhphost a fháil ón úsáideoirí eile.",
"emailfrom"     => "Ó",
"emailto"       => "Go",
"emailsubject"  => "Ábhar",
"emailmessage"  => "Teachtaireacht",
"emailsend"     => "Cuir an ríomhphost",
"emailsent"     => "Ríomhphost curtha",
"emailsenttext" => "Cuireadh do teachtaireacht ríomhphoist go ráthúil.",

# Watchlist
#
"watchlist"     => "Mo liosta faire",
"watchlistsub"  => "(don úsáideoir \"$1\")",
"nowatchlist"   => "Níl aon rud i do liosta faire.",
"watchnologin"  => "Níl tú logtha ann",
"watchnologintext"  => "Caithfidh tú bheith <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun do liosta faire a athraigh.",
"addedwatch"    => "Curtha san liosta faire",
"addedwatchtext" => "Cuireadh an leathanach \"$1\" le do <a href=\"" .
  wfLocalUrl( "Speisialta:Watchlist" ) . "\">liosta faire</a>.
Cuirfear athruithe amach anseo don leathanach sin agus a leathanach phlé leis an liosta ann,
agus beidh <b>cló dubh</b> ar a teideal san <a href=\"" .
  wfLocalUrl( "Speisialta:Recentchanges" ) . "\">liosta den athruithe deireanacha</a> sa chaoi go 
bhfeicfeá iad go héasca.</p>

<p>Más mian leat an leathanach a bain amach do liosta faire níos déanaí, brúigh ar \"Stop ag faire\" ar an taobhbharra.",
"removedwatch"  => "Bainthe amach ón liosta faire",
"removedwatchtext" => "Baineadh an leathanach \"$1\" amach ó do liosta faire.",
"watchthispage" => "Faire ar an leathanach seo",
"unwatchthispage" => "Stop ag faire",
"notanarticle"  => "Níl alt ann",
"watchnochange" => "Níor athraíodh aon de na leathanaigh i do liosta faire taobh istigh den am socraithe.",
"watchdetails" => "(Tá tú ag faire ar $1 leathanaigh chomh maith leis na leathanaigh phlé;
le déanach athraíodh $2 leathanaigh in iomlán;
$3...
<a href='$4'>athraigh do liosta</a>.)",
"watchmethod-recent" => "ag seiceáil na athruithe deireanacha do leathanaigh faire",
"watchmethod-list" => "ag seiceáil na leathanaigh faire do athruithe deireanacha",
"removechecked" => "Bain míreanna marcálaithe amach as do liosta faire",
"watchlistcontains" => "Tá $1 leathanaigh i do liosta faire.",
"watcheditlist" => "Seo liosta na leathanaigh i do liosta faire, in ord aibitre. 
Marcáil boscaí de na leathanaigh atá le baint amach an liosta faire, agus bruigh 
an cnaipe 'bain amach le marcanna' ag bun an leathanaigh.",
"removingchecked" => "Ag baint amach na míreanna ón liosta faire mar a iarraidh...",
"couldntremove" => "Níor baineadh amach an mír '$1'...",
"iteminvalidname" => "Fadhb leis an mír '$1', ainm neamhbhailí...",
"wlnote" => "Seo iad na $1 athruithe seo caite sna <b>$2</b> uaire seo caite.",
"wlshowlast" => "Taispeáin na míreanna deireanacha $1 uaire $2 laethanta $3", #FIXME

# Delete/protect/revert
#
"deletepage"    => "Scrios leathanach",
"confirm"       => "Cinntigh",
"excontent" => "sin a raibh an ábhar:",
"exbeforeblank" => "sin a raibh an ábhar roimh an folmhadh:",
"exblank" => "bhí an leathanach folamh",
"confirmdelete" => "Cinntigh an scriosadh",
"deletesub"     => "(Ag scriosadh \"$1\")",
"historywarning" => "Aire: Ta stair ag an leathanach a bhfuil tú ar tí é a scrios: ",
"confirmdeletetext" => "Tá tú ar tí leathanach nó íomhá a scrios, 
chomh maith leis a chuid stair, ón bunachar sonraí. 
Cinntigh go mian leis an méid seo a dhéanamh, go dtuigeann tú na
iarmhairtaí, agus go ndéanann tú é dar leis [[{$wgMetaNamespace}:Polasaí]].",
"confirmcheck"  => "Sea, is mian liom go fírinneach an rud seo a scrios.",
"actioncomplete" => "Gníomh déanta",
"deletedtext"   => "\"$1\" atá scriosaithe.
Féach ar $2 chun cuntas den scriosadh deireanacha a fháil.",
"deletedarticle" => "scriosadh \"$1\"",
"dellogpage"    => "Cuntas_scriosaidh",
"dellogpagetext" => "Seo é liosta de na scriosaidh is deireanacha. 
Is am an friothálaí (UTC) iad na hamanna atá anseo thíos.
<ul>
</ul>
",
"deletionlog"   => "cuntas scriosaidh",
"reverted"      => "Tá eagrán níos luaithe in úsáid anois",
"deletecomment" => "Cúis do scriosadh",
"imagereverted" => "D'éirigh le athúsáid go eagrán níos luath.",
"rollback"      => "Aththosaigh athruithe", #FIXME
"rollbacklink"  => "athúsáid",
"rollbackfailed" => "Theipeadh an athúsáid",
"cantrollback"  => "Ní féidir an athrú a áthúsáid; ba é údar an ailt an aon scríbhneoir atá ann.",
"alreadyrolled" => "Ní féidir eagrán níos luath an leathanach [[$1]] 
le [[Úsáideoir:$2|$2]] ([[Plé úsáideora:$2|Plé]]) a athúsáid; d'athraigh duine eile é fós nó 
d'athúsáid duine eile eagrán níos luaithe fós.

Ba é [[Úsáideoir:$3|$3]] ([[Plé úsáideora:$3|Plé]]) an té a rinne an athrú seo caite. ",
#   only shown if there is an edit comment
"editcomment" => "Seo a raibh an mínithe athraithe: \"<i>$1</i>\".", 
"revertpage"    => "D'athúsáideadh an athrú seo caite le $1",
"protectlogpage" => "Cuntas_cosanta",
"protectlogtext" => "Seo é liosta de glais a cuireadh ar / baineadh de leathanaigh.
Féach ar [[$wgMetaNamespace:Leathanach faoi ghlas]] chun níos mó eolais a fháil.",
"protectedarticle" => "faoi ghlas [[$1]]",
"unprotectedarticle" => "gan ghlas [[$1]]",

# Undelete
"undelete" => "Cuir leathanach scriosaithe ar ais",
"undeletepage" => "Féach ar agus cuir ar ais leathanaigh scriosaithe",
"undeletepagetext" => "Scriosaíodh na leathanaigh seo a leanas cheana, ach
tá síad ann fós san cartlann agus is féidir iad a cuir ar ais. 
Ó am go ham, is féidir leis an cartlann bheith folmhaithe.",
"undeletearticle" => "Cuir alt scriosaithe ar ais",
"undeleterevisions" => "Cuireadh $1 athbhreithniuthe sa chartlann",
"undeletehistory" => "Má chuirfeá ab leathanach ar ais, cuirfear ar ais gach athbhreithniú chuig an stair.
Má chruthaíodh leathanach nua leis an ainm céanna ó shin an scriosadh, taispeáinfear
na sean-athruithe san stair roimhe seo, agus ní athshuighfear an eagrán reatha an leathanaigh go huathoibríoch.",
"undeleterevision" => "Athbhreithniú scriosaithe den dáta $1",
"undeletebtn" => "Cuir ar ais!",
"undeletedarticle" => "cuireadh \"$1\" ar ais",
"undeletedtext"   => "Cuireadh an alt [[$1]] ar ais go rathúil.
Féach ar [[{$wgMetaNamespace}:Cuntas_scriosaidh]] chun cuntas de scriosaidh agus athchóirithe deireanacha a fháil.",

# Contributions
#
"contributions" => "Dréachtaí úsáideora",
"mycontris" => "Mo dréachtaí",
"contribsub"    => "Do $1",
"nocontribs"    => "Níor bhfuarthas aon athrú a raibh cosúil le na crítéir seo.",
"ucnote"        => "Is iad seo thíos na <b>$1</b> athruithe is deireanaí an úsáideora sna <b>$2</b> lae seo caite.",
"uclinks"       => "Féach ar na $1 athruithe is deireanaí; féach ar na $2 lae seo caite.",
"uctop"     => " (barr)" ,

# What links here
#
"whatlinkshere" => "Cad a nascaíonn anseo",
"notargettitle" => "Níl aon sprioc ann",
"notargettext"  => "Níor thug tú leathanach nó úsáideoir sprice 
chun an gníomh seo a dhéanamh ar.",
"linklistsub"   => "(Liosta nasc)",
"linkshere"     => "Nascaíonn na leathanaigh seo a leanas chuig an leathanach seo:",
"nolinkshere"   => "Ní nascaíonn aon leathanach chuig an leathanach seo.",
"isredirect"    => "Leathanach athsheolaidh",

# Block/unblock IP
#
"blockip"       => "Coisc úsáideoir",
"blockiptext"   => "Úsáid an foirm anseo thíos chun bealach scríofa a chosc ó 
seoladh IP nó ainm úsáideora áirithe.
Is féidir leat an rud seo a dhéanamh amháin chun an chreachadóireacht a chosc, de réir
mar a deirtear san [[{$wgMetaNamespace}:Polasaí|polasaí Vicipéide]].
Líonaigh cúis áirithe anseo thíos (mar shampla, is féidir leat a luaigh
leathanaigh áirithe a rinne an duine damáiste ar).",
"ipaddress"     => "Seoladh IP / ainm úsáideora",
"ipbreason"     => "Cúis",
"ipbsubmit"     => "Coisc an úsáideoir seo",
"badipaddress"  => "Níl aon úsáideoir ann leis an ainm seo.",
"noblockreason" => "Caithfidh tú cúis a thabhairt don cosc.",
"blockipsuccesssub" => "D'éirigh leis an cosc",
"blockipsuccesstext" => "Choisceadh \"$1\".
<br>Féach ar [[Speisialta:Ipblocklist|Liosta coisc IP]] chun coisc a athbhreithnigh.",
"unblockip"     => "Bain an cosc den úsáideoir",
"unblockiptext" => "Úsáid an foirm anseo thíos chun bealach scríofa a thabhairt ar ais go seoladh 
IP nó ainm úsáideora a raibh coiscthe roimhe seo.",
"ipusubmit"     => "Bain an cosc den seoladh seo",
"ipusuccess"    => "\"$1\" gan cosc",
"ipblocklist"   => "Liosta seoltaí IP agus ainmneacha úsáideoirí coiscthe",
"blocklistline" => "$1, $2 a choisc$3",
"blocklink"     => "Coisceadh",
"unblocklink"   => "bain an cosc den",
"contribslink"  => "dréachtaí",
"autoblocker"   => "Coiscthe go sealadach go huathoibríoch de bhrí go roinneann tú an seoladh IP céanna le \"$1\". Cúis \"$2\".",
"blocklogpage"  => "Cuntas_coisc",
"blocklogentry" => 'coisceadh "$1"',
"blocklogtext"  => "Seo é cuntas de gníomhartha coisc úsáideoirí agus míchoisc úsáideoirí. Ní cuirtear
seoltaí IP a raibh coiscthe go huathoibríoch ar an liosta seo. Féach ar an [[Speisialta:Ipblocklist|Liosta coisc IP]] chun
liosta a fháil de coisc atá i bhfeidhm faoi láthair.",
"unblocklogentry"   => 'baineadh an cosc den "$1"',

# Developer tools
#
"lockdb"        => "Cuir glas ar an bunachar sonraí",
"unlockdb"      => "Bain an glas den bunachar sonraí",
"lockdbtext"    => "Má chuirfeá glas ar an bunachar sonraí, ní beidh cead ar aon úsáideoir
leathanaigh a athraigh, a socruithe a athraigh, a liostaí faire a athraigh, nó rudaí eile a thrachtann le 
athruithe san bunachar sonraí.
Cinntigh go bhfuil an scéal seo d'intinn agat, is go bainfidh tú an glas den bunachar sonraí nuair a bhfuil 
do chuid coinneála déanta.",
"unlockdbtext"  => "Má bhainfeá an glas den bunachar sonraí, beidh ceat ag gach úsáideoirí aris
na leathanaigh a cuir in eagar, a socruithe a athraigh, a liostaí faire a athraigh, agus rudaí eile
a dhéanamh a thrachtann le athruithe san bunachar sonraí. 
Cinntigh go bhfuil an scéal seo d'intinn agat.",
"lockconfirm"   => "Sea, is mian liom glas a chur ar an bunachar sonraí.",
"unlockconfirm" => "Sea, is mian liom glas a bhain den bunachar sonraí.",
"lockbtn"       => "Cuir glas ar an bunachar sonraí",
"unlockbtn"     => "Bain an glas den bunachar sonraí",
"locknoconfirm" => "Níor mharcáil tú an bosca daingnithe.",
"lockdbsuccesssub" => "D'éirigh le glas an bunachair sonraí",
"unlockdbsuccesssub" => "Baineadh an glas den bunachar sonraí",
"lockdbsuccesstext" => "Cuireadh glas ar an bunachar sonraí Vicipéide.
<br>Cuimhnigh go caithfidh tú an glas a bhaint tar éis do chuid coinneála.",
"unlockdbsuccesstext" => "Baineadh an glas den bunachar sonraí Vicipéide.",

# SQL query
#
"asksql"        => "Ceist SQL",
"asksqltext"    => "Úsáid an foirm anseo thíos chun ceist díreach a dhéanamh den bunachar sonraí Vicipéide. 
Úsáid comharthaí athfhriotail singile ('mar sin') chun teorainn a chur le litriúla sraithe. Úsáid an gné seo go coigilteach.",
"sqlislogged"   => "Tabhair faoi deara go cuirtear gach ceist i gcuntas.",
"sqlquery"      => "Iontráil ceist",
"querybtn"      => "Cuir ceist",
"selectonly"    => "Níl na ceisteanna ina theannta \"SELECT\" ann ach amháin do 
cláraitheoirí Vicipéide.",
"querysuccessful" => "D'éirigh leis an ceist",

# Move page
#
"movepage"      => "Aistrigh an leathanach",
"movepagetext"  => "Úsáis an foirm anseo thíos chun leathanach a athainmnigh. Aistreofar a chuid
stair go léir chuig an ainm nua.
Déanfar leathanach athsheolaidh den sean-teideal chuig an teideal nua.
Ní athreofar naisc chuig sean-teideal an leathanach. Bheith cinnte chun 
[[Special:Maintenance|cuardach]] a dhéanamh ar athsheolaidh dubáilte nó briste.
Tá tú freagrach i cinnteach go leanann naisc chuig an pointe a bhfuil siad ag aimsigh ar.

Tabhair faoi deara go '''nach''' aistreofar an leathanach má bhfuil leathanach 
ann cheana ag an teideal nua, mura bhfuil sé folamh nó athsheoladh nó mura bhfuil aon 
stair athraithe aige cheana. Ciallaíonn sé sin go féidir leat leathanach a athainmnigh ar ais
chuig an áit ina raibh sé roimhe má dhéanfá botún, agus ní féidir leat leathanach atá ann a forshcriobh ar.

<b>AIRE!</b>
Is féidir leis an méid seo bheith athrú borb gan súil leis do leathanach coitianta;
cinntigh go dtuigeann tú na iarmhairtí go léir roimh a leanfá.",
"movepagetalktext" => "Aistreofar an leathanach phlé leis, má tá sin ann:
*'''mura''' bhfuil tú ag aistrigh an leathanach trasna ranna,
*'''mura''' bhfuil leathanach phlé neamhfholamh ann leis an ainm nua, nó
*'''mura''' baineann tú an marc den bosca anseo thíos.

Sna scéil sin, caithfidh tú an leathanach a aistrigh nó a báigh leis na lámha má tá sin an rud atá uait.",
"movearticle"   => "Aistrigh an leathanach",
"movenologin"   => "Níl tú logtha ann",
"movenologintext" => "Caithfidh tú bheith úsáideoir cláraithe agus <a href=\"" .
  wfLocalUrl( "Speisialta:Userlogin" ) . "\">logtha ann</a>
chun leathanach a aistrigh.",
"newtitle"      => "Go teideal nua",
"movepagebtn"   => "Aistrigh an leathanach",
"pagemovedsub"  => "D'éirigh leis an aistriú",
"pagemovedtext" => "D'aistraíodh an leathanach \"[[$1]]\" go \"[[$2]]\".",
"articleexists" => "Tá leathanach leis an ainm seo ann fós, nó níl an
ainm a rinne tú rogha air ina ainm bailí.
Toghaigh ainm eile le do thoil.",
"talkexists"    => "D'aistraíodh an leathanach é féin go rathúil, ach ní raibh sé ar a chumas an 
leathanach phlé a aistrigh de bhrí go bhfuil ceann ann fós ag an
teideal nua. Báigh iad go láimhe le do thoil.",
"movedto"       => "aistraithe go",
"movetalk"      => "Aistrigh an leathanach \"phlé\" freisin, má bhfuil an leathanach sin ann.",
"talkpagemoved" => "D'aistraíodh an leathanach phlé frithiontráil.",
"talkpagenotmoved" => "<strong>Níor</strong> aistraíodh an leathanach phlé frithiontráil.",

"export"        => "Onnmhairigh leathanaigh",
"exporttext"    => "Is féidir leat an téacs agus stair athraithe de leathanach áirithe a onnmhairiú, 
fillte i bpíosa XML; is féidir leat ansin é a iompórtál isteach wiki eile atá le na oideasra MediaWiki
air, nó is féidir leat é a coinnigh do do siamsa féin.",
"exportcuronly" => "Ná cuir san áireamh ach an eagrán reatha, ná cuir ann an stair in iomlán",

# Namespace 8 related

"allmessages"   => "Teachtaireachtaí_go_léir",
"allmessagestext"   => "Seo é liosta de na teachtaireachtaí go léir atá le fáil san roinn MediaWiki: ."
);

include_once( "LanguageUtf8.php" );

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

    function getMonthName( $key )
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
