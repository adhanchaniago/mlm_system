<?php

namespace App\Http\Controllers\API;

use App\Models\favorite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\AppUsers;
use Mail;

/**
 * Class botController
 * @package App\Http\Controllers\API
 */

class userAPIController extends AppBaseController
{
    public function make_user(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        if (empty($name) || empty($email) || empty($password)) {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "All the fields are required";
            return $data;
        }
        $created_from = "samybot_app";
        if (AppUsers::where('email', $email)->exists()) {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "There is already an account with this email";
            return $data;
        }
        $Input = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_from' => $created_from,
            'activated' => 0,
	        'receive_email'=> true,
	        'status'=> "3"
        ];
        $make_user = AppUsers::create($Input);

//      laravel mail goes here
        if ($make_user) {
            $user = AppUsers::whereId($make_user->id)->first();
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 30; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $activationLink = $randomString . '_' . $user->id;
            $data1['email'] = $user->email;
            $data1['name'] = $user->name;
            $data1['activationLink'] = $activationLink;
            try{
                Mail::send('email.activationLink', ['data' => $data1], function ($message) use ($data1) {
                    $message->to($data1['email'], $data1['name'])->from(env('MAIL_USERNAME'), 'Samy bot')->subject('Activation link from Samy bot');
                });
            }
            catch (\Swift_TransportException $ex) {
                $data['success'] = true;
                $data['data'] = "";
                $data['message'] = "Something went wrong";
                return $data;
            }
            AppUsers::whereId($user->id)->update(['activation_hash' => $activationLink]);
	        $make_user = AppUsers::whereId($user->id)->first();
	        if($make_user->receive_email == "true"){
		        $make_user->receive_email = true;
	        }
	        else{
		        $make_user->receive_email = false;
	        }
	        if($make_user->receive_favorite == "true"){
		        $make_user->receive_favorite = true;
	        }
	        else{
		        $make_user->receive_favorite = false;
	        }
            $data['success'] = true;
            $data['data'] = $make_user;
            $data['message'] = "Thanks for signing up with Samy! Mail will be sent to your Email Id";
        } else {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "Something went wrong.";
        }
        return $data;
    }

    public function user_login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if ($email != "" || $password != "") {
            if (AppUsers::where('email', $email)->exists()) {
                $user = AppUsers::where('email', $email)->first();
                if (Hash::check($password, $user->password) && $email == $user->email) {
                    if ($user->activated == 1) {
                        $data['success'] = true;
	                    if($user->receive_email == "true"){
		                    $user->receive_email = true;
	                    }
	                    else{
		                    $user->receive_email = false;
	                    }
	                    if($user->receive_favorite == "true"){
		                    $user->receive_favorite = true;
	                    }
	                    else{
		                    $user->receive_favorite = false;
	                    }
                        $data['data'] = $user;
                        $data['message'] = "OK";
                    } else {
                        $data['success'] = true;
                        $data['data'] = $user;
                        $data['message'] = "we sent you and activation email with a link to activate your account. If you didn't receive it, please click: RESEND";
                        $data['url'] = url('api/resendMail') . '/' . $user->id;
                    }
                } else {
                    $data['success'] = false;;
                    $data['data'] = "";
                    $data['message'] = "Wrong Combination of Email and Password";
                }
            } else {
                $data['success'] = false;;
                $data['data'] = "";
                $data['message'] = "No account with this email/password";
            }
        } else {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "Both Fields are Required.";
        }
        return $data;
    }
	public function get_user(Request $request){
    	$user_id=$request->user_id;
    	if(AppUsers::where('id', $user_id)->exists()){
		    $user = AppUsers::where('id', $user_id)->first();
		    if ($user->activated == 1) {
			    $data['success'] = true;
			    if($user->receive_email == "true"){
				    $user->receive_email = true;
			    }
			    else{
				    $user->receive_email = false;
			    }
			    if($user->receive_favorite == "true"){
				    $user->receive_favorite = true;
			    }
			    else{
				    $user->receive_favorite = false;
			    }
			    $user['user_image'] = asset('public/avatars'). '/' . $user->photo;
			    $data['data'] = $user;
			    $data['message'] = "OK";
		    } else {
			    $data['success'] = true;
			    $data['data'] = $user;
			    $data['message'] = "we sent you and activation email with a link to activate your account. If you didn't receive it, please click: RESEND";
			    $data['url'] = url('api/resendMail') . '/' . $user->id;
		    }
	    }
	    else{
		    $data['success'] = false;;
		    $data['data'] = "";
		    $data['message'] = "User not exists.";
	    }
		return $data;
	}
    public function activate_users($link)
    {
        $id = substr($link, strpos($link, "_") + 1);
        if (AppUsers::whereId($id)->exists()) {
            $user = AppUsers::whereId($id)->first();
            if ($user->activation_hash == $link) {
                $update['activated'] = 1;  //Updating activated column to true
                $update['activation_hash'] = ""; //emptying activation_hash column
                AppUsers::whereId($id)->update($update);
                return redirect('home');
            } else {
                $data['success'] = false;;
                $data['data'] = "";
                $data['message'] = "Token expired.Try Again";
            }
        } else {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "Something went wrong.Try Again";
        }
        return $data;
    }

    public function resendMail($id)
    {
        $user = AppUsers::whereId($id)->first();
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 30; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $activationLink = $randomString . '_' . $user->id;
        $data1['email'] = $user->email;
        $data1['name'] = $user->name;
        $data1['activationLink'] = $activationLink;
        try{
            Mail::send('email.activationLink', ['data' => $data1], function ($message) use ($data1) {
                $message->to($data1['email'], $data1['name'])->from(env('MAIL_USERNAME'), 'Samy bot')->subject('Activation link from Samy bot');
            });
        }
        catch (\Swift_TransportException $ex) {
            $data['success'] = true;
            $data['data'] = "";
            $data['message'] = "Something went wrong";
            return $data;
        }
        AppUsers::whereId($user->id)->update(['activation_hash' => $activationLink]);
	    if($user->receive_email == "true"){
		    $user->receive_email = true;
	    }
	    else{
		    $user->receive_email = false;
	    }
	    if($user->receive_favorite == "true"){
		    $user->receive_favorite = true;
	    }
	    else{
		    $user->receive_favorite = false;
	    }
        $data['success'] = true;
        $data['data'] = $user;
        $data['message'] = "Mail will be sent to your Email Id";
        return $data;
    }

    public function activate_user_api(Request $request)
    {
        $email = $request->email;
        if (AppUsers::where('email', $email)->exists()) {
            $user = AppUsers::where('email', $email)->first();
            AppUsers::whereId($user->id)->update(['activated' => 1]);
	        if($user->receive_email == "true"){
		        $user->receive_email = true;
	        }
	        else{
		        $user->receive_email = false;
	        }
	        if($user->receive_favorite == "true"){
		        $user->receive_favorite = true;
	        }
	        else{
		        $user->receive_favorite = false;
	        }
            $data['success'] = true;
            $data['data'] = $user;
            $data['message'] = "User account activated";
            return redirect('home');
        } else {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "There is no account with this email";
            return $data;
        }
    }

    public function update_user(Request $request)
    {
        $user_id = $request->user_id;
        $name = $request->user_name;
        $image = $request->user_image;
        $phone = $request->user_phone;
        $receive_favorite = $request->receive_favorite;
        $receive_email = $request->receive_email;
        if ($user_id == "") {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "User Id Required.";
            return $data;
        }
        if (AppUsers::whereId($user_id)->exists()) {
            if (!empty($name) ||  !empty($phone) || !empty($receive_favorite) || !empty($image)) {
                $user = AppUsers::whereId($user_id)->first();
                if (!empty($image)) {
                    if ($request->hasFile('user_image')) {
	                    $extension = $request->file('user_image')->getClientOriginalExtension();
	                    if($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg' || $extension == 'PNG' || $extension == 'svg'){
                            $filepath = public_path('avatars' . '/' . $user->photo);
                            $this->UnlinkImage($filepath);
                            $image = $request->file('user_image');
                            $name11 = time() . $request->file('user_image')->getClientOriginalName();
                            $destinationPath = public_path('avatars');
                            $mime = $request->file('user_image')->getClientOriginalExtension();
                            $this->compress($request->file('user_image'), public_path('avatars') . '/' . $name11, 100, $mime);
                            $Input['photo'] = $name11;
                        }
                        else
                        {
	                        $data['success'] = false;;
	                        $data['data'] = "";
	                        $data['message'] = "Profile image should be valid image type.";
	                        return $data;
                        }

                    }else{
                        $Input['photo'] = $user->photo;
                    }
                }
                if (!empty($name)) {
                    $Input['name'] = $name;
                }
                if (!empty($phone)) {
                    $Input['phone_number'] = $phone;
                }
                if (!empty($receive_favorite)) {
                    $Input['receive_favorite'] = $receive_favorite;
                }
                    $Input['receive_email'] = $receive_email;
                AppUsers::whereId($user->id)->update($Input);
                $user1 = AppUsers::whereId($user_id)->first();
                $user1['user_image'] = asset('public/avatars'). '/' . $user1->photo;
	            if($user1->receive_email == "true"){
		            $user1->receive_email = true;
	            }
	            else{
		            $user1->receive_email = false;
	            }
	            if($user1->receive_favorite == "true"){
		            $user1->receive_favorite = true;
	            }
	            else{
		            $user1->receive_favorite = false;
	            }
                $data['success'] = true;
                $data['data'] = $user1;
                $data['message'] = "Updated user details.";
            } else {
                $data['success'] = false;;
                $data['data'] = "";
                $data['message'] = "Enter details to update";
                return $data;
            }
        } else {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "User account with this id not found.";
        }
        return $data;
    }

    public function delete_user(Request $request)
    {
        $user_id = $request->user_id;
        if (AppUsers::where('id', $user_id)->exists()) {
            $user = AppUsers::whereId($user_id)->first();
            $array['email'] = $user->email;
            $array['name'] = $user->name;
            $array['subject'] = "We are sorry!";
            $array['content'] = 'We\'re sorry to see you go. We\'re here anytime if you want to find deals around you again.';
            try{
                Mail::send([], [], function ($message) use ($array) {
                    $message->from(env('MAIL_USERNAME'), 'Samy bot');
                    $message->to($array['email']);
                    $message->subject($array['subject']);
                    $message->setBody($array['content'], 'text/html');
                });
            }
            catch (\Swift_TransportException $ex) {
                $data['success'] = true;
                $data['data'] = "";
                $data['message'] = "Something went wrong";
                return $data;
            }
            AppUsers::whereId($user_id)->delete();
            favorite::where('user_id', $user_id)->delete();
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "User deleted";
            return $data;
        } else {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "There is no account with this user id.";
            return $data;
        }
    }

    public function admin_login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if ($email != "" || $password != "") {
            if (AppUsers::where('email', $email)->exists()) {
                $user = AppUsers::where('email', $email)->first();
                if (Hash::check($password, $user->password) && $email == $user->email) {
                    if ($user->status == 1) {
                        $data1['company_id'] = $user->typeid;
                        $data['success'] = true;
                        $data['data'] = $data1;
                        $data['message'] = "OK";
                    } else {
                        $data['success'] = true;
                        $data['data'] = "";
                        $data['message'] = "You are not an admin";
                    }
                } else {
                    $data['success'] = false;;
                    $data['data'] = "";
                    $data['message'] = "Wrong Combination of Email and Password";
                }
            } else {
                $data['success'] = false;;
                $data['data'] = "";
                $data['message'] = "No account with this email/password";
            }
        } else {
            $data['success'] = false;;
            $data['data'] = "";
            $data['message'] = "Both Fields are Required.";
        }
        return $data;
    }

    public function forgot_password(Request $request)
    {
        $email = $request->email;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 12; $i++) {
            $randomString .= $characters[rand(0, $length - 1)];
        }
        if (AppUsers::whereEmail($email)->exists()) {
            $user = AppUsers::whereEmail($email)->first();
            $pass = bcrypt($randomString);
            AppUsers::whereId($user->id)->update(['password' => $pass]);
            $data['email'] = $user->email;
            $data['name'] = $user->name;
            $data['content'] = 'You requested a new password. Please use this temporary password to connect: ' . $randomString;
            try{
                Mail::send('forgetpassword', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'], $data['name'])->from(env('MAIL_USERNAME'), 'Samy bot')->subject('Request for a new password');
                });
            }
            catch (\Swift_TransportException $ex) {
                $data['success'] = true;
                $data['data'] = "";
                $data['message'] = "Something went wrong";
                return $data;
            }
	        if($user->receive_email == "true"){
		        $user->receive_email = true;
	        }
	        else{
		        $user->receive_email = false;
	        }
	        if($user->receive_favorite == "true"){
		        $user->receive_favorite = true;
	        }
	        else{
		        $user->receive_favorite = false;
	        }
            $user_data['success'] = true;
            $user_data['data'] = $user;
            $user_data['message'] = 'success';
            return $user_data;
        } else {
            $data1['success'] = false;
            $data1['message'] = 'There is no account with this email. ';
            return $data1;
        }
    }

    public function reset_password(Request $request)
    {
        $user_id     = $request->user_id;
        $nPassword   = $request->password;
        $cPassword   = $request->current_password;
        $ncPassword  = $request->confirm_password;
        if (AppUsers::whereId($user_id)->exists()) {
            $user = AppUsers::whereId($user_id)->first();
            $userPass = $user->password;
            if (Hash::check($cPassword, $userPass)) {
                if ($nPassword == $ncPassword) {
                    AppUsers::whereId($user_id)->update(['password' => bcrypt($nPassword)]);
                    $newuser =  AppUsers::whereId($user_id)->first();
	                if($newuser->receive_email == "true"){
		                $newuser->receive_email = true;
	                }
	                else{
		                $newuser->receive_email = false;
	                }
	                if($newuser->receive_favorite == "true"){
		                $newuser->receive_favorite = true;
	                }
	                else{
		                $newuser->receive_favorite = false;
	                }
                    $data['success'] = true;
                    $data['data']    = $newuser;
                    $data['message'] = 'success';
                } else {
                    $data['success'] = false;
                    $data['success'] = false;
                    $data['message'] = 'Password and confirm password does not match.';
                }
            }
            else {
                $data['success'] = false;
                $data['data']    = "";
                $data['message'] = 'Enter valid Current Password.';
            }
        }
        else {
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = 'User Does Not Exists.';
        }
        return $data;
    }
	function compress($source, $destination, $quality,$mime) {
// Set a maximum height and width
		$width = 200;
		$height = 200;

// Content type
		header('Content-Type: image/'.$mime);

// Get new dimensions
		list($width_orig, $height_orig) = \getimagesize($source);

		$ratio_orig = $width_orig/$height_orig;

		if ($width/$height > $ratio_orig) {
			$width = $height*$ratio_orig;
		} else {
			$height = $width/$ratio_orig;
		}

// Resample
		$image_p = \imagecreatetruecolor($width, $height);
		$info = \getimagesize($source);

		if ($info['mime'] == 'image/jpg')
			$image = \imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/jpeg')
			$image = \imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif')
			$image = \imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png')
			$image = \imagecreatefrompng($source);
		\imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        \imagejpeg($image_p, $destination, $quality);
		return $destination;
	}
    function UnlinkImage($filepath)
    {
        $old_image = $filepath;
        if (file_exists($old_image)) {
            @unlink($old_image);
        }
    }
}