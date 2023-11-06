<?php

$bag = [];

function fillTheBag(&$bag) {
    for ($i = 0; $i < 10; $i++)  {
        $bag[] = $i;
    }
}

fillTheBag($bag); // Call the function to fill the $bag array


print_r($bag);
