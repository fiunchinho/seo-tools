<?php
namespace DomainFinder;

class DatabaseListenerTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$event_arguments = array( 'domain' => 'google.com' , 'query' => 'seo', 'number_of_results' => '23' );
		$this->event = new \Symfony\Component\EventDispatcher\GenericEvent();
		$this->event->setArguments( $event_arguments );

		$this->rank = $this->getMockBuilder( '\DomainFinder\Entity\Rank' )
			->setConstructorArgs( array( 'seo', 'google.com', '20130808', 23 ) )
			->getMock();
	}

	public function testInsertingNewRankingWhenThereIsNoRankingsForThatDateAndOverrideIsDisabled()
	{
		$repository = $this->getMockBuilder( '\DomainFinder\Entity\RankRepository' )->disableOriginalConstructor()->getMock();
		$repository->expects( $this->once() )->method( 'add' );

		$listener 	= new \DomainFinder\Event\DatabaseListener( $repository );
		$listener->onFound( $this->event );
	}

	public function testInsertingNewRankingWhenThereIsNoRankingsForThatDateAndOverrideIsEnabled()
	{
		$repository = $this->getMockBuilder( '\DomainFinder\Entity\RankRepository' )->disableOriginalConstructor()->getMock();
		$repository->expects( $this->once() )->method( 'add' );

		$listener 	= new \DomainFinder\Event\DatabaseListener( $repository );
		$listener->onFound( $this->event );
	}

	public function testInsertingRankingWhenThereIsAlreadyARankingForThatDateAndOverrideIsDisabled()
	{
		$repository = $this->getMockBuilder( '\DomainFinder\Entity\RankRepository' )->disableOriginalConstructor()->getMock();
		$repository->expects( $this->never() )->method( 'add' );
		$repository
			->expects( $this->once() )
			->method( 'findOneBy' )
			->will( $this->returnValue( $this->rank ) );

		$listener 	= new \DomainFinder\Event\DatabaseListener( $repository, false );
		$listener->onFound( $this->event );
	}

	public function testInsertingRankingWhenThereIsAlreadyARankingForThatDateAndOverrideIsEnabled()
	{
		$repository = $this->getMockBuilder( '\DomainFinder\Entity\RankRepository' )->disableOriginalConstructor()->getMock();
		$repository->expects( $this->once() )->method( 'add' );
		$repository
			->expects( $this->once() )
			->method( 'findOneBy' )
			->will( $this->returnValue( $this->rank ) );

		$listener 	= new \DomainFinder\Event\DatabaseListener( $repository, true );
		$listener->onFound( $this->event );
	}

	public function testInsertingRankingWhenThereIsAlreadyARankingForThatDateAndOverrideIsEnabledUpdatesOldRanking()
	{
		$this->rank
			->expects( $this->once() )
			->method( 'setPosition' );

		$repository = $this->getMockBuilder( '\DomainFinder\Entity\RankRepository' )->disableOriginalConstructor()->getMock();
		$repository->expects( $this->once() )->method( 'add' );
		$repository
			->expects( $this->any() )
			->method( 'findOneBy' )
			->will( $this->returnValue( $this->rank ) );

		$listener 	= new \DomainFinder\Event\DatabaseListener( $repository, true );
		$listener->onFound( $this->event );
	}
}