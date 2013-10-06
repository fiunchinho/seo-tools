<?php

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Behat context class.
 */
class UserFeatureContext extends BehatContext
{
	private $email;
	private $password;
	private $response;

    /**
     * Initializes context. Every scenario gets it's own context object.
     *
     * @param array $parameters Suite parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    	$this->user_helper = new UserHelper();
    }

    /**
     * @Given /^I am a registered user$/
     */
    public function iAmARegisteredUser()
    {
        throw new PendingException();
    }

    /**
     * @Given /^I provide a correct password like "([^"]*)"$/
     */
    public function iProvideACorrectPasswordLike($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When /^I try to login$/
     */
    public function iTryToLogin()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should be logged in in the application$/
     */
    public function iShouldBeLoggedInInTheApplication()
    {
        throw new PendingException();
    }

    /**
     * @Given /^I provide a incorrect password like "([^"]*)"$/
     */
    public function iProvideAIncorrectPasswordLike($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given /^I am an unregistered user$/
     */
    public function iAmAnUnregisteredUser()
    {
        throw new PendingException();
    }

    /**
     * @Given /^I provide a password like "([^"]*)"$/
     */
    public function iProvideAPasswordLike($arg1)
    {
        throw new PendingException();
    }
}
