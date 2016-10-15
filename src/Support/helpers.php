<?php

if (! function_exists('responseWithError')) {
    function responseWithError($message, string $class = 'alert alert-danger')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'class' => 'alert alert-danger'
        ]);
    }
}