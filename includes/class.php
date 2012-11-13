<?php
//******************************************* OLAF Class*****
include('fpdf.php');
include ('makefont/makefont.php');
if (file_exists("../phpqrcode/qrlib.php")) include "../phpqrcode/qrlib.php";
else include "phpqrcode/qrlib.php";
//include ('Simple_Image.php');
class info_registration {

//// registration data array ///
var $info = array (
 "name",
 "surename",
 "gender",
 "address",
 "city",
 "country",
 "activated",
 "email",
 "phone",
 "username",
 "password",
 "login_id"
);
var $language= array(
    1 => array (1 =>"english",    2 => "ltr.css",   3=> "English", 4=> "utf-8"),
    2 => array (1 =>"arabic",     2 => "rtl.css",   3=> "عربي", 4=> "utf-8"),
    3 => array (1 =>"italian",    2 => "ltr.css",   3=> "Italian", 4=> "utf-8"),
    4 => array (1 =>"romanian",   2 => "ltr.css",   3=> "Română", 4=> "utf-8"),
    5 => array (1 =>"french",     2 => "ltr.css",   3=> "Français", 4=> "utf-8"),
    6 => array (1 =>"portuguese", 2 => "ltr.css",   3=> "Português", 4=> "utf-8"),
    7 => array (1 =>"spanish",    2 => "ltr.css",   3=> "Español", 4=> "utf-8"),
    8 => array (1 =>"chinese",    2 => "ltr.css",   3=> "中文", 4=> "utf-8")
    );

var $report;
var $item_table="m001";
// generate random code and save it if it is not exists 
function generate_code($id)
    {
        $characters = 7;
        $possible_letters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $x = 0;
        while ($x <= 99000000)
            { 
                $code ='';
                $i = 0;
                while ($i < $characters)
                    { 
                        $code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
                        $i++;
                    }
                $query = "SELECT * FROM ".$this->item_table." where item_code='".$code."'";  
                $result = mysql_query($query) ;
                $row=mysql_fetch_array($result);
                if ($row)   {$x++; continue;}
                else break;
            }
            if ($x <= 99000000)
                {
                    $query="update ".$this->item_table." SET item_code='".$code."' where item_id='".$id."'";
                    $result= mysql_query($query);
                    if (!($result)) {
                        $this->report='Invalid_query';
                        return '0';
                        }
                    return $code;
                }
            if ($x >= 100000000)
                {
                    $this->report='no_code_space';
                    return '2';
                }
    }
/// qr code generator     
function qr_generator($code,$path)
    {
        $errorCorrectionLevel ='L';
        $matrixPointSize = 10;
        $data="onlaf.org/index.php?r=1&c=".$code;        
        $filename = $path.'/QR'.$code.'.png';
        // make QR
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        //echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';
        return 1;
    }
function get_image_path($item_code)
    {
        $query = "SELECT * FROM ".$this->item_table." where item_code='".$item_code."'";  
        $result = mysql_query($query) ;
        $row=mysql_fetch_array($result);
        //$abstract_code=$row['item_id'];
        //$code_length=strlen($abstract_code);
        //for ($i=$code_length;$i<10;$i++)
        //    {
        //    $abstract_code='0'.$abstract_code;
        //    } // fills the $abstract_code with zeros to the left to make its length uniform with 10 digits if it is less then 10
        //  
        //$abstract_code=$this->get_id_10digits($row['item_id']);
        $abstract_code=$row['item_id'];
        $code_length=strlen($abstract_code);
        for ($i=$code_length;$i<10;$i++)
            {
                $abstract_code='0'.$abstract_code;
            } // fills the $abstract_code with zeros to the left to make its length uniform with 10 digits if it is less then 10
        $level['1']=substr($abstract_code, -10, 2);    
        $level['2']=substr($abstract_code, -8, 2);    
        $level['3']=substr($abstract_code, -6, 2);    
        // this to generate image and QR code path
        $image_folder_path='../images/'.$this->item_table.'/'.$level['1'].'/'.$level['2'].'/'.$level['3'].'/';
        return $image_folder_path;
    }
function check_city_in_country($country,$city){
    $query = "SELECT * FROM country where printable_name='".$country."'";  
    $result = mysql_query($query) ;
    $row=mysql_fetch_array($result);
    $query = "SELECT * FROM city where CountryCode='".$row['Code']."' AND Name='".utf8_decode($city)."'";  
    $result = mysql_query($query) ;
    if (mysql_fetch_array($result)) return 1;
    else return 0;
}

function delete_image($image_path,$id){
    if($image_path=='../images/no_image.png') {return 0;}
    unlink($image_path);
    //$item_table="m001";
    $image_url='../images/no_image.png';
    $query="update ".$this->item_table." SET item_image='".$image_url."' where item_code='".$id."'";
    $result= mysql_query($query);
    if (!($result)) {
	$this->report='Invalid_query';
        return 0;
    }
    return 1;
}

//function get_id_10digits($id)
//{
 //   $abstract_code=$id;
  //  $code_length=strlen($abstract_code);
  //  for ($i=$code_length;$i<10;$i++)
  //      {
 //           $abstract_code='0'.$abstract_code;
  //      } // fills the $abstract_code with zeros to the left to make its length uniform with 10 digits if it is less then 10
 //   return $abstract_code;
//}
function update_image($_FILES,$image_path,$id)
    {
        $row=$this->get_item_id($id);
        $code_7=$row['item_id'];
        $new_image=0;
        //$item_table='m001';
        $image = new Simple_Image();
        // if there is no image
        if($image_path=='../images/no_image.png') // if there is no image allocated to item before
            { 
                $new_image=1; // x=1 means that there was no image before, i.e.,  uploaded image is new image for item
                //
                //get item id in the form of 10 digits
                //$abstract_code=$this->get_id_10digits($id);
                //$level['1']=substr($abstract_code, -10, 2);// level 1 directory, first two digits    
                //$level['2']=substr($abstract_code, -8, 2);// level 2 directory, second two digits    
                //$level['3']=substr($abstract_code, -6, 2);// level 3 directory, third two digits    
                //$dir_path='../images/'.$this->item_table.'/'.$level['1'].'/'.$level['2'].'/'.$level['3'];// generate directory path of image
                $dir_path=$this->get_image_path($id);
                if (!file_exists($image_path)) // if there is no such directory, make it
                    {
                        if (!mkdir($image_path,3, true)) $dir_path='../images/'.$this->item_table.'/tmp/'; // make directory, if fail then make the path to be temp directory
                    }
           }
           // we have the path to store image
        if ($_FILES["file"]["error"] > 0) // in case if there is any error in file upload
            {
                $this->report="error_image";
                return 0;
            }
        // checks image file format
        if (    ($_FILES["file"]["type"] == "image/gif")
            ||  ($_FILES["file"]["type"] == "image/jpeg")
            ||  ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/png"))
            {
                $image_temp_path="../images/".$this->item_table."/tmp/".$_FILES["file"]["name"];// generaqte the full path and name to save uploaded image temporarily 
                move_uploaded_file($_FILES["file"]["tmp_name"],$image_temp_path); //php function: ,move temp loaded file to site temp folder
                $image->resize_to_height($image_temp_path, $image_temp_path, 300); // resize upladed image dimentions to the hight =300 
                $size=getimagesize($image_temp_path);
                if($size['0']>500) $image->crop($image_temp_path, $image_temp_path,0,0,500,300);
                $path_parts = pathinfo($image_temp_path);
                // if there was an image allocated to item before and there is no broblem in new uploaded image
                if ($new_image!=1)  
                    {
                        $image_url=$image_path; // set new image url to the same old one
                         unlink($image_path); // delete old photo if it exists
                    }
                else $image_url=$dir_path.'/'.$code_7.'.'.$path_parts['extension']; // generate the permanent path of image :: path/code.extention
                // save image after modification to permanent folder
                rename($image_temp_path,$image_url); // move image from temp location to permanent location
                // update data base
                $query="update ".$this->item_table." SET item_image='".$image_url."' where item_code='".$id."'";
                $result= mysql_query($query);
                if (!($result)) 
                    {
                        $this->report='Invalid_query';
                        return 0;
                    }
                return $_FILES;
            }
        else //if there is error in file format
            {
                $this->report="invalid_image_format";
                return 0;
            }
    }

/// Upload photo
function upload_image($_FILES){
$image = new Simple_Image();
if ($_FILES["file"]["error"] > 0)
    {
    $this->report="error_image";
    if ($_FILES["file"]["error"] == 1) $this->report='image_size_big';
    return 0;
    }
if (($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/png"))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    $this->report="error_image";
    return 0;
    }
  else
    {
     $image_path="../images/".$this->item_table."/tmp/" . $_FILES["file"]["name"];
    move_uploaded_file($_FILES["file"]["tmp_name"],$image_path);
    $image->resize_to_height($image_path, $image_path, 300);
    $size=getimagesize($image_path);
    if($size['0']>500) $image->crop($image_path, $image_path,0,0,500,300);
   //echo "<img src='". $image_path."'>";
    return $_FILES;
    }
  }
else
  {
  $this->report="invalid_image_format";
  return 0;
  }
}
/// initialize item data- Read from $_Post by trimming left & right spaces if exists //////////////////////////////////////////////
function init_item_data($_POST){
    $info["item_name"]=rtrim(ltrim($_POST["item_name"]));
    $info["item_description"]=rtrim(ltrim($_POST["item_description"]));
    return $info;
}
       
/// read user data based on login_id ///////
function read_data($login_id){
    $query = "SELECT * FROM users where user_id='".$login_id."'";  
    $result = mysql_query($query) ;
    $row = mysql_fetch_array($result);
    return $row;
}

/// insert item[name] and item[description] of current user to default items_table (M001) 
function insert_item_data($item,$files,$login_id){
    $query = "SELECT * FROM users where user_id='".$login_id."'";
    $result = mysql_query($query) ;
    if (!$result) {
        $this->report='Invalid_query';
        return 0;
        }
    $user= mysql_fetch_array($result);
    if (!$user) {
        $this->report="not_authorized";
        return 0;
        }
if ($user['tokens']==0) {
    $this->report="no_credit";
    return 0;
    }
    //$query = "SELECT * FROM country where printable_name='".$user['country']."'";  
    //$result = mysql_query($query) ;
    //$country= mysql_fetch_array($result);
    $datetime=$this->get_datetime(); // get current date & time 
    //$item_table='m001'; // set the name of item table, Note: now we are using single default table for inserting items informations "MOO1"
    $query="insert into ".$this->item_table."(user_id,item_name,item_description,code_status,date,last_update) values ('".$login_id."','".$item['item_name']."','".$item['item_description']."','active','".$datetime."','".$datetime."')";			
    $result= mysql_query($query);
    if (!($result)) {
	$this->report='Invalid_query';
        return 0;
    }
    $id=mysql_insert_id();
    $code_length=strlen($id);
    $code_7=$this->generate_code($id);
    if ($code_7=='0' || $code_7=='2'){
        $this->report='Invalid_query';
        $query="delete from ".$this->item_table." where item_id='".$id."'";		
        $result= mysql_query($query);
        return 0;
    }
    // ((((Future work)))), check if the id is greater than 10 digits
    // this means that you have more than 9,999,999,999 items inserted in same table !!
    // another table is required
    //
    // Generate the code based on item ID in the table
    //$code=$id;
    //for ($i=$code_length;$i<10;$i++)
    //    {
    //    $code='0'.$code;
    //    } // fills the code with zeros to the left to make its length uniform with 10 digits if it is less then 10
    //$abstract_code=$this->get_id_10digits($id);
    //$code=substr($code,0,5).'.'.substr($code,5,5);
    //$code=$this->item_table.'.'.$code;
    //manage uploaded photo
     //$level['1']=substr($abstract_code, -10, 2);    
     //$level['2']=substr($abstract_code, -8, 2);    
     //$level['3']=substr($abstract_code, -6, 2);    
     //$image_folder_path='../images/m001/'.$level['1'].'/'.$level['2'].'/'.$level['3'].'/';
     // this to generate image and QR code path
     $image_folder_path = $this->get_image_path($id);
     if (!file_exists($image_folder_path)) 
         {
            if (!mkdir($image_folder_path,3, true)) $image_folder_path='../images/'.$this->item_table.'/tmp/';
         }
    // generate the QR code and save
    $this->qr_generator($code_7,$image_folder_path);
    //if the is no file uploaded, take the url of no_image.png
    if ($files==0) $image_url='../images/no_image.png';
    else // else, if the is image uploaded, save it to image path
    {
     $path_parts = pathinfo('../images/'.$this->item_table.'/tmp/'.$files["file"]["name"]); // to get file extantion
     $image_url=$image_folder_path.$code_7.'.'.$path_parts['extension']; // get the full URL of image
     rename('../images/'.$this->item_table.'/tmp/'.$files["file"]["name"],$image_url); // move image from temp dir to permanent dir
     //unlink('../images/m001/'.$files["file"]["name"]);
    }
    $query="update ".$this->item_table." SET  item_image='".$image_url."' where item_id='".$id."'";
    $result= mysql_query($query);
    if (!($result)) {
	$this->report='Invalid_query';
        //return 2;
    }
    $tokens=$user['tokens']-1; // reduce user codes tokens by 1
    $query="update users SET tokens='".$tokens."'  where user_id='".$login_id."'"; 
    $result=mysql_query($query);
    if (!($result)) {
	$this->report='Invalid_query';
        // item added without reducing token, complete add and send notification to system admin
        $body="this item has been added without reducing user tokens, user id= ".$login_id.", item code= ".$code_7;
        mail("afnfun@yahoo.com","item added without reducing tokens", $body,"info@onlaf.org");
    }
    // generate pdf sticker from code
    //$this->generate_pdf($code);
    $this->report='item_added';
    return $code_7;       
}

/// generate codes labels in pdf file, 2 pages: one colored and other one black & white   
function generate_pdf($code,$lang){
    //if (file_exists('pdf/'.$code.'.pdf')) return 1; // don't generate if file allready exists
    $path=$this-> get_image_path($code);
    $qr_image=$path.'/QR'.$code.'.png';
    $size=array(20,50);
    $pdf = new FPDF('P','mm','A4');
    ///// page 1//////////////////////////////
    $pdf->AddPage();
    $pdf->SetTextColor(0,0,0);
    $pdf->SetAuthor("Online Lost and Found (www.onlaf.com), by Alshammari");
    /// sequare label size 1 ---------------------------
    $pdf->SetFont('Arial','',16);$pdf->SetXY(10,6);$pdf->Write(5,"Square Label -----------------------------------------------");
    $pdf->SetFont('Arial','',3.8);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_square_sub_'.$lang.'.png',133,14,18);
    $pdf->Image("../images/logo.jpg",133.3,14.5,9.25);
    $pdf->SetXY(143,20.45);
    $pdf->Write(5,$code);
    /// sequare label size 2
    $pdf->SetFont('Arial','',4.5);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_square_sub_'.$lang.'.png',107.5,14,22);
    $pdf->Image($qr_image,107.9,14.4,11.2);
    $pdf->SetXY(120,22.4);
    $pdf->Write(5,$code);
    /// sequare label size 3
    $pdf->SetFont('Arial','',6.0);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_square_sub_'.$lang.'.png',73,14,30);
    $pdf->Image($qr_image,73.4,14.5,15.25);
    $pdf->SetXY(90,26.5);
    $pdf->Write(5,$code);
    /// sequare label size 4
    $pdf->SetFont('Arial','',10);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_square_sub_'.$lang.'.png',20,14,50);
    $pdf->Image($qr_image,20.5,14.6,25.5);
    $pdf->SetXY(49,36.5);
    $pdf->Write(5,$code);
    /// Rectangular label size 1 ---------------------------
    $pdf->SetFont('Arial','',16);$pdf->SetXY(10,56);$pdf->Write(5,"Rectangular Label -----------------------------------------");
    $pdf->SetFont('Arial','',4.7);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_rectangulare_sub_'.$lang.'.png',173,65,28);
    $pdf->Image("../images/logo.jpg",173.3,65.4,10.45);
    $pdf->SetXY(189.5,71.3);
    $pdf->Write(5,$code);
    /// Rectangular label size 2
    $pdf->SetFont('Arial','',5.7);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_rectangulare_sub_'.$lang.'.png',136,65,34);
    $pdf->Image($qr_image,136.4,65.5,12.4);
    $pdf->SetXY(155.5,73.3);
    $pdf->Write(5,$code);
    /// Rectangular label size 3
    $pdf->SetFont('Arial','',9.8);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_rectangulare_sub_'.$lang.'.png',84.25,65,50);
    $pdf->Image($qr_image,84.8,65.5,19);
    $pdf->SetXY(113,78.2);
    $pdf->Write(5,$code);
    /// Rectangular label size 4
    $pdf->SetFont('Arial','',12.9);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_rectangulare_sub_'.$lang.'.png',10,65,70);
    $pdf->Image($qr_image,10.6,65.5,26.5);
    $pdf->SetXY(50,84.5);
    $pdf->Write(5,$code);
    /// Long rectangular label size 1 ---------------------------
    $pdf->SetFont('Arial','',16);$pdf->SetXY(10,96);$pdf->Write(5,"Long Rectangular Label ----------------------------------");
    $pdf->SetFont('Arial','',4.8);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_long_rectangulare_sub_'.$lang.'.png',170,105,28);
    $pdf->Image("../images/logo.jpg",170.3,105.3,6.1);
    $pdf->SetXY(183.5,106.8);
    $pdf->Write(5,$code);
    /// Long rectangular label size 2
    $pdf->SetFont('Arial','',5.3);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_long_rectangulare_sub_'.$lang.'.png',134.4,105,34);
    $pdf->Image("../images/logo.jpg",134.6,105.35,7.75);
    $pdf->SetXY(149.5,107.6);
    $pdf->Write(5,$code);
    /// Long rectangular label size 3
    $pdf->SetFont('Arial','',7.6);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_long_rectangulare_sub_'.$lang.'.png',82,105,50);
    $pdf->Image($qr_image,82.3,105.3,11.5);
    $pdf->SetXY(106.8,110.2);
    $pdf->Write(5,$code);
    /// Long rectangular label size 4
    $pdf->SetFont('Arial','',10.6);
    $pdf->Image('../images/sticker/'.$lang.'/sticker_long_rectangulare_sub_'.$lang.'.png',10,105,70);
    $pdf->Image($qr_image,10.4,105.5,16);
    $pdf->SetXY(42,113.4);
    $pdf->Write(5,$code);
    /// Medal label size 1 ------------------------------
    $pdf->SetFont('Arial','',16);$pdf->SetXY(10,146);$pdf->Write(5,"Medal Label -------------------------------------------------");
    $pdf->SetFont('Arial','',7);
    $pdf->Image('../images/sticker/'.$lang.'/medal_sub_'.$lang.'.png',175,155,20);
    $pdf->Image($qr_image,179,165,12);
    $pdf->SetXY(179.5,177);
    $pdf->Write(5,$code);
    /// Medal label size 2
    $pdf->SetFont('Arial','',10);
    $pdf->Image('../images/sticker/'.$lang.'/medal_sub_'.$lang.'.png',141,155,30);
    $pdf->Image($qr_image,146.4,168,20);
    $pdf->SetXY(148.5,189);
    $pdf->Write(5,$code);
    /// Medal label size 3
    $pdf->SetFont('Arial','',15);
    $pdf->Image('../images/sticker/'.$lang.'/medal_sub_'.$lang.'.png',85,155,50);
    $pdf->Image($qr_image,94,177.5,32);
    $pdf->SetXY(99,213);
    $pdf->Write(5,$code);
    /// Medal label size 4
    $pdf->SetFont('Arial','',19);
    $pdf->Image('../images/sticker/'.$lang.'/medal_sub_'.$lang.'.png',10,155,70);
    $pdf->Image($qr_image,24,187,42);
    $pdf->SetXY(30,236);
    $pdf->Write(5,$code);
    //////////////////////////////////////////
    /// output pdf file ---
    $pdf->Output('pdf/'.$code.'.pdf');
}

/// show country list in pulldown selection menue, used in registration page
function show_country_list(){
$query = "SELECT * FROM country ORDER BY printable_name ";  
$result = mysql_query($query) ;
if (!($result)) {
	$this->report='Invalid_query';
        return 0;
    }
while($row = mysql_fetch_array($result))
    {
    echo '<option value="'.$row["printable_name"].'">'.$row["printable_name"].'</option>';
    }
return 1;
}

/// show country list and select current country in pulldown selection menue, used in edit information page
function select_country_list($country){
$query = "SELECT * FROM country ORDER BY printable_name";  
$result = mysql_query($query);
if (!($result)) {
	$this->report='Invalid_query';
        return 0;
    }
    while($row = mysql_fetch_array($result))
        {
        echo '<option value="'.utf8_encode($row["printable_name"]).'"';if ($country==$row["printable_name"]) echo 'selected="selected"'; echo '>'.utf8_encode($row["printable_name"]).'</option>';
        }
return 1;
}

/// show country list and select current country in pulldown selection menue, used in edit information page
function select_current_city_list($country,$city){
if (!$this->check_city_in_country($country, $city)) $city='Other';
$query = "SELECT * FROM country where printable_name='".$country."'";  
$result = mysql_query($query) ;
$row=mysql_fetch_array($result);
$query = "SELECT Name FROM city where CountryCode='".$row['Code']."' ORDER BY Name";  
$result = mysql_query($query) ;
$i=0;
while($row_c = mysql_fetch_array($result))
        {
        $i++;
        if ($row2['Name']!='Other') echo '<option value="'.utf8_encode($row_c["Name"]).'"';if ( $city==utf8_encode($row_c["Name"]) ) echo 'selected="selected"'; echo '>'.utf8_encode($row_c["Name"]).'</option>';
        }
        if($i==1) echo '<option value="'.$country.'">'.$country.'</option>';
        echo '<option value="Other"';if ( $city=='Other' ) echo 'selected="selected"'; echo '>'.$_SESSION['other_city'].'</option>';
return 1;
}

/// show language list and select current language in pulldown selection menue, used in edit information page
function select_language_list($lang){
        foreach ($this->language as $i => $value) {
                      echo '<option value="'.$this->language[$i][1].'"'; if ($lang==$this->language[$i][1]) echo 'selected="selected"'; echo '>'.$this->language[$i][3].'</option>';
                     }
        return 1;
}


/// to update item based on given code
function update_item($_POST,$code){
        //$item_table= "m001";
        $datetime=$this->get_datetime(); // get current date & time 
        $query="update ".$this->item_table." SET item_name='".$_POST['item_name']."',item_description='".$_POST['item_description']."',last_update='".$datetime."'  where item_code='".$code."'";
	$result=mysql_query($query);
	if (!($result)) 
            {
            $this->report='Invalid_query';
            return 0;
            }
        return 1;
}


//// get item description from code
function get_item($code){
//$item_table= "m001";
$query = "SELECT * FROM ".$this->item_table." where item_code='".$code."'";
$result = mysql_query($query);
        if (!($result)) 
            {
            $this->report='Invalid_query';
            return 0;
            }
$row = mysql_fetch_array($result);
if (file_exists('pdf/'.$code.'.pdf')) unlink('pdf/'.$code.'.pdf'); // delete pdf file if exist
return $row;
}
///
//// get item description from code
function get_item_id($id){
//$item_table= "m001";
$query = "SELECT * FROM ".$this->item_table." where item_id='".$id."'";
$result = mysql_query($query);
        if (!($result)) 
            {
            $this->report='Invalid_query';
            return 0;
            }
$row = mysql_fetch_array($result);
//if (file_exists('pdf/'.$code.'.pdf')) unlink('pdf/'.$code.'.pdf'); // delete pdf file if exist
return $row;
}
///
//// get item description from code and user_id
function get_item_of_user($user_id,$code){
//$item_table= "m001";
$query = "SELECT * FROM ".$this->item_table." where user_id='".$user_id."' and item_code='".$code."'";
$result = mysql_query($query);
        if (!($result)) 
            {
            $this->report='Invalid_query';
            return 0;
            }
$row = mysql_fetch_array($result);
return $row;
}

/// track code given by anonymous user
function track_code($code){   
$code=strtolower($code);
$row=$this->get_item($code);
if ($this->check_status($row['user_id'])==2)
        {
        $this->report='blocked_owner';
        return false;    
        }
if (!$row) 
            {
            $this->report='invalid_code';
            return false;
            }

if($row['code_status']=='active')
        {
        //This part also related to code expiration propoerty which is not active now
        //$remain=$this->get_remaining_days($row['TTL'],$row['date']);
        //echo $remain;
        //if ($remain<360){$this->report='Entered code is already expired because it has been created more than a year before without being activated';return false;}
        $query = "SELECT * FROM users where user_id='".$row['user_id']."'";
        $result = mysql_query($query);
        if (!($result)) {
            $this->report='Invalid_query';
            return 0;
        }
        $user = mysql_fetch_array($result);
        $privacy=$this->get_privacy_flag($row['user_id']);
        return array_merge($row,$user,$privacy);
        }
if($row['code_status']!='active')
    {
    $this->report='invalid_code';
    return false;
    }                                
return 1;
}


/// to read user credit
function gettokens($login_id){
    $query = "SELECT tokens FROM users where user_id='".$login_id."'";
    $result = mysql_query($query);
    if (!($result)) 
        {
        $this->report='Invalid_query';
        return 0;
        }
    $user = mysql_fetch_array($result);
    return $user['tokens'];
}
// block add credit for specific user
function block_add_credit($login_id)
    {
        $datetime=$this->get_datetime();
        $query="update users SET Block_Add_Credit='1', Block_Add_Credit_date='".$datetime."' where user_id='".$login_id."'";
        $result=mysql_query($query);
         if (!($result)) {
                    $this->report='Invalid_query';
                    return 0;}
         $this->report="user_blocked_add_credit";
         return 1;  
}
/// check block_add_credit
function check_block_add_credit($login_id)
    {
        $query = "SELECT Block_Add_Credit,Block_Add_Credit_date FROM users where user_id='".$login_id."'";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
        $datetime=$this->get_datetime();
        $query="SELECT HOUR(TIMEDIFF('".$datetime."','".$row['Block_Add_Credit_date']."')), MINUTE(TIMEDIFF('".$datetime."','".$row['Block_Add_Credit_date']."'))";
        $result = mysql_query($query);
        $temp = mysql_fetch_array($result);
        $timedifference['hour']=$temp[0];
        $timedifference['minute']=$temp[1];
        if  ($timedifference['minute'] < 1 && $row['Block_Add_Credit']==1) return array_merge( $row,$timedifference);
        if  ($row['Block_Add_Credit']==0) return 0;
        if  ($timedifference['minute'] >= 1 && $row['Block_Add_Credit']==1) 
            {
            $query="update users SET Block_Add_Credit='0' where user_id='".$login_id."'";
            $result=mysql_query($query);
             return 0; 
            }       
    }

 // block reg code for specific user
function block_reg_code($login_id)
    {
        $datetime=$this->get_datetime();
        $query="update users SET Block_Reg_Code='1', Block_Reg_Code_date='".$datetime."' where user_id='".$login_id."'";
        $result=mysql_query($query);
         if (!($result)) {
                    $this->report='Invalid_query';
                    return 0;}
         return 1;  
}
/// check block_reg_code
function check_block_reg_code($login_id)
    {
        $query = "SELECT Block_Reg_Code,Block_Reg_Code_date FROM users where user_id='".$login_id."'";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
        $datetime=$this->get_datetime();
        $query="SELECT HOUR(TIMEDIFF('".$datetime."','".$row['Block_Reg_Code_date']."')), MINUTE(TIMEDIFF('".$datetime."','".$row['Block_Reg_Code_date']."'))";
        $result = mysql_query($query);
        $temp = mysql_fetch_array($result);
        $timedifference['hour']=$temp[0];
        $timedifference['minute']=$temp[1];
        if  ($timedifference['minute'] < 1 && $row['Block_Reg_Code']==1) return array_merge( $row,$timedifference);
        if  ($row['Block_Reg_Code']==0) return 0;
        if  ($timedifference['minute'] >= 1 && $row['Block_Reg_Code']==1) 
            {
            $query="update users SET Block_Reg_Code='0' where user_id='".$login_id."'";
            $result=mysql_query($query);
             return 0; 
            }       
    }
    
    
/// to process added credit code by user
function insert_token_code($token_code,$login_id){
    $query = "SELECT * FROM token_codes where token_code='".$token_code."' and status=1";
    $result = mysql_query($query);
    if (!($result)) 
        {
         $this->report='not_valid_token_code';
         return 2;
        }
    $code = mysql_fetch_array($result);    
    if (!$code)
        {
        $this->report="not_valid_token_code";return 2;
        }
    else 
        {
        $datetime=$this->get_datetime();
        $query="update token_codes SET usage_date='".$datetime."',user_id='".$login_id."' ,status='0' where token_code='".$token_code."'";
        $result=mysql_query($query);
        if (!($result)) 
            {
            $this->report='Invalid_query';
            return 0;
            }
        $query = "SELECT * FROM users where user_id='".$login_id."'";
        $result = mysql_query($query);
        if (!($result)) 
            {
            $this->report='Invalid_query';
            $query="update token_codes SET usage_date='0',user_id='0' ,status='1' where token_code='".$token_code."'";
            mysql_query($query);
            return 0;
            }
        $user = mysql_fetch_array($result); 
        $newtokens=$user['tokens']+$code['tokens'];
        $query="update users SET tokens='".$newtokens."' where user_id='".$login_id."'";
        $result=mysql_query($query);
        if (!($result)) 
            {
            $this->report='Invalid_query';
            $query="update token_codes SET usage_date='0',user_id='0' ,status='1' where token_code='".$token_code."'";
            mysql_query($query);
            return 0;
            }
        }
    $this->report='credit_added';
    return 1;
} 
// check field
function check_field($table,$field,$value){
    $query = "SELECT * FROM ".$table." where ".$field."='".$value."'";
    $result = mysql_query($query);
        if (!($result)) 
        {
         $this->report='Invalid_query';
          return 0;
        }
    $row = mysql_fetch_array($result);
    if ($row) return true; else return false;   
}


/// get user from email 
function get_user_by_email($email){
    $query = "SELECT * FROM users where email='".$email."'";
    $result = mysql_query($query);
        if (!($result)) 
        {
         $this->report='Invalid_query';
          return 0;
        }
    $row = mysql_fetch_array($result);
    return $row;   
}


/// get user from id
function get_user_by_id($login_id){
    $query = "SELECT * FROM users where user_id='".$login_id."'";
    $result = mysql_query($query);
        if (!($result)) 
        {
         $this->report='Invalid_query';
          return 0;
        }
    $row = mysql_fetch_array($result);
    return $row;   
}

/// to update code status
function update_code_status($code,$status){
    //$item_table= "m001";
    $query="update ".$this->item_table." SET code_status='".$status."'  where item_code='".$code."'";
    $result=mysql_query($query);
        if (!($result)) 
        {
         $this->report='Invalid_query';
         return 0;
        }
    $this->report="updated";    
    return 1;
}

/// to send move_code_ownership request 
function move_code_ownership($source_id,$email,$code){
    $item=$this->get_item($code);
    if ($item['code_status']!='active') 
        {
        $this->report='not_active';
        return 0;
        }
    $destination_user=$this->get_user_by_email($email);
    if (!$destination_user)
        {
        $this->report='invalid_user';
        return 0;
        }
    if ($destination_user['activated']!='1')
        {
        $this->report='blocked_user';
        return 0;
        }
    if ($destination_user['user_id']==$source_id) 
        {
        $this->report='yourself' ;
        return 0;
        }
    else
        {
        $this->update_code_status($code,'pending');
        $datetime=$this->get_datetime();
        $query="insert into message_board (source_user_id,destination_user_id,destination_email,code_transfer_time,code,code_transfer_confirmation) values ('".$source_id."','".$destination_user['user_id']."','".$email."','".$datetime."','".$code."','pending')";			
        $result= mysql_query($query);
        if (!($result))
            {
            $this->report='Invalid_query';
            return 0;
            }
        else 
            {
            $this->report='sent_request';
            return 1;
            }
    }   
    
}

/// get destination email from transfered code 
function get_transfer_record($code){
    $query = "SELECT * FROM message_board where code='".$code."' and code_transfer_confirmation='pending'";
    $result = mysql_query($query);
    if (!($result))
        {
        $this->report='Invalid_query';
        return 0;
        }     
    $row = mysql_fetch_array($result);
    return $row;   
}

//////////////////////// till here
/// to cancel move_code_ownership request 
function cancel_move_code_ownership($code){  
 $transfer=$this->get_transfer_record($code);
 $item=$this->get_item($code);
 if ($item['code_status']!='pending') {$this->report='no_right2';return 0;}
 $query="update message_board set code_transfer_confirmation='canceled' where code='".$code."' and code_transfer_confirmation='pending'";			
 $result= mysql_query($query);
 if (!($result)) {
	$this->report='Invalid_query';
	return 0;}
     else {
         $this->update_code_status($code,'active');
         $this->report='cancel_transfer' ;
         return 1;
         }        
         
}   
    

 /// return number of received codes
function get_number_of_received_codes($my_id){
    $query = "SELECT * FROM message_board where destination_user_id='".$my_id."' and code_transfer_confirmation='pending'";
    $result = mysql_query($query);
    $num_rows = mysql_num_rows($result);
    return $num_rows;   
} 
  
//// show all received items in received items menue
function show_received_items($my_id){ 
//$item_table= "m001";  
$query = "SELECT * FROM message_board where destination_user_id='".$my_id."' and code_transfer_confirmation='pending'";
$result = mysql_query($query);
if (!$result) return false; 
while($received= mysql_fetch_array($result)){
        $item=$this->get_item($received['code']);
        $sender=$this->get_user_by_id($received['source_user_id']);
        echo '<option value="'.$item["item_code"].'">'.$item["item_name"].' :  '.'from '.$sender["name"].' '.$sender["surename"].' at: '.$received["code_transfer_time"].'</option>';
        }
return true;

}


// confirm received code
function confirm_code_receive($code,$login_id){
 $query = "SELECT * FROM users where user_id='".$login_id."'";
    $result = mysql_query($query) ;
    if (!$result) {
        $this->report='Invalid_query';
        return 0;
        }
    $user= mysql_fetch_array($result);
    if (!$user) {
        echo ' <script> alert("You are not authorized to insert item"); location.replace("../index.php"); </script>';
        return 0;
        }
 $received=$this->get_transfer_record($code);
 $item=$this->get_item($code);
 if ($item['code_status']!='pending') {$this->report='no_right';return 0;}
 $query="update message_board set code_transfer_confirmation='accepted' where code='".$code."' and code_transfer_confirmation='pending'";			
 $result= mysql_query($query);
 if (!($result)) {
	$this->report='Invalid_query';
	return 0;}
     else {
         $this->update_code_ownership($received['destination_user_id'],$code,'active');
         $this->report='received_accepted' ;
         $tokens=$user['tokens']-1; // reduce user codes tokens by 1
         $query="update users SET tokens='".$tokens."'  where user_id='".$login_id."'"; 
         $result=mysql_query($query);
         if (!($result)) {
            $this->report='Invalid_query';
            return 0;
         }
         return 1;
         }              
}



function update_code_ownership($my_id,$code,$status){
     //$item_table= "m001";
     //$user=$this->get_user_by_email($email);
     $query="update ".$this->item_table." SET user_id='".$my_id."', code_status='".$status."'  where item_code='".$code."'";
     mysql_query($query);
     return 0;
}

/// check reveived codes vs credits
function check_received_codes_vs_tokens($my_id){
$query = "SELECT * FROM users where user_id='".$my_id."'";
    $result = mysql_query($query) ;
    if (!$result) {
        $this->report='Invalid_query';
        return 0;
        }
    $user= mysql_fetch_array($result);
$query = "SELECT * FROM message_board where destination_user_id='".$my_id."' and code_transfer_confirmation='pending'";
$res = mysql_query($query);
if (mysql_num_rows($res) > $user['tokens']) 
    {
    $this->report='no_enough_credits';
    return 2;
    }
return 1;             
}
/// to confirm all received codes
function confirm_code_receive_all($my_id){
$query = "SELECT * FROM users where user_id='".$my_id."'";
    $result = mysql_query($query) ;
    if (!$result) {
        $this->report='Invalid_query';
        return 0;
        }
    $user= mysql_fetch_array($result);
    if (!$user) {
        echo ' <script> alert("You are not authorized to insert item"); location.replace("../index.php"); </script>';
        return 0;
        }        
$tokens=$user['tokens'];
        $query = "SELECT * FROM message_board where destination_user_id='".$my_id."' and code_transfer_confirmation='pending'";
$res = mysql_query($query);
while($row = mysql_fetch_array($res)){
         //$received=$this->get_transfer_record($row['code']);
         $item=$this->get_item($row['code']);
         if ($item['code_status']!='pending') {$this->report='no_right';continue;}
         $query="update message_board set code_transfer_confirmation='accepted' where code='".$row['code']."' and code_transfer_confirmation='pending'";			
         $result= mysql_query($query);
         if (!($result)) {
                $this->report='Invalid_query';
                return 0;}
        $this->update_code_ownership($my_id,$row['code'],'active');
        $tokens=$tokens-1; // reduce user codes tokens by 1
         $query="update users SET tokens='".$tokens."'  where user_id='".$my_id."'"; 
         $result=mysql_query($query);
         if (!($result)) {
            $this->report='Invalid_query';
            return 0;
         }
        }
$this->report='accept_codes' ;
return 1;             
}


/// to cancel all_code_ownership request 
function cancel_move_code_ownership_all($my_id){  
$query = "SELECT * FROM message_board where destination_user_id='".$my_id."' and code_transfer_confirmation='pending'";
$res = mysql_query($query);
while($row = mysql_fetch_array($res)){
         //$transfer=$this->get_transfer_record($row['code']);
         $item=$this->get_item($row['code']);
         if ($item['code_status']!='pending') {$this->report='no_right2';continue;}
         $query="update message_board set code_transfer_confirmation='canceled' where code='".$row['code']."' and code_transfer_confirmation='pending'";			
         $result= mysql_query($query);
         if (!($result)) {
                $this->report='Invalid_query';
                return 0;}
         $this->update_code_status($row['code'],'active');
        }
$this->report='canceled_codes' ;
return 1; 
}
// this to return datetime in my sql format yyyy/mm/dd hh:mm:ss 
function get_datetime(){
    $date = getdate();
    $f_datetime=$date['year'].'/'.$date['mon'].'/'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':'.$date['seconds'];
    return $f_datetime;
}

// this function to generate and store privacy flag, 0001: publish name, 0010: publish surname, 0100: publish address, 1000: publish phone
// e.g., 1001: publish my name and phone when somebody search a code belong to me
function update_privacy_flag($code,$login_id){
     $row=$this->get_user_by_id($login_id);
     $flag=$row['privacy_flag'];
     $flag=$flag;
     $flag=$flag ^$code;
     $query="update users SET privacy_flag='".$flag."' where user_id='".$login_id."'";
     $result=mysql_query($query);
     if (!($result)) {
                $this->report='Invalid_query';
                return 0;}
     $this->report="privacy_updated";
     return 1;
}

/// get privacy flag array
function get_privacy_flag($login_id){
$query = "SELECT * FROM users where user_id='".$login_id."'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$privacy['pr_name']=$row['privacy_flag'] & 1;
$privacy['pr_email']=$row['privacy_flag'] & 2;
$privacy['pr_address']=$row['privacy_flag'] & 4;
$privacy['pr_phone']=$row['privacy_flag'] & 8;

return $privacy;   
}

function check_status($login_id){
$query = "SELECT * FROM users where user_id='".$login_id."'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$status=$row['activated'];

return $status;   
}
function change_status($login_id){
     $status=$this->check_status($login_id);
     if($status==1) $status=2;
     else $status=1;
     $query="update users SET activated='".$status."' where user_id='".$login_id."'";
     $result=mysql_query($query);
     if (!($result)) {
                $this->report='Invalid_query';
                return 0;}
     $this->report="status_changed";
     return 1;
}
function welcome_user(){
    global $user;
    global $reg;
    global $login_general;
    echo '       <b>'.$login_general['welcome']; echo $user->data['name']; 
                    $received= $reg->get_number_of_received_codes($user->data['user_id']);
    if ($received>0) 
        echo '      <font color=red>&nbsp;&nbsp;&nbsp;'.$login_general['rec1'].'&nbsp;'.$received.'&nbsp;'.$login_general['rec2'].'<a href="show_received.php">'.$login_general['rec3'].'</a>'. $login_general['rec4'].'
                    </font>';
        echo'   </b>
		<br/>
		<hr/>';  
}
/// end of class
}

