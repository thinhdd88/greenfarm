module.exports = function(grunt) {
    var skinDir = 'skin/frontend/greenfarm/default/';
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        compass: {
            dist: {
                options: {
                    sassDir: skinDir + 'scss',
                    cssDir: skinDir + 'css',
                    environment: 'development',
                    outputStyle: 'nested'
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            livereload: {
                files: [
                    skinDir + 'scss/{,*/}*.scss',
                    [skinDir + 'js/*.js', '!' + skinDir + 'js/*.min.js']
                ],
                tasks: [
                    'compass',
                ]
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', [
        'compass'
    ]);
};
