<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReplyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => 'index',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $channelId
     * @param Thread $thread
     * @return RedirectResponse
     * @throws ValidationException
     * @throws \Exception
     */
    public function store($channelId, Thread $thread): RedirectResponse
    {
        $this->validateReply();

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * Display the specified resource.
     *
     * @param Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Reply $reply
     * @return void
     * @throws ValidationException|AuthorizationException
     */
    public function update(Request $request, Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        $this->validateReply();

        $reply->update(request(['body']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reply $reply
     * @return RedirectResponse
     */
    public function destroy(Reply $reply): RedirectResponse
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'Reply Deleted!']);
        }

        return back();
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    protected function validateReply() {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}
