<?php
/** Mirandese (Mirandés)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Cecílio
 * @author MCruz
 * @author Malafaya
 * @author Romaine
 * @author Urhixidur
 */

$fallback = 'pt';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Cumbersa',
	NS_USER             => 'Outelizador',
	NS_USER_TALK        => 'Cumbersa_outelizador',
	NS_PROJECT_TALK     => '$1_cumbersa',
	NS_FILE             => 'Fexeiro',
	NS_FILE_TALK        => 'Cumbersa_fexeiro',
	NS_MEDIAWIKI        => 'Biqui',
	NS_MEDIAWIKI_TALK   => 'Cumbersa_Biqui',
	NS_TEMPLATE         => 'Modelo',
	NS_TEMPLATE_TALK    => 'Cumbersa_Modelo',
	NS_HELP             => 'Ajuda',
	NS_HELP_TALK        => 'Cumbersa_ajuda',
	NS_CATEGORY         => 'Catadorie',
	NS_CATEGORY_TALK    => 'Cumbersa_catadorie',
);

$namespaceAliases = array(
	'Especial' => NS_SPECIAL,
	'Discussão' => NS_TALK,
	'Usuário' => NS_USER,
	'Usuário_Discussão' => NS_USER_TALK,
	'$1_Discussão' => NS_PROJECT_TALK,
	'Ficheiro' => NS_FILE,
	'Ficheiro_Discussão' => NS_FILE_TALK,
	'Imagem_Discussão' => NS_FILE,
	'Imagem_Discussão' => NS_FILE_TALK,
	'MediaWiki_Discussão' => NS_MEDIAWIKI_TALK,
	'Predefinição' => NS_TEMPLATE,
	'Predefinição_Discussão' => NS_TEMPLATE_TALK,
	'Ajuda_Discussão' => NS_HELP_TALK,
	'Categoria' => NS_CATEGORY,
	'Categoria_Discussão' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'Antrar' ),
	'Userlogout'                => array( 'Salir' ),
	'CreateAccount'             => array( 'Criar Cuonta' ),
	'Lonelypages'               => array( 'Páiginas Uorfanas' ),
	'Uncategorizedcategories'   => array( 'Catadories sien catadories' ),
	'Uncategorizedimages'       => array( 'Eimaiges sien catadories' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#ANCAMINAR', '#REDIRECIONAMENTO', '#REDIRECT' ),
	'img_right'             => array( '1', 'dreita', 'direita', 'right' ),
	'img_left'              => array( '1', 'squierda', 'esquerda', 'left' ),
	'img_none'              => array( '1', 'nanhun', 'nenhum', 'none' ),
	'img_center'            => array( '1', 'centro', 'center', 'centre' ),
	'img_middle'            => array( '1', 'meio', 'middle' ),
	'language'              => array( '0', '#LHENGUA:', '#IDIOMA:', '#LANGUAGE:' ),
	'filepath'              => array( '0', 'CAMINOFEXEIRO:', 'CAMINHODOARQUIVO', 'FILEPATH:' ),
	'tag'                   => array( '0', 'eitiqueta', 'tag' ),
	'pagesize'              => array( '1', 'TAMANHOFEXEIRO', 'TAMANHODAPAGINA', 'TAMANHODAPÁGINA', 'PAGESIZE' ),
	'staticredirect'        => array( '1', '_ANCAMINARSTATICO_', '__REDIRECIONAMENTOESTATICO__', '__REDIRECIONAMENTOESTÁTICO__', '__STATICREDIRECT__' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sublinhar lhigaçones:',
'tog-highlightbroken'         => 'Formatar lhigaçones para páiginas ineisistentes <a href="" class="new">assi</a> (altarnatiba: assi<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Justeficar parágrafos',
'tog-hideminor'               => 'Scunder eidiçones mais pequeinhas nas redadeiras altaraçones',
'tog-hidepatrolled'           => 'Scunder eidiçones patrulhadas nas redadeiras altaraçones',
'tog-newpageshidepatrolled'   => 'Scunder  páiginas patrulhadas na lhista de páiginas nuobas',
'tog-extendwatchlist'         => 'Lhistaige spandida de todas las altaraçones a las páiginas begiadas, nun solo de las redadeiras',
'tog-usenewrc'                => 'Ousar la berson melhorada de las mudanças recentes (requer JavaScrit)',
'tog-numberheadings'          => 'Outo-numerar cabeçalhos',
'tog-showtoolbar'             => "Amostrar barra d'eidiçon (JavaScrit)",
'tog-editondblclick'          => 'Eiditar páiginas quando houbir un clique duplo (JavaScrit)',
'tog-editsection'             => "Possiblitar l'eidiçon de cachos cun lhigaçones [eiditar]",
'tog-editsectiononrightclick' => "Posseblitar l'eidiçon de cachos por clique cul boton dreito ne l títalo de la seçon (JavaScrit)",
'tog-showtoc'                 => 'Amostrar índice (para páiginas cun mais de trés cachos)',
'tog-rememberpassword'        => 'Recordar la mie palabra-chabe antre sessones (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Ajuntar las páiginas que you criar a las minhas páiginas begiadas',
'tog-watchdefault'            => 'Ajuntar las páiginas que you eiditar a las minhas páiginas begiadas',
'tog-watchmoves'              => ' Ajuntar las páiginas que you mober a las minhas páiginas begiadas',
'tog-watchdeletion'           => ' Ajuntar las páiginas que you eileminar a las minhas páiginas begiadas',
'tog-previewontop'            => "Amostrar l'antebison antes de la caixa d'eidiçon",
'tog-previewonfirst'          => "Amostrar l'antebison na purmeira eidiçon",
'tog-nocache'                 => 'Zatibar caching de páiginas',
'tog-enotifwatchlistpages'    => 'Notificar-me por correio eiletrónico quando ua páigina begiada ye altarada',
'tog-enotifusertalkpages'     => 'Abisar -me por correio eiletrónico quando la mie páigina de çcusson ye eiditada',
'tog-enotifminoredits'        => 'Abisar-me por correio eiletrónico tamien quando las eidiçones fúren pequeinhas',
'tog-enotifrevealaddr'        => 'Rebelar l miu andereço de correio eiletrónico nas noteficaçones',
'tog-shownumberswatching'     => "Amostrar l númaro d'outelizadores a begiar",
'tog-fancysig'                => 'Tratar assinatura cumo wikitesto (sin lhigaçon outomática)',
'tog-externaleditor'          => 'Por oumisson, outelizar un eiditor sterno (solo para outelizadores abançados, eisige cunfiguraçones adecionales ne l tou cumputador)',
'tog-externaldiff'            => 'Por oumisson, outelizar defrenças sternas (solo para outelizadores abançados, eisige cunfiguraçones adicionales ne l tou cumputador)',
'tog-showjumplinks'           => 'Posseblitar lhigaçones d\'acessibelidade "{{ant:jumpto}}"',
'tog-uselivepreview'          => "Ousar l'antebison an tiempo rial (JavaScrit) (Spurmental)",
'tog-forceeditsummary'        => 'Abisar-me al antroduzir un sumairo bazio',
'tog-watchlisthideown'        => 'Scunder las mies eidiçones al lhistar mudanças a las páiginas begiadas',
'tog-watchlisthidebots'       => 'Scunder eidiçones de robós al lhistar mudanças a las páiginas begiadas',
'tog-watchlisthideminor'      => 'Scunder eidiçones menores al lhistar mudanças a las páiginas begiadas',
'tog-watchlisthideliu'        => "Scunder eidiçones d'outelizadores outenticados al lhistar mudanças a las páiginas begiadas",
'tog-watchlisthideanons'      => "Scunder  eidiçones d'outelizadores anónimos al lhistar mudanças a las páiginas begiadas",
'tog-watchlisthidepatrolled'  => 'Scunder  eidiçones patrulhadas al lhistar mudanças a las páiginas begiadas',
'tog-ccmeonemails'            => 'Ambiar-me cópias de l correio eiletrónico que you ambiar a outros outelizadores',
'tog-diffonly'                => 'Nun amostrar l cuntenido de la páigina al acumparar dues eidiçones',
'tog-showhiddencats'          => 'Amostrar catadories oucultas',
'tog-norollbackdiff'          => 'Scunder defrenças depuis de reberter eidiçones an bloco',

'underline-always'  => 'Siempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Ousar la cunfiguraçon de l nabegador',

# Dates
'sunday'        => 'Demingo',
'monday'        => 'Segunda-feira',
'tuesday'       => 'Terça',
'wednesday'     => 'Quarta',
'thursday'      => 'Quinta',
'friday'        => 'Sesta',
'saturday'      => 'Sábado',
'sun'           => 'dem',
'mon'           => 'seg',
'tue'           => 'ter',
'wed'           => 'qua',
'thu'           => 'qui',
'fri'           => 'ses',
'sat'           => 'sáb',
'january'       => 'Janeiro',
'february'      => 'Febreiro',
'march'         => 'Março',
'april'         => 'Abril',
'may_long'      => 'Maio',
'june'          => 'Junho',
'july'          => 'Júlio',
'august'        => 'Agosto',
'september'     => 'Setembre',
'october'       => 'Outubro',
'november'      => 'Nobembre',
'december'      => 'Dezembro',
'january-gen'   => 'Janeiro',
'february-gen'  => 'Fevereiro',
'march-gen'     => 'Márcio',
'april-gen'     => 'Abril',
'may-gen'       => 'Maio',
'june-gen'      => 'Júnio',
'july-gen'      => 'Júlio',
'august-gen'    => 'Agosto',
'september-gen' => 'Setembre',
'october-gen'   => 'Outubre',
'november-gen'  => 'Nobembre',
'december-gen'  => 'Dezembre',
'jan'           => 'Jan.',
'feb'           => 'Feb.',
'mar'           => 'Mar.',
'apr'           => 'Abr.',
'may'           => 'Mai.',
'jun'           => 'Jún.',
'jul'           => 'Júl.',
'aug'           => 'Ago.',
'sep'           => 'Set.',
'oct'           => 'Out.',
'nov'           => 'Nob.',
'dec'           => 'Dez.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Catadorie|Catadories}}',
'category_header'                => 'Páiginas na catadorie "$1"',
'subcategories'                  => 'Subcatadories',
'category-media-header'          => 'Multimédia na catadorie "$1"',
'category-empty'                 => "''Esta catadorie nun cuntén atualmente nanhue páigina ó fexeiro multimédia.''",
'hidden-categories'              => '{{PLURAL:$1|Catadorie scundida|Catadories scundidas}}',
'hidden-category-category'       => 'Catadories scundidas',
'category-subcat-count'          => '{{PLURAL:$2|Esta catadorie solo cuntén la seguinte subcatadorie.|Esta catadorie cuntén {{PLURAL:$1|la seguinte subcatadorie|las seguintes $1 subcatadories}} (dun total de $2).}}',
'category-subcat-count-limited'  => 'Esta catadorie ten {{PLURAL:$1|la seguinte subcatadorie|las $1 subcatadories a seguir}}.',
'category-article-count'         => '{{PLURAL:$2|Esta catadorie solo cuntén la seguinte páigina.|Esta catadorie cuntén {{PLURAL:$1|la seguinte páigina|las seguintes $1 páiginas}} (dun total de $2).}}',
'category-article-count-limited' => 'Hai, nesta catadorie, {{PLURAL:$1|la páigina a seguir|las $1 páiginas a seguir}}.',
'category-file-count'            => '{{PLURAL:$2|Esta catadorie solo cuntén l seguinte fexeiro.|Esta catadorie cuntén {{PLURAL:$1|l seguinte fexeiro|ls seguintes $1 fexeiros}} (dun total de $2).}}',
'category-file-count-limited'    => 'Nesta catadorie hai {{PLURAL:$1|un fexeiro|$1 fexeiros}}.',
'listingcontinuesabbrev'         => 'cunt.',

'about'         => 'Subre',
'article'       => 'Páigina de cuntenido',
'newwindow'     => '(abre nua nuoba jinela)',
'cancel'        => 'Çfazer',
'moredotdotdot' => 'Mais...',
'mypage'        => 'Mie páigina',
'mytalk'        => 'Mie cumbersa',
'anontalk'      => 'Çcusson pa este IP',
'navigation'    => 'Nabegaçon',
'and'           => '&#32;i',

# Cologne Blue skin
'qbfind'         => 'Percurar',
'qbbrowse'       => 'Nabegar',
'qbedit'         => 'Eiditar',
'qbpageoptions'  => 'Esta páigina',
'qbpageinfo'     => 'Cuntesto',
'qbmyoptions'    => 'Mies páiginas',
'qbspecialpages' => 'Páiginas speciales',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

'errorpagetitle'    => 'Erro',
'returnto'          => 'Retornar pa $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ajuda',
'search'            => 'Percura',
'searchbutton'      => 'Percurar',
'go'                => 'Bota',
'searcharticle'     => 'Bota',
'history'           => 'Stórico de la Páigina',
'history_short'     => 'Stórico',
'info_short'        => 'Anformaçon',
'printableversion'  => 'Berson pa Ampremir',
'permalink'         => 'Lhigaçon pa siempre',
'print'             => 'Ampremir',
'edit'              => 'Eiditar',
'create'            => 'Criar',
'editthispage'      => 'Eiditar esta páigina',
'create-this-page'  => 'Criar esta páigina',
'delete'            => 'Botar fuora',
'deletethispage'    => 'Apagar esta páigina',
'protect'           => 'Porteger',
'protect_change'    => 'demudar',
'protectthispage'   => 'Porteger esta páigina',
'unprotect'         => 'Çporteger',
'unprotectthispage' => 'Çporteger esta páigina',
'newpage'           => 'Nuoba páigina',
'talkpage'          => 'Çcutir esta páigina',
'talkpagelinktext'  => 'Cumbersar',
'specialpage'       => 'Páigina special',
'personaltools'     => 'Ferramientas pessonales',
'postcomment'       => 'Nuobo cacho',
'articlepage'       => 'Ber páigina de cuntenido',
'talk'              => 'Çcusson',
'views'             => 'Besitas',
'toolbox'           => 'Caixa de Ferramientas',
'userpage'          => 'Ber páigina de outelizador',
'imagepage'         => 'Ber páigina de fexeiro',
'mediawikipage'     => 'Ber páigina de mensaiges',
'templatepage'      => 'Ber páigina de modelos',
'viewhelppage'      => 'Ber páigina de ajuda',
'viewtalkpage'      => 'Ber çcusson',
'otherlanguages'    => 'Outras lhénguas',
'redirectedfrom'    => '(Ancaminamiento de <b>$1</b>)',
'redirectpagesub'   => 'Páigina de ancaminamiento',
'lastmodifiedat'    => 'Esta páigina fui demudada pula redadeira beç a las $2 de $1.',
'protectedpage'     => 'Páigina portegida',
'jumpto'            => 'Saltar pa:',
'jumptonavigation'  => 'nabegaçon',
'jumptosearch'      => 'percura',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Subre {{SITENAME}}',
'aboutpage'            => 'Project:Subre',
'copyright'            => 'Cuntenido çponible subre la lhicença $1.',
'copyrightpage'        => '{{ns:project}}:Dreitos de outor',
'currentevents'        => 'Amboras atuales',
'currentevents-url'    => 'Project:Amboras atuales',
'disclaimers'          => 'Abiso de Cuntenido',
'disclaimerpage'       => 'Project:Abiso giral',
'edithelp'             => 'Ajuda de eidiçon',
'edithelppage'         => 'Help:Eiditar',
'helppage'             => 'Help:Cuntenidos',
'mainpage'             => 'Páigina Percipal',
'mainpage-description' => 'Páigina Percipal',
'policy-url'           => 'Project:Políticas',
'portal'               => 'Portal da quemunidade',
'portal-url'           => 'Project:Portal de la quemunidade',
'privacy'              => 'Política de pribacidade',
'privacypage'          => 'Project:Política de pribacidade',

'badaccess' => 'Erro de premisson',

'versionrequired' => 'Ye percisa la beson $1 de l MediaWiki',

'ok'                      => 'OK',
'retrievedfrom'           => 'Sacado an "$1"',
'youhavenewmessages'      => 'Tu tenes $1 ($2).',
'newmessageslink'         => 'nuobas mensaiges',
'newmessagesdifflink'     => 'redadeira altaraçon',
'youhavenewmessagesmulti' => 'Tenes nuobas mensaiges an $1',
'editsection'             => 'eiditar',
'editold'                 => 'eiditar',
'viewsourceold'           => 'ber código',
'editlink'                => 'eiditar',
'viewsourcelink'          => 'ber código',
'editsectionhint'         => 'Eiditar cacho: $1',
'toc'                     => 'Tabela de cuntenido',
'showtoc'                 => 'amostrar',
'hidetoc'                 => 'scunder',
'thisisdeleted'           => 'Ber ó restourar $1?',
'viewdeleted'             => 'Ber $1?',
'restorelink'             => '{{PLURAL:$1|ua eidiçon apagada|$1 eidiçones apagadas}}',
'feedlinks'               => 'Feed:',
'site-rss-feed'           => 'Feed RSS $1',
'site-atom-feed'          => 'Feed Atom $1',
'page-rss-feed'           => 'Feed RSS de "$1"',
'page-atom-feed'          => 'Feed Atom de "$1"',
'red-link-title'          => '$1 (la páigina nun eisiste)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Páigina',
'nstab-user'      => 'Páigina de l outelizador',
'nstab-media'     => 'Páigina de média',
'nstab-special'   => 'Páigina special',
'nstab-project'   => 'Páigina de porjeto',
'nstab-image'     => 'Fexeiro',
'nstab-mediawiki' => 'Mensaige',
'nstab-template'  => 'Modelo',
'nstab-help'      => "Páigina d'ajuda",
'nstab-category'  => 'Catadorie',

# Main script and global functions
'nosuchaction'      => 'Acion nun eisiste',
'nosuchspecialpage' => 'Nun eisiste la páigina special que pediste',

# General errors
'error'                => 'Erro',
'databaseerror'        => 'Erro na base de dados',
'laggedslavemode'      => 'Abiso: La páigina poderá nun cuntener las redadeiras atualizaçones.',
'readonly'             => 'Base de dados bloquiada',
'missing-article'      => 'La base de dados nun achou l testo dua páigina que deberie tener achado, cul nome "$1" $2.

Esto normalmente ye por bias de la besita a ua lhigaçon zatualizada ó de stória dua páigina que fui apagada.

Se nun fur este l causo, tu puodes tener ancuntrado algun porblema ne l software.
Por fabor, diç esto a un [[Special:ListUsers/sysop|admenistrador]], dezindo la lhigaçon.',
'missingarticle-rev'   => '(rebison#: $1)',
'missingarticle-diff'  => '(Dif.: $1, $2)',
'internalerror'        => 'Erro anterno',
'internalerror_info'   => 'Erro anterno: $1',
'filecopyerror'        => 'Nun fui possible copiar l fexeiro "$1" pa "$2".',
'filerenameerror'      => 'Nun fui possible renomear l fexeiro "$1" pa "$2".',
'filedeleteerror'      => 'Nun fui possible apagar l fexeiro "$1".',
'directorycreateerror' => 'Nun fui possible criar la diretorie "$1".',
'filenotfound'         => 'Nun fui possible achar l fexeiro "$1".',
'fileexistserror'      => 'Nun fui possible grabar ne l fexeiro "$1": yá eisiste',
'unexpected'           => 'Balor nun sperado: "$1"="$2".',
'formerror'            => 'Erro: Nun fui possible ambiar l formulário',
'badarticleerror'      => 'Esta acion nun puode ser feita nesta páigina.',
'badtitle'             => 'Títalo ambálido',
'badtitletext'         => 'La páigina que pediste ye ambálida, bazia, ó ua lhigaçon mal feita dun títalo antre-lhéngua ó antre-biqui.
Puode tener un ó mais carateres que nun puoden ser outelizados an títalos.',
'viewsource'           => 'Ber código',
'viewsourcefor'        => 'pa $1',
'actionthrottled'      => 'Acion lhemitada',
'viewsourcetext'       => 'Tu puodes ber i copiar l código desta páigina:',

# Virus scanner
'virus-scanfailed'     => 'la berificaçon falhou (código $1)',
'virus-unknownscanner' => 'antibírus çcoincido:',

# Login and logout pages
'yourname'                => 'Nome de Outelizador',
'yourpassword'            => 'Palabra chabe',
'yourpasswordagain'       => 'Repite la tue palabra-chabe',
'remembermypassword'      => 'Lhembrar-se de mi neste cumputador (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'          => 'L tou domínio',
'login'                   => 'Antrar',
'nav-login-createaccount' => 'Antrar / criar cuonta',
'loginprompt'             => 'Tenes que tener ls <i>cookies</i> atibos para te outenticares an{{SITENAME}}.',
'userlogin'               => 'Antrar / criar cuonta',
'logout'                  => 'Salir',
'userlogout'              => 'Salir',
'notloggedin'             => 'Por outenticar',
'nologin'                 => "Nun tenes ua cuonta? '''$1'''.",
'nologinlink'             => 'Criar ua cuonta',
'createaccount'           => 'Criar nuoba cuonta',
'gotaccount'              => "Yá tenes ua cuonta? '''$1'''.",
'gotaccountlink'          => 'Antrar',
'createaccountmail'       => 'por morada eiletrónica',
'loginerror'              => 'Erro de outenticaçon',
'loginsuccesstitle'       => 'Antreste cumo debe de ser',
'loginsuccess'            => "'''Stás agora lhigado a {{SITENAME}} cumo \"\$1\"'''.",
'nosuchuser'              => 'Num eisiste nanhun outelizador cul nome "$1".
Ls nomes de outelizador son defrentes an lhetra grande ó pequeinha.
Bei cumo screbiste, ó [[Special:UserLogin/signup|cria ua nuoba cuonta]].',
'nosuchusershort'         => 'Nun eisiste nanhun outelizador cul nome "<nowiki>$1</nowiki>".
Bei se l screbiste bien.',
'nouserspecified'         => 'Tenes que dezir un nome de outelizador.',
'wrongpassword'           => 'La palabra chabe ye ambálida.
Por fabor, spurmenta outra beç.',
'wrongpasswordempty'      => 'Tenes que poner la palabra chabe.
Por fabor, spurmenta outra beç.',
'passwordtooshort'        => 'La tue palabra chabe ye ambálida ó mui pequeinha.
Debe de tener pul menos {{PLURAL:$1|1 caracter|$1 caracteres}}.',
'password-name-match'     => 'La tue palabra-chabe ten que ser defrente de l tou nome de outelizador.',
'mailmypassword'          => 'Ambiar nuoba palabra chabe por carta eiletrónica',
'passwordremindertitle'   => 'Nuoba palabra chabe temporária an {{SITENAME}}',
'passwordremindertext'    => 'Alguém (l mais cierto tu, a partir de la morada de IP $1) pediu que le fusse ambiada ua nouba palabra-chabe pa {{SITENAME}} ($4).
Fui criada ua palabra-chabe temporária pa l outelizador "$2", i fui puosta outra beç cumo "$3". Causo tengas feito cun este perpósito, entra na tue cuonta i scolhe ua nouba palabra-chabe agora.
La tue palabra-chabe temporária queda fuora de balidade {{PLURAL:$5|nun die|an $5 dies}}.

Causo tenga sido outra pessona a fazer este pedido, ó causo tu yá te tengas lhembrado de la palabra-chabe i nun queiras demudar-la, squece esta mensaige i cuntina a outelizar la palabra-chabe antiga.',
'noemail'                 => 'Nun eisiste morada eiletrónica pa l outelizador "$1".',
'passwordsent'            => 'Ua nuoba palabra chabe stá a ambiada pa la morada de correio eiletrónico de l outelizador "$1".
Por fabor, bolta a fazer la outenticaçon al recebir-la.',
'eauthentsent'            => 'Ua carta eiletrónica de cunfirmaçon fui ambiada pa la morada de correio eiletrónico nomeada.
Antes de qualquier outra carta eiletrónica seia ambiada pa la cuonta, terás de seguir las anstruçones na carta eiletrónica,
de modo a cunfirmar que la cuonta ye mesmo la tue.',
'mailerror'               => 'Erro al ambiar la carta eiletrónica: $1',
'emailconfirmlink'        => 'Cunfirma la tue morada de correio eiletrónico',
'loginlanguagelabel'      => 'Lhéngua: $1',

# Password reset dialog
'resetpass' => 'Demudar palabra-chabe',
'retypenew' => 'Pon outra beç la nuoba palabra chabe:',

# Edit page toolbar
'bold_sample'     => 'Testo carregado',
'bold_tip'        => 'Testo a negrito',
'italic_sample'   => 'Testo eitálico',
'italic_tip'      => 'Testo an eitálico',
'link_sample'     => 'Títalo de la lhigaçon',
'link_tip'        => 'Lhigaçon anterna',
'extlink_sample'  => 'http://www.example.com títalo de la lhigaçon',
'extlink_tip'     => 'Lhigaçon sterna (lembra-te de l perfixo http://)',
'headline_sample' => 'Testo de cabeçailho',
'headline_tip'    => 'Cacho de nible 2',
'math_sample'     => 'Poner fórmula eiqui',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Poner testo nun-formatado eiqui',
'nowiki_tip'      => 'Nun fazer causo de la formataçon biqui',
'image_tip'       => 'Fexeiro ambutido',
'media_tip'       => 'Lhigaçon pa fexeiro',
'sig_tip'         => 'La tue assinatura, cun hora i data',
'hr_tip'          => 'Lhinha hourizontal (outeliza cun regra)',

# Edit pages
'summary'                          => 'Sumário:',
'subject'                          => 'Assunto/cabeçailho:',
'minoredit'                        => 'Marcar cumo eidiçon pequerrixa',
'watchthis'                        => 'Ber esta páigina',
'savearticle'                      => 'Grabar páigina',
'preview'                          => 'Ber cumo queda',
'showpreview'                      => 'Ber cumo queda',
'showdiff'                         => 'Amostrar altaraçones',
'anoneditwarning'                  => "'''Abiso''': Tu nun stás outenticado. L tou IP será registrado ne l stórico de las eidiçones desta páigina.",
'summary-preview'                  => 'Amostra de l sumário:',
'blockedtext'                      => 'L tou nome d\'outelizador ó morada de IP foi bloquiada

L bloqueio fui feito por $1. La rezon fui \'\'$2\'\'.

* Ampeço de l bloqueio: $8
* Balidade de l bloqueio: $6
* Çtino de l bloqueio: $7

Tu puodes cuntatar $1 ó outro [[{{MediaWiki:Grouppage-sysop}}|admenistrador]] pa çcutir subre l bloqueio.

Bei que nun poderás outelizar la funcionalidade "Cuntatar outelizador" se nun tubires ua counta neste wiki ({{SITENAME}}) cun ua morada eiletrónica bálida andicada an las tues [[Special:Preferences|preferéncias d\'outelizador]] i se tubires sido bloquiado de outelizar essa ferramienta.

La tue morada de IP atual ye $3 i l ID de l bloqueio ye $5. Por fabor, anclui un deilhes (ó dambos ls dous) dados an qualquier tentatibas de sclarecimentos.',
'loginreqtitle'                    => 'Outenticaçon oubrigatória',
'loginreqlink'                     => 'antrar',
'loginreqpagetext'                 => 'Percisas de $1 pa poder ber outras páiginas.',
'accmailtitle'                     => 'Palabra-chabe ambiada.',
'accmailtext'                      => "Ua palabra-chabe criada al calhas pa [[User talk:$1|$1]] fui ambiada pa $2.

La palabra-chabe para esta nuoba cuonta puode ser demudada na páigina pa ''[[Special:ChangePassword|demudar palabra-chabe]]'' apuis de la outenticaçon.",
'newarticle'                       => '(Nuoba)',
'newarticletext'                   => "Tu bejiteste ua lhigaçon para ua páigina que inda nun eisiste.
Para criar la páigina, ampeça a screbir an la caixa ambaixo (bei la [[{{MediaWiki:Helppage}}|páigina de ajuda]] pa mais detailhes).
Se stás eiqui por anganho, carrega ne l boton '''retornar''' de l tou nabegador de la Anternete.",
'noarticletext'                    => 'Nun hai neste sfergante testo nesta páigina.
Tu puodes [[Special:Search/{{PAGENAME}}|percurar pul títalo desta páigina]] noutras páiginas,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} percurar ls registros que téngan a ber],
ó [{{fullurl:{{FULLPAGENAME}}|action=edit}} eiditar esta páigina]</span>.',
'note'                             => "'''Abiso:'''",
'previewnote'                      => "'''Esto ye solo ua amostra; las altaraçones inda nun fúrun grabadas!'''",
'editing'                          => 'A eiditar $1',
'editingsection'                   => 'A eiditar $1 (cacho)',
'yourtext'                         => 'L tou testo',
'storedversion'                    => 'berson guardada',
'copyrightwarning'                 => "Por fabor, bei que todas las tues cuntribuiçones an {{SITENAME}} son cunsideradas cumo feitas ne ls termos de la lhicença $2 (bei $1 pa detailhes). Se nun quieres que l tou testo seia eiditado sin piedade i reçtribuído cunsante la gana, nun l ambies.<br />
Tu stás, al mesmo tiempo, a garantir-mos qu'esto ye algo screbido por ti, ó algo copiado d'ua fuonte de testos an domínio público ó parecido de teor lhibre.
'''NUN AMBIES TRABALHO PORTEGIDO POR DREITOS D'OUTOR SIEN L DEBIDO PERMISSO!'''",
'longpagewarning'                  => "'''Abiso: Esta páigina ten$1 kilobytes; alguns
nabegadores de la anternete ténen porblemas al eiditar páiginas cun mais de 32 kb.
Por fabor, pensa an scachar la páigina an cachos mais pequeinhos.'''",
'templatesused'                    => '{{PLURAL:$1|Modelo ousado|Modelos ousados}} nesta páigina:',
'templatesusedpreview'             => '{{PLURAL:$1|Modelo outelizado|Modelos outelizados}} neste amostra:',
'template-protected'               => '(portegida)',
'template-semiprotected'           => '(semi-protegida)',
'hiddencategories'                 => 'Esta páigina faç parte {{PLURAL:$1|dua catadorie scundida|$1 duas catadories scundidas}}:',
'nocreatetext'                     => '{{SITENAME}} tem restringida la possibilidade de criar nuobas páginas.
Pode boltar atrás i editar unha página yá eisistente, o [[Special:UserLogin|autenticar-se o criar unha cuonta]].',
'permissionserrorstext-withaction' => 'Tu nun tenes premisson pa $2, {{PLURAL:$1|pula seguinte rezon|pulas seguintes rezones}}:',
'recreate-moveddeleted-warn'       => "Abiso: Tu stás a criar ua páigina que yá fui d'atrás botada fuora.'''

Bei bien se ye própio cuntinar a eiditar esta páigina.
L registro de la eileminaçon i de l arrastrar ye amostrado eiqui por cumbeniéncia:",
'moveddeleted-notice'              => 'Esta páigina fui apagada.
L registro de la eileminaçon i de l arrastrar stá ambaixo pa refréncia.',

# History pages
'viewpagelogs'           => 'Ber registros pa esta páigina',
'currentrev'             => 'Rebison atual',
'currentrev-asof'        => 'Eidiçon atual cumo $1',
'revisionasof'           => 'Eidiçon cumo la de $1',
'revision-info'          => 'Rebison de $1 por $2',
'previousrevision'       => "← Berson d'atrás",
'nextrevision'           => 'Berson mais nuoba→',
'currentrevisionlink'    => 'Ber berson atual',
'cur'                    => 'atu',
'last'                   => 'redadeiro',
'page_first'             => 'purmeira',
'page_last'              => 'redadeira',
'histlegend'             => 'Scuolhe de defrénça: marca las caixas an ua de las bersones que queiras cumparar i carrega ne l boton.<br />
Legenda: (atu) = defrénças de la berson atual,
(red) = defrénça de la redadeira berson, m = eidiçon pequerrixa',
'history-fieldset-title' => 'Nabegar pul stórico',
'histfirst'              => 'Mais antigas',
'histlast'               => 'Redadeiras',

# Revision feed
'history-feed-item-nocomment' => '$1 a $2',

# Revision deletion
'rev-delundel'      => 'amostrar/scunder',
'revdel-restore'    => 'Demudar besiblidade',
'revdelete-content' => 'cuntenido',
'revdelete-uname'   => 'nome de outelizador',

# History merging
'mergehistory-submit' => 'Misturar eidiçones',

# Merge log
'revertmerge' => 'Çfazer ounion',

# Diffs
'history-title'           => 'Stórico de eidiçones de "$1"',
'difference'              => '(Defréncias antre rebisones)',
'lineno'                  => 'Lhinha $1:',
'compareselectedversions' => 'Cumparar las bersones marcadas',
'editundo'                => 'çfazer',
'diff-multi'              => '({{PLURAL:$1|ua eidiçon antermédia nun stá a ser amostrada|$1 eidiçones antermédias nun stan a ser amostradas}}.)',

# Search results
'searchresults'             => 'Resultados de la percura',
'searchresults-title'       => 'Resultados de la percura por "$1"',
'searchresulttext'          => 'Pa mais anformaçones subre cumo percurar an {{SITENAME}}, bei [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Tu percureste por \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|páiginas ampeçadas por "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|páiginas que apuntan pa "$1"]])',
'searchsubtitleinvalid'     => 'Tu percureste por "$1"',
'notitlematches'            => 'Nanhun títalo de páigina bate cierto cula percura',
'notextmatches'             => 'Nun fui possible achar, ne l cuntenido de las páiginas, la palabra percurada',
'prevn'                     => 'anteriores {{PLURAL:$1|$1}}',
'nextn'                     => 'próssimos {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Ber ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:Conteúdos',
'searchprofile-images'      => 'Multimédia',
'searchprofile-everything'  => 'Todo',
'searchprofile-advanced'    => 'Abançado',
'search-result-size'        => '$1 ({{PLURAL:$2|1 palabra|$2 palabras}})',
'search-redirect'           => '(ancaminamiento pa $1)',
'search-section'            => '(cacho $1)',
'search-suggest'            => 'Será que queries dezir: $1',
'search-interwiki-caption'  => 'Porjetos armanos',
'search-interwiki-default'  => 'Resultados de $1:',
'search-interwiki-more'     => '(mais)',
'search-mwsuggest-enabled'  => 'cun sugestones',
'search-mwsuggest-disabled' => 'sien sugestones',
'searchall'                 => 'todos',
'nonefound'                 => "'''Abiso''': solo alguns spácios nominales son percurados por oumisson. Spurmenta outelizar l perfixo ''all:'' na percura, pa percurar por todos ls cuntenidos desta Biqui (até páiginas de çcusson, modelos etc), ó mesmo, outelizando l spácio nominal que queiras cumo perfixo.",
'powersearch'               => 'Percura Abançada',
'powersearch-legend'        => 'Percura abançada',
'powersearch-ns'            => 'Percurar ne ls spácios nominales:',
'powersearch-redir'         => 'Listar ancaminamientos',
'powersearch-field'         => 'Percurar',

# Preferences page
'preferences'               => 'Perfréncias',
'mypreferences'             => 'Las mies perfréncias',
'prefs-skin'                => 'Maçcarilha',
'skin-preview'              => 'Amostrar',
'prefs-math'                => 'Matemática',
'prefs-datetime'            => 'Data i hora',
'prefs-personal'            => 'Calantriç',
'prefs-rc'                  => 'Redadeiras alteraçones',
'saveprefs'                 => 'Grabar',
'prefs-editing'             => 'Oupçones de eidiçon',
'rows'                      => 'Lhinhas:',
'servertime'                => 'Hora de l serbidor:',
'timezoneregion-africa'     => 'África',
'timezoneregion-america'    => 'América',
'timezoneregion-antarctica' => 'Antártida',
'timezoneregion-arctic'     => 'Ártico',
'timezoneregion-asia'       => 'Ásia',
'timezoneregion-atlantic'   => 'Ouceano Atlântico',
'timezoneregion-australia'  => 'Oustrália',
'timezoneregion-europe'     => 'Ouropa',
'timezoneregion-indian'     => 'Ouceano Índico',
'timezoneregion-pacific'    => 'Ouceano Pacífico',
'default'                   => 'defeito',
'prefs-files'               => 'Fexeiros',
'prefs-emailconfirm-label'  => 'Cunfirmaçon de l correio eiletrónico:',
'youremail'                 => 'Morada de correio eiletrónico:',
'username'                  => 'Nome de outelizador:',
'uid'                       => 'Númaro de eidentificaçon:',
'prefs-memberingroups'      => 'Nembro {{PLURAL:$1|de l grupo|de ls grupos}}:',
'yourrealname'              => 'Nome berdadeiro:',
'yourlanguage'              => 'Lhéngua:',
'yournick'                  => 'Assinatura:',
'yourgender'                => 'Sexo:',
'gender-unknown'            => 'Nun specificado',
'gender-male'               => 'Home',
'gender-female'             => 'Mulhier',
'email'                     => 'Morada Eiletrónica',
'prefs-help-realname'       => 'L nome berdadeiro ye oupcional.
Causo l çponiblizes, este será outelizado pa te dar crédito pul tou trabalho.',
'prefs-signature'           => 'Assinatura',
'prefs-dateformat'          => 'Formato de la data',

# User rights
'editusergroup'           => 'Eiditar grupos de outelizadores',
'userrights-groupsmember' => 'Nembro de:',

# Groups
'group'            => 'Grupo:',
'group-user'       => 'Outelizadores',
'group-bot'        => 'Rubós',
'group-sysop'      => 'Admenistradores',
'group-bureaucrat' => 'Burocratas',

'group-bot-member'        => 'Rubó',
'group-sysop-member'      => 'Admenistrador',
'group-bureaucrat-member' => 'Burocrata',

'grouppage-bot'   => '{{ns:project}}:Rubós',
'grouppage-sysop' => '{{ns:project}}:Admenistradores',

# Rights
'right-edit'     => 'Eiditar páiginas',
'right-move'     => 'Arrastrar páiginas',
'right-movefile' => 'Arrastrar fexeiros',

# User rights log
'rightslog'  => 'Registro de dreitos de l outelizador',
'rightsnone' => '(nanhun)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'lher esta páigina',
'action-edit'       => 'eiditar esta páigina',
'action-createpage' => 'criar páiginas',
'action-move'       => 'arrastrar esta páigina',
'action-movefile'   => 'arrastrar este fexeiro',
'action-delete'     => 'apagar esta páigina',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|altaraçon|altaraçones}}',
'recentchanges'                  => 'Redadeiras altaraçones',
'recentchanges-legend'           => 'Oupçones de las redadeiras altaraçones',
'recentchanges-feed-description' => 'Acumpanha las redadeiras altaraçones de l biqui por esta semiente.',
'recentchanges-legend-newpage'   => '$1 - nuoba páigina',
'recentchanges-legend-minor'     => '$1 - eidiçon pequerrixa',
'rcnote'                         => "A seguir {{PLURAL:$1|stá listada '''ua''' altaraçon feita|stan '''$1''' altaraçones feitas}} {{PLURAL:$2|ne l redadeiro die|ne ls redadeiros '''$2''' dies}}, a partir de las $5 de $4.",
'rcnotefrom'                     => 'Alteraçones feitas zde <b>$2</b> (amostradas até <b>$1</b>).',
'rclistfrom'                     => 'Amostrar las noubas altaraçones a partir de $1',
'rcshowhideminor'                => '$1 eidiçones pequerrixas',
'rcshowhidebots'                 => '$1 robós',
'rcshowhideliu'                  => '$1 outelizadores registrados',
'rcshowhideanons'                => '$1 outelizadores anónimos',
'rcshowhidepatr'                 => '$1 eidiçones patrulhadas',
'rcshowhidemine'                 => '$1 mies eidiçones',
'rclinks'                        => 'Amostrar las redadeiras $1 altaraçones ne ls redadeiros $2 dies<br />$3',
'diff'                           => 'defr',
'hist'                           => 'stór',
'hide'                           => 'Scunder',
'show'                           => 'Amostrar',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc_categories_any'              => 'Qualquiera',
'newsectionsummary'              => '/* $1 */ nuobo cacho',
'rc-enhanced-expand'             => 'Amostrar detailhes (ye perciso JavaScript)',
'rc-enhanced-hide'               => 'Scunder detailhes',

