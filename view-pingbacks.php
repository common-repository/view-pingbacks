<?php
/*
	Plugin Name: View Pingbacks
	Plugin URI: http://www.nohatlabs.com/indexspy-wp-released/
	Description: This plugin allows us to generate a list of pingbacks in delimited text format so we can use them for miscellaneous purpouses. 
	Version: 1.0.1
	Author: Hudson Atwell
*/

function vp_get_string_between($string, $start, $end) 
{

	if (strstr($start,'%wildcard%'))
	{
		$start = str_replace("%wildcard%", ".*?", preg_quote($start, "/"));
	}
	else
	{
		$start = preg_quote($start, "/");
	}
	
	if (strstr($end,'%wildcard%'))
	{
		$end = str_replace("%wildcard%", ".*?", preg_quote($end, "/"));
	}
	else
	{
		//echo $end;exit;
		$end = preg_quote($end, "/");
		//echo $end; exit;
	}
	
    $regex = "/{$start}(.*?){$end}/si";
	//echo $regex; 

	
    if (preg_match($regex, $string, $matches))
        return $matches[1];
    else
		//echo "<hr>";
		//echo $string; 
		//echo "<hr>";
		//echo $regex; 
		//echo "<hr>";
		//print_r($matches);
		//exit;
        return false;
}

function vp_quick_curl($url,$parameters)
{	
	global $proxy_array;
	global $proxy_type;
	
	$agents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0)";
	$agents[] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
	$agents[] = "Opera/9.63 (Windows NT 6.0; U; ru) Presto/2.1.1";
	$agents[] = "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5?";
	$agents[] = "Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.18) Gecko/20081203 Firefox/2.0.0.18";
	$agents[] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.16) Gecko/20080702 Firefox/2.0.0.16";
	$agents[] = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6; en-us) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/3.2.1 Safari/525.27.1";
	 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
	curl_setopt($ch, CURLOPT_TIMEOUT ,20);
	curl_setopt($ch, CURLOPT_USERAGENT, $agents[rand(0,(count($agents)-1))]);	
	
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$data = curl_exec($ch);
	//echo "<hr>$data";

	//echo $data; exit;
	curl_close($ch);
	return $data;
}

function vp_prepare_tags($description,$tags_nature)
{  
  //echo $tags_min; exit;
  if ($tags_nature ==1)
  {
		
		//echo 1; exit;
		$num_tags = rand($tags_min,$tags_max);
		$url = "http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction";
		$parameters = array('appid'=>'ZAlzNRjV34H56QbVJk7fRvu_yAP8bYHxG9Q77nNjaDsj9aelNCiTlo2bGiO_m2do1ic-', 'context'=>$description );
		$result = vp_quick_curl($url,$parameters);
		//echo $result; exit;
		$result = vp_get_string_between($result, '<Result>','</Result>');		
  }
  else 
  {

		$trash ="comparable,how,replaces,remove,part,duty,world,an,get,longer,stock,met,seen,content,can\'t,can,plus,got,go,no,review,added,new,we,all,check,our,be,hire,night,file,incredible,list,mostly,finally,detail,|,of,add,minus,subtract,table,about,above,acid,across,actually,after,again,against,almost,already,also,alter,although,always,among,angry,another,anyway,appropriate,around,automatic,available,awake,aware,away,back,basic,beautiful,because,been,before,being,bent,better,between,bitter,black,blue,boiling,both,bright,broken,brown,came,cause,central,certain,certainly,cheap,chemical,chief,clean,clear,clearly,close,cold,come,common,complete,complex,concerned,conscious,could,cruel,current,dark,dead,dear,deep,delicate,dependent,different,difficult,dirty,down,each,early,east,easy,economic,either,elastic,electric,else,enough,equal,especially,even,ever,every,exactly,feeble,female,fertile,final,finalty,financial,fine,first,fixed,flat,following,foolish,foreign,form,former,forward,free,frequent,from,full,further,future,general,generality,give,good,great,green,grey,gray,half,hanging,happy,hard,have,healthy,heavy,help,here,high,himself,hollow,home,however,human,important,indeed,individual,industrial,instead,international,into,just,keep,kind,labor,large,last,late,later,least,left,legal,less,like,likely,line,little,living,local,long,loose,loud,main,major,make,male,many,married,material,maybe,mean,medical,might,military,mixed,modern,more,most,much,must,name,narrow,national,natural,near,nearly,necessary,never,next,nice,normal,north,obviously,often,okay,once,only,open,opposite,original,other,over,parallel,particular,particularly,past,perhaps,personal,physical,please,political,poor,popular,possible,present,previous,prime,private,probable,probably,professional,public,quick,quickly,quiet,quite,rather,ready,real,really,recent,recently,regular,responsible,right,rough,round,royal,safe,said,same,second,secret,seem,send,separate,serious,several,shall,sharp,short,should,shut,significant,similar,simple,simply,since,single,slow,small,smooth,social,soft,solid,some,sometimes,soon,sorry,south,special,specific,sticky,stiff,still,straight,strange,strong,successful,such,sudden,suddenly,sure,sweet,take,tall,than,that,their,them,then,there,therefore,these,they,thick,thin,think,this,those,though,through,thus,tight,till,tired,today,together,tomorrow,total,turn,under,unless,until,upon,used,useful,usually,various,very,violent,waiting,warm,well,were,west,what,whatever,when,where,whether,which,while,white,whole,whose,wide,will,wise,with,within,without,would,wrong,yeah,yellow,yesterday,young,your,anyone,builds,tried,after,before,when,while,since,until,although,though,even,while,if,unless,only,case,that,this,because,since,now,as,in,on,around,to,I,he,she,it,they,them,both,either,and,top,most,best,&,inside,for,their,from,one,two,three,four,five,six,seven,eight,nine,ten,1,2,3,4,5,6,7,8,9,0,user,inc,is,isn\'t,are,aren\'t,do,don\'t,does,anyone,really,too,over,under,into,the,a,an,my,mine,against,inbetween,me,was,you,with,your,will,win,by";
		$trash = explode(",", $trash);
	
		$replace =array('-','|','&','*','%','$','#','@','~','/','amp;','.',';',':','?','!','"','(',')','[',']',',','+','?','”');
		
		$description = str_replace($replace , "",$description);
		$description = preg_replace('/[^A-Za-z]/', ' ', $description);
		foreach ($trash as $k=>$v)
		{
			//$v = preg_quote($v);
			$description = preg_replace("/\b{$v}\b/i", '', $description);
			//echo $description."||$v <br>";
		}		

		$result = $description;
		//echo $result;exit;
		
   }
   //echo $result;exit;
   return  $result;
}

