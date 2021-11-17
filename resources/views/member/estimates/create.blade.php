@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.estimates.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">

<style>
        .dropdown-content {
            width: 250px;
            max-height: 250px;
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .item-row .input-group-addon {
            cursor: move;
        }
        #product-box .select2-results__option--highlighted[aria-selected] {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        #product-box .select2-results__option[aria-selected=true] {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        #product-box .select2-results__option[aria-selected] {
            cursor:default !important;
        }
        #selectProduct {
            width: 200px !important;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.estimates.createEstimate')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">

                                <div class="row">
    
                                    <div class="col-md-4">
    
                                        <div class="form-group" >
                                            <label class="control-label required">@lang('app.client')</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <select class="select2 form-control" data-placeholder="Choose Client" name="client_id">
                                                        @foreach($clients as $client)
                                                            <option value="{{ $client->user_id }}">{{ ucwords($client->user->name) }}
                                                            @if($client->company_name != '') {{ '('.$client->company_name.')' }} @endif</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
    
                                    </div>
    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('modules.invoices.currency')</label>
                                            <select class="form-control" name="currency_id" id="currency_id">
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
    
                                    </div>
    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('modules.estimates.validTill')</label>
                                            <div class="input-icon">
                                                <input type="text" class="form-control" name="valid_till" id="valid_till" value="{{ \Carbon\Carbon::today()->addDays(30)->format($global->date_format) }}">
                                            </div>
                                        </div>
    
                                    </div>
    
    
                                </div>
    
    
                                <hr>
    
                                <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group m-b-10 product-select" id="product-select">
                                                <select id="selectProduct" name="select"  data-placeholder="Select a product">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
    
                                <div class="row">
    
                                    <div class="col-xs-12  visible-md visible-lg">
    
                                        <div class="col-md-4 font-bold" style="padding: 8px 15px">
                                            @lang('modules.invoices.item')
                                        </div>
    
                                        <div class="col-md-1 font-bold" style="padding: 8px 15px">
                                            @lang('modules.invoices.qty')
                                        </div>
    
                                        <div class="col-md-2 font-bold" style="padding: 8px 15px">
                                            @lang('modules.invoices.unitPrice')
                                        </div>
    
                                        <div class="col-md-2 font-bold" style="padding: 8px 15px">
                                            @lang('modules.invoices.tax')
                                        </div>
    
                                        <div class="col-md-2 text-center font-bold" style="padding: 8px 15px">
                                            @lang('modules.invoices.amount')
                                        </div>
    
                                        <div class="col-md-1" style="padding: 8px 15px">
                                            &nbsp;
                                        </div>
    
                                    </div>
    
                                    <div id="sortable">
                                        <div class="col-xs-12 item-row margin-top-5">
    
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                            <input type="text" class="form-control item_name" name="item_name[]">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
    
                                                    </div>
                                                </div>
    
                                            </div>
    
                                            <div class="col-md-1">
    
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
                                                    <input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >
                                                </div>
    
    
                                            </div>
    
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                                                        <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="0" >
                                                    </div>
                                                </div>
    
                                            </div>
    
                                            <div class="col-md-2">
    
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.type')</label>
                                                    <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="selectpicker customSequence form-control type">
                                                        @foreach($taxes as $tax)
                                                            <option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                        @endforeach
                                                    </select>
                                                </div>
    
    
                                            </div>
    
                                            <div class="col-md-2 border-dark  text-center">
                                                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>
    
                                                <p class="form-control-static"><span class="amount-html">0.00</span></p>
                                                <input type="hidden" class="amount" name="amount[]" value="0">
                                            </div>
    
                                            <div class="col-md-1 text-right visible-md visible-lg">
                                                <button type="button" class="btn remove-item btn-circle btn-danger"><i class="fa fa-remove"></i></button>
                                            </div>
                                            <div class="col-md-1 hidden-md hidden-lg">
                                                <div class="row">
                                                    <button type="button" class="btn btn-circle remove-item btn-danger"><i class="fa fa-remove"></i> @lang('app.remove')</button>
                                                </div>
                                            </div>
    
                                        </div>
                                    </div>
    
                                    <div id="item-list">
    
                                    </div>
    
                                    <div class="col-xs-12 m-t-5">
                                        <button type="button" class="btn btn-info" id="add-item"><i class="fa fa-plus"></i> @lang('modules.invoices.addItem')</button>
                                    </div>
    
                                    <div class="col-xs-12 ">
    
    
                                            <div class="row">
                                                <div class="col-md-offset-9 col-xs-6 col-md-1 text-right p-t-10" >@lang('modules.invoices.subTotal')</div>
        
                                                <p class="form-control-static col-xs-6 col-md-2" >
                                                    <span class="sub-total">0.00</span>
                                                </p>
        
        
                                                <input type="hidden" class="sub-total-field" name="sub_total" value="0">
                                            </div>
        
                                            <div class="row">
                                                <div class="col-md-offset-9 col-md-1 text-right p-t-10">
                                                    @lang('modules.invoices.discount')
                                                </div>
                                                <div class="form-group col-xs-6 col-md-1" >
                                                    <input type="number" min="0" value="0" name="discount_value" class="form-control discount_value">
                                                </div>
                                                <div class="form-group col-xs-6 col-md-1" >
                                                    <select class="form-control" name="discount_type" id="discount_type">
                                                        <option value="percent">%</option>
                                                        <option value="fixed">@lang('modules.invoices.amount')</option>
                                                    </select>
                                                </div>
                                            </div>
        
                                            <div class="row m-t-5" id="invoice-taxes">
                                                <div class="col-md-offset-9 col-md-1 text-right p-t-10">
                                                    @lang('modules.invoices.tax')
                                                </div>
        
                                                <p class="form-control-static col-xs-6 col-md-2" >
                                                    <span class="tax-percent">0.00</span>
                                                </p>
                                            </div>
        
                                            <div class="row m-t-5 font-bold">
                                                <div class="col-md-offset-9 col-md-1 col-xs-6 text-right p-t-10" >@lang('modules.invoices.total')</div>
        
                                                <p class="form-control-static col-xs-6 col-md-2" >
                                                    <span class="total">0.00</span>
                                                </p>
        
        
                                                <input type="hidden" class="total-field" name="total" value="0">
                                            </div>
        
                                        </div>
    
                                </div>
    
    
                                <div class="row">
    
                                    <div class="col-sm-12">
    
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.note')</label>
    
                                            <textarea name="note" class="form-control" rows="5"></textarea>
                                        </div>
    
                                    </div>
    
                                </div>
    
                            </div>
                        <div class="form-actions" style="margin-top: 70px">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="dropup">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @lang('app.save') <span class="caret"></span>
                                        </button>
                                        <ul role="menu" class="dropdown-menu">
                                            <li>
                                                <a href="javascript:;" class="save-form" data-type="save">
                                                    <i class="fa fa-save"></i> @lang('app.save')
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:;" class="save-form" data-type="draft">
                                                    <i class="fa fa-file"></i> @lang('app.saveDraft')
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0);" class="save-form" data-type="send">
                                                    <i class="fa fa-send"></i> @lang('app.saveSend')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
{{--<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>--}}
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>

