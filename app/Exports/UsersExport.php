<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// 参考网址：https://learnku.com/articles/32391
//新增 ShouldAutoSize, 自动适应单元格宽
class UsersExport implements FromCollection, ShouldAutoSize, WithColumnFormatting
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    //业务代码
    public function createData(): array
    {
        //todo 业务代码
        return [];
    }

    /**
     * 格式化数据
     * @return Collection|\Illuminate\Support\Collection
     */
    public function collection()
    {
        //return User::all(); // 导出user表所有数据
        return new Collection($this->createData()); // 导出业务逻辑的数据
    }

    /**
     * 单元格格式化
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DMYSLASH, //日期
            'C' => NumberFormat::FORMAT_NUMBER_00, //金额保留两位小数
        ];
    }

    /**
     * sheet 表名称
     * @return string
     */
    public function title(): string
    {
        return 'user ' . rand(10000, 999999);
    }
}
