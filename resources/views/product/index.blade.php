<h1>This is index page</h1>
<div>
    @foreach($products as $product)
    <div>
        <h2>{{ $product->title }}</h2>
        <h3>{{ $product->description }}</h3>
    </div>
    @endforeach
</div>
