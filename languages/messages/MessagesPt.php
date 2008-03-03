<?php
/** Portuguese (Português)
 * This translation was made by:
 *  - Yves Marques Junqueira
 *  - Rodrigo Calanca Nishino
 *  - Nuno Tavares
 *  - Paulo Juntas
 *  - Manuel Menezes de Sequeira
 *  - Sérgio Ribeiro
 * from the Portuguese Wikipedia
 *
 * @package MediaWiki
 * @subpackage Language
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media', # -2
	NS_SPECIAL          => 'Especial', # -1
	NS_MAIN             => '', # 0
	NS_TALK             => 'Discussão', # 1
	NS_USER             => 'Usuário',
	NS_USER_TALK        => 'Usuário_Discussão',
/*
	Above entries are for PT_br. The following entries should
    be used instead. But:

     DO NOT USE THOSE ENTRIES WITHOUT MIGRATING STUFF ON
     WIKIMEDIA WEB SERVERS FIRST !! You will just break a lot
     of links 8-)

	NS_USER             => 'Utilizador', # 2
	NS_USER_TALK        => 'Utilizador_Discussão', # 3
*/
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_Discussão', # 5
	NS_IMAGE            => 'Imagem', # 6
	NS_IMAGE_TALK       => 'Imagem_Discussão', # 7
	NS_MEDIAWIKI        => 'MediaWiki', # 8
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Discussão', # 9
	NS_TEMPLATE         => 'Predefinição', # 10
	NS_TEMPLATE_TALK    => 'Predefinição_Discussão', # 11
	NS_HELP             => 'Ajuda', # 12
	NS_HELP_TALK        => 'Ajuda_Discussão', # 13
	NS_CATEGORY         => 'Categoria', # 14
	NS_CATEGORY_TALK    => 'Categoria_Discussão' # 15
);

$quickbarSettings = array(
	'Nenhuma', 'Fixo à esquerda', 'Fixo à direita', 'Flutuando à esquerda', 'Flutuando à direita'
);

