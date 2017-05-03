<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.05.2017
 * Time: 20:19
 */

namespace Asian\RequestApiBundle\Model\Adapter;


interface Adapter
{
	function getUser();

	function checkUser();
}