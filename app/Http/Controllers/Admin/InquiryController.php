<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InquiryContactExport;
use App\Exports\InquiryMessageExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inquiry\InquiryRequest;
use App\Services\InquiryService;
use App\Services\LanguageService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InquiryController extends Controller
{
    private $service, $serviceLang, $notification;

    public function __construct(
        InquiryService $service,
        LanguageService $serviceLang,
        NotificationService $notification
    )
    {
        $this->service = $service;
        $this->serviceLang = $serviceLang;
        $this->notification = $notification;
    }

    public function index(Request $request)
    {
        $l = '';
        $s = '';
        $q = '';
        if (isset($request->l) || isset($request->s) || isset($request->q)) {
            $l = '?l='.$request->l;
            $s = '&s='.$request->s;
            $q = '&q='.$request->q;
        }

        $data['inquiries'] = $this->service->getInquiryList($request);
        $data['no'] = $data['inquiries']->firstItem();
        $data['inquiries']->withPath(url()->current().$l.$s.$q);

        return view('backend.inquiries.index', compact('data'), [
            'title' => 'Inquiries',
            'breadcrumbs' => [
                'Inquiries' => '',
            ]
        ]);
    }

    public function detail(Request $request, $inquiryId)
    {
        $l = '';
        $s = '';
        $q = '';
        if (isset($request->l) || isset($request->s) || isset($request->q)) {
            $l = '?l='.$request->l;
            $s = '&s='.$request->s;
            $q = '&q='.$request->q;
        }

        $data['message'] = $this->service->getMessageList($request, $inquiryId);
        $data['no'] = $data['message']->firstItem();
        $data['message']->withPath(url()->current().$l.$s.$q);
        $data['inquiry'] = $this->service->find($inquiryId);

        $this->notification->read('admin/management/inquiry/'.$inquiryId.'/detail?q='.$request->q);

        return view('backend.inquiries.detail.'.$data['inquiry']->slug, compact('data'), [
            'title' => 'Inquiries - '.$data['inquiry']->fieldLang('name'),
            'breadcrumbs' => [
                'Inquiries' => '',
                $data['inquiry']->fieldLang('name') => '',
            ]
        ]);
    }

    public function create(Request $request)
    {
        $data['languages'] = $this->serviceLang->getAllLang();

        return view('backend.inquiries.form', compact('data'), [
            'title' => 'Inquiry - Create',
            'breadcrumbs' => [
                'Inquiry' => route('inquiry.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(InquiryRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'inquiry successfully added');
    }

    public function edit($id)
    {
        $data['inquiry'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();

        return view('backend.inquiries.form-edit', compact('data'), [
            'title' => 'Inquiry - Edit',
            'breadcrumbs' => [
                'Inquiry' => route('inquiry.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(InquiryRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'inquiry successfully updated');
    }

    public function publish($id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status inquiry changed');
    }

    public function read($inquiryId, $id)
    {
        $this->service->status($id, 1);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function export($inquiryId)
    {
        $inquiry = $this->service->find($inquiryId);

        $update = $inquiry->message()->where('exported', 0)->update([
            'exported' => 1
        ]);

        return Excel::download(new InquiryMessageExport($inquiry), 'inquiry-message.xlsx');
    }

    public function destroy($id)
    {
        $delete = $this->service->delete($id);

        if ($delete == true) {

            return response()->json([
                'success' => 1,
                'message' => ''
            ], 200);

        } else {

            return response()->json([
                'success' => 0,
                'message' => 'cannot delete inquiry that still have message'
            ], 200);
        }
    }

    public function destroyMessage($inquiryId, $id)
    {
        $this->service->deleteMessage($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('inquiry.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
