<?php

namespace Tests\Unit\Cache;

use ErrorException;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Cache\File\FileCacheItemPool;

set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

class FileCachePoolTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testGetItem()
    {
        $pool = new FileCacheItemPool();
        $item = $pool->getItem('test-key');
        $this->assertInstanceOf(\Sumup\Api\Cache\File\FileCacheItem::class, $item);

        $this->expectException(\Psr\Cache\InvalidArgumentException::class);
        $pool->getItem('test{key');
    }

    public function testGetItems()
    {
        $pool = new FileCacheItemPool();
        $items = $pool->getItems();
        $this->assertSameSize($items, []);

        $item = $pool->getItem('test-key');
        $pool->save($item);
        $items = $pool->getItems(['test-key']);
        $this->assertCount(1, $items);
    }

    public function testHasItem()
    {
        $pool = new FileCacheItemPool();
        $item = $pool->getItem('test-has-item');
        $item->set('test');
        $pool->save($item);
        $this->assertTrue($pool->hasItem('test-has-item'));
    }

    public function testClearPool()
    {
        $pool = new FileCacheItemPool();
        $this->assertTrue($pool->clear());
    }

    public function testDeleteItem()
    {
        $pool = new FileCacheItemPool();
        $item = $pool->getItem('test-delete');
        $item->set('test');
        $pool->save($item);
        $this->assertTrue($pool->deleteItem('test-delete'));
    }

    public function testDeleteItems()
    {
        $pool = new FileCacheItemPool();

        $item1 = $pool->getItem('test-delete-1');
        $item1->set('test');
        $pool->save($item1);

        $item2 = $pool->getItem('test-delete-2');
        $item2->set('test');
        $pool->save($item2);

        $this->assertTrue($pool->deleteItems(['test-delete-1', 'test-delete-2']));
    }

    public function testSaveItem()
    {
        $pool = new FileCacheItemPool();
        $item = $pool->getItem('test-save');
        $this->assertTrue($pool->save($item));
        $this->assertFalse($pool->getItem('test-save')->isHit());

        $this->assertInstanceOf(\Sumup\Api\Cache\File\FileCacheItem::class, $item->set('test'));
        $this->assertTrue($pool->save($item));
        $this->assertTrue($pool->getItem('test-save')->isHit());

        $pool = new FileCacheItemPool('/');
        $item = $pool->getItem('test-save-access');
        $item->set('test');
        $this->expectException(\ErrorException::class);
        $pool->save($item);
    }

    public function testSaveDeferredItems()
    {
        $pool = new FileCacheItemPool();
        $item = $pool->getItem('test-deferred');
        $this->assertTrue($pool->saveDeferred($item));
    }

    public function testCommitDeferredItems()
    {
        $pool = new FileCacheItemPool();

        $item1 = $pool->getItem('test-commit-1');
        $item1->set('test');
        $pool->saveDeferred($item1);

        $item2 = $pool->getItem('test-commit-2');
        $item2->set('test');
        $pool->saveDeferred($item2);

        $this->assertTrue($pool->commit());

        $item = $pool->getItem('test-commit-1');
        $this->assertTrue($item->isHit());
    }

    public function tearDown()
    {
        $pool = new FileCacheItemPool();
        $pool->clear();
    }
}
