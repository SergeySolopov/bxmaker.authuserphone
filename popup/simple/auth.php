<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

/**
 * @var $APPLICATION \CMain
 */

// ����������� ���������� ������������ ��� ������ ���������� � ��������� �����
\Bitrix\Main\UI\Extension::load('bxmaker.authuserphone.simple');
echo \CJSCore::GetHTML(['bxmaker.authuserphone.simple']);

// ����������� ����������
$APPLICATION->IncludeComponent(
    'bxmaker:authuserphone.simple',
    '',
    [
        'COMPOSITE_FRAME_MODE' => 'N'
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>