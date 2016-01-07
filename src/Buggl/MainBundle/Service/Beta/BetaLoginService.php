<?php

namespace Buggl\MainBundle\Service\Beta;

/**
 * BetaLoginService
 */
class BetaLoginService
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Constructor
     * @param Doctrine\ORM\EntityManager                       $entityManager []
     * @param Symfony\Component\HttpFoundation\Session\Session $session       []
     * @param Buggl\MainBundle\Helper\BugglConstant            $constants     []
     */
    public function __construct($entityManager, $session, $constants)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->constants = $constants;
        $this->exists = false;
    }

    /**
     * validates users if invited to use the beta site
     * @param string $email
     * @param string $token
     *
     * @return self
     */
    public function authenticate($email,$token)
    {
        $invite = $this->entityManager
                       ->getRepository('BugglMainBundle:BetaInvite')
                       ->retrieveByEmailAndToken($email, $token);

        $this->allowed = !is_null($invite);



        if ($this->allowed) {
            $name = $this->constants->get('buggl_beta_authenticated');
            $this->session->set($name, true);

            $this->session->set('beta_invite_email',$email);
            $this->session->set('beta_invite_token',$token);
			
			if($invite->getStatus() == $this->constants->get('BETA_INVITE_PENDING')){
				$invite->setStatus($this->constants->get('BETA_INVITE_ACCEPTED'));
				$this->entityManager->flush();
			}
        }

        return $this;
    }

    /**
     * If user was invited returns true else otherwise
     * @return boolean
     */
    public function isAllowed()
    {
        return $this->allowed;
    }
}