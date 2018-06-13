<?php
namespace Package;

use Gamez\Illuminate\Support\TypedCollection;

/**
 * Class ItemsCollection
 * @package SubDir
 */
class ItemsCollection extends TypedCollection
{
    protected static $allowedTypes = [Item::class];
}
