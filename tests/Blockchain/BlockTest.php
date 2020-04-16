<?php

namespace Blockchain;

use Bchain\Blockchain\Block;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase {
    public function testNewBlock() {
        $block = new Block();
        
        $block
            ->setData(["foo" => "bar"])
            ->setLastHash("last-hash")
            ->setHash("hash")
            ->setNonce("AF0")
            ->setDifficulty("000");
        
        $block = $block->getBlock();
        
        $this->assertEquals(["foo" => "bar"], $block["Data"]);
        $this->assertEquals("last-hash", $block["Last_Hash"]);
        $this->assertEquals("hash", $block["Hash"]);
        $this->assertEquals("AF0", $block["Nonce"]);
        $this->assertEquals("000", $block["Difficulty"]);
    }
}
