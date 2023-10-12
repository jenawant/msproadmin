<?php
/**
 * MsProAdmin is forked from Min-eAdmin, with the aim of building the system more customizable and faster
 */

declare(strict_types=1);
namespace Api;

use App\System\Service\SystemApiService;
use App\System\Service\SystemAppService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use MsPro\Helper\MsProCode;
use MsPro\MsProApi;
use Hyperf\HttpServer\Annotation\Controller;
use Psr\Http\Message\ResponseInterface;
use MsPro\Annotation\Api\MApiCollector;

/**
 * Class ApiDocController
 * @package Api
 */
#[Controller(prefix: "apiDoc")]
class ApiDocController extends MsProApi
{
    /**
     * @var SystemAppService
     */
    #[Inject]
    protected SystemAppService $systemAppService;

    /**
     * @var SystemApiService
     */
    #[Inject]
    protected SystemApiService $systemApiService;

    /**
     * 登录文档
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PostMapping("login")]
    public function login(): ResponseInterface
    {
        $app_id = $this->request->input('app_id', '');
        $app_secret = $this->request->input('app_secret', '');

        if (empty($app_id) && empty($app_secret)) {
            return $this->error(t('msproadmin.api_auth_fail'), MsProCode::API_PARAMS_ERROR);
        }

        if (($code = $this->systemAppService->loginDoc($app_id, $app_secret)) !== MsProCode::API_VERIFY_PASS) {
            return $this->error(t('msproadmin.api_auth_fail'), $code);
        }

        return $this->success();
    }

    /**
     * 通过app id获取接口数据
     * @param string $id
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("getAppAndInterfaceList/{id}")]
    public function getAppAndInterfaceList(string $id): ResponseInterface
    {
        $appAndInterfaceList = $this->systemAppService->getAppAndInterfaceList($id);

        $apis = MApiCollector::getApiInfosByAppId($id);

        // 注解与数据库定义的合并
        $apis = array_merge($appAndInterfaceList['apis'], $apis);
        $appAndInterfaceList['apis'] = $apis;

        return $this->success($appAndInterfaceList);
    }

    /**
     * @param string $id
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("getColumnList/{id}")]
    public function getColumnList(string $id): ResponseInterface
    {
        // 如果api注解收集器里有，直接返回信息
        if (MApiCollector::getApiInfo($id)) {
            return $this->success(MApiCollector::getApiInfo($id));
        }

        return $this->success($this->systemApiService->getColumnListByApiId($id));
    }

}