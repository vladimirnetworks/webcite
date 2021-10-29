<?php
class UserAgent
{
    /**
     * Agent data stored in file agent_list.json.
     *
     * @var array
     */
    private static $agentDetails;

    /**
     * Grab a random user agent from the library's agent list
     *
     * @param  array $filterBy
     * @return string
     * @throws \Exception
     */
    public static function random($filterBy = [])
    {
        $agents = self::loadUserAgents($filterBy);

        if (empty($agents)) {
            throw new \Exception('No user agents matched the filter');
        }

        return $agents[mt_rand(0, count($agents) - 1)];
    }

    /**
     * Get all of the unique values of the device_type field, which can be used for filtering
     * Device types give a general description of the type of hardware that the agent is running,
     * such as "Desktop", "Tablet", or "Mobile"
     *
     * @return array
     */
    public static function getDeviceTypes()
    {
        return self::getField('device_type');
    }

    /**
     * Get all of the unique values of the agent_type field, which can be used for filtering
     * Agent types give a general description of the type of software that the agent is running,
     * such as "Crawler" or "Browser"
     *
     * @return array
     */
    public static function getAgentTypes()
    {
        return self::getField('agent_type');
    }

    /**
     * Get all of the unique values of the agent_name field, which can be used for filtering
     * Agent names are general identifiers for a given user agent. For example, "Chrome" or "Firefox"
     *
     * @return array
     */
    public static function getAgentNames()
    {
        return self::getField('agent_name');
    }

    /**
     * Get all of the unique values of the os_type field, which can be used for filtering
     * OS Types are general names given for an operating system, such as "Windows" or "Linux"
     *
     * @return array
     */
    public static function getOSTypes()
    {
        return self::getField('os_type');
    }

    /**
     * Get all of the unique values of the os_name field, which can be used for filtering
     * OS Names are more specific names given to an operating system, such as "Windows Phone OS"
     *
     * @return array
     */
    public static function getOSNames()
    {
        return self::getField('os_name');
    }

    /**
     * This is a helper for the publicly-exposed methods named get...()
     * @param  string $fieldName
     * @return array
     * @throws \Exception
     */
    private static function getField($fieldName)
    {
        $agentDetails = self::getAgentDetails();
        $values       = [];

        foreach ($agentDetails as $agent) {
            if (!isset($agent[$fieldName])) {
                throw new \Exception("Field name \"$fieldName\" not found, can't continue");
            }

            $values[] = $agent[$fieldName];
        }

        return array_values(array_unique($values));
    }

    /**
     * Validates the filter so that no unexpected values make their way through
     *
     * @param array $filterBy
     * @return array
     */
    private static function validateFilter($filterBy = [])
    {
        // Components of $filterBy that will not be ignored
        $filterParams = [
            'agent_name',
            'agent_type',
            'device_type',
            'os_name',
            'os_type',
        ];

        $outputFilter = [];

        foreach ($filterParams as $field) {
            if (!empty($filterBy[$field])) {
                $outputFilter[$field] = $filterBy[$field];
            }
        }

        return $outputFilter;
    }

    /**
     * Returns an array of user agents that match a filter if one is provided
     *
     * @param array $filterBy
     * @return array
     */
    private static function loadUserAgents($filterBy = [])
    {
        $filterBy = self::validateFilter($filterBy);

        $agentDetails = self::getAgentDetails();
        $agentStrings = [];

        for ($i = 0; $i < count($agentDetails); $i++) {
            foreach ($filterBy as $key => $value) {
                if (!isset($agentDetails[$i][$key]) || !self::inFilter($agentDetails[$i][$key], $value)) {
                    continue 2;
                }
            }
            $agentStrings[] = $agentDetails[$i]['agent_string'];
        }

        return array_values($agentStrings);
    }

    /**
     * return if key exist in array of filters
     *
     * @param  $key
     * @param  $array
     * @return bool
     */
    private static function inFilter($key, $array)
    {
        return in_array(strtolower($key), array_map('strtolower', (array) $array));
    }

