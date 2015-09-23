require 'pp'
require_relative 'docparser'

# convert [ {name: 'foo'}, … ] to { foo: {name: 'foo'}, … }
def reindex arg
	if arg.is_a?(Array) && arg.all?{|v| v.is_a? Hash }
		Hash[ arg.map{|v| [ v[:name], reindex(v) ] } ]
	elsif arg.is_a? Hash
		arg.each_pair{|k, v| arg[k] = reindex(v) }
	else
		arg
	end
end

def indent text, tabs
	text == '' ? text : text.gsub(/^/, '  ' * tabs)
end

# whitespace-insensitive strings
def canonicalize value
	if value.is_a? String
		value.strip.gsub(/\s+/, ' ')
	elsif value.is_a? Array
		value.map{|v| canonicalize v }
	elsif value.is_a? Hash
		value.each_pair{|k, v| value[k] = canonicalize v }
	else
		value
	end
end

def sanitize_description text
	cleanup_class_name(text)
		.gsub('null and undefined', 'null')
		.gsub('undefined and null', 'null')
		.gsub('array()', '[]')
		.gsub('jQuery|string|Function', 'string')
		.gsub('jQuery', 'Tag')
		.gsub('string|Function', 'string')
		.gsub('object', 'array')
		.gsub(/#(\w+)/, '\1()')
		.gsub(/Object\.<.+?>/, 'array')
end

def smart_compare_process val, type
	val[:description] = sanitize_description val[:description]

	case type
	when :class
		val = val.dup
		val[:mixins].delete 'OO.EventEmitter' # JS only
		val[:mixins].delete 'PendingElement' # JS only
		val.delete :parent if val[:parent] == 'ElementMixin' # PHP only
		val.delete :methods
		val.delete :properties
		val.delete :events

	when :method
		if val[:name] == '#constructor'
			val[:params].delete 'element' # PHP only
		end
		val[:config].each_pair{|_k, v|
			default = v.delete :default
			v[:description] << " (default: #{default})" if default
			v[:description] = sanitize_description v[:description]
			v[:type] = sanitize_description v[:type]
		}
		val[:params].each_pair{|_k, v|
			default = v.delete :default
			v[:description] << " (default: #{default})" if default
			v[:description] = sanitize_description v[:description]
			v[:type] = sanitize_description v[:type]
		}
		if val[:return]
			val[:return][:description] = sanitize_description val[:return][:description]
			val[:return][:type] = sanitize_description val[:return][:type]
		end

	when :property
		val[:description] = sanitize_description val[:description]
		val[:type] = sanitize_description val[:type]

	end
	val
end

def smart_compare a, b, a_name, b_name, type
	a = smart_compare_process a, type
	b = smart_compare_process b, type
	compare_hash a, b, a_name, b_name
end

def smart_compare_methods a, b, a_name, b_name
	smart_compare a, b, a_name, b_name, :method
end

def smart_compare_properties a, b, a_name, b_name
	smart_compare a, b, a_name, b_name, :property
end

def compare_hash a, b, a_name, b_name, nested=:compare_hash
	keys = (a ? a.keys : []) + (b ? b.keys : [])
	out = keys.to_a.sort.uniq.map do |key|
		a_val = a ? canonicalize(a[key]) : nil
		b_val = b ? canonicalize(b[key]) : nil

		if [a_val, b_val] == [{}, []] || [a_val, b_val] == [[], {}]
			a_val, b_val = {}, {}
		end

		if a_val.is_a?(Hash) && b_val.is_a?(Hash)
			comparison_result = indent method(nested).call(a_val, b_val, a_name, b_name), 2
			if comparison_result.strip == ''
				"#{key}: match" if $VERBOSE
			else
				"#{key}: MISMATCH\n#{comparison_result}"
			end
		else
			if a_val == b_val
				"#{key}: match" if $VERBOSE
			elsif a_val.nil?
				"#{key}: #{a_name} missing"
			elsif b_val.nil?
				"#{key}: #{b_name} missing"
			else
				"#{key}: MISMATCH\n  #{a_name}: #{a_val.inspect}\n  #{b_name}: #{b_val.inspect}"
			end
		end
	end
	out.compact.join "\n"
end

if ARGV.empty? || ARGV == ['-h'] || ARGV == ['--help']
	$stderr.puts "usage: ruby [-v] #{$PROGRAM_NAME} <dirA> <dirB> <nameA> <nameB>"
	$stderr.puts "       ruby #{$PROGRAM_NAME} src php JS PHP > compare.txt"
else
	dir_a, dir_b, name_a, name_b = ARGV

	js = parse_any_path dir_a
	php = parse_any_path dir_b

	js = reindex js
	php = reindex php

	(js.keys + php.keys).sort.uniq.each do |class_name|
		where = [js.key?(class_name) ? name_a : nil, php.key?(class_name) ? name_b : nil].compact
		puts "\n#{class_name}: #{where.join '/'}" if $VERBOSE || where.length == 2

		if where.length == 2
			data = {
				'Basic:' =>
					smart_compare(js[class_name], php[class_name], name_a, name_b, :class),
				'Methods:' =>
					compare_hash(js[class_name][:methods], php[class_name][:methods], name_a, name_b, :smart_compare_methods),
				'Properties:' =>
					compare_hash(js[class_name][:properties], php[class_name][:properties], name_a, name_b, :smart_compare_properties),
			}
			data = data
				.select{|_k, v| v != ''}
				.map{|k, v| "#{k}\n#{indent v, 2}" }
				.join("\n")
			puts indent data, 2
		end
	end
end
