<?php

require_once('iSHOPEEClass.php');
/**
 * Check Domain Availability.
 *
 * Determine if a domain or group of domains are available for
 * registration or transfer.
 */
function getComment($data)
{
    // Require param
    require('checkValid.php');

    try {
        $limit = 6;
        $limitf = $data['limit'];
        if ($limit < $limitf) {
            $data['limit'] = $limit;
        }
        $b  = $iSHOPEEClass->getComment($data);

        $result = new stdClass();
        if ($b->error != 0) {
            $result->error = $result->error_msg;
            return  $result;
        }
        $result->item_rating_summary = $b->data->item_rating_summary;

        $rating = new stdClass();
        $result->ratings = array();

        foreach ($b->data->ratings as $key => $value) {
            $rating = new stdClass();
            $rating->cmtid = $value->cmtid;
            $rating->userid = $value->userid;
            $rating->author_username = $value->author_username;
            $rating->mtime = $value->mtime;
            $rating->rating = $value->rating;
            $rating->like_count = $value->like_count;
            $rating->comment = $value->comment;
            $rating->images = $value->images;
            $rating->videos = $value->videos;
            $rating->author_portrait = $value->author_portrait;
            $rating->author_shopid = $value->author_shopid;
            array_push($result->ratings, $rating);
        }

        for ($i = $limit; $i < $limitf; $i = $i + $limit) {
            $data['offset'] = $i;
            $data['limit'] = $limit;
            $a = $iSHOPEEClass->getComment($data);

            // echo '<pre>';
            // echo "i->";
            // var_dump($i);
            // echo "l->";
            // var_dump($limit);
            // echo "g->";
            // var_dump($limitf);
            // echo '</pre>';
            if ($a->error == 0) {
                foreach ($a->data->ratings as $key => $value) {
                    $rating = new stdClass();
                    $rating->cmtid = $value->cmtid;
                    $rating->userid = $value->userid;
                    $rating->author_username = $value->author_username;
                    $rating->mtime = $value->mtime;
                    $rating->rating = $value->rating;
                    $rating->like_count = $value->like_count;
                    $rating->comment = $value->comment;
                    $rating->images = $value->images;
                    $rating->videos = $value->videos;
                    $rating->author_portrait = $value->author_portrait;
                    $rating->author_shopid = $value->author_shopid;
                    array_push($result->ratings, $rating);
                }
                // echo '<pre>';
                // echo "f->";
                // var_dump(count($result->ratings));
                // echo "a->";
                // var_dump($a->data->ratings);
                // echo '</pre>';
            } else {

                // echo '<pre>';
                // var_dump("Loi");
                // var_dump($i);
                // echo '</pre>';
            }
            if (($i + $limit) > $limitf) $i = $limitf;
        }
        // echo '<pre>';
        // var_dump($result->ratings);
        // echo '</pre>';
        return  $result;
    } catch (\Exception $e) {
        $result->error = $e->getMessage();
        return  $result;
    }
}

function getProduct($data)
{
    // Require param
    require('checkValid.php');

    try {
        $limit = 30;
        $limitf = $data['limit'];
        if ($limit < $limitf) {
            $data['limit'] = $limit;
        }
        $b  = $iSHOPEEClass->getProduct($data);

        // echo '<pre>';
        // var_dump($b);
        // echo '</pre>';
        $result = new stdClass();
        if ($b->error != 0) {
            $result->error = $result->error_msg;
            return  $result;
        }
        $result->total_count = $b->total_count;

        $item = new stdClass();
        $result->items = array();

        if ($b->total_count <> 0)
            foreach ($b->items as $key => $value) {
                $item = new stdClass();
                $item->itemid = $value->itemid;
                $item->shopid = $value->shopid;
                $item->name = $value->item_basic->name;
                $item->image = $value->item_basic->image;
                $item->images = $value->item_basic->images;
                $item->currency = $value->item_basic->currency;
                $item->catid = $value->item_basic->catid;
                $item->price = $value->item_basic->price;
                $item->price_min = $value->item_basic->price_min;
                $item->price_max = $value->item_basic->price_max;
                array_push($result->items, $item);
            }

        for ($i = $limit; $i < $limitf; $i = $i + $limit) {
            $data['offset'] = $i;
            $data['limit'] = $limit;
            $a = $iSHOPEEClass->getProduct($data);

            // echo '<pre>';
            // echo "i->";
            // var_dump($i);
            // echo "l->";
            // var_dump($limit);
            // echo "g->";
            // var_dump($limitf);
            // echo '</pre>';
            if ($a->total_count <> 0) {
                foreach ($a->items as $key => $value) {
                    $item = new stdClass();
                    $item->itemid = $value->itemid;
                    $item->shopid = $value->shopid;
                    $item->name = $value->item_basic->name;
                    $item->image = $value->item_basic->image;
                    $item->images = $value->item_basic->images;
                    $item->currency = $value->item_basic->currency;
                    $item->catid = $value->item_basic->catid;
                    $item->price = $value->item_basic->price;
                    $item->price_min = $value->item_basic->price_min;
                    $item->price_max = $value->item_basic->price_max;
                    array_push($result->items, $item);
                }
                // echo '<pre>';
                // echo "f->";
                // var_dump(count($result->ratings));
                // echo "a->";
                // var_dump($a->data->ratings);
                // echo '</pre>';
            } else {

                // echo '<pre>';
                // var_dump("Loi");
                // var_dump($i);
                // echo '</pre>';
            }
            if (($i + $limit) > $limitf) $i = $limitf;
        }
        // echo '<pre>';
        // var_dump($result->ratings);
        // echo '</pre>';
        return  $result;
    } catch (\Exception $e) {
        $result->error = $e->getMessage();
        return  $result;
    }
}
function UR_exists($url)
{
    $headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;
}

