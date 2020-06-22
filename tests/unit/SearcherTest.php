<?php

namespace tests;

use app\components;
use app\components\Searcher;
use app\components\Factory;
use app\models\User;
use app\models\BitbucketRepo;
use app\models\GithubRepo;
use app\models\GitlabRepo;

/**
 * SearcherTest contains test cases for searcher component
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class SearcherTest extends \Codeception\Test\Unit
{
    /**
     * Test case for searching via several platforms
     * 
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     *
     * @return void
     */
    public function testSearcher()
    {
        /**
	 * There is no any throwing exceptions in this component (I dunno why), so I cannot handle negative checks
	 * Research of code show, that instead most of code used to prepare and show content, so it was easy
	 * But this handler actually go to repositories and try to search real users
	 * With my knowledge, I cannot mock it here
	 * And also I cannot change any files beside test ones
	 * What is worser, Bitbucket does not response at all
	 * And Github ban me for 180 minutes every 50 requests
	 *
	 * So, I do what I can for now, at least it return some data with this users
	 * So I can check that call itself work properly
	 */
	    $I_factory = new Factory();
	    $I_bitbucket = $I_factory->create("bitbucket");
	    $I_github = $I_factory->create("github");
	    $I_gitlab = $I_factory->create("gitlab");
	    $I_list_platforms = array($I_bitbucket, $I_gitlab, $I_github);
	    $I_list_users = array("amolchanov", "Xoscore");
	    $I_test_searcher = new Searcher();
	    $I_test_result = $I_test_searcher->search($I_list_platforms, $I_list_users);
	    $this->assertTrue(!empty($I_test_result));

    }
}
