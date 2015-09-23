require 'pp'
require_relative 'docparser'

if ARGV.empty? || ARGV == ['-h'] || ARGV == ['--help']
	$stderr.puts "usage: ruby #{$PROGRAM_NAME} <dirA> <dirB>"
	$stderr.puts "       ruby #{$PROGRAM_NAME} src php > tests/JSPHP-suite.json"
else
	dir_a, dir_b = ARGV
	js = parse_any_path dir_a
	php = parse_any_path dir_b

	class_names = (js + php).map{|c| c[:name] }.sort.uniq

	tests = []
	classes = php.select{|c| class_names.include? c[:name] }

	testable_classes = classes
		.reject{|c| c[:abstract] } # can't test abstract classes
		.reject{|c| !c[:parent] || c[:parent] == 'ElementMixin' || c[:parent] == 'Theme' } # can't test abstract
		.reject{|c| %w[Element Widget Layout Theme].include? c[:name] } # no toplevel
		.reject{|c| %w[DropdownInputWidget RadioSelectInputWidget].include? c[:name] } # different PHP and JS implementations

	# values to test for each type
	expandos = {
		'null' => [nil],
		'number' => [0, -1, 300],
		'boolean' => [true, false],
		'string' => ['Foo bar', '<b>HTML?</b>'],
	}

	# values to test for names
	sensible_values = {
		'href' => ['http://example.com/'],
		['TextInputWidget', 'type'] => %w[text password foo],
		['ButtonInputWidget', 'type'] => %w[button submit foo],
		['FieldLayout', 'help'] => true, # different PHP and JS implementations
		['ActionFieldLayout', 'help'] => true, # different PHP and JS implementations
		['FieldsetLayout', 'help'] => true, # different PHP and JS implementations
		['FieldLayout', 'errors'] => expandos['string'].map{|v| [v] }, # treat as string[]
		['FieldLayout', 'notices'] => expandos['string'].map{|v| [v] }, # treat as string[]
		'type' => %w[text button],
		'method' => %w[GET POST],
		'action' => [],
		'enctype' => true,
		'target' => ['_blank'],
		'accessKey' => ['k'],
		'name' => true,
		'autofocus' => true, # usually makes no sense in JS
		'tabIndex' => [-1, 0, 100],
		'maxLength' => [100],
		'icon' => ['picture'],
		'indicator' => ['down'],
		'flags' => %w[constructive],
		'label' => expandos['string'] + ['', ' '],
		# these are defined by Element and would bloat the tests
		'classes' => true,
		'id' => true,
		'content' => true,
		'text' => true,
	}

	find_class = lambda do |klass|
		return classes.find{|c| c[:name] == klass }
	end

	expand_types_to_values = lambda do |types|
		return types.map{|t|
			as_array = true if t.sub! '[]', ''
			t = 'ButtonWidget' if t == 'Widget' # Widget is not "testable", use a subclass
			if expandos[t]
				# Primitive. Run tests with the provided values.
				vals = expandos[t]
			elsif testable_classes.find{|c| c[:name] == t }
				# OOUI object. Test suite will instantiate one and run the test with it.
				params = find_class.call(t)[:methods][0][:params] || []
				config = params.map{|config_option|
					types = config_option[:type].split '|'
					values = expand_types_to_values.call(types)
					{ config_option[:name] => values[0] }
				}
				vals = [ '_placeholder_' + {
					class: t,
					config: config.inject({}, :merge)
				}.to_json ]
			else
				# We don't know how to test this. The empty value will result in no
				# tests being generated for this combination of config values.
				vals = []
			end
			as_array ? vals.map{|v| [v] } : vals
		}.inject(:+)
	end

	find_config_sources = lambda do |klass_name|
		return [] unless klass_name
		klass_names = [klass_name]
		while klass_name
			klass = find_class.call(klass_name)
			break unless klass
			klass_names +=
				find_config_sources.call(klass[:parent]) +
				klass[:mixins].map(&find_config_sources).flatten
			klass_name = klass[:parent]
		end
		return klass_names.uniq
	end

	testable_classes.each do |klass|
		config_sources = find_config_sources.call(klass[:name])
			.map{|c| find_class.call(c)[:methods][0] }
		config = config_sources.map{|c| c[:config] }.compact.inject(:+)
		required_config = klass[:methods][0][:params] || []

		# generate every possible configuration of configuration option sets
		maxlength = [config.length, 2].min
		config_combinations = (0..maxlength).map{|l| config.combination(l).to_a }.inject(:+)
		# for each set, generate all possible values to use based on option's type
		config_combinations = config_combinations.map{|config_comb|
			config_comb += required_config
			expanded = config_comb.map{|config_option|
				types = config_option[:type].split '|'
				sensible = sensible_values[ [ klass[:name], config_option[:name] ] ] ||
					sensible_values[ config_option[:name] ]
				if sensible == true
					[] # the empty value will result in no tests being generated
				else
					values = sensible || expand_types_to_values.call(types)
					values.map{|v| config_option.dup.merge(value: v) } + [nil]
				end
			}
			expanded.length > 0 ? expanded[0].product(*expanded[1..-1]) : []
		}.inject(:concat).map(&:compact).uniq

		# really require the required ones
		config_combinations = config_combinations.select{|config_comb|
			required_config.all?{|r| config_comb.find{|c| c[:name] == r[:name] } }
		}

		config_combinations.each do |config_comb|
			tests << {
				class: klass[:name],
				config: Hash[ config_comb.map{|c| [ c[:name], c[:value] ] } ]
			}
		end
	end

	$stderr.puts "Generated #{tests.length} test cases."
	tests = tests.group_by{|t| t[:class] }

	$stderr.puts tests.map{|class_name, class_tests| "* #{class_name}: #{class_tests.length}" }
	puts JSON.pretty_generate tests
end
