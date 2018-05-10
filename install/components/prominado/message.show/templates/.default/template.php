<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<? if ($arResult['MESSAGE']) { ?>
    <div class="prominado_info"><?= $arResult['MESSAGE']; ?></div>
<? } ?>