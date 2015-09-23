require 'pp'
require 'json'

$bad_input = false
def bad_input file, text
	$bad_input = true
	$stderr.puts "#{file}: unrecognized input: #{text}"
end

def parse_dir dirname
	Dir.entries(dirname).map{|filename|
		if filename == '.' || filename == '..'
			nil
		else
			parse_any_path "#{dirname}/#{filename}"
		end
	}.compact.inject(:+)
end

def cleanup_class_name class_name
	class_name.sub(/OO\.ui\./, '')
end

def parse_file filename
	if filename !~ /\.(php|js)$/
		return nil
	end
	filetype = filename[/\.(php|js)$/, 1].to_sym

	text = File.read filename, encoding: 'utf-8'

	# ewwww
	# some docblocks are missing and we really need them
	text = text.sub(/(?<!\*\/\n)^class/, "/**\n*/\nclass")
	# text = text.sub('public static $targetPropertyName', "/**\n*/\npublic static $targetPropertyName")

	# find all documentation blocks, together with the following line (unless it contains another docblock)
	docblocks = text.scan(/\/\*\*[\s\S]+?\*\/\n[ \t]*(?:(?=\/\*\*)|.*)/)

	current_class = nil
	output = []
	previous_item = {} # dummy

	docblocks.each{|d|
		kind = nil
		previous_item = data = {
			name: nil,
			description: '',
			parent: nil,
			mixins: [],
			methods: [],
			properties: [],
			events: [],
			params: [],
			config: [],
			visibility: :public,
			type: nil,
		}
		valid_for_all = %w[name description].map(&:to_sym)
		valid_per_kind = {
			class:    valid_for_all + %w[parent mixins methods properties events abstract].map(&:to_sym),
			method:   valid_for_all + %w[params config return visibility static].map(&:to_sym),
			property: valid_for_all + %w[type static].map(&:to_sym),
			event:    valid_for_all + %w[params].map(&:to_sym),
		}

		js_class_constructor = false
		js_class_constructor_desc = ''
		ignore = false

		comment, code_line = d.split '*/'
		comment.split("\n").each{|comment_line|
			next if comment_line.strip == '/**'
			comment_line.sub!(/^[ \t]*\*[ \t]?/, '') # strip leading '*' and whitespace

			m = comment_line.match(/^@(\w+)[ \t]*(.*)/)
			if !m
				previous_item[:description] << comment_line + "\n"
				next
			end

			keyword, content = m.captures

			# handle JS class/constructor conundrum
			if keyword == 'class' || keyword == 'constructor'
				js_class_constructor = true
			end

			case keyword
			when 'constructor'
				# handle JS class/constructor conundrum
				js_class_constructor_desc = data[:description]
				data[:description] = ''
				kind = :method
			when 'class'
				kind = :class
			when 'method'
				kind = :method
			when 'property', 'var'
				kind = :property
				m = content.match(/^\{?(.+?)\}?( .+)?$/)
				if !m
					bad_input filename, comment_line
					next
				end
				type, description = m.captures
				data[:type] = type
				data[:description] = description if description
			when 'event'
				kind = :event
				data[:name] = content.strip
			when 'extends'
				data[:parent] = cleanup_class_name(content.strip)
			when 'mixins'
				data[:mixins] << cleanup_class_name(content.strip)
			when 'param'
				case filetype
				when :js
					type, name, default, description = content.match(/^\{(.+?)\} \[?([\w.$]+?)(?:=(.+?))?\]?( .+)?$/).captures
					next if type == 'Object' && name == 'config'
					data[:params] << {name: name, type: cleanup_class_name(type), description: description || '', default: default}
					previous_item = data[:params][-1]
				when :php
					type, name, config, description = content.match(/^(\S+) \&?\$(\w+)(?:\['(\w+)'\])?( .+)?$/).captures
					next if type == 'array' && name == 'config' && !config
					if config && name == 'config'
						data[:config] << {name: config, type: cleanup_class_name(type), description: description || ''}
						previous_item = data[:config][-1]
					else
						data[:params] << {name: name, type: cleanup_class_name(type), description: description || ''}
						previous_item = data[:params][-1]
					end
				end
			when 'cfg' # JS only
				m = content.match(/^\{(.+?)\} \[?([\w.$]+?)(?:=(.+?))?\]?( .+)?$/)
				if !m
					bad_input filename, comment_line
					next
				end
				type, name, default, description = m.captures
				data[:config] << {name: name, type: cleanup_class_name(type), description: description || '', default: default}
				previous_item = data[:config][-1]
			when 'return'
				case filetype
				when :js
					m = content.match(/^\{(.+?)\}( .+)?$/)
				when :php
					m = content.match(/^(\S+)( .+)?$/)
				end
				if !m
					bad_input filename, comment_line
					next
				end
				type, description = m.captures
				data[:return] = {type: cleanup_class_name(type), description: description || ''}
				previous_item = data[:return]
			when 'private'
				data[:visibility] = :private
			when 'protected'
				data[:visibility] = :protected
			when 'static'
				data[:static] = true
			when 'abstract'
				data[:abstract] = true
			when 'ignore'
				ignore = true
			when 'inheritable', 'deprecated', 'singleton', 'throws',
				 'chainable', 'fires', 'localdoc', 'inheritdoc', 'member',
				 'see'
				# skip
			else
				bad_input filename, comment_line
				next
			end
		}

		next if ignore

		if code_line && code_line.strip != ''
			case filetype
			when :js
				m = code_line.match(/(?:(static|prototype)\.)?(\w+) =/)
				if !m
					bad_input filename, code_line.strip
					next
				end
				kind_, name = m.captures
				data[:static] = true if kind_ == 'static'
				kind = {'static' => :property, 'prototype' => :method}[ kind_.strip ] if kind_ && !kind
				data[:name] = cleanup_class_name(name)
			when :php
				m = code_line.match(/
					\s*
					(?:(public|protected|private)\s)?
					(?:(static)\s)?(function\s|class\s|\$)
					(\w+)
					(?:\sextends\s(\w+))?
				/x)
				if !m
					bad_input filename, code_line.strip
					next
				end
				visibility, static, kind_, name, parent = m.captures
				kind = {'$' => :property, 'function' => :method, 'class' => :class}[ kind_.strip ]
				data[:visibility] = {'private' => :private, 'protected' => :protected, 'public' => :public}[ visibility ] || :public
				data[:static] = true if static
				data[:parent] = cleanup_class_name(parent) if parent
				data[:name] = cleanup_class_name(name)
			end
		end

		# handle JS class/constructor conundrum
		if kind == :class || js_class_constructor
			if current_class
				output << current_class
			end
			current_class = data.select{|k, _v| valid_per_kind[:class].include? k }
			current_class[:description] = js_class_constructor_desc if js_class_constructor_desc != ''
			previous_item = current_class
		end

		# standardize
		if data[:name] == '__construct' || js_class_constructor
			data[:name] = '#constructor'
		end

		# put into the current class
		if kind && kind != :class
			keys = {
				method: :methods,
				property: :properties,
				event: :events,
			}
			current_class[keys[kind]] << data.select{|k, _v| valid_per_kind[kind].include? k }
			previous_item = current_class[keys[kind]]
		end
	}

	# this is evil, assumes we only have one class in a file, but we'd need a proper parser to do it better
	if current_class
		current_class[:mixins] +=
			text.scan(/\$this->mixin\( .*?new (\w+)\( \$this/).flatten.map(&method(:cleanup_class_name))
	end

	output << current_class if current_class
	output
end

def parse_any_path path
	if File.directory? path
		result = parse_dir path
	else
		result = parse_file path
	end
	if $bad_input
		$stderr.puts 'Unrecognized inputs encountered, stopping.'
		exit 1
	end
	result
end

if __FILE__ == $PROGRAM_NAME
	if ARGV.empty? || ARGV == ['-h'] || ARGV == ['--help']
		$stderr.puts "usage: ruby #{$PROGRAM_NAME} <files...>"
		$stderr.puts "       ruby #{$PROGRAM_NAME} src > docs-js.json"
		$stderr.puts "       ruby #{$PROGRAM_NAME} php > docs-php.json"
	else
		out = JSON.pretty_generate ARGV.map{|a| parse_any_path a }.inject(:+)
		# ew
		out = out.gsub(/\{\s+\}/, '{}').gsub(/\[\s+\]/, '[]')
		puts out
	end
end
