<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 15.01.2017
 * Time: 11:41
 */

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
	 * @ORM\JoinTable(name="fos_user_user_group",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 * )
	 */
    protected $groups;

	/**
	 * User constructor.
	 */
    public function __construct()
    {
        parent::__construct();
    }
}