<?php
/**
 * Fast - A PHP5.4+ API Micro Framework
 *
 * @author 		Kaleb Heitzman <kalebheitzman@gmail.com>
 * @copyright 2015 Kaleb Heitzman
 * @link 			https://github.com/kalebheitzman/fast
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

trait Database {

	/**
	 * Mongo DB Connection
	 * @var resource
	 */
	static protected $mongo;

	/**
	 * Initialize MongoDb
	 */
	static private function dbInit()
	{
		// get db connection info
		$host = self::$config['mongo']['host'];
		$port = self::$config['mongo']['port'];
		$database = self::$config['mongo']['name'];
		// build the connection string
		$connection = sprintf('mongodb://%s:%d/%s', $host, $port, $database);
		// attempt to connect to mongodb
		try {
			$db = new \MongoClient($connection);
			self::$mongo = $db->$database;
		// catch connection errors
		} catch ( \MongoConnectionException $e ) {
			Fast::response( 503, 'Could not connect to database' );
		}

	}

	/**
	 * Mongo Connection
	 * @return resource Mongo DB Connection
	 */
	static public function mongo()
	{
		return self::$mongo;
	}

}
