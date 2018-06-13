<?php
namespace Package;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class RestClient
 * @package SubDir
 */
class RestClient
{
    /** @var Client */
    private $client;

    /** @var string */
    private $host;

    /**
     * @param string $host
     * @param Client $client
     */
    public function __construct(string $host, Client $client)
    {
        $this->host = $host;
        $this->client = $client;
    }

    /**
     * @param bool $available
     * @return ItemsCollection
     * @throws ApiResponseException
     */
    public function getItems(bool $available = false): ItemsCollection
    {
        $url = $this->host . "/api/items?filter[available]=" . ($available ? 'true' : 'false');
        try {
            $result = $this->client->request('GET', $url);
        } catch (GuzzleException $e) {
            throw new ApiResponseException('Incorrect response');
        }

        if (200 !== $result->getStatusCode()) {
            throw new ApiResponseException('Incorrect response');
        }

        $collection = new ItemsCollection();
        $items = json_decode($result->getBody()->getContents(), true);

        if (empty($items['items'])) {
            return $collection;
        }

        foreach ($items['items'] as $item) {
            $collection->push(new Item($item['id'], $item['name'], $item['amount']));
        }

        return $collection;
    }

    /**
     * @param ItemCommand $command
     * @return Item
     * @throws ApiResponseException
     */
    public function createItem(ItemCommand $command): Item
    {
        $url = $this->host . "/api/items";
        $result = $this->client->post($url, [
            RequestOptions::JSON => $command->toArray()
        ]);

        if (201 !== $result->getStatusCode()) {
            throw new ApiResponseException('Incorrect response');
        }

        $item = json_decode($result->getBody()->getContents(), true);
        return new Item($item['id'], $item['name'], $item['amount']);
    }

    /**
     * @param int $id
     * @param ItemCommand $command
     * @return Item
     * @throws ApiResponseException
     */
    public function updateItem(int $id, ItemCommand $command): Item
    {
        $url = $this->host . "/api/items/" . $id;
        $result = $this->client->put($url, [
            RequestOptions::JSON => $command->toArray()
        ]);

        if (200 !== $result->getStatusCode()) {
            throw new ApiResponseException('Incorrect response');
        }

        $item = json_decode($result->getBody()->getContents(), true);
        return new Item($item['id'], $item['name'], $item['amount']);
    }

    /**
     * @param int $id
     * @throws ApiResponseException
     */
    public function deleteItem(int $id) : void
    {
        $url = $this->host . "/api/items/" . $id;
        $result = $this->client->delete($url);

        if (204 !== $result->getStatusCode()) {
            throw new ApiResponseException('Incorrect response');
        }
    }
}
