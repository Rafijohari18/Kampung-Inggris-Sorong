<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inquiry\InquiryContactRequest;
use App\Services\ConfigurationService;
use App\Services\InquiryService;
use App\Services\NotificationService;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InquiryViewController extends Controller
{
    private $service, $serviceUser, $config, $notification;

    public function __construct(
        InquiryService $service,
        UserService $serviceUser,
        ConfigurationService $config,
        NotificationService $notification
    )
    {
        $this->service = $service;
        $this->serviceUser = $serviceUser;
        $this->config = $config;
        $this->notification = $notification;
    }

    public function viewInquiryList(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['inquiries'] = $this->service->getInquiry($request, 'paginate', $limit);

        return view('frontend.inquiries.list', compact('data'), [
            'title' => 'Inquiries',
            'breadcrumbs' => [
                'Inquiries' => '',
            ],
        ]);
    }

    public function viewWithLang(Request $request, $lang = null, $slug = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->content($request, $slug, $lang);
    }

    public function viewWithoutLang(Request $request, $slug = null)
    {
        return $this->content($request, $slug);
    }

    public function content($request, $slug, $lang = null)
    {
        $data['read'] = $this->service->findBySlug($slug);

        $this->service->recordViewer($data['read']->id);

        //check
        if (empty($slug)) {
            return abort(404);
        }

        if ($data['read']->publish == 0 || empty($data['read']) || $data['read']->is_widget == 1) {
            return redirect()->route('home');
        }

        //data
        $data['field'] = $data['read']->field;

        // meta data
        $data['meta_title'] = $data['read']->fieldLang('name');
        if (!empty($data['read']->meta_data['title'])) {
            $data['meta_title'] = Str::limit(strip_tags($data['read']->meta_data['title']), 69);
        }

        $data['meta_description'] = $this->config->getValue('meta_description');
        if (!empty($data['read']->meta_data['description'])) {
            $data['meta_description'] = $data['read']->meta_data['description'];
        } elseif (empty($data['read']->meta_data['description']) && !empty($data['read']->fieldLang('body'))) {
            $data['meta_description'] = Str::limit(strip_tags($data['read']->fieldLang('body')), 155);
        }

        $data['meta_keywords'] = $this->config->getValue('meta_keywords');
        if (!empty($data['read']->meta_data['keywords'])) {
            $data['meta_keywords'] = $data['read']->meta_data['keywords'];
        }

        //images
        $data['creator'] = $data['read']->createBy->name;
        $data['banner'] = $data['read']->bannerSrc($data['read']);

        //breadcrumbs
        $breadcrumbsLong = [
            'Inquiry' => config('custom.language.multiple') == true ? route('inquiry.list', ['locale' => app()->getLocale()]) : route('inquiry.list'),
            $data['read']->fieldLang('name') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('name') => '',
        ];
        

        return view('frontend.inquiries.'.$slug, compact('data'), [
            'title' => 'Inquiry - '.$data['read']->fieldLang('name'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }

    public function sendContact(InquiryContactRequest $request, $inquiryId)
    {
    
        $inquiry = $this->service->find($inquiryId);

        $message = 'Send message successfully';
        if (!empty($inquiry->fieldLang('after_body'))) {
            $message = strip_tags($inquiry->fieldLang('after_body'));
        }

        $data = [
            'inquiryId' => $inquiryId,
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        foreach ($this->serviceUser->getUserActive('id', [1,2,3]) as $key => $value) {
            $this->notification->sendNotification(null, $value, 0,
                'las la-envelope', 'info', 'New Message', $request->message,
                'admin/management/inquiry/'.$inquiryId.'/detail?q='.$request->name);
        }

        // Mail::to($this->serviceUser->getUserActive('email', [1,2,3])['email'])->send(new \App\Mail\ContactMessageMail($data));

        $this->service->sendMessageContact($request, $inquiryId);

        Cookie::queue($inquiry->slug, $inquiry->fieldLang('name'), 120);
        

        return back()->with('success', $message);
    }
}
