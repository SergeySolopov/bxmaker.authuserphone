<?php

// �������� ���������� ���� � ���

$arResponse = [];

$formattdPhone = '';

do {

    if (!\Bitrix\Main\Loader::includeModule('bxmaker.authuserphone')) {
        $arResponse['error'] = '�� ���������� ������ ����������� �� ������ ��������';
        break;
    }

    $oManagerAuthUserPhone = \BXmaker\AuthUserPhone\Manager::getInstance();

    $oFormat = new \Bxmaker\AuthUserPhone\Format();

    $req = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

    $phone = $oManagerAuthUserPhone->getPreparedPhone((string)$req->getPost('phone'));

    if (!$oManagerAuthUserPhone->isValidPhone($phone)) {
        $arResponse['error'] = '����� ���������� �������� ������ �� �����';
        break;
    }

    $formattdPhone = $oFormat->getFormatedPhone($phone, true, true, true, true);

    try {

        $oManagerAuthUserPhone
            ->limitIP()
            ->setType(\BXmaker\AuthUserPhone\Manager::CONFIRM_TYPE_SMS_CODE)
            ->checkCanDoRequest();

        $oManagerAuthUserPhone
            ->limit()
            ->setType(\BXmaker\AuthUserPhone\Manager::CONFIRM_TYPE_SMS_CODE)
            ->setPhone($phone)
            ->checkCanDoRequest();

        $oManagerAuthUserPhone->checkSmsCodeTimeout($phone);

        $oManagerAuthUserPhone->limitIp()->setRequest();
        $oManagerAuthUserPhone->limit()->setRequest();

        $startSmsCodeResult = $oManagerAuthUserPhone->service()->startSmsCode($phone);
        if (!$startSmsCodeResult->isSuccess()) {
            $startSmsCodeResult->throwException();
        }

        $arResponse['timeout'] = $startSmsCodeResult->getMore('TIMEOUT');
        $arResponse['length'] = $startSmsCodeResult->getMore('LENGTH');
        $arResponse['msg'] = $startSmsCodeResult->getMore('MSG');


    } catch (\Bxmaker\AuthUserPhone\Exception\BaseException $ex) {

        $arResponse['error'] = $ex->getMessage();
        $arResponse['more'] = $ex->getCustomCode();
    }

} while (false);

$arResponse['formattedPhone'] = $formattdPhone;


echo \Bitrix\Main\Web\Json::encode($arResponse);








