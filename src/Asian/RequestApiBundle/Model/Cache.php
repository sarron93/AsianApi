<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 28.03.2017
 * Time: 18:42
 */

namespace Asian\RequestApiBundle\Model;

use Memcache;

class Cache
{
	private $memcache;
	const HOST = '127.0.0.1';
	const PORT = 11211;
	const EXPIRE = 120;

	/**
	 * Cache constructor.
	 */
	public function __construct()
	{
		$this->memcache = new Memcache();
		$this->memcache->connect(self::HOST, self::PORT);
	}

	/**
	 * set param into memcache server
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function setParam($key, $value)
	{
		$param = $this->getParam($key);

		if (!$param) {
			$this->memcache->add($key, $value, false, self::EXPIRE);
		} else {
			$this->memcache->replace($key, $value, false, self::EXPIRE);
		}
	}

	/**
	 *
	 * @param $key
	 * @return array|string
	 */
	public function getParam($key)
	{
		$param = $this->memcache->get($key);
		return $param;
	}
}