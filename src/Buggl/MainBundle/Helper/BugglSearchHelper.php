<?php

namespace Buggl\MainBundle\Helper;

class BugglSearchHelper
{
	/**
	 * list of words not to be included as keywords
	 *
	 * @link http://www.webconfs.com/stop-words.php , http://norm.al/2009/04/14/list-of-english-stop-words/
	 * @var array
	 *
	 */
	private $stopWords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");
	
	private $em;
	
	private $params = array();
	private $sortBy = array('relevant', 'recent', 'download');
	private $limit = 10;
	private $offset = 0;

	/**
	 * constructor. injects entitymanager and custom service
	 *
	 * @param EntityManager $entityManager
	 */
	public function __construct($entityManager)
	{
		$this->em = $entityManager;
	}

	public function sanitizeParams(array $params)
	{
		$this->params['location'] = trim( $params['location'] );
		$this->params['activity'] = $this->sanitizeKeywords( $params['activity'] );
		$this->params['sort'] = ( isset($params['sort']) AND in_array( trim($params['sort']), $this->sortBy) ) ?  trim( $params['sort'] ) : 'relevant';
		$this->params['page'] = ( isset($params['page']) AND (0 < (int)$params['page']) ) ? (int)$params['page'] : 1;
	}

	private function sanitizeKeywords($keywords)
	{
		$keywords = trim($keywords);
		$keywords = $this->removeQuotes($keywords);
		$keywords = $this->removeStopWords($keywords);

		$keywords = str_replace(',', "", $keywords);
		$keywords = explode(' ', $keywords);
		$keywords = array_filter(array_map('trim', $keywords));
		return $keywords;
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
	 * @author Nash Lesigon <nash.lesigon@goabroad.com>
	 *
	 * @return string
	 */
	private function removeStopWords($keywords)
	{
		$patterns = array();
		foreach($this->stopWords as $word)
		{
			$patterns[] = "/\b".$word."\b/u";
		}

		$keywords = preg_replace($patterns, "", $keywords);
		$keywords = preg_replace('/\s+/', " ", $keywords);
		
		return $keywords;
	}

	private function buildQuery()
	{
		$activities = implode("|", $this->params['activity']);
		
		$location = $this->params['location'];

		$locationJoin = "";
		$locationCond = "";
		if( strlen($location) )
		{
			$locationJoin = "LEFT JOIN e_guide_location as loc
								ON loc.e_guide_id = guide.id";

			$locationCond = sprintf("AND (loc.address LIKE '%s' OR guide.plain_title LIKE '%s')", "%".$location."%", "%".$location."%");
		}

		$activityJoin = "";
		$activityCond = "";
		if( strlen($activities) )
		{
			$activityJoin = "LEFT JOIN e_guide_to_category as egtc
								ON egtc.e_guide_id = guide.id
							LEFT JOIN category as cat
								ON cat.id = egtc.category_id";
			$activityCond = sprintf("AND cat.name REGEXP '%s'", $activities);
		}

		$orderBy = "";
		if ( $this->params['sort'] == 'download' )
			$orderBy = "ORDER BY guide.dl_count DESC";
        else if ( $this->params['sort'] == 'recent' )
        	$orderBy = "ORDER BY guide.date_updated DESC";
        
		
		$page = $this->params['page'];
		$limit = $this->limit + 1;
		$offset = ( 1 == $page) ? $this->offset : ($page - 1) * $this->limit;

		$query = "SELECT DISTINCT guide.* FROM e_guide as guide 
					$locationJoin
					$activityJoin
					WHERE 1
						AND guide.status = 2
						$locationCond
						$activityCond
					$orderBy
					LIMIT $offset, $limit
					";
		
		return $query;
		
	}

	public function execute()
	{
		$query = $this->buildQuery();
		$results = $this->em->getRepository('BugglMainBundle:EGuide')->findByQuery($query);
		
		$hasNext = false;
		if(count($results) > $this->limit){
			$hasNext = true;
			$partResult = array_chunk($results, $this->limit);
			$results = $partResult[0];
		}

		return array('objects' => $results, 'hasNext' => $hasNext, 'nextPage' => $this->params['page'] + 1);
	}
}