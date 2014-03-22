<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateData extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'data:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates track and artist data.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$channels = DB::table('channels')
            ->select('*')
            ->where('active', '=', 1)
            ->get();

        foreach ($channels as $channel) {
            $this->line('Checking: ' . $channel->name);
            // First get the uploads playlist id
            $youtubeChannel = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/channels?key='.Config::get('app.youtubeAPIKey').'&part=id,snippet,statistics,contentDetails&forUsername=' . $channel->name));
            $youtubeChannelData = $youtubeChannel->items[0];
            $channelUploadsPlaylistId = $youtubeChannelData->contentDetails->relatedPlaylists->uploads;

            // Get the items in the playlist
            $playlistItems = json_decode(
                file_get_contents(
                    'https://www.googleapis.com/youtube/v3/playlistItems?key='.Config::get('app.youtubeAPIKey').'&part=id,snippet,contentDetails&playlistId=' . $channelUploadsPlaylistId . '&maxResults=50'
                ));
            $this->line($channelUploadsPlaylistId);

            // Keep grabbing playlist items until nextPageToken is null
            while (isset($playlistItems->nextPageToken)) {
                $currentPageToken = $playlistItems->nextPageToken;
                $playlistItems = json_decode(
                    file_get_contents(
                        'https://www.googleapis.com/youtube/v3/playlistItems?key='.Config::get('app.youtubeAPIKey').'&part=id,snippet,contentDetails&playlistId=' . $channelUploadsPlaylistId . '&maxResults=50&pageToken='.$currentPageToken
                    ));

                // Start going through each video in the playlist
                foreach ($playlistItems->items as $playlistItem) {
                    $videoData = $playlistItem->snippet;

                    // Make sure the video is a video we actually want
                    // Should not contain 'teaser' in the title, and should match the filter regex for the channel.
                    if (!$this->containsTeaser($videoData->title) && preg_match($channel->filter, $videoData->title)) {

                        // Only need to add videos that haven't already been added
                        if (!$this->videoExists($videoData->resourceId->videoId)) {
                            $this->line('Attempting to add: ' . $videoData->title);

                            // Attempt to get the artist from the video title
                            if (preg_match($channel->artist_filter_regex, $videoData->title, $matches)) {
                                $artistFragment = $matches[0];

                                // Once we have the artist, we just remove the artist from the original video
                                // title to get the title of the track
                                $titleFragment = str_replace($artistFragment, '', $videoData->title);
                                // Clean up title
                                if ($channel->title_replace_chars !== '') {
                                    $titleReplacementChars = explode(',', $channel->title_replace_chars);
                                    $titleFragment = str_replace($titleReplacementChars, '', $titleFragment);
                                    $titleFragment = trim($titleFragment);
                                }
                                $this->line('Title: ' . trim($titleFragment));

                                // Clean up the artist name
                                $artistReplacementChars = explode(',', $channel->artist_replace_chars);
                                $artistFragment = str_replace($artistReplacementChars, '', $artistFragment);
                                $artistFragment = trim($artistFragment);
                                $this->line('Artist: ' . trim($artistFragment));

                                $artistInsertId = -1;
                                // Check if the artist already exists in the database
                                $artist = DB::table('artists')->select('*')->where('name', '=', $artistFragment)->get();

                                // If the artist already exists, use that id
                                // If artist doesn't exist yet, add them to the database
                                if (count($artist) > 0) {
                                    $artist = $artist[0];
                                    $this->line('This artist exists' . $artist->id . ' - ' . $artist->name);
                                    $artistInsertId = $artist->id;
                                } else {
                                    $artistInsertId = DB::table('artists')->insertGetId(array(
                                        'name' => $artistFragment
                                    ));
                                }

                                // Now that we have the artist id, insert the new track
                                DB::table('tracks')->insert(array(
                                    'title' => $titleFragment,
                                    'artist' => $artistInsertId,
                                    'videoId' => $videoData->resourceId->videoId,
                                    'channelId' => $channel->id,
                                    'uploaded' => $videoData->publishedAt,
                                    'viewCount' => $this->getViewCount($videoData->resourceId->videoId),
                                    'published' => 1
                                ));
                            }
                        }
                    }
                }

            }

            $this->line('finished');
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

    private function containsTeaser($title) {
        return strpos(strtolower($title), 'teaser');
    }

    private function videoExists($videoid) {
        $videoExists = DB::table('tracks')->select('videoid')->where('videoid', '=', $videoid)->count();

        if ($videoExists > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function getViewCount($videoid) {
        $videoJson = json_decode(
            file_get_contents(
                'https://www.googleapis.com/youtube/v3/videos?key='.Config::get('app.youtubeAPIKey').'&part=id,snippet,contentDetails,statistics&id=' . $videoid
            ));
        $video = $videoJson->items[0];

        return $video->statistics->viewCount;
    }

}