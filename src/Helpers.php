<?php

if (! function_exists('setting')) {
    function setting($key) {
        return \Alaame\Setting\Facades\AMESetting::setting($key);
    }
}
