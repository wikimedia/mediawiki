<?php

namespace MediaWiki\Rest\PathTemplateMatcher;

/**
 * A tree-based path routing algorithm.
 *
 * This container builds defined routing templates into a tree, allowing
 * paths to be efficiently matched against all templates. The match time is
 * independent of the number of registered path templates.
 *
 * Efficient matching comes at the cost of a potentially significant setup time.
 * We measured ~10ms for 1000 templates. Using getCacheData() and
 * newFromCache(), this setup time may be amortized over multiple requests.
 */
class PathMatcher {
	/**
	 * An array of trees indexed by the number of path components in the input.
	 *
	 * A tree node consists of an associative array in which the key is a match
	 * specifier string, and the value is another node. A leaf node, which is
	 * identifiable by its fixed depth in the tree, consists of an associative
	 * array with the following keys:
	 *   - template: The path template string
	 *   - paramNames: A list of parameter names extracted from the template
	 *   - userData: The user data supplied to add()
	 *
	 * A match specifier string may be either "*", which matches any path
	 * component, or a literal string prefixed with "=", which matches the
	 * specified deprefixed string literal.
	 *
	 * @var array
	 */
	private $treesByLength = [];

	/**
	 * Create a PathMatcher from cache data
	 *
	 * @param array $data The data array previously returned by getCacheData()
	 * @return PathMatcher
	 */
	public static function newFromCache( $data ) {
		$matcher = new self;
		$matcher->treesByLength = $data;
		return $matcher;
	}

	/**
	 * Get a data array for later use by newFromCache().
	 *
	 * The internal format is private to PathMatcher, but note that it includes
	 * any data passed as $userData to add(). The array returned will be
	 * serializable as long as all $userData values are serializable.
	 *
	 * @return array
	 */
	public function getCacheData() {
		return $this->treesByLength;
	}

	/**
	 * Determine whether a path template component is a parameter
	 *
	 * @param string $part
	 * @return bool
	 */
	private function isParam( $part ) {
		$partLength = strlen( $part );
		return $partLength > 2 && $part[0] === '{' && $part[$partLength - 1] === '}';
	}

	/**
	 * If a path template component is a parameter, return the parameter name.
	 * Otherwise, return false.
	 *
	 * @param string $part
	 * @return string|false
	 */
	private function getParamName( $part ) {
		if ( $this->isParam( $part ) ) {
			return substr( $part, 1, -1 );
		} else {
			return false;
		}
	}

	/**
	 * Recursively search the match tree, checking whether the proposed path
	 * template, passed as an array of component parts, can be added to the
	 * matcher without ambiguity.
	 *
	 * Ambiguity means that a path exists which matches multiple templates.
	 *
	 * The function calls itself recursively, incrementing $index so as to
	 * ignore a prefix of the input, in order to check deeper parts of the
	 * match tree.
	 *
	 * If a conflict is discovered, the conflicting leaf node is returned.
	 * Otherwise, false is returned.
	 *
	 * @param array $node The tree node to check against
	 * @param string[] $parts The array of path template parts
	 * @param int $index The current index into $parts
	 * @return array|false
	 */
	private function findConflict( $node, $parts, $index = 0 ) {
		if ( $index >= count( $parts ) ) {
			// If we reached the leaf node then a conflict is detected
			return $node;
		}
		$part = $parts[$index];
		$result = false;
		if ( $this->isParam( $part ) ) {
			foreach ( $node as $key => $childNode ) {
				$result = $this->findConflict( $childNode, $parts, $index + 1 );
				if ( $result !== false ) {
					break;
				}
			}
		} else {
			if ( isset( $node["=$part"] ) ) {
				$result = $this->findConflict( $node["=$part"], $parts, $index + 1 );
			}
			if ( $result === false && isset( $node['*'] ) ) {
				$result = $this->findConflict( $node['*'], $parts, $index + 1 );
			}
		}
		return $result;
	}

	/**
	 * Add a template to the matcher.
	 *
	 * The path template consists of components separated by "/". Each component
	 * may be either a parameter of the form {paramName}, or a literal string.
	 * A parameter matches any input path component, whereas a literal string
	 * matches itself.
	 *
	 * Path templates must not conflict with each other, that is, any input
	 * path must match at most one path template. If a path template conflicts
	 * with another already registered, this function throws a PathConflict
	 * exception.
	 *
	 * @param string $template The path template
	 * @param mixed $userData User data used to identify the matched route to
	 *   the caller of match()
	 * @throws PathConflict
	 */
	public function add( $template, $userData ) {
		$parts = explode( '/', $template );
		$length = count( $parts );
		if ( !isset( $this->treesByLength[$length] ) ) {
			$this->treesByLength[$length] = [];
		}
		$tree =& $this->treesByLength[$length];
		$conflict = $this->findConflict( $tree, $parts );
		if ( $conflict !== false ) {
			throw new PathConflict( $template, $userData, $conflict );
		}

		$params = [];
		foreach ( $parts as $index => $part ) {
			$paramName = $this->getParamName( $part );
			if ( $paramName !== false ) {
				$params[] = $paramName;
				$key = '*';
			} else {
				$key = "=$part";
			}
			if ( $index === $length - 1 ) {
				$tree[$key] = [
					'template' => $template,
					'paramNames' => $params,
					'userData' => $userData
				];
			} elseif ( !isset( $tree[$key] ) ) {
				$tree[$key] = [];
			}
			$tree =& $tree[$key];
		}
	}

	/**
	 * Match a path against the current match trees.
	 *
	 * If the path matches a previously added path template, an array will be
	 * returned with the following keys:
	 *   - params: An array mapping parameter names to their detected values
	 *   - userData: The user data passed to add(), which identifies the route
	 *
	 * If the path does not match any template, false is returned.
	 *
	 * @param string $path
	 * @return array|false
	 */
	public function match( $path ) {
		$parts = explode( '/', $path );
		$length = count( $parts );
		if ( !isset( $this->treesByLength[$length] ) ) {
			return false;
		}
		$node = $this->treesByLength[$length];

		$paramValues = [];
		foreach ( $parts as $part ) {
			if ( isset( $node["=$part"] ) ) {
				$node = $node["=$part"];
			} elseif ( isset( $node['*'] ) ) {
				$node = $node['*'];
				$paramValues[] = $part;
			} else {
				return false;
			}
		}

		return [
			'params' => array_combine( $node['paramNames'], $paramValues ),
			'userData' => $node['userData']
		];
	}
}
