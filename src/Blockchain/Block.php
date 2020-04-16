<?php


namespace Bchain\Blockchain;


class Block {
    /**
     * @var string
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
     * @var string
     */
    private $difficulty;

    public function __construct() {
        $mc = explode(" ", microtime())[0];
        
        $this->setTimestamp(time() . "." . explode('.', $mc)[1]);
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
        return $this->lastHash;
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
     * @param string $difficulty
     * @return Block
     */
    public function setDifficulty(string $difficulty): Block {
        $this->difficulty = $difficulty;
        
        return $this;
    }
    
    public function getBlock() : array {
        return [
            "timestamp"  => $this->getTimestamp(),
            "Date"       => date("c", $this->getTimestamp()),
            "Last_Hash"  => $this->getLastHash(),
            "Hash"       => $this->getHash(),
            "Nonce"      => $this->getNonce(),
            "Difficulty" => $this->getDifficulty(),
            "Data"       => $this->getData()
        ];
    }
}