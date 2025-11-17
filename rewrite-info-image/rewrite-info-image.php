<?php
defined('ABSPATH') || exit;
add_filter('wp_handle_upload_prefilter', 'cdw_upload_filter');

function cdw_upload_filter($file)
{
    $filename = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $blogname = get_bloginfo('name');

    $file['name'] = $filename . ' ' . $blogname . '.' . $extension;
    if ($file['type'] != 'image/svg')
        re_edit_image($file);

    return $file;
}

function re_edit_image($file)
{
    try {
        // sudo yum install perl-Image-ExifTool        
        // $exiftoolVersion = shell_exec("exiftool -ver");

        // if ($exiftoolVersion === null) {
        //     //echo "Exiftool chưa được cài đặt hoặc không hoạt động.";
        //     return;
        // }

        if (!extension_loaded('imagick')) {
            // echo "Imagick chưa được cài đặt hoặc không hoạt động.";
            return;
        }
        $inputPath = $file['tmp_name'];
        $outputPath = $inputPath;

        $imagick = new \Imagick($inputPath);

        $width = $imagick->getImageWidth();
        $height = $imagick->getImageHeight();
        $id_logo = get_theme_mod('site_logo');

        if (!empty($id_logo) && $width > 500 &&  $height > 300) {
            $logo_path = get_attached_file($id_logo);

            $logo = new \Imagick($logo_path);

            $logo->scaleImage(100, 100, true);
            $blurredLogo = clone $logo;

            $x = 10;
            $y = 10;

            $position = 'bottom_left';
            switch ($position) {
                case 'top_left':
                    $x = 10;
                    $y = 10;
                    break;
                case 'top_center':
                    $x = ($width - $logo->getImageWidth()) / 2;
                    $y = 40;
                    break;
                case 'top_right':
                    $x = $width - $logo->getImageWidth() - 10;
                    $y = 40;
                    break;
                case 'middle_left':
                    $x = 80;
                    $y = ($height - $logo->getImageHeight()) / 2;
                    break;
                case 'middle_center':
                    $x = ($width - $logo->getImageWidth()) / 2;
                    $y = ($height - $logo->getImageHeight()) / 2;
                    break;
                case 'middle_right':
                    $x = $width - $logo->getImageWidth() - 10;
                    $y = ($height - $logo->getImageHeight()) / 2;
                    break;
                case 'bottom_left':
                    $x = 10;
                    $y = $height - $logo->getImageHeight() - 10;
                    break;
                case 'bottom_center':
                    $x = ($width - $logo->getImageWidth()) / 2;
                    $y = $height - $logo->getImageHeight() - 10;
                    break;
                case 'bottom_right':
                    $x = $width - $logo->getImageWidth() - 10;
                    $y = $height - $logo->getImageHeight() - 10;
                    break;
            }
            $angle = 0;
            $blurredLogo->rotateImage('rgba(255, 255, 255, 0)', $angle);
            $imagick->compositeImage($blurredLogo, \Imagick::COMPOSITE_OVER, $x, $y);
        }
        $imagick->stripImage();

        $imagick->writeImage($outputPath);
        $imagick->destroy();

        // $title = pathinfo($file['name'], PATHINFO_FILENAME);
        // $blogname = get_bloginfo('name');
        // $url = get_bloginfo('url');
        // $excerpt = get_bloginfo('description');
        // $keywords = [$url];
        // $infoArray = [
        //     'Title' => $title,
        //     'Subject' => $excerpt,
        //     //'Keywords' => implode(';', $keywords),
        //     'Comments' => implode(';', [$blogname, $title, $excerpt]),
        //     'Author' =>  $blogname,
        //     'Artist' =>  $blogname,
        //     'XPTitle' => $title,
        //     'XPAuthor' =>  $url,
        //     'XPSubject' => $excerpt,
        //     'XPKeywords' => implode(';', $keywords),
        //     'XPComment' =>   implode(';', [$blogname, $title, $excerpt]),
        //     'Software' => 'SherwinVN - Vantrungit.com',
        //     'ImageDescription' => $title,
        //     'Site' => $url,
        //     'Rating' => '5',
        //     'Copyright' => $url,
        //     'DateTimeOriginal' => '1995-05-28 12:00:00',
        //     'CreateDate' => '1995-05-28 12:00:00',
        //     'orientation' => 'portrait',
        //     'GPSLatitude' => '10.8060881',
        //     'GPSLongitude' => '106.7171174',
        //     'GPSDateStamp' => '1995-05-28',
        //     'GPSTimeStamp' => '08:00:00',
        // ];
        // writeImageInfo($inputPath, $infoArray);
    } catch (Exception $x) {
    }
}
function writeImageInfo($imagePath, $infoArray)
{
    $exiftoolPath = 'exiftool';

    $command = $exiftoolPath . ' ';
    $command .= "-all= ";
    foreach ($infoArray as $key => $value) {
        $escapedValue = $value; // Escape giá trị để tránh lỗi
        $command .= "-$key='$escapedValue' ";
    }
    $command .= "-overwrite_original ";
    $command .= escapeshellarg($imagePath);

    $locale = 'vi_VN.UTF-8';
    putenv('LC_ALL=' . $locale); // Đặt biến môi trường cho tiếng Việt
    putenv('LC_CTYPE=vi_VN.UTF-8');
    exec($command, $output, $returnCode);

    return $returnCode === 0;
}
