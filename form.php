<?php
$base_url = "http://localhost/goodindia_badindia/services/";
?>

<h1><u>Facebook Login/Signup</u></h1>
<b><?php echo $base_url; ?>user/facebooklogin</b>
<form method="POST" action="<?php echo $base_url; ?>user/facebooklogin">
	<p>usr_name*: <input type="text" value="" name="usr_name"/></p>
	<p>usr_udid_number*: <input type="text" value="" name="usr_udid_number"/></p>
	<p>usr_gcm_number*: <input type="text" value="" name="usr_gcm_number"/></p>
	<p>usr_lgn_type*: <input type="text" value="" name="usr_lgn_type"/>[0=>Ios,1=>Android]</p>
	<p>usr_dob: <input type="text" value="" name="usr_dob"/></p>
	<p>usr_gender: <input type="radio" name="usr_gender" value="0" checked> Male<br>
	<input type="radio" name="usr_gender" value="1"> Female<br>[0=>Male,1=>Female]</p>
	<p>usr_image: <input type="text" value="" name="usr_image"/></p>	
		
	<p>lgn_fb_auth_id*: <input type="text" value="" name="lgn_fb_auth_id"/></p>
	<p>lgn_email*: <input type="text" value="" name="lgn_email"/></p>
	
	<?php		
		//$facebookDataArray = array("name"=>"Test","image"=>"Test_image");
		$facebookDataArray = array(
			'email'=>'kalpitgajera3@gmail.com',
			'id'=>'947293911982089',
			'name'=>'Kalpit Gajera',
			'picture'=>array(
				'data'=>array(
					'is_silhouette' => 0,
					'url' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfa1/v/t1.0-1/p50x50/12509214_1116894525022026_7734419410525145301_n.jpg?oh=7bf4a47e432800468427ee5ad8316cd3&oe=5783F090&__gda__=1467247638_d6c8e76ef1c6e3927678c9e1e97a645c',
				),
			),			
		);
    ?>
	fr_response *:<textarea name="fr_response"><?php echo json_encode($facebookDataArray); ?></textarea>
    <p><input type="submit" value="Facebook Login" name="signup"/></p>
</form>
<br/>
<br/>

<h1><u>Google Login/Signup</u></h1>
<b><?php echo $base_url; ?>user/googlelogin</b>
<form method="POST" action="<?php echo $base_url; ?>user/googlelogin">
	<p>usr_name: <input type="text" value="" name="usr_name"/></p>
	<p>usr_udid_number: <input type="text" value="" name="usr_udid_number"/></p>
	<p>usr_gcm_number: <input type="text" value="" name="usr_gcm_number"/></p>
	<p>usr_lgn_type: <input type="text" value="" name="usr_lgn_type"/>[0=>Ios,1=>Android]</p>
	<p>usr_dob: <input type="text" value="" name="usr_dob"/></p>
	<p>usr_gender: <input type="radio" name="usr_gender" value="0" checked> Male<br>
	<input type="radio" name="usr_gender" value="1"> Female<br>[0=>Male,1=>Female]</p>
	<p>usr_image: <input type="text" value="" name="usr_image"/></p>	
	<p>lgn_google_auth_id: <input type="text" value="" name="lgn_google_auth_id"/></p>
	<p>lgn_email: <input type="text" value="" name="lgn_email"/></p>
	<p><input type="submit" value="Google Login" name="signup"/></p>
</form>
<br/>
<br/>

<h1><u>All Post</u></h1>
<b><?php echo $base_url; ?>user/postlist</b>
<form method="POST" action="<?php echo $base_url; ?>user/postlist">
	<p>post_cat_id: <input type="text" value="" name="post_cat_id"/></p>
	<p>pageno: <input value="" name="pageno" type="text"></p>
	<p><input type="submit" value="All-Post" name="All Post"/></p>
</form>
<br/>
<br/>

