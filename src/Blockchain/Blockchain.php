<?php


namespace Bchain\Blockchain;


class Blockchain {
    
    /**
     * @var Block[]
     */
    private $chain = [];
    
    private $nodes = [];
    
    private $node;
    
    private $bc_file;
    
    private $nd_file;
    
    public function __construct($node, $node_tutor) {
        $this->node = $node;
        
        $this->bc_file = __DIR__ . "/../../chain/blockchain." . $node . ".json";
        $this->nd_file = __DIR__ . "/../../chain/nodes." . $node . ".json";
    
        file_put_contents($this->nd_file, json_encode([], JSON_PRETTY_PRINT));
        
        if(file_exists($this->bc_file)) {
            $newChain = !$this->replaceChain(json_decode(file_get_contents($this->bc_file), true));
        } else {
            $newChain = true;
        }
        
        if ($newChain){
            $genesis = new Block(["info" => "No princípio criou Deus o céu e a terra. Gênesis 1:1"]);
            $genesis->setTimestamp('1587150686.2415');
            $this->addBlock($genesis);
        }
        
        if ($this->node !== $node_tutor) {
            $this->loadNodesNetwork($node_tutor);
    
            foreach ($this->getNodes() as $item) {
                $this->sync($item);
            }
        }
    }
    
    public function loadNodesNetwork($node) {
        // Diz para o node principal que esta on
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL            => $node . "/node",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "PUT",
            CURLOPT_POSTFIELDS     => json_encode(["address" => $this->node]),
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json"
            ],
        ]);
    
        $nodes = curl_exec($curl);
    
        curl_close($curl);
    
        $nodes = json_decode($nodes, true) ? : [];
        
        if (empty($nodes)) {
            return false;
        }
    
        $this->setNode($node);
    
        foreach ($nodes as $item) {
            if ($this->setNode($item)) {
                if(!$this->loadNodesNetwork($item)){
                    $this->unsetNode($item);
                }
            }
        }
        
        return true;
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
        
        file_put_contents($this->bc_file, json_encode($this->chain, JSON_PRETTY_PRINT));
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getNodes(): array {
        if(file_exists($this->nd_file)) {
            $this->nodes = json_decode(file_get_contents($this->nd_file), true);
        }
        
        return array_values($this->nodes);
    }
    
    /**
     * @param string $node
     * @return bool
     */
    public function setNode(string $node): bool {
        if(file_exists($this->nd_file)) {
            $this->nodes = json_decode(file_get_contents($this->nd_file), true);
        }

        if ($node && !array_key_exists($node, $this->nodes) && $node != $this->node) {
            $this->nodes[$node] = $node;
            
            ksort($this->nodes);
    
            file_put_contents($this->nd_file, json_encode($this->nodes, JSON_PRETTY_PRINT));
    
            echo "-- Conectado no node {$node} --\n";
            
            return true;
        }
        
        return false;
    }
    
    public function unsetNode(string $node) {
        if(file_exists($this->nd_file)) {
            $this->nodes = json_decode(file_get_contents($this->nd_file), true);
        }
        
        unset($this->nodes[$node]);
    
        ksort($this->nodes);
    
        file_put_contents($this->nd_file, json_encode($this->nodes, JSON_PRETTY_PRINT));
    
        return true;
    }
    
    public function sync($node) {
        // Diz para o node principal que esta on
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL            => $node . "/chain",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json"
            ],
        ]);
    
        $bc = curl_exec($curl);
    
        curl_close($curl);
    
        $bc = json_decode($bc, true) ? : [];
        
        $this->replaceChain($bc);
        
        return true;
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