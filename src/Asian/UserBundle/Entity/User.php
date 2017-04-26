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
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $token;

	/**
	 * @ORM\Column(name="last_activity", type="datetime", nullable=true)
	 */
	protected $lastActivity;

	/**
	 * @ORM\Column(name="last_activity_api", type="datetime", nullable=true)
	 */
	protected $lastActivityApi;

	/**
	 * @ORM\ManyToOne(targetEntity="ApiUser", inversedBy="users")
	 * @ORM\JoinColumn(name="api_id", referencedColumnName="id")
	 */
	protected $apiUser;
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

	/**
	 * checking user data
	 *
	 * @return bool
	 */
    public function checkUser($token)
    {
	    if (!$this->getId()) {
		    return false;
	    }
	    if ($this->getToken() != $token) {
	    	return false;
	    }
	    return true;
    }

	/**
	 * check api user
	 *
	 * @return bool
	 */
    public function isApiUser()
    {
	    if (is_null($this->getApiUser())) {
		    return false;
	    }

	    if (!$this->getApiUser()->getId()) {
		    return false;
	    }

	    return true;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     *
     * @return User
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set lastActivityApi
     *
     * @param \DateTime $lastActivityApi
     *
     * @return User
     */
    public function setLastActivityApi($lastActivityApi)
    {
        $this->lastActivityApi = $lastActivityApi;

        return $this;
    }

    /**
     * Get lastActivityApi
     *
     * @return \DateTime
     */
    public function getLastActivityApi()
    {
        return $this->lastActivityApi;
    }

    /**
     * Set apiUser
     *
     * @param \Asian\UserBundle\Entity\ApiUser $apiUser
     *
     * @return User
     */
    public function setApiUser(\Asian\UserBundle\Entity\ApiUser $apiUser = null)
    {
        $this->apiUser = $apiUser;

        return $this;
    }

    /**
     * Get apiUser
     *
     * @return \Asian\UserBundle\Entity\ApiUser
     */
    public function getApiUser()
    {
        return $this->apiUser;
    }
}
