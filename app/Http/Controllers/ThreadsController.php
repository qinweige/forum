<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use Illuminate\Http\Request;
use App\Filters\ThreadsFilter;

class ThreadsController extends Controller
{

    protected $guarded = [];
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * @param Channel $channel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($channelSlug = null, ThreadsFilter $filter)
    {
        $threads = $this->getThreads($channelSlug, $filter);

        return view('threads.index', compact('threads'));
    }

    public function getThreads($channelSlug = null, ThreadsFilter $filter)
    {
        $threads = Thread::latest()->filter($filter);
        if ($channelSlug != null) {
            $channelId = Channel::where('slug', $channelSlug)->get()->first()->id;
            $threads->where('channel_id', $channelId)->latest();
        }
        return $threads->get();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'body' => 'required',
                'channel_id' => 'required|exists:channels,id'
            ]);
        $thread = Thread::create(array(
            'user_id' => auth()->id(),
            'channel_id' => $request['channel_id'],
            'title' => $request['title'],
            'body' => $request['body']
        ));
        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread)
    {
        return view('threads.show', [
            'thread' =>$thread,
            'replies' => $thread->replies()->paginate(20)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
