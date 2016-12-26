@extends('template')

@section('main')

    <main id="site-content" role="main" ng-controller="transaction_history">

        @include('common.subheader')

        <div class="page-container-responsive row-space-top-4 row-space-4">
            <div class="row">
                <div class="col-md-3">
                @include('common.sidenav')
                <!-- <div class="space-top-4 space-4">
  <a href="{{ url('invite?r=5') }}" class="btn btn-block">Invite Friends</a>
</div> -->
                </div>
                <div class="col-md-9">
                    <div id="transaction-history" class="panel">
                        <ul class="panel-header tabs tabs-header" role="tablist">
                            <li>
                                <a href="javascript:void(0);" class="tab-item" role="tab"
                                   aria-controls="completed-transactions" aria-selected="true">
                                    {{ trans('messages.account.completed_transactions') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="tab-item" role="tab"
                                   aria-controls="future-transactions" aria-selected="false">
                                    {{ trans('messages.account.future_transactions') }}
                                </a>
                            </li>
                        </ul>
                        <div id="completed-transactions" class="panel-body tab-panel transaction-tab" role="tabpanel"
                             aria-hidden="false">
                            <div class="row">
                                <div class="col-7" ng-show="result_show">
                                    <h3 class="payout-amount">{{ trans('messages.account.paid_out') }}: <span
                                                ng-bind-html="paid_out"></span></h3>
                                </div>
                                <div class="col-5 export-csv-link-container">
                                    <a href="{{ url() }}/transaction_history/csv/{{ Auth::user()->user()->id }}?@{{ completed_csv_param }}"
                                       class="export-csv-link" ng-show="result_show">
                                        {{ trans('messages.account.export_to_csv') }}
                                    </a>
                                </div>
                            </div>
                            <div class="row payout-filters">
                                <div class="col-12">
                                    <div class="select">
                                        {!! Form::select('payout_method', $payout_methods, '', ['class'=>'payout-method', 'placeholder'=>trans('messages.account.all_payout_methods')]) !!}
                                    </div>
                                    <div class="select">
                                        {!! Form::select('listing', $lists, '', ['class'=>'payout-listing', 'placeholder'=>trans('messages.account.all_listings')]) !!}
                                    </div>
                                    <div class="select">
                                        {!! Form::select('payout_year', $payout_year, '', ['class'=>'payout-year']) !!}
                                    </div>
                                    <div class="select">
                                        {!! Form::select('start_month', $from_month, 1, ['class'=>'payout-start-month']) !!}
                                    </div>
                                    <div class="select">
                                        {!! Form::select('end_month', $to_month, 12, ['class'=>'payout-end-month']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table transaction-table">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('messages.account.date') }}</th>
                                            <th>{{ trans('messages.account.type') }}</th>
                                            <th>{{ trans('messages.your_reservations.details') }}</th>
                                            <th>{{ trans('messages.account.amount') }}</th>
                                            <th>{{ trans('messages.account.paid_out') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody ng-show="result_show">
                                        <tr ng-repeat="item in result">
                                            <td>
                                                @{{ item.date }}
                                            </td>
                                            <td>
                                                {{ trans('messages.account.payout') }}
                                            </td>
                                            <td>
                                                {{ trans('messages.account.transfer_to') }} @{{ item.account }}
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                                <b><span ng-bind-html="item.currency_symbol"></span>@{{ item.amount }}
                                                </b>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <posts-pagination-transaction></posts-pagination-transaction>
                                </div>
                            </div>
                        </div>
                        <div id="future-transactions" class="panel-body tab-panel transaction-tab" role="tabpanel"
                             aria-hidden="true">
                            <div class="row">
                                <div class="col-7" ng-show="result_show">
                                    <h3 class="payout-amount">{{ trans('messages.account.paid_out') }}: <span
                                                ng-bind-html="paid_out"></span></h3>
                                </div>
                                <div class="col-5 export-csv-link-container">
                                    <a href="{{ url() }}/transaction_history/csv/{{ Auth::user()->user()->id }}?@{{ future_csv_param }}"
                                       class="export-csv-link" ng-show="result_show">
                                        {{ trans('messages.account.export_to_csv') }}
                                    </a>
                                </div>
                            </div>
                            <div class="row payout-filters">
                                <div class="col-12">
                                    <div class="select">
                                        {!! Form::select('payout_method', $payout_methods, '', ['class'=>'payout-method', 'placeholder'=>trans('messages.account.all_payout_methods')]) !!}
                                    </div>
                                    <div class="select">
                                        {!! Form::select('listing', $lists, '', ['class'=>'payout-listing', 'placeholder'=>trans('messages.account.all_listings')]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table transaction-table">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('messages.account.date') }}</th>
                                            <th>{{ trans('messages.account.type') }}</th>
                                            <th>{{ trans('messages.your_reservations.details') }}</th>
                                            <th>{{ trans('messages.account.pay_to') }}</th>
                                            <th>{{ trans('messages.account.amount') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody ng-show="result_show">
                                        <tr ng-repeat="item in result">
                                            <td>
                                                @{{ item.date }}
                                            </td>
                                            <td>
                                                {{ trans('messages.account.payout') }}
                                            </td>
                                            <td>
                                                {{ trans('messages.account.transfer_to') }} @{{ item.account }}
                                            </td>
                                            <td>
                                                <b><span ng-bind-html="item.currency_symbol"></span>@{{ item.amount }}
                                                </b>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <posts-pagination-transaction></posts-pagination-transaction>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="payout-eta-modal"></div>
                </div>

            </div>
        </div>

    </main>

@stop