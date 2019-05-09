<?php

namespace App\Traits;

trait ResponsesAndRedirects
{
    /**
     *
     * @param $data
     * @param $code
     * @param $message
     * @param $httpCode
     *
     * @return mixed
     */
    public function responseJson($data, $code, $message, $httpCode = 0)
    {
        return response()->json(compact('data', 'code', 'message'), $httpCode = $httpCode ?: $code);
    }

    /**
     * @param $message
     *
     * @return mixed
     */
    public function redirectBackWithSuccess($message)
    {
        return $this->redirectBack()->with('success', $message);
    }

    /**
     * @return mixed
     */
    public function redirectBack()
    {
        return redirect()->back();
    }

    /**
     * @param $message
     *
     * @return mixed
     */
    public function redirectBackWithErrors($message)
    {
        return $this->redirectBack()->withErrors($message);
    }

    /**
     * @param       $route
     * @param array $parameters
     *
     * @return mixed
     */
    public function redirectRoute($route, $parameters = [])
    {
        return redirect()->route($route, $parameters);
    }

    /**
     * @param $message
     * @param $route
     * @param $parameters
     *
     * @return mixed
     */
    public function redirectRouteWithSuccess($message, $route, $parameters = [])
    {
        return $this->redirectRoute($route, $parameters)->with('success', $message);
    }

    /**
     * @param $message
     * @param $route
     * @param $parameters
     *
     * @return mixed
     */
    public function redirectRouteWithErrors($message, $route, $parameters = [])
    {
        return $this->redirectRoute($route, $parameters)->withErrors($message);
    }
}
