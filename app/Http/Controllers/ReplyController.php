<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
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
     * @return Response
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
     * @return Application|ResponseFactory|RedirectResponse|Response|Model
     * @throws Exception
     */
    public function store(CreatePostRequest $form, $channelId, Thread $thread)
    {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * Display the specified resource.
     *
     * @param Reply $reply
     * @return Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reply $reply
     * @return Response
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
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            request()->validate(['body' => ['required', new SpamFree]]);
            $reply->update(request(['body']));
        } catch (Exception $e) {
            return response('Sorry, your response could not be updated at this time.', 422);
        }

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
}