// function createProduct($data)
// {
//     $objProduct = new WC_Product();
//     $objProduct->set_name($data['name']);
//     $objProduct->set_status("publish");  // can be publish,draft or any wordpress post status
//     $objProduct->set_catalog_visibility('visible'); // add the product visibility status
//     $objProduct->set_description($data['description']);
//     $objProduct->set_sku($data['sku']); //can be blank in case you don't have sku, but You can't add duplicate sku's
//     $objProduct->set_price($data['price']); // set product price
//     $objProduct->set_regular_price($data['regularPrice']); // set product regular price
//     $objProduct->set_manage_stock(true); // true or false
//     $objProduct->set_stock_quantity(10);
//     $objProduct->set_stock_status('instock'); // in stock or out of stock value
//     $objProduct->set_backorders('no');
//     $objProduct->set_reviews_allowed(true);
//     $objProduct->set_sold_individually(false);
//     $objProduct->set_category_ids($data['category_ids']); // array of category ids, You can get category id from WooCommerce Product Category Section of Wordpress Admin

//     // above function uploadMedia, I have written which takes an image url as an argument and upload image to wordpress and returns the media id, later we will use this id to assign the image to product.
//     $productImagesIDs = array(); // define an array to store the media ids.
//     $images = $data['images']; // images url array of product
//     foreach ($images as $image) {
//         $mediaID = uploadMedia($image); // calling the uploadMedia function and passing image url to get the uploaded media id
//         if ($mediaID) $productImagesIDs[] = $mediaID; // storing media ids in a array.
//     }
//     if ($productImagesIDs) {
//         $objProduct->set_image_id($productImagesIDs[0]); // set the first image as primary image of the product

//         //in case we have more than 1 image, then add them to product gallery. 
//         if (count($productImagesIDs) > 1) {
//             $objProduct->set_gallery_image_ids($productImagesIDs);
//         }
//     }
//     $product_id = $objProduct->save();
// }

function uploadMedia($image_url)
{
    require_once('wp-admin/includes/image.php');
    require_once('wp-admin/includes/file.php');
    require_once('wp-admin/includes/media.php');
    $media = media_sideload_image($image_url, 0);
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_title' => 'test',
        'post_status' => 'inherit',
        'post_parent' => 0,
        'orderby' => 'post_date',
        'order' => 'DESC'
    ));
    var_dump($media);
    var_dump($attachments[0]);
    return $attachments[0]->ID;
}

function getImageByUrl($imageurl,$filenamewithoutextension)
{
    include_once('wp-admin/includes/image.php');
    $imagesize =explode('/', getimagesize($imageurl)['mime']);

    $imagetype = end($imagesize);
    $filename = $filenamewithoutextension . '.' . $imagetype;

    $uploaddir = wp_upload_dir();
    $uploadfile = $uploaddir['path'] . '/' . $filename;
    $contents = file_get_contents($imageurl);
    $savefile = fopen($uploadfile, 'w');
    fwrite($savefile, $contents);
    fclose($savefile);

    $wp_filetype = wp_check_filetype(basename($filename), null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_text_field($filenamewithoutextension),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $uploadfile);
    $imagenew = get_post($attach_id);
    $fullsizepath = get_attached_file($imagenew->ID);
    $attach_data = wp_generate_attachment_metadata($attach_id, $fullsizepath);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}
