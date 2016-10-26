<?php

if (! function_exists('resolveTrans')) {
    /**
     * Resolve trans for a given key.
     *
     * @param  string $key
     * @return Mixed
     */
    function resolveTrans(string $key)
    {
        $default = trans('antvel::' . $key);

        if (! is_string($default)) {
            return $default;
        }

        return trans($key);
    }
}