<?php
class YouTubeAPIService {
    public function __construct() {

    }

    public function getChannelByName($name) {
        $youtubeChannel = json_decode(
            file_get_contents(
                'https://www.googleapis.com/youtube/v3/channels?key='.Config::get('app.youtubeAPIKey').'&part=id,snippet,statistics,contentDetails&forUsername=' . $name
            ));

        return $youtubeChannel->items[0];
    }

    public function getPlaylistItemsById($playlistId, $nextPageToken = null) {
        $nextPageTokenParam = '';
        if (!is_null($nextPageToken)) {
            $nextPageToken = '&nextPageToken=' . $nextPageToken;
        }

        $playlistItems = json_decode(
            file_get_contents(
                'https://www.googleapis.com/youtube/v3/playlistItems?key='.Config::get('app.youtubeAPIKey').'&part=id,snippet,contentDetails&playlistId=' . $playlistId . '&maxResults=50' . $nextPageToken
            ));

        return $playlistItems;
    }

    public function getChannelUploads($name) {
        $youtubeChannelData = $this->getChannelByName($name);
        $uploadsPlaylistId = $youtubeChannelData->contentDetails->relatedPlaylists->uploads;

        // Get first set of items in the playlist
        $playlistItems = $this->getPlaylistItemsById($uploadsPlaylistId);

        // Keep grabbing playlist items until nextPageToken is null
        while (isset($playlistItems->nextPageToken)) {
            $currentPageToken = $playlistItems->nextPageToken;
            $playlistItems = $this->getPlaylistItemsById($uploadsPlaylistId, $currentPageToken);

            // Start going through each video in the playlist
            foreach ($playlistItems->items as $playlistItem) {
                $videoData = $playlistItem->snippet;
            }
        }
    }
}
