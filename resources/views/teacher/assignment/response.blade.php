<h3>Sonuç İçeriği</h3>
{!! $response->response !!}
<hr>
<div class="d-flex justify-content-end">
    <div title="Ek İçerik" class="sw-30 mt-2">
        <div class="row g-0 rounded-sm sh-8 border">
            <div class="col-auto">
                <div class="sw-10 d-flex justify-content-center align-items-center h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-file-empty mb-3 d-inline-block text-primary"><path d="M6.5 18H13.5C14.9045 18 15.6067 18 16.1111 17.6629C16.3295 17.517 16.517 17.3295 16.6629 17.1111C17 16.6067 17 15.9045 17 14.5V7.44975C17 6.83775 17 6.53175 16.9139 6.24786C16.8759 6.12249 16.8256 6.00117 16.7638 5.88563C16.624 5.62399 16.4076 5.40762 15.9749 4.97487L14.0251 3.02513L14.0251 3.02512C13.5924 2.59238 13.376 2.37601 13.1144 2.23616C12.9988 2.1744 12.8775 2.12415 12.7521 2.08612C12.4682 2 12.1622 2 11.5503 2H6.5C5.09554 2 4.39331 2 3.88886 2.33706C3.67048 2.48298 3.48298 2.67048 3.33706 2.88886C3 3.39331 3 4.09554 3 5.5V14.5C3 15.9045 3 16.6067 3.33706 17.1111C3.48298 17.3295 3.67048 17.517 3.88886 17.6629C4.39331 18 5.09554 18 6.5 18Z"></path></svg>
                </div>
            </div>
            <div class="col rounded-sm-end d-flex flex-column justify-content-center pe-3">
                <div class="d-flex justify-content-between">
                    <p class="mb-0 clamp-line" data-line="1" style="overflow: hidden; text-overflow: ellipsis; -webkit-box-orient: vertical; display: -webkit-box; -webkit-line-clamp: 1;" title="{{$response->getFileName()}}">{{\Illuminate\Support\Str::limit($response->getFileName(),10,"...")}}</p>

                    <a target="_blank" href="{{$response->getFileUrl()}}" download="{{$response->getFileName()}}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-delay="{&quot;show&quot;:&quot;1000&quot;, &quot;hide&quot;:&quot;0&quot;}" data-bs-original-title="İndir">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-cloud-download undefined"><path d="M12 16 10.3536 17.6464C10.1583 17.8417 9.84171 17.8417 9.64645 17.6464L8 16M10 7 10 17"></path><path d="M15 13C16.5 13 18 12 18 8.94737C18 6.69591 16.2636 4.89474 14.093 4.89474C14.031 4.89474 13.969 4.89474 13.907 4.89474C13.2248 3.22222 11.6124 2 9.68992 2C7.33333 2.06433 5.41085 3.92983 5.22481 6.30994C3.42636 6.30994 2 7.78947 2 9.65497C2 11.5205 3.42636 13 5.22481 13"></path></svg>
                    </a>

                </div>
                <div class="text-small text-primary" title="Boyut">{{$response->getFileSize()}}</div>

            </div>
        </div>
    </div>
</div>