# Recent changes linked
'recentchangeslinked'          => 'Altaraçones relacionadas',
'recentchangeslinked-feed'     => 'Alterações relacionadas',
'recentchangeslinked-toolbox'  => 'Alterações relacionadas',
'recentchangeslinked-title'    => 'Altaraçones que ténen a ber cun "$1"',
'recentchangeslinked-noresult' => 'Nun houbo alteraçones an páiginas relacionadas ne l anterbalo de tiempo.',
'recentchangeslinked-summary'  => "Esta páigina special amostra las redadeiras altaraçones de páiginas que téngan ua lhigaçon a outra (ó de nembros dua catadorie speceficada).
Páiginas que steian ne ls [[Special:Watchlist|tous begiados]] son amostradas an '''negrito'''.",
'recentchangeslinked-page'     => 'Nome de la páigina:',
'recentchangeslinked-to'       => 'Amostrar antes altaraçones a páiginas que téngan a ber cula páigina dada',

# Upload
'upload'          => 'Cargar fexeiro',
'uploadbtn'       => 'Cargar fexeiro',
'uploadlogpage'   => 'Registro de carregamiento',
'filename'        => 'Nome de l fexeiro',
'filesource'      => 'Fuonte:',
'ignorewarnings'  => 'Eignorar qualquiera abiso',
'savefile'        => 'Grabar fexeiro',
'uploadedimage'   => 'cargou "[[$1]]"',
'watchthisupload' => 'Begiar este fexeiro',

