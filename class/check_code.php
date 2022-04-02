<?

// ������ ����������� �������� ���� � ����������� / ���������������

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
    $code = trim((string)$req->getPost('code'));

    if (!$oManagerAuthUserPhone->isValidPhone($phone)) {
        $arResponse['error'] = '����� ���������� �������� ������ �� �����';
        break;
    }

    if (strlen($code) <= 0) {
        $arResponse['error'] = '�� ������ ��� �� ���';
        break;
    }


    $formattdPhone = $oFormat->getFormatedPhone($phone, true, true, true, true);

    try {

        $oManagerAuthUserPhone
            ->limitIP()
            ->setType(\BXmaker\AuthUserPhone\Manager::CONFIRM_TYPE_SMS_CODE)
            ->checkCanDoCheck();

        $oManagerAuthUserPhone
            ->limit()
            ->setType(\BXmaker\AuthUserPhone\Manager::CONFIRM_TYPE_SMS_CODE)
            ->setPhone($phone)
            ->checkCanDoCheck();

        $oManagerAuthUserPhone->limitIp()->setCheck();
        $oManagerAuthUserPhone->limit()->setCheck();

        $checkSmsCodeResult = $oManagerAuthUserPhone->service()->checkSmsCode($phone, $code);
        if (!$checkSmsCodeResult->isSuccess()) {
            $checkSmsCodeResult->throwException();
        }


        $userId = null;

        $findUserResult = $oManagerAuthUserPhone->findUserIdByPhone($phone, true);
        if ($findUserResult->isSuccess()) {
            $userId = (int)$findUserResult->getResult();
        } else {
            $findInactiveResult = $oManagerAuthUserPhone->findUserIdByPhone($phone, false);
            if ($findInactiveResult->isSuccess()) {
                throw new \Bxmaker\AuthUserPhone\Exception\BaseException('������������ ������������', 'ERROR_USER_ACTIVE');
            }
        }

        //������������ ���� ����
        if (is_null($userId) && $oManagerAuthUserPhone->param()->isEnabledAutoRegister()) {
            $registerResult = $oManagerAuthUserPhone->register($phone);
            if (!$registerResult->isSuccess()) {
                $registerResult->throwException();
            }

            $userId = (int)$registerResult->getResult();
        }

        // �� ������� ����������
        if (is_null($userId)) {
            throw new \Bxmaker\AuthUserPhone\Exception\BaseException('������������ �� ������', 'ERROR_USER_ID');
        }

        $authResult = $oManagerAuthUserPhone->authorize($userId);
        if (!$authResult->isSuccess()) {
            $authResult->throwException();
        }


        $arResponse['msg'] = '����������� ������ �������';


    } catch (\Bxmaker\AuthUserPhone\Exception\BaseException $ex) {
        $arResponse['error'] = $ex->getMessage();
        $arResponse['more'] = $ex->getCustomCode();
    }
} while (false);

$arResponse['formattedPhone'] = $formattdPhone;


echo \Bitrix\Main\Web\Json::encode($arResponse);


