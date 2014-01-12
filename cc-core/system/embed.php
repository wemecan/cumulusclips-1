<?php

try {
    // Verify video is available
    if (empty($_GET['vid']) || !is_numeric ($_GET['vid']) || $_GET['vid'] < 1) throw new Exception();

    // Validate given video
    $videoMapper = new VideoMapper();
    $video = $videoMapper->getVideoByCustom(array(
        'video_id' => $_GET['vid'],
        'status' => 'approved',
        'gated' => '0',
        'private' => '0'
    ));
    if (!$video) throw new Exception();
} catch (Exception $e) {
    $video = null;
}

// Output player
include ($config->theme_path . '/embed.tpl');