'upload-unknown-size' => 'Tamanho çcoincido',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error28' => 'Ultrapassado l tiempo lhemite pa l ambio de l fexeiro',

'license'        => 'Lhicença:',
'license-header' => 'Lhicença:',
'nolicense'      => 'Nanhua scolhida',

# Special:ListFiles
'imgfile'               => 'fexeiro',
'listfiles'             => 'Fexeiros',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Outelizador',
'listfiles_size'        => 'Tamanho',
'listfiles_description' => 'Çcriçon',

# File description page
'file-anchor-link'          => 'Fexeiro',
'filehist'                  => 'Stórico de l fexeiro',
'filehist-help'             => 'Clique an ua data/hora para ber l fexeiro tal cumo el staba naquel sfergante.',
'filehist-current'          => 'atual',
'filehist-datetime'         => 'Data/Hora',
'filehist-thumb'            => 'Amostra',
'filehist-thumbtext'        => 'Amostra de la berson de las $1',
'filehist-user'             => 'Outelizador',
'filehist-dimensions'       => 'Tamanho',
'filehist-filesize'         => 'Tamanho de l fexeiro',
'filehist-comment'          => 'Comentairo',
'imagelinks'                => 'Lhigaçones de Fexeiros',
'linkstoimage'              => '{{PLURAL:$1|Esta páigina lhigan|Estas $1 páiginas lhigan}} este fexeiro:',
'nolinkstoimage'            => 'Nanhua páigina apunta pa este fexeiro.',
'sharedupload'              => 'Este fexeiro ye de $1 i puode ser outelizado por outros porjetos.',
'uploadnewversion-linktext' => 'Cargar ua nuoba berson deste fexeiro',
'shared-repo-from'          => 'de $1',

