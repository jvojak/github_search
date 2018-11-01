<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp;
use App\Term;

class SearchController extends Controller
{

    /**
     * Search {provider} issues with a given word
     *
     * @param $provider - github, twitter, ..
     * @param Request $request
     * @return JSON response
     */
    public function search( $provider, Request $request )
    {
      $term = $request->input('term');

      // If query string is empty or is not set, return response
      if($term == NULL)
      {
        return response()->json( ['term' => $term, 'response' => 'Insufficient parameters!'] );
      }

      $client = new GuzzleHttp\Client();
      $term_rocks = $term. ' rocks';
      $term_sucks = $term. ' sucks';
      
      if($model = Term::where(['provider' => $provider, 'term' => $term])->first())
      {
        return response()->json( ['term' => $model->term, 'score' => $model->score] );
      }
      
      switch($provider)
      {
        case 'github':
          // Get positive results
          $res = $client->request('GET', 'https://api.github.com/search/issues?',
            [
              'query' => ['q' => $term_rocks]
            ]);
          $res = json_decode($res->getBody());
          $positive = $res->total_count;

          // Get negative results
          $res = $client->request('GET', 'https://api.github.com/search/issues?',
            [
              'query' => ['q' => $term_sucks]
            ]);
          $res = json_decode($res->getBody());
          $negative = $res->total_count;

          // Check for division by ZERO
          if( $negative == 0 )
          {
            $score = 'Infinite';
          }
          else
          {
            $score = ( $positive / ( $positive + $negative) ) * 10;
          }

          $model = Term::create(
            [
              'term' => $term,
              'provider' => $provider,
              'score' => $score
            ]);

          break;
        default:
          break;
      }

      return response()->json( ['term' => $model->term, 'score' => $model->score] );
    }
}
