<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    protected $path = null; //保存地址
    protected $process = null; //进度
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {connection=mysql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '备份数据库';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->path = storage_path('logs' . DIRECTORY_SEPARATOR . 'backups');
        if (!file_exists($this->path)) {
            mkdir($this->path, 0777);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //选择备份那个数据库，默认为mysql
        $dbConnection = $this->argument('connection');
        $this->setProcess($dbConnection);
        try {
            // 删除N天之前的备份
            $files = scandir($this->path); //获取目录下的所有文件
            foreach ($files as $file) {
                if (!in_array($file, ['.', '..']) && now()->diffIndays(substr($file, 7, 10)) >= env('DB_BACKUP_DAYS')) {
                    unlink($this->path . $file);
                }
            }
            $this->process->mustRun();
            $this->info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $e) {
            $this->error('The backup process has been failed, reason is: ' . $e->getMessage());
        }

        \Log::info('数据备份完成');
        return true;
    }

    /**
     * @Describe: 设置process的值
     * @Author: chinwe.jing
     * @Data: 2018/8/22 10:14
     * @param $dbConnection
     * @param string mysql5.6以上备份数据库要用命令 mysqldump --defaults-extra-file=/etc/my.cnf test-blog > test.sql，其中my.cnf中的[client]配置数据库的连接密码即可。password = 'myServerPwd'
     * @param string mysql5.6以下备份数据库用 mysqldump -uroot -proot test-blog > test.sql
     */
    public function setProcess($dbConnection)
    {
        // mysql5.6以下
//        $this->process = new Process(sprintf(
//            'mysqldump -u%s -p%s %s > %s',
//            config("database.connections.{$dbConnection}.username"),
//            config("database.connections.{$dbConnection}.password"),
//            config("database.connections.{$dbConnection}.database"),
//            $this->path . DIRECTORY_SEPARATOR . 'backup_' . date('Ymd') . '.sql' //生成到项目文件夹外
//        ));

        // mysql5.6以上
//        $data = sprintf(
//            'mysqldump --defaults-extra-file=/etc/my.cnf %s > %s',
//            config("database.connections.{$dbConnection}.database"),
//            $this->path . DIRECTORY_SEPARATOR . 'backup_' . date('Ymd') . '.sql'
//        );
        $commandline = [
            'mysqldump --defaults-extra-file=/etc/my.cnf',
            config("database.connections.{$dbConnection}.database"),
            '>',
            $this->path . DIRECTORY_SEPARATOR . 'backup_' . date('Ymd') . '.sql',
        ];
        $cwd = '/usr/local/mysql/bin';

        \Log::info('备份数据库的数据为: ', $commandline);

        $this->process = new Process($commandline, $cwd);
    }
}