# File reversion
'filerevert-comment' => 'Comentairo:',

# File deletion
'filedelete'                  => 'Apagar $1',
'filedelete-legend'           => 'Apagar fexeiro',
'filedelete-submit'           => 'Apagar',
'filedelete-nofile'           => "'''$1''' nun eisiste.",
'filedelete-reason-otherlist' => 'Outra rezon',

# MIME search
'mimesearch' => 'Percura MIME',

# List redirects
'listredirects' => 'Amostrar ancaminamientos',

# Unused templates
'unusedtemplates' => 'Modelos nun outelizados',

# Random page
'randompage' => 'Páigina al calhas',

# Random redirect
'randomredirect' => 'Ancaminamiento al calhas',

# Statistics
'statistics'              => 'Statísticas',
'statistics-pages'        => 'Páiginas',
'statistics-views-total'  => 'Total de bejitas',
'statistics-users-active' => 'Outelizadores atibos',

'disambiguations' => 'Páigina de zambiguaçon',

'doubleredirects' => 'Ancaminamientos duplos',

'brokenredirects'        => 'Ancaminamientos scachados',
'brokenredirects-edit'   => 'eiditar',
'brokenredirects-delete' => 'Botar fuora',

'withoutinterwiki'        => 'Páiginas sin lhigaçones de lhénguas',
'withoutinterwiki-submit' => 'Amostrar',

