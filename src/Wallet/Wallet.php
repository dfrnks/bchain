<?php

namespace Bchain\Wallet;

use kornrunner\Secp256k1;
use kornrunner\Serializer\HexSignatureSerializer;
use kornrunner\Signature\Signature;
use Mdanter\Ecc\Crypto\Key\PrivateKey;
use Mdanter\Ecc\Crypto\Key\PublicKey;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;

class Wallet {
    
    /**
     * @var int 0.000 000 000 001
     */
    private $balance;
    
    private $privateKey;
    
    private $publicKey;
    
    public function __construct($privateKey = null, $publicKey = null) {
        if ($privateKey) {
            $this->privateKey = $privateKey;
        }
        
        if ($publicKey) {
            $this->publicKey = $publicKey;
        }
    }
    
    public function createKeys() {
    
        $words = "truvat amyrin cuss tulare redcoat reckla arratel cladose deedbox receipt overwin quasi kan bout joe rompish";
        
        $words = hash("sha256", hash("sha512", $words . " " . "1"));
        var_dump($words);
        
        $words = 0x80 . $words . substr(hash("sha256", hash("sha256", $words)), 0, 4);
        
        var_dump($words);

//        // openssl ecparam -genkey -name secp256k1 -text -noout -outform DER | xxd -p -c 1000 | sed 's/41534e31204f49443a20736563703235366b310a30740201010420/PrivKey: /' | sed 's/a00706052b8104000aa144034200/\'$'\nPubKey: /'
//
        
//        $adapter = EccFactory::getAdapter();
//        $generator = EccFactory::getNistCurves()->generator384();
//        $pointer = EccFactory::getNistCurves()->curve256();
//        $private = $generator->createPrivateKey();
//
//        $pk = new PublicKey($adapter, $generator, $pointer);
//
//        var_dump($private);
////        $prcryp = new PrivateKey($adapter, $generator, $private->getSecret());
//
////        var_dump($prcryp->createExchange()->createMultiPartyKey());
//
//        $derPrivateSerializer = new DerPrivateKeySerializer($adapter);
//        $der = $derPrivateSerializer->serialize($private);
//        echo sprintf("DER encoding:\n%s\n\n", base64_encode($der));
//
////        $derPublicSerializer = new DerPublicKeySerializer($adapter);
////        $der = $derPublicSerializer->serialize($private->getPublicKey());
////        echo sprintf("DER encoding:\n%s\n\n", base64_encode($der));
//
//        $pemSerializer = new PemPrivateKeySerializer($derPrivateSerializer);
//        $pem = $pemSerializer->serialize($private);
//        echo sprintf("PEM encoding:\n%s\n\n", $pem);
    

    }
    
    public function sign($message) {
        $secp256k1 = new Secp256k1();
    
        $signature = $secp256k1->sign(hash("sha256", json_encode($message)), $this->privateKey);
        
        return $signature->toHex();
    }
    
    public function verify($message, $hexSignature) {
        $secp256k1 = new Secp256k1();
        
        $signature = new HexSignatureSerializer();
        $signature = $signature->parse($hexSignature);
    
        return $secp256k1->verify(hash("sha256", json_encode($message)), $signature, $this->publicKey);
    }
}