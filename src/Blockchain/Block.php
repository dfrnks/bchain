<?php


namespace Bchain\Blockchain;


class Block {
    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var string
     */
    private $lastHash;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string
     */
    private $nonce;

    /**
     * @var int
     */
    private $difficulty = 3;
    
    public function __construct(array $data) {
        $this->data = $data;
    }
    
    /**
     * @return string
     */
    public function getTimestamp(): string {
        return $this->timestamp;
    }
    
    /**
     * @param string $timestamp
     * @return Block
     */
    public function setTimestamp(string $timestamp): Block {
        $this->timestamp = $timestamp;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLastHash(): string {
        return $this->lastHash ? : '--';
    }
    
    /**
     * @param string $lastHash
     * @return Block
     */
    public function setLastHash(string $lastHash): Block {
        $this->lastHash = $lastHash;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getHash(): string {
        return $this->hash;
    }
    
    /**
     * @param string $hash
     * @return Block
     */
    public function setHash(string $hash): Block {
        $this->hash = $hash;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * @param $data
     * @return Block
     */
    public function setData($data): Block {
        $this->data = $data;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getNonce(): string {
        return $this->nonce;
    }
    
    /**
     * @param string $nonce
     * @return Block
     */
    public function setNonce(string $nonce): Block {
        $this->nonce = $nonce;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getDifficulty(): int {
        return $this->difficulty;
    }
    
    /**
     * @param int $difficulty
     * @return Block
     */
    public function setDifficulty(int $difficulty): Block {
        $this->difficulty = $difficulty;
        
        return $this;
    }
    
    public function getBlock() : array {
        return [
            "Timestamp"  => $this->getTimestamp(),
            "Date"       => date("c", $this->getTimestamp()),
            "Last_Hash"  => $this->getLastHash(),
            "Hash"       => $this->getHash(),
            "Nonce"      => $this->getNonce(),
            "Difficulty" => $this->getDifficulty(),
            "Data"       => $this->getData()
        ];
    }
    
    public function mineBlock(Block $lastBlock = null) {
        $this->nonce = -1;
        
        do {
            $this->nonce++;
            // Verifica a dificuldade para aumentar ou diminuir
            
            $this->timestamp = $this->now();
            
            if ($lastBlock) {
                $this->setLastHash($lastBlock->getHash());
                $this->adjustDifficulty($lastBlock);
            }
            
            $this->hash = $this->hash();
        } while (!$this->isValid());
        
        return $this->getBlock();
    }
    
    public function isValid() {
        return substr($this->hash, $this->difficulty, $this->difficulty) === str_repeat('0', $this->difficulty);
    }
    
    private function now() {
        return  microtime(true);
    }
    
    private function hash() {
        $data = [
            "Timestamp"  => $this->getTimestamp(),
            "Data"       => $this->getData(),
            "Last_Hash"  => $this->getLastHash(),
            "Nonce"      => $this->getNonce(),
            "Difficulty" => $this->getDifficulty(),
        ];
    
        return hash("sha256", json_encode($data));
    }
    
    private function adjustDifficulty(Block $lastBlock) {
        // + 10000 = 1s
        $mine_rate = 10000 / 2; //500ms
        
        $this->difficulty = ($lastBlock->timestamp  * 10000) + $mine_rate > ($this->timestamp  * 10000) ? $lastBlock->difficulty + 1 : $lastBlock->difficulty - 1;
    }
}