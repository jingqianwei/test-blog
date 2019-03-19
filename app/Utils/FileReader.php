<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2018/12/4
 * Time: 10:24
 */

namespace App\Utils;

use SplFileObject;

class FileReader
{
    private $csv_file;
    private $spl_object = null;
    private $error;

    /**
     * 初始化
     * FileReader constructor.
     * @param string $csv_file
     */
    public function __construct($csv_file = '') {
        if($csv_file && file_exists($csv_file)) {
            $this->csv_file = $csv_file;
        }
    }

    /**
     * 设置文件路径
     * @param $csv_file
     * @return bool
     */
    public function set_csv_file($csv_file) {
        if(!$csv_file || !file_exists($csv_file)) {
            $this->error = 'File invalid';
            return false;
        }
        $this->csv_file = $csv_file;
        $this->spl_object = null;
    }

    /**
     * 获取文件路径
     * @return string
     */
    public function get_csv_file() {
        return $this->csv_file;
    }

    /**
     * 检查文件
     * @param string $file
     * @return bool
     */
    private function _file_valid($file = '') {
        $file = $file ? $file : $this->csv_file;
        if(!$file || !file_exists($file)) {
            return false;
        }
        if(!is_readable($file)) {
            return false;
        }
        return true;
    }

    /**
     * 打开文件
     * @return bool
     */
    private function _open_file() {
        if(!$this->_file_valid()) {
            $this->error = 'File invalid';
            return false;
        }
        if($this->spl_object == null) {
            $this->spl_object = new SplFileObject($this->csv_file, 'rb');
        }
        return true;
    }

    /**
     * 获取文件内容，返回为二维数组
     * @param int $length
     * @param int $start
     * @return array|bool
     */
    public function get_data($length = 0, $start = 0) {
        if(!$this->_open_file()) {
            return false;
        }
        $length = $length ? $length : $this->get_lines();
        $start = $start - 1;
        $start = ($start < 0) ? 0 : $start;
        $data = array();
        $this->spl_object->seek($start);
        while ($length-- && !$this->spl_object->eof()) {
            $row = $this->spl_object->fgetcsv();
            // array_walk()跟foreach()功能类似，但使用array_walk()中的回调函数存在作用域，foreach()不存在作用域，使用&方法不会出问题
            array_walk($row, function (&$val) {
                //未知原编码，通过auto自动检测后，转换编码为utf-8，防止读取文件乱码
                $val = mb_convert_encoding($val, 'utf-8', 'auto');
            });
            $data[] = $row;
            $this->spl_object->next();
        }
        return $data;
    }

    /**
     * 获取文件内容,返回为一维数组
     * @param int $length
     * @param int $start
     * @return array|bool
     */
    public function get_data_simple($length = 0, $start = 0) {
        if(!$this->_open_file()) {
            return false;
        }
        $length = $length ? $length : $this->get_lines();
        $start = $start - 1;
        $start = ($start < 0) ? 0 : $start;
        $data = array();
        $this->spl_object->seek($start);
        while ($length-- && !$this->spl_object->eof()) {
            $row = $this->spl_object->fgetcsv();
            // array_walk()跟foreach()功能类似，但使用array_walk()中的回调函数存在作用域，foreach()不存在作用域，使用&方法不会出问题
            array_walk($row, function (&$val) {
                //未知原编码，通过auto自动检测后，转换编码为utf-8，防止读取文件乱码
                $val = mb_convert_encoding($val, 'utf-8', 'auto');
            });
            $data = array_merge($data, $row);
            $this->spl_object->next();
        }
        return $data;
    }

    /**
     * 获取文件总行数(注意当文件结尾有换行符的时候，结果不太准确，具体得跟实际情况来判断)
     * @return bool
     */
    public function get_lines() {
        if(!$this->_open_file()) {
            return false;
        }
        $this->spl_object->seek(filesize($this->csv_file));
        return $this->spl_object->key() + 1;
    }

    /**
     * 关闭文件
     * @return bool
     */
    public function close_file() {
        if (!is_null($this->spl_object)) {
            $this->spl_object = null;
        }

        return true;
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function get_error() {
        return $this->error;
    }
}
