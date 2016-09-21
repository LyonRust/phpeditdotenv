<?php
/**
 * Dotenv
 *
 * 载入.env文件并修改保存
 *
 * @author 技安 <php360@qq.com>
 * @link http://www.moqifei.com 技安后院
 * @license MIT
 * @version 1.0.0
 */
namespace Agile\Phpeditdotenv;

class Dotenv
{
    /**
     * 读取env文件，保留空行和注释
     *
     * @param  string $path 文件路径
     * @param  string $file 文件名，默认".env"
     *
     * @return void
     */
    public static function load($path, $file = '.env')
    {
        // 拼接文件
        $filePath = rtrim($path, '/') . DIRECTORY_SEPARATOR . $file;
        if (!is_readable($filePath) || !is_file($filePath)) {
            throw new \InvalidArgumentException(
                'The file "' . $filePath . '" not readable or not found'
            );
        }

        // 在读取在 Macintosh 电脑中或由其创建的文件时， 如果 PHP 不能正确的识别行结束符，启用运行时配置可选项 auto_detect_line_endings 也许可以解决此问题。
        // 读取当前auto_detect_line_endings设置
        $autodetect = ini_get('auto_detect_line_endings');
        // 开启auto_detect_line_endings
        ini_set('auto_detect_line_endings', '1');
        // 把env文件读取到数组中
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // 还原auto_detect_line_endings
        ini_set('auto_detect_line_endings', $autodetect);

        $envData = [];
        foreach ($lines as $line) {
            // 注释或者空行
            if (strpos(trim($line), '#') === 0 || empty($line)) {
                $envData[] = $line;
                continue;
            }
            // 转化成数组
            if (strpos($line, '=') !== false) {
                list($name, $value) = array_map('trim', explode('=', $line, 2));
                $envData[$name] = $value;
            }
        }

        return $envData;
    }

    /**
     * 保存数据到env文件中，同时保留空行和注释
     *
     * @param  array  $envData 要保存的数据
     * @param  string $path 文件路径
     * @param  string $file 文件名，默认".env"
     *
     * @return void
     */
    public static function save(array $envData, $path, $file = '.env')
    {
        // 拼接文件
        $filePath = rtrim($path, '/') . DIRECTORY_SEPARATOR . $file;
        if (!is_writable($filePath) || !is_file($filePath)) {
            throw new \InvalidArgumentException(
                'The file "' . $filePath . '" not writable or not found'
            );
        }

        $env = '';
        foreach ($envData as $key => $value) {
            if (is_numeric($key)) {
                $env .= $value;
            } else {
                $env .= $key . '=' . $value;
            }

            $env .= PHP_EOL;
        }

        if (file_put_contents($filePath, $env) === false) {
            throw new \InvalidArgumentException(
                'The file "' . $filePath . '" to save false'
            );
        }

        return true;
    }
}