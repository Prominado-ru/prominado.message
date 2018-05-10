<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

try {
    Loader::includeModule('fileman');
    Loader::includeSharewareModule('prominado.message');
} catch (\Bitrix\Main\LoaderException $e) {
}

Loc::loadMessages(__FILE__);

$sites = [];
$res = CSite::GetList($by = 'ID', $order = 'ASC');
while ($ar = $res->GetNext()) {
    $sites[] = $ar;
}

$post = \Bitrix\Main\Context::getCurrent()->getRequest()->getPostList()->toArray();
try {
    if (!empty($post['settings'])) {
        foreach ($post['settings'] as $name => $val) {
            if ($val) {
                Option::set('prominado.message', $name, $val);
            } else {
                Option::delete('prominado.message', ['name' => $name]);
            }
        }
    }

    foreach ($sites as $site) {
        if ($post['info_message_' . $site['ID']]) {
            Option::set('prominado.message', 'info_message_' . $site['ID'], $post['info_message_' . $site['ID']]);
        } else {
            Option::delete('prominado.message', ['name' => 'info_message_' . $site['ID']]);
        }
    }
} catch (\Bitrix\Main\ArgumentOutOfRangeException $e) {
} catch (\Bitrix\Main\ArgumentNullException $e) {
}

$tabs = [];
foreach ($sites as $site) {
    $tabs[] = [
        'DIV' => 'site_' . $site['ID'],
        'TAB' => $site['NAME'],
        'ICON' => '',
        'TITLE' => Loc::getMessage('PROMINADO_MODULE_MESSAGE_SETTINGS') . ': ' . $site['NAME']
    ];
}

$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();

echo '<form name="prominado.disable" method="POST" action="' . $APPLICATION->GetCurPage() . '?mid=prominado.message&lang=' . LANGUAGE_ID . '" enctype="multipart/form-data">' . bitrix_sessid_post();
foreach ($sites as $site) {
    $tabControl->BeginNextTab();

    $message = '';
    $is_displayed = false;
    try {
        $message = Option::get('prominado.message', 'info_message_' . $site['ID']);
        $is_displayed = Option::get('prominado.message', 'is_displayed_' . $site['ID']) === 'Y';
    } catch (\Bitrix\Main\ArgumentNullException $e) {
    } catch (\Bitrix\Main\ArgumentOutOfRangeException $e) {
    }
    ?>
    <tr class="heading">
        <td colspan="2"><?= Loc::getMessage('PROMINADO_MODULE_MESSAGE_INFO_MESSAGE'); ?></td>
    </tr>
    <tr>
        <td width="40%" nowrap="" class="adm-detail-content-cell-l">
            <label for="is_displayed_<?= $site['ID']; ?>"><?= Loc::getMessage('PROMINADO_MODULE_MESSAGE_SHOW_MESSAGE'); ?>
                :</label>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="hidden" name="settings[is_displayed_<?= $site['ID']; ?>]" value="N"/>
            <input type="checkbox" id="is_displayed_<?= $site['ID']; ?>"
                   name="settings[is_displayed_<?= $site['ID']; ?>]"
                   value="Y"<?= $is_displayed ? ' checked' : ''; ?> />
        </td>
    </tr>
    <tr>
        <td width="40%" nowrap="" class="adm-detail-content-cell-l">
            <label for="top_panel_text"><?= Loc::getMessage('PROMINADO_MODULE_MESSAGE_MESSAGE'); ?>:</label>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <?php
            $editor = new CHTMLEditor();
            $editor->Show([
                'content' => $message,
                'minBodyHeight' => 200,
                'bAllowPhp' => true,
                'limitPhpAccess' => false,
                'showTaskbars' => false,
                'showNodeNavi' => false,
                'askBeforeUnloadPage' => true,
                'bbCode' => false,
                'controlsMap' => [
                    ['id' => 'Bold', 'compact' => true, 'sort' => 80],
                    ['id' => 'Italic', 'compact' => true, 'sort' => 90],
                    ['id' => 'Underline', 'compact' => true, 'sort' => 100],
                    ['id' => 'Strikeout', 'compact' => true, 'sort' => 110],
                    ['id' => 'RemoveFormat', 'compact' => true, 'sort' => 120],
                    ['id' => 'Color', 'compact' => true, 'sort' => 130],
                    ['id' => 'FontSize', 'compact' => false, 'sort' => 140],
                    ['separator' => true, 'compact' => false, 'sort' => 145],
                    ['id' => 'OrderedList', 'compact' => true, 'sort' => 150],
                    ['id' => 'UnorderedList', 'compact' => true, 'sort' => 160],
                    ['id' => 'AlignList', 'compact' => false, 'sort' => 190],
                    ['separator' => true, 'compact' => false, 'sort' => 200],
                    ['id' => 'InsertLink', 'compact' => true, 'sort' => 210],
                    ['id' => 'InsertImage', 'compact' => false, 'sort' => 220],
                    ['id' => 'InsertTable', 'compact' => false, 'sort' => 250],
                    ['id' => 'Quote', 'compact' => true, 'sort' => 270],
                    ['separator' => true, 'compact' => false, 'sort' => 290],
                    ['id' => 'Fullscreen', 'compact' => false, 'sort' => 310],
                    ['id' => 'BbCode', 'compact' => true, 'sort' => 340],
                    ['id' => 'More', 'compact' => true, 'sort' => 400],
                ],
                'siteId' => SITE_ID,
                'autoResize' => true,
                'autoResizeOffset' => 30,
                'saveOnBlur' => true,
                'setFocusAfterShow' => false,
                'name' => 'info_message_' . $site['ID'],
                'id' => 'info_message_' . $site['ID'],
                'width' => '100%',
                'arSmiles' => [],
            ]);
            ?>
        </td>
    </tr>
<? } ?>
<?
$tabControl->Buttons();

echo '<input type="hidden" name="update" value="Y" />';
echo '<input type="submit" name="save" value="' . Loc::getMessage('PROMINADO_MODULE_MESSAGE_SAVE') . '" class="adm-btn-save" />';
echo '<input type="reset" name="reset" value="' . Loc::getMessage('PROMINADO_MODULE_MESSAGE_RESET') . '" />';
echo '</form>';

$tabControl->End();