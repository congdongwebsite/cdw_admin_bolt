<?php
defined('ABSPATH') || exit;
class FunctionWPDB
{
    private $date;
    public function __construct()
    {
        $this->date = new DateTimeHandler();
    }
    public function save_history($id, $idParent, $title, $subtitle, $type, $date, $note)
    {
        $histories = get_post_meta($id, 'histories', true);
        if (!isset($histories) || !is_array($histories)) $histories = [];

        $histories[] = [
            "parent" => $idParent,
            "date" => $date,
            "title" => $title,
            "subtitle" => $subtitle,
            "type" => $type,
            "note" => $note
        ];
        update_post_meta($id, 'histories', $histories);
    }
    public function get_ids_in_posts($posts)
    {
        $post_ids = array_map(function ($post) {
            return $post->ID;
        }, $posts);
        return $post_ids;
    }
    public function get_total_receipt($id)
    {
        return (float) get_post_meta($id, 'total', true);
    }
    public function get_total_receipts($ids)
    {
        $amount = 0;
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $id) {
                $amount += (float) get_post_meta($id, 'total', true);
            }
        }
        return $amount;
    }
    public function get_total_payment($id)
    {
        return (float) get_post_meta($id, 'total', true);
    }
    public function get_total_payments($ids)
    {
        $amount = 0;
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $id) {
                $amount += (float) get_post_meta($id, 'total', true);
            }
        }
        return $amount;
    }
    public function get_total_billings($ids)
    {
        $amount = 0;
        foreach ($ids as $id) {
            $amount += (float) get_post_meta($id, 'amount', true);
        }
        return $amount;
    }
    public function add_post_meta_date($id, $meta_key, $meta_value)
    {
        $date = $meta_value;
        if ($this->date->isValidDateFormat($date)) {
            $date = $this->date->convertDateTime($date);
        } else {
            if ($this->date->isValidDateFormat($date, $this->date->formatDB))
                $date = $this->date->convertDateTime($date, $this->date->formatDB);
        }
        return add_post_meta($id, $meta_key, $date);
    }
    public function update_post_meta_date($id, $meta_key, $meta_value)
    {
        $date = $meta_value;
        if ($this->date->isValidDateFormat($date)) {
            $date = $this->date->convertDateTime($date);
        } else {
            if ($this->date->isValidDateFormat($date, $this->date->formatDB))
                $date = $this->date->convertDateTime($date, $this->date->formatDB);
        }
        return update_post_meta($id, $meta_key, $date);
    }
    public function func_load_detail($postType, $key, $value, $columns, $order_key = null)
    {
        $data = [];
        if (!post_type_exists($postType)) {
            return $data;
        }

        $args = array(
            'post_type' => $postType,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'and',
                array(
                    'key' =>  $key,
                    'value' => $value,
                    'compare' => '=',
                )
            )
        );
        if ($order_key != null) {
            $args['meta_key'] = $order_key;
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC';
        } else {
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
        }

        $posts = get_posts($args);

        foreach ($posts as $post) {
            $item = [];
            $item['id'] = $post;
            foreach ($columns as $column) {
                $item[$column] = get_post_meta($post, $column, true);
            }
            $data[] = $item;
        }
        return $data;
    }

    public function func_new_detail_post($postType, $key, $value, $data, $columns)
    {
        if (!post_type_exists($postType)) return $data;

        $arr = array(
            'post_type' => $postType,
            'post_status' => 'publish'
        );

        foreach ($data as $keyItem => $valueItem) {
            $id = wp_insert_post($arr);

            if ($id) {
                $data[$keyItem]["id"] = $id;
                add_post_meta($id, $key,  $value);
                foreach ($columns as $column) {
                    add_post_meta($id, $column, isset($valueItem[$column]) ? $valueItem[$column] : '');
                }
            }
        }
        return  $data;
    }

    public function func_new_detail_post_type_date($postType, $key, $value, $data, $columns)
    {
        if (!post_type_exists($postType)) return $data;

        $arr = array(
            'post_type' => $postType,
        );

        foreach ($data as $keyItem => $valueItem) {
            $id = wp_insert_post($arr);

            if ($id) {
                add_post_meta($id, $key,  $value);
                foreach ($columns as $column) {

                    $date = isset($valueItem[$column]) ? $valueItem[$column] : '';
                    if ($this->date->isValidDateFormat($date)) {
                        $date = $this->date->convertDateTime($date);
                    } else {
                        if ($this->date->isValidDateFormat($date, $this->date->formatDB)) {
                            $date = $this->date->convertDateTime($date, $this->date->formatDB);
                        }
                    }
                    if ($date)
                        add_post_meta($id, $column, $date);
                }
            }
        }
        return  $data;
    }

    public function func_update_detail_post($postType, $key, $value, $data, $columns)
    {
        if (!post_type_exists($postType)) return $data;

        $ids = array_column($data, 'id');
        $args = array(
            'post_type' => $postType,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post__not_in' => $ids,
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'and',
                array(
                    'key' =>  $key,
                    'value' => $value,
                    'compare' => '=',
                )
            )
        );

        $ids = get_posts($args);

        foreach ($data as $keyItem => $valueItem) {
            $change = isset($valueItem['change']) ? $valueItem['change'] : '';
            if ($change) {
                $id = isset($valueItem['id']) ? $valueItem['id'] : '';
                $arr = array(
                    'ID' => $id,
                    'post_type' => $postType,
                    'post_status' => 'publish'
                );
                $id = wp_update_post($arr);

                if ($id) {
                    foreach ($columns as $column) {
                        update_post_meta($id, $column, isset($valueItem[$column]) ? $valueItem[$column] : '');
                    }
                } else {
                    $id = wp_insert_post($arr);

                    if ($id) {
                        add_post_meta($id, $key,  $value);
                        foreach ($columns as $column) {
                            add_post_meta($id, $column, isset($valueItem[$column]) ? $valueItem[$column] : '');
                        }
                    }
                }
                $data[$keyItem]["id"] = $id;
            }
        }

        foreach ($ids as $id) {
            wp_delete_post($id, true);
        }
        return  $data;
    }
    public function func_update_detail_post_type_date($postType, $key, $value, $data, $columns)
    {
        if (!post_type_exists($postType)) return $data;

        foreach ($data as $keyItem => $valueItem) {
            $id = isset($valueItem['id']) ? $valueItem['id'] : '';
            $arr = array(
                'ID' => $id,
                'post_type' => $postType,
            );
            $id = wp_update_post($arr);

            if ($id) {
                foreach ($columns as $column) {
                    $date = isset($valueItem[$column]) ? $valueItem[$column] : '';
                    if ($this->date->isValidDateFormat($date)) {
                        $date = $this->date->convertDateTime($date);
                    } else {
                        if ($this->date->isValidDateFormat($date, $this->date->formatDB)) {
                            $date = $this->date->convertDateTime($date, $this->date->formatDB);
                        }
                    }
                    if ($date)
                        update_post_meta($id, $column, $date);
                }
            } else {
                $id = wp_insert_post($arr);

                if ($id) {
                    add_post_meta($id, $key,  $value);
                    foreach ($columns as $column) {
                        $date = isset($valueItem[$column]) ? $valueItem[$column] : '';
                        if ($this->date->isValidDateFormat($date)) {
                            $date = $this->date->convertDateTime($date);
                        } else {
                            if ($this->date->isValidDateFormat($date, $this->date->formatDB)) {
                                $date = $this->date->convertDateTime($date, $this->date->formatDB);
                            }
                        }
                        if ($date)
                            add_post_meta($id, $column, $date);
                    }
                }
            }
        }
        return  $data;
    }
    public function func_delete_detail_post($postType, $key, $value, $force_delete = true)
    {
        $success = 0;
        if (!post_type_exists($postType)) return $success;

        $args = array(
            'post_type' => $postType,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'and',
                array(
                    'key' =>  $key,
                    'value' => $value,
                    'compare' => '=',
                )
            )
        );
        $ids = get_posts($args);

        foreach ($ids as $id) {
            wp_delete_post($id, $force_delete);
            $success++;
        }
        return  $success;
    }
    public function func_exist_post($postType, $key, $value)
    {
        if (!post_type_exists($postType)) return false;

        $args = array(
            'post_type' => $postType,
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'and',
                array(
                    'key' =>  $key,
                    'value' => $value,
                    'compare' => '=',
                )
            )
        );
        $ids = get_posts($args);
        return  count($ids) != 0;
    }
    public function get_price_domain($domain)
    {
        global $CDWFunc;
        $price = -1;
        $domain = $CDWFunc->get_domain_paths($domain);
        $arr = array(
            'post_type' => 'domain',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1
        );

        $ids = get_posts($arr);
        foreach ($ids as $id) {
            if (str_ends_with($domain->full, get_the_title($id))) {
                $price = (float) get_post_meta($id, "gia_han", true);
                break;
            }
        }
        if ($price == -1) return false;
        return $price;
    }
    public function get_id_domain($domain)
    {
        global $CDWFunc;
        $result = false;
        $domain = $CDWFunc->get_domain_paths($domain);
        $arr = array(
            'post_type' => 'domain',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1
        );

        $ids = get_posts($arr);
        foreach ($ids as $id) {
            if (str_ends_with($domain->full, get_the_title($id))) {
                $result =  $id;
                break;
            }
        }
        return $result;
    }

    public function get_info_user($user_id)
    {
        global $CDWFunc;
        $result = new stdClass();
        $user = get_user_by('id', $user_id);
        if ($user == false) {
            return $result;
        }
        $result->customer_id = get_user_meta($user_id, 'customer-id', true);
        $result->name = get_post_meta($result->customer_id, 'name', true);
        $result->company = get_post_meta($result->customer_id, 'company_name', true);
        $result->phone = get_post_meta($result->customer_id, 'phone', true);
        $result->email = get_post_meta($result->customer_id, 'email', true);
        $result->idtp = get_post_meta($result->customer_id, 'dvhc_tp', true);
        $result->idqh = get_post_meta($result->customer_id, 'dvhc_qh', true);
        $result->idpx = get_post_meta($result->customer_id, 'dvhc_px', true);
        $result->idtp_label = get_post_meta($result->customer_id, 'dvhc_tp_label', true);
        $result->idqh_label = get_post_meta($result->customer_id, 'dvhc_qh_label', true);
        $result->idpx_label = get_post_meta($result->customer_id, 'dvhc_px_label', true);
        $result->straddress = get_post_meta($result->customer_id, 'address', true);
        $result->cmnd = get_post_meta($result->customer_id, 'cmnd', true);

        $result->id = $user_id;
        $result->username = $user->user_login;
        $result->useremail = $user->user_email;
        $result->birthdate = get_post_meta($result->customer_id, 'birthdate', true);
        $result->gender = get_post_meta($result->customer_id, 'gender', true);
        $result->website = get_user_meta($user_id, 'website', true);
        $result->avatar = get_user_meta($user_id, 'avatar-custom', true);
        if (empty($result->avatar))
            $result->avatar = ADMIN_CHILD_THEME_URL_F . '/assets/images/user.png';
        else
            $result->avatar = wp_get_attachment_image_url($result->avatar, 'large');

        $result->address = [$result->straddress, $result->idpx_label, $result->idtp_label];
        $result->address = implode(', ', $result->address);
        if (empty($result->company)) $result->company = $result->name;
        $result->roles = $user->roles;
        $result->role = '';
        foreach ($user->roles as $role) {
            $result->role = $role;
            break;
        }

        return $result;
    }
}
