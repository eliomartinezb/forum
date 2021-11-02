<reply :attributes="{{ $reply }}" inline-template class="mt-4">
    <div id="reply-{{ $reply->id }}" class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
            </div>
            @if (Auth::check())
                <div>
                    <favorite :reply="{{ $reply }}"></favorite>
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="body" v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div class="body" v-else v-text="body"></div>
        </div>
        @can('update', $reply)
            <div class="panel-footer level p-2">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>