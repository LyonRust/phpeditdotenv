# phpeditdotenv
编辑“.env”文件

####加载

    composer require 'agile/phpeditdotenv:~1.0'

    或者在composer.json文件中添加
    "require": {
        "agile/phpeditdotenv": "~1.0"
    }

####使用
    use Agile\Phpeditdotenv\Dotenv;

    public function index()
    {
        // 读取
        $envData = Dotenv::load(realpath('../'));
        var_dump($envData);

        // 修改某一项参数并保存
        $envData['CACHE_DRIVER'] = 'redis';
        Dotenv::save($envData, realpath('../'));
    }
