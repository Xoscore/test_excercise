<?php

namespace tests;

use app\models;
use app\models\GithubRepo;

/**
 * GithubRepoTest contains test cases for github repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class GithubRepoTest extends \Codeception\Test\Unit
{
    /**
     * Test case for counting repo rating
     *
     * @return void
     */
    public function testRatingCount()
    {
        /**
         * Simple check, that calculation work
	 */
	    $I_test_github_repo = new GithubRepo("test", 10, 10, 5);
	    $this->assertTrue($I_test_github_repo->getRating() == 10);
    }

    /**
     * Test case for repo model data serialization
     *
     * @return void
     */
    public function testData()
    {
        /**
         * Compare with expected data
         */
	    $I_test_github_repo = new GithubRepo("test", 10, 10, 5);
	    $I_expected_data = array(
		    "name" => "test",
		    "fork-count" => 10,
		    "start-count" => 10,
		    "watcher-count" => 5,
		    "rating" => 10
	    );
	    $I_test_data = $I_test_github_repo->getData();
	    $this->assertTrue($I_test_data == $I_expected_data);
    }

    /**
     * Test case for repo model __toString verification
     *
     * @return void
     */
    public function testStringify()
    {
        /**
         * Again, I have no reason to check regexp here, because it return the same outpu for same input
	 */
	    $I_test_github_repo = new GithubRepo("test", 10, 10, 5);
	    $I_test_string = $I_test_github_repo->__toString();
	    $this->assertTrue(strpos($I_test_string, "test") == 0);
	    $this->assertTrue(strpos($I_test_string, "10") == 78);
    }
}
