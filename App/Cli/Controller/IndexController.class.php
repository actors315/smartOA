<?php
namespace Cli\Controller;

use Think\Controller;

/**
 * 后台执行
 * 构建用户index
 */
class IndexController extends Controller {

    //失眠时间
    private $sleep = 5;

    private $fail_count = 0;

    //单线程爬
    public function index() {

        $user_model = D('ZhihuUser');
        //while (TRUE) {
        $username = $user_model -> get_user_queue('followee');
        if (empty($username)) {
            print "没有要采集的用户";
            return;
        }
        $username = addslashes($username);

        //取出深度
        $data = $user_model -> where(array('username' => $username)) -> find();
        $depth = $data['depth'];

        \Think\Log::write("采集用户列表 --- {$username} --- 开始", 'info');

        $user_rows = array();

        //获取关注了
        \Think\Log::write("采集用户列表 --- {$username} --- 关注了 ---", 'info');
        $user_rows = $this -> get_user_index($username, 'followees');
        if (empty($user_rows)) {
            \Think\Log::write("采集用户列表 --- " . $username . " --- 关注了 --- 失败", 'info');
        } else {
            \Think\Log::write("采集用户列表 --- " . $username . " --- 关注了 --- 成功", 'info');
        }

        if ($user_rows !== FALSE) {
            //如果不是执行失败
            $user_model -> where(array('username' => $username, )) -> save(array('followee_uptime' => time(), 'followee_progress_id' => posix_getpid()));
        }

        if (!empty($user_rows)) {
            \Think\Log::write("采集用户列表 --- " . $username . " --- 成功", 'info');

            foreach ($user_rows as $user_row) {
                // 子用户
                $c_username = addslashes($user_row['username']);
                $row = $user_model -> field('Count(*) As count') -> where(array('username' => $c_username)) -> find();
                // 如果用户不存在
                if (!$row['count']) {
                    $user_row['depth'] = $depth + 1;
                    $user_row['parent_username'] = $username;
                    $user_row['create_time'] = $user_row['follower_uptime'] = $user_row['followee_uptime'] = $user_row['info_uptime'] = time();
                    if ($user_model -> add($user_row)) {
                        \Think\Log::write("入库用户 --- " . $c_username . " --- 成功", 'info');
                    } else {
                        \Think\Log::write("入库用户 --- " . $c_username . " --- 失败", 'info');
                    }
                }
            }
        }
        //sleep(10);
        //每采集完一个用户之后休息2秒
        //}
    }

    public function follower() {
        $user_model = D('ZhihuUser');
        //while (TRUE) {
        $username = $user_model -> get_user_queue('follower');
        if (empty($username)) {
            print "没有要采集的用户";
            return;
        }
        $username = addslashes($username);

        //取出深度
        $data = $user_model -> where(array('username' => $username)) -> find();
        $depth = $data['depth'];

        \Think\Log::write("采集用户列表 --- {$username} --- 开始", 'info');

        $user_rows = array();

        // 获取关注者
        $user_rows = $this -> get_user_index($username, 'followers');

        if (empty($user_rows)) {
            \Think\Log::write("采集用户列表 --- " . $username . " --- 关注者 --- 失败", 'info');
        } else {
            \Think\Log::write("采集用户列表 --- " . $username . " --- 关注者 --- 成功", 'info');
        }

        if ($user_rows !== FALSE) {
            //如果不是执行失败
            $user_model -> where(array('username' => $username, )) -> save(array('follower_uptime' => time(), 'follower_progress_id' => posix_getpid()));
        }

        if (!empty($user_rows)) {
            \Think\Log::write("采集用户列表follower --- " . $username . " --- 成功", 'info');

            foreach ($user_rows as $user_row) {
                // 子用户
                $c_username = addslashes($user_row['username']);
                $row = $user_model -> field('Count(*) As count') -> where(array('username' => $c_username)) -> find();
                // 如果用户不存在
                if (!$row['count']) {
                    $user_row['depth'] = $depth + 1;
                    $user_row['parent_username'] = $username;
                    $user_row['create_time'] = $user_row['followee_uptime'] = $user_row['follower_uptime'] = $user_row['info_uptime'] = time();
                    if ($user_model -> add($user_row)) {
                        \Think\Log::write("入库用户follower --- " . $c_username . " --- 成功", 'info');
                    } else {
                        \Think\Log::write("入库用户 follower--- " . $c_username . " --- 失败", 'info');
                    }
                }
            }
        }
        //sleep(10);
        //每采集完一个用户之后休息2秒
        //}

    }

