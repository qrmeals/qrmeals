<div class="container-fluid py-2" id="orders" >
    <div class="row">
      <div class="col-12 col-xl-12 mt-3">
        <div class="card">
           
          <div class="card-header pb-0">
            <div class="row">
              <div class="col-lg-6 col-md-12 col-7">
                <h6>{{__('Orders')}}</h6>
              </div>
              <div class="col-lg-6 col-md-12 my-auto text-end">
                <a href="#" onclick="createPickupOrder()" class="btn bg-gradient-primary active" role="button" aria-pressed="true">
                  <span class="btn-inner--icon"><i class="ni ni-pin-3"></i></span>
                  <span class="btn-inner--text d-none d-sm-inline-block">{{ __('New takeaway order') }}</span>
                </a>
                <a href="#" onclick="createDeliveryOrder()" class="btn bg-gradient-primary active" role="button" aria-pressed="true">
                  <span class="btn-inner--icon"><i class="ni ni-delivery-fast"></i></span>
                  <span class="btn-inner--text d-none d-sm-inline-block">{{ __('New delivery order') }}</span>
                </a>
                <a href="#" onclick="moveOrder()" class="btn bg-gradient-default active" role="button" aria-pressed="true">
                  <span class="btn-inner--icon"><i class="ni ni-ui-04"></i></span>
                  <span class="btn-inner--text d-none d-sm-inline-block">{{ __('Move order') }}</span>
                </a>
              </div>
            </div>
          </div>
          <div class="card-header border-0">
            @if(count($orders))
              <form method="GET">
                  <div class="row align-items-center">
                      <div class="col-8">
                          <h3 class="mb-0">{{ __('Orders') }}</h3>
                      </div>
                      <div class="col-4 text-right">
                          <button id="show-hide-filters" class="btn btn-icon btn-1 btn-sm btn-outline-secondary" type="button">
                              <span class="btn-inner--icon"><i id="button-filters" class="ni ni-bold-down"></i></span>
                          </button>
                      </div>
                  </div>
                  <br/>
                  <div class="tab-content orders-filters">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="input-daterange datepicker row align-items-center">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="form-control-label">{{ __('Date From') }}</label>
                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                                  </div>
                                                  <input name="fromDate" autocomplete="off" class="form-control" placeholder="{{ __('Date from') }}" type="text" <?php if(isset($_GET['fromDate'])){echo 'value="'.$_GET['fromDate'].'"';} ?> >
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="form-control-label">{{ __('Date to') }}</label>
                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                                  </div>
                                                  <input name="toDate" autocomplete="off" class="form-control" placeholder="{{ __('Date to') }}" type="text"  <?php if(isset($_GET['toDate'])){echo 'value="'.$_GET['toDate'].'"';} ?>>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <!-- statuses -->
                              <div class="col-md-3">
                                  @include('partials.select', ['name'=>"Last status",'id'=>"status_id",'placeholder'=>"Select status",'data'=>$statuses,'required'=>false, 'value'=>''])
                              </div>

                              <!-- statuses -->
                              <div class="col-md-3">
                                  @include('partials.select', ['name'=>"Payment status",'id'=>"payment_status",'placeholder'=>"Select status",'data'=>['paid'=>__("Paid"),'unpaid'=>("Unpaid")],'required'=>false, 'value'=>''])
                              </div>   
                          </div>

                              <div class="col-md-6 offset-md-6">
                                  <div class="row">
                                      @if ($parameters)
                                          <div class="col-md-4">
                                              <a href="{{ Request::url() }}" class="btn btn-md btn-block">{{ __('Clear Filters') }}</a>
                                          </div>
                                          <div class="col-md-4">
                                          <a href="{{Request::fullUrl()."&report=true" }}" class="btn btn-md btn-success btn-block">{{ __('Download report') }}</a>
                                          </div>
                                      @else
                                          <div class="col-md-8"></div>
                                      @endif

                                      <div class="col-md-4">
                                          <button type="submit" class="btn btn-primary btn-md btn-block">{{ __('Filter') }}</button>
                                      </div>
                              </div>
                          </div>
                   </div>
              </form>
            @endif
          </div>
            <div class="table-responsive">
              {{-- <table class="table align-items-center mb-0" id="orderList"> --}}
              <table class="table align-items-center" id="orderList">
                <thead class="thead-light">
                  <tr>
                      <th scope="col">{{ __('ID') }}</th>
                      @hasrole('admin|driver')
                          <th scope="col">{{ __('Restaurant') }}</th>
                      @endif
                      <th class="table-web" scope="col">{{ __('Created') }}</th>
                      <th class="table-web" scope="col">{{ __('Time Slot') }}</th>
                      <th class="table-web" scope="col">{{ __('Method') }}</th>
                      <th scope="col">{{ __('Last status') }}</th>
                      <th scope="col">{{ __('Payment status') }}</th>
                      {{-- @hasrole('admin|owner|driver')
                          <th class="table-web" scope="col">{{ __('Client') }}</th>
                      @endif --}}
                      @if(auth()->user()->hasRole('admin'))
                          <th class="table-web" scope="col">{{ __('Address') }}</th>
                      @endif
                      @if(auth()->user()->hasRole('owner'))
                          {{-- <th class="table-web" scope="col">{{ __('Items') }}</th> --}}
                      @endif
                     {{--  @hasrole('admin|owner')
                          <th class="table-web" scope="col">{{ __('Driver') }}</th>
                      @endif --}}
                      <th class="table-web" scope="col">{{ __('Price') }}</th>
                      <th class="table-web" scope="col">{{ __('Delivery') }}</th>
                      @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('owner') || auth()->user()->hasRole('driver'))
                          <th scope="col">{{ __('Actions') }}</th>
                      @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                    <tr>
                        <td>
                            
                            <a class="btn badge badge-success badge-pill" href="{{ route('orders.show',$order->id )}}">#{{ $order->id }}</a>
                        </td>
                        @hasrole('admin|driver')
                        <th scope="row">
                            <div class="media align-items-center">
                                <a class="avatar-custom mr-3">
                                    <img class="rounded" alt="..." src={{ $order->restorant->icon }}>
                                </a>
                                <div class="media-body">
                                    <span class="mb-0 text-sm">{{ $order->restorant->name }}</span>
                                </div>
                            </div>
                        </th>
                        @endif

                        <td class="table-web">
                            {{ $order->created_at->locale(Config::get('app.locale'))->isoFormat('LLLL') }}
                        </td>
                        <td class="table-web">
                            {{ $order->time_formated }}
                        </td>
                        <td class="table-web">
                            <span class="badge badge-primary badge-pill">{{ $order->getExpeditionType() }}</span>
                        </td>
                        <td>
                            @include('orders.partials.laststatus')
                        </td>
                        <td>
                            @include('poscloud::pos.partials.paymentstatus',['status' => $order->payment_status ])
                        </td>
                        {{-- @hasrole('admin|owner|driver')
                        <td class="table-web">
                            @if ($order->client)
                                {{ $order->client->name }}
                            @else
                                {{ $order->getConfig('client_name','') }}
                            @endif
                        </td>
                        @endif --}}
                        @if(auth()->user()->hasRole('admin'))
                            <td class="table-web">
                                {{ $order->address?$order->address->address:"" }}
                            </td>
                        @endif
                        @if(auth()->user()->hasRole('owner'))
                           {{--  <td class="table-web">
                                {{ count($order->items) }}
                            </td> --}}
                        @endif
                        {{-- @hasrole('admin|owner')
                            <td class="table-web">
                                {{ !empty($order->driver->name) ? $order->driver->name : "" }}
                            </td>
                        @endif --}}
                        <td class="table-web">
                            @money( $order->order_price_with_discount, config('settings.cashier_currency'),config('settings.do_convertion'))

                        </td>
                        <td class="table-web">
                            @money( $order->delivery_price, config('settings.cashier_currency'),config('settings.do_convertion'))
                        </td>
                        @include('poscloud::pos.actions.table',['order' => $order ])
                    </tr>
                  @endforeach
                <tbody>
              </table>
              <div class="card-footer py-4">
                @if(count($orders))
                <nav class="d-flex justify-content-end" aria-label="...">
                    {{ $orders->appends(Request::all())->links() }}
                </nav>
                @else
                    <h4>{{ __('You don`t have any orders') }} ...</h4>
                @endif
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>