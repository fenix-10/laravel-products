<h1>This is index page</h1>
<div>
    @foreach($tags as $tag)
        <div>
            <h2>
                {{ $tag->title }}
            </h2>
        </div>
    @endforeach
</div>
