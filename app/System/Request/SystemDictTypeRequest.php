<?php
declare(strict_types=1);
namespace App\System\Request;

use MsPro\MsProFormRequest;

class SystemDictTypeRequest extends MsProFormRequest
{
    /**
     * 公共规则
     */
    public function commonRules(): array
    {
        return [];
    }


    /**
     * 新增数据验证规则
     * return array
     */
    public function saveRules(): array
    {
        return [
            'name' => 'required',
            'code' => 'required',
        ];
    }

    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            'name' => 'required',
            'code' => 'required',
        ];
    }

    /**
     * 修改状态数据验证规则
     * return array
     */
    public function changeStatusRules(): array
    {
        return [
            'id' => 'required',
            'status' => 'required'
        ];
    }

    /**
     * 字段映射名称
     * return array
     */
    public function attributes(): array
    {
        return [
            'id' => '字典类型ID',
            'name' => '字典类型名称',
            'code' => '字典类型标识',
            'status' => '字典类型状态',
        ];
    }

}