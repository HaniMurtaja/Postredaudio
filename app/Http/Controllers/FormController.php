<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Enums\MailType;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\VacancyFormRequest;
use Exception;

class FormController extends Controller
{
    public function contactForm(ContactFormRequest $request)
    {
        $formData = $request->validated();

        return $this->sendMail($formData, 'contact');
    }

    public function vacancyForm(VacancyFormRequest $request)
    {
        $formData = $request->validated();

        return $this->sendMail($formData, 'vacancy');
    }

    private function sendMail($data, $mailType)
    {
        if (!in_array($mailType, MailType::values())) {
            return response()->json([
                'message' => ['general' => 'Invalid mail type'],
                'success' => false,
                'status' => 400,
            ], 400);
        }

        try {
            $sentEmail = Mail::to($mailType === 'contact' ?
                nova_get_setting('contact_email', config("mail.mailers.mailgun.contact_mail")) :
                config("mail.mailers.mailgun.vacancy_mail"))
                ->send(new ($mailType === 'contact' ? 'App\Mail\ContactForm' : 'App\Mail\VacancyForm')($data));

            if ($sentEmail instanceof \Illuminate\Mail\SentMessage) {
                return response()->json([
                    'message' => ['general' => 'Request sent successfully'],
                    'success' => true,
                    'status' => 200,
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => [
                    'general' => 'Error sending email',
                    'error' => $e->getMessage()
                ],
                'success' => false,
                'status' => 400,
            ], 400);
        }
    }
}
