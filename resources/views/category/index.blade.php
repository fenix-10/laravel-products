<h1>This is index page</h1>
<div>
    @foreach($categories as $category)
    <div>
        <h2>
            {{ $category->title }}
        </h2>
    </div>
    @endforeach
</div>
