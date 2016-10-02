<?php

echo "start !";

$path = 'img/projets';

$projects = scandir($path);

$markdown = array();

foreach ($projects as $project) {

    $projectPath = $path . '/' . $project;

    if (isValidProjectFolder($project, $projectPath)) {

        deleteThumbs($projectPath);

    	$images = scandir($projectPath);

        array_push($markdown, $project . "\n\n");
        array_push($markdown, "images:\n");

    	foreach ($images as $image) {

            $imagePath = $projectPath. '/' . $image;

    		if (isValidProjectImage($imagePath, $image)) {

                list($width, $height) = getimagesize($imagePath);

                createThumb($projectPath, $image, $height);

                array_push($markdown, " - [\"" . $image . "\", \"" . $width . "\", \"" . $height . "\"]\n");
        	}

    	}
    }

    array_push($markdown, "\n\n");
};

$markdownFile = fopen("_posts/projets/images.markdown", "w");

if ($markdownFile) {
    foreach ($markdown as $line) {
        fwrite($markdownFile, $line);
    }

    fclose($markdownFile);
}

echo "done !";


function isValidProjectFolder($project, $projectPath)
{
    if ($project === '.' or $project === '..') {
        return false;
    }

    if (is_dir($projectPath)) {
        return true;
    }

    return false;
}

function isValidProjectImage($imagePath, $image)
{
    if ($image === '.' or $image === '..') {
        return false;
    }

    if (@is_array(getimagesize($imagePath))) {
        return true;
    }

    return false;
}

function deleteThumbs($projectPath) {

    $path = $projectPath . '/thumbs';

    if(is_dir($path)){
        $thumbs = scandir($path);

        foreach( $thumbs as $thumb )
        {
            if ($thumb === '.' or $thumb === '..') continue;
            unlink($path . '/' . $thumb);
        }

        rmdir($path);
    }
}

function createThumb($projectPath, $image, $height)
{
    if (!is_dir($projectPath . '/thumbs'))
    {
        mkdir($projectPath . '/thumbs');
    }

    copy(
        $projectPath . '/' . $image,
        $projectPath . '/thumbs/' . $image
    );

    $imagick = new \Imagick(realpath($projectPath . '/thumbs/' . $image));

    $imagick->resizeImage(500, intval(500/$height), Imagick::FILTER_LANCZOS, 1);
    $imagick->writeImage();
    $imagick->clear();
    $imagick->destroy();

}
