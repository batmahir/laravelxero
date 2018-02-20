<?php

function storeInMemory($data = array())
{
    apc_store($data);
}

function xmlConvertJson($xml_data)
{
    $xml = simplexml_load_string($xml_data);
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);

    return $array;
}


function xeroUrl($add_path)
{
    $endpoint = config('xerobat.xero_main_data_endpoint').$add_path;
    return $endpoint;
}


