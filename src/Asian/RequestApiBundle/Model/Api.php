<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.04.2017
 * Time: 20:40
 */

namespace Asian\RequestApiBundle\Model;

use Unirest;
abstract class Api
{
	/**
	 * send get request
	 *
	 * @param $url
	 * @param $headers
	 * @param $params
	 * @return mixed
	 */
	public static function sendGetRequest($url, $headers, $params = '')
	{
		$response = Unirest\Request::get($url, $headers, $params);

		static::checkResponse($response);

		return $response->body;
	}

	/**
	 * send post request
	 *
	 * @param $url
	 * @param $headers
	 * @param $body
	 * @return mixed
	 */
	public static function sendPostRequest($url, $headers, $body)
	{
		$response = Unirest\Request::post($url, $headers, $body);

		static::checkResponse($response);

		return $response->body;
	}

	abstract static function checkResponse($response);
}