'fewestrevisions' => 'Páiginas de cuntenido cun menos rebisones',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'nlinks'                  => '$1 {{PLURAL:$1|lhigaçon|lhigaçones}}',
'nmembers'                => '$1 {{PLURAL:$1|nembro|nembros}}',
'lonelypages'             => 'Páiginas uorfanas',
'uncategorizedpages'      => 'Páiginas sin catadories',
'uncategorizedcategories' => 'Catadories nun catadorizadas',
'uncategorizedimages'     => 'Eimaiges sin catadorie',
'uncategorizedtemplates'  => 'Modelos sin catadorie',
'unusedcategories'        => 'Catadories nun outelizadas',
'unusedimages'            => 'Fexeiros nun outelizados',
'popularpages'            => 'Páiginas populares',
'wantedcategories'        => 'Catadories pedidas',
'wantedpages'             => 'Páiginas pedidas',
'mostlinked'              => 'Páiginas mais lhigadas',
'mostlinkedcategories'    => 'Catadories cun mais nembros',
'mostlinkedtemplates'     => 'Modelos mais populares de lhigaçones',
'mostcategories'          => 'Páiginas de cuntenido cun mais catadories',
'mostimages'              => 'Eimaiges cun mais refréncias',
'mostrevisions'           => 'Páiginas de cuntenido cun mais rebisones',
'prefixindex'             => 'Todas las páiginas cun perfixo',
'shortpages'              => 'Páiginas pequeinhas',
'longpages'               => 'Páiginas cumpridas',
'deadendpages'            => 'Páiginas sin salida',
'protectedpages'          => 'Páginas protegidas',
'listusers'               => 'Lhista de outelizadores',
'newpages'                => 'Nuobas páiginas',
'ancientpages'            => 'Páiginas mais antigas',
'move'                    => 'Arrastrar',
'movethispage'            => 'Arrastrar esta páigina',
'pager-newer-n'           => '{{PLURAL:$1|1 nuoba|$1 nuobas}}',
'pager-older-n'           => '{{PLURAL:$1|1 atrasada|$1 atrasadas}}',

