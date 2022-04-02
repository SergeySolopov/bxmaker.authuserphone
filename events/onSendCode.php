<?php


$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler(
    "bxmaker.authuserphone",
    "onSendCode",
    "bxmaker_authuserphone_onSendCode"
);


function bxmaker_authuserphone_onSendCode(\Bitrix\Main\Event $event)
{
    $arParams = $event->getParameters();

    // �������� ����������, ����� �������� ������������ � ��������� �����
    if ($arParams['PHONE'] == '79991112233') {
        throw  new \Bxmaker\AuthUserPhone\Exception\BaseException(
            '�� ��� ����� ��������� ���������� ��������� ����',
            'ERROR_INVALID_PHONE'
        );
    }

    // ��� ������ ������, ����� ���� ����������� �������
    // ��������� ��� ����� ���������� ��� �������� ��������
    if ($arParams['PHONE'] == '79991112244') {
        return new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::ERROR,
            new \Bitrix\Main\Error(
                '�� ������� ��������� ��� ����� ���� ������, �� ���������� ����'
            )
        );
    }

    //�������� ������ ���������� ������ �� ������� ��� �������
    //  ������ �������  � ������ ��������, �
    //������ ��� ������ �������� ���� � ���
    if ($arParams['PHONE'] == '79991112255') {
        return new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::SUCCESS,
            [
                'TEST' => 1212
            ]
        );
    }

    //����� ��� ��, �� ��������� ������� ������
    return new \Bitrix\Main\EventResult(
        \Bitrix\Main\EventResult::SUCCESS,
        null
    );

}