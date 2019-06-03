@servers(['web' => '127.0.0.1'])

@task('deploy', ['on' => 'web'])
cd {{$path = '/home/work/'}}
{{ini_set('date.timezone','Asia/Shanghai')}}
@foreach(array_diff(scandir($path), ['.', '..']) as $file)
    @if(is_dir($path . $file) && is_dir($path . $file . '/.git'))
        cd {{$path . $file}}
        pwd
        echo {{date('Y-m-d H:i:s')}}----------------------------------------------
        git pull
        echo {{date('Y-m-d H:i:s')}}----------------------------------------------
    @endif
@endforeach
@endtask
