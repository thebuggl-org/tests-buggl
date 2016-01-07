<?php

namespace Buggl\MainBundle\Service\SEO;


class StaticPageService
{
	const TITLE = 'title';
	const DESC  = 'description';

	private $homeTitle = 'The Insiders Guide to Travel | Buggl';
	private $homeDescription = 'An Insiders guide to travel. A collection of low cost, high quality travel guides written for those interested in experiencing a unique way of traveling';

	private $attributes = array(
		'jobs' => array(
			self::TITLE => 'Jobs at Buggl.com | Buggl',
			self::DESC  => 'Want to contribute to Buggl\'s rise to the top? Send us your resume and become part of the Buggl Team.' 
			),
		'press' => array(
			self::TITLE => 'Press & Media | Buggl',
			self::DESC  => 'Want to feature Buggl.com? There are plenty of reasons you should. Learn how to access our founders, media relations team, and spread the word!' 
			),
		'faq' => array(
			self::TITLE => 'Frequently Asked Questions | Buggl',
			self::DESC  => 'Frequently asked questions about how to use Buggl.com, becoming an Insider, and find unique ways to travel.' 
			),
		'terms-of-use' => array(
			self::TITLE => 'Terms & Conditions | Buggl',
			self::DESC  => 'The fine print of Buggl.com. Don\'t worry, its the typical legal jargon noone has time to read.' 
			),
		'contact-us' => array(
			self::TITLE => 'Contact Us | Buggl',
			self::DESC  => 'Need some additional help? Have a question specific question about Buggl? Just want to say hi? Send us a message through our Contact Form.' 
			),
		'our-tribe' => array(
			self::TITLE => 'Our Tribe | Buggl',
			self::DESC  => 'Our Tribe consists of travel experts and industry leaders: Derek Bugley, Richard Walton, and Troy Peden.' 
			),
		'our-mission' => array(
			self::TITLE => 'Our Mission | Buggl',
			self::DESC  => 'Every company has a mission. Our mission is to share unique ways to travel and earn travel cash by sharing your experiences. Become an Insider.' 
			),
		'privacy-policy' => array(
			self::TITLE => 'Privacy Policy | Buggl',
			self::DESC  => 'You are a private person, we understand. Here is how we handle privacy and security on Buggl.com.' 
			),
		'become-an-expert' => array(
			self::TITLE => 'Become an Insider | Buggl',
			self::DESC  => 'Become an Insider and earn an income by sharing your travel knowledge, about the city you live in or a place you love to travel to, and all the activities that make travel fun!' 
			),
		'how-it-works' => array(
			self::TITLE => 'How it Works | Buggl',
			self::DESC  => 'Buggl works through a collective effort. Insiders provide in-depth travel knowledge and travelers wanting insider tips to guide their trip.' 
			),
		'registration' => array(
			self::TITLE => 'Buggl User Registration',
			self::DESC  => 'Register as a Buggl.com Travel Insider! Sign up now and you\'ll be on your way to generating an income from sharing your amazing travel experiences.' 
			),
		);

	public function __construct(){}

	public function buildMetaAttributes($slug)
	{
		return $this->meta = array(
			self::TITLE => $this->generateTitle($slug),
			self::DESC => $this->generateMetaDescription($slug)
			);
	}

	private function generateTitle($slug)
	{
		return isset( $this->attributes[ $slug ] ) ? 
			$this->attributes[ $slug ] [ self::TITLE ]
			: $this->homeTitle;
	}

	private function generateMetaDescription($slug)
	{
		return isset( $this->attributes[ $slug ] ) ? 
			$this->attributes[ $slug ] [ self::DESC ]
			: $this->homeDescription;
	}

}