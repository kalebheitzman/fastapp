<?php
/**
 * Fast - A PHP5.4+ API Micro Framework
 *
 * @author 		Kaleb Heitzman <kalebheitzman@gmail.com>
 * @copyright 	2015 Kaleb Heitzman
 * @link 		https://github.com/kalebheitzman/fast
 * @license 	https://github.com/kalebheitzman/fast/blob/master/LICENSE
 * @version 	0.1.0
 * @package  	Fast
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Fast;

/**
 * Fast
 *
 * Fast is an API Framework with a RESTful HTTP router.
 *
 * @package Fast
 * @author  Kaleb Heitzman <kalebheitzman@gmail.com>
 * @since  0.1.0
 */

trait Stack {

	/**
	 * @var array Stack
	 */
	static protected $stack;

	static private function stackInit()
	{
		self::$engine->stack = array();
	}

	/**
	 *	Run the actions list
	 */
	static private function runStack() {
		// sort the stack
		ksort( self::$engine->stack );
		// run each action in the stack
		foreach(self::$engine->stack as $key => $actions ) {
			foreach( $actions as $action ) {

				$response_key = key(self::$engine->stack[$key]);
				if ( $response_key == 'route' ) $response_key = 'data';

				$cb = $action['cb'];
				$args = isset( $action['params'] ) ? $action['params'] : array();
				$results = call_user_func_array($cb, $args);

				// add the data to the response
				if ( ! is_null( $results ) && is_array( $results) ) {
					foreach ( $results as $key => $result ) {
						self::$engine->response[$key] = $result;
					}
				}

			}
		}

		self::response();
	}

	/**
	 *	Run the route
	 */
	static private function buildStack()
	{
		// add middleware to stack
		foreach( self::$engine->route['middleware'] as $key => $middleware ) {
			if( isset( self::$engine->middleware[$middleware] ) ) {
				$middleware2 = self::$engine->middleware[$middleware];
				self::$engine->stack[$middleware2['position']][$middleware]['cb'] = $middleware2['cb'];
			}
		}

		// add route to stack
		$route = self::$engine->route;
		unset( $route['middleware'] );
		self::$engine->stack[self::$config['route_position']]['route'] = $route;

	}

}
