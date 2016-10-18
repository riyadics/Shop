<?php

namespace Antvel\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return an error  JSON response.
     *
     * @param  string $message
     * @param  string $class
     * @return JSON
     */
    public function respondsWithError(string $message, string $class = 'alert alert-danger')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'class' => 'alert alert-danger'
        ]);
    }

    /**
     * Return a success JSON response.
     *
     * @param  string $message
     * @param  string $redirectTo
     * @return JSON
     */
    public function respondsWithSuccess(string $message, string $redirectTo = '')
    {
        if (trim($message) != '') {
            session()->flash('message', $message);
        }

        return response()->json([
            'success' => true,
            'callback' => $redirectTo, //temporary while refactoring
            'redirectTo' => $redirectTo
        ], 200);
    }

}
