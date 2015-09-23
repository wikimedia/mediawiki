HISTORY
=======

master current trunk
   * align with handlebars.js master

v0.21 https://github.com/zordius/lightncandy/tree/v0.21
   * align with handlebars.js 3.0.3
   * 9f24268d57 support FLAG_BARE to remove PHP start/end tags
   * 60d5a46c55 handle object/propery merge when deal with partial
   * d0130bf7e5 support undefined `{{helper undefined}}`
   * 8d228606f7 support `lcrun` to use customized render library when compile()
   * d0bad115f0 remove tmp PHP file when prepare() now
   * d84bbb4519 support keeping tmp PHP file when prepare()
   * ee833ae2f8 fix syntax validator bug on `{{helper "foo[]"}}`
   * 30b891ab28 fix syntax validator bug on `{{helper 'foo[]'}}`
   * 1867f1cc37 now count subexpression usage correctly
   * 78ef9b8a89 new syntax validator on handlebars variable name

v0.20 https://github.com/zordius/lightncandy/tree/v0.20
   * align with handlebars.js 3.0.0
   * 3d9a557af9 fix `{{foo (bar ../abc)}}` compile bug
   * 7dc16ac255 refine custom helper error detection logic
   * 72d32dc299 fix subexpression parsing bug inside `{{#each}}`
   * d1f1b93130 support context access inside a hbhelper by `$options['_this']`

v0.19 https://github.com/zordius/lightncandy/tree/v0.19
   * align with handlebars.js 3.0.0
   * 5703851e49 fix `{{foo bar=['abc=123']}}` parsing bug
   * 7b4e36a1e3 fix `{{foo bar=["abc=123"]}}` parsing bug
   * c710c8349b fix `{{foo bar=(helper a b c)}}` parsing bug
   * 4bda1c6f41 fix subexpression+builtin block helper (EX: `{{#if (foo bar)}}`) parsing bug
   * 6fdba10fc6 fix `{{foo ( bar) or " car" or ' cat' or [ cage]}}` pasing bug
   * 0cd5f2d5e2 fix indent issue when custom helper inside a partial
   * 296ea89267 support dynamic partial `{{> (foo)}}`
   * f491d04bd5 fix `{{../foo}}` look up inside root scope issue
   * 38fba8a5a5 fix scope issue for hbhelpers
   * a24a0473e2 change internal variable structure and fix for `{{number}}`
   * 7ae8289b7e fix escape in double quoted string bug
   * 90adb5531b fix `{{#if 0.0}}` logic to behave as false
   * 004a6ddffe fix `{{../foo}}` double usage count bug
   * 9d55f12c5a fix subexpression parsing bug when line change inside it

v0.18 https://github.com/zordius/lightncandy/tree/v0.18
   * align with handlebars.js 2.0.0
   * 7bcce4c1a7 support `{{@last}}` for `{{#each}}` on both object and array
   * b0c44c3b40 remove ending \n in lightncandy.php
   * e130875d5a support single quoted string input: `{{foo 'bar'}}`
   * c603aa39d8 support `renderex` to extend anything in render function
   * f063e5302c now render function debug constants works well in standalone mode
   * 53f6a6816d fix parsing bug when there is a `=` inside single quoted string
   * 2f16c0c393 now really autoload when installed with composer
   * c4da1f576c supports `{{^myHelper}}`

v0.17 https://github.com/zordius/lightncandy/tree/v0.17
   * align with handlebars.js 2.0.0
   * 3b48a0acf7 fix parsing bug when FLAG_NOESCAPE enabled
   * 5c774b1b08 fix hbhelpers response error with options['fn'] when FLAG_BESTPERFORMANCE enabled
   * c60fe70bdb fix hbhelpers response error with options['inverse'] when FLAG_BESTPERFORMANCE enabled
   * e19b3e3426 provide options['root'] and options['_parent'] to hbhelpers
   * d8a288e83b refine variable parsing logic to support `{{@../index}}`, `{{@../key}}`, etc.

v0.16 https://github.com/zordius/lightncandy/tree/v0.16
   * align with handlebars.js 2.0.0
   * 4f036aff62 better error message for named arguments
   * 0b462a387b support `{{#with var}}` ... `{{else}}` ... `{{/with}}`
   * 4ca624f651 fix 1 ANSI code error
   * 01ea3e9f42 support instances with PHP __call magic funciton
   * 38059036a7 support `{{#foo}}` or `{{#each foo}}` on PHP Traversable instance
   * 366f5ec0ac add FLAG_MUSTACHESP and FLAG_MUSTACHEPAIN into FLAG_HANDLEBARS and FLAG_HANDLEBARSJS now
   * b61d7b4a81 align with handlebars.js standalone tags behavior
   * b211e1742e now render false as 'false'
   * 655a2485be fix bug for `{{helper "==="}}`
   * bb58669162 support FLAG_NOESCAPE

