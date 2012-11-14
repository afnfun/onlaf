<?php
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
session_start();
include ("../includes/config.php");
//
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'last_update';
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'asc';
$query = isset($_POST['query']) ? $_POST['query'] : false;
$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
// /*-- To use the SQL, remove this block
$usingSQL = true;
function runSQL($rsql) {
        $result = mysql_query($rsql);
        return $result;
        
        }
//
function countRec($fname,$tname,$where) {
        $sql = "SELECT count($fname) FROM $tname $where";
        $result = runSQL($sql);
        while ($row = mysql_fetch_array($result)) {
                return $row[0];
        }
}
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
//
if (!$sortname) $sortname = 'code';
if ($sortname=='sender') $sortname='source_user_id'; 
if (!$sortorder) $sortorder = 'asc';
//
$sort = "ORDER BY $sortname $sortorder";
//
if (!$page) $page = 1;
if (!$rp) $rp = 10;
//
$start = (($page-1) * $rp);
//
$limit = "LIMIT $start, $rp";
$where = "where destination_user_id='".$_SESSION['user']."' AND code_transfer_confirmation='pending'";
$where2 ="where";
$where3 ="where";
if ($query) {
    //$ss="insert into m001 (user_id,item_name,item_description) values ('0','".$query."','".$qtype."')";			
    //$res= runSQL($ss);
    if ($qtype=='code' || $qtype=='code_transfer_time') $where = "WHERE $qtype like '%$query%' and destination_user_id='".$_SESSION['user']."' and code_transfer_confirmation='pending'";
    if ($qtype=='item_name') $where2 = "WHERE $qtype like '%$query%' and ";
    if ($qtype=='sender') $where3 = "WHERE (name like '%$query%' or surename like '%$query%' or CONCAT(name,' ',surename) like '%$query%') and ";
}
//$sql = "SELECT iso,name,printable_name,iso3,numcode FROM country $where $sort $limit";
$item_table= "message_board"; // select default table name
$sql = "SELECT code,source_user_id,code_transfer_time FROM ".$item_table." $where $sort $limit"; // check of items belong to current user
 
$result = runSQL($sql);

$total = countRec('code',$item_table,$where);
$rows = array();
while ($row = mysql_fetch_array($result)) {
    // get item name from code
    $sql2 = "SELECT item_name FROM m001 $where2 item_code='".$row['code']."'"; // check of items belong to current user
    $result2 = runSQL($sql2);
    $row2 = mysql_fetch_array($result2);
    if(!$row2)        continue;
    $row['item_name']=$row2['item_name'];
    // get sender name from user_id
    $sql3 = "SELECT name,surename FROM users $where3 user_id='".$row['source_user_id']."'"; // check of items belong to current user
    $result3 = runSQL($sql3);
    $row3 = mysql_fetch_array($result3);
    if(!$row3)        continue;
    $row['sender']=$row3['name'].'&nbsp;'.$row3['surename'];
    //
    $rows[] = $row;
}
//*/////////////////////////////////////////////
if(!isset($usingSQL)){
        include dirname(__FILE__).'/countryArray.inc.php';
        if($qtype && $query){
                $query = strtolower(trim($query));
                foreach($rows AS $key => $row){
                        if(strpos(strtolower($row[$qtype]),$query) === false){
                                unset($rows[$key]);
                        }
                }
        }
        //Make PHP handle the sorting
        $sortArray = array();
        foreach($rows AS $key => $row){
                $sortArray[$key] = $row[$sortname];
        }
        $sortMethod = SORT_ASC;
        if($sortorder == 'desc'){
                $sortMethod = SORT_DESC;
        }
        array_multisort($sortArray, $sortMethod, $rows);
        $total = count($rows);
        $rows = array_slice($rows,($page-1)*$rp,$rp);
}
///////////////////////////////
header("Content-type: text/xml");
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$total</total>";
foreach($rows AS $row){
        $xml .= "<row id='".$row['code']."'>";
        $xml .= "<cell><![CDATA[".$row['item_name']."]]></cell>";
        $xml .= "<cell><![CDATA[".$row['code']."]]></cell>";
        //$xml .= "<cell><![CDATA[".print_r($_POST,true)."]]></cell>";
        $xml .= "<cell><![CDATA[".$row['sender']."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['code_transfer_time'])."]]></cell>";
        $xml .= "</row>";
}

$xml .= "</rows>";
echo $xml;
?>