<?php
namespace Gosu\Repositories;

use Track;

class MySQLTracksRepository implements TracksRepositoryInterface {
    protected $track;

    public function __construct(Track $track) {
        $this->track = $track;
    }

    public function all() {
        return $this->track->all()->toArray();
    }

    public function allSorted($sortType, $order) {
        return $this->track->getAllSorted($sortType, $order);
    }

    public function find($id) {
        return $this->track->getTrack($id)[0];
    }

    public function forPage($page, $settings) {
        return $this->track->getTracksForPage($page, $settings);
    }

    public function pageCount() {
        return $this->track->getTracksTotalPageCount();
    }

    public function filter($filterOptions) {
        return $this->track->filter($filterOptions);
    }

    public function getStats($trackid) {
        return $this->track->getStats($trackid);
    }

    public function comingSoon($count) {
        return $this->track->getComingSoon($count);
    }

    public function updateData($trackid) {
        $allTracks = $this->track->getAll();

        foreach ($allTracks as $track) {
            $json = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=AIzaSyAyoysE1W7YRwXogowu19rUJoTdCIOCnCE&part=id,snippet,statistics&id=" . $track->videoId));

            if (isset($json->items) && count($json->items) > 0) {
                $videoData = $json->items[0];
                $thumbUrl = $videoData->snippet->thumbnails->high->url;
                $thumbOutputPath = '/var/www/yourkpop_thumbs/'. $track->videoId .'.jpg';

                // Save thumbnail if it doesn't exist yet
                if (!file_exists($thumbOutputPath)) {
                    file_put_contents($thumbOutputPath, file_get_contents($thumbUrl));
                }

                if (isset($videoData->statistics)) {
                    $viewCount = $videoData->statistics->viewCount;

                    // Only update view count if our currently stored view count is off by 25%
                    if ($viewCount - $track->viewCount > $viewCount * 0.25) {
                        DB::table('tracks')
                            ->where('id', '=', $track->id)
                            ->update(array(
                                'viewCount' => $viewCount
                            ));
                    }
                }
            }
        }
    }
}