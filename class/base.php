<?

// ������� ������� �������������

// ����������� ������

if (\Bitrix\Main\Loader::includeModule('bxmaker.authuserphone')) {
    $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();

    //�������� ...
    $phone = $oManager->getPreparedPhone('8 999-111-22-33');
    echo $phone; // 79991112233
}

// Result
// ������ ���� ������� ���� �������� ������

$result = new \Bxmaker\AuthUserPhone\Result();

// �������� �������� ���������
$result->setResult(10);
echo $result->getResult(); // 10

// �������� ������������� ������
$result->setMore('MSG', '��� ����������');
echo $result->getMore('MSG'); //��� ����������


// ��������� �������� �� ���������
if ($result->isSuccess()) {
    echo (string)$result->getResult();
}


// ������ � ��������
// ���� � �������� �������� �������� ������, �� ����� �� �������� � ����������.
// ������������� ������ ����� ������ ���������� ���������� � ���� ������,
//  ������ ������ \Bxmaker\Authuserphone\Error

// ������ � �������
$result = new \Bxmaker\AuthUserPhone\Result();
$result->createError(
    '������� �� ��������',
    'ERROR_PHONE_INVALID',
    [
        'captcha' => \Bxmaker\AuthUserPhone\Manager::getInstance()->captcha()->getForJs()
    ]
);


// ����� ���������� �������� ������ ������ ����� � �������
$result->createError(
    '������� ��� � ��������',
    'ERROR_NEED_CAPTCHA',
    [
        'captcha' => \Bxmaker\AuthUserPhone\Manager::getInstance()->captcha()->getForJs()
    ]
);


// ����� �������� ������ �� ������
echo $result->getFirstError()->getCode(); //  ERROR_NEED_CAPTCHA
echo $result->getFirstError()->getMessage(); //  ������� ��� � ��������
var_export($result->getFirstError()->getMore()); //  ['captcha' => [...]]


// ���� ����� ������� ������ �� ����������, �� ������ ���������
$ex = new \Exception('Error');
$result->createErrorFromException($ex);
echo $result->getFirstError()->getMessage(); //  Error


// ����� ����� ��������� ���������� ��� ������� ������
if (!$result->isSuccess()) {
    $result->throwException();
    // throw new \Bxmaker\AuthUserPhone\Exception\BaseException()
}


//  ���������� ������ ��������
//��� ������������� ������ ��������, ��� ���������� �������� � ���� ���������� ��� ������������� �������������

$phone = $oManager->getPreparedPhone('+7 (999 111 22-33');
echo $phone; // 79991112233

// �������� ���������� ������ ��������
if ($oManager->isValidPhone($phone)) {
    echo '����� �������� ������ �����';
}

// �������� ���������� ������
// ���� ����� �������� �������� ���������� � ������������� ����� ��������, ���� ������ � ����� ������ ������ � �������� � ��������, ��������� ������

// ����������
// �� ip ������
$oManager->limitIP()->setType(\Bxmaker\AuthUserPhone\Manager::CONFIRM_TYPE_SMS_CODE);

//��� ������� � ��������� � ������, �������� �����
$oManager->limit()->setType(\Bxmaker\AuthUserPhone\Manager::CONFIRM_TYPE_SMS_CODE)->setPhone($phone);

// ��������� �����
// �� ip ������
$oManager->limitIP()->checkCanDoRequest();

//��� ������� � ��������� � ������, �������� �����
$oManager->limit()->checkCanDoRequest();

// ��������� ������� �������
// �� ip ������
$oManager->limitIP()->setRequest();

//��� ������� � ��������� � ������
$oManager->limit()->setRequest();


// ��������� ������� ��������
// �� ip ������
$oManager->limitIP()->setCheck();

//��� ������� � ��������� � ������
$oManager->limit()->setCheck();

//  ��������� �������� ����� ��������� ������ �������������, �������� ��� ���
$time = $oManager->getSmsCodeTimeout($phone);
$time = $oManager->getUserCallTimeout($phone);
$time = $oManager->getBotCallTimeout($phone);

//���� ����� ����� ��������� ��� �� �����, �� �������� ��������������� ����������
$oManager->checkSmsCodeTimeout($phone);
$oManager->checkUserCallTimeout($phone);
$oManager->checkBotCallTimeout($phone);


// ����� �� ������ �������� � ������
$phone = '79991112233';
$password = 'JIO^fne64V+3';

$userIdResult = $oManager->findUserIdByPhonePassword($phone, $password);
if ($userIdResult->isSuccess()) {
    $userId = (int)$userIdResult->getResult();
}


// �����������
// ����������� ������������,  ���������� �������� ����� ���� ��������

$userId = 10;

$resultAuth = $oManager->authorize($userId);
if (!$resultAuth->isSuccess()) {
    //  ����� �������� ��������� ����������, ����� �� ��������� ��� �����
    $resultAuth->throwException();
}

// ���������������

//  ���� ������������ � ����� ������� �������� ���, ���������� ��� ������������������
$arUserFields = [];
$registerResult = $oManager->register($phone, $arUserFields);
if (!$registerResult->isSuccess()) {
    $registerResult->throwException();
}

$userId = (int)$registerResult->getResult();

// ��������� ��������
$oManager->param()->isEnabledAutoRegister();


// ����� �����
$oManager->setSiteId('s2');

// ���������������
$oManager->setSiteId();












