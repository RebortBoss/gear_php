<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 11:21
 */

namespace src\cores;



/**
 * @method \src\plugins\route\Route route() 获取route对象
 * @method \src\plugins\factory\libs\Request request() 获取request对象
 * @method \src\plugins\dispatch\Dispatch dispatch() 获取dispatch对象
 * @method \src\plugins\dispatch\DispatchCli dispatchCli() 获取dispatchCli对象
 * @method \src\plugins\factory\libs\Format format() 获取format对象
 * @method \src\plugins\factory\libs\File file() 获取File对象
 * @method \src\plugins\factory\libs\Sender sender() 获取Sender对象
 * @method \src\plugins\encrypt\Encrypt encrypt() 获取Encrypt对象
 * @method \src\plugins\factory\libs\Session session() 获取Session对象
 * @method \src\plugins\factory\libs\Cookie cookie() 获取Cookie对象
 * @method \src\plugins\logger\Logger logger() 获取Logger对象
 * @method \src\plugins\captcha\Captcha captcha() 获取Captcha对象
 * @method Config config() 获取config对象
 * @method \src\plugins\infoJump\InfoJump infoJump() 获取InfoJump对象
 * @method \src\plugins\debug\Debug debug() 获取Debug对象
 * @method \src\plugins\qrCode\QrCode qrCode() 获取QrCode对象
 * @method \src\plugins\cache\Cache cache() 获取Cache对象
 * @method \src\plugins\weiChat\WeiChat weiChat() 获取WeiChat对象
 * @method \src\plugins\factory\libs\Excel excel() 获取Excel对象
 * @method \src\plugins\factory\libs\Image image(string $img_file, int $status = 0) 获取Image对象
 * @method \src\plugins\ueditor\Ueditor ueditor() 获取Ueditor对象
 * @method \src\plugins\uploader\Uploader uploader(string $path=null,array $allowtype=null,int $maxsize=null,bool $israndname=null) 获取Upload对象
 * @method \src\plugins\db\Db db(string $config_name='local',array $configs=[]) 获取Db对象
 * @method \Pinq\ITraversable pinq(array $from_array) 获取pinq对象
 * @method \src\plugins\factory\libs\Locker locker(string $name) 获取Lock对象
 * @method \src\plugins\factory\libs\Semaphore semaphore(string $name, int $max = 1) 获取Semaphore对象
 * @method \src\plugins\arrayDb\ArrayDb arrayDb(string $name) 获取ArrayDb对象
 * @method array getErrors() 获取错误纪录（由errorCatch纪录）
 * @method \Think\Model model_M(string $name='',string $tablePrefix='',mixed|string $connection='' ) 模型实例化
 * @method \Think\Model model_D(string $name='') 子类实例化
 * @method \src\plugins\picMagic\PicMagic picMagic(string $picFileOriginal,array $params) 获取PicMagic对象
 * @method \src\plugins\webSocket\Server webSocketServer(string $host = 'localhost',int $port = 8000, bool $ssl = false) 获取Server对象
 * @method \src\plugins\webSocket\client webSocketClient() 获取Client对象
 * @method \src\plugins\neuralNetwork\NeuralNetwork neuralNetwork(array $nodeCount) 获取NeuralNetwork对象
 * @method \src\plugins\email\Email email(string|array $account='default') 获取Email对象(传入配置名或配置数组)
 */
class Factory
{
    const EVENT_AFTER_FACTORY_ADD_RECIPE = 'EVENT_FACTORY_ADD_RECIPE_';
    const EVENT_NEED_RECIPE = 'EVENT_NEED_RECIPE_';

    private static $single;

    private static $recipes=[];

    /**
     * 返回单例
     * @return Factory
     */
    public static function getSingle()
    {
        if (!is_object(self::$single)){
            self::$single=new Factory();
        }
        return self::$single;
    }

    /**
     * 添加一种产品配方
     * @param $name string
     * @param $callable callable
     */
    public static function addRecipe($name,callable $callable){
        self::$recipes[$name]=$callable;
        Event::trigger(self::EVENT_AFTER_FACTORY_ADD_RECIPE . $name);
    }

    /**
     * 删除一种产品配方
     * @param $name string
     */
    public static function removeRecipe($name){
        unset(self::$recipes[$name]);
    }

    /**
     * 检查是否拥有一种产品配方
     * @param $name string
     * @return bool
     */
    public static function hasRecipe($name){
        return isset(self::$recipes[$name]) and is_callable(self::$recipes[$name]);
    }

    /**
     * 对象可以访问配方，获得产品
     * @param $name string
     * @param $args array
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $args)
    {
        if (!self::hasRecipe($name)){
            Event::trigger(self::EVENT_NEED_RECIPE.$name);
        }

        if (isset(self::$recipes[$name]) and is_callable(self::$recipes[$name])){
            $recipe= self::$recipes[$name];
            return call_user_func_array($recipe,$args);
        }else{
            throw new \Exception('Can not find function : '.$name);
        }
    }
}