<script>
     $(function () {
        $( "#sortable" ).sortable();
    });
     $(document).ready(function(){
         var products = {!! json_encode($products) !!}
         var  selectedID = '';
         $("#selectProduct").select2({
             data: products,
             placeholder: "Select a Product",
             allowClear: true,
             escapeMarkup: function(markup) {
                 return markup;
             },
             templateResult: function(data) {
                 var htmlData = '<b>'+data.title+'</b> <a href="javascript:;" class="btn btn-success btn btn-outline btn-xs waves-effect pull-right">@lang('app.add') <i class="fa fa-plus" aria-hidden="true"></i></a>';
                 return htmlData;
             },
             templateSelection: function(data) {
                 $('#select2-selectProduct-container').html('@lang('app.add') @lang('app.menu.products')');
                 $("#selectProduct").val('');
                 selectedID = data.id;
                 return '';
             },
         }).on('change', function (e) {
             if(selectedID){
                 addProduct(selectedID);
                 $('#select2-selectProduct-container').html('@lang('app.add') @lang('app.menu.products')');
             }
             selectedID = '';
         }).on('select2:open', function (event) {
             $('span.select2-container--open').attr('id', 'product-box');
         });

     });
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#valid_till').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    });

    $('.save-form').click(function(){
        var type = $(this).data('type');
        calculateTotal();

        $.easyAjax({
            url:'{{route('member.estimates.store')}}',
            container:'#storePayments',
            type: "POST",
            redirect: true,
            data:$('#storePayments').serialize()+ "&type=" + type
        })
    });
    $('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="col-xs-12 item-row margin-top-5">'

            +'<div class="col-md-4">'
            +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>'
            +'<div class="input-group">'
            +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            +'</div>'
            +'</div>'

            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'

            +'</div>'

            +'</div>'

            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'
            +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'
            +'</div>'

            +'</div>'


            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.tax')</label>'
            +'<select id="multiselect'+i+'" name="taxes['+i+'][]"  multiple="multiple" class="selectpicker customSequence form-control type">'
                @foreach($taxes as $tax)
            +'<option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name.': '.$tax->rate_percent }}%</option>'
                @endforeach
            +'</select>'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2 text-center">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static"><span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right visible-md visible-lg">'
            +'<button type="button" class="btn remove-item btn-circle btn-danger"><i class="fa fa-remove"></i></button>'
            +'</div>'

            +'<div class="col-md-1 hidden-md hidden-lg">'
            +'<div class="row">'
            +'<button type="button" class="btn remove-item btn-danger"><i class="fa fa-remove"></i> @lang('app.remove')</button>'
            +'</div>'
            +'</div>'

            +'</div>';

        $(item).hide().appendTo("#sortable").fadeIn(500);
        $('#multiselect'+i).selectpicker();
    });

    $('#storePayments').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            $('select.customSequence').each(function(index){
                $(this).attr('name', 'taxes['+index+'][]');
                $(this).attr('id', 'multiselect'+index+'');
            });
            calculateTotal();
        });
    });

    $('#storePayments').on('keyup','.quantity,.cost_per_item,.item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();

    });

    $('#storePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    function calculateTotal()
    {
        var subtotal = 0;
        var discount = 0;
        var tax = '';
        var taxList = new Object();
        var taxTotal = 0;
        var discountType = $('#discount_type').val();
        var discountValue = $('.discount_value').val();

        $(".quantity").each(function (index, element) {
            var itemTax = [];
            var itemTaxName = [];
            var discountedAmount = 0;

            $(this).closest('.item-row').find('select.type option:selected').each(function (index) {
                itemTax[index] = $(this).data('rate');
                itemTaxName[index] = $(this).text();
            });
            var itemTaxId = $(this).closest('.item-row').find('select.type').val();

            var amount = parseFloat($(this).closest('.item-row').find('.amount').val());
            if(discountType == 'percent' && discountValue != ''){
                discountedAmount = parseFloat(amount - ((parseFloat(amount)/100)*parseFloat(discountValue)));
            }
            else{
                discountedAmount = parseFloat(amount - (parseFloat(discountValue)));
            }

            if(isNaN(amount)){ amount = 0; }

            subtotal = (parseFloat(subtotal)+parseFloat(amount)).toFixed(2);

            if(itemTaxId != ''){
                for(var i = 0; i<=itemTaxName.length; i++)
                {
                    if(typeof (taxList[itemTaxName[i]]) === 'undefined'){
                        if (discountedAmount > 0) {
                            taxList[itemTaxName[i]] = ((parseFloat(itemTax[i])/100)*parseFloat(discountedAmount));                         
                        } else {
                            taxList[itemTaxName[i]] = ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                        }
                    }
                    else{
                        if (discountedAmount > 0) {
                            taxList[itemTaxName[i]] = parseFloat(taxList[itemTaxName[i]]) + ((parseFloat(itemTax[i])/100)*parseFloat(discountedAmount));   
                            console.log(taxList[itemTaxName[i]]);
                         
                        } else {
                            taxList[itemTaxName[i]] = parseFloat(taxList[itemTaxName[i]]) + ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                        }
                    }
                }
            }
        });


        $.each( taxList, function( key, value ) {
            if(!isNaN(value)){
                tax = tax+'<div class="col-md-offset-8 col-md-2 text-right p-t-10">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</p>';
                taxTotal = taxTotal+decimalupto2(value);
            }
        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal).toFixed(2));
        $('.sub-total-field').val(decimalupto2(subtotal));

        

        if(discountValue != ''){
            if(discountType == 'percent'){
                discount = ((parseFloat(subtotal)/100)*parseFloat(discountValue));
            }
            else{
                discount = parseFloat(discountValue);
            }

        }

        $('#invoice-taxes').html(tax);

        var totalAfterDiscount = decimalupto2(subtotal-discount);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        $('.total').html(total.toFixed(2));
        $('.total-field').val(total.toFixed(2));

    }

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }

     function addProduct(id) {
        var currencyId = $('#currency_id').val();
        $.easyAjax({
            url:'{{ route('member.all-invoices.update-item') }}',
            type: "GET",
            data: { id: id, currencyId: currencyId },
            success: function(response) {
                $(response.view).hide().appendTo("#sortable").fadeIn(500);
                var noOfRows = $(document).find('#sortable .item-row').length;
                var i = $(document).find('.item_name').length-1;
                var itemRow = $(document).find('#sortable .item-row:nth-child('+noOfRows+') select.type');
                itemRow.attr('id', 'multiselect'+i);
                itemRow.attr('name', 'taxes['+i+'][]');
                $(document).find('#multiselect'+i).selectpicker();
                calculateTotal();
            }
        });
    }

</script>
@endpush

