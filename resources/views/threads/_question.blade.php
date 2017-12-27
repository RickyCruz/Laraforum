{{-- Editing the question. --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">

            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="form.body"></textarea>
        </div>
    </div>

    <div class="panel-footer">
        <button class="btn btn-xs btn-primary level-item" @click="update">Save</button>
        <button class="btn btn-xs btn-default level-item" @click="resetForm">Cancel</button>
    </div>
</div>

{{-- Viewing the question. --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">

            <span class="flex">
                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:
                <span v-text="title"></span>
            </span>
        </div>
    </div>

    <div class="panel-body" v-text="body"></div>

    <div class="panel-footer" v-if="authorize('owns', thread)">
        <div class="level">
            <button class="btn btn-xs btn-default" @click="editing = true">Edit</button>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>
