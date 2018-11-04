<?php

namespace App\Http\Controllers\Api\v1;
use Illuminate\Http\Request;
use App\Helpers\GithubHelper;
use App\Helpers\MathHelper;
use App\Http\Controllers\Controller;

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
      
      // If the term is already queried in the past, its score should be stored in DB
      if($model = Term::where(['provider' => $provider, 'term' => $term])->first())
      {
        return response()->json( [
          'term' => $model->term, 
          'score' => $model->score] 
        );
      }

      $term_rocks = $term. ' rocks';
      $term_sucks = $term. ' sucks';
      
      switch($provider)
      {
        case 'github':
          // Github positive and negative results
          $positive = GithubHelper::searchIssues($term_rocks);
          $negative = GithubHelper::searchIssues($term_sucks);

          $score = MathHelper::calculatePopularity($positive, $negative);

          $model = Term::create([
              'term' => $term,
              'provider' => $provider,
              'score' => $score
            ]);
          break;
        default:
          break;
      }

      return response()->json([
        'term' => $model->term, 
        'score' => $model->score
      ]);
    }
}
