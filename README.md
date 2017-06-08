# GoogleScraper
Class for quick and easy scraping Google results.

# Use
1. Init with parameters: query, language (com, co.uk, de etcetera), number of results.
2. Scrape.
3. Get log and results.

use = new GoogleScraper( 'query', 'com', '50' ); //1.
use->scrape(); //2.
echo use->$log; //3.
echo use->return_results(); //3.
