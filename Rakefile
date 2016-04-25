require 'bundler/setup'

require 'rubocop/rake_task'
RuboCop::RakeTask.new(:rubocop) do |task|
  # if you use mediawiki-vagrant, rubocop will by default use it's .rubocop.yml
  # the next line makes it explicit that you want .rubocop.yml from the directory
  # where `bundle exec rake` is executed
  task.options = ['-c', '.rubocop.yml']
end

require 'mediawiki_selenium/rake_task'
MediawikiSelenium::RakeTask.new(site_tag: false)

task default: [:test]

desc 'Run all build/tests commands (CI entry point)'
task test: [:rubocop]
