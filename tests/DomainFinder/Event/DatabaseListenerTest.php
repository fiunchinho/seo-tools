<?php
namespace DomainFinder;

use DomainFinder\Event\DatabaseListener;

class DatabaseListenerTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->rank_repository = $this->getMockBuilder( '\DomainFinder\Entity\RankRepositoryInterface' )
			->disableOriginalConstructor()
			->getMock();

		$domain = $this->getMockBuilder( '\DomainFinder\Entity\Domain' )
			->setConstructorArgs( array( 'google.com' ) )
			->getMock();

		$this->rank = $this->getMockBuilder( '\DomainFinder\Entity\Rank' )
			->setConstructorArgs( array( $domain, new \DateTime( '2013-08-08' ), 23 ) )
			->getMock();

		$event_arguments 	= array( 'domain' => $domain , 'query' => 'seo', 'number_of_results' => '23' );
		$this->event 		= new \Symfony\Component\EventDispatcher\GenericEvent();
		$this->event->setArguments( $event_arguments );
	}

	public function testInsertingNewRankingWhenThereIsNoRankingsForThatDateAndOverrideIsDisabled()
	{
		$this->itShouldSaveTheRankingInTheRepository( $this->rank_repository );

		$listener = new DatabaseListener( $this->rank_repository );
		$listener->onFound( $this->event );
	}

	public function testInsertingNewRankingWhenThereIsNoRankingsForThatDateAndOverrideIsEnabled()
	{
		$this->itShouldSaveTheRankingInTheRepository( $this->rank_repository );

		$listener = new DatabaseListener( $this->rank_repository, DatabaseListener::OVERRIDE_EXISTING_RANKING );
		$listener->onFound( $this->event );
	}

	public function testInsertingRankingWhenThereIsAlreadyARankingForThatDateAndOverrideIsDisabled()
	{
		$this->givenRankingsForThisDate();
		$this->itShouldNotSaveTheRankingInTheRepository( $this->rank_repository );

		$listener = new DatabaseListener( $this->rank_repository );
		$listener->onFound( $this->event );
	}

	public function testInsertingRankingWhenThereIsAlreadyARankingForThatDateAndOverrideIsEnabledUpdatesOldRanking()
	{
		$this->givenRankingsForThisDate();
		$this->itShouldUpdateThePositionInRanking();
		$this->itShouldSaveTheRankingInTheRepository( $this->rank_repository );

		$listener = new DatabaseListener( $this->rank_repository, DatabaseListener::OVERRIDE_EXISTING_RANKING );
		$listener->onFound( $this->event );
	}

	private function givenRankingsForThisDate()
	{
		$this->rank_repository
			->expects( $this->any() )
			->method( 'findOneBy' )
			->will( $this->returnValue( $this->rank ) );
	}

	private function itShouldUpdateThePositionInRanking()
	{
		$this->rank
			->expects( $this->once() )
			->method( 'setPosition' );
	}

	private function itShouldSaveTheRankingInTheRepository( $repository )
	{
		$repository->expects( $this->once() )
			->method( 'add' );
	}

	private function itShouldNotSaveTheRankingInTheRepository( $repository )
	{
		$repository->expects( $this->never() )
			->method( 'add' );
	}
}