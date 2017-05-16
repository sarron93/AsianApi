<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.04.2017
 * Time: 20:15
 */

namespace Asian\RequestApiBundle\Model;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Unirest\Exception;

class ApiWeb extends Api
{
	public static function checkResponse($response)
	{
		if ($response->code < 200 || $response->code >= 400) {
			throw new HttpException($response->code, 'Request error');
		}

		if ($response->body->Code < 0) {
			throw new Exception(var_export($response, true));
		}
	}
}