<h1><u>Like/Unlike Post</u></h1>
<b><?php echo $base_url; ?>user/likeunlikepost</b>
<form method="POST" action="<?php echo $base_url; ?>user/likeunlikepost">
	<p>like_lgn_id: <input type="text" value="" name="like_lgn_id"/></p>
	<p>like_post_id: <input value="" name="like_post_id" type="text"></p>
	<p>like_type: <input value="" name="like_type" type="text">[1=>Good,2=>Bad]</p>
	<p><input type="submit" value="Like-Unlike Post" name="Like-Unlike Post"/></p>
</form>
<br/>
<br/>

<h1><u>My Post</u></h1>
<b><?php echo $base_url; ?>user/mypost</b>
<form method="POST" action="<?php echo $base_url; ?>user/mypost">
	<p>post_lgn_id: <input type="text" value="" name="post_lgn_id"/></p>
	<p>filtering post: <input type="text" value="" name="post_type"/></p>[all,good,bad]
	<p>post_latitude: <input value="" name="post_lattitude" type="text"></p>
	<p>post_longitude: <input value="" name="post_longitude" type="text"></p>
	<p>pageno: <input value="" name="pageno" type="text"></p>
	<p><input type="submit" value="My Post" name="My Post"/></p>
</form>
<br/>
<br/>

<h1><u>Add Post</u></h1>
<b><?php echo $base_url; ?>user/addpost</b>
<form method="POST" action="<?php echo $base_url; ?>user/addpost" enctype="multipart/form-data">
	<p>post_image: <input type="file" class="file-pos" id="post_image" name="post_image"></p>	
	<p>post_cat_id: <input type="text" value="" name="post_cat_id"/></p>
	<p>post_lgn_id: <input value="" name="post_lgn_id" type="text"></p>
	<p>post_title: <input type="text" value="" name="post_title"/></p>
	<p>post_desc: <input value="" name="post_desc" type="text"></p>
	<p>post_location: <input value="" name="post_location" type="text"></p>
	<p>post_latitude: <input value="" name="post_lattitude" type="text"></p>
	<p>post_longitude: <input value="" name="post_longitude" type="text"></p>
	<p>post_type: <input value="" name="post_type" type="text">[1=>Good,2=>Bad]</p>
	<p>post_img_status: <input value="" name="post_img_status" type="text">[1=>Image,2=>Video]</p>
	<p><input type="submit" value="Add Post" name="Add Post"/></p>
</form>
<br/>
<br/>

<h1><u>Add Category</u></h1>
<b><?php echo $base_url; ?>user/categorylist</b>
<form method="POST" action="<?php echo $base_url; ?>user/categorylist">
	<p><input type="submit" value="Add Category" name="Add Category"/></p>
</form>
<br/>
<br/>

<h1><u>Add Comment</u></h1>
<b><?php echo $base_url; ?>user/addcomment</b>
<form method="POST" action="<?php echo $base_url; ?>user/addcomment">
	<p>cmt_lgn_id: <input value="" name="cmt_lgn_id" type="text"></p>
	<p>cmt_post_id: <input value="" name="cmt_post_id" type="text"></p>
	<p>cmt_msg: <input value="" name="cmt_msg" type="text"></p>
	<p>cmt_type: <input value="" name="cmt_type" type="text">[0=>Parent,1=>Child(reply)]</p>
	<p>cmt_ref: <input value="" name="cmt_ref" type="text"></p>
	<p><input type="submit" value="Add Comment" name="Add Comment"/></p>
</form>
<br/>
<br/>

<h1><u>Comment List</u></h1>
<b><?php echo $base_url; ?>user/commentlist</b>
<form method="POST" action="<?php echo $base_url; ?>user/commentlist">
	<p>post_id: <input value="" name="post_id" type="text"></p>
	<p>pageno: <input value="" name="pageno" type="text"></p>
	<p><input type="submit" value="Comment List" name="Comment List"/></p>
</form>
<br/>
<br/>

