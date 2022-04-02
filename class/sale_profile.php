<?

// �������� ������� ���������� ���  ����������� �� ������ ��������,
// ����� ��� �������� �� �������� ���������� ������
// ���� ������� � ������������ ��� ���� ��������� �� ���������������� �������


//  �������� �������, ��� �������
//--------------------------------------------------

\Bitrix\Main\Loader::includeModule('bxmaker.authuserphone');


// ������ ��������� �������, ��� �������� ����������� ����

$oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();
$sendEventResult = $oManager->sendEvent(
    \Bxmaker\AuthUserPhone\Manager::EVENT_ON_USER_ADD,
    [
        'PHONE' => '79991112233', // ��������
        'PASSWORD' => 'testPassword'.time(), //������
        'ID' => '10', //  ������������� ������������
        'USER_ID' => '10', //������������� ������������
    ]
);



// ���������� �������,  ������� ����� ��������� � /bitrix/php_interface/init.php
//---------------------------------------------------------------------

// ������������� �� ������� ������
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler(
    "bxmaker.authuserphone",
    "onUserAdd",
    "bxmaker_authuserphone_onUserAdd"
);



// ����������� �������
function bxmaker_authuserphone_onUserAdd(\Bitrix\Main\Event $event)
{
    //������������� ���� �����������
    $personalTypeId = 1;
    //������������� �������� ��� �������� ������ ��������
    $phonePropId = 3;

    $arFields = $event->getParameter('FIELDS');


    //������ ������� ������������ �������
    $arProfileProps = [
        [
            "ORDER_PROPS_ID" => $phonePropId, //������������� �������� ������ � ������� �������� ��� ����������� ���� �����������
            "NAME" => "�������",
            "VALUE" => $arFields['PHONE']
        ]
    ];

    if (\Bitrix\Main\Loader::includeModule('sale')) {

        //������ ����� ������� --------
        $arProfileFields = [
            "NAME" => "������� ���������� (" . $arFields['PHONE'] . ')',
            "USER_ID" => intval($arFields['USER_ID']),
            "PERSON_TYPE_ID" => $personalTypeId
        ];

        $profileId = CSaleOrderUserProps::Add($arProfileFields);

        //���� ������� ������
        if ($profileId) {

            //��������� �������� ������� � ���������� ����� �������
            foreach ($arProfileProps as $arProp) {

                $resultProp = CSaleOrderUserPropsValue::Add(array_merge($arProp, [
                    "USER_PROPS_ID" => $profileId,
                ]));
            }
        }
    }

    $result = new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, []);
    return $result;
}
