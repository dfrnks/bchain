<?php


namespace Bchain\Blockchain;


class Blockchain {
    
    /**
     * @var Block[]
     */
    private $chain = [];
    
    public function __construct() {
        $newChain = false;
        if(file_exists("blockchain.json")) {
            $newChain = !$this->replaceChain(json_decode(file_get_contents("blockchain.json"), true));
        } else {
            $newChain = true;
        }
        
        if ($newChain){
            $genesis = new Block(["info" => "No princípio criou Deus o céu e a terra. Gênesis 1:1"]);
            $this->addBlock($genesis);
        }
    
    }
    
    /**
     * @param Block $block
     * @return Blockchain
     * @throws \Exception
     */
    public function addBlock(Block $block): Blockchain {
        if (empty($this->chain)) {
            $block->mineBlock();
        } else {
            $block->mineBlock(new Block(end($this->chain), true));
        }
        
        if (!$block->isValid()) {
            throw new \Exception("Block não é valido");
        }
    
        $this->chain[] = $block->getBlock();
        
        $this->setChain();
        
        return $this;
    }
    
    /**
     * @return Block[]
     */
    public function getChain(): array {
        return $this->chain;
    }
    
    private function setChain(array $chain = null): Blockchain {
        if ($chain) {
            $this->chain = $chain;
        }
        
        file_put_contents("blockchain.json", json_encode($this->chain, JSON_PRETTY_PRINT));
        
        return $this;
    }
    
    public function replaceChain($chain) {
        if (count($this->chain) > count($chain)) {
            return false;
        } elseif (!$this->isValidChain($chain)) {
            return false;
        }
        
        $this->setChain($chain);
        
        return true;
    }
    
    private function isValidChain($chain) {
        if (!empty($this->chain) && $chain[0] !== $this->chain[0]) {
            return false;
        }
        
        foreach ($chain as $key => $block) {
            if ($key > 0) {
                $lastBlock = $chain[$key - 1];
                $lastHash = $lastBlock["hash"];
            } else {
                $lastHash = "--";
            }
            
            if ($block["lastHash"] != $lastHash) {
                return false;
            }
    
            $test_block = new Block($block, true);
            
            if (!$test_block->isValid()) {
                return false;
            }
    
            if ($test_block->hash() !== $test_block->getHash()) {
                return false;
            }
        }
        
        return true;
    }

}