v0.15 https://github.com/zordius/lightncandy/tree/v0.15
   * align with handlebars.js 2.0.0
   * 4c750806e8 fix for `\` in template
   * 12ab6626d6 support escape. `\{{foo}}` will be rendered as is. ( handlebars spec , require FLAG_SLASH )
   * 876bd44d9c escape &#x60; to &amp;#x60; ( require FLAG_JSQUOTE )
   * f1f388ed79 support `{{^}}` as `{{else}}` ( require FLAG_ELSE )
   * d5e17204b6 support `{{#each}}` == `{{#each .}}` now
   * 742126b440 fix `{{>foo/bar}} partial not found bug
   * d62c261ff9 support numbers as helper input `{{helper 0.1 -1.2}}`
   * d40c76b84f support escape in string arguments `{{helper "test \" double quote"}}`
   * ecb57a2348 fix for missing partial in partial bug
   * 1adad5dbfa fix `{{#with}}` error when FLAG_WITH not used
   * ffd5e35c2d fix error when rendering array value as `{{.}}` without FLAG_JSOBJECT
   * bd4987adbd support changing context on partial `{{>foo bar}}` ( require FLAG_RUNTIMEPARTIAL )
   * f5decaa7e3 support name sarguments on partial `{{>foo bar name=tee}}` . fix `{{..}}` bug
   * c20bb36457 support `partials` in options
   * e8779dbe8c change default `basedir` hehavior, stop partial files lookup when do not prodive `basedir` in options
   * c4e3401fe4 fix `{{>"test"}}` or `{{>[test]}}` or `{{>1234}}` bug
   * e59f62ea9b fix seciton behavior when input is object, and add one new flag: FLAG_MUSTACHESEC
   * 80eaf8e007 use static::method not self::method for subclass
   * 0bad5c8f20 fix usedFeature generation bugs

v0.14 https://github.com/zordius/lightncandy/tree/v0.14
   * align with handlebars.js 2.0.0-alpha.4
   * fa6225f278 support boolen value in named arguments for cusotm helper
   * 160743e1c8 better error message when unmatch `{{/foo}}` tag detected
   * d9a9416907 support `{{&foo}}`
   * 8797485cfa fix `{{^foo}}` logic when foo is empty list
   * 523b1373c4 fix handlebars custom helper interface
   * a744a2d522 fix bad syntax when FLAG_RENDER_DEBUG + helpers
   * 0044f7bd10 change FLAG_THIS behavoir
   * b5b0739b68 support recursive context lookup now ( mustache spec , require FLAG_MUSTACHELOOKUP )
   * 096c241fce support standalone tag detection now ( mustache spec , require FLAG_MUSTACHESP )
   * cea46c9a67 support `{{=<% %>=}}` to set delimiter
   * 131696af11 support subexpression `{{helper (helper2 foo) bar}}`
   * 5184d41be6 support runtime/recursive partial ( require FLAG_RUNTIMEPARTIAL )
   * 6408917f76 support partial indent ( mustache spec , require FLAG_MUSTACHEPAIN )

v0.13 https://github.com/zordius/lightncandy/tree/v0.13
   * align with handlebars.js 2.0.0-alpha.4
   * e5a8fe3833 fix issue #46 ( error with `{{this.foo.bar}}` )
   * ea131512f9 fix issue #44 ( error with some helper inline function PHP code syntax )
   * 522591a0c6 fix issue #49 ( error with some helper user function PHP code syntax )
   * c4f7e1eaac support `{{foo.bar}} lookup on instance foo then property/method bar ( flagd FLAG_PROPERTY or FLAG_METHOD required )
   * 0f4c0daa4b stop simulate Javascript output for array when pass input to custom helpers
   * 22d07e5f0f BIG CHANGE of custom helper interface

v0.12 https://github.com/zordius/lightncandy/tree/v0.12
   * align with handlebars.js 2.0.0-alpha.2
   * 64db34cf65 support `{{@first}}` and `{{@last}}`
   * bfa1fbef97 add new flag FLAG_SPVARS
   * 10a4623dc1 remove json schema support
   * 240d9fa290 only export used LCRun2 functions when compile() with FLAG_STANDALONE now
   * 3fa897c98c rename LCRun2 to LCRun3 for interface changed, old none standalone templates will error with newer version
   * e0838c7418 now can output debug template map with ANSI color
   * 80dbeab63d fix php warning when compile with custom helper or block custom helper
   * 8ce6268b64 support Handlebars.js style custom helper

v0.11 https://github.com/zordius/lightncandy/tree/v0.11
   * align with handlebars.js 2.0.0-alpha.2
   * a275d52c97 use php array, remove val()
   * 8834914c2a only export used custom helper into render function now
   * eb6d82d871 refine option flag consts
   * fc437295ed refine comments for phpdoc
   * fbf116c3e2 fix for tailing ; after helper functions
   * f47a2d5014 fix for wrong param when new Exception
   * 94e71ebcbd add isset() check for input value
   * a826b8a1ab support `{{else}}` in `{{#each}}` now
   * 25dac11bb7 support `{{!-- comments --}}` now (this handlebars.js extension allow `}}` apperas in the comments)
   * e142b6e116 support `{{@root}}` or `{{@root.foo.bar}}` now
   * 58c8d84aa2 custom helper can return extra flag to change html encoded behavior now

v0.10 https://github.com/zordius/lightncandy/tree/v0.10
   * align with handlebars.js 2.0.0-alpha.1
   * 4c9f681080 file name changed: lightncandy.inc => lightncandy.php
   * e3de01081c some minor fix for json schema
   * 1feec458c7 new variable handling logic, save variable name parsing time when render() . rendering performance improved 10~30%!
   * 3fa897c98c rename LCRun to LCRun2 for interface changed, old none standalone templates will error with newer version
   * 43a6d33717 fix for `{{../}}` php warning message
   * 9189ebc1e4 now auto push documents from Travis CI
   * e077d0b631 support named arguments for custom helpers `{{helper name=value}}`
   * 2331b6fe55 support block custom helpers
   * 4fedaa25f7 support number value as named arguments
   * 6a91ab93d2 fix for default options and php warnings
   * fc157fde62 fix for doblue quoted arguments (issue #15)

v0.9 https://github.com/zordius/lightncandy/tree/v0.9
   * align with handlebars.js 1.3
   * a55f2dd067 support both `{{@index}}` and `{{@key}}` when `{{#each an_object}}`
   * e59f931ea7 add FLAG_JSQUOTE support
   * 92b3cf58af report more than 1 error when compile()
   * 93cc121bcf test for wrong variable name format in test/error.php
   * 41c1b431b4 support advanced variable naming `{{foo.[bar].10}}` now
   * 15ce1a00a8 add FLAG_EXTHELPER option
   * f51337bde2 support space control `{{~sometag}}` or `{{sometag~}}`
   * fe3d67802e add FLAG_SPACECTL option
   * 920fbb3039 support custom helper
   * 07ae71a1bf migrate into Travis CI
   * ddd3335ff6 support "some string" argument
   * 20f6c888d7 html encode after custom helper executed
   * 10a2f45fdc add test generator
   * ccd1d3ddc2 migrate to Scrutinizer , change file name LightnCandy.inc to LightnCandy.php
   * 5ac8ad8d04 now is a Composer package

v0.8 https://github.com/zordius/lightncandy/tree/v0.8
   * align with handlebars.js 1.0.12
   * aaec049 fix partial in partial not works bug
   * 52706cc fix for `{{#var}}` and `{{^var}}` , now when var === 0 means true
   * 4f7f816 support `{{@key}}` and `{{@index}}` in `{{#each var}}`
   * 63aef2a prevent array_diff_key() PHP warning when `{{#each}}` on none array value
   * 10f3d73 add more is_array() check when `{{#each}}` and `{{#var}}`
   * 367247b fix `{{#if}}` and `{{#unless}}` when value is an empty array
   * c76c0bb returns null if var is not exist in a template [contributed by dtelyukh@github.com]
   * d18bb6d add FLAG_ECHO support
   * aec2b2b fix `{{#if}}` and `{{#unless}}` when value is an empty string
   * 8924604 fix variable output when false in an array
   * e82c324 fix for ifv and ifvar logic difference
   * 1e38e47 better logic on var name checking. now support `{{0}}` in the loop, but it is not handlebars.js standard

v0.7 https://github.com/zordius/lightncandy/tree/v0.7
   * align with handlebarsjs 1.0.11
   * add HISTORY.md
   * 777304c change compile format to include in val, isec, ifvar
   * 55de127 support `{{../}}` in `{{#each}}`
   * 57e90af fix parent levels detection bug
   * 96bb4d7 fix bugs for `{{#.}}` and `{{#this}}`
   * f4217d1 add ifv and unl 2 new methods for LCRun
   * 3f1014c fix `{{#this}}` and `{{#.}}` bug when used with `{{../var}}`
   * cbf0b28 fix `{{#if}}` logic error when using `{{../}}`
   * 2b20ef8 fix `{{#with}}` + `{{../}}` bug
   * 540cd44 now support FLAG_STANDALONE
   * 67ac5ff support `{{>partial}}`
   * 98c7bb1 detect unclosed token now

v0.6 https://github.com/zordius/lightncandy/tree/v0.6
   * align with handlebarsjs 1.0.11
   * 45ac3b6 fix #with bug when var is false
   * 1a46c2c minor #with logic fix. update document
   * fdc753b fix #each and section logic for 018-hb-withwith-006
   * e6cc95a add FLAG_PARENT, detect template error when scan()
   * 1980691 make new LCRun::val() method to normal path.val logic
   * 110d24f `{{#if path.var}}` bug fixed
   * d6ae2e6 fix `{{#with path.val}}` when input value is null
   * 71cf074 fix for 020-hb-doteach testcase

v0.5 https://github.com/zordius/lightncandy/tree/v0.5
   * 955aadf fix #each bug when input is a hash
   * final version for following handlebarsjs 1.0.7
