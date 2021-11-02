<?php

function create($class, $atrributes = [], $times = null)
{
    return factory($class, $times)->create($atrributes);
}

function make($class, $atrributes = [], $times = null)
{
    return factory($class, $times)->make($atrributes);
}