$skinNames = array(
	'standard' => 'Clássico',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Azul colonial',
	'davinci' => 'DaVinci',
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT', '#redir'    ),
	'notoc'                  => array( 0,    '__NOTOC__'              ),
	'forcetoc'               => array( 0,    '__FORCETOC__'           ),
	'toc'                    => array( 0,    '__TOC__'                ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__'      ),
	'start'                  => array( 0,    '__START__'              ),
	'currentmonth'           => array( 1,    'CURRENTMONTH'           ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME'       ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV'     ),
	'currentday'             => array( 1,    'CURRENTDAY'             ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME'         ),
	'currentyear'            => array( 1,    'CURRENTYEAR'            ),
	'currenttime'            => array( 1,    'CURRENTTIME'            ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES'       ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES'          ),
	'pagename'               => array( 1,    'PAGENAME'               ),
	'pagenamee'              => array( 1,    'PAGENAMEE'              ),
	'namespace'              => array( 1,    'NAMESPACE'              ),
	'msg'                    => array( 0,    'MSG:'                   ),
	'subst'                  => array( 0,    'SUBST:'                 ),
	'msgnw'                  => array( 0,    'MSGNW:'                 ),
	'end'                    => array( 0,    '__END__'                ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'     ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'right', 'direita'       ),
	'img_left'               => array( 1,    'left', 'esquerda'       ),
	'img_none'               => array( 1,    'none', 'nenhum'         ),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre'       ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' ),
	'img_page'               => array( 1,    'page=$1', 'page $1'     ),
	'int'                    => array( 0,    'INT:'                   ),
	'sitename'               => array( 1,    'SITENAME'               ),
	'ns'                     => array( 0,    'NS:'                    ),
	'localurl'               => array( 0,    'LOCALURL:'              ),
	'localurle'              => array( 0,    'LOCALURLE:'             ),
	'server'                 => array( 0,    'SERVER'                 ),
	'servername'             => array( 0,    'SERVERNAME'             ),
	'scriptpath'             => array( 0,    'SCRIPTPATH'             ),
	'grammar'                => array( 0,    'GRAMMAR:'               ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1,    'CURRENTWEEK'            ),
	'currentdow'             => array( 1,    'CURRENTDOW'             ),
	'revisionid'             => array( 1,    'REVISIONID'             ),
);

$separatorTransformTable = array(',' => ' ', '.' => ',' );
#$linkTrail = '/^([a-z]+)(.*)$/sD';# ignore list



$messages = array(

# User preference toggles
'tog-underline' => 'Sublinhar hiperligações',
'tog-highlightbroken' => 'Formatar links quebrados <a href="" class="new">como isto</a> (alternativa: como isto<a href="" class="internal">?</a>).',
'tog-justify'   => 'Justificar parágrafos',
'tog-hideminor' => 'Esconder edições secundárias nas mudanças recentes',
'tog-extendwatchlist' => 'Expandir a lista de artigos vigiados para mostrar todas as alterações aplicáveis',
'tog-usenewrc' => 'Mudanças recentes melhoradas (JavaScript)',
'tog-numberheadings' => 'Auto-numerar cabeçalhos',
'tog-showtoolbar'               => 'Mostrar barra de edição (JavaScript)',
'tog-editondblclick' => 'Editar páginas quando houver clique duplo (JavaScript)',
'tog-editsection'               => 'Habilitar edição de secção via links [editar]',
'tog-editsectiononrightclick'   => 'Habilitar edição de secção por clique <br /> com o botão direito no título da secção (JavaScript)',
'tog-showtoc'                   => 'Mostrar Tabela de Conteúdos (para artigos com mais de 3 cabeçalhos)',
'tog-rememberpassword' => 'Lembrar palavra-chave entre sessões',
'tog-editwidth' => 'Caixa de edição com largura completa',
'tog-watchcreations' => 'Adicionar páginas criadas por mim à minha lista de artigos vigiados',
'tog-watchdefault' => 'Adicionar páginas editadas por mim à minha lista de artigos vigiados',
'tog-minordefault' => 'Marcar todas as edições como secundárias, por padrão',
'tog-previewontop' => 'Mostrar previsão antes da caixa de edição ao invés de ser após',
'tog-previewonfirst' => 'Mostrar previsão na primeira edição',
'tog-nocache' => 'Desactivar caching de páginas',
'tog-enotifwatchlistpages'      => 'Enviar-me um email quando houver mudanças nas páginas',
'tog-enotifusertalkpages'       => 'Enviar-me um email quando a minha página de discussão for editada',
'tog-enotifminoredits'          => 'Enviar-me um email também quando forem edições menores',
'tog-enotifrevealaddr'          => 'Revelar o meu endereço de email nas notificações',
'tog-shownumberswatching'       => 'mostrar o número de utilizadores a vigiar',
'tog-fancysig' => 'Assinaturas sem atalhos automáticos.',
'tog-externaleditor' => 'Utilizar editor externo por padrão',
'tog-externaldiff' => 'Utilizar diferenças externas por padrão',
'tog-showjumplinks' => 'Activar hiperligações de acessibilidade "ir para"',
'tog-uselivepreview' => 'Utilizar pré-visualização em tempo real (JavaScript) (Experimental)',
'tog-autopatrol' => 'Marcar as minhas edições como verificadas',
'tog-forceeditsummary' => 'Avisar-me ao introduzir um sumário vazio',
'tog-watchlisthideown' => 'Esconder as minhas edições da lista de artigos vigiados',
'tog-watchlisthidebots' => 'Esconder edições efectuadas por robôs da lista de artigos vigiados',

'underline-always' => 'Sempre',
'underline-never' => 'Nunca',
'underline-default' => 'Padrão',

'skinpreview' => '(Pré-visualizar)',

# dates
'sunday' => 'Domingo',
'monday' => 'Segunda-feira',
'tuesday' => 'Terça-feira',
'wednesday' => 'Quarta-feira',
'thursday' => 'Quinta-feira',
'friday' => 'Sexta-feira',
'saturday' => 'Sábado',
'sun' => 'Dom',
'mon' => 'Seg',
'tue' => 'Ter',
'wed' => 'Qua',
'thu' => 'Qui',
'fri' => 'Sex',
'sat' => 'Sáb',
'january' => 'Janeiro',
'february' => 'Fevereiro',
'march' => 'Março',
'april' => 'Abril',
'may_long' => 'Maio',
'june' => 'Junho',
'july' => 'Julho',
'august' => 'Agosto',
'september' => 'Setembro',
'october' => 'Outubro',
'november' => 'Novembro',
'december' => 'Dezembro',
'january-gen' => 'Janeiro',
'february-gen' => 'Fevereiro',
'march-gen' => 'Março',
'april-gen' => 'Abril',
'may-gen' => 'Maio',
'june-gen' => 'Junho',
'july-gen' => 'Julho',
'august-gen' => 'Agosto',
'september-gen' => 'Setembro',
'october-gen' => 'Outubro',
'november-gen' => 'Novembro',
'december-gen' => 'Dezembro',
'jan' => 'Jan',
'feb' => 'Fev',
'mar' => 'Mar',
'apr' => 'Abr',
'may' => 'Mai',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Ago',
'sep' => 'Set',
'oct' => 'Out',
'nov' => 'Nov',
'dec' => 'Dez',

# Bits of text used by many pages:
#
'categories' => 'Categorias',
'pagecategories' => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header' => 'Artigos na categoria "$1"',
'subcategories' => 'Subcategorias',


#'linkprefix'            => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD', # ignore list
'mainpage'              => 'Página principal',
'mainpagetext'    => "<big>'''MediaWiki instalado com sucesso.'''</big>",
'mainpagedocfooter' => "Consultar o [http://meta.wikimedia.org/wiki/Help:Contents Guia de Utilizadores] para informação acerca de como utilizador o software wiki.

== Começando ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Lista de configuração de ajustes]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki Perguntas e respostas frequentes]
* [http://mail.wikipedia.org/mailman/listinfo/mediawiki-announce Lista de correio do anúncio de publicações do MediaWiki]",

'portal'                => 'Portal comunitário',
'portal-url'            => '{{ns:project}}:Portal comunitário',
'about'                 => 'Sobre',
'aboutsite'      => 'Sobre',
'aboutpage'             => '{{ns:project}}:Sobre',
'article' => 'Artigo',
'help'                  => 'Ajuda',
'helppage'              => 'Ajuda:Conteúdos',
'bugreports'    => 'Bug reports',
'bugreportspage' => '{{ns:project}}:Bug_reports',
'sitesupport'   => 'Doações',
'sitesupport-url' => '{{ns:project}}:Apoio',
'faq'                   => 'FAQ',
'faqpage'               => '{{ns:project}}:FAQ',
'edithelp'              => 'Ajuda de edição',
'newwindow'             => '(abre numa nova janela)',
'edithelppage'  => 'Ajuda:Editar',
'cancel'                => 'Cancelar',
'qbfind'                => 'Procurar',
'qbbrowse'              => 'Navegar',
'qbedit'                => 'Editar',
'qbpageoptions' => 'Esta página',
'qbpageinfo'    => 'Informação da página',
'qbmyoptions'   => 'Minhas opções',
'qbspecialpages'        => 'Páginas especiais',
'moredotdotdot' => 'Mais...',
'mypage'                => 'Minha página',
'mytalk'                => 'Minha discussão',
'anontalk'              => 'Discussão para este IP',
'navigation' => 'Navegação',

# Metadata in edit box
'metadata_help' => 'Metadata (para uma explicação ver [[{{ns:project}}:Metadata]]):',

'currentevents' => 'Eventos actuais',
'currentevents-url' => 'Eventos actuais',

'disclaimers' => 'Disclaimers',
'disclaimerpage' => "{{ns:project}}:General_disclaimer",
'privacy' => 'Política de privacidade',
'privacypage' => '{{ns:project}}:Política_de_privacidade',
'errorpagetitle' => "Erro",
'returnto'              => "Retornar para $1.",
'tagline'       => 'De {{SITENAME}}',
'help'                  => 'Ajuda',
'search'                => 'Pesquisa',
'searchbutton'          => 'Pesquisa',
'go'            => 'Ir',
'searcharticle'            => 'Ir',
"history"             => 'Histórico',
'history_short' => 'História',
'updatedmarker' => 'actualizado desde a minha última visita',
'info_short'    => 'Informação',
'printableversion' => 'Versão para impressão',
'permalink'     => 'Ligação permanente',
'print' => 'Imprimir',
'edit' => 'Editar',
'editthispage'  => 'Editar esta página',
'delete' => 'Eliminar',
'deletethispage' => 'Eliminar esta página',
'undelete_short' => 'Restaurar $1 edições',
'protect' => 'Proteger',
'protectthispage' => 'Proteger esta página',
'unprotect' => 'Desproteger',
'unprotectthispage' => 'Desproteger esta página',
'newpage' => 'Nova página',
'talkpage'              => 'Discutir esta página',
'specialpage' => 'Página Especial',
'personaltools' => 'Ferramentas pessoais',
'postcomment'   => 'Envie um comentário',
'articlepage'   => 'Ver artigo',
'talk' => 'Discussão',
'views' => 'Vistas',
'toolbox' => 'Ferramentas',
'userpage' => 'Ver página de utilizador',
'projectpage' => 'Ver página do projecto',
'imagepage' =>       'Ver página de imagens',
'mediawikipage' =>     'Ver página de mensagens',
'templatepage' =>     'Ver página de predefinições',
'viewhelppage' =>     'Ver página de ajuda',
'categorypage' =>     'Ver página de categorias',
'viewtalkpage' => 'Ver discussão',
'otherlanguages' => 'Outras línguas',
'redirectedfrom' => '(Redireccionado de <b>$1</b> para <b>{{PAGENAME}}</b>.)',
'autoredircomment' => 'Redireccionando para [[$1]]',
'redirectpagesub' => 'Página de redireccionamento',
'lastmodifiedat'  => 'Esta página foi modificada pela última vez a $2, $1.',
'viewcount'             => 'Esta página foi acedida {{plural:$1|uma vez|$1 vezes}}.',
'copyright'     => 'Conteúdo disponível sob $1.',
'protectedpage' => 'Página protegida',
'jumpto' => 'Ir para:',
'jumptonavigation' => 'navegação',
'jumptosearch' => 'pesquisa',

'badaccess'     => 'Erro de permissão',
'badaccess-group0' => 'Não está autorizado a executar a acção requisitada.',
'badaccess-group1' => 'A acção que requisitou está limitada a utilizadores do grupo $1.',
'badaccess-group2' => 'A acção que requisitou está limitada a utilizadores de um dos grupos $1.',
'badaccess-groups' => 'A acção que requisitou está limitada a utilizadores de um dos grupos $1.',

'versionrequired' => 'Necessária versão $1 do MediaWiki',
'versionrequiredtext' => 'Esta página requer a versão $1 do MediaWiki para ser utilizada. Consulte [[Special:Version]]',

'ok'                    => 'OK',
'pagetitle'             => "$1 - {{SITENAME}}",
'retrievedfrom' => 'Retirado de "$1"',
'youhavenewmessages' => "Você tem $1 ($2).",
'newmessageslink' => 'novas mensagens',
'newmessagesdifflink' => 'comparar com a penúltima revisão',
'editsection'=>'editar',
'editold'=>'editar',
'editsectionhint' => 'Editar secção: $1',
'toc' => 'Tabela de conteúdo',
'showtoc' => 'mostrar',
'hidetoc' => 'esconder',
'thisisdeleted' => "Ver ou restaurar $1?",
'viewdeleted' => 'Ver $1?',
'restorelink' => '{{PLURAL:$1|uma edição eliminada|$1 edições eliminadas}}',
'feedlinks' => 'Feed:',
'feed-invalid' => 'Tipo de subscrição feed inválido.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Artigo',
'nstab-user' => 'Página de utilizador',
'nstab-media' => 'Media',
'nstab-special' => 'Especial',
'nstab-project' => 'Página de projecto',
'nstab-image' => 'Ficheiro',
'nstab-mediawiki' => 'Mensagem',
'nstab-template' => 'Predefinição',
'nstab-help' => 'Ajuda',
'nstab-category' => 'Categoria',

# Main script and global functions
#
'nosuchaction'  => 'Acção não existente',
'nosuchactiontext' => 'A acção especificada pelo URL não é reconhecida pelo programa da Wikipédia',
'nosuchspecialpage' => 'Não existe a página especial requesitada',
'nospecialpagetext' => 'Requesitou uma página especial inválida; uma lista de páginas especiais válidas poderá ser encontrada em [[{{ns:special}}:Specialpages]].',

# General errors
#
'error'                 => 'Erro',
'databaseerror' => 'Erro na base de dados',
'dberrortext'   => "Ocorreu um erro de sintaxe na pesquisa à base de dados.
A última tentativa de busca na base de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função \"<tt>$2</tt>\".
MySQL retornou o erro \"<tt>$3: $4</tt>\".",
'dberrortextcl' => "Ocorre um erro de sintaxe na pesquisa à base de dados.
A última tentativa de busca na base de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função \"<tt>$2</tt>\".
MySQL retornou o erro \"<tt>$3: $4</tt>\".",
'noconnect'             => 'Pedimos desculpas, mas esta wiki está passando por algumas
dificuldades técnicas e não pode contactar o servidor da base de dados.',
'noconnect'		=> 'Desculpe! A wiki está a experienciar algumas dificuldades técnicas, e não pode contactar o servidor da base de dados. <br />
$1',
'nodb'                  => "Não foi possível seleccionar a base de dados $1",
'cachederror'           => 'A página apresentada é uma cópia em cache da página requisitada, e pode não estar actualizada.',
'laggedslavemode'   => 'Aviso: A página poderá não conter actualizações recentes.',
'readonly'              => 'Base de dados somente para leitura',
'enterlockreason' => 'Introduza com um motivo para trancar, incluindo uma estimativa de quando poderá novamente ser editada',
'readonlytext'  => "A base de dados da {{SITENAME}} está actualmente trancada para novos
artigos e outras modificações, provavelmente por uma manutenção de rotina à base de dados, mais tarde voltará ao normal.

O administrador que fez o bloqueio oferece a seguinte explicação: $1",
'missingarticle' => "A base de dados não encontrou o texto de uma página que deveria ter encontrado: \"$1\".

Isto é geralmente causado pela procura de uma diferença num antigo ou num histórico que leva a uma página que foi eliminada.

Se este não for o caso, você pode ter encontrado um ''bug'' no software.
Por favor, tome nota do URL e comunique o erro a um [[{{ns:project}}:Administradores|administrador]].",
'readonly_lag' => "A base de dados foi automaticamente bloqueada para sincronização",
'internalerror' => 'Erro interno',
'filecopyerror' => "Não foi possível copiar o ficheiro \"$1\" para \"$2\".",
'filerenameerror' => "Não foi possível renomear o ficheiro \"$1\" para \"$2\".",
'filedeleteerror' => "Não foi possível eliminar o ficheiro \"$1\".",
'filenotfound'  => "Não foi possível encontrar o ficheiro \"$1\".",
'unexpected'    => "Valor não esperado: \"$1\"=\"$2\".",
'formerror'             => 'Erro: Não foi possível enviar o formulário',
'badarticleerror' => 'Esta acção não pode ser realizada nesta página.',
'cannotdelete'  => 'Não foi possível eliminar a página ou imagem especificada (Pode ter sido já eliminada por outro administrador.)',
'badtitle'              => 'Título inválido',
'badtitletext' => "O título de página requisitada era inválido, vazio, ou uma ligação incorrecta de inter-linguagem ou título inter-wiki.
No qual pode conter um ou mais caracteres que não podem ser utilizados em títulos.",
'perfdisabled' => 'Esta opção foi temporariamente desabilitada,
devido a tornar a base de dados lenta demais a ponto de impossibilitar a wiki.',
'perfdisabledsub' => "Aqui pode ver uma cópia de $1 salvaguardada:", # obsolete?
'perfcached' => 'Os dados seguintes encontram-se na cache e podem não estar actualizados.',
'perfcachedts' => 'Os seguintes dados encontram-se armazenados na cache e foram actualizados pela última vez a $1.',

'wrong_wfQuery_params' => "Parâmetros incorrectos para wfQuery()<br />
Function: $1<br />
Query: $2",
'viewsource' => 'Ver fonte',
'viewsourcefor' => 'para $1',
'protectedtext' => "Esta página foi protegida para não permitir edições; existem inúmeros motivos para
ocorrer esta situação, por favor consulte [[{{ns:project}}:Página protegida]].

Pode ver e copiar o código fonte desta página:",
'protectedinterface' => 'Esta página fornece texto de interface ao software, e encontra-se trancada para prevenir abusos.',
'editinginterface' => "'''Aviso:''' Encontra-se a editar uma página que é utilizada para fornecer texto de interface ao software. Alterações nesta página irão afectar a aparência da interface de utilizador para outros utilizadores.",
'sqlhidden' => '(Consulta SQL escondida)',

# Login and logout pages
#
'logouttitle'   => 'Saída de utilizador',
'logouttext'	=> '<strong>Saiu agora da sua conta.</strong><br />
Pode continuar a utilizar a {{SITENAME}} anonimamente, ou pode autenticar-se
novamente como o mesmo nome de utilizador ou com um nome de utilizador diferente. Tenha em atenção que algumas páginas poderão
continuar a ser apresentadas como se tivesse ainda autenticado, até limpar
a cache do seu navegador.',

'welcomecreation' => "== Bem-vindo, $1! ==

A sua conta foi criada. Não se esqueça de personalizar as suas [[Special:Preferences|preferências]] na {{SITENAME}}.",

'loginpagetitle' => 'Autenticação de utilizador',
'yourname'              => 'Seu nome de utilizador',
'yourpassword'  => 'Palavra-chave',
'yourpasswordagain' => 'Reintroduza a sua palavra-chave',
'remembermypassword' => 'Lembrar a minha palavra-chave entre sessões.',
'yourdomainname'       => 'Seu domínio',
'externaldberror'      => 'Ocorreu um erro externo à base de dados durante a autenticação, ou não lhe é permitido actualizar a sua conta externa.',
'loginproblem'  => '<b>Houve um problema com a sua autenticação.</b><br />Tente novamente!',
'alreadyloggedin' => "<strong>Utilizador $1, você já está autenticado!</strong><br />",
'login'                 => 'Entrar',
'loginprompt'           => "Você necessita de ter os <i>cookies</i> ligados para poder autenticar-se na {{SITENAME}}.",
'userlogin'             => 'Criar uma conta ou entrar',
'logout'                => 'Sair',
'userlogout'    => 'Sair',
'notloggedin'   => 'Não autenticado',
'nologin'       => 'Não possui uma conta? $1.',
'nologinlink'   => 'Criar uma conta',
'createaccount' => 'Criar conta de utilizador',
'gotaccount'    => 'Já possui uma conta? $1.',
'gotaccountlink'        => 'Entrar',
'createaccount' => 'Criar nova conta',
'createaccountmail'     => 'por email',
'badretype'             => 'As palavras-chaves que introduziu não são iguais.',
'userexists'    => 'O nome de utilizador que introduziu já existe. Por favor, escolha um nome diferente.',
'youremail'             => 'Endereço de email *:',
'username'              => 'Nome de utilizador:',
'uid'                   => 'Número de identificação:',
'yourrealname'          => 'Nome verdadeiro *:',
'yourlanguage'  => 'Idioma:',
'yourvariant'  => 'Variante',
'yournick'              => 'Alcunha:',
'badsig'                => 'Assinatura inválida; verifique o código HTML utilizado.',
'email'                 => 'Correio electrónico',
'prefs-help-email-enotif' => 'Este endereço é também utilizado para enviar-lhe notificações caso as active nas preferências.',
'prefs-help-realname'   => '* Nome verdadeiro (opcional): caso decida indicar, este será utilizado para lhe dar crédito pelo seu trabalho.',
'loginerror'    => 'Erro de autenticação',
'prefs-help-email'      => '* Email (opcional): Permite os utilizadores entrem em contacto consigo sem que tenha de lhes revelar o seu endereço de e-mail.',
'nocookiesnew'  => "A conta de utilizador foi criada, mas você não foi ligado à conta. Tem os <i>cookies</i> desactivados no seu navegador, e a {{SITENAME}} utiliza <i>cookies</i> para ligar os utilizadores às suas contas. Por favor os active, depois autentique-se com o seu nome de utilizador e a sua palavra-chave.",
'nocookieslogin'        => "Você tem os <i>cookies</i> desactivados no seu navegador, e a {{SITENAME}} utiliza <i>cookies</i> para ligar os utilizadores às suas contas. Por favor os active e tente novamente.",
'noname'                => 'Não colocou um nome de utilizador válido.',
'loginsuccesstitle' => 'Login bem sucedido',
'loginsuccess'  => "'''Encontra-se agora ligado à {{SITENAME}} como \"$1\"'''.",
'nosuchuser'    => "Não existe nenhum utilizador com o nome \"$1\".
Verifique o nome que introduziu, ou crie uma nova conta de utilizador.",
'nosuchusershort'       => "Não existe um utilizador com o nome \"$1\". Verifique o nome que introduziu.",
'nouserspecified'       => 'Precisa de especificar um nome de utilizador.',
'wrongpassword'         => 'A palavra-chave que introduziu é inválida. Por favor tente novamente.',
'wrongpasswordempty'            => 'Palavra-chave introduzida está em branco. Por favor tente novamente.',
'mailmypassword'        => 'Enviar uma nova palavra-chave por correio electrónico',
'passwordremindertitle' => "Lembrador de palavras-chave da {{SITENAME}}",
'passwordremindertext' => "Alguém (provavelmente você, do endereço de IP $1) solicitou que fosse lhe envido uma nova palavra-chave para {{SITENAME}} ($4).
A palavra para o utilizador \"$2\" é a partir de agora \"$3\". Pode agora entrar na sua conta e alterar a palavra-chave.

Caso tenha sido outra pessoa a fazer este pedido ou caso você já se tenha lembrado da sua palavra-chave e se não a desejar alterar, pode ignorar esta mensagem e continuar a utilizar a palavra-chave antiga.",
'noemail'                           => "Não existe um endereço de correio electrónico associado ao utilizador \"$1\".",
'passwordsent'  => "Uma nova palavra-chave encontra-se a ser enviada para o endereço de correio electrónico associado ao utilizador \"$1\".
Por favor, volte a efectuar a autenticação ao recebê-la.",
'eauthentsent'             =>  "Um email de confirmação foi enviado para o endereço de correio electrónico nomeado.
Antes de qualquer outro email seja enviado para a conta, terá seguir as instruções no email,
de modo a confirmar que a conta é mesmo sua.",
#'signupend'                         => '{{int:loginend}}', # ignore list
'mailerror'                 => "Erro a enviar o mail: $1",
'acct_creation_throttle_hit' => 'Pedimos desculpa, mas já foram criadas $1 contas por si. Não lhe é possível criar mais nenhuma.',
'emailauthenticated'        => 'O seu endereço de correio electrónico foi autenticado em $1.',
'emailnotauthenticated'     => 'O seu endereço de correio electrónico ainda não foi autenticado. Não lhe será enviado nenhum correio sobre nenhuma das seguintes funcionalidades.',
'noemailprefs'              => '<strong>Nenhum endereço de correio electrónico foi especificado</strong>, as seguintes funcionalidades não irão funcionar.',
'emailconfirmlink' => 'Confirme o seu endereço de correio electrónico',
'invalidemailaddress'   => 'O endereço de correio electrónico não pode ser aceite devido a possuír um formato inválido. Por favor introduza um endereço bem formatado ou esvazie o campo.',
'accountcreated' => 'Conta criada',
'accountcreatedtext' => 'A conta de utilizador para $1 foi criada.',

# Edit page toolbar
#
'bold_sample'      => 'Texto a negrito',
'bold_tip'         => 'Texto a negrito',
'italic_sample'    => 'Texto em itálico',
'italic_tip'       => 'Texto em itálico',
'link_sample'      => 'Título da ligação',
'link_tip'         => 'Ligação interna',
'extlink_sample'   => 'http://www.wikimedia.org ligação externa',
'extlink_tip'      => 'Ligação externa (lembre-se dos prefixos http://, ftp://, ...)',
'headline_sample'  => 'Texto de cabeçalho',
'headline_tip'     => 'Secção de nível 2',
'math_sample'      => 'Inserir fórmula aqui',
'math_tip'         => 'Fórmula matemática (LaTeX)',
'nowiki_sample'    => 'Inserir texto não-formatado aqui',
'nowiki_tip'       => 'Ignorar formato wiki',
'image_sample'     => 'Exemplo.jpg',
'image_tip'        => 'Imagem anexa',
'media_sample'     => 'Exemplo.ogg',
'media_tip'        => 'Ligação a ficheiro interno de multimédia',
'sig_tip'          => 'Sua assinatura com hora e data',
'hr_tip'           => 'Linha horizontal (utilize moderadamente)',

# Edit pages
#
'summary'               => 'Sumário',
'subject'               => 'Assunto/cabeçalho',
'minoredit'             => 'Marcar como edição menor',
'watchthis'             => 'Observar este artigo',
'savearticle'   => 'Salvar página',
'preview'               => 'Prever',
'showpreview'   => 'Mostrar previsão',
'showlivepreview'       => 'Pré-visualização em tempo real',
'showdiff'      => 'Mostrar alterações',
'anoneditwarning' => 'Não encontra-se autenticado. O seu endereço de IP será registado no histórico de edições desta página.',
'missingsummary' => "'''Atenção:''' Não introduziu um sumário de edição. Se carregar em Salvar novamente, a sua edição será salva sem um sumário.",
'missingcommenttext' => 'Por favor introduzida um comentário abaixo.',
'blockedtitle'  => 'Utilizador está bloqueado',
'blockedtext'   => "O seu nome de utilizador ou endereço de IP foi bloqueado por $1.<br />
O motivo é: ''$2''

Pode contactar [[{{ns:special}}:emailuser/$4|$4]] ou outro
[[{{ns:project}}:Administradores|administrador]] para discutir sobre o bloqueio.

Note que não poderá utilizar a funcionalidade \"Contactar utilizador\" se não possuir uma conta na Wikipédia e um endereço de email válido indicado nas suas preferências de utilizador. E lembre-se que só se encontra impossibilitado de editar páginas.<br /><br />

'''O seu endereço de IP é $3.''' Por favor inclua o seu endereço de IP ao contactar um administrador sobre o bloqueio.",
'blockedoriginalsource' => "A fonte de '''$1''' é mostrada abaixo:",
'blockededitsource' => "O texto das '''suas edições''' para '''$1''' é mostrado abaixo:",
'whitelistedittitle' => 'Autentificação necessária para visualizar',
'whitelistedittext' => 'Precisa de se $1 para poder visualizar páginas.',
'whitelistreadtitle' => 'Autentificação necessária para visualizar',
'whitelistreadtext' => 'Precisa de se [[Special:Userlogin|autenticar]] para puder visualizar páginas.',
'whitelistacctitle' => 'Não lhe é permitido criar uma conta',
'whitelistacctext' => 'De modo a poder criar contas de utilizador nesta Wiki terá que se [[Special:Userlogin|autenticar]] e possuir as devidas permissões.',
'confirmedittitle' => 'Confirmação por correio electrónico necessária para editar',
'confirmedittext' => 'Precisa de confirmar o seu endereço de correio electrónico antes de começar a editar páginas. Por favor introduza e valide o seu endereço de correio electrónico através das suas [[Especial:Preferences|preferências de utilizador]].',
'loginreqtitle' => 'Autenticação Requesitada',
'loginreqlink' => 'autenticar-se',
'loginreqpagetext'  => 'Precisa de $1 para visualizar outras páginas.',
'accmailtitle' => 'Palavra-chave enviada.',
'accmailtext' => "A palavra-chave para '$1' foi enviada para $2.",
'newarticle'    => '(Novo)',
'newarticletext' =>
"Seguiu um link para um artigo que ainda não existe. Para criá-lo, escreva o seu conteúdo na caixa abaixo, mas se chegou aqui por engano clique no botão '''volta''' (ou ''back'') do seu navegador. Por favor, '''NÃO''' crie páginas apenas para fazer [[Project:Artigos pedidos|pedidos]] ou [[Project:Página de testes|testes]].

(Consulte [[{{ns:project}}:Ajuda|a página de ajuda]] para mais informações)",
'anontalkpagetext' => "----
''Esta é a página de discussão para um utilizador anónimo que ainda não criou uma conta ou que não a utiliza. De modo a que temos que utilizar o endereço de IP para identificá-lo(a). Um endereço de IP pode ser partilhado por vários utilizadores. Se é um utilizador anónimo e acha relevante que os comentários sejam direccionados a si, por favor [[{{ns:special}}:Userlogin|crie uma conta ou autentique-se]] para evitar futuras confusões com outros utilizadores anónimos.''",
'noarticletext' => 'Não existe actualmente texto nesta página, pode [[{{ns:special}}:Search/{{PAGENAME}}|pesquisar pelo título desta página noutras páginas]] ou [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} editar esta página].',
'clearyourcache' => "'''Nota:''' Após salvar, terá de limpar a cache do seu navegador para ver as alterações.
'''Mozilla / Firefox / Safari:''' pressione ''Shift'' enquanto clica em ''Recarregar'', ou pressione ''Ctrl-Shift-R'' (''Cmd-Shift-R'' no Apple Mac); '''IE:''' pressione ''Ctrl'' enquanto clica em ''Recarregar'', ou pressione ''Ctrl-F5''; '''Konqueror:''': simplesmente clique no botão ''Recarregar'', ou pressione ''F5''; utilizadores do navegador '''Opera''' podem limpar completamente a sua cache em ''Ferramentas→Preferências''.",
'usercssjsyoucanpreview' => "<strong>Dica:</strong> Utilize o botão \"Mostrar previsão\" para testar seu novo CSS/JS antes de salvar.",
'usercsspreview' => "'''Lembre-se que está apenas a prever o seu CSS particular, e que ainda não foi salvo!'''",
'userjspreview' => "'''Lembre-se que está apenas a testar/prever o seu JavaScript particular, e que ainda não foi salvo!'''",
'userinvalidcssjstitle' => "'''Aviso:''' Não existe um tema \"$1\". Lembre-se que as páginas .css e  .js utilizam um título em minúsculas, exemplo: Utilizador:Silva/monobook.css aposto a Utilizador:Silva/Monobook.css.",
'updated'               => '(Actualizado)',
'note'                  => '<strong>Nota:</strong>',
'previewnote'   => '<strong>Isto é apenas uma previsão, as modificações ainda não foram salvas!</strong>',
'session_fail_preview' => '<strong>Pedimos desculpas, mas não foi possível processar a sua edição devido à perda de dados da sua sessão.
Por favor tente novamente. Caso continue a não funcionar, tente sair e voltar a entrar na sua conta.</strong>',
'previewconflict' => 'Esta previsão reflete o texto que está na área de edição acima e como ele aparecerá se você escolher salvar.',
'session_fail_preview_html' => '<strong>Pedimos desculpas! Não foi possível processar a sua edição devido a uma perda de dados de sessão.</strong>

\'\'Devido a esta wiki possuir HTML raw activo, a previsão está escondida como forma de precaução contra ataques JavaScript.\'\'

<strong>Por favor tente novamente caso esta seja uma tentativa de edição legítima. Caso continue a não funcionar, por favor tente sair e voltar a entrar na sua conta.</strong>',
'importing'		=> "Importando $1",
'editing'               => "Editando $1",
'editinguser'               => "Editando $1",
'editingsection'                => "Editando $1 (secção)",
'editingcomment'                => "Editando $1 (comentário)",
'editconflict'  => 'Conflito de edição: $1',
'explainconflict' => "Alguém mudou a página enquanto você a estava editando.<br />
A área de texto acima mostra o texto original.
Suas mudanças são mostradas na área abaixo
Você terá que mesclar suas modificações no texto existente.
<b>SOMENTE</b> o texto na área acima será salvo quando você pressionar \"Salvar página\".\n<br />",
'yourtext'              => 'Seu texto',
'storedversion' => 'Versão guardada',
'nonunicodebrowser' => "<strong>AVISO: O seu navegador não segue as especificações Unicode. Existe uma maneira para que possa editar com segurança os artigos: os caracteres não-ASCII aparecerão na caixa de edição no formato de códigos hexadecimais.</strong>",
'editingold'    => "<strong>CUIDADO: Encontra-se a editar uma revisão desactualizada deste artigo.
Se salvá-lo, todas as mudanças feitas a partir desta revisão serão perdidas.</strong>",
'yourdiff'              => 'Diferenças',
/*'copyrightwarning' => "Por favor note que todas as contribuições para a {{SITENAME}} são imediatamente colocadas sob a <b>GNU Free Documentation License</b> (consulte $1 para detalhes). Se você não quer que seu texto esteja sujeito a estes termos, então não o envie.<br />
Você também garante que está nos enviando um artigo escrito por você mesmo, ou extraído de uma fonte em domínio público.
<strong>Não ENVIE </strong>",*/
'copyrightwarning2' => "Tenha em consideração que todas as contribuições para o projecto {{SITENAME}}
podem ser editadas, alteradas, ou removidas por outros contribuidores.
Se não deseja ver as suas contribuições alteradas sem consentimento, não as envie para esta Wiki.<br />
Adicionalmente, estar-nos-á a dar a sua palavra em como os teus são da sua autoria, ou copiados por fontes de domínio público ou similares (veja mais detalhes em $1).
<strong>NÃO ENVIE MATERIAL COM DIREITOS DE AUTOR SEM PERMISSÃO!</strong>",
'longpagewarning' => "<strong>AVISO: Esta página ocupa $1; alguns browsers verificam
problemas em editar páginas maiores que 32kb.
Por favor, considere seccionar a página em secções de menor dimensão.</strong>",
'longpageerror' => "<strong>ERRO: A página que submeteu tem mais de $1 kilobytes
em tamanho, que é maior que o máximo de $2 kilobytes. A página não pode salva.</strong>",
'readonlywarning' => '<strong>AVISO: A base de dados foi bloqueada para manutenção, pelo que não poderá salvar a sua edição neste momento. Pode, no entanto, copiar o seu texto num editor externo e guardá-lo para posterior submissão.</strong>',
'protectedpagewarning' => "<strong>AVISO: Esta página foi protegida e apenas poderá ser editada por utilizadores com privilégios sysop (administradores). Certifique-se que está a respeitar as [[{{ns:project}}:Protected_page_guidelines|linhas de orientação para páginas protegidas]].</strong>",
'semiprotectedpagewarning' => "'''Nota:''' Esta página foi protegida de modo a que apenas utilizadores registados a possam editar.",
'templatesused' => 'Predefinições utilizadas nesta página:',
'edittools' => '<!-- Text here will be shown below edit and upload forms. -->',
'nocreatetitle' => 'Limitada a criação de páginas',
'nocreatetext' => 'Este website tem restringida a habilidade de criar novas páginas.
Pode voltar atrás e editar uma página já existente, ou [[Special:Userlogin|autenticar-se ou criar uma conta]].',
'cantcreateaccounttitle' => 'Não é possível criar uma conta',
'cantcreateaccounttext' => 'A criação de contas a partir deste endereço IP (<b>$1</b>) foi bloqueada. 
Isto é provavelmente devido a vandalismo persistente efectuada a partir da sua escola ou ISP.',

# History pages
#
'revhistory'    => 'Histórico de revisões',
'viewpagelogs' => 'Ver registos para esta página',
'nohistory'             => 'Não há histórico de edições para esta página.',
'revnotfound'   => 'Revisão não encontrada',
'revnotfoundtext' => "A antiga revisão desta página que requesitou não pode ser encontrada. Por favor verifique o URL que utilizou para aceder esta página.",
'loadhist'              => 'Carregando histórico',
'currentrev'    => 'Revisão actual',
'revisionasof'          => 'Revisão de $1',
'revision-info' => 'Revisão de $1; $2',
'previousrevision'      => '← Versão anterior',
'nextrevision'          => 'Versão posterior →',
'currentrevisionlink'   => 'ver versão actual',
'cur'                   => 'act',
'next'                  => 'prox',
'last'                  => 'ult',
'orig'                  => 'orig',
'histlegend'    => 'Selecção de diferença: marque as caixas para das versões que deseja comparar e carregue no botão.<br />
Legenda: (actu) = diferenças da versão actual,
(ult) = diferença da versão precedente, m = edição menor',
'deletedrev' => '[eliminada]',
'histfirst' => 'Mais antigas',
'histlast' => 'Mais recentes',
'rev-deleted-comment' => '(comentário removido)',
'rev-deleted-user' => '(nome de utilizador removido)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Esta revisão desta página foi removida dos arquivos públicos.
Poderão existir detalhes no [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registo de eliminação].
</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">
A revisão desta página foi removida dos arquivos públicos.
Como um administrador desta wiki pode a ver;
mais detalhes no [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registo de eliminação].
</div>',
'rev-delundel' => 'mostrar/esconder',

'history-feed-title' => 'História de revisão',
'history-feed-description'    => 'História de revisão para esta página nesta wiki',
'history-feed-item-nocomment' => '$1 a $2', # user at time
'history-feed-empty' => 'A página requisitada não existe.
Poderá ter sido eliminada da wiki, ou renomeada.
Tente [[Especial:Search|pesquisar na wiki]] por novas páginas relevantes.',

# Revision deletion
#
'revisiondelete' => 'Eliminar/restaurar revisões',
'revdelete-nooldid-title' => 'Nenhuma revisão seleccionada',
'revdelete-nooldid-text' => 'Não especificou nenhuma revisão, ou revisões,
no qual aplicar esta função.',
'revdelete-selected' => 'Revisão seleccionada para [[:$1]]:',
'revdelete-text' => "Revisões eliminadas continuarão a aparecer na história da página,
mas o seu conteúdo textual estará inacessível ao público.

Outros administradores nesta wiki continuarão a poder aceder ao conteúdo escondido e restaurá-lo novamente através deste mesmo ''interface'', a menos que uma restrição adicional seja colocada pelos operadores do ''site''.",
'revdelete-legend' => 'Atribuir restrições de revisões:',
'revdelete-hide-text' => 'Esconder texto de revisão',
'revdelete-hide-comment' => 'Esconder comentário de edição',
'revdelete-hide-user' => 'Esconder nome de utilizador/IP do editor',
'revdelete-hide-restricted' => 'Aplicar estas restrições a administrador tal como a outros',
'revdelete-log' => 'Comentário de registo:',
'revdelete-submit' => 'Aplicar a revisões seleccionadas',
'revdelete-logentry' => 'modificada visibilidade de revisão para [[$1]]',

# Diffs
#
'difference'    => '(Diferença entre revisões)',
'loadingrev'    => 'carregando a pesquisa por diferenças',
'lineno'                => "Linha $1:",
'editcurrent'   => 'Editar a versão actual desta página',
'selectnewerversionfordiff' => 'Seleccione uma versão mais recente para comparação',
'selectolderversionfordiff' => 'Seleccione uma versão mais antiga para comparação',
'compareselectedversions' => 'Compare as versões seleccionadas',

# Search results
#
'searchresults' => 'Resultados de pesquisa',
'searchresulttext' => "Para mais informações de como pesquisar na {{SITENAME}}, consulte [[{{ns:project}}:Pesquisa|Pesquisando {{SITENAME}}]].",
'searchsubtitle'   => "Para consulta \"[[:$1]]\"",
'searchsubtitleinvalid'   => "Para consulta \"$1\"",
'badquery'              => 'Linha de pesquisa inválida',
'badquerytext'  => 'Não foi possível processar seu pedido de pesquisa.
Aconteceu provavelmente porque tentou procurar uma palavra com menos de três letras. Isto também pode ter ocorrido porque digitou incorrectamente a expressão, por
exemplo "peixes <strong>e e</strong> escalas".
Por favor realize outro pedido de pesquisa.',
'matchtotals'   => "A pesquisa \"$1\" resultou $2 títulos de artigos
e $3 artigos com o texto procurado.",
'noexactmatch' => 'Não existe uma página com o título \"$1\". Pode criar [[:$1|esta página]].',
'titlematches'  => 'Resultados nos títulos dos artigos',
'notitlematches' => 'Nenhum título de página coincide',
'textmatches'   => 'Resultados dos textos dos artigos',
'notextmatches' => 'Nenhum texto nas páginas coincide',
'prevn'                 => "anteriores $1",
'nextn'                 => "próximos $1",
'viewprevnext'  => "Ver ($1) ($2) ($3).",
'showingresults' => "Mostrando <b>$1</b> resultados, começando no <b>$2</b>º.",
'showingresultsnum' => "Mostrando <b>$3</b> resultados começando com #<b>$2</b>.",
'nonefound'             => "<strong>Nota</strong>: pesquisas mal sucedidas são geralmente causadas devido ao uso de palavras muito comuns como \"tem\" e \"de\",
que não são indexadas, ou pela especificação de mais de um termo (somente as páginas contendo todos os termos aparecerão nos resultados).",
'powersearch' => 'Pesquisa',
'powersearchtext' => "
Pesquisar nos domínios:<br />
$1<br />
$2 Lista redirecciona &nbsp; Pesquisar por $3 $9",
"searchdisabled" => 'O motor de pesquisa na {{SITENAME}} foi desactivado por motivos de desempenho. Enquanto isso pode fazer a sua pesquisa através do Google ou do Yahoo!.<br />
Note que os índices do conteúdo da {{SITENAME}} destes sites podem estar desactualizados.',

'blanknamespace' => '(Principal)',

# Preferences page
#
'preferences'   => 'Preferências',
'mypreferences' => 'Minhas preferências',
'prefsnologin' => 'Não autenticado',
'prefsnologintext'      => "Precisa estar [[Special:Userlogin|autenticado]] para definir suas preferências.",
'prefsreset'    => 'Preferências restauradas da base de dados.',
'qbsettings'    => 'Barra Rápida',
'changepassword' => 'Alterar palavra-chave',
'skin'                  => 'Tema',
'math'                  => 'Matemática',
'dateformat'            => 'Formato da data',
'datedefault' => 'Sem preferência',
'datetime'              => 'Data e hora',
'math_failure'          => 'Falhou ao verificar gramática',
'math_unknown_error'    => 'Erro desconhecido',
'math_unknown_function' => 'Função desconhecida',
'math_lexing_error'     => 'Erro léxico',
'math_syntax_error'     => 'Erro de sintaxe',
'math_image_error'      => 'Erro na conversão para PNG; Verifique a instalação do latex, dvips, gs e convert',
'math_bad_tmpdir'       => 'Ocorreram problemas na criação ou escrita na directoria temporária math',
'math_bad_output'       => 'Ocorreram problemas na criação ou escrita na directoria de resultados math',
'math_notexvc'  => 'Executável texvc não encontrado; Consulte math/README para instruções da configuração.',
'prefs-personal' => 'Perfil de utilizador',
'prefs-rc' => 'Mudanças recentes',
'prefs-watchlist' => 'Lista de artigos vigiados',
'prefs-watchlist-days' => 'Número de dias a mostrar na lista de artigos vigiados:',
'prefs-watchlist-edits' => 'Numéro de edições a mostrar na lista de artigos vigados expandida:',
'prefs-misc' => 'Diversos',
'saveprefs'             => 'Salvar',
'resetprefs'    => 'Restaurar',
'oldpassword'   => 'Palavra-chave antiga',
'newpassword'   => 'Nova palavra-chave',
'retypenew'             => 'Reintroduza a nova palavra-chave',
'textboxsize'   => 'Opções de edição',
'rows'                  => 'Linhas:',
'columns'               => 'Colunas:',
'searchresultshead' => 'Pesquisa',
'resultsperpage' => 'Resultados por página:',
'contextlines'  => 'Linhas por resultado:',
'contextchars'  => 'Contexto por linha:',
'stubthreshold' => 'Variação para a visualização de esboços:',
'recentchangescount' => 'Número de artigos nas mudanças recentes:',
'savedprefs'    => 'As suas preferências foram salvas.',
'timezonelegend' => 'Fuso horário',
'timezonetext'  => 'Número de horas que o seu horário local difere do horário do servidor (UTC).',
'localtime'     => 'Hora local',
'timezoneoffset' => 'Diferença horária¹',
'servertime'    => 'Horário do servidor',
'guesstimezone' => 'Preencher a partir do navegador (browser)',
'allowemail'             => 'Permitir email de outros utilizadores',
'defaultns'             => 'Pesquisar nestes domínios por padrão:',
'default'               => 'padrão',
'files'                 => 'Ficheiros',

# User rights

'userrights-lookup-user' => 'Gerir grupos de utilizadores',
'userrights-user-editname' => 'Intruduza um nome de utilizador:',
'editusergroup' => 'Editar Grupos de Utilizadores',

'userrights-editusergroup' => 'Editar grupos do utilizador',
'saveusergroups' => 'Salvar Grupos do Utilizador',
'userrights-groupsmember' => 'Membro de:',
'userrights-groupsavailable' => 'Grupos disponíveis:',
'userrights-groupshelp' => 'Seleccione os grupos no qual deseja que o utilizador seja removido ou adicionado.
Grupos não seleccionados, não serão alterados. Pode seleccionar ou remover a selecção a um grupo com CTRL + Click esquerdo',

# Groups
'group'                   => 'Grupo:',
'group-bot'               => 'Robôs',
'group-sysop'             => 'Administradores',
'group-bureaucrat'        => 'Burocratas',
'group-all'               => '(todos)',

'group-bot-member'        => 'Robô',
'group-sysop-member'      => 'Administrador',
'group-bureaucrat-member' => 'Burocrata',

'grouppage-bot' => '{{ns:project}}:Robôs',
'grouppage-sysop' => '{{ns:project}}:Administradores',
'grouppage-bureaucrat' => '{{ns:project}}:Burocratas',

# Recent changes
#
'changes' => 'mudanças',
'recentchanges' => 'Mudanças recentes',
'recentchangestext' => 'Veja as mais novas mudanças na {{SITENAME}} nesta página.',
'rcnote'                => "Abaixo estão as últimas <strong>$1</strong> alterações nos últimos <strong>$2</strong> dias, desde $3.",
'rcnotefrom'    => "Abaixo estão as mudanças desde <b>$2</b> (mostradas até <b>$1</b>).",
'rclistfrom'    => "Mostrar as novas alterações a partir de $1",
'rcshowhideminor' => '$1 edições menores',
'rcshowhidebots' => '$1 robôs',
'rcshowhideliu' => '$1 utilizadores registados',
'rcshowhideanons' => '$1 utilizadores anónimos',
'rcshowhidepatr' => '$1 edições verificadas',
'rcshowhidemine' => '$1 as minhas edições',
'rclinks'               => "Mostrar as últimas $1 mudanças nos últimos $2 dias<br />$3",
'diff'                  => 'dif',
'hist'                  => 'hist',
'hide'                  => 'Esconder',
'show'                  => 'Mostrar',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'r',
'sectionlink' => '→',
'number_of_watching_users_pageview'     => '[$1 utilizador/es a vigiar]',
'rc_categories'	=> 'Limite para categorias (separar com "|")',
'rc_categories_any'	=> 'Qualquer',

# Upload
#
'upload'                => 'Carregar ficheiro',
'uploadbtn'             => 'Carregar ficheiro',
'reupload'              => 'Recarregar',
'reuploaddesc'  => 'Voltar ao formulário de carregamento.',
'uploadnologin' => 'Não autenticado',
'uploadnologintext'     => "Deve estar <a href=\"{{localurle:Special:Userlogin}}\">autenticado</a>
para carregar ficheiros.",
'upload_directory_read_only' => 'A directoria de envio ($1) não tem permissões de escrita pelo servidor Web.',
'uploaderror'   => 'Erro ao carregar',
'uploadtext'    =>
"
Utilize o formulário abaixo para carregar novos ficheiros,
para ver ou pesquisar imagens anteriormente carregadas
consulte a [[Special:Imagelist|lista de ficheiros carregados]],
carregamentos e eliminações são também registados no [[Special:Log|registo do projecto]].

Para incluír a imagem numa página, utilize o link na forma de
'''[[{{ns:6}}:ficheiro.jpg]]''',
'''[[{{ns:6}}:ficheiro.png|texto]]''' ou
'''[[{{ns:-2}}:ficheiro.ogg]]''' para uma ligação directa ao ficheiro.",
'uploadlog'             => 'registo de carregamento',
'uploadlogpage' => 'Registo de carregamento',
'uploadlogpagetext' => 'Segue-se uma lista dos carregamentos mais recentes.',
'filename'              => 'Nome do ficheiro',
'filedesc'              => 'Descrição do ficheiro',
'fileuploadsummary' => 'Sumário:',
'filestatus' => 'Estatuto de copyright',
'filesource' => 'Fonte',
'copyrightpage' => "{{ns:project}}:Direitos_de_autor",
'copyrightpagename' => "Direitos autorais da {{SITENAME}}",
'uploadedfiles' => 'Ficheiros carregados',
'ignorewarning'        => 'Ignorar aviso e salvar de qualquer forma.',
'ignorewarnings'        => 'Ignorar todos os avisos',
'minlength'             => 'O nome de um ficheiro tem de ter no mínimo três letras.',
'illegalfilename'       => 'O ficheiro "$1" possui caracteres que não são permitidos no título de uma página. Por favor altere o nome do ficheiro e tente carregar novamente.',
'badfilename'   => 'Nome do ficheiro foi alterado para "$1".',
'badfiletype'   => "\".$1\" é um formato de ficheiro não recomendado.",
'largefile'             => 'É recomendado que imagens não excedam $1 bytes em tamanho, o tamanho deste ficheiro é $2 bytes',
'largefileserver' => 'O tamanho deste ficheiro é superior ao qual o servidor encontra-se configurado para permitir.',
'emptyfile'             => 'O ficheiro que está a tentar carregar parece encontrar-se vazio. Isto poderá ser devido a um erro na escrita do nome do ficheiro. Por favor verifique se realmente deseja carregar este ficheiro.',
'fileexists'            => 'Já existe um ficheiro com este nome, por favor verifique $1 caso não tenha a certeza se deseja alterar o ficheiro actual.',
'fileexists-forbidden' => 'Já existe um ficheiro com este nome; por favor volte atrás e carregue este ficheiro sob um novo nome. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Já existe um ficheiro com este nome no repositório de ficheiros partilhados; por favor volte atrás e carregue este ficheiro sob um novo nome. [[Image:$1|thumb|center|$1]]',
'successfulupload' => 'Envio efectuado com sucesso',
'fileuploaded'  => "Ficheiro $1 enviado com sucesso.
Por favor siga este endereço: $2 para a página de descrição e preencha a informação acerca deste ficheiro, tais como a sua origem, quando foi criado e por quem, e quaisquer outros dados que tenha conhecimento sobre o mesmo. Caso este ficheiro seja uma imagem, pode inseri-lo desta forma: <tt>[[Imagem:$1|thumb|Descrição]]</tt>",
'uploadwarning' => 'Aviso de envio',
'savefile'              => 'Salvar ficheiro',
'uploadedimage' => "carregado \"[[$1]]\"",
'uploaddisabled' => 'Carregamentos desactivados',
'uploaddisabledtext' => 'O carregamento de ficheiros encontra-se desactivado nesta wiki.',
'uploadscripted' => 'Este ficheiro contém HTML ou código que pode ser erradamente interpretado por um navegador web.',
'uploadcorrupt' => 'O ficheiro encontra-se corrompido ou tem uma extensão não permitida. Corrija o ficheiro e tente novamento.',
'uploadvirus' => 'O ficheiro contém vírus! Detalhes: $1',
'sourcefilename' => 'Nome do ficheiro de origem',
'destfilename' => 'Nome do ficheiro de destino',
'watchthisupload'       => 'Vigiar esta página',
'filewasdeleted' => 'Um ficheiro com este nome foi anteriormente carregado e subsequentemente eliminado. Deveria verificar o $1 antes de proceder ao carregamento novamente.',

'license' => 'Licença',
'nolicense' => 'Nenhuma seleccionada',
'upload_source_url' => ' (válido, URL publicamente acessível)',
'upload_source_file' => ' (um ficheiro no seu computador)',

# Image list
#
'imagelist'             => 'Lista de ficheiros',
'imagelisttext' => 'Segue-se uma lista de <strong>$1</strong> ficheiros organizados <strong>$2</strong>.',
'imagelistforuser' => "Esta lista apenas mostra imagens carregadas por $1.",
'getimagelist'  => 'carregando lista de ficheiros',
'ilsubmit'              => 'Procurar',
'showlast'              => 'Mostrar últimos $1 ficheiros organizados $2.',
'byname'                => 'por nome',
'bydate'                => 'por data',
'bysize'                => 'por tamanho',
'imgdelete'             => 'eli',
'imgdesc'               => 'desc',
'imgfile'       => 'ficheiro',
'imglegend'             => 'Legenda: (desc) = mostrar/editar descrição de imagem.',
'imghistory'    => 'História',
'revertimg'             => 'rev',
'deleteimg'             => 'eli',
'deleteimgcompletely'           => 'Eliminar todas revisões deste ficheiro',
'imghistlegend' => 'Legenda: (actu) = imagem actual, (eli) = eliminar versão antiga, (rev) = reverter para versão antiga.
<br /><i>Clique na data para ver as imagens carregadas nessa data</i>.',
'imagelinks'    => 'Ligações',
'linkstoimage'  => 'As seguintes páginas apontam para este ficheiro:',
'nolinkstoimage' => 'Nenhuma página aponta para este ficheiro.',
'sharedupload' => 'Este ficheiro encontra-se partilhado e pode ser utilizado por outros projectos.',
'shareduploadwiki' => 'Por favor consulte a $1 para mais informação.',
'shareduploadwiki-linktext' => 'página de descrição',
'noimage'       => 'Nenhum ficheiro com este nome existe, se desejar pode $1',
'noimage-linktext'       => 'carrega-lo',
'uploadnewversion-linktext' => 'Carregar uma nova versão deste ficheiro',
'imagelist_date' => 'Data',
'imagelist_name' => 'Nome',
'imagelist_user' => 'Utilizador',
'imagelist_size' => 'Tamanho (bytes)',
'imagelist_description' => 'Descrição',
'imagelist_search_for' => 'Pesquisar por nome de imagem:',

# Unwatchedpages
#
'unwatchedpages' => 'Páginas não vigiadas',

# List redirects
'listredirects' => 'Listar redireccionamentos',

# Unused templates
'unusedtemplates' => 'Predefinições não utilizadas',
'unusedtemplatestext' => 'Esta página lista todas as páginas no domínio predefinição que não estão incluídas numa outra página. Lembre-se de verificar por outras ligações nas predefinições antes de as apagar.',
'unusedtemplateswlh' => 'outras ligações',

# Random redirect
'randomredirect' => 'Redireccionamento aleatório',

# Statistics
#
'statistics'    => 'Estatísticas',
'sitestats'             => 'Estatísticas do site',
'userstats'             => 'Estatística dos utilizadores',
'sitestatstext' => "Há actualmente um total de '''$1''' páginas na base de dados.
Isto inclui páginas de \"discussão\", páginas sobre o projecto, páginas de rascunho, redireccionamentos, e outras que provavelmente não são qualificadas como artigos.
Excluindo estas, há '''$2''' páginas que provavelmente são artigos legítimos.

'''$8''' ficheiros foram carregados.

Há um total de '''$3''' páginas vistas, e '''$4''' edições em páginas
desde a instalação do software.
O que nos leva a aproximadamente '''$5''' edições por página, e '''$6''' vistas por edição.

O tamanho da [http://meta.wikimedia.org/wiki/Help:Job_queue fila de tarefas] é de actualmente '''$7'''.",
'userstatstext' => "Há actualmente '''$1''' utilizadores registados.
Destes, '''$2''' (ou '''$4%''') são $5.",
'statistics-mostpopular' => 'Páginas mais vistas',

# Maintenance Page
#
'disambiguations'       => 'Página de desambiguações',
'disambiguationspage'   => 'Template:disambig',
'disambiguationstext'   => "As seguintes páginas ligam com uma <i>página de desambiguação</i>. Estas páginas deviam ligar com o tópico apropriado.<br />Qualquer página ligada com $1 é considerada página de desambiguação.<br />As ligações de outros domínios não são listadas aqui.",
'doubleredirects'       => 'Redireccionamentos duplos',
'doubleredirectstext'   => "Cada linha contém ligações para o primeiro e segundo redireccionamento, bem como a primeira linha de conteúdo do segundo redireccionamento, geralmente contendo a página destino \"real\", que devia ser o destino do primeiro redireccionamento.",
'brokenredirects'       => 'Redireccionamentos quebrados',
'brokenredirectstext'   => 'Os seguintes redireccionamentos ligam para páginas inexistentes:',

# Miscellaneous special pages
#
'nbytes'		=> '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'		=> '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'		=> '$1 {{PLURAL:$1|link|links}}',
'nmembers'		=> '$1 {{PLURAL:$1|membro|membros}}',
'nrevisions'		=> '$1 {{PLURAL:$1|revisão|revisões}}',
'nviews'		=> '$1 {{PLURAL:$1|visita|visitas}}',

'lonelypages'   => 'Páginas órfãs',
'lonelypagestext'    => 'As seguintes páginas não têm hiperligações a apontar para elas a partir de outras páginas nesta wiki.',
'uncategorizedpages'    => 'Páginas não categorizadas',
'uncategorizedcategories'       => 'Categorias não categorizadas',
'uncategorizedimages' => 'Imagens não categorizadas',
'unusedcategories' => 'Categorias não utilizadas',
'unusedimages'  => 'Ficheiros não utilizados',
'popularpages'  => 'Páginas populares',
'wantedcategories' => 'Categorias pedidas',
'wantedpages'   => 'Páginas pedidas',
'mostlinked'    => 'Páginas com mais afluentes',
'mostlinkedcategories' => 'Categorias com mais afluentes',
'mostcategories' => 'Artigos com mais categorias',
'mostimages'    => 'Imagens com mais afluentes',
'mostrevisions' => 'Artigos com mais revisões',
'allpages'              => 'Todas as páginas',
'prefixindex'   => 'Índice de prefixo',
'randompage'    => 'Página aleatória',
'shortpages'    => 'Páginas curtas',
'longpages'             => 'Páginas longas',
'deadendpages'  => 'Páginas sem saída',
'deadendpagestext'    => 'As seguintes páginas não contêm hiperligações para outras páginas nesta wiki.',
'listusers'             => 'Lista de utilizadores',
'specialpages'  => 'Páginas especiais',
'spheading'             => 'Páginas especiais para todos os utilizadores',
'restrictedpheading'    => 'Páginas especiais restritas',
'recentchangeslinked' => 'Alterações relacionadas',
'rclsub'                => "(para páginas linkadas de \"$1\")",
'newpages'              => 'Páginas novas',
'newpages-username' => 'Nome de utilizador:',
'ancientpages'          => 'Páginas mais antigas',
'intl'          => 'Ligações interlínguas',
'move' => 'Mover',
'movethispage'  => 'Mover esta página',
'unusedimagestext' => '<p>Por favor note que outros websites como as Wikipédias internacionais podem apontar para uma imagem através de um URL directamente, e por isso pode estar aparecer aqui mesmo estando em uso.</p>',
'unusedcategoriestext' => 'As seguintes categorias existem embora nenhum artigo ou categoria faça uso delas.',
'booksources'   => 'Fontes de livros',
'categoriespagetext' => 'As seguintes categorias existem na wiki.',
'data'  => 'Dados',
'userrights' => 'Gestão de privilégios do utilizador',
'groups' => 'Grupos de utilizadores',

'booksourcetext' => "Abaixo encontra-se uma lista de ligações para outros websites que vendem livros novos ou usados, e poderão ter mais informações sobre os livros que procura.",
'isbn'  => 'ISBN',
'alphaindexline' => "$1 até $2",
'version'               => 'Versão',
'log'           => 'Registos',
'alllogstext'   => 'Exposição combinada de carregamento de ficheiros, eliminação, protecção, bloqueio, e de direitos.
Pode diminuir a lista escolhendo um tipo de registo, um nome de utilizar, ou uma página afectada.',
'logempty' => 'Nenhum item idêntico no registo.',

# Special:Allpages
'nextpage'          => 'Próxima página ($1)',
'allpagesfrom'          => 'Mostrar páginas começando em:',
'allarticles'           => 'Todos artigos',
'allinnamespace'        => 'Todas páginas (domínio $1)',
'allnotinnamespace'     => 'Todas páginas (não no domínio $1)',
'allpagesprev'          => 'Anterior',
'allpagesnext'          => 'Próximo',
'allpagessubmit'        => 'Ir',
'allpagesprefix'        => 'Exibir páginas com o prefixo:',
'allpagesbadtitle'    => 'O título de página dado encontrava-se inválido ou tinha um prefixo interlíngua ou inter-wiki. Poderá conter um ou mais caracteres que não podem ser utilizados em títulos.',

# Special:Listusers
'listusersfrom' => 'Mostrar utilizadores começando em:',

# E this user
#
'mailnologin'   => 'Nenhum endereço de envio',
'mailnologintext' => "Necessita de estar [[Special:Userlogin|autenticado]]
e de possuir um endereço de e-mail válido nas suas [[Special:Preferences|preferências]]
para enviar um e-mail a outros utilizadores.",
'emailuser'             => 'Contactar este utilizador',
'emailpage'             => 'Contactar utilizador',
'emailpagetext' => 'Se o utilizador introduziu um endereço válido de e-mail
nas suas preferências, poderá usar o formulário abaixo para lhe enviar uma mensagem.
O endereço que introduziu nas suas preferências irá aparecer no campo "From" do e-mail
para que o destinatário lhe possa responder.',
'usermailererror' => 'Objecto de correio retornou um erro:',
'defemailsubject'  => "E-mail: {{SITENAME}}",
'noemailtitle'  => 'Sem endereço de e-mail',
'noemailtext'   => 'Este utilizador não especificou um endereço de e-mail válido, ou optou por não receber e-mail de outros utilizadores.',
'emailfrom'             => 'De',
'emailto'               => 'Para',
'emailsubject'  => 'Assunto',
'emailmessage'  => 'Mensagem',
'emailsend'             => 'Enviar',
'emailsent'             => 'E-mail enviado',
'emailsenttext' => 'A sua mensagem foi enviada.',

# Watchlist
#
'watchlist'                     => 'Artigos vigiados',
'watchlistfor' => "(para '''$1''')",
'nowatchlist'           => 'Não existem itens na sua lista de artigos vigiados.',
'watchlistanontext' => 'Por favor $1 para ver ou editar os itens na sua lista de artigos vigiados.',
'watchlistcount'     => "'''Tem {{PLURAL:$1|$1 item|$1 items}} na sua lista de artigos vigiados, incluindo páginas de discussão.'''",
'clearwatchlist'     => 'Limpar lista de artigos vigiados',
'watchlistcleartext' => 'Tem a certeza que deseja removê-los?',
'watchlistclearbutton' => 'Limpar',
'watchlistcleardone' => 'A sua lista de artigos vigiados foi limpa. {{PLURAL:$1|$1 item foi removido|$1 items foram removidos}}.',
'watchnologin'          => 'Não está autenticado',
'watchnologintext'      => 'Deve estar [[Special:Userlogin|autenticado]] para modificar a sua lista de artigos vigiados.',
'addedwatch'            => 'Adicionado à lista',
'addedwatchtext'        => "A página \"$1\" foi adicionada à sua [[Special:Watchlist|lista de artigos vigiados]].
Modificações futuras neste artigo e páginas de discussão associadas serão listadas lá e a página aparecerá a '''negrito''' na [[Especial:Recentchanges|lista de mudanças recentes]], para que possa encontrá-la com maior facilidade.

Se desejar remover o artigo da sua lista de artigos vigiados, clique em \"Desinteressar-se\" na barra lateral ou de topo.",
'removedwatch'          => 'Removida da lista de artigos vigiados',
'removedwatchtext'      => "A página \"$1\" não é mais de seu interesse e portanto foi removida de sua lista de artigos vigiados",
'watch' => 'Vigiar',
'watchthispage'         => 'Vigiar esta página',
'unwatch' => 'Desinteressar-se',
'unwatchthispage'       => 'Parar de vigiar esta página',
'notanarticle'          => 'Não é um artigo',
'watchnochange'         => 'Nenhum dos itens vigiados foram editados no período exibido.',
'watchdetails'          => '* {{PLURAL:$1|$1 página vigiada|$1 páginas vigiadas}}, excluindo páginas de discussão
* [[Especial:Watchlist/edit|Mostrar e editar a lista completa]]
* [[Especial:Watchlist/clear|Remover todas as páginas]]',
'wlheader-enotif'               => "* Notificação por email encontra-se activada.",
'wlheader-showupdated'   => "* Páginas modificadas desde a sua última visita são mostradas a '''negrito'''",
'watchmethod-recent'=> 'verificando edições recentes para os artigos vigiados',
'watchmethod-list'      => 'verificando páginas vigiadas para edições recentes',
'removechecked'         => 'Remover itens seleccionados',
'watchlistcontains' => "Sua lista contém $1 páginas.",
'watcheditlist'         => 'Aqui está uma lista alfabética de sua lista de artigos vigiados. Marque as caixas dos artigos que você deseja remover da lista e clique no botão \'Remover itens seleccionados\' na parte de baixo do ecrã (removendo uma página de discussão remove também a página associada e vice versa).',
'removingchecked'       => 'Removendo os itens solicitados de sua lista de artigos vigiados...',
'couldntremove'         => "Não foi possível remover o item '$1'...",
'iteminvalidname'       => "Problema com item '$1', nome inválido...",
'wlnote'                => 'Abaixo as últimas $1 mudanças nas últimas <b>$2</b> horas.',
'wlshowlast'            => 'Ver últimas $1 horas $2 dias $3',
'wlsaved'               => 'Esta é uma versão salva de sua lista de artigos vigiados.',
'wlhideshowown'         => '$1 minhas edições',
'wlhideshowbots'        => '$1 edições por robôs',
'wldone'            => 'Concluído.',

'enotif_mailer'                 => '{{SITENAME}} Correio de Notificação',
'enotif_reset'                  => 'Marcar todas páginas como visitadas',
'enotif_newpagetext'=> 'Isto é uma nova página.',
'changed'                       => 'alterada',
'created'                       => 'criada',
'enotif_subject'        => '{{SITENAME}}: A página $PAGETITLE foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited' => 'Consulte $1 para todas as alterações efectuadas desde a sua última visita.',
'enotif_body' => 'Caro $WATCHINGUSERNAME,

A página $PAGETITLE na {{SITENAME}} foi $CHANGEDORCREATED a $PAGEEDITDATE por $PAGEEDITOR, consulte $PAGETITLE_URL para a versão actual.

$NEWPAGE

Sumário de editor: $PAGESUMMARY $PAGEMINOREDIT

Contacte o editor:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Não haverá mais notificações no caso de futuras alterações a não ser que visite esta página. Poderá também restaurar as bandeiras de notificação para todas as suas páginas vigiadas na sua lista de artigos vigiados.

             O seu sistema de notificação amigável da {{SITENAME}}

--
Para alterar as suas preferências da lista de artigos vigiados, visite
{{fullurl:Special:Watchlist/edit}}

Contacto e assistência
{{fullurl:Ajuda:Conteúdos}}',

# Delete/protect/revert
#
'deletepage'    => 'Eliminar página',
'confirm'               => 'Confirmar',
'excontent' => "conteúdo era: '$1'",
'excontentauthor' => "conteúdo era: '$1' (e o único editor era '[[Especial:Contributions/$2|$2]]')",
'exbeforeblank' => "conteúdo antes de esvaziar era: '$1'",
'exblank' => 'página esvaziada',
'confirmdelete' => 'Confirmar eliminação',
'deletesub'             => "(Eliminando \"$1\")",
'historywarning' => 'Aviso: A página que está prestes a eliminar possui um histórico:',
'confirmdeletetext' => "Encontra-se prestes a eliminar permanentemente uma página ou uma imagem e todo o seu histórico da base de dados.
Por favor confirme que entende fazer isto, e que compreende as consequências, e que encontra-se a fazer isto de acordo com a [[{{ns:project}}:Política de eliminação|Política de eliminação]] do projecto.",
'actioncomplete' => 'Acção completada',
'deletedtext'   => "\"$1\" foi eliminada.
Consulte $2 para um registo de eliminações recentes.",
'deletedarticle' => "eliminada \"[[$1]]\"",
'dellogpage'    => 'Registo de eliminação',
'dellogpagetext' => 'Abaixo uma lista das eliminações mais recentes.',
'deletionlog'   => 'registo de eliminação',
'reverted'              => 'Revertido para versão mais nova',
'deletecomment' => 'Motivo de eliminação',
'imagereverted' => 'Reversão para versão mais nova foi bem sucedida.',
'rollback'              => 'Voltar edições',
'rollback_short' => 'Voltar',
'rollbacklink'  => 'voltar',
'rollbackfailed' => 'Reversão falhou',
'cantrollback'  => 'Não foi possível reverter a edição; o último contribuidor é o único autor deste artigo',
'alreadyrolled' => "Não foi possível reverter as edições de [[:$1]]
por [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|Discussão]]); alguém editou ou já reverteu o artigo.

A última edição foi de [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Discussão]]).",

