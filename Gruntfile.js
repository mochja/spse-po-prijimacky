module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: ['tmp/dist/spin.js.js', 'tmp/dist/ladda.js'],
        dest: 'public/dist/<%= pkg.name %>.min.js'
      }
    },
    
    cssmin: {
      add_banner: {
        options: {
          banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
        },
        files: {
          'public/dist/<%= pkg.name %>.min.css': ['tmp/dist/ladda-themeless.css']
        }
      }
    },
    
    bower: {
      dev: {
        dest: 'tmp/dist'
      }     
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-bower');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  // Default task(s).
  grunt.registerTask('default', ['bower', 'uglify', 'cssmin']);

};