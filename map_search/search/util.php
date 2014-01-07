<?php

function die_with($message) {
    die(json_encode(array(
        'error' => $message
    )));
}