#   only shown if there is an edit comment
'editcomment' => "O sumário de edição era: \"<i>$1</i>\".",
'revertpage'    => "Revertidas edições por $2, para a última versão por $1",
'sessionfailure' => 'Foram detectados problemas com a sua sessão;
Esta acção foi cancelada como medida de protecção contra a intercepção de sessões.
Experimente usar o botão "Voltar atrás" e refrescar a página de onde veio, e repita o processo.',
'protectlogpage' => 'Registo de protecção',
'protectlogtext' => "Abaixo encontra-se o registo de protecção e desprotecção de páginas.
Veja [[{{ns:project}}:Página protegida]] para mais informações.",
'protectedarticle' => 'protegeu "[[$1]]"',
'unprotectedarticle' => 'desprotegeu "[[$1]]"',
'protectsub' => '(Protegendo "$1")',
'confirmprotecttext' => 'Deseja realmente proteger esta página?',
'confirmprotect' => 'Confirmar protecção',
'protectmoveonly' => 'Impedir apenas que a página seja movida.',
'protectcomment' => 'Motivo de protecção',
'unprotectsub' =>"(Desprotegendo \"$1\")",
'confirmunprotecttext' => 'Deseja realmente desproteger esta página?',
'confirmunprotect' => 'Confirmar desprotecção',
'unprotectcomment' => 'Motivo de desprotecção',
'protect-unchain' => 'Desbloquear permissões de moção',
'protect-text' => 'Pode ver e alterar aqui, o nível de protecção para a página <strong>$1</strong>.
Por favor tenha a certeza que segue as [[{{ns:project}}:Página protegida|normas do projecto]].',
'protect-viewtext' => 'A sua conta de utilizador não tem permissões para alterar
os níveis de protecção desta página. Estas são as configurações actuais para a página <strong>$1</strong>:',
'protect-default' => '(padrão)',
'protect-level-autoconfirmed' => 'Bloquear utilizadores não-registados',
'protect-level-sysop' => 'Administradores apenas',

