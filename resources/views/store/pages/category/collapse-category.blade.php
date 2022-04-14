@php
use App\Helpers\URL;
use App\Models\CategoryModel;
$categoryModel = new CategoryModel();
$categories = $categoryModel->listItems(null, ['task' => 'store-list-items']);

@endphp

<div class="collection-collapse-block open">
    <h3 class="collapse-block-title">Danh mục</h3>
    <div class="collection-collapse-block-content">
        <div class="collection-brand-filter">
            <div class="custom-control custom-checkbox collection-filter-checkbox pl-0 category-item">
                <a class="my-text-primary" href="list.html">Bà mẹ - Em bé</a>
            </div>
            @foreach ($categories as $item)
                @php
                    $link = URL::linkCategory($item['id'], $item['name']);
                @endphp
                <div class="custom-control custom-checkbox collection-filter-checkbox pl-0 category-item">
                    <a class="text-dark " href="{{ $link }}">{{ $item['name'] }}</a>
                </div>
            @endforeach


        </div>
    </div>
</div>