<h1><u>Comment Reply List</u></h1>
<b><?php echo $base_url; ?>user/commentreplylist</b>
<form method="POST" action="<?php echo $base_url; ?>user/commentreplylist">
	<p>cmt_ref: <input value="" name="cmt_ref" type="text">[commentID]</p>
	<p>pageno: <input value="" name="pageno" type="text"></p>
	<p><input type="submit" value="Comment List" name="Comment List"/></p>
</form>
<br/>
<br/>

<h1><u>User Profile</u></h1>
<b><?php echo $base_url; ?>user/userprofile</b>
<form method="POST" action="<?php echo $base_url; ?>user/userprofile">
	<p>usr_lgn_id: <input value="" name="usr_lgn_id" type="text"></p>
	<p><input type="submit" value="Update Profile" name="Update Profile"/></p>
</form>
<br/>
<br/>

<h1><u>Post Detail</u></h1>
<b><?php echo $base_url; ?>user/postdetail</b>
<form method="POST" action="<?php echo $base_url; ?>user/postdetail">
	<p>post_id: <input type="text" value="" name="post_id"/></p>
	<p><input type="submit" value="Submit" name="Submit"/></p>
</form>
<br/>
<br/>

<h1><u>Like/Unlike Comment</u></h1>
<b><?php echo $base_url; ?>user/postcommentlikecount</b>
<form method="POST" action="<?php echo $base_url; ?>user/postcommentlikecount" >
	<p>like_lgn_id: <input value="" name="like_lgn_id" type="text"></p>
	<p>like_cmt_id: <input value="" name="like_cmt_id" type="text"></p>
	<p>like_post_id: <input value="" name="like_post_id" type="text"></p>
	<p>like_type: <input value="" name="like_type" type="text">[1=>Good,2=>Bad]</p>
	<p><input type="submit" value="like Comment" name="like Comment"/></p>
</form>
<br/>
<br/>

<h1><u>Location List</u></h1>
<b><?php echo $base_url; ?>user/locationlist</b>
<form method="POST" action="<?php echo $base_url; ?>user/locationlist">
	<p><input type="submit" value="Location" name=" Location"/></p>
</form>
<br/>
<br/>
<h1><u>Edit User Profile</u></h1>
<b><?php echo $base_url; ?>user/edituserprofile</b>
<form method="POST" action="<?php echo $base_url; ?>user/edituserprofile" enctype="multipart/form-data">
	<p>usr_lgn_id: <input value="" name="usr_lgn_id" type="text"></p>
	<p>usr_dob: <input value="" name="usr_dob" type="text"></p>
	<p>usr_gender: <input type="radio" name="usr_gender" value="0" checked> Male<br>
	<input type="radio" name="usr_gender" value="1"> Female<br></p>
	
	<p>usr_update_img: <input type="file" class="file-pos" id="usr_update_img" name="usr_update_img"></p>
	<p><input type="submit" value="Edit Profile" name=" Edit Profile"/></p>
</form>

<h1><u>ReportType List</u></h1>
<b><?php echo $base_url; ?>user/reporttypelist</b>
<form method="POST" action="<?php echo $base_url; ?>user/reporttypelist">
	<p><input type="submit" value="ReportType" name=" ReportType"/></p>
</form>
<br/>
<br/>

<h1><u>Add Report Abuse</u></h1>
<b><?php echo $base_url; ?>user/addreportabuse</b>
<form method="POST" action="<?php echo $base_url; ?>user/addreportabuse" >
	<p>report_type: <input value="" name="report_type" type="text">[1=>Illegal,2=>Porn]</p>
	<p>report_user_id: <input value="" name="report_user_id" type="text">[user Id]</p>
	<p>report_post_id: <input value="" name="report_post_id" type="text">[Post Id]</p>
	<p>report_comment: <input value="" name="report_comment" type="text"></p>
	<p><input type="submit" value="Report Abuse" name="Report Abuse"/></p>
</form>
<br/>
<br/>