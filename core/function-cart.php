<?php
defined('ABSPATH') || exit;
class FunctionCart
{
    private $key = 'carts';
    private $items = [];
    public function __construct() {}

    public function newItem($type, $id, $service, $description, $price, $quantity, $amount)
    {
        return [
            "id" => $id,
            "type" => $type,
            "service" => $service,
            "description" => $description,
            "price" => $price,
            "quantity" => $quantity,
            "amount" => $amount,
        ];
    }
    public function get()
    {
        $userC = wp_get_current_user();
        delete_user_meta($userC->ID, 'items');
        $this->items =  get_user_meta($userC->ID, $this->key, true);
        if (!is_array($this->items)) $this->items = [];
        return  $this->items;
    }
    public function set()
    {
        $userC = wp_get_current_user();
        update_user_meta($userC->ID, $this->key, $this->items);
        $this->calTotal();
        $this->setStatus(true);
        return  $this->items;
    }
    public function getItemById($id)
    {
        $result = [];
        $this->get();
        foreach ($this->items as $key => $item) {
            if ($this->items[$key]['idc'] == $id) {
                $result[$key] = $item;
            }
        }

        return $result;
    }
    public function getItemByType($type, $id)
    {
        $result = [];

        $this->get();
        foreach ($this->items as $key => $item) {
            if ($this->items[$key]['type'] == $type && $this->items[$key]['id'] == $id) {
                $result[$key] = $item;
            }
        }

        return $result;
    }
    public function setTax($has = false, $company = "", $code = "", $email = "")
    {
        update_user_meta(get_current_user_id(), $this->key . '_tax', [
            "has" => $has,
            "company" => $company,
            "code" => $code,
            "email" => $email,
        ]);
        return (object) [
            "has" => $has,
            "company" => $company,
            "code" => $code,
            "email" => $email,
        ];
    }
    public function getTax()
    {
        $tax =  get_user_meta(get_current_user_id(), $this->key . '_tax', true);

        return (object) [
            "has" => $tax['has'] ?? false,
            "company" => $tax['company'] ?? "",
            "code" => $tax['code'] ?? "",
            "email" => $tax['email'] ?? "",
        ];
    }
    public function setNote($note = "")
    {
        update_user_meta(get_current_user_id(), $this->key . '_note',  $note);
        return $note;
    }
    public function getNote()
    {
        $note =  get_user_meta(get_current_user_id(), $this->key . '_note', true);
        return $note;
    }
    public function add($item)
    {
        $idc = $this->getNewKey();
        $item['idc'] = $idc;
        $this->items[$idc] = $item;
    }
    public function addByExsitsId($items)
    {
        $this->addByExsitsField($items, 'id');
    }

    public function checkItemExsitsField($item, $field, $type = '')
    {
        foreach ($this->items as $itemc) {
            if (
                isset($itemc[$field]) &&
                ($item[$field] == $itemc[$field] &&
                    (empty($type) || $itemc["type"] == $type)
                )
            ) {
                return $itemc;
            }
        }
        return false;
    }
    public function addByExsitsField($items, $field, $type = '')
    {
        global $CDWFunc;

        $this->get();
        foreach ($items as $item) {
            if(empty($type)) $type = $item['type'];
            $items_exists = $this->checkItemExsitsField($item, $field, $type);
            if ($items_exists) {
                $price = (float) $item["price"];
                $quantity = (float) $items_exists["quantity"];
                $quantity += (float) $item['quantity'];

                $this->items[$items_exists['idc']]["quantity"] =  $quantity;
                $this->items[$items_exists['idc']]["amount"] =  $CDWFunc->number->round($quantity * $price);
            } else {
                $this->add($item);
            }
        }
        return  $this->set();
    }
    public function deleteItemById($id)
    {
        $this->get();
        foreach ($this->items as $key => $item) {
            if ($this->items[$key]['idc'] == $id) {
                unset($this->items[$key]);
            }
        }
        $this->set();
    }
    public function deleteItemByType($type, $id)
    {
        $this->get();
        foreach ($this->items as $key => $item) {
            if ($this->items[$key]['type'] == $type && $this->items[$key]['id'] == $id) {
                unset($this->items[$key]);
            }
        }
    }

    public function deleteNotExists($ids)
    {
        if (!is_array($ids) || count($ids) == 0) return;
        $this->get();
        foreach ($this->items as $key => $item) {
            if (!array_key_exists($key, $ids)) {
                unset($this->items[$key]);
            }
        }
        $this->set();
    }
    public function getNewKey()
    {
        return uniqid(bin2hex(random_bytes(2)));
    }

    public function update($items)
    {
        if (!is_array($items)) $items = [$items];

        $this->get();
        $fields = array_column($this->items, 'service', 'idc');
        foreach ($items as $item) {
            if (isset($item['idc']) && array_key_exists($item['idc'], $fields)) {
                foreach ($fields as $key => $field) {
                    $this->items[$key] =  $item;
                    break;
                }
            }
        }
        return  $this->set();
    }
    public function changePrice($items)
    {
        global $CDWFunc;
        if (!is_array($items)) return false;

        $this->get();

        foreach ($items as $key => $value) {
            $quantity = (float) $this->items[$key]["quantity"];
            $price = (float) $value;
            $this->items[$key]["price"] =  $price;
            $this->items[$key]["amount"] =  $CDWFunc->number->round($quantity * $price);
        }

        return  $this->set();
    }