    //多进程采集用户信息
    public function info() {
        \Think\Log::write("多进程采集用户详细信息 开始", 'info');
        import('@.Org.Util.ThinkSpider');
        $w = \SmartSpider\lib\Worker::instance('pcntl');
        $w -> count = 10;
        $w -> is_once = TRUE;
        $w -> on_worker_start = function($worker) {
            $user_model = D('ZhihuUser');
            $username = $user_model -> get_user_queue('info');
            if (empty($username)) {
                print "没有要采集的用户info" . PHP_EOL;
                return;
            }
            \Think\Log::write("采集用户详细信息{$worker->worker_id} --- " . $username . " --- 开始", 'info');
            $username = addslashes($username);
            $cookie = file_get_contents(C('SPIDER_COOKIE_FILE'));
            $curl = \Cli\Org\Util\ThinkCurl::getInstance();
            $curl -> set_cookie($cookie);
            $curl -> set_gzip(true);
            $curl -> set_useragent('Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36');
            $url = "https://www.zhihu.com/people/{$username}/about";
            $content = $curl -> get($url);
            $data = $this -> get_user_about($content);
            if (empty($data)) {
                \Think\Log::write("采集用户详细信息 {$worker->worker_id}--- " . $username . " --- 失败", 'info');
                print "采集用户详细信息{$worker->worker_id} --- " . $username . " --- 失败" . PHP_EOL;
                print "content is ".$content . PHP_EOL;
                return;
            }
            $data['info_server_id'] = $worker->worker_id;
            $data['info_progress_id'] = $worker->worker_pid;
            $data['info_uptime'] = time();
            $result = $user_model -> where(array('username' => $username, )) -> save($data);
            if ($result == FALSE) {
                \Think\Log::write("采集用户详细信息 --- " . $username . " --- 失败", 'info');
                return;
            }

            \Think\Log::write("采集用户详细信息 --- " . $username . " --- 成功", 'info');
        };
        $w -> run();
    }

