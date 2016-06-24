<?php
namespace SmartWorker\lib;
/**
 * pcntl实现多进程
 */
class Worker_pcntl {

    //最大进程数量
    public $count = 0;

    //主进程标记
    public $worker_id = 0;

    //当前进程ID
    public $worker_pid = 0;

    public $is_once = false;

    //进程开始时操作
    public $on_worker_start = false;

    //进程结束时操作
    public $on_worker_stop = false;

    //主进程
    protected static $_master_pid = 0;

    //子进程列表
    protected static $_worker_pids = array();

    public function __construct($config = array(), $debug = false) {
        if (!function_exists('pcntl_fork')) {
            throw new \SmartSpider\exception\SpiderException("当前环境不支持pcntl,请先安装该展", -1);
        }
    }

    /**
     * 安装信号处理函数
     */
    protected function install_signal() {
        pcntl_signal(SIGINT, array($this, 'signal_handler'), FALSE);
        pcntl_signal(SIGUSR1, array($this, 'signal_handler'), FALSE);
        pcntl_signal(SIGUSR2, array($this, 'signal_handler'), FALSE);
        pcntl_signal(SIGPIPE, SIG_IGN, FALSE);
    }

    /**
     * 卸载信号处理函数
     */
    protected function uninstall_signal() {
        // uninstall stop signal handler
        pcntl_signal(SIGINT, SIG_IGN, false);
        // uninstall reload signal handler
        pcntl_signal(SIGUSR1, SIG_IGN, false);
        // uninstall  status signal handler
        pcntl_signal(SIGUSR2, SIG_IGN, false);
    }

    /**
     * 信号处理函数
     */
    public function signal_handler($signal) {
        switch ($signal) {
            case SIGINT :
                $this -> stop_all();
                break;
            case SIGUSR1 :
                break;
            case SIGUSR2 :
                break;
            default :
                echo "do nothing";
        }
    }

    /**
     * 关闭进程流程
     * 依次通知子进程关闭
     */
    public function stop_all() {
        if (self::$_master_pid === posix_getpid()) {
            foreach (self::$_worker_pids as $worker_pid) {
                posix_kill($worker_pid, SIGINT);
            }
            //sleep(2);
            echo "主进程\n";
        } else {
            $this -> stop();
            exit(0);
        }
    }

    /**
     * 停止当前worker实例
     * @return void
     */
    public function stop() {
        if ($this -> on_worker_stop) {
            call_user_func($this -> on_worker_stop, $this);
        }
    }

    /**
     * 创建子进程
     */
    public function fork_one_worker($worker_id) {
        //fork是创建了一个子进程，父进程和子进程 都从fork的位置开始向下继续执行，
        //不同的是父进程执行过程中，得到的fork返回值为子进程号，而子进程得到的是0。
        $pid = pcntl_fork();
        if ($pid > 0) {
            self::$_worker_pids[$pid] = $pid;
        } elseif ($pid == 0) {
            if ($this -> on_worker_start) {
                $this -> worker_id = $worker_id;
                $this -> worker_pid = posix_getpid();
                call_user_func($this -> on_worker_start, $this);
            }
            exit(0);
        } else {
            exit("fork child worker fail.");
        }
    }

    /**
     * 监控所有子进程的退出事件及退出码
     * 通过一个死循环来监控进程
     */
    public function monitor_workers() {
        while (TRUE) {
            $status = 0;
            //挂起进程，直到有子进程退出或者被信号打断
            $pid = pcntl_wait($status, WUNTRACED);
            if ($pid > 0) {
                $this -> stop();

                if ($status !== 0) {
                    echo "worker {$pid} exit with status $status\n";
                }

                unset(self::$_worker_pids[$pid]);

                // 再生成一个worker
                if (!$this -> is_once) {
                    $this -> fork_one_worker();
                }

                //子进程全部退出时，主进程也退出
                if (!self::$_worker_pids) {
                    exit("主进程退出\n");
                }

            } else {
                exit("主进程 异常 退出\n");
            }
        }
    }

    /**
     * 运行worker实例
     */
    public function run() {
        for ($i = 0; $i < $this -> count; $i++) {
            $this -> fork_one_worker($i);
        }
        $this -> monitor_workers();
    }

}

/**
 * 后续参考看看别的方案是否可以优化
 * http://blog.csdn.net/huyanping/article/details/18280839
 */
