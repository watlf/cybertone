<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consumers
 *
 * @ORM\Table(name="consumers", uniqueConstraints={@ORM\UniqueConstraint(name="login", columns={"login", "email"})}, indexes={@ORM\Index(name="group_id", columns={"group_id"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Consumers")
 */
class Consumers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="account_expired", type="datetime", nullable=false)
     */
    private $accountExpired;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_extension", type="string", length=4, nullable=false)
     */
    private $avatarExtension;

    /**
     * @var \Application\Model\Entity\Groups
     *
     * @ORM\Column(name="group_id", type="integer", nullable=true)
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Groups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * })
     */
    private $groupId;

    /**
     * @param Groups $group
     */
    public function __construct(Groups $group)
    {
        $this->groupId = $group->getId();
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
     * Set login
     *
     * @param string $login
     * @return Consumers
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Consumers
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
     * @return Consumers
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
     * Set accountExpired
     *
     * @param \DateTime $accountExpired
     * @return Consumers
     */
    public function setAccountExpired($accountExpired)
    {
        $this->accountExpired = $accountExpired;

        return $this;
    }

    /**
     * Get accountExpired
     *
     * @return \DateTime 
     */
    public function getAccountExpired()
    {
        return $this->accountExpired;
    }

    /**
     * Set avatarExtension
     *
     * @param string $avatarExtension
     * @return Consumers
     */
    public function setAvatarExtension($avatarExtension)
    {
        $this->avatarExtension = $avatarExtension;

        return $this;
    }

    /**
     * Get avatarExtension
     *
     * @return string 
     */
    public function getAvatarExtension()
    {
        return $this->avatarExtension;
    }

    /**
     * Set groupId
     *
     * @param Groups $group
     * @return Consumers
     */
    public function setGroup(Groups $group = null)
    {
        $this->groupId = $group;

        return $this;
    }

    /**
     * Get groupId
     *
     * @return \Application\Model\Entity\Groups 
     */
    public function getGroup()
    {
        return $this->groupId;
    }
}