    private function get_user_about($content) {
        $data = array();
        if (empty($content)) {
            return $data;
        }

        // 一句话介绍
        preg_match('#<span class="bio" title=["|\'](.*?)["|\']>#', $content, $out);
        $data['headline'] = empty($out[1]) ? '' : $out[1];

        // 头像
        //preg_match('#<img alt="龙威廉"\ssrc="(.*?)"\sclass="zm-profile-header-img zg-avatar-big zm-avatar-editor-preview"/>#', $content, $out);
        preg_match('#<img class="avatar avatar-l" alt=".*?" src="(.*?)" srcset=".*?" />#', $content, $out);
        $data['headimg'] = empty($out[1]) ? '' : $out[1];

        // 居住地
        preg_match('#<span class="location item" title=["|\'](.*?)["|\']>#', $content, $out);
        $data['location'] = empty($out[1]) ? '' : $out[1];

        // 所在行业
        preg_match('#<span class="business item" title=["|\'](.*?)["|\']>#', $content, $out);
        $data['business'] = empty($out[1]) ? '' : $out[1];

        // 性别
        preg_match('#<span class="item gender" ><i class="icon icon-profile-(.*?)"></i></span>#', $content, $out);
        $gender = empty($out[1]) ? 'other' : $out[1];
        if ($gender == 'female')
            $data['gender'] = 0;
        elseif ($gender == 'male')
            $data['gender'] = 1;
        else
            $data['gender'] = 2;

        // 公司或组织名称
        preg_match('#<span class="employment item" title=["|\'](.*?)["|\']>#', $content, $out);
        $data['employment'] = empty($out[1]) ? '' : $out[1];

        // 职位
        preg_match('#<span class="position item" title=["|\'](.*?)["|\']>#', $content, $out);
        $data['position'] = empty($out[1]) ? '' : $out[1];

        // 学校或教育机构名
        //<span class="education item" title="南昌大学">
        preg_match('#<span class="education item" title=["|\'](.*?)["|\']>#', $content, $out);
        $data['education'] = empty($out[1]) ? '' : $out[1];

        // 专业方向
        //<span class="education-extra item" title="网络工程">
        preg_match('#<span class="education-extra item" title=["|\'](.*?)["|\']>#', $content, $out);
        $data['education_extra'] = empty($out[1]) ? '' : $out[1];

        // 新浪微博
        preg_match('#<a class="zm-profile-header-user-weibo" target="_blank" href="(.*?)"#', $content, $out);
        $data['weibo'] = empty($out[1]) ? '' : $out[1];

        // 个人简介
        preg_match('#<span class="content">\s(.*?)\s</span>#s', $content, $out);
        $data['description'] = empty($out[1]) ? '' : trim(strip_tags($out[1]));

        // 关注了、关注者
        preg_match('#<span class="zg-gray-normal">关注了</span><br />\s<strong>(.*?)</strong><label> 人</label>#', $content, $out);
        $data['followees'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#<span class="zg-gray-normal">关注者</span><br />\s<strong>(.*?)</strong><label> 人</label>#', $content, $out);
        $data['followers'] = empty($out[1]) ? 0 : intval($out[1]);

        // 关注专栏
        preg_match('#<strong>(.*?) 个专栏</strong>#', $content, $out);
        $data['followed'] = empty($out[1]) ? 0 : intval($out[1]);

        // 关注话题
        preg_match('#<strong>(.*?) 个话题</strong>#', $content, $out);
        $data['topics'] = empty($out[1]) ? 0 : intval($out[1]);

        // 关注专栏
        preg_match('#个人主页被 <strong>(.*?)</strong> 人浏览#', $content, $out);
        $data['pv'] = empty($out[1]) ? 0 : intval($out[1]);

        // 提问、回答、专栏文章、收藏、公共编辑
        preg_match('#提问\s<span class="num">(.*?)</span>#', $content, $out);
        $data['asks'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#回答\s<span class="num">(.*?)</span>#', $content, $out);
        $data['answers'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#专栏文章\s<span class="num">(.*?)</span>#', $content, $out);
        $data['posts'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#收藏\s<span class="num">(.*?)</span>#', $content, $out);
        $data['collections'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#公共编辑\s<span class="num">(.*?)</span>#', $content, $out);
        $data['logs'] = empty($out[1]) ? 0 : intval($out[1]);

        // 赞同、感谢、收藏、分享
        preg_match('#<strong>(.*?)</strong> 赞同#', $content, $out);
        $data['votes'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#<strong>(.*?)</strong> 感谢#', $content, $out);
        $data['thanks'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#<strong>(.*?)</strong> 收藏#', $content, $out);
        $data['favs'] = empty($out[1]) ? 0 : intval($out[1]);
        preg_match('#<strong>(.*?)</strong> 分享#', $content, $out);
        $data['shares'] = empty($out[1]) ? 0 : intval($out[1]);
        return $data;
    }

    private function get_user_index($username, $user_type = 'followees') {

        $keyword = $user_type == 'followees' ? '关注了' : '关注者';

        $iplist = explode(PHP_EOL, file_get_contents(C('IPLIST_FILE')));
        $cookie = file_get_contents(C('SPIDER_COOKIE_FILE'));
        $curl = \Cli\Org\Util\ThinkCurl::getInstance();
        $curl -> set_cookie($cookie);
        $curl -> set_gzip(true);
        $curl -> set_useragent('Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36');

        $url = "https://www.zhihu.com/people/{$username}/{$user_type}";
        $content = $curl -> get($url);

        if (empty($content)) {
            return array();
        }
        $users = array();
        preg_match_all('#<h2 class="zm-list-content-title"><a data-tip=".*?" href="https*://www.zhihu.com/people/(.*?)" class="zg-link" title=".*?">(.*?)</a></h2>#', $content, $out);
        $count = count($out[1]);
        if ($count < 1) {
            print date('Y-m-d H:i:s') . PHP_EOL;
            print $url . PHP_EOL;
            print $content . PHP_EOL;
            //失败了休息长一点时间,换个马夹,每多失败一次多休10秒
            $this -> sleep = mt_rand($this -> sleep, $this -> sleep + 10);
            \Think\Log::write("采集用户 --- " . $username . " --- {$keyword} --- 主页 --- 失败,休息{$this->sleep}秒", 'info');
            $this -> fail_count = $this -> fail_count + 1;
            if ($this -> fail_count == 3) {//失败3次退出
                exit(0);
            }
            sleep($this -> sleep);
            return FALSE;
        }
        for ($i = 0; $i < $count; $i++) {
            $d_username = empty($out[1][$i]) ? '' : $out[1][$i];
            $d_nickname = empty($out[2][$i]) ? '' : $out[2][$i];
            if (!empty($d_username) && !empty($d_nickname)) {
                $users[$d_username] = array('username' => $d_username, 'nickname' => $d_nickname, );
            }
        }

        \Think\Log::write("采集用户 --- " . $username . " --- {$keyword} --- 主页 --- 成功", 'info');

        preg_match('#<span class="zg-gray-normal">' . $keyword . '</span><br />\s<strong>(.*?)</strong><label> 人</label>#', $content, $out);
        $user_count = empty($out[1]) ? 0 : intval($out[1]);

        preg_match('#<input type="hidden" name="_xsrf" value="(.*?)"/>#', $content, $out);
        $_xsrf = empty($out[1]) ? '' : trim($out[1]);

        preg_match('#<div class="zh-general-list clearfix" data-init="(.*?)">#', $content, $out);
        $url_params = empty($out[1]) ? '' : json_decode(html_entity_decode($out[1]), true);
        if (!empty($_xsrf) && !empty($url_params) && is_array($url_params)) {
            $url = "https://www.zhihu.com/node/" . $url_params['nodename'];
            $params = $url_params['params'];

            $j = 0;
            for ($i = 20; $i < $user_count; $i = $i + 20) {//第一页不需要
                $j++;
                $params['offset'] = $i;
                $post_data = array('method' => 'next', 'params' => json_encode($params), '_xsrf' => $_xsrf, );
                $content = $curl -> post($url, $post_data);
                if (empty($content)) {
                    print data('Y-m-d H:i:s') . PHP_EOL;
                    print $content;
                    \Think\Log::write("采集用户 --- " . $username . " --- {$keyword} --- 第{$j}页 --- 失败", 'info');
                    //失败之后直接返回结果
                    return $users;
                }
                $rows = json_decode($content, true);
                if (empty($rows['msg']) || !is_array($rows['msg'])) {
                    print date('Y-m-d H:i:s') . PHP_EOL;
                    print $rows['msg'];
                    \Think\Log::write("采集用户 --- " . $username . " --- {$keyword} --- 第{$j}页 --- 失败", 'info');
                    return $users;
                }
                \Think\Log::write("采集用户 --- " . $username . " --- {$keyword} --- 第{$j}页 --- 成功", 'info');

                foreach ($rows['msg'] as $row) {
                    preg_match_all('#<h2 class="zm-list-content-title"><a data-tip=".*?" href="https*://www.zhihu.com/people/(.*?)" class="zg-link" title=".*?">(.*?)</a></h2>#', $row, $out);
                    $d_username = empty($out[1][0]) ? '' : $out[1][0];
                    $d_nickname = empty($out[2][0]) ? '' : $out[2][0];
                    if (!empty($d_username) && !empty($d_nickname)) {
                        $users[$d_username] = array('username' => $d_username, 'nickname' => $d_nickname, );
                    }
                }
                //这里405的概率很小，可以少休一会儿
                usleep(100000);
            }
        }
        return $users;

    }

    private function get_ip() {

    }

}
