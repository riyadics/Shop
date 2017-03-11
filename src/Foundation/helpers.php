<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