# restrictions (nouns)
'restriction-edit' => 'Editar',
'restriction-move' => 'Mover',

# Undelete
'undelete' => 'Ver páginas eliminadas',
'undeletepage' => 'Ver e restaurar páginas eliminadas',
'viewdeletedpage' => 'Ver páginas eliminadas',
'undeletepagetext' => 'As páginas seguintes foram eliminadas mas ainda permanecem na base de dados e podem ser restauradas. O arquivo pode ser limpo periodicamente.',
'undeleteextrahelp' => "Para restaurar a página inteira, deixe todas as caixas de selecção desseleccionadas e
clique em '''''Restaurar'''''. Para efectuar uma restauração selectiva, seleccione as caixas correspondentes às
revisões a serem restauradas, e clique em '''''Restaurar'''''. Clicar em '''''Limpar''''' irá limpar o
campo de comentário e todas as caixas de selecção.",
'undeletearticle' => 'Restaurar artigo eliminado',
'undeleterevisions' => "$1 revisões arquivadas",
'undeletehistory' => 'Se restaurar uma página, todas as revisões serão restauradas para o histórico.
Se uma nova página foi criada com o mesmo nome desde a eliminação, as revisões restauradas aparecerão primeiro no histórico e a página actual não será automaticamente trocada.',
'undeletehistorynoadmin' => 'Este artigo foi eliminado. O motivo para a eliminação é apresentado no súmario abaixo, junto dos detalhes do utilizador que editou esta página antes de eliminar. O texto actual destas revisões eliminadas encontra-se agora apenas disponível para administradores.',
'undeleterevision' => "Revisões eliminadas de $1",
'undeletebtn' => 'Restaurar',
'undeletereset' => 'Limpar',
'undeletecomment' => 'Comentário:',
'undeletedarticle' => "restaurado \"[[$1]]\"",
'undeletedrevisions' => "$1 revisões restauradas",
'undeletedrevisions-files' => "$1 revisões e $2 ficheiro(s) restauradas",
'undeletedfiles' => "{{PLURAL:$1|ficheiro restaurado|$1 ficheiros restaurados}}",
'cannotundelete' => 'Restauração falhada; alguém poderá já ter restaurado a página primeiro.',
'undeletedpage' => "<big>'''$1 foi restaurada'''</big>

