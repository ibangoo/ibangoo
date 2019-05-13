<?php

namespace App\Traits;

use Dingo\Api\Routing\Helpers;
use League\Fractal\TransformerAbstract;

trait ApiHelpers
{
    use Helpers;

    /**
     * @param $message
     * @param $code
     *
     * @return mixed
     */
    public function responseError($message, $code)
    {
        return $this->response->error($message, $code);
    }

    /**
     * 响应服务端错误 500
     *
     * @param $message
     *
     * @return mixed
     */
    public function responseInternal($message)
    {
        return $this->response->errorInternal($message);
    }

    /**
     * 响应客户端错误 422
     *
     * @param $message
     *
     * @return mixed
     */
    public function responseInvalidArgument($message)
    {
        return $this->response->error($message, 422);
    }

    /**
     * 响应客户端错误 403
     *
     * @param $message
     *
     * @return mixed
     */
    public function responseForbidden($message)
    {
        return $this->response->errorForbidden($message);
    }

    /**
     * 响应客户端错误 404
     *
     * @param $message
     *
     * @return mixed
     */
    public function responseNotFound($message)
    {
        return $this->response->errorNotFound($message);
    }

    /**
     * 响应客户端错误 400
     *
     * @param $message
     *
     * @return mixed
     */
    public function responseBadRequest($message)
    {
        return $this->response->errorBadRequest($message);
    }

    /**
     * 响应客户端错误 401
     *
     * @param $message
     *
     * @return mixed
     */
    public function responseUnauthorized($message)
    {
        return $this->response->errorUnauthorized($message);
    }

    /**
     * 响应 JSON 数组
     *
     * @param array  $data
     * @param int    $statusCode
     * @param string $message
     *
     * @return mixed
     */
    public function responseArray($data = [], $statusCode = 200, $message = '请求成功')
    {
        return $this->response->array([
            'data' => $data,
            'message' => $message,
            'status_code' => $statusCode,
        ])->setStatusCode($statusCode);
    }

    /**
     * 响应集合
     *
     * @param $data
     * @param $transformer
     *
     * @return \Dingo\Api\Http\Response
     */
    public function responseCollection($data, TransformerAbstract $transformer)
    {
        return $this->response->collection($data, $transformer);
    }

    /**
     * 响应分页
     *
     * @param $data
     * @param $transformer
     *
     * @return \Dingo\Api\Http\Response
     */
    public function responsePaginator($data, TransformerAbstract $transformer)
    {
        return $this->response->paginator($data, $transformer);
    }

    /**
     * 响应模型
     *
     * @param $data
     * @param $transformer
     *
     * @return \Dingo\Api\Http\Response
     */
    public function responseItem($data, TransformerAbstract $transformer)
    {
        return $this->response->item($data, $transformer);
    }
}