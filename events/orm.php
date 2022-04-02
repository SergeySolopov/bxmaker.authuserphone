<?php



$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler(
    "bxmaker.authuserphone",
    "\Bxmaker\AuthUserPhone\Manager\Limit::OnBeforeAdd",
    "bxmaker_authuserphone_manager_limit_onBeforeAdd"
);


/**
 * ���������� ������� ���������� ����� ����������� ������ � ������� �������
 * � ��������� � ������ ��������
 * @param \Bitrix\Main\Entity\Event $event
 * @return \Bitrix\Main\Entity\EventResult
 */
function bxmaker_authuserphone_manager_limit_onBeforeAdd(\Bitrix\Main\Entity\Event $event)
{
    $result = new \Bitrix\Main\Entity\EventResult;

    $fields = $event->getParameter("fields");

    // ��� ���������� ������ � ����, ����� ����� ������� ��� �������
    // ��������� 5 ��� ��������� ��� � ���, �� ������ ��� �������������
    // ������ ��������

    if($fields['PHONE'] === '79991112233')
    {
        $result->modifyFields(array('ATTEMPT_REQUEST_SMS_CODE' => 5));
    }

    return $result;
}
