<div class="table-responsive">
    <table class="table" id="opinions-table">
        <thead>
            <tr>
                <th>@lang('models/opinions.fields.doc_delivery')</th>
                <th>@lang('models/opinions.fields.payment')</th>
                <th>@lang('models/opinions.fields.cooperation')</th>
                <th>@lang('models/opinions.fields.comment')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @if ($opinions->isEmpty())
            <tr>
                <td colspan="5"> <div class="text-center">No opinions found !</div></td>
            </tr>
        @endif
        @foreach($opinions as $opinion)
            <tr>
                <td>
                    <input type="text" class="rating rating-loading" value="{{ $opinion->doc_delivery }}" data-size="sm" data-theme="krajee-fa" readonly></td>
                <td>
                    <input type="text" class="rating rating-loading" value="{{ $opinion->payment }}" data-size="sm" data-theme="krajee-fa" readonly></td>
                <td>
                    <input type="text" class="rating rating-loading" value="{{ $opinion->cooperation }}" data-size="sm" data-theme="krajee-fa" readonly></td>
                <td>{{ $opinion->comment }}</td>
                <td class=" text-center">
                    {!! Form::open(['route' => ['opinions.destroy', $opinion->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
