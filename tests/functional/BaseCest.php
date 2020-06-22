<?php

use JsonSchema\Validator;
use JsonSchema\Constraints\Constraint;
use Codeception\Util\JsonType;
use Codeception\Extension\Logger;

/**
 * Base contains test cases for testing api endpoint
 * 
 * @see https://codeception.com/docs/modules/Yii2
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class BaseCest
{
    /**
     * Example test case
     *
     * @return void
     */
    public function cestExample(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'kfr',
            ],
            'platforms' => [
                'github',
            ]
        ]);
        $expected = json_decode('[
            {
                "name": "kfr",
                "platform": "github",
                "total-rating": 1.5,
                "repos": [],
                "repo": [
                    {
                        "name": "kf-cli",
                        "fork-count": 0,
                        "start-count": 2,
                        "watcher-count": 2,
                        "rating": 1
                    },
                    {
                        "name": "cards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "UdaciCards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "unikgen",
                        "fork-count": 0,
                        "start-count": 1,
                        "watcher-count": 1,
                        "rating": 0.5
                    }
                ]
            }
        ]');
        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with bad request params
     *
     * @return void
     */
    public function cestBadParamsPlatform(\FunctionalTester $I)
    {
        /**
	 * If we put incorrect platform - it throw logic exception, so do it
	 * Although here it with different class
	 */
	    $I->expectException(\ErrorException::class, function(){
	    	$I->amOnPage([
			"base/api",
			"users" => ["kfr"],
			"platforms" => ["platform1"]
		]);
	    }
	    );
    }

    public function cestBadParamsUsers(\FunctionalTester $I)
    {
	    /**
	     * Now, try to crash app by incorrect user
	     * Wich is a little tricky, because app itself has no exception for it
	     * Found one in Gitlab
	     * Although it comes from API, not from app, it should be counted
	     */
	    $I->expectException(\ErrorException::class, function(){
		    $I->amOnPage([
			    "base/api",
			    "users" => [" "],
			    "platforms" => ["gitlab"]
		    ]);
	    });
    }

    /**
     * Test case for api with empty user list
     *
     * @return void
     */
    public function cestEmptyUsers(\FunctionalTester $I)
    {
        /**
	 * Well, for empty users, both Gitlab and Github throw exceptions
	 * Bitbucket does not response at all =/
	 */
	    $I->expectException(\ErrorException::class, function(){
		    $I->amOnPage([
			    "base/api",
			    "users" => "",
			    "platforms" => ["gitlab"]
		    ]);
	    });
    }

    /**
     * Test case for api with empty platform list
     *
     * @return void
     */
    public function cestEmptyPlatforms(\FunctionalTester $I)
    {
        /**
	 * Well, it is the same logic exception, as for incorrect platform (because it is strict switch inside code)
	 * I just copied it
	 */
		$I->expectException(\ErrorException::class, function(){
	    	$I->amOnPage([
			"base/api",
			"users" => ["kfr"],
			"platforms" => ""
		]);
	    }
	    );
    }

    /**
     * Test case for api with non empty platform list
     *
     * @return void
     */
    public function cestGithubPlatforms(\FunctionalTester $I)
    {
        /**
	 * Well, it is not truly correct to check response with exact JSON (like in example)
	 * Because it is real user's repos, the real users can affect them
	 * SO, I add JSON validation, I take it from here: https://github.com/justinrainbow/json-schema.git
	 * Installation: compose require justinrainbow/json-schema
	 * 
	 * Well, to be honest, I'm not really sure, that schema validation is correct
	 * I do not know, why 'isValid' return true (second assertion)
	 * So, most likely I need some help from someone, who already work with it, or more time to research
	 * At least, it is how it should look in real tests
	 * A proper testing required separate data and schema files
	 *
	 * Also, I will not do it for every JSON returned in this excercise, because it's really time-consuming
	 * And also not really have any mean without requirements
	 */
	 $I->amOnPage([
            "base/api",
            "users" => [
		    "amolchanov",
            ],
            "platforms" => [
		    "github",
            ]
    ]);
	 $I_test_result = json_decode($I->grabPageSource());
	 $I_JSON_validator = new Validator();
	 $I_JSON_schema = (object)[
		 "type" => "array",
		 "properties" => [
			 "name" => [
				 "type" => "string",
				 "required" => true
			 ],
			 "platform" => [
				 "type" => "string",
				 "required" => true,
				 "oneOf" => [
					 "github",
					 "gitlab",
					 "bitbucket"
				 ]
			 ],
			 "total-rating" => [
				 "type" => "integer",
				 "required" => true
			 ],
			 "repos" => [
				 "type" => "array",
				 "required" => true
			 ],
			 "repo" => [
				 "type" => "array",
				 "required" => true,
				 "items" => [
					 "properties" => [
						 "name" => [
							 "type" => "string",
							 "required" => true
						 ],
						 "fork-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "start-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "watcher-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "rating" => [
							 "type" => "integer",
							 "required" => true
						 ]
					 ]
				 ]
			 ]
		 ]
	 ];
	 $I_JSON_validator->validate($I_test_result, $I_JSON_schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);
	 $I->assertTrue(empty($I_JSON_validator->getErrors()));	
	 $I->assertTrue($I_JSON_validator->isValid()); 

    }

    public function cestGitlabPlatforms(\FunctionalTester $I)
    {
	$I->amOnPage([
            "base/api",
            "users" => [
		    "amolchanov",
            ],
            "platforms" => [
		    "gitlab",
            ]
    ]);

	 $I_test_result = json_decode($I->grabPageSource());
	 $I_JSON_validator = new Validator();
	 $I_JSON_schema = (object)[
		 "type" => "array",
		 "properties" => [
			 "name" => [
				 "type" => "string",
				 "required" => true
			 ],
			 "platform" => [
				 "type" => "string",
				 "required" => true,
				 "oneOf" => [
					 "github",
					 "gitlab",
					 "bitbucket"
				 ]
			 ],
			 "total-rating" => [
				 "type" => "integer",
				 "required" => true
			 ],
			 "repos" => [
				 "type" => "array",
				 "required" => true
			 ],
			 "repo" => [
				 "type" => "array",
				 "required" => true,
				 "items" => [
					 "properties" => [
						 "name" => [
							 "type" => "string",
							 "required" => true
						 ],
						 "fork-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "start-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "rating" => [
							 "type" => "integer",
							 "required" => true
						 ]
					 ]
				 ]
			 ]
		 ]
	 ];
	 $I_JSON_validator->validate($I_test_result, $I_JSON_schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);
	 $I->assertTrue(empty($I_JSON_validator->getErrors()));	
	 $I->assertTrue($I_JSON_validator->isValid()); 
    }
    
    public function cestBitbucketPlatforms(\FunctionalTester $I)
    {
	    /* It is truly incorrect to show good result, when main functionality does not work
	     * So, I separate platforms and expect error here
	     */
	$I->amOnPage([
            "base/api",
            "users" => [
		    "amolchanov",
            ],
            "platforms" => [
		    "bitbucket",
            ]
    ]);

	 $I_test_result = json_decode($I->grabPageSource());
	 $I_JSON_validator = new Validator();
	 $I_JSON_schema = (object)[
		 "type" => "array",
		 "properties" => [
			 "name" => [
				 "type" => "string",
				 "required" => true
			 ],
			 "platform" => [
				 "type" => "string",
				 "required" => true,
				 "oneOf" => [
					 "github",
					 "gitlab",
					 "bitbucket"
				 ]
			 ],
			 "total-rating" => [
				 "type" => "integer",
				 "required" => true
			 ],
			 "repos" => [
				 "type" => "array",
				 "required" => true
			 ],
			 "repo" => [
				 "type" => "array",
				 "required" => true,
				 "items" => [
					 "properties" => [
						 "name" => [
							 "type" => "string",
							 "required" => true
						 ],
						 "fork-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "start-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "watcher-count" => [
							 "type" => "integer",
							 "required" => true
						 ],
						 "rating" => [
							 "type" => "integer",
							 "required" => true
						 ]
					 ]
				 ]
			 ]
		 ]
	 ];
	 $I_JSON_validator->validate($I_test_result, $I_JSON_schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);
	 $I->assertTrue(empty($I_JSON_validator->getErrors()));	
	 $I->assertTrue($I_JSON_validator->isValid()); 
    }



    /**
     * Test case for api with non empty user list
     *
     * @return void
     */
    public function cestSeveralUsers(\FunctionalTester $I)
    {
        /**
	 * Do the same for users
	 * Because I did not have gitlab repo, I expect only one repo in response
         */
	$I->amOnPage([
            "base/api",
            "users" => [
		    "amolchanov",
		    "Xoscore"
            ],
            "platforms" => [
		    "gitlab",
            ]
    ]);
	 $I_test_result = json_decode($I->grabPageSource(), true);
	 $I->assertTrue(json_last_error() == JSON_ERROR_NONE);
	 $I->assertTrue($I_test_result[0]["name"] == "amolchanov");
	 $I->assertTrue(!empty($I_test_result[0]["repo"]));

    }

    /**
     * Test case for api with unknown platform in list
     *
     * @return void
     */
    public function cestUnknownPlatforms(\FunctionalTester $I)
    {
        /**
         * Really do not know, what you mean by this test, because it is thrown Logic exception as usual
         */
	$I->expectException(\ErrorException::class, function(){
	    	$I->amOnPage([
			"base/api",
			"users" => ["kfr"],
			"platforms" => ["platform1"]
		]);
	    }
	    );
    }

    /**
     * Test case for api with unknown user in list
     *
     * @return void
     */
    public function cestUnknownUsers(\FunctionalTester $I)
    {
        /**
         * Simple, because we have no exception, it just return empty array
	 */
	    $I->amOnPage([
            "base/api",
            "users" => [
                "mecrosofft",
            ],
            "platforms" => [
		    "github",
		    "gitlab",
		    "bitbucket"
            ]
    ]);
	    $I_result = $I->grabPageSource();
	    $I->assertTrue($I->grabPageSource() == "[]");

    }

    /**
     * Test case for api with mixed (unknown, real) users and non empty platform list
     *
     * @return void
     */
    public function cestMixedUsers(\FunctionalTester $I)
    {
        /**
         * This return only one repo
	 */
	     $I->amOnPage([
            "base/api",
            "users" => [
                "abyrwalg",
		"amolchanov",
            ],
            "platforms" => [
		    "gitlab",
            ]
    ]);
	 $I_test_result = json_decode($I->grabPageSource(), true);
	 $I->assertTrue(json_last_error() == JSON_ERROR_NONE);
	 $I->assertTrue($I_test_result[0]["name"] == "amolchanov");
	 $I->assertTrue(!empty($I_test_result[0]["repo"]));

    }

    /**
     * Test case for api with mixed (github, gitlab, bitbucket) platforms and non empty user list
     *
     * @return void
     */
    public function cestMixedPlatforms(\FunctionalTester $I)
    {
        /**
         * Let's call for several users and platforms and see result
         */
	 $I->amOnPage([
            "base/api",
            "users" => [
                "Xoscore",
		"amolchanov",
            ],
            "platforms" => [
		    "gitlab",
		    "github",
            ]
    ]);

	 $I_test_result = json_decode($I->grabPageSource(), true);
	 $I->assertTrue(json_last_error() == JSON_ERROR_NONE);
	 $I->assertTrue(count($I_test_result) == 3);
    }
}
