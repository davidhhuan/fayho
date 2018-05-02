<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Middlewares;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;
use Swoft\Core\RequestContext;
use Fayho\Base\StatusCode;
use Fayho\Services\Api\Interfaces\ApiInterface;
use Fayho\Util\Response;

/**
 * Class ApiHttpMiddleware - Custom middleware
 * @Bean()
 * @package App\Middlewares
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

        $this->checkRequest();

        // before request handle

        $response = $handler->handle($request);

        // after request handle

        return $response->withAddedHeader('User-Middleware', 'success');
    }

    /**
     * 跨域处理
     *
     * @param ResponseInterface $response
     * @return static
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
            'Request-Valid-Appid',
            'Request-Valid-Version',
            'Request-Valid-Token',
            'Request-Valid-Sign',
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
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.04.27
     */
    protected function checkRequest(ServerRequestInterface $request): array
    {
        if (!$request->hasHeader('Request-Valid-Version')) {
            return RequestContext::getResponse()->withStatus(200, StatusCode::handleReturnJson(50002));
        }
        
        if (!$request->hasHeader('Request-Valid-Appid')) {
            return RequestContext::getResponse()->withStatus(200, StatusCode::handleReturnJson(50001));
        }
        $appid = $request->getHeader('Request-Valid-Appid');
        $appInfoRs = $this->apiService->geApp($appid);
        if (!StatusCode::isSuccess($appInfoRs)) {
            return RequestContext::getResponse()
                ->withStatus(
                    200, 
                    Response::returnJson(
                        $appInfoRs['status'], 
                        $appInfoRs['msg'], 
                        $appInfoRs['result']
                    )
                ); 
        }

        if (!$request->hasHeader('Request-Valid-Token')) {
            return RequestContext::getResponse()->withStatus(200, StatusCode::handleReturnJson(50003));
        }

        if (!$request->hasHeader('Request-Valid-Sign')) {
            return RequestContext::getResponse()->withStatus(200, StatusCode::handleReturnJson(50004));
        }
    }
}
