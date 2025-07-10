<?php
defined('ABSPATH') || exit;
class AjaxClientTheme
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-client-theme',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_search-theme-client-cart',  array($this, 'func_search'));
        add_action('wp_ajax_ajax_search-per-theme-client-cart',  array($this, 'func_search_per'));
        add_action('wp_ajax_ajax_info-theme',  array($this, 'func_info_theme'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-theme-nonce', 'security');


        $userC = wp_get_current_user();
        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key'     => 'user-id',
                    'value'   => $userC->ID,
                    'compare' => '=',
                )
            )
        );
        $id_customers = get_posts($arr);

        $arr = array(
            'post_type' => 'customer-theme',
            'post_status' => 'publish',
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if (count($id_customers) == 0) $id_customers = -1;
        if (!$CDWFunc->isAdministrator($userC->ID))
            $arr['meta_query'][] =
                array(
                    'key' => "customer-id",
                    'value' => $id_customers,
                    'compare' => 'in',
                );

        if ($search != '') {
            $fieldSearch = ['name', 'sub_domain'];
            $arr['meta_query']['relation'] = 'OR';

            foreach ($fieldSearch as $field) {
                $arr['meta_query'][] =
                    array(
                        'key' => $field,
                        'value' => $search['value'],
                        'compare' => 'like',
                    );
            }
        }

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        if (!empty($type)) {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
                'type'    => 'type'
            );
        }

        $posts = get_posts($arr);
        $data = [];
        foreach ($posts as $post) {
            $site_type = get_post_meta($post->ID, 'site-type', true);
            $urlredirect = $CDWFunc->getUrl('detail', 'manage-site', 'id=' . $site_type);
            $item = [];
            $item['id'] = $post->ID;
            $title = get_the_title($site_type);
            $info = get_post_meta($post->ID, 'name', true);
            $price = get_post_meta($post->ID, 'price', true);
            $date = get_post_meta($post->ID, 'date', true);

            $thumbnail_id = get_post_thumbnail_id($site_type);
            $image =  wp_get_attachment_url($thumbnail_id);


            $item['urlredirect'] = $urlredirect;
            $item['image'] = !$image ? '' : $image;
            $item['title'] = !$title ? '' : $title;
            $item['info'] = $info;
            $item['date'] = $date;
            $item['price'] = $price;

            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_search()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-theme-nonce', 'security');

        $fieldSearch = ['name', 'sub_domain'];

        $arr = array(
            'post_type' => 'site-managers',
            'post_status' => 'publish',
            'meta_key' => 'name',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => -1,
        );
        $search = isset($_POST['search']) ? $_POST['search'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";

        if (is_array($fieldSearch) && !empty($search)) {
            $arrSeach = [];
            $arrSeach['relation'] = 'or';

            $arr['meta_query']['relation'] = 'OR';

            foreach ($fieldSearch as $field) {
                $arrSeach[] =
                    array(
                        'key' => $field,
                        'value' => $search,
                        'compare' => 'like',
                    );
            }
            $arr['meta_query'][] = $arrSeach;
        }

        if (!empty($type) && $type != -1) {
            $arr['tax_query'] = array(
                array(
                    'taxonomy' => 'site-types',
                    'field'    => 'term_id',
                    'terms'    =>  $type,
                ),
            );
        }
        $wp = new WP_Query($arr);
        $posts = $wp->posts;
        $data = [];
        $item_default = [
            "id" => -1,
            "name" => "Tên web site",
            "image" => ADMIN_CHILD_THEME_URL_F . '/assets/images/user.png',
            "price" => "0",
            "url" => "",
            "url_demo" => "",
            "id_type_list" => "",
            "template" => "theme-item-template",
        ];
        foreach ($posts as $post) {

            $item = (object) array_merge([], $item_default);
            $item->id = $post->ID;
            $arr = array(
                'fields' => 'ids',
                'number' => 0
            );
            $idSiteTypes = wp_get_post_terms($post->ID, 'site-types', $arr);
            $item->id_type_list = implode(",", $idSiteTypes);
            $data[] = $item;
        }

        wp_send_json_success(["items" => $data, "recordsTotal" => $wp->found_posts, "recordsFiltered" => $wp->found_posts]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_search_per()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-theme-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $item = (object)[
            "id" => -1,
            "name" => "Tên web site",
            "image" => ADMIN_CHILD_THEME_URL_F . '/assets/images/user.png',
            "price" => "0",
            "url" => "",
            "url_demo" => "",
            "id_type_list" => "",
            "template" => "theme-item-template",
        ];

        $thumbnail_id = get_post_thumbnail_id($id);
        if (!empty($thumbnail_id)) {
            $image = wp_get_attachment_url($thumbnail_id);
        }
        $item->name = get_post_meta($id, 'name', true);
        $item->image =  $image;
        $item->price = number_format(get_post_meta($id, 'price', true), 0, ',', '.');
        $item->url_demo = get_post_meta($id, 'sub_domain', true);
        $item->id = $id;
        $arr = array(
            'fields' => 'ids',
            'number' => 0
        );
        $idSiteTypes = wp_get_post_terms($id, 'site-types', $arr);
        $item->id_type_list = implode(",", $idSiteTypes);
        wp_send_json_success(["item" => $item]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_info_theme()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-theme-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";


        $info = (object)[
            "id" => -1,
            "name" => "Tên web site",
            "image" => ADMIN_CHILD_THEME_URL_F . '/assets/images/user.png',
            "price" => "0",
            "url_demo" => "",
        ];

        $thumbnail_id = get_post_thumbnail_id($id);
        if (!empty($thumbnail_id)) {
            $image = wp_get_attachment_url($thumbnail_id);
        }
        $info->name = get_post_meta($id, 'name', true);
        $info->image =  $image;
        $info->price = number_format(get_post_meta($id, 'price', true), 0, ',', '.');
        $info->url_demo = get_post_meta($id, 'sub_domain', true);
        $info->id = $id;

        wp_send_json_success(["info" => $info]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxClientTheme();