# Book sources
'booksources'               => 'Fuontes de lhibros',
'booksources-search-legend' => 'Percurar por fuontes de libros',
'booksources-go'            => 'Bota',

# Special:Log
'specialloguserlabel'  => 'Outelizador:',
'speciallogtitlelabel' => 'Títalo:',
'log'                  => 'Registros',
'all-logs-page'        => 'Todos ls registros públicos',

# Special:AllPages
'allpages'       => 'Todas las páiginas',
'alphaindexline' => '$1 a $2',
'nextpage'       => 'Próssima páigina ($1)',
'prevpage'       => "Páigina d'atrás ($1)",
'allpagesfrom'   => 'Amostrar páiginas ampeçando an:',
'allpagesto'     => 'Acabar de amostra las páiginas an:',
'allarticles'    => 'Todas las páiginas',
'allpagessubmit' => 'Bota',
'allpagesprefix' => 'Amostrar páiginas cul perfixo:',

# Special:Categories
'categories' => 'Catadories',

# Special:LinkSearch
'linksearch'    => 'Lhigaçones sternas',
'linksearch-ok' => 'Percurar',

# Special:ListUsers
'listusers-submit' => 'Amostrar',

# Special:Log/newusers
'newuserlogpage'          => 'Registro de criaçon de outelizadores',
'newuserlog-create-entry' => 'Nuobo outelizador',

# Special:ListGroupRights
'listgrouprights-rights'  => 'Dreitos',
'listgrouprights-members' => '(lista de nembros)',

# E-mail user
'emailuser' => 'Ambiar carta eiletrónica a este outelizador',
'emailfrom' => 'De:',
'emailto'   => 'Para:',

# Watchlist
'watchlist'         => 'Ls mius begiados',
'mywatchlist'       => 'Las mies páiginas begiadas',
'addedwatch'        => 'Ajuntada a las páiginas begiadas',
'addedwatchtext'    => "La páigina \"[[:\$1]]\" fui ajuntada a la tue [[Special:Watchlist|lista de páiginas begiadas]].
Altaraçones feturas na tal páigina i páiginas de çcusson a eilha associadas seran listadas alhá, cun la páigina aparecendo a '''negrito''' na [[Special:RecentChanges|lista de redadeiras altaraçones]], para que se pouda ancuntrar cun maior facelidade.",
'removedwatch'      => 'Botada fuora de las begiadas',
'removedwatchtext'  => 'La páigina "[[:$1]]" fui botada fuora de la [[Special:Watchlist|tue lista de páiginas begiadas]].',
'watch'             => 'Begiar',
'watchthispage'     => 'Begiar esta páigina',
'unwatch'           => 'Zantressar-se',
'watchlist-details' => '{{PLURAL:$1|$1 páigina begiada|$1 páiginas begiadas}}, fuora las páiginas de çcuçon.',
'wlshowlast'        => 'Ber redadeiras $1 horas $2 dies $3',
'watchlist-options' => 'Oupçones de la lista de begiados',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'A begiar...',
'unwatching' => 'A deixar de begiar...',

'changed' => 'demudada',
'created' => 'criada',

# Delete
'deletepage'            => 'Botar fuora páigina',
'delete-confirm'        => 'Botar fuora "$1"',
'delete-legend'         => 'Botar fuora',
'historywarning'        => 'Abiso: La páigina que stás quaije a botar fuora ten un stórico:',
'confirmdeletetext'     => "Stás quaije a botar fuora para siempre ua páigina ó ua eimaige i todos ls sous stóricos.
Por fabor, bei se ye esso que quieres fazer, que antendes las cunsequéncias i se esso stá d'acordo culas [[{{MediaWiki:Policy-url}}|políticas]].",
'actioncomplete'        => 'Acion acabada',
'deletedtext'           => '"<nowiki>$1</nowiki>" fue elhiminada.
Consulte $2 para um registo de eliminações recentes.',
'deletedarticle'        => 'botado pa la rue "[[$1]]"',
'dellogpage'            => 'Registro de botado fuora',
'deletecomment'         => 'Rezon:',
'deleteotherreason'     => 'Rezon adicional:',
'deletereasonotherlist' => 'Outra rezon',

# Rollback
'rollbacklink' => 'retornar',

# Protect
'protectlogpage'              => 'Registro de porteçon',
'protectedarticle'            => 'porteger "[[$1]]"',
'modifiedarticleprotection'   => 'demudeste l nible de porteçon pa "[[$1]]"',
'prot_1movedto2'              => '[[$1]] foi movido para [[$2]]',
'protect-legend'              => 'Confirmar protecçon',
'protectcomment'              => 'Rezon:',
'protectexpiry'               => 'Data de balidade:',
'protect_expiry_invalid'      => 'La data de balidade ye ambálido.',
'protect_expiry_old'          => 'La data de balidade stá ne l passado.',
'protect-text'                => "Tu eiqui puodes ber i demudar ls nibles de porteçon pa esta páigina '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "La tue cuonta nun ten permissones pa demudar ls nibles de porteçon dua páigina.
Esta ye la cunfiguraçon atual pa la páigina '''$1''':",
'protect-cascadeon'           => 'Esta páigina ancontra-se portegida, ua beç que se ancontra ancluída {{PLURAL:$1|na páigina listada a seguir, portegida|nas páiginas listadas a seguir, portegidas}} cun la "porteçon porgressiba" atibada. Tu puodes demudar l nible de porteçon desta páigina, mas esso nun terá eifeito na "porteçon an cachon".',
'protect-default'             => 'Premitir todos ls outelizadores',
'protect-fallback'            => 'Ye perciso la outorizaçon "$1"',
'protect-level-autoconfirmed' => 'Bloquiar outelizadores nuobos i por registrar',
'protect-level-sysop'         => 'Solo admenistradores',
'protect-summary-cascade'     => '"an cachon"',
'protect-expiring'            => 'termina an $1 (UTC)',
'protect-cascade'             => 'Portege qualquiera páigina que steia ancluída nesta (porteçon an cachon)',
'protect-cantedit'            => 'Tu nun puodes demudar l nible de porteçon desta páigina, porque tu nun tener outorizaçon pa la eiditar.',
'protect-expiry-options'      => '1 hora:1 hour,1 die:1 day,1 sumana:1 week,2 sumanas:2 weeks,1 més:1 month,3 meses:3 months,6 meses:6 months,1 anho:1 year,anfenito:infinite',
'restriction-type'            => 'Permisson:',
'restriction-level'           => 'Nible de restriçon:',

