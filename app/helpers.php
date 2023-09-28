<?php


if (!function_exists('get_automira')) {

    /**
     * Make get request to automira 1C server
     * @param string $path
     * @return array|mixed
     */
    function get_automira(string $path): mixed
    {
        return Http::automira()->get($path)->json();
    }
}


if (!function_exists('post_automira')) {

    /**
     * Make get request to automira 1C server
     * @param string $path
     * @param array|null $data
     * @return array|mixed
     */
    function post_automira(string $path, ?array $data = null): mixed
    {
        return Http::automira()->post($path, $data)->json();
    }
}

