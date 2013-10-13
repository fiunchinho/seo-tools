<?php

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

require_once 'UserHelper.php';
require_once 'DomainHelper.php';
require_once __DIR__ . '/../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

/**
 * Behat context class.
 */
class FeatureContext extends BehatContext
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
    	$this->user_helper         = new UserHelper();
    	$this->domain_helper       = new DomainHelper();
    	$this->application_helper  = new ApplicationHelper();
    	#$this->useContext('user_context', new UserFeatureContext( array() ) );
    	//$this->container 	= require_once __DIR__ . '/../../src/DomainFinderSilex/app.php';
    }

    /**
     * @Given /^I am a anonymous user$/
     */
    public function iAmAAnonymousUser()
    {
    }

    /**
     * @Given /^I provide a valid email like "([^"]*)"$/
     */
    public function iProvideAValidEmailLike($email)
    {
        $this->email = $email;
    }

    /**
     * @Given /^I provide a valid password like "([^"]*)"$/
     */
    public function iProvideAValidPasswordLike($password)
    {
        $this->password = $password;
    }

    /**
     * @Given /^I provide a valid name like "([^"]*)"$/
     */
    public function iProvideAValidNameLike($name)
    {
        $this->name = $name;
    }

    /**
     * @Given /^I accept the terms$/
     */
    public function iAcceptTheTerms()
    {
        $this->terms = 'on';
    }

    /**
     * @Given /^I don't accept the terms$/
     */
    public function iDontAcceptTheTerms()
    {
        $this->terms = null;
    }

    /**
     * @When /^I try to register$/
     */
    public function iTryToRegister()
    {
    	$this->response = $this->user_helper->registerUser(
    		array(
    			'email' 	=> $this->email,
                'password'  => $this->password,
                'name'      => $this->name,
    			'terms' 	=> $this->terms
    		)
    	);
    }

    /**
     * @Given /^I don\'t provide an email$/
     */
    public function iDonTProvideAnEmail()
    {
        $this->email = null;
    }

    /**
     * @Then /^I should get an "([^"]*)" error$/
     */
    public function iShouldGetAnError($exception)
    {
    	$this->user_helper->assertErrorInRegistration( $exception );
    }

    /**
     * @Given /^I provide a valid email that is already registered like "([^"]*)"$/
     */
    public function iProvideAValidEmailThatIsAlreadyRegisteredLike($email)
    {
        $this->email = $email;
    }

    /**
     * @Given /^I am a registered user$/
     */
    public function iAmARegisteredUser()
    {
    	$this->email = 'existing@email.com';
    }

    /**
     * @Given /^I provide a correct password like "([^"]*)"$/
     */
    public function iProvideACorrectPasswordLike($password)
    {
        $this->password = $password;
    }

    /**
     * @When /^I try to login$/
     */
    public function iTryToLogin()
    {
        $this->response = $this->user_helper->login(
        	array(
    			'email' 	=> $this->email,
    			'password' 	=> $this->password
    		)
        );
    }

    /**
     * @Then /^I should be logged in in the application$/
     */
    public function iShouldBeLoggedInInTheApplication()
    {
        $this->user_helper->assertUserIsLoggedIn();
    }

    /**
     * @Given /^I provide a incorrect password like "([^"]*)"$/
     */
    public function iProvideAIncorrectPasswordLike($password)
    {
        $this->password = $password;
    }

    /**
     * @Given /^I am an unregistered user$/
     */
    public function iAmAnUnregisteredUser()
    {
    }

    /**
     * @Given /^I provide a password like "([^"]*)"$/
     */
    public function iProvideAPasswordLike($password)
    {
        $this->password = $password;
    }

    /**
     * @Given /^I am an logged in user$/
     */
    public function iAmAnLoggedInUser()
    {
        $this->response = $this->user_helper->login(
        	array(
    			'email' 	=> 'existing@email.com',
    			'password' 	=> 'correct_password'
    		)
        );
    }

    /**
     * @When /^I try to logout$/
     */
    public function iTryToLogout()
    {
        $this->response = $this->user_helper->logout();
    }

    /**
     * @Then /^I should be logged out of the application$/
     */
    public function iShouldBeLoggedOutOfTheApplication()
    {
        $this->user_helper->assertUserHasLogout();
    }

    /**
     * @Given /^I didn\'t install the application before$/
     */
    public function iDidnTInstallTheApplicationBefore()
    {
    }

    /**
     * @When /^I install the application$/
     */
    public function iInstallTheApplication()
    {
        $request = array(
        	'email' 	=> $this->email,
        	'password' 	=> $this->password,
        );

        $this->response = $this->user_helper->install( $request );
    }

    /**
     * @Then /^application should be installed$/
     */
    public function applicationShouldBeInstalled()
    {
    }

    /**
     * @Given /^user should be registered with email \'([^\']*)\' and password \'([^\']*)\'$/
     */
    public function userShouldBeRegisteredWithEmailAndPassword($email, $password)
    {
    	$this->user_helper->assertUserCreated( $this->email );
    }

    /**
     * @Given /^\'([^\']*)\' is logged in$/
     */
    public function isLoggedIn($user)
    {
        //$this->user_helper->login($user);
        $this->response = $this->user_helper->login(
        	array(
    			'email' 	=> 'existing@email.com',
    			'password' 	=> 'correct_password'
    		)
        );
    }

    /**
     * @When /^I register the domain "([^"]*)"$/
     */
    public function iRegisterTheDomain($domain)
    {
        $this->domain_helper->register($domain);
    }

    /**
     * @Then /^the domain "([^"]*)" should be registered$/
     */
    public function theDomainShouldBeRegistered($domain)
    {
        $this->domain_helper->assertDomainIsRegistered($domain);
    }

    /**
     * @When /^I register the application "([^"]*)"$/
     */
    public function iRegisterTheApplication($app_name)
    {
        $this->application_helper->create($app_name);
    }

    /**
     * @Then /^application "([^"]*)" should be created$/
     */
    public function applicationShouldBeCreated($app_name)
    {
        $this->application_helper->assertApplicationIsRegistered($app_name);
    }

}
