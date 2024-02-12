<?php
namespace App\Utils;

use App\Entity\Coin;

class CoinUtils{

    public static function parse($jsonData): Coin{
        $coinData = $jsonData;

        $coinId = $coinData['id'];
        $coinAbbr = $coinData['symbol'];
        $coinName = $coinData['name'];
        $coinPrice = $coinData['price_usd'];

        $coin = new Coin();

        $coin->setCoinId($coinId);
        $coin->setName($coinName);
        $coin->setAbbr($coinAbbr);
        $coin->setPrice($coinPrice);

        return $coin;
    }
}