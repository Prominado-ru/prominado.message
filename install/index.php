<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

class prominado_message extends CModule
{
    var $MODULE_ID = 'prominado.message';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $MODULE_CSS;

    public function prominado_message()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = Loc::getMessage('PROMINADO_MODULE_MESSAGE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('PROMINADO_MODULE_MESSAGE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('PROMINADO_MODULE_MESSAGE_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('PROMINADO_MODULE_MESSAGE_PARTNER_WEBSITE');
    }

    public function DoInstall()
    {
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/components/prominado',
            Application::getDocumentRoot() . '/bitrix/components/prominado', true, true);
    }

    public function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/components/prominado',
            Application::getDocumentRoot() . '/bitrix/components/prominado');
    }
}