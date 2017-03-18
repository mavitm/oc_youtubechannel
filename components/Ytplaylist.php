<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
namespace Mavitm\Youtubechannel\Components;


use Cms\Classes\ComponentBase;
use Mavitm\Youtubechannel\Classes\Yt;

class Ytplaylist extends ComponentBase
{

    public  $youtubeID          = 0,
            $feedType           = "channel",
            $autoPlayer         = 0,
            $playerWidth        = "100%",
            $playerHeight       = 480,
            $putCss             = 0,
            $thumbWidth         = 150,
            $thumbHeight        = 110;

    public  $data               = [];
    public  $firstEmbed         = '';

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.youtubechannel::lang.Ytplaylist',
            'description' => 'mavitm.youtubechannel::lang.YtplaylistDesc'
        ];
    }

    public function defineProperties()
    {
        return [
            'feedType' => [
                'title'       => 'mmavitm.youtubechannel::lang.feedType',
                'description' => 'mavitm.youtubechannel::lang.feedTypeDesc',
                'type'        => 'dropdown',
                'options'     => [
                    "user"      => "User",
                    "channel"   => "Channel",
                    "playlist"  => "Play list"
                ],
                'default'     => "channel"
            ],
            'youtubeID' => [
                'title'       => 'mavitm.youtubechannel::lang.youtubeID',
                'description' => 'mavitm.youtubechannel::lang.youtubeIDDesc',
                'type'        => 'string'
            ],
            'autoPlayer' => [
                'title'       => 'mavitm.youtubechannel::lang.autoPlayers',
                'description' => '',
                'type'        => 'checkbox',
                'default'     => 0
            ],
            'playerWidth' => [
                'title'       => 'mavitm.youtubechannel::lang.playerWidth',
                'type'        => 'string',
                'default'     => '100%'
            ],
            'playerHeight' => [
                'title'       => 'mavitm.youtubechannel::lang.playerHeight',
                'type'        => 'string',
                'default'     => '480'
            ],
            'putCss' => [
                'title'       => 'mavitm.youtubechannel::lang.putCss',
                'description' => '',
                'type'        => 'checkbox',
                'default'     => 0
            ],
            'thumbWidth' => [
                'title'       => 'mavitm.youtubechannel::lang.thumbWidth',
                'type'        => 'string',
                'default'     => '150'
            ],
            'thumbHeight' => [
                'title'       => 'mavitm.youtubechannel::lang.thumbHeight',
                'type'        => 'string',
                'default'     => '110'
            ],

        ];
    }


    public function onRun()
    {

        /*
            $youtubeID          = 0,
            $feedType           = "channel",
            $autoPlayer         = 0,
            $playerWidth        = "100%",
            $playerHeight       = 480,
            $putCss             = 0;
            $thumbWidth         = 150;
            $thumbHeight        = 110;
        */

        $this->youtubeID    = $this->page['youtubeID']      = $this->property('youtubeID');
        $this->feedType     = $this->page['feedType']       = $this->property('feedType');
        $this->autoPlayer   = $this->page['autoPlayer']     = $this->property('autoPlayer');
        $this->playerWidth  = $this->page['playerWidth']    = $this->property('playerWidth');
        $this->playerHeight = $this->page['playerHeight']   = $this->property('playerHeight');
        $this->putCss       = $this->page['putCss']         = $this->property('putCss');
        $this->thumbWidth   = $this->page['thumbWidth']     = intval($this->property('thumbWidth'));
        $this->thumbHeight  = $this->page['thumbHeight']    = intval($this->property('thumbHeight'));
        
        switch ($this->feedType) {
            case 'user':
                $url = Yt::instance()->xmlUrl."?user=".$this->youtubeID;
                break;

            case 'playlist':
                $url = Yt::instance()->xmlUrl."?playlist_id=".$this->youtubeID;
                break;    
            
            default:
                $url = Yt::instance()->xmlUrl."?channel_id=".$this->youtubeID;
                break;
        }

        $this->data         = $this->page['data']           = Yt::instance()->rrsReader($url);
        $this->firstEmbed   = $this->page['firstEmbed']     = Yt::instance()->urlToEmbed(
                                                                                Yt::instance()->first()->url,
                                                                                $this->autoPlayer,
                                                                                $this->playerWidth,
                                                                                $this->playerHeight
                                                                            );
//        echo "<pre>";
//               print_r($this->data);
//        echo "</pre>";
    }



}


?>