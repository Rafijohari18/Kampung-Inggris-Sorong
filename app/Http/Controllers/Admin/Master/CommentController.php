<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $l = '';
        $f = '';
        $q = '';
        if (isset($request->l) || isset($request->f) || isset($request->q)) {
            $l = '?l='.$request->l;
            $f = '&f='.$request->f;
            $q = '&q='.$request->q;
        }

        $data['comment'] = $this->service->getCommentList($request);
        $data['no'] = $data['comment']->firstItem();
        $data['comment']->withPath(url()->current().$l.$f.$q);

        return view('backend.master.comments.index', compact('data'), [
            'title' => 'Comments',
            'breadcrumbs' => [
                'Data Master' => '',
                'Comments' => '',
            ]
        ]);
    }

    public function flags($id)
    {
        $this->service->flags($id);

        return back()->with('success', 'approve / inapprove comment successfully');
    }

    public function flagsReply($id)
    {
        $this->service->flagsReply($id);

        return back()->with('success', 'approve / inapprove comment reply successfully');
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
                'message' => 'cannot delete comment that are already used'
            ], 200);
        }
    }

    public function destroyReply($id)
    {
        $this->service->deleteReply($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }
}
