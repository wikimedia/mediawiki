<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageFr.php' );

/* private */ $wgNamespaceNamesBr = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Dibar',
	NS_MAIN				=> '',
	NS_TALK				=> 'Kaozeal',
	NS_USER				=> 'Implijer',
	NS_USER_TALK		=> 'Kaozeadenn_Implijer',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Kaozeadenn_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Skeudenn',
	NS_IMAGE_TALK		=> 'Kaozeadenn_Skeudenn',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Kaozeadenn_MediaWiki',
	NS_TEMPLATE			=> 'Patrom',
	NS_TEMPLATE_TALK	=> 'Kaozeadenn_Patrom',
	NS_HELP				=> 'Skoazell',
	NS_HELP_TALK		=> 'Kaozeadenn_Skoazell',
	NS_CATEGORY			=> 'Rummad',
	NS_CATEGORY_TALK	=> 'Kaozeadenn_Rummad'
) + $wgNamespaceNamesFr;

/* private */ $wgQuickbarSettingsBr = array(
	'Hini ebet', 'Kleiz', 'Dehou', 'War-neuñv a-gleiz'
);

/* private */ $wgSkinNamesBr = array(
	'standard'		=> 'Standard',
	'nostalgia'		=> 'Melkoni',
	'cologneblue'	=> 'Glaz Kologn',
	'smarty'		=> 'Paddington',
	'montparnasse'	=> 'Montparnasse'
) + $wgSkinNamesFr;

/* private */ $wgBookstoreListBr = $wgBookstoreListFr;

