<?php
namespace Package;

/**
 * Class ItemCommand
 * @package SubDir
 */
class ItemCommand
{
    /** @var string */
    private $name;

    /** @var int */
    private $amount;

    /**
     * @param string $name
     * @param int $amount
     */
    public function __construct(string $name, int $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'amount' => $this->getAmount()
        ];
    }
}
