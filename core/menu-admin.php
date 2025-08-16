<?php
defined('ABSPATH') || exit;
class MenuAdmin
{
    public function __construct() {}

    public function getItemMenu($data, $hasActiveMenu = false)
    {
        global $CDWFunc;
        if (isset($data->show) && $data->show && $this->checkPermission($data)) {
            $current = $this->checkItemActive($data);
            $hasarrow = false;
            if (isset($data->items) && is_array($data->items))
                foreach ($data->items as $item) {
                    if (isset($item->show) && $item->show) {
                        $hasarrow = true;
                        break;
                    }
                }
            ob_start();
?>
            <li class="<?php echo !$hasActiveMenu && $current->activeAction && $current->activeModule ? 'active ' : ' ';
                        echo  $hasarrow > 0 ? "has-child" : ""; ?>">
                <a href="<?php echo $CDWFunc->getUrl($data->action, $data->module); ?>" class="<?php echo  $hasarrow > 0 ? "has-arrow" : ""; ?>"><i class="<?php echo $data->icon; ?>"></i>
                    <?php
                    if ($hasarrow) echo '<span>';
                    echo $data->actionName;
                    if ($hasarrow) echo '</span>';
                    ?>
                </a>
                <?php
                if ($hasarrow && is_array($data->items)) {
                    echo '<ul>';
                    foreach ($data->items as $item) {
                        if (!$data->show) continue;
                        $menu = $this->getItemMenu($item, $hasActiveMenu);
                        $hasActiveMenu = $hasActiveMenu || $menu['hasActiveMenu'];
                        echo $menu['html'];
                    }
                    echo '</ul>';
                }
                ?>
            </li>

<?php
            $obj = ob_get_clean();
            $hasActiveMenu = $hasActiveMenu || $current->activeAction && $current->activeModule;
            return  ['html' => $obj, 'hasActiveMenu' => $hasActiveMenu];
        } else
            return  ['html' => '', 'hasActiveMenu' => false];
    }
    public function checkItemActive($data)
    {
        $activeAction =  strtolower($data->action) == strtolower(ACTION_ADMIN);
        $activeModule =  strtolower($data->module) == strtolower(MODULE_ADMIN);
        if (!($activeAction && $activeModule) && isset($data->items) && is_array($data->items) && count($data->items) > 0) {
            foreach ($data->items as $item) {
                $re = $this->checkItemActive($item);
                $activeAction = $activeAction || $re->activeAction;
                $activeModule = $activeModule || $re->activeModule;
                if ($activeAction && $activeModule) break;
            }
        }
        return (object) ["activeAction" => $activeAction, "activeModule" => $activeModule];
    }

    public function checkPermission($data)
    {
        global $CDWFunc;
        $has = $CDWFunc->checkPermission($data->action, $data->module);

        if (!$has && isset($data->items) && is_array($data->items) && count($data->items) > 0) {
            foreach ($data->items as $item) {
                $re = $this->checkPermission($item);
                $has = $has || $re;
                if ($has) break;
            }
        }
        return $has;
    }
}
