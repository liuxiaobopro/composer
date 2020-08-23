<?php

namespace liuxiaobo;

class curl
{
    private $cookie = ''; // cookie值
    private $header = []; // 请求头

    /**
     * get请求
     * @param $url
     * @return mixed
     */
    public function getHttp($url = '', $randUa = TRUE, $cookie = '')
    {
        try {
            $getHttpInit = $this->getHttpInit($url, $randUa, $cookie);
            if (is_bool($getHttpInit)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
                curl_setopt($ch, CURLOPT_NOSIGNAL, true);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
                $output = curl_exec($ch);
                curl_close($ch);
                return $output;
            } else {
                return $getHttpInit;
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * gethttp初始化
     * @param $isUa
     * @param $cookie
     */
    private function getHttpInit($url, $randUa, $cookie)
    {
        if (strlen($url) == 0) return '你是想让我去请求二氧化碳嘛!';
        $type = [
            [$url, 'is_string', '请求路由'],
            [$randUa, 'is_bool', '随机浏览器标识'],
            [$cookie, 'is_string', 'cookie'],
        ];
        $typeres = $this->type($type);
        if (is_bool($typeres)) {
            // 载入请求头
            $header = $this->header();
            // 不设置则删除
            if (!$randUa) unset($header['user-agent']);
            if (strlen($cookie) == 0) unset($header['cookie']);
            $this->header = $header;
            return true;
        } else {
            return $typeres;
        }
    }

    /**
     * 判断数据类型
     * @param $type
     */
    private function type($type)
    {
        foreach ($type as $v) {
            switch ($v[1]) {
                case 'is_string':
                    if (!is_string($v[0])) return "\'$v[3]\'不是字符串类型";
                    break;
                case 'is_bool':
                    if (!is_bool($v[0])) return "\'$v[3]\'不是布尔类型";
                    break;
                case 'is_array':
                    if (!is_array($v[0])) return "\'$v[3]\'不是数组类型";
                    break;
                default:
                    return '未定义类型检测,如有需要请联系作者添加';
                    break;
            }
        }
        return true;
    }

    /**
     * 设置请求头
     * @param $cookie
     * @param $user_agent
     * @return array
     */
    private function header()
    {
        $userAgent = $this->userAgent();
        $headerArray = array("cookie:{$this->cookie}", "Content-type:application/json;", "Accept:application/json", "user-agent: {$userAgent}");
        return $headerArray;
    }

    /**
     * 获取随机浏览器标识
     * @return mixed
     */
    private function userAgent()
    {
        $browser = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
            'Mozilla/5.0(Macintosh;IntelMacOSX10_7_0)AppleWebKit/535.11(KHTML,likeGecko)Chrome/17.0.963.56Safari/535.11',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36 Edg/84.0.522.44',
            'Mozilla/5.0(Macintosh;U;IntelMacOSX10_6_8;en-us)AppleWebKit/534.50(KHTML,likeGecko)Version/5.1Safari/534.50',
            'Mozilla/5.0(Windows;U;WindowsNT6.1;en-us)AppleWebKit/534.50(KHTML,likeGecko)Version/5.1Safari/534.50',
            'Mozilla/4.0(compatible;MSIE7.0;WindowsNT5.1;Trident/4.0;SE2.XMetaSr1.0;SE2.XMetaSr1.0;.NETCLR2.0.50727;SE2.XMetaSr1.0)',
            'Mozilla/4.0(compatible;MSIE7.0;WindowsNT5.1;360SE)',
            'Mozilla/4.0(compatible;MSIE7.0;WindowsNT5.1;Maxthon2.0)',
            'Mozilla/5.0(compatible;MSIE9.0;WindowsNT6.1;Trident/5.0;'
        ];
        $user_agent = $browser[rand(0, count($browser) - 1)];
        return $user_agent;
    }
}