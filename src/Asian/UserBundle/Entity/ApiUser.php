<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 26.01.2017
 * Time: 9:52
 */

namespace Asian\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Asian\UserBundle\Repository\ApiUserRepository")
 * @ORM\Table(name="api_user")
 */
class ApiUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=25)
	 */
	protected $username;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $password;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $AOKey;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $AOToken;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $url;

	/**
	 * @ORM\OneToMany(targetEntity="User", mappedBy="apiUser")
	 */
	private $users;

	public function __construct()
	{
		$this->users = new ArrayCollection();
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return ApiUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return ApiUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set aOKey
     *
     * @param string $aOKey
     *
     * @return ApiUser
     */
    public function setAOKey($aOKey)
    {
        $this->AOKey = $aOKey;

        return $this;
    }

    /**
     * Get aOKey
     *
     * @return string
     */
    public function getAOKey()
    {
        return $this->AOKey;
    }

    /**
     * Set aOToken
     *
     * @param string $aOToken
     *
     * @return ApiUser
     */
    public function setAOToken($aOToken)
    {
        $this->AOToken = $aOToken;

        return $this;
    }

    /**
     * Get aOToken
     *
     * @return string
     */
    public function getAOToken()
    {
        return $this->AOToken;
    }

    /**
     * Add user
     *
     * @param \Asian\UserBundle\Entity\User $user
     *
     * @return ApiUser
     */
    public function addUser(\Asian\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Asian\UserBundle\Entity\User $user
     */
    public function removeUser(\Asian\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

	/**
	 * set url
	 *
	 * @param string $url
	 * @return ApiUser
	 */
    public function setUrl($url)
    {
    	$this->url = $url;
    	return $this;
    }

	/**
	 * get api url
	 *
	 * @return string
	 */
    public function getUrl()
    {
    	return $this->url;
    }
}