# Restrictions (nouns)
'restriction-edit' => 'Eiditar',
'restriction-move' => 'Arrastrar',

# Undelete
'undeletebtn'      => 'Recuperar',
'undeletelink'     => 'ber/restourar',
'undeletedarticle' => 'restourado "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Spácio de nomes:',
'invert'         => 'Amberter scuolha',
'blanknamespace' => '(Percipal)',

# Contributions
'contributions'       => 'Cuntribuiçones de l outelizador',
'contributions-title' => 'Upas {{GENDER:$1|de l outelizador|de la outelizadora}} $1',
'mycontris'           => 'Las mies upas',
'contribsub2'         => 'Pa $1 ($2)',
'uctop'               => '(rebison atual)',
'month'               => 'De l més (i atrasados):',
'year'                => 'De l anho (i atrasados):',

'sp-contributions-newbies'     => 'Percurar solo an las cuntribuiçones de nuobas cuontas',
'sp-contributions-newbies-sub' => 'Pa nuobas cuontas',
'sp-contributions-blocklog'    => 'Registro de bloqueios',
'sp-contributions-talk'        => 'Cumbersa',
'sp-contributions-search'      => 'Percurar cuntribuiçones',
'sp-contributions-username'    => 'Morada de IP ó outelizador:',
'sp-contributions-submit'      => 'Percurar',

# What links here
'whatlinkshere'            => 'L que lhiga eiqui',
'whatlinkshere-title'      => 'Páiginas que lhígan a "$1"',
'whatlinkshere-page'       => 'Páigina:',
'linkshere'                => "Estas páiginas ténen lhigaçones pa '''[[:$1]]''':",
'nolinkshere'              => "Nun eisisten lhigaçones pa '''[[:$1]]'''.",
'isredirect'               => 'páigina de ancaminamiento',
'istemplate'               => 'ancluson',
'isimage'                  => "lhigaçon d'eimaige",
'whatlinkshere-prev'       => '{{PLURAL:$1|pa trás|$1 pa trás}}',
'whatlinkshere-next'       => '{{PLURAL:$1|próssimo|próssimos $1}}',
'whatlinkshere-links'      => '← lhigaçones',
'whatlinkshere-hideredirs' => '$1 ancaminamientos',
'whatlinkshere-hidetrans'  => '$1 anclusones',
'whatlinkshere-hidelinks'  => '$1 lhigaçones',
'whatlinkshere-filters'    => 'Filtros',

# Block/unblock
'blockip'                  => 'Bloquiar outelizador',
'ipboptions'               => '2 horas:2 hours,1 die:1 day,3 dias:3 days,1 sumana:1 week,2 sumanas:2 weeks,1 més:1 month,3 meses:3 months,6 meses:6 months,1 anho:1 year,anfenito:infinite',
'ipblocklist'              => 'IPs i outelizadores bloquiados',
'blocklink'                => 'bloquiar',
'unblocklink'              => 'zbloquiar',
'change-blocklink'         => 'altarar bloqueio',
'contribslink'             => 'contribs',
'blocklogpage'             => 'Registro de l bloqueio',
'blocklogentry'            => '"[[$1]]" fui bloquiado cun un tiempo de balidade de $2 $3',
'unblocklogentry'          => 'zbloqueste $1',
'block-log-flags-nocreate' => 'criaçon de cuontas zatibada',

# Move page
'move-page'        => 'Arrastrar $1',
'move-page-legend' => 'Mover página',
'movepagetext'     => "Outelizando este formulário tu puodes renomear ua páigina, arrastrando to l stórico para l nuobo títalo. L títalo anterior será transformado nun ancaminamiento para l nuobo.
Ye possible amanhar de forma outomática ancaminamientos que lhigen un títalo oureginal.
Causo scuolhas para que esso nun seia feito, bei se nun hai ancaminamientos [[Special:DoubleRedirects|dues bezes]] ó [[Special:BrokenRedirects|scachados]].
Ye de la tue respunsablidade tener la certeza de que las lhigaçones cuntinan a apuntar pa adonde dében.

Arrepara que la páigina '''nun''' será arrastrada se yá eisistir ua páigina cul nuobo títalo, a nun ser que steia bazio ó seia un ancaminamiento i nun tenga stórico de eidiçones. Esto quier dezir que puodes renomear outra beç ua páigina pa l nome que tenie antes de l anganho i que nun puodes subrescrebir ua páigina.

<b>CUIDADO!</b>
Esto puode ser ua altaraçon drástica i einesperada pa ua páigina popular; por fabor, ten la certeza de que antendes las cunsequéncias desto antes de cuntinar.",
'movepagetalktext' => "La páigina de \"çcusson\" associada, se eistir, será outomaticamente arrastrada, '''a nun ser que:'''
*Ua páigina de çcusson cun contenido yá eisista subre l nuobo títalo, ou
*Tu marques la caixa ambaixo.

Nestes causos, tu terás que arrastrar ou ajuntar la páigina a la mano, se assi quejires.",
'movearticle'      => 'Arrastrar páigina',
'newtitle'         => 'Pa nuobo títalo:',
'move-watch'       => 'Begiar esta páigina',
'movepagebtn'      => 'Arrastrar páigina',
'pagemovedsub'     => 'Páigina arrastrada cumo debe de ser',
'movepage-moved'   => '\'\'\'"$1" fui arrastrado pa "$2"\'\'\'',
'articleexists'    => 'Yá eisiste ua páigina cun este títalo, ou l títalo que scolhiste ye ambálido.
Por fabor, scuolhe outro nome.',
'talkexists'       => "'''La páigina an si fui arrastrada cun éisito. Inda assi, la páigina de çcusson nun fui arrastrada, ua beç que yá eisistie ua cun este títalo. Por fabor, ajunta-las a la mano.'''",
'movedto'          => 'arrastrado pa',
'movetalk'         => 'Arrastrar tamien la páigina de çcusson associada.',
'1movedto2'        => '[[$1]] fui arrastrado pa [[$2]]',
'1movedto2_redir'  => 'arrastreste [[$1]] pa [[$2]] nun ancaminamiento',
'movelogpage'      => "Registro d'arrastros",
'movereason'       => 'Rezon:',
'revertmove'       => 'poner al robés',

# Export
'export'          => 'Sportar páiginas',
'export-download' => 'Grabar cumo fexeiro',

# Namespace 8 related
'allmessages'            => 'Todas las mensaiges de l sistema',
'allmessagesname'        => 'Nome',
'allmessagesdefault'     => 'Testo por oumisson',
'allmessages-filter-all' => 'Todas',

# Thumbnails
'thumbnail-more'  => 'Oumentar',
'thumbnail_error' => 'Erro al criar eimaige pequeinha: $1',

