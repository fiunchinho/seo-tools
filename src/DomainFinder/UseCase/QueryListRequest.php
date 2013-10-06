<?php
namespace DomainFinder\UseCase;

class QueryListRequest extends BaseRequest
{
	private $request;

	public function __construct( BaseRequest $request )
	{
		$this->request = $request;
		parent::__construct();
	}
}