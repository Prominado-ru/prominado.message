<?php

namespace Prominado\Components;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

class MessageShow extends \CBitrixComponent
{
    public function executeComponent()
    {
        $this->prepareComponent();

        $siteId = $this->arParams['SITE_ID'] ?: SITE_ID;
        $this->arResult['MESSAGE'] = $this->getMessage($siteId);

        $this->includeComponentTemplate();
    }

    private function prepareComponent()
    {
        Loader::includeSharewareModule('prominado.message');
    }

    private function getMessage($siteId)
    {
        try {
            if (Option::get('prominado.message', 'is_displayed_' . $siteId) !== 'Y') {
                return '';
            }
            return Option::get('prominado.message', 'info_message_' . $siteId);
        } catch (ArgumentNullException $e) {
        } catch (ArgumentOutOfRangeException $e) {
        }

        return '';
    }
}