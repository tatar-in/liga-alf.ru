<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->SetTitle("Протокол");

$APPLICATION->AddChainItem($APPLICATION->GetTitle() );

?>

<?

if (CSite::InGroup( array(1,7) )) {

    CModule::IncludeModule('iblock');
    
    // echo 'Вот такие данные мы передали';
    // echo '<pre>';
    // print_r($_POST);
    // echo '<pre>';


    //Погнали
   $el = new CIBlockElement;

    //Свойства
    $PROP = array();
    $PROP['STAGE'] = $_POST['stage'];
    $PROP['TEAM_1'] = $_POST['team_1']; 
    $PROP['TEAM_2'] = $_POST['team_2']; 
    $PROP['STRUCTURE_TEAM_1'] = $_POST['team_1_player']; 
    $PROP['STRUCTURE_TEAM_2'] = $_POST['team_2_player']; 
    $PROP['GOALS_TEAM_1'] = $_POST['team_1_goals']; 
    $PROP['GOALS_TEAM_2'] = $_POST['team_2_goals']; 
    $PROP['YELLOW_CARDS_TEAM_1'] = $_POST['team_1_yellow_card']; 
    $PROP['YELLOW_CARDS_TEAM_2'] = $_POST['team_2_yellow_card']; 
    $PROP['TWO_YELLOW_CARDS_TEAM_1'] = $_POST['team_1_two_yellow_card']; 
    $PROP['TWO_YELLOW_CARDS_TEAM_2'] = $_POST['team_2_two_yellow_card']; 
    $PROP['RED_CARDS_TEAM_1'] = $_POST['team_1_red_card']; 
    $PROP['RED_CARDS_TEAM_2'] = $_POST['team_2_red_card']; 
    $PROP['AUTO_GOALS_TEAM_1'] = $_POST['team_1_autogoals']; 
    $PROP['AUTO_GOALS_TEAM_2'] = $_POST['team_2_autogoals']; 
    $PROP['PENALTY_TEAM_1'] = $_POST['team_1_penalty']; 
    $PROP['PENALTY_TEAM_2'] = $_POST['team_2_penalty']; 

    
    // echo 'Вот такие данные мы записали в PROP';
    // echo '<pre>';
    // print_r($PROP);
    // echo '<pre>';

    //Основные поля элемента
    $fields = array(
        "MODIFIED_BY" => $GLOBALS['USER']->GetID(),    //Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => "3", //ID информационного блока 
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    
    $EL_ID = $_POST['game_id'];  // изменяем элемент с кодом (ID) 
    
    
    //Результат в конце отработки
    if (!$el->Update($EL_ID, $fields)) {
        echo $el->LAST_ERROR;
    }
}
?>
   
    <script>
        window.location.href = '<? echo CUtil::JSEscape("/personal/tournament/?SECTION_ID=".$_POST['section_id'])?>';
    </script> 



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>