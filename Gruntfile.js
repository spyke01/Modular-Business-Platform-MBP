'use strict';
module.exports = function(grunt) {
  //var pkg = grunt.file.readJSON('package.json');

  grunt.initConfig({
    // setting folder templates
    dirs: {
      css: 'assets/css',
      images: 'images',
      js: 'javascripts',
      //less: 'assets/less'
    },
    pkg: grunt.file.readJSON('package.json'),

    // Compile all .less files.
    less: {
      /*
          admin: {
              files: {
                  '<%= dirs.css %>/admin.css': '<%= dirs.less %>/admin/admin.less',
                  '<%= dirs.css %>/setup.css': '<%= dirs.less %>/admin/setup.less',
                  '<%= dirs.css %>/accounting.css': '<%= dirs.less %>/admin/accounting.less'
              }
          }
  */
    },

    browserify: {
      development: {
        src: [
          '<%= dirs.js %>/functions.js',
          '<%= dirs.js %>/widgets.js',
        ],
        dest: '<%= dirs.js %>/common.js',
        options: {
          browserifyOptions: { debug: true },
          transform: [["babelify", { "presets": ["@babel/preset-env"] }]],
          plugin: [
            ["factor-bundle", { outputs: [
                '<%= dirs.js %>/functions.min.js',
                '<%= dirs.js %>/widgets.min.js',
              ] }]
          ]
        }
      }
    },

    jshint: {
      options: {
        jshintrc: '.jshintrc',
      },
      all: [
        'Gruntfile.js',
        '<%= dirs.js %>/*.js',
        '!<%= dirs.js %>/*.min.js',
      ],
    },

    watch: {
      /*
        less: {
            files: ['<%= dirs.less %>/*.less', '<%= dirs.less %>/admin/*.less' ],
            tasks: ['less:admin'],
            options: {
                livereload: true
            }
        }
        */
    },

    // Handle updating the version number
    bump: {
      options: {
        files: ['package.json'],
        updateConfigs: ['pkg'],
        commit: false,
        push: false,
      },
    },

    // Clean up build directory
    clean: {
      main: ['dist/'],
    },

    // Copy the plugin into the build directory
    copy: {
      build: {
        src: [
          '**',
          '!.codekit-cache/**',
          '!.git/**',
          '!.idea/**',
          '!.vagrant/**',
          '!assets/less/**',
          '!bin/**',
          '!dist/**',
          '!modules/**',
          '!nbproject/*',
          '!node_modules/**',
          '!vendor/bin/**',
          '!vendor/laravel/homestead/**',
          '!tests/**',
          '!.gitignore',
          '!.gitmodules',
          '!_db.php',
          '!_license.php',
          '!composer.lock',
          '!config.codekit',
          '!CONTRIBUTING.md',
          '!debug.log',
          '!export.sh',
          '!Gruntfile.js',
          '!Homestead.yaml',
          '!npm-debug.log',
          '!package.box',
          '!package.json',
          '!package-lock.json',
          '!phpunit.xml',
          '!plugin-deploy.sh',
          '!README.md',
          '!yarn-error.log',
          '!yarn.lock',
          '!**/*~',
        ],
        dest: 'dist/',
      },
      nodemodules: {
        src: [
          'node_modules/autosize/dist/**',
          'node_modules/bootbox.js/**',
          'node_modules/bootstrap-switch/dist/**',
          'node_modules/bootstrap-treeview/dist/**',
          'node_modules/bootstro/**',
          'node_modules/bootstrap-icon-picker/**',
          'node_modules/form/dist/**',
          'node_modules/jquery-jeditable/dist/**',
          'node_modules/jquery-minicolors/**',
          'node_modules/jQuery-Timepicker-Addon/dist/**',
          'node_modules/jquery-validation/build/**',
          'node_modules/jquery.pwstrength/dist/**',
          'node_modules/justgage/**',
          'node_modules/knockout/build/output/**',
          'node_modules/nestedSortable/**',
          'node_modules/raphael/**',
          'node_modules/select2/**',
          'node_modules/smartmenus/**',
          'node_modules/tablesorter/**',
          '!**/*~',
        ],
        dest: 'dist/',
      },
    },

    'string-replace': {
      version: {
        files: {
          'dist/includes/constants.php': 'includes/constants.php',
        },
        options: {
          replacements: [
            {
              pattern: /{{ VERSION }}/g,
              replacement: '<%= pkg.version %>',
            }],
        },
      },
    },

    // This allows us to create any folders that we need to be empty
    mkdir: {
      all: {
        options: {
          create: ['dist/modules'],
        },
      },
    },

    //Compress build directory into <name>.zip and <name>-<version>.zip
    compress: {
      main: {
        options: {
          mode: 'zip',
          //archive: './dist/mbp-v' + pkg.version + '.zip'
          archive: './dist/mbp.zip',
        },
        cwd: 'dist/',
        dest: '/',
        expand: true,
        src: ['*'],
      },
    },

    exec: {
      composer: {
        cmd: 'cd dist ; composer install --no-dev --optimize-autoloader',
      },
    },

  });

  // Load NPM tasks to be used here
  grunt.loadNpmTasks('grunt-bump');
  grunt.loadNpmTasks('grunt-composer');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-browserify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-exec');
  grunt.loadNpmTasks('grunt-mkdir');
  grunt.loadNpmTasks('grunt-string-replace');
  grunt.loadNpmTasks('grunt-text-replace');

  grunt.registerTask('default', [
    'browserify',
  ]);

  grunt.registerTask('productionTasks', [
    'exec:composer',
    'copy:nodemodules',
  ]);

  grunt.registerTask('build', [
    'browserify',
    'clean',
    'copy:build',
    'mkdir',
    'string-replace',
    'productionTasks',
  ]);

  grunt.registerTask('cleanup', [
    'clean',
  ]);

  grunt.registerTask('release', [
    'browserify',
    'clean',
    'copy:build',
    'mkdir',
    'bump',
    'string-replace',
    'productionTasks',
  ]);

  grunt.registerTask('zip', [
    'build', 'compress',
  ]);

  grunt.registerTask('zip-install', [
    'release', 'compress',
  ]);
};