Consulte o [[Special:Log/delete|registo de eliminações]] para um registo das eliminações e restaurações mais recentes.",

# Namespace form on various pages
'namespace' => 'Domínio:',
'invert' => 'Inverter selecção',

# Contributions
#
'contributions' => 'Contribuições do utilizador',
'mycontris'     => 'Minhas contribuições',
'contribsub'    => "Para $1",
'nocontribs'    => 'Não foram encontradas mudanças com este critério.',
'ucnote'        => "Segue as últimas <b>$1</b> mudanças nos últimos <b>$2</b> dias deste utilizador.",
'uclinks'       => "Ver as últimas $1 mudanças; ver os últimos $2 dias.",
'uctop'         => ' (revisão actual)' ,
'newbies'       => 'novatos',

'sp-newimages-showfrom' => 'Mostrar novas imagens começando de $1',

'sp-contributions-newest' => 'Mais recente',
'sp-contributions-oldest' => 'Mais antigo',
'sp-contributions-newer'  => 'Novo $1',
'sp-contributions-older'  => 'Antigo $1',
'sp-contributions-newbies-sub' => 'Para novatos',

# What links here
#
'whatlinkshere' => 'Artigos afluentes',
'notargettitle' => 'Sem alvo',
'notargettext'  => 'Não especificou uma página alvo ou utilizador para executar esta função.',
'linklistsub'   => '(Lista de ligações)',
'linkshere'             => "Os seguintes artigos contêm ligações para '''[[:$1]]''':",
'nolinkshere'   => "Não existem ligações para '''[[:$1]]'''.",
'isredirect'    => 'página de redireccionamento',
'istemplate'    => 'inclusão',

