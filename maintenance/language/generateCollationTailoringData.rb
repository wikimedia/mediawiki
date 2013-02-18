# coding: utf-8

require 'restclient'
require 'nokogiri'
require 'unicode_utils'
require 'pp'

n = Nokogiri.HTML RestClient.get 'http://developer.mimer.com/charts/tailorings.htm'

data = n.css('table tr').drop(3).map do |e|
	langcode = e.at('td:first-child .language, td:first-child').children.last.text[/\(([a-z-]+)/, 1]
	$stderr.puts langcode
	rules_container = e.at('td:last-child').at('b')
	rules = rules_container ? (rules_container.text.gsub('&lt', '<').gsub("\u00A0", ' ')) : ''
	
	tailored_first_letters = []
	rules.split('&').each do |chunk|
		next if chunk.strip.empty?
		chunk.strip.split(/\s+/).each_cons(3) do |a, mode, b|
			next unless mode =~ /\A<+\z/
			if mode == '<'
				tailored_first_letters << UnicodeUtils.upcase(b, langcode.to_sym)
			end
		end
	end
	
	[langcode, rules, tailored_first_letters]
end

data = data.sort_by{|a| a[0] }

puts data.map{|langcode, rules, letters|
	letters = letters.map{|lt| lt.dup.force_encoding('us-ascii').inspect }
	"'#{langcode}' => array( #{letters.join ", "} ),".sub('(  )', '()')
}

$stderr.puts data.map{|langcode, rules, letters|
	"#{langcode}: #{letters.join " "}"
}