/*----------------------------------------------------------------------------------------------------
									A D M I N     M E N U
----------------------------------------------------------------------------------------------------*/
function vpb_add_menu() {
	add_options_page('View Pingbacks', 'View Pingbacks', 8, 'View-Pingbacks', 'vpb_options_display');
}

add_action('admin_menu', 'vpb_add_menu');

function vpb_options_display()
{
	global $wpdb;
	//getting option/settings from Google XML Sitemaps
	$query = "SELECT * FROM ".$wpdb->prefix."comments WHERE comment_type='pingback'";
	$result = mysql_query($query);
	if (!$result){echo $query; echo mysql_error();}
	$count = mysql_num_rows($result); 
	while ($arr = mysql_fetch_array($result))
	{
		$query2 = "SELECT post_title,guid FROM ".$wpdb->prefix."posts WHERE ID='{$arr['comment_post_ID']}'";
		$result2 = mysql_query($query2);
		$arr2 = mysql_fetch_array($result2);
		
		$post_titles[] = $arr2['post_title'];
		$tags_yahoo[] = vp_prepare_tags($arr2['post_title'],1);
		$tags_title[] = vp_prepare_tags($arr2['post_title'],2);
		$tags_remote[] = vp_prepare_tags($arr['comment_author'],1);
		$guids[] = $arr2['guid'];
		$post_ids[] = $arr['comment_post_ID'];
		$remote_anchors[] = $arr['comment_author'];
		$pingbacks[] = $arr['comment_author_url'];
		
	}
		
	if($count==0): ?>
	
		<div class="wrap">
			<h2>View Pingbacks</h2>
			<div id="message" class="updated fade"><p class="">No Pingbacks!</p></div>
		</div>
		<?php exit; ?>
	
	<?php else: ?>
		<div  style='font-size:10px;text-align:left;'>
			<h2>View Pingbacks</h2>
						    

				<br /><br />
				<font style='font-size:12px;'><i>Post ID|Post Title|Shortend Title of Post|Yahoo Generated Tag|Pingback URL|Remote Anchor|Remote Anchor Tag</i></font>
				<hr>
				<pre><?php
				foreach ($pingbacks as $key=>$val)
				{
					echo "<a target=_blank href='{$guids[$key]}'>{$post_ids[$key]}</a>|{$post_titles[$key]}|{$tags_yahoo[$key]}|{$tags_title[$key]}|{$pingbacks[$key]}|{$remote_anchors[$key]}|{$tags_remote[$key]} <br>";
				}					
				?>
				</pre>
		</div>
	
	<?php endif; ?>
<?php
	
}

?>