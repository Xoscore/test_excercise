<?php

namespace tests;

use app\models;
use app\models\User;
use app\models\GitlabRepo;
use app\models\GithubRepo;
use app\models\BitbucketRepo;

/**
 * UserTest contains test cases for user model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class UserTest extends \Codeception\Test\Unit
{
    /**
     * Test case for adding repo models to user model
     * 
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     *
     * @return void
     */
    public function testPositiveGitlabAddingRepos()
    {
        /**
	 * "repositories is private field, and there is no "getrepo" method
	 * so, the only check for this, as I know, is through "get_data" method
	 */
	$I_test_repo = new GitlabRepo("test1", 10, 10);
	$I_test_user = new User("nuwe1", "Alex", "gitlab");
	$I_test_user->addRepos([$I_test_repo]);
	$I_user_data = $I_test_user->getData();
	$this->assertTrue($I_user_data["repo"][0]["name"] == "test1");
    }

    public function testPositiveGithubAddingRepos()
    {
	/**
	 * Check the same for Github
	 */
	$I_test_repo = new GithubRepo("test1",10, 10, 10);
	$I_test_user = new User("tester", "Nguyen", "github");
	$I_test_user->addRepos([$I_test_repo]);
	$I_user_data = $I_test_user->getData();
	$this->assertTrue($I_user_data["repo"][0]["name"] == "test1");
    }

    public function testPositiveBitbucketAddingRepos()
    {
	/**
	 * Check the sane for Bitbucket
	 */
	$I_test_repo = new BitbucketRepo("test1", 10, 10);
	$I_test_user = new User("bibucket", "Oleg", "bitbucket");
	$I_test_user->addRepos([$I_test_repo]);
	$I_user_data = $I_test_user->getData();
	$this->assertTrue($I_user_data["repo"][0]["name"] == "test1");
    }

    public function testNegativeAddingRepos()
    {
	/**
	 * Now catch the logic exception here
	 */
	$I_test_invalid_repo = "test_repo";
	$I_test_user = new User("negative", "Kolya", "bitbucket");
	$this->expectException(\LogicException::class);
	$I_test_user->addRepos([$I_test_invalid_repo, $I_test_invalid_repo]);
    }
    /**
     * Test case for counting total user rating
     *
     * @return void
     */
    public function testTotalRatingCount()
    {
        /**
         * Add few repos to user and check that calculation is correct
	 */
	$I_test_user = new User("rating", "Misha", "github");
	$I_test_repo_1 = new GithubRepo("test1", 10, 10, 10);
	$I_test_repo_2 = new GithubRepo("test2", 5, 5, 5);
	$I_test_user->addRepos([$I_test_repo_1, $I_test_repo_2]);
	$this->assertTrue($I_test_user->getTotalRating() == 17.5);
    }

    /**
     * Test case for user model data serialization
     *
     * @return void
     */
    public function testData()
    {
        /**
         * For this one, prepare expected data and just compare them
	 */
	$I_test_user = new User("eater", "Boris", "gitlab");
	$I_test_repo_1 = new GitlabRepo("test1", 5, 5);
	$I_test_repo_2 = new GitlabRepo("test2", 10, 10);
	$I_test_user->addRepos([$I_test_repo_1, $I_test_repo_2]);
	$I_test_data = $I_test_user->getData();
	$I_expected_data = array(
		"name" => "Boris",
		"platform" => "gitlab",
		"total-rating" => 18.75,
		"repos" => array(),
		"repo" => array(
			0 => array(
				"name" => "test2",
				"fork-count" => 10,
				"start-count" => 10,
				"rating" => 12.5
			),
			1 => array(
				"name" => "test1",
				"fork-count" => 5,
				"start-count" => 5,
				"rating" => 6.25
			)
		)
	);
    	$this->assertTrue($I_test_data == $I_expected_data);
    }

    /**
     * Test case for user model __toString verification
     *
     * @return void
     */
    public function testStringify()
    {
        /**
	 * Okie, for now checking formatted several lines string is a little bit difficult to me
	 * And timeconsuming too
	 * So, at least check, that this output contain several key words on exact positions
         */
	    $I_test_user = new User("stringer", "Gavriil", "github");
	    $I_test_repo_1 = new GithubRepo("test1", 5, 5, 5);
	    $I_test_repo_2 = new GithubRepo("test2", 10, 10, 10);
	    $I_test_user->addRepos([$I_test_repo_1, $I_test_repo_2]);
	    $I_test_string = $I_test_user->__toString();
	    $this->assertTrue(strpos($I_test_string, "Gavriil") == 0);
	    $this->assertTrue(strpos($I_test_string, "github") == 9);
	    $this->assertTrue(strpos($I_test_string, "test1") == 307);
	    $this->assertTrue(strpos($I_test_string, "test2") == 200);
    }
}
