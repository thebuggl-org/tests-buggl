<?php

namespace Proxies\__CG__\Buggl\MainBundle\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class LocalAuthor extends \Buggl\MainBundle\Entity\LocalAuthor implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setFirstName($firstName)
    {
        $this->__load();
        return parent::setFirstName($firstName);
    }

    public function getFirstName()
    {
        $this->__load();
        return parent::getFirstName();
    }

    public function setLastName($lastName)
    {
        $this->__load();
        return parent::setLastName($lastName);
    }

    public function getLastName()
    {
        $this->__load();
        return parent::getLastName();
    }

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
    }

    public function getEmailVerified()
    {
        $this->__load();
        return parent::getEmailVerified();
    }

    public function setEmailVerified($emailVerified)
    {
        $this->__load();
        return parent::setEmailVerified($emailVerified);
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setPassword($password)
    {
        $this->__load();
        return parent::setPassword($password);
    }

    public function getPassword()
    {
        $this->__load();
        return parent::getPassword();
    }

    public function setSlug($slug)
    {
        $this->__load();
        return parent::setSlug($slug);
    }

    public function getSlug()
    {
        $this->__load();
        return parent::getSlug();
    }

    public function setDateJoined($dateJoined)
    {
        $this->__load();
        return parent::setDateJoined($dateJoined);
    }

    public function getDateJoined()
    {
        $this->__load();
        return parent::getDateJoined();
    }

    public function setStatus($status)
    {
        $this->__load();
        return parent::setStatus($status);
    }

    public function getStatus()
    {
        $this->__load();
        return parent::getStatus();
    }

    public function getIsLocalAuthor()
    {
        $this->__load();
        return parent::getIsLocalAuthor();
    }

    public function setIsLocalAuthor($isLocalAuthor)
    {
        $this->__load();
        return parent::setIsLocalAuthor($isLocalAuthor);
    }

    public function getIsBetaParticipant()
    {
        $this->__load();
        return parent::getIsBetaParticipant();
    }

    public function setIsBetaParticipant($isBetaParticipant)
    {
        $this->__load();
        return parent::setIsBetaParticipant($isBetaParticipant);
    }

    public function getIsApproved()
    {
        $this->__load();
        return parent::getIsApproved();
    }

    public function setIsApproved($isApproved)
    {
        $this->__load();
        return parent::setIsApproved($isApproved);
    }

    public function getUsername()
    {
        $this->__load();
        return parent::getUsername();
    }

    public function getSalt()
    {
        $this->__load();
        return parent::getSalt();
    }

    public function getRoles()
    {
        $this->__load();
        return parent::getRoles();
    }

    public function eraseCredentials()
    {
        $this->__load();
        return parent::eraseCredentials();
    }

    public function getName($properize = false)
    {
        $this->__load();
        return parent::getName($properize);
    }

    public function setProfile(\Buggl\MainBundle\Entity\Profile $profile = NULL)
    {
        $this->__load();
        return parent::setProfile($profile);
    }

    public function getProfile()
    {
        $this->__load();
        return parent::getProfile();
    }

    public function setLocation(\Buggl\MainBundle\Entity\Location $location = NULL)
    {
        $this->__load();
        return parent::setLocation($location);
    }

    public function getLocation()
    {
        $this->__load();
        return parent::getLocation();
    }

    public function setStreetCredit($streetCredit)
    {
        $this->__load();
        return parent::setStreetCredit($streetCredit);
    }

    public function getStreetCredit()
    {
        $this->__load();
        return parent::getStreetCredit();
    }

    public function getFireWall()
    {
        $this->__load();
        return parent::getFireWall();
    }

    public function isAccountNonExpired()
    {
        $this->__load();
        return parent::isAccountNonExpired();
    }

    public function isAccountNonLocked()
    {
        $this->__load();
        return parent::isAccountNonLocked();
    }

    public function isCredentialsNonExpired()
    {
        $this->__load();
        return parent::isCredentialsNonExpired();
    }

    public function isEnabled()
    {
        $this->__load();
        return parent::isEnabled();
    }

    public function getShortUrl()
    {
        $this->__load();
        return parent::getShortUrl();
    }

    public function setShortUrl($ShortUrl)
    {
        $this->__load();
        return parent::setShortUrl($ShortUrl);
    }

    public function getEguideRequest()
    {
        $this->__load();
        return parent::getEguideRequest();
    }

    public function setEguideRequest($eguideRequest)
    {
        $this->__load();
        return parent::setEguideRequest($eguideRequest);
    }

    public function setLastActive($lastActive)
    {
        $this->__load();
        return parent::setLastActive($lastActive);
    }

    public function getLastActive()
    {
        $this->__load();
        return parent::getLastActive();
    }

    public function setLastInactiveNotificationSent($lastInactiveNotificationSent)
    {
        $this->__load();
        return parent::setLastInactiveNotificationSent($lastInactiveNotificationSent);
    }

    public function getLastInactiveNotificationSent()
    {
        $this->__load();
        return parent::getLastInactiveNotificationSent();
    }


    public function __sleep()
    {
        return array_merge(array('__isInitialized__'), parent::__sleep());
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}