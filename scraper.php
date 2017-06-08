<?php


// Google Scraper by NPROFIT.net

class GoogleScraper
{
	private $query;
	private $language;
	private $numberofresults;
	private $resultsarray;

	private $hour;
	private $attempts;

	public $log;

	public function __construct($query,$language,$numberofresults){

		$this->query = $query;
		$this->language = $language;
		$this->numberofresults = $numberofresults;
		$this->hour = date("H:i:s");
		$this->attempts = 2;
		$this->resultsarray = array();
	}
	public function return_results(){
		
	    if($error = error_get_last()){
	    	$this->log.="HTTP request failed. Error was: " . $error['message'];
	    }
		return $resultsarray;
	}
	public function clear_log(){
		$this->log = "<br/>".$this->hour."/$ query = ".$this->query;
	}

	public function scrape(){
		for($i = 0; $i < $this->attempts; ++$i){
			$this->log.='<br/><br/><strong>Attempt nr '. ($i+1). '</strong>';
			
			$this->clear_log();
			$url = 'http://www.google.'.$this->$language.'/search?q='.$this->query.'&num='.$this->numberofresults;
			
			$this->log.='<br/>url: ' . $url . '<br/>';
	
			@$result = file_get_contents($url);

			if ( !empty($result) ) {

				$page = preg_match_all('@<h3\s*class="r">\s*<a[^<>]*href="/([^<>]*)"[^<>]*>(.*)</a>\s*</h3>@siU', $result, $matches);
		 
				if($page != 0) {
					$googleresults = '';
				
					for ($i = 0; $i < count($matches[2]); $i++) {
						$matches[2][$i] = strip_tags($matches[2][$i]);
						$where = strpos($matches[1][$i],'?q=') + 3;
						$matches[1][$i] = substr($matches[1][$i],$where);
						$where2 = strpos($matches[1][$i],'sa=') - 5;
						$matches[1][$i] = substr($matches[1][$i],0,$where2);

						if( strlen($matches[1][$i]) > 3 ) { $resultsarray[] = $matches[1][$i]; }
					}

					break;
				}
				else {

					$this->log.=" banned";
				}


			}
			else{

					$this->log.=" banned";
			}
		}

	}

}


// Example of use

class RunScraping{
	private $scraping;

	private $starttime;
	private $endtime;
	private $runtime;

	public function start(){
		$this->starttime = microtime(true);

		$this->scraping = new GoogleScraper( 'query', 'com', '50' );

		$this->scraping->scrape();

		echo $this->scraping->$log;

		var_dump( $this->scraping->return_results() );
	}

	public function end(){
		$this->endtime = microtime(true);
	}

	public function run(){
		$this->start();
		$this->end();
		$this->runtime = $this->endtime - $this->starttime;
		echo "<br>Runtime: " . $this->runtime . "sec<br/>";
	}
}

$scraped = new RunScraping();
$scraped->run();


?>
