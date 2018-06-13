<?php
namespace Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Package\ApiResponseException;
use Package\Item;
use Package\ItemCommand;
use Package\ItemsCollection;
use Package\RestClient;
use PHPUnit\Framework\TestCase;

/**
 * Class RestClientTest
 * @package Unit
 */
class RestClientTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnItemsCollection()
    {
        $id = 2;
        $name = 'first item';
        $amount = 45;

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['items' => [['id' => $id, 'name' => $name, 'amount' => $amount]]]))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $restClient = new RestClient('localhost', $client);
        
        $items = $restClient->getItems(true);

        self::assertInstanceOf(ItemsCollection::class, $items);

        $item = $items[0];

        self::assertEquals($id, $item->getId());
        self::assertEquals($name, $item->getName());
        self::assertEquals($amount, $item->getAmount());
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenIncorrectResponseWhenGettingItemsCollection()
    {
        $this->expectException(ApiResponseException::class);

        $mock = new MockHandler([
            new Response(404, ['Content-Type' => 'application/json'])
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $restClient = new RestClient('localhost', $client);

        $restClient->getItems(true);
    }

    /**
     * @test
     */
    public function shouldCreateItem()
    {
        $id = 2;
        $name = 'first item';
        $amount = 45;

        $mock = new MockHandler([
            new Response(201, ['Content-Type' => 'application/json'], json_encode(['id' => $id, 'name' => $name, 'amount' => $amount]))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $restClient = new RestClient('localhost', $client);

        $item = $restClient->createItem(new ItemCommand($name, $amount));

        self::assertInstanceOf(Item::class, $item);
        self::assertEquals($id, $item->getId());
        self::assertEquals($name, $item->getName());
        self::assertEquals($amount, $item->getAmount());
    }

    /**
     * @test
     */
    public function shouldUpdateItem()
    {
        $id = 2;
        $name = 'first item';
        $amount = 45;

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => $id, 'name' => $name, 'amount' => $amount]))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $restClient = new RestClient('localhost', $client);

        $item = $restClient->updateItem($id, new ItemCommand($name, $amount));

        self::assertInstanceOf(Item::class, $item);
        self::assertEquals($id, $item->getId());
        self::assertEquals($name, $item->getName());
        self::assertEquals($amount, $item->getAmount());
    }

    /**
     * @doesNotPerformAssertions
     * @test
     */
    public function shouldDeleteItem()
    {
        $mock = new MockHandler([
            new Response(204, ['Content-Type' => 'application/json'])
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $restClient = new RestClient('localhost', $client);
        $restClient->deleteItem(2);
    }
}
