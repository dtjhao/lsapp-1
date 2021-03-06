<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

//use App\Mail\TestEmail;
//use Mail;

require '../vendor/autoload.php';

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request){
        Log::info("sendresetlinkemail");
        $this->validate($request, ['email' => 'required|email']);
        //$data = ['message' => 'This is a test!'];
        //try {
        //Mail::to('dtjh@live.com.sg')->send(new TestEmail($data));
       // } catch(Throwable $e) {
       //     Log::info($e);
        //}

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("dtjh@live.com.sg", "Example User");
        $email->setSubject("Sending with SendGrid is Fun");
        $email->addTo("ascendcomunity5@gmail.com", "Example User");
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        //Log::info(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            Log::info( $response->statusCode() . "\n");
            Log::info(($response->headers()));
            Log::info( $response->body() . "\n");
        } catch (Exception $e) {
            Log::info('Caught exception: '. $e->getMessage() ."\n");
        }        
        return redirect('/posts')->with('error', 'No Post Found');
    }
}
