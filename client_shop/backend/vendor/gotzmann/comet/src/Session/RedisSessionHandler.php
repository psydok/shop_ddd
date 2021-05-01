<?php
declare(strict_types=1);

namespace Comet\Session;

class RedisSessionHandler extends \SessionHandler
{

    /**
     * @var \Redis
     */
    protected $_redis;

    /**
     * @var int
     */
    protected $_maxLifeTime;

    /**
     * RedisSessionHandler constructor.
     * @param $config = [
     *  'host'     => '127.0.0.1',
     *  'port'     => 6379,
     *  'timeout'  => 2,
     *  'auth'     => '******',
     *  'database' => 2,
     *  'prefix'   => 'redis_session_',
     * ]
     */
    public function __construct($config)
    {
        if (false === extension_loaded('redis')) {
            throw new \RuntimeException('Please install redis extension.');
        }
        $this->_maxLifeTime = (int)ini_get('session.gc_maxlifetime');

        if (!isset($config['timeout'])) {
            $config['timeout'] = 2;
        }

        $this->_redis = new \Redis();
        if (false === $this->_redis->connect($config['host'], $config['port'], $config['timeout'])) {
            throw new \RuntimeException("Redis connect {$config['host']}:{$config['port']} fail.");
        }
        if (!empty($config['auth'])) {
            $this->_redis->auth($config['auth']);
        }
        if (!empty($config['database'])) {
            $this->_redis->select($config['database']);
        }
        if (empty($config['prefix'])) {
            $config['prefix'] = 'redis_session_';
        }
        $this->_redis->setOption(\Redis::OPT_PREFIX, $config['prefix']);
    }

    /**
     * {@inheritdoc}
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($session_id)
    {
        return $this->_redis->get($session_id);
    }

    /**
     * {@inheritdoc}
     */
    public function write($session_id, $session_data)
    {
        return true === $this->_redis->setex($session_id, $this->_maxLifeTime, $session_data);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($session_id)
    {
        $this->_redis->del($session_id);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}