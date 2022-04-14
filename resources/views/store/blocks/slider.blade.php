<section class="p-0 my-home-slider">
    <div class="slide-1 home-slider">
        @foreach ($sliderItems as $item)
        <div>
            <a href="" class="home text-center">
                <img src="{{asset('store/images')}}/{{$item['picture']}}" alt="" class="bg-img blur-up lazyload">
            </a>
        </div>
        @endforeach
       
    </div>
</section>
