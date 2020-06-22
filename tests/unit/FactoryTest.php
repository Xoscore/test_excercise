<?php

namespace tests;

use app\components;
use app\components\Factory;
use app\components\platforms\Bitbucket;
use app\components\platforms\Gitlab;
use app\components\platforms\Github;
/**
 * FactoryTest contains test cases for factory component
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class FactoryTest extends \Codeception\Test\Unit
{
    /**
     * Test case for creating platform component
     * 
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     *
     * @return void
     */
    public function testCreateBitbucket()
    {
        /**
         * Positive check to create Bitbucket
	 */
	$I_test_factory = new Factory();
	$I_platform = $I_test_factory -> create("bitbucket");
	$this->assertTrue($I_platform instanceof Bitbucket);
    }

    public function testCreateGitlab()
    {
	/**
	* Positive check to create Gitlab 
	 */
	$I_test_factory = new Factory();
	$I_platform = $I_test_factory->create("gitlab");
	$this->assertTrue($I_platform instanceof Gitlab);
    }

    public function testCreateGithub()
    {
	/**
	* Positive check for create Github
	*/
	$I_test_factory = new Factory();
	$I_platform = $I_test_factory->create("github");
	$this->assertTrue($I_platform instanceof Github);
    }

    public function testNegativeCreate()
    {
	/**
	* Negative check to catch exception
	*/
	$I_test_factory = new Factory();
	$this->expectException(\LogicException::class);
        $I_platform = $I_test_factory->create("abcdefg");
    }	
}
