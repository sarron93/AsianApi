<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 15.01.2017
 * Time: 11:41
 */

namespace Asian\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Asian\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $token;

	/**
	 * User constructor.
	 */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    public function isPasswordValid($password, $factory)
    {
    	$encoder = $factory->getEncoder($this);

    	return $encoder->isPasswordValid($this->getPassword(),$password, $this->getSalt());
    }
}
