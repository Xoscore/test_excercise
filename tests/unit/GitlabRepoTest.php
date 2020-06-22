<?php

namespace tests;

use app\models;
use app\models\GitlabRepo;

/**
 * GitlabRepoTest contains test cases for gitlab repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class GitlabRepoTest extends \Codeception\Test\Unit
{
    /**
     * Test case for counting repo rating
     *
     * @return void
     */
    public function testRatingCount()
    {
        /**
         * Just count rating for manually generated repo
	 */
	$I_test_gitlab_repo = new GitlabRepo("test", 10, 10);
	$this->assertTrue($I_test_gitlab_repo->getRating() == 12.5);
    }

    /**
     * Test case for repo model data serialization
     *
     * @return void
     */
    public function testData()
    {
        /**
         * Prepare some expected data and just compare it with one from function
	 */
	$I_test_gitlab_repo = new GitlabRepo("test", 10, 10);
	$I_expected_data = array(
		"name" => "test",
		"fork-count" => 10,
		"start-count" => 10,
		"rating" => 12.5
	);
	$I_test_data = $I_test_gitlab_repo->getData();
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
	 * It would be time consuming to generate regexp here
	 * Just check, that values are on place, as expected
	 */
	$I_test_gitlab_repo = new GitlabRepo("test", 10, 10);
	$I_test_string = $I_test_gitlab_repo->__toString();
	$this->assertTrue(strpos($I_test_string, "test") == 0);
	$this->assertTrue(strpos($I_test_string, "10") == 78);
    }
}
