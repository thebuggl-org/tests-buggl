<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\EGuide;

/**
 * Search service for finding eguide via free seach bar
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad>
 * @version   1.0
 * @copyright 2013 (c) Buggl.com
 */
class FreeSearchService
{
	private $entityManager;

	/**
	 * list of words not to be included as keywords
	 *
	 * @link http://www.webconfs.com/stop-words.php , http://norm.al/2009/04/14/list-of-english-stop-words/
	 * @var array
	 *
	 */
	private $stopWords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");

	/**
	 * constructor. injects entitymanager and custom service
	 *
	 * @param EntityManager $entityManager
	 * @param FreeSearchQueryService $freeSearchQueryService
	 */
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * search eguides containing the keywords
	 * @param  string $activity
	 * @param  string $location
	 * @param  integer $page
	 * @author Farly Taboada <farly.taboada@goabroad.com>
	 *
	 * @return EGuide
	 */
	public function search($activity, $location, $status, $limit, $page=1, $type='relevant')
	{
		// $keywords = $this->processKeywords($keywords);
		if (strlen($activity)) {
			$activity = $this->processKeywordsV2($activity);
		} else {
			$activity = array();
		}

		// $activity = explode(',', $activity);

		// if (count($keywords) == 0) {
		// 	$results = null;
		// } else {
			// $results = $this->entityManager->getRepository('BugglMainBundle:Keyword')->findByKeywords($keywords, $status, $limit, $page);
		$results = $this->entityManager->getRepository('BugglMainBundle:EGuide')->findByKeywords($activity, 'location:'.$location, $status, $limit, $page, $type);
		// }
		return $results;
	}

	/**
	 * removes quote
	 * @param  string $keywords
	 * @author Farly Taboada <farly.taboada@goabroad.com>
	 *
	 * @return string
	 */
	private function removeQuotes($keywords)
	{
		$keywords = str_replace("\"", "", $keywords);
		$keywords = str_replace("\'", "", $keywords);

		return $keywords;
	}

	/**
	 * removes words as keywords if listed in the stop words
	 * @param  string $keywords
	 * @author Farly Taboada <farly.taboada@goabroad.com>
	 *
	 * @return string
	 */
	private function removeStopWords($keywords)
	{
		// $keywords = str_ireplace($this->stopwords, "", $keywords);
		//
		foreach ($keywords as $key => $keyword) {
			if (in_array($keyword, $this->stopWords)) {
				unset($keywords[$key]);
			}
		}

		return $keywords;
	}

	/**
	 * basically processes the input: removes quotes, stop words.
	 * @param  string $keywords
	 *
	 * @return string
	 */
	private function processKeywordsV2($keywords)
	{
		$keywords = trim($keywords);
		$keywords = $this->removeQuotes($keywords);
		$keywords = explode(',', $keywords);

		// $keywords = array_filter($keywords, function($keyword){
		// 	if (strlen(trim($keyword))) {
		// 		return $keyword;
		// 	}
		// });

		$keywords = array_filter($keywords);

		$keywords = $this->removeStopWords($keywords);

		return $keywords;
	}


	/**
	 * basically processes the input: removes quotes, stop words.
	 * @param  string $keywords
	 *
	 * @return string
	 */
	private function processKeywords($keywords)
	{
		$keywords = $this->removeQuotes($keywords);
		$keywords = explode(' ', $keywords);
		$keywords = $this->removeStopWords($keywords);

		$repository = $this->entityManager->getRepository('BugglMainBundle:Country');

		/**
		 * check country keywords
		 */
		foreach ($keywords as $key => $keyword) {
			if (!is_null($repository->findByCountryLikeName($keyword))) {
				if (count($keywords) == 1) {
					continue;
				}
				else if ( isset($keywords[$key+1]) && !is_null($repository->findByCountryName("{$keyword} {$keywords[$key+1]}"))) {
					$keywords[$key] = "{$keyword} {$keywords[$key+1]}";
					unset($keywords[$key+1]);
				}
			}
		}

		return $keywords;
	}
}