<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

use App\Services\GoogleServiceReal;
use Google\Service\YouTube as YT;

use App\Models\ChannelDetails;

class Youtube extends Component
{
    use WithFileUploads;

    protected $listeners = ['channel' => 'changeSession'];

    public $auth_url;

    public $title;

    public $description;

    public $tags;

    public $category;

    public $status;

    public $video;

    public $playlist;

    public $channel;


    protected $rules = [
        'title' => 'required',
        'description' => 'required',
        'tags' => 'nullable'
    ];

    public function render()
    {
        $data = array();

        $google = new GoogleServiceReal('http://localhost:8000/youtube', null);

        if(session('youtube-token')) {
            $data['videoCategories'] = $google->getVideoCategories();

            $data['channels'] = auth()->user()->channel_detail;


            $queryParams = [
                'maxResults' => 10,
                'mine' => true
            ];
            $data['playlists'] = $google->youtube()->playlists->listPlaylists('status,snippet,contentDetails', $queryParams);

            // dump($data['playlists']);
        }

        // baru
        if(auth()->check()) {
            $data['channels'] = auth()->user()->channel_detail;
        }

        return view('livewire.youtube', $data);
    }

    public function submit()
    {
        $validated = $this->validate();

        $snippet = new YT\VideoSnippet();
        $snippet->setTitle($this->title);
        $snippet->setDescription($this->description);
        $snippet->setTags(explode(',',$this->tags));
        $snippet->setCategoryId($this->category);

        $playlistItem = new YT\PlaylistItem();

        $playlistItemSnippet = new YT\PlaylistItemSnippet();
        $playlistItemSnippet->setPlaylistId($this->playlist);

        // $resourceId = new YT\ResourceId();
        // $resourceId->setVideoId()


        $status = new YT\VideoStatus();
        $status->privacyStatus = $this->status;

        $video = new YT\Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        // chunk size
        $chunkSizeBytes = 1 * 1024 * 1024;

        $youtube = (new GoogleServiceReal())->youtube();
        $insertRequest = $youtube->videos->insert(
            "status,snippet",
            $video,
            array(
                'data' => file_get_contents($this->video->path()),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            )
        );

        dump($insertRequest->getSnippet(), $insertRequest->getStatus());

        // Create a MediaFileUpload object for resumable uploads.
        // $media = new \Google\Http\MediaFileUpload(
        //     (new GoogleServiceReal())->google,
        //     $insertRequest,
        //     'video/*',
        //     null,
        //     true,
        //     $chunkSizeBytes
        // );
        // $media->setFileSize(filesize($this->video->path()));

        // // Read the media file and upload it chunk by chunk.
        // $status = false;
        // $handle = fopen($this->video->path(), "rb");
        // while (!$status && !feof($handle)) {
        //     $chunk = fread($handle, $chunkSizeBytes);
        //     $status = $media->nextChunk($chunk);
        // }

        // fclose($handle);

        // dump($status);
        // dump(($this->video->path()));
    }

    public function channel($val)
    {
        $this->emit('channel', $val);
    }

    public function changeSession($val)
    {
        $channel = ChannelDetails::where('channel_id', $val)->first();

        return session(array(
            'youtube-token' => [
                'access_token' => $channel['access_token'],
                'expires_in' => $channel['expires_in'],
                'refresh_token' => $channel['refresh_token'],
                'scope' => $channel['scope'],
                'token_type' => $channel['token_type']

            ]
        ));
    }
}
