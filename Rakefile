require 'bundler/setup'

require 'rubocop/rake_task'
RuboCop::RakeTask.new(:rubocop) do |task|
  # if you use mediawiki-vagrant, rubocop will by default use it's .rubocop.yml
  # the next line makes it explicit that you want .rubocop.yml from the directory
  # where `bundle exec rake` is executed
  task.options = ['-c', '.rubocop.yml']
end

<<<<<<< HEAD
=======
require 'mediawiki_selenium/rake_task'
MediawikiSelenium::RakeTask.new(site_tag: false)

>>>>>>> a51acbb6409dd7ab17d9e33a46615bdb3ff32032
task default: [:test]

desc 'Run all build/tests commands (CI entry point)'
task test: [:rubocop]
