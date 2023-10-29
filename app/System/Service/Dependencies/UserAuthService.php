<?php
declare(strict_types=1);

/**
 * MsProAdmin is forked from Min-eAdmin, with the aim of building the system more customizable and faster
 */
namespace App\System\Service\Dependencies;

use App\System\Mapper\SystemUserMapper;
use App\System\Model\SystemUser;
use Hyperf\Database\Model\ModelNotFoundException;
use MsPro\Event\UserLoginAfter;
use MsPro\Event\UserLoginBefore;
use MsPro\Event\UserLogout;
use MsPro\Exception\NormalStatusException;
use MsPro\Exception\UserBanException;
use MsPro\Helper\MsProCode;
use MsPro\Interfaces\UserServiceInterface;
use MsPro\Vo\UserServiceVo;
use MsPro\Annotation\DependProxy;

/**
 * 用户登录
 */
#[DependProxy(values: [ UserServiceInterface::class ])]
class UserAuthService implements UserServiceInterface
{

    /**
     * 登录
     * @param UserServiceVo $userServiceVo
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function login(UserServiceVo $userServiceVo): string
    {
        $mapper = container()->get(SystemUserMapper::class);
        try {
            event(new UserLoginBefore(['username' => $userServiceVo->getUsername(), 'password' => $userServiceVo->getPassword()]));
            $userinfo = $mapper->checkUserByUsername($userServiceVo->getUsername())->toArray();
            $password = $userinfo['password'];
            unset($userinfo['password']);
            $userLoginAfter = new UserLoginAfter($userinfo);
            if ($mapper->checkPass($userServiceVo->getPassword(), $password)) {
                if (
                    ($userinfo['status'] == SystemUser::USER_NORMAL)
                    ||
                    ($userinfo['status'] == SystemUser::USER_BAN && $userinfo['id'] == env('SUPER_ADMIN'))
                ) {
                    $userLoginAfter->message = t('jwt.login_success');
                    $token = user()->getToken($userLoginAfter->userinfo);
                    $userLoginAfter->token = $token;
                    event($userLoginAfter);
                    return $token;
                } else {
                    $userLoginAfter->loginStatus = false;
                    $userLoginAfter->message = t('jwt.user_ban');
                    event($userLoginAfter);
                    throw new UserBanException;
                }
            } else {
                $userLoginAfter->loginStatus = false;
                $userLoginAfter->message = t('jwt.login_error');
                event($userLoginAfter);
                throw new NormalStatusException;
            }
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new NormalStatusException(t('jwt.login_error'), MsProCode::NO_USER);
            }
            if ($e instanceof NormalStatusException) {
                throw new NormalStatusException(t('jwt.login_error'), MsProCode::NO_USER);
            }
            if ($e instanceof UserBanException) {
                throw new NormalStatusException(t('jwt.user_ban'), MsProCode::USER_BAN);
            }
            console()->error($e->getMessage());
            throw new NormalStatusException(t('jwt.unknown_error'));
        }
    }

    /**
     * 用户退出
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function logout()
    {
        $user = user();
        event(new UserLogout($user->getUserInfo()));
        $user->getJwt()->logout();
    }
}