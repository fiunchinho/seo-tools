<?php
namespace DomainFinder\UseCase;

class RequestFactory
{
	private $container;

	public function __construct( $container )
	{
		$this->container = $container;
	}

	public function get( $name, array $params = array() )
	{
		if ( $name == 'login_required' )
		{
			return $this->getLoginRequiredRequest( $params );
		}
		else if ( $name == 'update_user' )
		{
			return $this->getLoginRequiredRequest( $params );
		}
		else if ( $name == 'application.list' )
		{
			return $this->getLoginRequiredRequest( $params );
		}
		else if ( $name == 'application.save' )
		{
			return $this->getLoginRequiredRequest( $params );
		}
		else if ( $name == 'query.list' )
		{
			return $this->getQueryListRequest( $params );
		}
		else if ( $name == 'query.save' )
		{
			return $this->getQueryListRequest( $params );
		}
		else if ( $name == 'query.update' )
		{
			return $this->getQueryListRequest( $params );
		}
		else if ( $name == 'query.show' )
		{
			return $this->getQueryListRequest( $params );
		}
		else
		{
			return $this->getBaseRequest( $params );
		}
	}

	private function getBaseRequest( $params = array() )
	{
		return new BaseRequest( $params );
	}

	private function getLoginRequiredRequest( $params = array() )
	{
		$user_repository = $this->container['orm.em']->getRepository( 'DomainFinder\Entity\User' );
		return new LoginRequiredRequest( $this->getBaseRequest( $params ), $this->container['session'], $user_repository );
	}

	private function getQueryListRequest( $params = array() )
	{
		$application_repo = $this->container['orm.em']->getRepository( 'DomainFinder\Entity\Application' );
		return new ListQueriesRequest( $this->getLoginRequiredRequest( $params ), $application_repo );
	}


}