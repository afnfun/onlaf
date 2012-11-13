<?php
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
session_start();
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
//
        $db['default']['hostname'] = "localhost";
        $db['default']['username'] = 'root';
        $db['default']['password'] = "root";
        $db['default']['database'] = "info2";
//
        $db['live']['hostname'] = 'localhost';
        $db['live']['username'] = 'root';
        $db['live']['password'] = 'root';
        $db['live']['database'] = 'info2';
//
        $active_group = 'default';
//
        $base_url = "http://".$_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
//
        $connect = mysql_connect($db[$active_group]['hostname'],$db[$active_group]['username'],$db[$active_group]['password']) or die ("Error: could not connect to database");
        $db = mysql_select_db($db[$active_group]['database']);
 //      
        $result = mysql_query($rsql) or die ('test');
        return $result;
        mysql_close($connect);
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
if (!$sortname) $sortname = 'last_update';
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
$where = "where user_id='".$_SESSION['user']."'";
if ($query) {
    //$ss="insert into m001 (user_id,item_name,item_description) values ('0','".$query."','".$qtype."')";			
    //$res= runSQL($ss);
    $where = " WHERE $qtype like '%$query%' AND user_id='".$_SESSION['user']."' ";    
}
//$sql = "SELECT iso,name,printable_name,iso3,numcode FROM country $where $sort $limit";
$item_table= "m001"; // select default table name
$sql = "SELECT item_name,item_code,code_status,date,last_update FROM ".$item_table." $where $sort $limit"; // check of items belong to current user
// 
$result = runSQL($sql);
//
$total = countRec('item_name',$item_table,$where);
$rows = array();
while ($row = mysql_fetch_array($result)) {
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
        $xml .= "<row id='".$row['item_code']."'>";
        $xml .= "<cell><![CDATA[".$row['item_name']."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['item_code'])."]]></cell>";
        //$xml .= "<cell><![CDATA[".print_r($_POST,true)."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['code_status'])."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['date'])."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['last_update'])."]]></cell>";
        $xml .= "</row>";
}
$xml .= "</rows>";
echo $xml;
?>