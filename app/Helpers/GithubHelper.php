<?php

namespace App\Helpers;
use GuzzleHttp;

class GithubHelper
{
	/**
	 * Send cURL GET request to GitHub API and searches for queried term
     * @return $count = term popularity
     */
    public static function searchIssues($term) 
    {
    	$client = new GuzzleHttp\Client();

        $res = $client->request('GET', 'https://api.github.com/search/issues?',
            [
              'query' => ['q' => $term]
            ]);
		$res = json_decode($res->getBody());
		$count = $res->total_count;

		return $count;
    }
}