<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
 <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII" />
    <head>
    <head>
    <title> Rewards </title>
   </head>
  </head>
  <body>
    <!--[if gte mso 15]>
    <xml>
      <o:OfficeDocumentSettings>
        <o:AllowPNG />
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <h1>Good News!</h1>
     <?php
     $user = App\User::where('id', $userid)->first(); 
     if($user){
      $fullName = $user->f_name . ' ' . $user->l_name;
     }
     ?>
    <p style="mso-line-height-rule: exactly; direction: ltr; font-family: &#39;Poppins&#39;,-apple-system,BlinkMacSystemFont,&#39;Segoe UI&#39;,Poppins,sans-serif; font-size: 15px; line-height: 22px; font-weight: 400; text-transform: none; color: #000000; margin: 0 0 11px;" align="left">
     <span data-key="4664702_greeting_text" style="text-align: left; direction: ltr; font-family: &#39;Poppins&#39;,-apple-system,BlinkMacSystemFont,&#39;Segoe UI&#39;,Poppins,sans-serif; font-size: 15px; line-height: 22px; font-weight: 400; text-transform: none; color: #000000;">
      <b>{{translate('Dear')}}</b>
      </span>
      <b>{{$fullName}},</b>
     <p>You got this <strong>{{ $credit }}</strong> creadit in your account.</p>
</body>
</html>
