<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Quarantine;
use PhraseanetSDK\EntityManager;

class QuarantineTest extends RepositoryTestCase
{

    public function testFindByOffset()
    {
        $client = $this->getClient($this->getSampleResponse('repository/quarantine/findAll'));
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $items = $quarantineRepository->findByOffset(0, 10);

        $this->assertIsCollection($items);

        foreach ($items as $item) {
            $this->checkQuarantine($item);
        }
    }

    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/quarantine/findById'));
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $item = $quarantineRepository->findById(1);

        $this->checkQuarantine($item);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByIdRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findById(1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\NotFoundException
     */
    public function testFindByOffsetByNameException()
    {
        $client = $this->getClient($this->getSampleResponse('repository/quarantine/findAll'), 404);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindByOffsetdException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByOffsetdQueryRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('bad'), 200);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByOffsetdRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'), 200);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }
}
