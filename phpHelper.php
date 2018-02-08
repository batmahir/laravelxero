<?php

function storeInMemory($data = array())
{
    apc_store($data);
}