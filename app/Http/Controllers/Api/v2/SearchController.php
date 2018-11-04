<?php

namespace App\Http\Controllers\Api\v2;
use Illuminate\Http\Request;
use App\Helpers\GithubHelper;
use App\Helpers\MathHelper;
use App\Http\Controllers\Controller;
use Validator;

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

      // Request validation rules
      $validator = Validator::make($request->all(), [
          'term' => 'required|max:255|min:2',
      ]);

      // If validator failes, return json response with validation errors
      if ($validator->fails()) {    
        return response()->json($validator->messages(), 200);
      }

      $term = $request->input('term');

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
          break;
        default:
          break;
      }

      // Calculating score
      $score = MathHelper::calculatePopularity($positive, $negative);
      $model = Term::create([
          'term' => $term,
          'provider' => $provider,
          'score' => $score
        ]);

      return response()->json([
        'term' => $model->term, 
        'score' => $model->score
      ]);
    }
}
