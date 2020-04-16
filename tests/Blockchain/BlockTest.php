<?php

namespace Blockchain;

use Bchain\Blockchain\Block;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase {
    public function testNewBlock() {
        $block1 = new Block(["foo" => "bar"]);
        
        $block1->mineBlock();
        
        $dataBlock = $block1->getBlock();
        
        $this->assertEquals(["foo" => "bar"], $dataBlock["Data"]);
        $this->assertEquals("--", $dataBlock["Last_Hash"]);
        $this->assertEquals(3, $dataBlock["Difficulty"]);
        
        $this->assertTrue($block1->isValid());
    
        $block2 = new Block(["foo" => "bar"]);
        $block2->mineBlock($block1);
    
        $block3 = new Block(["foo" => "bar"]);
        $block3->mineBlock($block2);
    
        $block4 = new Block(["foo" => "bar"]);
        $block4->mineBlock($block3);
    
        $block5 = new Block(["foo" => "bar"]);
        $block5->mineBlock($block4);
    
        $block6 = new Block(["foo" => "bar"]);
        $block6->mineBlock($block5);
    }
}
