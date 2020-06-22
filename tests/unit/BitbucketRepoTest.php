<?php

namespace tests;

use app\models;
use app\models\BitbucketRepo;

/**
 * BitbucketRepoTest contains test cases for bitbucket repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class BitbucketRepoTest extends \Codeception\Test\Unit
{
    /**
     * Test case for counting repo rating
     *
     * @return void
     */
    public function testRatingCount()
    {
        /**
	 * This functions are not go outside
	 * So just create some handle repo manually and check, that function work correctly
	 */
	$I_test_bitbucket_repo = new BitbucketRepo("test", 10, 10);
	$I_test_rating = $I_test_bitbucket_repo->getRating();
	$this->assertTrue($I_test_rating == 15);
    }

    /**
     * Test case for repo model data serialization
     *
     * @return void
     */
    public function testData()
    {
        /**
         * Prepare some test data and check, that data generation is correct
	 */
	$I_test_bitbucket_repo = new BitbucketRepo("test", 10, 10);
	$I_expected_data = array(
		"name" => "test",
		"fork-count" => 10,
		"watcher-count" => 10,
		"rating" => 15
	);
	$I_test_data = $I_test_bitbucket_repo->getData();
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
	 * It would be really time consuming, to create regexp for check such string
	 * So, a little trick, that all data is on place
	 */
	$I_test_bitbucket_repo = new BitbucketRepo("test", 10, 10);
	$I_test_string = $I_test_bitbucket_repo->__toString();
	$this->assertTrue(strpos($I_test_string, "test") == 0);
	$this->assertTrue(strpos($I_test_string, "10") == 78);
    }
}
