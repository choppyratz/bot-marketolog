<?php

class ShopHtmlAnalyser
{
    public  $db;
    public function  __construct($db)
    {
        $this->db = $db;
    }

    public function start($link) {
        $url_components = parse_url($link);
        if (!(new Shop($this->db))->isSupportedStore($url_components['host'])) {
            return ['error' => 'unknown shop'];
        }
        $html = Parser::getPage([
            "url" => $link
        ]);
        switch ($url_components['host']){
            case 'oz.by':
                return $this->OzParser($html);
                break;
            case 'www.wildberries.by':
                return $this->WildBerriesParser($html);
                break;
            case 'bagz.by':
                return $this->BagzParser($html);
                break;
        }


    }

    public function OzParser($thml) {
        if (!empty($thml["data"]["content"])){
            $content = $thml["data"]["content"];
            phpQuery::newDocument($content);
            $price = pq(".b-product-control__row")->find(".b-product-control__text")->text();
            $i = 0;
            $tr_price = '';
            while($price[$i] !== "."){
                $tr_price = $tr_price . $price[$i];
                $i++;
            }
            $title = pq(".b-product-title__heading")->find("h1")->text();
            $price = str_replace(",",".",substr($tr_price,0,-8));
            phpQuery::unloadDocuments();
            return [
                "title" => $title,
                "price" => $price
            ];
        }else {
            return null;
        }
    }

    public function WildBerriesParser($html) {
        if (!empty($html["data"]["content"])){
            $content = $html["data"]["content"];
            phpQuery::newDocument($content);
            $price_n = pq(".add-discount-text")->find(".add-discount-text-price")->text();
            $brand = pq(".brand-and-name")->find(".brand")->text();
            $name = pq(".brand-and-name")->find(".name")->text();
            $title = $brand . "/" . $name;
            $price = "";

            $i = 0;
            while ($price_n[$i] !== "{"){
                $price = $price . $price_n[$i];
                $i++;
            }

            $price_rub = substr($price,0,-12);
            $price_kop = substr($price,0,-5);
            $i = strlen($price_kop);
            $price_n = "";
            while ($price_kop[$i] !== " "){
                $price_n = $price_n . $price_kop[$i];
                $i--;
            }
            $price_kop = $price_n;
            $price= $price_rub . "." . $price_kop;
            phpQuery::unloadDocuments();
            return [
                "title" => $title,
                "price" => $price
            ];
        }
    }

    public function BagzParser($html) {
        if (!empty($html["data"]["content"])){
            $content =$html["data"]["content"];
            phpQuery::newDocument($content);
            $title = pq(".product_title")->find("h1")->text();
            $price = pq(".product_curprice")->text();
            $price = substr($price,0,-4);
            phpQuery::unloadDocuments();
            return [
                "title" => $title,
                "price" => $price
            ];
        }else {
            return null;
        }

    }
}
