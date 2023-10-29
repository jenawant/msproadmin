<?php


namespace App\Setting\Service;


use App\Setting\Mapper\SettingCrontabLogMapper;
use MsPro\Abstracts\AbstractService;
use MsPro\Annotation\DependProxy;
use MsPro\Interfaces\ServiceInterface\CrontabLogServiceInterface;

#[DependProxy(values: [CrontabLogServiceInterface::class])]
class SettingCrontabLogService extends AbstractService implements CrontabLogServiceInterface
{
    /**
     * @var SettingCrontabLogMapper
     */
    public $mapper;

    public function __construct(SettingCrontabLogMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}