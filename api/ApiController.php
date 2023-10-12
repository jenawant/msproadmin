<?php
/**
 * MsProAdmin is forked from Min-eAdmin, with the aim of building the system more customizable and faster
 */

declare(strict_types=1);
namespace Api;

use App\System\Service\SystemAppService;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\PostMapping;
use MsPro\Exception\NoPermissionException;
use MsPro\Exception\NormalStatusException;
use MsPro\Exception\TokenException;
use MsPro\Helper\MsProCode;
use MsPro\MsProApi;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\MultipleAnnotation;
use Hyperf\Di\ReflectionManager;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Api\Middleware\VerifyInterfaceMiddleware;

/**
 * Class ApiController
 * @package Api
 */
#[Controller(prefix: "api")]
class ApiController extends MsProApi
{
    public const SIGN_VERSION = '1.0';

    /**
     * 获取accessToken
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    #[PostMapping("v1/getAccessToken")]
    public function getAccessToken(): ResponseInterface
    {
        $service = container()->get(SystemAppService::class);
        return $this->success($service->getAccessToken($this->request->all()));
    }

    /**
     * v1 版本
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[RequestMapping("v1/{method}")]
    #[Middlewares([ VerifyInterfaceMiddleware::class ])]
    public function v1(): ResponseInterface
    {
        $apiData = $this->__init();

        try {
            $class = make($apiData['class_name']);
            // 反射拿参数
            $reflectionMethod = ReflectionManager::reflectMethod($apiData['class_name'], $apiData['method_name']);
            $parameters = $reflectionMethod->getParameters();
            $args = [];
            foreach ($parameters as $parameter) {
                if ($parameter->getType() === null) {
                    continue;
                }
                $className = $parameter->getType()->getName();
                $formRequest = container()->get($className);
                $args[] = $formRequest;
                if ($formRequest instanceof FormRequest) {
                    $this->handleSceneAnnotation($formRequest, $apiData['class_name'], $apiData['method_name'], $parameter->getName());
                    // 验证， 这里逻辑和 验证中间件一样 直接抛异常 
                    $formRequest->validateResolved();
                }
            }
            // 反射调用
            return $reflectionMethod->invokeArgs($class, $args);
        } catch (\Throwable $e) {
            if ($e instanceof ValidationException) {
                // 抛出的是验证异常 取一条错误信息返回
                $errors = $e->errors();
                $error = array_shift($errors);
                if (is_array($error)) {
                    $error = array_shift($error);
                }
                throw new NormalStatusException(t('msproadmin.interface_exception') . $error, MsProCode::INTERFACE_EXCEPTION);
            }
            if ($e instanceof NoPermissionException) {
                throw new NormalstatusException( t( key: 'msproadmin.api_auth_fail') . $e->getMessage(), code: MsProCode::NO_PERMISSION);
            }
            if ($e instanceof TokenException) {
                throw new NormalstatusException( t( key: 'msproadmin.api_auth_exception') . $e->getMessage(), code: MsProCode::TOKEN_EXPIRED);
            }

            throw new NormalstatusException( t( key: 'msproadmin.interface_exception') . $e->getMessage(), code: MsProCode::INTERFACE_EXCEPTION);
        }
    }

    /**
     * 初始化
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function __init()
    {
        if (empty($this->request->input('apiData'))) {
            throw new NormalStatusException(t('msproadmin.access_denied'), MsProCode::NORMAL_STATUS);
        }

        return $this->request->input('apiData');
    }

    protected function handleSceneAnnotation(FormRequest $request, string $class, string $method, string $argument): void
    {
        /** @var null|MultipleAnnotation $scene */
        $scene = AnnotationCollector::getClassMethodAnnotation($class, $method)[Scene::class] ?? null;
        if (! $scene) {
            return;
        }

        $annotations = $scene->toAnnotations();
        if (empty($annotations)) {
            return;
        }

        /** @var Scene $annotation */
        foreach ($annotations as $annotation) {
            if ($annotation->argument === null || $annotation->argument === $argument) {
                $request->scene($annotation->scene ?? $method);
                return;
            }
        }
    }
}