<?php

function _env($key) {
    if(isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    return  null;
}
function _envs() {
    return $_ENV;
}


