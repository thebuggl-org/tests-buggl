<?php

namespace Buggl\MainBundle\Service;

class EnvironmentVariablesService
{

	private $localVars = array(
		'facebookAppId' => '414889558583867',
		'facebookAppSecret' => 'ed21c7177731c718ebf5fcd157359c7e',

		'yahooMailConsumerKey' => 'dj0yJmk9TnF5aTBwNWo0WnpoJmQ9WVdrOVVtbGhRbWRxTnpRbWNHbzlNVGMyTkRFME1qWXkmcz1jb25zdW1lcnNlY3JldCZ4PTI1',
		'yahooMailConsumerSecret' => 'b5773a0001ad49fec6656b59fb49d39d207431ff',

		'googleMapsApiKey' => 'AIzaSyBbY4GN6gYS7dHtNjf4NPgbAilusybb5W4',

		'googleClientId' => '464369053826.apps.googleusercontent.com',
		'googleClientSecret' => 'n0G7bPNN4KMx5pQ_4M8GPlaj',

		'twitterConsumerKey' => 'oOhNUmYIVMcfsOpfFkoCQ',
		'twitterConsumerSecret' => 'bRlE4frmqLFTSyS9fmvnzC1N3mijQ8duqpwKcG1bQ',

		'stripe_secret_key'	=> 'sk_test_EBarPJ4q4zy0YB0899u5D4zM',
		'stripe_publishable_key' => 'pk_test_h74lAeRODkSkP6OtYUQTYEUs',
		'stripe_client_id' => 'ca_1cfhHeb08JTkMZw9p7cYrXl26WdlOK3i',
		'stripe_application_fee' => 10,
		
		'paypal_security_user_id' => 'noel.bacarisas-facilitator_api1.gmail.com',
		'paypal_security_paswword' => '1380522436',
		'paypal_security_signature' => 'AJiW4p9fpWOi42UIzLI8BotSVvwaA2Sz.lDVq7tXt9OROhGHUyBTCWbg',
		'paypal_app_id' => 'APP-80W284485P519543T',
		'paypal_account_email' => 'noel.bacarisas-facilitator@gmail.com',
		'use_paypal_sandbox' => true,
		
		/*'paypal_security_user_id' => 'derek_api1.buggl.com',
		'paypal_security_paswword' => 'UUWFNEN7M4FVGBK8',
		'paypal_security_signature' => 'AB6-BjQztXJcpiQ114XqUwMjpOW7AGy3Cgis5CV.WZKB.6CEiElnavAp',
		'paypal_app_id' => 'APP-10S58375D3736130T',
		'paypal_account_email' => 'derek@buggl.com',
		'use_paypal_sandbox' => false,*/
	);

	private $stagingVars = array(
		'facebookAppId' => '140364889470819',
		'facebookAppSecret' => '4420acb97a05d479e975783d30673092',

		'yahooMailConsumerKey' => 'dj0yJmk9MWhwbjlEcmFFMFNBJmQ9WVdrOVFuUnJNMlZrTXpRbWNHbzlOemd4TlRjMU1UWXkmcz1jb25zdW1lcnNlY3JldCZ4PTRi',
		'yahooMailConsumerSecret' => '0d40769980afe36a5a01c0f32bed14a9c6607d6b',

		'googleMapsApiKey' => 'AIzaSyBbY4GN6gYS7dHtNjf4NPgbAilusybb5W4',

		'googleClientId' => '464369053826.apps.googleusercontent.com',
		'googleClientSecret' => 'n0G7bPNN4KMx5pQ_4M8GPlaj',

		'twitterConsumerKey' => '4f9mOdrVsUaraJZMmhqZ8Q',
		'twitterConsumerSecret' => 'vq04nKfaqGiOpIfs0PwbMwUbTJgjdbNcG6v2qvQD0',

		'stripe_secret_key'	=> 'sk_test_SCumDZLz8OxHfBKrj3kSENi5',
		'stripe_publishable_key' => 'pk_test_EBbpJesbhTWaEbMEMQ6VnLwV',
		'stripe_client_id' => 'ca_1lEUwNIF8YRjp6NiAGsjKatPKzS8mtj4',
		'stripe_application_fee' => 10,
		
		'paypal_security_user_id' => 'noel.bacarisas-facilitator_api1.gmail.com',
		'paypal_security_paswword' => '1380522436',
		'paypal_security_signature' => 'AJiW4p9fpWOi42UIzLI8BotSVvwaA2Sz.lDVq7tXt9OROhGHUyBTCWbg',
		'paypal_app_id' => 'APP-80W284485P519543T',
		'paypal_account_email' => 'noel.bacarisas-facilitator@gmail.com',
		'use_paypal_sandbox' => true,
	);

