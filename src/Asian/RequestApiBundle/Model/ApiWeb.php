<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.04.2017
 * Time: 20:15
 */

namespace Asian\RequestApiBundle\Model;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiWeb extends Api
{
	public static function checkResponse($response)
	{
		if ($response->code < 200 || $response->code >= 400) {
			throw new HttpException($response->code, 'Request error');
		}
	}
}