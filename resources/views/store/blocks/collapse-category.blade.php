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
            @foreach ($categories as $item)
                @php
                    $link = URL::linkCategory($item['id'], $item['name']);
                    $activeClass = (isset($request_id) && $item['id'] == $request_id) ? 'my-text-primary' : 'text-dark';
                @endphp
                <div class="custom-control custom-checkbox collection-filter-checkbox pl-0 category-item">
                    <a class="{{ $activeClass }}" href="{{ $link }}">{{ $item['name'] }}</a>
                </div>
            @endforeach
        </div>
    </div>
</div>
