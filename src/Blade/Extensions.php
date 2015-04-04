<?php
namespace Xety\Cake3Blade\Blade;

use Illuminate\View\Compilers\BladeCompiler;

class Extensions {

/**
 * The Blade compiler.
 *
 * @var \Illuminate\View\Compilers\BladeCompiler
 */
	protected $compiler;

/**
 * The loaded view helpers.
 *
 * @var array
 */
	protected $helpers = [];

/**
 * Constructor.
 *
 * @param \Illuminate\View\Compilers\BladeCompiler $compiler The Blade compiler.
 * @param array $helpers Helpers used in the controllers.
 */
	public function __construct(BladeCompiler $compiler, $helpers = []) {
		$this->compiler = $compiler;

		$this->helpers = $helpers;

		$this->_parse();

	}

/**
 * Parse the view by getting all methods of this class.
 *
 * @return void
 */
	protected function _parse() {
		$methods = get_class_methods($this);
		foreach ($methods as $method) {
			if (substr($method, 0, 8) === "_compile") {
				$this->{$method}();
			}
		}
	}

	/**
	 * Turn @html->css() into $this->Html->css().
	 * This only works if the helper is loaded from a controller. Need to see about getting all attached helpers.
	 */
	protected function _compileHelpers() {
		foreach($this->helpers as $properties) {
			$this->compiler->extend(function($view) use ($properties) {
				$pattern = '/(?<!\w)(\s*)@' . strtolower($properties['class']) . '\-\>((?:[a-z][a-z]+))(\s*\(.*\))/';
				return preg_replace($pattern, '$1<?php echo $_view->' . $properties['class'] . '->$2$3; ?>', $view);
			});
		}
	}

	/**
	 * Turn @fetch() into $this->fetch().
	 */
	protected function _compileFetch() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('fetch');
			return preg_replace($pattern, '$1<?php echo $_view->fetch$2; ?>', $view);
		});
	}

	/**
	 * Turn @start() into $this->start().
	 */
	protected function _compileStart() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('start');
			return preg_replace($pattern, '$1<?php echo $_view->start$2; ?>', $view);
		});
	}

	/**
	 * Turn @append() into $this->append().
	 */
	protected function _compileAppend() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('append');
			return preg_replace($pattern, '$1<?php echo $_view->append$2; ?>', $view);
		});
	}

	/**
	 * Turn @prepend() into $this->prepend().
	 */
	protected function _compilePrepend() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('prepend');
			return preg_replace($pattern, '$1<?php echo $_view->prepend$2; ?>', $view);
		});
	}

	/**
	 * Turn @assign() into $this->assign().
	 */
	protected function _compileAssign() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('assign');
			return preg_replace($pattern, '$1<?php echo $_view->assign$2; ?>', $view);
		});
	}

	/**
	 * Turn @end() into $this->end().
	 */
	protected function _compileEnd() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('end');
			return preg_replace($pattern, '$1<?php echo $_view->end$2; ?>', $view);
		});
	}

	/**
	 * Turn @element() into $this->element().
	 */
	protected function _compileElement() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('element');
			return preg_replace($pattern, '$1<?php echo $_view->element$2; ?>', $view);
		});
	}

	/**
	 * Turn @cell() into $this->cell().
	 */
	protected function _compileCell() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('cell');
			return preg_replace($pattern, '$1<?php echo $_view->cell$2; ?>', $view);
		});
	}

	/**
	 * Turn @extend() into $this->extend().
	 */
	protected function _compileExtend() {
		$this->compiler->extend(function($view, $compiler) {
			$pattern = $compiler->createMatcher('extend');
			return preg_replace($pattern, '$1<?php echo $_view->extend$2; ?>', $view);
		});
	}

}