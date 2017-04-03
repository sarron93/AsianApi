<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.04.2017
 * Time: 20:39
 */

namespace Asian\RequestApiBundle\Model;
use Symfony\Component\Config\Definition\Exception\Exception;


class ApiConsole extends Api
{
	public static function checkResponse($response)
	{
		if ($response->code < 200 || $response->code >= 400) {
			throw new Exception($response->code . ' Response Error');
		}

		if ($response->body->Code < 0) {
			throw new Exception(var_export($response, true));
		}
	}
}