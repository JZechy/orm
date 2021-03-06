<?php

/**
 * @testCase
 * @dataProvider ../../../sections.ini
 */

namespace NextrasTests\Orm\Integration\Mapper;

use Mockery;
use NextrasTests\Orm\BookCollection;
use NextrasTests\Orm\DataTestCase;
use Tester\Assert;
use Tester\Environment;

$dic = require_once __DIR__ . '/../../../bootstrap.php';


class DbalPersistAutoupdateMapperTest extends DataTestCase
{
	public function setUp()
	{
		parent::setUp();
		if ($this->section === 'array') {
			Environment::skip('Test is only for Dbal mapper.');
		}
	}


	public function testInsertAndUpdate()
	{
		$bookCollection = new BookCollection();
		$bookCollection->name = 'Test Collection 1';

		Assert::null($bookCollection->updatedAt);
		$this->orm->bookColletions->persistAndFlush($bookCollection);

		Assert::type(\DateTime::class, $bookCollection->updatedAt);
		$old = $bookCollection->updatedAt;

		sleep(1);
		$bookCollection->name .= '1';
		$this->orm->bookColletions->persistAndFlush($bookCollection);

		Assert::type(\DateTime::class, $bookCollection->updatedAt);
		$new = $bookCollection->updatedAt;
		Assert::notEqual($old->format($old::ISO8601), $new->format($new::ISO8601));
	}
}


$test = new DbalPersistAutoupdateMapperTest($dic);
$test->run();
