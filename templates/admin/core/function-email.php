<?php
defined('ABSPATH') || exit;
class FunctionEmail
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;
    private $secure;

    public function __construct($host, $port, $username, $password, $fromEmail = "", $fromName = "", $secure = "TLS")
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->secure = $secure;
    }


    public function sendHtmlEmail($toEmail, $subject, $htmlBody, $toName = "wordpress")
    {
        return $this->sendEmail($toEmail, $toName, $subject, $htmlBody, 'text/html');
    }

    public function sendPlainEmail($toEmail, $subject, $htmlBody, $toName = "wordpress")
    {
        return $this->sendEmail($toEmail, $toName, $subject, $htmlBody, 'text/html');
    }
    private function sendEmail($toEmail, $toName, $subject, $message, $content_type = 'text/plain')
    {
        try {
            if (empty($toEmail)) return;
            $smtpHost = $this->host;
            $smtpUsername = $this->username;
            $smtpPassword = $this->password;
            $smtpSecure = $this->secure;

            include_once (ABSPATH . WPINC . '/PHPMailer/Exception.php');
            include_once (ABSPATH . WPINC . '/PHPMailer/PHPMailer.php');
            include_once (ABSPATH . WPINC . '/PHPMailer/SMTP.php');

            // Tạo một đối tượng PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail::$validator = static function ($email) {
                return (bool) is_email($email);
            };
            $mail->isSMTP();
            $mail->SMTPSecure = $smtpSecure;
            $mail->SMTPAuth = true;
            $mail->Host = $smtpHost;
            $mail->Port = $this->port;
            $mail->Username = $smtpUsername;
            $mail->Password = $smtpPassword;
            // $mail->SMTPOptions = array(
            //     'ssl' => array(
            //         'verify_peer' => false,
            //         'verify_peer_name' => false,
            //         'allow_self_signed' => true
            //     )
            // );


            // Empty out the values that may be set.
            $mail->clearAllRecipients();
            $mail->clearAttachments();
            $mail->clearCustomHeaders();
            $mail->clearReplyTos();
            $mail->Body    = '';
            $mail->AltBody = '';


            if (empty($this->fromName)) {
                $this->fromName = 'Congdongweb';
            }

            if (empty($this->fromEmail)) {
                // Get the site domain and get rid of www.
                $sitename   = wp_parse_url(network_home_url(), PHP_URL_HOST);
                $this->fromEmail = 'admin@';

                if (null !== $sitename) {
                    if ('www.' === substr($sitename, 0, 4)) {
                        $sitename = substr($sitename, 4);
                    }

                    $this->fromEmail .= $sitename;
                }
            }
            $mail->setFrom($this->fromEmail, $this->fromName);

            $mail->addAddress($toEmail, $toName);


            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = strip_tags($message);

            if (!isset($content_type)) {
                $content_type = 'text/plain';
            }
            $mail->ContentType = $content_type . '; charset=utf-8';
            //$mail->SMTPDebug = true;
            if ('text/html' === $content_type) {
                $mail->isHTML(true);
            }
            if (!isset($charset)) {
                $charset = get_bloginfo('charset');
            }

            $mail->CharSet = $charset;
            // var_dump($toEmail);
            //$mail->SMTPDebug = 1;
            // var_dump($toName);
            // var_dump($subject);
            // var_dump($message);
            // var_dump($content_type);
            //var_dump($mail);

            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            // echo $e->getMessage(); 
            return false; //Boring error messages from anything else!
        }
    }
    public function sendEmailTicketNew($ticket_id, $sendAdmin = false)
    {
        global $CDWFunc;
        $user_id = get_post_meta($ticket_id, 'user-id', true);
        $user_data = $CDWFunc->wpdb->get_info_user($user_id);
        $subject = "Hỗ Trợ #" . $ticket_id . ' Đã Được Khởi Tạo - Congdongweb.com';
        $subjectAdmin = $user_data->name . " yêu cầu hỗ Trợ #" . $ticket_id . ' - Congdongweb.com';
        $arg['ticket-id'] = $ticket_id;
        $arg['title'] = "HỖ TRỢ KHÁCH HÀNG QUA TICKET";
        $htmlBody =  $this->get_templete_email('ticket-new', $arg);
        if ($sendAdmin) {
            $this->sendHtmlEmail(EMAIL_TICKET, $subjectAdmin, $htmlBody, 'Cộng đồng Web');
            $this->sendHtmlEmail(EMAIL_SUPPORT2, $subjectAdmin, $htmlBody, 'Cộng đồng Web');
        }
        return $this->sendHtmlEmail($user_data->email, $subject, $htmlBody, $user_data->name);
    }

    public function sendEmailTicketUpdateStatus($ticket_id)
    {
        global $CDWFunc;
        $user_id = get_post_meta($ticket_id, 'user-id', true);
        $user_data = $CDWFunc->wpdb->get_info_user($user_id);
        $subject = "Cập Nhật Trạng Thái Hỗ Trợ #" . $ticket_id . " - Cộng Đồng Web";
        $arg['ticket-id'] = $ticket_id;
        $arg['title'] = "TRẠNG THÁI HỖ TRỢ CỦA KHÁCH HÀNG";
        $htmlBody =  $this->get_templete_email('ticket-update-status', $arg);
        return $this->sendHtmlEmail($user_data->email, $subject, $htmlBody, $user_data->name);
    }

    public function sendEmailTicketDetailNew($detail_id, $sendAdmin = false)
    {
        global $CDWFunc;
        $ticket_id = get_post_meta($detail_id, 'ticket-id', true);
        $user_id = get_post_meta($ticket_id, 'user-id', true);
        $user_data = $CDWFunc->wpdb->get_info_user($user_id);
        $id = get_post_meta($detail_id, 'ticket-id', true);
        $subject = "Phản Hồi Hỗ Trợ  #" . $id . " - Cộng Đồng Web";
        $subjectAdmin = $user_data->name . " phản Hồi Hỗ Trợ  #" . $id . " - Cộng Đồng Web";
        $arg['detail-id'] = $detail_id;
        $arg['title'] = "PHẢN HỒI HỖ TRỢ KHÁCH HÀNG QUA TICKET";
        $htmlBody =  $this->get_templete_email('ticket-new-detail', $arg);
        if ($sendAdmin) {
            $this->sendHtmlEmail(EMAIL_TICKET, $subjectAdmin, $htmlBody, 'Cộng Đồng Web');
            $this->sendHtmlEmail(EMAIL_SUPPORT2, $subjectAdmin, $htmlBody, 'Cộng Đồng Web');
        }
        return $this->sendHtmlEmail($user_data->email, $subject, $htmlBody, $user_data->name);
    }

    public function sendEmailOrderComplete($order_id,$sendAdmin = false)
    {
        global $CDWFunc;
        $customer_id = get_post_meta($order_id, 'customer-id', true);
        $user_id =  get_post_meta($customer_id, 'user-id', true);
        $user_data = $CDWFunc->wpdb->get_info_user($user_id);
        $email = get_post_meta($customer_id, 'email', true);
        $name = get_post_meta($customer_id, 'name', true);
        $subject = "Đặt Đơn Hàng Thành Công #" . $order_id . " - Cộng Đồng Web";
        $subjectAdmin = $name . " đặt Đơn Hàng Mới #" . $order_id . " - Cộng Đồng Web";
        $arg['order-id'] = $order_id;
        $arg['title'] = "ĐƠN ĐẶT HÀNG THÀNH CÔNG";
        $htmlBody =  $this->get_templete_email('order-complete', $arg);
         if ($sendAdmin) {
            $this->sendHtmlEmail(SMPT_USERNAME, $subjectAdmin, $htmlBody, 'Cộng Đồng Web');
            $this->sendHtmlEmail(EMAIL_SUPPORT2, $subjectAdmin, $htmlBody, 'Cộng Đồng Web');
        }

        return $this->sendHtmlEmail($email, $subject, $htmlBody, $name);
    }

    public function sendEmailOrderNew($order_id, $sendAdmin = false)
    {
        global $CDWFunc;
        $customer_id = get_post_meta($order_id, 'customer-id', true);
        $user_id =  get_post_meta($customer_id, 'user-id', true);
        //$user_data = $CDWFunc->wpdb->get_info_user($user_id);

        $email = get_post_meta($customer_id, 'email', true);

        $name = get_post_meta($customer_id, 'name', true);
        $subject = "Đặt Đơn Hàng Mới #" . $order_id . " - Cộng Đồng Web";
        $subjectAdmin = $name . " đặt Đơn Hàng Mới #" . $order_id . " - Cộng Đồng Web";
        $arg['order-id'] = $order_id;
        $arg['title'] = "ĐƠN ĐẶT HÀNG MỚI";
        $htmlBody =  $this->get_templete_email('order-new', $arg);

        if ($sendAdmin) {
            $this->sendHtmlEmail(SMPT_USERNAME, $subjectAdmin, $htmlBody, 'Cộng Đồng Web');
            $this->sendHtmlEmail(EMAIL_SUPPORT2, $subjectAdmin, $htmlBody, 'Cộng Đồng Web');
        }

        return $this->sendHtmlEmail($email, $subject, $htmlBody, $name);
    }
    public function sendEmailLicense($plugin_id, $sendAdmin = false)
    {
        global $CDWFunc;
        $plugin_name= get_post_meta($plugin_id, 'name', true);
        $plugin_date = get_post_meta($plugin_id, 'date', true);
        $plugin_type = get_post_meta($plugin_id, 'plugin-type', true);
        $plugin_name = get_post_meta($plugin_type, 'name', true);
        $plugin_expiry_date = get_post_meta($plugin_id, 'expiry_date', true);
        $plugin_license = get_post_meta($plugin_id, 'license', true);

        $customer_id = get_post_meta($plugin_id, 'customer-id', true);

        $email = get_post_meta($customer_id, 'email', true);

        $name = get_post_meta($customer_id, 'name', true);
        $license_info = cdw_get_license_info($plugin_license, $plugin_type);
        $subject = "Thông tin giấy phép plugin #" . $plugin_name . " - Cộng Đồng Web";
        $arg['plugin-id'] = $plugin_id;
        $arg['title'] = "Giấy phép Plugin " . $plugin_name;
        $arg['plugin-name'] = $plugin_name;
        $arg['plugin-date'] = $plugin_date;
        $arg['plugin-type'] = $plugin_type;
        $arg['plugin-expiry-date'] = $plugin_expiry_date;
        $arg['plugin-license'] = $plugin_license;
        $arg['plugin-url'] = $license_info['version_url'] . '?version=' . $license_info['version'];
        $arg['customer-id'] = $customer_id;
        
        $htmlBody =  $this->get_templete_email('license-plugin', $arg);
        return $this->sendHtmlEmail($email, $subject, $htmlBody, $name);
    }

    public function sendEmailNotificationDomain($ids)
    {
        global $CDWFunc;
        $customers = [];
        foreach ($ids as $id) {
            $customer_id = get_post_meta($id, 'customer-id', true);
            if (isset($customers[$customer_id])) {
                $customers[$customer_id]->ids[] = $id;
            } else {
                $item = new stdClass();
                $item->customer_id =  $customer_id;
                $item->user_id =  get_post_meta($item->customer_id, 'user-id', true);
                $item->user_data = $CDWFunc->wpdb->get_info_user($item->user_id);
                $item->email = get_post_meta($item->customer_id, 'email', true);
                $item->name = get_post_meta($item->customer_id, 'name', true);
                $item->ids[] = $id;
                $customers[$customer_id] = $item;
            }
        }
        foreach ($customers as $customer) {
            if (empty($customer->email)) continue;
            $subject = "Thông Báo Dịch Vụ Tên Miền Sắp Hết Hạn - Cộng Đồng Web";
            $arg['ids'] = $customer->ids;
            $arg['customer-id'] = $customer->customer_id;
            $arg['title'] = "THÔNG BÁO TÊN MIỀN SẮP HẾT HẠN";

            $htmlBody =  $this->get_templete_email('notification-domain', $arg);

            $this->sendHtmlEmail($customer->email, $subject, $htmlBody, $customer->name);
        }
    }

    public function sendEmailNotificationHosting($ids)
    {
        global $CDWFunc;
        foreach ($ids as $id) {
            $customer_id = get_post_meta($id, 'customer-id', true);
            if (isset($customers[$customer_id])) {
                $customers[$customer_id]->ids[] = $id;
            } else {
                $item = new stdClass();
                $item->customer_id =  $customer_id;
                $item->user_id =  get_post_meta($item->customer_id, 'user-id', true);
                $item->user_data = $CDWFunc->wpdb->get_info_user($item->user_id);
                $item->email = get_post_meta($item->customer_id, 'email', true);
                $item->name = get_post_meta($item->customer_id, 'name', true);
                $item->ids[] = $id;
                $customers[$customer_id] = $item;
            }
        }

        foreach ($customers as $customer) {
            if (empty($customer->email)) continue;
            $subject = "Thông Báo Dịch Vụ Hosting Sắp Hết Hạn - Cộng Đồng Web";
            $arg['ids'] = $customer->ids;
            $arg['customer-id'] = $customer->customer_id;
            $arg['title'] = "THÔNG BÁO HOSTING CỦA BẠN SẮP HẾT HẠN";
            $htmlBody =  $this->get_templete_email('notification-hosting', $arg);

            $this->sendHtmlEmail($customer->email, $subject, $htmlBody, $customer->name);
        }
    }
    public function sendEmailNotificationAllService($ids)
    {
        global $CDWFunc;
        foreach ($ids as $id) {
            $customer_id = get_post_meta($id, 'customer-id', true);
            if (isset($customers[$customer_id])) {
                $customers[$customer_id]->ids[] = $id;
            } else {
                $item = new stdClass();
                $item->customer_id =  $customer_id;
                $item->user_id =  get_post_meta($item->customer_id, 'user-id', true);
                $item->user_data = $CDWFunc->wpdb->get_info_user($item->user_id);
                $item->email = get_post_meta($item->customer_id, 'email', true);
                $item->name = get_post_meta($item->customer_id, 'name', true);
                $item->ids[] = $id;
                $customers[$customer_id] = $item;
            }
        }

        foreach ($customers as $customer) {
            if (empty($customer->email)) continue;
            $subject = "Thông Báo Dịch Vụ Sắp Hết Hạn - Cộng Đồng Web";
            $arg['ids'] = $customer->ids;
            $arg['customer-id'] = $customer->customer_id;
            $arg['title'] = "THÔNG BÁO DỊCH VỤ CỦA BẠN SẮP HẾT HẠN";
            $htmlBody =  $this->get_templete_email('notification-all-service', $arg);

            $this->sendHtmlEmail($customer->email, $subject, $htmlBody, $customer->name);
        }
    }
    public function sendEmailNotificationEmail($ids)
    {
        global $CDWFunc;
        foreach ($ids as $id) {
            $customer_id = get_post_meta($id, 'customer-id', true);
            if (isset($customers[$customer_id])) {
                $customers[$customer_id]->ids[] = $id;
            } else {
                $item = new stdClass();
                $item->customer_id =  $customer_id;
                $item->user_id =  get_post_meta($item->customer_id, 'user-id', true);
                $item->user_data = $CDWFunc->wpdb->get_info_user($item->user_id);
                $item->email = get_post_meta($item->customer_id, 'email', true);
                $item->name = get_post_meta($item->customer_id, 'name', true);
                $item->ids[] = $id;
                $customers[$customer_id] = $item;
            }
        }

        foreach ($customers as $customer) {
            if (empty($customer->email)) continue;
            $subject = "Thông Báo Dịch Vụ Email Sắp Hết Hạn - Cộng Đồng Web";
            $arg['ids'] = $customer->ids;
            $arg['customer-id'] = $customer->customer_id;
            $arg['title'] = "THÔNG BÁO EMAIL CỦA BẠN SẮP HẾT HẠN";
            $htmlBody =  $this->get_templete_email('notification-email', $arg);

            $this->sendHtmlEmail($customer->email, $subject, $htmlBody, $customer->name);
        }
    }
    public function sendEmailUserForgotPassword($user_id, $password)
    {
        global $CDWFunc;
        $subject = "Thay đổi mật khẩu - Cộng Đồng Web";
        $arg['user-id'] = $user_id;
        $arg['password'] = $password;
        $user_data = $CDWFunc->wpdb->get_info_user($user_id);
        $arg['title'] = "ĐỔI MẬT KHẨU";
        $htmlBody =  $this->get_templete_email('user-forgot-password', $arg);
        return $this->sendHtmlEmail($user_data->email, $subject, $htmlBody, $user_data->name);
    }

    public function sendEmailKYCRejected($customer_id, $reason)
    {
        global $CDWFunc;
        $email = get_post_meta($customer_id, 'email', true);
        $name = get_post_meta($customer_id, 'name', true);
        $subject = "Thông Báo Về Việc Xác Thực Tài Khoản (KYC) - Cộng Đồng Web";
        $arg['reason'] = $reason;
        $arg['customer_id'] = $customer_id;
        $arg['title'] = "XÁC THỰC TÀI KHOẢN KHÔNG THÀNH CÔNG";
        $htmlBody =  $this->get_templete_email('kyc-rejected', $arg);
        return $this->sendHtmlEmail($email, $subject, $htmlBody, $name);
    }

    public function sendAdminNotificationCustomerUpdate($customer_id)
    {
        $to = SMPT_USERNAME;
        $subject = '[CongDongWeb] Khách hàng đã cập nhật thông tin KYC';
        $arg['customer-id'] = $customer_id;
        $arg['title'] = 'KHÁCH HÀNG CẬP NHẬT THÔNG TIN KYC';
        $body = $this->get_templete_email('admin-customer-info-updated', $arg);
        return $this->sendHtmlEmail($to, $subject, $body, 'Admin');
    }


    public function get_templete_email($filename, $arg)
    {
        ob_start();
        require ADMIN_THEME_URL . '/templates/email/header.php';
        require ADMIN_THEME_URL . '/templates/email/' . $filename . '.php';
        require ADMIN_THEME_URL . '/templates/email/footer.php';
        $data = ob_get_clean();
        return  $data;
    }
}
