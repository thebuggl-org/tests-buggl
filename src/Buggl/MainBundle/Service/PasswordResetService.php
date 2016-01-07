<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Buggl\MainBundle\Entity\PasswordResetInfo;
use Doctrine\ORM\EntityManager;

class PasswordResetService
{
	protected $entityManager;
	protected $contants;
	protected $localAuthorService;
	protected $encoder;

    public function __construct(EntityManager $entityManager, $contants, $localAuthorService)
    {
        $this->entityManager = $entityManager;
		$this->constants = $contants;
		$this->localAuthorService = $localAuthorService;

		$this->encoder = new MessageDigestPasswordEncoder();
    }

	public function savePasswordResetInfo($data)
	{
		$user = $data['user'];
		$token = substr($this->encoder->encodePassword($data['email'].' : '.$user->getId().' : '.date('Y-m-d H:i:s'),''),0,100);

		$passwordResetInfo = $this->entityManager->getRepository('BugglMainBundle:PasswordResetInfo')->findOneByUser($user);

		if(is_null($passwordResetInfo)){
			$passwordResetInfo = new PasswordResetInfo();
		}

		$passwordResetInfo->setUser($user);
		$passwordResetInfo->setToken($token);
		$passwordResetInfo->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 day"));

		$this->entityManager->persist($passwordResetInfo);
		$this->entityManager->flush();

		return $passwordResetInfo;
	}

	public function updatePassword($data,$context)
	{
		$user = $this->getUserInContext(array('email' => $data['email']),$context);
		if($context == $this->constants->get('SITE_ADMIN')){
			// TO be implemented
		}
		else if($context == $this->constants->get('LOCAL_AUTHOR')){
			$user->setPassword($data['password']);
			$user = $this->localAuthorService->updateLocalAuthor($user);
		}

		return $user;
	}

	/*
	 * context is contant value from BugglConstants
	 */
	public function getUserInContext($criteria, $context)
	{
		$user = null;
		if($context == $this->constants->get('SITE_ADMIN')){
			$user = $this->entityManager->getRepository('BugglMainBundle:AdminUsers')->findOneBy($criteria);
		}
		else if($context == $this->constants->get('LOCAL_AUTHOR')){
			$user = $this->entityManager->getRepository('BugglMainBundle:LocalAuthor')->findOneBy($criteria);
		}

		return $user;
	}

	/*
	 * TODO: change return to passwordResetInfo when valid
	 */
	public function validateToken($token)
	{
		$valid = false;
		$passwordResetInfo = $this->entityManager->getRepository('BugglMainBundle:PasswordResetInfo')->findOneBy(array('token' => $token));
		if(!is_null($passwordResetInfo) and $token != ''){
			$valid = strtotime($passwordResetInfo->getTokenExpiration()->format('Y-m-d H:i:s')) >= strtotime(date('Y-m-d H:i:s'));
		}

		return $valid;
	}

	public function matchEmails($token, $email)
	{
		$match = false;
		$passwordResetInfo = $this->entityManager->getRepository('BugglMainBundle:PasswordResetInfo')->findOneBy(array('token' => $token));
		if(!is_null($passwordResetInfo)){
			$user = $passwordResetInfo->getUser();
			$match = $user->getEmail() == $email;
		}

		return $match;
	}

	public function invalidatePasswordResetInfo($token)
	{
		$passwordResetInfo = $this->entityManager->getRepository('BugglMainBundle:PasswordResetInfo')->findOneBy(array('token' => $token));

		if(!is_null($passwordResetInfo)){
			$passwordResetInfo->setToken('');
			$this->entityManager->persist($passwordResetInfo);
			$this->entityManager->flush();
		}

		return $passwordResetInfo;
	}
}