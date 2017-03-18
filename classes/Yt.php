<?php namespace Mavitm\Youtubechannel\Classes;

use League\Flysystem\Exception;

class Yt
{
    use \October\Rain\Support\Traits\Singleton;

    public  $data,
            $embedWidth = '100%',
            $embedHeight = '315';


    /*
       [
           https://www.youtube.com/feeds/videos.xml?channel_id=CHANNELID
           https://www.youtube.com/feeds/videos.xml?user=USERNAME
           https://www.youtube.com/feeds/videos.xml?playlist_id=YOUR_YOUTUBE_PLAYLIST_NUMBER
       ]
   */
    public   $embedUrl   = "https://www.youtube.com/embed/",
             $xmlUrl     = "https://www.youtube.com/feeds/videos.xml"; //?parameter


    public $ytUrl = array(
        "regex"         => '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@?&%=+\/\$_.-]*~i',
        "embedUrl"      => 'http://www.youtube.com/embed/$1',
        "code"          => '<iframe width="%s" height="%s" src="%s" frameborder="0" allowfullscreen></iframe>',
        "viewType"      => "iframe"
    );

    public function setWidth($width){
        $this->embedWidth = $width;
        return $this;
    }
    public function setHeight($height){
        $this->embedHeight = $height;
        return $this;
    }

    public function rrsReader($url)
    {
        try{
            $source = $this->curl_get_contents($url);
        }catch(\Exception $e){
            $source = file_get_contents($url);
        }

        try{
            $data = simplexml_load_string( $source );
        }catch(\Exception $e){
            return [];
        }

        $arr = (array)$data;
        return $this->data = $this->listSetValues($arr['entry']); //(array)$data;
    }

    public function listSetValues($entry)
    {

        //return $entry;
        if(!empty($entry) && is_array($entry)){
            foreach ($entry as $key => $value) {

                $id = @end(explode(':', $value->id));
                $entry[$key]->v         = $id;
                $entry[$key]->embed     = $this->embedUrl.$id;
                $entry[$key]->autoEmbed = $this->embedUrl.$id.'?autoplay=1';
                $entry[$key]->image     = $this->yThumbGet($id);
                $entry[$key]->url       = "http://www.youtube.com/watch?v=".$id;
            }
        }
        return $entry;
    }

    public function eq($index){
        $arr = (array) $this->data;
        if($index < 0){
            return $arr[$index];
        }
        return end($arr);
    }

    public function first(){
        return $this->eq(0);
    }

    public function last(){
        return $this->eq(-1);
    }

    public function find($youtebeVideoID){
        if(empty($this->data)){
           return [];
        }
        foreach ($this->data as $key => $value) {
            if($youtebeVideoID == $value->v){
                return $value;
            }
        }
        return [];
    }

    public function yThumbGet($id){
        return "https://i".rand(1,4).".ytimg.com/vi/".$id."/hqdefault.jpg";
    }

    public function urlToEmbed($url, $autoplay = 0, $with = null, $height = null){

        if(!$with){
            $with = $this->embedWidth;
        }

        if(!$height){
            $height = $this->embedHeight;
        }

        $url = preg_replace($this->ytUrl['regex'], $this->ytUrl['embedUrl'], $url);
        if($autoplay){ $url .= '?autoplay=1';}
        return vsprintf($this->ytUrl['code'], [$with, $height, $url]);
    }

    public function getVideo($id){
        if(is_numeric($id)){
            $arr = $this->eq($id);
        }else{
            $arr = $this->find($id);
        }
        if(!empty($arr)){
            return $this->urlToEmbed($arr->url);
        }
    }

    function curl_get_contents($url, $method='', $vars='') {
        $ch = curl_init();
        $header[] = "ACCEPT: ".$_SERVER['HTTP_ACCEPT'];

        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE);
        $buffer = curl_exec($ch);
        curl_close($ch);
        return $buffer;
    }
}