    /**
     * @return array
     */
    private static function getAgentDetails()
    {
        if (!isset(self::$agentDetails)) {
            self::$agentDetails = json_decode('[
                {
                    "agent_string": "BaiDuSpider",
                    "agent_type": "Crawler",
                    "agent_name": "Baiduspider",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Baiduspider+(+http:\/\/www.baidu.com\/search\/spider.htm)",
                    "agent_type": "Crawler",
                    "agent_name": "Baiduspider",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Baiduspider+(+http:\/\/www.baidu.com\/search\/spider_jp.html)",
                    "agent_type": "Crawler",
                    "agent_name": "Baiduspider",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Bunjalloo\/0.7.6(Nintendo DS;U;en)",
                    "agent_type": "Console",
                    "agent_name": "Bunjalloo",
                    "os_type": "Nintendo DS",
                    "os_name": "Nintendo DS",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Googlebot-Image\/1.0",
                    "agent_type": "Crawler",
                    "agent_name": "Googlebot-Image",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Googlebot\/2.1 (+http:\/\/www.google.com\/bot.html)",
                    "agent_type": "Crawler",
                    "agent_name": "Googlebot",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Googlebot\/2.1 (+http:\/\/www.googlebot.com\/bot.html)",
                    "agent_type": "Crawler",
                    "agent_name": "Googlebot",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/2.0 (compatible; Ask Jeeves\/Teoma)",
                    "agent_type": "Crawler",
                    "agent_name": "Teoma",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/2.0 (compatible; Ask Jeeves\/Teoma; +http:\/\/about.ask.com\/en\/docs\/about\/webmasters.shtml)",
                    "agent_type": "Crawler",
                    "agent_name": "Teoma",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/2.0 (compatible; Ask Jeeves\/Teoma; +http:\/\/sp.ask.com\/docs\/about\/tech_crawling.html)",
                    "agent_type": "Crawler",
                    "agent_name": "Teoma",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident\/6.0)",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/4.0 (PS3 (PlayStation 3); 1.00)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "Playstation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/4.0 (PSP (PlayStation Portable); 2.00)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation Portable",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation Portable",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Mobile; rv:40.0) Gecko\/40.0 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Mobile; rv:41.0) Gecko\/41.0 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Mobile; rv:42.0) Gecko\/42.0 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Mobile; rv:43.0) Gecko\/43.0 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Mobile; rv:44.0) Gecko\/44.0 Firefox\/44.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Tablet; rv:39.0) Gecko\/39.0 Firefox\/39.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Tablet; rv:40.0) Gecko\/40.0 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Tablet; rv:41.0) Gecko\/41.0 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Tablet; rv:42.0) Gecko\/42.0 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Tablet; rv:43.0) Gecko\/43.0 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Android; Tablet; rv:44.0) Gecko\/44.0 Firefox\/44.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; Baiduspider\/2.0; +http:\/\/www.baidu.com\/search\/spider.html)",
                    "agent_type": "Crawler",
                    "agent_name": "Baiduspider",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; bingbot\/2.0 +http:\/\/www.bing.com\/bingbot.htm)",
                    "agent_type": "Crawler",
                    "agent_name": "Bingbot",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; bingbot\/2.0; +http:\/\/www.bing.com\/bingbot.htm)",
                    "agent_type": "Crawler",
                    "agent_name": "Bingbot",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; Googlebot\/2.1; +http:\/\/www.google.com\/bot.html)",
                    "agent_type": "Crawler",
                    "agent_name": "Googlebot",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident\/6.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C)",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident\/6.0; Xbox; Xbox One)",
                    "agent_type": "Browser",
                    "agent_name": "Xbox One",
                    "os_type": "Xbox",
                    "os_name": "Xbox One",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident\/6.0; IEMobile\/10.0; ARM; Touch; NOKIA; Lumia 929",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident\/6.0; IEMobile\/10.0; ARM; Touch; NOKIA; Lumia 930",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident\/6.0; IEMobile\/10.0; ARM; Touch; PRESTIGIO; PSP8400DUO)",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident\/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED\/1.0",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; Yahoo! Slurp China; http:\/\/misc.yahoo.com.cn\/help.html)",
                    "agent_type": "Crawler",
                    "agent_name": "Yahoo! Slurp China",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; Yahoo! Slurp; http:\/\/help.yahoo.com\/help\/us\/ysearch\/slurp)",
                    "agent_type": "Crawler",
                    "agent_name": "Yahoo! Slurp",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; YandexBot\/3.0; +http:\/\/yandex.com\/bots)",
                    "agent_type": "Crawler",
                    "agent_name": "YandexBot",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (compatible; YandexImages\/3.0; +http:\/\/yandex.com\/bots)",
                    "agent_type": "Crawler",
                    "agent_name": "YandexImages",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPad; CPU OS 8_1 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B411 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPad; CPU OS 8_1_1 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B436 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPad; CPU OS 8_1_2 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B440 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPad; CPU OS 8_1_3 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B466 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPad; CPU OS 8_2 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12D508 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPad; CPU OS 8_3 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12F69 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPad; CPU OS 8_4 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12H143 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B411 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_1_1 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B435 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_1_2 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B440 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_1_3 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12B466 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_2 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12D508 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_3 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) CriOS\/42.0.2311.47 Mobile\/12F70 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_3 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) CriOS\/43.0.2357.61 Mobile\/12F70 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_3 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12F70 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (iPhone; CPU iPhone OS 8_4 like Mac OS X) AppleWebKit\/600.1.4 (KHTML, like Gecko) Version\/8.0 Mobile\/12H143 Safari\/600.1.4",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "iOS",
                    "os_name": "iPhone OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; Android 4.1.2; GT-I9100 Build\/JZO54K) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/32.0.1700.99 Mobile Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; Android 4.1.2; GT-I9100 Build\/JZO54K) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/33.0.1750.117 Mobile Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; Android 4.4.2; GT-I9100 Build\/KVT49L) AppleWebKit\/537.36 (KHTML, like Gecko) Version\/4.0 Chrome\/30.0.0.0 Mobile Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; Android 4.4.2; GT-I9505 Build\/KOT4H9) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/39.0.2171.59 Mobile Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; Android 4.4.4; SM-N910F Build\/KTU84P) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/39.0.2171.59 Mobile Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; Android 5.0; Nexus 4 Build\/LRX21T) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/39.0.2171.59 Mobile Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; U; Android 4.0.3; en-us; Galaxy S II Build\/GRJ22) AppleWebKit\/534.30 (KHTML, like Gecko) Version\/4.0 Mobile Safari\/534.30",
                    "agent_type": "Browser",
                    "agent_name": "Mobile Safari",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; U; Android 4.1.1; en-us; SonyC1505 Build\/11.3.A.0.47) AppleWebKit\/534.30 (KHTML, like Gecko) Version\/4.0 Mobile Safari\/534.30",
                    "agent_type": "Browser",
                    "agent_name": "Mobile Safari",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; U; Android 4.3.1; en-us; GT-I9100 Build\/JLS36I) AppleWebKit\/534.30 (KHTML, like Gecko) Version\/4.0 Mobile Safari\/534.30 CyanogenMod\/10.2\/i9100",
                    "agent_type": "Browser",
                    "agent_name": "Mobile Safari",
                    "os_type": "Android",
                    "os_name": "Android",
                    "device_type": "Tablet"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Linux; U; X11; en-US; Valve Steam GameOverlay\/1424305157; ) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/35.0.1916.86 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.10; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.10; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.10; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.10; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.10; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.8; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.9; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.9; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.9; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.9; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.9; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36 OPR\/25.0.1614.71",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36 OPR\/25.0.1614.71",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36 OPR\/25.0.1614.71",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit\/600.3.10 (KHTML, like Gecko) Version\/8.0.3 Safari\/600.3.10",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36 OPR\/25.0.1614.71",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/600.4.10 (KHTML, like Gecko) Version\/8.0.4 Safari\/600.4.10",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/600.5.17 (KHTML, like Gecko) Version\/8.0.5 Safari\/600.5.17",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/600.5.6 (KHTML, like Gecko) Version\/8.0.5 Safari\/600.5.6",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit\/600.6.3 (KHTML, like Gecko) Version\/8.0.6 Safari\/600.6.3",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/600.6.3 (KHTML, like Gecko) Version\/8.0.6 Safari\/600.6.3",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/600.7.10 (KHTML, like Gecko) Version\/8.0.7 Safari\/600.7.10",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/600.7.11 (KHTML, like Gecko) Version\/8.0.7 Safari\/600.7.11",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/600.7.12 (KHTML, like Gecko) Version\/8.0.7 Safari\/600.7.12",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit\/600.7.7 (KHTML, like Gecko) Version\/8.0.7 Safari\/600.7.7",
                    "agent_type": "Browser",
                    "agent_name": "Safari",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Macintosh; U; Intel Mac OS X; en-us) AppleWebKit\/537+ (KHTML, like Gecko) Version\/5.0 Safari\/537.6+ Midori\/0.4",
                    "agent_type": "Browser",
                    "agent_name": "Midori",
                    "os_type": "OS X",
                    "os_name": "OS X",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; OPENC; rv:40.0) Gecko\/40.0 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; OPENC; rv:41.0) Gecko\/41.0 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; OPENC; rv:42.0) Gecko\/42.0 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; OPENC; rv:43.0) Gecko\/43.0 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; OPENC; rv:44.0) Gecko\/44.0 Firefox\/44.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; OPENC; rv:45.0) Gecko\/45.0 Firefox\/45.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; rv:40.0) Gecko\/40.0 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; rv:41.0) Gecko\/41.0 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; rv:42.0) Gecko\/42.0 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; rv:43.0) Gecko\/43.0 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; rv:44.0) Gecko\/44.0 Firefox\/44.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; rv:45.0) Gecko\/45.0 Firefox\/45.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Firefox OS",
                    "os_name": "Firefox OS",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; HTC; HTC6690LVW) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 1520) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 521) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 630) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 810) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 820) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 822) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 830) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 920) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 925) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 928) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; SAMSUNG; GT-I8750) like iPhone OS 7_0_3 Mac OS X AppleWebKit\/537 (KHTML, like Gecko) Mobile Safari\/537",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 1.0)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 1.00)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 1.10)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 1.5)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 1.70)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 1.90)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 2.00)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 3.55)",
                    "agent_type": "Console",
                    "agent_name": "PlayStation 3",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 4.50) AppleWebKit\/531.22.8 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 4.53) AppleWebKit\/531.22.8 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 4.55) AppleWebKit\/531.22.8 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 4.60) AppleWebKit\/531.22.8 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PLAYSTATION 3; 4.66) AppleWebKit\/531.22.8 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 3",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation 4 2.00) AppleWebKit\/537.73 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 4",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation 4 2.01) AppleWebKit\/537.73 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 4",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation 4 2.02) AppleWebKit\/537.73 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 4",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation 4 2.03) AppleWebKit\/537.73 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 4",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation 4 2.04) AppleWebKit\/537.73 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 4",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation 4 2.50) AppleWebKit\/537.73 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 4",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation 4 2.51) AppleWebKit\/537.73 (KHTML, like Gecko)",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation 4",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation Vita 3.36) AppleWebKit\/537.73 (KHTML, like Gecko) Silk\/3.2",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation Vita",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (PlayStation Vita 3.50) AppleWebKit\/537.73 (KHTML, like Gecko) Silk\/3.2",
                    "agent_type": "Browser",
                    "agent_name": "NetFront",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation Vita",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.135 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36 OPR\/30.0.1835.49",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36 OPR\/30.0.1835.88",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; rv:36.0) Gecko\/20100101 Firefox\/36.0 Seamonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; rv:38.2.1) Gecko\/20100101 Firefox\/38.2.1",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; Trident\/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; rv:11.0) like Gecko",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.135 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; Win64; x64; rv:24.0) Gecko\/20140129 Firefox\/24.0 PaleMoon\/24.3.1",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; Win64; x64; rv:24.7) Gecko\/20140907 Firefox\/24.7 PaleMoon\/24.7.2",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko\/20141021 Firefox\/24.9 PaleMoon\/25.0.2",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36 OPR\/30.0.1835.49",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36 OPR\/30.0.1835.88",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:36.0) Gecko\/20100101 Firefox\/36.0 Seamonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36 OPR\/30.1835.49 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36 OPR\/30.0.1835.88",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; ARM; Trident\/7.0; Touch; rv:11.0; WPDesktop; Lumia 730 Dual SIM) like Gecko",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; ARM; Trident\/7.0; Touch; rv:11.0; WPDesktop; Lumia 928) like Gecko",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; ARM; Trident\/7.0; Touch; rv:11.0; WPDesktop; NOKIA; Lumia 1320) like Gecko",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; rv:36.0) Gecko\/20100101 Firefox\/36.0 Seamonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64; rv:24.0) Gecko\/20140129 Firefox\/24.0 PaleMoon\/24.3.1",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64; rv:24.7) Gecko\/20140907 Firefox\/24.7 PaleMoon\/24.7.2",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; Win64; x64; rv:25.0) Gecko\/20141021 Firefox\/24.9 PaleMoon\/25.0.2",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36 OPR\/30.1835.49 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36 OPR\/30.0.1835.88",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64; rv:36.0) Gecko\/20100101 Firefox\/36.0 Seamonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.2; WOW64; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows 8",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36 OPR\/30.1835.49 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36 OPR\/30.0.1835.88",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; rv:36.0) Gecko\/20100101 Firefox\/36.0 Seamonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64; rv:24.0) Gecko\/20140129 Firefox\/24.0 PaleMoon\/24.3.1",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64; rv:24.7) Gecko\/20140907 Firefox\/24.7 PaleMoon\/24.7.2",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; Win64; x64; rv:25.0) Gecko\/20141021 Firefox\/24.9 PaleMoon\/25.0.2",
                    "agent_type": "Browser",
                    "agent_name": "Pale Moon",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36 OPR\/30.1835.49 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.2357.125 Safari\/537.36 OPR\/30.0.1835.88",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64; rv:36.0) Gecko\/20100101 Firefox\/36.0 Seamonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows NT 6.3; WOW64; Trident\/7.0; .NET4.0E; .NET4.0C; .NET CLR 3.5.30729; .NET CLR 2.0.50727; .NET CLR 3.0.30729; InfoPath.3; rv:11.0) like Gecko",
                    "agent_type": "Browser",
                    "agent_name": "Internet Explorer",
                    "os_type": "Windows",
                    "os_name": "Windows NT",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows Phone 8.1; ARM; Trident\/7.0; Touch; rv:11.0; IEMobile\/11.0; NOKIA; Lumia 1320) like Gecko",
                    "agent_type": "Browser",
                    "agent_name": "IE Mobile",
                    "os_type": "Windows",
                    "os_name": "Windows Phone",
                    "device_type": "Mobile"
                },
                {
                    "agent_string": "Mozilla\/5.0 (Windows; Windows i686) KHTML\/4.10.2 (like Gecko) Konqueror\/4.10",
                    "agent_type": "Browser",
                    "agent_name": "Konqueror",
                    "os_type": "Windows",
                    "os_name": "Windows 7",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.65 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD amd64; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD i386; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; FreeBSD) KHTML\/4.9.1 (like Gecko) Konqueror\/4.9",
                    "agent_type": "Browser",
                    "agent_name": "Konqueror",
                    "os_type": "BSD",
                    "os_name": "FreeBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.21 (KHTML, like Gecko) konqueror\/4.14.2 Safari\/537.21",
                    "agent_type": "Browser",
                    "agent_name": "Konqueror",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.21 (KHTML, like Gecko) QupZilla\/1.6.6 Safari\/537.21",
                    "agent_type": "Browser",
                    "agent_name": "QupZilla",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.21 (KHTML, like Gecko) rekonq\/2.4.2 Safari\/537.21",
                    "agent_type": "Browser",
                    "agent_name": "rekonq",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/41.0.2272.118 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.135 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.90 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/36.0.1976.2 Chrome\/36.0.1976.2 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/36.0.1985.143 Chrome\/36.0.1985.143 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/37.0.2062.120 Chrome\/37.0.2062.120 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/37.0.2062.94 Chrome\/37.0.2062.94 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/38.0.2125.111 Chrome\/38.0.2125.111 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686) AppleWebKit\/538.15 (KHTML, like Gecko) Version\/8.0 Safari\/538.15 Ubuntu\/14.10 (3.10.3-0ubuntu3) Epiphany\/3.10.3",
                    "agent_type": "Browser",
                    "agent_name": "Epiphany",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:41.0) Gecko\/20100101 Firefox\/41.0 Iceweasel\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Iceweasel",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:42.0) Gecko\/20100101 Firefox\/42.0 Iceweasel\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Iceweasel",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:43.0) Gecko\/20100101 Firefox\/43.0 Iceweasel\/43.0.1",
                    "agent_type": "Browser",
                    "agent_name": "Iceweasel",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux i686; rv:44.0) Gecko\/20100101 Firefox\/44.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.21 (KHTML, like Gecko) konqueror\/4.14.2 Safari\/537.21",
                    "agent_type": "Browser",
                    "agent_name": "konqueror",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.21 (KHTML, like Gecko) QupZilla\/1.6.6 Safari\/537.21",
                    "agent_type": "Browser",
                    "agent_name": "QupZilla",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.21 (KHTML, like Gecko) rekonq\/2.4.2 Safari\/537.21",
                    "agent_type": "Browser",
                    "agent_name": "rekonq",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/41.0.2272.118 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.135 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.152 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/42.0.2311.90 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/44.0.2403.52 Safari\/537.36 OPR\/31.0.1889.50 (Edition beta)",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/36.0.1976.2 Chrome\/36.0.1976.2 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/36.0.1985.143 Chrome\/36.0.1985.143 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/37.0.2062.120 Chrome\/37.0.2062.120 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/37.0.2062.94 Chrome\/37.0.2062.94 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/38.0.2125.111 Chrome\/38.0.2125.111 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chromium",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/538.15 (KHTML, like Gecko) Version\/8.0 Safari\/538.15 Ubuntu\/14.10 (3.10.3-0ubuntu3) Epiphany\/3.10.3",
                    "agent_type": "Browser",
                    "agent_name": "Epiphany",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:41.0) Gecko\/20100101 Firefox\/41.0 Iceweasel\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Iceweasel",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:42.0) Gecko\/20100101 Firefox\/42.0 Iceweasel\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Iceweasel",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:43.0) Gecko\/20100101 Firefox\/43.0 Iceweasel\/43.0.1",
                    "agent_type": "Browser",
                    "agent_name": "Iceweasel",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Linux x86_64; rv:44.0) Gecko\/20100101 Firefox\/44.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Linux",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; NetBSD amd64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "BSD",
                    "os_name": "NetBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; NetBSD amd64; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "BSD",
                    "os_name": "NetBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.65 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD amd64; rv:43.0) Gecko\/20100101 Firefox\/43.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.124 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.125 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.130 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD i386) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/43.0.2357.81 Safari\/537.36",
                    "agent_type": "Browser",
                    "agent_name": "Chrome",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; OpenBSD i386; rv:36.0) Gecko\/20100101 Firefox\/36.0 SeaMonkey\/2.33.1",
                    "agent_type": "Browser",
                    "agent_name": "Seamonkey",
                    "os_type": "BSD",
                    "os_name": "OpenBSD",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Ubuntu; Linux i686; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Ubuntu; Linux i686; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Ubuntu; Linux i686; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Ubuntu; Linux x86_64; rv:40.0) Gecko\/20100101 Firefox\/40.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Ubuntu; Linux x86_64; rv:41.0) Gecko\/20100101 Firefox\/41.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Mozilla\/5.0 (X11; Ubuntu; Linux x86_64; rv:42.0) Gecko\/20100101 Firefox\/42.0",
                    "agent_type": "Browser",
                    "agent_name": "Firefox",
                    "os_type": "Linux",
                    "os_name": "Ubuntu",
                    "device_type": "Desktop"
                },
                {
                    "agent_string": "Opera\/9.10 (Nintendo Wii; U; ; 1621; en)",
                    "agent_type": "Browser",
                    "agent_name": "Opera",
                    "os_type": "Nintendo Wii",
                    "os_name": "Nintendo Wii",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Opera\/9.30 (Nintendo Wii; U; ; 2047-7; en)",
                    "agent_type": "Browser",
                    "agent_name": "Opera",
                    "os_type": "Nintendo Wii",
                    "os_name": "Nintendo Wii",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Opera\/9.30 (Nintendo Wii; U; ; 3642; en)",
                    "agent_type": "Browser",
                    "agent_name": "Opera",
                    "os_type": "Nintendo Wii",
                    "os_name": "Nintendo Wii",
                    "device_type": "Console"
                },
                {
                    "agent_string": "PSP (PlayStation Portable); 2.00",
                    "agent_type": "Console",
                    "agent_name": "PlayStation Portable",
                    "os_type": "PlayStation",
                    "os_name": "PlayStation Portable",
                    "device_type": "Console"
                },
                {
                    "agent_string": "YahooSeeker-Testing\/v3.9 (compatible; Mozilla 4.0; MSIE 5.5; http:\/\/search.yahoo.com\/)",
                    "agent_type": "Crawler",
                    "agent_name": "YahooSeeker-Testing",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "YahooSeeker\/1.2 (compatible; Mozilla 4.0; MSIE 5.5; yahooseeker at yahoo-inc dot com ; http:\/\/help.yahoo.com\/help\/us\/shop\/merchant\/)",
                    "agent_type": "Crawler",
                    "agent_name": "YahooSeeker",
                    "os_type": "unknown",
                    "os_name": "unknown",
                    "device_type": "Crawler"
                },
                {
                    "agent_string": "Opera\/9.00 (Nintendo Wii; U; ; 1309-9; en)",
                    "agent_type": "Browser",
                    "agent_name": "Opera",
                    "os_type": "Nintendo Wii",
                    "os_name": "Nintendo Wii",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Opera\/9.10 (Nintendo Wii; U; ; 1621; en)",
                    "agent_type": "Browser",
                    "agent_name": "Opera",
                    "os_type": "Nintendo Wii",
                    "os_name": "Nintendo Wii",
                    "device_type": "Console"
                },
                {
                    "agent_string": "Opera\/9.30 (Nintendo Wii; U; ; 2047-7; en)",
                    "agent_type": "Browser",
                    "agent_name": "Opera",
                    "os_type": "Nintendo Wii",
                    "os_name": "Nintendo Wii",
                    "device_type": "Console"
                }
            ]', true);
        }

        return self::$agentDetails;
    }
}