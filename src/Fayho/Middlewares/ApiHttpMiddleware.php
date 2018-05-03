<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Middlewares;

use Fayho\Base\Result;
use Fayho\Di\FayhoDi;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Exception\Exception;
use Swoft\Http\Message\Middleware\MiddlewareInterface;
use Swoft\Core\RequestContext;
use Fayho\Base\StatusCode;
use Fayho\Services\Api\Interfaces\ApiInterface;
use Fayho\Util\Response;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use Fayho\Services\Api\Constants as ServicesConstApi;

/**
 * Class ApiHttpMiddleware
 * @package Fayho\Middlewares
 *
 * @Bean()
 *
 * @author birdylee <birdylee_cn@163.com>
 * @since 2018.05.03
 */
class ApiHttpMiddleware implements MiddlewareInterface
{
    /**
     *
     * @Reference("api")
     * @var ApiInterface
     */
    private $apiService;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ('OPTIONS' === $request->getMethod()) {
            return $this->configResponse(\response());
        }

        $checkRs = $this->checkRequest($request);
        if (!StatusCode::isSuccess($checkRs)) {
            return response()->json($checkRs);
        }

        $response = $handler->handle($request);

        return $response->withAddedHeader('User-Middleware', 'success');
    }

    /**
     * 跨域处理
     *
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface|\Swoft\Http\Message\Server\Response
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    protected function configResponse(ResponseInterface $response): ResponseInterface
    {
        $allowHeaders = [
            'X-Requested-With',
            'Content-Type',
            'Accept',
            'Origin',
            'Authorization',
            ServicesConstApi::REQUEST_VALID_APPID,
            ServicesConstApi::REQUEST_VALID_SIGN,
            ServicesConstApi::REQUEST_VALID_TOKEN,
            ServicesConstApi::REQUEST_VALID_VERSION,
        ];
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', implode(',', $allowHeaders));
    }

    /**
     * 请求参数校验
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     *
     * @throws
     *
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.04.27
     */
    protected function checkRequest(ServerRequestInterface $request): array
    {
        //校验 ServicesConstApi::REQUEST_VALID_VERSION
        if (!$request->hasHeader(ServicesConstApi::REQUEST_VALID_VERSION)) {
            return StatusCode::handleReturn(50002);
        }

        //校验 ServicesConstApi::REQUEST_VALID_APPID
        if (!$request->hasHeader(ServicesConstApi::REQUEST_VALID_APPID)) {
            return StatusCode::handleReturn(50001);
        }
        $appid = $request->getHeader(ServicesConstApi::REQUEST_VALID_APPID)[0];
        $appInfoRs = $this->apiService->getApp($appid);
        if (!StatusCode::isSuccess($appInfoRs)) {
            return $appInfoRs;
        }

        //校验 ServicesConstApi::REQUEST_VALID_TOKEN
        if (!$request->hasHeader(ServicesConstApi::REQUEST_VALID_TOKEN)) {
            return StatusCode::handleReturn(50003);
        }

        //校验 ServicesConstApi::REQUEST_VALID_SIGN
        if (!$request->hasHeader(ServicesConstApi::REQUEST_VALID_SIGN)) {
            return StatusCode::handleReturn(50004);
        }

        return StatusCode::handleReturn(200);
    }
}