    public function changeQuantity($items)
    {
        global $CDWFunc;
        if (!is_array($items)) return false;

        $this->get();

        foreach ($items as $key => $value) {
            $price = (float) $this->items[$key]["price"];
            $quantity = (float) $value;
            $this->items[$key]["quantity"] =  $quantity;
            $this->items[$key]["amount"] =  $CDWFunc->number->round($quantity * $price);
        }

        return  $this->set();
    }

    public function calTotal()
    {
        $quantity = 0;
        $amount = 0;
        $this->get();

        foreach ($this->items as $item) {
            $amount  +=  (float) $item["amount"];
            $quantity  +=  (float) $item["quantity"];
        }

        $userC = wp_get_current_user();
        update_user_meta($userC->ID, "total-quantity", $quantity);
        update_user_meta($userC->ID, "total-amount", $amount);

        return (object) ["quantity" => $quantity, "amount" => $amount];
    }
    public function getTotal()
    {
        $userC = wp_get_current_user();
        $quantity = (float) get_user_meta($userC->ID, "total-quantity", true);
        $amount = (float) get_user_meta($userC->ID, "total-amount", true);

        return (object) ["quantity" => $quantity, "amount" => $amount];
    }
    public function clear()
    {
        $userC = wp_get_current_user();
        delete_user_meta($userC->ID, 'items');
        delete_user_meta($userC->ID, $this->key);
        $this->items = [];
        $this->calTotal();
        $this->setStatus(true);
    }
    public function delete($id)
    {
        $this->deleteItemById($id);
    }
    public function getStatus()
    {
        $userC = wp_get_current_user();
        return get_user_meta($userC->ID, "cart-status", true);
    }
    public function setStatus($status, $user_id = "")
    {
        if (empty($user_id)) {
            $userC = wp_get_current_user();
            $user_id = $userC->ID;
        }
        return update_user_meta($user_id, "cart-status", $status);
    }

    public function get_validated_item_properties($item)
    {
        global $CDWFunc;
        $price = 0;
        $quantity = $item['quantity']; // Default to existing quantity

        switch ($item["type"]) {
            case 'customer-email-change':
                $customer_email_id = $item['id'];
                $new_plan_wp_id = $item['new_plan_id'];
                $old_plan_wp_id = get_post_meta($customer_email_id, 'email-type', true);

                $old_price = (float) get_post_meta($old_plan_wp_id, 'gia_han', true);
                $new_price = (float) get_post_meta($new_plan_wp_id, 'gia_han', true);

                $expiry_date_str = get_post_meta($customer_email_id, 'expiry_date', true);
                $expiry_date = new DateTime($expiry_date_str);
                $current_date = new DateTime(current_time('mysql'));

                $quantity = 0;
                $price = 0;

                if ($expiry_date > $current_date) {
                    $interval = $current_date->diff($expiry_date);
                    $remaining_days = $interval->days;

                    if ($remaining_days > 0) {
                        $daily_price_diff = ($new_price - $old_price) / 30;
                        $daily_price_diff = max(0, $daily_price_diff);
                        $price = round($daily_price_diff);
                        $quantity = $remaining_days;
                    }
                }
                break;
            case 'customer-domain':
                $domain = $item["domain"];
                $type = get_post_meta($item["id"], 'domain-type', true);
                $price = (float) get_post_meta($type, 'gia_han', true);
                if ($price === false || $price == -1) {
                    $price = (float)  $CDWFunc->wpdb->get_price_domain($domain);
                }
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Domain không hợp lệ.']);
                }
                break;
            case 'customer-hosting':
                $id = $item["id"];
                $type = get_post_meta($id, 'type', true);
                $price = (float) get_post_meta($type, 'gia_han', true);

                if ($price == -1) {
                    $price = (float) get_post_meta($id, 'price', true);
                }
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Gói hosting không hợp lệ.']);
                }
                break;

            case 'customer-email':
                $id = $item["id"];
                $type = get_post_meta($id, 'email-type', true);
                $price = (float) get_post_meta($type, 'gia_han', true);

                if ($price == -1) {
                    $price = (float) get_post_meta($id, 'price', true);
                }

                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Gói email không hợp lệ.']);
                }
                break;

            case 'customer-plugin':
                $id = $item["id"];
                $type = get_post_meta($id, 'plugin-type', true);
                $price = (float) get_post_meta($type, 'price', true);
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Gói plugin không hợp lệ.']);
                }
                break;
            case 'manage-hosting':
                $id = $item["id"];
                $price = (float) get_post_meta($id, 'gia', true);
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Gói hosting không hợp lệ.']);
                }
                break;
            case 'manage-email':
                $id = $item["id"];
                $price = (float) get_post_meta($id, 'gia', true);
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Gói email không hợp lệ.']);
                }
                break;

            case 'manage-domain':
                $id = $item["id"];
                $price = (float) get_post_meta($id, 'gia', true);
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Domain không hợp lệ.']);
                }
                break;
            case 'site-managers':
                $id = $item["id"];
                $price = (float) get_post_meta($id, 'price', true);
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Giao diện không hợp lệ.']);
                }
                break;

            case 'manage-plugin':
                $id = $item["id"];
                $price = (float) get_post_meta($id, 'price', true);
                if ($price === false || $price == -1) {
                    wp_send_json_error(['msg' => 'Plugin không hợp lệ.']);
                }
                break;
        }

        if (!is_numeric($price)) {
            wp_send_json_error(['msg' => 'Giá sản phẩm không hợp lệ cho ' . $item['service']]);
        }

        return ['price' => $price, 'quantity' => $quantity];
    }
}
