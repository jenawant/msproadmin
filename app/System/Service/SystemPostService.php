<?php

declare(strict_types = 1);
namespace App\System\Service;

use App\System\Mapper\SystemPostMapper;
use MsPro\Abstracts\AbstractService;

class SystemPostService extends AbstractService
{
    /**
     * @var SystemPostMapper
     */
    public $mapper;

    public function __construct(SystemPostMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}