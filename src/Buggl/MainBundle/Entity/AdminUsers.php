<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Buggl\MainBundle\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
/**
 * AdminUsers
 *
 * @ORM\Table(name="admin_users")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\AdminUsersRepository")
 * @UniqueEntity(fields = "email", message="Email Address is already used.")
 */
class AdminUsers extends User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=false)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="role", type="integer", nullable=false)
     */
    private $role;



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
     * @return AdminUsers
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
    public function getUsername($credentials = false)
    {
        if(!$credentials){
            return $this->email;
        }

        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return AdminUsers
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
     * Set email
     *
     * @param string $email
     * @return AdminUsers
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set role
     *
     * @param integer $role
     * @return AdminUsers
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return integer
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return "";
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        if($this->role == 1){
            $role = array('ROLE_SUPER_ADMIN');
        }
        else if($this->role == 2){
            $role = array('ROLE_ADMIN');
        }
        else{
            $role = array('');
        }

        return $role;
    }

	public function getName()
	{
		return $this->username;
	}

    /**
     * Erase credentials
     *
     */
    public function eraseCredentials()
    {
    }

	public function getFireWall()
	{
		return 'admin_area';
	}
}