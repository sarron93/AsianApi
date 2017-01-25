<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.01.2017
 * Time: 8:47
 */

namespace Asian\UserBundle\Helper;

use Asian\UserBundle\Entity\User;

class Data
{
	/**
	 * generation Token
	 *
	 * @param User $user
	 * @param $password
	 * @param $timestamp
	 * @return string
	 */
	public function generateToken(User $user, $password,$timestamp)
	{
		return sha1($timestamp . $user->getUsername()
					. $timestamp . $password);
	}
}