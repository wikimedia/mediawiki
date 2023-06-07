/* eslint-env node */
module.exports = function ( grunt ) {
    grunt.loadNpmTasks( 'grunt-jsonlint' );
    grunt.loadNpmTasks( 'grunt-banana-checker' );
    grunt.loadNpmTasks( 'grunt-eslint' );
    grunt.loadNpmTasks( 'grunt-stylelint' );
    grunt.loadNpmTasks( 'grunt-svgmin' );

    grunt.initConfig( {
        eslint: {
            all: [
                '**/*.js',
                '!resources/libraries/**',
                '!node_modules/**',
                '!vendor/**'
            ]
        },
        banana: {
            all: 'i18n/'
        },
        jsonlint: {
            all: [
                '**/*.json',
                '!node_modules/**',
                '!vendor/**'
            ]
        },
        stylelint: {
            all: [
                '**/*.css',
                '**/*.less',
                '!resources/libraries/**',
                '!node_modules/**',
                '!vendor/**'
            ]
        },
        // SVG Optimization
        svgmin: {
            options: {
                js2svg: {
                    indent: '\t',
                    pretty: true
                },
                multipass: true,
                plugins: [ {
                    cleanupIDs: false
                }, {
                    removeDesc: false
                }, {
                    removeRasterImages: true
                }, {
                    removeTitle: false
                }, {
                    removeViewBox: false
                }, {
                    removeXMLProcInst: false
                }, {
                    sortAttrs: true
                } ]
            },
            all: {
                files: [ {
                    expand: true,
                    cwd: 'resources/images',
                    src: [
                        '**/*.svg'
                    ],
                    dest: 'resources/images/',
                    ext: '.svg'
                } ]
            }
        }
    } );

    grunt.registerTask( 'minify', 'svgmin' );
    grunt.registerTask( 'test', [ 'eslint', 'jsonlint', 'banana', 'stylelint' ] );
    grunt.registerTask( 'default', [ 'minify', 'test' ] );
};
