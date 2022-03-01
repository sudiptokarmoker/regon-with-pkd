<div class="mt-5">
    <form id="opinion-form" action="{{ route('opinions.store') }}" method="POST">
        <input type="hidden" name="nip" value="{{ $nip }}">
        @csrf
        <h5>Company</h5>
        <div class="row mb-2">
            <div class="col-6">
                <b>Name :</b> {{ Arr::get($company, 'name') }}
            </div>
            <div class="col-6">
                <b>Address :</b> {{ Arr::get($company, 'address') }}
            </div>
        </div>

        <h6>Give your opinion</h6>
        <!-- One "tab" for each step in the form: -->
        <div class="tab">Delivery of documents:
            <input type="text" name="doc_delivery" class="rating rating-loading" value="1" data-size="sm" data-theme="krajee-fa" title="">
        </div>
        <div class="tab">Payment:
            <input type="text" name="payment" class="rating rating-loading" value="1" data-size="sm" data-theme="krajee-fa" title="">
        </div>
        <div class="tab">Cooperation culture :
            <input type="text" name="cooperation" class="rating rating-loading" value="1" data-size="sm" data-theme="krajee-fa" title="">
        </div>
        <div class="tab">Comment:
            <textarea class="form-control" name="comment" id="comment"></textarea>
        </div>
        <div style="overflow:auto;">
            <div style="float:right; margin-top: 5px;">
                <button type="button" class="btn btn-warning previous">Back</button>
                <button type="button" class="btn btn-info next">Next</button>
                <button type="button" class="btn btn-success submit">Confirm</button>
            </div>
        </div>
        <!-- Circles which indicates the steps of the form: -->
        <div style="text-align:center;margin-top:40px;">
            <span class="step">1</span>
            <span class="step">2</span>
            <span class="step">3</span>
            <span class="step">4</span>
        </div>
    </form>
</div>
