<?php


use App\Exceptions\AutomiraException;

if (!function_exists('get_automira')) {

    /**
     * Make get request to automira 1C server
     * @param string $path
     * @return array|mixed
     * @throws AutomiraException
     */
    function get_automira(string $path): mixed
    {
        $response = Http::automira()->get($path);
        if ($response->status() === 204) {
            throw new AutomiraException();
        }

        return $response->json();
    }
}


if (!function_exists('post_automira')) {

    /**
     * Make get request to automira 1C server
     * @param string $path
     * @param array|null $data
     * @return array|mixed
     * @throws AutomiraException
     */
    function post_automira(string $path, ?array $data = null): mixed
    {
        $response = Http::automira()->post($path, $data);


        if ($response->status() === 204) {
            throw new AutomiraException();
        }

        return $response->json();
    }
}