	private $betaSiteVars = array(
		'facebookAppId' => '517832091587063',
		'facebookAppSecret' => '343d24f58450635d84cabbd81a33f0a9',

		'yahooMailConsumerKey' => 'dj0yJmk9QTRERzRkN2ZSV3M3JmQ9WVdrOVlqaG1OblY0TTJNbWNHbzlNVGczTURrME1UTTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD02YQ--',
		'yahooMailConsumerSecret' => '21ee9f927dfe8eedc2dddc677ab0cc8c63f69d56',

		'googleMapsApiKey' => 'AIzaSyCBfQzUarCd2GZfDNBFj87Z53alxKLgcHs',

		'googleClientId' => '464369053826.apps.googleusercontent.com',
		'googleClientSecret' => 'n0G7bPNN4KMx5pQ_4M8GPlaj',

		'twitterConsumerKey' => 'rQzziGxG36ZNZWPOjWhmQ',
		'twitterConsumerSecret' => 'zedSzway3p4v1kQ8MFD6XK1dYMDmHJc15FgGtAQ3A',

		'stripe_secret_key'	=> 'sk_test_TokceL11kB4W83Bx2j34xQXT',
		'stripe_publishable_key' => 'pk_test_nBSgpOChKR13rEByWi4PNrOG',
		'stripe_client_id' => 'ca_1lESL6Ex7wbJdZ9pZyg8OPtSqiUzBdIN',
		'stripe_application_fee' => 10,
		
		'paypal_security_user_id' => 'derek_api1.buggl.com',
		'paypal_security_paswword' => 'UUWFNEN7M4FVGBK8',
		'paypal_security_signature' => 'AB6-BjQztXJcpiQ114XqUwMjpOW7AGy3Cgis5CV.WZKB.6CEiElnavAp',
		'paypal_app_id' => 'APP-10S58375D3736130T',
		'paypal_account_email' => 'derek@buggl.com',
		'use_paypal_sandbox' => false,
		
		/*'paypal_security_user_id' => 'noel.bacarisas-facilitator_api1.gmail.com',
		'paypal_security_paswword' => '1380522436',
		'paypal_security_signature' => 'AJiW4p9fpWOi42UIzLI8BotSVvwaA2Sz.lDVq7tXt9OROhGHUyBTCWbg',
		'paypal_app_id' => 'APP-80W284485P519543T',
		'paypal_account_email' => 'noel.bacarisas-facilitator@gmail.com',
		'use_paypal_sandbox' => true,*/
	);

	private $prodVars = array(
		'facebookAppId' => '517832091587063',
		'facebookAppSecret' => '343d24f58450635d84cabbd81a33f0a9',

		'yahooMailConsumerKey' => 'dj0yJmk9QTRERzRkN2ZSV3M3JmQ9WVdrOVlqaG1OblY0TTJNbWNHbzlNVGczTURrME1UTTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD02YQ--',
		'yahooMailConsumerSecret' => '21ee9f927dfe8eedc2dddc677ab0cc8c63f69d56',

		'googleMapsApiKey' => 'AIzaSyCBfQzUarCd2GZfDNBFj87Z53alxKLgcHs',

		'googleClientId' => '464369053826.apps.googleusercontent.com',
		'googleClientSecret' => 'n0G7bPNN4KMx5pQ_4M8GPlaj',

		'twitterConsumerKey' => 'rQzziGxG36ZNZWPOjWhmQ',
		'twitterConsumerSecret' => 'zedSzway3p4v1kQ8MFD6XK1dYMDmHJc15FgGtAQ3A',

		'stripe_secret_key'	=> 'sk_test_TokceL11kB4W83Bx2j34xQXT',
		'stripe_publishable_key' => 'pk_test_nBSgpOChKR13rEByWi4PNrOG',
		'stripe_client_id' => 'ca_1lESL6Ex7wbJdZ9pZyg8OPtSqiUzBdIN',
		'stripe_application_fee' => 10,
		
		'paypal_security_user_id' => 'derek_api1.buggl.com',
		'paypal_security_paswword' => 'UUWFNEN7M4FVGBK8',
		'paypal_security_signature' => 'AB6-BjQztXJcpiQ114XqUwMjpOW7AGy3Cgis5CV.WZKB.6CEiElnavAp',
		'paypal_app_id' => 'APP-10S58375D3736130T',
		'paypal_account_email' => 'derek@buggl.com',
		'use_paypal_sandbox' => false,
	);

	private $vars;

	public function __construct()
	{
		//NOTE: There may be a more elegant way
		if(strpos($_SERVER['HTTP_HOST'],'local') !== false)
			$this->vars = $this->localVars;
		else if(strpos($_SERVER['HTTP_HOST'],'staging') !== false)
			$this->vars = $this->stagingVars;
		else if(strpos($_SERVER['HTTP_HOST'],'beta') !== false)
			$this->vars = $this->betaSiteVars;
		else
			$this->vars = $this->prodVars;

	}

	public function getVariable($key)
	{
		if(isset($this->vars[$key])){
			return $this->vars[$key];
		}

		throw new \Exception('Key does not exist!');
	}
}