<?php

namespace App\Services\Modules\MPost;

use App\Models\Contest;
use App\Models\Enterprise;
use App\Models\Post as ModelsPost;
use App\Models\Recruitment;
use App\Models\Round;
use App\Services\Traits\TUploadImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Post
{
    use TUploadImage;
    public function __construct(

        private Contest $contest,
        private ModelsPost $post,
        private Enterprise $enterprise,
        private Recruitment $recruitment,
        private Round $round,

    ) {
    }
    public function getList(Request $request)
    {
        $keyword = $request->has('keyword') ? $request->keyword : "";
        $status = $request->has('status') ? $request->status : null;
        $contest = $request->has('contest_id') ? $request->contest_id : 0;
        $rounds = $request->has('round_id') ? $request->round_id : 0;
        $recruitment = $request->has('recruitment_id') ? $request->recruitment_id : 0;
        $progress = $request->has('progress') ? $request->progress : null;
        $orderBy = $request->has('orderBy') ? $request->orderBy : 'id';
        $startTime = $request->has('startTime') ? $request->startTime : null;
        $endTime = $request->has('endTime') ? $request->endTime : null;
        $sortBy = $request->has('sortBy') ? $request->sortBy : "desc";
        $softDelete = $request->has('post_soft_delete') ? $request->post_soft_delete : null;
        if ($softDelete != null) {
            $query = $this->post::onlyTrashed()->where('title', 'like', "%$keyword%")->orderByDesc('deleted_at');
            return $query;
        }
        if ($contest != 0) {
            $query = $this->contest::find($contest);
            return $query;
        }
        if ($rounds != 0) {
            $query = $this->round::find($rounds);
            return $query;
        }
        if ($recruitment != 0) {
            $query = $this->recruitment::find($recruitment);
            return $query;
        }
        $query = $this->post::where('title', 'like', "%$keyword%");
        if ($status != null) {
            $query->where('status', $status);
        }
        if ($progress != null) {
            if ($progress == 'unpublished') {
                $query->where('published_at', '>', \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString());
            } elseif ($progress == 'published') {
                $query->where('published_at', '<', \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString());
            }
        }
        if ($endTime != null && $startTime != null) {
            $query->where('published_at', '>=', \Carbon\Carbon::parse(request('startTime'))->toDateTimeString())->where('published_at', '<=', \Carbon\Carbon::parse(request('endTime'))->toDateTimeString());
        }
        if ($sortBy == "desc") {
            $query->orderByDesc($orderBy);
        } else {
            $query->orderBy($orderBy);
        }
        // dd($query->get());
        return $query;
    }
    public function index(Request $request)
    {
        if ($request->contest_id) {
            return $this->getList($request)->posts()->paginate(config('util.HOMEPAGE_ITEM_AMOUNT'));
        }
        if ($request->round_id) {
            return $this->getList($request)->posts()->paginate(config('util.HOMEPAGE_ITEM_AMOUNT'));
        }
        if ($request->recruitment_id) {
            return $this->getList($request)->posts()->paginate(config('util.HOMEPAGE_ITEM_AMOUNT'));
        }

        return $this->getList($request)->paginate(config('util.HOMEPAGE_ITEM_AMOUNT'));;
    }
    public function find($id)
    {

        return $this->post::find($id);
    }
    public function store($request)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'published_at' => $request->published_at,
            'content' => $request->content ? $request->content : null,
            'slug' => $request->slug,
            'link_to' => $request->link_to ? $request->link_to : null,
            'user_id' => auth()->user()->id,
        ];

        if ($request->has('thumbnail_url')) {
            $fileImage =  $request->file('thumbnail_url');
            $image = $this->uploadFile($fileImage);
            $data['thumbnail_url'] = $image;
        }

        if ($request->contest_id != 0) {
            $dataContest = $this->contest::find($request->contest_id);
            $dataContest->posts()->create($data);
        } elseif ($request->round_id != 0) {
            $dataRound = $this->round::find($request->round_id);
            $dataRound->posts()->create($data);
        } elseif ($request->recruitment_id != 0) {
            $dataRound = $this->recruitment::find($request->recruitment_id);
            $dataRound->posts()->create($data);
        }
    }
    public function update($request, $id)
    {
        $post = $this->post::find($id);


        if (!$post) {
            return redirect('error');
        }
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->published_at = $request->published_at;
        $post->description = $request->description;
        $post->content = $request->content ?  $request->content : null;
        $post->link_to = $request->link_to ?  $request->link_to : null;

        if ($request->has('thumbnail_url')) {
            $fileImage =  $request->file('thumbnail_url');
            $image = $this->uploadFile($fileImage);
            $post->thumbnail_url = $image;
        }
        if ($request->contest_id != 0) {
            $post->postable_id = $request->contest_id;
            $post->postable_type = $this->contest::class;
        } elseif ($request->round_id != 0) {
            $post->postable_id = $request->round_id;
            $post->postable_type = $this->round::class;
        } elseif ($request->recruitment_id != 0) {
            $post->postable_id = $request->recruitment_id;
            $post->postable_type = $this->recruitment::class;
        }
        $post->save();
    }
}
