<?php declare(strict_types=1);

use Phan\AST\ContextNode;
use Phan\CodeBase;
use Phan\Language\Context;
use Phan\Language\UnionType;
use Phan\Language\Element\TypedElement;
use Phan\Plugin\PluginImplementation;
use Phan\Language\Element\Variable;
use Phan\Exception\IssueException;
use ast\Node;

/**
 * Looks for inline type annotations for local variables.
 *
 * For example, if you have:
 *   /** @var Foo $a ./
 *   $a = new $c;
 *
 *  This will ensure that phan knows that $a has type
 *  Foo. Similarly this will also work with foreach:
 *
 *  /** @var Bar $val ./
 *  foreach( $someArrayWithUnclearType as $index => $val )
 *
 *  This will only work if the type annotation is on
 *  the line immediately before the
 *  assignment or the foreach. It will only handle simple cases.
 *
 *  Multiline doc comments are supported only if they are precisely
 *  three lines, and the interesting bit is exactly 2 lines before
 *  the variable. e.g.
 *  /**
 *   * @var Baz $a
 *   ./
 *   $a = new $d;
 */
class InlineTypePlugin extends PluginImplementation {

	/**
	 * Look for assignments to local variables.
	 *
	 * @param CodeBase $code_base
	 * The code base in which the node exists
	 *
	 * @param Context $context
	 * The context in which the node exits. This is
	 * the context inside the given node rather than
	 * the context outside of the given node
	 *
	 * @param Node $node
	 * The php-ast Node being analyzed.
	 *
	 * @param Node $node
	 * The parent node of the given node (if one exists).
	 *
	 * @return void
	 */
	public function analyzeNode(
		CodeBase $code_base,
		Context $context,
		Node $node,
		Node $parent_node = null
	) {
		if (
			!$context->isInternal() &&
			$node->kind === \ast\AST_ASSIGN &&
			is_object( $node->children['var'] ) &&
			$node->children['var']->kind === \ast\AST_VAR &&
			is_string( $node->children['var']->children['name'] ) &&
			!$this->isHardcodedGlobal( $context, $node->children['var']->children['name'] )
		) {
			$cnode = new ContextNode(
				$code_base,
				$context,
				$node
			);
			// The following can potentially throw an IssueException.
			// But that should not happen, and if it does, fataling is
			// probably a fine response.
			$elem = $cnode->getVariable();
			$this->checkType( $elem, $context );
		}
	}

	/**
	 * Foreach needs to be dealt with pre-order
	 *
	 * @param CodeBase $code_base
	 * @param Context $contex
	 * @param Node $node
	 */
	public function preAnalyzeNode(
		CodeBase $code_base,
		Context $context,
		Node $node
	) {
		$elem = null;
		if (
			$node->kind === \ast\AST_FOREACH &&
			is_object( $node->children['value'] ) &&
			$node->children['value']->kind === \ast\AST_VAR &&
			is_string( $node->children['value']->children['name'] ) &&
			!$this->isHardcodedGlobal( $context, $node->children['value']->children['name'] )
		) {
			$cnode = new ContextNode(
				$code_base,
				$context,
				$node->children['value']
			);
			// The following can potentially throw an IssueException.
			// But that should not happen, and if it does, fataling is
			// probably a fine response.
			$elem = $cnode->getVariable();
			$this->checkType( $elem, $context );
		}
	}

	/**
	 * Are we in global scope and this is a hardcoded global?
	 *
	 * If we're dealing with a hardcoded global (e.g. a builtin
	 * global or something from the config setting globals_type_map)
	 * skip it, because the other methods will think its undeclared.
	 *
	 * @param Context $context
	 * @param string $globalName
	 * @return bool
	 */
	private function isHardcodedGlobal( Context $context, $globalName ) {
		return $context->isInGlobalScope() &&
			Variable::isHardcodedGlobalVariableWithName( $globalName );
	}

	/**
	 * Given a phan TypedElement, check if there is an inline type annotation.
	 *
	 * As a side effect, this will update the type provided the element
	 * does not currently have a type and the type annotation exists.
	 *
	 * @param TypedElement $elem TypedElement (probably a Variable)
	 * @param Context $context Where the element is
	 */
	private function checkType( TypedElement $elem, Context $context ) {
		if ( $elem->getUnionType()->isEmpty() ) {
			$line = $this->getPreviousLine(
				$context->getLineNumberStart(),
				$context->getFile()
			);
			$type = $this->parseVarLine( $line, $elem->getName(), $context );
			if ( $type ) {
				$elem->setUnionType( $type );
			}
		}
	}

	/**
	 * Given an inline doc comment, parse it.
	 *
	 * This only recognizes single line doc comments e.g.
	 *   /** @var Type1|Type2|Type3 $variableName ./
	 * or multi-line that are essentially one line e.g.
	 *  /**
	 *   * @var Type1|Type2|... $variableName
	 *   ./
	 *
	 * @param string $line Line to parse
	 * @param string $varName Name of variable we are trying to find type for
	 * @param Context $context
	 * @return UnionType|false The new type from the annotation
	 */
	private function parseVarLine ( $line, $varName, Context $context ) {
		// Based on Comment::ParameterFromCommentLine
		$regex = '/^\s*(?:\\/\\*)?\\*\s+@var\s+(?<type>' . UnionType::union_type_regex . ')\s+\\$(?<varname>\S+)/m';
		$match = [];
		if ( preg_match( $regex, $line, $match) ) {
			if ( $varName === $match['varname'] ) {
				$type = UnionType::fromStringInContext( $match['type'], $context );
				if ( !$type->isEmpty() ) {
					return $type;
				}
			}
		}
		return false;
	}

	/**
	 * Get the contents of the previous 2 lines
	 *
	 * @note This intentionally does not use MW shell exec methods
	 *   in order to make this class generic and not depend on MW.
	 *
	 * @param int $lineNumber One more than line numbers to fetch
	 * @param string $file Full path to file
	 * @return string Line in question
	 */
	private function getPreviousLine ( $lineNumber, $file ) {
		// Probably not the most efficent way
		return shell_exec(
			'head ' .
			escapeshellarg( '-' . ( $lineNumber - 1 ) ) . ' ' .
			escapeshellarg( $file ) .
			' | tail -2'
		);
	}
}

// Every plugin needs to return an instance of itself at the
// end of the file in which its defined.
return new InlineTypePlugin;
