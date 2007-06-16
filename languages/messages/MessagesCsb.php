<?php
/**
 * @addtogroup Language
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specjalnô',
	NS_MAIN             => '',
	NS_TALK             => 'Diskùsëjô',
	NS_USER             => 'Brëkòwnik',
	NS_USER_TALK        => 'Diskùsëjô_brëkòwnika',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Diskùsëjô_$1',
	NS_IMAGE            => 'Òbrôzk',
	NS_IMAGE_TALK       => 'Diskùsëjô_òbrôzków',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskùsëjô_MediaWiki',
	NS_TEMPLATE         => 'Szablóna',
	NS_TEMPLATE_TALK    => 'Diskùsëjô_Szablónë',
	NS_HELP             => 'Pòmòc',
	NS_HELP_TALK        => 'Diskùsëjô_Pòmòcë',
	NS_CATEGORY         => 'Kategòrëjô',
	NS_CATEGORY_TALK    => 'Diskùsëjô_Kategòrëji'
);

$messages = array(
# Dates
'january'       => 'stëcznik',
'february'      => 'Gromicznik',
'march'         => 'strumiannik',
'april'         => 'Łżëkwiôt',
'may_long'      => 'Môj',
'june'          => 'Czerwińc',
'july'          => 'Lëpinc',
'august'        => 'Zélnik',
'september'     => 'Séwnik',
'october'       => 'Rujan',
'november'      => 'Lëstopadnik',
'december'      => 'Gòdnik',
'january-gen'   => 'stëcznika',
'february-gen'  => 'Gromicznika',
'march-gen'     => 'strumiannika',
'april-gen'     => 'Łżëkwiôta',
'may-gen'       => 'Môja',
'june-gen'      => 'Czerwińca',
'july-gen'      => 'Lëpinca',
'august-gen'    => 'Zélnika',
'september-gen' => 'Séwnika',
'october-gen'   => 'Rujana',
'november-gen'  => 'Lëstopadnika',
'december-gen'  => 'Gòdnika',

# Bits of text used by many pages
'categories'      => 'Kategòrëje',
'pagecategories'  => '{{PLURAL:$1|Kategòrëjô|Kategòrëje}}',
'category_header' => 'Artikle w kategòrëji "$1"',

'newwindow'      => '(òtmëkô sã w nowim òczenkù)',
'qbfind'         => 'Nalézë',
'qbmyoptions'    => 'Mòje òptacëje',
'qbspecialpages' => 'Specjalné starnë',
'moredotdotdot'  => 'Wicy...',
'anontalk'       => 'Diskùsëjô dlô ti IP-adresë',
'navigation'     => 'Nawigacëjô',

'errorpagetitle' => 'Brida',
'returnto'       => 'Wôrcë sã do starnë: $1.',
'help'           => 'Pòmòc',
'search'         => 'Szëkba',
'searchbutton'   => 'Szëkba',
'go'             => 'Biôj!',
'searcharticle'  => 'Biôj!',
'history'        => 'Historëjô starnë',
'history_short'  => 'Historëjô',
'edit'           => 'Edicëjô',
'editthispage'   => 'Editëjë ną starnã',
'delete'         => 'Rëmôj',
'protect'        => 'Zazychrëjë',
'unprotect'      => 'Òdzychrëjë',
'specialpage'    => 'Specjalnô starna',
'personaltools'  => 'Priwatné przërëchtënczi',
'postcomment'    => 'Dôj dopòwiesc',
'talk'           => 'Diskùsëjô',
'toolbox'        => 'Przërëchtënczi',
'imagepage'      => 'Starna òbrôzka',
'redirectedfrom' => '(Przeczerowóné z $1)',
'lastmodifiedat' => 'Na starna bëła slédno editowónô ò $2, $1;', # $1 date, $2 time
'protectedpage'  => 'Starna je zazychrowónô',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => 'Ò {{SITENAME}}',
'aboutpage'      => '{{ns:4}}:Ò_{{SITENAME}}',
'copyright'      => 'Zamkłosc hewòtny starnë je ùżëczónô wedle reglów $1.',
'disclaimers'    => 'Prawné zastrzedżi',
'disclaimerpage' => '{{ns:4}}:General_disclaimer',
'faqpage'        => '{{ns:4}}:FAQ',
'mainpage'       => 'Przédnô starna',
'portal'         => 'Pòrtal wëcmaniznë',
'portal-url'     => '{{ns:4}}:Pòrtal wëcmaniznë',

'ok'          => 'Jo!',
'pagetitle'   => '$1 - {{SITENAME}}',
'editsection' => 'Edicëjô',
'editold'     => 'Edicëjô',
'toc'         => 'Spisënk zamkłoscë',
'showtoc'     => 'pokôż',
'hidetoc'     => 'zatacë',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Starna brëkòwnika',
'nstab-special'   => 'Specjalnô',
'nstab-project'   => 'meta-starna',
'nstab-image'     => 'Òbrôzk',
'nstab-mediawiki' => 'ògłosënk',
'nstab-template'  => 'Szablóna',
'nstab-help'      => 'Pòmòc',
'nstab-category'  => 'Kategòrëjô',

# Main script and global functions
'nosuchactiontext'  => 'Programa Mediawiki nie rozpòznôwô taczi òperacëji jakô je w URL',
'nosuchspecialpage' => 'Nie da taczi specjalny starnë',

# General errors
'databaseerror'   => 'Fela w pòdôwkòwi baze',
'readonly'        => 'Baza pòdôwków je zablokòwónô',
'missingarticle'  => 'Programa ni mô nalôzłé tekstu starnë, chtërnô bë mùsza bëc w baze, to je starnë "$1".<p>Colemało mô to plac, czej òstónie wëbróné sparłãczenié\ndo rëmóny starnë, np. stôrszi wersëji jińszi starnë.</p><p>Jińszé leżnosce mògą znaczëc, że w soft-wôrze je fela. W taczim przëtrôfkù prosymë zameldowac nen fakt administratorowi pòdającë hewòtną adresã.',
'internalerror'   => 'Bënowô fela',
'filecopyerror'   => 'Ni mòże skòpérowac lopka "$1" do "$2".',
'filerenameerror' => 'Ni mòże zmienic miona lopka "$1" na "$2".',
'filedeleteerror' => 'Ni mòże rëmac lopka "$1".',
'filenotfound'    => 'Ni mòże nalezc lopka "$1".',
'formerror'       => 'Fela: ni mòże wëslac fòrmùlara',

# Login and logout pages
'logouttitle'          => 'Wëlogòwanié brëkòwnika',
'logouttext'           => 'Të jes ju wëlogòwóny. Mòżesz prôcowac z {{SITENAME}} jakno anonimòwi brëkòwnik abò wlogòwac sã jakno zaregistrowóny brëkòwnik.',
'loginpagetitle'       => 'Logòwanié brëkòwnika',
'yourpassword'         => 'Twòja parola',
'yourpasswordagain'    => 'Pòwtórzë parolã',
'login'                => 'Wlogùjë mie',
'loginprompt'          => "Brëkùjesz miec ''cookies'' (kùszczi) włączoné bë sã wlogòwac do {{SITENAME}}.",
'userlogin'            => 'Logòwanié',
'logout'               => 'Wëlogùjë mie',
'userlogout'           => 'Wëlogòwanié',
'notloggedin'          => 'Felëje logòwóniô',
'createaccount'        => 'Założë nowé kònto',
'yourrealname'         => 'Twòje jistné miono*',
'yourlanguage'         => 'Kaszëbsczi',
'loginerror'           => 'Fela logòwaniô',
'loginsuccesstitle'    => 'ùdałé logòwanié',
'loginsuccess'         => 'Të jes wlogòwóny do {{SITENAME}} jakno "$1".',
'nosuchuser'           => 'Nie da taczégò brëkòwnika "$1". Sprôwdzë pisënk abò wëfùlujë fòrmular bë założëc nowé kònto.',
'passwordremindertext' => 'Chtos (prôwdëjuwerno Të, z adresë $1) pòprosëł ò wësłanié nowi parolë dopùscënkù do {{SITENAME}} ($4). Aktualnô parola dlô brëkòwnika "$2" je "$3". Nôlepi mdze czej wlogùjesz sã terô ë zarô zmienisz parolã.',
'noemail'              => 'W baze ni ma email-adresë dlô brëkòwnika "$1".',

# Edit page toolbar
'nowiki_sample' => 'Wstôw tuwò niesfòrmatowóny tekst',
'nowiki_tip'    => 'Ignorëjë wiki-fòrmatowanié',
'hr_tip'        => 'Wòdorównô (horizontalnô) linijô (brëkùjë szpôrowno)',

# Edit pages
'minoredit'          => 'Drobnô edicëjô.',
'watchthis'          => 'Ùzérôj',
'savearticle'        => 'Zapiszë artikel',
'preview'            => 'Pòdzérk',
'showpreview'        => 'Pòdzérk',
'blockedtext'        => 'Twòje kònto abò/ ë IP-adresa òstałë zascëgòwóné przez $1.\nPòdónô przëczëna to:<br />$2.<p>Bë wëjasnic sprawã zablokòwaniégò mòżesz skòntaktowac sã z $1 abò jińszim [[{{MediaWiki:grouppage-sysop}}|administratorã]].',
'whitelistedittitle' => 'Bë editowac je nót sã wlogòwac',
'whitelistreadtitle' => 'Bë czëtac je nót sã wlogòwac',
'previewnote'        => 'To je blós pòdzérk - artikel jesz nie je zapisóny!',
'explainconflict'    => 'Chtos sfórtowôł wprowadzëc swòją wersëjã artikla òbczôs Twòji edicëji. Górné pòle edicëji zamëkô w se tekst starnë aktualno zapisóny w pòdôwkòwi baze. Twòje zmianë są w dólnym pòlu edicëji. Bë wprowadzëc swòje zmianë mùszisz zmòdifikòwac tekst z górnégò pòla. <b>Blós</b> tekst z górnégò pòla mdze zapisóny w baze czej wcësniesz "Zapiszë".',
'readonlywarning'    => 'BÔCZËNK: Pòdôwkòwô baza òsta sztërkòwô zablokòwónô dlô administracëjnëch célów. Nie mòże tej timczasã zapisac nowi wersëje artikla. Bédëjemë przeniesc ji tekst do priwatnégò lopka
(wëtnij/wstôw) ë zachòwac na pózni.',

# History pages
'loadhist'   => 'Zladënk historëji ny starnë',
'cur'        => 'aktualnô',
'last'       => 'pòslédnô',
'histlegend' => 'Legenda: (aktualnô) = różnice w przërównanim do aktualny wersëje,
(wczasniészô) = różnice w przërównanim do wczasniészi wersëje, D = drobné edicëje',

# Diffs
'difference'              => '(różnice midzë wersëjama)',
'lineno'                  => 'Lëniô $1:',
'editcurrent'             => 'Editëjë aktualną wersëjã ny starnë',
'compareselectedversions' => 'Przërównôj wëbróné wersëje',

# Search results
'noexactmatch' => 'Nie da starnë z dokładno taczim titlã. Spróbùjë fùl szëkbë.',
'powersearch'  => 'Szëkba',

# Preferences page
'preferences'        => 'Preferencëje',
'prefsnologin'       => 'Felënk logòwóniô',
'changepassword'     => 'Zmiana parolë',
'skin'               => 'Wëzdrzatk',
'dateformat'         => 'Fòrmat datumù',
'math_failure'       => 'Parser nie rozmiôł rozpòznac',
'prefs-personal'     => 'Pòdôwczi brëkòwnika',
'newpassword'        => 'Nowô parola',
'recentchangescount' => 'Wielëna pòzycëji na lësce slédnëch edicëji',
'timezonelegend'     => 'Czasowô cona',
'localtime'          => 'Twòja czasowô cona',
'servertime'         => 'Aktualny czas serwera',
'guesstimezone'      => 'Wezmi z przezérnika',
'defaultns'          => 'Domëslno przeszëkùjë nôslédné rëmnotë mionów:',

'grouppage-sysop' => 'Project:Administratorzë',

# Recent changes
'recentchanges'     => 'Slédné edicëje',
'recentchangestext' => 'Na starna prezentérëje historëjã slédnëch edicëjów w {{SITENAME}}.\n\nWitôj! Jeżle Të jes tuwò dopiérze pierszi rôz, przeczëtôj né starnë: [[{{MediaWiki:faqpage}}|FAQ]], [[{{ns:4}}:Nazëwizna|konwencëje nazëwaniégò starnów]].',
'hide'              => 'zatacë',
'show'              => 'pokôż',
'minoreditletter'   => 'D',

# Recent changes linked
'recentchangeslinked' => 'Zmianë w dolënkòwónëch',

# Upload
'upload'            => 'Wladënk lopka',
'reupload'          => 'Wëslë jesz rôz',
'uploadnologin'     => 'Felënk logòwaniô',
'uploadtext'        => '<strong>STOP!</strong> Nigle wladëjesz jaczi lopk,\nprzeczëtôj [[{{ns:4}}:Regle_wladowaniô_lopków|regle wladowaniô lopków]] ë ùgwësnij sã, że wladëwającë gò òstóniesz z\nnima w zgòdze.\n<p>Jeżle chcesz przezdrzec abò przeszëkac do terô wladowóné lopczi,\nprzeńdzë do [[{{ns:special}}:Imagelist|lëstë wladowónëch lopków]].\nWszëtczé wladënczi ë rëmania są òdnotérowóné w\nspecjalnëch zestôwkach: [[{{ns:special}}:Log/upload|wladënczi]] ë [[{{ns:special}}:Log/delete|rëmóné]].\n<p>Bë wëslac nowi lopk do zòbrazowaniô Twòjégò artikla wëzwëskùj \nhewòtny fòrmùlar.\nW wikszoscë przezérników ùzdrzesz knąpã <i>Browse...</i>\nabò <i>Przezérôj...</i>, chtëren ùmożlëwi Cë òtemkniãcé sztandardowégò\nòkna wëbiérkù lopka. Wëbranié lopka sprawi wstôwienié jegò miona\nw tekstowim pòlu kòl knąpë.\nZaznaczającë pasowné pòle, mùszisz téż pòcwierdzëc, ëż sélającë\nlopk nie gwôłcësz nikògò autorsczich praw.\nWladënk zacznie sã pò wcësniãcym <i>Wladëjë lopk</i>.\nTo mòże sztërk zdérowac, òsoblëwò jeżle ni môsz chùtczégò dopùscënkù do internetu.\n<p>Preferowónyma fòrmatama są: JPEG dlô òdjimków, PNG dlô céchùnków\në òbrôzków ze znankama ikònów, ôs OGG dlô zwãków. Bë nie dac przińc do lëchòrozmieniów nadôwôj lopkom miona sparłãczóné z jich zamkłoscą.\nBë wstôwic òbrôzk do artikla, wpiszë lënk:\n<b><nowiki>[[</nowiki>{{ns:image}}<nowiki>:miono.jpg]]</nowiki></b> abò <b><nowiki>[[</nowiki>{{ns:image}}<nowiki>:miono.png|òpcjonalny tekst]]</nowiki></b>.\nDlô zwãkòwëch lopków lënk mdze wëzdrzôł tak: <b><nowiki>[[</nowiki>{{ns:media}}<nowiki>:file.ogg]]</nowiki></b>.\n<p>Prosymë wdarzëc, ëż tak samò jak w przëtrôfkù zwëczajnëch starnów {{SITENAME}},\njińszi brëkòwnicë mògą editowac abò rëmac wladowóné przez Ce lopczi,\njeżle mdą dbë, że to mdze lepi służëc całi ùdbie {{SITENAME}}.\nTwòje prawò do sélaniégò lopków mòże bëc Cë òdebróné, eżle nadùżëjesz systemë.',
'uploadlog'         => 'Lësta wladënków',
'uploadlogpage'     => 'Dołączoné',
'uploadlogpagetext' => 'Hewò je lësta slédno wladowónëch lopków.\nWszëtczé gòdzënë tikają conë ùniwersalnégò czasë (UTC).',
'filename'          => 'Miono lopka',
'filedesc'          => 'Òpisënk',
'filesource'        => 'Zdrój',
'uploadedfiles'     => 'Wladowóné lopczi',
'minlength'         => 'Miono òbrôzka brëkùje miec przënomni trzë lëterë.',
'badfilename'       => 'Miono òbrôzka zmienioné na "$1".',
'successfulupload'  => 'Wladënk darzëł sã',
'fileuploaded'      => 'Lopk"$1" òstôł wladowóny.\nBiôj, prosymë, do starnë òpisënkù lopka ($2) ë pòdôj tikającé gò infòrmacëje\ntaczé jak: pòchòdzenié lopka, czedë ë przez kògò òstôł ùtworzony, ôs dëcht co le ò nim wiész, a wëdôwô Cë sã wôżné.',
'uploadwarning'     => 'Òstrzega ò wladënkù',
'savefile'          => 'Zapiszë lôpk',
'uploadedimage'     => 'wladënk: "$1"',
'uploaddisabled'    => 'Przeprôszómë! Mòżlëwòta wladënkù lopków na nen serwer òsta wëłączonô.',

# Image list
'imagelist'      => 'Lësta òbrôzków',
'getimagelist'   => 'zladënk lëstë lopków',
'ilsubmit'       => 'Szëkôj',
'byname'         => 'wedle miona',
'bydate'         => 'wedle datumù',
'bysize'         => 'wedle wiôlgòscë',
'imgdelete'      => 'rëmôj',
'imgdesc'        => 'òpisënk',
'imghistory'     => 'Historëjô lopka',
'revertimg'      => 'przëwôrcë',
'deleteimg'      => 'rëmôj nen òbrôzk',
'imghistlegend'  => 'Legenda: (aktualny) = to je aktualny lopk, (rëmôj) = rëmôj
ną starszą wersëjã, (przëwôrcë) = przëwôrcë ną starszą wersëjã.
<br /><i>Klëkni na datum bë òbôczëc jaczé lopczi bëlë wladowóné òb nen dzéń</i>.',
'imagelinks'     => 'Lënczi do lopka',
'linkstoimage'   => 'Hewò są starnë, jaczé òdwòłëją sã do negò lopka:',
'nolinkstoimage' => 'Niżódnô starna nie òdwòłëje sã do negò lopka.',

# Statistics
'sitestats'     => 'Statistika artiklów',
'userstats'     => 'Statistika brëkòwników',
'sitestatstext' => 'W pòdôwkòwi baze je w sëmie <b>$1</b> starn. Na wielëna zamëkô w se starnë <i>Diskùsëji</i>, starnë ò {{SITENAME}}, starnë ôrtë <i>stub</i> (ùzémk), starnë przeczerowóniô, ë jińszé, chtërné grãdo je klasyfikòwac jakno artikle. Bez nëch to prôwdëjuwerno da <b>$2</b> starn artiklów.<p>\nBëło w sëmie <b>$3</b> òdwiôdënów ë <b>$4</b> edicëji òd sztótu, czej miôł plac\nupgrade soft-wôrë.\nDôwó to strzédno <b>$5</b> edicëji na jedną starnã ë <b>$6</b> òdwiôdënów na jedną edicëjã.',

'disambiguationspage' => '{{ns:4}}:Starnë_ùjednoznacznieniô',

'doubleredirects' => 'Dëbeltné przeczérowania',

'brokenredirects' => 'Zerwóné przeczerowania',

# Miscellaneous special pages
'nlinks'       => '$1 lënków',
'lonelypages'  => 'Niechóné starnë',
'unusedimages' => 'Nie wëzwëskóné òbrôzczi',
'popularpages' => 'Nôwidzalszé starnë',
'wantedpages'  => 'Nônótniészé starnë',
'randompage'   => 'Kawlowô starna',
'shortpages'   => 'Nôkrótszé starnë',
'longpages'    => 'Nôdłëgszé starnë',
'listusers'    => 'Lësta brëkòwników',
'specialpages' => 'Specjalné starnë',
'spheading'    => 'Specjalné nôpisma',
'newpages'     => 'Nowé starnë',
'ancientpages' => 'Nôstarszé starnë',
'move'         => 'Przeniesë',
'movethispage' => 'Przeniesë',

# Book sources
'booksources' => 'Ksążczi',

'version' => 'Wersëjô',

# E-mail user
'emailuser'       => 'Wëslë e-maila do negò brëkòwnika',
'emailpage'       => 'Sélajë e-mail do brëkòwnika',
'defemailsubject' => 'E-mail òd {{SITENAME}}',
'noemailtitle'    => 'Felënk email-adresë',
'emailfrom'       => 'Òd',
'emailto'         => 'Do',
'emailsubject'    => 'Téma',
'emailmessage'    => 'Wiadło',
'emailsend'       => 'Wëslë',

# Watchlist
'watchlist'         => 'Lësta ùzérónëch artiklów',
'mywatchlist'       => 'Lësta ùzérónëch artiklów',
'watchnologin'      => 'Felënk logòwóniô',
'addedwatch'        => 'Dodónô do lëstë ùzérónëch',
'removedwatch'      => 'Rëmóné z lëstë ùzérónëch',
'watch'             => 'Ùzérôj',
'watchthispage'     => 'Ùzérôj ną starnã',
'unwatch'           => 'Òprzestôj ùzerac',
'unwatchthispage'   => 'Òprzestôj ùzerac ną starnã',
'notanarticle'      => 'To nie je artikel',
'watchmethod-list'  => 'szëkba ùzérónëch artiklów westrzód pòslédnëch edicëjów',
'watchlistcontains' => 'Wielëna artiklów na Twòji lësce ùzérónëch: $1.',
'couldntremove'     => 'Ni móg rëmac pòzycëje "$1"...',

# Delete/protect/revert
'deletepage'         => 'Rëmôj starnã',
'confirm'            => 'Pòcwierdzë',
'excontent'          => 'Zamkłosc starnë "$1"',
'confirmdelete'      => 'Pòcwierdzë rëmónié',
'actioncomplete'     => 'Òperacëjô wëkònónô',
'dellogpage'         => 'Rëmóné',
'deletionlog'        => 'register rëmaniów',
'deletecomment'      => 'Przëczëna rëmaniô',
'imagereverted'      => 'Przëwôrcenié wczasniészi wersëje darzëło sã.',
'rollback'           => 'Copnij edicëjã',
'rollbacklink'       => 'copnij',
'rollbackfailed'     => 'Nie szło copnąc zmianë',
'protectedarticle'   => 'zazychrowónô [[$1]]',
'unprotectedarticle' => 'òdzychrowóny [[$1]]',
'confirmprotect'     => 'Pòcwierdzë zazychrowanié',
'protectcomment'     => 'Przëczëna zazychrowóniô',

# Contributions
'contributions' => 'Wkłôd brëkòwników',
'mycontris'     => 'Mòje edicëje',
'contribsub2'   => 'Dlô brëkòwnika $1 ($2)',
'ucnote'        => 'Hewò je lësta slédnëch <b>$1</b> edicëjów dokònónëch przez\nbrëkòwnika òbczôs òstatnëch <b>$2</b> dni.',

# What links here
'whatlinkshere' => 'Lënkùjącé',
'notargettitle' => 'Nie da taczi starnë',
'linkshere'     => 'Do ny starnë òdwòłëją sã hewòtné starnë:',
'isredirect'    => 'starna przeczerowaniô',

# Block/unblock
'blockip'           => 'Zascëgôj IP-adresã',
'ipbreason'         => 'Przëczëna',
'badipaddress'      => 'IP-adresa nie je richtich pòdónô.',
'contribslink'      => 'wkłôd',
'proxyblocksuccess' => 'Fertich.',

# Developer tools
'lockbtn' => 'Zascëgôj bazã pòdôwków',

# Move page
'movepage'      => 'Przeniesë starnã',
'movearticle'   => 'Przeniesë artikel',
'movenologin'   => 'Felënk logòwaniô',
'movepagebtn'   => 'Przeniesë starnã',
'pagemovedsub'  => 'Przeniesenié darzëło sã',
'pagemovedtext' => 'Starna "[[$1]]" òsta przeniesłô do "[[$2]]".',
'movedto'       => 'przeniesłô do',
'movetalk'      => 'Przeniesë téż starnã <i>Diskùsëje</i>, jeżle je to mòżlëwé.',
'1movedto2'     => '$1 przeniesłé do $2',

# Export
'export' => 'Ekspòrt starnów',

# Namespace 8 related
'allmessages' => 'Wszëtczé systemòwé ògłosë',

# Thumbnails
'missingimage' => '<b>Felëjący òbrôzk</b><br /><i>$1</i>',

# Special:Import
'import' => 'Impòrtëjë starnë',

# Tooltip help for the actions
'tooltip-watch' => 'Dodôj ną starnã do lëstë ùzérónëch',

# Attribution
'anonymous'        => 'Anonimòwi brëkòwnik/-cë  {{SITENAME}}',
'siteuser'         => 'Brëkòwnik {{SITENAME}} $1',
'lastmodifiedatby' => 'Na starna bëła slédno editowónô $2, $1 przez $3.', # $1 date, $2 time, $3 user
'and'              => 'ë',
'othercontribs'    => 'Òpiarté na prôcë $1.',

# Spam protection
'spamprotectiontitle'  => 'Anti-spamòwi filter',
'categoryarticlecount' => 'W ny kategòrëje je $1 artiklów.',

);

?>
