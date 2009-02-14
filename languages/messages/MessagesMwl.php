<?php
/** Mirandese (Mirandés)
 *
 * @ingroup Language
 * @file
 *
 * @author Cecílio
 * @author MCruz
 * @author Malafaya
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
	'redirect'              => array( '0', '#ANCAMINAR', '#REDIRECT' ),
	'img_right'             => array( '1', 'dreita', 'right' ),
	'img_left'              => array( '1', 'squierda', 'left' ),
	'img_none'              => array( '1', 'nanhun', 'none' ),
	'img_center'            => array( '1', 'centro', 'center', 'centre' ),
	'img_middle'            => array( '1', 'meio', 'middle' ),
	'language'              => array( '0', '#LHENGUA:', '#LANGUAGE:' ),
	'filepath'              => array( '0', 'CAMINOFEXEIRO:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'eitiqueta', 'tag' ),
	'pagesize'              => array( '1', 'TAMANHOFEXEIRO', 'PAGESIZE' ),
	'staticredirect'        => array( '1', '_ANCAMINARSTATICO_', '__STATICREDIRECT__' ),
);

$messages = array(
'underline-always' => 'Siempre',
'underline-never'  => 'Nunca',

# Dates
'sunday'        => 'Demingo',
'monday'        => 'Segunda',
'tuesday'       => 'Terça',
'wednesday'     => 'Quarta',
'thursday'      => 'Quinta',
'friday'        => 'Sesta',
'saturday'      => 'Sábado',
'sun'           => 'Dem',
'mon'           => 'Seg',
'tue'           => 'Ter',
'wed'           => 'Qua',
'thu'           => 'Qui',
'fri'           => 'Ses',
'sat'           => 'Sáb',
'january'       => 'Janeiro',
'february'      => 'Febreiro',
'march'         => 'Márcio',
'april'         => 'Abril',
'may_long'      => 'Maio',
'june'          => 'Júnio',
'july'          => 'Júlio',
'august'        => 'Agosto',
'september'     => 'Setembre',
'october'       => 'Outubre',
'november'      => 'Novembre',
'december'      => 'Dezembre',
'january-gen'   => 'Janeiro',
'february-gen'  => 'Febreiro',
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
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Már',
'apr'           => 'Abr',
'may'           => 'Mai',
'jun'           => 'Jún',
'jul'           => 'Júl',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Out',
'nov'           => 'Nob',
'dec'           => 'Dez',

# Categories related messages
'category_header'        => 'Páiginas an la catadorie "$1"',
'subcategories'          => 'Subcatadories',
'category-media-header'  => 'Multimédia an la catadorie "$1"',
'category-empty'         => "''Esta catadorie neste sfergante nun ten nanhua páigina ó cuntenido multimédia.''",
'listingcontinuesabbrev' => 'cunt.',

'about'     => 'Subre',
'newwindow' => '(abre nua nuoba jinela)',
'cancel'    => 'Çfazer',
'qbfind'    => 'Percurar',
'qbedit'    => 'Eiditar',
'mytalk'    => 'Mie cumbersa',

'errorpagetitle'   => 'Erro',
'returnto'         => 'Retornar pa $1.',
'tagline'          => 'De {{SITENAME}}',
'help'             => 'Ajuda',
'search'           => 'Percura',
'searchbutton'     => 'Percurar',
'searcharticle'    => 'Bota',
'history'          => 'Stórico de la Páigina',
'history_short'    => 'Stórico',
'printableversion' => 'Bersion pa Ampremir',
'permalink'        => 'Lhigaçon pa siempre',
'edit'             => 'Eiditar',
'editthispage'     => 'Eiditar esta páigina',
'delete'           => 'Botar fuora',
'protect'          => 'Porteger',
'newpage'          => 'Nuoba páigina',
'talkpage'         => 'Çcutir esta páigina',
'talkpagelinktext' => 'Cumbersar',
'personaltools'    => 'Ferramientas pessonales',
'talk'             => 'Çcusson',
'views'            => 'Bejitas',
'toolbox'          => 'Caixa de Ferramentas',
'redirectedfrom'   => '(Redireccionado de <b>$1</b>)',
'redirectpagesub'  => 'Páigina de reancaminamiento',
'jumpto'           => 'Saltar a:',
'jumptonavigation' => 'nabegaçon',
'jumptosearch'     => 'percura',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Subre {{SITENAME}}',
'aboutpage'            => 'Project:Subre',
'copyrightpage'        => '{{ns:project}}:Dreitos de outor',
'currentevents'        => 'Amboras atuales',
'currentevents-url'    => 'Project:Amboras atuales',
'disclaimers'          => 'Abiso de Cuntenido',
'disclaimerpage'       => 'Project:Abiso giral',
'edithelp'             => 'Ajuda de eidiçon',
'edithelppage'         => 'Help:Eiditar',
'helppage'             => 'Help:Cuntenidos',
'mainpage'             => 'Páigina Percípal',
'mainpage-description' => 'Páigina Percípal',
'portal'               => 'Portal da quemunidade',
'portal-url'           => 'Project:Portal de la quemunidade',
'privacy'              => 'Política de privacidade',
'privacypage'          => 'Project:Política de pribacidade',

'retrievedfrom'       => 'Sacado an "$1"',
'youhavenewmessages'  => 'Tu tenes $1 ($2).',
'newmessageslink'     => 'nuobas mensaiges',
'newmessagesdifflink' => 'comparar com la penúltima revison',
'editsection'         => 'eiditar',
'editold'             => 'eiditar',
'editsectionhint'     => 'Eiditar cacho: $1',
'toc'                 => 'Tabela de cuntenido',
'showtoc'             => 'amostrar',
'hidetoc'             => 'scunder',
'site-rss-feed'       => 'Feed RSS $1',
'site-atom-feed'      => 'Feed Atom $1',
'page-rss-feed'       => 'Feed RSS de "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'Páigina de l outelizador',
'nstab-project'  => 'Páigina de porjeto',
'nstab-image'    => 'Fexeiro',
'nstab-template' => 'Modelo',
'nstab-category' => 'Catadorie',

# General errors
'badtitle'       => 'Títalo ambálido',
'badtitletext'   => 'La páigina que pediste ye ambálida, bazia, ó ua lhigaçon mal feita dun títalo antre-lhéngua ó antre-biqui.
Puode tener un ó mais carateres que nun puoden ser outelizados an títalos.',
'viewsource'     => 'Ber código',
'viewsourcefor'  => 'pa $1',
'viewsourcetext' => 'Tu puodes ber i copiar l código desta páigina:',

# Login and logout pages
'yourname'                => 'Nome de Outelizador',
'yourpassword'            => 'Palabra chabe',
'remembermypassword'      => 'Lhembrar-se de mi neste cumputador',
'login'                   => 'Antrar',
'nav-login-createaccount' => 'Antrar / criar cuonta',
'loginprompt'             => 'Tenes que tener ls <i>cookies</i> atibos para te outenticares an{{SITENAME}}.',
'userlogin'               => 'Antrar / criar cuonta',
'logout'                  => 'Salir',
'userlogout'              => 'Salir',
'nologin'                 => 'Nun tenes ua cuonta? $1.',
'nologinlink'             => 'Criar ua cuonta',
'createaccount'           => 'Criar nuoba cuonta',
'gotaccount'              => 'Yá tenes ua cuonta? $1.',
'gotaccountlink'          => 'Antrar',
'yourrealname'            => 'Nome berdadeiro:',
'prefs-help-realname'     => 'L nome berdadeiro ye oupcional.
Causo l çponiblizes, este será outelizado pa te dar crédito pul tou trabalho.',
'loginsuccesstitle'       => 'Antreste cumo debe de ser',
'loginsuccess'            => "'''Stás agora lhigado a {{SITENAME}} cumo \"\$1\"'''.",
'nosuchuser'              => 'Num eisiste nanhun outelizador cul nome "$1".
Bei l nome que metiste, ó [[Special:UserLogin/signup|cria ua nouba cuonta]].',
'nosuchusershort'         => 'Nun eisiste nanhun outelizador cul nome "<nowiki>$1</nowiki>".
Bei se l screbiste bien.',
'nouserspecified'         => 'Tenes que dezir un nome de outelizador.',
'wrongpassword'           => 'La palabra chabe ye ambálida. 
Por fabor, spurmenta outra beç.',
'wrongpasswordempty'      => 'Tenes que poner la palabra chabe. 
Por fabor, spurmenta outra beç.',
'passwordtooshort'        => 'La tue palabra chabe ye ambálida ó mui pequeinha.
Debe de tener pul menos {{PLURAL:$1|1 caracter|$1 caracteres}} i ser defrente de l tou nome de outelizador.',
'mailmypassword'          => 'Ambiar nuoba palabra chabe por carta eiletrónica',
'passwordremindertitle'   => 'Nuoba palabra chabe temporária an {{SITENAME}}',
'passwordremindertext'    => 'Alguien (l mais cierto tu, a partir de la morada de IP $1) pediu que le fusse ambiada ua nuoba palabra chabe pa {{SITENAME}} ($4).
La palabra chabe temporária pa l outilizador "$2" ye, a partir d\'agora, "$3". Causo essa tenga sido l tou perpósito, entra na tue cuonta i cria ua nuoba palabra chabe.

Causo tenga sido outra pessona a fazer este pedido, ou causo tu yá te tengas lhembrado de la tue palabra chabe i nun la queiras demudar, nun fagas causo desta mensaige i cuntina a outelizar la tue palabra chabe antiga.',
'noemail'                 => 'Nun eisiste morada eiletrónica pa l outelizador "$1".',
'passwordsent'            => 'Ua nuoba palabra chabe stá a ambiada pa la morada de correio eiletrónico de l outelizador "$1".
Por fabor, bolta a fazer la outenticaçon al recebir-la.',
'eauthentsent'            => 'Ua carta eiletrónica de cunfirmaçon fui ambiada pa la morada de correio eiletrónico nomeada.
Antes de qualquier outra carta eiletrónica seia ambiada pa la cuonta, terás de seguir las anstruçones na carta eiletrónica,
de modo a cunfirmar que la cuonta ye mesmo la tue.',

# Password reset dialog
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
'headline_tip'    => 'Cacho de níble 2',
'math_sample'     => 'Poner fórmula eiqui',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Anserir testo nun-formatado eiqui',
'nowiki_tip'      => 'Nun fazer causo de la formataçon wiki',
'image_tip'       => 'Fexeiro ambutido',
'media_tip'       => 'Lhigaçon pa fexeiro',
'sig_tip'         => 'La tue assinatura, cun hora i data',
'hr_tip'          => 'Lhinha hourizontal (outeliza cun regra)',

# Edit pages
'summary'                => 'Sumário:',
'subject'                => 'Assunto/cabeçailho:',
'minoredit'              => 'Marcar cumo eidiçon pequerrixa',
'watchthis'              => 'Ber esta páigina',
'savearticle'            => 'Grabar páigina',
'preview'                => 'Amostrar Purmeiro',
'showpreview'            => 'Amostrar prebison',
'showdiff'               => 'Amostrar alteraçones',
'anoneditwarning'        => "'''Abiso''': Tu nun stás outenticado. L tou IP será registrado ne l stórico de las eidiçones desta páigina.",
'summary-preview'        => 'Amostra de l sumário:',
'blockedtext'            => '<big>L tou nome d\'outelizador ó morada de IP foi bloquiada</big>

L bloqueio fui feito por $1. La rezon fui \'\'$2\'\'.

* Ampeço de l bloqueio: $8
* Balidade de l bloqueio: $6
* Çtino de l bloqueio: $7

Tu puodes cuntatar $1 ó outro [[{{MediaWiki:Grouppage-sysop}}|admenistrador]] pa çcutir subre l bloqueio.

Bei que nun poderás outelizar la funcionalidade "Cuntatar outelizador" se nun tubires ua counta neste wiki ({{SITENAME}}) cun ua morada eiletrónica bálida andicada an las tues [[Special:Preferences|preferéncias d\'outelizador]] i se tubires sido bloquiado de outelizar essa ferramienta.

La tue morada de IP atual ye $3 i l ID de l bloqueio ye $5. Por fabor, anclui un deilhes (ó dambos ls dous) dados an qualquier tentatibas de sclarecimentos.',
'newarticle'             => '(Nuoba)',
'newarticletext'         => "Tu seguíste ua lhigaçon para ua páigina que inda nun eisiste. 
Para criar la páigina, ampeça a screbir an la caixa ambaixo(bei la [[{{MediaWiki:Helppage}}|páigina de ajuda]] pa mais detailhes).
Se stás eiqui por anganho, carrega ne l boton '''retornar'''de l tou nabegador de la Anternete.",
'noarticletext'          => 'Nun eisiste atualmente testo nesta páigina; tu puodes [[Special:Search/{{PAGENAME}}|percurar pul títalo desta páigina noutras páiginas]] ó [{{fullurl:{{FULLPAGENAME}}|action=edit}} eiditar esta páigina].',
'previewnote'            => "'''Esto ye solo ua amostra; las alteraçones inda nun fúrun grabadas!'''",
'editing'                => 'A eiditar $1',
'editingsection'         => 'A eiditar $1 (cacho)',
'copyrightwarning'       => "Por fabor, bei que todas las tues cuntribuiçones an {{SITENAME}} son cunsideradas cumo feitas ne ls termos de la lhicença $2 (bei $1 pa detailhes). Se nun quieres que l tou testo seia eiditado sin piedade i reçtribuído cunsante la gana, nun l ambies.<br 
/>
Tu stás, al mesmo tiempo, a garantir-mos qu'esto ye algo screbido por ti, ó algo copiado d'ua fuonte de testos an domínio público ó parecido de teor lhibre.
'''NUN AMBIES TRABALHO PORTEGIDO POR DREITOS DE OUTOR SIEN LA DEBIDA PERMISSON!'''",
'longpagewarning'        => "'''Abiso: Esta páigina ten$1 kilobytes; alguns
nabegadores de la anternete ténen porblemas al eiditar páiginas cun mais de 32 kb.
Por fabor, pensa an scachar la páigina an cachos mais pequeinhos.'''",
'templatesused'          => 'Predefiniçons utilizadas nesta página:',
'templatesusedpreview'   => 'Modelos outelizados neste amostra:',
'template-protected'     => '(portegida)',
'template-semiprotected' => '(semi-protegida)',
'nocreatetext'           => '{{SITENAME}} tem restringida la possibilidade de criar nuobas páginas.
Pode boltar atrás i editar unha página yá eisistente, o [[Special:UserLogin|autenticar-se o criar unha cuonta]].',
'recreate-deleted-warn'  => "'''Abiso: Tu stás a criar ua páigina que yá fui d'atrás botada fuora.'''

Bei bien se ye aprópiado cuntinar a eiditar esta páigina.
L registro de quando esta páigina fui botada fuora ye amostrado a seguir, por quemodidade:",

# History pages
'viewpagelogs'           => 'Ber registros pa esta páigina',
'currentrev'             => 'Rebison atual',
'revisionasof'           => 'Eidiçon cumo la de $1',
'revision-info'          => 'Rebison de $1 por $2', # Additionally available: $3: revision id
'previousrevision'       => "← Berson d'atrás",
'nextrevision'           => 'Berçon mais nuoba→',
'currentrevisionlink'    => 'Ber berson atual',
'cur'                    => 'atu',
'last'                   => 'redadeiro',
'page_first'             => 'purmeira',
'page_last'              => 'redadeira',
'histlegend'             => "Scuolha de defrénça: marca las caixas an ua de las bersones que queiras cumparar i carrega ne l botpn.<br />
Legenda: (atu) = defrénças de la berson atual,
(ult) = defrénça de la berson d'atrás, m = eidiçon pequerrixa",
'history-fieldset-title' => 'Nabegar pul stórico',
'histfirst'              => 'Mais antigas',
'histlast'               => 'Redadeiras',

# Revision feed
'history-feed-item-nocomment' => '$1 a $2', # user at time

# Diffs
'history-title'           => 'Stórico de eidiçones de "$1"',
'difference'              => '(Defréncias antre rebisones)',
'lineno'                  => 'Lhinha $1:',
'compareselectedversions' => 'Acumparar las berçones marcadas',
'editundo'                => 'çfazer',
'diff-multi'              => '({{PLURAL:$1|ua eidiçon antermédia nun stá a ser amostrada|$1 eidiçones antermédias nun stan a ser amostradas}}.)',

# Search results
'noexactmatch'   => "'''Num eisiste ua página com l títalo \"\$1\".''' Você puode [[:\$1|criar tal página]].",
'prevn'          => 'anteriores $1',
'nextn'          => 'próssimos $1',
'viewprevnext'   => 'Ber ($1) ($2) ($3)',
'searchhelp-url' => 'Help:Conteúdos',
'powersearch'    => 'Percura Abançada',

# Preferences page
'preferences'   => 'Perfréncias',
'mypreferences' => 'Las mies preferencias',

'grouppage-sysop' => '{{ns:project}}:Administradores',

# User rights log
'rightslog' => 'Registro de dreitos de l outelizador',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|alteração|alterações}}',
'recentchanges'                  => 'Redadeiras alteraçones',
'recentchanges-feed-description' => 'Acumpanha las redadeiras alteraçones de l biqui por este feed.',
'rcnote'                         => "A seguir {{PLURAL:$1|stá listada '''ua''' alteraçon feita|stan '''$1''' alteraçones feitas}} {{PLURAL:$2|ne l redadeiro die|ne ls redadeiros '''$2''' dies}}, a partir de las $5 de $4.",
'rcnotefrom'                     => 'Alteraçones feitas zde <b>$2</b> (amostradas até <b>$1</b>).',
'rclistfrom'                     => 'Amostrar las noubas alteraçones a partir de $1',
'rcshowhideminor'                => '$1 eidiçones pequerrixas',
'rcshowhidebots'                 => '$1 robós',
'rcshowhideliu'                  => '$1 outelizadores registrados',
'rcshowhideanons'                => '$1 outelizadores anónimos',
'rcshowhidepatr'                 => '$1 eidiçones patrulhadas',
'rcshowhidemine'                 => '$1 mies eidiçones',
'rclinks'                        => 'Amostrar las redadeiras $1 alteraçones ne ls redadeiros $2 dies<br />$3',
'diff'                           => 'defr',
'hist'                           => 'stór',
'hide'                           => 'Scunder',
'show'                           => 'Amostrar',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',

# Recent changes linked
'recentchangeslinked'          => 'Alterações relacionadas',
'recentchangeslinked-title'    => 'Alteraçones que ténen a ber cun "$1"',
'recentchangeslinked-noresult' => 'Nun houbo alteraçones an páiginas relacionadas ne l anterbalo de tiempo.',
'recentchangeslinked-summary'  => "Esta páigina special mostra las redadeiras alteraçones de páiginas que téngan ua lhigaçon a outra (ó de membros de ua catadorie specificada).
Páiginas que steian an ls [[Special:Watchlist|tous begiados]] son amostradas an '''negrito'''.",

# Upload
'upload'        => 'Cargar fexeiro',
'uploadbtn'     => 'Cargar fexeiro',
'uploadlogpage' => 'Registro de carregamiento',
'uploadedimage' => 'cargou "[[$1]]"',

# Special:ListFiles
'listfiles' => 'Fexeiros',

# File description page
'filehist'                  => 'Stórico de l fexeiro',
'filehist-help'             => 'Clique an ua data/hora para ber l fexeiro tal cumo el staba naquel sfergante.',
'filehist-current'          => 'atual',
'filehist-datetime'         => 'Data/Hora',
'filehist-user'             => 'Outelizador',
'filehist-dimensions'       => 'Tamanho',
'filehist-filesize'         => 'Tamanho de l fexeiro',
'filehist-comment'          => 'Comentairo',
'imagelinks'                => 'Lhigaçones',
'linkstoimage'              => '{{PLURAL:$1|Esta páigina lhigan|Estas $1 páiginas lhigan}} este fexeiro:',
'nolinkstoimage'            => 'Nanhua páigina apunta pa este fexeiro.',
'sharedupload'              => 'Este fexeiro stá cumpartido i puode ser outelizado por outros porjetos.',
'noimage'                   => 'Nun eisiste nanhun fexeiro cun este nome, mas puodes $1',
'noimage-linktext'          => 'carga un',
'uploadnewversion-linktext' => 'Cargar ua nuoba berçon deste fexeiro',

# MIME search
'mimesearch' => 'Percura MIME',

# List redirects
'listredirects' => 'Amostrar ancaminamientos',

# Unused templates
'unusedtemplates' => 'Modelos nun outelizados',

# Random page
'randompage' => 'Páigina a la suorte',

# Random redirect
'randomredirect' => 'Ancaminamiento al calhas',

# Statistics
'statistics' => 'Çtatísticas',

'disambiguations' => 'Páigina de zambiguaçon',

'doubleredirects' => 'Ancaminamientos duplos',

'brokenredirects' => 'Ancaminamientos scachados',

'withoutinterwiki' => 'Páiginas sin lhigaçones de lhénguas',

'fewestrevisions' => 'Páiginas de cuntenido cun menos rebisones',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'nlinks'                  => '$1 {{PLURAL:$1|lhigaçon|lhigaçones}}',
'nmembers'                => '$1 {{PLURAL:$1|membro|membros}}',
'lonelypages'             => 'Páiginas uorfanas',
'uncategorizedpages'      => 'Páiginas sin catadories',
'uncategorizedcategories' => 'Catadories nun catadorizadas',
'uncategorizedimages'     => 'Eimaiges sin catadorie',
'uncategorizedtemplates'  => 'Modelos sin catadorie',
'unusedcategories'        => 'Catadories nun outelizadas',
'unusedimages'            => 'Fexeiros nun outelizados',
'wantedcategories'        => 'Catadories pedidas',
'wantedpages'             => 'Páiginas pedidas',
'mostlinked'              => 'Páiginas mais lhigadas',
'mostlinkedcategories'    => 'Catadories cun mais nembros',
'mostlinkedtemplates'     => 'Modelos mais populares de lhigaçones',
'mostcategories'          => 'Páiginas de cuntenido cun mais catadories',
'mostimages'              => 'Eimaiges cun mais refréncias',
'mostrevisions'           => 'Páiginas de cuntenido cun mais rebisones',
'prefixindex'             => 'Índice de perfixo',
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
'booksources' => 'Fuontes de lhibros',

# Special:Log
'specialloguserlabel'  => 'Outelizador:',
'speciallogtitlelabel' => 'Títalo:',
'log'                  => 'Registros',
'all-logs-page'        => 'Todos ls registros',

# Special:AllPages
'allpages'       => 'Todas las páiginas',
'alphaindexline' => '$1 a $2',
'nextpage'       => 'Próssima páigina ($1)',
'prevpage'       => 'Página anterior ($1)',
'allpagesfrom'   => 'Amostrar páiginas ampeçando an:',
'allarticles'    => 'Todas las páiginas',
'allpagessubmit' => 'Bota',
'allpagesprefix' => 'Amostrar páiginas cul perfixo:',

# Special:Categories
'categories' => 'Catadories',

# E-mail user
'emailuser' => 'Ambiar carta eiletrónica a este outelizador',

# Watchlist
'watchlist'         => 'Ls mius begiados',
'mywatchlist'       => 'Las mies páiginas begiadas',
'watchlistfor'      => "(para '''$1''')",
'addedwatch'        => 'Ajuntada a las páiginas begiadas',
'addedwatchtext'    => "La páigina \"[[:\$1]]\" fui ajuntada a la tue [[Special:Watchlist|lista de páiginas begiadas]].
Alteraçones feturas na tal páigina i páiginas de çcusson a eilha associadas seran listadas alhá, cun la páigina aparecendo a '''negrito''' na [[Special:RecentChanges|lista de redadeiras alteraçones]], para que se pouda ancuntrar cun maior facelidade.",
'removedwatch'      => 'Botada fuora de las páiginas begiados',
'removedwatchtext'  => 'La páigina "[[:$1]]" fui botada fuora de la [[Special:Watchlist|tue lista de páiginas begiadas]].',
'watch'             => 'Begiar',
'watchthispage'     => 'Begiar esta páigina',
'unwatch'           => 'Zantressar-se',
'watchlist-details' => '{{PLURAL:$1|$1 páigina begiada|$1 páiginas begiadas}}, fuora las páiginas de çcuçon.',
'wlshowlast'        => 'Ber redadeiras $1 horas $2 dies $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'A begiar...',
'unwatching' => 'A deixar de begiar...',

# Delete
'deletepage'            => 'Botar fuora páigina',
'historywarning'        => 'Abiso: La páigina que stás quaije a botar fuora ten un stórico:',
'confirmdeletetext'     => "Stás quaije a botar fuora para siempre ua páigina ó ua eimaige i to ls sou stórico.
Por fabor, bei se ye esso que quieres fazer, que antendes las cunsequéncias i se esso stá d'acordo culas [[{{MediaWiki:Policy-url}}|políticas]].",
'actioncomplete'        => 'Acion acabada',
'deletedtext'           => '"<nowiki>$1</nowiki>" fue elhiminada.
Consulte $2 para um registo de eliminações recentes.',
'deletedarticle'        => 'botado pa la rue "[[$1]]"',
'dellogpage'            => 'Registro de botado fuora',
'deletecomment'         => 'Rezon pa botar pa fuora:',
'deleteotherreason'     => 'Rezon adicional:',
'deletereasonotherlist' => 'Outra rezon',

# Rollback
'rollbacklink' => 'retornar',

# Protect
'protectlogpage'              => 'Registo de protecção',
'prot_1movedto2'              => '[[$1]] foi movido para [[$2]]',
'protect-legend'              => 'Confirmar protecçon',
'protectcomment'              => 'Comentairo:',
'protectexpiry'               => 'Data de balidade:',
'protect_expiry_invalid'      => 'La data de balidade ye ambálido.',
'protect_expiry_old'          => 'La data de balidade stá ne l passado.',
'protect-unchain'             => 'Zbloguiar permissones pa arrastrar',
'protect-text'                => "Tu eiqui puodes ber i demudar ls níbles de proteçon pa esta páigina '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "La tue cuonta nun ten permissones pa demudar ls níbles de proteçon de ua páigina.
Esta ye la configuraçon atual pa la páigina '''$1''':",
'protect-cascadeon'           => 'Esta páigina ancontra-se portegida, ua beç que se ancontra ancluída {{PLURAL:$1|na páigina listada que se segue, portegida|nas páiginas listadas que se seguen, portegidas}} cun la "porteçon porgressiba" atibada. Tu puodes demudar l níble de porteçon desta páigina, mas esso nun terá eifeito na "porteçon porgressiba".',
'protect-default'             => '(oumisson)',
'protect-fallback'            => 'Ye perciso la outorizaçon "$1"',
'protect-level-autoconfirmed' => 'Bloquiar outelizadores nun registrados',
'protect-level-sysop'         => 'Solo admenistradores',
'protect-summary-cascade'     => 'an cascata',
'protect-expiring'            => 'termina an $1 (UTC)',
'protect-cascade'             => 'Portege qualquier páigina que steia ancluída nesta (porteçon porgressiba)',
'protect-cantedit'            => 'Tu nun puodes demudar l níble de proteçon desta páigina, porque tu nun tener outorizaçon pa la eiditar.',
'protect-expiry-options'      => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 anho:1 year,indefinido:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Permisson:',
'restriction-level'           => 'Níble de restriçon:',

# Undelete
'undeletebtn' => 'Recuperar',

# Namespace form on various pages
'namespace'      => 'Spácio de nomes:',
'invert'         => 'Amberter scuolha',
'blanknamespace' => '(Percípal)',

# Contributions
'contributions' => 'Cuntribuiçones de l outelizador',
'mycontris'     => 'Las mies upas',
'contribsub2'   => 'Pa $1 ($2)',
'uctop'         => '(rebison atual)',
'month'         => 'De l més (i atrasados):',
'year'          => 'De l anho (i atrasados):',

'sp-contributions-newbies'     => 'Percurar solo an las cuntribuiçones de nuobas cuontas',
'sp-contributions-newbies-sub' => 'Pa nuobas cuontas',
'sp-contributions-blocklog'    => 'Registro de bloqueios',
'sp-contributions-search'      => 'Percurar cuntribuiçones',
'sp-contributions-username'    => 'Morada de IP ó outelizador:',

# What links here
'whatlinkshere'       => 'L que lhiga eiqui',
'whatlinkshere-title' => 'Páiginas que lhigan a "$1"',
'linkshere'           => "Estas páiginas ténen lhigaçones pa '''[[:$1]]''':",
'nolinkshere'         => "Nun eisisten lhigaçones pa '''[[:$1]]'''.",
'isredirect'          => 'páigina de ancaminamiento',
'istemplate'          => 'incluson',
'whatlinkshere-prev'  => '{{PLURAL:$1|pa trás|$1 pa trás}}',
'whatlinkshere-next'  => '{{PLURAL:$1|próssimo|próssimos $1}}',
'whatlinkshere-links' => '← lhigaçones',

# Block/unblock
'blockip'       => 'Bloquiar outelizador',
'ipboptions'    => '2 horas:2 hours,1 die:1 day,3 dias:3 days,1 sumana:1 week,2 sumanas:2 weeks,1 més:1 month,3 meses:3 months,6 meses:6 months,1 anho:1 year,anfenito:infinite', # display1:time1,display2:time2,...
'ipblocklist'   => 'IPs i outelizadores bloquiados',
'blocklink'     => 'bloquiar',
'unblocklink'   => 'zbloquiar',
'contribslink'  => 'contribs',
'blocklogpage'  => 'Registro de l bloqueio',
'blocklogentry' => '"[[$1]]" fui bloquiado cun un tiempo de spiraçon de $2 $3',

# Move page
'move-page-legend' => 'Mover página',
'movepagetext'     => "Outelizando este formulário tu puodes renomear ua páigina, arrastrando to l stórico para l nuobo títalo. L títalo anterior será transformado nun ancaminamiento para l nuobo.
Ye possible amanhar de forma outomática ancaminamientos que lhigen un títalo oureginal.
Causo scuolhas para que esso nun seia feito, bei se nun hai reancaminamientos [[Special:DoubleRedirects|dues bezes]] ó [[Special:BrokenRedirects|scachados]].
Ye de la tue respunsabilidade tener la certeza de que las lhigaçones cuntinan a apuntar pa adonde dében.

Note que la páigina '''nun''' será arrastrada se yá eisistir ua páigina cul nuobo títalo, a nun ser que steia bazio ó seia un ancaminamiento i nun tenga stórico de eidiçones. Esto quier dezir que puodes renomear outra beç ua páigina para l nome que tenie antes de l anganho i que nun puodes subrescrebir ua páigina.

<b>CUIDADO!</b>
Esto puode ser ua alteraçon drástica i einesperada pa ua páigina popular; por fabor, ten la certeza de que antendes las cunsequéncias desto antes de cuntinar.",
'movepagetalktext' => "La páigina de \"çcusson\" associada, se eistir, será outomaticamente arrastrada, '''a nun ser que:'''
*Ua páigina de çcusson cun contenido yá eisista subre l nuobo títalo, ou
*Tu marques la caixa ambaixo.

Nestes causos, tu terás que arrastrar ou ajuntar la páigina a la mano, se assi quejires.",
'movearticle'      => 'Arrastrar páigina',
'newtitle'         => 'Pa nuobo títalo:',
'move-watch'       => 'Begiar esta páigina',
'movepagebtn'      => 'Arrastrar páigina',
'pagemovedsub'     => 'Páigina arrastrada cumo debe de ser',
'movepage-moved'   => '<big>\'\'\'"$1" fui arrastrado pa "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Yá eisiste ua páigina cun este títalo, ou l títalo que scolhiste ye ambálido.
Por fabor, scuolhe outro nome.',
'talkexists'       => "'''La páigina an si fui arrastrada cun eisito. Inda assi, la páigina de çcusson nun fui arrastrada, ua beç que yá existie ua cun este títalo. Por fabor, ajunta-las a la mano.'''",
'movedto'          => 'arrastrado pa',
'movetalk'         => 'Mober tambien la página de çcusson associada.',
'1movedto2'        => '[[$1]] fui arrastrado pa [[$2]]',
'movelogpage'      => 'Registro de mobimientos',
'movereason'       => 'Rezon:',
'revertmove'       => 'reverter',

# Export
'export' => 'Sportar páiginas',

# Namespace 8 related
'allmessages' => 'Todas las mensaiges de l sistema',

# Thumbnails
'thumbnail-more'  => 'Oumentar',
'thumbnail_error' => 'Erro al criar eimaige pequeinha: $1',

# Import log
'importlogpage' => 'Registro de amportaçones',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "La mie páigina d'outelizador",
'tooltip-pt-mytalk'               => 'Páigina de la mie cumbersa',
'tooltip-pt-preferences'          => 'Las mies perfréncias',
'tooltip-pt-watchlist'            => 'Lista de páiginas subre las quales stás a begiar las alteraçones.',
'tooltip-pt-mycontris'            => 'Lhista das mies contribuiçons',
'tooltip-pt-login'                => 'Tu sós animado pa que te outentiques, inda que esso nun seia oubrigatório.',
'tooltip-pt-logout'               => 'Salir',
'tooltip-ca-talk'                 => 'Çcusson subre l cuntenido de la páigina',
'tooltip-ca-edit'                 => 'Tu puodes eiditar esta páigina. Por fabor, outeliza l boton "Ber cumo queda" antes de grabar.',
'tooltip-ca-addsection'           => 'Ajuntar cometairo a esta çcuçon.',
'tooltip-ca-viewsource'           => 'Esta páigina stá protegida. Inda assi, tu puodes ber l sou código.',
'tooltip-ca-protect'              => 'Porteger esta páigina',
'tooltip-ca-delete'               => 'Botar fuora esta páigina',
'tooltip-ca-move'                 => 'Arrastrar esta páigina',
'tooltip-ca-watch'                => 'Ajuntar esta páigina als mius begiados',
'tooltip-ca-unwatch'              => 'Botar pa la rue esta páigina de ls mius begiados',
'tooltip-search'                  => 'Pesquisa {{SITENAME}}',
'tooltip-n-mainpage'              => 'Bejitar la Páigina Percípal',
'tooltip-n-portal'                => 'Subre l porjeto, l que puodes fazer, adonde ancuntrar cousas',
'tooltip-n-currentevents'         => 'Ancuntrar anformaçon de fondo subre amboras atuales',
'tooltip-n-recentchanges'         => 'Lhista de redadeiras alteraçones nesta biqui.',
'tooltip-n-randompage'            => 'Ber páigina al calhas',
'tooltip-n-help'                  => 'Lhugar cun anformaçon pa ajuda.',
'tooltip-t-whatlinkshere'         => 'Todas las páiginas que se lhigan eiqui',
'tooltip-t-contributions'         => "Ber las cuntribuiçones d'este outelizador",
'tooltip-t-emailuser'             => 'Ambiar ua carta eiletrónica a este outelizador',
'tooltip-t-upload'                => 'Cargar eimaiges ó fexeiros',
'tooltip-t-specialpages'          => 'Todas las páiginas speciales',
'tooltip-ca-nstab-user'           => 'Ber la páigina de l outelizador',
'tooltip-ca-nstab-project'        => 'Ber la páigina de l porjeto',
'tooltip-ca-nstab-image'          => 'Ber la páigina de l fexeiro',
'tooltip-ca-nstab-template'       => 'Ber l modelo',
'tooltip-ca-nstab-help'           => 'Ber la páigina de ajuda',
'tooltip-ca-nstab-category'       => 'Ber la páigina de la catadorie',
'tooltip-minoredit'               => 'Marcar cumo eidiçon pequerrixa',
'tooltip-save'                    => 'Grabar las tues alteraçones',
'tooltip-preview'                 => 'Bei purmeiro las alteraçones, por fabor outeliza esto antes de grabar!',
'tooltip-diff'                    => 'Amostrar alteraçones que faziste neste testo.',
'tooltip-compareselectedversions' => 'Ber las defréncias antre las dues berçones marcadas desta páigina.',
'tooltip-watch'                   => 'Ajuntar esta páigina als tous begiados',

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

# Bad image list
'bad_image_list' => 'L formato ye l seguinte:

Solo son cunsiderados cousas de la lista (lhinhas ampeçadas por *). La purmeira lhigaçon nua lhinha debe ser ua lhigaçon pa ua "bad image".
Lhigaçones a seguir na mesma lhinha son cunsideradas eicepçones, i.e. artigos adonde la eimaige puode acuntecer "inline".',

# Metadata
'metadata'          => 'Metadados',
'metadata-help'     => "Este fexeiro ten anformaçon adicional, l mais cierto ajuntada a partir de la máquina de retratos ó de l ''scanner'' outelizada para l criar.
Causo l fexeiro tenga sido demudado a partir de l sou stado ouriginal, alguns detailhes poderán nun refletir por cumpleto las alteraçones feitas.",
'metadata-expand'   => 'Amostrar mais detailhes',
'metadata-collapse' => 'Scunder mais detailhes',
'metadata-fields'   => 'Ls campos de metadados EXIF amostrados nesta mensaige poderán star persentes an la eisebiçon de la páigina de la eimaige quando la tabela de metadados stubir ne l modo "spandida". Outros poderán star scundidos por oumisson.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'Eiditar este fexeiro outelizando ua aplicaçon sterna',
'edit-externally-help' => '(Bei las [http://www.mediawiki.org/wiki/Manual:External_editors anstruçones de anstalaçon] pa mais anformaçon).',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'todas',
'namespacesall' => 'todas',
'monthsall'     => 'todos',

# Watchlist editing tools
'watchlisttools-view' => 'Ber alteraçones amportantes',
'watchlisttools-edit' => 'Ber i eiditar ls mius begiados',
'watchlisttools-raw'  => 'Ediçon bruta da lhista de ls bigiados',

# Special:Version
'version' => 'Berson', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'Páiginas speciales',

);