/* private */ $wgAllMessagesBr = array(

# User Toggles

'tog-editwidth' => 'Digeriñ ar prenestr aozañ en e led brasañ',
'tog-editondblclick' => 'Daouglikañ evit kemmañ ur bajenn (JavaScript)',
'tog-editsection'	=> 'Kemmañ ur rann dre al liammoù [kemmañ]',
'tog-editsectiononrightclick'	=> 'Kemmañ ur rann dre glikañ a-zehou<br /> war titl ar rann',
'tog-fancysig' => 'Sinadurioù diliamm (hep liamm emgefre)',
'tog-hideminor' => 'Kuzhat ar <i>C\'hemmoù nevez</i> dister',
'tog-highlightbroken' => 'Lakaat e ruz al liammoù war-du<br /> an danvezioù n\'eus ket anezho',
'tog-justify' => 'Rannbennadoù marzhekaet',
'tog-minordefault' => 'Sellet ouzh ar c\'hemmoù degaset ganin<br /> evel kemmoù dister dre ziouer',
'tog-nocache' => 'Diweredekaat krubuilh ar pajennoù',
'tog-numberheadings' => 'Niverenniñ emgefre an titloù',
'tog-previewonfirst' => 'Rakdiskouez tres ar bajenn kerkent hag an aozadenn gentañ',
'tog-previewontop' => 'Rakdiskouezet e vo tres ar bajenn<br /> a-us ar voest skridaozañ',
'tog-rememberpassword' => 'Derc\'hel soñj eus ma ger-temen (toupin)',
'tog-showtoc'	=> 'Diskwel an daolenn<br /> (evit ar pennaodù zo ouzhpenn 3 rann enno)',
'tog-showtoolbar' => 'Diskouez ar varrenn gant meuzioù an aozañ',
'tog-usenewrc' => 'Kemmoù nevez gwellaet<br /> (gant merdeerioù zo hepken)',
'tog-underline' => 'Liammoù islinennet',
'tog-watchdefault' => 'Heuliañ ar pennadoù savet pe kemmet ganin',


# Dates

'sunday' => 'Sul',
'monday' => 'Lun',
'tuesday' => 'Meurzh',
'wednesday' => 'Merc\'her',
'thursday' => 'Yaou',
'friday' => 'Gwener',
'saturday' => 'Sadorn',
'january' => 'Genver',
'february' => 'C\'hwevrer',
'march' => 'Meurzh',
'april' => 'Ebrel',
'may_long' => 'Mae',
'june' => 'Mezheven',
'july' => 'Gouere',
'august' => 'Eost',
'september' => 'Gwengolo',
'october' => 'Here',
'november' => 'Du',
'december' => 'Kerzu',
'jan' => 'Gen',
'feb' => 'C\'hwe',
'mar' => 'Meu',
'apr' => 'Ebr',
'may' => 'Mae',
'jun' => 'Mez',
'jul' => 'Gou',
'aug' => 'Eos',
'sep' => 'Gwe',
'oct' => 'Her',
'nov' => 'Du',
'dec' => 'Kzu',


# Bits of text used by many pages:
#
'categories'	=> 'Rummadoù ar bajenn',
'category'	=> 'rummad',
'category_header' => 'Niver a bennadoù er rummad "$1"',
'subcategories'	=> 'Isrummad',
'uncategorizedcategories' => 'Rummadoù hep rummadoù',
'uncategorizedpages' => 'Pajennoù hep rummad ebet',
'subcategorycount' => '$1 isrummad zo d\'ar rummad-mañ.',
'subcategorycount1' => '$1 isrummad zo d\'ar rummad-mañ.',

'allarticles'   => 'An holl bennadoù',
'linktrail'     => "/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙ]+)(.*)$/sD",
'mainpage'      => 'Degemer',
'mainpagetext'	=> 'Meziant {{SITENAME}} staliet.',
'portal'        => 'Porched ar gumuniezh',
'portal-url'	=> '{{ns:4}}:Degemer',
'about'         => 'Diwar-benn',
'aboutsite'     => 'Diwar-benn {{SITENAME}}',
'aboutpage'     => '{{ns:4}}:Diwar-benn',
'article'       => 'Pennad',
'help'          => 'Skoazell',
'helppage'      => '{{ns:4}}:Skoazell',
'bugreports'    => 'Teul ar fazioù',
'bugreportspage' => '{{ns:4}}:Teul ar fazioù',
'sitesupport'	=> 'Skoazellañ dre reiñ un dra bennak',
'faq'           => 'FAG',
'faqpage'       => '{{ns:4}}:FAG',
'edithelp'      => 'Skoazell',
'edithelppage'  => '{{ns:4}}:Penaos degas kemmoù en ur bajenn',
'cancel'        => 'Nullañ',
'qbfind'        => 'Klask',
'qbbrowse'      => 'Furchal',
'qbedit'        => 'Kemmañ',
'qbpageoptions' => 'Pajenn dibaboù',
'qbpageinfo'    => 'Pajenn gelaouiñ',
'qbmyoptions'   => 'Ma dibaboù',
'qbspecialpages'	=> 'Pajennoù dibar',
'moredotdotdot'	=> 'Ha muioc\'h c\'hoazh...',
'mypage'        => 'Ma fajenn',
'mytalk'        => 'Ma c\'haozeadennoù',
'anontalk'	=> 'Kaozeal gant ar chomlec\'h ip-mañ',
'navigation'	=> 'Merdeiñ',
'currentevents' => 'Keleier',
'disclaimers'	=> 'Kemennoù',
'disclaimerpage' => '{{ns:4}}:Kemenn hollek',
'errorpagetitle' => 'Fazi',
'returnto'      => 'Distreiñ d\'ar bajenn $1.',
'tagline'       => 'Ur pennad tennet eus {{SITENAME}}, ar c\'helgeriadur digor.',
'whatlinkshere' => 'Daveennoù d\'ar bajenn-mañ',
'help'          => 'Skoazell',
'search'        => 'Klask',
'history'       => 'Istor',
'printableversion' => 'Doare da voullañ',
'edit'		=> 'Kemmañ',
'editthispage'  => 'Kemmañ ar bajenn-mañ',
'delete'	=> 'Diverkañ',
'deletethispage' => 'Diverkañ ar bajenn-mañ',
'undelete_short' => 'Diziverkañ',
'undelete_short1' => 'Diziverkañ',
'protect' => 'Gwareziñ',
'protectthispage' => 'Gwareziñ ar bajenn-mañ',
'unprotect' => 'Diwareziñ',
'unprotectthispage' => 'Diwareziñ ar bajenn-mañ',
'newpage'       => 'Pajenn nevez',
'talkpage'      => 'Pajenn gaozeal',
'specialpage'	=> 'Pajen dibar',
'personaltools'	=> 'Ostilhoù personel',
'postcomment'	=> 'Ouzhpennañ e soñj',
'addsection'   => '+',
'articlepage'	=> 'Sellet ouzh ar pennad',
'subjectpage'   => 'Pajenn danvez',
'talk'		=> 'Kaozeadenn',
'toolbox'	=> 'Boest ostilhoù',
'userpage'      => 'Pajenn implijer',
'wikipediapage' => 'Pajenn meta',
'imagepage'     => 'Pajenn skeudenn',
'viewtalkpage'  => 'Pajenn gaozeal',
'otherlanguages' => 'Yezhoù all',
'redirectedfrom' => '(Adkaset adal $1)',
'lastmodified'  => 'Kemmoù diwezhañ degaset d\'ar bajenn-mañ : $1.',
'viewcount'     => 'Sellet ez eus bet ouzh ar bajenn-mañ $1 (g)wech.',
'copyright'	=> 'Danvez a c\'haller implijout dindan $1.',
'printsubtitle' => '(eus {{SERVER}})',
'protectedpage' => 'Pajenn warezet',
'administrators' => '{{ns:4}}:Merourien',
'sysoptitle'    => 'Moned merour dre ret',
'sysoptext'     => 'Ni\'hall ar pezh hoc\'h eus klasket seveniñ bezañ graet nemet gant un implijer gantañ ar statud "Merour".
Sellet ouzh $1.',
'developertitle' => 'Moned diorroer dre ret',
'developertext' => 'N\'hall ar pezh hoc\'h eus klasket seveniñ bezañ graet nemet gant un implijer gantañ ar statud "Diorroer".
Voir $1.',
'nbytes'        => '$1 eizhbit',
'go'            => 'Kas',
'ok'            => 'Mat eo',
'pagetitle'	=> '$1 - {{SITENAME}}',
'history'	=> 'Istor ar bajenn',
'history_short' => 'Istor',
'sitetitle'     => '{{SITENAME}}',
'sitesubtitle'  => 'Ar C\'helc\'hgeriadur digor',
'retrievedfrom' => 'Adtapet diwar « $1 »',
'newmessageslink' => 'Kemennoù nevez',
'newmessages'   => 'zo ganeoc\'h $1.',
'editsection'	=> 'kemmañ',
'toc'		=> 'Taolenn',
'showtoc'	=> 'diskwel',
'hidetoc'	=> 'kuzhat',
'thisisdeleted' => 'Diskwel pe diziverkañ $1 ?',
'restorelink'	=> '1 c\'hemm diverket',
'feedlinks'	=> 'Lusk:',
'sitenotice'	=> '-', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Pennad',
'nstab-user' => 'Pajenn implijer',
'nstab-media' => 'Media',
'nstab-special' => 'Dibar',
'nstab-wp' => 'Diwar-benn',
'nstab-image' => 'Skeudenn',
'nstab-mediawiki' => 'Kemennadenn',
'nstab-template' => 'Patrom',
'nstab-help' => 'Skoazell',
'nstab-category' => 'Rummad',

# Main script and global functions
#
'nosuchaction'	=> 'Ober dianv',
'nosuchactiontext' => 'N\'eo ket anavezet gant ar wiki an ober spisaet en Url.',
'nosuchspecialpage' => 'N\'eus ket eus ar bajenn dibar-mañ',
'nospecialpagetext' => 'Goulennet hoc\'h eus ur bajenn dibar n\'eo ket anavezet gant ar wiki.',

# General errors
#
'error'		=> 'Fazi',
'badaccess' => 'Fazi aotre',
'badaccesstext' => 'Miret eo an ober goulennet evit an implijerien ganto ar gwir "$2".
Sellet ouzh $1',
'databaseerror' => 'Fazi bank roadennoù',
'dberrortext'	=> "Fazi ereadur er bank roadennoù. Setu ar goulenn bet pledet gantañ da ziwezhañ :
<blockquote><tt>$1</tt></blockquote>
adal an arc\'hwel \"<tt>$2</tt>\".
adkaset eo bet ar fazi \"<tt>$3: $4</tt>\" gant MySQL.",
'dberrortextcl' => 'Ur fazi ereadur zo en ur goulenn graet ouzh ar bank roadennoù. Setu ar goulenn bet pledet gantañ da ziwezhañ :
"$1"
graet gant an arc\'hwel "$2"
adkaset eo bet ar fazi "$3 : $4" gant MySQL.',
'noconnect'	=> "Ho tigarez! Da-heul kudennoù teknikel,n\'haller ket kevreañ ouzh ar bank roadennoù evit poent.", //"Dibosupl kevreañ ouzh ar bank roadennoù war $1",
'nodb'		=> 'Dibosupl dibab ar bank roadennoù $1',
'cachederror'	=> 'Un eilenn eus ar bajenn goulennet eo homañ; marteze n\'eo ket bet hizivaet',
'readonly'	=> 'Hizivadurioù stanket war ar bank roadennoù',
'enterlockreason' => 'Merkit perak eo stanket hag istimit pegeit e chomo evel-henn ',
'readonlytext'	=> "Stanket eo an ouzhpennadennoù hag an hizivadurioù war bank roadennoù {{SITENAME}}; moarvat p'emeur o trezerc'\hel ar bank. Goude-se e vo plaen pep tra en-dro. Setu perak eo bet stanket ar bank gant ar merour :
<p>$1",
'missingarticle' => 'N\'eo ket bet ar bank roadennoù evit kavout testenn ur bajenn zo anezhi c\'hoazh gant an titl "$1".
N\'eo ket ur fazi gant ar bank roadennoù, un draen gant wiki marteze a-walc\'h.
Kasit, ni ho ped, keloù eus ar fazi-mañ d\'ur merer en ur verkañ mat dezhañ chomlec\'h ar bajenn e kaoz.',
'internalerror' => 'Fazi diabarzh',
'filecopyerror' => 'Dibosupl eilañ « $1 » war-du « $2 ».',
'fileinfo' => '$1Ko, seurt MIME: <tt>$2</tt>',
'filerenameerror' => 'Dibosupl da adenvel « $1 » e « $2 ».',
'filedeleteerror' => 'Dibosupl da ziverkañ « $1 ».',
'filenotfound'	=> 'N\'haller ket kavout ar restr "$1".',
'unexpected' => 'Talvoudenn dic\'hortoz : "$1"="$2".',
'formerror'	=> 'Fazi: Dibosupl eo kinnig ar furmskrid',
'badarticleerror' => 'N\'haller ket seveniñ an ober-mañ war ar bajenn-mañ.',
'cannotdelete'	=> "Dibosupl da ziverkañ ar bajenn pe ar skeudenn spisaet.",
'badtitle'	=> 'Titl fall',
'badtitletext'	=> 'Faziek pe c\'houllo eo titl ar bajenn goulennet; pe neuze eo faziek al liamm etreyezhel',
'laggedslavemode' => 'Diwallit : marteze a-walc\'h n\'emañ ket ar c\'hemmoù diwezhañ war ar bajenn-mañ',
'readonly_lag' => 'Stanket eo bet ar bank roadennoù ent emgefre e-keit ha m\'emañ an eilservijeriù oc\'h adpakañ o dale e-keñver ar pennservijer',
'perfdisabled' => 'Ho tigarez! Diweredekaet eo bet an arc\'hwel-mañ evit poent rak gorrekaat a ra ar bank roadennoù kement ha ma n\'hall ket mui den implijout ar wiki.',
'perfdisabledsub' => 'Setu aze un eilenn savete eus $1:',
'viewsource'	=> 'Sellet ouzh tarzh an destenn',
'protectedtext'	=> 'Stanket eo bet ar bajenn-mañ evit ma ne vo ket degaset kemmoù warni ken. Sellet ouzh [[{{ns:4}}:Pajenn warezet]] evit gwelet an abegoù a c\'hall bezañ.',
'allmessagesnotsupportedDB' => 'N\'haller ket kaout Special:AllMessages rak diweredekaet eo bet wgUseDatabaseMessages.',
'allmessagesnotsupportedUI' => 'Ne zegemer ket Special:AllMessages yezh hoc\'h etrefas (<b>$1</b>) war al lec\'hienn-mañ.',
'wrong_wfQuery_params' => 'Arventennoù faziek war an urzhiad wfQuery()<br />
Arc\'hwel : $1<br />
Goulenn : $2',
'versionrequired' => 'Rekis eo Doare $1 MediaWiki',
'versionrequiredtext' => 'Rekis eo doare $1 MediaWiki evit implijout ar bajenn-mañ. Sellit ouzh [[Special:Version]]',


# Login and logout pages
#
'logouttitle'	=> 'Dilugañ',
'logouttext'	=> "Diluget oc\'h bremañ.
Gallout a rit kenderc\'hel da implijout {{SITENAME}} en un doare dizanv, pe en em lugañ en-dro gant un anv all mar fell deoc\'h.\n",

'welcomecreation' => "<h2>Degemer mat, $1!</h2><p>Krouet eo bet ho kont implijer.
Na zisoñjit ket da bersonelaat ho {{SITENAME}} en ur sellet ouzh ar bajenn Personelaat.",

'loginpagetitle'     => 'Ho tisklêriadenn',
'yourname'           => 'Hoc\'h anv implijer',
'yourpassword'       => 'Ho ker-tremen',
'yourpasswordagain'  => 'Skrivit ho ker-tremen en-dro',
'newusersonly'       => ' (implijerien nevez hepken)',
'remembermypassword' => 'Derc\'hel soñj eus ma ger-tremen (toupin)',
'loginproblem'       => '<b>Kudenn disklêriañ.</b><br />Klaskit en-dro !',
'alreadyloggedin'    => '\'\'\'Implijer $1, disklêriet oc\'h dija!\'\'\'<br />',

'login'         => 'Disklêriañ',
'loginprompt'	=> 'Ret eo deoc\'h bezañ gweredekaet an toupinoù evit bezañ luget ouzh {{SITENAME}}.',
'userlogin'     => 'Krouiñ ur gont pe en em lugañ',
'logout'        => 'Dilugañ',
'userlogout'    => 'Dilugan',
'notloggedin'	=> 'Anluget',
'createaccount' => 'Krouiñ ur gont nevez',
'createaccountmail'	=> 'dre bostel',
'badretype'     => 'N\'eo ket peurheñvel an eil ouzh egile an daou c\'her-tremen bet lakaet ganeoc\'h.',
'userexists'    => "Implijet eo dija an anv implijer lakaet ganeoc\'h. Dibabit unan all mar plij.",
'youremail'     => 'Ma chomlec\'h elektronek',
'yournick'      => 'Sinadur evit ar c\'haozeadennoù (gant <tt><nowiki>~~~</nowiki></tt>)&nbsp;',
'yourrealname'	=> 'Hoc\'h anv gwir*',
'emailforlost'  => 'Ma tiankit ho ker-tremen e c\'hallit goulenn ma vo kaset deoc\'h ur ger-tremen nevez d\'ho chomlec\'h elektronek.',
'prefs-help-realname' => '* <strong>Hoc\'h anv</strong> (diret): ma vez spisaet ganeoc\'h e vo implijet evit merkañ ho tegasadennoù.',
'prefs-help-email' => '* <strong>Chomlec\'h elektronek</strong> (diret): gantañ e vo aes mont e darempred ganeoc\'h adal al lec\'ienn o terc\'hel kuzh ho chomlec\'h, hag adkas ur ger-tremen deoc\'h ma tichañsfe deoc\'h koll ho hini.',
'loginerror'    => 'Kudenn disklêriañ',
'nocookiesnew'	=> "krouet eo bet ar gont implijer met n'hoc\'h ket luget. {{SITENAME}} a implij toupinoù evit al lugañ met diweredekaet eo an toupinoù ganeoc\'h. Trugarez da weredekaat anezho ha d'en em lugañ en-dro.",
'nocookieslogin' => "{{SITENAME}} a implij toupinoù evit al lugañ met diweredekaet eo an toupinoù ganeoc\'h. Trugarez da weredekaat anezho ha d'en em lugañ en-dro.",
'noname'        => "N\'hoc\'h eus lakaet anv implijer ebet.",
'loginsuccesstitle' => "Disklêriet oc\'h.",
'loginsuccess'  => "Luget oc\'h bremañ war {{SITENAME}} evel \"$1\".",
'nosuchuser'    => "N\'eus ket eus an implijer \"$1\".
Gwiriit eo bet skrivet mat an anv ganeoc\'h pe implijit ar furmskrid a-is a-benn krouiñ ur gont implijer nevez.",
'nosuchusershort' => 'N\'eus perzhiad ebet gantañ an anv « $1 ». Gwiriit ar reizhskrivadur.',
'wrongpassword' => 'Ger-tremen kamm. Klaskit en-dro.',
'mailmypassword' => 'Kasit din ur ger-tremen nevez',
'passwordremindertitle' => "Ho ker-tremen nevez war {{SITENAME}}",
'passwordremindertext' => "Unan bennak (c\'hwi moarvat) gant ar chomlec\'h IP $1 en deus goulennet ma vo kaset deoc\'h ur ger-tremen nevez evit monet war ar wiki.
Ger-tremen an implijer \"$2\" zo bremañ \"$3\".
Erbediñ a reomp deoc\'h en em lugañ ha kemmañ ar ger-termen-man an abretañ ar gwellañ.",
'noemail'  => "N\'eus bet enrollet chomlec\'h elektronek ebet evit an implijer \"$1\".",
'passwordsent' => "Kaset ez eus bet ur ger-tremen nevez da chomlec\'h elektronek an implijer \"$1\".
Ho trugarez evit en em zisklêriañ kerkent ha ma vo bet resevet ganeoc\'h.",
'loginend'	=> '&nbsp;',
'mailerror'	=> 'Fazi o kas a rpostel : $1',
'acct_creation_throttle_hit' => 'Ho tigarez, krouet ez eus bet $1 (c\'h)gont ganeoc\'h dija. N\'hallit ket krouiñ unan nevez.',

# Edit page toolbar
'bold_sample'   => 'Testenn tev',
'bold_tip'      => 'Testenn tev',
'italic_sample' => 'Testenn italek',
'italic_tip'    => 'Testenn italek',
'link_sample'   => 'Liamm titl',
'link_tip'      => 'Liamm diabarzh',
'extlink_sample'  => 'http://www.example.com lien titre',
'extlink_tip'     => 'Liamm diavaez (na zisoñjit ket http://)',
'headline_sample' => 'Testenn istitl',
'headline_tip'  => 'Istitl live 2',
'math_sample'   => 'Lakit ho formulenn amañ',
'math_tip'      => 'Formulenn jedoniel (LaTeX)',
'nowiki_sample' => 'Lakait an destenn anfurmadet amañ',
'nowiki_tip'    => 'Na deuler pled ouzh eradur ar wiki',
'image_sample'  => 'Skouer.jpg',
'image_tip'     => 'Skeudenn enframmet',
'media_sample'  => 'Skouer.ogg',
'media_tip'     => 'Liamm restr media',
'sig_tip'       => 'Ho sinadur gant an deiziad',
'hr_tip'        => 'Liamm a-led (arabat implijout re)',
'infobox'       => 'Klikit war ar bouton-mañ da gaout skouer un tamm testenn',
'infobox_alert'	=> "Lakait an destenn hoc\'h c\'h c\'hoant da furmadiñ.\\n Diskwelet e vo er voest prest da vezañ eilet ha peget.\\nSkouer\\n$1\\na zeuio:\\n$2",

# Edit pages
#
'summary'      => 'Diverrañ&nbsp;',
'subject'	   => 'Danvez/titl',
'minoredit'    => 'Kemm dister.',
'watchthis'    => 'Evezhiañ ar pennad-mañ',
'savearticle'  => 'Savete',
'preview'      => 'Rakdiskouez',
'showpreview'  => 'Rakdiskouez',
'blockedtitle' => 'Implijer stanket',
"blockedtext"  => "Stanket eo bet ho kont implijer pe ho chomlec\'h IP gant $1 evit an abeg-mañ :<br />$2<p>Gallout a rit mon e darempred $1 pe unan eus ar [[{{ns:4}}:Merourien|verourien]] all evit eskemm ganto war se.",
'whitelistedittitle' => 'Ret eo bezañ luget evit skridaozañ',
'whitelistedittext' => 'Ret eo deoc\'h bezañ [[Special:Userlogin|luget]] evit gallout skridaozañ',
'whitelistreadtitle' => 'Ret eo bezañ luget evit gallout lenn',
'whitelistreadtext' => 'Ret eo bezañ [[Special:Userlogin|luget]] evit gallout lenn ar pennadoù',
'whitelistacctitle' => 'N_hoc\'h ket aotreet da grouiñ ur gont',
'whitelistacctext' => 'A-benn gallout krouiñ ur gont war ar Wiki-mañ e rankit bezañ [[Special:Userlogin|luget]] ha kaout an aotreoù rekis', // Looxix
'loginreqtitle'	=> 'Anv implijer rekis',
'accmailtitle' => 'Ger-tremen kaset.',
'accmailtext' => 'Kaset eo bet ger-tremen « $1 » da $2.',

'newarticle'   => '(Nevez)',
'newarticletext' => 'Skrivit amañ testenn ho pennad.',
'anontalkpagetext' => "---- ''Homañ eo ar bajenn gaozeal evit un implijer dianv n'eus ket c\'hoazh krouet kont ebet pe na implij ket anezhi. Setu perak e rankomp ober gant ar [[chomlec\'h IP]] niverel evit disklêriañ anezhañ. Gallout a ra ur chomlec\'h a seurt-se bezañ rannet etre meur a implijer. Ma'z oc\'h un implijer dianv ha ma stadit ez eus bet kaset deoc\'h kemennadennoù na sellont ket ouzhoc\'h, gallout a rit [[Special:Userlogin|krouiñ ur gont pe en em lugañ]] kuit a vagañ muioc\'h a gemmesk.",
'noarticletext' => "(N'eus evit poent tamm skrid ebet war ar bajenn-mañ)",
'clearyourcache'    => "'''Notenn:''' Goude bezañ enrollet ho pajenn e rankit adkargañ anezhi a-ratozh evit gwelet ar c\'hemmoù : Mozilla / Konqueror : ctrl-r, Firefox / IE / Opera : ctrl-f5, Safari : cmd-r.",
'updated'      => '(Hizivaet)',
'note'         => '<strong>Notenn :</strong> ',
'previewnote'  => "Diwallit mat, n'eus eus an destenn-mañ nemet ur rakweladenn ha n'eo ket bet enrollet c\'hoazh!",
'previewconflict' => "Gant ar rakdiskouez e teu testenn ar bajenn war wel evel ma vo pa vo bet enrollet.",
'editing'         => 'kemmañ $1',
'editingsection'  => 'kemmañ $1 (rann)',
'editingcomment'  => 'kemmañ $1 (soñj)',
'editconflict' => 'tabut kemmañ : $1',
'explainconflict' => "<b>Enrollet eo bet ar bajenn-mañ war-lerc\'h m\'ho pefe kroget d\'he c\'hemmañ.
E-krec\'h an takad aozañ emañ an destenn evel m\'emañ enrollet bremañ er bank roadennoù. Ho kemmoù deoc\'h a zeu war wel en takad aozañ traoñ. Ret e vo deoc\'h degas ho kemmoù d\'an destenn zo evit poent. N\'eus nemet an destenn zo en takad krec\'h a vo saveteet.<br />",
'yourtext'     => 'Ho testenn',
'storedversion' => 'Stumm enrollet',
"editingold"   => "<strong>Diwallit : o kemm un doare kozh eus ar bajenn-mañ emaoc\'h. Mard enrollit bremañ e vo kollet an holl gemmoù bet graet abaoe an doare-se.</strong>",
"yourdiff"  => "Diforc\'hioù",
"copyrightwarning" => "Sellet e vez ouzh an holl degasadennoù graet war {{SITENAME}} evel dgasadennoù a zouj da dermenoù ar GNU Free Documentation Licence, un aotre teulioù frank a wirioù (Sellet ouzh $1 evit gouzout hiroc\'h). Mar ne fell ket deoc\'h e vefe embannet ha skrignet ho skridoù, arabat kas anezho. Heñveldra, trugarez da gemer perzh o tegas hepken skridoù savet ganeoc\'h pe skridoù tennet eus ur vammen frank a wirioù. <b>NA IMPLIJIT KET LABOURIOÙ GANT GWIRIOÙ AOZER (COPYRIGHT) HEP KAOUT UN AOTRE A-RATOZH!</b>",
"longpagewarning" => "<strong>KEMENN DIWALL: $1 ko eo hed ar bajenn-mañ;
merdeerioù zo o deus poan da verañ ar pajennoù tro-dro pe en tu all da 32 ko pa vezont savet.
Marteze e c\'hallfec\'h rannañ ar bajenn e rannoù bihanoc\'h.</strong>",
"readonlywarning" => "<strong>KEMENN DIWALL: stanket eo bet ar bajenn-mañ evit bezañ trezalc\'het,
n\'oc\'h ket evit enrollañ ho kemmoù diouzhtu eta. Gallout a rit eilañ an destenn en ur restr hag enrollañ anezhi diwezhatoc\'hik.</strong>",
"protectedpagewarning" => "<strong>AVERTISSEMENT : stanket eo bet ar bajenn-mañ.
N\'eus nemet an implijerien ganto ar statud a verourien a c\'hall degas kemmoù enni. Bezit sur ec\'h heuilhit an [[Project:Pajenn_warezet|erbedadennoù a denn d\'ar pajennoù gwarezet]].<strong>",

# History pages
#
'revhistory'   => 'Stummoù kent',
'nohistory'    => "Ar bajenn-mañ n\'he deus tamm istor ebet.",
'revnotfound'  => 'N\'eo ket bet kavet ar stumm-mañ',
'revnotfoundtext' => "N\'eo ket kavet stumm kent ar bajenn-mañ. Gwiriit an URL lakaet ganeoc\'h evit mont d\'ar bajenn-mañ.\n",

'loadhist'     => 'O kargañ istor ar bajenn',
'currentrev'   => 'Stumm a-vremañ',
'revisionasof' => 'Stumm eus an $1',
'cur'    => 'brem',
'next'   => 'goude',
'last'   => 'diwez',
'orig'   => 'kent',
'histlegend' => "Alc\'hwez : (brem) = diforc\'hioù gant an doare a-vremañ,
(diwez) = diforc\'hioù gant an doare kent, K = kemm bihan",
'selectnewerversionfordiff' => 'Dibab un doare nevesoc\'h',
'selectolderversionfordiff' => 'Dibab un doare koshoc\'h',
'previousdiff' => '← Diforc\'h kent',
'previousrevision' => '← Doare kent',
'nextdiff' => 'Difoc\'h warlerc\'h →',
'nextrevision' => 'Stumm war-lerc\'h →',


# Category pages
#
'categoriespagetext' => "War ar wiki emañ ar rummadoù da-heul :",
'categoryarticlecount' => "$1 pennad zo er rummad-mañ.",
'categoryarticlecount1' => "N\'eus pennad ebet er rummad-mañ.",


#  Diffs
#
'difference' => '(Diforc\'hioù etre ar stummoù)',
'loadingrev' => 'kargañ ar stumm kent evit keñveriañ',
'lineno'  => 'Linenn $1:',
'editcurrent' => 'Kemmañ stumm a-vremañ ar bajenn-mañ',


# Search results
#
'searchresults' => 'Disoc\'h ar c\'hlask',
'searchresulttext' => "Evit kaout muoic\'h a ditouroù diwar-benn ar c\'hlask e {{SITENAME}}, sellet ouzh [[Project:Klask|Klask e-barzh {{SITENAME}}]].",
'searchquery' => "Evit ar goulenn \"$1\"",
'badquery'  => 'Goulenn savet a-dreuz',
'badquerytext' => "N\'eus ket bet gallet plediñ gant ho koulenn.
Klasket hoc\'h eus, moarvat, ur ger dindan teir lizherenn, ar pezh n\'hallomp ket ober evit c\'hoazh. Gallet hoc\'h eus ober, ivez, ur fazi ereadur evel \"pesked ha skant\".
Glaskit gant ur goulenn all.",
'matchtotals' => "Klotañ a ra ar goulenn \"$1\" gant $2 (d/z)titl
pennad ha gant testenn $3 (b/f)pennad.",
'nogomatch' => "N\'eus pajenn ebet ganti an titl-mañ, esae gant ar c\'hlask klok.",
'titlematches' => "Klotadurioù gant an titloù",
'notitlematches' => "N\'emañ ar ger(ioù) goulennet e titl pennad ebet",
'textmatches' => "Klotadurioù en testennoù",
'notextmatches' => "N\'emañ ar ger(ioù) goulennet e testenn pennad ebet",
'prevn'   => '$1 kent',
'nextn'   => '$1 war-lerc\'h',
'viewprevnext' => 'Gwelet ($1) ($2) ($3).',
'showingresults' => "Diskouez <b>$1</b> disoc\'h adal an #<b>$2</b>.",
'showingresultsnum' => "Diskouez <b>$3</b> disoc\'h adal an #<b>$2</b>.",
'nonefound'  => "<strong>Notenn</strong>: alies eo liammet an diouer a zisoc\'hoù ouzh an implij a vez graet eus termenoù klask re stank, evel \"da\" pe \"ha\",
termenoù n\'int ket menegeret, pe ouzh an implij a meur a dermen klask (en disoc\'hoù ne gaver nemet ar pajennoù enno an holl c\'herioù spisaet).",
'powersearch' => "Klask",
'powersearchtext' => "
Klask en esaouennoù :<br />
$1<br />
$2 Lakaat ivez ar pajennoù adkas &nbsp; Klask $3 $9",
'searchdisabled' => "<p>Diweredekaet eo bet an arc\'hwel klask war an destenn a-bezh evit ur frapad rak ur samm re vras e oa evit ar servijer. Emichañs e vo tu d\'e adlakaat pa vo ur servijer galloudsoc\'h ganeomp. Da c\'hortoz e c\'hallit klask gant Google:</p>
",
"blanknamespace" => "(Principal)",	// FIXME FvdP: trad de "(Main)"

) + $wgAllMessagesFr;

class LanguageBr extends LanguageFr {

	function getBookstoreList () {
		global $wgBookstoreListBr ;
		return $wgBookstoreListBr ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesBr;
		return $wgNamespaceNamesBr;
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesBr, $wgSitename;

		foreach ( $wgNamespaceNamesBr as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( $wgSitename == "Wikipédia" ) {
			if( 0 == strcasecmp( "Wikipedia", $text ) ) return 4;
			if( 0 == strcasecmp( "Discussion_Wikipedia", $text ) ) return 5;
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsBr;
		return $wgQuickbarSettingsBr;
	}

	function getSkinNames() {
		global $wgSkinNamesBr;
		return $wgSkinNamesBr;
	}

	function getMessage( $key ) {
		global $wgAllMessagesBr, $wgAllMessagesEn;
		if( isset( $wgAllMessagesBr[$key] ) ) {
			return $wgAllMessagesBr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
