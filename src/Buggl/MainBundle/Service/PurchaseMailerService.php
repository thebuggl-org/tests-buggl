<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Event\Mail\PurchaseEvent;

/**
 * Service used to mail after purchase of guide
 *
 * @author vftaboada <farly.taboada@goabroad.com>
 * @copyright 2013 July 17 (c) Buggl.com
 */
class PurchaseMailerService
{
	/**
     * builds the message instance (swift message)
     * @var Buggl\MainBundle\Service\MessageBuilder
     */
    private $messageBuilder;

    /**
     * used to dispatche event
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * constructor
     * @param MessageBuilder $messageBuilder []
     * @param EventDispatcher $dispatcher    []
     */
	public function __construct($messageBuilder, $dispatcher)
	{
		$this->messageBuilder = $messageBuilder;
		$this->dispatcher = $dispatcher;
	}

	/**
	 * invoked after purchasing the guides. sends the email
	 * @param  PurchaseInfo $purchaseInfo []
	 *
	 * @return null
	 */
	public function sendMail($purchaseInfo)
	{
		$event = new PurchaseEvent($this->messageBuilder,$purchaseInfo);
        // $this->get('event_dispatcher')->dispatch('buggl.approve_eguide',$event);
        $this->dispatcher->dispatch('buggl.purchase',$event);
        $this->dispatcher->dispatch('buggl.notify_buyer',$event->buildMessageForBuyer());
	}
    public function sendItineraryMail($purchaseInfo)
    {
        $event = new PurchaseEvent($this->messageBuilder,$purchaseInfo);
        // $this->get('event_dispatcher')->dispatch('buggl.approve_eguide',$event);
        $this->dispatcher->dispatch('buggl.purchase',$event);
        $this->dispatcher->dispatch('buggl.notify_buyer',$event->buildMessageForTraveler());
    }
    public function sendItineraryMailToAdmin($purchaseInfo)
    {
        $event = new PurchaseEvent($this->messageBuilder,$purchaseInfo);
        // $this->get('event_dispatcher')->dispatch('buggl.approve_eguide',$event);
       // $this->dispatcher->dispatch('buggl.purchase',$event);
        $this->dispatcher->dispatch('buggl.notify_buyer',$event->buildMessageForAdmin());
    }
}
