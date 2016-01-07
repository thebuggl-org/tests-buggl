<?php

namespace Buggl\MainBundle\Twig;

class ActivityHelperExtension extends \Twig_Extension
{
	private $constants;
	private $entityManager;
	private $router;
	
	private $loggedInUser = null;
	
	private $placeholderInfo = array(
								1 => array(
									'actor' => array(
										'{actor.name}' => 'getActorNameHTML'
									),
									'eguide' => array(
										'{guide.title}' => 'getEguideTitleHTML'
									)
								),
								2 => array(
									'actor' => array(
										'{actor.possesive_name}' => 'getActorPossesiveNameHTML',
									),
									'eguide' => array(
										'{guide.title}' => 'getEguideTitleHTML'
									)
								),
								3 => array(
									'actor' => array(
										'{actor.name}' => 'getActorNameHTML',
									),
									'purchaseInfo' => array(
										'{purchase_info.guide.title}' => 'getEguideTitleHTML'
									),
									'receiver' => array(
										'{receiver.possesive_name}' => 'getReceiverPossesiveNameHTML'
									)
								),
								4 => array(
									'actor' => array(
										'{actor.name}' => 'getActorNameHTML',
									),
									'receiver' => array(
										'{receiver.name}' => 'getReceiverNameHTML'
									)
								),
								5 => array(
									'actor' => array(
										'{actor.name}' => 'getActorNameHTML',
									),
									'receiver' => array(
										'{receiver.possesive_name}' => 'getReceiverPossesiveNameHTML'
									),
									'eguide' => array(
										'{guide.title}' => 'getEguideTitleHTML'
									)
								),
								6 => array(
									'actor' => array(
										'{actor.name}' => 'getActorNameHTML'
									),
									'eguide' => array(
										'{guide.title}' => 'getEguideTitleHTML'
									)
								),
								7 => array(
									'actor' => array(
										'{actor.name}' => 'getActorNameHTML'
									),
									'eguide' => array(
										'{guide.plain_title}' => 'getEguideTitle'
									)
								),
							);
	
	public function __construct($entityManager, $constants, $router)
	{
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->router = $router;
	}
	
    public function getFilters()
    {
        return array(
			'prepareActivityDescription' => new \Twig_Filter_Method($this, 'prepareEguideEventDescription'),
        );
    }

	public function prepareEguideEventDescription($activity,$localAuthor)
	{
		$preparedDescription = '';
		$description = $activity->getType()->getDescription();
		$this->loggedInUser = $localAuthor;
		
		foreach($this->placeholderInfo[$this->constants->get($activity->getType()->getName())] as $objectName => $placeHolders){
			$objectGetterMethod = 'get'.ucwords($objectName);
			$object = $this->$objectGetterMethod($activity);
			if(is_null($object))
				continue;
			
			foreach($placeHolders as $placeHolder => $getterMethod){
				$description = str_replace($placeHolder,$this->$getterMethod($object),$description);
			}
		}
		
		return ucfirst($description);
	}
	
	private function getActorNameHTML($actor)
	{	
		if(!is_null($this->loggedInUser) && $this->loggedInUser->getId() == $actor->getId()){
			return 'you';
		}
		
		if($actor->getIsLocalAuthor()){
			return '<a href="'.$this->router->generate('local_author_profile',array('slug'=>$actor->getSlug())).'">'.$actor->getName().'</a>';
		}
		
		return $actor->getName();
	}
	
	public function getActorPossesiveNameHTML($actor)
	{
		if(!is_null($this->loggedInUser) && $this->loggedInUser->getId() == $actor->getId()){
			return 'your';
		}
		
		$append = in_array(substr($actor->getName(), -1), array('s','x','z')) ? "'" : "'s";
		
		if($actor->getIsLocalAuthor()){
			return "<a href='".$this->router->generate('local_author_profile',array('slug'=>$actor->getSlug()))."'>".$actor->getName().$append."</a>";
		}
		
		return $actor->getName().$append;
	}
	
	private function getReceiverNameHTML($receiver)
	{	
		if(!is_null($this->loggedInUser) && $this->loggedInUser->getId() == $receiver->getId()){
			return 'you';
		}

		if($receiver->getIsLocalAuthor()){
			return '<a href="'.$this->router->generate('local_author_profile',array('slug'=>$receiver->getSlug())).'">'.$receiver->getName().'</a>';
		}
		
		return $receiver->getName();
	}
	
	public function getReceiverPossesiveNameHTML($receiver)
	{
		if(!is_null($this->loggedInUser) && $this->loggedInUser->getId() == $receiver->getId()){
			return 'your';
		}
		
		$append = in_array(substr($receiver->getName(), -1), array('s','x','z')) ? "'" : "'s";
		
		if($receiver->getIsLocalAuthor()){
			return "<a href='".$this->router->generate('local_author_profile',array('slug'=>$receiver->getSlug()))."'>".$receiver->getName().$append."</a>";
		}
		
		return $receiver->getName().$append;
	}
	
	private function getEguideTitle($object)
	{
		if(strpos(get_class($object),'EGuide') !== false){
			$eguide = $object;
		}
		else if(strpos(get_class($object),'PurchaseInfo') !== false){
			$eguide = $object->getEguide();
		}
		
		return $eguide->getPlainTitle();
	}
	
	private function getEguideTitleHTML($object)
	{
		if(strpos(get_class($object),'EGuide') !== false){
			$eguide = $object;
		}
		else if(strpos(get_class($object),'PurchaseInfo') !== false){
			$eguide = $object->getEguide();
		}
		
		return "<a href='".$this->router->generate('buggl_eguide_overview',array('slug'=>$eguide->getSlug()))."'>".$eguide->getPlainTitle()."</a>";
	}
	
	private function getEguidePossesiveTitleHTML($object)
	{
		if(strpos(get_class($object),'EGuide') !== false){
			$eguide = $object;
		}
		else if(strpos(get_class($object),'PurchaseInfo') !== false){
			$eguide = $object->getEguide();
		}
		
		$append = in_array(substr($eguide->getTitle(), -1), array('s','x','z')) ? "'" : "'s";
		
		return "<a href='".$this->router->generate('buggl_eguide_overview',array('slug'=>$eguide->getSlug()))."'>".$eguide->getPlainTitle().$append."</a>";
	}
	
	// =======================
	
	private function getActor($activity)
	{
		return $activity->getActor();
	}
	
	private function getReceiver($activity)
	{
		return $activity->getReceiver();
	}
	
	private function getEguide($activity)
	{
		$eguide = $this->entityManager->getRepository('BugglMainBundle:EGuide')->findOneById($activity->getObjectId());
		
		return $eguide;
	}
	
	private function getPurchaseInfo($activity)
	{
		// NOTE: for stripe
		// $purchaseInfo = $this->entityManager->getRepository('BugglMainBundle:PurchaseInfo')->findOneById($activity->getObjectId());
		$purchaseInfo = $this->entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneById($activity->getObjectId());
		
		return $purchaseInfo;
	}

    public function getName()
    {
        return 'buggl_activity_helper_extension';
    }
}