# Import log
'importlogpage' => 'Registro de amportaçones',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Páigina d'outelizador",
'tooltip-pt-mytalk'               => 'Mie cumbersa',
'tooltip-pt-preferences'          => 'Las mies perfréncias',
'tooltip-pt-watchlist'            => 'Lhista de páiginas subre las quales stás a begiar las altaraçones.',
'tooltip-pt-mycontris'            => 'Mies upas',
'tooltip-pt-login'                => 'Tu sós animado pa que te outentiques, inda que esso nun seia oubrigatório.',
'tooltip-pt-logout'               => 'Salir',
'tooltip-ca-talk'                 => 'Çcusson subre l cuntenido de la páigina',
'tooltip-ca-edit'                 => 'Tu puodes eiditar esta páigina. Por fabor, outeliza l boton "Ber cumo queda" antes de grabar.',
'tooltip-ca-addsection'           => 'Ampeçar un cacho nuobo',
'tooltip-ca-viewsource'           => 'Esta páigina stá portegida. Inda assi, tu puodes ber l sou código.',
'tooltip-ca-history'              => 'Eidiçones mais antigas deste páigina.',
'tooltip-ca-protect'              => 'Porteger esta páigina',
'tooltip-ca-delete'               => 'Botar fuora esta páigina',
'tooltip-ca-move'                 => 'Arrastrar esta páigina',
'tooltip-ca-watch'                => 'Ajuntar esta páigina als mius begiados',
'tooltip-ca-unwatch'              => 'Botar pa la rue esta páigina de ls mius begiados',
'tooltip-search'                  => 'Pesquisa {{SITENAME}}',
'tooltip-search-go'               => 'Ir pa ua páigina cun este nome, causo eisista',
'tooltip-search-fulltext'         => 'Percurar por páiginas cun este testo',
'tooltip-n-mainpage'              => 'Besitar la Páigina Percipal',
'tooltip-n-mainpage-description'  => 'Besitar la Páigina Percipal',
'tooltip-n-portal'                => 'Subre l porjeto, l que puodes fazer, adonde ancuntrar cousas',
'tooltip-n-currentevents'         => 'Ancuntrar anformaçon de fondo subre amboras atuales',
'tooltip-n-recentchanges'         => 'Lhista de redadeiras altaraçones nesta biqui.',
'tooltip-n-randompage'            => 'Ber páigina al calhas',
'tooltip-n-help'                  => 'Lhugar cun anformaçon pa ajuda.',
'tooltip-t-whatlinkshere'         => 'Todas las páiginas que se lhigan eiqui',
'tooltip-t-recentchangeslinked'   => 'Redadeiras altaraçones an páiginas que ténen a ber cun esta',
'tooltip-feed-rss'                => 'Feed RSS pa esta páigina',
'tooltip-feed-atom'               => 'Feed Atom pa esta páigina',
'tooltip-t-contributions'         => "Ber las cuntribuiçones d'este outelizador",
'tooltip-t-emailuser'             => 'Ambiar ua carta eiletrónica a este outelizador',
'tooltip-t-upload'                => 'Cargar eimaiges ó fexeiros',
'tooltip-t-specialpages'          => 'Todas las páiginas speciales',
'tooltip-t-print'                 => 'Berson pa ampremir desta páigina',
'tooltip-t-permalink'             => 'Lhigaçon pa siempre desta berson desta páigina',
'tooltip-ca-nstab-main'           => 'Ber la páigina de l cuntenido',
'tooltip-ca-nstab-user'           => 'Ber la páigina de l outelizador',
'tooltip-ca-nstab-special'        => 'Esta ye ua páigina special, nun puode ser eiditada.',
'tooltip-ca-nstab-project'        => 'Ber la páigina de l porjeto',
'tooltip-ca-nstab-image'          => 'Ber la páigina de l fexeiro',
'tooltip-ca-nstab-template'       => 'Ber l modelo',
'tooltip-ca-nstab-help'           => 'Ber la páigina de ajuda',
'tooltip-ca-nstab-category'       => 'Ber la páigina de la catadorie',
'tooltip-minoredit'               => 'Marcar cumo eidiçon pequerrixa',
'tooltip-save'                    => 'Grabar las tues altaraçones',
'tooltip-preview'                 => 'Bei purmeiro las altaraçones, por fabor outeliza esto antes de grabar!',
'tooltip-diff'                    => 'Amostrar altaraçones que faziste neste testo.',
'tooltip-compareselectedversions' => 'Ber las defréncias antre las dues bersones marcadas desta páigina.',
'tooltip-watch'                   => 'Ajuntar esta páigina als tous begiados',
'tooltip-rollback'                => '"{{int:rollbacklink}}" çfazer, cun un solo clique, las eidiçones de l redadeiro eiditor desta páigina.',
'tooltip-undo'                    => '"Çfazer" çfaç esta eidiçoni abre ls campos de eidiçon ne l modo "ber cumo queda".
Premite ajuntar la rezon de la eidiçon ne l sumário.',

# Skin names
'skinname-standard'    => 'Clássico',
'skinname-nostalgia'   => 'Suidade',
'skinname-cologneblue' => 'Azul',
'skinname-monobook'    => 'Lhibro',
'skinname-myskin'      => 'Piel',
'skinname-chick'       => 'Cipe-Çape',
'skinname-simple'      => 'Simpre',
'skinname-modern'      => 'Moderno',

# Browsing diffs
'previousdiff' => "← Eidiçon d'atrás",
'nextdiff'     => 'Redadeira eidiçon →',

# Media information
'file-info-size'       => '($1 × $2 pixel, tamanho: $3, tipo MIME: $4)',
'file-nohires'         => '<small>Sin resoluçon maior çponible.</small>',
'svg-long-desc'        => '(fexeiro SVG, de $1 × $2 pixeles, tamanho: $3)',
'show-big-image'       => 'Resoluçon cumpleta',
'show-big-image-thumb' => '<small>Tamanho desta prebison: $1 × $2 pixeles</small>',

# Special:NewFiles
'newimages' => 'Galerie de nuobos fexeiros',
'noimages'  => 'Nun hai nada pa ber.',
'ilsubmit'  => 'Percurar',

# Bad image list
'bad_image_list' => 'L formato ye l seguinte:

Solo son cunsiderados cousas de la lista (lhinhas ampeçadas por *). La purmeira lhigaçon nua lhinha debe ser ua lhigaçon pa ua "bad image".
Lhigaçones a seguir na mesma lhinha son cunsideradas eicepçones, i.e. artigos adonde la eimaige puode acuntecer "inline".',

# Metadata
'metadata'          => 'Metadados',
'metadata-help'     => "Este fexeiro ten mais anformaçon, l mais cierto ajuntada a partir de la máquina de retratos ó de l ''scanner'' outelizada para l criar.
Causo l fexeiro tenga sido demudado a partir de l sou stado oureginal, alguns detailhes poderán nun amostrar por cumpleto las altaraçones feitas.",
'metadata-expand'   => 'Amostrar mais detailhes',
'metadata-collapse' => 'Scunder mais detailhes',
'metadata-fields'   => 'Ls campos de metadados EXIF amostrados nesta mensaige poderán star persentes an la eisebiçon de la páigina de la eimaige quando la tabela de metadados stubir ne l modo "spandida". Outros poderán star scundidos por oumisson.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'       => 'Ancho',
'exif-imagelength'      => 'Altura',
'exif-imagedescription' => 'Títalo de la eimaige',
'exif-artist'           => 'Outor',
'exif-colorspace'       => 'Spácio de quelor',
'exif-contrast'         => 'Cuntraste',

'exif-orientation-1' => 'Normal',

'exif-exposureprogram-1' => 'Manual',

'exif-scenecapturetype-2' => 'Retrato',

'exif-gaincontrol-0' => 'Nanhun',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Suabe',
'exif-contrast-2' => 'Duro',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Suabe',
'exif-sharpness-2' => 'Duro',

'exif-subjectdistancerange-0' => 'Çcoincido',

# External editor support
'edit-externally'      => 'Eiditar este fexeiro outelizando ua aplicaçon sterna',
'edit-externally-help' => '(Bei las [http://www.mediawiki.org/wiki/Manual:External_editors anstruçones de anstalaçon] pa mais anformaçon).',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todo',
'imagelistall'     => 'todo',
'watchlistall2'    => 'todas',
'namespacesall'    => 'todas',
'monthsall'        => 'todos',

# action=purge
'confirm_purge_button' => 'Stá bien',

# Multipage image navigation
'imgmultipageprev' => '← páigina atrasada',
'imgmultipagenext' => 'páigina seguinte →',
'imgmultigo'       => 'Bota!',
'imgmultigoto'     => 'Ir pa páigina $1',

# Table pager
'ascending_abbrev'  => 'chubir',
'descending_abbrev' => 'decer',
'table_pager_next'  => 'Páigina seguinte',
'table_pager_prev'  => 'Páigina atrasada',
'table_pager_first' => 'Purmeira páigina',
'table_pager_last'  => 'Redadeira páigina',
'table_pager_limit' => 'Amostrar $1 antradas por páigina',
'table_pager_empty' => 'Sien resultados',

# Live preview
'livepreview-loading' => 'A cargar...',
'livepreview-ready'   => 'A cargar... Pronto!',

# Watchlist editor
'watchlistedit-raw-titles' => 'Títalos',

# Watchlist editing tools
'watchlisttools-view' => 'Ber altaraçones amportantes',
'watchlisttools-edit' => 'Ber i eiditar ls mius begiados',
'watchlisttools-raw'  => 'Ediçon bruta da lhista de ls bigiados',

# Special:Version
'version'                  => 'Berson',
'version-specialpages'     => 'Páiginas Speciales',
'version-variables'        => 'Bariables',
'version-other'            => 'Outro',
'version-license'          => 'Licença',
'version-software-product' => 'Perduto',
'version-software-version' => 'Berson',

# Special:FilePath
'filepath'        => 'Camino de l fexeiro',
'filepath-page'   => 'Fexeiro',
'filepath-submit' => 'Camino',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Nome de l fexeiro',
'fileduplicatesearch-submit'   => 'Percurar',

# Special:SpecialPages
'specialpages'                 => 'Páiginas speciales',
'specialpages-group-login'     => 'Antrar / anscrebir',
'specialpages-group-pages'     => 'Lhistas de páiginas',
'specialpages-group-pagetools' => 'Ferramienta de páiginas',

# Special:Tags
'tags-title' => 'Eitiquetas',
'tags-edit'  => 'eiditar',

);
