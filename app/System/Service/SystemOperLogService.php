<?php

declare(strict_types = 1);
namespace App\System\Service;


use App\System\Mapper\SystemOperLogMapper;
use MsPro\Abstracts\AbstractService;
use MsPro\Annotation\DependProxy;
use MsPro\Interfaces\ServiceInterface\OperLogServiceInterface;

#[DependProxy(values: [ OperLogServiceInterface::class ])]
class SystemOperLogService extends AbstractService implements OperLogServiceInterface
{
    public $mapper;

    public function __construct(SystemOperLogMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}