# Block/unblock IP
#
'blockip'               => 'Bloquear utilizador',
'blockiptext'   => "Utilize o formulário abaixo para bloquear o acesso à escrita de um endereço específico de IP ou nome de utilizador.
Isto só deve ser feito para prevenir vandalismo, e de acordo com a [[{{ns:project}}:Política|política da {{SITENAME}}]]. Preencha com um motivo específico (por exemplo, citando páginas que sofreram vandalismo).",
'ipaddress'             => 'Endereço de IP',
'ipadressorusername' => 'Endereço de IP ou nome de utilizador',
'ipbexpiry'             => 'Prazo',
'ipbreason'             => 'Motivo',
'ipbanononly'   => 'Bloquear apenas utilizadores anónimos',
'ipbcreateaccount' => 'Prevenir criação de conta de utilizador',
'ipbsubmit'             => 'Bloquear este utilizador',
'ipbother'              => 'Outro tempo',
'ipboptions'            => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,infinito:infinite',
'ipbotheroption'        => 'outro',
'badipaddress'  => 'O endereço de IP inválido',
'blockipsuccesssub' => 'Bloqueio bem sucedido',
'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1|$1]] foi bloqueado.<br />Consulte a [[Special:Ipblocklist|lista de IPs bloqueados]] para rever os bloqueios.',
'unblockip'             => 'Desbloquear utilizador',
'unblockiptext' => 'Utilize o formulário a seguir para restaurar o acesso à escrita para um endereço de IP ou nome de utilizador previamente bloqueado.',
'ipusubmit'             => 'Desbloquear este utilizador',
'unblocked' => '[[User:$1|$1]] foi desbloqueado',
'ipblocklist'   => 'Lista de IPs bloqueados',
'blocklistline' => "$1, $2 bloqueou $3 ($4)",
'ipblocklistempty'      => 'A lista de IPs bloqueados encontra-se vazia.',
'infiniteblock' => 'infinito',
'expiringblock' => 'expira em $1',
'anononlyblock' => 'anón. apenas',
'createaccountblock' => 'criação de conta de utilizador bloqueada',
'blocklink'             => 'bloquear',
'unblocklink'   => 'desbloquear',
'contribslink'  => 'contribs',
'autoblocker'   => "Foi automaticamente bloqueado pois partilha um endereço de IP com \"$1\". Motivo é: \"$2\".",
'blocklogpage'  => 'Registo de bloqueio',
'blocklogentry' => 'bloqueou \"[[$1]]\" com um tempo de expiração de $2',
'blocklogtext'  => 'Isto é um registo de acções de bloqueio e desbloqueio. Endereços IP sujeitos a bloqueio automático não são listados. Consulte a [[Special:Ipblocklist|lista de IPs bloqueados]] para obter a lista de bloqueios operativos e bloqueios actualmente válidos.',
'unblocklogentry'       => 'desbloqueou $1',
'range_block_disabled'  => 'A funcionalidade de bloquear gamas de IPs encontra-se desactivada.',
'ipb_expiry_invalid'    => 'Tempo de expiração inválido.',
'ipb_already_blocked' => '"$1" já encontra-se bloqueado',
'ip_range_invalid'      => "Gama de IPs inválida.",
'proxyblocker'  => 'Bloqueador de proxy',
'ipb_cant_unblock' => 'Erro: Bloqueio com ID $1 não encontrado. Poderá já ter sido desbloqueado.',
'proxyblockreason'      => 'O seu endereço de IP foi bloqueado por ser um proxy público. Por favor contacte o seu fornecedor do serviço de Internet ou o apoio técnico e informe-os deste problema de segurança grave.',
'proxyblocksuccess'     => "Terminado.",
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'O seu endereço IP encontra-se listado como proxy aberto em [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'O seu endereço de IP encontra-se listado como proxy aberto no [http://www.sorbs.net SORBS] DNSBL. Não pode criar uma conta',

# Developer tools
#
'lockdb'                => 'Trancar base de dados',
'unlockdb'              => 'Destrancar base de dados',
'lockdbtext'    => 'Trancar a base de dados suspenderá a habilidade de todos os utilizadores de editarem páginas, mudarem suas preferências, lista de artigos vigiados e outras coisas que requerem mudanças na base de dados.<br />
Por favor confirme que realmente pretende fazer isso, e que vai destrancar a base de dados quando a manutenção estiver concluída.',
'unlockdbtext'  => 'Desbloquear a base de dados vai restaurar a habilidade de todos os utilizadores de editar  artigos,  mudar suas preferências, editar suas listas de artigos vigiados e outras coisas que requerem mudanças na base de dados. Por favor , confirme que realmente pretende fazer isso.',
'lockconfirm'   => 'Sim, eu realmente desejo bloquear a base de dados.',
'unlockconfirm' => 'Sim, eu realmente desejo desbloquear a base de dados.',
'lockbtn'               => 'Bloquear base de dados',
'unlockbtn'             => 'Desbloquear base de dados',
'locknoconfirm' => 'Não marcou a caixa de confirmação.',
'lockdbsuccesssub' => 'Bloqueio bem sucedido',
'unlockdbsuccesssub' => 'Desbloqueio bem sucedido',
'lockdbsuccesstext' => 'A base de dados da {{SITENAME}} foi bloqueada.
<br />Lembre-se de remover o bloqueio após a manutenção.',
'unlockdbsuccesstext' => 'A base de dados foi desbloqueada.',
'lockfilenotwritable' => 'O ficheiro de bloqueio da base de dados não pode ser escrito. Para bloquear ou desbloquear a base de dados, este precisa de poder ser escrito pelo servidor Web.',
'databasenotlocked' => 'A base de dados não encontra-se bloqueada.',

# Make sysop
'makesysoptitle'        => 'Tornar um utilizador num administrador',
'makesysoptext'         => 'Este formulário é utilizado por burocratas para tornar utilizadores comuns em administradores.
Introduza o nome do utilizador na caixa e clique no botão para tornar o utilizador num administrador',
'makesysopname'         => 'Nome do utilizador:',
'makesysopsubmit'       => 'Tornar este utilizador num administrador',
'makesysopok'           => "<b>Utilizador \"$1\" é agora um administrador</b>",
'makesysopfail'         => "<b>Não foi possível tornar o utilizador \"$1\" num administrador. (Introduziu o nome correctamente?)</b>",
'setbureaucratflag' => 'Atribuir flag de burocrata',
'rightslog'         => 'Registo de direitos de utilizador',
'rightslogtext'     => 'Este é um registo de mudanças nos direitos dos utilizadores.',
'rightslogentry'    => "Alterado grupo do membro de $1 de $2 para $3",
'rights'                        => 'Direitos:',
'set_user_rights'       => 'Definir direitos de utilizador',
'user_rights_set'       => "<b>Direitos de utilizador para \"$1\" actualizados</b>",
'set_rights_fail'       => "<b>Direitos de utilizador para \"$1\" não poderam ser definidos. (Introduziu o nome correctamente?)</b>",
'makesysop'         => 'Tornar um utilizador num administrador',
'already_sysop'     => 'Este utilizador já é um administrador',
'already_bureaucrat' => 'Este utilizador já é um burocrata',
'rightsnone'            => '(nenhum)',
# Move page
#
'movepage'              => 'Mover página',
'movepagetext'  => 'Utilizando o seguinte formulário poderá renomear uma página, movendo todo o histórico para o novo título. O título antigo será transformado num redireccionamento para o novo.
Links para as páginas antigas não serão mudados; certifique-se de [[Especial:Maintenance|verificar]] redireccionamentos quebrados ou artigos duplos. Você é responsável por certificar-se que os links continuam apontando para onde eles deveriam apontar.

Note que a página \'\'\'não\'\'\' será movida se já existir uma página com o novo título, a não ser que ele esteja vazio ou seja um redircecionamento e não tenha histórico de edições. Isto significa que pode renomear uma página de volta para o nome que tinha antigamente se cometer algum engano e que não pode sobrescrever uma página.

<b>CUIDADO!!!</b>
Isto pode ser uma mudança drástica e inesperada para uma página popular; por favor, tenha certeza de que compreende as consequências da mudança antes de avançar.',
'movepagetalktext' => 'A página de "discussão" associada, se existir, será automaticamente movida, \'\'\'a não ser que:\'\'\'
*Você esteja movendo uma página estre namespaces,
*Uma página de discussão (não-vazia) já exista sob o novo título, ou
*Você não marque a caixa abaixo.

Nestes casos, você terá que mover ou mesclar a página manualmente, se desejar.',
'movearticle'   => 'Mover página',
'movenologin'   => 'Não autenticado',
'movenologintext' => "Deve ser um utilizador registado e [[Special:Userlogin|autenticado]]</a>
para mover uma página.",
'newtitle'              => 'Para novo título',
'movepagebtn'   => 'Mover página',
'pagemovedsub'  => 'Página movida com sucesso',
'pagemovedtext' => "Página \"[[$1]]\" movida para \"[[$2]]\".",
'articleexists' => 'Uma página com este título já existe, ou o título que escolheu é inválido.
Por favor, escolha outro nome.',
'talkexists'    => "'''A página em si foi movida com sucesso, porém a página de discussão não pode ser movida, pois, já existe uma com este título. Por favor, mescle-as manualmente.'''",
'movedto'               => 'movido para',
'movetalk'              => 'Mover também a página de discussão associada.',
'talkpagemoved' => 'A página de \"discussão\" correspondente foi movida com sucesso.',
'talkpagenotmoved' => 'A página de discussão correspondente <strong>não</strong> foi movida.',
'1movedto2'             => "[[$1]] movido para [[$2]]",
'1movedto2_redir' => '[[$1]] movido para [[$2]] sob redireccionamento',
'movelogpage' => 'Registo de movimento',
'movelogpagetext' => 'Abaixo encontra-se uma lista de páginas movidas.',
'movereason'    => 'Motivo',
'revertmove'    => 'reverter',
'delete_and_move' => 'Eliminar e mover',
'delete_and_move_text'  =>
'==Eliminação necessária==
O artigo destinatário "[[$1]]" já existe. Deseja eliminá-lo de modo a poder mover a página?',
'delete_and_move_confirm' => 'Sim, eliminar a página',
'delete_and_move_reason' => 'Eliminada para poder mover outra página para este título',
'selfmove' => "O título fonte e o título destinatário são os mesmos; não é possível mover uma página para o mesmo sítio.",
'immobile_namespace' => "O título destinatário é de um tipo especial; não é possível mover páginas para esse domínio.",

# Export

'export'                => 'Exportação de páginas',
'exporttext'    => 'É possível exportar o texto e o histórico de edições de uma página em particular num ficheiro XML. Poderá então importar esse conteúdo noutra wiki que utilize o software MediaWiki através da página Especial:Import, ou transformar o conteúdo (via XSLT), ou ainda manter o ficheiro por motivos particulares.

Para exportar páginas, introduza os títulos na caixa de texto abaixo, um título por linha, e seleccione se deseja apenas a versão actual ou todas versões.

Se desejar pode utilizar uma ligação, por exemplo [[{{ns:Special}}:Export/{{Mediawiki:mainpage}}]] para o artigo [[{{Mediawiki:mainpage}}]].',
'exportcuronly' => 'Incluir apenas a revisão actual, não o histórico inteiro',
'exportnohistory' => "----
'''Nota:''' a exportação da história completa das páginas através deste formulário foi desactivada devido a motivos de performance.",
'export-submit' => 'Exportar',

# Namespace 8 related

'allmessages'   => 'Todas mensagens de sistema',
'allmessagesname' => 'Nome',
'allmessagesdefault' => 'Texto padrão',
'allmessagescurrent' => 'Texto actual',
'allmessagestext'       => 'Esta é uma lista de todas mensagens de sistema disponíveis no domínio MediaWiki:.',
'allmessagesnotsupportedUI' => 'O seu actual idioma de interface <b>$1</b> não é suportado pelo Especial:Allmessages deste sítio.',
'allmessagesnotsupportedDB' => 'Especial:Allmessages não pode ser utilizado devido ao wgUseDatabaseMessages estar desligado.',
'allmessagesfilter' => 'Filtro de nome de mensagem:',
'allmessagesmodified' => 'Mostrar apenas modificados',

# Thumbnails

'thumbnail-more'        => 'Ampliar',
'missingimage'          => "<b>Imagem não encontrada</b><br /><i>$1</i>",
'filemissing'           => 'Ficheiro não encontrado',
'thumbnail_error'   => 'Erro ao criar miniatura: $1',

# Special:Import
'import'        => 'Importar páginas',
'importinterwiki' => 'Importação transwiki',
'import-interwiki-text' => 'Seleccione uma wiki e um título de página a importar.
As datas das revisões e os seus editores serão mantidos.
Todas as acções de importação transwiki são registadas no [[Special:Log/import|Registo de importações]].',
'import-interwiki-history' => 'Copiar todas revisões para esta página',
'import-interwiki-submit' => 'Importar',
'import-interwiki-namespace' => 'Transferir páginas para o domínio:',
'importtext'    => 'Por favor exporte o ficheiro da fonte wiki utilizando o utilitário Especial:Export, salve o ficheiro para o seu disco e importe-o aqui.',
'importstart'    => "Importando páginas...",
'import-revision-count' => '$1 {{PLURAL:$1|revisão|revisões}}',
'importnopages'    => "Não existem páginas a importar.",
'importfailed'  => "Importação falhou: $1",
'importunknownsource'    => "Tipo de fonte de importação desconhecida",
'importcantopen'    => "Não foi possível abrir o ficheiro de importação",
'importbadinterwiki'    => "Ligação de interwiki incorrecta",
'importnotext'  => 'Vazio ou sem texto',
'importsuccess' => 'Importação bem sucedida!',
'importhistoryconflict' => 'Existem conflitos de revisões no histórico (poderá já ter importado esta página antes)',
'importnosources' => 'Não foram definidas fontes de importação transwiki e o carregamento directo de históricos encontra-se desactivado.',
'importnofile' => 'Nenhum ficheiro de importação foi carregado.',
'importuploaderror' => 'O carregamento do ficheiro de importação falhou; talvez o ficheiro seja maior do que o tamanho de carregamento permitido.',

# import log
'importlogpage' => 'Registo de importações',
'importlogpagetext' => 'Importações administrativas de páginas com revisões noutras wikis.',
'import-logentry-upload' => 'importado [[$1]] através de ficheiro de importação',
'import-logentry-upload-detail' => '{{PLURAL:$1|revisão|revisões}}',
'import-logentry-interwiki' => 'transwiki $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|revisão|revisões}} de $2',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',
'accesskey-watch' => 'w',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Pesquisar na {{SITENAME}} [alt-f]',
'tooltip-minoredit' => 'Marcar como edição menor [alt-i]',
'tooltip-save' => 'Salvar as alterações [alt-s]',
'tooltip-preview' => 'Prever as alterações, por favor utilizar antes de salvar! [alt-p]',
'tooltip-diff' => 'Mostrar alterações que fez a este texto. [alt-v]',
'tooltip-compareselectedversions' => 'Ver as diferenças entre as duas versões seleccionadas desta página. [alt-v]',
'tooltip-watch' => 'Adicionar esta página à sua lista de artigos vigiados [alt-w]',

# stylesheets
'common.css' => '/** o código CSS colocado aqui será aplicado a todos os temas */',
'monobook.css' => '/* o código CSS colocado aqui terá efeito nos utilizadores do tema Monobook */',

# Metadata
'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
'notacceptable' => 'O servidor não pode fornecer os dados num formato que o seu cliente possa ler.',

# Attribution

'anonymous' => 'Utilizador(es) anónimo(s) da {{SITENAME}}',
'siteuser' => '{{SITENAME}} utilizador $1',
'lastmodifiedatby' => 'Esta página foi modificada pela última vez a $2, $1 por $3.',
'and' => 'e',
'othercontribs' => 'Baseado no trabalho de $1.',
'others' => 'outros',
'siteusers' => '{{SITENAME}} utilizador(es) $1',
'creditspage' => 'Créditos da página',
'nocredits' => 'Não há informação disponível sobre os créditos desta página.',

# Spam protection

'spamprotectiontitle' => 'Filtro de protecção contra spam',
'spamprotectiontext' => 'A página que deseja salvar foi bloqueada pelo filtro de spam. Tal bloqueio foi provavelmente causado por uma ligação para um website externo.',
'spamprotectionmatch' => 'O seguinte texto activou o filtro de spam: $1',
'subcategorycount' => "{{PLURAL:$1|Existe uma subcategoria|Existem $1 subcategorias}} nesta categoria.",
'categoryarticlecount' => "{{PLURAL:$1|Existe um artigo|Existem $1 artigos}} nesta categoria.",
'listingcontinuesabbrev' => " cont.",
'spambot_username' => 'MediaWiki limpeza de spam',
'spam_reverting' => 'Revertendo para a última versão não contendo hiperligações para $1',
'spam_blanking' => 'Todas revisões contendo hiperligações para $1, limpando',

# Info page
'infosubtitle' => 'Informação para página',
'numedits' => 'Número de edições (artigo): $1',
'numtalkedits' => 'Número de edições (página de discussão): $1',
'numwatchers' => 'Number of watchers: $1',
'numauthors' => 'Número de autores distintos (artigo): $1',
'numtalkauthors' => 'Número de autores distintos (página de discussão): $1',

# Math options
'mw_math_png' => 'Gerar sempre PNG',
'mw_math_simple' => 'HTML caso seja simples, caso contrário PNG',
'mw_math_html' => 'HTML se possível, caso contrário PNG',
'mw_math_source' => 'Deixar como TeX (para navegadores de texto)',
'mw_math_modern' => 'Recomendado para navegadores modernos',
'mw_math_mathml' => 'MathML se possível (experimental)',

# Patrolling
'markaspatrolleddiff'   => "Marcar como verificado",
'markaspatrolledtext'   => "Marcar este artigo como verificado",
'markedaspatrolled'     => "Marcado como verificado",
'markedaspatrolledtext' => "A revisão seleccionada foi marcada como verificada.",
'rcpatroldisabled'      => "Edições verificadas nas Mudanças Recentes desactivadas",
'rcpatroldisabledtext'  => "A funcionalidade de Edições verificadas nas Mudanças Recentes está actualmente desactivada.",
'markedaspatrollederror'  => "Não pode marcar como verificado",
'markedaspatrollederrortext' => "Precisa de especificar uma revisão para marcar como verificado.",

# Monobook.js: tooltips and access keys for monobook
'monobook.js' => '/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Minha página de utilizador\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'A página de utilizador para o ip que está a utilizar para editar\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Minha página de discussão\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Discussão sobre edições deste endereço de ip\');
ta[\'pt-preferences\'] = new Array(\'\',\'Minhas preferências\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Lista de artigos vigiados.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Lista das minhas contribuições\');
ta[\'pt-login\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
ta[\'pt-logout\'] = new Array(\'\',\'Sair\');
ta[\'ca-talk\'] = new Array(\'t\',\'Discussão sobre o conteúdo da página\');
ta[\'ca-edit\'] = new Array(\'e\',\'Você pode editar esta página. Por favor, utilize o botão Mostrar Previsão antes de salvar.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Adicionar comentário a essa discussão.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Esta página está protegida; você pode exibir seu código, no entanto.\');
ta[\'ca-history\'] = new Array(\'h\',\'Edições anteriores desta página.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Proteger esta página\');
ta[\'ca-delete\'] = new Array(\'d\',\'Apagar esta página\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Restaurar edições feitas a esta página antes da eliminação\');
ta[\'ca-move\'] = new Array(\'m\',\'Mover esta página\');
ta[\'ca-watch\'] = new Array(\'w\',\'Adicionar esta página aos artigos vigiados\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Remover esta página dos artigos vigiados\');
ta[\'search\'] = new Array(\'f\',\'Pesquisar nesta wiki\');
ta[\'p-logo\'] = new Array(\'\',\'Página principal\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Visitar a página principal\');
ta[\'n-portal\'] = new Array(\'\',\'Sobre o projecto\');
ta[\'n-currentevents\'] = new Array(\'\',\'Informação temática sobre eventos actuais\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'A lista de mudanças recentes nesta wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Carregar página aleatória\');
ta[\'n-help\'] = new Array(\'\',\'Um local reservado para auxílio.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Ajude-nos\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Lista de todas as páginas que ligam-se a esta\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Mudanças recentes em páginas relacionadas a esta\');
ta[\'feed-rss\'] = new Array(\'\',\'Feed RSS desta página\');
ta[\'feed-atom\'] = new Array(\'\',\'Feed Atom desta página\');
ta[\'t-contributions\'] = new Array(\'\',\'Ver as contribuições deste utilizador\');
ta[\'t-emailuser\'] = new Array(\'\',\'Enviar um e-mail a este utilizador\');
ta[\'t-upload\'] = new Array(\'u\',\'Carregar imagens ou ficheiros media\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Lista de páginas especiais\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Ver o conteúdo da página\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Ver a página de utilizador\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Ver a página de media\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Esta é uma página especial, não pode ser editada.\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Ver a página de projecto\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Ver a página de imagem\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Ver a mensagem de sistema\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Ver a predefinição\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Ver a página de ajuda\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Ver a página da categoria\');',

# image deletion
'deletedrevision' => 'Apagada versão antiga $1.',

# browsing diffs
'previousdiff' => '← Ver a alteração anterior',
'nextdiff' => 'Ver a alteração posterior →',

'imagemaxsize' => 'Limitar imagens nas páginas de descrição a:',
'thumbsize'     => 'Tamanho de miniaturas:',
'showbigimage' => 'Descarregar versão de maior resolução ($1x$2, $3 KB)',

'newimages' => 'Galeria de novos ficheiros',
'showhidebots' => '($1 robôs)',
'noimages'  => 'Nada para ver.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',
# variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr-jc' => 'sr-jc',
'variantname-sr-jl' => 'sr-jl',
'variantname-sr' => 'sr',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Utilizador:',
'speciallogtitlelabel' => 'Título:',

'passwordtooshort' => 'A sua palavra-chave é demasiado curta. Deve ter no mínimo $1 caracteres.',

# Media Warning
'mediawarning' => '\'\'\'Aviso\'\'\': Este ficheiro pode conter código malicioso, ao executar o seu sistema poderá estar comprometido.
<hr />',

'fileinfo' => '$1KB, tipo MIME: <code>$2</code>',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'Este ficheiro contém informação adicional, provavelmente adicionada a partir da câmara digital ou scanner utilizada para criar ou digitalizar a imagem. Caso o ficheiro tenha sido modificado a partir do seu estado original, alguns detalhes poderão não reflectir completamente as mudanças efectuadas.',
'metadata-expand' => 'Mostrar restantes detalhes',
'metadata-collapse' => 'Esconder restantes detalhes',
#'metadata-fields' => 'Os campos EXIF metadata listados nesta mensagem serão
#incluídos na apresentação da página de detalhes da imagem quando a tabela da metadata
#for minimizada. Outros serão escondidos por defeito.
#* make
#* model
#* datetimeoriginal
#* exposuretime
#* fnumber
#* focallength', # ignore list
# Exif tags
'exif-imagewidth' =>'Largura',
'exif-imagelength' =>'Altura',
'exif-bitspersample' =>'Bits por componente',
'exif-compression' =>'Esquema de compressão',
'exif-photometricinterpretation' =>'Composição pixel',
'exif-orientation' =>'Orientação',
'exif-samplesperpixel' =>'Número de componentes',
'exif-planarconfiguration' =>'Arranjo de dados',
'exif-ycbcrsubsampling' =>'Subsampling ratio of Y to C',
'exif-ycbcrpositioning' =>'Posicionamento Y e C',
'exif-xresolution' =>'Resolução horizontal',
'exif-yresolution' =>'Resolução vertical',
'exif-resolutionunit' =>'Unit of X and Y resolution',
'exif-stripoffsets' =>'Localização de dados da imagem',
'exif-rowsperstrip' =>'Number of rows per strip',
'exif-stripbytecounts' =>'Bytes per compressed strip',
'exif-jpeginterchangeformat' =>'Offset to JPEG SOI',
'exif-jpeginterchangeformatlength' =>'Bytes de dados JPEG',
'exif-transferfunction' =>'Função de transferência',
'exif-whitepoint' =>'White point chromaticity',
'exif-primarychromaticities' =>'Chromaticities of primarities',
'exif-ycbcrcoefficients' =>'Color space transformation matrix coefficients',
'exif-referenceblackwhite' =>'Par de valores de referência de preto e branco',
'exif-datetime' =>'Data e hora de modificação do ficheiro',
'exif-imagedescription' =>'Título',
'exif-make' =>'Fabricante da câmara',
'exif-model' =>'Modelo da câmara',
'exif-software' =>'Software utilizado',
'exif-artist' =>'Autor',
'exif-copyright' =>'Licença',
'exif-exifversion' =>'Versão Exif',
'exif-flashpixversion' =>'Versão de Flashpix suportada',
'exif-colorspace' =>'Espaço de cor',
'exif-componentsconfiguration' =>'Significado de cada componente',
'exif-compressedbitsperpixel' =>'Modo de compressão de imagem',
'exif-pixelydimension' =>'Largura de imagem válida',
'exif-pixelxdimension' =>'Altura de imagem válida',
'exif-makernote' =>'Anotações do fabricante',
'exif-usercomment' =>'Comentários de utilizadores',
'exif-relatedsoundfile' =>'Ficheiro áudio relacionado',
'exif-datetimeoriginal' =>'Data e hora de geração de dados',
'exif-datetimedigitized' =>'Data e hora de digitalização',
'exif-subsectime' =>'DateTime subseconds',
'exif-subsectimeoriginal' =>'DateTimeOriginal subseconds',
'exif-subsectimedigitized' =>'DateTimeDigitized subseconds',
'exif-exposuretime' =>'Tempo de exposição',
'exif-exposuretime-format' => '$1 seg ($2)',
'exif-fnumber' =>'Número F',
'exif-fnumber-format' =>'f/$1',
'exif-exposureprogram' =>'Programa de exposição',
'exif-spectralsensitivity' =>'Spectral sensitivity',
'exif-isospeedratings' =>'Taxa de velocidade ISO',
'exif-oecf' =>'Factor optoelectrónico de conversão.',
'exif-shutterspeedvalue' =>'Velocidade do obturador',
'exif-aperturevalue' =>'Abertura',
'exif-brightnessvalue' =>'Brilho',
'exif-exposurebiasvalue' =>'Polarização de exposição',
'exif-maxaperturevalue' =>'Abertura máxima',
'exif-subjectdistance' =>'Distância do sujeito',
'exif-meteringmode' =>'Metering mode',
'exif-lightsource' =>'Fonte de luz',
'exif-flash' =>'Flash',
'exif-focallength' =>'Comprimento de foco da lente',
'exif-focallength-format' =>'$1 mm',
'exif-subjectarea' =>'Área de sujeito',
'exif-flashenergy' =>'Energia do flash',
'exif-spatialfrequencyresponse' =>'Spatial frequency response',
'exif-focalplanexresolution' =>'Focal plane X resolution',
'exif-focalplaneyresolution' =>'Focal plane Y resolution',
'exif-focalplaneresolutionunit' =>'Focal plane resolution unit',
'exif-subjectlocation' =>'Localização de sujeito',
'exif-exposureindex' =>'Índice de exposição',
'exif-sensingmethod' =>'Método de sensação',
'exif-filesource' =>'Fonte do ficheiro',
'exif-scenetype' =>'Tipo de cena',
'exif-cfapattern' =>'CFA pattern',
'exif-customrendered' =>'Custom image processing',
'exif-exposuremode' =>'Modo de exposição',
'exif-whitebalance' =>'White Balance',
'exif-digitalzoomratio' =>'Digital zoom ratio',
'exif-focallengthin35mmfilm' =>'Focal length in 35 mm film',
'exif-scenecapturetype' =>'Tipo de captura de cena',
'exif-gaincontrol' =>'Controlo de cena',
'exif-contrast' =>'Contraste',
'exif-saturation' =>'Saturação',
'exif-sharpness' =>'Sharpness',
'exif-devicesettingdescription' =>'Descrição das configurações do dispositivo',
'exif-subjectdistancerange' =>'Distância de alcance do sujeito',
'exif-imageuniqueid' =>'Identificação única da imagem',
'exif-gpsversionid' =>'Versão de GPS',
'exif-gpslatituderef' =>'Latitude Norte ou Sul',
'exif-gpslatitude' =>'Latitude',
'exif-gpslongituderef' =>'Longitude Leste ou Oeste',
'exif-gpslongitude' =>'Longitude',
'exif-gpsaltituderef' =>'Referência de altitude',
'exif-gpsaltitude' =>'Altitude',
'exif-gpstimestamp' =>'Tempo GPS (relógio atómico)',
'exif-gpssatellites' =>'Satélites utilizados para a medição',
'exif-gpsstatus' =>'Estado do receptor',
'exif-gpsmeasuremode' =>'Modo da medição',
'exif-gpsdop' =>'Precisão da medição',
'exif-gpsspeedref' =>'Unidade da velocidade',
'exif-gpsspeed' =>'Velocidade do receptor GPS',
'exif-gpstrackref' =>'Referência para a direcção do movimento',
'exif-gpstrack' =>'Direcção do movimento',
'exif-gpsimgdirectionref' =>'Referência para a direcção da imagem',
'exif-gpsimgdirection' =>'Direcção da imagem',
'exif-gpsmapdatum' =>'Utilizados dados do estudo Geodetic',
'exif-gpsdestlatituderef' =>'Referência para a latitude do destino',
'exif-gpsdestlatitude' =>'Latitude do destino',
'exif-gpsdestlongituderef' =>'Referência para a longitude do destino',
'exif-gpsdestlongitude' =>'Longitude do destino',
'exif-gpsdestbearingref' =>'Reference for bearing of destination',
'exif-gpsdestbearing' =>'Bearing of destination',
'exif-gpsdestdistanceref' =>'Referência de distância para o destino',
'exif-gpsdestdistance' =>'Distância para o destino',
'exif-gpsprocessingmethod' =>'Nome do método de processamento do GPS',
'exif-gpsareainformation' =>'Nome da área do GPS',
'exif-gpsdatestamp' =>'Data do GPS',
'exif-gpsdifferential' =>'Correcção do diferencial do GPS',

# Exif attributes

'exif-compression-1' => 'Descomprimido',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normal', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Flipped horizontally', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotated 180Â°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Flipped vertically', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotated 90Â° CCW and flipped vertically', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotated 90Â° CW', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotated 90Â° CW and flipped vertically', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotated 90Â° CCW', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'formato irregular',
'exif-planarconfiguration-2' => 'formato plano',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'não existe',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Não definido',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Aperture priority',
'exif-exposureprogram-4' => 'Shutter priority',
'exif-exposureprogram-5' => 'Creative program (biased toward depth of field)',
'exif-exposureprogram-6' => 'Programa de movimento (tende a velocidade de disparo mais rápida)',
'exif-exposureprogram-7' => 'Modo de retrato (para fotos em <i>closeup</i> com o fundo fora de foco)',
'exif-exposureprogram-8' => 'Modo de paisagem (para fotos de paisagem com o fundo em foco)',

'exif-subjectdistance-value' => '$1 metros',


'exif-lightsource-0' => 'Desconhecida',
'exif-lightsource-1' => 'Luz do dia',
'exif-lightsource-2' => 'Fluorescente',
'exif-lightsource-10' => 'Tempo nublado',

'exif-focalplaneresolutionunit-2' => 'polegadas',

'exif-customrendered-0' => 'Processo normal',
'exif-customrendered-1' => 'Processo personalizado',

'exif-exposuremode-0' => 'Exposição automática',
'exif-exposuremode-1' => 'Exposição manual',
'exif-exposuremode-2' => 'Auto bracket',

'exif-subjectdistancerange-0' => 'Desconhecida',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Vista próxima',
'exif-subjectdistancerange-3' => 'Vista distante',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Quilómetros por hora',
'exif-gpsspeed-m' => 'Milhas por hora',
'exif-gpsspeed-n' => 'Nós',

# external editor support
'edit-externally' => 'Editar este ficheiro utilizando uma aplicação externa',
'edit-externally-help' => 'Consulte as [http://meta.wikimedia.org/wiki/Help:External_editors instruções de instalação] para mais informação.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todas',
'imagelistall' => 'todas',
'watchlistall1' => 'todas',
'watchlistall2' => 'todas',
'namespacesall' => 'todas',

# E-mail address confirmation
'confirmemail' => 'Confirmar endereço de E-mail',
'confirmemail_noemail' => 'Não possui um endereço de e-mail válido indicado nas suas [[Special:Preferences|preferências de utilizador]].',
'confirmemail_text' => "Esta wiki requer que valide o seu endereço de e-mail antes de utilizar as funcionalidades que requerem um endereço de e-mail. Active o botão abaixo para enviar uma confirmação para o seu endereço de e-mail. A mensagem incluíra um endereço que contém um código; carregue o endereço no seu navegador para confirmar que o seu endereço de e-mail encontra-se válido.",
'confirmemail_send' => 'Enviar código de confirmação',
'confirmemail_sent' => 'E-mail de confirmação enviado.',
'confirmemail_sendfailed' => 'Não foi possível enviar o email de confirmação. Por favor verifique o seu endereço de e-mail.

Mailer retornou: $1',
'confirmemail_invalid' => 'Código de confirmação inválido. O código poderá ter expirado.',
'confirmemail_needlogin' => 'Precisa de $1 para confirmar o seu endereço de correio electrónico.',
'confirmemail_success' => 'O seu endereço de e-mail foi confirmado. Pode agora se ligar.',
'confirmemail_loggedin' => 'O seu endereço de e-mail foi agora confirmado.',
'confirmemail_error' => 'Alguma coisa correu mal ao guardar a sua confirmação.',

'confirmemail_subject' => '{{SITENAME}} confirmação de endereço de e-mail',
'confirmemail_body' => "Alguém, provavelmente você com o endereço de IP $1, registou uma conta \"$2\" com este endereço de e-mail na {{SITENAME}}.

Para confirmar que esta conta realmente é sua, e para activar
as funcionalidades de e-mail na {{SITENAME}}, abra o seguinte endereço no seu navegador:

$3

Caso este *não* seja você, não siga o endereço. Este código de confirmação
irá expirar a $4.",

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Try exact match',
'searchfulltext' => 'Pesquisar no texto completo',
'createarticle' => 'Criar artigo',

# Trackbacks
'trackbackbox' => "<div id='mw_trackbacks'>
Trackbacks for this article:<br />
$1
</div>",
'trackbackremove' => ' ([$1 Eliminar])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'The trackback was successfully deleted.',

# delete conflict

'deletedwhileediting' => 'Aviso: Esta página foi eliminada após você ter começado a editar!',
'confirmrecreate' => 'O utilizador [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|Discussão]]) eliminou este artigo após você ter começado a editar, pelo seguinte motivo:
: \'\'$2\'\'
Por favor confirme que realmente deseja recriar este artigo.',
'recreate' => 'Recriar',
'tooltip-recreate' => 'Recriar a página apesar de ter sido eliminada',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Redireccionando para [[$1]]...',

# action=purge
'confirm_purge' => "Limpar a memória cache desta página?\n\n$1",
'confirm_purge_button' => 'OK',

'youhavenewmessagesmulti' => "Tem novas mensagens em $1",

'searchcontaining' => "Pesquisar por artigos contendo ''$1''.",
'searchnamed' => "Pesquisar por artigos intitulados de ''$1''.",
'articletitles' => "Artigos começandor com ''$1''",
'hideresults' => 'Esconder resultados',

# DISPLAYTITLE
'displaytitle' => '(Ligar a esta página como [[$1]])',

'loginlanguagelabel' => 'Idioma: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; página anterior',
'imgmultipagenext' => 'próxima página &rarr;',
'imgmultigo' => 'Ir!',
'imgmultigotopre' => 'Mostrar página',

# Table pager
'ascending_abbrev' => 'asc',
'descending_abbrev' => 'desc',
'table_pager_next' => 'Próxima página',
'table_pager_prev' => 'Página anterior',
'table_pager_first' => 'Primeira página',
'table_pager_last' => 'Última página',
'table_pager_limit' => 'Mostrar $1 items por página',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty' => 'Sem resultados',
);


?>
